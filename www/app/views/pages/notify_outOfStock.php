<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Notifications</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="?url=home">Home</a></li>
            <li class="breadcrumb-item"><a>Notifications</a></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="col-md-12">
        <div class="card card-danger card-outline">
          <div class="card-header">
            <h3 class="card-title">
              <?php

              if (isset($data['header'])) {
                echo $data['header'];
              }

              ?>
            </h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Brand Name</th>
                  <th>Genetic Name</th>
                  <th>Last Added on</th>
                  <th>Available Qty</th>
                  <th>Controls</th>
                </tr>
              </thead>
              <tbody>
                <?php if (isset($data['notification_contents']) && is_array($data['notification_contents'])): ?>
                  <?php  $i=1; foreach($data['notification_contents'] as $row): ?>
                  <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo (empty($row['brand_name']) ? 'Not Available' : ucfirst($row['brand_name'])); ?></td>
                    <td><?php echo (empty($row['generic_name']) ? 'Not Available' : ucfirst($row['generic_name'])); ?></td>
                    <td><?php echo $row['date_received']; ?></td>
                    <td><?php echo $row['qty']; ?></td>
                    <td>
                      <form method="POST" 
                            enctype="application/x-www-form-urlencoded" 
                            action="?url=home/add_stock/run">
                        <input type="hidden" name="multiple_drug_selector[]" value="<?php echo $row['d_id']; ?>" required>
                        <button class="btn btn-info btn-sm" type="submit">
                          <i class='fa fa-file-import fa-sm'> Add Stock</i>
                        </button>
                      </form>
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
      </div>
      <!-- /.col -->
    </div>
  </section>
</div>