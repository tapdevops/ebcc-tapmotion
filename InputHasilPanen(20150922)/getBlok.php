<?php

        include("../config/SQL_function.php"); 
		include("../config/db_config.php");
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');

		$afdeling = $_GET['afdeling'];
		$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
		
		$sql_t_BA  = "SELECT ID_BLOK, BLOK_NAME FROM T_BLOK WHERE ID_BA_AFD = '$subID_BA_Afd$afdeling'";
		$result_t_BA = oci_parse($con, $sql_t_BA);
					oci_execute($result_t_BA, OCI_DEFAULT);
					echo "<option>--select--</option>";
					while ($p=oci_fetch($result_t_BA)) {	
							$id_blok = oci_result($result_t_BA, "ID_BLOK");
							$blok_name = oci_result($result_t_BA, "BLOK_NAME");
					echo "<option value=\"$id_blok\">$id_blok - $blok_name</option>\n";

					}
?>
