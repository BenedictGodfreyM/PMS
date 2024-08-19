<?php

class NotificationModel extends Database {

	protected $db;

	public $expired_drugs_list = [];

	public $drugs_near_expiry = [];

	public $outOFstock_drugs = [];

	public $drugs_near_shortage = [];

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

	public function expiredDrugs(){

		$stmt = $this->db->prepare("SELECT b.drug_id, b.date_received, max(b.expiry_date) as expiry_date, sum(b.available_qty) as qty, d.drug_id as d_id, d.brand_name, d.generic_name FROM Batches as b INNER JOIN drugs as d ON b.drug_id = d.drug_id WHERE date(b.expiry_date) < ? GROUP BY b.drug_id HAVING qty != ? ORDER BY expiry_date DESC");
		$stmt->bindValue(1, date("Y-m-d", strtotime("now")), SQLITE3_TEXT);
    $stmt->bindValue(2, 0, SQLITE3_INTEGER);
		$result = $stmt->execute();

		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0)
		{
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->expired_drugs_list[$i] = $row;
      	$i++;
      }

			return $this->expired_drugs_list;
		}

	}

	public function drugsNearExpiry(){

		$stmt = $this->db->prepare("SELECT b.drug_id, max(b.expiry_date) as expiry_date, sum(b.available_qty) as qty, d.drug_id as d_id, d.brand_name, d.generic_name FROM Batches as b INNER JOIN drugs as d ON b.drug_id = d.drug_id WHERE date(b.expiry_date) BETWEEN ? AND ? GROUP BY b.drug_id HAVING qty != ? ORDER BY expiry_date DESC");
		$stmt->bindValue(1, date("Y-m-d", strtotime("now")), SQLITE3_TEXT);
    $stmt->bindValue(2, date("Y-m-d", strtotime("+6 Months")), SQLITE3_TEXT);
    $stmt->bindValue(3, 0, SQLITE3_INTEGER);
		$result = $stmt->execute();

		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0)
		{
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->drugs_near_expiry[$i] = $row;
      	$i++;
      }
			return $this->drugs_near_expiry;
		}

	}

	public function outOFstockDrugs(){

		$stmt = $this->db->prepare("SELECT b.drug_id, b.expiry_date, max(b.date_received) as date_received, sum(b.available_qty) as qty, d.drug_id as d_id, d.brand_name, d.generic_name FROM Batches as b INNER JOIN drugs as d ON b.drug_id = d.drug_id GROUP BY b.drug_id HAVING date(b.expiry_date) > ? AND qty = ? ORDER BY date_received DESC");
		$stmt->bindValue(1, date("Y-m-d", strtotime("now")), SQLITE3_TEXT);
    $stmt->bindValue(2, 0, SQLITE3_INTEGER);
		$result = $stmt->execute();

		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0)
		{
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->outOFstock_drugs[$i] = $row;
      	$i++;
      }
			return $this->outOFstock_drugs;
		}

	}

	public function drugsNearShortage(){

		$stmt = $this->db->prepare("SELECT * FROM drugs WHERE qty BETWEEN ? AND ?");
		$stmt->bindValue(1, 0, SQLITE3_INTEGER);
    $stmt->bindValue(2, 15, SQLITE3_INTEGER);
		$result = $stmt->execute();

		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0)
		{
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->drugs_near_shortage[$i] = $row;
      	$i++;
      }
			return $this->drugs_near_shortage;
		}

	}

	public function countStockChanges(){

		//Count Added Batches not seen by admin
		$stmt = $this->db->prepare("SELECT batch_id FROM Batches WHERE status = ?");
		$stmt->bindValue(1, 0, SQLITE3_INTEGER);
		$result = $stmt->execute();
		$count1 = 0;
		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$count1++;
		}

		//Count Added Items in Trash not seen by admin
		$stmt = $this->db->prepare("SELECT id FROM trash WHERE status = ?");
		$stmt->bindValue(1, 0, SQLITE3_INTEGER);
		$result = $stmt->execute();
		$count2 = 0;
		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$count2++;
		}

		echo json_encode(array('purchaseCount' => $count1, 'trashCount' => $count2));
		return true;

	}

	public function retrieveStockChanges(){

		//Select all Added Batches not seen by admin
		$stmt = $this->db->prepare("SELECT d.drug_id, d.brand_name, d.generic_name, d.strength, b.batch_id, b.date_received as date, b.expiry_date as expiry, b.qty_bought as qty, b.unit_b_price as unit, b.total_cost as total FROM Batches as b LEFT JOIN drugs as d ON b.drug_id = d.drug_id WHERE b.status = ? ORDER BY b.date_received DESC");
		$stmt->bindValue(1, 0, SQLITE3_INTEGER);
		$result = $stmt->execute();
		$dataset = [];
		$i = 0;
		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$dataset[$i] = $row;
			$i++;
		}

		//Select all Added Items in Trash not seen by admin
		$stmt = $this->db->prepare("SELECT d.drug_id, d.brand_name, d.generic_name, d.strength, t.id as trash_id, t.drug_id, t.qty_trashed as qty, t.date_of_end_of_use as expiry, t.date_trashed as date, t.unit_loss as unit, t.total_loss as total FROM trash as t LEFT JOIN drugs as d ON t.drug_id = d.drug_id WHERE t.status = ? ORDER BY t.date_trashed ASC");
		$stmt->bindValue(1, 0, SQLITE3_INTEGER);
		$result = $stmt->execute();
		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$dataset[$i] = $row;
			$i++;
		}

		$output = "<h4 class='text-center py-2'>There are no recent Notifications</h4>";
		if(!empty($dataset) && is_array($dataset)) {
			$output = "
					<table id='example1' class='table table-head-fixed text-nowrap'>
						<thead>
							<tr>
								<th>#</th>
								<th>Brand Name</th>
								<th>Generic Name</th>
								<th>Status</th>
								<th>Date & Time</th>
								<th>Expiry Date</th>
								<th>Qty</th>
								<th>Unit Cost/Loss(Tshs)</th>
								<th>Total Cost/Loss(Tsh)</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
			";
			$i=1;
			foreach ($dataset as $row) {
				if(array_key_exists('batch_id', $row)){
					$status = "<span class='badge badge-info'>Purchased</span>";
					$id = "<input type='hidden' value='".$row['batch_id']."' name='batch_id' />";
				}else{
					$status = "<span class='badge badge-warning'>Trashed</span>";
					$id = "<input type='hidden' value='".$row['trash_id']."' name='trash_id' />";
				}
				$output .= "
						<tr>
							<td>".$i."</td>
							<td>".$row['brand_name']."</td>
							<td>".$row['generic_name']."</td>
							<td>".$status."</td>
							<td>".$row['date']."</td>
							<td>".$row['expiry']."</td>
							<td>".$row['qty']."</td>
							<td>".$row['unit']."</td>
							<td>".$row['total']."</td>
							<td>
								<form action='?url=notification/stock_changes_notifications' method='POST'>
									".$id."
									<button type='submit' class='btn btn-info btn-sm'>Mark as Seen</button>
								</form>
							</td>
						</tr>
				";
				$i++;
			}
			$output .= "
						</tbody>
					</table>
			";
		}

		echo json_encode(array('notify' => $output));
		return true;

	}

	public function markStockChangesAsRead(){

		if(isset($_POST['batch_id']) && !empty($_POST['batch_id']))
		{
			//Mark Particular Batch as seen by admin
			$stmt = $this->db->prepare("UPDATE Batches SET status = ? WHERE batch_id = ?");
			$stmt->bindValue(1, 1, SQLITE3_INTEGER);
			$stmt->bindValue(2, $_POST['batch_id'], SQLITE3_TEXT);
			$result = $stmt->execute();
			if ($result) {
				Session::set('success','Success!. Record marked as read.');
				return true;
			}else{
				Session::set('error','Ooops!. Something went wrong.');
				return false;
			}
		}
		else if(isset($_POST['trash_id']) && !empty($_POST['trash_id']))
		{
			//Mark Particular Items in Trash as seen by admin
			$stmt = $this->db->prepare("UPDATE trash SET status = ? WHERE id = ?");
			$stmt->bindValue(1, 1, SQLITE3_INTEGER);
			$stmt->bindValue(2, $_POST['trash_id'], SQLITE3_TEXT);
			$result = $stmt->execute();
			if ($result) {
				Session::set('success','Success!. Record marked as read.');
				return true;
			}else{
				Session::set('error','Ooops!. Something went wrong.');
				return false;
			}
		}

	}

	public function __destruct(){

		if($this->db) $this->db->close();

	}

}

?>
