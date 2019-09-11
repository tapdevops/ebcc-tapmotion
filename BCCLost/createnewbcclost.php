<?php
session_start();
include("../include/Header.php");
if(isset($_SESSION['NIK'])){	
$username = $_SESSION['NIK'];	
	
	if($username == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:login.php");
	}
	else{
		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		
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
$(function() {
	$('#datepicker3').datepicker({
		  changeMonth: true,
		  changeYear: true
		});
});
</script>


<script type="text/javascript">

function formSubmit(x)
{
	if(x == 1){
	document.getElementById("formBCCLost").submit();
    }
	if(x == 2)
	{
	document.getElementById("formTampilkan").submit();
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
		<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>CREATE NEW BCC LOSS</strong></span></td>
      </tr>
	  <tr>
        <td height="45" colspan="3" valign="bottom" style="border-bottom:solid #000">LOKASI</td>
		<td height="45" colspan="3" valign="bottom" style="border-bottom:solid #000">PERIODE</td>
      </tr>
	  <form id="formTampilkan" name="formTampilkan" method="post" action="dofindbcclost.php">
      <tr>
        <?php   
			$sql_user_login  = 	"select c.id_cc as COMPANY_CODE, c.id_ba as BUSINESS_AREA, d.comp_name AS COMPANY_NAME, c.nama_ba
								from t_employee a, t_afdeling b, t_bussinessarea c, t_companycode d
								where a.id_ba_afd = b.id_ba_afd and b.id_ba = c.id_ba and c.id_cc = d.id_cc 
								and a.nik = '$username'";
			$result_user_login	= select_data($con,$sql_user_login);
			$company_code		= $result_user_login["COMPANY_CODE"];
			$business_area		= $result_user_login["BUSINESS_AREA"];
			$company_name		= $result_user_login["COMPANY_NAME"];
			$ba_name		= $result_user_login["NAMA_BA"];
			
			$_SESSION["CC"] = $company_code;
			$_SESSION["BA"] = $business_area;
		?>
		<td width="70" height="29" valign="top">Company Code</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="100" align="left" valign="top"><input name="company_code" type="text" id="company_code" value="<?=$company_code?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly></td>
		
		<td width="100" height="29" valign="top">Start Date</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="100" valign="top">
			<?php 	if(isset($_SESSION["date1"]))
					{ 
						$curdate1 = $_SESSION["date1"];
					}
					else
					{
						$curdate1 = "";
					}
			?>
			<input type="text" name="date1" id="datepicker2" class="box_field" value="<?=$curdate1?>"/> 
		</td>
		</tr>
		<tr>
		<td width="70" height="29" valign="top">Company Name</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="100" align="left" valign="top"><input name="company_name" type="text" id="company_name" value="<?=$company_name?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly></td>
		
		<td width="100" height="29" valign="top">End Date</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="100" valign="top">
			<?php 	if(isset($_SESSION["date2"]))
					{ 
						$curdate2 = $_SESSION["date2"];
					}
					else
					{
						$curdate2 = "";
					}
			?>
			<input type="text" name="date2" id="datepicker3" class="box_field" value="<?=$curdate2?>"/> 
		</td>
		</tr>
		<tr>
		<td width="70" height="29" valign="top">Business Area</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="100" align="left" valign="top"><input name="business_area" type="text" id="business_area" value="<?=$business_area?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly></td>
		</tr>
		<tr>
		<td width="70" height="29" valign="top">Business Area Name</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="100" align="left" valign="top"><input name="ba_name" type="text" id="ba_name" value="<?=$ba_name?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly></td>
		</tr>
		
		<tr>
		<td height="30" colspan="6" valign="bottom" style="border-bottom:solid #000">Tampilkan Data</td>
	  </tr>
	  <tr>
		<td colspan="6" align="right"><input type="submit" name="button2" id="button2" value="TAMPILKAN" style="width:120px; height: 30px; font-size:18px;" onclick="formSubmit(2)"/></td>
	  </tr>
	  </form>
	  
      <tr>
        <td height="45" colspan="6" valign="bottom" style="border-bottom:solid #000">DETAIL BCC LOSS</td>
      </tr>
	  <tr>
		<form id="formBCCLost" name="formBCCLost" method="post" action="docreatenewbcclost.php">
		<td width="100" height="29" valign="top">No Document</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="100">
			<?php    
				$sql_sysdate  = "select to_char(SYSDATE,'MM/DD/YYYY') TGL from dual";
				$result_sysdate  = select_data($con,$sql_sysdate);
				$tgl_doc_1 = $result_sysdate["TGL"];
				
				if(isset($_POST["no_doc"]))
				{
					$no_doc = $_POST["no_doc"];
					$sql_bcc_lost  = "SELECT NO_BCC,to_char(TGL_DOC, 'MM/DD/YYYY') AS TGL_DOC,REMARK,CREATED_BY,CREATED_DATE FROM T_BCC_LOST WHERE no_doc='$no_doc'";
					$result_bcc_lost  = select_data($con,$sql_bcc_lost);
					$no_bcc[] 	= $result_bcc_lost["NO_BCC"];
					$tgl_doc[]	= $result_bcc_lost["TGL_DOC"];
					$remark[] = $result_bcc_lost["REMARK"];
					
					$rowBCCLost = oci_num_rows($result_bcc_lost);
				}
				else
				{
					$no_doc = '';
					$no_bcc[] 	= '';
					$tgl_doc[]	= '';
					$remark[] = '';
				}
				
				if(isset($no_doc))
				{
					echo "
					<input type='text' name='no_doc' class='box_field' style='width:350px;' value='$no_doc' maxlength='100'/>
					";
				}
				else
				{
					echo "<input type='text' name='no_doc' class='box_field' style='width:350px;' maxlength='100'/>";
				}
			?>
		</td>
      </tr>
		<tr>
			<td width="100" height="29" valign="top">Tgl Document</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="100"><input type="text" name="tgl_doc" id="datepicker" class="box_field" 
			<?php 
				if($tgl_doc[0]!=='')
				{ 
					echo "value='$tgl_doc[0]'"; 
				}
				else
				{
					echo "value='$tgl_doc_1'";
				}
			?>></td>
		</tr>
		<tr>
		<td width="100" height="29" valign="top">Remark</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="100">
			<?php
				if(isset($remark[0]))
				{
					echo "
					<input type='text' name='remark' class='box_field' style='width:500px;' value='$remark[0]' maxlength='100'/>
					";
				}
				else
				{
					echo "<input type='text' name='remark' class='box_field' style='width:500px;' maxlength='100'/>";
				}
			?>
		</td>
      </tr>
      
      <tr>
        <td height="46" colspan="6" valign="bottom" style="border-bottom:solid #000">DAFTAR BCC RESTAN</td>
      </tr>
      <tr>
        <td colspan="6">
		  <?php
				echo "
					<table width=\"1134px\" border=\"1\" bordercolor=\"#9CC346\" align=\"center\">
					<tbody id=\"scrolling\" style=\"width:1134px\">
					  <tr bgcolor=\"#9CC346\">
						<td width=\"15\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">V</td>
						<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Tanggal</td>
						<td width=\"50\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">AFD</td>
						<td width=\"100\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Blok</td>
						<td width=\"300\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">No BCC</td>
						<td width=\"300\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Nama Mandor</td>
						<td width=\"300\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">Nama Krani Buah</td>
					  </tr>
				";
				if(isset($_SESSION["sql_bcc_lost"]))
				{
					$sql_bcc_restan = $_SESSION["sql_bcc_lost"];
				}
				else
				{
					$sql_bcc_restan = "	SELECT * FROM T_BUSSINESSAREA TBA
										INNER JOIN T_AFDELING TA ON TBA.ID_BA = TA.ID_BA
										INNER JOIN T_BLOK TB ON TA.ID_BA_AFD = TB.ID_BA_AFD 
										INNER JOIN T_DETAIL_RENCANA_PANEN TDRP ON TB.ID_BA_AFD_BLOK = TDRP.ID_BA_AFD_BLOK
										INNER JOIN T_HEADER_RENCANA_PANEN THRP ON THRP.ID_RENCANA = TDRP.ID_RENCANA
										INNER JOIN T_HASIL_PANEN THP ON TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC AND THP.ID_RENCANA = THRP.ID_RENCANA
										WHERE THP.STATUS_BCC = 'RESTAN' and TBA.ID_BA='$business_area'
										ORDER BY THRP.TANGGAL_RENCANA, THP.NO_BCC";
					//echo $sql_bcc_restan;
				}
				$result_bcc_restan = oci_parse($con, $sql_bcc_restan);
				oci_execute($result_bcc_restan, OCI_DEFAULT);
				while(oci_fetch($result_bcc_restan))
				{
					$sIdRencana[]	= oci_result($result_bcc_restan, "ID_RENCANA");
					$sTglRencana[]	= oci_result($result_bcc_restan, "TANGGAL_RENCANA");
					$sAFD[]	= oci_result($result_bcc_restan, "ID_AFD");
					$sBlok[]	= oci_result($result_bcc_restan, "ID_BLOK");
					$sBCCRestan[]	= oci_result($result_bcc_restan, "NO_BCC");
					$sNIKMandor[]	= oci_result($result_bcc_restan, "NIK_MANDOR");
					$sNIKKrani[]	= oci_result($result_bcc_restan, "NIK_KERANI_BUAH");
				}
				$rowBCCRestan = oci_num_rows($result_bcc_restan);
				
				for($xJAJ = 0; $xJAJ <  $rowBCCRestan; $xJAJ++)
				{
					$no = $xJAJ +1;

					if(($xJAJ % 2) == 0){
						$bg = "#F0F3EC";
					}
					else{
						$bg = "#DEE7D2";
					}

					$fixedBCC = separator($sBCCRestan[$xJAJ]);
					
					$result_mandor	= select_data($con,"select EMP_NAME from t_employee WHERE NIK = '$sNIKMandor[$xJAJ]'");
					$Mandor	= $result_mandor["EMP_NAME"];
					
					$result_krani	= select_data($con,"select EMP_NAME from t_employee WHERE NIK = '$sNIKKrani[$xJAJ]'");
					$Krani	= $result_krani["EMP_NAME"];
					
					
					echo "	<tr style=\"font-size:12px\" bgcolor=$bg>";
					echo "	<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\"> <input type=\"checkbox\" name=\"chk$xJAJ\" id=\"chk$xJAJ\" value=\"$sBCCRestan[$xJAJ]\" /></td>
							<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\"> <input type=\"hidden\" name=\"idrencana$xJAJ\" id=\"idrencana$xJAJ\" value=\"$sIdRencana[$xJAJ]\" />&nbsp;$sTglRencana[$xJAJ]</td>
							<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$sAFD[$xJAJ]</td>
							<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$sBlok[$xJAJ]</td>
							<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$fixedBCC</td>
							<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$Mandor</td>
							<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">&nbsp;$Krani</td>
							";
				}
				echo "</tr></tbody></table>";
				
				$_SESSION["rowBCCRestanLost"] = $rowBCCRestan;
		?>
        </form>
          <table width="1034" border="0">
          <tr>
            <td colspan="4">&nbsp;</td>
          </tr>
      </table>
          
        </td>
      </tr>
      <tr>
        <td colspan="4">
<?php

if(isset($_SESSION['err'])){
	$err = $_SESSION['err'];
	if($err!=null)
	{
		echo $err;
		unset($_SESSION['err']);
	}
}
//echo $_SESSION["ctr"];
if(isset($_SESSION["ctr"])){
	$ctr = $_SESSION["ctr"];
	//echo $ctr;
	if($ctr!=null){
		//for($ins = 0 ; $ins < $ctr; $ins++ ){
			if(isset($_SESSION["insert0"])){
				$insert = $_SESSION["insert0"];
				if($insert!=null){
					echo $insert;
					unset($_SESSION["insert0"]);
				}
				unset($_SESSION["insert0"]);
				unset($_SESSION["ctr"]);
			}
		//}
	}
	else{
		echo "insert success";
	}
}
?>
        </td>
      </tr>
	  <tr>
	  <td colspan="6" align="left"><input type="submit" name="button1" id="button1" value="SIMPAN" style="width:120px; height: 30px; font-size:18px;" onclick="formSubmit(1)"/></td>
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