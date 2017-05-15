<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_item_options', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            foreach (config('app.locales', [config('app.fallback_locale', 'en')]) as $locale) {
                $table->string('title_' . $locale, 255)->nullable();
                $table->text('description_' . $locale)->nullable();
            }
            $table->boolean('viewable')->default(false)->index();
            $table->decimal('price', 7, 2)->nullable()->index();
            $table->decimal('promo_price', 7, 2)->nullable()->index();
            $table->string('code', 255)->nullable()->index();
            $table->string('manufacturer_code')->nullable()->index();
            $table->decimal('weight', 7, 2)->default(0)->index();
            $table->decimal('volume', 7, 2)->default(0)->index();
            $table->smallInteger('avlb_qty')->default(0)->index();
            $table->smallInteger('pos')->default(0)->index();
            $table->integer('products_items_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products_item_options');
    }
}
