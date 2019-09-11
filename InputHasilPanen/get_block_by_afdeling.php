<?php

        include("../config/SQL_function.php"); 
		include("../config/db_config.php");
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');

		$propinsi = $_GET['propinsi'];
		$sql_t_BA  = "SELECT ID_BA, NAMA_BA FROM T_BUSSINESSAREA WHERE ID_CC='$propinsi' ORDER BY ID_BA ";
		$result_t_BA = oci_parse($con, $sql_t_BA);
					oci_execute($result_t_BA, OCI_DEFAULT);
					echo "<option>--select--</option>";
					while ($p=oci_fetch($result_t_BA)) {	
							$id_kabkot = oci_result($result_t_BA, "ID_BA");
							$nama_kabkot = oci_result($result_t_BA, "NAMA_BA");
					echo "<option value=\"$id_kabkot\">$id_kabkot : $nama_kabkot</option>\n";

					}
?>
