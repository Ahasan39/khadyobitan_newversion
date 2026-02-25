<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_notification_settings', function (Blueprint $table) {
            $table->string('whatsapp_number')->nullable()->after('send_to_whatsapp');
            $table->string('email_address')->nullable()->after('send_to_email');
        });
    }

    public function down(): void
    {
        Schema::table('order_notification_settings', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_number', 'email_address']);
        });
    }
};
