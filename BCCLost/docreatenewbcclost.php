<?php
session_start();
/*
echo "no_doc: ".$_POST["no_doc"]."<br>";
echo "tgl_doc: ".$_POST["tgl_doc"]."<br>";
echo "remark: ".$_POST["remark"]."<br>";
echo "NIK: ".$_SESSION["NIK"]."<br>";
echo "rowBCCRestanLost: ".$_SESSION["rowBCCRestanLost"]."<br>";
*/
//echo $_POST["no_doc"]."=".$_POST["remark"]."=".$_POST["tgl_doc"]."=".$_POST["end"]."=".$_SESSION["NIK"];
if(isset($_POST["no_doc"]) && isset($_POST["tgl_doc"]) && isset($_POST["remark"]) && isset($_SESSION["NIK"]) && isset($_SESSION["rowBCCRestanLost"])){
	
include("../config/SQL_function.php");
include("../config/db_connect.php");
$con = connect();

$no_doc = $_POST["no_doc"];
$remark = $_POST["remark"];
$tgl_doc = date("Y-m-d", strtotime($_POST["tgl_doc"]));
$NIK = $_SESSION["NIK"];
$rowBCCRestanLost = $_SESSION["rowBCCRestanLost"];
$action = true;
	
	$sql_sysdate  = "select to_char(SYSDATE,'MM/DD/YYYY') TGL from dual";
	$result_sysdate  = select_data($con,$sql_sysdate);
	$tgl_doc_1 = date("Y-m-d", strtotime($result_sysdate["TGL"]));
//echo "tgl_doc: ".$tgl_doc."<br>";
//echo "tgl_doc_1: ".$tgl_doc_1;
	if($tgl_doc=="1970-01-01")
	{
		$tgl_doc = $tgl_doc_1;
	}
//echo $no_doc." ".$remark." ".$tgl_doc;
	if($no_doc == "" || $remark == "" || $tgl_doc == ""){
		$_SESSION['err'] = "No Doc, Tgl Doc, atau Remark tidak boleh kosong";
		header("Location:createnewbcclost.php");
	}
	else{
		//CLEAR DATA FOR SELECTED NO_DOC
		$sql_del_t_bcc_lost = "DELETE FROM t_bcc_lost WHERE no_doc='$no_doc'";
		$result_del_t_bcc_lost = delete_data($con,$sql_del_t_bcc_lost);	
		
		$ctr = 0;
		for($x = 0 ; $x < $rowBCCRestanLost ; $x++) 
		{
			if(isset($_POST["chk$x"]) )
			{
				if($_POST["chk$x"] !== NULL)
				{
					$id_rencana = $_POST["idrencana$x"];
					$no_bcc = $_POST["chk$x"];
					$sql_t_bcc_lost = "INSERT INTO t_bcc_lost
					(no_doc, no_bcc, tgl_doc, remark, created_by, created_date) 
					VALUES
					('$no_doc', '$no_bcc', to_date('$tgl_doc','YYYY-MM-DD'), '$remark', '$NIK', sysdate)";
					$roweffect_t_bcc_lost = num_rows($con,$sql_t_bcc_lost);
					if($roweffect_t_bcc_lost > 0)
					{
						$insert[$ctr] = "BCC LOSSES has been created";
						$sql_t_hasil_panen = "UPDATE t_hasil_panen
						SET status_bcc='LOST' WHERE NO_BCC='$no_bcc' 
						AND ID_RENCANA = '$id_rencana'";
						$roweffect_t_hasil_panen = num_rows($con,$sql_t_hasil_panen);
					}
					else{
						$insert[$ctr] =  $ctr."NO Doc: ".$no_doc." with No BCC: ".$no_bcc." has not been created. Failed when insert t_bcc_lost";
					}
					
					/*$sql_t_hasil_panen = "UPDATE t_hasil_panen
					SET status_bcc='LOST' WHERE NO_BCC='$no_bcc'";
					$roweffect_t_hasil_panen = num_rows($con,$sql_t_hasil_panen);*/ //remarked by NBU 15.12.2015
					$ctr++;
				}
			}
		}
		
		if($action == true)
		{
			commit($con);
		}
		else
		{
			rollback($con);
		}
		
		$_SESSION["ctr"] = $ctr;
		for($y = 0 ; $y < $ctr ; $y++){
			$_SESSION["insert$y"] = $insert[$y];
		}
		
		//echo $roweffect_t_bcc_lost." - ".$roweffect_t_hasil_panen;
		header("Location:createnewbcclost.php");
	}
}
else{
	$_SESSION['err'] = "No Doc, Tgl Doc, atau Remark tidak boleh kosong";
	header("Location:createnewbcclost.php");
}

?>