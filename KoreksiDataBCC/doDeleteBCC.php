<?php
	session_start();
	
	
	if(isset($_SESSION['NIK']) && isset($_SESSION['LoginName'])){
		if(isset($_REQUEST['ID_RENCANA']) && isset($_REQUEST['NO_REKAP_BCC']) && isset($_REQUEST['NO_BCC'])
		&& isset($_REQUEST['nomor_ba']) && isset($_REQUEST['tanggal_ba']) && isset($_REQUEST['alasan'])){
			include("../config/SQL_function.php");
			include("../config/db_connect.php");
			$con = connect();
			
			$ID_RENCANA = $_REQUEST['ID_RENCANA'];
			$NO_REKAP_BCC = $_REQUEST['NO_REKAP_BCC'];
			$NO_BCC = $_REQUEST['NO_BCC'];
			$CC = $_REQUEST['CC'];
			$PROFILE_NAME = $_REQUEST['PROFILE_NAME'];
			$nomor_ba = $_REQUEST['nomor_ba'];
			$tanggal_ba = $_REQUEST['tanggal_ba'];
			$alasan = $_REQUEST['alasan'];
			
			////echo $ID_RENCANA." ".$NO_REKAP_BCC." ".$NO_BCC." ".$nomor_ba." ".$tanggal_ba." ".$alasan; exit;
			
			//T_HEADER_RENCANA_PANEN
			$query_ins_thrp = "INSERT INTO DEL_T_HEADER_RENCANA_PANEN
			  (ID_RENCANA, TANGGAL_RENCANA, NO_LHM, NIK_MANDOR, NIK_KERANI_BUAH, NIK_PEMANEN, STATUS_GANDENG)
			SELECT t.ID_RENCANA, t.TANGGAL_RENCANA, t.NO_LHM, t.NIK_MANDOR, t.NIK_KERANI_BUAH, t.NIK_PEMANEN, t.STATUS_GANDENG
			  FROM T_HEADER_RENCANA_PANEN t
			 WHERE t.ID_RENCANA = '".$ID_RENCANA."'";
			// echo $query_ins_thrp."<br><br>";
			$parse_thrp = oci_parse($con, $query_ins_thrp);
			$r_del_thrp = oci_execute($parse_thrp, OCI_DEFAULT);
			
				$success_thrp = 1;
				//T_DETAIL_RENCANA_PANEN
				$query_ins_tdrp = "INSERT INTO DEL_T_DETAIL_RENCANA_PANEN
				  (ID_BA_AFD_BLOK, NO_REKAP_BCC, ID_RENCANA, LUASAN_PANEN)
				SELECT t.ID_BA_AFD_BLOK, t.NO_REKAP_BCC, t.ID_RENCANA, t.LUASAN_PANEN
				  FROM T_DETAIL_RENCANA_PANEN t
				 WHERE t.ID_RENCANA = '".$ID_RENCANA."' and t.NO_REKAP_BCC = '".$NO_REKAP_BCC."'";
				 //echo $query_ins_tdrp."<br><br>";
				$parse_tdrp = oci_parse($con, $query_ins_tdrp);
				$r_del_tdrp = oci_execute($parse_tdrp, OCI_DEFAULT);
				
					
					//T_HASIL_PANEN
					$query_ins_thp = "INSERT INTO DEL_T_HASIL_PANEN
						(ID_RENCANA, NO_REKAP_BCC, NO_TPH, NO_BCC, KODE_DELIVERY_TICKET, LATITUDE, LONGITUDE, PICTURE_NAME, STATUS_BCC, ID_NAB_TGL, UPDATE_TIME_CLOB, CETAK_BCC, CETAK_DATE, VALIDASI_BCC, VALIDASI_DATE, NOMOR_BA, TANGGAL_BA, ALASAN, TANGGAL_DELETE)
					SELECT t.ID_RENCANA, t.NO_REKAP_BCC, t.NO_TPH, t.NO_BCC, t.KODE_DELIVERY_TICKET, t.LATITUDE, t.LONGITUDE, t.PICTURE_NAME, t.STATUS_BCC, t.ID_NAB_TGL, t.UPDATE_TIME_CLOB, t.CETAK_BCC, t.CETAK_DATE, t.VALIDASI_BCC, t.VALIDASI_DATE, '".$nomor_ba."', to_date('".$tanggal_ba."', 'MM-DD-YYYY HH24:MI:SS'), '".$alasan."', to_date('".date('m-d-Y H:i:s')."', 'MM-DD-YYYY HH24:MI:SS')
					  FROM T_HASIL_PANEN t
					 WHERE t.NO_REKAP_BCC = '".$NO_REKAP_BCC."' AND t.NO_BCC = '".$NO_BCC."' AND t.ID_RENCANA = '".$ID_RENCANA."'";
					//echo $query_ins_thp."<br><br>"; 
					
					$result_THP = num_rows($con, $query_ins_thp);
					$result_THP = 1;
					if($result_THP==1){
						//T_HASILPANEN_KUALTAS
						$query_ins_thpk = "INSERT INTO DEL_T_HASILPANEN_KUALTAS
						(ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
						SELECT t.ID_BCC_KUALITAS, t.ID_BCC, t.ID_KUALITAS, t.QTY, t.ID_RENCANA
						  FROM T_HASILPANEN_KUALTAS t
						 WHERE t.ID_BCC = '".$NO_BCC."' AND t.ID_RENCANA = '".$ID_RENCANA."'";
						//echo $query_ins_thpk."<br><br>";
						
						$result_THPK = num_rows($con, $query_ins_thpk);
						$result_THPK = 1;
						if($result_THPK>0){
							//DELETE - T_HASILPANEN_KUALTAS
							$query_del_thpk = "DELETE FROM T_HASILPANEN_KUALTAS 
							WHERE ID_BCC = '" . $NO_BCC . "' 
							AND ID_RENCANA = '" . $ID_RENCANA . "'";
							//echo $query_del_thpk."<br><br>";
							
							$parse_thpk = oci_parse($con, $query_del_thpk);
							$r_del_thpk = oci_execute($parse_thpk, OCI_DEFAULT);
							if (!$r_del_thpk) {
								$success_thpk = 0;
								rollback($con);
								$_SESSION["err"] = "Failed to Delete BCC, Err Log 4";
								header("Location:KoreksiBCCFil.php");
							} else {
								//DELETE - T_HASIL_PANEN
								$success_thpk = 1;
								$query_del_thp = "DELETE FROM T_HASIL_PANEN 
								WHERE NO_REKAP_BCC = '" . $NO_REKAP_BCC . "' 
								AND ID_RENCANA = '" . $ID_RENCANA . "'
								AND NO_BCC = '" . $NO_BCC . "'
								";
								//echo $query_del_thp."<br><br>";
								
								$parse_thp = oci_parse($con, $query_del_thp);
								$r_del_thp = oci_execute($parse_thp, OCI_DEFAULT);
								if (!$r_del_thp) {
									
									
									$success_thp = 0;
									rollback($con);
									$_SESSION["err"] = "Failed to Delete BCC, Err Log 3";
									header("Location:KoreksiBCCFil.php");
								} else {
								
									$success_thp = 1;
									
									//DELETE - T_DETAIL_RENCANA_PANEN
									$query_search_thp_avail = "SELECT * FROM T_HASIL_PANEN 
									WHERE NO_BCC <> '" . $NO_BCC . "' 
									AND ID_RENCANA = '" . $ID_RENCANA . "'
									AND NO_REKAP_BCC = '" . $NO_REKAP_BCC . "'";
									$result_search_thp_avail = oci_parse($con, $query_search_thp_avail);
									oci_execute($result_search_thp_avail, OCI_DEFAULT);
									oci_fetch($result_search_thp_avail);
									$cek_result_search_thp_avail = oci_result($result_search_thp_avail, "ID_RENCANA");
									//echo $cek_result_search_thp_avail;
									if ($cek_result_search_thp_avail == ''){
										//delete t_detail_rencana_panen
										$query_del_tdrp = "DELETE FROM T_DETAIL_RENCANA_PANEN 
										WHERE NO_REKAP_BCC = '" . $NO_REKAP_BCC . "' 
										AND ID_RENCANA = '" . $ID_RENCANA . "'";
										//echo $query_del_tdrp."<br><br>";
										
										$parse_tdrp = oci_parse($con, $query_del_tdrp);
										$r_del_tdrp = oci_execute($parse_tdrp, OCI_DEFAULT);
										if (!$r_del_tdrp) {
											
											$success_tdrp = 0;
											rollback($con);
											$_SESSION["err"] = "Failed to Delete BCC, Err Log 2";
											header("Location:KoreksiBCCFil.php");
										} else {
											$success_tdrp = 1;
												
												
										}	
									} else {
										$success_tdrp = 1;
									}
									
								}
								
								
							}
							
						} else {
							rollback($con);
							$_SESSION["err"] = "Failed to Delete BCC, Err ins 4";
							header("Location:KoreksiBCCFil.php");
						}
					} else {
						rollback($con);
						$_SESSION["err"] = "Failed to Delete BCC, Err ins 3";
						header("Location:KoreksiBCCFil.php");
					}
					
					
				
			
			
		} else {
			rollback($con);
			$_SESSION["err"] = "Failed to Delete BCC, choose the option";
			header("Location:KoreksiBCCFil.php");
		}
		
		//exit;
		//echo $success_thrp." ".$success_tdrp." ".$success_thp." ".$success_thpk; die();
		if($success_thrp==1 && $success_tdrp==1 && $success_thp==1 && $success_thpk>0){
			//Added by Ardo, 21-09-2016
			//Delete t_status_to_sap_ebcc
			$query_del_tstsapebcc = "DELETE FROM T_STATUS_TO_SAP_EBCC 
							WHERE COMP_CODE = '" . $CC . "' 
							AND PROFILE_NAME = '" . $PROFILE_NAME . "'	
							AND NO_BCC = '" . $NO_BCC . "'";
			$parse_tstsapebcc = oci_parse($con, $query_del_tstsapebcc);
			$r_del_tstsapebcc = oci_execute($parse_tstsapebcc, OCI_DEFAULT);
			
			//Delete t_status_to_sap_pinalty
			$query_del_tstsapinalty = "DELETE FROM T_STATUS_TO_SAP_DENDA_PANEN 
							WHERE COMP_CODE = '" . $CC . "' 
							AND PROFILE_NAME = '" . $PROFILE_NAME . "'	
							AND NO_BCC = '" . $NO_BCC . "'";
			$parse_tstsapinalty = oci_parse($con, $query_del_tstsapinalty);
			$r_del_tstsapinalty = oci_execute($parse_tstsapinalty, OCI_DEFAULT);
			
			//echo "BBB";die();
			commit($con);
			$_SESSION["err"] = "Success Delete BCC";
			header("Location:KoreksiBCCFil.php");
		} else {
			rollback($con);
			$_SESSION["err"] = "Failed to Delete BCC";
			header("Location:KoreksiBCCFil.php");
		}
		
	} else {
		$_SESSION["err"] = "Please login";
		header("Location:../index.php");
	}
?>