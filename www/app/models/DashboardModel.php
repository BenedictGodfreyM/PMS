<?php

class DashboardModel extends Database {

	protected $db;

	public $total_drugs = 0;

	public $today_sales = 0;

	public $num_sold_drugsToday = 0;

	public $this_month_sales = 0;

	public $expired_drugs = 0;

	public $near_expiry = 0;

	public $outOfStock = 0;

	public $nearOutOfStock = 0;

	public function __construct(){

		//Database Connection Instance
		$this->db = new Database;

		//Enabling Exceptions
		$this->db->enableExceptions();

		self::calcTotalDrugs();
		self::calcTodayTotalSales();
		self::calcNumOfDrugsSoldToday();
		self::calcThisMonthTotalSales();
		self::calcExpiredDrugs();
		self::calcNearExpiry();
		self::calcOutOfStockDrugs();
		self::calcNearOutOfStockDrugs();
	}


	/* -----------------------------------------------------------------
	------------------  Total Drugs present in store  ------------------
	------------------------------------------------------------------ */
	public function calcTotalDrugs(){

		$stmt = $this->db->prepare("SELECT * FROM drugs");
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		$this->total_drugs = $count;

	}

	/* -----------------------------------------------------------------
	------------------  Total Sales(Tsh) for Today  --------------------
	------------------------------------------------------------------ */
	public function calcTodayTotalSales(){

		$stmt = $this->db->prepare("SELECT sales_id, sum(total_amount) as total_amount, date_of_transaction FROM sales WHERE date(date_of_transaction) = ? GROUP BY sales_id");
		$stmt->bindValue(1, date("Y-m-d", strtotime("now")), SQLITE3_TEXT);
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
				$this->today_sales += $row['total_amount'];
			}
		}

	}

	/* -----------------------------------------------------------------
	-----------------=----  Total Drugs Sold Today  --------------------
	------------------------------------------------------------------ */
	public function calcNumOfDrugsSoldToday(){

		$stmt = $this->db->prepare("SELECT qty_sold, total_amount, date_of_transaction FROM sales WHERE date(date_of_transaction) = ?");
		$stmt->bindValue(1, date("Y-m-d", strtotime("now")), SQLITE3_TEXT);
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
				$this->num_sold_drugsToday += $row['qty_sold'];
			}
		}

	}

	/* -----------------------------------------------------------------
	----------------  Total Sales(Tsh) for this Month  -----------------
	------------------------------------------------------------------ */
	public function calcThisMonthTotalSales(){

		$stmt = $this->db->prepare("SELECT sales_id, sum(total_amount) as total_amount, date_of_transaction FROM sales WHERE strftime('%Y', date_of_transaction) = ? AND strftime('%m', date_of_transaction) = ? GROUP BY sales_id");
		$stmt->bindValue(1, date("Y", strtotime("now")), SQLITE3_TEXT);
    $stmt->bindValue(2, date("m", strtotime("now")), SQLITE3_TEXT);
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
				$this->this_month_sales += $row['total_amount'];
			}
		}

	}

	/* -----------------------------------------------------------------
	--------------------  Number of Expired Drugs  ---------------------
	------------------------------------------------------------------ */
	public function calcExpiredDrugs(){

		$stmt = $this->db->prepare("SELECT b.drug_id, b.expiry_date, sum(b.available_qty) as qty, d.drug_id FROM Batches as b INNER JOIN drugs as d ON b.drug_id = d.drug_id WHERE date(b.expiry_date) < ? GROUP BY b.drug_id HAVING qty != ?");
		$stmt->bindValue(1, date("Y-m-d", strtotime("now")), SQLITE3_TEXT);
    $stmt->bindValue(2, 0, SQLITE3_INTEGER);
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $this->expired_drugs = $count;
      }
		}

	}

	/* -----------------------------------------------------------------
	------------------  Number of Drugs near Expiry  -------------------
	------------------------------------------------------------------ */
	public function calcNearExpiry(){

		$stmt = $this->db->prepare("SELECT b.drug_id, b.expiry_date, sum(b.available_qty) as qty, d.drug_id FROM Batches as b INNER JOIN drugs as d ON b.drug_id = d.drug_id WHERE date(b.expiry_date) BETWEEN ? AND ? GROUP BY b.drug_id HAVING qty != ?");
		$stmt->bindValue(1, date("Y-m-d", strtotime("now")), SQLITE3_TEXT);
    $stmt->bindValue(2, date("Y-m-d", strtotime("+6 Months")), SQLITE3_TEXT);
    $stmt->bindValue(3, 0, SQLITE3_INTEGER);
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $this->near_expiry = $count;
      }
		}

	}

	/* -----------------------------------------------------------------
	----------------  Number of Drugs Out of Stock  --------------------
	------------------------------------------------------------------ */
	public function calcOutOfStockDrugs(){

		$stmt = $this->db->prepare("SELECT b.drug_id, b.expiry_date, sum(b.available_qty) as qty, d.drug_id FROM Batches as b INNER JOIN drugs as d ON b.drug_id = d.drug_id GROUP BY b.drug_id HAVING date(b.expiry_date) > ? AND qty = ?");
		$stmt->bindValue(1, date("Y-m-d", strtotime("now")), SQLITE3_TEXT);
    $stmt->bindValue(2, 0, SQLITE3_INTEGER);
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $this->outOfStock = $count;
      }
		}

	}

	/* -----------------------------------------------------------------
	-----  Number of Drugs Near Out of Stock(NOT IMPLEMENTED) ---------
	------------------------------------------------------------------ */
	public function calcNearOutOfStockDrugs(){

		$stmt = $this->db->prepare("SELECT batch_id, available_qty FROM Batches WHERE available_qty > ? AND available_qty <= ?");
		$stmt->bindValue(1, 0, SQLITE3_INTEGER);
    $stmt->bindValue(2, 15, SQLITE3_INTEGER);
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			$this->nearOutOfStock = $count;
		}

	}

	public function __destruct(){

		if($this->db) $this->db->close();

	}

}

?>
