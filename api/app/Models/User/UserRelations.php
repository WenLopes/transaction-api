<?php 

namespace App\Models\User;

trait UserRelations {

    public function paid_transactions()
    {
        return $this->hasMany(\App\Models\Transaction\Transaction::class, 'payer_id', 'id');
    }

    public function received_transactions()
    {
        return $this->hasMany(\App\Models\Transaction\Transaction::class, 'payee_id', 'id');
    }

}