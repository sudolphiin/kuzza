<li class="<?php echo e(spn_active_link([$child->route,], "mm-active")); ?> <?php echo e($child->route); ?> main">
    <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
        <div class="nav_icon_small">
            <span class="flaticon-reading"></span>
        </div>
        <div class="nav_title">
            <span><?php echo e(__($child->lang_name)); ?> </span>
        </div>
    </a>

   
     <?php if($child->route == 'fees.student-fees-list'): ?>
        <ul class="mm-collapse">
            <?php if(userPermission($child->route)): ?>
                <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="sub">
                        <a href="<?php echo e(validRouteUrl('fees.student-fees-list-parent', $c->id)); ?>"
                            class="<?php echo e(spn_active_link('fees.student-fees-list')); ?>">
                            <?php echo e(__($child->lang_name)); ?> - <?php echo e($c->full_name); ?>

                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </ul>
     <?php else: ?>     
        <ul class="mm-collapse">
            <?php if(userPermission($child->route)): ?>
                <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="sub">
                        <a href="<?php echo e(validRouteUrl($child->route, $c->id)); ?>"
                            class="<?php echo e(spn_active_link($child->route)); ?>">
                            <?php echo e(__($child->lang_name)); ?> - <?php echo e($c->full_name); ?>

                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </ul>
     <?php endif; ?>

     <?php if($child->route == 'fees.student-fees-list'): ?>
        <ul class="mm-collapse">
            <?php if(userPermission($child->route)): ?>
                <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="sub">
                        <a href="<?php echo e(validRouteUrl('fees.student-fees-list-parent', $c->id)); ?>"
                            class="<?php echo e(spn_active_link('fees.student-fees-list')); ?>">
                            <?php echo e(__($child->lang_name)); ?> - <?php echo e($c->full_name); ?>

                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </ul>    
     <?php endif; ?>

    
</li><?php /**PATH /media/supreme/627ADCCA7ADC9BDB/Users/Ayiik/Documents/Code/MyBidhaa/School Management/resources/views/backEnd/menu/parent_children_menu.blade.php ENDPATH**/ ?>