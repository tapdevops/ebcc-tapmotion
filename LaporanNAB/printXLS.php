<?php
session_start();

if(isset($_SESSION["NIK"]) && isset($_SESSION["sql_Laporan_NAB"])){
	
$sql_Laporan_NAB = $_SESSION["sql_Laporan_NAB"];
$userlogin = $_SESSION["NIK"]; 
$rbtn_type = $_SESSION['rbtn_type'];

		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		
		$sql = $sql_Laporan_NAB;
		$result = oci_parse($con, $sql);
		oci_execute($result, OCI_DEFAULT);
		if($rbtn_type == "Detail"){
			while (oci_fetch($result)) {
				$TGL_PANEN[] 			= OCI_RESULT($result, "TGL_NAB");
				$NO_NAB[] 			= OCI_RESULT($result, "NO_NAB");
				$ID_AFD[] 		= OCI_RESULT($result, "ID_AFD");
				$NOPOL[] 		= OCI_RESULT($result, "NO_POLISI"); 
				$NIK_SUPIR[]   = OCI_RESULT($result, "NIK_SUPIR"); 
				$NAMA_SUPIR[]   = OCI_RESULT($result, "NAMA_SUPIR");
				$NIK_TUKANG_MUAT1[] 			= OCI_RESULT($result, "NIK_TUKANG_MUAT1");
				$NAMA_TM1[] 			= OCI_RESULT($result, "NAMA_TM1");
				$NIK_TUKANG_MUAT2[] 			= OCI_RESULT($result, "NIK_TUKANG_MUAT2");
				$NAMA_TM2[] 			= OCI_RESULT($result, "NAMA_TM2");
				$NIK_TUKANG_MUAT3[] 			= OCI_RESULT($result, "NIK_TUKANG_MUAT3");
				$NAMA_TM3[] 			= OCI_RESULT($result, "NAMA_TM3");
				$NAMA_KERANI_BUAH[]   = OCI_RESULT($result, "NAMA_KERANI_BUAH");
				$NIK_KERANI_BUAH[] 			= OCI_RESULT($result, "NIK_KERANI_BUAH");
				$NO_BCC[] 			= OCI_RESULT($result, "NO_BCC");
				$ESTIMASI_BERAT[] 			= OCI_RESULT($result, "ESTIMASI_BERAT");
				$NO_REKAP_BCC[] = OCI_RESULT($result, "NO_REKAP_BCC");
				$TBS[]   = OCI_RESULT($result, "TBS"); 
				$BRD[]   = OCI_RESULT($result, "BRD");
				$BCC_STATUS_EXPORT[] = OCI_RESULT($result, "BCC_STATUS_EXPORT");
				$NAB_STATUS_EXPORT[] = OCI_RESULT($result, "NAB_STATUS_EXPORT");
			}
		}else{
			while(oci_fetch($result))
			{
				$TGL_PANEN[] 			= OCI_RESULT($result, "TGL_NAB");
				$NO_NAB[] 			= OCI_RESULT($result, "NO_NAB");
				$NOPOL[] 		= OCI_RESULT($result, "NO_POLISI"); 
				$NIK_SUPIR[]   = OCI_RESULT($result, "NIK_SUPIR"); 
				$NAMA_SUPIR[]   = OCI_RESULT($result, "NAMA_SUPIR");
				$NIK_TUKANG_MUAT1[] 			= OCI_RESULT($result, "NIK_TUKANG_MUAT1");
				$NAMA_TM1[] 			= OCI_RESULT($result, "NAMA_TM1");
				$NIK_TUKANG_MUAT2[] 			= OCI_RESULT($result, "NIK_TUKANG_MUAT2");
				$NAMA_TM2[] 			= OCI_RESULT($result, "NAMA_TM2");
				$NIK_TUKANG_MUAT3[] 			= OCI_RESULT($result, "NIK_TUKANG_MUAT3");
				$NAMA_TM3[] 			= OCI_RESULT($result, "NAMA_TM3");
				$NAMA_KERANI_BUAH[]   = OCI_RESULT($result, "NAMA_KERANI_BUAH");
				$NIK_KERANI_BUAH[] 			= OCI_RESULT($result, "NIK_KERANI_BUAH");
				$ID_BLOK[] 			= OCI_RESULT($result, "ID_BLOK");
				$BLOK_NAME[] 			= OCI_RESULT($result, "BLOK_NAME");
				$BJR[] 			= OCI_RESULT($result, "BJR");
				$TAHUN_TANAM[] 			= OCI_RESULT($result, "TAHUN_TANAM");
				$TTL_BCC[] 			= OCI_RESULT($result, "TTL_BCC");
				$TBS[] = OCI_RESULT($result, "TBS");
				$BRD[] = OCI_RESULT($result, "BRD");
				$ESTIMASI_BERAT[] 			= OCI_RESULT($result, "ESTIMASI_BERAT");
				$NAB_STATUS_EXPORT[] = OCI_RESULT($result, "NAB_STATUS_EXPORT");
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
			header('Content-Disposition: attachment;filename="NAB Report.xls"');
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
	
																 
			if($rbtn_type == "Detail"){
				//isinya
				$objPHPExcel->setActiveSheetIndex(0)
				->mergeCells("A".(1).":A".(2))
				->setCellValue("A1", "Tgl")
				->mergeCells("B".(1).":B".(2))
				->setCellValue("B1", "No NAB")
				->mergeCells("C".(1).":C".(2))
				->setCellValue("C1", "Afdeling")
				->mergeCells("D".(1).":D".(2))
				->setCellValue("D1", "Nopol")
				->mergeCells("E".(1).":F".(1))
				->setCellValue("E1", "Supir")
				->mergeCells("G".(1).":H".(1))
				->setCellValue("G1", "Tukang Muat 1")
				->mergeCells("I".(1).":J".(1))
				->setCellValue("I1", "Tukang Muat 2")
				->mergeCells("K".(1).":L".(1))
				->setCellValue("K1", "Tukang Muat 3")
				->mergeCells("M".(1).":N".(1))
				->setCellValue("M1", "Kerani Buah")
				->mergeCells("O".(1).":O".(2))
				->setCellValue("O1", "No Bcc")
				->mergeCells("P".(1).":P".(2))
				->setCellValue("P1", "TBS Kirim (JJG)")
				->mergeCells("Q".(1).":Q".(2))
				->setCellValue("Q1", "BRD(KG)")
				->mergeCells("R".(1).":R".(2))
				->setCellValue("R1", "Estimasi Berat")
				->mergeCells("S".(1).":S".(2))
				->setCellValue("S1", "Status NAB")
				->mergeCells("T".(1).":T".(2))
				->setCellValue("T1", "Status BCC")
				
				->setCellValue("E2", "NIK")
				->setCellValue("F2", "Nama")
				->setCellValue("G2", "NIK")
				->setCellValue("H2", "Nama")
				->setCellValue("I2", "NIK")
				->setCellValue("J2", "Nama")
				->setCellValue("K2", "NIK")
				->setCellValue("L2", "Nama")
				->setCellValue("M2", "NIK")
				->setCellValue("N2", "Nama");
				
				for ($x = 3; $x < ($roweffec+3) ; $x++) {
				$y= ($x-3);
				$objPHPExcel->getActiveSheet()->getStyle('E3')
							->getNumberFormat()
							->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
							
				$fixedBCC = separator($NO_BCC[$y]);
				
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue("A$x", "$TGL_PANEN[$y]")
							->setCellValue("B$x", "$NO_NAB[$y]")
							->setCellValue("C$x", "$ID_AFD[$y]")
							->setCellValue("D$x", "$NOPOL[$y]")
							->setCellValue("E$x", "$NIK_SUPIR[$y]")
							->setCellValue("F$x", "$NAMA_SUPIR[$y]")
							->setCellValue("G$x", "$NIK_TUKANG_MUAT1[$y]")
							->setCellValue("H$x", "$NAMA_TM1[$y]")
							->setCellValue("I$x", "$NIK_TUKANG_MUAT2[$y]")
							->setCellValue("J$x", "$NAMA_TM2[$y]")
							->setCellValue("K$x", "$NIK_TUKANG_MUAT3[$y]")
							->setCellValue("L$x", "$NAMA_TM3[$y]")
							->setCellValue("M$x", "$NIK_KERANI_BUAH[$y]")
							->setCellValue("N$x", "$NAMA_KERANI_BUAH[$y]")
							->setCellValue("O$x", "$fixedBCC")
							->setCellValue("P$x", "$TBS[$y]")
							->setCellValue("Q$x", "$BRD[$y]")
							->setCellValue("R$x", "$ESTIMASI_BERAT[$y]")
							->setCellValue("S$x", "$NAB_STATUS_EXPORT[$y]")
							->setCellValue("T$x", "$BCC_STATUS_EXPORT[$y]")
							;
							
				$objPHPExcel->getActiveSheet()->getCell("O$x")->setValueExplicit("$fixedBCC", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->getStyle("O$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
				} 
			}else{
				//isinya
				$objPHPExcel->setActiveSheetIndex(0)
				->mergeCells("A".(1).":A".(2))
				->setCellValue("A1", "Tgl")
				->mergeCells("B".(1).":B".(2))
				->setCellValue("B1", "No NAB")
				->mergeCells("C".(1).":C".(2))
				->setCellValue("C1", "Nopol")
				->mergeCells("D".(1).":E".(1))
				->setCellValue("D1", "Supir")
				->mergeCells("F".(1).":G".(1))
				->setCellValue("F1", "Tukang Muat 1")
				->mergeCells("H".(1).":I".(1))
				->setCellValue("H1", "Tukang Muat 2")
				->mergeCells("J".(1).":K".(1))
				->setCellValue("J1", "Tukang Muat 3")
				->mergeCells("L".(1).":M".(1))
				->setCellValue("L1", "Kerani Buah")
				->mergeCells("N".(1).":O".(1))
				->setCellValue("N1", "Blok")
				->mergeCells("P".(1).":P".(2))
				->setCellValue("P1", "BJR")
				->mergeCells("Q".(1).":Q".(2))
				->setCellValue("Q1", "Tahun Tanam")
				->mergeCells("R".(1).":R".(2))
				->setCellValue("R1", "Total Bcc")
				->mergeCells("S".(1).":S".(2))
				->setCellValue("S1", "TBS Kirim (JJG)")
				->mergeCells("T".(1).":T".(2))
				->setCellValue("T1", "BRD(KG)")
				->mergeCells("U".(1).":U".(2))
				->setCellValue("U1", "Estimasi Berat")
				->mergeCells("V".(1).":V".(2))
				->setCellValue("V1", "Status NAB")
				
				->setCellValue("D2", "NIK")
				->setCellValue("E2", "Nama")
				->setCellValue("F2", "NIK")
				->setCellValue("G2", "Nama")
				->setCellValue("H2", "NIK")
				->setCellValue("I2", "Nama")
				->setCellValue("J2", "NIK")
				->setCellValue("K2", "Nama")
				->setCellValue("L2", "NIK")
				->setCellValue("M2", "Nama")
				->setCellValue("N2", "Kode Blok")
				->setCellValue("O2", "Nama Blok");
				
				for ($x = 3; $x < ($roweffec+3) ; $x++) {
				$y= ($x-3);
				$objPHPExcel->getActiveSheet()->getStyle('E3')
							->getNumberFormat()
							->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
							
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue("A$x", "$TGL_PANEN[$y]")
							->setCellValue("B$x", "$NO_NAB[$y]")
							->setCellValue("C$x", "$NOPOL[$y]")
							->setCellValue("D$x", "$NIK_SUPIR[$y]")
							->setCellValue("E$x", "$NAMA_SUPIR[$y]")
							->setCellValue("F$x", "$NIK_TUKANG_MUAT1[$y]")
							->setCellValue("G$x", "$NAMA_TM1[$y]")
							->setCellValue("H$x", "$NIK_TUKANG_MUAT2[$y]")
							->setCellValue("I$x", "$NAMA_TM2[$y]")
							->setCellValue("J$x", "$NIK_TUKANG_MUAT3[$y]")
							->setCellValue("K$x", "$NAMA_TM3[$y]")
							->setCellValue("L$x", "$NIK_KERANI_BUAH[$y]")
							->setCellValue("M$x", "$NAMA_KERANI_BUAH[$y]")
							->setCellValue("N$x", "'$ID_BLOK[$y]")
							->setCellValue("O$x", "$BLOK_NAME[$y]")
							->setCellValue("P$x", "$BJR[$y]")
							->setCellValue("Q$x", "$TAHUN_TANAM[$y]")
							->setCellValue("R$x", "$TTL_BCC[$y]")
							->setCellValue("S$x", "$TBS[$y]")
							->setCellValue("T$x", "$BRD[$y]")
							->setCellValue("U$x", "$ESTIMASI_BERAT[$y]")
							->setCellValue("V$x", "$NAB_STATUS_EXPORT[$y]");
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
echo "krani blm login";
} 