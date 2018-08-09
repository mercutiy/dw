<?php

namespace App\Http\Controllers;

use App\Exceptions\WrongJsonStructure;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Repository\Product as Repository;
use App\Repository\Product as Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\ParameterBag;

class ProductController extends Controller
{
    /**
     * Show only product of certain sizes
     */
    const FIELD_SIZE = 'size';

    /**
     * List of fields that should show
     */
    const FIELD_FIELDS = 'fields';

    /**
     * Displays products list
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        if ($request->has(self::FIELD_SIZE)) {
            $collection = $this->getRepository()->getBySize((int)$request->get(self::FIELD_SIZE));
        } else {
            $collection = $this->getRepository()->getAll();
        }
        if ($request->has(self::FIELD_FIELDS)) {
            ProductResource::onlyFields(explode(',', $request->get(self::FIELD_FIELDS)));
        }
        return ProductResource::collection($collection)->response();
    }

    /**
     * Update/create bunch of products
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {
        if (!$request->isJson()) {
            return response()->json(['error' => 'Bad request'], 400);
        }

        $json = $request->json();
        if (!$json instanceof ParameterBag || !$json->count()) {
            return response()->json(['error' => 'Malformed JSON provided'], 400);
        }

        try {
            foreach ($this->processProduct($json->all()) as $product) {
                $this->getRepository()->replaceProduct(
                    $product['sku'],
                    $product['name'],
                    $product['image'],
                    $product['collection'],
                    $product['size']
                );
            }
        } catch (WrongJsonStructure $e) {
            return response()->json(['error' => 'Wrong JSON structure'], 400);
        }

        return response()->json(null,201);
    }

    /**
     * Displays the specified product
     *
     * @param  string  $sku
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $sku) {
        try {
            $product = $this->getRepository()->getBySky($sku);
        } catch (ModelNotFoundException $e ) {
            return response()->json(['error' => 'Resource not found'], 404);
        }

        ProductResource::allFields();
        return (new ProductResource($product))->response();
    }

    /**
     * Displays the collection's products list
     *
     * @param string $collection
     * @return \Illuminate\Http\JsonResponse
     */
    public function collectionProducts(string $collection) {
        $products = $this->getRepository()->getCollection($collection);
        if (!$products->count()) {
            return response()->json(['error' => 'Resource not found'], 404);
        }

        return ProductResource::collection($products)->response();
    }

    /**
     * Processes received json
     *
     * @param array $json
     * @return array
     * @throws WrongJsonStructure
     */
    private function processProduct(array $json) : array {
        $productLst = [];
        foreach ($json as $collection) {
            $collectionName = trim($collection['collection'] ?? '');
            $size = (int)($collection['size'] ?? 0);
            $products = (array)($collection['products'] ?? []);

            if (empty($collectionName) || empty($size)) {
                throw new WrongJsonStructure();
            }

            foreach ($products as $product) {
                $image = trim($product['image'] ?? '');
                $name = trim($product['name'] ?? '');
                $sku = trim($product['sku'] ?? '');
                if (empty($image) || empty($name) || empty($sku)) {
                    throw new WrongJsonStructure();
                }

                $productLst[] = [
                    'sku' => $sku,
                    'name' => $name,
                    'image' => $image,
                    'collection' => $collectionName,
                    'size' => $size,
                ];
            }
        }

        return $productLst;
    }

    private function getRepository() : Repository {
        static $repository = null;
        $repository = new Repository();
        return $repository;
    }
}
