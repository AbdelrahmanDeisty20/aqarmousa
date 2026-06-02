<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerificationActionRestrictionTest extends TestCase
{
    use RefreshDatabase;

    public function test_unverified_user_cannot_toggle_favorite()
    {
        $user = User::factory()->unverified()->create();
        $unit = Unit::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/favorites/toggle', [
            'unit_id' => $unit->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_unverified_user_cannot_store_review()
    {
        $user = User::factory()->unverified()->create();
        $unit = Unit::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/reviews', [
            'unit_id' => $unit->id,
            'rating' => 5,
            'comment' => 'Great unit!',
        ]);

        $response->assertStatus(403);
    }

    public function test_unverified_user_cannot_store_testimonial()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/testimonials', [
            'content' => 'Great service!',
        ]);

        $response->assertStatus(403);
    }

    public function test_verified_user_can_access_restricted_routes()
    {
        $user = User::factory()->create(); // verified by default usually
        $unit = Unit::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/favorites/toggle', [
            'unit_id' => $unit->id,
        ]);

        $response->assertStatus(200);
    }
}
