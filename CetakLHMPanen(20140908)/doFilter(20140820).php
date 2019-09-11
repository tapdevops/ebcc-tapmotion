<?php
session_start();

if(isset($_POST["valueAfd"]) || isset($_POST["NIKMandor"]) || isset($_POST["sdate1"]) || isset($_POST["sdate2"])){
	$valueAfdeling 		= $_POST["valueAfd"];
	$NIK_Mandor 		= $_POST["NIKMandor"];
	$date1 	= $_POST["sdate1"];
	$date2 	= $_POST["sdate2"];
	$ID_BA 		= $_SESSION['subID_BA_Afd'];
	$ID_CC 		= $_SESSION['subID_CC'];
	
	echo "afdeling ". $valueAfdeling." mandor ". $NIK_Mandor. " id_ba ".$ID_BA . " id_cc ". $ID_CC . " date1 ". $date1 . " date2 ". $date1 ;
	
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
		$sql_cetak_LHM_panen = "	
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
             NVL( F_GET_HASIL_PANEN_TBS2 ( thp.no_rekap_bcc, thp.no_bcc),0)  as TBS, 
             NVL( F_GET_HASIL_PANEN_BRD  ( thp.no_rekap_bcc, thp.no_bcc),0)  as BRD,
             NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 1),0)  as BM, 
             NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 2) ,0) as BK, 
             NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 7) ,0) as TP, 
             NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 6),0)  as BB, 
             0 JK, 
             NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 11),0)  as BT, 
             NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 12),0)  as BL, 
             NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 13) ,0) as PB,
             NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 10) ,0) as AB, 
             NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 14) ,0) as SF,               
             NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 8) ,0) as BS, 
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
			";
			echo $sql_cetak_LHM_panen; die();
			//order by tdrp.no_rekap_bcc, thrp.id_rencana, thrp.nik_mandor, tgl_panen, nama_pemanen, thp.no_bcc
			$_SESSION["sql_cetak_LHM_panen"] = $sql_cetak_LHM_panen;
			$_SESSION["printdate"] = $printdate;			
		//	echo $sql_cetak_LHM_panen; die ();
			header("Location:PDF_LHMPanen.php");
	}
}
else{
$_SESSION[err] = "Please choose the options";
header("Location:WelCetakLHMPanenFilter.php");
}
?>