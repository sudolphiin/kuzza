<?php
    $school_config = schoolConfig();
    $isSchoolAdmin = Session::get('isSchoolAdmin');
?>
<!-- sidebar part here -->
<nav id="sidebar" class="sidebar">

    <div class="sidebar-header update_sidebar">
        <?php if(Auth::user()->role_id != 2 && Auth::user()->role_id != 3): ?>
            <?php if(userPermission('dashboard')): ?>
                <?php if(moduleStatusCheck('Saas') == true &&
                    Auth::user()->is_administrator == 'yes' &&
                    Session::get('isSchoolAdmin') == false &&
                    Auth::user()->role_id == 1): ?>
                    <a href="<?php echo e(route('superadmin-dashboard')); ?>" id="superadmin-dashboard">
                <?php elseif(moduleStatusCheck('Saas') == true &&
                    moduleStatusCheck('SaasHr') == true &&
                    Auth::user()->is_administrator == 'yes' &&
                    Session::get('isSchoolAdmin') == false): ?>
                    <a href="<?php echo e(route('superadmin-dashboard')); ?>" id="superadmin-dashboard">
                <?php else: ?>
                    <a href="<?php echo e(route('admin-dashboard')); ?>" id="admin-dashboard">
                <?php endif; ?>
            <?php else: ?>
                <a href="<?php echo e(url('/')); ?>" id="admin-dashboard">
            <?php endif; ?>
        <?php else: ?>
            <a href="<?php echo e(url('/')); ?>" id="admin-dashboard">
        <?php endif; ?>
        <?php if(!is_null($school_config->logo)): ?>
            <img src="<?php echo e(asset($school_config->logo)); ?>" alt="logo">
        <?php else: ?>
            <img src="<?php echo e(asset('public/uploads/settings/logo.png')); ?>" alt="logo">
        <?php endif; ?>
        </a>
        <a id="close_sidebar" class="d-lg-none">
            <i class="ti-close"></i>
        </a>

    </div>
    <?php if(Auth::user()->is_saas == 0): ?>
       
        <ul class="sidebar_menu list-unstyled" id="sidebar_menu">
            <?php if(moduleStatusCheck('Saas') == true &&
                Auth::user()->is_administrator == 'yes' &&
                Session::get('isSchoolAdmin') == false &&
                Auth::user()->role_id == 1): ?>
                <?php echo $__env->make('saas::menu.Saas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <?php elseif(moduleStatusCheck('Saas') == true &&
                Auth::user()->is_administrator == 'yes' &&
                Session::get('isSchoolAdmin') == false &&
                moduleStatusCheck('SaasHr') == true): ?>
                <?php echo $__env->make('saas::menu.Saas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php else: ?>
                <?php if(auth()->user()->role_id == 2): ?>
                    <?php if ($__env->exists('backEnd.menu.student', ['paid_modules' => $paid_modules])) echo $__env->make('backEnd.menu.student', ['paid_modules' => $paid_modules], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php elseif(auth()->user()->role_id == 3): ?>
                    <?php if ($__env->exists('backEnd.menu.parent', ['children' => $children, 'paid_modules' => $paid_modules])) echo $__env->make('backEnd.menu.parent', ['children' => $children, 'paid_modules' => $paid_modules], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php else: ?>
                    
                    <?php if ($__env->exists('backEnd.menu.staff', ['paid_modules' => $paid_modules])) echo $__env->make('backEnd.menu.staff', ['paid_modules' => $paid_modules], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    <?php endif; ?>
</nav>
<!-- sidebar part end -->
<?php $__env->startPush('script'); ?>
    <script>
        $(document).ready(function(){
            var sections=[];
            $('.menu_seperator').each(function() { sections.push($(this).data('section')); });

            jQuery.each(sections, function(index, section) {
                if($('.'+section).length == 0) {
                    $('#seperator_'+section).addClass('d-none');
                }else{
                    $('#seperator_'+section).removeClass('d-none');
                }
            });
        })

    </script>
<?php $__env->stopPush(); ?><?php /**PATH /var/www/kuzza/resources/views/components/sidebar-component.blade.php ENDPATH**/ ?>