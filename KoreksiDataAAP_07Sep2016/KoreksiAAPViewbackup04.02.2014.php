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
		
		$KoreksiDataAAP = "";
		if(isset($_POST["KoreksiDataAAP"])){
			$KoreksiDataAAP = $_POST["KoreksiDataAAP"];
			$_SESSION["KoreksiDataAAP"] = $KoreksiDataAAP;
		}
		if(isset($_SESSION["KoreksiDataAAP"])){
			$KoreksiDataAAP = $_SESSION["KoreksiDataAAP"];
		}
		
		if($KoreksiDataAAP == TRUE){
		
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
			
			if(isset($_SESSION['BA'])){
				$sID_BA = $_SESSION["BA"];
				$sql_t_AFD  = "select * from t_Afdeling tafd inner join t_bussinessarea tba on tafd.id_ba = tba.id_ba WHERE tba.id_ba = '$sID_BA'";
				$optionGETBA = "<option value=\"$sID_BA\" selected=\"selected\">$sID_BA</option>";
			}
			
			$result_t_sAFD = oci_parse($con, $sql_t_AFD);
			oci_execute($result_t_sAFD, OCI_DEFAULT);
			while (oci_fetch($result_t_sAFD)) {	
				$ID_AFD[] 		= oci_result($result_t_sAFD, "ID_AFD");
			}
			$roweffec_AFD = oci_num_rows($result_t_sAFD);
	
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
				$_SESSION['date1'] = $_POST["date1"];
				unset($_SESSION['date2']);
			}
	
			if(isset($_POST["date2"])){
				$_SESSION['date2'] = $_POST["date2"];
			}
	
			if(isset($_SESSION['date1'])){
				$sdate1 = $_SESSION['date1'];
			}
			
			if(isset($_SESSION['date2'])){
				$sdate2 = $_SESSION['date2'];
			}
			/*
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
			*/
			$sql_t_Pemanen = "
								select NIK_Pemanen, f_get_empname(NIK_Pemanen) Nama_Pemanen, NIK_Mandor, f_get_empname(NIK_Mandor) Nama_Mandor
								from t_header_rencana_panen thrp, t_detail_rencana_panen tdrp, t_blok tb, t_afdeling ta, t_detail_gandeng tdg, t_hasilpanen_kualtas thk
								WHERE 
								thrp.id_rencana = tdrp.id_rencana and
								tdrp.id_ba_afd_blok = tb.id_ba_afd_blok and
								tb.id_ba_afd = ta.id_ba_afd and
								tdrp.id_rencana = tdg.id_rencana and
								tdrp.id_rencana = thk.id_rencana and
								thrp.status_gandeng = 'YES' and
								tdrp.luasan_panen > 0 and
								ta.ID_BA = '$sID_BA' 
								and ta.id_afd = nvl (decode ('$sAFD', 'ALL', null, '$sAFD'), ta.id_afd) 
								and tb.id_blok = nvl (decode ('$sID_BLOK', 'ALL', null, '$sID_BLOK'), tb.id_blok)
								and to_char(thrp.tanggal_rencana,'YYYY-MM-DD') between '$sdate1' and nvl ('$sdate2', '$sdate1') 
								group by NIK_PEMANEN, NIK_Mandor
								order by Nama_Pemanen 
							";
			$result_t_Pemanen = oci_parse($con, $sql_t_Pemanen);
			oci_execute($result_t_Pemanen, OCI_DEFAULT);
			while (oci_fetch($result_t_Pemanen)) {	
				$Nama_Pemanen[] = oci_result($result_t_Pemanen, "NAMA_PEMANEN");
				$NIK_Pemanen[] 	= oci_result($result_t_Pemanen, "NIK_PEMANEN");
				$Nama_Mandor[] 	= oci_result($result_t_Pemanen, "NAMA_MANDOR");
			}
			$roweffec_Pemanen = oci_num_rows($result_t_Pemanen);			
			
			if(isset($_POST['NIK_Pemanen'])){
				$_SESSION["NIK_Pemanen"] = $_POST['NIK_Pemanen'];
			}
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
			
			$roweffec_hpk = 0;
			if(isset($_POST["submitvalue"]) == 1){
			/*
			$sql_t_hpk  = "SELECT TANGGAL,
								 ID_CC,
								 ID_BA,
								 ID_AFD,
								 ID_BLOK,
								 NIK_GANDENG, 
								 SUM (LUASAN_PANEN) LUASAN_PANEN,
								 SUM (NIK_PEMANEN) NIK_PEMANEN,
								 SUM (NAMA_PEMANEN) NAMA_PEMANEN
							FROM (SELECT THRP.TANGGAL_RENCANA TANGGAL,
										 TBA.ID_CC,
										 TBA.ID_BA,
										 TA.ID_AFD,
										 TB.ID_BLOK,
										 TB.NIK_GANDENG,
										 TDRP.LUASAN_PANEN, 
										 F_GET_TBS_PROD (TDRP.ID_RENCANA,TDRP.NO_REKAP_BCC) NIK_PEMANEN ,
										 F_GET_BRD_PROD (TDRP.ID_RENCANA,TDRP.NO_REKAP_BCC) NAMA_PEMANEN              
									FROM T_HEADER_RENCANA_PANEN THRP
										 INNER JOIN T_DETAIL_RENCANA_PANEN TDRP
											ON THRP.ID_RENCANA = TDRP.ID_RENCANA
										 INNER JOIN T_BLOK TB
											ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
										 INNER JOIN T_AFDELING TA
											ON TB.ID_BA_AFD = TA.ID_BA_AFD
										 INNER JOIN T_BUSSINESSAREA TBA
											ON TA.ID_BA = TBA.ID_BA
									   where tba.id_cc = '$subID_CC' and tba.ID_BA = '$sID_BA' 
									and ta.id_afd = nvl (decode ('$sAFD', 'ALL', null, '$sAFD'), ta.id_afd) 
									and tb.id_blok = nvl (decode ('$sID_BLOK', 'ALL', null, '$sID_BLOK'), tb.id_blok)
									and to_char(thrp.tanggal_rencana,'YYYY-MM-DD') between '$sdate1' and nvl ('$sdate2', '$sdate1')
											 and thrp.nik_pemanen = nvl (decode ('$sNIK_Pemanen', 'ALL', null, '$sNIK_Pemanen'), thrp.nik_pemanen)  )
							group by tanggal,
									 id_cc,
									 id_ba,
									 id_afd,
									 id_blok,
									 NIK_GANDENG
							order by tanggal,
									 id_cc,
									 id_ba,
									 id_afd,
									 id_blok";
			*/
			$sql_t_hpk  = "
							select thrp.tanggal_rencana, thrp.id_rencana, tb.id_blok, ta.id_afd, NIK_Pemanen, f_get_empname(NIK_Pemanen) Nama_Pemanen, 
							NIK_Mandor, f_get_empname(NIK_Mandor) Nama_Mandor, 
							NIK_Kerani_buah, f_get_empname(NIK_Kerani_buah) Nama_Kerani_buah, 
							luasan_panen, nik_gandeng 
							from 
							t_header_rencana_panen thrp, 
							t_detail_rencana_panen tdrp, 
							t_blok tb, t_afdeling ta, 
							t_detail_gandeng tdg, 
							t_hasilpanen_kualtas thk,
							t_bussinessarea tba
							WHERE 
							thrp.id_rencana = tdrp.id_rencana and 
							tdrp.id_ba_afd_blok = tb.id_ba_afd_blok and 
							tb.id_ba_afd = ta.id_ba_afd and 
							tdrp.id_rencana = tdg.id_rencana and 
							tdrp.id_rencana = thk.id_rencana and 
							ta.id_ba = tba.id_ba and
							thrp.status_gandeng = 'YES' and 
							tdrp.luasan_panen > 0 and 
							tba.id_cc = '$subID_CC' and
							ta.ID_BA = '$sID_BA' and 
							ta.id_afd = nvl (decode ('ALL', 'ALL', null, 'ALL'), ta.id_afd) and 
							tb.id_blok = nvl (decode ('', 'ALL', null, ''), tb.id_blok) and 
							to_char(thrp.tanggal_rencana,'YYYY-MM-DD') between '$sdate1' and nvl ('$sdate2', '$sdate1') and 
							thrp.nik_pemanen = nvl (decode ('$sNIK_Pemanen', 'ALL', null, '$sNIK_Pemanen'), thrp.nik_pemanen)
							group by thrp.tanggal_rencana, thrp.id_rencana, tb.id_blok, ta.id_afd, NIK_PEMANEN, NIK_Mandor, NIK_Kerani_buah, NIK_Kerani_buah, luasan_panen, nik_gandeng 
							order by thrp.tanggal_rencana, ta.id_afd, tb.id_blok, Nama_Kerani_buah, Nama_Pemanen
							";
			$_SESSION["sql_koreksi_aap"] = $sql_t_hpk;
			$result_t_hpk = oci_parse($con, $sql_t_hpk);
			oci_execute($result_t_hpk, OCI_DEFAULT);
			
			while (oci_fetch($result_t_hpk)) {	
				$viewTanggal_Rencana[] = oci_result($result_t_hpk, "TANGGAL_RENCANA");
				$viewID_Rencana[] = oci_result($result_t_hpk, "ID_RENCANA");
				$viewID_AFD[] = oci_result($result_t_hpk, "ID_AFD");
				$viewID_BLOK[] = oci_result($result_t_hpk, "ID_BLOK");
				$viewNIK_GANDENG[] = oci_result($result_t_hpk, "NIK_GANDENG");
				$viewLUASAN_PANEN[] = number_format((float)oci_result($result_t_hpk, "LUASAN_PANEN"), 2, '.', '');
				$viewNIK_PEMANEN[] = oci_result($result_t_hpk, "NIK_PEMANEN");
				$viewNAMA_PEMANEN[] = oci_result($result_t_hpk, "NAMA_PEMANEN");
				$viewNIK_KERANI_BUAH[] = oci_result($result_t_hpk, "NIK_KERANI_BUAH");
				$viewNAMA_KERANI_BUAH[] = oci_result($result_t_hpk, "NAMA_KERANI_BUAH");
				$viewNIK_MANDOR[] = oci_result($result_t_hpk, "NIK_MANDOR");
				$viewNAMA_MANDOR[] = oci_result($result_t_hpk, "NAMA_MANDOR");
			}
			$roweffec_hpk = oci_num_rows($result_t_hpk);

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
<table width="1064" height="390" border="0" align="center">
  <tr>
    <th height="197" scope="row" align="center"><table width="937" border="0" id="setbody2">
      <tr>
        <td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>KOREKSI DATA AKTIVITAS AKHIR PANEN</strong></span></td>
      </tr>
      <tr>
        <td  colspan="3" valign="bottom" style="border-bottom:solid #000">LOKASI</td>
        <td  colspan="6" valign="bottom" style="border-bottom:solid #000">PERIODE</td>
        </tr>
      <tr>

      
        <td width="161" height="13" valign="top">Company Code</td>
        <td width="6" height="13" valign="top">:</td>
        <td width="506" height="13" valign="top"><input name="CClabel" type="text" id="CClabel" value="<?=$subID_CC?>" style="background-color:#CCC; width: 50px; height:25px; font-size:15px; display:inline" onmousedown="return false"/></td>
        <td width="106" valign="top" >Start Date</td>
        <td width="10" valign="top" >:</td>
		<td height="13" colspan="4" valign="top">
        <form id="form1" name="form1" method="post" action="KoreksiAAPView.php">
        <input type="text" name="date1" id="datepicker" class="box_field"  onchange="this.form.submit();" value="<?=$sdate1?>" <?php if(isset($_POST["date1"])){ echo "value='$_POST[date1]'"; }?>>
        </form>
        </td>
      </tr>
      <tr>
        <td height="6" valign="top" >Company Name </td>
        <td width="6" height="6" valign="top">:</td>
        <td width="506" height="6" valign="top"><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$sComp_Name?>" style="background-color:#CCC; width: 400px; height:25px; font-size:15px" onmousedown="return false"/></td>
        <td width="106" valign="top">End Date</td>
        <td width="10" valign="top">:</td>
        <td height="6" colspan="4" valign="top" >
        <form id="form1" name="form1" method="post" action="KoreksiAAPView.php">
        <input type="text" name="date2" id="datepicker2" class="box_field" onchange="this.form.submit();" value="<?=$sdate2?>" <?php if(isset($_POST["date2"])){ echo "value='$_POST[date2]'"; }?>>
        </form>
        </td>
        </tr>
      <tr>
        <td height="6" valign="top" >Business Area</td>
        <td width="6" height="6" valign="top" >:</td>
        <td width="506" height="6" valign="top" ><input name="CClabel2" type="text" id="CClabel2" value="<?=$subID_BA_Afd?>" style="background-color:#CCC; width: 50px; height:25px; font-size:15px; display:inline" onmousedown="return false"/></td>
        <td width="106" valign="top" >&nbsp;</td>
        <td width="10" valign="top" >&nbsp;</td>
        <td height="6" colspan="4" valign="top">&nbsp;</td>
        </tr>
      <tr>
      <td valign="top" >Afdeling</td>
      <td valign="top" >:</td>
      <td ><form id="form2" name="form1" method="post" action="KoreksiAAPView.php">
        <?php    

if($roweffec_AFD > 0 ){

	$selectoAFD = "<select name=\"AFD\" id=\"AFD\" onchange=\"this.form.submit();\" style=\"width: 60px; display:inline \">";
	$optionAFD= "<option value=\"ALL\"> ALL </option>";
	echo $selectoAFD.$optionGETAFD.$optionAFD;
	for($xAFD = 0; $xAFD <  $roweffec_AFD; $xAFD++){
		echo "<option value=\"$ID_AFD[$xAFD]\">$ID_AFD[$xAFD]</option>";
	}
	$selectcAFD = "</select>";
	echo $selectcAFD;
}
else{
	$selectoAFD = "<select name=\"AFD\" id=\"AFD\" onchange=\"this.form.submit();\" style=\"width: 60px; display:inline \">";
	$optionAFD= "<option value=\"ALL\"> ALL </option>";
	echo $selectoAFD.$optionAFD;
	
	$selectcAFD = "</select>";
	echo $selectcAFD;
}


?>
      </form></td>
      <td valign="top" >&nbsp;</td>
      <td valign="top" >&nbsp;</td>
      <td colspan="4" >&nbsp;</td>
      </tr>
      <tr>
        <td valign="top" >Blok</td>
        <td valign="top" >:</td>
        <td >
        <form id="form4" name="form1" method="post" action="KoreksiAAPView.php">
<?php    

if($roweffec_BLOK > 0 ){
	$selectoBLOK = "<select name=\"BLOK\" id=\"BLOK\" onchange=\"this.form.submit();\" style=\"width: 60px; display:inline \">";
	$optionBLOK = "<option value=\"ALL\"> ALL </option>";
	echo $selectoBLOK.$optionGETBLOK.$optionBLOK;
	
	for($xBLOK = 0; $xBLOK <  $roweffec_BLOK; $xBLOK++){
		echo "<option value=\"$ID_BLOK[$xBLOK]\">$ID_BLOK[$xBLOK]</option>";
	}
	
	$selectcBLOK = "</select>";
	echo $selectcBLOK;
}
else{
	$selectoBLOK = "<select name=\"BLOK\" id=\"BLOK\" onchange=\"this.form.submit();\" style=\"width: 60px; display:inline \">";
	$optionBLOK = "<option value=\"ALL\"> ALL </option>";
	echo $selectoBLOK.$optionBLOK;
	
	$selectcBLOK = "</select>";
	echo $selectcBLOK;
}


?>
      </form>
        </td>
        <td valign="top">&nbsp;</td>
        <td valign="top">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
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
			<td height="30" colspan="9" valign="bottom" style=" border-bottom:solid #000">Tampilkan Data</td>
		</tr>
      <tr>
        <td valign="top">Pemanen</td>
        <td valign="top" >:</td>
        <td valign="top"><form id="formtampilkan" name="formtampilkan" method="post" action="KoreksiAAPView.php">
          <input name="submitvalue" type="text" id="submitvalue" value="1" style="display:none"/>
          <input name="sdate1" type="text" id="sdate1" value="<?=$sdate1?>" onmousedown="return false" style="display:none"/>
          <input name="sdate2" type="text" id="sdate2" value="<?=$sdate2?>" onmousedown="return false" style="display:none"/>
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
          </form></td>
        <td align="right" colspan="6" >
		<input type="submit" name="button2" id="button2" value="TAMPILKAN" style="width:120px; height: 30px" onclick="formSubmit(2)"/>
		</td>
		</tr>
      <tr>
        <td height="46" colspan="9" align="center" valign="bottom">
          <?php
		  

if($roweffec_hpk >0){
echo "<table width=\"1100px\"  border=\"1\" bordercolor=\"#9CC346\">
		  <tbody id=\"scrolling\" style=\"width:1100px\">
          <tr bgcolor=\"#9CC346\" >
		    <td width=\"75px\" rowspan=\"2\" align=\"center\" style=\"font-size:14px; background:url(../image/small-bar2.png) no-repeat;background-size:cover\" id=\"bordertable\">Tanggal</td>
            <td width=\"75px\" rowspan=\"2\" align=\"center\" style=\"font-size:14px; background:url(../image/small-bar2.png) no-repeat;background-size:cover\" id=\"bordertable\">AFD</td>
            <td width=\"75px\" rowspan=\"2\" align=\"center\" style=\"font-size:14px; background:url(../image/small-bar2.png) no-repeat;background-size:cover\" id=\"bordertable\">Blok</td>
            <td width=\"400px\" colspan=\"2\" align=\"center\" style=\"font-size:14px; background:url(../image/small-bar2.png) no-repeat;background-size:cover\" id=\"bordertable\">Kerani Buah</td>
			<td width=\"400px\" colspan=\"2\" align=\"center\" style=\"font-size:14px; background:url(../image/small-bar2.png) no-repeat;background-size:cover\" id=\"bordertable\">Mandor</td>
			<td width=\"400px\" colspan=\"2\" align=\"center\" style=\"font-size:14px; background:url(../image/small-bar2.png) no-repeat;background-size:cover\" id=\"bordertable\">Pemanen</td>
            <td width=\"75px\" rowspan=\"2\" align=\"center\" style=\"font-size:14px; background:url(../image/small-bar2.png) no-repeat;background-size:cover\" id=\"bordertable\">Luasan Panen(Ha)</td>
            <td width=\"200px\" rowspan=\"2\" align=\"center\" style=\"font-size:14px; background:url(../image/small-bar2.png) no-repeat;background-size:cover\"  id=\"bordertable\">NIK Gandeng</td>
			<td width=\"100px\" rowspan=\"2\" align=\"center\" style=\"font-size:14px; background:url(../image/small-bar2.png) no-repeat;background-size:cover\"  id=\"bordertable\">Koreksi</td>
          </tr>

          
          <tr bgcolor=\"#9CC346\">
            <td width=\"200px\" align=\"center\" style=\"font-size:14px; background:url(../image/small-bar2.png) no-repeat;background-size:cover\" id=\"bordertable\">NIK</td>
            <td width=\"200px\" align=\"center\" style=\"font-size:14px; background:url(../image/small-bar2.png) no-repeat;background-size:cover\" id=\"bordertable\">NAMA</td>
			<td width=\"200px\" align=\"center\" style=\"font-size:14px; background:url(../image/small-bar2.png) no-repeat;background-size:cover\" id=\"bordertable\">NIK</td>
            <td width=\"200px\" align=\"center\" style=\"font-size:14px; background:url(../image/small-bar2.png) no-repeat;background-size:cover\" id=\"bordertable\">NAMA</td>
			<td width=\"200px\" align=\"center\" style=\"font-size:14px; background:url(../image/small-bar2.png) no-repeat;background-size:cover\" id=\"bordertable\">NIK</td>
            <td width=\"200px\" align=\"center\" style=\"font-size:14px; background:url(../image/small-bar2.png) no-repeat;background-size:cover\" id=\"bordertable\">NAMA</td>
          </tr>";

for($xJAN = 0; $xJAN <  $roweffec_hpk ; $xJAN++){
	$no = $xJAN +1;	
	if(($xJAN % 2) == 0){
		$bg = "#F0F3EC";
	}
	else{
		$bg = "#DEE7D2";
	}
echo "<tr style=\"font-size:14px\" bgcolor=$bg >";
echo "
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$viewTanggal_Rencana[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$viewID_AFD[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$viewID_BLOK[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$viewNIK_KERANI_BUAH[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$viewNAMA_KERANI_BUAH[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$viewNIK_MANDOR[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$viewNAMA_MANDOR[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$viewNIK_PEMANEN[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$viewNAMA_PEMANEN[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$viewLUASAN_PANEN[$xJAN]</td>
	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$viewNIK_GANDENG[$xJAN]</td>
	<form id=\"formIDRENCANA$xJAN\" name=\"formIDRENCANA$xJAN\" method=\"post\" action=\"KoreksiAAPSelect.php\">
		<input name=\"editID_RENCANA\" type=\"text\" id=\"editID_RENCANA\" value=\"$viewID_Rencana[$xJAN]\" style=\"display:none\"/>
		<input name=\"editID_BLOK\" type=\"text\" id=\"editID_BLOK\" value=\"$viewID_BLOK[$xJAN]\" style=\"display:none\"/>
		<input name=\"editID_AFD\" type=\"text\" id=\"editID_AFD\" value=\"$viewID_AFD[$xJAN]\" style=\"display:none\"/>
		<td id=\"bordertable\">
		<a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formIDRENCANA$xJAN') .submit()\">
		<input type=\"button\" name=\"button\" id=\"button\" value=\"Koreksi\" style=\"width:90px; height:25px; font-size:12px\"/>
		</a>
		</td>
	</form>
	";

}
echo "</tr>";
echo "</tbody>
        </table>";
}
?>     
        
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
