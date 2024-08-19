<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Today's Sales</h1>
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
      <!-- Top row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-8 connectedSortable">
          <!-- TABLE: LATEST SALES -->
          <div class="card card-info card-outline elevation-2">
            <div class="card-header">
              <h3 class="card-title" style="float: left;">Today's Sales</h3>
              <h3 class="card-title" style="float: right;"><?php echo date("D, d/m/Y"); ?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Brand Name</th>
                    <th>Generic Name</th>
                    <th>Qty Sold</th>
                    <th>Unit Cost(Tshs)</th>
                    <th>Total Cost(Tshs)</th>
                    <th>Sold By</th>
                    <th>Date Sold</th>
                    <?php if(Session::get('isAdmin') == true): ?>
                      <th>Controls</th>
                    <?php endif; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php if (isset($data['today_sales_list']) && is_array($data['today_sales_list'])): ?>
                    <?php  $i=1; foreach($data['today_sales_list'] as $row): ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $row['brand_name']; ?></td>
                      <td><?php echo $row['generic_name']; ?></td>
                      <td><?php echo $row['qty_sold']; ?></td>
                      <td><?php echo $row['unit_s_price']; ?></td>
                      <td><?php echo $row['total_amount']; ?></td>
                      <td><?php echo $row['firstname']." ".$row['lastname']; ?></td>
                      <td><?php echo $row['date_of_transaction']; ?></td>
                      <?php if(Session::get('isAdmin') == true): ?>
                        <td>
                          <a data-toggle="modal" class="btn btn-danger btn-sm" href="#modal-warning-onDelete-<?php echo $i; ?>">
                            <i class="fa fa-trash fa-xs"></i>
                          </a>
                        </td>
                      <?php endif; ?>
                    </tr>
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
                            <a class="btn btn-outline-dark" href="?url=home/deleteSales/<?php echo $row["s_id"]; ?>">Confirm</a>
                          </div>
                        </div>
                        <!-- /.modal-content -->
                      </div>
                      <!-- /.modal-dialog -->
                    </div>
                    <?php $i++;endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
          <!-- /.card -->
        </section>

        <!-- Right col -->
        <section class="col-lg-4 connectedSortable">
          <!-- Todays Report -->
          <div class="card card-info card-outline elevation-2">
            <div class="card-header">
              <h3 class="card-title" style="float: left;">Today's Sales</h3>
              <h3 class="card-title" style="float: right;">
                <?php echo date("D, d/m/Y"); ?>
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table m-0">
                  <thead>
                    <tr>
                      <th>Parameters</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tr>
                    <td><strong>Total Sales</strong></td>
                    <td>Tsh <?php echo $data['todaySales']; ?>.00/=</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Monthly Report -->
        <?php if(Session::get('isAdmin') == true): ?>
          <div class="card elevation-2">
            <div class="card-header">
              <h3 class="card-title" style="float: left;">Monthly Sales</h3>
              <h3 class="card-title" style="float: right;">
                <?php echo date("M, Y"); ?>
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table m-0">
                  <thead>
                    <tr>
                      <th>Parameters</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tr>
                    <td><strong>Total Sales</strong></td>
                    <td><?php echo "Tsh ".$data['thisMonthSales']."/="; ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        <?php endif; ?>
      </div>

    </section>
  </div>

  <!-- Bottom row -->
  <div class="row">

  </div>

</div>
</section>

</div>
