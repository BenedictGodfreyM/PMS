
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User Accounts</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="?url=home">Home</a></li>
              <li class="breadcrumb-item active">Settings</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="card card-info card-outline elevation-2">
        <div class="card-header">
          <h3 class="card-title">Registered User Accounts</h3>
        </div>
        <div class="card-body p-0">
          <table class="table table-striped projects">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>E-Mail</th>
                      <th>Status</th>
                      <th>Controls</th>
                  </tr>
              </thead>
              <tbody>
                <?php if (isset($data['list_accounts']) && is_array($data['list_accounts'])): ?>
                  <?php  $i=1; foreach ($data['list_accounts'] as $row): ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row['firstname']; ?></td>
                        <td><?php echo $row['lastname']; ?></td>
                        <td><?php echo (empty($row['email']) ? 'Not Available' : $row['email']); ?></td>
                        <td>
                          <a>Employee</a><br/>
                          <small>Created <?php echo $row['date_created']; ?></small>
                        </td>
                        <td>
                            <a class="btn btn-primary btn-sm ld-ext-right" onclick="this.classList.toggle('running')" href="?url=home/resetUserAccountPass/<?php echo $row['user_id']; ?>" title="Reset the Default Password">
                                <i class="fas fa-key"></i> <div class="ld spinner-border spinner-border-sm md-5"></div>
                            </a>
                            &nbsp;
                            <a class="btn btn-danger btn-sm ld-ext-right" onclick="this.classList.toggle('running')" href="?url=home/deleteUserAccount/<?php echo $row['user_id']; ?>" title="Delete User Account">
                                <i class="fas fa-trash"></i> <div class="ld spinner-border spinner-border-sm md-5"></div>
                            </a>
                        </td>
                    </tr>
                  <?php $i++;endforeach; ?>
                <?php endif; ?>
              </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
