<?php $__env->startSection('title'); ?>
    School Recommended Items
<?php $__env->stopSection(); ?>

<?php $__env->startSection('mainContent'); ?>
<section class="student-details">
    <div class="container-fluid p-0">
        <div class="white-box">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-15">Recommended by School</h3>
                        <p class="text-muted mb-0" style="font-size: 13px;">
                            Items are grouped by the date your school assigned them, just like on the admin side.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 text-right">
                    <a href="<?php echo e(route('parent-recommended-items-cart')); ?>" class="primary-btn fix-gr-bg">
                        <span class="ti-shopping-cart mr-2"></span>
                        View Cart
                    </a>
                </div>
            </div>

            <?php if(isset($groupedItems) && $groupedItems->flatten()->count() > 0): ?>
                <?php $__currentLoopData = $groupedItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dateKey => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $first = $items->first();
                        $batch = $first->batch;
                        $assignedDate = $batch && $batch->created_at ? $batch->created_at : ($first->created_at ?? null);
                    ?>
                    <div class="mt-30">
                        <div class="d-flex justify-content-between align-items-center mb-10">
                            <h4 class="mb-0">
                                Assigned on <?php echo e($assignedDate ? $assignedDate->format('d M Y') : 'Unknown date'); ?>

                            </h4>
                            <?php if($batch && $batch->deadline): ?>
                                <span class="text-muted small">
                                    Deadline: <?php echo e($batch->deadline->format('d M Y')); ?>

                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead style="background-color:#f8f9fa;">
                                    <tr>
                                        <th style="width: 30%;">Item</th>
                                        <th style="width: 15%;">Category</th>
                                        <th>Description</th>
                                        <th style="width: 10%;">Price</th>
                                        <th style="width: 15%;">Child</th>
                                        <th style="width: 15%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assigned): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $schoolItem = $assigned->recommendedItem;
                                            $studentUser = $assigned->student;
                                        ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo e($schoolItem->item_name ?? 'Item'); ?></strong>
                                            </td>
                                            <td>
                                                <?php echo e(ucfirst(str_replace('_',' ', $schoolItem->item_type ?? 'general'))); ?>

                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    <?php echo e(\Illuminate\Support\Str::limit($schoolItem->description ?? '', 80)); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <?php if(isset($currency)): ?>
                                                    <?php echo e($currency->currency_symbol); ?><?php echo e(number_format($schoolItem->price ?? 0, 2)); ?>

                                                <?php else: ?>
                                                    <?php echo e(number_format($schoolItem->price ?? 0, 2)); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php echo e($studentUser->full_name ?? 'Child'); ?>

                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('parent-recommended-items-cart')); ?>" class="btn btn-sm primary-btn fix-gr-bg mb-1">
                                                    Go to cart
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div style="text-align: center; padding: 60px 20px; background: #f8f9fa; border-radius: 8px;">
                            <i class="ti-layout-list-large" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                            <h4 style="color: #999; margin-bottom: 10px;">No Items Available</h4>
                            <p style="color: #999; font-size: 16px;">No items have been assigned to your children yet.</p>
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
    function addToCart(itemId) {
        $.ajax({
            type: 'POST',
            url: '<?php echo e(route("add-recommended-item", ":id")); ?>'.replace(':id', itemId),
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                item_id: itemId
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message || 'Item added to cart successfully!');
                    setTimeout(function() {
                        window.location.href = '<?php echo e(route("parent-recommended-items-cart")); ?>';
                    }, 1500);
                } else {
                    toastr.error(response.message || 'Failed to add item to cart');
                }
            },
            error: function(error) {
                toastr.error('An error occurred. Please try again.');
                console.log(error);
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backEnd.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/joe/Videos/Code/kuzza/kuzza/resources/views/backEnd/parentPanel/recommended_items.blade.php ENDPATH**/ ?>