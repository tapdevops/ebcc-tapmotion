<?php
session_start();

if(isset($_POST["valueAfd_select"]) || isset($_POST["NIKMandor_select"]) || isset($_POST["sdate1"]) || isset($_POST["sdate2"])){
	
	$valueAfdeling 		= $_POST["valueAfd_select"];
	$NIK_Mandor 		= $_POST["NIKMandor_select"];
	$date1 	= $_POST["sdate1"];
	$date2 	= $_POST["sdate2"];
	$ID_BA 		= $_SESSION['subID_BA_Afd'];
	$ID_CC 		= $_SESSION['subID_CC'];
	
	//echo "afdeling ". $valueAfdeling." mandor ". $NIK_Mandor. " id_ba ".$ID_BA . " id_cc ". $ID_CC . " date1 ". $date1 . " date2 ". $date1 ; exit;
	
	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();
	
	$result_printdate  = select_data($con,"select to_char(SYSDATE,'DD/MM/YYYY') TGL from dual");
	$printdate = $result_printdate["TGL"];
	
	if($date1 == "0000-00-00"){
		//echo "salah";
		$_SESSION[err] 		= "please choose date". $date1. $date2;	
		header("Location:WelCetakLHMPanenFilter.php");
	}
	else{
		if($date2 == "0000-00-00"){
			$date2 = "";
		}
		//echo "benar";
		/*$sql_cetak_LHM_panen = "	
		  select tc.id_cc,
         tc.comp_name comp_name,
         tba.id_ba,
         tba.nama_ba nama_ba,
         ta.id_afd,
         thrp.tanggal_rencana tgl_panen,
         thrp.nik_mandor,
         f_get_empname (thrp.nik_mandor) nama_mandor,
         thrp.nik_pemanen,
         f_get_empname (thrp.nik_pemanen) nama_pemanen,
         thp.no_bcc,
         thp.no_tph,
         tb.id_blok,
         tdrp.luasan_panen,
         null jam_kerja,
         GHP.TBS2 tbs, GHP.BRD,  GHP.BM, GHP.BK, GHP.TP, GHP.BB,  0 jk, GHP.BT, GHP.BL, GHP.PB, GHP.AB, GHP.SF, GHP.BS,
         null kode_absen,
         null customer,
		 tb.blok_name,
		 tdrp.no_rekap_bcc,
		 thrp.id_rencana
       FROM t_header_rencana_panen thrp
         INNER JOIN t_detail_rencana_panen tdrp
            ON thrp.id_rencana = tdrp.id_rencana
         INNER JOIN t_hasil_panen thp
            ON tdrp.id_rencana = thp.id_rencana
               AND tdrp.no_rekap_bcc = thp.no_rekap_bcc
           INNER JOIN V_GET_HASIL_PANEN GHP
          ON THP.no_bcc = GHP.no_bcc
               AND tdrp.no_rekap_bcc = GHP.no_rekap_bcc
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
         and thrp.nik_mandor = nvl(decode('$NIK_Mandor', 'ALL', null, '$NIK_Mandor'), thrp.nik_mandor)
		 and TO_CHAR (thrp.tanggal_rencana, 'YYYY-MM-DD') BETWEEN '$date1' and nvl ('$date2', '$date1')
order by   tgl_panen, thrp.nik_mandor,  nama_pemanen,  tdrp.no_rekap_bcc,  thrp.id_rencana, thp.no_bcc
			";*/
			
			//Edited by Ardo, 06-08-2016 : Synchronize BCC
			$sql_cetak_LHM_panen = "
				 select 
				 ( SELECT count(val.no_bcc) FROM t_validasi val where val.no_bcc = thp.no_bcc ) as VALIDASI,
				 tc.id_cc,
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
				 null jam_kerja,
					 NVL (F_GET_HASIL_PANEN_BUNCH (tba.id_ba, thp.no_rekap_bcc, thp.no_bcc, 'BUNCH_HARVEST'), 0) as TBS,
					 NVL( F_GET_HASIL_PANEN_BRDX  (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc),0)  as BRD,
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 1),0)  as BM, 
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 2) ,0) as BK, 
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 7) ,0) as TP, 
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 6),0)  as BB, 
					 NVL (F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 15), 0) JK,
					 NVL( F_GET_HASIL_PANEN_NUMBERX ( thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 16),0)  as BA,					 
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 11),0)  as BT, 
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 12),0)  as BL, 
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 13) ,0) as PB,
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 10) ,0) as AB, 
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 14) ,0) as SF,               
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 8) ,0) as BS, 
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 3) ,0) as MS, 
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 4) ,0) as ORR, 
				 null kode_absen,
				 CASE
					WHEN ta.ID_BA <> ta2.ID_BA THEN 'CINT_' || ta.ID_BA
					ELSE NULL
				 END
					customer,
				 tb.blok_name,
				 tdrp.no_rekap_bcc,
				 thrp.id_rencana
			   FROM t_header_rencana_panen thrp
				 INNER JOIN T_EMPLOYEE te
					ON thrp.NIK_PEMANEN = te.NIK
				 INNER JOIN T_AFDELING ta2
					ON te.ID_BA_AFD = ta2.ID_BA_AFD
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
		   where     tc.id_cc = '$ID_CC'
				 and tba.id_ba = '$ID_BA'
				 and ta.id_afd = nvl(decode('$valueAfdeling', 'ALL', null, '$valueAfdeling'), ta.id_afd)
				 and thrp.nik_mandor = nvl(decode('$NIK_Mandor', 'ALL', null, '$NIK_Mandor'), thrp.nik_mandor)
				 and TO_CHAR (thrp.tanggal_rencana, 'YYYY-MM-DD') BETWEEN '$date1' and nvl ('$date2', '$date1')
		order by tgl_panen,
         thrp.nik_mandor,
		 id_afd,
		 afd_pemanen,
         nama_pemanen,
		 id_blok,
		 luasan_panen desc,
		 NO_BCC,
         BM,
         BK,
         TP,
         BB,
         JK,
         BA,
         BT,
         BL,
         PB,
         AB,
         SF,
         BS";
			
			//echo $sql_cetak_LHM_panen; die();
			//order by tdrp.no_rekap_bcc, thrp.id_rencana, thrp.nik_mandor, tgl_panen, nama_pemanen, thp.no_bcc
			$_SESSION["sql_cetak_LHM_panen"] = $sql_cetak_LHM_panen;
			$_SESSION["printdate"] = $printdate;			
			$_SESSION["tgl1"] = $date1;
			$_SESSION["tgl2"] = $date2;
			$_SESSION["ID_BA"] = $ID_BA;
			$_SESSION["ID_CC"] = $ID_CC;
			$_SESSION["valueAfd"] = $valueAfdeling;
			$_SESSION["nikmandor"] = $NIK_Mandor;
			//echo $sql_cetak_LHM_panen; die ();
			header("Location:PDF_LHMPanen.php");
	}
}
// else{
// $_SESSION[err] = "Please choose the options";
// header("Location:WelCetakLHMPanenFilter.php");
// }
?>