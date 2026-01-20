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
        // Use raw SQL to make columns nullable
        DB::statement('ALTER TABLE sales MODIFY quantity INT NULL');
        DB::statement('ALTER TABLE sales MODIFY unit_price DECIMAL(12, 2) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to NOT NULL (set defaults first)
        DB::statement('UPDATE sales SET quantity = 1 WHERE quantity IS NULL');
        DB::statement('UPDATE sales SET unit_price = 0 WHERE unit_price IS NULL');
        DB::statement('ALTER TABLE sales MODIFY quantity INT NOT NULL');
        DB::statement('ALTER TABLE sales MODIFY unit_price DECIMAL(12, 2) NOT NULL');
    }
};
