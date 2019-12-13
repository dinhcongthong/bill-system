<!-- <p style="color: red;">data</p> -->
<p style="color: red;">{{ $name }}</p>
<style>
	/*.border { border: 1px solid black; }*/
	.text-center { text-align: center; }
	.width-a4 {max-width: 592px}
	.height-a4 {max-height: 842px;}
</style>

<div class="width-a4 height-a4"> <!-- A4 size -->
	<div class="col-12">
		<div class="row">
			<div class="col-7 text-center">
				<h2>INVOICE</h2>
			</div>
			<div class="col">

			</div>
		</div>
	<div class="width-a4" style="padding: 0.5px; background-color: black; margin: 0 auto;"></div>
	</div>
	<table width="100%;" border="1">
	    <thead >
	        <tr style="background-color: gray;">
	            <th class="text-center">No</th>
	            <th class="text-center">Name of product</th>
	            <th class="text-center">QTY</th>
	            <th class="text-center">Price</th>
	            <th class="text-center">Total</th>
	        </tr>
	    </thead>
	    <tbody>
	    	<tr>
		    	<td class="text-center">1</td>
		    	<td class="text-center">Premium Page April</td>
		    	<td class="text-center">1 month</td>
		    	<td class="text-center">$200</td>
		    	<td class="text-center">$200</td>
	    	</tr>
	    	<tr class="border">
	    		<td class="text-center" rowspan="3" colspan="2">1USD=23,000VND</td>
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
</div>