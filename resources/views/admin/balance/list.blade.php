@extends('layouts.admin')
@section('title','Balance List')
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
@endpush
@section('content')
<section class="content-header">
  <h1>Balance<small>List</small></h1>
    
    <ol class="breadcrumb">
      <li><a href=""><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li><a href="">Bank</a></li>
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
                <h3 class="box-title">At Bank</h3>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                      <th>S.N.</th>
                      <th>Name</th>
                      <th>Branch</th>
                      <th>Account Number</th>
                      <th>Balance</th>
                    </tr>
                </thead>
                <tbody id="sortable">
                  @php($i=1)
                  @foreach($banks as $detail)
                  <tr id="{{ $detail->id }}">
                      <td>{{$i}}</td>
                      <td>{{$detail->name}}</td>
                      <td>{{$detail->branch}}</td>
                      <td>{{$detail->account_number}}</td>
                      <td>
                        
                        <a class="btn btn-success" href="" title="Edit">
                          <?php
                            $payments = $detail->payments->sum('amount');

                            $receiveds =  $detail->received->sum('amount');
                            $purchasePayments = $detail->purchasePayments->sum('amount');
                            
                            $balance = $receiveds-($payments+$purchasePayments);

                          ?>
                          Balance: ({{$balance}})</a>
                      </td>
                  </tr>
                  @php($i++)
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <div class="box">
            <div class="box-header">
                <h3 class="box-title">At Digital Wallet </h3>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                      <th>S.N.</th>
                      <th>Name</th>
                      <th>Balalnce</th>
                    </tr>
                </thead>
                <tbody id="sortable">
                  @php($i=1)
                  @foreach($payment_gateways as $detail)
                  <tr id="{{ $detail->id }}">
                      <td>{{$i}}</td>
                      <td>{{$detail->name}}</td>
                      
                      <td>
                        
                        <a class="btn btn-success" href="" title="Edit">
                          <?php
                            $receiveds = $detail->receiveds->sum('amount');
                            $payments = $detail->payments->sum('amount');
                            $purchasePayments =  $detail->purchasePayments->sum('amount');

                            $balance = $receiveds-($payments+$purchasePayments);
                          ?>
                        Balance:-  ({{$balance}})
                      </a>
                    </td>
                  </tr>
                  @php($i++)
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="box">
            <div class="box-header">
                <h3 class="box-title">At Office</h3>
            </div>
            <div class="box-body">

              <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                      
                      <th>Collection </th>
                      <th>Payment</th>
                      <th>Total</th>
                    </tr>
                </thead>
                <tbody id="sortable">
                  @if(isset($officeBalance))
                  <tr id="">
                      <td>{{@$officeBalance[0]->totalamount}}</td>
                      <td>{{@$officeBalance[1]->totalamount}}</td>
                      <td>{{@$officeBalance[0]->totalamount - @$officeBalance[1]->totalamount}}</td>
                      
                  </tr>
                  @endif
                 
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
