@extends('layouts.admin')	
@section('title','Vat ')
@push('admin.styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.4/datepicker.min.css" />	
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- bootstrap wysihtml5 - text editor -->
@endpush
@section('content')
<section class="content-header">
	<h1>Vat<small>create</small></h1>
	<ol class="breadcrumb">
		<li><a href=""><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href="">Vat</a></li>
		<li><a href="">Create</a></li>
	</ol>
</section>
<div class="content">
	@if (count($errors) > 0)
	<div class="alert alert-danger">
		<ul>
			@foreach($errors->all() as $error)
			<li>{{$error}}</li>
			@endforeach
		</ul>
	</div>
	@endif
<form method="post" action="{{route('vat.store')}}" enctype="multipart/form-data">
	{{csrf_field()}}
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-heading">
					<h3 class="box-title">Add a new vat</h3>
				</div>
				<div class="box-body row">
					<div class="col-lg-6 col-sm-12">
						<div class="form-group">
						<label>Particular</label>
						<input type="text" name="particular" class="form-control" value="{{old('particular')}}">
						</div>
					</div>
					<div class="col-lg-6 col-sm-12">
						<div class="form-group">
							<label>Purchased From</label>
							<input type="text" name="purchased_from" class="form-control" value="{{old('purchased_from')}}">
						</div>
					</div>
					<div class="col-lg-6 col-sm-12">
						<div class="form-group">
							<label>Purchased Item</label>
							<input type="text" name="purchased_item" class="form-control" value="{{old('purchased_item')}}">
						</div>
					</div>
					<div class="col-lg-6 col-sm-12">
						<div class="form-group">
							<label>Bill No</label>
							<input type="number" name="bill_no" class="form-control" value="{{old('bill_no')}}">
						</div>
					</div>
					<div class="col-lg-6 col-sm-12">
						<div class="form-group">
						    <label>Bill Image</label>
						    <input type="file" name="bill_image" class="form-control" >
						</div>
					</div>
					<!-- <div class="form-group">
						<label>Date</label>
						<input type="text" id="nepaliDate1" class="bod-picker form-control" name="vat_date" autocomplete="off">
					</div> -->
					<!-- <div class="col-lg-6 col-sm-12">
						<div class="form-group ">
							<label>Paid Through</label>
							<select class="form-control paid_through" name="payment_type">
								<option value="Cash">Cash</option>
								<option value="Cheque">Cheque</option>
							</select>
						</div>
					</div> -->
					<div class="col-lg-12 col-sm-12">
						<div class="form-group bank hidden">
					      	<label>Bank</label>
					      	<select name="bank" class="form-control">
					        	@foreach($dashboard_bank->where('our_bank',1) as $bank)
					        	<option value="{{$bank->id}}">{{$bank->name}}</option>
					        	@endforeach
					      	</select>
					    </div>
					</div>
					<div class="col-lg-6 col-sm-12">
					    <div class="form-group">
						  	<label for="notVat"><input type="checkbox" id="notVat" name="notVat" value="1" >Not Vat</label>
						</div>
					</div>

					<div class="col-lg-12 col-sm-12">
					    <div class="form-group total_paid hidden">
							<label>Total Paid</label>
							<input type="text" name="total_paid" class="form-control total_paid_amount" value="{{old('total_paid')}}" data-value="">
						</div>
					</div>

					<div class="col-lg-6 col-sm-12">
						<div class="form-group taxable_amount">
							<label>Taxable Amount</label>
							<input type="text" name="taxable_amount" class="form-control taxable_amount_value" value="{{old('taxable_amount')}}">
						</div>
					</div>

					<div class="col-lg-6 col-sm-12">
						<div class="form-group vat_paid">
							<label>Vat Paid</label>
							<input type="text" name="vat_paid" class="form-control vat_paid" value="{{old('vat_paid')}}" readonly="">
						</div>
					</div>

					<div class="col-lg-6 col-sm-12">
						<div class="form-group total">
							<label>Total</label>
							<input type="text" name="total" class="form-control total" value="{{old('total')}}" readonly="">
						</div>
					</div>

					<div class="col-lg-6 col-sm-12">
						<div class="form-group round_total">
							<label>Round Total</label>
							<input type="text" name="round_total" class="form-control round_total_value" value="{{old('round_total')}}" readonly="">
						</div>
					</div>
					<!-- <div class="form-group">
					  <label for="tdsShow"><input type="checkbox" id="tdsShow" name="tdsShow" value="1" > Tds</label>
					</div>
					<div class="form-group hidden tds_amount">
					  <label>Tds Amount</label>
					  <input type="text" name="tds" class="form-control tds_amount_value" value="">
					</div> -->
					<div class="col-lg-6 col-sm-12">
						<div class="form-group">
							<input type="submit" name="submit" class="btn btn-success">
						</div>
					</div>
					
				</div>
			</div>
		</div>
		
	</div>
</form>
</div>
@endsection
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.4/datepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.js"></script>
<script>
		$(".bod-picker").nepaliDatePicker({
	    dateFormat: "%y-%m-%d",
	    closeOnDateSelect: true,
	    // minDate: formatedNepaliDate
	});
	$(document).ready(function(){
		$('.taxable_amount_value').on('keyup',function(){
			taxable_amount= $(this).val();
			if($.isNumeric(taxable_amount)){
				vat = 0.13*taxable_amount;
				$('.vat_paid').val(vat);
				$('.total').val(parseFloat(vat)+parseFloat(taxable_amount));
				roundvalue=parseFloat(vat)+parseFloat(taxable_amount);
				
				$('.round_total_value').val(Math.ceil(roundvalue));
			}
			
		});
	});

	$(document).ready(function(){
		$('.paid_through').on('change',function(){
			value = $(this).val();
			if(value=='Cash'){
				$('.bank').addClass('hidden');
			}
			if(value=="Cheque"){
				$('.bank').removeClass('hidden');
			}
		});
		$('input[name="tdsShow"]').click(function(){
		  	var tdsShowChecked = $('input[name="tdsShow"]:checked').val();
		  	if(tdsShowChecked==1){
		    	$('.tds_amount').removeClass('hidden');
		    	tdsamount = $('.tds_amount').val();
		    	total = $('.total').val();
		    	amount_without_vat=total-tdsamount;
		    	$('.total').val(total);
		  	}else{
		    	$('.tds_amount').addClass('hidden');
		  	}
		  
		});

	$('input[name="notVat"]').click(function(){	
		  	var notVatChecked = $('input[name="notVat"]:checked').val();
		  	if(notVatChecked==1){
		  		$('.total_paid').removeClass('hidden');
		    	$('.taxable_amount').addClass('hidden');
				$('.vat_paid').addClass('hidden');
				$('.total').addClass('hidden');
				$('.round_total').addClass('hidden');
				$('.taxable_amount_value').val('');
				$('.round_total_value').val('');
				$('.vat_paid').val('');
				$('.total').val('');
		  	}else{
		  		$('.total_paid').addClass('hidden');
		    	$('.taxable_amount').removeClass('hidden');
				$('.vat_paid').removeClass('hidden');
				$('.total').removeClass('hidden');
				$('.round_total').removeClass('hidden');
		  	}
		  
		});
	});


	$(document).ready(function(){
	$('.total_paid_amount').on('change',function(){
		value = $(this).val();
		$(this).data("value", value);
	});												
	$('.tds_amount_value').on('keyup',function(){
		//first find transaction with vat or not
		var notVatChecked = $('input[name="notVat"]:checked').val();
		tdsamount = $(this).val();
		if($.isNumeric(tdsamount)){
			if(notVatChecked==1){
				total_paid_amount = $('.total_paid_amount').data('value');
				amount_after_deducting_tds = parseFloat(total_paid_amount) - parseFloat(tdsamount); 
				$('.total_paid_amount').val(amount_after_deducting_tds);
			}else{
				//with vat

				taxable_amount=$('.taxable_amount_value').val();
				console.log(taxable_amount);
				if($.isNumeric(taxable_amount)){
					vat = 0.13*taxable_amount;
					total = parseFloat(vat)+parseFloat(taxable_amount);
					amount_after_deducting_tds = parseFloat(total) - parseFloat(tdsamount);
					console.log(amount_after_deducting_tds);
					$('.total').val(amount_after_deducting_tds);
					$('.round_total_value').val(amount_after_deducting_tds);
					
				}
			}
			
		}
		
		


	});
	});	


</script>
  
@endpush	