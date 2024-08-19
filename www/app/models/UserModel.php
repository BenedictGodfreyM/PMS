<?php

class UserModel extends Database{

	protected $db;

	public $user_accounts = [];

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

	public function valid_email($str){
	  return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? false : true;
	}

	public function normalUserStillHasDefaultPassword(){
		$id = Session::get('id');
		$privileges = "user";
		$stmt = $this->db->prepare("SELECT password FROM users WHERE user_id = ? AND privileges = ?");
		$stmt->bindValue(1, $id, SQLITE3_TEXT);
		$stmt->bindValue(2, $privileges, SQLITE3_TEXT);
		$result = $stmt->execute();
		$password = "";
		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$password = $row['password'];
		}
		$passCheck = password_verify("12345", $password);
		if($passCheck == true){
			return true;
		}else{
			return false;
		}
	}

	public function changeUserDetails(){

		if($_SERVER['REQUEST_METHOD'] != 'POST'){
			return false;
			exit();
		}

		if (!isset($_POST['id']) || !isset($_POST['username']) || !isset($_POST['firstname']) || !isset($_POST['lastname']) || !isset($_POST['email'])) {
			Session::set('error','Oooops! Error in System configurations.');
			return false;
			exit();
		}

		//Data from the form
		$id = $_POST['id'];
		$username = $_POST['username'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];

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

			$check_email = $this->db->prepare("SELECT * FROM users WHERE email = ? AND user_id <> ?");
			$check_email->bindValue(1, $email, SQLITE3_TEXT);
			$check_email->bindValue(2, $id, SQLITE3_TEXT);
			$results = $check_email->execute();
			$count = 0;
			while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
				$count++;
			}
			if ($count > 0){
				Session::set('warning','Sorry! The E-Mail Address is already in use.');
				return false;
				exit();
			}
		}

		if(Session::get('isAdmin') == false){
			$privileges = 'user';
		}else if(Session::get('isAdmin') == true){
			$privileges = 'admin';
		}

		$stmt = $this->db->prepare("UPDATE users SET username = ?, firstname = ?, lastname = ?, email = ? WHERE user_id = ? AND privileges = ?");
		$stmt->bindValue(1, $username, SQLITE3_TEXT);
		$stmt->bindValue(2, $firstname, SQLITE3_TEXT);
		$stmt->bindValue(3, $lastname, SQLITE3_TEXT);
		$stmt->bindValue(4, $email, SQLITE3_TEXT);
		$stmt->bindValue(5, $id, SQLITE3_TEXT);
		$stmt->bindValue(6, $privileges, SQLITE3_TEXT);
		$result = $stmt->execute();
		$changed_rows = $this->db->changes();

		if ($changed_rows > 0) {
			//Reset Session Variables
			Session::set('username', $username);
			Session::set('firstname', $firstname);
			Session::set('lastname', $lastname);
			Session::set('email', $email);

			Session::set('success','Details updated successfully.');
			return true;
		}else{
			Session::set('error','Ooops!!! Something went wrong.');
			return false;
		}

	}

	public function changePassword(){

		if($_SERVER['REQUEST_METHOD'] != 'POST'){
			return false;
			exit();
		}

		if (!isset($_POST['id']) || !isset($_POST['old_pass']) || !isset($_POST['new_pass']) || !isset($_POST['confirm_pass'])) {
			Session::set('error','Oooops! Error in System configurations.');
			return false;
			exit();
		}

			//Data from the form
		$id = $_POST['id'];
		$old = $_POST['old_pass'];
		$new = $_POST['new_pass'];
		$confirm = $_POST['confirm_pass'];

		if(Session::get('isAdmin') == false){
			$privileges = 'user';
		}else if(Session::get('isAdmin') == true){
			$privileges = 'admin';
		}

		$stmt = $this->db->prepare("SELECT password FROM users WHERE user_id = ? AND privileges = ?");
		$stmt->bindValue(1, $id, SQLITE3_TEXT);
		$stmt->bindValue(2, $privileges, SQLITE3_TEXT);
		$result = $stmt->execute();

		$num = 0;
		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$num++;
		}

		if($num <= 0) {
			Session::set('error','Error! Invalid user token.');
			return false;
		}

		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$password_enc = $row['password'];
		}

		$pwdCheck = password_verify($old, $password_enc);
		if ($pwdCheck == false) {
			Session::set('error','Error! Invalid account password');
			return false;
		}

		if ($new !== $confirm) {
			Session::set('error','Error! Passwords do not match');
			return false;
		}

		$password = password_hash($new, PASSWORD_DEFAULT);

		$stmt = $this->db->prepare("UPDATE users SET password = ? WHERE user_id = ? AND privileges = ?");
		$stmt->bindValue(1, $password, SQLITE3_TEXT);
		$stmt->bindValue(2, $id, SQLITE3_TEXT);
		$stmt->bindValue(3, $privileges, SQLITE3_TEXT);
		$result = $stmt->execute();
		$changed_rows = $this->db->changes();

		if ($changed_rows > 0) {
			Session::set('success','Password Change successfully.');
			return true;
		}else{
			Session::set('error','Ooops!!! Something went wrong.');
			return false;
		}

	}

	public function addUser(){

		if($_SERVER['REQUEST_METHOD'] != 'POST'){
			return false;
			exit();
		}

		if (!isset($_POST['firstname']) || !isset($_POST['lastname']) || !isset($_POST['email'])) {
			Session::set('error','Oooops! Error in System configurations.');
			return false;
			exit();
		}

		//Data from the form
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];

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

			$check_email = $this->db->prepare("SELECT * FROM users WHERE email = ?");
			$check_email->bindValue(1, $email, SQLITE3_TEXT);
			$results = $check_email->execute();
			$count = 0;
			while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
				$count++;
			}
			if ($count > 0){
				Session::set('warning','Sorry! The E-Mail Address is already in use.');
				return false;
				exit();
			}
		}

		//Auto-Generated Details
		$id = strtolower(md5(uniqid(microtime(), false) . date("Y-m-d h:i:s", strtotime("now"))));
		$username = strtoupper($lastname);
		$password = password_hash('12345', PASSWORD_DEFAULT);
		$privileges = 'user';

		$stmt = $this->db->prepare("INSERT INTO users(user_id, username, password, firstname, lastname, email, privileges, date_created) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bindValue(1, $id, SQLITE3_TEXT);
		$stmt->bindValue(2, $username, SQLITE3_TEXT);
		$stmt->bindValue(3, $password, SQLITE3_TEXT);
		$stmt->bindValue(4, $firstname, SQLITE3_TEXT);
		$stmt->bindValue(5, $lastname, SQLITE3_TEXT);
		$stmt->bindValue(6, $email, SQLITE3_TEXT);
		$stmt->bindValue(7, $privileges, SQLITE3_TEXT);
		$stmt->bindValue(8, date("Y-m-d", strtotime("now")), SQLITE3_TEXT);
		$result = $stmt->execute();
		$inserted_rows = $this->db->changes();

		if ($inserted_rows > 0) {
			Session::set('success','New User Account Successfully Created.');
			return true;
		}else{
			Session::set('error','Ooops!!! Something went wrong.');
			return false;
		}

	}

	public function viewUserAccounts(){

		$stmt = $this->db->prepare("SELECT user_id, firstname, lastname, email, date_created FROM users WHERE privileges = ?");
		$stmt->bindValue(1, 'user', SQLITE3_TEXT);
		$result = $stmt->execute();
		$count = 0;
		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$count++;
		}
		if($count > 0){
			$i = 0;
			while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
				$this->user_accounts[$i] = $row;
				$i++;
			}
			return $this->user_accounts;
		}

	}

	public function resetDefaultPass($param){

		$stmt = $this->db->prepare("UPDATE users SET password = ? WHERE user_id = ? AND privileges = ?");
		$stmt->bindValue(1, password_hash('12345', PASSWORD_DEFAULT), SQLITE3_TEXT);
		$stmt->bindValue(2, $param, SQLITE3_TEXT);
		$stmt->bindValue(3, 'user', SQLITE3_TEXT);
		$result = $stmt->execute();
		$changed_rows = $this->db->changes();

		if ($changed_rows > 0) {
			Session::set('success','Password Reset successfully.');
			return true;
		}else{
			Session::set('error','Ooops!!! Something went wrong.');
			return false;
		}

	}

	public function deleteAccount($param){

		$stmt = $this->db->prepare("DELETE FROM users WHERE user_id = ? AND privileges = ?");
		$stmt->bindValue(1, $param, SQLITE3_TEXT);
		$stmt->bindValue(2, 'user', SQLITE3_TEXT);
		$result = $stmt->execute();
		$deleted_rows = $this->db->changes();

		if ($deleted_rows > 0) {
			Session::set('success','Account Deletion Successfully.');
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
