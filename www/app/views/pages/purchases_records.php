<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- On Submit Search-Form Loader -->
	<div id="pageloader">
		<img src="assets/img/ProcessingIcon.gif" alt="processing...">
	</div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Stock Records</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="?url=home">Home</a></li>
            <li class="breadcrumb-item active">Stock Management</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- OVERVIEW -->
      <h5 class="mb-2 py-2">Quick Overview</h5>
      <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
          <div class="info-box elevation-2">
            <span class="info-box-icon bg-info"><i class="fa fa-cart-plus fa-xs"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Purchases</span>
              <span class="info-box-number">Tshs <?php echo (!empty($data['all_purchases_total_amount']) ? $data['all_purchases_total_amount'] : '0'); ?>/=</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
          <div class="info-box elevation-2">
            <span class="info-box-icon bg-info"><i class="fa fa-cart-plus fa-xs"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">This Month(Purchases)</span>
              <span class="info-box-number">Tshs <?php echo (!empty($data['this_month_purchases_amount']) ? $data['this_month_purchases_amount'] : '0'); ?>/=</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
          <div class="info-box elevation-2">
            <span class="info-box-icon bg-danger"><i class="fa fa-trash fa-xs"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Loses(Trashed)</span>
              <span class="info-box-number">Tshs <?php echo (!empty($data['total_loses_amount']) ? $data['total_loses_amount'] : '0'); ?>/=</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
          <div class="info-box elevation-2">
            <span class="info-box-icon bg-danger"><i class="fa fa-trash fa-xs"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">This Month(Trashed)</span>
              <span class="info-box-number">Tshs <?php echo (!empty($data['this_month_loses_amount']) ? $data['this_month_loses_amount'] : '0'); ?>/=</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- STOCK-RECORDS UPDATE'S NOTIFICATIONS -->
      <div class="row">
        <div class="col-md-12">
          <div class="card card-warning card-outline elevation-2">
            <div class="card-header">
              <h3 class="card-title"><i class="fa fa-info"></i> Notifications(Stock Updates)</h3>
            </div>
            <!-- /.card-header -->
            <div id="notification-body" class="card-body table-responsive p-0" style="max-height: 270px;">
              <h4 id="pre-notification-text" class="text-center py-2">There are no recent Notifications</h4>
              <h4 id="post-notification-text" class="text-center py-2" style="display: none;">Error Loading Contents!!!.</h4>
            </div>
          </div>
        </div>
      </div>

      <!-- TRASHED STOCK -->
      <div class="row">
        <div class="col-md-12">
          <div class="card card-danger card-outline elevation-2">
            <div class="card-header">
              <h3 class="card-title">Trash</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header d-flex p-0">
                      <h3 class="card-title p-3"><i class="fas fa-info"></i> Search Trashed Stock(Expired)</h3>
                      <ul id="trash-search-total" class="nav nav-pills ml-auto p-2"></ul>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <form id="search-trash" class="form-horizontal">
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
                            <th>Brand Name</th>
                            <th>Generic Name</th>
                            <th>Date Trashed</th>
                            <th>Expiry Date</th>
                            <th>Qty Expired</th>
                            <th>Unit Loss(Tshs)</th>
                            <th>Total Loss(Tsh)</th>
                          </tr>
                        </thead>
                        <tbody id="list-trash-search-results"></tbody>
                      </table>
                    </div><!-- /.card-body -->
                  </div><!-- /.card -->
                </div>
              </div>

              <div id="accordion">
                <!-- we are adding the .class so bootstrap.js collapse plugin detects it -->
                <div class="card card-danger">
                  <div class="card-header">
                    <h4 class="card-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" onclick="">
                        All Items in Trash(Expired Items)
                      </a>
                    </h4>
                  </div>
                  <div id="collapseThree" class="panel-collapse collapse">
                    <div class="card-body">
                      <table id="example2" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Brand Name</th>
                            <th>Generic Name</th>
                            <th>Date Trashed</th>
                            <th>Expiry Date</th>
                            <th>Strength</th>
                            <th>Qty Expired</th>
                            <th>Unit Loss(Tshs)</th>
                            <th>Total Loss(Tsh)</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($data['list_items_in_trash']) && is_array($data['list_items_in_trash'])): ?>
                            <?php  $i=1; foreach ($data['list_items_in_trash'] as $row): ?>
                            <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo (empty($row['brand_name']) ? 'Not Available' : ucfirst($row['brand_name'])); ?></td>
                              <td><?php echo (empty($row['generic_name']) ? 'Not Available' : ucfirst($row['generic_name'])); ?></td>
                              <td><?php echo $row['date_trashed']; ?></td>
                              <td><?php echo $row['date_of_end_of_use']; ?></td>
                              <td><?php echo $row['strength']; ?></td>
                              <td><?php echo $row['qty_trashed']; ?></td>
                              <td><?php echo $row['unit_loss']; ?></td>
                              <td><?php echo $row['total_loss']; ?></td>
                            </tr>
                            <?php $i++;endforeach; ?>
                          <?php endif; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>

      <!-- PURCHASES CUSTOM SEARCH -->
      <div class="row">
        <div class="col-md-12">
          <div class="card card-info card-outline elevation-2">
            <div class="card-header">
              <h3 class="card-title">Purchases</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header d-flex p-0">
                      <h3 class="card-title p-3"><i class="fas fa-info"></i> Search Purchases</h3>
                      <ul id="purchases-search-total" class="nav nav-pills ml-auto p-2"></ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                      <form id="search-purchases" class="form-horizontal">
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
                            <th>Brand Name</th>
                            <th>Generic Name</th>
                            <th>Date Received</th>
                            <th>Expiry Date</th>
                            <th>Strength</th>
                            <th>Qty Bought</th>
                            <th>Unit Cost(Tshs)</th>
                            <th>Total Cost(Tsh)</th>
                          </tr>
                        </thead>
                        <tbody id="list-purchases-search-results"></tbody>
                      </table>
                    </div><!-- /.card-body -->
                  </div><!-- /.card -->
                </div>
              </div>
            </div><!-- /.card-body -->
          </div><!-- /.card -->
        </div>
      </div>

      <!-- MONTHLY PURCHASES -->
      <div class="row">
        <section class="col-lg-12 connectedSortable">
          <!-- TABLE: PURCHASES -->
          <div class="card card-info card-outline elevation-2">
            <div class="card-header">
              <h3 class="card-title">This Month Purchases</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example4" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Brand Name</th>
                    <th>Generic Name</th>
                    <th>Date Received</th>
                    <th>Expiry Date</th>
                    <th>Strength</th>
                    <th>Qty Bought</th>
                    <th>Unit Cost(Tshs)</th>
                    <th>Total Cost(Tsh)</th>
                    <?php if(Session::get('isAdmin') == true): ?>
                      <th>Controls</th>
                    <?php endif; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php if (isset($data['this_month_purchases']) && is_array($data['this_month_purchases'])): ?>
                    <?php  $i=1; foreach ($data['this_month_purchases'] as $row): ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo (empty($row['brand_name']) ? 'Not Available' : ucfirst($row['brand_name'])); ?></td>
                      <td><?php echo (empty($row['generic_name']) ? 'Not Available' : ucfirst($row['generic_name'])); ?></td>
                      <td><?php echo $row['date_received']; ?></td>
                      <td><?php echo $row['expiry_date']; ?></td>
                      <td><?php echo $row['strength']; ?></td>
                      <td><?php echo $row['qty_bought']; ?></td>
                      <td><?php echo $row['unit_b_price']; ?></td>
                      <td><?php echo $row['total_cost']; ?></td>
                      <?php if(Session::get('isAdmin') == true): ?>
                      <td>
                        <a class="btn btn-info btn-sm" href="?url=home/edit_stock_record/<?php echo $row['batch_id']; ?>" title="Edit Stock Details">
                          <i class="fa fa-pen fa-xs"></i>
                        </a>
                        &nbsp;
                        <a data-toggle="modal" class="btn btn-danger btn-sm" href="#modal-warning-onDelete-<?php echo $i; ?>" title="Delete Stock Record">
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
                                <a class="btn btn-outline-dark" href="?url=home/delete_stock_record/<?php echo $row["batch_id"]; ?>">Confirm</a>
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
          </div>
          <!-- /.card -->
        </section>
      </div>

      <!-- ALL PURCHASES -->
      <div class="row">
        <section class="col-lg-12 connectedSortable">
          <!-- TABLE: PURCHASES -->
          <div class="card card-info card-outline elevation-2">
            <div class="card-header">
              <h3 class="card-title">All Purchases</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example5" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Brand Name</th>
                    <th>Generic Name</th>
                    <th>Date Received</th>
                    <th>Expiry Date</th>
                    <th>Strength</th>
                    <th>Qty Bought</th>
                    <th>Unit Cost(Tshs)</th>
                    <th>Total Cost(Tsh)</th>
                    <?php if(Session::get('isAdmin') == true): ?>
                      <th>Controls</th>
                    <?php endif; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php if (isset($data['list_all_purchases']) && is_array($data['list_all_purchases'])): ?>
                    <?php  $i=1; foreach ($data['list_all_purchases'] as $row): ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo (empty($row['brand_name']) ? 'Not Available' : ucfirst($row['brand_name'])); ?></td>
                      <td><?php echo (empty($row['generic_name']) ? 'Not Available' : ucfirst($row['generic_name'])); ?></td>
                      <td><?php echo $row['date_received']; ?></td>
                      <td><?php echo $row['expiry_date']; ?></td>
                      <td><?php echo $row['strength']; ?></td>
                      <td><?php echo $row['qty_bought']; ?></td>
                      <td><?php echo $row['unit_b_price']; ?></td>
                      <td><?php echo $row['total_cost']; ?></td>
                      <?php if(Session::get('isAdmin') == true): ?>
                      <td>
                        <a class="btn btn-info btn-sm" href="?url=home/edit_stock_record/<?php echo $row['batch_id']; ?>" title="Edit Stock Details">
                          <i class="fa fa-pen fa-xs"></i>
                        </a>
                        &nbsp;
                        <a data-toggle="modal" class="btn btn-danger btn-sm" href="#modal-warning-onDelete-<?php echo $i; ?>" title="Delete Stock Record">
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
                                <a class="btn btn-outline-dark" href="?url=home/delete_stock_record/<?php echo $row["batch_id"]; ?>">Confirm</a>
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
          </div>
          <!-- /.card -->
        </section>
      </div>
    </div>
  </section>

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
</div>
