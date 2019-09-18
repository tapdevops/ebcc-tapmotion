<?php

function csvtoarray($filename='', $delimiter) {
	if(!file_exists($filename) || !is_readable($filename)) return FALSE;
	$header = NULL;
	$data = array();

	if (($handle = fopen($filename, 'r')) !== FALSE ) {
		while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
			if(!$header) {
				$header = $row;
			} else {
				$data[] = array_combine($header, $row);
			}
		}
		fclose($handle);
	}
	return $data;
}


$data = csvtoarray('/home/eBCC-csv-TPH/TPH GBSM.csv', ',');
//$data = csvtoarray('M_TPH.csv', ',');
//echo '<pre>'; print_r($data); echo '</pre>';

include("../config/SQL_function.php");
include("../config/dw_tap_config.php");

$total_insert = $total_update = 0;
foreach ($data as $k => $v) {

	$select = "SELECT COUNT(*) AS TOTAL FROM TAP_DW.TM_TPH WHERE WERKS = '{$v['WERKS']}' AND AFD_CODE = '{$v['AFD_CODE']}' AND BLOCK_CODE = '{$v['BLOCK_CODE']}' AND NO_TPH = '{$v['NO_TPH']}'";
	$res = select_data($cons,$select);

	if ($res['TOTAL'] > 0) {
		$update = "UPDATE TAP_DW.TM_TPH SET WERKS = '{$v['WERKS']}', AFD_CODE = '{$v['AFD_CODE']}', BLOCK_CODE = '{$v['BLOCK_CODE']}', NO_TPH = '{$v['NO_TPH']}', LATITUDE = '{$v['LATITUDE']}', LONGITUDE = '{$v['LONGITUDE']}', UPDATE_USER = 'system', UPDATE_TIME = SYSDATE WHERE WERKS = '{$v['WERKS']}' AND AFD_CODE = '{$v['AFD_CODE']}' AND BLOCK_CODE = '{$v['BLOCK_CODE']}' AND NO_TPH = '{$v['NO_TPH']}'";
		$stmt = oci_parse($cons, $update);
		oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
		oci_free_statement($stmt);
		$total_update++;
	} else {
		$insert = "INSERT INTO TAP_DW.TM_TPH (WERKS, AFD_CODE, BLOCK_CODE, NO_TPH, LATITUDE, LONGITUDE, INSERT_USER, INSERT_TIME) VALUES ('{$v['WERKS']}', '{$v['AFD_CODE']}', '{$v['BLOCK_CODE']}', '{$v['NO_TPH']}', '{$v['LATITUDE']}', '{$v['LONGITUDE']}', 'system', SYSDATE)";
		$stmt = oci_parse($cons, $insert);
		oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
		oci_free_statement($stmt);
		$total_insert++;
	}
}
echo 'Total Insert : ' . $total_insert . '<br />';
echo 'Total Update : ' . $total_update . '<br />';

?>