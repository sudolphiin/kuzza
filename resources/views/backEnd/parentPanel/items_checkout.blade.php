@extends('backEnd.master')

@section('mainContent')
<div class="container">
    <h3>Checkout — Example</h3>

    @if(count($cart) === 0)
        <div class="alert alert-warning">Your cart is empty. Please add items first.</div>
        <a href="{{ route('parents.items.shop') }}" class="btn btn-secondary">Back to Shop</a>
    @else
    <form method="POST" action="{{ route('parents.items.processCheckout') }}">
        @csrf
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $line)
                <tr>
                    <td>{{ $line['name'] }}</td>
                    <td>{{ number_format($line['price'], 2) }}</td>
                    <td>{{ $line['qty'] }}</td>
                    <td>{{ number_format($line['subtotal'], 2) }}</td>
                </tr>
                <input type="hidden" name="cart[]" value="{{ htmlspecialchars(json_encode($line), ENT_QUOTES, 'UTF-8') }}">
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total</th>
                    <th>{{ number_format($total, 2) }}</th>
                </tr>
            </tfoot>
        </table>

        <div class="form-group">
            <label for="payer_name">Your name</label>
            <input id="payer_name" name="payer_name" class="form-control" value="{{ auth()->user()->full_name ?? '' }}" />
        </div>

        <div class="form-group">
            <label for="payment_method">Payment method</label>
            <select id="payment_method" name="payment_method" class="form-control">
                <option>Cash on Delivery</option>
                <option>Card</option>
                <option>Mobile Money</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success btn-lg">Place Order</button>
            <a href="{{ route('parents.items.shop') }}" class="btn btn-secondary btn-lg">Modify Cart</a>
        </div>
    </form>
    @endif
</div>
@endsection
