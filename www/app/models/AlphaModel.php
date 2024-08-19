<?php

/**
 *
 */
class AlphaModel extends Database{

  public $admin_info_in_db = false;

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

  public function valid_email($str){
    return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? false : true;
  }

  public function is_admin_present(){

    $stmt = $this->db->prepare("SELECT * FROM users WHERE privileges = ?");
    $stmt->bindValue(1, 'admin', SQLITE3_TEXT);
		$result = $stmt->execute();
		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }

    switch (true) {
      case ($count <= 0):
        $this->admin_info_in_db = false;
        break;
      case ($count == 1):
        $this->admin_info_in_db = true;
        break;
      default:
        $this->admin_info_in_db = false;
        break;
    }
    return $this->admin_info_in_db;

  }

  public function register_admin(){

    if($_SERVER['REQUEST_METHOD'] != 'POST'){
      return false;
      exit();
    }

    if (!isset($_POST['firstname']) || !isset($_POST['lastname']) || !isset($_POST['email']) || !isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['confirm_password'])) {
      Session::set('error','Oooops! Error in System configurations.');
      return false;
      exit();
    }

    //Data from the form
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (self::filterString($firstname) == false) {
      Session::set('error','Error! Enter a pure string without characters like commas, question marks, etc for First Name.');
      return false;
      exit();
    }

    if (self::filterString($lastname) == false) {
      Session::set('error','Error! Enter a pure string without characters like commas, question marks, etc for Last Name.');
      return false;
      exit();
    }

		if(!empty($email)){
			if (self::valid_email($email) == false) {
				Session::set('error','Error! Invalid E-Mail Address.');
				return false;
				exit();
			}
		}

    //Chech if the email account is already in use by another user
    $stmt = $this->db->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bindValue(1, $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    $count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
    if($count > 0) {
      Session::set('error','Email account already in use.');
      return false;
    }

    //Check if passwords match
    if ($password != $confirm_password) {
      Session::set('error','Passwords do not match.');
      return false;
    }
    $password = password_hash($password, PASSWORD_DEFAULT);

    //Auto-Generated Details
    $id = strtolower(md5(uniqid(microtime(), false) . date("Y-m-d h:i:s", strtotime("now"))));

    //Save Data into the Database(Activation successful)
    $stmt = $this->db->prepare("INSERT INTO users(user_id, username, password, firstname, lastname, email, privileges, date_created) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bindValue(1, $id, SQLITE3_TEXT);
    $stmt->bindValue(2, $username, SQLITE3_TEXT);
    $stmt->bindValue(3, $password, SQLITE3_TEXT);
    $stmt->bindValue(4, $firstname, SQLITE3_TEXT);
    $stmt->bindValue(5, $lastname, SQLITE3_TEXT);
    $stmt->bindValue(6, $email, SQLITE3_TEXT);
    $stmt->bindValue(7, 'admin', SQLITE3_TEXT);
    $stmt->bindValue(8, date("Y-m-d", strtotime("now")), SQLITE3_TEXT);
    $result = $stmt->execute();
    $inserted_rows = $this->db->changes();

    if ($inserted_rows > 0) {
      Session::destroy();
      Session::set('success','Administrator Account Successfully Created.');
      return true;
    }else{
      Session::set('error','Ooops!!! Something went wrong.');
      return false;
    }

  }

	public function __destruct(){

		if($this->db) $this->db->close();

	}

}

?>
