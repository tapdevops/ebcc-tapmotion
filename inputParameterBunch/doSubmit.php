<?php
session_start();

	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();
	
	$ba = $_POST['kota'];
	$comp = $_POST["propinsi"];
	$bunch = $_POST["bunch"];
	$harvest = $_POST["t_harvest"];
	$t_iuser = $_POST["t_iuser"];
	$t_itime = $_POST["t_itime"];
	$t_duser = $_POST["t_duser"];
	$t_dtime  = $_POST["t_dtime"];
	$send = $_POST["t_send"];
	$paid = $_POST["t_paid"];
	
	$NIK 			= $_SESSION['NIK'];
	$Login_Name 	= $_SESSION['LoginName'];
	$LoginName 		= $_SESSION['Name'];
	 
	$delHarvest = "DELETE FROM T_PARAMETER_BUNCH 
					WHERE BA_CODE = '".$ba."' 
					AND KETERANGAN = '".$bunch."'";					
	$res_delHarvest = num_rows($con, $delHarvest);
						
	for ($i=0; $i < count($harvest); $i++){
			if ($t_iuser[$i] == ''){
				$usrIns = $LoginName;
			}else{
				$usrIns = $t_iuser[$i];
			}
		
			if ($t_itime[$i] == ''){
				$insTime = "SYSDATE";
			}else{
				$insTime = "'".$t_itime[$i]."'";
			}
		$query_inserbunch = "INSERT INTO T_PARAMETER_BUNCH (BA_CODE, 
									ID_KUALITAS, 
									KETERANGAN, 
									INSERT_USER, 
									INSERT_TIME, 
									UPDATE_USER, 
									UPDATE_TIME,
									DELETE_USER,
									DELETE_TIME
									)
							VALUES ('".$ba."', 
									'".$harvest[$i]."',
									'".$bunch."', 
									'".$usrIns."', 
									".$insTime.", 
									'".$LoginName."', 
									SYSDATE,
									'".$t_duser[$i]."', 
									'".$t_dtime[$i]."'
									)";
			$result_insertemp = num_rows($con, $query_inserbunch);
			
	}
	  
	commit($con);
	$_SESSION["err"] = "Data inserted";
	header("Location:inputparameterbunch.php");	
?>