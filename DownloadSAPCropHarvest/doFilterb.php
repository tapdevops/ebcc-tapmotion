<?php
session_start();

if(isset($_POST["Afdeling"]) || isset($_POST["date1"]) || isset($_POST["date2"])){
	$valueAfdeling 		= $_POST["Afdeling"];
	$date1 	= $_POST["date1"];
	$date2 	= $_POST["date2"];
	$ID_BA 		= $_SESSION['subID_BA_Afd'];
	$ID_CC 		= $_SESSION['subID_CC'];
	
	echo "afdeling ". $valueAfdeling." id_ba ".$ID_BA . " id_cc ". $ID_CC . " date1 ". $date1 . " date2 ". $date1 ;
	
	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();
	
	if($date1 == "0000-00-00"){
		//echo "salah";
		$_SESSION[err] 		= "please choose date". $date1. $date2;	
		//header("Location:DownloadSAPCH.php");
	}

	else{
		if($date2 == "0000-00-00"){
			$date2 = "";
		}
		
		//echo "benar";
		$sql_Download_Crop_Harv = "	
		  SELECT THRP.NIK_PEMANEN,
         TO_CHAR (THRP.TANGGAL_RENCANA, 'DD.MM.YYYY') TANGGAL,
         THP.NO_BCC,
         THP.NO_TPH,
         CASE
            WHEN TA2.ID_BA <> TA.ID_BA THEN 'CINT_' || TA2.ID_BA
            ELSE NULL
         END
            CUST,
         TA.ID_BA PLANT,
         TB.ID_BLOK,
         F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'TBS2') TBS,
         F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'BRD') BRD,
         F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'TBS2') DIKIRIM,
         THRP.NIK_MANDOR,
         THRP.NIK_KERANI_BUAH NIK_KERANI_BUAH,
         CASE WHEN TDG.NIK_GANDENG != '-' THEN 'X' ELSE NULL END GANDENG,
		 CASE WHEN TDG.NIK_GANDENG = '-' THEN NULL ELSE TDG.NIK_GANDENG END NIK_GANDENG
    FROM T_HEADER_RENCANA_PANEN THRP
         INNER JOIN T_EMPLOYEE TE
            ON THRP.NIK_PEMANEN = TE.NIK
         INNER JOIN T_AFDELING TA
            ON TE.ID_BA_AFD = TA.ID_BA_AFD
         INNER JOIN T_BUSSINESSAREA TBA
            ON TA.ID_BA = TBA.ID_BA
         INNER JOIN T_DETAIL_RENCANA_PANEN TDRP
            ON THRP.ID_RENCANA = TDRP.ID_RENCANA
         INNER JOIN T_HASIL_PANEN THP
            ON TDRP.ID_RENCANA = THP.ID_RENCANA
               AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC
         INNER JOIN T_BLOK TB
            ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
         INNER JOIN T_AFDELING TA2
            ON TB.ID_BA_AFD = TA2.ID_BA_AFD
         LEFT JOIN T_DETAIL_GANDENG TDG
            ON THRP.ID_RENCANA = TDG.ID_RENCANA
   WHERE     TBA.ID_CC = '$ID_CC'
         AND TA.ID_BA = '$ID_BA'
         AND TA.ID_AFD = NVL (DECODE ('$valueAfdeling', 'ALL', NULL, '$valueAfdeling'), TA.ID_AFD)
         AND TO_CHAR (THRP.TANGGAL_RENCANA, 'yyyy-mm-dd') BETWEEN '$date1'
                                                              AND  NVL (
                                                                      '$date2',
                                                                      '$date1')
ORDER BY THRP.NIK_PEMANEN,
         TANGGAL,
         THP.NO_BCC,
         THP.NO_TPH,
         CUST,
         PLANT,
         TB.ID_BLOK,
         TBS,
         BRD,
         DIKIRIM,
         THRP.NIK_MANDOR,
         NIK_KERANI_BUAH,
         GANDENG,
         TDG.NIK_GANDENG
		";
			//	echo $sql_Download_Crop_Harv;
	//	die ();
			$_SESSION["sql_Download_Crop_Harv"] = $sql_Download_Crop_Harv;	
	
			header("Location:printXLS.php");
	}
}
else{
$_SESSION[err] = "Please choose the options";
//header("Location:DownloadSAPCH.php");
}
?>