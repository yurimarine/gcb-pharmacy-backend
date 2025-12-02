<?php

namespace App\Repositories;

use App\Models\Batch;
use App\Repositories\BatchItemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class BatchRepository extends BaseRepositoryInterface
{
    protected $model;
    protected $batchItemRepo;

    public function __construct(Batch $batch, BatchItemRepository $batchItemRepo)
    {
        $this->batchItemRepo = $batchItemRepo;
        $this->model = $batch;
    }

    public function createBatch(array $data)
    {
        $batch = $this->model->create([
            'batch_number' => 'BATCH-' . time(),
            'pharmacy_id' => $data['pharmacy_id'],
            'batch_date' => now(),
        ]);

        $this->batchItemRepo->createBatchItems($batch->id, $data['pharmacy_id'], $data['items']);

        return $batch;
    }

     public function getBatches(Request $request)
    {
        $batches = $this->model->with('pharmacy:id,name')->get();

        return $batches;
    }

        public function getBatchItemsById(int $id)
    {
        $batchItems = $this->batchItemRepo->getBatchItemsById($id);

        return $batchItems;
    }
}