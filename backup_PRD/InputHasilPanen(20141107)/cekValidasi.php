<?php

        include("../config/SQL_function.php"); 
		include("../config/db_config.php");
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
		
		$nik = $_POST['nik'];
		$tgl_Panen = $_POST['var_tgl'];
		$blok = $_POST['var_blok'];
		//$ticket = $_POST['var_ticket'];
		$ba = $_POST['var_ba'];
		$afd = $_POST['var_afd'];
		$datePlan = date('Ymd', strtotime($tgl_Panen));
		$id_rencana = "";
		$id_ba_afd_blok =  $afd . $blok;
		$PlanID = $datePlan . ".MANUAL." . $nik;
		$sql_t_PID  = "select ID_RENCANA from T_DETAIL_RENCANA_PANEN where ID_BA_AFD_BLOK = '$id_ba_afd_blok' and ID_RENCANA like '$datePlan.%.$nik'";
		$result_t_PID = oci_parse($con, $sql_t_PID);
					oci_execute($result_t_PID, OCI_DEFAULT);
					while ($p=oci_fetch($result_t_PID)) {	
							$id_rencana = oci_result($result_t_PID, "ID_RENCANA");
					}
		if($id_rencana == ""){
			echo "kosong";
		}else{
			echo $id_rencana;
		}
?>
