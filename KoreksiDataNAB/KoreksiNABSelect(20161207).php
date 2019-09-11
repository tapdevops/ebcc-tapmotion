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
			//$_SESSION[err] = "Please choose";
			header("location:KoreksiNABFil.php");
		}

		$sql_t_NAB = $_SESSION["sql_t_NAB"];
		//echo $sql_t_NAB ;die();
		$result_t_NAB = oci_parse($con, $sql_t_NAB);
		oci_execute($result_t_NAB, OCI_DEFAULT);
		$ttl_jjg = 0;
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
			$ttl_jjg				+= oci_result($result_t_NAB, "JJG");
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
		if(isset($_POST['Nama_Supir1'])){
			$_SESSION['Nama_Supir'] = $_POST['Nama_Supir1'];
			$tes = "none";
		}
		if(isset($_POST['Nama_Supir2'])){
			$_SESSION['Nama_Supir'] = $_POST['Nama_Supir2'];
			$tes = "none";
		}

		if(isset($_SESSION['Nama_Supir'])){
			$sesSupir = $_SESSION['Nama_Supir'];
		}
		
		if(isset($_POST['No_PolisiLabel1']) && isset($_POST['Id_Internal_OrderLabel1'])){
			$_SESSION['No_PolisiLabel'] 		= $_POST['No_PolisiLabel1'];
			$_SESSION['Id_Internal_OrderLabel'] = $_POST['Id_Internal_OrderLabel1'];
		}
		
		if(isset($_POST['No_PolisiLabel2'])){
			$_SESSION['No_PolisiLabel'] 		= $_POST['No_PolisiLabel2'];
			$_SESSION['Id_Internal_OrderLabel'] = '-';
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

		if(isset($_POST['NIK_Supir1'])){
			$_SESSION['NIK_Supir'] = $_POST['NIK_Supir1'];
		}
		
		if(isset($_POST['Nama_Supir2'])){
			$_SESSION['NIK_Supir'] = $_POST['Nama_Supir2'];
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
		
		
		
		if(isset($_POST['NIK_TM1'])){
			$_SESSION['NIK_TM1'] = $_POST['NIK_TM1'];
		}
				
		if(isset($_SESSION['NIK_TM1'])){
			$NIK_TM1 = $_SESSION['NIK_TM1'];
		}
		
		if(isset($_POST['NIK_TM2'])){
			$_SESSION['NIK_TM2'] = $_POST['NIK_TM2'];
		}
				
		if(isset($_SESSION['NIK_TM2'])){
			$NIK_TM2 = $_SESSION['NIK_TM2'];
		}
		
		if(isset($_POST['NIK_TM3'])){
			$_SESSION['NIK_TM3'] = $_POST['NIK_TM3'];
		}
				
		if(isset($_SESSION['NIK_TM3'])){
			$NIK_TM3 = $_SESSION['NIK_TM3'];
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
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	//$("#add").click(function(){
		var countRow = $("#countRow").val();
		//alert(countRow);
		$.ajax({
			url: "getDataOld.php",
			data: "countRow="+ countRow,
			method: 'POST',
			cache: false,
			success: function(msg){
				//jika data sukses diambil dari server kita tampilkan
				if(msg == '0'){
					alert('Tidak ada Hasil Panen di Afdeling Tersebut');
				}else{
					
					var split = msg.split(' . # . ');
					$('#countRow').val(split[1]-1);
					$("#hasil_panen").append(split[0]);
				}
			}
		});
	//});
	var str1 = $("#TIPE_ORDERLabel").val();
	if(str1 == "INTERNAL") {
        show_internal();
		hide_eksternal();
    }else if(str1 == "EXTERNAL" || str1 == "EKSTERNAL"){
		show_eksternal();
		hide_internal();
    }else{
		hide_internal();
		hide_eksternal();
	}
	
	if(str1 == "INTERNAL"){
		document.getElementById('sNO_POLISI').value = document.getElementById('No_PolisiLabel1').value;
		document.getElementById('sID_INTERNAL_ORDER1').value = document.getElementById('Id_Internal_OrderLabel1').value;
		document.getElementById('s_Supir').value = document.getElementById('NIK_Supir1').value;
		document.getElementById('s_TM1').value  = document.getElementById('NIK_TM1').value;
		document.getElementById('s_TM2').value  = document.getElementById('NIK_TM2').value;
		document.getElementById('s_TM3').value  = document.getElementById('NIK_TM3').value;
	}else {
		document.getElementById('sNO_POLISI').value = document.getElementById('No_PolisiLabel2').value;
		document.getElementById('sID_INTERNAL_ORDER1').value = document.getElementById('Id_Internal_OrderLabel2').value;
		document.getElementById('s_Supir').value = document.getElementById('Nama_Supir2').value;
		document.getElementById('s_TM1').value  = "-";
		document.getElementById('s_TM2').value  = "-";
		document.getElementById('s_TM3').value  = "-";
	}
	
	
	
	$("#add").click(function(){
		var countRow = $("#countRow").val();
		//alert(countRow);
		$.ajax({
			url: "getData.php",
			data: "countRow="+ countRow,
			method: 'POST',
			cache: false,
			success: function(msg){
				//jika data sukses diambil dari server kita tampilkan
				if(msg == '0'){
					alert('Tidak ada Hasil Panen di Afdeling Tersebut');
				}else{
					var split = msg.split(' . # . ');
					$('#countRow').val(split[1]-1);
					$("#hasil_panen").append(split[0]);
				}
			}
		});
	});
	
	$("#deliv_ticket").keyup(function(){
		var deliv_ticket = $('#deliv_ticket').val();
		var afdeling = $("#AFDlabel").val();
		var tgl_kirim = $("#datepicker").val();
		var BA = $("#ID_BAlabel").val();
		if(tgl_kirim==""){
			alert("Pilih Tanggal Kirim terlebih dahulu.");
			return false;
		}
		if(afdeling=="0"){
			alert("Pilih Afdeling terlebih dahulu.");
			return false;
		}
		if(deliv_ticket==""){
			$("#display").html("");
		}
		else{
			$.ajax({
				type: "POST",
				url: "getTicket.php",
				data: "deliv_ticket="+ deliv_ticket+"&afdeling="+BA+""+afdeling + "&var_tgl=" + tgl_kirim , //Edited by Ardo, 25-02-2016 : Delivery Ticket tetap per BA-AFD
				success: function(html){
					$("#display").html("");
					$("#display").html(html).show();
				}
			});
		}
	});
	
	
	$("#btn_simpan").click( function(){
		if (validateInput() == false){
		}else{
			document.getElementById('tmpNo_NAB').value = document.getElementById('No_NABLabel').value;
			document.getElementById('tglNAB').value = document.getElementById('datepicker').value;
			var dataString = $("#hasil_panen, #inputBCC, #editNAB").serialize();
			var status = 0;
			var tipeOrder = document.getElementById('ssTIPE_ORDER').value;
			if(tipeOrder == 'INTERNAL'){
				var noPolisi = document.getElementById('No_PolisiLabel1').value;
				var noIO = document.getElementById('Id_Internal_OrderLabel1').value;
				var namaSupir = document.getElementById('Nama_Supir1').value;
				var nikSupir = document.getElementById('NIK_Supir1').value;
				var tkgMuat = document.getElementById('Nama_TM1').value;
				if(noPolisi == '' || noIO == '' || noIO == '-' || namaSupir == '' || nikSupir == '' || tkgMuat == ''){
					if(noIO == '' || noIO == '-'){
						messageArea = document.getElementById("alert_txtNoPolisi");
						messageArea.innerHTML = '*No Polisi harus dipilih!';
						return false;
					}else{
						messageArea = document.getElementById("alert_txtNoPolisi");
						messageArea.innerHTML = '';
					}
					if(namaSupir == ''){
						messageArea = document.getElementById("alert_txtNamaSupir");
						messageArea.innerHTML = '*Supir harus dipilih!';
						return false;
					}else{
						messageArea = document.getElementById("alert_txtNamaSupir");
						messageArea.innerHTML = '';
					}
					if(tkgMuat == ''){
						messageArea = document.getElementById("alert_txtNamaTM1");
						messageArea.innerHTML = '*Tukang Muat harus dipilih!';
						return false;
					}else{
						messageArea = document.getElementById("alert_txtNamaTM1");
						messageArea.innerHTML = '';
					}
				}else {
				
					document.getElementById('sNO_POLISI').value = document.getElementById('No_PolisiLabel1').value;
					//document.getElementById('sID_INTERNAL_ORDER').value = document.getElementById('Id_Internal_OrderLabel1').value;
					document.getElementById('s_Supir').value = document.getElementById('NIK_Supir1').value;
					document.getElementById('s_TM1').value  = document.getElementById('NIK_TM1').value;
					document.getElementById('s_TM2').value  = document.getElementById('NIK_TM2').value;
					document.getElementById('s_TM3').value  = document.getElementById('NIK_TM3').value;
				
					messageArea = document.getElementById("alert_txtNoPolisi");
					messageArea.innerHTML = '';
					messageArea = document.getElementById("alert_txtNamaSupir");
					messageArea.innerHTML = '';
					messageArea = document.getElementById("alert_txtNamaTM1");
					messageArea.innerHTML = '';
					status = 1;
				}
			}else{
				document.getElementById('sNO_POLISI').value = document.getElementById('No_PolisiLabel2').value;
				//document.getElementById('sID_INTERNAL_ORDER').value = document.getElementById('Id_Internal_OrderLabel2').value;
				document.getElementById('s_Supir').value = document.getElementById('Nama_Supir2').value;
				document.getElementById('s_TM1').value  = document.getElementById('NIK_TM1').value;
				document.getElementById('s_TM2').value  = document.getElementById('NIK_TM2').value;
				document.getElementById('s_TM3').value  = document.getElementById('NIK_TM3').value;

				status = 1;
			}
			
			if(status == 1){
				$('#loading').html('<img src="../image/loading.gif">');
				$('#page_panen').hide();
				
				
				$.ajax({
					type     : "post",
					url      : "doSubmit.php",
					data     : dataString,
					cache    : false,
					success  : function(data) {
						//alert(data);return false;
						//var split = data.split(' . # . ');
						if(data == '1'){
							alert("Data Berhasil disimpan");
							location.href = 'KoreksiNABFil.php';
						}else if(data == '0'){
							alert("Data Tidak Berhasil tersimpan.");
						}else if (data == '3'){
							alert('Data Tidak Berhasil tersimpan. Tanggal NAB tidak boleh lebih kecil dari Tanggal BCC');
						}else{
							alert("Data Tidak ada yang diubah.");
						}
					},
					complete : function(){
						$('#loading').hide();
						$("#page_panen").show();
					}
				});
			}
		}
	});
	
	
});

function fill(Value){
	//Edited by Ardo, 03-11-2016 : Issue BCC yang dipilih bukan dobel jika no bcc identik
	var split = Value.split(' - ');
	$('#tmp_bcc').val(split[0]);
	$('#tmp_rencana').val(split[1]);
	$('#display').hide();
	var afdeling = $("#AFDlabel").val();
	var tgl_kirim = $("#datepicker").val();
    
    var countRow = $("#countRow").val();
	// alert(countRow);
    var tmp_bcc = $("#tmp_bcc").val();
   var tmp_rencana = $("#tmp_rencana").val();

	if(tgl_kirim != ""){
    $.ajax({
        url: "getData.php",
        data: "countRow="+ countRow+ "&tmp_bcc=" + tmp_bcc+ "&tmp_rencana=" + tmp_rencana,
        method: 'POST',
		cache: false,
        success: function(msg){
            //jika data sukses diambil dari server kita tampilkan
            //$("#hasil_panen").html(msg);
			//alert(msg);
			if(msg == '0'){
				alert('Tidak ada Hasil Panen di Afdeling Tersebut');
			}else{
				var split = msg.split(' . # . ');
				$('#countRow').val(split[1]-1);
				$("#hasil_panen").append(split[0]);
				var ttl_janjang = document.getElementById('total_janjang').value;
				var row = document.getElementById('countRow').value;
				var jjg = document.getElementById('t_jjg'+row).value;
				document.getElementById('total_janjang').value = parseInt(ttl_janjang) + parseInt(jjg);
			}
        }
    });
	}else{
		alert("Tanggal Kirim diisi terlebih dahulu.");
		document.getElementById("afdeling").value = 0;
		return false;
	}
	document.getElementById("deliv_ticket").value = "";
	
	
}

function cekUpper(){
	document.getElementById('No_NABLabel').value = document.getElementById('No_NABLabel').value.toUpperCase();
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
	var tipeOrder = document.getElementById('ssTIPE_ORDER').value;
	
	if(tipeOrder == 'INTERNAL'){
		document.getElementById('sNO_POLISI').value = document.getElementById('No_PolisiLabel1').value;
		document.getElementById('sID_INTERNAL_ORDER1').value = document.getElementById('Id_Internal_OrderLabel1').value;
		document.getElementById('s_Supir').value = document.getElementById('NIK_Supir1').value;
		document.getElementById('s_TM1').value  = document.getElementById('NIK_TM1').value;
		document.getElementById('s_TM2').value  = document.getElementById('NIK_TM2').value;
		document.getElementById('s_TM3').value  = document.getElementById('NIK_TM3').value;
	}else{
		document.getElementById('sNO_POLISI').value = document.getElementById('No_PolisiLabel2').value;
		document.getElementById('sID_INTERNAL_ORDER1').value = document.getElementById('Id_Internal_OrderLabel2').value;
		document.getElementById('s_Supir').value = document.getElementById('Nama_Supir2').value;
		document.getElementById('s_TM1').value  = '-';
		document.getElementById('s_TM2').value  = '-';
		document.getElementById('s_TM3').value  = '-';
}
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
	//alert (str5);
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
function isAngka(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
function deleteRow(row,arr){
    var i=row.parentNode.parentNode.rowIndex;
	var ttl_janjang = document.getElementById('total_janjang').value;
	var jjg = document.getElementById('t_jjg'+arr).value;
	document.getElementById('total_janjang').value = parseInt(ttl_janjang) - parseInt(jjg);
    document.getElementById('hasil_panen').deleteRow(i);
	var countRow = $("#countRow").val();
	//$("#countRow").val() = parseInt(countRow) - 1;
	
}

//LoV UTK DAFTAR DOKUMEN
function showListNoPolisi() {
	var business_area = document.getElementById('ID_BAlabel').value;
	//alert(business_area);
	if (business_area != "")
		sList = window.open("popupNoPolisi.php?BA="+business_area, "Daftar_No_Polisi", "width=600,height=300");
	document.getElementById('sNO_POLISI').value = document.getElementById('No_PolisiLabel1').value;
	//document.getElementById('sID_INTERNAL_ORDER').value = document.getElementById('Id_Internal_OrderLabel1').value;
	//alert(document.getElementById('Id_Internal_OrderLabel1').value);return false;
}

function showListSupir(kode) {
	var afdeling = document.getElementById('AFDlabel').value;
	var ba = document.getElementById('ID_BAlabel').value;
	//var baris = row;
	if (afdeling != "0")
		sList = window.open("popupSupir.php?afdeling="+ba+afdeling+"&baris="+kode+"", "Daftar_Dokumen", "width=800,height=500");
	else if (afdeling=="0")
		alert ("Pilih afdeling terlebih dahulu");
	else
		sList = window.open("popupSupir.php?afdeling="+ba+afdeling+"&baris="+kode+"", "Daftar_Dokumen", "width=800,height=500");
	//document.getElementById('s_Supir').value = document.getElementById('NIK_Supir1').value;
}

function showListTkgMuat1(kode) {
	var afdeling = document.getElementById('AFDlabel').value;
	var ba = document.getElementById('ID_BAlabel').value;
	
	//var baris = row;
	if (afdeling != "0")
		sList = window.open("popupTkgMuat1.php?afdeling="+ba+afdeling+"&baris="+kode+"", "Daftar_Dokumen", "width=800,height=500");
	else if (afdeling=="0")
		alert ("Pilih afdeling terlebih dahulu");
	else
		sList = window.open("popupTkgMuat1.php?afdeling="+ba+afdeling+"&baris="+kode+"", "Daftar_Dokumen", "width=800,height=500");
	//document.getElementById('s_TM1').value  = document.getElementById('NIK_TM1').value;
}

function showListTkgMuat2(kode) {
	var afdeling = document.getElementById('AFDlabel').value;
	var ba = document.getElementById('ID_BAlabel').value;
	var nik1 = document.getElementById('NIK_TM1').value;
	
	if(nik1 != ""){
		if (afdeling != "0")
			sList = window.open("popupTkgMuat2.php?afdeling="+ba+afdeling+"&baris="+kode+"", "Daftar_Dokumen", "width=800,height=500");
		else if (afdeling=="0")
			alert ("Pilih afdeling terlebih dahulu");
		else
			sList = window.open("popupTkgMuat2.php?afdeling="+ba+afdeling+"&baris="+kode+"", "Daftar_Dokumen", "width=800,height=500");
	}else{
		alert("Mohon isi data Tukang Muat 1 terlebih dahulu..");
	}
	//document.getElementById('s_TM2').value  = document.getElementById('NIK_TM2').value;
}

function showListTkgMuat3(kode) {
	var afdeling = document.getElementById('AFDlabel').value;
	var ba = document.getElementById('ID_BAlabel').value;
	var nik1 = document.getElementById('NIK_TM1').value;
	var nik2 = document.getElementById('NIK_TM2').value;
	if(nik1 != ""){
		if(nik2 != ""){
			if (afdeling != "0")
				sList = window.open("popupTkgMuat3.php?afdeling="+ba+afdeling+"&baris="+kode+"", "Daftar_Dokumen", "width=800,height=500");
			else if (afdeling=="0")
				alert ("Pilih afdeling terlebih dahulu");
			else
				sList = window.open("popupTkgMuat3.php?afdeling="+ba+afdeling+"&baris="+kode+"", "Daftar_Dokumen", "width=800,height=500");
		}else{
			alert("Mohon isi data Tukang Muat 2 terlebih dahulu..");
		}
	}else{
		alert("Mohon isi data Tukang Muat 1 terlebih dahulu..");
	}
	//document.getElementById('s_TM3').value  = document.getElementById('NIK_TM3').value;

}

function check() {
    var el = document.getElementById("sTIPE_ORDER");
    var str = el.options[el.selectedIndex].value;
	//alert(str);false;
	if(str == "INTERNAL") {
        show_internal();
		hide_eksternal();
    }else if(str == "EXTERNAL"){
		show_eksternal();
		hide_internal();
    }else{
		hide_internal();
		hide_eksternal();
	}
	document.getElementById('ssTIPE_ORDER').value = document.getElementById('sTIPE_ORDER').value;
}

function hide_internal(){
	document.getElementById('No_PolisiLabel1').style.visibility='hidden';
	document.getElementById('No_PolisiLabel1').style.display='none';
	document.getElementById('Id_Internal_OrderLabel1').style.visibility='hidden';
	document.getElementById('Id_Internal_OrderLabel1').style.display='none';
	document.getElementById('NIK_Supir1').style.visibility='hidden';
	document.getElementById('NIK_Supir1').style.display='none';
	document.getElementById('Nama_Supir1').style.visibility='hidden';
	document.getElementById('Nama_Supir1').style.display='none';
}

function hide_eksternal(){
	document.getElementById('No_PolisiLabel2').style.visibility='hidden';
	document.getElementById('No_PolisiLabel2').style.display='none';
	document.getElementById('Id_Internal_OrderLabel2').style.visibility='hidden';
	document.getElementById('Id_Internal_OrderLabel2').style.display='none';
	document.getElementById('Nama_Supir2').style.visibility='hidden';
	document.getElementById('Nama_Supir2').style.display='none';
}

function show_internal(){
	document.getElementById('No_PolisiLabel1').style.visibility='visible';
	document.getElementById('No_PolisiLabel1').style.display='block';
	document.getElementById('Id_Internal_OrderLabel1').style.visibility='visible';
	document.getElementById('Id_Internal_OrderLabel1').style.display='block';
	document.getElementById('NIK_Supir1').style.visibility='visible';
	document.getElementById('NIK_Supir1').style.display='block';
	document.getElementById('Nama_Supir1').style.visibility='visible';
	document.getElementById('Nama_Supir1').style.display='block';	
	
	
	document.getElementById('NIK_TM1').style.visibility='visible';
	document.getElementById('NIK_TM1').style.display='block';
	document.getElementById('NIK_TM2').style.visibility='visible';
	document.getElementById('NIK_TM2').style.display='block';
	document.getElementById('NIK_TM3').style.visibility='visible';
	document.getElementById('NIK_TM3').style.display='block';

	document.getElementById('Nama_TM1').style.visibility='visible';
	document.getElementById('Nama_TM1').style.display='block';
	document.getElementById('Nama_TM2').style.visibility='visible';
	document.getElementById('Nama_TM2').style.display='block';
	document.getElementById('Nama_TM3').style.visibility='visible';
	document.getElementById('Nama_TM3').style.display='block';
	
	document.getElementById('buttonTkgMuat1').style.visibility='visible';
	document.getElementById('buttonTkgMuat1').style.display='block';
	document.getElementById('buttonTkgMuat2').style.visibility='visible';
	document.getElementById('buttonTkgMuat2').style.display='block';
	document.getElementById('buttonTkgMuat3').style.visibility='visible';
	document.getElementById('buttonTkgMuat3').style.display='block';
	
	
}

function show_eksternal(){
	document.getElementById('No_PolisiLabel2').style.visibility='visible';
	document.getElementById('No_PolisiLabel2').style.display='block';
	document.getElementById('Id_Internal_OrderLabel2').style.visibility='visible';
	document.getElementById('Id_Internal_OrderLabel2').style.display='block';
	//document.getElementById('nikSupir2').style.visibility='visible';
	//document.getElementById('nikSupir2').style.display='block';
	document.getElementById('Nama_Supir2').style.visibility='visible';
	document.getElementById('Nama_Supir2').style.display='block';
	
	document.getElementById('NIK_TM1').style.visibility='hidden';
	document.getElementById('NIK_TM1').style.display='none';
	document.getElementById('NIK_TM2').style.visibility='hidden';
	document.getElementById('NIK_TM2').style.display='none';
	document.getElementById('NIK_TM3').style.visibility='hidden';
	document.getElementById('NIK_TM3').style.display='none';

	document.getElementById('Nama_TM1').style.visibility='hidden';
	document.getElementById('Nama_TM1').style.display='none';
	document.getElementById('Nama_TM2').style.visibility='hidden';
	document.getElementById('Nama_TM2').style.display='none';
	document.getElementById('Nama_TM3').style.visibility='hidden';
	document.getElementById('Nama_TM3').style.display='none';
	
	document.getElementById('buttonTkgMuat1').style.visibility='hidden';
	document.getElementById('buttonTkgMuat1').style.display='none';
	document.getElementById('buttonTkgMuat2').style.visibility='hidden';
	document.getElementById('buttonTkgMuat2').style.display='none';
	document.getElementById('buttonTkgMuat3').style.visibility='hidden';
	document.getElementById('buttonTkgMuat3').style.display='none';
}

function datediff(date1, date2){
	// Now we convert the array to a Date object, which has several helpful methods
	date1 = new Date(date1);
	date2 = new Date(date2);
	
	// We use the getTime() method and get the unixtime (in milliseconds, but we want seconds, therefore we divide it through 1000)
	date1_unixtime = parseInt(date1.getTime() / 1000);
	date2_unixtime = parseInt(date2.getTime() / 1000);
	
	// This is the calculated difference in seconds
	var timeDifference = date2_unixtime - date1_unixtime;
	
	// in Hours
	var timeDifferenceInHours = timeDifference / 60 / 60;
	
	// and finaly, in days :)
	var timeDifferenceInDays = timeDifferenceInHours  / 24;
	
	return timeDifferenceInDays;
}

function validateInput(elem){

	var returnValue;
	returnValue = true;	
	var jmlrow = document.getElementById('countRow').value;
	var nik_pemanen = $("#nikPemanen").val();
	var tgl = $("#datepicker").val();
	
	var v_blok = "";
	var v_tph = "";
	var v_ticket = "";
		
	if (document.getElementById("datepicker").value == ""){	
		messageArea = document.getElementById("alert_datepicker");
		messageArea.innerHTML = '* Tanggal Panen Harus Diisi!';
		returnValue = false;
	}else{
		messageArea = document.getElementById("alert_datepicker");
		messageArea.innerHTML = '';
		returnValue = true;
		var currentTime = new Date();
		var month = currentTime.getMonth() + 1;
		var day = currentTime.getDate();
		var year = currentTime.getFullYear();
		var today_date = year + "/" + month + "/" + day;
		var tgl_skrg = new Date(today_date);
		var tgl_skrg = new Date(today_date.replace(/-/gi,"/"));
		
		var tgl_kirim = new Date(datepicker.value);
		//alert(tgl_kirim);
		var tgl_kirim = new Date(datepicker.value.replace(/-/gi,"/"));
		//alert(tgl_kirim + " " + tgl_skrg);return false;
		if(tgl_kirim > tgl_skrg){
			alert("Tanggal NAB tidak boleh lebih besar dari hari ini");
			return false;
		}else{
			var date_Harv = $.datepicker.formatDate('yy/m/d', new Date(datepicker.value));
			var count_date = datediff(date_Harv, today_date);
			/*if(count_date > 7){
				alert("Tanggal Penginputan Hasil Panen tidak boleh lebih dari 7 hari");
				return false;
			}*/ //ditutup sementara untuk Kalbar
		}
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
	font-size: 13px;
	font-weight:normal;
}
.f_alertRed10px {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #FF0000;
}
</style>
<table width="1151" height="390" border="0" align="center">
  <!--<tr bgcolor="#C4D59E">-->
  <tr>
    <th height="197" scope="row" align="center"><table width="937" border="0" id="setbody2">
      <tr>
		<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>KOREKSI DATA NAB</strong></span></td>
      </tr>
		<form id="inputBCC" name="inputBCC" >
      <table border="0" id="setbody2">
          <tr> <?php //method="post" action="KoreksiNABSelect.php" ?>
            <td width="120">Company Name</td>
            <td width="7">:</td>
            <td><input name="Comp_NameLabel" type="text" id="Comp_NameLabel" value="<?=$sCOMP_NAME[0]?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
            <td>&nbsp;</td>
            <td height="9"> Afdeling Panen</td>
            <td height="9">:</td>
            <td style="font-size:16px">
				<?php
				//Edited by Ardo, 25-02-2016 : Afdeling dibuat LOV
				$afd_by_ba = "select * from T_AFDELING where ID_BA = '".$sID_BA[0]."'";
				$result_afd_by_ba = oci_parse($con, $afd_by_ba);
				oci_execute($result_afd_by_ba, OCI_DEFAULT);
				
				?>
				<select name="AFDlabel" id="AFDlabel" style="width:300px; height:25px; font-size:15px">
				<?php
				while(oci_fetch($result_afd_by_ba)){
					if(oci_result($result_afd_by_ba, "ID_AFD")==$sID_AFD[0]){
					?> <option value="<?php echo oci_result($result_afd_by_ba, "ID_AFD");?>" selected><?=oci_result($result_afd_by_ba, "ID_AFD")?></option><?php	
					} else {
					?> <option value="<?php echo oci_result($result_afd_by_ba, "ID_AFD");?>"><?=oci_result($result_afd_by_ba, "ID_AFD")?></option><?php	
					}
				}
				?>
				</select>
				<!-- <input name="AFDlabel" type="text" id="AFDlabel" value="<?=$sID_AFD[0]?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/> -->
			</td>
			</td>
          </tr>
          <tr>
            <td>Business Area</td>
            <td>:</td>
            <td><input name="ID_BAlabel" type="text" id="ID_BAlabel" value="<?=$sID_BA[0]?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/>
            </td>
            <td>&nbsp;</td>
            <td width="142">Tanggal NAB</td>
            <td width="8">:</td>
            <td width="303"><input type="text" name="datepicker" id="datepicker" onchange='push_data();' value="<?=$TGL_NAB?>" style="width:300px; height:25px; font-size:15px" readOnly="readOnly" ><span class="f_alertRed10px" id="alert_datepicker"></span></td>
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
			if($sTIPE_ORDER == "EKSTERNAL" OR $sTIPE_ORDER == "EXTERNAL"){
				echo "
				<td>New Type Order</td>
				<td>:</td>
				<td>
				<select name=\"sTIPE_ORDER\" id=\"sTIPE_ORDER\" style=\"font-size: 15px\" onchange=\"check();\">
				$selectedTypeOrder
				<option value=\"EXTERNAL\">EXTERNAL</option>
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
            <td><input name="No_PolisiLabel1" id="No_PolisiLabel1" value="<?php echo $sNO_POLISI; ?>" onChange='push_data();' style="width: 300px; height:25px; font-size:15px; visibility:hidden; display:none" onClick="javascript:showListNoPolisi();" readonly='readonly'/>
				<input name="No_PolisiLabel2" id="No_PolisiLabel2" maxlength='10' value="<?php echo $sNO_POLISI; ?>" onChange='push_data();' style="width: 300px; height:25px; font-size:15px; visibility:hidden; display:none" />
			<?php
			/*if($SessTIPE_ORDER == "EKSTERNAL" OR $SessTIPE_ORDER == "EXTERNAL"){
            	echo "<input name=\"No_PolisiLabel1\" type=\"text\" id=\"No_PolisiLabel1\" value=\"$sNO_POLISI\" style=\"width: 300px; height:25px; font-size:15px\" onchange=\"send(6)\" onblur=\"send(6)\" />"; 
			}
			else{
				echo "<input name=\"No_PolisiLabel2\" type=\"text\" id=\"No_PolisiLabel2\" value=\"$sNO_POLISI\" style=\"width: 300px; height:25px; font-size:15px\" onClick=\"javascript:showListNoPolisi();\" readonly='readonly'/>";
				//<input name="noPolisi1" type="text" id="noPolisi1" visible="false" value="" style="width:220px; height:20px; visibility:hidden; display:none" />
			
			}*/
			?>
			<span class="f_alertRed10px" id="alert_txtNoPolisi"></span>
			</td>
            <td height="3" align="center">&nbsp;</td>
			<td>No Internal Order</td>
				<td>:</td>
				<td><input name="Id_Internal_OrderLabel1" type="text" id="Id_Internal_OrderLabel1" 
				value="<?php echo $sID_INTERNAL_ORDER ?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px; visibility:hidden; display:none" 
				onmousedown="return false"/>
				<td style="display:none"><input name="Id_Internal_OrderLabel2" type="text" id="Id_Internal_OrderLabel2" 
				value="<?php echo $sID_INTERNAL_ORDER ?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px; visibility:hidden; display:none" 
				onmousedown="return false"/></td>
            <?php
            /*if($SessTIPE_ORDER == "INTERNAL"){
				echo "<td>No Internal Order</td>
				<td>:</td>
				<td><input name=\"Id_Internal_OrderLabel1\" type=\"text\" id=\"Id_Internal_OrderLabel1\" 
				value=\"$sID_INTERNAL_ORDER\" style=\"background-color:#CCC; width: 300px; height:25px; font-size:15px\" 
				onmousedown=\"return false\"/></td>";
            }
			else
			{
				echo "<td style=\"display:none\">No Internal Order</td>

				<td style=\"display:none\">:</td>
				<td style=\"display:none\"><input name=\"Id_Internal_OrderLabel2\" type=\"text\" id=\"Id_Internal_OrderLabel2\" 
				value=\"$sID_INTERNAL_ORDER\" style=\"background-color:#CCC; width: 300px; height:25px; font-size:15px\" 
				onmousedown=\"return false\"/></td>";
			}*/
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
            <td height="19" colspan="1" align="center" style="border-bottom:solid #556A29; border-top:solid #556A29"><strong>Data Supir</strong></td>
			<td height="19" colspan="6" align="left" style="border-bottom:solid #556A29; border-top:solid #556A29"><input type="button" name="buttonSupir" id="buttonSupir" value="Pinjam Karyawan" style="width:120px; height: 30px" onClick='javascript:showListSupir(3);'/></td>
          </tr>
		  <tr>
            <td>Nama Supir</td>
            <td>:</td>
            <td><span style="font-size:16px">
				<input name="Nama_Supir1" type="text" id="Nama_Supir1" value="<?=$Nama_Supir?>" style="width: 300px; height:25px; font-size:15px; visibility:hidden; display:none"  onClick='javascript:showListSupir(1);'/>            
				<input name="Nama_Supir2" type="text" id="Nama_Supir2" value="<?=$NIK_Supir?>" onChange='push_data();' style="width: 300px; height:25px; font-size:15px; visibility:hidden; display:none" /></span>
				<span class="f_alertRed10px" id="alert_txtNamaSupir"></span></td>
			<td>&nbsp;</td>
            <td>NIK Supir</td>
            <td>:</td>
            <td>
				<input name="NIK_Supir1" type="text" id="NIK_Supir1" value="<?=$NIK_Supir?>" onChange='push_data();' style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/>
			</td>
          </tr>
          
        <?php
		/*if($SessTIPE_ORDER == "INTERNAL"){
		  echo "          
          <tr>
            <td>Nama Supir</td>
            <td>:</td>
            <td><span style=\"font-size:16px\">
				<input name=\"Nama_Supir1\" type=\"text\" id=\"Nama_Supir1\" value=\"$Nama_Supir\" style=\"width: 300px; height:25px; font-size:15px\"  onClick='javascript:showListSupir(1);'/>            </span></td>
            <td>&nbsp;</td>
            <td>NIK Supir</td>
            <td>:</td>
            <td><input name=\"NIK_Supir1\" type=\"text\" id=\"NIK_Supir1\" value=\"$NIK_Supir\" style=\"background-color:#CCC; width: 300px; height:25px; font-size:15px\" onmousedown=\"return false\"/></td>
          </tr>";
		}
		  //untuk eksternal
		else{
		  echo "
		  <tr>
            <td valign=\"top\">Nama Supir</td>
            <td valign=\"top\">:</td>
            <td width=\"303\" valign=\"top\"><input name=\"Nama_Supir2\" type=\"text\" id=\"Nama_Supir2\" value=\"$NIK_Supir\" style=\"background-color:#CCC; background-color:#CCC; width: 300px; height:25px; font-size:15px\" onchange=\"send(7)\" onmousedown=\"return false\"/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input name=\"NIK_Supir2\" type=\"text\" id=\"NIK_Supir2\" value=\"$NIK_Supir\" style=\"background-color:#CCC; width: 300px; height:25px; font-size:15px; display:none\" onmousedown=\"return false\"/></td>
            <td><input name=\"Afd_Supir2\" type=\"text\" id=\"Afd_Supir2\" value=\"$Afd_Supir\" style=\"background-color:#CCC; width:50px; height:25px; font-size:15px; display:none\" onmousedown=\"return false\"/></td>
          </tr>
		  ";
		}*/
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
		  <tr>
            <td height="37" colspan="1" align="center" style="border-bottom:solid #556A29; border-top:solid #556A29"><strong>Data Tukang Muat 1</strong></td>
			<td height="37" colspan="6" align="left" style="border-bottom:solid #556A29; border-top:solid #556A29"><input type="button" name="buttonTkgMuat1" id="buttonTkgMuat1" value="Pinjam Karyawan" style="width:120px; height: 30px" onClick='javascript:showListTkgMuat1(3);'/></td>
		  </tr>
          <tr>
            <td width="150" valign="top">Nama Tukang Muat 1</td>
            <td valign="top">:</td>
            <td width="303" valign="top"><input name="Nama_TM1" type="text" id="Nama_TM1" value="<?=$Nama_TM1?>" onChange='push_data();' style="width: 300px; height:25px; font-size:15px" onClick='javascript:showListTkgMuat1(1);' readonly='readonly'/>
          </script><span class="f_alertRed10px" id="alert_txtNamaTM1"></span></td><td>&nbsp;</td>
            <td>NIK Tukang Muat 1</td>
            <td>:</td>
            <td><input name="NIK_TM1" type="text" id="NIK_TM1" value="<?=$NIK_TM1?>" onChange='push_data();' style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
          </tr>
          <tr>
            <td height="32" colspan="7" align="right" valign="top"><a href="ResTM1Session.php">
              <input type="button" name="DEFAULTTM1" id="DEFAULTTM1" value="DEFAULT" style="width:120px; height: 30px"/>
            </a></td>
          </tr>
          <tr>
            <td height="37" colspan="1" align="center" style="border-bottom:solid #556A29; border-top:solid #556A29"><strong>Data Tukang Muat 2</strong></td>
			<td height="37" colspan="6" align="left" style="border-bottom:solid #556A29; border-top:solid #556A29"><input type="button" name="buttonTkgMuat2" id="buttonTkgMuat2" value="Pinjam Karyawan" style="width:120px; height: 30px" onClick='javascript:showListTkgMuat2(3);'/></td>
		  </tr>
          <tr>
            <td valign="top">Nama Tukang Muat 2</td>
            <td valign="top">:</td>
            <td width="303" valign="top"><input name="Nama_TM2" type="text" id="Nama_TM2" onChange='push_data();' value="<?=$Nama_TM2?>" style="width: 300px; height:25px; font-size:15px" onClick='javascript:showListTkgMuat2(1);' readonly='readonly'/></td>
            <td>&nbsp;</td>
            <td>NIK Tukang Muat 2</td>
            <td>:</td>
            <td><input name="NIK_TM2" type="text" id="NIK_TM2" value="<?=$NIK_TM2?>" onChange='push_data();' style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
          </tr>
          <tr>
            <td height="33" colspan="7" align="right" valign="top"><a href="ResTM2Session.php">
              <input type="button" name="DEFAULTTM2" id="DEFAULTTM2" value="DEFAULT" style="width:120px; height: 30px"/>
            </a></td>
          </tr>
          <tr>
            <td height="38" colspan="1" align="center" style="border-bottom:solid #556A29; border-top:solid #556A29"><strong>Data Tukang Muat 3</strong></td>
          	<td height="37" colspan="6" align="left" style="border-bottom:solid #556A29; border-top:solid #556A29"><input type="button" name="buttonTkgMuat3" id="buttonTkgMuat3" value="Pinjam Karyawan" style="width:120px; height: 30px" onClick='javascript:showListTkgMuat3(3);'/></td>
		  </tr>
          <tr>
            <td valign="top">Nama Tukang Muat 3</td>
            <td valign="top">:</td>
            <td width="303" valign="top"><input name="Nama_TM3" type="text" id="Nama_TM3" value="<?=$Nama_TM3?>" onChange='push_data();' style="width: 300px; height:25px; font-size:15px"  onClick='javascript:showListTkgMuat3(1);' readonly='readonly'/></td>
            <td>&nbsp;</td>
            <td>NIK Tukang Muat 3</td>
            <td>:</td>
            <td><input name="NIK_TM3" type="text" id="NIK_TM3" value="<?=$NIK_TM3?>" onChange='push_data();' style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
          </tr>
          <tr>
            <td height="32" colspan="7" align="right" valign="top"><a href="ResTM3Session.php">
              <input type="button" name="DEFAULTTM3" id="DEFAULTTM3" value="DEFAULT" style="width:120px; height: 30px"/>
            </a></td>
          </tr>
		  
		  
          <?php
        /*if($SessTIPE_ORDER == "INTERNAL"){
		?>
          <tr>
            <td height="37" colspan="1" align="center" style="border-bottom:solid #556A29; border-top:solid #556A29"><strong>Data Tukang Muat 1</strong></td>
			<td height="37" colspan="6" align="left" style="border-bottom:solid #556A29; border-top:solid #556A29"><input type="button" name="buttonTkgMuat1" id="buttonTkgMuat1" value="Pinjam Karyawan" style="width:120px; height: 30px" onClick='javascript:showListTkgMuat1(3);'/></td>
		  </tr>
          <tr>
            <td width="150" valign="top">Nama Tukang Muat 1</td>
            <td valign="top">:</td>
            <td width="303" valign="top"><input name="Nama_TM1" type="text" id="Nama_TM1" value="<?=$Nama_TM1?>" style="width: 300px; height:25px; font-size:15px" onClick='javascript:showListTkgMuat1(1);' readonly='readonly'/>
              <script type="text/javascript">
					/*$(document).ready(function() {
						//var bacode = $("#BATM1").val();
						var bacode =  $('#BATM1').find(":selected").text();
						var q =  $('#Nama_TM1').find(":selected").text();
						$("#Nama_TM1").autocomplete("userTM1.php?bacode="+bacode+"&q="+q, {
							selectFirst: true
						});
					});
          </script></td><td>&nbsp;</td>
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
            <td height="37" colspan="1" align="center" style="border-bottom:solid #556A29; border-top:solid #556A29"><strong>Data Tukang Muat 2</strong></td>
			<td height="37" colspan="6" align="left" style="border-bottom:solid #556A29; border-top:solid #556A29"><input type="button" name="buttonTkgMuat2" id="buttonTkgMuat2" value="Pinjam Karyawan" style="width:120px; height: 30px" onClick='javascript:showListTkgMuat2(3);'/></td>
		  </tr>
          <tr>
            <td valign="top">Nama Tukang Muat 2</td>
            <td valign="top">:</td>
            <td width="303" valign="top"><input name="Nama_TM2" type="text" id="Nama_TM2" value="<?=$Nama_TM2?>" style="width: 300px; height:25px; font-size:15px" onClick='javascript:showListTkgMuat2(1);' readonly='readonly'/></td>
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
            <td height="38" colspan="1" align="center" style="border-bottom:solid #556A29; border-top:solid #556A29"><strong>Data Tukang Muat 3</strong></td>
          	<td height="37" colspan="6" align="left" style="border-bottom:solid #556A29; border-top:solid #556A29"><input type="button" name="buttonTkgMuat3" id="buttonTkgMuat3" value="Pinjam Karyawan" style="width:120px; height: 30px" onClick='javascript:showListTkgMuat3(3);'/></td>
		  </tr>
          <tr>
            <td valign="top">Nama Tukang Muat 3</td>
            <td valign="top">:</td>
            <td width="303" valign="top"><input name="Nama_TM3" type="text" id="Nama_TM3" value="<?=$Nama_TM3?>" style="width: 300px; height:25px; font-size:15px"  onClick='javascript:showListTkgMuat3(1);' readonly='readonly'/></td>
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
		}*/
		  ?>
		  <tr> <td colspan="7" align="center" style="border-top:solid #556A29"> </td></tr>
		  <tr>
			<td width="70" height="29" valign="top">Delivery Ticket</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" align="left" valign="top" colspan='6'>
				<input name="deliv_ticket" type="text" id="deliv_ticket" maxlength="5" value="" onkeypress="return isAngka(event)" style="width:220px; height:20px"/>
				<!--input type="button" name="add" id="add" value="Add"-->
				<!--input type="button" name="submit" id="submit" value="Search"-->
				<div id="display"></div>
			</td>
		</tr>
		<tr>
			<!-- Edited by Ardo, 03-11-2016 : Issue BCC yang dipilih bukan dobel jika no bcc identik -->
			<td><input name="tmp_bcc" type="text" id="tmp_bcc" value="" style="width:220px; height:20px; display:none;"/></td>
			<td><input name="tmp_rencana" type="text" id="tmp_rencana" value="" style="width:220px; height:20px; display:none;"/></td>
		</tr>
          </table>
		
    </form>
    
    <table width="1031" border="0" id="setbody2">
    <tr>
          <td align="center"><form id="editNAB" name="editNAB"> <?php  //method="post" action="doSubmit.php" ?>
          <input name="s_Supir" type="text" id="s_Supir" value="" style="display:none;" onmousedown="return false"/>
            <input name="s_TM1" type="text" id="s_TM1" value=""  style="display:none;" onmousedown="return false"/>
            <input name="s_TM2" type="text" id="s_TM2" value=""  style="display:none;" onmousedown="return false"/>
            <input name="s_TM3" type="text" id="s_TM3" value=""  style="display:none;" onmousedown="return false"/>
            <input name="ssTIPE_ORDER" type="text" id="ssTIPE_ORDER" value=""  style="display:none;" onmousedown="return false"/>
            <input name="sID_INTERNAL_ORDER1" type="text" id="sID_INTERNAL_ORDER1"  style="display:none;" onmousedown="return false"/>
            <input name="s_BA" type="text" id="s_BA" value="<?=$sID_BA[0]?>"  style="display:none;" onmousedown="return false"/>
            <input name="sNO_POLISI" type="text" id="sNO_POLISI" value=""  style="display:none;" onmousedown="return false"/>				
            <input name="editNO_NAB" type="text" id="editNO_NAB" value="<?=$_SESSION["editNO_NAB"]?>" style="display:none;" onmousedown="return false"/>
            <input name="tmpNo_NAB" type="text" id="tmpNo_NAB" value="" style="display:none;" onmousedown="return false"/>
            <input name="tglNAB" type="text" id="tglNAB" value="" style="display:none;" onmousedown="return false"/>
			<input name="s_old_TIPE_ORDER" type="text" id="s_old_TIPE_ORDER" value=<?=$sTIPE_ORDER?> style="display:none;" onmousedown="return false"/>
            <!--input type="button" name="btn_simpan" id="btn_simpan" value="SIMPAN"  style="width:120px; height: 30px"/-->
			<div id='loading'></div>
			<div style="overflow:scroll; width:1200px" id='page_panen'>
			<frameset>
			<legend>BCC dikirim</legend>
				<table id="hasil_panen" border='1' cellpadding='1' cellspacing='1' style='table-layout:fixed; overflow-x:scroll'>
					<input name='countRow' type='hidden' id='countRow' value='<?php echo $roweffec_NAB; ?>' style='width:70px; height:20px' readonly='readonly'/>
					<input name='startInputBCC' type='hidden' id='startInputBCC' value='<?php echo $roweffec_NAB; ?>' style='width:70px; height:20px' readonly='readonly'/>
		
				</table>
				<table width='1158px' border='0'>
				<tr>
					<td align="right" width='495px'>Total Janjang Kirim</td>
					<td align="right" width='10px'>:</td>
					<td align="left" width='10px'><input id='total_janjang' name='total_janjang' style='width:50px; height:20px' type='text' value='<?php echo $ttl_jjg; ?>' readonly='readonly'/></td>
					<td width='82px'>&nbsp;</td>
					<td width='75px'>&nbsp;</td>
				</tr><tr align="center">
					<td colspan='5'><input id='btn_simpan' name='btn_simpan' type='button' value="Simpan"/></td>
				</tr>
			</table>
			</frameset>
	</div>
            </form></td> <?php //onclick="passValue();" ?>
    </tr>
	<tr>
            <td colspan="7" align="center"><a href="ResALLSession.php">
              <input type="button" name="DEFAULTALL" id="DEFAULTALL" value="DEFAULT ALL" style="width:120px; height: 30px"/>
            </a></td>
          </tr>
    </table>
    <table width="1031" border="0" id="setbody2">
    <tr>
          <td align="center"><span class="style1">Pastikan koreksi data Anda telah mendapatkan persetujuan dari EM atau KABUN !!</span></td>
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