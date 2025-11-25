<?php

namespace App\Repositories;

use App\Models\Pharmacy;
use App\Repositories\BaseRepositoryInterface;
use Illuminate\Http\Request;

class PharmacyRepository extends BaseRepositoryInterface
{
    protected $model;

    public function __construct(Pharmacy $pharmacy)
    {
        $this->model = $pharmacy;
    }

    public function createPharmacy(array $data)
    {
        if ($this->model->where('name', $data['name'])->exists()) {
            throw new \Exception('Pharmacy name already exists');
        }

        $pharmacy = $this->model->create($data);

        return $pharmacy->fresh();
    }

    public function updatePharmacy(int $id, array $data)
    {
        $pharmacy = $this->model->findOrFail($id);
        $pharmacy->update($data);
        return $pharmacy->fresh();
    }

    public function deletePharmacy(int $id): bool
    {
        $pharmacy = $this->model->findOrFail($id);
        return (bool) $pharmacy->delete();
    }

    public function getPharmacies(Request $request)
    {
        return $this->model->get();
    }
}