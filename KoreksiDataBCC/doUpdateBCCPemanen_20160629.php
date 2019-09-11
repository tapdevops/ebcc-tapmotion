<?php
	session_start();
	
	if(isset($_POST['NIK_Pemanen']) && isset($_POST['roweffec_BCC']) && isset($_POST['No_BCC']) && isset($_POST['ID_RENCANA']) && isset($_SESSION['NIK']) && isset($_SESSION['LoginName'])){
		$NIK_Pemanen	= $_POST['NIK_Pemanen'];
		$roweffec_BCC	= $_POST['roweffec_BCC'];
		$No_BCC			= $_POST['No_BCC'];
		$No_Rekap		= $_POST['NO_Rekap'];
		$ID_RENCANA		= $_POST['ID_RENCANA'];
		$oldrencana 	= $ID_RENCANA;
		$NIK 			= $_SESSION['NIK'];
		$Login_Name 	= $_SESSION['LoginName'];
		$tglPanenx 		= $_POST['datepicker'];
		$NIK_Mandor		= $_POST['NIK_Mandor'];
		
		$ID_blok		= $_POST['selectblok'];
		$cek_rekap		= date("ymd", strtotime($tglPanenx)).$ID_blok;
		
		$ba				= $_POST['ID_BAlabel'];
		$afd			= $_POST['AFDlabel'];
		$tglPanen 		= date("m-d-Y", strtotime($tglPanenx));
		$tglCek 		= strtoupper(date("d-M-y", strtotime($tglPanenx)));
		$id_bafd 		= $ba.$afd.$ID_blok;
		$id_bafd_old 	= $_POST['id_bafd_old'];
		
		
		
		if($NIK_Pemanen == "" || $roweffec_BCC == "" || $No_BCC	== ""){
			$_SESSION[err] = "Data to update not found!";
			header("location:KoreksiBCCFil.php");
		}
		else{
			include("../config/SQL_function.php");
			include("../config/db_connect.php");
			$con = connect();		
			
			for($x=0; $x < 16; $x++){
				$ID_BCC_KUALITAS[$x] = $_POST["ID_BCC_KUALITAS$x"];
				$ID_Kualitas[$x] = $_POST["ID_Kualitas$x"];
				//echo $ID_Kualitas[$x];
				$Qty[$x] = $_POST["NewQty$x"];
				$OldQty[$x] = $_POST["OldQty$x"];
				
				if($Qty[$x] == NULL){
					$Qty[$x] = 0;
				}			
				
				if(!is_numeric($Qty[$x])){
					$_SESSION["err$x"] = "Please input valid value on New Qty data ".$No_BCC;
					header("Location:KoreksiBCCList.php");
				} 
				else {
					
					//cek id rencana
					$query_id_rencana = "select ID_RENCANA from T_HEADER_RENCANA_PANEN
					where NIK_MANDOR = '" . $NIK_Mandor . "'
					and NIK_Pemanen = '" . $NIK_Pemanen . "' 
					and TANGGAL_RENCANA = to_date('" . $tglPanen . "','mm-dd-yyyy hh24:mi:ss')";
					
					$result_id_rencana = oci_parse($con, $query_id_rencana);
					oci_execute($result_id_rencana, OCI_DEFAULT);
					oci_fetch($result_id_rencana);
					$plan_ID = oci_result($result_id_rencana, "ID_RENCANA");
					//echo $query_id_rencana."<br><br>";
					//print_r("X=".$x." PLANID=".$plan_ID." ID_RENCANA = ".$ID_RENCANA." No_rekap ".$No_BCC." id_bafd ".$id_bafd."<br>");
					
					if($plan_ID != ""){
						if($x==0){
							
							
							$result_insertemp = 1;
							
							//cek apakah afd dan blok berubah
							$cek_blok_tdrp = "SELECT ID_BA_AFD_BLOK FROM T_DETAIL_RENCANA_PANEN
							WHERE ID_RENCANA = '" . $plan_ID . "'
							AND ID_BA_AFD_BLOK = '" . $id_bafd . "'
							";
							$result_blok_tdrp = oci_parse($con, $cek_blok_tdrp);
							oci_execute($result_blok_tdrp, OCI_DEFAULT);
							oci_fetch($result_blok_tdrp);
							$ID_BA_tdrp = oci_result($result_blok_tdrp, "ID_BA_AFD_BLOK");
							//echo $ID_BA_tdrp." ".$id_bafd;
							
							
							//jika ya, create new no_rekap_bcc
							if($ID_BA_tdrp!=$id_bafd){
								//create new rekap BCC
								
								$cek_jml_rekap = "SELECT CASE
											WHEN NO_REKAP_BCC < 10
											THEN
												CONCAT('0',NO_REKAP_BCC)
											ELSE
												TO_CHAR(NO_REKAP_BCC)
											END
												AS NO_REKAP_BCC
											FROM (
								SELECT substr(NO_REKAP_BCC,-1)+1 NO_REKAP_BCC, rown
								  FROM (SELECT a.*, ROWNUM rown
										  FROM (  SELECT *
													FROM t_detail_rencana_panen
												   WHERE ID_RENCANA =
															'".$plan_ID."'
														 AND id_ba_afd_blok = '".$id_bafd."'
												ORDER BY NO_REKAP_BCC DESC) a)
								 WHERE rown = 1
								 )
													";
								$result_jml_rekap = oci_parse($con, $cek_jml_rekap);
													oci_execute($result_jml_rekap, OCI_DEFAULT);
													oci_fetch($result_jml_rekap);
								$jmlblok_afd = oci_result($result_jml_rekap, "NO_REKAP_BCC");
								if($jmlblok_afd==""){ $jmlblok_afd = "01"; }
								
								$new_rekap = $cek_rekap.$jmlblok_afd;
								
								//INSERT NEW T_DETAIL_RENCANA_PANEN JIKA TIDAK ADA
								
								$cek_avail_detail_rencana_panen = "SELECT *
								FROM T_DETAIL_RENCANA_PANEN
								WHERE ID_RENCANA LIKE '" . $plan_ID . "'
								AND NO_REKAP_BCC = '" . $new_rekap . "'
								AND ID_BA_AFD_BLOK = '" . $id_bafd . "'";
								
								$result_cek_avail_detail_rencana_panen = oci_parse($con, $cek_avail_detail_rencana_panen);
								oci_execute($result_cek_avail_detail_rencana_panen, OCI_DEFAULT);
								oci_fetch($result_cek_avail_detail_rencana_panen);
								$cek_id_rencana_TDRP = oci_result($result_cek_avail_detail_rencana_panen, "ID_RENCANA");
								
								if($cek_id_rencana_TDRP == ""){
									$insert_new_detail_rencana_panen = "INSERT INTO T_DETAIL_RENCANA_PANEN (ID_BA_AFD_BLOK, NO_REKAP_BCC, ID_RENCANA, LUASAN_PANEN)
									VALUES ('" . $id_bafd . "', '" . $new_rekap . "', '" . $plan_ID . "', '0')";
									//echo $insert_new_detail_rencana_panen."<br><br>";
									$result_insert_new_detail_rencana_panen = num_rows($con, $insert_new_detail_rencana_panen);
									
									//Added by Ardo, 22-02-2016 : Insert into t_log_rencana_panen
									if($result_insert_new_detail_rencana_panen>0){
										$ins_log_rencana_panen = "INSERT INTO T_LOG_RENCANA_PANEN
										(INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_RENCANA, ON_NO_REKAP_BCC, NEW_VALUE_ID_BA_AFD_BLOK, OLD_VALUE_ID_BA_AFD_BLOK, CREEDIT_FROM, SYNC_SERVER)
										VALUES
										('INSERT', SYSDATE, '$NIK', '$Login_Name', 't_detail_rencana_panen', '".$plan_ID."', '".$new_rekap."', '".$id_bafd."', '', 'Website', SYSDATE)";
										$result_ins_log_rencana_panen =  oci_parse($con, $ins_log_rencana_panen);
										oci_execute($result_ins_log_rencana_panen, OCI_DEFAULT);
										//echo $ins_log_rencana_panen."<br><br>";
									}
								}
								
								
								//INSERT NEW ID_RENCANA T_HASIL_PANEN
								$query_thp_cek = "SELECT * FROM T_HASIL_PANEN 
								WHERE NO_BCC = '" . $No_BCC . "' 
								AND ID_RENCANA = '" . $plan_ID . "'
								AND NO_REKAP_BCC = '" . $No_rekap . "'";
								$result_thp_cek = oci_parse($con, $query_thp_cek);
								oci_execute($result_thp_cek, OCI_DEFAULT);
								oci_fetch($result_thp_cek);
								$cek_id_rencana_THP = oci_result($result_thp_cek, "ID_RENCANA");
								if ($cek_id_rencana_THP == ""){
									if($plan_ID==$ID_RENCANA){
										$query_ins_thp = "UPDATE T_HASIL_PANEN SET NO_REKAP_BCC = '".$new_rekap."' WHERE NO_REKAP_BCC = '".$No_Rekap."' AND NO_BCC = '".$No_BCC."' AND ID_RENCANA = '".$ID_RENCANA."'";
										$result_THP = num_rows($con, $query_ins_thp);
										
										if($result_THP>0){
											//Added by Ardo, 22-2-2016 : Insert into t_log_hasilpanen
											$ins_log_hasilpanen = "INSERT INTO T_LOG_HASIL_PANEN
												(INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_NO_BCC, ON_KODE_DELIVERY_TICKET, NEW_VALUE_STATUS_BCC, OLD_VALUE_STATUS_BCC, NEW_VALUE_NO_REKAP_BCC, OLD_VALUE_NO_REKAP_BCC, CREEDIT_FROM, SYNC_SERVER, NEW_VALUE_ID_RENCANA, OLD_VALUE_ID_RENCANA)
												VALUES
												('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_hasil_panen', '".$No_BCC."', 'NULL', 'NULL', 'NULL', '".$new_rekap."', '".$No_Rekap."', 'Website' , SYSDATE, '".$ID_RENCANA."', '".$ID_RENCANA."')";
											$result_ins_log_hasilpanen = oci_parse($con, $ins_log_hasilpanen);
											oci_execute($result_ins_log_hasilpanen, OCI_DEFAULT);
											//echo $ins_log_hasilpanen."<br><br>";
										}
									} else {
										$query_ins_thp = "INSERT INTO T_HASIL_PANEN
									  (ID_RENCANA, NO_REKAP_BCC, NO_TPH, NO_BCC, KODE_DELIVERY_TICKET, LATITUDE, LONGITUDE, PICTURE_NAME, STATUS_BCC, ID_NAB_TGL, IMAGE_FILE, UPDATE_TIME_CLOB)
									SELECT '".$plan_ID."', '".$new_rekap."', t.NO_TPH, t.NO_BCC, t.KODE_DELIVERY_TICKET, t.LATITUDE, t.LONGITUDE, t.PICTURE_NAME, t.STATUS_BCC, t.ID_NAB_TGL, t.IMAGE_FILE, t.UPDATE_TIME_CLOB
									  FROM T_HASIL_PANEN t
									 WHERE t.NO_REKAP_BCC = '".$No_Rekap."' AND t.NO_BCC = '".$No_BCC."' AND t.ID_RENCANA = '".$ID_RENCANA."'";
										$result_THP = num_rows($con, $query_ins_thp);
										//echo $query_ins_thp.";<br><br>";
										if($result_THP>0){
											if($new_rekap<>$No_Rekap or $plan_ID<>$ID_RENCANA){
											//Added by Ardo, 22-2-2016 : Insert into t_log_hasilpanen
											$ins_log_hasilpanen = "INSERT INTO T_LOG_HASIL_PANEN
												(INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_NO_BCC, ON_KODE_DELIVERY_TICKET, NEW_VALUE_STATUS_BCC, OLD_VALUE_STATUS_BCC, NEW_VALUE_NO_REKAP_BCC, OLD_VALUE_NO_REKAP_BCC, CREEDIT_FROM, SYNC_SERVER, NEW_VALUE_ID_RENCANA, OLD_VALUE_ID_RENCANA)
												VALUES
												('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_hasil_panen', '".$No_BCC."', 'NULL', 'NULL', 'NULL', '".$new_rekap."', '".$No_Rekap."', 'Website' , SYSDATE, '".$plan_ID."', '".$ID_RENCANA."')";
											$result_ins_log_hasilpanen = oci_parse($con, $ins_log_hasilpanen);
											oci_execute($result_ins_log_hasilpanen, OCI_DEFAULT);
											//echo $ins_log_hasilpanen."<br><br>";
											}
										}
									}
									
															
									
									
									if($result_THP > 0){
										
										//delete T_HASILPANEN_KUALTAS
										$query_del_thk_old = "DELETE FROM T_HASILPANEN_KUALTAS 
										WHERE ID_BCC = '" . $No_BCC . "' 
										AND ID_RENCANA = '" . $ID_RENCANA . "'";
										//echo $query_del_thk_old.";<br><br>";
										$r_del_thk = oci_parse($con, $query_del_thk_old);
										oci_execute($r_del_thk, OCI_DEFAULT);
										
										//delete old THP
										$sql_update_bcc_thp = "DELETE FROM T_HASIL_PANEN WHERE NO_REKAP_BCC = '" . $No_Rekap . "' 
										AND NO_BCC = '" . $No_BCC . "' 
										AND ID_RENCANA = '" . $ID_RENCANA . "'";
										//echo $sql_update_bcc_thp.";<br><br>";
										$r_update_tpanen = oci_parse($con, $sql_update_bcc_thp);
										oci_execute($r_update_tpanen, OCI_DEFAULT);
										
										//delete t_detail_rencana_panen jika di t_hasilpanen gak dipake
										$query_search_thp_avail = "SELECT * FROM T_HASIL_PANEN 
										WHERE NO_BCC <> '" . $No_BCC . "' 
										AND ID_RENCANA = '" . $ID_RENCANA . "'
										AND NO_REKAP_BCC = '" . $No_Rekap . "'";
										$result_search_thp_avail = oci_parse($con, $query_search_thp_avail);
										oci_execute($result_search_thp_avail, OCI_DEFAULT);
										oci_fetch($result_search_thp_avail);
										$cek_result_search_thp_avail = oci_result($result_search_thp_avail, "ID_RENCANA");
										if ($cek_result_search_thp_avail == 0){
											//delete t_detail_rencana_panen
											$query_del_tdrp_old = "DELETE FROM T_DETAIL_RENCANA_PANEN 
											WHERE NO_REKAP_BCC = '" . $No_Rekap . "' 
											AND ID_BA_AFD_BLOK = '" . $id_bafd_old . "'
											AND ID_RENCANA = '" . $ID_RENCANA . "'";
											//echo $query_del_tdrp_old.";<br><br>";
											$r_del_tdrp_old = oci_parse($con, $query_del_tdrp_old);
											oci_execute($r_del_tdrp_old, OCI_DEFAULT);
										}
										
										$roweffec_change_data = 1;
										
									}
								
									
									//echo "no_rekap = ".$new_rekap." result_detail = ".$result_insert_new_detail_rencana_panen." result_thp = ".$result_insert_TDRP."<br>";
									
									
									if($result_insert_TDRP > 0){
										$roweffec_change_data = 1;
									}
								} else {
									$roweffec_change_data = 1;
								}
							
							
							} else {
								//GET NO_REKAP_BCC from T_DETAIL_RENCANA_PANEN
								$get_no_rekap_bcc = "SELECT NO_REKAP_BCC FROM T_DETAIL_RENCANA_PANEN
								WHERE ID_RENCANA = '" . $plan_ID . "'
								AND ID_BA_AFD_BLOK = '" . $id_bafd . "'
								";
								$result_get_no_rekap_bcc = oci_parse($con, $get_no_rekap_bcc);
								oci_execute($result_get_no_rekap_bcc, OCI_DEFAULT);
								oci_fetch($result_get_no_rekap_bcc);
								$no_rekap_bcc_value = oci_result($result_get_no_rekap_bcc, "NO_REKAP_BCC");
								//echo $get_no_rekap_bcc." - ".$no_rekap_bcc_value."<br><br>";
								//INSERT NEW ID_RENCANA T_HASIL_PANEN
								$query_thp_cek = "SELECT * FROM T_HASIL_PANEN 
								WHERE NO_BCC = '" . $No_BCC . "' 
								AND ID_RENCANA = '" . $plan_ID . "'
								AND NO_REKAP_BCC = '" . $no_rekap_bcc_value . "'";
								$result_thp_cek = oci_parse($con, $query_thp_cek);
								oci_execute($result_thp_cek, OCI_DEFAULT);
								oci_fetch($result_thp_cek);
								$cek_id_rencana_THP = oci_result($result_thp_cek, "ID_RENCANA");
								if ($cek_id_rencana_THP == ""){
									if($plan_ID==$ID_RENCANA){
										$query_ins_thp = "UPDATE T_HASIL_PANEN SET NO_REKAP_BCC = '".$no_rekap_bcc_value."' WHERE NO_REKAP_BCC = '".$No_Rekap."' AND NO_BCC = '".$No_BCC."' AND ID_RENCANA = '".$ID_RENCANA."'";
										$result_THP = num_rows($con, $query_ins_thp);
										
										if($result_THP>0){
											//Added by Ardo, 22-2-2016 : Insert into t_log_hasilpanen
											$ins_log_hasilpanen = "INSERT INTO T_LOG_HASIL_PANEN
												(INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_NO_BCC, ON_KODE_DELIVERY_TICKET, NEW_VALUE_STATUS_BCC, OLD_VALUE_STATUS_BCC, NEW_VALUE_NO_REKAP_BCC, OLD_VALUE_NO_REKAP_BCC, CREEDIT_FROM, SYNC_SERVER, NEW_VALUE_ID_RENCANA, OLD_VALUE_ID_RENCANA)
												VALUES
												('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_hasil_panen', '".$No_BCC."', 'NULL', 'NULL', 'NULL', '".$no_rekap_bcc_value."', '".$No_Rekap."', 'Website' , SYSDATE, '".$ID_RENCANA."', '".$ID_RENCANA."')";
											$result_ins_log_hasilpanen = oci_parse($con, $ins_log_hasilpanen);
											oci_execute($result_ins_log_hasilpanen, OCI_DEFAULT);
											//echo $ins_log_hasilpanen."<br><br>";
										}
										
									} else {
										$query_ins_thp = "INSERT INTO T_HASIL_PANEN
										  (ID_RENCANA, NO_REKAP_BCC, NO_TPH, NO_BCC, KODE_DELIVERY_TICKET, LATITUDE, LONGITUDE, PICTURE_NAME, STATUS_BCC, ID_NAB_TGL, IMAGE_FILE, UPDATE_TIME_CLOB)
										SELECT '".$plan_ID."', '".$no_rekap_bcc_value."', t.NO_TPH, t.NO_BCC, t.KODE_DELIVERY_TICKET, t.LATITUDE, t.LONGITUDE, t.PICTURE_NAME, t.STATUS_BCC, t.ID_NAB_TGL, t.IMAGE_FILE, t.UPDATE_TIME_CLOB
										  FROM T_HASIL_PANEN t
										 WHERE t.NO_REKAP_BCC = '".$No_Rekap."' AND t.NO_BCC = '".$No_BCC."' AND t.ID_RENCANA = '".$ID_RENCANA."'";
										$result_THP = num_rows($con, $query_ins_thp);
										//echo $query_ins_thp.";<br><br>";
										if($result_THP>0){
											if($No_Rekap<>$no_rekap_bcc_value or $plan_ID<>$ID_RENCANA){
												//Edited by Ardo, 31-05-2016 : bug koreksi
												$ins_log_hasilpanen = "INSERT INTO T_LOG_HASIL_PANEN
													(INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_NO_BCC, ON_KODE_DELIVERY_TICKET, NEW_VALUE_STATUS_BCC, OLD_VALUE_STATUS_BCC, NEW_VALUE_NO_REKAP_BCC, OLD_VALUE_NO_REKAP_BCC, CREEDIT_FROM, SYNC_SERVER, NEW_VALUE_ID_RENCANA, OLD_VALUE_ID_RENCANA)
													VALUES
													('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_hasil_panen', '".$No_BCC."', 'NULL', 'NULL', 'NULL', '".$no_rekap_bcc_value."', '".$No_Rekap."', 'Website' , SYSDATE, '".$plan_ID."', '".$ID_RENCANA."')";
												$result_ins_log_hasilpanen = oci_parse($con, $ins_log_hasilpanen);
												oci_execute($result_ins_log_hasilpanen, OCI_DEFAULT);
												echo $ins_log_hasilpanen."<br><br>";
											}
										}
									
									}
									
															
									
									
									if($result_THP > 0){
										
										//delete T_HASILPANEN_KUALTAS
										$query_del_thk_old = "DELETE FROM T_HASILPANEN_KUALTAS 
										WHERE ID_BCC = '" . $No_BCC . "' 
										AND ID_RENCANA = '" . $ID_RENCANA . "'";
										//echo $query_del_thk_old.";<br><br>";
										$r_del_thk = oci_parse($con, $query_del_thk_old);
										oci_execute($r_del_thk, OCI_DEFAULT);
										
										//delete old THP
										$sql_update_bcc_thp = "DELETE FROM T_HASIL_PANEN WHERE NO_REKAP_BCC = '" . $No_Rekap . "' 
										AND NO_BCC = '" . $No_BCC . "' 
										AND ID_RENCANA = '" . $ID_RENCANA . "'";
										//echo $sql_update_bcc_thp.";<br><br>";
										$r_update_tpanen = oci_parse($con, $sql_update_bcc_thp);
										oci_execute($r_update_tpanen, OCI_DEFAULT);
										
										//delete t_detail_rencana_panen jika di t_hasilpanen gak dipake
										$query_search_thp_avail = "SELECT * FROM T_HASIL_PANEN 
										WHERE NO_BCC <> '" . $No_BCC . "' 
										AND ID_RENCANA = '" . $ID_RENCANA . "'
										AND NO_REKAP_BCC = '" . $No_Rekap . "'";
										$result_search_thp_avail = oci_parse($con, $query_search_thp_avail);
										oci_execute($result_search_thp_avail, OCI_DEFAULT);
										oci_fetch($result_search_thp_avail);
										$cek_result_search_thp_avail = oci_result($result_search_thp_avail, "ID_RENCANA");
										if ($cek_result_search_thp_avail == 0){
											//delete t_detail_rencana_panen
											$query_del_tdrp_old = "DELETE FROM T_DETAIL_RENCANA_PANEN 
											WHERE NO_REKAP_BCC = '" . $No_Rekap . "' 
											AND ID_BA_AFD_BLOK = '" . $id_bafd_old . "'
											AND ID_RENCANA = '" . $ID_RENCANA . "'";
											//echo $query_del_tdrp_old.";<br><br>";
											$r_del_tdrp_old = oci_parse($con, $query_del_tdrp_old);
											oci_execute($r_del_tdrp_old, OCI_DEFAULT);
										}
										
										$roweffec_change_data = 1;
										
									}
								
									
									//echo "no_rekap = ".$new_rekap." result_detail = ".$result_insert_new_detail_rencana_panen." result_thp = ".$result_insert_TDRP."<br>";
									
									
									if($result_insert_TDRP > 0){
										$roweffec_change_data = 1;
									}
								} else {
									$roweffec_change_data = 1;
								}
							}
							$ID_RENCANA = $plan_ID;
						}
						//print_r("x=".$x." ".$oldrencana." ".$ID_RENCANA."<br>");
						
					}
					else {
						
						if($x==0){
							$cek_nik_mandor = "select NIK_PEMANEN,NIK_MANDOR,TANGGAL_RENCANA from T_HEADER_RENCANA_PANEN
							where ID_RENCANA = '" . $oldrencana . "'";
							$result_nik_mandor = oci_parse($con, $cek_nik_mandor);
							oci_execute($result_nik_mandor, OCI_DEFAULT);
							oci_fetch($result_nik_mandor);
							$nik_mandorr = oci_result($result_nik_mandor, "NIK_MANDOR");
							$nik_pemanen = oci_result($result_nik_mandor, "NIK_PEMANEN");
							$tanggal_rencana = oci_result($result_nik_mandor, "TANGGAL_RENCANA");
							$log_tanggal = date("m-d-Y", strtotime($tanggal_rencana));
							
							
							if($nik_mandorr<>$NIK_Mandor){
								
								if($nik_pemanen<>$NIK_Pemanen or $tanggal_rencana<>$tglCek){
									
									$newdatePlan = date("Ymd", strtotime($tglPanenx));
									$new_id_rencana = $newdatePlan . ".MANUAL." . $NIK_Pemanen;
									if($x == 0){
										//INSERT NEW T_HEADER_RENCANA_PANEN
										$query_id_rencana = "select NIK_KERANI_BUAH from T_HEADER_RENCANA_PANEN where ID_RENCANA LIKE '%" . $ID_RENCANA . "%'";
										
										$result_id_rencana = oci_parse($con, $query_id_rencana);
										oci_execute($result_id_rencana, OCI_DEFAULT);
										oci_fetch($result_id_rencana);
										$nik_krani_buah = oci_result($result_id_rencana, "NIK_KERANI_BUAH");
									
										$query_insertemp = "INSERT INTO T_HEADER_RENCANA_PANEN (ID_RENCANA, TANGGAL_RENCANA, NIK_MANDOR, NIK_KERANI_BUAH, NIK_PEMANEN, STATUS_GANDENG)
										VALUES ('" . $new_id_rencana . "', to_date('" . $tglPanen . "','mm-dd-yyyy hh24:mi:ss'), '" . $NIK_Mandor . "', 
										'" . $nik_krani_buah . "', '" . $NIK_Pemanen . "', 'YES')";
										
										echo $query_insertemp."<br><br> ";
										
										$result_insertemp = num_rows($con, $query_insertemp);
										
										//Added by Ardo, 22-02-2016 : Insert into t_log_rencana_panen
										if($result_insertemp>0){
											if($nik_pemanen<>$NIK_Pemanen and $tanggal_rencana<>$tglCek){
												$field_change = ',NEW_VALUE_NIK_MANDOR, OLD_VALUE_NIK_MANDOR, NEW_VALUE_NIK_PEMANEN, OLD_VALUE_NIK_PEMANEN, NEW_VALUE_TANGGAL_RENCANA, OLD_VALUE_TANGGAL_RENCANA';
												$value_change = ",'".$NIK_Mandor."', '".$nik_mandorr."', '".$NIK_Pemanen."', '".$nik_pemanen."', to_date('" . $tglPanen . "','mm-dd-yyyy hh24:mi:ss'), to_date('" . $log_tanggal . "','mm-dd-yyyy hh24:mi:ss')";
											} else if($nik_pemanen<>$NIK_Pemanen and $tanggal_rencana==$tglCek){
												$field_change = ',NEW_VALUE_NIK_MANDOR, OLD_VALUE_NIK_MANDOR, NEW_VALUE_NIK_PEMANEN, OLD_VALUE_NIK_PEMANEN';
												$value_change = ",'".$NIK_Mandor."', '".$nik_mandorr."', '".$NIK_Pemanen."', '".$nik_pemanen."'";
											} else if($tanggal_rencana<>$tglCek and $nik_pemanen==$NIK_Pemanen){
												$field_change = ',NEW_VALUE_NIK_MANDOR, OLD_VALUE_NIK_MANDOR, NEW_VALUE_TANGGAL_RENCANA, OLD_VALUE_TANGGAL_RENCANA';
												$value_change = ",'".$NIK_Mandor."', '".$nik_mandorr."', to_date('" . $tglPanen . "','mm-dd-yyyy hh24:mi:ss'), to_date('" . $log_tanggal . "','mm-dd-yyyy hh24:mi:ss')";
											}
											
											$ins_log_header_rencana = "INSERT INTO T_LOG_RENCANA_PANEN
											(INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_RENCANA, ON_NO_REKAP_BCC, NEW_VALUE_ID_BA_AFD_BLOK, OLD_VALUE_ID_BA_AFD_BLOK, CREEDIT_FROM, SYNC_SERVER $field_change)
											VALUES
											('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_header_rencana_panen', '".$new_id_rencana."', 'NULL', 'NULL', 'NULL', 'Website', SYSDATE $value_change)";
											$result_ins_log_header_rencana =  oci_parse($con, $ins_log_header_rencana);
											oci_execute($result_ins_log_header_rencana, OCI_DEFAULT);
											echo $ins_log_header_rencana."<br><br>";
										}
										
										//INSERT NEW T_DETAIL_GANDENG
										$no_detail_gandeng = oci_parse($con, "select SEQ_DETAIL_GANDENG.nextval as next_id FROM dual");
										oci_execute($no_detail_gandeng, OCI_DEFAULT);
										oci_fetch($no_detail_gandeng);
										$id_gandeng = oci_result($no_detail_gandeng, "next_id");
										$query_ins_detail_gandeng = "INSERT INTO T_DETAIL_GANDENG(ID_GANDENG,ID_RENCANA,NIK_GANDENG) VALUES('".$id_gandeng."','".$new_id_rencana."','-')";
										
										echo $query_ins_detail_gandeng."<br><br>";
										
										//Added by Ardo, 22-02-2016 : Insert into t_detail_gandeng
										$jml_ins_detail_gandeng = num_rows($con, $query_ins_detail_gandeng);
										if($jml_ins_detail_gandeng>0){
											$ins_log_detail_gandeng = "INSERT INTO T_LOG_DETAIL_GANDENG
											(INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_GANDENG, CREEDIT_FROM, SYNC_SERVER)
											VALUES
											('INSERT', SYSDATE, '$NIK', '$Login_Name', 't_detail_gandeng', '".$id_gandeng."', 'Website', SYSDATE)";
											$result_ins_log_detail_gandeng =  oci_parse($con, $ins_log_detail_gandeng);
											oci_execute($result_ins_log_detail_gandeng, OCI_DEFAULT);
											echo $ins_log_detail_gandeng."<br><br>";
										}
									} else{
										
										$result_insertemp = 1;
									}
									
								} else {
									
									$new_id_rencana = $ID_RENCANA;
									$query_insertemp = "UPDATE T_HEADER_RENCANA_PANEN SET NIK_MANDOR = '".$NIK_Mandor."',
									NIK_PEMANEN = '".$NIK_Pemanen."', 
									TANGGAL_RENCANA = to_date('" . $tglPanen . "','mm-dd-yyyy hh24:mi:ss')
									WHERE ID_RENCANA = '".$new_id_rencana."'
									";
									
									echo $query_insertemp."<br><br> ";
									
									$result_insertemp = num_rows($con, $query_insertemp);
									
									//Added by Ardo, 22-02-2016 : Insert into t_log_rencana_panen
									if($result_insertemp>0){
										$field_change = ',NEW_VALUE_NIK_MANDOR, OLD_VALUE_NIK_MANDOR';
										$value_change = ",'".$NIK_Mandor."', '".$nik_mandorr."'";
										
										$ins_log_header_rencana = "INSERT INTO T_LOG_RENCANA_PANEN
										(INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_RENCANA, ON_NO_REKAP_BCC, NEW_VALUE_ID_BA_AFD_BLOK, OLD_VALUE_ID_BA_AFD_BLOK, CREEDIT_FROM, SYNC_SERVER $field_change)
										VALUES
										('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_header_rencana_panen', '".$new_id_rencana."', 'NULL', 'NULL', 'NULL', 'Website', SYSDATE $value_change)";
										$result_ins_log_header_rencana =  oci_parse($con, $ins_log_header_rencana);
										oci_execute($result_ins_log_header_rencana, OCI_DEFAULT);
										echo $ins_log_header_rencana."<br><br>";
									}
								}
								
								
								
							} else {
								$newdatePlan = date("Ymd", strtotime($tglPanenx));
								$new_id_rencana = $newdatePlan . ".MANUAL." . $NIK_Pemanen;
								
								if($x == 0){
									//INSERT NEW T_HEADER_RENCANA_PANEN
									$query_id_rencana = "select NIK_KERANI_BUAH from T_HEADER_RENCANA_PANEN where ID_RENCANA LIKE '%" . $ID_RENCANA . "%'";
									
									$result_id_rencana = oci_parse($con, $query_id_rencana);
									oci_execute($result_id_rencana, OCI_DEFAULT);
									oci_fetch($result_id_rencana);
									$nik_krani_buah = oci_result($result_id_rencana, "NIK_KERANI_BUAH");
								
									$query_insertemp = "INSERT INTO T_HEADER_RENCANA_PANEN (ID_RENCANA, TANGGAL_RENCANA, NIK_MANDOR, NIK_KERANI_BUAH, NIK_PEMANEN, STATUS_GANDENG)
									VALUES ('" . $new_id_rencana . "', to_date('" . $tglPanen . "','mm-dd-yyyy hh24:mi:ss'), '" . $NIK_Mandor . "', 
									'" . $nik_krani_buah . "', '" . $NIK_Pemanen . "', 'YES')";
									
									echo $query_insertemp.";<br><br>";
									
									$result_insertemp = num_rows($con, $query_insertemp);
									
									//Added by Ardo, 22-02-2016 : Insert into t_log_rencana_panen
									if($result_insertemp>0){
										
										if($nik_pemanen<>$NIK_Pemanen and $tanggal_rencana<>$tglCek){
											$field_change = ',NEW_VALUE_NIK_PEMANEN, OLD_VALUE_NIK_PEMANEN, NEW_VALUE_TANGGAL_RENCANA, OLD_VALUE_TANGGAL_RENCANA';
											$value_change = ",'".$NIK_Pemanen."', '".$nik_pemanen."', to_date('" . $tglPanen . "','mm-dd-yyyy hh24:mi:ss'), to_date('" . $log_tanggal . "','mm-dd-yyyy hh24:mi:ss')";
										} else if($nik_pemanen<>$NIK_Pemanen and $tanggal_rencana==$tglCek){
											$field_change = ',NEW_VALUE_NIK_PEMANEN, OLD_VALUE_NIK_PEMANEN';
											$value_change = ",'".$NIK_Pemanen."', '".$nik_pemanen."'";
										} else if($tanggal_rencana<>$tglCek and $nik_pemanen==$NIK_Pemanen){
											$field_change = ',NEW_VALUE_TANGGAL_RENCANA, OLD_VALUE_TANGGAL_RENCANA';
											$value_change = ",to_date('" . $tglPanen . "','mm-dd-yyyy hh24:mi:ss'), to_date('" . $log_tanggal . "','mm-dd-yyyy hh24:mi:ss')";
											
										}
										
										$ins_log_header_rencana = "INSERT INTO T_LOG_RENCANA_PANEN
										(INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_RENCANA, ON_NO_REKAP_BCC, NEW_VALUE_ID_BA_AFD_BLOK, OLD_VALUE_ID_BA_AFD_BLOK, CREEDIT_FROM, SYNC_SERVER $field_change)
										VALUES
										('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_header_rencana_panen', '".$new_id_rencana."', 'NULL', 'NULL', 'NULL', 'Website', SYSDATE $value_change)";
										$result_ins_log_header_rencana =  oci_parse($con, $ins_log_header_rencana);
										oci_execute($result_ins_log_header_rencana, OCI_DEFAULT);
										echo $ins_log_header_rencana."<br><br>";
									}
									
									//INSERT NEW T_DETAIL_GANDENG
									$no_detail_gandeng = oci_parse($con, "select SEQ_DETAIL_GANDENG.nextval as NEXT_ID FROM dual");
									oci_execute($no_detail_gandeng, OCI_DEFAULT);
									oci_fetch($no_detail_gandeng);
									$id_gandeng = oci_result($no_detail_gandeng, "NEXT_ID");
									$query_ins_detail_gandeng = "INSERT INTO T_DETAIL_GANDENG(ID_GANDENG,ID_RENCANA,NIK_GANDENG) VALUES('".$id_gandeng."','".$new_id_rencana."','-')";
								
									
									echo $query_ins_detail_gandeng."<br><br> ";
									
									//Added by Ardo, 22-02-2016 : Insert into t_detail_gandeng
									$jml_ins_detail_gandeng = num_rows($con, $query_ins_detail_gandeng);
									if($jml_ins_detail_gandeng>0){
										$ins_log_detail_gandeng = "INSERT INTO T_LOG_DETAIL_GANDENG
										(INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_GANDENG, CREEDIT_FROM, SYNC_SERVER)
										VALUES
										('INSERT', SYSDATE, '$NIK', '$Login_Name', 't_detail_gandeng', '".$id_gandeng."', 'Website', SYSDATE)";
										$result_ins_log_detail_gandeng =  oci_parse($con, $ins_log_detail_gandeng);
										oci_execute($result_ins_log_detail_gandeng, OCI_DEFAULT);
										echo $ins_log_detail_gandeng."<br><br>";
									}
									
								} else{
									
									$result_insertemp = 1;
								}
								
							}
							
							
							
							//cek apakah afd dan blok berubah
							$cek_blok_tdrp = "SELECT ID_BA_AFD_BLOK FROM T_DETAIL_RENCANA_PANEN
							WHERE ID_RENCANA = '" . $new_id_rencana . "'
							AND ID_BA_AFD_BLOK = '" . $id_bafd . "'
							";
							$result_blok_tdrp = oci_parse($con, $cek_blok_tdrp);
							oci_execute($result_blok_tdrp, OCI_DEFAULT);
							oci_fetch($result_blok_tdrp);
							$ID_BA_tdrp = oci_result($result_blok_tdrp, "ID_BA_AFD_BLOK");
							
							
							
							//jika ya, create new no_rekap_bcc
							if($ID_BA_tdrp!=$id_bafd){
								//create new rekap BCC
								$cek_jml_rekap = "SELECT CASE
											WHEN NO_REKAP_BCC < 10
											THEN
												CONCAT('0',NO_REKAP_BCC)
											ELSE
												TO_CHAR(NO_REKAP_BCC)
											END
												AS NO_REKAP_BCC
											FROM (
								SELECT substr(NO_REKAP_BCC,-1)+1 NO_REKAP_BCC, rown
								  FROM (SELECT a.*, ROWNUM rown
										  FROM (  SELECT *
													FROM t_detail_rencana_panen
												   WHERE ID_RENCANA =
															'".$new_id_rencana."'
														 AND id_ba_afd_blok = '".$id_bafd."'
												ORDER BY NO_REKAP_BCC DESC) a)
								 WHERE rown = 1
								 )
								";
								
								$result_jml_rekap = oci_parse($con, $cek_jml_rekap);
													oci_execute($result_jml_rekap, OCI_DEFAULT);
													oci_fetch($result_jml_rekap);
								$jmlblok_afd = oci_result($result_jml_rekap, "NO_REKAP_BCC");
								if($jmlblok_afd==""){ $jmlblok_afd = "01"; }
								
								$new_rekap = $cek_rekap.$jmlblok_afd;
								
								//INSERT NEW T_DETAIL_RENCANA_PANEN JIKA TIDAK ADA
								
								$cek_avail_detail_rencana_panen = "SELECT *
								FROM T_DETAIL_RENCANA_PANEN
								WHERE ID_RENCANA = '" . $new_id_rencana . "'
								AND NO_REKAP_BCC = '" . $new_rekap . "'
								AND ID_BA_AFD_BLOK = '" . $id_bafd . "'";
								
								$result_cek_avail_detail_rencana_panen = oci_parse($con, $cek_avail_detail_rencana_panen);
								oci_execute($result_cek_avail_detail_rencana_panen, OCI_DEFAULT);
								oci_fetch($result_cek_avail_detail_rencana_panen);
								$cek_id_rencana_TDRP = oci_result($result_cek_avail_detail_rencana_panen, "ID_RENCANA");
								
								if($cek_id_rencana_TDRP == ""){
									$insert_new_detail_rencana_panen = "INSERT INTO T_DETAIL_RENCANA_PANEN (ID_BA_AFD_BLOK, NO_REKAP_BCC, ID_RENCANA, LUASAN_PANEN)
									VALUES ('" . $id_bafd . "', '" . $new_rekap . "', '" . $new_id_rencana . "', '0')";
									$result_insert_new_detail_rencana_panen = num_rows($con, $insert_new_detail_rencana_panen);
									
									echo $insert_new_detail_rencana_panen."<br><br>";
									
									//Added by Ardo, 22-02-2016 : Insert into t_log_rencana_panen
									if($result_insert_new_detail_rencana_panen>0){
										$ins_log_rencana_panen = "INSERT INTO T_LOG_RENCANA_PANEN
										(INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_RENCANA, ON_NO_REKAP_BCC, NEW_VALUE_ID_BA_AFD_BLOK, OLD_VALUE_ID_BA_AFD_BLOK, CREEDIT_FROM, SYNC_SERVER)
										VALUES
										('INSERT', SYSDATE, '$NIK', '$Login_Name', 't_detail_rencana_panen', '".$new_id_rencana."', '".$new_rekap."', '".$id_bafd."', '', 'Website', SYSDATE)";
										$result_ins_log_rencana_panen =  oci_parse($con, $ins_log_rencana_panen);
										oci_execute($result_ins_log_rencana_panen, OCI_DEFAULT);
										echo $ins_log_rencana_panen."<br><br>";
									}
									
								}
								
								//INSERT NEW ID_RENCANA T_HASIL_PANEN
								$query_thp_cek = "SELECT * FROM T_HASIL_PANEN 
								WHERE NO_BCC = '" . $No_BCC . "' 
								AND ID_RENCANA = '" . $new_id_rencana . "'
								AND NO_REKAP_BCC = '" . $new_rekap . "'";
								$result_thp_cek = oci_parse($con, $query_thp_cek);
								oci_execute($result_thp_cek, OCI_DEFAULT);
								oci_fetch($result_thp_cek);
								$cek_id_rencana_THP = oci_result($result_thp_cek, "ID_RENCANA");
								if ($cek_id_rencana_THP == ""){
									
									//echo"oldrencana = ".$oldrencana." - new celana = ".$new_id_rencana."<br>";
									if($oldrencana==$new_id_rencana){
										$query_ins_thp = "UPDATE T_HASIL_PANEN SET NO_REKAP_BCC = '".$new_rekap."' WHERE NO_REKAP_BCC = '".$No_Rekap."' AND NO_BCC = '".$No_BCC."' AND ID_RENCANA = '".$oldrencana."'";
										$result_THP = num_rows($con, $query_ins_thp);
										if($result_THP>0){
											//Added by Ardo, 22-2-2016 : Insert into t_log_hasilpanen
											$ins_log_hasilpanen = "INSERT INTO T_LOG_HASIL_PANEN
												(INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_NO_BCC, ON_KODE_DELIVERY_TICKET, NEW_VALUE_STATUS_BCC, OLD_VALUE_STATUS_BCC, NEW_VALUE_NO_REKAP_BCC, OLD_VALUE_NO_REKAP_BCC, CREEDIT_FROM, SYNC_SERVER, NEW_VALUE_ID_RENCANA, OLD_VALUE_ID_RENCANA)
												VALUES
												('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_hasil_panen', '".$No_BCC."', 'NULL', 'NULL', 'NULL', '".$new_rekap."', '".$No_Rekap."', 'Website' , SYSDATE, '".$oldrencana."', '".$oldrencana."')";
											$result_ins_log_hasilpanen = oci_parse($con, $ins_log_hasilpanen);
											oci_execute($result_ins_log_hasilpanen, OCI_DEFAULT);
											echo $ins_log_hasilpanen."<br><br>";
										}
									} else {
										$query_ins_thp = "INSERT INTO T_HASIL_PANEN
			  (ID_RENCANA, NO_REKAP_BCC, NO_TPH, NO_BCC, KODE_DELIVERY_TICKET, LATITUDE, LONGITUDE, PICTURE_NAME, STATUS_BCC, ID_NAB_TGL, IMAGE_FILE, UPDATE_TIME_CLOB)
			SELECT '".$new_id_rencana."', '".$new_rekap."', t.NO_TPH, t.NO_BCC, t.KODE_DELIVERY_TICKET, t.LATITUDE, t.LONGITUDE, t.PICTURE_NAME, t.STATUS_BCC, t.ID_NAB_TGL, t.IMAGE_FILE, t.UPDATE_TIME_CLOB
			  FROM T_HASIL_PANEN t
			 WHERE t.NO_REKAP_BCC = '".$No_Rekap."' AND t.NO_BCC = '".$No_BCC."' AND t.ID_RENCANA = '".$oldrencana."'";
										echo "tess".$query_ins_thp.";<br><br>";
										$result_THP = num_rows($con, $query_ins_thp);
										echo "tess".$result_THP.";<br><br>";
										if($result_THP>0){
											if($new_rekap<>$No_Rekap or $new_id_rencana<>$oldrencana){
												//Added by Ardo, 22-2-2016 : Insert into t_log_hasilpanen
												$ins_log_hasilpanen = "INSERT INTO T_LOG_HASIL_PANEN
													(INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_NO_BCC, ON_KODE_DELIVERY_TICKET, NEW_VALUE_STATUS_BCC, OLD_VALUE_STATUS_BCC, NEW_VALUE_NO_REKAP_BCC, OLD_VALUE_NO_REKAP_BCC, CREEDIT_FROM, SYNC_SERVER, NEW_VALUE_ID_RENCANA, OLD_VALUE_ID_RENCANA)
													VALUES
													('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_hasil_panen', '".$No_BCC."', 'NULL', 'NULL', 'NULL', '".$new_rekap."', '".$No_Rekap."', 'Website' , SYSDATE, '".$new_id_rencana."', '".$oldrencana."')";
												$result_ins_log_hasilpanen = oci_parse($con, $ins_log_hasilpanen);
												oci_execute($result_ins_log_hasilpanen, OCI_DEFAULT);
												echo $ins_log_hasilpanen."<br><br>";
											}
										}
										
									}
									
									
									
									
									if($result_THP > 0){
										
										//delete T_HASILPANEN_KUALTAS
										$query_del_thk_old = "DELETE FROM T_HASILPANEN_KUALTAS 
										WHERE ID_BCC = '" . $No_BCC . "' 
										AND ID_RENCANA = '" . $oldrencana . "'";
										echo $query_del_thk_old.";<br><br>";
										$r_del_thk = oci_parse($con, $query_del_thk_old);
										oci_execute($r_del_thk, OCI_DEFAULT);
										
										//delete old THP
										$sql_update_bcc_thp = "DELETE FROM T_HASIL_PANEN WHERE NO_REKAP_BCC = '" . $No_Rekap . "' 
										AND NO_BCC = '" . $No_BCC . "' 
										AND ID_RENCANA = '" . $oldrencana . "'";
										echo $sql_update_bcc_thp.";<br><br>";
										$r_update_tpanen = oci_parse($con, $sql_update_bcc_thp);
										oci_execute($r_update_tpanen, OCI_DEFAULT);
										
										//delete t_detail_rencana_panen jika di t_hasilpanen gak dipake
										$query_search_thp_avail = "SELECT * FROM T_HASIL_PANEN 
										WHERE NO_BCC <> '" . $No_BCC . "' 
										AND ID_RENCANA = '" . $oldrencana . "'
										AND NO_REKAP_BCC = '" . $No_Rekap . "'";
										$result_search_thp_avail = oci_parse($con, $query_search_thp_avail);
										oci_execute($result_search_thp_avail, OCI_DEFAULT);
										oci_fetch($result_search_thp_avail);
										$cek_result_search_thp_avail = oci_result($result_search_thp_avail, "ID_RENCANA");
										if ($cek_result_search_thp_avail == 0){
											//delete t_detail_rencana_panen
											$query_del_tdrp_old = "DELETE FROM T_DETAIL_RENCANA_PANEN 
											WHERE NO_REKAP_BCC = '" . $No_Rekap . "' 
											AND ID_BA_AFD_BLOK = '" . $id_bafd_old . "'
											AND ID_RENCANA = '" . $oldrencana . "'";
											echo $query_del_tdrp_old.";<br><br>";
											$r_del_tdrp_old = oci_parse($con, $query_del_tdrp_old);
											oci_execute($r_del_tdrp_old, OCI_DEFAULT);
										}
										
										
										$roweffec_change_data = 1;
										
									}
								}
								else{
									$roweffec_change_data = 1;
									
								}
								
								
							} else {
								if($new_id_rencana<>$ID_RENCANA){
									//GET NO_REKAP_BCC from T_DETAIL_RENCANA_PANEN
									$get_no_rekap_bcc = "SELECT NO_REKAP_BCC FROM T_DETAIL_RENCANA_PANEN
									WHERE ID_RENCANA = '" . $plan_ID . "'
									AND ID_BA_AFD_BLOK = '" . $id_bafd . "'
									";
									$result_get_no_rekap_bcc = oci_parse($con, $get_no_rekap_bcc);
									oci_execute($result_get_no_rekap_bcc, OCI_DEFAULT);
									oci_fetch($result_get_no_rekap_bcc);
									$no_rekap_bcc_value = oci_result($result_get_no_rekap_bcc, "NO_REKAP_BCC");
									
									
									$cek_avail_detail_rencana_panen = "SELECT *
									FROM T_DETAIL_RENCANA_PANEN
									WHERE ID_RENCANA = '" . $new_id_rencana . "'
									AND NO_REKAP_BCC = '" . $no_rekap_bcc_value . "'
									AND ID_BA_AFD_BLOK = '" . $id_bafd . "'";
									
									$result_cek_avail_detail_rencana_panen = oci_parse($con, $cek_avail_detail_rencana_panen);
									oci_execute($result_cek_avail_detail_rencana_panen, OCI_DEFAULT);
									oci_fetch($result_cek_avail_detail_rencana_panen);
									$cek_id_rencana_TDRP = oci_result($result_cek_avail_detail_rencana_panen, "ID_RENCANA");
									
									if($cek_id_rencana_TDRP == ""){
										$insert_new_detail_rencana_panen = "INSERT INTO T_DETAIL_RENCANA_PANEN (ID_BA_AFD_BLOK, NO_REKAP_BCC, ID_RENCANA, LUASAN_PANEN)
										VALUES ('" . $id_bafd . "', '" . $no_rekap_bcc_value . "', '" . $new_id_rencana . "', '0')";
										echo $insert_new_detail_rencana_panen.";<br><br>";
										$result_insert_new_detail_rencana_panen = num_rows($con, $insert_new_detail_rencana_panen);
										
										//Added by Ardo, 22-02-2016 : Insert into t_log_rencana_panen
										if($result_insert_new_detail_rencana_panen>0){
											$ins_log_rencana_panen = "INSERT INTO T_LOG_RENCANA_PANEN
											(INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_RENCANA, ON_NO_REKAP_BCC, NEW_VALUE_ID_BA_AFD_BLOK, OLD_VALUE_ID_BA_AFD_BLOK, CREEDIT_FROM, SYNC_SERVER)
											VALUES
											('INSERT', SYSDATE, '$NIK', '$Login_Name', 't_detail_rencana_panen', '".$new_id_rencana."', '".$no_rekap_bcc_value."', '".$id_bafd."', '', 'Website', SYSDATE)";
											$result_ins_log_rencana_panen =  oci_parse($con, $ins_log_rencana_panen);
											oci_execute($result_ins_log_rencana_panen, OCI_DEFAULT);
											echo $ins_log_rencana_panen."<br><br>";
										}
										
									}
									
									//INSERT NEW ID_RENCANA T_HASIL_PANEN
									$query_thp_cek = "SELECT * FROM T_HASIL_PANEN 
									WHERE NO_BCC = '" . $No_BCC . "' 
									AND ID_RENCANA = '" . $new_id_rencana . "'";
									$result_thp_cek = oci_parse($con, $query_thp_cek);
									oci_execute($result_thp_cek, OCI_DEFAULT);
									oci_fetch($result_thp_cek);
									$cek_id_rencana_THP = oci_result($result_thp_cek, "ID_RENCANA");
									if ($cek_id_rencana_THP == ""){
										if($oldrencana==$new_id_rencana){
										$query_ins_thp = "UPDATE T_HASIL_PANEN SET NO_REKAP_BCC = '".$new_rekap."' WHERE NO_REKAP_BCC = '".$no_rekap_bcc_value."' AND NO_BCC = '".$No_BCC."' AND ID_RENCANA = '".$oldrencana."'";
										$result_THP = num_rows($con, $query_ins_thp);
										if($result_THP>0){
											if($new_rekap<>$no_rekap_bcc_value){
												//Added by Ardo, 22-2-2016 : Insert into t_log_hasilpanen
												$ins_log_hasilpanen = "INSERT INTO T_LOG_HASIL_PANEN
													(INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_NO_BCC, ON_KODE_DELIVERY_TICKET, NEW_VALUE_STATUS_BCC, OLD_VALUE_STATUS_BCC, NEW_VALUE_NO_REKAP_BCC, OLD_VALUE_NO_REKAP_BCC, CREEDIT_FROM, SYNC_SERVER, NEW_VALUE_ID_RENCANA, OLD_VALUE_ID_RENCANA)
													VALUES
													('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_hasil_panen', '".$No_BCC."', 'NULL', 'NULL', 'NULL', '".$new_rekap."', '".$no_rekap_bcc_value."', 'Website' , SYSDATE, '".$oldrencana."', '".$oldrencana."')";
												$result_ins_log_hasilpanen = oci_parse($con, $ins_log_hasilpanen);
												oci_execute($result_ins_log_hasilpanen, OCI_DEFAULT);
												echo $ins_log_hasilpanen."<br><br>";
											}
										}
										
										} else {
										$query_ins_thp = "INSERT INTO T_HASIL_PANEN
			  (ID_RENCANA, NO_REKAP_BCC, NO_TPH, NO_BCC, KODE_DELIVERY_TICKET, LATITUDE, LONGITUDE, PICTURE_NAME, STATUS_BCC, ID_NAB_TGL, IMAGE_FILE, UPDATE_TIME_CLOB)
			SELECT '".$new_id_rencana."', t.NO_REKAP_BCC, t.NO_TPH, t.NO_BCC, t.KODE_DELIVERY_TICKET, t.LATITUDE, t.LONGITUDE, t.PICTURE_NAME, t.STATUS_BCC, t.ID_NAB_TGL, t.IMAGE_FILE, t.UPDATE_TIME_CLOB
			  FROM T_HASIL_PANEN t
			 WHERE t.NO_REKAP_BCC = '".$No_Rekap."' AND t.NO_BCC = '".$No_BCC."' AND t.ID_RENCANA = '".$oldrencana."'";
										$result_THP = num_rows($con, $query_ins_thp);
										
										
										}
										echo $query_ins_thp.";<br><br>";
										
										
										
										if($result_THP > 0){
											
											//delete T_HASILPANEN_KUALTAS
											$query_del_thk_old = "DELETE FROM T_HASILPANEN_KUALTAS 
											WHERE ID_BCC = '" . $No_BCC . "' 
											AND ID_RENCANA = '" . $oldrencana . "'";
											echo $query_del_thk_old.";<br><br>";
											$r_del_thk = oci_parse($con, $query_del_thk_old);
											oci_execute($r_del_thk, OCI_DEFAULT);
											
											//delete old THP
											$sql_update_bcc_thp = "DELETE FROM T_HASIL_PANEN WHERE NO_REKAP_BCC = '" . $no_rekap_bcc_value . "' 
											AND NO_BCC = '" . $No_BCC . "' 
											AND ID_RENCANA = '" . $oldrencana . "'";
											//echo $sql_update_bcc_thp.";<br><br>";
											$r_update_tpanen = oci_parse($con, $sql_update_bcc_thp);
											oci_execute($r_update_tpanen, OCI_DEFAULT);
											
											//delete t_detail_rencana_panen jika di t_hasilpanen gak dipake
											$query_search_thp_avail = "SELECT * FROM T_HASIL_PANEN 
											WHERE NO_BCC <> '" . $No_BCC . "' 
											AND ID_RENCANA = '" . $oldrencana . "'
											AND NO_REKAP_BCC = '" . $No_Rekap . "'";
											$result_search_thp_avail = oci_parse($con, $query_search_thp_avail);
											oci_execute($result_search_thp_avail, OCI_DEFAULT);
											oci_fetch($result_search_thp_avail);
											$cek_result_search_thp_avail = oci_result($result_search_thp_avail, "ID_RENCANA");
											if ($cek_result_search_thp_avail == 0){
												//delete t_detail_rencana_panen
												$query_del_tdrp_old = "DELETE FROM T_DETAIL_RENCANA_PANEN 
												WHERE NO_REKAP_BCC = '" . $No_Rekap . "' 
												AND ID_BA_AFD_BLOK = '" . $id_bafd_old . "'
												AND ID_RENCANA = '" . $oldrencana . "'";
												echo $query_del_tdrp_old.";<br><br>";
												$r_del_tdrp_old = oci_parse($con, $query_del_tdrp_old);
												oci_execute($r_del_tdrp_old, OCI_DEFAULT);
											}
											
											$roweffec_change_data = 1;
											
										}
									}
									else{
										$result_THP = 1;
										
									}
									
									
								} else {
								
								
								
									$roweffec_change_data = 1;
								}
								
							}
							
							
						}
						
					}
					
					if ( $new_id_rencana != ''){
						$ID_RENCANA = $new_id_rencana;
					}
					//print_r("x=".$x." id_rencana = ".$ID_RENCANA."<br>");
					$sql_check = "select count (*) TTL from T_HASILPANEN_KUALTAS WHERE ID_BCC_Kualitas = '".$ID_BCC_KUALITAS[$x]."' AND ID_BCC = '".$No_BCC."' AND ID_RENCANA = '".$ID_RENCANA."'";
					$sql_checks = oci_parse($con, $sql_check);
					oci_execute($sql_checks, OCI_DEFAULT);
					oci_fetch($sql_checks);
					$roweffec_check = oci_result($sql_checks, "TTL");
					//print_r("x=".$x." ".$roweffec_check."<br>");
					if($roweffec_check > 0){
						$sql_value[$x] = "UPDATE t_hasilpanen_kualtas SET Qty = $Qty[$x] WHERE ID_BCC_Kualitas = '$ID_BCC_KUALITAS[$x]' AND ID_BCC = '$No_BCC' AND ID_RENCANA = '$ID_RENCANA'";
						$roweffec_value[$x] = num_rows($con,$sql_value[$x]);
						
						$sql_value_log_hasilpanen_kualitas = "INSERT INTO t_log_hasilpanen_kualitas 
						(InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_BCC_Kualitas, New_Value_Qty, Old_Value_Qty, CreEdit_From, Sync_Server)
						VALUES
						('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_hasilpanen_kualtas', '$ID_BCC_KUALITAS[$x]', '$Qty[$x]', '$OldQty[$x]', 'Website', SYSDATE)" ;
						$roweffec_value_log_hasilpanen_kualitas = num_rows($con,$sql_value_log_hasilpanen_kualitas);
					}
					else {
						$ID_BCC_Kualitas[$x] = $No_BCC.$ID_Kualitas[$x];
						$sql_value[$x]  = "INSERT INTO t_hasilpanen_kualtas 
						(ID_BCC_Kualitas, ID_BCC, ID_Kualitas, Qty, ID_RENCANA) 
						VALUES
						('$ID_BCC_Kualitas[$x]', '$No_BCC', '$ID_Kualitas[$x]', '$Qty[$x]', '$ID_RENCANA')";
						$roweffec_value[$x] = num_rows($con,$sql_value[$x]);
						
						/* print_r("x = ".$x."
							ID_BCC_Kualitas= ".$ID_BCC_Kualitas[$x]."
							ID_BCC= ".$No_BCC."
							ID_Kualitas= ".$ID_Kualitas[$x]."
							Qty= ".$Qty[$x]."
							ID_RENCANA= ".$new_id_rencana.
						"<br>"); */
						
						$sql_value_log_hasilpanen_kualitas = "INSERT INTO t_log_hasilpanen_kualitas 
						(InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_BCC_Kualitas, New_Value_Qty, Old_Value_Qty, CreEdit_From, Sync_Server)
						VALUES
						('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_hasilpanen_kualtas', '$ID_BCC_Kualitas[$x]', '$Qty[$x]', '$OldQty[$x]', 'Website', SYSDATE)" ;
						$roweffec_value_log_hasilpanen_kualitas = num_rows($con,$sql_value_log_hasilpanen_kualitas);
						
					}
					/* if($x==0 or $x==1){
						echo $sql_value[$x].";<br><br>";
					} */
					
					//print_r($sql_value[$x]);
					//print_r("x=".$x." - ".$roweffec_value[$x]." - ".$roweffec_value_log_hasilpanen_kualitas." - ".$roweffec_change_data."<br>");
					if($roweffec_value[$x] > 0 && $roweffec_value_log_hasilpanen_kualitas > 0 && $roweffec_change_data > 0){
						commit($con);
						//rollback($con);
						$_SESSION["err"] = "Data updated";
					}
					else{
						
						rollback($con);
						if($_SESSION["err"]!="Data updated"){
							$_SESSION["err"] = "Data not updated".$sql_value_pemanen[$x];
						}
					}
					
				}
			}
			
			header("Location:KoreksiBCCList.php");
		}
	}
	
	else{
		$_SESSION["err"] = "Please login";
		header("Location:../index.php");
	}
?>