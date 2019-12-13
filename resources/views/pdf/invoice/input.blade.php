@extends('templates.master')

@section('content')
<div class="container">
	<div class="col-md-12">
    	<input id="input" type="text" name="data">

<!-- 		    	<div class="form-group" id="box2" >
    		<div class="input-group date" id="form_datetimepicker">
    			<input type="text" class="form-control" />	<span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></span>
    		</div>
		</div> -->

		<input type="text" class="form-control" id="form_datetimepicker" />
    	<a href="{{ URL::to('/pdf/view/' . '$contract->id'.'/' . '$invoice->id') }}">View Invoice</a>
    	<!-- <button class="btn-view-invoice" >View Invoice</button> -->

		<div class="table-preview">

    	</div>
	</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
	
	// $("[data-header-left='true']").parent().addClass("pmd-navbar-left");

	$(document).ready(function () {

		$("#form_datetimepicker").datetimepicker({
	     	icons: {
		        time: 'far fa-clock',
		        date: 'far fa-calendar',
		        up: 'fas fa-arrow-up',
		        down: 'fas fa-arrow-down',
		        previous: 'fas fa-chevron-left',
		        next: 'fas fa-chevron-right',
		        today: 'far fa-calendar-check',
		        clear: 'fas fa-trash',
		        close: 'fas fa-times'
		    },
			// 'format' : "YYYY-MM-DD HH:mm:ss", // HH:mm:ss
			format : "DD-MM-YYYY", // HH:mm:ss
			// inline: true,
			showTodayButton: true,
			showClose: true,
			// icons: {
   //       		rightIcon: icons
   //       	}
	    });

		// preview();
    });

    function preview() {
	   $('.btn-view-invoice').on("click", function() {
	    	// e.preventDefault();
	    	// alert('Hello');
	    	var APPURL = {!! json_encode(url('/')) !!}
	    	console.log(APPURL);
        	var value = $('#input').val() ;
        	var row = '2';

	        $.ajax({
	            method: 'GET',
	            // url: "{ route('get_view', value )  }}"

	            url: APPURL + '/pdf/view/' + value + '/' + row,

	            // base_url + '/admin/ads/update-inform',
	            // data: {
	            //     'id': id
	            // }
	        })
	        .done(function(data) {
	            console.log(data);
	            // console.log(value);
	            $('.table-preview').html(data);
	        })
	        .fail(function(xhr, status, error) {
	            console.log(this.url);
	            console.log(error);
	        })
	    });
	}
</script>
@endsection