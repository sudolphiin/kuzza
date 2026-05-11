<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\Mpesa\MpesaDarajaStkService;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InitiateMpesaStkForUssdOrderJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public function __construct(
        public int $orderId,
        public ?string $stkPhone254 = null
    ) {}

    public function handle(MpesaDarajaStkService $mpesa): void
    {
        $order = Order::query()->find($this->orderId);
        if (! $order || $order->status !== 'awaiting_mpesa') {
            return;
        }

        $phone = $this->stkPhone254 ?? '';
        if ($phone === '') {
            $parent = User::withoutGlobalScopes()->find($order->parent_id);
            $phone = $this->normalize254((string) ($parent->phone_number ?? $parent->phone ?? ''));
        }

        if ($phone === '') {
            Log::warning('mpesa.stk_skipped_no_phone', ['order_id' => $order->id]);
            $order->update([
                'status' => 'mpesa_init_failed',
                'mpesa_result_desc' => 'No phone for STK',
            ]);

            return;
        }

        $ref = 'ORD'.$order->id;
        $desc = 'Kuzza order '.$order->id;

        $result = $mpesa->initiateStkPush($phone, (float) $order->total_amount, $ref, $desc);

        if (! $result['ok']) {
            Log::error('mpesa.stk_init_failed', ['order_id' => $order->id, 'message' => $result['message'] ?? '']);
            $order->update([
                'status' => 'mpesa_init_failed',
                'mpesa_result_desc' => substr($result['message'] ?? 'STK failed', 0, 255),
            ]);

            return;
        }

        $order->update([
            'mpesa_checkout_request_id' => $result['checkout_request_id'] ?? null,
            'mpesa_merchant_request_id' => $result['merchant_request_id'] ?? null,
        ]);
    }

    protected function normalize254(string $raw): string
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
}
