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
        // First expand the ENUM to include both old and new values so update statements don't throw truncation warnings
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE units MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'sold', 'rented', 'available', 'reserved') DEFAULT 'available'");
        }

        // Now update old values so they match the new enum
        DB::table('units')->where('status', 'approved')->update(['status' => 'available']);
        DB::table('units')->where('status', 'rented')->update(['status' => 'reserved']);

        // Finally shrink the ENUM to contain only the final desired values
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE units MODIFY COLUMN status ENUM('pending', 'available', 'rejected', 'sold', 'reserved') DEFAULT 'available'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE units MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'sold', 'rented') DEFAULT 'approved'");
    }
};
