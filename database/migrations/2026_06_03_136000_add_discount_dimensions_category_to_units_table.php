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
            if (!Schema::hasColumn('units', 'discount')) {
                $table->decimal('discount', 15, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('units', 'length')) {
                $table->decimal('length', 8, 2)->nullable()->after('area');
            }
            if (!Schema::hasColumn('units', 'width')) {
                $table->decimal('width', 8, 2)->nullable()->after('length');
            }
            if (!Schema::hasColumn('units', 'category')) {
                $table->enum('category', ['land', 'property'])->default('land')->after('width');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['discount', 'length', 'width', 'category']);
        });
    }
};
