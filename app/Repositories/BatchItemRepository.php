<?php

namespace App\Repositories;

use App\Models\BatchItem;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class BatchItemRepository extends BaseRepositoryInterface
{
    protected $model;

    public function __construct(BatchItem $batchItem)
    {
        $this->model = $batchItem;
    }

    public function createBatchItems($batchId, $pharmacyId, array $data)
    {
        try {
            $batchItems = $data;
            $batchResults = [];

            foreach ($batchItems as $item) {
                $productId  = $item['product_id'];
                $qtyToAdd   = (int) ($item['in_stock_quantity'] ?? 0);
                $qtyToSub   = (int) ($item['out_stock_quantity'] ?? 0);
                $expiryDate = $item['new_expiry_date'] ?? null;

                $inventory = Inventory::where('pharmacy_id', $pharmacyId)
                    ->where('product_id', $productId)
                    ->first();

                if (!$inventory) {
                    return [
                        "status" => "failed",
                        "message" => "Inventory does not exist for pharmacy_id $pharmacyId and product_id $productId",
                    ];
                }

                $previousQty = $inventory->stock_quantity;

                $newQty = $previousQty + $qtyToAdd - $qtyToSub;

                if ($newQty < 0) {
                    $newQty = 0;
                }

                $inventory->update([
                    'stock_quantity' => $newQty,
                    'expiry_date'    => $expiryDate ?? $inventory->expiry_date,
                ]);

                $batchItem = $this->model->create([
                    'batch_id'          => $batchId,
                    'product_id'        => $productId,
                    'in_stock_quantity' => $qtyToAdd,
                    'out_stock_quantity'=> $qtyToSub,
                    'prev_stock_quantity'=> $previousQty,
                    'new_stock_quantity'=> $newQty,
                    'new_expiry_date'   => $expiryDate,
                    'batch_date'        => now(),
                ]);

                $batchResults[] = [
                    "product_id"        => $productId,
                    "batch_item_id"     => $batchItem->id,
                    "previous_quantity" => $previousQty,
                    "new_quantity"      => $newQty,
                    "expiry_date"       => $inventory->expiry_date,
                ];
            }

            return [
                "status" => "success",
                "items" => $batchResults
            ];
        }
        catch (\Exception $e) {
            \Log::error("Repository createBatch() error", [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'data'    => $data,
            ]);

            throw $e;
        }
    }

    public function getBatchItemsById(int $id)
    {
        $batchItems = $this->model->where('batch_id', $id)->with('product:id,product_name', 'batch:id,batch_number,batch_date')->get();
        return $batchItems;
    }
}