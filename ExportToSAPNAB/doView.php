<?php
session_start();

if(isset($_SESSION["sql_Download_NABview"]) || isset($_GET["viewNO_NAB"]) || isset($_GET["viewAfd"])){
	$sql_Download_NABview 		= $_SESSION["sql_Download_NABview"];
	$NO_NAB		= $_GET["viewNO_NAB"];
	$Afd		= $_GET["viewAfd"];
	$_SESSION['rbtn_type'] = 'Detail';
	//echo "afdeling ". $valueAfdeling." id_ba ".$ID_BA . " id_cc ". $ID_CC . " date1 ". $date1 . " date2 ". $date2." TampilNAB ". $TampilNAB  ;


		//echo "benar";
			
		$sql_Laporan_NAB = $sql_Download_NABtxt. "and tn.NO_NAB = '$NO_NAB'	";
			
			$_SESSION["sql_Laporan_NAB"] = $sql_Download_NABview. "and tn.NO_NAB = '$NO_NAB' and ta.id_afd = nvl (decode ('$Afd', 'ALL', null, '$Afd'), ta.id_afd)";		
			
			//echo $_SESSION["sql_Laporan_NAB"];die();
			header("Location:../LaporanNAB/laporanNAB.php");
}
else{
$_SESSION[err] = "Please choose the options";
//header("Location:DownloadSAPCH.php");
}
?>