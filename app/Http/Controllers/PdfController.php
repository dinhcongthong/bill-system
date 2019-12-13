<?php

namespace App\Http\Controllers;
// namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Http\Controllers\Admin\BaseController as Controller;

use App\Models\User;
// use App\Models\Vietnamese;
// use App\Models\Gallery;
use App\Http\Models\ContractService;
use App\Http\Models\Service;
use App\Http\Models\Client;
use App\Http\Models\Contract;
use App\Http\Models\Invoice;
use App\Http\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

// use Spartie\Activitylog\Traits\LogsActivity;

// composer require barryvdh/laravel-dompdf
use PDF;
use Dompdf\Dompdf;

class PdfController extends Controller {

	public function index(){
		return view('pdf.invoice.index')->with($this->data);
	}

	public function input(Request $request) {
		$title = "Input for Invoice";
		$data = Input::get('data', 'Hello everybody');
		$this->data['title'] = $title;
		$this->data['data'] = $data;
		return view('pdf.invoice.input')->with($this->data);
	}

	public function view(Request $request, $data) {
		$title = "PAGE FOR SALE";
        $json = json_decode($data, true);

        // return $json;
        $today = date('d-m-Y', strtotime('+0 Month', time()));
        $client_id = $json['client_id'];
    	$contract_id = $json['contract_id'];
    	$invoice_id = $json['invoice_id'];

    	$contract = Contract::find($contract_id);
    	$quantity_invoice = $contract->quantity_months / $contract->payment_times;

		$contract_services = ContractService::where('contract_id', $contract_id)->get();

		$client = Client::where('id', $client_id)->first();
		$invoice = Invoice::where('contract_id', $contract_id)->where('id', $invoice_id)->first();

		$pay_per_month_arr = [];
		$total_arr = [];
		$service_discount_arr = [];
		$grand_total_beta = 0;
		
		foreach($contract_services as $contract_service) {
			$service_quantity = $contract_service->quantity_months; // no change

			$service_discount = $contract_service->discount_rate; // no change

			$price = $contract_service->service_price; // no change

			$pay_per_month = round( $price*$service_quantity / (int)$contract->quantity_months , 2);
			$total = $quantity_invoice*$pay_per_month*((100 - $service_discount)/100)  ;

			$total_arr[] = $total;
			$pay_per_month_arr[] = $pay_per_month;
			$service_discount_arr[] = $service_discount;

			
			$grand_total_beta +=  $total;
		}


		if($contract->vat_status == 'yes') {
            $percent_vat = $contract->vat;
            $invoice_vat_money = ($percent_vat/100)*$grand_total_beta;
        } else {
            $invoice_vat_money = 0;
        }
		
		// $invoice_vat_money = ($percent_vat/100)*$grand_total_beta;
		$grand_total_money =  round( $grand_total_beta + $invoice_vat_money , 0);
		
		// $this->data['poste'] = $poste;
		$this->data['name_poste'] = $client->getArea->name_poste;
		$this->data['tax_code'] = $client->getArea->tax_code;
		$this->data['address_poste'] = $client->getArea->address_poste;
		$this->data['phone_poste'] = $client->getArea->phone_poste;
		$this->data['email_poste'] = $client->getArea->email_poste;
		$this->data['exchange_rate'] = $contract->exchange_rate; // get from contract
		$this->data['area_name'] = $client->getArea->area_name;
		$this->data['bank_name'] = $client->getArea->bank_name;
		$this->data['acc_bank_name'] = $client->getArea->acc_bank_name;
		$this->data['acc_bank_number'] = $client->getArea->acc_bank_number;
		$this->data['path_logo_file'] = $client->getArea->path_logo_file;

		$this->data['today'] = $today;
		$this->data['contract_id'] = $contract_id;
		$this->data['contract_code'] = $contract->contract_code;
		$this->data['payment_times'] = $contract->payment_times;
		$this->data['invoice_id'] = $invoice_id;
		$this->data['invoice'] = $invoice;
		// $this->data['poste'] = $poste;
		$this->data['contract_services'] = $contract_services;
		$this->data['client'] = $client;
		// $this->data['quantity'] = $quantity;
		$this->data['discount_rate'] = $service_discount;
		// $this->data['percent_vat'] = $percent_vat;
		$this->data['percent_vat'] = $contract->vat;
	 	$this->data['invoice_vat_money'] = $invoice_vat_money;
		$this->data['invoice_vat_status'] = $contract->vat_status;  // get from contract
		$this->data['quantity_invoice'] = $quantity_invoice;
		// $this->data['payment_type'] = $contract->payment_type;
		$this->data['payment_type'] = $contract->payment_type; // get from contract
		// $this->data['price'] = $price;
		$this->data['total_arr'] = $total_arr;  
		$this->data['pay_per_month_arr'] = $pay_per_month_arr ;
		$this->data['service_discount_arr'] = $service_discount_arr;
		$this->data['grand_total_beta'] = $grand_total_beta;
		$this->data['grand_total_money'] = $grand_total_money;


		return view('pdf.invoice.view_invoice')->with($this->data);
		// return view('pdf.invoice.invoice2')->with($this->data);
	}

	public function confirm(Request $request, $data) {
		$title = "CONFIRM PAGE";
        $json = json_decode($data, true);

        $number = $request->number;
        $sign_name = $request->sign_name;
        $payment_type = $request->payment_type;
        $quantity_invoice = $contract->quantity_months / $contract->payment_times;
        $client_id = $json['client_id'];
    	$contract_id = $json['contract_id'];
    	// return $contract_id;
    	$grand_total = $json['grand_total'];
    	$contract = Contract::find($contract_id);
    	
    	$invoice_id = $json['invoice_id'];
    	
		$contract_services = ContractService::where('contract_id', $contract_id)->get();
		$invoice_vat_status = $contract->vat_status;
		$client = Client::where('id', $client_id)->first();
		$invoice = Invoice::where('contract_id', $contract_id)->where('id', $invoice_id)->first();

		$exchange_rate = $contract->exchange_rate;

		if($invoice_vat_status == 'yes') {
			$invoice_vat = $contract->getArea->vat_rate;
		} else {
			$invoice_vat = 0;
		}

		// 

		$pay_per_month_arr = [];
		$total_arr = [];
		$grand_total = 0;

		foreach($contract_services as $contract_service) {
			$service_quantity = $contract_service->quantity_months;

			$service_discount = $contract_service->discount_rate;

			$price = $contract_service->getServiceName->price;

			$pay_per_month = round( $price*$service_quantity / (int)$contract->quantity_months , 2);
			$total = $quantity_invoice*$pay_per_month*((100 - $service_discount)/100)  ;

			$total_arr[] = $total;
			$pay_per_month_arr[] = $pay_per_month;

			
			$grand_total +=  $total;
		}

		$invoice_vat = ($percent_vat/100)*$grand_total;
		$grand_total_after_vat =  round( $grand_total + $invoice_vat , 0);

		$this->data['name_poste'] = $contract->getArea->name_poste;
		$this->data['tax_code'] = $contract->getArea->tax_code;
		$this->data['address_poste'] = $contract->getArea->address_poste;
		$this->data['phone_poste'] = $contract->getArea->phone_poste;
		$this->data['email_poste'] = $contract->getArea->email_poste;
		$this->data['exchange_rate'] = $exchange_rate;
		$this->data['area_name'] = $contract->getArea->area_name;
		$this->data['bank_name'] = $contract->getArea->bank_name;
		$this->data['acc_bank_name'] = $contract->getArea->acc_bank_name;
		$this->data['acc_bank_number'] = $contract->getArea->acc_bank_number;
		$this->data['path_logo_file'] =  $contract->getArea->path_logo_file;

		$this->data['today'] = date('d-m-Y', strtotime('+0 Month', time()));
		$this->data['contract_id'] = $contract_id;
		$this->data['contract_code'] = $contract->contract_code;
		$this->data['payment_times'] = $contract->payment_times;
		$this->data['invoice_id'] = $invoice_id;
		$this->data['invoice'] = $invoice;
		$this->data['contract_services'] = $contract_services;
		$this->data['client'] = $client;
		// $this->data['quantity'] = $quantity;
		$this->data['discount_rate'] = $service_discount;
		$this->data['percent_vat'] = $percent_vat;
		$this->data['invoice_vat'] = $invoice_vat; 
		$this->data['invoice_vat_status'] = $invoice_vat_status;
		$this->data['quantity_invoice'] = $quantity_invoice;
		// $this->data['price'] = $price;
		$this->data['total_arr'] = $total_arr; 
		$this->data['pay_per_month_arr'] = $pay_per_month_arr ;
		$this->data['grand_total'] = $grand_total;
		$this->data['grand_total_after_vat'] = $grand_total_after_vat;

		// $invoice = Invoice::find($invoice_id);
		// $invoice->payment_type = $payment_type;
		// $invoice->save();

		return view('pdf.invoice.confirm')->with($this->data);
	}

	// Laravel DomPDF
	public static function download(Request $request, $value){
		$json = json_decode($value, true);
        $client_id = $json['client_id'];
    	$contract_id = $json['contract_id'];
    	$invoice_id = $json['invoice_id'];
    	$client = Client::find($client_id);
    	$contract = Contract::find($contract_id);

		$quantity_invoice = $contract->quantity_months / $contract->payment_times;
    	$contract_services = ContractService::where('contract_id', $contract_id)->get();

		$today = date('d-m-Y', strtotime('+0 Month', time()));

		$invoice = Invoice::find($invoice_id);

		// if($invoice_vat_status == 'yes') {
		// 	$invoice_vat = $contract->getArea->vat_rate;
		// } else {
		// 	$invoice_vat = 0;
		// }
		$service_discount_arr = [];

		$pay_per_month_arr = [];
		$total_arr = [];
		$grand_total_beta = 0;

		foreach($contract_services as $contract_service) {
			$service_quantity = $contract_service->quantity_months; // no change

			$service_discount = $contract_service->discount_rate; // no change

			$price = $contract_service->service_price; // no change

			$pay_per_month = round( $price*$service_quantity / (int)$contract->quantity_months , 2);
			$total = $quantity_invoice*$pay_per_month*((100 - $service_discount)/100)  ;

			$total_arr[] = $total;
			$pay_per_month_arr[] = $pay_per_month;
			$service_discount_arr[] = $service_discount;

			
			$grand_total_beta +=  $total;
		}


		if($contract->vat_status == 'yes') {
            $percent_vat = $contract->vat;
            $invoice_vat_money = ($percent_vat/100)*$grand_total_beta;
        } else {
			$percent_vat = 0;
            $invoice_vat_money = 0;
        }

		$grand_total_money =  round( $grand_total_beta + $invoice_vat_money , 0);
    	
		$content = [
			'contract_services' => $contract_services,
			'client_id' => $client_id,
			'payment_times' => $contract->payment_times,
			'contract_id' => $contract_id,
			'contract_code' => $contract->contract_code,
			'invoice_id' => $invoice_id,
			'percent_vat' => $percent_vat,
			'invoice_vat_status' => $contract->vat_status,
			'client' => $client,
			'number' => $request->number,
			'sign_name' => $request->sign_name,
			'today' => $today,
			'service_discount' => $service_discount,
			'total' => $total,
			'pay_per_month_arr' => $pay_per_month_arr,
			'quantity_invoice' => $quantity_invoice,
			'total_arr' => $total_arr,
			'grand_total_beta' => $grand_total_beta,
			'grand_total_money' => $grand_total_money,
			'payment_type' =>$contract->payment_type,

			'start_date' => $invoice->start_date,
			'end_date' => $invoice->time,
			'service_discount_arr' => $service_discount_arr,
			'invoice_vat_money' => $invoice_vat_money,

			'name_poste' => $client->getArea->name_poste,
            'tax_code' => $client->getArea->tax_code,
            'address_poste' => $client->getArea->address_poste,
            'phone_poste' => $client->getArea->phone_poste,
            'email_poste' => $client->getArea->email_poste,
            'exchange_rate' => $contract->getArea->exchange_rate,
			'area_name' => $client->getArea->area_name,
			'bank_name' => $client->getArea->acc_bank_name,
			'acc_bank_name' => $client->getArea->acc_bank_name,
			'acc_bank_number' => $client->getArea->acc_bank_number,
            'path_logo_file' => $client->getArea->path_logo_file,
		];

		$pdf = PDF::loadView('pdf.invoice.invoice_download', $content)->setPaper('a4', 'portrait');
		return $pdf->download(Carbon::createFromFormat('Y-m-d', date('Y-m-d')).'_'.'invoice.pdf');
	 	// $this->pdf->loadHtml(
	  //       view('pdf.invoice.invoice2')->with($content)
	  //   );
	    // $this->pdf->setPaper('a4', 'portrait')->render();
	    // $this->pdf->stream(Carbon::createFromFormat('Y-m-d', date('Y-m-d')).'_'.'invoice.pdf');
	    // $this->pdf->stream(Carbon::createFromFormat('Y-m-d', date('Y-m-d')).'_'.'invoice.pdf', ['Attachment' => false]);
	}

	public function generate_download_page(Request $request, $value) {
		$title = "Preview Before Print";
		$json = json_decode($value, true);
        $client_id = $json['client_id'];
    	$contract_id = $json['contract_id'];
    	$contract = Contract::find($contract_id);
    	$contract_code = $contract->contract_code;
    	$payment_times = $contract->payment_times;
    	$invoice_id = $json['invoice_id'];
    	// $number = $json['number'];
    	// $sign_name = $json['sign_name'];
    	$number = $request->number;
    	$sign_name = $request->sign_name;
    	$today = $json['today'];
    	$client = Client::where('id', $client_id)->first();
    	
    	$contract_services = ContractService::where('contract_id', $contract_id)->get();
		$invoice_vat_status = $contract->vat_status;

		// $invoice = Invoice::where('contract_id', $contract_id)->where('id', $invoice_id)->first();
		$invoice = Invoice::find($invoice_id);

    	$name_poste = $contract->getArea->name_poste;
		$tax_code = $contract->getArea->tax_code;
		$address_poste = $contract->getArea->address_poste;
		$phone_poste = $contract->getArea->phone_poste;
		$email_poste = $contract->getArea->email_poste;
		$exchange_rate = $contract->getArea->exchange_rate;
		$bank_name = $contract->getArea->bank_name;
		$acc_bank_name = $contract->getArea->acc_bank_name;
		$acc_bank_number = $contract->getArea->acc_bank_number;
		$area_name = $contract->getArea->area_name;
		$path_logo_file = $contract->getArea->path_logo_file;

		if($invoice_vat_status == 'yes') {
			$invoice_vat = $contract->getArea->vat_rate;
		} else {
			$invoice_vat = 0;
		}

    	foreach($contract_services as $contract_service) {
			$quantity = $contract_service->quantity_months;

			$service_discount = $contract_service->discount_rate;

			$price = $contract_service->getServiceName->price; 
			$total = round( ($price*$quantity*((100 - $service_discount)/100)) / (int)$payment_times ,0) ;
			static $grand_total = 0;
			$grand_total += $total;
    	}

		$content = [
			'contract_services' => $contract_services,
			'title' => $title,
			'client_id' => $client_id,
			'payment_times' => $payment_times,
			'contract_id' => $contract_id,
			'contract_code' => $contract_code,
			'invoice_id' => $invoice_id,
			'percent_vat' => $percent_vat,
			'invoice_vat' => $invoice_vat,
			'invoice_vat_status' => $invoice_vat_status,
			'client' => $client,
			'number' => $number,
			'sign_name' => $sign_name,
			'today' => $today,
			'service_discount' => $service_discount,
			'total' => $total,
			'grand_total' => $grand_total,
			'grand_total_after_vat' => $grand_total_after_vat,

			'start_date' => $invoice->start_date,
			'end_date' => $invoice->time,

			'name_poste' => $name_poste,
            'tax_code' => $tax_code,
            'address_poste' => $address_poste,
            'phone_poste' => $phone_poste,
            'email_poste' => $email_poste,
            'exchange_rate' => $exchange_rate,
			'area_name' => $area_name,
			'bank_name' => $bank_name,
			'acc_bank_name' => $acc_bank_name,
			'acc_bank_number' => $acc_bank_number,
            'path_logo_file' => $path_logo_file,
		];

		$pdf = new Dompdf;
	    $pdf->loadHtml(
	        view('pdf.invoice.invoice2')->with($content)
	    );
	    $pdf->setPaper('a4', 'portrait')->render();
	    // $pdf->render();
	    // $pdf->stream('invoice.pdf');
	    $pdf->stream(Carbon::createFromFormat('Y-m-d', date('Y-m-d')).'_'.'invoice.pdf', ['Attachment' => true]);
	    // return $pdf->output();
	}

}
