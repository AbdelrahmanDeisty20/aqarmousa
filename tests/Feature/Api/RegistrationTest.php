<?php

namespace Tests\Feature\Api;

use App\Models\Governorate;
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
        $governorate = Governorate::create(['name_ar' => 'القاهرة', 'name_en' => 'Cairo']);

        $response = $this->postJson('/api/register', [
            'name' => 'Test Seller',
            'email' => 'seller@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'phone' => '01012345678',
            'role' => 'seller',
            'address' => 'Test Address',
            'id_photo' => UploadedFile::fake()->image('id.jpg'),
            'governorate_id' => $governorate->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['requires_verification', 'user']]);

        $this->assertDatabaseHas('users', [
            'email' => 'seller@example.com',
            'role' => 'seller',
            'status' => 'pending',
            'governorate_id' => $governorate->id,
        ]);

        $user = User::where('email', 'seller@example.com')->first();
        $this->assertNotNull($user->id_photo);
        Storage::disk('public')->assertExists($user->id_photo);
    }

    public function test_buyer_registration_success_with_governorate()
    {
        $governorate = Governorate::create(['name_ar' => 'القاهرة', 'name_en' => 'Cairo']);

        $response = $this->postJson('/api/register', [
            'name' => 'Test Buyer',
            'email' => 'buyer@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'phone' => '01112345678',
            'role' => 'buyer',
            'governorate_id' => $governorate->id,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'buyer@example.com',
            'role' => 'buyer',
            'status' => 'approved',
            'governorate_id' => $governorate->id,
        ]);
    }
}
