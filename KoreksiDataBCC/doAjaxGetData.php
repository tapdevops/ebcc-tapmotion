<?php
	header("Access-Control-Allow-Origin: *");
	if(isset($_REQUEST['request'])){
		$request = $_REQUEST['request'];
		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		if($request=="getBlokData"){
			$BA = $_REQUEST['BA'];
			$AFD = $_REQUEST['AFD'];
			$TANGGAL_RENCANA = strtoupper(date("d-M-Y", strtotime($_REQUEST['TANGGAL_RENCANA'])));
			
			$query_blok  = "SELECT ID_BLOK, BLOK_NAME FROM T_BLOK WHERE ID_BA_AFD = '$BA$AFD' AND (INACTIVE_DATE IS NULL or INACTIVE_DATE >= '$TANGGAL_RENCANA') order by ID_BLOK";
			$result_blok = oci_parse($con, $query_blok);
			oci_execute($result_blok, OCI_DEFAULT);
			while ($p=oci_fetch($result_blok)) {	
				$id_blok = oci_result($result_blok, "ID_BLOK");
				$blok_name = oci_result($result_blok, "BLOK_NAME");
				
				$data[] = array(
					'value'=>"<option value=\"$id_blok\" selected='selected'>$id_blok - $blok_name</option>\n"
				);
				//echo "<option value=\"$id_blok\">$id_blok - $blok_name</option>\n";
				
			}
			
			echo(json_encode($data));
		}
	}
?>