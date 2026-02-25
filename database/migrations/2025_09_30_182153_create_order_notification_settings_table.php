<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_notification_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('send_to_whatsapp')->default(0);
            $table->boolean('send_to_email')->default(1);
            $table->timestamps();
        });

        // ডিফল্ট একটাই রো থাকবে
        DB::table('order_notification_settings')->insert([
            'send_to_whatsapp' => 0,
            'send_to_email' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('order_notification_settings');
    }
};
