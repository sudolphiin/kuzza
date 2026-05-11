<?php

namespace App\Http\Controllers\Mpesa;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\ParentRecommendedItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MpesaStkCallbackController extends Controller
{
    public function handle(Request $request): Response
    {
        $payload = $request->json()->all();
        if ($payload === []) {
            $decoded = json_decode($request->getContent(), true);
            $payload = is_array($decoded) ? $decoded : [];
        }

        $cb = $payload['Body']['stkCallback'] ?? null;
        if (! is_array($cb)) {
            return response('OK', 200);
        }

        $checkoutId = $cb['CheckoutRequestID'] ?? null;
        $resultCode = isset($cb['ResultCode']) ? (int) $cb['ResultCode'] : null;
        $metadata = $cb['CallbackMetadata']['Item'] ?? [];

        $receipt = null;
        if (is_array($metadata)) {
            foreach ($metadata as $item) {
                if (is_array($item) && ($item['Name'] ?? '') === 'MpesaReceiptNumber') {
                    $receipt = $item['Value'] ?? null;
                    break;
                }
            }
        }

        if (! is_string($checkoutId) || $checkoutId === '') {
            return response('OK', 200);
        }

        $order = Order::query()->where('mpesa_checkout_request_id', $checkoutId)->first();
        if (! $order) {
            Log::notice('mpesa.stk_callback_unknown_order', ['checkout' => substr($checkoutId, 0, 8)]);

            return response('OK', 200);
        }

        $desc = isset($cb['ResultDesc']) ? substr((string) $cb['ResultDesc'], 0, 255) : null;

        if ($resultCode === 0) {
            $this->markOrderPaid($order, (string) ($resultCode ?? ''), $desc, is_string($receipt) ? $receipt : null);
        } else {
            $order->update([
                'status' => 'mpesa_failed',
                'mpesa_result_code' => (string) $resultCode,
                'mpesa_result_desc' => $desc,
            ]);
            $ids = $order->items()->pluck('parent_recommended_item_id')->filter()->all();
            if ($ids !== []) {
                ParentRecommendedItem::query()
                    ->whereIn('id', $ids)
                    ->where('status', 'awaiting_payment')
                    ->update(['status' => 'pending']);
            }
        }

        return response('OK', 200);
    }

    protected function markOrderPaid(Order $order, string $resultCode, ?string $desc, ?string $receipt): void
    {
        if ($order->status === 'paid') {
            return;
        }

        DB::transaction(function () use ($order, $resultCode, $desc, $receipt): void {
            $order->refresh();
            if ($order->status === 'paid') {
                return;
            }

            $order->load('items');
            $order->update([
                'status' => 'paid',
                'mpesa_result_code' => $resultCode,
                'mpesa_result_desc' => $desc,
                'mpesa_receipt_number' => $receipt,
            ]);

            foreach ($order->items as $line) {
                if ($line->parent_recommended_item_id) {
                    ParentRecommendedItem::query()->whereKey($line->parent_recommended_item_id)->update(['status' => 'ordered']);
                }
            }
        });

        Log::info('mpesa.stk_order_paid', ['order_id' => $order->id, 'receipt' => $receipt]);
    }
}
