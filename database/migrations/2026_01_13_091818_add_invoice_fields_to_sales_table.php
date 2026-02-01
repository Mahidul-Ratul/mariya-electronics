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
    // Add columns one by one to satisfy TiDB's strict schema requirements
    Schema::table('sales', function (Blueprint $table) {
        $table->string('customer_name')->nullable()->after('customer_id');
    });

    Schema::table('sales', function (Blueprint $table) {
        $table->text('customer_address')->nullable()->after('customer_name');
    });

    Schema::table('sales', function (Blueprint $table) {
        $table->string('customer_mobile')->nullable()->after('customer_address');
    });

    Schema::table('sales', function (Blueprint $table) {
        $table->string('guarantor_name')->nullable()->after('customer_mobile');
    });

    Schema::table('sales', function (Blueprint $table) {
        $table->string('guarantor_mobile')->nullable()->after('guarantor_name');
    });

    Schema::table('sales', function (Blueprint $table) {
        $table->json('products_data')->nullable()->after('product_id');
    });

    Schema::table('sales', function (Blueprint $table) {
        $table->decimal('paid_amount', 10, 2)->default(0)->after('total_amount');
    });

    Schema::table('sales', function (Blueprint $table) {
        $table->integer('installment_months')->nullable()->after('paid_amount');
    });

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
