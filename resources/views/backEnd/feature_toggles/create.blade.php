@extends('backEnd.layouts.master')
@section('title', 'Create New Feature Toggle')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2@2.0.9/dist/spectrum.min.css" rel="stylesheet">
<style>
    .feature-create-header {
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
    .feature-type-card {
        border: 2px solid #e3e6f0;
        border-radius: 10px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 15px;
    }
    .feature-type-card:hover {
        border-color: #667eea;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    }
    .feature-type-card.selected {
        border-color: #667eea;
        background: #f8f9ff;
    }
    .feature-type-card input[type="radio"] {
        display: none;
    }
    .feature-settings-section {
        display: none;
        margin-top: 30px;
    }
    .feature-settings-section.active {
        display: block;
    }
    .social-icon-item {
        border: 1px solid #dee2e6;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        background: #f8f9fa;
        position: relative;
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
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
                <h4 class="page-title">Create New Feature</h4>
            </div>
        </div>
    </div>

    <!-- Feature Header -->
    <div class="row">
        <div class="col-12">
            <div class="feature-create-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2">
                            <i class="fas fa-plus-circle me-2"></i>
                            Create New Feature Toggle
                        </h3>
                        <p class="mb-0 opacity-75">Add a new feature to your website</p>
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

    <!-- Create Form -->
    <div class="row">
        <div class="col-12">
            <div class="card settings-card">
                <div class="card-body">
                    <form action="{{ route('feature.toggles.store') }}" method="POST" id="createFeatureForm">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="feature_key" class="form-label">Feature Key <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('feature_key') is-invalid @enderror" 
                                           id="feature_key" 
                                           name="feature_key" 
                                           value="{{ old('feature_key') }}"
                                           placeholder="e.g., my_custom_feature"
                                           required>
                                    <small class="text-muted">Unique identifier for this feature (lowercase, underscores only)</small>
                                    @error('feature_key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="feature_name" class="form-label">Feature Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('feature_name') is-invalid @enderror" 
                                           id="feature_name" 
                                           name="feature_name" 
                                           value="{{ old('feature_name') }}"
                                           placeholder="e.g., My Custom Feature"
                                           required>
                                    <small class="text-muted">Display name for this feature</small>
                                    @error('feature_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Enable/Disable Toggle -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-light border">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="is_enabled" 
                                               id="is_enabled"
                                               {{ old('is_enabled') ? 'checked' : '' }}
                                               style="width: 3rem; height: 1.5rem;">
                                        <label class="form-check-label ms-2" for="is_enabled">
                                            <strong>Enable this feature immediately</strong>
                                            <br>
                                            <small class="text-muted">Turn this on to activate the feature on your website after creation</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Feature Type Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">Select Feature Type <span class="text-danger">*</span></h5>
                                
                                <div class="row">
                                    <!-- Help Button -->
                                    <div class="col-md-6">
                                        <label class="feature-type-card" for="type_help_button">
                                            <input type="radio" name="feature_type" id="type_help_button" value="help_button" {{ old('feature_type') == 'help_button' ? 'checked' : '' }}>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3" style="font-size: 2rem;">
                                                    <i class="fas fa-headset text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">Help Button</h6>
                                                    <small class="text-muted">Floating help/support button</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Social Media Floating -->
                                    <div class="col-md-6">
                                        <label class="feature-type-card" for="type_social_media">
                                            <input type="radio" name="feature_type" id="type_social_media" value="social_media_floating" {{ old('feature_type') == 'social_media_floating' ? 'checked' : '' }}>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3" style="font-size: 2rem;">
                                                    <i class="fas fa-share-alt text-info"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">Social Media Floating</h6>
                                                    <small class="text-muted">Floating social media icons</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- WhatsApp Invoice Button -->
                                    <div class="col-md-6">
                                        <label class="feature-type-card" for="type_whatsapp">
                                            <input type="radio" name="feature_type" id="type_whatsapp" value="invoice_whatsapp_button" {{ old('feature_type') == 'invoice_whatsapp_button' ? 'checked' : '' }}>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3" style="font-size: 2rem;">
                                                    <i class="fab fa-whatsapp text-success"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">Invoice WhatsApp Button</h6>
                                                    <small class="text-muted">WhatsApp button in invoices</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Installment Message -->
                                    <div class="col-md-6">
                                        <label class="feature-type-card" for="type_installment">
                                            <input type="radio" name="feature_type" id="type_installment" value="installment_message" {{ old('feature_type') == 'installment_message' ? 'checked' : '' }}>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3" style="font-size: 2rem;">
                                                    <i class="fas fa-credit-card text-warning"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">Installment Message</h6>
                                                    <small class="text-muted">Display installment info</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Custom Feature -->
                                    <div class="col-md-12">
                                        <label class="feature-type-card" for="type_custom">
                                            <input type="radio" name="feature_type" id="type_custom" value="custom" {{ old('feature_type') == 'custom' ? 'checked' : '' }}>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3" style="font-size: 2rem;">
                                                    <i class="fas fa-cog text-secondary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">Custom Feature</h6>
                                                    <small class="text-muted">Create a custom feature without predefined settings</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Feature-Specific Settings -->
                        <div id="settings_help_button" class="feature-settings-section">
                            @include('backEnd.feature_toggles.partials.help_button', ['settings' => []])
                        </div>

                        <div id="settings_social_media_floating" class="feature-settings-section">
                            @include('backEnd.feature_toggles.partials.social_media_floating', ['settings' => []])
                        </div>

                        <div id="settings_invoice_whatsapp_button" class="feature-settings-section">
                            @include('backEnd.feature_toggles.partials.invoice_whatsapp', ['settings' => []])
                        </div>

                        <div id="settings_installment_message" class="feature-settings-section">
                            @include('backEnd.feature_toggles.partials.installment_message', ['settings' => []])
                        </div>

                        <div id="settings_custom" class="feature-settings-section">
                            <div class="alert alert-info">
                                <i class="fe-info me-2"></i>
                                <strong>Custom Feature:</strong> This feature will be created without predefined settings. You can configure it programmatically in your code.
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <hr class="mb-4">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('feature.toggles.index') }}" class="btn btn-secondary">
                                        <i class="fe-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fe-save me-1"></i> Create Feature
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

    // Handle feature type selection
    document.querySelectorAll('input[name="feature_type"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            console.log('Feature type selected:', this.value);
            
            // Remove selected class from all cards
            document.querySelectorAll('.feature-type-card').forEach(function(card) {
                card.classList.remove('selected');
            });
            
            // Add selected class to chosen card
            this.closest('.feature-type-card').classList.add('selected');
            
            // Hide all settings sections and remove required from their inputs
            document.querySelectorAll('.feature-settings-section').forEach(function(section) {
                section.classList.remove('active');
                // Remove required attribute from all inputs in hidden sections
                section.querySelectorAll('input[required], select[required], textarea[required]').forEach(function(input) {
                    input.removeAttribute('required');
                });
            });
            
            // Show selected settings section and add required to its inputs
            const settingsId = 'settings_' + this.value;
            const settingsSection = document.getElementById(settingsId);
            if (settingsSection) {
                settingsSection.classList.add('active');
                // Add required attribute back to inputs in active section (except for custom type)
                if (this.value !== 'custom') {
                    settingsSection.querySelectorAll('input[data-required="true"], select[data-required="true"], textarea[data-required="true"]').forEach(function(input) {
                        input.setAttribute('required', 'required');
                    });
                }
                console.log('Settings section shown:', settingsId);
            }
        });
    });

    // Trigger change event on page load if a type is already selected
    document.addEventListener('DOMContentLoaded', function() {
        const selectedType = document.querySelector('input[name="feature_type"]:checked');
        if (selectedType) {
            selectedType.dispatchEvent(new Event('change'));
        }
    });

    // Auto-generate feature key from feature name
    document.getElementById('feature_name').addEventListener('input', function() {
        const featureKey = this.value
            .toLowerCase()
            .replace(/[^a-z0-9\s]/g, '')
            .replace(/\s+/g, '_');
        document.getElementById('feature_key').value = featureKey;
    });

    // Form submission validation and debugging
    document.getElementById('createFeatureForm').addEventListener('submit', function(e) {
        console.log('Form submitting...');
        
        const featureKey = document.getElementById('feature_key').value;
        const featureName = document.getElementById('feature_name').value;
        const featureType = document.querySelector('input[name="feature_type"]:checked');
        
        console.log('Feature Key:', featureKey);
        console.log('Feature Name:', featureName);
        console.log('Feature Type:', featureType ? featureType.value : 'NOT SELECTED');
        
        // Validate required fields
        if (!featureKey) {
            e.preventDefault();
            alert('Please enter a Feature Key');
            console.error('Validation failed: Feature Key is empty');
            return false;
        }
        
        if (!featureName) {
            e.preventDefault();
            alert('Please enter a Feature Name');
            console.error('Validation failed: Feature Name is empty');
            return false;
        }
        
        if (!featureType) {
            e.preventDefault();
            alert('Please select a Feature Type');
            console.error('Validation failed: Feature Type not selected');
            return false;
        }
        
        console.log('Form validation passed, submitting...');
        return true;
    });

    // Log any errors
    window.addEventListener('error', function(e) {
        console.error('JavaScript Error:', e.message, e.filename, e.lineno);
    });
</script>
@endsection
