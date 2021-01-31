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
	<h1>Vat<small>edit</small></h1>
	<ol class="breadcrumb">
		<li><a href=""><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href="">Vat</a></li>
		<li><a href="">Edit </a></li>
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
<form method="post" action="{{route('vat.update',$detail->id)}}" enctype="multipart/form-data">
	{{csrf_field()}}
	<input type="hidden" name="_method" value="PUT">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-heading">
					<h3 class="box-title">Edit vat</h3>
				</div>
				<div class="box-body row">

					
					@if($detail->type=='sales')
					<div class="col-lg-6 col-12">
					<div class="form-group">
						<label>Sales To</label>
						<input type="text" name="sales_to" class="form-control" value="{{$detail->sales_to}}">
					</div>
					</div>
					@else
					

					<div class="col-lg-6 col-12">
						<div class="form-group">
							<label>Particular</label>
							<input type="text" name="particular" class="form-control" value="{{$detail->particular}}">
						</div>
					</div>

					<div class="col-lg-6 col-12">
						<div class="form-group">
							<label>Purchased From</label>
							<input type="text" name="purchased_from" class="form-control" value="{{$detail->purchased_from}}">
						</div>
					</div>

					<div class="col-lg-6 col-12">
						<div class="form-group">
							<label>Purchased Item</label>
							<input type="text" name="purchased_item" class="form-control" value="{{$detail->purchased_item}}">
						</div>
					</div>
					@endif

					<div class="col-lg-6 col-12">
						<div class="form-group">
							<label>Bill No</label>
							<input type="number" name="bill_no" class="form-control" value="{{$detail->bill_no}}">
						</div>
					</div>

					<div class="col-lg-6 col-12">
						<div class="form-group">
						    <label>Bill Image</label>
						    <input type="file" name="bill_image" class="form-control" >
						    @if($detail->bill_image)
						    <a href="{{asset('images/'.$detail->bill_image)}}" download="">Bill Image Download</a>
						    @endif
						</div>
					</div>

					<!-- <div class="col-lg-6 col-12">
						<div class="form-group ">
							<label>Paid Through</label>
							<select class="form-control paid_through" name="payment_type">
								<option value="Cash" {{$detail->payment_type=='Cash'?'selected':''}}>Cash</option>
								<option value="Cheque" {{$detail->payment_type=='Cheque'?'selected':''}}>Cheque</option>
							</select>
						</div>
					</div>
 -->
					<div class="col-lg-12 col-12">
						<div class="form-group bank hidden">
					      	<label>Bank</label>
					      	<select name="bank" class="form-control">
					        	@foreach($dashboard_bank->where('our_bank',1) as $bank)
					        	<option value="{{$bank->id}}">{{$bank->name}}</option>
					        	@endforeach
					      	</select>
					    </div>
					</div>

					<div class="col-lg-6 col-12">
						<div class="form-group">
						  	<label for="notVat"><input type="checkbox" id="notVat" name="notVat" value="1" {{$detail->not_vat==1?'checked':'' }}>Not Vat</label>
						</div>
					</div>

					<!-- <div class="form-group">
						<label>Date</label>
						<input type="text" name="vat_date" class="bod-picker form-control" value="{{$detail->vat_date}}" id="nepaliDate1">
					</div> -->
					
					<div class="col-lg-12 col-12">
						<div class="form-group total_paid {{$detail->not_vat==1?'':'hidden'}}">
							<label>Total Paid</label>
							<input type="text" name="total_paid" class="form-control total_paid_amount" value="{{$detail->total}}" data-value="">
						</div>
					</div>

					@if($detail->not_vat!=1)
					<div class="col-lg-6 col-12">
						<div class="form-group taxable_amount">
							<label>Taxable Amount</label>
							<input type="text" name="taxable_amount" class="form-control taxable_amount_value" value="{{$detail->taxable_amount}}">
						</div>
					</div>

					<div class="col-lg-6 col-12">
						<div class="form-group vat_paid">
							<label>Vat Paid</label>
							<input type="text" name="vat_paid" class="form-control vat_paid" value="{{$detail->vat_paid}}" readonly="">
						</div>
					</div>

					<div class="col-lg-6 col-12">
						<div class="form-group total">
							<label>Total</label>
							<input type="text" name="total" class="form-control total" value="{{$detail->total}}" readonly="">
						</div>
					</div>

					<div class="col-lg-6 col-12">
						<div class="form-group round_total">
							<label>Round Total</label>
							<input type="text" name="round_total" class="form-control round_total_value" value="{{$detail->round_total}}" readonly="">
						</div>
					</div>
					@endif
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
 <script>
 	$(".bod-picker").nepaliDatePicker({
    dateFormat: "%y-%m-%d",
    closeOnDateSelect: true,
    // minDate: formatedNepaliDate
});

 	   $(window).on('load', function () {
 	     value=$('.paid_through').val();
 	     console.log(value);
 	    if(value=='Cheque'){
 	     $('.bank').removeClass('hidden');
 	     $('.wallet').addClass('hidden');
 	   }
 	   if(value=='Cash'){
 	     $('.bank').addClass('hidden');
 	     $('.wallet').addClass('hidden');
 	   }
 	   if(value=='Digital Wallet'){
 	     
 	     $('.bank').addClass('hidden');
 	     $('.wallet').removeClass('hidden');
 	   }

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