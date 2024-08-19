<?php

class Error500 extends Controller {

	public function __construct(){}

	public function index(){

		$error = 'Internal Server or Database Configuration Error';

		parent::view('error', '500 Error', [
			'page_include' => '500',
			'error' => $error
		], 0);

	}

	public function database($error = []){

		$error = 'Database Configuration Error: '.$error['exception'].'.';

		parent::view('error', '500 Error', [
			'page_include' => '500',
			'error' => $error
		], 0);
		exit();

	}

	public function databreach($description = []){

		$error = 'Database Corrupted: '.$description['statement'];

		parent::view('error', '500 Error', [
			'page_include' => '500',
			'error' => $error
		], 0);
		exit();

	}

	public function unloaded_extension($extension = []) {

		$error = 'Internal Server Configuration Error: '.$extension['name'].' extension was not loaded. It may be deactivated or not available.';

		parent::view('error', '500 Error', [
			'page_include' => '500',
			'error' => $error
		], 0);
		exit();

	}

}

?>
