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

    public function setAsSuccessDataProvider() {

        $this->refreshApplication();

        $transactionWaiting = Transaction::factory()->create();

        $transactionSuccess = Transaction::factory()->create([
            'transaction_status_id' => config('constants.transaction.status.SUCCESS')
        ]);

        return [

            'invalid_transaction' => [ 
                'transactionId' => intval(INF),
                'expected' => false
            ],

            'transaction_in_waiting_status' => [ 
                'transactionId' => $transactionWaiting->id,
                'expected' => true
            ],

            'transaction_in_success_status' => [ 
                'transactionId' => $transactionSuccess->id,
                'expected' => false
            ]
        ];

    }

    public function setAsErrorDataProvider() {

        $this->refreshApplication();

        $transactionWaiting = Transaction::factory()->create();

        $transactionError = Transaction::factory()->create([
            'transaction_status_id' => config('constants.transaction.status.ERROR')
        ]);

        return [
            'invalid_transaction' => [ 
                'transactionId' => intval(INF),
                'expected' => false
            ],

            'transaction_in_waiting_status' => [ 
                'transactionId' => $transactionWaiting->id,
                'expected' => true
            ],

            'transaction_in_error_status' => [ 
                'transactionId' => $transactionError->id,
                'expected' => false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider setAsSuccessDataProvider
     */
    public function test_set_transaction_as_success(int $transactionId, bool $expected)
    {
        $query = $this->transactionRepo->setAsSuccess($transactionId);
        $this->assertEquals($expected, $query);
    }

    /**
     * @test
     * @dataProvider setAsErrorDataProvider
     */
    public function test_set_transaction_as_error(int $transactionId, bool $expected)
    {
        $query = $this->transactionRepo->setAsError($transactionId);
        $this->assertEquals($expected, $query);
    }
}
