<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\CreateTransactionRequest;

class TransactionController extends Controller
{
    public function create(CreateTransactionRequest $request)
    {
        $data = $request->validated();
        dd($data);
    }
}
