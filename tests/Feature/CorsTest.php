<?php

namespace Tests\Feature;

use Tests\TestCase;

class CorsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_api_allows_cors_from_www_subdomain(): void
    {
        $response = $this->withHeaders([
            'Origin' => 'https://www.propix8.com',
        ])->get('/up');

        $response->assertStatus(200);
        $response->assertHeader('Access-Control-Allow-Origin', 'https://www.propix8.com');
    }

    public function test_api_allows_cors_from_apex_domain(): void
    {
        $response = $this->withHeaders([
            'Origin' => 'https://propix8.com',
        ])->get('/up');

        $response->assertStatus(200);
        $response->assertHeader('Access-Control-Allow-Origin', 'https://propix8.com');
    }
}
