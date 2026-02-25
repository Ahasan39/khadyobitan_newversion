<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('order_details', 'product_color')) {
                $table->string('product_color')->nullable()->after('qty');
            }
            if (!Schema::hasColumn('order_details', 'product_size')) {
                $table->string('product_size')->nullable()->after('product_color');
            }
            if (!Schema::hasColumn('order_details', 'product_type')) {
                $table->tinyInteger('product_type')->default(1)->comment('1=simple, 0=variable')->after('product_size');
            }
            if (!Schema::hasColumn('order_details', 'product_discount')) {
                $table->decimal('product_discount', 10, 2)->default(0)->after('product_type');
            }
            if (!Schema::hasColumn('order_details', 'weight')) {
                $table->decimal('weight', 10, 2)->nullable()->after('product_discount');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn(['product_color', 'product_size', 'product_type', 'product_discount', 'weight']);
        });
    }
};
