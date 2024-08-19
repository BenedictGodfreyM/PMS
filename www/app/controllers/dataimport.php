<?php

class Dataimport extends Controller {

	public function __construct(){
		parent::authenticate();//Check if User is Logged In First ???
	}

	/*--- Load the Data Import View ---*/
	public function index(){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			parent::view('index', 'Drug Module', [
				'page_include' => 'import_excel_sheet'
			], 0);
		}
		else
		{
			parent::logout();
		}

	}

	/*--- Import EXCEL-Sheet(List of Drugs) by Default ---*/
	public function run(){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			//Import Excel Sheet
			parent::model('DatatableImportsModel')->importEXCEL();

			parent::view('index', 'Drug Module', [
				'page_include' => 'import_excel_sheet',
				'data_import_response' => ''
			], 0);
		}
		else
		{
			parent::logout();
		}

	}

}

?>
