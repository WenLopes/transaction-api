<?php

namespace App\Listeners\Transaction\Transfer;

use App\Events\Transaction\Transfer\ProcessTransferFailed;
use App\Exceptions\Transaction\Transfer\RollbackTransferOnProcessFailedException;
use App\Services\Transaction\Transfer\RollbackTransferServiceInterface;

class RollbackTransferOnProcessFailed
{

    /** @var RollbackTransferServiceInterface */
    protected $rollbackTransfer;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(RollbackTransferServiceInterface $rollbackTransfer)
    {
        $this->rollbackTransfer = $rollbackTransfer;
    }

    /**
     * Handle the event.
     *
     * @param ProcessTransferFailed $event
     * @return void
     */
    public function handle($event) : void
    {
        try {
            $this->rollbackTransfer->handleRollbackTransfer($event->getTransaction());
        } catch (\Exception $e){
            throw new RollbackTransferOnProcessFailedException($e->getMessage());
        }
    }
}
