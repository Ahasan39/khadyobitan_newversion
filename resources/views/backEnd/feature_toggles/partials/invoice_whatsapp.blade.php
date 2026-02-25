<h5 class="mb-3"><i class="fab fa-whatsapp me-2"></i> Invoice WhatsApp Button Settings</h5>

<div class="row">
    <!-- Button Text -->
    <div class="col-md-6 mb-3">
        <label for="button_text" class="form-label">Button Text <span class="text-danger">*</span></label>
        <input type="text" 
               class="form-control @error('button_text') is-invalid @enderror" 
               id="button_text" 
               name="button_text" 
               value="{{ old('button_text', $settings['button_text'] ?? 'Contact via WhatsApp') }}"
               placeholder="e.g., Contact via WhatsApp"
              >
        @error('button_text')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Text displayed on the WhatsApp button</small>
    </div>

    <!-- Phone Number -->
    <div class="col-md-6 mb-3">
        <label for="phone_number" class="form-label">WhatsApp Number <span class="text-danger">*</span></label>
        <input type="text" 
               class="form-control @error('phone_number') is-invalid @enderror" 
               id="phone_number" 
               name="phone_number" 
               value="{{ old('phone_number', $settings['phone_number'] ?? '') }}"
               placeholder="+8801XXXXXXXXX"
              >
        @error('phone_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">WhatsApp number (with country code)</small>
    </div>

    <!-- Message Template -->
    <div class="col-md-12 mb-3">
        <label for="message_template" class="form-label">Message Template <span class="text-danger">*</span></label>
        <textarea class="form-control @error('message_template') is-invalid @enderror" 
                  id="message_template" 
                  name="message_template" 
                  rows="4"
                 >{{ old('message_template', $settings['message_template'] ?? 'Hello, I have a question about my order #{invoice_id}. Customer: {customer_name}, Total: {total_amount}') }}</textarea>
        @error('message_template')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">
            <strong>Available Variables:</strong>
            <code>{invoice_id}</code>, 
            <code>{customer_name}</code>, 
            <code>{total_amount}</code>
        </small>
    </div>

    <!-- Button Color -->
    <div class="col-md-6 mb-3">
        <label for="button_color" class="form-label">Button Color <span class="text-danger">*</span></label>
        <input type="text" 
               class="form-control color-picker @error('button_color') is-invalid @enderror" 
               id="button_color" 
               name="button_color" 
               value="{{ old('button_color', $settings['button_color'] ?? '#25D366') }}"
              >
        @error('button_color')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Background color of the button</small>
    </div>

    <!-- Button Position -->
    <div class="col-md-6 mb-3">
        <label for="button_position" class="form-label">Button Position <span class="text-danger">*</span></label>
        <select class="form-select @error('button_position') is-invalid @enderror" 
                id="button_position" 
                name="button_position" 
               >
            <option value="top" {{ old('button_position', $settings['button_position'] ?? 'bottom') == 'top' ? 'selected' : '' }}>
                Top of Invoice
            </option>
            <option value="bottom" {{ old('button_position', $settings['button_position'] ?? 'bottom') == 'bottom' ? 'selected' : '' }}>
                Bottom of Invoice
            </option>
            <option value="both" {{ old('button_position', $settings['button_position'] ?? '') == 'both' ? 'selected' : '' }}>
                Both (Top & Bottom)
            </option>
        </select>
        @error('button_position')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Where the button appears in the invoice</small>
    </div>
</div>

<!-- Preview Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-info">
            <h6><i class="fe-eye me-2"></i> Preview</h6>
            <div class="icon-preview">
                <a href="#" 
                   class="btn btn-lg" 
                   style="background: {{ $settings['button_color'] ?? '#25D366' }}; color: white; text-decoration: none; border: none;">
                    <i class="fab fa-whatsapp me-2"></i>
                    {{ $settings['button_text'] ?? 'Contact via WhatsApp' }}
                </a>
            </div>
            <div class="mt-3 p-3 bg-light rounded">
                <strong>Sample Message:</strong>
                <p class="mb-0 mt-2">
                    {{ str_replace(
                        ['{invoice_id}', '{customer_name}', '{total_amount}'],
                        ['#12345', 'John Doe', '৳1,500'],
                        $settings['message_template'] ?? 'Hello, I have a question about my order #{invoice_id}.'
                    ) }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Usage Instructions -->
<div class="row mt-3">
    <div class="col-12">
        <div class="alert alert-warning">
            <h6><i class="fe-info me-2"></i> How to Use</h6>
            <ul class="mb-0">
                <li>The button will appear on customer order invoices</li>
                <li>When clicked, it opens WhatsApp with the pre-filled message</li>
                <li>Variables will be automatically replaced with actual order data</li>
                <li>Make sure to include country code in the phone number (e.g., +880)</li>
            </ul>
        </div>
    </div>
</div>
