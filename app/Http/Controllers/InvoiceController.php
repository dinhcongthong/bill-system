<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Models\Client;
use App\Http\Models\Invoice;
use App\Http\Models\Setting;
use App\Http\Models\Contract;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;
use Config;
use App\Http\Controllers\Traits\WriteActivityLog;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    use WriteActivityLog;

    public function updatePaymentStatus(Request $request, $json, $invoice_id) {
        $data_array = json_decode($json, true);
        $invoice = Invoice::find($invoice_id);
        
		$payment_status = $data_array['payment_status'];
		// $payment_note =  $data_array['payment_note'];
		// $time =  $data_array['time'];
		// $exported =  $data_array['exported'];
		$old_contract_id = $data_array['contract_id'];

        $contract = Contract::find($old_contract_id);
        $contract_code = $contract->contract_code;
        
        // $old_payment_note = $invoice->payment_note;
        // $old_time = $invoice->time;
        // $old_exported = $invoice->exported;

        $old_payment_status = 0;

        if( $payment_status === 0) {
            $old_payment_status = 1;  
        } elseif ($payment_status === 1) {
            $old_payment_status = 0;
        }

		if($invoice->payment_status == '1'){
			$invoice->update(['payment_status' => 0]);
			$last_insert_id = $invoice_id;
            $jsonRow = Invoice::where('id', '=', $last_insert_id)->first();
            // $contract_id = $jsonRow->contract_id;
            $log_type = "was updated";
			$paid = 'no';
            $expo = '';
		}
		else {
			$invoice->update(['payment_status' => 1]);
			$last_insert_id = $invoice_id;
            $jsonRow = Invoice::where('id', '=', $last_insert_id)->first();
            // $contract_id = $jsonRow->contract_id;
            $log_type = "was updated";
			$paid = 'yes';
            $expo = '';
		}

        $contract = Contract::find($data_array['contract_id']);
        $payment_times = $contract->payment_times;
        $count_invoice = Invoice::where('payment_status', 1)->where('contract_id', $data_array['contract_id'])->count();
        if($count_invoice == $payment_times) {
            $contract->update(['invoice' => 1]);
        } elseif($count_invoice < $payment_times) {
            $contract->update(['invoice' => 0]);
        }

		if ($invoice) {
            $json_array = array(array(
            	'contract_code' => $contract_code,
            	'payment_status' => $payment_status,
            	'old_payment_status' => $old_payment_status,
            	'paid' => $paid,
                'expo' => $expo,
            ));
            $invoice_json = json_encode($json_array);

            // call function from Trait
            return $this->writeActivityLog('invoices_payment_status',$last_insert_id,isset($last_insert_id) ? $last_insert_id : '',$log_type, $invoice_json);
        }

		return 0;
	}

    public function updateExported(Request $request, $json, $invoice_id) {
        $data_array = json_decode($json, true);
        $invoice = Invoice::find($invoice_id);

        $exported = $data_array['exported'];
        $old_contract_id = $data_array['contract_id'];

        $contract = Contract::find($old_contract_id);
        $contract_code = $contract->contract_code;

        $old_exported = 0;

        if( $exported === 0) {
            $old_exported = 1;  
        } elseif ($exported === 1) {
            $old_exported = 0;
        }

        // $invoice = Invoice::find(1);
        if($invoice->exported == '1'){
            $invoice->update(['exported' => 0]);
            $last_insert_id = $invoice_id;
            $jsonRow = Invoice::find($last_insert_id);
            $contract_id = $jsonRow->contract_id;
            $log_type = "was updated";
            $expo = 'no';
            $paid = '';
        }
        else {
            $invoice->update(['exported' => 1]);
            $last_insert_id = $invoice_id;
            $jsonRow = Invoice::find($last_insert_id);
            $contract_id = $jsonRow->contract_id;
            $log_type = "was updated";
            $expo = 'yes';
            $paid = '';
        }

        if ($invoice) {
            $json_array = array(array(
                'contract_code' => $contract_code,
                'exported' => $exported, 
                'old_exported' => $old_exported,
                'paid' => $paid,
                'expo' => $expo,
            ));
            $invoice_json = json_encode($json_array);

            // call function from Trait
            return $this->writeActivityLog('invoices',$last_insert_id,isset($last_insert_id) ? $last_insert_id : '',$log_type, $invoice_json);
        }

        return 0;
    }

	public function getNew($invoice_id){
		if( $invoice_id === '0' ) {
            return view('invoice.add-new')->with($this->data);
        }
        else{
            $invoice = Invoice::find($invoice_id);
            $this->data['id'] = $invoice->id;
            $this->data['contract_id'] = $invoice->contract_id;
            $this->data['payment_type'] = $invoice->payment_type;
            $this->data['payment_status'] = $invoice->payment_status;
            $this->data['discount'] = $invoice->discount;
            $this->data['vat_status'] = $invoice->vat_status;
            $this->data['payment_money'] = $invoice->payment_money;
            $this->data['payment_note'] = $invoice->payment_note;
            $this->data['time'] = $invoice->time;
            $this->data['exported'] = $invoice->exported;
            return view('invoice.add-new')->with($this->data);
        }
        
        return view('invoice.add-new')->with($this->data);
	}
}