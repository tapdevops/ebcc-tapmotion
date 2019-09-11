<?php
session_start();

if(isset($_SESSION["Afdeling"]) || isset($_POST["date1"])){
	$valueAfdeling 		= $_SESSION["Afdeling"];
	$date1 = date("Y-m-d", strtotime($_POST["date1"]));
	$date2 = date("Y-m-d", strtotime($_POST["date2"]));
	$ID_BA 		= $_SESSION['subID_BA_Afd'];
	$ID_CC 		= $_SESSION['subID_CC'];
	$rbtn_type = $_POST['rbtn_type'];
	
	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();
	
	if($date2 == "1970-01-01")
	{
		$date2='';
	}
	
	if($date1 == "1970-01-01"){
		$_SESSION[err] 		= "please choose date";	
		header("Location:laporanNABFilter.php");
	}
	else{
		if($rbtn_type == "Rekap"){
			$sql_Laporan_NAB = "	
					select 
						tc.id_cc, 
						tc.comp_name, 
						tba.id_ba, 
						tba.nama_ba, 
						tn.tgl_nab, 
						tn.no_nab, 
						ta.id_afd, 
						tn.no_polisi, 
						tn.nik_supir, 
						f_get_empname (tn.nik_supir) nama_supir, 
						tn.nik_tukang_muat1, 
						f_get_empname (tn.nik_tukang_muat1) nama_tm1, 
						tn.nik_tukang_muat2, 
						f_get_empname (tn.nik_tukang_muat2) nama_tm2, 
						tn.nik_tukang_muat3, 
						f_get_empname (tn.nik_tukang_muat3) nama_tm3, 
						thrp.nik_kerani_buah, 
						tbj.bjr,
						f_get_empname (thrp.nik_kerani_buah) nama_kerani_buah, 
						count(thp.no_bcc) as ttl_BCC, 
						sum(f_get_hasil_panen (thp.no_rekap_bcc, thp.no_bcc, 'TBS')) estimasi_berat, 
						SUM(F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'TBS2') ) TBS,
						SUM(F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'BRD') ) BRD,
						tb.id_blok,
						tb.blok_name,
						to_char(tb.tahun_tanam,'yyyy') as tahun_tanam
					from t_header_rencana_panen thrp 
						inner join t_detail_rencana_panen tdrp on thrp.id_rencana = tdrp.id_rencana 
						inner join t_hasil_panen thp on tdrp.id_rencana = thp.id_rencana and tdrp.no_rekap_bcc = thp.no_rekap_bcc 
						inner join t_nab tn on thp.id_nab_tgl = tn.id_nab_tgl 
						inner join t_blok tb on tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
						inner join t_afdeling ta on tb.id_ba_afd = ta.id_ba_afd 
						inner join t_bussinessarea tba on tba.id_ba = ta.id_ba
						inner join t_companycode tc on tba.id_cc = tc.id_cc
						inner join t_bjr tbj on tb.id_ba_afd_blok = tbj.id_ba_afd_blok 
					where
						tc.id_cc = '$ID_CC' 
						and tba.id_ba = '$ID_BA' 
						and ta.id_afd = nvl(decode('$valueAfdeling','ALL',null,'$valueAfdeling'),ta.id_afd) 
						and to_char(tn.TGL_NAB,'yyyy-mm-dd') between '$date1' and nvl ('$date2', '$date1')
						and thp.status_bcc = 'DELIVERED'
					and to_char(SPMON,'yyyy-mm-dd') = to_char(trunc(trunc(tn.TGL_NAB,'MM')-1,'MM'),'yyyy-mm-dd')
					group by 
						tc.id_cc, 
						tc.comp_name, 
						tba.id_ba, 
						tba.nama_ba, 
						tn.tgl_nab, 
						tn.no_nab, 
						ta.id_afd, 
						tn.no_polisi, 
						tn.nik_supir, 
						f_get_empname (tn.nik_supir), 
						tn.nik_tukang_muat1, 
						f_get_empname (tn.nik_tukang_muat1), 
						tn.nik_tukang_muat2, 
						f_get_empname (tn.nik_tukang_muat2), 
						tn.nik_tukang_muat3, 
						f_get_empname (tn.nik_tukang_muat3), 
						thrp.nik_kerani_buah, 
						f_get_empname (thrp.nik_kerani_buah),tbj.bjr,tb.id_blok,
						tb.blok_name,tb.tahun_tanam order by tn.tgl_nab, ta.id_afd, tn.no_nab";
			}else{
				$sql_Laporan_NAB = "	
				select tc.id_cc,
					 tc.comp_name,
					 tba.id_ba,
					 tba.nama_ba,
					 tn.tgl_nab,
					 tn.no_nab,
					 ta.id_afd,
					 tn.no_polisi,
					 tn.nik_supir,
					 f_get_empname (tn.nik_supir) nama_supir,
					 tn.nik_tukang_muat1,
					 f_get_empname (tn.nik_tukang_muat1) nama_tm1,
					 tn.nik_tukang_muat2,
					 f_get_empname (tn.nik_tukang_muat2) nama_tm2,
					 tn.nik_tukang_muat3,
					 f_get_empname (tn.nik_tukang_muat3) nama_tm3,
					 thrp.nik_kerani_buah,
					 f_get_empname (thrp.nik_kerani_buah) nama_kerani_buah,
					 thp.no_bcc,
					 f_get_hasil_panen (thp.no_rekap_bcc, thp.no_bcc, 'TBS') estimasi_berat,
					 F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'TBS2') TBS,
					 F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'BRD') BRD,
					 tdrp.no_rekap_bcc,
					 thrp.nik_mandor,
					 thrp.nik_pemanen,
					 thrp.tanggal_rencana
				from t_header_rencana_panen thrp
					 inner join t_detail_rencana_panen tdrp
						on thrp.id_rencana = tdrp.id_rencana
					 inner join t_hasil_panen thp
						on tdrp.id_rencana = thp.id_rencana
						and tdrp.no_rekap_bcc = thp.no_rekap_bcc
					 inner join t_nab tn
						on thp.id_nab_tgl = tn.id_nab_tgl
					 inner join t_blok tb
						on tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
					 inner join t_afdeling ta
						on tb.id_ba_afd = ta.id_ba_afd
					 inner join t_bussinessarea tba
						on tba.id_ba = ta.id_ba
					 inner join t_companycode tc
						on tba.id_cc = tc.id_cc
				where tc.id_cc = '$ID_CC'
					 and tba.id_ba = '$ID_BA'
					 and ta.id_afd = nvl(decode('$valueAfdeling','ALL',null,'$valueAfdeling'),ta.id_afd)
					 and to_char(tn.TGL_NAB,'yyyy-mm-dd') between '$date1' and nvl ('$date2', '$date1')
					 and thp.status_bcc = 'DELIVERED'
				order by thrp.tanggal_rencana, ta.id_afd, tn.no_nab";
			}
			$_SESSION["sql_Laporan_NAB"] = $sql_Laporan_NAB;	
			$_SESSION["rbtn_type"] = $rbtn_type;
			echo $sql_Laporan_NAB; die();
			header("Location:laporanNAB.php");
	}
}
else{
$_SESSION[err] = "Please choose the options";
header("Location:laporanNABFilter.php");
}
?>