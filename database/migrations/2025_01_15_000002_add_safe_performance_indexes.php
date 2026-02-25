<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration safely adds database indexes to improve query performance
     * Only adds indexes if the columns exist
     */
    public function up(): void
    {
        // Products table indexes
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'status')) {
                    try { $table->index('status', 'products_status_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('products', 'slug')) {
                    try { $table->index('slug', 'products_slug_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('products', 'category_id')) {
                    try { $table->index('category_id', 'products_category_id_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('products', 'subcategory_id')) {
                    try { $table->index('subcategory_id', 'products_subcategory_id_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('products', 'childcategory_id')) {
                    try { $table->index('childcategory_id', 'products_childcategory_id_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('products', 'brand_id')) {
                    try { $table->index('brand_id', 'products_brand_id_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('products', 'campaign_id')) {
                    try { $table->index('campaign_id', 'products_campaign_id_idx'); } catch (\Exception $e) {}
                }
            });
        }

        // Orders table indexes
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'customer_id')) {
                    try { $table->index('customer_id', 'orders_customer_id_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('orders', 'invoice_id')) {
                    try { $table->index('invoice_id', 'orders_invoice_id_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('orders', 'order_status')) {
                    try { $table->index('order_status', 'orders_order_status_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('orders', 'created_at')) {
                    try { $table->index('created_at', 'orders_created_at_idx'); } catch (\Exception $e) {}
                }
            });
        }

        // Categories table indexes
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (Schema::hasColumn('categories', 'status')) {
                    try { $table->index('status', 'categories_status_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('categories', 'slug')) {
                    try { $table->index('slug', 'categories_slug_idx'); } catch (\Exception $e) {}
                }
            });
        }

        // Subcategories table indexes
        if (Schema::hasTable('subcategories')) {
            Schema::table('subcategories', function (Blueprint $table) {
                if (Schema::hasColumn('subcategories', 'status')) {
                    try { $table->index('status', 'subcategories_status_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('subcategories', 'slug')) {
                    try { $table->index('slug', 'subcategories_slug_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('subcategories', 'category_id')) {
                    try { $table->index('category_id', 'subcategories_category_id_idx'); } catch (\Exception $e) {}
                }
            });
        }

        // Childcategories table indexes
        if (Schema::hasTable('childcategories')) {
            Schema::table('childcategories', function (Blueprint $table) {
                if (Schema::hasColumn('childcategories', 'status')) {
                    try { $table->index('status', 'childcategories_status_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('childcategories', 'slug')) {
                    try { $table->index('slug', 'childcategories_slug_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('childcategories', 'subcategory_id')) {
                    try { $table->index('subcategory_id', 'childcategories_subcategory_id_idx'); } catch (\Exception $e) {}
                }
            });
        }

        // Brands table indexes
        if (Schema::hasTable('brands')) {
            Schema::table('brands', function (Blueprint $table) {
                if (Schema::hasColumn('brands', 'status')) {
                    try { $table->index('status', 'brands_status_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('brands', 'slug')) {
                    try { $table->index('slug', 'brands_slug_idx'); } catch (\Exception $e) {}
                }
            });
        }

        // Customers table indexes
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table) {
                if (Schema::hasColumn('customers', 'phone')) {
                    try { $table->index('phone', 'customers_phone_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('customers', 'email')) {
                    try { $table->index('email', 'customers_email_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('customers', 'status')) {
                    try { $table->index('status', 'customers_status_idx'); } catch (\Exception $e) {}
                }
            });
        }

        // Campaigns table indexes
        if (Schema::hasTable('campaigns')) {
            Schema::table('campaigns', function (Blueprint $table) {
                if (Schema::hasColumn('campaigns', 'status')) {
                    try { $table->index('status', 'campaigns_status_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('campaigns', 'slug')) {
                    try { $table->index('slug', 'campaigns_slug_idx'); } catch (\Exception $e) {}
                }
            });
        }

        // Reviews table indexes
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                if (Schema::hasColumn('reviews', 'product_id')) {
                    try { $table->index('product_id', 'reviews_product_id_idx'); } catch (\Exception $e) {}
                }
                if (Schema::hasColumn('reviews', 'status')) {
                    try { $table->index('status', 'reviews_status_idx'); } catch (\Exception $e) {}
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes if they exist
        $indexes = [
            'products' => ['products_status_idx', 'products_slug_idx', 'products_category_id_idx', 
                          'products_subcategory_id_idx', 'products_childcategory_id_idx', 
                          'products_brand_id_idx', 'products_campaign_id_idx'],
            'orders' => ['orders_customer_id_idx', 'orders_invoice_id_idx', 'orders_order_status_idx', 'orders_created_at_idx'],
            'categories' => ['categories_status_idx', 'categories_slug_idx'],
            'subcategories' => ['subcategories_status_idx', 'subcategories_slug_idx', 'subcategories_category_id_idx'],
            'childcategories' => ['childcategories_status_idx', 'childcategories_slug_idx', 'childcategories_subcategory_id_idx'],
            'brands' => ['brands_status_idx', 'brands_slug_idx'],
            'customers' => ['customers_phone_idx', 'customers_email_idx', 'customers_status_idx'],
            'campaigns' => ['campaigns_status_idx', 'campaigns_slug_idx'],
            'reviews' => ['reviews_product_id_idx', 'reviews_status_idx'],
        ];

        foreach ($indexes as $table => $tableIndexes) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) use ($tableIndexes) {
                    foreach ($tableIndexes as $index) {
                        try {
                            $table->dropIndex($index);
                        } catch (\Exception $e) {
                            // Index doesn't exist, continue
                        }
                    }
                });
            }
        }
    }
};
