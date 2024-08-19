<?php
/**
* Request parsing class
*/
class Request {

	private static $_params = array();

	function __construct(){
		if(isset($_GET['url'])){
			$urlParams = self::parseUrl($_GET['url']);
			unset($_GET['url']);
			self::$_params["controller"] = $urlParams[0];
			self::$_params["method"] = isset($urlParams[1]) ? $urlParams[1] : 'index' ;
			self::$_params["params"] = array_slice($urlParams, 2);
		}else{
			self::$_params["controller"] = "home";		//Default Controller
			self::$_params["method"] 	=  "index";		//Default Method
			self::$_params["params"] = array();
		}
		
	}

	/*
	*Allows access to the private variable $_params
	*@return $_params
	*/
	public static function getParams(){
		return self::$_params;
	}


	/*
	*Converts the URL passed into an array
	*@return array
	*/
	public static function parseUrl($urlString){

		//Remove trailing slashes in the URL
		$url = rtrim($urlString, '/');

		//Filter the URL
		$url = filter_var($url, FILTER_SANITIZE_URL);

		//Explode the URL into an array
		$url = explode('/', $url);

		return $url;
	}

}

?>