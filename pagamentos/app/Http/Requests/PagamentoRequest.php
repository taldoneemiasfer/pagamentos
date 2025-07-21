<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
class PagamentoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * 
     */
    public function rules(): array
    {
        return [
            'produtos' => ['required', 'array', 'min:1'],
            'forma_pagamento' => ['required', Rule::in(['PIX', 'CREDIT_CARD', 'BOLETO'])],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->sometimes('holderName', ['required', 'regex:/^[\pL\s]+$/u'], function ($input) {
            return $input->forma_pagamento === 'CREDIT_CARD';
        });

        $validator->sometimes('number', ['required', 'digits_between:13,19'], function ($input) {
            return $input->forma_pagamento === 'CREDIT_CARD';
        });

        $validator->sometimes('expiryMonth', ['required', 'digits_between:1,2', 'string', 'between:01,12'], function ($input) {
            return $input->forma_pagamento === 'CREDIT_CARD';
        });

        $validator->sometimes('expiryYear', ['required', 'digits:4', 'integer'], function ($input) {
            return $input->forma_pagamento === 'CREDIT_CARD';
        });

        $validator->sometimes('cvv', ['required', 'digits:3'], function ($input) {
            return $input->forma_pagamento === 'CREDIT_CARD';
        });
        /*$validator->sometimes('params.cpfCnpj', ['required', 'regex:/^\d{11,14}$/'], function ($input) {
            return $input->forma_pagamento === 'CREDIT_CARD';
        });
        $validator->sometimes('params.mobilePhone', ['required', 'regex:/^\d{10,11}$/'], function ($input) {
            return $input->forma_pagamento === 'CREDIT_CARD';
        });
        $validator->sometimes('params.postalCode', ['required', 'digits:8'], function ($input) {
            return $input->forma_pagamento === 'CREDIT_CARD';
        });*/
    }
}
