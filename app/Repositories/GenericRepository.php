<?php

namespace App\Repositories;

use App\Models\Generic;
use App\Repositories\BaseRepositoryInterface;
use Illuminate\Http\Request;

class GenericRepository extends BaseRepositoryInterface
{
    protected $model;

    public function __construct(Generic $generic)
    {
        $this->model = $generic;
    }

    public function createGeneric(array $data)
    {
        if ($this->model->where('name', $data['name'])->exists()) {
            throw new \Exception('Generic name already exists');
        }

        $generic = $this->model->create($data);

        return $generic->fresh();
    }

    public function updateGeneric(int $id, array $data)
    {
        $generic = $this->model->findOrFail($id);
        $generic->update($data);
        return $generic->fresh();
    }

    public function deleteGeneric(int $id): bool
    {
        $generic = $this->model->findOrFail($id);
        return (bool) $generic->delete();
    }

    public function getGenerics(Request $request)
    {
        return $this->model->get();
    }

    public function getGenericById(int $id)
    {
        return $this->model->findOrFail($id);
    }
}