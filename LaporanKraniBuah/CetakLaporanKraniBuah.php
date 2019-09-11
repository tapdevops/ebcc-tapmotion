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
		    		EBCC.F_GET_EMPNAME@PRODDB_LINK(THRP.NIK_KERANI_BUAH) KRANI_BUAH,
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
		    		(SELECT NILAI FROM EBCC.T_PARAMETER@PRODDB_LINK WHERE KETERANGAN = 'RANGE') AS TOLERANSI_JARAK
				FROM EBCC.T_HEADER_RENCANA_PANEN@PRODDB_LINK THRP 
				INNER JOIN EBCC.T_DETAIL_RENCANA_PANEN@PRODDB_LINK TDRP ON THRP.ID_RENCANA = TDRP.ID_RENCANA 
				INNER JOIN EBCC.T_HASIL_PANEN@PRODDB_LINK THP ON TDRP.ID_RENCANA = THP.ID_RENCANA AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC 
				INNER JOIN EBCC.T_BLOK@PRODDB_LINK TB ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK 
				INNER JOIN EBCC.T_AFDELING@PRODDB_LINK TA ON TB.ID_BA_AFD = TA.ID_BA_AFD 
				INNER JOIN EBCC.T_BUSSINESSAREA@PRODDB_LINK TBA ON TA.ID_BA = TBA.ID_BA 
				LEFT JOIN EBCC.T_STATUS_TO_SAP_EBCC@PRODDB_LINK TSAP ON TBA.ID_CC = TSAP.COMP_CODE AND TBA.ID_BA = TSAP.PLANT AND THP.NO_BCC = TSAP.NO_BCC 
				LEFT JOIN TAP_DW.TM_TPH TMT ON TB.ID_BA_AFD_BLOK = TMT.WERKS || TMT.AFD_CODE || TMT.BLOCK_CODE AND THP.NO_TPH = TMT.NO_TPH 
				LEFT JOIN EBCC.T_LOG_HASIL_PANEN@PRODDB_LINK TLHP ON TLHP.ON_NO_BCC = THP.NO_BCC AND TLHP.INSERTUPDATE = 'INSERT' 
				WHERE 
					TBA.ID_CC = '{$idcc}' 
					AND TBA.ID_BA = '{$idba}' 
					AND TA.ID_AFD = NVL (DECODE ('{$idafd}', 'ALL', null, '{$idafd}'), TA.ID_AFD) 
					AND TO_CHAR(THRP.TANGGAL_RENCANA,'YYYY-MM-DD') BETWEEN '{$start}' AND NVL ('{$end}', '{$start}') 
					AND ( 
		        		(UPPER ('PEMANEN') = 'PEMANEN' AND (THRP.NIK_PEMANEN LIKE '%' || '' || '%' OR EBCC.F_GET_EMPNAME@PRODDB_LINK(THRP.NIK_PEMANEN) LIKE '%' || UPPER ('') || '%')) 
		        		OR (UPPER ('PEMANEN') = 'MANDOR' AND (THRP.NIK_MANDOR LIKE '%' || '' || '%' OR EBCC.F_GET_EMPNAME@PRODDB_LINK(THRP.NIK_MANDOR) LIKE '%' || UPPER ('') || '%')) 
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

	//echo $sql;
	//die();
	
	include("../config/dw_tap_config.php");
	$cons = connect();

	$result = oci_parse($cons, $sql);
	oci_execute($result, OCI_DEFAULT);

	$newarray = array();
	while (($data = oci_fetch_array($result, OCI_ASSOC))) {
		$newarray[] = $data;
	}

	$res = [];
	if (empty($newarray)) {
		$res['length'] = 0;
		$res['output'] = 'Data tidak ada pada periode ini';
	} else {
		$res['length'] = 1;
		$res['output'] = '';
	}

	echo json_encode($res);

	exit();
?>
