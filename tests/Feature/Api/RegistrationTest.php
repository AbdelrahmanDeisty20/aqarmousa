<?php

namespace Tests\Feature\Api;

use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_registration_requires_id_photo()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test Seller',
            'email' => 'seller@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'phone' => '01012345678',
            'role' => 'seller',
            'address' => 'Test Address',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id_photo']);
    }

    public function test_seller_registration_success_with_id_photo()
    {
        Storage::fake('public');
        $city = City::create(['name_ar' => 'القاهرة', 'name_en' => 'Cairo']);

        $response = $this->postJson('/api/register', [
            'name' => 'Test Seller',
            'email' => 'seller@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'phone' => '01012345678',
            'role' => 'seller',
            'address' => 'Test Address',
            'id_photo' => UploadedFile::fake()->image('id.jpg'),
            'city_id' => $city->id,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'user']);

        $this->assertDatabaseHas('users', [
            'email' => 'seller@example.com',
            'role' => 'seller',
            'status' => 'pending',
            'city_id' => $city->id,
        ]);

        $user = User::where('email', 'seller@example.com')->first();
        $this->assertNotNull($user->id_photo);
        Storage::disk('public')->assertExists($user->id_photo);
    }

    public function test_buyer_registration_success_with_city()
    {
        $city = City::create(['name_ar' => 'القاهرة', 'name_en' => 'Cairo']);

        $response = $this->postJson('/api/register', [
            'name' => 'Test Buyer',
            'email' => 'buyer@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'phone' => '01112345678',
            'role' => 'buyer',
            'city_id' => $city->id,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => 'buyer@example.com',
            'role' => 'buyer',
            'status' => 'approved',
            'city_id' => $city->id,
        ]);
    }
}
