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
        $table->decimal('shipping_charge_dhaka', 10, 2)->nullable();
        $table->decimal('shipping_charge_outside_dhaka', 10, 2)->nullable();
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn(['shipping_charge_dhaka', 'shipping_charge_outside_dhaka']);
    });
}

};
