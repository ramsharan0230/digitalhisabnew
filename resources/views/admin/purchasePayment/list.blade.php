@extends('layouts.admin')
@section('title','Payment List')
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
@endpush
@section('content')
<section class="content-header">
  <h1>Payment<small>List</small></h1>
 <!--  <div class="row">
    <div class="col-md-6">
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
    <div class="col-md-6">
      <div class="form-group form-group-wrapper">
        <label>Select Year</label>
        <input type="text" name="year" class="form-control year" placeholder="Input year">
      </div>
    </div>
  </div> -->
    
    <ol class="breadcrumb">
      <li><a href=""><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li><a href="">Payment</a></li>
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
            <div class="box-header">
                <h3 class="box-title">Data Table</h3>
            </div>
          <div class="box-body append">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                      <th>S.N.</th>
                      <th>Date</th>
                      <th>Particular</th>
                      <th>Payment Type</th>
                      <th>Amount</th>
                      
                    </tr>
                </thead>
                <tbody id="sortable">
                @php($i=1)
                @foreach($purchase->purchasePayments as $detail)
                  <tr id="{{ $detail->id }}">
                      <td>{{$i}}</td>
                      <td>{{$detail->date}}</td>
                      <td>{{$detail->particular}}</td>

                      <td>
                        {{$detail->payment_type}}
                        @if($detail->paid_through_bank)
                        ({{$detail->bank->name}})
                        @endif




                      </td>
                      <td>{{$detail->amount}}</td>
                      
                  </tr>
                  @php($i++)
                  @endforeach
                </tbody>
              </table>
          </div>
        </div>
    </div>
</div>
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
  $(document).ready(function(){
    $('#month').on('change',function(){
      value=$(this).val();
      segment_2=$('#segment').val();
      console.log(segment_2);
      $.ajax({
        method:'post',
        url:"{{route('monthlyPaymentReport')}}",
        data:{value:value,segment:segment_2},
        success:function(data){
          $('.table-striped').remove();
          $('.append').html(data);
          $('.monthvalue').val(value);
          $("#example1").DataTable();
        }
      });
    });
  });
  $(document).ready(function(){
    $('.year').on('change',function(){
      value=$(this).val();
      segment_2=$('#segment').val();
      console.log(segment_2);
      $.ajax({
        method:'post',
        url:"{{route('yearlyPaymentReport')}}",
        data:{value:value,segment:segment_2},
        success:function(data){
          $('.table-striped').remove();
          $('.append').html(data);
          $('.monthvalue').val(value);
          $("#example1").DataTable();
        }
      });
    });
  });
</script>
@endpush
