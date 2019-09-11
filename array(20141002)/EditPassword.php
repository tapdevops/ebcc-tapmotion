<?php
$response = array();

//jika kirim
if (isset($_POST["NIK"]) && isset($_POST["OldPassword"]) && isset($_POST["NewPassword"]) && isset($_POST['Login_Name'])) {
	/*
	if (get_magic_quotes_gpc()) {
		$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
		while (list($key, $val) = each($process)) {
			foreach ($val as $k => $v) {
				unset($process[$key][$k]);
				if (is_array($v)) {
					$process[$key][stripslashes($k)] = $v;
					$process[] = &$process[$key][stripslashes($k)];
				} else {
					$process[$key][stripslashes($k)] = stripslashes($v);
				}
			}
		}
		unset($process);
	*/
	

$NIK = $_POST['NIK'];
$Login_Name = $_POST['Login_Name'];
$OldPassword = $_POST['OldPassword'];
$NewPassword = $_POST['NewPassword']; 

/*
$NIK = "41/4121/0310/68";
$OldPassword = "67891";
$NewPassword = "12345"; */


		if ($NIK =="" && $OldPassword == "" && $NewPassword == ""){
			$response["success"] = 0;
			$response["message"] = "Please input your Login Name, NIK, and Password";
			echo json_encode($response);
		}else if($NIK == ""){
			$response["success"] = 0;
			$response["message"] = "Please input your NIK";
			echo json_encode($response);
		}else if($OldPassword == ""){
			$response["success"] = 0;
			$response["message"] = "Please input your Password";
			echo json_encode($response);
		}else if($NewPassword == ""){
			$response["success"] = 0;
			$response["message"] = "Please input your Login Name";
			echo json_encode($response);
		}else {
			include("../config/SQL_function.php");
			//require_once __DIR__ . '/db_config.php'; 
			//$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
			include("../config/db_connect.php");
			$con = connect();
			
			//t_nik_passwd
			$sql_value_nik = "SELECT Login_Name FROM t_user where Login_Name = '$Login_Name' and Passwd = '$OldPassword'";
			/*$fetch_nik = select_data($con,$sql_value_nik);
			$NIKmatch = $fetch_nik['NIK'];*/
			$result_value_nik = oci_parse($con, $sql_value_nik);
			oci_execute($result_value_nik, OCI_DEFAULT);
			oci_fetch($result_value_nik);
			$Login_Name 		= oci_result($result_value_nik, "LOGIN_NAME");
			
			//jika yang di input ada di database
			if ($result_value_nik && $Login_Name != ""){
					$sql_update_password = "UPDATE t_user SET Passwd = '$NewPassword' WHERE Login_Name = '$Login_Name' AND Passwd = '$OldPassword'";
					$rs_update_password = num_rows($con, $sql_update_password);
					
					
					$sql_log_t_NP = "INSERT INTO t_log_nik_passwd (
					InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, New_Value_Pass, Old_Value_Pass, CreEdit_From, Sync_Server, ON_NIK) 
					VALUES
					('UPDATE', SYSDATE , '$NIK', '$Login_Name', 't_nik_passwd', '$NewPassword', '$OldPassword', 'Device', SYSDATE, '$NIK')";
					$log_update_pass = num_rows($con,$sql_log_t_NP);
					//oci_execute($rs_update_password, OCI_DEFAULT);
					//oci_fetch($rs_update_password);
					//num_rows($rs_update_password);
					
					//echo $rs_update_password. $sql_update_password ;
				
					//echo oci_num_rows($rs_update_password). $sql_update_password;
				//break;
					//jika table employee ada isinya
					if($rs_update_password > 0 && $log_update_pass > 0 ){
						$response["success"] = 1;
						$response["message"] = "Password successfully changed";
						commit($con);
						echo json_encode($response);
						}// close if ($fetch_empname = mysql_fetch_array($sql_empname))
					else{
						$response["success"] = 0;
						$response["message"] = "Change password failed";
						rollback($con);
						echo json_encode($response);	
					}		
				
			}//close if ($result_nik && $result_arealogin)		
			else{
				$response["success"] = 0;
				$response["message"] = "Please check your NIK and Password";
				rollback($con);
				echo json_encode($response);
			} 
				
		}
	/*
	}
	else
	{
		echo json_encode($response);
	}*/
}//close if (isset($_POST["NIK"]) && isset($_POST["Passwd"]) && isset($_POST["Login_Name"]))
else{
    $response["success"] = 0;
    $response["message"] = "Required NIK and Password";
    echo json_encode($response);
} 
?>