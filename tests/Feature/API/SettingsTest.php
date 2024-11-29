<?php

namespace Tests\Feature\API;

use App\Models\Settings;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testThatSettingsCanBeUpdated()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->withoutExceptionHandling();
        $setting = Settings::factory()->create();

        $response = $this->putJson('/api/settings/'.$setting->id, $this->validData());
        $response->assertOk();
    }

    /** @test */
    public function testThatSettingsCanBeUpdatedFailsValidation()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->withoutExceptionHandling();
        $setting = Settings::factory()->create();

        $response = $this->putJson('/api/settings/'.$setting->id, $this->invalidData());
        $response->assertStatus(422);
    }

    private function validData()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        return [
            'name' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur vehicula tortor mi, eget consequat nisl imperdiet nec. Curabitur efficitur blandit sem. Mauris vel pulvinar urna, et pretium orci. Quisque euismod consectetur porttitor.',
        ];
    }

    private function invalidData()
    {
        return [
            'name' => null,
        ];
    }
}
