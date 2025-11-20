<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\CategoryRepository  ;
use App\Helpers\ApiResponse;

class CategoryController extends Controller
{
    protected $categoryRepo;
    protected $response;

    public function __construct(CategoryRepository $categoryRepo, ApiResponse $response)
    {
        $this->categoryRepo = $categoryRepo;
        $this->response = $response;
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

    }

    public function addCategory(Request $request)
    {
        $validatedData = $this->validateRequest($request);
        DB::beginTransaction();
        try {

            $category = $this->categoryRepo->createCategory($validatedData);

            DB::commit();
            return $this->response->success($category, 'Category added successfully');

        } catch (\Exception $e) {

            DB::rollBack();
            return $this->response->error("Failed to create Category", 500, $e->getMessage());

        }

    }

    public function updateCategory(Request $request, $id)
    {
        $validated = $this->validateRequest($request);

        DB::beginTransaction();
        try {

            $category = $this->categoryRepo->updateCategory($id, $validated);

            DB::commit();
            return $this->response->success($category, "Category successfully updated", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response->error("Failed to update Category", 500, $e->getMessage());
        }
    }

    public function deleteCategory($id)
    {

            $category = $this->categoryRepo->deleteCategory($id);
            return $this->response->success($category, "Category successfully deleted", 200);

    }

    public function getCategories(Request $request)
    {
        $categories = $this->categoryRepo->getCategories($request);
        return response()->json([
            'data' => $categories
        ]);
    }

}