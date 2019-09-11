<?php
session_start();
include("../include/Header.php");
if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name'])){	
$Job_Code = $_SESSION['Job_Code'];
$username = $_SESSION['NIK'];	
$Emp_Name = $_SESSION['Name'];
$Comp_Name = $_SESSION['Comp_Name'];
$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
$Date = $_SESSION['Date'];
$ID_Group_BA = $_SESSION['ID_Group_BA'];
	
	if($username == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:../index.php");
	}
	
	else{
		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
			
		$JobAuthorityView = "";
		if(isset($_POST["JobAuthorityView"])){
			$JobAuthorityView = $_POST["JobAuthorityView"];
			$_SESSION["JobAuthorityView"] = $JobAuthorityView;
		}
		if(isset($_SESSION["JobAuthorityView"])){
			$JobAuthorityView = $_SESSION["JobAuthorityView"];
		}
		
		if($JobAuthorityView == TRUE){	
				
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
						$sql_t_JA_No  = "SELECT * FROM t_Authority where AUTHORITY != 9 order by AUTHORITY";
					}
					else{
					$conditionsessionJA= "AND AUTHORITY = '$ses_JA'";
					$sql_t_sJA  = "SELECT * FROM t_Authority WHERE AUTHORITY = '$ses_JA' order by AUTHORITY";
					//$result_t_sJA  = select_data($con,$sql_t_sJA);
					
					$result_t_sJA = oci_parse($con, $sql_t_sJA);
					oci_execute($result_t_sJA, OCI_DEFAULT);
					oci_fetch($result_t_sJA);
					
					$sAUTHORITY		= oci_result($result_t_sJA, "AUTHORITY");
					$sAUTHORITY_NAME	= oci_result($result_t_sJA, "AUTHORITY_NAME");
					$sAUTHORITY_DESC	= oci_result($result_t_sJA, "AUTHORITY_DESC");
		
		
						
					$optionGetJA= "<option value=\"$sAUTHORITY\" selected=\"selected\">$sAUTHORITY</option>";	
					$sql_t_JA_No  = "SELECT * FROM t_Authority WHERE AUTHORITY != '$ses_JA' and AUTHORITY != 9 order by AUTHORITY";	
					//echo "<br> ses_JA".$ses_JA;
					
					
							if($ses_JA == 1 && $sID_BA !== ""){
								//echo "masuk ses_JA 1";
								$conditionJA = "select * from t_job_activitycode where ACTIVITY_CODE = 'HA' OR ACTIVITY_CODE = 'HA2'"; 
								$conditionBA = "ID_BA = '$sID_BA'";
								$sql_t_AC  = "select * from ($conditionJA) WHERE $conditionBA order by ID_BA";
								
								
							}
							else{
								//echo "masuk ses_JA other";
								$conditionJA = "where ID_BA = '$sID_BA'"; 
								$sql_t_AC  = "select DISTINCT JOB_CODE from t_jobauthority $conditionJA $conditionsessionJA order by JOB_CODE ";
								//echo  $sql_t_AC ;
							}
							$result_t_AC = oci_parse($con, $sql_t_AC);
							oci_execute($result_t_AC, OCI_DEFAULT);
							while(oci_fetch($result_t_AC)){
								//$acID_BA[]		= oci_result($result_t_AC, "ID_BA");
								//$acACTIVITY_CODE[]	= oci_result($result_t_AC, "ACTIVITY_CODE");
								$acJOB_CODE[]	= oci_result($result_t_AC, "JOB_CODE");
							}
							$roweffec_AC = oci_num_rows($result_t_AC);
							
							//echo $sql_t_AC. $roweffec_AC ;
							$_SESSION["roweffec_AC"] = $roweffec_AC;
							
							//send session
							for($xAC = 0; $xAC <  $roweffec_AC; $xAC++){
								$_SESSION["acACTIVITY_CODE$xAC"] = $acACTIVITY_CODE[$xAC];
							}
							//echo $roweffec_AC;
					}
				}
				else{
					$sAUTHORITY = "";
					$sAUTHORITY_NAME = "";
					$sAUTHORITY_DESC = "";
					$optionGetJA= "";	
					
					$roweffec_AC = "";
					
					$sql_t_JA_No  = "SELECT * FROM t_Authority where AUTHORITY != 9 order by AUTHORITY";
					//echo "sAUTHORITY".$sAUTHORITY." and sAUTHORITY_NAME".$sAUTHORITY_NAME;
					
					
				}
				
				$result_t_JA_No = oci_parse($con, $sql_t_JA_No);
				oci_execute($result_t_JA_No, OCI_DEFAULT);
				while(oci_fetch($result_t_JA_No)){
					$AUTHORITY[]		= oci_result($result_t_JA_No, "AUTHORITY");
					$AUTHORITY_NAME[]	= oci_result($result_t_JA_No, "AUTHORITY_NAME");
					$AUTHORITY_DESC[]	= oci_result($result_t_JA_No, "AUTHORITY_DESC");
				}
				$roweffec_JA_No = oci_num_rows($result_t_JA_No);
				//echo $roweffec_JA_No;
		}
		else{
			header("location:../menu/authoritysecure.php");
		}
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
		<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>JOB AUTHORIZATION</strong></span></td>
      </tr>
	  <tr>
        <td height="45" colspan="4" valign="bottom" style="border-bottom:solid #000">LOKASI</td>
      </tr>
      <tr>
        <td width="150" height="29" valign="top">Company Code</td>
		<td width="10" height="29" valign="top">:</td>
		<td><form id="form1" name="form1" method="post" action="viewjobauthority.php">
<?php    

if($roweffec_CC > 1 ){

//	echo "11";	
	$selectoCC = "<select name=\"CC\" id=\"CC\" onchange=\"this.form.submit();\" style=\"width: 150px\">";
	$optionCC = "<option value=\"kosong\">-- select --</option>";
	echo $selectoCC.$optionGetCC.$optionCC;
	
	for($xCC = 0; $xCC <  $roweffec_CC; $xCC++){
		
		echo "<option value=\"$ID_CC[$xCC]\">$ID_CC[$xCC]</option>";
	}
	
	$selectcCC = "</select>";
	echo $selectcCC;
}

else if($roweffec_CC == 1){
	//echo "22";

	//$ID_CC[0]	= oci_result($result_t_CC, "ID_CC");

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

</form>
</td>
		</tr>
		<tr>
        <td width="150" valign="top">Business Area</td>
		<td width="10" height="29" valign="top">:</td>
		<td><form id="form3" name="form3" method="post" action="viewjobauthority.php">
<?php       
if($roweffec_cstBA > 1 ){
		
	$selectoBA = "<select name=\"BA\" id=\"BA\" style=\"width: 150px; \" onchange=\"this.form.submit()\">";
	$optionBA = "<option value=\"kosong\">-- select --</option>";
	echo $selectoBA.$optionGetBA.$optionBA;
	
	for($xBA = 0; $xBA <  $roweffec_cstBA; $xBA++){
		
		echo "<option value=\"$ID_BA[$xBA]\">$ID_BA[$xBA]</option>"; 
	}
	
	$selectcBA = "</select>";
	echo $selectcBA;
}

else if($roweffec_cstBA == 1){

	//$ID_BA[0]	= oci_result($result_t_bussinessarea, "ID_BA");
		
	if($ID_BA[0] == ""){
		echo "Userlogin doesn't have area". $ID_BA[0];
	}
	else{
	$selectoBA = "<select name=\"BA\" id=\"BA\"  style=\"width: 150px\" onchange=\"this.form.submit();\">";
	$optionBA = "<option value=\"kosong\">-- select --</option>";
	echo $selectoBA.$optionGetBA.$optionBA;
	
		echo "<option value=\"$ID_BA[0]\">$ID_BA[0]</option>"; 
	
	$selectcBA = "</select>";
	echo $selectcBA;
	}
}
        
?>
        </form></td>
		</tr>
		<tr>
        <td width="150" align="left" valign="middle">Company Name</td>
        <td width="10" height="29" valign="top">:</td>
		<td><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$sComp_Name?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" onMouseDown="return false"/></td>
      </tr>
      <tr>
        <td height="40" colspan="4" valign="bottom" style="border-bottom:solid #000">KODE OTORISASI</td>
      </tr>
<?php
if($iudvalue == 1){
echo "<tr><td>Last Job Authorization Code : $AUTHORITY[0]<td><tr>";
}
else if($iudvalue == 2){
echo "<tr><td>Last Job Authorization Code : $AUTHORITY[0]<td><tr>";
}

?>  
      <tr style="display:inline">
            <tr>
				<td width="150" valign="top">Job Authorization Code</td>
				<td width="10" height="29" valign="top">:</td>
				<td><form id="form2" name="form2" method="post" action="viewjobauthority.php" >
          <!--onchange="this.form.submit(); -->
<?php       
if($roweffec_JA_No > 1){
	
	$selectoJA_No = "<select name=\"JA_No\" id=\"JA_No\" style=\"width: 150px\" onchange=\"this.form.submit();\">";
	$optionJa_No = "<option value=\"kosong\">-- select --</option>";
	echo $selectoJA_No.$optionGetJA.$optionJa_No;
	
	for($xJAN = 0; $xJAN <  $roweffec_JA_No; $xJAN++){
		
		echo "<option value=\"$AUTHORITY[$xJAN]\">$AUTHORITY[$xJAN]</option>"; 
	}
	echo $optionHO;
	$selectcJA_No = "</select>";
	echo $selectcJA_No;
}

?>        
              </td>
            </tr>
            <tr style="display:none">
              <td>
<input name="iudID_JOBAUTHORITY" type="text" id="iudID_JOBAUTHORITY" value="<?=$iudID_JOBAUTHORITY?>" style="background-color:#CCC; width: 50px; height:25px; font-size:15px" onMouseDown="return false"/>
<input name="iudAUTHORITY" type="text" id="iudAUTHORITY" value="<?=$iudAUTHORITY?>" style="background-color:#CCC; width: 50px; height:25px; font-size:15px" onMouseDown="return false"/>
<input name="iudvalue" type="text" id="iudvalue" value="<?=$iudvalue?>" style="background-color:#CCC; width: 50px; height:25px; font-size:15px" onMouseDown="return false"/>                
              </td>
            </tr>
      </tr>
      <tr>
        <td width="150" valign="top">Job Authorization Name</td>
		<td width="10" height="29" valign="top">:</td>
		<td><input name="Comp_Name2" type="text" id="Comp_Name2" value="<?=$sAUTHORITY_NAME?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" onMouseDown="return false"/></td>
      </tr>
      <tr>
        <td width="150" valign="top">Job Authorization Desc</td>
		<td width="10" height="29" valign="top">:</td>
		<td><input name="Comp_Name3" type="text" id="Comp_Name3" value="<?=$sAUTHORITY_DESC?>" style="background-color:#CCC; width: 500px; height:25px; font-size:15px" onMouseDown="return false"/></td>
      </tr>
      <tr>
        <td height="46" colspan="4" valign="bottom" style="border-bottom:solid #000">DAFTAR JOB CODE</td>
      </tr>
      <tr>
        <td colspan="4">
		
<?php	
$mod = 3;	
//echo "tesss".$roweffec_AC."a".$roweffec_ACx."val".$iudvalue;
if($roweffec_AC > 0 && $ses_JA == 1){
	//	echo "11";	
	$tableo = " <table width=\"1034\" border=\"0\">";	
	echo $tableo;
	$td = '';	
	$hitJ = 0;		
	for($xJAJ = 0; $xJAJ <  $roweffec_AC; $xJAJ++){
	
	$td .= 	"<td height=\"35\"> <input type=\"checkbox\" checked=\"checked\" onclick=\"return false\" name=\"chk$xJAJ\" id=\"chk$xJAJ\" value=\"$acJOB_CODE[$xJAJ]\" /> $acJOB_CODE[$xJAJ] </td>";
	$hitJ++;	
		if (($hitJ % $mod) == 0) {
			print "<tr>$td</tr>";
			$td = '';
			
		}
	}
	$tablec = "</table>";
	print "<tr>$td</tr>";
	echo $tablec;
}

else if($roweffec_AC > 0 && $iudvalue == ""){
//	echo "11";	
	$tableo = " <table width=\"1034\" border=\"0\">";	
	echo $tableo;
	$td = '';	
	$hitJ = 0;		
	for($xJAJ = 0; $xJAJ <  $roweffec_AC; $xJAJ++){
		
		
		$td .= 	"<td height=\"35\"> <input type=\"checkbox\" checked=\"checked\" onclick=\"return false\" name=\"chk$xJAJ\" id=\"chk$xJAJ\" value=\"$acJOB_CODE[$xJAJ]\" /> $acJOB_CODE[$xJAJ] </td>";
		$hitJ++;	
		if (($hitJ % $mod) == 0) {
			print "<tr>$td</tr>";
			$td = '';
			
		}
	}
	$tablec = "</table>";
	print "<tr>$td</tr>";
	echo $tablec;
}

?>
   
        </td>
      </tr>
      <tr>
        <td colspan="4">
        
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