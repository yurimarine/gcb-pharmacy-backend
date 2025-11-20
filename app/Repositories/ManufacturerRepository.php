<?php

namespace App\Repositories;

use App\Models\Manufacturer;
use App\Repositories\BaseRepositoryInterface;
use Illuminate\Http\Request;

class ManufacturerRepository extends BaseRepositoryInterface
{
    protected $model;

    public function __construct(Manufacturer $manufacturer)
    {
        $this->model = $manufacturer;
    }

    public function createManufacturer(array $data)
    {
        if ($this->model->where('name', $data['name'])->exists()) {
            throw new \Exception('Manufacturer name already exists');
        }

        $manufacturer = $this->model->create($data);

        return $manufacturer->fresh();
    }

    public function updateManufacturer(int $id, array $data)
    {
        $manufacturer = $this->model->findOrFail($id);
        $manufacturer->update($data);
        return $manufacturer->fresh();
    }

    public function deleteManufacturer(int $id): bool
    {
        $manufacturer = $this->model->findOrFail($id);
        return (bool) $manufacturer->delete();
    }

}