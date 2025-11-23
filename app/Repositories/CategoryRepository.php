<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\BaseRepositoryInterface;
use Illuminate\Http\Request;

class CategoryRepository extends BaseRepositoryInterface
{
    protected $model;

    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function createCategory(array $data)
    {
        if ($this->model->where('name', $data['name'])->exists()) {
            throw new \Exception('Category name already exists');
        }

        $category = $this->model->create($data);

        return $category->fresh();
    }

    public function updateCategory(int $id, array $data)
    {
        $category = $this->model->findOrFail($id);
        $category->update($data);
        return $category->fresh();
    }

    public function deleteCategory(int $id): bool
    {
        $category = $this->model->findOrFail($id);
        return (bool) $category->delete();
    }

    public function getCategories(Request $request)
    {
        return $this->model->get();
    }


    public function getCategoryById(int $id)
    {
        return $this->model->findOrFail($id);
    }

}