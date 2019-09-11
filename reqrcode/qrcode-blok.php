<?php
if (isset($_POST) && !empty($_POST)) {
	include("../config/db_config.php");
	$con_ebcc = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');

	$stid = oci_parse($con_ebcc, "SELECT distinct(TB.BLOK_NAME), TB.ID_BLOK FROM EBCC.T_DETAIL_RENCANA_PANEN TDRP
                         LEFT JOIN EBCC.T_HASIL_PANEN THP 
                          ON THP.ID_RENCANA = TDRP.ID_RENCANA
						  AND THP.NO_REKAP_BCC = TDRP.NO_REKAP_BCC
                          INNER JOIN EBCC.T_BLOK TB
                            ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
                         INNER JOIN EBCC.T_AFDELING TA
                            ON TB.ID_BA_AFD = TA.ID_BA_AFD
                         INNER JOIN EBCC.T_BUSSINESSAREA TBA
                            ON TA.ID_BA = TBA.ID_BA
                          WHERE THP.STATUS_TPH = 'MANUAL'
                          AND TBA.ID_BA = '{$_POST['werks']}' 
                         AND TA.ID_AFD = '{$_POST['afd']}'
						 ORDER BY BLOK_NAME");
	oci_execute($stid);

	$output = '<select name="blok">';
	$output .= '<option value="">--- Pilih ---</option>';
	while(($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
		$output .= '<option value="'.$row['ID_BLOK'].'">'.$row['BLOK_NAME'].'</option>';
	}
	$output .= '</select>';

	echo $output;

	exit();
}
?>