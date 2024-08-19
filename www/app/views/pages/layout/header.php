<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Pharmacy SYS &brvbar; <?php echo $page_title; ?></title>
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
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- On Form Submit Preloader CSS -->
  <link rel="stylesheet" href="assets/css/onSubmitPreloader.css">
  <!-- On LD-Btn-Click loader CSS -->
  <link rel="stylesheet" type="text/css" href="assets/css/ldbtn.min.css">

  <?php if(isset($data['page_include'])): ?>
    <?php if ($data['page_include'] == 'login'): ?>
      <!-- Splash Screen Css -->
      <link rel="stylesheet" href="assets/css/splash-screen.css">
    <?php endif; ?>

    <?php if ($data['page_include'] == 'add_stock' || $data['page_include'] == 'pos'): ?>
      <!-- Select2 -->
      <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
    <?php endif; ?>

    <?php if ($data['page_include'] !== '500' && $data['page_include'] !== '404' && $data['page_include'] !== 'login'): ?>
      <!-- DataTables -->
      <link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
      <link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
      <!-- overlayScrollbars -->
      <link rel="stylesheet" href="assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <?php endif; ?>
  <?php endif; ?>

  <!-- Theme style -->
  <link rel="stylesheet" href="assets/css/adminlte.min.css">
</head>
