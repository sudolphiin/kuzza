<?php $__env->startSection('title'); ?>
    Recommended Items Cart
<?php $__env->stopSection(); ?>

<?php $__env->startSection('mainContent'); ?>
<section class="student-details">
    <div class="container-fluid p-0">
        <div class="white-box">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-15">Recommended Items Cart</h3>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 text-right">
                    <a href="<?php echo e(route('parent-recommended-items')); ?>" class="secondary-btn fix-gr-bg">
                        <span class="ti-arrow-left mr-2"></span>
                        Continue Shopping
                    </a>
                </div>
            </div>

            <?php if($cart_items && count($cart_items) > 0): ?>
                <form method="POST" action="<?php echo e(route('parent-recommended-items-cart.checkout')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="table-responsive mt-20">
                    <table class="table table-hover">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th>
                                    <input type="checkbox" id="select-all-items">
                                </th>
                                <th>Item Name</th>
                                <th>Type</th>
                                <th>Student</th>
                                <th>Price</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $cart_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="item_ids[]" value="<?php echo e($item->id); ?>" class="item-checkbox">
                                    </td>
                                    <td>
                                        <strong><?php echo e($item->recommendedItem->item_name); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: #e3f2fd; color: #1976d2;">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $item->recommendedItem->item_type))); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($item->student->full_name); ?></td>
                                    <td>
                                        <strong>
                                            <?php if(isset($currency)): ?>
                                                <?php echo e($currency->currency_symbol); ?><?php echo e(number_format($item->recommendedItem->price, 2)); ?>

                                            <?php else: ?>
                                                <?php echo e(number_format($item->recommendedItem->price, 2)); ?>

                                            <?php endif; ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <?php if($item->batch && $item->batch->deadline): ?>
                                            <?php echo e($item->batch->deadline->format('d M Y')); ?>

                                        <?php else: ?>
                                            <span class="text-muted">None</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning" style="background-color: #fff3cd; color: #856404;">
                                            <?php echo e(ucfirst($item->status)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="<?php echo e(route('parent-recommended-items-cart.already-bought', $item->id)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <select name="reason" class="form-control form-control-sm mb-1">
                                                <option value="Already purchased">Already purchased</option>
                                                <option value="Too expensive">Too expensive</option>
                                                <option value="Looking for other options">Looking for other options</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-outline-danger mb-1">
                                                <i class="ti-close"></i> Remove from list
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Cart Summary -->
                <div class="row mt-30">
                    <div class="col-lg-6 offset-lg-6">
                        <div class="white-box" style="border: 2px solid #e9ecef; border-radius: 8px;">
                            <h5 style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">Cart Summary</h5>
                            
                            <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                                <span>Total Items:</span>
                                <strong><?php echo e(count($cart_items)); ?></strong>
                            </div>

                            <div style="display: flex; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                                <span>Subtotal:</span>
                                <strong>
                                    <?php if(isset($currency)): ?>
                                        <?php echo e($currency->currency_symbol); ?><?php echo e(number_format($total_amount, 2)); ?>

                                    <?php else: ?>
                                        <?php echo e(number_format($total_amount, 2)); ?>

                                    <?php endif; ?>
                                </strong>
                            </div>

                            <div style="display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 18px;">
                                <span>Total:</span>
                                <strong style="color: var(--base_color, #20b2aa);">
                                    <?php if(isset($currency)): ?>
                                        <?php echo e($currency->currency_symbol); ?><?php echo e(number_format($total_amount, 2)); ?>

                                    <?php else: ?>
                                        <?php echo e(number_format($total_amount, 2)); ?>

                                    <?php endif; ?>
                                </strong>
                            </div>

                            <button type="submit" class="btn btn-lg primary-btn fix-gr-bg btn-block" style="margin-top: 20px;">
                                <i class="ti-shopping-cart mr-2"></i> Confirm selected items
                            </button>

                            <p style="text-align: center; color: #999; font-size: 12px; margin-top: 15px;">
                                Once you proceed with payment, the items will be dispatched to your child.
                            </p>
                        </div>
                    </div>
                </div>
                </form>

                <!-- Pagination -->
                <?php if($cart_items->hasPages()): ?>
                    <div class="row mt-20">
                        <div class="col-lg-12">
                            <div style="display: flex; justify-content: center;">
                                <?php echo e($cart_items->links()); ?>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div style="text-align: center; padding: 60px 20px; background: #f8f9fa; border-radius: 8px;">
                            <i class="ti-shopping-cart" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                            <h4 style="color: #999; margin-bottom: 10px;">Your Cart is Empty</h4>
                            <p style="color: #999; font-size: 16px; margin-bottom: 20px;">No items added to cart yet.</p>
                            <a href="<?php echo e(route('parent-recommended-items')); ?>" class="primary-btn fix-gr-bg">
                                Start Shopping
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script>
    (function () {
        $('#select-all-items').on('change', function () {
            $('.item-checkbox').prop('checked', $(this).is(':checked'));
        });
    })();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backEnd.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /media/supreme/627ADCCA7ADC9BDB/Users/Ayiik/Documents/Code/MyBidhaa/School Management/resources/views/backEnd/parentPanel/recommended_items_cart.blade.php ENDPATH**/ ?>