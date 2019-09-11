<?php
session_start();

if(isset($_POST["Afdeling"]) || isset($_POST["date1"]) || isset($_POST["date2"])){
	$valueAfdeling 		= $_POST["Afdeling"];
	$date1 = date("Y-m-d", strtotime($_POST["date1"]));
	$date2 = date("Y-m-d", strtotime($_POST["date2"]));
	$ID_BA 		= $_SESSION['subID_BA_Afd'];
	$ID_CC 		= $_SESSION['subID_CC'];
	$TampilNAB	= "ALL";
	
	//echo "afdeling ". $valueAfdeling." id_ba ".$ID_BA . " id_cc ". $ID_CC . " date1 ". $date1 . " date2 ". $date2." TampilNAB ". $TampilNAB  ;
	

	
	if($date2 == "1970-01-01")
	{
		$date2='';
		//echo $date2;
	}
	
	if($date1 == "1970-01-01"){
		//echo "salah";
		$_SESSION[err] 		= "please choose date";	
		header("Location:ExportToSAPNAB.php");
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
		
		$where_where = " where tc.id_cc = '$ID_CC' and tba.id_ba = '$ID_BA'
       and ta.id_afd = nvl (decode ('ALL', 'ALL', null, 
'ALL'), ta.id_afd)
       and tn.status_download = decode ('$TampilNAB', 'ALL', 
status_download, '$TampilNAB')
       and to_char (tn.tgl_nab, 'yyyy-mm-dd') between 
'$date1' and  nvl ('$date2','$date1')
		and thp.status_bcc = 'DELIVERED'";
		
		$where_view = " where tc.id_cc = '$ID_CC' and tba.id_ba = '$ID_BA'
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
				tba.id_estate,
				tba.PROFILE_NAME
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
		 tba.PROFILE_NAME,
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
   $where_where
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
       (F_GET_BJR (TB.ID_BLOK, THRP.TANGGAL_RENCANA, TDRP.ID_BA_AFD_BLOK)
						* F_GET_HASIL_PANEN_BUNCH(tba.id_ba, THP.NO_REKAP_BCC, THP.NO_BCC, 'BUNCH_SEND')) estimasi_berat,
	   F_GET_HASIL_PANEN_BUNCH(tba.id_ba, THP.NO_REKAP_BCC, THP.NO_BCC, 'BUNCH_SEND') TBS,
	   F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'BRD') BRD,
       tdrp.no_rekap_bcc,
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
  FROM t_header_rencana_panen thrp
       INNER JOIN t_detail_rencana_panen tdrp
          ON thrp.id_rencana = tdrp.id_rencana
       INNER JOIN t_hasil_panen thp
              ON TDRP.ID_RENCANA = THP.ID_RENCANA
               AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC
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
	  LEFT JOIN t_status_to_sap_ebcc tsap
		ON tba.id_cc = TSAP.COMP_CODE and
		tba.id_ba = TSAP.PLANT and
		thp.no_bcc = TSAP.NO_BCC
	LEFT JOIN t_status_to_sap_nab nsap
		ON tba.id_cc = nsap.COMP_CODE and
		tba.PROFILE_NAME = nsap.PROFILE_NAME and
		thp.no_bcc = nsap.NO_BCC and
		tn.no_nab = nsap.no_nab
   $where_view
			";
			
			
			
			
			$_SESSION["sql_Download_NAB"] = $sql_Download_NAB."ORDER BY tba.id_ba, ta.ID_AFD, tgl_nab, tn.no_nab";	
			$_SESSION["sql_Download_NABtxt"] = $sql_Download_NABtxt;	//ordernya ada di printTXT.php
			$_SESSION["sql_Download_NABview"] = $sql_Download_NABview;
			$_SESSION['tampilkan'] = 1;
			$_SESSION['Comp_Name'] = $_POST['Comp_Name'];
			$_SESSION['ID_BA2'] = $_POST['ID_BA2'];
			$_SESSION['date1'] = $date1;
			$_SESSION['date2'] = $date2;
			$_SESSION['valueAfdeling'] = $valueAfdeling;
			//echo $sql_Download_NAB;
			//echo $sql_Download_NABtxt; exit;
			//echo $sql_Download_NABview; die();
			header("Location:ExportToSAPNAB.php");
	}
}
else{
$_SESSION[err] = "Please choose the options";
//header("Location:DownloadSAPCH.php");
}
?>