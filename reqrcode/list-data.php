<?php

if (isset($_POST) && !empty($_POST)) {
	include("../config/db_config.php");
	$con_ebcc = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
	//$nm = oci_parse($con_ebcc, "SELECT * FROM T_BLOK_TPH WHERE WERKS = '{$_POST['werks']}' AND AFD = '{$_POST['afd']}'");
	//$nm = oci_parse($con_ebcc, "SELECT WERKS, AFD, BLOCK_CODE, MAX(TPH) AS TPH FROM T_BLOK_TPH WHERE WERKS = '{$_POST['werks']}' AND AFD = '{$_POST['afd']}' GROUP BY WERKS, AFD, BLOCK_CODE ORDER BY WERKS, AFD, BLOCK_CODE");
	//echo "SELECT * FROM T_BLOK_TPH WHERE WERKS = '{$_POST['werks']}' AND AFD = '{$_POST['afd']}'";

	$query = "SELECT * FROM (
				SELECT TB.ID_BLOK,
                         TB.BLOK_NAME,
                         TDRP.ID_BA_AFD_BLOK,
                         MAX (THRP.TANGGAL_RENCANA) TANGGAL,
                         THP.NO_TPH,
                         CASE 
                            WHEN (TRT.DATE_CREATED IS NULL) 
                            THEN MAX (THRP.TANGGAL_RENCANA) 
                            ELSE TRT.DATE_CREATED
                         END
                         AS  DATE_CREATED
                    FROM EBCC.T_HEADER_RENCANA_PANEN THRP
                         LEFT JOIN EBCC.T_DETAIL_RENCANA_PANEN TDRP
                            ON THRP.ID_RENCANA = TDRP.ID_RENCANA
                         LEFT JOIN EBCC.T_HASIL_PANEN THP
                            ON TDRP.ID_RENCANA = THP.ID_RENCANA
                               AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC
                               AND THP.NO_REKAP_BCC = TDRP.NO_REKAP_BCC
                         JOIN EBCC.T_BLOK TB
                            ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
                         JOIN EBCC.T_AFDELING TA
                            ON TB.ID_BA_AFD = TA.ID_BA_AFD
                         JOIN EBCC.T_BUSSINESSAREA TBA
                            ON TA.ID_BA = TBA.ID_BA
                         LEFT JOIN (SELECT WERKS, AFD, BLOK, TPH, MAX(DATE_CREATED) DATE_CREATED FROM EBCC.T_REPRINT_TPH GROUP BY WERKS, AFD, BLOK, TPH) TRT
                         ON   TRT.WERKS = TBA.ID_BA 
                         AND TRT.AFD = TA.ID_AFD 
                         AND TRT.BLOK = TB.ID_BLOK
                         AND TRT.TPH =  THP.NO_TPH
                   WHERE TBA.ID_BA = '{$_POST['werks']}' 
						 AND TA.ID_AFD = '{$_POST['afd']}'
						 AND TB.ID_BLOK = '{$_POST['blok']}'                
                         AND THP.STATUS_TPH = 'MANUAL'
                GROUP BY TB.ID_BLOK,
                         TB.BLOK_NAME,
                         TDRP.ID_BA_AFD_BLOK,
                         THP.NO_TPH, 
                         TRT.DATE_CREATED
                ORDER BY ID_BA_AFD_BLOK, THP.NO_TPH
                )
                 --WHERE TANGGAL >= DATE_CREATED";

	//$nm = oci_parse($con_ebcc,$query);
	//oci_execute($nm);
	
	//$rows = oci_fetch_array($nm, OCI_ASSOC);
	$output = '';

	$result = oci_parse($con_ebcc, $query);
			oci_execute($result, OCI_DEFAULT);
			while (oci_fetch($result)) {	
				$output .= '
					<tr id="tr'.oci_result($result,'BLOK_NAME').oci_result($result,'NO_TPH').'">
						<td style="padding:10px; border-bottom:1px solid black;"><input type="hidden" id="'.oci_result($result,'BLOK_NAME').oci_result($result,'NO_TPH').'" value="'.oci_result($result,'TANGGAL').'">'.oci_result($result,'TANGGAL').'</td>
						<td style="padding:10px; border-bottom:1px solid black;">'.oci_result($result,'ID_BLOK').'</td>
						<td style="padding:10px; border-bottom:1px solid black;">'.oci_result($result,'BLOK_NAME').'</td>
						<td style="padding:10px; border-bottom:1px solid black;">'.oci_result($result,'NO_TPH').'</td>
						<td style="padding:10px; border-bottom:1px solid black;"><input type="button" onclick="generate_code(\''.oci_result($result,'ID_BLOK').'\',\''.oci_result($result,'BLOK_NAME').'\',\''.oci_result($result,'NO_TPH').'\');" value="GENERATE" style="font-weight:bold; font-size:15px;"></td>
					</tr>';
			}

	echo $output;
	die();
	exit();
}
?>