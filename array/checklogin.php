<?php
$response = array();

//jika kirim
if (isset($_POST["NIK"]) && isset($_POST["Passwd"]) && isset($_POST["Login_Name"])) {
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
$Passwd = $_POST['Passwd'];
$Login_Name = $_POST['Login_Name'];
$statusAPK = $_POST['Status_APK'];

/*
$NIK = "32/3221/0514/446";
$Passwd = "12345";
$Login_Name = "3221.B.KB1"; 
*/
		if ($NIK =="" && $Passwd == "" && $Login_Name == ""){
			$response["success"] = 0;
			$response["message"] = "Please input your Login Name, NIK, and Password";
			echo json_encode($response);
		}else if($NIK == ""){
			$response["success"] = 0;
			$response["message"] = "Please input your NIK";
			echo json_encode($response);
		}else if($Passwd == ""){
			$response["success"] = 0;
			$response["message"] = "Please input your Password";
			echo json_encode($response);
		}else if($Login_Name == ""){
			$response["success"] = 0;
			$response["message"] = "Please input your Login Name";
			echo json_encode($response);
		}else {
			
			include("../config/SQL_function.php");
			//include("../config/db_config.php");
			//require_once __DIR__ . '/db_config.php'; 
			//$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed'); 
			include("../config/db_connect.php");
			$con = connect();
			
			//t_nik_passwd
			$sql_value_nik = "SELECT NIK, Jenis_Login FROM t_nik_passwd where NIK = '$NIK' and JENIS_LOGIN in (2,3)";
			/*$fetch_nik = select_data($con,$sql_value_nik);
			$NIKmatch = $fetch_nik['NIK'];
			$Jenis_Login = $fetch_nik['JENIS_LOGIN'];
			$Number_of_Login = $fetch_nik['NUMBER_OF_LOGIN']; */
			$result_value_nik = oci_parse($con, $sql_value_nik);
			oci_execute($result_value_nik, OCI_DEFAULT);
			while(oci_fetch($result_value_nik)){
				$Jenis_Login[] 		= oci_result($result_value_nik, "JENIS_LOGIN");
			}
			$roweffec_value_nik = oci_num_rows($result_value_nik);
			
			
			/*
			oci_fetch($result_value_nik);
			$roweffec_value_nik = oci_num_rows($result_value_nik);
			$NIKmatch 		= oci_result($result_value_nik, "NIK");
			$Jenis_Login 		= oci_result($result_value_nik, "JENIS_LOGIN");
			//$Number_of_Login 		= oci_result($result_value_nik, "NUMBER_OF_LOGIN");*/
			
			//t_user
			$sql_value_user = "SELECT te.ID_BA_AFD, te.ID_AFD, tu.NUMBER_OF_LOGIN FROM t_user tu 
			inner join t_afdeling te on tu.ID_BA_AFD = te.ID_BA_AFD
			where Login_Name = '$Login_Name' and Passwd = '$Passwd'";
			/*$fetch_arealogin = select_data($con,$sql_value_user);
			$arealogin = $fetch_arealogin['ID_BA_AFD']; */
			
			$result_value_user = oci_parse($con, $sql_value_user);
			oci_execute($result_value_user, OCI_DEFAULT);
			oci_fetch($result_value_user);
			$roweffec_value_user = oci_num_rows($result_value_user);
			$arealogin 		= oci_result($result_value_user, "ID_BA_AFD");  
			//$arealoginBA 			= oci_result($result_value_user, "ID_BA"); //GANTI ID_BA_AFD ke ID_BA
			$ID_AFD 		= oci_result($result_value_user, "ID_AFD");
			$Number_of_Login 		= oci_result($result_value_user, "NUMBER_OF_LOGIN");

					//jika yang di input ada di database
					if($result_value_nik && $roweffec_value_nik > 0 && $result_value_user && $roweffec_value_user > 0){
						
						$sql_matcharea_user = "SELECT * FROM t_employee te 
						where ID_BA_AFD = '$arealogin' and NIK = '$NIK'";
						//GANTI ID_BA_AFD ke ID_BA
						/*$sql_matcharea_user = "SELECT * FROM t_employee te 
						inner join t_afdeling ta on te.ID_BA_AFD = ta.ID_BA_AFD
						where ID_BA = '$arealoginBA' and NIK = '$username'";*/
						$roweffec_matcharea_user = select_data($con,$sql_matcharea_user);
						
						if($roweffec_matcharea_user > 0){
							for($x = 0 ; $x < $roweffec_value_nik ; $x++){
							//jika jenis loginnya 2, 3 dan 4, select dari table employee
									if($Jenis_Login[$x] == 2 || $Jenis_Login[$x] == 3)	{
									$countNumlog = $Number_of_Login +1;
									
										$sql_empname = "SELECT te.Emp_Name, ta.ID_BA as subID_BA_Afd 
										FROM t_employee te
										INNER JOIN T_AFDELING ta on te.ID_BA_AFD = ta.ID_BA_AFD
										where te.NIK = '$NIK' and te.ID_BA_Afd = '$arealogin'" ;
										//GANTI ID_BA_AFD ke ID_BA	
										/*$sql_empname = "SELECT te.Emp_Name, ta.ID_BA as subID_BA_Afd 
										FROM t_employee te
										INNER JOIN T_AFDELING ta on te.ID_BA_AFD = ta.ID_BA_AFD
										where te.NIK = '$NIK' and ta.ID_BA = '$arealoginBA'" ;*/
										$result_empname = oci_parse($con, $sql_empname);
										oci_execute($result_empname, OCI_DEFAULT);
										//echo $sql_empname;
										//	break;
										
										//jika table employee ada isinya
										if (oci_fetch($result_empname)){
											$sql_dual = "select SEQ_RL.nextval as List_No from dual";
											$result_dual = oci_parse($con, $sql_dual);
											oci_execute($result_dual, OCI_DEFAULT);
											oci_fetch($result_dual);
											$List_No 		= oci_result($result_dual, "LIST_NO");
					
											//echo $sql_dual;
											
											//insert t_relasi_login
											$sql_insert_t_relasi_login = "INSERT INTO t_relasi_login (List_No, Login_Name, NIK, Tgl_Login, Jam_Login) 
											VALUES ('$List_No', '$Login_Name', '$NIK', to_date(sysdate, 'DD/MM/YYY'), sysdate )";
											$rs_insert_t_relasi_login = insert_data($con,$sql_insert_t_relasi_login);
											
											//update_t_nik_passwd
											$sql_update_t_nik_passwd = "UPDATE t_user SET Number_of_Login = '$countNumlog' WHERE Login_Name = '$Login_Name'";
											$rs_update_t_nik_passwd = update_data($con,$sql_update_t_nik_passwd);
												
											commit($con);
													
											//selectid_t_relasi_login
											$sql_selectid_t_relasi_login = "SELECT to_char(Tgl_Login, 'YYYY-MM-DD') as TGL_LOGIN, to_char(Jam_Login, 'HH24:MI:SS') as JAM_LOGIN
											FROM t_relasi_login
											WHERE List_No = '$List_No' ";
											/*$fetch_selectid_t_relasi_login = select_data($con,$sql_selectid_t_relasi_login);
											$Tgl_Login 		= $fetch_selectid_t_relasi_login["TGL_LOGIN"];
											$Jam_Login 		= $fetch_selectid_t_relasi_login["JAM_LOGIN"];*/
											$result_selectid_t_relasi_logi = oci_parse($con, $sql_selectid_t_relasi_login);
											oci_execute($result_selectid_t_relasi_logi, OCI_DEFAULT);
											oci_fetch($result_selectid_t_relasi_logi);
											$Tgl_Login 		= oci_result($result_selectid_t_relasi_logi, "TGL_LOGIN");
											$Jam_Login 		= oci_result($result_selectid_t_relasi_logi, "JAM_LOGIN");		
											
											//echo $sql_selectid_t_relasi_login;
											//break;
											
											$subID_BA_Afd 		= oci_result($result_empname, "SUBID_BA_AFD");	
											$response["success"] = 1;
											$response["message"] = "Login success";
											$response["ID_BA"] = $subID_BA_Afd;
											$response["ID_BA_Afd"] = $arealogin;
											$response["ID_Afd"] = $ID_AFD;
											$response["Number_of_Login"] = $Number_of_Login;
											$response["Jenis_Login"] = $Jenis_Login[0];
											$response["Tgl_Login"] = $Tgl_Login;
											$response["Jam_Login"] = $Jam_Login;
												
											echo json_encode($response);
											
										}// close if ($fetch_empname = mysql_fetch_array($sql_empname))
										else{
											$response["success"] = 0;
											$response["message"] = "NIK ".$NIK." not a member of ".$arealogin."Please input valid NIK and Login Name";
											rollback($con);
											echo json_encode($response);
										}
									}// close if($Jenis_Login == 2 || $Jenis_Login == 3)
									else{
										$response["success"] = 0;
										$response["message"] = "You don't have authority";
										rollback($con);
										echo json_encode($response);
									}
							}//close for
						}
						else{
							$response["success"] = 0;
							$response["message"] = "Login Name does not match with Username";
							echo json_encode($response);
						}
					}//close if ($result_nik && $result_arealogin)		
					else{
						$response["success"] = 0;
						$response["message"] = "Please check your NIK and Password - ".$NIK." ".$Passwd;
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
    $response["message"] = "Required username and password";
    echo json_encode($response);
} 
?>