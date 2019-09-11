<?php
session_start();
include("../include/Header.php");
if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['Jenis_Login']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name']) && isset($_SESSION['subID_CC'])){	
$Job_Code = $_SESSION['Job_Code'];
$username = $_SESSION['NIK'];	
$Emp_Name = $_SESSION['Name'];
$Jenis_Login = $_SESSION['Jenis_Login'];
$Comp_Name = $_SESSION['Comp_Name'];
$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
$Date = $_SESSION['Date'];
$ID_Group_BA = $_SESSION['ID_Group_BA'];
$subID_CC = $_SESSION['subID_CC'];
$sComp_Name = $_SESSION['Comp_Name'];
	
	if($username == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:../index.php");
	}
	else{
		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		
		$LaporanProduksi = "";
		if(isset($_POST["LaporanProduksi"])){
			$LaporanProduksi = $_POST["LaporanProduksi"];
			$_SESSION["LaporanProduksi"] = $LaporanProduksi;
		}
		if(isset($_SESSION["LaporanProduksi"])){
			$LaporanProduksi = $_SESSION["LaporanProduksi"];
		}
		
		if($LaporanProduksi == TRUE){
		
			$optionGETBA = "<option value=\"$subID_BA_Afd\" selected=\"selected\">$subID_BA_Afd</option>";
			$optionGETAFD = "";
			$optionGETBLOK = "";
			$optionGETPemanen = "";
			$sID_BLOK = "";
			$sAFD = "";
			$roweffec_ACx = 0;
			$visisub = "visible";
			$disCC = "inline";
			$disBA = "inline";
			$disJA = "inline";
				
			$iudID_JOBAUTHORITY = "";
			$iudAUTHORITY = "";
			$iudvalue = "";
			
			if(isset($_SESSION["BA"]) == ""){
				$_SESSION["BA"] = $subID_BA_Afd;
			}
			
			if(isset($_SESSION["AFD"]) == ""){
				$_SESSION["AFD"] = 'ALL';
			}
			
			$sql_t_BA = "select ID_BA from t_bussinessarea WHERE ID_CC = '$subID_CC' ORDER BY ID_BA";
			$result_t_BA = oci_parse($con, $sql_t_BA);
			oci_execute($result_t_BA, OCI_DEFAULT);
			while (oci_fetch($result_t_BA)) {	
				$ID_BA[] = oci_result($result_t_BA, "ID_BA");
			}
			$roweffec_BA = oci_num_rows($result_t_BA);
			
			//echo "roweffec_BA".$roweffec_BA;
				
			//BA to AFD
			if(isset($_POST['BA'])){
				$_SESSION["BA"] = $_POST['BA'];
				//unset($_SESSION['AFD']);
				$_SESSION["AFD"] = 'ALL';
				unset($_SESSION['BLOK']);
				unset($_SESSION['NIK_Pemanen']);
			}
			
			if(isset($_SESSION['BA'])){
				$sID_BA = $_SESSION["BA"];
				$sql_t_AFD  = "select * from t_Afdeling tafd inner join t_bussinessarea tba on tafd.id_ba = tba.id_ba WHERE tba.id_ba = '$sID_BA'";
				$optionGETBA = "<option value=\"$sID_BA\" selected=\"selected\">$sID_BA</option>";
			}
			
			//echo "<br>sql_t_AFD".$sql_t_AFD ;
			$result_t_sAFD = oci_parse($con, $sql_t_AFD);
			oci_execute($result_t_sAFD, OCI_DEFAULT);
			while (oci_fetch($result_t_sAFD)) {	
				$ID_AFD[] 		= oci_result($result_t_sAFD, "ID_AFD");
			}
			//$_SESSION["ID_BA"] = $ID_BA[0]; 
			$roweffec_AFD = oci_num_rows($result_t_sAFD);
			//echo "<br>roweffec_AFD".$roweffec_AFD;
			
	
			//AFD to BLOK
			if(isset($_POST['AFD'])){
				$_SESSION["AFD"] = $_POST['AFD'];
				unset($_SESSION['BLOK']);
				unset($_SESSION['NIK_Pemanen']);
			}
			
			$roweffec_BLOK = 0;
			if(isset($_SESSION['AFD'])){
				$sAFD = $_SESSION["AFD"];
				$sql_t_BLOK  = "select ID_BLOK from t_blok tb 
				inner join t_afdeling ta on tb.id_ba_afd = ta.id_ba_afd 
				WHERE ta.id_afd = nvl (decode ('$sAFD', 'ALL', null, '$sAFD'), ta.id_afd)  and id_ba = '$sID_BA' order by tb.id_blok";
				$optionGETAFD = "<option value=\"$sAFD\" selected=\"selected\">$sAFD</option>";	
				$result_t_sBLOK = oci_parse($con, $sql_t_BLOK);
				oci_execute($result_t_sBLOK, OCI_DEFAULT);
				while (oci_fetch($result_t_sBLOK)) {	
					$ID_BLOK[] 		= oci_result($result_t_sBLOK, "ID_BLOK");
				}
				$roweffec_BLOK = oci_num_rows($result_t_sBLOK);	
			}
			
			//echo "sql_t_BLOK ".$sql_t_BLOK;
			
			
			//BLOK
			if(isset($_POST['BLOK'])){
				$_SESSION["BLOK"] = $_POST['BLOK'];
				unset($_SESSION['NIK_Pemanen']);
			}
			
			if(isset($_SESSION['BLOK'])){
				$sID_BLOK = $_SESSION['BLOK'];
				$optionGETBLOK = "<option value=\"$sID_BLOK\" selected=\"selected\">$sID_BLOK</option>";		
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
				if($sdate1 == "1970-01-01")
				{
					$sdate1 = "";
				}
			}
			
			if(isset($_SESSION['date2'])){
				$sdate2 = $_SESSION['date2'];
				if($sdate2 == "1970-01-01")
				{
					$sdate2 = "";
				}
			}
			
			$sql_t_Pemanen = "select NIK_Pemanen, f_get_empname(NIK_Pemanen) Nama_Pemanen, NIK_Mandor, f_get_empname(NIK_Mandor) Nama_Mandor 
			from t_header_rencana_panen thrp 
			inner join t_detail_rencana_panen tdrp on thrp.id_rencana = tdrp.id_rencana 
			inner join t_blok tb on tdrp.id_ba_afd_blok = tb.id_ba_afd_blok 
			inner join t_afdeling ta on tb.id_ba_afd = ta.id_ba_afd 
			WHERE ta.ID_BA = '$sID_BA' 
			and ta.id_afd = nvl (decode ('$sAFD', 'ALL', null, '$sAFD'), ta.id_afd) 
			and tb.id_blok = nvl (decode ('$sID_BLOK', 'ALL', null, '$sID_BLOK'), tb.id_blok)
			and to_char(thrp.tanggal_rencana,'YYYY-MM-DD') between '$sdate1' and nvl ('$sdate2', '$sdate1')
			group by NIK_PEMANEN, NIK_Mandor 
			order by Nama_Pemanen
			";
	
			//echo "sql_t_Pemanen ".$sql_t_Pemanen;
			$result_t_Pemanen = oci_parse($con, $sql_t_Pemanen);
			oci_execute($result_t_Pemanen, OCI_DEFAULT);
			while (oci_fetch($result_t_Pemanen)) {	
				$Nama_Pemanen[] = oci_result($result_t_Pemanen, "NAMA_PEMANEN");
				$NIK_Pemanen[] 	= oci_result($result_t_Pemanen, "NIK_PEMANEN");
				$Nama_Mandor[] 	= oci_result($result_t_Pemanen, "NAMA_MANDOR");
			}
			$roweffec_Pemanen = oci_num_rows($result_t_Pemanen);
			//echo "q pemanen ".$sql_t_Pemanen. $roweffec_Pemanen;
			
			
			if(isset($_POST['NIK_Pemanen'])){
				$_SESSION["NIK_Pemanen"] = $_POST['NIK_Pemanen'];
			}
			//echo "<br> session pemanen".$_SESSION['NIK_Pemanen'];
			if(isset($_SESSION['NIK_Pemanen'])){
				$sNIK_Pemanen = $_SESSION["NIK_Pemanen"];
				$session_sql_t_Pemanen = "select NIK_Pemanen, f_get_empname(NIK_Pemanen) Nama_Pemanen, NIK_Mandor, f_get_empname(NIK_Mandor) Nama_Mandor from t_header_rencana_panen where NIK_Pemanen = '$sNIK_Pemanen'";
				$session_result_t_Pemanen = oci_parse($con, $session_sql_t_Pemanen);
				oci_execute($session_result_t_Pemanen, OCI_DEFAULT);
				while (oci_fetch($session_result_t_Pemanen)) {	
					$sesNama_Pemanen = oci_result($session_result_t_Pemanen, "NAMA_PEMANEN");
					$sesNIK_Pemanen 	= oci_result($session_result_t_Pemanen, "NIK_PEMANEN");
					$sesNama_Mandor 	= oci_result($session_result_t_Pemanen, "NAMA_MANDOR");
				}
				
				$optionGETPemanen = "<option value=\"$sesNIK_Pemanen\" selected=\"selected\">$sesNama_Pemanen | $sesNIK_Pemanen | $sesNama_Mandor</option>";
				if($sNIK_Pemanen == "ALL"){
					$optionGETPemanen = "";
				}
			}
			
			
			$pagesize = 10;	
			$roweffec_hpk = 0;
			if(isset($_GET["submitvalue"]) == 1){
				$sql_t_hpk  = "SELECT TANGGAL,
									 ID_CC,
									 ID_BA,
									 ID_AFD,
									 ID_BLOK,
									 BLOK_NAME,
									 BJR,
									 SUM (LUASAN_PANEN) LUASAN_PANEN,
									 SUM (estimasi_berat) estimasi_berat,
									 SUM (TBS) TBS,
									 SUM (BRD) BRD
								FROM (   
								SELECT TP.*
										FROM (              
										SELECT TN.TGL_NAB TANGGAL,
													   THRP.TANGGAL_RENCANA,
													   THP.NO_REKAP_BCC,
													   THRP.ID_RENCANA,
													   TDRP.ID_BA_AFD_BLOK,
													   TBA.ID_CC,
													   TBA.ID_BA,
													   TA.ID_AFD,
													   TB.ID_BLOK,
													   TB.BLOK_NAME,
													   TDRP.LUASAN_PANEN,
													   F_GET_BJR (TB.ID_BLOK, THRP.TANGGAL_RENCANA,TDRP.ID_BA_AFD_BLOK)
														  AS BJR,
														(SUM(F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'TBS2')) * 
															F_GET_BJR (TB.ID_BLOK, THRP.TANGGAL_RENCANA,TDRP.ID_BA_AFD_BLOK)) estimasi_berat,
													   SUM(F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'TBS2')) TBS,
													   SUM(F_GET_HASIL_PANEN (THP.NO_REKAP_BCC, THP.NO_BCC, 'BRD')) BRD
												  FROM T_HEADER_RENCANA_PANEN THRP
													   INNER JOIN T_DETAIL_RENCANA_PANEN TDRP
														  ON THRP.ID_RENCANA = TDRP.ID_RENCANA
													   INNER JOIN T_BLOK TB
														  ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
													   INNER JOIN T_HASIL_PANEN THP
														  ON TDRP.ID_RENCANA = THP.ID_RENCANA
															 AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC
													   INNER JOIN T_AFDELING TA
														  ON TB.ID_BA_AFD = TA.ID_BA_AFD
													   INNER JOIN T_BUSSINESSAREA TBA
														  ON TA.ID_BA = TBA.ID_BA
													   INNER JOIN T_NAB TN
														  ON THP.ID_NAB_TGL = TN.ID_NAB_TGL
												 WHERE tba.id_cc = '$subID_CC' AND tba.ID_BA = '$sID_BA'
													   AND ta.id_afd = nvl (decode ('$sAFD', 'ALL', null, '$sAFD'), ta.id_afd) 
													   AND tb.id_blok = nvl (decode ('$sID_BLOK', 'ALL', null, '$sID_BLOK'), tb.id_blok)
													   AND tn.tgl_nab  between TO_DATE('$sdate1','RRRR-mm-dd') and TO_DATE(nvl ('$sdate2', '$sdate1'),'RRRR-mm-dd')
													   AND thrp.nik_pemanen = nvl (decode ('$sNIK_Pemanen', 'ALL', null, '$sNIK_Pemanen'), thrp.nik_pemanen) 
											  GROUP BY TN.TGL_NAB,
													   THRP.TANGGAL_RENCANA,
													   THP.NO_REKAP_BCC,
													   THRP.ID_RENCANA,
													   TDRP.ID_BA_AFD_BLOK,
													   TBA.ID_CC,
													   TBA.ID_BA,
													   TA.ID_AFD,
													   TB.ID_BLOK,
													   TB.BLOK_NAME,
													   TDRP.LUASAN_PANEN,
													   TDRP.NO_REKAP_BCC
											) TP                                            
										)
										GROUP BY tanggal,
												 id_cc,
												 id_ba,
												 id_afd,
												 id_blok,
												 blok_name,
												 bjr
										ORDER BY tanggal,
												 id_cc,
												 id_ba,
												 id_afd,
												 id_blok";
				
				$_SESSION["sql_laporan_production"] = $sql_t_hpk;
				$result_t_hpk = oci_parse($con, $sql_t_hpk);
				oci_execute($result_t_hpk, OCI_DEFAULT);
				//echo $sql_t_hpk; die ();
				while (oci_fetch($result_t_hpk)) {	
					$viewTANGGAL[] = oci_result($result_t_hpk, "TANGGAL");
					$viewID_CC[] = oci_result($result_t_hpk, "ID_CC");
					$viewID_BA[] = oci_result($result_t_hpk, "ID_BA");
					$viewID_AFD[] = oci_result($result_t_hpk, "ID_AFD");
					$viewID_BLOK[] = oci_result($result_t_hpk, "ID_BLOK");
					$viewBLOK_NAME[] = oci_result($result_t_hpk, "BLOK_NAME");
					$viewLUASAN_PANEN[] = oci_result($result_t_hpk, "LUASAN_PANEN");
					$viewBJR[] = oci_result($result_t_hpk, "BJR");
					$viewESTIMASI[] = oci_result($result_t_hpk, "ESTIMASI_BERAT");
					$viewTBS[] = oci_result($result_t_hpk, "TBS");
					$viewBRD[] = oci_result($result_t_hpk, "BRD");
				}
				$roweffec_hpk = oci_num_rows($result_t_hpk);
				
				$totalpage = ceil($roweffec_hpk/$pagesize);
				$setPage = $totalpage - 1;
			}
			else{
				$totalpage = 0;
				$roweffec_hpk  = "";
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
			//echo $OnPage;
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

function formSubmit(x)
{
	if(x == 1){
	document.getElementById("formJC").submit();
    }
	
	if(x == 2){
	document.getElementById("formtampilkan").submit();
    }
}
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

tbody#scrolling {
	width: 1100px;
    height: 300px;
    overflow: auto;
    display: block;
}

</style>
<table width="1151" height="390" border="0" align="center">
  <!--<tr bgcolor="#C4D59E">-->
  <tr>
    <th height="197" scope="row" align="center"><table width="1100" border="0" id="setbody2">
      <tr>
        <td colspan="5" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>LAPORAN TAKSASI PRODUKSI</strong></span></td>
		<td colspan="5" align="right"><a href="printXLS.php"><input type="submit" name="button" id="button" value="DOWNLOAD TO XLS" style="width:200px; height: 30px; font-size:16px; visibility:<?=$visisub?>" onclick="formSubmit(1)"/></a></td>
      </tr>
	  <tr>
        <td height="45" colspan="3" valign="bottom" style="border-bottom:solid #000">LOKASI</td>
		<!--td height="45" colspan="1" valign="bottom" style="border-bottom:solid #000">&nbsp;</td-->
		<td height="45" colspan="4" valign="bottom" style="border-bottom:solid #000">PERIODE</td>
		<td height="45" colspan="2" valign="bottom" style="border-bottom:solid #000">&nbsp;</td>
		      </tr>
      <tr>
      
		<form id="submittanggal1" name="submittanggal1" method="post" action="LaporanProduksiView.php">
		<tr>
		<td width="96" height="29" valign="top" >Company Code</td>
        <td width="9" height="29" valign="top" >:</td>
        <td width="226" height="29" valign="top" ><input name="CClabel" type="text" id="CClabel" value="<?=$subID_CC?>" style="background-color:#CCC; width: 100px; height:25px; font-size:15px; display:inline" onmousedown="return false"/></td>
        
		<td width="50" height="29" colspan="3" valign="top" >Start Date</td>
		<td width="10" height="29"  valign="top" >:</td>
		<td width="50" colspan="4" valign="top" ><input type="text" name="date1" id="datepicker" class="box_field" onchange="this.form.submit();" <?php if(isset($_POST["date1"])){ echo "value='$_POST[date1]'"; }?>></td>
		</tr>
		<tr>
		<td width="112" valign="top" >Company Name</td>
        <td width="10" valign="top" >:</td>
        <td width="513" valign="top" ><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$sComp_Name?>" style="background-color:#CCC; width: 400px; height:25px; font-size:15px" onmousedown="return false"/></td>
		
		<td width="80" height="29" colspan="3" valign="top" >End Date</td>
		<td width="10" height="29" valign="top" >:</td>
		<td width="100" colspan="4" valign="top" ><input type="text" name="date2" id="datepicker2" class="box_field" onchange="this.form.submit();" <?php if(isset($_POST["date2"])){ echo "value='$_POST[date2]'"; }?>></td>
		</tr>
		</form>
		
        <td width="94" valign="top" >Business Area</td>
        <td width="16" valign="top" >:</td>
        <td width="134" valign="top" ><form id="form3" name="form3" method="post" action="LaporanProduksiView.php">
          <?php       
if($roweffec_BA > 0 ){
		
	$selectoBA = "<select name=\"BA\" id=\"BA\" style=\"width: 150px; display:$disBA\" onchange=\"this.form.submit()\">";
	
	echo $selectoBA.$optionGETBA;
	
	for($xBA = 0; $xBA <  $roweffec_BA; $xBA++){
		
		echo "<option value=\"$ID_BA[$xBA]\">$ID_BA[$xBA]</option>"; 
	}
	
	$selectcBA = "</select>";
	echo $selectcBA;
}


        
?>
        </form></td>
      </tr>
      <tr>
      <td valign="top" >Afdeling</td>
      <td valign="top" >:</td>
      <td ><form id="form2" name="form1" method="post" action="LaporanProduksiView.php">
        <?php    

if($roweffec_AFD > 0 ){

//	echo "11";	
	$selectoAFD = "<select name=\"AFD\" id=\"AFD\" onchange=\"this.form.submit();\" style=\"width: 150px; display:inline \">";
	$optionAFD= "<option value=\"ALL\"> ALL </option>";
	//echo $selectoCC.$optionCC;
	echo $selectoAFD.$optionGETAFD.$optionAFD;
	for($xAFD = 0; $xAFD <  $roweffec_AFD; $xAFD++){
		echo "<option value=\"$ID_AFD[$xAFD]\">$ID_AFD[$xAFD]</option>";
	}
	$selectcAFD = "</select>";
	echo $selectcAFD;
}
//TAMBAH ELSE 24.09.2013
else{
	$selectoAFD = "<select name=\"AFD\" id=\"AFD\" onchange=\"this.form.submit();\" style=\"width: 150px; display:inline \">";
	$optionAFD= "<option value=\"ALL\"> ALL </option>";
	echo $selectoAFD.$optionAFD;
	
	$selectcAFD = "</select>";
	echo $selectcAFD;
}


?>
      </form></td>
	  </tr>
	  <tr>
      <td valign="top" >Blok</td>
      <td valign="top" >:</td>
      <td ><form id="form4" name="form1" method="post" action="LaporanProduksiView.php">
        <?php    

if($roweffec_BLOK > 0 ){

//	echo "11";	
	$selectoBLOK = "<select name=\"BLOK\" id=\"BLOK\" onchange=\"this.form.submit();\" style=\"width: 150px; display:inline \">";
	$optionBLOK = "<option value=\"ALL\"> ALL </option>";
	echo $selectoBLOK.$optionGETBLOK.$optionBLOK;
	
	for($xBLOK = 0; $xBLOK <  $roweffec_BLOK; $xBLOK++){
		echo "<option value=\"$ID_BLOK[$xBLOK]\">$ID_BLOK[$xBLOK]</option>";
	}
	
	$selectcBLOK = "</select>";
	echo $selectcBLOK;
}
else{
	$selectoBLOK = "<select name=\"BLOK\" id=\"BLOK\" onchange=\"this.form.submit();\" style=\"width: 150px; display:inline \">";
	$optionBLOK = "<option value=\"ALL\"> ALL </option>";
	echo $selectoBLOK.$optionBLOK;
	
	$selectcBLOK = "</select>";
	echo $selectcBLOK;
}


?>
      </form></td>
	  </tr>
		<tr>
			<td height="30" colspan="9" valign="bottom" style="border-bottom:solid #000">Tampilkan Data</td>
		</tr>
		<tr>
        <td valign="top" >Pemanen</td>
        <td valign="top" >:</td>
        <td colspan="6" valign="top"><form id="formtampilkan" name="formtampilkan" method="post" action="LaporanProduksiView.php?submitvalue=1">
          <!--<input name="submitvalue" type="text" id="submitvalue" value="1" style="display:none"/>-->
          <?php    

			if($roweffec_Pemanen > 0 ){

			//	echo "11";	
				$selectoPM = "<select name=\"NIK_Pemanen\" id=\"NIK_Pemanen\" style=\"width: 400px; display:$disCC \">";
				$optionPM = "<option value=\"ALL\"> ALL </option>";
				echo $selectoPM.$optionGETPemanen.$optionPM;
				
				for($xPM = 0; $xPM <  $roweffec_Pemanen; $xPM++){
					
					echo "<option value=\"$NIK_Pemanen[$xPM]\">$Nama_Pemanen[$xPM] | $NIK_Pemanen[$xPM] | $Nama_Mandor[$xPM] </option>";
				}
				
				$selectcPM = "</select>";
				echo $selectcPM;
			}

		?>
        </form>
		</td>
		<td align="right" valign="bottom" ><input type="submit" name="button2" id="button2" value="TAMPILKAN" style="width:120px; height: 30px" onclick="formSubmit(2)"/></td>
		</tr>
<?php
if($iudvalue == 1){
echo "<tr><td>Last Job Authorization Code : $AUTHORITY[0]<td><tr>";
}
else if($iudvalue == 2){
echo "<tr><td>Last Job Authorization Code : $AUTHORITY[0]<td><tr>";
}

?>  
      
      <tr>
        <td height="46" colspan="9" align="center" valign="bottom" >
          <?php
if($roweffec_hpk >0){
echo "<table width=\"1100px\"  border=\"1\" bordercolor=\"#9CC346\">
          <tr bgcolor=\"#9CC346\" >
            <td width=\"150px\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Tanggal</td>
            <td width=\"150px\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Company Code</td>
            <td width=\"150px\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Business Area</td>
            <td width=\"80px\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Divisi</td>
            <td width=\"200px\" colspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Blok</td>
            <td width=\"200px\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Luasan Panen(Ha)</td>
            <td width=\"200px\" rowspan=\"2\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BJR</td>
            <td width=\"200px\" colspan=\"3\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Produksi</td>
          </tr>

          
          <tr bgcolor=\"#9CC346\">
            <td width=\"100px\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">SAP</td>
            <td width=\"100px\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Desc.</td>
            <td width=\"100px\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">TBS (jjg)</td>
            <td width=\"100px\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">BRD (kg)</td>
			<td width=\"100px\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Estimasi Berat</td>
          </tr>";
$endPage = $calPage + $pagesize;
for($xJAN = $calPage; $xJAN <  $roweffec_hpk && $xJAN <$endPage; $xJAN++){
//for($xJAN = 0; $xJAN <  $roweffec_hpk ; $xJAN++){
	$no = $xJAN +1;	
	if(($xJAN % 2) == 0){
		$bg = "#F0F3EC";
	}
	else{
		$bg = "#DEE7D2";
	}
echo "<tr style=\"font-size:12px\" bgcolor=$bg >";
echo "<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$viewTANGGAL[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$viewID_CC[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$viewID_BA[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$viewID_AFD[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$viewID_BLOK[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$viewBLOK_NAME[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$viewLUASAN_PANEN[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$viewBJR[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$viewTBS[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$viewBRD[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$viewESTIMASI[$xJAN]</td>
	";

}
echo "</tr>";
echo "</table>";
}
?>
		<!--</tbody>
        </table>-->
        
        
        </td>
      </tr>
      <tr>
        <td colspan="9">
        <form id="form6" name="form6" method="post" action="">
          <input name="CC" type="text" id="CC" value="<?=$vID_BA?>" style="display:none" onmousedown="return false"/>
          <input name="BA" type="text" id="BA" value="<?=$vID_BA?>" style="display:none" onmousedown="return false"/>
          <input name="AFD" type="text" id="AFD" value="<?=$vID_BA?>" style="display:none" onmousedown="return false"/>
          <input name="BLOK" type="text" id="BLOK" value="<?=$vID_BA?>" style="display:none" onmousedown="return false"/>
          <input name="PEMANEN" type="text" id="PEMANEN" value="<?=$vID_BA?>" style="display:none" onmousedown="return false"/>
          <input name="DATE1" type="text" id="BLOK" value="<?=$vID_BA?>" style="display:none" onmousedown="return false"/>
          <input name="PEMANEN" type="text" id="PEMANEN" value="<?=$vID_BA?>" style="display:none" onmousedown="return false"/>
        </form></td>
      </tr>
	  
	  <tr>
        <td colspan="9" align="right">
<?php
if($roweffec_hpk > 0){
?>
          <table width="400" border="1">
            <tr>
			  <td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="LaporanProduksiView.php?page=first&&submitvalue=1">
                <input type="button" name="button6" id="button6" value="&lt;&lt; First" style="width:70px; background-color:#9CC346"/>
              </a></td>
              <td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="LaporanProduksiView.php?page=back&&submitvalue=1">
                <input type="button" name="button5" id="button5" value="&lt; Back" style="width:70px; background-color:#9CC346"/>
              </a></td>
              <td width="100" align="center" valign="middle" style="background-color:#9CC346; font-size:12px"><span style="padding-top:0px">Page
                <?=$sesPageres+1?>
                of
                <?=$totalpage?>
              </span></td>
              <td width="70" align="left" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="LaporanProduksiView.php?page=next&&submitvalue=1"></a><a href="LaporanProduksiView.php?page=next&&submitvalue=1">
                <input type="button" name="button4" id="button4" value="Next &gt;" style="width:70px; background-color:#9CC346"/>
              </a></td>
			  <td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="LaporanProduksiView.php?page=last&&submitvalue=1">
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
        <td colspan="9">
        
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
