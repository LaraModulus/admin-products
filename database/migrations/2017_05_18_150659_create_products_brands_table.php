<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_brands', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            foreach (config('app.locales', [config('app.fallback_locale', 'en')]) as $locale) {
                $table->string('title_'.$locale)->nullable();
                $table->text('description_'.$locale)->nullable();
                $table->string('meta_title_' . $locale, 255)->nullable();
                $table->string('meta_description_' . $locale, 255)->nullable();
                $table->string('meta_keywords_' . $locale, 255)->nullable();
            }
            $table->boolean('viewable')->default(true)->index();
            $table->smallInteger('pos')->default(0)->index();
            $table->string('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_brands');
    }
}
