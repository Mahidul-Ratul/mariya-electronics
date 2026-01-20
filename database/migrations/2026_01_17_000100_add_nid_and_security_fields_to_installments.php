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
        Schema::table('installments', function (Blueprint $table) {
            // Customer NID information
            $table->string('customer_nid')->nullable()->after('customer_address');
            $table->string('customer_nid_image')->nullable()->after('customer_nid');
            
            // Customer's wife NID information
            $table->string('customer_wife_name')->nullable()->after('customer_nid_image');
            $table->string('customer_wife_nid')->nullable()->after('customer_wife_name');
            $table->string('customer_wife_nid_image')->nullable()->after('customer_wife_nid');
            
            // Guarantor security information
            $table->string('guarantor_nid')->nullable()->after('guarantor_address');
            $table->string('guarantor_security_info')->nullable()->after('guarantor_nid');
            $table->string('guarantor_security_image')->nullable()->after('guarantor_security_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('installments', function (Blueprint $table) {
            $table->dropColumn([
                'customer_nid',
                'customer_nid_image',
                'customer_wife_name',
                'customer_wife_nid',
                'customer_wife_nid_image',
                'guarantor_nid',
                'guarantor_security_info',
                'guarantor_security_image'
            ]);
        });
    }
};
