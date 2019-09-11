<?php

        include("../config/SQL_function.php"); 
		include("../config/db_config.php");
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');

		//print_r ($_POST);
		//print_r ($_GET);
		$afdeling = $_GET['afdeling'];
		$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
		//print_r($subID_BA_Afd);die();
		$sql_t_BA  = "SELECT ID_BLOK, BLOK_NAME FROM T_BLOK WHERE ID_BA_AFD = '$subID_BA_Afd$afdeling' and INACTIVE_DATE is NULL ORDER BY ID_BLOK";
		//echo $sql_t_BA;

		$result_t_BA = oci_parse($con, $sql_t_BA);
		oci_execute($result_t_BA, OCI_DEFAULT);
		$output = "<option>--select--</option>";
		while ($p=oci_fetch($result_t_BA)) {	
			$id_blok = oci_result($result_t_BA, "ID_BLOK");
			$blok_name = oci_result($result_t_BA, "BLOK_NAME");
			$output .= '<option value="'.$id_blok.'">'.$id_blok.' - '.$blok_name.'</option>';
		}

		$sql_params = "SELECT NILAI FROM T_PARAMETER WHERE KETERANGAN = 'ID_BA_AFD'";
		$res_param = select_data($con, $sql_params);
		//print_r ($res_param);

		$total = 0;
		if (isset($res_param['NILAI']) && !empty($res_param['NILAI'])) {
			if (strpos($res_param['NILAI'], $subID_BA_Afd.$afdeling) !== false) { $total = 1; } else { $total = 0; }
		} else {
			$total = 0;
		}


		$res = array();
		$res['output'] = $output;
		$res['exist'] = $total;
		echo json_encode($res);

		exit();
?>
