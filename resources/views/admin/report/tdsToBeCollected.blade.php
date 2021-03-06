@extends('layouts.admin')
@section('title','Tds List')
@push('styles')

<!-- <style type="text/css">
  

  .form-btn-wrapp {

    display: flex;
  }

  .total-wrapp {

    margin-left: 10px;
  }
  .form-group {

    width: 50%;
  }

</style> -->
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
  <h1>Tds<small>List</small></h1>
         
 
    
       <div class="box">
         <div class="row">
            <div class="col-md-6">
              <div class="box-header">
                <h3 class="box-title">Custom Date</h3>
              </div>
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
                  <div class="form-group sales-btn">
                    <input type="submit" name="submit" value="submit" class="btn btn-success customDateSearch">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
                <div class="box-header"><h3 class="box-title">Year and Month</h3></div>

                <div class="box-body">
                    <div class="erport-wrapp profit-loss-wrapp">
                        <div class="form-group form-group-wrapper">
                            <label>Input Year</label>
                            <input class="form-control inputYear" type="text" placeholder="Input Year">
                        </div>
                        <div class="form-group select-mont-wrapp top-form-wrapp form-group-wrapper">
                          <label>Select Month</label>
                          <select name="month" class="form-control inputMonth">
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
                        <form class="export-form" method="post" action="{{route('admin.report.tdsToBeCollectedExport')}}">
                          {{csrf_field()}}
                          <input type="hidden" name="month" class="monthvalue">
                          <input type="hidden" name="year" class="yearvalue">
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
      <li><a href="">Invoice</a></li>
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
                      <th>Amount</th>
                      <th>From</th>
                      <th>Bill No</th>
                      <th>Date</th>
                    </tr>
                </thead>
                <tbody id="sortable">
                @php($i=1)
                @foreach($details as $detail)
                  <tr id="{{ $detail->id }}">
                      <td>{{$i}}</td>
                      <td>{{$detail->amount}}</td>
                      <td>{{$detail->company_name}}</td>
                      <td>{{$detail->bill_no}}</td>
                      <td>{{$detail->date}}</td>
                  </tr>
                  @php($i++)
                  @endforeach
                </tbody>
              </table>
              <div class="table-total-wrapper">
                    <p>Total</p>
                    <span>{{$details->sum('amount')}}</span>
              </div>
          </div>
        </div>
    </div>
</div>
</div>
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


    $(function () {
        $("#example1").DataTable();
    });
    $('.message').delay(5000).fadeOut(400);
    $('.message1').delay(4000).fadeOut(300);

    $(".bod-picker").nepaliDatePicker({
      dateFormat: "%y-%m-%d",
      closeOnDateSelect: true,
      // minDate: formatedNepaliDate
  });
  $(document).ready(function(){
    $('.customDateSearch').click(function(e){
      e.preventDefault();
      start_date = $('#start_date').val();
      end_date = $('#end_date').val();
      
      
      $.ajax({
        method:'post',
        url:"{{route('reportCustomTdsCollected')}}",
        data:{start_date:start_date,end_date:end_date},
        success:function(data){
          $('.table-striped').remove();
          $('.append').html(data);
          $('#example1').dataTable();
        }
      });
    });
  });
  $(document).ready(function(){
    $('#month').on('change',function(){
      value = $(this).val();

      $.ajax({
        method:"post",
        url:"{{route('reportMonthlyTdsCollected')}}",
        data:{month:value},
        success:function(data){
          $('.table-striped').remove();
          $('.append').html(data);
          $('#example1').dataTable();
        }
      });
    });
  });

  $('#example1').dataTable( {
    "pageLength": 100
  } );

  $(document).ready(function(){
    $('.inputMonth').on('change',function(){
      $('.monthvalue').val($('.inputMonth').val())
    });
  });

  $(document).ready(function(){
    $('.inputYear').on('keyup',function(){
      $('.yearvalue').val($('.inputYear').val())
    });
  });
</script>
@endpush
