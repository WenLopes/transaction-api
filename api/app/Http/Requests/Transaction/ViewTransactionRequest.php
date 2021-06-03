<?php

namespace App\Http\Requests\Transaction;

use App\Http\Requests\BaseFormRequest;

class ViewTransactionRequest extends BaseFormRequest
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
            'transaction' => [
                'bail',
                'integer',
                'required'
            ]
        ];
    }

    /**
     * Add parameters to be validated
     *
     * @return array
     */
    public function all($keys = null)
    {
        return array_replace_recursive(
            parent::all(),
            $this->route()->parameters()
        );
    }
}
