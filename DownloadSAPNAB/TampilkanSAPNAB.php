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
		
		$SAPTemplateNAB = "";
		if(isset($_POST["SAPTemplateNAB"])){
			$SAPTemplateNAB = $_POST["SAPTemplateNAB"];
			$_SESSION["SAPTemplateNAB"] = $SAPTemplateNAB;
		}
		if(isset($_SESSION["SAPTemplateNAB"])){
			$SAPTemplateNAB = $_SESSION["SAPTemplateNAB"];
		}
		
		if($SAPTemplateNAB == TRUE){
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

	$('#button').click(function() {
		if ($('#datepicker').val() == '' || $('#datepicker2').val() == '') {
			alert('Pilih Periode');
			return false;
		} else if (!$("input:radio[name='TampilNAB']").is(":checked")) {
			alert('Pilih Tampilkan Data');
			return false;
		}
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
		<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>SAP TEMPLATE NAB</strong></span></td>
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
				<td width="100" valign="top"><input type="text" name="date1" id="datepicker" class="box_field" <?php if(isset($_POST["date1"])){ echo "value='$_POST[date1]'"; }?>></td>
			  </tr>
              <tr>
                <td width="70" height="29" valign="top">Business Area</td>
				<td width="10" height="29" valign="top">:</td>
				<td width="100" align="left" valign="top"><input name="ID_BA2" type="text" id="ID_BA2" value="<?=$subID_BA_Afd?>" style="background-color:#CCC; width:70px; height:25px; font-size:15px" onmousedown="return false"/></td>
                
				<td width="70" height="29" valign="top" >End Date</td>
				<td width="10" height="29" valign="top">:</td>
				<td width="100" valign="top" ><input type="text" name="date2" id="datepicker2" class="box_field" <?php if(isset($_POST["date2"])){ echo "value='$_POST[date2]'"; }?>></td>			  
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
					echo "<option value=\"$ID_Afd[$xAfd]\">$ID_Afd[$xAfd]</option>"; 
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
                <td colspan="3" ><input type="radio" name="TampilNAB" id="TampilNAB" value="ALL"/>Tampilkan semua NAB</td>
				<td align="right" colspan="6" ><input type="submit" name="button" id="button" value="TAMPILKAN" style="width:120px; height: 30px"/></td>
              </tr>
              <tr>
                <td colspan="3" ><input type="radio" name="TampilNAB" id="TampilNAB" value="Y"/>Hanya Tampilkan yang SUDAH pernah di download</td>
              </tr>
              <tr>
                <td colspan="3"><input type="radio" name="TampilNAB" id="TampilNAB" value="N"/>Hanya Tampilkan yang BELUM pernah di download</td>
              </tr>
            </table> 
          <form/>
          
          
   </th>
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
