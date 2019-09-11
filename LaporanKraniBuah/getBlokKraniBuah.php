<?php

	include("../config/SQL_function.php");
	include("../config/db_connect.php");

	$con = connect();

	$roweffec_BLOK = 0;

	if (isset($_POST['afd'])) {
		$sql_t_BLOK = "
			SELECT ID_BLOK FROM T_BLOK TB 
			INNER JOIN T_AFDELING TA ON TB.ID_BA_AFD = TA.ID_BA_AFD 
			WHERE TA.ID_AFD = NVL(DECODE('{$_POST['afd']}', 'ALL', null, '{$_POST['afd']}'), TA.ID_AFD) AND ID_BA = '{$_POST['ba']}' ORDER BY TB.ID_BLOK
		";

		$result_t_sBLOK = oci_parse($con, $sql_t_BLOK);
		oci_execute($result_t_sBLOK, OCI_DEFAULT);

		$output = '';
		$output .= '<option value="ALL">ALL</option>';
		while (oci_fetch($result_t_sBLOK)) {
			$idblok = oci_result($result_t_sBLOK, "ID_BLOK");
			if ($idblok == $afd) {
				$output .= '<option value="'.$idblok.'" selected="selected">'.$idblok.'</option>';
			} else {
				$output .= '<option value="'.$idblok.'">'.$idblok.'</option>';
			}
		}
	}

	echo json_encode($output);

	exit();
?>