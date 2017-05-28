<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_items', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            foreach (config('app.locales', [config('app.fallback_locale', 'en')]) as $locale) {
                $table->string('title_' . $locale, 255)->nullable();
                $table->string('sub_title_' . $locale, 255)->nullable();
                $table->text('short_description_' . $locale)->nullable();
                $table->text('description_' . $locale)->nullable();
                $table->text('table_info_' . $locale)->nullable();
                $table->string('meta_title_' . $locale, 255)->nullable();
                $table->string('meta_description_' . $locale, 255)->nullable();
                $table->string('meta_keywords_' . $locale, 255)->nullable();
            }
            $table->boolean('viewable')->default(false)->index();
            $table->decimal('price', 7, 2)->nullable()->index();
            $table->decimal('promo_price', 7, 2)->nullable()->index();
            $table->dateTime('promo_from')->nullable()->index();
            $table->dateTime('promo_to')->nullable()->index();
            $table->string('code', 255)->nullable()->index();
            $table->string('manufacturer_code')->nullable()->index();
            $table->decimal('weight', 7, 2)->default(0)->index();
            $table->decimal('volume', 7, 2)->default(0)->index();
            $table->smallInteger('avlb_qty')->default(0)->index();
            $table->smallInteger('pos')->default(0)->index();
            $table->boolean('subtract_qty')->default(false);
            $table->integer('brand_id')->unsigned()->nullable()->index();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_items');
    }
}
