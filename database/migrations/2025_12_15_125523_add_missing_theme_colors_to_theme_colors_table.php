<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('theme_colors', function (Blueprint $table) {

            // Recent Product
            $table->string('recent_product_bg')->nullable();
            $table->string('recent_product_text')->nullable();
            $table->string('recent_order_btn_bg')->nullable();
            $table->string('recent_order_btn_hover')->nullable();
            $table->string('recent_order_btn_text')->nullable();
            $table->string('recent_cart_btn_bg')->nullable();
            $table->string('recent_cart_btn_hover')->nullable();
            $table->string('recent_cart_btn_text')->nullable();

            // Quick View
            $table->string('quick_bg')->nullable();
            $table->string('quick_text')->nullable();
            $table->string('quick_price')->nullable();
            $table->string('quick_cart_btn_bg')->nullable();
            $table->string('quick_cart_btn_text')->nullable();
            $table->string('quick_buy_btn_bg')->nullable();
            $table->string('quick_buy_btn_text')->nullable();

            // Mobile Footer
            $table->string('mobile_footer_text')->nullable();

            // Product Details
            $table->string('details_info_bg')->nullable();
            $table->string('details_product_text')->nullable();
            $table->string('details_price_text')->nullable();
            $table->string('details_accordion_bg')->nullable();
            $table->string('details_accordion_tab_bg')->nullable();
            $table->string('details_accordion_tab_hover_bg')->nullable();
            $table->string('details_accordion_text')->nullable();

            // Quick Order
            $table->string('quick_order_bg')->nullable();
            $table->string('quick_order_text')->nullable();
            $table->string('quick_order_btn1_bg')->nullable();
            $table->string('quick_order_btn2_bg')->nullable();
            $table->string('quick_order_title_text')->nullable();

            // Tabs
            $table->string('details_tab_bg')->nullable();
            $table->string('details_tab_active_bg')->nullable();
            $table->string('details_tab_text')->nullable();

            // Cart Drawer
            $table->string('cart_drawer_bg')->nullable();
            $table->string('cart_drawer_text')->nullable();
            $table->string('checkout_btn_bg')->nullable();
            $table->string('checkout_btn_text')->nullable();
            $table->string('view_cart_btn_bg')->nullable();
          $table->string('view_cart_btn_text', 30)->nullable();


            // Sidebars
            $table->string('main_sidebar_bg')->nullable();
            $table->string('main_sidebar_text')->nullable();
            $table->string('category_sidebar_bg')->nullable();
            $table->string('category_sidebar_text')->nullable();
            $table->string('filter_sidebar_bg')->nullable();
            $table->string('filter_sidebar_text')->nullable();
            $table->string('filter_sidebar_active')->nullable();

            // Search Popup
            $table->string('search_popup_bg')->nullable();
            $table->string('search_popup_text')->nullable();
            $table->string('search_popup_price')->nullable();

            // Single Checkout
            $table->string('checkout_header_bg')->nullable();
            $table->string('checkout_header_text')->nullable();
            $table->string('checkout_card_bg')->nullable();
            $table->string('checkout_text')->nullable();
            $table->string('checkout_order_btn_bg')->nullable();
            $table->string('checkout_order_btn_text')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('theme_colors', function (Blueprint $table) {
            
        });
    }
};

