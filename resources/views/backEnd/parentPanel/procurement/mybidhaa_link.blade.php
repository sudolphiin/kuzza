@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Kuzza Procurement Link</h1>
            <div class="bc-pages">
                <a href="{{url('dashboard')}}">Dashboard</a>
                <a href="{{route('parent.procurement.dashboard')}}">Procurement</a>
                <a href="#">Kuzza Procurement Link</a>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="white-box">
            <p>This section will provide a link or integrate with the Kuzza procurement platform for checkout.</p>
            {{-- Kuzza procurement integration details or link will go here --}}
            <a href="{{ url('/') }}" target="_blank" class="primary-btn fix-gr-bg">Go to Kuzza</a>
        </div>
    </div>
</div>
@endsection
