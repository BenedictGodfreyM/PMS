<div id="animate-splash" class="login-box d-none">
	<div class="login-logo">
		<a><b><h1>PHARMACY </h1></b><h3> MANAGEMENT SYSTEM</h3></a>
	</div><!-- /.login-logo -->
	<?php if(isset($data['error_message'])): ?>
		<div class="alert alert-danger">
			<p class="text-center"><?php echo $data['error_message']; ?></p>
		</div>
		<br />
	<?php endif ?>
	<div class="login-box-body">
		<p class="login-box-msg">Login In To Dashboard</p>
		<form id="login-form" action="?url=login/run" method="POST" enctype="application/x-www-form-urlencoded">
			<div class="form-group has-feedback">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fa fa-user fa-sm"></i></span>
					</div>
					<input type="text" id="inputForUsername" class="form-control" placeholder="Username" name="username" autocomplete="off">
				</div>
			</div>
			<div class="form-group has-feedback">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fa fa-key fa-sm"></i></span>
					</div>
					<input type="password" id="inputForPassword" class="form-control" placeholder="Password" name="password" autocomplete="off">
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">

				</div><!-- /.col -->
				<div class="col-md-4">
					<button type="submit" value="submit" class="btn btn-primary btn-block btn-round ld-ext-right" onclick="this.classList.toggle('running')" name="form_login">
						Sign In <div class="ld spinner-border spinner-border-sm md-5"></div>
					</button>
				</div><!-- /.col -->
				<div class="col-md-4">

				</div><!-- /.col -->
			</div>
		</form>


	</div><!-- /.login-box-body -->
	</div><!-- /.login-box -->
