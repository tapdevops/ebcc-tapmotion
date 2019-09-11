<?php
session_start();

if(isset($_POST['NIK_Pemanen']) && isset($_POST['roweffec_BCC']) && isset($_POST['No_BCC']) && isset($_POST['ID_RENCANA']) && isset($_SESSION['NIK']) && isset($_SESSION['LoginName'])){
$NIK_Pemanen	= $_POST['NIK_Pemanen'];
$roweffec_BCC	= $_POST['roweffec_BCC'];
$No_BCC			= $_POST['No_BCC'];
$No_Rekap		= $_POST['NO_Rekap'];
$ID_RENCANA		= $_POST['ID_RENCANA'];
$NIK 			= $_SESSION['NIK'];
$Login_Name 	= $_SESSION['LoginName'];
$tglPanen 		= $_POST['datepicker'];
$NIK_Mandor		= $_POST['NIK_Mandor'];
$ID_blok		= $_POST['selectblok'];
$ba				= $_POST['ID_BAlabel'];
$afd			= $_POST['AFDlabel'];
$tglPanen = date("m-d-Y", strtotime($tglPanen));

//echo $ID_RENCANA."<br>".$NIK_Pemanen."<br>".$tglPanen."<br>".$NIK_Mandor."<br>".$ba."<br>".$afd."<br>".$ID_blok."<br>".$No_BCC."<br>".$No_Rekap."<br>".$roweffec_BCC."<br>";//die();

	if($NIK_Pemanen == "" || $roweffec_BCC == "" || $No_BCC	== ""){
		$_SESSION[err] = "Data to update not found!";
		header("location:KoreksiBCCFil.php");
	}
	else{
		include("../config/SQL_function.php");
		//require_once __DIR__ . '/db_config.php'; 
		//$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
		include("../config/db_connect.php");
		$con = connect();
		
		for($x=0; $x < $roweffec_BCC; $x++){
			$ID_BCC_KUALITAS[$x]	= $_POST["ID_BCC_KUALITAS$x"];
			$ID_Kualitas[$x] = $_POST["ID_Kualitas$x"];
			//echo "HEREEE".$ID_BCC_KUALITAS[$x];
			$Qty[$x] = $_POST["NewQty$x"];
			$OldQty[$x] = $_POST["OldQty$x"];
			//echo "NewQty ".$Qty[$x] . " OldQty ".$OldQty[$x]."<br>";
	
			if($Qty[$x] == NULL)
			{
				$Qty[$x] = 0;
				//header("Location:KoreksiBCCList.php");
			}			
			
			if(!is_numeric($Qty[$x]))
			{
				$_SESSION["err$x"] = "Please input valid value on New Qty data ".$No_BCC;
				header("Location:KoreksiBCCList.php");
			}
			else
			{
				$new_id_rencana = $ID_RENCANA;
				$query_id_rencana = "select ID_RENCANA from T_HEADER_RENCANA_PANEN where NIK_MANDOR = '" . $NIK_Mandor . "' and NIK_Pemanen = '" . $NIK_Pemanen . "' 
									 and TANGGAL_RENCANA = to_date('" . $tglPanen . "','mm-dd-yyyy hh24:mi:ss')";
		
				$result_id_rencana = oci_parse($con, $query_id_rencana);
				oci_execute($result_id_rencana, OCI_DEFAULT);
				oci_fetch($result_id_rencana);
				$plan_ID = oci_result($result_id_rencana, "ID_RENCANA");
				
				if($plan_ID != ""){
					if($plan_ID <> $ID_RENCANA){
						$new_id_rencana = $plan_ID;
						$roweffec_change_date_nik = 0;
						$roweffec_value_hasilpanen_kualtas = 0;
						$sql_update_bcc_thpk = "UPDATE T_HASILPANEN_KUALTAS set ID_RENCANA = '" . $plan_ID . "' where NO_BCC = '" . $No_BCC . "' and ID_RENCANA = '" . $ID_RENCANA . "'";
						$roweffec_value_hasilpanen_kualtas = num_rows($con, $sql_update_bcc_thpk);
						
						$roweffec_value_hasil_panen = 0;
						$sql_update_bcc_thp = "UPDATE T_HASIL_PANEN set ID_RENCANA = '" . $plan_ID . "' where NO_BCC = '" . $No_BCC . "' and ID_RENCANA = '" . $ID_RENCANA . "'";
						$roweffec_value_hasil_panen = num_rows($con, $sql_update_bcc_thp);
						
						$ID_BA_AFD_BLOK = $ba . $afd . $ID_blok;
						$query_no_rekap_bcc = "SELECT NO_REKAP_BCC FROM T_DETAIL_RENCANA_PANEN WHERE ID_BA_AFD_BLOK = '" . $ID_BA_AFD_BLOK . "' AND NO_REKAP_BCC = '" . $No_Rekap . "' 
									  AND ID_RENCANA = '" . $plan_ID . "'";
		
						$result_no_rekap_bcc = oci_parse($con, $query_no_rekap_bcc);
						oci_execute($result_no_rekap_bcc, OCI_DEFAULT);
						oci_fetch($result_no_rekap_bcc);
						$new_rekap_bcc = oci_result($result_no_rekap_bcc, "NO_REKAP_BCC");
						
						$row_effec_value_insert_tdrp = 0;
						if($new_rekap_bcc == ""){
							$sql_insert_bcc_tdrp = "INSERT INTO T_DETAIL_RENCANA_PANEN (ID_BA_AFD_BLOK, NO_REKAP_BCC, ID_RENCANA, LUASAN_PANEN) VALUES ('" . $ID_BA_AFD_BLOK . "', '" . $No_Rekap . "', '" . $plan_ID . "', 0)";
							$row_effec_value_insert_tdrp = num_rows($con, $sql_insert_bcc_tdrp);
						}else{
							$row_effec_value_insert_tdrp = 1;
						}
						
						//kalau berhasil update dan insert kasih informasi untuk commit
						if($roweffec_value_hasilpanen_kualtas != 0 && $roweffec_value_hasil_panen != 0 && $row_effec_value_insert_tdrp != 0){
							$roweffec_change_data = 1;
						}
					}else{
						$ID_BA_AFD_BLOK = $ba . $afd . $ID_blok;
						$datePlan = date('ymd', strtotime($_POST['datepicker']));
						$datePlan1 = date('Ymd', strtotime($_POST['datepicker']));
						$tmp_dateblok = $datePlan . $ID_blok;
						
						$query_no_rekap = "SELECT NO_REKAP_BCC FROM T_DETAIL_RENCANA_PANEN WHERE ID_BA_AFD_BLOK = '" . $ID_BA_AFD_BLOK . "' 
										AND NO_REKAP_BCC LIKE '" . $tmp_dateblok . "%' 
										AND ID_RENCANA LIKE '" . $datePlan1 . ".%." . $NIK_Pemanen . "'";
						//echo $query_no_rekap . "<br>";
						$result_no_rekap = oci_parse($con, $query_no_rekap);
						oci_execute($result_no_rekap, OCI_DEFAULT);
						oci_fetch($result_no_rekap);
						$new_no_rekap = oci_result($result_no_rekap, "NO_REKAP_BCC");
						
						if($new_no_rekap == ""){
							$new_no_rekap = $datePlan . $ID_blok . "01";
							$query_ins_no_rekap = "INSERT INTO T_DETAIL_RENCANA_PANEN (ID_BA_AFD_BLOK, NO_REKAP_BCC, ID_RENCANA, LUASAN_PANEN)
							VALUES ('" . $ID_BA_AFD_BLOK . "', '" . $new_no_rekap . "', '" . $ID_RENCANA . "', '0')";
							//echo $query_ins_no_rekap . "<br>";
							$result_ins_no_rekap = num_rows($con, $query_ins_no_rekap);
							//echo $result_ins_no_rekap;
						}else{
							$result_ins_no_rekap = 1;
						}
						//bentuk bcc baru
						$tmp_str = substr($No_BCC, 11,10);
						$new_no_BCC = $new_no_rekap . $tmp_str;
						
						$sql_update_bcc_thp = "UPDATE T_HASILPANEN_KUALTAS set NO_REKAP_BCC = '" . $new_no_rekap . "', 
												NO_BCC = '" . $new_no_BCC . "' WHERE NO_REKAP_BCC = '" . $No_Rekap . "' AND NO_BCC = '" . $No_BCC . "' and ID_RENCANA = '" . $ID_RENCANA . "'";
						//echo $sql_update_bcc_thp . "<br>";
						$roweffec_value_hasil_panen = num_rows($con, $sql_update_bcc_thp);
						//echo $roweffec_value_hasil_panen;
						//print_r($ID_BCC_KUALITAS);echo "<br>";
						//print_r($ID_Kualitas);echo "<br>";
						
						//echo $roweffec_BCC;
						if($x + 1 == $roweffec_BCC){
							$sql_update_bcc_thp = "UPDATE T_HASIL_PANEN set NO_REKAP_BCC = '" . $new_no_rekap . "', 
													NO_BCC = '" . $new_no_BCC . "' WHERE NO_REKAP_BCC = '" . $No_Rekap . "' AND NO_BCC = '" . $No_BCC . "' and ID_RENCANA = '" . $ID_RENCANA . "'";
							//echo $sql_update_bcc_thp . "<br>";
							$roweffec_value_hasil_panen = num_rows($con, $sql_update_bcc_thp);
							//echo $roweffec_value_hasil_panen;
							//die();
						}
					}
				}else{
					$newdatePlan = date("Ymd", strtotime($tglPanen));
					$new_id_rencana = $newdatePlan . ".MANUAL." . $NIK_Pemanen;
					
					$query_id_rencana = "select NIK_KERANI_BUAH from T_HEADER_RENCANA_PANEN where ID_RENCANA LIKE '%" . $ID_RENCANA . "%'";
		
					$result_id_rencana = oci_parse($con, $query_id_rencana);
					oci_execute($result_id_rencana, OCI_DEFAULT);
					oci_fetch($result_id_rencana);
					$nik_krani_buah = oci_result($result_id_rencana, "NIK_KERANI_BUAH");
					
					$query_insertemp = "INSERT INTO T_HEADER_RENCANA_PANEN (ID_RENCANA, TANGGAL_RENCANA, NIK_MANDOR, NIK_KERANI_BUAH, NIK_PEMANEN, STATUS_GANDENG)
					VALUES ('" . $new_id_rencana . "', to_date('" . $tglPanen . "','mm-dd-yyyy hh24:mi:ss'), '" . $NIK_Mandor . "', 
					'" . $nik_krani_buah . "', '" . $NIK_Pemanen . "', 'NO')";
					
					$result_insertemp = num_rows($con, $query_insertemp);
					
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
					}else $result_insert_TDRP = 1;
					
					$query_update_THPK = "UPDATE T_HASILPANEN_KUALTAS SET ID_RENCANA = '" . $new_id_rencana . "' 
									WHERE ID_BCC_Kualitas = '" . $ID_BCC_KUALITAS[$x] . "' AND ID_BCC = '" . $No_BCC . "'";
					$result_THPK = num_rows($con,$query_update_THPK);
					
					$query_update_THP = "UPDATE T_HASIL_PANEN SET ID_RENCANA = '" . $new_id_rencana . "' 
									WHERE ID_RENCANA = '". $ID_RENCANA ."' AND NO_BCC = '" . $No_BCC . "'";
					$result_THP = num_rows($con,$query_update_THP);
					
					if($result_insertemp == 1 && $result_insert_TDRP == 1 && $result_THPK == 1 && $result_THP == 1){
						$roweffec_change_data = 1;
					}
				}
				
				$sql_check = "select * from t_hasilpanen_kualtas WHERE ID_BCC_Kualitas = '$ID_BCC_KUALITAS[$x]' AND ID_BCC = '$No_BCC'";
				$roweffec_check = select_data($con,$sql_check);
				
				if($roweffec_check > 0){
					
					//echo "qty1".$Qty[$x] . " -- ";
					$sql_value[$x] = "UPDATE t_hasilpanen_kualtas SET Qty = $Qty[$x] WHERE ID_BCC_Kualitas = '$ID_BCC_KUALITAS[$x]' AND ID_BCC = '$No_BCC'";
					$roweffec_value[$x] = num_rows($con,$sql_value[$x]);
					//echo "update".$sql_value[$x].$roweffec_value[$x]."<br>";
					
					$sql_value_log_hasilpanen_kualitas = "INSERT INTO t_log_hasilpanen_kualitas 
					(InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_BCC_Kualitas, New_Value_Qty, Old_Value_Qty, CreEdit_From, Sync_Server)
					VALUES
					('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_hasilpanen_kualtas', '$ID_BCC_KUALITAS[$x]', '$Qty[$x]', '$OldQty[$x]', 'Website', SYSDATE)" ;
					$roweffec_value_log_hasilpanen_kualitas = num_rows($con,$sql_value_log_hasilpanen_kualitas);
					//echo "insert log update".$sql_value_log_hasilpanen_kualitas.$roweffec_value_log_hasilpanen_kualitas."<br>";
				}
				else{
					$ID_BCC_Kualitas[$x] = $No_BCC.$ID_Kualitas[$x];
					$sql_value[$x]  = "INSERT INTO t_hasilpanen_kualtas 
					(ID_BCC_Kualitas, ID_BCC, ID_Kualitas, Qty, ID_RENCANA) 
					VALUES
					('$ID_BCC_Kualitas[$x]', '$No_BCC', '$ID_Kualitas[$x]', '$Qty[$x]', '$new_id_rencana')";
					$roweffec_value[$x] = num_rows($con,$sql_value[$x]);
					//echo "insert".$sql_value[$x].$roweffec_value[$x]."<br>";
					
					$sql_value_log_hasilpanen_kualitas = "INSERT INTO t_log_hasilpanen_kualitas 
					(InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_BCC_Kualitas, New_Value_Qty, Old_Value_Qty, CreEdit_From, Sync_Server)
					VALUES
					('INSERT', SYSDATE, '$NIK', '$Login_Name', 't_hasilpanen_kualtas', '$ID_BCC_Kualitas[$x]', '$Qty[$x]', '$OldQty[$x]', 'Device', SYSDATE)" ;
					$roweffec_value_log_hasilpanen_kualitas = num_rows($con,$sql_value_log_hasilpanen_kualitas);
					//echo "insert log insert".$sql_value_log_hasilpanen_kualitas.$roweffec_value_log_hasilpanen_kualitas."<br>";
				}
				
				if($roweffec_value[$x] > 0 && $roweffec_value_log_hasilpanen_kualitas > 0 && $roweffec_change_data > 0){
					commit($con);
					$_SESSION["err"] = "Data updated";
					//echo "sukses ".$_SESSION["err"]."<br>";
				}
				else{
					rollback($con);
					$_SESSION["err"] = "Data not updated".$sql_value_pemanen[$x];
					//echo "gagal else ".$_SESSION["err"]."<br>";
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