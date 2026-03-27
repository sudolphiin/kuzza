<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('parent.parent_dashboard'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('css'); ?>
    <style>
        .QA_section .QA_table thead th {
            padding-left: 30px !important;
        }

        .customeDashboard tr td, #default_table tr td{
            min-width: 150px;
        }

        .table.dataTable thead .sorting::after,
        .table.dataTable thead .sorting_asc:after,
        .table.dataTable thead .sorting_desc:after{
            top: 17px!important;
        }

        .table.dataTable.homework-table thead .sorting::after,
        .table.dataTable.homework-table thead .sorting_asc:after,
        .table.dataTable.homework-table thead .sorting_desc:after{
            top: 10px!important;
        }

        .table.dataTable.attendence-table tr th, .table.dataTable.attendence-table tr td{
            padding: 8px!important
        }
        .check_box_table .QA_table .table tbody th:first-child, 
        .QA_section.check_box_table .QA_table .table thead tr th:first-child,
        .QA_section.check_box_table .QA_table .table thead tr th {
            padding-left: 20px !important;
        }

        .customeDashboard tr td,
        #default_table tr td {
            min-width: 150px;
        }

        .table .routine-table td,
        .table th {
            padding: 12px 20px !important;
        }
        .check_box_table .QA_table .table tbody td:nth-child(2){
            padding-left: 20px
        }
        .QA_section .QA_table th, .QA_section .QA_table td{
            padding: 12px 20px !important;
        }

        .QA_table {
            margin-top: 5px !important;
        }

        .fc th {
            padding: 0 !important;
        }

        table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting:after,table.dataTable thead .sorting_desc:after {
            left: 16px;
            top: 10px;
        }
        .check_box_table .QA_table .table tbody td:first-child{
            padding-left: 20px
        }
        table thead tr th{
            line-height: 1.9!important;
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('mainContent'); ?>
    <section class="student-details">
        <div class="container-fluid p-0">

            
            <?php $__currentLoopData = $my_childrens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $children): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div class="white-box">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="main-title">
                                <h3 class="mb-15"><?php echo app('translator')->get('parent.my_children'); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Start Student Meta Information -->
                            <div class="main-title">
                                <h3 class="mb-15"> <?php echo e($children->full_name); ?></h3>
                            </div>
    
                            <?php
                                $student_detail = $children;
    
                                $issueBooks = $student_detail->bookIssue;
    
                                $homeworkLists = 0;
                                $totalSubjects = 0;
                                $totalOnlineExams = 0;
                                $totalTeachers = 0;
                                $totalExams = 0;
                                $feesDue = 0;
                                $totalPoint = 0;
                                $balance_fees = 0;
                                $pendingHomework = 0;
                                
                                foreach ($student_detail->studentRecords as $record) {
                                    $homeworkLists += $record->getParentPanelHomeWorkAttribute()->count();
                                    $totalSubjects += $record->getAssignSubjectAttribute()->count();
                                    $totalTeachers += $record->getStudentTeacherCount();
                                    
                                    $totalOnlineExams += $record->getOnlineExamAttribute()->count();
                                    $totalExams += $record->examSchedule()->count();
    
                                    foreach ($record->feesInvoice as $key => $studentInvoice) {
                                        $amount = $studentInvoice->Tamount;
                                        $weaver = $studentInvoice->Tweaver;
                                        $fine = $studentInvoice->Tfine;
                                        $paid_amount = $studentInvoice->Tpaidamount;
                                        $sub_total = $studentInvoice->Tsubtotal;
                                        $feesDue = $feesDue + ($amount + $fine - ($paid_amount + $weaver));
                                    }
                                    foreach ($record->directFeesInstallments as $feesInstallment) {
                                        $balance_fees += discount_fees($feesInstallment->amount, $feesInstallment->discount_amount) - $feesInstallment->paid_amount;
                                    }
                                    foreach ($record->incidents as $incident) {
                                        $totalPoint += $incident->point;
                                    }
                                }
                                $attendances = $student_detail->studentAttendances->where('academic_id', generalSetting()->session_id);
                            ?>
                        </div>
                    </div>
                    <div class="row row-gap-24">
                        <?php if(userPermission('parent-dashboard-subject')): ?>
                            <div class="col-lg-3 col-md-6">
                                <a href="<?php echo e(route('parent_subjects', $children->id)); ?>" class="d-block">
                                    <div class="white-box single-summery cyan">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h3><?php echo app('translator')->get('common.subject'); ?></h3>
                                                <p class="mb-0"><?php echo app('translator')->get('parent.total_subject'); ?></p>
                                            </div>
                                            <h1 class="gradient-color2">
    
                                                <?php echo e($totalSubjects); ?>

    
                                            </h1>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if(userPermission('parent-dashboard-notice')): ?>
                            <div class="col-lg-3 col-md-6">
                                <a href="<?php echo e(route('parent_noticeboard')); ?>" class="d-block">
                                    <div class="white-box single-summery violet">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h3><?php echo app('translator')->get('parent.notice'); ?></h3>
                                                <p class="mb-0"><?php echo app('translator')->get('parent.total_notice'); ?></p>
                                            </div>
                                            <h1 class="gradient-color2">
                                                <?php if(isset($totalNotices)): ?>
                                                    <?php echo e(count($totalNotices)); ?>

                                                <?php endif; ?>
                                            </h1>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if(userPermission('parent-dashboard-exam')): ?>
                            <div class="col-lg-3 col-md-6">
                                <a href="<?php echo e(route('parent_exam_schedule', $children->id)); ?>" class="d-block">
                                    <div class="white-box single-summery blue">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h3><?php echo app('translator')->get('parent.exam'); ?></h3>
                                                <p class="mb-0"><?php echo app('translator')->get('parent.total_exam'); ?></p>
                                            </div>
                                            <h1 class="gradient-color2">
    
                                                <?php echo e($totalExams); ?>

                                            </h1>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if(userPermission('parent-dashboard-exam')): ?>
                            <div class="col-lg-3 col-md-6">
                                <a href="<?php echo e(route('parent_online_examination', $children->id)); ?>" class="d-block">
                                    <div class="white-box single-summery fuchsia">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h3><?php echo app('translator')->get('parent.online_exam'); ?></h3>
                                                <p class="mb-0"><?php echo app('translator')->get('parent.total_online_exam'); ?></p>
                                            </div>
                                            <h1 class="gradient-color2">
    
                                                <?php echo e($totalOnlineExams); ?>

                                            </h1>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if(userPermission('parent-dashboard-teacher')): ?>
                            <div class="col-lg-3 col-md-6">
                                <a href="<?php echo e(route('parent_teacher_list', $children->id)); ?>" class="d-block">
                                    <div class="white-box single-summery cyan">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h3><?php echo app('translator')->get('parent.teachers'); ?></h3>
                                                <p class="mb-0"><?php echo app('translator')->get('parent.total_teachers'); ?></p>
                                            </div>
                                            <h1 class="gradient-color2">
                                                <?php echo e($totalTeachers); ?>

                                            </h1>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if(userPermission('parent-dashboard-issued-books')): ?>
                            <div class="col-lg-3 col-md-6">
                                <a href="<?php echo e(route('parent_library')); ?>" class="d-block">
                                    <div class="white-box single-summery violet">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h3><?php echo app('translator')->get('parent.issued_book'); ?></h3>
                                                <p class="mb-0"><?php echo app('translator')->get('parent.total_issued_book'); ?></p>
                                            </div>
                                            <h1 class="gradient-color2">
                                                <?php if(isset($issueBooks)): ?>
                                                    <?php echo e(count($issueBooks)); ?>

                                                <?php endif; ?>
                                            </h1>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if(userPermission('parent-dashboard-pending-homeworks')): ?>
                            <div class="col-lg-3 col-md-6">
                                <a href="<?php echo e(route('parent_homework', $children->id)); ?>" class="d-block">
                                    <div class="white-box single-summery blue">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h3><?php echo app('translator')->get('parent.pending_home_work'); ?></h3>
                                                <p class="mb-0"><?php echo app('translator')->get('parent.total_pending_home_work'); ?></p>
                                            </div>
                                            <h1 class="gradient-color2">
                                                <?php if(isset($homeworkLists)): ?>
                                                    <?php echo e($homeworkLists); ?>

                                                <?php endif; ?>
                                            </h1>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if(userPermission('parent-dashboard-attendance-in-current-month')): ?>
                            <div class="col-lg-3 col-md-6">
                                <a href="<?php echo e(route('parent_attendance', $children->id)); ?>" class="d-block">
                                    <div class="white-box single-summery fuchsia">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h3><?php echo app('translator')->get('parent.attendance_in_current_month'); ?></h3>
                                                <p class="mb-0"><?php echo app('translator')->get('parent.total_attendance_in_current_month'); ?></p>
                                            </div>
                                            <h1 class="gradient-color2">
                                                <?php if(isset($attendances)): ?>
                                                    <?php echo e(count($attendances)); ?>

                                                <?php endif; ?>
                                            </h1>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if(userPermission('fees.student-fees-list-parent')): ?>
                            <div class="col-lg-3 col-md-6">
                                <a href="<?php echo e(generalSetting()->fees_status == 0 ? route('parent_fees', $children->id) : route('fees.student-fees-list-parent', $children->id)); ?>"
                                    class="d-block">
                                    <div class="white-box single-summery cyan">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h3><?php echo app('translator')->get('parent.fees'); ?></h3>
                                                <p class="mb-0"><?php echo app('translator')->get('parent.total_due_fees'); ?></p>
                                            </div>
                                            <h1 class="gradient-color2">
                                                <?php if(generalSetting()->fees_status == 0): ?>
                                                    <?php echo e($currency->currency_symbol); ?><?php echo e($balance_fees); ?>

                                                <?php elseif(isset($feesDue)): ?>
                                                    <?php echo e($currency->currency_symbol); ?><?php echo e($feesDue); ?>

                                                <?php endif; ?>
                                            </h1>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if(moduleStatusCheck('BehaviourRecords')): ?>
                            <div class="col-lg-3 col-md-6">
                                <a href="<?php echo e(route('my_children', $children->id)); ?>" class="d-block">
                                    <div class="white-box single-summery violet">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h3><?php echo app('translator')->get('parent.behaviour_point'); ?></h3>
                                                <p class="mb-0"><?php echo app('translator')->get('parent.student_behaviour_point'); ?></p>
                                            </div>
                                            <h1 class="gradient-color2">
                                                <?php if(isset($totalPoint)): ?>
                                                    <?php echo e($totalPoint); ?>

                                                <?php endif; ?>
                                            </h1>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Items assigned by school -->
                <div class="white-box mt-40">
                    <div class="row align-items-center justify-content-between mb-20">
                        <div class="col-auto">
                            <div class="main-title mb-0">
                                <h3 class="mb-0">Items assigned by school</h3>
                            </div>
                        </div>
                        <div class="col-auto d-flex flex-wrap align-items-center gap-2 justify-content-end">
                            <a href="<?php echo e(route('parent-recommended-items')); ?>" class="primary-btn fix-gr-bg">
                                <span class="ti-shopping-cart mr-2"></span> VIEW ALL ITEMS
                            </a>
                            <?php if((isset($recommended_items) && $recommended_items->count() > 0) || (isset($required_items) && $required_items->count() > 0)): ?>
                                <a href="<?php echo e(route('parent-recommended-items')); ?>" class="secondary-btn fix-gr-bg">
                                    See all assigned items <i class="ti-arrow-right ml-2"></i>
                                </a>
                                <a href="<?php echo e(route('parent-recommended-items-cart')); ?>" class="btn btn-success btn-lg">Go to cart</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if((isset($recommended_items) && $recommended_items->count() > 0) || (isset($required_items) && $required_items->count() > 0)): ?>
                        <?php
                            $categoryBadges = ['Books' => ['bg' => '#e3f2fd', 'color' => '#1976d2'], 'Uniform' => ['bg' => '#fce4ec', 'color' => '#d81b60'], 'Stationery' => ['bg' => '#fff3e0', 'color' => '#fb8c00'], 'default' => ['bg' => '#e8f5e9', 'color' => '#43a047']];
                        ?>

                        <div class="mt-10">
                            <h4 class="mb-15">Required items</h4>
                            <?php if(isset($required_items) && $required_items->count() > 0): ?>
                                <div class="row row-gap-24">
                                    <?php $__currentLoopData = $required_items->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assigned): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $schoolItem = $assigned->recommendedItem;
                                            $cat = $schoolItem->item_type ?? 'Stationery';
                                            $style = $categoryBadges[$cat] ?? $categoryBadges['default'];
                                            $img = $schoolItem && $schoolItem->image_url ? asset($schoolItem->image_url) : (str_contains(strtolower($cat), 'book') ? asset('public/images/demo/book.svg') : (str_contains(strtolower($cat), 'uniform') ? asset('public/images/demo/uniform.svg') : asset('public/images/demo/stationery.svg')));
                                            $qty = (int) ($assigned->assigned_quantity ?: 1);
                                        ?>
                                        <div class="col-lg-3 col-md-6">
                                            <div class="white-box single-item-card" style="border:1px solid #e9ecef;border-radius:8px;overflow:hidden;">
                                                <div style="height:160px;display:flex;align-items:center;justify-content:center;background:#f8f9fa;">
                                                    <img src="<?php echo e($img); ?>" alt="<?php echo e($schoolItem->item_name ?? 'Item'); ?>" style="max-width:80%;max-height:80%;object-fit:contain;" onerror="this.style.display='none'"/>
                                                </div>
                                                <div style="padding:16px;">
                                                    <h5 style="margin-bottom:6px;font-weight:600;color:#333;"><?php echo e($schoolItem->item_name ?? 'Item'); ?></h5>
                                                    <div style="margin-bottom:8px;">
                                                        <span class="badge" style="background:#ffebee;color:#c62828;padding:5px 10px;border-radius:20px;font-size:12px;">Required</span>
                                                        <span class="badge ml-1" style="background:<?php echo e($style['bg']); ?>;color:<?php echo e($style['color']); ?>;padding:5px 10px;border-radius:20px;font-size:12px;"><?php echo e($cat); ?></span>
                                                    </div>
                                                    <p style="font-size:13px;color:#666;margin-bottom:6px;">Qty: <?php echo e($qty); ?></p>
                                                    <p style="font-size:13px;color:#666;margin-bottom:10px;"><?php echo e(\Illuminate\Support\Str::limit($schoolItem->description ?? '', 60)); ?></p>
                                                    <div style="margin-bottom:10px;"><h4 style="color:var(--base_color);font-weight:700;"><?php echo e($currency->currency_symbol ?? 'KSh'); ?> <?php echo e(number_format(($schoolItem->price ?? 0) * $qty, 2)); ?></h4></div>
                                                    <a href="<?php echo e(route('parent-recommended-items-cart')); ?>" class="btn btn-sm btn-primary" style="width:100%;">Go to checkout</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted mb-3">No required items right now.</p>
                            <?php endif; ?>
                        </div>

                        <div class="mt-25">
                            <h4 class="mb-15">Recommended items</h4>
                            <?php if(isset($recommended_items) && $recommended_items->count() > 0): ?>
                                <div class="row row-gap-24">
                                    <?php $__currentLoopData = $recommended_items->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assigned): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $schoolItem = $assigned->recommendedItem;
                                            $cat = $schoolItem->item_type ?? 'Stationery';
                                            $style = $categoryBadges[$cat] ?? $categoryBadges['default'];
                                            $img = $schoolItem && $schoolItem->image_url ? asset($schoolItem->image_url) : (str_contains(strtolower($cat), 'book') ? asset('public/images/demo/book.svg') : (str_contains(strtolower($cat), 'uniform') ? asset('public/images/demo/uniform.svg') : asset('public/images/demo/stationery.svg')));
                                            $qty = (int) ($assigned->assigned_quantity ?: 1);
                                        ?>
                                        <div class="col-lg-3 col-md-6">
                                            <div class="white-box single-item-card" style="border:1px solid #e9ecef;border-radius:8px;overflow:hidden;">
                                                <div style="height:160px;display:flex;align-items:center;justify-content:center;background:#f8f9fa;">
                                                    <img src="<?php echo e($img); ?>" alt="<?php echo e($schoolItem->item_name ?? 'Item'); ?>" style="max-width:80%;max-height:80%;object-fit:contain;" onerror="this.style.display='none'"/>
                                                </div>
                                                <div style="padding:16px;">
                                                    <h5 style="margin-bottom:6px;font-weight:600;color:#333;"><?php echo e($schoolItem->item_name ?? 'Item'); ?></h5>
                                                    <div style="margin-bottom:8px;">
                                                        <span class="badge" style="background:#e8f5e9;color:#2e7d32;padding:5px 10px;border-radius:20px;font-size:12px;">Recommended</span>
                                                        <span class="badge ml-1" style="background:<?php echo e($style['bg']); ?>;color:<?php echo e($style['color']); ?>;padding:5px 10px;border-radius:20px;font-size:12px;"><?php echo e($cat); ?></span>
                                                    </div>
                                                    <p style="font-size:13px;color:#666;margin-bottom:6px;">Qty: <?php echo e($qty); ?></p>
                                                    <p style="font-size:13px;color:#666;margin-bottom:10px;"><?php echo e(\Illuminate\Support\Str::limit($schoolItem->description ?? '', 60)); ?></p>
                                                    <div style="margin-bottom:10px;"><h4 style="color:var(--base_color);font-weight:700;"><?php echo e($currency->currency_symbol ?? 'KSh'); ?> <?php echo e(number_format(($schoolItem->price ?? 0) * $qty, 2)); ?></h4></div>
                                                    <a href="<?php echo e(route('parent-recommended-items-cart')); ?>" class="btn btn-sm btn-primary" style="width:100%;">Go to checkout</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted mb-0">No recommended items right now.</p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                    <p class="text-muted mb-0">No items have been assigned to your children yet.</p>
                    <?php endif; ?>
                </div>

                <div class="white-box mt-40">
                    <?php if(userPermission('parent_class_routine')): ?>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="main-title">
                                <h3 class="mb-15"><?php echo app('translator')->get('academics.class_routine'); ?></h3>
                            </div>
                        </div>
                        <div class="col-lg-12 student-details up_admin_visitor mb-20">
                            <ul class="nav nav-tabs tabs_scroll_nav ml-0" role="tablist">
                                <?php $__currentLoopData = $children->studentRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?php if($key == 0): ?> active <?php endif; ?>"
                                            href="#routineTab<?php echo e($key); ?>" role="tab" data-toggle="tab">
                                            <?php if(moduleStatusCheck('University')): ?>
                                                <?php echo e($record->semesterLabel->name); ?>

                                                (<?php echo e($record->unSection->section_name); ?>)
                                                -
                                                <?php echo e(@$record->unAcademic->name); ?>

                                            <?php else: ?>
                                                <?php echo e($record->class->class_name); ?> (<?php echo e($record->section->section_name); ?>) <?php if(shiftEnable()): ?> <?php if($record->shift): ?> [<?php echo e($record->shift->shift_name); ?>] <?php endif; ?> <?php endif; ?>
                                            <?php endif; ?>
                                        </a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <div class="tab-content">
                                <?php $__currentLoopData = $children->studentRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div role="tabpanel"
                                        class="tab-pane fade  <?php if($key == 0): ?> active show <?php endif; ?>"
                                        id="routineTab<?php echo e($key); ?>">
                                        <div class="container-fluid p-0">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="table-responsive">
                                                        <?php if (isset($component)) { $__componentOriginal163c8ba6efb795223894d5ffef5034f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal163c8ba6efb795223894d5ffef5034f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                                            <table id="default_table"
                                                                class="table customeDashboard routine-table"
                                                                cellspacing="0"
                                                                width="100%">
                                                                <tr>
                                                                    <?php
                                                                        $height = 0;
                                                                        $tr = [];
                                                                    ?>
                                                                    <?php $__currentLoopData = $sm_weekends; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sm_weekend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php
                                                                            if (moduleStatusCheck('University')) {
                                                                                $studentClassRoutine = App\SmWeekend::universityStudentClassRoutine($record->un_semester_label_id, $record->un_section_id, $sm_weekend->id);
                                                                            } else {
                                                                                $studentClassRoutine = App\SmWeekend::studentClassRoutineFromRecord($record->class_id, $record->section_id, $sm_weekend->id, $record->shift_id);
                                                                            }
                                                                        ?>
                                                                        <?php if($studentClassRoutine->count() > $height): ?>
                                                                            <?php
                                                                                $height = $studentClassRoutine->count();
                                                                            ?>
                                                                        <?php endif; ?>

                                                                        <th
                                                                            class="<?php echo e(\Carbon\Carbon::now()->format('l') == $sm_weekend->name ? 'main-border-color' : ''); ?>">
                                                                            <?php echo e(@$sm_weekend->name); ?></th>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </tr>

                                                                <?php
                                                                    $used = [];
                                                                    $tr = [];
                                                                ?>
                                                                <?php $__currentLoopData = $sm_weekends; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sm_weekend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php
                                                                        $i = 0;
                                                                        if (moduleStatusCheck('University')) {
                                                                            $studentClassRoutine = App\SmWeekend::universityStudentClassRoutine($record->un_semester_label_id, $record->un_section_id, $sm_weekend->id);
                                                                        } else {
                                                                            $studentClassRoutine = App\SmWeekend::studentClassRoutineFromRecord($record->class_id, $record->section_id, $sm_weekend->id, $record->shift_id);
                                                                        }
                                                                    ?>
                                                                    <?php $__currentLoopData = $studentClassRoutine; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $routine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php
                                                                            if (!in_array($routine->id, $used)) {
                                                                                if (moduleStatusCheck('University')) {
                                                                                    $tr[$i][$sm_weekend->name][$loop->index]['subject'] = $routine->unSubject ? $routine->unSubject->subject_name : '';
                                                                                    $tr[$i][$sm_weekend->name][$loop->index]['subject_code'] = $routine->unSubject ? $routine->unSubject->subject_code : '';
                                                                                } else {
                                                                                    $tr[$i][$sm_weekend->name][$loop->index]['subject'] = $routine->subject ? $routine->subject->subject_name : '';
                                                                                    $tr[$i][$sm_weekend->name][$loop->index]['subject_code'] = $routine->subject ? $routine->subject->subject_code : '';
                                                                                }
                                                                                $tr[$i][$sm_weekend->name][$loop->index]['class_room'] = $routine->classRoom ? $routine->classRoom->room_no : '';
                                                                                $tr[$i][$sm_weekend->name][$loop->index]['teacher'] = $routine->teacherDetail ? $routine->teacherDetail->full_name : '';
                                                                                $tr[$i][$sm_weekend->name][$loop->index]['start_time'] = $routine->start_time;
                                                                                $tr[$i][$sm_weekend->name][$loop->index]['end_time'] = $routine->end_time;
                                                                                $tr[$i][$sm_weekend->name][$loop->index]['is_break'] = $routine->is_break;
                                                                                $used[] = $routine->id;
                                                                            }
                                                                        ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                                    <?php
                                                                        $i++;
                                                                    ?>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                                <?php for($i = 0; $i < $height; $i++): ?>
                                                                    <tr>
                                                                        <?php $__currentLoopData = $tr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $days): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <?php $__currentLoopData = $sm_weekends; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sm_weekend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <td
                                                                                    class="<?php echo e(\Carbon\Carbon::now()->format('l') == $sm_weekend->name ? 'main-border-color' : ''); ?>">
                                                                                    <?php
                                                                                        $classes = gv($days, $sm_weekend->name);
                                                                                    ?>
                                                                                    <?php if($classes && gv($classes, $i)): ?>
                                                                                        <?php if($classes[$i]['is_break']): ?>
                                                                                            <strong> <?php echo app('translator')->get('academics.break'); ?>
                                                                                            </strong>

                                                                                            <span class="">
                                                                                                (<?php echo e(date('h:i A', strtotime(@$classes[$i]['start_time']))); ?>

                                                                                                -
                                                                                                <?php echo e(date('h:i A', strtotime(@$classes[$i]['end_time']))); ?>)
                                                                                                <br> </span>
                                                                                        <?php else: ?>
                                                                                            <span class="">
                                                                                                <strong><?php echo app('translator')->get('common.time'); ?>
                                                                                                    :</strong>
                                                                                                <?php echo e(date('h:i A', strtotime(@$classes[$i]['start_time']))); ?>

                                                                                                -
                                                                                                <?php echo e(date('h:i A', strtotime(@$classes[$i]['end_time']))); ?>

                                                                                                <br> </span>
                                                                                            <span class=""> <strong>
                                                                                                    <?php echo e($classes[$i]['subject']); ?>

                                                                                                </strong>
                                                                                                (<?php echo e($classes[$i]['subject_code']); ?>)
                                                                                                <br> </span>
                                                                                            <?php if($classes[$i]['class_room']): ?>
                                                                                                <span class="">
                                                                                                    <strong><?php echo app('translator')->get('academics.room'); ?>
                                                                                                        :</strong>
                                                                                                    <?php echo e($classes[$i]['class_room']); ?>

                                                                                                    <br> </span>
                                                                                            <?php endif; ?>
                                                                                            <?php if($classes[$i]['teacher']): ?>
                                                                                                <span class="">
                                                                                                    <?php echo e($classes[$i]['teacher']); ?>

                                                                                                    <br>
                                                                                                </span>
                                                                                            <?php endif; ?>
                                                                                        <?php endif; ?>
                                                                                    <?php endif; ?>

                                                                                </td>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </tr>
                                                                <?php endfor; ?>
                                                            </table>
                                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $attributes = $__attributesOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $component = $__componentOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__componentOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                </div>

                <?php if(userPermission('parent_attendance')): ?>
                    <div class="row mt-40">
                        <?php
                            $now = Carbon::now();
                            $year = $now->year;
                            $month = $now->month;
                            $days = cal_days_in_month(CAL_GREGORIAN, $now->month, $now->year);
                            $attendance = $children->attendances;
                        ?>
                        <?php echo $__env->make('backEnd.parentPanel.inc._parent_dashboard_attendance_statistics', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php echo $__env->make('backEnd.parentPanel.inc._dashboard_subject_attendance_tab', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                <?php endif; ?>
                
                <?php if(userPermission('fees.student-fees-list-parent')): ?>
                    <div class="white-box mt-40">
                        <div class="row">
                            <?php echo $__env->make('backEnd.parentPanel.inc._fees_info', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if(userPermission('parent_exam_schedule')): ?>
                    <div class="white-box mt-40">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="main-title">
                                    <h3 class="mb-15"><?php echo app('translator')->get('exam.exam_routine'); ?></h3>
                                </div>
                            </div>
                            <div class="col-lg-12 student-details up_admin_visitor mb-20">
                                <ul class="nav nav-tabs tabs_scroll_nav ml-0" id="myTab" role="tablist">
                                    <?php $__currentLoopData = $children->studentRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($record->Exam): ?>
                                            <?php $__currentLoopData = $record->Exam->unique(function ($item) {
                                                return $item->exam_type_id . $item->class_id . $item->section_id;
                                            }); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="nav-item">
                                                    <a class="nav-link <?php if($key == 0): ?> active <?php endif; ?>" id="home-tab<?php echo e($children->id . $exam->id); ?>"
                                                        data-toggle="tab" href="#home<?php echo e($children->id . $exam->id); ?>"
                                                        role="tab" aria-controls="home" aria-selected="true">
                                                        <?php echo e($exam->examType->title); ?> - <?php echo e($record->class->class_name); ?>

                                                        (<?php echo e($record->section->section_name); ?>) <?php if(shiftEnable()): ?> <?php if($record->shift): ?> [<?php echo e($record->shift->shift_name); ?>] <?php endif; ?> <?php endif; ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                                <div class="tab-content">
                                    <?php $__currentLoopData = $children->studentRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($record->Exam): ?>
                                            <?php $__currentLoopData = $record->Exam->unique(function ($item) {
                                                return $item->exam_type_id . $item->class_id . $item->section_id;
                                            }); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $exam_routines = App\SmExamSchedule::getAllExams($exam->class_id, $exam->section_id, $exam->exam_type_id, $exam->shift_id);
                                                ?>
                                                <div class="tab-pane fade <?php if($key == 0): ?> active show <?php endif; ?>"
                                                    id="home<?php echo e($children->id . $exam->id); ?>" role="tabpanel"
                                                    aria-labelledby="home-tab<?php echo e($children->id . $exam->id); ?>">
                                                    <div class="container-fluid p-0">
                                                        <?php if (isset($component)) { $__componentOriginal163c8ba6efb795223894d5ffef5034f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal163c8ba6efb795223894d5ffef5034f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                                            <div class="table-responsive">
                                                                <table id="default_table" class="table" cellspacing="0"
                                                                    width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width:10%;">
                                                                                <?php echo app('translator')->get('exam.date_&_day'); ?>
                                                                            </th>
                                                                            <th><?php echo app('translator')->get('exam.subject'); ?></th>
                                                                            <th><?php echo app('translator')->get('common.class_Sec'); ?></th>
                                                                            <th><?php echo app('translator')->get('exam.teacher'); ?></th>
                                                                            <th><?php echo app('translator')->get('exam.time'); ?></th>
                                                                            <th><?php echo app('translator')->get('exam.duration'); ?></th>
                                                                            <th><?php echo app('translator')->get('exam.room'); ?></th>
    
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $__currentLoopData = $exam_routines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $exam_routine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <tr
                                                                                class="<?php echo e(Carbon::parse($exam_routine->date)->format('Y-m-d') == Carbon::now()->format('Y-m-d') ? 'main-border-color' : ''); ?>">
                                                                                <td><?php echo e(dateConvert($exam_routine->date)); ?>

                                                                                    <br><?php echo e(Carbon::createFromFormat('Y-m-d', $exam_routine->date)->format('l')); ?>

                                                                                </td>
                                                                                <td>
                                                                                    <strong>
                                                                                        <?php echo e($exam_routine->subject ? $exam_routine->subject->subject_name : ''); ?>

                                                                                    </strong>
                                                                                    <?php echo e($exam_routine->subject ? '(' . $exam_routine->subject->subject_code . ')' : ''); ?>

                                                                                </td>
                                                                                <td><?php echo e($exam_routine->class ? $exam_routine->class->class_name : ''); ?>

                                                                                    <?php echo e($exam_routine->section ? '(' . $exam_routine->section->section_name . ')' : ''); ?>

                                                                                </td>
                                                                                <td><?php echo e($exam_routine->teacher ? $exam_routine->teacher->full_name : ''); ?>

                                                                                </td>
    
                                                                                <td> <?php echo e(date('h:i A', strtotime(@$exam_routine->start_time))); ?>

                                                                                    -
                                                                                    <?php echo e(date('h:i A', strtotime(@$exam_routine->end_time))); ?>

                                                                                </td>
                                                                                <td>
                                                                                    <?php
                                                                                        $duration = strtotime($exam_routine->end_time) - strtotime($exam_routine->start_time);
                                                                                    ?>
    
                                                                                    <?php echo e(timeCalculation($duration)); ?>

                                                                                </td>
    
                                                                                <td><?php echo e($exam_routine->classRoom ? $exam_routine->classRoom->room_no : ''); ?>

                                                                                </td>
    
                                                                            </tr>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $attributes = $__attributesOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $component = $__componentOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__componentOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="white-box">
                        <?php echo $__env->make('backEnd.parentPanel.inc._complaint_list_tab', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>
            </div>

            <?php if(userPermission('parent-dashboard-calendar')): ?>
                <div class="row mt-40">
                    <div class="col-lg-12">
                        <div>
                            <?php echo $__env->make('backEnd.communicate.commonAcademicCalendar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <div id="fullCalModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span
                            class="sr-only">close</span></button>
                    <h4 id="modalTitle" class="modal-title"></h4>
                </div>
                <div class="modal-body text-center">
                    <img src="" alt="There are no image" id="image" height="150" width="auto">
                    <div id="modalBody"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backEnd.communicate.academic_calendar_css_js', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
                    }, 1000);
                } else {
                    toastr.error(response.message || 'Failed to add item to cart');
                }
            },
            error: function(error) {
                toastr.error('An error occurred. Please try again.');
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backEnd.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /media/supreme/627ADCCA7ADC9BDB/Users/Ayiik/Documents/Code/MyBidhaa/School Management/resources/views/backEnd/parentPanel/parent_dashboard.blade.php ENDPATH**/ ?>