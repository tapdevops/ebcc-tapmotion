<?php
session_start();
include("../include/Header.php");
if(isset($_SESSION['LoginName']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name']) && isset($_SESSION['roweffec_t_nik_passwd'])){	
$LoginName = $_SESSION['LoginName'];
$username = $_SESSION['NIK'];	
$Emp_Name = $_SESSION['Name'];
//$Jenis_Login = $_SESSION['Jenis_Login'];
$Comp_Name = $_SESSION['Comp_Name'];
$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
$Date = $_SESSION['Date'];
$ID_Group_BA = $_SESSION['ID_Group_BA'];
$Number_Of_Login = $_SESSION['Number_Of_Login'];
$subID_Afd = $_SESSION['subID_Afd'];
$roweffec_jenis_login = $_SESSION['roweffec_t_nik_passwd'];

	if($username == "" || $LoginName == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:../index.php");
	}
	else{
		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		
		// open secure user matriks
		$ChangePassword = "";
		if(isset($_POST["ChangePassword"])){
			$ChangePassword = $_POST["ChangePassword"];
			$_SESSION["ChangePassword"] = $ChangePassword;
		}
		if(isset($_SESSION["ChangePassword"])){
			$ChangePassword = $_SESSION["ChangePassword"];
		}
		
		if($ChangePassword == TRUE){
		
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
					$conditionCC2 = "";
					
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
					$conditionCC2 = "and tc.ID_CC = '$sID_CC'";
				}
			}
			else{
				$sID_CC = "";
				$sComp_Name = "";
				$optionGetCC= "";	
				
				$conditionCC = "";
				$conditionCC2 = "";
				
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
			}
			
			if(isset($_SESSION['BA'])){
				$ses_BA = $_SESSION['BA'];
				$sql_t_sBA  = "SELECT ID_BA,Nama_BA FROM t_bussinessarea $conditionCC and ID_BA = '$ses_BA'";
				//$result_t_sBA  = select_data($con,$sql_t_sBA);
				
				$result_t_sBA = oci_parse($con, $sql_t_sBA);
				oci_execute($result_t_sBA, OCI_DEFAULT);
				oci_fetch($result_t_sBA);
				
				$sID_BA		= oci_result($result_t_sBA, "ID_BA");
				$sBA_Name	= oci_result($result_t_sBA, "NAMA_BA");
	
				$optionGetBA= "<option value=\"$sID_BA\" selected=\"selected\">$sID_BA</option>";	
				$sql_t_BA  = "SELECT ID_BA FROM t_bussinessarea $conditionCC and ID_BA != '$ses_BA' ";	
				
				$conditionBA = "and tb.ID_BA = '$sID_BA'";
				$conditionBA2 = "and ID_BA = '$sID_BA'";
				//echo "ses_BA".$ses_BA. $sql_t_sBA;
				if($ses_BA == "ALL"){
					$sql_t_sBA = "SELECT ID_BA FROM t_bussinessarea $conditionCC";	
					$optionGetBA= "";	
					$conditionBA = "";
					$conditionBA2 = "";
				}
				else{
					$sql_t_sBA  = "SELECT ID_BA,Nama_BA FROM t_bussinessarea $conditionCC and ID_BA = '$ses_BA'";
				}
			}
			else{
				$sID_BA = "";
				$optionGetBA= "";	
				$conditionBA = "";
				$conditionBA2 = "";
				$sql_t_BA  = "SELECT ID_BA FROM t_bussinessarea $conditionCC";	
				//echo "sID_BA".$sID_BA;
			}
	
			$result_t_bussinessarea = oci_parse($con, $sql_t_BA);
			oci_execute($result_t_bussinessarea, OCI_DEFAULT);
			while(oci_fetch($result_t_bussinessarea)){
				$ID_BA[]	= oci_result($result_t_bussinessarea, "ID_BA");
			}
			$roweffec_cstBA = oci_num_rows($result_t_bussinessarea);
			
			$conditionAfd = "";
			$optionGetAfd = "";
			if(isset($_POST["Afdeling"])){
			$_SESSION["Afdeling"] = $_POST["Afdeling"];
			}
			
			if(isset($_SESSION["Afdeling"])){
				$sesAfdeling = $_SESSION["Afdeling"];
				
				$Ses_sql_afd = "select * from t_afdeling where ID_AFD = '$sesAfdeling' $conditionBA2 ORDER BY ID_BA";
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
					$sql_afd = "select * from t_afdeling where ID_AFD like '%' $conditionBA2 ORDER BY ID_BA";
					$conditionAfd = "";
					$optionGetAfd = "";
				}
				else{
					$sql_afd = "select * from t_afdeling where ID_AFD != '$sesAfdeling' $conditionBA2 ORDER BY ID_BA";
				}
			}
			else{
				$sql_afd = "select * from t_afdeling where ID_AFD like '%' $conditionBA2 ORDER BY ID_BA";
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
			
			$optionGetMD = "";
			if(isset($_POST["LoginEmployee"])){
			$_SESSION["LoginEmployee"] = $_POST["LoginEmployee"];
			}
			
	//echo "NIK: ".$_POST["LoginEmployee"]." + ".$conditionCC2." + ".$conditionBA." + ".$conditionAfd;
			
			if(isset($_SESSION["LoginEmployee"])){
				$sesLoginEmployee = $_SESSION["LoginEmployee"];
				
				$Ses_sql_MD = "SELECT LOGIN_NAME FROM T_USER te
				inner  join  t_afdeling  ta on  te.ID_BA_AFD = ta.ID_BA_AFD
				inner  join  t_bussinessarea  tb on  ta.id_ba = tb.id_ba
				inner  join  t_companycode  tc on  tb.id_cc =  tc.id_cc
				where te.LOGIN_NAME = '$sesLoginEmployee' $conditionCC2 $conditionBA $conditionAfd 
				GROUP BY LOGIN_NAME ORDER BY te.LOGIN_NAME";
//echo 	$Ses_sql_MD;			
				$Ses_result_MD = oci_parse($con, $Ses_sql_MD);
				oci_execute($Ses_result_MD, OCI_DEFAULT);
				while (oci_fetch($Ses_result_MD)) {	
					$Login_Employee[] 		= oci_result($Ses_result_MD, "LOGIN_NAME");
				}
				$optionGetMD = "<option value=\"$Login_Employee[0]\" selected=\"selected\">$Login_Employee[0]</option>";
				$cur_Login = $Login_Employee[0];
				if($sesLoginEmployee == "ALL"){
					$sql_MD = "SELECT LOGIN_NAME FROM T_USER te
					inner  join  t_afdeling  ta on  te.ID_BA_AFD =   ta.ID_BA_AFD
					inner  join  t_bussinessarea  tb on  ta.id_ba =  tb.id_ba
					inner  join  t_companycode  tc on  tb.id_cc =  tc.id_cc
					where te.LOGIN_NAME like '%' $conditionCC2 $conditionBA $conditionAfd 
					GROUP BY LOGIN_NAME ORDER BY te.LOGIN_NAME";
					$optionGetMD = "";
				}
				else{
					$sql_MD = "SELECT LOGIN_NAME FROM T_USER te
					inner  join  t_afdeling  ta on  te.ID_BA_AFD =   ta.ID_BA_AFD
					inner  join  t_bussinessarea  tb on  ta.id_ba =  tb.id_ba
					inner  join  t_companycode  tc on  tb.id_cc =  tc.id_cc
					where te.LOGIN_NAME != '$sesLoginEmployee' $conditionCC2 $conditionBA $conditionAfd 
					GROUP BY LOGIN_NAME ORDER BY te.LOGIN_NAME";
				}
			}
			else{
				$sql_MD = "SELECT LOGIN_NAME FROM T_USER te
					inner  join  t_afdeling  ta on  te.ID_BA_AFD =   ta.ID_BA_AFD
					inner  join  t_bussinessarea  tb on  ta.id_ba =  tb.id_ba
					inner  join  t_companycode  tc on  tb.id_cc =  tc.id_cc
					where te.LOGIN_NAME like '%' $conditionCC2 $conditionBA $conditionAfd 
					GROUP BY LOGIN_NAME ORDER BY te.LOGIN_NAME";
					
			}
	
			//echo " here".$sql_MD;
			$result_MD = oci_parse($con, $sql_MD);
			oci_execute($result_MD, OCI_DEFAULT);
			while (oci_fetch($result_MD)) {	
				$Login_Employee[] 		= oci_result($result_MD, "LOGIN_NAME");
			}
			$cur_Login = $Login_Employee[0];
			$jumlahMD = oci_num_rows($result_MD);
			//echo "here".$sql_MD."jumlah".$jumlahMD;
		
		}
		else{
			header("location:../menu/authoritysecure.php");
		} // close secure user matriks
	}


?>
<script type="text/javascript">

function formSubmit(x)
{
	if(x == 1){
	document.getElementById("formSave").submit();
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
		<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>CHANGE PASSWORD LOGIN</strong></span></td>
      </tr>
      <tr>
        <td height="45" colspan="9" valign="bottom" style="border-bottom:solid #000">LOKASI</td>
      </tr>
      <?php
	  //echo $roweffec_jenis_login;
	  $xdone = 0;
for($y =0; $y<$roweffec_jenis_login; $y++)
{
	$Jenis_Login[$y] = $_SESSION["Jenis_LoginHead$y"];
	//echo "test".$xdone;
	if(($Jenis_Login[$y]==9) && ($xdone == 0))
	{	
		$xdone = 1;
?>
      <tr>
        <td width="140" valign="top" style="font-size:14px">Company Code 1</td>
        <td width="9" valign="top" style="font-size:14px">:</td>
        <td valign="top" style="font-size:16px"><form id="form3" name="form3" method="post" action="changepass.php" style="height:10px">
          <?php    

		if($roweffec_CC > 1 )
		{
			//	echo "11";	
			$selectoCC = "<select name=\"CC\" id=\"CC\" onchange=\"this.form.submit();\" style=\"width: 60px; display:$disCC \">";
			$optionCC = "<option value=\"kosong\">-- select --</option>";
			echo $selectoCC.$optionGetCC.$optionCC;
			
			for($xCC = 0; $xCC <  $roweffec_CC; $xCC++)
			{
				echo "<option value=\"$ID_CC[$xCC]\">$ID_CC[$xCC]</option>";
			}
		
			$selectcCC = "</select>";
			echo $selectcCC;
		}
	
		else if($roweffec_CC == 1)
		{	
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
        </form></td>
        <td width="137" align="left" valign="middle" style="font-size:14px">Company Name </td>
        <td width="6" align="left" valign="middle" style="font-size:14px">:</td>
        <td width="367" valign="top" style="font-size:14px"><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$sComp_Name?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" onmousedown="return false"/></td>
      </tr>
      <tr>
        <td width="140" valign="top" style="font-size:14px">Business Area </td>
        <td width="9" valign="top" style="font-size:14px">:</td>
        <td valign="top" style="font-size:16px"><form id="form3" name="form3" method="post" action="changepass.php" style="height:10px">
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
        </form></td>
        <td width="137" align="left" valign="middle" style="font-size:14px">BA Name </td>
        <td width="6" align="left" valign="middle" style="font-size:14px">:</td>
        <td width="367" valign="top" style="font-size:14px"><input name="BA_Name" type="text" id="BA_Name" value="<?=$sBA_Name?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" onmousedown="return false"/></td>
      </tr>
      <tr>
        <td width="140" valign="top" style="font-size:14px">Afdeling </td>
        <td width="9" valign="top" style="font-size:14px">:</td>
        <td valign="top" style="font-size:16px"><form id="form1" name="form1" method="post" action="" style="height:10px">
          <?php
		if($jumlahAfd > 0 ){
			$selectoAfd = "<select name=\"Afdeling\" id=\"Afdeling\" onchange=\"this.form.submit();\" style=\"width: 50px; visibility:visible; font-size: 14px; height: 20px \">";
			$optiondefAfd = "<option value=\"ALL\">-- ALL --</option>";
			echo $selectoAfd.$optionGetAfd.$optiondefAfd;
			for($xAfd = 0; $xAfd < $jumlahAfd; $xAfd++){
				echo "<option value=\"$ID_Afd[$xAfd]\">$ID_Afd[$xAfd]</option>"; 
			}
			$selectcAfd = "</select>";
			echo $selectcAfd;
		}           
?>
        </form></td>
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="140" valign="top" style="font-size:14px">Login Name </td>
        <td width="9" valign="top" style="font-size:14px">:</td>
        <td valign="top" style="font-size:16px"><!--<form id="formPG" name="formPG" method="post" action="savechange.php">-->
          <form id="form1" name="form1" method="post" action="" style="height:10px">
            <?php
		if($jumlahMD > 0 ){
			$selectoMD = "<select name=\"LoginEmployee\" id=\"LoginEmployee\" onchange=\"this.form.submit();\" style=\"width: 120px; visibility:visible; font-size: 14px; height: 20px \">";
			echo $selectoMD.$optionGetMD;
			for($xMD = 0; $xMD < $jumlahMD; $xMD++){
				echo "<option value=\"$Login_Employee[$xMD]\">$Login_Employee[$xMD]</option>"; 
			}
			$selectcMD = "</select>";
			echo $selectcMD;
		}   
		else
		{
			$selectoMD = "<select name=\"LoginEmployee\" id=\"LoginEmployee\" onchange=\"this.form.submit();\" style=\"visibility:visible; font-size: 15px; height: 25px \">";
			echo $selectoMD.$optionGetMD;
			$selectcMD = "</select>";
			echo $selectcMD;
		}
?>
          </form></td>
          <td colspan="2">&nbsp;</td>
          <td>&nbsp;</td>
        <?php
		$sql_password  = "SELECT Passwd FROM t_user WHERE LOGIN_NAME = '$cur_Login'";
		$result_password  = select_data($con,$sql_password);
		$sPass 			= $result_password["PASSWD"];
		
		$_SESSION["LoginEmployee"] = $cur_Login;
		//echo $cur_Login." - ".$sql_password." - ".$sql_MD;
	}//close if($Jenis_Login==9)			
	else if($xdone == 0)
	{
	$xdone = 1;
?>
      </tr>
      <tr>
        <?php   
		$sql_user_login  = 	"select c.id_cc as COMPANY_CODE, c.id_ba as BUSINESS_AREA, d.comp_name AS COMPANY_NAME
							from T_USER a, t_afdeling b, t_bussinessarea c, t_companycode d
							where a.id_ba_afd = b.id_ba_afd and b.id_ba = c.id_ba and c.id_cc = d.id_cc 
							and a.LOGIN_NAME = '$LoginName'";
		$result_user_login	= select_data($con,$sql_user_login);
		$company_code		= $result_user_login["COMPANY_CODE"];
		$business_area		= $result_user_login["BUSINESS_AREA"];
		$company_name		= $result_user_login["COMPANY_NAME"];
		
		$sql_password  = "SELECT Passwd FROM t_user WHERE LOGIN_NAME = '$LoginName'";
		$result_password  = select_data($con,$sql_password);
		$sPass 			= $result_password["PASSWD"];
		
		$_SESSION["LoginEmployee"] = $LoginName;
?>
        <td width="140" align="left" valign="top" style="font-size:14px">Company Code 2</td>
        <td width="9" align="left" valign="top" style="font-size:14px">:</td>
        <td width="359" align="left" valign="top" style="font-size:14px"><input name="company_code" type="text" id="company_code" value="<?=$company_code?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly="readonly" /></td>
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="140" align="left" valign="top" style="font-size:14px">Business Area </td>
        <td width="9" align="left" valign="top" style="font-size:14px">:</td>
        <td width="359" align="left" valign="top" style="font-size:14px"><input name="business_area" type="text" id="business_area" value="<?=$business_area?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly="readonly" /></td>
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="140" align="left" valign="top" style="font-size:14px">Company Name </td>
        <td width="9" align="left" valign="top" style="font-size:14px">:</td>
        <td width="359" align="left" valign="top" style="font-size:14px"><input name="company_name" type="text" id="company_name" value="<?=$company_name?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly="readonly" /></td>
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="140" align="left" valign="top" style="font-size:14px">Login Name </td>
        <td width="9" align="left" valign="top" style="font-size:14px">:</td>
        <td width="359" align="left" valign="top" style="font-size:14px"><input name="LoginEmployee" type="text" id="LoginEmployee" value="<?=$LoginName?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly="readonly" /></td>
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php
	}//close else
}//close for($y =0; $y<$roweffec_jenis_login; $y++)
?>
      <tr>
        <td width="140" align="left" valign="middle" style="font-size:14px">Password</td>
        <td width="9" align="left" valign="middle" style="font-size:14px">:</td>
        <form id="formSave" name="formSave" method="post" action="savechange.php">
          <td width="359" valign="top" style="font-size:14px"><input name="Pass" type="text" id="Pass" value="<?=$sPass?>" style="width: 350px; height:15px; font-size:14px"/></td>
          <td colspan="2">&nbsp;</td>
          <td>&nbsp;</td>
        </form>
      </tr>
	  
      <tr>
        <td colspan="6"><?php

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
?></td>
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