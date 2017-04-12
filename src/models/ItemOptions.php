<?php
namespace Escapeboy\AdminProducts\Models;

use Escapeboy\AdminCore\Scopes\AdminCoreOrderByCreatedAtScope;
use Escapeboy\AdminCore\Scopes\AdminCoreOrderByPosScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemOptions extends Model
{
    public $timestamps = true;
    protected $table = 'products_item_options';

    use SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'visible' => 'boolean',
        'price' => 'decimal',
        'promo_price' => 'decimal',
        'weight' => 'decimal',
        'volume' => 'decimal',
        'avlb_qty' => 'integer',
        'pos' => 'integer',
    ];

    protected $dates = ['deleted_at'];

    public function scopeVisible($q)
    {
        return $q->whereVisible(true);
    }

    public function getTitleAttribute(){
        return $this->{'title_'.config('app.fallback_locale', 'en')};
    }

    public function getDescriptionAttribute(){
        return $this->{'description_'.config('app.fallback_locale', 'en')};
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