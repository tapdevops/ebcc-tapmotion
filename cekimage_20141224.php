<html>
<head>
</head>
<body>
<table>
<th>
	<td> PT </td>
	<?php 
	for ($i=30;$i>=0;$i--){
		echo date(mktime(0, 0, 0, date("m")-$i, date("d"),   date("Y")),"d");
		echo "doni $i";
	}
	?>
	<td>
</th>

<?php
	include("config/db_connect.php");
	$con = connect();
	//
	$sql  = "select ID_BA, NAMA_BA from T_BUSSINESSAREA where ID_BA in ('5121','5132','2121','4121','4122','4123','4221','4321','4421','5131') group by ID_BA,NAMA_BA ";
	$resultPt = oci_parse($con, $sql);
	oci_execute($resultPt, OCI_DEFAULT);
	while(oci_fetch($resultPt)){
		$namaPt = oci_result($resultPt, "NAMA_BA");
		$kodePt = oci_result($resultPt, "ID_BA");
		$image =null;
		$font="";
		$jumahImageCocok=0;
		$selisih=0;
		
		for ($i=0;$i<=30;$i++){
			$tgl  = date('ymd',mktime(0, 0, 0, date("m")  , date("d")-$i, date("Y")));
			$tglTampil  = date('d-m-Y',mktime(0, 0, 0, date("m")  , date("d")-$i, date("Y")));
			$jumahImageCocok=0;
			$image =null;
			$font="";
			$selisih=0;
			
			$sql  = " select picture_name from t_hasil_panen a where substr(id_rencana,29,4)= '$kodePt'   and substr(no_rekap_bcc,1,6) = '$tgl'";
			//where substr(id_nab_tgl,1,4)  = '$kodePt'   and substr(picture_name,24,6) = '$tgl'";
				
			//echo $sql."<br>";
			//die();
			$result = oci_parse($con, $sql);
			oci_execute($result, OCI_DEFAULT);
			while(oci_fetch($result)){
				$imageNow = oci_result($result, "PICTURE_NAME");
				$path = "/var/www/html/tap-motion/ebcc/array/uploads/".$imageNow;
				$image[]		= $imageNow;		
				if (file_exists($path)){
					$jumahImageCocok ++;
				}
			}
		$totalImageDb = count($image);
		if ($totalImageDb <> $jumahImageCocok){
			$font = "<font color=red>";
			$selisih = $totalImageDb - $jumahImageCocok;
		}else $selisih=0;
		
		//echo "<br>$font PT : $namaPt - Tanggal : $tglTampil Jumlah Image Database : ".count($image) ." - Jumlah Image File =" . $jumahImageCocok ." - Selisih = $selisih </font>";
		}
	echo "<br>";
	}
	
?>
</table>
</body>
</html>