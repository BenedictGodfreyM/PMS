<div class="lockscreen-logo"> 
  <a>
    <h1 style="font-weight: 3000;font-size: 100px;" class="headline text-danger">500</h1>
  </a>
</div>

<div class="help-block text-center">
  <h3>
    <i class='fas fa-exclamation-triangle text-danger'></i> 
    Oops! Something went wrong.
  </h3>
</div>
<div class="text-center">
  <p>
    <div class='alert alert-danger'>
      <?php if (isset($data['error'])) {echo $data['error'];} ?>
      <?php if (isset($_GET['error_msg'])) {echo ": \"".$_GET['error_msg']."\"";} ?>
    </div>
  </p>
</div>