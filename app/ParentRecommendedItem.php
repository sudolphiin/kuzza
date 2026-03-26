<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentRecommendedItem extends Model
{
    use SoftDeletes;

    protected $table = 'parent_recommended_items';

    protected $fillable = [
        'recommended_item_id',
        'parent_id',
        'student_id',
        'assignment_batch_id',
        'status',
        'payment_amount',
        'payment_date',
        'delivered_date',
        'notes',
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'delivered_date' => 'datetime',
    ];

    public function recommendedItem()
    {
        return $this->belongsTo('App\SchoolRecommendedItem', 'recommended_item_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\User', 'parent_id');
    }

    public function student()
    {
        return $this->belongsTo('App\User', 'student_id');
    }

    public function batch()
    {
        return $this->belongsTo(ItemAssignmentBatch::class, 'assignment_batch_id');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isAlreadyBought(): bool
    {
        return $this->status === 'already_bought';
    }

    public function isSelectedForOrder(): bool
    {
        return $this->status === 'selected_for_order';
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isDelivered()
    {
        return $this->status === 'delivered';
    }
}
