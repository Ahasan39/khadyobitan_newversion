@extends('backEnd.layouts.master')
@section('title','Business Setting')

@section('content')
<div class="container mt-4">

    <!-- Top Tabs -->
    <ul class="nav nav-tabs" id="settingTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#homeTab"
                type="button" role="tab" aria-controls="homeTab" aria-selected="true">
                🏠 Home Page
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="whatsapp-tab" data-bs-toggle="tab" data-bs-target="#whatsappTab"
                type="button" role="tab" aria-controls="whatsappTab" aria-selected="false">
                💬 WhatsApp Message
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="other-tab" data-bs-toggle="tab" data-bs-target="#otherTab"
                type="button" role="tab" aria-controls="otherTab" aria-selected="false">
                ⚙️ Order Notification 
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3" id="settingTabsContent">

        <!-- Home Page Tab -->
        <div class="tab-pane fade show active" id="homeTab" role="tabpanel" aria-labelledby="home-tab">
            <div class="card shadow-sm border-0">
                <div class="card-header text-white"
                    style='background: linear-gradient(-45deg, #ffa53b, #0da487, #009289);'>
                    <h5 class="mb-0">🏷️ Homepage Section Visibility</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.business_setting.update') }}" method="POST">
                        @csrf

                          <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="show_new_arrival" id="show_new_arrival"
                        {{ $show_new_arrival ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_new_arrival">
                        <strong>🆕 Show New Arrival</strong>
                    </label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="show_category" id="show_category"
                        {{ $show_category ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_category">
                        <strong>🗂️ Category</strong>
                    </label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="show_top_rated" id="show_top_rated"
                        {{ $show_top_rated ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_top_rated">
                        <strong>⭐ Show Top Rated</strong>
                    </label>
                </div>
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="show_top_selling" id="show_top_selling"
                        {{ $show_top_selling ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_top_selling">
                        <strong>🔥 Show Top Selling</strong>
                    </label>
                </div>
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="show_all_product" id="show_all_product"
                        {{ $show_all_product ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_all_product">
                        <strong>🛒 Show All Product</strong>
                    </label>
                </div>
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="show_brand" id="show_brand"
                        {{ $show_brand ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_brand">
                        <strong>🏢 Show Brand</strong>
                    </label>
                </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Save Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- WhatsApp Tab -->
        <div class="tab-pane fade" id="whatsappTab" role="tabpanel" aria-labelledby="whatsapp-tab">
            @include('backEnd.business_settings.partials.whatsapp')
        </div>

        <!-- Other Settings Tab -->
      <div class="card shadow-sm border-0">
    <div class="card-header text-white bg-primary">
        <h5 class="mb-0">⚙️ Order Notification Settings</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.business_setting.updateOrderNotification') }}" method="POST">
            @csrf
            @php
                $orderSettings = \App\Models\OrderNotificationSetting::first();
            @endphp

            <!-- WhatsApp -->
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="send_to_whatsapp" id="send_to_whatsapp"
                    {{ $orderSettings && $orderSettings->send_to_whatsapp ? 'checked' : '' }}>
                <label class="form-check-label" for="send_to_whatsapp">
                    💬 Send Order Notification to WhatsApp
                </label>
            </div>
            <div class="mb-3">
                <label for="whatsapp_number" class="form-label">WhatsApp Number</label>
                <input type="text" class="form-control" name="whatsapp_number" id="whatsapp_number"
                       value="{{ $orderSettings->whatsapp_number ?? '' }}" placeholder="+8801XXXXXXXXX">
            </div>

            <!-- Email -->
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="send_to_email" id="send_to_email"
                    {{ $orderSettings && $orderSettings->send_to_email ? 'checked' : '' }}>
                <label class="form-check-label" for="send_to_email">
                    📧 Send Order Notification to Email
                </label>
            </div>
            <div class="mb-3">
                <label for="email_address" class="form-label">Notification Email</label>
                <input type="email" class="form-control" name="email_address" id="email_address"
                       value="{{ $orderSettings->email_address ?? '' }}" placeholder="example@domain.com">
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fa fa-save"></i> Save Notification Settings
            </button>
        </form>
    </div>
</div>


    </div>
</div>


<!-- Create Modal -->
<div class="modal fade" id="createSettingModal" tabindex="-1" aria-labelledby="createSettingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.business_setting.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createSettingModalLabel">➕ Add New Setting</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="index" class="form-label">Type Index</label>
                        <input type="text" class="form-control" name="type_index" id="index" placeholder="Enter setting key" required>
                    </div>
                    <div class="mb-3">
                        <label for="value" class="form-label">Value</label>
                        <textarea class="form-control" name="value" id="value" placeholder="Enter value"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Setting</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
