<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Add JSON field for multiple products if it doesn't exist
            if (!Schema::hasColumn('sales', 'products_data')) {
                $table->json('products_data')->nullable()->after('product_id');
            }
            
            // Add customer info fields if they don't exist
            if (!Schema::hasColumn('sales', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('customer_id');
            }
            if (!Schema::hasColumn('sales', 'customer_mobile')) {
                $table->string('customer_mobile')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('sales', 'customer_address')) {
                $table->text('customer_address')->nullable()->after('customer_mobile');
            }
            
            // Add paid_amount field if it doesn't exist
            if (!Schema::hasColumn('sales', 'paid_amount')) {
                $table->decimal('paid_amount', 12, 2)->default(0)->after('total_amount');
            }
        });
        
        // Make foreign keys nullable using raw SQL (if needed)
        DB::statement('ALTER TABLE sales MODIFY customer_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE sales MODIFY product_id BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'customer_mobile',
                'customer_address',
                'products_data',
                'paid_amount'
            ]);
            
            // Note: Cannot easily revert nullable changes in down migration
            // You may need to recreate the table if rolling back
        });
    }
};
