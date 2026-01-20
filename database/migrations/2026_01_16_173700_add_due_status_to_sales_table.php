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
        // Modify the status enum to include 'due'
        DB::statement("ALTER TABLE `sales` MODIFY COLUMN `status` ENUM('pending', 'completed', 'cancelled', 'due') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE `sales` MODIFY COLUMN `status` ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
