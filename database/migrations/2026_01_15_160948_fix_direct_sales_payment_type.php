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
        // Fix direct sales that might have incorrect payment types
        // Direct sales (customer_id = null) should always be cash type and completed status
        DB::table('sales')
            ->whereNull('customer_id')
            ->where('payment_type', '!=', 'cash')
            ->update([
                'payment_type' => 'cash',
                'status' => 'completed'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration only fixes data, no need to reverse
    }
};
