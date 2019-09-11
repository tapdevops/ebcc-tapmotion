<?php
session_start();
echo $_SESSION["CC"]."#".$_SESSION["BA"]."#".$_POST["date1"]."#".$_POST["sdate2"]."#".$_SESSION["NIK"];
if(isset($_SESSION["subID_CC"]) && isset($_SESSION["subID_BA_Afd"]) && isset($_POST["sdate1"]) && isset($_SESSION["NIK"])){
	
include("../config/SQL_function.php");
include("../config/db_connect.php");
$con = connect();

$date1 = date("Y-m-d", strtotime($_POST["sdate1"]));
$date2 = date("Y-m-d", strtotime($_POST["sdate2"]));
$ID_BA 		= $_SESSION['subID_BA_Afd'];
$ID_CC 		= $_SESSION['subID_CC'];
$NIK = $_SESSION["NIK"];

echo $ID_CC; echo $ID_BA;



	if($NIK == ""){
		$_SESSION[err] = "Created By Empty";
		header("Location:daftarbccrestan.php");
	}
	else{
	
		if($date2 == '1970-01-01')
		{
			$date2 = null;
		}
		$sql_bcc_restan1 = "SELECT   F_GET_IDBA_IDR (t1.id_rencana, s.id_ba_afd_blok, s.no_rekap_bcc) as BA, t1.tanggal_rencana, 
		F_GET_NAMABLOK_IDR(t1.id_rencana, s.id_ba_afd_blok) as BLOK,  S.NO_REKAP_BCC, T1.NIK_PEMANEN, F_GET_EMPNAME(T1.NIK_PEMANEN) as pemanen, T1.NIK_KERANI_BUAH, F_GET_EMPNAME(T1.NIK_KERANI_BUAH) as KRANI, SUBSTR(s.ID_RENCANA, 10, 15) AS IMEI 
        FROM   t_header_rencana_panen t1 inner join t_detail_rencana_panen s on t1.id_rencana = s.id_rencana
         WHERE  (t1.nik_pemanen,t1.tanggal_rencana, s.id_ba_afd_blok) in ( SELECT DISTINCT nik_pemanen
                                               , tanggal_rencana, s.id_ba_afd_blok
                                 FROM     t_header_rencana_panen t1 inner join t_detail_rencana_panen s on t1.id_rencana = s.id_rencana
                                 GROUP BY nik_pemanen, tanggal_rencana, s.id_ba_afd_blok
                                 HAVING COUNT(*) > 1 ) AND F_GET_IDCC_IDR (t1.id_rencana, s.id_ba_afd_blok)='$ID_CC' AND  F_GET_IDBA_IDR (t1.id_rencana, s.id_ba_afd_blok, s.no_rekap_bcc)='$ID_BA' and TO_CHAR(t1.tanggal_rencana,'YYYY-MM-DD') BETWEEN '$date1' AND NVL('$date2', '$date1')";
		
		$_SESSION['date1'] = $date1;
		$_SESSION['date2'] = $date2;
		$_SESSION["sql_bcc_restan1"] = $sql_bcc_restan1;
		//echo $sql_bcc_restan1; die();
		//
		header("Location:daftarbccrestan.php");
	}
	
}
else{
	$_SESSION[err] = "Pilih Company Code, Business Area, atau Job Authorization Code | ".$_SESSION["CC"]."#".$_SESSION["BA"]."#".$_POST["date1"]."#".$_POST["date2"]."#".$_SESSION["NIK"];
	header("Location:daftarbccrestan.php");
}

?>