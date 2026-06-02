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
        Schema::create('maintenance_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_service_id')->constrained('maintenance_services')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('phone');
            $table->string('address');
            $table->text('message')->nullable();
            $table->string('status')->default('pending'); // pending, contacted, done
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_bookings');
    }
};
