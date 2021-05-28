<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Services\Transaction\Transfer\CreateTransferService;

class TransactionController extends Controller
{
    public function create(
        CreateTransactionRequest $request,
        CreateTransferService $createTransferService
    )
    {
        $payload = $request->validated();

        [
            'payee' => $payeeId, 
            'payer' => $payerId,
            'value' => $value
        ] = $payload;

        $createTransferService->handle($payeeId, $payerId, $value);
    }
}
