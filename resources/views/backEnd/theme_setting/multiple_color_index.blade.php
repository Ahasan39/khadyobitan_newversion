@extends('backEnd.layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">🎨 Theme Color Settings</h4>
                </div>

                <div class="card-body bg-light">
                    <form action="{{ route('theme.setting.store') }}" method="POST">
                        @csrf
                            <div class='row'>
                                <div class='col-6'>
                                    <div class="form-group mb-3">
                                    <label for="header_color" class="font-weight-bold">🟥 Header Color</label>
                                    <input type="color" id="header_color" name="header_color" value="{{ $setting->header_color ?? '#ffffff' }}" class="form-control form-control-color">
                                </div>
        
                                <div class="form-group mb-3">
                                    <label for="footer_color" class="font-weight-bold">🟪 Footer Color</label>
                                    <input type="color" id="footer_color" name="footer_color" value="{{ $setting->footer_color ?? '#ffffff' }}" class="form-control form-control-color">
                                </div>
        
                                <div class="form-group mb-3">
                                    <label for="text_color" class="font-weight-bold">⬛ Text Color</label>
                                    <input type="color" id="text_color" name="text_color" value="{{ $setting->text_color ?? '#000000' }}" class="form-control form-control-color">
                                </div>
        
                                <div class="form-group mb-3">
                                    <label for="button_color" class="font-weight-bold">🟦 Order Button Color</label>
                                    <input type="color" id="button_color" name="button_color" value="{{ $setting->button_color ?? '#007bff' }}" class="form-control form-control-color">
                                </div>
        
                                <div class="form-group mb-3">
                                    <label for="add_to_cart_color" class="font-weight-bold">🟧 Add to Cart Button Color</label>
                                    <input type="color" id="add_to_cart_color" name="add_to_cart_color" value="{{ $setting->add_to_cart_color ?? '#28a745' }}" class="form-control form-control-color">
                                </div>
        
                                <div class="form-group mb-4">
                                    <label for="price_color" class="font-weight-bold">💰 Price Color</label>
                                    <input type="color" id="price_color" name="price_color" value="{{ $setting->price_color ?? '#dc3545' }}" class="form-control form-control-color">
                                </div>
                                </div>
                                <div class='col-6'>
                                    <div class="form-group mb-3">
                                        <label for="header_color" class="font-weight-bold">🟥 Single Page Order Btn</label>
                                        <input type="color" id="header_color" name="single_order_color" value="{{ $setting->single_order_color ?? '#ffffff' }}" class="form-control form-control-color">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="header_color" class="font-weight-bold">🟥 Checkout Header Color</label>
                                        <input type="color" id="header_color" name="checkout_header_color" value="{{ $setting->checkout_header_color ?? '#ffffff' }}" class="form-control form-control-color">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="header_color" class="font-weight-bold">🟥 Checkout Order Btn</label>
                                        <input type="color" id="header_color" name="checkout_order_color" value="{{ $setting->checkout_order_color ?? '#ffffff' }}" class="form-control form-control-color">
                                    </div>
                                </div>
                                
                            </div>
                        
                                

                        <div class="text-end">
                            <button type="submit" class="btn btn-success px-4">
                                💾 Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center text-muted small">
                    <em>Powered by <a href="https://webleez.com" target="_blank">Webleez</a></em>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
