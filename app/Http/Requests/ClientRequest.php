<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name'                      => 'required',
            'tax_code'                  => 'nullable',
            'area'                      => 'required',
            'client_in_charge'          => 'required',
            'phone_client_in_charge'    => 'nullable|numeric',
            'email_client_in_charge'    => 'nullable|email',
            'address_client_in_charge'  => 'required',
            'client_type_id'             => 'required',
        ];
    }

    /**
     * Get the validation messages that apply to the request 
     * 
     * @return array 
     */
    public function messages()
    {
        return [
            'name.required'                      => 'Company name is required',
            'client_in_charge.required'          => 'Contact person is required',
            'address_client_in_charge.required'  => 'Please enter address',
            'email.email'                        => 'Email is wrong',
            'phone_client_in_charge.numeric'     => 'Phone number is not correct',
            'area.required'                      => 'Area client is required',
            'client_type_id.required'            => 'Client type is required'
        ];
    }
}
