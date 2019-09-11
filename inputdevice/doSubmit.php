<?php
session_start();


if(isset($_POST['merk']) || isset($_POST['type']) || isset($_POST['imei']) ||  isset($_POST["yy"])){
	
	$id_ba = $_POST['kota'];
	$id_cc = $_POST["propinsi"];
	$merk = $_POST['merk'];
	$tipe = $_POST['tipe'];
	$imei = $_POST['imei'];
	$nik1 = $_POST['id_kec'];
	$nik2 = $_POST['id_kec'];
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
		
		$sql_dual = "select SEQ_DEVICE.nextval as List_No from dual";
		$result_dual = oci_parse($con, $sql_dual);
		oci_execute($result_dual, OCI_DEFAULT);
		oci_fetch($result_dual);
		$List_No 		= oci_result($result_dual, "LIST_NO");
	
		//cek data yang diinput sama atau tidak
		$sql_select = "select * from T_DEVICE where IMEI= '$imei'";
		$roweffec_select = select_data($con,$sql_select);
		
	//	echo $List_No;
		//echo $roweffec_select;
		//die;
	//	
		if($roweffec_select ==0){
		$sql_value_t_log_nab = "INSERT INTO T_DEVICE 
				(ID_DEV, ID_BA, ID_CC, MERK, TIPE, IMEI, NIK1, NIK2, TGL_IN) 
				VALUES
				('$List_No', '$id_ba', '$id_cc', '$merk', '$tipe', '$imei',
				'$nik1', '$nik2', SYSDATE)" ;
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