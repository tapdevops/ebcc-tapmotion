<?php
session_start();

if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['Jenis_Login']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name']) && isset($_SESSION['subID_CC'])){	
	
	$subID_CC = $_SESSION['subID_CC'];
	if(isset($_SESSION["BA"]) == ""){
		$_SESSION["BA"] = $subID_BA_Afd;
	}
	
	if(isset($_SESSION['BA'])){
		$sID_BA = $_SESSION["BA"];
	}
	$sdate1 = "";
	$sdate2 = "";
	if(isset($_POST["date1"])){
		$_SESSION['date1'] = $_POST["date1"];
		unset($_SESSION['date2']);
	}

	if(isset($_POST["date2"])){
		$_SESSION['date2'] = $_POST["date2"];
	}

	if(isset($_SESSION['date1'])){
		$sdate1 = date("Y-m-d", strtotime($_SESSION['date1']));
	}
	
	if(isset($_SESSION['date2']) and $_SESSION['date2']!=""){
		$sdate2 = date("Y-m-d", strtotime($_SESSION['date2']));
	}
	//include("Filter/query2.php");
	//AFD
	if(isset($_POST['AFD'])){
		$_SESSION["AFD"] = $_POST['AFD'];
	}
	
	if(isset($_SESSION['AFD'])){
		$sAFD = $_SESSION["AFD"];
		$optionGETAFD = "<option value=\"$sAFD\" selected=\"selected\">$sAFD</option>";	
	}
	
	//BLOK
	if(isset($_POST['BLOK'])){
		$_SESSION["BLOK"] = $_POST['BLOK'];
		
	}
	
	if(isset($_SESSION['BLOK'])){
		$sID_BLOK = $_SESSION['BLOK'];
	}
	
	//PEMANEN
	if(isset($_POST['NIK_Pemanen'])){
		$_SESSION["NIK_Pemanen"] = $_POST['NIK_Pemanen'];
		
	}
	
	if(isset($_SESSION['NIK_Pemanen'])){
		$sNIK_Pemanen = $_SESSION['NIK_Pemanen'];
	}
	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();
	
	if($sdate2 == "1970-01-01")
	{
		$sdate2='';
		//echo $date2;
	}
	
	if($sdate1 == "1970-01-01"){
		//echo "salah";
		$_SESSION[err] 		= "please choose date";	
		header("Location:LaporanAAP.php");
	}
	else{
		//Edited by Ardo 16-08-2016 : Synchronize BCC - Laporan BCC
		$sql_t_AAP = "
		select id_rencana, tanggal_rencana, id_afd, id_blok, blok_name, nik_mandor, nama_mandor, nik_kerani_buah, nama_kerani_buah, nik_pemanen, nama_pemanen, nik_gandeng, nama_gandeng, max(luasan_panen) luasan_panen from (
   SELECT DISTINCT
                 (NVL (nik_gandeng, '-')) AS nik_gandeng,
                 thrp.id_rencana,
                 no_bcc,
                 thrp.tanggal_rencana,
                 ta.id_afd,
                 tb.id_blok,
				 tb.blok_name,
                 NIK_Mandor,
                 f_get_empname (NIK_Mandor) Nama_Mandor,
                 NIK_Kerani_buah,
                 f_get_empname (NIK_Kerani_buah) Nama_Kerani_buah,
                 nik_pemanen,
                 f_get_empname (nik_pemanen) Nama_pemanen,
                 NVL (f_get_empname (nik_gandeng), '-') AS Nama_gandeng,
                 luasan_panen
            FROM T_HEADER_RENCANA_PANEN THRP
                 INNER JOIN T_EMPLOYEE TE
                    ON THRP.NIK_PEMANEN = TE.NIK
                 INNER JOIN T_AFDELING TA2
                    ON TE.ID_BA_AFD = TA2.ID_BA_AFD
                 INNER JOIN T_DETAIL_RENCANA_PANEN TDRP
                    ON THRP.ID_RENCANA = TDRP.ID_RENCANA
                 INNER JOIN T_HASIL_PANEN THP
                    ON TDRP.ID_RENCANA = THP.ID_RENCANA
                       AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC
                 INNER JOIN T_BLOK TB
                    ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
                 INNER JOIN T_AFDELING TA
                    ON TB.ID_BA_AFD = TA.ID_BA_AFD
                 INNER JOIN T_BUSSINESSAREA TBA
                    ON TA.ID_BA = TBA.ID_BA
                 LEFT JOIN T_DETAIL_GANDENG TDG
                    ON THRP.ID_RENCANA = TDG.ID_RENCANA
           WHERE     tdrp.luasan_panen >= 0 and
							tba.id_cc = '$subID_CC' and 
							ta.ID_BA = '$sID_BA' and 
							ta.id_afd = nvl (decode ('$sAFD', 'ALL', null, '$sAFD'), ta.id_afd) and 
							tb.id_blok = nvl (decode ('$sID_BLOK', 'ALL', null, '$sID_BLOK'), tb.id_blok) and
							to_char(thrp.tanggal_rencana,'YYYY-MM-DD') between '$sdate1' and nvl ('$sdate2', '$sdate1') and
							thrp.nik_pemanen = nvl (decode ('$sNIK_Pemanen', 'ALL', null, '$sNIK_Pemanen'), thrp.nik_pemanen)
							ORDER BY thrp.tanggal_rencana,id_afd,id_blok,luasan_panen
						) group by id_rencana, tanggal_rencana, id_afd, id_blok, blok_name, nik_mandor, nama_mandor, nik_kerani_buah, nama_kerani_buah, nik_pemanen, nama_pemanen, nik_gandeng, nama_gandeng ORDER BY tanggal_rencana, id_afd, id_blok, luasan_panen desc, nik_gandeng desc
		";
		$_SESSION["sql_t_AAP"] 		= $sql_t_AAP;	
		//echo $sql_t_AAP; die();
		header("Location:LaporanAAPList.php");
	}
}
else{
	$_SESSION[err] = "Please choose the options";
	header("Location:LaporanAAP.php");
}
?>