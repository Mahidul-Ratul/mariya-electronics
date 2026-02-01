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
    // 1. First, perform the column change alone
    Schema::table('installments', function (Blueprint $table) {
        $table->unsignedBigInteger('sale_id')->nullable()->change();
    });

    // 2. Add the first anchor column
    Schema::table('installments', function (Blueprint $table) {
        $table->string('installment_sale_number')->unique()->nullable()->after('id');
    });

    // 3. Add the customer anchor
    Schema::table('installments', function (Blueprint $table) {
        $table->string('customer_name')->nullable()->after('sale_id');
    });

    // 4. Add the rest of the fields in small, safe groups
    Schema::table('installments', function (Blueprint $table) {
        $table->string('customer_mobile')->nullable()->after('customer_name');
        $table->text('customer_address')->nullable()->after('customer_mobile');
    });

    Schema::table('installments', function (Blueprint $table) {
        $table->string('guarantor_name')->nullable()->after('customer_address');
        $table->string('guarantor_mobile')->nullable()->after('guarantor_name');
        $table->text('guarantor_address')->nullable()->after('guarantor_mobile');
    });

    Schema::table('installments', function (Blueprint $table) {
        $table->json('products_data')->nullable()->after('guarantor_address');
        $table->decimal('subtotal', 12, 2)->default(0)->after('products_data');
        $table->decimal('discount_amount', 12, 2)->default(0)->after('subtotal');
        $table->decimal('total_amount', 12, 2)->default(0)->after('discount_amount');
    });

    Schema::table('installments', function (Blueprint $table) {
        $table->decimal('down_payment', 12, 2)->default(0)->after('total_amount');
        $table->integer('total_installments')->default(0)->after('down_payment');
        $table->decimal('monthly_installment', 12, 2)->default(0)->after('total_installments');
        $table->date('sale_date')->nullable()->after('monthly_installment');
        $table->enum('payment_status', ['active', 'completed', 'defaulted', 'cancelled'])->default('active')->after('status');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('installments', function (Blueprint $table) {
            $table->dropColumn([
                'installment_sale_number',
                'customer_name',
                'customer_mobile',
                'customer_address',
                'guarantor_name',
                'guarantor_mobile',
                'guarantor_address',
                'products_data',
                'subtotal',
                'discount_amount',
                'total_amount',
                'down_payment',
                'total_installments',
                'monthly_installment',
                'sale_date',
                'payment_status'
            ]);
        });
    }
};
