<?php

class Controller {

	//Check if User is Loged In ???
	public function authenticate(){
		Session::init();
		$logged = Session::get('loggedIn');
		if ($logged == false) {
			Session::destroy();
			self::redirect("?url=login");
		}
		$params = Request::getParams();
		if($params["method"] != "alterPassword"){
			//Check if User is Logged In First ???
			//If the logged in user is a normal user,
			//check if his/her password is still default,
			//If it is still default, prompt to change his/her password.
			if(Session::get('account') == "user"){
				if(self::model('UserModel')->normalUserStillHasDefaultPassword()){
					self::view('index', 'Settings', ['page_include' => 'change_password'], 0);
				}
			}
		}
	}

	public function welcome(){
		self::view('auth/welcome', 'Welcome Administrator!', ['page_include' => ''], 101);
	}

	public function model($model){

		//Load the required Model
		$file = '../app/models/'.$model.'.php';

		//Check if the Model Exists
		if (file_exists($file)) {
			//Open the required Model
			require_once $file;
			return new $model();
		}

	}

	public function view($view, $head, $data = [], $code = ''){

		/** --- CUSTOM VIEW CODES ---
		*
		* -- 0 => 'default'
		*
		* -- 101 => 'administrator setup wizard view'
		*
		**/

		if($code == 0)
		{
			$page_title = $head;
			require_once '../app/views/pages/layout/header.php';

			//Open the required View
			require_once '../app/views/'.$view.'.php';

			require_once '../app/views/pages/layout/footer.php';

			return true;
		}
		else if($code == 101)
		{
			$page_title = $head;

			//Open the required View
			require_once '../app/views/'.$view.'.php';

			return true;
		}

	}

	public static function message(){

		if(Session::check('success')){
			$class = 'success';
			$message = Session::get('success');
			Session::delete('success');
		}

		if(Session::check('warning')){
			$class = 'warning';
			$message = Session::get('warning');
			Session::delete('warning');
		}

		if(Session::check('error')){
			$class = 'error';
			$message = Session::get('error');
			Session::delete('error');
		}

		$output = '';
		if (isset($message) && !empty($message)){
			$output .= "toastr.".$class;
			$output .= "('".$message."')";
		}
		echo $output;
		return true;

	}

	//Function to Log Out User
	public function logout(){

		Session::destroy();
		self::redirect("?url=login");

	}

	public function back(){

		print '<script type="text/javascript">';
		print 'window.history.back()';
		print '</script>';
		print '<noscript>';
		print '<meta http-equiv="refresh" content="0;" />';
		print '</noscript>';
		exit(1);

	}

	public function redirect($url){

		if (!headers_sent()){
			header('Location: '.$url);
			exit();
		}else{
			print '<script type="text/javascript">';
			print 'window.location.href="'.$url.'";';
			print '</script>';
			print '<noscript>';
			print '<meta http-equiv="refresh" content="0;url='.$url.'" />';
			print '</noscript>';
			exit(1);
		}

	}

}

?>
