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
		
		$LaporanNAB = "";
		if(isset($_POST["LaporanNAB"])){
			$LaporanNAB = $_POST["LaporanNAB"];
			$_SESSION["LaporanNAB"] = $LaporanNAB;
		}
		if(isset($_SESSION["LaporanNAB"])){
			$LaporanNAB = $_SESSION["LaporanNAB"];
		}
		if($LaporanNAB == TRUE){
			
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
			
			if(isset($_POST["status_export"])){
				$_SESSION["status_export"] = $_POST["status_export"];
			} else {
				$_SESSION["status_export"] = 'ALL';
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
		<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>LAPORAN NAB</strong></span></td>
      </tr>
      <tr>
        <td height="45" colspan="3" valign="bottom" style="border-bottom:solid #000">LOKASI</td>
		<td height="45" colspan="3" valign="bottom" style="border-bottom:solid #000">PERIODE</td>
      </tr>
			<form id="doFilter" name="doFilter" method="post" action="doFilter.php">
              <tr>
				<td width="70" height="29" valign="top">Jenis Laporan</td>
				<td width="10" height="29" valign="top" >:</td>
				<td width="100" align="left" valign="top">
					<input type="radio" name="rbtn_type" value="Detail" checked="checked">Detail
					<input type="radio" name="rbtn_type" value="Rekap">Rekap<br>
				</td>
				<td align="right" colspan="6"></td>
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
			<form id="form1" name="form1" method="post" action="">
              <tr>
                <td width="70" height="29" valign="top" >Afdeling</td>
				<td width="10" height="29" valign="top" >:</td>
				<td width="100" align="left" valign="top" >
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
                </td>
              </tr>
			  <!-- Added by Ardo 16-08-2016 : Synchronize EBCC to SAP - Laporan NAB -->
			  <tr>
					<td width="70" height="29" valign="top" >Status NAB</td>
					<td width="10" height="29" valign="top" >:</td>
					<td width="100" align="left" valign="top" >
						<select name="status_export" id="status_export" onchange="this.form.submit();" style="visibility:visible; font-size: 15px">
							<option value="ALL" <?= ($_SESSION['status_export']=="ALL")?"selected":"" ?>>ALL</option>
							<option value="Belum Export" <?= ($_SESSION['status_export']=="Belum Export")?"selected":"" ?>>Belum Export</option>
							<option value="Sudah Export" <?= ($_SESSION['status_export']=="Sudah Export")?"selected":"" ?>>Sudah Export</option>
							<option value="Sudah Post" <?= ($_SESSION['status_export']=="Sudah Post")?"selected":""; ?>>Sudah Post</option>
						</select>
					</td>
				</tr>
				</form>
              <tr>
				<td height="30" colspan="6" valign="bottom" style="border-bottom:solid #000">Tampilkan Data</td>
			  </tr>
			  <tr>
				<td align="right" colspan="6"><input type="submit" name="button" id="button" value="TAMPILKAN" style="visibility:visible; width:120px; height: 30px" onclick="formSubmit(1)"/></td>
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
