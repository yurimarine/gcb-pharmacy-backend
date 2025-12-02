<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = [
        'batch_number',
        'pharmacy_id',
        'batch_date',
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function batchItems()
    {
        return $this->hasMany(BatchItem::class);
    }
}