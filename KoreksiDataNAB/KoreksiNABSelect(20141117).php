<?php
session_start();
include("../include/Header.php");
?>
<!-- LIBRARY JQUERY -->
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery.min.js"></script>
<!-- END LIBRARY JQUERY -->
<!-- TOKEN INPUT 
<script type="text/javascript" src="jquery.tokeninput.js"></script>
-->

<script type="text/javascript">
	$(document).ready(function() {
		//var bacode = $("#BASupir").val();
		
		var bacode =  $('#BASupir').find(":selected").text();
		var q =  $('#Nama_Supir').find(":selected").text();
		$("#Nama_Supir").autocomplete("userSupir.php?bacode="+bacode+"&q="+q, {
				selectFirst: true
			});
	});
</script>

   <script type="text/javascript">
					$(document).ready(function() {
						//var bacode = $("#BATM2").val();
						var bacode =  $('#BATM2').find(":selected").text();
						var q =  $('#Nama_TM2').find(":selected").text();
						$("#Nama_TM2").autocomplete("userTM2.php?bacode="+bacode+"&q="+q, {
							selectFirst: true
						});
					});
				</script>
                
         <script type="text/javascript">
					$(document).ready(function() {
						//var bacode = $("#BATM3").val();
						var bacode =  $('#BATM3').find(":selected").text();
						var q =  $('#Nama_TM3').find(":selected").text();						
						$("#Nama_TM3").autocomplete("userTM3.php?bacode="+bacode+"&q="+q, {
							selectFirst: true
						});
					});
				</script>             

<link rel="stylesheet" type="text/css" href="jquery.autocomplete.css" />
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery.autocomplete.js"></script>


<link rel="stylesheet" href="token-input.css" type="text/css" />
<!-- END TOKEN INPUT -->

<?php
if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['Jenis_Login']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name']) ){	 
$Job_Code = $_SESSION['Job_Code'];
$username = $_SESSION['NIK'];	
$Emp_Name = $_SESSION['Name'];
$Jenis_Login = $_SESSION['Jenis_Login'];
$Comp_Name = $_SESSION['Comp_Name'];
$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
$Date = $_SESSION['Date'];
$ID_Group_BA = $_SESSION['ID_Group_BA'];

//$editNO_NAB = $_GET['editNO_NAB'];

$tes = "none";
	
	if($username == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:login.php");
	}
	else{
		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();

		if(isset($_POST["editNO_NAB"])){
		$_SESSION["editNO_NAB"] = $_POST["editNO_NAB"];
		}
		
		if(isset($_SESSION["editNO_NAB"])){
		$sesNO_NAB = $_SESSION["editNO_NAB"];
		}
		
		else{
			$_SESSION[err] = "Please choose";
			header("location:KoreksiNABFil.php");
		}

		$sql_t_NAB = $_SESSION["sql_t_NAB"];
		//echo $sql_t_NAB ;
		$result_t_NAB = oci_parse($con, $sql_t_NAB);
		oci_execute($result_t_NAB, OCI_DEFAULT);
		while(oci_fetch($result_t_NAB)){
			$sID_CC[]				= oci_result($result_t_NAB, "ID_CC");
			$sCOMP_NAME[] 			= oci_result($result_t_NAB, "COMP_NAME");
			$sID_BA[] 				= oci_result($result_t_NAB, "ID_BA");
			$sID_AFD[] 				= oci_result($result_t_NAB, "ID_AFD");
			$sNO_POLISI				= oci_result($result_t_NAB, "NO_POLISI");
			$sTIPE_ORDER 			= oci_result($result_t_NAB, "TIPE_ORDER");
			$sID_INTERNAL_ORDER 	= oci_result($result_t_NAB, "ID_INTERNAL_ORDER");
			$sNO_NAB[] 				= oci_result($result_t_NAB, "NO_NAB");
			$NIK_Supir 				= oci_result($result_t_NAB, "NIK_SUPIR");
			$NIK_TM1 				= oci_result($result_t_NAB, "NIK_TUKANG_MUAT1");
			$NIK_TM2 				= oci_result($result_t_NAB, "NIK_TUKANG_MUAT2");
			$NIK_TM3 				= oci_result($result_t_NAB, "NIK_TUKANG_MUAT3");
			$TGL_NAB				= oci_result($result_t_NAB, "TGL_NAB"); //added by NB 25.08.2014
		}
		$temp_NAB = $sNO_NAB[0];
		$roweffec_NAB = oci_num_rows($result_t_NAB);
		//echo $sql_t_NAB;
		$Nama_Supir	= "";
		$Afd_Supir  = "";
		$BA_Supir = "";
		$sql_default_Nama_Afd_Supir = "select te.emp_name, ta.id_afd, ta.id_ba from t_employee te
		inner join t_afdeling ta on te.id_ba_afd = ta.id_ba_afd where te.NIK ='$NIK_Supir'";
		$result_default_Nama_Afd_Supir = oci_parse($con, $sql_default_Nama_Afd_Supir);
		oci_execute($result_default_Nama_Afd_Supir, OCI_DEFAULT);
		while(oci_fetch($result_default_Nama_Afd_Supir)){
			$Nama_Supir		= oci_result($result_default_Nama_Afd_Supir, "EMP_NAME");
			$Afd_Supir 		= oci_result($result_default_Nama_Afd_Supir, "ID_AFD");
			$BA_Supir		= oci_result($result_default_Nama_Afd_Supir, "ID_BA");
		}
		//echo "<br><br>".$sql_default_Nama_Afd_Supir;
		//echo $Afd_Supir." & ". $BA_Supir. $sql_default_Nama_Afd_Supir;
		
		$Nama_TM1	= "";
		$Afd_TM1  	= "";
		$BA_TM1 = "";
		$sql_default_Nama_Afd_TM1 = "select te.emp_name, ta.id_afd, ta.id_ba from t_employee te
		inner join t_afdeling ta on te.id_ba_afd = ta.id_ba_afd where te.NIK ='$NIK_TM1'";
		$result_default_Nama_Afd_TM1 = oci_parse($con, $sql_default_Nama_Afd_TM1);
		oci_execute($result_default_Nama_Afd_TM1, OCI_DEFAULT);
		while(oci_fetch($result_default_Nama_Afd_TM1)){
			$Nama_TM1		= oci_result($result_default_Nama_Afd_TM1, "EMP_NAME");
			$Afd_TM1 		= oci_result($result_default_Nama_Afd_TM1, "ID_AFD");
			$BA_TM1			= oci_result($result_default_Nama_Afd_TM1, "ID_BA");
		}
		
		$Nama_TM2	= "";
		$Afd_TM2 	= "";
		$BA_TM2 = "";
		$sql_default_Nama_Afd_TM2 = "select te.emp_name, ta.id_afd, ta.id_ba from t_employee te
		inner join t_afdeling ta on te.id_ba_afd = ta.id_ba_afd where te.NIK ='$NIK_TM2'";
		$result_default_Nama_Afd_TM2 = oci_parse($con, $sql_default_Nama_Afd_TM2);
		oci_execute($result_default_Nama_Afd_TM2, OCI_DEFAULT);
		while(oci_fetch($result_default_Nama_Afd_TM2)){
			$Nama_TM2		= oci_result($result_default_Nama_Afd_TM2, "EMP_NAME");
			$Afd_TM2		= oci_result($result_default_Nama_Afd_TM2, "ID_AFD");
			$BA_TM2			= oci_result($result_default_Nama_Afd_TM2, "ID_BA");
		}
		
		$Nama_TM3	= "";
		$Afd_TM3  	= "";
		$BA_TM3 = "";
		$sql_default_Nama_Afd_TM3 = "select te.emp_name, ta.id_afd, ta.id_ba from t_employee te
		inner join t_afdeling ta on te.id_ba_afd = ta.id_ba_afd where te.NIK ='$NIK_TM3'";
		$result_default_Nama_Afd_TM3 = oci_parse($con, $sql_default_Nama_Afd_TM3);
		oci_execute($result_default_Nama_Afd_TM3, OCI_DEFAULT);
		while(oci_fetch($result_default_Nama_Afd_TM3)){
			$Nama_TM3		= oci_result($result_default_Nama_Afd_TM3, "EMP_NAME");
			$Afd_TM3		= oci_result($result_default_Nama_Afd_TM3, "ID_AFD");
			$BA_TM3			= oci_result($result_default_Nama_Afd_TM3, "ID_BA");
		}
		
		//BA SUPIR
		if(isset($_POST['BASupir'])){
			$_SESSION['BASupir'] = $_POST['BASupir'];
		}
		
		if(isset($_SESSION['BASupir'])){
			$ses_BASupir = $_SESSION['BASupir'];
			if($ses_BASupir  == ""){
				
				$sql_BASupir = "select ID_BA from t_alternate_ba_group 
				WHERE ID_GROUP_BA = '$ID_Group_BA'
				order by id_ba";
				$optionBASupir = "";
			}
			else{
				$sql_BASupir = "select ID_BA from t_alternate_ba_group 
				WHERE ID_GROUP_BA = '$ID_Group_BA' and ID_BA != '$ses_BASupir'
				order by id_ba";
				$optionBASupir = "<option value=\"$ses_BASupir\" selected=\"selected\">$ses_BASupir</option>";
			}
		}
		else{
			if($BA_Supir == ""){
				$sql_BASupir = "select ID_BA from t_alternate_ba_group 
					WHERE ID_GROUP_BA = '$ID_Group_BA'
					order by id_ba";
				$optionBASupir = "";
			}
			else{
				$sql_BASupir = "select ID_BA from t_alternate_ba_group 
					WHERE ID_GROUP_BA = '$ID_Group_BA' and ID_BA != '$BA_Supir'
					order by id_ba";
				$optionBASupir = "<option value=\"$BA_Supir\" selected=\"selected\">$BA_Supir</option>";	
			}
		}			
		$result_BASupir = oci_parse($con, $sql_BASupir);
		oci_execute($result_BASupir, OCI_DEFAULT);
		while(oci_fetch($result_BASupir)){
			$ID_BASupir[]		= oci_result($result_BASupir, "ID_BA");	
		}
		$roweffec_BASupir = oci_num_rows($result_BASupir);
		
		//BA_TM1
		if(isset($_POST['BATM1'])){
			$_SESSION['BATM1'] = $_POST['BATM1'];
		}
		
		if(isset($_SESSION['BATM1'])){
			$ses_BATM1 = $_SESSION['BATM1'];
			if($ses_BATM1  == ""){
				$sql_BATM1 = "select ID_BA from t_alternate_ba_group 
				WHERE ID_GROUP_BA = '$ID_Group_BA'
				order by id_ba";
				$optionBATM1 = "";
			}
			else{
				$sql_BATM1 = "select ID_BA from t_alternate_ba_group 
				WHERE ID_GROUP_BA = '$ID_Group_BA' and  ID_BA != '$ses_BATM1'
				order by id_ba";
				$optionBATM1 = "<option value=\"$ses_BATM1\" selected=\"selected\">$ses_BATM1</option>";
			}
		}
		else{
			if($BA_TM1 ==""){
				$sql_BATM1 = "select ID_BA from t_alternate_ba_group 
					WHERE ID_GROUP_BA = '$ID_Group_BA'
					order by id_ba";
				$optionBATM1 = "";
			}
			else{
				$sql_BATM1 = "select ID_BA from t_alternate_ba_group 
					WHERE ID_GROUP_BA = '$ID_Group_BA' and  ID_BA != '$BA_TM1'
					order by id_ba";
				$optionBATM1 = "<option value=\"$BA_TM1\" selected=\"selected\">$BA_TM1</option>";
			}
		}			
		$result_BATM1 = oci_parse($con, $sql_BATM1);
		oci_execute($result_BATM1, OCI_DEFAULT);
		while(oci_fetch($result_BATM1)){
			$ID_BATM1[]		= oci_result($result_BATM1, "ID_BA");	
		}
		$roweffec_BATM1 = oci_num_rows($result_BATM1);
		
		
		//BA_TM2
		if(isset($_POST['BATM2'])){
			$_SESSION['BATM2'] = $_POST['BATM2'];
		}
		
		if(isset($_SESSION['BATM2'])){
			$ses_BATM2 = $_SESSION['BATM2'];
			if($ses_BATM2  == ""){
				$sql_BATM2 = "select ID_BA from t_alternate_ba_group 
				WHERE ID_GROUP_BA = '$ID_Group_BA'
				order by id_ba";
				$optionBATM2 = "";
			}
			else{
				$sql_BATM2 = "select ID_BA from t_alternate_ba_group 
				WHERE ID_GROUP_BA = '$ID_Group_BA' and ID_BA != '$ses_BATM2'
				order by id_ba";
				$optionBATM2 = "<option value=\"$ses_BATM2\" selected=\"selected\">$ses_BATM2</option>";
			}
		}
		else{
			if($BA_TM2 ==""){
				$sql_BATM2 = "select ID_BA from t_alternate_ba_group 
					WHERE ID_GROUP_BA = '$ID_Group_BA'
					order by id_ba";
				$optionBATM2 = "";
			}
			else{
				$sql_BATM2 = "select ID_BA from t_alternate_ba_group 
					WHERE ID_GROUP_BA = '$ID_Group_BA' and ID_BA != '$BA_TM2'
					order by id_ba";
				$optionBATM2 = "<option value=\"$BA_TM2\" selected=\"selected\">$BA_TM2</option>";
			}
		}	
		$result_BATM2 = oci_parse($con, $sql_BATM2);
		oci_execute($result_BATM2, OCI_DEFAULT);
		while(oci_fetch($result_BATM2)){
			$ID_BATM2[]		= oci_result($result_BATM2, "ID_BA");	
		}
		$roweffec_BATM2 = oci_num_rows($result_BATM2);
		
		
		//BA_TM3
		if(isset($_POST['BATM3'])){
			$_SESSION['BATM3'] = $_POST['BATM3'];
		}
		
		if(isset($_SESSION['BATM3'])){
			$ses_BATM3 = $_SESSION['BATM3'];
			if($ses_BATM3  == ""){
				$sql_BATM3 = "select ID_BA from t_alternate_ba_group 
				WHERE ID_GROUP_BA = '$ID_Group_BA'
				order by id_ba";
				$optionBATM3 = "";
			}
			else{
				$sql_BATM3 = "select ID_BA from t_alternate_ba_group 
				WHERE ID_GROUP_BA = '$ID_Group_BA' and ID_BA != '$ses_BATM3'
				order by id_ba";
				$optionBATM3 = "<option value=\"$ses_BATM3\" selected=\"selected\">$ses_BATM3</option>";
			}
		}
		else{
			if($BA_TM3 ==""){
				$sql_BATM3 = "select ID_BA from t_alternate_ba_group 
					WHERE ID_GROUP_BA = '$ID_Group_BA'
					order by id_ba";
				$optionBATM3 = "";
			}
			else{
				$sql_BATM3 = "select ID_BA from t_alternate_ba_group 
					WHERE ID_GROUP_BA = '$ID_Group_BA' and ID_BA != '$BA_TM3'
					order by id_ba";
				$optionBATM3 = "<option value=\"$BA_TM3\" selected=\"selected\">$BA_TM3</option>";
			}
		}			
		$result_BATM3 = oci_parse($con, $sql_BATM3);
		oci_execute($result_BATM3, OCI_DEFAULT);
		while(oci_fetch($result_BATM3)){
			$ID_BATM3[]		= oci_result($result_BATM3, "ID_BA");	
		}
		$roweffec_BATM3 = oci_num_rows($result_BATM3);
		
		
		
		if(isset($_POST['Nama_Supir'])){
			$_SESSION['Nama_Supir'] = $_POST['Nama_Supir'];
			$tes = "none";
		}
		if(isset($_SESSION['Nama_Supir'])){
			$sesSupir = $_SESSION['Nama_Supir'];
		}
		
		if(isset($_POST['No_PolisiLabel']) && isset($_POST['Id_Internal_OrderLabel'])){
			$_SESSION['No_PolisiLabel'] 		= $_POST['No_PolisiLabel'];
			$_SESSION['Id_Internal_OrderLabel'] = $_POST['Id_Internal_OrderLabel'];
		}
		
		if(isset($_SESSION['No_PolisiLabel']) && isset($_SESSION['Id_Internal_OrderLabel'])){
			$sNO_POLISI			= $_SESSION['No_PolisiLabel'];
			$sID_INTERNAL_ORDER = $_SESSION['Id_Internal_OrderLabel'];
		}
	
		if(isset($_POST['NIK_Supir']) && isset($_POST['Afd_Supir']) && isset($_POST['Nama_Supir'])){
			$_SESSION['NIK_Supir'] = $_POST['NIK_Supir'];
			$_SESSION['Afd_Supir'] = $_POST['Afd_Supir'];
			$_SESSION['Nama_Supir'] = $_POST['Nama_Supir'];
		}
		
		if(isset($_SESSION['NIK_Supir']) && isset($_SESSION['Afd_Supir']) && isset($_SESSION['Nama_Supir'])){
			$NIK_Supir = $_SESSION['NIK_Supir'];
			$Afd_Supir = $_SESSION['Afd_Supir'];
			$Nama_Supir = $_SESSION['Nama_Supir'];
		}
		
		if(isset($_POST['NIK_TM1']) && isset($_POST['Afd_TM1']) && isset($_POST['Nama_TM1'])){
			$_SESSION['NIK_TM1'] = $_POST['NIK_TM1'];
			$_SESSION['Afd_TM1'] = $_POST['Afd_TM1'];
			$_SESSION['Nama_TM1'] = $_POST['Nama_TM1'];
		}
				
		if(isset($_SESSION['NIK_TM1']) && isset($_SESSION['Afd_TM1']) && isset($_SESSION['Nama_TM1'])){
			$NIK_TM1 = $_SESSION['NIK_TM1'];
			$Afd_TM1 = $_SESSION['Afd_TM1'];
			$Nama_TM1 = $_SESSION['Nama_TM1'];
		}
		
		if(isset($_POST['NIK_TM2']) && isset($_POST['Afd_TM2']) && isset($_POST['Nama_TM2'])){
			$_SESSION['NIK_TM2'] = $_POST['NIK_TM2'];
			$_SESSION['Afd_TM2'] = $_POST['Afd_TM2'];
			$_SESSION['Nama_TM2'] = $_POST['Nama_TM2'];
		}
				
		if(isset($_SESSION['NIK_TM2']) && isset($_SESSION['Afd_TM2']) && isset($_SESSION['Nama_TM2'])){
			$NIK_TM2 = $_SESSION['NIK_TM2'];
			$Afd_TM2 = $_SESSION['Afd_TM2'];
			$Nama_TM2 = $_SESSION['Nama_TM2'];
		}
		
		if(isset($_POST['NIK_TM3']) && isset($_POST['Afd_TM3']) && isset($_POST['Nama_TM3'])){
			$_SESSION['NIK_TM3'] = $_POST['NIK_TM3'];
			$_SESSION['Afd_TM3'] = $_POST['Afd_TM3'];
			$_SESSION['Nama_TM3'] = $_POST['Nama_TM3'];
		}
				
		if(isset($_SESSION['NIK_TM3']) && isset($_SESSION['Afd_TM3']) && isset($_SESSION['Nama_TM3'])){
			$NIK_TM3 = $_SESSION['NIK_TM3'];
			$Afd_TM3 = $_SESSION['Afd_TM3'];
			$Nama_TM3 = $_SESSION['Nama_TM3'];
		}
		
		$SessTIPE_ORDER = $sTIPE_ORDER;
		$selectedTypeOrder = "";
		if(isset($_POST['sTIPE_ORDER'])){
			$_SESSION['SessTIPE_ORDER'] = $_POST['sTIPE_ORDER'];
		}
				
		if(isset($_SESSION['SessTIPE_ORDER'])){
			$SessTIPE_ORDER = $_SESSION['SessTIPE_ORDER'];
			$selectedTypeOrder = "<option value=\"$SessTIPE_ORDER\" selected=\"selected\">$SessTIPE_ORDER</option>";
		}
		
	}
	
?>

<script type="text/javascript">
function cekUpper(){
	document.getElementById('No_NABLabel').value = document.getElementById('No_NABLabel').value.toUpperCase();
}

function passValue(){
	document.getElementById('tmpNo_NAB').value = document.getElementById('No_NABLabel').value;
	document.getElementById('tglNAB').value = document.getElementById('datepicker').value;
}

function cekData(){

	var v_afd = document.getElementById('AFDlabel').value;
	var v_nab = document.getElementById('No_NABLabel').value;
	var v_ba = document.getElementById('ID_BAlabel').value;
	var vtemp_nab = document.getElementById('temp_NAB').value;
	
	if (document.getElementById("No_NABLabel").value == ""){	
		alert("NAB harus diisi!");
		return false;
	}
	if(v_nab.length != '7'){
		alert("NAB harus tepat 7 digit!");
		document.getElementById('No_NABLabel').value = vtemp_nab;
		return false;
	}
	var xhr;
	if (window.XMLHttpRequest) { // Mozilla, Safari, ...
		xhr = new XMLHttpRequest();
	} else if (window.ActiveXObject) { // IE 8 and older
		xhr = new ActiveXObject("Microsoft.XMLHTTP");
	}
	var data = "nab=" + v_nab + "&v_ba=" + v_ba + "&var_afd=" + v_afd;
	//alert(data);
	xhr.open("POST", "cekValidasi.php", true); 
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
	xhr.send(data);
	xhr.onreadystatechange = display_data;
	function display_data() {
	 if (xhr.readyState == 4) {
	  if (xhr.status == 200) {
		document.getElementById('tmpNo_NAB').value = document.getElementById('No_NABLabel').value;
		if(xhr.responseText == "kosong"){	
		}else{
			if(vtemp_nab != v_nab){
				alert("No NAB sudah pernah digunakan. mohon ubah No NAB.");
				//document.getElementById('No_NABLabel').value = "";
				document.getElementById('No_NABLabel').focus();
				//alert(xhr.responseText);
			}else{
				document.getElementById('tmpNo_NAB').value = document.getElementById('No_NABLabel').value;
			}
		}
	  //document.getElementById("suggestion").innerHTML = xhr.responseText;
	  } else {
		alert('There was a problem with the request.');
	  }
	 }
	}
}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 48 || (charCode > 57 && charCode < 65) || (charCode > 90 && charCode < 97) || (charCode > 122 && charCode < 127))) {
        return false;
    }
    return true;
}

function formSubmit(x)
{
	document.getElementById('NIK_Pemanen').value = x;
	document.getElementById("FormPemanen").submit();
}

function formSubmitvalue()
{
	//document.getElementById('NIK_Pemanen').value = x;
	document.getElementById("FormEditBCC").submit();
}

function push_data(){
	document.getElementById('tmpNo_NAB').value = document.getElementById('No_NABLabel').value;
	document.getElementById('tglNAB').value = document.getElementById('datepicker').value;
}

function send(x)
{
	if(x == "1"){
	var str1 = document.getElementById('Nama_Supir').value;
	var n=str1.split(":"); 
	//alert (str1);
	document.getElementById('NIK_Supir').value= n[0];
	document.getElementById('Nama_Supir').value= n[1];
	document.getElementById('Afd_Supir').value= n[2];
	document.getElementById('s_Supir').value= n[0];
	}
	
	
	
	if(x == "7"){
	var str1ext = document.getElementById('Nama_Supir').value;
	document.getElementById('Nama_Supir').value= str1ext;
	document.getElementById('NIK_Supir').value= "";
	document.getElementById('Afd_Supir').value= "";
	document.getElementById('s_Supir').value= str1ext;
	}
	
	if(x == "2"){
	var str2 = document.getElementById('Nama_TM1').value;
	var n=str2.split(":"); 
	//alert (str2);
	document.getElementById('NIK_TM1').value= n[0];
	document.getElementById('Nama_TM1').value= n[1];
	document.getElementById('Afd_TM1').value= n[2];
	document.getElementById('s_TM1').value= n[0];
	}
	
	if(x == "3"){
	var str3 = document.getElementById('Nama_TM2').value;
	var n=str3.split(":"); 
	//alert (str3);
	document.getElementById('NIK_TM2').value= n[0];
	document.getElementById('Nama_TM2').value= n[1];
	document.getElementById('Afd_TM2').value= n[2];
	document.getElementById('s_TM2').value= n[0];
	}
	
	if(x == "4"){
	var str4 = document.getElementById('Nama_TM3').value;
	var n=str4.split(":"); 
	//alert (str4);
	document.getElementById('NIK_TM3').value= n[0];
	document.getElementById('Nama_TM3').value= n[1];
	document.getElementById('Afd_TM3').value= n[2];
	document.getElementById('s_TM3').value= n[0];
	}
	//document.getElementById("doSubmit").submit();
	if(x == "5"){
	var str5 = document.getElementById('No_PolisiLabel').value;
	var n=str5.split(":"); 
	//alert (str3);
	document.getElementById('Id_Internal_OrderLabel').value= n[0];
	document.getElementById('No_PolisiLabel').value= n[1];
	document.getElementById('sID_INTERNAL_ORDER').value= n[0];
	document.getElementById('sNO_POLISI').value= n[1];
	}
	
	if(x == "6"){
	var str6 = document.getElementById('No_PolisiLabel').value;
	document.getElementById('Id_Internal_OrderLabel').value= "";
	document.getElementById('No_PolisiLabel').value= str6;
	document.getElementById('sID_INTERNAL_ORDER').value= "";
	document.getElementById('sNO_POLISI').value= str6;
	}
}

</script>

<link href="../css/style2.css" rel="stylesheet" type="text/css" media="all" />

<!--script type="text/javascript" src="../datepicker/js/jquery.min.js"></script-->
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
.style1 {
	color: #FF0000;
	font-weight: bold;
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
		<form id="form1" name="form1" method="post" action="KoreksiNABSelect.php">
      <table border="0" id="setbody2">
          <tr>
            <td width="120">Company Name</td>
            <td width="7">:</td>
            <td><input name="Comp_NameLabel" type="text" id="Comp_NameLabel" value="<?=$sCOMP_NAME[0]?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
            <td>&nbsp;</td>
            <td height="9"> Afdeling Panen</td>
            <td height="9">:</td>
            <td style="font-size:16px"><input name="AFDlabel" type="text" id="AFDlabel" value="<?=$sID_AFD[0]?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/></td>
          </tr>
          <tr>
            <td>Business Area</td>
            <td>:</td>
            <td><input name="ID_BAlabel" type="text" id="ID_BAlabel" value="<?=$sID_BA[0]?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/>
            </td>
            <td>&nbsp;</td>
            <td width="142">Tanggal NAB</td>
            <td width="8">:</td>
            <td width="303"><input type="text" name="datepicker" id="datepicker" onchange='push_data();' value="<?=$TGL_NAB?>" style="width:300px; height:25px; font-size:15px" readOnly="readOnly" ></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td width="142">No NAB</td>
            <td width="8">:</td>
            <td width="303"><input name="No_NABLabel" type="text" id="No_NABLabel" onkeypress='return isNumber(event)' onchange='cekUpper()' onblur='cekData()' maxlength="7" value="<?=$sNO_NAB[0]?>" style="width:300px; height:25px; font-size:15px" /><input name="temp_NAB" type="text" id="temp_NAB" value="<?=$temp_NAB?>" style="width:300px; height:25px; font-size:15px; display:none;" /></td>
          </tr>
		  <tr>
            <td colspan="7" style="border-bottom:solid #556A29">&nbsp;</td>
          </tr>
          <tr>
            <td height="10">Type Order</td>
            <td height="10">:</td>
            <td style="font-size:16px">
            <input name="TIPE_ORDERLabel" type="text" id="TIPE_ORDERLabel" value="<?=$sTIPE_ORDER?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px; display:inline" onmousedown="return false" />
            </td>
            <td height="3" align="center">&nbsp;</td>
            <?php
			if($sTIPE_ORDER == "EKSTERNAL"){
				echo "
				<td>New Type Order</td>
				<td>:</td>
				<td>
				<select name=\"sTIPE_ORDER\" id=\"sTIPE_ORDER\" style=\"font-size: 15px\" onchange=\"this.form.submit();\">
				$selectedTypeOrder
				<option value=\"EKSTERNAL\">EKSTERNAL</option>
				<option value=\"INTERNAL\">INTERNAL</option>
				</select>
				</td>"; 
			}
			else{
				echo "
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>
				&nbsp;<input name=\"sTIPE_ORDER\" type=\"text\" id=\"sTIPE_ORDER\" value=\"$sTIPE_ORDER\" style=\"width:300px; height:25px; font-size:15px; display:none\" onmousedown=\"return false\" />
				</td>";
			}
			?>
          </tr>
          <tr>
            <td>No Polisi</td>
            <td>:</td>
            <td>
            <?php
			if($SessTIPE_ORDER == "EKSTERNAL"){
            	echo "<input name=\"No_PolisiLabel\" type=\"text\" id=\"No_PolisiLabel\" value=\"$sNO_POLISI\" style=\"width: 300px; height:25px; font-size:15px\" onchange=\"send(6)\"/>"; 
			}
			else{
				echo "<input name=\"No_PolisiLabel\" type=\"text\" id=\"No_PolisiLabel\" value=\"$sNO_POLISI\" style=\"width: 300px; height:25px; font-size:15px\" onchange=\"send(5)\"/>";
			}
			?>
			<script type="text/javascript">
					$(document).ready(function() {
						var bacode = $("#ID_BAlabel").val();
						//var bacode =  $('#ID_BAlabel').find(":selected").text();
						var q =  $('#No_PolisiLabel').find(":selected").text();
						$("#No_PolisiLabel").autocomplete("userNoPol.php?bacode="+bacode+"&q="+q, {
							selectFirst: true
						});
					});
            </script>
			
              </td>
            <td height="3" align="center">&nbsp;</td>
            <?php
            if($SessTIPE_ORDER == "INTERNAL"){
				echo "<td>No Internal Order</td>
				<td>:</td>
				<td><input name=\"Id_Internal_OrderLabel\" type=\"text\" id=\"Id_Internal_OrderLabel\" 
				value=\"$sID_INTERNAL_ORDER\" style=\"background-color:#CCC; width: 300px; height:25px; font-size:15px\" 
				onmousedown=\"return false\"/></td>";
            }
			else
			{
				echo "<td style=\"display:none\">No Internal Order</td>

				<td style=\"display:none\">:</td>
				<td style=\"display:none\"><input name=\"Id_Internal_OrderLabel\" type=\"text\" id=\"Id_Internal_OrderLabel\" 
				value=\"$sID_INTERNAL_ORDER\" style=\"background-color:#CCC; width: 300px; height:25px; font-size:15px\" 
				onmousedown=\"return false\"/></td>";
			}
            ?>
          </tr>
          <tr>
            <td height="3" colspan="7" align="right"><a href="ResNoPolisiSession.php">
              <input type="button" name="DEFAULTNoPolisi" id="DEFAULTNoPolisi" value="DEFAULT" style="width:120px; height: 30px"/>
            </a></td>
          </tr>
          <?php
		  if(isset($_SESSION['err']) || isset($_SESSION['err_type_order'])){
          echo "<tr>
            <td height=\"4\" colspan=\"7\" align=\"center\" style=\"color:#F00\">";
			if(isset($_SESSION['err'])){
				$err = $_SESSION['err'];
				if($err!=null)
				{
					echo $err;
					unset($_SESSION['err']);
				}
			}
			
            echo "<br>";
            
			if(isset($_SESSION['err_type_order'])){
				$err = $_SESSION['err_type_order'];
				if($err!=null)
				{
					echo $err;
					unset($_SESSION['err_type_order']);
				}
			}
			
         echo "</td>
          </tr>";
          }
          ?>
          <tr>
            <td height="19" colspan="7" align="center" style="border-bottom:solid #556A29; border-top:solid #556A29"><strong>Data Supir</strong></td>
          </tr>
          
        <?php
		if($SessTIPE_ORDER == "INTERNAL"){
		  echo "
          <tr>
            <td valign=\"top\">Business Area</td>
            <td valign=\"top\">:</td>
            <td width=\"305\" valign=\"top\">";
				//$jumlahAfd = $_SESSION['jumlahAfd'];
				$selectoBASupir = "<select name=\"BASupir\" id=\"BASupir\" style=\"font-size: 15px\"  onchange=\"this.form.submit();\" >";
				echo $selectoBASupir.$optionBASupir;
				for($xBASupir = 0; $xBASupir < $roweffec_BASupir; $xBASupir++){
					echo "<option value=\"$ID_BASupir[$xBASupir]\">$ID_BASupir[$xBASupir]</option>"; 
				}
				$selectcBASupir = "</select>";
				echo $selectcBASupir;        
			echo "
			  </td>
            <td width=\"108\">&nbsp;</td>
            <td valign=\"top\">Nama Supir</td>
            <td valign=\"top\">:</td>
            <td width=\"303\" valign=\"top\"><input name=\"Nama_Supir\" type=\"text\" id=\"Nama_Supir\" value=\"$Nama_Supir\" style=\"width: 300px; height:25px; font-size:15px\" onchange=\"send(1)\"/></td>
          </tr>
          <tr>
            <td>Afdeling</td>
            <td>:</td>
            <td><span style=\"font-size:16px\">
              <input name=\"Afd_Supir\" type=\"text\" id=\"Afd_Supir\" value=\"$Afd_Supir\" style=\"background-color:#CCC; width:50px; height:25px; font-size:15px\" onmousedown=\"return false\"/>
            </span></td>
            <td>&nbsp;</td>
            <td>NIK Supir</td>
            <td>:</td>
            <td><input name=\"NIK_Supir\" type=\"text\" id=\"NIK_Supir\" value=\"$NIK_Supir\" style=\"background-color:#CCC; width: 300px; height:25px; font-size:15px\" onmousedown=\"return false\"/></td>
          </tr>";
		}
		  //untuk eksternal
		else{
		  echo "
		  <tr>
            <td valign=\"top\">Nama Supir</td>
            <td valign=\"top\">:</td>
            <td width=\"303\" valign=\"top\"><input name=\"Nama_Supir\" type=\"text\" id=\"Nama_Supir\" value=\"$NIK_Supir\" style=\"background-color:#CCC; background-color:#CCC; width: 300px; height:25px; font-size:15px\" onchange=\"send(7)\" onmousedown=\"return false\"/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input name=\"NIK_Supir\" type=\"text\" id=\"NIK_Supir\" value=\"$NIK_Supir\" style=\"background-color:#CCC; width: 300px; height:25px; font-size:15px; display:none\" onmousedown=\"return false\"/></td>
            <td><input name=\"Afd_Supir\" type=\"text\" id=\"Afd_Supir\" value=\"$Afd_Supir\" style=\"background-color:#CCC; width:50px; height:25px; font-size:15px; display:none\" onmousedown=\"return false\"/></td>
          </tr>
		  ";
		}
        ?>
          <tr>
            <td height="32" colspan="7" align="right" valign="top"><a href="ResSupirSession.php">
              <input type="button" name="DEFAULTSupir" id="DEFAULTSupir" value="DEFAULT" style="width:120px; height: 30px"/>
            </a></td>
          </tr>
          
            <?php
          	if(isset($_SESSION['err_cek_supir'])){
				echo "<tr>
            <td height=\"32\" colspan=\"7\" align=\"center\" valign=\"top\">";
				$err = $_SESSION['err_cek_supir'];
				if($err!=null)
				{
					echo $err;
					unset($_SESSION['err_cek_supir']);
				}
				echo "</td>
          </tr>";
			}
			?>
          
          <tr>
            <td colspan="7" align="center"></td>
          </tr>
          <?php
        if($SessTIPE_ORDER == "INTERNAL"){
		?>
          <tr>
            <td height="37" colspan="7" align="center" style="border-bottom:solid #556A29; border-top:solid #556A29"><strong>Data Tukang Muat 1</strong></td>
          </tr>
          <tr>
            <td valign="top">Business Area</td>
            <td valign="top">:</td>
            <td width="305" valign="top"><?php
				//$jumlahAfd = $_SESSION['jumlahAfd'];
				$selectoBATM1 = "<select name=\"BATM1\" id=\"BATM1\" style=\"visibility:visible; font-size: 15px\" onchange=\"this.form.submit();\" >";
				echo $selectoBATM1.$optionBATM1;
				for($xBATM1 = 0; $xBATM1 < $roweffec_BATM1; $xBATM1++){
					echo "<option value=\"$ID_BATM1[$xBATM1]\">$ID_BATM1[$xBATM1]</option>"; 
				}
				$selectcBATM1 = "</select>";
				echo $selectcBATM1;
				          
				?></td>
            <td width="108">&nbsp;</td>
            <td valign="top">Nama Tukang Muat 1</td>
            <td valign="top">:</td>
            <td width="303" valign="top"><input name="Nama_TM1" type="text" id="Nama_TM1" value="<?=$Nama_TM1?>" style="width: 300px; height:25px; font-size:15px"  onchange="send(2)"/>
              <script type="text/javascript">
					$(document).ready(function() {
						//var bacode = $("#BATM1").val();
						var bacode =  $('#BATM1').find(":selected").text();
						var q =  $('#Nama_TM1').find(":selected").text();
						$("#Nama_TM1").autocomplete("userTM1.php?bacode="+bacode+"&q="+q, {
							selectFirst: true
						});
					});
          </script></td>
          </tr>
          <tr>
            <td>Afdeling</td>
            <td>:</td>
            <td><span style="font-size:16px">
              <input name="Afd_TM1" type="text" id="Afd_TM1" value="<?=$Afd_TM1?>" style="background-color:#CCC; width:50px; height:25px; font-size:15px" onmousedown="return false"/>
            </span></td>
            <td>&nbsp;</td>
            <td>NIK Tukang Muat 1</td>
            <td>:</td>
            <td><input name="NIK_TM1" type="text" id="NIK_TM1" value="<?=$NIK_TM1?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
          </tr>
          <tr>
            <td height="32" colspan="7" align="right" valign="top"><a href="ResTM1Session.php">
              <input type="button" name="DEFAULTTM1" id="DEFAULTTM1" value="DEFAULT" style="width:120px; height: 30px"/>
            </a></td>
          </tr>
          <tr>
            <td height="37" colspan="7" align="center" style="border-bottom:solid #556A29; border-top:solid #556A29"><strong>Data Tukang Muat 2</strong></td>
          </tr>
          <tr>
            <td valign="top">Business Area</td>
            <td valign="top">:</td>
            <td width="305" valign="top"><?php
				//$jumlahAfd = $_SESSION['jumlahAfd'];
				$selectoBATM2 = "<select name=\"BATM2\" id=\"BATM2\" style=\"visibility:visible; font-size: 15px\" onchange=\"this.form.submit();\" >";
				echo $selectoBATM2.$optionBATM2;
				for($xBATM2 = 0; $xBATM2 < $roweffec_BATM2; $xBATM2++){
					echo "<option value=\"$ID_BATM2[$xBATM2]\">$ID_BATM2[$xBATM2]</option>"; 
				}
				$selectcBATM2 = "</select>";
				echo $selectcBATM2;
				          
				?></td>
            <td width="108">&nbsp;</td>
            <td valign="top">Nama Tukang Muat 2</td>
            <td valign="top">:</td>
            <td width="303" valign="top"><input name="Nama_TM2" type="text" id="Nama_TM2" value="<?=$Nama_TM2?>" style="width: 300px; height:25px; font-size:15px"  onchange="send(3)"/></td>
          </tr>
          <tr>
            <td>Afdeling</td>
            <td>:</td>
            <td><span style="font-size:16px">
              <input name="Afd_TM2" type="text" id="Afd_TM2" value="<?=$Afd_TM2?>" style="background-color:#CCC; width:50px; height:25px; font-size:15px" onmousedown="return false"/>
            </span></td>
            <td>&nbsp;</td>
            <td>NIK Tukang Muat 2</td>
            <td>:</td>
            <td><input name="NIK_TM2" type="text" id="NIK_TM2" value="<?=$NIK_TM2?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
          </tr>
          <tr>
            <td height="33" colspan="7" align="right" valign="top"><a href="ResTM2Session.php">
              <input type="button" name="DEFAULTTM2" id="DEFAULTTM2" value="DEFAULT" style="width:120px; height: 30px"/>
            </a></td>
          </tr>
          <tr>
            <td height="38" colspan="7" align="center" style="border-bottom:solid #556A29; border-top:solid #556A29"><strong>Data Tukang Muat 3</strong></td>
          </tr>
          <tr>
            <td valign="top">Business Area</td>
            <td valign="top">:</td>
            <td width="305" valign="top"><?php
				//$jumlahAfd = $_SESSION['jumlahAfd'];
				$selectoBATM3 = "<select name=\"BATM3\" id=\"BATM3\" style=\"visibility:visible; font-size: 15px\" onchange=\"this.form.submit();\" >";
				echo $selectoBATM3.$optionBATM3;
				for($xBATM3 = 0; $xBATM3 < $roweffec_BATM3; $xBATM3++){
					echo "<option value=\"$ID_BATM3[$xBATM3]\">$ID_BATM3[$xBATM3]</option>"; 
				}
				$selectcBATM3 = "</select>";
				echo $selectcBATM3;
				          
				?></td>
            <td width="108">&nbsp;</td>
            <td valign="top">Nama Tukang Muat 3</td>
            <td valign="top">:</td>
            <td width="303" valign="top"><input name="Nama_TM3" type="text" id="Nama_TM3" value="<?=$Nama_TM3?>" style="width: 300px; height:25px; font-size:15px"  onchange="send(4)"/></td>
          </tr>
          <tr>
            <td>Afdeling</td>
            <td>:</td>
            <td><span style="font-size:16px">
              <input name="Afd_TM3" type="text" id="Afd_TM3" value="<?=$Afd_TM3?>" style="background-color:#CCC; width:50px; height:25px; font-size:15px" onmousedown="return false"/>
            </span></td>
            <td>&nbsp;</td>
            <td>NIK Tukang Muat 3</td>
            <td>:</td>
            <td><input name="NIK_TM3" type="text" id="NIK_TM3" value="<?=$NIK_TM3?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
          </tr>
          <tr>
            <td height="32" colspan="7" align="right" valign="top"><a href="ResTM3Session.php">
              <input type="button" name="DEFAULTTM3" id="DEFAULTTM3" value="DEFAULT" style="width:120px; height: 30px"/>
            </a></td>
          </tr>
          <?php
		}
		  ?>
          <tr>
            <td colspan="7" align="center" style="border-top:solid #556A29"><a href="ResALLSession.php">
              <input type="button" name="DEFAULTALL" id="DEFAULTALL" value="DEFAULT ALL" style="width:120px; height: 30px"/>
            </a></td>
          </tr>
        </table>
    </form>
    
    <table width="1031" border="0" id="setbody2">
    <tr>
          <td align="right"><form id="form1" name="form1" method="post" action="doSubmit.php">
          <input name="s_Supir" type="text" id="s_Supir" value="<?=$NIK_Supir?>" style="display:none" onmousedown="return false"/>
            <input name="s_TM1" type="text" id="s_TM1" value="<?=$NIK_TM1?>" style="display:none" onmousedown="return false"/>
            <input name="s_TM2" type="text" id="s_TM2" value="<?=$NIK_TM2?>" style="display:none" onmousedown="return false"/>
            <input name="s_TM3" type="text" id="s_TM3" value="<?=$NIK_TM3?>" style="display:none" onmousedown="return false"/>
            <input name="sTIPE_ORDER" type="text" id="sTIPE_ORDER" value="<?=$SessTIPE_ORDER?>" style="display:none" onmousedown="return false"/>
            <input name="sID_INTERNAL_ORDER" type="text" id="sID_INTERNAL_ORDER" value="<?=$sID_INTERNAL_ORDER?>" style="display:none" onmousedown="return false"/>
            <input name="sNO_POLISI" type="text" id="sNO_POLISI" value="<?=$sNO_POLISI?>" style="display:none" onmousedown="return false"/>
            <input name="editNO_NAB" type="text" id="editNO_NAB" value="<?=$_SESSION["editNO_NAB"]?>" style="display:none" onmousedown="return false"/>
            <input name="tmpNo_NAB" type="text" id="tmpNo_NAB" value="" style="display:none" onmousedown="return false"/>
            <input name="tglNAB" type="text" id="tglNAB" value="" style="display:none" onmousedown="return false"/>
            <input type="submit" name="button" id="button" value="SIMPAN" onclick="passValue();" style="width:120px; height: 30px"/>
            </form></td>
    </tr>
    </table>
    <table width="1031" border="0" id="setbody2">
    <tr>
          <td align="center"><span class="style1">Pastikan koreksi data Anda telah mendapatkan persutujan dari EM atau KABUN !!</span></td>
    </tr>
    </table>
    
    </th>
  </tr>
  <tr>
    <th align="center"><?php
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
    <th align="center"><?php include("../include/Footer.php") ?></th>
  </tr>
</table>
<?php
}
else{
	$_SESSION[err] = "tolong login dulu!".$Job_Code."<br>".$username."<br>".$Emp_Name."<br>".$Jenis_Login."<br>".$subID_BA_Afd;
	header("location:../index.php");
}
?>