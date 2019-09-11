<?php
session_start();

if(isset($_POST["Afdeling"]) || isset($_POST["date1"]) || isset($_POST["date2"]) || isset($_POST["date3"]) || isset($_POST["editNO_NAB"]) || isset($_SESSION["sql_t_NABminOrder"]) || isset($_SESSION["NABOrder"])){
	$valueAfdeling 		= $_POST["Afdeling"];
	$date1 	= $_POST["date1"];
	$date2 	= $_POST["date2"];
	$ID_BA 		= $_SESSION['subID_BA_Afd'];
	$ID_CC 		= $_SESSION['subID_CC'];	
	$editNO_NAB = $_POST["editNO_NAB"];
	$sql_t_NABminOrder = $_SESSION["sql_t_NABminOrder"];
	$Order 		= $_SESSION["NABOrder"];
	//include("Filter/query2.php");
	
	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();
	
	if($date1 == "0000-00-00"){
		$_SESSION[err] 		= "please choose date". $date1. $date2;	
		header("Location:KoreksiNABFil.php");
	}

	else{
			$sql_t_NAB = $sql_t_NABminOrder." and tn.id_nab_tgl = '$editNO_NAB' ".$Order;
			$_SESSION["sql_t_NAB"] 		= $sql_t_NAB;	
			$_SESSION["editNO_NAB"] 	= $editNO_NAB;	
			//echo $sql_t_BCC;
			header("Location:KoreksiNABSelect.php");
	}
}
else{
$_SESSION[err] = "Please choose the options";
header("Location:KoreksiNABFil.php");
}
?>