<?php

namespace LaraMod\Admin\Products\Models;

use LaraMod\Admin\Core\Scopes\AdminCoreOrderByCreatedAtScope;
use LaraMod\Admin\Core\Scopes\AdminCoreOrderByPosScope;
use Illuminate\Database\Eloquent\Model;

class Characteristics extends Model
{
    public $timestamps = true;
    protected $table = 'products_characteristics';

    protected $guarded = ['id'];

    protected $casts = [
        'filter_enabled' => 'boolean'
    ];

    protected $dates = ['deleted_at'];

    protected $appends = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        foreach (config('app.locales', [config('app.fallback_locale', 'en')]) as $locale) {
            $this->fillable = array_merge($this->fillable, [
                'title_'.$locale,
                'description_'.$locale
            ]);
        }

        $this->fillable = array_merge($this->fillable, [
            'filter_type',
            'filter_enabled'
        ]);
    }

    public function getTitleAttribute()
    {
        return $this->{'title_' . config('app.fallback_locale', 'en')};
    }

    public function getDescriptionAttribute()
    {
        return $this->{'description_' . config('app.fallback_locale', 'en')};
    }



    protected function bootIfNotBooted()
    {
        parent::boot();
        static::addGlobalScope(new AdminCoreOrderByCreatedAtScope());
    }


}