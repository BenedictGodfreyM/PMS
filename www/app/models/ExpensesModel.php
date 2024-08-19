<?php

/**
 *
 */
class ExpensesModel extends Database{

	protected $db;

	public $custom_expense_list = [];

	public $custom_total_expenses = [];

	public $all_expenses_transactions = [];

	public $this_month_expenses = [];

	public $list_expensesBYid = [];

	function __construct(){

		//Database Connection Instance
		$this->db = new Database;

		//Enabling Exceptions
		$this->db->enableExceptions();

	}

	public function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	public function filterString($data){
		$filter_params = array("options"=>array("regexp"=>"/^[0-9a-zA-Z\s]+$/"));
		if(filter_var($data, FILTER_VALIDATE_REGEXP, $filter_params)){
			return true;
		} else{
			return false;
		}
	}

	public function listExpenses(){

		$this_month = date("Y-m");
		$stmt = $this->db->prepare("SELECT * FROM expenses  WHERE strftime('%Y-%m', date_of_transaction) = ? ORDER BY date_of_transaction DESC");
		$stmt->bindValue(1, $this_month, SQLITE3_TEXT);
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->this_month_expenses[$i] = $row;
      	$i++;
      }
			return $this->this_month_expenses;
		}

	}

	public function listExpensesBYid($param){

		$stmt = $this->db->prepare("SELECT * FROM expenses WHERE expense_id = ?");
		$stmt->bindValue(1, $param, SQLITE3_TEXT);
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->list_expensesBYid[$i] = $row;
      	$i++;
      }
			return $this->list_expensesBYid;
		}

	}

	public function addExpenses(){

		if($_SERVER['REQUEST_METHOD'] != 'POST'){
			return false;
			exit();
		}

		if (!isset($_POST['description']) || !isset($_POST['cost']) || !isset($_POST['transaction_date'])) {
			Session::set('error','Oooops! Error in System configurations.');
			return false;
			exit();
		}

		//Data from the form
		$description = self::test_input($_POST['description']);
		$cost = self::test_input($_POST['cost']);
		$date = self::test_input($_POST['transaction_date']);

		if (empty($description)) {
			$description = "NAN";
		}

		if (self::filterString($description) == false) {
			Session::set('error','Error! Enter a pure string without characters like commas, question marks, etc for description.');
			return false;
			exit();
		}

		if (strlen($cost) > 10) {
			Session::set('error','Error! Figures on the costs of expenses should be less than 10.');
			return false;
			exit();
		}

		//Auto generated data
		$id = strtolower(md5(uniqid(microtime(), false) . date("Y-m-d h:i:s", strtotime("now"))));

		$stmt = $this->db->prepare("INSERT INTO expenses(expense_id, description, total_cost, date_of_transaction) VALUES(?, ?, ?, ?)");
		$stmt->bindValue(1, $id, SQLITE3_TEXT);
    $stmt->bindValue(2, $description, SQLITE3_TEXT);
    $stmt->bindValue(3, $cost, SQLITE3_INTEGER);
    $stmt->bindValue(4, $date, SQLITE3_TEXT);
		$result = $stmt->execute();

		if ($result) {
			Session::set('success','Record added successfully.');
			return true;
		}else{
			Session::set('warning','Ooops!!! Something went wrong.');
			return false;
		}

	}

	public function editExpenses(){

		if($_SERVER['REQUEST_METHOD'] != 'POST'){
			return false;
			exit();
		}

		if (!isset($_POST['edit_id']) || !isset($_POST['description']) || !isset($_POST['cost']) || !isset($_POST['transaction_date'])) {
			Session::set('error','Oooops! Error in System configurations.');
			return false;
			exit();
		}

		//Data from the form
		$id = self::test_input($_POST['edit_id']);
		$description = self::test_input($_POST['description']);
		$cost = self::test_input($_POST['cost']);
		$date = self::test_input($_POST['transaction_date']);

		if (empty($description)) {
			$description = "NAN";
		}

		if (self::filterString($description) == false) {
			Session::set('error','Error! Enter a pure string without characters like commas, question marks, etc for description.');
			return false;
			exit();
		}

		if (strlen($cost) > 10) {
			Session::set('error','Error! Figures on the costs of expenses should be less than 10.');
			return false;
			exit();
		}

		$stmt = $this->db->prepare("UPDATE expenses SET description = ?, total_cost = ?, date_of_transaction = ? WHERE expense_id = ?");
		$stmt->bindValue(1, $description, SQLITE3_TEXT);
    $stmt->bindValue(2, $cost, SQLITE3_INTEGER);
    $stmt->bindValue(3, $date, SQLITE3_TEXT);
    $stmt->bindValue(4, $id, SQLITE3_TEXT);
		$result = $stmt->execute();

		if ($result) {
			Session::set('success','Record updated successfully.');
			return true;
		}else{
			Session::set('error','Ooops!!! Something went wrong.');
			return false;
		}

	}

	public function deleteExpenses($param){

		$stmt = $this->db->prepare("DELETE FROM expenses WHERE expense_id = ?");
		$stmt->bindValue(1, $param, SQLITE3_TEXT);
		$result = $stmt->execute();
		if ($result){
			Session::set('success','One of the Records was Deleted.');
			return true;
		}

	}

	public function customExpenseReport($d1 = '', $d2 = ''){

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			$stmt = $this->db->prepare("SELECT * FROM expenses  WHERE date(date_of_transaction) BETWEEN ? AND ? ORDER BY date_of_transaction DESC");
			$stmt->bindValue(1, $_POST['d1'], SQLITE3_TEXT);
			$stmt->bindValue(2, $_POST['d2'], SQLITE3_TEXT);
			$result = $stmt->execute();
			$count = 0;
	    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
	      $count++;
	    }
			if($count < 0) {
				$output = "<tr><td colspan='8' style='color: red;'>Connection Error!!!</td></tr>";
				echo json_encode(array('header' => '', 'list_data'  => $output));
				return false;
			}
			if(empty($count)) {
				$output = "<tr><td colspan='8' style='color: blue;'>No Data within the given date range. (From ".$_POST['d1']." to ".$_POST['d2'].")</td></tr>";
				echo json_encode(array('header' => '', 'list_data'  => $output));
				return false;
			}
			$output = "";
			if($count > 0) {
				$i=1;
				while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
					$output .= "
							<tr>
								<td>".$i."</td>
								<td>".$row['description']."</td>
								<td>".$row['total_cost']."</td>
								<td>".$row['date_of_transaction']."</td>
							</tr>
					";
					$i++;
				}
			}
			$search_results = array(
				'header' => self::customTotalExpenses(),
				'list_data'  => $output
			);
			echo json_encode($search_results);
			return true;

		}

		if($_SERVER['REQUEST_METHOD'] == 'GET') {

			$stmt = $this->db->prepare("SELECT description, total_cost, date_of_transaction FROM expenses  WHERE date(date_of_transaction) BETWEEN ? AND ? ORDER BY date_of_transaction DESC");
			$stmt->bindValue(1, $d1, SQLITE3_TEXT);
			$stmt->bindValue(2, $d2, SQLITE3_TEXT);
			$result = $stmt->execute();

			$count = 0;
			while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
				$count++;
			}

			$data = $temp = [];
			if($count > 0) {
				$i=0;
				while ($i < $count) {
					$num = $i+1;
					$data[$i] = array('s/n' => $num);
					$i++;
				}
				$i=0;
				while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
					$temp[$i] = array_merge($data[$i], $row);
					$i++;
				}
				$data = $temp;
			}
			return $data;

		}

	}

	public function customTotalExpenses(){

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			$stmt = $this->db->prepare("SELECT sum(total_cost) as total FROM expenses WHERE date(date_of_transaction) BETWEEN ? AND ? GROUP BY expense_id");
			$stmt->bindValue(1, $_POST['d1'], SQLITE3_TEXT);
			$stmt->bindValue(2, $_POST['d2'], SQLITE3_TEXT);
			$result = $stmt->execute();

			$count = 0;
	    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
	      $count++;
	    }
			$total = 0;
			$output = "";
			if($count > 0) {
				while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
					$total += $row['total'];
				}
				if(!empty($total))
				{
					$output .= "
						<li class='nav-item'>
							<a class='nav-link active'>Total: Tsh ".$total."/=</a>
						</li>
						<li class='nav-item'>
							<a class='nav-link' href='?url=dataexport/toPDF/expenses/".$_POST['d1']."/".$_POST['d2']."' target='_blank'>
								<i class='fas fa-file-pdf'> Export to PDF</i>
							</a>
						</li>
					";
				}
				else
				{
					$output .= "
						<li class='nav-item'>
							<a class='nav-link active'>Total: Tsh 0/=</a>
						</li>
					";
				}
				return $output;
			}

		}

	}

	public function allExpensesTransactions(){

		$stmt = $this->db->prepare("SELECT * FROM expenses ORDER BY date_of_transaction DESC");
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->all_expenses_transactions[$i] = $row;
      	$i++;
      }
			return $this->all_expenses_transactions;
		}

	}

	public function __destruct(){

		if($this->db) $this->db->close();

	}

}

?>
