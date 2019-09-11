<?php
session_start();

if(isset($_SESSION["NIK"]) && isset($_SESSION["sql_bcc_restan"])){

$sql_bcc_restan = $_SESSION["sql_bcc_restan"];
$userlogin = $_SESSION["NIK"]; 

		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		die($sql_bcc_restan);
		$sql = $sql_bcc_restan;
		$result = oci_parse($con, $sql);
		oci_execute($result, OCI_DEFAULT);
		
		while (oci_fetch($result)) {
			$COMPANY_CODE[] 	= oci_result($result, "ID_CC");
			$BUSINESS_AREA[] 	= oci_result($result, "ID_BA");
			$DIVISI[] 			= oci_result($result, "ID_AFD");
			$TGL_PANEN[] 		= oci_result($result, "TANGGAL_RENCANA"); 
			$BLOK[]   			= oci_result($result, "ID_BLOK"); 
			$MANDOR[]   		= oci_result($result, "NIK_MANDOR");
			$NO_BCC[]   		= oci_result($result, "NO_BCC");
			$TBS[]   		    = oci_result($result, "TBS");
			$BRD[]   		    = oci_result($result, "BRD");
			$ESTIMASI_BERAT[]   = oci_result($result, "ESTIMASI_BERAT");
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
			header('Content-Disposition: attachment;filename="BCC Restan Report.xls"');
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
			->setCellValue("A1", "Company Code")
			->setCellValue("B1", "Business Area")
			->setCellValue("C1", "Divisi")
			->setCellValue("D1", "Tanggal Panen")
			->setCellValue("E1", "Blok")
			->setCellValue("F1", "Mandor")
			->setCellValue("G1", "No.BCC")
		    ->setCellValue("H1", "TBS")
			->setCellValue("I1", "BRD")
			->setCellValue("J1", "Estimasi Berat");
			
			for ($x = 3; $x < ($roweffec+3) ; $x++) {
			$y= ($x-3);
			$objPHPExcel->getActiveSheet()->getStyle('E3')
						->getNumberFormat()
						->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A$x", "$COMPANY_CODE[$y]")
						->setCellValue("B$x", "$BUSINESS_AREA[$y]")
						->setCellValue("C$x", "$DIVISI[$y]")
						->setCellValue("D$x", "$TGL_PANEN[$y]")
						->setCellValue("E$x", "$BLOK[$y]")
						->setCellValue("F$x", "$MANDOR[$y]")
						->setCellValue("G$x", "$BLOK[$y]")
						->setCellValue("H$x", "$TBS[$y]")
						->setCellValue("I$x", "$BRD[$y]")
						->setCellValue("J$x", "$ESTIMASI_BERAT[$y]");
						
			$objPHPExcel->getActiveSheet()->getCell("G$x")->setValueExplicit("$NO_BCC[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()->getStyle("G$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			
			$objPHPExcel->getActiveSheet()->getCell("E$x")->setValueExplicit("$BLOK[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()->getStyle("E$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			
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
elseif (!isset($_SESSION["sql_bcc_restan"])){
echo "Harap tampilkan data terlebih dahulu";
} else{
	echo "krani blm login";
}