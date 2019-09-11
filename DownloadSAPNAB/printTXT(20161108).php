<?php
session_start();

//if(isset($_SESSION["NIK"]) && isset($_SESSION["sql_Download_NAB"]) && isset($_POST["roweffec"])){
if(isset($_SESSION["NIK"]) && isset($_SESSION["sql_Download_NABtxt"]) && isset($_SESSION["LoginName"])){
	
$sql_Download_NABtxt = $_SESSION['sql_Download_NABtxt'];
$NIK = $_SESSION["NIK"]; 
$roweffecPost = $_POST["roweffec"];
$Login_Name = $_SESSION["LoginName"];

//echo $sql_Download_NAB. "<br> effect".$roweffecPost;
//$NIK_krani = '0000';
		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		
		header('Content-Type: application/txt');
		header('Content-Disposition: attachment;filename="NAB Report text.txt"');
		header('Cache-Control: max-age=0');
		
		if($roweffecPost  > 0){
			$ctr = 0;
			for($x = 0; $x<$roweffecPost ;$x++){
				if(isset($_POST["chk$x"])){
					
					if($_POST["chk$x"] !== NULL){
						$chk[$ctr] = $_POST["chk$x"];
						//echo "chk[$ctr] ".$chk[$ctr];
						$sql[$ctr] = $sql_Download_NABtxt. "and tn.id_nab_tgl = '$chk[$ctr]' ORDER BY tba.id_ba, tgl_nab, tn.no_nab"; //select where NO_NAB = '$chk[$ctr]';
						$result[$ctr] = oci_parse($con, $sql[$ctr]);
						//echo $sql[$ctr]."\r\n";
						oci_execute($result[$ctr], OCI_DEFAULT);
						while (oci_fetch($result[$ctr])) {						
							
							$ID_CC[$ctr][] 		= oci_result($result[$ctr], "ID_CC");
							$ID_BA[$ctr][] 		= oci_result($result[$ctr], "ID_BA");
							$ID_ESTATE[$ctr][] 	= oci_result($result[$ctr], "ID_ESTATE");
							$NO_NAB[$ctr][] 	= oci_result($result[$ctr], "NO_NAB");
							$ID_NAB_TGL[$ctr][] = oci_result($result[$ctr], "ID_NAB_TGL");
							$NO_BCC[$ctr][]		= oci_result($result[$ctr], "NO_BCC");
							$DATE[$ctr][] 		= oci_result($result[$ctr], "TGL_NAB");
							$NO_POLISI[$ctr][] 	= oci_result($result[$ctr], "NO_POLISI");
							//$STATUS[$ctr][] 	= oci_result($result[$ctr], "NO_POLISI");
						}
						//$roweffec = oci_num_rows($result[$ctr]);
						$roweffec[$ctr] = oci_num_rows($result[$ctr]);
						echo $roweffec[$ctr] . $NO_BCC[$ctr] . "\r\n";
						$ctr++;
					}
					else{
						//echo "<br>" .$_POST["chk$x"]." null";
					}
				}
				else{
					//echo "<br>" .$_POST["chk$x"]." tidak ada";
				}
			} //close for
			
			
			/*for($z = 0 ;$z < $roweffec; $z++){
				echo "$ID_CC[$z]	$ID_BA[$z]	$NO_NAB[$z]	$NO_BCC[$z]	$DATE[$z]	$NO_POLISI[$z]\r\n";
				//if($roweffec[$ctr] > 0){	
					// UPDATE SUDAH DIDOWNLOAD
					
						//for($q = 0 ;$q < $roweffec[$ctr]; $q++){
							//if($STATUS[$ctr][$q] == "BELUM"){
								//$sql_update_status = ""; //$NO_NAB[$ctr][$q];
								//$roweffec_update_status = num_rows($con,$sql_update_status);}
						//if($roweffec_update_status > 0){ //commit($con); }
						
					
				/*}
				else{
					echo "<br> gagal select ". $chk[$ctr];
				}*/
			//}//close for looping print*/
			//echo $ID_CC[0][0];
			$save = true;
			$temp_id_nab_tgl = "";
			for ($y = 0; $y < ($ctr+1); $y++) {
				for($z = 0 ;$z < $roweffec[$y]; $z++){
					$cek_count++;
					//echo $ID_CC[$y][$z]."	".$ID_BA[$y][$z]."	".$NO_NAB[$y][$z]."	".$NO_BCC[$y][$z]."	".$DATE[$y][$z]."	".$NO_POLISI[$y][$z]."\r\n";					
					//echo $ID_CC[$y][$z]."	".$ID_ESTATE[$y][$z]."	".$NO_NAB[$y][$z]."	".$NO_BCC[$y][$z]."	".$DATE[$y][$z]."	".$NO_POLISI[$y][$z]."\r\n";					
					
					$sql_update_status = "UPDATE T_NAB SET STATUS_DOWNLOAD = 'Y' WHERE ID_NAB_TGL = '".$ID_NAB_TGL[$y][$z]."'";
					$roweffec_value_status = num_rows($con,$sql_update_status);
					
					if($temp_id_nab_tgl !== $ID_NAB_TGL[$y][$z]){
						$sql_select = "select STATUS_DOWNLOAD from T_NAB
						WHERE ID_NAB_TGL = '".$ID_NAB_TGL[$y][$z]."'";
						$rs_select = oci_parse($con, $sql_select);
						oci_execute($rs_select, OCI_DEFAULT);
						oci_fetch($rs_select);
						$oldSTATUS_DOWNLOAD 	= oci_result($rs_select, "STATUS_DOWNLOAD");
						
						$sql_value_t_log_nab = "INSERT INTO t_log_nab 
						(InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_NAB_Tgl, CreEdit_From, Sync_Server, 
						New_Status_Download, Old_Status_Download) 
						VALUES
						('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_nab', '".$ID_NAB_TGL[$y][$z]."', 'Website', SYSDATE, 
						'Y', '$oldSTATUS_DOWNLOAD')" ;
						$roweffec_value_log = num_rows($con,$sql_value_t_log_nab);
						//echo $sql_value_t_log_nab. $roweffec_value_log;
					}
					
					if($roweffec_value_status = 0 || $roweffec_value_log = 0){
						$save = false;
					}
					$temp_id_nab_tgl =  $ID_NAB_TGL[$y][$z];
				}
			} 
//echo $sql_Download_NABtxt;
			if($save == true){
				
				commit($con);
			}
			else{
				rollback($con);
			}
			
		}
		else{
			echo "<br>" ."report tidak ada". $roweffecPost;
		}
		
}
else{
echo "<br>" ."krani blm login";
} 