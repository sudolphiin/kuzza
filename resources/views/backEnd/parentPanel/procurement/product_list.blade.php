@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Product List</h1>
            <div class="bc-pages">
                <a href="{{url('dashboard')}}">Dashboard</a>
                <a href="{{route('parent.procurement.dashboard')}}">Procurement</a>
                <a href="#">Product List</a>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="white-box">
            <p>This is the product list section. You can view, buy, or upload lists of products here.</p>
            {{-- Table for product list and buy/upload forms will go here --}}
        </div>
    </div>
</div>
@endsection
