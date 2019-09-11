<?php
session_start();

if(isset($_SESSION["NIK"])){
	
$sql_t_BCCLoss = $_SESSION["sql_t_BCCLoss"];
$userlogin = $_SESSION["NIK"]; 
$lap = $_SESSION['lap'];
$jenis_lap = $_SESSION['jenis_lap'];

		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		//$rbtn_type = $_SESSION['rbtn_type'];
		
		$sql = $sql_t_BCCLoss;
		$result_t_BCC_Loss = oci_parse($con, $sql);
		oci_execute($result_t_BCC_Loss, OCI_DEFAULT);
		$i = 0;
		if($lap == 'loss'){
				if($jenis_lap == 'rekap'){
					while(oci_fetch($result_t_BCC_Loss)){
						$i++;
						$NO[] 				= $i;
						$TANGGAL_RENCANA[] 	= oci_result($result_t_BCC_Loss, "TANGGAL_RENCANA");
						$TGL_DOC[] 			= oci_result($result_t_BCC_Loss, "TGL_DOC");
						$NO_DOC[] 			= oci_result($result_t_BCC_Loss, "NO_DOC");
						$CREATED_DATE[] 	= oci_result($result_t_BCC_Loss, "CREATED_DATE");
						$ID_BA[] 			= oci_result($result_t_BCC_Loss, "ID_BA");
						$ID_AFD[] 			= oci_result($result_t_BCC_Loss, "ID_AFD");
						$JML_NO_BCC[] 		= oci_result($result_t_BCC_Loss, "JML_NO_BCC");
						$JML_JJG[] 			= oci_result($result_t_BCC_Loss, "JML_JJG");
						$JML_BRD[] 			= oci_result($result_t_BCC_Loss, "JML_BRD");
						$ESTIMASI_BERAT[] 	= oci_result($result_t_BCC_Loss, "ESTIMASI_BERAT");
					}
				}else{
					while(oci_fetch($result_t_BCC_Loss)){
						$i++;
						$NO[] 				= $i;
						$TANGGAL_RENCANA[] 	= oci_result($result_t_BCC_Loss, "TANGGAL_RENCANA");
						$TGL_DOC[] 			= oci_result($result_t_BCC_Loss, "TGL_DOC");
						$CREATED_DATE[] 	= oci_result($result_t_BCC_Loss, "CREATED_DATE");
						$NO_DOC[] 			= oci_result($result_t_BCC_Loss, "NO_DOC");
						$ID_BA[] 			= oci_result($result_t_BCC_Loss, "ID_BA");
						$ID_AFD[] 			= oci_result($result_t_BCC_Loss, "ID_AFD");
						$ID_BLOK[] 			= oci_result($result_t_BCC_Loss, "ID_BLOK");
						$BLOK_NAME[]		= oci_result($result_t_BCC_Loss, "BLOK_NAME");
						$BJR[] 				= oci_result($result_t_BCC_Loss, "BJR");
						$NO_BCC[] 			= oci_result($result_t_BCC_Loss, "NO_BCC");
						$JJG[] 				= oci_result($result_t_BCC_Loss, "TBS");
						$BRD[]	 			= oci_result($result_t_BCC_Loss, "BRD");
						$ESTIMASI_BERAT[] 	= oci_result($result_t_BCC_Loss, "ESTIMASI_BERAT");
						$ALASAN[] 			= oci_result($result_t_BCC_Loss, "REMARK");
					}
				}
			}else{
				if($jenis_lap == 'rekap'){
					while(oci_fetch($result_t_BCC_Loss)){
						$i++;
						$NO[] 				= $i;
						$TANGGAL_RENCANA[] 	= oci_result($result_t_BCC_Loss, "TANGGAL_RENCANA");
						$PERIODE_WO[] 		= oci_result($result_t_BCC_Loss, "PERIODE_WO");
						$ID_BA[] 			= oci_result($result_t_BCC_Loss, "ID_BA");
						$ID_AFD[] 			= oci_result($result_t_BCC_Loss, "ID_AFD");
						$JML_NO_BCC[] 		= oci_result($result_t_BCC_Loss, "JML_NO_BCC");
						$JML_JJG[] 			= oci_result($result_t_BCC_Loss, "JML_JJG");
						$JML_BRD[] 			= oci_result($result_t_BCC_Loss, "JML_BRD");
						$ESTIMASI_BERAT[] 	= oci_result($result_t_BCC_Loss, "ESTIMASI_BERAT");
					}
				}else{
					while(oci_fetch($result_t_BCC_Loss)){
						$i++;
						$NO[] 				= $i;
						$TANGGAL_RENCANA[] 	= oci_result($result_t_BCC_Loss, "TANGGAL_RENCANA");
						$PERIODE_WO[] 		= oci_result($result_t_BCC_Loss, "PERIODE_WO");
						$ID_BA[] 			= oci_result($result_t_BCC_Loss, "ID_BA");
						$ID_AFD[] 			= oci_result($result_t_BCC_Loss, "ID_AFD");
						$ID_BLOK[] 			= oci_result($result_t_BCC_Loss, "ID_BLOK");
						$BLOK_NAME[]		= oci_result($result_t_BCC_Loss, "BLOK_NAME");
						$BJR[] 				= oci_result($result_t_BCC_Loss, "BJR");
						$NO_BCC[] 			= oci_result($result_t_BCC_Loss, "NO_BCC");
						$JJG[] 				= oci_result($result_t_BCC_Loss, "TBS");
						$BRD[]	 			= oci_result($result_t_BCC_Loss, "BRD");
						$ESTIMASI_BERAT[] 	= oci_result($result_t_BCC_Loss, "ESTIMASI_BERAT");
					}
				}
			}
		$roweffec = oci_num_rows($result_t_BCC_Loss);
		
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
			header('Content-Disposition: attachment;filename="BCC LOSS-WO Report.xls"');
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
			if($lap == 'loss'){
				if($jenis_lap == 'rekap'){
					$objPHPExcel->setActiveSheetIndex(0)
					->mergeCells("A".(1).":A".(2))
					->setCellValue("A1", "No")
					->mergeCells("B".(1).":B".(2))
					->setCellValue("B1", "Tgl Panen")
					->mergeCells("C".(1).":C".(2))
					->setCellValue("C1", "Tgl Document")
					->mergeCells("D".(1).":D".(2))
					->setCellValue("D1", "Tgl Input Loss")
					->mergeCells("E".(1).":E".(2))
					->setCellValue("E1", "No Document")
					->mergeCells("F".(1).":F".(2))
					->setCellValue("F1", "Business Area")
					->mergeCells("G".(1).":G".(2))
					->setCellValue("G1", "Afd")
					->mergeCells("H".(1).":H".(2))
					->setCellValue("H1", "Jml No BCC")
					->mergeCells("I".(1).":I".(2))
					->setCellValue("I1", "Jml JJG Kirim")
					->mergeCells("J".(1).":J".(2))
					->setCellValue("J1", "Jml BRD")
					->mergeCells("K".(1).":K".(2))
					->setCellValue("K1", "Estimasi Berat");
				}else{
					$objPHPExcel->setActiveSheetIndex(0)
					->mergeCells("A".(1).":A".(2))
					->setCellValue("A1", "No")
					->mergeCells("B".(1).":B".(2))
					->setCellValue("B1", "Tgl Panen")
					->mergeCells("C".(1).":C".(2))
					->setCellValue("C1", "Tgl Document")
					->mergeCells("D".(1).":D".(2))
					->setCellValue("D1", "Tgl Input Loss")
					->mergeCells("E".(1).":E".(2))
					->setCellValue("E1", "No Document")
					->mergeCells("F".(1).":F".(2))
					->setCellValue("F1", "Business Area")
					->mergeCells("G".(1).":G".(2))
					->setCellValue("G1", "Afd")				
					->mergeCells("H".(1).":H".(2))
					->setCellValue("H1", "Kode Blok")
					->mergeCells("I".(1).":I".(2))
					->setCellValue("I1", "Blok Desc")
					->mergeCells("J".(1).":J".(2))
					->setCellValue("J1", "BJR")
					->mergeCells("K".(1).":K".(2))
					->setCellValue("K1", "No BCC")
					->mergeCells("L".(1).":L".(2))
					->setCellValue("L1", "JJG Kirim")
					->mergeCells("M".(1).":M".(2))
					->setCellValue("M1", "BRD")
					->mergeCells("N".(1).":N".(2))
					->setCellValue("N1", "Estimasi Berat")
					->mergeCells("O".(1).":O".(2))
					->setCellValue("O1", "Alasan");
				}
			}else{
				if($jenis_lap == 'rekap'){
					$objPHPExcel->setActiveSheetIndex(0)
					->mergeCells("A".(1).":A".(2))
					->setCellValue("A1", "No")
					->mergeCells("B".(1).":B".(2))
					->setCellValue("B1", "Periode Write Off")
					->mergeCells("C".(1).":C".(2))
					->setCellValue("C1", "Tgl Panen")
					->mergeCells("D".(1).":D".(2))
					->setCellValue("D1", "Business Area")
					->mergeCells("E".(1).":E".(2))
					->setCellValue("E1", "Afd")
					->mergeCells("F".(1).":F".(2))
					->setCellValue("F1", "Jml No BCC")
					->mergeCells("G".(1).":G".(2))
					->setCellValue("G1", "Jml JJG Kirim")
					->mergeCells("H".(1).":H".(2))
					->setCellValue("H1", "Jml BRD")
					->mergeCells("I".(1).":I".(2))
					->setCellValue("I1", "Estimasi Berat");
				}else{
					$objPHPExcel->setActiveSheetIndex(0)
						->mergeCells("A".(1).":A".(2))
						->setCellValue("A1", "No")
						->mergeCells("B".(1).":B".(2))
						->setCellValue("B1", "Periode Write Off")
						->mergeCells("C".(1).":C".(2))
						->setCellValue("C1", "Tgl Panen")
						->mergeCells("D".(1).":D".(2))
						->setCellValue("D1", "Business Area")
						->mergeCells("E".(1).":E".(2))
						->setCellValue("E1", "Afd")
						->mergeCells("F".(1).":F".(2))
						->setCellValue("F1", "Kode Blok")
						->mergeCells("G".(1).":G".(2))
						->setCellValue("G1", "Blok Desc")						
						->mergeCells("H".(1).":H".(2))
						->setCellValue("H1", "BJR")
						->mergeCells("I".(1).":I".(2))
						->setCellValue("I1", "No BCC")
						->mergeCells("J".(1).":J".(2))
						->setCellValue("J1", "JJG Kirim")
						->mergeCells("K".(1).":K".(2))
						->setCellValue("K1", "BRD")
						->mergeCells("L".(1).":L".(2))
						->setCellValue("L1", "Estimasi Berat");
						
				}
			}
					
			for ($x = 3; $x < ($roweffec+3) ; $x++) {
				$y= ($x-3);
				$objPHPExcel->getActiveSheet()->getStyle('E3')
							->getNumberFormat()
							->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
					
				if($lap == 'loss'){
					if($jenis_lap == 'rekap'){
						$objPHPExcel->setActiveSheetIndex(0)
									->setCellValue("A$x", "$NO[$y]")
									->setCellValue("B$x", "$TANGGAL_RENCANA[$y]")
									->setCellValue("C$x", "$TGL_DOC[$y]")
									->setCellValue("D$x", "$CREATED_DATE[$y]")
									->setCellValue("E$x", "$NO_DOC[$y]")
									->setCellValue("F$x", "$ID_BA[$y]")
									->setCellValue("G$x", "$ID_AFD[$y]")
									->setCellValue("H$x", "$JML_NO_BCC[$y]")
									->setCellValue("I$x", "$JML_JJG[$y]")
									->setCellValue("J$x", "$JML_BRD[$y]")
									->setCellValue("K$x", "$ESTIMASI_BERAT[$y]");													
					}else{
						$fixedBCC = separator($NO_BCC[$y]);				
						$objPHPExcel->setActiveSheetIndex(0)
									->setCellValue("A$x", "$NO[$y]")
									->setCellValue("B$x", "$TANGGAL_RENCANA[$y]")
									->setCellValue("C$x", "$TGL_DOC[$y]")
									->setCellValue("D$x", "$CREATED_DATE[$y]")
									->setCellValue("E$x", "$NO_DOC[$y]")
									->setCellValue("F$x", "$ID_BA[$y]")
									->setCellValue("G$x", "$ID_AFD[$y]")
									->setCellValue("H$x", "$ID_BLOK[$y]")
									->setCellValue("I$x", "$BLOK_NAME[$y]")
									->setCellValue("J$x", "$BJR[$y]")
									->setCellValue("K$x", "$fixedBCC")
									->setCellValue("L$x", "$JJG[$y]")
									->setCellValue("M$x", "$BRD[$y]")
									->setCellValue("N$x", "$ESTIMASI_BERAT[$y]")
									->setCellValue("O$x", "$ALASAN[$y]");		

						$objPHPExcel->getActiveSheet()->getCell("H$x")->setValueExplicit("$ID_BLOK[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet()->getStyle("H$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
					}
				}else{
					if($jenis_lap == 'rekap'){						
						$objPHPExcel->setActiveSheetIndex(0)
									->setCellValue("A$x", "$NO[$y]")
									->setCellValue("B$x", "$PERIODE_WO[$y]")
									->setCellValue("C$x", "$TANGGAL_RENCANA[$y]")
									->setCellValue("D$x", "$ID_BA[$y]")
									->setCellValue("E$x", "$ID_AFD[$y]")
									->setCellValue("F$x", "$JML_NO_BCC[$y]")
									->setCellValue("G$x", "$JML_JJG[$y]")
									->setCellValue("H$x", "$JML_BRD[$y]")
									->setCellValue("I$x", "$ESTIMASI_BERAT[$y]");
					}else{
						$fixedBCC = separator($NO_BCC[$y]);				
						$objPHPExcel->setActiveSheetIndex(0)
									->setCellValue("A$x", "$NO[$y]")
									->setCellValue("B$x", "$PERIODE_WO[$y]")
									->setCellValue("C$x", "$TANGGAL_RENCANA[$y]")
									->setCellValue("D$x", "$ID_BA[$y]")
									->setCellValue("E$x", "$ID_AFD[$y]")
									->setCellValue("F$x", "$ID_BLOK[$y]")
									->setCellValue("G$x", "$BLOK_NAME[$y]")
									->setCellValue("H$x", "$BJR[$y]")
									->setCellValue("I$x", "$fixedBCC")
									->setCellValue("J$x", "$JJG[$y]")
									->setCellValue("K$x", "$BRD[$y]")
									->setCellValue("L$x", "$ESTIMASI_BERAT[$y]");		
						$objPHPExcel->getActiveSheet()->getCell("F$x")->setValueExplicit("$ID_BLOK[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet()->getStyle("F$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);									
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
echo "krani blm login".$_SESSION["NIK"]." *** ".$_SESSION["sql_t_BCCLoss"];
} 