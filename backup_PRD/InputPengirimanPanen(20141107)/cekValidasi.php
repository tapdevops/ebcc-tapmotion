<?php

        include("../config/SQL_function.php"); 
		include("../config/db_config.php");
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
		
		$nab = $_POST['nab'];
		$ba = $_POST['v_ba'];
		$afd = $_POST['var_afd'];
		$id_nab = $ba.$nab;
		
		$sql_t_IDNAB  = "select ID_NAB_TGL from T_NAB where ID_NAB_TGL like '$id_nab%'";
		
		$s_id_nab = "";
		$result_t_IDNAB = oci_parse($con, $sql_t_IDNAB);
					oci_execute($result_t_IDNAB, OCI_DEFAULT);
					while ($p=oci_fetch($result_t_IDNAB)) {	
							$s_id_nab = oci_result($result_t_IDNAB, "ID_NAB_TGL");
					}
		
		if($s_id_nab == ""){
			echo "kosong";
		}else{
			echo $s_id_nab;
		}
?>
