<?php
session_start();

if(isset($_SESSION["NIK"]) && isset($_SESSION["sql_Download_NABtxt"]) && isset($_SESSION["LoginName"])){
	
$sql_Download_NABtxt = $_SESSION['sql_Download_NABtxt'];
$NIK = $_SESSION["NIK"]; 
$roweffecPost = $_POST["roweffec"];
$Login_Name = $_SESSION["LoginName"];

		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		
		
		if($roweffecPost  > 0){
			$total_fail = 0;
			$total_posting = 0;
			$total_export = 0;
			//$total_rec = 0;
			$ctr = 0;
			for($x = 0; $x<$roweffecPost ;$x++){
				if(isset($_POST["chk$x"])){
					
					if($_POST["chk$x"] !== NULL){
						$chk[$ctr] = $_POST["chk$x"];
						$sql[$ctr] = $sql_Download_NABtxt. "and tn.id_nab_tgl = '$chk[$ctr]' ORDER BY tba.id_ba, tgl_nab, tn.no_nab"; //select where NO_NAB = '$chk[$ctr]';
						
						$result[$ctr] = oci_parse($con, $sql[$ctr]);
						oci_execute($result[$ctr], OCI_DEFAULT);
						while (oci_fetch($result[$ctr])) {						
							
							$ID_CC[$ctr][] 		= oci_result($result[$ctr], "ID_CC");
							$PROFILE_NAME[$ctr][] 		= oci_result($result[$ctr], "PROFILE_NAME");
							$ID_BA[$ctr][] 		= oci_result($result[$ctr], "ID_BA");
							$ID_ESTATE[$ctr][] 	= oci_result($result[$ctr], "ID_ESTATE");
							$NO_NAB[$ctr][] 	= oci_result($result[$ctr], "NO_NAB");
							$ID_NAB_TGL[$ctr][] = oci_result($result[$ctr], "ID_NAB_TGL");
							$NO_BCC[$ctr][]		= oci_result($result[$ctr], "NO_BCC");
							$DATE[$ctr][] 		= oci_result($result[$ctr], "TGL_NAB");
							$NO_POLISI[$ctr][] 	= oci_result($result[$ctr], "NO_POLISI");
							
						}
						
						$roweffec[$ctr] = oci_num_rows($result[$ctr]);
						
						$ctr++;
					}
					else{
						
					}
					
				}
				else{
				
				}
			} //close for
			
			
			$save = true;
			$temp_id_nab_tgl = "";
			for ($y = 0; $y < ($ctr+1); $y++) {
				$success_count = 0;
				$fail_count = 0;
				
				$record_count = 0;
				$export_count = 0;
				$posting_count = 0;
				
				
				for($z = 0 ;$z < $roweffec[$y]; $z++){
					//echo $ID_CC[$y][$z]."	".$ID_ESTATE[$y][$z]."	".$NO_NAB[$y][$z]."	".$NO_BCC[$y][$z]."	".$DATE[$y][$z]."	".$NO_POLISI[$y][$z]."\r\n";					
					//check ke table t_status_to_sap_ebcc
					
					//Edited by Ardo, 07-11-2016 : Issue Log Export to SAP NAB
					$query_cek_export = "
					SELECT * FROM T_STATUS_TO_SAP_NAB WHERE 
					COMP_CODE = '".$ID_CC[$y][$z]."' AND 
					PROFILE_NAME = '".$PROFILE_NAME[$y][$z]."' AND
					ESTATE_CODE = '".$ID_ESTATE[$y][$z]."' AND 
					ID_NAB_TGL = '".$ID_NAB_TGL[$y][$z]."' AND 
					NO_BCC = '".$NO_BCC[$y][$z]."'
					";
					
					$result_export_cek = oci_parse($con, $query_cek_export);
					oci_execute($result_export_cek, OCI_DEFAULT);
					$result_export_count = oci_fetch_all($result_export_cek, $result_export_record, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
					
					
					//echo $query_cek_export; exit;
					if($result_export_count==1){
						
						if($result_export_record[0]['POST_STATUS']!="" and $result_export_record[0]['POST_TIMESTAMP']!=""){
							//$STATUS[] = 'POSTING';
							$posting_count++;
						} else if($result_export_record[0]['EXPORT_STATUS']!="" and $result_export_record[0]['EXPORT_TIMESTAMP']!=""){
							//$STATUS[] = 'EXPORT';
							$export_count++;
						} else if($result_export_record[0]['EXPORT_STATUS']=="" and $result_export_record[0]['EXPORT_TIMESTAMP']==""){
							
							//Edited by Ardo, 07-11-2016 : Issue Log Export to SAP NAB
							$export_data = "
							UPDATE T_STATUS_TO_SAP_NAB SET
							TANGGAL = '".$DATE[$y][$z]."', 
							NO_POLISI = '".$NO_POLISI[$y][$z]."',
							NO_NAB = '".$NO_NAB[$y][$z]."',
							EXPORT_STATUS = 'X',
							EXPORT_TIMESTAMP = TO_DATE('".date('m-d-Y H:i:s')."', 'MM-DD-YYYY HH24:MI:SS')
							WHERE COMP_CODE = '".$ID_CC[$y][$z]."' AND
							PROFILE_NAME = '".$PROFILE_NAME[$y][$z]."' AND
							ESTATE_CODE = '".$ID_ESTATE[$y][$z]."' AND
							ID_NAB_TGL = '".$ID_NAB_TGL[$y][$z]."' AND
							NO_BCC = '".$NO_BCC[$y][$z]."'
							";
							//echo $export_data; exit;
							$num_export = num_rows($con, $export_data);
							
							if($num_export==1){
								$success_count++;
							} else {
								$fail_count++;
							}
						}
					} else if($result_export_count>1){
						//$STATUS[] = 'Gagal export BCC';
						$fail_count++;
					} else {
						
						//Edited by Ardo, 07-11-2016 : Issue Log Export to SAP NAB
						$export_data = "
						INSERT INTO T_STATUS_TO_SAP_NAB(
						COMP_CODE,
						PROFILE_NAME,
						ESTATE_CODE,
						NO_NAB,
						NO_BCC, 
						TANGGAL, 
						NO_POLISI,
						ID_NAB_TGL,
						EXPORT_STATUS,
						EXPORT_TIMESTAMP
						) VALUES (
						'".$ID_CC[$y][$z]."',
						'".$PROFILE_NAME[$y][$z]."',
						'".$ID_ESTATE[$y][$z]."',
						'".$NO_NAB[$y][$z]."',
						'".$NO_BCC[$y][$z]."',
						'".$DATE[$y][$z]."',
						'".$NO_POLISI[$y][$z]."',
						'".$ID_NAB_TGL[$y][$z]."',
						'X',
						TO_DATE('".date('m-d-Y H:i:s')."', 'MM-DD-YYYY HH24:MI:SS')
						)
						";
						//echo $export_data; exit;
						$num_export = num_rows($con, $export_data);
						
						if($num_export==1){
							$success_count++;
						} else {
							$fail_count++;
						}
					}
					
				}
				//$total_rec++;
				if($posting_count>0){
					$total_posting++;
				} else if($export_count>0 and $posting_count==0){
					$total_export++;
				}  else if($export_count==0 and $fail_count>0){
					$total_fail++;
				} 
			} 
			
			
			
		}
		
		commit($con);
		
		include("../include/Header.php");
		?>
		<style type="text/css">
			a:link {
			text-decoration: none;
			}
			a:visited {
			text-decoration: none;
			}
			a:hover {
			text-decoration: none;
			}
			a:active {
			text-decoration: none;
			}
			body,td,th {
			font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			font-size: 16px;
			font-weight:normal;
			}
		</style>
		
	<table width="978" height="390" border="0" align="center">
		<tr>
			<th height="100" scope="row" align="center">
				<table border="0" id="setbody2">
					<tr>
						<td height="50" colspan="4" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>Export To SAP - NAB</strong></span></td>
						
					</tr>
					<tr>
						<td colspan="4" valign="bottom" style=" border-bottom:solid #000">LOKASI</td>
						<td colspan="3" valign="bottom" style=" border-bottom:solid #000"></td>
					</tr>
					
					<tr>
						<td width="138">Company Name</td>
						<td width="8">:</td>
						<td width="385">
							<input type="text" value="<?=$_SESSION['Comp_Name']?>" style="background-color:#CCC; color:black; width: 300px; height:25px; font-size:15px" onmousedown="return false" disabled />
						</td>
						<td width="28">&nbsp;</td>
						<td width="96"><span >Start Date</span></td>
						<td width="10">:</td>
						<td width="277">
							<input type="text" value="<?=$_SESSION['date1']?>" style="background-color:#CCC; color:black; width: 300px; height:25px; font-size:15px" onmousedown="return false" disabled />
						</td>
					</tr>
					<tr>
						<td>Business Area</td>
						<td>:</td>
						<td>
							<input type="text" value="<?=$_SESSION['ID_BA2']?>" style="background-color:#CCC; color:black; width: 300px; height:25px; font-size:15px" onmousedown="return false" disabled />
						</td>
						<td width="28">&nbsp;</td>
						<td width="96"><span >End Date</span></td>
						<td width="10">:</td>
						<td width="277">
							<input type="text" value="<?=$_SESSION['date2']?>" style="background-color:#CCC; color:black; width: 300px; height:25px; font-size:15px" onmousedown="return false" disabled />
						</td>
						
						
					</tr>
					<tr>
						<td> Afdeling</td>
						<td>:</td>
						<td style="font-size:16px">
							<input type="text" value="<?=$_SESSION['valueAfdeling']?>" style="background-color:#CCC; color:black; width: 300px; height:25px; font-size:15px" onmousedown="return false" disabled />
						</td>
					</tr>
					
				</table>
			</th>
		</tr>
		<tr>
			<td colspan="4" valign="top">
				<table width="1134" border="1">
					<tr bgcolor="#9CC346">
						<td width="250" rowspan="2" align="center" style="font-size:14px" id="bordertable">Jumlah NAB yang di Export</td>
						<td width="250" colspan="3" align="center" style="font-size:14px" id="bordertable">Status Export</td>
						
					</tr>
					<tr bgcolor="#9CC346"> 
						<td align="center" style="font-size:14px" id="bordertable">Gagal Export</td>
						<td align="center" style="font-size:14px" id="bordertable">Sudah Pernah Export</td>
						<td align="center" style="font-size:14px" id="bordertable">Sudah Posting</td>
					</tr>
					<tr>
						<td align="center" id="bordertable"><?= $ctr ?></td>
						<td align="center" id="bordertable"><?= ($total_fail==0)?"-":$total_fail ?></td>
						<td align="center" id="bordertable"><?= ($total_export==0)?"-":$total_export ?></td>
						<td align="center" id="bordertable"><?= ($total_posting==0)?"-":$total_posting ?></td>
					</tr>
					<?php
					
				echo"
				</table>";
	?>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td height="50" colspan="9" align="right" valign="baseline"><input type="button" value="KEMBALI" style="width:120px; height: 30px" onClick="location.href='ExportToSAPNAB.php'" /></td>
		</tr>
		<tr>
			<th align="center"><?php include("../include/Footer.php") ?></th>
		</tr>
	</table>
		<?php
	
	
	oci_close($con);
		
}
else{
echo "<br>" ."krani blm login";
} 