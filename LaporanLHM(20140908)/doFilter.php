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
	$valueBlok 	= $_POST["blok_post"];
	$NIK_Pemanen_rekap = $_POST['nik_pemanen_rekap_post'];
	$NIK_Mandor_rekap = $_POST['nik_mandor_rekap_post'];
	
	$rbtn_type = $_POST['rbtn_type'];
	$rbtn_filter = $_POST['rbtn_filter'];
	if($rbtn_type == "Detail"){
		$rbtn_filter = "";
	}
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
		header("Location:laporanLHMFilter.php");
	}
	else{
		if($rbtn_type == "Detail"){
			$sql_Laporan_LHM = "	
				select tc.id_cc,
			 tc.comp_name,
			 tba.id_ba,
			 tba.nama_ba,
			 thrp.tanggal_rencana tgl_panen,
			 thrp.no_lhm,
			 ta.id_afd,
			 thrp.nik_mandor,
			 f_get_empname (thrp.nik_mandor) nama_mandor,
			 thrp.nik_pemanen,
			 f_get_empname (thrp.nik_pemanen) nama_pemanen,
			 thrp.nik_kerani_buah,
			 f_get_empname (thrp.nik_kerani_buah) nama_kerani_buah,
			 tb.id_blok,
			 tb.blok_name,
			 tdrp.luasan_panen,
			 thp.no_bcc,
			 thp.no_tph,
			 tdrp.no_rekap_bcc,
			 GHP.TBS2 tbs, GHP.BRD,  GHP.BM, GHP.BK, GHP.TP, GHP.BB,  0 jk, GHP.BT, GHP.BL, GHP.PB, GHP.AB, GHP.SF, GHP.BS
		from t_header_rencana_panen thrp
			 inner join t_detail_rencana_panen tdrp
				on thrp.id_rencana = tdrp.id_rencana
			 inner join t_hasil_panen thp
				on tdrp.id_rencana = thp.id_rencana
				and tdrp.no_rekap_bcc = thp.no_rekap_bcc
			 INNER JOIN V_GET_HASIL_PANEN GHP
			  ON THP.no_bcc = GHP.no_bcc
				   AND tdrp.no_rekap_bcc = GHP.no_rekap_bcc
			 inner join t_blok tb
				on tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
			 inner join t_afdeling ta
				on tb.id_ba_afd = ta.id_ba_afd
			 inner join t_bussinessarea tba
				on tba.id_ba = ta.id_ba
			 inner join t_companycode tc
				on tba.id_cc = tc.id_cc
	   where     tc.id_cc = '$ID_CC'
			 and tba.id_ba = '$ID_BA'
			 and ta.id_afd = nvl(decode('$valueAfdeling','ALL',null,'$valueAfdeling'),ta.id_afd)
			 and thrp.nik_mandor = nvl(decode('$NIK_Mandor','ALL',null,'$NIK_Mandor'),thrp.nik_mandor)
			 and thrp.nik_pemanen = nvl(decode('$NIK_Pemanen','ALL',null,'$NIK_Pemanen'),thrp.nik_pemanen)
			 and to_char(thrp.tanggal_rencana,'yyyy-mm-dd') between '$date1' and nvl ('$date2', '$date1')
		order by thrp.nik_mandor, tgl_panen, thrp.nik_pemanen, thp.no_bcc";
		 /*
		 order by tc.id_cc,
		 tba.id_ba,
		 thrp.tanggal_rencana,
		 thrp.no_lhm,
		 ta.id_afd,
		 thrp.nik_mandor,
		 thrp.nik_pemanen,
		 tb.id_blok,
		 thp.no_bcc
		*/
		}else{
			if($rbtn_filter == "Pemanen"){
				$sql_Laporan_LHM = "select tgl_panen, nama_pemanen, nik_pemanen, sum(luasan_panen) as total_HA_panen, sum(TBS) as TBS, sum(BRD) as BRD,
					sum(BM) as BM, sum(BK) as BK, sum(TP) as TP, sum(BB) as BB, sum(jk) as JK, sum(BT) as BT, sum(BL) as BL, sum(PB) as PB, sum(AB) as AB, 
					sum(SF) as SF, sum(BS) as BS from(
					select tgl_panen, nama_pemanen, nik_pemanen, luasan_panen, sum(TBS) as TBS, sum(BRD) as BRD,
					sum(BM) as BM, sum(BK) as BK, sum(TP) as TP, sum(BB) as BB, sum(jk) as JK, sum(BT) as BT, sum(BL) as BL, 
					sum(PB) as PB, sum(AB) as AB, sum(SF) as SF, sum(BS) as BS
					 from (
					select 
					tc.id_cc, 
					tc.comp_name, 
					tba.id_ba, 
					tba.nama_ba, 
					thrp.tanggal_rencana tgl_panen, 
					thrp.no_lhm, 
					ta.id_afd, 
					thrp.nik_mandor, 
					f_get_empname (thrp.nik_mandor) nama_mandor, 
					thrp.nik_pemanen, 
					f_get_empname (thrp.nik_pemanen) nama_pemanen, 
					thrp.nik_kerani_buah, 
					f_get_empname (thrp.nik_kerani_buah) nama_kerani_buah, 
					tb.id_blok, 
					tb.blok_name, 
					tdrp.luasan_panen, 
					thp.no_bcc, 
					thp.no_TPH,
					tdrp.no_rekap_bcc, 
					GHP.TBS2 tbs, 
					GHP.BRD, 
					GHP.BM, 
					GHP.BK, 
					GHP.TP, 
					GHP.BB, 
					0 jk, 
					GHP.BT, 
					GHP.BL, 
					GHP.PB, 
					GHP.AB, 
					GHP.SF, 
					GHP.BS 
					from t_header_rencana_panen thrp 
					inner join t_detail_rencana_panen tdrp on thrp.id_rencana = tdrp.id_rencana 
					inner join t_hasil_panen thp on tdrp.id_rencana = thp.id_rencana and tdrp.no_rekap_bcc = thp.no_rekap_bcc 
					INNER JOIN V_GET_HASIL_PANEN GHP ON THP.no_bcc = GHP.no_bcc AND tdrp.no_rekap_bcc = GHP.no_rekap_bcc 
					inner join t_blok tb on tdrp.id_ba_afd_blok = tb.id_ba_afd_blok 
					inner join t_afdeling ta on tb.id_ba_afd = ta.id_ba_afd 
					inner join t_bussinessarea tba on tba.id_ba = ta.id_ba 
					inner join t_companycode tc on tba.id_cc = tc.id_cc 
					where tc.id_cc = '$ID_CC' 
					and tba.id_ba = '$ID_BA'
					and ta.id_afd = nvl(decode('$valueAfdeling','ALL',null,'$valueAfdeling'),ta.id_afd)
					and thrp.nik_mandor = nvl(decode('$NIK_Mandor_rekap','ALL',null,'$NIK_Mandor_rekap'),thrp.nik_mandor)
					and thrp.nik_pemanen = nvl(decode('$NIK_Pemanen_rekap','ALL',null,'$NIK_Pemanen_rekap'),thrp.nik_pemanen)
					and to_char(thrp.tanggal_rencana,'yyyy-mm-dd') between '$date1' and nvl ('$date2', '$date1')
					order by thrp.nik_mandor, tgl_panen, thrp.nik_pemanen, thp.no_bcc) DETAIL
					group by tgl_panen, nik_pemanen, nama_pemanen, luasan_panen order by tgl_panen, nik_pemanen) SUM_REKAP
					group by tgl_panen, nik_pemanen, nama_pemanen order by tgl_panen, nik_pemanen";
			}else{
				$sql_Laporan_LHM = "
					select tgl_panen, id_blok, blok_name, ha_blok, sum(luasan_panen) as luasan_panen, sum(TBS) as TBS, sum(BRD) as BRD, 
					sum(BM) as BM, sum(BK) as BK, sum(TP) as TP, sum(BB) as BB, sum(jk) as JK, sum(BT) as BT, sum(BL) as BL, sum(PB) as PB, sum(AB) as AB, sum(SF) as SF, sum(BS) as BS from (
					select tgl_panen, id_blok, blok_name, ha_blok, luasan_panen, sum(TBS) as TBS, sum(BRD) as BRD,
					sum(BM) as BM, sum(BK) as BK, sum(TP) as TP, sum(BB) as BB, sum(jk) as JK, sum(BT) as BT, sum(BL) as BL, sum(PB) as PB, sum(AB) as AB, sum(SF) as SF, sum(BS) as BS
					from (
					select 
					tc.id_cc, 
					tc.comp_name, 
					tba.id_ba, 
					tba.nama_ba, 
					thrp.tanggal_rencana tgl_panen, 
					thrp.no_lhm, 
					ta.id_afd, 
					thrp.nik_mandor, 
					f_get_empname (thrp.nik_mandor) nama_mandor, 
					thrp.nik_pemanen, 
					f_get_empname (thrp.nik_pemanen) nama_pemanen, 
					thrp.nik_kerani_buah, 
					f_get_empname (thrp.nik_kerani_buah) nama_kerani_buah, 
					tb.id_blok, 
					tb.blok_name, 
					tdrp.luasan_panen, 
					thb.planted as ha_blok,
					thp.no_bcc, 
					thp.no_TPH,
					tdrp.no_rekap_bcc, 
					GHP.TBS2 tbs, 
					GHP.BRD, 
					GHP.BM, 
					GHP.BK, 
					GHP.TP, 
					GHP.BB, 
					0 jk, 
					GHP.BT, 
					GHP.BL, 
					GHP.PB, 
					GHP.AB, 
					GHP.SF, 
					GHP.BS 
					from t_header_rencana_panen thrp 
					inner join t_detail_rencana_panen tdrp on thrp.id_rencana = tdrp.id_rencana 
					inner join t_hasil_panen thp on tdrp.id_rencana = thp.id_rencana and tdrp.no_rekap_bcc = thp.no_rekap_bcc 
					INNER JOIN V_GET_HASIL_PANEN GHP ON THP.no_bcc = GHP.no_bcc AND tdrp.no_rekap_bcc = GHP.no_rekap_bcc 
					inner join t_blok tb on tdrp.id_ba_afd_blok = tb.id_ba_afd_blok 
					inner join t_afdeling ta on tb.id_ba_afd = ta.id_ba_afd 
					inner join t_bussinessarea tba on tba.id_ba = ta.id_ba 
					inner join t_companycode tc on tba.id_cc = tc.id_cc 
					inner join t_ha_block thb on concat(concat(thb.comp_code,thb.est_code), thb.afd_code) = tb.id_ba_afd and thb.block_code = tb.id_blok
					where tc.id_cc = '$ID_CC' 
					and tba.id_ba = '$ID_BA'
					and ta.id_afd = nvl(decode('A','ALL',null,'A'),ta.id_afd) 
					and thb.spmon = '$date_month'
					and to_char(thrp.tanggal_rencana,'yyyy-mm-dd') between '$date1' and nvl ('$date2', '$date1')
					order by thrp.nik_mandor, tgl_panen, thrp.nik_pemanen, thp.no_bcc) DETAIL
					group by tgl_panen, id_blok, blok_name, ha_blok, luasan_panen) SUM_REKAP
					group by tgl_panen, id_blok, blok_name, ha_blok";
			}
		}
		$_SESSION["sql_Laporan_LHM"] = $sql_Laporan_LHM;
		$_SESSION["rbtn_type"] = $rbtn_type;
		$_SESSION["rbtn_filter"] = $rbtn_filter;
		header("Location:laporanLHM.php");
	}
}
else{
$_SESSION[err] = "Please choose the options";
header("Location:laporanLHMFilter.php");
}
?>