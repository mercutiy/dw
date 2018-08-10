<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowProductTest extends TestCase
{

    public function testShowProductNegative() {
        $response = $this->getJson('/api/v1/product/DO_NOT_EXIST');
        $response->assertStatus(404);
    }

    public function testShowProductPositive() {
        $response = $this->getJson('/api/v1/product/C99900217');
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'sku',
                    'name',
                    'image',
                    'collection',
                    'size',
                ]
            ]
        );
    }
}
