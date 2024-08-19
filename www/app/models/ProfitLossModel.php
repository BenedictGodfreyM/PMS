<?php

class ProfitLossModel extends Database{

	protected $db;

	public function __construct(){

		//Database Connection Instance
		$this->db = new Database;

		//Enabling Exceptions
		$this->db->enableExceptions();

	}

	public function customProfitLossReport(){

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			$total_purchases = 0;
			$total_sales = 0;
			$gloss_profit = 0;
			$total_expenses = 0;
			$total_expiry_loses = 0;
			$net_profit = 0;
			$count = 0;

			$stmt1 = $this->db->prepare("SELECT sum(total_cost) as total_purchase FROM Batches  WHERE strftime('%Y-%m', date_received) BETWEEN ? AND ? GROUP BY batch_id");
			$stmt1->bindValue(1, $_POST['d1'], SQLITE3_TEXT);
			$stmt1->bindValue(2, $_POST['d2'], SQLITE3_TEXT);
			$result1 = $stmt1->execute();
			while ($row1 = $result1->fetchArray(SQLITE3_ASSOC)) {
				$total_purchases += $row1['total_purchase'];
				$count++;
			}

			$stmt2 = $this->db->prepare("SELECT sum(total_amount) as total_sale, sum(gloss_profit) as total_gloss_profit FROM Sales WHERE strftime('%Y-%m', date_of_transaction) BETWEEN ? AND ? GROUP BY drug_id");
			$stmt2->bindValue(1, $_POST['d1'], SQLITE3_TEXT);
			$stmt2->bindValue(2, $_POST['d2'], SQLITE3_TEXT);
			$result2 = $stmt2->execute();
			while ($row2 = $result2->fetchArray(SQLITE3_ASSOC)) {
				$total_sales += $row2['total_sale'];
				$gloss_profit += $row2['total_gloss_profit'];
				$count++;
			}

			$stmt3 = $this->db->prepare("SELECT sum(total_cost) as total_expense FROM expenses  WHERE strftime('%Y-%m', date_of_transaction) BETWEEN ? AND ? GROUP BY expense_id");
			$stmt3->bindValue(1, $_POST['d1'], SQLITE3_TEXT);
			$stmt3->bindValue(2, $_POST['d2'], SQLITE3_TEXT);
			$result3 = $stmt3->execute();
			while ($row3 = $result3->fetchArray(SQLITE3_ASSOC)) {
				$total_expenses += $row3['total_expense'];
				$count++;
			}

			$stmt4 = $this->db->prepare("SELECT sum(total_loss) as total_expiry_loss FROM trash  WHERE strftime('%Y-%m', date_of_end_of_use) BETWEEN ? AND ? GROUP BY id");
			$stmt4->bindValue(1, $_POST['d1'], SQLITE3_TEXT);
			$stmt4->bindValue(2, $_POST['d2'], SQLITE3_TEXT);
			$result4 = $stmt4->execute();
			while ($row4 = $result4->fetchArray(SQLITE3_ASSOC)) {
				$total_expiry_loses += $row4['total_expiry_loss'];
				$count++;
			}

			//Calculate net profit
			if($gloss_profit > $total_expenses){
				$temp_gloss_profit = $gloss_profit;
				$temp_total_expenses = $total_expenses;
				$net_profit = $temp_gloss_profit - $temp_total_expenses;
				$net_profit_header = "<li class='nav-item'><a class='nav-link active'>Net-Profit: Tsh ".$net_profit."/=</a></li>";
				$net_profit = "<span class='badge badge-info'>".$net_profit."</span>";
			}
			if($gloss_profit < $total_expenses){
				$temp_gloss_profit = $gloss_profit;
				$temp_total_expenses = $total_expenses;
				$net_profit = $temp_gloss_profit - $temp_total_expenses;
				$net_profit_header = "<li class='nav-item'><a class='nav-link'>Net-Loss: Tsh ".$net_profit."/=</a></li>";
				$net_profit = "<span class='badge badge-danger'>".$net_profit."</span>";
			}
			if($gloss_profit == $total_expenses){
				$temp_gloss_profit = $gloss_profit;
				$temp_total_expenses = $total_expenses;
				$net_profit = $temp_gloss_profit - $temp_total_expenses;
				$net_profit_header = "<li class='nav-item'><a class='nav-link'>Tsh ".$net_profit."/=</a></li>";
				$net_profit = "<span class='badge badge-secondary'>".$net_profit."</span>";
			}

			$net_profit_body = "";
			if($count > 0) {
				$net_profit_body .= "
				<tr>
				<td>".$total_purchases."</td>
				<td>".$total_sales."</td>
				<td>".$gloss_profit."</td>
				<td>".$total_expenses."</td>
				<td>".$total_expiry_loses."</td>
				<td>".$net_profit."</td>
				</tr>
				";
			}
			$search_results = array(
				'header' => $net_profit_header,
				'list_data'  => $net_profit_body
			);
			echo json_encode($search_results);
			return true;

		}

	}

	public function __destruct(){

		if($this->db) $this->db->close();

	}

}

?>
