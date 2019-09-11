<?php
session_start();
//echo $_SESSION["subID_CC"]."#".$_SESSION["subID_BA_Afd"]."#".$_SESSION["Afdeling"]."#".$_POST["sdate1"]."#".$_POST["sdate2"]."#".$_SESSION["NIK"];
if(isset($_SESSION["subID_CC"]) && isset($_SESSION["subID_BA_Afd"]) && isset($_POST["date1"]) && isset($_SESSION["NIK"])){
	
include("../config/SQL_function.php");
include("../config/db_connect.php");
$con = connect();

$valueAfdeling 		= $_SESSION["Afdeling"];
$date1 		= date("Y-m-d", strtotime($_POST["date1"]));
$date2 		= date("Y-m-d", strtotime($_POST["date2"]));
$ID_BA 		= $_SESSION['subID_BA_Afd'];
$ID_CC 		= $_SESSION['subID_CC'];
$NIK = $_SESSION["NIK"];


	if($NIK == ""){
		$_SESSION[err] = "Created By Empty";
		header("Location:daftarbccrestan.php");
	}
	else{ //die('XX');
	
		if($date2 == '1970-01-01')
		{
			$date2 = null;
		}
		$sql_bcc_restan  = "SELECT TBA.ID_CC, tba.ID_BA, ID_AFD, TANGGAL_RENCANA, ID_BLOK,NIK_MANDOR, NO_BCC,
		f_get_hasil_panen (thp.no_rekap_bcc, thp.no_bcc, 'TBS2') TBS,
        f_get_hasil_panen (thp.no_rekap_bcc, thp.no_bcc, 'BRD') BRD, round(    f_get_hasil_panen (thp.no_rekap_bcc, thp.no_bcc, 'TBS')
         + f_get_hasil_panen (thp.no_rekap_bcc, thp.no_bcc, 'BRD'),0)
        ESTIMASI_BERAT FROM T_BUSSINESSAREA TBA
		INNER JOIN T_AFDELING TA ON TBA.ID_BA = TA.ID_BA
		INNER JOIN T_BLOK TB ON TA.ID_BA_AFD = TB.ID_BA_AFD 
		INNER JOIN T_DETAIL_RENCANA_PANEN TDRP ON TB.ID_BA_AFD_BLOK = 

		TDRP.ID_BA_AFD_BLOK
		INNER JOIN T_HEADER_RENCANA_PANEN THRP ON THRP.ID_RENCANA = TDRP.ID_RENCANA
		INNER JOIN T_HASIL_PANEN THP ON tdrp.id_rencana = thp.id_rencana
            AND tdrp.no_rekap_bcc = thp.no_rekap_bcc
		WHERE TBA.ID_CC = '$ID_CC'
		AND TBA.ID_BA = '$ID_BA'
		AND TA.ID_AFD = nvl(decode('$valueAfdeling','ALL',null,'$valueAfdeling'),TA.ID_AFD)
		AND TO_CHAR(THRP.TANGGAL_RENCANA,'YYYY-MM-DD') BETWEEN '$date1' AND NVL('$date2', '$date1')
		AND THP.ID_NAB_TGL='0' AND THP.STATUS_BCC='RESTAN'
		ORDER BY THRP.TANGGAL_RENCANA, THP.NO_BCC";
		
		$_SESSION['date1'] = $date1;
		$_SESSION['date2'] = $date2;
		$_SESSION["sql_bcc_restan"] = $sql_bcc_restan;
		//print_r($_SESSION['date1']);die();
		//echo $sql_bcc_restan; die;

		header("Location:daftarbccrestan.php?date1=".$date1."&date2=".$date2);
	}
	
}
else{
	$_SESSION[err] = "Pilih Company Code, Business Area, atau Job Authorization Code | ".$_SESSION["subID_CC"]."#".$_SESSION["subID_BA_Afd"]."#".$_SESSION["Afdeling"]."#".$_POST["date1"]."#".$_POST["date2"]."#".$_SESSION["NIK"];
	header("Location:daftarbccrestan.php");
}

?>