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

if($username == "") {
	$_SESSION[err] = "tolong login dulu!";
	header("location:login.php");
} else {
	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();

	if(isset($_POST["editNO_BCC"])) { $_SESSION["editNO_BCC"] = $_POST["editNO_BCC"]; }
	if(isset($_SESSION["editNO_BCC"])) { $NO_BCC = $_SESSION["editNO_BCC"]; }
	//Added by Ardo 03-11-2016 : Issue Solving kriteria dobel BCC sama input 2 hp
	if(isset($_POST["editRencanaPanen"])) { $_SESSION["editRencanaPanen"] = $_POST["editRencanaPanen"]; }
	if(isset($_SESSION["editRencanaPanen"])) { $editRencanaPanen = $_SESSION["editRencanaPanen"]; }

	$sql_t_BCC = "
		select 
			thrp.tanggal_rencana tanggal, thrp.id_rencana id_rencana,
			tba.id_cc AS CC,
			tba.id_ba AS BA,
			tba.PROFILE_NAME AS PROFILE_NAME,
			ta.id_afd AS AFD,
			tb.id_blok as ID_BLOK,
			tb.blok_name as BLOK_NAME,
			thp.no_bcc,
			thp.no_rekap_bcc,
			thp.no_tph, 
			thp.latitude,
			thp.longitude,
			thp.status_lokasi,
			thrp.nik_pemanen,
			f_get_empname (thrp.nik_pemanen) nama_pemanen,
			thrp.nik_mandor,
			tdrp.luasan_panen as LUASAN_PANEN,
			f_get_empname (thrp.nik_mandor) nama_mandor,
			tkp.nama_kualitas, thpk.ID_BCC_KUALITAS as ID_BCC_KUALITAS, thpk.qty
		from t_header_rencana_panen thrp
		inner join t_detail_rencana_panen tdrp
			on thrp.id_rencana = tdrp.id_rencana
		inner join t_hasil_panen thp
			on tdrp.id_rencana = thp.id_rencana and tdrp.no_rekap_bcc = thp.no_rekap_bcc
		inner join t_blok tb
			on tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
		inner join t_afdeling ta
			on tb.id_ba_afd = ta.id_ba_afd
		inner join t_bussinessarea tba
			on ta.id_ba = tba.id_ba
		inner join t_hasilpanen_kualtas thpk
			on thp.no_bcc = thpk.id_bcc and tdrp.id_rencana=thpk.id_rencana
		inner join  t_kualitas_panen tkp
			on thpk.id_kualitas=tkp.id_kualitas
		where     thp.no_bcc = '$NO_BCC'
			AND tba.id_ba = '$subID_BA_Afd' -- added by NBU 02.11.2015
			AND thp.id_rencana = '$editRencanaPanen' -- added by Ardo 03.11.2016
	";
	//echo $sql_t_BCC;die();
	$result_t_BCC = oci_parse($con, $sql_t_BCC);
	oci_execute($result_t_BCC, OCI_DEFAULT);
	while(oci_fetch($result_t_BCC)) {
		$ID_RENCANA 		= oci_result($result_t_BCC, "ID_RENCANA");
		$TANGGAL_RENCANA 		= oci_result($result_t_BCC, "TANGGAL");
		$aNO_BCC 				= oci_result($result_t_BCC, "NO_BCC");
		$PROFILE_NAME				= oci_result($result_t_BCC, "PROFILE_NAME");
		$CC				= oci_result($result_t_BCC, "CC");
		$BA 				= oci_result($result_t_BCC, "BA");
		$AFD 				= oci_result($result_t_BCC, "AFD");
		$ID_BLOK 				= oci_result($result_t_BCC, "ID_BLOK");
		$BLOK_NAME 				= oci_result($result_t_BCC, "BLOK_NAME");
		$NAMA_PEMANEN 		= oci_result($result_t_BCC, "NAMA_PEMANEN");
		$NAMA_MANDOR 			= oci_result($result_t_BCC, "NAMA_MANDOR");
		$NIK_PEMANEN 		= oci_result($result_t_BCC, "NIK_PEMANEN");
		$NIK_MANDOR 			= oci_result($result_t_BCC, "NIK_MANDOR");
		$NO_REKAP 			= oci_result($result_t_BCC, "NO_REKAP_BCC");
		$LUASAN_PANEN 			= oci_result($result_t_BCC, "LUASAN_PANEN");
		$BLOK_TPH = oci_result($result_t_BCC, "NO_TPH");
		$LAT_TPH = oci_result($result_t_BCC, "LATITUDE");
		$LONG_TPH = oci_result($result_t_BCC, "LONGITUDE");
		$STATUS_LOKASI = oci_result($result_t_BCC, "STATUS_LOKASI");
	}

	$sql_m_LatLong = "
		SELECT latitude, longitude FROM TAP_DW.TM_TPH@DEVDW_LINK WHERE WERKS = '{$BA}' AND AFD_CODE = '{$AFD}' AND BLOCK_CODE = '{$ID_BLOK}' AND NO_TPH = '{$BLOK_TPH}'
	";
	$result_m_LatLong = oci_parse($con, $sql_m_LatLong);
	oci_execute($result_m_LatLong, OCI_DEFAULT);
	while(oci_fetch($result_m_LatLong)) {
		$LAT_M_TPH = oci_result($result_m_LatLong, "LATITUDE");
		$LONG_M_TPH = oci_result($result_m_LatLong, "LONGITUDE");
	}
		
	$sql_t_BCC_table = "
		SELECT ID_BCC_KUALITAS,
			tkp.id_kualitas,
			tkp.nama_kualitas,
			thpk.ID_BCC_KUALITAS AS ID_BCC_KUALITAS,
			thpk.qty,
			tkp.param
		FROM t_header_rencana_panen thrp
		INNER JOIN t_detail_rencana_panen tdrp
			ON thrp.id_rencana = tdrp.id_rencana
		INNER JOIN t_hasil_panen thp
			ON tdrp.id_rencana = thp.id_rencana AND tdrp.no_rekap_bcc = thp.no_rekap_bcc
		INNER JOIN t_blok tb
			ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
		INNER JOIN t_afdeling ta
			ON tb.id_ba_afd = ta.id_ba_afd
		INNER JOIN t_bussinessarea tba
			ON ta.id_ba = tba.id_ba
		INNER JOIN t_hasilpanen_kualtas thpk
			ON thp.no_bcc = thpk.id_bcc AND tdrp.id_rencana = thpk.id_rencana
		INNER JOIN t_kualitas_panen tkp
			ON thpk.id_kualitas = tkp.id_kualitas
		WHERE thp.no_bcc = '$NO_BCC' AND tba.id_ba = '$subID_BA_Afd'
			AND thp.id_rencana = '$editRencanaPanen' -- added by Ardo 03.11.2016
			ORDER BY Group_kualitas, NAMA_KUALITAS, thpk.ID_KUALITAS ASC";

		/*$sql_t_BCC_table = "SELECT H.ID_BCC_KUALITAS ,K.ID_KUALITAS, K.NAMA_KUALITAS, H.QTY  FROM  t_kualitas_panen K,
		t_hasilpanen_kualtas H  
		WHERE  H.ID_BCC(+)='$NO_BCC'
		AND K.ID_KUALITAS = H.ID_KUALITAS(+)
		ORDER BY Group_kualitas, K.NAMA_KUALITAS, ID_KUALITAS ASC";*/ //remarked by NBU 02.11.2015
		//echo $sql_t_BCC_table;die();
		//echo $sql_t_BCC_table;die();	

	$result_t_BCC_table = oci_parse($con, $sql_t_BCC_table);
	oci_execute($result_t_BCC_table, OCI_DEFAULT);
	while(oci_fetch($result_t_BCC_table)) {
		$NAMA_KUALITAS[] 	= oci_result($result_t_BCC_table, "NAMA_KUALITAS");
		$QTY[] 				= oci_result($result_t_BCC_table, "QTY");
		$ID_BCC_KUALITAS[] 	= oci_result($result_t_BCC_table, "ID_BCC_KUALITAS");
		$ID_Kualitas[]		= oci_result($result_t_BCC_table, "ID_KUALITAS");
		$PARAM[]			= oci_result($result_t_BCC_table, "PARAM");
	}
	$roweffec_BCC = oci_num_rows($result_t_BCC_table);
		
	$sql_t_CC = "select COMP_NAME from t_companycode WHERE ID_CC = '$CC'";
		
	$result_t_CC = oci_parse($con, $sql_t_CC);
	oci_execute($result_t_CC, OCI_DEFAULT);
	while(oci_fetch($result_t_CC)) {
		$COMP_NAME			= oci_result($result_t_CC, "COMP_NAME");
	}
	$roweffec_CC = oci_num_rows($result_t_CC);

	if(isset($_POST['BA'])) {
		$_SESSION['BA'] = $_POST['BA'];
	}
		
	if(isset($_SESSION['BA'])) {
		$ses_BA = $_SESSION['BA'];
		if($ses_BA  == "") {
			$sql_BA = "select * from t_BussinessArea tba
				inner join t_CompanyCode tcc on tba.id_cc = tcc.id_cc
				inner join t_alternate_ba_group tabg on tba.id_ba = tabg.id_ba
				where tabg.id_group_ba in (select id_group_ba from t_bussinessarea where id_ba = $subID_BA_Afd)
				 order by tba.id_ba";
			$sql_t_Emp_All  = "SELECT * from t_employee WHERE JOB_CODE = 'PEMANEN'";
			$optionBA = "";
		} else {
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
	} else {
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

	if(isset($_POST['NIK_Pemanen'])){
		$_SESSION['NIK_Pemanen'] = $_POST['NIK_Pemanen'];
		//echo "Pemanenpost";
	}
	//echo "here".$_POST['NIK_Pemanen'];
	if(isset($_SESSION['NIK_Pemanen'])){
		$ses_NIK_Pemanen = $_SESSION['NIK_Pemanen'];
		//echo "Pemanen". $_SESSION['NIK_Pemanen'];
		if($ses_NIK_Pemanen  == ""){
			$sql_t_Emp  = "select te.nik, te.emp_name, ta.id_ba, ta.id_afd
				from t_employee te inner join t_afdeling ta on te.id_ba_afd = ta.id_ba_afd WHERE NIK = '$NIK_PEMANEN'";	
			//echo "seskosong";
		} else {
			$sql_t_Emp  = "select te.nik, te.emp_name, ta.id_ba, ta.id_afd
				from t_employee te inner join t_afdeling ta on te.id_ba_afd = ta.id_ba_afd WHERE NIK = '$ses_NIK_Pemanen'";	
				//echo "sesada".$sql_t_Emp;
		}
	} else {
		$sql_t_Emp  = "select te.nik, te.emp_name, ta.id_ba, ta.id_afd
			from t_employee te inner join t_afdeling ta on te.id_ba_afd = ta.id_ba_afd WHERE NIK = '$NIK_PEMANEN'";	
			//echo "sesnotsend".$sql_t_Emp;
	}
	$result_t_Emp = oci_parse($con, $sql_t_Emp);
	oci_execute($result_t_Emp, OCI_DEFAULT);
		
	while (oci_fetch($result_t_Emp)) {	
		$NIK_Pemanen = oci_result($result_t_Emp, "NIK");
		$Nama_Pemanen = oci_result($result_t_Emp, "EMP_NAME");
		//$ID_BA_Pemanen = oci_result($result_t_Emp, "ID_BA");
		$Afd_Pemanen = oci_result($result_t_Emp, "ID_AFD");
	}
	$roweffec_Emp = oci_num_rows($result_t_Emp);
}	
?>

<?php
	function get_meters_between_points($latitude1, $longitude1, $latitude2, $longitude2) {
		if (($latitude1 == $latitude2) && ($longitude1 == $longitude2)) { return 0; }
		$p1 = deg2rad($latitude1);
		$p2 = deg2rad($latitude2);
		$dp = deg2rad($latitude2 - $latitude1);
		$dl = deg2rad($longitude2 - $longitude1);
		$a = (sin($dp/2) * sin($dp/2)) + (cos($p1) * cos($p2) * sin($dl/2) * sin($dl/2));
		$c = 2 * atan2(sqrt($a),sqrt(1-$a));
		$r = 6371008;
		$d = $r * $c;
		$d = number_format(round($d), 0, ",", ".");
		return $d;
	}
	$jarak = get_meters_between_points($LAT_TPH, $LONG_TPH, $LAT_M_TPH, $LONG_M_TPH);
?>

<script type="text/javascript">
	function pilihTerdekat(a) {
		var str = a;
		var res = str.split("-");

		$('#lokasisubmit').remove();

		$('#AFDlabel').val(res[1]);
		$('#selectblok').val(res[2]);
		$('#selecttph').val(res[3]);
		$('#jarakGEO').val(res[4]);
	}

	function formSubmit(x) {
		document.getElementById('NIK_Pemanen').value = x;
		document.getElementById("FormPemanen").submit();
	}

	function formSubmitvalue() {
		//Validasi Added by Ardo, 08-01-2016
		var roweffec_BCC = document.getElementById('roweffec_BCC').value;
		for(var x=0;x<roweffec_BCC;x++) {
		var nama_kualitas = document.getElementById('NAMA_KUALITAS'+x).value;
		var id_kualitas = document.getElementById('ID_Kualitas'+x).value;
		var param = document.getElementById('PARAM'+x).value;
		var qty = parseInt(document.getElementById('NewQty'+x).value);
		
		if(param!='NULL') {
			var isi_param = param.split(',');
			var validasi = 0;
			//var keterangan_alert = nama_kualitas+" tidak boleh melebihi ";
			for(var m=0;m<isi_param.length;m++) {
				for(var k=0;k<roweffec_BCC;k++) {
					var idk = document.getElementById('ID_Kualitas'+k).value;
					
					if(isi_param[m]==idk) {
						var newqty = parseInt(document.getElementById('NewQty'+k).value);
						//var keterangan_kualitas = document.getElementById('NAMA_KUALITAS'+k).value;
						validasi = validasi + newqty;
						//keterangan_alert = keterangan_alert+" "+keterangan_kualitas+",";
						
					}
					
				}
			}
			//alert(qty+" - "+validasi+" - "+keterangan_alert);
			if(qty>validasi){
				alert('Jumlah '+nama_kualitas+' yang diinput terlalu besar');
				return false;
			}
		}
	}
	
}

function showListPemanen() {
	var afd = document.getElementById('AFDlabel').value;
	var ba = document.getElementById('ID_BAlabel').value;
	var tgl = document.getElementById('datepicker1').value;
	
	var afdeling = ba+afd;
	//var baris = row;
	if (afdeling != "0" && tgl != "")
		sList = window.open("popupPemanen.php?afdeling="+afdeling+"&tgl_panen="+tgl+"", "Daftar_Dokumen", "width=800,height=500");
	else if (tgl == "")
		alert("Pilih tanggal panen terlebih dahulu");
	else
		sList = window.open("popupPemanen.php?afdeling="+afdeling+"&tgl_panen="+tgl+"", "Daftar_Dokumen", "width=800,height=500");
}

function showListMandor() {
	var afd = document.getElementById('AFDlabel').value;
	var ba = document.getElementById('ID_BAlabel').value;
	var tgl = document.getElementById('datepicker1').value;
	
	var afdeling = ba+afd;
	if (afdeling != "0" && tgl != "")
		sList = window.open("popupMandor.php?afdeling="+afdeling+"&tgl_panen="+tgl+"", "Daftar_Dokumen", "width=800,height=500");
	else if (tgl == "")
		alert("Pilih tanggal panen terlebih dahulu");
	else
		sList = window.open("popupMandor.php?afdeling="+afdeling+"&tgl_panen="+tgl+"", "Daftar_Dokumen", "width=800,height=500");
}

function cekvalid(id) {
	
	var valcek = document.getElementById('NewQty'+id).value;
	var valid = document.getElementById('ID_Kualitas'+id).value;
	//untuk alas brondolan (id = 10) hanya bisa diinput 0 atau 1 
	if (valid == 10) {
		if (Number(valcek) > 1) {
			alert ("Input hanya boleh angka 0 atau 1 !");
			document.getElementById('NewQty'+id).value = 0;
			document.getElementById('NewQty'+id).focus();
		}
	}
}

function cek_konfirmasi_delete() {
	if($('#nomor_ba').val()=='') {
		alert('Nomor BA belum diisi!');
		return false;
	} else if($('#tanggal_ba').val()=='') {
		alert('Tanggal BA belum diisi!');
		return false;
	} else if($('#alasan').val()=='') {
		alert('Alasan belum diisi!');
		return false;
	}
}

</script>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"> </script>
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

	<link href="../jquery-ui-1.10.4.custom/css/ui-lightness/jquery-ui-1.10.4.custom.css" rel="stylesheet">
	<script src="../jquery-ui-1.10.4.custom/js/jquery-1.10.2.js"></script>
	<script src="../jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.js"></script>

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

<script>
	<?php 
	// Modified by Ardo on 16/02/2016 : Penambahan validasi max penarikan report adalah hari ini atau tanggal NAB.
	$date = date("d M Y", time());
	$datenow = date("d M Y", time());
	$get_tgl_nab = oci_parse($con,"SELECT TGL_NAB FROM T_NAB TNAB JOIN T_HASIL_PANEN THP ON THP.ID_NAB_TGL = TNAB.ID_NAB_TGL WHERE THP.NO_BCC = '".$aNO_BCC."'");
	oci_execute($get_tgl_nab, OCI_DEFAULT);
	while ($p=oci_fetch($get_tgl_nab)) {
		$tgl_nab = date("d M Y",strtotime(oci_result($get_tgl_nab, "TGL_NAB")));
		
		if($date>=$tgl_nab){
			$date = $tgl_nab;
		}
	}
	
	?>
	var date1 = '<?php echo $date;?>';
	var datenow = '<?php echo $datenow;?>';
	date1 = new Date(date1);
	datenow = new Date(datenow);
	
	var date_ba = '<?php echo date('d M Y', strtotime($TANGGAL_RENCANA)) ?>';
	date_ba = new Date(date_ba);
	$(function() {
		$('#datepicker1').datepicker({
			changeMonth: true,
			changeYear: true,	  
			maxDate: date1 
		});
		
		$('#tanggal_ba').datepicker({
			changeMonth: true,
			changeYear: true,
			minDate: date_ba,
			maxDate: datenow
		});
		
		$("#tanggal_ba").keydown(function (e) {
			var charCode = (e.which) ? e.which : e.keyCode;
			e.preventDefault();
			return true;
		});
	});
	$(document).ready(function(){
		$('#AFDlabel').change(function(){
			//alert($('#datepicker').val());
			$.ajax ({
				type: 'get',
				url: "doAjaxGetData.php",
				dataType: "json",
				data : "request=getBlokData&BA="+$('#ID_BAlabel').val()+"&AFD="+$('#AFDlabel').val()+"&TANGGAL_RENCANA="+$('#datepicker1').val(),
				success:function(data){
					var value = '';
					$.each(data, function(i,n){
						value = value + n['value'];
						//alert(n['value']);
					});
					if(value==''){
						alert('Tidak ada data blok pada Afdeling tersebut');
					} else {
						$('#selectblok').html(value);
					}
				}
			});	
		});
		$('#selectblok').change(function() {
			var ba = $('#ID_BAlabel').val();
			var afd = $('#AFDlabel').val();
			var blok = $('#selectblok').val();
			var exist_tph = $('#exist_tph').val();

			if (exist_tph != '') {
				etph = exist_tph;
			} else {
				etph = '';
			}

			$.ajax({
				type: 'POST',
				url: 'KoreksiAmbilTPH.php',
				dataType: 'json',
				data: { ba: ba, afd: afd, blok: blok, exist_tph: etph },
				success: function(data) {
					$('#selecttph').html(data);
					$('#selecttphawal').val(etph);
				}
			})
		}).change();

		$(".qtyval").keydown(function (e) {
			//Edited by Ardo, 10-02-2016 : prevent user click other than number
			var charCode = (e.which) ? e.which : e.keyCode
			if (charCode > 31 && (charCode < 48 || charCode > 57)){
				e.preventDefault();
			}
			return true;
		});
		
		$( "#dialog-form" ).dialog({
			autoOpen: false,
			width: 400
		});

		// Link to open the dialog
		$( "#btn_hapus_bcc" ).click(function( event ) {
			$( "#dialog-form" ).dialog( "open" );
			event.preventDefault();
		});
		
		$( "#btn_delete_close" ).click(function( event ) {
			$( "#dialog-form" ).dialog( "close" );
			event.preventDefault();
		});

		$('#lokasisubmit').click(function() {
			$.ajax({
				url: 'doUpdateBCCPemanenLokasi.php',
				type: 'POST',
				dataType: 'json',
				contentType: false,
				processData: false,
				data: new FormData($('#FormEditBCC')[0]),
				success: function(data) {
					location.reload();
					//window.location.replace('KoreksiBCCList.php');
				}
			});
		});
		
	});
	
</script>

<form id="FormEditBCC" name="FormEditBCC" method="post" action="doUpdateBCCPemanen.php" onsubmit="return formSubmitvalue()">
<table width="1151" height="390" border="0" align="center">

  <!--<tr bgcolor="#C4D59E">-->
  <tr>
    <th height="197" scope="row" align="center"><table width="937" border="0" id="setbody2">
      <tr>
		<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>KOREKSI DATA BCC</strong></span></td>
      </tr>
  
  <tr>
    <th height="197" scope="row" align="center"><table border="0" style="border:solid #556A29">
      <tr>
        <td align="center"><table width="995" border="0">
          <tr>
            <td width="130" height="29" valign="top">Company Name</td>
            <td width="7" height="29" valign="top">:</td>
            <td width="355" align="left" valign="top"><input type="hidden" name="LUASAN_PANEN" value="<?=$LUASAN_PANEN?>"><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$COMP_NAME?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
            <td width="130" height="29" valign="top">Tanggal Panen</td>
            <td width="7" height="29" valign="top">:</td>
            <td width="355" align="left" valign="top">
				<input type="text" name="datepicker" id="datepicker1" value="<?=$TANGGAL_RENCANA?>" style="width:300px; height:25px; font-size:15px" readOnly="readOnly" >
				
				</td>
            </tr>
          <tr>
            <td width="130" height="29" valign="top">Business Area</td>
            <td width="7" height="29" valign="top">:</td>
            <td width="355" align="left" valign="top" ><input name="ID_BAlabel" type="text" id="ID_BAlabel" value="<?=$BA?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/></td>
            <td width="130" height="29" valign="top" >Nama Mandor</td>
            <td width="7" height="29" valign="top" >:</td>
            <td width="355" align="left" valign="top" ><input name="Nama_Mandorlabel" type="text" id="Nama_Mandorlabel" value="<?=$NAMA_MANDOR?>" style="width: 300px; height:25px; font-size:15px" onClick='javascript:showListMandor();'  readOnly="readOnly"/>
				<input name="NIK_Mandor" type="text" id="NIK_Mandor" value="<?=$NIK_MANDOR?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px; display:none">
				
			</td>
            </tr>
          <tr>
            <td width="130" height="29" valign="top" >No BCC</td>
            <td width="7" height="29" valign="top" >:</td>
            <td width="355" align="left" valign="top" ><input name="No_BCClabel" type="text" id="No_BCClabel" value="<?=separator($aNO_BCC)?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/><input name="NO_Rekap" type="text" id="NO_Rekap" value="<?=$NO_REKAP?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px; display:none"></td>
            </tr>
        </table></td>
        					<td></td><td></td>

        </tr>
	<tr>
		<td align="center" style="padding-top:20px; padding-bottom:10px;">
			<table width="991" border="0" style="border:solid #556A29;">
				<tr>
					<td width="112" align="center" colspan="4" style="padding-top:15px; padding-bottom: 15px;">Lokasi Panen</td>
				</tr>
				<tr>
					<td width="130" height="29" valign="top" style="padding-left:20px;">Afdeling Panen</td>
					<td width="7" height="29" valign="top" align="right">:</td>
					<td width="355" align="left" valign="top" >
						<?php
							$afd_by_ba = "select * from T_AFDELING where ID_BA = '".$BA."'";
							$result_afd_by_ba = oci_parse($con, $afd_by_ba);
							oci_execute($result_afd_by_ba, OCI_DEFAULT);
						?>
						<select name="AFDlabel" id="AFDlabel" style="width:200px; height:25px; font-size:15px">
							<?php
								while (oci_fetch($result_afd_by_ba)) {
									if (oci_result($result_afd_by_ba, "ID_AFD") == $AFD) {
							?> 
							<option value="<?php echo oci_result($result_afd_by_ba, "ID_AFD");?>" selected><?=oci_result($result_afd_by_ba, "ID_AFD")?></option>
							<?php
								} else {
							?> 
							<option value="<?php echo oci_result($result_afd_by_ba, "ID_AFD");?>"><?=oci_result($result_afd_by_ba, "ID_AFD")?></option>
							<?php
									}
								}
							?>
						</select>
						<input type="hidden" id="AFDlabelAwal" name="afd_awal" value="">
					</td>
					<td align="center">
						<?php if ($STATUS_LOKASI != '1') : ?>
						<?php if (str_replace('.', '', $jarak) > 30) : ?>
						<input type="button" id="lokasisubmit" name="lokasisubmit" value="Lokasi Sudah Benar" style="color:white; background-color:#19521D; padding-left:20px; padding-right:20px; padding-top:5px; padding-bottom:5px; font-size:14px; text-align:center;">
						<?php endif; ?>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td width="130" height="29" valign="top" style="padding-left:20px;">Blok</td>
					<td width="7" height="29" valign="top" align="right">:</td>
					<td width="355" align="left" valign="top">
						<select name="selectblok" id="selectblok" style="width:200px; height:25px; font-size:15px">
							<?php
								$query_blok  = "SELECT * FROM T_BLOK WHERE ID_BA_AFD = '$BA$AFD' AND (INACTIVE_DATE IS NULL or INACTIVE_DATE >= '$TANGGAL_RENCANA') order by ID_BLOK"; //Adding Inactive_Date Filter by Ardo, 18-01-2016
								$result_blok = oci_parse($con, $query_blok);
								oci_execute($result_blok, OCI_DEFAULT);
								while ($p=oci_fetch($result_blok)) {
									$id_blok = oci_result($result_blok, "ID_BLOK");
									$blok_name = oci_result($result_blok, "BLOK_NAME");
									if ($id_blok == $ID_BLOK) {
										echo "<option value=\"$id_blok\" selected='selected'>$id_blok - $blok_name</option>\n";
									} else {
										echo "<option value=\"$id_blok\">$id_blok - $blok_name</option>\n";
									}
								}
							?>
						</select>
						<input type="hidden" id="selectblokawal" name="blok_awal" value="">
					</td>
					<td></td>
				</tr>
				<tr>
					<td width="130" height="29" valign="top" style="padding-left:20px;">TPH</td>
					<td width="7" height="29" valign="top" >:</td>
					<td width="355" align="left" valign="top">
						<input type="hidden" id="exist_tph" value="<?=$BLOK_TPH?>">
						<select name="selecttph" id="selecttph" style="width:200px; height:25px; font-size:15px">
							<option>--select--</option>
						</select>
						<input type="hidden" id="selecttphawal" name="tph_awal" value="">
					</td>
					<td></td>
				</tr>
				<tr>
					<td width="250" height="29" valign="top" style="padding-left:20px;">Jarak Geo Tagging & TPH Inputan</td>
					<td width="7" height="29" valign="top" >:</td>
					<td width="355" align="left" valign="top" >
						<input name="jarakGEO" type="text" id="jarakGEO" value="<?=$jarak?>" style="width:200px; height:25px; font-size:15px" readOnly="readOnly"/> meter
						<input type="hidden" id="jarakGEOAwal" name="jarakGEOAwal" value="">
						<input type="hidden" name="lat_bcc" value="<?php echo $LAT_TPH; ?>">
						<input type="hidden" name="long_bcc" value="<?php echo $LONG_TPH; ?>">
						<input type="hidden" name="lat_tph" value="<?php echo $LAT_M_TPH; ?>">
						<input type="hidden" name="long_tph" value="<?php echo $LONG_M_TPH; ?>">
					</td>
					<td></td>
				</tr>
				<tr>
					<td colspan="4" style="padding:20px;">
						<p style="text-align:center;"><u><b>TPH Terdekat</b></u></p>
						<table id="tph-terdekat" class="tph-terdekat" width="100%" border="0" style="border:solid #556A29;">
							<thead style="border-bottom:3px solid #556A29;">
								<tr>
									<th style="font-weight:bold; padding-top:10px; padding-bottom:10px; border-right: 2px solid #556A29;">AFD</th>
									<th style="font-weight:bold; padding-top:10px; padding-bottom:10px; border-right: 2px solid #556A29;">Block</th>
									<th style="font-weight:bold; padding-top:10px; padding-bottom:10px; border-right: 2px solid #556A29;">TPH</th>
									<th style="font-weight:bold; padding-top:10px; padding-bottom:10px; border-right: 2px solid #556A29;">Jarak (meter)</th>
									<th style="font-weight:bold; padding-top:10px; padding-bottom:10px; border-right: 2px solid #556A29;">Pilih</th>
								</tr>
							</thead>
							<?php
								$sql = "
									SELECT werks, afd_code, blok_name, block_code, latitude, longitude, no_tph, jarak FROM (
										SELECT werks, afd_code, block_code, latitude, longitude, no_tph, round(distance * 1000, 2) as jarak 
										FROM (
											SELECT z.werks, z.afd_code, z.block_code, z.latitude, z.longitude, z.no_tph, p.radius,
												p.distance_unit
													* rad2deg * (ACOS(COS(deg2rad * (p.latpoint))
													* COS(deg2rad * (z.latitude))
													* COS(deg2rad * (p.longpoint - z.longitude))
													+ SIN(deg2rad * (p.latpoint))
													* SIN(deg2rad * (z.latitude)))) AS distance
											FROM TAP_DW.TM_TPH@DEVDW_LINK z
											--  FROM T_HASIL_PANEN z
											JOIN (
												SELECT 
													-2.91877874 AS latpoint, 112.32735435 AS longpoint,
													0.03 AS radius,        111.045 AS distance_unit,
													57.2957795 AS rad2deg, 0.0174532925 AS deg2rad
												FROM  DUAL
											) p ON 1=1
											WHERE 
												z.latitude <> '-2.91877874' 
												AND z.longitude <> '112.32735435'
												AND z.latitude
													BETWEEN p.latpoint  - (p.radius / p.distance_unit)
													AND p.latpoint  + (p.radius / p.distance_unit)
												AND z.longitude
													BETWEEN p.longpoint - (p.radius / (p.distance_unit * COS(deg2rad * (p.latpoint))))
													AND p.longpoint + (p.radius / (p.distance_unit * COS(deg2rad * (p.latpoint))))
										)
										WHERE distance <= radius
										ORDER BY distance
									) d
									LEFT JOIN t_blok tb ON tb.id_ba_afd_blok = d.werks || d.afd_code || d.block_code
									WHERE ROWNUM <= 6
								";

								$result_latlong = oci_parse($con, $sql);
								oci_execute($result_latlong);
							?>
							<tbody>
								<?php while ($row = oci_fetch_array($result_latlong, OCI_ASSOC+OCI_RETURN_NULLS)) : ?>
									<?php $a = $row['WERKS'] . '-' . $row['AFD_CODE'] . '-' . $row['BLOCK_CODE'] . '-' . $row['NO_TPH'] . '-' . $row['JARAK']; ?>
									<tr>
										<td style="text-align:center; padding-top:5px; padding-bottom:5px; border-bottom:2px solid #556A29; border-right:2px solid #556A29; padding-left:5px; padding-right:5px;"><?php echo $row[AFD_CODE]; ?></td>
										<td style="text-align:center; padding-top:5px; padding-bottom:5px; border-bottom:2px solid #556A29; border-right:2px solid #556A29; padding-left:5px; padding-right:5px;"><?php echo $row['BLOCK_CODE']; ?> - <?php echo $row['BLOCK_NAME']; ?></td>
										<td style="text-align:center; padding-top:5px; padding-bottom:5px; border-bottom:2px solid #556A29; border-right:2px solid #556A29; padding-left:5px; padding-right:5px;"><?php echo $row['NO_TPH']; ?></td>
										<td style="text-align:center; padding-top:5px; padding-bottom:5px; border-bottom:2px solid #556A29; border-right:2px solid #556A29; padding-left:5px; padding-right:5px;"><?php echo $row['JARAK']; ?></td>
										<td style="text-align:center; padding-top:5px; padding-bottom:5px; border-bottom:2px solid #556A29; border-right:2px solid #556A29; padding-left:5px; padding-right:5px;"><a href="" id="abc" onclick="pilihTerdekat('<?php echo $a; ?>'); return false;" style="color:blue;">Pilih</a></td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</td>
				</tr>
			</table>
		</td>
					<td></td><td></td>
	</tr>
      <tr>
        <td align="center">
          <table width="991" border="0" style="border:solid #556A29">
            <tr>
              <td width="112" align="center" colspan="6">Data Pemanen</td>
              </tr>
            <tr>
              <td width="130" height="29" valign="top" >Business Area</td>
              <td width="7" height="29" valign="top" >:</td>
              <td width="355" align="left" valign="top" ><input name="ID_BAlabel" type="text" id="ID_BAlabel" value="<?=$BA?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/></td>
              
              <td width="130" height="29" valign="top" >Nama Pemanen</td>
              <td width="7" height="29" valign="top" >:</td>
              <td width="355" align="left" valign="top" >
                <input name="Nama_Pemanen" type="text" id="Nama_Pemanen" value="<?=$Nama_Pemanen?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/>
                </td>
              </tr>
            <tr>
              <td width="130" height="29" valign="top" >Afdeling</td>
              <td width="7" height="29" valign="top" >:</td>
              <td width="355" align="left" valign="top">
                <input name="Afd_Pemanen" type="text" id="Afd_Pemanen" value="<?=$Afd_Pemanen?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/>
                </td>
              
              <td width="130" height="29" valign="top" >NIK Pemanen</td>
              <td width="7" height="29" valign="top" >:</td>
              <td width="355" align="left" valign="top" >
                <input name="NIK_Pemanen" type="text" id="NIK_Pemanen" value="<?=$NIK_Pemanen?>" style="width:300px; height:25px; font-size:15px" onClick='javascript:showListPemanen();' readOnly="readOnly"/></td>
				
			  </tr>
          </table>        </td><td></td><td></td>
        </tr>
      <tr>
        <td align="center">
          <table width="992" border="0" style="border:solid #556A29">
            <tr bgcolor="#9CC346">
              <td width="55" align="center" style="font-size:14px; border-bottom:ridge">No.</td>
              <td width="466" align="center" style="font-size:14px; border-bottom:ridge">Kualitas Panen</td>
              <td width="216" align="center" style="font-size:14px; border-bottom:ridge">Jumlah Lama</td>
              <td width="229" align="center" style="font-size:14px; border-bottom:ridge">Jumlah Baru</td>
              </tr>
            <?php
//Added by Ardo 16-02-2016 : Penambahan informasi tambahan penalty
$get_kualitas_penalty = oci_parse($con,"SELECT * FROM T_KUALITAS_PANEN WHERE GROUP_KUALITAS = 'PENALTY MANDOR' ORDER BY NAMA_KUALITAS, ID_KUALITAS ASC");
oci_execute($get_kualitas_penalty, OCI_DEFAULT);
while ($p=oci_fetch($get_kualitas_penalty)){
	$daftar_kualitas_penalty[oci_result($get_kualitas_penalty, "ID_KUALITAS")] = oci_result($get_kualitas_penalty, "ID_KUALITAS");
}

for($xJAN = 0; $xJAN <  $roweffec_BCC ; $xJAN++){
	$no = $xJAN +1;
	
	if(($xJAN % 2) == 0){
		$bg = "#F0F3EC";
	}
	else{
		$bg = "#DEE7D2";
	}
	
	echo "<tr style=\"font-size:14px\" bgcolor=$bg >";
	echo "<td>$no</td>
            <td>$NAMA_KUALITAS[$xJAN]</td>
            <td align=\"center\">$QTY[$xJAN]<input name=\"OldQty$xJAN\" value=\"$QTY[$xJAN]\" style=\"display:none\">
			<input type=\"hidden\" name=\"ID_BCC_KUALITAS$xJAN\" value=\"$ID_BCC_KUALITAS[$xJAN]\" style=\"\">
			<input type=\"hidden\" name=\"ID_Kualitas$xJAN\" id=\"ID_Kualitas$xJAN\" value=\"$ID_Kualitas[$xJAN]\">
			<input type=\"hidden\" name=\"NAMA_KUALITAS$xJAN\" id=\"NAMA_KUALITAS$xJAN\" value=\"$NAMA_KUALITAS[$xJAN]\">
			<input type=\"text\" name=\"NIK_Pemanen1\" value=\"$NIK_Pemanen\" style=\"display:none\">
			<input type=\"hidden\" name=\"PARAM\" id=\"PARAM$xJAN\" value=\"$PARAM[$xJAN]\" style=\"\">
			<input type=\"hidden\" name=\"ID_RENCANA\" value=\"$ID_RENCANA\" style=\"\"></td>
            <td align=\"center\">
			<input name=\"NewQty$xJAN\" type=\"text\" class='qtyval' id=\"NewQty$xJAN\" value=\"$QTY[$xJAN]\" style=\"width: 50px; height:25px; font-size:15px\" onchange=\"cekvalid($xJAN)\"  /></td>
			";
	
	unset($daftar_kualitas_penalty[$ID_Kualitas[$xJAN]]);
	$xJAN2 = $xJAN;
}

//Added by Ardo 16-02-2016 : Penambahan informasi tambahan penalty
$xJAN2++;
foreach($daftar_kualitas_penalty as $row_penalty){
	
	$get_kualitas_penalty = oci_parse($con,"SELECT * FROM T_KUALITAS_PANEN WHERE ID_KUALITAS = '".$row_penalty."'");
	oci_execute($get_kualitas_penalty, OCI_DEFAULT);
	while ($p=oci_fetch($get_kualitas_penalty)){
		if(($xJAN2 % 2) == 0){
			$bg = "#F0F3EC";
		}
		else{
			$bg = "#DEE7D2";
		}
		echo "<tr style=\"font-size:14px\" bgcolor=$bg >";
		echo "<td>".++$no."</td>
            <td>".oci_result($get_kualitas_penalty, "NAMA_KUALITAS")."</td>
            <td align=\"center\">0<input name=\"OldQty$xJAN2\" value=\"0\" style=\"display:none\">
			<input type=\"hidden\" name=\"ID_BCC_KUALITAS$xJAN2\" value=\"$aNO_BCC$row_penalty\" style=\"\">
			<input type=\"hidden\" name=\"ID_Kualitas$xJAN2\" id=\"ID_Kualitas$xJAN2\" value=\"$row_penalty\">
			<input type=\"hidden\" name=\"NAMA_KUALITAS$xJAN2\" id=\"NAMA_KUALITAS$xJAN2\" value=\"".oci_result($get_kualitas_penalty, "NAMA_KUALITAS")."\">
			<input type=\"text\" name=\"NIK_Pemanen1\" value=\"$NIK_Pemanen\" style=\"display:none\">
			<input type=\"hidden\" name=\"PARAM\" id=\"PARAM$xJAN2\" value=\"".oci_result($get_kualitas_penalty, "PARAM")."\" style=\"\">
			<input type=\"hidden\" name=\"ID_RENCANA\" value=\"$ID_RENCANA\" style=\"\"></td>
			<td align=\"center\">
			<input name=\"NewQty$xJAN2\" type=\"text\" class='qtyval' id=\"NewQty$xJAN2\" value=\"0\" style=\"width: 50px; height:25px; font-size:15px\" onchange=\"cekvalid($xJAN2)\"  /></td>
			";
	}
	$xJAN2++;
}
echo "</tr>";
?>
            </table>
          <input name="roweffec_BCC" type="text" id="roweffec_BCC" value="<?=$roweffec_BCC?>" style="display:none"/>
          <input name="No_BCC" type="text" id="No_BCC" value="<?=$aNO_BCC?>" style="display:none"/>
        </td>
<td></td><td></td>
        </tr>
      <tr>
		
        <td align="center" colspan="3">
			<input type="hidden" name="id_bafd_old" value="<?=$BA.''.$AFD.''.$ID_BLOK?>">
			<input type="submit" name="button" id="button" value="SIMPAN" style="width:120px; height: 30px"/>
			&nbsp;
			<?php
			//Edited by Ardo, 11-11-2016 : Hapus BCC untuk ALL PT
			//if($_SESSION['subID_CC']=='43' or $Job_Code=='ADMINISTRATOR' or $Job_Code=='ADM'){
			?>
			<input type="button" name="button" id="btn_hapus_bcc" value="HAPUS" style="width:120px; height: 30px"/>
			&nbsp;
			<?php //} ?>
			<input type="button" name="button" id="button" value="KEMBALI" style="width:120px; height: 30px" onClick="window.history.go(-1)"/>
		</td>
		
      </tr>
	  
       <tr>
         <td align="center"><span class="style1">Pastikan koreksi data Anda telah mendapatkan persetujuan dari EM atau KABUN !!</span></td>
         <td></td>
         <td></td>
        </tr>
    </table></th>
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
</th>
  </tr>
  </form>
  
  <div id="dialog-form" title="Konfirmasi Delete BCC">
  <p class="validateTips">Apakah Anda yakin untuk menghapus BCC ini ?</p>
 
  <form method="post" action="doDeleteBCC.php" onsubmit="return cek_konfirmasi_delete()">
    <fieldset>
		
		<input type="hidden" name="ID_RENCANA" value="<?= $ID_RENCANA ?>">
		<input type="hidden" name="NO_REKAP_BCC" value="<?= $NO_REKAP ?>">
		<input type="hidden" name="NO_BCC" value="<?= $aNO_BCC ?>">
		<input type="hidden" name="PROFILE_NAME" value="<?= $PROFILE_NAME ?>">
		<input type="hidden" name="CC" value="<?= $CC ?>">
		
        <label for="nomor_ba">Input Nomor BA</label><br>
        <input type="text" name="nomor_ba" id="nomor_ba" class="text ui-widget-content ui-corner-all"><br>
        <label for="tanggal_ba">Tanggal BA</label><br>
        <input type="text" name="tanggal_ba" id="tanggal_ba" class="text ui-widget-content ui-corner-all"><br>
        <label for="alasan">Alasan</label><br>
        <textarea name="alasan" id="alasan" class="text ui-widget-content ui-corner-all"></textarea><br><br>
	
		<!-- Allow form submission with keyboard without duplicating the dialog button -->
		<div style="text-align:center">
		<input type="submit" value="YA" id="btn_delete_ok">
		<input type="button" value="BATAL" id="btn_delete_close">
		</div>
    </fieldset>
  </form>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#AFDlabelAwal').val($('#AFDlabel').val());
		$('#selectblokawal').val($('#selectblok').val());
		$('#jarakGEOAwal').val($('#jarakGEO').val());
	})
</script>
<?php
}
else{
	$_SESSION[err] = "tolong login dulu!".$Job_Code."<br>".$username."<br>".$Emp_Name."<br>".$subID_BA_Afd;
	header("location:../index.php");
}
?>