<?php

/**
 *
 */
class GraphStatistics extends Database{

  protected $db;

  public function __construct(){

    parent::__construct();

    //Database Connection Instance
    $this->db = new Database;

    //Enabling Exceptions
    $this->db->enableExceptions();

  }

  /* --- nth WEEK --- */
  public function weekSalesStatistics($param){

    //Start Date
    $d=strtotime($param);
    for ($i=0; $i<7; $i++) {
      $timestamp = strtotime('+'.$i.' Days', $d);
      $date = date("Y-m-d", $timestamp);
      $stmt = $this->db->prepare("SELECT sum(total_amount) as nth_week_total, date_of_transaction FROM Sales WHERE date(date_of_transaction) = '$date' GROUP BY date_of_transaction ORDER BY date_of_transaction ASC");
      $result = $stmt->execute();
      $count = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $count++;
      }
      if($count <= 0) {
        echo $count.", ";
      }else{
        $nth_week_total = 0;
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
          $nth_week_total += $row['nth_week_total'];
        }
        echo $nth_week_total;
        if ($i < 7) {echo ", ";}
      }
    }
  }

  /* --- nth YEAR --- */
  public function yearlySalesStatistics($param){

    //Start Month
    $m=strtotime($param);
    for ($i=0; $i<12; $i++) {
      $timestamp = strtotime('+'.$i.' Months', $m);
      $month = date("Y-m", $timestamp);
      $stmt = $this->db->prepare("SELECT sum(total_amount) as nth_month_total, date_of_transaction FROM Sales WHERE strftime('%Y-%m', date_of_transaction) = '$month' GROUP BY date_of_transaction ORDER BY date_of_transaction ASC");
      $result = $stmt->execute();
      $count = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $count++;
      }
      if($count <= 0) {
        echo $count.", ";
      }else{
        $nth_month_total = 0;
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
          $nth_month_total += $row['nth_month_total'];
        }
        echo $nth_month_total;
        if ($i < 12) {echo ", ";}
      }
    }
  }

	public function __destruct(){

		if($this->db) $this->db->close();

	}

}

?>
