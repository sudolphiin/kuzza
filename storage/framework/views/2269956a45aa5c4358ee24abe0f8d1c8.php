<?php $__env->startPush('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('public/backEnd/multiselect/')); ?>/js/jquery.multiselect.js"></script>
    <script type="text/javascript">
        $(function () {
            $("select[multiple].active.multypol_check_select").multiselect({ 
                columns: 1, 
                placeholder: "Select", 
                search: true, 
                searchOptions: {default: "Select"},
                selectAll: true, 
            }); 
        }); 
    </script>
<?php $__env->stopPush(); ?> <?php /**PATH /media/supreme/627ADCCA7ADC9BDB/Users/Ayiik/Documents/Code/MyBidhaa/School Management/resources/views/backEnd/partials/multi_select_js.blade.php ENDPATH**/ ?>