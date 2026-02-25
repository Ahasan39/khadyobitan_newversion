@extends('backEnd.layouts.master')
@section('title', 'Incomplete Orders')
@section('css')
<link href="{{ asset('public/backEnd/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
<style>
    .stats-card {
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .stats-card h3 {
        font-size: 28px;
        font-weight: bold;
        margin: 0;
    }
    .stats-card p {
        margin: 5px 0 0 0;
        color: #6c757d;
    }
    .filter-section {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .action-buttons .btn {
        margin-right: 5px;
        margin-bottom: 5px;
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
                        <li class="breadcrumb-item active">Incomplete Orders</li>
                    </ol>
                </div>
                <h4 class="page-title">Incomplete Orders Management</h4>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-2">
            <div class="stats-card bg-primary text-white">
                <h3>{{ $stats['total'] }}</h3>
                <p>Total</p>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stats-card bg-warning text-white">
                <h3>{{ $stats['pending'] }}</h3>
                <p>Pending</p>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stats-card bg-info text-white">
                <h3>{{ $stats['contacted'] }}</h3>
                <p>Contacted</p>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stats-card bg-success text-white">
                <h3>{{ $stats['converted'] }}</h3>
                <p>Converted</p>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stats-card bg-danger text-white">
                <h3>{{ $stats['abandoned'] }}</h3>
                <p>Abandoned</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row">
        <div class="col-12">
            <div class="filter-section">
                <form action="{{ route('admin.incomplete_orders.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted</option>
                                    <option value="abandoned" {{ request('status') == 'abandoned' ? 'selected' : '' }}>Abandoned</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Search</label>
                                <input type="text" name="keyword" class="form-control" placeholder="Name or Phone" value="{{ request('keyword') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fe-search"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <h4 class="header-title">Incomplete Orders List</h4>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end">
                                <button type="button" class="btn btn-info" id="bulkContactedBtn">
                                    <i class="fe-phone"></i> Mark as Contacted
                                </button>
                                <button type="button" class="btn btn-danger" id="bulkDeleteBtn">
                                    <i class="fe-trash-2"></i> Bulk Delete
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($incompleteOrders as $order)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                    </td>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="tel:{{ $order->phone }}">{{ $order->phone ?? 'N/A' }}</a>
                                    </td>
                                    <td>{{ Str::limit($order->address ?? 'N/A', 30) }}</td>
                                    <td>
                                        @if($order->total_amount)
                                            ৳{{ number_format($order->total_amount, 2) }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $order->status_badge }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.incomplete_orders.show', $order->id) }}" 
                                               class="btn btn-sm btn-info" title="View Details">
                                                <i class="fe-eye"></i>
                                            </a>
                                            <a href="tel:{{ $order->phone }}" 
                                               class="btn btn-sm btn-success" title="Call">
                                                <i class="fe-phone"></i>
                                            </a>
                                            @if($order->phone)
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->phone) }}" 
                                               target="_blank" class="btn btn-sm btn-success" title="WhatsApp">
                                                <i class="mdi mdi-whatsapp"></i>
                                            </a>
                                            @endif
                                            <form action="{{ route('admin.incomplete_orders.destroy', $order->id) }}" 
                                                  method="POST" style="display: inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger delete-confirm" title="Delete">
                                                    <i class="fe-trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No incomplete orders found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row mt-3">
                        <div class="col-12">
                            {{ $incompleteOrders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Select All Checkbox
        $('#selectAll').on('change', function() {
            $('.order-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Bulk Mark as Contacted
        $('#bulkContactedBtn').on('click', function() {
            var selectedIds = [];
            $('.order-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                toastr.error('Please select at least one order');
                return;
            }

            if (confirm('Are you sure you want to mark selected orders as contacted?')) {
                $.ajax({
                    url: '{{ route("admin.incomplete_orders.bulk_update_status") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order_ids: selectedIds,
                        status: 'contacted'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            location.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong');
                    }
                });
            }
        });

        // Bulk Delete
        $('#bulkDeleteBtn').on('click', function() {
            var selectedIds = [];
            $('.order-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                toastr.error('Please select at least one order');
                return;
            }

            swal({
                title: 'Are you sure?',
                text: 'You want to delete ' + selectedIds.length + ' selected order(s)?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: '{{ route("admin.incomplete_orders.bulk_delete") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            order_ids: selectedIds
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                toastr.success(response.message);
                                location.reload();
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('Something went wrong');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
