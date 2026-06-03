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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'id_photo')) {
                $table->string('id_photo')->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('users', 'governorate_id') && !Schema::hasColumn('users', 'city_id')) {
                $table->foreignId('governorate_id')->nullable()->after('id_photo')->constrained('governorates')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['governorate_id']);
            $table->dropColumn(['id_photo', 'governorate_id']);
        });
    }
};
