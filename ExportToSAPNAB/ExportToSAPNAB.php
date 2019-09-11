<?php
	session_start();
	include("../include/Header.php");
	if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name'])){	
		$Job_Code = $_SESSION['Job_Code'];
		$username = $_SESSION['NIK'];	
		$Emp_Name = $_SESSION['Name'];
		//$Jenis_Login = $_SESSION['Jenis_Login'];
		$Comp_Name = $_SESSION['Comp_Name'];
		$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
		$Date = $_SESSION['Date'];
		
		if($username == ""){
			$_SESSION[err] = "tolong login dulu!";
			header("location:../index.php");
		}
		else{
			include("../config/SQL_function.php");
			include("../config/db_connect.php");
			$con = connect();
			
			$ExportToSAPNAB = "";
			if(isset($_POST["ExportToSAPNAB"])){
				$ExportToSAPNAB = $_POST["ExportToSAPNAB"];
				$_SESSION["ExportToSAPNAB"] = $ExportToSAPNAB;
			}
			if(isset($_SESSION["ExportToSAPNAB"])){
				$ExportToSAPNAB = $_SESSION["ExportToSAPNAB"];
			}
			
			
			if($ExportToSAPNAB == TRUE){
				$sql_afd = "select * from t_afdeling where ID_BA = '$subID_BA_Afd' ORDER BY ID_BA";
				//echo "here".$sql_afd;
				$result_afd = oci_parse($con, $sql_afd);
				oci_execute($result_afd, OCI_DEFAULT);
				while (oci_fetch($result_afd)) {	
					$ID_BA_Afd[] 		= oci_result($result_afd, "ID_BA_AFD");
					$ID_Afd[] 		= oci_result($result_afd, "ID_AFD");
				}
				$jumlahAfd = oci_num_rows($result_afd);
				
				//echo "here".$sql_afd."jumlah".$jumlahAfd;
			}
			else{
				
				header("location:../menu/authoritysecure.php");
			}
			
		}
		
	?>
	
	<script type="text/javascript" src="../datepicker/js/jquery.min.js"></script>
	<script type="text/javascript" src="../datepicker/js/pa.js"></script>
	<script type="text/javascript" src="../datepicker/datepicker/ui.core.js"></script>
	<script type="text/javascript" src="../datepicker/datepicker/ui.datepicker.js"></script>
	<link type="text/css" href="../datepicker/datepicker/ui.core.css" rel="stylesheet" />
	<link type="text/css" href="../datepicker/datepicker/ui.resizable.css" rel="stylesheet" />
	<link type="text/css" href="../datepicker/datepicker/ui.accordion.css" rel="stylesheet" />
	<link type="text/css" href="../datepicker/datepicker/ui.dialog.css" rel="stylesheet" />
	<link type="text/css" href="../datepicker/datepicker/ui.slider.css" rel="stylesheet" />
	<link type="text/css" href="../datepicker/datepicker/ui.tabs.css" rel="stylesheet" />
	<link type="text/css" href="../datepicker/datepicker/ui.datepicker.css" rel="stylesheet" />
	<link type="text/css" href="../datepicker/datepicker/ui.progressbar.css" rel="stylesheet" />
	<link type="text/css" href="../datepicker/datepicker/ui.theme.css" rel="stylesheet" />
	<link type="text/css" href="../datepicker/datepicker/demos.css" rel="stylesheet" />
	
	<script type="text/javascript">
		$(function() {
			$('#datepicker2').datepicker({
				changeMonth: true,
				changeYear: true
			});
		});
		
		function checkOne(s){
			
			if(document.getElementById("chkall").checked == true){
				for(var y = 0 ; y < s ; y++){
					var z = "chk"+y;
					document.getElementById(z).checked = true;
				}
				
			}
			else{
				for(var y = 0 ; y < s ; y++){
					var z = "chk"+y;
					document.getElementById(z).checked = false;
				}
			}
			
		}
		
		
		function change(x)
		{
			if(x == 1){
				document.getElementById("Afdeling").style.visibility="visible";
				document.getElementById("NIKMandor").style.visibility="hidden";
				//document.getElementById("NIKPemanen").style.visibility="hidden";
				document.getElementById("NIKMandor").value="kosong";
				//document.getElementById("NIKPemanen").value="kosong";
				document.getElementById("button").style.visibility="visible";
				
			}
			if(x == 2){
				document.getElementById("Afdeling").style.visibility="hidden";
				document.getElementById("NIKMandor").style.visibility="visible";
				//document.getElementById("NIKPemanen").style.visibility="hidden";
				document.getElementById("Afdeling").value="kosong";
				//document.getElementById("NIKPemanen").value="kosong";
				document.getElementById("button").style.visibility="visible";
			}
			
		}
		
		function coba(x)
		{
			if(x == 1){
				document.getElementById("Tanggal").style.visibility="visible";
				document.getElementById("Periode").style.visibility="hidden";
				document.getElementById("Periode").value="kosong";
			}
			
			if(x == 2){
				document.getElementById("Tanggal").style.visibility="hidden";
				document.getElementById("Periode").style.visibility="visible";
				document.getElementById("Tanggal").value="kosong";
			}
		}
		
		function formSubmit(x)
		{
			if(x == 1){
				document.getElementById("doFilter").submit();
			}
		}
		
		
	</script>
	
	<?php
	}
	else{
		$_SESSION[err] = "tolong login dulu!".$Job_Code."<br>".$username."<br>".$Emp_Name."<br>".$subID_BA_Afd;
		header("location:../index.php");
	}
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
<table width="1151" height="390" border="0" align="center">
	<!--<tr bgcolor="#C4D59E">-->
	<tr>
		<th height="197" scope="row" align="center"><table width="937" border="0" id="setbody2">
			<tr>
				<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>EXPORT TO SAP - NAB</strong></span></td>
			</tr>
			<tr>
				<td height="45" colspan="3" valign="bottom" style="border-bottom:solid #000">LOKASI</td>
				<td height="45" colspan="3" valign="bottom" style="border-bottom:solid #000">PERIODE</td>
			</tr>
			
			<form id="doFilter" name="doFilter" method="post" action="doFilter.php">
				<tr>
					<td width="70" height="29" valign="top">Company Name</td>
					<td width="10" height="29" valign="top" >:</td>
					<td width="100" align="left" valign="top"><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$Comp_Name?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
					
					<td width="70" height="29" valign="top" >Start Date</td>
					<td width="10" height="29" valign="top" >:</td>
					<td width="100" valign="top"><input type="text" name="date1" id="datepicker" class="box_field" <?php if(isset($_SESSION["date1"])){ echo "value='$_SESSION[date1]'"; }?>></td>
				</tr>
				<tr>
					<td width="70" height="29" valign="top">Business Area</td>
					<td width="10" height="29" valign="top">:</td>
					<td width="100" align="left" valign="top"><input name="ID_BA2" type="text" id="ID_BA2" value="<?=$subID_BA_Afd?>" style="background-color:#CCC; width:70px; height:25px; font-size:15px" onmousedown="return false"/></td>
					
					<td width="70" height="29" valign="top" >End Date</td>
					<td width="10" height="29" valign="top">:</td>
					<td width="100" valign="top" ><input type="text" name="date2" id="datepicker2" class="box_field" <?php if(isset($_SESSION["date2"])){ echo "value='$_SESSION[date2]'"; }?>></td>			  
				</tr>
				<tr>
					<td width="70" height="29" valign="top">Afdeling</td>
					<td width="10" height="29" valign="top" >:</td>
					<td width="100" align="left" valign="top">
						<?php
							if($jumlahAfd > 0 ){
								//$jumlahRecord = $_SESSION['jumlahAfd'];
								$selectoAfd = "<select name=\"Afdeling\" id=\"Afdeling\" style=\"visibility:visible; font-size: 15px; height: 25px \">";
								$optiondefAfd = "<option value=\"ALL\"> ALL </option>";
								echo $selectoAfd.$optiondefAfd;
								for($xAfd = 0; $xAfd < $jumlahAfd; $xAfd++){
									if($_SESSION['valueAfdeling']==$ID_Afd[$xAfd]){
										echo "<option selected value=\"$ID_Afd[$xAfd]\">$ID_Afd[$xAfd]</option>"; 
										} else {
										echo "<option value=\"$ID_Afd[$xAfd]\">$ID_Afd[$xAfd]</option>"; 
									}
								}
								$selectcAfd = "</select>";
								echo $selectcAfd;
							}           
						?>
					</td>
				</tr>
				<tr>
					<input name="valueAfd" type="text" id="valueAfd" value="<?=$sesAfdeling?>" onmousedown="return false" style="display:none"/>
				</tr>
				<tr>
					<td height="30" colspan="6" valign="bottom" style="border-bottom:solid #000">Tampilkan Data</td>
				</tr>
				<tr>
					<td align="right" colspan="6" ><input type="submit" name="button" id="button" value="TAMPILKAN" style="width:120px; height: 30px"/></td>
				</tr>
			</table> 
		</form>
		
		
		</th>
	</tr>
	
	<?php if(isset($_SESSION['sql_Download_NAB']) and $_SESSION['tampilkan']==1){
		$sql = $_SESSION['sql_Download_NAB'];
		//echo $_SESSION['sql_Download_NABtxt'];
		
		$result = oci_parse($con, $sql);
		oci_execute($result, OCI_DEFAULT);
		$p = 0;
		while (oci_fetch($result)) {		
			$ID_CC[] 	= oci_result($result, "ID_CC");
			$ID_BA[] 	= oci_result($result, "ID_BA");
			$ID_AFD[] 	= oci_result($result, "ID_AFD");
			$ID_ESTATE[]= oci_result($result, "ID_ESTATE");
			$NO_NAB[] 	= oci_result($result, "NO_NAB");
			$ID_NAB_TGL[] 	= oci_result($result, "ID_NAB_TGL");
			$DATE[] 	= oci_result($result, "TGL_NAB");
			$NO_POLISI[]= oci_result($result, "NO_POLISI");
			//$PROFILE_NAME[]= oci_result($result, "PROFILE_NAME");
			//$NO_BCC[]= oci_result($result, "NO_BCC");
			
			$get_per_bcc = "SELECT DISTINCT tc.id_cc,
         tba.id_ba,
		 tba.PROFILE_NAME,
		 tba.id_estate,
         TO_CHAR (tn.tgl_nab, 'DD.MM.YYYY') TGL_NAB,
         tn.no_nab,
		 tn.id_nab_tgl,
		 thp.NO_BCC,
         tn.no_polisi
    FROM t_header_rencana_panen thrp
         INNER JOIN t_detail_rencana_panen tdrp
            ON thrp.id_rencana = tdrp.id_rencana
         INNER JOIN t_hasil_panen thp
            ON tdrp.no_rekap_bcc = thp.no_rekap_bcc
			  AND 
           TDRP.ID_RENCANA = thp.ID_RENCANA
         INNER JOIN t_nab tn
            ON thp.id_nab_tgl = tn.id_nab_tgl
         INNER JOIN t_blok tb
            ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
         INNER JOIN t_afdeling ta
            ON tb.id_ba_afd = ta.id_ba_afd
         INNER JOIN t_bussinessarea tba
            ON tba.id_ba = ta.id_ba
         INNER JOIN t_companycode tc
            ON tba.id_cc = tc.id_cc where tc.id_cc = '".oci_result($result, "ID_CC")."' and tba.id_ba = '".oci_result($result, "ID_BA")."'
       and ta.id_afd = nvl (decode ('ALL', 'ALL', null, 
'ALL'), ta.id_afd)
       and tn.status_download = decode ('ALL', 'ALL', 
status_download, 'ALL')
       and to_char (tn.tgl_nab, 'yyyy-mm-dd') between 
'".$_SESSION['date1']."' and  nvl ('".$_SESSION['date2']."','".$_SESSION['date1']."')
		and thp.status_bcc = 'DELIVERED'
		and no_nab = '".oci_result($result, "NO_NAB")."'
		and tn.ID_NAB_TGL = '".oci_result($result, "ID_NAB_TGL")."'
		and TO_CHAR (tn.tgl_nab, 'DD.MM.YYYY') = '".oci_result($result, "TGL_NAB")."'
		";
			$count_rec_bcc = 0;
			$total_success = 0;
			$res_per_bcc = oci_parse($con, $get_per_bcc);
			oci_execute($res_per_bcc, OCI_DEFAULT);
			while (oci_fetch($res_per_bcc)) {
				$count_rec_bcc++;
				//check ke table t_status_to_sap_ebcc
				$query_cek_export = "
				SELECT * FROM T_STATUS_TO_SAP_EBCC WHERE COMP_CODE = '".oci_result($res_per_bcc, "ID_CC")."' AND PROFILE_NAME = '".oci_result($res_per_bcc, "PROFILE_NAME")."' AND NO_BCC = '".oci_result($res_per_bcc, "NO_BCC")."'
				";
				//echo $query_cek_export."<br>";
				//echo $get_per_bcc;
				//die();
				
				$result_export_cek = oci_parse($con, $query_cek_export);
				oci_execute($result_export_cek, OCI_DEFAULT);
				$result_export_count = oci_fetch_all($result_export_cek, $result_export_record, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
				if($result_export_count==1){
					
					if($result_export_record[0]['POST_STATUS']!="" and $result_export_record[0]['POST_TIMESTAMP']!=""){
						
						$total_success++;
					} else {
						
						
						//buat testing aja
						//$total_success++;
					}
					
				} else {
					//buat testing aja
					//$total_success++;
				}
			}
			
			//echo $count_rec_bcc."==".$total_success;
			if($count_rec_bcc==$total_success and $total_success>0){
				$checkbox[] = "<input type=\"checkbox\" name=\"chk$p\" id=\"chk$p\" value=\"".oci_result($result, "ID_NAB_TGL")."\">";
				$status[] = "Siap Export";
				$total_all++;
				$p++;
			} else {
				$checkbox[] = "";
				$status[] = "Belum bisa Export";
			}
			
			
		}
		$roweffec = oci_num_rows($result);
		
		/* unset($_SESSION['tampilkan']);
			unset($_SESSION['date1']);
			unset($_SESSION['date2']);
		unset($_SESSION['valueAfdeling']); */
		
	?>
	<tr>
		<td colspan="9" valign="top" align="center"><table width="1134" border="0">
			<form id="form3" name="form3" method="post" action="doExport.php" >
				<?php
					if($roweffec >0){
						
						echo "
						<tbody id=\"scrolling\" style=\"width:1134px\" border=\"1\" bordercolor=\"#9CC346\">
						<tr bgcolor=\"#9CC346\">
						<td width=\"70\" align=\"center\" valign=\"top\" id=\"bordertable\">Pilih <input type=\"checkbox\" name=\"chkall\" id=\"chkall\" onclick=\"checkOne($total_all)\" ></td>
						<td width=\"100\" align=\"center\" valign=\"top\" id=\"bordertable\">Status</td>
						<td width=\"150\" align=\"center\" valign=\"top\" id=\"bordertable\">Company Code</td>
						<td width=\"100\" align=\"center\" valign=\"top\" id=\"bordertable\">ID Estate</td>
						<td width=\"100\" align=\"center\" valign=\"top\" id=\"bordertable\">ID BA</td>
						<td width=\"100\" align=\"center\" valign=\"top\" id=\"bordertable\">ID AFD</td>
						<td width=\"250\" align=\"center\" valign=\"top\" id=\"bordertable\">No NAB</td>
						<td width=\"150\" align=\"center\" valign=\"top\" id=\"bordertable\">Tanggal NAB</td>
						<td width=\"150\" align=\"center\" valign=\"top\" id=\"bordertable\">No Polisi</td>
						<td width=\"100\" align=\"center\" valign=\"top\" id=\"bordertable\">View</td>
						</tr>";
						
						for($x=0 ; $x<$roweffec ; $x++){
							
							
							
							if(($x % 2) == 0){
								$bg = "#F0F3EC";
							}
							else{
								$bg = "#DEE7D2";
							}
							
							echo "
							<tr bgcolor=$bg>
							<td align=\"center\" id=\"bordertable\">$checkbox[$x]</td>
							<td align=\"center\" id=\"bordertable\">$status[$x]</td>
							<td align=\"center\" id=\"bordertable\">$ID_CC[$x]</td>
							<td align=\"center\" id=\"bordertable\">$ID_ESTATE[$x]</td>
							<td align=\"center\" id=\"bordertable\">$ID_BA[$x]</td>
							<td align=\"center\" id=\"bordertable\">$ID_AFD[$x]</td>
							<td align=\"center\" id=\"bordertable\">$NO_NAB[$x]</td>
							<td align=\"center\" id=\"bordertable\">$DATE[$x]</td>
							<td align=\"center\" id=\"bordertable\">$NO_POLISI[$x]</td>
							<td align=\"center\" id=\"bordertable\">
							<a target=new href=\"doView.php?viewNO_NAB=$NO_NAB[$x]&viewAfd=$ID_AFD[$x]\"><input type=\"button\" name=\"button\" id=\"button\" value=\"VIEW\" style=\"width:50px\"/></a>
							</td>
							</tr>";
						}
						
						if($total_all==0){
							$btn_submit = "<input type=\"submit\" name=\"button\" id=\"button\" value=\"EXPORT\" disabled style=\"width:120px; height: 30px\"/>";
						} else {
							$btn_submit = "<input type=\"submit\" name=\"button\" id=\"button\" value=\"EXPORT\" style=\"width:120px; height: 30px\"/>";
						}
						
						echo "
						</tbody>
						<table width=\"1134\" border=\"0\">
						<tr><td>&nbsp;</td></tr>
						<tr>
						<td></td>
						<td height=\"15\" align=\"right\">
						$btn_submit
						</td>
						</tr>
						</table>
						";
					}
				?>
				<input name="roweffec" type="text" id="roweffec" value="<?=$total_all?>" style="display:none" onmousedown="return false"/>
			</form>
			
		</td>
	</tr>
	<?php if($roweffec >0){ unset($_SESSION['tampilkan']); ?>
	<tr>
		<td>* Belum Bisa Export - Terdapat BCC yang belum posting di SAP</td>
	</tr>
	<?php } } ?>
	<tr>
		<th align="center"><?php
			if(isset($_SESSION['err'])){
				$err = $_SESSION['err'];
				if($err!=null)
				{
					echo $err;
					unset($_SESSION['err']);
				}
			}
		?></th>
	</tr>
	
	<tr>
		<th align="center"><?php include("../include/Footer.php") ?></th>
	</tr>
	</table>
