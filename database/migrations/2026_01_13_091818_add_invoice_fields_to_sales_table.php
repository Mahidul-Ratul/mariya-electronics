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
        Schema::table('sales', function (Blueprint $table) {
            // Customer information fields
            $table->string('customer_name')->nullable()->after('customer_id');
            $table->text('customer_address')->nullable()->after('customer_name');
            $table->string('customer_mobile')->nullable()->after('customer_address');
            
            // Guarantor information
            $table->string('guarantor_name')->nullable()->after('customer_mobile');
            $table->string('guarantor_mobile')->nullable()->after('guarantor_name');
            
            // Multi-product support (storing as JSON)
            $table->json('products_data')->nullable()->after('product_id');
            
            // Installment payment details
            $table->decimal('paid_amount', 10, 2)->default(0)->after('total_amount');
            $table->integer('installment_months')->nullable()->after('paid_amount');
            
            // Make original product_id nullable since we now support multiple products
            $table->unsignedBigInteger('product_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'customer_address', 
                'customer_mobile',
                'guarantor_name',
                'guarantor_mobile',
                'products_data',
                'paid_amount',
                'installment_months'
            ]);
            
            // Restore product_id as required
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
        });
    }
};
