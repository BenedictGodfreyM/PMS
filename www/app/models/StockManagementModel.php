<?php

class StockManagementModel extends Database{

	protected $db;

	public $available_stock = [];

	public $list_of_cart_items = [];

	public $this_month_total_loss = 0;

	public $total_loss = 0;

	public $trash_list = [];

	public $this_month_total_purchases = 0;

	public $this_month_purchases_list = [];

	public $total_purchases = 0;

	public $purchases_list = [];

	public function __construct(){

		//Database Connection Instance
		$this->db = new Database;

		//Enabling Exceptions
		$this->db->enableExceptions();

	}

	public function availableStockRetrieval(){

		$stmt = $this->db->prepare("SELECT drug_id, brand_name, generic_name, unit_s_price FROM drugs");
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if($count > 0){
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->available_stock[$i] = $row;
      	$i++;
      }
			return $this->available_stock;
		}

	}

	public function getCartDetails($ids = []){

		$i = 0;
		foreach($ids as $id) {
			$stmt = $this->db->prepare("SELECT drug_id, brand_name, generic_name, unit_s_price FROM drugs WHERE drug_id = ? ORDER BY brand_name ASC");
			$stmt->bindValue(1, $id, SQLITE3_TEXT);
			$result = $stmt->execute();
			while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
				$this->list_of_cart_items[$i] = $row;
			}
			$i++;
		}
		$count = 0;
		foreach ($this->list_of_cart_items as $row) {
    	$count++;
    }
		if ($count > 0){
			return $this->list_of_cart_items;
		}else{
			return false;
		}

	}

	public function saveStockInDB(){

		if($_SERVER['REQUEST_METHOD'] != "POST")
		{
			return false;
			exit();
		}

		if (!isset($_POST['id']) || !isset($_POST['qty_bought']) || !isset($_POST['unit_cost']) || 
			!isset($_POST['total_cost']) || !isset($_POST['expiry_date']))
		{
			#Session::set('error','Oooops! Error in System configurations.');
			return false;
			exit();
		}

		#------- SET THE MAX EXECUTION TIME OF THE SCRIPT TO 5 mins (300 seconds) -------
		set_time_limit(300);

		//Data from the form
		$drug_ids = $_POST['id'];
		$expiry_dates = $_POST['expiry_date'];
		$added_quantities = $_POST['qty_bought'];
		$unit_costs = $_POST['unit_cost'];
		$buying_prices = $_POST['total_cost'];

		//Verify the expiry dates of the submitted Stock Details
		foreach ($expiry_dates as $expiry_date){
			if($expiry_date < date("Y-m-d", strtotime("now")))
			{
				Session::set('error','Oooops!!! One of Drug(s) in the newly added Stock have already expired. Please Go through your stock carefully');
				return false;
				exit();
			}
		}

		#---------------------------------------------------------------------------------------
		# ----------------------------- MERGING INDIVIDUAL ARRAYS -----------------------------
		#---------------------------------------------------------------------------------------
		/*Combining All the Details in Separate Arrays into one Array for Easy Database Entry*/
		$purchase_details = [];

		//Count the number of Drug IDs to Handle
		$rowCount = count($drug_ids);

		/* -- Auto Generate Purchase IDs -- */
		$i = 0;
		while ($i < $rowCount) {
			$purchase_details[$i] = array('batch_id' => strtolower(md5(uniqid($i, false) . date("Y-m-d h:i:s"))));
			$i++;
		}

		/* -- Drug IDs -- */
		$i = 0;
		foreach ($drug_ids as $drug_id) {
			if($i < $rowCount){
				$temp[$i] = array_merge($purchase_details[$i], array('drug_id' => $drug_id));
			}
			$i++;
		}
		$purchase_details = $temp;

		/* -- Expiry Date for each -- */
		$i = 0;
		foreach ($expiry_dates as $expiry_date) {
			if($i < $rowCount){
				$temp[$i] = array_merge($purchase_details[$i], array('expiry_date' => $expiry_date));
			}
			$i++;
		}
		$purchase_details = $temp;

		/* -- Qty Added for each -- */
		$i = 0;
		foreach ($added_quantities as $added_quantity) {
			if($i < $rowCount){
				$temp[$i] = array_merge($purchase_details[$i], array('qty_added' => $added_quantity));
			}
			$i++;
		}
		$purchase_details = $temp;

		/* -- Unit Cost for each -- */
		$i = 0;
		foreach ($unit_costs as $unit_cost) {
			if($i < $rowCount){
				$temp[$i] = array_merge($purchase_details[$i], array('unit_cost' => $unit_cost));
			}
			$i++;
		}
		$purchase_details = $temp;

		/* -- Total Cost for Each -- */
		$i = 0;
		foreach ($buying_prices as $buying_price) {
			if($i < $rowCount){
				$temp[$i] = array_merge($purchase_details[$i], array('total_cost' => $buying_price));
			}
			$i++;
		}
		$purchase_details = $temp;
		#---------------------------------------------------------------------------------------
		# ------------------------------- END OF MERGE OPERATION -------------------------------
		#---------------------------------------------------------------------------------------

		$i = 0;
		$results = [];
		//BEGIN Transaction
		$this->db->query("BEGIN");
		foreach($purchase_details as $purchase_detail) {
			$stmt = $this->db->prepare("INSERT INTO Batches(batch_id, expiry_date, qty_bought, total_cost, date_received, available_qty, drug_id, unit_b_price, status) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
					$stmt->bindValue(1, $purchase_detail['batch_id'], SQLITE3_TEXT);
	        $stmt->bindValue(2, $purchase_detail['expiry_date'], SQLITE3_TEXT);
	        $stmt->bindValue(3, $purchase_detail['qty_added'], SQLITE3_INTEGER);
	        $stmt->bindValue(4, $purchase_detail['total_cost'], SQLITE3_INTEGER);
        	$stmt->bindValue(5, date("Y-m-d h:i:s", strtotime("now")), SQLITE3_TEXT);
	        $stmt->bindValue(6, $purchase_detail['qty_added'], SQLITE3_INTEGER);
	        $stmt->bindValue(7, $purchase_detail['drug_id'], SQLITE3_TEXT);
	        $stmt->bindValue(8, $purchase_detail['unit_cost'], SQLITE3_INTEGER);
					$status = 0;
					if(Session::get('isAdmin') == true) $status = 1;
	        $stmt->bindValue(9, $status, SQLITE3_INTEGER);
			$results[$i] = $stmt->execute();
			$i++;
		}
		//Get the number of rows inserted into the database[$this->db->changes()]
		$num = count($results);

		//Check if there was any error during data entry(using the array of results)
		if (!array_search(0, $results, true)) {
				//Close all database transactions
				$this->db->query("COMMIT");
				Session::set('success','Success!. '.$num.' Drug Batche(s) were added in stock.');
				return true;
		}
		else
		{
				//Reverse all database transactions
				$this->db->query("ROLLBACK");
				Session::set('error','Ooops!!! Something went wrong. One of the Transactions was not compleleted. Please confirm the failed transaction(s) before repeating the procedure');
				return false;
		}

	}

	public function moveToTrash($id = ''){

		if(!empty($id))
		{
			#------- SET THE MAX EXECUTION TIME OF THE SCRIPT TO 5 mins (300 seconds) -------
			set_time_limit(300);

			//Retrieve all expired batches from the Batches-table
			$today = date("Y-m-d");
			$stmt = $this->db->prepare("SELECT drug_id, available_qty, batch_id, expiry_date, unit_b_price FROM Batches WHERE drug_id = ? AND date(expiry_date) < ? AND available_qty != ?");
      $stmt->bindValue(1, $id, SQLITE3_TEXT);
      $stmt->bindValue(2, $today, SQLITE3_TEXT);
      $stmt->bindValue(3, 0, SQLITE3_INTEGER);
			$result = $stmt->execute();

			$count = 0;
	        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
	          $count++;
	        }

			//Begin database transaction
			$this->db->query("BEGIN");
			$results = [];
			if ($count > 0)
			{
				$i = 0;
				while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
					$stmt = $this->db->prepare("INSERT INTO trash(drug_id, batch_id, date_of_end_of_use, date_trashed, qty_trashed, unit_loss, total_loss, status) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
	        $stmt->bindValue(1, $row['drug_id'], SQLITE3_TEXT);
	        $stmt->bindValue(2, $row['batch_id'], SQLITE3_TEXT);
	        $stmt->bindValue(3, $row['expiry_date'], SQLITE3_TEXT);
	        $stmt->bindValue(4, date("Y-m-d h:i:s", strtotime("now")), SQLITE3_TEXT);
	        $stmt->bindValue(5, $row['available_qty'], SQLITE3_INTEGER);
	        $stmt->bindValue(6, $row['unit_b_price'], SQLITE3_INTEGER);
					$total_loss = $row['available_qty'] * $row['unit_b_price'];
	        $stmt->bindValue(7, $total_loss, SQLITE3_INTEGER);
					$status = 0;
					if(Session::get('isAdmin') == true) $status = 1;
	        $stmt->bindValue(8, $status, SQLITE3_INTEGER);
					$results[$i] = $stmt->execute();
					$i++;
				}
			}

			//Check if there was any error during data entry(using the array of results)
			//Then Delete all expired batches in Batches-table associated with the specific drug
			if (!empty($results) && !array_search(0, $results, true))
			{
				while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
					$stmt = $this->db->prepare("UPDATE Batches SET available_qty = ? WHERE batch_id = ?");
	        $stmt->bindValue(1, 0, SQLITE3_INTEGER);
	        $stmt->bindValue(2, $row['batch_id'], SQLITE3_TEXT);
					$result = $stmt->execute();
				}

				//Close database transaction
				$this->db->query("COMMIT");
			}
			else
			{
				//Reverse all database transaction
				$this->db->query("ROLLBACK");
				Session::set('warning','Ooops!!! Something went wrong. Unable to move the batch to trash.');
				return false;
			}
		}

	}

	public function calThisMonthTotalLoses(){

		$stmt = $this->db->prepare("SELECT id, date_of_end_of_use, sum(total_loss) as all_loses FROM trash WHERE strftime('%Y', date_of_end_of_use) = ? AND strftime('%m', date_of_end_of_use) = ? GROUP BY id");
		$stmt->bindValue(1, date("Y"), SQLITE3_TEXT);
		$stmt->bindValue(2, date("m"), SQLITE3_TEXT);
		$result = $stmt->execute();
		$count = 0;
		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$count++;
		}
		if ($count > 0){
			while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
				$this->this_month_total_loss += $row['all_loses'];
			}
			return $this->this_month_total_loss;
		}

	}

	public function calTotalLoses(){

		$stmt = $this->db->prepare("SELECT id, sum(total_loss) as all_loses FROM trash GROUP BY id");
		$result = $stmt->execute();
		$count = 0;
		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$count++;
		}
		if ($count > 0){
			while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
				$this->total_loss += $row['all_loses'];
			}
			return $this->total_loss;
		}

	}

	public function custom_trash_search_total(){

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			$stmt = $this->db->prepare("SELECT id, date_of_end_of_use, sum(total_loss) as total FROM trash WHERE date(date_of_end_of_use) BETWEEN ? AND ? GROUP BY id");
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
							<a class='nav-link active'>Total Loss: Tsh ".$total."/=</a>
						</li>
						<li class='nav-item'>
							<a class='nav-link' href='?url=dataexport/toPDF/trash/".$_POST['d1']."/".$_POST['d2']."' target='_blank'>
								<i class='fas fa-file-pdf'> Export to PDF</i>
							</a>
						</li>
					";
				}
				else
				{
					$output .= "
						<li class='nav-item'>
							<a class='nav-link active'>Total Loss: Tsh 0/=</a>
						</li>
					";
				}
				return $output;
			}

		}

	}

	public function custom_trash_search($d1 = '', $d2 = ''){

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			$stmt = $this->db->prepare("SELECT t.id, t.drug_id, t.qty_trashed, t.date_of_end_of_use, t.date_trashed, t.qty_trashed, t.unit_loss, t.total_loss, d.drug_id, d.brand_name, d.generic_name FROM trash as t LEFT JOIN drugs as d ON t.drug_id = d.drug_id WHERE date(t.date_of_end_of_use) BETWEEN ? AND ? ORDER BY t.date_of_end_of_use ASC");
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
								<td>".$row['date_trashed']."</td>
								<td>".$row['date_of_end_of_use']."</td>
								<td>".$row['qty_trashed']."</td>
								<td>".$row['unit_loss']."</td>
								<td>".$row['total_loss']."</td>
							</tr>
					";
					$i++;
				}
			}
			$search_results = array(
				'header' => self::custom_trash_search_total(),
				'list_data'  => $output
			);
			echo json_encode($search_results);
			return true;

		}

		if($_SERVER['REQUEST_METHOD'] == 'GET') {

			$stmt = $this->db->prepare("SELECT d.brand_name, d.generic_name, t.date_trashed, t.date_of_end_of_use, t.qty_trashed, t.unit_loss, t.total_loss FROM trash as t LEFT JOIN drugs as d ON t.drug_id = d.drug_id WHERE date(t.date_of_end_of_use) BETWEEN ? AND ? ORDER BY t.date_of_end_of_use ASC");
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

	public function list_items_in_trash(){

		$stmt = $this->db->prepare("SELECT t.id, t.drug_id, t.qty_trashed, t.date_of_end_of_use, t.date_trashed, t.qty_trashed, t.unit_loss, t.total_loss, d.drug_id, d.brand_name, d.generic_name, d.strength FROM trash as t LEFT JOIN drugs as d ON t.drug_id = d.drug_id ORDER BY t.date_trashed ASC");
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->trash_list[$i] = $row;
      	$i++;
      }
			return $this->trash_list;
		}

	}

	public function calThisMMonthTotalPurchases(){

		$stmt = $this->db->prepare("SELECT batch_id, date_received, sum(total_cost) as total_purchases FROM Batches WHERE strftime('%Y', date_received) = ? AND strftime('%m', date_received) = ? GROUP BY batch_id");
    $stmt->bindValue(1, date("Y"), SQLITE3_TEXT);
    $stmt->bindValue(2, date("m"), SQLITE3_TEXT);
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->this_month_total_purchases += $row['total_purchases'];
      	$i++;
      }
			return $this->this_month_total_purchases;
		}

	}

	public function this_month_purchases(){

		$stmt = $this->db->prepare("SELECT d.drug_id, d.brand_name, d.generic_name, d.strength, b.batch_id, b.date_received, b.expiry_date, b.qty_bought, b.unit_b_price, b.total_cost FROM Batches as b LEFT JOIN drugs as d ON b.drug_id = d.drug_id WHERE strftime('%Y', b.date_received) = ? AND strftime('%m', b.date_received) = ? GROUP BY b.batch_id ORDER BY b.date_received DESC");
    $stmt->bindValue(1, date("Y"), SQLITE3_TEXT);
    $stmt->bindValue(2, date("m"), SQLITE3_TEXT);
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->this_month_purchases_list[$i] = $row;
      	$i++;
      }
			return $this->this_month_purchases_list;
		}

	}

	public function calTotalPurchases(){

		$stmt = $this->db->prepare("SELECT batch_id, sum(total_cost) as total_purchases FROM Batches GROUP BY batch_id");
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->total_purchases += $row['total_purchases'];
      	$i++;
      }
			return $this->total_purchases;
		}

	}

	public function custom_purchases_search_total(){

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			$stmt = $this->db->prepare("SELECT batch_id, date_received, sum(total_cost) as total FROM Batches WHERE date(date_received) BETWEEN ? AND ? GROUP BY batch_id");
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
								<a class='nav-link' href='?url=dataexport/toPDF/purchases/".$_POST['d1']."/".$_POST['d2']."' target='_blank'>
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
			}
			return $output;

		}

	}

	public function custom_purchases_search($d1 = '', $d2 = ''){

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			$stmt = $this->db->prepare("SELECT d.drug_id, d.brand_name, d.generic_name, d.strength, b.batch_id, b.date_received, b.expiry_date, b.qty_bought, b.unit_b_price, b.total_cost FROM Batches as b LEFT JOIN drugs as d ON b.drug_id = d.drug_id WHERE date(b.date_received) BETWEEN ? AND ? ORDER BY b.date_received ASC");
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
								<td>".$row['date_received']."</td>
								<td>".$row['expiry_date']."</td>
								<td>".$row['strength']."</td>
								<td>".$row['qty_bought']."</td>
								<td>".$row['unit_b_price']."</td>
								<td>".$row['total_cost']."</td>
							</tr>
						";
					$i++;
				}
			}
			$search_results = array(
				'header' => self::custom_purchases_search_total(),
				'list_data'  => $output
			);
			echo json_encode($search_results);
			return true;

		}

		if($_SERVER['REQUEST_METHOD'] == 'GET') {

			$stmt = $this->db->prepare("SELECT d.brand_name, d.generic_name, d.strength, b.date_received, b.expiry_date, b.qty_bought, b.unit_b_price, b.total_cost FROM Batches as b LEFT JOIN drugs as d ON b.drug_id = d.drug_id WHERE date(b.date_received) BETWEEN ? AND ? ORDER BY b.date_received ASC");
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

	public function list_all_purchases(){

		$stmt = $this->db->prepare("SELECT d.drug_id, d.brand_name, d.generic_name, d.strength, b.batch_id, b.date_received, b.expiry_date, b.qty_bought, b.unit_b_price, b.total_cost FROM Batches as b LEFT JOIN drugs as d ON b.drug_id = d.drug_id ORDER BY b.date_received DESC");
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->purchases_list[$i] = $row;
      	$i++;
      }
			return $this->purchases_list;
		}

	}

	public function listPurchasesBYid($param){

		$stmt = $this->db->prepare("SELECT d.drug_id, d.brand_name, d.generic_name, d.strength, d.unit_s_price, b.batch_id, b.date_received, b.expiry_date, b.qty_bought, b.unit_b_price, b.total_cost FROM Batches as b LEFT JOIN drugs as d ON b.drug_id = d.drug_id WHERE batch_id = ? ORDER BY b.date_received DESC");
		$stmt->bindValue(1, $param, SQLITE3_TEXT);
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->purchases_list[$i] = $row;
      	$i++;
      }
			return $this->purchases_list;
		}

	}

	public function editStockRecord($param){

		if($_SERVER['REQUEST_METHOD'] != "POST")
		{
			return false;
			exit();
		}

		if (!isset($_POST['Bid']) || !isset($_POST['selling_price']) || !isset($_POST['expiry_date']) || !isset($_POST['qty_bought']) ||
			!isset($_POST['unit_cost']) || !isset($_POST['total_cost'])) {
			#Session::set('error','Oooops! Error in System configurations.');
			return false;
			exit();
		}

		//Data from the form
		$id = $_POST['Bid'];
		$expiry_date = $_POST['expiry_date'];
		$qty_bought = $_POST['qty_bought'];
		$selling_price = $_POST['selling_price'];
		$unit_cost = $_POST['unit_cost'];
		$total_cost = $_POST['total_cost'];

		//Create a session for the edited stock record
		Session::set('edit_stock', $id);

		//Verify the expiry date of the submitted Stock Details
		if($expiry_date < date("Y-m-d", strtotime("now")))
		{
			Session::set('error','Oooops!!! Invalid Stock Expiry Date.');
			return false;
			exit();
		}

		//Verify the quantity of the submitted Stock Details
		if(! is_numeric($qty_bought))
		{
			Session::set('error','Oooops!!! Invalid Stock Quantity.');
			return false;
			exit();
		}

		//Verify the purchasing price of the submitted Stock Details
		if($selling_price < $unit_cost)
		{
			Session::set('error','Error! The provided Unit Purchasing Cost of this Drug is greater than the existing Selling Price of this Drug.');
			return false;
			exit();
		}

		$stmt = $this->db->prepare("UPDATE Batches SET expiry_date = ?, qty_bought = ?, unit_b_price = ?, total_cost = ? WHERE batch_id = ?");
		$stmt->bindValue(1, $expiry_date, SQLITE3_TEXT);
    $stmt->bindValue(2, $qty_bought, SQLITE3_INTEGER);
    $stmt->bindValue(3, $unit_cost, SQLITE3_INTEGER);
    $stmt->bindValue(4, $total_cost, SQLITE3_INTEGER);
    $stmt->bindValue(5, $id, SQLITE3_TEXT);
		$result = $stmt->execute();

		if ($result) {
			Session::set('success','Stock Details was updated successfully.');
			unset($_POST);
			Session::delete('edit_stock');
			return true;
		}else{
			Session::set('warning','Ooops!!! Something went wrong.');
			return false;
		}

	}

	public function deleteStockRecord($param){

		$stmt = $this->db->prepare("DELETE FROM Batches WHERE batch_id = ?");
    $stmt->bindValue(1, $param, SQLITE3_TEXT);
		$result = $stmt->execute();

		if ($result){
			Session::set('success','Stock Record was Removed Permanently.');
			return true;
		}else{
			Session::set('warning','Ooops!!! Something went wrong.');
			return false;
		}

	}

	public function __destruct(){

		if($this->db) $this->db->close();

	}

}

?>
