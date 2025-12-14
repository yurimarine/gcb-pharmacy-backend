<?php

namespace App\Repositories;

use App\Models\TransactionItem;
use App\Repositories\BaseRepositoryInterface;
use Illuminate\Http\Request;


class TransactionItemRepository extends BaseRepositoryInterface

{
    protected $model;

    public function __construct(TransactionItem $transactionItem)
    {
        $this->model = $transactionItem;
    }

}
