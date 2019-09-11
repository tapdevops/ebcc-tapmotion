<?php
	session_start();

if(isset($_SESSION[NIK])){
	
	$visiTM2 = "hidden";
	$visiTM3 = "hidden";

	//echo $_SESSION['No_NAB']. $_SESSION['date3'];
	
	$jumlahSUPIR = $_SESSION['jumlahSUPIR'];
	$jumlahTM = $_SESSION['jumlahTM'];
	$jumlahIN = $_SESSION['jumlahIN'];
	$Job_Code = $_SESSION[Job_Code];
	$username = $_SESSION[NIK];	
	$Emp_Name = $_SESSION[Name];
	$Jenis_Login = $_SESSION[Jenis_Login];	
	$subID_BA_Afd = $_SESSION[subID_BA_Afd];		//mengambil session yang berisi username dan jenisuser
		if($username == "")		//cek apa ada user masuk ke web tanpa login
		{
		$_SESSION[err] = "tolong login dulu!";
		header("location:../../login.php");
		}
		else
		{	
			include '../../db_connect.php';
			$db = new DB_CONNECT();
			
					if(isset($_POST[No_NAB])){
					$_SESSION['No_NAB'] = $_POST[No_NAB];
					}
					
					if($_POST[date3] != '0000-00-00' && $_POST[date3] != ""){
					$_SESSION['date3'] = $_POST[date3];
					}
					
					if(isset($_POST[No_Polisiext])){
					$_SESSION['No_Polisiext'] = $_POST[No_Polisiext];
					}
					
					if(isset($_POST[Supirext])){
					$_SESSION['Supirext'] = $_POST[Supirext];
					}
					
					if(isset($_POST[No_Polisiint])){
					$_SESSION['No_Polisiint'] = $_POST[No_Polisiint];
					}
					
					if(isset($_POST[Supirint])){
					$_SESSION['Supirint'] = $_POST[Supirint];
					}
					
					//echo "getTM1".$_GET[TM1];
					if($_GET[TM1] != "kosong" && $_GET[TM1] != "" ){
					$_SESSION[TM1SBCCNAB] = $_GET[TM1];
					}
					
					//echo "getTM2".$_GET[TM2];
					if($_GET[TM2] != "kosong" && $_GET[TM2] != "" ){
					$_SESSION[TM2SBCCNAB] = $_GET[TM2];
					}
					
					//echo "getTM3".$_GET[TM3];
					if($_GET[TM3] != "kosong" && $_GET[TM3] != "" ){
					$_SESSION[TM3SBCCNAB] = $_GET[TM3];
					}
			
					$visisubint = "visible";
					$visisubext = "hidden";
					
//echo "TM1 = ".$_SESSION[TM1SBCCNAB] . "TM2 = ".$_SESSION[TM2SBCCNAB]. "TM3 = ".$_SESSION[TM3SBCCNAB];
				//buka TM
				if(isset($_SESSION[TM1SBCCNAB]) && isset($_SESSION[TM2SBCCNAB]) && isset($_SESSION[TM3SBCCNAB])) {
					//echo "TM3";
					$sql_TM1 = "SELECT NIK, Emp_Name, Job_Code
					FROM t_employee
					WHERE NIK = '$_SESSION[TM1SBCCNAB]'";
					
					$result_TM1 = mysql_query($sql_TM1);
					$fetch_TM1 = mysql_fetch_array($result_TM1);
					$NIKTM1 = $fetch_TM1['NIK'];
					$Emp_NameTM1 = $fetch_TM1['Emp_Name'];
					$Job_CodeTM1 = $fetch_TM1['Job_Code'];

					$sql_TM2 = "SELECT NIK, Emp_Name, Job_Code
					FROM t_employee
					WHERE NIK = '$_SESSION[TM2SBCCNAB]'";
						
					$result_TM2 = mysql_query($sql_TM2);
					$fetch_TM2 = mysql_fetch_array($result_TM2);
					$NIKTM2 = $fetch_TM2['NIK'];
					$Emp_NameTM2 = $fetch_TM2['Emp_Name'];
					$Job_CodeTM2 = $fetch_TM2['Job_Code'];					
						
					
					$sql_TM3 = "SELECT NIK, Emp_Name, Job_Code
					FROM t_employee
					WHERE NIK = '$_SESSION[TM3SBCCNAB]'";
					
					$result_TM3 = mysql_query($sql_TM3);
					$fetch_TM3 = mysql_fetch_array($result_TM3);
					$NIKTM3 = $fetch_TM3['NIK'];
					$Emp_NameTM3 = $fetch_TM3['Emp_Name'];
					$Job_CodeTM3 = $fetch_TM3['Job_Code'];

					//$_SESSION[TM3SBCCNAB] = $_GET[TM3];
					$visiTM2 = "visible";
					$visiTM3 = "visible";
					$optionGetTM1 = "<option value=\"$NIKTM1\" selected=\"selected\">$NIKTM1 - $Emp_NameTM1 - $Job_CodeTM1</option>";	
					$optionGetTM2 = "<option value=\"$NIKTM2\" selected=\"selected\">$NIKTM2 - $Emp_NameTM2 - $Job_CodeTM2</option>";	
					$optionGetTM3 = "<option value=\"$NIKTM3\" selected=\"selected\">$NIKTM3 - $Emp_NameTM3 - $Job_CodeTM3</option>";
					
					//open filter
					$sql_value_employee_TM = "SELECT NIK, Emp_Name, Job_Type, Job_Code, ID_BA_Afd, ID_JobAuthority
					FROM t_employee
					WHERE SUBSTRING(ID_BA_Afd,1,4) = '$subID_BA_Afd' && Job_Code = 'TUKANG MUAT' && NIK != '$_SESSION[TM3SBCCNAB]' && NIK != '$_SESSION[TM1SBCCNAB]' && NIK != '$_SESSION[TM2SBCCNAB]' ";
				}

				else if(isset($_SESSION[TM1SBCCNAB]) && isset($_SESSION[TM2SBCCNAB])) {
				//echo "TM2";
					$sql_TM1 = "SELECT NIK, Emp_Name, Job_Code
					FROM t_employee
					WHERE NIK = '$_SESSION[TM1SBCCNAB]'";
	
					$result_TM1 = mysql_query($sql_TM1);
					$fetch_TM1 = mysql_fetch_array($result_TM1);
					$NIKTM1 = $fetch_TM1['NIK'];
					$Emp_NameTM1 = $fetch_TM1['Emp_Name'];
					$Job_CodeTM1 = $fetch_TM1['Job_Code'];

					$sql_TM2 = "SELECT NIK, Emp_Name, Job_Code
					FROM t_employee
					WHERE NIK = '$_SESSION[TM2SBCCNAB]'";
	
					$result_TM2 = mysql_query($sql_TM2);
					$fetch_TM2 = mysql_fetch_array($result_TM2);
					$NIKTM2 = $fetch_TM2['NIK'];
					$Emp_NameTM2 = $fetch_TM2['Emp_Name'];
					$Job_CodeTM2 = $fetch_TM2['Job_Code'];	
	
					//$_SESSION[TM2SBCCNAB] = $_GET[TM2];
					$visiTM2 = "visible";
					$visiTM3 = "visible";
					$optionGetTM1 = "<option value=\"$NIKTM1\" selected=\"selected\">$NIKTM1 - $Emp_NameTM1 - $Job_CodeTM1</option>";	
					$optionGetTM2 = "<option value=\"$NIKTM2\" selected=\"selected\">$NIKTM2 - $Emp_NameTM2 - $Job_CodeTM2</option>";		
					
					//open filter
					$sql_value_employee_TM = "SELECT NIK, Emp_Name, Job_Type, Job_Code, ID_BA_Afd, ID_JobAuthority
					FROM t_employee
					WHERE SUBSTRING(ID_BA_Afd,1,4) = '$subID_BA_Afd' && Job_Code = 'TUKANG MUAT' && NIK != '$_SESSION[TM2SBCCNAB]' && NIK != '$_SESSION[TM1SBCCNAB]' ";	
				}
				
				else if(isset($_SESSION[TM1SBCCNAB])) {
				//echo "TM1";
					$sql_TM1 = "SELECT NIK, Emp_Name, Job_Code
					FROM t_employee
					WHERE NIK = '$_SESSION[TM1SBCCNAB]'";
					
					$result_TM1 = mysql_query($sql_TM1);
					$fetch_TM1 = mysql_fetch_array($result_TM1);
					$NIKTM1 = $fetch_TM1['NIK'];
					$Emp_NameTM1 = $fetch_TM1['Emp_Name'];
					$Job_CodeTM1 = $fetch_TM1['Job_Code'];

					//$_SESSION[TM1SBCCNAB] = $NIKTM1;
					$visiTM2 = "visible";
					$visiTM3 = "hidden";
					$visisubint = "visible";
					$optionGetTM1 = "<option value=\"$NIKTM1\" selected=\"selected\">$NIKTM1 - $Emp_NameTM1 - $Job_CodeTM1</option>";	
					
					//open filter
					$sql_value_employee_TM = "SELECT NIK, Emp_Name, Job_Type, Job_Code, ID_BA_Afd, ID_JobAuthority
					FROM t_employee
					WHERE SUBSTRING(ID_BA_Afd,1,4) = '$subID_BA_Afd' && Job_Code = 'TUKANG MUAT' && NIK != '$_SESSION[TM1SBCCNAB]'";
					/*$result_value_employee_TM = mysql_query($sql_value_employee_TM);
					$roweffec_value_employee_TM = mysql_affected_rows();

					if($result_value_employee_TM && $roweffec_value_employee_TM > 0 && $roweffec_value_employee_TM != 0){ 

							$jumlahTM = mysql_num_rows($result_value_employee_TM);
							while($fetch_value_employee_TM = mysql_fetch_array($result_value_employee_TM)){
								$NIKSBCCTM[]		= $fetch_value_employee_TM['NIK'];
								$Emp_NameSBCCTM[]	= $fetch_value_employee_TM['Emp_Name'];
								$Job_TypeSBCCTM[]	= $fetch_value_employee_TM['Job_Type'];
								$Job_CodeSBCCTM[]	= $fetch_value_employee_TM['Job_Code'];
								$ID_BA_AfdSBCCTM[]= $fetch_value_employee_TM['ID_BA_Afd'];
								$ID_JobAuthoritySBCCTM[]	= $fetch_value_employee_TM['ID_JobAuthority'];
							}
					}
					else{
						$_SESSION[err] = "Tukang Muat2 not found";
						header("Location:../../EditStatusBCC.php");	
					}	// close filter */
				}
				
				
				
				else{
					//echo "MASUK";
					
					$visiTM2 = "hidden";   
					$visiTM3 = "hidden";
					$optionGetTM1 = "";
					$optionGetTM2 = "";

					// t_employee_tukangmuat
					$sql_value_employee_TM = "SELECT NIK, Emp_Name, Job_Type, Job_Code, ID_BA_Afd, ID_JobAuthority
					FROM t_employee
					WHERE SUBSTRING(ID_BA_Afd,1,4) = '$subID_BA_Afd' && Job_Code = 'TUKANG MUAT'";

				} 
				
				//tutup TM

				//echo "<br>".$jumlahTM."<br>".$sql_value_employee_TM;
				
				if($_POST[No_Polisiint] != "kosong" && $_POST[No_Polisiint] != ""){
					//echo "MASUK io internal <br>";
					//ADD ADD
					$_SESSION[No_Polisiint] = $_POST[No_Polisiint];		
					$sql_io = "SELECT ID_Internal_Order, No_Polisi, ID_BA
					FROM t_internal_order 
					WHERE ID_BA = '$subID_BA_Afd' && ID_Internal_Order = '$_POST[No_Polisiint]'";
					$result_io = mysql_query($sql_io);
					$fetch_io = mysql_fetch_array($result_io);
					$ID_Internal_Order_io = $fetch_io['ID_Internal_Order'];
					$No_Polisi_io = $fetch_io['No_Polisi'];
					$ID_BA_io = $fetch_io['ID_BA'];		
					$_SESSION[No_Polisi_io] = $No_Polisi_io;
					
					$optionPostNoint = "<option value=\"$ID_Internal_Order_io\" selected=\"selected\">$ID_Internal_Order_io - $No_Polisi_io - $ID_BA_io </option>";	

					$sql_value_t_internal_order = "SELECT ID_Internal_Order, No_Polisi, ID_BA
					FROM t_internal_order 
					WHERE ID_BA = '$subID_BA_Afd' && ID_Internal_Order != '$_POST[No_Polisiint]' ";
				}
				
				//else if(isset($_POST[No_Polisiint]) && isset($_SESSION[No_Polisiint]) && $_POST[No_Polisiint] != "kosong" && $_POST[No_Polisiint] != ""){
				else if(isset($_SESSION[No_Polisiint])){
				
					//echo "MASUK io session internal <br>";
					//ADD ADD
					$sql_io = "SELECT ID_Internal_Order, No_Polisi, ID_BA
					FROM t_internal_order 
					WHERE ID_BA = '$subID_BA_Afd' && ID_Internal_Order = '$_SESSION[No_Polisiint]'";
					$result_io = mysql_query($sql_io);
					$roweffec_io = mysql_affected_rows();
					
					if($result_io && $roweffec_io > 0 && $roweffec_io != 0){
					$fetch_io = mysql_fetch_array($result_io);
					$ID_Internal_Order_io = $fetch_io['ID_Internal_Order'];
					$No_Polisi_io = $fetch_io['No_Polisi'];
					$ID_BA_io = $fetch_io['ID_BA'];	
					$_SESSION[No_Polisi_io] = $No_Polisi_io;	

					$optionPostNoint = "<option value=\"$ID_Internal_Order_io\" selected=\"selected\">$ID_Internal_Order_io - $No_Polisi_io - $ID_BA_io </option>";	
	
					$sql_value_t_internal_order = "SELECT ID_Internal_Order, No_Polisi, ID_BA
					FROM t_internal_order 
					WHERE ID_BA = '$subID_BA_Afd' && ID_Internal_Order != '$_SESSION[No_Polisiint]' ";
					}
					else{
					$optionPostNoint = "";
					$sql_value_t_internal_order = "SELECT ID_Internal_Order, No_Polisi, ID_BA
					FROM t_internal_order 
					WHERE ID_BA = '$subID_BA_Afd'";
					}
				}
			
				else{					
					$sql_value_t_internal_order = "SELECT ID_Internal_Order, No_Polisi, ID_BA
					FROM t_internal_order 
					WHERE ID_BA = '$subID_BA_Afd'";
					$optionPostNoint = "";
				}


				if($_POST[Supirint] != "kosong" && $_POST[Supirint] != ""){
					//echo "MASUK sup internal <br>";

					$_SESSION[Supirint] = $_POST[Supirint];
					$sql_S = "SELECT NIK, Emp_Name, Job_Type, Job_Code, ID_BA_Afd, ID_JobAuthority
					FROM t_employee
					WHERE SUBSTRING(ID_BA_Afd,1,4) = '$subID_BA_Afd' && Job_Code = 'SUPIR' && NIK = '$_POST[Supirint]' ";
					$result_S = mysql_query($sql_S);
					$fetch_S = mysql_fetch_array($result_S);
					$NIK_S = $fetch_S['NIK'];
					$Emp_Name_S = $fetch_S['Emp_Name'];
					$Job_Code_S = $fetch_S['Job_Code'];
					
					$optionPostSupint = "<option value=\"$NIK_S\" selected=\"selected\">$NIK_S - $Emp_Name_S - $Job_Code_S </option>";	

					$sql_value_employee_S = "SELECT NIK, Emp_Name, Job_Type, Job_Code, ID_BA_Afd, ID_JobAuthority
					FROM t_employee
					WHERE SUBSTRING(ID_BA_Afd,1,4) = '$subID_BA_Afd' && Job_Code = 'SUPIR' && NIK != '$_POST[Supirint]' ";
					
				}
				
				//else if(isset($_POST[Supirint]) && isset($_SESSION[Supirint]) && $_POST[Supirint] != "kosong" && $_POST[Supirint] != ""){
				else if(isset($_SESSION[Supirint])){
					//echo "MASUK sup session internal <br>";
				//ADD ADD
					$sql_S = "SELECT NIK, Emp_Name, Job_Type, Job_Code, ID_BA_Afd, ID_JobAuthority
					FROM t_employee
					WHERE SUBSTRING(ID_BA_Afd,1,4) = '$subID_BA_Afd' && Job_Code = 'SUPIR' && NIK = '$_SESSION[Supirint]' ";
					$result_S = mysql_query($sql_S);
					$roweffec_S = mysql_affected_rows();
					
					if($result_S && $roweffec_S > 0 && $roweffec_S != 0){
					$fetch_S = mysql_fetch_array($result_S);
					$NIK_S = $fetch_S['NIK'];
					$Emp_Name_S = $fetch_S['Emp_Name'];
					$Job_Code_S = $fetch_S['Job_Code'];
					
					$optionPostSupint = "<option value=\"$NIK_S\" selected=\"selected\">$NIK_S - $Emp_Name_S - $Job_Code_S </option>";	

					$sql_value_employee_S = "SELECT NIK, Emp_Name, Job_Type, Job_Code, ID_BA_Afd, ID_JobAuthority
					FROM t_employee
					WHERE SUBSTRING(ID_BA_Afd,1,4) = '$subID_BA_Afd' && Job_Code = 'SUPIR' && NIK != '$_SESSION[Supirint]' ";
					}
					else{
					$sql_value_employee_S = "SELECT NIK, Emp_Name, Job_Type, Job_Code, ID_BA_Afd, ID_JobAuthority
					FROM t_employee
					WHERE SUBSTRING(ID_BA_Afd,1,4) = '$subID_BA_Afd' && Job_Code = 'SUPIR'";
					}
				}
				
				else{					
					$sql_value_employee_S = "SELECT NIK, Emp_Name, Job_Type, Job_Code, ID_BA_Afd, ID_JobAuthority
					FROM t_employee
					WHERE SUBSTRING(ID_BA_Afd,1,4) = '$subID_BA_Afd' && Job_Code = 'SUPIR'";
					
					$optionPostSupint = "";
				}
				
				// t_employee_TM
				$result_value_employee_TM = mysql_query($sql_value_employee_TM);
				$roweffec_value_employee_TM = mysql_affected_rows();
				
				// t_employee_supir
				/* $sql_value_employee_S = "SELECT NIK, Emp_Name, Job_Type, Job_Code, ID_BA_Afd, ID_JobAuthority
				FROM t_employee
				WHERE SUBSTRING(ID_BA_Afd,1,4) = '$subID_BA_Afd' && Job_Code = 'SUPIR'"; */
				$result_value_employee_S = mysql_query($sql_value_employee_S);
				$roweffec_value_employee_S = mysql_affected_rows();
				
				// t_internal_order
				/*$sql_value_t_internal_order = "SELECT ID_Internal_Order, No_Polisi, ID_BA
				FROM t_internal_order 
				WHERE ID_BA = '$subID_BA_Afd'"; */
				$result_value_internal_order = mysql_query($sql_value_t_internal_order);
				$roweffec_value_internal_order = mysql_affected_rows();

				if($result_value_employee_TM && $roweffec_value_employee_TM > 0 && $roweffec_value_employee_TM != 0 &&
				$result_value_employee_S && $roweffec_value_employee_S > 0 && $roweffec_value_employee_S != 0 && 
				$result_value_internal_order && $roweffec_value_internal_order > 0 && $roweffec_value_internal_order != 0){ 
		
						$jumlahTM = mysql_num_rows($result_value_employee_TM);
						while($fetch_value_employee_TM = mysql_fetch_array($result_value_employee_TM)){
							$NIKSBCCTM[]		= $fetch_value_employee_TM['NIK'];
							$Emp_NameSBCCTM[]	= $fetch_value_employee_TM['Emp_Name'];
							$Job_TypeSBCCTM[]	= $fetch_value_employee_TM['Job_Type'];
							$Job_CodeSBCCTM[]	= $fetch_value_employee_TM['Job_Code'];
							$ID_BA_AfdSBCCTM[]= $fetch_value_employee_TM['ID_BA_Afd'];
							$ID_JobAuthoritySBCCTM[]	= $fetch_value_employee_TM['ID_JobAuthority'];
						}
		
						$jumlahSUPIR = mysql_num_rows($result_value_employee_S);
						while($fetch_value_employee_S = mysql_fetch_array($result_value_employee_S)){
							$NIKSBCCS[]		= $fetch_value_employee_S['NIK'];
							$Emp_NameSBCCS[]	= $fetch_value_employee_S['Emp_Name'];
							$Job_TypeSBCCS[]	= $fetch_value_employee_S['Job_Type'];
							$Job_CodeSBCCS[]	= $fetch_value_employee_S['Job_Code'];
							$ID_BA_AfdSBCCS[]= $fetch_value_employee_S['ID_BA_Afd'];
							$ID_JobAuthoritySBCCS[]	= $fetch_value_employee_S['ID_JobAuthority'];
						}
						
						$jumlahIN = mysql_num_rows($result_value_internal_order);	
						while($fetch_value_internal_order = mysql_fetch_array($result_value_internal_order)){
							$ID_Internal_OrderSBCC[]= $fetch_value_internal_order['ID_Internal_Order'];
							$No_PolisiSBCC[]		= $fetch_value_internal_order['No_Polisi'];
							$ID_BASBCC[]			= $fetch_value_internal_order['ID_BA'];
								}
				}
				
				else{
					$_SESSION[err] = "Supir, Tukang Muat, or Internal Order not found";
					header("Location:../../EditStatusBCC.php");	
				}	

		}
?>
<script type="text/javascript">
function change(x)
{
	if(x == 1){
	document.getElementById("internal").style.display="inline";
	document.getElementById("internal2").style.display="inline";
	document.getElementById("eksternal").style.display="none";
	document.getElementById("No_Polisiext").value="";
	document.getElementById("Supirext").value="";
	//document.getElementById("form1").submit();
	}
	if(x == 2){
	document.getElementById("internal").style.display="none";
	document.getElementById("internal2").style.display="none";
	document.getElementById("eksternal").style.display="inline";
	document.getElementById("No_Polisiint").value="kosong";
	document.getElementById("Supirint").value="kosong";
	document.getElementById("TM1").value="kosong";
	document.getElementById("TM2").value="kosong";
	document.getElementById("rowTM2").style.visibility="hidden";
	document.getElementById("TM3").value="kosong";
	document.getElementById("rowTM3").style.visibility="hidden";
	document.getElementById("SubmitFormint").style.visibility="hidden";
	document.getElementById("SubmitFormext").style.visibility="visible";
	}
}

function formSubmit(x)
{
	if(x == 1){
	document.getElementById("formTM1").submit();
	document.getElementById("SubmitFormint").style.visibility="visible";
	//alert ("tes1");
	}
	if(x == 2){
	document.getElementById("formTM2").submit();
	//alert ("tes2");
	}
	if(x == 3){
	document.getElementById("formTM3").submit();
	//alert ("tes3");
	}	
	
	if(x == 4){
	document.getElementById("hidFormint").submit();
	//alert ("tes4");
	}
	
	if(x == 5){
	document.getElementById("Formext").submit();
	}
	
	if(x == 6){
	document.getElementById("form1").submit();
	}	
}

</script>

<link href="../../calendar/calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../../calendar/calendar/calendar.js"></script>

<?
require_once('../../calendar/calendar/classes/tc_calendar.php');
}
else{
$_SESSION[err] = "tolong login dulu!";
header("location:../../login.php");
}
?>


<style type="text/css">
<!--tambahan-->
   body {
       margin:0; padding:0;
   }
   html, body, #background {
       height:auto;
       width:100%;
   }
   #background {
       position:absolute; 
       left:0;
       right:0;
       bottom:0;
       top:-20px;
       overflow:hidden;
       z-index:0;
   }
   #background img {
       width:100%;
	   height:100%;
       min-width:100%;
       min-height:100%;
	   max-height:100%;
	   max-width:100%;
	   
   }
   #isi {
	   position:fixed; 
       left:0;
       right:0;
       bottom:0;
       top:0;
       z-index:1;
	   overflow:scroll;
   }  
   <!--tambahan-->
</style>
<div id="background">
   <img style="display:block;" src="../../image/greenBack.png">
</div>
<div id="isi">
<table width="805" height="424" border="0" align="center">
  <tr style="background:url(../../image/logo.png) no-repeat; background-position:center">
    <th width="301" height="115" scope="row">&nbsp;</th>
  </tr>
  <tr>
    <th height="372" scope="row" valign="top"><table width="799" border="0" align="center">
      <tr style="color:#FFF">
        <th width="295" align="left" scope="row" valign="baseline"><span style="font:normal">Welcome,</span> <span style="font:bold"><?=$Emp_Name?> (<?=$username?>)</span></th>
      </tr>
      <tr style="font-style: italic; color:#FFF">
        <th align="left" scope="row">Bussiness Area :
          <?=$subID_BA_Afd?></th>
      </tr>
      <tr style="font-style: italic; color:#FFF">
        <th align="left" scope="row">Job Code/  Login Type:
          <?=$Job_Code?> / <?=$Jenis_Login?></th>
      </tr>
      <tr>
        <td height="201" scope="row" style="font:Georgia, 'Times New Roman', Times, serif; font-size:18px ; font-style: italic; color: #8A0000" align="center" valign="top">
        
        <table width="779" height="194" border="0"  align="center">
          <tr>
            <th height="46" style="font:Georgia, 'Times New Roman', Times, serif; font-size:24px ; font-style: italic; color: #666666; border-bottom:double #666666" scope="row">Edit Hasil Panen</th>
          </tr>
          <tr>
            <th height="32" scope="row"><table width="690" border="0">
              <tr>
                <th width="445" height="34" align="left" scope="row"><a href="../../welcome.php">
                  <input type="button" name="Home" id="Home" value="Home" style="width:140px; height:30px; color:#333"/>
                </a></th>
                <td width="447" align="right"><a href="../../db_disconnect.php">
                  <input type="button" name="Logout" id="Logout" value="Logout" style="width:140px; height:30px; color:#333"/>
                </a></td>
              </tr>
            </table></th>
          </tr>
          <tr>
            <th height="32" align="left" scope="row">
            <form id="form1" name="form1" method="post" action="EditStatusBCC&NAB.php">
              <table width="787" border="0" id="delivered" style="display:inline">
                <!--HERE-->
                <?
                
								
				//echo  "No_NAB ".$_SESSION['No_NAB']. "date " .$_SESSION['date3']."no_polisiext " .$_SESSION['No_Polisiext']."no_polisiint " .$_SESSION['No_Polisiint'];
				
				
				?>
                
                <tr>
                  <td width="243" style="border-bottom:dotted #666666">No_NAB</td>
                  <td width="8">:</td>
                  <td width="559" style="border-bottom:dotted #666666"><input name="No_NAB" type="text" id="No_NAB" value="<?=$_SESSION['No_NAB']?>"/></td>
                </tr>
				
				<?
				if(isset($_SESSION['date3'])){
				echo "<tr>
                  <td style=\"border-bottom:dotted #666666\">Previous Selected Tgl_NAB</td>
                  <td>:</td>
                  <td style=\"border-bottom:dotted #666666\"> <input name=\"getdate3\" type=\"text\" id=\"getdate3\" value=\"$_SESSION[date3]\" style=\"width:70px\" onmousedown=\"return false\"/></td>
                </tr>";
				
				}
				?>
				
                <tr>
                  <td style="border-bottom:dotted #666666">Tgl_NAB</td>
                  <td>:</td>
                  <td style="border-bottom:dotted #666666"><?
				  $myCalendar = new tc_calendar("date3", true, false);
				  $myCalendar->setIcon("../../calendar/calendar/images/iconCalendar.gif");
				  //$myCalendar->setDate(date('d'), date('m'), date('Y'));
				  $myCalendar->setPath("../../calendar/calendar/");
				  $myCalendar->setYearInterval(2013, 2028);
				  $myCalendar->dateAllow('2013-01-01', '2028-12-31');
				  $myCalendar->setDateFormat('j F Y');
				  //$myCalendar->setHeight(350);
				  $myCalendar->autoSubmit(true, "form1");
				  $myCalendar->setAlignment('left', 'bottom');
				  //$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
				  //$myCalendar->setSpecificDate(array("2011-04-10", "2011-04-14"), 0, 'month');
				  //$myCalendar->setSpecificDate(array("2011-06-01"), 0, '');
				  $myCalendar->writeScript();
				  
				  ?></td>
                </tr>
				
				
                <tr>
                  <td style="border-bottom:dotted #666666">Eksternal/ Internal Order</td>
                  <td>:</td>
                  <td><a href="EditBCC&NABunset.php"><input type="button" name="INTERNAL" id="INTERNAL" value="INTERNAL" onclick="change(1)"/></a>
                    <input type="button" name="EKSTERNAL" id="EKSTERNAL" value="EKSTERNAL" onclick="change(2)"/></td>
                </tr>
                <tr>
                  <td colspan="3">
				  
				  
				  
				  <!-- INTERNAL -->
				  <table width="818" border="0" id="internal" style="display:inline">
				  <tr>
					<td width="242" style="border-bottom:dotted #666666">No_Polisi</td>
					<td width="10">:</td>
					<td width="557" style="border-bottom:dotted #666666"><?
						$selectoNPint = "<select name=\"No_Polisiint\" id=\"No_Polisiint\" onchange=\"formSubmit(6)\">";
						$optiondefNPint = "<option value=\"kosong\">-- select table --</option>";
						echo $selectoNPint.$optionPostNoint.$optiondefNPint;
						for($x=0; $x < $jumlahIN; $x++){			
							echo "<option value=\"$ID_Internal_OrderSBCC[$x]\">$ID_Internal_OrderSBCC[$x] - $No_PolisiSBCC[$x] - $ID_BASBCC[$x]</option>"; 
						}
						$selectcNPint = "</select>";
						echo $selectcNPint;
					?></td>
				  </tr>
				  <tr>
					<td style="border-bottom:dotted #666666">Supir</td>
					<td>:</td>
					<td style="border-bottom:dotted #666666">
					<?
						$selectoSint = "<select name=\"Supirint\" id=\"Supirint\" onchange=\"formSubmit(6)\">";
						$optiondefSint = "<option value=\"kosong\">-- select table --</option>";
						echo $selectoSint.$optionPostSupint.$optiondefSint;
						for($x=0; $x < $jumlahSUPIR; $x++){			  
							echo "<option value=\"$NIKSBCCS[$x]\">$NIKSBCCS[$x] - $Emp_NameSBCCS[$x] - $Job_CodeSBCCS[$x]</option>"; 
						}
						$selectcSint = "</select>";
						echo $selectcSint;
					?>
					</td>
				  </tr>
				  <!-- BOTH
					<tr>
					  <td>Input Ticket</td>
					  <td>:</td>
					  <td><input name="ticketint" type="text" id="$Qty4"></td>
					</tr>
					-->
				</table>
				
				
				</td>
                </tr>
              </table>
              </form>
			  
			  
			  <form id="Formext" name="Formext" method="post" action="RunEditStatusBCC&NAB.php">
                <!-- EKSTERNAL -->
				<table width="818" border="0" id="eksternal" style="display:none">
				  <!--HERE-->
				  <tr>
					<td width="196" style="border-bottom:dotted #666666">No_Polisi</td>
					<td width="18">:</td>
					<td width="452"><input name="No_Polisiext" type="text" id="No_Polisiext" /></td>
				  </tr>
				  <tr>
					<td style="border-bottom:dotted #666666">Supir</td>
					<td>:</td>
					<td><input name="Supirext" type="text" id="Supirext" /></td>
				  </tr>
				  <!-- BOTH 
					<tr>
					  <td>Input Ticket</td>
					  <td>:</td>
					  <td><input name="ticketext" type="text" id="$Qty3"></td>
					</tr>
					-->
				</table>
				<input name="Status_BCC" type="text" id="Status_BCC" value="DELIVERED" style="display:none"/>
        		<input name="No_NABin" type="text" id="No_NABin" style="display:none" value="<?=$_SESSION['No_NAB']?>"/>
				<input name="getdate3" type="text" id="getdate3" style="display:none" value="<?=$_SESSION['date3']?>"/>
				</form>
				
              <table width="822" border="0" id="internal2" style="display:inline">
                <tr id="rowTM1" style="visibility:visible">
                  <td height="60" width="239" style="border-bottom:dotted #666666">Tukang Muat 1</td>
                  <td width="8">:</td>
                  <td width="553" valign="bottom" style="border-bottom:dotted #666666">
                  <form id="formTM1" name="formTM1" method="get" action="EditStatusBCC&NAB.php?TM1=<?=$_GET[TM1]?>">
					 <?
                        $selectoTM1 = "<select name=\"TM1\" id=\"TM1\" onchange=\"formSubmit(1);\">";
                        $optiondefTM1 = "<option value=\"kosong\">-- select table --</option>";							
                        echo $selectoTM1.$optionGetTM1.$optiondefTM1;
                        for($y=0; $y < $jumlahTM; $y++){											
                            echo "<option value=\"$NIKSBCCTM[$y]\">$NIKSBCCTM[$y] - $Emp_NameSBCCTM[$y] - $Job_CodeSBCCTM[$y]</option>"; 
							/*<option   if($_POST[TM1])  value=\"$NIKSBCCTM[$y]\">$NIKSBCCTM[$y] - $Emp_NameSBCCTM[$y] - $Job_CodeSBCCTM[$y]</option> */
                        }
                        $selectcTM1 = "</select>";
                        echo $selectcTM1;
                                                    
                        /*$a =  "<script> var e = document.getElementById('TM1'); strUser = e.options[e.selectedIndex].value; alert (strUser); </script>";
                        if($a != kosong ){
                        echo $a;	}*/
                     ?>
                  </form>
                  </td>
                </tr>
                <tr id="rowTM2" style="visibility:<?=$visiTM2?>">
                  <td height="60" style="border-bottom:dotted #666666">Tukang Muat 2</td>
                  <td>:</td>
                  <td valign="bottom" style="border-bottom:dotted #666666">
                  <form id="formTM2" name="formTM2" method="get" action="EditStatusBCC&NAB.php?TM2=<?=$_GET[TM2]?>">
					 <?
                        $selectoTM2 = "<select name=\"TM2\" id=\"TM2\" onchange=\"formSubmit(2)\">";
                        $optiondefTM2 = "<option value=\"kosong\">-- select table --</option>";
                        echo $selectoTM2.$optionGetTM2.$optiondefTM2;
                        for($y=0; $y < $jumlahTM; $y++){											
                            echo "<option value=\"$NIKSBCCTM[$y]\">$NIKSBCCTM[$y] - $Emp_NameSBCCTM[$y] - $Job_CodeSBCCTM[$y]</option>"; 
                        }
                        $selectcTM2 = "</select>";
                        echo $selectcTM2;
                     ?>
                  </form>
                  </td>
                </tr>
                <tr id="rowTM3" style="visibility:<?=$visiTM3?>">
                  <td height="60" style="border-bottom:dotted #666666">Tukang Muat 3</td>
                  <td>:</td>
                  <td valign="bottom" style="border-bottom:dotted #666666">
                  <form id="formTM3" name="formTM3" method="get" action="EditStatusBCC&NAB.php?TM3=<?=$_GET[TM3]?>">
                    <!--<select name="TM1" id="TM1" onchange="formSubmit()">
						<option value="kosong">-- select table --</option>
						<option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
						</select>
                        </form> -->
					<?
                        $selectoTM3 = "<select name=\"TM3\" id=\"TM3\" onchange=\"formSubmit(3)\">";
                        $optiondefTM3 = "<option value=\"kosong\">-- select table --</option>";
                        echo $selectoTM3.$optionGetTM3.$optiondefTM3;
                        for($y=0; $y < $jumlahTM; $y++){	
                            echo "<option value=\"$NIKSBCCTM[$y]\">$NIKSBCCTM[$y] - $Emp_NameSBCCTM[$y] - $Job_CodeSBCCTM[$y]</option>"; 
                        }
                        $selectcTM3 = "</select>";
                        echo $selectcTM3; 
                    ?>
                  </form>
                  </td>
                </tr>
                <!-- BOTH
                        <tr>
                          <td>Input Ticket</td>
                          <td>:</td>
                          <td><input name="ticketint" type="text" id="$Qty4"></td>
                        </tr>
                        -->
                </table>

              <!--<form id="formTM" name="formTM" method="post" action="EditStatusBCC.php"> -->
              <!--</form> -->

              
            </th>
            </tr>
          <tr>
            <th height="32" scope="row"><input type="submit" name="SubmitFormint" id="SubmitFormint" value="Submit" style="visibility:<?=$visisubint?>" onclick="formSubmit(4)"/></th>
          </tr>
          <tr>
            <th height="32" scope="row"><input type="submit" name="SubmitFormext" id="SubmitFormext" value="Submit" style="visibility:<?=$visisubext?>" onclick="formSubmit(5)"/></th>
          </tr>
        </table>
        <?php
			$err = $_SESSION[err];
			if($err!=null){
				echo $err;
				unset($_SESSION[err]);
			}
		?>
        
        
        </td>
      </tr>
      <tr>
        <th scope="row"><form id="hidFormint" name="hidFormint" method="post" action="RunEditStatusBCC&NAB.php">
        <input name="Status_BCC" type="text" id="Status_BCC" value="DELIVERED" style="display:none"/>
        <input name="No_NABin" type="text" id="No_NABin" style="display:none" value="<?=$_SESSION['No_NAB']?>"/>
		<input name="getdate3" type="text" id="getdate3" style="display:none" value="<?=$_SESSION['date3']?>"/>
		<input name="No_Polisiintin" type="text" id="No_Polisiintin" style="display:none" value="<?=$_SESSION['No_Polisiint']?>"/>
		<input name="Supirintin" type="text" id="Supirintin" style="display:none" value="<?=$_SESSION['Supirint']?>"/>
		<input name="TM1in" type="text" id="TM1in" style="display:none" value="<?=$_SESSION['TM1SBCCNAB']?>"/>
		<input name="TM2in" type="text" id="TM2in" style="display:none" value="<?=$_SESSION['TM2SBCCNAB']?>"/>
		<input name="TM3in" type="text" id="TM3in" style="display:none" value="<?=$_SESSION['TM3SBCCNAB']?>"/>

        </form>
        </th>
      </tr>
      <tr>
        <th scope="row">&nbsp;</th>
      </tr>
    </table></th>
  </tr>
  <tr style="background:url(../../image/footer.png) no-repeat; background-position:center; margin-top: -2px">
    <th height="30" scope="row" style="font-size:12px" align="center"> Copyright 2013 - Sola Interactive</th>
  </tr>
</table>
</div>
