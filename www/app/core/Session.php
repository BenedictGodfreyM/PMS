<?php

class Session{

	public static function init()
	{
		if(!isset($_SESSION)) @session_start();
	}

	public static function check($key = '')
	{
		if(empty($key)) return false;
		if(isset($_SESSION[$key])){
			return true;
		}else{
			return false;
		}
	}

	public static function set($key = '', $value)
	{
		if(empty($key)) return false;
		$_SESSION[$key] = $value;
		return true;
	}

	public static function get($key = '')
	{
		if(empty($key)) return false;
		if(self::check($key)) return $_SESSION[$key];
	}

	public static function delete($key = '')
	{
		if(empty($key)) return false;
		unset($_SESSION[$key]);
		if(!self::check($key)) return true;
	}

	public static function destroy()
	{
		self::init();
		session_unset();
		session_destroy();
		return true;
	}

}

?>
