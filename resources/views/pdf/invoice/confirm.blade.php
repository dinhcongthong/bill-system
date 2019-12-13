@extends('templates.master')
@section('stylesheets')
	<style>
		.main {font-family: Times News Roman;}
		.margin-auto { margin: 0 auto; }
		.mt-15 {margin-top: 15px;}
		.mt-60 {margin-top: 60px;}
		.p-0 {padding: 0;}
		.width-view {max-width: 778px}
		.font-news-roman {font-family: Times News Roman;}
		.font-15 {font-size: 15px;}
		.my-border-bottom {border-bottom: 1px solid black;}
		h2 {font-family: Times News Roman; font-weight: bold;}
	</style>
@stop
@section('content')

<div class="main flex-fill" style="margin: 0 auto; ">
	@foreach($contract_services as $contract_service)
		@php
			$quantity = $contract_service->created_at->diffInMonths($contract_service->time_service);
			$price = $contract_service->getServiceName->price;


	        if( $quantity >= 12) {
	        	if( $contract_service->discount_rate == '') {
		            $discount = 50;
	        	}
	        	else {
	        		$discount = $contract_service->discount_rate;
	        	}
	        } elseif($quantity <= 2){
				$quantity = 1;
				$discount = 0;
			} else {
				$discount = 0;
			}

			$total = ($price*$quantity*((100 - $discount)/100)) / (int)$payment_times ;
			$total = round($total, 0);
		
			static $grand_total = 0;
			$grand_total += $total; 
    	@endphp
    @endforeach
	@php
        $data = [
        	'contract_services' => $contract_services,
            'client_id' => $client->id,
            'contract_id' => $contract_id,
            'contract_code' => $contract_code,
            'invoice_id' => $invoice_id,
            'today' => $today,
            'number' => $number,
            'sign_name' => $sign_name,
        ];
    @endphp

    <iframe style="width: 100%; border: 0; height: 95vh;" src="{{ URL::to('/pdf/generate_download_page/' . json_encode($data) ) }}"></iframe>
	{{-- <div class="mt-60 text-center">
		<a class="btnDownload btn btn-info" href="{{ URL::to('/pdf/download/' . json_encode($data) ) }}" >Export invoice pdf file</a>
	</div> --}}
</div>
@endsection