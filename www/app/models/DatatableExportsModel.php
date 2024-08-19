<?php

class DatatableExportsModel extends FPDF_CellFit{

	/* ---- Export to PDF ---- */
	public function exportToPDF($name = '', $tableColumns = [], $tableData = []){

		/**
		 * FPDF Constructor
		 *
		 *  __construct(string orientation, string unit, mixed size)
		 */
		if(!is_array($tableColumns) || !is_array($tableData)) return false;

		if (count($tableColumns) > 5)
		{
			$pdf = new FPDF_CellFit('L', 'mm', 'A4');

			//Defines the Dimensions of the cells in the Document
			$countColumns = count($tableColumns);
			if($countColumns == 0) return false;
			$pageWidth = $pdf->GetPageWidth();
			$columnWidth = $pageWidth / $countColumns;
			//Remove the aditional margin size from left
			$columnWidth -= 4;
		}
		else
		{
			$pdf = new FPDF_CellFit('P', 'mm', 'A4');

			//Defines the Dimensions of the cells in the Document
			$countColumns = count($tableColumns);
			if($countColumns == 0) return false;
			$pageWidth = $pdf->GetPageWidth();
			$columnWidth = $pageWidth / $countColumns;
			//Remove the aditional margin size from left
			$columnWidth -= 7;
		}

		$pdf->SetMargins(15, 15, 15);
		$pdf->AddPage();
		$pdf->SetAutoPageBreak(true, 15);

		$pdf->SetFont('Arial', 'B', 14);
		$pdf->SetTextColor(51, 51, 0);
		$pdf->CellFitScale(0, 10, $name, 0, 1, 'C');
		$pdf->Ln(4);

		//Defines the Heading of the Document
		foreach($tableColumns as $tableColumn) {
			$pdf->SetFont('Arial', 'B', 12);
			$pdf->SetTextColor(0, 0, 102);
			if (empty($tableColumn)) {
				$pdf->CellFitScale($columnWidth, 10, 'NAN', 1);
			}else{
				$pdf->CellFitScale($columnWidth, 10, $tableColumn, 1);
			}
		}

		//Defines the Body of the Document
		$i=1;
		foreach ($tableData as $row) {
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->Ln();

			foreach($row as $column){
				if (empty($column)) {
					$pdf->CellFitScale($columnWidth, 8, 'NAN', 1);
				}else{
					$pdf->CellFitScale($columnWidth, 8, $column, 1);
				}
			}
			$i++;
		}

		//Creates the Document
		$pdf->Output();

	}

	/* ---- Export to EXCEL ---- */
	public function exportToEXCEL($name = '', $tableColumns = [], $tableData = []){

		$timestamp = time();
		$filename = $name . '_' . $timestamp . '.xls';

		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$filename\"");

		if (!empty($tableColumns))
		{
			//Defines the Heading of the Document
			$isPrintHeader = false;
			foreach($tableColumns as $tableColumn) {
				if (! $isPrintHeader)
				{
					echo implode("\t", $tableColumns) . "\n";

					$isPrintHeader = true;
				}
			}

			//Defines the Body of the Document
			foreach ($tableData as $row) {
				echo implode("\t", array_values($row)) . "\n";
			}
		}
		exit();

	}

}

?>
