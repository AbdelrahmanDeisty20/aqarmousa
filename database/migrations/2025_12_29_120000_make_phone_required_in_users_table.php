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
        // Update existing null values to a placeholder logic
        // Using DB facade or Model to ensure data integrity before schema change
        \Illuminate\Support\Facades\DB::table('users')
            ->whereNull('phone')
            ->update(['phone' => '00000000000']);

        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->change();
        });
    }
};
