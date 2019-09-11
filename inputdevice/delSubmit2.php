<?php
session_start();


if(isset($_GET['id'])){

	$id_dev = $_GET['id'];

	//print_r($_GET);
	//die ();
	                 


		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();

 
		$sql_value_t_log_nab = "DELETE T_DEVICE  WHERE ID_DEV='$id_dev'" ;
				//echo $sql_value_t_log_nab;
				//die ();
				$result_value_t_log_nab = num_rows($con,$sql_value_t_log_nab);
				commit($con);
				$_SESSION["err"] = "Data deleted";
				//echo $sql_value_t_log_nab;
				//die ();
			   header("Location:inputdevice.php");	
	
}
else{
$_SESSION["err"] = "Data yang diinput tidak lengkap";
header("Location:inputdevice.php");
}


?>