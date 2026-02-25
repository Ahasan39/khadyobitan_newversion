@extends('backEnd.layouts.master')

@section('title', 'License Management')

@section('content')
<div class="container-fluid">
    <!-- License Generator -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">🔑 License Key Generator</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('licenses.generate') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label for="version" class="form-label">Version</label>
                    <input type="text" class="form-control" id="version" name="version" placeholder="e.g. 1.0.0" required>
                </div>
                <div class="col-md-4">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" max="100" placeholder="How many to generate" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-key me-1"></i> Generate Keys
                    </button>
                </div>
            </form>
        </div>
    </div>



        <div class="card-body">
            <!-- Search -->
            <form method="GET" action="{{ route('licenses.index') }}" class="row mb-4">
                <div class="col-md-4">
                    <input type="text" name="version" value="{{ request('version') }}" class="form-control"
                           placeholder="Search by version (e.g. 1.0.0)">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100" type="submit">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('licenses.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-sync-alt me-1"></i> Reset
                    </a>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>License Key</th>
                            <th>Version</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($licenses as $license)
                            <tr>
                                <td class="text-monospace text-break" style="font-size:15px;">{{ $license->license_key }}</td>
                                <td class="text-center">{{ $license->version }}</td>
                                <td class="text-center">
                                    <span class="badge rounded-pill {{ $license->is_used ? 'bg-danger' : 'bg-success' }}" style="font-size:14px;">
                                        {{ $license->is_used ? 'Used' : 'Available' }}
                                    </span>
                                </td>
                                <td class="text-center">{{ $license->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No license keys found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($licenses->hasPages())
                <div class="mt-3">
                    {{ $licenses->links('vendor.pagination.bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table {
        border-radius: 10px;
        overflow: hidden;
    }
    .table thead {
        background: #007bff;
        color: #fff;
        font-size: 16px;
        font-weight: 600;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.08);
        transition: background-color 0.2s;
    }
    .badge {
        font-size: 13px;
        padding: 0.4em 0.7em;
    }
    .card-header {
        background: #f8f9fa;
        border-bottom: 2px solid #007bff;
    }
</style>
@endsection
