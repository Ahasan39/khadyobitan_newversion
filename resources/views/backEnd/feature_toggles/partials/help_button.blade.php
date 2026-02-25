<h5 class="mb-3"><i class="fas fa-headset me-2"></i> Help Button Settings</h5>

<div class="row">
    <!-- Button Text -->
    <div class="col-md-6 mb-3">
        <label for="text" class="form-label">Button Text <span class="text-danger">*</span></label>
        <input type="text" 
               class="form-control @error('text') is-invalid @enderror" 
               id="text" 
               name="text" 
               value="{{ old('text', $settings['text'] ?? 'Need Help?') }}"
               placeholder="e.g., Need Help?"
              >
        @error('text')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Text displayed on the help button</small>
    </div>

    <!-- Position -->
    <div class="col-md-6 mb-3">
        <label for="position" class="form-label">Button Position <span class="text-danger">*</span></label>
        <select class="form-select @error('position') is-invalid @enderror" 
                id="position" 
                name="position" 
               >
            <option value="bottom-right" {{ old('position', $settings['position'] ?? 'bottom-right') == 'bottom-right' ? 'selected' : '' }}>
                Bottom Right
            </option>
            <option value="bottom-left" {{ old('position', $settings['position'] ?? '') == 'bottom-left' ? 'selected' : '' }}>
                Bottom Left
            </option>
            <option value="top-right" {{ old('position', $settings['position'] ?? '') == 'top-right' ? 'selected' : '' }}>
                Top Right
            </option>
            <option value="top-left" {{ old('position', $settings['position'] ?? '') == 'top-left' ? 'selected' : '' }}>
                Top Left
            </option>
        </select>
        @error('position')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Where the button appears on the screen</small>
    </div>

    <!-- Phone Number -->
    <div class="col-md-6 mb-3">
        <label for="phone" class="form-label">Phone Number</label>
        <input type="text" 
               class="form-control @error('phone') is-invalid @enderror" 
               id="phone" 
               name="phone" 
               value="{{ old('phone', $settings['phone'] ?? '') }}"
               placeholder="+8801XXXXXXXXX">
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Phone number for direct calls (optional)</small>
    </div>

    <!-- WhatsApp Number -->
    <div class="col-md-6 mb-3">
        <label for="whatsapp" class="form-label">WhatsApp Number</label>
        <input type="text" 
               class="form-control @error('whatsapp') is-invalid @enderror" 
               id="whatsapp" 
               name="whatsapp" 
               value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}"
               placeholder="+8801XXXXXXXXX">
        @error('whatsapp')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">WhatsApp number for chat support (optional)</small>
    </div>

    <!-- Button Color -->
    <div class="col-md-6 mb-3">
        <label for="color" class="form-label">Button Color <span class="text-danger">*</span></label>
        <input type="text" 
               class="form-control color-picker @error('color') is-invalid @enderror" 
               id="color" 
               name="color" 
               value="{{ old('color', $settings['color'] ?? '#25D366') }}"
              >
        @error('color')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Background color of the button</small>
    </div>

    <!-- Icon -->
    <div class="col-md-6 mb-3">
        <label for="icon" class="form-label">Icon Class</label>
        <input type="text" 
               class="form-control @error('icon') is-invalid @enderror" 
               id="icon" 
               name="icon" 
               value="{{ old('icon', $settings['icon'] ?? 'fa-headset') }}"
               placeholder="fa-headset">
        @error('icon')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Font Awesome icon class (e.g., fa-headset, fa-phone)</small>
    </div>
</div>

<!-- Preview Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-info">
            <h6><i class="fe-eye me-2"></i> Preview</h6>
            <div class="icon-preview">
                <div style="display: inline-block; padding: 15px 25px; background: {{ $settings['color'] ?? '#25D366' }}; color: white; border-radius: 50px; font-weight: 600;">
                    <i class="fas {{ $settings['icon'] ?? 'fa-headset' }} me-2"></i>
                    {{ $settings['text'] ?? 'Need Help?' }}
                </div>
            </div>
        </div>
    </div>
</div>
