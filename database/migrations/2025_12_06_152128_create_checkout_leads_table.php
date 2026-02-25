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
    Schema::create('checkout_leads', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('phone')->nullable();
        $table->text('address')->nullable();
        $table->string('ip')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('checkout_leads');
}

};
