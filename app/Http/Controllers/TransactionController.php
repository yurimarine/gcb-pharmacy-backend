<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\TransactionRepository;
use App\Helpers\ApiResponse;


class TransactionController extends Controller
{
    protected $transactionRepo;
    protected $response;

    public function __construct(TransactionRepository $transactionRepo, ApiResponse $response)
    {
        $this->transactionRepo = $transactionRepo;
        $this->response = $response;
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'user_id' => 'required|exists:users,id',
            'pharmacy_id' => 'required|exists:pharmacies,id'
        ]);

    }

}
