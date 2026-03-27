@extends('backEnd.master')
@section('title')
    Items Assigned by School
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-20 up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Items Assigned by School</h1>
            <div class="bc-pages">
                <a href="{{ route('student-dashboard') }}">@lang('common.dashboard')</a>
                <a href="{{ route('student-profile') }}">@lang('student.my_profile')</a>
                <a href="#">Assigned Items</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <div class="row align-items-center mb-20">
                        <div class="col-md-8">
                            <div class="main-title mb-0">
                                <h3 class="mb-0">Items Assigned by School</h3>
                                <p class="text-muted mb-0">All items assigned to you by the school.</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="{{ route('student-profile') }}" class="primary-btn small fix-gr-bg">Back to Profile</a>
                        </div>
                    </div>
                    <form method="GET" class="mb-20">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <label class="primary_input_label">Type</label>
                                <select name="type" class="primary_select form-control">
                                    <option value="">All</option>
                                    <option value="required" @if(request('type') === 'required') selected @endif>Required</option>
                                    <option value="recommended" @if(request('type') === 'recommended') selected @endif>Recommended</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="primary_input_label">From</label>
                                <input type="date" name="from" class="primary_input_field form-control" value="{{ request('from') }}">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="primary_input_label">To</label>
                                <input type="date" name="to" class="primary_input_field form-control" value="{{ request('to') }}">
                            </div>
                            <div class="col-md-3 mb-2 d-flex align-items-end">
                                <button type="submit" class="primary-btn small fix-gr-bg mr-2">Filter</button>
                                <a href="{{ route('student-assigned-items') }}" class="primary-btn small tr-bg">Reset</a>
                            </div>
                        </div>
                    </form>

                    @if(isset($assigned_items) && $assigned_items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead style="background-color:#f8f9fa;">
                                    <tr>
                                        <th style="width: 24%;">Item</th>
                                        <th style="width: 14%;">Category</th>
                                        <th style="width: 12%;">Type</th>
                                        <th style="width: 10%;">Qty</th>
                                        <th>Description</th>
                                        <th style="width: 14%;">Assigned</th>
                                        <th style="width: 12%;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assigned_items as $assigned)
                                        @php
                                            $item = $assigned->recommendedItem;
                                            $qty = (int) ($assigned->assigned_quantity ?: 1);
                                            $type = $assigned->assignment_type === 'required' ? 'Required' : 'Recommended';
                                            $assignedAt = $assigned->batch && $assigned->batch->created_at ? $assigned->batch->created_at : $assigned->created_at;
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $item->item_name ?? 'Item' }}</strong>
                                            </td>
                                            <td>{{ ucfirst(str_replace('_',' ', $item->item_type ?? 'general')) }}</td>
                                            <td>
                                                <span class="badge {{ $type === 'Required' ? 'badge-danger' : 'badge-success' }}">{{ $type }}</span>
                                            </td>
                                            <td>{{ $qty }}</td>
                                            <td class="text-muted small">{{ \Illuminate\Support\Str::limit($item->description ?? '', 100) }}</td>
                                            <td>{{ $assignedAt ? $assignedAt->format('d M Y') : '—' }}</td>
                                            <td>
                                                {{ $currency->currency_symbol ?? 'KSh' }}
                                                {{ number_format(($item->price ?? 0) * $qty, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $assigned_items->links() }}
                        </div>
                    @else
                        <div style="text-align: center; padding: 60px 20px; background: #f8f9fa; border-radius: 8px;">
                            <i class="ti-layout-list-large" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                            <h4 style="color: #999; margin-bottom: 10px;">No Items Available</h4>
                            <p style="color: #999; font-size: 16px;">No items have been assigned to you yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
