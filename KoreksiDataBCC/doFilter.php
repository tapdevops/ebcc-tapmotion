<?php
session_start();

if(isset($_POST["Afdeling"]) || isset($_POST["date1"]) || isset($_POST["date2"]) || isset($_POST["date3"]) || isset($_POST["selectfinder"]) || isset($_POST["typefinder"])){
	$valueAfdeling 		= $_POST["Afdeling"];
	$date1 = date("Y-m-d", strtotime($_POST["date1"]));
	$date2 = date("Y-m-d", strtotime($_POST["date2"]));
	$selectfinder 	= $_POST["selectfinder"];
	$typefinder 	= $_POST["typefinder"];
	$ID_BA 		= $_SESSION['subID_BA_Afd'];
	$ID_CC 		= $_SESSION['subID_CC'];	
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
		//header("Location:laporanLHMFilter.php"); 
		header("Location:KoreksiBCCFil.php"); //Edited by Ardo, 27-01-2016 : wrong header location
	}
	else{
		//Edited by Ardo, 15-08-2016 : Synchronize BCC - Koreksi BCC
			$sql_t_BCC = "
		select 
		thrp.id_rencana,
		thrp.tanggal_rencana tanggal,
		tba.id_cc AS CC,
       tba.id_ba AS BA,
       ta.id_afd AS AFD,
       thp.no_bcc,
	   thrp.nik_kerani_buah,
       f_get_empname (thrp.nik_kerani_buah) nama_kerani,
       thrp.nik_pemanen,
       f_get_empname (thrp.nik_pemanen) nama_pemanen,
       thrp.nik_mandor,
       f_get_empname (thrp.nik_mandor) nama_mandor,
	   tsap.post_status,
		tsap.export_status,
		thp.cetak_bcc,
		case
			when tsap.post_status = 'X'
			then 'Sudah Post'
			when tsap.export_status = 'X'
			then 'Sudah Export'
			WHEN tsap.export_status is null AND thp.cetak_bcc = 'X'
            THEN
               'Tercetak'
            WHEN tsap.export_status is null AND thp.cetak_bcc is null
            THEN
               'Belum Cetak'
			   end status_export
  from t_header_rencana_panen thrp
       inner join t_detail_rencana_panen tdrp
          on thrp.id_rencana = tdrp.id_rencana
       inner join t_hasil_panen thp
          on tdrp.id_rencana = thp.id_rencana
		  and tdrp.no_rekap_bcc = thp.no_rekap_bcc
       inner join t_blok tb
          on tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
       inner join t_afdeling ta
          on tb.id_ba_afd = ta.id_ba_afd
       inner join t_bussinessarea tba
          on ta.id_ba = tba.id_ba
		LEFT JOIN t_status_to_sap_ebcc tsap
				ON tba.id_cc = TSAP.COMP_CODE and
				tba.id_ba = TSAP.PLANT and
				thp.no_bcc = TSAP.NO_BCC
where     tba.id_cc = '$ID_CC'
       and tba.id_ba = '$ID_BA'
       and ta.id_afd = nvl (decode ('$valueAfdeling', 'ALL', null, '$valueAfdeling'), ta.id_afd)
       and to_char(thrp.tanggal_rencana,'YYYY-MM-DD') between '$date1' and nvl ('$date2', '$date1')
       and ( (upper ('$selectfinder') = 'PEMANEN'
              and (thrp.nik_pemanen like '%' || '$typefinder' || '%'
                   or f_get_empname (thrp.nik_pemanen) like
                        '%' || upper ('$typefinder') || '%'))
            or (upper ('$selectfinder') = 'MANDOR'
                and (thrp.nik_mandor like '%' || '$typefinder' || '%'
                     or f_get_empname (thrp.nik_mandor) like
                          '%' || upper ('$typefinder') || '%'))
            or (upper ('$selectfinder') = 'NO_BCC'
                and (thp.no_bcc like '%' || '$typefinder' || '%')))
			order by thrp.tanggal_rencana, nama_mandor, thp.no_bcc
			"; 
			$_SESSION["sql_t_BCC"] 		= $sql_t_BCC;	
			//echo $sql_t_BCC; die();
			header("Location:KoreksiBCCList.php");
	}
}
else{
$_SESSION[err] = "Please choose the options";
header("Location:KoreksiBCCFil.php");
}
?>