<?php
if(isset($_REQUEST['id_rencana']) and isset($_REQUEST['no_bcc'])){
	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();

		session_start();
		// GET JANJANG
		$JJG_VALIDATE_OR = 0; 
		$JJG_VALIDATE_MS = 0; 
		$JJG_VALIDATE_JK = 0; 
		$JJG_VALIDATE_BM = 0; 
		$JJG_VALIDATE_BK = 0; 
		$JJG_VALIDATE_BB = 0;
		$JJG_VALIDATE_BA = 0; 
		$JJG_VALIDATE_2 = null; 
		$JJG_VALIDATE_1 = null; 
		$JJG_EBCC_OR = 0; 
		$JJG_EBCC_MS = 0; 
		$JJG_EBCC_JK = 0; 
		$JJG_EBCC_BM = 0; 
		$JJG_EBCC_BK = 0;
		$JJG_EBCC_BB = 0; 
		$JJG_EBCC_BA = 0; 
		$JJG_EBCC_2 = null; 
		$JJG_EBCC_1 = null;				
		$get_data_hasil_panen = "SELECT ID_KUALITAS, QTY
								 FROM T_HASILPANEN_KUALTAS 
								 WHERE ID_RENCANA = '".$_REQUEST['id_rencana']."' "; 
		$result_data_hasil_panen = oci_parse($con, $get_data_hasil_panen);
		oci_execute($result_data_hasil_panen, OCI_DEFAULT);
		while(oci_fetch($result_data_hasil_panen))
		{
			$ID_KUALITAS = oci_result($result_data_hasil_panen, "ID_KUALITAS");
			$QTY = oci_result($result_data_hasil_panen, "QTY");

			$JJG_VALIDATE_BM += $ID_KUALITAS==1?$QTY:0; 
			$JJG_EBCC_BM += $ID_KUALITAS==1?$QTY:0; 
			$JJG_VALIDATE_BK += $ID_KUALITAS==2?$QTY:0; 
			$JJG_EBCC_BK += $ID_KUALITAS==2?$QTY:0; 
			$JJG_VALIDATE_MS += $ID_KUALITAS==3?$QTY:0; 
			$JJG_EBCC_MS += $ID_KUALITAS==3?$QTY:0; 
			$JJG_VALIDATE_OR += $ID_KUALITAS==4?$QTY:0; 
			$JJG_EBCC_OR += $ID_KUALITAS==4?$QTY:0; 
			$JJG_VALIDATE_BB += $ID_KUALITAS==6?$QTY:0; 
			$JJG_EBCC_BB += $ID_KUALITAS==6?$QTY:0; 
			$JJG_VALIDATE_JK += $ID_KUALITAS==15?$QTY:0; 
			$JJG_EBCC_JK += $ID_KUALITAS==15?$QTY:0; 
			$JJG_VALIDATE_BA += $ID_KUALITAS==16?$QTY:0; 
			$JJG_EBCC_BA += $ID_KUALITAS==16?$QTY:0; 
		}
		// GET PANEN
		$get_data_validation = "SELECT 
									tanggal_rencana AS tanggal_ebcc, 
									no_bcc, 
									nik_kerani_buah, 
									nik_mandor,
									F_GET_EMPNAME (nik_kerani_buah) NAMA_KERANI_BUAH, 
									F_GET_EMPNAME (nik_mandor) NAMA_MANDOR, 
									F_GET_IDBA_IDR (a.ID_RENCANA,c.ID_BA_AFD_BLOK, a.NO_REKAP_BCC) AS BA_CODE, 
									F_GET_NAMABA_IDR(a.ID_RENCANA,c.ID_BA_AFD_BLOK) AS BA_NAME,
									F_GET_IDAFD_IDR (a.ID_RENCANA,c.ID_BA_AFD_BLOK) AS AFD, 
									F_GET_NAMABLOK_IDR(a.ID_RENCANA,c.ID_BA_AFD_BLOK) AS BLOK_NAME,
									c.ID_BA_AFD_BLOK,
									no_tph,
									picture_name,
									( SELECT COUNT(*) FROM t_validasi WHERE no_bcc = '20060115501001068011' )  count_ebcc, 
									a.ID_RENCANA
								FROM 
									t_hasil_panen a 
								INNER JOIN 
									T_HEADER_RENCANA_PANEN b 
								ON 
									a.ID_RENCANA = b.ID_RENCANA 
								INNER JOIN 
									T_DETAIL_RENCANA_PANEN c
								ON 
									c.ID_RENCANA = b.ID_RENCANA 
								WHERE 
									no_bcc = '".$_REQUEST['no_bcc']."' "; 
		$result_data_validation = oci_parse($con, $get_data_validation);
		oci_execute($result_data_validation, OCI_DEFAULT);
		while(oci_fetch($result_data_validation))
		{
			$COUNT_EBCC = oci_result($result_data_validation, "COUNT_EBCC");
			$NO_BCC = oci_result($result_data_validation, "NO_BCC");
			if($COUNT_EBCC==0 && strlen($NO_BCC)>0)
			{
				// INSERT TO EBCC.T_VALIDASI
				$TANGGAL_EBCC = oci_result($result_data_validation, "TANGGAL_EBCC");
				$NIK_KRANI_BUAH = oci_result($result_data_validation, "NIK_KERANI_BUAH");
				$NIK_MANDOR = oci_result($result_data_validation, "NIK_MANDOR");
				$NAMA_KRANI_BUAH = oci_result($result_data_validation, "NAMA_KERANI_BUAH");
				$ID_VALIDASI = $NIK_KRANI_BUAH.'-'.$NIK_MANDOR.'-'.date('Ymd',strtotime($TANGGAL_EBCC));
				$NAMA_MANDOR = oci_result($result_data_validation, "NAMA_MANDOR");
				$BA_CODE = oci_result($result_data_validation, "BA_CODE");
				$BA_NAME = oci_result($result_data_validation, "BA_NAME");
				$BLOK_NAME = oci_result($result_data_validation, "BLOK_NAME");
				$AFD = oci_result($result_data_validation, "AFD");
				$ID_BA_AFD_BLOK = oci_result($result_data_validation, "ID_BA_AFD_BLOK");
				$ID_BLOK = str_replace($BA_CODE.$AFD, '', $ID_BA_AFD_BLOK);
				$NO_TPH = oci_result($result_data_validation, "NO_TPH");
				$PICTURE_NAME = oci_result($result_data_validation, "PICTURE_NAME");
				$valid_query = "
					insert into t_validasi 
						(TANGGAL_EBCC,NO_BCC,NIK_KRANI_BUAH,NIK_MANDOR,TANGGAL_VALIDASI,NIK,NAMA,ROLES)
					values 
						('".$TANGGAL_EBCC."',
						 '".$NO_BCC."',
						 '".$NIK_KRANI_BUAH."',
						 '".$NIK_MANDOR."',
						 sysdate,
						 '".$_SESSION['NIK']."',
						 '".$_SESSION['Name']."',
						 'ASISTEN LAPANGAN') ";
				insert_data($con, $valid_query);
				
				// INSERT TO MOBILE_INSPECTION.TR_VALIDASI_DETAIL
				$JJG_VALIDATE_TOTAL = $JJG_VALIDATE_BM+$JJG_VALIDATE_BK+$JJG_VALIDATE_MS+$JJG_VALIDATE_OR+$JJG_VALIDATE_BB+$JJG_VALIDATE_JK+$JJG_VALIDATE_BA; 
				$JJG_EBCC_TOTAL = $JJG_VALIDATE_BM+$JJG_VALIDATE_BK+$JJG_VALIDATE_MS+$JJG_VALIDATE_OR+$JJG_VALIDATE_BB+$JJG_VALIDATE_JK+$JJG_VALIDATE_BA; 
				$valid_query2 = "
					insert into mobile_inspection.tr_validasi_detail  
						( 	VAL_EBCC_CODE, UUID, TANGGAL_EBCC, NO_TPH, NO_BCC, NIK_MANDOR, NIK_KRANI_BUAH, NAMA_MANDOR, NAMA_KRANI_BUAH, KONDISI_FOTO,
							JJG_VALIDATE_TOTAL, JJG_VALIDATE_OR, JJG_VALIDATE_MS, JJG_VALIDATE_JK, JJG_VALIDATE_BM, JJG_VALIDATE_BK, JJG_VALIDATE_BB,
							JJG_VALIDATE_BA, JJG_VALIDATE_2, JJG_VALIDATE_1, JJG_EBCC_TOTAL, JJG_EBCC_OR, JJG_EBCC_MS, JJG_EBCC_JK, JJG_EBCC_BM, JJG_EBCC_BK,
							JJG_EBCC_BB, JJG_EBCC_BA, JJG_EBCC_2, JJG_EBCC_1, INSERT_USER_USERROLE, INSERT_USER_FULLNAME, INSERT_USER, INSERT_TIME, 
							ID_VALIDASI, DATA_SOURCE, BLOCK_NAME, BLOCK_CODE, BA_NAME, BA_CODE, AFD_CODE
						)
					values 
						(
							'".$PICTURE_NAME."',null,'".$TANGGAL_EBCC."','".$NO_TPH."','".$NO_BCC."','".$NIK_MANDOR."','".$NIK_KRANI_BUAH."',
							'".$NAMA_MANDOR."','".$NAMA_KRANI_BUAH."',null,'".$JJG_VALIDATE_TOTAL."', '".$JJG_VALIDATE_OR."', '".$JJG_VALIDATE_MS."', 
							'".$JJG_VALIDATE_JK."', '".$JJG_VALIDATE_BM."', '".$JJG_VALIDATE_BK."', '".$JJG_VALIDATE_BB."','".$JJG_VALIDATE_BA."',
							'".$JJG_VALIDATE_2."', '".$JJG_VALIDATE_1."', '".$JJG_EBCC_TOTAL."', '".$JJG_EBCC_OR."', '".$JJG_EBCC_MS."', '".$JJG_EBCC_JK."', 
							'".$JJG_EBCC_BM."', '".$JJG_EBCC_BK."', '".$JJG_EBCC_BB."', '".$JJG_EBCC_BA."', '".$JJG_EBCC_2."', '".$JJG_EBCC_1."',
							'ASISTEN LAPANGAN','".$_SESSION['Name']."','".$_SESSION['Name']."',sysdate,'".$ID_VALIDASI."','EBCC','".$BLOK_NAME."','".$ID_BLOK."',
							'".$BA_NAME."','".$BA_CODE."','".$AFD."'
						)";
				insert_data($con, $valid_query2);
			}
		}

	$select = "
	select validasi_bcc, validasi_date from t_hasil_panen WHERE ID_RENCANA = '".$_REQUEST['id_rencana']."' AND NO_BCC = '".$_REQUEST['no_bcc']."' AND VALIDASI_BCC is null
	";
	$stid1 = oci_parse($con, $select);
	oci_execute($stid1);
	$count = oci_fetch_all($stid1, $rec, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
	if($count==1){
		$valid_query = "
		update t_hasil_panen set validasi_bcc = 'X', validasi_date = to_date('".date('m-d-Y H:i:s')."', 'MM-DD-YYYY HH24:MI:SS') WHERE ID_RENCANA = '".$_REQUEST['id_rencana']."' AND NO_BCC = '".$_REQUEST['no_bcc']."'
		";
		
		$result_THP = num_rows($con, $valid_query);
		//echo $result_THP; exit;
		if($result_THP==1){
			echo "success";
			commit($con);
		} else {
			echo "fail";
			rollback($con);
		}
	}
}
?>