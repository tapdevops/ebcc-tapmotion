<?php

        include("../config/SQL_function.php"); 
		include("../config/db_config.php");
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');

  $kota = $_GET['kec'];
  $sql_t_BA  = "SELECT  ID_AFD FROM T_AFDELING where ID_BA='$kec' order by te.ID_AFD ";
	$result_t_BA = oci_parse($con, $sql_t_BA);
	
				   oci_execute($result_t_BA, OCI_DEFAULT);
					echo "<option>-All-</option>";
					while ($p=oci_fetch($result_t_BA)) {	
						  $id_kec = oci_result($result_t_BA, "NIK");
						 // $nama_kec = oci_result($result_t_BA, "EMP_NAME");
				               //  ECHO $nama_kec; DIE;
							 echo "<option value=\"$id_kec\">$id_kec</option>\n";

}

?>
