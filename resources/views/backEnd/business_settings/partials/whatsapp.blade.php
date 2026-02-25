@php
    $whatsApp = App\Models\WhatsAppSetting::first();
@endphp

<div class="card shadow-sm border-0">
    <div class="card-header text-white bg-success">
        <h5 class="mb-0">📝 WhatsApp Message Settings</h5>
    </div>
    
    <div class="card-body">
        <form action="{{ route('admin.whatsapp_setting.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Company Name</label>
                <input type="text" class="form-control" name="name" id="name"
                    value="{{ old('name', optional($whatsApp)->name) }}">
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Default Message</label>
                <textarea class="form-control" name="message" id="message" rows="3">{{ old('message', optional($whatsApp)->message) }}</textarea>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fa fa-save"></i> Save WhatsApp Settings
            </button>
        </form>
    </div>
</div>