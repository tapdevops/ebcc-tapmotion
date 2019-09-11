<?php
	include ("config/db_connect.php");
	include ("jpgraph/src/jpgraph.php");
	include ("jpgraph/src/jpgraph_pie.php");
	//include ("jpgraph/src/jpgraph_pie3d.php");
	
	$werks = $_GET['id']; //get from url
	
	$con = connect();
	//'5121','5132','2121','4121','4122','4123','4221','4321','4421','5131'
	$sql  = "select ID_BA, NAMA_BA from T_BUSSINESSAREA where ID_BA in ($werks) group by ID_BA,NAMA_BA ";
	//$sql  = "select ID_BA, NAMA_BA from T_BUSSINESSAREA where ID_BA = $werks group by ID_BA,NAMA_BA ";
	$resultPt = oci_parse($con, $sql);
	oci_execute($resultPt, OCI_DEFAULT);
	$cek=0;
	
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
			if ($selisih != 0){
				$data_selisih[] = $selisih;
				$data_date[] = $tglTampil.' ('.$selisih.')';
			}
		}
			$cek = $cek + $totalImageDb;
	//echo "<br>";
	}
	//echo $data_selisih.'<br/>'.$data_date;
	
	if ($cek == 0){
		$data_selisih[] = 100;
		$data_date[] = 'NO IMAGE FOUND!';
	}
	// Some data and the labels
	$data   = $data_selisih;
	$labels = $data_date;
	 
	// Create the Pie Graph.
	$graph = new PieGraph(600,600);
	 
	// Set A title for the plot
	$graph->title->Set($namaPt);
	//$graph->title->SetFont(FF_VERDANA,FS_BOLD,12);
	$graph->title->SetColor('black');
	 
	// Create pie plot
	$p1 = new PiePlot($data);
	//$p1>SetCenter(0.5,0.5);
	$p1->SetSize(0.3);

	// Setup the labels to be displayed
	$p1->SetLabels($labels);
	 
	// This method adjust the position of the labels. This is given as fractions
	// of the radius of the Pie. A value < 1 will put the center of the label
	// inside the Pie and a value >= 1 will pout the center of the label outside the
	// Pie. By default the label is positioned at 0.5, in the middle of each slice.
	$p1->SetLabelPos(1);
	//$p1->SetLabelPos(0.6); 
	 
	// Setup the label formats and what value we want to be shown (The absolute)
	// or the percentage.
	$p1->SetLabelType(PIE_VALUE_PER);
	$p1->value->Show();
	//$p1->value->SetFont(FF_ARIAL,FS_NORMAL,9);
	$p1->value->SetColor('darkgray');
	//$p1->ExplodeAll();
	 
	// Add and stroke
	$graph->Add($p1);
	$graph->Stroke();
	
	
?>
