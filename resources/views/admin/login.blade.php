<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Digital Hisab</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{asset('backend/bootstrap/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('backend/dist/css/AdminLTE.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('backend/plugins/iCheck/square/blue.css')}}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<style type="text/css">

  .login-card {
    border: 0;
    border-radius: 27.5px;
    box-shadow: 0 10px 30px 0 rgba(172, 168, 168, 0.43);
    overflow: hidden;

    background: #fff;
    margin-top: 50px!important;
}
  
.login-card .card-body {
    padding: 85px 60px 60px;
}

.brand-wrapper {
    margin-bottom: 19px;
    font-size: 40px;
}

.brand-wrapper span{color: #8c1b9f;}

.login-card-description {
    font-size: 25px;
    color: #000;
    font-weight: normal;
    margin-bottom: 23px;
}

.login-card form {
    max-width: 326px;
}

.login-card .forgot-password-link {
    font-size: 14px;
    color: #919aa3;
    margin-bottom: 12px;
}

.login-card-footer-text {
    font-size: 16px;
    color: #0d2366;
    margin-bottom: 60px;
}


.login-card .form-control {
    border: 1px solid #d5dae2;
    padding: 15px 25px;
    margin-bottom: 20px;
    min-height: 45px;
    font-size: 13px;
    line-height: 15;
    font-weight: normal;
}

.login-card .login-btn {
    padding: 13px 20px 12px;
    background-color: #8c1b9f;
    border-radius: 4px;
    font-size: 17px;
    font-weight: bold;
    line-height: 20px;
    color: #fff;
    margin-bottom: 24px;
}

.login-card .forgot-password-link {
    font-size: 14px;
    color: #919aa3;
    margin-bottom: 12px;
}


.login-card-img {
    border-radius: 0;
 
    width: 100%;
    height: 500px;
    -o-object-fit: cover;
    object-fit: cover;
}


</style>








<body class="hold-transition login-page">





<main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    <div class="container">
      <div class="card login-card">
        <div class="row no-gutters">
          <div class="col-md-5">
            <img src="{{asset('backend/dist/img/digitaldaybook.jpg')}}" alt="login" class="login-card-img">
          </div>
          <div class="col-md-7">

            <div class="card-body">
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
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                  {!! Session::get('message') !!}
              </div>
              @endif
              <div class="brand-wrapper">
             DIGITAL <span>HISAB</span>
              </div>
              <p class="login-card-description">Sign into your account</p>
      <form action="{{route('postLogin')}}" method="post">
    {{csrf_field()}}
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" name="email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-12">
          <button type="submit" class="btn btn-block login-btn mb-4">Sign In</button>
          
        </div>
     <!--    <div class="col-xs-12">
          
          <a href="{{route('password-reset')}}" class="btn btn-success btn-block btn-flat">Forgot Password</a>
        </div> -->
        
        <!-- /.col -->
      </div>
    </form>
                   <a href="{{route('password-reset')}}" class="forgot-password-link">Forgot password?</a>
             
          
            </div>
          </div>
        </div>
      </div>
   
    </div>
  </main>






<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="{{asset('backend/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{asset('backend/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- iCheck -->
<script src="{{asset('backend/plugins/iCheck/icheck.min.js')}}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
