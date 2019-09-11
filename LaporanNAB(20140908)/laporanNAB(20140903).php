<?php
session_start();
include("../include/Header.php");
if(isset($_SESSION['NIK'])){	
$NIK = $_SESSION['NIK'];	

	if($NIK == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:login.php");
	}
	else{
		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		
		 
		$sql_user_login  = 	"select c.id_cc as COMPANY_CODE, c.id_ba as BUSINESS_AREA, d.comp_name AS COMPANY_NAME
							from t_employee a, t_afdeling b, t_bussinessarea c, t_companycode d
							where a.id_ba_afd = b.id_ba_afd and b.id_ba = c.id_ba and c.id_cc = d.id_cc 
							and a.nik = '$NIK'";
		$result_user_login	= select_data($con,$sql_user_login);
		$company_code		= $result_user_login["COMPANY_CODE"];
		$business_area		= $result_user_login["BUSINESS_AREA"];
		$company_name		= $result_user_login["COMPANY_NAME"];
		
		$pagesize = 10;	
		$sql_Laporan_NAB = $_SESSION['sql_Laporan_NAB'];

		$result_laporan_NAB = oci_parse($con, $sql_Laporan_NAB);
		oci_execute($result_laporan_NAB, OCI_DEFAULT);
		while(oci_fetch($result_laporan_NAB))
		{
			$TGL_PANEN[] 			= OCI_RESULT($result_laporan_NAB, "TGL_NAB");
			$NO_NAB[] 			= OCI_RESULT($result_laporan_NAB, "NO_NAB");
			$ID_AFD[] 		= OCI_RESULT($result_laporan_NAB, "ID_AFD");
			$NOPOL[] 		= OCI_RESULT($result_laporan_NAB, "NO_POLISI"); 
			$NIK_SUPIR[]   = OCI_RESULT($result_laporan_NAB, "NIK_SUPIR"); 
			$NAMA_SUPIR[]   = OCI_RESULT($result_laporan_NAB, "NAMA_SUPIR");
			$NIK_TUKANG_MUAT1[] 			= OCI_RESULT($result_laporan_NAB, "NIK_TUKANG_MUAT1");
			$NAMA_TM1[] 			= OCI_RESULT($result_laporan_NAB, "NAMA_TM1");
			$NIK_TUKANG_MUAT2[] 			= OCI_RESULT($result_laporan_NAB, "NIK_TUKANG_MUAT2");
			$NAMA_TM2[] 			= OCI_RESULT($result_laporan_NAB, "NAMA_TM2");
			$NIK_TUKANG_MUAT3[] 			= OCI_RESULT($result_laporan_NAB, "NIK_TUKANG_MUAT3");
			$NAMA_TM3[] 			= OCI_RESULT($result_laporan_NAB, "NAMA_TM3");
			$NAMA_KERANI_BUAH[]   = OCI_RESULT($result_laporan_NAB, "NAMA_KERANI_BUAH");
			$NIK_KERANI_BUAH[] 			= OCI_RESULT($result_laporan_NAB, "NIK_KERANI_BUAH");
			$NO_BCC[] 			= OCI_RESULT($result_laporan_NAB, "NO_BCC");
			$ESTIMASI_BERAT[] 			= OCI_RESULT($result_laporan_NAB, "ESTIMASI_BERAT");
			$TBS[] = OCI_RESULT($result_laporan_NAB, "TBS");
			$BRD[] = OCI_RESULT($result_laporan_NAB, "BRD");
			$NO_REKAP_BCC[] = OCI_RESULT($result_laporan_NAB, "NO_REKAP_BCC");
		}
		$rowNAB = oci_num_rows($result_laporan_NAB);
		
		
		if($rowNAB>0)	
		{
			$totalpage = ceil($rowNAB/$pagesize);
			$setPage = $totalpage - 1;
			//echo "totalpage".$totalpage;	
		}
		else{
			$totalpage = 0;
			$rowNAB  = "";
			//echo "rowNAB".$rowNAB;
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
	}
	
?>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/pa.js"></script>
<script type="text/javascript" src="datepicker/ui.core.js"></script>
<script type="text/javascript" src="datepicker/ui.datepicker.js"></script>
<link type="text/css" href="datepicker/ui.core.css" rel="stylesheet" />
<link type="text/css" href="datepicker/ui.resizable.css" rel="stylesheet" />
<link type="text/css" href="datepicker/ui.accordion.css" rel="stylesheet" />
<link type="text/css" href="datepicker/ui.dialog.css" rel="stylesheet" />
<link type="text/css" href="datepicker/ui.slider.css" rel="stylesheet" />
<link type="text/css" href="datepicker/ui.tabs.css" rel="stylesheet" />
<link type="text/css" href="datepicker/ui.datepicker.css" rel="stylesheet" />
<link type="text/css" href="datepicker/ui.progressbar.css" rel="stylesheet" />
<link type="text/css" href="datepicker/ui.theme.css" rel="stylesheet" />
<link type="text/css" href="datepicker/demos.css" rel="stylesheet" />

<script type="text/javascript">
$(function() {
	$('#datepicker2').datepicker({
		  changeMonth: true,
		  changeYear: true
		});
});
</script>

<?php
}
else{
	$_SESSION[err] = "tolong login dulu!";
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
        <td width="431" align="right"><a href="printXLS.php"><input type="submit" name="button" id="button" value="DOWNLOAD TO XLS" style="width:200px; height: 30px; font-size:16px; visibility:<?=$visisub?>" onclick="formSubmit(1)"/></a></td>
      </tr>
      <tr>
        <td width="130" height="29" valign="top">Company Code</td>
		<td width="7" height="29" valign="top">:</td>
		<td width="355" align="left" valign="top"><input name="company_code" type="text" id="company_code" value="<?=$company_code?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly="readonly" />
		</td>
      </tr>
      <tr>
        <td width="130" height="29" valign="top">Business Area</td>
		<td width="7" height="29" valign="top">:</td>
		<td width="355" align="left" valign="top"><input name="business_area" type="text" id="business_area" value="<?=$business_area?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly="readonly" />
        </td>
      </tr>
      <tr>
        <td width="130" height="29" valign="top">Company Name</td>
		<td width="7" height="29" valign="top">:</td>
		<td width="355" align="left" valign="top"><input name="company_name" type="text" id="company_name" value="<?=$company_name?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly="readonly" />
        </td>
      </tr>
      <tr>
  <?php
  
if($rowNAB > 0){

echo "
<table width=\"1150\" border=\"1\" bordercolor=\"#9CC346\">
  <tr bgcolor=\"#9CC346\">
    <td width=\"75\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Tgl</td>
    <td width=\"50\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">No.NAB</td>
    <td width=\"37\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Afdeling</td>
    <td width=\"60\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NoPol</td>
    <td colspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Supir</td>
    <td colspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Tukang Muat 1</td>
    <td colspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Tukang Muat 2</td>
    <td colspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Tukang Muat 3</td>
	<td colspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Kerani Buah</td>
    <td width=\"100\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">No Bcc</td>
    <td width=\"100\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">TBS (JJG)</td>
    <td width=\"50\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BRD (KG)</td>
    <td width=\"50\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Estimasi Berat</td>
  </tr>
  <tr bgcolor=\"#9CC346\">
    <td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NIK</td>
    <td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\"><p>Nama</p></td>
    <td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NIK</td>
    <td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\"><p>Nama</p></td>
    <td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NIK</td>
    <td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\"><p>Nama</p></td>
    <td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NIK</td>
    <td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\"><p>Nama</p></td>
    <td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NIK</td>
    <td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Nama</td>
  </tr>
";	
	
	
$endPage = $calPage + $pagesize;
for($xJAN = $calPage; $xJAN <  $rowNAB && $xJAN <$endPage; $xJAN++){
	$no = $xJAN +1;
	
	if(($xJAN % 2) == 0){
		$bg = "#F0F3EC";
	}
	else{
		$bg = "#DEE7D2";
	}
	
	$fixedBCC = separator($NO_BCC[$xJAN]);
	
echo "<tr style=\"font-size:12px\" bgcolor=$bg>";
echo "	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$TGL_PANEN[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$NO_NAB[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$ID_AFD[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$NOPOL[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$NIK_SUPIR[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$NAMA_SUPIR[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$NIK_TUKANG_MUAT1[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$NAMA_TM1[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$NIK_TUKANG_MUAT2[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$NAMA_TM2[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$NIK_TUKANG_MUAT3[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$NAMA_TM3[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$NIK_KERANI_BUAH[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$NAMA_KERANI_BUAH[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$fixedBCC</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$TBS[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$BRD[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$ESTIMASI_BERAT[$xJAN]</td>
	";

}
echo "</tr></table>";
}
?>
        </tr>
      <tr>
        <td colspan="3" align="right">
        
		<table width="400" border="0">
		  <tr>
			<td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="laporanNAB.php?page=first">
				<input type="button" name="button6" id="button6" value="&lt;&lt; First" style="width:70px; background-color:#9CC346"/>
			</a></td>
			<td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="laporanNAB.php?page=back">
			  <input type="button" name="button5" id="button5" value="&lt; Back" style="width:70px; background-color:#9CC346"/>
			</a></td>
			<td width="100" align="center" valign="middle" style="background-color:#9CC346; font-size:12px"><span style="padding-top:0px">Page
			  <?=$sesPageres+1?>
			  of
			  <?=$totalpage?>
			</span></td>
			<td width="70" align="left" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="laporanNAB.php?page=next"></a><a href="laporanNAB.php?page=next">
			  <input type="button" name="button4" id="button4" value="Next &gt;" style="width:70px; background-color:#9CC346"/>
			</a></td>
			<td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="laporanNAB.php?page=last">
			<input type="button" name="button7" id="button7" value="Last &gt;&gt;" style="width:70px; background-color:#9CC346"/>
			</a></td>
		  </tr>
		</table>
        
        
        </td>
        </tr>
    </table></th>
  </tr>
  <tr>
    <th align="center"><?php include("../include/Footer.php") ?></th>
  </tr>
</table>

