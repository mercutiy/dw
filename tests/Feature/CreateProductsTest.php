<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateProductsTest extends TestCase
{
    public function testCreateNegative()
    {
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


    public function testCreatePositive()
    {
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
                            'sku' => 'C99900217',
                        ],
                        [
                            'image' => 'dw-petite-28-sterling-black-cat.png',
                            'name' => 'Classic Petite Sterling 28mm (Black)',
                            'sku' => 'C99900218',
                        ],
                    ],
                ],
                [
                    'collection' => 'classic-petite',
                    'size' => 32,
                    'products' => [
                        [
                            'image' => 'dw-petite-durham-rg-cat_1.png',
                            'name' => 'Classic Petite Durham Rose Gold 32mm (Black)',
                            'sku' => 'C99900166',
                        ],
                    ],
                ],
                [
                    'collection' => 'test-collection',
                    'size' => 16,
                    'products' => [
                        [
                            'image' => 'test image',
                            'name' => 'Test name',
                            'sku' => 'RT88400084',
                        ],
                    ],
                ],
            ]
        );
        $response->assertStatus(201);
    }

    /**
     * @depends testCreatePositive
     */
    public function testCreatePositive2()
    {
        $response = $this->getJson('/api/product/RT88400084');
        $response->assertStatus(200);
        $response->assertExactJson(
            [
                'data' => [
                    'sku' => 'RT88400084',
                    'image' => 'test image',
                    'name' => 'Test name',
                    'collection' => 'test-collection',
                    'size' => 16,
                ]
            ]
        );
    }

    /**
     * @depends testCreatePositive2
     */
    public function testUpdatePositive()
    {
        $response = $this->postJson(
            '/api/products',
            [
                [
                    'collection' => 'dapper',
                    'size' => 38,
                    'products' => [
                        [
                            'image' => 'dp38-sheffield-rg_3.png',
                            'name' => 'Dapper Sheffield 38mm Rose Gold',
                            'sku' => 'RT88400084'
                        ]
                    ]
                ]
            ]
        );
        $response->assertStatus(201);
    }

    /**
     * @depends testCreatePositive
     */
    public function testUpdatePositive2()
    {
        $response = $this->getJson('/api/product/RT88400084');
        $response->assertStatus(200);
        $response->assertExactJson(
            [
                'data' => [
                    'sku' => 'RT88400084',
                    'image' => 'dp38-sheffield-rg_3.png',
                    'name' => 'Dapper Sheffield 38mm Rose Gold',
                    'collection' => 'dapper',
                    'size' => 38,
                ]
            ]
        );
    }
}