<h5 class="mb-3"><i class="fas fa-share-alt me-2"></i> Social Media Floating Settings</h5>

<div class="row">
    <!-- Position -->
    <div class="col-md-12 mb-3">
        <label for="position" class="form-label">Icons Position <span class="text-danger">*</span></label>
        <select class="form-select @error('position') is-invalid @enderror" 
                id="position" 
                name="position" 
               >
            <option value="left" {{ old('position', $settings['position'] ?? 'left') == 'left' ? 'selected' : '' }}>
                Left Side
            </option>
            <option value="right" {{ old('position', $settings['position'] ?? '') == 'right' ? 'selected' : '' }}>
                Right Side
            </option>
        </select>
        @error('position')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Where the social media icons appear on the screen</small>
    </div>
</div>

<hr class="my-4">

<h6 class="mb-3">Social Media Icons</h6>

<div id="social-icons-container">
    @php
        $icons = $settings['icons'] ?? [
            ['platform' => 'facebook', 'url' => '', 'icon' => 'fab fa-facebook-f', 'color' => '#1877F2'],
            ['platform' => 'instagram', 'url' => '', 'icon' => 'fab fa-instagram', 'color' => '#E4405F'],
            ['platform' => 'twitter', 'url' => '', 'icon' => 'fab fa-twitter', 'color' => '#1DA1F2'],
            ['platform' => 'youtube', 'url' => '', 'icon' => 'fab fa-youtube', 'color' => '#FF0000']
        ];
    @endphp

    @foreach($icons as $index => $icon)
    <div class="social-icon-item position-relative" data-index="{{ $index }}">
        <button type="button" class="btn btn-sm btn-danger remove-icon-btn" onclick="removeIcon(this)">
            <i class="fe-x"></i>
        </button>
        
        <div class="row">
            <!-- Platform -->
            <div class="col-md-3 mb-2">
                <label class="form-label">Platform</label>
                <select class="form-control" name="icons[{{ $index }}][platform]">
                    <option value="facebook" {{ ($icon['platform'] ?? '') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                    <option value="instagram" {{ ($icon['platform'] ?? '') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                    <option value="twitter" {{ ($icon['platform'] ?? '') == 'twitter' ? 'selected' : '' }}>Twitter</option>
                    <option value="youtube" {{ ($icon['platform'] ?? '') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                    <option value="linkedin" {{ ($icon['platform'] ?? '') == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                    <option value="tiktok" {{ ($icon['platform'] ?? '') == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                    <option value="pinterest" {{ ($icon['platform'] ?? '') == 'pinterest' ? 'selected' : '' }}>Pinterest</option>
                    <option value="whatsapp" {{ ($icon['platform'] ?? '') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                </select>
            </div>

            <!-- URL -->
            <div class="col-md-4 mb-2">
                <label class="form-label">URL</label>
                <input type="url" 
                       class="form-control" 
                       name="icons[{{ $index }}][url]" 
                       value="{{ $icon['url'] ?? '' }}"
                       placeholder="https://facebook.com/yourpage">
            </div>

            <!-- Icon Class -->
            <div class="col-md-3 mb-2">
                <label class="form-label">Icon Class</label>
                <input type="text" 
                       class="form-control" 
                       name="icons[{{ $index }}][icon]" 
                       value="{{ $icon['icon'] ?? '' }}"
                       placeholder="fab fa-facebook-f"
                      >
            </div>

            <!-- Color -->
            <div class="col-md-2 mb-2">
                <label class="form-label">Color</label>
                <input type="text" 
                       class="form-control color-picker" 
                       name="icons[{{ $index }}][color]" 
                       value="{{ $icon['color'] ?? '#1877F2' }}"
                      >
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row mt-3">
    <div class="col-12">
        <button type="button" class="btn btn-success" onclick="addIcon()">
            <i class="fe-plus me-1"></i> Add Social Media Icon
        </button>
    </div>
</div>

<!-- Preview Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-info">
            <h6><i class="fe-eye me-2"></i> Preview</h6>
            <div class="icon-preview">
                <div style="display: flex; flex-direction: column; gap: 10px; align-items: {{ ($settings['position'] ?? 'left') == 'left' ? 'flex-start' : 'flex-end' }};">
                    @foreach($icons as $icon)
                        @if(!empty($icon['url']))
                        <div style="width: 50px; height: 50px; background: {{ $icon['color'] ?? '#1877F2' }}; color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.5rem;">
                            <i class="{{ $icon['icon'] ?? 'fab fa-facebook-f' }}"></i>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
@parent
<script>
let iconIndex = {{ count($icons) }};

function addIcon() {
    const container = document.getElementById('social-icons-container');
    const newIcon = `
        <div class="social-icon-item position-relative" data-index="${iconIndex}">
            <button type="button" class="btn btn-sm btn-danger remove-icon-btn" onclick="removeIcon(this)">
                <i class="fe-x"></i>
            </button>
            
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label class="form-label">Platform</label>
                    <select class="form-control" name="icons[${iconIndex}][platform]">
                        <option value="facebook">Facebook</option>
                        <option value="instagram">Instagram</option>
                        <option value="twitter">Twitter</option>
                        <option value="youtube">YouTube</option>
                        <option value="linkedin">LinkedIn</option>
                        <option value="tiktok">TikTok</option>
                        <option value="pinterest">Pinterest</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">URL</label>
                    <input type="url" class="form-control" name="icons[${iconIndex}][url]" placeholder="https://facebook.com/yourpage">
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Icon Class</label>
                    <input type="text" class="form-control" name="icons[${iconIndex}][icon]" value="fab fa-facebook-f">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">Color</label>
                    <input type="text" class="form-control color-picker" name="icons[${iconIndex}][color]" value="#1877F2">
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', newIcon);
    
    // Reinitialize color picker for new element
    $(container).find('.color-picker').last().spectrum({
        type: "color",
        showInput: true,
        showInitial: true,
        allowEmpty: false,
        showAlpha: false
    });
    
    iconIndex++;
}

function removeIcon(button) {
    if (confirm('Are you sure you want to remove this icon?')) {
        button.closest('.social-icon-item').remove();
    }
}
</script>
@endsection
