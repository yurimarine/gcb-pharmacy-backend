<?php

namespace App\Repositories;

use App\Models\InventoryMovement;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

class InventoryMovementRepository
{
    protected $model;

    public function __construct(InventoryMovement $inventoryMovement)
    {
        $this->model = $inventoryMovement;
    }

    public function createMovement(int $productId, int $pharmacyId, int $quantityChange, string $movementType, string $source)
    {
        return DB::transaction(function () use ($productId, $pharmacyId, $quantityChange, $movementType, $source) {
            // Record the movement
            $movement = $this->model->create([
                'product_id' => $productId,
                'pharmacy_id' => $pharmacyId,
                'transaction_id' => null,
                'quantity_change' => $quantityChange,
                'movement_type' => $movementType,
                'movement_date' => now(),
                'source' => $source,
            ]);

            // Update cached stock in inventory
            $inventory = Inventory::where('product_id', $productId)
                ->where('pharmacy_id', $pharmacyId)
                ->firstOrFail();

            $inventory->stock_quantity += $quantityChange;
            $inventory->save();

            return $inventory->fresh();
        });
    }

    public function rebuildStock(int $productId, int $pharmacyId)
    {
        $total = $this->model
            ->where('product_id', $productId)
            ->where('pharmacy_id', $pharmacyId)
            ->sum('quantity_change');

        $inventory = Inventory::where('product_id', $productId)
            ->where('pharmacy_id', $pharmacyId)
            ->firstOrFail();

        $inventory->stock_quantity = $total;
        $inventory->save();

        return $inventory->fresh();
    }
}
