<?php
session_start();
include("../include/Header.php");
if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name'])){	
$Job_Code = $_SESSION['Job_Code'];
$username = $_SESSION['NIK'];	
$Emp_Name = $_SESSION['Name'];
//$Jenis_Login = $_SESSION['Jenis_Login'];
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
		
		$sql_user_login  = 	"select c.id_cc as COMPANY_CODE, c.id_ba as BUSINESS_AREA, d.comp_name AS COMPANY_NAME
							from t_employee a, t_afdeling b, t_bussinessarea c, t_companycode d
							where a.id_ba_afd = b.id_ba_afd and b.id_ba = c.id_ba and c.id_cc = d.id_cc 
							and a.nik = '$username'";
		$result_user_login	= select_data($con,$sql_user_login);
		$company_code		= $result_user_login["COMPANY_CODE"];
		$business_area		= $result_user_login["BUSINESS_AREA"];
		$company_name		= $result_user_login["COMPANY_NAME"];
			
			
		if(isset($_SESSION["sql_t_BCC"]))	{
			$sql_t_BCC = $_SESSION["sql_t_BCC"];

			$result_t_BCC = oci_parse($con, $sql_t_BCC);
			oci_execute($result_t_BCC, OCI_DEFAULT);
			while(oci_fetch($result_t_BCC)){
				$TANGGAL_RENCANA[] 		= oci_result($result_t_BCC, "TANGGAL");
				$NO_BCC[] 				= oci_result($result_t_BCC, "NO_BCC");
				$NAMA_PEMANEN[] 		= oci_result($result_t_BCC, "NAMA_PEMANEN");
				$NAMA_MANDOR[] 			= oci_result($result_t_BCC, "NAMA_MANDOR");
				$NIK_PEMANEN[] 		= oci_result($result_t_BCC, "NIK_PEMANEN");
				$NIK_MANDOR[] 			= oci_result($result_t_BCC, "NIK_MANDOR");
			}
			$roweffec_BCC = oci_num_rows($result_t_BCC);
		}
		else{
			$_SESSION[err] = "Please check input value!";
			header("location:KoreksiBCCFil.php");
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
		<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>KOREKSI DATA BCC</strong></span></td>
      </tr>

	<tr>
	<td width="130" height="29" valign="top" >Company Code</td>
	<td width="7" height="29" valign="top" >:</td>
	<td width="355" align="left" valign="top" ><input name="company_code" type="text" id="company_code" value="<?=$company_code?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly="readonly" />
	</td>
	</tr>
	<tr>
	<td width="130" height="29" valign="top">Business Area</td>
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
        <td colspan="4" valign="top"><table width="1134" border="0">
		<tbody id="scrolling" style="width:1134px">
          <tr bgcolor="#9CC346">
            <td width="44" align="center" style="font-size:14px" id="bordertable">No.</td>
            <td width="91" align="center" style="font-size:14px" id="bordertable">Tanggal</td>
            <td width="196" align="center" style="font-size:14px" id="bordertable">No BCC</td>
            <td width="200" align="center" style="font-size:14px" id="bordertable">NIK Pemanen</td>
            <td width="250" align="center" style="font-size:14px" id="bordertable">Nama Pemanen</td>
            <td width="200" align="center" style="font-size:14px" id="bordertable">NIK Mandor</td>
            <td width="250" align="center" style="font-size:14px" id="bordertable">Nama Mandor</td>
            <td width="99" id="bordertable">&nbsp;</td>
          </tr>
          <?php
//$endPage = $calPage + $pagesize;
for($xBCC = 0; $xBCC <  $roweffec_BCC; $xBCC++){
	$no = $xBCC +1;
	
	if(($xBCC % 2) == 0){
		$bg = "#F0F3EC";
	}
	else{
		$bg = "#DEE7D2";
	}
	
	$fixedBCC = separator($NO_BCC[$xBCC]);
	
echo "<tr style=\"font-size:12px\" bgcolor=$bg>";
echo "<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">$no</td>
            <td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">$TANGGAL_RENCANA[$xBCC]</td>
            <td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">$fixedBCC</td>
            <td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">$NIK_PEMANEN[$xBCC]</td>
            <td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">$NAMA_PEMANEN[$xBCC]</td>
            <td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">$NIK_MANDOR[$xBCC]</td>
			<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">$NAMA_MANDOR[$xBCC]</td>
			
			<form id=\"formNOBCC$xBCC\" name=\"formNOBCC$xBCC\" method=\"post\" action=\"KoreksiBCCSelect.php\">
				<input name=\"editNO_BCC\" type=\"text\" id=\"editNO_BCC\" value=\"$NO_BCC[$xBCC]\" style=\"display:none\"/>
				<td id=\"bordertable\">
				<a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formNOBCC$xBCC') .submit()\">
				<input type=\"button\" name=\"button\" id=\"button\" value=\"Koreksi\" style=\"width:90px; height:25px; font-size:14px\"/>
				</a>
				</td>
			</form>
			";

}
echo "</tr>";
?>
        </tbody>
		</table></td>
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

