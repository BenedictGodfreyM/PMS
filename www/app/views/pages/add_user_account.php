<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Add New User</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="?url=home">Home</a></li>
						<li class="breadcrumb-item"><a>Settings</a></li>
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
				<div class="card card-primary elevation-2">
					<div class="card-header">
						<h3 class="card-title">User Details</h3>
					</div>
<form class="add-drug" method="POST" enctype="application/x-www-form-urlencoded" action="?url=home/addUserAccount">
						<div class="card-body">
              <div class="form-group">
                <label for="inputFirstName">First Name</label>
                <input type="text" id="inputFirstName" name="firstname" class="form-control" autocomplete="off" required>
              </div>
              <div class="form-group">
                <label for="inputLastName">Last Name</label>
                <input type="text" id="inputLastName" name="lastname" class="form-control" autocomplete="off" required>
              </div>
              <div class="form-group">
                <label for="inputEmail">E-Mail</label>
                <input type="email" id="inputEmail" name="email" class="form-control" autocomplete="off" placeholder="Optional">
              </div>
						</div><!-- /.card-body -->
            <div class="card-footer text-center">
              <button type="submit" value="submit" class="btn btn-success btn-block btn-round ld-ext-right" onclick="this.classList.toggle('running')">
                Add User Account <div class="ld spinner-border spinner-border-sm md-5"></div>
              </button>
            </div><!-- /.card-footer -->
					</div><!-- /.card -->
				</div>
			</div>
</form>
		</div>
		<div class="col-md-4"></div>
	</div>
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
