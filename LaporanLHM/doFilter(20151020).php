<?php
session_start();

if(isset($_SESSION["Afdeling"]) || isset($_SESSION["NIKMandor"]) || isset($_SESSION["NIKPemanen"]) || isset($_POST["date1"])){
	$valueAfdeling 		= $_SESSION["Afdeling"];
	$NIK_Mandor 		= $_SESSION["NIKMandor"];
	$NIK_Pemanen 		= $_SESSION["NIKPemanen"];
	$date1 = date("Y-m-d", strtotime($_POST["date1"]));
	$date2 = date("Y-m-d", strtotime($_POST["date2"]));
	$ID_BA 		= $_SESSION['subID_BA_Afd'];
	$ID_CC 		= $_SESSION['subID_CC'];
	$valueBlok 	= $_POST["blok_post"];
	$NIK_Pemanen_rekap = $_POST['nik_pemanen_rekap_post'];
	$NIK_Mandor_rekap = $_POST['nik_mandor_rekap_post'];
	
	$rbtn_type = $_POST['rbtn_type'];
	$rbtn_filter = $_POST['rbtn_filter'];
	if($rbtn_type == "Detail"){
		$rbtn_filter = "";
	}
	
	if($date2 == "1970-01-01"){
		$dt=date_create($date1 . ' first day of last month');
		$date_month = $dt->format('m.Y');
	}
	else{
		$dt=date_create($date2 . ' first day of last month');
		$date_month = $dt->format('m.Y');
	}
	
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
		header("Location:laporanLHMFilter.php");
	}
	else{
		if($rbtn_type == "Detail"){
			$sql_Laporan_LHM = "	
				SELECT tc.id_cc,
					 tc.comp_name,
					 tba.id_ba,
					 tba.nama_ba,
					 thrp.tanggal_rencana tgl_panen,
					 ta.id_afd,
					 thrp.nik_mandor,
					 f_get_empname (thrp.nik_mandor) nama_mandor,
					 thrp.nik_pemanen,
					 f_get_empname (thrp.nik_pemanen) nama_pemanen,
					 thrp.nik_kerani_buah,
					 f_get_empname (thrp.nik_kerani_buah) nama_kerani_buah,
					 tb.id_blok,
					 tb.blok_name,
					 tdrp.luasan_panen,
					 thp.no_bcc,
					 thp.no_tph,
					 tdrp.no_rekap_bcc,
					 NVL (F_GET_HASIL_PANEN_TBS2 (thp.no_rekap_bcc, thp.no_bcc), 0) AS TBS,
					 NVL (F_GET_HASIL_PANEN_BRD (thp.no_rekap_bcc, thp.no_bcc), 0) AS BRD,
					 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 1), 0) AS BM,
					 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 2), 0) AS BK,
					 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 3), 0) AS MS,
					 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 4), 0) AS OVR,
					 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 6), 0) AS BB,
					 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 15), 0) AS JK,
					 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 7), 0) AS TP,
					 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 9), 0) AS MH,
					 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 11), 0) AS BT,
					 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 12), 0) AS BL,
					 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 13), 0) AS PB,
					 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 10), 0) AS AB,
					 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 14), 0) AS SF,
					 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 8), 0) AS BS
				FROM t_header_rencana_panen thrp
					 INNER JOIN t_detail_rencana_panen tdrp
						ON thrp.id_rencana = tdrp.id_rencana
					 INNER JOIN t_hasil_panen thp
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
			   WHERE     tc.id_cc = '$ID_CC'
					 AND tba.id_ba = '$ID_BA'
					 AND ta.id_afd = NVL (DECODE ('$valueAfdeling', 'ALL', NULL, '$valueAfdeling'), ta.id_afd)
					 AND thrp.nik_mandor =
						   NVL (DECODE ('$NIK_Mandor', 'ALL', NULL, '$NIK_Mandor'), thrp.nik_mandor)
					 AND thrp.nik_pemanen =
						   NVL (DECODE ('$NIK_Pemanen', 'ALL', NULL, '$NIK_Pemanen'), thrp.nik_pemanen)
					 AND TO_CHAR (thrp.tanggal_rencana, 'yyyy-mm-dd') BETWEEN '$date1'
																		  AND  NVL ('$date2', '$date1')
			ORDER BY tgl_panen,
					 thrp.nik_mandor,
					 nama_pemanen,
					 tdrp.no_rekap_bcc,
					 thrp.id_rencana,
					 thp.no_bcc";
		 }else{
			if($rbtn_filter == "Pemanen"){
				$sql_Laporan_LHM = "SELECT tgl_panen,
									 nama_pemanen,
									 nik_pemanen,
									 SUM (luasan_panen) AS total_HA_panen,
									 SUM (TBS) AS TBS,
									 SUM (BRD) AS BRD,
									 SUM (BM) AS BM,
									 SUM (BK) AS BK,
									 SUM (MS) AS MS,
									 SUM (OVR) AS OVR,
									 SUM (BB) AS BB,
									 SUM (JK) AS JK,
									 SUM (TP) AS TP,
									 SUM (MH) AS MH,
									 SUM (BT) AS BT,
									 SUM (BL) AS BL,
									 SUM (PB) AS PB,
									 SUM (AB) AS AB,
									 SUM (SF) AS SF,
									 SUM (BS) AS BS
								FROM (  SELECT tgl_panen,
											   nama_pemanen,
											   nik_pemanen,
											   id_blok,
											   luasan_panen AS luasan_panen,
											   SUM (TBS) AS TBS,
											   SUM (BRD) AS BRD,
											   SUM (BM) AS BM,
											   SUM (BK) AS BK,
											   SUM (MS) AS MS,
											   SUM (OVR) AS OVR,
											   SUM (BB) AS BB,
											   SUM (JK) AS JK,
											   SUM (TP) AS TP,
											   SUM (MH) AS MH,
											   SUM (BT) AS BT,
											   SUM (BL) AS BL,
											   SUM (PB) AS PB,
											   SUM (AB) AS AB,
											   SUM (SF) AS SF,
											   SUM (BS) AS BS
										  FROM (  SELECT tc.id_cc,
														 tc.comp_name,
														 tba.id_ba,
														 tba.nama_ba,
														 thrp.tanggal_rencana tgl_panen,
														 ta.id_afd,
														 thrp.nik_mandor,
														 f_get_empname (thrp.nik_mandor) nama_mandor,
														 thrp.nik_pemanen,
														 f_get_empname (thrp.nik_pemanen) nama_pemanen,
														 thrp.nik_kerani_buah,
														 f_get_empname (thrp.nik_kerani_buah) nama_kerani_buah,
														 tb.id_blok,
														 tb.blok_name,
														 tdrp.luasan_panen,
														 thp.no_bcc,
														 thp.no_TPH,
														 tdrp.no_rekap_bcc,
														 NVL (F_GET_HASIL_PANEN_TBS2 (thp.no_rekap_bcc, thp.no_bcc), 0) AS TBS,
														 NVL (F_GET_HASIL_PANEN_BRD (thp.no_rekap_bcc, thp.no_bcc), 0) AS BRD,
														 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 1), 0) AS BM,
														 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 2), 0) AS BK,
														 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 3), 0) AS MS,
														 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 4), 0) AS OVR,
														 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 6), 0) AS BB,
														 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 15), 0) AS JK,
														 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 7), 0) AS TP,
														 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 9), 0) AS MH,
														 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 11), 0) AS BT,
														 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 12), 0) AS BL,
														 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 13), 0) AS PB,
														 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 10), 0) AS AB,
														 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 14), 0) AS SF,
														 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 8), 0) AS BS
													FROM t_header_rencana_panen thrp
														 INNER JOIN t_detail_rencana_panen tdrp
															ON thrp.id_rencana = tdrp.id_rencana
														 INNER JOIN t_hasil_panen thp
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
												   WHERE tc.id_cc = '$ID_CC' AND tba.id_ba = '$ID_BA'
														 AND ta.id_afd =
															   NVL (DECODE ('$valueAfdeling', 'ALL', NULL, '$valueAfdeling'), ta.id_afd)
														 AND thrp.nik_mandor =
															   NVL (DECODE ('$NIK_Mandor', 'ALL', NULL, '$NIK_Mandor'),
																	thrp.nik_mandor)
														 AND thrp.nik_pemanen =
															   NVL (DECODE ('$NIK_Pemanen', 'ALL', NULL, '$NIK_Pemanen'),
																	thrp.nik_pemanen)
														 AND TO_CHAR (thrp.tanggal_rencana, 'yyyy-mm-dd') BETWEEN '$date1'
																											  AND  NVL ('$date2','$date1')
												ORDER BY thrp.nik_mandor,
														 tgl_panen,
														 thrp.nik_pemanen,
														 thp.no_bcc) DETAIL
									  GROUP BY tgl_panen,
											   nik_pemanen,
											   nama_pemanen,
											   id_blok,
											   luasan_panen
									  ORDER BY tgl_panen, nik_pemanen) SUM_REKAP
							GROUP BY tgl_panen, nik_pemanen, nama_pemanen
							ORDER BY tgl_panen, nik_pemanen";
			}else{
				$sql_Laporan_LHM = "
					SELECT tgl_panen,
					 id_blok,
					 blok_name,
					 ha_blok,
					 SUM (luasan_panen) AS luasan_panen,
					 SUM (TBS) AS TBS,
					 SUM (BRD) AS BRD,
					 SUM (BM) AS BM,
					 SUM (BK) AS BK,
					 SUM (MS) AS MS,
					 SUM (OVR) AS OVR,
					 SUM (BB) AS BB,
					 SUM (JK) AS JK,
					 SUM (TP) AS TP,
					 SUM (MH) AS MH,
					 SUM (BT) AS BT,
					 SUM (BL) AS BL,
					 SUM (PB) AS PB,
					 SUM (AB) AS AB,
					 SUM (SF) AS SF,
					 SUM (BS) AS BS
				FROM (  SELECT tgl_panen,
							   id_cc,
							   comp_name,
							   id_ba,
							   nama_ba,
							   id_blok,
							   blok_name,
							   ha_blok,
							   luasan_panen,
							   no_rekap_bcc,
							   SUM (TBS) AS TBS,
							   SUM (BRD) AS BRD,
							   SUM (BM) AS BM,
							   SUM (BK) AS BK,
							   SUM (MS) AS MS,
							   SUM (OVR) AS OVR,
							   SUM (BB) AS BB,
							   SUM (JK) AS JK,
							   SUM (TP) AS TP,
							   SUM (MH) AS MH,
							   SUM (BT) AS BT,
							   SUM (BL) AS BL,
							   SUM (PB) AS PB,
							   SUM (AB) AS AB,
							   SUM (SF) AS SF,
							   SUM (BS) AS BS
						  FROM (  SELECT tc.id_cc,
										 tc.comp_name,
										 tba.id_ba,
										 tba.nama_ba,
										 thrp.tanggal_rencana tgl_panen,
										 ta.id_afd,
										 thrp.nik_mandor,
										 f_get_empname (thrp.nik_mandor) nama_mandor,
										 thrp.nik_pemanen,
										 f_get_empname (thrp.nik_pemanen) nama_pemanen,
										 thrp.nik_kerani_buah,
										 f_get_empname (thrp.nik_kerani_buah) nama_kerani_buah,
										 tb.id_blok,
										 tb.blok_name,
										 tdrp.luasan_panen,
										 zb.planted AS ha_blok,
										 tdrp.no_rekap_bcc,
										 NVL (F_GET_HASIL_PANEN_TBS2 (thp.no_rekap_bcc, thp.no_bcc), 0) AS TBS,
										 NVL (F_GET_HASIL_PANEN_BRD (thp.no_rekap_bcc, thp.no_bcc), 0) AS BRD,
										 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 1), 0) AS BM,
										 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 2), 0) AS BK,
										 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 3), 0) AS MS,
										 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 4), 0) AS OVR,
										 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 6), 0) AS BB,
										 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 15), 0) AS JK,
										 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 7), 0) AS TP,
										 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 9), 0) AS MH,
										 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 11), 0) AS BT,
										 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 12), 0) AS BL,
										 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 13), 0) AS PB,
										 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 10), 0) AS AB,
										 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 14), 0) AS SF,
										 NVL (F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 8), 0) AS BS
									FROM t_header_rencana_panen thrp
										 INNER JOIN t_detail_rencana_panen tdrp
											ON thrp.id_rencana = tdrp.id_rencana
										 INNER JOIN t_hasil_panen thp
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
										 INNER JOIN (
											select * from STAGING.ZEST_BLOCKH@proddb_link zb
											where  zb.SPMON = (select max(zb1.SPMON) from
											STAGING.ZEST_BLOCKH@proddb_link zb1 where
											zb.comp_code = zb1.comp_code 
											and zb.est_code = zb1.est_code
											and zb.afd_code = zb1.afd_code
											and zb.block_code = zb1.block_code                                
											)) ZB ON (CONCAT (CONCAT (ZB.comp_code, ZB.est_code),
													   ZB.afd_code) = tb.id_ba_afd
											   AND ZB.block_code = tb.id_blok)
									WHERE tc.id_cc = '$ID_CC' 
										AND tba.id_ba = '$ID_BA'
										AND ta.id_afd = nvl(decode('$valueAfdeling','ALL',null,'$valueAfdeling'),ta.id_afd)
										AND thrp.nik_mandor = nvl(decode('$NIK_Mandor_rekap','ALL',null,'$NIK_Mandor_rekap'),thrp.nik_mandor)
										AND thrp.nik_pemanen = nvl(decode('$NIK_Pemanen_rekap','ALL',null,'$NIK_Pemanen_rekap'),thrp.nik_pemanen)
										AND to_char(thrp.tanggal_rencana,'yyyy-mm-dd') between '$date1' and nvl ('$date2', '$date1')
								GROUP BY tc.id_cc,
										 tc.comp_name,
										 tba.id_ba,
										 tba.nama_ba,
										 thrp.tanggal_rencana,
										 ta.id_afd,
										 thrp.nik_mandor,
										 f_get_empname (thrp.nik_mandor),
										 thrp.nik_pemanen,
										 f_get_empname (thrp.nik_pemanen),
										 thrp.nik_kerani_buah,
										 f_get_empname (thrp.nik_kerani_buah),
										 tb.id_blok,
										 tb.blok_name,
										 tdrp.luasan_panen,
										 zb.planted,
										 tdrp.no_rekap_bcc,
										 F_GET_HASIL_PANEN_TBS2 (thp.no_rekap_bcc, thp.no_bcc),
										 F_GET_HASIL_PANEN_BRD (thp.no_rekap_bcc, thp.no_bcc),
										 F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 1),
										 F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 2),
										 F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 3),
										 F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 4),
										 F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 6),
										 F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 15),
										 F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 7),
										 F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 9),
										 F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 11),
										 F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 12),
										 F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 13),
										 F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 10),
										 F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 14),
										 F_GET_HASIL_PANEN_NUMBER (thp.no_rekap_bcc, thp.no_bcc, 8)
								ORDER BY blok_name, no_rekap_bcc)
					  GROUP BY tgl_panen,
							   id_cc,
							   comp_name,
							   id_ba,
							   nama_ba,
							   id_blok,
							   blok_name,
							   ha_blok,
							   luasan_panen,
							   no_rekap_bcc
					  ORDER BY blok_name)
			GROUP BY tgl_panen,
					 id_blok,
					 blok_name,
					 ha_blok
			ORDER BY id_blok";
			}
		}
		$_SESSION["sql_Laporan_LHM"] = $sql_Laporan_LHM;
		$_SESSION["rbtn_type"] = $rbtn_type;
		$_SESSION["rbtn_filter"] = $rbtn_filter;
		//echo $sql_Laporan_LHM;die();
		header("Location:laporanLHM.php");
	}
}
else{
$_SESSION[err] = "Please choose the options";
header("Location:laporanLHMFilter.php");
}
?>