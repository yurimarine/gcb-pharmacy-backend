<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable =[
        'product_id',
        'pharmacy_id',
        'stock_quantity',
        'reorder_quantity',
        'expiry_date',
        'markup_percentage',
        'selling_price',
        'status',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
}