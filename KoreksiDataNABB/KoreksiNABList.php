<?php
session_start();
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
		//require_once __DIR__ . '/db_config.php'; 
		//$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
		include("../config/db_connect.php");
		$con = connect();
			
		$pagesize = 15;	
		if(isset($_SESSION["sql_t_NABList"]))	{
			$sql_t_NAB = $_SESSION["sql_t_NABList"];
			//echo $sql_t_NAB;
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
	}
	
?>

<link href="../calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../calendar/calendar.js"></script>

<?php
require_once('../calendar/classes/tc_calendar.php');
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
</style>
<table width="1037" height="390" border="0" align="center">
  <tr>
    <th width="972" height="108" scope="row"><?php include("../include/Header.php") ?></th>
  </tr>
  <tr>
    <th height="40" scope="row" align="center"><span style="font-size: 18px">Koreksi Data NAB</span></th>
  </tr>
  <tr>
    <th align="center" valign="top"  style="padding: 9px" scope="row"><table width="1015" border="0">
      <tr>
        <td colspan="4" valign="top" align="center"><table width="1004" border="0">
          <?php
if($roweffec_NAB > 0){
echo "<tr bgcolor=\"#9CC346\">
            <td width=\"44\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">No.</td>
            <td width=\"91\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">Tanggal</td>
            <td width=\"196\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">No NAB</td>
            <td width=\"148\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">No Polisi</td>
            <td width=\"99\" style=\"border-bottom:ridge\">&nbsp;</td>
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
	
echo "<tr style=\"font-size:14px\" bgcolor=$bg >";
echo "<td align=\"center\">$no</td>
	<td align=\"center\">$TANGGAL_RENCANA[$xNAB]</td>
	<td align=\"center\">$NO_NAB[$xNAB]</td>
	<td align=\"center\">$NO_POLISI[$xNAB]</td>
	
	<form id=\"formNONAB$xNAB\" name=\"formNONAB$xNAB\" method=\"post\" action=\"doSelect.php\">
		<input name=\"editNO_NAB\" type=\"text\" id=\"editNO_NAB\" value=\"$ID_NAB_TGL[$xNAB]\" style=\"display:none\"/>
		<td>
		<a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formNONAB$xNAB') .submit()\">
		<input type=\"button\" name=\"button\" id=\"button\" value=\"Koreksi\" style=\"width:90px; height:25px; font-size:14px\"/>
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
        </table></td>
      </tr>
      <tr style="display:none">
        <td align="right">&nbsp;</td>
        <td><table width="223" border="0" style="display:none">
          <tr>
            <td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="iudjobautho.php?page=back">
              <input type="button" name="button5" id="button5" value="&lt;&lt; Back" style="width:70px; background-color:#9CC346"/>
            </a></td>
            <td width="65" align="center" valign="middle" style="background-color:#9CC346; font-size:12px"><span style="padding-top:0px">Page
              <?=$sesPageres+1?>
              of
              <?=$totalpage?>
            </span></td>
            <td width="74" align="left" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="iudjobautho.php?page=next"></a><a href="iudjobautho.php?page=next">
              <input type="button" name="button4" id="button4" value="Next &gt;&gt;" style="width:70px; background-color:#9CC346"/>
            </a></td>
          </tr>
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

