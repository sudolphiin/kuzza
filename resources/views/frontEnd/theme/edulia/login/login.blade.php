@php
    $gs = isset($system_setting) ? $system_setting : generalSetting();
@endphp
<!DOCTYPE html>
@php
    App::setLocale(getUserLanguage());
    $ttl_rtl = userRtlLtl();
    $css = "background: url('../../../CC.png') no-repeat center; background-size: cover;";
@endphp
<html lang="{{ app()->getLocale() }}" @if (isset($ttl_rtl) && $ttl_rtl == 1) dir="rtl" class="rtl" @endif>

<head>
    <!-- All Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset($gs->favicon) }}" type="image/png" />
    <title>@lang('auth.login')</title>
    <meta name="_token" content="{!! csrf_token() !!}" />

    <!-- Fonts -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/css/bootstrap.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/css/fontawesome.all.min.css') }}">

    @if(userRtlLtl() ==1)
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/css/style_rtl.css') }}">
    @else
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/css/style.css') }}">
    @endif
        <style>
        body {
            min-height: 100vh;
            background: #140b24;
        }

        .login {
            position: relative;
            min-height: 100vh;
            padding: 42px 16px;
            display: grid;
            place-items: center;
            isolation: isolate;
        }

        .login::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(135deg, rgba(18, 11, 35, 0.82), rgba(64, 27, 98, 0.58)),
                radial-gradient(circle at top left, rgba(245, 197, 24, 0.18), transparent 34%);
            z-index: -1;
        }

        .login_wrapper {
            width: min(100%, 560px);
            padding: 34px;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(255, 255, 255, 0.32);
            backdrop-filter: blur(14px);
            box-shadow: 0 28px 70px rgba(12, 7, 24, 0.34);
        }

        .login_wrapper_logo img {
            max-width: 210px;
            width: 100%;
            object-fit: contain;
            margin-bottom: 18px;
        }

        .login_intro {
            margin-bottom: 26px;
            text-align: center;
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

        .login_wrapper_content h4 {
            margin-bottom: 10px;
            color: #0e94f1;
            font-size: clamp(1.8rem, 3vw, 2.35rem);
            font-weight: 800;
            text-shadow: 0 2px 10px rgba(34, 17, 51, 0.28);
        }

        .login_subtitle {
            margin: 0 auto;
            max-width: 420px;
            color: #5d5570;
            font-size: 15px;
            line-height: 1.7;
        }

        .input-control {
            position: relative;
            margin-bottom: 16px;
        }

        .input-control-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #6f5c8c;
            z-index: 2;
            font-size: 15px;
        }

        .input-control-input {
            width: 100%;
            min-height: 58px;
            padding: 16px 18px 16px 52px;
            border-radius: 16px;
            border: 1px solid rgba(91, 45, 142, 0.14);
            background: rgba(255, 255, 255, 0.82);
            color: #241436;
            font-size: 15px;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .input-control-input:focus {
            outline: none;
            border-color: rgba(91, 45, 142, 0.45);
            box-shadow: 0 0 0 4px rgba(91, 45, 142, 0.1);
            background: #fff;
        }

        .input-control input[type="submit"] {
            padding-left: 18px;
            background: linear-gradient(135deg, #5b2d8e, #7c43bf);
            color: #fff;
            font-weight: 700;
            border: none;
            box-shadow: 0 16px 32px rgba(91, 45, 142, 0.24);
        }

        .input-control input[type="submit"]:hover {
            transform: translateY(-1px);
        }

        .row_gap_24 {
            row-gap: 18px;
            align-items: center;
            justify-content: space-between;
            margin: 8px 0 8px;
        }

        .checkbox-title,
        #forget {
            color: #4f4664;
            font-size: 14px;
        }

        #forget:hover {
            color: #5b2d8e;
        }

        .text-danger.text-left {
            display: block;
            margin-top: -6px;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .row.row-gap-10 {
            row-gap: 12px;
            --bs-gutter-x: 12px;
            margin-top: 18px;
        }

        .row.row-gap-10 .input-control-input {
            min-height: 44px;
            padding: 10px 12px;
            border-radius: 12px;
            font-size: 12px;
            background: rgba(91, 45, 142, 0.06);
        }

        .row.row-gap-10 [class*=col-] {
            --bs-gutter-x: 12px;
        }

        .saas_school_top_five_link_show {
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid rgba(91, 45, 142, 0.12);
        }

        .saas_school_top_five_link_show .title {
            color: #332244;
            font-weight: 700;
            margin-bottom: 14px;
        }

        .link_to_school {
            display: block;
            padding: 10px 12px;
            border-radius: 12px;
            text-align: center;
            background: rgba(91, 45, 142, 0.06);
            color: #4a2c73;
        }

        @media only screen and (max-width: 767px) {
            .login {
                padding: 26px 12px;
            }

            .login_wrapper {
                padding: 24px 18px;
                border-radius: 22px;
            }

            .login_wrapper_content h4 {
                font-size: 1.65rem;
            }
        }

        @media (max-width: 480px) {
            .row.row-gap-10 [class*=col-] {
                width: 50%;
                max-width: 50%;
            }
        }
    </style>
</head>

<body>

    <section class="login" style="{{ $css }}">
        <div class="login_wrapper">

            {{--@if (config('app.app_sync') && isset($schools) && session('domain') == 'school')

                <div class="row justify-content-center">
                    @foreach ($schools as $school)
                        <div class="col-md-3">
                            <h4 class="text-center text-danger">@lang('auth.school') {{ $loop->iteration }}</h4>
                            <hr>
                            <a target="_blank" href="//{{ $school->domain . '.' . config('app.short_url') }}/home"
                                class="primary-btn fix-gr-bg  mt-10 text-center col-lg-12">{{ Str::limit($school->school_name, 20, '...') }}</a>
                        </div>
                    @endforeach
                </div>
            @endif--}}

            <!-- login form start -->
            <div class="login_wrapper_login_content">
                <div class="login_wrapper_logo text-center"><img src="{{ asset($gs->logo) }}"
                        alt=""></div>
                <div class="login_wrapper_content">
                    <div class="login_intro">
                        <div class="login_kicker">Secure Access</div>
                        <p class="login_subtitle">Sign in to manage students, communication, finance, and day-to-day school operations from one place.</p>
                    </div>
                    <h4>@lang('auth.login_details')</h4>
                    <form action="{{ route('login') }}" method='POST'>
                        @csrf
                        <input type="hidden" name="username" id="username-hidden">
                        <div class="input-control">
                            <label for="#" class="input-control-icon"><i class="fal fa-envelope"></i></label>
                            <input type="text" name="email" class="input-control-input"
                                placeholder="@lang('auth.enter_email_address')" value="{{ old('email') }}">
                        </div>
                        @if ($errors->has('email'))
                            <span class="text-danger text-left mb-15" role="alert">
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                        <div class="input-control">
                            <label for="#" class="input-control-icon"><i class="fal fa-lock-alt"></i></label>
                            <input type="password" name='password' class="input-control-input"
                                placeholder='@lang('auth.enter_password')'>
                        </div>
                        @if ($errors->has('password'))
                            <span class="text-danger text-left mb-15" role="alert">
                                {{ $errors->first('password') }}
                            </span>
                        @endif
                        <div class="input-control d-flex flex-wrap row_gap_24">
                            <label for="#" class="checkbox">
                                <input type="checkbox" class="checkbox-input" name="remember" id="rememberMe"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <span class="checkbox-title">@lang('auth.remember_me')</span>
                            </label>
                            <a href="{{ route('recoveryPassord') }}" id='forget'>@lang('auth.forget_password')?</a>
                        </div>
                        <div class="input-control">
                            <input type="submit" class='input-control-input' value="Sign In">
                        </div>
                    </form>
                </div>
            </div>

            @if (config('app.app_sync') && session('domain') != 'school')
                <div class="row justify-content-center align-items-center" style="">
                    <div class="col-lg-6 col-md-8">
                        <div class="grid__button__layout">
                            @foreach ($users as $user)
                                @if ($user)
                                    <form method="POST" class="loginForm" action="{{ route('login') }}">
                                        @csrf()
                                        <input type="hidden" name="email" value="{{ $user->email }}">
                                        <input type="hidden" name="auto_login" value="true">
                                        {{-- <button type="submit"
                                class="primary-btn fix-gr-bg  mt-10 text-center col-lg-12 text-nowrap">{{ $user[0]->roles->name }}</button> --}}
                                    </form>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            <!-- login form end -->
            @if (config('app.app_sync'))
                <div class="row justify-content-center align-items-center mt-20">
                    <div class="col-lg-12">
                        <div class="row row-gap-10">
                            @foreach ($users as $user)
                                @if ($user)
                                    <div class="col-4 col-sm-4 col-md-3">
                                        <form method="POST" class="loginForm" action="{{ route('login') }}">
                                            @csrf()
                                            <input type="hidden" name="email" value="{{ $user->email }}">
                                            <input type="hidden" name="auto_login" value="true">
                                            <input type="submit" class='input-control-input'
                                                value="{{ $user->roles->name }}">
                                        </form>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            @if (config('app.app_sync') && isset($schools) && session('domain') == 'school')
            <!-- Star School Links -->
            <div class="saas_school_top_five_link_show mt-4">
                <div class="row row-gap-10 justify-content-center">
                    <div class="col-12">
                        <h6 class="text-center title">{{ __('edulia.schools') }}</h6>
                    </div>
                    @foreach ($schools as $school)
                    <div class="col-4 col-sm-4 col-md-3">
                        <a target="_blank" href="//{{ $school->domain . '.' . config('app.short_url') }}/home" class="link_to_school">{{ Str::limit($school->school_name, 20, '...') }}</a>
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- End School Links -->
            @endif
        </div>

    </section>


    <!-- jQuery JS -->
    <script src="{{ asset('public/theme/edulia/js/jquery.min.js') }}"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Toastr JavaScript file -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Main Script JS -->
    <script src="{{ asset('public/theme/edulia/js/script.js') }}"></script>
    <script src="{{ asset('public/backEnd/') }}/js/login.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#email-address").keyup(function() {
                $("#username-hidden").val($(this).val());
            });
        });
    </script>
     <script>
        @if(Session::has('toast_message'))
            toastr.{{ Session::get('toast_message')['type'] }}('{{ Session::get('toast_message')['message'] }}');
        @endif
    </script>
</body>

</html>
