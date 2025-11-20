<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\SupplierRepository  ;
use App\Helpers\ApiResponse;

class SupplierController extends Controller
{
    protected $supplierRepo;
    protected $response;

    public function __construct(SupplierRepository $supplierRepo, ApiResponse $response)
    {
        $this->supplierRepo = $supplierRepo;
        $this->response = $response;
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|digits:11',
            'address' => 'nullable|string|max:255',
        ]);

    }

    public function addSupplier(Request $request)
    {
        $validatedData = $this->validateRequest($request);
        DB::beginTransaction();
        try {

            $supplier = $this->supplierRepo->createSupplier($validatedData);

            DB::commit();
            return $this->response->success($supplier, 'Supplier added successfully');

        } catch (\Exception $e) {

            DB::rollBack();
            return $this->response->error("Failed to create Supplier", 500, $e->getMessage());

        }

    }

    public function updateSupplier(Request $request, $id)
    {
        $validated = $this->validateRequest($request);

        DB::beginTransaction();
        try {

            $supplier = $this->supplierRepo->updateSupplier($id, $validated);

            DB::commit();
            return $this->response->success($supplier, "Supplier successfully updated", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response->error("Failed to update Supplier", 500, $e->getMessage());
        }
    }

    public function deleteSupplier($id)
    {

            $supplier = $this->supplierRepo->deleteSupplier($id);
            return $this->response->success($supplier, "Supplier successfully deleted", 200);

    }

    public function fetchSuppliers(Request $request)
    {
        $suppliers = $this->supplierRepo->fetch($request);
        return response()->json([
            'data' => $suppliers
        ]);
    }

}
