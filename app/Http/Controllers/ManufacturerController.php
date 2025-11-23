<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\ManufacturerRepository  ;
use App\Helpers\ApiResponse;

class ManufacturerController extends Controller
{
    protected $manufacturerRepo;
    protected $response;

    public function __construct(ManufacturerRepository $manufacturerRepo, ApiResponse $response)
    {
        $this->manufacturerRepo = $manufacturerRepo;
        $this->response = $response;
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

    }

    public function addManufacturer(Request $request)
    {
        $validatedData = $this->validateRequest($request);
        DB::beginTransaction();
        try {

            $manufacturer = $this->manufacturerRepo->createManufacturer($validatedData);

            DB::commit();
            return $this->response->success($manufacturer, 'Manufacturer added successfully');

        } catch (\Exception $e) {

            DB::rollBack();
            return $this->response->error("Failed to create Manufacturer", 500, $e->getMessage());

        }

    }

    public function updateManufacturer(Request $request, $id)
    {
        $validated = $this->validateRequest($request);

        DB::beginTransaction();
        try {

            $manufacturer = $this->manufacturerRepo->updateManufacturer($id, $validated);

            DB::commit();
            return $this->response->success($manufacturer, "Manufacturer successfully updated", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response->error("Failed to update Manufacturer", 500, $e->getMessage());
        }
    }

    public function deleteManufacturer($id)
    {

            $manufacturer = $this->manufacturerRepo->deleteManufacturer($id);
            return $this->response->success($manufacturer, "Manufacturer successfully deleted", 200);

    }

    public function getManufacturers(Request $request)
    {
        $manufacturers = $this->manufacturerRepo->getManufacturers($request);
        return response()->json([
            'data' => $manufacturers
        ]);
    }

     public function getManufacturerById($id)
    {
        $manufacturer = $this->manufacturerRepo->getManufacturerById($id);
        return $this->response->success($manufacturer, "Manufacturer retrieved successfully", 200);
    }

}
