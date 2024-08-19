<?php

class DatatableImportsModel extends Database{

	protected $db;

	public function __construct(){

		//Database Connection Instance
		$this->db = new Database;

		//Enabling Exceptions
		$this->db->enableExceptions();

	}

	/* ---- Import EXCEL Sheet ---- */
	public function importEXCEL(){

		if($_SERVER['REQUEST_METHOD'] != "POST")
		{
			return false;
			exit();
		}

		$allowedFileTypes = [
			'application/vnd.ms-excel',
			'text/xls',
			'text/xlsx',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		];

		if(in_array($_FILES["excel_sheet"]["type"], $allowedFileTypes))
		{
			$targetPath = 'uploads/'.$_FILES['excel_sheet']['name'];
			@move_uploaded_file($_FILES['excel_sheet']['tmp_name'], $targetPath);

			$Reader = new SpreadsheetReader($targetPath);

			$sheetCount = count($Reader->sheets());

			$index = 0;
			$results1 = [];
			$results2 = [];
			//Begin Transaction
			$this->db->query("BEGIN");
			for($i=0; $i<$sheetCount; $i++){

				$Reader->ChangeSheet($i);

				foreach ($Reader as $Row);
					if(count($Row) < 4) {
						Session::set('error','Errors!!!. Some columns are missing for the drug details to be stored in stock.');
						return false;
						exit();
					}

				foreach ($Reader as $Row){

					$brand_name = $generic_name = $strength = $price = "";
					if(isset($Row[0]) && isset($Row[1]) && isset($Row[2]) && isset($Row[3])) {
						$brand_name = $Row[0];
						$generic_name = $Row[1];
						$strength = $Row[2];
						$price = $Row[3];
					}

					if (empty($brand_name) || empty($generic_name) || empty($strength) || empty($price))
					{
						//Rollback Transaction
						$this->db->query("ROLLBACK");

						Session::set('error','Error!!!. One or more cells in the excel file are empty. Please fill in the empty fields in the excel sheet.');
						return false;
						exit();
					}

					$check_stmt = $this->db->prepare("SELECT * FROM drugs WHERE brand_name = ? AND generic_name = ?");

			        $check_stmt->bindValue(1, $brand_name, SQLITE3_TEXT);
			        $check_stmt->bindValue(2, $generic_name, SQLITE3_TEXT);
					$results1[$index] = $check_stmt->execute();

					$count = 0;
			        while ($row = $results1[$index]->fetchArray(SQLITE3_ASSOC)) {
			          $count++;
			        }
					if ($count <= 0)
					{
						//Auto generated data
						$id = strtolower(md5(uniqid(microtime(), false) . microtime()));

						$stmt = $this->db->prepare("INSERT INTO drugs(drug_id, brand_name, generic_name, strength, unit_s_price, updated_on) VALUES(?, ?, ?, ?, ?, ?)");

				        $stmt->bindValue(1, $id, SQLITE3_TEXT);
				        $stmt->bindValue(2, $brand_name, SQLITE3_TEXT);
				        $stmt->bindValue(3, $generic_name, SQLITE3_TEXT);
				        $stmt->bindValue(4, $strength, SQLITE3_TEXT);
				        $stmt->bindValue(5, $price, SQLITE3_INTEGER);
    					$stmt->bindValue(6, date("Y-m-d h:i:s", strtotime("now")), SQLITE3_TEXT);
						$results2[$index] = $stmt->execute();
					}

					$index++;
				}
			}

			@unlink($targetPath);

			//Get the number of rows inserted into the database
			$num = count($results2);

			//Check if there was any error during data entry(using the array of results)
			if (!array_search(0, $results1, true) && !array_search(0, $results2, true))
			{
				//Commit Transaction
				$this->db->query("COMMIT");

				Session::set('success',$num.'  Drug(s) added into the database.');
				return true;
				exit();
			}
			else
			{
				//Rollback Transaction
				$this->db->query("ROLLBACK");

				Session::set('warning','Ooops!!! Something went wrong. There might be invalid data in the cells of the imported excel sheet or the entries in the excel sheet already exist in the database.');
				return false;
				exit();
			}

		}
		else
		{
			Session::set('error','Invalid File Type. Upload an Excel File.');
			return false;
			exit();
		}

	}

	public function __destruct(){

		if($this->db) $this->db->close();

	}

}

?>
