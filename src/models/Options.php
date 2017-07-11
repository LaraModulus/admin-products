<?php

namespace LaraMod\Admin\Products\Models;

use LaraMod\Admin\Core\Scopes\AdminCoreOrderByCreatedAtScope;
use LaraMod\Admin\Core\Scopes\AdminCoreOrderByPosScope;
use Illuminate\Database\Eloquent\Model;
use LaraMod\Admin\Core\Traits\HelpersTrait;

class Options extends Model
{
    public $timestamps = true;
    protected $table = 'products_options';

    protected $guarded = ['id'];

    use HelpersTrait;

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
    }


    public function getTitleAttribute()
    {
        return $this->{'title_' . config('app.locale', 'en')};
    }

    public function getDescriptionAttribute()
    {
        return $this->{'description_' . config('app.locale', 'en')};
    }

    protected function bootIfNotBooted()
    {
        parent::boot();
        static::addGlobalScope(new AdminCoreOrderByCreatedAtScope());
    }


}