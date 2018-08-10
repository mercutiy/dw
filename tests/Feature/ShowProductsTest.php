<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowProductsTest extends TestCase
{
    public function testShowProducts() {
        $response = $this->getJson('/api/v1/products');
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

    public function testShowProductsSize() {
        $response = $this->getJson('/api/v1/products?size=38&fields=sku');
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    [
                        'sku',
                    ]
                ]
            ]
        );
    }


}
