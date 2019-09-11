<?php
session_start();
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
			
			$pagesize = 15;	
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
			
			$iudID_JOBAUTHORITY = "";
			//$iudAUTHORITY = "";
			//$iudvalue = "";
			if($iudID_JOBAUTHORITY != "" && $iudAUTHORITY!="" && $iudvalue !=""){
			
				if($iudID_JOBAUTHORITY == "" || $iudvalue == ""){
					$_SESSION["err"] = "ID_JOBAUTHORITY and value empty";
				}
				else{
					if($iudvalue == 1){
						$roweffec_AC = 0;
						$roweffec_CC = 0;
						$roweffec_cstBA = 0;
						//$roweffec_JA_No = 0;
						$visisub = "hidden";
						$disCC = "none";
						$disJA = "none";
						$disBA = "none";
						$vdisCC = "inline";
						$vdisBA = "inline";
						
						$sql_view = "SELECT * FROM  t_companycode a INNER JOIN t_jobauthority b ON a.ID_CC = b.ID_CC INNER JOIN t_Authority c ON b.AUTHORITY = c.AUTHORITY WHERE ID_JOBAUTHORITY = '$iudID_JOBAUTHORITY' AND b.AUTHORITY = '$iudAUTHORITY' ";
						
						$result_t_CC_AC = oci_parse($con, $sql_view);
						oci_execute($result_t_CC_AC, OCI_DEFAULT);
						
						while (oci_fetch($result_t_CC_AC)) {	
							$ID_CC[] = oci_result($result_t_CC_AC, "ID_CC");
							$sComp_Name = oci_result($result_t_CC_AC, "COMP_NAME");
							$vID_BA = oci_result($result_t_CC_AC, "ID_BA");
							$ACTIVITY_CODE[] = oci_result($result_t_CC_AC, "ACTIVITY_CODE");
							$JOB_CODE[] = oci_result($result_t_CC_AC, "JOB_CODE");
							$ID_JOBAUTHORITY[] = oci_result($result_t_CC_AC, "ID_JOBAUTHORITY");
							
							$AUTHORITY[] = oci_result($result_t_CC_AC, "AUTHORITY");
							$sAUTHORITY_NAME	= oci_result($result_t_CC_AC, "AUTHORITY_NAME");
							$sAUTHORITY_DESC	= oci_result($result_t_CC_AC, "AUTHORITY_DESC");
							
							$CREATED_DATE[] = oci_result($result_t_CC_AC, "CREATED_DATE");
							$CREATED_BY[] = oci_result($result_t_CC_AC, "CREATED_BY");
						}
						$roweffec_ACx = oci_num_rows($result_t_CC_AC);
	
						$sql_t_AC  = "select * from t_jobauthority WHERE ID_JOBAUTHORITY = '$iudID_JOBAUTHORITY' AND AUTHORITY = '$iudAUTHORITY' AND ID_BA = '$vID_BA' order by ID_BA ";
	//echo "<br>".$sql_t_AC;
						$result_t_AC = oci_parse($con, $sql_t_AC);
						oci_execute($result_t_AC, OCI_DEFAULT);
						while(oci_fetch($result_t_AC)){
							$acID_BA[]		= oci_result($result_t_AC, "ID_BA");
							$acACTIVITY_CODE[]	= oci_result($result_t_AC, "ACTIVITY_CODE");
							$acJOB_CODE[]	= oci_result($result_t_AC, "JOB_CODE");
						}
						$roweffec_AC = oci_num_rows($result_t_AC);
						$ses_JA = "";
						
	
					}
					else if($iudvalue == 2){
						$roweffec_AC = 0;
						$roweffec_CC = 0;
						$roweffec_cstBA = 0;
						$visisub = "visible";
						$disCC = "none";
						$disJA = "inline";
						$disBA = "none";
						$vdisCC = "inline";
						$vdisBA = "inline";
						
						$sql_view = "SELECT * FROM  t_companycode a INNER JOIN t_jobauthority b ON a.ID_CC = b.ID_CC INNER JOIN t_Authority c ON b.AUTHORITY = c.AUTHORITY WHERE ID_JOBAUTHORITY = '$iudID_JOBAUTHORITY' AND b.AUTHORITY = '$iudAUTHORITY' ";
						
						$result_t_CC_AC = oci_parse($con, $sql_view);
						oci_execute($result_t_CC_AC, OCI_DEFAULT);
						
						while (oci_fetch($result_t_CC_AC)) {	
							$ID_CC[] = oci_result($result_t_CC_AC, "ID_CC");
							$sComp_Name = oci_result($result_t_CC_AC, "COMP_NAME");
							$vID_BA = oci_result($result_t_CC_AC, "ID_BA");
							$ACTIVITY_CODE[] = oci_result($result_t_CC_AC, "ACTIVITY_CODE");
							$JOB_CODE[] = oci_result($result_t_CC_AC, "JOB_CODE");
							$ID_JOBAUTHORITY[] = oci_result($result_t_CC_AC, "ID_JOBAUTHORITY");
							
							$AUTHORITY[] = oci_result($result_t_CC_AC, "AUTHORITY");
							$sAUTHORITY_NAME	= oci_result($result_t_CC_AC, "AUTHORITY_NAME");
							$sAUTHORITY_DESC	= oci_result($result_t_CC_AC, "AUTHORITY_DESC");
							
							$CREATED_DATE[] = oci_result($result_t_CC_AC, "CREATED_DATE");
							$CREATED_BY[] = oci_result($result_t_CC_AC, "CREATED_BY");
						}
						$roweffec_ACx = oci_num_rows($result_t_CC_AC);
						echo $sql_view.$roweffec_ACx ;
						
						$sql_t_JA_No  = "SELECT * FROM t_Authority order by AUTHORITY";
						
						$result_t_JA_No = oci_parse($con, $sql_t_JA_No);
						oci_execute($result_t_JA_No, OCI_DEFAULT);
						while(oci_fetch($result_t_JA_No)){
							$AUTHORITY[]		= oci_result($result_t_JA_No, "AUTHORITY");
							$AUTHORITY_NAME[]	= oci_result($result_t_JA_No, "AUTHORITY_NAME");
							$AUTHORITY_DESC[]	= oci_result($result_t_JA_No, "AUTHORITY_DESC");
						}
						$roweffec_JA_No = oci_num_rows($result_t_JA_No);
						$optionGetJA = "";
						
						
						$sql_t_ACx  = "select * from t_jobauthority WHERE ID_JOBAUTHORITY = '$iudID_JOBAUTHORITY' AND AUTHORITY = '$iudAUTHORITY' AND ID_BA = '$vID_BA' order by ID_BA ";
	//echo "<br>".$sql_t_AC;
						$result_t_ACx = oci_parse($con, $sql_t_ACx);
						oci_execute($result_t_ACx, OCI_DEFAULT);
						while(oci_fetch($result_t_ACx)){
							$acxID_BA[]		= oci_result($result_t_ACx, "ID_BA");
							$acxACTIVITY_CODE[]	= oci_result($result_t_ACx, "ACTIVITY_CODE");
							$acxJOB_CODE[]	= oci_result($result_t_ACx, "JOB_CODE");
						}
						$roweffec_ACx = oci_num_rows($result_t_ACx);
						$ses_JA = "";
						
						$sql_t_AC  = "select * from t_job_activitycode where ID_BA = '$acxID_BA[0]' AND ACTIVITY_CODE != '$acxACTIVITY_CODE[0]' AND JOB_CODE != '$acxJOB_CODE[0]' order by ID_BA";
						echo "<br>".$sql_t_AC;
	
						$result_t_AC = oci_parse($con, $sql_t_AC);
						oci_execute($result_t_AC, OCI_DEFAULT);
						while(oci_fetch($result_t_AC)){
							$acID_BA[]		= oci_result($result_t_AC, "ID_BA");
							$acACTIVITY_CODE[]	= oci_result($result_t_AC, "ACTIVITY_CODE");
							$acJOB_CODE[]	= oci_result($result_t_AC, "JOB_CODE");
						}
						$roweffec_AC = oci_num_rows($result_t_AC);
						$ses_JA = "";
						
	
					}
				}
				
			}
			else{
					$roweffec_ACx = 0;
					$visisub = "visible";
					$disCC = "inline";
					$disBA = "inline";
					$disJA = "inline";
					$vdisCC = "none";
					$vdisBA = "none";
					
					
					$iudID_JOBAUTHORITY = "";
					$iudAUTHORITY = "";
					$iudvalue = ""; 
						
					if(isset($_POST['CC'])){
						$_SESSION['CC'] = $_POST['CC'];
						if($_POST['CC'] == 'kosong'){
							unset($_SESSION['BA']);
						}
						unset($_SESSION['BA']);
					}
					
					if(isset($_SESSION['CC'])){
						$ses_CC = $_SESSION['CC'];
						if($ses_CC  == 'kosong'){
							$sID_CC = "";
							$sComp_Name = "";
							$optionGetCC= "";	
							
							$conditionCC = "";
							
							$sql_t_CC  = "SELECT ID_CC, Comp_Name FROM t_companycode";	
							unset($_SESSION['BA']);
						}
						else{
							$sql_t_sCC  = "SELECT ID_CC, Comp_Name FROM t_companycode WHERE ID_CC = '$ses_CC'";
							//$result_t_sCC  = select_data($con,$sql_t_sCC);
							
							$result_t_sCC = oci_parse($con, $sql_t_sCC);
							oci_execute($result_t_sCC, OCI_DEFAULT);
							oci_fetch($result_t_sCC);
							
							$sID_CC 		= oci_result($result_t_sCC, "ID_CC");
							$sComp_Name 	= oci_result($result_t_sCC, "COMP_NAME");
				
							$optionGetCC= "<option value=\"$sID_CC\" selected=\"selected\">$sID_CC</option>";	
							$sql_t_CC  = "SELECT ID_CC, Comp_Name FROM t_companycode WHERE ID_CC != '$ses_CC' ";	
							//echo "ses_CC".$ses_CC;
							
							$conditionCC = "WHERE ID_CC = '$sID_CC'";
						}
					}
					else{
						$sID_CC = "";
						$sComp_Name = "";
						$optionGetCC= "";	
						
						$conditionCC = "";
						
						$sql_t_CC  = "SELECT ID_CC, Comp_Name FROM t_companycode";	
						//echo "sID_CC".$sID_CC." and sComp_Name".$sComp_Name;
					}
			
					$result_t_CC = oci_parse($con, $sql_t_CC);
					oci_execute($result_t_CC, OCI_DEFAULT);
					
					while (oci_fetch($result_t_CC)) {	
						$ID_CC[] = oci_result($result_t_CC, "ID_CC");
					}
					$roweffec_CC = oci_num_rows($result_t_CC);
					//echo $roweffec_CC;
					
					
					
					if(isset($_POST['BA'])){
						$_SESSION['BA'] = $_POST['BA'];
						unset($_SESSION['AFD']);
					}
					
					if(isset($_SESSION['BA'])){
						$ses_BA = $_SESSION['BA'];
						$sql_t_sBA  = "SELECT ID_BA FROM t_bussinessarea $conditionCC and ID_BA = '$ses_BA'";
						//$result_t_sBA  = select_data($con,$sql_t_sBA);
						
						$result_t_sBA = oci_parse($con, $sql_t_sBA);
						oci_execute($result_t_sBA, OCI_DEFAULT);
						oci_fetch($result_t_sBA);
						
						$sID_BA		= oci_result($result_t_sBA, "ID_BA");
			
						$optionGetBA= "<option value=\"$sID_BA\" selected=\"selected\">$sID_BA</option>";	
						$sql_t_BA  = "SELECT ID_BA FROM t_bussinessarea $conditionCC and ID_BA != '$ses_BA' ";	
						//echo "ses_BA".$ses_BA. $sql_t_sBA;
			
					}
					else{
						$sID_BA = "";
						$optionGetBA= "";	
						
						$sql_t_BA  = "SELECT ID_BA FROM t_bussinessarea $conditionCC";	
						//echo "sID_BA".$sID_BA;
					}
					
					if(isset($_POST['AFD'])){
						$_SESSION['AFD'] = $_POST['AFD'];
					}
					
					$result_t_bussinessarea = oci_parse($con, $sql_t_BA);
					oci_execute($result_t_bussinessarea, OCI_DEFAULT);
					while(oci_fetch($result_t_bussinessarea)){
						$ID_BA[]	= oci_result($result_t_bussinessarea, "ID_BA");
					}
					$roweffec_cstBA = oci_num_rows($result_t_bussinessarea);
					
					
					
					if(isset($_POST['JA_No'])){
						$_SESSION['JA_No'] = $_POST['JA_No'];
					}
					
					if(isset($_SESSION['JA_No'])){
						$ses_JA = $_SESSION['JA_No'];
						
						if($ses_JA == 'kosong'){
							$sAUTHORITY = "";
							$sAUTHORITY_NAME = "";
							$sAUTHORITY_DESC = "";
							$optionGetJA= "";	
							$roweffec_AC = "";
							$sql_t_JA_No  = "SELECT * FROM t_Authority order by AUTHORITY";
						}
						else{
						$sql_t_sJA  = "SELECT * FROM t_Authority WHERE AUTHORITY = '$ses_JA' order by AUTHORITY";
						//$result_t_sJA  = select_data($con,$sql_t_sJA);
						
						$result_t_sJA = oci_parse($con, $sql_t_sJA);
						oci_execute($result_t_sJA, OCI_DEFAULT);
						oci_fetch($result_t_sJA);
						
						$sAUTHORITY		= oci_result($result_t_sJA, "AUTHORITY");
						$sAUTHORITY_NAME	= oci_result($result_t_sJA, "AUTHORITY_NAME");
						$sAUTHORITY_DESC	= oci_result($result_t_sJA, "AUTHORITY_DESC");
			
			
							
						$optionGetJA= "<option value=\"$sAUTHORITY\" selected=\"selected\">$sAUTHORITY</option>";	
						$sql_t_JA_No  = "SELECT * FROM t_Authority WHERE AUTHORITY != '$ses_JA' order by AUTHORITY";	
						//echo "<br> ses_JA".$ses_JA;
						
						
								if($ses_JA == 1 && $sID_BA !== ""){
									//echo "masuk ses_JA 1";
									$conditionJA = "select * from t_job_activitycode where ACTIVITY_CODE = 'HA' OR ACTIVITY_CODE = 'HA2'"; 
									$conditionBA = "ID_BA = '$sID_BA'";
									$sql_t_AC  = "select * from ($conditionJA) WHERE $conditionBA order by ID_BA";
									
									$visisub = "hidden";	
								}
								else{
									//echo "masuk ses_JA other";
									$conditionJA = "where ID_BA = '$sID_BA'"; 
									$sql_t_AC  = "select * from t_job_activitycode $conditionJA order by ID_BA ";
									//echo  $sql_t_AC ;
								}
								$result_t_AC = oci_parse($con, $sql_t_AC);
								oci_execute($result_t_AC, OCI_DEFAULT);
								while(oci_fetch($result_t_AC)){
									$acID_BA[]		= oci_result($result_t_AC, "ID_BA");
									$acACTIVITY_CODE[]	= oci_result($result_t_AC, "ACTIVITY_CODE");
									$acJOB_CODE[]	= oci_result($result_t_AC, "JOB_CODE");
								}
								$roweffec_AC = oci_num_rows($result_t_AC);
								$_SESSION["roweffec_AC"] = $roweffec_AC;
								
								//send session
								for($xAC = 0; $xAC <  $roweffec_AC; $xAC++){
									$_SESSION["acACTIVITY_CODE$xAC"] = $acACTIVITY_CODE[$xAC];
								}
						}
					}
					else{
						$sAUTHORITY = "";
						$sAUTHORITY_NAME = "";
						$sAUTHORITY_DESC = "";
						$optionGetJA= "";	
						
						$roweffec_AC = "";
						
						$sql_t_JA_No  = "SELECT * FROM t_Authority order by AUTHORITY";
					}
					
					$result_t_JA_No = oci_parse($con, $sql_t_JA_No);
					oci_execute($result_t_JA_No, OCI_DEFAULT);
					while(oci_fetch($result_t_JA_No)){
						$AUTHORITY[]		= oci_result($result_t_JA_No, "AUTHORITY");
						$AUTHORITY_NAME[]	= oci_result($result_t_JA_No, "AUTHORITY_NAME");
						$AUTHORITY_DESC[]	= oci_result($result_t_JA_No, "AUTHORITY_DESC");
					}
					$roweffec_JA_No = oci_num_rows($result_t_JA_No);
			}
			
		}
		else{
			header("location:../menu/authoritysecure.php");
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
	font-family: Tahoma;
	font-size: 16px;
}
</style>
<table width="1064" height="390" border="0" align="center">
  <tr>
    <th width="972" height="108" scope="row"><?php include("../include/Header.php") ?></th>
  </tr>
  <tr bgcolor="#C4D59E">
    <th align="center" valign="top" scope="row"><table width="1044" border="0">
      <tr>
        <td colspan="5"><span style="font-size:18px">LAPORAN BCC RESTAN</span></td>
        <td colspan="5" align="right"><a href="printXLS.php"><input type="submit" name="button" id="button" value="DOWNLOAD TO XLS" style="width:200px; height: 30px; font-size:16px; visibility:<?=$visisub?>" onclick="formSubmit(1)"/></a></td>
      </tr>
      <tr>
        <td height="21" colspan="9" valign="bottom" style="font-size:14px ; border-bottom:solid #000">LOKASI</td>
      </tr>
      <tr>
        <td width="138" height="10" valign="top" style="font-size:14px">Company Code</td>
        <td width="5" valign="top" style="font-size:14px">:</td>
        <td width="122" height="10" valign="top" style="font-size:14px">
        
<form id="form1" name="form1" method="post" action="daftarbccrestan.php">
<?php    

if($roweffec_CC > 1 ){

//	echo "11";	
	$selectoCC = "<select name=\"CC\" id=\"CC\" onchange=\"this.form.submit();\" style=\"width: 60px; display:$disCC \">";
	$optionCC = "<option value=\"kosong\">-- select --</option>";
	echo $selectoCC.$optionGetCC.$optionCC;
	
	for($xCC = 0; $xCC <  $roweffec_CC; $xCC++){
		
		echo "<option value=\"$ID_CC[$xCC]\">$ID_CC[$xCC]</option>";
	}
	
	$selectcCC = "</select>";
	echo $selectcCC;
}

else if($roweffec_CC == 1){

	if($ID_CC[0] == ""){
		echo "Userlogin doesn't have area";
	}
	else{
	$selectoCC = "<select name=\"CC\" id=\"CC\" style=\"width: 60px\">";
	$optionCC = "<option value=\"kosong\">-- select --</option>";
	echo $selectoCC.$optionGetCC.$optionCC;
		echo "<option value=\"$ID_CC[0]\">$ID_CC[0]</option>"; 
	$selectcCC = "</select>";
	echo $selectcCC;
	}
}

?>
<input name="EditBA" type="text" id="EditBA" value="<?=$ID_CC[0]?>" style="background-color:#CCC; width: 50px; height:25px; font-size:15px; display:<?=$vdisCC?>" onmousedown="return false"/>
</form>
        
        </td>
        <td width="119" valign="top" style="font-size:14px">Business Area</td>
        <td width="7" valign="top" style="font-size:14px">:</td>
        <td width="187" height="10" valign="top" style="font-size:14px">
        
<form id="form3" name="form3" method="post" action="daftarbccrestan.php">
<?php       
if($roweffec_cstBA > 1 ){
		
	$selectoBA = "<select name=\"BA\" id=\"BA\" style=\"width: 70px; display:$disBA\" onchange=\"this.form.submit()\">";
	$optionBA = "<option value=\"kosong\">-- select --</option>";
	echo $selectoBA.$optionGetBA.$optionBA;
	
	for($xBA = 0; $xBA <  $roweffec_cstBA; $xBA++){
		
		echo "<option value=\"$ID_BA[$xBA]\">$ID_BA[$xBA]</option>"; 
	}
	
	$selectcBA = "</select>";
	echo $selectcBA;
}

else if($roweffec_cstBA == 1){

	if($ID_BA[0] == ""){
		echo "Userlogin doesn't have area". $ID_BA[0];
	}
	else{
	$selectoBA = "<select name=\"BA\" id=\"BA\"  style=\"width: 70px\" onchange=\"this.form.submit();\">";
	$optionBA = "<option value=\"kosong\">-- select --</option>";
	echo $selectoBA.$optionGetBA.$optionBA;
	
		echo "<option value=\"$ID_BA[0]\">$ID_BA[0]</option>"; 
	
	$selectcBA = "</select>";
	echo $selectcBA;
	}
}
        
?>
<input name="EditBA" type="text" id="EditBA" value="<?=$vID_BA?>" style="background-color:#CCC; width: 70px; height:25px; font-size:15px; display:<?= $vdisBA?>" onmousedown="return false"/>
</form>
        
        
        </td>
        <td width="150" height="10" valign="top" style="font-size:14px">Company Name</td>
        <td width="7" valign="top" style="font-size:14px">:</td>
        <td width="367" valign="top" style="font-size:14px"><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$sComp_Name?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" onmousedown="return false"/></td>
      </tr>
      <tr>
        <td height="10" valign="top" style="font-size:14px">Divisi</td>
        <td height="10" valign="top" style="font-size:14px">:</td>
        <td height="10" colspan="3" valign="top" style="font-size:14px">
          
  <form id="form1" name="form1" method="post" action="daftarbccrestan.php">
  <?php  
	if(isset($_SESSION['BA'])){
		$ses_BA = $_SESSION['BA'];
		$sql_t_afdeling  = "SELECT * FROM t_afdeling where ID_BA = '$ses_BA'";
		
		$result_t_afdeling = oci_parse($con, $sql_t_afdeling);
		oci_execute($result_t_afdeling, OCI_DEFAULT);
		while(oci_fetch($result_t_afdeling))
		{
			$sID_AFD[] 			= oci_result($result_t_afdeling, "ID_AFD");
		}
		$rowAFD = oci_num_rows($result_t_afdeling);
		
		$optionGetAFD= "";
		if(isset($_SESSION['AFD'])){
			$sesAFD = $_SESSION['AFD'];
			if($sesAFD == "ALL"){
				$optionGetAFD= "<option value=\"$sesAFD\" selected=\"selected\">$sesAFD</option>";
			}
			else{
				$ses_sql_t_afdeling  = "SELECT ID_AFD FROM t_afdeling where ID_BA = '$ses_BA' and ID_AFD = '$sesAFD'";
				$ses_rs_t_afdeling  = oci_parse($con, $ses_sql_t_afdeling);
				oci_execute($ses_rs_t_afdeling, OCI_DEFAULT);
				$ses_fetch_t_afdeling = oci_fetch_array($ses_rs_t_afdeling);
				$ses_ID_AFD 		= $ses_fetch_t_afdeling["ID_AFD"];
				$optionGetAFD= "<option value=\"$ses_ID_AFD\" selected=\"selected\">$ses_ID_AFD</option>";
			}
		}
							
		$selectoAFD = "<select name=\"AFD\" id=\"AFD\"  style=\"width: 70px\" onchange=\"this.form.submit();\">";
		$optionAFD = "<option value=\"ALL\">-- ALL --</option>";
		echo $selectoAFD.$optionGetAFD.$optionAFD;
		
		for($xAFD = 0; $xAFD < $rowAFD; $xAFD++)
		{
			echo "<option value=\"$sID_AFD[$xAFD]\">$sID_AFD[$xAFD]</option>"; 
		}
		
		$selectcAFD = "</select>";
		echo $selectcAFD;

	}
	else{
		$sID_AFD = "";
		$optionGetAFD= "";	
	}		
?>
  <input name="EditBA" type="text" id="EditBA" value="<?=$vID_BA?>" style="background-color:#CCC; width: 70px; height:25px; font-size:15px; display:<?= $vdisBA?>" onmousedown="return false"/>
  </form>
          
          </td>
        <td height="10" valign="bottom" style="font-size:14px">&nbsp;</td>
        <td height="10" colspan="3" valign="bottom" style="font-size:14px">&nbsp;</td>
      </tr>
      <!--<tr>
			<td width="170" align="left" valign="left" style="font-size:14px"><?php //echo "Divisi Dipilih: ".$_SESSION['AFD']; ?></td>
	  </tr>-->
      <tr>
        <td height="19" colspan="9" valign="bottom" style="font-size:14px ; border-bottom:solid #000">PERIODE</td>
      </tr>
      <form id="form1" name="form1" method="post" action="dofindbccrestan.php">
      <tr>
        <td height="14" valign="top" style="font-size:14px">Start Date</td>
        <td height="14" valign="top" style="font-size:14px">:</td>
        <td height="14" colspan="3" valign="top" style="font-size:14px">
            <input type="text" name="date1" id="datepicker" class="box_field" /> 
            <?php if(isset($_GET["date1"])){ echo "value='$_GET[date1]'"; }?>
            
        </td>
        <td height="14" valign="bottom" style="font-size:14px">&nbsp;</td>
        <td height="14" colspan="3" valign="bottom" style="font-size:14px">&nbsp;</td>
      </tr>
      <tr>
        <td height="15" valign="top" style="font-size:14px">End Date</td>
        <td height="15" valign="top" style="font-size:14px">:</td>
        <td height="15" colspan="3" valign="top" style="font-size:14px">
            <input type="text" name="date2" id="datepicker2" class="box_field"  />
            <?php if(isset($_GET["date2"])){ echo "value='$_GET[date2]'"; }?>
           
        </td>
        <td height="15" valign="bottom" style="font-size:14px">&nbsp;</td>
        <td height="15" colspan="3" valign="bottom" style="font-size:14px" align="right"><input type="submit" name="button6" id="button6" value="TAMPILKAN" style="width:120px; height: 30px; font-size:18px" /></td>
      </tr>
      
      </form>
      
      
      <?php
	  
	  if($sdate1 != ""){
	  echo
      "
	  <tr>
        <td height=\"21\" colspan=\"9\" valign=\"bottom\" style=\"font-size:14px ; border-bottom:solid #000\">Tanggal Dipilih</td>
      </tr>
	  <tr>
        <td valign=\"top\" style=\"font-size:14px\">Start</td>
        <td valign=\"top\" style=\"font-size:14px\">:</td>
        <td colspan=\"5\" valign=\"top\" style=\"font-size:14px\">$sdate1</td>
        <td valign=\"bottom\" style=\"font-size:14px\">&nbsp;</td>
        <td align=\"right\" valign=\"bottom\" style=\"font-size:14px\">&nbsp;</td>
      </tr>
      <tr>
        <td valign=\"top\" style=\"font-size:14px\">End</td>
        <td valign=\"top\" style=\"font-size:14px\">:</td>
        <td colspan=\"5\" valign=\"top\" style=\"font-size:14px\">$sdate2</td>
        <td valign=\"bottom\" style=\"font-size:14px\">&nbsp;</td>
        <td align=\"right\" valign=\"bottom\" style=\"font-size:14px\">&nbsp;</td>
      </tr>"
      ;
	  }
      ?>
      <tr>
        <td height="27" colspan="9" valign="bottom" style="font-size:14px">&nbsp;</td>
      </tr>
	  
        
        
        
        
        
        <tr>
        <td colspan="9" valign="top">
        
        
          <?php
if($rowBCCRestan > 0){
	
echo "
		<table width=\"1134\" border=\"1\" bordercolor=\"#9CC346\">
          <tr bgcolor=\"#9CC346\">
            <td width=\"50\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">Company Code</td>
			<td width=\"50\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">Business Area</td>
			<td width=\"50\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">Divisi</td>
            <td width=\"100\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">Tanggal Panen</td>
            <td width=\"50\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">Blok</td>
			<td width=\"150\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">Mandor</td>
			<td width=\"150\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">No.BCC</td>
				<td width=\"80\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">TBS (JJG)</td>
					<td width=\"80\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">BRD (KG)</td>
						<td width=\"100\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">ESTIMASI BERAT(KG)</td>
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
	
echo "<tr style=\"font-size:14px\" bgcolor=$bg>";
echo "<td align=\"center\">&nbsp;$COMPANY_CODE[$xJAN]</td>
            <td align=\"center\">&nbsp;$BUSINESS_AREA[$xJAN]</td>
            <td align=\"center\">&nbsp;$DIVISI[$xJAN]</td>
			<td align=\"center\">&nbsp;$TGL_PANEN[$xJAN]</td>
			<td align=\"center\">&nbsp;$BLOK[$xJAN]</td>
			<td align=\"center\">&nbsp;$MANDOR[$xJAN]</td>
			<td align=\"center\">&nbsp;$fixedBCC</td>
		    <td align=\"center\">&nbsp;$TBS[$xJAN]</td>
			<td align=\"center\">&nbsp;$BRD[$xJAN]</td>
			<td align=\"center\">&nbsp;$ESTIMASI_BERAT[$xJAN]</td>
            ";

}
echo "</tr></table>";
}
?>
        </td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right"><table width="223" border="0">
          <tr>
            <td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="daftarbccrestan.php?page=back">
          <input type="button" name="button5" id="button5" value="&lt;&lt; Back" style="width:70px; background-color:#9CC346"/>
        </a></td>
        <td width="65" align="center" valign="middle" style="background-color:#9CC346; font-size:12px"><span style="padding-top:0px">Page <?=$sesPageres+1?> of <?=$totalpage?></span></td>
        <td width="74" align="left" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="daftarbccrestan.php?page=next"></a><a href="daftarbccrestan.php?page=next">
          <input type="button" name="button4" id="button4" value="Next &gt;&gt;" style="width:70px; background-color:#9CC346"/>
        </a></td>
          </tr>
        </table></td>
        
      </tr>
      <tr>
        <td colspan="9" align="center">
          
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