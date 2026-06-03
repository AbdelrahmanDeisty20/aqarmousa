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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->text('description_ar');
            $table->text('description_en')->nullable();
            $table->decimal('price', 15, 2);
            $table->decimal('discount', 15, 2)->nullable(); // الخصم
            $table->decimal('price_per_m2', 10, 2)->nullable();
            $table->enum('offer_type', ['sale', 'rent']);
            $table->decimal('area', 8, 2);
            $table->decimal('length', 8, 2)->nullable(); // الطول
            $table->decimal('width', 8, 2)->nullable();  // العرض
            $table->enum('category', ['land', 'property'])->default('land'); // تصنيف الوحدة: أرض أو عقار
            $table->integer('rooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('garages')->nullable();
            $table->integer('build_year')->nullable();
            $table->decimal('land_area', 10, 2)->nullable();
            $table->decimal('internal_area', 10, 2)->nullable();
            $table->enum('status', ['pending', 'available', 'rejected', 'sold', 'reserved'])->default('available');
            $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('governorate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_type_id')->constrained();
            $table->foreignId('compound_id')->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('developer_id')->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
