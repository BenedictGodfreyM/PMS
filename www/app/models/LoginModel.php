<?php

class LoginModel extends Database {

	protected $db;

	public $feedback;

	public function __construct(){

		//Database Connection Instance
		$this->db = new Database;

		//Enabling Exceptions
		$this->db->enableExceptions();

		$this->run();

	}

	public function run(){

		if($_SERVER['REQUEST_METHOD'] != "POST")
		{
			return false;
			exit();
		}

		if (!isset($_POST['form_login']) || !isset($_POST['username']) || !isset($_POST['password']))
		{
			$message = "Oooops!!! Error in System configurations";
			$this->feedback = "<div class='alert alert-danger'><p class='text-center'>".$message."</p></div>";

			return false;
			exit();
		}

		if (empty($_POST['username']))
		{
			$this->feedback = "Username cannot be empty";

			return false;
			exit();
		}

		if (empty($_POST['password']))
		{
			$this->feedback = "Password cannot be empty";

			return false;
			exit();
		}

		/* ----- USER AUTHENTICATION ----- */
		$stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
		$stmt->bindValue(1, $_POST['username'], SQLITE3_TEXT);
		$result = $stmt->execute();

		$count = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $count++;
    }
		if ($count > 0)
		{
			while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
				$id = $row['user_id'];
				$username = $row['username'];
				$password = $row['password'];
				$firstname = $row['firstname'];
				$lastname = $row['lastname'];
				$email = $row['email'];
				$privileges = $row['privileges'];
			}

			$passCheck = password_verify($_POST['password'], $password);
			if ($passCheck == true)
			{
				//Craeting a Session
				Session::init();
				Session::set('loggedIn', true);
				if($privileges == "user") Session::set('isAdmin', false);
				if($privileges == "admin") Session::set('isAdmin', true);
				Session::set('id', $id);
				Session::set('account', $privileges);
				Session::set('username', $username);
				Session::set('firstname', $firstname);
				Session::set('lastname', $lastname);
				Session::set('email', $email);

				if (!headers_sent()){
					header('Location: ?url=home/index');
					exit();
				}else{
					print '<script type="text/javascript">';
					print 'window.location.href="?url=home/index";';
					print '</script>';
					print '<noscript>';
					print '<meta http-equiv="refresh" content="0;url=?url=home/index" />';
					print '</noscript>';
					exit(1);
				}
			}
			else
			{
				$this->feedback = "Invalid Password";
				return false;
			}
		}
		else
		{
			$this->feedback = "Invalid User";
			return false;
		}

	}

	public function __destruct(){

		if($this->db) $this->db->close();

	}

}

?>
