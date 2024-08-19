<?php

class Dataexport extends Controller {

	public function __construct(){
		parent::authenticate();//Check if User is Logged In First ???
	}

	public function index(){}

	/*--- Export to EXCEL ---*/
	/*public function toExcel(){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			//For the Beginning of the Filename
			$name = 'Transactions';

			if (isset($_GET['sales']))
			{
				$name = 'Sales';

				if (isset($_GET['start']) && isset($_GET['last']))
				{
					//Create A Sales Object
					$SalesObj = parent::model('SalesModel');

					//Receive list of Sales made btn provided start & last dates
					$CustomList = $SalesObj->customSalesReport($_GET['start'], $_GET['last']);

					//Column Headings on the Excel Sheet
					$tableColumns = array('Sales ID', 'Drug ID', 'Brand Name', 'Genetic Name', 'Quantity Sold', 'Total Amount(Tshs)', 'Date Sold');
				}
				else
				{
					$CustomList = [];
					$tableColumns = [];
				}
			}
			else if (isset($_GET['expenses']))
			{
				$name = 'Expenses';

				if (isset($_GET['start']) && isset($_GET['last']))
				{
					//Create A Expenses Object
					$ExpensesObj = parent::model('ExpensesModel');

					//Receive list of Expenses made btn provided start & last dates
					$CustomList = $ExpensesObj->customExpenseReport($_GET['start'], $_GET['last']);

					//Column Headings on the Excel Sheet
					$tableColumns = array('Expenses ID', 'Title', 'Description', 'Cost(Tshs)', 'Date');
				}
				else
				{
					$CustomList = [];
					$tableColumns = [];
				}
			}

			//Create A DatatableExports Object
			$DataExportObj = parent::model('DatatableExportsModel');

			//Export Datatable to EXCEL format
			$DataExportObj->exportToEXCEL($name.' of '.$_GET['start'].' to '.$_GET['last'], $tableColumns, $CustomList);
		}
		else
		{
			parent::logout();
		}

	}*/

	/*--- Export to PDF ---*/
	public function toPDF($type, $start, $end){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			$CustomList = [];
			$tableColumns = [];

			//For the Beginning of the Filename
			$name = 'Transactions';

			if($type == 'trash')
			{
				$name = 'Expired Items';

				if (!empty($start) && !empty($end))
				{
					//Receive list of Trash Items made btn provided start & last dates
					$CustomList = parent::model('StockManagementModel')->custom_trash_search($start, $end);

					//Column Headings on the Document
					$tableColumns = array('S/N', 'Brand Name', 'Genetic Name', 'Date Trashed', 'Expiry Date', 'Qty Expired', 'Unit Loss(Tshs)', 'Total Loss(Tshs)');
				}
			}
			else if($type == 'purchases')
			{
				$name = 'Purchases';

				if (!empty($start) && !empty($end))
				{
					//Receive list of Purchases made btn provided start & last dates
					$CustomList = parent::model('StockManagementModel')->custom_purchases_search($start, $end);

					//Column Headings on the Document
					$tableColumns = array('S/N', 'Brand Name', 'Generic Name', 'Date Purchased', 'Expiry Date', 'Strength', 'Qty Bought', 'Unit Cost(Tshs)', 'Total Cost(Tshs)');
				}
			}
			else if ($type == 'sales')
			{
				$name = 'Sales';

				if (!empty($start) && !empty($end))
				{
					//Receive list of Sales made btn provided start & last dates
					$CustomList = parent::model('SalesModel')->customSalesReport($start, $end);

					//Column Headings on the Document
					$tableColumns = array('S/N', 'Brand Name', 'Generic Name', 'Quantity Sold', 'Unit Selling Price(Tshs)', 'Total (Tshs)', 'Sold By', 'Date Sold');
				}
			}
			else if ($type == 'expenses')
			{
				$name = 'Expenses';

				if (!empty($start) && !empty($end))
				{
					//Receive list of Expenses made btn provided start & last dates
					$CustomList = parent::model('ExpensesModel')->customExpenseReport($start, $end);

					//Column Headings on the Document
					$tableColumns = array('S/N', 'Description', 'Total Cost(Tshs)', 'Date of Transaction');
				}
			}

			//Export Datatable to PDF format
			parent::model('DatatableExportsModel')->exportToPDF($name.' From: '.$start.' To: '.$end, $tableColumns, $CustomList);
		}
		else
		{
			parent::logout();
		}

	}

}

?>
