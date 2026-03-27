@extends('backEnd.master')
@section('title')
    Recommended Items Checkout
@endsection

@section('mainContent')
<section class="student-details">
    <div class="container-fluid p-0">
        <div class="white-box">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-15">Checkout</h3>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 text-right">
                    <a href="{{ route('parent-recommended-items-cart') }}" class="secondary-btn fix-gr-bg">
                        <span class="ti-arrow-left mr-2"></span>
                        Back to Cart
                    </a>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mt-20">
                    <strong>Please fix the errors below and try again.</strong>
                    <ul class="mb-0 mt-10">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('parent-recommended-items-cart.pay') }}" id="checkout-form">
                @csrf
                @foreach($item_ids as $id)
                    <input type="hidden" name="item_ids[]" value="{{ $id }}">
                @endforeach

                <div class="row mt-20">
                    <div class="col-lg-7">
                        <div class="white-box" style="border: 2px solid #e9ecef; border-radius: 8px;">
                            <h5 style="margin-bottom: 15px; padding-bottom: 12px; border-bottom: 1px solid #eee;">Order Summary</h5>

                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead style="background-color: #f8f9fa;">
                                        <tr>
                                            <th>Item</th>
                                            <th>Student</th>
                                            <th class="text-right">Qty</th>
                                            <th class="text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                            @php
                                                $qty = (int) ($item->assigned_quantity ?: 1);
                                                $price = (float) optional($item->recommendedItem)->price;
                                                $line = $qty * $price;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <strong>{{ optional($item->recommendedItem)->item_name }}</strong>
                                                    <div class="text-muted" style="font-size: 12px;">
                                                        {{ ucfirst(str_replace('_', ' ', optional($item->recommendedItem)->item_type)) }}
                                                    </div>
                                                </td>
                                                <td>{{ optional($item->student)->full_name }}</td>
                                                <td class="text-right"><strong>{{ $qty }}</strong></td>
                                                <td class="text-right">
                                                    <strong>
                                                        @if(isset($currency))
                                                            {{ $currency->currency_symbol }}{{ number_format($line, 2) }}
                                                        @else
                                                            {{ number_format($line, 2) }}
                                                        @endif
                                                    </strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div style="border-top: 1px solid #eee; padding-top: 15px; margin-top: 15px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                    <span>Total Items:</span>
                                    <strong>{{ count($items) }}</strong>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 18px;">
                                    <span>Grand Total:</span>
                                    <strong style="color: var(--base_color, #20b2aa);">
                                        @if(isset($currency))
                                            {{ $currency->currency_symbol }}{{ number_format($total, 2) }}
                                        @else
                                            {{ number_format($total, 2) }}
                                        @endif
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="white-box" style="border: 2px solid #e9ecef; border-radius: 8px;">
                            <h5 style="margin-bottom: 15px; padding-bottom: 12px; border-bottom: 1px solid #eee;">Payment Method</h5>

                            <div class="mb-15">
                                <label class="primary_input_label d-block mb-2">Select how you want to pay</label>

                                <div class="d-flex align-items-center mb-10" style="gap: 10px;">
                                    <input type="radio" name="payment_method" id="pm_mpesa" value="mpesa" {{ old('payment_method', 'mpesa') === 'mpesa' ? 'checked' : '' }}>
                                    <label for="pm_mpesa" class="mb-0">
                                        <strong>MPESA</strong>
                                        <div class="text-muted" style="font-size: 12px;">You’ll receive a payment prompt on your phone.</div>
                                    </label>
                                </div>

                                <div id="mpesa-fields" class="mb-15" style="padding-left: 24px;">
                                    <label class="primary_input_label" for="mpesa_phone">MPESA Phone Number</label>
                                    <input type="text" class="primary_input_field form-control" name="mpesa_phone" id="mpesa_phone" placeholder="e.g. 07XXXXXXXX" value="{{ old('mpesa_phone') }}">
                                    @error('mpesa_phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="d-flex align-items-center mb-10" style="gap: 10px;">
                                    <input type="radio" name="payment_method" id="pm_wallet" value="kuzza_wallet" {{ old('payment_method') === 'kuzza_wallet' ? 'checked' : '' }}>
                                    <label for="pm_wallet" class="mb-0">
                                        <strong>Kuzza Wallet</strong>
                                        <div class="text-muted" style="font-size: 12px;">Pay instantly from your wallet balance.</div>
                                    </label>
                                </div>
                                <div id="wallet-hint" class="mb-15 text-muted" style="padding-left: 24px; font-size: 12px;">
                                    Wallet balance: <strong>
                                        @if(isset($currency))
                                            {{ $currency->currency_symbol }}{{ number_format((float) $wallet_balance, 2) }}
                                        @else
                                            {{ number_format((float) $wallet_balance, 2) }}
                                        @endif
                                    </strong>
                                </div>

                                <div class="d-flex align-items-center mb-10" style="gap: 10px;">
                                    <input type="radio" name="payment_method" id="pm_paylater" value="pay_later" {{ old('payment_method') === 'pay_later' ? 'checked' : '' }}>
                                    <label for="pm_paylater" class="mb-0">
                                        <strong>Pay Later</strong>
                                        <div class="text-muted" style="font-size: 12px;">Request approval from the school before payment is due.</div>
                                    </label>
                                </div>

                                <div id="paylater-terms" class="mb-15" style="padding-left: 24px; display: none;">
                                    <label class="d-flex align-items-center" style="gap: 10px; margin-bottom: 0;">
                                        <input type="checkbox" name="pay_later_terms" value="1" {{ old('pay_later_terms') ? 'checked' : '' }}>
                                        <span style="font-size: 13px;">
                                            I understand this is a request and delivery/payment will proceed after school approval.
                                        </span>
                                    </label>
                                    @error('pay_later_terms')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-lg primary-btn fix-gr-bg btn-block" style="margin-top: 10px;">
                                <i class="ti-credit-card mr-2"></i> Place Order
                            </button>

                            <p class="text-muted" style="text-align: center; font-size: 12px; margin-top: 12px;">
                                You can review your cart items before placing the order.
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('script')
<script>
    (function () {
        function syncPaymentUI() {
            var method = $('input[name="payment_method"]:checked').val();

            $('#mpesa-fields').toggle(method === 'mpesa');
            $('#paylater-terms').toggle(method === 'pay_later');
        }

        $(document).on('change', 'input[name="payment_method"]', syncPaymentUI);
        syncPaymentUI();
    })();
</script>
@endpush

