<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchItem extends Model
{
    protected $fillable = [
        'batch_id',
        'product_id',
        'in_stock_quantity',
        'out_stock_quantity',
        'prev_stock_quantity',
        'new_stock_quantity',
        'new_expiry_date',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}