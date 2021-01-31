@extends('layouts.admin')
@section('title','Transaction View')
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
@endpush
@section('content')
<section class="content-header">
  <h1>Transaction<small>View</small></h1>
    <ol class="breadcrumb">
      <li><a href=""><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li><a href="">Transaction</a></li>
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
              <h3 class="box-title">Purchase Detail</h3>
          </div>
          <ul class="box-body">
              <li class="caient-detail-wrapper">
                   <p>date:</p> <span>{{$purchase->vat_date}}</span>
              </li>
              <li class="caient-detail-wrapper">
                  <p>Purchase Item:</p> <span>{{$purchase->purchased_item}}</span>
              </li>

              <li class="caient-detail-wrapper">
                <p>Bill No:</p> <span>{{$purchase->bill_no}}</span>
              </li>

              <li class="caient-detail-wrapper">
                  <p>Total Amount:</p> <span>{{$purchase->total}}</span>
              </li>

              <li class="caient-detail-wrapper">
                 <p>Billing Status:</p> <span>{{$purchase->total_amount_of_purchase_amount_paid==$purchase->total?'Closed':'Open'}}</span>
              </li>

              <li class="caient-detail-wrapper">
                  <p>Particular:</p> <span>{{$purchase->particular}}</span>
              </li>

              <li class="caient-detail-wrapper">
                  <p>Image:</p><span>@if($purchase->bill_image)<img src="{{asset('images/'.$purchase->bill_image)}}" class="img-responsive">@endif</span>
              </li>
          </ul>
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
                      <th>Date</th>
                      <th>Amount</th>
                      <th>Payment Type</th>
                      <th>Remark</th>
                      
                      
                    </tr>
                </thead>
                <tbody id="sortable">
                @php($i=1)
                @foreach($purchasePayments as $detail)
                  <tr id="{{ $detail->id }}">
                      <td>{{$i}}</td>
                      <td>{{$detail->date}}</td>
                      <td>{{$detail->amount}}</td>
                      <td>{{$detail->payment_type}}</td>
                      <td>{{$detail->particular}}</td>
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

    

  $('#example1').dataTable( {
    "pageLength": 10
  } );
</script>
@endpush
