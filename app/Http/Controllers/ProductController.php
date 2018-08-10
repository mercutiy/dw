<?php

namespace App\Http\Controllers;

use App\Exceptions\WrongJsonStructure;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Repository\Product as Repository;
use App\Repository\Product as Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
     * @throws BadRequestHttpException
     */
    public function store(Request $request) {
        if (!$request->isJson()) {
            throw new BadRequestHttpException();
        }

        $json = $request->json();
        if (!$json instanceof ParameterBag || !$json->count()) {
            throw new BadRequestHttpException('Malformed JSON provided');
        }

        foreach ($this->processProduct($json->all()) as $product) {
            $this->getRepository()->replaceProduct(
                $product['sku'],
                $product['name'],
                $product['image'],
                $product['collection'],
                $product['size']
            );
        }

        return response()->json(null,201);
    }

    /**
     * Displays the specified product
     *
     * @param  string  $sku
     * @return \Illuminate\Http\JsonResponse
     * @throws ModelNotFoundException
     */
    public function show(string $sku) {
        $product = $this->getRepository()->getBySky($sku);

        ProductResource::allFields();
        return (new ProductResource($product))->response();
    }

    /**
     * Displays the collection's products list
     *
     * @param string $collection
     * @return \Illuminate\Http\JsonResponse
     * @throws ModelNotFoundException
     */
    public function collectionProducts(string $collection) {
        $products = $this->getRepository()->getCollection($collection);
        if (!$products->count()) {
            throw new ModelNotFoundException();
        }

        return ProductResource::collection($products)->response();
    }

    /**
     * Processes received json
     *
     * @param array $json
     * @return array
     * @throws BadRequestHttpException
     */
    private function processProduct(array $json) : array {
        $productLst = [];
        foreach ($json as $collection) {
            $collectionName = trim($collection['collection'] ?? '');
            $size = (int)($collection['size'] ?? 0);
            $products = (array)($collection['products'] ?? []);

            if (empty($collectionName) || empty($size)) {
                throw new BadRequestHttpException('Wrong JSON structure');
            }

            foreach ($products as $product) {
                $image = trim($product['image'] ?? '');
                $name = trim($product['name'] ?? '');
                $sku = trim($product['sku'] ?? '');
                if (empty($image) || empty($name) || empty($sku)) {
                    throw new BadRequestHttpException('Wrong JSON structure');
                }

                if (isset($productLst[$sku])) {
                    throw new BadRequestHttpException('The sku field must be unique');
                }

                $productLst[$sku] = [
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
