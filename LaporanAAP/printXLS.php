<?php
session_start();

if(isset($_SESSION["NIK"]) && isset($_SESSION["sql_t_AAP"])){
	
		$sql_t_AAP = $_SESSION["sql_t_AAP"];
		$userlogin = $_SESSION["NIK"]; 

		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		
		
		$result_t_AAP = oci_parse($con, $sql_t_AAP);
		oci_execute($result_t_AAP, OCI_DEFAULT);
		$nrows = oci_fetch_all($result_t_AAP, $res_AAP);
		
		$cek_done = true;
			$jml_max_gandeng = 1;
			$cek_max_gandeng = 1;
			$roweffec_AAP = 0;
			//echo"<br><br><br>";
			foreach($res_AAP['ID_RENCANA'] as $key_AAP=>$item_AAP){
				//echo $res_AAP['ID_RENCANA'][$key_AAP]." ".$cek_max_gandeng."<br>";
				if($cek_done){
					if($res_AAP['ID_RENCANA'][$key_AAP]==$res_AAP['ID_RENCANA'][$key_AAP+1] and $res_AAP['ID_AFD'][$key_AAP]==$res_AAP['ID_AFD'][$key_AAP+1] and $res_AAP['ID_BLOK'][$key_AAP]==$res_AAP['ID_BLOK'][$key_AAP+1]){
						$cek_done = false;
						$no_gdg = $roweffec_AAP;
						$data_gdg[$roweffec_AAP]['NIK_GANDENG'][] = $res_AAP['NIK_GANDENG'][$key_AAP];
						$data_gdg[$roweffec_AAP]['NAMA_GANDENG'][] = $res_AAP['NAMA_GANDENG'][$key_AAP];
						$cek_max_gandeng++;
						
						if($jml_max_gandeng<$cek_max_gandeng){ $jml_max_gandeng=$cek_max_gandeng; }
					} else {
						$cek_done = true;
						$data_gdg[$roweffec_AAP]['NIK_GANDENG'][] = $res_AAP['NIK_GANDENG'][$key_AAP];
						$data_gdg[$roweffec_AAP]['NAMA_GANDENG'][] = $res_AAP['NAMA_GANDENG'][$key_AAP];
						
						
						$cek_max_gandeng = 1;
					}
					$data_gdg[$roweffec_AAP]['ID_RENCANA'] = $res_AAP['ID_RENCANA'][$key_AAP];
					$data_gdg[$roweffec_AAP]['TANGGAL_RENCANA'] = $res_AAP['TANGGAL_RENCANA'][$key_AAP];
					$data_gdg[$roweffec_AAP]['ID_AFD'] = $res_AAP['ID_AFD'][$key_AAP];
					$data_gdg[$roweffec_AAP]['ID_BLOK'] = $res_AAP['ID_BLOK'][$key_AAP];
					$data_gdg[$roweffec_AAP]['BLOK_NAME'] = $res_AAP['BLOK_NAME'][$key_AAP];
					$data_gdg[$roweffec_AAP]['NIK_KERANI_BUAH'] = $res_AAP['NIK_KERANI_BUAH'][$key_AAP];
					$data_gdg[$roweffec_AAP]['NAMA_KERANI_BUAH'] = $res_AAP['NAMA_KERANI_BUAH'][$key_AAP];
					$data_gdg[$roweffec_AAP]['NIK_MANDOR'] = $res_AAP['NIK_MANDOR'][$key_AAP];
					$data_gdg[$roweffec_AAP]['NAMA_MANDOR'] = $res_AAP['NAMA_MANDOR'][$key_AAP];
					$data_gdg[$roweffec_AAP]['NIK_PEMANEN'] = $res_AAP['NIK_PEMANEN'][$key_AAP];
					$data_gdg[$roweffec_AAP]['NAMA_PEMANEN'] = $res_AAP['NAMA_PEMANEN'][$key_AAP];
					$data_gdg[$roweffec_AAP]['LUASAN_PANEN'] = number_format((float)$res_AAP['LUASAN_PANEN'][$key_AAP], 2, '.', '');
					$roweffec_AAP++;
				} else {
					if($res_AAP['ID_RENCANA'][$key_AAP]==$res_AAP['ID_RENCANA'][$key_AAP+1] and $res_AAP['ID_AFD'][$key_AAP]==$res_AAP['ID_AFD'][$key_AAP+1] and $res_AAP['ID_BLOK'][$key_AAP]==$res_AAP['ID_BLOK'][$key_AAP+1]){
						$cek_done = false;
						$data_gdg[$no_gdg]['NIK_GANDENG'][] = $res_AAP['NIK_GANDENG'][$key_AAP];
						$data_gdg[$no_gdg]['NAMA_GANDENG'][] = $res_AAP['NAMA_GANDENG'][$key_AAP];
						$cek_max_gandeng++;
						if($jml_max_gandeng<$cek_max_gandeng){ $jml_max_gandeng=$cek_max_gandeng; }
					} else {
						
						$cek_done = true;
						$data_gdg[$no_gdg]['NIK_GANDENG'][] = $res_AAP['NIK_GANDENG'][$key_AAP];
						$data_gdg[$no_gdg]['NAMA_GANDENG'][] = $res_AAP['NAMA_GANDENG'][$key_AAP];
						
						if($jml_max_gandeng<$cek_max_gandeng){ $jml_max_gandeng=$cek_max_gandeng; }
						$cek_max_gandeng = 1;
					}
				}
				
			}
		
		/* echo"<pre style='text-align:left'>";
			print_r($data_gdg); exit;
			echo"</pre>";
			exit;  */
		
		if($roweffec_AAP > 0){
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
			header('Content-Disposition: attachment;filename="AAP Report.xls"');
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
			->setCellValue("A1", "TANGGAL")
			->setCellValue("B1", "AFD")
			->setCellValue("C1", "BLOK")
			->setCellValue("D1", "BLOK DESK")
			->setCellValue("E1", "NIK KRANI BUAH")
			->setCellValue("F1", "NAMA KRANI BUAH")
			->setCellValue("G1", "NIK MANDOR")
			->setCellValue("H1", "NAMA MANDOR")
			->setCellValue("I1", "NIK KARYAWAN")
			->setCellValue("J1", "NAMA KARYAWAN")
			->setCellValue("K1", "HA PANEN");
			$ascii=76;
			$col1 = 0;
			$col2 = 1;
			for($jj=0;$jj<$jml_max_gandeng;$jj++){
				
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue("".chr(($ascii+$col1))."1", "NIK GANDENG ".($jj+1));
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue("".chr(($ascii+$col2))."1", "NAMA GANDENG ".($jj+1));
				$col1=$col1+2; $col2=$col2+2;
			} 
			
			$tanda = 0;
			for ($x = 0; $x < $roweffec_AAP ; $x++) {
				$xj = $x+2;
				if($tanda==0){
					$val_luasan_panen = $data_gdg[$x]['LUASAN_PANEN'];
				} else {
					if($data_gdg[$x]['TANGGAL_RENCANA']!==$data_gdg[$x-1]['TANGGAL_RENCANA'] || $data_gdg[$x]['ID_AFD']!==$data_gdg[$x-1]['ID_AFD'] || $data_gdg[$x]['ID_BLOK']!==$data_gdg[$x-1]['ID_BLOK'] || $data_gdg[$x]['NIK_PEMANEN']!==$data_gdg[$x-1]['NIK_PEMANEN']){
						$val_luasan_panen = $data_gdg[$x]['LUASAN_PANEN'];
					} else {
						$val_luasan_panen = $data_gdg[$x-1]['LUASAN_PANEN'];
					}
				}
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue("A$xj", $data_gdg[$x]['TANGGAL_RENCANA'])
							->setCellValue("B$xj", $data_gdg[$x]['ID_AFD'])
							->setCellValue("C$xj", $data_gdg[$x]['ID_BLOK'])
							->setCellValue("D$xj", $data_gdg[$x]['BLOK_NAME'])
							->setCellValue("E$xj", $data_gdg[$x]['NIK_KERANI_BUAH'])
							->setCellValue("F$xj", $data_gdg[$x]['NAMA_KERANI_BUAH'])
							->setCellValue("G$xj", $data_gdg[$x]['NIK_MANDOR'])
							->setCellValue("H$xj", $data_gdg[$x]['NAMA_MANDOR'])
							->setCellValue("I$xj", $data_gdg[$x]['NIK_PEMANEN'])
							->setCellValue("J$xj", $data_gdg[$x]['NAMA_PEMANEN'])
							->setCellValue("K$xj", $val_luasan_panen);
				$ind_gdg=0;
				for($xjml=0;$xjml<$jml_max_gandeng;$xjml++){
					if(isset($data_gdg[$x]['NIK_GANDENG'][$xjml])){
						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue(chr(($ascii+$ind_gdg+0))."$xj", $data_gdg[$x]['NIK_GANDENG'][$xjml])
							->setCellValue(chr(($ascii+$ind_gdg+1))."$xj", $data_gdg[$x]['NAMA_GANDENG'][$xjml]);
					} else {
						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue(chr(($ascii+$ind_gdg+0))."$xj", "-")
							->setCellValue(chr(($ascii+$ind_gdg+1))."$xj", "-");
					}
					$ind_gdg = $ind_gdg+2;
				} 			
				
				//$objPHPExcel->getActiveSheet()->getCell("G$x")->setValueExplicit("$BLOKfix[$y]", PHPExcel_Cell_DataType::TYPE_STRING);
				//$objPHPExcel->getActiveSheet()->getStyle("G$x")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						
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