@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Procurement Dashboard</h1>
            <div class="bc-pages">
                <a href="{{url('dashboard')}}">Dashboard</a>
                <a href="#">Procurement</a>
                <a href="#">Procurement Dashboard</a>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="white-box">
            <p>Welcome to the Procurement Dashboard. Here you can manage your book lists, product orders, and Kuzza integrations.</p>
            <ul>
                <li><a href="{{route('parent.procurement.book-list')}}">View Book List</a></li>
                <li><a href="{{route('parent.procurement.product-list')}}">View Product List</a></li>
                <li><a href="{{route('parent.procurement.mybidhaa-link')}}">Kuzza Procurement Link</a></li>
            </ul>
        </div>
    </div>
</div>
@endsection
