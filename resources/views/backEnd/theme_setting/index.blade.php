@extends('backEnd.layouts.master')
@section('content')
<style>
    .color-input-group {
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    .color-input-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #495057;
    }
    .color-input-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .color-input-wrapper input[type="color"] {
        width: 60px;
        height: 40px;
        border: 2px solid #dee2e6;
        border-radius: 6px;
        cursor: pointer;
    }
    .color-input-wrapper input[type="text"] {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-family: monospace;
    }
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #212529;
        margin-top: 25px;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #dee2e6;
    }
    .section-title:first-child {
        margin-top: 0;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Sync color picker with text input
        const colorInputs = document.querySelectorAll('input[type="color"]');
        colorInputs.forEach(colorInput => {
            const textInput = colorInput.nextElementSibling;
            
            colorInput.addEventListener('input', function() {
                textInput.value = this.value;
            });
            
            textInput.addEventListener('input', function() {
                if(/^#[0-9A-F]{6}$/i.test(this.value)) {
                    colorInput.value = this.value;
                }
            });
        });
    });
</script>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Theme Color Settings</h4>
                </div>
                <div class="card-body bg-light">
                    <form method="POST" action="{{ url('admin/theme-color') }}">
                        @csrf
                        
                        <!-- Offer Marquee Section -->
                        <h5 class="section-title">Offer Marquee</h5>
                        <div class='row'>
                            
                        <div class="col-6 color-input-group">
                            <label>Offer Background Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="offer_bg" value="{{ $data->offer_bg ?? '#ff0000' }}">
                                <input type="text" value="{{ $data->offer_bg ?? '#ff0000' }}" placeholder="#ff0000" maxlength="7">
                            </div>
                        </div>
                        <div class="col-6 color-input-group">
                            <label>Offer Text Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="offer_text" value="{{ $data->offer_text ?? '#ffffff' }}">
                                <input type="text" value="{{ $data->offer_text ?? '#ffffff' }}" placeholder="#ffffff" maxlength="7">
                            </div>
                        </div>
                        </div>

                        <!-- Header Section -->
                        <h5 class="section-title">Header</h5>
                          <div class='row'>
                              
                        <div class="col-6 color-input-group">
                            <label>Header Background Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="header_bg" value="{{ $data->header_bg ?? '#ffffff' }}">
                                <input type="text" value="{{ $data->header_bg ?? '#ffffff' }}" placeholder="#ffffff" maxlength="7">
                            </div>
                        </div>
                        <div class="col-6 color-input-group">
                            <label>Header Text Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="header_text" value="{{ $data->header_text ?? '#000000' }}">
                                <input type="text" value="{{ $data->header_text ?? '#000000' }}" placeholder="#000000" maxlength="7">
                            </div>
                        </div>
                        <div class="col-6 color-input-group">
                            <label>Category Dropdown Text Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="category_dropdown_text" value="{{ $data->category_dropdown_text ?? '#000000' }}">
                                <input type="text" value="{{ $data->category_dropdown_text ?? '#000000' }}" placeholder="#000000" maxlength="7">
                            </div>
                        </div>
                          </div>

                        <!-- Body Section -->
                        <h5 class="section-title">Body</h5>
                          <div class='row'>
                              
                        <div class="col-6 color-input-group">
                            <label>Body Background Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="body_bg" value="{{ $data->body_bg ?? '#ffffff' }}">
                                <input type="text" value="{{ $data->body_bg ?? '#ffffff' }}" placeholder="#ffffff" maxlength="7">
                            </div>
                        </div>
                        <div class="col-6 color-input-group">
                            <label>Body Text Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="body_text" value="{{ $data->body_text ?? '#000000' }}">
                                <input type="text" value="{{ $data->body_text ?? '#000000' }}" placeholder="#000000" maxlength="7">
                            </div>
                        </div>
                          </div>

                        <!-- Product Card Section -->
                        <h5 class="section-title">Product Card</h5>
                          <div class='row'>
                              
                        <div class="col-6 color-input-group">
                            <label>Product Card Background Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="product_bg" value="{{ $data->product_bg ?? '#ffffff' }}">
                                <input type="text" value="{{ $data->product_bg ?? '#ffffff' }}" placeholder="#ffffff" maxlength="7">
                            </div>
                        </div>
                        <div class="col-6 color-input-group">
                            <label>Product Card Text Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="product_text" value="{{ $data->product_text ?? '#000000' }}">
                                <input type="text" value="{{ $data->product_text ?? '#000000' }}" placeholder="#000000" maxlength="7">
                            </div>
                        </div>
                        <div class="col-6 color-input-group">
                            <label>Price Text Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="price_text" value="{{ $data->price_text ?? '#ff0000' }}">
                                <input type="text" value="{{ $data->price_text ?? '#ff0000' }}" placeholder="#ff0000" maxlength="7">
                            </div>
                        </div>
                          </div>

                        <!-- Buttons Section -->
                        <h5 class="section-title">Buttons</h5>
                          <div class='row'>
                              
                        <div class="col-6 color-input-group">
                            <label>Order Button Background Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="order_btn_bg" value="{{ $data->order_btn_bg ?? '#007bff' }}">
                                <input type="text" value="{{ $data->order_btn_bg ?? '#007bff' }}" placeholder="#007bff" maxlength="7">
                            </div>
                        </div>
                        <div class="col-6 color-input-group">
                            <label>Order Button Hover Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="order_btn_hover" value="{{ $data->order_btn_hover ?? '#0056b3' }}">
                                <input type="text" value="{{ $data->order_btn_hover ?? '#0056b3' }}" placeholder="#0056b3" maxlength="7">
                            </div>
                        </div>
                        <div class="col-6 color-input-group">
                            <label>Order Button Text Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="order_btn_text" value="{{ $data->order_btn_text ?? '#ffffff' }}">
                                <input type="text" value="{{ $data->order_btn_text ?? '#ffffff' }}" placeholder="#ffffff" maxlength="7">
                            </div>
                        </div>
                        <div class="col-6 color-input-group">
                            <label>Cart Button Background Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="cart_btn_bg" value="{{ $data->cart_btn_bg ?? '#28a745' }}">
                                <input type="text" value="{{ $data->cart_btn_bg ?? '#28a745' }}" placeholder="#28a745" maxlength="7">
                            </div>
                        </div>
                        <div class="col-6 color-input-group">
                            <label>Cart Button Hover Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="cart_btn_hover" value="{{ $data->cart_btn_hover ?? '#1e7e34' }}">
                                <input type="text" value="{{ $data->cart_btn_hover ?? '#1e7e34' }}" placeholder="#1e7e34" maxlength="7">
                            </div>
                        </div>
                        <div class="col-6 color-input-group">
                            <label>Cart Button Text Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="cart_btn_text" value="{{ $data->cart_btn_text ?? '#ffffff' }}">
                                <input type="text" value="{{ $data->cart_btn_text ?? '#ffffff' }}" placeholder="#ffffff" maxlength="7">
                            </div>
                        </div>
                          </div>
                          <h5 class="section-title">Recent Product Card</h5>
<div class="row">
    <div class="col-12 col-md-3 color-input-group">
        <label>Background</label>
         <div class="color-input-wrapper">
        <input type="color" name="recent_product_bg"value="{{ $data->recent_product_bg ?? '#007bff' }}">
                                <!--<input type="text" value="{{ $data->recent_product_bg ?? '#007bff' }}" placeholder="#007bff" maxlength="7">-->
        </div>
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Text Color</label>
        <input type="color" name="recent_product_text"value="{{ $data->recent_product_text ?? '#007bff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Order Button BG</label>
        <input type="color" name="recent_order_btn_bg" value="{{ $data->recent_order_btn_bg ?? '#007bff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Order Button Hover</label>
        <input type="color" name="recent_order_btn_hover"value="{{ $data->recent_order_btn_hover ?? '#007bff' }}">
    </div>

    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Order Button Text</label>
        <input type="color" name="recent_order_btn_text"value="{{ $data->recent_order_btn_text ?? '#007bff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Cart Button BG</label>
        <input type="color" name="recent_cart_btn_bg"value="{{ $data->recent_cart_btn_bg ?? '#007bff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Cart Button Hover</label>
        <input type="color" name="recent_cart_btn_hover"value="{{ $data->recent_cart_btn_hover ?? '#007bff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Cart Button Text</label>
        <input type="color" name="recent_cart_btn_text"value="{{ $data->recent_cart_btn_text ?? '#007bff' }}">
    </div>
</div>
<hr>
<h5 class="section-title">Quick View Popup</h5>
<div class="row">
    <div class="col-12 col-md-3 color-input-group">
        <label>Background</label>
        <input type="color" name="quick_bg" value="{{ $data->quick_bg ?? '#ffffff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Text Color</label>
        <input type="color" name="quick_text" value="{{ $data->quick_text ?? '#000000' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Price Color</label>
        <input type="color" name="quick_price" value="{{ $data->quick_price ?? '#ff0000' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Add To Cart BG</label>
        <input type="color" name="quick_cart_btn_bg" value="{{ $data->quick_cart_btn_bg ?? '#28a745' }}">
    </div>

    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Add To Cart Text</label>
        <input type="color" name="quick_cart_btn_text" value="{{ $data->quick_cart_btn_text ?? '#ffffff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Buy Now BG</label>
        <input type="color" name="quick_buy_btn_bg" value="{{ $data->quick_buy_btn_bg ?? '#007bff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Buy Now Text</label>
        <input type="color" name="quick_buy_btn_text" value="{{ $data->quick_buy_btn_text ?? '#ffffff' }}">
    </div>
</div>
<hr>
<h5 class="section-title">Mobile Footer</h5>
<div class="row">
    <div class="col-12 col-md-3 color-input-group">
        <label>Text Color</label>
        <input type="color" name="mobile_footer_text" value="{{ $data->mobile_footer_text ?? '#000000' }}">
    </div>
</div>
<hr>
<h5 class="section-title">Product Details Page</h5>
<div class="row">
    <div class="col-12 col-md-3 color-input-group">
        <label>Info Section BG</label>
        <input type="color" name="details_info_bg" value="{{ $data->details_info_bg ?? '#ffffff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Product Text</label>
        <input type="color" name="details_product_text" value="{{ $data->details_product_text ?? '#000000' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Price Color</label>
        <input type="color" name="details_price_text" value="{{ $data->details_price_text ?? '#ff0000' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Accordion BG</label>
        <input type="color" name="details_accordion_bg" value="{{ $data->details_accordion_bg ?? '#f8f9fa' }}">
    </div>

    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Accordion Tab BG</label>
        <input type="color" name="details_accordion_tab_bg" value="{{ $data->details_accordion_tab_bg ?? '#ffffff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Tab Hover BG</label>
        <input type="color" name="details_accordion_tab_hover_bg" value="{{ $data->details_accordion_tab_hover_bg ?? '#e9ecef' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Accordion Text</label>
        <input type="color" name="details_accordion_text" value="{{ $data->details_accordion_text ?? '#000000' }}">
    </div>
</div>
<hr>
<h5 class="section-title">Quick Order</h5>
<div class="row">
    <div class="col-12 col-md-3 color-input-group">
        <label>Background</label>
        <input type="color" name="quick_order_bg" value="{{ $data->quick_order_bg ?? '#ffffff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Text Color</label>
        <input type="color" name="quick_order_text" value="{{ $data->quick_order_text ?? '#000000' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Button 1 BG</label>
        <input type="color" name="quick_order_btn1_bg" value="{{ $data->quick_order_btn1_bg ?? '#007bff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Button 2 BG</label>
        <input type="color" name="quick_order_btn2_bg" value="{{ $data->quick_order_btn2_bg ?? '#28a745' }}">
    </div>

    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Title Text</label>
        <input type="color" name="quick_order_title_text" value="{{ $data->quick_order_title_text ?? '#000000' }}">
    </div>
</div>
<hr>
<h5 class="section-title">Details Tabs</h5>
<div class="row">
    <div class="col-12 col-md-3 color-input-group">
        <label>Tab Background</label>
        <input type="color" name="details_tab_bg" value="{{ $data->details_tab_bg ?? '#f8f9fa' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Active Tab BG</label>
        <input type="color" name="details_tab_active_bg" value="{{ $data->details_tab_active_bg ?? '#007bff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Tab Text</label>
        <input type="color" name="details_tab_text" value="{{ $data->details_tab_text ?? '#000000' }}">
    </div>
</div>
<hr>
<h5 class="section-title">Cart Drawer</h5>
<div class="row">
    <div class="col-12 col-md-3 color-input-group">
        <label>Background</label>
        <input type="color" name="cart_drawer_bg" value="{{ $data->cart_drawer_bg ?? '#ffffff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Text Color</label>
        <input type="color" name="cart_drawer_text" value="{{ $data->cart_drawer_text ?? '#000000' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Checkout Button BG</label>
        <input type="color" name="checkout_btn_bg" value="{{ $data->checkout_btn_bg ?? '#007bff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Checkout Button Text</label>
        <input type="color" name="checkout_btn_text" value="{{ $data->checkout_btn_text ?? '#ffffff' }}">
    </div>

    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>View Cart BG</label>
        <input type="color" name="view_cart_btn_bg" value="{{ $data->view_cart_btn_bg ?? '#28a745' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>View Cart Text</label>
        <input type="color" name="view_cart_btn_text" value="{{ $data->view_cart_btn_text ?? '#ffffff' }}">
    </div>
</div>
<hr>
<h5 class="section-title">Sidebars & Checkout</h5>
<div class="row">
    <div class="col-12 col-md-3 color-input-group">
        <label>Main Sidebar BG</label>
        <input type="color" name="main_sidebar_bg" value="{{ $data->main_sidebar_bg ?? '#ffffff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Main Sidebar Text</label>
        <input type="color" name="main_sidebar_text" value="{{ $data->main_sidebar_text ?? '#000000' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Category Sidebar BG</label>
        <input type="color" name="category_sidebar_bg" value="{{ $data->category_sidebar_bg ?? '#f8f9fa' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group">
        <label>Category Sidebar Text</label>
        <input type="color" name="category_sidebar_text" value="{{ $data->category_sidebar_text ?? '#000000' }}">
    </div>

    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Filter Sidebar BG</label>
        <input type="color" name="filter_sidebar_bg" value="{{ $data->filter_sidebar_bg ?? '#ffffff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Filter Sidebar Text</label>
        <input type="color" name="filter_sidebar_text" value="{{ $data->filter_sidebar_text ?? '#000000' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Filter Active Color</label>
        <input type="color" name="filter_sidebar_active" value="{{ $data->filter_sidebar_active ?? '#007bff' }}">
    </div>

    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Search Popup BG</label>
        <input type="color" name="search_popup_bg" value="{{ $data->search_popup_bg ?? '#ffffff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Search Popup Text</label>
        <input type="color" name="search_popup_text" value="{{ $data->search_popup_text ?? '#000000' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Search Price Color</label>
        <input type="color" name="search_popup_price" value="{{ $data->search_popup_price ?? '#ff0000' }}">
    </div>

    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Checkout Header BG</label>
        <input type="color" name="checkout_header_bg" value="{{ $data->checkout_header_bg ?? '#007bff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Checkout Header Text</label>
        <input type="color" name="checkout_header_text" value="{{ $data->checkout_header_text ?? '#ffffff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Checkout Card BG</label>
        <input type="color" name="checkout_card_bg" value="{{ $data->checkout_card_bg ?? '#ffffff' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Checkout Text</label>
        <input type="color" name="checkout_text" value="{{ $data->checkout_text ?? '#000000' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Order Button BG</label>
        <input type="color" name="checkout_order_btn_bg" value="{{ $data->checkout_order_btn_bg ?? '#28a745' }}">
    </div>
    <div class="col-12 col-md-3 color-input-group mt-2">
        <label>Order Button Text</label>
        <input type="color" name="checkout_order_btn_text" value="{{ $data->checkout_order_btn_text ?? '#ffffff' }}">
    </div>
</div>



                        <!-- Footer Section -->
                        <h5 class="section-title">Footer</h5>
                          <div class='row'>
                              
                        <div class="col-6 color-input-group">
                            <label>Footer Background Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="footer_bg" value="{{ $data->footer_bg ?? '#343a40' }}">
                                <input type="text" value="{{ $data->footer_bg ?? '#343a40' }}" placeholder="#343a40" maxlength="7">
                            </div>
                        </div>
                        <div class="col-6 color-input-group">
                            <label>Footer Text Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="footer_text" value="{{ $data->footer_text ?? '#ffffff' }}">
                                <input type="text" value="{{ $data->footer_text ?? '#ffffff' }}" placeholder="#ffffff" maxlength="7">
                            </div>
                        </div>
                        <div class="col-6 color-input-group">
                            <label>Footer Link Hover Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="footer_hover" value="{{ $data->footer_hover ?? '#007bff' }}">
                                <input type="text" value="{{ $data->footer_hover ?? '#007bff' }}" placeholder="#007bff" maxlength="7">
                            </div>
                        </div>
                          </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-save"></i> Save Settings
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