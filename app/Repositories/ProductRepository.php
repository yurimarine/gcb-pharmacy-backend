<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\InventoryRepository;

class ProductRepository extends BaseRepositoryInterface
{
    protected $model;
    protected $inventoryRepository;

    public function __construct(Product $product, InventoryRepository $inventoryRepository)
    {
        $this->model = $product;
        $this->inventoryRepository = $inventoryRepository;
    }

    private function generateSku(Product $product)
    {
        $sanitize = function ($text) {
            return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $text));
        };

        $genericName = $product->generic->name ?? 'GEN';
        $brandName   = $product->brand_name ?? 'BRAND';
        $form        = $product->dosage_form ?? 'FORM';

        if ($product->volume_amount && $product->volume_unit) {
            $volume = $product->volume_amount . strtoupper($product->volume_unit);
        } else {
            $volume = 'NA';
        }

        $genericCode = $sanitize(substr($genericName, 0, 4));
        $brandCode   = $sanitize($brandName);
        $formCode    = $sanitize(substr($form, 0, 3));
        $count    = Product::count() + 1;
        $sequence = str_pad($count, 5, '0', STR_PAD_LEFT);

        return "{$genericCode}-{$brandCode}-{$formCode}-{$volume}-{$sequence}";
    }

    public function createProduct(array $data)
    {
        $product = $this->model->create($data);
        $product->load(['generic', 'category', 'supplier', 'manufacturer']);
        $product->sku = $this->generateSku($product);
        $product->save();
        $this->inventoryRepository->createInventory($product->id);
        return $product->fresh();
    }

    public function updateProduct(int $id, array $data)
    {
        $product = $this->model->findOrFail($id);
        unset($data['sku']);
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
            'generic:id,name',
            'category:id,name',
            'supplier:id,name',
            'manufacturer:id,name'
        ])->get();
    }
}