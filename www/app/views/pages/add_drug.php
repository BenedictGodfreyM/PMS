<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- On Submit Form Loader -->
	<div id="pageloader">
		<img src="assets/img/ProcessingIcon.gif" alt="processing...">
	</div>
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Add Drug</h1>
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
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-4">
				<div class="card card-primary card-outline elevation-2">
					<div class="card-header">
						<h3 class="card-title">General Details</h3>
					</div>
<form class="add-drug" method="POST" enctype="application/x-www-form-urlencoded" action="?url=home/add_drug">
						<div class="card-body">
							<div class="form-group">
								<label for="inputBrandName">Brand Name</label>
								<input type="text" id="inputBrandName" name="brand" class="form-control" required>
							</div>
							<div class="form-group">
								<label for="inputGeneticName">Genetic Name</label>
								<input type="text" id="inputGeneticName" name="name" class="form-control" required>
							</div>
			                <div class="form-group">
			                  <label for="inputStrength">Strength (mg/ml/g)</label>
			                  <input type="text" id="inputStrength" name="strength" class="form-control" required>
			                </div>
			                <div class="form-group">
			                  <label for="inputPrice">Unit Price (in Tsh)</label>
			                  <input type="number" id="inputPrice" name="price" class="form-control" required>
			                </div>
						</div>
						<!-- /.card-body -->
			              <div class="card-footer">
			                <button type="submit" value="submit" class="btn btn-success btn-block btn-round">
			                  Add
			                </button>
			              </div>
			              <!-- /.card-footer -->
					</div>
					<!-- /.card -->
				</div>
			</div>
</form>
		</div>
	</div>
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
