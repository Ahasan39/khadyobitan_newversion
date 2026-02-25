<h5 class="mb-3"><i class="fas fa-credit-card me-2"></i> Installment Message Settings</h5>

<div class="row">
    <!-- Message Text -->
    <div class="col-md-12 mb-3">
        <label for="message_text" class="form-label">Message Text <span class="text-danger">*</span></label>
        <textarea class="form-control @error('message_text') is-invalid @enderror" 
                  id="message_text" 
                  name="message_text" 
                  rows="3"
                 >{{ old('message_text', $settings['message_text'] ?? 'Pay in easy installments! Contact us for more details.') }}</textarea>
        @error('message_text')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">The message to display about installment options</small>
    </div>

    <!-- Display Location -->
    <div class="col-md-12 mb-3">
        <label for="display_location" class="form-label">Display Location <span class="text-danger">*</span></label>
        <select class="form-select @error('display_location') is-invalid @enderror" 
                id="display_location" 
                name="display_location" 
               >
            <option value="product" {{ old('display_location', $settings['display_location'] ?? 'both') == 'product' ? 'selected' : '' }}>
                Product Page Only
            </option>
            <option value="checkout" {{ old('display_location', $settings['display_location'] ?? 'both') == 'checkout' ? 'selected' : '' }}>
                Checkout Page Only
            </option>
            <option value="both" {{ old('display_location', $settings['display_location'] ?? 'both') == 'both' ? 'selected' : '' }}>
                Both (Product & Checkout)
            </option>
        </select>
        @error('display_location')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Where the message should be displayed</small>
    </div>

    <!-- Background Color -->
    <div class="col-md-4 mb-3">
        <label for="background_color" class="form-label">Background Color <span class="text-danger">*</span></label>
        <input type="text" 
               class="form-control color-picker @error('background_color') is-invalid @enderror" 
               id="background_color" 
               name="background_color" 
               value="{{ old('background_color', $settings['background_color'] ?? '#FFF3CD') }}"
              >
        @error('background_color')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Background color of the message box</small>
    </div>

    <!-- Text Color -->
    <div class="col-md-4 mb-3">
        <label for="text_color" class="form-label">Text Color <span class="text-danger">*</span></label>
        <input type="text" 
               class="form-control color-picker @error('text_color') is-invalid @enderror" 
               id="text_color" 
               name="text_color" 
               value="{{ old('text_color', $settings['text_color'] ?? '#856404') }}"
              >
        @error('text_color')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Color of the message text</small>
    </div>

    <!-- Border Style -->
    <div class="col-md-4 mb-3">
        <label for="border_style" class="form-label">Border Style <span class="text-danger">*</span></label>
        <select class="form-select @error('border_style') is-invalid @enderror" 
                id="border_style" 
                name="border_style" 
               >
            <option value="solid" {{ old('border_style', $settings['border_style'] ?? 'solid') == 'solid' ? 'selected' : '' }}>
                Solid
            </option>
            <option value="dashed" {{ old('border_style', $settings['border_style'] ?? '') == 'dashed' ? 'selected' : '' }}>
                Dashed
            </option>
            <option value="none" {{ old('border_style', $settings['border_style'] ?? '') == 'none' ? 'selected' : '' }}>
                None
            </option>
        </select>
        @error('border_style')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Border style of the message box</small>
    </div>

    <!-- Border Color -->
    <div class="col-md-12 mb-3">
        <label for="border_color" class="form-label">Border Color</label>
        <input type="text" 
               class="form-control color-picker @error('border_color') is-invalid @enderror" 
               id="border_color" 
               name="border_color" 
               value="{{ old('border_color', $settings['border_color'] ?? '#FFEAA7') }}">
        @error('border_color')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Border color (only if border style is not "none")</small>
    </div>
</div>

<!-- Preview Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-info">
            <h6><i class="fe-eye me-2"></i> Live Preview</h6>
            <div id="installment-preview" 
                 style="padding: 15px 20px; 
                        border-radius: 8px; 
                        background: {{ $settings['background_color'] ?? '#FFF3CD' }}; 
                        color: {{ $settings['text_color'] ?? '#856404' }}; 
                        border: 2px {{ $settings['border_style'] ?? 'solid' }} {{ $settings['border_color'] ?? '#FFEAA7' }}; 
                        text-align: center;
                        font-weight: 500;">
                <i class="fas fa-credit-card me-2"></i>
                {{ $settings['message_text'] ?? 'Pay in easy installments! Contact us for more details.' }}
            </div>
        </div>
    </div>
</div>

<!-- Usage Instructions -->
<div class="row mt-3">
    <div class="col-12">
        <div class="alert alert-warning">
            <h6><i class="fe-info me-2"></i> Display Information</h6>
            <ul class="mb-0">
                <li><strong>Product Page:</strong> Message appears near the "Add to Cart" button</li>
                <li><strong>Checkout Page:</strong> Message appears in the payment section</li>
                <li><strong>Both:</strong> Message appears on both product and checkout pages</li>
            </ul>
        </div>
    </div>
</div>

@section('script')
@parent
<script>
// Live preview update
document.addEventListener('DOMContentLoaded', function() {
    const preview = document.getElementById('installment-preview');
    
    // Update message text
    document.getElementById('message_text').addEventListener('input', function() {
        const icon = '<i class="fas fa-credit-card me-2"></i>';
        preview.innerHTML = icon + this.value;
    });
    
    // Update background color
    $('#background_color').on('change.spectrum', function(e, color) {
        preview.style.background = color.toHexString();
    });
    
    // Update text color
    $('#text_color').on('change.spectrum', function(e, color) {
        preview.style.color = color.toHexString();
    });
    
    // Update border style
    document.getElementById('border_style').addEventListener('change', function() {
        const borderColor = document.getElementById('border_color').value;
        preview.style.border = `2px ${this.value} ${borderColor}`;
    });
    
    // Update border color
    $('#border_color').on('change.spectrum', function(e, color) {
        const borderStyle = document.getElementById('border_style').value;
        preview.style.border = `2px ${borderStyle} ${color.toHexString()}`;
    });
});
</script>
@endsection
