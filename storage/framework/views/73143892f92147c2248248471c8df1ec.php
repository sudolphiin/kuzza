<?php
    $is_registration_permission = false;
    if (moduleStatusCheck('ParentRegistration')) {
        $reg_setting = Modules\ParentRegistration\Entities\SmRegistrationSetting::where('school_id', $school->id)->first();
        $is_registration_position = $reg_setting ? $reg_setting->position : null;
        $is_registration_permission = $reg_setting ? $reg_setting->registration_permission == 1 : false;
    }
    $setting = generalSetting();
    App::setLocale(getUserLanguage());
    $ttl_rtl = userRtlLtl();
?>
<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" <?php if(isset($ttl_rtl) && $ttl_rtl == 1): ?> dir="rtl" class="rtl" <?php endif; ?>>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo e(asset('icon.png')); ?>" type="image/png" />
    <link rel="stylesheet" href="<?php echo e(asset('public/backEnd/vendors/font_awesome/css/all.min.css')); ?>" />
    <title>Kuzza Education ERP</title>
    <meta name="_token" content="<?php echo csrf_token(); ?>" />
    <style>
        .footer-list ul {
            list-style: none;
            padding-left: 0;
            margin-bottom: 50px;
        }

        .footer-list ul li {
            display: block;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .f_title {
            margin-bottom: 40px;
        }

        .f_title h4 {
            color: var(--base_color);
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 0px;
        }
    </style>
    <!-- Bootstrap CSS -->
    <?php if($setting->site_title == 1): ?>
        <link rel="stylesheet" href="<?php echo e(asset('public/backEnd/')); ?>/css/rtl/bootstrap.min.css" />
    <?php else: ?>
        <link rel="stylesheet" href="<?php echo e(asset('public/backEnd/')); ?>/vendors/css/bootstrap.css" />
    <?php endif; ?>

    <link rel="stylesheet" href="<?php echo e(asset('public/backEnd/')); ?>/vendors/css/jquery-ui.css" />

    <link rel="stylesheet" href="<?php echo e(asset('public/backEnd/')); ?>/vendors/css/bootstrap-datepicker.min.css" />
    <link rel="stylesheet" href="<?php echo e(asset('public/backEnd/')); ?>/vendors/css/bootstrap-datetimepicker.min.css" />
    <link rel="stylesheet" href="<?php echo e(asset('public/backEnd/')); ?>/vendors/css/themify-icons.css" />
    <link rel="stylesheet" href="<?php echo e(asset('public/backEnd/')); ?>/vendors/css/font-awesome.min.css" />
    <link rel="stylesheet" href="<?php echo e(asset('public/backEnd/')); ?>/vendors/css/nice-select.css" />
    <link rel="stylesheet" href="<?php echo e(asset('public/backEnd/')); ?>/vendors/css/magnific-popup.css" />
    <link rel="stylesheet" href="<?php echo e(asset('public/backEnd/')); ?>/vendors/css/fastselect.min.css" />
    <link rel="stylesheet" href="<?php echo e(asset('public/backEnd/')); ?>/vendors/css/owl.carousel.min.css" />
    <!-- main css -->


    <link rel="stylesheet" href="<?php echo e(asset('public/frontend/')); ?>/css/<?php echo e(@activeStyle()->path_main_style); ?>" />


    <?php if(isset($ttl_rtl) && $ttl_rtl == 1): ?>
        <link rel="stylesheet" href="<?php echo e(url('public/backEnd/')); ?>/assets/vendors/vendors_static_style.css" />
        <link rel="stylesheet" href="<?php echo e(asset('public/backEnd/assets/css/rtl/style.css')); ?>" />
    <?php endif; ?>

    <link rel="stylesheet" href="<?php echo e(asset('public/backEnd/')); ?>/vendors/css/fullcalendar.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('public/backEnd/')); ?>/vendors/css/fullcalendar.print.css">

    <link rel="stylesheet" href="<?php echo e(asset('public/')); ?>/frontend/css/infix.css" />
    
    <link rel="stylesheet" href="<?php echo e(asset('public/theme/'.activeTheme().'/css/fontawesome.all.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('public/theme/'.activeTheme().'/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('public/whatsapp-support/style.css')); ?>">
    <?php if (isset($component)) { $__componentOriginal05bb8265ee24cbda94049f193d0e88b0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal05bb8265ee24cbda94049f193d0e88b0 = $attributes; } ?>
<?php $component = App\View\Components\RootCss::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('root-css'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\RootCss::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal05bb8265ee24cbda94049f193d0e88b0)): ?>
<?php $attributes = $__attributesOriginal05bb8265ee24cbda94049f193d0e88b0; ?>
<?php unset($__attributesOriginal05bb8265ee24cbda94049f193d0e88b0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal05bb8265ee24cbda94049f193d0e88b0)): ?>
<?php $component = $__componentOriginal05bb8265ee24cbda94049f193d0e88b0; ?>
<?php unset($__componentOriginal05bb8265ee24cbda94049f193d0e88b0); ?>
<?php endif; ?>
    
    <?php echo $__env->yieldPushContent(config('pagebuilder.site_style_var')); ?>
    <script src="<?php echo e(asset('public/backEnd/')); ?>/vendors/js/jquery-3.2.1.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script>
        window._locale = '<?php echo e(app()->getLocale()); ?>';
        window._rtl = <?php echo e(userRtlLtl() == 1 ? 'true' : 'false'); ?>;
    </script>
    <?php echo $__env->yieldPushContent('css'); ?>
</head>

<body class="client light">
    <!--================ Start Header Menu Area =================-->
    <header class="header-area">
        <div class="main_menu">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container box-1420">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <a class="navbar-brand" href="<?php echo e(url('/')); ?>/home">
                        <img class="w-75" src="<?php echo e(asset('logo.png')); ?>" alt="Kuzza Logo" style="max-width: 150px;">
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="ti-menu"></span>
                    </button>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                        <ul class="nav navbar-nav menu_nav ml-auto">

                            
                            
                            <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(count($menu->childs) > 0): ?>
                                    <li class="nav-item submenu_left_control">
                                        <a class="nav-link" href="#"><?php echo e($menu->title); ?></a>
                                        <ul class="sumbmenu">
                                            <?php $__currentLoopData = $menu->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $sub_menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="<?php echo e($sub_menu->show == 1 ? 'menu_list_left' : ''); ?>">
                                                    <a <?php echo e($sub_menu->is_newtab ? 'target="_blank"' : ''); ?>

                                                        <?php if($sub_menu->type == 'dPages'): ?> 
                                                            href="<?php echo e(route('view-page', $sub_menu->link)); ?>" 
                                                        <?php endif; ?>
                                                        <?php if($sub_menu->type == 'sPages'): ?> 
                                                            <?php if($sub_menu->link == '/login'): ?>
                                                                <?php if(!auth()->check()): ?>
                                                                    href="<?php echo e(url('/')); ?><?php echo e($sub_menu->link); ?>"
                                                                <?php else: ?>
                                                                    href="<?php echo e(url('/')); ?><?php echo e($sub_menu->link); ?>" 
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                href="<?php echo e(url('/')); ?><?php echo e($sub_menu->link); ?>"
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        <?php if($sub_menu->type == 'dCourse'): ?> href="<?php echo e(route('course-Details', $sub_menu->element_id)); ?>" <?php endif; ?>
                                                        <?php if($sub_menu->type == 'dCourseCategory'): ?> href="<?php echo e(route('view-course-category', $sub_menu->element_id)); ?>" <?php endif; ?>
                                                        <?php if($sub_menu->type == 'dNewsCategory'): ?> href="<?php echo e(route('view-news-category', $sub_menu->element_id)); ?>" <?php endif; ?>
                                                        <?php if($sub_menu->type == 'dNews'): ?> href="<?php echo e(route('news-Details', $sub_menu->element_id)); ?>" <?php endif; ?>
                                                        <?php if($sub_menu->type == 'customLink'): ?> href="<?php echo e($sub_menu->link); ?>" <?php endif; ?>
                                                    >
                                                        <?php if($sub_menu->link == '/login'): ?>
                                                            <?php if(!auth()->check()): ?>
                                                                <?php echo e($sub_menu->title); ?>

                                                            <?php else: ?>
                                                                <?php echo app('translator')->get('common.dashboard'); ?>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <?php echo e($sub_menu->title); ?>

                                                        <?php endif; ?>
                                                    </a>
                                                    <ul class="sumbmenu">
                                                        <?php $__currentLoopData = $sub_menu->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $child_sub_menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <li>
                                                                <a <?php echo e($child_sub_menu->is_newtab ? 'target="_blank"' : ''); ?>

                                                                    <?php if($child_sub_menu->type == 'dPages'): ?> 
                                                                        href="<?php echo e(route('view-page', $child_sub_menu->link)); ?>" 
                                                                    <?php endif; ?>
                                                                    <?php if($child_sub_menu->type == 'sPages'): ?> 
                                                                        <?php if($child_sub_menu->link == '/login'): ?>
                                                                            <?php if(!auth()->check()): ?>
                                                                                href="<?php echo e(url('/')); ?><?php echo e($child_sub_menu->link); ?>"
                                                                            <?php else: ?>
                                                                                href="<?php echo e(url('/')); ?><?php echo e($child_sub_menu->link); ?>" 
                                                                            <?php endif; ?>
                                                                        <?php else: ?>
                                                                            href="<?php echo e(url('/')); ?><?php echo e($child_sub_menu->link); ?>"
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                    <?php if($child_sub_menu->type == 'dCourse'): ?> href="<?php echo e(route('course-Details', $child_sub_menu->element_id)); ?>" <?php endif; ?>
                                                                    <?php if($child_sub_menu->type == 'dCourseCategory'): ?> href="<?php echo e(route('view-course-category', $child_sub_menu->element_id)); ?>" <?php endif; ?>
                                                                    <?php if($child_sub_menu->type == 'dNewsCategory'): ?> href="<?php echo e(route('view-news-category', $child_sub_menu->element_id)); ?>" <?php endif; ?>
                                                                    <?php if($child_sub_menu->type == 'dNews'): ?> href="<?php echo e(route('news-Details', $child_sub_menu->element_id)); ?>" <?php endif; ?>
                                                                    <?php if($child_sub_menu->type == 'customLink'): ?> href="<?php echo e($child_sub_menu->link); ?>" <?php endif; ?>
                                                                >
                                                                    <?php if($child_sub_menu->link == '/login'): ?>
                                                                        <?php if(!auth()->check()): ?>
                                                                            <?php echo e($child_sub_menu->title); ?>

                                                                        <?php else: ?>
                                                                            <?php echo app('translator')->get('common.dashboard'); ?>
                                                                        <?php endif; ?>
                                                                    <?php else: ?>
                                                                        <?php echo e($child_sub_menu->title); ?>

                                                                    <?php endif; ?>
                                                                </a>
                                                            </li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </ul>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </li>
                                <?php else: ?>
                                    <li class="nav-item">
                                        <a class="nav-link" <?php echo e($menu->is_newtab ? 'target="_blank"' : ''); ?>

                                            <?php if($menu->type == 'dPages'): ?> href="<?php echo e(route('view-page', $menu->link)); ?>" <?php endif; ?>
                                            <?php if($menu->type == 'sPages'): ?> <?php if($menu->link == '/login'): ?>
                                                <?php if(!auth()->check()): ?>
                                                href="<?php echo e(url('/')); ?><?php echo e($menu->link); ?>"
                                                <?php else: ?>
                                                href="<?php echo e(url('/admin-dashboard')); ?>" <?php endif; ?>
                                        <?php else: ?>
                                            href="<?php echo e(url('/')); ?><?php echo e($menu->link); ?>"
                                            <?php endif; ?>
                                            <?php endif; ?>
                                            <?php if($menu->type == 'dCourse'): ?> href="<?php echo e(route('course-Details', $menu->element_id)); ?>" <?php endif; ?>
                                            <?php if($menu->type == 'dCourseCategory'): ?> href="<?php echo e(route('view-course-category', $menu->element_id)); ?>" <?php endif; ?>
                                            <?php if($menu->type == 'dNewsCategory'): ?> href="<?php echo e(route('view-news-category', $menu->element_id)); ?>" <?php endif; ?>
                                            <?php if($menu->type == 'dNews'): ?> href="<?php echo e(route('news-Details', $menu->element_id)); ?>" <?php endif; ?>
                                            <?php if($menu->type == 'customLink'): ?> href="<?php echo e($menu->link); ?>" <?php endif; ?>
                                            >
                                            <?php if($menu->link == '/login'): ?>
                                                <?php if(!auth()->check()): ?>
                                                    <?php echo e($menu->title); ?>

                                                <?php else: ?>
                                                    <?php echo app('translator')->get('common.dashboard'); ?>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php echo e($menu->title); ?>

                                            <?php endif; ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if(moduleStatusCheck('Saas') and session('domain') == 'school'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo e(url('/institution-register-new')); ?>"
                                    target="_blank"><?php echo app('translator')->get('common.school_signup'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if(moduleStatusCheck('ParentRegistration') && $is_registration_permission && $is_registration_permission == 1): ?>

                            <li class="nav-item">
                                <a class="nav-link"
                                    href="<?php echo e(route('parentregistration/registration', $reg_setting->url)); ?>"><?php echo app('translator')->get('student.student_registration'); ?></a>
                            </li>

                        <?php endif; ?>
                        <?php if(moduleStatusCheck('Certificate')): ?>
                            <li class="nav-item"> <a class="nav-link" href="<?php echo e(route('certificate.verify-certificate')); ?>"><?php echo e(__('certificate.Certificate')); ?></a></li>
                        <?php endif; ?>

                        </ul>

                    </div>
                </div>
            </nav>
        </div>
    </header>
    <!--================ End Header Menu Area =================-->

    <?php echo $__env->yieldContent('main_content'); ?>

    <!--================Footer Area =================-->
    <footer class="footer_area section-gap-top">
        <div class="container">
            <div class="row footer_inner">
                <?php if(@$custom_link != ''): ?>
                    <div class="col-lg-3 col-sm-6">
                        <div class="footer-widget">
                            <div class="f_title">
                                <h4><?php echo e($custom_link->title1); ?></h4>
                            </div>
                            <div class="footer-list">
                                <nav>
                                    <ul>
                                        <?php if(moduleStatusCheck('ParentRegistration') == true): ?>
                                            <?php if($is_registration_permission && $is_registration_permission == 2): ?>
                                                <li>
                                                    <a
                                                        href="<?php echo e(route('parentregistration/registration', $reg_setting->url)); ?>">
                                                        <?php echo app('translator')->get('student.student_registration'); ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if(moduleStatusCheck('Certificate')): ?>
                                            <li class="nav-item"> <a  href="<?php echo e(route('certificate.verify-certificate')); ?>"><?php echo e(__('certificate.Certificate')); ?></a></li>
                                        <?php endif; ?>
                                        <?php if($custom_link->link_href1 != ''): ?>
                                            <li>
                                                <a href="<?php echo e($custom_link->link_href1); ?>">
                                                    <?php echo e($custom_link->link_label1); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if($custom_link->link_href5 != ''): ?>
                                            <li>
                                                <a href="<?php echo e($custom_link->link_href5); ?>">
                                                    <?php echo e($custom_link->link_label5); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if($custom_link->link_href9 != ''): ?>
                                            <li>
                                                <a href="<?php echo e($custom_link->link_href9); ?>">
                                                    <?php echo e($custom_link->link_label9); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if($custom_link->link_href13 != ''): ?>
                                            <li>
                                                <a href="<?php echo e($custom_link->link_href13); ?>">
                                                    <?php echo e($custom_link->link_label13); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="footer-widget">
                            <div class="f_title">
                                <h4><?php echo e($custom_link->title2); ?></h4>
                            </div>
                            <div class="footer-list">
                                <nav>
                                    <ul>
                                        <?php if($custom_link->link_href2 != ''): ?>
                                            <li>
                                                <a href="<?php echo e($custom_link->link_href2); ?>">
                                                    <?php echo e($custom_link->link_label2); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if($custom_link->link_href6 != ''): ?>
                                            <li>
                                                <a href="<?php echo e(url($custom_link->link_href6)); ?>">
                                                    <?php echo e($custom_link->link_label6); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if($custom_link->link_href10 != ''): ?>
                                            <li>
                                                <a href="<?php echo e($custom_link->link_href10); ?>">
                                                    <?php echo e($custom_link->link_label10); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if($custom_link->link_href14 != ''): ?>
                                            <li>
                                                <a href="<?php echo e($custom_link->link_href14); ?>">
                                                    <?php echo e($custom_link->link_label14); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="footer-widget">
                            <div class="f_title">
                                <h4><?php echo e($custom_link->title3); ?></h4>
                            </div>
                            <div class="footer-list">
                                <nav>
                                    <ul>
                                        <?php if($custom_link->link_href3 != ''): ?>
                                            <li>
                                                <a href="<?php echo e($custom_link->link_href3); ?>">
                                                    <?php echo e($custom_link->link_label3); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if($custom_link->link_href7 != ''): ?>
                                            <li>
                                                <a href="<?php echo e($custom_link->link_href7); ?>">
                                                    <?php echo e($custom_link->link_label7); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if($custom_link->link_href11 != ''): ?>
                                            <li>
                                                <a href="<?php echo e($custom_link->link_href11); ?>">
                                                    <?php echo e($custom_link->link_label11); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if($custom_link->link_href15 != ''): ?>
                                            <li>
                                                <a href="<?php echo e($custom_link->link_href15); ?>">
                                                    <?php echo e($custom_link->link_label15); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="footer-widget">
                            <div class="f_title">
                                <h4><?php echo e($custom_link->title4); ?></h4>
                            </div>
                            <div class="footer-list">
                                <nav>
                                    <ul>
                                        <?php if($custom_link->link_href4 != ''): ?>
                                            <li>
                                                <a href="<?php echo e($custom_link->link_href4); ?>">
                                                    <?php echo e($custom_link->link_label4); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if($custom_link->link_href8 != ''): ?>
                                            <li>
                                                <a href="<?php echo e($custom_link->link_href8); ?>">
                                                    <?php echo e($custom_link->link_label8); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if($custom_link->link_href12 != ''): ?>
                                            <li>
                                                <a href="<?php echo e($custom_link->link_href12); ?>">
                                                    <?php echo e($custom_link->link_label12); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if($custom_link->link_href16 != ''): ?>
                                            <li>
                                                <a href="<?php echo e($custom_link->link_href16); ?>">
                                                    <?php echo e($custom_link->link_label16); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row single-footer-widget">
                <div class="col-lg-8 col-md-9">
                    <div class="copy_right_text">
                        <?php if($setting->copyright_text): ?>
                            <p><?php echo $setting->copyright_text; ?></p>
                        <?php else: ?>
                            Copyright © 2019 All rights reserved | This application is made with by Codethemes
                        <?php endif; ?>
                    </div>
                </div>
                <?php if($social_permission): ?>
                    <div class="col-lg-4 col-md-3">
                        <div class="social_widget">
                            <?php $__currentLoopData = $social_icons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social_icon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(@$social_icon->url != ''): ?>
                                    <a href="<?php echo e(@$social_icon->url); ?>">
                                        <i class="<?php echo e($social_icon->icon); ?>"></i>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </footer>
    <!--================End Footer Area =================-->

    
    <script src="<?php echo e(asset('public/backEnd/')); ?>/vendors/js/jquery-ui.js"></script>
    <script src="<?php echo e(asset('public/backEnd/')); ?>/vendors/js/popper.js"></script>
    <script src="<?php echo e(asset('public/backEnd/')); ?>/vendors/js/bootstrap.min.js"></script>
    <script src="<?php echo e(asset('public/backEnd/')); ?>/vendors/js/nice-select.min.js"></script>
    <script src="<?php echo e(asset('public/backEnd/')); ?>/vendors/js/jquery.magnific-popup.min.js"></script>
    <script src="<?php echo e(asset('public/backEnd/')); ?>/vendors/js/raphael-min.js"></script>
    <script src="<?php echo e(asset('public/backEnd/')); ?>/vendors/js/morris.min.js"></script>
    <script src="<?php echo e(asset('public/backEnd/')); ?>/vendors/js/owl.carousel.min.js"></script>
    <script src="<?php echo e(asset('public/backEnd/')); ?>/vendors/js/moment.min.js"></script>
    <script src="<?php echo e(asset('public/backEnd/')); ?>/vendors/js/print/bootstrap-datetimepicker.min.js"></script>
    <script src="<?php echo e(asset('public/backEnd/')); ?>/vendors/js/bootstrap-datepicker.min.js"></script>


    <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyDs3mrTgrYd6_hJS50x4Sha1lPtS2T-_JA">
    </script>
    <script src="<?php echo e(asset('public/backEnd/')); ?>/js/main.js"></script>
    <script src="<?php echo e(asset('public/backEnd/')); ?>/js/custom.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?php echo e(asset('public/backEnd/')); ?>/js/developer.js"></script>
    <?php echo Toastr::message(); ?>

    <?php echo $__env->yieldContent('script'); ?>
    
    <?php echo $__env->yieldPushContent(config('pagebuilder.site_script_var')); ?>

    <?php
        $school_id = @Auth::user()->school_id;
        $tawk_is_enable = @App\Models\Plugin::where('school_id', $school_id)->where('name', 'tawk')->first()->is_enable ?? 0;
        $messenger_is_enable = @App\Models\Plugin::where('school_id', $school_id)->where('name', 'messenger')->first()->is_enable ?? 0;
    ?>

    
    <?php if (! (request()->is('/'))): ?>
        <?php if($tawk_is_enable == 1): ?>
            <div class="tawk-min-container tawk-test">
                <script type="text/javascript">
                    var Tawk_API=Tawk_API||{},Tawk_LoadStart=new Date();
                    (function(){var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                        s1.async=true;s1.src=`https://embed.tawk.to/<?php echo $__env->make('plugins.tawk_to', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>`;
                        s1.charset='UTF-8';s1.setAttribute('crossorigin','*');s0.parentNode.insertBefore(s1,s0);
                    })();
                </script>
            </div>
        <?php endif; ?>

        <script src="<?php echo e(asset('public/whatsapp-support/scripts.js')); ?>"></script>

        <?php if($messenger_is_enable == 1): ?>
            <div class="messengerContainer">
                <!-- Messenger Chat Plugin Code -->
                <div id="fb-root"></div>
                <!-- Your Chat Plugin code -->
                <div id="fb-customer-chat" class="fb-customerchat"></div>
                <script>
                    var chatbox=document.getElementById('fb-customer-chat');
                    chatbox.setAttribute("page_id","<?php echo $__env->make('plugins.messenger', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>");
                    chatbox.setAttribute("attribution","biz_inbox");
                </script>
                <!-- Your SDK code -->
                <script>
                    window.fbAsyncInit=function(){FB.init({xfbml:true,version:'v18.0'});};
                    (function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];
                        if(d.getElementById(id))return;
                        js=d.createElement(s);js.id=id;
                        js.src='https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
                        fjs.parentNode.insertBefore(js,fjs);
                    }(document,'script','facebook-jssdk'));
                </script>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <style>
        .messengerContainer:hover {
            cursor: pointer;
        }
    </style>
    <?php if($messenger_position == 'right'): ?> 
        <style>
            .messengerContainer {
                position: fixed;
                bottom: 85px;
                right: 22px;
                width: 48px;
                height: 45px;
                border-radius: 50%;
                color: white;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 24px;
                z-index: 3;
                box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 50px;
            }
        </style>
    <?php elseif($messenger_position == 'left'): ?> 
        <style>
            .messengerContainer {
                position: fixed;
                bottom: 85px;
                width: 48px;
                height: 45px;
                left: 22px;
                border-radius: 50%;
                color: white;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 24px;
                z-index: 3;
                box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 50px;
            }
        </style>
    <?php endif; ?>

    <?php
        $school_id = @Auth::user()->school_id;
        $tawk_is_enable = @App\Models\Plugin::where('school_id', $school_id)->where('name', 'tawk')->first()->is_enable;
        $messenger_is_enable = @App\Models\Plugin::where('school_id', $school_id)->where('name', 'messenger')->first()->is_enable;
    ?>

    <?php if($tawk_is_enable == 1): ?>
        <div class="tawk-min-container tawk-test">
            <script type="text/javascript">
                var Tawk_API=Tawk_API||{},
                Tawk_LoadStart=new Date();
                (function(){ var s1=document.createElement("script"),
                s0=document.getElementsByTagName("script")[0];
                s1.async=true; s1.src=`https://embed.tawk.to/<?php echo $__env->make('plugins.tawk_to', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>`;
                s1.charset='UTF-8'; s1.setAttribute('crossorigin','*');
                s0.parentNode.insertBefore(s1,s0); })();
            </script>
        </div>  
    <?php endif; ?>

    <script src="<?php echo e(asset('public/whatsapp-support/scripts.js')); ?>"></script>
    

    <?php if($messenger_is_enable == 1): ?>
        <div class="messengerContainer">
            <!-- Messenger Chat Plugin Code -->
            <div id="fb-root"></div>
        
            <!-- Your Chat Plugin code -->
            <div id="fb-customer-chat" class="fb-customerchat">
            </div>
        
            <script>
            var chatbox = document.getElementById('fb-customer-chat');
            chatbox.setAttribute("page_id", "<?php echo $__env->make('plugins.messenger', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>");
            chatbox.setAttribute("attribution", "biz_inbox");
            </script>
        
            <!-- Your SDK code -->
            <script>
            window.fbAsyncInit = function() {
                FB.init({
                xfbml            : true,
                version          : 'v18.0'
                });
            };
        
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
            </script>
        </div>
    <?php endif; ?>
    
    <script>
        var position = '<?php echo e($position); ?>';
        var tawkposition = '';
    
        if(position == 'left'){
            tawkposition = 'bl';
        }else{
            tawkposition = 'br';
        }
        var Tawk_API = Tawk_API || {};
    
        Tawk_API.customStyle = {
            visibility : {
                desktop : {
                    position : tawkposition,
                    xOffset : 0,
                    yOffset : 20
                },
                mobile : {
                    position : tawkposition,
                    xOffset : 0,
                    yOffset : 0
                },
                bubble : {
                    rotate : '0deg',
                     xOffset : -20,
                     yOffset : 0
                }
            }
        };
    </script>
    <?php if(moduleStatusCheck('WhatsappSupport')): ?>
        <?php echo $__env->make('whatsappsupport::partials._popup', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
</body>

</html>
<?php /**PATH /media/supreme/627ADCCA7ADC9BDB/Users/Ayiik/Documents/Code/MyBidhaa/School Management/resources/views/frontEnd/home/front_master.blade.php ENDPATH**/ ?>