<?php if (isset($component)) { $__componentOriginal288b082e3ae37093a10c3d78895b4c0d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal288b082e3ae37093a10c3d78895b4c0d = $attributes; } ?>
<?php $component = App\View\Components\SidebarComponent::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SidebarComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal288b082e3ae37093a10c3d78895b4c0d)): ?>
<?php $attributes = $__attributesOriginal288b082e3ae37093a10c3d78895b4c0d; ?>
<?php unset($__attributesOriginal288b082e3ae37093a10c3d78895b4c0d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal288b082e3ae37093a10c3d78895b4c0d)): ?>
<?php $component = $__componentOriginal288b082e3ae37093a10c3d78895b4c0d; ?>
<?php unset($__componentOriginal288b082e3ae37093a10c3d78895b4c0d); ?>
<?php endif; ?><?php /**PATH /home/joe/Videos/Code/kuzza/kuzza/resources/views/backEnd/partials/sidebar.blade.php ENDPATH**/ ?>