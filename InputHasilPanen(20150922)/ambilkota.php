<?php

        include("../config/SQL_function.php"); 
		include("../config/db_config.php");
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');

		$afdeling = $_GET['afdeling'];
		$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
		//print_r($subID_BA_Afd);die();
		$sql_t_BA  = "SELECT ID_BLOK, BLOK_NAME FROM T_BLOK WHERE ID_BA_AFD = '$subID_BA_Afd$afdeling' ORDER BY ID_BLOK";
		$result_t_BA = oci_parse($con, $sql_t_BA);
					oci_execute($result_t_BA, OCI_DEFAULT);
					echo "<option>--select--</option>";
					while ($p=oci_fetch($result_t_BA)) {	
							$id_blok = oci_result($result_t_BA, "ID_BLOK");
							$blok_name = oci_result($result_t_BA, "BLOK_NAME");
					echo "<option value=\"$id_blok\">$id_blok - $blok_name</option>\n";

					}
?>


<script language='javascript'>
function showKab(){
<?php
	include("../config/SQL_function.php"); 
	include("../config/db_config.php");
	$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
	
	// membaca semua propinsi
	$sql_t_BA  = "SELECT ID_BA_AFD, ID_AFD  FROM T_AFDELING TAFD LEFT JOIN T_BUSSINESSAREA TBA ON TAFD.ID_BA = TBA.ID_BA WHERE TBA.ID_BA = '$subID_BA_Afd'";
	$result_t_BA = oci_parse($con, $sql_t_BA);
	oci_execute($result_t_BA, OCI_DEFAULT);	
	
	// membuat if untuk masing-masing pilihan propinsi beserta isi option untuk combobox kedua
	while ($data = oci_fetch($result_t_BA)){
		$prov = $data['id'];

		// membuat IF untuk masing-masing propinsi
		echo "if (document.form1.provinsi.value == \"".$prov."\")";
		//echo “if (document.form1.provinsi.value == \””.$prov.”\”)”;
		echo "{";

		// membuat option kota untuk masing-masing propinsi
		$query2 = "SELECT * FROM kota WHERE id_prov = ‘$prov’ ORDER BY id_kota ASC";
		$hasil2 = mysql_query($query2);
		$content = "document.getElementById(‘kot’).innerHTML = \”";
		while ($data2 = mysql_fetch_array($hasil2)){
			$content .= "<option value=’".$data2['id_kota']."‘>".$data2['kota']."</option>";
		}
		$content .= "\”";
		echo $content;
		echo "}\n";
	}
?>
}
</script>