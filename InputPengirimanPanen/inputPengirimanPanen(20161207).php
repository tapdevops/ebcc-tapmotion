<?php
session_start();
include("../include/Header.php");
//include("../InputPengirimanPanen/getData.php");

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
		header("location:login.php");
	}
	else{
		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		
		$con = connect();

		if(isset($_POST["editNO_BCC"])){
		$_SESSION["editNO_BCC"] = $_POST["editNO_BCC"];
		}
		$NO_BCC = 0;
		if(isset($_SESSION["editNO_BCC"])){
		$NO_BCC = $_SESSION["editNO_BCC"];
		}

		$sql_t_BCC = "
		select thrp.tanggal_rencana tanggal, thrp.id_rencana id_rencana,
		tba.id_cc AS CC,
        tba.id_ba AS BA,
       ta.id_afd AS AFD,
       thp.no_bcc,
       thrp.nik_pemanen,
       f_get_empname (thrp.nik_pemanen) nama_pemanen,
       thrp.nik_mandor,
       f_get_empname (thrp.nik_mandor) nama_mandor,
      tkp.nama_kualitas, thpk.ID_BCC_KUALITAS as ID_BCC_KUALITAS, thpk.qty
  from t_header_rencana_panen thrp
       inner join t_detail_rencana_panen tdrp
          on thrp.id_rencana = tdrp.id_rencana
       inner join t_hasil_panen thp
		  on tdrp.id_rencana = thp.id_rencana
		  and tdrp.no_rekap_bcc = thp.no_rekap_bcc
       inner join t_blok tb
          on tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
       inner join t_afdeling ta
          on tb.id_ba_afd = ta.id_ba_afd
       inner join t_bussinessarea tba
          on ta.id_ba = tba.id_ba
     inner join t_hasilpanen_kualtas thpk
          on thp.no_bcc = thpk.id_bcc
      inner join  t_kualitas_panen tkp
        on thpk.id_kualitas=tkp.id_kualitas
where     thp.no_bcc = '$NO_BCC'";
		//echo $sql_t_BCC; die;
		$result_t_BCC = oci_parse($con, $sql_t_BCC);
		oci_execute($result_t_BCC, OCI_DEFAULT);
		while(oci_fetch($result_t_BCC)){
			$ID_RENCANA 		= oci_result($result_t_BCC, "ID_RENCANA");
			$TANGGAL_RENCANA 		= oci_result($result_t_BCC, "TANGGAL");
			$aNO_BCC 				= oci_result($result_t_BCC, "NO_BCC");
			$CC				= oci_result($result_t_BCC, "CC");
			$BA 				= oci_result($result_t_BCC, "BA");
			$AFD 				= oci_result($result_t_BCC, "AFD");
			$NAMA_PEMANEN 		= oci_result($result_t_BCC, "NAMA_PEMANEN");
			$NAMA_MANDOR 			= oci_result($result_t_BCC, "NAMA_MANDOR");
			$NIK_PEMANEN 		= oci_result($result_t_BCC, "NIK_PEMANEN");
			$NIK_MANDOR 			= oci_result($result_t_BCC, "NIK_MANDOR");
		}
		
		$sql_t_BCC_table = "SELECT H.ID_BCC_KUALITAS ,K.ID_KUALITAS, K.NAMA_KUALITAS, H.QTY  FROM  t_kualitas_panen K,
        t_hasilpanen_kualtas H  
        WHERE  H.ID_BCC(+)='$NO_BCC'
            AND K.ID_KUALITAS = H.ID_KUALITAS(+)
            ORDER BY Group_kualitas,ID_KUALITAS ASC";
		$result_t_BCC_table = oci_parse($con, $sql_t_BCC_table);
		oci_execute($result_t_BCC_table, OCI_DEFAULT);
		while(oci_fetch($result_t_BCC_table)){
			$NAMA_KUALITAS[] 	= oci_result($result_t_BCC_table, "NAMA_KUALITAS");
			$QTY[] 				= oci_result($result_t_BCC_table, "QTY");
			$ID_BCC_KUALITAS[] 	= oci_result($result_t_BCC_table, "ID_BCC_KUALITAS");
			$ID_Kualitas[]		= oci_result($result_t_BCC_table, "ID_KUALITAS");
		}
		$roweffec_BCC = oci_num_rows($result_t_BCC_table);
		
		if(isset($_POST['BA'])){
			$_SESSION['BA'] = $_POST['BA'];
		}
		
		if(isset($_SESSION['BA'])){
			$ses_BA = $_SESSION['BA'];
			if($ses_BA  == ""){
				$sql_BA = "select * from t_BussinessArea tba
				inner join t_CompanyCode tcc on tba.id_cc = tcc.id_cc
				inner join t_alternate_ba_group tabg on tba.id_ba = tabg.id_ba
				where tabg.id_group_ba in (select id_group_ba from t_bussinessarea where id_ba = $subID_BA_Afd)
				 order by tba.id_ba";
				$sql_t_Emp_All  = "SELECT * from t_employee WHERE JOB_CODE = 'PEMANEN'";
				$optionBA = "";
			}
			else{
				$sql_BA = "select * from t_BussinessArea tba
				inner join t_CompanyCode tcc on tba.id_cc = tcc.id_cc 
				inner join t_alternate_ba_group tabg on tba.id_ba = tabg.id_ba
				where tabg.id_group_ba in (select id_group_ba from t_bussinessarea where id_ba = $subID_BA_Afd)
				AND tba.ID_BA != '$ses_BA' order by tba.id_ba";
				
				$optionBA = "<option value=\"$ses_BA\" selected=\"selected\">$ses_BA</option>";
				
				$sql_t_Emp_All  = "select * from t_employee te
				inner join t_afdeling ta on te.id_ba_afd = ta.id_ba_afd
				where ta.id_ba = '$ses_BA' and te.job_code = 'PEMANEN'";	
			}
		}
		else{
			$sql_BA = "select * from t_BussinessArea tba
				inner join t_CompanyCode tcc on tba.id_cc = tcc.id_cc 
				inner join t_alternate_ba_group tabg on tba.id_ba = tabg.id_ba
				where tabg.id_group_ba in (select id_group_ba from t_bussinessarea where id_ba = $subID_BA_Afd)
				order by tba.id_ba";
			$sql_t_Emp_All  = "SELECT * from t_employee WHERE JOB_CODE = 'PEMANEN'";
			$optionBA = "";
		}		
		
		//echo $sql_BA;
		$result_t_Emp_All = oci_parse($con, $sql_t_Emp_All);
		oci_execute($result_t_Emp_All, OCI_DEFAULT);
		
		while (oci_fetch($result_t_Emp_All)) {	
			$NIK_Pemanen_All[] = oci_result($result_t_Emp_All, "NIK");
			$Nama_Pemanen_All[] = oci_result($result_t_Emp_All, "EMP_NAME");
			$ID_BA_AFD_Pemanen_All[] = oci_result($result_t_Emp_All, "ID_BA_AFD");
		}
		$roweffec_Emp_All = oci_num_rows($result_t_Emp_All);

		$result_BA = oci_parse($con, $sql_BA);
		oci_execute($result_BA, OCI_DEFAULT);
		while(oci_fetch($result_BA)){
			$ID_BA[]		= oci_result($result_BA, "ID_BA");	
		}
		$roweffec_BA = oci_num_rows($result_BA);
	}
	
?>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">

$(document).ready(function(){
  //apabila terjadi event onchange terhadap object			
  $("#add").click(function(){
   var afdeling = $("#afdeling").val();
   var tgl_kirim = $("#datepicker").val();
   var deliv_ticket = $("#deliv_ticket").val();
   var countRow = $("#countRow").val();
  // alert(countRow);
   var tmp_tgl = $("#tmp_tgl").val();
   var tmp_blok = $("#tmp_blok").val();
   var tmp_nik = $("#tmp_nik").val();
   var tmp_tph = $("#tmp_tph").val();
   var tmp_bcc = $("#tmp_bcc").val();

	if(tgl_kirim != ""){
    $.ajax({
        url: "getData.php",
        data: "countRow="+ countRow+"&deliv_ticket="+ deliv_ticket+"&afdeling="+afdeling + "&var_tgl=" + tgl_kirim
				 + "&tmp_tgl=" + tmp_tgl + "&tmp_blok=" + tmp_blok + "&tmp_nik=" + tmp_nik + "&tmp_tph=" + tmp_tph+ "&tmp_bcc=" + tmp_bcc,
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
			}
        }
    });
	}else{
		alert("Tanggal Kirim diisi terlebih dahulu.");
		document.getElementById("afdeling").value = 0;
		return false;
	}
  });
  
  $("#btn_simpan").click( function(){
		if (validateInput() == false){
		} else {
			$('#loading').html('<img src="../image/loading.gif">');
			$('#page_panen').hide();
			
			$.ajax({
				type     : "post",
				url      : "inputPengirimanPanenProses.php",
				data     : $("#inputpengirimanpanen").serialize(),
				cache    : false,
				success  : function(data) {
					var split = data.split(' . # . ');
					if(split[1] == '1'){
						alert("Data Berhasil disimpan dengan kode NAB " + split[0] + ".");
						document.getElementById("inputpengirimanpanen").reset();
						/*document.getElementById('datepicker').focus();
						document.getElementById('datepicker').value = "";
						document.getElementById('afdeling').value = 0;
						document.getElementById('TypeOrder').value = 0;
						document.getElementById('nikTkgMuat1').value = "";
						document.getElementById('namaTkgMuat1').value = "";
						document.getElementById('nikTkgMuat2').value = "";
						document.getElementById('namaTkgMuat2').value = "";
						document.getElementById('nikTkgMuat3').value = "";
						document.getElementById('namaTkgMuat3').value = "";						
						document.getElementById('deliv_ticket').value = "";
						document.getElementById('total_janjang').value = "0";
						document.getElementById('noPolisi1').value="";
						document.getElementById('noIntOrder1').value="";
						document.getElementById('nikSupir1').value="";
						document.getElementById('namaSupir1').value="";
						document.getElementById('noPolisi2').value="";
						document.getElementById('noIntOrder2').value="";
						document.getElementById('namaSupir2').value="";
						hide_internal();
						hide_eksternal();*/
						
						$("#hasil_panen").find("tr:gt(0)").remove();
					}else{
						alert("Data Tidak Berhasil tersimpan.");
					}
				},
				complete : function(){
					$('#loading').hide();
					$("#page_panen").show();
				}
			});
		
		}
    });
});

function deleteRow(row,arr){
    var i=row.parentNode.parentNode.rowIndex;
	var ttl_janjang = document.getElementById('total_janjang').value;
	var jjg = document.getElementById('t_jjg'+arr).value;
	document.getElementById('total_janjang').value = parseInt(ttl_janjang) - parseInt(jjg);
    document.getElementById('hasil_panen').deleteRow(i);
}

function isAngka(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

 function finishAjax(response){
  alert(response);
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

function cekUpper(){
	document.getElementById('NoNAB').value = document.getElementById('NoNAB').value.toUpperCase();
}

function validateInput(){

	var returnValue;
	returnValue = true;	
	
	if (document.getElementById("datepicker").value == ""){	
		messageArea = document.getElementById("alert_datepicker");
		messageArea.innerHTML = '* Tanggal Kirim Harus Diisi!';
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
		var tgl_kirim = new Date(datepicker.value.replace(/-/gi,"/"));
		
		if(tgl_kirim > tgl_skrg){
			alert("Tanggal Pengiriman Hasil Panen tidak boleh lebih besar dari hari ini");
			return false;
		}else{
			var currentTime = new Date();
			var month = currentTime.getMonth() + 1;
			var day = currentTime.getDate();
			var year = currentTime.getFullYear();
			var today_date = year + "/" + month + "/" + day;
			var tgl_skrg = new Date(today_date);
		
			var date_Harv = $.datepicker.formatDate('yy/m/d', new Date(datepicker.value));
			var count_date = datediff(date_Harv, today_date);
			/*if(count_date > 7){
				alert("Tanggal Pengiriman Hasil Panen tidak boleh lebih dari 7 hari");
				return false;
			}*/ //ditutup sementara untuk Kalbar
		}
	}

	
	if (document.getElementById("afdeling").value == "0"){	
		messageArea = document.getElementById("alert_afd");
		messageArea.innerHTML = '*Afdeling harus dipilih!';
		returnValue = false;
	}else{
		messageArea = document.getElementById("alert_afd");
		messageArea.innerHTML = '';
		returnValue = true;
	}
	
	if (document.getElementById("NoNAB").value == ""){	
		messageArea = document.getElementById("alert_NAB");
		messageArea.innerHTML = '*NAB harus diisi!';
		returnValue = false;
	}else if(document.getElementById("NoNAB").value.length != '7'){
			alert("No NAB harus tepat 7 digit!");
			return false;
	}else{
		messageArea = document.getElementById("alert_NAB");
		messageArea.innerHTML = '';
		returnValue = true;
	}
	
	if (document.getElementById("TypeOrder").value == "0"){	
		messageArea = document.getElementById("alert_typeorder");
		messageArea.innerHTML = '*Type Order harus dipilih!';
		returnValue = false;
	}else{
		messageArea = document.getElementById("alert_typeorder");
		messageArea.innerHTML = '';
		returnValue = true;
		
		if (document.getElementById("TypeOrder").value == "1"){	
			if (document.getElementById("noPolisi1").value == ""){
				messageArea = document.getElementById("alert_txtPolisi");
				messageArea.innerHTML = '*No Polisi harus diisi!';
				returnValue = false;
			}else{
				messageArea = document.getElementById("alert_txtPolisi");
				messageArea.innerHTML = '';
				returnValue = true;
			}
			
			if (document.getElementById("noIntOrder1").value == ""){
				messageArea = document.getElementById("alert_txtOrder");
				messageArea.innerHTML = '*No Internal Order harus diisi!';
				returnValue = false;
			}else{
				messageArea = document.getElementById("alert_txtOrder");
				messageArea.innerHTML = '';
				returnValue = true;
			}
			if (document.getElementById("nikSupir1").value == ""){
				messageArea = document.getElementById("alert_txtSupir1");
				messageArea.innerHTML = '*NIK Supir harus diisi!';
				messageArea = document.getElementById("alert_txtSupir2");
				messageArea.innerHTML = '';
				returnValue = false;
			}else{
				messageArea = document.getElementById("alert_txtSupir1");
				messageArea.innerHTML = '';
				returnValue = true;
			}
		}else{
			if (document.getElementById("noPolisi2").value == ""){
				messageArea = document.getElementById("alert_txtPolisi");
				messageArea.innerHTML = '*No Polisi harus diisi!';
				messageArea = document.getElementById("alert_txtOrder");
				messageArea.innerHTML = '';
				returnValue = false;
			}else{
				messageArea = document.getElementById("alert_txtPolisi");
				messageArea.innerHTML = '';
				returnValue = true;
				messageArea = document.getElementById("alert_txtOrder");
				messageArea.innerHTML = '';
				returnValue = true;
			}
			if (document.getElementById("namaSupir2").value == ""){
				messageArea = document.getElementById("alert_txtSupir2");
				messageArea.innerHTML = '*Nama Supir harus diisi!';
				messageArea = document.getElementById("alert_txtSupir1");
				messageArea.innerHTML = '';
				returnValue = false;
			}else{
				messageArea = document.getElementById("alert_txtSupir2");
				messageArea.innerHTML = '';
				returnValue = true;
			}
		}
	}
	
	
	if (document.getElementById("TypeOrder").value == "1"){	
		if (document.getElementById("nikTkgMuat1").value == "" && document.getElementById("nikTkgMuat2").value == "" && document.getElementById("nikTkgMuat3").value == ""){
			messageArea = document.getElementById("alert_txtTkgMuat");
			messageArea.innerHTML = '*Data Tukang Muat harus diisi salah satu!';
			returnValue = false;
		}else{
			messageArea = document.getElementById("alert_txtTkgMuat");
			messageArea.innerHTML = '';
			returnValue = true;
		}
	}else{
		messageArea = document.getElementById("alert_txtTkgMuat");
		messageArea.innerHTML = '';
		returnValue = true;	
	}	
	
	if (returnValue == false){	
		alert("Mohon isi terlebih dahulu data yang belum lengkap");
		return false;
	}
	
	return returnValue;
}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 48 || (charCode > 57 && charCode < 65) || (charCode > 90 && charCode < 97) || (charCode > 122 && charCode < 127))) {
        return false;
    }
    return true;
}

function cekData(){

	var v_afd = document.getElementById('afdeling').value;
	var v_nab = document.getElementById('NoNAB').value;
	var v_ba = document.getElementById('ID_BA2').value;
	
	if (document.getElementById("NoNAB").value == ""){	
		alert("NAB harus diisi!");
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
		if(xhr.responseText == "kosong"){	
		}else{
			alert("No NAB sudah pernah digunakan. mohon ubah No NAB.");
			document.getElementById('NoNAB').value = "";
			document.getElementById('NoNAB').focus();
			//alert(xhr.responseText);
		}
	  //document.getElementById("suggestion").innerHTML = xhr.responseText;
	  } else {
		alert('There was a problem with the request.');
	  }
	 }
	}
}

function check() {
    var el = document.getElementById("TypeOrder");
    var str = el.options[el.selectedIndex].value;
	if(str == "1") {
        show_internal();
		hide_eksternal();
    }else if(str == "2"){
		show_eksternal();
		hide_internal();
    }else{
		hide_internal();
		hide_eksternal();
	}
}

function hide_internal(){
	document.getElementById('noPolisi1').style.visibility='hidden';
	document.getElementById('noPolisi1').style.display='none';
	document.getElementById('noIntOrder1').style.visibility='hidden';
	document.getElementById('noIntOrder1').style.display='none';
	document.getElementById('nikSupir1').style.visibility='hidden';
	document.getElementById('nikSupir1').style.display='none';
	document.getElementById('namaSupir1').style.visibility='hidden';
	document.getElementById('namaSupir1').style.display='none';
}

function hide_eksternal(){
	document.getElementById('noPolisi2').style.visibility='hidden';
	document.getElementById('noPolisi2').style.display='none';
	document.getElementById('noIntOrder2').style.visibility='hidden';
	document.getElementById('noIntOrder2').style.display='none';
	//document.getElementById('nikSupir2').style.visibility='hidden';
	//document.getElementById('nikSupir2').style.display='none';
	document.getElementById('namaSupir2').style.visibility='hidden';
	document.getElementById('namaSupir2').style.display='none';
}

function show_internal(){
	document.getElementById('noPolisi1').style.visibility='visible';
	document.getElementById('noPolisi1').style.display='block';
	document.getElementById('noIntOrder1').style.visibility='visible';
	document.getElementById('noIntOrder1').style.display='block';
	document.getElementById('nikSupir1').style.visibility='visible';
	document.getElementById('nikSupir1').style.display='block';
	document.getElementById('namaSupir1').style.visibility='visible';
	document.getElementById('namaSupir1').style.display='block';	
	
	
	document.getElementById('nikTkgMuat1').style.visibility='visible';
	document.getElementById('nikTkgMuat1').style.display='block';
	document.getElementById('nikTkgMuat2').style.visibility='visible';
	document.getElementById('nikTkgMuat2').style.display='block';
	document.getElementById('nikTkgMuat3').style.visibility='visible';
	document.getElementById('nikTkgMuat3').style.display='block';

	document.getElementById('namaTkgMuat1').style.visibility='visible';
	document.getElementById('namaTkgMuat1').style.display='block';
	document.getElementById('namaTkgMuat2').style.visibility='visible';
	document.getElementById('namaTkgMuat2').style.display='block';
	document.getElementById('namaTkgMuat3').style.visibility='visible';
	document.getElementById('namaTkgMuat3').style.display='block';
	
	document.getElementById('buttonTkgMuat1').style.visibility='visible';
	document.getElementById('buttonTkgMuat1').style.display='block';
	document.getElementById('buttonTkgMuat2').style.visibility='visible';
	document.getElementById('buttonTkgMuat2').style.display='block';
	document.getElementById('buttonTkgMuat3').style.visibility='visible';
	document.getElementById('buttonTkgMuat3').style.display='block';
	
	
}

function show_eksternal(){
	document.getElementById('noPolisi2').style.visibility='visible';
	document.getElementById('noPolisi2').style.display='block';
	document.getElementById('noIntOrder2').style.visibility='visible';
	document.getElementById('noIntOrder2').style.display='block';
	//document.getElementById('nikSupir2').style.visibility='visible';
	//document.getElementById('nikSupir2').style.display='block';
	document.getElementById('namaSupir2').style.visibility='visible';
	document.getElementById('namaSupir2').style.display='block';
	
	document.getElementById('nikTkgMuat1').style.visibility='hidden';
	document.getElementById('nikTkgMuat1').style.display='none';
	document.getElementById('nikTkgMuat2').style.visibility='hidden';
	document.getElementById('nikTkgMuat2').style.display='none';
	document.getElementById('nikTkgMuat3').style.visibility='hidden';
	document.getElementById('nikTkgMuat3').style.display='none';

	document.getElementById('namaTkgMuat1').style.visibility='hidden';
	document.getElementById('namaTkgMuat1').style.display='none';
	document.getElementById('namaTkgMuat2').style.visibility='hidden';
	document.getElementById('namaTkgMuat2').style.display='none';
	document.getElementById('namaTkgMuat3').style.visibility='hidden';
	document.getElementById('namaTkgMuat3').style.display='none';
	
	document.getElementById('buttonTkgMuat1').style.visibility='hidden';
	document.getElementById('buttonTkgMuat1').style.display='none';
	document.getElementById('buttonTkgMuat2').style.visibility='hidden';
	document.getElementById('buttonTkgMuat2').style.display='none';
	document.getElementById('buttonTkgMuat3').style.visibility='hidden';
	document.getElementById('buttonTkgMuat3').style.display='none';
}

</script>

<script type="text/javascript" src="../js/jsformatnumber.js"></script>
<script language="JavaScript" type="text/JavaScript">
/*function klik(ff){ 
showList(ff); 
}*/

function selectAllFiles(c,jml) {
	for (i = 1; i <= jml; i++) {
		document.getElementById('cbox' + i).checked = c;
	}
}

function changeformat(obj) {
  obj.value = formatCurrency(obj.value) ;
}

function onChangeValue(iter, obj){
	var jmlh;
	obj.value = formatCurrency(obj.value);
	document.getElementById('mentah'+iter).value = formatCurrency(document.getElementById('mentah'+iter).value);
	document.getElementById('mengkal'+iter).value = formatCurrency(document.getElementById('mengkal'+iter).value);
	document.getElementById('masak'+iter).value = formatCurrency(document.getElementById('masak'+iter).value);
	document.getElementById('toomasak'+iter).value = formatCurrency(document.getElementById('toomasak'+iter).value);
	jmlh = eval(document.getElementById('mentah'+iter).value) + eval(document.getElementById('mengkal'+iter).value) + 
		   eval(document.getElementById('masak'+iter).value) + eval(document.getElementById('toomasak'+iter).value);
	document.getElementById('janjang'+iter).value = jmlh;
}

//LoV UTK DAFTAR DOKUMEN
function showListNoPolisi() {
	var business_area = document.getElementById('ID_BA2').value;
	//alert(business_area);
	if (business_area != "")
		sList = window.open("popupNoPolisi.php?BA="+business_area, "Daftar_No_Polisi", "width=600,height=300");
}

function showListIntOrder() {
	var business_area = document.getElementById('ID_BA2').value;
	//alert(business_area);
	if (business_area != "")
		sList = window.open("popupIntOrder.php?BA="+business_area, "Daftar_Int_Order", "width=600,height=300");
}

function showListSupir(kode) {
	var afdeling = document.getElementById('afdeling').value;
	//var baris = row;
	if (afdeling != "0")
		sList = window.open("popupSupir.php?afdeling="+afdeling+"&baris="+kode+"", "Daftar_Dokumen", "width=800,height=500");
	else if (afdeling=="0")
		alert ("Pilih afdeling terlebih dahulu");
	else
		sList = window.open("popupSupir.php?afdeling="+afdeling+"&baris="+kode+"", "Daftar_Dokumen", "width=800,height=500");
}

function showListTkgMuat1(kode) {
	var afdeling = document.getElementById('afdeling').value;
	//var baris = row;
	if (afdeling != "0")
		sList = window.open("popupTkgMuat1.php?afdeling="+afdeling+"&baris="+kode+"", "Daftar_Dokumen", "width=800,height=500");
	else if (afdeling=="0")
		alert ("Pilih afdeling terlebih dahulu");
	else
		sList = window.open("popupTkgMuat1.php?afdeling="+afdeling+"&baris="+kode+"", "Daftar_Dokumen", "width=800,height=500");
}

function showListTkgMuat2(kode) {
	var afdeling = document.getElementById('afdeling').value;
	var nik1 = document.getElementById('nikTkgMuat1').value;
	
	if(nik1 != ""){
		if (afdeling != "0")
			sList = window.open("popupTkgMuat2.php?afdeling="+afdeling+"&baris="+kode+"", "Daftar_Dokumen", "width=800,height=500");
		else if (afdeling=="0")
			alert ("Pilih afdeling terlebih dahulu");
		else
			sList = window.open("popupTkgMuat2.php?afdeling="+afdeling+"&baris="+kode+"", "Daftar_Dokumen", "width=800,height=500");
	}else{
		alert("Mohon isi data Tukang Muat 1 terlebih dahulu..");
	}
}

function showListTkgMuat3(kode) {
	var afdeling = document.getElementById('afdeling').value;
	//var baris = row;
	var nik1 = document.getElementById('nikTkgMuat1').value;
	var nik2 = document.getElementById('nikTkgMuat2').value;
	if(nik1 != ""){
		if(nik2 != ""){
			if (afdeling != "0")
				sList = window.open("popupTkgMuat3.php?afdeling="+afdeling+"&baris="+kode+"", "Daftar_Dokumen", "width=800,height=500");
			else if (afdeling=="0")
				alert ("Pilih afdeling terlebih dahulu");
			else
				sList = window.open("popupTkgMuat3.php?afdeling="+afdeling+"&baris="+kode+"", "Daftar_Dokumen", "width=800,height=500");
		}else{
			alert("Mohon isi data Tukang Muat 2 terlebih dahulu..");
		}
	}else{
		alert("Mohon isi data Tukang Muat 1 terlebih dahulu..");
	}
}

function fill(Value){
	//Edited by Ardo, 03-11-2016 : Issue BCC yang dipilih bukan dobel jika no bcc identik
	var split = Value.split(' - ');
	$('#tmp_bcc').val(split[0]);
	$('#tmp_rencana').val(split[1]);
	$('#display').hide();
	var afdeling = $("#afdeling").val();
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

$(document).ready(function(){
	$("#deliv_ticket").keyup(function(){
		var deliv_ticket = $('#deliv_ticket').val();
		var afdeling = $("#afdeling").val();
		var tgl_kirim = $("#datepicker").val();
		if(tgl_kirim==""){
			alert("Pilih Tanggal Kirim terlebih dahulu.");
			return false;
		}if(afdeling=="0"){
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
				data: "deliv_ticket="+ deliv_ticket+"&afdeling="+afdeling + "&var_tgl=" + tgl_kirim ,
				success: function(html){
					$("#display").html(html).show();
				}
			});
		}
	});
});


</script>




<script type="text/javascript" href="../jquery-ui-1.10.4.custom/development-bundle/ui/ui.core.js"/></script>
<script type="text/javascript" href="../jquery-ui-1.10.4.custom/development-bundle/ui/ui.datepicker.js"/></script>

<link href="../css/style.css" rel="stylesheet" type="text/css" media="all" />
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>Edited by Ardo 03-11-2016 : tidak pentin --> 
<script type="text/javascript" src="../js/script.js"></script>

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
.f_alertRed10px {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #FF0000;
}
body,td,th {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-weight:normal;
}
.style2 {
	font-family : "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight:normal;
}
</style>
<form name="inputpengirimanpanen" id="inputpengirimanpanen">
<table width="1151" height="390" border="0" align="center">

  <!--<tr bgcolor="#C4D59E"> action="inputHasilPanenProses.php"-->
  <tr>
    <th height="197" scope="row" align="center">
	<table width="937" border="0" id="setbody2">
		<tr>
			<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>INPUT PENGIRIMAN HASIL PANEN BCP</strong></span></td>
		  </tr>  
	<tr>
		<td height="25" colspan="3" valign="bottom"></td>
	</tr>
	  <tr>
		  <tr>
			<td width="70" height="29" valign="top">Company Name</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" align="left" valign="top"><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$Comp_Name?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>            
			<td width="70" height="29" valign="top">Tanggal Kirim</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" valign="top"><input type="text" name="datepicker" id="datepicker" style="width:100px; height:20px;" readonly='readonly'><span class="f_alertRed10px" id="alert_datepicker"></span></td>
		  </tr>
		  <tr>
			<td width="70" height="29" valign="top">Business Area</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" align="left" valign="top"><input name="ID_BA2" type="text" id="ID_BA2" value="<?=$subID_BA_Afd?>" style="background-color:#CCC; width:70px; height:25px; font-size:15px" onmousedown="return false"/></td>
			<td width="70" height="29" valign="top">No NAB</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" valign="top"><input type="text" name="NoNAB" id="NoNAB" maxlength="7" style="width:100px; height:20px;" onkeypress='return isNumber(event)' onchange='cekUpper()' onblur='cekData()'><span class="f_alertRed10px" id="alert_NAB"></span></td>
		  </tr>
		  <tr>
			<td width="70" height="29" valign="top">&nbsp;</td>
			<td width="10" height="29" valign="top">&nbsp;</td>
			<td width="100" align="left" valign="top">&nbsp;</td> 
			<td width="70" height="29" valign="top">Afdeling Panen</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" align="left" valign="top">
				<select name="afdeling" id="afdeling">
				<option value='0' selected="selected">--select--</option>
				<?php
						$sql_t_BA  = "SELECT ID_BA_AFD, ID_AFD  FROM T_AFDELING TAFD LEFT JOIN T_BUSSINESSAREA TBA ON TAFD.ID_BA = TBA.ID_BA WHERE TBA.ID_BA = '$subID_BA_Afd'";
						$result_t_BA = oci_parse($con, $sql_t_BA);
									   oci_execute($result_t_BA, OCI_DEFAULT);
						while ($p=oci_fetch($result_t_BA)) {	
									  $id_kabkot = oci_result($result_t_BA, "ID_BA_AFD");
									  $nama_kabkot = oci_result($result_t_BA, "ID_AFD");
									  echo "<option value=\"$id_kabkot\">$nama_kabkot</option>\n";
						}
						?>
			   </select>
			   <input type="hidden" id="afd" name="afd" value="">
			   <span class="f_alertRed10px" id="alert_afd"></span>
			</td>
			</tr>
		   <tr>
			<td width="70" height="29" valign="top" style="border-bottom:solid #000"></td>
			<td width="10" height="29" valign="top" style="border-bottom:solid #000"></td>
			<td width="100" align="left" valign="top" style="border-bottom:solid #000"></td>            
			<td width="70" height="29" valign="top" style="border-bottom:solid #000"></td>
			<td width="10" height="29" valign="top" style="border-bottom:solid #000"></td>
			<td width="100" valign="top" style="border-bottom:solid #000"></td>
		  </tr>

        </tr>
		<tr>
			<td width="70" height="1" valign="top"></td>
		</tr>
		<tr>
			<td width="70" height="29" valign="top">Type Order</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" align="left" valign="top">
				<select name="TypeOrder" id="TypeOrder" onChange="check();">
					<option value='0' selected="selected">--select--</option>
					<option value='1'> INTERNAL </option>
					<option value='2'> EXTERNAL </option>
			   </select><span class="f_alertRed10px" id="alert_typeorder"></span>
			</td>
		</tr>
		<tr><td>
		<table id="tbl_noPolisi" border="0">
		<tr>
			<td width="200" height="29" valign="top">No. Polisi</td>
		</tr>
		</table>
		</td>
		<td>
		<table id="tbl_titikdua" border="0">
		<tr>
		<td width="10" height="29" valign="top">:</td>
		</tr>
		</table>
		</td>
		<td>
		<table id="tbl_txtnopol" border="0" height="29" width="320">
		<tr>
			<td width="100" align="left" valign="top">
				<input name="noPolisi1" type="text" id="noPolisi1" visible="false" value="" style="width:220px; height:20px; visibility:hidden; display:none" onClick="javascript:showListNoPolisi();" readonly='readonly'/>
				<input name="noPolisi2" type="text" id="noPolisi2" visible="false" maxlength='10' value="" style="width:220px; height:20px; visibility:hidden; display:none"/>				
				<span class="f_alertRed10px" id="alert_txtPolisi"></span>
			</td>
		</tr>
		</table>
		</td>
		<td>
		<table id="tbl_txtnoIntOrder" border="0">
			<tr><td width="320" height="29" valign="top">No. Internal Order</td></tr>
		</table>
		</td>
		<td>
		<table id="klm_titikdua" border="0">
		<tr><td width="10" height="29" valign="top">:</td></tr>
		</table>
		</td>
		<td>
		<table id="txt_noIntOrder" border="0" width="320">
		<tr><td width="100" align="left" valign="top">
				<input name="noIntOrder1" type="text" id="noIntOrder1" value="" visible="false" style="width:220px; height:20px; visibility:hidden; display:none" onClick="javascript:showListIntOrder();" readonly='readonly'/>
				<input name="noIntOrder2" type="text" id="noIntOrder2" value="" visible="false" disabled="true" style="width:220px; height:20px; visibility:hidden; display:none" readonly='readonly'/>
                <span class="f_alertRed10px" id="alert_txtOrder"></span>
			</td></tr>
		</table>
		</td>
		
		</tr>
		<tr>
			<td height="45" width="100" colspan="2" valign="bottom" style="border-bottom:solid #000">DATA SUPIR</td>
			<td height="45" colspan="0" valign="bottom" style="border-bottom:solid #000"><input type="button" name="buttonSupir" id="buttonSupir" value="Pinjam Karyawan" style="width:120px; height: 30px" onClick='javascript:showListSupir(3);'/></td>
		</tr>
		<tr>
			<td width="70" height="29" valign="top">NIK</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" align="left" valign="top">
				<input name="nikSupir1" type="text" id="nikSupir1" value="" style="width:220px; height:20px" onClick='javascript:showListSupir(1);' readonly='readonly'/>
                <!--input name="nikSupir2" type="text" id="nikSupir2" visible="false" readonly='readonly' value="" style="width:220px; height:20px; visibility:hidden; display:none"/--><span class="f_alertRed10px" id="alert_txtSupir1"></span>
			</td>
			<td width="70" height="29" valign="top">Nama</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" align="left" valign="top">
				<input name="namaSupir1" type="text" id="namaSupir1" value="" style="width:220px; height:20px" disabled="true"/>
				<input name="namaSupir2" type="text" id="namaSupir2" visible="false" maxlength='30' value="" style="width:220px; height:20px; visibility:hidden; display:none"/><span class="f_alertRed10px" id="alert_txtSupir2"></span>
			</td>
		</tr>
		<tr>
			<td height="45" width="100" colspan="2" valign="bottom" style="border-bottom:solid #000">DATA TUKANG MUAT 1<span class="f_alertRed10px" id="alert_txtTkgMuat"></span></td>
			<td height="45" colspan="0" valign="bottom" style="border-bottom:solid #000">
			<input type="button" name="buttonTkgMuat1" id="buttonTkgMuat1" value="Pinjam Karyawan" style="width:120px; height: 30px" onClick='javascript:showListTkgMuat1(3);'/>
			</td>
		</tr>
		<tr>
			<td width="70" height="29" valign="top">NIK</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" align="left" valign="top">
				<input name="nikTkgMuat1" type="text" id="nikTkgMuat1" value="" style="width:220px; height:20px" onClick='javascript:showListTkgMuat1(1);' readonly='readonly'/>
                <span class="f_alertRed10px" id="alert_txtTkgMuat"></span>
			</td>
			<td width="70" height="29" valign="top">Nama</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" align="left" valign="top">
				<input name="namaTkgMuat1" type="text" id="namaTkgMuat1" value="" style="width:220px; height:20px" disabled="true"/>
			</td>
		</tr>
		<tr>
			<td height="45" width="100" colspan="2" valign="bottom" style="border-bottom:solid #000">DATA TUKANG MUAT 2<span class="f_alertRed10px" id="alert_txtTkgMuat"></span></td>
			<td height="45" colspan="0" valign="bottom" style="border-bottom:solid #000">
			<input type="button" name="buttonTkgMuat2" id="buttonTkgMuat2" value="Pinjam Karyawan" style="width:120px; height: 30px" onClick='javascript:showListTkgMuat2(3);'/>
			</td>
		</tr>
		<tr>
			<td width="70" height="29" valign="top">NIK</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" align="left" valign="top">
				<input name="nikTkgMuat2" type="text" id="nikTkgMuat2" value="" style="width:220px; height:20px" onClick='javascript:showListTkgMuat2(1);' readonly='readonly'/>
			</td>
			<td width="70" height="29" valign="top">Nama</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" align="left" valign="top">
				<input name="namaTkgMuat2" type="text" id="namaTkgMuat2" value="" style="width:220px; height:20px" disabled="true"/>
			</td>
		</tr>
		<tr>
			<td height="45" width="100" colspan="2" valign="bottom" style="border-bottom:solid #000">DATA TUKANG MUAT 3<span class="f_alertRed10px" id="alert_txtTkgMuat"></span></td>
			<td height="45" colspan="0" valign="bottom" style="border-bottom:solid #000">
			<input type="button" name="buttonTkgMuat3" id="buttonTkgMuat3" value="Pinjam Karyawan" style="width:120px; height: 30px" onClick='javascript:showListTkgMuat3(3);'/>
			</td>
		</tr>
		<tr>
			<td width="70" height="29" valign="top">NIK</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" align="left" valign="top">
				<input name="nikTkgMuat3" type="text" id="nikTkgMuat3" value="" style="width:220px; height:20px" onClick='javascript:showListTkgMuat3(1);' readonly='readonly'/>
			</td>
			<td width="70" height="29" valign="top">Nama</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" align="left" valign="top">
				<input name="namaTkgMuat3" type="text" id="namaTkgMuat3" value="" style="width:220px; height:20px" disabled="true"/>
			</td>
		</tr>
		<tr>
			<td width="70" height="29" valign="top">&nbsp;</td>
			<td width="10" height="29" valign="top">&nbsp;</td>
			<td width="100" align="left" valign="top">&nbsp;</td>
		</tr>
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
		
	<tr>
		<th align="center"><?php
		
			if(isset($_GET['f'])){
				$err = $_GET['f'];
				if($err!=null)
				{
				?>
				<script type="text/javascript">
					alert("Data Inserted");
				</script>
				<?php
				}
			}
			?></th>
	  </tr>     
	  </table>
	  <div id='loading'></div>
	<div style="overflow:scroll; width:1200px" id='page_panen'>
			<frameset>
			<legend>BCC dikirim</legend>
				<table id="hasil_panen" border='1' cellpadding='1' cellspacing='1' style='table-layout:fixed; overflow-x:scroll'>
					<input name='countRow' type='hidden' id='countRow' value='0' style='width:70px; height:20px' readonly='readonly'/>
		
				</table>
				<table width='1095px' border='0'>
				<tr>
					<td align="right" width='820px'>Total Janjang Kirim</td>
					<td align="right" width='15px'>:</td>
					<td align="left" width='10px'><input id='total_janjang' name='total_janjang' style='width:50px; height:20px' type='text' value="0" readonly='readonly'/></td>
					<td width='70px'>&nbsp;</td>
					<td width='65px'>&nbsp;</td>
				</tr><tr align="center">
					<td colspan='5'><input id='btn_simpan' name='btn_simpan' type='button' value="Simpan"/></td>
				</tr>
			</table>
			</frameset>
	</div>
		

  
  <tr>
    <th align="center"><?php include("../include/Footer.php") ?></th>
  </tr>
</th>
</table>


</form>
<?php
}
else{
	$_SESSION[err] = "tolong login dulu!".$Job_Code."<br>".$username."<br>".$Emp_Name."<br>".$subID_BA_Afd;
	header("location:../index.php");
}
?>
