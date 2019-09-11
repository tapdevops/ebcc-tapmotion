<?php

        include("../config/SQL_function.php"); 
		include("../config/db_config.php");
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');

  $kota = $_GET['kec'];
  $sql_t_BA  = "select te.nik, te.emp_name from t_employee te inner join t_afdeling ta on te.id_ba_afd = ta.id_ba_afd where ID_BA='$kec' order by te.emp_name ";
	$result_t_BA = oci_parse($con, $sql_t_BA);
	
				   oci_execute($result_t_BA, OCI_DEFAULT);
					echo "<option>--select--</option>";
					while ($p=oci_fetch($result_t_BA)) {	
						  $id_kec = oci_result($result_t_BA, "NIK");
						  $nama_kec = oci_result($result_t_BA, "EMP_NAME");
				               //  ECHO $nama_kec; DIE;
							 echo "<option value=\"$id_kec\">$nama_kec - $id_kec</option>\n";

}

?>
