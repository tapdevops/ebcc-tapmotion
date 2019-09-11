<?php
session_start();

if (isset($_SESSION["NIK"])) {

	$sql_t_BCC = $_SESSION["sql_t_BCC"];
	$userlogin = $_SESSION["NIK"];

	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();
	//$rbtn_type = $_SESSION['rbtn_type'];

	$sql = $sql_t_BCC;
	$result = oci_parse($con, $sql);
	oci_execute($result, OCI_DEFAULT);
	$i = 0;
	while (oci_fetch($result)) {
		$i++;
		$NO[] 					= $i;
		$VAL_DATE_TIME[] 		= oci_result($result_t_BCC, "VAL_DATE_TIME");
		$VAL_NIK_VALIDATOR[] 		= oci_result($result_t_BCC, "VAL_NIK_VALIDATOR");
		$VAL_NAMA_VALIDATOR[] 				= oci_result($result_t_BCC, "VAL_NAMA_VALIDATOR");
		$VAL_JABATAN_VALIDATOR[] 		= oci_result($result_t_BCC, "VAL_JABATAN_VALIDATOR");
		$VAL_WERKS[] 				= oci_result($result_t_BCC, "VAL_WERKS");
		$VAL_AFD_CODE[] 			= oci_result($result_t_BCC, "VAL_AFD_CODE");
		$VAL_BLOCK_CODE[] 			= oci_result($result_t_BCC, "VAL_BLOCK_CODE");
		$VAL_BLOCK_NAME[] 			= oci_result($result_t_BCC, "VAL_BLOCK_NAME");
		$VAL_EBCC_CODE[] 		= oci_result($result_t_BCC, "VAL_EBCC_CODE");
		$VAL_TPH_CODE[] 			= oci_result($result_t_BCC, "VAL_TPH_CODE");
		$VAL_DELIVERY_TICKET[] 			= oci_result($result_t_BCC, "VAL_DELIVERY_TICKET");
		$VAL_JML_BM[] 			= oci_result($result_t_BCC, "VAL_JML_BM");
		$VAL_JML_BK[] 			= oci_result($result_t_BCC, "VAL_JML_BK");
		$VAL_JML_MS[] 			= oci_result($result_t_BCC, "VAL_JML_MS");
		$VAL_JML_OR[] 			= oci_result($result_t_BCC, "VAL_JML_OR");
		$VAL_JML_BB[] 			= oci_result($result_t_BCC, "VAL_JML_BB");
		$VAL_JML_JK[] 			= oci_result($result_t_BCC, "VAL_JML_JK");
		$VAL_JML_BA[] 			= oci_result($result_t_BCC, "VAL_JML_BA");
		$VAL_JML_BRD[] 			= oci_result($result_t_BCC, "VAL_JML_BRD");
		$VAL_JJG_PANEN[] 			= oci_result($result_t_BCC, "VAL_JJG_PANEN");
		$EBCC_ID_RENCANA[] 			= oci_result($result_t_BCC, "EBCC_ID_RENCANA");
		$EBCC_NO_BCC[] 			= oci_result($result_t_BCC, "EBCC_NO_BCC");
		$EBCC_WERKS[] 			= oci_result($result_t_BCC, "EBCC_WERKS");
		$EBCC_NIK_KERANI_BUAH[] 			= oci_result($result_t_BCC, "EBCC_NIK_KERANI_BUAH");
		$EBCC_NAMA_KERANI_BUAH[] 			= oci_result($result_t_BCC, "EBCC_NAMA_KERANI_BUAH");
		$EBCC_JABATAN_KERANI_BUAH[] 			= oci_result($result_t_BCC, "EBCC_JABATAN_KERANI_BUAH");
		$EBCC_DATE_TIME[] 			= oci_result($result_t_BCC, "EBCC_DATE_TIME");
		$EBCC_AFD_CODE[] 			= oci_result($result_t_BCC, "EBCC_AFD_CODE");
		$EBCC_BLOCK_CODE[] 			= oci_result($result_t_BCC, "EBCC_BLOCK_CODE");
		$EBCC_TPH_CODE[] 			= oci_result($result_t_BCC, "EBCC_TPH_CODE");
		$EBCC_JML_BM[] 			= oci_result($result_t_BCC, "EBCC_JML_BM");
		$EBCC_JML_BK[] 			= oci_result($result_t_BCC, "EBCC_JML_BK");
		$EBCC_JML_MS[] 			= oci_result($result_t_BCC, "EBCC_JML_MS");
		$EBCC_JML_OR[] 			= oci_result($result_t_BCC, "EBCC_JML_OR");
		$EBCC_JML_BB[] 			= oci_result($result_t_BCC, "EBCC_JML_BB");
		$EBCC_JML_JK[] 			= oci_result($result_t_BCC, "EBCC_JML_JK");
		$EBCCJML_BA[] 			= oci_result($result_t_BCC, "EBCCJML_BA");
		$EBCC_JML_BRD[] 			= oci_result($result_t_BCC, "EBCC_JML_BRD");
		$EBCC_JJG_PANEN[] 			= oci_result($result_t_BCC, "EBCC_JJG_PANEN");
		//Added by Ardo 16-08-2016 : Synchronize BCC - Laporan
		$STATUS_EXPORT[]			= oci_result($result, "STATUS_EXPORT");
	}
	$roweffec = oci_num_rows($result);

	if ($roweffec > 0) {
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
		header('Content-Disposition: attachment;filename="BCC Report.xls"');
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
			->mergeCells("A" . (1) . ":A" . (2))
			->setCellValue("A1", "No")
			->mergeCells("B" . (1) . ":B" . (2))
			->setCellValue("B1", "Tanggal")
			->mergeCells("C" . (1) . ":C" . (2))
			->setCellValue("C1", "NIK Pembuat")
			->mergeCells("D" . (1) . ":D" . (2))
			->setCellValue("D1", "Nama Pembuat")
			->mergeCells("E" . (1) . ":E" . (2))
			->setCellValue("E1", "Jabatan Pembuat")
			->mergeCells("F" . (1) . ":F" . (2))
			->setCellValue("F1", "Kode BA")
			->mergeCells("G" . (1) . ":G" . (2))
			->setCellValue("G1", "Business Area")
			->mergeCells("H" . (1) . ":H" . (2))
			->setCellValue("H1", "Kode AFD")
			->mergeCells("I" . (1) . ":I" . (2))
			->setCellValue("I1", "Kode Block")
			->mergeCells("J" . (1) . ":J" . (2))
			->setCellValue("J1", "Deskripsi Block")
			->mergeCells("K" . (1) . ":K" . (2))
			->setCellValue("K1", "TPH")
			->mergeCells("L" . (1) . ":L" . (2))
			->setCellValue("L1", "Code Sampling EBCC")
			->mergeCells("M" . (1) . ":M" . (2))
			->setCellValue("M1", "Status QR Code TPH")
			->mergeCells("N" . (1) . ":N" . (2))
			->setCellValue("N1", "BM (jjg)")
			->mergeCells("O" . (1) . ":O" . (2))
			->setCellValue("O1", "BK (jjg)")
			->mergeCells("P" . (1) . ":P" . (2))
			->setCellValue("P1", "MS (jjg)")
			->mergeCells("Q" . (1) . ":Q" . (2))
			->setCellValue("Q1", "OR (jjg)")
			->mergeCells("R" . (1) . ":R" . (2))
			->setCellValue("R1", "BB (jjg)")
			->mergeCells("S" . (1) . ":S" . (2))
			->setCellValue("S1", "JK (jjg)")
			->mergeCells("T" . (1) . ":T" . (2))
			->setCellValue("T1", "BA (jjg)")
			->mergeCells("U" . (1) . ":U" . (2))
			->setCellValue("U1", "Total Jenjang Panen")
			->mergeCells("V" . (1) . ":V" . (2))
			->setCellValue("V1", "NIK Krani Buah")
			->mergeCells("W" . (1) . ":W" . (2))
			->setCellValue("W1", "Nama Krani Buah")
			->mergeCells("X" . (1) . ":X" . (2))
			->setCellValue("X1", "No BCC")
			->mergeCells("Y" . (1) . ":Y" . (2))
			->setCellValue("Y1", "Status QR Code TPH")
			->mergeCells("Z" . (1) . ":Z" . (2))
			->setCellValue("Z1", "BM (jjg)")
			->mergeCells("AA" . (1) . ":AA" . (2))
			->setCellValue("AA1", "BK (jjg)")
			->mergeCells("AB" . (1) . ":AB" . (2))
			->setCellValue("AB1", "MS (jjg)")
			->mergeCells("AC" . (1) . ":AC" . (2))
			->setCellValue("AC1", "OR (jjg)")
			->mergeCells("AD" . (1) . ":AD" . (2))
			->setCellValue("AD1", "BB (jjg)")
			->mergeCells("AE" . (1) . ":E" . (2))
			->setCellValue("AE1", "JK (jjg)")
			->mergeCells("AF" . (1) . ":AF" . (2))
			->setCellValue("AF1", "BA (jjg)")
			->mergeCells("AG" . (1) . ":AG" . (2))
			->setCellValue("AG1", "Total Jengan Panen")
			->mergeCells("AH" . (1) . ":AH" . (2))
			->setCellValue("AH1", "Lihat Foto")
			->mergeCells("AI" . (1) . ":AI" . (2))
			->setCellValue("AI1", "Akurasi Sampling EBCC")
			->mergeCells("AJ" . (1) . ":AJ" . (2))
			->setCellValue("AJ1", "Akurasi Kualitas MS");

		for ($x = 3; $x < ($roweffec + 3); $x++) {
			$y = ($x - 3);
			$objPHPExcel->getActiveSheet()->getStyle('E3')
				->getNumberFormat()
				->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

			$fixedBCC = separator($NO_BCC[$y]);

			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue("A$x", "$NO[$y]")
				->setCellValue("B$x", "$VAL_DATE_TIME[$y]")
				->setCellValue("C$x", "$VAL_NIK_VALIDATOR")
				->setCellValue("D$x", "$VAL_NAMA_VALIDATOR[$y]")
				->setCellValue("E$x", "$VAL_JABATAN_VALIDATOR[$y]")
				->setCellValue("F$x", "$VAL_WERKS[$y]")
				->setCellValue("G$x", "Belum Ada")
				->setCellValue("H$x", "$VAL_AFD_CODE[$y]")
				->setCellValue("I$x", "$VAL_BLOCK_CODE[$y]")
				->setCellValue("J$x", "$VAL_BLOCK_NAME[$y]")
				->setCellValue("K$x", "$VAL_TPH_CODE[$y]")
				->setCellValue("L$x", "$VAL_EBCC_CODE[$y]")
				->setCellValue("M$x", "Belum Ada")
				->setCellValue("N$x", "$VAL_JML_BM[$y]")
				->setCellValue("O$x", "$VAL_JML_BK[$y]")
				->setCellValue("P$x", "$VAL_JML_MS[$y]")
				->setCellValue("Q$x", "$VAL_JML_OR[$y]")
				->setCellValue("R$x", "$VAL_JML_BB[$y]")
				->setCellValue("S$x", "$VAL_JML_JK[$y]")
				->setCellValue("T$x", "$VAL_JML_BA[$y]")
				->setCellValue("U$x", "$VAL_JJG_PANEN[$y]")
				->setCellValue("V$x", "$EBCC_NIK_KERANI_BUAH[$y]")
				->setCellValue("W$x", "$EBCC_NAMA_KERANI_BUAH[$y]")
				->setCellValue("X$x", "$EBCC_NO_BCC[$y]")
				->setCellValue("Y$x", "Belum Ada")
				->setCellValue("Z$x", "$EBCC_JML_BM[$y]")
				->setCellValue("AA$x", "$EBCC_JML_BK[$y]")
				->setCellValue("AB$x", "$EBCC_JML_MS[$y]")
				->setCellValue("AC$x", "$EBCC_JML_OR[$y]")
				->setCellValue("AD$x", "$EBCC_JML_BB[$y]")
				->setCellValue("AE$x", "$EBCC_JML_JK[$y]")
				->setCellValue("AF$x", "$EBCCJML_BA[$y]")
				->setCellValue("AG$x", "$EBCC_JJG_PANEN[$y]")
				->setCellValue("AH$x", "Belum Ada")
				->setCellValue("AI$x", "Belum Ada")
				->setCellValue("AJ$x", "Belum Ada");
		}


		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Sheet1');

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	} else {
		echo "report tidak ada";
	}
} else {
	echo "krani blm login" . $_SESSION["NIK"] . " *** " . $_SESSION["sql_t_BCC"];
}
