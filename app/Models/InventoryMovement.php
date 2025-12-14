<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $fillable =[
        'product_id',
        'pharmacy_id',
        'transaction_id',
        'quantity_change',
        'movement_type',
        'source',
        'movement_date',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}