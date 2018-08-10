<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('products', function (Blueprint $table) {
            $table->string('sku', 16)->nullable(false);
            $table->string('name', 128);
            $table->string('image', 128);
            $table->string('collection', 64);
            $table->tinyInteger('size');
            $table->timestamps();
            $table->primary('sku');
            $table->index('collection');
            $table->index('size');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
