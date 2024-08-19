<?php

class Home extends Controller {

	public function __construct(){
		parent::authenticate();
	}

	public function index(){

		//Create A Dashboard Object
		$DashboardObj = parent::model('DashboardModel');

		parent::view('index', 'Home', [
			'page_include' => 'dashboard',
			'drugs' => $DashboardObj->total_drugs,
			'todaySales' => $DashboardObj->today_sales,
			'todaySalesNum' => $DashboardObj->num_sold_drugsToday,
			'thisMonthSales' => $DashboardObj->this_month_sales,
			'expiredDrugs' => $DashboardObj->expired_drugs,
			'nearExpiry' => $DashboardObj->near_expiry,
			'outOfStock' => $DashboardObj->outOfStock,
			'nearOutOfStock' => $DashboardObj->nearOutOfStock
		], 0);

	}

	public function view_drugs(){

		//Receive a list of Drugs
		$DrugList = parent::model('DrugModuleModel')->listDrugs();

		parent::view('index', 'Drug Module', [
			'page_include' => 'view_drugs',
			'list_of_drugs' => $DrugList
		], 0);

	}

	public function add_drug(){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			//Add Drug
			parent::model('DrugModuleModel')->addDrug();

			parent::view('index', 'Drug Module', [
				'page_include' => 'add_drug'
			], 0);
		}
		else
		{
			self::view_drugs();
		}

	}

	public function edit_drug($id = ''){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			//Create A Drug Module Object
			$DrugModuleObj = parent::model('DrugModuleModel');

			//Perform Edit Request
			$DrugModuleObj->editDrug();

			//Receive the Drug
			$Drug = [];
			if (!empty($id)) {
				$Drug = $DrugModuleObj->listDrugBYid($id);
			}
			if(Session::check('edit_drug')){
				$Drug = $DrugModuleObj->listDrugBYid(strtolower(Session::get('edit_drug')));
			}

			if(Session::check('edit_drug') || !empty($id)){
				parent::view('index', 'Edit Details', [
					'page_include' => 'edit_drug',
					'list_of_drugs' => $Drug
				], 0);
			}else{
				self::view_drugs();
			}
		}
		else
		{
			self::view_drugs();
		}

	}

	public function delete_drug($id = ''){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			//Create A Drug Module Object
			$DrugModuleObj = parent::model('DrugModuleModel');

			//Receive a Delete Response
			if (!empty($id)) {
				$DrugModuleObj->deleteDrug($id);
			}

			//Receive a list of Drugs
			$DrugList = $DrugModuleObj->listDrugs();

			parent::view('index', 'Drug Module', [
				'page_include' => 'view_drugs',
				'list_of_drugs' => $DrugList
			], 0);
		}
		else
		{
			self::view_drugs();
		}

	}

	public function add_stock($action = ''){

		//Create A Stock Management Object
		$StockManagementObj = parent::model('StockManagementModel');

		if ($action == 'run')
		{
			$CartDetails = [];

			if (isset($_POST['multiple_drug_selector']) && is_array($_POST['multiple_drug_selector']))
			{
				if(!empty($_POST['multiple_drug_selector']))
				{
					//Saving Stock Details into Temporary Cart
					$CartDetails = $StockManagementObj->getCartDetails($_POST['multiple_drug_selector']);
				}
			}

			parent::view('index', 'Stock Management', [
				'page_include' => 'add_stock',
				'cart_details' => $CartDetails
			], 0);
		}
		else if ($action == 'save')
		{
			//Saving Stock Details into the Database
			$SaveStockDetailsResponse = $StockManagementObj->saveStockInDB();

			//List of Available Stock
			$AvailableStockList = $StockManagementObj->availableStockRetrieval();

			parent::view('index', 'Stock Management', [
				'page_include' => 'add_stock',
				'save_stock_details_response' => $SaveStockDetailsResponse,
				'available_stock' => $AvailableStockList
			], 0);
		}
		else
		{
			//List of Available Stock
			$AvailableStockList = $StockManagementObj->availableStockRetrieval();

			parent::view('index', 'Stock Management', [
				'page_include' => 'add_stock',
				'available_stock' => $AvailableStockList
			], 0);
		}

	}

	public function list_purchases(){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			//Create A Stock Management Object
			$StockManagementObj = parent::model('StockManagementModel');

			//Obtain This Month Total Loses from Expired Drugs
			$TotalMonthlyLosesAmount = $StockManagementObj->calThisMonthTotalLoses();

			//Obtain Total Loses from Expired Drugs
			$TotalLosesAmount = $StockManagementObj->calTotalLoses();

			//List Items in Trash(Expired)
			$ItemsInTrash = $StockManagementObj->list_items_in_trash();

			//Obtain This Month Total Purchases
			$TotalMonthlyPurchasesAmount = $StockManagementObj->calThisMMonthTotalPurchases();

			//List This Month Purchases
			$ThisMonthPurchases = $StockManagementObj->this_month_purchases();

			//Obtain Total Purchases
			$TotalPurchasesAmount = $StockManagementObj->calTotalPurchases();

			//List All Purchases
			$AllPurchases = $StockManagementObj->list_all_purchases();

			parent::view('index', 'Stock Management', [
				'page_include' => 'purchases_records',
				'this_month_loses_amount' => $TotalMonthlyLosesAmount,
				'total_loses_amount' => $TotalLosesAmount,
				'list_items_in_trash' => $ItemsInTrash,
				'this_month_purchases_amount' => $TotalMonthlyPurchasesAmount,
				'this_month_purchases' => $ThisMonthPurchases,
				'all_purchases_total_amount' => $TotalPurchasesAmount,
				'list_all_purchases' => $AllPurchases
			], 0);
		}
		else
		{
			self::add_stock();
		}

	}

	public function edit_stock_record($id = ''){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			//Create A Stock Management Object
			$StockManagementObj = parent::model('StockManagementModel');

			//Perform Edit Request
			$StockManagementObj->editStockRecord($id);

			//Receive the Stock Record
			$Purchase = [];
			if (!empty($id)) {
				$Purchase = $StockManagementObj->listPurchasesBYid($id);
			}
			if(Session::check('edit_stock')){
				$Purchase = $StockManagementObj->listPurchasesBYid(strtolower(Session::get('edit_stock')));
			}

			if(Session::check('edit_stock') || !empty($id)){
				parent::view('index', 'Edit Purchase Details', [
					'page_include' => 'edit_stock',
					'list_of_purchases' => $Purchase
				], 0);
			}else{
				self::list_purchases();
			}
		}
		else
		{
			self::add_stock();
		}

	}

	public function delete_stock_record($id = ''){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			//Create A Stock Management Object
			$StockManagementObj = parent::model('StockManagementModel');

			//Receive a Delete Response
			if (!empty($id)) {
				$StockManagementObj->deleteStockRecord($id);
			}

			self::list_purchases();
		}
		else
		{
			self::add_stock();
		}

	}

	public function liveSearchTrash(){

		//Perform a search operation
		parent::model('StockManagementModel')->custom_trash_search();

	}

	public function liveSearchPurchases(){

		//Perform a search operation
		parent::model('StockManagementModel')->custom_purchases_search();

	}

	public function pos(){

		parent::view('index', 'POS', [
			'page_include' => 'pos'
		], 0);

	}

	public function retrieve_available_stock(){

		//Get the List of Available Stock
		parent::model('POSModel')->availableStock();

	}

	public function process_payments(){

		//Processed payment
		parent::model('POSModel')->processPayments();

	}

	public function sales(){

		//Create A Dashboard Object
		$DashboardObj = parent::model('DashboardModel');

		//Create A Sales Object
		$SalesObj = parent::model('SalesModel');

		//Receive list of Sales made Today
		$TodaySalesList = $SalesObj->salesOFtoday();

		parent::view('index', 'Sales', [
			'page_include' => 'sales',
			'todaySales' => $DashboardObj->today_sales,
			'thisMonthSales' => $DashboardObj->this_month_sales,
			'today_sales_list' => $TodaySalesList
		], 0);

	}

	public function deleteSales($id = ''){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			//Create A Sales Object
			$SalesObj = parent::model('SalesModel');

			//Receive a Delete Response
			if (!empty($id)) {
				$SalesObj->deleteSales($id);
			}

			//Create A Dashboard Object
			$DashboardObj = parent::model('DashboardModel');

			//Receive list of Sales made Today
			$TodaySalesList = $SalesObj->salesOFtoday();

			parent::view('index', 'Sales', [
				'page_include' => 'sales',
				'todaySales' => $DashboardObj->today_sales,
				'thisMonthSales' => $DashboardObj->this_month_sales,
				'today_sales_list' => $TodaySalesList
			], 0);
		}
		else
		{
			self::sales();
		}

	}

	public function sales_report(){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			//Create A Sales Object
			$SalesObj = parent::model('SalesModel');

			//Receive Total Sales made This Week
			$ThisWeekTotalSales = $SalesObj->fixedWeekTotalSales('monday this week');

			//Receive Total Sales made The Past Week
			$LastWeekTotalSales = $SalesObj->fixedWeekTotalSales('monday last week');

			//Receive Total Sales made This Year
			$ThisYearTotalSales = $SalesObj->fixedYearTotalSales('this year');

			//Receive Total Sales made Last Year
			$LastYearTotalSales = $SalesObj->fixedYearTotalSales('last year');

			//Receive All Sales Records
			$AllSalesTransactionsList = $SalesObj->allSalesTransactions();

			parent::view('index', 'Sales Report', [
				'page_include' => 'sales_report',
				'this_week_total_sales' => $ThisWeekTotalSales,
				'last_week_total_sales' => $LastWeekTotalSales,
				'this_year_total_sales' => $ThisYearTotalSales,
				'last_year_total_sales' => $LastYearTotalSales,
				'all_sales_transactions' => $AllSalesTransactionsList
			], 0);
		}
		else
		{
			self::sales();
		}

	}

	public function liveSearchSales(){

		//Perform a search operation
		parent::model('SalesModel')->customSalesReport();

	}

	public function deleteSalesTransaction($id = ''){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			//Receive a Delete Response
			if (!empty($id))
			{
				parent::model('SalesModel')->deleteSales($id);
			}
			self::sales_report();
		}
		else
		{
			self::sales();
		}

	}

	public function list_expenses(){

		//Receiving list of Expenses
		$ExpensesList = parent::model('ExpensesModel')->listExpenses();

		parent::view('index', 'Expenses', [
			'page_include' => 'expenses',
			'list_of_expenses' => $ExpensesList
		], 0);

	}

	public function add_expense(){

		//Create A Expenses Object
		$ExpensesObj = parent::model('ExpensesModel');

		//Receiving response from the "POST" Request
		$ExpensesObj->addExpenses();

		//Receiving list of Expenses
		$ExpensesList = $ExpensesObj->listExpenses();

		parent::view('index', 'Expenses', [
			'page_include' => 'expenses',
			'list_of_expenses' => $ExpensesList
		], 0);

	}

	public function edit_expense($id = ''){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			//First display the details of the record to be edited
			if(!empty($id))
			{
				//Create A Expenses Object
				$ExpensesObj = parent::model('ExpensesModel');

				//Receiving list of Expenses
				$ExpensesList = $ExpensesObj->listExpenses();

				//Receiving a particular record of Expenses
				$ExpenseRecord = $ExpensesObj->listExpensesBYid($id);

				parent::view('index', 'Expenses', [
					'page_include' => 'expenses',
					'list_of_expenses' => $ExpensesList,
					'expense_record_of_edit' => $ExpenseRecord,
					'expenses_db_response' => ''
				], 0);
			}
			//Perform editing operation
			else
			{
				//Create A Expenses Object
				$ExpensesObj = parent::model('ExpensesModel');

				//Receiving response from the "GET" Request
				$ExpensesObj->editExpenses();

				//Receiving list of Expenses
				$ExpensesList = $ExpensesObj->listExpenses();

				parent::view('index', 'Expenses', [
					'page_include' => 'expenses',
					'list_of_expenses' => $ExpensesList
				], 0);
			}
		}
		else
		{
			self::list_expenses();
		}

	}

	public function delete_expense($id = ''){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			if(!empty($id))
			{
				//Create A Expenses Object
				$ExpensesObj = parent::model('ExpensesModel');

				//Receiving response from the "GET" Request
				$ExpensesObj->deleteExpenses($id);

				//Receiving list of Expenses
				$ExpensesList = $ExpensesObj->listExpenses();

				parent::view('index', 'Expenses', [
					'page_include' => 'expenses',
					'list_of_expenses' => $ExpensesList
				], 0);
			}
			else
			{
				self::list_expenses();
			}
		}
		else
		{
			self::list_expenses();
		}

	}

	public function expenses_report(){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			//Receive All Expenses Records
			$AllExpensesTransactionsList = parent::model('ExpensesModel')->allExpensesTransactions();

			parent::view('index', 'Expenses Report', [
				'page_include' => 'expenses_report',
				'all_expenses_transactions' => $AllExpensesTransactionsList
			], 0);
		}
		else
		{
			self::list_expenses();
		}

	}

	public function liveSearchExpenses(){

		//Perform a search operation
		parent::model('ExpensesModel')->customExpenseReport();

	}

	public function deleteExpensesTransaction($id = ''){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			//Receive a Delete Response
			if (!empty($id))
			{
				//Receiving response from the "GET" Request
				parent::model('ExpensesModel')->deleteExpenses($id);
			}
			self::expenses_report();
		}
		else
		{
			self::list_expenses();
		}

	}

	public function profitloss_report(){

		//Create a Profit-Loss Object
		$ProfitLossObj = parent::model('ProfitLossModel');

		parent::view('index', 'Profit-Loss Report', [
			'page_include' => 'profit_loss_report'
		], 0);

	}

	public function liveSearchProfitLoss(){

		//Perform a search operation
		parent::model('ProfitLossModel')->customProfitLossReport();

	}

	public function change_user_details(){

		parent::view('index', 'Settings', [
			'page_include' => 'change_user_details'
		], 0);

	}

	public function alterUserDetails(){

		//Receive a response for the "POST" request made
		parent::model('UserModel')->changeUserDetails();

		parent::view('index', 'Settings', [
			'page_include' => 'change_user_details'
		], 0);

	}

	public function change_password(){

		parent::view('index', 'Settings', [
			'page_include' => 'change_password'
		], 0);

	}

	public function alterPassword(){

		//Receive a response for the "POST" request made
		parent::model('UserModel')->changePassword();

		parent::view('index', 'Settings', [
			'page_include' => 'change_password'
		], 0);

	}

	public function addUserAccount(){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			//Receive a response for the "POST" request made
			parent::model('UserModel')->addUser();

			parent::view('index', 'Settings', [
				'page_include' => 'add_user_account'
			], 0);
		}

	}

	public function userAccounts(){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			//Receive a response for the "POST" request made
			$UserAccounts = parent::model('UserModel')->viewUserAccounts();

			parent::view('index', 'Settings', [
				'page_include' => 'user_accounts',
				'list_accounts' => $UserAccounts
			], 0);
		}

	}

	public function resetUserAccountPass($id = ''){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			//Receive a Password Reset Response
			if (!empty($id))
			{
				//Receiving response from the "GET" Request
				parent::model('UserModel')->resetDefaultPass($id);
			}

			self::userAccounts();
		}

	}

	public function deleteUserAccount($id = ''){

		//Check if the View-Request is made by an Administrator
		if(Session::get('isAdmin') == true)
		{
			//Receive a Account Deletion Response
			if (!empty($id))
			{
				//Receiving response from the "GET" Request
				parent::model('UserModel')->deleteAccount($id);
			}

			self::userAccounts();
		}

	}

	public function runLogOut(){

		parent::logout();

	}

}

?>
