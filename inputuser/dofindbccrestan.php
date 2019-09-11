<?php
session_start();
echo $_SESSION["CC"]."#".$_SESSION["BA"]."#".$_SESSION["AFD"]."#".$_POST["date1"]."#".$_POST["date2"]."#".$_SESSION["NIK"];
if(isset($_SESSION["CC"]) && isset($_SESSION["BA"]) && isset($_POST["date1"]) && isset($_SESSION["NIK"])){
	
include("../config/SQL_function.php");
include("../config/db_connect.php");
$con = connect();

$CC = $_SESSION["CC"];
$BA = $_SESSION["BA"];
$AFD = $_SESSION["AFD"];
$date1 = date("Y-m-d", strtotime($_POST["date1"]));
$date2 = date("Y-m-d", strtotime($_POST["date2"]));
$NIK = $_SESSION["NIK"];


	if($NIK == ""){
		$_SESSION[err] = "Created By Empty";
		header("Location:daftarbccrestan.php");
	}
	else{
	
		if($date2 == '1970-01-01')
		{
			$date2 = null;
		}
		$sql_bcc_restan  = "SELECT * FROM T_BUSSINESSAREA TBA
		INNER JOIN T_AFDELING TA ON TBA.ID_BA = TA.ID_BA
		INNER JOIN T_BLOK TB ON TA.ID_BA_AFD = TB.ID_BA_AFD 
		INNER JOIN T_DETAIL_RENCANA_PANEN TDRP ON TB.ID_BA_AFD_BLOK = 

		TDRP.ID_BA_AFD_BLOK
		INNER JOIN T_HEADER_RENCANA_PANEN THRP ON THRP.ID_RENCANA = TDRP.ID_RENCANA
		INNER JOIN T_HASIL_PANEN THP ON TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC
		WHERE TBA.ID_CC = '$CC'
		AND TBA.ID_BA = '$BA'
		AND TA.ID_AFD = nvl(decode('$AFD','ALL',null,'$AFD'),TA.ID_AFD)
		AND TO_CHAR(THRP.TANGGAL_RENCANA,'YYYY-MM-DD') BETWEEN '$date1' AND NVL('$date2', '$date1')
		AND THP.ID_NAB_TGL IS NULL
		ORDER BY THRP.TANGGAL_RENCANA, THP.NO_BCC";
		
		$_SESSION['date1'] = $date1;
		$_SESSION['date2'] = $date2;
		$_SESSION["sql_bcc_restan"] = $sql_bcc_restan;
		header("Location:daftarbccrestan.php");
	}
	
}
else{
	$_SESSION[err] = "Pilih Company Code, Business Area, atau Job Authorization Code | ".$_SESSION["CC"]."#".$_SESSION["BA"]."#".$_SESSION["AFD"]."#".$_POST["date1"]."#".$_POST["date2"]."#".$_SESSION["NIK"];
	header("Location:daftarbccrestan.php");
}

?>