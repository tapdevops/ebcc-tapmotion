<?php
ini_set('display_errors',true);
error_reporting(-1);
		include("../config/SQL_function.php"); 
		include("../config/db_config.php");
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
		
$book_name = $_POST['book_name'];
$test = $_POST['test'];
echo $test; die();
$sql_t_PID  = "SELECT ID_RENCANA FROM T_HASIL_PANEN WHERE NO_TPH = '$book_name'";

$result_t_PID = oci_parse($con, $sql_t_PID);
oci_execute($result_t_PID);
					
while (oci_fetch_array($result_t_PID)) {	
		$id_rencana = oci_result($result_t_PID, "ID_RENCANA");
		echo $id_rencana;
}


?>

