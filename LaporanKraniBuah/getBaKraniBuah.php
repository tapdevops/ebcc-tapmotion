<?php

	include("../config/SQL_function.php");
	include("../config/db_connect.php");

	$con = connect();

	if (isset($_POST['comp'])) {
		$sql_t_BA = "SELECT ID_BA FROM t_bussinessarea WHERE ID_CC = '{$_POST['comp']}' ORDER BY ID_BA";
		$result_t_BA = oci_parse($con, $sql_t_BA);
		oci_execute($result_t_BA, OCI_DEFAULT);

		$array = array();
		while (oci_fetch($result_t_BA)) {
			$idba = oci_result($result_t_BA, "ID_BA");
			if ($idba == $_POST['ba']) {
				$array[$idba] = 'selected';
			} else {
				$array[$idba] = '';
			}
		}
	}

	$key = array_search('selected', $array);

	if (empty($key)) {
		$newk = array_keys($array)[0];
		$array[$newk] = 'selected';
	}

	$output = '';
	foreach ($array as $k => $v) {
		if (!empty($v)) {
			$output .= '<option value="'.$k.'" selected="selected">'.$k.'</option>';
		} else {
			$output .= '<option value="'.$k.'">'.$k.'</option>';
		}
	}

	echo json_encode($output);

	exit();
?>