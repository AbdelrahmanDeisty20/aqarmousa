<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->string('development_status')->nullable()->change();
        });

        // Update existing rent units to have null development_status
        DB::table('units')
            ->where('offer_type', 'rent')
            ->update(['development_status' => null]);
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->string('development_status')->nullable(false)->change();
        });
    }
};
