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
		
		$rbtn_type = $_SESSION['rbtn_type'];
		
		$sql_user_login  = 	"select c.id_cc as COMPANY_CODE, c.id_ba as BUSINESS_AREA, d.comp_name AS COMPANY_NAME
							from t_employee a, t_afdeling b, t_bussinessarea c, t_companycode d
							where a.id_ba_afd = b.id_ba_afd and b.id_ba = c.id_ba and c.id_cc = d.id_cc 
							and a.nik = '$NIK'";
		$result_user_login	= select_data($con,$sql_user_login);
		$company_code		= $result_user_login["COMPANY_CODE"];
		$business_area		= $result_user_login["BUSINESS_AREA"];
		$company_name		= $result_user_login["COMPANY_NAME"];
		
		
		$pagesize = 10;	
		$sql_Laporan_BCP = $_SESSION['sql_Laporan_BCP'];
		$result_laporan_BCP = oci_parse($con, $sql_Laporan_BCP);
		oci_execute($result_laporan_BCP, OCI_DEFAULT);
		if($rbtn_type == "Detail"){
			while(oci_fetch($result_laporan_BCP))
			{
				$TGL_PANEN[] 			= OCI_RESULT($result_laporan_BCP, "TGL_PANEN");
				$ID_CC[] 			= OCI_RESULT($result_laporan_BCP, "ID_CC");
				$ID_BA[] 		= OCI_RESULT($result_laporan_BCP, "ID_BA");
				$ID_AFD[]   = OCI_RESULT($result_laporan_BCP, "ID_AFD");
				$NAMA_KERANI_BUAH[]   = OCI_RESULT($result_laporan_BCP, "NAMA_KERANI_BUAH");
				$NIK_KERANI_BUAH[] 			= OCI_RESULT($result_laporan_BCP, "NIK_KERANI_BUAH");
				$ID_BLOK[] 			= OCI_RESULT($result_laporan_BCP, "ID_BLOK");
				$BLOK_NAME[] 		= OCI_RESULT($result_laporan_BCP, "BLOK_NAME");
				$NAMA_PEMANEN[]   = OCI_RESULT($result_laporan_BCP, "NAMA_PEMANEN");
				$NIK_PEMANEN[] 			= OCI_RESULT($result_laporan_BCP, "NIK_PEMANEN");
				$NO_BCC[] 			= OCI_RESULT($result_laporan_BCP, "NO_BCC");
				$TBS[]   = OCI_RESULT($result_laporan_BCP, "TBS"); 
				$BRD[]   = OCI_RESULT($result_laporan_BCP, "BRD");
				$NO_NAB[]   = OCI_RESULT($result_laporan_BCP, "NO_NAB"); 
			}
		}else{
			while(oci_fetch($result_laporan_BCP))
			{	$TGL_PANEN[] 			= OCI_RESULT($result_laporan_BCP, "TGL_PANEN");
				$ID_CC[] 			= OCI_RESULT($result_laporan_BCP, "ID_CC");
				$ID_BA[] 		= OCI_RESULT($result_laporan_BCP, "ID_BA");
				$ID_AFD[]   = OCI_RESULT($result_laporan_BCP, "ID_AFD");
				$NAMA_KERANI_BUAH[]   = OCI_RESULT($result_laporan_BCP, "NAMA_KERANI_BUAH");
				$NIK_KERANI_BUAH[] 			= OCI_RESULT($result_laporan_BCP, "NIK_KERANI_BUAH");
				$ID_BLOK[] 			= OCI_RESULT($result_laporan_BCP, "ID_BLOK");
				$BLOK_NAME[] 		= OCI_RESULT($result_laporan_BCP, "BLOK_NAME");
				$JML_BCC[] 			= OCI_RESULT($result_laporan_BCP, "JML_BCC");
				$TBS[]   = OCI_RESULT($result_laporan_BCP, "TBS"); 
				$BRD[]   = OCI_RESULT($result_laporan_BCP, "BRD");
			}
		}
		$rowBCP = oci_num_rows($result_laporan_BCP);
		
		if($rowBCP>0)	
		{
			$totalpage = ceil($rowBCP/$pagesize);
			$setPage = $totalpage - 1;
			//echo "totalpage".$totalpage;	
		}
		else{
			$totalpage = 0;
			$rowBCP  = "";
			//echo "rowBCP".$rowBCP;
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
        <td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>LAPORAN BCP</strong></span></td>
        <td width="619" align="right">
			<a href="printXLS.php">
			<input type="submit" name="button" id="button" value="DOWNLOAD TO XLS" style="width:200px; height: 30px; font-size:16px; visibility:<?=$visisub?>" onclick="formSubmit(1)"/>
			</a>
		</td>
      </tr>
      <tr>
        <td width="130" height="29" valign="top" >Company Code</td>
		<td width="7" height="29" valign="top" >:</td>
		<td width="355" align="left" valign="top" ><input name="company_code" type="text" id="company_code" value="<?=$company_code?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly="readonly" />
        </td>
      </tr>
      <tr>
        <td width="130" height="29" valign="top" >Business Area</td>
		<td width="7" height="29" valign="top" >:</td>
		<td width="355" align="left" valign="top" ><input name="business_area" type="text" id="business_area" value="<?=$business_area?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly="readonly" />
        </td>
      </tr>
      <tr>
        <td width="130" height="29" valign="top" >Company Name</td>
		<td width="7" height="29" valign="top" >:</td>
		<td width="355" align="left" valign="top" ><input name="company_name" type="text" id="company_name" value="<?=$company_name?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly="readonly" />
        </td>
      </tr>
      <tr>
          <?php
if($rowBCP > 0){
	if($rbtn_type == "Detail"){
		echo "
		<table width=\"1150\" border=\"1\" bordercolor=\"#9CC346\">
				  <tr bgcolor=\"#9CC346\">
					<td width=\"70\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Tgl</td>
					<td width=\"30\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Afd</td>
					<td width=\"100\" colspan=\"2\" align=\"center\" style=\"font-size:14px; \" id=\"bordertable\">Krani Buah</td>
					<td colspan=\"2\" align=\"center\" style=\"font-size:14px; \" id=\"bordertable\">Blok</td>
					<td colspan=\"2\" align=\"center\" style=\"font-size:14px; \" id=\"bordertable\">Pemanen</td>
					<td width=\"30\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">No BCC</td>
					<td width=\"35\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Jml Janjang Panen</td>
					<td width=\"25\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Jml Brondolan</td>
					<td width=\"25\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">No NAB</td>
				  </tr>
				  <tr bgcolor=\"#9CC346\">
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Nama</td>
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NIK</td>
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Kode Blok</td>
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Nama Blok</td>
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Nama</td>
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NIK</td>
				  </tr>";
				  
		$endPage = $calPage + $pagesize;
		for($xJAN = $calPage; $xJAN <  $rowBCP && $xJAN <$endPage; $xJAN++){
			$no = $xJAN +1;
			
			/*if(($xJAN % 2) == 0){
				$bg = "#F0F3EC";
			}
			else{
				$bg = "#DEE7D2";
			}
			
			$fixedBCC = separator($NO_BCC[$xJAN]);
			if($xJAN == 0)
			{
				$HA = number_format((float)$LUASAN_PANEN[$xJAN], 2, '.', '');
			}
			else
			{
				if($NO_REKAP_BCC[$xJAN] !== $NO_REKAP_BCC[$xJAN-1])
				{
					$HA = number_format((float)$LUASAN_PANEN[$xJAN], 2, '.', '');
				}
				else
				{
					$HA = '-';
				}
			}*/
			
			echo "<tr style=\"font-size:12px\" bgcolor=$bg>";
			echo "<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$TGL_PANEN[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$ID_AFD[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NAMA_KERANI_BUAH[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NIK_KERANI_BUAH[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$ID_BLOK[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BLOK_NAME[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NAMA_PEMANEN[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NIK_PEMANEN[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NO_BCC[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$TBS[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BRD[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NO_NAB[$xJAN]</TD>";

		}
		echo "</tr></table>";
	}else{
		echo "
		<table width=\"1150\" border=\"1\" bordercolor=\"#9CC346\">
				  <tr bgcolor=\"#9CC346\">
					<td width=\"70\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Tgl</td>
					<td width=\"30\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Afd</td>
					<td colspan=\"2\" align=\"center\" style=\"font-size:14px; \" id=\"bordertable\">Krani Buah</td>
					<td colspan=\"2\" align=\"center\" style=\"font-size:14px; \" id=\"bordertable\">Blok</td>
					<td width=\"30\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Jml BCC</td>
					<td width=\"35\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Jml Janjang Panen</td>
					<td width=\"125\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Jml Brondolan</td>
				  </tr>
				  <tr bgcolor=\"#9CC346\">
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Nama</td>
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NIK</td>
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Kode Blok</td>
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Nama Blok</td>
				  </tr>";
				  
		$endPage = $calPage + $pagesize;
		for($xJAN = $calPage; $xJAN <  $rowBCP && $xJAN <$endPage; $xJAN++){
			$no = $xJAN +1;
			
			/*if(($xJAN % 2) == 0){
				$bg = "#F0F3EC";
			}
			else{
				$bg = "#DEE7D2";
			}
			
			$fixedBCC = separator($NO_BCC[$xJAN]);
			if($xJAN == 0)
			{
				$HA = number_format((float)$LUASAN_PANEN[$xJAN], 2, '.', '');
			}
			else
			{
				if($NO_REKAP_BCC[$xJAN] !== $NO_REKAP_BCC[$xJAN-1])
				{
					$HA = number_format((float)$LUASAN_PANEN[$xJAN], 2, '.', '');
				}
				else
				{
					$HA = '-';
				}
			}*/

			echo "<tr style=\"font-size:12px\" bgcolor=$bg>";
			echo "<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$TGL_PANEN[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$ID_AFD[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NAMA_KERANI_BUAH[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NIK_KERANI_BUAH[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$ID_BLOK[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BLOK_NAME[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$JML_BCC[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$TBS[$xJAN]</TD>
			<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BRD[$xJAN]</TD>";

		}
		echo "</tr></table>";
	}
}
	
?>
        </tr>
      <tr>
        <td colspan="3" align="right">
          <table width="400" border="0">
            <tr>
			  <td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="laporanBCP.php?page=first">
                <input type="button" name="button6" id="button6" value="&lt;&lt; First" style="width:70px; background-color:#9CC346"/>
              </a></td>
              <td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="laporanBCP.php?page=back">
                <input type="button" name="button5" id="button5" value="&lt; Back" style="width:70px; background-color:#9CC346"/>
              </a></td>
              <td width="100" align="center" valign="middle" style="background-color:#9CC346; font-size:12px"><span style="padding-top:0px">Page
                <?=$sesPageres+1?>
                of
                <?=$totalpage?>
              </span></td>
              <td width="70" align="left" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="laporanBCP.php?page=next"></a><a href="laporanBCP.php?page=next">
                <input type="button" name="button4" id="button4" value="Next &gt;" style="width:70px; background-color:#9CC346"/>
              </a></td>
			  <td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="laporanBCP.php?page=last">
                <input type="button" name="button7" id="button7" value="Last &gt;&gt;" style="width:70px; background-color:#9CC346"/>
              </a></td>
            </tr>
          </table>
		  </td>
      </tr>
      <tr>        
		<th align="center"><?php
			if(isset($_SESSION['err'])){
				$err = $_SESSION['err'];
				if($err!=NULL)
				{
					echo $err;
					unset($_SESSION['err']);
				}
			}
			?>
		</th>
      </tr>
    </table></th>
  </tr>
  <tr>
    <th align="center"><?php include("../include/Footer.php") ?></th>
  </tr>
</table>

