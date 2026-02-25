@extends('backEnd.layouts.master')
@section('title', 'Feature Toggles')
@section('css')
<style>
    .feature-card {
        transition: all 0.3s ease;
        border: 1px solid #e3e6f0;
        border-radius: 10px;
        overflow: hidden;
    }
    .feature-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .feature-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
    }
    .feature-icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #28a745;
    }
    input:checked + .slider:before {
        transform: translateX(26px);
    }
    .status-badge {
        font-size: 0.875rem;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
    }
    .feature-description {
        color: #6c757d;
        font-size: 0.9rem;
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Website Setting</a></li>
                        <li class="breadcrumb-item active">Feature Toggles</li>
                    </ol>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="page-title mb-0">🎛️ Feature Toggles Management</h4>
                    <a href="{{ route('feature.toggles.create') }}" class="btn btn-primary">
                        <i class="fe-plus me-1"></i> Create New Feature
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fe-info me-2"></i>
                <strong>Info:</strong> Enable or disable features from your website. Click "Edit" to configure feature settings.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Feature Cards -->
    <div class="row">
        @foreach($features as $feature)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <div class="feature-card">
                <div class="feature-header text-center">
                    <div class="feature-icon">
                        @if($feature->feature_key == 'help_button')
                            <i class="fas fa-headset"></i>
                        @elseif($feature->feature_key == 'social_media_floating')
                            <i class="fas fa-share-alt"></i>
                        @elseif($feature->feature_key == 'invoice_whatsapp_button')
                            <i class="fab fa-whatsapp"></i>
                        @elseif($feature->feature_key == 'installment_message')
                            <i class="fas fa-credit-card"></i>
                        @else
                            <i class="fas fa-toggle-on"></i>
                        @endif
                    </div>
                    <h5 class="mb-0">{{ $feature->feature_name }}</h5>
                </div>
                
                <div class="card-body">
                    <!-- Status Badge -->
                    <div class="text-center mb-3">
                        @if($feature->is_enabled)
                            <span class="status-badge bg-success text-white">
                                <i class="fe-check-circle"></i> Active
                            </span>
                        @else
                            <span class="status-badge bg-secondary text-white">
                                <i class="fe-x-circle"></i> Inactive
                            </span>
                        @endif
                    </div>

                    <!-- Feature Description -->
                    <div class="feature-description text-center">
                        @if($feature->feature_key == 'help_button')
                            Display a floating help button for customer support
                        @elseif($feature->feature_key == 'social_media_floating')
                            Show floating social media icons on your website
                        @elseif($feature->feature_key == 'invoice_whatsapp_button')
                            Add WhatsApp button in order invoices
                        @elseif($feature->feature_key == 'installment_message')
                            Display installment payment information
                        @endif
                    </div>

                    <!-- Toggle Switch -->
                    <div class="text-center my-3">
                        <form method="POST" action="{{ route('feature.toggles.toggle') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="feature_id" value="{{ $feature->id }}">
                            <label class="toggle-switch">
                                <input type="checkbox" 
                                       {{ $feature->is_enabled ? 'checked' : '' }}
                                       onchange="this.form.submit()">
                                <span class="slider"></span>
                            </label>
                        </form>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('feature.toggles.edit', $feature->id) }}" 
                           class="btn btn-primary btn-sm">
                            <i class="fe-edit"></i> Configure Settings
                        </a>
                        <form action="{{ route('feature.toggles.destroy', $feature->id) }}" 
                              method="POST" 
                              class="delete-form"
                              onsubmit="return confirm('Are you sure you want to delete this feature? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                <i class="fe-trash-2"></i> Delete Feature
                            </button>
                        </form>
                    </div>

                    <!-- Last Updated -->
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fe-clock"></i> Updated: {{ $feature->updated_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($features->isEmpty())
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fe-alert-circle" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3">No Features Found</h4>
                    <p class="text-muted">Please run the seeder to create default features.</p>
                    <code>php artisan db:seed --class=FeatureToggleSeeder</code>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('script')
<script>
    // Auto-submit toggle forms with confirmation
    document.querySelectorAll('.toggle-switch input[type="checkbox"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', function(e) {
            const status = this.checked ? 'enable' : 'disable';
            if (!confirm(`Are you sure you want to ${status} this feature?`)) {
                e.preventDefault();
                this.checked = !this.checked;
                return false;
            }
        });
    });
</script>
@endsection
