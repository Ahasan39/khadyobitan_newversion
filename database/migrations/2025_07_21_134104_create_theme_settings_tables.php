<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('theme_settings', function (Blueprint $table) {
            $table->id();
            $table->string('header_color', 20)->nullable();
            $table->string('footer_color', 20)->nullable();
            $table->string('text_color', 20)->nullable();
            $table->string('button_color', 20)->nullable();
            $table->string('add_to_cart_color', 20)->nullable();
            $table->string('price_color', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('theme_settings');
    }
};
