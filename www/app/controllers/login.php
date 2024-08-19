<?php

class Login extends Controller {

	public function __construct(){

		//Check if an administrator info is present in the database
		//if not, redirect to administrator setup wizard
		$admin_registered = parent::model('AlphaModel')->is_admin_present();
		if($admin_registered == false) parent::redirect("?url=alpha");

	}

	public function index(){

		parent::view('auth', 'Log-In', [
			'page_include' => 'login',
			'message' => ''
		], 0);

	}

	public function run(){

		//Create A Log IN Object
		$loginObj = parent::model('LoginModel');

		//Load the LOGIN View
		parent::view('auth', 'Log-In', [
			'page_include' => 'login',
			'error_message' => $loginObj->feedback
		], 0);

	}

}

?>
