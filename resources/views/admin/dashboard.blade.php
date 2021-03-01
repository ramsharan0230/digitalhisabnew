@extends('layouts.admin')	
@section('title','Dashboard')
@push('admin.styles')
<!-- Date Picker -->
<link rel="stylesheet" href="{{ asset('backend/plugins/datepicker/datepicker3.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('backend/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('backend/dist/css/fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/dist/css/all.min.css') }}">

<!-- bootstrap wysihtml5 - text editor -->
@endpush
@section('content')
<section class="content-header">
	<h1>Dashboard<small></small></h1>
	
</section>
<div class="content">



<div class="row havemargin">
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <?php
                $balance=$totalReceived-($totalPayment+$totalPurchasePaymentMade);
              ?>
              <h3>{{$balance}} Nrs</h3>

              <p>Balance</p>
            </div>
            <div class="icon">

        <i class="ion-soup-can"></i>
           
            </div>
            <a href="{{route('balance.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{$amountToBeCollected}} Nrs</h3>

              <p>To be Collected</p>
            </div>
            <div class="icon">
               <i class="ion-briefcase"></i>
            </div>
            <a href="{{route('toBeCollected')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <?php
              $needToPay = $purchase-$totalPurchasePaymentMade;
              ?>
              <h3>{{$needToPay}} Nrs</h3>

              <p>Need To Pay</p>
            </div>
            <div class="icon">

      
              <i class="ion-card"></i>
            </div>
            <a href="{{route('toBePaid')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
 
        <!-- ./col -->
      </div>



<div class="row">
        <!-- Left col -->
        <div class="col-md-8">
    
          <!-- /.box -->
  

          <!-- TABLE: LATEST ORDERS -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Invoice</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Invoice ID</th>
                    <th>To</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Action</th>
                    
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($invoices as $invoice)
                  <tr>

                    <td>#{{$invoice->id}}</td>
                    <td>{{$invoice->client_name}}</td>
                    <td>Rs. {{$invoice->grand_total}}</td>
                    <td>{{$invoice->nepali_date}}</td>
                    <td><a class="btn btn-info edit" target="_blank" href="{{route('previewInvoice', $invoice->id)}}" title="Edit"><span class="fa fa-eye"></span></a></td>
                    </tr>
                  @endforeach

                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a href="{{route('invoice.index')}}" class="btn btn-sm btn-info btn-flat pull-left">View All Invoice</a>

            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

      
<div class="col-md-4">
      
  

          <!-- PRODUCT LIST -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Recently Purchase</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                @foreach($purchaseItem as $purchase)
                <li class="item">
              
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">{{$purchase->particular}}
                      <span class="label label-warning pull-right">{{$purchase->purchased_from}}</span></a>
                    
                    <span class="product-description">
                          {{$purchase->purchased_item}}
                        </span>
                  </div>
                </li>
                @endforeach
             
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="{{route('purchase.index')}}" class="uppercase">View All</a>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <div class="x_panel height_manage">
                <div class="x_title">
                    <h2>Sales</h2>
             <!--        <ul class="nav navbar-right panel_toolbox">


              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul> -->
                    <div class="clearfix"></div>
                </div>
                <ul class="nav">
                    <li>
                        <form action="">
                            <div class="row">
                              <div class="col-md-4 form-group">
                              <input type="text" name="year" class="form-control year" placeholder="Input year">
                            </div>
                            </div>
                        </form>
                    </li>
                </ul>
                <div class="x_content">
                    <canvas id="trades"></canvas>
                </div>
            </div>
          </div>
          <!-- <div class="col-md-8">
            <div class="box box-solid bg-teal-gradient">
              <div class="box-header ui-sortable-handle" style="cursor: move;">
                <i class="fa fa-th"></i>
                <h3 class="box-title">Sales Graph</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="nav-tabs-custom">
                ldkg ldfkglskfgj 
              </div> 
            </div>
          </div> -->
        
      <div class="col-md-4">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Latest Collections</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
            <!-- /.box-header -->
          <div class="box-body">
            <div class="table-responsive">
              <table class="table no-margin">
                <thead>
                  <tr>
                    <th>From</th>
                    <th>Date</th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($receiveds as $received)
                  <tr>
                    <td>{{$received->received_from}}</td>
                    <td>{{$received->date}}</td>
                    <td>{{$received->amount}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
              <!-- /.table-responsive -->
          </div>
            <!-- /.box-body -->
          <div class="box-footer clearfix">
            <a href="{{route('received.index')}}" class="btn btn-sm btn-info btn-flat pull-left">View All Collections</a>
          </div>
            <!-- /.box-footer -->
        </div>
      </div>
    </div>
</div><!-- end of contain-->
@endsection
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
  <!-- daterangepicker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
  <!-- datepicker -->
  <script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
  	

    var sales = <?php echo json_encode($salesAndPurchaseVat['salesVat']);?>;
    var purchase = <?php echo json_encode($salesAndPurchaseVat['purchasevat']);?>;
    
    if ($("#trades").length) { 
        var f = document.getElementById("trades");
       
        
        $product_drink_sold=new Chart(f, 
            { type: "line", 
                data:
                { 
                    labels: ['Baishak','Jestha','Asar','Shrawan','Bhadra','Ashoj','Kartik','Mangsir','Poush','Magh','Falgun','Chaitra'],
                    
                    datasets: 
                    [
                        { 
                            label: "Sales", 
                            /*backgroundColor: "rgba(38, 185, 154, 0.31)",*/ 
                            borderColor: "#ff6384", 
                            pointBorderColor: "rgba(38, 185, 154, 0.7)", 
                            pointBackgroundColor: "rgba(38, 185, 154, 0.7)", 
                            pointHoverBackgroundColor: "#fff", 
                            pointHoverBorderColor: "#ff6384", 
                            pointBorderWidth: 1, 
                            data: sales,

                        }, 

                        { 
                            label: "Purchase", 
                            /*backgroundColor: "rgba(3, 88, 106, 0.3)",*/ 
                            borderColor: "#36a2eb", 
                            pointBorderColor: "rgba(3, 88, 106, 0.70)", 
                            pointBackgroundColor: "rgba(3, 88, 106, 0.70)", 
                            pointHoverBackgroundColor: "#fff", 
                            pointHoverBorderColor: "rgba(151,187,205,1)", 
                            pointBorderWidth: 1, 

                            data: purchase,
                        }
                    ] 
                } ,
                options: {
                    scales: {
                        xAxes: [{
                            stacked: false,
                            beginAtZero: true,
                            scaleLabel: {
                                labelString: 'Month'
                            },
                            ticks: {
                                stepSize: 1,
                                min: 0,
                                autoSkip: false
                            }
                        }]
                    },
              
                }
            }
        ) 
    }
    

    
      $(document).on('change','.year',function(){
        value=$(this).val();
        $.ajax({
            method:'POST',
            url:"{{route('salesAndPurchaseVatByYear')}}",
            data:{value:value},
            success:function(data){
                console.log(data.data);
                // console.log($product_drink_sold.data.datasets[0].data);
                
                $product_drink_sold.data.datasets[0].data=data.salesVat;
                $product_drink_sold.data.datasets[1].data=data.purchasevat;
                console.log($product_drink_sold.data.datasets[1].data);
                $product_drink_sold.update();
            }
        });

      });
    
  	
    </script>
@endpush