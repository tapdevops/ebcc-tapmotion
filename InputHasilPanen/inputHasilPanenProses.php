<?php
session_start();
include("../config/SQL_function.php");
include("../config/db_connect.php");
include("../InputHasilPanen/inputHasilPanenClass.php");

//error_reporting(E_ALL);
//ini_set('display_errors','On');	

$inHasilPanen = new input_Hasil_Panen;
$status_free = 0;
$status_free_input = 0;
$inHasilPanen->s_PlanDate = $_POST["datepicker"];
$inHasilPanen->s_MandorNIK = $_POST["nikMandor"];
$nama_mandor = $_POST['namaMandor'];
$inHasilPanen->s_KraniNIK = $_POST["txtNik"];
$nama_krani = $_POST['txtNama'];
$inHasilPanen->s_PemanenNIK = $_POST['nikPemanen'];
$inHasilPanen->i_Row = $_POST['countRow'];
$inHasilPanen->s_idBA = $_POST['ID_BA2'];
$inHasilPanen->s_afd = $_POST['afdeling'];
$v_blok = "";
$v_tph = "";
$v_tiket = "";
$returnValidasi_compare = 0;
$inHasilPanen->s_username 	= $_SESSION['NIK'];
$inHasilPanen->s_loginname 	= $_SESSION['LoginName'];
						
for($i = 1; $i <= $inHasilPanen->i_Row; $i++){
	$v_blok = $_POST["blok" . $i];
	if (isset($_POST["tphold" . $i]) && !empty($_POST["tphold" . $i])) {
		$v_tph = $_POST["tphold" . $i];
	} else {
		$v_tph = $_POST["tphnew" . $i];
	}
	$v_tiket = $_POST["ticket" . $i];
	for($j = 1; $j <= $inHasilPanen->i_Row; $j++){
		if($i <> $j){
			$v_blok_compare = $_POST["blok" . $j];
			if (isset($_POST["tphold" . $j]) && !empty($_POST["tphold" . $j])) {
				$v_tph_compare = $_POST["tphold" . $j];
			} else {
				$v_tph_compare = $_POST["tphnew" . $j];
			}
			$v_tiket_compare = $_POST["ticket" . $j];
			if($v_blok == $v_blok_compare && $v_tph == $v_tph_compare && $v_tiket == $v_tiket_compare){
				echo  "Sama " . $i . " " . $j;
				$status_free_input = 1;
				exit();
			}
		}
	}
}

if($status_free_input == 0){
	for($i = 1; $i <= $inHasilPanen->i_Row; $i++){ 
		$inHasilPanen->s_blok = $_POST["blok" . $i];
		if (isset($_POST["tphold" . $i]) && !empty($_POST["tphold" . $i])) {
			$inHasilPanen->s_TPH = $_POST["tphold" . $i];
		} else {
			$inHasilPanen->s_TPH = $_POST["tphnew" . $i];
		}
		$inHasilPanen->s_deliveryTicket = $_POST["ticket" . $i];
		$returnValidasi = $inHasilPanen->cek_ValidasiBCC();
		if($returnValidasi == 1){
			echo "Ada " . $i ;
			$status_free = 1;
			exit();
		}
	}
	if($status_free == 0){
		$datePlan = date('Ymd', strtotime($inHasilPanen->s_PlanDate));

		$inHasilPanen->s_PlanID = $datePlan . ".MANUAL." . $inHasilPanen->s_PemanenNIK;
		$count = 0;
		$returnHeader = $inHasilPanen->insert_HeaderRencanaPanen();
		$split1 = explode(" . # . ", $returnHeader);
		$inHasilPanen->s_PlanID2 = $split1[1];
		if($split1[0] == "1"){
			for($i = 1; $i <= $inHasilPanen->i_Row; $i++){ 
				$inHasilPanen->s_blok = $_POST["blok" . $i];
				if (isset($_POST["tphold" . $i]) && !empty($_POST["tphold" . $i])) {
					$inHasilPanen->s_TPH = $_POST["tphold" . $i];
				} else {
					$inHasilPanen->s_TPH = $_POST["tphnew" . $i];
				}
				$inHasilPanen->s_deliveryTicket = $_POST["ticket" . $i];
				$inHasilPanen->i_mentah = $_POST["mentah" . $i];
				$inHasilPanen->i_mengkal = $_POST["mengkal" . $i];
				$inHasilPanen->i_masak = $_POST["masak" . $i];
				$inHasilPanen->i_toomasak = $_POST["toomasak" . $i];
				$inHasilPanen->i_busuk = $_POST["busuk" . $i];
				$inHasilPanen->i_jangkos = $_POST["jangkos" . $i];
				$inHasilPanen->i_buborsi = $_POST["buborsi" . $i];
				$inHasilPanen->i_tangkaipanjang = $_POST["tangkai_panjang" . $i];
				$inHasilPanen->i_abnormal = $_POST["abnormal" . $i];
				$inHasilPanen->i_hama = $_POST["hama" . $i];
				$inHasilPanen->i_alas = $_POST["alas" . $i];
				$inHasilPanen->i_ttlbrondolan = $_POST["brondolan" . $i];
				$returnDetail = $inHasilPanen->insert_DetailRencanaPanen($inHasilPanen->s_PlanID2);
				$split = explode(" . # . ", $returnDetail);
				if($split[0] == '1'){
					if($no_bcc == ""){
						$no_bcc = $split[1] . " . # . ";
					}else{
						$no_bcc = $no_bcc . $split[1] . " . # . ";
					}
					$count += 1;
					$status_berhasil = $split[0];
				}
			}
		}else{
			$status_berhasil = 0;
			$count = 0;
			$no_bcc = 0;
		}
		echo $status_berhasil . " . # . " . $count . " . # . " . $no_bcc;
	}
}
//echo "1";


?>