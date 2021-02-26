@extends('layouts.admin')
@section('title','Payment List')
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
@endpush
@section('content')
<section class="content-header">
  <h1>Payment<small>List</small></h1>
  
  <div class="box">
      <div class="row">
          <div class="col-lg-6 col-md-12 col-sm-12">
              <div class="box-header"><h3 class="box-title">Custom Date</h3></div>
              <div class="box-body">
                <form action="{{ route('payment-filter-date') }}" method="GET">
                  @csrf
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
                      <input type="submit" name="submit" value="submit" class="btn btn-success" >
                    </div>
                  </div>
                </form>
              </div>
          </div>
          <div class="col-lg-6 col-md-12 col-sm-12 select-date-wrapper">
            <div class="box-header"><h3 class="box-title">Year and Month</h3></div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-5">
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
                    <div class="col-md-5">
                      <div class="form-group form-group-wrapper">
                        <label>Select Year</label>
                        <input type="text" name="year" class="form-control year" placeholder="Input year">
                      </div>
                    </div>
                    <div class="col-ld-2">
                      <div class="form-group list-export">
                          <form action="{{ route('admin.contact.export-client-transaction') }}" method="post">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <input type="hidden" name="id" value="3">
                              <input type="submit" name="Export" class="btn btn-success" value="Export" formtarget="_blank">
                          </form>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
      </div>
  </div>
    <a href="{{route('payment.create')}}" class="btn btn-success">Add Payment</a>
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
                      <th>Paid To</th>
                      <th>Payment Type</th>
                      <th>Amount</th>
                      <th>Bank</th>
                      <th>Action</th>
                    </tr>
                </thead>
                <tbody id="sortable">
                @php($i=1)
                @foreach($details as $detail)
                  <tr id="{{ $detail->id }}">
                      <td>{{$i}}</td>
                      <td>{{$detail->date}}</td>
                      <td>{{$detail->paid_to}}</td>

                      <td>{{$detail->payment_type}}</td>
                      <td>{{$detail->amount}}</td>
                      <td>{{$detail->bank}}</td>
                      <td>
                        
                        @if($detail->date==$todaysNepaliDate)
                        <a class="btn btn-info edit" href="{{route('payment.edit',$detail->id)}}" title="Edit"><span class="fa fa-edit"></span></a>
                        <form method= "post" action="{{route('payment.destroy',$detail->id)}}" class="delete btn btn-danger">
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn-delete" style="display:inline"><span class="fa fa-trash"></span></button>
                        </form>
                        @endif

                        <a class="btn btn-warning view " title="Edit" data-id="{{$detail->id}}">
                          <span class="fa fa-eye"></span>
                      </a>
                      </td>
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
@include('admin.payment.include.modal')
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
      $(document).on('click','.view',function(e){
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
          url: "{{route('paymentModal')}}",
          method: 'post',
          async: true,
          data: { id: id},
          success:function(data){
            console.log(data);
            $('#myModal .modal-body').html(data);
            $('#myModal').modal('show');
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
  $(document).ready(function(){
    $('.customDateSearch').click(function(e){
      e.preventDefault();
      start_date = $('#start_date').val();
      end_date = $('#end_date').val();
      alert(start_date);
      $.ajax({
        method:'post',
        url:"{{route('customDateSearch')}}",
        data:{start_date:start_date,end_date:end_date},
        success:function(data){
          $('.table-striped').remove();
          $('.append').html(data);
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
