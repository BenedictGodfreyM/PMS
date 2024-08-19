<?php

class Error404 extends Controller {

	public function __construct(){}

	public function index(){

		$error = 'User Request is not recognised by the System';

		parent::view('error', '404 Error', [
			'page_include' => '404',
			'error' => $error
		], 0);

	}

}

?>
