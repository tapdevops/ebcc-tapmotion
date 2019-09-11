<?php
session_start();

if(isset($_SESSION["NIK"]) && isset($_SESSION["sql_Download_Crop_Harv"])){
	
$sql_Download_Crop_Harv = $_SESSION["sql_Download_Crop_Harv"];
$userlogin = $_SESSION["NIK"]; 

		include("../config/SQL_function.php");
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
		
		for ($counter = 0; $counter < $roweffec; $counter++)
		{
			$NIK_KARYAWANfix[$counter] 		= "";
			$TANGGALfix[$counter] 			= "";
			$NO_BCCfix[$counter] 			= "";
			$NO_TPHfix[$counter] 			= "";
			$CUSTfix[$counter] 				= "";
			$PLANTfix[$counter] 			= "";
			$BLOKfix[$counter] 				= "";
			$TBSfix[$counter] 				= "";
			$BRONDOLANfix[$counter] 		= "";
			$DIKIRIMfix[$counter] 			= "";
			$NIK_MANDORfix[$counter] 		= "";
			$NIK_KRANI_BUAHfix[$counter] 	= "";
			$GANDENGfix[$counter] 			= "";
			$NIK_GANDENGfix[$counter] 		= "";
			$NIK_GANDENGmore[$counter] 	= "";
			$jmlGandeng[$counter] 		= 0;
		}
		
		$xcount = 0;
		$xcountbuff = 0;
		for ($counter = 0; $counter < $roweffec; $counter++)
		{
			//echo "No ".$xcount.": ".$NO_BCCfix[$xcount]." # ".$NO_BCC[$counter]." <br>";
			if($NO_BCCfix[$xcount] == $NO_BCC[$counter])
			{
				$xcountbuff++;
				$NIK_GANDENGfix[$xcount] 	= $NIK_GANDENGfix[$xcount]." , ".$NIK_GANDENG[$counter];
				$NIK_GANDENGmore[$xcount] 	= $NIK_GANDENG[$counter];
				$jmlGandeng[$xcount] 		= $xcountbuff;
				//echo "NIK GANDENG FIX: ".$NIK_GANDENGfix[$xcount]." <br>";
				//echo "jmlGandeng ke-".$xcount.": ".$jmlGandeng[$xcount]." <br>";
			}
			else
			{
				$NIK_KARYAWANfix[$xcount] 		= $NIK_KARYAWAN[$counter];
				$TANGGALfix[$xcount] 			= $TANGGAL[$counter];
				$NO_BCCfix[$xcount] 			= $NO_BCC[$counter];
				$NO_TPHfix[$xcount] 			= $NO_TPH[$counter];
				$CUSTfix[$xcount] 				= $CUST[$counter];
				$PLANTfix[$xcount] 				= $PLANT[$counter];
				$BLOKfix[$xcount] 				= $BLOK[$counter];
				$TBSfix[$xcount] 				= $TBS[$counter];
				$BRONDOLANfix[$xcount] 			= $BRONDOLAN[$counter];
				$DIKIRIMfix[$xcount] 			= $DIKIRIM[$counter];
				$NIK_MANDORfix[$xcount] 		= $NIK_MANDOR[$counter];
				$NIK_KRANI_BUAHfix[$xcount] 	= $NIK_KRANI_BUAH[$counter];
				$GANDENGfix[$xcount] 			= $GANDENG[$counter];
				$NIK_GANDENGfix[$xcount] 		= $NIK_GANDENG[$counter];
				$NIK_GANDENGmore[$xcount] 	= $NIK_GANDENG[$counter];
				//echo "BCCfix ke ".$xcount." : ".$NO_BCCfix[$xcount]." <br>";
			}
			
			if($counter < $roweffec-1)
			{
				if($NO_BCCfix[$xcount] !== $NO_BCC[$counter+1])
				{
					$xcount++;
					$xcountbuff = 0;
					//echo "xcount now: ".$xcount." <br>";
				}
			}
		}
		//echo "xcount: ".$xcount." roweffec:".$roweffec; die;
		if($xcount > 0){
		//if($roweffec > 0){
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
			
			$shw = 1;
			for ($x = 2; $x < ($xcount+3) ; $x++) {
				//for ($x = 2; $x < ($roweffec+2) ; $x++) {
				$y= ($x-2);
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue("A$x", "$NIK_KARYAWANfix[$y]")
							->setCellValue("B$x", "$TANGGALfix[$y]")
							->setCellValue("C$x", "$NO_BCCfix[$y]")
							->setCellValue("D$x", "$NO_TPHfix[$y]")
							->setCellValue("E$x", "$CUSTfix[$y]")
							->setCellValue("F$x", "$PLANTfix[$y]")
							->setCellValue("G$x", "$BLOKfix[$y]")
							->setCellValue("H$x", "$TBSfix[$y]")
							->setCellValue("I$x", "$BRONDOLANfix[$y]")
							->setCellValue("J$x", "$DIKIRIMfix[$y]")
							->setCellValue("K$x", "$NIK_MANDORfix[$y]")
							->setCellValue("L$x", "$NIK_KRANI_BUAHfix[$y]")
							->setCellValue("M$x", "$GANDENGfix[$y]");
							
				$objPHPExcel->getActiveSheet()->getCell("D$x")->setValueExplicit("$NO_TPHfix[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->getStyle("D$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
				
				$objPHPExcel->getActiveSheet()->getCell("C$x")->setValueExplicit("$NO_BCCfix[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->getStyle("C$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
				
				$objPHPExcel->getActiveSheet()->getCell("G$x")->setValueExplicit("$BLOKfix[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->getStyle("G$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
				//echo "jumlahgandeng: ".$jmlGandeng[$y];
				if($jmlGandeng[$y] > 0)
				{
					$col = 13;
					$shw = $shw-1;
					for($k=0; $k < $jmlGandeng[$y]+1; $k++)
					{
						$show = $shw + $y;
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, "NIK_GANDENG");
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $x, "$NIK_GANDENG[$show]");
						$col++;
						$shw++;
					}
				}
				else
				{
					$objPHPExcel->getActiveSheet()->getCell("N$x")->setValueExplicit("$NIK_GANDENGfix[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->getStyle("N$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
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
		//echo $sql_Download_Crop_Harv;
		}
}
else{
echo "krani blm login";
} 