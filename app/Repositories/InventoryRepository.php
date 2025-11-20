<?php

namespace App\Repositories;

use App\Models\Inventory;
use App\Models\Pharmacy;

class InventoryRepository
{
    protected $model;

    public function __construct(Inventory $inventory)
    {
        $this->model = $inventory;
    }


    public function createInventory(int $productId)
    {
        $pharmacies = Pharmacy::all();

        foreach ($pharmacies as $pharmacy) {
            $this->model->create([
                'product_id'  => $productId,
                'pharmacy_id' => $pharmacy->id,
            ]);
        }
    }

    public function updateInventory(int $productId, int $pharmacyId, array $data)
    {
        $inventory = $this->model
            ->where('product_id', $productId)
            ->where('pharmacy_id', $pharmacyId)
            ->firstOrFail();

        $inventory->update($data);

        return $inventory->fresh();
    }


    public function getInventoryByPharmacy(int $pharmacyId)
    {
        return $this->model
            ->with('product')
            ->where('pharmacy_id', $pharmacyId)
            ->get();
    }
}
