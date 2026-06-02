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
        Schema::table('units', function (Blueprint $table) {
            // Modify the status enum to include 'sold' and 'rented'
            DB::statement("ALTER TABLE units MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'sold', 'rented') DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            // Rollback to original status enum values
            DB::statement("ALTER TABLE units MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
        });
    }
};
