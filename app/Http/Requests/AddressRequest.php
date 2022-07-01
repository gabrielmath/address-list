<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'cidade_id'  => ['required', 'numeric', 'exists:App\Models\City,id'],
            'logradouro' => ['required', 'min:3', 'max:100'],
            'numero'     => ['required', 'max:20'],
            'bairro'     => ['required', 'min:3', 'max:100'],
        ];
    }
}
