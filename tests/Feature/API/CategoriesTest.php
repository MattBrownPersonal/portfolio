<?php

namespace Tests\Feature\API;

use App\Models\Memorialisations;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testThatAllCategoriesCanBeRead()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $this->withoutExceptionHandling();
        $category = Memorialisations::factory()->create();

        $response = $this->getJson('/api/memorialisations');
        $response->assertOk();

        $body = $response->decodeResponseJson();

        // There is one category
        $this->assertEquals(count($body), 1);
        $this->assertEquals($body[0]['id'], $category->id);
        $this->assertEquals($body[0]['name'], $category->name);
        $this->assertEquals($body[0]['created_at'], $category->created_at->toISOString());
        $this->assertEquals($body[0]['updated_at'], $category->updated_at->toISOString());
    }

    /** @test */
    public function testThatCategoriesCanBeAdded()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $this->withoutExceptionHandling();

        $data = $this->validData();

        $response = $this->postJson('/api/memorialisations', $data);
        $response->assertCreated();

        $body = $response->decodeResponseJson();
        $this->assertGreaterThan(0, $body['id']);

        $actual = Memorialisations::find($body['id']);
        $this->assertEquals($actual->name, $data['name']);

        $this->assertEquals($body['id'], $actual->id);
        $this->assertEquals($body['name'], $actual->name);
        $this->assertEquals($body['created_at'], $actual->created_at->toISOString());
        $this->assertEquals($body['updated_at'], $actual->updated_at->toISOString());
    }

    /** @test */
    public function testThatCategoriesMustPassValidation()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);
        $this->withoutExceptionHandling();

        $category = Memorialisations::factory()->create();

        $data = [
            'name' => $category->name,
            'memorialisationId' => 1,
            'siteId' => 2,
        ];

        $response = $this->postJson('/api/memorialisations', $data);
        $this->assertEquals(422, $response->getStatusCode());

        $body = $response->decodeResponseJson();

        $this->assertEquals(['A Category called '.$category->name.' already exists'], $body['errors']);
    }

    public function testThatCategoriesCanBeDeleted()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $category = Memorialisations::factory()->create();

        $response = $this->deleteJson('/api/memorialisations/'.$category->id);
        $response->assertOk();
    }

    private function validData()
    {
        return [
            'name' => 'Fake',
            'memorialisationId' => 1,
            'siteId' => 2,
        ];
    }

    private function invalidData()
    {
        return [
            'name' => 999,
            'memorialisationId' => 1,
            'siteId' => 2,
        ];
    }
}
