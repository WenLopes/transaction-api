<?php

namespace App\Jobs\Transaction\Transfer;

use App\Models\Transaction\Transaction;
use App\Services\Transaction\Authorization\AuthorizationServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTransferJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int */
    public $tries = 5;

    /** @var int */
    public $maxExceptions = 5;

    /** @var Transaction */
    public $transaction;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(AuthorizationServiceInterface $authorizationService) : bool
    {
        try {
            return true;
        } catch (\Exception $e){
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(\Exception $e)
    {
    }
}
