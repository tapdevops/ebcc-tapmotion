<?php
session_start();

if(isset($_POST["valueAfd_select"]) || isset($_POST["NIKMandor_select"]) || isset($_POST["sdate1"]) || isset($_POST["sdate2"])){
	
	$valueAfdeling 		= $_POST["valueAfd_select"];
	$NIK_Mandor 		= $_POST["NIKMandor_select"];
	$date1 	= $_POST["sdate1"];
	$date2 	= $_POST["sdate2"];
	$ID_BA 		= $_SESSION['subID_BA_Afd'];
	$ID_CC 		= $_SESSION['subID_CC'];
	
	//echo "afdeling ". $valueAfdeling." mandor ". $NIK_Mandor. " id_ba ".$ID_BA . " id_cc ". $ID_CC . " date1 ". $date1 . " date2 ". $date1 ; exit;
	
	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();
	
	// RUN PROCEDURE EBCC COMPARE
	$user = $_SESSION['LoginName'];
	$user_pt = $_SESSION['subID_CC'];             
	$procedure = 'BEGIN MOBILE_INSPECTION.prc_tr_ebcc_compare_afd( :A, :B, :C, :D, :E, :F); END;';  
	$stmt = oci_parse($con,$procedure);     
	$date_procedure = date('d-M-Y',strtotime($date1));
	$werks = $_SESSION['subID_BA_Afd'];
	oci_bind_by_name($stmt,':A',$date_procedure);           
	oci_bind_by_name($stmt,':B',$date_procedure);           
	oci_bind_by_name($stmt,':C',$werks);           
	oci_bind_by_name($stmt,':D',$valueAfdeling);           
	oci_bind_by_name($stmt,':E',$NIK_Mandor);           
	oci_bind_by_name($stmt,':F',$user);  
	$cursor = oci_new_cursor($con);
	oci_execute($stmt);


	if(intval(str_replace('-', '', $date1))>=20201201)
	{
		$data_compare = " SELECT a.*,COALESCE(sap.export_status,'0') export_status FROM MOBILE_INSPECTION.tr_ebcc_compare a
	                      LEFT JOIN t_status_to_sap_ebcc sap ON sap.no_bcc = a.ebcc_no_bcc
	                      WHERE  
	                        ( 
	                         	val_jabatan_validator IN ('KEPALA KEBUN', 'KEPALA_KEBUN', 'ASISTEN KEPALA', 'ASISTEN_KEPALA', 'EM', 'SEM GM', 'SENIOR ESTATE MANAGER') 
	                    	OR 
	                         	val_jabatan_validator LIKE 'ASISTEN%' 
	                        )
	                      AND ebcc_nik_mandor = '$NIK_Mandor'
	                      AND val_afd_code = '$valueAfdeling'
	                      AND to_char(val_date_time,'YYYY-MM-DD') =  '$date1'
	                      AND akurasi_sampling_ebcc = 'MATCH'
	                      AND status_tph = 'ACTIVE'
	                      AND NVL (val_ebcc_code, 'x') NOT IN (SELECT NVL (val_ebcc_code, 'x') FROM MOBILE_INSPECTION.tr_validasi_detail) 
	                      AND (( val_sumber = 'MI' AND val_ebcc_code NOT LIKE 'M%' ) OR ( val_sumber = 'ME' )) ";
		$select_data_compare = oci_parse($con, $data_compare);
		oci_execute($select_data_compare, OCI_DEFAULT);
		while(($data = oci_fetch_array($select_data_compare, OCI_ASSOC))) 
		{
			//  IF DATA NOT EXPORTED TO SAP
			if($data['EXPORT_STATUS']!='X')
			{
	            $id_validasi = $data['EBCC_NIK_KERANI_BUAH'].'-'.$data['EBCC_NIK_MANDOR'].'-'.str_replace('-','',$date1);
	            $uuid = $data['EBCC_NO_BCC'].'-'.date('YmdHis');

				// INSERT LOG TO EBCC
				if((substr($data['VAL_EBCC_CODE'],0,1)=='V' && $data['VAL_SUMBER'] == 'MI') || $data['VAL_SUMBER'] == 'ME')
				{
					if($data['VAL_JABATAN_VALIDATOR']=='EM')
					{
						// CHECK DATA VALIDATION KABUN IN T_VALIDASI
						$check_kabun_validation = "SELECT * FROM T_VALIDASI 
						                           WHERE NO_BCC = '$data[EBCC_NO_BCC]' 
						                           AND ROLES IN('SEM GM',
				                                                'SENIOR ESTATE MANAGER')
				                                                ";
					}
					elseif($data['VAL_JABATAN_VALIDATOR']=='KEPALA KEBUN' || 
						   $data['VAL_JABATAN_VALIDATOR']=='KEPALA_KEBUN' || 
						   $data['VAL_JABATAN_VALIDATOR']=='ASISTEN_KEPALA' || 
						   $data['VAL_JABATAN_VALIDATOR']=='ASISTEN KEPALA')
					{
						// CHECK DATA VALIDATION KABUN IN T_VALIDASI
						$check_kabun_validation = "SELECT * FROM T_VALIDASI 
						                           WHERE NO_BCC = '$data[EBCC_NO_BCC]' 
						                           AND ROLES IN('EM',
				                                                'SEM GM',
				                                                'SENIOR ESTATE MANAGER')
				                                                ";
					}
					elseif(substr($data['VAL_JABATAN_VALIDATOR'],0,7)=='ASISTEN')
					{
						// CHECK DATA VALIDATION KABUN IN T_VALIDASI
						$check_kabun_validation = "SELECT * FROM T_VALIDASI 
						                           WHERE NO_BCC = '$data[EBCC_NO_BCC]' 
						                           AND ROLES IN('KEPALA KEBUN',
				                                                'KEPALA_KEBUN',
				                                                'ASISTEN KEPALA',
				                                                'ASISTEN_KEPALA',
				                                                'EM',
				                                                'SEM GM',
				                                                'SENIOR ESTATE MANAGER')
				                                                ";
					}
					$check_kabun = num_rows($con, $check_kabun_validation);

					// UPDATE BCC HASIL PANEN KUALITAS IF ROLE ABOVE IS NONE
					if($check_kabun==0)
					{       
						$data['VAL_JML_1'] = $data['VAL_JML_1']==null?0:$data['VAL_JML_1'];                               
						$data['VAL_JML_6'] = $data['VAL_JML_6']==null?0:$data['VAL_JML_6'];                               
						$data['VAL_JML_15'] = $data['VAL_JML_15']==null?0:$data['VAL_JML_15'];  
						$data['VAL_JML_4'] = $data['VAL_JML_4']==null?0:$data['VAL_JML_4'];  
						$data['VAL_JML_3'] = $data['VAL_JML_3']==null?0:$data['VAL_JML_3'];  
						                             
						// UPDATE QUANTITY MENTAH
						 $update_MENTAH = "UPDATE T_HASILPANEN_KUALTAS SET QTY = '$data[VAL_JML_1]' WHERE ID_BCC = '$data[EBCC_NO_BCC]' AND ID_KUALITAS = '1'";
						 update_data($con,$update_MENTAH);
						// UPDATE QUANTITY BUSUK
						 $update_BUSUK = "UPDATE T_HASILPANEN_KUALTAS SET QTY = '$data[VAL_JML_6]' WHERE ID_BCC = '$data[EBCC_NO_BCC]' AND ID_KUALITAS = '6'";
						 update_data($con,$update_BUSUK);
						// UPDATE QUANTITY JAJANG KOSONG
						 $update_KOSONG = "UPDATE T_HASILPANEN_KUALTAS SET QTY = '$data[VAL_JML_15]' WHERE ID_BCC = '$data[EBCC_NO_BCC]' AND ID_KUALITAS = '15'";
						 update_data($con,$update_KOSONG);
						// UPDATE QUANTITY OVERRIPE
						 $update_OVERRIPE = "UPDATE T_HASILPANEN_KUALTAS SET QTY = '$data[VAL_JML_4]' WHERE ID_BCC = '$data[EBCC_NO_BCC]' AND ID_KUALITAS = '4'";
						 update_data($con,$update_OVERRIPE);
						// UPDATE QUANTITY MASAK
						 $update_MASAK = "UPDATE T_HASILPANEN_KUALTAS SET QTY = '$data[VAL_JML_3]' WHERE ID_BCC = '$data[EBCC_NO_BCC]' AND ID_KUALITAS = '3'";
						 update_data($con,$update_MASAK);

						// INSERT TO MI VALIDATION_DETAIL
						$query_insert1 = "INSERT INTO MOBILE_INSPECTION.tr_validasi_detail 
										  (
											uuid,id_validasi,data_source,val_ebcc_code,tanggal_ebcc,nik_krani_buah,nama_krani_buah,nik_mandor,nama_mandor,ba_code,ba_name,afd_code,block_code,
											block_name,no_tph,no_bcc,jjg_ebcc_bm,jjg_ebcc_bk,jjg_ebcc_ms,jjg_ebcc_or,jjg_ebcc_bb,jjg_ebcc_jk,jjg_ebcc_ba,jjg_ebcc_total,jjg_ebcc_1,jjg_ebcc_2,
											jjg_validate_bm,jjg_validate_bk,jjg_validate_ms,jjg_validate_or,jjg_validate_bb,jjg_validate_jk,jjg_validate_ba,jjg_validate_total,jjg_validate_1,
											jjg_validate_2,kondisi_foto,insert_time,insert_user,insert_user_fullname,insert_user_userrole
										  )
										  VALUES 
										  (
											'$uuid',
											'$id_validasi',
											'$data[VAL_SUMBER]',
											'$data[VAL_EBCC_CODE]',
											'$data[VAL_DATE_TIME]',
											'$data[EBCC_NIK_KERANI_BUAH]',
											'$data[EBCC_NAMA_KERANI_BUAH]',
											'$data[EBCC_NIK_MANDOR]',
											'$data[EBCC_NAMA_MANDOR]',
											'$data[VAL_WERKS]',
											'$data[VAL_EST_NAME]',
											'$data[VAL_AFD_CODE]',
											'$data[VAL_BLOCK_CODE]',
											'$data[VAL_BLOCK_NAME]',
											'$data[VAL_TPH_CODE]',
											'$data[EBCC_NO_BCC]',
											'$data[EBCC_JML_BM]',
											0,
											'$data[EBCC_JML_MS]',
											'$data[EBCC_JML_OR]',
											'$data[EBCC_JML_BB]',
											'$data[EBCC_JML_JK]',
											0,
											'$data[EBCC_JJG_PANEN]',
											NULL,
											NULL,
											'$data[VAL_JML_1]',
											'$data[VAL_JML_2]',
											'$data[VAL_JML_3]',
											'$data[VAL_JML_4]',
											'$data[VAL_JML_6]',
											'$data[VAL_JML_15]',
											'$data[VAL_JML_16]',
											'$data[VAL_TOTAL_JJG]',
											NULL,
											NULL,
											NULL,
											sysdate,
											'$data[VAL_NIK_VALIDATOR]',
											'$data[VAL_NAMA_VALIDATOR]',
											'$data[VAL_JABATAN_VALIDATOR]'
										  )
										";
						oci_execute(oci_parse($con, $query_insert1), OCI_DEFAULT);
					
						$query_insert2 = "INSERT INTO T_VALIDASI 
										  ( TANGGAL_EBCC, NO_BCC, TANGGAL_VALIDASI, ROLES, NIK, NAMA, NIK_KRANI_BUAH, NIK_MANDOR )
										  VALUES 
										  ( '$data[VAL_DATE_TIME]',
										  	'$data[EBCC_NO_BCC]',
										  	sysdate,
										  	'$data[VAL_JABATAN_VALIDATOR]',
										  	'$data[VAL_NIK_VALIDATOR]',
										  	'$data[VAL_NAMA_VALIDATOR]',
										  	'$data[EBCC_NIK_KERANI_BUAH]',
										  	'$data[EBCC_NIK_MANDOR]'
										  ) ";
						insert_data($con, $query_insert2); 
					}  
				}   
			}
		}
	}

	$result_printdate  = select_data($con,"select to_char(SYSDATE,'DD/MM/YYYY') TGL from dual");
	$printdate = $result_printdate["TGL"];
	
	if($date1 == "0000-00-00")
	{
		//echo "salah";
		$_SESSION[err] 		= "please choose date". $date1. $date2;	
		header("Location:WelCetakLHMPanenFilter.php");
	}
	else
	{
		if($date2 == "0000-00-00"){
			$date2 = "";
		}
		//echo "benar";
		/*$sql_cetak_LHM_panen = "	
		  select tc.id_cc,
         tc.comp_name comp_name,
         tba.id_ba,
         tba.nama_ba nama_ba,
         ta.id_afd,
         thrp.tanggal_rencana tgl_panen,
         thrp.nik_mandor,
         f_get_empname (thrp.nik_mandor) nama_mandor,
         thrp.nik_pemanen,
         f_get_empname (thrp.nik_pemanen) nama_pemanen,
         thp.no_bcc,
         thp.no_tph,
         tb.id_blok,
         tdrp.luasan_panen,
         null jam_kerja,
         GHP.TBS2 tbs, GHP.BRD,  GHP.BM, GHP.BK, GHP.TP, GHP.BB,  0 jk, GHP.BT, GHP.BL, GHP.PB, GHP.AB, GHP.SF, GHP.BS,
         null kode_absen,
         null customer,
		 tb.blok_name,
		 tdrp.no_rekap_bcc,
		 thrp.id_rencana
       FROM t_header_rencana_panen thrp
         INNER JOIN t_detail_rencana_panen tdrp
            ON thrp.id_rencana = tdrp.id_rencana
         INNER JOIN t_hasil_panen thp
            ON tdrp.id_rencana = thp.id_rencana
               AND tdrp.no_rekap_bcc = thp.no_rekap_bcc
           INNER JOIN V_GET_HASIL_PANEN GHP
          ON THP.no_bcc = GHP.no_bcc
               AND tdrp.no_rekap_bcc = GHP.no_rekap_bcc
         INNER JOIN t_blok tb
            ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
         INNER JOIN t_afdeling ta
            ON tb.id_ba_afd = ta.id_ba_afd
         INNER JOIN t_bussinessarea tba
            ON tba.id_ba = ta.id_ba
         INNER JOIN t_companycode tc
            ON tba.id_cc = tc.id_cc
   where     tc.id_cc = '$ID_CC'
         and tba.id_ba = '$ID_BA'
         and ta.id_afd = nvl(decode('$valueAfdeling', 'ALL', null, '$valueAfdeling'), ta.id_afd)
         and thrp.nik_mandor = nvl(decode('$NIK_Mandor', 'ALL', null, '$NIK_Mandor'), thrp.nik_mandor)
		 and TO_CHAR (thrp.tanggal_rencana, 'YYYY-MM-DD') BETWEEN '$date1' and nvl ('$date2', '$date1')
order by   tgl_panen, thrp.nik_mandor,  nama_pemanen,  tdrp.no_rekap_bcc,  thrp.id_rencana, thp.no_bcc
			";*/
			
			//Edited by Ardo, 06-08-2016 : Synchronize BCC
			$sql_cetak_LHM_panen = "
				 select 
				 distinct tc.id_cc, 
				 ( SELECT count(val.no_bcc) FROM t_validasi val where val.no_bcc = thp.no_bcc ) as VALIDASI,
				 tc.comp_name comp_name,
				 tba.id_ba,
				 tba.nama_ba nama_ba,
				 ta.id_afd,
				 thrp.tanggal_rencana tgl_panen,
				 thrp.nik_mandor,
				 f_get_empname (thrp.nik_mandor) nama_mandor,
				 thrp.nik_pemanen,
				 f_get_idafd_nik(thrp.nik_pemanen) afd_pemanen,
				 f_get_empname (thrp.nik_pemanen) nama_pemanen,
				 thp.no_bcc,
				 thp.no_tph,
				 thp.kode_delivery_ticket,
				 tb.id_blok,
				 tdrp.luasan_panen,
				 null jam_kerja,
					 NVL (F_GET_HASIL_PANEN_BUNCH (tba.id_ba, thp.no_rekap_bcc, thp.no_bcc, 'BUNCH_HARVEST'), 0) as TBS,
					 NVL( F_GET_HASIL_PANEN_BRDX  (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc),0)  as BRD,
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 1),0)  as BM, 
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 2) ,0) as BK, 
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 7) ,0) as TP, 
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 6),0)  as BB, 
					 NVL (F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 15), 0) JK,
					 NVL( F_GET_HASIL_PANEN_NUMBERX ( thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 16),0)  as BA,					 
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 11),0)  as BT, 
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 12),0)  as BL, 
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 13) ,0) as PB,
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 10) ,0) as AB, 
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 14) ,0) as SF,               
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 8) ,0) as BS, 
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 3) ,0) as MS, 
					 NVL( F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 4) ,0) as ORR, 
				 null kode_absen,
				 CASE
					WHEN ta.ID_BA <> ta2.ID_BA THEN 'CINT_' || ta.ID_BA
					ELSE NULL
				 END
					customer,
				 tb.blok_name,
				 tdrp.no_rekap_bcc,
				 thrp.id_rencana
			   FROM t_header_rencana_panen thrp
				 INNER JOIN T_EMPLOYEE te
					ON thrp.NIK_PEMANEN = te.NIK
				 INNER JOIN T_AFDELING ta2
					ON te.ID_BA_AFD = ta2.ID_BA_AFD
				 INNER JOIN t_detail_rencana_panen tdrp
					ON thrp.id_rencana = tdrp.id_rencana
				 INNER JOIN t_hasil_panen thp
					ON tdrp.id_rencana = thp.id_rencana
					AND tdrp.no_rekap_bcc = thp.no_rekap_bcc
				 INNER JOIN t_blok tb
					ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
				 INNER JOIN t_afdeling ta
					ON tb.id_ba_afd = ta.id_ba_afd
				 INNER JOIN t_bussinessarea tba
					ON tba.id_ba = ta.id_ba
				 INNER JOIN t_companycode tc ON tba.id_cc = tc.id_cc
		   where     tc.id_cc = '$ID_CC'
				 and tba.id_ba = '$ID_BA'
				 and ta.id_afd = nvl(decode('$valueAfdeling', 'ALL', null, '$valueAfdeling'), ta.id_afd)
				-- and thrp.nik_mandor = nvl(decode('$NIK_Mandor', 'ALL', null, '$NIK_Mandor'), thrp.nik_mandor)
				 and TO_CHAR (thrp.tanggal_rencana, 'YYYY-MM-DD') BETWEEN '$date1' and nvl ('$date2', '$date1')
		order by tgl_panen,
         thrp.nik_mandor,
		 id_afd,
		 afd_pemanen,
         nama_pemanen,
		 id_blok,
		 luasan_panen desc,
		 NO_BCC,
         BM,
         BK,
         TP,
         BB,
         JK,
         BA,
         BT,
         BL,
         PB,
         AB,
         SF,
         BS";
			
			//echo $sql_cetak_LHM_panen; die();
			//order by tdrp.no_rekap_bcc, thrp.id_rencana, thrp.nik_mandor, tgl_panen, nama_pemanen, thp.no_bcc
			$_SESSION["sql_cetak_LHM_panen"] = $sql_cetak_LHM_panen;
			$_SESSION["printdate"] = $printdate;			
			$_SESSION["tgl1"] = $date1;
			$_SESSION["tgl2"] = $date2;
			$_SESSION["ID_BA"] = $ID_BA;
			$_SESSION["ID_CC"] = $ID_CC;
			$_SESSION["valueAfd"] = $valueAfdeling;
			$_SESSION["nikmandor"] = $NIK_Mandor;
			//echo $sql_cetak_LHM_panen; die ();
			header("Location:PDF_LHMPanen.php");
	}
}
else
{
	$_SESSION[err] = "Please choose the options";
	header("Location:WelCetakLHMPanenFilter.php");
}
?>