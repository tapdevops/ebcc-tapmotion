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
				$MS[] 			= OCI_RESULT($result, "MS");
				$OVR[] 			= OCI_RESULT($result, "OVR");
				$BB[] 		= OCI_RESULT($result, "BB"); 
				$JK[]   = OCI_RESULT($result, "JK"); 
				$BA[]   = OCI_RESULT($result, "BA"); 
				$TP[] 		= OCI_RESULT($result, "TP");
				$MH[] 		= OCI_RESULT($result, "MH");
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
					$MS[] 			= OCI_RESULT($result, "MS");
					$OVR[] 			= OCI_RESULT($result, "OVR");
					$BB[] 		= OCI_RESULT($result, "BB"); 
					$JK[]   = OCI_RESULT($result, "JK"); 
					$BA[]   = OCI_RESULT($result, "BA"); 
					$TP[] 		= OCI_RESULT($result, "TP");
					$MH[] 		= OCI_RESULT($result, "MH");
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
					$MS[] 			= OCI_RESULT($result, "MS");
					$OVR[] 			= OCI_RESULT($result, "OVR");
					$BB[] 		= OCI_RESULT($result, "BB"); 
					$JK[]   = OCI_RESULT($result, "JK"); 
					$BA[]   = OCI_RESULT($result, "BA"); 
					$TP[] 		= OCI_RESULT($result, "TP");
					$MH[] 		= OCI_RESULT($result, "MH");
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
			header('Content-Disposition: attachment;filename="Laporan Hasil Panen.xls"');
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
				->mergeCells("O".(1).":U".(1))
				->setCellValue("O1", "Kualitas Buah")
				->mergeCells("V".(1).":AC".(1))
				->setCellValue("V1", "Kondisi Buah")
				
				->setCellValue("C2", "Nama")
				->setCellValue("D2", "NIK")
				->setCellValue("E2", "Nama")
				->setCellValue("F2", "NIK")
				->setCellValue("G2", "Nama")
				->setCellValue("H2", "NIK")
				->setCellValue("M2", "JJG Panen")
				->setCellValue("N2", "BRD")
				->setCellValue("O2", "BM")
				->setCellValue("P2", "BK")
				->setCellValue("Q2", "MS")
				->setCellValue("R2", "OR")
				->setCellValue("S2", "BB")
				->setCellValue("T2", "JK")
				->setCellValue("U2", "BA")
				->setCellValue("V2", "TP")
				->setCellValue("W2", "MH")
				->setCellValue("X2", "BT")
				->setCellValue("Y2", "BL")
				->setCellValue("Z2", "PB")
				->setCellValue("AA2", "AB")
				->setCellValue("AB2", "SF")
				->setCellValue("AC2", "BS");
				
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
					//Edit by Ardo, 22-09-2016 : CR Synchronize EBCC perubahan perhitungan Luasan Panen
					if($TGL_PANEN[$y]!==$TGL_PANEN[$y-1] || $ID_AFD[$y]!==$ID_AFD[$y-1] || $ID_BLOK[$y]!==$ID_BLOK[$y-1] || $NIK_PEMANEN[$y]!==$NIK_PEMANEN[$y-1])
					//if($NO_REKAP_BCC[$y] !== $NO_REKAP_BCC[$y-1] || ($NO_REKAP_BCC[$y] == $NO_REKAP_BCC[$y-1] && $NAMA_PEMANEN[$y] !== $NAMA_PEMANEN[$y-1]))
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
							->setCellValue("Q$x", "$MS[$y]")
							->setCellValue("R$x", "$OVR[$y]")
							->setCellValue("S$x", "$BB[$y]")
							->setCellValue("T$x", "$JK[$y]")
							->setCellValue("U$x", "$BA[$y]")
							->setCellValue("V$x", "$TP[$y]")
							->setCellValue("W$x", "$MH[$y]")
							->setCellValue("X$x", "$BT[$y]")
							->setCellValue("Y$x", "$BL[$y]")
							->setCellValue("Z$x", "$PB[$y]")
							->setCellValue("AA$x", "$AB[$y]")
							->setCellValue("AB$x", "$SF[$y]")
							->setCellValue("AC$x", "$BS[$y]");
							
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
					->mergeCells("G".(1).":M".(1))
					->setCellValue("G1", "Kualitas Buah")
					->mergeCells("N".(1).":U".(1))
					->setCellValue("N1", "Kondisi Buah")
					
					->setCellValue("B2", "Nama")
					->setCellValue("C2", "NIK")
					->setCellValue("E2", "JJG Panen")
					->setCellValue("F2", "BRD")
					->setCellValue("G2", "BM")
					->setCellValue("H2", "BK")
					->setCellValue("I2", "MS")
					->setCellValue("J2", "OR")
					->setCellValue("K2", "BB")
					->setCellValue("L2", "JK")
					->setCellValue("M2", "BA")
					->setCellValue("N2", "TP")
					->setCellValue("O2", "MH")
					->setCellValue("P2", "BT")
					->setCellValue("Q2", "BL")
					->setCellValue("R2", "PB")
					->setCellValue("S2", "AB")
					->setCellValue("T2", "SF")
					->setCellValue("U2", "BS");
					
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
								->setCellValue("I$x", "$MS[$y]")
								->setCellValue("J$x", "$OVR[$y]")
								->setCellValue("K$x", "$BB[$y]")
								->setCellValue("L$x", "$JK[$y]")
								->setCellValue("M$x", "$BA[$y]")
								->setCellValue("N$x", "$TP[$y]")
								->setCellValue("O$x", "$MH[$y]")
								->setCellValue("P$x", "$BT[$y]")
								->setCellValue("Q$x", "$BL[$y]")
								->setCellValue("R$x", "$PB[$y]")
								->setCellValue("S$x", "$AB[$y]")
								->setCellValue("T$x", "$SF[$y]")
								->setCellValue("U$x", "$BS[$y]");	
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
					->mergeCells("H".(1).":N".(1))
					->setCellValue("H1", "Kualitas Buah")
					->mergeCells("O".(1).":V".(1))
					->setCellValue("O1", "Kondisi Buah")
					
					->setCellValue("B2", "Kode Blok")
					->setCellValue("C2", "Nama Blok")
					->setCellValue("F2", "JJG Panen")
					->setCellValue("G2", "BRD")
					->setCellValue("H2", "BM")
					->setCellValue("I2", "BK")
					->setCellValue("J2", "MS")
					->setCellValue("K2", "OR")
					->setCellValue("L2", "BB")
					->setCellValue("M2", "JK")
					->setCellValue("N2", "BA")
					->setCellValue("O2", "TP")
					->setCellValue("P2", "MH")
					->setCellValue("Q2", "BT")
					->setCellValue("R2", "BL")
					->setCellValue("S2", "PB")
					->setCellValue("T2", "AB")
					->setCellValue("U2", "SF")
					->setCellValue("V2", "BS");
					
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
								->setCellValue("J$x", "$MS[$y]")
								->setCellValue("K$x", "$OVR[$y]")
								->setCellValue("L$x", "$BB[$y]")
								->setCellValue("M$x", "$JK[$y]")
								->setCellValue("N$x", "$BA[$y]")
								->setCellValue("O$x", "$TP[$y]")
								->setCellValue("P$x", "$MH[$y]")
								->setCellValue("Q$x", "$BT[$y]")
								->setCellValue("R$x", "$BL[$y]")
								->setCellValue("S$x", "$PB[$y]")
								->setCellValue("T$x", "$AB[$y]")
								->setCellValue("U$x", "$SF[$y]")
								->setCellValue("V$x", "$BS[$y]");
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