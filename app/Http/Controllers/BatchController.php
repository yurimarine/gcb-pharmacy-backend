<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\BatchRepository;
use App\Helpers\ApiResponse;

class BatchController extends Controller
{
    protected $batchRepo;
    protected $response;

    public function __construct(BatchRepository $batchRepo, ApiResponse $response)
    {
        $this->batchRepo = $batchRepo;
        $this->response = $response;
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'pharmacy_id' => 'required|exists:pharmacies,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.in_stock_quantity' => 'nullable|integer|min:0',
            'items.*.out_stock_quantity' => 'nullable|integer|min:0',
            'items.*.prev_stock_quantity' => 'nullable|integer|min:0',
            'items.*.new_expiry_date' => 'nullable|date',
        ]);

    }

    public function addBatch(Request $request)
    {
        $validatedData = $this->validateRequest($request);
        DB::beginTransaction();
        try {

            $batch = $this->batchRepo->createBatch($validatedData);

            DB::commit();
            return $this->response->success($batch, 'Batch Products added successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error("Batch Creation Failed", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'payload' => $validatedData
        ]);
            return $this->response->error("Failed to create Batch", 500, $e->getMessage());

        }
    }

    public function getBatches(Request $request)
    {
        $batches = $this->batchRepo->getBatches($request);
        return response()->json([
            'data' => $batches
        ]);
    }

    public function getBatchById($id)
    {
        $batch = $this->batchRepo->getBatchItemsById($id);
        return response()->json([
            'data' => $batch
        ]);
    }
}