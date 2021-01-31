@extends('layouts.admin')	
@section('title','Edit User')
@push('admin.styles')
<!-- Date Picker -->
<link rel="stylesheet" href="{{ asset('backend/plugins/datepicker/datepicker3.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('backend/plugins/daterangepicker/daterangepicker.css') }}">
<!-- bootstrap wysihtml5 - text editor -->
@endpush
@section('content')
<section class="content-header">
	<h1>User<small>edit</small></h1>
	<ol class="breadcrumb">
		<li><a href=""><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href="">User</a></li>
		<li><a href="">Edit</a></li>
	</ol>
</section>
<div class="content">
	@if (count($errors) > 0)
	<div class="alert alert-danger message">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
      		<span aria-hidden="true">&times;</span>
    	</button>
		<ul>
			@foreach($errors->all() as $error)
			<li>{{$error}}</li>
			@endforeach
		</ul>
	</div>
	@endif
<form method="post" action="{{route('user.update',$detail->id)}}" enctype="multipart/form-data" id="editor">
	{{csrf_field()}}
	<input type="hidden" name="_method" value="PUT">
	<div class="row">
		<div class="col-md-5">
			<div class="box box-primary">
				<div class="box-header with-heading">
					<h3 class="box-title">Edit user</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label>Name(required)</label>
						<input type="text" name="name" class="form-control" value="{{$detail->name}}">
					</div>
					<div class="form-group">
						<label>Email(required)</label>
						<input type="email" name="email" class="form-control" value="{{$detail->email}}">
					</div>
					
					
						<div class="form-group">
							<label>Password(required)</label>
							<input type="password" name="password" class="form-control" id="password"> 
					    </div>
					<div class="form-group">
						<label>Confirm Password</label>
						<input type="password" name="confirm_password" class="form-control">
				    </div>
				    <div class="form-group">
						<label for="publish"><input type="checkbox" id="publish" name="publish" {{$detail->publish==1?'checked':''}} >Publish</label>
				    </div>
				</div>
			</div>
		</div>
		<div class="col-md-7">
			<div class="box box-warning">
				<div class="box-header with-heading">
					<h3 class="box-title">Role</h3>
				</div>
				<div class="box-body">
					<div class="row">
					@php($i=0)
					@foreach($access_options as $key=>$option)
					<div class="col-md-4">
						<div class="form-group">
							<label for="{{$key}}"><input type="checkbox" id="{{$key}}" name="access[]" {{ in_array($key, $accesses) ? 'checked' : '' }} value="{{$key}}">{{$option}}</label>
					    </div>
				    </div>
				    @endforeach
					</div>
					<div class="form-group">
				    	<input type="submit" name="" class="btn btn-success" value="submit">
				    </div>
				</div>
			</div>
		</div>
		
	</div>
</form>
</div>
@endsection
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
  <!-- daterangepicker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
  <script src="{{ asset('backend/plugins/daterangepicker/daterangepicker.js') }}"></script>
  <!-- datepicker -->
  <script src="{{ asset('backend/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
  <!-- CK Editor -->
  <script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
   <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js"></script>
  <!-- datepicker -->
<script >
  $(document).ready(function(){
    $('#editor').validate({
      rules:{
      	name:{
      		required:true,
      	},
        email:{
          required:true ,
          email:true,
        }
       
      },
    });
  });
  $('.message').delay(5000).fadeOut(400);
</script>
@endpush