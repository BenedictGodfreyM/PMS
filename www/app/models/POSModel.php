<?php

/**
 *
 */
class POSModel  extends Database{

	protected $db;

	public function __construct(){

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

	public function availableStock(){

		$stmt = $this->db->prepare("SELECT b.drug_id, b.expiry_date, sum(b.available_qty) as qty, d.drug_id, d.brand_name, d.generic_name, d.unit_s_price FROM Batches as b INNER JOIN drugs as d ON b.drug_id = d.drug_id GROUP BY d.drug_id HAVING date(b.expiry_date) > ? AND qty > ?");
		$stmt->bindValue(1, date("Y-m-d", strtotime("now")), SQLITE3_TEXT);
		$stmt->bindValue(2, 0, SQLITE3_INTEGER);
		$result = $stmt->execute();

		$count = 0;
	    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
	      $count++;
	    }
		$available_stock = array();
		if($count > 0) {
			$i = 0;
			while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
				$available_stock[$i] = $row;
				$i++;
			}
		}
		echo json_encode(array("drug_list" => $available_stock));
		return true;

	}

	public function processPayments(){

		if($_SERVER['REQUEST_METHOD'] != 'POST'){
			echo json_encode(array(
				"response" => "error", 
				"message" => "Invalid Request."
			));
			return false;
			exit();
		}

		if (!isset($_POST['cart']) && !is_array($_POST['cart'])) {
			echo json_encode(array(
				"response" => "error", 
				"message" => "Oooops! Error in System configurations."
			));
			return false;
			exit();
		}

		$cart = $_POST['cart'];

		//Retrieve Details of the Drugs to be sold
		$drugs = array();
		for ($i=0; $i < count($cart); $i++) { 
			$stmt = $this->db->prepare("SELECT d.drug_id, d.unit_s_price, b.batch_id, b.drug_id, sum(b.available_qty) as qty, b.expiry_date, b.unit_b_price FROM drugs as d LEFT JOIN Batches as b ON d.drug_id = b.drug_id GROUP BY d.drug_id HAVING d.drug_id = ? AND date(b.expiry_date) > ? AND qty > ? ORDER BY b.expiry_date ASC");
			$stmt->bindValue(1, self::test_input($cart[$i][0]), SQLITE3_TEXT);
			$stmt->bindValue(2, date("Y-m-d", strtotime("now")), SQLITE3_TEXT);
			$stmt->bindValue(3, 0, SQLITE3_INTEGER);
			$result = $stmt->execute();

			while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
				$drugs[$i] = $row;
			}
		}

		// Check if drug quantities in cart is less than their equivalent stock quantities
		for ($i=0; $i < count($cart); $i++) { 
			if($cart[$i][1] > $drugs[$i]['qty']){
				echo json_encode(array(
					"response" => "warning", 
					"message" => "Ooops! You have selected a larger quantity than the available stock."
				));
				return false;
			}
		}

		//Additional Details for Sales Records
		$total_cost = $total_amount = $id = array();
		for ($i=0; $i < count($cart); $i++) { 
			// Total cost for the nth qty of drugs sold
			$total_cost[$i] = $drugs[$i]['unit_b_price'] * self::test_input($cart[$i][1]);

			// Total income for the nth qty of drugs sold
			$total_amount[$i] = $drugs[$i]['unit_s_price'] * self::test_input($cart[$i][1]);

			//Gloss Profit Generated for the Transaction
			$gloss_profit[$i] = $total_amount[$i] - $total_cost[$i];

			//Auto generated data
			$id[$i] = strtolower(md5(uniqid($cart[$i][0], false) . date("Y-m-d h:i:s", strtotime("now"))));
		}

		//BEGIN Transaction
		$this->db->query("BEGIN");
		//Save sales Details in the Sales-Table
		$result1 = array();//print_r(count($cart));
		for ($i=0; $i < count($cart); $i++) { 
			$stmt1 = $this->db->prepare("INSERT INTO Sales(sales_id, drug_id, batch_id, user_id, qty_sold, unit_s_price, total_amount, gloss_profit, date_of_transaction) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt1->bindValue(1, $id[$i], SQLITE3_TEXT);
			$stmt1->bindValue(2, self::test_input($cart[$i][0]), SQLITE3_TEXT);
			$stmt1->bindValue(3, $drugs[$i]['batch_id'], SQLITE3_TEXT);
			$stmt1->bindValue(4, Session::get('id'), SQLITE3_TEXT);
			$stmt1->bindValue(5, self::test_input($cart[$i][1]), SQLITE3_TEXT);
			$stmt1->bindValue(6, $drugs[$i]['unit_s_price'], SQLITE3_TEXT);
			$stmt1->bindValue(7, $total_amount[$i], SQLITE3_TEXT);
			$stmt1->bindValue(8, $gloss_profit[$i], SQLITE3_TEXT);
			$stmt1->bindValue(9, date("Y-m-d h:i:s", strtotime("now")), SQLITE3_TEXT);
			$result1[$i] = $stmt1->execute();
		}

		//Select all Batches with the selected drug prior for sale
		$batches = array();
		for ($i=0; $i < count($cart); $i++) { 
			$stmt2 = $this->db->prepare("SELECT batch_id, drug_id, expiry_date, available_qty FROM Batches WHERE drug_id = ? AND date(expiry_date) > ? AND available_qty > ? ORDER BY expiry_date ASC");
			$stmt2->bindValue(1, self::test_input($cart[$i][0]), SQLITE3_TEXT);
			$stmt2->bindValue(2, date("Y-m-d", strtotime("now")), SQLITE3_TEXT);
			$stmt2->bindValue(3, 0, SQLITE3_INTEGER);
			$result2 = $stmt2->execute();
			$j = 0;
			while ($row2 = $result2->fetchArray(SQLITE3_ASSOC)) {
				$batches[$i][$j] = $row2;
				$j++;
			}
		}

		// Decrement stock quantity in the Batch(es) from which drugs were taken for sale respective of their expiry dates
		$updated_rows = 0;//No. of rows updated in Batches-Table
		for ($i=0; $i < count($batches); $i++) { 
			for ($j=0; $j < count($batches[$i]); $j++) { 
				if($batches[$i][$j]['available_qty'] >= $cart[$i][1]){
					$qty_sold = self::test_input($cart[$i][1]);
					$stmt3 = $this->db->prepare("UPDATE Batches SET available_qty = available_qty - '$qty_sold' WHERE batch_id = ?");
					$stmt3->bindValue(1, $batches[$i][$j]['batch_id'], SQLITE3_TEXT);
					$result3 = $stmt3->execute();
					$updated_rows += $this->db->changes();//No. of rows updated in Batches-Table
					break;
				}else if($batches[$i][$j]['available_qty'] > 0 && $batches[$i][$j]['available_qty'] < $cart[$i][1]){
					$cart[$i][1] = $cart[$i][1] - $batches[$i][$j]['available_qty'];//Calculate Remaining Qty
					$stmt3 = $this->db->prepare("UPDATE Batches SET available_qty = ? WHERE batch_id = ?");
					$stmt3->bindValue(1, 0, SQLITE3_INTEGER);
					$stmt3->bindValue(2, $batches[$i][$j]['batch_id'], SQLITE3_TEXT);
					$result3 = $stmt3->execute();
					$updated_rows += $this->db->changes();//No. of rows updated in Batches-Table
					continue;
				}
			}
		}

		if (!array_search(0, $result1, true) && $updated_rows > 0) {
			//Close all database transactions
			$this->db->query("COMMIT");
			echo json_encode(array(
				"response" => "success", 
				"message" => "Transaction made successfully!."
			));
			return true;
		}else{
			//Reverse all database transactions
			$this->db->query("ROLLBACK");
			echo json_encode(array(
				"response" => "error", 
				"message" => "Sorry! Something went wrong."
			));
			return false;
		}

	}

	public function __destruct(){

		if($this->db) $this->db->close();

	}

}

?>
