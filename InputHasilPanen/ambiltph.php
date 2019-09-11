<?php

	include('../config/dw_tap_config.php');

	$werks = substr($_POST['afdeling'], 0, 4);
	$afd = substr($_POST['afdeling'], 4);
	$id = str_replace('tph', '', $_POST['tph']);

	$sql = "SELECT * FROM TM_TPH WHERE WERKS = '{$werks}' AND AFD_CODE = '{$afd}' AND BLOCK_CODE = '{$_POST['blok']}'";
	$result = oci_parse($cons, $sql);
	oci_execute($result, OCI_DEFAULT);

	//$output = '<select name="tph'.$id.'" id="tph'.$id.'">';
	$total = 0;
	$output = '<option>--select--</option>';
	while ($p = oci_fetch($result)) {
		$tph = oci_result($result, "NO_TPH");
		$output .= '<option value="'.$tph.'">'.$tph.'</option>';
		$total++;
	}

	if ($total == 0) {
		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();

		$sql = "SELECT TPH FROM T_BLOK_TPH WHERE WERKS = '{$werks}' AND AFD = '{$afd}' AND BLOCK_CODE = '{$_POST['blok']}'";
		$result = oci_parse($con, $sql);
		oci_execute($result, OCI_DEFAULT);

		$select = 0;
		while (oci_fetch($result)) {
			$tph = oci_result($result, "TPH");
		}

		for ($i=1; $i<=$tph; $i++) {
			$exist = '';
			$tp = str_pad($i, 3, '0', STR_PAD_LEFT);
			$output .= '<option value="'.$tp.'">'.$tp.'</option>';
			$total++;
		}

	}
	$output .= '</select>';

	$res = array();
	$res['total'] = $total;
	$res['output'] = $output;

	echo json_encode($res);

	exit();
?>
