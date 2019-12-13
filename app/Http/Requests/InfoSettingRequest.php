<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InfoSettingRequest extends FormRequest
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
            'name_poste'     => 'required',
            'address_poste'  => 'required',
            'vat_rate'       => 'required|numeric',
            'tax_code'       => 'required',
            'phone_poste'    => 'required|numeric',
            'email_poste'    => 'required|email',
            'exchange_rate'  => 'required|numeric',
            'bank_name'      => 'required',
            'acc_bank_name'  => 'required',
            'acc_bank_number'=> 'required|numeric',
            'area_name'      => 'required|max:30',
            'path_logo_file' => 'nullable|image|mimes:jpeg,png|max:2049', 
        ];
    }
    
    public function messages()
    {
        return [
            'name_poste.required'     => 'Name can not be null!',
            'address_poste.required'  => 'Address can not be null',
            'vat_rate.required'       => 'Vat rate can not be null',
            'vat_rate.numeric'        => 'Vat rate require a number',
            'tax_code.required'       => 'Tax code can not be null',
            'phone_poste.required'    => 'Phone can not be null',
            'phone_poste.numeric'     => 'Phone require a number',
            'email_poste.required'    => 'Email can not be null',
            'email_poste.email'       => 'Email not available',
            'exchange_rate.required'  => 'Exchange rate can not be null',
            'bank_name.required'      => 'Bank name can not be null',
            'acc_bank_name.required'  => 'Account bank name can not be null',
            'acc_bank_number.numeric' => 'Account bank must be format number',
            'area_name.required'      => 'Area name can not be null',
            'path_logo_file.image'    => 'Logo required a photo',
            'path_logo_file.max'      => 'Logo size too big',
            'path_logo_file.mimes'    => 'Logo photo just support path file jpg or png'
        ];
    }
}
