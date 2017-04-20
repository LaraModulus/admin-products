<?php
namespace LaraMod\AdminProducts\Models;

use LaraMod\AdminCore\Scopes\AdminCoreOrderByCreatedAtScope;
use LaraMod\AdminCore\Scopes\AdminCoreOrderByPosScope;
use LaraMod\AdminFiles\Models\Files;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    protected $appends = ['title', 'price_final'];

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

    public function getPriceFinalAttribute(){
        return (double)($this->promo_price > 0 && strtotime($this->promo_from) < time() && strtotime($this->promo_to) > time() ? $this->promo_price : $this->price);
    }

    public function getIsPromoAttribute()
    {
        return ($this->promo_price > 0 && strtotime($this->promo_from) < time() && strtotime($this->promo_to) > time() ? true : false);
    }

    public function getPromoDiscountAttribute(){
        if(!$this->is_promo) return 0;
        return 100-ceil(($this->promo_price / $this->price)*100);
    }



    protected function bootIfNotBooted()
    {
        parent::boot();
        static::addGlobalScope(new AdminCoreOrderByPosScope());
        static::addGlobalScope(new AdminCoreOrderByCreatedAtScope());
    }



}