<?php
header("Refresh:3600");

include("config/db_connect.php");
$con = connect();

$sql  = "
	SELECT ID_BA, NAMA_BA 
	FROM T_BUSSINESSAREA 
	WHERE ID_BA <> '9999'
	GROUP BY ID_BA,NAMA_BA 
	ORDER BY ID_BA, NAMA_BA
";
$resultPt = oci_parse($con, $sql);
oci_execute($resultPt, OCI_DEFAULT);


	$image =null;
	$font="";
	$jumahImageCocok=0;
	$selisih=0;
	
		$jumahImageCocok=0;
		$image =null;
		$font="";
		$selisih=0;
		
		//ISI TANGGAL DAN KODE BA
		$tanggal = $_REQUEST['tanggal'];
		$kode_ba = $_REQUEST['kode'];
		
		$sql  = "
			SELECT tba.id_ba AS BA_CODE, thp.PICTURE_NAME, substr(thrp.id_rencana,10,15) as IMEI
			FROM (
				SELECT id_rencana, tanggal_rencana
				FROM EBCC.t_header_rencana_panen
				WHERE TO_CHAR(tanggal_rencana, 'DD-MM-RRRR') = '".$tanggal."'
			) thrp
			INNER JOIN EBCC.t_detail_rencana_panen tdrp
				ON thrp.id_rencana = tdrp.id_rencana
			INNER JOIN EBCC.t_hasil_panen thp
				ON tdrp.id_rencana = thp.id_rencana
				AND tdrp.no_rekap_bcc = thp.no_rekap_bcc
				--and thp.picture_name <> '0'
			INNER JOIN EBCC.t_blok tb
				ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
			INNER JOIN EBCC.t_afdeling ta
				ON tb.id_ba_afd = ta.id_ba_afd
			INNER JOIN EBCC.t_bussinessarea tba
				ON tba.id_ba = ta.id_ba
			WHERE tba.id_ba = '".$kode_ba."'
		";
		
		$result = oci_parse($con, $sql);
		oci_execute($result, OCI_DEFAULT); 
		
		while(oci_fetch($result)){
			$imageNow = oci_result($result, "PICTURE_NAME");
			$ba_code = oci_result($result, "BA_CODE");
			$imei = oci_result($result, "IMEI");
			$path = "http://tap-motion.tap-agri.com/ebcc/array/uploads/".$imageNow; //path image EBCC + picture name
			$image[]= $imageNow;
			
			$ch = curl_init($path);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, TRUE);
			curl_setopt($ch, CURLOPT_NOBODY, TRUE);

			$data = curl_exec($ch);
			$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
			if ($size > 0) {
				$jumahImageCocok ++;
			} else {
				echo $path."<br>";
			}
			curl_close($ch);
			 
		}
		
		$totalImageDb = count($image);
		if ($totalImageDb <> $jumahImageCocok){
			$font = "<font color=red>";
			$selisih = $totalImageDb - $jumahImageCocok;
		} else {
			$selisih=0;
		}
		
		echo "$font Tanggal : . Jumlah Image Database = ".count($image) ." - Jumlah Image File = " . $jumahImageCocok ." - Selisih = $selisih. </font><br>";


	echo "<br>";

?>
