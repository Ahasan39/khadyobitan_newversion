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
    Schema::table('products', function (Blueprint $table) {
        $table->string('shipping_title_dhaka')->nullable();
        $table->decimal('shipping_amount_dhaka', 10, 2)->nullable();
        $table->string('shipping_title_outside_dhaka')->nullable();
        $table->decimal('shipping_amount_outside_dhaka', 10, 2)->nullable();
    });
}
    
    public function down()
    {
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn([
            'shipping_title_dhaka',
            'shipping_amount_dhaka',
            'shipping_title_outside_dhaka',
            'shipping_amount_outside_dhaka',
        ]);
    });
}
};
