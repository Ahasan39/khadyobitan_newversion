<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeColor extends Model
{
    protected $fillable = [

        // existing
        'offer_bg','offer_text',
        'header_bg','header_text','category_dropdown_text',
        'body_bg','body_text',
        'section_title','section_subtitle',
        'category_bg','category_text','category_hover_bg',
        'product_bg','product_text','price_text',
        'order_btn_bg','order_btn_hover','order_btn_text',
        'cart_btn_bg','cart_btn_hover','cart_btn_text',
        'footer_bg','footer_text','footer_hover',
        'dashboard_sidebar_bg','dashboard_sidebar_text',
        'table_bg','table_text',

        // ================= MISSING (NEW) =================

        // Recent Product Card
        'recent_product_bg',
        'recent_product_text',
        'recent_order_btn_bg',
        'recent_order_btn_hover',
        'recent_order_btn_text',
        'recent_cart_btn_bg',
        'recent_cart_btn_hover',
        'recent_cart_btn_text',

        // Quick View
        'quick_bg',
        'quick_text',
        'quick_price',
        'quick_cart_btn_bg',
        'quick_cart_btn_text',
        'quick_buy_btn_bg',
        'quick_buy_btn_text',

        // Mobile Footer
        'mobile_footer_text',

        // Product Details
        'details_info_bg',
        'details_product_text',
        'details_price_text',
        'details_accordion_bg',
        'details_accordion_tab_bg',
        'details_accordion_tab_hover_bg',
        'details_accordion_text',

        // Quick Order
        'quick_order_bg',
        'quick_order_text',
        'quick_order_btn1_bg',
        'quick_order_btn2_bg',
        'quick_order_title_text',

        // Tabs
        'details_tab_bg',
        'details_tab_active_bg',
        'details_tab_text',

        // Cart Drawer
        'cart_drawer_bg',
        'cart_drawer_text',
        'checkout_btn_bg',
        'checkout_btn_text',
        'view_cart_btn_bg',
        'view_cart_btn_text',

        // Sidebars
        'main_sidebar_bg',
        'main_sidebar_text',
        'category_sidebar_bg',
        'category_sidebar_text',
        'filter_sidebar_bg',
        'filter_sidebar_text',
        'filter_sidebar_active',

        // Search Popup
        'search_popup_bg',
        'search_popup_text',
        'search_popup_price',

        // Single Checkout
        'checkout_header_bg',
        'checkout_header_text',
        'checkout_card_bg',
        'checkout_text',
        'checkout_order_btn_bg',
        'checkout_order_btn_text',
    ];
}
