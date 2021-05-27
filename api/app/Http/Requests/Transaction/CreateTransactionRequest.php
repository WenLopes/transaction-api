<?php

namespace App\Http\Requests\Transaction;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;


class CreateTransactionRequest extends BaseFormRequest
{
    /**
     * Determine if the User is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'value' => [
                'required', 
                'numeric',
                'min:0.01'
            ],
            'payer' => [
                'required',
                'integer',
                'user_active',
                'not_seller'
            ],
            'payee' => [
                'required',
                'integer',
                'exists:users,id',
                'different:payer'
            ]
        ];
    }
}
