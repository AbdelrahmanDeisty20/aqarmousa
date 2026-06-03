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
            if (Schema::hasColumn('units', 'rented_at') && !Schema::hasColumn('units', 'reserved_at')) {
                $table->renameColumn('rented_at', 'reserved_at');
            } elseif (!Schema::hasColumn('units', 'reserved_at')) {
                $table->timestamp('reserved_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            if (Schema::hasColumn('units', 'reserved_at')) {
                $table->renameColumn('reserved_at', 'rented_at');
            }
        });
    }
};
