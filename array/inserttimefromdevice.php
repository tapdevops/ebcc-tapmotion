<?php
include("fungsi.php");
$response = array();



if(isset($_POST["NIK"]) && isset($_POST["Login_Name"]) ){

$x_stage = 0;
$x_stage2 = 0;
$timestamp_insert_count = 0;
	
	$NIK = $_POST["NIK"];
	$Login_Name = $_POST["Login_Name"];  

	if($NIK == "" && $Login_Name == ""){
	$response["success"] = 0;
	$response["message"] = "NIK dan Login Name tidak ada";
	echo json_encode($response);
	logError(json_encode($response));
	}else if($NIK == "" || $Login_Name == ""){
	$response["success"] = 0;
	$response["message"] = "NIK atau Login Name tidak ada";
	echo json_encode($response);
	logError(json_encode($response));
	}
	else{
		if(isset($_POST["row_t_timestamp"])){	
			$row_t_timestamp = $_POST["row_t_timestamp"]; 
			if($row_t_timestamp	== ""){
				$response["success"] = 0;
				$response["message"] = "data yang dikirim kosong";
				echo json_encode($response);
				logError(json_encode($response));
			}else{
				include("../config/SQL_function.php");
				include("../config/db_connect.php");
				$con = connect();							
				if ($row_t_timestamp > 0){
					$affected_t_timestamp = 0;
					for($a = 0 ; $a < $row_t_timestamp ; $a++){
						$ID_TIMESTAMP[$a] =  $_POST["ID_TIMESTAMP$a"];
						$TYPE_TIMESTAMP[$a] =  strtoupper($_POST["TYPE_TIMESTAMP$a"]);
						if($TYPE_TIMESTAMP[$a] <> 'INPUT AKTIVITAS AKHIR PANEN' && $TYPE_TIMESTAMP[$a] <> 'INPUT PENALTY PANEN' ){
							$ID_TIMESTAMP[$a] = str_replace('.', '', $ID_TIMESTAMP[$a]);
						}
						$START_INS_TIME[$a] =  $_POST["START_INS_TIME$a"];
						$END_INS_TIME[$a] =  $_POST["END_INS_TIME$a"];
						$START_UPD_TIME[$a] =  $_POST["START_UPD_TIME$a"];
						$END_UPD_TIME[$a] =  $_POST["END_UPD_TIME$a"];
						if($START_UPD_TIME[$a] == ''){$START_UPD_TIME_temp = "''";}
						else{$START_UPD_TIME_temp = "to_date('" . $START_UPD_TIME[$a] . "','yyyy/mm/dd hh24:mi:ss')";}
						if($END_UPD_TIME[$a] == ''){$END_UPD_TIME_temp = "''";}
						else{$END_UPD_TIME_temp = "to_date('" . $END_UPD_TIME[$a] . "','yyyy/mm/dd hh24:mi:ss')";}
						
						//NBU 20042018
						$sql_t_ts = "select count(*) JUMLAH_DATA from t_timestamp where ID_TIMESTAMP = '$ID_TIMESTAMP[$a]' 
						and TYPE_TIMESTAMP = upper('$TYPE_TIMESTAMP[$a]')";
						$select_t_ts  = select_data($con,$sql_t_ts);
						$jml_ts = $select_t_ts["JUMLAH_DATA"];

						if($jml_ts == 0){
							$sql_t_timestamp = "INSERT INTO t_timestamp	(ID_TIMESTAMP, TYPE_TIMESTAMP, START_INS_TIME, END_INS_TIME, START_UPD_TIME, END_UPD_TIME)
							VALUES ('$ID_TIMESTAMP[$a]', upper('$TYPE_TIMESTAMP[$a]'), to_date('$START_INS_TIME[$a]','yyyy/mm/dd hh24:mi:ss'), to_date('$END_INS_TIME[$a]','yyyy/mm/dd hh24:mi:ss')," . $START_UPD_TIME_temp . "," . $END_UPD_TIME_temp . " )";
							$roweffec_t_timestamp = num_rows($con,$sql_t_timestamp);
							logToFile($ID_TIMESTAMP[$a].'STEP NIC'.$sql_t_timestamp);
							
							if($roweffec_t_timestamp > 0){
								commit($con);
								$affected_t_timestamp++;
								$hpk_message[$a] = "input timestamp sukses".$ID_TIMESTAMP[$a];
								$hpk_array[$a] = 1;
								$timestamp_insert_count = $timestamp_insert_count + 1;
							}
							else{
								rollback($con);
								$failed_t_timestamp++;
								$hpk_message[$a] = "input timestamp gagal".$ID_BCC_Kualitas[$a];
								$hpk_array[$a] = 0;
							}
						}else{
							$affected_t_timestamp++;
							$hpk_message[$a] = "input timestamp sukses".$ID_TIMESTAMP[$a];
							$hpk_array[$a] = 1;
							$timestamp_insert_count = $timestamp_insert_count + 1;
						}//end NBU 20042018
					}
					//echo $row_t_timestamp . " AAA " . $affected_t_timestamp;die();
					if($row_t_timestamp == $affected_t_timestamp){
						$mess = "Timestamp Terkirim : " . $timestamp_insert_count . "\n";
						$mess .= "Timestamp Tidak Terkirim : " . $row_t_timestamp - $timestamp_insert_count . "\n";
						$response["success"] = 1;
						//$response["message"] = "seluruh data berhasil diinput ".$sql_t_timestamp. " roweffect : ".$roweffec_t_timestamp;
						$response["message"] = $mess;
						$response["row_t_timestamp"] = $row_t_timestamp;
						
						echo json_encode($response);
						logError(json_encode($response));
					}
					else{
						$response["message"] = "t_timestamp";
						$response["total_array"] = $row_t_timestamp - $affected_t_timestamp;
						if($response["total_array"] == $row_t_timestamp){
							$response["success"] = 0;
						}else{
							$response["success"] = 1;
						}
						logError(json_encode($response));
						for($d = 0 ; $d < $row_t_timestamp ; $d++ ){
							if ($hpk_array[$d] == 0){
								$response["hpk_message$d"] = $hpk_message[$d];
								$response["hpk_array$d"] = $d;
							}
						}
						echo json_encode($response);	
					} 
				}
			else{
				$response["success"] = 0;
				$response["message"] = "No data to input, DETAIL: ".$_POST["row_t_timestamp"];
				
				echo json_encode($response);
				logError(json_encode($response));
			}
		}// close else	
	}	//close if(isset($_POST["NIK"]) && isset($_POST["Login_Name"]) )
}		}
else{
	$NIK = $_POST["NIK"];
	$Login_Name = $_POST["Login_Name"];  
	$response["success"] = 0;
	$response["message"] = "User login kosong nih ".$NIK." - ".$Login_Name;
	echo json_encode($response);
	logError(json_encode($response));
}	
			
?>