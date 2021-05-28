<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'payee' => $this->payee_id,
            'payer' => $this->payer_id,
            'value' => $this->value,
            'status' => $this->transaction_status_id,
            'created_at' => $this->created_at
        ];
    }
}
