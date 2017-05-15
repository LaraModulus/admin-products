<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_collections', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            foreach (config('app.locales', [config('app.fallback_locale', 'en')]) as $locale) {
                $table->string('title_' . $locale, 255)->nullable();
                $table->string('sub_title_' . $locale, 255)->nullable();
                $table->text('short_description_' . $locale)->nullable();
                $table->text('description_' . $locale)->nullable();
                $table->string('meta_title_' . $locale, 255)->nullable();
                $table->string('meta_description_' . $locale, 255)->nullable();
                $table->string('meta_keywords_' . $locale, 255)->nullable();
            }
            $table->boolean('viewable')->default(false)->index();
            $table->smallInteger('pos')->default(0)->index();
        });

        Schema::create('products_item_collection', function(Blueprint $table){
            $table->integer('collection_id');
            $table->integer('item_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products_collections');
        Schema::drop('products_item_collection');
    }
}
