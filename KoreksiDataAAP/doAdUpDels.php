<?php
session_start();

$UpdateAAP		= "";
$AddAAP			= "";
$NewAAPSubmit	= "";

include("../config/SQL_function.php");
include("../config/db_connect.php");
$con = connect();

if(isset($_POST['UpdateAAP']) || isset($_POST['AddAAP']) || isset($_POST['NewAAPSubmit']) || isset($_POST['DelStat'])){
$Login_Name = $_SESSION['LoginName'];

//field luasan		
$luasanIdRencana = $_POST['luasanIdRencana'];
$luasanNoRekapBcc = $_POST['luasanNoRekapBcc'];
$luasanPanenBaru = $_POST['luasanPanenBaru'];
$luasanafdblock = $_POST['luasanafdblock'];

//field gandeng
$GD_NIK_GANDENG = $_POST['GD_NIK_GANDENG'];
$GD_NIK_GANDENG = array_values(array_unique($GD_NIK_GANDENG));
		
//update luasan panen		
foreach( $luasanIdRencana as $key => $n ) {
  if ($luasanPanenBaru[$key] > 0){
	$sqlUpdLuasan = "UPDATE T_DETAIL_RENCANA_PANEN SET LUASAN_PANEN = '".$luasanPanenBaru[$key]."' WHERE ID_RENCANA = '".$n."' AND NO_REKAP_BCC = '".$luasanNoRekapBcc[$key]."'";
	$roweffecLuasan[] = num_rows($con,$sqlUpdLuasan);
	$MsgeffecLuasan[] = $luasanafdblock[$key].' - '.$luasanPanenBaru[$key];
  }
}

$GET_ID_RENCANA = "SELECT THRP.ID_RENCANA FROM T_HEADER_RENCANA_PANEN THRP
					WHERE THRP.ID_RENCANA like '%".$_SESSION['NikPemanen']."'
                    AND THRP.TANGGAL_RENCANA = TO_DATE( '".$_SESSION['tgl']."', 'DD-MON-RRRR')";
		
$RES_ID_RENCANA = oci_parse($con, $GET_ID_RENCANA);
oci_execute($RES_ID_RENCANA, OCI_DEFAULT);
	
$RES_ID_RENCANAX = oci_parse($con, $GET_ID_RENCANA);
oci_execute($RES_ID_RENCANAX, OCI_DEFAULT);

	
	while(oci_fetch($RES_ID_RENCANAX)){
		//delete detail gandeng	
		$DEL_GANDENG = "DELETE FROM t_detail_gandeng WHERE ID_RENCANA = '".oci_result($RES_ID_RENCANAX, "ID_RENCANA")."'";
		$roweffecGANDENG[] = num_rows($con,$DEL_GANDENG);
		
		//insert detail gandeng	
		for ($x = 0; $x < count(array_unique($GD_NIK_GANDENG)); $x++) {
			IF ($GD_NIK_GANDENG[$x] != ""){
				$strGandeng = explode('|', $GD_NIK_GANDENG[$x]);
				$nik = trim($strGandeng[0],' ');
				$nama = trim($strGandeng[1],' ');
			
				$GETLASTNOGANDENG = "SELECT MAX(ID_GANDENG) ID_GANDENG FROM T_DETAIL_GANDENG";
				$RESGETLASTNOGANDENG	= select_data($con,$GETLASTNOGANDENG);
				$NIDGANDENG = $RESGETLASTNOGANDENG['ID_GANDENG']+1;

				$SQLINS = "INSERT INTO t_detail_gandeng (ID_GANDENG, ID_RENCANA, NIK_GANDENG) 
								VALUES ('".$NIDGANDENG."', '".oci_result($RES_ID_RENCANAX, "ID_RENCANA")."', '".$nik."')";
				
				$roweffecINSGANDENG[] = num_rows($con,$SQLINS);						
				$MsgeffecINSGANDENG[] = oci_result($RES_ID_RENCANAX, "ID_RENCANA")." - ".$nik." - ".$nama;						
				
				$SQLINSLOG = "INSERT INTO t_log_detail_gandeng (
													INSERTUPDATE, 
													TGL_CREEDIT,
													NIK_CREEDITOR,
													LOGIN_NAME_CREEDITOR, 
													ON_TABLE, 
													ON_ID_GANDENG, 
													CREEDIT_FROM,
													SYNC_SERVER) 
							VALUES ('INSERT', SYSDATE , '".$nik."', '".$Login_Name."', 't_detail_gandeng', '".$NIDGANDENG."', 'Website', SYSDATE)" ;
				$roweffecINSGANDENGLOG = num_rows($con,$SQLINSLOG);
				
			}
		}
	}
	
/*	echo "Perubahan ha pada luasan panen : \n"; 
	for ($x = 0; $x < count(array_unique($MsgeffecLuasan)); $x++) {
		echo $MsgeffecLuasan[$x]." \n";
	} 
	echo "\n Pemanen gandeng yang terdaftar : \n";
	for ($x = 0; $x < count(array_unique($MsgeffecINSGANDENG)); $x++) {
		echo $MsgeffecINSGANDENG[$x]." \n";
	} 
*/	
commit($con);

}else{
	$_SESSION["err"] = "Please login";
	header("Location:../index.php");
}
			
?>