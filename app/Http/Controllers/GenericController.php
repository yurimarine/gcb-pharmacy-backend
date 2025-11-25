<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\GenericRepository  ;
use App\Helpers\ApiResponse;

class GenericController extends Controller
{
    protected $genericRepo;
    protected $response;

    public function __construct(GenericRepository $genericRepo, ApiResponse $response)
    {
        $this->genericRepo = $genericRepo;
        $this->response = $response;
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

    }

    public function addGeneric(Request $request)
    {
        $validatedData = $this->validateRequest($request);
        DB::beginTransaction();
        try {

            $generic = $this->genericRepo->createGeneric($validatedData);

            DB::commit();
            return $this->response->success($generic, 'Generic added successfully');

        } catch (\Exception $e) {

            DB::rollBack();
            return $this->response->error("Failed to create Generic", 500, $e->getMessage());

        }

    }

    public function updateGeneric(Request $request, $id)
    {
        $validated = $this->validateRequest($request);

        DB::beginTransaction();
        try {

            $generic = $this->genericRepo->updateGeneric($id, $validated);

            DB::commit();
            return $this->response->success($generic, "Generic successfully updated", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response->error("Failed to update Generic", 500, $e->getMessage());
        }
    }

    public function deleteGeneric($id)
    {

            $generic = $this->genericRepo->deleteGeneric($id);
            return $this->response->success($generic, "Generic successfully deleted", 200);

    }

    public function getGenerics(Request $request)
    {
        $generics = $this->genericRepo->getGenerics($request);
        return response()->json([
            'data' => $generics
        ]);
    }

     public function getGenericById ($id)
    {
        $generic = $this->genericRepo->getGenericById($id);
        return $this->response->success($generic, "Generic retrieved successfully", 200);
    }

}