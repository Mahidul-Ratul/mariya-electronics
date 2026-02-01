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
    // Block 1: Add the first anchor column alone
    Schema::table('sales', function (Blueprint $table) {
        $table->string('customer_name')->nullable()->after('customer_id');
    });

    // Block 2: Add the rest of the new columns
    Schema::table('sales', function (Blueprint $table) {
        $table->text('customer_address')->nullable()->after('customer_name');
        $table->string('customer_mobile')->nullable()->after('customer_address');
        $table->string('guarantor_name')->nullable()->after('customer_mobile');
        $table->string('guarantor_mobile')->nullable()->after('guarantor_name');
        $table->json('products_data')->nullable()->after('product_id');
        $table->decimal('paid_amount', 10, 2)->default(0)->after('total_amount');
        $table->integer('installment_months')->nullable()->after('paid_amount');
    });

    // Block 3: Modify the existing column (Separated to prevent TiDB conflict)
    Schema::table('sales', function (Blueprint $table) {
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
