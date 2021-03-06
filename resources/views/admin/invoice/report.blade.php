@extends('layouts.admin')
@section('title','Invoice Report')
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
@endpush
@section('content')
<section class="content-header">
  <h1>Invoice Monthly Report<small>List</small></h1>
    
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
    <ol class="breadcrumb">
      <li><a href=""><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li><a href="">Invoice</a></li>
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
                <h3 class="box-title">Data Table</h3>
              
            </div>
          <div class="box-body append">
              
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
        var message=confirm('Are you sure?');
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
    $('.message1').delay(4000).fadeOut(300);

    $(document).ready(function(){
      $('#month').on('change',function(){
        value=$(this).val();
        segment_2=$('#segment').val();
        console.log(segment_2);
        $.ajax({
          method:'post',
          url:"{{route('invoiceMonthlyReport')}}",
          data:{value:value,segment:segment_2},
          success:function(data){
            $('.table-striped').remove();
            $('.append').html(data);
            $('.monthvalue').val(value);
            $("#example1").DataTable();
          }
        });
      });
    });

   

    

   $('#example1').dataTable( {
  "pageLength": 100
  } );
</script>
@endpush
