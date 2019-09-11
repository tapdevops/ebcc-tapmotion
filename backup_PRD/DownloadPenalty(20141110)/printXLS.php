<?php
session_start();

if(isset($_SESSION["NIK"]) && isset($_SESSION["sql_Download_Crop_Harv"])){
	
$sql_Download_Crop_Harv = $_SESSION["sql_Download_Crop_Harv"];
$userlogin = $_SESSION["NIK"]; 

//$NIK_krani = '0000';
		include("../config/SQL_function.php");
		//require_once __DIR__ . '/db_config.php'; 
		//$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
		include("../config/db_connect.php");
		$con = connect();
		
		$sql = $sql_Download_Crop_Harv;
		$result = oci_parse($con, $sql);
		oci_execute($result, OCI_DEFAULT);
		
		while (oci_fetch($result)) {		
			$NOBCC[] 	= oci_result($result, "NOBCC");
			$PENALTI[] 			= oci_result($result, "PENALTI");
			$NILAI[] 			= oci_result($result, "NILAI");
		
		}
		$roweffec = oci_num_rows($result);

		//if( mysql_num_rows($result_area)!=NULL && mysql_num_rows($result_value)!=NULL){ //see row effect
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
			header('Content-Disposition: attachment;filename="DENDA PENALTI.xls"');
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
	
			$NOBCC[] 	= oci_result($result, "NOBCC");
			$PENALTI[] 			= oci_result($result, "PENALTI");
			$NILAI[] 			= oci_result($result, "NILAI");
		
	
			//isinya
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A1", "NO BCC")
			->setCellValue("B1", "KODE DENDA PANEN")
			->setCellValue("C1", "JUMLAH");
			
			for ($x = 2; $x < ($roweffec+2) ; $x++) {
			$y= ($x-2);
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A$x", "$NOBCC[$y]")
						->setCellValue("B$x", "$PENALTI[$y]")
						->setCellValue("C$x", "$NILAI[$y]");
			
			$objPHPExcel->getActiveSheet()->getCell("A$x")->setValueExplicit("$NOBCC[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()->getStyle("A$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						

			
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
		//echo $sql_Download_Crop_Harv;
		}
}
else{
echo "krani blm login";
} 