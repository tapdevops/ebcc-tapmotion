<?php

if (isset($_POST) && !empty($_POST)) {
	include('../config/dw_tap_config.php');

	$stid = oci_parse($cons, "SELECT DISTINCT(AFD_CODE) FROM TAP_DW.TM_BLOCK WHERE WERKS = '{$_POST['werks']}' ORDER BY AFD_CODE");
	oci_execute($stid);

	$output = '<select name="afd">';
	$output .= '<option value="">--- Pilih ---</option>';
	while(($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
		$output .= '<option value="'.$row['AFD_CODE'].'">'.$row['AFD_CODE'].'</option>';
	}
	$output .= '</select>';

	echo $output;

	exit();
}
?>