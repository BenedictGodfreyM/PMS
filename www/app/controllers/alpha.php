<?php

class Alpha extends Controller {

	public function __construct(){

		//Check if an administrator info is present in the database
		//if present, redirect to the dashboard
		$admin_registered = parent::model('AlphaModel')->is_admin_present();
		if($admin_registered == true) parent::redirect("?url=home");

	}

	public function index(){

		parent::view('auth/alpha', 'Administrator Setup Wizard!', ['page_include' => ''], 101);

	}

	public function setSystemAdministrator(){

		if(parent::model('AlphaModel')->register_admin()){
			parent::welcome();
		}else{
			self::index();
		}

	}

}

?>
