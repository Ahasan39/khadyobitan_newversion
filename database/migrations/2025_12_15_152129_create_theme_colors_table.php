<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('theme_colors', function (Blueprint $table) {
    $table->id();

    // Offer Marquee
    $table->string('offer_bg')->nullable();
    $table->string('offer_text')->nullable();

    // Header
    $table->string('header_bg')->nullable();
    $table->string('header_text')->nullable();
    $table->string('category_dropdown_text')->nullable();

    // Body
    $table->string('body_bg')->nullable();
    $table->string('body_text')->nullable();

    // Section
    $table->string('section_title')->nullable();
    $table->string('section_subtitle')->nullable();

    // Category Section
    $table->string('category_bg')->nullable();
    $table->string('category_text')->nullable();
    $table->string('category_hover_bg')->nullable();

    // Product Card
    $table->string('product_bg')->nullable();
    $table->string('product_text')->nullable();
    $table->string('price_text')->nullable();
    $table->string('order_btn_bg')->nullable();
    $table->string('order_btn_hover')->nullable();
    $table->string('order_btn_text')->nullable();
    $table->string('cart_btn_bg')->nullable();
    $table->string('cart_btn_hover')->nullable();
    $table->string('cart_btn_text')->nullable();

    // Footer
    $table->string('footer_bg')->nullable();
    $table->string('footer_text')->nullable();
    $table->string('footer_hover')->nullable();

    // Dashboard
    $table->string('dashboard_sidebar_bg')->nullable();
    $table->string('dashboard_sidebar_text')->nullable();
    $table->string('table_bg')->nullable();
    $table->string('table_text')->nullable();

    $table->timestamps();
});
}

public function down()
{
    Schema::dropIfExists('theme_color');
}

};
