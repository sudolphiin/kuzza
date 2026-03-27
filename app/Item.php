<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'description', 'category', 'price'];

    public function assignments()
    {
        return $this->hasMany(ItemParentAssignment::class);
    }
}
