<?php
session_start();

if(isset($_POST['jabatan'])){
//print_r($_POST);die();
	
	$jab = $_POST['jabatan'];
	$id_ba = $_POST['kota'];
	$id_cc = $_POST["propinsi"];
	$nik = $_POST["NIK"];
	$nama = $_POST["USER"];
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
	$LoginName 	= $_SESSION['Name'];

		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		
/*		$dbsql = new Database;
		
		$query_master = "select Employee_NIK, Employee_FullName
						FROM M_Employee 
						where Employee_Location <> 'HEAD OFFICE' 
						and Employee_PositionCode in ('0000000184','0000000123','0000000272','0000000277') 
						and Employee_ResignDate is null";
		$dbsql->query($query_master);
		while($dbsql->nextRecord()){
			$nik = $dbsql->Record['Employee_NIK'];
			$nama = $dbsql->Record['Employee_FullName'];
			echo $nik. " ".$nama."<br>"; 
		}*/

/*		$sql_dual = "select SEQ_DEVICE.nextval as List_No from dual";
		$result_dual = oci_parse($con, $sql_dual);
		oci_execute($result_dual, OCI_DEFAULT);
		oci_fetch($result_dual);
		$List_No 		= oci_result($result_dual, "LIST_NO");*/
		
		$query_jabatan = "SELECT AUTHORITY_NAME FROM T_AUTHORITY WHERE AUTHORITY = $jab";
		$result_jabatan = oci_parse($con, $query_jabatan);
		oci_execute($result_jabatan, OCI_DEFAULT);
		while (oci_fetch_array($result_jabatan)) {
			$id_jabatan = oci_result($result_jabatan, "AUTHORITY_NAME");
		}
		
		//$jabatan = select_data($con,$query_jabatan);		
		
		//cek data yang diinput sama atau tidak
		$query_nik = "SELECT count(NIK) NIK FROM T_EMPLOYEE where NIK = '$nik'";
		$result_nik = oci_parse($con, $query_nik);
		oci_execute($result_nik, OCI_DEFAULT);
		while (oci_fetch_array($result_nik)) {
			$emp_id = oci_result($result_nik, "NIK");
		}
		
		//print_r($query_insertemp);die();
	//	echo $List_No;
		//echo $roweffec_select;
		//die;
	//	
		if($emp_id ==0){
		$query_insertemp = "INSERT INTO T_EMPLOYEE (NIK, EMP_NAME, JOB_TYPE, JOB_CODE, ID_BA_AFD, ID_JOBAUTHORITY)
			VALUES ('$nik', '$nama', '-', '$id_jabatan', '$id_ba-', '$id_ba.$id_jabatan')";
			$result_insertemp = num_rows($con,$query_insertemp);
			//print_r($query_insertemp);die();
			//commit($con);
			//$_SESSION["err"] = "Data inserted";
			//echo $sql_value_t_log_nab;
			//die ();
		   //header("Location:inputdevice.php");	
		   
		$query_insertjob = "INSERT INTO T_JOBAUTHORITY (ID_BA, ACTIVITY_CODE, JOB_CODE, ID_JOBAUTHORITY, AUTHORITY, CREATED_DATE, CREATED_BY, ID_CC)
			VALUES ($id_ba, NULL, '$id_jabatan', '$id_ba.$id_jabatan', $jab, sysdate, '$LoginName', '$id_cc')";
			//print_r($query_insertjob);die();
			$result_insertjob = num_rows($con,$query_insertjob);
			//commit($con);
			//$_SESSION["err"] = "Data inserted";
			//echo $sql_value_t_log_nab;
			//die ();
		   //header("Location:inputdevice.php");	
		   
		$query_insertuser = "INSERT INTO T_USER (LOGIN_NAME, ID_BA_AFD, PASSWD, NUMBER_OF_LOGIN, IPIMAGE)
					VALUES ('$id_ba.$id_jabatan', '$id_ba-', '12345', '0', '-')";
		
			//print_r($query_insertuser);die();
			$result_insertuser = num_rows($con,$query_insertuser);
			commit($con);
			$_SESSION["err"] = "Data inserted";
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