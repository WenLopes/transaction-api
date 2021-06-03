<?php

namespace App\Http\Controllers;

use App\Exceptions\Transaction\NotFoundTransactionException;
use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Http\Requests\Transaction\ViewTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Services\Transaction\Transfer\CreateTransferServiceInterface;

class TransactionController extends Controller
{

    /**     
     * @OA\Get(
     *     path="/api/transaction/{transaction}",
     *     summary="Transaction view",
     *     description="Specific transaction view from id",
     *     operationId="transaction.view",
     *     tags={"Transaction"},
     * 
     *     @OA\Parameter(
     *        description="Transaction identification", name="transaction", in="path", required=true, @OA\Schema(type="integer")
     *     ),
     * 
     *     @OA\Response(response="200", description="Returns transaction resource"),
     * 
     *     @OA\Response(response="404", description="Transaction not found response")
     * )
     */
    public function view(
        ViewTransactionRequest $request,
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

        $transaction = $createTransferService->createTransfer($payeeId, $payerId, $value);
        return response()->json( new TransactionResource($transaction) );
    }
}
