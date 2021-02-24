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

     <div class="box">
        <div class="row">
            <div class="col-lg-6">
                <div class="box-header"><h3 class="box-title">Custom Date</h3></div>
                <div class="box-body">
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
                    <div class="form-group pro-submit-btn">
                      <input type="submit" name="submit" value="submit" class="btn btn-success customDateSearch">
                    </div>
                  </div>
                </div>
            </div>

            <div class="col-lg-6">
              <div class="box-header"><h3 class="box-title">Year and Month</h3></div>

                <div class="box-body">
                    <div class="erport-wrapp profit-loss-wrapp">

                      <div class="form-group form-group-wrapper">
                          <label>Input Year</label>
                          <input class="form-control" type="text" placeholder="Input Year">
                      </div>
                      <div class="select-mont-wrapp form-group-wrapper">
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
                      </div>
                      <form class="export-form" method="post" action="{{route('vatExport')}}">
                        {{csrf_field()}}
                        <input type="hidden" name="month" class="monthvalue" value="">
                        <input type="hidden" name="type" value="1">
                        <input type="hidden" name="segment" value="{{Request::segment(2)}}" id="segment">
                        <input type="submit" name="Export" value="Export" class="btn btn-info">
                      </form>
                  </div>
              </div>
            </div>
        </div>
    </div>

      <?php /*
      <div class="whole-form-wrapper">
          <div class="export-wrapper">
            

            

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

      */?>



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
            <?php
              $invoice=\App\Models\Invoice::orderBy('created_at','desc')->get();
              $total_collected = $invoice->sum('collected_amount');
              $remaining_amount = $invoice->sum('total') - $total_collected;

            ?>
            <!-- <b>To be collected = {{$remaining_amount}}</b> -->
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                      <th>S.N.</th>
                      <th>Date</th>
                      <th>Sales To</th>
                      <th>Amount</th>
                      <th>Billing Status</th>
                      <th>Detail</th>
                    </tr>
                </thead>
                <tbody id="sortable">
                    @php($i=1)
                    @foreach($details as $detail)
                    <?php //dd(@$detail->sales()) ?>
                    <tr id="{{ $detail->id }}">
                      <td>{{$i}}</td>
                      <td>{{$detail->nepali_date}}</td>
                      <td>{{@$detail->client_name}}</td>
                      <td>{{$detail->total}}</td>
                      <td>{{$detail->collected_amount=$detail->grand_total?'collected':'not complete'}}</td>
                      
                      <td><a class="btn btn-info edit salesView" href="{{route('invoiceView',$detail->id)}}" title="Sales View" data-id="{{$detail->id}}" target="_blank"><span class="fa fa-eye"></span></a></td>
                    </tr>
                    @php($i++)
                    @endforeach
                </tbody>
                <?php
                  $total_sales = $details->sum('total');
                  $salesVat=$details->sum('vat_paid');
                  $purchase = \App\Models\Purchase::orderBy('created_at','desc')->get();
                  $purchase_vat = $purchase->sum('vat_paid');
                  $difference = $salesVat-$purchase_vat;
                ?>
                <tr>
                    <td colspan="4"><h4>Total Sales =<b>({{$total_sales}}) </b></h4> </td>
                    <td colspan="4"><h4>Total Sales Vat = <b>{{$salesVat}}</b></h4></td>
                </tr>
                <tr>
                    <!-- <td colspan="4"><h4>Total Purchase = <b>({{$purchase->sum('total')}})</b></h4></td> -->
                    <!-- <td colspan="4"><h4>Total Purchase Vat = <b>{{$purchase_vat}}</b></h4></td> -->
                </tr>
                <tr>
                   <!-- <td colspan="8"><h4>Conclusion(sales vat - purchase vat)= <b>{{$difference}}</b></h4></td> -->
                </tr>
              </table>   
          </div>
        </div>
    </div>
</div>
</div>
@include('admin.sales.include.modal')
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
          url:"{{route('salesSearchByMonth')}}",
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

    // $(document).ready(function(){
    //   $('.export').click(function(e){
    //     e.preventDefault();
    //     month=$('#month').val();
    //     $.ajax({
    //       method:"post",
    //       data:{month:month},
    //       url:"{{route('vatExport')}}",
    //       success:function(data){
    //         console.log(data);
    //       }
    //     });
    //   });
    // });


    // $(document).ready(function(){
    //   $('.salesView').click(function(e){
    //     e.preventDefault();
    //     value = $(this).data('id');
    //     $.ajax({
    //       method:"post",
    //       url:"{{route('salesView')}}",
    //       data:{value:value},
    //       success:function(data){
    //         $('#myModal .modal-body').html(data);
    //         $('#myModal').modal('show');
    //       }
    //     });
    //   });
    // });
    
    $(document).ready(function(){
      $('.customDateSearch').click(function(){
        start_date = $('#start_date').val()
        end_date = $('#end_date').val()
        $.ajax({
          method:'post',
          url:"{{route('salesSearchBySalesWithoutVat')}}",
          data:{start_date:start_date, end_date:end_date},
          success:function(data){
            $('.table-striped').remove();
            $('.append').html(data);
            
            $('.export').removeClass('hidden');
            $('.monthvalue').val(value);
          }
        });
      });
    });

    $(".bod-picker").nepaliDatePicker({
      dateFormat: "%y-%m-%d",
      closeOnDateSelect: true,
      // minDate: formatedNepaliDate
  });
    

   
</script>
@endpush
