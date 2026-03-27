@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Book List</h1>
            <div class="bc-pages">
                <a href="{{url('dashboard')}}">Dashboard</a>
                <a href="{{route('parent.procurement.dashboard')}}">Procurement</a>
                <a href="#">Book List</a>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="white-box">
            <p>This is the book list section. Details about available books will be displayed here.</p>
            {{-- Table for book list will go here --}}
        </div>
    </div>
</div>
@endsection
