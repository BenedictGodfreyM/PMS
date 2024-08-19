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
          <h1 class="m-0 text-dark">Profit-Loss Report</h1>
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
                  <h3 class="card-title p-3"><i class="fas fa-info"></i> Custom Profit-Loss Report</h3>
                  <ul id="netprofit-search-total" class="nav nav-pills ml-auto p-2"></ul>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <form id="search-netprofit" class="form-horizontal">
                    <div class="row">
                      <div class="col-md-5">
                        <div class="form-group">
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text">From :</span>
                            </div>
                            <input type="month" name="d1" class="form-control" id="inputFromDate" required>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group">
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text">To :</span>
                            </div>
                            <input type="month" name="d2" class="form-control" id="inputToDate" required>
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
                        <th>Purchases(Tshs)</th>
                        <th>Sales(Tshs)</th>
                        <th>Gloss Profit(Tshs)</th>
                        <th>Expenses(Tshs)</th>
                        <th>Losses Due to Expiry(Tshs)</th>
                        <th>Net Profit(Tshs)</th>
                      </tr>
                    </thead>
                    <tbody id="list-netprofit-search-results"></tbody>
                  </table>
                </div><!-- /.card-body -->
              </div><!-- /.card -->
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>
