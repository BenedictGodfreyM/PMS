<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Expenses</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="?url=home">Home</a></li>
            <li class="breadcrumb-item"><a>POS</a></li>
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
        <div class="col-md-8">
          <div class="card card-info card-outline elevation-2">
            <div class="card-header">
              <h3 class="card-title">List of Expenses made This Month</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>Total Cost(Tshs)</th>
                    <th>Date of Transaction</th>
                    <?php if(Session::get('isAdmin') == true): ?>
                      <th>Controls</th>
                    <?php endif; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php if (isset($data['list_of_expenses']) && is_array($data['list_of_expenses'])): ?>
                    <?php  $i=1; foreach($data['list_of_expenses'] as $row): ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $row['description']; ?></td>
                      <td><?php echo $row['total_cost']; ?></td>
                      <td><?php echo $row['date_of_transaction']; ?></td>
                      <?php if(Session::get('isAdmin') == true): ?>
                        <td>
                          <a class="btn btn-info btn-sm" href="?url=home/edit_expense/<?php echo $row['expense_id']; ?>" title="Edit Details">
                            <i class="fa fa-pen fa-xs"></i>
                          </a>
                          &nbsp;
                          <a data-toggle="modal" class="btn btn-danger btn-sm" href="#modal-warning-onDelete-<?php echo $i; ?>">
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
                                  <a class="btn btn-outline-dark" href="?url=home/delete_expense/<?php echo $row["expense_id"]; ?>">Confirm</a>
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
        <div class="col-md-4">
          <?php if (isset($data['expense_record_of_edit']) && is_array($data['expense_record_of_edit'])): ?>
            <?php  $i=1; foreach($data['expense_record_of_edit'] as $row): ?>
            <div class="card card-info card-outline elevation-2">
              <div class="card-header">
                <h3 class="card-title">Edit Expense Record</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="POST" enctype="application/x-www-form-urlencoded" action="?url=home/edit_expense" class="form-horizontal">
                  <input type="hidden" name="edit_id" value="<?php echo $row['expense_id']; ?>" class="form-control" id="inputTitle" required>
                  <div class="form-group">
                    <label for="inputDescription" class="col-sm-6 col-form-label">
                      Description:
                    </label>
                    <div class="col-sm-10">
                      <textarea class="form-control" name="description" id="inputDescription"><?php echo $row['description']; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputCost" class="col-sm-6 col-form-label">
                      Total Cost Used: (Tsh):
                    </label>
                    <div class="col-sm-10">
                      <input type="number" name="cost" value="<?php echo $row['total_cost']; ?>" class="form-control" id="inputCost" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputCost" class="col-sm-6 col-form-label">
                      Date of Transaction:
                    </label>
                    <div class="col-sm-10">
                      <input type="date" name="transaction_date" value="<?php echo $row['date_of_transaction']; ?>" class="form-control" id="inputCost" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                      <button type="submit" class="btn btn-info ld-ext-right" onclick="this.classList.toggle('running')">
                        Update Record <div class="ld spinner-border spinner-border-sm md-5"></div>
                      </button>
                    </div>
                  </div>
                </form>
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
          <?php $i++;endforeach; ?>
          <?php else: ?>
            <div class="card card-info card-outline elevation-2">
              <div class="card-header">
                <h3 class="card-title">Add Expense Record</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="POST" enctype="application/x-www-form-urlencoded" action="?url=home/add_expense" class="form-horizontal">
                  <div class="form-group">
                    <label for="inputDescription" class="col-sm-6 col-form-label">
                      Description:
                    </label>
                    <div class="col-sm-10">
                      <textarea class="form-control" name="description" id="inputDescription"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputCost" class="col-sm-6 col-form-label">
                      Total Cost Used (Tsh):
                    </label>
                    <div class="col-sm-10">
                      <input type="number" name="cost" class="form-control" id="inputCost" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputCost" class="col-sm-6 col-form-label">
                      Date of Transaction:
                    </label>
                    <div class="col-sm-10">
                      <input type="date" name="transaction_date" class="form-control" id="inputCost" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                      <button type="submit" class="btn btn-success ld-ext-right" onclick="this.classList.toggle('running')">
                        Add Record <div class="ld spinner-border spinner-border-sm md-5"></div>
                      </button>
                    </div>
                  </div>
                </form>
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

</div>
