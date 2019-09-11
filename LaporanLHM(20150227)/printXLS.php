<?php
session_start();

if(isset($_SESSION["NIK"])){
	
$sql_laporan_LHM = $_SESSION["sql_Laporan_LHM"];
$userlogin = $_SESSION["NIK"]; 

		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		$rbtn_type = $_SESSION['rbtn_type'];
		$rbtn_filter = $_SESSION['rbtn_filter'];
		if($rbtn_type == "Detail"){
			$rbtn_filter = "";
		}
		$sql = $sql_laporan_LHM;
		$result = oci_parse($con, $sql);
		oci_execute($result, OCI_DEFAULT);
		
		if($rbtn_type == "Detail"){
			while (oci_fetch($result)) {
				$TGL_PANEN[] 			= OCI_RESULT($result, "TGL_PANEN");
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
		}else{
			if($rbtn_filter == "Pemanen"){
				while(oci_fetch($result)){
					$TGL_PANEN[] 			= OCI_RESULT($result, "TGL_PANEN");
					$NAMA_PEMANEN[]   = OCI_RESULT($result, "NAMA_PEMANEN");
					$NIK_PEMANEN[] 			= OCI_RESULT($result, "NIK_PEMANEN");
					$TOTAL_HA_PANEN[]	= OCI_RESULT($result, "TOTAL_HA_PANEN");
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
				}
			}else{
				while(oci_fetch($result)){
					$TGL_PANEN[] 			= OCI_RESULT($result, "TGL_PANEN");
					$ID_BLOK[]   = OCI_RESULT($result, "ID_BLOK");
					$BLOK_NAME[] 			= OCI_RESULT($result, "BLOK_NAME");
					$HA_BLOK[]	= OCI_RESULT($result, "HA_BLOK");
					$TOTAL_HA_PANEN[]	= OCI_RESULT($result, "LUASAN_PANEN");
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
				}
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
			if($rbtn_type == "Detail"){
				$objPHPExcel->setActiveSheetIndex(0)
				->mergeCells("A".(1).":A".(2))
				->setCellValue("A1", "Tgl")
				->mergeCells("B".(1).":B".(2))
				->setCellValue("B1", "Afdeling")
				->mergeCells("C".(1).":D".(1))
				->setCellValue("C1", "Mandor")
				->mergeCells("E".(1).":F".(1))
				->setCellValue("E1", "Pemanen")
				->mergeCells("G".(1).":H".(1))
				->setCellValue("G1", "Kerani Buah")
				->mergeCells("I".(1).":I".(2))
				->setCellValue("I1", "Kode Blok")
				->mergeCells("J".(1).":J".(2))
				->setCellValue("J1", "Deskripsi Blok")
				->mergeCells("K".(1).":K".(2))
				->setCellValue("K1", "Luasan Panen")
				->mergeCells("L".(1).":L".(2))
				->setCellValue("L1", "No Bcc")
				->mergeCells("M".(1).":N".(1))
				->setCellValue("M1", "Hasil Panen")
				->mergeCells("O".(1).":Y".(1))
				->setCellValue("O1", "Pinalti")
				
				->setCellValue("C2", "Nama")
				->setCellValue("D2", "NIK")
				->setCellValue("E2", "Nama")
				->setCellValue("F2", "NIK")
				->setCellValue("G2", "Nama")
				->setCellValue("H2", "NIK")
				->setCellValue("M2", "JJG")
				->setCellValue("N2", "BRD")
				->setCellValue("O2", "BM")
				->setCellValue("P2", "BK")
				->setCellValue("Q2", "TP")
				->setCellValue("R2", "BB")
				->setCellValue("S2", "JK")
				->setCellValue("T2", "BT")
				->setCellValue("U2", "BL")
				->setCellValue("V2", "PB")
				->setCellValue("W2", "AB")
				->setCellValue("X2", "SF")
				->setCellValue("Y2", "BS");
				
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
							->setCellValue("B$x", "$ID_AFD[$y]")
							->setCellValue("C$x", "$NAMA_MANDOR[$y]")
							->setCellValue("D$x", "$NIK_MANDOR[$y]")
							->setCellValue("E$x", "$NAMA_PEMANEN[$y]")
							->setCellValue("F$x", "$NIK_PEMANEN[$y]")
							->setCellValue("G$x", "$NAMA_KERANI_BUAH[$y]")
							->setCellValue("H$x", "$NIK_KERANI_BUAH[$y]")
							->setCellValue("I$x", "'$ID_BLOK[$y]")
							->setCellValue("J$x", "$BLOK_NAME[$y]")
							->setCellValue("K$x", "$HA")
							->setCellValue("L$x", "$fixedBCC")
							->setCellValue("M$x", "$TBS[$y]")
							->setCellValue("N$x", "$BRD[$y]")
							->setCellValue("O$x", "$BM[$y]")
							->setCellValue("P$x", "$BK[$y]")
							->setCellValue("Q$x", "$TP[$y]")
							->setCellValue("R$x", "$BB[$y]")
							->setCellValue("S$x", "$JK[$y]")
							->setCellValue("T$x", "$BT[$y]")
							->setCellValue("U$x", "$BL[$y]")
							->setCellValue("V$x", "$PB[$y]")
							->setCellValue("W$x", "$AB[$y]")
							->setCellValue("X$x", "$SF[$y]")
							->setCellValue("Y$x", "$BS[$y]");
							
				$objPHPExcel->getActiveSheet()->getCell("I$x")->setValueExplicit("$ID_BLOK[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->getStyle("I$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
				
				}
			}else{
				if($rbtn_filter == "Pemanen"){
					$objPHPExcel->setActiveSheetIndex(0)
					->mergeCells("A".(1).":A".(2))
					->setCellValue("A1", "Tgl")
					->mergeCells("B".(1).":C".(1))
					->setCellValue("B1", "Pemanen")
					->mergeCells("D".(1).":D".(2))
					->setCellValue("D1", "Total HA Panen")
					->mergeCells("E".(1).":F".(1))
					->setCellValue("E1", "Hasil Panen")
					->mergeCells("G".(1).":Q".(1))
					->setCellValue("G1", "Penalti")
					
					->setCellValue("B2", "Nama")
					->setCellValue("C2", "NIK")
					->setCellValue("E2", "JJG")
					->setCellValue("F2", "BRD")
					->setCellValue("G2", "BM")
					->setCellValue("H2", "BK")
					->setCellValue("I2", "TP")
					->setCellValue("J2", "BB")
					->setCellValue("K2", "JK")
					->setCellValue("L2", "BT")
					->setCellValue("M2", "BL")
					->setCellValue("N2", "PB")
					->setCellValue("O2", "AB")
					->setCellValue("P2", "SF")
					->setCellValue("Q2", "BS");
					
					for ($x = 3; $x < ($roweffec+3) ; $x++) {
					$y= ($x-3);
					$objPHPExcel->getActiveSheet()->getStyle('E3')
								->getNumberFormat()
								->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
					
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue("A$x", "$TGL_PANEN[$y]")
								->setCellValue("B$x", "$NAMA_PEMANEN[$y]")
								->setCellValue("C$x", "$NIK_PEMANEN[$y]")
								->setCellValue("D$x", "$TOTAL_HA_PANEN[$y]")
								->setCellValue("E$x", "$TBS[$y]")
								->setCellValue("F$x", "$BRD[$y]")
								->setCellValue("G$x", "$BM[$y]")
								->setCellValue("H$x", "$BK[$y]")
								->setCellValue("I$x", "$TP[$y]")
								->setCellValue("J$x", "$BB[$y]")
								->setCellValue("K$x", "$JK[$y]")
								->setCellValue("L$x", "$BT[$y]")
								->setCellValue("M$x", "$BL[$y]")
								->setCellValue("N$x", "$PB[$y]")
								->setCellValue("O$x", "$AB[$y]")
								->setCellValue("P$x", "$SF[$y]")
								->setCellValue("Q$x", "$BS[$y]");	
					} 
				}else{
					$objPHPExcel->setActiveSheetIndex(0)
					->mergeCells("A".(1).":A".(2))
					->setCellValue("A1", "Tgl")
					->mergeCells("B".(1).":C".(1))
					->setCellValue("B1", "Blok")
					->mergeCells("D".(1).":D".(2))
					->setCellValue("D1", "HA Blok")
					->mergeCells("E".(1).":E".(2))
					->setCellValue("E1", "Total HA Panen")
					->mergeCells("F".(1).":G".(1))
					->setCellValue("F1", "Hasil Panen")
					->mergeCells("H".(1).":R".(1))
					->setCellValue("H1", "Pinalti")
					
					->setCellValue("B2", "Kode Blok")
					->setCellValue("C2", "Nama Blok")
					->setCellValue("F2", "JJG")
					->setCellValue("G2", "BRD")
					->setCellValue("H2", "BM")
					->setCellValue("I2", "BK")
					->setCellValue("J2", "TP")
					->setCellValue("K2", "BB")
					->setCellValue("L2", "JK")
					->setCellValue("M2", "BT")
					->setCellValue("N2", "BL")
					->setCellValue("O2", "PB")
					->setCellValue("P2", "AB")
					->setCellValue("Q2", "SF")
					->setCellValue("R2", "BS");
					
					for ($x = 3; $x < ($roweffec+3) ; $x++) {
					$y= ($x-3);
					$objPHPExcel->getActiveSheet()->getStyle('E3')
								->getNumberFormat()
								->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
					
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue("A$x", "$TGL_PANEN[$y]")
								->setCellValue("B$x", "'$ID_BLOK[$y]")
								->setCellValue("C$x", "$BLOK_NAME[$y]")
								->setCellValue("D$x", "$HA_BLOK[$y]")
								->setCellValue("E$x", "$TOTAL_HA_PANEN[$y]")
								->setCellValue("F$x", "$TBS[$y]")
								->setCellValue("G$x", "$BRD[$y]")
								->setCellValue("H$x", "$BM[$y]")
								->setCellValue("I$x", "$BK[$y]")
								->setCellValue("J$x", "$TP[$y]")
								->setCellValue("K$x", "$BB[$y]")
								->setCellValue("L$x", "$JK[$y]")
								->setCellValue("M$x", "$BT[$y]")
								->setCellValue("N$x", "$BL[$y]")
								->setCellValue("O$x", "$PB[$y]")
								->setCellValue("P$x", "$AB[$y]")
								->setCellValue("Q$x", "$SF[$y]")
								->setCellValue("R$x", "$BS[$y]");
						$objPHPExcel->getActiveSheet()->getCell("B$x")->setValueExplicit("$ID_BLOK[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet()->getStyle("B$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
					}
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
echo "krani blm login".$_SESSION["NIK"]." *** ".$_SESSION["sql_laporan_LHM"];
} 