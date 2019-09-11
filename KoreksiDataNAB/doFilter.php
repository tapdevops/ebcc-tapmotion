<?php
session_start();

if(isset($_POST["Afdeling"]) || isset($_POST["date1"]) || isset($_POST["date2"]) || isset($_POST["date3"]) || isset($_POST["selectfinder"]) || isset($_POST["typefinder"])){
	$valueAfdeling 		= $_POST["Afdeling"];
	$date1 = date("Y-m-d", strtotime($_POST["date1"]));
	$date2 = date("Y-m-d", strtotime($_POST["date2"]));
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
		header("Location:KoreksiNABFil.php");
	}
	else{
		//Edited by Ardo, 15-08-2016 : Synchronize BCC - Koreksi NAB
		
		$sql_t_NABList = "
		SELECT tn.TGL_NAB, 
         tn.no_nab,
		 tn.id_nab_tgl,
         tn.no_polisi,
		 tc.id_cc,
		 tba.PROFILE_NAME,
		 tba.id_estate,
		 ta.id_afd,
		 tba.id_ba
    from t_header_rencana_panen thrp
         inner join t_detail_rencana_panen tdrp
            on thrp.id_rencana = tdrp.id_rencana
         inner join t_hasil_panen thp
            on tdrp.no_rekap_bcc = thp.no_rekap_bcc and
		    tdrp.id_rencana = thp.id_rencana
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
         and ta.id_afd = nvl (decode ('$valueAfdeling', 'ALL', null, '$valueAfdeling'), ta.id_afd)
         and to_char (tn.tgl_nab, 'yyyy-mm-dd') between '$date1' and  nvl ('$date2','$date1')
		 group by 
         tn.TGL_NAB, 
         tn.no_nab,
		 tn.id_nab_tgl,
         tn.no_polisi,
		 tc.id_cc,
         tba.PROFILE_NAME,
         tba.id_estate,
		 ta.id_afd,
		 tba.id_ba
		 order by  tn.TGL_NAB, 
         tn.no_nab,
         tn.no_polisi
		";
		
		$sql_t_NABminOrder = "
		SELECT tc.id_cc,
         tc.comp_name,
         tba.id_ba,
         tba.nama_ba,
		 thrp.tanggal_rencana,
         tn.tgl_nab,
         tn.no_nab,
		 tn.TIPE_ORDER,
		 tn.id_nab_tgl,
         ta.id_afd,
		 tb.id_blok,
         tb.blok_name,
		 thp.no_tph,
         thp.kode_delivery_ticket,
         tn.no_polisi,
         tn.nik_supir,
         tn.id_internal_order,
		 thrp.NIK_PEMANEN,
         f_get_empname(THRP.NIK_PEMANEN) nama_pemanen,
         f_get_empname (tn.nik_supir) nama_supir,
         tn.nik_tukang_muat1,
         f_get_empname (tn.nik_tukang_muat1) nama_tm1,
         tn.nik_tukang_muat2,
         f_get_empname (tn.nik_tukang_muat2) nama_tm2,
         tn.nik_tukang_muat3,
         f_get_empname (tn.nik_tukang_muat3) nama_tm3,
         thp.no_bcc,
		 F_GET_BJR (TB.ID_BLOK, THRP.TANGGAL_RENCANA, TDRP.ID_BA_AFD_BLOK) BJR,
         --f_get_hasil_panen (thp.no_rekap_bcc, thp.no_bcc, 'TBS2') JJG,
         NVL (F_GET_HASIL_PANEN_BUNCH (TBA.ID_BA, thp.no_rekap_bcc, thp.no_bcc, 'BUNCH_SEND'), 0) AS JJG,
         --NVL (F_GET_HASIL_PANEN_BUNCH (TBA.ID_BA, thp.no_rekap_bcc, thp.no_bcc,  'BUNCH_HARVEST'), 0) AS BRD,
         NVL(f_get_hasil_panen (thp.no_rekap_bcc, thp.no_bcc, 'BRD'),0) BRD,
		 NVL(F_GET_BJR (TB.ID_BLOK, THRP.TANGGAL_RENCANA, TDRP.ID_BA_AFD_BLOK),0) *
		 NVL (F_GET_HASIL_PANEN_BUNCH (TBA.ID_BA, thp.no_rekap_bcc, thp.no_bcc, 'BUNCH_SEND'), 0)
            estimasi_berat
    from t_header_rencana_panen thrp
         inner join t_detail_rencana_panen tdrp
            on thrp.id_rencana = tdrp.id_rencana
         inner join t_hasil_panen thp
            on tdrp.no_rekap_bcc = thp.no_rekap_bcc and
				tdrp.id_rencana = thp.id_rencana
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
         and ta.id_afd = nvl (decode ('$valueAfdeling', 'ALL', null, '$valueAfdeling'), ta.id_afd)
         and to_char (tn.tgl_nab, 'yyyy-mm-dd') between '$date1' and  nvl ('$date2','$date1')	
		";
		
		$Order = " 
order by tc.comp_name,
         tba.id_ba,
         tba.nama_ba,
         tn.tgl_nab,
         tn.no_nab,
         ta.id_afd,
         tn.no_polisi";
		
			$_SESSION["sql_t_NABList"] 		= $sql_t_NABList;	
			$_SESSION["sql_t_NABminOrder"] 		= $sql_t_NABminOrder;
			$_SESSION["NABOrder"] 		= $Order;
			$_SESSION["date1"] 		= $date1;
			$_SESSION["date2"] 		= $date2;
			
			//echo $sql_t_NABList; die();
			header("Location:KoreksiNABList.php");
	}
}
else{
$_SESSION[err] = "Please choose the options";
header("Location:KoreksiNABFil.php");
}
?>