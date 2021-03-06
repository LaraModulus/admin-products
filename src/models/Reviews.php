<?php

namespace LaraMod\Admin\Products\Models;

use LaraMod\Admin\Core\Scopes\AdminCoreOrderByCreatedAtScope;
use LaraMod\Admin\Core\Scopes\AdminCoreOrderByPosScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaraMod\Admin\Core\Traits\HelpersTrait;

class Reviews extends Model
{
    public $timestamps = true;
    protected $table = 'products_items_reviews';

    use SoftDeletes, HelpersTrait;
    protected $guarded = ['id'];


    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title',
        'description',
        'link',
        'products_items_id',
        'language',
        'pos',
        'rating'
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'products_items_id');
    }

    protected function bootIfNotBooted()
    {
        parent::boot();
        static::addGlobalScope(new AdminCoreOrderByPosScope());
        static::addGlobalScope(new AdminCoreOrderByCreatedAtScope());
    }


}