<?php
session_start();

if(isset($_SESSION["Afdeling"]) || isset($_SESSION["NIKMandor"]) || isset($_SESSION["NIKPemanen"]) || isset($_POST["date1"])){
	$valueAfdeling 		= $_SESSION["Afdeling"];
	$NIK_Mandor 		= $_SESSION["NIKMandor"];
	$NIK_Pemanen 		= $_SESSION["NIKPemanen"];
	$date1 = date("Y-m-d", strtotime($_POST["date1"]));
	$date2 = date("Y-m-d", strtotime($_POST["date2"]));
	$ID_BA 		= $_SESSION['subID_BA_Afd'];
	$ID_CC 		= $_SESSION['subID_CC'];
	$rbtn_type = $_POST['rbtn_type'];

	if($date2 == "1970-01-01"){
		$date_month = date("m.Y", strtotime($_POST["date1"])-1);
	}
	else{
		$date_month = date("m.Y", strtotime($_POST["date2"])-1);
	}
	
	
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
		header("Location:laporanBCPFilter.php");
	}
	else{
		if($rbtn_type == "Detail"){
			$sql_Laporan_BCP = "	
				select 
				thrp.tanggal_rencana tgl_panen, 
				tc.id_cc, 
				tba.id_ba, 
				ta.id_afd, 
				thrp.nik_kerani_buah, 
				f_get_empname (thrp.nik_kerani_buah) nama_kerani_buah, 
				tb.id_blok, 
				tb.blok_name, 
				thrp.nik_pemanen, 
				f_get_empname (thrp.nik_pemanen) nama_pemanen, 
				thp.no_bcc, 
				sum(NVL(F_GET_HASIL_PANEN_BUNCH(TBA.ID_BA, THP.NO_REKAP_BCC, THP.NO_BCC, 'BUNCH_HARVEST'),0)) as TBS,
				sum(NVL( F_GET_HASIL_PANEN_BRDX ( thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc),0)) as BRD,
				NVL(tn.no_nab,'-') as NO_NAB
				from t_header_rencana_panen thrp 
				inner join t_detail_rencana_panen tdrp on thrp.id_rencana = tdrp.id_rencana 
				inner join t_hasil_panen thp on tdrp.id_rencana = thp.id_rencana and tdrp.no_rekap_bcc = thp.no_rekap_bcc 
				inner join t_blok tb on tdrp.id_ba_afd_blok = tb.id_ba_afd_blok 
				inner join t_afdeling ta on tb.id_ba_afd = ta.id_ba_afd 
				inner join t_bussinessarea tba on tba.id_ba = ta.id_ba 
				inner join t_companycode tc on tba.id_cc = tc.id_cc 
				left join t_nab tn on tn.ID_NAB_TGL = thp.ID_NAB_TGL
				where tc.id_cc = '$ID_CC' 
				and tba.id_ba = '$ID_BA' 
				--and thrp.id_rencana like '%.MANUAL.%'
				and thp.no_bcc like '%B'
				and ta.id_afd = nvl(decode('','ALL',null,''),ta.id_afd) 
				and to_char(thrp.tanggal_rencana,'yyyy-mm-dd') between '$date1' and nvl ('$date2', '$date1') group by thrp.tanggal_rencana, tc.id_cc,
				 tba.id_ba, ta.id_afd,thrp.nik_kerani_buah,f_get_empname (thrp.nik_kerani_buah), tb.id_blok, 
				tb.blok_name,thrp.nik_pemanen,f_get_empname (thrp.nik_pemanen),thp.no_bcc,tn.no_nab order by tgl_panen,ta.id_afd,f_get_empname (thrp.nik_kerani_buah),
				tb.id_blok,f_get_empname (thrp.nik_pemanen)";
		}else{
			$sql_Laporan_BCP = "select 
				thrp.tanggal_rencana tgl_panen, 
				tc.id_cc, 
				tba.id_ba, 
				ta.id_afd, 
				f_get_empname (thrp.nik_kerani_buah) nama_kerani_buah, 
				thrp.nik_kerani_buah, 
				tb.id_blok, 
				tb.blok_name, 
				count(thp.no_bcc) jml_bcc, 
				sum(NVL(F_GET_HASIL_PANEN_BUNCH(TBA.ID_BA, THP.NO_REKAP_BCC, THP.NO_BCC, 'BUNCH_HARVEST'),0)) AS TBS,
				sum(NVL (F_GET_HASIL_PANEN_BRDX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc), 0)) AS BRD
				from t_header_rencana_panen thrp 
				inner join t_detail_rencana_panen tdrp on thrp.id_rencana = tdrp.id_rencana 
				inner join t_hasil_panen thp on tdrp.id_rencana = thp.id_rencana and tdrp.no_rekap_bcc = thp.no_rekap_bcc 
				inner join t_blok tb on tdrp.id_ba_afd_blok = tb.id_ba_afd_blok 
				inner join t_afdeling ta on tb.id_ba_afd = ta.id_ba_afd 
				inner join t_bussinessarea tba on tba.id_ba = ta.id_ba 
				inner join t_companycode tc on tba.id_cc = tc.id_cc 
				where tc.id_cc = '$ID_CC' 
				and tba.id_ba = '$ID_BA' 
				--and thrp.id_rencana like '%.MANUAL.%'
				and thp.no_bcc like '%B'
				and ta.id_afd = nvl(decode('','ALL',null,''),ta.id_afd) 
				and to_char(thrp.tanggal_rencana,'yyyy-mm-dd') between '$date1' and nvl ('$date2', '$date1') group by thrp.tanggal_rencana, tc.id_cc, tc.comp_name,
				 tba.id_ba, tba.nama_ba, ta.id_afd,thrp.nik_kerani_buah,f_get_empname (thrp.nik_kerani_buah), tb.id_blok, 
				tb.blok_name  order by tgl_panen, thrp.nik_kerani_buah";
		}

		$_SESSION["sql_Laporan_BCP"] = $sql_Laporan_BCP;
		//echo $sql_Laporan_BCP;die();
		$_SESSION["rbtn_type"] = $rbtn_type;
		header("Location:laporanBCP.php");
	}
}
else{
$_SESSION[err] = "Please choose the options";
header("Location:laporanBCPFilter.php");
}
?>