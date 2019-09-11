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
		$ba				= $_POST['ID_BAlabel'];
		$afd			= $_POST['AFDlabel'];
		$tglPanen 		= date("m-d-Y", strtotime($tglPanenx));
		
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
					//echo $query_id_rencana;
					//print_r("X=".$x." PLANID=".$plan_ID." ID_RENCANA = ".$ID_RENCANA."<br>");
					
					if($plan_ID != ""){
						if($x==0){
							$query_insertemp = "UPDATE T_HEADER_RENCANA_PANEN SET NIK_MANDOR = '".$NIK_Mandor."',
							NIK_PEMANEN = '".$NIK_Pemanen."', 
							TANGGAL_RENCANA = to_date('" . $tglPanen . "','mm-dd-yyyy hh24:mi:ss')
							WHERE ID_RENCANA = '".$ID_RENCANA."'
							";
							
							$result_insertemp = num_rows($con, $query_insertemp);
							
							$cek_blok_tdrp = "SELECT ID_BA_AFD_BLOK FROM T_DETAIL_RENCANA_PANEN
							WHERE ID_RENCANA = '" . $ID_RENCANA . "'
							AND NO_REKAP_BCC = '" . $No_Rekap . "'
							";
							$result_blok_tdrp = oci_parse($con, $cek_blok_tdrp);
							oci_execute($result_blok_tdrp, OCI_DEFAULT);
							oci_fetch($result_blok_tdrp);
							$ID_BA_tdrp = oci_result($result_blok_tdrp, "ID_BA_AFD_BLOK");
							$id_ba_afd_blok_cek = $ba . $afd . $ID_blok;
							if($ID_BA_tdrp!=$id_ba_afd_blok_cek){
								$update_blok_tdrp = "UPDATE T_DETAIL_RENCANA_PANEN SET ID_BA_AFD_BLOK = '".$id_ba_afd_blok_cek."'
								WHERE ID_RENCANA = '" . $ID_RENCANA . "'
								AND NO_REKAP_BCC = '" . $No_Rekap . "'
								";
								$result_insert_TDRP = num_rows($con, $update_blok_tdrp);
								if($result_insert_TDRP > 0){
									$roweffec_change_data = 1;
								}
							} else {
								$roweffec_change_data = 1;
							}
							
							
						}
						
						//print_r("x=".$x." ".$oldrencana." ".$ID_RENCANA."<br>");
						if($x==1 AND $oldrencana!=$ID_RENCANA){
							$query_del_thk_old = "DELETE FROM T_HASILPANEN_KUALTAS 
							WHERE ID_BCC = '" . $No_BCC . "' 
							AND ID_RENCANA = '" . $oldrencana . "'";
							//echo $query_del_thk_old;
							$r_del_thk = oci_parse($con, $query_del_thk_old);
							$exe_del_thk = oci_execute($r_del_thk);
							
							$sql_update_bcc_thp = "DELETE FROM T_HASIL_PANEN WHERE NO_REKAP_BCC = '" . $No_Rekap . "' 
							AND NO_BCC = '" . $No_BCC . "' 
							AND ID_RENCANA = '" . $oldrencana . "'";
							//echo $sql_update_bcc_thp;
							$r_update_tpanen = oci_parse($con, $sql_update_bcc_thp);
							$r_exe_update_tpanen= oci_execute($r_update_tpanen);
							
							//$roweffec_value_hasil_panen = num_rows($con, $sql_update_bcc_thp);
							
						} 
						
						//print_r($result_insert_TDRP);
					}
					else {
							
							
						$cek_nik_mandor = "select NIK_PEMANEN,NIK_MANDOR,TANGGAL_RENCANA from T_HEADER_RENCANA_PANEN
						where ID_RENCANA = '" . $oldrencana . "'";
						$result_nik_mandor = oci_parse($con, $cek_nik_mandor);
						oci_execute($result_nik_mandor, OCI_DEFAULT);
						oci_fetch($result_nik_mandor);
						$nik_mandorr = oci_result($result_nik_mandor, "NIK_MANDOR");
						$nik_pemanen = oci_result($result_nik_mandor, "NIK_PEMANEN");
						$tanggal_rencana = oci_result($result_nik_mandor, "TANGGAL_RENCANA");
						
						
						if($nik_mandorr<>$NIK_Mandor){
							
							if($nik_pemanen<>$NIK_Pemanen or $tanggal_rencana<>$tglPanenx){
								
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
									'" . $nik_krani_buah . "', '" . $NIK_Pemanen . "', 'NO')";
									
									$result_insertemp = num_rows($con, $query_insertemp);
									
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
								
								$result_insertemp = num_rows($con, $query_insertemp);
							}
							
							
							//print_r("x=".$x." ".$result_insertemp."<br>");
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
								'" . $nik_krani_buah . "', '" . $NIK_Pemanen . "', 'NO')";
								
								$result_insertemp = num_rows($con, $query_insertemp);
								
							} else{
								
								$result_insertemp = 1;
							}
							
						}
						
						//INSERT T_DETAIL_RENCANA_PANEN
						$ID_BA_AFD_BLOK = $ba . $afd . $ID_blok;
						$query_cek_TDRP = "SELECT *
						FROM T_DETAIL_RENCANA_PANEN
						WHERE ID_RENCANA LIKE '" . $new_id_rencana . "'
						AND NO_REKAP_BCC = '" . $No_Rekap . "'
						AND ID_BA_AFD_BLOK = '" . $ID_BA_AFD_BLOK . "'";
						
						$result_cek_TDRP = oci_parse($con, $query_cek_TDRP);
						oci_execute($result_cek_TDRP, OCI_DEFAULT);
						oci_fetch($result_cek_TDRP);
						$cek_id_rencana_TDRP = oci_result($result_cek_TDRP, "ID_RENCANA");
						
						if($cek_id_rencana_TDRP == ""){
							$query_insert_TDRP = "INSERT INTO T_DETAIL_RENCANA_PANEN (ID_BA_AFD_BLOK, NO_REKAP_BCC, ID_RENCANA, LUASAN_PANEN)
							VALUES ('" . $ID_BA_AFD_BLOK . "', '" . $No_Rekap . "', '" . $new_id_rencana . "', '0')";
							
							$result_insert_TDRP = num_rows($con, $query_insert_TDRP);
							
						}
						else{ 
							$result_insert_TDRP = 1;
						}	
						
						//INSERT T_HASIL_PANEN
						$query_thp_cek = "SELECT * FROM T_HASIL_PANEN 
						WHERE NO_BCC = '" . $No_BCC . "' 
						AND ID_RENCANA = '" . $new_id_rencana . "'";
						$result_thp_cek = oci_parse($con, $query_thp_cek);
						oci_execute($result_thp_cek, OCI_DEFAULT);
						oci_fetch($result_thp_cek);
						$cek_id_rencana_THP = oci_result($result_thp_cek, "ID_RENCANA");
						//echo $query_thp_cek;
						//print_r("x=".$x." ".$cek_id_rencana_THP."<br>");
						if ($cek_id_rencana_THP == ""){
							$query_thp_old = "SELECT * FROM T_HASIL_PANEN 
							WHERE NO_BCC = '" . $No_BCC . "' 
							AND ID_RENCANA = '" . $oldrencana . "'";
							
							$result_thp_old = oci_parse($con, $query_thp_old);
							oci_execute($result_thp_old, OCI_DEFAULT);
							oci_fetch($result_thp_old);
							
							/* $query_ins_thp = "INSERT INTO T_HASIL_PANEN 
							(ID_RENCANA, 
							NO_REKAP_BCC, 
							NO_TPH, 
							NO_BCC,
							KODE_DELIVERY_TICKET,
							LATITUDE,
							LONGITUDE,
							PICTURE_NAME,
							STATUS_BCC,
							ID_NAB_TGL,
							IMAGE_FILE,
							UPDATE_TIME_CLOB)
							VALUES ('" .$new_id_rencana."',
							'" .$No_Rekap."',
							'" .oci_result($result_thp_old, "NO_TPH")."',
							'" .oci_result($result_thp_old, "NO_BCC")."',  
							'" .oci_result($result_thp_old, "KODE_DELIVERY_TICKET")."',
							'" .oci_result($result_thp_old, "LATITUDE")."',
							'" .oci_result($result_thp_old, "LONGITUDE")."',
							'" .oci_result($result_thp_old, "PICTURE_NAME")."',
							'" .oci_result($result_thp_old, "STATUS_BCC")."',
							'" .oci_result($result_thp_old, "ID_NAB_TGL")."',
							'" .oci_result($result_thp_old, "IMAGE_FILE")."',
							'" .oci_result($result_thp_old, "UPDATE_TIME_CLOB")."')"; */
							
							$query_ins_thp = "INSERT INTO T_HASIL_PANEN
  (ID_RENCANA, NO_REKAP_BCC, NO_TPH, NO_BCC, KODE_DELIVERY_TICKET, LATITUDE, LONGITUDE, PICTURE_NAME, STATUS_BCC, ID_NAB_TGL, IMAGE_FILE, UPDATE_TIME_CLOB)
SELECT '".$new_id_rencana."', t.NO_REKAP_BCC, t.NO_TPH, t.NO_BCC, t.KODE_DELIVERY_TICKET, t.LATITUDE, t.LONGITUDE, t.PICTURE_NAME, t.STATUS_BCC, t.ID_NAB_TGL, t.IMAGE_FILE, t.UPDATE_TIME_CLOB
  FROM T_HASIL_PANEN t
 WHERE t.NO_REKAP_BCC = '".$No_Rekap."' AND t.NO_BCC = '".$No_BCC."' AND t.ID_RENCANA = '".$oldrencana."'";
							
							/* $stid = oci_parse($conn,$query_ins_thp );
							$e = oci_error($stid);  // For oci_execute errors pass the statement handle
							print htmlentities($e['message']);
							exit; */
							$result_THP = num_rows($con, $query_ins_thp);
							
							//print_r($result_THP);
						}
						else{
							$result_THP = 1;
							
						}
						
						//print_r("x=".$x." ".$oldrencana." ".$ID_RENCANA."<br>");
						//======= delete thk old ========
						if($x==1 AND $oldrencana!=$ID_RENCANA){
							
							$query_del_thk_old = "DELETE FROM T_HASILPANEN_KUALTAS 
							WHERE ID_BCC = '" . $No_BCC . "' 
							AND ID_RENCANA = '" . $oldrencana . "'";
							//echo $query_del_thk_old;
							$r_del_thk = oci_parse($con, $query_del_thk_old);
							$exe_del_thk = oci_execute($r_del_thk);
							
							$update_bcc_t_hasil_panen = "DELETE FROM T_HASIL_PANEN WHERE NO_REKAP_BCC = '" . $No_Rekap . "' 
							AND NO_BCC = '" . $No_BCC . "' 
							AND ID_RENCANA = '" . $oldrencana . "'";
							//echo $update_bcc_t_hasil_panen;
							
							$r_del_tpanen = oci_parse($con, $update_bcc_t_hasil_panen);
							$r_exe_tpanen= oci_execute($r_del_tpanen);
						}
						//print_r("x=".$x." ".$result_insertemp." ".$result_insert_TDRP." ".$result_THP."<br>");
						
						if($result_insertemp == 1 && $result_insert_TDRP == 1 && $result_THP == 1){
							$roweffec_change_data = 1;
						}
						
					}
					
					if ( $new_id_rencana != ''){
						$ID_RENCANA = $new_id_rencana;
					}
					//print_r("x=".$x." ".$ID_RENCANA."<br>");
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
						('$ID_BCC_Kualitas[$x]', '$No_BCC', '$ID_Kualitas[$x]', '$Qty[$x]', '$new_id_rencana')";
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
						('INSERT', SYSDATE, '$NIK', '$Login_Name', 't_hasilpanen_kualtas', '$ID_BCC_Kualitas[$x]', '$Qty[$x]', '$OldQty[$x]', 'Device', SYSDATE)" ;
						$roweffec_value_log_hasilpanen_kualitas = num_rows($con,$sql_value_log_hasilpanen_kualitas);
						
					}
					
					//print_r("x=".$x." - ".$roweffec_value[$x]." - ".$roweffec_value_log_hasilpanen_kualitas." - ".$roweffec_change_data."<br>");
					if($roweffec_value[$x] > 0 && $roweffec_value_log_hasilpanen_kualitas > 0 && $roweffec_change_data > 0){
						commit($con);
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