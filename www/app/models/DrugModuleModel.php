<?php

class DrugModuleModel extends Database{

	protected $db;

	public $drug_list = [];

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

	/* -----------------------------------------------------------------
	----------------  List of Available Drugs in Store  ----------------
	------------------------------------------------------------------ */
	public function listDrugs(){

		$stmt = $this->db->prepare("SELECT d.drug_id as d_id, d.brand_name, d.generic_name, d.strength, d.unit_s_price, b.drug_id, sum(b.available_qty) as qty, d.updated_on FROM drugs as d LEFT JOIN Batches as b ON d.drug_id = b.drug_id GROUP BY d.drug_id ORDER BY d.updated_on DESC");
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->drug_list[$i] = $row;
      	$i++;
      }
			return $this->drug_list;
		}

	}

	/* -----------------------------------------------------------------
	-------------------  Request Drug Details by ID  -------------------
	------------------------------------------------------------------ */
	public function listDrugBYid($param){

		$stmt = $this->db->prepare("SELECT drug_id, brand_name, generic_name, strength, unit_s_price FROM drugs WHERE drug_id = ?");
		$stmt->bindValue(1, $param, SQLITE3_TEXT);
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->drug_list[$i] = $row;
      	$i++;
      }
			return $this->drug_list;
		}

	}
	public function listDrugDetailsForSale($param){

		$stmt = $this->db->prepare("SELECT d.drug_id as d_id, d.brand_name, d.generic_name, d.strength, d.unit_s_price, b.drug_id, sum(b.available_qty) as qty, b.expiry_date, d.updated_on FROM drugs as d LEFT JOIN Batches as b ON d.drug_id = b.drug_id GROUP BY d.drug_id HAVING d.drug_id = ? AND date(b.expiry_date) > ? AND qty > ?");
		$stmt->bindValue(1, $param, SQLITE3_TEXT);
		$stmt->bindValue(2, date("Y-m-d", strtotime("now")), SQLITE3_TEXT);
		$stmt->bindValue(3, 0, SQLITE3_INTEGER);
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0){
			$i = 0;
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      	$this->drug_list[$i] = $row;
      	$i++;
      }
			return $this->drug_list;
		}

	}

	public function addDrug(){

		if($_SERVER['REQUEST_METHOD'] != "POST")
		{
			return false;
			exit();
		}

		if (!isset($_POST['brand']) || !isset($_POST['name']) || !isset($_POST['strength']) || 
			!isset($_POST['price'])) {
			#Session::set('error','Oooops! Error in System configurations.');
			return false;
			exit();
		}

		//Data from the form
		$brand = self::test_input($_POST['brand']);
		$name = self::test_input($_POST['name']);
		$strength = self::test_input($_POST['strength']);
		$price = self::test_input($_POST['price']);

		if (self::filterString($brand) == false) {
			Session::set('error','Error! Enter a pure string without characters like commas, question marks, etc for Brand Name.');
			return false;
			exit();
		}

		if (self::filterString($name) == false) {
			Session::set('error','Error! Enter a pure string without characters like commas, question marks, etc for Generic Name.');
			return false;
			exit();
		}

		if (strlen($price) > 10) {
			Session::set('error','Error! Figures on the price tag should be less than 10.');
			return false;
			exit();
		}

		//Auto generated data
		$id = strtolower(md5(uniqid(microtime(), false) . date("Y-m-d h:i:s", strtotime("now"))));

		$stmt = $this->db->prepare("INSERT INTO drugs(drug_id, brand_name, generic_name, strength, unit_s_price, updated_on) VALUES(?, ?, ?, ?, ?, ?)");
		$stmt->bindValue(1, $id, SQLITE3_TEXT);
    $stmt->bindValue(2, $brand, SQLITE3_TEXT);
    $stmt->bindValue(3, $name, SQLITE3_TEXT);
    $stmt->bindValue(4, $strength, SQLITE3_TEXT);
    $stmt->bindValue(5, $price, SQLITE3_INTEGER);
    $stmt->bindValue(6, date("Y-m-d h:i:s", strtotime("now")), SQLITE3_TEXT);
		$result = $stmt->execute();

		if ($result) {
			Session::set('success','Drug was added successfully.');
			unset($_POST);
			return true;
		}else{
			Session::set('warning','Ooops!!! Something went wrong.');
			return false;
		}

	}

	public function editDrug(){

		if($_SERVER['REQUEST_METHOD'] != "POST")
		{
			return false;
			exit();
		}

		if (!isset($_POST['Did']) || !isset($_POST['brand']) || !isset($_POST['name']) ||
			!isset($_POST['strength']) || !isset($_POST['price'])) {
			#Session::set('error','Oooops! Error in System configurations.');
			return false;
			exit();
		}

		//Data from the form
		$id = strtolower(self::test_input($_POST['Did']));
		$brand = self::test_input($_POST['brand']);
		$name = self::test_input($_POST['name']);
		$strength = self::test_input($_POST['strength']);
		$price = self::test_input($_POST['price']);

		//Create a session for the edited drug
		Session::set('edit_drug', $id);

		if (self::filterString($brand) == false) {
			Session::set('error','Error! Enter a pure string without characters like commas, question marks, etc for Brand Name.');
			return false;
			exit();
		}

		if (self::filterString($name) == false) {
			Session::set('error','Error! Enter a pure string without characters like commas, question marks, etc for Generic Name.');
			return false;
			exit();
		}

		if (strlen($price) > 10) {
			Session::set('error','Error! Figures on the price tag should be less than 10.');
			return false;
			exit();
		}

		$stmt = $this->db->prepare("UPDATE drugs SET brand_name = ?, generic_name = ?, strength = ?, unit_s_price = ?, updated_on = ? WHERE drug_id = ?");
		$stmt->bindValue(1, $brand, SQLITE3_TEXT);
    $stmt->bindValue(2, $name, SQLITE3_TEXT);
    $stmt->bindValue(3, $strength, SQLITE3_TEXT);
    $stmt->bindValue(4, $price, SQLITE3_INTEGER);
    $stmt->bindValue(5, date("Y-m-d h:i:s", strtotime("now")), SQLITE3_TEXT);
    $stmt->bindValue(6, $id, SQLITE3_TEXT);
		$result = $stmt->execute();

		if ($result) {
			Session::set('success','Drug Details was updated successfully.');
			unset($_POST);
			Session::delete('edit_drug');
			return true;
		}else{
			Session::set('warning','Ooops!!! Something went wrong.');
			return false;
		}

	}

	public function deleteDrug($param){

		$stmt = $this->db->prepare("DELETE FROM drugs WHERE drug_id = ?");
    $stmt->bindValue(1, $param, SQLITE3_TEXT);
		$result = $stmt->execute();

		if ($result){
			Session::set('success','One of Drugs was Removed from Store.');
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
