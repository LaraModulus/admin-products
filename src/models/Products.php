<?php

namespace LaraMod\Admin\Products\Models;

use LaraMod\Admin\Core\Scopes\AdminCoreOrderByCreatedAtScope;
use LaraMod\Admin\Core\Scopes\AdminCoreOrderByPosScope;
use LaraMod\Admin\Core\Traits\HelpersTrait;
use LaraMod\Admin\Files\Models\Files;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    public $timestamps = true;
    protected $table = 'products_items';

    use SoftDeletes, HelpersTrait;
    protected $guarded = ['id'];

    protected $casts = [
        'viewable'    => 'boolean',
        'price'       => 'decimal',
        'promo_price' => 'decimal',
        'weight'      => 'decimal',
        'volume'      => 'decimal',
        'avlb_qty'    => 'integer',
        'pos'         => 'integer',
        'table_info'  => 'object',
        'subtract_qty' => 'boolean',
    ];

    protected $dates = ['deleted_at', 'promo_from', 'promo_to'];

    protected $appends = ['title', 'price_final'];


    protected $fillable = [
        'viewable',
        'price',
        'promo_price',
        'promo_from',
        'promo_to',
        'code',
        'manufacturer_code',
        'weight',
        'volume',
        'avlb_qty',
        'subtract_qty',
        'brand_id',
        'slug',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        foreach (config('app.locales', [config('app.fallback_locale', 'en')]) as $locale) {
            $this->fillable = array_merge($this->fillable, [
                'title_' . $locale,
                'sub_title_' . $locale,
                'short_description_' . $locale,
                'description_' . $locale,
                'table_info_' . $locale,
                'meta_title_' . $locale,
                'meta_description_' . $locale,
                'meta_keywords_' . $locale,
            ]);
        }
    }

    public function scopeVisible($q)
    {
        return $q->whereViewable(true);
    }

    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'products_item_categories', 'products_items_id',
            'products_categories_id');
    }

    public function options()
    {
        return $this->belongsToMany(Options::class, 'products_item_options', 'products_item_id',
            'products_option_id')
            ->withPivot(['viewable', 'price', 'promo_price', 'code', 'manufacturer_code', 'weight', 'volume', 'avlb_qty','pos'])
            ->orderBy('products_item_options.pos');
    }

    public function linked()
    {
        return $this->belongsToMany(Products::class, 'products_linked_items', 'item1_id', 'item2_id');
    }

    public function files()
    {
        return $this->morphToMany(Files::class, 'relation', 'files_relations', 'relation_id', 'files_id')->orderBy('files_relations.pos');
    }

    public function characteristics()
    {
        return $this->belongsToMany(Characteristics::class,'products_items_characteristics', 'products_items_id', 'products_characteristics_id')
            ->withPivot(['filter_value', 'pos'])
            ->orderBy('products_items_characteristics.pos');
    }

    public function reviews()
    {
        return $this->hasMany(Reviews::class, 'products_items_id');
    }

    public function collections(){
        return $this->belongsToMany(Collections::class,'products_item_collection','item_id','collection_id');
    }

    public function brand()
    {
        return $this->hasOne(Brands::class, 'id', 'brand_id');
    }

    public function getTitleAttribute()
    {
        return $this->{'title_' . config('app.fallback_locale', 'en')};
    }

    public function getSubTitleAttribute()
    {
        return $this->{'sub_title_' . config('app.fallback_locale', 'en')};
    }

    public function getShortDescriptionAttribute()
    {
        return $this->{'short_description_' . config('app.fallback_locale', 'en')};
    }

    public function getDescriptionAttribute()
    {
        return $this->{'description_' . config('app.fallback_locale', 'en')};
    }

    public function getTableInfoAttribute()
    {
        return $this->{'table_info_' . config('app.fallback_locale', 'en')};
    }

    public function getMetaTitleAttribute()
    {
        return $this->{'meta_title_' . config('app.fallback_locale', 'en')};
    }

    public function getMetaDescriptionAttribute()
    {
        return $this->{'meta_description_' . config('app.fallback_locale', 'en')};
    }

    public function getMetaKeywordsAttribute()
    {
        return $this->{'meta_keywords_' . config('app.fallback_locale', 'en')};
    }

    public function getPriceFinalAttribute()
    {
        return (double)($this->promo_price > 0 && strtotime($this->promo_from) < time() && strtotime($this->promo_to) > time() ? $this->promo_price : $this->price);
    }

    public function getIsPromoAttribute()
    {
        return ($this->promo_price > 0 && strtotime($this->promo_from) < time() && strtotime($this->promo_to) > time() ? true : false);
    }

    public function getPromoDiscountAttribute()
    {
        if (!$this->is_promo) {
            return 0;
        }

        return 100 - ceil(($this->promo_price / $this->price) * 100);
    }

    public function setPromoFromAttribute($value)
    {
        $this->attributes['promo_from'] = $value ?: null;
    }

    public function setPromoToAttribute($value)
    {
        $this->attributes['promo_to'] = $value ?: null;
    }


    protected function bootIfNotBooted()
    {
        parent::boot();
        static::addGlobalScope(new AdminCoreOrderByPosScope());
        static::addGlobalScope(new AdminCoreOrderByCreatedAtScope());
    }


}