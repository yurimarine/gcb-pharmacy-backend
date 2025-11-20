<?php

namespace App\Repositories;

use App\Models\Supplier;
use App\Repositories\BaseRepositoryInterface;
use Illuminate\Http\Request;

class SupplierRepository extends BaseRepositoryInterface
{
    protected $model;

    public function __construct(Supplier $supplier)
    {
        $this->model = $supplier;
    }

    public function createSupplier(array $data)
    {
        if ($this->model->where('name', $data['name'])->exists()) {
            throw new \Exception('Supplier name already exists');
        }

        $supplier = $this->model->create($data);

        return $supplier->fresh();
    }

    public function updateSupplier(int $id, array $data)
    {
        $supplier = $this->model->findOrFail($id);
        $supplier->update($data);
        return $supplier->fresh();
    }

    public function deleteSupplier(int $id): bool
    {
        $supplier = $this->model->findOrFail($id);
        return (bool) $supplier->delete();
    }

}