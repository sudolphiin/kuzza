@extends('backEnd.master')
@section('title')
    Assign Items to Student
@endsection
@push('style')
<style>
    .assign-shell {
        background: #f5f7fb;
        padding: 24px;
        border-radius: 14px;
        border: 1px solid #e4e7f2;
    }

    .assign-shell .section-title {
        font-weight: 600;
        font-size: 20px;
        color: #1f2937;
    }

    .assign-shell .section-subtitle {
        font-size: 13px;
        color: #6b7280;
    }

    .assign-shell .primary_input_label {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #6b7280;
        margin-bottom: 6px;
    }

    .assign-shell .primary_input_field,
    .assign-shell .primary_select.form-control,
    .assign-shell .input-group>.form-control {
        border-radius: 10px;
        border-color: #d1d5db;
        font-size: 14px;
        box-shadow: inset 0 0 0 1px rgba(209, 213, 219, 0.5);
        transition: border-color .15s ease, box-shadow .15s ease, background-color .15s ease;
        background-color: #ffffff;
    }

    .assign-shell .primary_input_field:focus,
    .assign-shell .primary_select.form-control:focus,
    .assign-shell .input-group>.form-control:focus {
        border-color: #4f46e5;
        box-shadow:
            0 0 0 1px rgba(79, 70, 229, 0.4),
            0 4px 12px rgba(15, 23, 42, 0.18);
        background-color: #ffffff;
    }

    .assign-shell .input-group .btn {
        border-radius: 0 10px 10px 0;
        padding-inline: 18px;
        font-weight: 500;
    }

    /* Connected radio pill chain */
    .scope-toggle-chain {
        display: inline-flex;
        align-items: stretch;
        border: 1px solid #d1d5db;
        border-radius: 999px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 3px 10px rgba(15, 23, 42, 0.05);
    }

    .scope-toggle-chain .toggle-option {
        position: relative;
        margin: 0;
    }

    .scope-toggle-chain .toggle-option:not(:last-child) {
        border-right: 1px solid #e5e7eb;
    }

    .scope-toggle-chain .toggle-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .scope-toggle-chain .toggle-option label {
        display: block;
        padding: 8px 14px;
        margin: 0;
        font-size: 13px;
        color: #4b5563;
        cursor: pointer;
        user-select: none;
        transition: background-color .15s ease, color .15s ease, box-shadow .15s ease;
    }

    .scope-toggle-chain .toggle-option input:checked + label {
        background: linear-gradient(135deg, #2563eb, #22c55e);
        color: #fff;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.15);
    }

    .scope-toggle-chain .toggle-option label:hover {
        background-color: #f3f4f6;
    }

    /* Selections as right-side card */
    .selections-card {
        background: linear-gradient(165deg, #0b1020 0%, #111827 55%, #0f766e 120%);
        border-radius: 12px;
        border: 1px solid rgba(15, 23, 42, 0.15);
        padding: 10px 12px;
        color: #e5e7eb;
        box-shadow: 0 10px 24px rgba(2, 6, 23, 0.35);
        min-height: 86px;
    }

    .selections-card .meta {
        font-size: 10px;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: rgba(226, 232, 240, 0.7);
    }

    .selections-card .count-line {
        font-size: 13px;
        color: #e2e8f0;
    }

    .selections-card .total-line strong {
        color: #fbbf24;
    }

    #selections-detail {
        background: #0b1220;
        border: 1px solid rgba(148, 163, 184, 0.25);
        color: #e2e8f0;
    }

    #selections-detail .text-muted,
    #selections-detail .small {
        color: #94a3b8 !important;
    }

    #selections-list li {
        border-color: rgba(148, 163, 184, 0.25) !important;
    }

    .cart-pulse {
        animation: cartPulse .28s ease-out;
    }

    @keyframes cartPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }

    .batch-product-photo {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
    }
</style>
@endpush
@section('mainContent')
    @php
        $activeTab = request()->query('tab') === 'assigned' ? 'assigned' : 'new';
        $breadCrumbs = [
            'h1' => 'Assign Items to Student',
            'bcPages' => ['<a href="#">Student Information</a>'],
        ];
    @endphp
    <x-bread-crumb-component :breadCrumbs="$breadCrumbs" />
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box assign-shell">
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'new' ? 'active' : '' }}" data-toggle="tab" href="#tab-assign-new" role="tab">New items</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'assigned' ? 'active' : '' }}" data-toggle="tab" href="#tab-assign-history" role="tab">Assigned items</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                        <div class="tab-pane fade {{ $activeTab === 'new' ? 'show active' : '' }}" id="tab-assign-new" role="tabpanel">
                        <div class="main-title mb-3">
                            <h3 class="mb-1 section-title">Search products and assign to a student</h3>
                            <p class="section-subtitle mb-0">Items you pick here will quietly appear on the student and parent dashboards for easy checkout.</p>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-6">
                                <label class="primary_input_label d-block">Recipients</label>
                                <div class="scope-toggle-chain">
                                    <div class="toggle-option">
                                        <input class="scope-radio" type="radio" name="assign_scope" id="scope-all" value="all" checked>
                                        <label for="scope-all">Assign to all students</label>
                                    </div>
                                    <div class="toggle-option">
                                        <input class="scope-radio" type="radio" name="assign_scope" id="scope-class" value="class">
                                        <label for="scope-class">Assign to class / stream</label>
                                    </div>
                                    <div class="toggle-option">
                                        <input class="scope-radio" type="radio" name="assign_scope" id="scope-student" value="student">
                                        <label for="scope-student">Assign to specific student</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="scope-class-fields" class="mb-3" style="display:none;">
                                    <label class="primary_input_label d-block">Class / Section</label>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <select id="class-select" class="primary_select form-control">
                                                <option value="">Select class</option>
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <select id="section-select" class="primary_select form-control">
                                                <option value="">All sections</option>
                                            </select>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block">All students in the selected class/section will receive these items.</small>
                                </div>
                                <div id="scope-student-fields">
                                    <label class="primary_input_label">Search student</label>
                                    <input type="text" id="student-search" class="primary_input_field form-control" placeholder="Type student name or admission no...">
                                    <input type="hidden" id="selected-student-id">
                                    <input type="hidden" id="selected-student-user-id">
                                    <input type="hidden" id="selected-student-name">
                                    <div id="student-results" class="list-group mt-1" style="display:none; max-height:200px; overflow-y:auto;"></div>
                                    <div id="selected-student-display" class="mt-2 text-success" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-20">
                            <div class="col-md-6">
                                <label class="primary_input_label">Search products</label>
                                <div class="input-group">
                                    <input type="text" id="product-search" class="primary_input_field form-control" placeholder="Type to search (e.g. books, uniform)...">
                                    <div class="input-group-append">
                                        <button type="button" id="btn-search-products" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1">Press Enter to search. Suggestions appear as you type.</small>
                                <div class="mt-3">
                                    <label class="primary_input_label">Categories</label>
                                    <select id="product-category" class="primary_select form-control">
                                        <option value="">All categories</option>
                                    </select>
                                    <small class="text-muted d-block mt-1">Use this to quickly jump between groups such as Special needs, books, uniforms, and more.</small>
                                </div>
                            </div>
                        <div class="col-md-3">
                                <label class="primary_input_label">Deadline (optional)</label>
                                <input type="date" id="assignment-deadline" class="primary_input_field form-control">
                                <small class="text-muted d-block">For information only; items remain even after the date.</small>
                            </div>
                            <div class="col-md-3">
                                <label class="primary_input_label">Selections</label>
                                <div id="selections-summary" class="selections-card cursor-pointer" style="cursor:pointer;" title="Click to view or edit selections">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="meta">Live selections</div>
                                            <div class="count-line"><span id="selections-count">0</span> item(s) selected</div>
                                            <div class="total-line">Total: <strong id="selections-total">KES 0.00</strong></div>
                                        </div>
                                        <span id="selections-chevron" class="ti-angle-down"></span>
                                    </div>
                                </div>
                                <div id="selections-detail" class="border rounded p-3 mt-2 bg-light" style="display:none;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>Selected items</strong>
                                        <button type="button" id="btn-remove-all" class="btn btn-sm btn-outline-danger">Remove all</button>
                                    </div>
                                    <div class="mb-2 small">
                                        <strong>Recipients:</strong> <span id="detail-recipients" class="text-muted">—</span>
                                    </div>
                                    <div class="mb-2 small">
                                        <strong>Deadline:</strong> <span id="detail-deadline" class="text-muted">—</span>
                                    </div>
                                    <ul id="selections-list" class="list-unstyled mb-0" style="max-height:280px; overflow-y:auto;"></ul>
                                    <div class="mt-2 pt-2 border-top">
                                        <strong>Total: <span id="detail-total">KES 0.00</span></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="products-loading" class="text-center py-4" style="display:none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div id="products-list" class="mb-20"></div>

                        <div class="row">
                            <div class="col-12">
                                <button type="button" id="btn-assign" class="primary-btn fix-gr-bg" disabled>
                                    <span class="ti-check mr-2"></span> Confirm and assign
                                </button>
                                <span id="assign-status" class="ml-3"></span>
                            </div>
                        </div>
                        </div> <!-- /tab-assign-new -->
                        <div class="tab-pane fade {{ $activeTab === 'assigned' ? 'show active' : '' }}" id="tab-assign-history" role="tabpanel">
                            <h3 class="mb-15">Assigned items history</h3>
                            <p class="text-muted mb-3">Batches group items that were assigned together in a single selection.</p>
                            @if(isset($batches) && $batches->count())
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Scope</th>
                                            <th>Deadline</th>
                                            <th>Total items</th>
                                            <th>Pending</th>
                                            <th>Already bought</th>
                                            <th>Ordered</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($batches as $batch)
                                            @php
                                                $items = $batch->items;
                                                $total = $items->count();
                                                $pending = $items->where('status','pending')->count();
                                                $bought = $items->where('status','already_bought')->count();
                                                $ordered = $items->where('status','ordered')->count();
                                            @endphp
                                            <tr>
                                                <td>{{ $batch->created_at->format('d M Y H:i') }}</td>
                                                <td>
                                                    @if($batch->scope === 'all')
                                                        All students
                                                    @elseif($batch->scope === 'class')
                                                        Class ID {{ $batch->class_id }}@if($batch->section_id) — Section ID {{ $batch->section_id }}@endif
                                                    @else
                                                        Specific students
                                                    @endif
                                                </td>
                                                <td>{{ $batch->deadline ? $batch->deadline->format('d M Y') : 'None' }}</td>
                                                <td>{{ $total }}</td>
                                                <td>{{ $pending }} pending</td>
                                                <td>{{ $bought }} bought</td>
                                                <td>{{ $ordered }} ordered</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary toggle-batch-items" data-batch-id="{{ $batch->id }}">View items</button>
                                                </td>
                                            </tr>
                                            <template id="batch-items-template-{{ $batch->id }}">
                                                @if($items->isEmpty())
                                                    <span class="text-muted">No items in this batch.</span>
                                                @else
                                                    <div class="row">
                                                        @foreach($items as $item)
                                                            @php
                                                                $recommended = $item->recommendedItem;
                                                                $photo = optional($recommended)->image_url;
                                                                $qty = (int) ($item->assigned_quantity ?: 1);
                                                                $assignmentType = $item->assignment_type === 'required' ? 'Required' : 'Recommended';
                                                            @endphp
                                                            <div class="col-md-6 mb-3">
                                                                <div class="border rounded p-3 h-100">
                                                                    <div class="d-flex">
                                                                        @if($photo)
                                                                            <img src="{{ $photo }}" alt="Item image" class="batch-product-photo mr-3">
                                                                        @else
                                                                            <div class="batch-product-photo mr-3 d-flex align-items-center justify-content-center text-muted small">No image</div>
                                                                        @endif
                                                                        <div class="flex-grow-1">
                                                                            <div class="font-weight-bold mb-1">{{ optional($recommended)->item_name ?? 'Item' }}</div>
                                                                            <div class="small text-muted mb-1">Category: {{ optional($recommended)->item_type ?: 'General' }}</div>
                                                                            <div class="small mb-1">Assigned quantity: <strong>{{ $qty }}</strong></div>
                                                                            <div class="small mb-1">Type: <strong>{{ $assignmentType }}</strong></div>
                                                                            <div class="small mb-1">Status: <span class="badge badge-light">{{ ucfirst($item->status) }}</span></div>
                                                                            @if(optional($recommended)->description)
                                                                                <div class="small text-muted mb-1">{{ $recommended->description }}</div>
                                                                            @endif
                                                                            @if($item->notes)
                                                                                <div class="small text-muted">Reason: {{ $item->notes }}</div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-2 text-right">
                                                                        <form method="POST" action="{{ route('assign-items-to-student.unassign-item', $item->id) }}" onsubmit="return confirm('Unassign this item from all recipients in this batch?');">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-sm btn-outline-danger">Unassign</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="border-top pt-3 mt-2">
                                                        <form method="POST" action="{{ route('assign-items-to-student.unassign-batch', $batch->id) }}" onsubmit="return confirm('Unassign all items in this batch?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">Unassign entire batch</button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </template>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    {{ $batches->links() }}
                                </div>
                            @else
                                <p class="text-muted">No assignments found yet.</p>
                            @endif
                        </div> <!-- /tab-assign-history -->
                        </div> <!-- /tab-content -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="batchItemsModal" tabindex="-1" role="dialog" aria-labelledby="batchItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="batchItemsModalLabel">Assigned items</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="batchItemsModalBody"></div>
            </div>
        </div>
    </div>

    @push('script')
    <script>
        (function() {
            const csrf = '{{ csrf_token() }}';
            const baseUrl = '{{ url("/") }}';

            let selectedProducts = [];
            let selectedStudent = null;
            let currentScope = 'all';
            let selectedClassId = '';
            let selectedSectionId = '';
            let selectedCategoryId = '';
            let currentQuery = '';
            let currentPage = 1;
            let lastPage = 1;

            function formatPrice(n) {
                return (Number(n)).toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function escapeHtml(s) {
                if (!s) return '';
                const div = document.createElement('div');
                div.textContent = s;
                return div.innerHTML;
            }

            function updateScopeVisibility() {
                if (currentScope === 'class') {
                    $('#scope-class-fields').show();
                    $('#scope-student-fields').hide();
                } else if (currentScope === 'student') {
                    $('#scope-class-fields').hide();
                    $('#scope-student-fields').show();
                } else {
                    $('#scope-class-fields').hide();
                    $('#scope-student-fields').hide();
                }
                updateButtonsState();
            }

            $('.scope-radio').on('change', function() {
                currentScope = $(this).val();
                updateScopeVisibility();
            });

            $('#class-select').on('change', function() {
                selectedClassId = $(this).val() || '';
                selectedSectionId = '';
                $('#section-select').html('<option value=\"\">All sections</option>');
                if (!selectedClassId) {
                    updateButtonsState();
                    return;
                }
                $.get(baseUrl + '/assign-items-to-student/sections', { class_id: selectedClassId }).done(function(res) {
                    const sections = res.sections || [];
                    let options = '<option value=\"\">All sections</option>';
                    sections.forEach(function(s) {
                        options += '<option value=\"' + s.id + '\">' + s.section_name + '</option>';
                    });
                    $('#section-select').html(options);
                });
                updateButtonsState();
            });

            $('#section-select').on('change', function() {
                selectedSectionId = $(this).val() || '';
                updateButtonsState();
            });

            function renderProducts(products) {
                if (!products.length) {
                    $('#products-list').html('<p class="text-muted">No products found. Try a different search or leave empty to see all.</p>');
                    return;
                }

                let html = '<h5 class="mb-3">Select items to assign</h5><div class="row">';
                products.forEach(function(p) {
                    const id = (p.id || '').toString();
                    const selected = selectedProducts.find(function(sp) { return String(sp.id) === String(id); }) || null;
                    const isChecked = !!selected;
                    const name = escapeHtml(p.name || '');
                    const category = escapeHtml(p.category || '');
                    const price = Number(p.price || 0);
                    const quantity = selected ? Math.max(1, Number(selected.quantity || 1)) : 1;
                    const assignmentType = selected ? String(selected.assignment_type || 'recommended') : 'recommended';
                    const imgUrl = (p.image_url || '').replace(/"/g, '');
                    const imgTag = imgUrl
                        ? '<img src="' + imgUrl + '" alt="" class="product-thumb mr-2 rounded" loading="lazy" width="56" height="56" style="object-fit:cover;">'
                        : '<span class="product-thumb-placeholder mr-2 d-inline-block bg-light rounded text-muted" style="width:56px;height:56px;line-height:56px;text-align:center;font-size:10px;">No image</span>';
                    html += '<div class="col-md-6 col-lg-4 mb-3">' +
                        '<div class="border rounded p-3 h-100">' +
                        '<label class="d-flex align-items-start mb-0 cursor-pointer">' +
                        '<input type="checkbox" class="product-cb mt-1 mr-2" ' + (isChecked ? 'checked ' : '') +
                        'data-id="' + escapeHtml(id) + '" data-name="' + name + '" data-category="' + category + '" data-price="' + price + '" data-description="' + escapeHtml(p.description || '') + '" data-image="' + imgUrl + '" data-url="' + escapeHtml(p.product_url || '') + '">' +
                        '<div class="flex-grow-1 min-w-0">' +
                        imgTag +
                        '<strong class="d-block">' + name + '</strong>' +
                        '<small class="text-muted d-block">' + category + ' — KES ' + formatPrice(price) + '</small>' +
                        '<div class="mt-2 d-flex align-items-center flex-wrap">' +
                        '<label class="small text-muted mb-0 mr-2">Qty</label>' +
                        '<input type="number" min="1" max="999" value="' + quantity + '" class="form-control form-control-sm product-qty" data-id="' + escapeHtml(id) + '" style="width:80px;">' +
                        '<label class="small text-muted mb-0 ml-3 mr-2">Type</label>' +
                        '<select class="form-control form-control-sm product-assignment-type" data-id="' + escapeHtml(id) + '" style="width:150px;">' +
                        '<option value="recommended" ' + (assignmentType === 'recommended' ? 'selected' : '') + '>Recommended</option>' +
                        '<option value="required" ' + (assignmentType === 'required' ? 'selected' : '') + '>Required</option>' +
                        '</select>' +
                        '</div>' +
                        '</div></label></div></div>';
                });
                html += '</div>';
                $('#products-list').html(html);
            }

            function renderProductsPagination(meta) {
                if (!meta || !meta.last_page) {
                    $('#products-pagination').remove();
                    return;
                }
                currentPage = meta.current_page || 1;
                lastPage = meta.last_page || 1;

                let html = '<div id="products-pagination" class="d-flex justify-content-between align-items-center mt-3">' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary mr-2" id="btn-prev-page" ' + (currentPage <= 1 ? 'disabled' : '') + '>&laquo; Prev</button>' +
                    '<div class="d-flex align-items-center small text-muted">' +
                    'Page&nbsp;<input type="number" id="page-input" class="form-control form-control-sm d-inline-block" style="width:80px;" min="1" max="' + lastPage + '" value="' + currentPage + '">&nbsp;of&nbsp;' + lastPage +
                    '<button type="button" class="btn btn-sm btn-outline-primary ml-2" id="btn-page-go">Go</button>' +
                    '</div>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary ml-2" id="btn-next-page" ' + (currentPage >= lastPage ? 'disabled' : '') + '>Next &raquo;</button>' +
                    '</div>';

                $('#products-pagination').remove();
                $('#products-list').after(html);
            }

            function performSearch(page) {
                const categoryParam = selectedCategoryId ? '&category_id=' + encodeURIComponent(selectedCategoryId) : '';
                const url = baseUrl + '/assign-items-to-student/search-products?q=' + encodeURIComponent(currentQuery) + '&page=' + page + categoryParam;
                $('#products-loading').show();
                $('#products-list').empty();
                $('#products-pagination').remove();

                $.get(url).done(function(res) {
                    $('#products-loading').hide();
                    const products = res.products || [];
                    renderProducts(products);
                    renderProductsPagination(res.meta || null);
                    renderSelectionsSummary();
                }).fail(function() {
                    $('#products-loading').hide();
                    $('#products-list').html('<p class="text-danger">Failed to load products.</p>');
                });
            }

            $('#btn-search-products').on('click', function() {
                currentQuery = $('#product-search').val().trim();
                performSearch(1);
            });

            $('#product-search').on('keypress', function(e) {
                if (e.which === 13) { e.preventDefault(); $('#btn-search-products').click(); }
            });

            $('#product-category').on('change', function() {
                selectedCategoryId = $(this).val() || '';
                performSearch(1);
            });

            // Pagination controls for product list
            $(document).on('click', '#btn-prev-page', function() {
                if (currentPage > 1) {
                    performSearch(currentPage - 1);
                }
            });

            $(document).on('click', '#btn-next-page', function() {
                if (currentPage < lastPage) {
                    performSearch(currentPage + 1);
                }
            });

            // Jump to page
            $(document).on('click', '#btn-page-go', function() {
                const input = $('#page-input');
                let target = parseInt(input.val(), 10);
                if (isNaN(target)) return;
                if (target < 1) target = 1;
                if (target > lastPage) target = lastPage;
                performSearch(target);
            });

            $(document).on('keypress', '#page-input', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#btn-page-go').click();
                }
            });

            $(document).on('change', '.product-cb', function() {
                const cb = $(this);
                const id = String(cb.data('id'));
                const qtyInput = cb.closest('.border').find('.product-qty').first();
                const quantity = Math.max(1, parseInt(qtyInput.val(), 10) || 1);
                if (cb.is(':checked')) {
                    if (!selectedProducts.some(function(sp) { return String(sp.id) === id; })) {
                        selectedProducts.push({
                            id: id,
                            name: cb.data('name'),
                            category: cb.data('category'),
                            price: parseFloat(cb.data('price')) || 0,
                            description: cb.data('description'),
                            image_url: cb.data('image'),
                            product_url: cb.data('url'),
                            quantity: quantity,
                            assignment_type: cb.closest('.border').find('.product-assignment-type').val() || 'recommended'
                        });
                    }
                } else {
                    selectedProducts = selectedProducts.filter(function(sp) { return String(sp.id) !== id; });
                }
                renderSelectionsSummary();
                updateButtonsState();
            });

            $(document).on('input change', '.product-qty', function() {
                const input = $(this);
                const id = String(input.data('id'));
                const quantity = Math.max(1, parseInt(input.val(), 10) || 1);
                input.val(quantity);
                const existing = selectedProducts.find(function(sp) { return String(sp.id) === id; });
                if (existing) {
                    existing.quantity = quantity;
                    renderSelectionsSummary();
                }
            });

            $(document).on('change', '.product-assignment-type', function() {
                const select = $(this);
                const id = String(select.data('id'));
                const value = (select.val() === 'required') ? 'required' : 'recommended';
                const existing = selectedProducts.find(function(sp) { return String(sp.id) === id; });
                if (existing) {
                    existing.assignment_type = value;
                    renderSelectionsSummary();
                }
            });

            function renderSelectionsSummary() {
                const count = selectedProducts.length;
                const total = selectedProducts.reduce(function(sum, p) {
                    return sum + ((p.price || 0) * Math.max(1, Number(p.quantity || 1)));
                }, 0);
                $('#selections-count').text(count);
                $('#selections-total').text('KES ' + formatPrice(total));
                $('#detail-total').text('KES ' + formatPrice(total));

                let recipientsText = '—';
                if (currentScope === 'all') recipientsText = 'All students';
                else if (currentScope === 'class' && selectedClassId) {
                    recipientsText = $('#class-select option:selected').text() + (selectedSectionId ? (' — ' + $('#section-select option:selected').text()) : ' (all sections)');
                } else if (currentScope === 'student' && selectedStudent) recipientsText = selectedStudent.name;
                $('#detail-recipients').text(recipientsText);

                const deadlineVal = $('#assignment-deadline').val();
                $('#detail-deadline').text(deadlineVal || 'None');

                let listHtml = '';
                selectedProducts.forEach(function(p) {
                    const qty = Math.max(1, Number(p.quantity || 1));
                    const lineTotal = (p.price || 0) * qty;
                    const assignmentType = (p.assignment_type === 'required') ? 'Required' : 'Recommended';
                    listHtml += '<li class="d-flex justify-content-between align-items-center py-2 border-bottom">' +
                        '<span class="text-truncate flex-grow-1">' + escapeHtml(p.name) + ' × ' + qty + ' — KES ' + formatPrice(lineTotal) + '<span class="ml-2 badge badge-light">' + assignmentType + '</span></span>' +
                        '<button type="button" class="btn btn-sm btn-outline-danger ml-2 remove-selection" data-id="' + escapeHtml(String(p.id)) + '" title="Remove from selections">' +
                        '<i class="ti-close"></i></button></li>';
                });
                if (!listHtml) {
                    listHtml = '<li class="text-muted small">No items selected. Search and check products above.</li>';
                    $('#btn-remove-all').hide();
                } else {
                    $('#btn-remove-all').show();
                }
                $('#selections-list').html(listHtml);

                // Subtle pulse animation on cart summary when items change
                const $summary = $('#selections-summary');
                $summary.removeClass('cart-pulse');
                // force reflow so animation can restart
                void $summary[0].offsetWidth;
                $summary.addClass('cart-pulse');
            }

            $('#selections-summary').on('click', function() {
                var detail = $('#selections-detail');
                detail.toggle();
                $('#selections-chevron').toggleClass('ti-angle-down ti-angle-up');
                if (detail.is(':visible')) renderSelectionsSummary();
            });

            $(document).on('click', '.remove-selection', function() {
                var id = $(this).data('id');
                selectedProducts = selectedProducts.filter(function(sp) { return String(sp.id) !== String(id); });
                $('.product-cb').each(function() { if (String($(this).data('id')) === String(id)) $(this).prop('checked', false); });
                renderSelectionsSummary();
                updateButtonsState();
            });

            // Assigned items tab: popup modal for batch items
            $(document).on('click', '.toggle-batch-items', function() {
                var batchId = $(this).data('batch-id');
                var template = document.getElementById('batch-items-template-' + batchId);
                if (!template) return;
                $('#batchItemsModalBody').html(template.innerHTML);
                $('#batchItemsModalLabel').text('Assigned items in batch #' + batchId);
                $('#batchItemsModal').modal('show');
            });

            $('#btn-remove-all').on('click', function() {
                selectedProducts = [];
                $('.product-cb').prop('checked', false);
                renderSelectionsSummary();
                updateButtonsState();
            });

            $('#student-search').on('input', function() {
                const q = $(this).val().trim();
                $('#selected-student-id').val('');
                $('#selected-student-user-id').val('');
                $('#selected-student-name').val('');
                $('#selected-student-display').hide();
                selectedStudent = null;
                updateButtonsState();
                if (q.length < 2) {
                    $('#student-results').hide().empty();
                    return;
                }
                $.get(baseUrl + '/assign-items-to-student/search-students', { q: q }).done(function(res) {
                    const students = res.students || [];
                    let html = '';
                    students.forEach(function(s) {
                        html += '<a href="#" class="list-group-item list-group-item-action student-option" data-id="' + s.id + '" data-user-id="' + s.user_id + '" data-name="' + (s.full_name || '').replace(/"/g, '&quot;') + '">' + (s.full_name || '') + ' (' + (s.admission_no || '') + ')</a>';
                    });
                    $('#student-results').html(html || '<span class="list-group-item text-muted">No students found</span>').show();
                });
            });

            $(document).on('click', '.student-option', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const userId = $(this).data('user-id');
                const name = $(this).data('name');
                $('#selected-student-id').val(id);
                $('#selected-student-user-id').val(userId);
                $('#selected-student-name').val(name);
                $('#student-search').val(name);
                $('#student-results').hide().empty();
                $('#selected-student-display').text('Student: ' + name).show();
                selectedStudent = { id: id, user_id: userId, name: name };
                updateButtonsState();
            });

            function updateButtonsState() {
                let recipientsReady = false;
                if (currentScope === 'all') {
                    recipientsReady = true;
                } else if (currentScope === 'class') {
                    recipientsReady = !!selectedClassId;
                } else if (currentScope === 'student') {
                    recipientsReady = !!(selectedStudent && selectedStudent.id);
                }
                const hasProducts = selectedProducts.length > 0;
                $('#btn-assign').prop('disabled', !(recipientsReady && hasProducts));
            }

            $('#btn-assign').on('click', function() {
                const studentId = $('#selected-student-id').val();
                if (selectedProducts.length === 0) return;
                $('#assign-status').text('Assigning...').css('color', '');
                const payload = {
                    _token: csrf,
                    scope: currentScope,
                    class_id: selectedClassId,
                    section_id: selectedSectionId,
                    student_id: studentId,
                    products: selectedProducts,
                    deadline: $('#assignment-deadline').val() || null,
                };
                $.ajax({
                    url: baseUrl + '/assign-items-to-student/assign',
                    method: 'POST',
                    data: payload
                }).done(function(res) {
                    const assigned = (typeof res.assigned === 'number') ? res.assigned : null;
                    const message = assigned !== null
                        ? (assigned === 0 ? 'Items already assigned to all selected recipients.' : (assigned + ' item(s) assigned to selected recipients.'))
                        : 'Items assigned successfully.';
                    $('#assign-status').text(message).css('color', 'green');
                    selectedProducts = [];
                    $('.product-cb').prop('checked', false);
                    renderSelectionsSummary();
                    updateButtonsState();
                    $('#selections-detail').hide();
                    $('#selections-chevron').removeClass('ti-angle-up').addClass('ti-angle-down');

                    // Reload fresh data and open "Assigned items" tab automatically.
                    setTimeout(function () {
                        window.location.href = baseUrl + '/assign-items-to-student?tab=assigned';
                    }, 450);
                }).fail(function(xhr) {
                    const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Failed to assign.';
                    $('#assign-status').text(msg).css('color', 'red');
                });
            });

            function loadCategories() {
                $.get(baseUrl + '/assign-items-to-student/categories').done(function(res) {
                    const categories = res.categories || [];
                    let options = '<option value=\"\">All categories</option>';
                    categories.forEach(function(c) {
                        const id = c.id;
                        const name = escapeHtml(c.name || 'Category');
                        options += '<option value=\"' + id + '\">' + name + '</option>';
                    });
                    const $select = $('#product-category');
                    $select.html(options);
                    if ($select.hasClass('primary_select')) {
                        try {
                            $select.niceSelect('update');
                        } catch (e) {
                            // niceSelect may not be initialised yet; ignore.
                        }
                    }
                }).fail(function() {
                    // Keep the default "All categories" option if loading fails.
                });
            }

            // Load categories + initial product list on page load
            loadCategories();
            $('#btn-search-products').click();

            updateScopeVisibility();
        })();
    </script>
    @endpush
@endsection
