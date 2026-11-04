<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    public function test_health_endpoint_returns_successful_json_response(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200);

        $response->assertExactJson([
            'status' => 'ok',
            'message' => 'Laravel Order API is running',
        ]);
    }
}