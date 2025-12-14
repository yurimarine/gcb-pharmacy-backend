<?php

namespace App\Repositories;

use App\Models\Inventory;
use App\Models\Pharmacy;
use App\Repositories\InventoryMovementRepository;

class InventoryRepository
{
    protected $model;

    public function __construct(Inventory $inventory, InventoryMovementRepository $inventoryMovementRepo)
    {
        $this->model = $inventory;
        $this->inventoryMovementRepo = $inventoryMovementRepo;
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

    public function createInventoryByPharmacy(int $pharmacyId, int $productId)
    {
        $this->model->create([
            'product_id'  => $productId,
            'pharmacy_id' => $pharmacyId,
        ]);
    }

    public function updateInventory(int $productId, int $pharmacyId, array $data)
    {
        $inventory = $this->model
            ->where('product_id', $productId)
            ->where('pharmacy_id', $pharmacyId)
            ->firstOrFail();

            if (isset($data['stock_quantity'])) {
            $diff = $data['stock_quantity'] - $inventory->stock_quantity;
            if ($diff !== 0) {
                $this->inventoryMovementRepo->createMovement(
                    $productId,
                    $pharmacyId,
                    $diff,
                    'adjustment',
                    'admin',
                );
            }
        }

        $data['status'] = $this->calculateStatus($data);
        $inventory->fill($data);
        $inventory->save();

        return $inventory->fresh();
    }

    private function calculateStatus(array $data): string
    {
        $stock = $data['stock_quantity'] ?? 0;
        $reorder = $data['reorder_quantity'] ?? 0;
        $expiry = $data['expiry_date'] ?? null;

        $isExpired = $expiry && $expiry < now()->toDateString();

        if ($isExpired && $stock < $reorder) return 'critical';
        if ($isExpired) return 'expired';
        if ($stock < $reorder) return 'low_stock';
        return 'normal';
    }

    public function getInventoryByPharmacy(int $pharmacyId)
    {
        return $this->model
            ->with('product.generic')
            ->where('pharmacy_id', $pharmacyId)
            ->get();
    }

    public function getInventoryForTerminal(int $pharmacyId)
    {
        return $this->model
            ->where('pharmacy_id', $pharmacyId)
            ->select('id','product_id', 'pharmacy_id','stock_quantity', 'reorder_quantity', 'expiry_date', 'markup_percentage','selling_price', 'status')
            ->get();
    }

    public function getInventoryById(int $pharmacyId, int $productId)
    {
        return $this->model
            ->with('product')
            ->where('pharmacy_id', $pharmacyId)
            ->where('product_id', $productId)
            ->firstOrFail();
    }

   public function getLowStockItemsByPharmacy(int $pharmacyId)
    {
        return $this->model
            ->with('product')
            ->where('pharmacy_id', $pharmacyId)
            ->whereColumn('stock_quantity', '<', 'reorder_quantity')
            ->get();
    }

    public function getAllLowStock()
    {
        return $this->model
            ->with(['product.generic', 'pharmacy'])
            ->whereColumn('stock_quantity', '<', 'reorder_quantity')
            ->get();
    }

    public function getExpiredItemsByPharmacy(int $pharmacyId)
    {
        return $this->model
            ->with('product')
            ->where('pharmacy_id', $pharmacyId)
            ->where('expiry_date', '<', now())
            ->get();
    }
    public function getAllExpired()
    {
        return $this->model
            ->with(['product.generic', 'pharmacy'])
            ->where('expiry_date', '<', now())
            ->get();
    }

}
