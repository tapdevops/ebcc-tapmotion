<?php
	
session_start();

include("../include/Header.php");
if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['Jenis_Login']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name']) && isset($_SESSION['subID_CC'])){	
	$Job_Code = $_SESSION['Job_Code'];
	$username = $_SESSION['NIK'];	
	$Emp_Name = $_SESSION['Name'];
	$Jenis_Login = $_SESSION['Jenis_Login'];
	$Comp_Name = $_SESSION['Comp_Name'];
	$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
	$Date = $_SESSION['Date'];
	$ID_Group_BA = $_SESSION['ID_Group_BA'];
	$subID_CC = $_SESSION['subID_CC'];
	
	if($username == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:../index.php");
	} else {
		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		
		$LaporanAAP = "";
		if(isset($_POST["LaporanAAP"])){
			$LaporanAAP = $_POST["LaporanAAP"];
			$_SESSION["LaporanAAP"] = $LaporanAAP;
		}
		if(isset($_SESSION["LaporanAAP"])){
			$LaporanAAP = $_SESSION["LaporanAAP"];
		}
		
		if($LaporanAAP == TRUE){
			$sAFD = "";
			$optionGETBLOK = "";	
			if(isset($_SESSION["BA"]) == ""){
				$_SESSION["BA"] = $subID_BA_Afd;
			}
			
			if(isset($_SESSION['BA'])){
				$sID_BA = $_SESSION["BA"];
				$sql_t_AFD  = "select * from t_Afdeling tafd inner join t_bussinessarea tba on tafd.id_ba = tba.id_ba WHERE tba.id_ba = '$sID_BA'";
			}
			
			$result_t_sAFD = oci_parse($con, $sql_t_AFD);
			oci_execute($result_t_sAFD, OCI_DEFAULT);
			while (oci_fetch($result_t_sAFD)) {	
				$ID_AFD[] 		= oci_result($result_t_sAFD, "ID_AFD");
			}
			$roweffec_AFD = oci_num_rows($result_t_sAFD);
			
			//AFD
			if(isset($_POST['AFD'])){
				$_SESSION["AFD"] = $_POST['AFD'];
				unset($_SESSION['BLOK']);
				unset($_SESSION['NIK_Pemanen']);
			}
			
			if(isset($_SESSION['AFD'])){
				$sAFD = $_SESSION["AFD"];
				$optionGETAFD = "<option value=\"$sAFD\" selected=\"selected\">$sAFD</option>";	
				$sql_t_BLOK  = "select ID_BLOK from t_blok tb 
				inner join t_afdeling ta on tb.id_ba_afd = ta.id_ba_afd 
				WHERE ta.id_afd = nvl (decode ('$sAFD', 'ALL', null, '$sAFD'), ta.id_afd)  and id_ba = '$sID_BA' order by tb.id_blok";
				$result_t_sBLOK = oci_parse($con, $sql_t_BLOK);
				oci_execute($result_t_sBLOK, OCI_DEFAULT);
				while (oci_fetch($result_t_sBLOK)) {	
					$ID_BLOK[] 		= oci_result($result_t_sBLOK, "ID_BLOK");
				}
				$roweffec_BLOK = oci_num_rows($result_t_sBLOK);	
				
			}
			
			//BLOK
			if(isset($_POST['BLOK'])){
				$_SESSION["BLOK"] = $_POST['BLOK'];
				unset($_SESSION['NIK_Pemanen']);
			}
			
			if(isset($_SESSION['BLOK'])){
				$sID_BLOK = $_SESSION['BLOK'];
				$optionGETBLOK = "<option value=\"$sID_BLOK\" selected=\"selected\">$sID_BLOK</option>";		
			}
			
			$sdate1 = "";
			$sdate2 = "";
			
			if(isset($_POST["date1"])){
				$_SESSION['date1'] = date("Y-m-d", strtotime($_POST["date1"]));
				unset($_SESSION['date2']);
				$sdate2 = "";
			}
	
			if(isset($_POST["date2"])){
				$_SESSION['date2'] = date("Y-m-d", strtotime($_POST["date2"]));
			}
	
			if(isset($_SESSION['date1'])){
				$sdate1 = $_SESSION['date1'];
			}
			
			if(isset($_SESSION['date2'])){
				$sdate2 = $_SESSION['date2'];
			}
			
			
			$sql_t_Pemanen = "
								select NIK_Pemanen, f_get_empname(NIK_Pemanen) Nama_Pemanen, NIK_Mandor, f_get_empname(NIK_Mandor) Nama_Mandor
								from t_header_rencana_panen thrp LEFT JOIN t_detail_rencana_panen tdrp
									ON thrp.id_rencana = tdrp.id_rencana
								 LEFT JOIN t_blok tb
									ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
								 LEFT JOIN t_afdeling ta
									ON tb.id_ba_afd = ta.id_ba_afd
								 LEFT JOIN t_detail_gandeng tdg
									ON tdrp.id_rencana = tdg.id_rencana
								 LEFT JOIN t_hasil_panen thp
									ON tdrp.id_rencana = thp.id_rencana
									   AND tdrp.no_rekap_bcc = thp.no_rekap_bcc
								where
								tdrp.luasan_panen >= 0 and
								ta.ID_BA = '$sID_BA' 
								and ta.id_afd = nvl (decode ('$sAFD', 'ALL', null, '$sAFD'), ta.id_afd) 
								and tb.id_blok = nvl (decode ('$sID_BLOK', 'ALL', null, '$sID_BLOK'), tb.id_blok)
								and to_char(thrp.tanggal_rencana,'YYYY-MM-DD') between '$sdate1' and nvl ('$sdate2', '$sdate1') 
								group by NIK_PEMANEN, NIK_Mandor
								order by Nama_Pemanen 
							";
				//echo $sql_t_Pemanen; exit;			
				$result_t_Pemanen = oci_parse($con, $sql_t_Pemanen);
				oci_execute($result_t_Pemanen, OCI_DEFAULT);
				while (oci_fetch($result_t_Pemanen)) {	
					$Nama_Pemanen[] = oci_result($result_t_Pemanen, "NAMA_PEMANEN");
					$NIK_Pemanen[] 	= oci_result($result_t_Pemanen, "NIK_PEMANEN");
					$Nama_Mandor[] 	= oci_result($result_t_Pemanen, "NAMA_MANDOR");
				}
				
				$roweffec_Pemanen = oci_num_rows($result_t_Pemanen);			
				//echo $roweffec_Pemanen; exit;
				if(isset($_POST['NIK_Pemanen'])){
					$_SESSION["NIK_Pemanen"] = $_POST['NIK_Pemanen'];
				}
				if(isset($_SESSION['NIK_Pemanen'])){
					$sNIK_Pemanen = $_SESSION["NIK_Pemanen"];
					$session_sql_t_Pemanen = "select NIK_Pemanen, f_get_empname(NIK_Pemanen) Nama_Pemanen, NIK_Mandor, f_get_empname(NIK_Mandor) Nama_Mandor from t_header_rencana_panen where NIK_Pemanen = '$sNIK_Pemanen'";
					$session_result_t_Pemanen = oci_parse($con, $session_sql_t_Pemanen);
					oci_execute($session_result_t_Pemanen, OCI_DEFAULT);
					while (oci_fetch($session_result_t_Pemanen)) {	
						$sesNama_Pemanen = oci_result($session_result_t_Pemanen, "NAMA_PEMANEN");
						$sesNIK_Pemanen 	= oci_result($session_result_t_Pemanen, "NIK_PEMANEN");
						$sesNama_Mandor 	= oci_result($session_result_t_Pemanen, "NAMA_MANDOR");
					}
					
					$optionGETPemanen = "<option value=\"$sesNIK_Pemanen\" selected=\"selected\">$sesNama_Pemanen | $sesNIK_Pemanen | $sesNama_Mandor</option>";
					if($sNIK_Pemanen == "ALL"){
						$optionGETPemanen = "";
					}
				}
			
		} else {
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
<script type="text/javascript">
$(function() {
	$('#datepicker2').datepicker({
		changeMonth: true,
		changeYear: true,	  
		maxDate: "+0D" 
	});
	
	$('#datepicker').change(function(){
		$('#datepicker2').val('');
	});
});

function formSubmit(x)
{
	if(x == 1){
	document.getElementById("formJC").submit();
    }
	
	if(x == 2){
	document.getElementById("formtampilkan").submit();
    }
}
</script>

<?php
} else {
	$_SESSION[err] = "tolong login dulu!";
	header("location:../index.php");
}
?>

<table width="1064" height="390" border="0" align="center">
	<tr>
		<th height="197" scope="row" align="center">
			<table width="937" border="0" id="setbody2">
				<tr>
					<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>LAPORAN AKTIVITAS AKHIR PANEN</strong></span></td>
				</tr>
			    <tr>
					<td  colspan="3" valign="bottom" style="border-bottom:solid #000">LOKASI</td>
					<td  colspan="6" valign="bottom" style="border-bottom:solid #000">PERIODE</td>
				</tr>
				
				
				<tr>
					<td width="161" height="13" valign="top">Company Code</td>
					<td width="6" height="13" valign="top">:</td>
					<td width="506" height="13" valign="top"><input name="CClabel" type="text" id="CClabel" value="<?=$subID_CC?>" style="background-color:#CCC; width: 50px; height:25px; font-size:15px; display:inline" onmousedown="return false"/></td>
					<td width="106" valign="top" >Start Date</td>
					<td width="10" valign="top" >:</td>
					<td height="13" colspan="4" valign="top">
						<form id="form1" name="form1" method="post" action="LaporanAAP.php">
						<input type="text" name="date1" id="datepicker" class="box_field" onchange="this.form.submit();" value="<?=$sdate1?>" <?php if(isset($_POST["date1"])){ echo "value='$_POST[date1]'"; }?>>
						</form>
					</td>
				</tr>
				<tr>
					<td width="161" height="13" valign="top">Company Name</td>
					<td width="6" height="13" valign="top">:</td>
					<td width="506" height="13" valign="top"><input name="CClabel" type="text" id="CClabel" value="<?=$Comp_Name?>" style="background-color:#CCC; width: 400px; height:25px; font-size:15px; display:inline" onmousedown="return false"/></td>
					<td width="106" valign="top">End Date</td>
					<td width="10" valign="top">:</td>
					<td height="6" colspan="4" valign="top" >
					<form id="form1" name="form1" method="post" action="LaporanAAP.php">
					<input type="text" name="date2" id="datepicker2" class="box_field" onchange="this.form.submit();" value="<?=$sdate2?>" <?php if(isset($_POST["date2"])){ echo "value='$_POST[date2]'"; }?>>
					</form>
					</td>
				</tr>
				<tr>
					<td height="6" valign="top" >Business Area</td>
					<td width="6" height="6" valign="top" >:</td>
					<td width="506" height="6" valign="top" ><input name="CClabel2" type="text" id="CClabel2" value="<?=$subID_BA_Afd?>" style="background-color:#CCC; width: 50px; height:25px; font-size:15px; display:inline" onmousedown="return false"/></td>
					<td valign="top" >&nbsp;</td>
				    <td valign="top" >&nbsp;</td>
				    <td colspan="4" >&nbsp;</td>
				</tr>	
				<tr>
				    <td valign="top" >Afdeling</td>
				    <td valign="top" >:</td>
				    <td ><form id="form2" name="form1" method="post" action="LaporanAAP.php">
					<?php    
					if($roweffec_AFD > 0 ){

						$selectoAFD = "<select name=\"AFD\" id=\"AFD\" onchange=\"this.form.submit();\" style=\"width: 60px; display:inline \">";
						//$optionAFD= "<option value=\"ALL\"> ALL </option>";
						echo $selectoAFD.$optionGETAFD.$optionAFD;
						echo "<option value=\"ALL\">ALL</option>";
						for($xAFD = 0; $xAFD <  $roweffec_AFD; $xAFD++){
							echo "<option value=\"$ID_AFD[$xAFD]\">$ID_AFD[$xAFD]</option>";
						}
						$selectcAFD = "</select>";
						echo $selectcAFD;
					}
					else{
						$selectoAFD = "<select name=\"AFD\" onchange=\"this.form.submit();\" id=\"AFD\" style=\"width: 60px; display:inline \">";
						//$optionAFD= "<option value=\"ALL\"> ALL </option>";
						echo $selectoAFD.$optionAFD;
						
						$selectcAFD = "</select>";
						echo $selectcAFD;
					}
					?>
					</form>
					</td>
				    <td valign="top" >&nbsp;</td>
				    <td valign="top" >&nbsp;</td>
				    <td colspan="4" >&nbsp;</td>
				</tr>
				<tr>
				    <td valign="top" >Blok</td>
				    <td valign="top" >:</td>
				    <td >
						<form id="form4" name="form1" method="post" action="LaporanAAP.php">
						<?php    

						if($roweffec_BLOK > 0 ){
							$selectoBLOK = "<select name=\"BLOK\" id=\"BLOK\" onchange=\"this.form.submit();\" style=\"width: 60px; display:inline \">";
							$optionBLOK = "<option value=\"\"> - </option>";
							echo $selectoBLOK.$optionGETBLOK.$optionBLOK;
							
							for($xBLOK = 0; $xBLOK <  $roweffec_BLOK; $xBLOK++){
								echo "<option value=\"$ID_BLOK[$xBLOK]\">$ID_BLOK[$xBLOK]</option>";
							}
							
							$selectcBLOK = "</select>";
							echo $selectcBLOK;
						} else {
							$selectoBLOK = "<select name=\"BLOK\" id=\"BLOK\" onchange=\"this.form.submit();\" style=\"width: 60px; display:inline \">";
							$optionBLOK = "<option value=\"-\"> - </option>";
							echo $selectoBLOK.$optionBLOK;
							
							$selectcBLOK = "</select>";
							echo $selectcBLOK;
						}


						?>
						</form>
					</td>
				    <td valign="top" >&nbsp;</td>
				    <td valign="top" >&nbsp;</td>
				    <td colspan="4" >&nbsp;</td>
				</tr>
			    <tr>
					<td height="30" colspan="9" valign="bottom" style=" border-bottom:solid #000">Tampilkan Data</td>
			    </tr>
				<tr>
					<td valign="top">Pemanen</td>
					<td valign="top" >:</td>
					<td valign="top"><form id="formtampilkan" name="formtampilkan" method="post" action="doFilter.php">
					  
					  <?php    

						if($roweffec_Pemanen > 0 ){

						//	echo "11";	
							$selectoPM = "<select name=\"NIK_Pemanen\" id=\"NIK_Pemanen\" style=\"width: 400px; display:$disCC \">";
							$optionPM = "<option value=\"ALL\"> ALL </option>";
							echo $selectoPM.$optionGETPemanen.$optionPM;
							
							for($xPM = 0; $xPM <  $roweffec_Pemanen; $xPM++){
								
								echo "<option value=\"$NIK_Pemanen[$xPM]\">$Nama_Pemanen[$xPM] | $NIK_Pemanen[$xPM] | $Nama_Mandor[$xPM] </option>";
							}
							
							$selectcPM = "</select>";
							echo $selectcPM;
						}

						?>
					  </form>
					</td>
					<td align="right" colspan="6" >
						<input type="submit" name="button2" id="button2" value="TAMPILKAN" style="width:120px; height: 30px" onclick="formSubmit(2)"/>
					</td>
				  </tr>
				
				
				
      <tr>
        <td colspan="9">
        
<?php

if(isset($_SESSION['err'])){
	$err = $_SESSION['err'];
	if($err!=NULL)
	{
		echo $err;
		unset($_SESSION['err']);
	}
}

?>
        </td>
      </tr>
    </table></th>
  </tr>
  <tr>
    <th align="center"><?php include("../include/Footer.php") ?></th>
  </tr>
</table>
