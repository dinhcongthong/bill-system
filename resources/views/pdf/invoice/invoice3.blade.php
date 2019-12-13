<p style="color: red;">{ $name }}</p>
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
<style>
	body, * {font-family: Times News Roman; !important;}
	.font-fam-kanji {font-family: kozuka_mincho_pro_b; !important;}
	/*.border { border: 1px solid black; }*/
	.my-border-bottom {border-bottom: 1px solid black;}
	/*.text-center { text-align: center; }*/
	/*.text-right { text-align: right; }*/
	.margin-auto { margin: 0 auto; }
	.mt-15 {margin-top: 15px;}
	.mt-200 {margin-top: 200px;}
	.p-0 {padding: 0;}
	.width-a4 {max-width: 592px;}
	.width-92 {width: 92px;}
	.width-102 {width: 102px;}
	.width-245 {width: 245px;}
	.width-255 {width: 252px;}
	.height-a4 {max-height: 842px;}
	.font-news-roman {font-family: Times News Roman;}
	.font-15 {font-size: 15px;}
	.font-30 {font-size: 30px;}
	.font-rem-poste {font-size: 0.9rem;}
/*	.col-12 {width: 100%;}
	.col-7 {width: 60%;}
	.col-5 {width: 40%;}*/
	.mt-100 {margin-top: 100px;}
	.mb-100 {margin-bottom: 100px;}
	h2 {font-family: Times News Roman; font-weight: bold;}
	.font-rem-poste {font-size: 12px;}
	/*.height-view {max-height: 842px;}*/
</style>

<div class="width-a4  margin-auto" >
	<div class="col-12 p-0">
		<table class="margin-auto mt-15"  style="width: 100%;">
			<tr>
				<td rowspan="2" class="font-weight-bold font-30 text-center" style="vertical-align: top;">INVOICE</td>
				<td class="font-rem-poste text-center">Date 12/04/2019</td>
			</tr>
			<tr>
				<td class="font-rem-poste" style="padding-left: 32%;">Number</td>
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
					<!-- <img style="display: block; margin-left: auto;" class="img-responsive" alt="logo" src="{URL::to('images/logo.jpg')}}" width="150" height="50" /></br> -->
					<img style="display: block; margin-right: 0; float: right;" class="img-responsive" alt="logo" src="{{public_path('images\logo.png')}}" width="150" height="50" /></br>
				</td>
			</tr>
		</table>
	</div>
</div>

<div class="width-a4  margin-auto" style="margin-top: 45px;">
	<div class="width-a4">
		<div class="col-12 p-0">
			<table class="margin-auto" style="width: 100%;">
				<tbody>
					<tr>
						<td class="text-right" style="font-size: 0.9rem;">{{ $poste->name_poste }}</td>
					</tr>
				</tbody>
			</table>

			<!-- test -->
			<table class="margin-auto"  style="width: 100%;">
			    <tbody>
			    	<tr>
			    		<td class="width-102">Company name</td>
			    		<td class="width-240 text-right font-fam-kanji font-weight-bold" >Forte 様</td>
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
			    		<td class="width-245 my-border-bottom text-center">Mr.Deyama</td>
			    		<td class="width-255 font-rem-poste text-right">Tax code: <span>{{ $poste->tax_code }}</span></td>
			    	</tr>
			    	<tr >
			    		<td class="width-92 my-border-bottom" style="vertical-align: top;">ADD:</td>
			    		<td class="width-245 my-border-bottom">98N Le Lai Str, Ben Thanh Ward. Dist 1, HCMC</td>
			    		<td class="width-255 font-rem-poste text-right">Address(HQ): <span>{{ $poste->address_poste }}</span></td>
			    	</tr>
		    		<tr >
			    		<td class="my-border-bottom width-92">TEL:</td>
			    		<td class="col-4 p-0 my-border-bottom text-center">0909 255 533</td>
			    		<td class="col p-0 font-rem-poste text-right">Tel: <span>{{ $poste->phone_poste }}</span></td>
			    	</tr>
			    	<tr >
			    		<td class="col-2 p-0 my-border-bottom width-92">FAX:</td>
			    		<td class="col-4 p-0 my-border-bottom text-center"></td>
			    		<td class="col p-0 font-rem-poste text-right">Email: <span>{{ $poste->email_poste }}</span></td>
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
				<td class="width-245 my-border-bottom font-weight-bold" style="font-size: 25px;">$100  (2,300,000VND)</td>
				<td class="width-255"></td>
			</tr>
		</tbody>
	</table>

	<table class="margin-auto mt-15" border="1" style="width: 100%;">
	    <thead >
	        <tr style="background-color: #ccc;">
	            <!-- <th class="text-center">No</th> -->
	            <td class="text-center">No</td>
	            <td class="text-center">Name of product</td>
	            <td class="text-center">QTY</td>
	            <td class="text-center">Price</td>
	            <td class="text-center">Total</td>
	        </tr>
	    </thead>
	    <tbody>
	    	<tr>
		    	<td class="text-center">1</td>
		    	<td class="text-left">Premium Page April</td>
		    	<td class="text-center" rowspan="2">1 month</td>
		    	<td class="text-center">$200</td>
		    	<td class="text-center">$200</td>
	    	</tr>
	    	<tr class="">
	    		<td class="text-center">2</td>
	    		<td class="text-left">Discount 10%</td>
		    	<td class="text-center">$100</td>
		    	<td class="text-center">$100</td>

	    	</tr>
	    	<tr>
	    		<td class="text-center" rowspan="3" colspan="3">1USD=<span>{{ number_format( (float)$poste->exchange_rate) }}</span>VND</td>
	    		<td class="text-center">Total</td>
	    		<td class="text-center">$100</td>

	    	</tr>
	    	<tr>
	    		<td class="text-center">VAT(10%)</td>
	    		<td class="text-center">0</td>

	    	</tr>
	    	<tr>
	    		<td class="text-center">Grand Total</td>
	    		<td class="text-center">$100</td>
	    	</tr>
	    </tbody>
	</table>

	<table class="margin-auto mt-200" style="width: 100%;">
		<tbody>
			<tr>
				<td class="text-right">ISSUED BY POSTE CO., LTD.</td>
			</tr>
		</tbody>
	</table>
</div>

<!-- copy - test long content -->
<div class="width-a4  margin-auto" >
	<div class="col-12 p-0">
		<table class="margin-auto mt-15"  style="width: 100%;">
			<tr>
				<td rowspan="2" class="font-weight-bold font-30 text-center" style="vertical-align: top;">INVOICE</td>
				<td class="font-rem-poste text-center">Date 12/04/2019</td>
			</tr>
			<tr>
				<td class="font-rem-poste" style="padding-left: 32%;">Number</td>
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
					<!-- <img style="display: block; margin-left: auto;" class="img-responsive" alt="logo" src="{URL::to('images/logo.jpg')}}" width="150" height="50" /></br> -->
					<img style="display: block; margin-right: 0; float: right;" class="img-responsive" alt="logo" src="{{public_path('images\logo.png')}}" width="150" height="50" /></br>
				</td>
			</tr>
		</table>
	</div>
</div>

<div class="width-a4  margin-auto" style="margin-top: 45px;">
	<div class="width-a4">
		<div class="col-12 p-0">
			<table class="margin-auto" style="width: 100%;">
				<tbody>
					<tr>
						<td class="text-right" style="font-size: 0.9rem;">{{ $poste->name_poste }}</td>
					</tr>
				</tbody>
			</table>

			<!-- test -->
			<table class="margin-auto"  style="width: 100%;">
			    <tbody>
			    	<tr>
			    		<td class="width-102">Company name</td>
			    		<td class="width-240 text-right font-fam-kanji font-weight-bold" >Forte 様</td>
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
			    		<td class="width-245 my-border-bottom text-center">Mr.Deyama</td>
			    		<td class="width-255 font-rem-poste text-right">Tax code: <span>{{ $poste->tax_code }}</span></td>
			    	</tr>
			    	<tr >
			    		<td class="width-92 my-border-bottom" style="vertical-align: top;">ADD:</td>
			    		<td class="width-245 my-border-bottom">98N Le Lai Str, Ben Thanh Ward. Dist 1, HCMC</td>
			    		<td class="width-255 font-rem-poste text-right">Address(HQ): <span>{{ $poste->address_poste }}</span></td>
			    	</tr>
		    		<tr >
			    		<td class="my-border-bottom width-92">TEL:</td>
			    		<td class="col-4 p-0 my-border-bottom text-center">0909 255 533</td>
			    		<td class="col p-0 font-rem-poste text-right">Tel: <span>{{ $poste->phone_poste }}</span></td>
			    	</tr>
			    	<tr >
			    		<td class="col-2 p-0 my-border-bottom width-92">FAX:</td>
			    		<td class="col-4 p-0 my-border-bottom text-center"></td>
			    		<td class="col p-0 font-rem-poste text-right">Email: <span>{{ $poste->email_poste }}</span></td>
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
				<td class="width-245 my-border-bottom font-weight-bold" style="font-size: 25px;">$100  (2,300,000VND)</td>
				<td class="width-255"></td>
			</tr>
		</tbody>
	</table>

	<table class="margin-auto mt-15" border="1" style="width: 100%;">
	    <thead >
	        <tr style="background-color: #ccc;">
	            <!-- <th class="text-center">No</th> -->
	            <td class="text-center">No</td>
	            <td class="text-center">Name of product</td>
	            <td class="text-center">QTY</td>
	            <td class="text-center">Price</td>
	            <td class="text-center">Total</td>
	        </tr>
	    </thead>
	    <tbody>
	    	<tr>
		    	<td class="text-center">1</td>
		    	<td class="text-left">Premium Page April</td>
		    	<td class="text-center" rowspan="2">1 month</td>
		    	<td class="text-center">$200</td>
		    	<td class="text-center">$200</td>
	    	</tr>
	    	<tr class="">
	    		<td class="text-center">2</td>
	    		<td class="text-left">Discount 10%</td>
		    	<td class="text-center">$100</td>
		    	<td class="text-center">$100</td>

	    	</tr>
	    	<tr>
	    		<td class="text-center" rowspan="3" colspan="3">1USD=<span>{{ number_format( (float)$poste->exchange_rate) }}</span>VND</td>
	    		<td class="text-center">Total</td>
	    		<td class="text-center">$100</td>

	    	</tr>
	    	<tr>
	    		<td class="text-center">VAT(10%)</td>
	    		<td class="text-center">0</td>

	    	</tr>
	    	<tr>
	    		<td class="text-center">Grand Total</td>
	    		<td class="text-center">$100</td>
	    	</tr>
	    </tbody>
	</table>

	<table class="margin-auto mt-200" style="width: 100%;">
		<tbody>
			<tr>
				<td class="text-right">ISSUED BY POSTE CO., LTD.</td>
			</tr>
		</tbody>
	</table>
</div>