<?php

namespace App\Http\Controllers;

use App\Http\Models\Contract;
use App\Http\Models\Service;
use App\Http\Models\ContractService;
use App\Http\Models\Client;
use App\Http\Requests\ContractRequest;
use App\Http\Models\Invoice;
use App\Http\Models\Setting;
use App\Http\Models\StoreBranch;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Traits\WriteActivityLog;

use File;
use App\Http\Models\Galleries;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Config;

class ContractController extends Controller
{
    use writeActivityLog;
    
    public function getNew($client_id) {
        if (!isset($client_id)) {
            return 'can not find any client!';
        }
        $this->data['vat_status']               = old('vat_status' ,'');
        $this->data['contract_start_date']      = old('contract_start_date' ,'');
        $this->data['contract_code']            = old('contract_code' ,'');
        $this->data['representative']           = old('representative' ,'');
        $this->data['company_phone']            = old('company_phone' ,'');
        $this->data['end_date']                 = old('end_date' ,'');
        $this->data['payment_times']            = old('payment_times' ,'');
        $this->data['poste_in_charge']          = old('poste_in_charge' ,'');
        $this->data['contract_note']            = old('contract_note' ,'');
        $this->data['discount_rate']            = old('discount_rate');
        $client = Client::find($client_id);
        $this->data['service_list'] = Service::all();
        
        $this->data['store_branch_list'] = StoreBranch::where('client_id', $client_id)->get();
        
        $this->data['client_id'] = $client_id;
        $this->data['client_name'] = $client->name;
        
        if(old('service_id')) {
            $service_ids = old('service_id');
            $time_service = old('time_service');
            $service_start_date = old('service_start_date');
            
            $contract_service = [];
            foreach($service_ids as $key => $id) {
                $service_contract_item = new ContractService();
                $service_contract_item->id = $id;
                $service_contract_item->quantity_months = $time_service[$key];
                $service_contract_item->service_start_date = $service_start_date[$key];
                
                $contract_service[] = $service_contract_item;
            }
            
            $this->data['contract_service'] = $contract_service;
        }
        $this->data['permission'] = config('permission.id');
        
        return view('contract.add-new')->with($this->data);
    }
    
    public function getUpdate ($id, $client_id) {
        $contract = Contract::find($id);
        
        // return $id;
        
        if (is_null($contract)) {
            return back()->withInput()->withErrors(['errors' => 'Can not find any contract_id']);
        }
        
        $contract_service = ContractService::where('contract_id', $id)->get();
        if ($contract_service->count() < 0) {
            return back()->withInput()->withErrors(['errors' => 'Can not find any contract_id']);
        }
        
        $scan_file = Galleries::where('contract_id', $id)->get();
        if (!is_null($scan_file)) {
            $this->data['scan_file'] = $scan_file;
        }
        
        $this->data['service_list'] = Service::all();
        
        $this->data['contract_service'] = $contract_service;
        $this->data['contract'] = $contract;
        $this->data['contract_id'] = $id;
        
        $client = Client::find($client_id);
        $this->data['client_name'] = $client->name;
        
        $store_branch_id = [];
        $store_branch_id = explode(',', $contract->store_branch_id);
        
        $store_branch_list = StoreBranch::where('client_id', $client_id)->get();
        if ($store_branch_list->count()) {
            $this->data['store_branch_list'] = $store_branch_list;
        }
        
        $this->data['store_branch_id'] = $store_branch_id;
        $this->data['default_store_branch_id'] = $contract->store_branch_id;
        
        // data return view
        $this->data['client_id']                = old('client_id', $client_id);
        $this->data['vat_status']               = old('vat_status', $contract->vat_status);
        $this->data['contract_start_date']      = old('contract_start_date', $contract->start_date);
        $this->data['contract_code']            = old('contract_code', $contract->contract_code);
        $this->data['status']                   = old('status', $contract->status);
        $this->data['representative']           = old('representative', $contract->representative);
        $this->data['company_phone']            = old('company_phone', $contract->company_phone);
        $this->data['end_date']                 = old('end_date', $contract->quantity_months);
        $this->data['payment_times']            = old('payment_times', $contract->payment_times);
        $this->data['poste_in_charge']          = old('poste_in_charge', $contract->poste_in_charge);
        $this->data['contract_note']            = old('contract_note', $contract->contract_note);
        $this->data['payment_type']             = old('contract_note', $contract->payment_type);

        $this->data['permission'] = config('permission.id');

        return view('contract.add-new')->with($this->data);
    }
    
    public function postNew (ContractRequest $request) {
        $dateContract = $request->end_date;
        
        $store_branch_id = '';
        if ($request->has('store_branch_id')) {
            $store_branch_id  = implode(',', $request->store_branch_id);
        }
        
        // check payment time and time contract
        $payment = $dateContract / $request->payment_times;
        if (is_float($payment)) {
            return back()->withInput()->withErrors(['errors' => 'Payment times or time contract not available']);
        }
        
        // delete old contract to create new
        if (isset($request->contract_id)) {
            $contract = Contract::find($request->contract_id)->forceDelete();
            $contract_service = ContractService::where('contract_id', $request->contract_id)->delete();
            $invoice = Invoice::where('contract_id', $request->contract_id)->delete();
            $gallery = Galleries::where('contract_id', $request->contract_id)->get();
            if ($gallery->count()) {
                $file = File::delete('scan_files/' . $gallery->name . '.' . $gallery->path);
                $gallery = $gallery->delete();
                if (!$contract || !$invoice || !$contract_service || !$gallery) {
                    return back()->withInput()->withErrors(['errors' => 'Error!']);
                }
            }
        }
        $client_id = $request->client_id;
        $startDateContract = $request->contract_start_date;
        
        $dateNow = time();
        $endDateContract = date('Y-m-d', strtotime('+' . $dateContract . ' Month', $dateNow));
        
        $vat_status = $request->vat_status;
        if ($request->vat_status == '') {
            $vat_status = "no";
        }
        
        $dataContract = [
            'client_id'                 => $client_id,
            'contract_code'             => $request->contract_code,
            'company_phone'             => $request->company_phone,
            'poste_in_charge'           => $request->poste_in_charge,
            'company_person_in_charge'  => $request->company_person_in_charge,
            'status'                    => $request->has('status') ? $request->status : 'doing',
            'start_date'                => $request->contract_start_date,
            'end_date'                  => $endDateContract,
            'vat'                       => Client::find($client_id)->getArea->vat_rate,
            'vat_status'                => $request->vat_status,                     // important
            'payment_times'             => $request->payment_times,
            'contract_note'             => $request->contract_note,
            'payment_type'              => $request->payment_type,
            'representative'            => $request->representative,
            'store_branch_id'           => $store_branch_id,
            'quantity_months'           => $dateContract,
            'exchange_rate'             => Client::find($client_id)->getArea->exchange_rate, // important              
        ];
        
        $contract = Contract::create($dataContract);
        $contract->save();
        $last_insert_id = $contract->id;
        $log_type = "was saved to system";
        
        $client_id = $request->client_id;                               $old_client_id = $request->old_client_id;
        $contract_code = $request->contract_code;                       $old_contract_code = $request->old_contract_code;
        $company_phone = $request->company_phone;                       $old_company_phone = $request->old_company_phone;
        $tax_code = $request->tax_code;                                 $old_tax_code = $request->old_tax_code;
        $poste_in_charge = $request->poste_in_charge;                   $old_poste_in_charge = $request->old_poste_in_charge;
        $representative = $request->representative;                     $old_representative = $request->old_representative;
        $status = $request->status;                                     $old_status = $request->old_status;
        $vat_status = $request->vat_status;                             $old_vat_status = $request->old_vat_status;
        $start_date = $request->contract_start_date;                    $old_start_date = $request->old_contract_start_date;
        $end_date = $request->end_date;                              $old_end_date = $request->old_end_date;
        $vat_status = $request->vat_status;                             $old_vat_status = $request->old_vat_status;
        $payment_times = $request->payment_times;                       $old_payment_times = $request->old_payment_times;
        $contract_note = $request->contract_note;                       $old_contract_note = $request->old_contract_note;
        $quantity_months = $request->quantity_months;                   $old_quantity_months = $request->old_quantity_months;
        
        if ($contract) {
            $client = Client::where('id', $client_id)->update(['status' => 'active']);
            if (!$client) {
                return back()->withInput()->withErrors(['errors', 'This Client do not have any contract!']);
            }
            $json_array = array(array(
                "client_id" => $client_id,                                  "old_client_id" => $old_client_id,
                "contract_code" => $contract_code,                          "old_contract_code" => $old_contract_code,
                "company_phone" => $company_phone,                          "old_company_phone" => $old_company_phone,
                "poste_in_charge" => $poste_in_charge,                      "old_poste_in_charge" => $old_poste_in_charge,
                "representative" => $representative,                        "old_representative" => $old_representative,
                "status" => 'doing',                                        "old_status" => 'doing',
                "start_date" => $start_date,                                "old_start_date" => $old_start_date,
                "end_date" => $end_date,                                    "old_end_date" => $old_end_date,
                "vat_status" => $vat_status,                                "old_vat_status" => $old_vat_status,
                "payment_times" => $payment_times,                          "old_payment_times" => $old_payment_times,
                "contract_note" => $contract_note,                          "old_contract_note" => $old_contract_note,
                "quantity_months" => $quantity_months,                      "old_quantity_months" => $old_quantity_months,
                "store_branch_id" => $request->store_branch_id,             "old_store_branch_id" => $request->old_store_branch_id,
                "payment_type"  => $request->payment_type,                  "old_payment_type"  => $request->old_payment_type,      
            ));
            $contract_json = json_encode($json_array);
            
            // handle save contract_service
            $service_id = $request->service_id;
            $end_date_service = $request->time_service;
            $quantity_invoice = $contract->quantity_months / $contract->payment_times; // how many month per invoice
            $grand_total_beta = 0;
            foreach ($service_id as $key => $item) {
                $endDateService = date('Y-m-d', strtotime('+' . $end_date_service[$key] . ' Month', $dateNow));
                $startDateService = $request->service_start_date;
                $discount_rate = $request->discount_rate;
                if($discount_rate == '') { $discount_rate = 0; }
                $dataContractService = [
                    'contract_id'        => $last_insert_id,
                    'service_id'         => $item,
                    'service_start_date' => $startDateService[$key],
                    'discount_rate'      => $discount_rate[$key],
                    'time_service'       => $endDateService,
                    'quantity_months'    => $end_date_service[$key]
                ];
                $contract_service = ContractService::create($dataContractService);
                $service_quantity = $contract_service->quantity_months;
                $service_discount = $contract_service->discount_rate;
                $price = $contract_service->getServiceName->price;
                $pay_per_month = round( $price*$service_quantity / (int)$contract->quantity_months , 2);
                $total = $quantity_invoice*$pay_per_month*((100 - $service_discount)/100)  ;
                $dataContractServiceUpdate = [ 'service_price' => $price ];
                $contract_service->update($dataContractServiceUpdate);
                $grand_total_beta +=  $total;
            }
            if (!$contract_service) {
                return 'error contract service!';
            }
            if($contract->vat_status == 'yes') {
                $percent_vat = $contract->vat;
                $invoice_vat_money = ($percent_vat/100)*$grand_total_beta;
            } else {
                $invoice_vat_money = 0;
            }
            $grand_total_money =  round( $grand_total_beta + $invoice_vat_money , 0); // invoice money
            $payment_money = $grand_total_money*$contract->payment_times; // contract money
            $dataContractUpdate = [ 'payment_money' => $payment_money, ];
            Contract::where('id', '=', $last_insert_id)->update($dataContractUpdate);
            
            

            // create invoice
            for ($i = 1, $j = $i - 1; $i <= $request->payment_times, $i <= $request->payment_times; $i++, $j++) {
                $endDate = $this->setEndDateInvoices($payment, $i, $startDateContract);
                $lastEndDate = $this->setEndDateInvoices($payment, $j, $startDateContract);
                if ($i == 1) {
                    $startDate = $startDateContract;
                }
                else {
                    $startDate = $this->setStartDateInvoices($lastEndDate);
                }
                
                
                $dataInvoice = [
                    'contract_id' => $last_insert_id,
                    'start_date'  => $startDate,
                    'time'        => $endDate,
                    'grand_total_money' => $grand_total_money,
                ];
                
                $invoice = Invoice::create($dataInvoice);
                $invoice = $invoice->save();
                
            }
            
            // save scan file
            if ($request->has('scan_file')) {
                $scan_file = $request->file('scan_file');
                foreach ($scan_file as $key => $item) {
                    $file_name = $item->getClientOriginalName($item);
                    // get file name without extension
                    $file_name = explode('.', $file_name)[0];
                    $file_name = $file_name . '_' .$last_insert_id;
                    $path = $item->getClientOriginalExtension($item);
                    Galleries::uploadFile($file_name, $path, 'contract', $last_insert_id);
                    $item->move('scan_files', $file_name . '.' . $path);
                }
            }
            
            return $this->writeActivityLog('contracts',$last_insert_id,isset($last_insert_id) ? $last_insert_id : '',$log_type, $contract_json);
        }
    }
    
    public function deleteScanFile(Request $request) {
        $id = $request->id;
        $gallery = Galleries::find($id);
        $file = File::delete('scan_files/' . $gallery->name . '.' . $gallery->path);
        $gallery = $gallery->delete();
        if ($file && $gallery) {
            return 1;
        }
        return 0;
    }
    
    public function deleteContract (Request $request) {
        // if ($request->has('id')) {
        //     return $request->id;
        // }
        // return 1;
        // $data_array = json_decode($json, true);
        
        $contract = Contract::find($request->id);
        if ($contract->invoice == 0) {
            return 0;
        }
        // save logs
        $json_array = array(array(
            "client_id"                     => $contract->client_id,                                  
            "contract_code"                 => $contract->contract_code,                         
            "company_phone"                 => $contract->company_phone, 
            "tax_code"                      => $contract->tax_code,                          
            "poste_in_charge"               => $contract->poste_in_charge,                      
            "representative"                => $contract->representative,    
            "status"                        => $contract->status,                                        
            "start_date"                    => $contract->start_date,                                
            "end_date"                      => $contract->end_date, 
            "vat"                           => $contract->vat,                              
            "vat_status"                    => $contract->vat_status,                                
            "payment_times"                 => $contract->payment_times,                          
            "invoice"                       => $contract->invoice, 
            "contract_note"                 => $contract->contract_note, 
            "quantity_months"               => $contract->quantity_months, 
            "store_branch_id"               => $contract->store_branch_id, 
            "payment_money"                 => $contract->payment_money, 
            "exchange_rate"                 => $contract->exchange_rate, 
            "payment_type"                  => $contract->payment_type, 
        ));
        $contract_json = json_encode($json_array);
        
        if (is_null($contract)) {
            return 0;
        }
        else {
            $contract = $contract->forceDelete();
            if (!$contract) {
                return 'contract delete was failed!';
            }
            
            $invoice = Invoice::where('contract_id', $request->id)->delete();
            if (!$invoice) {
                return 'invoice delete wass failed!';
            }
            
            $contract_service = ContractService::where('contract_id', $request->id)->delete();
            if (!$contract_service) {
                return 'contract_service delete was failed!';
            }
            
            $gallery = Galleries::where('contract_id', '=', $request->id)->get();
            
            if ($gallery->count()) {
                $file = File::delete('scan_files/' . $gallery->name . '.' . $gallery->path);
                $gallery = $gallery->delete();
                
                if(!$gallery) {
                    return 'gallery was failed!';
                }
            }
            
            $log_type = 'was deleted';
            $row_name = 'of ' . Auth::user()->full_name;
            // return 1;
            // call function from Trait
            return $this->writeActivityLog('contract_delete', $request->id, $row_name, $log_type, $contract_json);
        }
        
        return 0;
    }
    
    // recipe calculate invoices
    private function setEndDateInvoices($invoice_deadline, $index, $contract_start_date) {
        // format date function. Ex: Date("Y-m-d", strtotime("2013-01-01 +1 Month"));
        $endDate = Date('Y-m-d', strtotime($contract_start_date . ' +' . $invoice_deadline * $index . ' Month')); //format date
        $endDate = Date("Y-m-d", strtotime($endDate . ' -1 days'));
        return $endDate;
    }
    
    private function setStartDateInvoices ($last_deadline) {
        return Date("Y-m-d", strtotime($last_deadline . " +1 days"));
    }
    
}
