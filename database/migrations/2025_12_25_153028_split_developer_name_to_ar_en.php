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
        Schema::table('developers', function (Blueprint $table) {
            $table->string('name_ar')->after('id')->nullable();
            $table->string('name_en')->after('name_ar')->nullable();
        });

        // Copy existing names to new columns
        \DB::table('developers')->update([
            'name_ar' => \DB::raw('name'),
            'name_en' => \DB::raw('name'),
        ]);

        Schema::table('developers', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('developers', function (Blueprint $table) {
            $table->string('name')->after('id')->nullable();
        });

        \DB::table('developers')->update([
            'name' => \DB::raw('name_ar'),
        ]);

        Schema::table('developers', function (Blueprint $table) {
            $table->dropColumn(['name_ar', 'name_en']);
        });
    }
};
