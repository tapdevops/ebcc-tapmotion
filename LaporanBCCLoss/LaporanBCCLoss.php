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
		header("location:login.php");
	}
	else
	{
		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
	
		$LaporanLaporanBCCLoss = "";
		if(isset($_POST["LaporanLaporanBCCLoss"])){
			$LaporanLaporanBCCLoss = $_POST["LaporanLaporanBCCLoss"];
			$_SESSION["LaporanLaporanBCCLoss"] = $LaporanLaporanBCCLoss;
		}
		if(isset($_SESSION["LaporanLaporanBCCLoss"])){
			$LaporanLaporanBCCLoss = $_SESSION["LaporanLaporanBCCLoss"];
		}
		if($LaporanLaporanBCCLoss == TRUE){
	
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

function formSubmit(x)
{
	if(x == 1){
	document.getElementById("doFilter").submit();
    }
}

function change(x)
{
	if(x == 1){
	document.getElementById("Afdeling").style.visibility="visible";
	document.getElementById("NIKMandor").style.visibility="hidden";
	document.getElementById("NIKPemanen").style.visibility="hidden";
	document.getElementById("NIKMandor").value="kosong";
	document.getElementById("NIKPemanen").value="kosong";
	document.getElementById("button").style.visibility="visible";
	}
	if(x == 2){
	document.getElementById("Afdeling").style.visibility="hidden";
	document.getElementById("NIKMandor").style.visibility="visible";
	document.getElementById("NIKPemanen").style.visibility="hidden";
	document.getElementById("Afdeling").value="kosong";
	document.getElementById("NIKPemanen").value="kosong";
	document.getElementById("button").style.visibility="visible";
	}
	if(x == 3){
	document.getElementById("Afdeling").style.visibility="hidden";
	document.getElementById("NIKMandor").style.visibility="hidden";
	document.getElementById("NIKPemanen").style.visibility="visible";
	document.getElementById("Afdeling").value="kosong";
	document.getElementById("NIKMandor").value="kosong";
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

function showData(value) {
	if(value == "wo"){
		document.getElementById('datepicker2').style.visibility='hidden';
		document.getElementById('datepicker2').style.display='none';
		document.getElementById('end').style.visibility='hidden';
		document.getElementById('end').style.display='none';
		document.getElementById('titikdua2').style.visibility='hidden';
		document.getElementById('titikdua2').style.display='none';
		document.getElementById('jenis_laporan').style.visibility='visible';
	} 
	//Added by Ardo 19-08-2016 : Synchronize BCC - Laporan Loss/Wo/Delete
	else if(value == "del"){
		
		document.getElementById('datepicker2').style.visibility='visible';
		document.getElementById('datepicker2').style.display='block';
		document.getElementById('end').style.visibility='visible';
		document.getElementById('end').style.display='block';
		document.getElementById('titikdua2').style.visibility='visible';
		document.getElementById('titikdua2').style.display='block';
		document.getElementById('jenis_laporan').style.visibility='hidden';
	} else {
		
		document.getElementById('datepicker2').style.visibility='visible';
		document.getElementById('datepicker2').style.display='block';
		document.getElementById('end').style.visibility='visible';
		document.getElementById('end').style.display='block';
		document.getElementById('titikdua2').style.visibility='visible';
		document.getElementById('titikdua2').style.display='block';
		document.getElementById('jenis_laporan').style.visibility='visible';
	}
}
</script>

<?php
		}
		else{
			header("location:../menu/authoritysecure.php");
		}
	}
}
else{
	$_SESSION[err] = "Please Login!".$Job_Code."<br>".$username."<br>".$Emp_Name."<br>".$Jenis_Login."<br>".$subID_BA_Afd;
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
		<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>LAPORAN BCC LOSS/WO/DELETE</strong></span></td>
      </tr>
      <tr>
        <td height="45" colspan="3" valign="bottom" style="border-bottom:solid #000">LOKASI</td>
		<td height="45" colspan="3" valign="bottom" style="border-bottom:solid #000">PERIODE</td>
      </tr>
          <form id="form3" name="form3" method="post" action="doFilter.php">
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
                    
					<td width="70" height="29" valign="top" ><div id='end'>End Date</div></td>
					<td width="10" height="29" valign="top" ><div id='titikdua2'>:</div></td>
					<td width="100" valign="top" ><input type="text" name="date2" id="datepicker2" class="box_field" <?php if(isset($_POST["date2"])){ echo "value='$_POST[date2]'"; }?>></td>
                  </tr>
                  <tr>
                    <td width="70" height="29" valign="top" >Afdeling</td>
					<td width="10" height="29" valign="top" >:</td>
					<td width="100" align="left" valign="top" ><?php
					//Afdeling
					$sql_value_afd = "select *  from t_Afdeling tafd
					left join t_BussinessArea tba
					on tafd.id_ba = tba.id_ba WHERE tba.id_ba = '$subID_BA_Afd'";
					
					$result_value_afd = oci_parse($con, $sql_value_afd);
					oci_execute($result_value_afd, OCI_DEFAULT);
					while(oci_fetch($result_value_afd)){
						$ID_AFD[]		= oci_result($result_value_afd, "ID_AFD");
						$ID_BA_Afd[]		= oci_result($result_value_afd, "ID_BA_AFD");
					}
					$roweffec_afd = oci_num_rows($result_value_afd);

					//$jumlahAfd = $_SESSION['jumlahAfd'];
					$selectoAfd = "<select name=\"Afdeling\" id=\"Afdeling\" style=\"visibility:visible; font-size: 15px\">";
					$optiondefAfd = "<option value=\"ALL\"> ALL </option>";
					echo $selectoAfd.$optiondefAfd;
					for($xAfd = 0; $xAfd < $roweffec_afd; $xAfd++){
						echo "<option value=\"$ID_AFD[$xAfd]\">$ID_AFD[$xAfd]</option>"; 
					}
					$selectcAfd = "</select>";
					echo $selectcAfd;
							  
					?></td>
                    </tr> 
              <tr>
				<td height="30" colspan="6" valign="bottom" style="border-bottom:solid #000">Tampil Berdasarkan</td>
			  </tr>
			  <tr>
				<td width="100" height="29" valign="top" >Laporan</td>
				<td width="10" height="29" valign="top" >:</td>
				<td width="100" align="left" valign="top" >
				<input type="radio" name="rbtn_filter" value="loss" checked="checked" onClick="showData('loss');">BCC Loss
				<input type="radio" name="rbtn_filter" value="wo" onClick="showData('wo');">BCC Write Off
				<input type="radio" name="rbtn_filter" value="delete" onClick="showData('del');">BCC Delete<br>
			  </td>
				<td width="100" align="left" valign="top" ></td>
				<td colspan="6" height="15" align="right"><input type="submit" name="button" id="button" value="TAMPILKAN" style="width:120px; height: 30px"/></td>
			  </tr>
			  <tr>
			  <td width="100" height="29" valign="top" >Jenis Laporan</td>
				<td width="10" height="29" valign="top" >:</td>
				<td width="100" align="left" valign="top" id="jenis_laporan">
			
				<input type="radio" name="rbtn_filter_type" id="jenis_lap_rekap" value="rekap" checked="checked" >Rekap
				<input type="radio" name="rbtn_filter_type" id="jenis_lap_detail" value="detail" >Detail<br>
				
			  </td>
			  </tr>
          </form>
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
