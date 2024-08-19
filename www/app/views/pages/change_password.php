<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Change Password</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="?url=home">Home</a></li>
            <li class="breadcrumb-item"><a>Settings</a></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-3">
          <!-- Profile Image -->
            <div class="card card-primary card-outline elevation-2">
              <div class="card-body box-profile">
                <div class="text-center"></div>

                <h3 class="profile-username text-center">
                  <?php echo ucfirst(Session::get('firstname'))."&nbsp;".ucfirst(Session::get('lastname')); ?>
                </h3>

                <p class="text-muted text-center">
                  <?php echo (Session::get('isAdmin') == false) ? 'USER' : 'ADMINISTRATOR'; ?>
                </p>

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->

        <div class="col-md-9">
          <div class="card card-info card-outline elevation-2">
            <div class="card-header">
              <h3 class="card-title">Change Account Password</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <form method="POST" enctype="application/x-www-form-urlencoded" action="?url=home/alterPassword" class="form-horizontal">
                <input type="hidden" name="id" value="<?php echo Session::get('id'); ?>" required>
                <div class="form-group row">
                  <label for="inputOldPass" class="col-sm-2 col-form-label">
                    Old Password:
                  </label>
                  <div class="col-sm-10">
                    <input type="password" name="old_pass" class="form-control" id="inputOldPass" autocomplete="off" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputNewPass" class="col-sm-2 col-form-label">
                    New Password:
                  </label>
                  <div class="col-sm-10">
                    <input type="password" name="new_pass" class="form-control" id="inputNewPass" autocomplete="off" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputConfirmPass" class="col-sm-2 col-form-label">
                    Confirm Pass
                  </label>
                  <div class="col-sm-10">
                    <input type="password" name="confirm_pass" class="form-control" id="inputConfirmPass" autocomplete="off" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="offset-sm-2 col-sm-10">
                    <button type="submit" class="btn btn-success ld-ext-right" onclick="this.classList.toggle('running')">
                      Update <div class="ld spinner-border spinner-border-sm md-5"></div>
                    </button>
                  </div>
                </div>
              </form>
            </div><!-- /.card-body -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>

    </div>
  </section>

</div>
