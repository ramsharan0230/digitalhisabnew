@extends('layouts.admin')
@section('title','Vendor View')
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
@endpush
@section('content')
<section class="content-header">
  <h1>Vendor<small>View</small></h1>
    <ol class="breadcrumb">
      <li><a href=""><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li><a href="">Vendor</a></li>
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
          <div class="box-header">
              <h3 class="box-title">Vendor Detail</h3>
          </div>
          <ul class="box-body">
              <li class="caient-detail-wrapper">
                  <p>name:</p> <span>{{$detail->name}}</span>
              </li>

              <li class="caient-detail-wrapper">
                  <p>address:</p> <span>{{$detail->address}}</span>
              </li>

              <li class="caient-detail-wrapper">
                <p>phone:</p><span>{{$detail->phone}}</span>
              </li>

              <li class="caient-detail-wrapper">
                 <p>email:</p><span>{{$detail->email}}</span>
              </li>

              <li class="caient-detail-wrapper">
                  <p>vat no:</p><span>{{$detail->vat_no}}</span>
              </li>

              <li class="caient-detail-wrapper">
                <p>contact person:</p><span>{{$detail->contact_person}}</span>
              </li>

              <li class="caient-detail-wrapper">
                <p>designation:</p><span>{{$detail->designation}}</span>
              </li>

          </ul>
        </div>
      </div>
      
      
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          
              <div class="row">
                  <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="box-header"><h3 class="box-title">Custom Date</h3></div>
                      <div class="box-body">
                        <input type="hidden" name="client_id" data-id="{{$detail->id}}" id="vendor_id">
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
                  <div class="col-lg-6 col-md-12 col-sm-12">
                      <div class="box-header">
                          <h3 class="box-title">Year & Month</h3>
                      </div>
                       <div class="box-body">
                          <div class="erport-wrapp">
                            <div class="form-group form-group-wrapper">
                                <label>Input Year</label>
                                <input class="form-control" type="text" placeholder="Input Year">
                            </div>

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
                                    <form action="{{route('exportVendorTransaction')}}" method="post">
                                        {{csrf_field()}}
                                        <input type="hidden" name="id" value="{{$detail->id}}">
                                        <input type="submit" name="Export" class="btn btn-success" value="Export">
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
            </div>
            <div class="box-body">
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
                @foreach($detail->purchases as $data)
                  <tr id="{{ $detail->id }}">
                      <td>{{$i}}</td>
                      <td>{{$data->vat_date}}</td>
                      <td>{{$data->total_amount_of_purchase_amount_paid==$data->total?'Closed':'Open'}}</td>
                      <td>{{$data->total}}</td>
                      <td>
                        <a class="btn btn-info edit" href="{{route('vendorTransactionView',$data->id)}}" title="Edit"><span class="fa fa-eye"></span></a>
                       
                      

                      </td>
                  </tr>
                  @php($i++)
                  @endforeach
                </tbody>
              </table>
              <div class="table-total-wrapper">
                  <p>Total</p>
                  <span>{{$detail->purchases()->sum('total')}}</span>
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

  $(document).ready(function(){
    $('#month').on('change',function(){
      value = $(this).val();

      $.ajax({
        method:"post",
        url:"{{route('searchMonthlyVendorBusinessDetail')}}",
        data:{month:value},
        success:function(data){
          $('.table-striped').remove();
          $('.append').html(data);
          $('#example1').dataTable();
        }
      });
    });
  });
  $(document).ready(function(){
    $('.export').click(function(e){
      e.preventDefault();
      id=$(this).data('id');
      $.ajax({
        method:"post",
        data:{id:id},
        url:"{{route('exportVendorTransaction')}}",
        success:function(data){
          console.log(data);
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
      vendor_id = $('#vendor_id').data('id');
      alert(start_date);
      $.ajax({
        method:'post',
        url:"{{route('customVendorSearch')}}",
        data:{start_date:start_date,end_date:end_date,vendor_id:vendor_id},
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
