@extends('backEnd.master')

@section('mainContent')
<div class="container">
    <h3>Order Confirmation</h3>

    <div class="alert alert-success">
        Thank you, {{ $name }}. Your order was recorded. Payment method: <strong>{{ $method }}</strong>.
    </div>

    <h4>Order Summary</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart as $line)
                <tr>
                    <td>{{ $line['name'] ?? $line['title'] ?? 'Unknown' }}</td>
                    <td>{{ $line['qty'] ?? 1 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('parents.items.shop') }}" class="btn btn-primary">Back to Shop</a>
</div>
@endsection
