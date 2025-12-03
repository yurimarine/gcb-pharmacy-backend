<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $fillable =[
        'transaction_id',
        'product_id',
        'price',
        'discount',
        'discounted_price',
        'subtotal',
        'quantity'
    ];
}
