@extends('layouts.admin') 
@section('title','Add Receipts')
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
<!-- Date Picker -->
<link rel="stylesheet" href="{{ asset('backend/plugins/datepicker/datepicker3.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('backend/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.css">
<!-- bootstrap wysihtml5 - text editor -->
@endpush
@section('content')
<section class="content-header">
    <h1>Receipt<small>Create</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="">Receipt</a></li>
        <li><a href="">Create</a></li>
    </ol>

    
</section>
<div class="content">
    <br>
    @if(Session::has('message'))
    <div class="alert alert-success alert-dismissible message">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {!! Session::get('message') !!}
    </div>
    @endif
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        
        <div class="col-md-12">
            <form method="post" action="{{route('received.store')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="box box-primary">
                    <div class="box-header with-heading">
                        <h3 class="box-title">Add Receipt</h3>
                    </div>
                    <div class="box-body row">
                        <div class="col-lg-4 col-md-4 col-sm-10">
                            <div class="form-group">
                                <label>From</label>
                                <select class="form-control" name="client_id">
                                    <option selected="true" disabled="true">Select Client</option>
                                    @foreach($clients as $client)
                                    <option value="{{$client->id}}">{{$client->name}}</option>
                                    @endforeach
                                </select>
                                
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="form-group add-client-plus">
                                <label></label>
                                <button class="btn btn-success addClient">+</button>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" name="amount" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>For</label>
                                <input type="text" name="for" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                            <label>Remark</label>
                            <input type="text" name="remark" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="text" id="nepaliDate1" class="bod-picker form-control" name="date" autocomplete="off" value="{{old('nepali_date')}}">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Payment Type</label>
                                <select name="payment_type" class="form-control payment_type">
                                    <option selected="true" disabled="true">Select Payment Type</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Digital Wallet">Digital Wallet</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="office" >
                                <label style="maring-top:20px" id="keep_at_office">Keep At Office</label>
                                <input type="checkbox" name="keep_at_office" id="keep_at_office" checked>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group hidden bank">
                                <label>Bank</label>
                                <select name="bank_id" class="form-control">
                                    <option selected="true" disabled="true">Select Bank</option>
                                    @foreach($dashboard_bank->where('our_bank') as $bank)
                                        <option value="{{$bank->id}}">{{$bank->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group hidden bank">
                              <label>Cheque Number</label>
                              <input type="text" name="cheque_number" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group wallet hidden">
                              <label>Select Digital Wallet</label>
                              <select name="paymentgateway_id" class="form-control">
                                @foreach($digital_wallet as $wallet)
                                <option value="{{$wallet->id}}">{{$wallet->name}}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>

                        
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group reciept-btn">
                            <input type="submit" name="submit" value="submit" class="btn btn-success">
                            </div>
                        </div>

                    </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('admin.client.modal')
@endsection
@push('script')
  <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('backend/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
  <!-- daterangepicker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
  <script>
        $(".bod-picker").nepaliDatePicker({
            dateFormat: "%y-%m-%d",
            closeOnDateSelect: true,
            // minDate: formatedNepaliDate
        });

        $(document).ready(function(){
        $('.payment_type').on('change',function(){
          value = $(this).val();
          
          if(value=='Cheque'){
            $('.bank').removeClass('hidden');
            $('.wallet').addClass('hidden');
            $('.office').addClass('hidden');
          }
          if(value=='Cash'){
            $('.bank').addClass('hidden');
            $('.wallet').addClass('hidden');
            $('.office').removeClass('hidden')
          }
          if(value=='Digital Wallet'){
            $('.office').addClass('hidden');
            $('.bank').addClass('hidden');
            $('.wallet').removeClass('hidden');
          }
        });
    });

    $(document).ready(function(){
        $('.addClient').click(function(e){
            e.preventDefault();
            $('#myModal').modal('show');
        });

        $('.client').change(function(e){
            e.preventDefault();
            value = $(this).val();
            if($.isNumeric(value)){
                $.ajax({
                    method:"post",
                    url:"{{route('findClient')}}",
                    data:{value:value},
                    success:function(data){
                        
                        if(data.message=='success'){
                            $('#contact').val(data.client.phone);
                            $('#email').val(data.client.email);
                            $('#address').val(data.client.address);
                            $('#vat_pan').val(data.client.vat_no);
                            
                        }else{

                        }
                    }
                });
            }
        });
    });
  </script>
@endpush