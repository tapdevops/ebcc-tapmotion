<?php

	include("../config/SQL_function.php");
	include("../config/db_connect.php");

	$con = connect();

	if (isset($_POST['ba'])) {
		$sql_t_AFD = "
			SELECT * FROM t_Afdeling tafd INNER JOIN t_bussinessarea tba ON tafd.id_ba = tba.id_ba WHERE tba.id_ba = '{$_POST['ba']}'  ORDER BY tafd.id_afd
		";

		$result_t_AFD = oci_parse($con, $sql_t_AFD);
		oci_execute($result_t_AFD, OCI_DEFAULT);

		$output = '';
		$output .= '<option value="ALL">ALL</option>';
		while (oci_fetch($result_t_AFD)) {
			$idafd = oci_result($result_t_AFD, "ID_AFD");
			if ($idafd == $_POST['afd']) {
				$output .= '<option value="'.$idafd.'" selected="selected">'.$idafd.'</option>';
			} else {
				$output .= '<option value="'.$idafd.'">'.$idafd.'</option>';
			}
		}
	}

	echo json_encode($output);

	exit();
?>