@extends('layouts.admin')   
@section('title','Setting')
@push('admin.styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.4/datepicker.min.css" /> 
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- bootstrap wysihtml5 - text editor -->
@endpush
@section('content')
<section class="content-header">
    <h1>Setting</h1>
    <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="">Setting</a></li>
    </ol>
</section>
<div class="content">
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
    @endif
<form method="post" action="{{route('setting.update',$detail->id)}}" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="PUT">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-heading">
                    <h3 class="box-title">Setting</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label>Organization Name</label>
                        <input type="text" name="organization_name" class="form-control" value="{{$detail->organization_name}}">
                    </div>
                    <div class="form-group">
                        <label>Logo</label>
                        <input type="file" name="logo" class="form-control" value="{{$detail->logo}}">
                        @if($detail->logo)
                        <div id="image-holder">
                            <img src="{{asset('images/')}}/{{$detail->logo}}" class="thumb-image" width="150px" height="150px">
                        </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Invoice Logo</label>
                        <input type="file" name="invoice_logo" class="form-control" value="{{$detail->invoice_logo}}">
                        @if($detail->invoice_logo)
                        <div id="image-holder">
                            <img src="{{asset('images/')}}/{{$detail->invoice_logo}}" class="thumb-image" width="150px" height="150px">
                        </div>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone_number" class="form-control" value="{{$detail->phone_number}}">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" value="{{$detail->address}}">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" name="email" class="form-control" value="{{$detail->email}}">
                    </div>
                    <!-- <div class="form-group">
                        <label>Sparrow Sms Token</label>
                        <input type="text" name="sms_token" class="form-control" value="{{$detail->sms_token}}">
                    </div> -->
                    <div class="form-group">
                        <label>Website</label>
                        <input type="text" name="website" class="form-control" value="{{$detail->website}}">
                    </div>
                    <div class="form-group">
                        <label>Email To Send Invoice</label>
                        <input type="text" name="email_to_send_invoice" class="form-control" value="{{$detail->email_to_send_invoice}}">
                    </div>
                    <div class="form-group">
                        <label>Email To Collect Invoice</label>
                        <input type="text" name="email_to_collect_invoice" class="form-control" value="{{$detail->email_to_collect_invoice}}">
                    </div>
                    <div class="form-group">
                        <label for="publish"><input type="checkbox" id="publish" name="display_sales_without_vat" {{$detail->display_sales_without_vat==1?
                            'checked':''}}>Display Sales WithOut Vat</label>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" class="btn btn-success" value="submit">
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</form>
</div>
@endsection
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.4/datepicker.js"></script>
  <script>
    $('#date').datepicker({
    autoclose: true,
    changeMonth: true,
    changeYear: true,
    format: 'yyyy-mm-dd'
  });
    $('#date1').datepicker({
    autoclose: true,
    changeMonth: true,
    changeYear: true,
    format: 'yyyy-mm-dd'
  });
    
    </script>
@endpush