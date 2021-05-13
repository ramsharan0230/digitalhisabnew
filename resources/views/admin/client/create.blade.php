@extends('layouts.admin') 
@section('title','Create Client')
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
<!-- Date Picker -->
<link rel="stylesheet" href="{{ asset('backend/plugins/datepicker/datepicker3.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('backend/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.css">

<style>
    .inputs{
        margin-top:20px
    }
</style>
<!-- bootstrap wysihtml5 - text editor -->
@endpush
@section('content')
<section class="content-header">
    <h1>Client<small>Create</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="">Client</a></li>
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
            
            <form method="post" action="{{route('client.store')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="box box-primary">
                    
                    <div class="box-header with-heading">
                        <h3 class="box-title">Add Client</h3>
                    </div>
                    <div class="box-body row">
                        
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" name="email" class="form-control">
                            </div>
                        </div>


                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="address" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Vat No.</label>
                                <input type="text" name="vat_no" class="form-control">
                            </div>
                        </div>

                       {{-- new  --}}
                       <div class="col-sm-12">
                        <div class="input-grodup controld-group after-add-more">
                            <div class="form-group">
                                <div class="col-sm-3">
                                    <input type="text" name="contact_person[]" class="form-control" placeholder="Enter Contact person">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="designation[]" class="form-control" placeholder="Enter Desigation">
                                </div>

                                <div class="col-sm-4">
                                    <input type="text" name="phone[]" class="form-control" placeholder="Enter Phone">
                                </div>

                                <div class="col-sm-2">
                                    <button class="btn btn-success add-more" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
                                </div>
                            </div>
                        </div>
                
                        <!-- Copy Fields -->
                        <div class="copy hide">
                            <div class="control-grosup input-grosup" >
                                <div class="col-sm-3 inputs">
                                    <input type="text" name="contact_person[]" class="form-control" placeholder="Enter Contact person">
                                </div>
                                <div class="col-sm-3 inputs">
                                    <input type="text" name="designation[]" class="form-control" placeholder="Enter Desigation">
                                </div>

                                <div class="col-sm-4 inputs">
                                    <input type="text" name="phone[]" class="form-control" placeholder="Enter Phone...">
                                </div>

                                <div class="col-sm-2 inputs">
                                    <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                                </div>
            
                            </div>
                        </div>
                        {{-- new end --}}
                       </div>
                        

                        <div class="col-lg-6 col-md-6 col-sm-12" style="margin-top:50px">
                            <div class="form-group">
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
@endsection
@push('script')
  <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('backend/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
  <!-- daterangepicker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
      $(".add-more").click(function(){ 
          var html = $(".copy").html();
          $(".after-add-more").after(html);
      });
      $("body").on("click",".remove",function(){ 
          $(this).parents(".input-grosup").remove();
      });
    });
  </script>
@endpush