<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsItemsReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_items_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('link', 1000)->nullable();
            $table->integer('products_items_id')->unsigned()->index();
            $table->string('language', 3)->default('en')->index();
            $table->smallInteger('pos')->default(0)->index();
            $table->tinyInteger('rating')->unsigned();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products_items_reviews');
    }
}
