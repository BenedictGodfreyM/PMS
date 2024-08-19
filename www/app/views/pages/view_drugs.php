<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Available Drugs</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="?url=home">Home</a></li>
            <li class="breadcrumb-item"><a>Drugs</a></li>
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
        <div class="card card-info card-outline elevation-2">
          <div class="card-header py-3">
            <h3 class="card-title"> List of Available Drugs in Store</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Brand Name</th>
                  <th>Generic Name</th>
                  <th>Strength</th>
                  <th>Unit-Price (Tsh)</th>
                  <th>Quantity</th>
                  <th>Updated on</th>
                  <?php if(Session::get('isAdmin') == true): ?>
                    <th>Controls</th>
                  <?php endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php if (isset($data['list_of_drugs']) && is_array($data['list_of_drugs'])): ?>
                  <?php  $i=1; foreach ($data['list_of_drugs'] as $row): ?>
                  <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo ucfirst($row['brand_name']); ?></td>
                    <td><?php echo ucfirst($row['generic_name']); ?></td>
                    <td><?php echo $row['strength']; ?></td>
                    <td><?php echo $row['unit_s_price']; ?></td>
                    <td><?php echo $row['qty']; ?></td>
                    <td><?php echo $row['updated_on']; ?></td>
                    <?php if(Session::get('isAdmin') == true): ?>
                      <td>
                        <a class="btn btn-info btn-sm" href="?url=home/edit_drug/<?php echo $row['d_id']; ?>" title="Edit Details">
                          <i class="fa fa-pen fa-xs"></i>
                        </a>
                        &nbsp;
                        <a data-toggle="modal" class="btn btn-danger btn-sm" href="#modal-warning-onDelete-<?php echo $i; ?>" title="Delete Drug">
                          <i class="fa fa-trash fa-xs"></i>
                        </a>
                        <div class="modal fade" id="modal-warning-onDelete-<?php echo $i; ?>">
                          <div class="modal-dialog">
                            <div class="modal-content bg-warning">
                              <div class="modal-header">
                                <h4 class="modal-title">Warning!!</h4>
                              </div>
                              <div class="modal-body">
                                <p>Are You Sure&hellip;</p>
                              </div>
                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
                                <a class="btn btn-outline-dark" href="?url=home/delete_drug/<?php echo $row["d_id"]; ?>">Confirm</a>
                              </div>
                            </div>
                            <!-- /.modal-content -->
                          </div>
                          <!-- /.modal-dialog -->
                        </div>
                      </td>
                    <?php endif; ?>
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
