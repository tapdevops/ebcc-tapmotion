<?php
session_start();
include("../include/Header.php");

if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name'])) {
	$Job_Code = $_SESSION['Job_Code'];
	$username = $_SESSION['NIK'];
	$Emp_Name = $_SESSION['Name'];
	//$Jenis_Login = $_SESSION['Jenis_Login'];
	$Comp_Name = $_SESSION['Comp_Name'];
	$subID_BA_Afd = $_SESSION['subID_BA_Afd'];

	$Date = $_SESSION['Date'];
	$ID_Group_BA = $_SESSION['ID_Group_BA'];

	if($username == "") {
		$_SESSION[err] = "tolong login dulu!";
		header("location:login.php");
	} else {
		include("../config/SQL_function.php");
		include("../config/db_connect.php");

		$con = connect();

		if(isset($_POST["editNO_BCC"])) {
			$_SESSION["editNO_BCC"] = $_POST["editNO_BCC"];
		}
		$NO_BCC = 0;
		if(isset($_SESSION["editNO_BCC"])) {
			$NO_BCC = $_SESSION["editNO_BCC"];
		}

		$sql_t_BCC = "
			select 
				thrp.tanggal_rencana tanggal, thrp.id_rencana id_rencana,
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
			where thp.no_bcc = '$NO_BCC'
		";
		//echo $sql_t_BCC; die;
		$result_t_BCC = oci_parse($con, $sql_t_BCC);
		oci_execute($result_t_BCC, OCI_DEFAULT);
		while(oci_fetch($result_t_BCC)) {
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

		$sql_t_BCC_table = "
			SELECT H.ID_BCC_KUALITAS ,K.ID_KUALITAS, K.NAMA_KUALITAS, H.QTY  FROM  t_kualitas_panen K, t_hasilpanen_kualtas H 
			WHERE  H.ID_BCC(+)='$NO_BCC' 
			AND K.ID_KUALITAS = H.ID_KUALITAS(+)
			ORDER BY Group_kualitas,ID_KUALITAS ASC
		";
		$result_t_BCC_table = oci_parse($con, $sql_t_BCC_table);
		oci_execute($result_t_BCC_table, OCI_DEFAULT);
		while(oci_fetch($result_t_BCC_table)) {
			$NAMA_KUALITAS[] 	= oci_result($result_t_BCC_table, "NAMA_KUALITAS");
			$QTY[] 				= oci_result($result_t_BCC_table, "QTY");
			$ID_BCC_KUALITAS[] 	= oci_result($result_t_BCC_table, "ID_BCC_KUALITAS");
			$ID_Kualitas[]		= oci_result($result_t_BCC_table, "ID_KUALITAS");
		}
		$roweffec_BCC = oci_num_rows($result_t_BCC_table);
		
		if(isset($_POST['BA'])) {
			$_SESSION['BA'] = $_POST['BA'];
		}
		
		if(isset($_SESSION['BA'])) {
			$ses_BA = $_SESSION['BA'];
			if($ses_BA  == "") {
				$sql_BA = "
					select * from t_BussinessArea tba
					inner join t_CompanyCode tcc on tba.id_cc = tcc.id_cc
					inner join t_alternate_ba_group tabg on tba.id_ba = tabg.id_ba
					where tabg.id_group_ba in (select id_group_ba from t_bussinessarea where id_ba = $subID_BA_Afd)
					order by tba.id_ba
				";
				$sql_t_Emp_All  = "SELECT * from t_employee WHERE JOB_CODE = 'PEMANEN'";
				$optionBA = "";
			} else {
				$sql_BA = "
					select * from t_BussinessArea tba
					inner join t_CompanyCode tcc on tba.id_cc = tcc.id_cc 
					inner join t_alternate_ba_group tabg on tba.id_ba = tabg.id_ba
					where tabg.id_group_ba in (select id_group_ba from t_bussinessarea where id_ba = $subID_BA_Afd)
					AND tba.ID_BA != '$ses_BA' order by tba.id_ba
				";
				$optionBA = "<option value=\"$ses_BA\" selected=\"selected\">$ses_BA</option>";
				$sql_t_Emp_All  = "
					select * from t_employee te
					inner join t_afdeling ta on te.id_ba_afd = ta.id_ba_afd
					where ta.id_ba = '$ses_BA' and te.job_code = 'PEMANEN'
				";
			}
		} else {
			$sql_BA = "
				select * from t_BussinessArea tba
				inner join t_CompanyCode tcc on tba.id_cc = tcc.id_cc 
				inner join t_alternate_ba_group tabg on tba.id_ba = tabg.id_ba
				where tabg.id_group_ba in (select id_group_ba from t_bussinessarea where id_ba = $subID_BA_Afd)
				order by tba.id_ba
			";
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
		while(oci_fetch($result_BA)) {
			$ID_BA[]		= oci_result($result_BA, "ID_BA");
		}
		$roweffec_BA = oci_num_rows($result_BA);
	}
	
?>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
	function formSubmit(x) {
		document.getElementById('NIK_Pemanen').value = x;
		document.getElementById("FormPemanen").submit();
	}

	function formSubmitvalue() {
		//document.getElementById('NIK_Pemanen').value = x;
		//alert('Pastikan koreksi data Anda telah mendapatkan persutujan dari EM atau KABUN !!!');
		document.getElementById("FormEditBCC").submit();
	}

	function getTPH(t) {
		var id = t.id;
		var tph = id.replace('blok', 'tph');
		var afdeling = $('#afdeling').val();
		var blok = $('#' + id).val();

		$.ajax({
			url: 'ambiltph.php',
			type: 'POST',
			dataType: 'json',
			data: { afdeling: afdeling, blok: blok, tph: tph },
			cache: false,
			success: function(data) {
				$('#' + tph).html(data);
			}
		});
    }

	$(document).ready(function() {
		//apabila terjadi event onchange terhadap object <select id=propinsi>
		$("#afdeling").change(function() {
			var afdeling = $("#afdeling").val();
			var i = document.getElementById('countRow').value; //added by NB 04.07.2014

			$.ajax({
				url: "ambilkota.php",
				data: "afdeling="+afdeling,
				cache: false,
				success: function(msg) {
					//jika data sukses diambil dari server kita tampilkan
					//di <select id=kota>
					for (j = 1; j <= i; j++) {
						$("#blok" + j).html(msg);
					}
				}
			});
		});

		/*$('#blok1').change(function() {
			var afdeling = $('#afdeling').val();
			var blok = $('#blok1').val();

			$.ajax({
				url: 'ambiltph.php',
				type: 'POST',
				dataType: 'json',
				data: { afdeling: afdeling, blok: blok },
				cache: false,
				success: function(data) {
					//for ()
					$('#blok1').html(data);
				}
			})
		});*/

		$("#btn_simpan").click( function() {
			if (validateInput() == false) {
			} else {
				$('#loading').html('<img src="../image/loading.gif">');
				$('#page_hasil').hide();
				$.ajax({
					type     : "post",
					url      : "inputHasilPanenProses.php",
					data     : $("#inputhasilpanen").serialize(),
					cache    : false,
					success  : function(data) {
						//alert(data);
						var hasil = data.split(" . # . ");
						var kd_bcc = "";
						//alert(hasil[0]);
						if(hasil[0] == '1') {
							jml_data = parseInt(hasil[1]) + 2;
							for(j = 2; j <= jml_data; j++) {
								if(j == jml_data) {
									kd_bcc = kd_bcc + hasil[j] + '.';
								} else {
									kd_bcc = kd_bcc + hasil[j] + ', ';
								}
							}
							alert("Data Berhasil disimpan dengan kode bcc : " + kd_bcc);
							var jmlrow = document.getElementById('countRow').value;
							document.getElementById("datepicker").value = "";
							document.getElementById("afdeling").value = "";
							document.getElementById("nikPemanen").value = "";
							document.getElementById("namaPemanen").value = "";

							for (i = 1; i <= jmlrow; i++) {
								document.getElementById("blok" + i).value = "";
								document.getElementById("tph" + i).value = "";
								document.getElementById("ticket" + i).value = "";
								document.getElementById("mentah" + i).value = "0";
								document.getElementById("mengkal" + i).value = "0";
								document.getElementById("masak" + i).value = "0";
								document.getElementById("toomasak" + i).value = "0";
								document.getElementById("janjang" + i).value = "0";
								document.getElementById("busuk" + i).value = "0";
								document.getElementById("jangkos" + i).value = "0";
								document.getElementById("buborsi" + i).value = "0";
								document.getElementById("tangkai_panjang" + i).value = "0";
								document.getElementById("abnormal" + i).value = "0";
								document.getElementById("alas" + i).value = "";
								document.getElementById("hama" + i).value = "0";
								document.getElementById("brondolan" + i).value = "0";
							}
						} else if(data.split(" ", 1) == "Sama") {
							var res = data.split(" ");
							alert("Blok dengan TPH dan delivery ticket baris ke " + res[1] + " sama dengan baris ke " + res[2] + ".");
						} else if(data.split(" ", 1) == "Ada") {
							var res = data.split(" ");
							alert("Blok dengan TPH dan Delivery ticket baris ke " + res[1] + " sudah pernah dibuat.");
						} else if(hasil[2] == '0') {
							alert("Data sudah pernah terpakai. Tidak berhasil disimpan");
						}
					},
					complete : function() {
						$('#loading').hide();
						$("#page_hasil").show();
					}
				});
			}
		});
	});

	function sleep(milliseconds) {
		var start = new Date().getTime();
		for (var i = 0; i < 1e7; i++) {
			if ((new Date().getTime() - start) > milliseconds) {
				break;
			}
		}
	}

	function datediff(date1, date2) {
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

	function finishAjax(response) {
		alert(response);
	}

	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}

	function isDigit(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode != 48 && charCode != 49 && charCode != 8 && charCode != 9) {
			return false;
		}
		return true;
	}

	function validateInput(elem) {
		var returnValue;
		returnValue = true;
		var jmlrow = document.getElementById('countRow').value;
		var nik_pemanen = $("#nikPemanen").val();
		var tgl = $("#datepicker").val();

		var v_blok = "";
		var v_tph = "";
		var v_ticket = "";

		if (document.getElementById("datepicker").value == "") {
			messageArea = document.getElementById("alert_datepicker");
			messageArea.innerHTML = '* Tanggal Panen Harus Diisi!';
			returnValue = false;
		} else {
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

			if(tgl_kirim > tgl_skrg) {
				alert("Tanggal Penginputan Hasil tidak boleh lebih besar dari hari ini");
				return false;
			} else {
				var date_Harv = $.datepicker.formatDate('yy/m/d', new Date(datepicker.value));
				var count_date = datediff(date_Harv, today_date);
				/*if(count_date > 7) {
					alert("Tanggal Penginputan Hasil Panen tidak boleh lebih dari 7 hari");
					return false;
				}*/ //ditutup sementara untuk Kalbar
			}
		}

		if (document.getElementById("txtNik").value == "") {
			messageArea = document.getElementById("alert_txtNIK");
			messageArea.innerHTML = '*NIK Krani Buah harus diisi!';
			returnValue = false;
		}
		if (document.getElementById("nikMandor").value == "") {
			messageArea = document.getElementById("alert_txtMandor");
			messageArea.innerHTML = '*NIK Mandor harus diisi!';
			returnValue = false;
		}
		if (document.getElementById("nikPemanen").value == "") {
			messageArea = document.getElementById("alert_txtPemanen");
			messageArea.innerHTML = '*NIK Pemanen harus diisi!';
			returnValue = false;
		}

		if (document.getElementById("txtNik").value != "" && document.getElementById("nikMandor").value != "" && document.getElementById("nikPemanen").value != "" && document.getElementById("datepicker").value != "") {
			messageArea = document.getElementById("alert_txtNIK");
			messageArea.innerHTML = '';
			messageArea = document.getElementById("alert_txtMandor");
			messageArea.innerHTML = '';
			messageArea = document.getElementById("alert_txtPemanen");
			messageArea.innerHTML = '';
			returnValue = true;
		}

		if (returnValue == false) {
			alert("Mohon isi terlebih dahulu data yang belum lengkap");
			return false;
		}

		for (i = 1; i <= jmlrow; i++) {
			v_blok = document.getElementById("blok" + i).selectedIndex;
			v_tph = document.getElementById("tph" + i).value;
			v_ticket = document.getElementById("ticket" + i).value;
			v_mentah = document.getElementById("mentah" + i).value;
			v_mengkal = document.getElementById("mengkal" + i).value;
			v_masak = parseInt(document.getElementById("masak" + i).value);
			v_toomasak = parseInt(document.getElementById("toomasak" + i).value);
			v_busuk = parseInt(document.getElementById("busuk" + i).value);
			v_janjang = parseInt(document.getElementById("janjang" + i).value);
			v_abnormal = parseInt(document.getElementById("abnormal" + i).value);
			v_hama = parseInt(document.getElementById("hama" + i).value);
			v_tangkai_pjg = parseInt(document.getElementById("tangkai_panjang" + i).value);
			v_ttl = eval(v_mentah) + eval(v_mengkal) + eval(v_masak) + eval(v_toomasak) + eval(v_busuk);

			if(v_blok == '0') {
				alert("Blok pada baris ke-" + i + " belum dipilih!");
				return false;
			}

			if (v_tph.replace(" ", "") == "") {
				alert("TPH pada baris ke-" + i + " belum diisi!");
				return false;
			} else if (v_tph.length != '3') {
				alert("TPH pada baris ke-" + i + " harus tepat 3 digit!");
				return false;
			}

			if (v_ticket.replace(" ", "") == "") {
				alert("Delivery Ticket pada baris ke-" + i + " belum diisi!");
				return false;
			} else if (v_ticket.length != '5') {
				alert("Ticket pada baris ke-" + i + " harus tepat 5 digit!");
				return false;
			}

			if(v_janjang == '0') {
				alert("Jumlah janjang Panen tidak boleh 0!");
				return false;
			}

			if(v_tangkai_pjg > v_masak) {
				alert("Jumlah janjang tangkai panjang baris ke-" + i + " tidak boleh melebihi jumlah janjang masak baris ke-" + i + "!");
				return false;
			}

			if(v_abnormal > v_ttl) {
				alert("Jumlah janjang abnormal baris ke-" + i + " tidak boleh melebihi jumlah janjang kirim baris ke-" + i + "!");
				return false;
			}

			if(v_hama > v_ttl) {
				alert("Jumlah janjang dimakan tikus baris ke-" + i + " tidak boleh melebihi jumlah janjang kirim baris ke-" + i + "!");
				return false;
			}
		}
		return returnValue;
	}

	function cekData() {
		var nik_pemanen = $("#nikPemanen").val();
		var tgl = $("#datepicker").val();
		var v_afd = document.getElementById('afdeling').value;
		var jmlrow = document.getElementById('countRow').value;
		var v_ba = document.getElementById('ID_BA2').value;

		for (i = 1; i <= jmlrow; i++) {
			var v_blok = document.getElementById('blok' + i).value;
			//var v_tph = document.getElementById('tph' + i).value;
			var v_ticket = document.getElementById('ticket' + i).value;

			$.ajax({
				url: "cekValidasi.php",
				data: "nik=" + nik_pemanen + "&var_tgl=" + tgl + "&var_ba=" + v_ba + "&var_blok=" + v_blok + "&var_afd=" + v_afd,
				cache: false,
				success: function(msg) {
					//jika data sukses diambil dari server kita tampilkan
					//di <select id=kota>
					if(msg == "kosong") {
					} else {
						confirm("Data Pemanen dan Blok pernah diinput, apakah anda akan menambahkan data hasil panen?");
					}
				}
			});	
		}
	}
</script>

<script type="text/javascript" href="../jquery-ui-1.10.4.custom/development-bundle/ui/ui.core.js"/></script>
<script type="text/javascript" href="../jquery-ui-1.10.4.custom/development-bundle/ui/ui.datepicker.js"/></script>

<link href="../css/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
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
	a:link { text-decoration: none; }
	a:visited { text-decoration: none; }
	a:hover { text-decoration: none; }
	a:active { text-decoration: none; }
	.style1 { color: #FF0000; font-weight: bold; }
	.f_alertRed10px { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #FF0000; }
	.f_alert13px { font-family: Arial, Helvetica, sans-serif; font-size: 9px; }
	body,td,th { font-family: "Trebuchet MS", Arial, Helvetica, sans-serif; font-size: 13px; font-weight:normal; }
	.style2 { font-family : "Trebuchet MS", Arial, Helvetica, sans-serif; font-size: 12px; font-weight:normal; }
</style>

<form name="inputhasilpanen" id="inputhasilpanen" method = "post" action="inputHasilPanenProses.php">
	<table width="1151" height="390" border="0" align="center">
		<!--<tr bgcolor="#C4D59E">-->
		<tr>
			<th height="197" scope="row" align="center">
				<table width="937" border="0" id="setbody2">
					<tr>
						<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>INPUT HASIL PANEN</strong></span></td>
					</tr>
					<tr>
						<td height="45" colspan="3" valign="bottom" style="border-bottom:solid #000">LOKASI</td>
						<td height="45" colspan="3" valign="bottom" style="border-bottom:solid #000">PERIODE</td>
					</tr>
					<tr>
						<tr>
							<td width="70" height="29" valign="top">Company Name</td>
							<td width="10" height="29" valign="top">:</td>
							<td width="100" align="left" valign="top"><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$Comp_Name?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
							<td width="70" height="29" valign="top">Tanggal Panen</td>
							<td width="10" height="29" valign="top">:</td>
							<td width="100" valign="top"><input type="text" name="datepicker" id="datepicker" readonly='readonly'><br /><span class="f_alertRed10px" id="alert_datepicker"></span></td>
						</tr>
						<tr>
							<td width="70" height="29" valign="top">Business Area</td>
							<td width="10" height="29" valign="top">:</td>
							<td width="100" align="left" valign="top"><input name="ID_BA2" type="text" id="ID_BA2" value="<?=$subID_BA_Afd?>" style="background-color:#CCC; width:70px; height:25px; font-size:15px" onmousedown="return false"/></td>
						</tr>
						<tr>
							<td width="70" height="29" valign="top">Afdeling</td>
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
							</td>
						</tr>
					</tr>
					<tr>
						<td height="45" width="100" colspan="0" valign="bottom" style="border-bottom:solid #000">DATA KRANI BUAH</td>
						<td height="45" colspan="0" valign="bottom" style="border-bottom:solid #000">
							<input type="button" name="buttonKrani" id="buttonKrani" value="Pinjam Karyawan" style="width:120px; height: 30px" onClick='javascript:showList(2);'/>
						</td>
					</tr>
					<tr>
						<td width="70" height="29" valign="top">NIK</td>
						<td width="10" height="29" valign="top">:</td>
						<td width="100" align="left" valign="top">
							<!-- input name="txtNik" type="text" id="txtNik" value="" style="width:220px; height:20px" onclick="javascript:showList(1);"/-->
							<input name="txtNik" type="text" id="txtNik" value="" style="width:220px; height:20px" onclick='return showList(1)' readonly='readonly'/><br />
							<span class="f_alertRed10px" id="alert_txtNIK"></span>
						</td>
						<td width="70" height="29" valign="top">Nama</td>
						<td width="10" height="29" valign="top">:</td>
						<td width="100" align="left" valign="top">
							<input name="txtNama" type="text" id="txtNama" value="" style="width:220px; height:20px" readonly='readonly'/>
						</td>
					</tr>
					<tr>
						<td height="45" width="100" colspan="0" valign="bottom" style="border-bottom:solid #000">DATA MANDOR</td>
						<td height="45" colspan="0" valign="bottom" style="border-bottom:solid #000">
							<input type="button" name="buttonMandor" id="buttonMandor" value="Pinjam Karyawan" style="width:120px; height: 30px" onClick='javascript:showListMandor(4);'/>
						</td>
					</tr>
					<tr>
						<td width="70" height="29" valign="top">NIK</td>
						<td width="10" height="29" valign="top">:</td>
						<td width="100" align="left" valign="top">
							<input name="nikMandor" type="text" id="nikMandor" value="" style="width:220px; height:20px" onClick='javascript:showListMandor(3);' readonly='readonly'/><br />
							<span class="f_alertRed10px" id="alert_txtMandor"></span>
						</td>
						<td width="70" height="29" valign="top">Nama</td>
						<td width="10" height="29" valign="top">:</td>
						<td width="100" align="left" valign="top">
							<input name="namaMandor" type="text" id="namaMandor" value="" style="width:220px; height:20px" readonly='readonly'/>
						</td>
					</tr>
					<tr>
						<td height="45" width="100" colspan="0" valign="bottom" style="border-bottom:solid #000">DATA PEMANEN</td>
						<td height="45" colspan="0" valign="bottom" style="border-bottom:solid #000">
							<input type="button" name="buttonPemanen" id="buttonPemanen" value="Pinjam Karyawan" style="width:120px; height: 30px" onClick='javascript:showListPemanen(6);'/>
						</td>
					</tr>
					<tr>
						<td width="70" height="29" valign="top">NIK</td>
						<td width="10" height="29" valign="top">:</td>
						<td width="100" align="left" valign="top">
							<input name="nikPemanen" type="text" id="nikPemanen" value="" style="width:220px; height:20px" onClick='javascript:showListPemanen(5);' readonly='readonly'/><br />
							<span class="f_alertRed10px" id="alert_txtPemanen"></span>
						</td>
						<td width="70" height="29" valign="top">Nama</td>
						<td width="10" height="29" valign="top">:</td>
						<td width="100" align="left" valign="top">
							<input name="namaPemanen" type="text" id="namaPemanen" value="" style="width:220px; height:20px" readonly='readonly'/>
						</td>
					</tr>
					<tr>
						<th align="center">
							<?php
								if(isset($_GET['f'])) {
									$err = $_GET['f'];
									if($err!=null) {
							?>
							<script type="text/javascript">
								alert("Data Inserted");
							</script>
							<?php
									}
								}
							?>
						</th>
					</tr>
				</table>

				<div id='loading'></div>
				<div style="overflow:scroll; width:1200px" id='page_hasil'>
					<fieldset>
						<legend>HASIL PANEN</legend>
						<table id="hasil_panen" border="0" cellpadding="1" cellspacing="1" style="table-layout:fixed; overflow-x:scroll">
							<tr #04B431>
								<th class="f_alert13px">NO</th>
								<th class="f_alert13px">BLOK</th>
								<th class="f_alert13px">TPH</th>
								<th class="f_alert13px">DELIVERY</br>TICKET</th>
								<th class="f_alert13px">BUAH</br>MENTAH</th>
								<th class="f_alert13px">BUAH</br>MENGKAL</th>
								<th class="f_alert13px">BUAH</br>MASAK</th>
								<th class="f_alert13px">TERLALU</br>MASAK</th>
								<th class="f_alert13px">BUAH</br>BUSUK</th>
								<th class="f_alert13px">JANJANG</br>KOSONG</th>
								<th class="f_alert13px">BUAH</br>ABORSI</th>
								<th class="f_alert13px">JUMLAH</br>JANJANG </br>PANEN</th>
								<th class="f_alert13px">ABNORMAL</th>
								<th class="f_alert13px">TANGKAI</br>PANJANG</th>
								<th class="f_alert13px">DIMAKAN</br>HAMA</th>
								<th class="f_alert13px">ALAS</br>BRONDOLAN</th>
								<th class="f_alert13px">BRONDOLAN</br>(KG)</th>
							</tr>
							<tr>
								<td><input style="width:36px;" type='text' name='no1' id='no1' value='1' disabled="disabled"></td>
								<td>
									<select name="blok1" id="blok1" onblur='cekData();' onchange="getTPH(this);">
										<option value='0' selected="selected">--select--</option>
										<?php
											$query_blok  = "SELECT ID_BLOK, BLOK_NAME FROM T_BLOK WHERE ID_BA_AFD = '$subID_BA_Afd' order by ID_BLOK";
											$result_blok = oci_parse($con, $query_blok);
											oci_execute($result_blok, OCI_DEFAULT);
											while ($p=oci_fetch($result_blok)) {
												$id_blok = oci_result($result_blok, "ID_BLOK");
												$blok_name = oci_result($result_blok, "BLOK_NAME");
												$_SESSION['tes'] = $id_blok;
												echo "<option value=\"$id_blok\">$id_blok - $blok_name</option>\n";
											}
										?>
									</select>
								</td>
								<td>
									<select name="tph1" id="tph1">
										<option value='0' selected="selected">--select--</option>
									</select>
								</td>
								<td><input style="width:46px;" maxlength="5" type='text' name='ticket1' id='ticket1' onkeypress="return isNumber(event)" value=''></td>
								<td><input style="width:58px;" type='text' name='mentah1' id='mentah1' value='0' onblur='onChangeValue(1, this);'></td>
								<td><input style="width:58px;" type='text' name='mengkal1' id='mengkal1' value='0' onblur='onChangeValue(1, this);'></td>
								<td><input style="width:58px;" type='text' name='masak1' id='masak1' value='0' onblur='onChangeValue(1, this);'></td>
								<td><input style="width:58px;" type='text' name='toomasak1' id='toomasak1' value='0' onblur='onChangeValue(1, this);'></td>
								<td><input style="width:58px;" type='text' name='busuk1' id='busuk1' value='0' onblur='onChangeValue(1, this)'></td>
								<td><input style="width:58px;" type='text' name='jangkos1' id='jangkos1' value='0' onblur='onChangeValue(1, this)'></td>
								<td><input style="width:58px;" type='text' name='buborsi1' id='buborsi1' value='0' onblur='onChangeValue(1, this);'></td>
								<td><input style="width:58px;" type='text' name='janjang1' id='janjang1' value='0' readonly="readonly"></td>
								<td><input style="width:58px;" type='text' name='abnormal1' id='abnormal1' value='0' onblur='changeformat(this)'></td>
								<td><input style="width:58px;" type='text' name='tangkai_panjang1' id='tangkai_panjang1' value='0' onblur='changeformat(this)'></td>
								<td><input style="width:58px;" type='text' name='hama1' id='hama1' value='0' onblur='changeformat(this)'></td>
								<td><input style="width:58px;" maxlength="1" type='text' name='alas1' id='alas1' value='' onkeypress="return isDigit(event)"></td>
								<td><input style="width:58px;" type='text' name='brondolan1' id='brondolan1' value='0' onblur='changeformat(this)'></td>
							</tr>
						</table>
						<table width='100%'>
							<tr class='bg-white'>
								<input onclick='addRowToTable();' type='button' value="Add row" id = "add_row"/>
								<input onclick='removeRowFromTable();' type='button' value="Delete row"/>
								<input type='hidden' value='1' id='countRow' name='countRow' />
							</tr>
						</table>
						<table width='100%'>
							<tr align="center">
								<td>--------------------------------</td>
							</tr>
							<tr align="center">
								<td><input id='btn_simpan' name='btn_simpan' type='button' value="Simpan"/></td>
							</tr>
						</table>
					</fieldset>
				</div>

				<tr>
					<th align="center"><?php include("../include/Footer.php") ?></th>
				</tr>
			</th>
		</tr>
	</table>

<?php
} else {
	$_SESSION[err] = "tolong login dulu!".$Job_Code."<br>".$username."<br>".$Emp_Name."<br>".$subID_BA_Afd;
	header("location:../index.php");
}
?>

<script type="text/javascript" src="../js/jsformatnumber.js"></script>
<script language="JavaScript" type="text/JavaScript">
	/*function klik(ff){
		showList(ff);
	}*/

	function changeformat(obj) {
		obj.value = formatCurrency(obj.value);
	}

	function onChangeValue(iter, obj) {
		var jmlh;
		obj.value = formatCurrency(obj.value);
		document.getElementById('mentah'+iter).value = formatCurrency(document.getElementById('mentah'+iter).value);
		document.getElementById('mengkal'+iter).value = formatCurrency(document.getElementById('mengkal'+iter).value);
		document.getElementById('masak'+iter).value = formatCurrency(document.getElementById('masak'+iter).value);
		document.getElementById('toomasak'+iter).value = formatCurrency(document.getElementById('toomasak'+iter).value);
		document.getElementById('busuk'+iter).value = formatCurrency(document.getElementById('busuk'+iter).value);
		document.getElementById('jangkos'+iter).value = formatCurrency(document.getElementById('jangkos'+iter).value);
		document.getElementById('buborsi'+iter).value = formatCurrency(document.getElementById('buborsi'+iter).value);
		jmlh = eval(document.getElementById('mentah'+iter).value) + 
				eval(document.getElementById('mengkal'+iter).value) + 
				eval(document.getElementById('masak'+iter).value) + 
				eval(document.getElementById('toomasak'+iter).value) + 
				eval(document.getElementById('busuk'+iter).value) + 
				eval(document.getElementById('jangkos'+iter).value) + 
				eval(document.getElementById('buborsi'+iter).value);
		document.getElementById('janjang'+iter).value = jmlh;
	}

	//LoV UTK DAFTAR DOKUMEN
	function showList(row) {
		var afdeling = document.getElementById('afdeling').value;
		var baris = row;
		if (afdeling != "0" || row == "2")
			sList = window.open("popupDoc.php?afdeling="+afdeling+"&baris="+row+"", "Daftar_Dokumen", "width=800,height=500");
		else if (afdeling=="0")
			alert ("Pilih afdeling terlebih dahulu");
		else
			sList = window.open("popupDoc.php?afdeling="+afdeling+"&baris="+row+"", "Daftar_Dokumen", "width=800,height=500");
			//return true;
	}

	function showListMandor(row) {
		var afdeling = document.getElementById('afdeling').value;
		var baris = row;
		if (afdeling != "0" || row == "4")
			sList = window.open("popupMandor.php?afdeling="+afdeling+"&baris="+row+"", "Daftar_Dokumen", "width=800,height=500");
		else if (afdeling=="0")
			alert ("Pilih afdeling terlebih dahulu");
		else
			sList = window.open("popupMandor.php?afdeling="+afdeling+"&baris="+row+"", "Daftar_Dokumen", "width=800,height=500");
	}

	function showListPemanen(row) {
		var afdeling = document.getElementById('afdeling').value;
		var baris = row;
		if (afdeling != "0" || row == "6")
			sList = window.open("popupPemanen.php?afdeling="+afdeling+"&baris="+row+"", "Daftar_Dokumen", "width=800,height=500");
		else if (afdeling=="0")
			alert ("Pilih afdeling terlebih dahulu");
		else
			sList = window.open("popupPemanen.php?afdeling="+afdeling+"&baris="+row+"", "Daftar_Dokumen", "width=800,height=500");
	}

	function remLink() {
		if (window.sList && window.sList.open && !window.sList.closed)
			window.sList.opener = null;
	}

	//untuk menampilkan data blok
	$("#add_row").click(function () {
		var afdeling = $("#afdeling").val();
		var i = document.getElementById('countRow').value; //added by NB 04.07.2014
		$.ajax({
			url: "ambilkota.php",
			data: "afdeling="+afdeling,
			cache: false,
			success: function(msg) {
				//jika data sukses diambil dari server kita tampilkan
				//for (j = 1; j <= i; j++){
					$("#blok" + i).html(msg);
				//}
			}
		});
	});

	// TAMBAH BARIS
	function addRowToTable() {
		var tbl = document.getElementById('hasil_panen');
		var lastRow = tbl.rows.length;
		document.getElementById('countRow').value = (document.getElementById('countRow').value*1) + 1;
		var iteration = lastRow;
		var row = tbl.insertRow(lastRow);
		var afd = document.getElementById('afdeling').value;

		// KATEGORI DOKUMEN
		var cell0 = row.insertCell(0);
		var el = document.createElement('input');
		el.type = 'text';
		el.name = 'no' + iteration;
		el.id = 'no' + iteration;
		el.value = iteration;
		el.disabled = 'disabled';
		el.style.width='36px';
		el.maxLength = '3';
		cell0.appendChild(el);

		// KATEGORI DOKUMEN
		var cell1 = row.insertCell(1);
		var sel = document.createElement('select');
		sel.name = 'blok' + iteration;
		sel.id = 'blok' + iteration;
		sel.options[0] = new Option('--select--', '0');
		sel.setAttribute("onchange", "getTPH(this);");
		sel.onblur = function() {
			var nik_pemanen = $("#nikPemanen").val();
			var tgl = $("#datepicker").val();
			var v_afd = document.getElementById('afdeling').value;
			
			var v_blok = document.getElementById('blok' + iteration).value;
			var v_ba = document.getElementById('ID_BA2').value;
			var v_tph = document.getElementById('tph' + iteration).value;
			var v_ticket = document.getElementById('ticket' + iteration).value;
		
			var xhr;
			if (window.XMLHttpRequest) { // Mozilla, Safari, ...
				xhr = new XMLHttpRequest();
			} else if (window.ActiveXObject) { // IE 8 and older
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
			var data = "nik=" + nik_pemanen + "&var_tgl=" + tgl + "&var_ba=" + v_ba + "&var_blok=" + v_blok + "&var_afd=" + v_afd;

			xhr.open("POST", "cekValidasi.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send(data);
			xhr.onreadystatechange = display_data;
			
			function display_data() {
				if (xhr.readyState == 4) {
					if (xhr.status == 200) {
						if(xhr.responseText == "kosong") {
						} else {
							confirm("Data Pemanen dan Blok pernah diinput, apakah anda akan menambahkan data hasil panen?");
							//alert(xhr.responseText);
						}
						//document.getElementById("suggestion").innerHTML = xhr.responseText;
					} else {
						alert('There was a problem with the request.');
					}
				}
			}
		}
		cell1.appendChild(sel);

		// INFORMASI PERMINTAAN
		var cell2 = row.insertCell(2);
		var el = document.createElement('select');
		el.name = 'tph' + iteration;
		el.id = 'tph' + iteration;
		el.options[0] = new Option('--select--', '0');
		cell2.appendChild(el);
	
		// INFORMASI PERMINTAAN
		var cell3 = row.insertCell(3);
		var el = document.createElement('input');
		el.type = 'text';
		el.name = 'ticket' + iteration;
		el.id = 'ticket' + iteration;
		el.value = '';
		el.style.width='46px';
		el.maxLength = '5';
		el.onkeypress = isNumber;
		cell3.appendChild(el);
	
		// INFORMASI PERMINTAAN
		var cell4 = row.insertCell(4);
		var el = document.createElement('input');
		el.type = 'text';
		el.name = 'mentah' + iteration;
		el.id = 'mentah' + iteration;
		el.value = '0';
		el.style.width='58px';
		el.onblur = function() {
			var jmlh;
			document.getElementById('mentah'+iteration).value = formatCurrency(document.getElementById('mentah'+iteration).value);
			document.getElementById('mentah'+iteration).value = formatCurrency(document.getElementById('mentah'+iteration).value);
			document.getElementById('mengkal'+iteration).value = formatCurrency(document.getElementById('mengkal'+iteration).value);
			document.getElementById('masak'+iteration).value = formatCurrency(document.getElementById('masak'+iteration).value);
			document.getElementById('toomasak'+iteration).value = formatCurrency(document.getElementById('toomasak'+iteration).value);
			jmlh = eval(document.getElementById('mentah'+iteration).value) + 
					eval(document.getElementById('mengkal'+iteration).value) + 
					eval(document.getElementById('masak'+iteration).value) + 
					eval(document.getElementById('toomasak'+iteration).value);
			document.getElementById('janjang'+iteration).value = jmlh;
		}
		cell4.appendChild(el);

		// INFORMASI PERMINTAAN
		var cell5 = row.insertCell(5);
		var el = document.createElement('input');
		el.type = 'text';
		el.name = 'mengkal' + iteration;
		el.id = 'mengkal' + iteration;
		el.value = '0';
		el.style.width='58px';
		el.onblur = function() {
			var jmlh;
			document.getElementById('mengkal'+iteration).value = formatCurrency(document.getElementById('mengkal'+iteration).value);
			document.getElementById('mentah'+iteration).value = formatCurrency(document.getElementById('mentah'+iteration).value);
			document.getElementById('mengkal'+iteration).value = formatCurrency(document.getElementById('mengkal'+iteration).value);
			document.getElementById('masak'+iteration).value = formatCurrency(document.getElementById('masak'+iteration).value);
			document.getElementById('toomasak'+iteration).value = formatCurrency(document.getElementById('toomasak'+iteration).value);
			jmlh = eval(document.getElementById('mentah'+iteration).value) + 
					eval(document.getElementById('mengkal'+iteration).value) + 
					eval(document.getElementById('masak'+iteration).value) + 
					eval(document.getElementById('toomasak'+iteration).value);
			document.getElementById('janjang'+iteration).value = jmlh;
		}
		cell5.appendChild(el);
	
		// INFORMASI PERMINTAAN
		var cell6 = row.insertCell(6);
		var el = document.createElement('input');
		el.type = 'text';
		el.name = 'masak' + iteration;
		el.id = 'masak' + iteration;
		el.value = '0';
		el.style.width='58px';
		el.onblur = function() {
			var jmlh;
			document.getElementById('masak'+iteration).value = formatCurrency(document.getElementById('masak'+iteration).value);
			document.getElementById('mentah'+iteration).value = formatCurrency(document.getElementById('mentah'+iteration).value);
			document.getElementById('mengkal'+iteration).value = formatCurrency(document.getElementById('mengkal'+iteration).value);
			document.getElementById('masak'+iteration).value = formatCurrency(document.getElementById('masak'+iteration).value);
			document.getElementById('toomasak'+iteration).value = formatCurrency(document.getElementById('toomasak'+iteration).value);
			jmlh = eval(document.getElementById('mentah'+iteration).value) + 
					eval(document.getElementById('mengkal'+iteration).value) + 
					eval(document.getElementById('masak'+iteration).value) + 
					eval(document.getElementById('toomasak'+iteration).value);
			document.getElementById('janjang'+iteration).value = jmlh;
		}
		cell6.appendChild(el);
	
		// INFORMASI PERMINTAAN
		var cell7 = row.insertCell(7);
		var el = document.createElement('input');
		el.type = 'text';
		el.name = 'toomasak' + iteration;
		el.id = 'toomasak' + iteration;
		el.value = '0';
		el.style.width='58px';
		el.onblur = function() {
			var jmlh;
			document.getElementById('mentah'+iteration).value = formatCurrency(document.getElementById('mentah'+iteration).value);
			document.getElementById('mengkal'+iteration).value = formatCurrency(document.getElementById('mengkal'+iteration).value);
			document.getElementById('masak'+iteration).value = formatCurrency(document.getElementById('masak'+iteration).value);
			document.getElementById('toomasak'+iteration).value = formatCurrency(document.getElementById('toomasak'+iteration).value);
			jmlh = eval(document.getElementById('mentah'+iteration).value) + 
					eval(document.getElementById('mengkal'+iteration).value) + 
					eval(document.getElementById('masak'+iteration).value) + 
					eval(document.getElementById('toomasak'+iteration).value);
			document.getElementById('janjang'+iteration).value = jmlh;
		}
		cell7.appendChild(el);

		// INFORMASI PERMINTAAN
		var cell8 = row.insertCell(8);
		var el = document.createElement('input');
		el.type = 'text';
		el.name = 'busuk' + iteration;
		el.id = 'busuk' + iteration;
		el.value = '0';
		el.style.width='58px';
		el.onblur = function() {
			var jmlh;
			document.getElementById('busuk'+iteration).value = formatCurrency(document.getElementById('busuk'+iteration).value);
			document.getElementById('mentah'+iteration).value = formatCurrency(document.getElementById('mentah'+iteration).value);
			document.getElementById('mengkal'+iteration).value = formatCurrency(document.getElementById('mengkal'+iteration).value);
			document.getElementById('masak'+iteration).value = formatCurrency(document.getElementById('masak'+iteration).value);
			document.getElementById('toomasak'+iteration).value = formatCurrency(document.getElementById('toomasak'+iteration).value);
			jmlh = eval(document.getElementById('mentah'+iteration).value) + 
					eval(document.getElementById('mengkal'+iteration).value) + 
					eval(document.getElementById('masak'+iteration).value) + 
					eval(document.getElementById('toomasak'+iteration).value) +
					eval(document.getElementById('busuk'+iteration).value);
			document.getElementById('janjang'+iteration).value = jmlh;
		}
		cell8.appendChild(el);
	
		// INFORMASI PERMINTAAN
		var cell9 = row.insertCell(9);
		var el = document.createElement('input');
		el.type = 'text';
		el.name = 'jangkos' + iteration;
		el.id = 'jangkos' + iteration;
		el.value = '0';
		el.style.width='58px';
		el.onblur = function() {
			var jmlh;
			document.getElementById('busuk'+iteration).value = formatCurrency(document.getElementById('busuk'+iteration).value);
			document.getElementById('mentah'+iteration).value = formatCurrency(document.getElementById('mentah'+iteration).value);
			document.getElementById('mengkal'+iteration).value = formatCurrency(document.getElementById('mengkal'+iteration).value);
			document.getElementById('masak'+iteration).value = formatCurrency(document.getElementById('masak'+iteration).value);
			document.getElementById('toomasak'+iteration).value = formatCurrency(document.getElementById('toomasak'+iteration).value);
			document.getElementById('jangkos'+iteration).value = formatCurrency(document.getElementById('jangkos'+iteration).value);
			jmlh = eval(document.getElementById('mentah'+iteration).value) + 
					eval(document.getElementById('mengkal'+iteration).value) + 
					eval(document.getElementById('masak'+iteration).value) + 
					eval(document.getElementById('toomasak'+iteration).value) + 
					eval(document.getElementById('busuk'+iteration).value) + 
					eval(document.getElementById('jangkos'+iteration).value);
			document.getElementById('janjang'+iteration).value = jmlh;
		}
		cell9.appendChild(el);

		// INFORMASI PERMINTAAN
		var cell10 = row.insertCell(10);
		var el = document.createElement('input');
		el.type = 'text';
		el.name = 'buborsi' + iteration;
		el.id = 'buborsi' + iteration;
		el.value = '0';
		el.style.width='58px';
		el.onblur = function() {
			var jmlh;
			document.getElementById('busuk'+iteration).value = formatCurrency(document.getElementById('busuk'+iteration).value);
			document.getElementById('mentah'+iteration).value = formatCurrency(document.getElementById('mentah'+iteration).value);
			document.getElementById('mengkal'+iteration).value = formatCurrency(document.getElementById('mengkal'+iteration).value);
			document.getElementById('masak'+iteration).value = formatCurrency(document.getElementById('masak'+iteration).value);
			document.getElementById('toomasak'+iteration).value = formatCurrency(document.getElementById('toomasak'+iteration).value);
			document.getElementById('jangkos'+iteration).value = formatCurrency(document.getElementById('jangkos'+iteration).value);
			document.getElementById('buborsi'+iteration).value = formatCurrency(document.getElementById('buborsi'+iteration).value);
			jmlh = eval(document.getElementById('mentah'+iteration).value) + 
					eval(document.getElementById('mengkal'+iteration).value) + 
					eval(document.getElementById('masak'+iteration).value) + 
					eval(document.getElementById('toomasak'+iteration).value) + 
					eval(document.getElementById('busuk'+iteration).value) + 
					eval(document.getElementById('jangkos'+iteration).value) + 
					eval(document.getElementById('buborsi'+iteration).value);
			document.getElementById('janjang'+iteration).value = jmlh;
		}
		cell10.appendChild(el);
	
		// INFORMASI PERMINTAAN
		var cell11 = row.insertCell(11);
		var el = document.createElement('input');
		el.type = 'text';
		el.name = 'janjang' + iteration;
		el.id = 'janjang' + iteration;
		el.value = '0';
		el.style.width='58px';
		el.readOnly = 'readOnly';
		cell11.appendChild(el);
	
		// INFORMASI PERMINTAAN
		var cell12 = row.insertCell(12);
		var el = document.createElement('input');
		el.type = 'text';
		el.name = 'abnormal' + iteration;
		el.id = 'abnormal' + iteration;
		el.value = '0';
		el.style.width='58px';
		el.onblur = function() {
			document.getElementById('abnormal'+iteration).value = formatCurrency(document.getElementById('abnormal'+iteration).value);
		}
		cell12.appendChild(el);

		// INFORMASI PERMINTAAN
		var cell13 = row.insertCell(13);
		var el = document.createElement('input');
		el.type = 'text';
		el.name = 'tangkai_panjang' + iteration;
		el.id = 'tangkai_panjang' + iteration;
		el.value = '0';
		el.style.width='58px';
		el.onblur = function() {
			document.getElementById('tangkai_panjang'+iteration).value = formatCurrency(document.getElementById('tangkai_panjang'+iteration).value);
		}
		cell13.appendChild(el);
	
		// INFORMASI PERMINTAAN
		var cell14 = row.insertCell(14);
		var el = document.createElement('input');
		el.type = 'text';
		el.name = 'hama' + iteration;
		el.id = 'hama' + iteration;
		el.value = '0';
		el.style.width='58px';
		el.onblur = function() {
			document.getElementById('hama'+iteration).value = formatCurrency(document.getElementById('hama'+iteration).value);
		}
		cell14.appendChild(el);
	
		// INFORMASI PERMINTAAN
		var cell15 = row.insertCell(15);
		var el = document.createElement('input');
		el.type = 'text';
		el.name = 'alas' + iteration;
		el.id = 'alas' + iteration;
		el.value = '';
		el.style.width='58px';
		el.onkeypress = isDigit;
		cell15.appendChild(el);
	
		// INFORMASI PERMINTAAN
		var cell16 = row.insertCell(16);
		var el = document.createElement('input');
		el.type = 'text';
		el.name = 'brondolan' + iteration;
		el.id = 'brondolan' + iteration;
		el.value = '0';
		el.style.width='58px';
		el.onblur = function() {
			document.getElementById('brondolan'+iteration).value = formatCurrency(document.getElementById('brondolan'+iteration).value);
		}
		cell16.appendChild(el);
	}

	// HAPUS BARIS
	function removeRowFromTable() {
		var tbl = document.getElementById('hasil_panen');
		var lastRow = tbl.rows.length;
		if(document.getElementById('countRow').value > 1)
			document.getElementById('countRow').value -= 1;
		if (lastRow > 2) 
			tbl.deleteRow(lastRow - 1);
	}
</script>
</form>
