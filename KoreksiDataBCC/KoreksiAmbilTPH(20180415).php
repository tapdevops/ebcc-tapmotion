<?php

include('../config/dw_tap_config.php');
$sql = "SELECT * FROM TM_TPH WHERE WERKS = '{$_POST['ba']}' AND AFD_CODE = '{$_POST['afd']}' AND BLOCK_CODE = '{$_POST['blok']}'";
$sql = "SELECT * FROM TM_TPH WHERE WERKS = '4122' AND AFD = 'N' AND BLOCK_CODE = '305'";
$result = oci_parse($cons, $sql);
oci_execute($result, OCI_DEFAULT);

$select = 0;
$total = 0;
$output = '<option>--select--</option>';
while ($p = oci_fetch($result)) {
	$tph = oci_result($result, "NO_TPH");
	$exist = '';
	if (isset($_POST['exist_tph']) && !empty($_POST['exist_tph'])) {
		$exist = $_POST['exist_tph'];
	}

	if ($exist == $tph) {
		$output .= '<option value="'.$tph.'" selected="selected">'.$tph.'</option>';
		$select = $select++;
	} else {
		$output .= '<option value="'.$tph.'">'.$tph.'</option>';
	}
	$total++;
}


if ($select == 0) {
	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();

	$sql = "SELECT TPH FROM T_BLOK_TPH WHERE WERKS = '{$_POST['ba']}' AND AFD = '{$_POST['afd']}' AND BLOCK_CODE = '{$_POST['blok']}'";
	$result = oci_parse($con, $sql);
	oci_execute($result, OCI_DEFAULT);

	$select = 0;
	while (oci_fetch($result)) {
		$tph = oci_result($result, "TPH");
	}

	for ($i=1; $i<=$tph; $i++) {
		$exist = '';
		if (isset($_POST['exist_tph']) && !empty($_POST['exist_tph'])) {
			$exist = $_POST['exist_tph'];
		}

		$tp = str_pad($i, 3, '0', STR_PAD_LEFT);
		if ($exist == $tp) {
			$output .= '<option value="'.$tp.'" selected="selected">'.$tp.'</option>';
		} else {
			$output .= '<option value="'.$tp.'">'.$tp.'</option>';
		}
		$total++;
	}

}

$res = array();
$res['exist'] = $exist;
$res['total'] = $total;
$res['output'] = $output;
echo json_encode($res);

exit();


?>