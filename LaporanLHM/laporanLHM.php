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
		$rbtn_filter = $_SESSION['rbtn_filter'];
		if($rbtn_type == "Detail"){
			$rbtn_filter = "";
		}
		$sql_user_login  = 	"select c.id_cc as COMPANY_CODE, c.id_ba as BUSINESS_AREA, d.comp_name AS COMPANY_NAME
							from t_employee a, t_afdeling b, t_bussinessarea c, t_companycode d
							where a.id_ba_afd = b.id_ba_afd and b.id_ba = c.id_ba and c.id_cc = d.id_cc 
							and a.nik = '$NIK'";
		$result_user_login	= select_data($con,$sql_user_login);
		$company_code		= $result_user_login["COMPANY_CODE"];
		$business_area		= $result_user_login["BUSINESS_AREA"];
		$company_name		= $result_user_login["COMPANY_NAME"];
		
		
		$pagesize = 10;	
		$sql_laporan_LHM = $_SESSION['sql_Laporan_LHM'];
		$sql_laporan_LHM = str_replace('ORDER BY tgl_panen,
				id_afd,
				id_blok,
				nama_pemanen,
				luasan_panen desc', 'ORDER BY tgl_panen,
				id_afd,
				id_blok,
				nama_pemanen,
				no_tph,
				luasan_panen desc', $sql_laporan_LHM);
		//echo $sql_laporan_LHM;die();
		$result_laporan_LHM = oci_parse($con, $sql_laporan_LHM);
		oci_execute($result_laporan_LHM, OCI_DEFAULT);
		if($rbtn_type == "Detail"){
			while(oci_fetch($result_laporan_LHM))
			{
				$TGL_PANEN[] 			= OCI_RESULT($result_laporan_LHM, "TGL_PANEN");
				$NO_TPH[] 			= OCI_RESULT($result_laporan_LHM, "NO_TPH");
				$ID_AFD[] 		= OCI_RESULT($result_laporan_LHM, "ID_AFD");
				$NAMA_MANDOR[] 		= OCI_RESULT($result_laporan_LHM, "NAMA_MANDOR"); 
				$NIK_MANDOR[]   = OCI_RESULT($result_laporan_LHM, "NIK_MANDOR"); 
				$NAMA_PEMANEN[]   = OCI_RESULT($result_laporan_LHM, "NAMA_PEMANEN");
				$NIK_PEMANEN[] 			= OCI_RESULT($result_laporan_LHM, "NIK_PEMANEN");
				$NAMA_KERANI_BUAH[]   = OCI_RESULT($result_laporan_LHM, "NAMA_KERANI_BUAH");
				$NIK_KERANI_BUAH[] 			= OCI_RESULT($result_laporan_LHM, "NIK_KERANI_BUAH");
				$ID_BLOK[] 			= OCI_RESULT($result_laporan_LHM, "ID_BLOK");
				$BLOK_NAME[] 		= OCI_RESULT($result_laporan_LHM, "BLOK_NAME");
				$LUASAN_PANEN[] 			= OCI_RESULT($result_laporan_LHM, "LUASAN_PANEN");
				$NO_BCC[] 		= OCI_RESULT($result_laporan_LHM, "NO_BCC"); 
				$TBS[]   = OCI_RESULT($result_laporan_LHM, "TBS"); 
				$BRD[]   = OCI_RESULT($result_laporan_LHM, "BRD");
				
				$BM[] 			= OCI_RESULT($result_laporan_LHM, "BM");
				$BK[] 			= OCI_RESULT($result_laporan_LHM, "BK");
				$MS[] 		= OCI_RESULT($result_laporan_LHM, "MS");
				$OVR[] 		= OCI_RESULT($result_laporan_LHM, "OVR"); 
				$BB[] 		= OCI_RESULT($result_laporan_LHM, "BB"); 
				$JK[]   = OCI_RESULT($result_laporan_LHM, "JK"); 
				$BA[]   = OCI_RESULT($result_laporan_LHM, "BA"); 
				$TP[] 		= OCI_RESULT($result_laporan_LHM, "TP");
				$MH[]   = OCI_RESULT($result_laporan_LHM, "MH");
				$BT[]   = OCI_RESULT($result_laporan_LHM, "BT");
				$BL[] 			= OCI_RESULT($result_laporan_LHM, "BL");
				$PB[] 			= OCI_RESULT($result_laporan_LHM, "PB");
				$AB[] 		= OCI_RESULT($result_laporan_LHM, "AB");
				$SF[] 		= OCI_RESULT($result_laporan_LHM, "SF"); 
				$BS[]   = OCI_RESULT($result_laporan_LHM, "BS"); 
				$NO_REKAP_BCC[] = OCI_RESULT($result_laporan_LHM, "NO_REKAP_BCC");
				$PICTURE_NAME[] = OCI_RESULT($result_laporan_LHM, "PICTURE_NAME");
			}
		}else{
			if($rbtn_filter == "Pemanen"){
				while(oci_fetch($result_laporan_LHM)){
					$TGL_PANEN[] 			= OCI_RESULT($result_laporan_LHM, "TGL_PANEN");
					$NAMA_PEMANEN[]   = OCI_RESULT($result_laporan_LHM, "NAMA_PEMANEN");
					$NIK_PEMANEN[] 			= OCI_RESULT($result_laporan_LHM, "NIK_PEMANEN");
					$TOTAL_HA_PANEN[]	= OCI_RESULT($result_laporan_LHM, "TOTAL_HA_PANEN");
					$TBS[]   = OCI_RESULT($result_laporan_LHM, "TBS"); 
					$BRD[]   = OCI_RESULT($result_laporan_LHM, "BRD");
					
					
					$BM[] 			= OCI_RESULT($result_laporan_LHM, "BM");
					$BK[] 			= OCI_RESULT($result_laporan_LHM, "BK");
					$MS[] 		= OCI_RESULT($result_laporan_LHM, "MS");
					$OVR[] 		= OCI_RESULT($result_laporan_LHM, "OVR"); 
					$BB[] 		= OCI_RESULT($result_laporan_LHM, "BB"); 
					$JK[]   = OCI_RESULT($result_laporan_LHM, "JK"); 
					$BA[]   = OCI_RESULT($result_laporan_LHM, "BA"); 
					$TP[] 		= OCI_RESULT($result_laporan_LHM, "TP");
					$MH[]   = OCI_RESULT($result_laporan_LHM, "MH");
					$BT[]   = OCI_RESULT($result_laporan_LHM, "BT");
					$BL[] 			= OCI_RESULT($result_laporan_LHM, "BL");
					$PB[] 			= OCI_RESULT($result_laporan_LHM, "PB");
					$AB[] 		= OCI_RESULT($result_laporan_LHM, "AB");
					$SF[] 		= OCI_RESULT($result_laporan_LHM, "SF"); 
					$BS[]   = OCI_RESULT($result_laporan_LHM, "BS"); 
					//$PICTURE_NAME[] = OCI_RESULT($result_laporan_LHM, "PICTURE_NAME");
				}
			}else{
				while(oci_fetch($result_laporan_LHM)){
					$TGL_PANEN[] 			= OCI_RESULT($result_laporan_LHM, "TGL_PANEN");
					$ID_BLOK[]   = OCI_RESULT($result_laporan_LHM, "ID_BLOK");
					$BLOK_NAME[] 			= OCI_RESULT($result_laporan_LHM, "BLOK_NAME");
					$HA_BLOK[]	= OCI_RESULT($result_laporan_LHM, "HA_BLOK");
					$TOTAL_HA_PANEN[]	= OCI_RESULT($result_laporan_LHM, "LUASAN_PANEN");
					$TBS[]   = OCI_RESULT($result_laporan_LHM, "TBS"); 
					$BRD[]   = OCI_RESULT($result_laporan_LHM, "BRD");
					$BM[] 			= OCI_RESULT($result_laporan_LHM, "BM");
					$BK[] 			= OCI_RESULT($result_laporan_LHM, "BK");
					$MS[] 		= OCI_RESULT($result_laporan_LHM, "MS");
					$OVR[] 		= OCI_RESULT($result_laporan_LHM, "OVR"); 
					$BB[] 		= OCI_RESULT($result_laporan_LHM, "BB"); 
					$JK[]   = OCI_RESULT($result_laporan_LHM, "JK"); 
					$BA[]   = OCI_RESULT($result_laporan_LHM, "BA"); 
					$TP[] 		= OCI_RESULT($result_laporan_LHM, "TP");
					$MH[]   = OCI_RESULT($result_laporan_LHM, "MH");
					$BT[]   = OCI_RESULT($result_laporan_LHM, "BT");
					$BL[] 			= OCI_RESULT($result_laporan_LHM, "BL");
					$PB[] 			= OCI_RESULT($result_laporan_LHM, "PB");
					$AB[] 		= OCI_RESULT($result_laporan_LHM, "AB");
					$SF[] 		= OCI_RESULT($result_laporan_LHM, "SF"); 
					$BS[]   = OCI_RESULT($result_laporan_LHM, "BS"); 
					//$PICTURE_NAME[] = OCI_RESULT($result_laporan_LHM, "PICTURE_NAME");
				}
			}
		}
		$rowLHM = oci_num_rows($result_laporan_LHM);
		
		
		if($rowLHM>0)	
		{
			$totalpage = ceil($rowLHM/$pagesize);
			$setPage = $totalpage - 1;
			//echo "totalpage".$totalpage;	
		}
		else{
			$totalpage = 0;
			$rowLHM  = "";
			//echo "rowLHM".$rowLHM;
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
        <td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>LAPORAN HASIL PANEN</strong></span></td>
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
if($rowLHM > 0){
	if($rbtn_type == "Detail"){
		echo "
		<table width=\"1200\" border=\"1\" bordercolor=\"#9CC346\">
				  <tr bgcolor=\"#9CC346\">
					<td width=\"70\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">&nbsp;&nbsp;Tanggal&nbsp;&nbsp;</td>
					<td width=\"77\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">No TPH</td>
					<td width=\"30\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Afd</td>
					<td colspan=\"2\" align=\"center\" style=\"font-size:14px; \" id=\"bordertable\">Mandor</td>
					<td colspan=\"2\" align=\"center\" style=\"font-size:14px; \" id=\"bordertable\">Pemanen</td>
					<td colspan=\"2\" align=\"center\" style=\"font-size:14px; \" id=\"bordertable\">Krani Buah</td>
					<td width=\"30\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Kode Blok</td>
					<td width=\"35\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Desc Blok</td>
					<td width=\"30\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Luasan Panen</td>
					<td width=\"125\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">No Bcc</td>
					<td colspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Hasil Panen</td>
					<td colspan=\"7\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Kualitas Buah</td>
					<td colspan=\"8\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Kondisi Buah</td>
					<td rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Image</td>
				  </tr>
				  <tr bgcolor=\"#9CC346\">
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Nama</td>
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NIK</td>
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Nama</td>
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NIK</td>
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Nama</td>
					<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NIK</td>
					<td width=\"50\" align=\"center\" style=\"font-size:14x\" id=\"bordertable\">JJG Panen</td>
					<td width=\"40\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BRD</td>
					<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BM</td>
					<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BK</td>
					<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">MS</td>
					<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">OR</td>
					<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BB</td>
					<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">JK</td>
					<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BA</td>
					<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">TP</td>
					<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">MH</td>
					<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BT</td>
					<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BL</td>
					<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">PB</td>
					<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">AB</td>
					<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">SF</td>
					<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BS</td>
				  </tr>
		";
			  
		$endPage = $calPage + $pagesize;
		for($xJAN = $calPage; $xJAN <  $rowLHM && $xJAN <$endPage; $xJAN++){
			$no = $xJAN +1;
			
			if(($xJAN % 2) == 0){
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
				//Edit by Ardo, 22-09-2016 : CR Synchronize EBCC perubahan perhitungan Luasan Panen
				if($TGL_PANEN[$xJAN]!==$TGL_PANEN[$xJAN-1] || $ID_AFD[$xJAN]!==$ID_AFD[$xJAN-1] || $ID_BLOK[$xJAN]!==$ID_BLOK[$xJAN-1] || $NIK_PEMANEN[$xJAN]!==$NIK_PEMANEN[$xJAN-1])
				//if($NO_REKAP_BCC[$xJAN] !== $NO_REKAP_BCC[$xJAN-1] || ($NO_REKAP_BCC[$xJAN] == $NO_REKAP_BCC[$xJAN-1] && $NAMA_PEMANEN[$xJAN] !== $NAMA_PEMANEN[$xJAN-1]))
				{
					$HA = number_format((float)$LUASAN_PANEN[$xJAN], 2, '.', '');
				}
				else
				{
					$HA = '-';
				}
			}
		
		echo "<tr style=\"font-size:12px\" bgcolor=$bg>";
		echo "<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$TGL_PANEN[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NO_TPH[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$ID_AFD[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NAMA_MANDOR[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NIK_MANDOR[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NAMA_PEMANEN[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NIK_PEMANEN[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NAMA_KERANI_BUAH[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NIK_KERANI_BUAH[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$ID_BLOK[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BLOK_NAME[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$HA</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$fixedBCC</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$TBS[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BRD[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BM[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BK[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$MS[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$OVR[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BB[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$JK[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BA[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$TP[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$MH[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BT[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BL[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$PB[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$AB[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$SF[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BS[$xJAN]</TD>
		<TD style=\"font-size:12px\" id=\"bordertable\"><a target='_blank' href='../array/uploads/".$PICTURE_NAME[$xJAN]."'>download </a></TD>";

		}
		echo "</tr></table>";
	}else{
		if($rbtn_filter == "Pemanen"){
			echo "
			<table width=\"1150\" border=\"1\" bordercolor=\"#9CC346\">
					  <tr bgcolor=\"#9CC346\">
						<td width=\"70\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">&nbsp;&nbsp;Tanggal&nbsp;&nbsp;</td>
						<td colspan=\"2\" align=\"center\" style=\"font-size:14px; \" id=\"bordertable\">Pemanen</td>
						<td width=\"35\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Total HA Panen</td>
						<td colspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Hasil Panen</td>
						<td colspan=\"7\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Kualitas Buah</td>
						<td colspan=\"8\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Kondisi Buah</td>
					  </tr>
					  <tr bgcolor=\"#9CC346\">
						<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Nama</td>
						<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NIK</td>
						<td width=\"50\" align=\"center\" style=\"font-size:14x\" id=\"bordertable\">JJG Panen</td>
						<td width=\"40\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BRD</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BM</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BK</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">MS</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">OR</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BB</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">JK</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BA</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">TP</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">MH</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BT</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BL</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">PB</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">AB</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">SF</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BS</td>
					  </tr>
			";
					  
			$endPage = $calPage + $pagesize;
			for($xJAN = $calPage; $xJAN <  $rowLHM && $xJAN <$endPage; $xJAN++){
				$no = $xJAN +1;
				
				if(($xJAN % 2) == 0){
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
					//Edit by Ardo, 22-09-2016 : CR Synchronize EBCC perubahan perhitungan Luasan Panen
					if($TGL_PANEN[$xJAN]!==$TGL_PANEN[$xJAN-1] || $ID_AFD[$xJAN]!==$ID_AFD[$xJAN-1] || $ID_BLOK[$xJAN]!==$ID_BLOK[$xJAN-1] || $NIK_PEMANEN[$xJAN]!==$NIK_PEMANEN[$xJAN-1])
					//if($NO_REKAP_BCC[$xJAN] !== $NO_REKAP_BCC[$xJAN-1] || ($NO_REKAP_BCC[$xJAN] == $NO_REKAP_BCC[$xJAN-1] && $NAMA_PEMANEN[$xJAN] !== $NAMA_PEMANEN[$xJAN-1]))
					{
						$HA = number_format((float)$LUASAN_PANEN[$xJAN], 2, '.', '');
					}
					else
					{
						$HA = '-';
					}
				}
					
				echo "<tr style=\"font-size:12px\" bgcolor=$bg>";
				echo "<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$TGL_PANEN[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NAMA_PEMANEN[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$NIK_PEMANEN[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$TOTAL_HA_PANEN[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$TBS[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BRD[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BM[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BK[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$MS[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$OVR[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BB[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$JK[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BA[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$TP[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$MH[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BT[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BL[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$PB[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$AB[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$SF[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BS[$xJAN]</TD>";

			}
			echo "</tr></table>";
		}else{
			echo "
			<table width=\"1150\" border=\"1\" bordercolor=\"#9CC346\">
					  <tr bgcolor=\"#9CC346\">
						<td width=\"70\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">&nbsp;&nbsp;Tanggal&nbsp;&nbsp;</td>
						<td colspan=\"2\" align=\"center\" style=\"font-size:14px; \" id=\"bordertable\">BLOK</td>
						<td width=\"30\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">HA Block</td>
						<td width=\"35\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Total HA Panen</td>
						<td colspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Hasil Panen</td>
						<td colspan=\"7\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Kualitas Buah</td>
						<td colspan=\"8\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Kondisi Buah</td>
					  </tr>
					  <tr bgcolor=\"#9CC346\">
						<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Kode Blok</td>
						<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Deskripsi Blok</td>
						<td width=\"50\" align=\"center\" style=\"font-size:14x\" id=\"bordertable\">JJG Panen</td>
						<td width=\"40\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BRD</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BM</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BK</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">MS</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">OR</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BB</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">JK</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BA</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">TP</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">MH</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BT</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BL</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">PB</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">AB</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">SF</td>
						<td width=\"25\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BS</td>
					  </tr>
			";
					  
			$endPage = $calPage + $pagesize;
			for($xJAN = $calPage; $xJAN <  $rowLHM && $xJAN <$endPage; $xJAN++){
				$no = $xJAN +1;
				
				if(($xJAN % 2) == 0){
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
					//Edit by Ardo, 22-09-2016 : CR Synchronize EBCC perubahan perhitungan Luasan Panen
					if($TGL_PANEN[$xJAN]!==$TGL_PANEN[$xJAN-1] || $ID_AFD[$xJAN]!==$ID_AFD[$xJAN-1] || $ID_BLOK[$xJAN]!==$ID_BLOK[$xJAN-1] || $NIK_PEMANEN[$xJAN]!==$NIK_PEMANEN[$xJAN-1])
					//if($NO_REKAP_BCC[$xJAN] !== $NO_REKAP_BCC[$xJAN-1] || ($NO_REKAP_BCC[$xJAN] == $NO_REKAP_BCC[$xJAN-1] && $NAMA_PEMANEN[$xJAN] !== $NAMA_PEMANEN[$xJAN-1]))
					{
						$HA = number_format((float)$LUASAN_PANEN[$xJAN], 2, '.', '');
					}
					else
					{
						$HA = '-';
					}
				}
					
				echo "<tr style=\"font-size:12px\" bgcolor=$bg>";
				echo "<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$TGL_PANEN[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$ID_BLOK[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BLOK_NAME[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$HA_BLOK[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$TOTAL_HA_PANEN[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$TBS[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BRD[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BM[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BK[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$MS[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$OVR[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BB[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$JK[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BA[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$TP[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$MH[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BT[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BL[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$PB[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$AB[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$SF[$xJAN]</TD>
				<TD style=\"font-size:12px\" id=\"bordertable\">&nbsp;$BS[$xJAN]</TD>";

			}
			echo "</tr></table>";
		}
	}
}

?>
        </tr>
      <tr>
        <td colspan="3" align="right">
          <table width="400" border="0">
            <tr>
			  <td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="laporanLHM.php?page=first">
                <input type="button" name="button6" id="button6" value="&lt;&lt; First" style="width:70px; background-color:#9CC346"/>
              </a></td>
              <td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="laporanLHM.php?page=back">
                <input type="button" name="button5" id="button5" value="&lt; Back" style="width:70px; background-color:#9CC346"/>
              </a></td>
              <td width="100" align="center" valign="middle" style="background-color:#9CC346; font-size:12px"><span style="padding-top:0px">Page
                <?=$sesPageres+1?>
                of
                <?=$totalpage?>
              </span></td>
              <td width="70" align="left" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="laporanLHM.php?page=next"></a><a href="laporanLHM.php?page=next">
                <input type="button" name="button4" id="button4" value="Next &gt;" style="width:70px; background-color:#9CC346"/>
              </a></td>
			  <td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="laporanLHM.php?page=last">
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

