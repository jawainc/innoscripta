<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompany extends FormRequest
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
     * Validates Company
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'customerNumber' => 'required',
            'cost' => 'required',
            'bills.*.date' => 'required',
            'bills.*.amount' => 'required'
        ];
    }
}
