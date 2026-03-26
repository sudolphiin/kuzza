<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolRecommendedItem extends Model
{
    use SoftDeletes;

    protected $table = 'school_recommended_items';

    protected $fillable = [
        'school_id',
        'item_name',
        'item_type',
        'description',
        'price',
        'product_link',
        'image_url',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2'
    ];

    public function school()
    {
        return $this->belongsTo('App\SmSchool', 'school_id');
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function parentItems()
    {
        return $this->hasMany('App\ParentRecommendedItem', 'recommended_item_id');
    }
}
