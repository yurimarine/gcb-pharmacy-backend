<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\InventoryRepository;
use App\Helpers\ApiResponse;

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
            'markup_percentage' => 'nullable|decimal:8,2',
            'selling_price' => 'nullable|decimal:8,2',
        ]);

    }

    public function getInventoryByPharmacy($pharmacyId)
    {
        $data = $this->inventoryRepository->getInventoryByPharmacy($pharmacyId);
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
}