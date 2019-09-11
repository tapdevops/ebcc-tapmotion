<?php
header("Refresh:3600");

include("config/db_connect.php");
$con = connect();

//get all BA di EBCC
$sql  = "
	SELECT ID_BA, NAMA_BA 
	FROM T_BUSSINESSAREA 
	WHERE ID_BA <> '9999'
	GROUP BY ID_BA,NAMA_BA 
	ORDER BY ID_BA, NAMA_BA
";
$resultPt = oci_parse($con, $sql);
oci_execute($resultPt, OCI_DEFAULT);

while(oci_fetch($resultPt)){
	$namaPt = oci_result($resultPt, "NAMA_BA");
	$kodePt = oci_result($resultPt, "ID_BA");
	$image =null;
	$font="";
	$jumahImageCocok=0;
	$selisih=0;
	
	//show nama PT
	echo "<br><b>PT : $kodePt - $namaPt</b><br>";

	// cek image 7 hari ke belakang
	for ($i=0;$i<=7;$i++){
		$tgl  = date('ymd',mktime(0, 0, 0, date("m"), date("d")-$i, date("Y")));
		$tglTampil  = date('d-m-Y',mktime(0, 0, 0, date("m"), date("d")-$i, date("Y")));
		$jumahImageCocok=0;
		$image =null;
		$font="";
		$selisih=0;

		$sql  = "
			SELECT tba.id_ba AS BA_CODE, thp.PICTURE_NAME
			FROM (
				SELECT id_rencana, tanggal_rencana
				FROM EBCC.t_header_rencana_panen
				WHERE TO_CHAR(tanggal_rencana, 'DD-MM-RRRR') = '$tglTampil'
			) thrp
			INNER JOIN EBCC.t_detail_rencana_panen tdrp
				ON thrp.id_rencana = tdrp.id_rencana
			INNER JOIN EBCC.t_hasil_panen thp
				ON tdrp.id_rencana = thp.id_rencana
				AND tdrp.no_rekap_bcc = thp.no_rekap_bcc
			INNER JOIN EBCC.t_blok tb
				ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
			INNER JOIN EBCC.t_afdeling ta
				ON tb.id_ba_afd = ta.id_ba_afd
			INNER JOIN EBCC.t_bussinessarea tba
				ON tba.id_ba = ta.id_ba
			WHERE tba.id_ba = '$kodePt'
				AND thp.NO_BCC NOT LIKE '%B' -- exclude BCP
		";
		$result = oci_parse($con, $sql);
		oci_execute($result, OCI_DEFAULT); 
		
		while(oci_fetch($result)){
			$imageNow = oci_result($result, "PICTURE_NAME");
			$ba_code = oci_result($result, "BA_CODE");
			$path = "/var/www/html/tap-motion/ebcc/array/uploads/".$imageNow; //path image EBCC + picture name
			$image[]= $imageNow;
			
			// jika file ada & sizenya >0 Byte maka dianggap file tersedia
			if (file_exists($path) && (filesize($path) > 0) ) {
				$jumahImageCocok ++;
			}
		}
		
		// compare jumlah image di database dengan jml file yang tersedia
		$totalImageDb = count($image);
		if ($totalImageDb <> $jumahImageCocok){
			$font = "<font color=red>";
			$selisih = $totalImageDb - $jumahImageCocok;
		}else {
			$selisih=0;
		}
		
		//show jumlah image database vs image fisik
		echo "$font Tanggal : $tglTampil. Jumlah Image Database = ".count($image) ." - Jumlah Image File = " . $jumahImageCocok ." - Selisih = $selisih. </font><br>";
		//echo "<tr><td>$tglTampil</td><td>".count($image) ."</td><td>" . $jumahImageCocok ."</td><td>$selisih. </td></tr>";

		// insert ke log untuk counter image
		$sqlDel  = "DELETE FROM TR_CEK_IMG WHERE WERKS = '".$kodePt."' AND DATE_IMG = TO_DATE('".$tglTampil."','DD-MM-RRRR')";
		$resl = oci_parse($con, $sqlDel);
		oci_execute($resl, OCI_DEFAULT); 
		oci_commit($con);
		oci_free_statement($resl);

		$sqlIns  = "
			INSERT INTO TR_CEK_IMG (WERKS, DATE_IMG, NUM_IMG, FILE_IMG, INSERT_TIME) 
			VALUES ('".$kodePt."',TO_DATE('".$tglTampil."','DD-MM-RRRR'),'".count($image)."','$jumahImageCocok', SYSDATE)
		";
		$resl = oci_parse($con, $sqlIns);
		oci_execute($resl, OCI_DEFAULT); 
		oci_commit($con);
		oci_free_statement($resl);
	}

	echo "<br>";
}
?>
