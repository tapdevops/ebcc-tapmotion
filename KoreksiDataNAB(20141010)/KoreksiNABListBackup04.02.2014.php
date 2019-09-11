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
			
		if(isset($_SESSION["sql_t_NABList"])){
			$sql_t_NAB = $_SESSION["sql_t_NABList"];
			$result_t_NAB = oci_parse($con, $sql_t_NAB);
			oci_execute($result_t_NAB, OCI_DEFAULT);
			while(oci_fetch($result_t_NAB)){
				$TANGGAL_RENCANA[] 		= oci_result($result_t_NAB, "TGL_NAB");
				$NO_NAB[] 				= oci_result($result_t_NAB, "NO_NAB");
				$ID_NAB_TGL[] 				= oci_result($result_t_NAB, "ID_NAB_TGL");
				$NO_POLISI[] 		= oci_result($result_t_NAB, "NO_POLISI");
			}
			$roweffec_NAB = oci_num_rows($result_t_NAB);
		}
		else{
			$_SESSION[err] = "Please check input value!";
			header("location:KoreksiNABFil.php");
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
		<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>KOREKSI DATA NAB</strong></span></td>
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
	<td width="7" height="29" valign="top">:</td>
	<td width="355" align="left" valign="top" ><input name="company_name" type="text" id="company_name" value="<?=$company_name?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly="readonly" />
	</td>
	</tr>
	
      <tr>
        <td colspan="4" valign="top" align="center"><table width="1134" border="0">
		<tbody id="scrolling" style="width:1134px" >
			<?php
			if($roweffec_NAB > 0){
			echo "<tr bgcolor=\"#9CC346\">
						<td width=\"100\" align=\"center\" style=\"font-size:14px; background-image:url(../image/small-bar2.png)\" id=\"bordertable\">No.</td>
						<td width=\"200\" align=\"center\" style=\"font-size:14px; background-image:url(../image/small-bar2.png)\" id=\"bordertable\">Tanggal</td>
						<td width=\"300\" align=\"center\" style=\"font-size:14px; background-image:url(../image/small-bar2.png)\" id=\"bordertable\">No NAB</td>
						<td width=\"400\" align=\"center\" style=\"font-size:14px; background-image:url(../image/small-bar2.png)\" id=\"bordertable\">No Polisi</td>
						<td width=\"150\" align=\"center\" style=\"background-image:url(../image/small-bar2.png)\" id=\"bordertable\">&nbsp;</td>
					  </tr>";		  
					  
			//$endPage = $calPage + $pagesize;
			for($xNAB = 0; $xNAB <  $roweffec_NAB; $xNAB++){
				$no = $xNAB +1;
				
				if(($xNAB % 2) == 0){
					$bg = "#F0F3EC";
				}
				else{
					$bg = "#DEE7D2";
				}
				
			echo "<tr style=\"font-size:12px\" bgcolor=$bg >";
			echo "<td align=\"center\" id=\"bordertable\">$no</td>
				<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">$TANGGAL_RENCANA[$xNAB]</td>
				<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">$NO_NAB[$xNAB]</td>
				<td align=\"center\" style=\"font-size:12px\" id=\"bordertable\">$NO_POLISI[$xNAB]</td>
				
				<form id=\"formNONAB$xNAB\" name=\"formNONAB$xNAB\" method=\"post\" action=\"doSelect.php\">
					<input name=\"editNO_NAB\" type=\"text\" id=\"editNO_NAB\" value=\"$ID_NAB_TGL[$xNAB]\" style=\"display:none\"/>
					<td align=\"center\" id=\"bordertable\">
					<a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formNONAB$xNAB') .submit()\">
					<input type=\"button\" name=\"button\" id=\"button\" value=\"Koreksi\" style=\"width:90px; height:25px; font-size:12px\"/>
					</a>
					</td>
				</form>
				";
				//echo $ID_NAB_TGL[$xNAB];
			}
			echo "</tr>";
			}
			else{
				echo "No Data Found";
			}
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
    </table>
  <tr>
    <th align="center"><?php include("../include/Footer.php") ?></th>
  </tr>
</table>

