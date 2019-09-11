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
		header("Location:laporanLHMFilter.php");
	}
	else{
		$sql_t_NABList = "
		SELECT tn.TGL_NAB, 
         tn.no_nab,
		 tn.id_nab_tgl,
         tn.no_polisi
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
         tn.no_polisi
		 order by  tn.TGL_NAB, 
         tn.no_nab,
         tn.no_polisi
		";
		
		$sql_t_NABminOrder = "
		SELECT tc.id_cc,
         tc.comp_name,
         tba.id_ba,
         tba.nama_ba,
         tn.tgl_nab,
         tn.no_nab,
		 tn.TIPE_ORDER,
		 tn.id_nab_tgl,
         ta.id_afd,
         tn.no_polisi,
         tn.nik_supir,
          tn.id_internal_order,
         f_get_empname (tn.nik_supir) nama_supir,
         tn.nik_tukang_muat1,
         f_get_empname (tn.nik_tukang_muat1) nama_tm1,
         tn.nik_tukang_muat2,
         f_get_empname (tn.nik_tukang_muat2) nama_tm2,
         tn.nik_tukang_muat3,
         f_get_empname (tn.nik_tukang_muat3) nama_tm3,
         thp.no_bcc,
         f_get_hasil_panen (thp.no_rekap_bcc, thp.no_bcc, 'TBS')
         + f_get_hasil_panen (thp.no_rekap_bcc, thp.no_bcc, 'BRD')
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
			//echo $sql_t_NABList; die();
			header("Location:KoreksiNABList.php");
	}
}
else{
$_SESSION[err] = "Please choose the options";
header("Location:KoreksiNABFil.php");
}
?>