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
        Schema::table('unit_media', function (Blueprint $table) {
            $table->string('processed_url')->nullable()->after('url');
            $table->enum('processing_status', ['pending', 'processing', 'completed', 'failed'])->default('pending')->after('processed_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unit_media', function (Blueprint $table) {
            //
        });
    }
};
