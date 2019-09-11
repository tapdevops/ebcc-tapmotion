<?php
session_start();

if(isset($_POST["Afdeling"]) || isset($_POST["date1"]) || isset($_POST["date2"])){
	$valueAfdeling 		= $_POST["Afdeling"];
	$date1 	= date("Y-m-d", strtotime($_POST["date1"]));
	$date2 	= date("Y-m-d", strtotime($_POST["date2"]));
	$ID_BA 		= $_SESSION['subID_BA_Afd'];
	$ID_CC 		= $_SESSION['subID_CC'];
	
	//echo "afdeling ". $valueAfdeling." id_ba ".$ID_BA . " id_cc ". $ID_CC . " date1 ". $date1 . " date2 ". $date1 ;
	
	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();
	
	if($date1 == '1970-01-01'){
		//echo "salah";
		$_SESSION[err] 		= "please choose date". $date1. $date2;	
		//header("Location:DownloadSAPCH.php");
	}

	else{
		if($date2 == '1970-01-01'){
			$date2 = null;
		}
		
		//echo "benar";
		$sql_Download_Crop_Harv = "SELECT
		HPK.ID_BCC AS NOBCC,  KP.SHORT_NAME AS PENALTI, HPK.QTY AS NILAI 
 	 FROM T_HEADER_RENCANA_PANEN THRP
       INNER JOIN T_DETAIL_RENCANA_PANEN TDRP
          ON THRP.ID_RENCANA = TDRP.ID_RENCANA
       INNER JOIN T_HASIL_PANEN H
		     ON TDRP.ID_RENCANA = H.ID_RENCANA
               AND TDRP.NO_REKAP_BCC = H.NO_REKAP_BCC
       INNER JOIN  T_HASILPANEN_KUALTAS HPK
          ON  H.NO_BCC=HPK.ID_BCC
       INNER JOIN T_KUALITAS_PANEN KP
          ON KP.ID_KUALITAS = HPK.ID_KUALITAS
       INNER JOIN T_BLOK TB
          ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
       INNER JOIN T_AFDELING TA
          ON TB.ID_BA_AFD = TA.ID_BA_AFD
       INNER JOIN T_BUSSINESSAREA TBA
            ON TA.ID_BA = TBA.ID_BA   
   WHERE     TBA.ID_CC = '$ID_CC'
         AND TA.ID_BA = '$ID_BA'
		 AND TA.ID_AFD = NVL (DECODE ('$valueAfdeling', 'ALL', NULL, '$valueAfdeling'), TA.ID_AFD)
         AND TO_CHAR (THRP.TANGGAL_RENCANA, 'yyyy-mm-dd') BETWEEN '$date1'AND  NVL ('$date2','$date1')
		 AND KP.PENALTY_STATUS='Y'  AND  HPK.QTY<>'0'
         ORDER BY   HPK.ID_BCC
		";
				echo $sql_Download_Crop_Harv;die ();
			$_SESSION["sql_Download_Crop_Harv"] = $sql_Download_Crop_Harv;	
	
			header("Location:printXLS.php");
	}
}
else{
$_SESSION[err] = "Please choose the options";
//header("Location:DownloadSAPCH.php");
}
?>