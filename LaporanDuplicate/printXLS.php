<?php
session_start();

if(isset($_SESSION["NIK"]) && isset($_SESSION["sql_bcc_restan1"])){
	
$sql_bcc_restan = $_SESSION["sql_bcc_restan1"];
$userlogin = $_SESSION["NIK"]; 

		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		
		$sql = $sql_bcc_restan;
		$result = oci_parse($con, $sql);
		oci_execute($result, OCI_DEFAULT);
		
		while (oci_fetch($result)) {
			$COMPANY_CODE[] 	= oci_result($result, "BA");
			$BUSINESS_AREA[] 	= oci_result($result, "BLOK");
			$DIVISI[] 			= oci_result($result, "TANGGAL_RENCANA");
			$TGL_PANEN[] 		= oci_result($result, "NO_REKAP_BCC"); 
			$BLOK[]   			= oci_result($result, "NIK_PEMANEN"); 
			$MANDOR[]   		= oci_result($result, "PEMANEN");
			$NO_BCC[]   		= oci_result($result, "NIK_KERANI_BUAH");
			$KRANI1[]   		= oci_result($result, "KRANI");
			$IMEI1[]   		    = oci_result($result, "IMEI");
			
		}
		$roweffec = oci_num_rows($result);

		//if( mysql_num_rows($result_area)!=NULL && mysql_num_rows($result_value)!=NULL){ 
		//see row effect
		
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
			header('Content-Disposition: attachment;filename="BCC DUPLICATE.xls"');
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
			->setCellValue("A1", "BA")
			->setCellValue("B1", "TANGGAL RENCANA")
			->setCellValue("C1", "BLOK")
			->setCellValue("D1", "NO REKAP BCC")
			->setCellValue("E1", "NIK PEMANEN")
			->setCellValue("F1", "NAMA PEMANEN")
			->setCellValue("G1", "NIK KRANI BUAH")
			->setCellValue("H1", "NAMA KRANI BUAH")
			->setCellValue("I1", "IMEI");
			
			
			for ($x = 3; $x < ($roweffec+3) ; $x++) {
			$y= ($x-3);
			$objPHPExcel->getActiveSheet()->getStyle('E3')
						->getNumberFormat()
						->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			$fixedBCC2 =sepbcc($TGL_PANEN[$y]);
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A$x", "$COMPANY_CODE[$y]")
						->setCellValue("B$x", "$DIVISI[$y]")
						->setCellValue("C$x", "$BUSINESS_AREA[$y]")
						->setCellValue("D$x", "$fixedBCC2")
						->setCellValue("E$x", "$BLOK[$y]")
						->setCellValue("F$x", "$MANDOR[$y]")
						->setCellValue("G$x", "'$NO_BCC[$y]")
						->setCellValue("H$x", "$KRANI1[$y]")
						->setCellValue("I$x", "$IMEI1[$y]");
						
			$objPHPExcel->getActiveSheet()->getCell("G$x")->setValueExplicit("$NO_BCC[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()->getStyle("G$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			
			$objPHPExcel->getActiveSheet()->getCell("I$x")->setValueExplicit("$IMEI1[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()->getStyle("I$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			
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
echo "krani blm login";
} 