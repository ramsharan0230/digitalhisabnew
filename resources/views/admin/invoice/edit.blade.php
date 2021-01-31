@extends('layouts.admin') 
@section('title','Edit Invoice')
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
<!-- Date Picker -->
<link rel="stylesheet" href="{{ asset('backend/plugins/datepicker/datepicker3.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('backend/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.css">
<!-- bootstrap wysihtml5 - text editor -->
@endpush
@section('content')
<section class="content-header">
    <h1> Invoice<small>Edit</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href=""> Invoice</a></li>
        <li><a href="">Edit</a></li>
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
            <form method="post" action="{{route('invoice.update',$detail->id)}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" name="_method" value="PUT">
                <div class="box box-primary">
                    <div class="box-header with-heading">
                        <h3 class="box-title">Add Invoice</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group invoice1">
                                        <label>Client Name</label>
                                        <!-- <input type="text" name="name" class="form-control" value="{{old('name')}}">
                                        </div> -->
                                        <select class="form-control client" name="client_id" >
                                            <option selected="true" disabled="true">Select Client</option>
                                            @foreach($clients as $client)
                                            <option value="{{$client->id}}" {{$detail->client_id==$client->id?'selected':''}}>{{$client->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label></label>
                                        <button class="btn btn-success addClient">+</button>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Client Email</label>
                                    <input type="text" name="email" class="form-control" value="{{$detail->email}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="client_address" value="{{$detail->client_address}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>CC</label>
                                    <input type="text" name="cc" value="{{$detail->cc}}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Contact</label>
                                    <input type="text" name="contact" value="{{$detail->contact}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="text" id="nepaliDate1" class="form-control" name="nepali_date" autocomplete="off" value="{{$detail->nepali_date}}">
                                    
                                </div>
                            </div>
                        </div>
                        
                        <button class="add btn btn-warning marb15">Add Fee Description</button>
                        <div class="row fee hidden">
                        	<div class="col-md-6">
                        		<label>Description</label>
                        	</div>
                        	<div class="col-md-5">
                        		<label>Amount</label>
                        	</div>
                            <div class="col-md-1"></div>
                        </div>
                        <div class="row addRow">
                            @foreach($datas as $data)
                            <div class="feeRow">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Title" name="fee_description[]" value="{{$data->fee_description}}">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input type="number" class="form-control qty1" name="fee_amount[]" value="{{$data->fee_amount}}">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <i class="fa fa-times-circle clear removeRow"></i>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if($detail->vat!=0)
                        <div class="row vat ">
                             <div class="col-md-6">
                                <label>Vat</label>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <input type="number" class="form-control vattotal" value="{{$detail->vat}}"  name="vat" id="vat" />
                                </div>
                            </div>  
                        </div>
                        @endif
                        <div class="row total ">
                             <div class="col-md-6">
                                <label>Total</label>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <input type="number" class="form-control total" value="{{$detail->total}}"  name="total"  id="total" />
                                </div>
                            </div>  
                        </div>
                        <div class="row grand_total ">
                             <div class="col-md-6">
                                <label>Grand Total</label>
                            </div>
                            <div class="col-md-5">
                                @if($detail->vat!=0)
                                <div class="form-group">
                                    <input type="number" class="form-control grand_total_value" value="{{$detail->grand_total}}"  name="grand_total" id="grand_total" />
                                </div>
                                @else
                                <div class="form-group">
                                    <input type="number" class="form-control grand_total_value" value="{{$detail->total}}"  name="grand_total" id="grand_total" />
                                </div>
                                @endif

                            </div>  
                        </div>
                       
                        <div class="row">
                            <div class="col-md-6">
                                
                            </div>
                           
                        </div>
                        <div class="form-group">
                            <label>Amount In Words</label>
                            <input type="text" name="words" class="form-control" value="{{$detail->words}}">
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn btn-success" value="submit">
                        </div>
                        
                    </div>
                </div>
            </form>
        </div>

       
    </div>
</div>
@include('admin.client.modal')
@endsection
@push('script')
  <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('backend/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
  <!-- daterangepicker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
  <script>
    $(".bod-picker").nepaliDatePicker({
        dateFormat: "%y-%m-%d",
        closeOnDateSelect: true,
        // minDate: formatedNepaliDate
    });
    $(function () {
        $("#example1").DataTable();
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function(){
       $('.delete').submit(function(e){
        e.preventDefault();
        var message=confirm('Are you sure to delete');
        if(message){
          this.submit();
        }
        return;
       });
    });

    //total sum//
    $(document).on("input", ".qty1", function() {
    var sum = 0;
    var vat = $('.vattotal').val();
    var grandTotal= $('#grand_total').val();
    $(".qty1").each(function(){
        sum += +$(this).val();
        newVat = 0.13*sum;
        grandtotal = parseFloat(sum)+parseFloat(newVat);
    });
    $(".total").val(sum.toFixed(2));
    $(".vattotal").val(newVat.toFixed(2));
    $('#grand_total').val(grandtotal.toFixed(2));
});

    //Add row//
    $(document).ready(function(){
        $('.add').click(function(e){
            e.preventDefault();
            
            $('.fee').removeClass('hidden');
            Add='<div class="feeRow">'
            Add+='<div class="col-md-6">';
            Add+='<div class="form-group">';
            Add+='<input type="text" class="form-control" placeholder="Title" name="fee_description[]">';
            Add+='</div>';
            Add+='</div>';
            Add+='<div class="col-md-5">';
            Add+='<div class="form-group">';
            Add+='<input type="number" class="form-control qty1" name="fee_amount[]">';
            Add+='</div>';
            Add+='</div>';
            Add+='<div class="col-md-1">'
            Add+='<i class="fa fa-times-circle clear removeRow"></i>';
            Add+='</div>'
            Add+='</div>';
            $('.addRow').append(Add);
        });
    });
    //For displaying modal//
    $(document).ready(function(){
        $('.view').on('click',function(e){
            e.preventDefault();
            var year=$(this).data('year');
            var month=$(this).data('month');
            var total=$(this).data('total');
            var paid=$(this).data('pay');
            var transportation=$(this).data('transportation');
            var tution_fee=$(this).data('tution_fee');
            var library=$(this).data('library');
            $('#myModal').modal('show');
            $('#myModal').find('#year').val(year);
            $('#myModal').find('#month').val(month);
            $('#myModal').find('#tution_fee').val(tution_fee);
            $('#myModal').find('#transportation').val(transportation);
            $('#myModal').find('#library').val(library);
            $('#myModal').find('#total').val(total.toFixed(2));
        });
    });
    
    $('.message').delay(5000).fadeOut(400);

    // $(document).ready(function(){
    //     $('#add').on('click',function(e){
    //         e.preventDefault();
    //         var Add='<tr>';
    //             Add+='<td>';
    //             Add+='<div class="form-group">';
    //             Add+='<input type="text" name="" class="form-control">';
    //             Add+'</div>';
    //             Add+='</td>';
    //             Add+='<td>';
    //             Add+='<div class="form-group">';
    //             Add+='<input type="number" name="" class="form-control prc">';
    //             Add+'</div>';
    //             Add+='</td>';
    //             Add+='<td>';
    //             Add+='<i class="fa fa-times-circle clearExperience"></i>';
    //             Add+='</td>';
    //             Add+='</tr>';
    //             $('#addRow').append(Add);
    //     });  
    // });
    $(document).on('click','.removeRow',function(){
            $(this).parent('div').closest('.feeRow').remove();
            var value=$(this).parent('div').closest('.feeRow').find('.qty1').val();

            var total= $('#total').val();
        
            var reducedValue=total-value;
            var vatAmount = 0.13 * reducedValue;
            $('.vattotal').val(vatAmount.toFixed(2));
            $("#total").val(reducedValue.toFixed(2));
            grandTotal = parseFloat(vatAmount)+parseFloat(reducedValue);
            $('#grand_total').val(grandTotal.toFixed(2));
        });
    $('input[name="vatShow"]').click(function(){
           var vatShowChecked = $('input[name="vatShow"]:checked').length > 0;
           if(vatShowChecked==true){
                $('.vat').removeClass('hidden');
                var total = $('#total').val();
                var vatAmount = 0.13 * total;
                var grandTotal = parseFloat(total)+parseFloat(vatAmount);
                $('.vattotal').val(vatAmount.toFixed(2));
                $('#grand_total').val(grandTotal.toFixed(2));

           }else{
                $('.vat').addClass('hidden');
           }
           
        });
    $(document).ready(function(){
        $('.addClient').click(function(e){
            e.preventDefault();
            $('#myModal').modal('show');
        });

        $('.client').change(function(e){
            e.preventDefault();
            value = $(this).val();
            if($.isNumeric(value)){
                $.ajax({
                    method:"post",
                    url:"{{route('findClient')}}",
                    data:{value:value},
                    success:function(data){
                        if(data.message=='success'){
                            $('#contact').val(data.client.phone);
                            $('#email').val(data.client.email);
                            $('#address').val(data.client.address);
                        }else{
                            alert('something went wrong');
                        }
                    }
                });
            }
        });
    });
    </script>
@endpush