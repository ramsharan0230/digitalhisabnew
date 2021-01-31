@extends('layouts.admin')
@section('title','Purchase List')
@push('styles')

<style>

table#example1 {
    width: 100%;
    overflow-x: scroll;
    display: block;
    white-space: nowrap;
}

  .export-wrapper {

    display: flex;
    align-items: center;
    margin: 10px 0;
    margin-right: 10px;

  }

  .purchase-btn {

    margin-top: 0 !important;
    margin-right: 10px;
  }

  .form-group {

    width: 100%;
  }

  .whole-form-wrapper,
  .whole-btn-wrapper {

    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .whole-form-wrapper {

    align-items: baseline;
  }


</style>
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
@endpush
@section('content')
<section class="content-header">
  @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
    @endif
	   <h1>Purchase <small>List</small></h1>

    <div class="whole-form-wrapper">
        <div class="export-wrapper">
            
          <a href="{{route('purchase.create')}}" class="btn purchase-btn btn-success">Add Purchase</a>
          

          
            
            
          
      </div>
    
        <!-- <div class="form-group form-group-wrapper">
          <label>Select Month</label>
          <select name="month" class="form-control" id="month">
            <option disabled="true" selected="true">Select Month</option>
            <option value="01">Baishak</option>
            <option value="02">Jestha</option>
            <option value="03">Ashad</option>
            <option value="04">Shrawan</option>
            <option value="05">Bhadra</option>
            <option value="06">Ashoj</option>
            <option value="07">Kartik</option>
            <option value="08">Mangsir</option>
            <option value="09">Poush</option>
            <option value="10">Magh</option>
            <option value="11">Falgun</option>
            <option value="12">Chaitra</option>
          </select>
        </div> -->
  </div>

    <div class="box">
        <div class="row">
            <div class="col-lg-6">
                <div class="box-header"><h3 class="box-title">Custom Date</h3></div>
                <div class="box-body">
                  <div class="col-md-5">
                    <div class="form-group">
                      <label>Start Date</label>
                      <input type="text" id="start_date" class="bod-picker form-control" name="start_date" autocomplete="off" value="">
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <label>End Date</label>
                      <input type="text" id="end_date" class="bod-picker form-control" name="end_date" autocomplete="off" value="">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group pro-submit-btn">
                      <input type="submit" name="submit" value="submit" class="btn btn-success customDateSearch">
                    </div>
                  </div>
                </div>
            </div>

            <div class="col-lg-6">
              <div class="box-header"><h3 class="box-title">Year and Month</h3></div>

                <div class="box-body">
                    <div class="erport-wrapp profit-loss-wrapp">

                      <div class="form-group form-group-wrapper">
                          <label>Input Year</label>
                          <input class="form-control" type="text" placeholder="Input Year">
                      </div>
                      <div class="select-mont-wrapp form-group-wrapper">
                          <div class="form-group form-group-wrapper">
                            <label>Select Month</label>
                            <select name="month" class="form-control" id="month">
                              <option disabled="true" selected="true">Select Month</option>
                              <option value="01">Baishak</option>
                              <option value="02">Jestha</option>
                              <option value="03">Ashad</option>
                              <option value="04">Shrawan</option>
                              <option value="05">Bhadra</option>
                              <option value="06">Ashoj</option>
                              <option value="07">Kartik</option>
                              <option value="08">Mangsir</option>
                              <option value="09">Poush</option>
                              <option value="10">Magh</option>
                              <option value="11">Falgun</option>
                              <option value="12">Chaitra</option>
                            </select>
                          </div>
                      </div>
                      <form class="export-form" method="post" action="{{route('vatExport')}}">
                        {{csrf_field()}}
                        
                        <input type="hidden" name="month" class="monthvalue" value="">
                        <input type="hidden" name="type" value="0">
                        <input type="hidden" name="segment" value="{{Request::segment(2)}}" id="segment">
                        <input type="submit" name="Export" value="Export" class="btn btn-info">
                      </form>
                  </div>
              </div>
            </div>
        </div>
    </div>


  	<ol class="breadcrumb">
    	<li><a href=""><i class="fa fa-dashboard"></i>Dashboard</a></li>
    	<li><a href="">Purchase</a></li>
    	<li><a href="">List</a></li>
  	</ol>
</section>

<div class="content">
  	@if(Session::has('message'))
  	<div class="alert alert-success alert-dismissible message">
    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
          	<span aria-hidden="true">&times;</span>
      	</button>
      	{!! Session::get('message') !!}
  	</div>
  	@endif
  	<div class="row">
    	<div class="col-xs-12">
      		<div class="box">
        		<div class="box-header">
          			<h3 class="box-title">Data Table</h3>
        		</div>
        	<div class="box-body append">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>S.N.</th>
                  <th>Date</th>
                  
                  <th>Purchased From</th>
                  <th>Bill No</th>
                  <th>Date</th>
                  <th>Taxable Amount</th>
                  <th>Vat Paid</th>
                  <th>Total</th>
                  <th>Round Total(VAT)</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="sortable">
                @php($i=1)
                @foreach($details as $detail)
                <tr id="{{ $detail->id }}">
                  <td>{{$i}}</td>
                  <td>{{$detail->purchased_from}}</td>
                  <td>{{$detail->bill_no}}</td>     
                  <td>{{$detail->vat_date}}</td>
                  <td>{{$detail->taxable_amount}}</td>
                  <td>{{$detail->vat_paid}}</td>
                  <td>{{$detail->total}}</td>
                  <td>{{$detail->round_total}}</td>
                  
                  <td class="whole-btn-wrapper">
                    @if($detail->collected_type==null)
                    <a class="btn btn-info edit" href="{{route('purchase.edit',$detail->id)}}" title="Edit"><span class="fa fa-edit"></span></a>
                    <form method= "post" action="{{route('purchase.destroy',$detail->id)}}" class="delete btn btn-danger">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn-delete" style="display:inline"><span class="fa fa-trash"></span></button>
                    </form>
                    @endif
                    @if($detail->collected!=1)
                    <div class="form-group" id="select{{$detail->id}}">
                      <select class="form-control purchasePayment">
                        <option disabled="true" selected="true">Make Payment</option>
                        <option value="partial" data-id="{{$detail->id}}">Partial</option>
                        @if($detail->collected_type!='partial')
                        <option value="full" data-id="{{$detail->id}}">Full</option>
                        @endif
                      </select>
                    </div>
                    @endif
                    <a href="{{route('purchasePaymentList',$detail->id)}}" class="btn btn-success">Payments</a>
                  </td>
                </tr>
                @php($i++)
                @endforeach
              </tbody>
              <?php

              $sales = \App\Models\Sales::orderBy('created_at','desc')->get();
              $total_sales=$sales->sum('total');
              $salesVat=$sales->sum('vat_paid');
              $purchase_vat = $details->sum('vat_paid');
              $difference = $salesVat-$purchase_vat;

              ?>
              <tr>
                  <!-- <td colspan="4"><h4>Total Sales =<b>({{$total_sales}}) </b></h4></td> -->
                  <!-- <td colspan="5"><h4>Total Sales Vat = <b>{{$salesVat}}</b></h4></td> -->
              </tr>
              <tr>
                  <td colspan="4"><h4>Total Purchase = <b>({{$details->sum('total')}})</b></h4> </td>
                  <td colspan="5"><h4>Total Purchase Vat = <b>{{$purchase_vat}}</b></h4></td>
              </tr>
              <tr>
                  <!-- <td colspan="9"><h4>Conclusion(sales vat - purchase vat)= <b>{{$difference}}</b></h4></td> -->
              </tr>
            </table>	
        	</div>
      	</div>
    </div>
</div>
</div>
@include('admin.purchasePayment.include.modal')
@endsection
@push('script')
  <!-- DataTables -->
  <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('backend/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
  <!-- SlimScroll -->
  <script src="{{ asset('backend/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
  <!-- FastClick -->
  <script src="{{ asset('backend/plugins/fastclick/fastclick.js') }}"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script >
     $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function(){
        $('.delete').submit(function(e){
        e.preventDefault();
        var message=confirm('Are you sure to delete?');
        if(message){
            this.submit();
        }
        return;
        });
    });


    $(function () {
        $("#example1").DataTable();
    });
    $('.message').delay(5000).fadeOut(400);

    $(document).ready(function(){
      $('#month').on('change',function(){
        value=$(this).val();
        segment_2=$('#segment').val();
        console.log(segment_2);
        $.ajax({
          method:'post',
          url:"{{route('purchaseSearchByMonth')}}",
          data:{value:value,segment:segment_2},
          success:function(data){
            $('.table-striped').remove();
            $('.append').html(data);
            
            $('.export').removeClass('hidden');
            $('.monthvalue').val(value);
          }
        });
      });
    });

    $(document).ready(function(){
      $('.export').click(function(e){
        e.preventDefault();
        month=$('#month').val();
        $.ajax({
          method:"post",
          data:{month:month},
          url:"{{route('vatExport')}}",
          success:function(data){
            console.log(data);
          }
        });
      });
    });

  $(document).ready(function(){
    $(document).on('change','.purchasePayment',function(e){
      e.preventDefault();
      
      
      if($(this).val()=='partial'){
        
        id=$(this).find(':selected').data('id');
        $.ajax({
          method:"post",
          url:"{{route('partialPurchasePayment')}}",
          data:{id:id},
          success:function(data){
            console.log(data);
            if(data.message=='success'){
              $('#myModal .modal-body').html(data.html);
              $('#myModal').modal('show').on('hide', function() {
                $('#new_passenger').modal('hide')
                });
              $('.purchasePayment').prop('selectedIndex',0);
            }
            
          }
        });
      }else{
        id=$(this).find(':selected').data('id');
        $.ajax({
          method:"post",
          url:"{{route('fullPurchasePayment')}}",
          data:{id:id},
          success:function(data){
            console.log(data.html);
            if(data.message=='success'){
              $('#myModal .modal-body').html(data.html);
              $('#myModal').modal('show').on('hide', function() {
                $('#new_passenger').modal('hide')
              });
              $('.collectionWithVat').prop('selectedIndex',0);
              //$('#select'+id).addClass('hidden');
            }
          }
        });
      }


      $(document).on('keyup','.amount',function(){
        remaining_amount=$('.remaining_amount').val();
        if(parseInt(remaining_amount)>=parseInt($(this).val())){
          $('.submitButton').removeClass('hidden');
        }else{
          $('.submitButton').addClass('hidden');
        }
      });
    });
  });


  $(".bod-picker").nepaliDatePicker({
      dateFormat: "%y-%m-%d",
      closeOnDateSelect: true,
      // minDate: formatedNepaliDate
  });
</script>
@endpush
