<?php session_start();

if(isset($_POST["LoginName"]) && isset($_POST["Usrname"]) && isset($_POST["Passwd"])){
include("config/SQL_function.php");
//require_once __DIR__ . '/db_config.php'; 
//$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
include("config/db_connect.php");
$con = connect();

	$loginname = $_POST["LoginName"];
	$username = $_POST["Usrname"];
	$password = $_POST["Passwd"];
	
	/*$loginname = "2121-A-KB1";
	$username = "21/2121/0104/188";
	$password = "12345";*/
	
	//echo $username.$password;die();
	//$enc = md5($password);	
	
//$sql = "SELECT * FROM table_user where where username = '$username' and password = '$enc'";

	if($username == "")						// validasi-validasi bila textfield / inputan tak diisi
	{
		$_SESSION[err] = "Please fill Username";
		header("Location:index.php");
	}
	
	else if($password == "")
	{
		$_SESSION[err] = "Please fill Password";
		header("Location:index.php");
	}
	
	else if($loginname == "")
	{
		$_SESSION[err] = "Please fill Login Name";
		header("Location:index.php");
	}

	else
	{
		//$enc = md5($password);		//untuk meng-md5-kan password
		//mengecek apakah data inputan ada di database
		//t_nik_passwd
		$sql_t_nik_passwd = "SELECT NIK, Number_of_Login, Jenis_Login FROM t_nik_passwd WHERE NIK = '$username'";		
		$rs_t_nik_passwd = oci_parse($con, $sql_t_nik_passwd);
		oci_execute($rs_t_nik_passwd, OCI_DEFAULT);
		while(oci_fetch($rs_t_nik_passwd)){
			$Jenis_Login[] 		= oci_result($rs_t_nik_passwd, "JENIS_LOGIN");
		}
		$roweffec_t_nik_passwd = oci_num_rows($rs_t_nik_passwd);
		
		//t_user
		$sql_t_user = "SELECT * FROM t_user tu 
		inner join t_afdeling ta on tu.ID_BA_AFD = ta.ID_BA_AFD
		WHERE Login_Name = '$loginname' and Passwd = '$password'";		
		$rs_t_user = oci_parse($con, $sql_t_user);
		oci_execute($rs_t_user, OCI_DEFAULT);
		oci_fetch($rs_t_user);
		$roweffec_t_user 	= oci_num_rows($rs_t_user);
		$arealogin 			= oci_result($rs_t_user, "ID_BA_AFD");
		$arealoginBA 			= oci_result($rs_t_user, "ID_BA");  //GANTI ID_BA_AFD ke ID_BA
		$ID_AFD 			= oci_result($rs_t_user, "ID_AFD");
		$Number_of_Login 	= oci_result($rs_t_user, "NUMBER_OF_LOGIN");
		
        //echo $sql_t_user; die();
		if($rs_t_nik_passwd && $roweffec_t_nik_passwd > 0 && $rs_t_user && $roweffec_t_user > 0)			//bila data inputan ada dan benar seperti di database, maka dapat masuk ke web
		{
			/*$sql_matcharea_user = "SELECT * FROM t_employee te 
			where ID_BA_AFD = '$arealogin' and NIK = '$username'"; */
			//GANTI ID_BA_AFD ke ID_BA
			$sql_matcharea_user = "SELECT * FROM t_employee te 
			inner join t_afdeling ta on te.ID_BA_AFD = ta.ID_BA_AFD
			where ID_BA = '$arealoginBA' and NIK = '$username'";
			$roweffec_matcharea_user = select_data($con,$sql_matcharea_user);
			
			//echo $sql_matcharea_user."<br>";			
			if($roweffec_matcharea_user > 0){
			
				//fetch t_nik_passwd			
				//$fetch_t_nik_passwd = oci_fetch_array($rs_t_nik_passwd);
				for($x = 0 ; $x < $roweffec_t_nik_passwd ; $x++){
				  //  echo $roweffec_t_nik_passwd; die;
					
					if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 10|| $Jenis_Login[$x] == 0){
					$countNumlog = $Number_of_Login +1;
					
					//insert t_relasi_login
					$sql_insert_t_relasi_login = "INSERT INTO t_relasi_login (List_No, Login_Name, NIK, Tgl_Login, Jam_Login) VALUES (SEQ_RL.nextval ,'$loginname', '$username', to_date(sysdate, 'DD.MM.YYY'), SYSDATE)";
					$rs_insert_t_relasi_login = insert_data($con,$sql_insert_t_relasi_login);
				//	echo "sini".$sql_insert_t_relasi_login."<br>"; die();
					//selectid_t_relasi_login
					$sql_selectid_t_relasi_login = "SELECT List_No
					FROM t_relasi_login
					WHERE Login_Name =  '$loginname' and NIK =  '$username'
					and rownum = 1 ORDER BY List_No DESC";
					$rs_selectid_t_relasi_login = oci_parse($con, $sql_selectid_t_relasi_login);
					oci_execute($rs_selectid_t_relasi_login, OCI_DEFAULT);
					$fetch_selectid_t_relasi_login = oci_fetch_array($rs_selectid_t_relasi_login);
					$Last_Number_List 		= $fetch_selectid_t_relasi_login["LIST_NO"];
					// echo $sql_selectid_t_relasi_login."<br>".$Last_Number_List."<br>";
					//update_t_nik_passwd
					$sql_update_t_nik_passwd = "UPDATE t_user SET Number_of_Login = '$countNumlog' WHERE Login_Name = '$loginname'";
					$rs_update_t_nik_passwd = update_data($con,$sql_update_t_nik_passwd);
		 //echo $sql_update_t_nik_passwd."<br>".$countNumlog."<br>"; die();
					//t_employee
					$sql_t_employee = "SELECT EMP_NAME, JOB_CODE, ta.id_afd, ta.id_ba as SUBID_BA_AFD, tb.ID_CC as SUBID_CC 
					FROM t_employee te
					INNER JOIN t_afdeling ta on te.id_ba_afd = ta.id_ba_afd 
					INNER JOIN t_bussinessarea tb on ta.id_ba = tb.id_ba 
					WHERE te.NIK = '$username'";
					//echo $sql_t_employee; die();
					$rs_t_employee = oci_parse($con, $sql_t_employee);
					oci_execute($rs_t_employee, OCI_DEFAULT);
					oci_fetch($rs_t_employee);
					$Job_Code 	= oci_result($rs_t_employee, "JOB_CODE");
					$Emp_Name 	= oci_result($rs_t_employee, "EMP_NAME");
					$subID_BA_Afd 	= oci_result($rs_t_employee, "SUBID_BA_AFD");
					$ID_AFD 	= oci_result($rs_t_employee, "ID_AFD");
					$subID_CC 	= oci_result($rs_t_employee, "SUBID_CC");
					// echo $sql_t_employee;
					//t_companycode
					$sql_t_CC = "SELECT Comp_Name FROM t_companycode WHERE ID_CC = '$subID_CC'";
					$rs_t_CC = oci_parse($con, $sql_t_CC);
					oci_execute($rs_t_CC, OCI_DEFAULT);
					oci_fetch($rs_t_CC);
					$Comp_Name 	= oci_result($rs_t_CC, "COMP_NAME");
					//echo $sql_t_CC; die();
					$sql_ID_Group_BA  = "SELECT ID_Group_BA 
					FROM t_alternate_ba_group
					WHERE ID_BA = '$subID_BA_Afd'";
					//$result_ID_Group_BA = mysql_query($sql_ID_Group_BA);
					$rs_ID_Group_BA = oci_parse($con, $sql_ID_Group_BA);
					oci_execute($rs_ID_Group_BA, OCI_DEFAULT);
					oci_fetch($rs_ID_Group_BA);
					$ID_Group_BA = oci_result($rs_ID_Group_BA, 'ID_GROUP_BA');
					//echo $sql_ID_Group_BA; die;
					//get date
					$sql_datetime = "SELECT TO_CHAR(SYSDATE, 'DD Month YYYY') as DT FROM DUAL";
					$rs_datetime = oci_parse($con, $sql_datetime);
					oci_execute($rs_datetime, OCI_DEFAULT);
					oci_fetch($rs_datetime);
					$Date 	= oci_result($rs_datetime, "DT");
					//echo $sql_datetime;
					/*$Job_Code = $fetch_t_employee["JOB_CODE"];
					$Emp_Name = $fetch_t_employee["EMP_NAME"];
					$subID_BA_Afd = $fetch_t_employee["SUBID_BA_AFD"];*/
					//die ();
					commit($con);
					
						$_SESSION[Job_Code]	= $Job_Code;
						$_SESSION[NIK] 		= $username;
						$_SESSION[LoginName]= $loginname;
						$_SESSION[Name] 	= $Emp_Name;
						$_SESSION[Jenis_Login] 	= $Jenis_Login[0];
						$_SESSION[Comp_Name]	= $Comp_Name;
						$_SESSION[subID_CC]		= $subID_CC;
						$_SESSION[ID_Group_BA]	= $ID_Group_BA;
						$_SESSION[subID_BA_Afd]	= $subID_BA_Afd;
						$_SESSION[subID_Afd]	= $ID_AFD;
						$_SESSION[Last_Number_List]	= $Last_Number_List;
						$_SESSION[Date]	= $Date;
						$_SESSION[Number_Of_Login]	= $Number_of_Login;
						$_SESSION[roweffec_t_nik_passwd]	= $roweffec_t_nik_passwd;
						
						//echo '<pre>'; print_r($_SESSION); echo '</pre>'; die();
						
						$x_true = false;
						
						for($y = 0 ; $y < $roweffec_t_nik_passwd ; $y++){
							$_SESSION["Jenis_LoginHead$y"] = $Jenis_Login[$y];
							if($Jenis_Login[$y] == 4 || $Jenis_Login[$y] == 5 || $Jenis_Login[$y] == 6 || $Jenis_Login[$y] == 8 || $Jenis_Login[$y] == 9 || $Jenis_Login[$y] == 10 || $Jenis_Login[$y] == 0)
							{
								$x_true = true; 
							}
						}
						//echo $x_true; die();
						//$x_true = true;
						 //echo $Number_of_Login; die;
						
						if($x_true == true)
						 // echo $$x_true;
						  //echo "TEST"; die();
						   
						{
							if($Number_of_Login == 0)
							{
								
								$_SESSION["ChangePassword"] = TRUE;
								header("Location:ChangePass/changepass.php");
							}
							else
							{
								header("Location:menu/home.php");
								exit;
							}
						}
						else
						{
							$_SESSION[err] = "not allowed";
							
							header("Location:index.php");	
						}
					}	//close if($Job_Code == "ADMIN")		
					else{
						$_SESSION[err] = "not allowed";
						header("Location:index.php");
					}
				}//close for($x = 0 ; $x < $roweffec_t_nik_passwd ; $x++){
			}//close if($roweffec_matcharea_user > 0){
			else{
				$_SESSION[err] = "Login Name does not match with Username";
				header("Location:index.php");
			}	
		}
		else										//proses login gagal karena data yang dimasukkan salah
		{
			$_SESSION[err] = "Please check your username and password"; //.$sql_t_nik_passwd."<br>".$sql_t_user;
			header("Location:index.php");
		}

	}
}
else{
	$_SESSION[err] = "Pilih Please input username, login name, and password";
	header("Location:index.php");
}
?>