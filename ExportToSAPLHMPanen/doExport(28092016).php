<?php
	session_start();
if(isset($_POST['btn_export']) and isset($_SESSION['sql_export_crop_harv']) and isset($_SESSION['sql_Download_Denda_Panen'])){
	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();
	
	$result = oci_parse($con, $_SESSION['sql_export_crop_harv']);
	oci_execute($result, OCI_DEFAULT);
	
	
	$success_count = 0;
	$fail_count = 0;
	$record_count = 0;
	$export_count = 0;
	$posting_count = 0;
	
	while (oci_fetch($result)){
		$record_count++;
		$ID_CC = oci_result($result, "ID_CC");
		$PROFILE_NAME = oci_result($result, "PROFILE_NAME");
		$NIK_PEMANEN[] = oci_result($result, "NIK_PEMANEN");
		$NAMA_PEMANEN[] = oci_result($result, "NAMA_PEMANEN");
		$NO_BCC[] = oci_result($result, "NO_BCC");
		$NO_TPH[] = oci_result($result, "NO_TPH");
		$ID_BLOK[] = oci_result($result, "ID_BLOK");
		
		
		//check ke table t_status_to_sap_ebcc
		$query_cek_export = "
		SELECT * FROM T_STATUS_TO_SAP_EBCC WHERE COMP_CODE = '".oci_result($result, "ID_CC")."' AND PROFILE_NAME = '".oci_result($result, "PROFILE_NAME")."' AND NO_BCC = '".oci_result($result, "NO_BCC")."'
		";
		
		$result_export_cek = oci_parse($con, $query_cek_export);
		oci_execute($result_export_cek, OCI_DEFAULT);
		$result_export_count = oci_fetch_all($result_export_cek, $result_export_record, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
		
		
		//echo $query_cek_export; exit;
		if($result_export_count==1){
			//for($x=0;$x<$result_export_count;$x++){
				if($result_export_record[0]['POST_STATUS']!="" and $result_export_record[0]['POST_TIMESTAMP']!=""){
					//$STATUS[] = 'POSTING';
					$posting_count++;
				} else if($result_export_record[0]['EXPORT_STATUS']!="" and $result_export_record[0]['EXPORT_TIMESTAMP']!=""){
					//$STATUS[] = 'EXPORT';
					$export_count++;
				} else if($result_export_record[0]['EXPORT_STATUS']=="" and $result_export_record[0]['EXPORT_TIMESTAMP']==""){
					
					//cek_hektar
					
					$qhectare = "
					SELECT * FROM T_STATUS_TO_SAP_EBCC WHERE 
					COMP_CODE = '".oci_result($result, "ID_CC")."' AND 
					PROFILE_NAME = '".oci_result($result, "PROFILE_NAME")."' AND 
					NIK_PEMANEN = '".oci_result($result, "NIK_PEMANEN")."' AND
					TANGGAL = '".oci_result($result, "TANGGAL")."' AND
					AFDELING = '".oci_result($result, "AFDELING")."' AND
					BLOCK = '".oci_result($result, "BLOCK")."'
					";
					
					$rhectare = oci_parse($con, $qhectare);
					oci_execute($rhectare, OCI_DEFAULT);
					$res_hectare = oci_fetch_all($rhectare, $rec_hectare, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
					if($res_hectare>0){
						$luasan_panen = 0;
						/* $no_rekap_bcc = oci_result($result, "NO_REKAP_BCC");
						$id_rencana = oci_result($result, "ID_RENCANA"); */
						$tglPanen = oci_result($result, "TANGGAL");
						$IDAfd = oci_result($result, "AFD_KERJA");
						$val_blok = oci_result($result, "ID_BLOK");
						$val_pemanen = oci_result($result, "NIK_PEMANEN");
						$NIKMandor = oci_result($result, "NIK_MANDOR");
					} else {
						if($record_count==0){
							/* $no_rekap_bcc = oci_result($result, "NO_REKAP_BCC");
							$id_rencana = oci_result($result, "ID_RENCANA"); */
							$tglPanen = oci_result($result, "TANGGAL");
							$IDAfd = oci_result($result, "AFD_KERJA");
							$val_blok = oci_result($result, "ID_BLOK");
							$val_pemanen = oci_result($result, "NIK_PEMANEN");
							$NIKMandor = oci_result($result, "NIK_MANDOR");
						} else {
							
							if(oci_result($result, "TANGGAL")!=$tglPanen || 
							oci_result($result, "AFD_KERJA")!=$IDAfd || 
							oci_result($result, "ID_BLOK")!=$val_blok || 
							oci_result($result, "NIK_PEMANEN")!=$val_pemanen || 
							oci_result($result, "NIK_MANDOR")!=$NIKMandor
							){
							/* if(oci_result($result, "NO_REKAP_BCC")!==$no_rekap_bcc){
								$luasan_panen = oci_result($result, "LUASAN_PANEN");
							} else if(oci_result($result, "ID_RENCANA")!==$id_rencana){ */
								$luasan_panen = oci_result($result, "LUASAN_PANEN");
							} else {
								$luasan_panen = 0;
							}
						}
					}
					
					
					
					$update_data = "
					update T_STATUS_TO_SAP_EBCC set
					NIK_PEMANEN = '".oci_result($result, "NIK_PEMANEN")."', 
					TANGGAL = '".oci_result($result, "TANGGAL")."', 
					CUSTOMER = '".oci_result($result, "CUST")."', 
					PLANT = '".oci_result($result, "BA_KERJA")."', 
					AFDELING = '".oci_result($result, "AFD_KERJA")."',
					BLOCK = '".oci_result($result, "ID_BLOK")."',
					HECTARE = '".$luasan_panen."',
					TBS_BAYAR = '".oci_result($result, "TBS_BAYAR")."', 
					BRONDOLAN = '".oci_result($result, "BRD")."',
					TBS_KIRIM = '".oci_result($result, "DIKIRIM")."', 
					TBS_PANEN = '".oci_result($result, "TBS")."', 
					NIK_MANDOR = '".oci_result($result, "NIK_MANDOR")."', 
					NIK_KRANI_BUAH = '".oci_result($result, "NIK_KERANI_BUAH")."',
					FLAG_GANDENG = '".oci_result($result, "GANDENG")."',
					NIK_GANDENG = '".oci_result($result, "NIK_GANDENG")."',
					EXPORT_STATUS = 'X',
					EXPORT_TIMESTAMP = TO_DATE('".date('m-d-Y H:i:s')."', 'MM-DD-YYYY HH24:MI:SS')
					where COMP_CODE = '".oci_result($result, "ID_CC")."' and PROFILE_NAME = '".oci_result($result, "PROFILE_NAME")."' and NO_BCC = '".oci_result($result, "NO_BCC")."'
					";
					//echo $update_data; exit;
					$num_ebcc = num_rows($con, $update_data);
					
					if($num_ebcc>0){
						$query_denda = $_SESSION['sql_Download_Denda_Panen']." AND HPK.ID_BCC = '".oci_result($result, "NO_BCC")."'";
						
						$sql_denda = oci_parse($con, $query_denda);
						oci_execute($sql_denda, OCI_DEFAULT);
						while (oci_fetch($sql_denda)){
							//check ke table t_status_to_sap_denda_panen
							$query_cek_denda_panen = "
							SELECT * FROM T_STATUS_TO_SAP_DENDA_PANEN WHERE 
							COMP_CODE = '".oci_result($result, "ID_CC")."' AND 
							PROFILE_NAME = '".oci_result($result, "PROFILE_NAME")."' AND 
							NO_BCC = '".oci_result($result, "NO_BCC")."' AND
							KODE_DENDA_PANEN = '".oci_result($sql_denda, "PENALTI")."'
							";
							
							$result_cek_denda_panen = oci_parse($con, $query_cek_denda_panen);
							oci_execute($result_cek_denda_panen, OCI_DEFAULT);
							$result_denda_panen_count = oci_fetch_all($result_cek_denda_panen, $result_denda_panen_record, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
							
							if($result_denda_panen_count>0){
								$update_denda = "
								UPDATE T_STATUS_TO_SAP_DENDA_PANEN SET
								JUMLAH = '".oci_result($sql_denda, "NILAI")."',
								EXPORT_STATUS = 'X',
								EXPORT_TIMESTAMP = TO_DATE('".date('m-d-Y H:i:s')."', 'MM-DD-YYYY HH24:MI:SS')
								WHERE 
								COMP_CODE = '".oci_result($sql_denda, "ID_CC")."' AND
								PROFILE_NAME = '".oci_result($sql_denda, "PROFILE_NAME")."' AND
								NO_BCC = '".oci_result($sql_denda, "NO_BCC")."' AND
								KODE_DENDA_PANEN = '".oci_result($sql_denda, "PENALTI")."'
								";
								$num_denda = num_rows($con, $update_denda);
								//echo $update_denda; exit;
							} else {
								$insert_denda = "
								INSERT INTO T_STATUS_TO_SAP_DENDA_PANEN(
								COMP_CODE,PROFILE_NAME,NO_BCC,KODE_DENDA_PANEN,JUMLAH,EXPORT_STATUS,EXPORT_TIMESTAMP
								) VALUES(
								'".oci_result($sql_denda, "ID_CC")."',
								'".oci_result($sql_denda, "PROFILE_NAME")."',
								'".oci_result($sql_denda, "NO_BCC")."',
								'".oci_result($sql_denda, "PENALTI")."',
								'".oci_result($sql_denda, "NILAI")."',
								'X',
								TO_DATE('".date('m-d-Y H:i:s')."', 'MM-DD-YYYY HH24:MI:SS')
								)
								";
								$num_denda = num_rows($con, $insert_denda);
							}
							
						}
						
						$success_count++;
					} else {
						$fail_count++;
					}
					
					//echo $update_data; exit;
				}
			//}
			
		} else if($result_export_count>1){
			//$STATUS[] = 'Gagal export BCC';
			$fail_count++;
		} else {
			
			$qhectare = "
			SELECT * FROM T_STATUS_TO_SAP_EBCC WHERE 
			COMP_CODE = '".oci_result($result, "ID_CC")."' AND 
			PROFILE_NAME = '".oci_result($result, "PROFILE_NAME")."' AND 
			NIK_PEMANEN = '".oci_result($result, "NIK_PEMANEN")."' AND
			TANGGAL = '".oci_result($result, "TANGGAL")."' AND
			AFDELING = '".oci_result($result, "AFDELING")."' AND
			BLOCK = '".oci_result($result, "BLOCK")."'
			";
			
			$rhectare = oci_parse($con, $qhectare);
			oci_execute($rhectare, OCI_DEFAULT);
			$res_hectare = oci_fetch_all($rhectare, $rec_hectare, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
			if($res_hectare>0){
				$luasan_panen = 0;
				/* $no_rekap_bcc = oci_result($result, "NO_REKAP_BCC");
				$id_rencana = oci_result($result, "ID_RENCANA"); */
				$tglPanen = oci_result($result, "TANGGAL");
				$IDAfd = oci_result($result, "AFD_KERJA");
				$val_blok = oci_result($result, "ID_BLOK");
				$val_pemanen = oci_result($result, "NIK_PEMANEN");
				$NIKMandor = oci_result($result, "NIK_MANDOR");
			} else {
				if($record_count==0){
					/* $no_rekap_bcc = oci_result($result, "NO_REKAP_BCC");
					$id_rencana = oci_result($result, "ID_RENCANA"); */
					$tglPanen = oci_result($result, "TANGGAL");
					$IDAfd = oci_result($result, "AFD_KERJA");
					$val_blok = oci_result($result, "ID_BLOK");
					$val_pemanen = oci_result($result, "NIK_PEMANEN");
					$NIKMandor = oci_result($result, "NIK_MANDOR");
				} else {
					if(oci_result($result, "TANGGAL")!=$tglPanen || 
					oci_result($result, "AFD_KERJA")!=$IDAfd || 
					oci_result($result, "ID_BLOK")!=$val_blok || 
					oci_result($result, "NIK_PEMANEN")!=$val_pemanen || 
					oci_result($result, "NIK_MANDOR")!=$NIKMandor
					){
					/* if(oci_result($result, "NO_REKAP_BCC")!==$no_rekap_bcc){
						$luasan_panen = oci_result($result, "LUASAN_PANEN");
					} else if(oci_result($result, "ID_RENCANA")!==$id_rencana){ */
						$luasan_panen = oci_result($result, "LUASAN_PANEN");
					} else {
						$luasan_panen = 0;
					}
				}
			}
			
			
			$export_data = "
			INSERT INTO T_STATUS_TO_SAP_EBCC(
			COMP_CODE,
			PROFILE_NAME,
			NO_BCC, 
			NIK_PEMANEN, 
			TANGGAL, 
			NO_TPH, 
			CUSTOMER, 
			PLANT, 
			AFDELING,
			BLOCK,
			HECTARE,
			TBS_BAYAR, 
			BRONDOLAN, 
			TBS_KIRIM,
			TBS_PANEN,
			NIK_MANDOR, 
			NIK_KRANI_BUAH,
			FLAG_GANDENG,
			NIK_GANDENG,
			COMP_CODE_KARY,
			PROFILE_NAME_KARY,
			AFDELING_KARY,
			EXPORT_STATUS,
			EXPORT_TIMESTAMP
			) VALUES (
			'".oci_result($result, "ID_CC")."',
			'".oci_result($result, "PROFILE_NAME")."',
			'".oci_result($result, "NO_BCC")."',
			'".oci_result($result, "NIK_PEMANEN")."',
			'".oci_result($result, "TANGGAL")."',
			'".oci_result($result, "NO_TPH")."',
			'".oci_result($result, "CUST")."',
			'".oci_result($result, "BA_KERJA")."',
			'".oci_result($result, "AFD_KERJA")."',
			'".oci_result($result, "ID_BLOK")."',
			'".$luasan_panen."',
			'".oci_result($result, "TBS_BAYAR")."',
			'".oci_result($result, "BRD")."',
			'".oci_result($result, "DIKIRIM")."',
			'".oci_result($result, "TBS")."',
			'".oci_result($result, "NIK_MANDOR")."',
			'".oci_result($result, "NIK_KERANI_BUAH")."',
			'".oci_result($result, "GANDENG")."',
			'".oci_result($result, "NIK_GANDENG")."',
			'".oci_result($result, "COMP_CODE_KARY")."',
			'".oci_result($result, "PROFILE_NAME_KARY")."',
			'".oci_result($result, "AFD_PEMANEN")."',
			'X',
			TO_DATE('".date('m-d-Y H:i:s')."', 'MM-DD-YYYY HH24:MI:SS')
			)
			";
			//echo $export_data; exit;
			$num_export = num_rows($con, $export_data);
			
			if($num_export==1){
				//Insert into denda_panen
				$query_denda = $_SESSION['sql_Download_Denda_Panen']." AND HPK.ID_BCC = '".oci_result($result, "NO_BCC")."'";
				//	 echo $query_denda; exit;
				$sql_denda = oci_parse($con, $query_denda);
				oci_execute($sql_denda, OCI_DEFAULT);
				while (oci_fetch($sql_denda)){
					$insert_denda = "
					INSERT INTO T_STATUS_TO_SAP_DENDA_PANEN(
					COMP_CODE,PROFILE_NAME,NO_BCC,KODE_DENDA_PANEN,JUMLAH,EXPORT_STATUS,EXPORT_TIMESTAMP
					) VALUES(
					'".oci_result($sql_denda, "ID_CC")."',
					'".oci_result($sql_denda, "PROFILE_NAME")."',
					'".oci_result($sql_denda, "NO_BCC")."',
					'".oci_result($sql_denda, "PENALTI")."',
					'".oci_result($sql_denda, "NILAI")."',
					'X',
					TO_DATE('".date('m-d-Y H:i:s')."', 'MM-DD-YYYY HH24:MI:SS')
					)
					";
					$num_denda = num_rows($con, $insert_denda);
				}
				$STATUS[] = 'Success export BCC';
				$success_count++;
			} else {
				$fail_count++;
			}
			
			
		}
		/* $no_rekap_bcc = oci_result($result, "NO_REKAP_BCC");
		$id_rencana = oci_result($result, "ID_RENCANA"); */
		$tglPanen = oci_result($result, "TANGGAL");
		$IDAfd = oci_result($result, "AFD_KERJA");
		$val_blok = oci_result($result, "ID_BLOK");
		$val_pemanen = oci_result($result, "NIK_PEMANEN");
		$NIKMandor = oci_result($result, "NIK_MANDOR");
	}
	
	//echo $record_count."==".$success_count; exit;
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
						<td height="50" colspan="4" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>Export To SAP - LHM Panen</strong></span></td>
						
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
						<td width="96"><span >Tanggal Panen</span></td>
						<td width="10">:</td>
						<td width="277">
							<input type="text" value="<?=$_SESSION['date1']?>" style="background-color:#CCC; color:black; width: 300px; height:25px; font-size:15px" onmousedown="return false" disabled />
						</td>
					</tr>
					<tr>
						<td>Business Area</td>
						<td>:</td>
						<td>
							<input type="text" value="<?=$_SESSION['subID_BA_Afd']?>" style="background-color:#CCC; color:black; width: 300px; height:25px; font-size:15px" onmousedown="return false" disabled />
						</td>
						<td>&nbsp;</td>
						
						<td width="96"><span >Mandor</span></td>
						<td width="10">:</td>
						<td width="277">
							<input type="text" value="<?=$_SESSION['Emp_NameMandor']?>" style="background-color:#CCC; color:black; width: 300px; height:25px; font-size:15px" onmousedown="return false" disabled />
						</td>
						
					</tr>
					<tr>
						<td> Afdeling</td>
						<td>:</td>
						<td style="font-size:16px">
							<input type="text" value="<?=$_SESSION['Afdeling']?>" style="background-color:#CCC; color:black; width: 300px; height:25px; font-size:15px" onmousedown="return false" disabled />
						</td>
						<td>&nbsp;</td>
						<td width="96">&nbsp;</td>
						<td width="10">&nbsp;</td>
						<td width="277">&nbsp;</td>
					</tr>
					
				</table>
			</th>
		</tr>
		<tr>
			<td colspan="4" valign="top">
				<table width="1134" border="1">
					<tr bgcolor="#9CC346">
						<td width="250" rowspan="2" align="center" style="font-size:14px" id="bordertable">Jumlah BCC yang di Export</td>
						<td width="250" colspan="3" align="center" style="font-size:14px" id="bordertable">Status Export</td>
						
					</tr>
					<tr bgcolor="#9CC346"> 
						<td align="center" style="font-size:14px" id="bordertable">Gagal Export</td>
						<td align="center" style="font-size:14px" id="bordertable">Sudah Pernah Export</td>
						<td align="center" style="font-size:14px" id="bordertable">Sudah Posting</td>
					</tr>
					<tr>
						<td align="center" id="bordertable"><?= $record_count ?></td>
						<td align="center" id="bordertable"><?= ($fail_count==0)?"-":$fail_count ?></td>
						<td align="center" id="bordertable"><?= ($export_count==0)?"-":$export_count ?></td>
						<td align="center" id="bordertable"><?= ($posting_count==0)?"-":$posting_count ?></td>
					</tr>
					<?php
					
				echo"
				</table>";
	?>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td height="50" colspan="9" align="right" valign="baseline"><input type="button" value="KEMBALI" style="width:120px; height: 30px" onClick="location.href='ExportToSAPLHMPanen.php'" /></td>
		</tr>
		<tr>
			<th align="center"><?php include("../include/Footer.php") ?></th>
		</tr>
	</table>
		<?php
	
	
	oci_close($con);
} else {
	$_SESSION[err] = "Please choose the options";
	header("Location:ExportToSAPLHMPanen.php");
}
?>