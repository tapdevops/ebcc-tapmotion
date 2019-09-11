<?php
session_start();

if(isset($_POST["ID_GROUP_BA"])){
	
include("../config/SQL_function.php");
include("../config/db_connect.php");
$con = connect();

$ID_GROUP_BA = $_POST["ID_GROUP_BA"];
//ECHO $ID_GROUP_BA;
	if($ID_GROUP_BA == "")
	{
		$_SESSION["IDGroupView"] = TRUE;
		$_SESSION[err] = "ID_GROUP_BA kosong";
		header("Location:daftargroupba.php");
	}
	else
	{
		$sql_delete_t_alternate_ba_group = "delete from t_alternate_ba_group where ID_GROUP_BA= '$ID_GROUP_BA'";
		$result_delete = delete_data($con,$sql_delete_t_alternate_ba_group);
		
		$sql_delete_t_group_ba = "delete from t_group_ba where ID_GROUP_BA= '$ID_GROUP_BA'";
		$result_delete = delete_data($con,$sql_delete_t_group_ba);
		
		commit($con);
		$_SESSION["IDGroupView"] = TRUE;
		header("Location:daftargroupba.php");	
	}
}
else{	
	$_SESSION[err] = "Pilih salahsatu data untuk dihapus";
	$_SESSION["IDGroupView"] = TRUE;
	header("Location:daftargroupba.php");
}

?>