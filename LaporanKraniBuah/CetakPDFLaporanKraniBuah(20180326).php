<?php 
	session_start();
	error_reporting(0);

	define('FPDF_FONTPATH','font/');
	include "fpdf.php";

	$idcc = $_POST['CClabel'];
	$idba = $_POST['BA'];
	$idafd = $_POST['AFD'];
	$start = $_POST['date1'];
	$end = $_POST['date2'];

	$comp = $_POST['Comp_Name'];

	if (empty($start)) {
		$start = '1999-01-01';
		$end = date('Y-m-d');
	} else {
		$start = explode('/', $start);
		$start = $start[2] . '-' . $start[1] . '-' . $start[0];
		$end = explode('/', $end);
		$end = $end[2] . '-' . $end[1] . '-' . $end[0];
	}

	include("../config/SQL_function.php");
	include("../config/db_connect.php");

	$con = connect();

	$query = "SELECT NAMA_BA FROM T_BUSSINESSAREA WHERE ID_BA = '{$idba}'";
	$res = oci_parse($con, $query);
	oci_execute($res, OCI_DEFAULT);
	while (oci_fetch($res)) {
		$namaba = oci_result($res, "NAMA_BA");
	}

	$sql = "
		SELECT 
			TANGGAL_RENCANA,
			BA, 
			AFD,
			NIK_KERANI_BUAH,
			KRANI_BUAH,
			SUM(SCAN) AS SCAN,
			SUM(MANUAL) AS MANUAL,
			SUM(SCAN) + SUM(MANUAL) AS TOTAL,
			SUM(LOKASI_SALAH) AS LOKASI_SALAH
		FROM (
			SELECT 
		    	TANGGAL_RENCANA,
				BA, 
		    	AFD,
		    	NIK_KERANI_BUAH,
		    	KRANI_BUAH,
		    	STATUS_TPH,
		    	CASE WHEN STATUS_TPH = 'AUTOMATIC' THEN 1 ELSE 0 END AS SCAN,
		    	CASE WHEN STATUS_TPH = 'MANUAL' THEN 1 ELSE 0 END AS MANUAL,
		    	CASE
		        	WHEN JARAK > TOLERANSI_JARAK AND STATUS_LOKASI <> '1' THEN 0
		        	ELSE 1
		    	END AS LOKASI_SALAH
			FROM (
				SELECT 
		    		THRP.TANGGAL_RENCANA,
					TBA.NAMA_BA AS BA,
		    		TA.ID_AFD AS AFD, 
		    		THRP.NIK_KERANI_BUAH,
		    		EBCC.F_GET_EMPNAME(THRP.NIK_KERANI_BUAH) KRANI_BUAH,
		    		THP.ID_RENCANA,
		    		TMT.LATITUDE AS LATITUDE_MASTER_TPH,
		    		TMT.LONGITUDE AS LONGITUDE_MASTER_TPH,
		    		THP.LATITUDE AS LATITUDE_INPUT_BCC,
					THP.LONGITUDE AS LONGITUDE_INPUT_BCC,
		    		THP.STATUS_TPH,
		    		THP.STATUS_LOKASI,
		    		CASE 
		        		WHEN
		            		TMT.LATITUDE IS NULL OR TMT.LONGITUDE IS NULL
		        		THEN 
		            		30.1
		        		ELSE
		            		ROUND(SDO_GEOM.SDO_DISTANCE(
		                		SDO_GEOM.SDO_GEOMETRY(2001, 8307, SDO_GEOM.SDO_POINT_TYPE(NVL(TMT.LATITUDE, 0), NVL(TMT.LONGITUDE, 0), NULL), NULL, NULL),
		                		SDO_GEOM.SDO_GEOMETRY(2001, 8307, SDO_POINT_TYPE(NVL(THP.LATITUDE, 0), NVL(THP.LONGITUDE, 0), NULL), NULL, NULL), 0.0001, 'unit=M'
		            		))
		    		END JARAK,
		    		(SELECT NILAI FROM T_PARAMETER WHERE KETERANGAN = 'RANGE') AS TOLERANSI_JARAK
				FROM T_HEADER_RENCANA_PANEN THRP 
				INNER JOIN T_DETAIL_RENCANA_PANEN TDRP ON THRP.ID_RENCANA = TDRP.ID_RENCANA 
				INNER JOIN T_HASIL_PANEN THP ON TDRP.ID_RENCANA = THP.ID_RENCANA AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC 
				INNER JOIN T_BLOK TB ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK 
				INNER JOIN T_AFDELING TA ON TB.ID_BA_AFD = TA.ID_BA_AFD 
				INNER JOIN T_BUSSINESSAREA TBA ON TA.ID_BA = TBA.ID_BA 
				LEFT JOIN T_STATUS_TO_SAP_EBCC TSAP ON TBA.ID_CC = TSAP.COMP_CODE AND TBA.ID_BA = TSAP.PLANT AND THP.NO_BCC = TSAP.NO_BCC 
				LEFT JOIN TAP_DW.TM_TPH@DEVDW_LINK TMT ON TB.ID_BA_AFD_BLOK = TMT.WERKS || TMT.AFD_CODE || TMT.BLOCK_CODE AND THP.NO_TPH = TMT.NO_TPH 
				LEFT JOIN T_LOG_HASIL_PANEN TLHP ON TLHP.ON_NO_BCC = THP.NO_BCC AND TLHP.INSERTUPDATE = 'INSERT' 
				WHERE 
					TBA.ID_CC = '{$idcc}' 
					AND TBA.ID_BA = '{$idba}' 
					AND TA.ID_AFD = NVL (DECODE ('{$idafd}', 'ALL', null, '{$idafd}'), TA.ID_AFD) 
					AND TO_CHAR(THRP.TANGGAL_RENCANA,'YYYY-MM-DD') BETWEEN '{$start}' AND NVL ('{$end}', '{$start}') 
					AND ( 
		        		(UPPER ('PEMANEN') = 'PEMANEN' AND (THRP.NIK_PEMANEN LIKE '%' || '' || '%' OR F_GET_EMPNAME (THRP.NIK_PEMANEN) LIKE '%' || UPPER ('') || '%')) 
		        		OR (UPPER ('PEMANEN') = 'MANDOR' AND (THRP.NIK_MANDOR LIKE '%' || '' || '%' OR F_GET_EMPNAME (THRP.NIK_MANDOR) LIKE '%' || UPPER ('') || '%')) 
		        		OR (UPPER ('PEMANEN') = 'NO_BCC' AND (THP.NO_BCC LIKE '%' || '' || '%'))) 
				ORDER BY THRP.TANGGAL_RENCANA
				)
			) GROUP BY 
				TANGGAL_RENCANA,
				BA, 
				AFD,
				NIK_KERANI_BUAH,
				KRANI_BUAH,
				LOKASI_SALAH
		ORDER BY 
			TANGGAL_RENCANA, 
			AFD, 
			KRANI_BUAH
	";

	echo $sql;
	die;

	$page_header = 1;
	$to_page = 1;

	$result = oci_parse($con, $sql);
	oci_execute($result, OCI_DEFAULT);

	$newarray = array();
	while (($data = oci_fetch_array($result, OCI_ASSOC))) {
		$newarray[] = $data;
	}

	$arr = array();
	foreach ($newarray as $key => $item) {
		$arr[$item['TANGGAL_RENCANA']][$key] = $item;
	}

	$print_date = date('d/m/Y');

	$pdf = new FPDF("L","cm","A4");
	$pdf->SetMargins(1,2,1);

	foreach ($arr as $key => $val) {
		$pdf->AddPage();

		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(28.1, 0.6, 'Print Date: '.$print_date, 0, 0, 'R');
		$pdf->Ln();
		$pdf->SetFont('Arial', 'B', 12);
		$pdf->Cell(10.5, 0.6, $comp, 1, 0, 'L');
		$pdf->Cell(17.5, 0.6, 'LAPORAN HARIAN KRANI BUAH', 1, 0, 'C');
		$pdf->Ln();
		
		$pdf->SetFont('Arial', '', 9);
		$pdf->Cell(4, 0.4, 'Tanggal Panen', 'L', 0, 'L');
		$pdf->Cell(0.5, 0.4, ':', 0, 0, 'L');
		$pdf->Cell(5, 0.4, $key, 0, 0, 'L');
		$pdf->Cell(1, 0.4, '', 'R', 0, 'L');
		$pdf->Cell(3.5, 0.4, '', '', 0, 'C');
		$pdf->Cell(3, 0.4, 'Page', 0, 0, 'L');
		$pdf->Cell(0.5, 0.4, ':', 0, 0, 'L');
		$pdf->Cell(10.5, 0.4, $page_header.' dari '.$to_page , 'R', 0, 'L');	
		$pdf->Ln();
		
		$pdf->Cell(4, 0.4, 'Estate', 'L', 0, 'L');
		$pdf->Cell(0.5, 0.4, ':', 0, 0, 'L');
		$pdf->Cell(5, 0.4, $namaba, 0, 0, 'L');
		$pdf->Cell(1, 0.4, '', 'R', 0, 'L');
		$pdf->Cell(3.5, 0.4, '', '', 0, 'C');
		$pdf->Cell(3, 0.4, '', 0, 0, 'L');
		$pdf->Cell(0.5, 0.4, '', 0, 0, 'L');
		$pdf->Cell(10.5, 0.4, '', 'R', 0, 'L');
		$pdf->Ln();
		
		$pdf->Cell(9.5, 0.4, '', 'L', 0, 'L');
		$pdf->Cell(1, 0.4, '', 'R', 0, 'L');
		$pdf->Cell(3.5, 0.4, '', '', 0, 'C');
		$pdf->Cell(14, 0.4, '', 'R', 0, 'L');
		
		$pdf->Ln();
		$pdf->SetFont('Arial', 'B', 9);
		
		$pdf->setFillColor(184, 184, 184);
		$pdf->Cell(2, 1.5, 'AFD', 1, 0, 'C', 1);
		$pdf->Cell(3.1, 1.5, 'NIK', 1, 0, 'C', 1);
		$pdf->Cell(5.4, 1.5, 'NAMA', 1, 0, 'C', 1);
		$pdf->Cell(8.6, 0.5, 'JUMLAH EBCC', 1, 0, 'C', 1);
		$pdf->Cell(8.9, 0.5, 'TELAH DIPERIKSA', 1, 0, 'C', 1);
		
		$pdf->Ln();
		$pdf->Cell(2, 0.5, '', 0, 0, 'C', false);
		$pdf->Cell(3.1, 0.5, '', 0, 0, 'C', false);
		$pdf->Cell(5.4, 0.5, '', 0, 0, 'C', false);
		$pdf->Cell(1.9, 1, 'SCAN', 1, 0, 'C', 1);
		$pdf->Cell(1.9, 1, 'MANUAL', 1, 0, 'C', 1);
		$pdf->Cell(1.9, 1, 'TOTAL', 1, 0, 'C', 1);
		$pdf->Cell(2.9, 1, 'LOKASI SALAH *', 1, 0, 'C', 1);
		$pdf->Cell(3, 1, 'NAMA ASLAP', 1, 0, 'C', 1);
		$pdf->Cell(3, 1, 'TTD', 1, 0, 'C', 1);
		$pdf->Cell(2.9, 0.5, 'JUMLAH EBCC', 'T,L,R', 0, 'C', 1);
		$pdf->Ln();
		
		$pdf->Cell(25.1 ,0.5, '', 0, 0, 'C', false);
		$pdf->Cell(2.9, 0.5, 'YANG DIKOREKSI', 'L,B,R', 0, 'C', 1);

		$total_scan = $total_manual = $total_total = $total_lokasi_salah = 0;
		foreach ($val as $key => $value) {
			$pdf->SetFont('Arial', '', 9);
			$pdf->Ln();
			$pdf->Cell(2, 0.5, $value['AFD'], 1, 0, 'C');
			$pdf->Cell(3.1, 0.5, $value['NIK_KERANI_BUAH'], 1, 0, 'L');
			$pdf->Cell(5.4, 0.5, $value['KRANI_BUAH'], 1, 0, 'L');
			$pdf->Cell(1.9, 0.5, $value['SCAN'], 1, 0, 'C');
			$pdf->Cell(1.9, 0.5, $value['MANUAL'], 1, 0, 'C');
			$pdf->Cell(1.9, 0.5, $value['TOTAL'], 1, 0, 'C');
			$pdf->Cell(2.9, 0.5, $value['LOKASI_SALAH'], 1, 0, 'C');
			$pdf->Cell(3, 0.5, '', 1, 0, 'C');
			$pdf->Cell(3, 0.5, '', 1, 0, 'C');
			$pdf->Cell(2.9, 0.5, '', 1, 0, 'C');

			$total_scan = $total_scan + $value['SCAN'];
			$total_manual = $total_manual + $value['MANUAL'];
			$total_total = $total_total + $value['TOTAL'];
			$total_lokasi_salah = $total_lokasi_salah + $value['LOKASI_SALAH'];
		}

		$pdf->Ln();
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(10.5, 0.5, 'TOTAL', 1, 0, 'C', 1);
		$pdf->SetFont('Arial', '', 9);
		$pdf->Cell(1.9, 0.5, $total_scan, 1, 0, 'C');
		$pdf->Cell(1.9, 0.5, $total_manual, 1, 0, 'C');
		$pdf->Cell(1.9, 0.5, $total_total, 1, 0, 'C');
		$pdf->Cell(2.9, 0.5, $total_lokasi_salah, 1, 0, 'C');
		$pdf->Cell(8.9, 0.5, '', 1, 0, 'C', 1);

		$pdf->Ln();
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(6.1, 0.5, 'Disetujui Oleh,', 1, 0, 'C');
		$pdf->Cell(16, 0.5, '', 'T,L,R', 0, 'C');
		$pdf->Cell(5.9, 0.5, 'Data Telah Diverifikasi Oleh,', 1, 0, 'C');

		$pdf->Ln();
		$pdf->Cell(3, 0.5, '', 'LR', 0, 'C');
		$pdf->Cell(3.1, 0.5, '', 'LR', 0, 'C');
		$pdf->Cell(16, 0.5, ' Keterangan:', 'LR', 0, 'L');
		$pdf->Cell(5.9, 0.5, '', 'LR', 0, 'C');

		$pdf->Ln();
		$pdf->Cell(3, 0.5, '', 'LR', 0, 'C');
		$pdf->Cell(3.1, 0.5, '', 'LR', 0, 'C');
		$pdf->Cell(16, 0.5, ' * Lokasi Salah = EBCC yang lokasinya diinput manual (bukan scan) dan berada diluar radius master TPH', 'LR', 0, 'L');
		$pdf->Cell(5.9, 0.5, '', 'LR', 0, 'C');

		$pdf->Ln();
		$pdf->Cell(3, 0.5, '', 'LR', 0, 'C');
		$pdf->Cell(3.1, 0.5, '', 'LR', 0, 'C');
		$pdf->Cell(16, 0.5, '   Jika terdapat EBCC yang lokasi salah, maka harus dilakukan Koreksi BCC', 'LR', 0, 'L');
		$pdf->Cell(5.9, 0.5, '', 'LR', 0, 'C');

		$pdf->Ln();
		$pdf->Cell(3, 0.5, 'Kepala Kebun', 1, 0, 'C');
		$pdf->Cell(3.1, 0.5, 'Estate Manager', 1, 0, 'C');
		$pdf->Cell(16, 0.5, '', 'LBR', 0, 'L');
		$pdf->Cell(5.9, 0.5, 'PGA', 1, 0, 'C');
	}

	$pdf->Output();
?>