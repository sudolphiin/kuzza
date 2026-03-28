<?php $__env->startSection('title'); ?>
    Recommended Items Checkout
<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<style>
    .payment-method-option {
        position: relative;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px 18px;
        margin-bottom: 12px;
        border: 1px solid #e5dff2;
        border-radius: 16px;
        background: #fff;
        cursor: pointer;
        transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease, background-color .18s ease;
    }

    .payment-method-option:hover {
        border-color: rgba(91, 45, 142, 0.35);
        box-shadow: 0 12px 24px rgba(58, 26, 107, 0.08);
        transform: translateY(-1px);
    }

    .payment-method-option.active {
        background: linear-gradient(180deg, rgba(245, 197, 24, 0.07), rgba(200, 168, 233, 0.14));
        border-color: rgba(91, 45, 142, 0.45);
        box-shadow: 0 16px 28px rgba(58, 26, 107, 0.12);
    }

    .payment-method-option input[type="radio"] {
        margin-top: 10px;
        accent-color: var(--purple, #5B2D8E);
        flex: 0 0 auto;
    }

    .payment-method-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: var(--purple-deep, #3A1A6B);
        background: rgba(91, 45, 142, 0.1);
        border: 1px solid rgba(200, 168, 233, 0.9);
        flex: 0 0 46px;
    }

    .payment-method-copy {
        flex: 1 1 auto;
        min-width: 0;
    }

    .payment-method-copy strong {
        display: block;
        color: #1f2937;
        margin-bottom: 3px;
    }

    .payment-method-copy .text-muted {
        font-size: 12px;
        line-height: 1.45;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('mainContent'); ?>
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
                    <a href="<?php echo e(route('parent-recommended-items-cart')); ?>" class="secondary-btn fix-gr-bg">
                        <span class="ti-arrow-left mr-2"></span>
                        Back to Cart
                    </a>
                </div>
            </div>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger mt-20">
                    <strong>Please fix the errors below and try again.</strong>
                    <ul class="mb-0 mt-10">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('parent-recommended-items-cart.pay')); ?>" id="checkout-form">
                <?php echo csrf_field(); ?>
                <?php $__currentLoopData = $item_ids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <input type="hidden" name="item_ids[]" value="<?php echo e($id); ?>">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
                                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $qty = (int) ($item->assigned_quantity ?: 1);
                                                $price = (float) optional($item->recommendedItem)->price;
                                                $line = $qty * $price;
                                            ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo e(optional($item->recommendedItem)->item_name); ?></strong>
                                                    <div class="text-muted" style="font-size: 12px;">
                                                        <?php echo e(ucfirst(str_replace('_', ' ', optional($item->recommendedItem)->item_type))); ?>

                                                    </div>
                                                </td>
                                                <td><?php echo e(optional($item->student)->full_name); ?></td>
                                                <td class="text-right"><strong><?php echo e($qty); ?></strong></td>
                                                <td class="text-right">
                                                    <strong>
                                                        <?php if(isset($currency)): ?>
                                                            <?php echo e($currency->currency_symbol); ?><?php echo e(number_format($line, 2)); ?>

                                                        <?php else: ?>
                                                            <?php echo e(number_format($line, 2)); ?>

                                                        <?php endif; ?>
                                                    </strong>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>

                            <div style="border-top: 1px solid #eee; padding-top: 15px; margin-top: 15px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                    <span>Total Items:</span>
                                    <strong><?php echo e(count($items)); ?></strong>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 18px;">
                                    <span>Grand Total:</span>
                                    <strong style="color: var(--base_color, #20b2aa);">
                                        <?php if(isset($currency)): ?>
                                            <?php echo e($currency->currency_symbol); ?><?php echo e(number_format($total, 2)); ?>

                                        <?php else: ?>
                                            <?php echo e(number_format($total, 2)); ?>

                                        <?php endif; ?>
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

                                <label class="payment-method-option" for="pm_mpesa">
                                    <input type="radio" name="payment_method" id="pm_mpesa" value="mpesa" <?php echo e(old('payment_method', 'mpesa') === 'mpesa' ? 'checked' : ''); ?>>
                                    <span class="payment-method-icon">
                                        <i class="ti-mobile"></i>
                                    </span>
                                    <span class="payment-method-copy">
                                        <strong>MPESA</strong>
                                        <div class="text-muted" style="font-size: 12px;">You’ll receive a payment prompt on your phone.</div>
                                    </span>
                                </label>

                                <div id="mpesa-fields" class="mb-15" style="padding-left: 24px;">
                                    <label class="primary_input_label" for="mpesa_phone">MPESA Phone Number</label>
                                    <input type="text" class="primary_input_field form-control" name="mpesa_phone" id="mpesa_phone" placeholder="e.g. 07XXXXXXXX" value="<?php echo e(old('mpesa_phone')); ?>">
                                    <?php $__errorArgs = ['mpesa_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <label class="payment-method-option" for="pm_wallet">
                                    <input type="radio" name="payment_method" id="pm_wallet" value="kuzza_wallet" <?php echo e(old('payment_method') === 'kuzza_wallet' ? 'checked' : ''); ?>>
                                    <span class="payment-method-icon">
                                        <i class="ti-wallet"></i>
                                    </span>
                                    <span class="payment-method-copy">
                                        <strong>Kuzza Wallet</strong>
                                        <div class="text-muted" style="font-size: 12px;">Pay instantly from your wallet balance.</div>
                                    </span>
                                </label>
                                <div id="wallet-hint" class="mb-15 text-muted" style="padding-left: 24px; font-size: 12px;">
                                    Wallet balance: <strong>
                                        <?php if(isset($currency)): ?>
                                            <?php echo e($currency->currency_symbol); ?><?php echo e(number_format((float) $wallet_balance, 2)); ?>

                                        <?php else: ?>
                                            <?php echo e(number_format((float) $wallet_balance, 2)); ?>

                                        <?php endif; ?>
                                    </strong>
                                </div>

                                <label class="payment-method-option" for="pm_paylater">
                                    <input type="radio" name="payment_method" id="pm_paylater" value="pay_later" <?php echo e(old('payment_method') === 'pay_later' ? 'checked' : ''); ?>>
                                    <span class="payment-method-icon">
                                        <i class="ti-time"></i>
                                    </span>
                                    <span class="payment-method-copy">
                                        <strong>Pay Later</strong>
                                        <div class="text-muted" style="font-size: 12px;">Request approval from the school before payment is due.</div>
                                    </span>
                                </label>

                                <div id="paylater-terms" class="mb-15" style="padding-left: 24px; display: none;">
                                    <label class="d-flex align-items-center" style="gap: 10px; margin-bottom: 0;">
                                        <input type="checkbox" name="pay_later_terms" value="1" <?php echo e(old('pay_later_terms') ? 'checked' : ''); ?>>
                                        <span style="font-size: 13px;">
                                            I understand this is a request and delivery/payment will proceed after school approval.
                                        </span>
                                    </label>
                                    <?php $__errorArgs = ['pay_later_terms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script>
    (function () {
        function paintPaymentOptions() {
            $('.payment-method-option').removeClass('active');
            $('input[name="payment_method"]:checked').closest('.payment-method-option').addClass('active');
        }

        function syncPaymentUI() {
            var method = $('input[name="payment_method"]:checked').val();

            $('#mpesa-fields').toggle(method === 'mpesa');
            $('#paylater-terms').toggle(method === 'pay_later');
            paintPaymentOptions();
        }

        $(document).on('change', 'input[name="payment_method"]', syncPaymentUI);
        syncPaymentUI();
    })();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backEnd.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/kuzza/resources/views/backEnd/parentPanel/recommended_items_checkout.blade.php ENDPATH**/ ?>