@extends('backEnd.master')
@section('title') 
    @lang('jitsi::jitsi.settings')
@endsection
@push('css')
<style type="text/css">
.primary_input_field~label  {
    top: -14px;

}
</style>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-20 up_breadcrumb">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('jitsi::jitsi.settings')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">{{__('jitsi::jitsi.dashboard')}}</a>
                    <a href="#">@lang('jitsi::jitsi.jitsi')</a>
                    <a href="#">@lang('jitsi::jitsi.settings')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <form action="{{ route('jitsi.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="col-lg-12">
                                    <div class="row  mt-20">
                                        <div class="col-lg-12">
                                            <div class="primary_input ">
                                                <label> @lang('jitsi::jitsi.jitsi_server_url') <span class="text-danger"> *</span></label>
                                                <input class="primary_input_field form-control{{ $errors->has('jitsi_server') ? ' is-invalid' : '' }}"  type="text" name="jitsi_server"  value="@if(!empty($setting)){{ old('server_base_url',$setting->jitsi_server) }}@endif" placeholder="https://meet.jit.si/">

                                               
                                                
                                                @if ($errors->has('server_base_url'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                                {{ $errors->first('jitsi_server') }}
                                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                               
                                    <div class="row mt-20">
                                        <div class="col-lg-12 text-center">
                                            <button type="submit" class="primary-btn fix-gr-bg"
                                                    id="_submit_btn_admission">
                                                <span class="ti-check"></span>
                                              
                                                @lang('common.update')
                                            </button>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
