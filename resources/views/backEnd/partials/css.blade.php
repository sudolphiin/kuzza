<link rel="stylesheet" href="{{ asset('public/backEnd/assets/vendors/css/jquery-ui.css') }}" />
{{-- metsimenu --}}
<link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/metisMenu.css') }}" />

<link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/loade.css') }}" />
<link rel="stylesheet" href="{{ asset('public/css/app.css') }}" />
<link rel="stylesheet" href="{{asset('public/backEnd/assets/css/croppie.css')}}" />
 @if(userRtlLtl() ==1)
<link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/rtl/style.css')}}" />
<link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/rtl/infix.css')}}" />
@else
<link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/backend_static_style.css') }}" />
<link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/infix.css') }}" />
@endif

<link rel="stylesheet" href="{{ asset('public/backEnd/assets/vendors/vendors_static_style.css') }}" />
<link rel="stylesheet" href="{{asset('public/backEnd/assets/css/preloader.css')}}" />
<link rel="stylesheet" href="{{asset('public/backEnd/assets/css/solid_style.css')}}" />
<link rel="stylesheet" href="{{asset('public/backEnd/multiselect/css/jquery.multiselect.css')}}" />
<link rel="stylesheet" href="{{asset('public/backEnd/multiselect/css/custom_style.css')}}" />
<link rel="stylesheet" href="{{asset('public/backEnd/assets/css/radio_checkbox.css')}}" />

<link rel="stylesheet" href="{{asset('public/css/backend_design_v2.css')}}">
<style>
    *{
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    /* for toastr dynamic start*/
    .toast-success {
        background-color: #4BCF90!important;
    }

    .toast-message {
        color: #FFFFFF;
    }

    .toast-title {
        color: #FFFFFF;

    }

    .toast {
        color: #FFFFFF;
    }

    .toast-error {
        background-color: #FF6D68!important;
    }

    .toast-warning {
        background-color: #E09079!important;
    }
</style>
<style>

    :root {
    --background: #FAFAFA;
    --base_color: #5B2D8E;
    --sidebar_bg: #3A1A6B;
    --gradient_1: #5B2D8E;
    --gradient_2: #5B2D8E;
--gradient_3: #5B2D8E;
    --text-color: #1A0A2E;
    --scroll_color: #1A0A2E;
    --text_white: #FAFAF8;
    --bg_white: #FAFAF8;
    --text_black: #1A0A2E;
    --bg_black: #1A0A2E;
    --border_color: #F2EEF9;
    --sidebar_active: #F2EEF9;
    --sidebar_hover: #C8A8E9;
    --primary-color: #5B2D8E;
    --card-gradient-cyan: linear-gradient(to right, #06b6d4, #22d3ee);
    --card-gradient-violet: linear-gradient(to right, #8b5cf6, #a78bfa);
    --card-gradient-blue: linear-gradient(to right, #3b82f6, #60a5fa);
    --card-gradient-fuchsia: linear-gradient(to right, #d946ef, #e879f9);
    --card-gradient-cyan-hover: linear-gradient(to right, #06b6d4, #22d3ee);
    --card-gradient-violet-hover: linear-gradient(to right, #8b5cf6, #a78bfa);
    --card-gradient-blue-hover: linear-gradient(to right, #3b82f6, #60a5fa);
    --card-gradient-fuchsia-hover: linear-gradient(to right, #d946ef, #e879f9);
    
    --sidebar-section: #C8A8E9;
    --sidebar-nav-link: #FAFAF8;
    --transparent: transparent;

    --input_bg: #FAFAF8;
    --success: #4BCF90;
    --danger: #FF6D68;
    --warning: #F5C518;
    --red: #d33333;
    --black: #1A0A2E;
    --link-hover: #3A1A6B;
    --notification_title: #1A0A2E;
    --notification_time: #3b3f5c99;
    --modalLink_color: #2f2f3be6;
    --profile_text_hover: #3A1A6B;
    --table_header: #F2EEF9;
    --box_shadow: rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0.1) 0px 1px 3px 0px, rgba(0, 0, 0, 0.1) 0px 1px 2px -1px!important;
    --purple: #5B2D8E;
    --purple-deep: #3A1A6B;
    --yellow: #F5C518;
    --yellow-light: #FFE066;
    --pink-accent: #E8A0D8;
    --lilac: #C8A8E9;
    --white: #FAFAF8;
    --off-white: #F2EEF9;
    --text-dark: #1A0A2E;
    }
</style>
