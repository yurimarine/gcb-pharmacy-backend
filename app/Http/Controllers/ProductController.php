<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\ProductRepository;
use App\Helpers\ApiResponse;

class ProductController extends Controller
{
    protected $productRepo;
    protected $response;

    public function __construct(ProductRepository $productRepo, ApiResponse $response)
    {
        $this->productRepo = $productRepo;
        $this->response = $response;
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'generic_id' => 'nullable|exists:generics,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'manufacturer_id' => 'nullable|exists:manufacturers,id',
            'category_id' => 'nullable|exists:categories,id',
            'brand_name' => 'required|string|max:255',
            'dosage_form' => 'nullable|string|max:255',
            'packaging_type' => 'nullable|string|max:255',
            'packaging_amount' => 'nullable|integer',
            'volume_amount' => 'nullable|numeric:8,2',
            'volume_unit' => 'nullable|string|max:255',
            'unit_cost' => 'nullable|numeric:8,2',
            'barcode' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);
    }

    public function addProduct(Request $request)
    {
        $validatedData = $this->validateRequest($request);
        DB::beginTransaction();
        try {

            $product = $this->productRepo->createProduct($validatedData);

            DB::commit();
            return $this->response->success($product, 'Product added successfully');

        } catch (\Exception $e) {

            DB::rollBack();
            return $this->response->error("Failed to create Product", 500, $e->getMessage());

        }
    }

    public function updateProduct(Request $request, $id)
    {
        $validated = $this->validateRequest($request);

        DB::beginTransaction();
        try {

            $product = $this->productRepo->updateProduct($id, $validated);

            DB::commit();
            return $this->response->success($product, "Product successfully updated", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response->error("Failed to update Product", 500, $e->getMessage());
        }
    }

    public function deleteProduct($id)
    {
        $product = $this->productRepo->deleteProduct($id);
        return $this->response->success($product, "Product successfully deleted", 200);
    }

    public function getProducts()
    {
        $products = $this->productRepo->getProducts();
        return $this->response->success($products, "Products retrieved successfully", 200);
    }


    public function getProductById($id)
    {
        $product = $this->productRepo->getProductById($id);
        return $this->response->success($product, "Product retrieved successfully", 200);
    }



}
