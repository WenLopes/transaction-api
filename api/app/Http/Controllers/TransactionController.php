<?php

namespace App\Http\Controllers;

use App\Exceptions\Transaction\NotFoundTransactionException;
use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Http\Requests\Transaction\ShowTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Services\Transaction\Transfer\CreateTransferServiceInterface;

class TransactionController extends Controller
{

    public function show(
        ShowTransactionRequest $request,
        TransactionRepositoryInterface $transactionRepo
    ) {
        $transaction = $transactionRepo->findById( $request->route('transaction') );

        if(!$transaction){
            throw new NotFoundTransactionException();
        }

        return response()->json( new TransactionResource($transaction) );
    }

    public function create(
        CreateTransactionRequest $request,
        CreateTransferServiceInterface $createTransferService
    ) {
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
