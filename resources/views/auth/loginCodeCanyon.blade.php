@php
$gs = generalSetting();
@endphp
<!DOCTYPE html>
@php
App::setLocale(getUserLanguage());
$ttl_rtl = userRtlLtl();

$css = "background: url('../../../CC.png') no-repeat center; background-size: cover;";
@endphp
<html lang="{{ app()->getLocale() }}" @if (isset($ttl_rtl) && $ttl_rtl==1) dir="rtl" class="rtl" @endif>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset(generalSetting()->favicon) }}" type="image/png" />
    <title>@lang('auth.login')</title>
    <meta name="_token" content="{!! csrf_token() !!}" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/vendors/css/bootstrap.css" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/vendors/css/themify-icons.css" />

    <link rel="stylesheet" href="{{ url('/') }}/public/backEnd/vendors/css/nice-select.css" />
    <link rel="stylesheet" href="{{ url('/') }}/public/backEnd/vendors/js/select2/select2.css" />

    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/vendors/css/toastr.min.css" />
    <link rel="stylesheet" href="{{ asset('public/frontend/') }}/css/{{ activeStyle()->path_main_style }}" />
    <x-root-css />
    @if (isset($ttl_rtl) && $ttl_rtl==1)
    <link rel="stylesheet" href="{{ url('public/backEnd/') }}/assets/vendors/vendors_static_style.css" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/rtl/style.css') }}" />
        @endif
</head>

<body class="login admin login_screen_body" style=" {{ $css }} ">
    <style>
        .login_screen_body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 30px 0;
            grid-gap: 20px;
            position: relative;
            isolation: isolate;
            background-color: #140b24;
        }

        .login_screen_body::before {
            content: "";
            position: fixed;
            inset: 0;
            background:
                linear-gradient(135deg, rgba(18, 11, 35, 0.82), rgba(64, 27, 98, 0.58)),
                radial-gradient(circle at top left, rgba(245, 197, 24, 0.18), transparent 34%);
            z-index: -1;
        }

        @media (max-width: 991px) {
            .login.admin.hight_100 .login-height .form-wrap {
                padding: 50px 8px;
            }

            .login-area .login-height {
                min-height: auto;
            }
        }

        body {
            height: 100%;
        }

        hr {
            background: linear-gradient(90deg, var(--gradient_1) 0%, #c738d8 51%, var(--gradient_1) 100%) !important;
            height: 1px !important;
        }

        .invalid-select strong {
            font-size: 11px !important;
        }

        .login-area .form-group i {
            position: absolute;
            top: 12px;
            left: 0;
        }

        .login_screen {
            padding: 12px 14px 30px;
        }

        .login_screen .form-wrap {
            padding: 34px 32px !important;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(255, 255, 255, 0.32);
            backdrop-filter: blur(14px);
            box-shadow: 0 28px 70px rgba(12, 7, 24, 0.34);
        }

        .login_screen .logoimage {
            max-width: 210px;
            width: 100%;
            object-fit: contain;
            margin-bottom: 18px;
        }

        .login_intro {
            margin-bottom: 24px;
        }

        .login_kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(91, 45, 142, 0.08);
            color: #5b2d8e;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .login_screen .form-wrap h5 {
            color: #0e94f1;
            font-size: clamp(1.8rem, 3vw, 2.3rem);
            font-weight: 800;
            letter-spacing: 0;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(34, 17, 51, 0.28);
        }

        .login_subtitle {
            color: #5d5570;
            font-size: 15px;
            line-height: 1.7;
            max-width: 420px;
            margin: 0 auto;
        }

        .login_screen .form-control {
            min-height: 58px;
            border-radius: 16px;
            border: 1px solid rgba(91, 45, 142, 0.14);
            background: rgba(255, 255, 255, 0.82);
            padding-left: 46px;
            color: #241436;
            font-size: 15px;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .login_screen .form-control:focus {
            outline: none;
            border-color: rgba(91, 45, 142, 0.45);
            box-shadow: 0 0 0 4px rgba(91, 45, 142, 0.1);
            background: #fff;
        }

        .login_screen .input-group-addon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
            color: #6f5c8c;
        }

        .login_screen .checkbox label,
        .login_screen .d-flex a {
            color: #4f4664;
            font-size: 14px;
        }

        .login_screen .primary-btn.fix-gr-bg {
            min-height: 58px;
            width: 100%;
            border-radius: 16px;
            border: none;
            background: linear-gradient(135deg, #5b2d8e, #7c43bf);
            box-shadow: 0 16px 32px rgba(91, 45, 142, 0.24);
        }

        .grid__button__layout {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-gap: 15px;
        }

        .grid__button__layout button {
            font-size: 11px;
            margin: 0 !important;
            padding: 0;
            height: 31px;
            line-height: 31px;
        }

        @media (max-width: 575.98px) {
            .login_screen .form-wrap {
                padding: 24px 18px !important;
                border-radius: 22px;
            }

            .grid__button__layout {
                grid-template-columns: repeat(2, 1fr);
                grid-gap: 10px;
            }
        }
    </style>

    <!--================ Start Login Area =================-->
    <section class="login-area up_login login_screen">

        <div class="container">

            @if (config('app.app_sync') and isset($schools) and !session('domain'))

            <div class="row justify-content-center">

                @foreach ($schools as $school)
                <div class="col-md-3">
                    <h4 class="text-center text-white">@lang('auth.school') {{ $loop->iteration }}</h4>
                    <hr>
                    <a target="_blank" href="//{{ $school->domain . '.' . config('app.short_url') }}/home"
                        class="primary-btn fix-gr-bg  mt-10 text-center col-lg-12">{{ Str::limit($school->school_name, 20, '...') }}</a>
                </div>
                @endforeach


            </div>
            @endif


            <input type="hidden" id="url" value="{{ url('/') }}">
            <div class="row login-height justify-content-center align-items-center mb-30 mt-30">
                <div class="col-lg-6 col-md-8">
                    <div class="form-wrap text-center">
                        <div class="logo-container">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset(generalSetting()->logo) }}" alt="" class="logoimage">
                            </a>
                        </div>

                        <div class="login_intro">
                            <div class="login_kicker">Secure Access</div>
                            <p class="login_subtitle">Sign in to manage students, communication, finance, and day-to-day school operations from one place.</p>
                        </div>

                        <h5 class="text-uppercase">@lang('auth.login_details')</h5>

                        <?php if(session()->has('message-success') != ""): ?>
                        <?php if(session()->has('message-success')): ?>
                        <p class="text-success"><?php echo e(session()->get('message-success')); ?></p>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php if(session()->has('message-danger') != ""): ?>
                        <?php if(session()->has('message-danger')): ?>
                        <p class="text-danger"><?php echo e(session()->get('message-danger')); ?></p>
                        <?php endif; ?>
                        <?php endif; ?>
                        <form method="POST" class="" action="<?php echo e(route('login')); ?>">
                            <?php echo csrf_field(); ?>

                            <div class="form-group input-group mb-4">

                                <input type="hidden" name="username" id="username-hidden">

                                 
                                <div class="form-group input-group mb-4">
                                    <span class="input-group-addon">
                                        <i class="ti-email"></i>
                                    </span>
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        type="text" name='email' id="email-address"
                                        placeholder="@lang('auth.enter_email_address')" value="{{ old('email') }}" />
                                </div>
                                @if ($errors->has('email'))
                                <span class="text-danger text-left mb-15" role="alert">
                                    {{ $errors->first('email') }}
                                </span>
                                @endif

                                <div class="form-group input-group mb-4">
                                    <span class="input-group-addon">
                                        <i class="ti-key"></i>
                                    </span>
                                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                        type="password" name='password' id="password"
                                        placeholder="@lang('auth.enter_password')" />
                                </div>
                                @if ($errors->has('password'))
                                <span class="text-danger text-left mb-15" role="alert">
                                    {{ $errors->first('password') }}
                                </span>
                                @endif

                                <div class="d-flex form-group input-group justify-content-between align-items-center">
                                    <div class="checkbox ">
                                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label for="rememberMe">@lang('auth.remember_me')</label>
                                    </div>
                                    <div>
                                        <a href="<?php echo e(route('recoveryPassord')); ?>">@lang('auth.forget_password')
                                            ?</a>
                                    </div>
                                </div>

                                <div class="form-group mt-30 mb-30 flex-fill">
                                    <button type="submit" class="primary-btn fix-gr-bg" id="btnsubmit">
                                        <span class="ti-lock mr-2"></span>
                                        @lang('auth.login')
                                    </button>
                                </div>
                        </form>
                    </div>

                </div>
            </div>


        </div>
        @if (config('app.app_sync'))
        <div class="row justify-content-center align-items-center" style="">
            <div class="col-lg-6 col-md-8">
                <div class="grid__button__layout">
                    @foreach ($users as $user)
                    @if ($user)
                    <form method="POST" class="loginForm" action="{{ route('login') }}">
                        @csrf()
                        <input type="hidden" name="email" value="{{ $user[0]->email }}">
                        <input type="hidden" name="auto_login" value="true">
                        <button type="submit"
                            class="primary-btn fix-gr-bg  mt-10 text-center col-lg-12 text-nowrap">{{ $user[0]->roles->name }}</button>
                    </form>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </section>
    <!--================ Start End Login Area =================-->

    <!--================ Footer Area =================-->
    <footer class="footer_area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 text-center">
                    <p class="mb-0">{!! generalSetting()->copyright_text !!}</p>
                </div>
            </div>
        </div>
    </footer>
    <!--================ End Footer Area =================-->

    <script src="{{ asset('public/backEnd/') }}/vendors/js/jquery-3.2.1.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/vendors/js/popper.js"></script>
    <script src="{{ asset('public/backEnd/') }}/vendors/js/bootstrap.min.js"></script>
    <script src="{{ url('/') }}/public/backEnd/vendors/js/nice-select.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/js/login.js"></script>
    <script type="text/javascript" src="{{ asset('public/backEnd/') }}/vendors/js/toastr.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#email-address").keyup(function () {
                $("#username-hidden").val($(this).val());
            });
        });
    </script>

    {!! Toastr::message() !!}


</body>

</html>
