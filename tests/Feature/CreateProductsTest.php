<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateProductsTest extends TestCase
{
    public function testCreateNegative() {
        $response = $this->postJson(
            '/api/products',
            [
                [
                    'collection' => 'classic-petite',
                    'size' => 28,
                    'products' => [
                        [
                            'image' => 'dw-petite-28-melrose-black-cat.png',
                            'name' => '',
                            'sku' => 'C99900217'
                        ]
                    ]
                ]
            ]
        );
        $response->assertStatus(400);
    }

    public function testCreatePositive() {
        $response = $this->postJson(
            '/api/products',
            [
                [
                    'collection' => 'classic-petite',
                    'size' => 28,
                    'products' => [
                        [
                            'image' => 'dw-petite-28-melrose-black-cat.png',
                            'name' => 'Classic Petite Melrose 28mm (Black)',
                            'sku' => 'C99900217'
                        ]
                    ]
                ]
            ]
        );
        $response->assertStatus(201);
    }
}
