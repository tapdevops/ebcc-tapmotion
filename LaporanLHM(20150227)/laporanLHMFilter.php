<?php
session_start();
include("../include/Header.php");
if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['Jenis_Login']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name'])){	
$Job_Code = $_SESSION['Job_Code'];
$username = $_SESSION['NIK'];	
$Emp_Name = $_SESSION['Name'];
$Jenis_Login = $_SESSION['Jenis_Login'];
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
		
		//$LaporanLHM = "TRUE";
		if(isset($_POST["LaporanLHM"])){
			$LaporanLHM = $_POST["LaporanLHM"];
			$_SESSION["LaporanLHM"] = $LaporanLHM;
		}
		if(isset($_SESSION["LaporanLHM"])){
			$LaporanLHM = $_SESSION["LaporanLHM"];
		}
		
		if($LaporanLHM == TRUE){
		
			$conditionAfd = "";
			$optionGetAfd = "";
			if(isset($_POST["Afdeling"])){
			$_SESSION["Afdeling"] = $_POST["Afdeling"];
			 unset($_SESSION['NIKMandor']);
			}
			
			if(isset($_SESSION["Afdeling"])){
				$sesAfdeling = $_SESSION["Afdeling"];
				
				$Ses_sql_afd = "select * from t_afdeling where ID_BA = '$subID_BA_Afd' and ID_AFD = '$sesAfdeling' ORDER BY ID_BA";
				//echo "here".$sql_afd;
				$Ses_result_afd = oci_parse($con, $Ses_sql_afd);
				oci_execute($Ses_result_afd, OCI_DEFAULT);
				while (oci_fetch($Ses_result_afd)) {	
					$Ses_ID_BA_Afd[] 		= oci_result($Ses_result_afd, "ID_AFD");
					$Ses_ID_Afd[] 		= oci_result($Ses_result_afd, "ID_AFD");
				}
				$optionGetAfd = "<option value=\"$Ses_ID_Afd[0]\" selected=\"selected\">$Ses_ID_Afd[0]</option>";
				$conditionAfd = "and ta.ID_AFD = '$Ses_ID_Afd[0]'";
				$sesSql_Afd = "select * from t_afdeling where ID_AFD != '$sesAfdeling'";
				
				if($sesAfdeling == "ALL"){
					$sql_afd = "select * from t_afdeling where ID_BA = '$subID_BA_Afd' ORDER BY ID_BA";
					$conditionAfd = "";
					$optionGetAfd = "";
				}
				else{
					$sql_afd = "select * from t_afdeling where ID_BA = '$subID_BA_Afd' and ID_AFD != '$sesAfdeling' ORDER BY ID_BA";
				}
			}
			else{
				$sql_afd = "select * from t_afdeling where ID_BA = '$subID_BA_Afd' ORDER BY ID_BA";
			}
			
			//echo "here".$sql_afd;
			$result_afd = oci_parse($con, $sql_afd);
			oci_execute($result_afd, OCI_DEFAULT);
			while (oci_fetch($result_afd)) {	
				$ID_BA_Afd[] 		= oci_result($result_afd, "ID_BA_AFD");
				$ID_Afd[] 		= oci_result($result_afd, "ID_AFD");
			}
			$jumlahAfd = oci_num_rows($result_afd);
			//echo "here".$sql_afd."jumlah".$jumlahAfd;
			
			$conditionMD = "";
			$optionGetMD = "";
			if(isset($_POST["NIKMandor"])){
			$_SESSION["NIKMandor"] = $_POST["NIKMandor"];
			}
			
			if(isset($_SESSION["NIKMandor"])){
				$sesNIKMandor = $_SESSION["NIKMandor"];
				
				$Ses_sql_MD = "select * from t_employee te 
								inner join t_jobauthority tj on te.id_jobauthority = tj.id_jobauthority 
								inner join t_afdeling ta on te.id_ba_afd = ta.id_ba_afd 
								where ta.ID_BA = '$subID_BA_Afd' and tj.authority = '7' and te.NIK = '$sesNIKMandor' $conditionAfd ORDER BY te.EMP_NAME";
				
				$Ses_result_MD = oci_parse($con, $Ses_sql_MD);
				oci_execute($Ses_result_MD, OCI_DEFAULT);
				while (oci_fetch($Ses_result_MD)) {	
					$NIK_Mandor[] 		= oci_result($Ses_result_MD, "NIK");
					$Emp_NameMandor[] 		= oci_result($Ses_result_MD, "EMP_NAME");
				}
				$optionGetMD = "<option value=\"$NIK_Mandor[0]\" selected=\"selected\">$Emp_NameMandor[0]</option>";
				$conditionMD = "and thrp.NIK_Mandor = '$NIK_Mandor[0]'";
				
				if($sesNIKMandor == "ALL"){
					$sql_MD = "select * from t_employee te 
								inner join t_jobauthority tj on te.id_jobauthority = tj.id_jobauthority 
								inner join t_afdeling ta on te.id_ba_afd = ta.id_ba_afd 
								where ta.ID_BA = '$subID_BA_Afd' and tj.authority = '7' $conditionAfd ORDER BY te.EMP_NAME";
					$conditionMD = "";
					$optionGetMD = "";
				}
				else{
					$sql_MD = "select * from t_employee te 
								inner join t_jobauthority tj on te.id_jobauthority = tj.id_jobauthority 
								inner join t_afdeling ta on te.id_ba_afd = ta.id_ba_afd 
								where ta.ID_BA = '$subID_BA_Afd' and tj.authority = '7' and te.NIK != '$sesNIKMandor' $conditionAfd ORDER BY te.EMP_NAME";
				}
			}
			else{
				$sql_MD = "select * from t_employee te 
								inner join t_jobauthority tj on te.id_jobauthority = tj.id_jobauthority 
								inner join t_afdeling ta on te.id_ba_afd = ta.id_ba_afd 
								where ta.ID_BA = '$subID_BA_Afd' and tj.authority = '7' $conditionAfd ORDER BY te.EMP_NAME";
			}
	
			//echo " here".$sql_MD;
			$result_MD = oci_parse($con, $sql_MD);
			oci_execute($result_MD, OCI_DEFAULT);
			while (oci_fetch($result_MD)) {	
				$NIK_Mandor[] 		= oci_result($result_MD, "NIK");
				$Emp_NameMandor[] 		= oci_result($result_MD, "EMP_NAME");
			}
			$jumlahMD = oci_num_rows($result_MD);
			//echo "here".$sql_MD."jumlah".$jumlahMD;
			
			$curNIKPemanen="";
			
			if(isset($_POST["NIKPemanen"])){
			$_SESSION["NIKPemanen"] = $_POST["NIKPemanen"];
			}
			
			if(isset($_SESSION["NIKPemanen"]))
			{
				$curNIKPemanen = $_SESSION["NIKPemanen"];
				if($curNIKPemanen == "ALL")
				{
					$optionGetPMN = "";
				}
				else
				{
					$sql_PMN2 = "SELECT te.nik, te.emp_name
								FROM t_employee te
								where te.nik = '$curNIKPemanen'
								group by te.nik, te.emp_name";
					
					$result_PMN2 = oci_parse($con, $sql_PMN2);
					oci_execute($result_PMN2, OCI_DEFAULT);
					while (oci_fetch($result_PMN2)) {	
						$NIK_Pemanen2[] 		= oci_result($result_PMN2, "NIK");
						$Emp_NamePemanen2[] 	= oci_result($result_PMN2, "EMP_NAME");
					}
					$optionGetPMN = "<option value=\"$curNIKPemanen\" selected=\"selected\">$NIK_Pemanen2[0] - $Emp_NamePemanen2[0]</option>";
				}
			}
			else
			{
				$optionGetPMN = "";
			}
			
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

function getData(val){
	if(val == "blok"){
		document.getElementById('blok_post').value = document.getElementById('blok_rekap').value;
	}else if (val == "pemanen"){
		document.getElementById('nik_pemanen_rekap_post').value = document.getElementById('NIKPemanen_rekap').value;
	}else if (val == "mandor"){
		document.getElementById('nik_mandor_rekap_post').value = document.getElementById('NIKMandor_rekap').value;
	}
}

function showData(value) {

	if(value == "Detail"){
		document.getElementById('detail').style.visibility='visible';
		document.getElementById('detail').style.display='block';
		document.getElementById('rekap_btn').style.visibility='hidden';
		document.getElementById('rekap_btn').style.display='none';
		document.getElementById('rekap').style.visibility='hidden';
		document.getElementById('rekap').style.display='none';
	}else if(value == "Rekap"){
		document.getElementById('detail').style.visibility='hidden';
		document.getElementById('detail').style.display='none';
		document.getElementById('rekap_btn').style.visibility='visible';
		document.getElementById('rekap_btn').style.display='block';
		document.getElementById('rekap').style.visibility='visible';
		document.getElementById('rekap').style.display='block';
	}else if(value == "Pemanen"){
		document.getElementById('tbl_rekap_pemanen').style.visibility='visible';
		document.getElementById('tbl_rekap_pemanen').style.display='block';
		document.getElementById('tbl_rekap_blok').style.visibility='hidden';
		document.getElementById('tbl_rekap_blok').style.display='none';
	}else{
		document.getElementById('tbl_rekap_pemanen').style.visibility='hidden';
		document.getElementById('tbl_rekap_pemanen').style.display='none';
		document.getElementById('tbl_rekap_blok').style.visibility='visible';
		document.getElementById('tbl_rekap_blok').style.display='block';
	}
}

function change(x)
{
	if(x == 1){
	document.getElementById("Afdeling").style.visibility="visible";
	document.getElementById("NIKMandor").style.visibility="hidden";
	document.getElementById("NIKMandor").value="kosong";
	document.getElementById("button").style.visibility="visible";
	
	}
	if(x == 2){
	document.getElementById("Afdeling").style.visibility="hidden";
	document.getElementById("NIKMandor").style.visibility="visible";
	document.getElementById("Afdeling").value="kosong";
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
	$_SESSION[err] = "tolong login dulu!".$Job_Code."<br>".$username."<br>".$Emp_Name."<br>".$Jenis_Login."<br>".$subID_BA_Afd;
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
		<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>LAPORAN LHM</strong></span></td>
      </tr>
	  <tr>
        <td height="45" colspan="3" valign="bottom" style=" border-bottom:solid #000">LOKASI</td>
		<td height="45" colspan="3" valign="bottom" style="border-bottom:solid #000">PERIODE</td>
      </tr>
			  <form id="doFilter" name="doFilter" method="post" action="doFilter.php">
              <tr>
				<td width="70" height="29" valign="top">Jenis Laporan</td>
				<td width="10" height="29" valign="top" >:</td>
				<td width="100" align="left" valign="top"><input type="radio" name="rbtn_type" value="Detail" checked="checked" onClick="showData('Detail');">Detail
														  <input type="radio" name="rbtn_type" value="Rekap" onClick="showData('Rekap');">Rekap<br>
				</td>
				<td align="right" colspan="6"></td>
				</tr>
				<tr>
				<td colspan='6'>
				<table width="1095" border="0" id="rekap_btn" style='display:none;'>
				<tr>
				<td width="123" height="29" valign="top" >Rekap berdasarkan</td>
				<td width="20" height="29" valign="top" >:</td>
				<td width="100" align="left" valign="top" ><input type="radio" name="rbtn_filter" value="Pemanen" checked="checked" onClick="showData('Pemanen');">Pemanen<br>
														  <input type="radio" name="rbtn_filter" value="Blok" onClick="showData('Blok');">Blok Panen<br></td>
				
				<td><input name="blok_post" type="text" id="blok_post" value="" style="background-color:#CCC; width: 300px; height:25px; font-size:15px; display:none"/>
				<input name="nik_pemanen_rekap_post" type="text" id="nik_pemanen_rekap_post" value="" style="background-color:#CCC; width: 300px; height:25px; font-size:15px; display:none"/>
				<input name="nik_mandor_rekap_post" type="text" id="nik_mandor_rekap_post" value="" style="background-color:#CCC; width: 300px; height:25px; font-size:15px; display:none"/>
				</td>
				</tr>
				</table>
				</tr>
				<tr>
                <td width="70" height="29" valign="top" >Company Name</td>
				<td width="10" height="29" valign="top" >:</td>
				<td width="100" align="left" valign="top" ><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$Comp_Name?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
              
				<td width="70" height="29" valign="top" >Start Date</td>
				<td width="10" height="29" valign="top" >:</td>
				<td width="100" valign="top" ><input type="text" name="date1" id="datepicker" class="box_field" <?php if(isset($_POST["date1"])){ echo "value='$_POST[date1]'"; }?>></td>
			  </tr>
              <tr>
                <td width="70" height="29" valign="top" >Business Area</td>
				<td width="10" height="29" valign="top" >:</td>
				<td width="100" align="left" valign="top" ><input name="ID_BA2" type="text" id="ID_BA2" value="<?=$subID_BA_Afd?>" style="background-color:#CCC; width:70px; height:25px; font-size:15px" onmousedown="return false"/></td>
                
				<td width="70" height="29" valign="top" >End Date</td>
				<td width="10" height="29" valign="top" >:</td>
				<td width="100" valign="top" ><input type="text" name="date2" id="datepicker2" class="box_field" <?php if(isset($_POST["date2"])){ echo "value='$_POST[date2]'"; }?>></td>
              </tr>
			  </form>
              <tr>
                <td width="70" height="29" valign="top" >Afdeling</td>
				<td width="10" height="29" valign="top" >:</td>
				<td width="100" align="left" valign="top" ><form id="form1" name="form1" method="post" action="">
                  <?php
				if($jumlahAfd > 0 ){
				$selectoAfd = "<select name=\"Afdeling\" id=\"Afdeling\" onchange=\"this.form.submit();\" style=\"visibility:visible; font-size: 15px; height: 25px; width:70px \">";
				$optiondefAfd = "<option value=\"ALL\"> ALL </option>";
				echo $selectoAfd.$optionGetAfd.$optiondefAfd;
				for($xAfd = 0; $xAfd < $jumlahAfd; $xAfd++){
					echo "<option value=\"$ID_Afd[$xAfd]\">$ID_Afd[$xAfd]</option>"; 
				}
				$selectcAfd = "</select>";
				echo $selectcAfd;
				}           
				?>
                </form></td>
              </tr>
			  <tr>
				<td height="30" colspan="6" valign="bottom" style="border-bottom:solid #000">Tampilkan Data</td>
			  </tr>
			 <tr>
				<td colspan="6">
				<table width="1095" border="0" id="detail">
				<tr>
				<td width="136" height="29" valign="top" >Mandor</td>
				<td width="23" height="29" valign="top" >:</td>
				<td width="100" align="left" valign="top" ><form id="form1" name="form1" method="post" action="">
				  <?php
				if($jumlahMD > 0 ){
				$selectoMD = "<select name=\"NIKMandor\" id=\"NIKMandor\" onchange=\"this.form.submit();\" style=\"visibility:visible; font-size: 15px; height: 25px; width:350px \">";
				$optiondefMD = "<option value=\"ALL\"> ALL </option>";
				echo $selectoMD.$optionGetMD.$optiondefMD;
				for($xMD = 0; $xMD < $jumlahMD; $xMD++){
					echo "<option value=\"$NIK_Mandor[$xMD]\">$Emp_NameMandor[$xMD]</option>"; 
				}
				$selectcMD = "</select>";
				echo $selectcMD;
				}   
				else
				{
					$selectoMD = "<select name=\"NIKMandor\" id=\"NIKMandor\" onchange=\"this.form.submit();\" style=\"visibility:visible; font-size: 15px; height: 25px; width:350px  \">";
					$optiondefMD = "<option value=\"ALL\"> ALL </option>";
					echo $selectoMD.$optionGetMD.$optiondefMD;
					$selectcMD = "</select>";
					echo $selectcMD;
				}
				?>
				</form></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="right"><input type="submit" name="button" id="button" value="TAMPILKAN" style="visibility:visible; width:120px; height: 30px" onclick="formSubmit(1)"/></td>
				</tr>
				<tr>
				<td width="136" height="29" valign="top" >Pemanen</td>
				<td width="23" height="29" valign="top" >:</td>
				<td valign="top" ><form id="form1" name="form1" method="post" action="">
				<?php
				$sql_PMN = "SELECT te.nik, te.emp_name
							FROM t_header_rencana_panen thrp
							INNER JOIN t_detail_rencana_panen tdrp
							ON thrp.id_rencana = tdrp.id_rencana
							INNER JOIN t_blok tb
							ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
							INNER JOIN t_afdeling ta
							ON tb.id_ba_afd = ta.id_ba_afd
							inner join t_employee te on THRP.NIK_PEMANEN = te.NIK
							where   ta.id_ba = '$subID_BA_Afd' $conditionAfd $conditionMD
							group by te.nik, te.emp_name";
				$result_PMN = oci_parse($con, $sql_PMN);
				oci_execute($result_PMN, OCI_DEFAULT);
				while (oci_fetch($result_PMN)) {	
					$NIK_Pemanen[] 		= oci_result($result_PMN, "NIK");
					$Emp_NamePemanen[] 	= oci_result($result_PMN, "EMP_NAME");
				}
				$jumlahPMN = oci_num_rows($result_PMN);
				if($jumlahPMN >0 ){
				$selectoPMN = "<select name=\"NIKPemanen\" id=\"NIKPemanen\" onchange=\"this.form.submit();\" style=\"visibility:visible; font-size: 15px;  height: 25px; width:350px \">";
				$optiondefPMN = "<option value=\"ALL\"> ALL </option>";
				echo $selectoPMN.$optionGetPMN.$optiondefPMN;
				for($xPMN = 0; $xPMN < $jumlahPMN; $xPMN++){
				 echo "<option value=\"$NIK_Pemanen[$xPMN]\">$NIK_Pemanen[$xPMN] - $Emp_NamePemanen[$xPMN]</option>"; 
				}
				$selectcPMN = "</select>";
				echo $selectcPMN;
				}
				?>
				</form>
				</td>
				<td width="123">&nbsp;</td>
				<td width="110">&nbsp;</td>
				<td width="110">&nbsp;</td>
				<td width="100">&nbsp;</td>
				<td width="100">&nbsp;</td>
				<td width="100">&nbsp;</td>
              </tr>
			  </table>
			  
			  <table width="1093" border="0" id="rekap" style='display:none;'>
				<tr>
				<td colspan='6'>
				<table width='1095' border='0' id='tbl_rekap_pemanen'>
					<tr>
					<td width="138" height="29" valign="top" >Mandor</td>
					<td width="23" height="29" valign="top" >:</td>
					<td width="100" align="left" valign="top" ><form id="form1" name="form1" method="post" action="">
					  <?php
					if($jumlahMD > 0 ){
					$selectoMD = "<select name=\"NIKMandor_rekap\" id=\"NIKMandor_rekap\" onchange=\"getData('mandor');\" style=\"visibility:visible; font-size: 15px; height: 25px; width:350px \">";
					$optiondefMD = "<option value=\"ALL\"> ALL </option>";
					echo $selectoMD.$optionGetMD.$optiondefMD;
					for($xMD = 0; $xMD < $jumlahMD; $xMD++){
						echo "<option value=\"$NIK_Mandor[$xMD]\">$Emp_NameMandor[$xMD]</option>"; 
					}
					$selectcMD = "</select>";
					echo $selectcMD;
					}   
					else
					{
						$selectoMD = "<select name=\"NIKMandor_rekap\" id=\"NIKMandor_rekap\" onchange=\"getData('mandor');\" style=\"visibility:visible; font-size: 15px; height: 25px; width:350px  \">";
						$optiondefMD = "<option value=\"ALL\"> ALL </option>";
						echo $selectoMD.$optionGetMD.$optiondefMD;
						$selectcMD = "</select>";
						echo $selectcMD;
					}
					?>
					</form></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td align="right"><input type="submit" name="button" id="button" value="TAMPILKAN" style="visibility:visible; width:120px; height: 30px" onclick="formSubmit(1)"/></td>
				</tr>
					<tr>
					<td width="136" height="29" valign="top" >Pemanen</td>
					<td width="23" height="29" valign="top" >:</td>
					<td valign="top" ><form id="form1" name="form1" method="post" action="">
					<?php
					$sql_PMN = "SELECT te.nik, te.emp_name
								FROM t_header_rencana_panen thrp
								INNER JOIN t_detail_rencana_panen tdrp
								ON thrp.id_rencana = tdrp.id_rencana
								INNER JOIN t_blok tb
								ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
								INNER JOIN t_afdeling ta
								ON tb.id_ba_afd = ta.id_ba_afd
								inner join t_employee te on THRP.NIK_PEMANEN = te.NIK
								where   ta.id_ba = '$subID_BA_Afd' $conditionAfd $conditionMD
								group by te.nik, te.emp_name";
					
					$result_PMN = oci_parse($con, $sql_PMN);
					oci_execute($result_PMN, OCI_DEFAULT);
					while (oci_fetch($result_PMN)) {	
						$NIK_Pemanen[] 		= oci_result($result_PMN, "NIK");
						$Emp_NamePemanen[] 	= oci_result($result_PMN, "EMP_NAME");
					}
					$jumlahPMN = oci_num_rows($result_PMN);
					if($jumlahPMN >0 ){
					$selectoPMN = "<select name=\"NIKPemanen_rekap\" id=\"NIKPemanen_rekap\" onchange=\"getData('pemanen');\" style=\"visibility:visible; font-size: 15px;  height: 25px; width:350px \">";
					$optiondefPMN = "<option value=\"ALL\"> ALL </option>";
					echo $selectoPMN.$optionGetPMN.$optiondefPMN;
					for($xPMN = 0; $xPMN < $jumlahPMN; $xPMN++){
					 echo "<option value=\"$NIK_Pemanen[$xPMN]\">$NIK_Pemanen[$xPMN] - $Emp_NamePemanen[$xPMN]</option>"; 
					}
					$selectcPMN = "</select>";
					echo $selectcPMN;
					}
					?>
					</form>
					</td>
					<td width="123">&nbsp;</td>
					<td width="123">&nbsp;</td>
					<td width="123">&nbsp;</td>
					<td width="123">&nbsp;</td>
					<td width="123">&nbsp;</td>
					<td width="123">&nbsp;</td>
				  </tr>
				  </table>
				  
				  <table width='1095' border='0' id='tbl_rekap_blok' style='display:none;'>
					<tr>
					<td width="148" height="29" valign="top" >Blok</td>
					<td width="25" height="29" valign="top" >:</td>
					<td valign="top" ><form id="form1" name="form1" method="post" action="">
					<?php
					$sql_PMN = "SELECT tb.id_blok, tb.blok_name
								FROM t_header_rencana_panen thrp
								INNER JOIN t_detail_rencana_panen tdrp
								ON thrp.id_rencana = tdrp.id_rencana
								INNER JOIN t_blok tb
								ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
								INNER JOIN t_afdeling ta
								ON tb.id_ba_afd = ta.id_ba_afd
								inner join t_employee te on THRP.NIK_PEMANEN = te.NIK
								where   ta.id_ba = '$subID_BA_Afd' $conditionAfd $conditionMD
								group by tb.id_blok, tb.blok_name order by id_blok";
					//echo $sql_PMN;die();
					$result_PMN = oci_parse($con, $sql_PMN);
					oci_execute($result_PMN, OCI_DEFAULT);
					while (oci_fetch($result_PMN)) {	
						$ID_BLOK[] 		= oci_result($result_PMN, "ID_BLOK");
						$BLOK_NAME[] 	= oci_result($result_PMN, "BLOK_NAME");
					}
					$jumlahPMN = oci_num_rows($result_PMN);
					if($jumlahPMN >0 ){
					$selectoPMN = "<select name=\"blok_rekap\" id=\"blok_rekap\" onchange=\"getData('blok');\" style=\"visibility:visible; font-size: 15px;  height: 25px; width:350px \">";
					$optiondefPMN = "<option value=\"ALL\"> ALL </option>";
					echo $selectoPMN.$optionGetPMN.$optiondefPMN;
					for($xPMN = 0; $xPMN < $jumlahPMN; $xPMN++){
					 echo "<option value=\"$ID_BLOK[$xPMN]\">$ID_BLOK[$xPMN] - $BLOK_NAME[$xPMN]</option>"; 
					}
					$selectcPMN = "</select>";
					echo $selectcPMN;
					}
					?>
					</form>
					</td>
					<td width="123">&nbsp;</td>
					<td width="123">&nbsp;</td>
					<td width="123">&nbsp;</td>
					<td width="123">&nbsp;</td>
					<td width="123">&nbsp;</td>
					<td align="right"><input type="submit" name="button" id="button" value="TAMPILKAN" style="visibility:visible; width:120px; height: 30px" onclick="formSubmit(1)"/></td>
				  </tr>
				  </table>
				  </td>
			  </table>
			  </td>
			  </tr>
			  
    </table></th>
  </tr>
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
