<?php

if (isset($_POST) && !empty($_POST)) {
	include("../config/db_config.php");
	$con_ebcc = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
	//$nm = oci_parse($con_ebcc, "SELECT * FROM T_BLOK_TPH WHERE WERKS = '{$_POST['werks']}' AND AFD = '{$_POST['afd']}'");
	$nm = oci_parse($con_ebcc, "SELECT WERKS, AFD, BLOCK_CODE, MAX(TPH) AS TPH FROM T_BLOK_TPH WHERE WERKS = '{$_POST['werks']}' AND AFD = '{$_POST['afd']}' GROUP BY WERKS, AFD, BLOCK_CODE ORDER BY WERKS, AFD, BLOCK_CODE");
	//echo "SELECT * FROM T_BLOK_TPH WHERE WERKS = '{$_POST['werks']}' AND AFD = '{$_POST['afd']}'";
	oci_execute($nm);

	$newexist = array();
	while(($rows = oci_fetch_array($nm, OCI_ASSOC)) != false) {
		$newexist[$rows['BLOCK_CODE']] = $rows['TPH'];
	}

	//include('../config/dw_tap_config.php');
	//$stid = oci_parse($cons, "SELECT BLOCK_CODE, BLOCK_NAME FROM TAP_DW.TM_BLOCK WHERE WERKS = '{$_POST['werks']}' AND AFD_CODE = '{$_POST['afd']}' GROUP BY BLOCK_CODE, BLOCK_NAME ORDER BY BLOCK_CODE");
	$idbaafd = $_POST['werks'] . $_POST['afd'];
	$public_database_link_name="PRODDW_LINK";
	//$public_database_link_name="DEVDW_LINK";
	$stid = oci_parse($con_ebcc, "SELECT DISTINCT * FROM (
									SELECT ID_BLOK, BLOK_NAME FROM EBCC.T_BLOK WHERE ID_BA_AFD = '{$idbaafd}' AND INACTIVE_DATE IS NULL
									UNION
									SELECT BLOCK_CODE ID_BLOK, BLOCK_NAME FROM TAP_DW.TM_BLOCK@".$public_database_link_name." 
									WHERE WERKS = '{$_POST['werks']}' AND AFD_CODE = '{$_POST['afd']}' GROUP BY BLOCK_CODE, BLOCK_NAME) a
									ORDER BY ID_BLOK");
	oci_execute($stid);

	$newdata = array();
	while(($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
		$newdata[] = array(
			'BLOCK_CODE' => $row['ID_BLOK'],
			'BLOCK_NAME' => $row['BLOK_NAME'],
			'TPH' => (isset($newexist[$row['ID_BLOK']])) ? $newexist[$row['ID_BLOK']] : '0'
		);
	}
	
	$i = 0;
	foreach ($newdata as $k => $v) {
		$output .= '
			<tr>
				<td style="padding:10px; border-bottom:1px solid black;">
					<input type="hidden" name="blok_code['.$k.']" value="'.$v['BLOCK_CODE'].'">'.$v['BLOCK_CODE'].'
				</td>
				<td style="padding:10px; border-bottom:1px solid black;">
					<input type="hidden" name="blok_name['.$k.']" value="'.$v['BLOCK_NAME'].'">'.$v['BLOCK_NAME'].'
				</td>';
		if ($v['TPH'] == '0') {
			$output .= '
				<td style="border-bottom:1px solid black;">0</td>
				<td style="border-bottom:1px solid black;"><input type="hidden" name="cur_tph['.$k.']" value="0"><input type="text" name="tph['.$k.']"></td>
			';
		} else {
			$output .= '
				<td style="border-bottom:1px solid black;">'.$v['TPH'].'</td>
				<td style="border-bottom:1px solid black;"><input type="hidden" name="cur_tph['.$k.']" value="'.$v['TPH'].'"><input type="text" name="tph['.$k.']" value=""></td>
			';
		}

		$output .= '
			</tr>
		';
	}

	echo $output;

	exit();
}
?>
