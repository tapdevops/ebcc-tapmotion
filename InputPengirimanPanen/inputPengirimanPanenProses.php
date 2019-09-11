<?php
session_start();
include("../config/SQL_function.php");
include("../config/db_connect.php");
include("../InputPengirimanPanen/inputPengirimanPanenClass.php");

//error_reporting(E_ALL);
//ini_set('display_errors','On');	
$inPengirimanPanen = new input_Pengiriman_Panen;

$inPengirimanPanen->i_Row = $_POST['countRow'];
$inPengirimanPanen->s_username 	= $_SESSION['NIK'];
$inPengirimanPanen->s_loginname = $_SESSION['LoginName'];

$inPengirimanPanen->s_tgl_kirim = $_POST["datepicker"];
$inPengirimanPanen->s_idBA = $_POST['ID_BA2'];
$inPengirimanPanen->s_no_nab = strtoupper($_POST['NoNAB']);
$inPengirimanPanen->i_tipe_order = $_POST['TypeOrder'];
if($inPengirimanPanen->i_tipe_order == '1'){
	$inPengirimanPanen->s_tipe_order = 'INTERNAL';
	$inPengirimanPanen->i_no_polisi = $_POST['noPolisi1'];
	$inPengirimanPanen->s_id_int_order = $_POST['noIntOrder1'];
	$inPengirimanPanen->i_nik_supir = $_POST['nikSupir1'];
}else if($inPengirimanPanen->i_tipe_order == '2'){
	$inPengirimanPanen->s_tipe_order = 'EXTERNAL';
	$inPengirimanPanen->i_no_polisi = $_POST['noPolisi2'];
	$inPengirimanPanen->s_id_int_order = "-";
	$inPengirimanPanen->i_nik_supir = $_POST['namaSupir2'];
}

$inPengirimanPanen->nik_tkg_muat1 = $_POST['nikTkgMuat1'];
$inPengirimanPanen->nik_tkg_muat2 = $_POST['nikTkgMuat2'];
$inPengirimanPanen->nik_tkg_muat3 = $_POST['nikTkgMuat3'];
$datePlan = date('Y-m-d', strtotime($inPengirimanPanen->s_tgl_kirim));
$inPengirimanPanen->s_id_nab_tgl = $inPengirimanPanen->s_idBA.$inPengirimanPanen->s_no_nab.$datePlan;
//echo $inPengirimanPanen->s_id_nab_tgl;die();
$returnNAB = $inPengirimanPanen->insert_T_NAB();

if($returnNAB == '1'){
	for($i = 1; $i <= $inPengirimanPanen->i_Row; $i++){ 
		$inPengirimanPanen->s_PlanID = $_POST["id_rencana" . $i];
		$inPengirimanPanen->s_no_bcc = $_POST["t_no_bcc" . $i];
		if($inPengirimanPanen->s_PlanID <> "" && $inPengirimanPanen->s_no_bcc <> ""){
			$returnDetail = $inPengirimanPanen->Update_T_Hasil_Panen();
		}
	}echo $inPengirimanPanen->s_no_nab . " . # . " . $returnDetail;
}else{
	echo "ERROR";
}



//header('Location: inputPengirimanPanen.php?s=add&f=ok');

?>