<?PHP 
include("../config/SQL_function.php");
include("../config/db_connect.php");
?>
<HTML>
<HEAD>
<TITLE>Daftar Karyawan</TITLE>
<script type="text/javascript" src="jquery.js"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function pick(symbol,tgl_panen, afdeling) {
	if (window.opener && !window.opener.closed) {
    window.opener.document.inputaktivitaspanen.nikPemanen.value = symbol; 
	window.close();
	}
}
function pickNAMA(symbol,row) {
	if (window.opener && !window.opener.closed) {
    window.opener.document.inputaktivitaspanen.namaPemanen.value = symbol;
	window.close();
	}
}
function pickla(symbol,row) {
	if (window.opener && !window.opener.closed) {
    window.opener.document.inputhasilpanen.txtNik.value = symbol;
	window.opener.document.inputhasilpanen.docCode.value = window.opener.document.adddetaildoc.docCode.value + "\'" + symbol + "\'" +",";
	window.close();
	}
}
// -->
</SCRIPT>
<link href="./css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>
<?PHP
$afdeling = $_GET['afdeling'];
$tgl_panen = $_GET['tgl_panen'];
$buss_area = substr($afdeling, 0, -1);
$con = connect();
	$query = "Select COUNT(NIK_PEMANEN) from T_HEADER_RENCANA_PANEN THRP 
                         LEFT JOIN T_DETAIL_RENCANA_PANEN TDRP ON (THRP.ID_RENCANA = TDRP.ID_RENCANA)
                         LEFT JOIN T_EMPLOYEE TE ON (TE.NIK = THRP.NIK_PEMANEN)
                         WHERE TANGGAL_RENCANA = TO_DATE('$tgl_panen', 'MM/DD/YYYY HH24:MI:SS') AND LUASAN_PANEN = '0' 
						 And ID_BA_AFD_BLOK like '$afdeling%'";
	$sql1 = oci_parse($con,$query);
			oci_execute($sql1, OCI_DEFAULT);
	$numRow = oci_fetch_row($sql1);

	$query_emp = "Select distinct(SUBSTR(TE.ID_BA_AFD,1,4)) as BA, SUBSTR(TE.ID_BA_AFD,5,1) as AFD,
						(NIK_PEMANEN) as NIK, EMP_NAME, JOB_CODE from T_HEADER_RENCANA_PANEN THRP 
                         LEFT JOIN T_DETAIL_RENCANA_PANEN TDRP ON (THRP.ID_RENCANA = TDRP.ID_RENCANA)
                         LEFT JOIN T_EMPLOYEE TE ON (TE.NIK = THRP.NIK_PEMANEN)
                         WHERE TANGGAL_RENCANA = TO_DATE('$tgl_panen', 'MM/DD/YYYY HH24:MI:SS') AND LUASAN_PANEN = '0' 
						 and ID_BA_AFD_BLOK like '$afdeling%' and INACTIVE_DATE is NULL
						 order by (SUBSTR(TE.ID_BA_AFD,1,4)), SUBSTR(TE.ID_BA_AFD,5,1), TE.JOB_CODE, EMP_NAME";
						 //echo $query_emp;die();
    $sql = oci_parse($con,$query_emp);
	oci_execute($sql, OCI_DEFAULT);
	if ($numRow[0]==0) {
		echo "
		<table width='100%' border=0 cellspacing=0 cellpadding=0 style='border:none'>
		<tr>
			<td align='center'>
				<img src='./images/error.png'><br>
				<div class='error'>Tidak Ada Dokumen Yang Tersedia</div>
			</td>
		</tr>
		<tr>
			<td align='center'>
				<a href='#' onclick='window.close();'><b>[Tutup]</b></a>
			</td>
		</tr>
		</table>
		";
	}
	else{
		echo "<form name='search' method='post' action='".$_SERVER['PHP_SELF']."?afdeling=$afdeling&tgl_panen=$tgl_panen'>
			  <div style='text-align:left; padding:10px 5px; margin-bottom :5px; background :#CCC;'>
				<b>Pencarian :</b> <input name='txtSearch' id='txtSearch' type='text' size='25%' style='text-transform:uppercase'/>
			  </div>
			  </form>";
		?>

		<table width="100%" border="1" cellspacing="0" cellpadding="0">
		<tr>
			<th width='5%'>BA</th>
			<th width='5%'>Afdeling</th>
			<th width='15%'>NIK</th>
			<th width='30%'>Nama Karyawan</th>
			<th width='20%'>Jabatan</th>
		<tr>
		<?PHP
		if($_POST) {
			$afdeling = $_GET['afdeling'];
			$tgl_panen = $_GET['tgl_panen'];
			$buss_area = substr($afdeling, 0, -1);
			$search=strtoupper($_POST['txtSearch']);
			$query_emp = "Select distinct(SUBSTR(TE.ID_BA_AFD,1,4)) as BA, SUBSTR(TE.ID_BA_AFD,5,1) as AFD,
						 (NIK_PEMANEN) as NIK, EMP_NAME, JOB_CODE from T_HEADER_RENCANA_PANEN THRP 
                         LEFT JOIN T_DETAIL_RENCANA_PANEN TDRP ON (THRP.ID_RENCANA = TDRP.ID_RENCANA)
                         LEFT JOIN T_EMPLOYEE TE ON (TE.NIK = THRP.NIK_PEMANEN)
                         WHERE TANGGAL_RENCANA = TO_DATE('$tgl_panen', 'MM/DD/YYYY HH24:MI:SS') AND LUASAN_PANEN = '0' 
						 and ID_BA_AFD_BLOK like '$afdeling%' AND (NIK_PEMANEN LIKE '%$search%' OR EMP_NAME LIKE '%$search%')
						 and INACTIVE_DATE is NULL
						 order by TJ.ID_BA, SUBSTR(TE.ID_BA_AFD, 5,1), TE.JOB_CODE, EMP_NAME";
			$sql = oci_parse($con,$query_emp);
			
			oci_execute($sql, OCI_DEFAULT);
			$results=array();
			//$numSearch = oci_fetch_all($sql,$results);

			//if ($numSearch==0){
			//	echo"<tr><td colspan='20' align='center'><b>Data Tidak Ditemukan</b></td></tr>";
			//}else{
				while ($ar=oci_fetch_array($sql)){
				//print_r($arr);die();
					?>
					<tr>
						<td align='center'><?= $ar['BA'] ?></td>
						<td align='center'><?= $ar['AFD'] ?></td>
						<td align='center'><u><a href="javascript:pick('<?= $ar['NIK'] ?>','<?= $tgl_panen ?>','<?= $afdeling ?>');javascript:pickNAMA('<?= $ar['EMP_NAME'] ?>')"><?= $ar['NIK'] ?></a></u></td>
						<td align='center'><?= $ar['EMP_NAME'] ?></td>
						<td align='center'><?= $ar['JOB_CODE'] ?></td>
					</tr>
					<?php
				//}
			}
		}
		while ($arr=oci_fetch_array($sql)){
			?>
			<tr>
				<td align='center'><?= $arr['BA'] ?></td>
				<td align='center'><?= $arr['AFD'] ?></td>
				<td align='center'><u><a href="javascript:pick('<?= $arr['NIK'] ?>','<?= $tgl_panen ?>','<?= $afdeling ?>');javascript:pickNAMA('<?= $arr['EMP_NAME'] ?>')"><?= $arr['NIK'] ?></a></u></td>
				<td align='center'><?= $arr['EMP_NAME'] ?></td>
				<td align='center'><?= $arr['JOB_CODE'] ?></td>
			</tr>
			<?PHP
		}
	}

?>		
</TABLE>
</BODY>
</HTML>