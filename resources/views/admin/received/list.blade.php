@extends('layouts.admin')
@section('title','Receipt List')
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
@endpush
@section('content')
<section class="content-header">
  <h1>Receipts<small>List</small></h1>
    

    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body box-body-wrapper">
            <div class="col-lg-6">
              <div class="box-header"><h3 class="box-title">Custom Date</h3></div>
                <div class="row">
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
                        <input type="submit" name="submit" value="submit" class="btn btn-success customDateSearch" formtarget="_blank">
                      </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
              <div class="box-header"><h3 class="box-title">Year and Month</h3></div>
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
                          <label>Input Year</label>
                          <input type="text" name="year" class="form-control year" placeholder="Input year">
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group list-export">
                        <form action="{{ route('admin.contact.export-client-transaction') }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="3">
                            <input type="hidden" name="year" class="yearValue">
                            <input type="hidden" name="month" class="monthValue">
                            <input type="submit" name="Export" class="btn btn-success" value="Export" formtarget="_blank">
                        </form>
                    </div>
                  </div>
                </div>
            </div>
           
               
               
                
          </div>
        </div>
      </div>
      
    </div>
    <ol class="breadcrumb">
      <li><a href=""><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li><a href="">Receipt</a></li>
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
              <a href="" class=""></a>
                <h3 class="box-title">Data Table</h3>
              
            </div>
          <div class="box-body append">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                      <th>S.N.</th>
                      <th>From</th>
                      <th>Particular</th>
                      <th>Payment Type</th>
                      <th>Date</th>
                      <th>Amount</th>
                      <th>Action</th>
                    </tr>
                </thead>
                <tbody id="sortable">
                @php($i=1)
                @foreach($details as $detail)
                  <tr id="{{ $detail->id }}">
                      <td>{{$i}}</td>
                      <td>{{$detail->received_from}}</td>
                      <td>{{$detail->particular}}</td>
                      <td>{{$detail->payment_type}}</td>
                      <td>{{$detail->date}}</td>
                      <td>{{$detail->amount}}</td>
                      <td>
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
@include('admin.received.include.modal')
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
        var message=confirm('Are you sure?');
        if(message){
            this.submit();
        }
        return;
        });
    });

    $(document).ready(function(){

    })


    $(function () {
        $("#example1").DataTable();
    });
    $('.message').delay(5000).fadeOut(400);
    $('.message1').delay(4000).fadeOut(300);

    $(document).ready(function(){
      $('#month').on('change',function(){
        value=$(this).val();
        segment_2=$('#segment').val();
        $('.monthValue').val(value)
        $.ajax({
          method:'post',
          url:"{{route('monthlyReceivedReport')}}",
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

    //search by year
  $(document).ready(function(){
    $('.year').on('keyup',function(){
      value=$(this).val();
      $('.yearValue').val(value);
      segment_2=$('#segment').val();
      console.log(segment_2);
      $.ajax({
        method:'post',
        url:"{{route('yearlyReceivedReport')}}",
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
          url: "{{route('receiptModal')}}",
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
      $('.customDateSearch').click(function(e){
        e.preventDefault();
        start_date = $('#start_date').val();
        end_date = $('#end_date').val();
        $.ajax({
          method:'post',
          url:"{{route('customReceivedSearch')}}",
          data:{start_date:start_date,end_date:end_date},
          success:function(data){
            $('.table-striped').remove();
            $('.append').html(data);
            $("#example1").DataTable();
          }
        });
      });
    });

   

    

  $('#example1').dataTable( {
      "pageLength": 100
  });
  $(".bod-picker").nepaliDatePicker({
      dateFormat: "%y-%m-%d",
      closeOnDateSelect: true,
      // minDate: formatedNepaliDate
  });
</script>
@endpush
