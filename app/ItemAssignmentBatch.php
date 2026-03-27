<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemAssignmentBatch extends Model
{
    protected $fillable = [
        'school_id',
        'created_by',
        'scope',
        'class_id',
        'section_id',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(ParentRecommendedItem::class, 'assignment_batch_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\User::class, 'created_by');
    }
}
