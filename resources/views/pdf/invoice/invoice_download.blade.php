<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<style>
		@import url('https://fonts.googleapis.com/css?family=Noto+Serif+JP:400,700&display=swap&subset=vietnamese');
		body { font-family: 'Noto Serif JP', 'times_new_roman', sans-serif }
		.mt-3 {margin-top: 15px;}
		.mt-200 {margin-top: 200px;}
		.p-0 {padding: 0;}
		.width-a4 {
			width: 690px;
		}
		.height-a4 {
			min-height: 842px;
		}
		@page {
			size: A4;
			margin: 0;
		}
		@media print {
			html, body {
				width: 210mm;
				height: 297mm;        
			}
			.page {
				width: 690px;
				margin: 0;
				border: initial;
				border-radius: initial;
				width: initial;
				min-height: initial;
				box-shadow: initial;
				background: initial;
				page-break-after: always;
				display: block;
				position: relative;
				/* font-family: 'serif', serif; */
			}
		}
		.text-center {
			text-align: center;
		}
		.text-left {
			text-align: left;
		}
		.text-right{
			text-align: right;
		}
		.ml-auto, .mx-auto{
			margin-left: auto;
		}
		.mr-auto, .mx-auto{
			margin-right: auto;
		}
		.fw-bold{
			font-weight: bold;
		}
		.border-bottom{
			border-bottom: 1px solid #ccc;
		}
		.w-100{
			width: 100%;
		}
		.h2{
			font-size: 2rem;
		}
		.pl-3, .px-3{
			padding-left: 1rem;
		}
		.pr-3, .px-3{
			padding-right: 1rem;
		}
		.mt-2{
			margin-top: .5rem;
		}
		table{
			border-spacing: 0;
			border-collapse: collapse;
		}
		.pt-4, .py-4{
			padding-top: 1.5rem;
		}
		.pb-4, .py-4{
			padding-bottom: 1.5rem;
		}
		.float-right{
			float: right;
		}
	</style>
</head>

<body>
	
	<div class="page">
		
		<div class="width-a4 mx-auto" >
			<table class="w-100 mx-auto mt-3">
				<tr>
					<td class="fw-bold h2 text-center"><span style="font-family: 'mincho';">INVOICE</span></td>
				</tr>
				<tr>
					<td class="text-right">
						<span class="text-left" style="display: inline-block; float: right;">
							{{ $today }} - Contract code: {{ $contract_code }}<br>
							Number: {{ $number }}
						</span>
					</td>
				</tr>
			</table>
		</div>
		
		<div class="width-a4 mx-auto border-bottom" style="clear:both;"></div>
		
		<div class="width-a4 mx-auto py-4" >
			<table class="w-100">
				<tbody>
					<tr>
						<td></td>
						<td class="text-right" width="40%" height="50">
							<img style="display: inline-block;"  alt="logo" src="{{ public_path('area/' . $area_name . '.' . $path_logo_file) }}" width="auto" height="100%" /><br>
						</td>
					</tr>
					
					<tr>
						<td style="vertical-align:top;">
							<table class="w-100 mx-auto mt-2">
								<tbody>
									<tr>
										<td class="border-bottom">COMPANY NAME:</td>
										<td class="border-bottom pl-3">{{ $client->name }}</td>
									</tr>
									<tr>
										<td class="border-bottom">ATTN:</td>
										<td class="border-bottom pl-3">{{ $client->client_in_charge }}</td>
									</tr>
									<tr >
										<td class="border-bottom">ADD:</td>
										<td class="border-bottom pl-3">{{ $client->address_client_in_charge }}</td>
									</tr>
									<tr >
										<td class="border-bottom">TEL:</td>
										<td class="border-bottom pl-3">{{ $client->phone_client_in_charge }}</td>
									</tr>
									<tr >
										<td class="border-bottom">FAX:</td>
										<td class="border-bottom pl-3"></td>
									</tr>
									<tr>
										<td class="border-bottom align-bottom" style="vertical-align:bottom;">TOTAL:</td>
										<td class="border-bottom fw-bold pl-3 pt-4" style="font-size: 1.5rem; font-family: 'mincho', serif;">${{ number_format($grand_total_money) }}{{ $exchange_rate != 0 ? ' ('.number_format($exchange_rate*$grand_total_money).'VND)' : '' }}</td>
									</tr>
									<tr>
										<td class="border-bottom align-bottom">PAYMENT TYPE:</td>
										<td class="border-bottom pl-3">{{ $payment_type }}</td>
									</tr>
								</tbody>
							</table>
						</td>
						<td width="40%" style="vertical-align:top; font-size: .9rem;">
							<table class="w-100 mx-auto">
								<tbody>
									<tr>
										<td class="text-right fw-bold"><span style="font-family: 'mincho';">{{ $name_poste }}</span></td>
									</tr>
									<tr>
										<td class="text-right">Tax code: <span>{{ $tax_code }}</span></td>
									</tr>
									<tr>
										<td class="text-right">Address(HQ): <span>{{ $address_poste }}</span></td>
									</tr>
									<tr>
										<td class="text-right">Tel: <span>{{ $phone_poste }}</span></td>
									</tr>
									<tr>
										<td class="text-right">Email: <span>{{ $email_poste }}</span></td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		
		
		<div class="width-a4 mx-auto" >
			
			<table class="w-100 mx-auto mt-3" border="1">
				<thead >
					<tr style="background-color: #ccc;">
						<td class="text-center">Name of product</td>
						<td class="text-center">Start date</td>
						<td class="text-center">End date</td>
						<td class="text-center">QTY (month)</td>
						<td class="text-center">Price ($)</td>
						<td class="text-center">Discount (%)</td>
						<td class="text-center">Total ($)</td>
					</tr>
				</thead>
				<tbody>
					@foreach($contract_services as $contract_service)
					<tr>
						<td class="text-center px-3" width="20%">{{ $contract_service->getServiceName->name }}</td>
						<td class="text-center px-3" width="17%">{{ date("m-d-Y", strtotime($start_date) ) }}</td>
						<td class="text-center px-3" width="17%">{{ date("m-d-Y", strtotime($end_date) ) }}</td>
						<td class="text-center px-3" width="6%">{{ $quantity_invoice }}</td>
						<td class="text-center px-3" width="10%">{{ number_format($pay_per_month_arr[$loop->index]) }}</td>
						<td class="text-center px-3" width="15%">{{ $service_discount_arr[$loop->index] ? $service_discount_arr[$loop->index] : 0 }}</td>
						<td class="text-right px-3" width="15%">{{ number_format($total_arr[$loop->index]) }}</td>
					</tr>
					
					@endforeach
					
					
					
					
					@if($invoice_vat_status === 'no' || $invoice_vat_status === '0')
					<tr> 
						<td class="text-center px-3" colspan="5" rowspan="2">{{ $exchange_rate != 0 ? '1USD = ' .number_format( (float)$exchange_rate). ' VND' : '' }}</td>
						<td class="text-center">Total Invoice</td>
						<td class="text-right px-3">{{ number_format($grand_total_beta) }}</td>
					</tr>
					<tr>
						<td class="text-center">Grand Total</td>
						<td class="text-right px-3">${{ number_format($grand_total_money) }}</td>
					</tr>
					@else
					<tr>
						<td class="text-left px-3" colspan="5" rowspan="3">
							Bank account at: {{$bank_name}} {{ $exchange_rate != 0 ? '( Swift: ASCBVNVX )' : '' }}<br>
							Account name: {{$acc_bank_name}}<br>
							Account number: {{$acc_bank_number}}<br>
							{{ $exchange_rate != 0 ? '1USD = ' .number_format( (float)$exchange_rate). ' VND' : '' }}
						</td>
						<td class="text-center ">Total Invoice</td>
						<td class="text-right px-3">${{ number_format($grand_total_beta) }}</td>
					</tr>
					<tr>
						<td class="text-center">VAT({{ $percent_vat }} %)</td>
						<td class="text-right px-3">${{ number_format($invoice_vat_money) }}</td>
						
					</tr>
					<tr>
						<td class="text-center">Grand Total</td>
						<td class="text-right fw-600 px-3">${{ number_format($grand_total_money) }}</td>
					</tr>
					@endif
				</tbody>
			</table>
			
			<table class="ml-auto" style="width: 100%; margin-top: 35mm;">
				<tbody>
					<tr>
						<td class="text-right" style="font-weight: bold;"><span class="d-inline-block text-center float-right" style="font-family: 'Noto Serif JP';">{{ $sign_name }}<br>ISSUED BY POSTE CO., LTD.</span></td>
					</tr>
				</tbody>
			</table>
		</div>
		
		
		
	</div>
	
</body>
</html>