<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\InventoryRepository;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    protected $inventoryRepository;
    protected $response;

    public function __construct(InventoryRepository $inventoryRepository, ApiResponse $response)
    {
        $this->inventoryRepository = $inventoryRepository;
        $this->response = $response;
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'stock_quantity' => 'nullable|integer',
            'reorder_quantity' => 'nullable|integer',
            'expiry_date' => 'nullable|date',
            'markup_percentage' => 'nullable|numeric:8,2',
            'selling_price' => 'nullable|numeric:8,2',
        ]);

    }

    public function getInventoryByPharmacy($pharmacyId)
    {
        $data = $this->inventoryRepository->getInventoryByPharmacy($pharmacyId);
        return $this->response->success($data, "Inventory loaded");
    }

    public function getInventoryById($pharmacyId, $productId)
    {
        $data = $this->inventoryRepository->getInventoryById($pharmacyId, $productId);
        return $this->response->success($data, "Inventory loaded");
    }

    public function updateInventory(Request $request,$pharmacyId, $productId)
    {
        $validatedData = $this->validateRequest($request);
        DB::beginTransaction();
        try {
            $inventory = $this->inventoryRepository->updateInventory(
                $productId,
                $pharmacyId,
                $validatedData
            );
            DB::commit();
            return $this->response->success($inventory, "Inventory successfully updated", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response->error("Failed to update Inventory", 500, $e->getMessage());
        }
    }

    public function getLowStockByPharmacy($pharmacyId)
    {
        $lowStock = $this->inventoryRepository->getLowStockItemsByPharmacy($pharmacyId);
        return $this->response->success(
            $lowStock,
            $lowStock->isEmpty()
                ? "No low-stock items"
                : "Low-stock items found"
        );
    }

    public function getAllLowStock()
    {
        $result = $this->inventoryRepository->getAllLowStock();

        return $this->response->success(
            $result,
            "All low-stock inventories retrieved"
        );
    }

    public function getExpiredByPharmacy($pharmacyId)
    {
        $expired = $this->inventoryRepository->getExpiredItemsByPharmacy($pharmacyId);
        return $this->response->success(
            $expired,
            $expired->isEmpty()
                ? "No expired items"
                : "Expired items found"
        );
    }

    public function getAllExpired()
    {
        $result = $this->inventoryRepository->getAllExpired();
        return $this->response->success(
            $result,
            "All expired inventories retrieved"
        );
    }


}