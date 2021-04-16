<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{asset('backend/bootstrap/css/bootstrap.min.css')}}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.4/datepicker.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.css">
  
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('backend/dist/css/AdminLTE.css')}}">
 <link rel="stylesheet" href="{{asset('backend/dist/css/style.css')}}">

  <link rel="stylesheet" href="{{asset('backend/plugins/datepicker/datepicker3.css')}}">

  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{asset('backend/dist/css/skins/_all-skins.min.css')}}">
 
  
  <link rel="stylesheet" href="{{asset('backend/dist/js/nepali.datepicker.v2.2.min.css')}}">

  <style type="text/css">
    .modal-dialog{
        position: relative;
        display: table; //This is important
        overflow-y: auto;
        overflow-x: auto;
        min-width: 300px;
    }

    .jcrop-keymgr{
      opacity:0 !important;
    }
    button{
          background: none;
            border:none;
            padding:0;
            margin:0;
        }
  
  </style>
  @stack('styles')
  <?php
    $today=Carbon\Carbon::today();
    
    
    $user=Auth::user();
    
    $role=$user->role;
    $user_access = explode(",",$user->access_level);
    
    
    
    
  ?>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->

<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>D</b>H</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Digital</b> Hisab</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">

        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <!-- Notifications: style can be found in dropdown.less -->
          <!-- Tasks: style can be found in dropdown.less -->
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-xs">Digital Hisab</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <p>
                  Digital Hisab
                </p>
              </li>
              <!-- Menu Body -->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-right">
                  <a href="{{route('logout')}}" class="btn btn-default btn-flat">Log Out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
            <?php //dd($dashboard_setting) ?>
          <img src="{{asset('images/'.@$dashboard_setting->logo)}}"  alt="User Image" style="max-width: 120px;" >
        </div>
       
      </div>
      <!-- search form -->
      
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="treeview">
          <a href="{{route('dashboard.index')}}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
           <!--  <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span> -->
          </a>

        <li class="treeview "><a href="#"><i class="fa fa-sliders"></i> <span>Contacts </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
            <ul class="treeview-menu">
              <li class=""><a href="{{route('vendor.index')}}"><i class="fa fa-circle-o text-yellow"></i> Vendors</a></li>
              <li class=""><a href="{{route('client.index')}}"><i class="fa fa-circle-o text-aqua"></i> Clients</a></li>
            </ul>
        </li>

  
        @if($role=="admin" || ($role=="staff" && (in_array("invoice",$user_access))))
        <li class="treeview ">
          <a href="#"><i class="fa fa-sliders"></i> <span>Invoice</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
            <ul class="treeview-menu">
              <li class=""><a href="{{route('invoice.create')}}"><i class="fa fa-circle-o text-yellow"></i> Add Invoice</a></li>
              <li class=""><a href="{{route('invoice.index')}}"><i class="fa fa-circle-o text-aqua"></i> All Invoices</a></li>
              <!-- <li class=""><a href="{{route('invoiceReport')}}"><i class="fa fa-circle-o text-aqua"></i> Report</a></li> -->
            </ul>
        </li>
        @endif

        @if($role=="admin" || ($role=="staff" && (in_array("received",$user_access))))
        <li class="treeview ">
          <a href="#">
            <i class="fa fa-sliders"></i> <span>Receipts</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class=""><a href="{{route('received.index')}}"><i class="fa fa-circle-o text-yellow"></i>All Receipts</a></li>
            <li class=""><a href="{{route('received.create')}}"><i class="fa fa-circle-o text-yellow"></i>Add Receipts</a></li>
            
          </ul>
        </li>
        @endif

        @if($role=="admin" || ($role=="staff" && (in_array("sales",$user_access))))
        <li class="treeview ">
          <a href="#"><i class="fa fa-sliders"></i> <span>Sales Book</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
          <ul class="treeview-menu">
            <li class=""><a href="{{route('sales.index')}}"><i class="fa fa-circle-o text-aqua"></i>Sales</a></li>
            
            @if($dashboard_setting->display_sales_without_vat==1)
            <li class=""><a href="{{route('salesWithoutVat')}}"><i class="fa fa-circle-o text-aqua"></i>Sales Without Vat</a></li>
            @endif
            <li class=""><a href="{{route('toBeCollected')}}"><i class="fa fa-circle-o text-aqua"></i>To Be Collected</a></li>
          </ul>
        </li>
        @endif

        @if($role=="admin" || ($role=="staff" && (in_array("purchase-vat",$user_access))))
        <li class="treeview sortable_class" id="">
          <a href="#">
            <i class="fa fa-sliders"></i> <span class="menutextfind">Purchase Book</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class=""><a href="{{route('purchase.create')}}"><i class="fa fa-circle-o text-yellow"></i> Add Purchase</a></li>
            <li class=""><a href="{{route('purchase.index')}}"><i class="fa fa-circle-o text-aqua"></i>Purchase </a></li>
            <li class=""><a href="{{route('toBePaid')}}"><i class="fa fa-circle-o text-aqua"></i>To Be Paid </a></li>
            
          </ul>
        </li>
        @endif

        @if($role=="admin" || ($role=="staff" && (in_array("payment",$user_access))))
        <li class="treeview ">
          <a href="#">
            <i class="fa fa-sliders"></i> <span>Payment</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class=""><a href="{{route('payment.create')}}"><i class="fa fa-circle-o text-yellow"></i> Add Payment</a></li>
            <li class=""><a href="{{route('payment.index')}}"><i class="fa fa-circle-o text-aqua"></i> All Payments</a></li>
          </ul>
        </li>
        @endif

         @if($role=="admin" || ($role=="staff" && (in_array("day-book",$user_access))))
        <li class="treeview sortable_class" id="">
          <a href="{{route('reportDaybook')}}">
            <i class="fa fa-sliders"></i> <span class="menutextfind">DayBook</span>
            
          </a>
          
        </li>
        @endif

        <!-- @if($role=="admin" || ($role=="staff" && (in_array("day-book",$user_access)))) -->
        <li class="treeview sortable_class" id="">
          <a href="{{route('reportProfitAndLoss')}}">
            <i class="fa fa-sliders"></i> <span class="menutextfind">Profit & Loss</span>
            
          </a>
          
        </li>
        <!-- @endif -->

        <li class="treeview ">
          <a href="#">
            <i class="fa fa-sliders"></i> <span>Reports</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class=""><a href="{{route('salesReport')}}"><i class="fa fa-circle-o text-yellow"></i>Sales</a></li>
            <li class=""><a href="{{route('reportReceiptList')}}"><i class="fa fa-circle-o text-aqua"></i> Receipts</a></li>
            <li class=""><a href="{{route('reportinvoiceList')}}"><i class="fa fa-circle-o text-aqua"></i> Invoices</a></li>
            <li class=""><a href="{{route('toBeCollected')}}"><i class="fa fa-circle-o text-aqua"></i> To be collected</a></li>
            <li class=""><a href="{{route('toBePaid')}}"><i class="fa fa-circle-o text-aqua"></i> To be Paid</a></li>
            <li class=""><a href="{{route('reportClientList')}}"><i class="fa fa-circle-o text-aqua"></i> Clients</a></li>
            <li class=""><a href="{{route('reportProfitAndLoss')}}"><i class="fa fa-circle-o text-aqua"></i> Profit and Loss</a></li>
            <li class=""><a href="{{route('reportVendorList')}}"><i class="fa fa-circle-o text-aqua"></i> Suppliers</a></li>
            <li class=""><a href="{{route('purchaseReport')}}"><i class="fa fa-circle-o text-aqua"></i> Purchase</a></li>
            
            <li class="treeview ">
              <a href="#">
                <i class="fa fa-bar-chart " aria-hidden="true"></i>
                <span>Vat</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class=""><a href="{{route('reportVatCollected')}}"><i class="fa fa-circle-o text-aqua"></i> Vat Collected</a></li>
                <li class=""><a href="{{route('reportVatPaid')}}"><i class="fa fa-circle-o text-aqua"></i> Vat Paid</a></li>
              </ul>
            </li>
            <li class="treeview ">
              <a href="#">
                <i class="fa fa-bar-chart " aria-hidden="true"></i>
                <span>Tds</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class=""><a href="{{route('reportTdsToBeCollected')}}"><i class="fa fa-circle-o text-aqua"></i>Tds To Be Collected</a></li>
                <li class=""><a href="{{route('reportTdsToBePaid')}}"><i class="fa fa-circle-o text-aqua"></i>Tds To Be Paid</a></li>
              </ul>
            </li>
            
            <li class=""><a href="{{route('reportPaymentList')}}"><i class="fa fa-circle-o text-aqua"></i> Payment</a></li>
            
            
            
            
            
            
            <!-- <li class=""><a href="{{route('purchaseReport')}}"><i class="fa fa-circle-o text-aqua"></i> Purchase</a></li> -->
            
            
            <!-- <li class=""><a href=""><i class="fa fa-circle-o text-aqua"></i> Voucher</a></li>
            <li class=""><a href=""><i class="fa fa-circle-o text-aqua"></i> Ledger</a></li>
            <li class=""><a href=""><i class="fa fa-circle-o text-aqua"></i> Trial Balance</a></li>
            <li class=""><a href=""><i class="fa fa-circle-o text-aqua"></i> Balance Sheet</a></li> -->
            <li class=""><a href="{{route('reportDaybook')}}"><i class="fa fa-circle-o text-aqua"></i> Daybook</a></li>
            
          </ul>


        </li>

        @if($role=="admin" || ($role=="staff" && (in_array("tds",$user_access))))
        <li class="treeview ">
          <a href="#">
            <i class="fa fa-sliders"></i> <span>Tds</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class=""><a href="{{route('tds.index')}}"><i class="fa fa-circle-o text-yellow"></i>Tds To Be Collected</a></li>
            
          </ul>
          <ul class="treeview-menu">
            <li class=""><a href="{{route('tdsToBePaid')}}"><i class="fa fa-circle-o text-yellow"></i>Tds To Be Paid</a></li>
            
          </ul>
        </li>
        @endif

        @if($role=="admin" || ($role=="staff" && (in_array("bank",$user_access))))
        <li class="treeview ">
          <a href="#">
            <i class="fa fa-sliders"></i> <span>Bank & Balance</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class=""><a href="{{route('bank.index')}}"><i class="fa fa-circle-o text-aqua"></i> Banks</a></li>
            <li class=""><a href="{{route('balance.index')}}"><i class="fa fa-circle-o text-yellow"></i> Balance</a></li>
            <li class=""><a href="{{route('payment-gateway.index')}}"><i class="fa fa-circle-o text-aqua"></i> Payment Gateways</a></li>
          </ul>
        </li>
        @endif



        
        
       
        @if($role=="admin" || ($role=="staff" && (in_array("user",$user_access))))
        <li class="treeview ">
          <a href="#">
            <i class="fa fa-sliders"></i> <span>User</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class=""><a href="{{route('user.create')}}"><i class="fa fa-circle-o text-yellow"></i>Add User</a></li>
            
          </ul>
          <ul class="treeview-menu">
            <li class=""><a href="{{route('user.index')}}"><i class="fa fa-circle-o text-yellow"></i>All Users</a></li>
            
          </ul>
        </li>
        @endif
      

       

        @if($role=="admin" || ($role=="staff" && (in_array("setting",$user_access))))
        <li class="treeview sortable_class" id="">
          <a href="{{route('setting.create')}}">
            <i class="fa fa-sliders"></i> <span class="menutextfind">Setting</span>
            
          </a>
          
        </li>
        @endif
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
   

    <!-- Main content -->
   @yield('content')

    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    
    
  </footer>

  <!-- Control Sidebar -->
  <!--  -->
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="{{asset('backend/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{asset('backend/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- SlimScroll -->
<script src="{{asset('backend/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('backend/plugins/fastclick/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('backend/dist/js/app.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->

<script src="{{asset('backend/dist/js/demo.js')}}"></script>
<script src="{{asset('js/nepali.datepicker.v2.2.min.js')}}"></script>

@stack('script')
</body>
</html>
