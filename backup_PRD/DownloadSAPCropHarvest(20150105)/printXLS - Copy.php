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
			$NIK_KARYAWAN[] 	= oci_result($result, "NIK_PEMANEN");
			$TANGGAL[] 			= oci_result($result, "TANGGAL");
			$NO_BCC[] 			= oci_result($result, "NO_BCC");
			$NO_TPH[] 			= oci_result($result, "NO_TPH");
			$CUST[] 			= oci_result($result, "CUST");
			$PLANT[] 			= oci_result($result, "PLANT");
			$BLOK[] 			= oci_result($result, "ID_BLOK");
			$TBS[] 				= oci_result($result, "TBS");
			$BRONDOLAN[] 		= oci_result($result, "BRD");
			$DIKIRIM[] 			= oci_result($result, "DIKIRIM");
			$NIK_MANDOR[] 		= oci_result($result, "NIK_MANDOR");
			$NIK_KRANI_BUAH[] 	= oci_result($result, "NIK_KERANI_BUAH");
			$GANDENG[] 			= oci_result($result, "GANDENG");
			$NIK_GANDENG[] 		= oci_result($result, "NIK_GANDENG");
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
			header('Content-Disposition: attachment;filename="CROP HARVESTING.xls"');
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
	
			$NIK_KARYAWAN[] 	= oci_result($result, "NIK_KARYAWAN");
			$TANGGAL[] 			= oci_result($result, "TANGGAL");
			$NO_BCC[] 			= oci_result($result, "NO_BCC");
			$NO_TPH[] 			= oci_result($result, "NO_TPH");
			$CUST[] 			= oci_result($result, "CUST");
			$PLANT[] 			= oci_result($result, "PLANT");
			$BLOK[] 			= oci_result($result, "BLOK");
			$TBS[] 				= oci_result($result, "TBS");
			$BRONDOLAN[] 		= oci_result($result, "BRONDOLAN");
			$DIKIRIM[] 			= oci_result($result, "DIKIRIM");
			$NIK_MANDOR[] 		= oci_result($result, "NIK_MANDOR");
			$NIK_KRANI_BUAH[] 	= oci_result($result, "NIK_KRANI_BUAH");
			$GANDENG[] 			= oci_result($result, "GANDENG");
			$NIK_GANDENG[] 		= oci_result($result, "NIK_GANDENG");
																 
			//isinya
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A1", "NIK_KARYAWAN")
			->setCellValue("B1", "TANGGAL")
			->setCellValue("C1", "NO_BCC")
			->setCellValue("D1", "NO_TPH")
			->setCellValue("E1", "CUST")
			->setCellValue("F1", "PLANT")
			->setCellValue("G1", "BLOK")
			->setCellValue("H1", "TBS")
			->setCellValue("I1", "BRONDOLAN")
			->setCellValue("J1", "DIKIRIM")
			->setCellValue("K1", "NIK_MANDOR")
			->setCellValue("L1", "NIK_KRANI_BUAH")
			->setCellValue("M1", "GANDENG")
			->setCellValue("N1", "NIK_GANDENG");
			
			for ($x = 2; $x < ($roweffec+2) ; $x++) {
			$y= ($x-2);
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A$x", "$NIK_KARYAWAN[$y]")
						->setCellValue("B$x", "$TANGGAL[$y]")
						->setCellValue("C$x", "$NO_BCC[$y]")
						->setCellValue("D$x", "$NO_TPH[$y]")
						->setCellValue("E$x", "$CUST[$y]")
						->setCellValue("F$x", "$PLANT[$y]")
						->setCellValue("G$x", "$BLOK[$y]")
						->setCellValue("H$x", "$TBS[$y]")
						->setCellValue("I$x", "$BRONDOLAN[$y]")
						->setCellValue("J$x", "$DIKIRIM[$y]")
						->setCellValue("K$x", "$NIK_MANDOR[$y]")
						->setCellValue("L$x", "$NIK_KRANI_BUAH[$y]")
						->setCellValue("M$x", "$GANDENG[$y]")
						->setCellValue("N$x", "$NIK_GANDENG[$y]");
						
			$objPHPExcel->getActiveSheet()->getCell("D$x")->setValueExplicit("$NO_TPH[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()->getStyle("D$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			
			$objPHPExcel->getActiveSheet()->getCell("C$x")->setValueExplicit("$NO_BCC[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()->getStyle("C$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			
			$objPHPExcel->getActiveSheet()->getCell("G$x")->setValueExplicit("$BLOK[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()->getStyle("G$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			
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