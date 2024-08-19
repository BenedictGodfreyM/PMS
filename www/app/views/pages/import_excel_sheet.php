<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- On Submit Move-To-Trash Loader -->
	<div id="pageloader">
		<img src="assets/img/ProcessingIcon.gif" alt="processing...">
	</div>
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Import Excel Sheet</h1>
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
      <div class="alert alert-info alert-dismissible elevation-2">
        <button class="close" type="button" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5>
          <i class="icon fas fa-info"></i> Info!
        </h5>
        Please Upload a valid excel file containing drugs to be added to the database with excel column(s) of each data being: A representing Brand Name, B representing Generic Name, C representing Strength of Drug(with units either mg, ml, or g), and D representing Unit Selling Price. NOTE THAT, no action will be performed if you repeate to import the same file while the same data already exists in the drug database.
      </div>
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
          <div class="card card-primary card-outline elevation-2">
            <div class="card-heading box-profile"></div>
            <div class="card-body">
              <form class="import-drugs" method="POST" enctype="multipart/form-data" action="?url=dataimport/run">
                <div class="form-group row">
                  <label for="inputPhoto" class="col-sm-12 col-form-label">
                    Select an Excel file:
                  </label>
                  <div class="col-sm-12">
                    <input type="file" accept=".xlsx, .xls, .csv" name="excel_sheet" class="form-control" id="inputTitle" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="offset-sm-1 col-sm-10">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary btn-block">Upload</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-4"></div>
      </div>

    </div>
  </section>

</div>
