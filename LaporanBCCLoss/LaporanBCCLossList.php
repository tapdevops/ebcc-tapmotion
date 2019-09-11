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
$ID_Group_BA = $_SESSION['ID_Group_BA']; 
$lap = $_SESSION['lap'];
$jenis_lap = $_SESSION['jenis_lap'];

	if($username == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:../index.php");
	}
	else{
	
		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		
		$sql_user_login  = 	"select c.id_cc as COMPANY_CODE, c.id_ba as BUSINESS_AREA, d.comp_name AS COMPANY_NAME
							from t_employee a, t_afdeling b, t_bussinessarea c, t_companycode d
							where a.id_ba_afd = b.id_ba_afd and b.id_ba = c.id_ba and c.id_cc = d.id_cc 
							and a.nik = '$username'";
		$result_user_login	= select_data($con,$sql_user_login);
		$company_code		= $result_user_login["COMPANY_CODE"];
		$business_area		= $result_user_login["BUSINESS_AREA"];
		$company_name		= $result_user_login["COMPANY_NAME"];
			
			
		if(isset($_SESSION["sql_t_BCCLoss"]))	{
			$pagesize = 10;	
			
			$sql_t_BCCLoss = $_SESSION["sql_t_BCCLoss"];
			$result_t_BCC_Loss = oci_parse($con, $sql_t_BCCLoss);
			oci_execute($result_t_BCC_Loss, OCI_DEFAULT);
			if($lap == 'loss'){
				if($jenis_lap == 'rekap'){
					while(oci_fetch($result_t_BCC_Loss)){
						$TANGGAL_RENCANA[] 	= oci_result($result_t_BCC_Loss, "TANGGAL_RENCANA");
						$TGL_DOC[] 			= oci_result($result_t_BCC_Loss, "TGL_DOC");
						$NO_DOC[] 			= oci_result($result_t_BCC_Loss, "NO_DOC");
						$CREATED_DATE[] 	= oci_result($result_t_BCC_Loss, "CREATED_DATE");
						$ID_BA[] 			= oci_result($result_t_BCC_Loss, "ID_BA");
						$ID_AFD[] 			= oci_result($result_t_BCC_Loss, "ID_AFD");
						$JML_NO_BCC[] 		= oci_result($result_t_BCC_Loss, "JML_NO_BCC");
						$JML_JJG[] 			= oci_result($result_t_BCC_Loss, "JML_JJG");
						$JML_BRD[] 			= oci_result($result_t_BCC_Loss, "JML_BRD");
						$ESTIMASI_BERAT[] 	= oci_result($result_t_BCC_Loss, "ESTIMASI_BERAT");
					}
				}else{
					while(oci_fetch($result_t_BCC_Loss)){
						$TANGGAL_RENCANA[] 	= oci_result($result_t_BCC_Loss, "TANGGAL_RENCANA");
						$TGL_DOC[] 			= oci_result($result_t_BCC_Loss, "TGL_DOC");
						$CREATED_DATE[] 	= oci_result($result_t_BCC_Loss, "CREATED_DATE");
						$NO_DOC[] 			= oci_result($result_t_BCC_Loss, "NO_DOC");
						$ID_BA[] 			= oci_result($result_t_BCC_Loss, "ID_BA");
						$ID_AFD[] 			= oci_result($result_t_BCC_Loss, "ID_AFD");
						$ID_BLOK[] 			= oci_result($result_t_BCC_Loss, "ID_BLOK");
						$BLOK_NAME[]		= oci_result($result_t_BCC_Loss, "BLOK_NAME");
						$BJR[] 				= oci_result($result_t_BCC_Loss, "BJR");
						$NO_BCC[] 			= oci_result($result_t_BCC_Loss, "NO_BCC");
						$JJG[] 				= oci_result($result_t_BCC_Loss, "TBS");
						$BRD[]	 			= oci_result($result_t_BCC_Loss, "BRD");
						$ESTIMASI_BERAT[] 	= oci_result($result_t_BCC_Loss, "ESTIMASI_BERAT");
						$ALASAN[] 			= oci_result($result_t_BCC_Loss, "REMARK");
					}
				}
			}else if($lap == 'wo'){
				if($jenis_lap == 'rekap'){
					while(oci_fetch($result_t_BCC_Loss)){
						$TANGGAL_RENCANA[] 	= oci_result($result_t_BCC_Loss, "TANGGAL_RENCANA");
						$PERIODE_WO[] 		= oci_result($result_t_BCC_Loss, "PERIODE_WO");
						$ID_BA[] 			= oci_result($result_t_BCC_Loss, "ID_BA");
						$ID_AFD[] 			= oci_result($result_t_BCC_Loss, "ID_AFD");
						$JML_NO_BCC[] 		= oci_result($result_t_BCC_Loss, "JML_NO_BCC");
						$JML_JJG[] 			= oci_result($result_t_BCC_Loss, "JML_JJG");
						$JML_BRD[] 			= oci_result($result_t_BCC_Loss, "JML_BRD");
						$ESTIMASI_BERAT[] 	= oci_result($result_t_BCC_Loss, "ESTIMASI_BERAT");
					}
				}else{
					while(oci_fetch($result_t_BCC_Loss)){
						$TANGGAL_RENCANA[] 	= oci_result($result_t_BCC_Loss, "TANGGAL_RENCANA");
						$PERIODE_WO[] 		= oci_result($result_t_BCC_Loss, "PERIODE_WO");
						$ID_BA[] 			= oci_result($result_t_BCC_Loss, "ID_BA");
						$ID_AFD[] 			= oci_result($result_t_BCC_Loss, "ID_AFD");
						$ID_BLOK[] 			= oci_result($result_t_BCC_Loss, "ID_BLOK");
						$BLOK_NAME[]		= oci_result($result_t_BCC_Loss, "BLOK_NAME");
						$BJR[] 				= oci_result($result_t_BCC_Loss, "BJR");
						$NO_BCC[] 			= oci_result($result_t_BCC_Loss, "NO_BCC");
						$JJG[] 				= oci_result($result_t_BCC_Loss, "TBS");
						$BRD[]	 			= oci_result($result_t_BCC_Loss, "BRD");
						$ESTIMASI_BERAT[] 	= oci_result($result_t_BCC_Loss, "ESTIMASI_BERAT");
					}
				}
			} else {
				//Added by Ardo 19-08-2016 : Synchronize BCC - LaporanBCCLoss
				while(oci_fetch($result_t_BCC_Loss)){
					$TANGGAL_PANEN[] 	= oci_result($result_t_BCC_Loss, "TGL_PANEN");
					$TANGGAL_DELETE[] 	= oci_result($result_t_BCC_Loss, "TANGGAL_DELETE");
					$TANGGAL_DOCUMENT[] = oci_result($result_t_BCC_Loss, "TANGGAL_BA");
					$NO_BA[] 			= oci_result($result_t_BCC_Loss, "NOMOR_BA");
					$ID_BA[] 			= oci_result($result_t_BCC_Loss, "ID_BA");
					$ID_AFD[] 			= oci_result($result_t_BCC_Loss, "ID_AFD");
					$NO_BCC[] 			= oci_result($result_t_BCC_Loss, "NO_BCC");
					$NO_REKAP_BCC[] 	= oci_result($result_t_BCC_Loss, "NO_REKAP_BCC");
					$ALASAN[] 			= oci_result($result_t_BCC_Loss, "ALASAN");
					
					//JML JJG PANEN
					$get_jjg = "SELECT SUM (THK.QTY) AS JML_JJG 
           FROM DEL_T_HASIL_PANEN THP
                INNER JOIN DEL_T_HASILPANEN_KUALTAS THK
                   ON THP.NO_BCC = THK.ID_BCC
                INNER JOIN T_KUALITAS_PANEN TKP
                   ON THK.ID_KUALITAS = TKP.ID_KUALITAS   
                INNER JOIN T_PARAMETER_BUNCH PAR
                   ON THK.ID_KUALITAS = PAR.ID_KUALITAS
                   AND DELETE_USER IS NULL
         WHERE     THP.NO_REKAP_BCC = '".oci_result($result_t_BCC_Loss, "NO_REKAP_BCC")."'
                AND THP.NO_BCC = '".oci_result($result_t_BCC_Loss, "NO_BCC")."'
                AND PAR.BA_CODE = '".oci_result($result_t_BCC_Loss, "ID_BA")."'
                AND PAR.KETERANGAN = 'BUNCH_HARVEST'
                AND TKP.ACTIVE_STATUS = 'YES'";
					$r_jjg = oci_parse($con, $get_jjg);
					oci_execute($r_jjg, OCI_DEFAULT);
					oci_fetch($r_jjg);
					$JML_JJG[] = oci_result($r_jjg, "JML_JJG");
					
					//JML JJG BRD
					$get_brd = "SELECT SUM (THK.QTY) JML_BRD
           FROM DEL_T_HASIL_PANEN THP
                INNER JOIN DEL_T_HASILPANEN_KUALTAS THK
                   ON THP.NO_BCC = THK.ID_BCC
                   AND THP.ID_RENCANA = THK.ID_RENCANA
                INNER JOIN T_KUALITAS_PANEN TKP
                   ON THK.ID_KUALITAS = TKP.ID_KUALITAS
          WHERE     THP.NO_REKAP_BCC = '".oci_result($result_t_BCC_Loss, "NO_REKAP_BCC")."'
                AND THP.NO_BCC = '".oci_result($result_t_BCC_Loss, "NO_BCC")."'
                AND THP.ID_RENCANA = '".oci_result($result_t_BCC_Loss, "ID_RENCANA")."'
                AND UPPER (TKP.GROUP_KUALITAS) = 'HASIL PANEN'
                AND UPPER (TKP.UOM) =  'KG'
                AND TKP.ACTIVE_STATUS = 'YES'";
				
					$r_brd = oci_parse($con, $get_brd);
					oci_execute($r_brd, OCI_DEFAULT);
					oci_fetch($r_brd);
					$JML_BRD[] = oci_result($r_brd, "JML_BRD");
				}
			}
			$roweffec_BCC_Loss = oci_num_rows($result_t_BCC_Loss);
			
			if($roweffec_BCC_Loss>0)	
			{
				$totalpage = ceil($roweffec_BCC_Loss/$pagesize);
				$setPage = $totalpage - 1;
				//echo "totalpage".$totalpage;	
			}
			else{
				$totalpage = 0;
				$roweffec_BCC_Loss  = "";
				//echo "roweffec_BCC".$roweffec_BCC;
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
		else{
			$_SESSION[err] = "Please check input value!";
			header("location:LaporanBCCLoss.php");
		}
	}
	
?>

<?php
}
else{
	$_SESSION[err] = "Please Login!";
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
		<td width="619" align="right">
			<a href="printXLS.php">
			<input type="submit" name="button" id="button" value="DOWNLOAD TO XLS" style="width:200px; height: 30px; font-size:16px; visibility:<?=$visisub?>" onclick="formSubmit(1)"/>
			</a>
		</td>
	  </tr>
  <tr>
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
        <td colspan="4" valign="top"><table width="1200" border="0">

          <tr bgcolor="#9CC346">
		  <?php 
			if($lap == 'loss'){
				if($jenis_lap == 'rekap'){ ?>
					<td width="44" align="center" style="font-size:14px" id="bordertable">No.</td>
					<td width="196" align="center" style="font-size:14px" id="bordertable">Tgl Panen</td>
					<td width="196" align="center" style="font-size:14px" id="bordertable">Tgl Document</td>
					<td width="250" align="center" style="font-size:14px" id="bordertable">Tgl Input Loss</td>
					<td width="250" align="center" style="font-size:14px" id="bordertable">No Document</td>
					<td width="100" align="center" style="font-size:14px" id="bordertable">Business Area</td>
					<td width="100" align="center" style="font-size:14px" id="bordertable">Afd</td>
					<td width="100" align="center" style="font-size:14px" id="bordertable">Jml No BCC</td>
					<td width="250" align="center" style="font-size:14px" id="bordertable">Jml JJG Kirim</td>
					<td width="250" align="center" style="font-size:14px" id="bordertable">Jml BRD</td>
					<td width="250" align="center" style="font-size:14px" id="bordertable">Estimasi Berat</td>
				<?php
				}else{ //detail ?>
					<td width="44" align="center" style="font-size:14px" id="bordertable">No.</td>
					<td width="196" align="center" style="font-size:14px" id="bordertable">Tgl Panen</td>
					<td width="196" align="center" style="font-size:14px" id="bordertable">Tgl Document</td>
					<td width="250" align="center" style="font-size:14px" id="bordertable">Tgl Input Loss</td>
					<td width="250" align="center" style="font-size:14px" id="bordertable">No Document</td>
					<td width="100" align="center" style="font-size:14px" id="bordertable">Business Area</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">Afd</td>
					<td width="100" align="center" style="font-size:14px" id="bordertable">Kode Blok</td>
					<td width="100" align="center" style="font-size:14px" id="bordertable">Blok Desc</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">BJR</td>
					<td width="100" align="center" style="font-size:14px" id="bordertable">No BCC</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">JJG Kirim</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">BRD</td>
					<td width="200" align="center" style="font-size:14px" id="bordertable">Estimasi Berat</td>
					<td width="350" align="center" style="font-size:14px" id="bordertable">Alasan</td>
				<?php
				} 
			}else if($lap == 'wo'){ //wo
				if($jenis_lap == 'rekap'){ ?>
					<td width="44" align="center" style="font-size:14px" id="bordertable">No.</td>
					<td width="196" align="center" style="font-size:14px" id="bordertable">Periode Write Off</td>
					<td width="196" align="center" style="font-size:14px" id="bordertable">Tgl Panen</td>
					<td width="100" align="center" style="font-size:14px" id="bordertable">Business Area</td>
					<td width="100" align="center" style="font-size:14px" id="bordertable">Afd</td>
					<td width="100" align="center" style="font-size:14px" id="bordertable">Jml No BCC</td>
					<td width="250" align="center" style="font-size:14px" id="bordertable">Jml JJG Kirim</td>
					<td width="250" align="center" style="font-size:14px" id="bordertable">Jml BRD</td>
					<td width="250" align="center" style="font-size:14px" id="bordertable">Estimasi Berat</td>
				<?php
				}else{ ?>
					<td width="44" align="center" style="font-size:14px" id="bordertable">No.</td>
					<td width="196" align="center" style="font-size:14px" id="bordertable">Periode Write Off</td>
					<td width="196" align="center" style="font-size:14px" id="bordertable">Tgl Panen</td>
					<td width="100" align="center" style="font-size:14px" id="bordertable">Business Area</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">Afd</td>
					<td width="100" align="center" style="font-size:14px" id="bordertable">Kode Blok</td>
					<td width="100" align="center" style="font-size:14px" id="bordertable">Blok Desc</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">BJR</td>
					<td width="100" align="center" style="font-size:14px" id="bordertable">No BCC</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">JJG Kirim</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">BRD</td>
					<td width="200" align="center" style="font-size:14px" id="bordertable">Estimasi Berat</td>
					<?php
				}

			} else { //delete
				//Added by Ardo 19-08-2016 : Synchronize BCC - LaporanBCCLoss
				?>
					<td width="44" align="center" style="font-size:14px" id="bordertable">No.</td>
					<td width="196" align="center" style="font-size:14px" id="bordertable">Tgl Panen</td>
					<td width="196" align="center" style="font-size:14px" id="bordertable">Tgl Delete</td>
					<td width="196" align="center" style="font-size:14px" id="bordertable">Tgl Document</td>
					<td width="100" align="center" style="font-size:14px" id="bordertable">No BA</td>
					<td width="100" align="center" style="font-size:14px" id="bordertable">Bussiness Area</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">Afd</td>
					<td width="100" align="center" style="font-size:14px" id="bordertable">No BCC</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">JML JJG Panen</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">JML BRD</td>
					<td width="200" align="center" style="font-size:14px" id="bordertable">Alasan</td>
				<?php
				
			}
			?>
          </tr>
          <?php
$endPage = $calPage + $pagesize;
for($xJAN = $calPage; $xJAN <  $roweffec_BCC_Loss && $xJAN <$endPage; $xJAN++){
	$no = $xJAN +1;
	
	if(($xJAN % 2) == 0){
		$bg = "#F0F3EC";
	}
	else{
		$bg = "#DEE7D2";
	}
	
	$fixedBCC = separator($NO_BCC[$xJAN]);
	if($lap == 'loss'){
		if($jenis_lap == 'rekap'){
			echo "<tr style=\"font-size:12px\" bgcolor=$bg >";
			echo "<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$no</td>
            <td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$TANGGAL_RENCANA[$xJAN]</td>
            <td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$TGL_DOC[$xJAN]</td>
            <td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$CREATED_DATE[$xJAN]</td>
            <td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$NO_DOC[$xJAN]</td>
            <td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$ID_BA[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$ID_AFD[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$JML_NO_BCC[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$JML_JJG[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$JML_BRD[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$ESTIMASI_BERAT[$xJAN]</td>
			
			";
		}else{ //detail		
			echo "<tr style=\"font-size:12px\" bgcolor=$bg >";
			echo "<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$no</td>
            <td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$TANGGAL_RENCANA[$xJAN]</td>
            <td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$TGL_DOC[$xJAN]</td>
            <td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$CREATED_DATE[$xJAN]</td>
            <td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$NO_DOC[$xJAN]</td>
            <td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$ID_BA[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$ID_AFD[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$ID_BLOK[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$BLOK_NAME[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$BJR[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$NO_BCC[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$JJG[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$BRD[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$ESTIMASI_BERAT[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$ALASAN[$xJAN]</td>
			
			";
		}
	}else if($lap == 'wo'){ //wo
		if($jenis_lap == 'rekap'){			
			echo "<tr style=\"font-size:12px\" bgcolor=$bg >";
			echo "<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$no</td>
            <td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$PERIODE_WO[$xJAN]</td>
            <td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$TANGGAL_RENCANA[$xJAN]</td>
            <td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$ID_BA[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$ID_AFD[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$JML_NO_BCC[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$JML_JJG[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$JML_BRD[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$ESTIMASI_BERAT[$xJAN]</td>
			
			";
		}
		else{			
			echo "<tr style=\"font-size:12px\" bgcolor=$bg >";
			echo "<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$no</td>
            <td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$PERIODE_WO[$xJAN]</td>
            <td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$TANGGAL_RENCANA[$xJAN]</td>
            <td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$ID_BA[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$ID_AFD[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$ID_BLOK[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$BLOK_NAME[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$BJR[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$NO_BCC[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$JJG[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$BRD[$xJAN]</td>
			<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$ESTIMASI_BERAT[$xJAN]</td>
			
			";
		}
	} else {
		//Delete
		//Added by Ardo 19-08-2016 : Synchronize BCC - LaporanBCCLoss
		echo "<tr style=\"font-size:12px\" bgcolor=$bg >";
		echo "<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$no</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$TANGGAL_PANEN[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$TANGGAL_DELETE[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$TANGGAL_DOCUMENT[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$NO_BA[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$ID_BA[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$ID_AFD[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$NO_BCC[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$JML_JJG[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$JML_BRD[$xJAN]</td>
		<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$ALASAN[$xJAN]</td>
		
		";
	}

}
echo "</tr>";
?>

		</table></td>
      </tr>
	  
	  <tr>
        <td colspan="3" align="right">
		<table width="400" border="0">
		  <tr>
			<td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="LaporanBCCLossList.php?page=first">
				<input type="button" name="button6" id="button6" value="&lt;&lt; First" style="width:70px; background-color:#9CC346"/>
			</a></td>
			<td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="LaporanBCCLossList.php?page=back">
			  <input type="button" name="button5" id="button5" value="&lt; Back" style="width:70px; background-color:#9CC346"/>
			</a></td>
			<td width="100" align="center" valign="middle" style="background-color:#9CC346; font-size:12px"><span style="padding-top:0px">Page
			  <?=$sesPageres+1?>
			  of
			  <?=$totalpage?>
			</span></td>
			<td width="70" align="left" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="LaporanBCCLossList.php?page=next"></a><a href="LaporanBCCLossList.php?page=next">
			  <input type="button" name="button4" id="button4" value="Next &gt;" style="width:70px; background-color:#9CC346"/>
			</a></td>
			<td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="LaporanBCCLossList.php?page=last">
			<input type="button" name="button7" id="button7" value="Last &gt;&gt;" style="width:70px; background-color:#9CC346"/>
			</a></td>
		  </tr>
		</table>
        </td>
        </tr>
	  
      <tr>
        <td colspan="4"><?php
if(isset($_SESSION['err'])){
	$err = $_SESSION['err'];
	if($err!=NULL)
	{
		echo $err;
		unset($_SESSION['err']);
	}
}
?></td>
      </tr>
    </table></th>
  </tr>
  <tr>
    <th align="center"><?php include("../include/Footer.php") ?></th>
  </tr>
</table>

