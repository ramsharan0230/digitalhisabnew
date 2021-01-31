@extends('layouts.admin') 
@section('title','Add Invoice')
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
    <h1> Invoice<small>Create</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href=""> Invoice</a></li>
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
            <form method="post" action="{{route('invoice.store')}}" enctype="multipart/form-data">
                {{csrf_field()}}
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
                                            <option value="{{$client->id}}">{{$client->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group add-client-plus">
                                        <label></label>
                                        <button class="btn btn-success addClient">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group invoice1">
                            <label>Client Email</label>
                            <input type="text" name="email" class="form-control" value="{{old('email')}}" id="email">
                            </div>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-lg-6">
                             <div class="form-group invoice1">
                            <label>Address</label>
                            <input type="text" name="client_address"  class="form-control" value="{{old('client_address')}}" id="address">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group invoice1">
                            <label>CC</label>
                            <input type="text" name="cc" value="" class="form-control" placeholder="john@gmail.com,Jeny@gmail.com">
                            </div>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group invoice1">
                            <label>Contact</label>
                            <input type="text" name="contact" value="{{old('contact')}}" class="form-control" id="contact">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group invoice1">
                            <label>Date</label>
                            <input type="text" id="nepaliDate1" class="bod-picker form-control" name="nepali_date" autocomplete="off" value="{{old('nepali_date')}}">
                            </div>
                        </div>
                        </div>
                        <div class="row">
                       <div class="col-lg-6">
                            <div class="form-group invoice1 vat-wrapp">
                            <label for="vatShow"><input type="checkbox" id="vatShow" name="vatShow"  > Vat</label>
                            </div>
                            <button class="add btn btn-warning">Add Fee Description</button>
                       </div>
                       </div>
                        
                        
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
                           
                        </div>
                        <div class="row vat hidden">
                             <div class="col-md-6">
                                <label>Vat</label>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <input type="text" class="form-control vattotal" value="" readonly name="vat" id="vat" />
                                </div>
                            </div>  
                        </div>
                        <div class="row total hidden">
                             <div class="col-md-6">
                                <label>Total</label>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <input type="number" class="form-control total" value="" readonly name="total" id="total" />
                                </div>
                            </div>  
                        </div>
                        <div class="row grand_total hidden">
                             <div class="col-md-6">
                                <label>Grand Total</label>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <input type="text" class="form-control grand_total_value" value=""  name="grand_total" id="grand_total" />
                                </div>
                            </div>  
                        </div>
                       
                        <div class="row">
                            <div class="col-md-6">
                                
                            </div>
                           
                        </div>
                        <div class="form-group">
                            <label>Amount In Words</label>
                            <input type="text" name="words" class="form-control">
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
        var vatShowChecked = $('input[name="vatShow"]:checked').length > 0;
           if(vatShowChecked==true){
                    newVat = 0.13*sum;
                    grandtotal = parseFloat(sum)+parseFloat(newVat);

        }else{
            newVat = sum;
            grandtotal = parseFloat(sum);
        }
        
        
        
    });
    $(".total").val(sum.toFixed(2));
    $(".vattotal").val(newVat.toFixed(2));
    $('#grand_total').val(grandtotal.toFixed(2));
});

    //Add row//
    $(document).ready(function(){
        $('.add').click(function(e){
            e.preventDefault();
            $('.total').removeClass('hidden');
            $('.fee').removeClass('hidden');
            Add='<div class="feeRow">'
            Add+='<div class="col-md-6">';
            Add+='<div class="form-group">';
            Add+='<input type="text" class="form-control" placeholder="Title" name="fee_description[]">';
            Add+='</div>';
            Add+='</div>';
            Add+='<div class="col-md-5">';
            Add+='<div class="form-group">';
            Add+='<input type="text" class="form-control qty1" name="fee_amount[]">';
            Add+='</div>';
            Add+='</div>';
            Add+='<div class="col-md-1">'
            Add+='<i class="fa fa-times-circle clear removeRow"></i>';
            Add+='</div>'
            Add+='</div>';
            $('.addRow').append(Add);
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
                $('#total').removeClass('hidden');
                $('.total').removeClass('hidden');
                $('.grand_total').removeClass('hidden');
                var total = $('#total').val();
                var vatAmount = 0.13 * total;
                var grandTotal = parseFloat(total)+parseFloat(vatAmount);
                $('.vattotal').val(vatAmount.toFixed(2));
                $('#grand_total').val(grandTotal.toFixed(2));

           }else{
                $('.vat').addClass('hidden');
                $('#total').addClass('hidden');
                $('.total').addClass('hidden');
                var sum = 0;
    
                $(".qty1").each(function(){
                    sum += +$(this).val();
                    
                    
                });
                vat=0.13*sum
                console.log(vat);
                $('#grand_total').val(parseFloat(sum));

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

                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush