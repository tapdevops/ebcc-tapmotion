<?php
session_start();

if (isset($_POST["Afdeling"]) || isset($_POST["date1"]) || isset($_POST["date2"]) || isset($_POST["date3"]) || isset($_POST["selectfinder"]) || isset($_POST["typefinder"])) {
	$valueAfdeling 		= $_POST["Afdeling"];
	$date1 = date("Y-m-d", strtotime($_POST["date1"]));
	$date2 = date("Y-m-d", strtotime($_POST["date2"]));
	$selectfinder 	= $_POST["selectfinder"];
	$typefinder 	= $_POST["typefinder"];
	$ID_BA 		= $_SESSION['subID_BA_Afd'];
	$ID_CC 		= $_SESSION['subID_CC'];
	$status_export = $_POST["status_export"];
	if ($status_export == 'ALL') {
		$and_status = "";
	} else if ($status_export == 'Belum Cetak') {
		$and_status = "where status_export = 'Belum Cetak'";
	} else if ($status_export == 'Tercetak') {
		$and_status = "where status_export = 'Tercetak'";
	} else if ($status_export == 'Sudah Export') {
		$and_status = "where status_export = 'Sudah Export'";
	} else if ($status_export == 'Sudah Post') {
		$and_status = "where status_export = 'Sudah Post'";
	}


	//include("Filter/query2.php");

	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();

	if ($date2 == "1970-01-01") {
		$date2 = '';
		//echo $date2;
	}

	if ($date1 == "1970-01-01") {
		//echo "salah";
		$_SESSION[err] 		= "please choose date";
		header("Location:LaporanBCCCompare.php");
	} else {

		//Edited by Ardo 16-08-2016 : Synchronize BCC - Laporan BCC
		$sql_t_BCC = "/* Formatted on 7/30/2019 1:07:28 PM (QP5 v5.136.908.31019) */

		SELECT *
		
			FROM    ( 
		
			SELECT EBCC_VAL.VAL_EBCC_CODE,
		
												EBCC_VAL.VAL_WERKS,
		
												EBCC_VAL.VAL_NIK_VALIDATOR,
		
												EMP_VAL.EMPLOYEE_FULLNAME AS VAL_NAMA_VALIDATOR,
		
												EMP_VAL.EMPLOYEE_POSITION AS VAL_JABATAN_VALIDATOR,
		
												EBCC_VAL.VAL_DATE_TIME,
		
												EBCC_VAL.VAL_AFD_CODE,
		
												EBCC_VAL.VAL_BLOCK_CODE,
		
												SUBBLOCK.BLOCK_NAME AS VAL_BLOCK_NAME,
		
												EBCC_VAL.VAL_TPH_CODE,
		
												EBCC_VAL.VAL_DELIVERY_TICKET,
		
												NVL (SUM (EBCC_VAL.JML_BM), 0) AS VAL_JML_BM,
		
												NVL (SUM (EBCC_VAL.JML_BK), 0) AS VAL_JML_BK,
		
												NVL (SUM (EBCC_VAL.JML_MS), 0) AS VAL_JML_MS,
		
												NVL (SUM (EBCC_VAL.JML_OR), 0) AS VAL_JML_OR,
		
												NVL (SUM (EBCC_VAL.JML_BB), 0) AS VAL_JML_BB,
		
												NVL (SUM (EBCC_VAL.JML_JK), 0) AS VAL_JML_JK,
		
												NVL (SUM (EBCC_VAL.JML_BA), 0) AS VAL_JML_BA,
		
												NVL (SUM (EBCC_VAL.JML_BRD), 0) AS VAL_JML_BRD,
		
												NVL (SUM (EBCC_VAL.VAL_JJG_PANEN), 0) AS VAL_JJG_PANEN
		
									 --'http://tap-motion.tap-agri.com/mobile_estate/upload_image/'||IMG_EBCC_VAL.IMAGE_NAME||'.jpg' as VAL_IMAGE_NAME
		
									 FROM    (
		
									 SELECT HD_EBCC_VAL.EBCC_CODE AS VAL_EBCC_CODE,
		
																	 HD_EBCC_VAL.WERKS AS VAL_WERKS,
		
																	 HD_EBCC_VAL.NIK_VALIDATOR AS VAL_NIK_VALIDATOR,
		
																	 HD_EBCC_VAL.DATE_TIME AS VAL_DATE_TIME,
		
																	 HD_EBCC_VAL.AFD_CODE AS VAL_AFD_CODE,
		
																	 HD_EBCC_VAL.BLOCK_CODE AS VAL_BLOCK_CODE,
		
																	 HD_EBCC_VAL.TPH_CODE AS VAL_TPH_CODE,
		
																	 HD_EBCC_VAL.DELIVERY_TICKET
		
																			AS VAL_DELIVERY_TICKET,
		
																	 DETAIL_EBCC_VAL.JML_BM,
		
																	 DETAIL_EBCC_VAL.JML_BK,
		
																	 DETAIL_EBCC_VAL.JML_MS,
		
																	 DETAIL_EBCC_VAL.JML_OR,
		
																	 DETAIL_EBCC_VAL.JML_BB,
		
																	 DETAIL_EBCC_VAL.JML_JK,
		
																	 DETAIL_EBCC_VAL.JML_BA,
		
																	 DETAIL_EBCC_VAL.JML_BRD,
		
																	 CASE
		
																			WHEN PAR.KETERANGAN = 'BUNCH_HARVEST'
		
																			THEN
		
																				 DETAIL_EBCC_VAL.QTYS
		
																			ELSE
		
																				 0
		
																	 END
		
																			AS VAL_JJG_PANEN
		
															FROM (SELECT EBCC_CODE,
		
																					 INSERT_USER AS NIK_VALIDATOR,
		
																					 TRUNC (DATE_TIME) AS DATE_TIME,
		
																					 WERKS,
		
																					 AFD_CODE,
		
																					 BLOCK_CODE,
		
																					 TPH_CODE,
		
																					 DELIVERY_TICKET
		
																			FROM MOBILE_ESTATE.TR_EBCC
		
																		 WHERE DATA_FROM = 'MOBILE_INS'
		
																					 AND WERKS = '4122'
		
																					 --AND EBCC_CODE = 'V011020190708142235003378' AND DATE_TIME BETWEEN TO_DATE('01-07-2019', 'DD-MM-RRRR') AND TO_DATE('01-07-2019', 'DD-MM-RRRR')
		
																					 AND TO_CHAR (DATE_TIME, 'DD-MM-RRRR') = '08-07-2019'
		
																					 ) HD_EBCC_VAL
		
																	 LEFT JOIN (SELECT *
		
																								FROM (SELECT EBCC_CODE,
		
																														 WERKS,
		
																														 ID_KUALITAS,
		
																														 ID_KUALITAS AS ID_K,
		
																														 QTY AS QTYS,
		
																														 QTY
		
																												FROM MOBILE_ESTATE.TR_EBCC_KUALITAS
		
																											 WHERE 1 = 1
		
																														 AND WERKS = '4122'
		
																														 AND TO_CHAR (DATE_TIME, 'DD-MM-RRRR') = '08-07-2019'
		
																														 --AND DATE_TIME BETWEEN TO_DATE('01-07-2019', 'DD-MM-RRRR')  AND TO_DATE (  '08-07-2019',  'DD-MM-RRRR')
		
																																	 ) PIVOT (SUM(QTY)
		
																																									FOR (
		
																																										 ID_K)
		
																																									IN ('1' AS JML_BM,
		
																																									'2' AS JML_BK,
		
																																									'3' AS JML_MS,
		
																																									'4' AS JML_OR,
		
																																									'6' AS JML_BB,
		
																																									'15' AS JML_JK,
		
																																									'16' AS JML_BA,
		
																																									'5' AS JML_BRD))
		
																																									)
		
																						 DETAIL_EBCC_VAL
		
																			ON DETAIL_EBCC_VAL.EBCC_CODE =
		
																						HD_EBCC_VAL.EBCC_CODE
		
																				 AND DETAIL_EBCC_VAL.WERKS =
		
																							 HD_EBCC_VAL.WERKS
		
																	 LEFT JOIN EBCC.T_PARAMETER_BUNCH PAR
		
																			ON PAR.BA_CODE = DETAIL_EBCC_VAL.WERKS
		
																				 AND PAR.ID_KUALITAS =
		
																							 DETAIL_EBCC_VAL.ID_KUALITAS
		
																				 AND PAR.KETERANGAN = 'BUNCH_HARVEST')
		
													 EBCC_VAL
		
												-- LEFT JOIN MOBILE_ESTATE.TR_IMAGE IMG_EBCC_VAL
		
												-- ON IMG_EBCC_VAL.TR_CODE = EBCC_VAL.VAL_EBCC_CODE
		
												-- AND IMG_EBCC_VAL.TR_TYPE = 'MOBILE_INS'
		
												LEFT JOIN
		
													 (SELECT EMPLOYEE_NIK,
		
																	 EMPLOYEE_FULLNAME,
		
																	 EMPLOYEE_JOINDATE AS START_VALID,
		
																	 CASE
		
																			WHEN EMPLOYEE_RESIGNDATE IS NULL
		
																			THEN
		
																				 TO_DATE ('9999', 'RRRR')
		
																			ELSE
		
																				 EMPLOYEE_RESIGNDATE
		
																	 END
		
																			AS END_VALID,
		
																	 EMPLOYEE_POSITION
		
															FROM TAP_DW.TM_EMPLOYEE_HRIS@PRODDW_LINK
		
														UNION
		
														SELECT NIK,
		
																	 EMPLOYEE_NAME,
		
																	 START_VALID,
		
																	 CASE
		
																			WHEN RES_DATE IS NULL AND END_VALID IS NULL
		
																			THEN
		
																				 TO_DATE ('9999', 'RRRR')
		
																			WHEN RES_DATE IS NOT NULL
		
																			THEN
		
																				 RES_DATE
		
																			ELSE
		
																				 END_VALID
		
																	 END
		
																			AS END_VALID,
		
																	 JOB_CODE
		
															FROM TAP_DW.TM_EMPLOYEE_SAP@DWH_LINK) EMP_VAL
		
												ON EMP_VAL.EMPLOYEE_NIK = EBCC_VAL.VAL_NIK_VALIDATOR
		
													 AND EBCC_VAL.VAL_DATE_TIME BETWEEN EMP_VAL.START_VALID
		
													 AND  EMP_VAL.END_VALID
		
												-- tambahan cek data block EBCC ke master sub block -> jk sub block, alihkan ke block induk
		
												LEFT JOIN TAP_DW.TM_SUB_BLOCK@DWH_LINK SUBBLOCK
		
													 ON SUBBLOCK.WERKS = EBCC_VAL.VAL_WERKS
		
															AND SUBBLOCK.SUB_BLOCK_CODE = EBCC_VAL.VAL_BLOCK_CODE
		
															AND EBCC_VAL.VAL_DATE_TIME BETWEEN SUBBLOCK.START_VALID AND  SUBBLOCK.END_VALID  
		
							 GROUP BY EBCC_VAL.VAL_EBCC_CODE,
		
												EBCC_VAL.VAL_WERKS,
		
												EBCC_VAL.VAL_NIK_VALIDATOR,
		
												EMP_VAL.EMPLOYEE_FULLNAME,
		
												EMP_VAL.EMPLOYEE_POSITION,
		
												EBCC_VAL.VAL_DATE_TIME,
		
												EBCC_VAL.VAL_AFD_CODE,
		
												EBCC_VAL.VAL_BLOCK_CODE,
		
												SUBBLOCK.BLOCK_NAME,
		
												EBCC_VAL.VAL_TPH_CODE,
		
												EBCC_VAL.VAL_DELIVERY_TICKET    
		
												--IMG_EBCC_VAL.IMAGE_NAME
		
											 -- Batas EBCC validation
		
							) EBCC_VALIDATION
		
					 LEFT JOIN
		
							(
		
							SELECT EBCC.ID_RENCANA AS EBCC_ID_RENCANA,
		
												EBCC.NO_BCC AS EBCC_NO_BCC,
		
												EBCC.WERKS AS EBCC_WERKS,
		
												EBCC.NIK_KERANI_BUAH AS EBCC_NIK_KERANI_BUAH,
		
												EMP_EBCC.EMPLOYEE_FULLNAME AS EBCC_NAMA_KERANI_BUAH,
		
												EMP_EBCC.EMPLOYEE_POSITION AS EBCC_JABATAN_KERANI_BUAH,
		
												EBCC.DATE_TIME AS EBCC_DATE_TIME,
		
												EBCC.AFD_CODE AS EBCC_AFD_CODE,
		
												SUBBLOCK.BLOCK_CODE AS EBCC_BLOCK_CODE,
		
												SUBBLOCK.BLOCK_NAME AS EBCC_BLOCK_NAME,
		
												EBCC.TPH_CODE AS EBCC_TPH_CODE,
		
												--EBCC.DELIVERY_TICKET as EBCC_DELIVERY_TICKET,
		
												NVL (SUM (EBCC.JML_BM), 0) AS EBCC_JML_BM,
		
												NVL (SUM (EBCC.JML_BK), 0) AS EBCC_JML_BK,
		
												NVL (SUM (EBCC.JML_MS), 0) AS EBCC_JML_MS,
		
												NVL (SUM (EBCC.JML_OR), 0) AS EBCC_JML_OR,
		
												NVL (SUM (EBCC.JML_BB), 0) AS EBCC_JML_BB,
		
												NVL (SUM (EBCC.JML_JK), 0) AS EBCC_JML_JK,
		
												NVL (SUM (EBCC.JML_BA), 0) AS EBCCJML_BA,
		
												NVL (SUM (EBCC.JML_BRD), 0) AS EBCC_JML_BRD,
		
												NVL (SUM (EBCC.JJG_PANEN), 0) AS EBCC_JJG_PANEN
		
									 --EBCC.IMAGE_NAME as EBCC_IMAGE_NAME
		
									 FROM (SELECT HRP.ID_RENCANA,
		
																HP.NO_BCC,
		
																TA.ID_BA AS WERKS,
		
																HRP.NIK_KERANI_BUAH,
		
																HRP.TANGGAL_RENCANA AS DATE_TIME,
		
																TA.ID_AFD AS AFD_CODE,
		
																TB.ID_BLOK AS BLOCK_CODE,
		
																HP.NO_TPH AS TPH_CODE,
		
																HP.KODE_DELIVERY_TICKET AS DELIVERY_TICKET,
		
																HPK.JML_BM,
		
																HPK.JML_BK,
		
																HPK.JML_MS,
		
																HPK.JML_OR,
		
																HPK.JML_BB,
		
																HPK.JML_JK,
		
																HPK.JML_BA,
		
																HPK.JML_BRD,
		
																CASE
		
																	 WHEN PAR.KETERANGAN = 'BUNCH_HARVEST'
		
																	 THEN
		
																			HPK.QTYS
		
																	 ELSE
		
																			0
		
																END
		
																	 AS JJG_PANEN
		
													 -- 'http://tap-motion.tap-agri.com/ebcc/array/uploads/'||HP.PICTURE_NAME AS IMAGE_NAME
		
													 FROM (SELECT ID_RENCANA,
		
																				TANGGAL_RENCANA,
		
																				NIK_KERANI_BUAH
		
																	 FROM EBCC.T_HEADER_RENCANA_PANEN
		
																	WHERE 1 = 1
		
																				AND TO_CHAR (TANGGAL_RENCANA, 'DD-MM-RRRR') = '08-07-2019'
		
																				--AND TANGGAL_RENCANA BETWEEN TO_DATE ('01-07-2019', 'DD-MM-RRRR') AND  TO_DATE ( '01-07-2019', 'DD-MM-RRRR')
		
																				)
		
																HRP
		
																LEFT JOIN EBCC.T_DETAIL_RENCANA_PANEN DRP
		
																	 ON HRP.ID_RENCANA = DRP.ID_RENCANA
		
																LEFT JOIN EBCC.T_HASIL_PANEN HP
		
																	 ON HP.ID_RENCANA = DRP.ID_RENCANA
		
																			AND HP.NO_REKAP_BCC = DRP.NO_REKAP_BCC
		
																LEFT JOIN (SELECT *
		
																						 FROM (SELECT ID_BCC,
		
																													ID_RENCANA,
		
																													ID_KUALITAS,
		
																													ID_KUALITAS AS IDK,
		
																													QTY AS QTYS,
		
																													QTY
		
																										 FROM MOBILE_ESTATE.T_HASILPANEN_KUALTAS@PRODDB_LINK) PIVOT (SUM(QTY)
		
																																																					FOR (
		
																																																						 IDK)
		
																																																					IN ('1' AS JML_BM,
		
																																																					'2' AS JML_BK,
		
																																																					'3' AS JML_MS,
		
																																																					'4' AS JML_OR,
		
																																																					'6' AS JML_BB,
		
																																																					'15' AS JML_JK,
		
																																																					'16' AS JML_BA,
		
																																																					'5' AS JML_BRD)))
		
																					HPK
		
																	 ON HP.NO_BCC = HPK.ID_BCC
		
																			AND HP.ID_RENCANA = HPK.ID_RENCANA
		
																LEFT JOIN EBCC.T_BLOK TB
		
																	 ON TB.ID_BA_AFD_BLOK = DRP.ID_BA_AFD_BLOK
		
																LEFT JOIN EBCC.T_AFDELING TA
		
																	 ON TA.ID_BA_AFD = TB.ID_BA_AFD
		
																LEFT JOIN EBCC.T_PARAMETER_BUNCH PAR
		
																	 ON PAR.BA_CODE =
		
																				 SUBSTR (DRP.ID_BA_AFD_BLOK, 1, 4)
		
																			AND PAR.ID_KUALITAS = HPK.ID_KUALITAS
		
																			AND PAR.KETERANGAN = 'BUNCH_HARVEST'
		
													WHERE 1 = 1 AND TA.ID_BA = 4122) EBCC
		
												LEFT JOIN (SELECT EMPLOYEE_NIK,
		
																					EMPLOYEE_FULLNAME,
		
																					EMPLOYEE_JOINDATE AS START_VALID,
		
																					CASE
		
																						 WHEN EMPLOYEE_RESIGNDATE IS NULL
		
																						 THEN
		
																								TO_DATE ('9999', 'RRRR')
		
																						 ELSE
		
																								EMPLOYEE_RESIGNDATE
		
																					END
		
																						 AS END_VALID,
		
																					EMPLOYEE_POSITION
		
																		 FROM TAP_DW.TM_EMPLOYEE_HRIS@PRODDW_LINK
		
																	 UNION
		
																	 SELECT NIK,
		
																					EMPLOYEE_NAME,
		
																					START_VALID,
		
																					CASE
		
																						 WHEN RES_DATE IS NULL
		
																									AND END_VALID IS NULL
		
																						 THEN
		
																								TO_DATE ('9999', 'RRRR')
		
																						 WHEN RES_DATE IS NOT NULL
		
																						 THEN
		
																								RES_DATE
		
																						 ELSE
		
																								END_VALID
		
																					END
		
																						 AS END_VALID,
		
																					JOB_CODE
		
																		 FROM TAP_DW.TM_EMPLOYEE_SAP@DWH_LINK) EMP_EBCC
		
													 ON EMP_EBCC.EMPLOYEE_NIK = EBCC.NIK_KERANI_BUAH
		
															AND EBCC.DATE_TIME BETWEEN EMP_EBCC.START_VALID
		
																										 AND  EMP_EBCC.END_VALID
		
												-- tambahan cek data block EBCC ke master sub block -> jk sub block, alihkan ke block induk
		
												LEFT JOIN TAP_DW.TM_SUB_BLOCK@DWH_LINK SUBBLOCK
		
													 ON SUBBLOCK.WERKS = EBCC.WERKS
		
															AND SUBBLOCK.SUB_BLOCK_CODE = EBCC.BLOCK_CODE
		
															AND EBCC.DATE_TIME BETWEEN SUBBLOCK.START_VALID AND  SUBBLOCK.END_VALID
		
							 GROUP BY EBCC.ID_RENCANA,
		
												EBCC.NO_BCC,
		
												EBCC.WERKS,
		
												EBCC.NIK_KERANI_BUAH,
		
												EMP_EBCC.EMPLOYEE_FULLNAME,
		
												EMP_EBCC.EMPLOYEE_POSITION,
		
												EBCC.DATE_TIME,
		
												EBCC.AFD_CODE,
		
												SUBBLOCK.BLOCK_CODE,
		
												SUBBLOCK.BLOCK_NAME,
		
												EBCC.TPH_CODE                       --EBCC.DELIVERY_TICKET
		
																																 --EBCC.IMAGE_NAME
		
						) EBCC
		
					 ON     EBCC.EBCC_WERKS = EBCC_VALIDATION.VAL_WERKS
		
							AND EBCC.EBCC_AFD_CODE = EBCC_VALIDATION.VAL_AFD_CODE
		
							AND EBCC.EBCC_BLOCK_CODE = EBCC_VALIDATION.VAL_BLOCK_CODE
		
							AND EBCC.EBCC_TPH_CODE = EBCC_VALIDATION.VAL_TPH_CODE 
							--AND EBCC.EBCC_DELIVERY_TICKET = EBCC_VALIDATION.VAL_DELIVERY_TICKET AND EBCC.EBCC_DATE_TIME = EBCC_VALIDATION.VAL_DATE_TIME AND EBCC.EBCC_JJG_PANEN = EBCC_VALIDATION.VAL_JJG_PANE";


		$_SESSION["sql_t_BCC"] 		= $sql_t_BCC;
		//echo $sql_t_BCC; die();
		header("Location:LaporanBCCList.php");
	}
} else {
	$_SESSION[err] = "Please choose the options";
	header("Location:LaporanBCCFil.php");
}
