
<?php

session_start();
if(isset($_SESSION["LoginEmployee"]) && isset($_POST["Pass"]) && isset($_SESSION["NIK"]) && isset($_SESSION["LoginName"])){
	
include("../config/SQL_function.php");
include("../config/db_connect.php");
$con = connect();

$Login_Name = $_SESSION["LoginName"];
$NIK 		= $_SESSION["NIK"];
$LoginEmployee= $_SESSION["LoginEmployee"];
$Pass 		= $_POST["Pass"];

$action = true;
	if($LoginEmployee == ""){
		$_SESSION[err] = "Login Name tidak boleh kosong";
		header("Location:changepass.php");
	}
	else{
	
		$sql_old_pass  = "SELECT PASSWD
		FROM t_user 
		WHERE LOGIN_NAME = '$LoginEmployee'";
		$result_old_pass = oci_parse($con, $sql_old_pass);
		oci_execute($result_old_pass, OCI_DEFAULT);
		while(oci_fetch($result_old_pass)){
			$old_Pass[]		= oci_result($result_old_pass, "PASSWD");
		}
		
		/*$sql_update_pass = "UPDATE t_user
		SET passwd='$Pass'
		WHERE LOGIN_NAME='$LoginEmployee'";
		$update_pass = update_data($con,$sql_update_pass); */
		
		$sql_t_nik_password = "select count(*) as HITUNG from t_user where LOGIN_NAME='$LoginEmployee'";
		$result_t_nik_password = select_data($con,$sql_t_nik_password);
		
		if($result_t_nik_password["HITUNG"] > 0){
			$insert[$ctr] = $ctr."LOGIN_NAME: ".$LoginEmployee." already on table t_user and updated";
			$sql_t_NP = "UPDATE t_user SET passwd='$Pass' WHERE LOGIN_NAME='$LoginEmployee'";
			$update_pass = num_rows($con, $sql_t_NP);
			
			$sql_log_t_NP = "INSERT INTO t_log_nik_passwd (
			InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, New_Value_Pass, Old_Value_Pass, CreEdit_From, Sync_Server, ON_NIK) 
			VALUES
			('UPDATE', SYSDATE , '$NIK', '$Login_Name', 't_user', '$Pass', '$old_Pass[0]', 'Website', SYSDATE, '$LoginEmployee')";
			$log_update_pass = num_rows($con,$sql_log_t_NP);
		}
		else{
			$sql_t_NP = "INSERT INTO t_user
			(LOGIN_NAME, PASSWD, NUMBER_OF_LOGIN, JENIS_LOGIN) 
			VALUES
			('$LoginEmployee', '$Pass', 0, 0)";
			$update_pass = num_rows($con,$sql_t_NP);
			
			$sql_log_t_NP = "INSERT INTO t_log_nik_passwd (
			InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, New_Value_Pass, CreEdit_From, Sync_Server, ON_NIK) 
			VALUES
			('INSERT', SYSDATE , '$NIK', '$Login_Name', 't_user', '$Pass', 'Website', SYSDATE, '$LoginEmployee')";
			$log_update_pass = num_rows($con,$sql_log_t_NP);
		}
		   
		   //echo $sql_t_NP.$update_pass."<br>". $sql_log_t_NP.$log_update_pass ;
		if($update_pass > 0 && $log_update_pass > 0)
		{
			commit($con);
			//unset($_SESSION["ChangePassword"]);
			$_SESSION[err] = "Password berhasil di update";
			header("Location:http:changepass.php");
		}
		else
		{
			rollback($con);
			$_SESSION[err] = "Password gagal di update";
			header("Location:http:changepass.php");
		}
		
		$_SESSION["ctr"] = $ctr;
		for($y = 0 ; $y < $ctr ; $y++){
			$_SESSION["insert$y"] = $insert[$y];
		} 
		
	}
}
else{
	$_SESSION[err] = "LOGIN_NAME Employee tidak boleh kosong. ".$_SESSION["LoginEmployee"]." # ".$_POST["Pass"]." # ".$_SESSION["NIK"]." # ".$_SESSION["LoginName"];
	 echo '<script type="text/javascript"> alert(\'Hi\'); </script>';
	echo "<script>alert (\"js inside php\")</script>";
	header("Location:http:changepass.php");
	
    	
}
?>