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
		
		$PanenGandeng = "";
		if(isset($_POST["PanenGandeng"])){
			$PanenGandeng = $_POST["PanenGandeng"];
			$_SESSION["PanenGandeng"] = $PanenGandeng;
		}
		if(isset($_SESSION["PanenGandeng"])){
			$PanenGandeng = $_SESSION["PanenGandeng"];
		}
		
		if($PanenGandeng == TRUE){
			
		//Added by Ardo, 01 Dec 2016 : Batas Gandeng agar dapat input lebih dari 1
		$sql_user_login  = 	"select c.id_cc as COMPANY_CODE, c.id_ba as BUSINESS_AREA, d.comp_name AS COMPANY_NAME
							from t_employee a, t_afdeling b, t_bussinessarea c, t_companycode d
							where a.id_ba_afd = b.id_ba_afd and b.id_ba = c.id_ba and c.id_cc = d.id_cc 
							and a.nik = '$_SESSION[NIK]'";
		$result_user_login	= select_data($con,$sql_user_login);
		$val_baa		= $result_user_login["BUSINESS_AREA"];
	  
		$sql_user_login  	= 	"select maksimum_jumlah_gandeng, batas_gandeng, id_ba, to_char(start_date,'MM/DD/YYYY') start_date, to_char(end_date,'MM/DD/YYYY') end_date from t_max_gandeng where id_ba='$val_baa'";
		$result_user_login	= select_data($con,$sql_user_login);
		$batas_gandeng	= $result_user_login["BATAS_GANDENG"];
		
		if($batas_gandeng==null)
		{
			$batas_gandeng =  0;
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
		
		//Added by Ardo, 21-09-2016 : prevent user click other than number
		$("#max_jml_gandeng").keydown(function (e) {
			
			
			var charCode = (e.which) ? e.which : e.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 50)){
				e.preventDefault();
			}
		
			return true;
		});
		
		$("#max_jml_gandeng").keyup(function (e) {
			var jml_gandeng = $("#max_jml_gandeng").val();
			//alert(jml_gandeng);
			var batas_gandeng = '<?= $batas_gandeng ?>';
			if(jml_gandeng.length>1 && jml_gandeng.substr(0, 1)=='0'){
				alert('Max Employee harus sesuai dengan format angka yang benar');
				$("#max_jml_gandeng").val(0);
			} else if(parseInt(jml_gandeng)>parseInt(batas_gandeng)){
				alert('Max Employee tidak boleh lebih besar dari '+jml_gandeng);
				$("#max_jml_gandeng").val(0);
			}
			return true;
		});
});


</script>


<script type="text/javascript">

function formSubmit(x)
{
	if(x == 1){
		//alert("Data Sudah \nTersimpan!");
	document.getElementById("formPG").submit();
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
		<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>PANEN GANDENG SETTING</strong></span></td>
      </tr>
      <tr>
        <td height="45" colspan="3" valign="bottom" style="border-bottom:solid #000">LOKASI</td>
		<td height="40" colspan="3" valign="bottom" style="border-bottom:solid #000">MASA BERLAKU</td>
      </tr>
	  <form id="formPG" name="formPG" method="post" action="docreatenewpanengandeng.php">
      <tr>
        <?php   
			$sql_user_login  = 	"select c.id_cc as COMPANY_CODE, c.id_ba as BUSINESS_AREA, d.comp_name AS COMPANY_NAME
								from t_employee a, t_afdeling b, t_bussinessarea c, t_companycode d
								where a.id_ba_afd = b.id_ba_afd and b.id_ba = c.id_ba and c.id_cc = d.id_cc 
								and a.nik = '$username'";
			$result_user_login	= select_data($con,$sql_user_login);
			$company_code		= $result_user_login["COMPANY_CODE"];
			$business_area		= $result_user_login["BUSINESS_AREA"];
			$company_name		= $result_user_login["COMPANY_NAME"];
		?>
		<?php   
			$sql_user_login  	= 	"select maksimum_jumlah_gandeng, id_ba, to_char(start_date,'MM/DD/YYYY') start_date, to_char(end_date,'MM/DD/YYYY') end_date from t_max_gandeng where id_ba='$business_area'";
			$result_user_login	= select_data($con,$sql_user_login);
			$start_date			= $result_user_login["START_DATE"];
			$end_date			= $result_user_login["END_DATE"];
			$max_jml_gandeng	= $result_user_login["MAKSIMUM_JUMLAH_GANDENG"];
			
			if($start_date==null)
			{
				$start_date =  '';
			}
			if($end_date==null)
			{
				$end_date =  '';
			}
			if($max_jml_gandeng==null)
			{
				$max_jml_gandeng =  '';
			}
			//echo $start_date." ".$end_date;
		?>
		<td width="70" height="29" valign="top">Company Code</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="100"><input name="company_code" type="text" id="company_code" value="<?=$company_code?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly></td>
		
		<td width="70" height="29" valign="top">Start Date</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="100" valign="top"><input type="text" name="start" id="datepicker" class="box_field" 
		<?php 
			if($start_date!=='')
			{ 
				//$start_date = date("m/d/Y", strtotime($start_date));
				echo "value='$start_date'"; 
			}
		?>></td>
		</tr>
		<tr>
		<td width="70" height="29" valign="top">Business Area</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="100"><input name="business_area" type="text" id="business_area" value="<?=$business_area?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly></td>
		
		<td width="70" height="29" valign="top">End Date</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="100" valign="top"><input type="text" name="end" id="datepicker2" class="box_field" 
		<?php 
			if($end_date!=='')
			{ 
			//$end_date = date("m/d/Y", strtotime($end_date));
			echo "value='$end_date'"; 
			}
		?>></td>
		</tr>
		<tr>
		<td width="70" height="29" valign="top">Company Name</td>
		<td width="10" height="29" valign="top">:</td>
		<td width="100"><input name="company_name" type="text" id="company_name" value="<?=$company_name?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly></td>
		</tr>
      <tr>
        <td height="40" colspan="6" valign="bottom" style="border-bottom:solid #000">MAX NUMBER OF EMPLOYEE</td>
      </tr>
		<tr>
			<td width="70" height="29" valign="top">Max Employee</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100"><input type="text" name="max_jml_gandeng" id="max_jml_gandeng" class="box_field" 
			<?php 
				if($max_jml_gandeng!=='')
				{ 
					echo "value='$max_jml_gandeng'"; 
				}
			?>></td>
		</tr>
      </form>
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

if(isset($_SESSION['ctr'])){
	$ctr = $_SESSION['ctr'];
	if($ctr!=null){
		for($ins = 0 ; $ins < $ctr; $ins++ ){
			if(isset($_SESSION['insert$ins'])){
				$insert = $_SESSION['insert$ins'];
				if($insert!=null){
					echo $insert;
					unset($_SESSION['insert$ins']);
				}
				unset($_SESSION["insert$ins"]);
			}
		}
	}
	else{
		echo "insert success";
	}
}
?>
        </td>
      </tr>
	  <tr>
	  <td colspan="3" align="left"><input type="submit" name="button" id="button" value="SIMPAN" style="width:120px; height: 25px;" onclick="formSubmit(1)"/></td>
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
			header("location:../menu/authoritysecure.php");
		}
	}
}
else{
	$_SESSION[err] = "tolong login dulu!";
	header("location:../index.php");
}

?>