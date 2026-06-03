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
            if (!Schema::hasColumn('units', 'rooms')) {
                $table->integer('rooms')->nullable()->after('category');
            }
            if (!Schema::hasColumn('units', 'bathrooms')) {
                $table->integer('bathrooms')->nullable()->after('rooms');
            }
            if (!Schema::hasColumn('units', 'garages')) {
                $table->integer('garages')->nullable()->after('bathrooms');
            }
            if (!Schema::hasColumn('units', 'build_year')) {
                $table->integer('build_year')->nullable()->after('garages');
            }
            if (!Schema::hasColumn('units', 'land_area')) {
                $table->decimal('land_area', 10, 2)->nullable()->after('build_year');
            }
            if (!Schema::hasColumn('units', 'internal_area')) {
                $table->decimal('internal_area', 10, 2)->nullable()->after('land_area');
            }
            if (!Schema::hasColumn('units', 'development_status')) {
                $table->string('development_status')->nullable()->after('internal_area');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['rooms', 'bathrooms', 'garages', 'build_year', 'land_area', 'internal_area', 'development_status']);
        });
    }
};
