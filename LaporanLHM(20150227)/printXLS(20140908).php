<?php
session_start();

if(isset($_SESSION["NIK"])){
	
$sql_laporan_LHM = $_SESSION["sql_Laporan_LHM"];
$userlogin = $_SESSION["NIK"]; 

		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		
		$sql = $sql_laporan_LHM;
		$result = oci_parse($con, $sql);
		oci_execute($result, OCI_DEFAULT);
		
		while (oci_fetch($result)) {
			$TGL_PANEN[] 			= OCI_RESULT($result, "TGL_PANEN");
			$NO_LHM[] 			= OCI_RESULT($result, "NO_LHM");
			$ID_AFD[] 		= OCI_RESULT($result, "ID_AFD");
			$NAMA_MANDOR[] 		= OCI_RESULT($result, "NAMA_MANDOR"); 
			$NIK_MANDOR[]   = OCI_RESULT($result, "NIK_MANDOR"); 
			$NAMA_PEMANEN[]   = OCI_RESULT($result, "NAMA_PEMANEN");
			$NIK_PEMANEN[] 			= OCI_RESULT($result, "NIK_PEMANEN");
			$NAMA_KERANI_BUAH[]   = OCI_RESULT($result, "NAMA_KERANI_BUAH");
			$NIK_KERANI_BUAH[] 			= OCI_RESULT($result, "NIK_KERANI_BUAH");
			$ID_BLOK[] 			= OCI_RESULT($result, "ID_BLOK");
			$BLOK_NAME[] 		= OCI_RESULT($result, "BLOK_NAME");
			$LUASAN_PANEN[] 			= OCI_RESULT($result, "LUASAN_PANEN");
			$NO_BCC[] 		= OCI_RESULT($result, "NO_BCC"); 
			$TBS[]   = OCI_RESULT($result, "TBS"); 
			$BRD[]   = OCI_RESULT($result, "BRD");
			
			$BM[] 			= OCI_RESULT($result, "BM");
			$BK[] 			= OCI_RESULT($result, "BK");
			$TP[] 		= OCI_RESULT($result, "TP");
			$BB[] 		= OCI_RESULT($result, "BB"); 
			$JK[]   = OCI_RESULT($result, "JK"); 
			$BT[]   = OCI_RESULT($result, "BT");
			$BL[] 			= OCI_RESULT($result, "BL");
			$PB[] 			= OCI_RESULT($result, "PB");
			$AB[] 		= OCI_RESULT($result, "AB");
			$SF[] 		= OCI_RESULT($result, "SF"); 
			$BS[]   = OCI_RESULT($result, "BS"); 
			$NO_REKAP_BCC[] = OCI_RESULT($result, "NO_REKAP_BCC");
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
			header('Content-Disposition: attachment;filename="LHM Report.xls"');
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
			->setCellValue("A1", "Tgl")
			->mergeCells("B".(1).":B".(2))
			->setCellValue("B1", "No LHM")
			->mergeCells("C".(1).":C".(2))
			->setCellValue("C1", "Afdeling")
			->mergeCells("D".(1).":E".(1))
			->setCellValue("D1", "Mandor")
			->mergeCells("F".(1).":G".(1))
			->setCellValue("F1", "Pemanen")
			->mergeCells("H".(1).":I".(1))
			->setCellValue("H1", "Kerani Buah")
			->mergeCells("J".(1).":J".(2))
			->setCellValue("J1", "Kode Blok")
			->mergeCells("K".(1).":K".(2))
			->setCellValue("K1", "Deskripsi Blok")
			->mergeCells("L".(1).":L".(2))
			->setCellValue("L1", "Luasan Panen")
			->mergeCells("M".(1).":M".(2))
			->setCellValue("M1", "No Bcc")
			->mergeCells("N".(1).":O".(1))
			->setCellValue("N1", "Hasil Panen")
			->mergeCells("P".(1).":Z".(1))
			->setCellValue("P1", "Pinalti")
			
			->setCellValue("D2", "Nama")
			->setCellValue("E2", "NIK")
			->setCellValue("F2", "Nama")
			->setCellValue("G2", "NIK")
			->setCellValue("H2", "Nama")
			->setCellValue("I2", "NIK")
			->setCellValue("N2", "JJG")
			->setCellValue("O2", "BRD")
			->setCellValue("P2", "BM")
			->setCellValue("Q2", "BK")
			->setCellValue("R2", "TP")
			->setCellValue("S2", "BB")
			->setCellValue("T2", "JK")
			->setCellValue("U2", "BT")
			->setCellValue("V2", "BL")
			->setCellValue("W2", "PB")
			->setCellValue("X2", "AB")
			->setCellValue("Y2", "SF")
			->setCellValue("Z2", "BS");
			
			for ($x = 3; $x < ($roweffec+3) ; $x++) {
			$y= ($x-3);
			$objPHPExcel->getActiveSheet()->getStyle('E3')
						->getNumberFormat()
						->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			
			$fixedBCC = separator($NO_BCC[$y]);
			if($y == 0)
			{
				$HA = number_format((float)$LUASAN_PANEN[$y], 2, '.', '');
			}
			else
			{
				if($NO_REKAP_BCC[$y] !== $NO_REKAP_BCC[$y-1])
				{
					$HA = number_format((float)$LUASAN_PANEN[$y], 2, '.', '');
				}
				else
				{
					$HA = '-';
				}
			}
	
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A$x", "$TGL_PANEN[$y]")
						->setCellValue("B$x", "$NO_LHM[$y]")
						->setCellValue("C$x", "$ID_AFD[$y]")
						->setCellValue("D$x", "$NAMA_MANDOR[$y]")
						->setCellValue("E$x", "$NIK_MANDOR[$y]")
						->setCellValue("F$x", "$NAMA_PEMANEN[$y]")
						->setCellValue("G$x", "$NIK_PEMANEN[$y]")
						->setCellValue("H$x", "$NAMA_KERANI_BUAH[$y]")
						->setCellValue("I$x", "$NIK_KERANI_BUAH[$y]")
						->setCellValue("J$x", "'$ID_BLOK[$y]")
						->setCellValue("K$x", "$BLOK_NAME[$y]")
						->setCellValue("L$x", "$HA")
						->setCellValue("M$x", "$fixedBCC")
						->setCellValue("N$x", "$TBS[$y]")
						->setCellValue("O$x", "$BRD[$y]")
						->setCellValue("P$x", "$BM[$y]")
						->setCellValue("Q$x", "$BK[$y]")
						->setCellValue("R$x", "$TP[$y]")
						->setCellValue("S$x", "$BB[$y]")
						->setCellValue("T$x", "$JK[$y]")
						->setCellValue("U$x", "$BT[$y]")
						->setCellValue("V$x", "$BL[$y]")
						->setCellValue("W$x", "$PB[$y]")
						->setCellValue("X$x", "$AB[$y]")
						->setCellValue("Y$x", "$SF[$y]")
						->setCellValue("Z$x", "$BS[$y]");
						
			$objPHPExcel->getActiveSheet()->getCell("J$x")->setValueExplicit("$ID_BLOK[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()->getStyle("J$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			
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
echo "krani blm login".$_SESSION["NIK"]." *** ".$_SESSION["sql_laporan_LHM"];
} 