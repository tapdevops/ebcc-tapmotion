<?php
session_start();

if(isset($_SESSION["NIK"])){
	
$sql_t_BCC = $_SESSION["sql_t_BCC"];
$userlogin = $_SESSION["NIK"]; 

		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		//$rbtn_type = $_SESSION['rbtn_type'];
		
		$sql = $sql_t_BCC;
		$result = oci_parse($con, $sql);
		oci_execute($result, OCI_DEFAULT);
		$i = 0;
		while (oci_fetch($result)) {
			$i++;
			$NO[] 					= $i;
			$TANGGAL_RENCANA[] 		= oci_result($result, "TANGGAL");
			$NO_BCC[] 				= oci_result($result, "NO_BCC");
			$NAMA_PEMANEN[] 		= oci_result($result, "NAMA_PEMANEN");
			$NAMA_MANDOR[] 			= oci_result($result, "NAMA_MANDOR");
			$NIK_PEMANEN[] 		= oci_result($result, "NIK_PEMANEN");
			$NIK_MANDOR[] 			= oci_result($result, "NIK_MANDOR");
		}
		$roweffec = oci_num_rows($result);
		
		if($roweffec > 0){
			error_reporting(E_ALL);
			ini_set('display_errors', TRUE);
			ini_set('display_startup_errors', TRUE);
			date_default_timezone_set('Europe/London');
			
			if (PHP_SAPI == 'cli')
				die('This example should only be run from a Web Browser');
			
			/** Include PHPExcel */
			require_once '../Classes/PHPExcel.php';
												
			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="BCC Report.xls"');
			header('Cache-Control: max-age=0');

			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel();
			
			// Set document properties
			$objPHPExcel->getProperties()->setCreator("")
										 ->setLastModifiedBy("")
										 ->setTitle("")
										 ->setSubject("")
										 ->setDescription("")
										 ->setKeywords("")
										 ->setCategory("");	
										 
			$objPHPExcel->getDefaultStyle()
						->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	
																 
			//isinya
			$objPHPExcel->setActiveSheetIndex(0)
			->mergeCells("A".(1).":A".(2))
			->setCellValue("A1", "No")
			->mergeCells("B".(1).":B".(2))
			->setCellValue("B1", "Tanggal")
			->mergeCells("C".(1).":C".(2))
			->setCellValue("C1", "No BCC")
			->mergeCells("D".(1).":D".(2))
			->setCellValue("D1", "NIK Pemanen")
			->mergeCells("E".(1).":E".(2))
			->setCellValue("E1", "Nama Pemanen")
			->mergeCells("F".(1).":F".(2))
			->setCellValue("F1", "NIK Mandor")
			->mergeCells("G".(1).":G".(2))
			->setCellValue("G1", "Nama Mandor");
			
			for ($x = 3; $x < ($roweffec+3) ; $x++) {
			$y= ($x-3);
			$objPHPExcel->getActiveSheet()->getStyle('E3')
						->getNumberFormat()
						->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	
			$fixedBCC = separator($NO_BCC[$y]);
				
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A$x", "$NO[$y]")
						->setCellValue("B$x", "$TANGGAL_RENCANA[$y]")
						->setCellValue("C$x", "$fixedBCC")
						->setCellValue("D$x", "$NIK_PEMANEN[$y]")
						->setCellValue("E$x", "$NAMA_PEMANEN[$y]")
						->setCellValue("F$x", "$NIK_MANDOR[$y]")
						->setCellValue("G$x", "$NAMA_MANDOR[$y]");
			} 
			
			
			// Rename worksheet
			$objPHPExcel->getActiveSheet()->setTitle('Sheet1');
			
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
		}
		
		else{
		echo "report tidak ada";
		}
}
else{
echo "krani blm login".$_SESSION["NIK"]." *** ".$_SESSION["sql_t_BCC"];
} 