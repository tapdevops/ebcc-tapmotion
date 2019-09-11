<?PHP 
include("../config/SQL_function.php");
include("../config/db_connect.php");
?>
<HTML>
<HEAD>
<TITLE>Daftar Karyawan</TITLE>
<script type="text/javascript" src="jquery.js"></script>
<SCRIPT LANGUAGE="JavaScript">

function pick(symbol, afdeling) {
	if (window.opener && !window.opener.closed) {
    window.opener.document.FormEditBCC.NIK_Pemanen.value = symbol;
	window.close();
	}
}
function pickNAMA(symbol,row) {
	if (window.opener && !window.opener.closed) {
    window.opener.document.FormEditBCC.Nama_Pemanen.value = symbol;
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

</SCRIPT>
<link href="./css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>
<?PHP
$afdeling = $_GET['afdeling'];
$con = connect();
	$query = "select COUNT(TJ.ID_BA) as JUMLAH
				 from T_JOBAUTHORITY TJ 
                 left join T_EMPLOYEE TE on TE.ID_JOBAUTHORITY = TJ.ID_JOBAUTHORITY
                 where AUTHORITY = '1' and ID_BA_AFD = '$afdeling'";
	$sql1 = oci_parse($con,$query);
			oci_execute($sql1, OCI_DEFAULT);
	$numRow = oci_fetch_row($sql1);

	$query_emp = "select TJ.ID_BA as BA, 
					SUBSTR(TE.ID_BA_AFD, 5,1) as AFD, 
					TE.NIK as NIK, 
					TE.EMP_NAME as EMP_NAME, 
					TE.JOB_CODE as JOB_CODE from T_JOBAUTHORITY TJ 
                 left join T_EMPLOYEE TE on TE.ID_JOBAUTHORITY = TJ.ID_JOBAUTHORITY
                 where AUTHORITY = '1' and ID_BA_AFD = '$afdeling'";
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
			$search=strtoupper($_POST['txtSearch']);
			$query_emp = "select TJ.ID_BA as BA, 
							SUBSTR(TE.ID_BA_AFD, 5,1) as AFD, 
							TE.NIK as NIK, 
							TE.EMP_NAME as EMP_NAME, 
							TE.JOB_CODE as JOB_CODE from T_JOBAUTHORITY TJ 
						 left join T_EMPLOYEE TE on TE.ID_JOBAUTHORITY = TJ.ID_JOBAUTHORITY
						 where AUTHORITY = '1' and ID_BA_AFD = '$afdeling' AND (NIK LIKE '%$search%' OR EMP_NAME LIKE '%$search%')";
			$sql = oci_parse($con,$query_emp);
			
			oci_execute($sql, OCI_DEFAULT);
			$results=array();
				while ($ar=oci_fetch_array($sql)){
					?>
					<tr>
						<td align='center'><?= $ar['BA'] ?></td>
						<td align='center'><?= $ar['AFD'] ?></td>
						<td align='center'><u><a href="javascript:pick('<?= $ar['NIK'] ?>','<?= $afdeling ?>');javascript:pickNAMA('<?= $ar['EMP_NAME'] ?>')"><?= $ar['NIK'] ?></a></u></td>
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
				<td align='center'><u><a href="javascript:pick('<?= $arr['NIK'] ?>','<?= $afdeling ?>');javascript:pickNAMA('<?= $arr['EMP_NAME'] ?>')"><?= $arr['NIK'] ?></a></u></td>
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