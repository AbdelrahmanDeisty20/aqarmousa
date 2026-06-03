<?php

namespace Database\Factories;

use App\Models\Unit;
use App\Models\User;
use App\Models\Governorate;
use App\Models\UnitType;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    protected $model = Unit::class;

    public function definition(): array
    {
        return [
            'title_ar' => $this->faker->sentence(),
            'title_en' => $this->faker->sentence(),
            'description_ar' => $this->faker->paragraph(),
            'description_en' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 100000, 10000000),
            'discount' => null,
            'price_per_m2' => null,
            'offer_type' => $this->faker->randomElement(['sale', 'rent']),
            'area' => $this->faker->randomFloat(2, 50, 1000),
            'address_ar' => $this->faker->address(),
            'address_en' => $this->faker->address(),
            'length' => null,
            'width' => null,
            'category' => 'land',
            'status' => 'available',
            'owner_id' => User::factory(),
            'governorate_id' => Governorate::first() ?? Governorate::create(['name_ar' => 'القاهرة', 'name_en' => 'Cairo']),
            'unit_type_id' => UnitType::first() ?? UnitType::create(['name_ar' => 'أرض زراعية', 'name_en' => 'Agricultural Land']),
        ];
    }
}
