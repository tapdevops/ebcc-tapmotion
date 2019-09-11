<?php

        include("../config/SQL_function.php"); 
		include("../config/db_config.php");
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');

		$comp_code = $_GET['comp_code'];
		
		$sql_t_BA  = "select distinct(ID_BA) as ID_BA from T_ALTERNATE_BA_GROUP TABA1 where ID_BA like '$comp_code%' order by ID_BA";
		$result_t_BA = oci_parse($con, $sql_t_BA);
					oci_execute($result_t_BA, OCI_DEFAULT);
					echo "<option>--select--</option>";
					while ($p=oci_fetch($result_t_BA)) {	
						$id_ba = oci_result($result_t_BA, "ID_BA");
						$ba_name = oci_result($result_t_BA, "ID_BA");
						echo "<option value=\"$id_ba\">$ba_name</option>\n";
					}
?>
