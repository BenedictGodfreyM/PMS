<div class="lockscreen-logo"> 
  <a>
    <h1 style="font-weight: 3000;font-size: 100px;" class="headline text-warning">404</h1>
  </a>
</div>

<div class="help-block text-center">
  <h3>
    <i class="fas fa-exclamation-triangle text-warning"></i> 
    Sorry! Unrecognised Request.
  </h3>
</div>
<div class="text-center">
  <p>
    <div class="alert alert-warning">
      <?php if (isset($data['error'])) {echo $data['error'];} ?>
    </div>
  </p>
</div>