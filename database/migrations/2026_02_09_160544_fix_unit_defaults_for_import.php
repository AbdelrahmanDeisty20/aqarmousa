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
            // Change default status to 'approved'
            $table->enum('status', ['pending', 'approved', 'rejected', 'sold', 'rented'])
                ->default('approved')
                ->change();

            // Change default is_visible to 0 (hidden)
            $table->boolean('is_visible')
                ->default(false)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'sold', 'rented'])
                ->default('pending')
                ->change();

            $table->boolean('is_visible')
                ->default(true)
                ->change();
        });
    }
};
