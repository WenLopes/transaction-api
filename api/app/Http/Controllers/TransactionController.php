<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Services\Transaction\Transfer\CreateTransferServiceInterface;

class TransactionController extends Controller
{
    public function create(
        CreateTransactionRequest $request,
        CreateTransferServiceInterface $createTransferService
    )
    {
        $payload = $request->validated();

        [
            'payee' => $payeeId,
            'payer' => $payerId,
            'value' => $value
        ] = $payload;

        $transaction = $createTransferService->handle($payeeId, $payerId, $value);
        return response()->json( new TransactionResource($transaction) );
    }
}
