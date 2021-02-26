@extends('layouts.admin')
@section('title','To Be Paid List')
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
@endpush
@section('content')
<section class="content-header">
  <h1>To Be Paid<small>List</small></h1>
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


  
    <div class="box">
      <div class="row">
        <div class="col-md-6">
          <div class="box-header">
            <h3 class="box-title">Custom Date</h3>
          </div>
          <div class="box-body">
            <form action="{{ route('to-be-paid-date-filter') }}" method="get">
              <div class="col-md-5">
                <div class="form-group">
                  <label>Start Date</label>
                  <input type="text" id="start_date" class="bod-picker form-control" name="start_date" autocomplete="off" value="">
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label>End Date</label>
                  <input type="text" id="end_date" class="bod-picker form-control" name="end_date" autocomplete="off" value="">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group sales-btn">
                  <input type="submit" name="submit" value="submit" class="btn btn-success customDateSearch">
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="col-md-6">
            <div class="box-header"><h3 class="box-title">Year and Month</h3></div>
            <div class="box-body">
                <div class="erport-wrapp profit-loss-wrapp">
                    <div class="form-group form-group-wrapper">
                        <label>Input Year</label>
                        <input class="form-control inputYear" type="text" placeholder="Input Year">
                    </div>
                    <div class="form-group select-mont-wrapp form-group-wrapper">
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
                    <form class="export-form" method="post" action="{{route('to-be-paid-pdf')}}">
                        {{csrf_field()}}
                        <input type="hidden" name="month" class="monthvalue" value="">
                        <input type="hidden" name="year" class="yearvalue" value="">
                        <input type="hidden" name="type" value="0">
                        <input type="hidden" name="segment" value="{{Request::segment(2)}}" id="segment">
                        <input type="submit" name="Export" value="Export" class="btn btn-info" formtarget="_blank">
                    </form>
                </div>

            </div>

        </div>
      </div>
      
    </div>



    <div class="row">
      <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
                <h3 class="box-title">Data Table</h3>
            </div>
          <div class="box-body append">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                      <th>S.N.</th>
                      <th>To</th>
                      <th>Total</th>
                      <th>Paid</th>
                      <th>Due</th>
                      <th>Date</th>
                      <th>For</th>
                      <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="sortable">
                @php($i=1)
                @foreach($details as $detail)
                  <tr id="{{ $detail->id }}">
                    <?php //dd($detail) ?>
                      <td>{{$i}}</td>
                      <td>{{$detail->purchased_from}}</td>
                      <td>{{$detail->total}}</td>
                      <td>{{$detail->total_amount_of_purchase_amount_paid}}</td>
                      <td>{{$detail->total-$detail->total_amount_of_purchase_amount_paid}}</td>
                      <td>{{$detail->vat_date}}</td>
                      <td>{{$detail->purchased_item}}</td>
                      <td>
                        {{-- <div class="form-group" id="select{{$detail->id}}">
                          <select class="form-control purchasePayment">
                            <option disabled="true" selected="true">Make Payment</option>
                            <option value="partial" data-id="{{$detail->id}}">Partial</option>
                            @if($detail->collected_type!='partial')
                            <option value="full" data-id="{{$detail->id}}">Full</option>
                            @endif
                          </select>
                        </div> --}}

                        @if($detail->collected_type==null)
                    <a class="btn btn-info edit" href="{{route('purchase.edit',$detail->id)}}" title="Edit"><span class="fa fa-edit"></span></a>
                    <form method= "post" action="{{route('purchase.destroy',$detail->id)}}" class="delete btn btn-danger">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn-delete" style="display:inline"><span class="fa fa-trash"></span></button>
                    </form>
                    @endif
                    @if($detail->collected!=1)
                    <div class="form-group" id="select{{$detail->id}}">
                      <select class="form-control purchasePayment">
                        <option disabled="true" selected="true">Make Payment</option>
                        <option value="partial" data-id="{{$detail->id}}">Partial</option>
                        @if($detail->collected_type!='partial')
                        <option value="full" data-id="{{$detail->id}}">Full</option>
                        @endif
                      </select>
                    </div>
                    @endif
                    <a href="{{route('purchasePaymentList',$detail->id)}}" class="btn btn-success">Payments</a>
                      </td>
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
@include('admin.purchasePayment.include.modal')
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

  $('#example1').dataTable( {
    "pageLength": 10
  } );


  $(".bod-picker").nepaliDatePicker({
      dateFormat: "%y-%m-%d",
      closeOnDateSelect: true,
      // minDate: formatedNepaliDate
  });

  $(".inputYear").keyup(function(){
    $('.yearvalue').val($(".inputYear").val())
  });

  $(document).ready(function(){
      $('#month').on('change',function(){
        month=$(this).val();
        segment_2=$('#segment').val();
        year=$('.yearvalue').val();

        $.ajax({
          method:'post',
          url:"{{route('toBePaidCustomSearched')}}",
          data:{month:month, segment:segment_2, year: year },
          success:function(data){
            $('.table-striped').remove();
            $('.append').html(data);
            
            $('.export').removeClass('hidden');
            $('.monthvalue').val(month);
          }
        });
      });
    });

  $(document).ready(function(){
    $(document).on('change','.purchasePayment',function(e){
      e.preventDefault();
      
      
      if($(this).val()=='partial'){
        
        id=$(this).find(':selected').data('id');
        $.ajax({
          method:"post",
          url:"{{route('partialPurchasePayment')}}",
          data:{"_token": "{{ csrf_token() }}", id:id},
          success:function(data){
            console.log(data);
            if(data.message=='success'){
              $('#myModal .modal-body').html(data.html);
              $('#myModal').modal('show').on('hide', function() {
                $('#new_passenger').modal('hide')
                });
              $('.purchasePayment').prop('selectedIndex',0);
            }
            
          }
        });
      }else{
        id=$(this).find(':selected').data('id');
        $.ajax({
          method:"post",
          url:"{{route('fullPurchasePayment')}}",
          data:{"_token": "{{ csrf_token() }}", id:id},
          success:function(data){
            console.log(data.html);
            if(data.message=='success'){
              $('#myModal .modal-body').html(data.html);
              $('#myModal').modal('show').on('hide', function() {
                $('#new_passenger').modal('hide')
              });
              $('.collectionWithVat').prop('selectedIndex',0);
              //$('#select'+id).addClass('hidden');
            }
          }
        });
      }


      $(document).on('keyup','.amount',function(){
        remaining_amount=$('.remaining_amount').val();
        if(parseInt(remaining_amount)>=parseInt($(this).val())){
          $('.submitButton').removeClass('hidden');
        }else{
          $('.submitButton').addClass('hidden');
        }
      });
    });
  });
   
</script>
@endpush
