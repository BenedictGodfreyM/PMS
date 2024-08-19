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
          <h1 class="m-0 text-dark">Sales Report</h1>
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
                  <h3 class="card-title p-3"><i class="fas fa-info"></i> Custom Sales Report</h3>
                  <ul id="sales-search-total" class="nav nav-pills ml-auto p-2"></ul>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <form id="search-sales" class="form-horizontal">
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
                        <th>Qty Sold</th>
                        <th>Unit Cost(Tshs)</th>
                        <th>Total Cost(Tshs)</th>
                        <th>Sold By</th>
                        <th>Date Sold</th>
                      </tr>
                    </thead>
                    <tbody id="list-sales-search-results"></tbody>
                  </table>
                </div><!-- /.card-body -->
              </div><!-- /.card -->
            </div>
          </div>
        </section>
      </div>

      <!-- Middle row -->
      <div class="row">
        <!-- Top -->
        <section class="col-lg-12 connectedSortable">
          <div class="card card-info card-outline elevation-2">
            <div class="card-header border-0">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Weekly Sales Overview</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool btn-sm" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
            </div>
            <div class="card-body">
              <div class="d-flex">
                <p class="d-flex flex-column">
                  <?php

                  $sales_status = "N/A";
                  $sales_status_results = 0;
                  $this_week_records = $data['this_week_total_sales'];
                  $last_week_records = $data['last_week_total_sales'];
                  $all_week_records = $this_week_records + $last_week_records;
                  if ($this_week_records > $last_week_records) {
                    $sales_status_results = $this_week_records - $last_week_records;
                    echo "
                    <span class='text-bold text-lg'>
                    Tsh ".$sales_status_results."/=
                    </span>
                    <span class='badge badge-success'>Increased</span>
                    ";
                  }else if($this_week_records < $last_week_records) {
                    $sales_status_results = $last_week_records - $this_week_records;
                    echo "
                    <span class='text-bold text-lg'>
                    Tsh ".$sales_status_results."/=
                    </span>
                    <span class='badge badge-danger'>Decreased</span>
                    ";
                  }else if($this_week_records = $last_week_records) {
                    echo "
                    <span class='text-bold text-lg'>
                    Tsh ".$this_week_records."/=
                    </span>
                    <span class='badge badge-primary'>Balanced</span>
                    ";
                  }else{
                    echo "N/A";
                  }

                  ?>
                </p>
                <p class="ml-auto d-flex flex-column text-right">
                  <?php

                  $percentage_sales_status = 0;
                  if ($this_week_records > $last_week_records) {
                      //Percentage Increase
                    $percentage_sales_status = ceil(($sales_status_results / $all_week_records) * 100);
                    echo "
                    <span class='text-success'>
                    <i class='fas fa-arrow-up'></i>
                    ".$percentage_sales_status."%
                    </span>
                    ";
                  }else if($this_week_records < $last_week_records) {
                      //Percentage Decrease
                    $percentage_sales_status = ceil(($sales_status_results / $all_week_records) * 100);
                    echo "
                    <span class='text-danger'>
                    <i class='fas fa-arrow-down'></i>
                    ".$percentage_sales_status."%
                    </span>
                    ";
                  }else if($this_week_records = $last_week_records) {
                    $percentage_sales_status = ceil(($sales_status_results / $all_week_records) * 100);
                    echo "
                    <span class='text-primary'>
                    <i class='fas fa-circle'></i>
                    BALANCED
                    </span>
                    ";
                  }else{
                    echo "N/A";
                  }

                  ?>
                  <span class="text-muted">Since last week</span>
                </p>
              </div>
              <!-- /.d-flex -->

              <div class="position-relative mb-4">
                <canvas id="sales-chart-weekly" height="200"></canvas>
              </div>

              <div class="d-flex flex-row justify-content-end">
                <span class="mr-2">
                  <i class="fas fa-square text-primary"></i> This Week
                  (
                    <?php

                    $default_this = strtotime('monday this week');
                    for ($i=0; $i<7; $i++) {
                      $d = strtotime('+'.$i.' Days', $default_this);
                      if ($i == 0) {echo date('D-d', $d);}
                      if ($i > 0) {if (ceil(7 / $i) == $i) {echo " to ";}}
                      if ($i == 6) {echo date('D-d', $d);}
                    }

                    ?>
                    )
                  </span>

                  <span>
                    <i class="fas fa-square text-gray"></i> Last Week
                    (
                      <?php

                      $default_last = strtotime('monday last week');
                      for ($i=0; $i<7; $i++) {
                        $d = strtotime('+'.$i.' Days', $default_last);
                        if ($i == 0) {echo date('D-d', $d);}
                        if ($i > 0) {if (ceil(7 / $i) == $i) {echo " to ";}}
                        if ($i == 6) {echo date('D-d', $d);}
                      }

                      ?>
                      )
                    </span>
                  </div>
                </div>
              </div>
              <!-- /.card -->
            </section>

            <!-- Bellow -->
            <section  class="col-lg-12 connectedSortable">
              <div class="card card-info card-outline elevation-2">
                <div class="card-header border-0">
                  <div class="d-flex justify-content-between">
                    <h3 class="card-title">Monthly Sales Overview</h3>

                    <div class="card-tools">
                      <button type="button" class="btn btn-tool btn-sm" data-card-widget="maximize">
                        <i class="fas fa-expand"></i>
                      </button>
                    </div>
                    <!-- /.card-tools -->
                  </div>
                </div>
                <div class="card-body">
                  <div class="d-flex">
                    <p class="d-flex flex-column">
                      <?php

                      $year_sales_status = "N/A";
                      $year_sales_difference = 0;
                      $this_year_records = $data['this_year_total_sales'];
                      $last_year_records = $data['last_year_total_sales'];
                      $total_years_sales = $this_year_records + $last_year_records;
                      if ($this_year_records > $last_year_records) {
                        $year_sales_difference = $this_year_records - $last_year_records;
                        echo "
                        <span class='text-bold text-lg'>
                        Tsh ".$year_sales_difference."/=
                        </span>
                        <span class='badge badge-success'>Increased</span>
                        ";
                      }else if($this_year_records < $last_year_records) {
                        $year_sales_difference = $last_year_records - $this_year_records;
                        echo "
                        <span class='text-bold text-lg'>
                        Tsh ".$year_sales_difference."/=
                        </span>
                        <span class='badge badge-danger'>Decreased</span>
                        ";
                      }else if($this_year_records = $last_year_records) {
                        echo "
                        <span class='text-bold text-lg'>
                        Tsh ".$this_year_records."/=
                        </span>
                        <span class='badge badge-primary'>Balanced</span>
                        ";
                      }else{
                        echo "N/A";
                      }

                      ?>
                    </p>
                    <p class="ml-auto d-flex flex-column text-right">
                      <?php

                      $percentage_year_sales_status = 0;
                      if ($this_year_records > $last_year_records) {
                      //Percentage Increase
                        $percentage_year_sales_status = ceil(($year_sales_difference / $total_years_sales) * 100);
                        echo "
                        <span class='text-success'>
                        <i class='fas fa-arrow-up'></i>
                        ".$percentage_year_sales_status."%
                        </span>
                        ";
                      }else if($this_year_records < $last_year_records) {
                      //Percentage Decrease
                        $percentage_year_sales_status = ceil(($year_sales_difference / $total_years_sales) * 100);
                        echo "
                        <span class='text-danger'>
                        <i class='fas fa-arrow-down'></i>
                        ".$percentage_year_sales_status."%
                        </span>
                        ";
                      }else if($this_year_records = $last_year_records) {
                        $percentage_year_sales_status = ceil(($year_sales_difference / $total_years_sales) * 100);
                        echo "
                        <span class='text-primary'>
                        <i class='fas fa-circle'></i>
                        BALANCED
                        </span>
                        ";
                      }else{
                        echo "N/A";
                      }

                      ?>
                      <span class="text-muted">Since last Year</span>
                    </p>
                  </div>
                  <!-- /.d-flex -->

                  <div class="position-relative mb-4">
                    <canvas id="sales-chart-monthly" height="200"></canvas>
                  </div>

                  <div class="d-flex flex-row justify-content-end">
                    <span class="mr-2">
                      <i class="fas fa-square text-primary"></i> This Year
                      (
                        <?php

                        $this_year = strtotime('this year');
                        echo date('Y', $this_year);

                        ?>
                        )
                      </span>

                      <span>
                        <i class="fas fa-square text-gray"></i> Last Year
                        (
                          <?php

                          $last_year = strtotime('last year');
                          echo date('Y', $last_year);

                          ?>
                          )
                        </span>
                      </div>
                    </div>
                  </div>
                  <!-- /.card -->
                </section>
              </div>

              <!-- Bottom row -->
              <div class="row">
                <div class="col-md-12">
                  <?php
                  if(isset($data['del_sales_trans_response'])){
                    if(!empty($data['del_sales_trans_response'])){echo $data['del_sales_trans_response'];}
                  }
                  ?>
                </div>
                <section class="col-lg-12 connectedSortable">
                  <!-- TABLE: LATEST SALES -->
                  <div class="card card-info card-outline elevation-2">
                    <div class="card-header">
                      <h3 class="card-title">All Transactions (Sales)</h3>
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
                          <?php if (isset($data['all_sales_transactions']) && is_array($data['all_sales_transactions'])): ?>
                            <?php  $i=1; foreach($data['all_sales_transactions'] as $row): ?>
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
                                          <a class="btn btn-outline-dark" href="?url=home/deleteSalesTransaction/<?php echo $row["sales_id"]; ?>">Confirm</a>
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

        </div>
