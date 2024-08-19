<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- On Submit Search-Form Loader -->
	<div id="pageloader">
		<img src="assets/img/ProcessingIcon.gif" alt="processing...">
	</div>
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Expenses Report</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="?url=home">Home</a></li>
            <li class="breadcrumb-item"><a>Reports</a></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Top row -->
      <div class="row">
        <section class="col-lg-12 connectedSortable">
          <div class="row">
            <div class="col-md-12">
              <div class="card card-info card-outline elevation-2">
                <div class="card-header d-flex p-0">
                  <h3 class="card-title p-3"><i class="fas fa-info"></i> Custom Expenses Report</h3>
                  <ul id="expenses-search-total" class="nav nav-pills ml-auto p-2"></ul>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <form id="search-expenses" class="form-horizontal">
                    <div class="row">
                      <div class="col-md-5">
                        <div class="form-group">
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text">From :</span>
                            </div>
                            <input type="date" name="d1" class="form-control" id="inputFromDate" required>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group">
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text">To :</span>
                            </div>
                            <input type="date" name="d2" class="form-control" id="inputToDate" required>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <div class="col-md-12">
                            <button  class="btn btn-success" type="submit" value="submit">
                              <i class="fas fa-search text-muted"></i> Search
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div><!-- /.card-body -->
                <div class="card-body table-responsive p-0" style="max-height: 270px;">
                  <table class="table table-head-fixed text-nowrap">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th>Total Cost(Tshs)</th>
                        <th>Date of Transaction</th>
                      </tr>
                    </thead>
                    <tbody id="list-expenses-search-results"></tbody>
                  </table>
                </div><!-- /.card-body -->
              </div><!-- /.card -->
            </div>
          </div>
        </section>
      </div>

      <!-- Bottom row -->
      <div class="row">
        <section class="col-lg-12 connectedSortable">

          <div class="card card-info card-outline elevation-2">
            <div class="card-header">
              <h3 class="card-title">All Transactions (Expenses)</h3>
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
                  <?php if (isset($data['all_expenses_transactions']) && is_array($data['all_expenses_transactions'])): ?>
                    <?php  $i=1; foreach($data['all_expenses_transactions'] as $row): ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $row['description']; ?></td>
                      <td><?php echo $row['total_cost']; ?></td>
                      <td><?php echo $row['date_of_transaction']; ?></td>
                      <td>
                        <?php if(Session::get('isAdmin') == true): ?>
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
                                  <a class="btn btn-outline-dark" href="?url=home/deleteExpensesTransaction/<?php echo $row["expense_id"]; ?>">Confirm</a>
                                </div>
                              </div>
                              <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                          </div>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <?php $i++;endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
          <!-- /.card -->
        </section>
      </div>
    </div>
  </section>

</div>
