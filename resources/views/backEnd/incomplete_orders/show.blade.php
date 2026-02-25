@extends('backEnd.layouts.master')
@section('title', 'Incomplete Order Details')
@section('css')
<style>
    .info-card {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .info-card h5 {
        border-bottom: 2px solid #f1f3fa;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
    .info-row {
        display: flex;
        padding: 10px 0;
        border-bottom: 1px solid #f1f3fa;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: 600;
        width: 150px;
        color: #6c757d;
    }
    .info-value {
        flex: 1;
    }
    .cart-item {
        padding: 15px;
        border: 1px solid #e3e6f0;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    .timeline-item:before {
        content: '';
        position: absolute;
        left: -24px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #727cf5;
    }
    .timeline-item:after {
        content: '';
        position: absolute;
        left: -19px;
        top: 17px;
        width: 2px;
        height: 100%;
        background: #e3e6f0;
    }
    .timeline-item:last-child:after {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.incomplete_orders.index') }}">Incomplete Orders</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
                <h4 class="page-title">Incomplete Order #{{ $incompleteOrder->id }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Information -->
        <div class="col-lg-6">
            <div class="info-card">
                <h5><i class="fe-user"></i> Customer Information</h5>
                <div class="info-row">
                    <div class="info-label">Name:</div>
                    <div class="info-value">{{ $incompleteOrder->name ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Phone:</div>
                    <div class="info-value">
                        <a href="tel:{{ $incompleteOrder->phone }}">{{ $incompleteOrder->phone ?? 'N/A' }}</a>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Address:</div>
                    <div class="info-value">{{ $incompleteOrder->address ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">IP Address:</div>
                    <div class="info-value">{{ $incompleteOrder->ip ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="badge bg-{{ $incompleteOrder->status_badge }} fs-6">
                            {{ ucfirst($incompleteOrder->status) }}
                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Created:</div>
                    <div class="info-value">{{ $incompleteOrder->formatted_created_at }}</div>
                </div>
                @if($incompleteOrder->total_amount)
                <div class="info-row">
                    <div class="info-label">Estimated Amount:</div>
                    <div class="info-value">
                        <strong class="text-success">৳{{ number_format($incompleteOrder->total_amount, 2) }}</strong>
                    </div>
                </div>
                @endif
            </div>

            <!-- Contact Actions -->
            <div class="info-card">
                <h5><i class="fe-phone"></i> Contact Actions</h5>
                <div class="d-grid gap-2">
                    <a href="tel:{{ $incompleteOrder->phone }}" class="btn btn-success btn-lg">
                        <i class="fe-phone"></i> Call Customer
                    </a>
                    @if($incompleteOrder->phone)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $incompleteOrder->phone) }}" 
                       target="_blank" class="btn btn-success btn-lg">
                        <i class="mdi mdi-whatsapp"></i> WhatsApp
                    </a>
                    @endif
                    <form action="{{ route('admin.incomplete_orders.mark_contacted', $incompleteOrder->id) }}" 
                          method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info btn-lg w-100">
                            <i class="fe-check-circle"></i> Mark as Contacted
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Details & Actions -->
        <div class="col-lg-6">
            <!-- Cart Data -->
            @if($incompleteOrder->cart_data && count($incompleteOrder->cart_data) > 0)
            <div class="info-card">
                <h5><i class="fe-shopping-cart"></i> Cart Items ({{ count($incompleteOrder->cart_data) }} items)</h5>
                @foreach($incompleteOrder->cart_data as $item)
                <div class="cart-item">
                    <div class="row align-items-center">
                        @if(isset($item['image']))
                        <div class="col-3">
                            <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] ?? 'Product' }}" 
                                 class="img-fluid rounded" style="max-height: 60px;">
                        </div>
                        @endif
                        <div class="col-{{ isset($item['image']) ? '6' : '9' }}">
                            <strong>{{ $item['name'] ?? 'Product' }}</strong>
                            <br>
                            <small class="text-muted">
                                Qty: {{ $item['qty'] ?? 1 }}
                                @if(isset($item['size']) && $item['size'])
                                    | Size: {{ $item['size'] }}
                                @endif
                                @if(isset($item['color']) && $item['color'])
                                    | Color: {{ $item['color'] }}
                                @endif
                            </small>
                        </div>
                        <div class="col-3 text-end">
                            <strong>৳{{ number_format(($item['price'] ?? 0) * ($item['qty'] ?? 1), 2) }}</strong>
                            <br>
                            <small class="text-muted">@৳{{ number_format($item['price'] ?? 0, 2) }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="cart-item bg-light">
                    <div class="d-flex justify-content-between">
                        <strong>Total Amount:</strong>
                        <strong class="text-success">৳{{ number_format($incompleteOrder->total_amount, 2) }}</strong>
                    </div>
                </div>
            </div>
            @else
            <div class="info-card">
                <h5><i class="fe-shopping-cart"></i> Cart Items</h5>
                <div class="alert alert-info">
                    <i class="fe-info"></i> No cart data available. Customer may have abandoned before adding products.
                </div>
            </div>
            @endif

            <!-- Status Update -->
            <div class="info-card">
                <h5><i class="fe-edit"></i> Update Status</h5>
                <form action="{{ route('admin.incomplete_orders.update_status', $incompleteOrder->id) }}" 
                      method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Change Status</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $incompleteOrder->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="contacted" {{ $incompleteOrder->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="converted" {{ $incompleteOrder->status == 'converted' ? 'selected' : '' }}>Converted</option>
                            <option value="abandoned" {{ $incompleteOrder->status == 'abandoned' ? 'selected' : '' }}>Abandoned</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fe-save"></i> Update Status
                    </button>
                </form>
            </div>

            <!-- Admin Notes -->
            <div class="info-card">
                <h5><i class="fe-file-text"></i> Admin Notes</h5>
                @if($incompleteOrder->admin_note)
                <div class="alert alert-info">
                    {{ $incompleteOrder->admin_note }}
                </div>
                @endif
                <form action="{{ route('admin.incomplete_orders.add_note', $incompleteOrder->id) }}" 
                      method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea name="admin_note" class="form-control" rows="4" 
                                  placeholder="Add your notes here..." required>{{ $incompleteOrder->admin_note }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fe-save"></i> Save Note
                    </button>
                </form>
            </div>

            <!-- Contact History -->
            @if($incompleteOrder->contacted_at)
            <div class="info-card">
                <h5><i class="fe-clock"></i> Contact History</h5>
                <div class="timeline">
                    <div class="timeline-item">
                        <div>
                            <strong>Contacted</strong>
                            <br>
                            <small class="text-muted">{{ $incompleteOrder->formatted_contacted_at }}</small>
                            @if($incompleteOrder->contactedBy)
                            <br>
                            <small class="text-muted">By: {{ $incompleteOrder->contactedBy->name }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div>
                            <strong>Created</strong>
                            <br>
                            <small class="text-muted">{{ $incompleteOrder->formatted_created_at }}</small>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="text-end">
                <a href="{{ route('admin.incomplete_orders.index') }}" class="btn btn-secondary">
                    <i class="fe-arrow-left"></i> Back to List
                </a>
                <form action="{{ route('admin.incomplete_orders.destroy', $incompleteOrder->id) }}" 
                      method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-danger delete-confirm">
                        <i class="fe-trash-2"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
