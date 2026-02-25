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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('new_arrival')->default(0)->after('topsale');
            $table->boolean('top_rated')->default(0)->after('new_arrival');
            $table->boolean('top_selling')->default(0)->after('top_rated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['new_arrival', 'top_rated', 'top_selling']);
        });
    }
};