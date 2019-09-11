<?php
session_start();

$UpdateAAP		= "";
$AddAAP			= "";
$NewAAPSubmit	= "";

if(isset($_POST['UpdateAAP']) || isset($_POST['AddAAP']) || isset($_POST['NewAAPSubmit']) || isset($_POST['DelStat'])){
	
	if($_POST['UpdateAAP'] == "" && $_POST['AddAAP'] == "" && $_POST['NewAAPSubmit'] == "" && $_POST['DelStat'] == ""){
		$_SESSION['err'] = "Update, Add New, Submit or Delete value not valid!";
		header("location:KoreksiAAPSelect.php");
	}
	else{
		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		
		if($_POST['UpdateAAP'] == TRUE){
			
			//OPEN ADIT UPDATE
			if(isset($_POST['NO_REKAP_BCC']) && isset($_POST['roweffec_DETAILGANDENG']) && isset($_POST['Luasan_Panen']) && isset($_POST['ID_RENCANA']) 
			&& isset($_POST['ID_BAlabel'])
			//&& isset($_SESSION['ID_Group_BA']) 
			){
			$NO_REKAP_BCC	= $_POST['NO_REKAP_BCC'];
			$roweffec_DETAILGANDENG	= $_POST['roweffec_DETAILGANDENG'];
			$LUASAN_PANEN			= $_POST['Luasan_Panen'];
			$ID_RENCANA				= $_POST['ID_RENCANA'];	
			$ID_Group_BA 			= $_SESSION['ID_Group_BA'];
				
				if($roweffec_DETAILGANDENG == "" || $LUASAN_PANEN	== ""){
					$_SESSION['err'] = "Data to update not found!";
					header("location:KoreksiAAPSelect.php");
				}
				else{
					/*include("../config/SQL_function.php");
					include("../config/db_connect.php");
					$con = connect();*/
					
					if(is_numeric($LUASAN_PANEN) ==  false)
					{
						rollback($con);
						$_SESSION["err"] = "Data not updated, Luasan Panen is not number!";
						header("Location:KoreksiAAPSelect.php");
						die;
					}
					
					//Edited by Ardo, 03-10-2016 : CR Perubahan perhitungan luasan panen
					$get_tanggal_pemanen = "select to_char(tanggal_rencana,'MM/DD/YYYY') tanggal_rencana, nik_pemanen from t_header_rencana_panen where id_rencana='$ID_RENCANA'";
					$r_get_tanggal_pemanen	= select_data($con,$get_tanggal_pemanen);
					
					$get_other_rencana = "select * from t_header_rencana_panen where NIK_PEMANEN = '".$r_get_tanggal_pemanen['NIK_PEMANEN']."' AND TANGGAL_RENCANA like TO_DATE('".$r_get_tanggal_pemanen['TANGGAL_RENCANA']."', 'MM/DD/YYYY')";
					$r_get_other_rencana = oci_parse($con,$get_other_rencana);
					oci_execute($r_get_other_rencana, OCI_DEFAULT);
					
					
					$get_id_ba_afd_blok = "select ID_BA_AFD_BLOK from t_detail_rencana_panen WHERE ID_RENCANA = '$ID_RENCANA' AND NO_REKAP_BCC = '$NO_REKAP_BCC'";
					$r_get_id_ba_afd_blok	= select_data($con,$get_id_ba_afd_blok);
					//echo $r_get_id_ba_afd_blok['ID_BA_AFD_BLOK'];
					
					$jml_cek_lpanen = 0;
					$jml_true_lpanen = 0;
					//echo $ID_RENCANA."<br>";
					while(oci_fetch($r_get_other_rencana)){
						$sql_value[$x] = "UPDATE t_detail_rencana_panen SET LUASAN_PANEN = $LUASAN_PANEN WHERE ID_RENCANA = '".oci_result($r_get_other_rencana, "ID_RENCANA")."' AND ID_BA_AFD_BLOK = '".$r_get_id_ba_afd_blok['ID_BA_AFD_BLOK']."'";
						$roweffec_value[$x] = num_rows($con,$sql_value[$x]);
						//echo oci_result($r_get_other_rencana, "ID_RENCANA")." ".$roweffec_value[$x]."<br>";
					}
					//exit;
					$_SESSION["val_luasan_panen"] = $LUASAN_PANEN;
					
					
					
					if($roweffec_DETAILGANDENG == 0)
					{
						$_SESSION["err"] = "Data updated";
						commit($con);
					}
					
					for($x=0; $x < $roweffec_DETAILGANDENG; $x++){
						$ID_GANDENG[$x] = $_POST["ID_GANDENG$x"];
						$NewNIK_GANDENG[$x] = $_POST["NewNIK_GANDENG$x"];
						$NIK_GANDENG[$x] = $_POST["NIK_GANDENG$x"];
						$ID_BA_Gandeng 	= $_POST["ID_BAlabel"];
						
						if($NewNIK_GANDENG[$x] == $NIK_GANDENG[$x])
						{
							$_SESSION["err"] = "Data updated";
							commit($con);
						}			
						else
						{
							/*$sql_select_NIKExist = "SELECT * FROM t_employee te 
							inner join t_afdeling ta on te.ID_BA_AFD = ta.ID_BA_AFD
							where ID_BA = '$ID_BA_Gandeng' and NIK = '$NewNIK_GANDENG[$x]'";*/
							$sql_select_NIKExist = "SELECT *
							FROM t_alternate_ba_group t2
							INNER JOIN t_bussinessarea t3 	ON t2.ID_BA = t3.ID_BA
							INNER JOIN t_afdeling t5 	ON t3.ID_BA = t5.ID_BA
							INNER JOIN t_employee t7 	ON t5.ID_BA_Afd = t7.ID_BA_Afd
							WHERE t2.ID_Group_BA = '$ID_Group_BA' and t7.NIK = '$NewNIK_GANDENG[$x]'";
							$rs_select_NIKExist = oci_parse($con, $sql_select_NIKExist);
							oci_execute($rs_select_NIKExist, OCI_DEFAULT);
							oci_fetch($rs_select_NIKExist);
							$roweffec_select_NIKExist	= oci_num_rows($rs_select_NIKExist);
							//echo "<br> roweffec_select_NIKExist". $roweffec_select_NIKExist. $sql_select_NIKExist;
							
							//echo $sql_select_NIKExist; die;
							
							if($roweffec_select_NIKExist > 0){
								$sql_check = "select * from t_detail_gandeng WHERE ID_RENCANA = '$ID_RENCANA' AND ID_GANDENG = '$ID_GANDENG[$x]'";
								$roweffec_check = select_data($con,$sql_check);
								echo $sql_check."<br>";
								
								if($roweffec_check > 0){
									$sql_value[$x] = "UPDATE t_detail_gandeng SET NIK_GANDENG = '$NewNIK_GANDENG[$x]' WHERE ID_RENCANA = '$ID_RENCANA' AND ID_GANDENG = '$ID_GANDENG[$x]'";
									$roweffec_value[$x] = num_rows($con,$sql_value[$x]);
								}
								else{
									$sql_value[$x]  = "INSERT INTO t_detail_gandeng 
									(ID_GANDENG, ID_RENCANA, NIK_GANDENG) 
									VALUES
									('$ID_GANDENG[$x]', '$ID_RENCANA', '$NewNIK_GANDENG[$x]')";
									$roweffec_value[$x] = num_rows($con,$sql_value[$x]);
								}
									
								if($roweffec_value[$x] > 0){
									commit($con);
									$_SESSION["err"] = "Data updated";
								}
								else{
									rollback($con);
									$_SESSION["err"] = "Data not updated";
								}
							}
							else{
								//TIDAK PUNYA GROUP
								$sql_select_NIKExist_without_BA = "SELECT *
								FROM t_bussinessarea t3
								INNER JOIN t_afdeling t5 	ON t3.ID_BA = t5.ID_BA
								INNER JOIN t_employee t7 	ON t5.ID_BA_Afd = t7.ID_BA_Afd
								WHERE t3.ID_BA = '$ID_BA_Gandeng' and t7.NIK = '$NewNIK_GANDENG[$x]'";
								$rs_select_NIKExist_without_BA = oci_parse($con, $sql_select_NIKExist_without_BA);
								oci_execute($rs_select_NIKExist_without_BA, OCI_DEFAULT);
								oci_fetch($rs_select_NIKExist_without_BA);
								$roweffec_select_NIKExist_without_BA	= oci_num_rows($rs_select_NIKExist_without_BA);
								
								if($roweffec_select_NIKExist_without_BA > 0){
									$sql_check = "select * from t_detail_gandeng WHERE ID_RENCANA = '$ID_RENCANA' AND ID_GANDENG = '$ID_GANDENG[$x]'";
									$roweffec_check = select_data($con,$sql_check);
									echo $sql_check."<br>";
									
									if($roweffec_check > 0){
										$sql_value[$x] = "UPDATE t_detail_gandeng SET NIK_GANDENG = '$NewNIK_GANDENG[$x]' WHERE ID_RENCANA = '$ID_RENCANA' AND ID_GANDENG = '$ID_GANDENG[$x]'";
										$roweffec_value[$x] = num_rows($con,$sql_value[$x]);
									}
									else{
										$sql_value[$x]  = "INSERT INTO t_detail_gandeng 
										(ID_GANDENG, ID_RENCANA, NIK_GANDENG) 
										VALUES
										('$ID_GANDENG[$x]', '$ID_RENCANA', '$NewNIK_GANDENG[$x]')";
										$roweffec_value[$x] = num_rows($con,$sql_value[$x]);
									}
										
									if($roweffec_value[$x] > 0){
										commit($con);
										$_SESSION["err"] = "Data updated";
									}
									else{
										rollback($con);
										$_SESSION["err"] = "Data not updated";
									}
								}
								else{
									rollback($con);
									$_SESSION['err'] = "NIK Gandeng not found";
								}
							}
						}
						
					}
					header("Location:KoreksiAAPSelect.php");
				}
			}
			else{
			$_SESSION["err"] = "Uncomplete Data for Update";
			header("Location:../KoreksiAAPSelect.php");
			}
			//CLODE ADIT UPDATE
		}
		
		// OPEN TASYA ADD and SUBMIT
		else if($_POST['AddAAP'] == TRUE){
			$AddAAP			= $_POST['AddAAP'];
			echo 1 .$AddAAP	;
			$sql_select_lastrow_gandeng 	= "select MAX(ID_GANDENG) AS ID_GANDENG from t_detail_gandeng";
			$result_select_lastrow_gandeng 	= select_data($con,$sql_select_lastrow_gandeng);
			$ID_GANDENG 	= $result_select_lastrow_gandeng["ID_GANDENG"];
			
			$IDGandengIncrement = 1;
			
			$_SESSION['AddAAP'] = $AddAAP;
			$_SESSION['NewIDGandeng'] = $ID_GANDENG + $IDGandengIncrement;
			
			echo  $_SESSION['AddAAP']." - ".$_SESSION['NewIDGandeng'];
			header("location:KoreksiAAPSelect.php");
		}
		
		else if($_POST['NewAAPSubmit']  == TRUE){
			echo 2;
				
			if(isset($_POST['ID_Detail_Gandeng_BARU']) && isset($_POST['ID_Rencana_IGBARU']) && isset($_POST['NIK_Gandeng_BARU']) && isset($_POST['ID_BA_Gandeng']) && isset($_SESSION['LoginName']) && isset($_SESSION['NIK'])){
				$ID_Detail_Gandeng_BARU	= $_POST['ID_Detail_Gandeng_BARU'];
				$ID_Rencana_IGBARU 		= $_POST['ID_Rencana_IGBARU'];
				$NIK_Gandeng_BARU 		= $_POST['NIK_Gandeng_BARU'];
				$ID_BA_Gandeng 			= $_POST['ID_BA_Gandeng'];
				$ID_Group_BA 			= $_SESSION['ID_Group_BA'];
				$Login_Name 			= $_SESSION['LoginName'];
				$NIK 					= $_SESSION['NIK'];
				
				//echo "<br> ID_Detail_Gandeng_BARU = ". $ID_Detail_Gandeng_BARU. "<br> ID_Rencana_IGBARU = ". $ID_Rencana_IGBARU. "<br> NIK_Gandeng_BARU = ".$NIK_Gandeng_BARU . "<br> ID_BA_Gandeng = ". $ID_BA_Gandeng ;
				
				if($ID_Detail_Gandeng_BARU == "" || $ID_Rencana_IGBARU == "" || $NIK_Gandeng_BARU  == ""){
					$_SESSION['err'] = "Please input valid value" . "<br>Login_Name = ".$Login_Name . "<br> NIK =". $NIK  ;
				}
				else{
					/*$sql_select_NIKExist = "SELECT * FROM t_employee te 
					inner join t_afdeling ta on te.ID_BA_AFD = ta.ID_BA_AFD
					where ID_BA = '$ID_BA_Gandeng' and NIK = '$NIK_Gandeng_BARU'";*/
					$sql_select_NIKExist = "SELECT *
					FROM t_alternate_ba_group t2
					INNER JOIN t_bussinessarea t3 	ON t2.ID_BA = t3.ID_BA
					INNER JOIN t_afdeling t5 	ON t3.ID_BA = t5.ID_BA
					INNER JOIN t_employee t7 	ON t5.ID_BA_Afd = t7.ID_BA_Afd
					WHERE t2.ID_Group_BA = '$ID_Group_BA' and t7.NIK = '$NIK_Gandeng_BARU'";
					$rs_select_NIKExist = oci_parse($con, $sql_select_NIKExist);
					oci_execute($rs_select_NIKExist, OCI_DEFAULT);
					oci_fetch($rs_select_NIKExist);
					$roweffec_select_NIKExist	= oci_num_rows($rs_select_NIKExist);
					//echo "<br> roweffec_select_NIKExist". $roweffec_select_NIKExist. $sql_select_NIKExist;
					
					if($roweffec_select_NIKExist > 0){
						$sql_InsertNewGandeng = "INSERT INTO t_detail_gandeng
						(ID_GANDENG, ID_RENCANA, NIK_GANDENG) 
						VALUES
						('$ID_Detail_Gandeng_BARU', '$ID_Rencana_IGBARU', '$NIK_Gandeng_BARU')";
						$roweffec_InsertNewGandeng = num_rows($con,$sql_InsertNewGandeng);
						
						if($roweffec_InsertNewGandeng > 0){
							$sql_value_t_log_detail_gandeng = "INSERT INTO t_log_detail_gandeng (
							InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_Gandeng, CreEdit_From, Sync_Server) 
							VALUES
							('INSERT', SYSDATE , '$NIK', '$Login_Name', 't_detail_gandeng', '$ID_Detail_Gandeng_BARU', 'Website', SYSDATE)" ;
							$result_value_t_log_detail_gandeng = insert_data($con,$sql_value_t_log_detail_gandeng);
							commit($con);
							$_SESSION['err'] = "Add New NIK Gandeng Success";
							header("location:KoreksiAAPSelect.php");
						}
						else{
							$_SESSION['err'] = "Add New NIK Gandeng Failed";
							header("location:KoreksiAAPSelect.php");
						}
					}
					else{
						//TIDAK PUNYA GROUP
						$sql_select_NIKExist_without_BA = "SELECT *
						FROM t_bussinessarea t3
						INNER JOIN t_afdeling t5 	ON t3.ID_BA = t5.ID_BA
						INNER JOIN t_employee t7 	ON t5.ID_BA_Afd = t7.ID_BA_Afd
						WHERE t3.ID_BA = '$ID_BA_Gandeng' and t7.NIK = '$NIK_Gandeng_BARU'";
						$rs_select_NIKExist_without_BA = oci_parse($con, $sql_select_NIKExist_without_BA);
						oci_execute($rs_select_NIKExist_without_BA, OCI_DEFAULT);
						oci_fetch($rs_select_NIKExist_without_BA);
						$roweffec_select_NIKExist_without_BA	= oci_num_rows($rs_select_NIKExist_without_BA);
						
						if($roweffec_select_NIKExist_without_BA > 0){
							$sql_InsertNewGandeng = "INSERT INTO t_detail_gandeng
							(ID_GANDENG, ID_RENCANA, NIK_GANDENG) 
							VALUES
							('$ID_Detail_Gandeng_BARU', '$ID_Rencana_IGBARU', '$NIK_Gandeng_BARU')";
							$roweffec_InsertNewGandeng = num_rows($con,$sql_InsertNewGandeng);
							
							if($roweffec_InsertNewGandeng > 0){
								$sql_value_t_log_detail_gandeng = "INSERT INTO t_log_detail_gandeng (
								InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_Gandeng, CreEdit_From, Sync_Server) 
								VALUES
								('INSERT', SYSDATE , '$NIK', '$Login_Name', 't_detail_gandeng', '$ID_Detail_Gandeng_BARU', 'Website', SYSDATE)" ;
								$result_value_t_log_detail_gandeng = insert_data($con,$sql_value_t_log_detail_gandeng);
								commit($con);
								$_SESSION['err'] = "Add New NIK Gandeng Success";
								header("location:KoreksiAAPSelect.php");
							}
							else{
								$_SESSION['err'] = "Add New NIK Gandeng Failed";
								header("location:KoreksiAAPSelect.php");
							}
						}
						else{
							$_SESSION['err'] = "NIK Gandeng not found";
							header("location:KoreksiAAPSelect.php");
						}
					}
				}
			}
			else{
				$_SESSION['err'] = "Submit not valid!";
				header("location:KoreksiAAPSelect.php");
			}
		}
		// CLOSE TASYA ADD and SUBMIT
		
		// OPEN TASYA DELETE
		else if($_POST['DelStat']  == TRUE){
			//echo $_POST['DelStat'];

			$DelLine		= $_POST['DelLine'];
			$DelAAP_ID		= $_POST['DelAAP_ID'.$DelLine];
			$Del_IDRencana 	= $_POST['Del_IDRencana'];
			$Login_Name 			= $_SESSION['LoginName'];
			$NIK 					= $_SESSION['NIK'];
				
			$sqlrow = "select count(*) totalRow from t_detail_gandeng where id_rencana = '$Del_IDRencana' order by ID_GANDENG";
			$result_row = oci_parse($con, $sqlrow);
			oci_execute($result_row, OCI_DEFAULT);
			while(oci_fetch($result_row)){
				$totalRow 	= oci_result($result_row, "TOTALROW");
			}
			
			//Edited by Ardo, 29-09-2016 : CR Perubahan Proses Koreksi AAP
			$sql_DeleteIDGandeng = "DELETE FROM t_detail_gandeng WHERE ID_GANDENG='$DelAAP_ID' and ID_RENCANA = '$Del_IDRencana'";
			$roweffec_DeleteIDGandeng = num_rows($con,$sql_DeleteIDGandeng);
			
			
			if($roweffec_DeleteIDGandeng > 0){
				$sql_value_t_log_detail_gandeng = "INSERT INTO t_log_detail_gandeng (
				InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_Gandeng, CreEdit_From, Sync_Server) 
				VALUES
				('DELETE', SYSDATE , '$NIK', '$Login_Name', 't_detail_gandeng', '$DelAAP_ID', 'Website', SYSDATE)" ;
				$result_value_t_log_detail_gandeng = delete_data($con,$sql_value_t_log_detail_gandeng);
				commit($con);
				$_SESSION['err'] = "Delete Success";
				header("location:KoreksiAAPSelect.php");
				//echo  $sql_value_t_log_detail_gandeng. "<br>". $sql_DeleteIDGandeng ;
			}
			else{
				$_SESSION['err'] = "Delete Failed";
				header("location:KoreksiAAPSelect.php");
			}
		}
		
		else{
			echo 3;
			$_SESSION['err'] = "Update, Add, and Delete not valid!". "<br>AddAAP =".$_POST['AddAAP']."<br>UpdateAAP =". $_POST['UpdateAAP']."<br>NewAAPSubmit =".$_POST['NewAAPSubmit']."<br>DelStat =".$_POST['DelStat'];
			header("location:KoreksiAAPSelect.php");
		}
		//CLOSE TASYA DELETE
	}
}
else{
$_SESSION["err"] = "Please login";
header("Location:../index.php");
}
			
?>