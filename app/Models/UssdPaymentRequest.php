<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UssdPaymentRequest extends Model
{
    public const FLOW_SCHOOL_FEES = 'school_fees';

    public const FLOW_STUDENT_UTILITIES = 'student_utilities';

    protected $table = 'ussd_payment_requests';

    protected $fillable = [
        'session_id',
        'phone_number',
        'flow_type',
        'student_name',
        'admission_reference',
        'amount',
        'status',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'meta' => 'array',
        ];
    }
}
