@extends('backEnd.layouts.master')
@section('title', 'Edit Feature - ' . $feature->feature_name)
@section('css')
<link href="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2@2.0.9/dist/spectrum.min.css" rel="stylesheet">
<style>
    .feature-edit-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
    }
    .settings-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 10px;
    }
    .form-label {
        font-weight: 600;
        color: #495057;
    }
    .icon-preview {
        font-size: 2rem;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
        text-align: center;
    }
    .social-icon-item {
        border: 1px solid #dee2e6;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        background: #f8f9fa;
    }
    .remove-icon-btn {
        position: absolute;
        top: 10px;
        right: 10px;
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
                        <li class="breadcrumb-item"><a href="{{ route('feature.toggles.index') }}">Feature Toggles</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Feature</h4>
            </div>
        </div>
    </div>

    <!-- Feature Header -->
    <div class="row">
        <div class="col-12">
            <div class="feature-edit-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2">
                            @if($feature->feature_key == 'help_button')
                                <i class="fas fa-headset me-2"></i>
                            @elseif($feature->feature_key == 'social_media_floating')
                                <i class="fas fa-share-alt me-2"></i>
                            @elseif($feature->feature_key == 'invoice_whatsapp_button')
                                <i class="fab fa-whatsapp me-2"></i>
                            @elseif($feature->feature_key == 'installment_message')
                                <i class="fas fa-credit-card me-2"></i>
                            @endif
                            {{ $feature->feature_name }}
                        </h3>
                        <p class="mb-0 opacity-75">Configure settings for this feature</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('feature.toggles.index') }}" class="btn btn-light">
                            <i class="fe-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-12">
            <div class="card settings-card">
                <div class="card-body">
                    <form action="{{ route('feature.toggles.update', $feature->id) }}" method="POST">
                        @csrf
                        
                        <!-- Enable/Disable Toggle -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-light border">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="is_enabled" 
                                               id="is_enabled"
                                               {{ $feature->is_enabled ? 'checked' : '' }}
                                               style="width: 3rem; height: 1.5rem;">
                                        <label class="form-check-label ms-2" for="is_enabled">
                                            <strong>Enable this feature</strong>
                                            <br>
                                            <small class="text-muted">Turn this on to activate the feature on your website</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Feature-Specific Settings -->
                        @if($feature->feature_key == 'help_button')
                            @include('backEnd.feature_toggles.partials.help_button', ['settings' => $feature->settings])
                        @elseif($feature->feature_key == 'social_media_floating')
                            @include('backEnd.feature_toggles.partials.social_media_floating', ['settings' => $feature->settings])
                        @elseif($feature->feature_key == 'invoice_whatsapp_button')
                            @include('backEnd.feature_toggles.partials.invoice_whatsapp', ['settings' => $feature->settings])
                        @elseif($feature->feature_key == 'installment_message')
                            @include('backEnd.feature_toggles.partials.installment_message', ['settings' => $feature->settings])
                        @endif

                        <!-- Submit Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <hr class="mb-4">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('feature.toggles.index') }}" class="btn btn-secondary">
                                        <i class="fe-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fe-save me-1"></i> Save Settings
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2@2.0.9/dist/spectrum.min.js"></script>
<script>
    // Initialize color pickers
    $(".color-picker").spectrum({
        type: "color",
        showInput: true,
        showInitial: true,
        allowEmpty: false,
        showAlpha: false,
        showPalette: true,
        palette: [
            ['#000000', '#434343', '#666666', '#999999', '#b7b7b7'],
            ['#cccccc', '#d9d9d9', '#efefef', '#f3f3f3', '#ffffff'],
            ['#980000', '#ff0000', '#ff9900', '#ffff00', '#00ff00'],
            ['#00ffff', '#4a86e8', '#0000ff', '#9900ff', '#ff00ff'],
            ['#25D366', '#1877F2', '#E4405F', '#1DA1F2', '#FF0000']
        ]
    });
</script>
@endsection
