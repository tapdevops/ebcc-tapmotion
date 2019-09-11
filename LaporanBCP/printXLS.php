<?php
session_start();

if(isset($_SESSION["NIK"])){
	
$sql_Laporan_BCP = $_SESSION["sql_Laporan_BCP"];
$userlogin = $_SESSION["NIK"]; 

		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		$rbtn_type = $_SESSION['rbtn_type'];
		
		$sql = $sql_Laporan_BCP;
		$result = oci_parse($con, $sql);
		oci_execute($result, OCI_DEFAULT);
		
		if($rbtn_type == "Detail"){
			while (oci_fetch($result)) {
				$TGL_PANEN[] 			= OCI_RESULT($result, "TGL_PANEN");
				$ID_CC[] 			= OCI_RESULT($result, "ID_CC");
				$ID_BA[] 		= OCI_RESULT($result, "ID_BA");
				$ID_AFD[]   = OCI_RESULT($result, "ID_AFD");
				$NAMA_KERANI_BUAH[]   = OCI_RESULT($result, "NAMA_KERANI_BUAH");
				$NIK_KERANI_BUAH[] 			= OCI_RESULT($result, "NIK_KERANI_BUAH");
				$ID_BLOK[] 			= OCI_RESULT($result, "ID_BLOK");
				$BLOK_NAME[] 		= OCI_RESULT($result, "BLOK_NAME");
				$NAMA_PEMANEN[]   = OCI_RESULT($result, "NAMA_PEMANEN");
				$NIK_PEMANEN[] 			= OCI_RESULT($result, "NIK_PEMANEN");
				$NO_BCC[] 			= OCI_RESULT($result, "NO_BCC");
				$TBS[]   = OCI_RESULT($result, "TBS"); 
				$BRD[]   = OCI_RESULT($result, "BRD");
				$NO_NAB[]   = OCI_RESULT($result, "NO_NAB"); 
			}
		}else{
			while(oci_fetch($result)){
				$TGL_PANEN[] 			= OCI_RESULT($result, "TGL_PANEN");
				$ID_CC[] 			= OCI_RESULT($result, "ID_CC");
				$ID_BA[] 		= OCI_RESULT($result, "ID_BA");
				$ID_AFD[]   = OCI_RESULT($result, "ID_AFD");
				$NAMA_KERANI_BUAH[]   = OCI_RESULT($result, "NAMA_KERANI_BUAH");
				$NIK_KERANI_BUAH[] 			= OCI_RESULT($result, "NIK_KERANI_BUAH");
				$ID_BLOK[] 			= OCI_RESULT($result, "ID_BLOK");
				$BLOK_NAME[] 		= OCI_RESULT($result, "BLOK_NAME");
				$JML_BCC[] 			= OCI_RESULT($result, "JML_BCC");
				$TBS[]   = OCI_RESULT($result, "TBS"); 
				$BRD[]   = OCI_RESULT($result, "BRD");
			}
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
			header('Content-Disposition: attachment;filename="BCP Report.xls"');
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
			if($rbtn_type == "Detail"){
				$objPHPExcel->setActiveSheetIndex(0)
				->mergeCells("A".(1).":A".(2))
				->setCellValue("A1", "Tgl")
				->mergeCells("B".(1).":B".(2))
				->setCellValue("B1", "Afdeling")
				->mergeCells("C".(1).":D".(1))
				->setCellValue("C1", "Kerani Buah")
				->mergeCells("E".(1).":F".(1))
				->setCellValue("E1", "Blok")
				->mergeCells("G".(1).":H".(1))
				->setCellValue("G1", "Pemanen")
				->mergeCells("I".(1).":I".(2))
				->setCellValue("I1", "No BCC")
				->mergeCells("J".(1).":J".(2))
				->setCellValue("J1", "Jml Janjang Panen")
				->mergeCells("K".(1).":K".(2))
				->setCellValue("K1", "Jml Brondolan")
				->mergeCells("L".(1).":L".(2))
				->setCellValue("L1", "No NAB")
				
				->setCellValue("C2", "Nama")
				->setCellValue("D2", "NIK")
				->setCellValue("E2", "Kode Blok")
				->setCellValue("F2", "Nama Blok")
				->setCellValue("G2", "Nama")
				->setCellValue("H2", "NIK");
				
				for ($x = 3; $x < ($roweffec+3) ; $x++) {
				$y= ($x-3);
				$objPHPExcel->getActiveSheet()->getStyle('E3')
							->getNumberFormat()
							->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		
		
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue("A$x", "$TGL_PANEN[$y]")
							->setCellValue("B$x", "$ID_AFD[$y]")
							->setCellValue("C$x", "$NAMA_KERANI_BUAH[$y]")
							->setCellValue("D$x", "$NIK_KERANI_BUAH[$y]")
							->setCellValue("E$x", "'$ID_BLOK[$y]")
							->setCellValue("F$x", "$BLOK_NAME[$y]")
							->setCellValue("G$x", "$NAMA_PEMANEN[$y]")
							->setCellValue("H$x", "$NIK_PEMANEN[$y]")
							->setCellValue("I$x", "$NO_BCC[$y]")
							->setCellValue("J$x", "$TBS[$y]")
							->setCellValue("K$x", "$BRD[$y]")
							->setCellValue("L$x", "$NO_NAB[$y]");
							
				$objPHPExcel->getActiveSheet()->getCell("E$x")->setValueExplicit("$ID_BLOK[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->getStyle("E$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
				
				} 
			}else{
			
				$objPHPExcel->setActiveSheetIndex(0)
				->mergeCells("A".(1).":A".(2))
				->setCellValue("A1", "Tgl")
				->mergeCells("B".(1).":B".(2))
				->setCellValue("B1", "Afdeling")
				->mergeCells("C".(1).":D".(1))
				->setCellValue("C1", "Kerani Buah")
				->mergeCells("E".(1).":F".(1))
				->setCellValue("E1", "Blok")
				->mergeCells("G".(1).":G".(2))
				->setCellValue("G1", "Jml BCC")
				->mergeCells("H".(1).":H".(2))
				->setCellValue("H1", "Jml Janjang Panen")
				->mergeCells("I".(1).":I".(2))
				->setCellValue("I1", "Jml Brondolan")
				
				->setCellValue("C2", "Nama")
				->setCellValue("D2", "NIK")
				->setCellValue("E2", "Kode Blok")
				->setCellValue("F2", "Nama Blok");
				
				for ($x = 3; $x < ($roweffec+3) ; $x++) {
					$y= ($x-3);
					$objPHPExcel->getActiveSheet()->getStyle('E3')
								->getNumberFormat()
								->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			
			$TGL_PANEN[] 			= OCI_RESULT($result, "TGL_PANEN");
				$ID_CC[] 			= OCI_RESULT($result, "ID_CC");
				$ID_BA[] 		= OCI_RESULT($result, "ID_BA");
				$ID_AFD[]   = OCI_RESULT($result, "ID_AFD");
				$NAMA_KERANI_BUAH[]   = OCI_RESULT($result, "NAMA_KERANI_BUAH");
				$NIK_KERANI_BUAH[] 			= OCI_RESULT($result, "NIK_KERANI_BUAH");
				$ID_BLOK[] 			= OCI_RESULT($result, "ID_BLOK");
				$BLOK_NAME[] 		= OCI_RESULT($result, "BLOK_NAME");
				$JML_BCC[] 			= OCI_RESULT($result, "JML_BCC");
				$TBS[]   = OCI_RESULT($result, "TBS"); 
				$BRD[]   = OCI_RESULT($result, "BRD");
				
			
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue("A$x", "$TGL_PANEN[$y]")
								->setCellValue("B$x", "$ID_AFD[$y]")
								->setCellValue("C$x", "$NAMA_KERANI_BUAH[$y]")
								->setCellValue("D$x", "$NIK_KERANI_BUAH[$y]")
								->setCellValue("E$x", "'$ID_BLOK[$y]")
								->setCellValue("F$x", "$BLOK_NAME[$y]")
								->setCellValue("G$x", "$JML_BCC[$y]")
								->setCellValue("H$x", "$TBS[$y]")
								->setCellValue("I$x", "$BRD[$y]");
								
					$objPHPExcel->getActiveSheet()->getCell("E$x")->setValueExplicit("$ID_BLOK[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->getStyle("E$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		
				}
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
echo "krani blm login".$_SESSION["NIK"]." *** ".$_SESSION["sql_laporan_BCP"];
} 