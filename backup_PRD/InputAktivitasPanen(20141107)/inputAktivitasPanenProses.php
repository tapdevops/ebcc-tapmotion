<?php
session_start();
include("../config/SQL_function.php");
include("../config/db_connect.php");
include("../InputAktivitasPanen/inputAktivitasPanenClass.php");

//error_reporting(E_ALL);
//ini_set('display_errors','On');	
$inAktivitasPanen = new input_Aktivitas_Panen;
$inAktivitasPanen->i_rowCount = $_POST['row_count'];
$inAktivitasPanen->s_username 	= $_SESSION['NIK'];
$inAktivitasPanen->s_loginname 	= $_SESSION['LoginName'];
$status_gandeng = 0;
$return_berhasil = 0;
for($i = 1; $i <= $inAktivitasPanen->i_rowCount; $i++){
	$inAktivitasPanen->s_cmb_compcode = $_POST['cmb_compcode' . $i];
	$inAktivitasPanen->s_cmb_BA = $_POST['cmb_BA' . $i];
	$inAktivitasPanen->s_cmb_Afd = $_POST['cmb_Afd' . $i];
	$inAktivitasPanen->s_nikGandeng = $_POST['nikGandeng' . $i];
	$inAktivitasPanen->s_idRencana = $_POST['id_rencana' . $i];
	if(($inAktivitasPanen->s_cmb_compcode != "0" || $inAktivitasPanen->s_cmb_compcode == " --select-- ") && $inAktivitasPanen->s_cmb_BA != "0" 
		&& $inAktivitasPanen->s_cmb_Afd != "0" && $inAktivitasPanen->s_nikGandeng != ""){
	
		$status_gandeng = 1;
		$return_updateTHRP = $inAktivitasPanen->Update_T_H_RencanaPanen();
		if($return_updateTHRP == "1"){
			$return_insertTDG = $inAktivitasPanen->Insert_T_Detail_Gandeng();
		}
	}
}
$inAktivitasPanen->i_countRow = $_POST['countRow'];

for($j = 1; $j <= $inAktivitasPanen->i_countRow; $j++){
	$inAktivitasPanen->s_id_ba_afd_blok = $_POST['id_ba_afd_blok' . $j];
	$inAktivitasPanen->s_no_rekap_bcc = $_POST['no_rekap_bcc' . $j];
	$inAktivitasPanen->s_idRencana = $_POST['id_rencana' . $j];
	$inAktivitasPanen->f_luasan_panen = $_POST['t_luasan_panen' . $j];
	
	$return_update_TDRP = $inAktivitasPanen->Update_T_D_Rencana_Panen();
	
	//$return_update_TDRP = 1;
	if($return_update_TDRP == "1"){
		$inAktivitasPanen->i_BT_Pokok = $_POST['t_bt_pokok' . $j];
		$inAktivitasPanen->i_BT_Piringan = $_POST['t_bt_piringan' . $j];
		$inAktivitasPanen->i_PB_Piringan = $_POST['t_pb_piringan' . $j];
		$inAktivitasPanen->i_Buah_Matahari = $_POST['t_buahmatahari' . $j];
		$inAktivitasPanen->s_id_BCC = $_POST['no_bcc' . $j];
		$return_insert_THPK_11 = $inAktivitasPanen->Insert_T_Hasil_PK('11');
		$return_insert_THPK_12 = $inAktivitasPanen->Insert_T_Hasil_PK('12');
		$return_insert_THPK_13 = $inAktivitasPanen->Insert_T_Hasil_PK('13');
		$return_insert_THPK_14 = $inAktivitasPanen->Insert_T_Hasil_PK('14');
		if($return_insert_THPK_11 == '1' && $return_insert_THPK_12 == '1' && $return_insert_THPK_13 == '1' && $return_insert_THPK_14 == '1'){
			$return_berhasil = '1';
		}
	}
}echo $return_berhasil;
//}
//header('Location: inputPengirimanPanen.php?s=add&f=ok');

?>