<?php
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
		
		$LaporanDuplicateBCC = "";
		if(isset($_POST["LaporanDuplicateBCC"])){
			$LaporanDuplicateBCC = $_POST["LaporanDuplicateBCC"];
			$_SESSION["LaporanDuplicateBCC"] = $LaporanDuplicateBCC;
		}
		if(isset($_SESSION["LaporanDuplicateBCC"])){
			$LaporanDuplicateBCC = $_SESSION["LaporanDuplicateBCC"];
		}
		
		if($LaporanDuplicateBCC == TRUE){
		
			$sdate1 = "";
			$sdate2 = "";
	
			if(isset($_SESSION["date1"])){
				$sdate1 = $_SESSION['date1'];
			}
			
			if(isset($_SESSION["date2"])){
				$sdate2 = $_SESSION['date2'];
			}
			
			$pagesize = 10;	
			if(isset($_SESSION["sql_bcc_restan1"]))	{
				$sql_bcc_restan1 = $_SESSION["sql_bcc_restan1"];
	//echo $sql_bcc_restan1;
	
				$result_t_bcc_restan = oci_parse($con, $sql_bcc_restan1);
				oci_execute($result_t_bcc_restan, OCI_DEFAULT);
				while(oci_fetch($result_t_bcc_restan)){
				$BA1[] 			= oci_result($result_t_bcc_restan, "BA");
				$BLOK1[] 			= oci_result($result_t_bcc_restan, "BLOK");
				$TANGGAL_RENCANA1[] 			= oci_result($result_t_bcc_restan, "TANGGAL_RENCANA");
				$NO_REKAP_BCC1[] 		= oci_result($result_t_bcc_restan, "NO_REKAP_BCC");
				$NIK_PEMANEN1[] 		= oci_result($result_t_bcc_restan, "NIK_PEMANEN"); 
				$PEMANEN1[]   = oci_result($result_t_bcc_restan, "PEMANEN"); 
				$NIK_KERANI_BUAH1[]   = oci_result($result_t_bcc_restan, "NIK_KERANI_BUAH");
				$KRANI[]   = oci_result($result_t_bcc_restan, "KRANI");
				$IMEI1[]   = oci_result($result_t_bcc_restan, "IMEI");
				
				
				
				}
				$rowBCCRestan = oci_num_rows($result_t_bcc_restan);
				//print_r($DIVISI[]);
				//die ();
				$totalpage = ceil($rowBCCRestan/$pagesize);
				$setPage = $totalpage - 1;
				
		
				//echo "DALAM IF: ".$sql_bcc_restan1;
				//echo "row: ".$rowBCCRestan." ".$totalpage." - ".$setPage;
			}
			else{
				$totalpage = 0;
				$rowBCCRestan  = "";
				//echo "ELSE: ".$sql_bcc_restan1;
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
	document.getElementById("formJC").submit();
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
		<td colspan="5" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>LAPORAN DUPLICATE BCC</strong></span></td>
		<td colspan="1" align="right"><a href="printXLS.php"><input type="submit" name="button" id="button" value="DOWNLOAD TO XLS" style="width:200px; height: 30px; font-size:16px; visibility:<?=$visisub?>" onclick="formSubmit(1)"/></a></td>
      </tr>
      <tr>
        <td height="45" colspan="3" valign="bottom" style="border-bottom:solid #000">LOKASI</td>
		<td height="45" colspan="3" valign="bottom" style="border-bottom:solid #000">PERIODE</td>
      </tr>
    <tr>
        <td width="70" height="29" valign="top" >Company Name</td>
		<td width="10" height="29" valign="top" >:</td>
		<td width="100" align="left" valign="top" ><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$Comp_Name?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
	  
		<td width="150" height="29" valign="top">Start Date</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="355" align="left" valign="top">
        <form id="form1" name="form1" method="post" action="daftarbccrestan.php">
        <input type="text" name="date1" id="datepicker" class="box_field"  onchange="this.form.submit();" value="<?=$sdate1?>" <?php if(isset($_POST["date1"])){ echo "value='$_POST[date1]'"; }?>>
        </form>
        </td>
	</tr>
		
	<tr>
        <td width="70" height="29" valign="top" >Business Area</td>
		<td width="10" height="29" valign="top" >:</td>
		<td width="100" align="left" valign="top" ><input name="ID_BA2" type="text" id="ID_BA2" value="<?=$subID_BA_Afd?>" style="background-color:#CCC; width:70px; height:25px; font-size:15px" onmousedown="return false"/></td>
		
		<td width="150" height="29" valign="top">End Date</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="355" align="left" valign="top">
        <form id="form1" name="form1" method="post" action="daftarbccrestan.php">
        <input type="text" name="date2" id="datepicker2" class="box_field" onchange="this.form.submit();" value="<?=$sdate2?>" <?php if(isset($_POST["date2"])){ echo "value='$_POST[date2]'"; }?>>
        </form>
        </td>
	</tr>
	
	<tr>
		<td height="30" colspan="6" valign="bottom" style="border-bottom:solid #000">Tampilkan Data</td>
	</tr>
	<tr>
		<td align="right" colspan="6">
		<form id="form1" name="form1" method="post" action="dofindbccrestan.php">
		<input name="sdate1" type="text" id="sdate1" value="<?=$sdate1?>" onmousedown="return false" style="display:none"/>
		<input name="sdate2" type="text" id="sdate2" value="<?=$sdate2?>" onmousedown="return false" style="display:none"/>
		<input type="submit" name="button6" id="button6" value="TAMPILKAN" style="width:120px; height: 30px; font-size:18px" />
		</form>
		</td>
	</tr>    
        <tr>
        <td colspan="6" valign="top">
          <?php
		if($rowBCCRestan > 0){

		echo "
				<table width=\"1140\" border=\"1\" bordercolor=\"#9CC346\">
				  <tr bgcolor=\"#9CC346\">
					<td width=\"50\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BA</td>
					<td width=\"60\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">TANGGAL RENCANA</td>
					<td width=\"50\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BLOK</td>
					<td width=\"120\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NO REKAP BCC</td>
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NIK PEMANEN</td>
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NAMA PEMANEN</td>
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NIK KRANI BUAH</td>
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NAMA KRANI BUAH</td>
					<td width=\"50\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">IMEI</td>
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
			

		$fixedBCC2 = sepbcc($NO_REKAP_BCC1[$xJAN]);

			
		echo "<tr style=\"font-size:12px\" bgcolor=$bg>";
		echo "<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BA1[$xJAN]</td>
					<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$TANGGAL_RENCANA1[$xJAN]</td>
					<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BLOK1[$xJAN]</td>
					<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$fixedBCC2</td>
					<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NIK_PEMANEN1[$xJAN]</td>
					<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$PEMANEN1[$xJAN]</td>
					<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NIK_KERANI_BUAH1[$xJAN]</td>
					<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$KRANI[$xJAN]</td>
					<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">&nbsp;$IMEI1[$xJAN]</td>
					
					";


		}
		echo "</tr></table>";
		}
		?>        
		</td>
      </tr>
      <tr>
        <td colspan="6" align="right">
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
          </table></td>
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
?>        </td>
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