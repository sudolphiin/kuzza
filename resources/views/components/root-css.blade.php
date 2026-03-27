
<style>
    /* for toastr dynamic start*/
    .toast-success {
        background-color: !important;
    }

    .toast-message {
        color: ;
    }

    .toast-title {
        color: ;

    }

    .toast {
        color: ;
    }

    .toast-error {
        background-color: !important;
    }

    .toast-warning {
        background-color: !important;
    }
</style>
<style>
:root {
    @php
        $cyan_one = '#06b6d4';
        $cyan_two = '#22d3ee';
        $cyan_one_hover = '#06b6d4';
        $cyan_two_hover = '#22d3ee';
        $violet_one = '#8b5cf6';
        $violet_one_hover = '#8b5cf6';
        $violet_two = '#a78bfa';
        $violet_two_hover = '#a78bfa';
        $blue_one = '#3b82f6';
        $blue_one_hover = '#3b82f6';
        $blue_two = '#60a5fa';
        $blue_two_hover = '#60a5fa';
        $fuchsia_one = '#d946ef';
        $fuchsia_one_hover = '#d946ef';
        $fuchsia_two = '#e879f9';
        $fuchsia_two_hover = '#e879f9';
    @endphp

    --base_font : {{ in_array(session()->get('locale', Config::get('app.locale')), ['ar']) ? 'Cairo,' : ''}}Poppins, sans-serif;
    --box_shadow : {{ $color_theme->box_shadow ? 'var(--box_shadow)' : 'none' }};
    
    @foreach($color_theme->colors as $color)
        --{{ $color->name}}: {{ $color->pivot->value }};
        
        @if(in_array($color->name, ['success', 'danger']))
            --{{ $color->name}}_with_opacity: {{ $color->pivot->value }}23;
        @endif

        @if ($color->name           == 'card-gradient-cyan_one')
            @php $cyan_one          = $color->pivot->value; @endphp
        @elseif ($color->name       == 'card-gradient-cyan_two')
            @php $cyan_two          = $color->pivot->value; @endphp
        @elseif ($color->name       == 'card-gradient-cyan_one_hover')
            @php $cyan_one_hover    = $color->pivot->value; @endphp
        @elseif ($color->name       == 'card-gradient-cyan_two_hover')
            @php $cyan_two_hover    = $color->pivot->value; @endphp
        @elseif ($color->name       == 'card-gradient-violet_one')
            @php $violet_one        = $color->pivot->value; @endphp
        @elseif ($color->name       == 'card-gradient-violet_one_hover')
            @php $violet_one_hover  = $color->pivot->value; @endphp
        @elseif ($color->name       == 'card-gradient-violet_two')
            @php $violet_two        = $color->pivot->value; @endphp
        @elseif ($color->name       == 'card-gradient-violet_two_hover')
            @php $violet_two_hover  = $color->pivot->value; @endphp
        @elseif ($color->name       == 'card-gradient-blue_one')
            @php $blue_one          = $color->pivot->value; @endphp
        @elseif ($color->name       == 'card-gradient-blue_one_hover')
            @php $blue_one_hover    = $color->pivot->value; @endphp
        @elseif ($color->name       == 'card-gradient-blue_two')
            @php $blue_two          = $color->pivot->value; @endphp
        @elseif ($color->name       == 'card-gradient-blue_two_hover')
            @php $blue_two_hover    = $color->pivot->value; @endphp
        @elseif ($color->name       == 'card-gradient-fuchsia_one')
            @php $fuchsia_one       = $color->pivot->value; @endphp
        @elseif ($color->name       == 'card-gradient-fuchsia_one_hover')
            @php $fuchsia_one_hover = $color->pivot->value; @endphp
        @elseif ($color->name       == 'card-gradient-fuchsia_two')
            @php $fuchsia_two       = $color->pivot->value; @endphp
        @elseif ($color->name       == 'card-gradient-fuchsia_two_hover')
            @php $fuchsia_two_hover = $color->pivot->value; @endphp
        @endif
    @endforeach

    --card-gradient-cyan: linear-gradient(to right, {{ $cyan_one }}, {{ $cyan_two }});
    --card-gradient-cyan-hover: linear-gradient(to right, {{ $cyan_one_hover }}, {{ $cyan_two_hover }});
    --card-gradient-violet: linear-gradient(to right, {{ $violet_one }}, {{ $violet_two }});
    --card-gradient-violet-hover: linear-gradient(to right, {{ $violet_one_hover }}, {{ $violet_two_hover }});
    --card-gradient-blue: linear-gradient(to right, {{ $blue_one }}, {{ $blue_two }});
    --card-gradient-blue-hover: linear-gradient(to right, {{ $blue_one_hover }}, {{ $blue_two_hover }});
    --card-gradient-fuchsia: linear-gradient(to right, {{ $fuchsia_one }}, {{ $fuchsia_two }});
    --card-gradient-fuchsia-hover: linear-gradient(to right, {{ $fuchsia_one_hover }}, {{ $fuchsia_two_hover }});

    /* Custom palette override */
    --purple: #5B2D8E;
    --purple-deep: #3A1A6B;
    --yellow: #F5C518;
    --yellow-light: #FFE066;
    --pink-accent: #E8A0D8;
    --lilac: #C8A8E9;
    --white: #FAFAF8;
    --off-white: #F2EEF9;
    --text-dark: #1A0A2E;

    --base_color: #5B2D8E;
    --primary-color: #5B2D8E;
    --background: #FAFAFA;
    --text-color: #1A0A2E;
    --text_white: #FAFAF8;
    --bg_white: #FAFAF8;
    --border_color: #F2EEF9;
    --link-hover: #3A1A6B;
    --table_header: #F2EEF9;
    --sidebar_bg: #3A1A6B;
    --sidebar_active: #F2EEF9;
    --sidebar_hover: #C8A8E9;
    --sidebar-section: #C8A8E9;
    --sidebar-nav-link: #FAFAF8;
}
        
</style>
<style>
    /* Button color override */
    :root {
        --button_bg: #F5C518;
        --button_bg_hover: #FFE066;
        --button_text: #1A0A2E;
    }

    .primary-btn,
    .primary-btn.fix-gr-bg,
    .primary-btn.small,
    .primary-btn.medium,
    .primary-btn.large,
    .primary-btn.semi-large,
    .btn_1,
    .btn.btn-primary,
    button.primary-btn,
    a.primary-btn {
        background: var(--button_bg) !important;
        border-color: var(--button_bg) !important;
        color: var(--button_text) !important;
        background-image: none !important;
    }

    .primary-btn:hover,
    .primary-btn.fix-gr-bg:hover,
    .primary-btn.small:hover,
    .primary-btn.medium:hover,
    .primary-btn.large:hover,
    .primary-btn.semi-large:hover,
    .btn_1:hover,
    .btn.btn-primary:hover,
    button.primary-btn:hover,
    a.primary-btn:hover {
        background: var(--button_bg_hover) !important;
        border-color: var(--button_bg_hover) !important;
        color: var(--button_text) !important;
        background-image: none !important;
    }
</style>
