@extends('backEnd.master')

@section('mainContent')
<div class="container">
    <h3>Suggested Items — Demo Shop</h3>
    <form method="POST" action="{{ route('parents.items.checkout') }}">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ number_format($item->price ?? 0, 2) }}</td>
                    <td>
                        <input type="number" name="items[{{ $item->id }}]" value="0" min="0" class="form-control" style="width:100px;" />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="form-group">
            <button class="btn btn-primary">Proceed to Checkout</button>
        </div>
    </form>
</div>
@endsection
