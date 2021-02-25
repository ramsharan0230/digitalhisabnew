@extends('layouts.admin')
@section('title','Daybook')
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
@endpush
@section('content')
<section class="content-header">
  <h1>Daybook</h1>

        <!-- <form class="export-form" method="post" action="{{route('dayBookExport')}}">
            {{csrf_field()}}
            <input type="hidden" name="month" class="monthvalue" value="">
            <input type="hidden" name="type" value="0">
            <input type="hidden" name="segment" value="{{Request::segment(2)}}" id="segment">
            <input type="submit" name="Export" value="Export" class="btn btn-info">
        </form>
          <div class="col-md-12">
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
          </div> -->
    <!-- <div class="col-md-6">
      <div class="form-group form-group-wrapper">
        <label>Select Year</label>
        <input type="text" name="year" class="form-control year" placeholder="Input year">
      </div>
    </div> -->

 <div class="box">
   <div class="row box-body-wrapper daybook-wrapp">
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
              <div class="form-group sub-btn-wrapper">
                <input type="submit" name="submit" value="submit" class="btn btn-success customDateSearch">
              </div>
            </div>
          </div>
      </div>
      <div class="col-lg-6">
          <div class="box-header"><h3 class="box-title">Year and Month</h3></div>
          <div class="erport-wrapp all-form-container">

              <div class="form-group form-group-wrapper">
                  <label>Input Year</label>
                  <input class="form-control inputYear" type="text" placeholder="Input Year">
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
              <form class="export-form" method="post" action="{{route('daybookPdf')}}">
                  {{csrf_field()}}
                  <input type="hidden" name="month" class="monthvalue" value="">
                  <input type="hidden" name="year" class="yearvalue" value="">
                  <input type="hidden" name="type" value="0">
                  <input type="hidden" name="segment" value="{{Request::segment(2)}}" id="segment">
                  <input type="submit" name="Export" value="Export" class="btn btn-info">
              </form>
          </div>
      </div>
    </div>
</div>
  
    <ol class="breadcrumb">
      <li><a href=""><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li><a href="">Daybook</a></li>
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
    <div class="alert alert-success alert-dismissible message1" style="display:none">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="row">
      <div class="col-xs-12">
          <div class="box">
            <div class="col-sm-6">
            <div class="box-header">
                <h3 class="box-title">Day Book</h3>
            </div>
          </div>
          <div class="col-sm-6"><div id="example1_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control input-sm query" placeholder="" aria-controls="example1"></label><button class="btn btn-success daybook_search">Submit</button></div></div>
          <div class="box-body daybookappend">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                   
                      <th></th>
                      <th colspan="2">Collection</th> 
     
                      <th colspan="3">Purchase</th>
                   
                      <th colspan="3">Paid</th>

               
                    
                </thead>
                <tbody id="sortable">
          
                  <tr id="" style="font-weight: 700; color: #666;">
                      <td>Date</td>
                      <td>From</td> 
                      <td>Amount</td>
                      <td>From</td> 
                      <td>Item</td> 
                      <td>Amount</td> 
                      <td>To</td> 
                      <td>For</td>  
                      <td>Amount</td> 
                  </tr>   
                  @foreach($daybook as $data)
                  <tr id="">
                    <td>{{$data->date}}</td>
                    <td>{{$data->collection_from?$data->collection_from:'------'}}</td> 
                    <td>{{$data->collection_amount?$data->collection_amount:'------'}}</td>
                    <td>{{$data->purchase_from?$data->purchase_from:'------'}}</td> 
                    <td>{{$data->purchase_item?$data->purchase_item:'------'}}</td> 
                    <td>{{$data->purchase_amount?$data->purchase_amount:'------'}}</td> 
                    <td>{{$data->payment_to?$data->payment_to:'------'}}</td> 
                    <td>{{$data->payment_for?$data->payment_for:'------'}}</td>  
                    <td>{{$data->payment_amount?$data->payment_amount:'------'}}</td> 
                  </tr>   
                  @endforeach
                  <tr>
                    <th>Total</th> 
                    <th colspan="2">Nrs. {{$daybook->sum('collection_amount')}}</th> 
                    <th colspan="3">Nrs. {{$daybook->sum('purchase_amount')}}</th>
                    <th colspan="3">Nrs. {{$daybook->sum('payment_amount')}}</th>  
                  </tr>
                </tbody>
              </table>
          </div>
        </div>
        
    </div>
    
</div>

<!-- <div class="row">
      <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
                <h3 class="box-title">Log Book</h3>
            </div>
          <div class="box-body append">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                      <th>S.N.</th>
                      <th>Date</th>
                      <th>Received</th>
                      <th>Payment </th>
                      <th>Balance</th>
                    </tr>
                </thead>
                <tbody id="sortable">
                @php($i=1)
                @foreach($balances as $detail)
                  <tr id="{{ $detail->id }}">
                      <td>{{$i}}</td>
                      <td>{{$detail->date}}</td>
                      <td>{{$detail->received}}</td>

                      <td>{{$detail->payment}}</td>
                      <td>{{$detail->balance}}</td>
                      
                  </tr>
                  @php($i++)
                  @endforeach
                </tbody>
              </table>
          </div>
        </div>
    </div>
    
</div> -->









</div>
@include('admin.include.modal')
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
    

  $('#example1').dataTable( {
    "pageLength": 100
  } );

  $('.inputYear').keyup(function(){
    $('.yearvalue').val($('.inputYear').val())
  })
  $('#month').change(function(){
    $('.monthvalue').val($('#month').val())
  })
  // $('#example2').dataTable( {
  //   "pageLength": 100
  // } );
  //search by month
  $(document).ready(function(){
    $('#month').on('change',function(){
      value=$(this).val();
      segment_2=$('#segment').val();
      console.log(segment_2);
      $.ajax({
        method:'post',
        url:"{{route('monthlydaybookReport')}}",
        data:{value:value,segment:segment_2},
        success:function(data){
          $('.table-striped').remove();
          $('.daybookappend').html(data);
          $('.monthvalue').val(value);
          $("#example1").DataTable();
        }
      });
    });
  });
  //search by year
  $(document).ready(function(){
    $('.year').on('keyup',function(){
      value=$(this).val();
      segment_2=$('#segment').val();
      console.log(segment_2);
      $.ajax({
        method:'post',
        url:"{{route('yearlydaybookReport')}}",
        data:{value:value,segment:segment_2},
        success:function(data){
          $('.table-striped').remove();
          $('.append').html(data);
          $('.monthvalue').val(value);
          $("#example1").DataTable();
        }
      });
    });

    //daybook search

    $('.daybook_search').on('click',function(){
      
      value = $('.query').val();
      $.ajax({
        method:'post',
        url:"{{route('searchDaybook')}}",
        data:{value:value},
        success:function(data){
          console.log(data);
          $('#example2').remove();
          $('.daybookappend').html(data);
            
        }
      });
    });
  });
  $(document).ready(function(){
    $('.customDateSearch').click(function(e){
      e.preventDefault();
      start_date = $('#start_date').val();
      end_date = $('#end_date').val();
      
      $.ajax({
        method:'post',
        url:"{{route('customDateDaybookSearch')}}",
        data:{start_date:start_date,end_date:end_date},
        success:function(data){
          $('.table-striped').remove();
          $('.daybookappend').html(data);
          $("#example1").DataTable();
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
