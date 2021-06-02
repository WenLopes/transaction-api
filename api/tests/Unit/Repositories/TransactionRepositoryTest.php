<?php

namespace Tests\Unit\Repositories;

use App\Models\Transaction\Transaction;
use App\Repositories\Transaction\TransactionRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TransactionRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /** @var TransactionRepository */
    protected $transactionRepo;

    protected function setUp() : void
    {
        parent::setUp();
        $this->transactionRepo = app(TransactionRepository::class);
    }

    /**
     * @test
     */
    public function test_status_should_not_set_as_success_when_transaction_is_already_successful()
    {
        $transactionSuccess = Transaction::factory()->create([
            'transaction_status_id' => config('constants.transaction.status.SUCCESS')
        ]);

        $query = $this->transactionRepo->setAsSuccess($transactionSuccess->id);
        $this->assertEquals(false, $query);
    }

    /**
     * @test
     */
    public function test_status_should_be_success_when_transaction_is_waiting()
    {
        $transactionWaiting = Transaction::factory()->create();
        $query = $this->transactionRepo->setAsSuccess($transactionWaiting->id);
        $this->assertEquals(true, $query);
    }

    /**
     * @test
     */
    public function test_status_should_not_set_as_error_when_transaction_is_already_error()
    {
        $transactionError = Transaction::factory()->create([
            'transaction_status_id' => config('constants.transaction.status.ERROR')
        ]);
        
        $query = $this->transactionRepo->setAsError($transactionError->id);
        $this->assertEquals(false, $query);
    }

    /**
     * @test
     */
    public function test_status_should_be_error_when_transaction_is_waiting(){
        $transactionWaiting = Transaction::factory()->create();
        $query = $this->transactionRepo->setAsError($transactionWaiting->id);
        $this->assertEquals(true, $query);
    }
}
