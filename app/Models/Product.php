<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable =[
        'generic_id',
        'supplier_id',
        'manufacturer_id',
        'brand_name',
        'sku',
        'dosage_form',
        'dosage_amount',
        'packaging_type',
        'volume_amount',
        'volume_unit',
        'unit_cost',
        'barcode',
        'description',
    ];
    public function generic()
    {
        return $this->belongsTo(Generic::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
