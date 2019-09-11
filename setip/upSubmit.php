<?php
session_start();


if(isset($_POST['merk']) || isset($_POST['type']) || isset($_POST['imei']) ||  isset($_POST["yy"])){

	$id_dev = $_POST['id_dev'];
	$id_ba = $_POST['kota'];
	$id_cc = $_POST["propinsi"];
	$merk = $_POST['merk'];
	$tipe = $_POST['tipe'];
	$imei = $_POST['imei'];
	$nik1 = $_POST['yy'];
	$nik2 = $_POST['yy'];
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

        
		//if ($nik1==$nik2)  {$nik1=$nik2;}
		//cek data yang diinput sama atau tidak
		$sql_select = "select * from T_DEVICE where IMEI = '$imei'";
		$result_select = oci_parse($con, $sql_select);
		oci_execute($result_select, OCI_DEFAULT);
		$roweffec_select = oci_num_rows($result_select);
		
		//echo $sql_select; die ();
	///	print_r($result_select = oci_parse($con, $sql_select));
		//echo $roweffec_select; die;
	//	
		if($roweffec_select ==0){
		$sql_value_t_log_nab = "UPDATE T_DEVICE 
				SET ID_BA='$id_ba', ID_CC='$id_cc', MERK='$merk', TIPE='$tipe', IMEI='$imei', NIK1='$nik1', 
				NIK2='$nik1', TGL_UP=SYSDATE WHERE ID_DEV='$id_dev'" ;
				//echo $sql_value_t_log_nab;
				//die ();
				$result_value_t_log_nab = num_rows($con,$sql_value_t_log_nab);
				commit($con);
				$_SESSION["err"] = "Data updated";
				//echo $sql_value_t_log_nab;
				//die ();
			   header("Location:inputdevice.php");	
		}

		else{
		
			  $_SESSION["err"] = "You input same data. No data updated";
			 
			   header("Location:inputdevice.php");		
		}
}
else{
$_SESSION["err"] = "Data yang diinput tidak lengkap";
header("Location:inputdevice.php");
}


?>