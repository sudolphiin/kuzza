
@if(userPermission('jitsi') && menuStatus(816))
    <li data-position="{{menuPosition(816)}}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
            <div class="nav_icon_small">
                <span class="flaticon-reading"></span>
            </div>
            <div class="nav_title">
               <span> @lang('jitsi::jitsi.jitsi')</span>
                @if (config('app.app_sync'))
                    <span class="demo_addons">Addon</span>
                @endif
            </div>
        </a>
        <ul class="list-unstyled">
            @if(userPermission('jitsi.virtual-class')  && menuStatus(817))
                <li data-position="{{menuPosition(817)}}">
                    <a href="{{ route('jitsi.virtual-class')}}">@lang('jitsi::jitsi.virtual_class')</a>
                </li>
            @endif
            @if(userPermission('jitsi.meetings') && menuStatus(822))
                    <li data-position="{{menuPosition(822)}}">
                    <a href="{{ route('jitsi.meetings') }}">@lang('jitsi::jitsi.virtual_meeting')</a>
                </li>
            @endif
            @if(userPermission('jitsi.virtual.class.reports.show') && menuStatus(827))
                    <li data-position="{{menuPosition(827)}}">
                    <a href="{{route('jitsi.virtual.class.reports.show')}}">@lang('jitsi::jitsi.class_reports')</a>
                </li>
            @endif

            @if(userPermission('jitsi.meeting.reports.show') && menuStatus(829))
                <li data-position="{{menuPosition(829)}}">
                    <a href="{{route('jitsi.meeting.reports.show')}}">@lang('jitsi::jitsi.meeting_reports')</a>
                </li>
            @endif
            @if(userPermission('jitsi.settings') && menuStatus(831))
                    <li data-position="{{menuPosition(831)}}">
                    <a href="{{ route('jitsi.settings') }}">@lang('jitsi::jitsi.settings')</a>
                </li>
            @endif
        </ul>
    </li>
    <!-- jitsi Menu  -->
@endif
