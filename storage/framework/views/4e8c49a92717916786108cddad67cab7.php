<?php $__env->startPush('css'); ?>
<style>
    .table-responsive .table-alignment tr th, .table-responsive .table-alignment tr td{
        min-width: 150px;
    }
</style>
<?php $__env->stopPush(); ?>

<div role="tabpanel" class="tab-pane fade" id="studentBehaviourRecord">
    <div>
        <div class="table-responsive">
            <table class="table table-alignment" cellspacing="0"
            width="100%">
            <thead>
                <tr>
                    <th width="15%"><?php echo app('translator')->get('behaviourRecords.title'); ?></th>
                    <th width="10%"><?php echo app('translator')->get('behaviourRecords.point'); ?></th>
                    <th width="10%"><?php echo app('translator')->get('behaviourRecords.date'); ?></th>
                    <th width="45%"><?php echo app('translator')->get('behaviourRecords.description'); ?></th>
                    <th width="10%"><?php echo app('translator')->get('behaviourRecords.assigned_by'); ?></th>
                    <th width="10%"><?php echo app('translator')->get('behaviourRecords.actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $studentBehaviourRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td width="15%"><?php echo e($data->incident->title); ?></td>
                        <td width="10%"><?php echo e($data->incident->point); ?></td>
                        <td width="10%"><?php echo e(dateconvert($data->incident->created_at)); ?></td>
                        <td width="45%"><?php echo e($data->incident->description); ?></td>
                        <td width="10%"><?php echo e($data->user->full_name); ?></td>
                        <td width="10%">
                            <?php if (isset($component)) { $__componentOriginal5828d9175fa53510a68ffc290f67c972 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5828d9175fa53510a68ffc290f67c972 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.drop-down','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('drop-down'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <?php if(auth()->user()->role_id == 1): ?>
                                    <a class="dropdown-item"
                                        href="<?php echo e(route('behaviour_records.incident_comment', [$data->id])); ?>"><?php echo app('translator')->get('behaviourRecords.comment'); ?></a>
                                <?php elseif(auth()->user()->role_id == 2): ?>
                                    <?php if($behaviourRecordSetting->student_comment == 1): ?>
                                        <a class="dropdown-item"
                                            href="<?php echo e(route('behaviour_records.incident_comment', [$data->id])); ?>"><?php echo app('translator')->get('behaviourRecords.comment'); ?></a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if($behaviourRecordSetting->parent_comment == 1): ?>
                                        <a class="dropdown-item"
                                            href="<?php echo e(route('behaviour_records.incident_comment', [$data->id])); ?>"><?php echo app('translator')->get('behaviourRecords.comment'); ?></a>
                                    <?php endif; ?>
                                <?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5828d9175fa53510a68ffc290f67c972)): ?>
<?php $attributes = $__attributesOriginal5828d9175fa53510a68ffc290f67c972; ?>
<?php unset($__attributesOriginal5828d9175fa53510a68ffc290f67c972); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5828d9175fa53510a68ffc290f67c972)): ?>
<?php $component = $__componentOriginal5828d9175fa53510a68ffc290f67c972; ?>
<?php unset($__componentOriginal5828d9175fa53510a68ffc290f67c972); ?>
<?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<?php /**PATH /home/joe/Videos/Code/kuzza/kuzza/resources/views/backEnd/studentInformation/inc/_student_behaviour_record_tab.blade.php ENDPATH**/ ?>