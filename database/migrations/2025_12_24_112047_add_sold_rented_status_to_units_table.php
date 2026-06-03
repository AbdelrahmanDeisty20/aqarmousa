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
        Schema::table('units', function (Blueprint $table) {
            // Modify the status enum
            if (DB::getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE units MODIFY COLUMN status ENUM('pending', 'available', 'rejected', 'sold', 'reserved') DEFAULT 'available'");
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            // Rollback to original status enum values
            DB::statement("ALTER TABLE units MODIFY COLUMN status ENUM('pending', 'available', 'rejected') DEFAULT 'available'");
        });
    }
};
