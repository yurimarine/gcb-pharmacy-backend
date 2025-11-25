<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\PharmacyRepository  ;
use App\Helpers\ApiResponse;

class PharmacyController extends Controller
{
    protected $pharmacyRepo;
    protected $response;

    public function __construct(PharmacyRepository $pharmacyRepo, ApiResponse $response)
    {
        $this->pharmacyRepo = $pharmacyRepo;
        $this->response = $response;
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'manager' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|digits:11',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

    }

    public function addPharmacy(Request $request)
    {
        $validatedData = $this->validateRequest($request);
        DB::beginTransaction();
        try {

            $pharmacy = $this->pharmacyRepo->createPharmacy($validatedData);

            DB::commit();
            return $this->response->success($pharmacy, 'Pharmacy added successfully');

        } catch (\Exception $e) {

            DB::rollBack();
            return $this->response->error("Failed to create pharmacy", 500, $e->getMessage());

        }

    }

    public function updatePharmacy(Request $request, $id)
    {
        $validated = $this->validateRequest($request);

        DB::beginTransaction();
        try {

            $pharmacy = $this->pharmacyRepo->updatePharmacy($id, $validated);

            DB::commit();
            return $this->response->success($pharmacy, "Pharmacy successfully updated", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response->error("Failed to update pharmacy", 500, $e->getMessage());
        }
    }

    public function deletePharmacy($id)
    {

            $pharmacy = $this->pharmacyRepo->deletePharmacy($id);
            return $this->response->success($pharmacy, "Pharmacy successfully deleted", 200);

    }

    public function getPharmacies(Request $request)
    {
        $pharmacies = $this->pharmacyRepo->getPharmacies($request);
        return response()->json([
            'data' => $pharmacies
        ]);
    }

}