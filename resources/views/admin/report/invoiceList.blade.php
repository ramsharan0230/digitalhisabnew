@extends('layouts.admin')
@section('title','Invoice List')
@push('styles')

<style type="text/css">
  
  .form-btn-wrapper {

    display: flex;align-items: center;
  }

  .top-form-wrapp {

    display: block;
    width: 100%;
  }

  .form-btn-wrapper .btn {

    margin-right: 10px;
  }
</style>
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
@endpush
@section('content')
<section class="content-header">

  <h1>Invoice<small>List</small></h1>

      


 
    <div class="box">
      <div class="row">
        <div class="col-md-6">
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
                <div class="form-group sales-btn">
                  <input type="submit" name="submit" value="submit" class="btn btn-success customDateSearch">
                </div>
              </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box-header"><h3 class="box-title">Year and Month</h3></div>
            <div class="box-body">
                <div class="erport-wrapp profit-loss-wrapp">
                    <div class="form-group form-group-wrapper">
                        <label>Input Year</label>
                        <input class="form-control" type="text" placeholder="Input Year">
                    </div>
                    <div class="form-group select-mont-wrapp top-form-wrapp form-group-wrapper">
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

                    <form class="export-form" method="post" action="{{route('dayBookExport')}}">
                        {{csrf_field()}}
                        <input type="hidden" name="month" class="monthvalue" value="">
                        <input type="hidden" name="type" value="0">
                        <input type="hidden" name="segment" value="{{Request::segment(2)}}" id="segment">
                        <input type="submit" name="Export" value="Export" class="btn btn-info">
                    </form>
                </div>
            </div>
        </div>
      </div>
      
    </div>
    <ol class="breadcrumb">
      <li><a href=""><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li><a href="">Invoice</a></li>
      <li><a href="">List</a></li>
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
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                      
                      <th>S.N.</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Collected</th>
                      <th>Total</th>
                      <th>Action</th>
               
                    </tr>
                </thead>
                <tbody id="sortable">
                @php($i=1)
                @foreach($details as $detail)
                  <tr id="{{ $detail->id }}">

                     
                      <td>{{$i}}</td>
                      <td>{{$detail->client_name}}</td>
                      <td>{{$detail->email}}</td>
                      <td>{{$detail->collected_amount}}</td>
                      <td>{{$detail->grand_total}}</td>
                      <td>
                       
                       <a class="btn btn-warning edit" href="{{route('previewInvoice',$detail->id)}}" target="_blank" title="Edit"><span class="fa fa-eye"></span></a>
                       <a class="btn btn-info print" href="#" title="Edit" data-id="{{$detail->id}}"><span class="fa fa-edit"></span>Print</a>
                       



                      </td>
                  </tr>
                  @php($i++)
                  @endforeach
                </tbody>
              </table>
                <div class="table-total-wrapper">
                    <p>Total</p>
                    <span>{{$details->sum('grand_total')}}</span>
                </div>
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
      $('.send').click(function(){
        $(this).text('Sending...');
        $(this).removeClass('btn-info').addClass('btn-warning');
        var id=$(this).data('id');
        var _this=this;
        $.ajax({
          method:'POST',
          url:"{{route('sendInvoice')}}",
          data:{id:id},
          success:function(data){
            console.log(data);
            if(data=='success'){
              $(_this).text('Mail Sent');
              $(_this).removeClass('btn-warning').addClass('btn-success');
            }else{
              console.log(data);
              $(_this).text('Failed');
              setTimeout(function() { 
                $(_this).text('Send Email');
                $(_this).removeClass('btn-warning').addClass('btn-info');
              }, 3000);
            }
            
          }
        });
      });
    });

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
        segment_2=$('#segment').val();
        console.log(segment_2);
        $.ajax({
          method:'post',
          url:"{{route('reportInvoiceSearchByMonth')}}",
          data:{value:value,segment:segment_2},
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
          url:"{{route('reportPrintInvoice')}}",
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

   

    

   $('#example1').dataTable( {
  "pageLength": 100
  } );
  $(".bod-picker").nepaliDatePicker({
      dateFormat: "%y-%m-%d",
      closeOnDateSelect: true,
      // minDate: formatedNepaliDate
  });
  $(document).ready(function(){
    $('.customDateSearch').click(function(e){
      e.preventDefault();
      start_date = $('#start_date').val();
      end_date = $('#end_date').val();
      $.ajax({
        method:'post',
        url:"{{route('customInvoiceSearch')}}",
        data:{start_date:start_date,end_date:end_date},
        success:function(data){
          $('.table-striped').remove();
          $('.append').html(data);
          $('#example1').dataTable();
        }
      });
    });
  });
</script>
@endpush
