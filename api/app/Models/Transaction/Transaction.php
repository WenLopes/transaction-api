<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, TransactionRelations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payee_id',
        'payer_id',
        'value',
        'transaction_status_id',
        'processed_at'
    ];

    /**
     * Returns if transaction has already been processed
     */
    public function alreadyProcessed() : bool
    {
        return $this->transaction_status_id !== config('constants.transaction.status.WAITING');
    }
}
