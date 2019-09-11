<?php
session_start();

if(isset($_POST["date1"]) && isset($_POST["date2"]) && isset($_SESSION["NIK"])){
	
include("../config/SQL_function.php");
include("../config/db_connect.php");
$con = connect();

$date1 = date("Y-m-d", strtotime($_POST["date1"]));
$date2 = date("Y-m-d", strtotime($_POST["date2"]));
$NIK = $_SESSION["NIK"];
$ID_BA = $_POST["BA"];

	if($NIK == ""){
		$_SESSION[err] = "Created By Empty";
		$_SESSION["IDGroupView"] = TRUE;
		header("Location:daftargroupba.php");
	}
	else{
		if($date2 == '1970-01-01')
		{
			$date2 = null;
		}
		if($date1 == "1970-01-01" && $date2 != "1970-01-01"){
			$_SESSION[err] = "Please fill start date";
			$_SESSION["IDGroupView"] = TRUE;
			header("Location:daftargroupba.php");
		}
		//tak ada yg diisi
		else if($date1 == "1970-01-01" && $date2 == "1970-01-01"){
			$q = "";
		}
		//date1 & 2 diisi
		else if($date1 != "1970-01-01" && $date2 != "1970-01-01"){
			$q = "WHERE to_char(a.created_date,'YYYY-MM-DD') between '$date1' and NVL('$date2', '$date1')";
		}
		
		if($ID_BA == "ALL")
		{
			$ba = "";
		}
		else
		{
			$ba = "and b.ID_BA = '$ID_BA'";
		}
		$sql_t_groupba = "select a.id_group_ba,a.group_name,a.created_date,a.created_by,a.start_date,a.end_date from t_group_ba a, t_alternate_ba_group b 
		$q and a.id_group_ba=b.id_group_ba $ba
		group by a.id_group_ba,a.group_name,a.created_date,a.created_by,a.start_date,a.end_date
		order by a.id_group_ba";
		
		//echo $sql_t_groupba;
		//die;
		
		//$sql_t_groupba  = "SELECT * FROM t_group_ba a, t_alternate_ba_group b $q and a.id_group_ba=b.id_group_ba $ba order by a.ID_GROUP_BA";
		
		$_SESSION["sql_t_groupba"] 		= $sql_t_groupba;
		$_SESSION["IDGroupView"] = TRUE;
		header("Location:daftargroupba.php");
	}
	
}
else{
	$_SESSION[err] = "Pilih Company Code, Business Area, atau Job Authorization Code";
	$_SESSION["IDGroupView"] = TRUE;
	header("Location:daftargroupba.php");
}

?>