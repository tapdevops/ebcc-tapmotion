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

//echo $NIK_Pemanen."<br>".$roweffec_BCC."<br>".$No_BCC."<br>".$_POST['ID_BCC_KUALITAS0']."<br>";
	
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
				$sql_update_pemanen = "UPDATE T_HEADER_RENCANA_PANEN set NIK_MANDOR = '" . $NIK_Mandor . "', NIK_PEMANEN = '" . $NIK_Pemanen . "', TANGGAL_RENCANA = to_date('" . $tglPanen . "','mm-dd-yyyy hh24:mi:ss') where ID_RENCANA = '" . $ID_RENCANA . "'";
				//echo $sql_update_pemanen; die();
				$roweffec_value_header_rencana = num_rows($con,$sql_update_pemanen);
				
				
				$query_id_ba_afd_blok = "select ID_BA_AFD_BLOK from T_DETAIL_RENCANA_PANEN where ID_RENCANA = '" . $ID_RENCANA . "' and NO_REKAP_BCC = '" . $No_Rekap . "'";
				$result_id_ba_afd_blok = oci_parse($con, $query_id_ba_afd_blok);
				oci_execute($result_id_ba_afd_blok, OCI_DEFAULT);
				oci_fetch($result_id_ba_afd_blok);
				$old_id_ba_afd_blok = oci_result($result_id_ba_afd_blok, "ID_BA_AFD_BLOK");
				$new_id_ba_afd_blok = $ba.$afd.$ID_blok;
				if($old_id_ba_afd_blok <> $new_id_ba_afd_blok){
					$sql_update_blok = "UPDATE T_DETAIL_RENCANA_PANEN set ID_BA_AFD_BLOK = '" . $new_id_ba_afd_blok . "' where ID_RENCANA = '" . $ID_RENCANA . "' AND NO_REKAP_BCC = '" . $No_Rekap . "'";
					$roweffec_value_detail_rencana = num_rows($con,$sql_update_blok);
				
					$query_insertlog = "INSERT INTO T_LOG_RENCANA_PANEN (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_RENCANA, ON_NO_REKAP_BCC,
								NEW_VALUE_ID_BA_AFD_BLOK, OLD_VALUE_ID_BA_AFD_BLOK, CREEDIT_FROM, SYNC_SERVER) VALUES ('UPDATE', sysdate, '$NIK', '$Login_Name',
								't_detail_rencana_panen', '" . $ID_RENCANA . "', '$No_Rekap', '$new_id_ba_afd_blok', 'old_id_ba_afd_blok', 'Website', '')";
					$result_insertlog = num_rows($con, $query_insertlog);
					commit($con);
				}
				
				$sql_check = "select * from t_hasilpanen_kualtas WHERE ID_BCC_Kualitas = '$ID_BCC_KUALITAS[$x]' AND ID_BCC = '$No_BCC'";
				$roweffec_check = select_data($con,$sql_check);
				
				if($roweffec_check > 0){
					
					echo "qty1".$Qty[$x];
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
					('$ID_BCC_Kualitas[$x]', '$No_BCC', '$ID_Kualitas[$x]', '$Qty[$x]', '$ID_RENCANA')";
					$roweffec_value[$x] = num_rows($con,$sql_value[$x]);
					//echo "insert".$sql_value[$x].$roweffec_value[$x]."<br>";
					
					$sql_value_log_hasilpanen_kualitas = "INSERT INTO t_log_hasilpanen_kualitas 
					(InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_BCC_Kualitas, New_Value_Qty, Old_Value_Qty, CreEdit_From, Sync_Server)
					VALUES
					('INSERT', SYSDATE, '$NIK', '$Login_Name', 't_hasilpanen_kualtas', '$ID_BCC_Kualitas[$x]', '$Qty[$x]', '$OldQty[$x]', 'Device', SYSDATE)" ;
					$roweffec_value_log_hasilpanen_kualitas = num_rows($con,$sql_value_log_hasilpanen_kualitas);
					//echo "insert log insert".$sql_value_log_hasilpanen_kualitas.$roweffec_value_log_hasilpanen_kualitas."<br>";
				}
					
				if($roweffec_value[$x] > 0 && $roweffec_value_log_hasilpanen_kualitas > 0 && $roweffec_value_header_rencana > 0 && $roweffec_value_detail_rencana > 0){
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