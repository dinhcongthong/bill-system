<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<style>
	body {font-family: Times News Roman; !important;}

	.font-fam-kanji {font-family: kozuka_mincho_pro_b !important;}
	/*.border { border: 1px solid black; }*/
	.my-border-bottom {border-bottom: 1px solid black;}
	/*.text-center { text-align: center; }*/
	/*.text-right { text-align: right; }*/
	.margin-auto { margin: 0 auto; }
	.mt-15 {margin-top: 15px;}
	.mt-200 {margin-top: 200px;}
	.p-0 {padding: 0;}
	.width-a4 {
		/*width: 592px;*/
		width: 690px;
		/*width: 793.7007874px;*/
		/*width: 210mm;*/
	}
	.height-a4 {
		min-height: 842px;
		/*max-height: 297mm;*/
		/*max-height: 1122.519685px;*/
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
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
    }
	.width-92 {
		width: 92px;
		/*width: 24.341666667mm;*/
	}
	.width-102 {
		width: 102px;
		/*width: 26.9875mm;*/
	}
	.width-245 {
		width: 245px;
		/*width: 64.822916667mm;*/
	}
	.width-255 {
		width: 252px;
		/*width: 67.46875mm;*/
	}
	
	/*.font-news-roman {font-family: Times News Roman;}*/
	.font-15 {font-size: 13px;}
	.font-30 {font-size: 30px;}
	.font-rem-poste {font-size: 14px;}
/*	.col-12 {width: 100%;}
	.col-7 {width: 60%;}
	.col-5 {width: 40%;}*/
	.mt-100 {margin-top: 100px;}
	.mb-100 {margin-bottom: 100px;}
	h2 {font-family: Times News Roman; font-weight: bold;}

	/*.height-view {max-height: 842px;}*/
</style>
<div class="page">
	<!-- <div class="subpage"> -->

    	
		<div class="width-a4  margin-auto" >
			<div class="col-12 p-0">
				<table class="margin-auto mt-15"  style="width: 100%;">
					<tr>
						<td class="font-weight-bold font-30 text-center" style="vertical-align: top;">INVOICE</td>
						<!-- <td class="font-rem-poste text-left" style="padding-left: 30%;">{ $today }}</td> -->
					</tr>
					<tr>
						<td class="font-rem-poste text-right">{{ $today }} - Contract code: {{ $contract_code }}</td>
						
					</tr>
					<tr>
						<td class="font-rem-poste  text-right" style="padding-right: 130px;">Number: {{ $number }}</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="width-a4" style="height: 1px; background-color: black; margin: 0 auto;"></div>

		<div class="width-a4  margin-auto" >
			<div class="col-12 p-0">
				<table class="margin-auto mt-15" style="width: 100%;">
					<tr>
						<td class="col-12 p-0">
							<!-- <img style="display: block; margin-right: 0; float: right;" class="img-responsive" alt="logo" src="{public_path('images\logo-main.png')}}" width="150" height="60" /></br> -->
							<!-- {URL::to('images/logo.jpg')}} -->
							<img style="display: block; margin-right: 0; float: right;"  alt="logo" src="{{ public_path('area/' . $area_name . '.' . $path_logo_file) }}" /><br>

						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="width-a4  margin-auto" style="margin-top: 65px;">
			<div class="width-a4">
				<div class="col-12 p-0">
					<table class="margin-auto" style="width: 100%;">
						<tbody>
							<tr>
								<td class="text-right" style="font-size: 0.9rem;font-weight: bold;">{{ $name_poste }}</td>
							</tr>
						</tbody>
					</table>

					<!-- test -->
					<table class="margin-auto"  style="width: 100%;">
					    <tbody>
					    	<tr>
					    		<td class="width-102">Company name</td>
					    		<td class="width-240 text-center font-fam-kanji font-weight-bold align-top" >{{ $client->name }}</td>
					    		<td class="width-250 text-right"></td>
					    	</tr>
					    </tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="width-a4  margin-auto" >
			<div class="width-a4">
				<div class="col-12 p-0">
					<table class="margin-auto mt-10"  style="width: 100%;">
					    <tbody>
					    	<tr>
					    		<td class="width-92 my-border-bottom">ATTN:</td>
					    		<td class="width-245 my-border-bottom text-center">{{ $client->client_in_charge }}</td>
					    		<td class="width-255 font-rem-poste text-right">Tax code: <span>{{ $tax_code }}</span></td>
					    	</tr>
					    	<tr >
					    		<td class="width-92 my-border-bottom" style="vertical-align: top;">ADD:</td>
					    		<td class="width-245 my-border-bottom">{{ $client->address_client_in_charge }}</td>
					    		<td class="width-255 font-rem-poste text-right">Address(HQ): <span>{{ $address_poste }}</span></td>
					    	</tr>
				    		<tr >
					    		<td class="my-border-bottom width-92">TEL:</td>
					    		<td class="col-4 p-0 my-border-bottom text-center">{{ $client->phone_client_in_charge }}</td>
					    		<td class="col p-0 font-rem-poste text-right">Tel: <span>{{ $phone_poste }}</span></td>
					    	</tr>
					    	<tr >
					    		<td class="col-2 p-0 my-border-bottom width-92">FAX:</td>
					    		<td class="col-4 p-0 my-border-bottom text-center"></td>
					    		<td class="col p-0 font-rem-poste text-right">Email: <span>{{ $email_poste }}</span></td>
					    	</tr>
					    </tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="width-a4  margin-auto" >
			<table class="margin-auto mt-15" style="width: 100%;">
				<tbody>
					<tr>
						<td class="width-92 my-border-bottom align-bottom">Total</td>
						<td class="width-245 my-border-bottom font-weight-bold" style="font-size: 25px;">{{ isset($exchange_rate) ? '$'.$grand_total.' ('.number_format($exchange_rate*$grand_total).'VND)' : '' }}</td>
						<td class="width-255"></td>
					</tr>
				</tbody>
			</table>

			<table class="margin-auto mt-15" border="1" style="width: 100%;">
			    <thead >
			        <tr style="background-color: #ccc;">
		            <td class="text-center">No</td>
		            <td class="text-center">Name of product</td>
		            <td class="text-center">Start date</td>
		            <td class="text-center">End date</td>
		            <td class="text-center">QTY (month)</td>
		            <td class="text-center">Price</td>
		            <td class="text-center">Discount</td>
		            <td class="text-center" colspan="2">Total</td>
			        </tr>
			    </thead>
			    <tbody>
		    		@foreach($contract_services as $contract_service)
			    	<tr>
				    	<td class="text-center">{{ $contract_service->id }}</td>
				    	<td class="text-left">{{ $contract_service->getServiceName->name }}</td>
				    	<td class="text-center">{{ date("m-d-Y", strtotime($start_date) ) }}</td>
				    	<td class="text-center">{{ date("m-d-Y", strtotime($end_date) ) }}</td>
						<td class="text-center">{{ $contract_service->quantity_months }}</td>
				    	<td class="text-center">{{ $contract_service->getServiceName->price }}</td>
				    	<td class="text-center">{{ $service_discount }}</td>
				    	<td class="text-center" colspan="2">{{ $total }}</td>
			    	</tr>
			    	
					@endforeach


			    	<tr>
			    		<!-- <td class="text-center" colspan="6" rowspan="3">1USD=<span>{ ($exchange_rate != '') ? number_format( (float)$exchange_rate) : '' }}</span>VND</td> -->
			    		@if($invoice_vat === 0)
			    		<td class="text-center" colspan="6" rowspan="3">1USD=<span>{{ isset($exchange_rate) ? number_format( (float)$exchange_rate) : '' }}</span>VND</td>
			    		@else
						<td class="text-left" colspan="6" rowspan="3">
							Bank account at: {{$bank_name}} ( Swift: ASCBVNVX )<br>
							Account name: {{$acc_bank_name}}<br>
							Account number: {{ $acc_bank_number }}<br>
							1USD=<span>{{ isset($exchange_rate) ? number_format( (float)$exchange_rate) : '' }}</span>VND
						</td>
			    		@endif
			    		<td class="text-center">Total Invoice</td>
			    		<td class="text-center" colspan="2">{{ $grand_total }}</td>

			    	</tr>
			    	<tr>
			    		<td class="text-center">VAT ({{ $percent_vat }}%)</td>
			    		<td class="text-center" colspan="2">${{ $invoice_vat }}</td>

			    	</tr>
			    	<tr>
			    		<td class="text-center">Grand Total</td>
			    		<td class="text-center" colspan="2">${{ $grand_total_after_vat }}</td>
			    	</tr>
			    </tbody>
			</table>

			<table class="margin-auto mt-200" style="width: 100%;">
				<tbody>
					<tr>
						{{ $sign_name }}
					</tr>
					<tr>
						<td class="text-right" style="font-weight: bold;">ISSUED BY POSTE CO., LTD.</td>
					</tr>
				</tbody>
			</table>
		</div>
	<!-- </div> -->
</div>