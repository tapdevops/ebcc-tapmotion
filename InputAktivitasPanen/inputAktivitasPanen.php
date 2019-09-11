<?php
session_start();
include("../include/Header.php");

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
		
		//Added by Ardo, 03-10-2016
		$sql_user_login  = 	"select c.id_cc as COMPANY_CODE, c.id_ba as BUSINESS_AREA, d.comp_name AS COMPANY_NAME
							from t_employee a, t_afdeling b, t_bussinessarea c, t_companycode d
							where a.id_ba_afd = b.id_ba_afd and b.id_ba = c.id_ba and c.id_cc = d.id_cc 
							and a.nik = '$_SESSION[NIK]'";
		$result_user_login	= select_data($con,$sql_user_login);
		$val_baa		= $result_user_login["BUSINESS_AREA"];
	  
		$sql_user_login  	= 	"select maksimum_jumlah_gandeng, id_ba, to_char(start_date,'MM/DD/YYYY') start_date, to_char(end_date,'MM/DD/YYYY') end_date from t_max_gandeng where id_ba='$val_baa'";
		$result_user_login	= select_data($con,$sql_user_login);
		$max_jml_gandeng	= $result_user_login["MAKSIMUM_JUMLAH_GANDENG"];
		if($max_jml_gandeng==null)
		{
			$max_jml_gandeng =  0;
		}
		
	}
	
?>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">

$(document).ready(function(){
  //apabila terjadi event change terhadap object
  
  
  $("#nikPemanen").blur(function(){
   var afdeling = $("#afdeling").val();
   var tgl_panen = $("#datepicker").val();
   var nik = $("#nikPemanen").val();
	if(tgl_panen != "" && nik != "" && afdeling != '0'){
		$.ajax({
			url: "getData.php",
			data: "afdeling="+afdeling + "&var_tgl=" + tgl_panen + "&nik=" + nik,
			method: 'POST',
			cache: false,
			success: function(msg){
				//jika data sukses diambil dari server kita tampilkan
				$("#aktivitas_panen").html(msg);
			}
		});
	}
  });
  
  $("#afdeling").change(function(){
   var afdeling = $("#afdeling").val();
   var tgl_panen = $("#datepicker").val();
   var nik = $("#nikPemanen").val();
	if(tgl_panen != "" && nik != "" && afdeling != '0'){
		$.ajax({
			url: "getData.php",
			data: "afdeling="+afdeling + "&var_tgl=" + tgl_panen + "&nik=" + nik,
			method: 'POST',
			cache: false,
			success: function(msg){
				//jika data sukses diambil dari server kita tampilkan
				$("#aktivitas_panen").html(msg);
			}
		});
	}
  });
  
  $("#datepicker").change(function(){
	document.getElementById('nikPemanen').value = "";
	document.getElementById('namaPemanen').value = "";
	document.getElementById('afdeling').value = 0;
   var afdeling = $("#afdeling").val();
   var tgl_panen = $("#datepicker").val();
   var nik = $("#nikPemanen").val();
	if(tgl_panen != "" && nik != "" && afdeling != '0'){
		$.ajax({
			url: "getData.php",
			data: "afdeling="+afdeling + "&var_tgl=" + tgl_panen + "&nik=" + nik,
			method: 'POST',
			cache: false,
			success: function(msg){
				//jika data sukses diambil dari server kita tampilkan
				$("#aktivitas_panen").html(msg);
			}
		});
	}
  });
  
  $("#btn_simpan").click( function() {
		if (validateInput() == false){
		} else {
			$('#loading').html('<img src="../image/loading.gif">');
			$('#aktivitas_panen').hide();
			$.ajax({
				type     : "post",
				url      : "inputAktivitasPanenProses.php",
				data     : $("#inputaktivitaspanen").serialize(),
				cache    : false,
				success  : function(data) {
					if(data == '1'){
						alert("Data Berhasil disimpan.");
						document.getElementById("inputaktivitaspanen").reset();
						$("#aktivitas_panen").find("tr:gt(1)").remove();
					}else{
						alert("Data Tidak Berhasil tersimpan.");
					}
				},
				complete : function(){
					$('#loading').hide();
					$("#aktivitas_panen").show();
				}
			});
		}
    });
});

function getBA(urutan){
	var comp_code = document.getElementById("cmb_compcode" + urutan).value;

	if(comp_code != "0"){
		$.ajax({
			url: "getBA.php",
			data: "comp_code="+comp_code,
			cache: false,
			success: function(msg){
				//jika data sukses diambil dari server kita tampilkan
				//di <select id=kota>
			   //for (j = 1; j <= i; j++){
				 $("#cmb_BA" + urutan).html(msg);
			   //}
			}
		});
	}else{
		alert("Pilih Company Code terlebih dahulu");
	}
}

function getAfdeling(urutan){
	var comp_code = document.getElementById("cmb_compcode" + urutan).value;
	var buss_area = document.getElementById("cmb_BA" + urutan).value;
	
	if(buss_area != "0"){
		$.ajax({
			url: "getAfdeling.php",
			data: "buss_area="+buss_area,
			cache: false,
			success: function(msg){
				//jika data sukses diambil dari server kita tampilkan
				//di <select id=kota>
			   //for (j = 1; j <= i; j++){
				 $("#cmb_Afd" + urutan).html(msg);
			   //}
			}
		});
	}
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

function validateInput(){

	var returnValue;
	returnValue = true;	
	
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
		var tgl_kirim = new Date(datepicker.value.replace(/-/gi,"/"));
		
		if(tgl_kirim > tgl_skrg){
			alert("Tanggal Panen tidak boleh lebih besar dari hari ini");
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
			//alert(count_date);
			/*if(count_date > 7){
				alert("Tanggal Panen tidak boleh lebih dari 7 hari");
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
	
	if (document.getElementById("nikPemanen").value == ""){	
		messageArea = document.getElementById("alert_txtPemanen");
		messageArea.innerHTML = '*NIK Pemanen harus dipilih!';
		returnValue = false;
	}else{
		messageArea = document.getElementById("alert_txtPemanen");
		messageArea.innerHTML = '';
		returnValue = true;
	}
	
	if (returnValue == false){	
		alert("Mohon isi terlebih dahulu data yang belum lengkap");
		return false;
	}
	
	
	var jmlrow = document.getElementById('countRow').value;
	var jml_max_gandeng = '<?= $max_jml_gandeng ?>';
	//alert(document.getElementById('row_count').value+" "+jml_max_gandeng);
	
	//Edited by Ardo, 30 Nov 2016 : parameter 0 tidak bisa input
	if(document.getElementById('row_count').value==1 && jml_max_gandeng==0){
		if(document.getElementById('nikGandeng1').value!=''){
			alert("Jumlaha NIK Gandeng melebihi parameter Panen Gandeng");
			return false;
			var aman = false;
		} else {
			var aman = false;
		}
	} else {
		var aman = true;
	}
	
	if(document.getElementById('row_count').value>jml_max_gandeng && aman==true){
		alert("jJumlah NIK Gandeng melebihi parameter Panen Gandeng");
		return false;
	}
	
	
	total = 0;
	for (i = 1; i <= jmlrow; i++){
		v_luasan_panen = document.getElementById('t_luasan_panen' + i).value;
		if (v_luasan_panen == ""){
			alert("Luasan Panen baris " + i + " belum diisi. ");
			return false;
		}
		if (v_luasan_panen == "0"){
			alert("Luasan Panen baris " + i + " tidak boleh isi 0. ");
			return false;
		}
		if (document.getElementById('t_bt_pokok' + i).value == ""){
			alert("Luasan Buah Tinggal (Pokok) baris " + i + " belum diisi. ");
			return false;
		}
		if (document.getElementById('t_bt_piringan' + i).value == ""){
			alert("Luasan Buah Tinggal (Piringan) baris " + i + " belum diisi. ");
			return false;
		}
		if (document.getElementById('t_pb_piringan' + i).value == ""){
			alert("Luasan Penalti Brondolan (Piringan) baris " + i + " belum diisi. ");
			return false;
		}
		if (document.getElementById('t_buahmatahari' + i).value == ""){
			alert("Luasan Buah Matahari baris " + i + " belum diisi. ");
			return false;
		}
	}	
	return returnValue;
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
}

function hide_eksternal(){
	document.getElementById('noPolisi2').style.visibility='hidden';
	document.getElementById('noPolisi2').style.display='none';
	document.getElementById('noIntOrder2').style.visibility='hidden';
	document.getElementById('noIntOrder2').style.display='none';
}

function show_internal(){
	document.getElementById('noPolisi1').style.visibility='visible';
	document.getElementById('noPolisi1').style.display='block';
	document.getElementById('noIntOrder1').style.visibility='visible';
	document.getElementById('noIntOrder1').style.display='block';
}

function show_eksternal(){
	document.getElementById('noPolisi2').style.visibility='visible';
	document.getElementById('noPolisi2').style.display='block';
	document.getElementById('noIntOrder2').style.visibility='visible';
	document.getElementById('noIntOrder2').style.display='block';
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

//LoV UTK DAFTAR DOKUMEN
function showListPemanen() {
	var afdeling = document.getElementById('afdeling').value;
	var tgl = document.getElementById('datepicker').value;
	//var baris = row;
	if (afdeling != "0" && tgl != "")
		sList = window.open("popupPemanen.php?afdeling="+afdeling+"&tgl_panen="+tgl+"", "Daftar_Dokumen", "width=800,height=500");
	else if (tgl == "")
		alert("Pilih tanggal panen terlebih dahulu");
	else if (afdeling=="0")
		alert ("Pilih afdeling terlebih dahulu");
	else
		sList = window.open("popupPemanen.php?afdeling="+afdeling+"&tgl_panen="+tgl+"", "Daftar_Dokumen", "width=800,height=500");
}

function showListGandeng(urutan) {
	var comp_code = document.getElementById('cmb_compcode'+urutan).value;
	var buss_area = document.getElementById('cmb_BA'+urutan).value;
	var afd = document.getElementById('cmb_Afd'+urutan).value;
	
	var id_ba_afd = buss_area+afd;
	//var baris = row;
	if (comp_code != "0" && buss_area != "0" && afd != "0")
		sList = window.open("popupGandeng.php?id_ba_afd="+id_ba_afd+"&urutan="+urutan+"", "Daftar_Dokumen", "width=800,height=500");
	else if (comp_code == "0")
		alert("Pilih Company Code terlebih dahulu");
	else if (buss_area == "0")
		alert ("Pilih Business Area terlebih dahulu");
	else if (afd == "0")
		alert ("Pilih Afdeling terlebih dahulu");
	else
		sList = window.open("popupGandeng.php?id_ba_afd="+id_ba_afd+"&urutan="+urutan+"", "Daftar_Dokumen", "width=800,height=500");
}


// TAMBAH BARIS
function addRowToTable() {
	var tbl = document.getElementById('tabel_gandeng2');
	var lastRow = tbl.rows.length;
	
	var next_record = (document.getElementById('row_count').value*1) + 1;
	var jml_max_gandeng = '<?= $max_jml_gandeng ?>';
	if(next_record>jml_max_gandeng){
		alert("Tidak bisa menambah jumlah NIK Gandeng");
	} else {
		document.getElementById('row_count').value = next_record;
		
		var iteration = (lastRow/2) + 1;
		
		var row = tbl.insertRow(lastRow);
		
		var afd = document.getElementById('afdeling').value;
		
		var cell0 = row.insertCell(0);
		var el = document.createElement('label');
		el.innerHTML = "Company Name";
		cell0.appendChild(el);
		
		var cell1 = row.insertCell(1);
		var el = document.createElement('label');
		el.innerHTML = ":";
		cell1.appendChild(el);

		var cell2 = row.insertCell(2);
		var sel = document.createElement('select');
		sel.name = 'cmb_compcode' + iteration;
		sel.id = 'cmb_compcode' + iteration;
		sel.options[0] = new Option('--select--', '0');
		var i = document.getElementById('row_count').value; 
		var id_ba = document.getElementById('ID_BA2').value;
		$.ajax({
			url: "getCC.php",
			data: "id_ba="+id_ba,
			cache: false,
			success: function(msg){
			   //for (j = 1; j <= i; j++){
				 $("#cmb_compcode" + iteration).html(msg);
			   //}
			}
		});
		sel.onchange = function(){
			var comp_code = document.getElementById("cmb_compcode" + iteration).value;

			if(comp_code != "0"){
				$.ajax({
					url: "getBA.php",
					data: "comp_code="+comp_code,
					cache: false,
					success: function(msg){
						 $("#cmb_BA" + iteration).html(msg);
					}
				});
			}else{
				alert("Pilih Company Code terlebih dahulu");
			}
		}
		cell2.appendChild(sel);

		var cell3 = row.insertCell(3);
		var el = document.createElement('label');
		el.innerHTML = "Business Area";
		cell3.appendChild(el);
		
		var cell4 = row.insertCell(4);
		var el = document.createElement('label');
		el.innerHTML = ":";
		cell4.appendChild(el);
		
		var cell5 = row.insertCell(5);
		var sel = document.createElement('select');
		sel.name = 'cmb_BA' + iteration;
		sel.id = 'cmb_BA' + iteration;
		sel.options[0] = new Option('--select--', '0');
		sel.onchange = function(){
			var comp_code = document.getElementById("cmb_compcode" + iteration).value;
			var buss_area = document.getElementById("cmb_BA" + iteration).value;
			
			if(buss_area != "0"){
				$.ajax({
					url: "getAfdeling.php",
					data: "buss_area="+buss_area,
					cache: false,
					success: function(msg){
						 $("#cmb_Afd" + iteration).html(msg);
					}
				});
			}
		}
		cell5.appendChild(sel);
		
		var cell6 = row.insertCell(6);
		var el = document.createElement('label');
		el.innerHTML = "Business Area";
		cell6.appendChild(el);
		
		var cell7 = row.insertCell(7);
		var el = document.createElement('label');
		el.innerHTML = ":";
		cell7.appendChild(el);
		
		var cell8 = row.insertCell(8);
		var sel = document.createElement('select');
		sel.name = 'cmb_Afd' + iteration;
		sel.id = 'cmb_Afd' + iteration;
		sel.options[0] = new Option('--select--', '0');
		cell8.appendChild(sel);
		
		
		var tbl = document.getElementById('tabel_gandeng2');
		var lastRow = tbl.rows.length;
		
		var row = tbl.insertRow(lastRow);

		var cell0 = row.insertCell(0);
		var el = document.createElement('label');
		el.innerHTML = "NIK";
		cell0.appendChild(el);
		
		var cell1 = row.insertCell(1);
		var el = document.createElement('label');
		el.innerHTML = ":";
		cell1.appendChild(el);
		
		var cell2 = row.insertCell(2);
		var el = document.createElement('input');
		el.type = 'text';
		el.name = 'nikGandeng' + iteration;
		el.id = 'nikGandeng' + iteration;
		el.style.width = '220px';
		el.style.height = '20px';
		el.readOnly = true;
		el.onclick = function(){
			var comp_code = document.getElementById('cmb_compcode'+iteration).value;
			var buss_area = document.getElementById('cmb_BA'+iteration).value;
			var afd = document.getElementById('cmb_Afd'+iteration).value;
			
			var id_ba_afd = buss_area+afd;
			//var baris = row;
			if (comp_code != "0" && buss_area != "0" && afd != "0")
				sList = window.open("popupGandeng.php?id_ba_afd="+id_ba_afd+"&urutan="+iteration+"", "Daftar_Dokumen", "width=800,height=500");
			else if (comp_code == "0")
				alert("Pilih Company Code terlebih dahulu");
			else if (buss_area == "0")
				alert ("Pilih Business Area terlebih dahulu");
			else if (afd == "0")
				alert ("Pilih Afdeling terlebih dahulu");
			else
			sList = window.open("popupGandeng.php?id_ba_afd="+id_ba_afd+"&urutan="+iteration+"", "Daftar_Dokumen", "width=800,height=500");
		}
		cell2.appendChild(el);
		
		var cell3 = row.insertCell(3);
		var el = document.createElement('label');
		el.innerHTML = "&nbsp;";
		cell3.appendChild(el);
		
		var cell4 = row.insertCell(4);
		var el = document.createElement('label');
		el.innerHTML = "&nbsp;";
		cell4.appendChild(el);

		var cell5 = row.insertCell(5);
		var el = document.createElement('label');
		el.innerHTML = "&nbsp;";
		cell5.appendChild(el);
		
		var cell6 = row.insertCell(6);
		var el = document.createElement('label');
		el.innerHTML = "Nama Karyawan";
		cell6.appendChild(el);
		
		var cell7 = row.insertCell(7);
		var el = document.createElement('label');
		el.innerHTML = ":";
		cell7.appendChild(el);
		
		var cell8 = row.insertCell(8);
		var el = document.createElement('input');
		el.type = 'text';
		el.name = 'namaGandeng' + iteration;
		el.id = 'namaGandeng' + iteration;
		el.style.width = '220px';
		el.style.height = '20px';
		el.readOnly = true;
		cell8.appendChild(el);
	}
}

function removeRowFromTable() {
	var tbl = document.getElementById('tabel_gandeng2');
	var lastRow = tbl.rows.length;
	if(document.getElementById('row_count').value > 1)
		document.getElementById('row_count').value -= 1;
	if (lastRow > 2) {
		tbl.deleteRow(lastRow - 1);
		tbl.deleteRow(lastRow - 2);
	}
}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 46 || charCode > 57)) {
        return false;
    }
    return true;
}

</script>


<script type="text/javascript" href="../jquery-ui-1.10.4.custom/development-bundle/ui/ui.core.js"/></script>
<script type="text/javascript" href="../jquery-ui-1.10.4.custom/development-bundle/ui/ui.datepicker.js"/></script>

<link href="../css/style.css" rel="stylesheet" type="text/css" media="all" />
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>-->
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
<form name="inputaktivitaspanen" id="inputaktivitaspanen">
<table width="1151" height="390" border="0" align="center">

  <!--<tr bgcolor="#C4D59E"> action="inputHasilPanenProses.php"-->
  <tr>
    <th height="197" scope="row" align="center">
	<table width="937" border="0" id="setbody2">
		<tr>
			<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>INPUT AKTIVITAS AKHIR PANEN BCP</strong></span></td>
		  </tr>  
	<tr>
		<td height="35" valign="bottom" style="border-bottom:solid #000">LOKASI PANEN</td>
		<td height="25" colspan="6" valign="bottom" style="border-bottom:solid #000"></td>
	</tr>
	  <tr>
		  <tr>
			<td width="70" height="29" valign="top">Company Name</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" align="left" valign="top"><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$Comp_Name?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>            
			<td width="70" height="29" valign="top">Tanggal Panen</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" valign="top"><input type="text" name="datepicker" id="datepicker" readonly='readonly'><span class="f_alertRed10px" id="alert_datepicker"></span></td>
		  </tr>
		  <tr>
			<td width="70" height="29" valign="top">Business Area</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" align="left" valign="top"><input name="ID_BA2" type="text" id="ID_BA2" value="<?=$subID_BA_Afd?>" style="background-color:#CCC; width:70px; height:25px; font-size:15px" onmousedown="return false"/></td>
			
			
		  </tr>
		  <tr>
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
			<td width="70" height="45" valign="bottom" style="border-bottom:solid #000">DATA PEMANEN</td>
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
			<td width="70" height="29" valign="top">NIK</td>
			<td width="10" height="29" valign="top">:</td>
			<td><input name="nikPemanen" type="text" id="nikPemanen" value="" style="width:220px; height:20px" onClick='javascript:showListPemanen();' readonly='readonly'/>
                <span class="f_alertRed10px" id="alert_txtPemanen"></span>
			</td>
			<td width="70" height="29" valign="top">Nama Karyawan</td>
			<td width="10" height="29" valign="top">:</td>
			<td width="100" align="left" valign="top">
				<input name="namaPemanen" type="text" id="namaPemanen" value="" style="width:220px; height:20px" disabled="true"/>
			</td>
		</tr>
		
		<tr>
			<td height="45" width="100" colspan="0" valign="bottom" style="border-bottom:solid #000">AKTIVITAS AKHIR PANEN</td>
			<td height="45" colspan="5" valign="bottom" style="border-bottom:solid #000"></td>
		</tr>
		<tr>
			<div id='loading'></div>
			<td width="70" height="29" colspan="6" valign="top">
				<table id="aktivitas_panen" border='1' cellpadding='1' cellspacing='1' align='center' style='table-layout:fixed; overflow-x:scroll'>
					
				</table>
			</td>
	
		</tr>
		<tr>
		<td colspan='6'>
		
		<div style="overflow:scroll; width:1200px">
				<table id="tabel_gandeng1" width="1180px" border='0' cellpadding='1' cellspacing='1' style='table-layout:fixed; overflow-x:scroll'>
					<tr>
						<td height="45" width="80" colspan="9" valign="bottom" style="border-bottom:solid #000">DATA PEMANEN GANDENG</td>
					</tr>
					</table>
					<table id="tabel_gandeng2" width="980px" border='0' cellpadding='1' cellspacing='1' style='table-layout:fixed; overflow-x:scroll'>
					<tr>
						<td height="29" width='80px' valign="top">Company Code</td>
						<td height="29" width='10px' valign="top">:</td>
						<td align="left" width='50px' valign="top">
							<select name="cmb_compcode1" id="cmb_compcode1" onchange="getBA(1);">
								<option value='0' selected="selected"> --select-- </option>
								<?php
										$sql_t_BA  = "select distinct(SUBSTR(ID_BA, 0,2)) as ID_BA from T_ALTERNATE_BA_GROUP TABA1 where 
														ID_GROUP_BA = (select ID_GROUP_BA from T_ALTERNATE_BA_GROUP 
														where ID_BA = '$subID_BA_Afd') order by ID_BA";
										$result_t_BA = oci_parse($con, $sql_t_BA);
													   oci_execute($result_t_BA, OCI_DEFAULT);
										while ($p=oci_fetch($result_t_BA)) {	
													  $id_ba = oci_result($result_t_BA, "ID_BA");
													  $nama_ba = oci_result($result_t_BA, "ID_BA");
													  echo "<option value=\"$id_ba\">$nama_ba</option>\n";
										}
										?>
							</select><br />
							<span class="f_alertRed10px" id="alert_cmbCompCode1"></span>
						</td>
						
						<td height="29" width='80px' valign="top">Business Area</td>
						<td height="29" width='10px' valign="top">:</td>
						<td align="left" width='50px' valign="top">
							<select name="cmb_BA1" id="cmb_BA1" onchange="getAfdeling(1);">
								<option value='0' selected="selected"> --select-- </option>
							</select><br />
							<span class="f_alertRed10px" id="alert_cmbBA"></span>
						</td>
						
						<td height="29" width='80px' valign="top">Afdeling</td>
						<td height="29" width='10px' valign="top">:</td>
						<td align="left" width='50px' valign="top">
							<select name="cmb_Afd1" id="cmb_Afd1">
								<option value='0' selected="selected"> --select-- </option>
							</select><br />
							<span class="f_alertRed10px" id="alert_cmbAfd"></span>
						</td>
					</tr>
					<tr>
						<td height="29" valign="top">NIK</td>
						<td height="29" valign="top">:</td>
						<td align="left" colspan="2" valign="top">
							<input name="nikGandeng1" type="text" id="nikGandeng1" value="" style="width:220px; height:20px" onClick='javascript:showListGandeng(1);' readonly='readonly'/>
						</td>
						<td height="29" colspan="2" valign="top"></td>
						<td height="29" valign="top">Nama Karyawan</td>
						<td height="29" valign="top">:</td>
						<td align="left" valign="top">
							<input name="namaGandeng1" type="text" id="namaGandeng1" value="" style="width:220px; height:20px" readOnly='readonly'/>
						</td>
					</tr>
					</div>
					</td>
	  </tr>
	  </table> 
	<table width='100%'>
		<tr>
			<td align="left"><input id='btn_tambah' name='btn_tambah' type='button' value="Tambah" onclick='addRowToTable();'/>&nbsp; &nbsp; &nbsp;
			<input onclick='removeRowFromTable();' type='button' value="Hapus"/></td>
			<td align="left"><td>
			<td align="right"><input id='btn_simpan' name='btn_simpan' type='button' value="Simpan"/></td>
			<input name="row_count" type="hidden" id="row_count" value="1"/>
		</tr>
	</table>
  
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
