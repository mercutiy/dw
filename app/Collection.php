<?php

namespace App\Repository;

use App\Model\Product as Model;

class Collection
{
    /**
     * Retrieves all collections
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll() {
        return Model::select(Model::FIELD_COLLECTION)->distinct()->get();
    }
}