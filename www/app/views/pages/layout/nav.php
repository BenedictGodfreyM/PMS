<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light elevation-2" style="border-top: 1px solid #999;">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="?url=home" class="nav-link <?php if($data['page_include'] == "dashboard"){echo "active";} ?>">Home</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="?url=home/runLogOut" class="nav-link ld-ext-right" onclick="this.classList.toggle('running')">
        Log Out <div class="ld spinner-border spinner-border-sm md-5"></div>
      </a>
    </li>
  </ul>

  <!-- Right navbar link -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <h5>
        <!-- Element for Displaying Date and Time -->
        <span id="CLOCK" style="font-weight: 300;">00:00:00 Hrs</span>
      </h5>
    </li>
  </ul>
</nav>
<!-- /.navbar -->
