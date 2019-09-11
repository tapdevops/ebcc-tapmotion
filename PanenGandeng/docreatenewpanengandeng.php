
<?php

session_start();
if(isset($_POST["business_area"]) && isset($_POST["max_jml_gandeng"]) && isset($_POST["start"]) && isset($_POST["end"]) && isset($_SESSION["NIK"]) && isset($_SESSION["LoginName"])){
	
include("../config/SQL_function.php");
include("../config/db_connect.php");
$con = connect();

$business_area = $_POST["business_area"];
$max_jml_gandeng = $_POST["max_jml_gandeng"];
$start = date("Y-m-d", strtotime($_POST["start"]));
$end = date("Y-m-d", strtotime($_POST["end"]));
$NIK = $_SESSION["NIK"];
$Login_Name = $_SESSION["LoginName"];
$action = true;

//echo $start." # ".$_POST["start"]." # ".$end." # ".$_POST["end"]; die;

$sql_group_ba_1  = "select to_char(SYSDATE+1,'MM/DD/YYYY') TGL from dual";
$result_group_ba_1  = select_data($con,$sql_group_ba_1);
$start_date_1 = $result_group_ba_1["TGL"];
$start_date_2 = date("Y-m-d", strtotime($start_date_1));
	
	if($business_area == "" || $max_jml_gandeng == "" || $start == "" || $end == ""){
		$_SESSION[err] = "Business Area, Max Jumlah Gandeng, Start Date, atau End Date tidak boleh kosong";
		header("Location:panengandeng.php");
	}
	else{
	
		if($end=="1970-01-01")
		{
			$end="2999-12-31";
		}
		
		if($start=="1970-01-01")
		{
			$start=$start_date_2;
		}
		
		$sql_select = "select MAKSIMUM_JUMLAH_GANDENG, START_DATE, END_DATE from t_max_gandeng
		WHERE ID_BA='$business_area'";
		$rs_select = oci_parse($con, $sql_select);
		oci_execute($rs_select, OCI_DEFAULT);
		oci_fetch($rs_select);
		$oldMAKSIMUM_JUMLAH_GANDENG 	= oci_result($rs_select, "MAKSIMUM_JUMLAH_GANDENG");
		$oldSTART_DATE 	= oci_result($rs_select, "START_DATE");
		$oldEND_DATE	 	= oci_result($rs_select, "END_DATE");
	
		$sql_select_t_max_gandeng = "select count(*) as HITUNG from t_max_gandeng where ID_BA='$business_area'";
		$roweffec_select_t_max_gandeng = select_data($con,$sql_select_t_max_gandeng);
		
		//Edited by Ardo, 01 Dec 2016 : Batas Gandeng agar dapat input lebih dari 1
		if($roweffec_select_t_max_gandeng["HITUNG"] > 0){
			//$insert[$ctr] = $ctr."business_area: ".$business_area." already on table t_max_gandeng and updated";
			$_SESSION[err] = "business_area: ".$business_area." already on table t_max_gandeng and updated";
			$sql_t_MG = "UPDATE t_max_gandeng
			SET MAKSIMUM_JUMLAH_GANDENG='$max_jml_gandeng', START_DATE=to_date('$start','YYYY-MM-DD'), END_DATE=to_date('$end','YYYY-MM-DD')
			WHERE ID_BA='$business_area'";
			$roweffec_t_MG = num_rows($con,$sql_t_MG);
			
			$sql_log_t_MG = "INSERT INTO t_log_max_gandeng (
			InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, New_Value_MG, Old_Value_MG, CreEdit_From, Sync_Server, ON_ID_BA, NEW_START_DATE, OLD_START_DATE, NEW_END_DATE, OLD_END_DATE) 
			VALUES
			('UPDATE', SYSDATE , '$NIK', '$Login_Name', 't_max_gandeng', '$max_jml_gandeng', '$oldMAKSIMUM_JUMLAH_GANDENG', 'Website', SYSDATE, '$business_area', to_date('$start','YYYY-MM-DD'), '$oldSTART_DATE', to_date('$end','YYYY-MM-DD'), '$oldEND_DATE')";
			$log_update_MG = num_rows($con,$sql_log_t_MG);
		}
		else{
	
			$sql_t_MG = "INSERT INTO t_max_gandeng
			(ID_BA, MAKSIMUM_JUMLAH_GANDENG, BATAS_GANDENG, START_DATE, END_DATE) 
			VALUES
			('$business_area', '$max_jml_gandeng', '2', to_date('$start','YYYY-MM-DD'), to_date('$end','YYYY-MM-DD'))";
			$roweffec_t_MG = num_rows($con,$sql_t_MG);
			
			$sql_log_t_MG = "INSERT INTO t_log_max_gandeng (
			InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, New_Value_MG, Old_Value_MG, CreEdit_From, Sync_Server, ON_ID_BA, NEW_START_DATE, OLD_START_DATE, NEW_END_DATE, OLD_END_DATE) 
			VALUES
			('INSERT', SYSDATE , '$NIK', '$Login_Name', 't_max_gandeng', '$max_jml_gandeng', '$oldMAKSIMUM_JUMLAH_GANDENG', 'Website', SYSDATE, '$business_area', to_date('$start','YYYY-MM-DD'), '$oldSTART_DATE', to_date('$end','YYYY-MM-DD'), '$oldEND_DATE')";
			$log_update_MG = num_rows($con,$sql_log_t_MG);
			
			$_SESSION[err] = "Data successfully inserted";
		}
		
		   
		if($roweffec_t_MG > 0 && $log_update_MG > 0 )
		{
			commit($con);
		}
		else
		{
			rollback($con);
		}
	    
		header("Location:http:panengandeng.php");
	}
}
else{
	$_SESSION[err] = "Business Area, Max Jumlah Gandeng, Start Date, atau End Date tidak boleh kosong";
	header("Location:http:panengandeng.php");
	
    	
}
?>