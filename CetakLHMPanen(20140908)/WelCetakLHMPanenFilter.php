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
		
		$CetakLHMPanen = "";
		if(isset($_POST["CetakLHMPanen"])){
			$CetakLHMPanen = $_POST["CetakLHMPanen"];
			$_SESSION["CetakLHMPanen"] = $CetakLHMPanen;
		}
		if(isset($_SESSION["CetakLHMPanen"])){
			$CetakLHMPanen = $_SESSION["CetakLHMPanen"];
		}	
			
		if($CetakLHMPanen == TRUE){

			$conditionAfd = "";
			$optionGetAfd = "";
			$sesAfdeling = "";
			if(isset($_POST["Afdeling"])){
			$_SESSION["Afdeling"] = $_POST["Afdeling"];
			}
			
			if(isset($_SESSION["Afdeling"])){
				$sesAfdeling = $_SESSION["Afdeling"];
				//echo $sesAfdeling;
				$Ses_sql_afd = "select * from t_afdeling where ID_BA = '$subID_BA_Afd' and ID_AFD = nvl(decode('$sesAfdeling', 'ALL', null, '$sesAfdeling'), ID_AFD) ORDER BY ID_BA";
				//echo "here".$sql_afd;
				$Ses_result_afd = oci_parse($con, $Ses_sql_afd);
				oci_execute($Ses_result_afd, OCI_DEFAULT);
				while (oci_fetch($Ses_result_afd)) {	
					$Ses_ID_BA_Afd[] 		= oci_result($Ses_result_afd, "ID_BA_AFD");
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
			
			
			$sdate1 = "";
			$sdate2 = "";
			if(isset($_POST["date1"])){
				$_SESSION['date1'] = date("Y-m-d", strtotime($_POST["date1"]));
				unset($_SESSION['date2']);
				$sdate2 = "";
			}
	
			if(isset($_POST["date2"])){
				$_SESSION['date2'] = date("Y-m-d", strtotime($_POST["date2"]));
				
				// Modified by SABRINA on 27/02/2014 : Penambahan validasi max penarikan report adalah 31 hari.
				$diff = strtotime($_SESSION['date2']) - strtotime($_SESSION['date1']);
				$diff = floor($diff/(60*60*24));;
				if($diff > 31) {
					$_SESSION['err'] = "Periode Pengambilan Report Melebihi 31 hari.";
				}
				
				if($_SESSION['date2'] == "1970-01-01")
				{
					$_SESSION['date2'] = "";
				}
			}
	
			if(isset($_SESSION['date1'])){
				$sdate1 = $_SESSION['date1'];
			}
			
			if(isset($_SESSION['date2'])){
				$sdate2 = $_SESSION['date2'];
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
		changeYear: true,	  
		maxDate: "+0D" // Modified by SABRINA on 27/02/2014 : Penambahan validasi max penarikan report adalah hari ini.
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
<table width="978" height="390" border="0" align="center">
  <tr>
    <th height="197" scope="row" align="center"><table width="937" border="0" id="setbody2">
      <tr>
        <td height="50" colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>CETAK LHM PANEN</strong></span></td>
        <td height="50" colspan="3" align="right" valign="baseline"><input type="submit" name="button" id="button" value="CETAK" style="visibility:visible; width:120px; height: 30px" onclick="formSubmit(1)"/></td>
      </tr>
      <tr>
        <td height="9" colspan="3" valign="bottom" style="font-size:14px ; border-bottom:solid #000">LOKASI</td>
        <td height="9" colspan="3" valign="bottom" style="font-size:14px ; border-bottom:solid #000">PERIODE</td>
      </tr>
      <form id="submittanggal1" name="submittanggal1" method="post" action="WelCetakLHMPanenFilter.php">
      <tr>
        <td width="169">Company Name</td>
        <td width="11">:</td>
        <td width="349"><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$Comp_Name?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
        <td width="75"><p><span>Start Date</span></p></td>
        <td width="6">:</td>
        <td width="301">
        <input type="text" name="date1" id="datepicker" class="box_field"  onchange="this.form.submit();" value="<?=$sdate1?>" <?php if(isset($_POST["date1"])){ echo "value='$_POST[date1]'"; }?>>
        </td>
      </tr>
      <tr>
        <td>Business Area</td>
        <td>:</td>
        <td><input name="ID_BA2" type="text" id="ID_BA2" value="<?=$subID_BA_Afd?>" style="background-color:#CCC; width:70px; height:25px; font-size:15px" onmousedown="return false"/></td>
        <td ><span>End Date</span></td>
        <td >:</td>
        <td ><input type="text" name="date2" id="datepicker2" class="box_field" onchange="this.form.submit();" value="<?=$sdate2?>" <?php if(isset($_POST["date2"])){ echo "value='$_POST[date2]'"; }?>>
        
        </td>
      </tr>
      </form>
      <tr>
        <td valign="top"> Afdeling</td>
        <td valign="top">:</td>
        <td valign="top" style="font-size:16px"><form id="form1" name="form1" method="post" action="">
          <?php
				//Afdeling
				
					
				if($jumlahAfd > 0 ){
				//$jumlahRecord = $_SESSION['jumlahAfd'];
				$selectoAfd = "<select name=\"Afdeling\" id=\"Afdeling\" onchange=\"this.form.submit();\" style=\"visibility:visible; font-size: 15px; height: 25px \">";
				//$optiondefAfd = "<option value=\"ALL\"> ALL </option>"; // Modified by SABRINA on 27/02/2014 : Tidak ada pilihan ALL.
				echo $selectoAfd.$optionGetAfd.$optiondefAfd;
				for($xAfd = 0; $xAfd < $jumlahAfd; $xAfd++){
					echo "<option value=\"$ID_Afd[$xAfd]\">$ID_Afd[$xAfd]</option>"; 
				}
				$selectcAfd = "</select>";
				echo $selectcAfd;
				}           
				?>
        </form></td>
        <td colspan="3" >&nbsp;</td>
      </tr>
      <tr>
        <td height="28" colspan="8" valign="bottom" style="border-bottom:solid #000">&nbsp;</td>
      </tr>
      <form id="doFilter" name="doFilter" method="post" action="doFilter.php">
        <tr>
          <td valign="top">Mandor</td>
          <td valign="top">:</td>
          <td  valign="top"><?php
        //Mandor
        $sql_MD = "select NIK_Mandor, f_get_empname(NIK_Mandor) Nama_Mandor from t_header_rencana_panen thrp 
        inner join t_detail_rencana_panen tdrp on thrp.id_rencana = tdrp.id_rencana 
        inner join t_blok tb on tdrp.id_ba_afd_blok = tb.id_ba_afd_blok 
        inner join t_afdeling ta on tb.id_ba_afd = ta.id_ba_afd 
        WHERE ta.ID_BA = '$subID_BA_Afd' 
        and ta.id_afd = nvl (decode ('$sesAfdeling', 'ALL', null, '$sesAfdeling'), ta.id_afd) 
        and to_char(thrp.tanggal_rencana,'YYYY-MM-DD') between '$sdate1' and nvl ('$sdate2', '$sdate1')
        group by NIK_Mandor 
        order by Nama_Mandor";
        
        //echo $sql_MD;
        $result_MD = oci_parse($con, $sql_MD);
        oci_execute($result_MD, OCI_DEFAULT);
        while (oci_fetch($result_MD)) {	
            $NIK_Mandor[] 		= oci_result($result_MD, "NIK_MANDOR");
            $Emp_NameMandor[] 	= oci_result($result_MD, "NAMA_MANDOR");
        }
        $jumlahMD = oci_num_rows($result_MD);
        
        //echo "mandor".$sql_MD. $jumlahMD;
        
        //echo "here".$sql_MD .$jumlahMD;
        if($jumlahMD >0 ){
        //$jumlahRecord = $_SESSION['jumlahMD'];
        $selectoMD = "<select name=\"NIKMandor\" id=\"NIKMandor\" style=\"visibility:visible; font-size: 15px;  height: 25px\">";
        $optiondefMD = "<option value=\"ALL\"> ALL </option>";
        echo $selectoMD.$optiondefMD;
        for($xMD = 0; $xMD < $jumlahMD; $xMD++){
         echo "<option value=\"$NIK_Mandor[$xMD]\">$NIK_Mandor[$xMD] - $Emp_NameMandor[$xMD]</option>"; 
        }
        $selectcMD = "</select>";
        echo $selectcMD;
        }
        ?></td>
          <td colspan="3"  valign="top">&nbsp;</td>
          </tr>
        <input name="valueAfd" type="text" id="valueAfd" value="<?=$Ses_ID_Afd[0]?>" onmousedown="return false" style="display:none"/>
        <input name="sdate1" type="text" id="sdate1" value="<?=$sdate1?>" onmousedown="return false" style="display:none"/>
        <input name="sdate2" type="text" id="sdate2" value="<?=$sdate2?>" onmousedown="return false" style="display:none"/>
      </form>
      <tr>
        <td>&nbsp;
          <?php
                
				//echo  $sql_MD. $jumlahMD;
				?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="3">&nbsp;</td>
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
