<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Pharmacy SYS | <?php echo $page_title; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no" />
  <!-- Theme Color -->
  <meta name="theme-color" content="#000000" />
  <!-- Manifest File -->
  <link rel="manifest" href="assets/manifest.json">
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="assets/img/icon.png">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- pace-progress -->
  <link rel="stylesheet" href="assets/plugins/pace-progress/themes/black/pace-theme-flat-top.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
  <!-- On Form Submit Preloader CSS -->
  <link rel="stylesheet" href="assets/css/onSubmitPreloader.css">
  <!-- Splash Screen Css -->
  <link rel="stylesheet" href="assets/css/splash-screen.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/css/adminlte.min.css">
</head>
<body class="hold-transition register-page">

  <!-- On Form-Submit Loader -->
  <div id="pageloader">
    <img style="position: absolute;top: 50%;bottom: 0;left: 0;right: 0;margin: 0 auto;" src="assets/img/ProcessingIcon.gif" alt="processing...">
  </div>

  <div id="animate-splash" class="register-box d-none">
    <div class="register-logo">
      <a><b><h1>PHARMACY </h1></b><h3> MANAGEMENT SYSTEM</h3></a>
    </div>

    <div class="card elevation-4">
      <div class="card-body register-card-body">
        <p class="login-box-msg">ADMINISTRATOR SETUP WIZARD</p>

        <form id="register" method="POST" enctype="application/x-www-form-urlencoded" action="?url=alpha/setSystemAdministrator">
          <div class="input-group mb-3">
            <input type="text" class="form-control" name="firstname" placeholder="First Name" autocomplete="off" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="text" class="form-control" name="lastname" placeholder="Last Name" autocomplete="off" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="email" class="form-control" name="email" placeholder="Email (Optional)" autocomplete="off">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="text" class="form-control" name="username" placeholder="Username" autocomplete="off" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="off" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" name="confirm_password" placeholder="Retype password" autocomplete="off" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" value="submit" class="btn btn-primary btn-block">Create Account</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
      </div>
      <!-- /.form-box -->
    </div><!-- /.card -->
  </div>
  <!-- /.register-box -->

  <?php require_once 'splash_animation.php'; ?>

  <!-- jQuery -->
  <script src="assets/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- pace-progress -->
  <script src="assets/plugins/pace-progress/pace.min.js"></script>
  <!-- Toastr -->
  <script src="assets/plugins/toastr/toastr.min.js"></script>
  <script type="text/javascript">
    $(function(){<?php Controller::message(); ?>});
  </script>

  <!-- Prevent Form Resubmission on Page Refresh -->
  <script type="text/javascript">
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>

  <!-- On Form Submit Preloader JS -->
  <script type="text/javascript">
    $(document).ready(function(){
      $("#register").on("submit", function(){
        $("#pageloader").fadeIn();
      });//submit
    });//document ready
  </script>

  <!-- AdminLTE App -->
  <script src="assets/js/adminlte.min.js"></script>
</body>
</html>
