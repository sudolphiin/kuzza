@if (in_array(818, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1)
    <div class="col-lg-4 col-xl-3">

        @if (isset($editdata))
            <form class="form-horizontal" action="{{ route('jitsi.virtual-class.update', $editdata->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
            @else
                <form class="form-horizontal" action="{{ route('jitsi.virtual-class.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    
                    <div class="main-title">
                        <h3 class="mb-15">
                            @if (isset($editdata))
                                @lang('common.edit_virtual_class')
                            @else
                                @lang('common.add_virtual_class')
                            @endif
            
                        </h3>
                    </div>
                    @if (moduleStatusCheck('University'))
                    @includeIf('university::common.session_faculty_depart_academic_semester_level', [
                        'required' => ['USN', 'UF', 'UD', 'UA', 'US', 'USL', 'USEC'],
                        'hide' => ['USUB'],
                        'row' => 1,
                        'div' => 'col-lg-12',
                        'mt' => 'mt-0',
                        'editData' =>@$editdata,                    
                    ])
                @else
               
                    <div class="row ">
                        <div class="col-lg-12">
                            <div class="primary_input">

                                <label class="primary_input_label" for="">
                                    {{ __('common.class') }}
                                    <span class="text-danger"> *</span>
                                </label>
                                <select
                                    class="primary_select   form-control{{ $errors->has('class_id') ? ' is-invalid' : '' }}"
                                    name="class_id" id="select_class">
                                    <option data-display="  @lang('common.class')*" value="">@lang('common.class') *
                                    </option>


                                    @foreach ($classes as $class)
                                        @if (isset($editdata))
                                            <option value="{{ $class->id }}"
                                                {{ old('class_id', $editdata->class_id) == $class->id ? 'selected' : '' }}>
                                                {{ $class->class_name }}</option>
                                        @else
                                            <option value="{{ $class->id }}"
                                                {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->class_name }}</option>
                                        @endif
                                    @endforeach

                                </select>
                                @if ($errors->has('class_id'))
                                    <span class="text-danger">
                                        {{ $errors->first('class_id') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="row  mt-15">
                        <div class="col-lg-12" id="select_section_div">
                            <label class="primary_input_label" for="">
                                {{ __('common.section') }}
                                <span class="text-danger"> </span>
                            </label>
                            <select
                                class="primary_select form-control {{ @$errors->has('section') ? ' is-invalid' : '' }}"
                                id="select_section" name="section">
                                <option data-display="@lang('common.select_section') " value="">@lang('common.select_section') </option>
                                @if (isset($editdata))
                                    @foreach ($class_sections as $section)
                                        <option value="{{ @$section->id }}"
                                            {{ old('section', $section->id) == $editdata->section_id ? 'selected' : '' }}>
                                            {{ @$section->section_name }} </option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="pull-right loader" id="select_section_loader"
                                style="margin-top: -30px;padding-right: 21px;">
                                <img src="{{ asset('Modules/Jitsi/Resources/assets/images/pre-loader.gif') }}"
                                    alt="" style="width: 28px;height:28px;">
                            </div>
                            @if ($errors->has('section'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ @$errors->first('section') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- <div class="row mt-15" >
                        @includeIf('backEnd.shift.shift_include_new', [
                            'grid_class' => 'col-lg-12',
                            'id' => 'select_shift',
                            'mt' => null,
                            'label' => __('common.shift'),
                            'required' => false,
                            'editData' => '',
                        ])
                    </div> --}}
                    @endif

                    @if ($user->role_id == 1 || $user->role_id == 5)
                        <div class="row mt-15">
                            <div class="col-lg-12" id="selectTeacherDiv">
                                <label for="teacher_ids" class="mb-2">@lang('common.teacher') <span class="text-danger">
                                        *</span></label>
                                @foreach ($teachers as $teacher)
                                    <div class="">
                                        @if (isset($editdata))
                                            <input type="radio" id="section{{ @$teacher->user_id }}"
                                                class="common-checkbox form-control{{ @$errors->has('teacher_ids') ? ' is-invalid' : '' }}"
                                                name="teacher_ids[]" value="{{ @$teacher->user_id }}"
                                                {{ $editdata->teachers->contains($teacher->user_id) ? 'checked' : '' }}>
                                            <label
                                                for="section{{ @$teacher->user_id }}">{{ @$teacher->full_name }}</label>
                                        @else
                                            <input type="radio" id="section{{ @$teacher->user_id }}"
                                                class="common-checkbox form-control{{ @$errors->has('teacher_ids') ? ' is-invalid' : '' }}"
                                                name="teacher_ids[]" value="{{ @$teacher->user_id }}">
                                            <label for="section{{ @$teacher->user_id }}">
                                                {{ @$teacher->full_name }}</label>
                                        @endif
                                    </div>
                                @endforeach
                                @if ($errors->has('teacher_ids'))
                                    <span class="text-danger" style="display:block">
                                        {{ $errors->first('teacher_ids') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                    @if ($user->role_id == 4)
                        <div class="row mt-15">
                            <div class="col-lg-12" id="selectTeacherDiv">
                                <label for="teacher_ids" class="mb-2">@lang('common.teacher') </label>
                                @foreach ($teachers as $teacher)
                                    <div class="">
                                        @if (isset($editdata))
                                            <input type="checkbox" id="section{{ @$teacher->user_id }}"
                                                class="common-checkbox form-control{{ @$errors->has('teacher_ids') ? ' is-invalid' : '' }}"
                                                name="teacher_ids[]" value="{{ @$teacher->user_id }}"
                                                {{ $editdata->teachers->contains($teacher->user_id) ? 'checked' : '' }}>
                                            <label
                                                for="section{{ @$teacher->user_id }}">{{ @$teacher->full_name }}</label>
                                        @else
                                            <input type="checkbox" id="section{{ @$teacher->user_id }}"
                                                class="common-checkbox form-control{{ @$errors->has('teacher_ids') ? ' is-invalid' : '' }}"
                                                name="teacher_ids[]" value="{{ @$teacher->user_id }}">
                                            <label for="section{{ @$teacher->user_id }}">
                                                {{ @$teacher->full_name }}</label>
                                        @endif
                                    </div>
                                @endforeach
                                <input type="hidden" name="teacher_ids[]" value="{{ $user->id }}">
                                @if ($errors->has('teacher_ids'))
                                    <span class="text-danger" style="display:block">
                                        {{ $errors->first('teacher_ids') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="row mt-15">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('common.topic') <span
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
                                <label class="primary_input_label" for="">@lang('common.date_of_meeting')<span
                                        class="text-danger"> *</span></label>
                                <div class="primary_datepicker_input">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="">
                                                <input
                                                    class="primary_input_field  primary_input_field date form-control form-control"
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
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('common.meeting_time') <span
                                        class="text-danger"> *</span></label>
                                <div class="primary_datepicker_input">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="">
                                                <input class="primary_input_field primary_input_field time"
                                                    type="text" name="time" id="meetTime"
                                                    value="{{ isset($editdata) ? old('time', $editdata->time) : old('time') }}">

                                                @if ($errors->has('time'))
                                                    <span class="text-danger d-block">
                                                        {{ $errors->first('time') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <button class="" type="button">
                                            <label class="m-0 p-0" for="meetTime">
                                                <i class="ti-alarm-clock " id="admission-date-icon"></i>
                                            </label>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-15">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('common.meeting_duration')<span
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
                                <label class="primary_input_label" for="">@lang('common.meeting_start_before')</label>
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

                    <div class="row  mt-15">
                        <div class="col-lg-12 mt-15">
                            <div class="primary_input">
                                <div class="primary_file_uploader">

                                    <input
                                        class="primary_input_field form-control {{ $errors->has('attached_file') ? ' is-invalid' : '' }}"
                                        readonly="true" type="text"
                                        placeholder="{{ isset($editdata->attached_file) && @$editdata->attached_file != '' ? getFilePath3(@$editdata->attached_file) : trans('common.attach_file') }}"
                                        id="placeholderInput">

                                    <button class="" type="button">
                                        <label class="primary-btn small fix-gr-bg"
                                            for="browseFile">{{ __('common.browse') }}</label>
                                        <input type="file" class="d-none" name="attached_file" id="browseFile">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-30">
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
@endif
@include('backEnd.partials.date_picker_css_js')