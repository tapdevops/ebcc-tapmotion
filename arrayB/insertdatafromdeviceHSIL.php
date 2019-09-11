<?php
include("fungsi.php");
$response = array();
include("../config/SQL_function.php");
						include("../config/db_connect.php");
						$don = connect();

$Tanggal_Rencana 	= $_POST["Tanggal_Rencana"]; 
			$NIK_Mandor 		= $_POST["NIK_Mandor"];
			$NIK_Pemanen 		= $_POST["NIK_Pemanen"];
			$Status_Gandeng 	= $_POST["Status_Gandeng"]; 
			$row_t_detail_rencana_panen	= $_POST["row_t_detail_rencana_panen"];
			$row_t_hasil_panen			= $_POST["row_t_hasil_panen"];
			$row_t_detail_gandeng 		= $_POST["row_t_detail_gandeng"]; 
			$row_t_hasilpanen_kualitas=$_POST["row_t_hasilpanen_kualitas"];
			$ID_Rencana 		= $_POST["ID_Rencana"]; 
			
			

for($d = 0 ; $d < $row_t_hasilpanen_kualitas ; $d++ )
	
	{
	  logToFile("sql.log", $d);
	logToFile("sql.log", $row_t_hasilpanen_kualitas);
	
		$ID_BCC_Kualitas[$d] =  replace_dot($_POST["ID_BCC_Kualitas$d"]);
		//$ID_BCC[$d]		=  replace_dot($_POST["ID_BCC$d"]);
		$ID_Kualitas[$d]=  $_POST["ID_Kualitas$d"];
		$Qty[$d] 		=  $_POST["Qty$d"]; 
		$ID_BCC[$d] 		= replace_dot($_POST["ID_BCC$d"]);
		
		//$ID_BCC[$d] 		= replace_dot($_POST["ID_BCC$d"]);
		//for($d = 0 ; $d < $row_t_hasil_panen ; $d++ )
		//'$No_NAB[$d]'
		
		//logToFile("sql.log", 'step 6 :'$ID_BCC[$d]);
	
		$sql_status_bcc = "select Status_BCC from t_hasil_panen 
		WHERE No_BCC  = '$ID_BCC[$d]' AND ID_RENCANA = '$ID_Rencana'";
		$select_status_bcc  = select_data($don,$sql_status_bcc);
		$result_Status_BCC = $select_status_bcc["STATUS_BCC"];
		logToFile("sql.log", 'step 7');
		
		logToFile("sql.log", $sql_status_bcc);
		
		if($result_Status_BCC !== "LOST")
		{
			$sql_t_hasilpanen_kualtas = "INSERT INTO t_hasilpanen_kualtas 
			(ID_BCC_Kualitas, ID_BCC, ID_Kualitas, Qty, ID_RENCANA) 
			VALUES
			('$ID_BCC_Kualitas[$d]', '$ID_BCC[$d]', '$ID_Kualitas[$d]', '$Qty[$d]', '$ID_Rencana')";
			
			logToFile("sql.log", 'step 8');
			
			logToFile("sql.log", $sql_t_hasilpanen_kualtas);
			
			$stmt = oci_parse($don,$sql_t_hasilpanen_kualtas);
			$x_exe = oci_execute($stmt, OCI_DEFAULT);
			if(!$x_exe)
			{
				$m = oci_error($stmt);
				$sql_t_hasilpanen_kualtas = "UPDATE t_hasilpanen_kualtas 
				SET QTY = '$Qty[$d]'
				WHERE ID_BCC_Kualitas  = '$ID_BCC_Kualitas[$d]' AND ID_RENCANA = '$ID_Rencana'";
				$roweffec_t_hasilpanen_kualtas  = num_rows($don,$sql_t_hasilpanen_kualtas);
				$logAction ="UPDATE";
				logToFile("sql.log", 'step 9');
				
				logToFile("sql.log", $sql_t_hasilpanen_kualtas);
			}
			else
			{
				$roweffec_t_hasilpanen_kualtas = oci_num_rows($stmt);
				oci_free_statement($stmt);
				$logAction ="INSERT";
			}
			
			$sql_value_log_hasilpanen_kualitas = "INSERT INTO t_log_hasilpanen_kualitas 
			(InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_BCC_Kualitas, New_Value_Qty, CreEdit_From, Sync_Server)
			VALUES
			('$logAction', SYSDATE, '$NIK', '$Login_Name', 't_hasilpanen_kualtas', '$ID_BCC_Kualitas[$d]', $Qty[$d], 'Device', SYSDATE)" ;
			$roweffec_value_log_hasilpanen_kualitas = num_rows($don,$sql_value_log_hasilpanen_kualitas);
			
			logToFile("sql.log", 'step 10');
			logToFile("sql.log", $sql_value_log_hasilpanen_kualitas);
			
			if($roweffec_t_hasilpanen_kualtas > 0 && $roweffec_t_hasilpanen_kualtas != 0 
			   && $roweffec_value_log_hasilpanen_kualitas > 0 && $roweffec_value_log_hasilpanen_kualitas != 0){
				//commit($don);
				$hpk_message[$d] = $logAction." kualitas hasil panen sukses".$ID_BCC_Kualitas[$d];
				$hpk_array[$d] = 1;
			}
			else{
				//rollback($don);
				$hpk_message[$d] = $logAction." kualitas hasil panen gagal".$ID_BCC_Kualitas[$d];
				$hpk_array[$d] = 0;
				$x_continue = false;
				$x_stage = 6;
			}
		}
	} // close 



			
?>