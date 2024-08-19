<?php

//Check if SQLite3 is installed in the server and if Extension is Loaded
if(!class_exists('SQLite3') || !extension_loaded('SQLite3'))
{
	//Loading the Error500 controller file
	require_once '../app/controllers/Error500.php';
	
	//Creating an object of a controller
	$controller = new Error500;

	//Call the unloaded_extension method
	$method = 'unloaded_extension';

	//Define the missing extension
	$params = ['name' => 'SQLite3'];

	//Call the functions in the 500 error controller with the default method
	call_user_func([$controller, $method], $params);

	exit();
}

class Database extends SQLite3 {

	public function __construct()
	{
		try
		{
			parent::open(DATABASE_FILE, SQLITE3_OPEN_READWRITE);
		}
		catch(Exception $e)
		{
			//Loading the Error500 controller file
			require_once '../app/controllers/Error500.php';
			
			//Creating an object of a controller
			$controller = new Error500;

			//Call the database method
			$method = 'database';

			//Define the exception occured when handling the database
			$params = ['exception' => $e->getMessage()];

			//Call the functions in the 500 error controller with the default method
			call_user_func([$controller, $method], $params);

			return false;
			exit();
		}
	}

}

?>