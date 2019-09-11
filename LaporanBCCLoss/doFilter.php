<?php
session_start();

if(isset($_POST["Afdeling"]) || isset($_POST["date1"]) || isset($_POST["date2"]) || isset($_POST["date3"]) || isset($_POST["selectfinder"]) || isset($_POST["typefinder"])){
	$valueAfdeling 		= $_POST["Afdeling"];
	$date1 = date("Y-m-d", strtotime($_POST["date1"]));
	$date2 = date("Y-m-d", strtotime($_POST["date2"]));
	$m_date = date("m", strtotime($_POST["date1"]));
	$y_date = date("Y", strtotime($_POST["date1"]));
	
	$selectfinder 	= $_POST["selectfinder"];
	$typefinder 	= $_POST["typefinder"];
	$ID_BA 		= $_SESSION['subID_BA_Afd'];
	$ID_CC 		= $_SESSION['subID_CC'];	
	$lap 	= $_POST["rbtn_filter"];
	$jenis_lap 	= $_POST["rbtn_filter_type"];
	//echo $lap . " " . $jenis_lap;die();
	//include("Filter/query2.php");
	
	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();
	
	if($date2 == "1970-01-01")
	{
		$date2='';
		//echo $date2;
	}
	
	if($date1 == "1970-01-01"){
		//echo "salah";
		$_SESSION[err] 		= "please choose date";	
		header("Location:LaporanBCCLoss.php");
	}
	else{
		if($lap == 'loss'){
			if($jenis_lap == 'rekap'){
				$sql_t_BCCLoss = "SELECT to_char(TANGGAL_RENCANA, 'dd-MON-YYYY') TANGGAL_RENCANA,
								 to_char(TGL_DOC, 'dd-MON-YYYY') TGL_DOC,
								 NO_DOC,
								 to_char(CREATED_DATE, 'dd-MON-YYYY HH24:MI:SS') CREATED_DATE,
								 ID_BA,
								 ID_AFD,
								 COUNT (NO_BCC) JML_NO_BCC,
								 SUM (TBS) JML_JJG,
								 SUM (BRD) JML_BRD,
								 SUM (ESTIMASI_BERAT) ESTIMASI_BERAT
							FROM (SELECT THRP.TANGGAL_RENCANA,
									 TGL_DOC,
									 NO_DOC,
									 CREATED_DATE,
									 TBA.ID_BA,
									 TA.ID_AFD,
									 F_GET_BJR (TB.ID_BLOK,
												THRP.TANGGAL_RENCANA,
												TDRP.ID_BA_AFD_BLOK)
										AS BJR,
									 -- F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'TBS2') TBS, -- remarked by NBU 13.10.2015
									 NVL (F_GET_HASIL_PANEN_BUNCH (TBA.ID_BA, thp.no_rekap_bcc, thp.no_bcc, 'BUNCH_SEND'), 0) as TBS, 
									 F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'BRD') BRD,
									 (F_GET_BJR (TB.ID_BLOK,
												 THRP.TANGGAL_RENCANA,
												 TDRP.ID_BA_AFD_BLOK)
									  * NVL (F_GET_HASIL_PANEN_BUNCH (TBA.ID_BA, thp.no_rekap_bcc, thp.no_bcc, 'BUNCH_SEND'), 0))
										ESTIMASI_BERAT,
									 TBL.NO_BCC
								FROM T_BCC_LOST TBL
									 LEFT JOIN T_EMPLOYEE TE
										ON TE.NIK = TBL.CREATED_BY
									 LEFT JOIN T_HASIL_PANEN THP
										ON THP.NO_BCC = TBL.NO_BCC
									 LEFT JOIN T_DETAIL_RENCANA_PANEN TDRP
										ON TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC
										   AND THP.ID_RENCANA = TDRP.ID_RENCANA
									 LEFT JOIN T_HEADER_RENCANA_PANEN THRP
										ON THRP.ID_RENCANA = TDRP.ID_RENCANA
									 LEFT JOIN T_BLOK TB
										ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
									 LEFT JOIN T_AFDELING TA
										ON TB.ID_BA_AFD = TA.ID_BA_AFD
									 LEFT JOIN T_BUSSINESSAREA TBA
										ON TA.ID_BA = TBA.ID_BA
							   WHERE tba.id_cc = '$ID_CC' AND tba.id_ba = '$ID_BA'
									   and ta.id_afd = nvl (decode ('$valueAfdeling', 'ALL', null, '$valueAfdeling'), ta.id_afd)
									   AND THRP.TANGGAL_RENCANA BETWEEN TO_DATE ('$date1', 'YYYY-MM-DD') AND TO_DATE (NVL ('$date2', '$date1'), 'YYYY-MM-DD'))
								GROUP BY TANGGAL_RENCANA,
								 TGL_DOC,
								 NO_DOC,
								 CREATED_DATE,
								 ID_BA,
								 ID_AFD
								ORDER BY TANGGAL_RENCANA,
								 TGL_DOC,CREATED_DATE,
								 ID_BA,
								 ID_AFD
				";
			}else{ //detail
				$sql_t_BCCLoss = "SELECT to_char(THRP.TANGGAL_RENCANA, 'dd-MON-YYYY') TANGGAL_RENCANA,
									 to_char(TGL_DOC, 'dd-MON-YYYY') TGL_DOC,
									 NO_DOC,
									 to_char(CREATED_DATE, 'dd-MON-YYYY HH24:MI:SS') CREATED_DATE,
									 TBA.ID_BA,
									 TA.ID_AFD,
									 F_GET_BJR (TB.ID_BLOK,
												THRP.TANGGAL_RENCANA,
												TDRP.ID_BA_AFD_BLOK)
										AS BJR,
									 -- F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'TBS2') TBS, -- remarked by NBU 13.10.2015
									 NVL (F_GET_HASIL_PANEN_BUNCH (TBA.ID_BA, thp.no_rekap_bcc, thp.no_bcc, 'BUNCH_SEND'), 0) as TBS, 
									 F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'BRD') BRD,
									 (F_GET_BJR (TB.ID_BLOK,
												 THRP.TANGGAL_RENCANA,
												 TDRP.ID_BA_AFD_BLOK)
									  * NVL (F_GET_HASIL_PANEN_BUNCH (TBA.ID_BA, thp.no_rekap_bcc, thp.no_bcc, 'BUNCH_SEND'), 0))
										ESTIMASI_BERAT,
									 TB.ID_BLOK, TB.BLOK_NAME,
									 TBL.NO_BCC, TBL.REMARK
								FROM T_BCC_LOST TBL
									 LEFT JOIN T_EMPLOYEE TE
										ON TE.NIK = TBL.CREATED_BY
									 LEFT JOIN T_HASIL_PANEN THP
										ON THP.NO_BCC = TBL.NO_BCC
									 LEFT JOIN T_DETAIL_RENCANA_PANEN TDRP
										ON TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC
										   AND THP.ID_RENCANA = TDRP.ID_RENCANA
									 LEFT JOIN T_HEADER_RENCANA_PANEN THRP
										ON THRP.ID_RENCANA = TDRP.ID_RENCANA
									 LEFT JOIN T_BLOK TB
										ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
									 LEFT JOIN T_AFDELING TA
										ON TB.ID_BA_AFD = TA.ID_BA_AFD
									 LEFT JOIN T_BUSSINESSAREA TBA
										ON TA.ID_BA = TBA.ID_BA
							   WHERE tba.id_cc = '$ID_CC' AND tba.id_ba = '$ID_BA'
									   and ta.id_afd = nvl (decode ('$valueAfdeling', 'ALL', null, '$valueAfdeling'), ta.id_afd)
									   AND tb.id_blok = NVL (DECODE ('', 'ALL', NULL, ''), tb.id_blok)
									   AND THRP.TANGGAL_RENCANA BETWEEN TO_DATE ('$date1', 'YYYY-MM-DD') AND TO_DATE (NVL ('$date2', '$date1'), 'YYYY-MM-DD')
								ORDER BY TANGGAL_RENCANA,
								 TGL_DOC,CREATED_DATE,
								 ID_BA,
								 ID_AFD,
								 TB.ID_BLOK
				";
			}
		} else if($lap == 'wo'){ //wo
			if($jenis_lap == 'rekap'){
				$sql_t_BCCLoss = "SELECT TO_CHAR (TANGGAL_RENCANA, 'dd-MON-YYYY') TANGGAL_RENCANA,
								 TO_CHAR (PERIODE_WO, 'MON-YYYY') PERIODE_WO,
								 ID_BA,
								 ID_AFD,
								 COUNT (NO_BCC) JML_NO_BCC,
								 SUM (TBS) JML_JJG,
								 SUM (BRD) JML_BRD,
								 SUM (ESTIMASI_BERAT) ESTIMASI_BERAT
							FROM (    
							SELECT THRP.TANGGAL_RENCANA,
										 PERIODE_WO,
										 TBA.ID_BA,
										 TA.ID_AFD,
										 F_GET_BJR (TB.ID_BLOK,
													THRP.TANGGAL_RENCANA,
													TDRP.ID_BA_AFD_BLOK)
											AS BJR,
										 -- F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'TBS2') TBS, -- remarked by NBU 13.10.2015
										 NVL (F_GET_HASIL_PANEN_BUNCH (TBA.ID_BA, thp.no_rekap_bcc, thp.no_bcc, 'BUNCH_SEND'), 0) TBS, 
										 F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'BRD') BRD,
										 (F_GET_BJR (TB.ID_BLOK,
													 THRP.TANGGAL_RENCANA,
													 TDRP.ID_BA_AFD_BLOK)
										  * NVL (F_GET_HASIL_PANEN_BUNCH (TBA.ID_BA, thp.no_rekap_bcc, thp.no_bcc, 'BUNCH_SEND'), 0))
											ESTIMASI_BERAT,
										 TB.ID_BLOK,
										 TBW.NO_BCC
									FROM T_BCC_WO TBW
										 LEFT JOIN T_HASIL_PANEN THP
											ON THP.ID_RENCANA = TBW.ID_RENCANA
											AND THP.NO_BCC = TBW.NO_BCC
										 LEFT JOIN T_DETAIL_RENCANA_PANEN TDRP
											ON TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC
											   AND THP.ID_RENCANA = TDRP.ID_RENCANA
										 LEFT JOIN T_HEADER_RENCANA_PANEN THRP
											ON THRP.ID_RENCANA = TDRP.ID_RENCANA
										 LEFT JOIN T_BLOK TB
											ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
										 LEFT JOIN T_AFDELING TA
											ON TB.ID_BA_AFD = TA.ID_BA_AFD
										 LEFT JOIN T_BUSSINESSAREA TBA
											ON TA.ID_BA = TBA.ID_BA
								   WHERE tba.id_cc = '$ID_CC' AND tba.id_ba = '$ID_BA'
										 and ta.id_afd = nvl (decode ('$valueAfdeling', 'ALL', null, '$valueAfdeling'), ta.id_afd)
										 AND TO_CHAR(PERIODE_WO, 'MM') = '$m_date'
										 AND TO_CHAR(PERIODE_WO, 'YYYY') = '$y_date'
						)
						GROUP BY TANGGAL_RENCANA,
								 PERIODE_WO,
								 ID_BA,
								 ID_AFD
						ORDER BY TANGGAL_RENCANA,
								 PERIODE_WO,
								 ID_BA,
								 ID_AFD
				";
			}else{
				$sql_t_BCCLoss = "SELECT TO_CHAR (TANGGAL_RENCANA, 'dd-MON-YYYY') TANGGAL_RENCANA,
								 TO_CHAR (PERIODE_WO, 'MON-YYYY') PERIODE_WO,
									 TBA.ID_BA,
									 TA.ID_AFD,
									 TB.ID_BLOK,
									 TB.BLOK_NAME,
									 F_GET_BJR (TB.ID_BLOK,
												THRP.TANGGAL_RENCANA,
												TDRP.ID_BA_AFD_BLOK)
										AS BJR,
									 TBW.NO_BCC,
									 -- F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'TBS2') TBS, -- remarked by NBU 13.10.2015
									 NVL (F_GET_HASIL_PANEN_BUNCH (TBA.ID_BA, thp.no_rekap_bcc, thp.no_bcc, 'BUNCH_SEND'), 0) TBS, 
									 F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'BRD') BRD,
									 (F_GET_BJR (TB.ID_BLOK,
												 THRP.TANGGAL_RENCANA,
												 TDRP.ID_BA_AFD_BLOK)
									  * NVL (F_GET_HASIL_PANEN_BUNCH (TBA.ID_BA, thp.no_rekap_bcc, thp.no_bcc, 'BUNCH_SEND'), 0))
										ESTIMASI_BERAT
								FROM T_BCC_WO TBW
									 LEFT JOIN T_HASIL_PANEN THP
										ON THP.ID_RENCANA = TBW.ID_RENCANA
										AND THP.NO_BCC = TBW.NO_BCC
									 LEFT JOIN T_DETAIL_RENCANA_PANEN TDRP
										ON TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC
										   AND THP.ID_RENCANA = TDRP.ID_RENCANA
									 LEFT JOIN T_HEADER_RENCANA_PANEN THRP
										ON THRP.ID_RENCANA = TDRP.ID_RENCANA
									 LEFT JOIN T_BLOK TB
										ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
									 LEFT JOIN T_AFDELING TA
										ON TB.ID_BA_AFD = TA.ID_BA_AFD
									 LEFT JOIN T_BUSSINESSAREA TBA
										ON TA.ID_BA = TBA.ID_BA
							   WHERE tba.id_cc = '$ID_CC' AND tba.id_ba = '$ID_BA'
										and ta.id_afd = nvl (decode ('$valueAfdeling', 'ALL', null, '$valueAfdeling'), ta.id_afd)
									 AND tb.id_blok =
										   NVL (DECODE ('', 'ALL', NULL, ''), tb.id_blok)
									 AND TO_CHAR(PERIODE_WO, 'MM') = '$m_date'
									 AND TO_CHAR(PERIODE_WO, 'YYYY') = '$y_date'
						   ORDER BY TANGGAL_RENCANA,
							 PERIODE_WO,
							 ID_BA,
							 ID_AFD,TB.ID_BLOK
				";
			}
		} else {
			//Added by Ardo 19-08-2016 : Synchronize BCC - LaporanBCCLoss
			//delete
			$sql_t_BCCLoss = "	
				 select tc.id_cc,
				 tc.comp_name comp_name,
				 tba.id_ba,
				 tba.nama_ba nama_ba,
				 ta.id_afd,
				 thrp.tanggal_rencana tgl_panen,
				 thrp.nik_mandor,
				 f_get_empname (thrp.nik_mandor) nama_mandor,
				 thrp.nik_pemanen,
				 f_get_idafd_nik(thrp.nik_pemanen) afd_pemanen,
				 f_get_empname (thrp.nik_pemanen) nama_pemanen,
				 thp.no_bcc,
				 thp.no_tph,
				 thp.kode_delivery_ticket,
				 tb.id_blok,
				 tdrp.luasan_panen,
				 null JML_JJG,
				 null JML_BRD,
					 --NVL (F_GET_HASIL_PANEN_BUNCH (tba.id_ba, thp.no_rekap_bcc, thp.no_bcc, 'BUNCH_HARVEST'), 0) as JML_JJG,
					 --NVL(F_GET_HASIL_PANEN_BRDX  (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc),0)  as JML_BRD, 
				 tb.blok_name,
				 tdrp.no_rekap_bcc,
				 thrp.id_rencana,
				 thp.nomor_ba,
				 thp.tanggal_ba,
				 thp.alasan,
				 thp.tanggal_delete
			   FROM del_t_header_rencana_panen thrp
				 INNER JOIN del_t_detail_rencana_panen tdrp
					ON thrp.id_rencana = tdrp.id_rencana
				 INNER JOIN del_t_hasil_panen thp
					ON tdrp.id_rencana = thp.id_rencana
					AND tdrp.no_rekap_bcc = thp.no_rekap_bcc
				 INNER JOIN t_blok tb
					ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
				 INNER JOIN t_afdeling ta
					ON tb.id_ba_afd = ta.id_ba_afd
				 INNER JOIN t_bussinessarea tba
					ON tba.id_ba = ta.id_ba
				 INNER JOIN t_companycode tc
					ON tba.id_cc = tc.id_cc
		   where     tc.id_cc = '$ID_CC'
				 and tba.id_ba = '$ID_BA'
				 and ta.id_afd = nvl(decode('$valueAfdeling', 'ALL', null, '$valueAfdeling'), ta.id_afd)
				 and thrp.nik_mandor = nvl(decode('ALL', 'ALL', null, 'ALL'), thrp.nik_mandor)
				 and TO_CHAR (thrp.tanggal_rencana, 'YYYY-MM-DD') BETWEEN '$date1' and nvl ('$date2', '$date1')
			order by tgl_panen,
			 thrp.nik_mandor,
			 id_afd,
			 nama_pemanen,
			 nik_pemanen,
			 id_blok,
			 NO_BCC
			 ";
		}
		$_SESSION["sql_t_BCCLoss"] 		= $sql_t_BCCLoss;	
		$_SESSION["lap"] = $lap;
		$_SESSION["jenis_lap"] = $jenis_lap;
		//echo $sql_t_BCCLoss; die();
		header("Location:LaporanBCCLossList.php");
	}
}
else{
$_SESSION[err] = "Please choose the options";
header("Location:LaporanBCCLoss.php");
}
?>