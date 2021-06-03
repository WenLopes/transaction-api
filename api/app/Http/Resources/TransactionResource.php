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
            'payee' => $this->getPayeeData(),
            'payer' => $this->getPayerData(),
            'value' => $this->value,
            'status' => $this->getStatusData(),
            'created_at' => $this->created_at
        ];
    }

    /**
     * Get payee data
     * @return array
     */
    protected function getPayeeData(): array
    {
        $payee = [
            'id' => $this->payee_id
        ];

        if (isset($this->payee)) {
            $payee = [
                'id' => $this->payee->id,
                'name' => $this->payee->name,
                'document' => $this->payee->document
            ];
        }

        return $payee;
    }

    /**
     * Get payer data
     * @return array
     */
    protected function getPayerData(): array
    {
        $payer = [
            'id' => $this->payer_id
        ];

        if (isset($this->payer)) {
            $payer = [
                'id' => $this->payer->id,
                'name' => $this->payer->name,
                'document' => $this->payer->document
            ];
        }

        return $payer;
    }

    /**
     * Get payer data
     * @return array
     */
    protected function getStatusData(): array
    {
        $status = [
            'id' => $this->transaction_status_id
        ];

        if (isset($this->status)) {
            $status = [
                'id' => $this->status->id,
                'description' => $this->status->description
            ];
        }

        return $status;
    }
}
