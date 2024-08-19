<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a>Home</a></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-4 col-12">
          <!-- small box -->
          <div class="small-box bg-primary elevation-2">
            <div class="inner">
              <h3><?php echo $data['drugs']; ?></h3>

              <p> Total Drugs </p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <?php if ($data['drugs'] != 0): ?>
              <a href="?url=home/view_drugs" class="small-box-footer">
                View <i class="fas fa-arrow-circle-right"></i>
              </a>
            <?php endif; ?>
          </div>
        </div>
        <!-- ./col -->

        <div class="col-lg-4 col-12">
          <!-- small box -->
          <div class="small-box bg-info elevation-2">
            <div class="inner">
              <h3><?php echo "Tsh ".$data['todaySales']."/="; ?></h3>

              <p> Sales of the Day </p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <?php if ($data['todaySales'] != 0): ?>
              <a href="?url=home/sales" class="small-box-footer">
                View <i class="fas fa-arrow-circle-right"></i>
              </a>
            <?php endif; ?>
          </div>
        </div>
        <!-- ./col -->

        <?php if(Session::get('isAdmin') == true): ?>
          <div class="col-lg-4 col-12">
            <!-- small box -->
            <div class="small-box bg-info elevation-2">
              <div class="inner">
                <h3><?php echo "Tsh ".$data['thisMonthSales']."/="; ?></h3>

                <p> Sales of the Month </p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <?php if ($data['thisMonthSales'] != 0): ?>
                <a href="?url=home/sales_report" class="small-box-footer">
                  View <i class="fas fa-arrow-circle-right"></i>
                </a>
              <?php endif; ?>
            </div>
          </div>
        <?php endif ?>
        <!-- ./col -->

        <div class="col-lg-4 col-12">
          <!-- small box -->
          <div class="small-box bg-danger elevation-2">
            <div class="inner">
              <h3><?php echo $data['expiredDrugs']; ?></h3>

              <p> Expired Drugs </p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <?php if ($data['expiredDrugs'] != 0): ?>
              <a href="?url=notification/expired" class="small-box-footer">
                View <i class="fas fa-arrow-circle-right"></i>
              </a>
            <?php endif; ?>
          </div>
        </div>
        <!-- ./col -->

        <div class="col-lg-4 col-12">
          <!-- small box -->
          <div class="small-box bg-warning elevation-2">
            <div class="inner">
              <h3><?php echo $data['nearExpiry']; ?></h3>

              <p> Drugs Near Expiry (Within 6 Months Span)  </p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <?php if ($data['nearExpiry'] != 0): ?>
              <a href="?url=notification/nearExpiry" class="small-box-footer">
                View <i class="fas fa-arrow-circle-right"></i>
              </a>
            <?php endif; ?>
          </div>
        </div>
        <!-- ./col -->

        <div class="col-lg-4 col-12">
          <!-- small box -->
          <div class="small-box bg-danger elevation-2">
            <div class="inner">
              <h3><?php echo $data['outOfStock']; ?></h3>

              <p> Drug Shortages (Absolutely Zero Qty) </p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <?php if ($data['outOfStock'] != 0): ?>
              <a href="?url=notification/outOFstock" class="small-box-footer">
                View <i class="fas fa-arrow-circle-right"></i>
              </a>
            <?php endif; ?>
          </div>
        </div>
        <!-- ./col -->

        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-12 connectedSortable">
            <!-- LINE CHART -->
            <div class="card card-primary elevation-2">
              <div class="card-header">
                <h3 class="card-title"> This Week Sales Graph (Tshs) </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool btn-sm" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </section>
        </div>

      </div>
    </section>
  </div>
