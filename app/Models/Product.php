<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const FIELD_SKU = 'sku';
    const FIELD_NAME = 'name';
    const FIELD_IMAGE = 'image';
    const FIELD_COLLECTION = 'collection';
    const FIELD_SIZE = 'size';

    protected $primaryKey = 'sku';

    protected $keyType = 'string';

    protected $fillable = [
        self::FIELD_SKU,
        self::FIELD_NAME,
        self::FIELD_IMAGE,
        self::FIELD_COLLECTION,
        self::FIELD_SIZE,
    ];

}
