<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('checkout_leads', function (Blueprint $table) {
            $table->enum('status', ['pending', 'contacted', 'converted', 'abandoned'])->default('pending')->after('ip');
            $table->text('admin_note')->nullable()->after('status');
            $table->timestamp('contacted_at')->nullable()->after('admin_note');
            $table->unsignedBigInteger('contacted_by')->nullable()->after('contacted_at');
            $table->json('cart_data')->nullable()->after('contacted_by');
            $table->decimal('total_amount', 10, 2)->nullable()->after('cart_data');
            
            $table->foreign('contacted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checkout_leads', function (Blueprint $table) {
            $table->dropForeign(['contacted_by']);
            $table->dropColumn(['status', 'admin_note', 'contacted_at', 'contacted_by', 'cart_data', 'total_amount']);
        });
    }
};
