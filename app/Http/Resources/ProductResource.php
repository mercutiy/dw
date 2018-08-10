<?php

namespace App\Http\Resources;

use App\Model\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    private static $fields = [
        Product::FIELD_SKU,
        Product::FIELD_NAME,
        Product::FIELD_IMAGE,
        Product::FIELD_COLLECTION,
        Product::FIELD_SIZE,

    ];

    private static $showFields = [
        Product::FIELD_SKU,
        Product::FIELD_NAME,
        Product::FIELD_IMAGE,
    ];

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        return $this->filterArray(
            [
                Product::FIELD_SKU => $this->offsetGet(Product::FIELD_SKU),
                Product::FIELD_NAME => $this->offsetGet(Product::FIELD_NAME),
                Product::FIELD_IMAGE => $this->offsetGet(Product::FIELD_IMAGE),
                Product::FIELD_COLLECTION => $this->offsetGet(Product::FIELD_COLLECTION),
                Product::FIELD_SIZE => (int)$this->offsetGet(Product::FIELD_SIZE),
            ]
        );
    }

    private function filterArray(array $fields) : array {
        return array_intersect_key($fields, array_flip(self::$showFields));
    }

    public static function onlyFields(array $fields) {
        self::$showFields = array_intersect(self::$fields, $fields);
    }

    public static function exceptFields(array $fields) {
        self::$showFields = array_diff(self::$fields, $fields);
    }

    public static function allFields() {
        self::$showFields = self::$fields;
    }
}
