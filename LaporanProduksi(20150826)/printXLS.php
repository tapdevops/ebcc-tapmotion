<?php
session_start();

if(isset($_SESSION["NIK"]) && isset($_SESSION["sql_laporan_production"])){
	
$sql_laporan_production = $_SESSION["sql_laporan_production"];
$userlogin = $_SESSION["NIK"]; 

//$NIK_krani = '0000';
		include("../config/SQL_function.php");
		//require_once __DIR__ . '/db_config.php'; 
		//$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
		include("../config/db_connect.php");
		$con = connect();
		
		$sql = $sql_laporan_production;
		$result = oci_parse($con, $sql);
		oci_execute($result, OCI_DEFAULT);
		
		while (oci_fetch($result)) {
				
			$TANGGAL[] 	= oci_result($result, "TANGGAL");
			$ID_CC[] 	= oci_result($result, "ID_CC");
			$ID_BA[] 	= oci_result($result, "ID_BA");
			$ID_AFD[] 	= oci_result($result, "ID_AFD");
			$SAP[] 		= oci_result($result, "ID_BLOK");
			$DESC[] 	= oci_result($result, "BLOK_NAME");
			$LUASAN_PANEN[] = oci_result($result, "LUASAN_PANEN");
			$BJR[] 		= oci_result($result, "BJR");
			$ESTIMASI[] = oci_result($result, "ESTIMASI_BERAT");
			$TBS[] 		= oci_result($result, "TBS");
			$BRD[] 		= oci_result($result, "BRD");
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
			header('Content-Disposition: attachment;filename="Production Report.xls"');
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
			->setCellValue("A1", "Tanggal")
			->mergeCells("B".(1).":B".(2))
			->setCellValue("B1", "Company Code")
			->mergeCells("C".(1).":C".(2))
			->setCellValue("C1", "Business Area")
			->mergeCells("D".(1).":D".(2))
			->setCellValue("D1", "Divisi")
			->mergeCells("E".(1).":F".(1))
			->setCellValue("E1", "Blok")
			->setCellValue("E2", "SAP")
			->setCellValue("F2", "Desc")
			->mergeCells("G".(1).":G".(2))
			->setCellValue("G1", "Luasan Panen(Ha)")
			->mergeCells("H".(1).":H".(2))
			->setCellValue("H1", "BJR")
			->mergeCells("I".(1).":J".(1))
			->setCellValue("I1", "Produksi")
			->setCellValue("I2", "TBS(jjg)")
			->setCellValue("J2", "BRD(kg)")
			->mergeCells("K".(1).":K".(2))
			->setCellValue("K1", "Estimasi Berat");
			
			for ($x = 3; $x < ($roweffec+3) ; $x++) {
			$y= ($x-3);
			$objPHPExcel->getActiveSheet()->getStyle('E3')
						->getNumberFormat()
						->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A$x", "$TANGGAL[$y]")
						->setCellValue("B$x", "$ID_CC[$y]")
						->setCellValue("C$x", "$ID_BA[$y]")
						->setCellValue("D$x", "$ID_AFD[$y]")
						->setCellValue("E$x", "$SAP[$y]")
						->setCellValue("F$x", "$DESC[$y]")
						->setCellValue("G$x", "$LUASAN_PANEN[$y]")
						->setCellValue("H$x", "$BJR[$y]")
						->setCellValue("I$x", "$TBS[$y]")
						->setCellValue("J$x", "$BRD[$y]")
						->setCellValue("K$x", "$ESTIMASI[$y]");
			
			$objPHPExcel->getActiveSheet()->getCell("E$x")->setValueExplicit("$SAP[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
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
else{
echo "krani blm login";
} 