<?php
$response = array();

if (isset($_POST["NIK"]) && isset($_POST["ID_BA"]) && isset($_POST["Login_Name"])) {
	/*
	if (get_magic_quotes_gpc()) {
		$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
		while (list($key, $val) = each($process)) {
			foreach ($val as $k => $v) {
				unset($process[$key][$k]);
				if (is_array($v)) {
					$process[$key][stripslashes($k)] = $v;
					$process[] = &$process[$key][stripslashes($k)];
				} else {
					$process[$key][stripslashes($k)] = stripslashes($v);
				}
			}
		}
		unset($process);
		
$NIK = "51/5121/0408/2";
$Login_Name = "5121-B-KB1";
$subID_BA_Afd = "5121"; */
	
	
$NIK = $_POST['NIK'];
$Login_Name = $_POST['Login_Name']; 
$subID_BA_Afd	= $_POST['ID_BA'];
//$response["success"] = 0;	

	if ($NIK =="" && $Login_Name == "" && $subID_BA_Afd == ""){
		$response["success"] = 0;
		$response["message"] = "NIK, Login name, and ID_BA empty";
		echo json_encode($response);
	}else if($NIK == ""){
		$response["success"] = 0;
		$response["message"] = "NIK empty";
		echo json_encode($response);
	}else if($Login_Name == ""){
		$response["success"] = 0;
		$response["message"] = "Login Name empty";
		echo json_encode($response);
	}else if($subID_BA_Afd == ""){
		$response["success"] = 0;
		$response["message"] = "ID_BA empty";
		echo json_encode($response);
	}else {
$x_stage=1;	
		include("../config/SQL_function.php");
		//require_once __DIR__ . '/db_config.php'; 
		//$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
		include("../config/db_connect.php");
		$con = connect();
$x_stage=2;			
		//select ID_Group_BA 
		$sql_check_user  = "SELECT *
		FROM t_employee
		WHERE NIK = '$NIK'";
		$result_check_user = oci_parse($con, $sql_check_user);
		oci_execute($result_check_user, OCI_DEFAULT);
		while(oci_fetch($result_check_user)){
			$user_ID_JOBAUTHORITY		= oci_result($result_check_user, "ID_JOBAUTHORITY");
		}
		$roweffec_check_user = oci_num_rows($result_check_user);
$x_stage=3;	
		$sql_validasi = "select count(*) as ADA from t_jobauthority where ID_jobauthority = '$user_ID_JOBAUTHORITY' and authority in (2, 3)";
		$result_validasi  = select_data($con,$sql_validasi);
		$validasi 		= $result_validasi["ADA"];
$x_stage=4;		
			//echo $validasi;			
		if($roweffec_check_user > 0){
			if($validasi > 0){ 
				//select ID_Group_BA 
				$sql_ID_Group_BA  = "SELECT  tgb.ID_Group_BA FROM t_alternate_ba_group tabg 
				inner join t_group_ba tgb  on tabg.id_group_ba = tgb.id_group_ba 
				WHERE tabg.ID_BA = '$subID_BA_Afd'";
				$result_ID_Group_BA = oci_parse($con, $sql_ID_Group_BA);
				oci_execute($result_ID_Group_BA, OCI_DEFAULT);
				while(oci_fetch($result_ID_Group_BA)){
					$sel_ID_Group_BA[]		= oci_result($result_ID_Group_BA, "ID_GROUP_BA");
				}
				$roweffec_ID_Group_BA = oci_num_rows($result_ID_Group_BA);
				//echo $sql_ID_Group_BA.$roweffec_ID_Group_BA."<br>";	
$x_stage=5;			
				//untuk DIKIRIM 
				//JIKA ADA GROUP	
				if($roweffec_ID_Group_BA > 0){
					//select base on ID_Group_BA
					//t_group_ba
					$sql_t_group_ba  = "SELECT DISTINCT t1.ID_Group_BA, t1.Group_Name
					FROM t_group_ba t1
					WHERE ID_Group_BA = '$sel_ID_Group_BA[0]'";
					$result_t_group_ba = oci_parse($con, $sql_t_group_ba);
					oci_execute($result_t_group_ba, OCI_DEFAULT);
					while(oci_fetch($result_t_group_ba)){
						$GB_ID_Group_BA[]		= oci_result($result_t_group_ba, "ID_GROUP_BA");
						$GB_Group_Name[]		= oci_result($result_t_group_ba, "GROUP_NAME");
					}
					$roweffec_t_group_ba = oci_num_rows($result_t_group_ba);
					//echo $sql_t_group_ba.$roweffec_t_group_ba."<br>";	
$x_stage=6;		
					//t_alternate_ba_group
					$sql_t_alternate_ba_group  = "SELECT DISTINCT t2.ID_Alt_BA_Group, t2.ID_BA, t2.ID_Group_BA
					FROM t_alternate_ba_group t2
					WHERE ID_Group_BA = '$sel_ID_Group_BA[0]'";
					$result_t_alternate_ba_group = oci_parse($con, $sql_t_alternate_ba_group);
					oci_execute($result_t_alternate_ba_group, OCI_DEFAULT);
					while(oci_fetch($result_t_alternate_ba_group)){								
						$ALT_ID_Alt_BA_Group[]	= oci_result($result_t_alternate_ba_group, strtoupper("ID_Alt_BA_Group"));
						$ALT_ID_BA[]			= oci_result($result_t_alternate_ba_group, strtoupper("ID_BA"));
						$ALT_ID_Group_BA[]		= oci_result($result_t_alternate_ba_group, strtoupper("ID_Group_BA"));
					}
					$roweffec_t_alternate_ba_group = oci_num_rows($result_t_alternate_ba_group);
					//echo $sql_t_alternate_ba_group.$roweffec_t_alternate_ba_group."<br>";	
$x_stage=7;		
					//t_companycode
					$sql_t_companycode  = "SELECT DISTINCT t4.ID_CC, t4.Comp_Name
					FROM t_alternate_ba_group t2
					INNER JOIN t_bussinessarea t3 	ON t2.ID_BA = t3.ID_BA
					INNER JOIN t_companycode t4 	ON t3.ID_CC = t4.ID_CC
					WHERE t2.ID_Group_BA = '$sel_ID_Group_BA[0]'";
					$result_t_companycode = oci_parse($con, $sql_t_companycode);
					oci_execute($result_t_companycode, OCI_DEFAULT);
					while(oci_fetch($result_t_companycode)){
						$CC_ID_CC[]		= oci_result($result_t_companycode, strtoupper("ID_CC"));
						$CC_Comp_Name[]	= oci_result($result_t_companycode, strtoupper("Comp_Name"));
					}
					$roweffec_t_companycode = oci_num_rows($result_t_companycode);
					//echo $sql_t_companycode.$roweffec_t_companycode."<br>";	
$x_stage=8;		
					//t_bussinessarea
					$sql_t_bussinessarea  = "SELECT DISTINCT t3.ID_BA, t3.Nama_BA, t3.ID_Estate, t3.ID_CC
					FROM t_alternate_ba_group t2
					INNER JOIN t_bussinessarea t3 	ON t2.ID_BA = t3.ID_BA
					WHERE t2.ID_Group_BA = '$sel_ID_Group_BA[0]'";
					$result_t_bussinessarea = oci_parse($con, $sql_t_bussinessarea);
					oci_execute($result_t_bussinessarea, OCI_DEFAULT);
					while(oci_fetch($result_t_bussinessarea)){
						$BA_ID_BA[]		= oci_result($result_t_bussinessarea, strtoupper("ID_BA"));
						$BA_Nama_BA[]		= oci_result($result_t_bussinessarea, strtoupper("Nama_BA"));
						$BA_ID_CC[]		= oci_result($result_t_bussinessarea, strtoupper("ID_CC"));
						$BA_ID_Estate[]	= oci_result($result_t_bussinessarea, strtoupper("ID_Estate"));
					}
					$roweffec_t_bussinessarea = oci_num_rows($result_t_bussinessarea);
					//echo $sql_t_bussinessarea.$roweffec_t_bussinessarea."<br>";	
$x_stage=9;		
					//t_afdeling
					$sql_t_afdeling  = "SELECT DISTINCT t5.ID_BA_Afd, t5.ID_Afd, t5.ID_BA
					FROM t_alternate_ba_group t2
					INNER JOIN t_bussinessarea t3 	ON t2.ID_BA = t3.ID_BA
					INNER JOIN t_afdeling t5 	ON t3.ID_BA = t5.ID_BA
					WHERE t2.ID_Group_BA = '$sel_ID_Group_BA[0]'";
					$result_t_afdeling = oci_parse($con, $sql_t_afdeling);
					oci_execute($result_t_afdeling, OCI_DEFAULT);
					while(oci_fetch($result_t_afdeling)){
						$AFD_ID_BA_Afd[]	= oci_result($result_t_afdeling, strtoupper("ID_BA_Afd"));
						$AFD_ID_Afd[]		= oci_result($result_t_afdeling, strtoupper("ID_Afd"));
						$AFD_ID_BA[]		= oci_result($result_t_afdeling, strtoupper("ID_BA"));
					}
					$roweffec_t_afdeling = oci_num_rows($result_t_afdeling);
					//echo $sql_t_afdeling.$roweffec_t_afdeling."<br>";	
$x_stage=10;	
					//t_blok
					$sql_t_blok  = "SELECT DISTINCT t6.ID_Blok, t6.Blok_Name, t6.ID_BA_Afd, t6.ID_BA_Afd_Blok, 
					to_char(t6.Tahun_Tanam, 'YYYY') AS TAHUN_TANAM, 30 AS MAX_TPH
					FROM t_alternate_ba_group t2
					INNER JOIN t_bussinessarea t3 	ON t2.ID_BA = t3.ID_BA
					INNER JOIN t_afdeling t5 	ON t3.ID_BA = t5.ID_BA
					INNER JOIN t_blok t6 		ON t5.ID_BA_Afd = t6.ID_BA_Afd
					WHERE t2.ID_Group_BA = '$sel_ID_Group_BA[0]'";
					$result_t_blok = oci_parse($con, $sql_t_blok);
					oci_execute($result_t_blok, OCI_DEFAULT);
					while(oci_fetch($result_t_blok)){
						$blok_ID_Blok[]			= oci_result($result_t_blok, strtoupper("ID_Blok"));
						$blok_Blok_Name[]		= oci_result($result_t_blok, strtoupper("Blok_Name"));
						$blok_ID_BA_Afd[]		= oci_result($result_t_blok, strtoupper("ID_BA_Afd"));
						$blok_ID_BA_Afd_Blok[]	= oci_result($result_t_blok, strtoupper("ID_BA_Afd_Blok"));
						$blok_Tahun_Tanam[]		= oci_result($result_t_blok, strtoupper("Tahun_Tanam"));
						$blok_Max_Tph[] 		= oci_result($result_t_blok, strtoupper("Max_Tph"));
					}
					$roweffec_t_blok = oci_num_rows($result_t_blok);
					//echo $sql_t_blok.$roweffec_t_blok."<br>";
$x_stage=11;	
					//t_employee
					$sql_t_employee  = "SELECT DISTINCT t7.NIK as NIK, t7.Emp_Name as Emp_Name, t7.Job_Type as Job_Type, 
					t7.Job_Code as Job_Code, t7.ID_BA_Afd as ID_BA_Afd, t7.ID_JobAuthority as ID_JobAuthority
					FROM t_alternate_ba_group t2
					INNER JOIN t_bussinessarea t3 	ON t2.ID_BA = t3.ID_BA
					INNER JOIN t_afdeling t5 	ON t3.ID_BA = t5.ID_BA
					INNER JOIN t_employee t7 	ON t5.ID_BA_Afd = t7.ID_BA_Afd
					WHERE t2.ID_Group_BA = '$sel_ID_Group_BA[0]'";
					//echo "here".$sql_t_employee;
					$result_t_employee = oci_parse($con, $sql_t_employee);
					oci_execute($result_t_employee, OCI_DEFAULT);
					while(oci_fetch($result_t_employee)){
						$emp_NIK[]				= oci_result($result_t_employee, strtoupper("NIK"));
						$emp_Emp_Name[]			= oci_result($result_t_employee, strtoupper("Emp_Name"));
						$emp_Job_Type[]			= oci_result($result_t_employee, strtoupper("Job_Type"));
						$emp_Job_Code[]			= oci_result($result_t_employee, strtoupper("Job_Code"));
						$emp_ID_BA_Afd[]		= oci_result($result_t_employee, strtoupper("ID_BA_Afd"));
						$emp_ID_JobAuthority[]	= oci_result($result_t_employee, strtoupper("ID_JobAuthority"));								
					}
					$roweffec_t_employee = oci_num_rows($result_t_employee);
					//echo $sql_t_employee.$roweffec_t_employee."<br>";
$x_stage=12;	
					//t_jobauthority
					$sql_t_jobauthority  = "SELECT DISTINCT t7.ID_BA as ID_BA, t7.Activity_Code as Activity_Code, 
					t7.Job_Code as Job_Code, t7.ID_JobAuthority as ID_JobAuthority, t7.Authority as Authority, 
					to_char(t7.CREATED_DATE, 'DD-MM-YYYY') AS CREATED_DATE, t7.CREATED_BY as CREATED_BY, t7.ID_CC as ID_CC
					FROM t_alternate_ba_group t2
					INNER JOIN t_bussinessarea t3 	ON t2.ID_BA = t3.ID_BA
					INNER JOIN t_jobauthority t7 	ON t3.ID_BA = t7.ID_BA
					WHERE t2.ID_Group_BA = '$sel_ID_Group_BA[0]'";
					$result_t_jobauthority = oci_parse($con, $sql_t_jobauthority);
					oci_execute($result_t_jobauthority, OCI_DEFAULT);
					while(oci_fetch($result_t_jobauthority)){
						$JA_ID_BA[]			= oci_result($result_t_jobauthority, strtoupper("ID_BA"));
						$JA_Activity_Code[]	= oci_result($result_t_jobauthority, strtoupper("Activity_Code"));
						$JA_Job_Code[]			= oci_result($result_t_jobauthority, strtoupper("Job_Code"));
						$JA_ID_JobAuthority[]	= oci_result($result_t_jobauthority, strtoupper("ID_JobAuthority"));
						$JA_Authority[]		= oci_result($result_t_jobauthority, strtoupper("Authority"));
						$JA_Created_Date[]		= oci_result($result_t_jobauthority, strtoupper("CREATED_DATE"));
						$JA_Created_By[]		= oci_result($result_t_jobauthority, strtoupper("CREATED_BY"));
						$JA_ID_CC[]			= oci_result($result_t_jobauthority, strtoupper("ID_CC"));
						//$Authority_Desc[]	= oci_result($result_t_jobauthority, strtoupper("Authority_Desc"));																
					}
					$roweffec_t_jobauthority = oci_num_rows($result_t_jobauthority);
					//echo $sql_t_jobauthority.$roweffec_t_jobauthority."<br>";
$x_stage=13;	
					//t_internal_order
					$sql_t_internal_order  = "SELECT DISTINCT t7.ID_Internal_Order as ID_Internal_Order, 
					t7.No_Polisi as No_Polisi, t7.ID_BA as ID_BA
					FROM t_alternate_ba_group t2
					INNER JOIN t_internal_order t7 	ON t2.ID_BA = t7.ID_BA
					WHERE t2.ID_Group_BA = '$sel_ID_Group_BA[0]'";
					$result_t_internal_order = oci_parse($con, $sql_t_internal_order);
					oci_execute($result_t_internal_order, OCI_DEFAULT);
					while(oci_fetch($result_t_internal_order)){			
						$in_ID_Internal_Order[]	= oci_result($result_t_internal_order, strtoupper("ID_Internal_Order"));
						$in_No_Polisi[]			= oci_result($result_t_internal_order, strtoupper("No_Polisi"));	
						$in_ID_BA[]				= oci_result($result_t_internal_order, strtoupper("ID_BA"));														
					}
					$roweffec_t_internal_order = oci_num_rows($result_t_internal_order);
					//echo $sql_t_internal_order.$roweffec_t_internal_order."<br>";
				}
				//JIKA TIDAK ADA ID_GROUP (QUERY BEDA)
				else{
					$roweffec_t_group_ba = 1;
					$roweffec_t_alternate_ba_group = 1;
					$GB_ID_Group_BA[]		= "";
					$GB_Group_Name[]		= "";			
					$ALT_ID_Alt_BA_Group[]	= "";
					$ALT_ID_BA[]			= "";
					$ALT_ID_Group_BA[]		= "";
$x_stage=14;	
					//t_companycode
					$sql_t_companycode  = "SELECT DISTINCT t4.ID_CC, t4.Comp_Name
					FROM t_bussinessarea t3
					INNER JOIN t_companycode t4     ON t3.ID_CC = t4.ID_CC
					WHERE t3.ID_BA = '$subID_BA_Afd'";
					$result_t_companycode = oci_parse($con, $sql_t_companycode);
					oci_execute($result_t_companycode, OCI_DEFAULT);
					while(oci_fetch($result_t_companycode)){
						$CC_ID_CC[]		= oci_result($result_t_companycode, strtoupper("ID_CC"));
						$CC_Comp_Name[]	= oci_result($result_t_companycode, strtoupper("Comp_Name"));
					}
					$roweffec_t_companycode = oci_num_rows($result_t_companycode);
					//echo $sql_t_companycode.$roweffec_t_companycode."<br>";	
$x_stage=15;	
					//t_bussinessarea
					$sql_t_bussinessarea  = "SELECT DISTINCT ID_BA, Nama_BA, ID_Estate, ID_CC
					FROM t_bussinessarea
					WHERE ID_BA = '$subID_BA_Afd'";
					$result_t_bussinessarea = oci_parse($con, $sql_t_bussinessarea);
					oci_execute($result_t_bussinessarea, OCI_DEFAULT);
					while(oci_fetch($result_t_bussinessarea)){
						$BA_ID_BA[]		= oci_result($result_t_bussinessarea, strtoupper("ID_BA"));
						$BA_Nama_BA[]		= oci_result($result_t_bussinessarea, strtoupper("Nama_BA"));
						$BA_ID_CC[]		= oci_result($result_t_bussinessarea, strtoupper("ID_CC"));
						$BA_ID_Estate[]	= oci_result($result_t_bussinessarea, strtoupper("ID_Estate"));
					}
					$roweffec_t_bussinessarea = oci_num_rows($result_t_bussinessarea);
					//echo $sql_t_bussinessarea.$roweffec_t_bussinessarea."<br>";	
$x_stage=16;		
					//t_afdeling
					$sql_t_afdeling  = "SELECT DISTINCT t5.ID_BA_Afd, t5.ID_Afd, t5.ID_BA
					FROM t_bussinessarea t3
					INNER JOIN t_afdeling t5 	ON t3.ID_BA = t5.ID_BA
					WHERE t3.ID_BA = '$subID_BA_Afd'";
					$result_t_afdeling = oci_parse($con, $sql_t_afdeling);
					oci_execute($result_t_afdeling, OCI_DEFAULT);
					while(oci_fetch($result_t_afdeling)){
						$AFD_ID_BA_Afd[]	= oci_result($result_t_afdeling, strtoupper("ID_BA_Afd"));
						$AFD_ID_Afd[]		= oci_result($result_t_afdeling, strtoupper("ID_Afd"));
						$AFD_ID_BA[]		= oci_result($result_t_afdeling, strtoupper("ID_BA"));
					}
					$roweffec_t_afdeling = oci_num_rows($result_t_afdeling);
					//echo $sql_t_afdeling.$roweffec_t_afdeling."<br>";	
$x_stage=17;	
					//t_blok
					$sql_t_blok  = "SELECT DISTINCT t6.ID_Blok, t6.Blok_Name, t6.ID_BA_Afd, t6.ID_BA_Afd_Blok, 
					to_char(t6.Tahun_Tanam, 'YYYY') AS TAHUN_TANAM, 30 AS MAX_TPH
					FROM t_bussinessarea t3
					INNER JOIN t_afdeling t5 	ON t3.ID_BA = t5.ID_BA
					INNER JOIN t_blok t6 		ON t5.ID_BA_Afd = t6.ID_BA_Afd
					WHERE t3.ID_BA = '$subID_BA_Afd'";
					$result_t_blok = oci_parse($con, $sql_t_blok);
					oci_execute($result_t_blok, OCI_DEFAULT);
					while(oci_fetch($result_t_blok)){
						$blok_ID_Blok[]			= oci_result($result_t_blok, strtoupper("ID_Blok"));
						$blok_Blok_Name[]		= oci_result($result_t_blok, strtoupper("Blok_Name"));
						$blok_ID_BA_Afd[]		= oci_result($result_t_blok, strtoupper("ID_BA_Afd"));
						$blok_ID_BA_Afd_Blok[]	= oci_result($result_t_blok, strtoupper("ID_BA_Afd_Blok"));
						$blok_Tahun_Tanam[]		= oci_result($result_t_blok, strtoupper("Tahun_Tanam"));
						$blok_Max_Tph[] 		= oci_result($result_t_blok, strtoupper("Max_Tph"));
					}
					$roweffec_t_blok = oci_num_rows($result_t_blok);
					//echo $sql_t_blok.$roweffec_t_blok."<br>";
$x_stage=18;	
					//t_employee
					$sql_t_employee  = "SELECT DISTINCT t7.NIK as NIK, t7.Emp_Name as Emp_Name, t7.Job_Type as Job_Type, 
					t7.Job_Code as Job_Code, t7.ID_BA_Afd as ID_BA_Afd, t7.ID_JobAuthority as ID_JobAuthority
					FROM t_bussinessarea t3
					INNER JOIN t_afdeling t5 	ON t3.ID_BA = t5.ID_BA
					INNER JOIN t_employee t7 	ON t5.ID_BA_Afd = t7.ID_BA_Afd
					WHERE t3.ID_BA = '$subID_BA_Afd'";
					//echo "here".$sql_t_employee;
					$result_t_employee = oci_parse($con, $sql_t_employee);
					oci_execute($result_t_employee, OCI_DEFAULT);
					while(oci_fetch($result_t_employee)){
						$emp_NIK[]				= oci_result($result_t_employee, strtoupper("NIK"));
						$emp_Emp_Name[]			= oci_result($result_t_employee, strtoupper("Emp_Name"));
						$emp_Job_Type[]			= oci_result($result_t_employee, strtoupper("Job_Type"));
						$emp_Job_Code[]			= oci_result($result_t_employee, strtoupper("Job_Code"));
						$emp_ID_BA_Afd[]		= oci_result($result_t_employee, strtoupper("ID_BA_Afd"));
						$emp_ID_JobAuthority[]	= oci_result($result_t_employee, strtoupper("ID_JobAuthority"));								
					}
					$roweffec_t_employee = oci_num_rows($result_t_employee);
					//echo $sql_t_employee.$roweffec_t_employee."<br>";
$x_stage=19;		
					//t_jobauthority
					$sql_t_jobauthority  = "SELECT DISTINCT t7.ID_BA as ID_BA, t7.Activity_Code as Activity_Code, 
					t7.Job_Code as Job_Code, t7.ID_JobAuthority as ID_JobAuthority, t7.Authority as Authority, 
					to_char(t7.CREATED_DATE, 'DD-MM-YYYY') AS CREATED_DATE, t7.CREATED_BY as CREATED_BY, t7.ID_CC as ID_CC
					FROM t_bussinessarea t3
					INNER JOIN t_jobauthority t7 	ON t3.ID_BA = t7.ID_BA
					WHERE t3.ID_BA = '$subID_BA_Afd'";
					$result_t_jobauthority = oci_parse($con, $sql_t_jobauthority);
					oci_execute($result_t_jobauthority, OCI_DEFAULT);
					while(oci_fetch($result_t_jobauthority)){
						$JA_ID_BA[]			= oci_result($result_t_jobauthority, strtoupper("ID_BA"));
						$JA_Activity_Code[]	= oci_result($result_t_jobauthority, strtoupper("Activity_Code"));
						$JA_Job_Code[]			= oci_result($result_t_jobauthority, strtoupper("Job_Code"));
						$JA_ID_JobAuthority[]	= oci_result($result_t_jobauthority, strtoupper("ID_JobAuthority"));
						$JA_Authority[]		= oci_result($result_t_jobauthority, strtoupper("Authority"));
						$JA_Created_Date[]		= oci_result($result_t_jobauthority, strtoupper("CREATED_DATE"));
						$JA_Created_By[]		= oci_result($result_t_jobauthority, strtoupper("CREATED_BY"));
						$JA_ID_CC[]			= oci_result($result_t_jobauthority, strtoupper("ID_CC"));
						//$Authority_Desc[]	= oci_result($result_t_jobauthority, strtoupper("Authority_Desc"));																
					}
					$roweffec_t_jobauthority = oci_num_rows($result_t_jobauthority);
					//echo $sql_t_jobauthority.$roweffec_t_jobauthority."<br>";
$x_stage=20;		
					//t_internal_order
					$sql_t_internal_order  = "SELECT DISTINCT t7.ID_Internal_Order as ID_Internal_Order, 
					t7.No_Polisi as No_Polisi, t7.ID_BA as ID_BA
					FROM  t_bussinessarea t3
					INNER JOIN t_internal_order t7 	ON t3.ID_BA = t7.ID_BA
					WHERE t3.ID_BA = '$subID_BA_Afd'";
					$result_t_internal_order = oci_parse($con, $sql_t_internal_order);
					oci_execute($result_t_internal_order, OCI_DEFAULT);
					while(oci_fetch($result_t_internal_order)){			
						$in_ID_Internal_Order[]	= oci_result($result_t_internal_order, strtoupper("ID_Internal_Order"));
						$in_No_Polisi[]			= oci_result($result_t_internal_order, strtoupper("No_Polisi"));	
						$in_ID_BA[]				= oci_result($result_t_internal_order, strtoupper("ID_BA"));														
					}
					$roweffec_t_internal_order = oci_num_rows($result_t_internal_order);
					//echo $sql_t_internal_order.$roweffec_t_internal_order."<br>";
				}
$x_stage=21;	
				//t_kualitas_panen
				$sql_t_kualitas_panen  = "SELECT DISTINCT ID_Kualitas, Nama_Kualitas, UOM, Group_Kualitas, Active_Status
				FROM t_kualitas_panen";
				$result_t_kualitas_panen = oci_parse($con, $sql_t_kualitas_panen);
				oci_execute($result_t_kualitas_panen, OCI_DEFAULT);
				while(oci_fetch($result_t_kualitas_panen)){			
					$KP_ID_Kualitas[]		= oci_result($result_t_kualitas_panen, strtoupper("ID_Kualitas"));
					$KP_Nama_Kualitas[]	= oci_result($result_t_kualitas_panen, strtoupper("Nama_Kualitas"));	
					$KP_UOM[]				= oci_result($result_t_kualitas_panen, strtoupper("UOM"));	
					$KP_Group_Kualitas[]	= oci_result($result_t_kualitas_panen, strtoupper("Group_Kualitas"));	
					$KP_Active_Status[]	= oci_result($result_t_kualitas_panen, strtoupper("Active_Status"));
				}
				$roweffec_t_kualitas_panen = oci_num_rows($result_t_kualitas_panen);
				//echo $sql_t_kualitas_panen.$roweffec_t_kualitas_panen."<br>";
$x_stage=22;	
				//t_bjr
				$sql_t_bjr  = "select a.ID_BA_AFD_blok as id_ba_afd_blok, F_GET_BJR (b.id_ba,a.id_blok,trunc(sysdate)) as bjr 
								from t_blok a inner join t_afdeling b on a.id_ba_afd=b.id_ba_afd where id_ba='$subID_BA_Afd'";
				$result_t_bjr = oci_parse($con, $sql_t_bjr);
				oci_execute($result_t_bjr, OCI_DEFAULT);
				while(oci_fetch($result_t_bjr)){			
					$BJR[]	= oci_result($result_t_bjr, strtoupper("bjr"));	
					$BJR_ID_BA_AFD_BLOK[]	= oci_result($result_t_bjr, strtoupper("id_ba_afd_blok"));
				}
				$roweffec_t_bjr = oci_num_rows($result_t_bjr);
$x_stage=23;	
				//t_max_gandeng 
				//$sql_t_max_gandeng  = "SELECT Maksimum_Jumlah_Gandeng FROM t_max_gandeng";
				$sql_t_max_gandeng  = "select Maksimum_Jumlah_Gandeng from t_max_gandeng where ID_BA = '$subID_BA_Afd'";
				$result_t_max_gandeng = oci_parse($con, $sql_t_max_gandeng);
				oci_execute($result_t_max_gandeng, OCI_DEFAULT);
				while(oci_fetch($result_t_max_gandeng)){			
					$Maksimum_Jumlah_Gandeng	= oci_result($result_t_max_gandeng, strtoupper("Maksimum_Jumlah_Gandeng"));													
				}
				$roweffec_t_max_gandeng = oci_num_rows($result_t_max_gandeng);
				//echo $sql_t_max_gandeng.$roweffec_t_max_gandeng."<br>";
$x_stage=24;								
				if( $roweffec_t_group_ba > 0 &&
				$roweffec_t_alternate_ba_group > 0 &&
				$roweffec_t_companycode > 0 && 
				$roweffec_t_bussinessarea > 0 && 
				$roweffec_t_afdeling > 0 &&
				$roweffec_t_blok > 0  &&
				$roweffec_t_employee > 0 &&
				$roweffec_t_jobauthority > 0  
				/*$roweffec_t_internal_order > 0 && 
				$roweffec_t_kualitas_panen > 0 */
				){ 
					$response["t_group_ba"]		= array();
					$response["t_alternate_ba_group"]	= array();
					$response["t_companycode"] 		= array();
					$response["t_bussinessarea"]	= array();
					$response["t_afdeling"]		= array();
					$response["t_blok"]			= array();
					$response["t_employee"]		= array();
					$response["t_jobauthority"]	= array();
					$response["t_internal_order"]	= array();
					$response["t_kualitas_panen"]	= array();
					$response["t_bjr"]	= array();
					
					//t_group_ba
					for($y=0; $y<$roweffec_t_group_ba; $y++){
						
						$t_group_ba = array();
						$t_group_ba["ID_Group_BA"] 	= $GB_ID_Group_BA[$y];
						$t_group_ba["Group_Name"]	= $GB_Group_Name[$y];
						//$t_group_ba["ID_Group_BA"] 	= $NIK." ".$Login_Name." ".$subID_BA_Afd;
						//$t_group_ba["Group_Name"]	= "test";
						array_push($response["t_group_ba"], $t_group_ba);	
					}
					
					//t_alternate_ba_group
					for($y=0; $y<$roweffec_t_alternate_ba_group; $y++){
						$t_alternate_ba_group = array();
						$t_alternate_ba_group["ID_Alt_BA_Group"]	= $ALT_ID_Alt_BA_Group[$y];
						$t_alternate_ba_group["ID_BA"] 				= $ALT_ID_BA[$y];
						$t_alternate_ba_group["ID_Group_BA"] 		= $ALT_ID_Group_BA[$y];
						array_push($response["t_alternate_ba_group"], $t_alternate_ba_group);
					}
					
					//t_companycode
					for($y=0; $y<$roweffec_t_companycode; $y++){
						$t_companycode = array();
						$t_companycode["ID_CC"]		= $CC_ID_CC[$y];
						$t_companycode["Comp_Name"]	= $CC_Comp_Name[$y];
						array_push($response["t_companycode"], $t_companycode);
					}
					
					//t_bussinessarea
					for($y=0; $y<$roweffec_t_bussinessarea; $y++){
						$t_bussinessarea = array();
						$t_bussinessarea["ID_BA"]		= $BA_ID_BA[$y];
						$t_bussinessarea["Nama_BA"]		= $BA_Nama_BA[$y];
						$t_bussinessarea["ID_CC"]		= $BA_ID_CC[$y];
						$t_bussinessarea["ID_Estate"]	= $BA_ID_Estate[$y];
						array_push($response["t_bussinessarea"], $t_bussinessarea);
					}
					
					//t_afdeling
					for($y=0; $y<$roweffec_t_afdeling; $y++){
						$t_afdeling = array();
						$t_afdeling["ID_BA_Afd"]= $AFD_ID_BA_Afd[$y];
						$t_afdeling["ID_Afd"]	= $AFD_ID_Afd[$y];
						$t_afdeling["ID_BA"]	= $AFD_ID_BA[$y];
						array_push($response["t_afdeling"], $t_afdeling);
					}
					
					//t_blok
					for($y=0; $y<$roweffec_t_blok; $y++){
						$t_blok = array();
						$t_blok["ID_Blok"]			= $blok_ID_Blok[$y];
						$t_blok["Blok_Name"]		= $blok_Blok_Name[$y];
						$t_blok["ID_BA_Afd"]		= $blok_ID_BA_Afd[$y];
						$t_blok["ID_BA_Afd_Blok"]	= $blok_ID_BA_Afd_Blok[$y];	
						$t_blok["Tahun_Tanam"]		= $blok_Tahun_Tanam[$y];									
						$t_blok["Max_Tph"] 			= $blok_Max_Tph[$y];
						array_push($response["t_blok"], $t_blok);
					}	
					
					//t_employee
					for($y=0; $y<$roweffec_t_employee; $y++){
						$t_employee = array();
						$t_employee["NIK"]			= $emp_NIK[$y];
						$t_employee["Emp_Name"]		= $emp_Emp_Name[$y];
						$t_employee["Job_Type"]		= $emp_Job_Type[$y];
						$t_employee["Job_Code"]		= $emp_Job_Code[$y];
						$t_employee["ID_BA_Afd"]		= $emp_ID_BA_Afd[$y];
						$t_employee["ID_JobAuthority"]	= $emp_ID_JobAuthority[$y];
						array_push($response["t_employee"], $t_employee);
					}		
					
					//t_jobauthority
					for($y=0; $y<$roweffec_t_jobauthority; $y++){
						$t_jobauthority = array();
						$t_jobauthority["ID_BA"]			= $JA_ID_BA[$y];
						$t_jobauthority["Activity_Code"]	= $JA_Activity_Code[$y];
						$t_jobauthority["Job_Code"]			= $JA_Job_Code[$y];
						$t_jobauthority["ID_JobAuthority"]	= $JA_ID_JobAuthority[$y];
						$t_jobauthority["Authority"]		= $JA_Authority[$y];
						$t_jobauthority["Created_Date"]		= $JA_Created_Date[$y];
						$t_jobauthority["Created_By"]		= $JA_Created_By[$y];
						$t_jobauthority["ID_CC"]			= $JA_ID_CC[$y];
						//$t_jobauthority["Authority_Desc"]	= $Authority_Desc[$y];
						array_push($response["t_jobauthority"], $t_jobauthority);
					}
					
					//t_internal_order
					for($y=0; $y<$roweffec_t_internal_order; $y++){
						$t_internal_order = array();
						$t_internal_order["ID_Internal_Order"]	= $in_ID_Internal_Order[$y];
						$t_internal_order["No_Polisi"]			= $in_No_Polisi[$y];
						$t_internal_order["ID_BA"]				= $in_ID_BA[$y];
						array_push($response["t_internal_order"], $t_internal_order);
					}
					
					//t_kualitas_panen
					for($y=0; $y<$roweffec_t_kualitas_panen; $y++){
						$t_kualitas_panen = array();
						$t_kualitas_panen["ID_Kualitas"]	= $KP_ID_Kualitas[$y];
						$t_kualitas_panen["Nama_Kualitas"]	= $KP_Nama_Kualitas[$y];
						$t_kualitas_panen["UOM"]			= $KP_UOM[$y];
						$t_kualitas_panen["Group_Kualitas"]	= $KP_Group_Kualitas[$y];
						$t_kualitas_panen["Active_Status"]	= $KP_Active_Status[$y];
						array_push($response["t_kualitas_panen"], $t_kualitas_panen);
					}
					
					//t_bjr
					for($y=0; $y<$roweffec_t_bjr; $y++){
						$t_bjr = array();
						$t_bjr["BJR"]	= $BJR[$y];
						$t_bjr["ID_BA_AFD_BLOK"]	= $BJR_ID_BA_AFD_BLOK[$y];
						array_push($response["t_bjr"], $t_bjr);
					}
					
					/*
					$Maksimum_Jumlah_Gandeng = 0;
					for($y=0; $y<count(select_data($con,$sql_t_max_gandeng)); $y++){
						
						$Maksimum_Jumlah_Gandeng = $fetch_t_max_gandeng[strtoupper("Maksimum_Jumlah_Gandeng")];
						$response["Maksimum_Jumlah_Gandeng"] = $Maksimum_Jumlah_Gandeng;
					}
					*/
					//t_max_gandeng
					if($Maksimum_Jumlah_Gandeng == null)
					{
						$Maksimum_Jumlah_Gandeng =0;
					}
					$response["Maksimum_Jumlah_Gandeng"] = $Maksimum_Jumlah_Gandeng;					
					$response["success"] = 1;
					$response["message"] = "login and get data success"." #x_stage:".$x_stage;	
					//echo json_encode($response);
					//commit($con);
					echo str_replace('\/','/',json_encode($response));
				}	
				else{
					$response["success"] = 0;
					//$response["message"] = "no result for".$ID_Group_BA;
					$response["message"] = "no result ".$roweffec_ID_Group_BA." ".$roweffec_t_group_ba." ".$roweffec_t_alternate_ba_group." ".$roweffec_t_companycode." ".$roweffec_t_bussinessarea." ".$roweffec_t_afdeling." ".$roweffec_t_blok." ".$roweffec_t_employee." ".$roweffec_t_jobauthority." #x_stage:".$x_stage;
					//rollback($con);
					echo json_encode($response);
				}	
		
			}//close if($validasi > 0){ 
			else{
				$response["success"] = 0;
				$response["message"] = "user tidak punya authority login, validasi = ".$validasi.", Query = ".$sql_validasi." #x_stage:".$x_stage;	
				echo json_encode($response);
			}
		
		} //close if($roweffec_check_user > 0){
		else{
			$response["success"] = 0;
			$response["message"] = "userlogin tidak ditemukan"." #x_stage:".$x_stage;	
			echo json_encode($response);
		}
		
	} //close else
/*
	}
	else
	{
		echo json_encode($response);
	}*/
	//echo json_encode($response);
}
else{
$response["success"] = 0;
$response["message"] = "NIK, Login name, and ID_BA empty"." #x_stage:".$x_stage;
echo json_encode($response);
} 
?>