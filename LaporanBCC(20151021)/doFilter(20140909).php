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
		header("Location:LaporanBCC.php");
	}
	else{
		$sql_t_BCC = "
		select thrp.tanggal_rencana tanggal,
		tba.id_cc AS CC,
		tba.id_ba AS BA,
		ta.id_afd AS AFD,
		thp.no_bcc,
		thrp.nik_pemanen,
		f_get_empname (thrp.nik_pemanen) nama_pemanen,
		thrp.nik_mandor,
		f_get_empname (thrp.nik_mandor) nama_mandor
		from t_header_rencana_panen thrp
		inner join t_detail_rencana_panen tdrp
		on thrp.id_rencana = tdrp.id_rencana
		inner join t_hasil_panen thp
		on tdrp.id_rencana = thp.id_rencana
         AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC
		inner join t_blok tb
		on tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
		inner join t_afdeling ta
		on tb.id_ba_afd = ta.id_ba_afd
		inner join t_bussinessarea tba
		on ta.id_ba = tba.id_ba
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
			  GROUP BY thrp.tanggal_rencana ,
		tba.id_cc ,
		tba.id_ba ,
		ta.id_afd ,
		thp.no_bcc,
		thrp.nik_pemanen,
		f_get_empname (thrp.nik_pemanen),
		thrp.nik_mandor,
		f_get_empname (thrp.nik_mandor) 

		";
		$_SESSION["sql_t_BCC"] 		= $sql_t_BCC;	
		//echo $sql_t_BCC; die();
		header("Location:LaporanBCCList.php");
	}
}
else{
$_SESSION[err] = "Please choose the options";
header("Location:LaporanBCCFil.php");
}
?>