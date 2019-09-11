<?php

        include("../config/SQL_function.php"); 
		include("../config/db_config.php");
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');

		$id_ba = $_GET['id_ba'];
		//SAMPE SINI YAAAAAAAAAA
		$sql_t_BA  = "select distinct(SUBSTR(ID_BA, 0,2)) as ID_BA from T_ALTERNATE_BA_GROUP TABA1 where 
														ID_GROUP_BA = (select ID_GROUP_BA from T_ALTERNATE_BA_GROUP 
														where ID_BA = '$id_ba') order by ID_BA";
		$result_t_BA = oci_parse($con, $sql_t_BA);
					oci_execute($result_t_BA, OCI_DEFAULT);
					echo "<option>--select--</option>";
					while ($p=oci_fetch($result_t_BA)) {	
						$id_ba = oci_result($result_t_BA, "ID_BA");
						$ba_name = oci_result($result_t_BA, "ID_BA");
						echo "<option value=\"$id_ba\">$ba_name</option>\n";
					}
?>
