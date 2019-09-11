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
		
		$IDGroupView = "";
		if(isset($_POST["IDGroupView"])){
			$IDGroupView = $_POST["IDGroupView"];
			$_SESSION["IDGroupView"] = $IDGroupView;
		}
		if(isset($_SESSION["IDGroupView"])){
			$IDGroupView = $_SESSION["IDGroupView"];
		}
		
		if($IDGroupView == TRUE){
		
			$pagesize = 15;	
			//echo $_SESSION["sql_t_groupba"];
			if(isset($_SESSION["sql_t_groupba"]))	{
				$sql_t_groupba = $_SESSION["sql_t_groupba"];
	//echo $sql_t_groupba;
				$result_t_groupba = oci_parse($con, $sql_t_groupba);
				oci_execute($result_t_groupba, OCI_DEFAULT);
				while(oci_fetch($result_t_groupba)){
				$ID_GROUP_BA[] 			= oci_result($result_t_groupba, "ID_GROUP_BA");
				$GROUP_NAME[] 			= oci_result($result_t_groupba, "GROUP_NAME");
				$CREATED_DATE[] 		= oci_result($result_t_groupba, "CREATED_DATE");
				$CREATED_BY[] 		= oci_result($result_t_groupba, "CREATED_BY"); 
				$START_DATE[]   = oci_result($result_t_groupba, "START_DATE"); 
				$END_DATE[]   = oci_result($result_t_groupba, "END_DATE");
				$CREATED_BY[]   = oci_result($result_t_groupba, "CREATED_BY");
				}
				$rowBA = oci_num_rows($result_t_groupba);
				
				$totalpage = ceil($rowBA/$pagesize);
				$setPage = $totalpage - 1;
				//echo "totalpage".$totalpage;
			}
			else{
				$totalpage = 0;
				$rowBA  = "";
				//echo "rowBA".$rowBA;
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

<?php
		}
		else{
			header("location:../menu/authoritysecure.php");
		}
	}
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
	font-family: Tahoma;
	font-size: 16px;
}
</style>
<table width="1151" height="390" border="0" align="center">
  <tr>
    <th width="972" height="108" scope="row"><?php include("../include/Header.php") ?></th>
  </tr>
  <tr bgcolor="#C4D59E">
    <th align="center" valign="top"  style="padding: 9px" scope="row"><table width="1129" border="0">
      <tr>
        <td width="889"><span style="font-size:18px; background-color:#9C6">DAFTAR ID GROUP</span></td>
        <td width="260" colspan="3" align="right">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" valign="bottom" style="font-size:14px"><form id="form1" name="form1" method="post" action="dofindgroupba.php">
          <table width="1128" border="0">
            <tr>
				<td width="100" align="left" align="left" valign="top" style="font-size:14px">Start Date</td>
				<td width="10">:</td>
				<td width="100" align="left" align="left" valign="top" style="font-size:14px"><input type="text" name="date1" id="datepicker" class="box_field" <?php if(isset($_POST["date1"])){ echo "value='$_POST[date1]'"; }?>></td>
			</tr>
			<tr>
				<td width="100" align="left" align="left" valign="top" style="font-size:14px">End Date</td>
				<td width="10">:</td>
				<td width="100" align="left" align="left" valign="top" style="font-size:14px"><input type="text" name="date2" id="datepicker2" class="box_field" <?php if(isset($_POST["date2"])){ echo "value='$_POST[date2]'"; }?>></td>
			</tr>
            <tr>
				<td width="100" valign="top" style="font-size:14px">
						Created By 
						<?php    

						$sql_t_employee  	= "SELECT EMP_NAME FROM T_EMPLOYEE WHERE NIK='$NIK'";
						$result_t_employee  = select_data($con,$sql_t_employee);
						$emp_name 			= $result_t_employee["EMP_NAME"];
						
						?>
						<td width="10">:</td>
						<td width="100" align="left" valign="top" style="font-size:14px"><input name="emp_name" type="text" id="emp_name" value="<?=$emp_name?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly></td>
				</td>
				<td width="468" align="right"><input type="submit" name="button6" id="button6" value="TAMPILKAN" style="width:120px; height: 30px; font-size:18px" /></td>
			</tr>
			<tr>
				<td width="100" valign="top" style="font-size:14px">
						Business Area
						<td width="10">:</td>
						<td width="100" align="left" valign="top" style="font-size:14px">
						<?php
						$sql_BA = "SELECT * FROM t_bussinessarea";
						$result_BA = oci_parse($con, $sql_BA);
						oci_execute($result_BA, OCI_DEFAULT);
						while (oci_fetch($result_BA)) {	
							$ID_BA[] 		= oci_result($result_BA, "ID_BA");
							$NAMA_BA[] 	= oci_result($result_BA, "NAMA_BA");
						}
						$jumlahBA = oci_num_rows($result_BA);
						if($jumlahBA >0 ){
						$selectoBA = "<select name=\"BA\" id=\"BA\" style=\"visibility:visible; font-size: 15px;  height: 25px\">";
						$optiondefBA = "<option value=\"ALL\">-- ALL --</option>";
						echo $selectoBA.$optiondefBA;
						for($xBA = 0; $xBA < $jumlahBA; $xBA++){
						 echo "<option value=\"$ID_BA[$xBA]\">$ID_BA[$xBA] - $NAMA_BA[$xBA]</option>"; 
						}
						$selectcBA = "</select>";
						echo $selectcBA;
						}
						?>
						</td>
				</td>
			</tr>
        </table>
        </form></td>
      </tr>
      <tr>
        <td height="46" colspan="4" valign="bottom" style="font-size:14px ; border-bottom:solid #000">DAFTAR ID GROUP BA YANG SUDAH DIBUAT</td>
      </tr>
      <tr>
        <td colspan="4" valign="top"><table width="1134" border="0">
          <tr>
            <td width="20" align='center' style="font-size:14px; border-bottom:ridge">No.</td>
			<td width="80" align='center' style="font-size:14px; border-bottom:ridge">ID Group BA</td>
			<td width="150" align='center' style="font-size:14px; border-bottom:ridge">Group Name</td>
            <td width="110" align='center' style="font-size:14px; border-bottom:ridge">Created Date</td>
            <td width="145" align='center' style="font-size:14px; border-bottom:ridge">Start Date</td>
			<td width="145" align='center' style="font-size:14px; border-bottom:ridge">End Date</td>
			<td width="100" align='center' style="font-size:14px; border-bottom:ridge">Created By</td>
            <td width="75" align='center' style="border-bottom:ridge">&nbsp;</td>
            <td width="75" align='center' style="border-bottom:ridge">&nbsp;</td>
            <td width="80" align='center' style="border-bottom:ridge">&nbsp;</td>
          </tr>
          <?php
$endPage = $calPage + $pagesize;
for($xJAN = $calPage; $xJAN <  $rowBA && $xJAN <$endPage; $xJAN++){
	$no = $xJAN +1;

$sql_created_by  	= "SELECT EMP_NAME FROM T_EMPLOYEE WHERE NIK='$CREATED_BY[$xJAN]'";
$result_created_by  = select_data($con,$sql_created_by);
$created_name 		= $result_created_by["EMP_NAME"];

echo "<tr style=\"font-size:14px\">";
echo "<td>$no</td>
            <td>$ID_GROUP_BA[$xJAN]</td>
            <td>$GROUP_NAME[$xJAN]</td>
            <td>$CREATED_DATE[$xJAN]</td>
			<td>$START_DATE[$xJAN]</td>
			<td>$END_DATE[$xJAN]</td>
			<td>$created_name</td>
            
			<form id=\"formLIHAT$xJAN\" name=\"formLIHAT$xJAN\" method=\"post\" action=\"createnewgroupba.php\">
				<input name=\"ID_GROUP_BA\" type=\"text\" id=\"ID_GROUP_BA\" value=\"$ID_GROUP_BA[$xJAN]\" style=\"display:none\"/>
				<input name=\"IDGroupCN\" type=\"text\" id=\"IDGroupCN\" value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
				<td>
				<a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formLIHAT$xJAN') .submit()\">
				<input type=\"button\" name=\"button\" id=\"button\" value=\"LIHAT\" style=\"width:70px\"/>
				</a>
				</td>
			</form>
			
			<form id=\"formEDIT$xJAN\" name=\"formEDIT$xJAN\" method=\"post\" action=\"createnewgroupba.php\">
				<input name=\"ID_GROUP_BA\" type=\"text\" id=\"ID_GROUP_BA\" value=\"$ID_GROUP_BA[$xJAN]\" style=\"display:none\"/>
				<input name=\"EDITABLE\" type=\"text\" id=\"EDITABLE\" value=\"TRUE\" style=\"display:none\"/>
				<input name=\"IDGroupCN\" type=\"text\" id=\"IDGroupCN\" value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
				<td>
				<a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formEDIT$xJAN') .submit()\">
				<input type=\"button\" name=\"button2\" id=\"button2\" value=\"EDIT\" style=\"width:70px\"/>
				</a>
				</td>
			</form>
			
			<form id=\"formDELETE$xJAN\" name=\"formDELETE$xJAN\" method=\"post\" action=\"delgroupba.php\">
				<input name=\"ID_GROUP_BA\" type=\"text\" id=\"ID_GROUP_BA\" value=\"$ID_GROUP_BA[$xJAN]\" style=\"display:none\"/>
				<td>
				<a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formDELETE$xJAN') .submit()\">
				<input type=\"button\" name=\"button3\" id=\"button3\" value=\"DELETE\" style=\"width:70px\"/>
				</a>
				</td>
			</form>
			";

}
echo "</tr>";
?>
        </table></td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td><table width="223" border="0">
          <tr>
            <td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="daftargroupba.php?page=back">
          <input type="button" name="button5" id="button5" value="&lt;&lt; Back" style="width:70px; background-color:#9CC346"/>
        </a></td>
        <td width="65" align="center" valign="middle" style="background-color:#9CC346; font-size:12px"><span style="padding-top:0px">Page <?=$sesPageres+1?> of <?=$totalpage?></span></td>
        <td width="74" align="left" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="daftargroupba.php?page=next"></a><a href="daftargroupba.php?page=next">
          <input type="button" name="button4" id="button4" value="Next &gt;&gt;" style="width:70px; background-color:#9CC346"/>
        </a></td>
          </tr>
        </table></td>
        
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
?></td>
      </tr>
    </table></th>
  </tr>
  <tr>
    <th align="center"><?php include("../include/Footer.php") ?></th>
  </tr>
</table>

