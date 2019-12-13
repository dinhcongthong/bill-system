@extends('templates.master')
@section('stylesheets')
<style>
	#table.table-bordered, #table.table-bordered td, #table.table-bordered th{
		border: 1px solid rgba(0,0,0,.5);
		border-collapse: collapse;
	}
</style>
@stop
@section('content')
<div class="flex-fill h-100" style="overflow-y:auto;">
	<div class="container my-4" style="max-width: 768px;">
		<div class="media-wrapper-A4 bg-white" style="padding-top: calc(297 / 210 * 100%);">
			
			@php
			$data = [
			'client_id' => $client->id,
			'contract_id' => $contract_id,
			'invoice_id' => $invoice_id,
			];
			@endphp
			
			<div class="invoice-A4-wrapper" style="font-family: 'Times News Roman', 'Times', 'kozuka_mincho_pro_b', serif; max-height: 100%; overflow-y:auto;">
				<form class="position-relative d-flex flex-wrap px-4" action="{{ URL::to('/pdf/download/' . json_encode($data) ) }}" method="GET">
					{{ csrf_field() }}
					
					<h2 class="col-12 text-center fw-700 mt-5">INVOICE</h2>
					<div class="col-12 text-right">{{ $today }} - Contract code: {{ $contract_code ? $contract_code : 'N/A' }}</div>
					<div class="col-12 text-right d-flex align-items-end justify-content-end border-bottom pb-3">
						<label class="d-inline-block m-0">Invoice Number: </label>
						<input class="form-control form-control-sm d-inline-block ml-2" style="max-width: 6rem;" placeholder="......" type="text" name="number">
					</div>

					<div class="col-12 mt-4 mb-5">
						<div class="row align-items-center">
							<div class="col-7">
								<div class="d-grid align-items-end" style="grid-template-columns: auto 1fr;">
									<div class="border-bottom pt-2">COMPANY NAME:</div>
									<div class="h4 border-bottom pt-2 pl-4 m-0">{{ $client->name }}</div>
									
									<div class="border-bottom pt-2">ATTN:</div>
									<div class="border-bottom pt-2 pl-4 m-0">{{ $client->client_in_charge }}</div>
									
									<div class="border-bottom pt-2">ADD:</div>
									<div class="border-bottom pt-2 pl-4 m-0">{{ $client->address_client_in_charge }}</div>
									
									<div class="border-bottom pt-2">TEL:</div>
									<div class="border-bottom pt-2 pl-4 m-0">{{ $client->phone_client_in_charge }}</div>
									
									<div class="border-bottom pt-2">TOTAL:</div>
									<div class="h3 border-bottom fw-700 pt-5 pl-4 m-0">${{ number_format($grand_total_money) }} {{ $exchange_rate <= 0 ? '('.number_format((int)$exchange_rate*$grand_total_money). 'VND)' : '' }}</div>
									
									<div class="border-bottom pt-2">PAYMENT TYPE:</div>
									<div class="border-bottom text-capitalize pt-2 pl-4 m-0">{{ $payment_type }}</div>
								</div>
							</div>
							<div class="col-5 d-flex flex-wrap justify-content-end">
								<div class="col-auto p-0 mb-2">
									<img class="img-fluid w-75 float-right" alt="region-logo" src="{{ URL::to('area/' . $area_name . '.' . $path_logo_file) }}"/>
								</div>
								<div class="col-12 p-0">
									<p class="mb-3 text-right fw-700">{{ $name_poste }}</p>
									<p class="m-0 text-right">Tax code: <span>{{ $tax_code }}</span></p>
									<p class="m-0 text-right">Address(HQ): <span>{{ $address_poste }}</span></p>
									<p class="m-0 text-right">Tel: <span>{{ $phone_poste }}</span></p>
									<p class="m-0 text-right">Email: <span>{{ $email_poste }}</span></p>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-12" >
						<table id="table" class="table table-sm table-bordered border-dark mt-3">
							<thead >
								<tr style="background-color: #ccc;">
									<!-- <td class="text-center">No</td> -->
									<td class="text-center">List of product<br><em class="text-black-50">(Monthly price)</em></td>
									<td class="text-center">Start date<br><em class="text-black-50">(Y-M-D)</em></td>
									<td class="text-center">End date<br><em class="text-black-50">(Y-M-D)</em></td>
									<td class="text-center">QTY<br><em class="text-black-50">(month)</em></td>
									<td class="text-center">Pay/month<br><em class="text-black-50">($)</em></td>
									<td class="text-center">Discount<br><em class="text-black-50">(%)</em></td>
									<td class="text-center">Total<br><em class="text-black-50">($)</em></td>
								</tr>
							</thead>
							
							<tbody>
								@foreach($contract_services as $contract_service)
								<tr>
									<td class="text-left px-3" id="contract_service_name" value="{{ $contract_service->getServiceName->name }}" width="25%">
										{{ $contract_service->getServiceName->name }} (${{ $contract_service->getServiceName->price }})
									</td>
									
									<td class="text-center px-3" >{{ date("Y-m-d", strtotime($invoice->start_date) ) }}</td> <!-- invoice -->
									<td class="text-center px-3" >{{ date("Y-m-d", strtotime($invoice->time) ) }}</td> <!-- invoice -->
									<td class="text-center px-3" >{{ $quantity_invoice }}</td> 										<!-- invoice -->
									<td class="text-center px-3" >{{ number_format($pay_per_month_arr[$loop->index]) }}</td> <!-- no change -->
									<td class="text-center px-3" width="15%">{{ $service_discount_arr[$loop->index] ? $service_discount_arr[$loop->index] : '0' }}</td>
									<td class="text-right px-3" width="15%">{{ number_format($total_arr[$loop->index]) }}</td>
								</tr>
								@endforeach
								
								@if($invoice_vat_status === 'no' || $invoice_vat_status === '0')
								<tr> 
									<td class="text-center" colspan="5" rowspan="2">{{ $exchange_rate <= 0 ? '1USD= ' .number_format( (float)$exchange_rate). 'VND' : '' }}</td>
									<td class="text-center">Total Invoice</td>
									<td class="text-right px-3">${{ number_format($grand_total_beta) }}</td>
								</tr>
								<tr>
									<td class="text-center fw-700">Grand Total</td>
									<td class="text-right fw-700 px-3">${{ number_format($grand_total_money) }}</td>
								</tr>
								@else
								<tr>
									<td class="text-left p-3" colspan="5" rowspan="3">
										Bank account at: {{$bank_name}} {{ $exchange_rate <= 0 ? '( Swift: ASCBVNVX )' : '' }}<br>
										Account name: {{$acc_bank_name}}<br>
										Account number: {{$acc_bank_number}}<br>
										{{ $exchange_rate <= 0 ? '1USD= ' .number_format( (float)$exchange_rate). 'VND' : '' }}
									</td>
									<td class="text-center">Total Invoice</td>
									<td class="text-right px-3">${{ number_format($grand_total_beta) }}</td>
								</tr>
								<tr>
									<td class="text-center">VAT({{ $percent_vat }} %)</td>
									<td class="text-right px-3">${{ number_format($invoice_vat_money) }}</td>
									
								</tr>
								<tr>
									<td class="text-center fw-700">Grand Total</td>
									<td class="text-right fw-700 px-3">${{ number_format($grand_total_money) }}</td>
								</tr>
								@endif
								
							</tbody>
							
						</table>
					</div>
					
					<div class="col-6" style="margin: 15vh 0 5vh auto;">
						<input class="form-control" placeholder="SIGN NAME HERE..." type="text" name="sign_name">
					</div>
					<div class="col-12 align-self-end mb-5">
						<button class="btn btn-outline-danger btn-block mt-15" type="submit"><i class="lnr-download2"></i> Download Invoice</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection



@section('scripts')
<script type="text/javascript">
	$(document).ready(function () {
		resetIndex();
	});
	
	function preview() {
		$('.btn-view-invoice').on("click", function() {
			var APPURL = {!! json_encode(url('/')) !!}
			console.log(APPURL);
			var number = $('#number').val();
			var contract_services_id = $('#contract_services_id').val();
			
			var obj = {
				"client_id"  : "{{ $client->id }}",
				"contract_id" : "{{ $contract_id }}",
				"invoice_id"  : "{{ $invoice_id }}",
				"today" : "{{ date("d-m-Y", strtotime($today) ) }}",
				"number" : number,
			};
			var json= JSON.stringify( obj);
			
			$.ajax({
				method: 'GET',
				url: base_url + '/pdf/confirm/' +  json,
				
			})
			.done(function(data) {
				console.log(data);
			})
			.fail(function(xhr, status, error) {
				console.log(this.url);
				console.log(error);
			})
		});
	}
	
	function resetIndex() {
		var table = document.getElementById('table'),rIndex,cIndex;
		for (var i=1;i<table.rows.length;i++) {
			for (var j=0;j<table.rows[i].cells.length;j++) {
				rIndex = document.getElementById('contract_services_id').parentElement.rowIndex;
				cIndex = this.cellIndex;
				console.log(rIndex);
				$(this).value = rIndex;
			}
		}
	}
</script>
@endsection
