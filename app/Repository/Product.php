<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;
use App\Model\Product as Model;

class Product
{
    /**
     * Retrieves all the products
     *
     * @return Collection
     */
    public function getAll() : Collection {
        return Model::all();
    }

    /**
     * Retrieves all the products of the collection
     *
     * @param string $collection - name of the collection
     * @return Collection
     */
    public function getCollection(string $collection) : Collection {
        return Model::where(Model::FIELD_COLLECTION, '=', $collection)->get();
    }

    /**
     * Retrieves all the products with given size
     *
     * @param int $size - size of the product
     * @return Collection
     */
    public function getBySize(int $size) : Collection {
        return Model::where(Model::FIELD_SIZE, '=', $size)->get();
    }

    /**
     * Tries to find product by its sku
     *
     * @param string $sku - sku of the product
     * @return Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getBySky(string $sku) : Model {
        return Model::where(Model::FIELD_SKU, '=', $sku)->firstOrFail();
    }

    /**
     * Update the product if one exists otherwise creates it
     *
     * @param string $sku
     * @param string $name
     * @param string $image
     * @param string $collection
     * @param int $size
     */
    public function replaceProduct (
        string $sku,
        string $name,
        string $image,
        string $collection,
        int $size
    ) {
        Model::updateOrCreate(
            [Model::FIELD_SKU => $sku],
            [
                Model::FIELD_NAME => $name,
                Model::FIELD_IMAGE => $image,
                Model::FIELD_COLLECTION => $collection,
                Model::FIELD_SIZE => $size,
            ]
        );
    }
}