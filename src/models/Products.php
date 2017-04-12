<?php
namespace Escapeboy\AdminProducts\Models;

use Escapeboy\AdminCore\Scopes\AdminCoreOrderByCreatedAtScope;
use Escapeboy\AdminCore\Scopes\AdminCoreOrderByPosScope;
use Escapeboy\AdminFiles\Models\Files;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Products extends Model
{
    public $timestamps = true;
    protected $table = 'products_items';

    use SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'viewable' => 'boolean',
        'price' => 'decimal',
        'promo_price' => 'decimal',
        'weight' => 'decimal',
        'volume' => 'decimal',
        'avlb_qty' => 'integer',
        'pos' => 'integer',
        'table_info' => 'object'
    ];

    protected $dates = ['deleted_at', 'promo_from', 'promo_to'];

    public function scopeVisible($q)
    {
        return $q->whereViewable(true);
    }

    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'products_item_categories','products_items_id', 'products_categories_id');
    }

    public function options()
    {
        return $this->hasMany(ItemOptions::class, 'products_items_id');
    }

    public function linked()
    {
        return $this->belongsToMany(Products::class,'products_linked_items', 'item1_id','item2_id');
    }

    public function files(){
        return $this->morphToMany(Files::class,'relation','files_relations','relation_id', 'files_id');
    }

    public function reviews()
    {
        return $this->hasMany(Reviews::class,'products_items_id');
    }

    public function getTitleAttribute(){
        return $this->{'title_'.config('app.fallback_locale', 'en')};
    }
    public function getSubTitleAttribute(){
        return $this->{'sub_title_'.config('app.fallback_locale', 'en')};
    }
    public function getShortDescriptionAttribute(){
        return $this->{'short_description_'.config('app.fallback_locale', 'en')};
    }
    public function getDescriptionAttribute(){
        return $this->{'description_'.config('app.fallback_locale', 'en')};
    }
    public function getTableInfoAttribute(){
        return $this->{'table_info_'.config('app.fallback_locale', 'en')};
    }
    public function getMetaTitleAttribute(){
        return $this->{'meta_title_'.config('app.fallback_locale', 'en')};
    }
    public function getMetaDescriptionAttribute(){
        return $this->{'meta_description_'.config('app.fallback_locale', 'en')};
    }
    public function getMetaKeywordsAttribute(){
        return $this->{'meta_keywords_'.config('app.fallback_locale', 'en')};
    }



    protected function bootIfNotBooted()
    {
        parent::boot();
        static::addGlobalScope(new AdminCoreOrderByPosScope());
        static::addGlobalScope(new AdminCoreOrderByCreatedAtScope());
    }



}