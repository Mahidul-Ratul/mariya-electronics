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
    // 1. Tell Laravel to treat ENUMs as strings to prevent the "Unknown column type" error
    Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

    // 2. Change numeric and date fields one by one
    Schema::table('installments', function (Blueprint $table) {
        $table->integer('installment_number')->nullable()->change();
    });

    Schema::table('installments', function (Blueprint $table) {
        $table->decimal('amount', 12, 2)->nullable()->change();
    });

    Schema::table('installments', function (Blueprint $table) {
        $table->date('due_date')->nullable()->change();
    });

    // 3. Use Raw SQL to change the ENUM status to nullable
    // This bypasses the Doctrine driver entirely and works perfectly in TiDB
    DB::statement("ALTER TABLE installments MODIFY status ENUM('pending', 'paid', 'overdue', 'partial') NULL");
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
