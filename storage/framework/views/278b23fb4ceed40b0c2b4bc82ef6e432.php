<?php $__env->startPush('css'); ?>
    <link rel="icon" type="image/png" href="<?php echo e(asset('public/landing/img/favicon.png')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('public/')); ?>/frontend/css/new_style.css"/>
    <style>
        /* On landing, hide the default front_master header so we can use the PageBuilder header instead */
        body.landing-page .header-area {
            display: none;
        }
        /* Hide the Edulia off-canvas mobile menu shell so it doesn't render as a vertical duplicate */
        body.landing-page .heading_mobile_menu {
            display: none;
        }
        /* Remove theme spacer below header on landing to avoid extra gap */
        body.landing-page .clear_head {
            display: none;
        }

        /* Header container positioning over hero */
        body.landing-page .heading {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 20;
            background: transparent;
            transition: background .5s ease, box-shadow .5s ease, transform .5s ease;
        }

        /* Header + hero logo base state (fully visible at top) */
        body.landing-page .heading_sub,
        body.landing-page .heading_main {
            opacity: 1;
            transform: translateY(0);
            /* lengthen/slide only – no fade */
            transition: transform .4s ease;
        }
        body.landing-page .heading_main {
            transform-origin: top center;
            transition: background .3s ease, box-shadow .3s ease, transform .3s ease;
        }
        body.landing-page .heading_main_logo img,
        body.landing-page .heading_logo img {
            opacity: 0;
            transform: scale(.85);
            transition: opacity .35s ease, transform .35s ease;
        }
        /* Transition phase: hero and header start to merge */
        body.landing-page.landing-scrolled .heading_sub,
        body.landing-page.landing-scrolled .heading_main {
            opacity: 1;
            transform: translateY(0);
        }
        /* Subtle "grow into place" animation for main menu during scroll */
        body.landing-page.landing-scrolled .heading_main {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-6px) scaleY(0.97);
        }
        body.landing-page.landing-scrolled .heading_main_logo img,
        body.landing-page.landing-scrolled .heading_logo img {
            opacity: .7;
            transform: scale(.9);
        }
        /* Fixed phase: top bar scrolls away, menu stays fixed */
        body.landing-page.landing-fixed .heading_sub {
            opacity: 0;
            pointer-events: none;
            transform: translateY(-40px);
        }
        body.landing-page.landing-fixed .heading_main {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: #ffffff;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
            z-index: 30;
            transform: translateY(0) scaleY(1);
            border-top: 0;
            margin-top: 0;
        }
        body.landing-page.landing-fixed .heading_main_logo img,
        body.landing-page.landing-fixed .heading_logo img {
            opacity: 1;
            transform: scale(1);
        }
        /* Prevent content jump: hero always occupies full viewport, header simply overlays it */
        body.landing-page.landing-scrolled .heading_main_logo img,
        body.landing-page.landing-scrolled .heading_logo img {
            opacity: 1;
            transform: scale(1);
        }
        .landing-hero {
            position: relative;
            min-height: 90vh;
            padding-top: 200px;
            background: linear-gradient(120deg, rgba(32, 178, 170, 0.92), rgba(56, 178, 172, 0.85)),
            url(<?php echo e($homePage && $homePage->image ? asset($homePage->image) : asset('public/landing/img/dashboard_preview.png')); ?>) center/cover no-repeat;
            border-radius: 0 0 120px 120px;
            overflow: hidden;
            color: #fff;
        }
        .landing-hero .banner-content {
            text-align: center;
            max-width: 720px;
            margin: 0 auto;
        }
        .landing-hero .banner-content h5 {
            text-transform: uppercase;
            letter-spacing: .4em;
            font-size: 22px !important;
            margin-bottom: 26px;
            color: rgba(255,255,255,.85);
            transition: transform .4s ease, opacity .4s ease;
        }
        .landing-hero .banner-content h2 {
            font-size: clamp(3.1rem, 5vw, 4.4rem);
            line-height: 1.12;
            margin-bottom: 24px;
            transition: transform .4s ease, opacity .4s ease;
        }
        .landing-hero .banner-content p {
            color: rgba(255,255,255,.92);
            max-width: 720px;
            font-size: 1.22rem !important;
            line-height: 1.8;
            margin: 0 auto;
            transition: transform .4s ease, opacity .4s ease;
        }
        .landing-hero .cta-group .btn-outline {
            border: 1px solid rgba(255,255,255,.4);
            color: #fff;
            padding: 13px 28px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background .3s, color .3s;
        }
        .landing-hero .cta-group {
            justify-content: center;
            gap: 16px;
        }
        .landing-hero .cta-group .primary-btn,
        .landing-hero .cta-group .btn-outline {
            transition: transform .4s ease, opacity .4s ease;
        }
        .landing-hero .cta-group .btn-outline:hover {
            background: rgba(255,255,255,.12);
            color: #fff;
        }
        .landing-hero-logo {
            display: block;
            max-width: 320px;
            width: 100%;
            height: auto;
            margin: 0 auto 28px;
            filter: brightness(0) invert(1);
            transition: transform .4s ease, opacity .4s ease;
        }
        /* Progressive hero animation driven by --hero-progress (0–1) */
        :root {
            --hero-progress: 0;
        }
        body.landing-page .landing-hero .banner-content h5 {
            transform: translateY(calc(-120px * var(--hero-progress)));
            opacity: calc(1 - var(--hero-progress));
        }
        body.landing-page .landing-hero .banner-content h2 {
            transform: translateX(calc(-200px * var(--hero-progress)));
            opacity: calc(1 - var(--hero-progress));
        }
        body.landing-page .landing-hero .banner-content p {
            transform: translateX(calc(200px * var(--hero-progress)));
            opacity: calc(1 - var(--hero-progress));
        }
        /* Lead CTA button drifts toward bottom-left, secondary toward bottom-right */
        body.landing-page .landing-hero .cta-group .primary-btn {
            transform:
                translateY(calc(180px * var(--hero-progress)))
                translateX(calc(-220px * var(--hero-progress)));
            opacity: calc(1 - var(--hero-progress));
        }
        body.landing-page .landing-hero .cta-group .btn-outline {
            transform:
                translateY(calc(180px * var(--hero-progress)))
                translateX(calc(220px * var(--hero-progress)));
            opacity: calc(1 - var(--hero-progress));
        }
        body.landing-page .landing-hero-logo {
            transform:
                scale(calc(1 - 0.4 * var(--hero-progress)))
                translateY(calc(-120px * var(--hero-progress)))
                translateX(calc(-160px * var(--hero-progress)));
            opacity: calc(1 - var(--hero-progress));
            pointer-events: none;
        }
        /* Force sticky/fixed header logo to use Kuzza branding */
        .heading_main_logo img,
        .heading_logo img,
        .navbar-brand img {
            content: url('<?php echo e(asset("logo.png")); ?>') !important;
        }
        .landing-card {
            border: 1px solid rgba(124, 50, 255, .1);
            border-radius: 16px;
            padding: 28px;
            height: 100%;
            box-shadow: 0 20px 40px rgba(15, 30, 65, .08);
            background: #fff;
        }
        .landing-icon {
            width: 50px;
            height: 50px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(32, 178, 170, .12);
            color: #20b2aa;
            font-size: 20px;
            margin-bottom: 16px;
        }
        .news-image {
            height: 200px;
            object-fit: cover;
        }
        .feature-grid .landing-card {
            border: none;
            box-shadow: none;
            background: #f9f7ff;
            transition: transform .2s;
        }
        .feature-grid .landing-card:hover {
            transform: translateY(-6px);
        }
        .section-heading {
            margin-bottom: 45px;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php
    $generalSetting = generalSetting();
    $brandify = function ($text) {
        return is_string($text) ? str_ireplace([
            'INFIX', 'Infix', 'infix', 'MyBidhaa', 'MYBIDHAA', 'mybidhaa', 'InfixEdu', 'INFIXEDU', 'School Management', 'SCHOOL MANAGEMENT', 'school management'
        ], 'Kuzza', $text) : $text;
    };
    $heroTitle = $brandify($homePage->long_title ?? 'Kuzza');
    $heroKicker = $brandify($homePage->title ?? 'Operate with confidence');
    $heroCopy = $brandify($homePage->short_description ?? 'Automate admissions, academics, finance, and communication without switching tabs.');
    $heroPrimaryCta = [
        'label' => $brandify($homePage->link_label ?? __('common.login')),
        'url' => $homePage->link_url ?? url('login'),
    ];
    $heroSecondaryCta = [
        'label' => 'Talk to our team',
        'url' => url('contact'),
    ];
    $courseTotal = $courseCount ?? $courses->count();
    $highlights = [
        ['icon' => 'ti-id-badge', 'title' => 'Admissions & Enrollment', 'copy' => 'Centralize inquiries, applications, and approvals with live status tracking.'],
        ['icon' => 'ti-comment-alt', 'title' => 'Community Communication', 'copy' => 'Push notices, newsletters, and urgent alerts to families instantly.'],
        ['icon' => 'ti-wallet', 'title' => 'Finance & Fees', 'copy' => 'Generate invoices, reconcile payments, and monitor dues in real time.'],
    ];
?>

<?php $__env->startSection('main_content'); ?>
    
    <?php echo $__env->make('pagebuilder.header.view', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="container-fluid px-0">
        <div class="landing-hero">
            <div class="container box-1420">
                <div class="banner-inner">
                    <div class="banner-content">
                        <h5><?php echo e($brandify($heroKicker)); ?></h5>
                        <img class="landing-hero-logo" src="<?php echo e(asset('logo.png')); ?>" alt="Kuzza" style="max-width:320px;max-height:120px;object-fit:contain;filter:none;" />
                        <p><?php echo e($brandify($heroCopy)); ?></p>
                        <div class="cta-group mt-4 d-flex gap-3 flex-wrap">
                            <a class="primary-btn fix-gr-bg semi-large" href="<?php echo e($heroPrimaryCta['url']); ?>">
                                <?php echo e($brandify($heroPrimaryCta['label'])); ?>

                            </a>
                            <a class="btn-outline" href="<?php echo e($heroSecondaryCta['url']); ?>">
                                <span class="ti-headphone-alt"></span> <?php echo e($heroSecondaryCta['label']); ?>

                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="fact-area section-gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="landing-card">
                        <div class="landing-icon"><i class="ti-user"></i></div>
                        <h4><?php echo app('translator')->get('dashboard.total_students'); ?></h4>
                        <p class="mb-0 h2"><?php echo e(number_format($totalStudents ?? 0)); ?></p>
                        <span class="text-muted">Enrolled learners</span>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="landing-card">
                        <div class="landing-icon"><i class="ti-blackboard"></i></div>
                        <h4><?php echo app('translator')->get('front_settings.faculty'); ?></h4>
                        <p class="mb-0 h2"><?php echo e(number_format($totalTeachers ?? 0)); ?></p>
                        <span class="text-muted">Active educators</span>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="landing-card">
                        <div class="landing-icon"><i class="ti-layers"></i></div>
                        <h4><?php echo app('translator')->get('front_settings.course_list'); ?></h4>
                        <p class="mb-0 h2"><?php echo e(number_format($courseTotal ?? 0)); ?></p>
                        <span class="text-muted">Curriculum tracks</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-gap feature-grid">
        <div class="container">
            <div class="section-heading text-center">
                <h5 class="text-uppercase text-muted">Made for every department</h5>
                <h2>Connect leadership, teachers, families, and finance from one workspace</h2>
            </div>
            <div class="row">
                <?php $__currentLoopData = $highlights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="landing-card">
                            <div class="landing-icon"><i class="<?php echo e($feature['icon']); ?>"></i></div>
                            <h4><?php echo e($feature['title']); ?></h4>
                            <p><?php echo e($feature['copy']); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    <?php if($about): ?>
        <section class="info-area section-gap-bottom">
            <div class="container">
                <div class="single-info row mt-40 align-items-center">
                    <div class="col-lg-6 col-md-12 text-center pr-lg-0 info-left">
                        <div class="info-thumb">
                            <img src="<?php echo e(asset($about->main_image)); ?>" class="img-fluid" alt="<?php echo e($brandify($about->main_title)); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 pl-lg-0 info-rigth">
                        <div class="info-content">
                            <h2><?php echo e($brandify($about->main_title)); ?></h2>
                            <p><?php echo e($brandify($about->main_description)); ?></p>
                            <?php if($about->button_url && $about->button_text): ?>
                                <a class="primary-btn fix-gr-bg semi-large" href="<?php echo e($about->button_url); ?>">
                                    <?php echo e($brandify($about->button_text)); ?>

                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if($courses->isNotEmpty()): ?>
        <section class="academics-area">
            <div class="container">
                <div class="row mb-40">
                    <div class="col-lg-6">
                        <h3 class="title"><?php echo app('translator')->get('front_settings.course_list'); ?></h3>
                    </div>
                    <div class="col-lg-6 text-lg-right text-left">
                        <a href="<?php echo e(url('course')); ?>" class="primary-btn small fix-gr-bg">
                            <?php echo app('translator')->get('front_settings.browse_all'); ?>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="academic-item">
                                <div class="academic-img">
                                    <img class="img-fluid" src="<?php echo e(asset($course->image)); ?>" alt="<?php echo e($brandify($course->title)); ?>">
                                </div>
                                <div class="academic-text">
                                    <h4>
                                        <a href="<?php echo e(url('course-Details/'.$course->id)); ?>"><?php echo e($brandify($course->title)); ?></a>
                                    </h4>
                                        <p><?php echo e($brandify(\Illuminate\Support\Str::limit(strip_tags($course->overview), 80))); ?></p>
                                        <a href="<?php echo e(url('course-Details/'.$course->id)); ?>" class="client-btn">
                                            <?php echo e(__('common.view')); ?>

                                        </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if($notice_board->isNotEmpty()): ?>
        <section class="notice-board-area section-gap-top">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-lg-12 d-flex justify-content-between align-items-center">
                        <h3 class="title mb-0"><?php echo app('translator')->get('communicate.notice_board'); ?></h3>
                        <a href="<?php echo e(url('home')); ?>" class="primary-btn small fix-gr-bg">
                            <?php echo app('translator')->get('front_settings.browse_all'); ?>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <?php $__currentLoopData = $notice_board; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="landing-card h-100">
                                <p class="date">
                                    <?php echo e($notice->publish_on ? dateConvert($notice->publish_on) : ''); ?>

                                </p>
                                <h4><?php echo e($brandify($notice->notice_title)); ?></h4>
                                <p><?php echo $brandify(\Illuminate\Support\Str::limit(strip_tags($notice->notice_message), 120)); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if($news->isNotEmpty()): ?>
        <section class="news-area section-gap-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 mb-4 d-flex justify-content-between align-items-center">
                        <h3 class="title mb-0"><?php echo app('translator')->get('front_settings.latest_news'); ?></h3>
                        <a href="<?php echo e(url('news-page')); ?>" class="primary-btn small fix-gr-bg">
                            <?php echo app('translator')->get('front_settings.browse_all'); ?>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="news-item">
                                <div class="news-img">
                                    <img class="img-fluid w-100 news-image" src="<?php echo e(asset($item->image)); ?>" alt="<?php echo e($brandify($item->news_title)); ?>">
                                </div>
                                <div class="news-text">
                                    <p class="date">
                                        <?php echo e($item->publish_date ? dateConvert($item->publish_date) : ''); ?>

                                    </p>
                                    <h4>
                                        <a href="<?php echo e(url('news-details/'.$item->id)); ?>"><?php echo e($brandify($item->news_title)); ?></a>
                                    </h4>
                                    <p><?php echo e($brandify(\Illuminate\Support\Str::limit(strip_tags($item->news_body), 90))); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if($events->isNotEmpty()): ?>
        <section class="events-area">
            <div class="container">
                <div class="row mb-40">
                    <div class="col-lg-12">
                        <h3 class="title"><?php echo app('translator')->get('communicate.event_list'); ?></h3>
                    </div>
                </div>
                <div class="row">
                    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="events-item">
                                <div class="card">
                                    <img class="card-img-top" src="<?php echo e(asset($event->uplad_image_file)); ?>" alt="<?php echo e($brandify($event->event_title)); ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo e($brandify($event->event_title)); ?></h5>
                                        <p class="card-text"><?php echo e($brandify($event->event_location)); ?></p>
                                        <div class="date">
                                            <?php echo e($event->from_date ? dateConvert($event->from_date) : ''); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if($testimonial->isNotEmpty()): ?>
        <section class="testimonial-area relative section-gap box-1420">
            <div class="overlay overlay-bg"></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="active-testimonial owl-carousel">
                        <?php $__currentLoopData = $testimonial; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="single-testimonial text-center">
                                <div class="d-flex justify-content-center">
                                    <div class="thumb">
                                        <img class="img-fluid rounded-circle testimonial-image"
                                             src="<?php echo e($value->image ? asset($value->image) : asset('public/uploads/sample.jpg')); ?>"
                                             alt="<?php echo e($brandify($value->name)); ?>">
                                    </div>
                                    <div class="meta text-left">
                                        <h4><?php echo e($brandify($value->name)); ?></h4>
                                        <p><?php echo e($brandify($value->designation)); ?>, <?php echo e($brandify($value->institution_name)); ?></p>
                                    </div>
                                </div>
                                <p class="desc">
                                    <?php echo e($brandify($value->description)); ?>

                                </p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if($contact_info): ?>
        <section class="contact_area section-gap-top">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="contact_info">
                            <div class="info_item">
                                <i class="ti-home"></i>
                                <h6><?php echo e($brandify($contact_info->address)); ?></h6>
                                <p><?php echo e($brandify($contact_info->address_text)); ?></p>
                            </div>
                            <div class="info_item">
                                <i class="ti-headphone-alt"></i>
                                <h6><a href="tel:<?php echo e($contact_info->phone); ?>"><?php echo e($brandify($contact_info->phone)); ?></a></h6>
                                <p><?php echo e($brandify($contact_info->phone_text)); ?></p>
                            </div>
                            <div class="info_item">
                                <i class="ti-envelope"></i>
                                <h6><a href="mailto:<?php echo e($contact_info->email); ?>"><?php echo e($brandify($contact_info->email)); ?></a></h6>
                                <p><?php echo e($brandify($contact_info->email_text)); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="landing-card">
                            <h4 class="mb-3"><?php echo app('translator')->get('front_settings.send_message'); ?></h4>
                            <form action="<?php echo e(url('send-message')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="primary_input mb-3">
                                    <input class="primary_input_field form-control" type="text" name="name" required>
                                    <label class="primary_input_label"><?php echo app('translator')->get('front_settings.Enter_your_name'); ?> *</label>
                                </div>
                                <div class="primary_input mb-3">
                                    <input class="primary_input_field form-control" type="email" name="email" required>
                                    <label class="primary_input_label"><?php echo app('translator')->get('front_settings.Enter_your_email'); ?> *</label>
                                </div>
                                <div class="primary_input mb-3">
                                    <input class="primary_input_field form-control" type="text" name="subject" required>
                                    <label class="primary_input_label"><?php echo app('translator')->get('front_settings.enter_subject'); ?> *</label>
                                </div>
                                <div class="primary_input mb-3">
                                    <textarea class="primary_input_field form-control" name="message" rows="4" required></textarea>
                                    <label class="primary_input_label"><?php echo app('translator')->get('front_settings.enter_message'); ?> *</label>
                                </div>
                                <button type="submit" class="primary-btn fix-gr-bg"><?php echo app('translator')->get('front_settings.send_message'); ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        document.addEventListener('DOMContentLoaded',function(){
            var b=document.body,
                d=document.documentElement,
                logoSrc=<?php echo json_encode(asset($generalSetting->logo ?? 'public/uploads/settings/logo.png'), 15, 512) ?>;

            b.classList.add('landing-page');
            document.querySelectorAll('.heading_main_logo img,.heading_logo img')
                .forEach(function(img){img.setAttribute('src',logoSrc);});

            function onScroll(){
                var y=window.scrollY||window.pageYOffset,
                    p=Math.max(0,Math.min(y/300,1));
                d.style.setProperty('--hero-progress',p.toString());

                if(y>40){b.classList.add('landing-scrolled');}
                else{b.classList.remove('landing-scrolled');b.classList.remove('landing-fixed');}

                if(y>160){b.classList.add('landing-fixed');}
                else if(y<=160){b.classList.remove('landing-fixed');}
            }

            onScroll();
            window.addEventListener('scroll',onScroll,{passive:true});
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('frontEnd.home.front_master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /media/supreme/627ADCCA7ADC9BDB/Users/Ayiik/Documents/Code/MyBidhaa/School Management/resources/views/frontEnd/landing/new.blade.php ENDPATH**/ ?>