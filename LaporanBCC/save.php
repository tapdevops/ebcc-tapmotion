<?php
if(isset($_REQUEST['id_rencana']) and isset($_REQUEST['no_bcc'])){
	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();
	
	$select = "
	select validasi_bcc, validasi_date from t_hasil_panen WHERE ID_RENCANA = '".$_REQUEST['id_rencana']."' AND NO_BCC = '".$_REQUEST['no_bcc']."' AND VALIDASI_BCC is null
	";
	$stid1 = oci_parse($con, $select);
	oci_execute($stid1);
	$count = oci_fetch_all($stid1, $rec, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
	if($count==1){
		$valid_query = "
		update t_hasil_panen set validasi_bcc = 'X', validasi_date = to_date('".date('m-d-Y H:i:s')."', 'MM-DD-YYYY HH24:MI:SS') WHERE ID_RENCANA = '".$_REQUEST['id_rencana']."' AND NO_BCC = '".$_REQUEST['no_bcc']."'
		";
		
		$result_THP = num_rows($con, $valid_query);
		//echo $result_THP; exit;
		if($result_THP==1){
			echo "success";
			commit($con);
		} else {
			echo "fail";
			rollback($con);
		}
	}
}
?>