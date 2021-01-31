@extends('layouts.admin')
@section('title','Vat List')
@push('styles')

<style>

  .export-wrapper {

    display: flex;
    align-items: center;
    margin: 10px 0;
    margin-right: 10px;

  }

  .purchase-btn {

    margin-top: 0 !important;
    margin-right: 10px;
  }

  .form-group {

    width: 100%;
  }

  .whole-form-wrapper,
  .whole-btn-wrapper {

    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .whole-form-wrapper {

    align-items: baseline;
  }


</style>
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
@endpush
@section('content')
<section class="content-header">
  @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
    @endif
	   <h1>Vat <small>List</small></h1>

    <div class="whole-form-wrapper">
      <div class="export-wrapper">
          @if(Request::route()->getName()=='vat.index')
        <a href="{{route('vat.create')}}" class="btn purchase-btn btn-success">Add Purchase</a>
        @endif

        <form class="export-form" method="post" action="{{route('vatExport')}}">
          {{csrf_field()}}
          @if(isset($sales))
          <input type="hidden" name="month" class="monthvalue" value="">
          <input type="hidden" name="type" value="1">
          <input type="hidden" name="segment" value="{{Request::segment(2)}}" id="segment">
          <input type="submit" name="Export" value="Export" class="btn btn-info">
          @else
          <input type="hidden" name="month" class="monthvalue" value="">
          <input type="hidden" name="type" value="0">
          <input type="hidden" name="segment" value="{{Request::segment(2)}}" id="segment">
          <input type="submit" name="Export" value="Export" class="btn btn-info">
          @endif
          
        </form>
    </div>
    
    <div class="form-group form-group-wrapper">
      <!-- <label>Select Month</label> -->
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
    </div>


  	<ol class="breadcrumb">
    	<li><a href=""><i class="fa fa-dashboard"></i>Dashboard</a></li>
    	<li><a href="">Vat</a></li>
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
  	<div class="row">
    	<div class="col-xs-12">
      		<div class="box">
        		<div class="box-header">
          			<h3 class="box-title">Data Table</h3>
        		</div>
        	<div class="box-body append">
              @if(isset($sales))
                @include('admin.vat.include.salesVat')
              @else
                @include('admin.vat.include.table')
              @endif
          		
        	</div>
      	</div>
    </div>
</div>
</div>

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

    $(document).ready(function(){
        $('.delete').submit(function(e){
        e.preventDefault();
        var message=confirm('Are you sure to delete?');
        if(message){
            this.submit();
        }
        return;
        });
    });


    $(function () {
        $("#example1").DataTable();
    });
    $('.message').delay(5000).fadeOut(400);

    $(document).ready(function(){
      $('#month').on('change',function(){
        value=$(this).val();
        segment_2=$('#segment').val();
        console.log(segment_2);
        $.ajax({
          method:'post',
          url:"{{route('searchByMonth')}}",
          data:{value:value,segment:segment_2},
          success:function(data){
            $('.table-striped').remove();
            $('.append').html(data);
            
            $('.export').removeClass('hidden');
            $('.monthvalue').val(value);
          }
        });
      });
    });

    $(document).ready(function(){
      $('.export').click(function(e){
        e.preventDefault();
        month=$('#month').val();
        $.ajax({
          method:"post",
          data:{month:month},
          url:"{{route('vatExport')}}",
          success:function(data){
            console.log(data);
          }
        });
      });
    });

    

   
</script>
@endpush
