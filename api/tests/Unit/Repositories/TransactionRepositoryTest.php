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
    public function test_status_should_be_success_when_transaction_is_waiting()
    {
        $transactionWaiting = Transaction::factory()->create();
        $query = $this->transactionRepo->setAsSuccess($transactionWaiting->id);
        $this->assertEquals(true, $query);
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
