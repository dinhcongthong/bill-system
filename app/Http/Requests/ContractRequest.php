<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractRequest extends FormRequest
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
            'company_phone'             => 'nullable|numeric',
            'poste_in_charge'           => 'required',
            'representative'            => 'required',
            'payment_times'             => 'required|numeric',
            'contract_start_date'       => 'required|date',
            'service_start_date.*'      => 'required|date',
            'time_service.*'            => 'required|numeric|min:1',
            'service_id.*'              => 'required',
            'scan_file.*'               => 'nullable|mimes:pdf',
            'discount_rate.*'           => 'nullable|numeric|max:100'
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
            'company_phone.numeric'             => 'Company phone is required number',
            'poste_in_charge.required'          => 'Poste person in charge is required',
            'representative.required'           => 'Representative is required',
            'payment_times.numeric'             => 'Payment times is required number',
            'time_service.*.required'           => 'Time service is required',
            'time_service.*.numeric'            => 'Time service must be number',
            'time_service.*.min'                => 'Time service min is 1 month',
            'service_id.*'                      => 'Service is required',
            'scan_file.*'                       => 'Scan file only support pdf file',
            'discount_rate.*'                   => 'Discount rate max is 100%'
        ];
    }
}
