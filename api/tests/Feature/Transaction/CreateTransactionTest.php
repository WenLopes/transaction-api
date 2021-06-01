<?php

namespace Tests\Feature\Transaction;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateTransactionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function test_should_failed_if_payer_has_no_balance()
    {
        //Given
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

        $expectedJsonStructure = [
            'errors' => [
                'payer'
            ]
        ];

        //When
        $response = $this->post('api/transaction', [
            'payee' => $payee->id,
            'payer' => $payer->id,
            'value' => 1000.00
        ]);

        //Then
        $response->assertStatus(422);
        $response->assertJsonStructure($expectedJsonStructure);
        $this->assertDatabaseMissing('transactions', [
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'value'    => 1000.00
        ]);
    }

    /**
     * @test
     */
    public function test_should_failed_if_payer_is_seller()
    {
        //Given
        $payer = User::factory()->create([
            'is_seller' => true,
            'balance' => 100,
            'active' => true
        ]);

        $payee = User::factory()->create([
            'is_seller' => false,
            'balance' => 100,
            'active' => true
        ]);

        $expectedJsonStructure = [
            'errors' => [
                'payer'
            ]
        ];

        //When
        $response = $this->post('api/transaction', [
            'payee' => $payee->id,
            'payer' => $payer->id,
            'value' => 10.00
        ]);

        //Then
        $response->assertStatus(422);
        $response->assertJsonStructure($expectedJsonStructure);
        $this->assertDatabaseMissing('transactions', [
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'value'    => 1000.00
        ]);
    }

    /**
     * @test
     */
    public function test_should_create_transfer_with_valid_data()
    {
        //Given
        $payerInitialBalance = 1000;
        $transactionValue = 100;

        $payer = User::factory()->create([
            'is_seller' => false,
            'balance' => $payerInitialBalance,
            'active' => true
        ]);

        $payee = User::factory()->create([
            'is_seller' => false,
            'balance' => 1,
            'active' => true
        ]);

        $expectedJsonStructure = [
            'id', 
            'payee', 
            'payer',
            'value',
            'status',
            'created_at'
        ];

        //When
        $response = $this->post('api/transaction', [
            'payee' => $payee->id,
            'payer' => $payer->id,
            'value' => $transactionValue
        ]);

        //Then
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedJsonStructure);
        $this->assertEquals( 
            ( $payerInitialBalance - $transactionValue ),
            $payer->fresh()->balance
        );
        $this->assertDatabaseHas('transactions', [
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'value'    => 100.00
        ]);
    }
}
