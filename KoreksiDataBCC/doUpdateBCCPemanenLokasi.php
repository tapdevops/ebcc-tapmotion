<?php
	session_start();
	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();		
	if(isset($_POST['NIK_Pemanen']) && isset($_POST['roweffec_BCC']) && isset($_POST['No_BCC']) && isset($_POST['ID_RENCANA']) && isset($_SESSION['NIK']) && isset($_SESSION['LoginName'])) {

		$afdOld = $_POST['afd_awal'];
		$afdNew = $_POST['AFDlabel'];
		$blokOld = $_POST['blok_awal'];
		$blokNew = $_POST['selectblok'];
		$tphOld = $_POST['tph_awal'];
		$tphNew = $_POST['selecttph'];
		$latTph = $_POST['lat_tph'];
		$latBcc = $_POST['lat_bcc'];
		$longTph = $_POST['long_tph'];
		$longBcc = $_POST['long_bcc'];

		$jarakOld = $_POST['jarakGEO'];
		$jarakNew = $_POST['jarakGEOAwal'];

		$no_bcc = $_POST['No_BCC'];

		$id_rencana = $_POST['ID_RENCANA'];

		$sql = "UPDATE T_HASIL_PANEN SET STATUS_DETIC = 'WEBSITE', STATUS_LOKASI = '1' WHERE ID_RENCANA = '{$id_rencana}' AND NO_BCC = '{$no_bcc}'";

		$stm = oci_parse($con, $sql);
		oci_execute($stm, OCI_COMMIT_ON_SUCCESS);
		oci_free_statement($stm);

		$query = "UPDATE T_LOG_HASIL_PANEN SET 
			NEW_VALUE_AFD = '{$afdNew}',
			OLD_VALUE_AFD = '{$afdOld}',
			NEW_VALUE_BLOK = '{$blokNew}',
			OLD_VALUE_BLOK = '{$blokOld}',
			NEW_VALUE_TPH = '{$tphNew}',
			OLD_VALUE_TPH = '{$tphOld}',
			VALUE_LAT_BCC = '{$latBcc}',
			VALUE_LAT_TPH = '{$latTph}',
			VALUE_LONG_BCC = '{$longBcc}',
			VALUE_LONG_TPH = '{$longTph}',
			NEW_VALUE_JARAK = '{$jarakNew}',
			OLD_VALUE_JARAK = '{$jarakOld}' 
			WHERE ON_NO_BCC = '{$no_bcc}'
		";

		$stmt = oci_parse($con, $query);
		oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
		oci_free_statement($stmt);

		echo json_encode('Berhasil');

		exit();
	}
?>