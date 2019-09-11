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
		//Edited by Ardo, 07-11-2016 : Issue Log Export to SAP NAB
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
						F_GET_BJR (TB.ID_BLOK, tn.tgl_nab, TDRP.ID_BA_AFD_BLOK) bjr,
						f_get_empname (thrp.nik_kerani_buah) nama_kerani_buah, 
						count(thp.no_bcc) as ttl_BCC, 
						SUM ((F_GET_BJR (TB.ID_BLOK, THRP.TANGGAL_RENCANA, TDRP.ID_BA_AFD_BLOK)
						* F_GET_HASIL_PANEN_BUNCH(tba.id_ba, THP.NO_REKAP_BCC, THP.NO_BCC, 'BUNCH_SEND')))
							estimasi_berat,
						SUM(F_GET_HASIL_PANEN_BUNCH(tba.id_ba, THP.NO_REKAP_BCC, THP.NO_BCC, 'BUNCH_SEND') ) TBS,
						SUM(F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'BRD') ) BRD,
						tb.id_blok,
						tb.blok_name,
						to_char(tb.tahun_tanam,'yyyy') as tahun_tanam,
						 case
							when nsap.post_status = 'X'
							then 'Sudah Post'
							when nsap.export_status = 'X'
							then 'Sudah Export'
							WHEN nsap.export_status is null
							THEN
							   'Belum Export'
						end nab_status_export
					from t_header_rencana_panen thrp 
						left join t_detail_rencana_panen tdrp on thrp.id_rencana = tdrp.id_rencana 
						left join t_hasil_panen thp on tdrp.id_rencana = thp.id_rencana and tdrp.no_rekap_bcc = thp.no_rekap_bcc 
						left join t_nab tn on thp.id_nab_tgl = tn.id_nab_tgl 
						left join t_blok tb on tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
						left join t_afdeling ta on tb.id_ba_afd = ta.id_ba_afd 
						left join t_bussinessarea tba on tba.id_ba = ta.id_ba
						left join t_companycode tc on tba.id_cc = tc.id_cc
						LEFT JOIN t_status_to_sap_nab nsap
							ON tba.id_cc = nsap.COMP_CODE and
							tba.PROFILE_NAME = nsap.PROFILE_NAME and
							thp.no_bcc = nsap.NO_BCC and
							tn.id_nab_tgl = nsap.id_nab_tgl
					where
						tc.id_cc = '$ID_CC' 
						and tba.id_ba = '$ID_BA' 
						and ta.id_afd = nvl(decode('$valueAfdeling','ALL',null,'$valueAfdeling'),ta.id_afd) 
						and to_char(tn.TGL_NAB,'yyyy-mm-dd') between '$date1' and nvl ('$date2', '$date1')
						and thp.status_bcc = 'DELIVERED'
					group by 
						tc.id_cc, 
						tc.comp_name, 
						tba.id_ba, 
						tba.nama_ba,
						TDRP.ID_BA_AFD_BLOK,						
						tn.tgl_nab, 
						tn.no_nab, 
						ta.id_afd, 
						tn.no_polisi, 
						tn.nik_supir, 
						tn.nik_tukang_muat1, 
						tn.nik_tukang_muat2, 
						tn.nik_tukang_muat3, 
						thrp.nik_kerani_buah, 
						tb.id_blok,
						tb.blok_name,
						tb.tahun_tanam,
						nsap.post_status,
						nsap.export_status						
						order by tn.tgl_nab, ta.id_afd, tn.no_nab";
						
			}else{
				$status_export = $_SESSION["status_export"];
				if($status_export=='ALL'){
					$and_status = "";
				} else if($status_export=='Belum Export'){
					$and_status = "where nab_status_export = 'Belum Export'";
				} else if($status_export=='Sudah Export'){
					$and_status = "where nab_status_export = 'Sudah Export'";
				} else if($status_export=='Sudah Post'){
					$and_status = "where nab_status_export = 'Sudah Post'";
				}
				//Edited by Ardo, 07-11-2016 : Issue Log Export to SAP NAB
				$sql_Laporan_NAB = "	
				select * from (select tc.id_cc,
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
					 F_GET_BJR (TB.ID_BLOK, THRP.TANGGAL_RENCANA, TDRP.ID_BA_AFD_BLOK) BJR,
					 --(F_GET_BJR (TB.ID_BLOK, THRP.TANGGAL_RENCANA, TDRP.ID_BA_AFD_BLOK)
					 --	* F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'TBS2')) estimasi_berat,
					 (F_GET_BJR (TB.ID_BLOK, THRP.TANGGAL_RENCANA, TDRP.ID_BA_AFD_BLOK)
					 * F_GET_HASIL_PANEN_BUNCH(tba.id_ba, THP.NO_REKAP_BCC, THP.NO_BCC, 'BUNCH_SEND')) estimasi_berat,
					 --F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'TBS2') TBS,
					 F_GET_HASIL_PANEN_BUNCH(tba.id_ba, THP.NO_REKAP_BCC, THP.NO_BCC, 'BUNCH_SEND') TBS,
					 F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'BRD') BRD,
					 tdrp.no_rekap_bcc,
					 thrp.nik_mandor,
					 thrp.nik_pemanen,
					 thrp.tanggal_rencana,
					 nsap.post_status as nab_post_status,
					nsap.export_status as nab_export_status,
					case
						when nsap.post_status = 'X'
						then 'Sudah Post'
						when nsap.export_status = 'X'
						then 'Sudah Export'
						WHEN nsap.export_status is null
						THEN
						   'Belum Export'
					end nab_status_export,
					 tsap.post_status as bcc_post_status,
					tsap.export_status as bcc_export_status,
					thp.cetak_bcc as bcc_cetak_bcc,
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
					end bcc_status_export
				from t_header_rencana_panen thrp
					 left join t_detail_rencana_panen tdrp
						on thrp.id_rencana = tdrp.id_rencana
					 left join t_hasil_panen thp
						on tdrp.id_rencana = thp.id_rencana
						and tdrp.no_rekap_bcc = thp.no_rekap_bcc
					 left join t_nab tn
						on thp.id_nab_tgl = tn.id_nab_tgl
					 left join t_blok tb
						on tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
					 left join t_afdeling ta
						on tb.id_ba_afd = ta.id_ba_afd
					 left join t_bussinessarea tba
						on tba.id_ba = ta.id_ba
					 left join t_companycode tc
						on tba.id_cc = tc.id_cc
					 LEFT JOIN t_status_to_sap_ebcc tsap
						ON tba.id_cc = TSAP.COMP_CODE and
						tba.id_ba = TSAP.PLANT and
						thp.no_bcc = TSAP.NO_BCC
					 LEFT JOIN t_status_to_sap_nab nsap
						ON tba.id_cc = nsap.COMP_CODE and
						tba.PROFILE_NAME = nsap.PROFILE_NAME and
						thp.no_bcc = nsap.NO_BCC and
						tn.id_nab_tgl = nsap.id_nab_tgl
				where tc.id_cc = '$ID_CC'
					 and tba.id_ba = '$ID_BA'
					 and ta.id_afd = nvl(decode('$valueAfdeling','ALL',null,'$valueAfdeling'),ta.id_afd)
					 and to_char(tn.TGL_NAB,'yyyy-mm-dd') between '$date1' and nvl ('$date2', '$date1')
					 and thp.status_bcc = 'DELIVERED'
				order by thrp.tanggal_rencana, ta.id_afd, tn.no_nab) nab_all
				$and_status";
			}
			$_SESSION["sql_Laporan_NAB"] = $sql_Laporan_NAB;	
			$_SESSION["rbtn_type"] = $rbtn_type;
			
			echo $sql_Laporan_NAB; 
			die();
			
			header("Location:laporanNAB.php");
	}
}
else{
$_SESSION[err] = "Please choose the options";
header("Location:laporanNABFilter.php");
}
?>