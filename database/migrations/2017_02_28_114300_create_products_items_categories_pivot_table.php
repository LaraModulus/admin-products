<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsItemsCategoriesPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_item_categories', function (Blueprint $table) {
            $table->integer('products_items_id')->index();
            $table->integer('products_categories_id')->index();
            $table->primary(['products_items_id', 'products_categories_id'], 'categories_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_item_categories');
    }
}
