<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsCharacteristicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_characteristics', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            foreach (config('app.locales', [config('app.fallback_locale', 'en')]) as $locale) {
                $table->string('title_' . $locale, 255)->nullable();
                $table->text('description_' . $locale)->nullable();
            }
            $table->enum('filter_type', ['range', 'multiple', 'single', 'boolean'])->default('single');
            $table->boolean('filter_enabled')->default(false);
        });

        Schema::create('products_items_characteristics', function(Blueprint $table){
           $table->integer('products_characteristics_id')->unsigned();
           $table->integer('products_items_id')->unsigned();
           $table->string('filter_value')->nullable();
           $table->smallInteger('pos')->default(0)->index();

           $table->primary(['products_characteristics_id', 'products_items_id'], 'characteristics_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_characteristics');
        Schema::dropIfExists('products_items_characteristics');
    }
}
