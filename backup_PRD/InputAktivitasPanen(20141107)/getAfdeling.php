<?php

        include("../config/SQL_function.php"); 
		include("../config/db_config.php");
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');

		$buss_area = $_GET['buss_area'];
		
		$sql_t_BA  = "select ID_AFD from T_AFDELING where ID_BA = '$buss_area' order by ID_AFD";
		$result_t_BA = oci_parse($con, $sql_t_BA);
					oci_execute($result_t_BA, OCI_DEFAULT);
					echo "<option value='0'>--select--</option>";
					while ($p=oci_fetch($result_t_BA)) {	
						$id_afd = oci_result($result_t_BA, "ID_AFD");
						$afd_name = oci_result($result_t_BA, "ID_AFD");
						echo "<option value=\"$id_afd\">$afd_name</option>\n";

					}
?>
