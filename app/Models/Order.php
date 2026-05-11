<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'parent_id',
        'student_id',
        'total_amount',
        'status',
        'external_order_id',
        'external_order_code',
        'external_source',
        'notes',
        'mpesa_checkout_request_id',
        'mpesa_merchant_request_id',
        'mpesa_result_code',
        'mpesa_result_desc',
        'mpesa_receipt_number',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

