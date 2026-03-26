@extends('backEnd.master')
@section('title')
    Recommended Items Cart
@endsection

@section('mainContent')
<section class="student-details">
    <div class="container-fluid p-0">
        <div class="white-box">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-15">Recommended Items Cart</h3>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 text-right">
                    <a href="{{ route('parent-recommended-items') }}" class="secondary-btn fix-gr-bg">
                        <span class="ti-arrow-left mr-2"></span>
                        Continue Shopping
                    </a>
                </div>
            </div>

            @if($cart_items && count($cart_items) > 0)
                <form method="POST" action="{{ route('parent-recommended-items-cart.checkout') }}">
                    @csrf
                    <div class="table-responsive mt-20">
                    <table class="table table-hover">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th>
                                    <input type="checkbox" id="select-all-items">
                                </th>
                                <th>Item Name</th>
                                <th>Type</th>
                                <th>Student</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart_items as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="item_ids[]" value="{{ $item->id }}" class="item-checkbox">
                                    </td>
                                    <td>
                                        <strong>{{ $item->recommendedItem->item_name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: #e3f2fd; color: #1976d2;">
                                            {{ ucfirst(str_replace('_', ' ', $item->recommendedItem->item_type)) }}
                                        </span>
                                    </td>
                                    <td>{{ $item->student->full_name }}</td>
                                    <td>
                                        <strong>{{ (int) ($item->assigned_quantity ?: 1) }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            $lineQty = (int) ($item->assigned_quantity ?: 1);
                                            $linePrice = (float) optional($item->recommendedItem)->price;
                                            $lineTotal = $linePrice * $lineQty;
                                        @endphp
                                        <strong>
                                            @if(isset($currency))
                                                {{ $currency->currency_symbol }}{{ number_format($lineTotal, 2) }}
                                            @else
                                                {{ number_format($lineTotal, 2) }}
                                            @endif
                                        </strong>
                                    </td>
                                    <td>
                                        @if($item->batch && $item->batch->deadline)
                                            {{ $item->batch->deadline->format('d M Y') }}
                                        @else
                                            <span class="text-muted">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-warning" style="background-color: #fff3cd; color: #856404;">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('parent-recommended-items-cart.already-bought', $item->id) }}">
                                            @csrf
                                            <select name="reason" class="form-control form-control-sm mb-1">
                                                <option value="Already purchased">Already purchased</option>
                                                <option value="Too expensive">Too expensive</option>
                                                <option value="Looking for other options">Looking for other options</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-outline-danger mb-1">
                                                <i class="ti-close"></i> Remove from list
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Cart Summary -->
                <div class="row mt-30">
                    <div class="col-lg-6 offset-lg-6">
                        <div class="white-box" style="border: 2px solid #e9ecef; border-radius: 8px;">
                            <h5 style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">Cart Summary</h5>
                            
                            <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                                <span>Total Items:</span>
                                <strong>{{ count($cart_items) }}</strong>
                            </div>

                            <div style="display: flex; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                                <span>Subtotal:</span>
                                <strong>
                                    @if(isset($currency))
                                        {{ $currency->currency_symbol }}{{ number_format($total_amount, 2) }}
                                    @else
                                        {{ number_format($total_amount, 2) }}
                                    @endif
                                </strong>
                            </div>

                            <div style="display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 18px;">
                                <span>Total:</span>
                                <strong style="color: var(--base_color, #20b2aa);">
                                    @if(isset($currency))
                                        {{ $currency->currency_symbol }}{{ number_format($total_amount, 2) }}
                                    @else
                                        {{ number_format($total_amount, 2) }}
                                    @endif
                                </strong>
                            </div>

                            <button type="submit" class="btn btn-lg primary-btn fix-gr-bg btn-block" style="margin-top: 20px;">
                                <i class="ti-shopping-cart mr-2"></i> Confirm selected items
                            </button>

                            <p style="text-align: center; color: #999; font-size: 12px; margin-top: 15px;">
                                Once you proceed with payment, the items will be dispatched to your child.
                            </p>
                        </div>
                    </div>
                </div>
                </form>

                <!-- Pagination -->
                @if($cart_items->hasPages())
                    <div class="row mt-20">
                        <div class="col-lg-12">
                            <div style="display: flex; justify-content: center;">
                                {{ $cart_items->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="row">
                    <div class="col-lg-12">
                        <div style="text-align: center; padding: 60px 20px; background: #f8f9fa; border-radius: 8px;">
                            <i class="ti-shopping-cart" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                            <h4 style="color: #999; margin-bottom: 10px;">Your Cart is Empty</h4>
                            <p style="color: #999; font-size: 16px; margin-bottom: 20px;">No items added to cart yet.</p>
                            <a href="{{ route('parent-recommended-items') }}" class="primary-btn fix-gr-bg">
                                Start Shopping
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

@push('script')
<script>
    (function () {
        $('#select-all-items').on('change', function () {
            $('.item-checkbox').prop('checked', $(this).is(':checked'));
        });
    })();
</script>
@endpush
