<?php

class SalesModel extends Database{

	protected $db;

	public $custom_sales_list = [];

	public $custom_total_sales = [];

	public $all_sales_transactions = [];

	public $todaySalesList = [];

	public function __construct(){

		//Database Connection Instance
		$this->db = new Database;

		//Enabling Exceptions
		$this->db->enableExceptions();

	}

	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	public function salesOFtoday(){

		$stmt = $this->db->prepare("SELECT s.sales_id AS s_id, s.drug_id, d.brand_name, d.generic_name, s.qty_sold, s.unit_s_price, s.total_amount, s.date_of_transaction, d.drug_id AS d_id, u.firstname, u.lastname FROM Sales AS s LEFT JOIN drugs AS d ON s.drug_id = d.drug_id LEFT JOIN users AS u ON s.user_id = u.user_id WHERE date(s.date_of_transaction) = ? ORDER BY s.date_of_transaction DESC");
		$stmt->bindValue(1, date("Y-m-d"), SQLITE3_TEXT);
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->todaySalesList[$i] = $row;
      	$i++;
      }
			return $this->todaySalesList;
		}

	}

	public function deleteSales($id){

		$delete_id = self::test_input($id);
		$stmt = $this->db->prepare("DELETE FROM Sales WHERE sales_id = ?");
		$stmt->bindValue(1, $delete_id, SQLITE3_TEXT);
		$result = $stmt->execute();

		if ($result){
			Session::set('success','Sales Record Deleted Successfully.');
			return true;
		}else{
			Session::set('error','Ooops!!! Sorry! Something went wrong.');
			return false;
		}

	}

	public function customSalesReport($d1 = '', $d2 = ''){

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			$stmt = $this->db->prepare("SELECT s.sales_id, s.drug_id, d.brand_name, d.generic_name, s.qty_sold, s.unit_s_price, s.total_amount, s.date_of_transaction, d.drug_id, u.firstname, u.lastname FROM Sales AS s LEFT JOIN drugs AS d ON s.drug_id = d.drug_id LEFT JOIN users AS u ON s.user_id = u.user_id WHERE date(s.date_of_transaction) BETWEEN ? AND ? ORDER BY s.date_of_transaction DESC");
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
								<td>".$row['brand_name']."</td>
								<td>".$row['generic_name']."</td>
								<td>".$row['qty_sold']."</td>
								<td>".$row['unit_s_price']."</td>
								<td>".$row['total_amount']."</td>
								<td>".$row['firstname']." ".$row['lastname']."</td>
								<td>".$row['date_of_transaction']."</td>
							</tr>
					";
					$i++;
				}
			}
			$search_results = array(
				'header' => self::customTotalSales(),
				'list_data'  => $output
			);
			echo json_encode($search_results);
			return true;

		}

		if($_SERVER['REQUEST_METHOD'] == 'GET') {

			$stmt = $this->db->prepare("SELECT d.brand_name, d.generic_name, s.qty_sold, s.unit_s_price, s.total_amount, s.date_of_transaction, u.firstname, u.lastname FROM Sales AS s LEFT JOIN drugs AS d ON s.drug_id = d.drug_id LEFT JOIN users AS u ON s.user_id = u.user_id WHERE date(s.date_of_transaction) BETWEEN ? AND ? ORDER BY s.date_of_transaction DESC");
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
					$temp[$i] = array_merge($data[$i], array(
									'brand_name' => $row['brand_name'],
									'generic_name' => $row['generic_name'],
									'qty_sold' => $row['qty_sold'],
									'unit_s_price' => $row['unit_s_price'],
									'total_amount' => $row['total_amount'],
									'sold_by' => $row['firstname']." ".$row['lastname'],
									'date_of_transaction' => $row['date_of_transaction'],
								));
					$i++;
				}
				$data = $temp;
			}
			return $data;

		}

	}

	public function customTotalSales(){

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			$stmt = $this->db->prepare("SELECT sum(total_amount) as total FROM Sales WHERE date(date_of_transaction) BETWEEN ? AND ? GROUP BY sales_id");
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
							<a class='nav-link' href='?url=dataexport/toPDF/sales/".$_POST['d1']."/".$_POST['d2']."' target='_blank'>
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

	public function allSalesTransactions(){

		$stmt = $this->db->prepare("SELECT s.sales_id, s.drug_id, d.brand_name, d.generic_name, s.qty_sold, s.unit_s_price, s.total_amount, s.date_of_transaction, d.drug_id, u.firstname, u.lastname FROM Sales AS s LEFT JOIN drugs AS d ON s.drug_id = d.drug_id LEFT JOIN users AS u ON s.user_id = u.user_id ORDER BY s.date_of_transaction DESC");
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->all_sales_transactions[$i] = $row;
      	$i++;
      }
			return $this->all_sales_transactions;
		}

	}

	public function fixedWeekTotalSales($param){

		/* --- nth WEEK --- */
        //Start Day of particular Week
		$d=strtotime($param);
		$nth_week_total_sales = 0;
		for ($i=0; $i<7; $i++) {
			$timestamp = strtotime('+'.$i.' Days', $d);
			$nth_day = date("Y-m-d", $timestamp);
			$stmt = $this->db->prepare("SELECT sum(total_amount) as nth_week_total, date_of_transaction FROM Sales WHERE date(date_of_transaction) = ? GROUP BY date_of_transaction ORDER BY date_of_transaction ASC");
			$stmt->bindValue(1, $nth_day, SQLITE3_TEXT);
			$result = $stmt->execute();
			$count = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $count++;
      }
			if ($count > 0) {
				while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
					$nth_week_total_sales += $row['nth_week_total'];
				}
			}
		}
		return $nth_week_total_sales;
	}

	public function fixedYearTotalSales($param){

		/* --- nth YEAR --- */
        //Start Month of particular Year
		$y = strtotime($param);
		$nth_year = date("Y", $y);
		$stmt = $this->db->prepare("SELECT sum(total_amount) as nth_year_total, date_of_transaction FROM Sales WHERE strftime('%Y', date_of_transaction) = ? GROUP BY date_of_transaction ORDER BY date_of_transaction ASC");
		$stmt->bindValue(1, $nth_year, SQLITE3_TEXT);
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		$nth_year_total_sales = 0;
		if ($count > 0) {
			while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
				$nth_year_total_sales += $row['nth_year_total'];
			}
		}
		return $nth_year_total_sales;
	}

	public function __destruct(){

		if($this->db) $this->db->close();

	}

}
