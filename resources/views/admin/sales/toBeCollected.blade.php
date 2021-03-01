@extends('layouts.admin')
@section('title','To Be Collected List')
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
@endpush
@section('content')
<section class="content-header">
  <h1>To Be Collected<small>List</small></h1>
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
          <form action="{{ route('toBeCollected') }}" method="get">
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
                <div class="form-group sales-btn" >
                  <button type="submit" class="btn btn-success customDateSearch" style="maring-top: 10px"> Submit</button>
                </div>
              </div>
            </div>
          </form>
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
                    <form class="export-form" method="post" action="{{route('toBeCollectedPdf')}}">
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
        {{-- <div class="col-md-2">
            <div class="box-header"><h3 class="box-title">Year</h3></div>
              <div class="box-body">
                <form action="{{ route('sales.to-be-collected-year') }}" method="POST">
                  {{ csrf_field() }}
                  <input type="text" placeholder="Type Year..." class="form-control" id="year" name="year">
                  <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                </form>
              </div>
        </div> --}}
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
                      <th>Clent</th>
                      <th>Date</th>
                      <th>Total</th>
                      <th>Paid</th>
                      <th>Need To Be Collected</th>
                      <th>Action</th>
                    </tr>
                </thead>
                <tbody id="sortable">
                @php($i=1)
                @foreach($details as $detail)
                <?php //dd($detail) ?>
                  <tr id="{{ $detail->id }}">
                      <td>{{$i}}</td>
                      <td>{{$detail->client_name}}</td>
                      <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $detail->date )
                        ->format('d-m-Y')}}</td>
                      <td>{{$detail->grand_total}}</td>
                      <td>{{$detail->collected_amount}}</td>
                      <td>{{$detail->grand_total-$detail->collected_amount}}</td>
                      <td>
                        @if($detail->vat_id!=null && $detail->vat!=0 && $detail->collected==0)
                        
                          @if($detail->collected!=$detail->grand_total)
                          <div class="form-group" id="select{{$detail->id}}">
                            <select class="form-control collectionWithVat">
                              <option disabled="true" selected="true">Collection</option>
                              <option value="partial" data-id="{{$detail->id}}">Partial</option>
                              @if($detail->collected_type!='partial')
                              <option value="full" data-id="{{$detail->id}}">Full</option>
                              @endif
                            </select>
                          </div>
                          @endif
                        @endif
                        @if($detail->vat==0 && $detail->sales_without_vat_collected==0 && $detail->collected==0)
                          @if($detail->collected!=$detail->grand_total)
                          <div class="form-group" id="select{{$detail->id}}">
                            <select class="form-control collectionWithVat">
                              <option disabled="true" selected="true">Collection</option>
                              <option value="partial" data-id="{{$detail->id}}">Partial</option>
                              @if($detail->collected_type!='partial')
                              <option value="full" data-id="{{$detail->id}}">Full</option>
                              @endif
                            </select>
                          </div>
                          @endif
                        @endif
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
@include('admin.invoice.include.modal')
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
  
  $('.inputYear').keyup(function(){
    if($('.inputYear').val().length == 4){
      year = $('.inputYear').val();
      $.ajax({
          method:"post",
          url:"{{route('to-be-collected-year')}}",
          data:{year: year},
          success:function(data){
            $('.table-striped').remove();
            $('.append').html(data);
            $('.monthvalue').val(year);
            $("#example1").DataTable();
          }
        });
    }

  })

  $('#example1').dataTable( {
    "pageLength": 10
  } );

  $(document).ready(function(){
    $(document).on('change','.collectionWithVat',function(e){
      e.preventDefault();
      var fieldName = $(this).attr('name'),
              field = $('input[name=' + fieldName + ']'),
              optionValue = $(this).val();
          this.inputfield = this.inputfield || field;
          this.inputfield.attr('name', optionValue);
      
      if($(this).val()=='partial'){
        
        id=$(this).find(':selected').data('id');
        $.ajax({
          method:"post",
          url:"{{route('checkAmountToBeCollcted')}}",
          data:{id:id},
          success:function(data){
            if(data.message=='success'){
              $('#myModal .modal-body').html(data.html);
              $('#myModal').modal('show').on('hide', function() {
                $('#new_passenger').modal('hide')
                });
              $('.collectionWithVat').prop('selectedIndex',0);
            }
          }
        });
      }else{
        id=$(this).find(':selected').data('id');
        $.ajax({
          method:"post",
          url:"{{route('payFullAmount')}}",
          data:{id:id},
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

  $(document).ready(function(){
    $('#month').on('change',function(){
      value=$(this).val();
      year=$('.inputYear').val();
      segment_2=$('#segment').val();
      $.ajax({
        method:'post',
        url:"{{route('invoiceMonthlyReport')}}",
        data:{value:value,segment:segment_2, year:year},
        success:function(data){
          console.log(data);
          $('.table-striped').remove();
          $('.append').html(data);
          $('.monthvalue').val(value);
          $("#example1").DataTable();
        }
      });
    });
  });

  $(document).ready(function(){
    $('.print').click(function(e){
      e.preventDefault();
      value = $(this).data('id');
      $.ajax({
        method:"post",
        url:"{{route('printInvoice')}}",
        data:{value:value},
        success:function(data){
          var mywindow = window.open('','','left=0,top=0,width=950,height=600,toolbar=0,scrollbars=0,status=0,addressbar=0');

              var is_chrome = Boolean(mywindow.chrome);
              mywindow.document.write(data);
              mywindow.document.close();
              if (is_chrome) {
                      mywindow.onload = function() { // wait until all resources loaded 
                          mywindow.focus(); // necessary for IE >= 10
                          mywindow.print();  // change window to mywindow
                          mywindow.close();// change window to mywindow
                      };
                  }
                  else {
                      mywindow.document.close(); // necessary for IE >= 10
                      mywindow.focus(); // necessary for IE >= 10
                      mywindow.print();
                      mywindow.close();
                  }

                  return true;
           // console.log(data);
           // newWin.moveTo(0, 0);
           // newWin.resizeTo(screen.width, screen.height);
           // newWin.document.write(data);
           // setTimeout(function() {
           //     newWin.print();
           //     newWin.close();
           // }, 3000);

           
      }
      });
    });
  });

  $('.inputYear').keyup(function(){
    $('.yearvalue').val($('.inputYear').val());
  })


  $(".bod-picker").nepaliDatePicker({
      dateFormat: "%y-%m-%d",
      closeOnDateSelect: true,
      // minDate: formatedNepaliDate
  });
</script>
@endpush
