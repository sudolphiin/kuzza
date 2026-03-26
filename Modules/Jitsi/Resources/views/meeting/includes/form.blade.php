@if (in_array(823, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1)
    <div class="col-lg-4 col-xl-3">

        <div class="white-box">
            <div class="main-title">
                <h3 class="mb-0">
                    @if (isset($editdata))
                        @lang('common.edit_meeting')
                    @else
                        @lang('common.add_meeting')
                    @endif
    
                </h3>
            </div>
    
            @if (isset($editdata))
                <form class="form-horizontal" action="{{ route('jitsi.meetings.update', $editdata->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                @else
                    <form class="form-horizontal" action="{{ route('jitsi.meetings.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <div>
    
    
                        <div class="row mt-15">
                            <div class="col-lg-12 ">
                                <div class="primary_input">
                                    <label for="checkbox" class="mb-2">@lang('common.member_type') <span class="text-danger"> *</span></label>
                                    <select
                                        class="primary_select user_type form-control{{ $errors->has('member_type') ? ' is-invalid' : '' }}"
                                        name="member_type">
                                        <option data-display=" @lang('jitsi::jitsi.member_type') *" value="">@lang('jitsi::jitsi.member_type') *</option>
                                        @foreach ($roles as $value)
                                            @if (isset($editdata))
                                                <option value="{{ $value->id }}"
                                                    {{ $value->id == $user_type ? 'selected' : '' }}>{{ $value->name }}
                                                </option>
                                            @else
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('member_type'))
                                    <span class="text-danger">
                                        {{ $errors->first('member_type') }}
                                    </span>
                                @endif
                            </div>
                        </div>
    
                        <div class="row mt-15">
                            <div class="col-lg-12" id="selectTeacherDiv">
                                <label for="checkbox" class="mb-2">@lang('common.member') <span class="text-danger">
                                        *</span></label>
                                <select multiple id="selectMultiUsers" class="multypol_check_select active position-relative"
                                     name="participate_ids[]"
                                    style="width:300px">
                                    @if (isset($editdata))
                                        @foreach ($userList as $value)
                                            <option value="{{ $value->id }}" selected>{{ $value->full_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('participate_ids'))
                                    <span class="text-danger">
                                        {{ $errors->first('participate_ids') }}
                                    </span>
                                @endif
                            </div>
                        </div>
    
    
                        <div class="row mt-15">
                            <div class="col-lg-12">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('jitsi::jitsi.topic') <span
                                        class="text-danger"> *</span></label>
                                    <input
                                        class="primary_input_field form-control{{ $errors->has('topic') ? ' is-invalid' : '' }}"
                                        type="text" name="topic" autocomplete="off"
                                        value="{{ isset($editdata) ? old('topic', $editdata->topic) : old('topic') }}">
                                   
    
                                    @if ($errors->has('topic'))
                                        <span class="text-danger">
                                            {{ $errors->first('topic') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
    
                        <div class="row mt-15">
                            <div class="col-lg-12">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('common.description')</label>
                                    <textarea class="primary_input_field form-control" cols="0" rows="4" name="description" id="address">{{ isset($editdata) ? old('description', $editdata->description) : old('description') }}</textarea>
                                    
    
                                    @if ($errors->has('description'))
                                        <span class="text-danger">
                                            {{ $errors->first('description') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
    
    
    
    
                        <div class="row mt-15">
                            <div class="col-lg-12">
                                
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('jitsi::jitsi.date_of_meeting')<span
                                        class="text-danger"> *</span></label>
                                    <div class="primary_datepicker_input">
                                        <div class="no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="">
                                                    <input class="primary_input_field  primary_input_field date form-control form-control"
                                    id="startDate" type="text" name="date" readonly="true"
                                    value="{{ isset($editdata) ? old('date', Carbon\Carbon::parse($editdata->date_of_meeting)->format('m/d/Y')) : old('date', Carbon\Carbon::now()->format('m/d/Y')) }}"
                                    required>
                                                </div>
                                            </div>
                                            <button class="btn-date" data-id="#startDate" type="button">
                                                <label class="m-0 p-0" for="startDate">
                                                    <i class="ti-calendar" id="start-date-icon"></i>
                                                </label>
                                            </button>
                                        </div>
                                    </div>
                                    <span class="text-danger">{{ $errors->first('date') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-15">
                            <div class="col-lg-12">
                                <label class="primary_input_label" for="">@lang('jitsi::jitsi.meeting_time') <span
                                        class="text-danger"> *</span></label>
                                <input
                                    class="primary_input_field primary_input_field time form-control{{ @$errors->has('time') ? ' is-invalid' : '' }}"
                                    type="text" name="time"
                                    value="{{ isset($editdata) ? old('time', $editdata->time) : old('time') }}">
    
                                @if ($errors->has('time'))
                                    <span class="text-danger">
                                        {{ @$errors->first('time') }}
                                    </span>
                                @endif
                            </div>
                        </div>
    
                        <div class="row mt-15">
                            <div class="col-lg-12">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('jitsi::jitsi.meeting_duration')<span
                                        class="text-danger"> *</span></label>
                                    <input oninput="numberCheckWithDot(this)"
                                        class="primary_input_field form-control{{ $errors->has('duration') ? ' is-invalid' : '' }}"
                                        type="text" name="duration" autocomplete="off"
                                        value="{{ isset($editdata) ? old('duration', $editdata->duration) : old('duration') }}">
                                    
    
                                    @if ($errors->has('duration'))
                                        <span class="text-danger">
                                            {{ $errors->first('duration') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
    
    
                        <div class="row mt-15">
                            <div class="col-lg-12">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('jitsi::jitsi.meeting_start_before') </label>
                                    <input oninput="numberCheckWithDot(this)"
                                        class="primary_input_field form-control{{ $errors->has('time_start_before') ? ' is-invalid' : '' }}"
                                        type="text" name="time_start_before" autocomplete="off"
                                        value="{{ isset($editdata) ? old('time_start_before', $editdata->time_start_before) : 10 }}">
                                  
    
                                    @if ($errors->has('time_start_before'))
                                        <span class="text-danger">
                                            {{ $errors->first('time_start_before') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
    
                        <div class="row mt-15">                        
                            <div class="col-lg-12 mt-15">
                                <div class="primary_input">
                                    <div class="primary_file_uploader">
                                       
                                            <input
                                        class="primary_input_field form-control {{ $errors->has('attached_file') ? ' is-invalid' : '' }}"
                                        readonly="true" type="text"
                                        placeholder="{{ isset($editdata->file) && @$editdata->file != '' ? getFilePath3(@$editdata->file) : trans('common.attach_file') }}"
                                        id="placeholderInput">
                                        <button class="" type="button">
                                            <label class="primary-btn small fix-gr-bg" for="browseFile">{{ __('common.browse') }}</label>
                                            <input type="file" class="d-none" name="attached_file" id="browseFile">
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
    
    
    
    
                        <div class="row mt-40">
                            <div class="col-lg-12 text-center">
    
                                <button class="primary-btn fix-gr-bg">
                                    <span class="ti-check"></span>
                                    @if (isset($editdata))
                                        @lang('common.update')
                                    @else
                                        @lang('common.save')
                                    @endif
    
                                </button>
    
                            </div>
                        </div>
    
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>

@endif
@include('backEnd.partials.multi_select_js')
@include('backEnd.partials.date_picker_css_js')