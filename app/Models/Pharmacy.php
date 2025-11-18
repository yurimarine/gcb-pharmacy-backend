<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    protected $fillable =[
        'name',
        'manager',
        'email',
        'phone',
        'address',
        'description'
    ];
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
