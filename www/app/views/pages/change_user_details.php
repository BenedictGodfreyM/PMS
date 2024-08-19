<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">User Details</h1>
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
              <h3 class="card-title">Change Account Details</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <form method="POST" enctype="application/x-www-form-urlencoded" action="?url=home/alterUserDetails" class="form-horizontal">
                <input type="hidden" name="id" value="<?php echo Session::get('id'); ?>" required>
                <div class="form-group row">
                  <label for="inputUsername" class="col-sm-2 col-form-label">
                    Username:
                  </label>
                  <div class="col-sm-10">
                    <input type="text" name="username" value="<?php echo Session::get('username'); ?>" class="form-control" id="inputUsername" autocomplete="off" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputFirstname" class="col-sm-2 col-form-label">
                    Firstname:
                  </label>
                  <div class="col-sm-10">
                    <input type="text" name="firstname" value="<?php echo Session::get('firstname'); ?>" class="form-control" id="inputFirstname" autocomplete="off" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputLastname" class="col-sm-2 col-form-label">
                    Lastname:
                  </label>
                  <div class="col-sm-10">
                    <input type="text" name="lastname" value="<?php echo Session::get('lastname'); ?>" class="form-control" id="inputLastname" autocomplete="off" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail" class="col-sm-2 col-form-label">
                    E-Mail:
                  </label>
                  <div class="col-sm-10">
                    <input type="email" name="email" value="<?php echo Session::get('email'); ?>" class="form-control" id="inputEmail" placeholder="<?php echo (empty(Session::get('email')) ? 'Optional' : Session::get('email')); ?>" autocomplete="off">
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
