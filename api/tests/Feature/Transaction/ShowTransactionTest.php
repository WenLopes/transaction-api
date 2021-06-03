<?php

namespace Tests\Feature;

use App\Models\Transaction\Transaction;
use App\Models\User\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowTransactionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function test_should_return_transaction_with_valid_transaction_id()
    {
        $payer = User::factory()->create([
            'is_seller' => false,
            'balance' => 100,
            'active' => true
        ]);

        $payee = User::factory()->create([
            'is_seller' => false,
            'balance' => 100,
            'active' => true
        ]);

        $transaction = Transaction::factory()->create([
            'payer_id'  => $payer->id,
            'payee_id'  => $payee->id,
            'value'     => 10,
            'transaction_status_id' => config('constants.transaction.status.WAITING')
        ]);

        $response = $this->get("api/transaction/{$transaction->id}");
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function test_should_not_return_transaction_with_invalid_transaction_id()
    {
        $transactionInvalidId = intval(INF);
        $response = $this->get("api/transaction/$transactionInvalidId");
        $response->assertStatus(404);
        $response->assertExactJson([
            'message' => 'Transaction not found'
        ]);
    }
}
