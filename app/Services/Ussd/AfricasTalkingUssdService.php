<?php

namespace App\Services\Ussd;

use App\Models\MyBidhaaOrder;
use App\Models\Order;
use App\Models\OrderItem;
use App\ParentRecommendedItem;
use App\SmParent;
use App\SmStudent;
use App\User;
use App\Jobs\InitiateMpesaStkForUssdOrderJob;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Wallet\Entities\WalletTransaction;

/**
 * Kuzza parent USSD commerce (*384*20156# is configured on Africa's Talking, not here).
 *
 * @see internal USSD core logic doc (parent MSISDN auth, students, cart, checkout, Daraja STK).
 */
class AfricasTalkingUssdService
{
    protected const MAX_ITEMS = 5;

    /** @var list<string>|null */
    protected static ?array $userPhoneColumns = null;

    /** @var list<string>|null */
    protected static ?array $parentUserBaseColumns = null;

    public function __construct(
        protected UssdSessionRepository $sessions,
        protected UssdResponseBuilder $response
    ) {}

    public function handle(Request $request): string
    {
        $payload = array_change_key_case($this->collectRequestPayload($request), CASE_LOWER);
        $sessionId = (string) ($payload['sessionid'] ?? '');
        $phoneNumber = (string) ($payload['phonenumber'] ?? '');
        $text = (string) ($payload['text'] ?? $payload['userinput'] ?? $payload['fulltext'] ?? $payload['input'] ?? '');

        if ($sessionId === '' || $phoneNumber === '') {
            Log::warning('ussd.africastalking.missing_fields', [
                'payload_keys' => array_keys($payload),
                'content_type' => $request->header('Content-Type'),
            ]);

            return $this->response->end('Kuzza setup error. Check callback URL.');
        }

        $this->logIncoming($sessionId, $phoneNumber, $text);

        $msisdnPlus = $this->normalizeMsisdnPlus($phoneNumber);
        $segment = $this->latestSegment($text);

        $session = $this->sessions->get($sessionId);

        if (($session['step'] ?? null) === null) {
            $parent = $this->resolveParentUser($phoneNumber);
            if (! $parent) {
                return $this->response->end('Your number is not registered on Kuzza. Please contact your school.');
            }

            $students = $this->loadParentStudents($parent);
            if ($students->isEmpty()) {
                return $this->response->end('No students linked to this account. Please contact your school.');
            }

            $base = [
                'step' => 'main_menu',
                'parent_user_id' => $parent->id,
                'msisdn' => $msisdnPlus,
                'cart' => [],
            ];

            if ($students->count() === 1) {
                $base['student_user_id'] = (int) $students->first()->user_id;
                $this->sessions->put($sessionId, $base);

                return $this->response->continuation($this->mainMenuBody());
            }

            $pick = [];
            $lines = ['Select Student:'];
            $i = 1;
            foreach ($students as $stu) {
                $pick[(string) $i] = (int) $stu->user_id;
                $lines[] = $i.'.'.$this->studentLineLabel($stu);
                if ($i >= 7) {
                    break;
                }
                $i++;
            }
            $base['step'] = 'student_select';
            $base['student_pick_map'] = $pick;
            $this->sessions->put($sessionId, $base);

            return $this->response->continuation(implode("\n", $lines));
        }

        $parent = User::withoutGlobalScopes()->find((int) ($session['parent_user_id'] ?? 0));
        if (! $parent) {
            $this->sessions->forget($sessionId);

            return $this->response->end('Session expired. Dial again.');
        }

        return match ($session['step'] ?? '') {
            'student_select' => $this->handleStudentSelect($sessionId, $session, $segment, $parent),
            'main_menu' => $this->handleMainMenu($sessionId, $session, $segment, $parent),
            'wallet_detail' => $this->handleWalletDetail($sessionId, $session, $segment, $parent),
            'order_history' => $this->handleOrderHistory($sessionId, $session, $segment, $parent),
            'paylater_info' => $this->handlePaylaterInfo($sessionId, $session, $segment),
            'items_list' => $this->handleItemsList($sessionId, $session, $segment, $parent),
            'item_detail' => $this->handleItemDetail($sessionId, $session, $segment, $parent),
            'checkout_payment' => $this->handleCheckoutPayment($sessionId, $session, $segment, $parent, $phoneNumber),
            default => $this->resetAndEnd($sessionId),
        };
    }

    protected function handleStudentSelect(string $sessionId, array $session, ?string $segment, User $parent): string
    {
        $map = $session['student_pick_map'] ?? [];
        if ($segment === null || $segment === '' || ! isset($map[$segment])) {
            return $this->response->continuation('Pick a valid student number.');
        }

        $session['student_user_id'] = (int) $map[$segment];
        $session['step'] = 'main_menu';
        unset($session['student_pick_map']);
        $this->sessions->put($sessionId, $session);

        return $this->response->continuation($this->mainMenuBody());
    }

    protected function handleMainMenu(string $sessionId, array $session, ?string $segment, User $parent): string
    {
        if ($segment === null || $segment === '') {
            return $this->response->continuation($this->mainMenuBody());
        }

        return match ($segment) {
            '1' => $this->enterItemsList($sessionId, $session, $parent),
            '2' => $this->showWallet($sessionId, $session, $parent),
            '3' => $this->showOrderHistory($sessionId, $session, $parent),
            '4' => $this->showPaylaterInfo($sessionId, $session),
            default => $this->response->continuation("Pick 1-4.\n".$this->mainMenuBody()),
        };
    }

    protected function handleWalletDetail(string $sessionId, array $session, ?string $segment, User $parent): string
    {
        if ($segment === '0' || $segment === null || $segment === '') {
            $session['step'] = 'main_menu';
            $this->sessions->put($sessionId, $session);

            return $this->response->continuation($this->mainMenuBody());
        }

        return $this->response->continuation($this->walletBody($parent));
    }

    protected function handleOrderHistory(string $sessionId, array $session, ?string $segment, User $parent): string
    {
        if ($segment === '0' || $segment === null || $segment === '') {
            $session['step'] = 'main_menu';
            $this->sessions->put($sessionId, $session);

            return $this->response->continuation($this->mainMenuBody());
        }

        return $this->response->continuation($this->orderHistoryBody($parent));
    }

    protected function handlePaylaterInfo(string $sessionId, array $session, ?string $segment): string
    {
        if ($segment === '0' || $segment === null || $segment === '') {
            $session['step'] = 'main_menu';
            $this->sessions->put($sessionId, $session);

            return $this->response->continuation($this->mainMenuBody());
        }

        return $this->response->continuation("Pay Later account:\nContact your school.\n0.Menu");
    }

    protected function handleItemsList(string $sessionId, array $session, ?string $segment, User $parent): string
    {
        $studentUserId = (int) ($session['student_user_id'] ?? 0);
        $items = $this->assignableItems($parent, $studentUserId);

        if ($items->isEmpty()) {
            $session['step'] = 'main_menu';
            $this->sessions->put($sessionId, $session);
            $name = $this->studentFirstName($parent, $studentUserId);

            return $this->response->continuation("No items assigned for {$name} yet.\n".$this->mainMenuBody());
        }

        if ($segment === null || $segment === '') {
            return $this->response->continuation($this->itemsListBody($session, $items, $studentUserId));
        }

        if ($segment === '0') {
            $session['step'] = 'main_menu';
            $this->sessions->put($sessionId, $session);

            return $this->response->continuation($this->mainMenuBody());
        }

        $count = min($items->count(), self::MAX_ITEMS);
        $checkoutAllKey = (string) ($count + 1);
        $cartCheckoutKey = '8';
        $cart = $session['cart'] ?? [];

        if ($segment === $checkoutAllKey) {
            $ids = $items->pluck('id')->map(fn ($id) => (int) $id)->all();
            $session['cart'] = $ids;
            $session['step'] = 'checkout_payment';
            $this->sessions->put($sessionId, $session);

            return $this->response->continuation($this->checkoutPaymentBody($session, $parent));
        }

        if ($segment === $cartCheckoutKey && $cart !== []) {
            $session['step'] = 'checkout_payment';
            $this->sessions->put($sessionId, $session);

            return $this->response->continuation($this->checkoutPaymentBody($session, $parent));
        }

        $idx = (int) $segment;
        if ($idx >= 1 && $idx <= $count) {
            $row = $items->values()->get($idx - 1);
            if (! $row) {
                return $this->response->continuation($this->itemsListBody($session, $items, $studentUserId));
            }
            $session['step'] = 'item_detail';
            $session['detail_item_id'] = (int) $row->id;
            $this->sessions->put($sessionId, $session);

            return $this->response->continuation($this->itemDetailBody($row));
        }

        return $this->response->continuation($this->itemsListBody($session, $items, $studentUserId));
    }

    protected function handleItemDetail(string $sessionId, array $session, ?string $segment, User $parent): string
    {
        $itemId = (int) ($session['detail_item_id'] ?? 0);
        $studentUserId = (int) ($session['student_user_id'] ?? 0);
        $row = ParentRecommendedItem::query()->whereKey($itemId)->first();
        if (! $this->recommendedItemBelongsToParentStudent($row, $parent, $studentUserId)) {
            $session['step'] = 'items_list';
            unset($session['detail_item_id']);
            $this->sessions->put($sessionId, $session);

            return $this->response->continuation($this->itemsListBody($session, $this->assignableItems($parent, $studentUserId), $studentUserId));
        }

        if ($segment === '1') {
            $cart = $session['cart'] ?? [];
            if (! in_array($itemId, $cart, true)) {
                $cart[] = $itemId;
            }
            $session['cart'] = $cart;
            $session['step'] = 'items_list';
            unset($session['detail_item_id']);
            $this->sessions->put($sessionId, $session);

            return $this->response->continuation("Added.\n".$this->itemsListBody($session, $this->assignableItems($parent, $studentUserId), $studentUserId));
        }

        if ($segment === '2' || $segment === null || $segment === '') {
            $session['step'] = 'items_list';
            unset($session['detail_item_id']);
            $this->sessions->put($sessionId, $session);

            return $this->response->continuation($this->itemsListBody($session, $this->assignableItems($parent, $studentUserId), $studentUserId));
        }

        return $this->response->continuation($this->itemDetailBody($row));
    }

    protected function handleCheckoutPayment(string $sessionId, array $session, ?string $segment, User $parent, string $rawPhone): string
    {
        $studentUserId = (int) ($session['student_user_id'] ?? 0);
        $cart = array_values(array_filter(array_map('intval', $session['cart'] ?? [])));
        if ($cart === []) {
            $session['step'] = 'items_list';
            $this->sessions->put($sessionId, $session);

            return $this->response->continuation("Cart empty.\n".$this->itemsListBody($session, $this->assignableItems($parent, $studentUserId), $studentUserId));
        }

        if ($segment === null || $segment === '') {
            return $this->response->continuation($this->checkoutPaymentBody($session, $parent));
        }

        if ($segment === '4') {
            $session['cart'] = [];
            $session['step'] = 'items_list';
            $this->sessions->put($sessionId, $session);

            return $this->response->continuation('Cancelled.'."\n".$this->itemsListBody($session, $this->assignableItems($parent, $studentUserId), $studentUserId));
        }

        if ($segment === '1') {
            return $this->payWithMpesa($sessionId, $session, $parent, $cart, $rawPhone);
        }

        if ($segment === '2') {
            return $this->payWithWallet($sessionId, $session, $parent, $cart);
        }

        if ($segment === '3') {
            return $this->payWithPayLater($sessionId, $session, $parent, $cart);
        }

        return $this->response->continuation($this->checkoutPaymentBody($session, $parent));
    }

    protected function payWithMpesa(string $sessionId, array $session, User $parent, array $cart, string $rawPhone): string
    {
        [$order, $err] = $this->placeRecommendedOrder($parent, (int) $session['student_user_id'], $cart, 'mpesa');
        if ($err !== null) {
            return $this->response->end($err);
        }

        $stkPhone = $this->normalizeMsisdn254Digits($rawPhone);
        $studentUserId = (int) $session['student_user_id'];
        $first = $this->studentFirstName($parent, $studentUserId);
        // Run STK after the HTTP response is flushed so the USSD gateway does not wait
        // on Daraja (avoids Safaricom "technical problems" / timeout on slow networks).
        $orderId = $order->id;
        app()->terminating(static function () use ($orderId, $stkPhone): void {
            InitiateMpesaStkForUssdOrderJob::dispatch($orderId, $stkPhone);
        });
        $this->sessions->forget($sessionId);

        return $this->response->end('Request sent. Enter M-Pesa PIN on popup to pay for '.$first.'.');
    }

    protected function payWithWallet(string $sessionId, array $session, User $parent, array $cart): string
    {
        $total = $this->cartTotal($parent, (int) $session['student_user_id'], $cart);
        $balance = (float) ($parent->wallet_balance ?? 0);
        if ($balance < $total) {
            return $this->response->end('Insufficient Kuzza Wallet balance. Top up first.');
        }

        [$order, $err] = $this->placeRecommendedOrder($parent, (int) $session['student_user_id'], $cart, 'kuzza_wallet');
        if ($err !== null) {
            return $this->response->end($err);
        }

        $this->sessions->forget($sessionId);

        return $this->response->end('Paid KES '.number_format($total, 0).' from wallet. Order #'.$order->id.'.');
    }

    protected function payWithPayLater(string $sessionId, array $session, User $parent, array $cart): string
    {
        [$order, $err] = $this->placeRecommendedOrder($parent, (int) $session['student_user_id'], $cart, 'pay_later');
        if ($err !== null) {
            return $this->response->end($err);
        }

        $this->sessions->forget($sessionId);

        return $this->response->end('Pay Later order #'.$order->id.' placed. Contact school for terms.');
    }

    /**
     * @param  list<int>  $cart
     * @return array{0: ?Order, 1: ?string}
     */
    protected function placeRecommendedOrder(User $parent, int $studentUserId, array $cart, string $paymentMethod): array
    {
        $items = ParentRecommendedItem::query()
            ->whereIn('id', $cart)
            ->whereIn('parent_id', $this->parentAssignmentIdKeys($parent))
            ->whereIn('student_id', $this->studentAssignmentIdKeys($studentUserId))
            ->whereIn('status', ['pending', 'selected_for_order'])
            ->with('recommendedItem', 'student')
            ->get();

        if ($items->isEmpty() || $items->count() !== count(array_unique($cart))) {
            return [null, 'Invalid cart. Try again.'];
        }

        $total = (float) $items->sum(function ($item) {
            $qty = (int) ($item->assigned_quantity ?: 1);
            $price = $item->recommendedItem ? (float) $item->recommendedItem->price : 0;

            return $price * $qty;
        });

        try {
            DB::beginTransaction();

            $status = match ($paymentMethod) {
                'kuzza_wallet' => 'paid',
                'pay_later' => 'pay_later',
                'mpesa' => 'awaiting_mpesa',
                default => 'pending',
            };

            $order = Order::create([
                'parent_id' => $parent->id,
                'student_id' => $studentUserId,
                'total_amount' => $total,
                'status' => $status,
                'external_source' => 'ussd',
                'notes' => $paymentMethod === 'mpesa' ? 'USSD M-Pesa STK' : null,
            ]);

            foreach ($items as $item) {
                $qty = (int) ($item->assigned_quantity ?: 1);
                $price = $item->recommendedItem ? (float) $item->recommendedItem->price : 0;

                OrderItem::create([
                    'order_id' => $order->id,
                    'recommended_item_id' => $item->recommended_item_id,
                    'parent_recommended_item_id' => $item->id,
                    'quantity' => $qty,
                    'price' => $price,
                ]);

                $item->status = in_array($paymentMethod, ['kuzza_wallet', 'pay_later'], true) ? 'ordered' : 'awaiting_payment';
                $item->save();
            }

            if ($paymentMethod === 'kuzza_wallet') {
                $currentBalance = (float) ($parent->wallet_balance ?: 0);
                if ($currentBalance < $total) {
                    DB::rollBack();

                    return [null, 'Insufficient wallet balance.'];
                }

                $parent->wallet_balance = $currentBalance - $total;
                $parent->save();

                $walletTransaction = new WalletTransaction;
                $walletTransaction->amount = $total;
                $walletTransaction->expense = $total;
                $walletTransaction->payment_method = 'Kuzza Wallet';
                $walletTransaction->user_id = $parent->id;
                $walletTransaction->note = 'USSD recommended items order #'.$order->id;
                $walletTransaction->type = 'expense';
                $walletTransaction->status = 'approve';
                $walletTransaction->created_by = $parent->id;
                $walletTransaction->academic_id = $parent->academic_id ?? 1;
                $walletTransaction->school_id = $parent->school_id ?? 1;
                $walletTransaction->save();
            }

            try {
                $code = 'SCH-USSD-'.now()->format('YmdHis').'-'.$order->id;
                $ecOrder = MyBidhaaOrder::create([
                    'code' => $code,
                    'user_id' => $parent->id,
                    'status' => $paymentMethod === 'kuzza_wallet' ? 'paid' : 'pending',
                    'amount' => $total,
                    'sub_total' => $total,
                    'tax_amount' => 0,
                    'shipping_amount' => 0,
                    'payment_fee' => 0,
                    'description' => 'USSD school order',
                    'store_id' => 1,
                ]);
                $order->external_order_id = $ecOrder->id ?? null;
                $order->external_order_code = $code;
                $order->save();
            } catch (\Throwable $e) {
                Log::error('ussd.mybidhaa_order_failed', ['message' => $e->getMessage()]);
            }

            DB::commit();

            return [$order, null];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('ussd.place_order_failed', ['message' => $e->getMessage()]);

            return [null, 'Could not place order. Try again.'];
        }
    }

    protected function cartTotal(User $parent, int $studentUserId, array $cart): float
    {
        $items = ParentRecommendedItem::query()
            ->whereIn('id', $cart)
            ->whereIn('parent_id', $this->parentAssignmentIdKeys($parent))
            ->whereIn('student_id', $this->studentAssignmentIdKeys($studentUserId))
            ->with('recommendedItem')
            ->get();

        return (float) $items->sum(function ($item) {
            $qty = (int) ($item->assigned_quantity ?: 1);
            $price = $item->recommendedItem ? (float) $item->recommendedItem->price : 0;

            return $price * $qty;
        });
    }

    protected function enterItemsList(string $sessionId, array $session, User $parent): string
    {
        $studentUserId = (int) ($session['student_user_id'] ?? 0);
        if ($studentUserId === 0) {
            return $this->response->continuation('Pick a student first.');
        }

        $session['step'] = 'items_list';
        $this->sessions->put($sessionId, $session);
        $items = $this->assignableItems($parent, $studentUserId);

        return $this->response->continuation($this->itemsListBody($session, $items, $studentUserId));
    }

    protected function showWallet(string $sessionId, array $session, User $parent): string
    {
        $session['step'] = 'wallet_detail';
        $this->sessions->put($sessionId, $session);

        return $this->response->continuation($this->walletBody($parent));
    }

    protected function showOrderHistory(string $sessionId, array $session, User $parent): string
    {
        $session['step'] = 'order_history';
        $this->sessions->put($sessionId, $session);

        return $this->response->continuation($this->orderHistoryBody($parent));
    }

    protected function showPaylaterInfo(string $sessionId, array $session): string
    {
        $session['step'] = 'paylater_info';
        $this->sessions->put($sessionId, $session);

        return $this->response->continuation("Pay Later account:\nContact your school.\n0.Menu");
    }

    protected function mainMenuBody(): string
    {
        return "Welcome to Kuzza.\n1.View assigned items\n2.Wallet\n3.Orders\n4.Pay Later";
    }

    protected function walletBody(User $parent): string
    {
        $bal = (float) ($parent->wallet_balance ?? 0);

        return 'Wallet KES '.number_format($bal, 0).".\n0.Menu";
    }

    protected function orderHistoryBody(User $parent): string
    {
        $rows = Order::query()->where('parent_id', $parent->id)->latest()->take(3)->get();
        if ($rows->isEmpty()) {
            return "No orders yet.\n0.Menu";
        }
        $lines = ['Recent orders:'];
        foreach ($rows as $r) {
            $lines[] = '#'.$r->id.' '.substr((string) $r->status, 0, 10).' KES'.number_format((float) $r->total_amount, 0);
        }
        $lines[] = '0.Menu';

        return implode("\n", $lines);
    }

    protected function itemsListBody(array $session, Collection $items, int $studentUserId): string
    {
        $name = $this->studentFirstName(User::withoutGlobalScopes()->find($session['parent_user_id']), $studentUserId);
        $slice = $items->take(self::MAX_ITEMS)->values();
        $lines = ['Items '.$name.':'];
        $i = 1;
        foreach ($slice as $row) {
            $label = $row->recommendedItem?->item_name ?? 'Item';
            $label = $this->truncate($label, 22);
            $price = (float) ($row->recommendedItem?->price ?? 0);
            $lines[] = $i.'.'.$label.' KES'.number_format($price, 0);
            $i++;
        }
        $n = $slice->count();
        if ($n > 0) {
            $lines[] = ($n + 1).'.Checkout all';
        }
        if (($session['cart'] ?? []) !== []) {
            $lines[] = '8.Checkout cart';
        }
        $lines[] = '0.Menu';

        return implode("\n", $lines);
    }

    protected function itemDetailBody(ParentRecommendedItem $row): string
    {
        $name = $row->recommendedItem?->item_name ?? 'Item';
        $name = $this->truncate($name, 28);
        $price = (float) ($row->recommendedItem?->price ?? 0);

        return $name."\nKES ".number_format($price, 0)."\n1.Add cart\n2.Back";
    }

    protected function checkoutPaymentBody(array $session, User $parent): string
    {
        $total = $this->cartTotal($parent, (int) $session['student_user_id'], $session['cart'] ?? []);

        return 'Total KES '.number_format($total, 0).".\nPay:\n1.M-Pesa\n2.Wallet\n3.Pay Later\n4.Cancel";
    }

    protected function studentFirstName(User $parent, int $studentUserId): string
    {
        $stu = SmStudent::withoutGlobalScopes()
            ->where('user_id', $studentUserId)
            ->with('user')
            ->first();

        return $this->truncate($this->studentDisplayName($stu?->user, $stu), 14);
    }

    /**
     * Infix/Kuzza stores the label on users.full_name (not users.name).
     */
    protected function studentDisplayName(?User $user, ?SmStudent $student): string
    {
        if ($user) {
            $n = trim((string) ($user->full_name ?? ''));
            if ($n !== '') {
                return $n;
            }
        }
        if ($student) {
            $n = trim((string) ($student->full_name ?? ''));
            if ($n !== '') {
                return $n;
            }
            $n = trim(trim((string) ($student->first_name ?? '')).' '.trim((string) ($student->last_name ?? '')));
            if ($n !== '') {
                return $n;
            }
        }

        return 'Student';
    }

    protected function studentLineLabel(SmStudent $student): string
    {
        $name = $this->truncate($this->studentDisplayName($student->user, $student), 16);
        $rec = $student->relationLoaded('studentRecords')
            ? $student->studentRecords->sortByDesc('id')->first()
            : $student->studentRecords()->with('class')->orderByDesc('id')->first();
        $class = $rec && $rec->class ? $this->truncate((string) $rec->class->class_name, 8) : '';

        return $class !== '' ? $name.' ('.$class.')' : $name;
    }

    protected function assignableItems(User $parent, int $studentUserId): Collection
    {
        $schoolId = (int) $parent->school_id;
        $parentKeys = $this->parentAssignmentIdKeys($parent);
        $studentKeys = $this->studentAssignmentIdKeys($studentUserId);

        if ($parentKeys === [] || $studentKeys === []) {
            return collect();
        }

        return ParentRecommendedItem::query()
            ->whereHas('recommendedItem', function ($q) use ($schoolId): void {
                $q->where('school_id', $schoolId)->where('is_active', true);
            })
            ->whereIn('parent_id', $parentKeys)
            ->whereIn('student_id', $studentKeys)
            ->whereIn('status', ['pending', 'selected_for_order'])
            ->with('recommendedItem')
            ->orderBy('id')
            ->get();
    }

    /**
     * parent_recommended_items.parent_id references users.id; legacy rows may use sm_parents.id.
     *
     * @return list<int>
     */
    protected function parentAssignmentIdKeys(User $parent): array
    {
        $ids = [(int) $parent->id];
        $profileId = SmParent::withoutGlobalScopes()->where('user_id', $parent->id)->value('id');
        if ($profileId && (int) $profileId !== (int) $parent->id) {
            $ids[] = (int) $profileId;
        }

        return array_values(array_unique(array_filter($ids)));
    }

    /**
     * parent_recommended_items.student_id references users.id (student user); legacy rows may use sm_students.id.
     *
     * @return list<int>
     */
    protected function studentAssignmentIdKeys(int $studentUserId): array
    {
        if ($studentUserId <= 0) {
            return [];
        }

        $ids = [$studentUserId];
        $smStudentId = SmStudent::withoutGlobalScopes()->where('user_id', $studentUserId)->value('id');
        if ($smStudentId && (int) $smStudentId !== $studentUserId) {
            $ids[] = (int) $smStudentId;
        }

        return array_values(array_unique(array_filter($ids)));
    }

    protected function recommendedItemBelongsToParentStudent(?ParentRecommendedItem $row, User $parent, int $studentUserId): bool
    {
        if (! $row) {
            return false;
        }

        return in_array((int) $row->parent_id, $this->parentAssignmentIdKeys($parent), true)
            && in_array((int) $row->student_id, $this->studentAssignmentIdKeys($studentUserId), true);
    }

    protected function loadParentStudents(User $parent): Collection
    {
        $parentProfile = SmParent::withoutGlobalScopes()->where('user_id', $parent->id)->first();
        if (! $parentProfile) {
            return collect();
        }

        return SmStudent::withoutGlobalScopes()
            ->where('parent_id', $parentProfile->id)
            ->where('active_status', 1)
            ->with(['user', 'studentRecords.class'])
            ->orderBy('id')
            ->get();
    }

    protected function resolveParentUser(string $phoneNumber): ?User
    {
        $variants = $this->phoneVariants($phoneNumber);
        if ($variants === []) {
            return null;
        }

        $roleId = (int) config('ussd.parent_role_id', 3);
        $phoneCols = $this->userPhoneColumns();
        $select = array_values(array_unique(array_merge(
            $this->resolveParentUserBaseColumns(),
            $phoneCols
        )));

        $query = User::withoutGlobalScopes()
            ->select($select)
            ->where('role_id', $roleId)
            ->where(function ($q) use ($variants, $phoneCols): void {
                foreach ($variants as $v) {
                    foreach ($phoneCols as $column) {
                        $q->orWhere($column, $v);
                    }
                }
            });

        $schoolId = config('ussd.default_school_id');
        if ($schoolId !== null && $schoolId !== '') {
            $query->where('school_id', (int) $schoolId);
        }

        return $query->first();
    }

    /**
     * Columns for parent User SELECT — from config only (no Schema::hasColumn;
     * information_schema lookups can take 10–60s on Windows/MySQL and break AT’s 10s limit).
     *
     * @return list<string>
     */
    protected function resolveParentUserBaseColumns(): array
    {
        if (self::$parentUserBaseColumns !== null) {
            return self::$parentUserBaseColumns;
        }

        $cols = config('ussd.parent_user_select_columns', []);
        if (! is_array($cols) || $cols === []) {
            $cols = ['id', 'full_name', 'email', 'username', 'role_id', 'school_id', 'active_status', 'wallet_balance', 'phone_number'];
        }

        return self::$parentUserBaseColumns = array_values(array_unique($cols));
    }

    /**
     * @return list<string>
     */
    protected function userPhoneColumns(): array
    {
        if (self::$userPhoneColumns !== null) {
            return self::$userPhoneColumns;
        }

        $configured = config('ussd.user_phone_columns_env');
        if (is_string($configured) && trim($configured) !== '') {
            $cols = array_values(array_filter(array_map('trim', explode(',', $configured))));
            self::$userPhoneColumns = $cols !== [] ? $cols : ['phone_number'];

            return self::$userPhoneColumns;
        }

        // Default: phone_number only. Set USSD_USER_PHONE_COLUMNS=phone_number,phone if needed.
        self::$userPhoneColumns = ['phone_number'];

        return self::$userPhoneColumns;
    }

    /**
     * @return list<string>
     */
    protected function phoneVariants(string $raw): array
    {
        $digits = preg_replace('/\D+/', '', $raw) ?? '';
        if ($digits === '') {
            return [];
        }

        $variants = [$raw, $digits, '+'.$digits];

        if (str_starts_with($digits, '254')) {
            $variants[] = '0'.substr($digits, 3);
            $variants[] = '+254'.substr($digits, 3);
        }

        if (str_starts_with($digits, '0') && strlen($digits) >= 9) {
            $variants[] = '254'.substr($digits, 1);
            $variants[] = '+254'.substr($digits, 1);
        }

        if (strlen($digits) === 9 && str_starts_with($digits, '7')) {
            $variants[] = '254'.$digits;
            $variants[] = '+254'.$digits;
            $variants[] = '0'.$digits;
        }

        return array_values(array_unique(array_filter($variants)));
    }

    protected function normalizeMsisdnPlus(string $raw): string
    {
        $digits = preg_replace('/\D+/', '', $raw) ?? '';
        if ($digits === '') {
            return '';
        }
        if (str_starts_with($digits, '254')) {
            return '+'.$digits;
        }
        if (str_starts_with($digits, '0')) {
            return '+254'.substr($digits, 1);
        }
        if (strlen($digits) === 9) {
            return '+254'.$digits;
        }

        return '+'.$digits;
    }

    protected function normalizeMsisdn254Digits(string $raw): string
    {
        $digits = preg_replace('/\D+/', '', $raw) ?? '';
        if ($digits === '') {
            return '';
        }
        if (str_starts_with($digits, '254')) {
            return $digits;
        }
        if (str_starts_with($digits, '0')) {
            return '254'.substr($digits, 1);
        }
        if (strlen($digits) === 9) {
            return '254'.$digits;
        }

        return $digits;
    }

    protected function truncate(string $text, int $max): string
    {
        if (strlen($text) <= $max) {
            return $text;
        }

        return substr($text, 0, max(1, $max - 3)).'...';
    }

    /**
     * @return array<string, mixed>
     */
    protected function collectRequestPayload(Request $request): array
    {
        $payload = [];

        $raw = $request->getContent();
        if (is_string($raw) && $raw !== '') {
            $trim = ltrim($raw);
            if ($trim !== '' && ($trim[0] === '{' || $trim[0] === '[')) {
                $decoded = json_decode($raw, true);
                if (is_array($decoded)) {
                    if (isset($decoded['data']) && is_array($decoded['data'])) {
                        $payload = array_merge($payload, $decoded['data']);
                    } else {
                        $payload = array_merge($payload, $decoded);
                    }
                }
            }
        }

        return array_merge($payload, $request->all());
    }

    protected function latestSegment(string $text): ?string
    {
        $text = trim($text);
        if ($text === '') {
            return null;
        }

        $parts = explode('*', $text);
        $last = trim((string) end($parts));

        return $last === '' ? null : $last;
    }

    protected function logIncoming(string $sessionId, string $phoneNumber, string $text): void
    {
        $masked = strlen($phoneNumber) > 6
            ? substr($phoneNumber, 0, 4).'****'.substr($phoneNumber, -2)
            : '****';

        Log::channel(config('logging.default'))->info('ussd.africastalking', [
            'sessionId' => substr(hash('sha256', $sessionId), 0, 12),
            'phone' => $masked,
            'text' => $text,
        ]);
    }

    protected function resetAndEnd(string $sessionId): string
    {
        $this->sessions->forget($sessionId);

        return $this->response->end('Session reset. Dial again.');
    }
}
