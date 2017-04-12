<?php
namespace LaraMod\AdminProducts\Models;

use LaraMod\AdminCore\Scopes\AdminCoreOrderByCreatedAtScope;
use LaraMod\AdminCore\Scopes\AdminCoreOrderByPosScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reviews extends Model
{
    public $timestamps = true;
    protected $table = 'products_items_reviews';

    use SoftDeletes;
    protected $guarded = ['id'];


    protected $dates = ['deleted_at'];

    public function product(){
        return $this->belongsTo(Products::class, 'products_items_id');
    }

    protected function bootIfNotBooted()
    {
        parent::boot();
        static::addGlobalScope(new AdminCoreOrderByPosScope());
        static::addGlobalScope(new AdminCoreOrderByCreatedAtScope());
    }



}