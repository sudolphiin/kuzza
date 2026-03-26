<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemParentAssignment extends Model
{
    protected $fillable = ['item_id', 'parent_id', 'assigned_by', 'assigned_by_role'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function parent()
    {
        return $this->belongsTo(SmParent::class, 'parent_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
