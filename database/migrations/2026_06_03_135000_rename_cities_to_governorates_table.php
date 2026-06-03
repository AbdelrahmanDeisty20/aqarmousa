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
        if (Schema::hasTable('cities')) {
            Schema::rename('cities', 'governorates');
        }

        if (Schema::hasTable('compounds') && Schema::hasColumn('compounds', 'city_id')) {
            Schema::table('compounds', function (Blueprint $table) {
                $table->renameColumn('city_id', 'governorate_id');
            });
        }

        if (Schema::hasTable('units') && Schema::hasColumn('units', 'city_id')) {
            Schema::table('units', function (Blueprint $table) {
                $table->renameColumn('city_id', 'governorate_id');
            });
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'city_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('city_id', 'governorate_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'governorate_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('governorate_id', 'city_id');
            });
        }

        if (Schema::hasTable('units') && Schema::hasColumn('units', 'governorate_id')) {
            Schema::table('units', function (Blueprint $table) {
                $table->renameColumn('governorate_id', 'city_id');
            });
        }

        if (Schema::hasTable('compounds') && Schema::hasColumn('compounds', 'governorate_id')) {
            Schema::table('compounds', function (Blueprint $table) {
                $table->renameColumn('governorate_id', 'city_id');
            });
        }

        if (Schema::hasTable('governorates')) {
            Schema::rename('governorates', 'cities');
        }
    }
};
