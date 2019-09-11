<?php

//print_r($_POST);
session_start();
include("../include/Header.php");
if(isset($_SESSION['NIK'])){	
$NIK = $_SESSION['NIK'];
$Comp_Name = $_SESSION['Comp_Name'];
$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
	
	if($NIK == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:login.php");
	}
	else{
		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		
		$LaporanBCCRestan = "";
		if(isset($_POST["LaporanBCCRestan"])){
			$LaporanBCCRestan = $_POST["LaporanBCCRestan"];
			$_SESSION["LaporanBCCRestan"] = $LaporanBCCRestan;
		}
		if(isset($_SESSION["LaporanBCCRestan"])){
			$LaporanBCCRestan = $_SESSION["LaporanBCCRestan"];
		}
		
		if($LaporanBCCRestan == TRUE){
		
			$sdate1 = "";
			$sdate2 = "";
	
			if(isset($_SESSION["date1"])){
				$sdate1 = $_SESSION['date1'];
			}
			
			if(isset($_SESSION["date2"])){
				$sdate2 = $_SESSION['date2'];
			}
			
			$pagesize = 10;	
			if(isset($_SESSION["sql_bcc_restan"]))	{
				$sql_bcc_restan = $_SESSION["sql_bcc_restan"];
	//echo $sql_bcc_restan; die;
	
				$result_t_bcc_restan = oci_parse($con, $sql_bcc_restan);
				oci_execute($result_t_bcc_restan, OCI_DEFAULT);
				while(oci_fetch($result_t_bcc_restan)){
				$COMPANY_CODE[] 			= oci_result($result_t_bcc_restan, "ID_CC");
				$BUSINESS_AREA[] 			= oci_result($result_t_bcc_restan, "ID_BA");
				$DIVISI[] 		= oci_result($result_t_bcc_restan, "ID_AFD");
				$TGL_PANEN[] 		= oci_result($result_t_bcc_restan, "TANGGAL_RENCANA"); 
				$BLOK[]   = oci_result($result_t_bcc_restan, "ID_BLOK"); 
				$MANDOR[]   = oci_result($result_t_bcc_restan, "NIK_MANDOR");
				$NO_BCC[]   = oci_result($result_t_bcc_restan, "NO_BCC");
				$TBS[]   = oci_result($result_t_bcc_restan, "TBS"); 
				$BRD[]   = oci_result($result_t_bcc_restan, "BRD");
				$ESTIMASI_BERAT[]   = oci_result($result_t_bcc_restan, "ESTIMASI_BERAT");
				
				}
				$rowBCCRestan = oci_num_rows($result_t_bcc_restan);
				
				$totalpage = ceil($rowBCCRestan/$pagesize);
				$setPage = $totalpage - 1;
				//echo "DALAM IF: ".$sql_bcc_restan;
				//echo "row: ".$rowBCCRestan." ".$totalpage." - ".$setPage;
			}
			else{
				$totalpage = 0;
				$rowBCCRestan  = "";
				//echo "ELSE: ".$sql_bcc_restan;
			}
			
			if(isset($_SESSION["Cpage"])){
				$sesPage = $_SESSION["Cpage"];
			}
			else{
				$sesPage = 0;
			}
			
			if(isset($_GET["page"])){
			$OnPage = $_GET["page"];
			$CPage = 1;
				if($OnPage == "next"){
					$sesPageres = $sesPage + $CPage;
					if($sesPageres >= $setPage){
						$sesPageres = $setPage;
					}
					$calPage = $sesPageres * $pagesize;
					$_SESSION["Cpage"]  = $sesPageres; 
				}
				
				else if($OnPage == "back"){
					$sesPageres = $sesPage - $CPage;
					if($sesPageres <= 0){
						$sesPageres = 0;
					}
					$calPage = $sesPageres * $pagesize;
					$_SESSION["Cpage"]  = $sesPageres; 
				}
				
				else if($OnPage == "first"){
					$sesPageres = 0;
					if($sesPageres <= 0){
						$sesPageres = 0;
					}
					$calPage = $sesPageres * $pagesize;
					$_SESSION["Cpage"]  = $sesPageres; 
				}
				
				else if($OnPage == "last"){
					$sesPageres = $totalpage;
					if($sesPageres >= $setPage){
						$sesPageres = $setPage;
					}
					$calPage = $sesPageres * $pagesize;
					$_SESSION["Cpage"]  = $sesPageres; 
				}
				
				else{
					$calPage = 0; 
				}
			}
			else{
				$CPage = 0;
				$sesPageres = $sesPage + $CPage;
				$calPage = $sesPageres * $pagesize;
				$_SESSION["Cpage"]  = $sesPageres;  
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
</script>

<script type="text/javascript">

function formSubmit(x)
{
	if(x == 1){
	document.getElementById("doFilter").submit();
    }
}
</script>


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
        <td colspan="5" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>LAPORAN BCC RESTAN</strong></span></td>
		<td height="19" colspan="1" align="right"><a href="printXLS.php"><input type="submit" name="button" id="button" value="DOWNLOAD TO XLS" style="width:200px; height: 30px; font-size:16px; visibility:<?=$visisub?>" onclick="formSubmit(1)"/></a></td>
        </tr>
      <tr>
        <td height="45" colspan="3" valign="bottom" style="border-bottom:solid #000">LOKASI</td>
		<td height="45" colspan="3" valign="bottom" style="border-bottom:solid #000">PERIODE</td>
        </tr>
      <form id="doFilter" name="doFilter" method="post" action="dofindbccrestan.php">
	  <tr>
		<td width="70" height="29" valign="top" >Company Name</td>
		<td width="10" height="29" valign="top" >:</td>
		<td width="100" align="left" valign="top" ><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$Comp_Name?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
	  
		<td width="70" height="29" valign="top" >Start Date</td>
		<td width="10" height="29" valign="top" >:</td>
		<td width="100" valign="top" ><input type="text" name="date1" id="datepicker" class="box_field" <?php if(isset($_POST["date1"])){ echo "value='$_POST[date1]'"; }else{if (isset($_GET[date1])){ echo "value='$_GET[date1]'"; }}?>></td>
	  </tr>
	  <tr>
		<td width="70" height="29" valign="top" >Business Area</td>
		<td width="10" height="29" valign="top" >:</td>
		<td width="100" align="left" valign="top" ><input name="ID_BA2" type="text" id="ID_BA2" value="<?=$subID_BA_Afd?>" style="background-color:#CCC; width:70px; height:25px; font-size:15px" onmousedown="return false"/></td>
		
		<td width="70" height="29" valign="top" >End Date</td>
		<td width="10" height="29" valign="top" >:</td>
		<td width="100" valign="top" ><input type="text" name="date2" id="datepicker2" class="box_field" <?php if(isset($_POST["date2"])){ echo "value='$_POST[date2]'"; }else{if (isset($_GET[date2])){ echo "value='$_GET[date2]'"; }}?>></td>
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
		<td align="right" colspan="6"><input type="submit" name="button" id="button" value="TAMPILKAN" style="visibility:visible; width:120px; height: 30px" onclick="formSubmit(1)"/></td>
	  </tr>
        
        <tr>
        <td colspan="6" valign="top">
        
        
          <?php
if($rowBCCRestan > 0){
	
echo "
		<table width=\"1134\" border=\"1\" bordercolor=\"#9CC346\">
          <tr bgcolor=\"#9CC346\">
            <td width=\"50\" align=\"center\" style=\"font-size:14px \" id=\"bordertable\">Company Code</td>
			<td width=\"50\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Business Area</td>
			<td width=\"50\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">AFD</td>
            <td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Tanggal Panen</td>
            <td width=\"50\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Blok</td>
			<td width=\"150\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Mandor</td>
			<td width=\"150\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">No.BCC</td>
				<td width=\"80\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">TBS (JJG)</td>
					<td width=\"80\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BRD (KG)</td>
						<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">ESTIMASI BERAT(KG)</td>
          </tr>
";
		  
$endPage = $calPage + $pagesize;
for($xJAN = $calPage; $xJAN <  $rowBCCRestan && $xJAN <$endPage; $xJAN++){
	$no = $xJAN +1;
	
	if(($xJAN % 2) == 0){
		$bg = "#F0F3EC";
	}
	else{
		$bg = "#DEE7D2";
	}

$fixedBCC = separator($NO_BCC[$xJAN]);
	
echo "<tr bgcolor=$bg  style=\"font-size:12px\">";
echo "<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$COMPANY_CODE[$xJAN]</td>
            <td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BUSINESS_AREA[$xJAN]</td>
            <td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$DIVISI[$xJAN]</td>
			<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$TGL_PANEN[$xJAN]</td>
			<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BLOK[$xJAN]</td>
			<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$MANDOR[$xJAN]</td>
			<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$fixedBCC</td>
		    <td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$TBS[$xJAN]</td>
			<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BRD[$xJAN]</td>
			<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$ESTIMASI_BERAT[$xJAN]</td>
            ";

}
echo "</tr></table>";
}
else{
echo "
	<table align=\"center\" style=\"font-size:18px ; font-style: italic; color:#8A0000\">
		<tr align=\"center\">
			<td>Data not Found</td>
		</tr>
	</table>";	
}
?>
        </td>
      </tr>
	  
	  <tr>
        <td colspan="6" align="right">
<?php
if($rowBCCRestan > 0){
?>
          <table width="400" border="0">
            <tr>
			  <td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="daftarbccrestan.php?page=first">
                <input type="button" name="button6" id="button6" value="&lt;&lt; First" style="width:70px; background-color:#9CC346"/>
              </a></td>
              <td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="daftarbccrestan.php?page=back">
                <input type="button" name="button5" id="button5" value="&lt; Back" style="width:70px; background-color:#9CC346"/>
              </a></td>
              <td width="100" align="center" valign="middle" style="background-color:#9CC346; font-size:12px"><span style="padding-top:0px">Page
                <?=$sesPageres+1?>
                of
                <?=$totalpage?>
              </span></td>
              <td width="70" align="left" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="daftarbccrestan.php?page=next"></a><a href="daftarbccrestan.php?page=next">
                <input type="button" name="button4" id="button4" value="Next &gt;" style="width:70px; background-color:#9CC346"/>
              </a></td>
			  <td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="daftarbccrestan.php?page=last">
                <input type="button" name="button7" id="button7" value="Last &gt;&gt;" style="width:70px; background-color:#9CC346"/>
              </a></td>
            </tr>
          </table>
<?php
}
?>
          </td>
      </tr>
	  
      <tr>
        <td colspan="6" align="center">
          
          <?php

if(isset($_SESSION['err'])){
	$err = $_SESSION['err'];
	if($err!=NULL)
	{
		echo $err;
		unset($_SESSION['err']);
	}
}

if(isset($_SESSION["ctr"])){
	$ctr = $_SESSION["ctr"];
	//echo "ctr".$ctr;
	if($ctr!=NULL){
		for($ins = 0 ; $ins < $ctr; $ins++ ){
			if(isset($_SESSION["insert$ins"])){
				$insert = $_SESSION["insert$ins"];
				if($insert!=NULL){
					echo $insert;
					unset($_SESSION["insert$ins"]);
				}
				unset($_SESSION["insert$ins"]);
			}
		}
	}
	else{
		echo "no data updated";
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
<?php
}
else{
	$_SESSION[err] = "tolong login dulu!";
	header("location:../index.php");
}

?>