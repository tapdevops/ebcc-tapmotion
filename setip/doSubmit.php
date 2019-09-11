<?php
session_start();


if(isset($_POST['ip'])){
	
	$id_ba = $_POST['kota'];
	$id_cc = $_POST["propinsi"];
	$ip = $_POST['ip'];
	$afd = $_POST['id_kec'];
	$baafd =$id_ba.$afd;
	
//	echo $baafd;
	//die;

//	$nik1 =split(':',$_POST['Nama_TM1']);
	//$nik2 =split(':',$_POST['Nama_TM2']);
	
	//
	//print_r($_POST);
//die ();
	                 
	$NIK 			= $_SESSION['NIK'];
	$Login_Name 	= $_SESSION['LoginName'];

		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		

	
		//cek data yang diinput sama atau tidak
		//$sql_select = "select * from T_USER where ID_BA_AFD= '$baafd'";
		//$roweffec_select = select_data($con,$sql_select);
		
	//	echo $List_No;
		//echo $roweffec_select;
		//die;
	//	
		if ($afd=='-All-'){
		$sql_value_t_log_nab = "UPDATE T_USER SET IPIMAGE='http://$ip/ebcc/array/' WHERE SUBSTR(ID_BA_AFD,1,4)='$id_ba'" ;
				//echo $sql_value_t_log_nab;
				//die ();
				$result_value_t_log_nab = num_rows($con,$sql_value_t_log_nab);
				commit($con);
				$_SESSION["err"] = " All Data updated";
				//echo $sql_value_t_log_nab;
				//die ();
			   header("Location:inputip.php");	
		}

		else{
			$sql_value_t_log_nab = "UPDATE T_USER SET IPIMAGE='http://$ip/ebcc/array/' WHERE ID_BA_AFD='$baafd'" ;
				//echo $sql_value_t_log_nab;
				//die ();
				$result_value_t_log_nab = num_rows($con,$sql_value_t_log_nab);
				commit($con);
				$_SESSION["err"] = "Data updated";
				//echo $sql_value_t_log_nab;
			  
			 
			   header("Location:inputip.php");		
		}
}
else{
$_SESSION["err"] = "Data yang diinput tidak lengkap";
header("Location:inputip.php");
}


?>