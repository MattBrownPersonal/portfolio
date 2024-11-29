<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    public function test_health_check()
    {
        $response = $this->get('/health');
        $response->assertStatus(200);
    }
}
