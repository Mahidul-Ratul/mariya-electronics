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
            // Make payment-specific fields nullable for main installment sale records
            $table->integer('installment_number')->nullable()->change();
            $table->decimal('amount', 12, 2)->nullable()->change();
            $table->date('due_date')->nullable()->change();
            $table->enum('status', ['pending', 'paid', 'overdue', 'partial'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('installments', function (Blueprint $table) {
            $table->integer('installment_number')->nullable(false)->change();
            $table->decimal('amount', 12, 2)->nullable(false)->change();
            $table->date('due_date')->nullable(false)->change();
            $table->enum('status', ['pending', 'paid', 'overdue', 'partial'])->default('pending')->nullable(false)->change();
        });
    }
};
