<?php

namespace App\Jobs\Transaction\Transfer;

use App\Events\Transaction\Transfer\ProcessTransferFailed;
use App\Exceptions\Transaction\Transfer\ProcessTransferJobException;
use App\Models\Transaction\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Transaction\Authorization\AuthorizationServiceInterface;
use App\Services\Transaction\Transfer\CompleteTransferServiceInterface;
use App\Services\Transaction\Transfer\RollbackTransferServiceInterface;

class ProcessTransferJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** @var int */
    public $tries = 3;

    /** @var int */
    public $maxExceptions = 3;

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
    public function handle(
        AuthorizationServiceInterface $authorizationService,
        CompleteTransferServiceInterface $completeTransfer,
        RollbackTransferServiceInterface $rollbackTransfer
    ): bool {
        if ($authorizationService->authorized()) {
            return $completeTransfer->handleCompleteTransfer($this->transaction->fresh());
        }

        return $rollbackTransfer->handleRollbackTransfer($this->transaction->fresh());
    }

    /**
     * Handle a job failure.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        event(new ProcessTransferFailed($this->transaction->fresh()));
        throw new ProcessTransferJobException($exception->getMessage());
    }
}
