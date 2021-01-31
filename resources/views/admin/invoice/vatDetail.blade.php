@extends('layouts.admin')   
@section('title','Vat ')
@push('admin.styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.4/datepicker.min.css" /> 
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- bootstrap wysihtml5 - text editor -->
@endpush
@section('content')
<section class="content-header">
    <h1>Vat<small>create</small></h1>
    <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="">Vat</a></li>
        <li><a href="">Create</a></li>
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
<form method="post" action="{{route('saveVatDetail',$detail->id)}}" enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-heading">
                    <h3 class="box-title">Add a new vat</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                         <label>VAT/PAN</label>
                         <input type="text" name="vat_pan" class="form-control" value="{{old('vat_pan')}}">
                     </div>
                     <div class="form-group">
                         <label>Bill No</label>
                         <input type="text" name="bill_no" class="form-control" value="{{old('bill_no')}}">
                     </div>
                     <div class="form-group">
                         <label>Bill Image</label>
                         <input type="file" name="bill_image" class="form-control" >
                     </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="text" id="nepaliDate1" class="bod-picker form-control" name="date" autocomplete="off" value="{{old('date')}}">
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" class="btn btn-success">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.js"></script>
<script>
            $(".bod-picker").nepaliDatePicker({
            dateFormat: "%y-%m-%d",
            closeOnDateSelect: true,
            // minDate: formatedNepaliDate
        });
</script>
  
@endpush