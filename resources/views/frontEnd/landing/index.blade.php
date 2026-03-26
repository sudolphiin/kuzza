<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Kuzza Education ERP & Management System</title>
    <meta name="description" content="Kuzza is a modern, feature-rich education management platform for schools, academies, and institutions.">
    <meta name="keywords" content="Kuzza, Education ERP, School Management, Academy Management, Teal Theme">
    <meta name="ahrefs-site-verification" content="578d1aa1e01051e3ac46df77d602eb6846f676a092e33c9befa5ce2395403bd2">
    <meta name="google-site-verification" content="oXBw6wz6Ie5UTDB4h1JRUXHDZE2n-uGO2lm1HVYcA-c" />
    <meta name="yandex-verification" content="dc2fe84459064626" />

    <link rel="icon" type="image/png" href="{{ asset('public/landing/img/favicon.png') }}">
    <x-root-css/>
    <style type="text/css">
        :root {
            --gradient_1: #38b2ac;
            --main_teal: #20b2aa;
        }
        .custome-button{
            padding: 5px 10px !important;
            border: 1px solid var(--main_teal) !important;
            border-radius: 20px !important;
            width: 60%;
            margin: 0 auto;
            font-size: 16px !important;
            background-image: linear-gradient(to left, var(--gradient_1) 0%, var(--main_teal) 100%);
            color: white !important;
        }
        .banner_part, .main_menu, .footer-area, .feature_part {
            background: linear-gradient(135deg, var(--gradient_1) 0%, var(--main_teal) 100%) !important;
        }
        .btn_1, .btn_2, .btn {
            background-color: var(--main_teal) !important;
            border-color: var(--main_teal) !important;
        }
        .btn_1:hover, .btn_2:hover, .btn:hover {
            background-color: #319795 !important;
            border-color: #319795 !important;
        }
    </style>
    <script type="application/ld+json">
        [ {
          "@context" : "http://schema.org",
          "@type" : "SoftwareApplication",
          "name" : "Kuzza - Best School Management software system",
          "url" : "{{ url('/') }}",
          "author" : {
            "@type" : "Company",
            "name" : "Spondon IT"
          },
          "datePublished" : "2019-05-28",
          "publisher" : {
            "@type" : "School Management ERP software",
            "name" : "Kuzza"
          },
          "applicationCategory" : "ERP Software",
          "downloadUrl" : "{{ url('/') }}",
          "operatingSystem" : "2019",
          "requirements" : "in a complete",
          "screenshot" : "{{ asset('public/landing/img/dashboard_preview.png') }}",
          "softwareVersion" : "2.0",

          "review" : {
            "@type" : "Review",
            "reviewRating" : {
              "@type" : "Rating",
              "ratingValue" : "5",
              "bestRating" : "5",
              "worstRating" : ""
            },
            "reviewBody" : "Query\n                                Account"
          }
        } ]
    </script>


    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:site" content="@KuzzaEdu"/>
    <meta name="twitter:creator" content="@KuzzaEdu"/>
    <meta name="twitter:url" content="{{ url('/') }}"/>
    <meta name="twitter:title" content="The Ultimate Education Management System For School, Institute & Academy - Kuzza"/>
    <meta name="twitter:description" content="Kuzza is a modern School management system and ERP-based software. Try the free demo today! Kuzza offers 100+ features, well-documented and the latest academic management software for schools, universities and educational institutes."/>
    <meta property="og:image" content="{{ asset('public/landing/img/dashboard_preview.png') }}"/>


    <!-- Open Graph data -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url('/') }}"/>
    <meta property="og:image" content="{{ asset('public/landing/img/dashboard_preview.png') }}"/>
    <meta property="og:title" content="School Management ERP Software & School Management System - Kuzza"/>

    <meta property="og:description" content="Kuzza is a modern School management system and ERP-based software. Try the free demo today! Kuzza offers 100+ features for schools, universities and educational institutes."/>
    <meta property="og:site_name" content="Kuzza" />

    <link rel="canonical" href="{{ url('/') }}" hreflang="en-us" />

    <link rel="icon" href="{{asset('public/landing/img/favicon.png')}}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('public/landing/css/bootstrap.min.css')}}">
    <!-- animate CSS -->
    <link rel="stylesheet" href="{{asset('public/landing/css/animate.css')}}">
    <!-- themify CSS -->
    <link rel="stylesheet" href="{{asset('public/landing/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/font-awesome.min.css"/>
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="{{asset('public/landing/css/magnific-popup.css')}}">
    <!-- style CSS -->
    <link rel="stylesheet" href="{{asset('public/landing/css/style.css')}}">

</head>

<body>
<!-- banner part start-->
<section class="banner_part">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-xl-5">
                <div class="banner_text">
                    <div class="banner_text_iner">
                        <h1>The Ultimate Education Management System for Schools, Institutes & Academies</h1>
                        <p>Managing various administrative tasks in one place is now quite easy and time-saving with Kuzza. Give your valued time to your institute and increase next-generation productivity for our society.</p>
                        @if(!moduleStatusCheck('Saas'))
                            <a  class="btn_1" target="_blank" href="mailto:hello@aorasoft.com?subject=Request for Kuzza SaaS Application Demo!&body=Could you please arrange a demo for the Kuzza SaaS application at your earliest convenience?"> <i class="ti-email"></i> For Saas Demo</a>
                        @endif
                        <a href="{{url('/home')}}" class="btn_2" target="_blank"> <i class="ti-package"></i> Try Live Demo</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- banner part start-->

<!--::header part start::-->
<section class="main_menu" id="sticky_menu">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="navbar-brand" href="{{url('/')}}">
                        <img src="{{ asset('public/landing/img/logo.png') }}" alt="Kuzza" style="max-width:180px;max-height:60px;object-fit:contain;"> </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse main-menu-item justify-content-center"
                         id="navbarSupportedContent">
                        <ul class="navbar-nav align-items-center">
                            <li class="nav-item active">
                                <a class="nav-link" href="{{url('/login')}}" target="_blank" >Demo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link page-scroll" href="#modulus">Modules</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link page-scroll" href="#Components">Features</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link page-scroll" href="#Support">Support</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('/home')}}" target="_blank" >Frontend</a>
                            </li>
                        </ul>
                    </div>
                    <!-- <a class="btn_1 d-none d-lg-block" target="_blank" href="http://salespanel.infixedu.com/">Purchase</a> -->
                    <a class="btn_1 d-none d-lg-block" target="_blank" href="https://aorasoft.com">Purchase</a>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- Header part end-->

<!-- feature_part start-->
<section class="feature_part section_padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-md-8">
                <div class="section_tittle text-center">
                    <h5>Amazing Features</h5>
                    <h2>Some Features that make
                        Us Proud</h2>
                    <p>Looking forward to something different & unique! Here we are with few that never expected in
                        others. </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-layout-grid3"></i></span>
                        <h4>Tons of Features</h4>
                        <p>Kuzza has all in one place. You’ll find everything you are looking for in an education
                            management system software. </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-layout-media-overlay"></i></span>
                        <h4>User Friendly Interface</h4>
                        <p>We care! User will never bothered in our real eye catchy user friendly UI & UX  Interface design.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-layers"></i></span>
                        <h4>Proper Documentation</h4>
                        <p>You know! Smart ideas always come to well planners. And Kuzza is smart for its well
                            documentation.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-headphone-alt"></i></span>
                        <h4>Powerful Support</h4>
                        <p>Explore in new support world! It’s now faster & quicker. You’ll
                            find us on Support Ticket, Email, Skype, WhatsApp.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- feature_part part start-->

<!-- learning part start-->
<section class="video_section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-7 col-lg-7">
                <div class="video_section_img">

                    <img src="http://i3.ytimg.com/vi/DhZ6p_tYnpk/maxresdefault.jpg" alt="">
                    <!-- <img src="{{asset('public/landing/img/vodeo_bg.png')}}" alt=""> -->
                    <div class="intro_video_icon">
                        <a id="play-video_1" class="video-play-button popup-youtube"
                           href="https://www.youtube.com/watch?v=DhZ6p_tYnpk">
                            <span class="ti-control-play"></span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-5 col-lg-5">
                <div class="video_section_text">
                    <h2>Take A look at Our Kuzza Overview in Video</h2>

                    <img src="{{asset('public/landing/img/Line.png')}}" alt="">
                    <p>What’s query you have in mind? How it works? Have look a Look in our videos you’ll crystal
                        clear about it.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- learning part end-->

<!-- feature_part start-->
<section class="all_feature" id="modulus">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-md-8">
                <div class="section_tittle text-center">
                    <h5>Complete Package</h5>
                    <h2>Every Single Module You Want That Are Available</h2>
                    <p>Curiosity is future of new discover. Explore all our single modules that will blow your mind!
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard_img">

                    <img src="{{asset('public/landing/img/dashboard_preview.png')}}" alt="">
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-medall"></i></span>
                        <h4>Admin Module</h4>
                        <p>Managing other accounts,
                            Manage Teacher, Student, Guardian etc
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-id-badge"></i></span>
                        <h4>Student Info </h4>
                        <p>Student Admission,
                            Student List,
                            Student Attendance, Promote, Reports, etc.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-briefcase"></i></span>
                        <h4>Teacher</h4>
                        <p>Uploading Content,
                            Material,
                            Assignment,
                            Syllabus
                            Downloads and many more.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-server"></i></span>
                        <h4>Fees Collection</h4>
                        <p>Fees Master
                            Collect Fees
                            Due fees searches
                            Discount and many more
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-wallet"></i></span>
                        <h4>Accounts</h4>
                        <p>Profit, Income, Expense
                            Search Query
                            Account List
                            Payment Methods etc.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-user"></i></span>
                        <h4>Human Resource</h4>
                        <p>Staff (Directory, Attendance, Reports)
                            Payroll
                            Designation
                            Department and more
                            .</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-archive"></i></span>
                        <h4>Examination</h4>
                        <p>Exam routine, Date & time
                            Schedule notice.
                            Seat plan
                            Mark sheet & Report etc.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-write "></i></span>
                        <h4>Academics</h4>
                        <p>Class Routine
                            Subjective assign
                            Teacher assign
                            Manage Subject etc.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-headphone-alt"></i></span>
                        <h4>Communication</h4>
                        <p>Notice Manage (Holiday, Events etc)
                            Massaging
                            Emailing
                            Reports and More
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-receipt"></i></span>
                        <h4>Library</h4>
                        <p>Book adding, removing,
                            Card issuing
                            Member listing & manage
                            Book category/list
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-package"></i></span>
                        <h4>Inventory</h4>
                        <p>Inventory Item (Listing, Storing, Categories)
                            Supply
                            Item Sell, Issuing etc
                            Item receiving etc.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-truck"></i></span>
                        <h4>Transport</h4>
                        <p>Roads,
                            Vehicles listing,
                            Schedule/routine, student transport
                            Reports etc.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-home"></i></span>
                        <h4>Dormitory</h4>
                        <p>Dormitory finding
                            Categories & Listing
                            Rooms monitoring
                            Reports etc.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature coming_soon">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-panel"></i></span>
                        <h4>Front CMS</h4>
                        <p>Out team working for this. Hopefully it will include our next update version. </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-pie-chart"></i></span>
                        <h4>Reports</h4>
                        <p>
                            Class reports,
                            student’s reports,
                            Progress card,
                            Attendant reports and many more.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-settings"></i></span>
                        <h4>System Settings</h4>
                        <p>General Settings,
                            Email, Permission Setup
                            Backup Restore,
                            System Update and more.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- feature_part part end-->

<!-- our_speciality start-->
<section class="our_speciality section_padding" id="Components">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-md-8">
                <div class="section_tittle text-center">
                    <h5>AMazing Features</h5>
                    <h2>More Features in Kuzza</h2>
                    <p>It's vast! Kuzza has more additional features that you would expect in a complete solution.</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-4 col-xl-2">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-dashboard"></i></span>
                        <p>Optimized <br>
                            Performance</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xl-2">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-reload"></i></span>
                        <p>One Click <br>
                            Update System</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xl-2">
                <div class="single_feature coming_soon1">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-loop"></i></span>
                        <p>Supports <br>
                            RESTful APIs</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xl-2">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-split-v-alt"></i></span>
                        <p>Clean <br>
                            Code Quality</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xl-2">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-wand"></i></span>
                        <p>Installation <br>
                            Wizard</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xl-2">

                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-home"></i></span>
                        <p>Multi <br>
                            Lingual</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xl-2">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-desktop"></i></span>
                        <p>Fully <br>
                            Responsive</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xl-2">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-align-right"></i></span>
                        <p>Supports <br>
                            Right-to-Left</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xl-2">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-paint-bucket"></i></span>
                        <p>Themes & Colors <br>
                            Styling Options</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xl-2">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-email"></i></span>
                        <p>Email Notification <br>
                            with Templates</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xl-2">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-bell"></i></span>
                        <p>Supports SMS <br>
                            Notification</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xl-2">

                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-write"></i></span>
                        <p>Printable <br>
                            Reports</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xl-2">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-plug"></i></span>
                        <p>Inbuilt <br>
                            Backup Tool</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xl-2">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-filter"></i></span>
                        <p>IP Filter <br>
                            & Block</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xl-2">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-clipboard"></i></span>
                        <p>Activity <br>
                            & Email Log</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xl-2">
                <div class="single_feature">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="ti-write"></i></span>

                        <p>Export <br>
                            Reports</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- our_speciality part end-->

<!-- our_speciality start-->
<section class="contact_us padding_top" id="Support">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-md-8">
                <div class="section_tittle text-center">
                    <h5>Application Support</h5>
                    <h2>Kuzza Support Centre</h2>
                    <p>Need faster help? Ask your queries here. You’ll get instant help from us.</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">

            <div class="col-md-6">
                <a href="https://ticket.aorasoft.com/infixedu" target="_blank">
                    <div class="single_feature">
                        <div class="single_feature_part">
                                <span class="single_feature_icon"><i class="ti-headphone-alt"></i>
                                <p class="custome-button">Submit a Ticket</p></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a class="support-link" target="_blank" href="https://ticket.aorasoft.com/infixedu">
                    <div class="single_feature">
                        <div class="single_feature_part">
                            <span class="single_feature_icon"><i class="ti-write"></i></span>
                            <p class="custome-button"> Documentation</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
<!-- our_speciality part end-->


<!-- footer part star-->
<footer class="footer_section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="footer_area_text">

                    <img src="{{asset('public/landing/img/logo.png')}}" alt="">
                    <h2>So Finally You Are Here! Now, All You Know About Our Features.</h2>
                    <p>We Believe! It won’t be a wrong decision in choosing Kuzza for your Institute.</p>
                    <!-- <a href="http://salespanel.infixedu.com/" class="footer_btn"> <i class="ti-shopping-cart-full"></i> -->
                    <a href="https://aorasoft.com" target="_blank" class="footer_btn"> <i class="ti-shopping-cart-full"></i>
                        <h3>Get Kuzza</h3>
                        <p>The Ultimate Education ERP</p>
                    </a>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="copyright_text">
                    <p> <img src="{{asset('public/landing/img/copyright.svg')}}" alt="#"> {{ date('Y') }} Kuzza - Ultimate Education ERP. All Rights
                        Reserved to <a href="#">Codetheme </a> .</p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer part end-->
<!--    <div class="skype-button bubble" data-bot-id="spondonit"> </div>
<script src="https://swc.cdn.skype.com/sdk/v1/sdk.min.js"></script>-->

<style>
    .whatsApp_icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #00C348;
        font-size: 48px;
        line-height: 40px;
        text-align: center;
        color: #fff;
        position: fixed;
        right: 20px;
        bottom: 20px;
        cursor: pointer;
        z-index: 100;
    }
    .whatsApp_icon i {
        padding: 15px;
        font-size: 30px;
    }
</style>
<a class="whatsApp_icon" style="background-color: #359334" href="https://wa.me/+96897002784" target="_blank">
    <i class="fa fa-whatsapp"></i>
</a>

<!-- jquery plugins here-->
<!-- jquery -->
<script src="{{asset('public/landing/js/jquery-1.12.1.min.js')}}"></script>
<!-- popper js -->
<script src="{{asset('public/landing/js/popper.min.js')}}"></script>
<!-- bootstrap js -->
<script src="{{asset('public/landing/js/bootstrap.min.js')}}"></script>
<!-- easing js -->
<script src="{{asset('public/landing/js/jquery.magnific-popup.js')}}"></script>
<!--  -->
<script src="{{asset('public/landing/js/jquery.easing.min.js')}}"></script>
<!-- custom js -->
<script src="{{asset('public/landing/js/custom.js')}}"></script>

</body>

</html>
