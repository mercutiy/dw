<?php

namespace App\Http\Controllers;

use App\Http\Resources\CollectionResource;
use App\Repository\Collection as Repository;

class CollectionController extends Controller
{
    /**
     * Displays collections list
     */
    public function index() {
        return CollectionResource::collection(
            (new Repository())->getAll()
        )->response();
    }

}
