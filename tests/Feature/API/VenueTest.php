<?php

namespace Tests\Feature\API;

use App\Models\Site;
use App\Models\User;
use Database\Seeders\SiteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class VenueTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testThatAllSitesCanBeRead()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->withoutExceptionHandling();

        $this->seed(SiteSeeder::class);

        $response = $this->getJson('/api/sites');

        $response->assertOk();
    }

    /** @test */
    public function testThatASiteFailsToAddWhenValidationFails()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->withoutExceptionHandling();

        $response = $this->postJson('/api/sites', $this->invalidData());
        $response->assertStatus(422);
    }

    /** @test */
    public function testThatASiteCanBeUpdated()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->withoutExceptionHandling();

        $site = Site::factory()->create();

        $response = $this->putJson('/api/sites/'.$site->id, $this->validData());
        $response->assertOk();
    }

    /** @test */
    public function testThatASiteDoesntUpdateWhenValidationFails()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->withoutExceptionHandling();

        $site = Site::factory()->create();

        $response = $this->putJson('/api/sites/'.$site->id, $this->invalidData());
        $response->assertStatus(422);
    }

    /** @test */
    public function testThatASiteDoesntUpdateWhenItCantBeFoundById()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->withoutExceptionHandling();

        Site::factory()->create();

        $response = $this->putJson('/api/sites/'.'abc', $this->invalidData());
        $response->assertStatus(422);
    }

    /** @test */
    public function testThatASiteCanBeDeleted()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $site = Site::factory()->create();

        $response = $this->deleteJson('/api/sites/'.$site->id);
        $response->assertOk();
    }

    /** @test */
    public function testThatASiteIsntDeletedWhenIdIsntFound()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        Site::factory()->create();

        $response = $this->deleteJson('/api/sites/'.'abc');
        $response->assertStatus(404);
    }

    private function validData()
    {
        return [
            'slug' => 'test-slug-2',
            'siteName' => 'FakeTest',
            'siteId' => 'ABC123',
            'accountType' => 'obitus',
            'siteType' => 'Crematorium',
            'operatorType' => 'Council',
            'usesCategories' => 1,
        ];
    }

    private function invalidData()
    {
        return [
            'siteName' => 1,
            'siteId' => 'ABC123',
            'accountType' => 'obitus',
            'siteType' => 'Crematorium',
            'operatorType' => 'Council',
            'usesCategories' => 1,
            'slug' => 'test-slug',
        ];
    }
}
