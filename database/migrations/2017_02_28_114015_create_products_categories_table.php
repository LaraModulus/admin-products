<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            foreach(config('app.locales', [config('app.fallback_locale', 'en')]) as $locale){
                $table->string('title_'.$locale, 255)->nullable();
                $table->string('sub_title_'.$locale, 255)->nullable();
                $table->text('description_'.$locale)->nullable();
                $table->string('meta_title_'.$locale, 255)->nullable();
                $table->string('meta_description_'.$locale, 255)->nullable();
                $table->string('meta_keywords_'.$locale, 255)->nullable();
            }
            $table->integer('categories_id')->default(0)->index();
            $table->boolean('viewable')->default(0)->index();
            $table->smallInteger('pos')->default(0)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products_categories');
    }
}
