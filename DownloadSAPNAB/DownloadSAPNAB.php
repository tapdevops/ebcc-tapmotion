<?php
session_start();
include("../include/Header.php");
if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name']) && isset($_SESSION['sql_Download_NAB'])){	
$Job_Code = $_SESSION['Job_Code'];
$username = $_SESSION['NIK'];	
$Emp_Name = $_SESSION['Name'];
//$Jenis_Login = $_SESSION['Jenis_Login'];
$Comp_Name = $_SESSION['Comp_Name'];
$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
$Date = $_SESSION['Date'];
$sql_Download_NAB = $_SESSION['sql_Download_NAB'];
	
	if($username == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:login.php");
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
		
		$sql = $sql_Download_NAB;
		 //BA, AFD, Posting Date, No NAB

		$result = oci_parse($con, $sql);
		oci_execute($result, OCI_DEFAULT);
		while (oci_fetch($result)) {		
			$ID_CC[] 	= oci_result($result, "ID_CC");
			$ID_BA[] 	= oci_result($result, "ID_BA");
			$ID_AFD[] 	= oci_result($result, "ID_AFD");
			$ID_ESTATE[]= oci_result($result, "ID_ESTATE");
			$NO_NAB[] 	= oci_result($result, "NO_NAB");
			$ID_NAB_TGL[] 	= oci_result($result, "ID_NAB_TGL");
			$DATE[] 	= oci_result($result, "TGL_NAB");
			$NO_POLISI[]= oci_result($result, "NO_POLISI");
		}
		$roweffec = oci_num_rows($result);
		//echo $sql. $roweffec ;
	}
?>

<script type="text/javascript">

function checkOne(s){
	
    if(document.getElementById("chkall").checked == true){
		for(var y = 0 ; y < s ; y++){
			 var z = "chk"+y;
			 document.getElementById(z).checked = true;
		}
		
    }
    else{
         for(var y = 0 ; y < s ; y++){
			 var z = "chk"+y;
			 document.getElementById(z).checked = false;
		}
    }

  }

function change(x)
{
	if(x == 1){
	document.getElementById("Afdeling").style.visibility="visible";
	document.getElementById("NIKMandor").style.visibility="hidden";
	document.getElementById("NIKPemanen").style.visibility="hidden";
	document.getElementById("NIKMandor").value="kosong";
	document.getElementById("NIKPemanen").value="kosong";
	document.getElementById("button").style.visibility="visible";
	}
	if(x == 2){
	document.getElementById("Afdeling").style.visibility="hidden";
	document.getElementById("NIKMandor").style.visibility="visible";
	document.getElementById("NIKPemanen").style.visibility="hidden";
	document.getElementById("Afdeling").value="kosong";
	document.getElementById("NIKPemanen").value="kosong";
	document.getElementById("button").style.visibility="visible";
	}
	if(x == 3){
	document.getElementById("Afdeling").style.visibility="hidden";
	document.getElementById("NIKMandor").style.visibility="hidden";
	document.getElementById("NIKPemanen").style.visibility="visible";
	document.getElementById("Afdeling").value="kosong";
	document.getElementById("NIKMandor").value="kosong";
	document.getElementById("button").style.visibility="visible";
	}
}

function coba(x)
{
	
	if(x == 1){
	document.getElementById("Tanggal").style.visibility="visible";
	document.getElementById("Periode").style.visibility="hidden";
	document.getElementById("Periode").value="kosong";
	}
	
	if(x == 2){
	document.getElementById("Tanggal").style.visibility="hidden";
	document.getElementById("Periode").style.visibility="visible";
	document.getElementById("Tanggal").value="kosong";
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
  <tr>
    <th height="197" scope="row" align="center"><table width="937" border="0" id="setbody2">
      <tr>
		<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>DOWNLOAD SAP TEMPLATE NAB</strong></span></td>
      </tr>
	  
	<tr>
		<td width="130" height="27" valign="top">Company Code</td>
	  <td width="7" height="27" valign="top">:</td>
		<td width="355" align="left" valign="top"><input name="company_code" type="text" id="company_code" value="<?=$company_code?>" style="background-color:#CCC; width: 350px; height:20px; font-size:15px" readonly="readonly" />
		</td>
	</tr>
	<tr>
		<td width="130" height="22" valign="top">Business Area</td>
	  <td width="7" height="22" valign="top">:</td>
		<td width="355" align="left" valign="top"><input name="business_area" type="text" id="business_area" value="<?=$business_area?>" style="background-color:#CCC; width: 350px; height:20px; font-size:15px" readonly="readonly" />
		</td>
	</tr>
	<tr>
		<td width="130" height="22" valign="top">Company Name</td>
	  <td width="7" height="22" valign="top" >:</td>
		<td width="355" align="left" valign="top"><input name="company_name" type="text" id="company_name" value="<?=$company_name?>" style="background-color:#CCC; width: 350px; height:20px; font-size:15px" readonly="readonly" />
		</td>
	</tr>
	
      <tr>
        <td colspan="9" valign="top" align="center"><table width="1134" border="0">
		<form id="form3" name="form3" method="post" action="printTXT.php" >
          	  <?php
			  if($roweffec >0){
				  
			  echo "
			  <tbody id=\"scrolling\" style=\"width:1134px\" border=\"1\" bordercolor=\"#9CC346\">
              <tr bgcolor=\"#9CC346\">
                <td width=\"50\" align=\"center\" valign=\"top\" id=\"bordertable\">Pilih</td>
                <td width=\"150\" align=\"center\" valign=\"top\" id=\"bordertable\">Company Code</td>
				<td width=\"100\" align=\"center\" valign=\"top\" id=\"bordertable\">ID Estate</td>
                <td width=\"100\" align=\"center\" valign=\"top\" id=\"bordertable\">ID BA</td>
				<td width=\"100\" align=\"center\" valign=\"top\" id=\"bordertable\">ID AFD</td>
                <td width=\"250\" align=\"center\" valign=\"top\" id=\"bordertable\">No NAB</td>
                <td width=\"150\" align=\"center\" valign=\"top\" id=\"bordertable\">Posting Date</td>
                <td width=\"150\" align=\"center\" valign=\"top\" id=\"bordertable\">No Polisi</td>
                <td width=\"100\" align=\"center\" valign=\"top\" id=\"bordertable\">View</td>
              </tr>";
              
			  for($x=0 ; $x<$roweffec ; $x++){
				  
				if(($x % 2) == 0){
					$bg = "#F0F3EC";
				}
				else{
					$bg = "#DEE7D2";
				}
				  
              echo "
              <tr bgcolor=$bg>
                <td align=\"center\" id=\"bordertable\"><input type=\"checkbox\" name=\"chk$x\" id=\"chk$x\" value=\"$ID_NAB_TGL[$x]\"></td>
                <td align=\"center\" id=\"bordertable\">$ID_CC[$x]</td>
                <td align=\"center\" id=\"bordertable\">$ID_ESTATE[$x]</td>
                <td align=\"center\" id=\"bordertable\">$ID_BA[$x]</td>
				<td align=\"center\" id=\"bordertable\">$ID_AFD[$x]</td>
				<td align=\"center\" id=\"bordertable\">$NO_NAB[$x]</td>
                <td align=\"center\" id=\"bordertable\">$DATE[$x]</td>
                <td align=\"center\" id=\"bordertable\">$NO_POLISI[$x]</td>
                <td align=\"center\" id=\"bordertable\"><a href=\"doView.php?viewNO_NAB=$NO_NAB[$x]\">
					<input type=\"button\" name=\"button\" id=\"button\" value=\"VIEW\" style=\"width:50px\"/>
					</a>
				</td>
              </tr>";
			  }
			  
			  echo "
			  </tbody>
			  <table width=\"1134\" border=\"0\">
              <tr>
			  	<td><input type=\"checkbox\" name=\"chkall\" id=\"chkall\" onclick=\"checkOne($roweffec)\" ></td>
                <td height=\"15\" align=\"right\"><input type=\"submit\" name=\"button\" id=\"button\" value=\"DOWNLOAD\" style=\"width:120px; height: 30px\"/></td>
              </tr>
			  </table>
			  ";
			  }
			  ?>
            <input name="roweffec" type="text" id="roweffec" value="<?=$roweffec?>" style="display:none" onmousedown="return false"/>
        </form>
		
		</td>
      </tr>
    </table></th>
  </tr>
  <tr>
    <th align="center" colspan="3" ><?php
		if(isset($_SESSION['err'])){
			$err = $_SESSION['err'];
			if($err!=null)
			{
				echo $err;
				unset($_SESSION['err']);
			}
		}
		?></th>
  </tr>
  <tr>
    <th align="center" colspan="3" ><?php include("../include/Footer.php") ?></th>
  </tr>
</table>

<?php
}
else{
	$_SESSION[err] = "Please Login!".$Job_Code."<br>".$username."<br>".$Emp_Name."<br>".$subID_BA_Afd;
	header("location:../index.php");
}
?>
