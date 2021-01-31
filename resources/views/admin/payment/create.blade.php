@extends('layouts.admin') 
@section('title','Add Payment')
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
    <h1> Payment<small>Create</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href=""> Payment</a></li>
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
            <form method="post" action="{{route('payment.store')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="box box-primary">
                    <div class="box-header with-heading">
                        <h3 class="box-title">Add Payment</h3>
                    </div>
                    <div class="box-body row">

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Paid To</label>
                                <input type="text" name="paid_to" class="form-control" value="{{old('paid_to')}}">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Paid For</label>
                                <input type="text" name="payment_for" class="form-control" value="{{old('payment_for')}}">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Payment Type</label>
                                <select name="payment_type" class="form-control payment_type">
                                    <option value="Cash">Cash</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Digital Wallet">Digital Wallet</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group hidden bank">
                                <label>Bank</label>
                                <select name="bank" class="form-control">
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
                              <select name="digital_wallet" class="form-control">
                                @foreach($digital_wallet as $wallet)
                                <option value="{{$wallet->id}}">{{$wallet->name}}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                        
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="text" name="amount" value="{{old('amount')}}" class="form-control">
                                </div>
                            </div>
                            
                        <div class="col-md-6">
                            
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="text" id="nepaliDate1" class="bod-picker form-control" name="date" autocomplete="off" value="{{old('date')}}">
                                </div>
                            
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea class="form-control" name="narration">{{old('narration')}}</textarea>
                            </div>
                        </div>
                        
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="submit" name="submit" class=" btn btn-success">
                            </div>
                        </div>
                           
                    </div>
                </div>
            </form>
        </div>

       
    </div>
</div>
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
    

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function(){
        $('.payment_type').on('change',function(){
          value = $(this).val();
          
          if(value=='Cheque'){
            $('.bank').removeClass('hidden');
            $('.wallet').addClass('hidden');
          }
          if(value=='Cash'){
            $('.bank').addClass('hidden');
            $('.wallet').addClass('hidden');
          }
          if(value=='Digital Wallet'){
            
            $('.bank').addClass('hidden');
            $('.wallet').removeClass('hidden');
          }
        });
    });

    

    
    </script>
@endpush