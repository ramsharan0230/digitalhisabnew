@extends('layouts.admin')
@section('title','Client View')
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
@endpush
@section('content')
<section class="content-header">
  <h1>Client<small>View</small></h1>
    <ol class="breadcrumb">
      <li><a href=""><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li><a href="">Client</a></li>
      <li><a href="">View</a></li>
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
          <div class="box-header client-header">
              <h3 class="box-title">Client Detail</h3>
              <!-- <a href="{{route('otherReceipts',$detail->id)}}" class="btn btn-success">Other Receipts</a> -->
          </div>
          <ul class="box-body">
            
              <li class="caient-detail-wrapper">
                  <p>name:</p><span>{{$detail->name}}</span>
              </li>

              <li class="caient-detail-wrapper">
                  <p>address:</p> <span>{{$detail->address}}</span>
              </li>

              <li class="caient-detail-wrapper">
                <p>phone:</p>
                <?php $last_key = array_key_last(json_decode($detail->contact_person, true)) ?>
                @foreach(json_decode($detail->contact_person, true) as $key=>$contact)
                  <span>{{$contact}} @if($key != $last_key), &nbsp; @endif</span>
                @endforeach

              </li>

              <li class="caient-detail-wrapper">
                <p>email:</p> <span>{{$detail->email}}</span>
              </li>

              <li class="caient-detail-wrapper">
                <p>vat no:</p><span>{{$detail->vat_no}}</span>
              </li>

              
              <li class="caient-detail-wrapper">
                <p>contact person:</p>
                <?php $last_key = array_key_last(json_decode($detail->contact_person, true)) ?>
                @foreach(json_decode($detail->contact_person, true) as $key=>$contact)
                  <span>{{$contact}} @if($key != $last_key), &nbsp; @endif</span>
                @endforeach
              </li>

              <li class="caient-detail-wrapper">
                <p>Designation:</p>
                <?php $last_key = array_key_last(json_decode($detail->designation, true)) ?>
                @foreach(json_decode($detail->designation, true) as $key=>$designation)
                  <span>{{$designation}} @if($key != $last_key), &nbsp; @endif</span>
                @endforeach
              </li>
          </ul>
        </div>
      </div>
      
      
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          
          <div class="row">
              <div class="col-lg-6">
                  <div class="box-header"><h3 class="box-title">Custom Date</h3></div>
                  <div class="box-body">
                    <input type="hidden" name="client_id" data-id="{{$detail->id}}" id="client_id">
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
                        <div class="form-group client-sub-btn">
                          <input type="submit" name="submit" value="submit" class="btn btn-success customDateSearch">
                        </div>
                      </div>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="box-header">
                    <h3 class="box-title">Year and Month</h3>
                  </div>

                <div class="box-body">
                  <div class="erport-wrapp">
                      <div class="form-group form-group-wrapper">
                          <label>Input Year</label>
                          <input class="form-control inputYear" type="text" placeholder="Input Year">
                      </div>
                      <div class="form-group form-group-wrapper ">
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
                      {{-- <form action="{{route('exportClientTransaction')}}" method="post"> --}}
                        <form action="{{route('admin.contact.export-client-transaction-pdf')}}" method="post">
                          {{csrf_field()}}
                          <input type="hidden" name="id" value="{{$detail->id}}">
                          <input type="hidden" name="month" class="monthValue">
                          <input type="hidden" name="year" class="yearValue">
                          <input type="submit" name="Export" class="btn btn-success" value="Export" formtarget="_blank">
                      </form>
                  </div>
                  </div>
              </div>
          </div>
            
        </div>
      </div>
      
    </div>
    <div class="row">

      <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
                <h3 class="box-title">Business Detail</h3>
                <div class="row">
                    
                    <div class="col-md">
                     
                    </div>
                    
                  </div>
                
            </div>
            <div class="box-body append">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                      <th>S.N.</th>
                      <th>Created Date</th>
                      <th>Billing Status</th>
                      <th>Amount</th>
                      
                      <th>Action</th>
                    </tr>
                </thead>
                
                <tbody id="sortable">
                @php($i=1)
                @foreach($detail->invoice as $data)
                  <tr id="{{ $detail->id }}">
                      <td>{{$i}}</td>
                      <td>{{$data->nepali_date}}</td>
                      <td>{{$data->collected==$data->grand_total?'Closed':'Open'}}</td>
                      <td>{{$data->grand_total}}</td>
                      <td>
                        <a class="btn btn-info edit" href="{{route('clientInvoicePreview',$data->id)}}" title="Edit" target="_blank"><span class="fa fa-eye" ></span></a>
                      </td>
                  </tr>
                  @php($i++)
                  @endforeach
                </tbody>
              </table>
                <div class="table-total-wrapper">
                    <p>Total</p>
                    <span>{{$detail->invoice()->sum('grand_total')}}</span>
                </div>
                     
                   
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
    "pageLength": 10
  } );

  $('.inputYear').keyup(function(){
    $('.yearValue').val($('.inputYear').val())
  })

  $(document).ready(function(){
    $('#month').on('change',function(){
      value = $(this).val();
      $('.monthValue').val(value)

      $.ajax({
        method:"post",
        url:"{{route('searchMonthlyClientBusinessDetail')}}",
        data:{month:value},
        success:function(data){
          $('.table-striped').remove();
          $('.append').html(data);
          $('#example1').dataTable();
        }
      });
    });
  });
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
      client_id = $('#client_id').data('id');
      $.ajax({
        method:'post',
        url:"{{route('customClientSearch')}}",
        data:{start_date:start_date,end_date:end_date,client_id:client_id},
        success:function(data){
          $('.table-striped').remove();
          $('.append').html(data);
          $('#example1').dataTable();
        }
      });
    });
  });
</script>
@endpush
