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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
}