<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsLinkedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_linked_items', function (Blueprint $table) {
            $table->integer('item1_id')->unsigned()->index();
            $table->integer('item2_id')->unsigned()->index();
            $table->primary(['item1_id', 'item2_id'], 'items_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_linked_items');
    }
}
