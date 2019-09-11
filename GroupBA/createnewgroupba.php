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
		
		//open tambahan secure user matriks
		unset($_SESSION["IDGroupCreatenew"]);
		$IDGroupCreatenew = "";
		if(isset($_POST["IDGroupCN"])){
			$IDGroupCreatenew = $_POST["IDGroupCN"];
			$_SESSION["IDGroupCreatenew"] = $IDGroupCreatenew;
		}
		if(isset($_SESSION["IDGroupCreatenew"])){
			$IDGroupCreatenew = $_SESSION["IDGroupCreatenew"];
		}
		if(isset($_SESSION["IDGroupCN"])){
			$IDGroupCreatenew = $_SESSION["IDGroupCN"];
		}
		//echo $IDGroupCreatenew. "ggg";
		
		if($IDGroupCreatenew == TRUE){	
		//middle tambahan secure user matriks
		
			if(isset($_POST["EDITABLE"]))
			{
				$visisub = "visible";
			}
			else
			{
				if(isset($_SESSION["EDITABLE2"]))
				{
					$visisub = "visible";
				}
				else
				{
					$visisub = "hidden";
				}
				unset($_SESSION["EDITABLE2"]);
			}
		}
		else{
			header("location:../menu/authoritysecure.php");
		}//close tambahan secure user matriks
		
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
</script>


<script type="text/javascript">

function formSubmit(x)
{
	if(x == 1){
	//alert("Submitted!");
	document.getElementById("formBA").submit();
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
		<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>CREATE NEW GROUP BA</strong></span></td>
      </tr>
      <tr>
        <td width="100" height="45" colspan="3" valign="bottom" style="border-bottom:solid #000">PROFIL ID GROUP</td>
		<td width="100" height="40" colspan="3" valign="bottom" style="border-bottom:solid #000">MASA BERLAKU</td>
      </tr>
      <tr>
		<td width="100" height="29" valign="top">ID Group BA</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="100"><form id="formBA" name="formBA" method="post" action="docreatenewgroupba.php">
				<?php    
				$sql_group_ba_1  = "select to_char(SYSDATE+1,'MM/DD/YYYY') TGL from dual";
				$result_group_ba_1  = select_data($con,$sql_group_ba_1);
				$start_date_1 = $result_group_ba_1["TGL"];
				
				if(isset($_POST["ID_GROUP_BA"]))
				{
					$ID_GROUP_BA = $_POST["ID_GROUP_BA"];
					$sql_group_ba  = "SELECT GROUP_NAME,to_char(START_DATE, 'MM/DD/YYYY') AS START_DATE,to_char(END_DATE, 'MM/DD/YYYY') AS END_DATE FROM T_GROUP_BA WHERE ID_GROUP_BA='$ID_GROUP_BA'";
					$result_group_ba  = select_data($con,$sql_group_ba);
					$id_group_name 		= $result_group_ba["GROUP_NAME"];
					$start_date = $result_group_ba["START_DATE"];
					$end_date = $result_group_ba["END_DATE"];
					
				}
				else
				{
					$sql_next_group_ba  = "SELECT MAX(ID_GROUP_BA) AS ID_GROUP_BA FROM T_GROUP_BA";
					$result_next_group_ba  = select_data($con,$sql_next_group_ba);
					$ID_GROUP_BA 		= $result_next_group_ba["ID_GROUP_BA"];
					
					if($ID_GROUP_BA==null)
					{
						$ID_GROUP_BA = 1;
					}
					else
					{
						$ID_GROUP_BA = $ID_GROUP_BA+1;
					}
					$id_group_name='';
					$start_date='';
					$end_date='';
					
					$ceklen = strlen($ID_GROUP_BA);
				
					if($ceklen >1 && $ceklen<3)
					{
						$ID_GROUP_BA = '0'.$ID_GROUP_BA;
					}
					if($ceklen <2)
					{
						$ID_GROUP_BA = '00'.$ID_GROUP_BA;
					}
				}
				?>
		<input name="id_group_ba" type="text" id="id_group_ba" value="<?=$ID_GROUP_BA?>" style="background-color:#CCC; width:100px; height:25px; font-size:15px" readonly>
		</td>
		
		<td width="100" height="29" valign="top">Start Date</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="100" valign="top"><input type="text" name="start" id="datepicker" class="box_field" 
		<?php 
			if($start_date!=='')
			{ 
				echo "value='$start_date'"; 
			}
			else
			{
				echo "value='$start_date_1'";
			}
		?>></td>
      </tr>
	  <tr>
        <td width="100" height="29" valign="top">ID Group Name</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="100">
			<?php
				if(isset($id_group_name))
				{
					echo "
					<input type='text' name='id_group_name' class='box_field' style='width:350px;' value='$id_group_name' maxlength='100'/>
					";
				}
				else
				{
					echo "<input type='text' name='id_group_name' class='box_field' style='width:350px;' maxlength='100'/>";
				}
			?>
		</td>
		
		<td width="100" height="29" valign="top">End Date</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="100" valign="top"><input type="text" name="end" id="datepicker2" class="box_field" 
		<?php 
			if($end_date!=='')
			{ 
				echo "value='$end_date'"; 
			}
			else
			{
				echo "value='01/01/2999'";
			}
		?>></td>
      </tr>
      <tr>
        <td width="100" height="46" colspan="6" valign="bottom" style="border-bottom:solid #000">DAFTAR BUSINESS AREA</td>
      </tr>
      <tr>
        <td colspan="6">
          <?php
		
				$sql_BA = 'select * from t_bussinessarea order by ID_BA';
				$result_t_AC = oci_parse($con, $sql_BA);
				oci_execute($result_t_AC, OCI_DEFAULT);
				while(oci_fetch($result_t_AC)){
							$sIDBA[]		= oci_result($result_t_AC, "ID_BA");
							$sNamaBA[]	= oci_result($result_t_AC, "NAMA_BA");
						}
				$rowBA = oci_num_rows($result_t_AC);
				
				if($rowBA > 1){
					$tableo = " <table width=\"1034\" border=\"0\">";	
					echo $tableo;
					$td = '';	
					$hitJ = 0;		
					for($xJAJ = 0; $xJAJ <  $rowBA; $xJAJ++)
					{
						$sql_check = "select count(*) as FIND from t_alternate_ba_group where ID_GROUP_BA = '$ID_GROUP_BA' and ID_BA = '$sIDBA[$xJAJ]'";
						$check_result = select_data($con, $sql_check);
						
						$sql_disable = "select count(*) as DISABLE from t_alternate_ba_group where ID_BA = '$sIDBA[$xJAJ]'";
						$disable_result = select_data($con, $sql_disable);
						if($check_result["FIND"]==0)
						{
							if($disable_result["DISABLE"]==0)
							{
								$td .= 	"<td height=\"35\"> <input type=\"checkbox\" name=\"chk$xJAJ\" id=\"chk$xJAJ\" value=\"$sIDBA[$xJAJ]\" /> $sIDBA[$xJAJ] - $sNamaBA[$xJAJ] </td>";
							}
							else
							{
								$td .= 	"<td height=\"35\"> <input type=\"checkbox\" name=\"chk$xJAJ\" id=\"chk$xJAJ\" value=\"$sIDBA[$xJAJ]\" disabled='disabled' /> $sIDBA[$xJAJ] - $sNamaBA[$xJAJ] </td>";
							}
						}
						else
						{
							$td .= 	"<td height=\"35\"> <input type=\"checkbox\" name=\"chk$xJAJ\" id=\"chk$xJAJ\" checked=\"checked\" value=\"$sIDBA[$xJAJ]\" /> $sIDBA[$xJAJ] - $sNamaBA[$xJAJ] </td>";
						}
						
						$hitJ++;	
						if (($hitJ % 4) == 0) 
						{
							print "<tr>$td</tr>";
							$td = '';
							
						}
					}
					$tablec = "</table>";
					print "<tr>$td</tr>";
					echo $tablec; 
					
					$_SESSION["rowBA"] = $rowBA;
				}
		
		?>
        </form>
          <table width="1034" border="0">
          <tr>
            <td colspan="6">&nbsp;</td>
          </tr>
      </table>
          
        </td>
      </tr>
      <tr>
        <td colspan="6">
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
	if($ctr!=0){
		//for($ins = 0 ; $ins < $ctr; $ins++ ){
			if(isset($_SESSION["insert0"])){
				$insert = $_SESSION["insert0"];
				if($insert!=null){
					echo $insert;
					unset($_SESSION["insert0"]);
				}
				unset($_SESSION["insert0"]);
			}
		//}
	}
	else{
		echo "insert success";
		unset($_SESSION["ctr"]);
	}
}
?>
        </td>
      </tr>
	  <tr>
	  <td colspan="6" align="left"><input type="submit" name="button" id="button" value="SIMPAN" style="width:120px; height: 30px; font-size:18px; visibility:<?=$visisub?>" onclick="formSubmit(1)"/></td>
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