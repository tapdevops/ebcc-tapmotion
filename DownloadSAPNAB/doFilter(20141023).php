<?php
session_start();

if(isset($_POST["Afdeling"]) || isset($_POST["date1"]) || isset($_POST["date2"]) || isset($_POST["TampilNAB"])){
	$valueAfdeling 		= $_POST["Afdeling"];
	$date1 = date("Y-m-d", strtotime($_POST["date1"]));
	$date2 = date("Y-m-d", strtotime($_POST["date2"]));
	$ID_BA 		= $_SESSION['subID_BA_Afd'];
	$ID_CC 		= $_SESSION['subID_CC'];
	$TampilNAB	= $_POST["TampilNAB"];
	
	//echo "afdeling ". $valueAfdeling." id_ba ".$ID_BA . " id_cc ". $ID_CC . " date1 ". $date1 . " date2 ". $date2." TampilNAB ". $TampilNAB  ;
	

	
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
		//echo "benar";
		$where = " where tc.id_cc = '$ID_CC' and tba.id_ba = '$ID_BA'
       and ta.id_afd = nvl (decode ('$valueAfdeling', 'ALL', null, 
'$valueAfdeling'), ta.id_afd)
       and tn.status_download = decode ('$TampilNAB', 'ALL', 
status_download, '$TampilNAB')
       and to_char (tn.tgl_nab, 'yyyy-mm-dd') between 
'$date1' and  nvl ('$date2','$date1')
		and thp.status_bcc = 'DELIVERED'";
		
		$sql_Download_NAB = "	
		 select distinct tc.id_cc,
                tba.id_ba,
                to_char (tn.tgl_nab, 'DD.MM.YYYY') tgl_nab,
				ta.ID_AFD,
                tn.no_nab,
				tn.id_nab_tgl,
                tn.no_polisi,
				tba.id_estate
  from t_header_rencana_panen thrp
       inner join t_detail_rencana_panen tdrp
          on thrp.id_rencana = tdrp.id_rencana
       inner join t_hasil_panen thp
          on tdrp.no_rekap_bcc = thp.no_rekap_bcc
		    AND 
         TDRP.ID_RENCANA = thp.ID_RENCANA
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

   $where
			";
			
		$sql_Download_NABtxt = "	
		 SELECT DISTINCT tc.id_cc,
         tba.id_ba,
		 tba.id_estate,
         TO_CHAR (tn.tgl_nab, 'DD.MM.YYYY') TGL_NAB,
         tn.no_nab,
		 tn.id_nab_tgl,
		 thp.NO_BCC,
         tn.no_polisi
    FROM t_header_rencana_panen thrp
         INNER JOIN t_detail_rencana_panen tdrp
            ON thrp.id_rencana = tdrp.id_rencana
         INNER JOIN t_hasil_panen thp
            ON tdrp.no_rekap_bcc = thp.no_rekap_bcc
			  AND 
           TDRP.ID_RENCANA = thp.ID_RENCANA
         INNER JOIN t_nab tn
            ON thp.id_nab_tgl = tn.id_nab_tgl
         INNER JOIN t_blok tb
            ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
         INNER JOIN t_afdeling ta
            ON tb.id_ba_afd = ta.id_ba_afd
         INNER JOIN t_bussinessarea tba
            ON tba.id_ba = ta.id_ba
         INNER JOIN t_companycode tc
            ON tba.id_cc = tc.id_cc
   $where
			";
			
		$sql_Download_NABview = "	
		SELECT tc.id_cc,
       tc.comp_name,
       tba.id_ba,
       tba.nama_ba,
       tn.tgl_nab,
       tn.no_nab,
       tn.id_nab_tgl,
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
       f_get_hasil_panen (thp.no_rekap_bcc, thp.no_bcc, 'TBS')
       + GHP.BRD
          estimasi_berat,
       GHP.TBS2 tbs, GHP.BRD, 
       tdrp.no_rekap_bcc
  FROM t_header_rencana_panen thrp
       INNER JOIN t_detail_rencana_panen tdrp
          ON thrp.id_rencana = tdrp.id_rencana
       INNER JOIN t_hasil_panen thp
              ON TDRP.ID_RENCANA = THP.ID_RENCANA
               AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC
       INNER JOIN V_GET_HASIL_PANEN GHP
            ON THP.NO_BCC = GHP.NO_BCC
               AND TDRP.NO_REKAP_BCC = GHP.NO_REKAP_BCC  
       INNER JOIN t_nab tn
          ON thp.id_nab_tgl = tn.id_nab_tgl
       INNER JOIN t_blok tb
          ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
       INNER JOIN t_afdeling ta
          ON tb.id_ba_afd = ta.id_ba_afd
       INNER JOIN t_bussinessarea tba
          ON tba.id_ba = ta.id_ba
       INNER JOIN t_companycode tc
          ON tba.id_cc = tc.id_cc
   $where
			";
			
			
			$_SESSION["sql_Download_NAB"] = $sql_Download_NAB."ORDER BY tba.id_ba, ta.ID_AFD, tgl_nab, tn.no_nab";	
			$_SESSION["sql_Download_NABtxt"] = $sql_Download_NABtxt;	//ordernya ada di printTXT.php
			$_SESSION["sql_Download_NABview"] = $sql_Download_NABview;
			//echo $sql_Download_NAB;
			//echo $sql_Download_NABtxt;
			//echo $sql_Download_NABview; die();
			header("Location:DownloadSAPNAB.php");
	}
}
else{
$_SESSION[err] = "Please choose the options";
//header("Location:DownloadSAPCH.php");
}
?>