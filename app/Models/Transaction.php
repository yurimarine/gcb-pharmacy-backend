<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable =[
        'user_id',
        'pharmacy_id',
        'receipt_number',
        'total_amount',
        'total_payment',
        'total_change',
        'transaction_date'
    ];

}
