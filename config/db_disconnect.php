<?php
session_start();
if(isset($_SESSION['NIK']) && isset($_SESSION['Last_Number_List'])){
	include("SQL_function.php");
	include("db_connect.php");
	$con = connect();
	$NIK 				= $_SESSION['NIK']; 
	$Last_Number_List	= $_SESSION['Last_Number_List'];
	$sql_value = "UPDATE t_relasi_login SET TGL_LOGOUT = to_date(sysdate, 'DD.MM.YYY'), JAM_LOGOUT = SYSDATE WHERE NIK = '$NIK' AND LIST_NO = '$Last_Number_List'";
	$roweffec_value = num_rows($con,$sql_value);
	echo $sql_value;
	commit($con);
	header("Location:Run_db_disconnect.php");
}
else{
	echo $_SESSION['NIK']."-". $_SESSION['Last_Number_List'];
	header("Location:Run_db_disconnect.php");
}
?>