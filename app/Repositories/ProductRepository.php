<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\InventoryRepository;
use Illuminate\Http\Request;

class ProductRepository extends BaseRepositoryInterface
{
    protected $model;
    protected $inventoryRepository;

    public function __construct(Product $product, InventoryRepository $inventoryRepository)
    {
        $this->model = $product;
        $this->inventoryRepository = $inventoryRepository;
    }

    public function createProduct(array $data)
    {

        $product = $this->model->create($data);
        $this->inventoryRepository->createInventory($product->id);
        return $product->fresh();
    }

    public function updateProduct(int $id, array $data)
    {
        $product = $this->model->findOrFail($id);
        $product->update($data);
        return $product->fresh();
    }

    public function deleteProduct(int $id): bool
    {
        $product = $this->model->findOrFail($id);
        return (bool) $product->delete();
    }

    public function getProducts()
    {
        return $this->model->with([
            'generic:id,name',       // select only id and name
            'category:id,name',      // select only id and name
            'supplier:id,name',      // select only id and name
            'manufacturer:id,name'   // select only id and name
        ])->get();
    }
}
