<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowCollectionsTest extends TestCase
{
    public function testShowCollections() {
        $response = $this->getJson('/api/collections');
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    [
                        'id',
                    ]
                ]
            ]
        );
    }

    public function testShowCollectionProductsNegative() {
        $response = $this->getJson('/api/collection/DO_NOT_EXIST/products');
        $response->assertStatus(404);
    }

    public function testShowCollectionProductsPositive() {
        $response = $this->getJson('/api/collection/classic-petite/products');
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    [
                        'sku',
                        'name',
                        'image',
                    ]
                ]
            ]
        );
    }
}
