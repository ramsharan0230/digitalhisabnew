@extends('layouts.admin')
@section('title','Invoice List')
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
@endpush
@section('content')
<section class="content-header">
  <h1>Invoice 123<small>List</small></h1>
    <a href="{{route('invoice.create')}}" class="btn btn-success">Add Invoice</a>
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
          <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                      
                      <th>S.N.</th>
                      <th>Name</th>
                      <th>Email</th>
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
                      <td>{{$detail->grand_total}}</td>
                      <td>
                        <a class="btn btn-info edit" href="{{route('invoice.edit',$detail->id)}}" title="Edit"><span class="fa fa-edit"></span></a>
                       <a class="btn btn-warning edit" href="{{route('previewInvoice',$detail->id)}}" target="_blank" title="Edit"><span class="fa fa-eye"></span></a>
                       <a class="btn btn-info send" title="Send" data-id="{{$detail->id}}">{{$detail->collected_type!=null?'ReInvoice':'Send Email'}}</a>
                        <form method= "post" action="{{route('invoice.destroy',$detail->id)}}" class="delete btn btn-danger">
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn-delete" style="display:inline"><span class="fa fa-trash"></span></button>
                        </form>
                        
                        @if($detail->vat_id==null && $detail->vat!=0)
                        <a href="{{route('invoice.show',$detail->id)}}" class="btn btn btn-warning">Billed</a>
                        @endif
                        @if($detail->vat_id!=null && $detail->vat!=0 && $detail->collected==0)
                        <!-- <a href="{{route('markCollectedVat',$detail->vat_id)}}" class="btn btn btn-warning salesWithVatCollection">Collected</a> -->
                          @if($detail->collected!=$detail->grand_total)
                          <div class="form-group" id="select{{$detail->id}}">
                            <select class="form-control collectionWithVat">
                              <option disabled="true" selected="true">Collection</option>
                              <option value="partial" data-id="{{$detail->id}}">Partial</option>
                              <option value="full" data-id="{{$detail->id}}">Full</option>
                            </select>
                          </div>
                          @endif
                        @endif
                        @if($detail->vat==0 && $detail->sales_without_vat_collected==0)
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
@include('admin.include.modal')
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
        
        if($(this).val()=='partial'){
          
          id=$(this).find(':selected').data('id');
          $.ajax({
            method:"post",
            url:"{{route('checkAmountToBeCollcted')}}",
            data:{id:id},
            success:function(data){
              if(data.message=='success'){
                $('#myModal .modal-body').html(data.html);
                $('#myModal').modal('show');
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
              if(data.message=='success'){
                $('#select'+id).addClass('hidden');
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

   

    

   $('#example1').dataTable( {
  "pageLength": 100
  } );
</script>
@endpush
