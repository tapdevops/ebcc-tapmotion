<?PHP 
include("../config/SQL_function.php");
include("../config/db_connect.php");
?>
<HTML>
<HEAD>
<TITLE>Daftar Nomor Polisi</TITLE>
<SCRIPT LANGUAGE="JavaScript">
<!--
function pick_polisi(symbol,row) {
	if (window.opener && !window.opener.closed) {
    window.opener.document.inputpengirimanpanen.noPolisi1.value = symbol;
	window.close();
	}
}
function pick_iorder(symbol,row) {
	if (window.opener && !window.opener.closed) {
    window.opener.document.inputpengirimanpanen.noIntOrder1.value = symbol;
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
$buss_area = $_GET['BA'];
//$baris = $_GET['baris'];

$con = connect();
//IF ($baris=="3"){
	$query = "SELECT COUNT(NO_POLISI) AS JUMLAH
				  FROM T_INTERNAL_ORDER
				 WHERE ID_BA = '$buss_area'";
	$sql1 = oci_parse($con,$query);
			oci_execute($sql1, OCI_DEFAULT);
	$numRow = oci_fetch_row($sql1);
	
	$query_emp = "SELECT NO_POLISI, ID_INTERNAL_ORDER
				  FROM T_INTERNAL_ORDER
				 WHERE ID_BA = '$buss_area'";

  		    $sql = oci_parse($con,$query_emp);
			oci_execute($sql, OCI_DEFAULT);
	if ($numRow[0]==0) {
		echo "
		<table width='100%' border=0 cellspacing=0 cellpadding=0 style='border:none'>
		<tr>
			<td align='center'>
				<img src='./images/error.png'><br>
				<div class='error'>Tidak Ada Kendaraan Yang Tersedia</div>
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
		echo "<form name='search' method='post' action='".$_SERVER['PHP_SELF']."?BA=$buss_area'>
			  <div style='text-align:left; padding:10px 5px; margin-bottom :5px; background :#CCC;'>
				<b>Pencarian :</b> <input name='txtSearch' id='txtSearch' type='text' size='25%' style='text-transform:uppercase'/>
			  </div>
			  </form>";
		?>

		<table width="100%" border="1" cellspacing="0" cellpadding="0">
		<tr>
			<th width='15%'>No Polisi</th>
			<th width='25%'>Internal Order</th>
		<tr>
		<?PHP
		if($_POST) {
			$buss_area = $_GET['BA'];
			$search=strtoupper($_POST['txtSearch']);
			$query_emp =   "SELECT NO_POLISI, ID_INTERNAL_ORDER
						  FROM T_INTERNAL_ORDER
						 WHERE ID_BA = '$buss_area'
						 AND (ID_INTERNAL_ORDER LIKE '%$search%' OR NO_POLISI LIKE '%$search%')";
			$sql = oci_parse($con,$query_emp);
			
			oci_execute($sql, OCI_DEFAULT);
			$results=array();
			while ($ar=oci_fetch_array($sql)){
				?>
					<tr>
					<td align='center'><u><a href="javascript:pick_polisi('<?= $ar['NO_POLISI'] ?>');javascript:pick_iorder('<?= $ar['ID_INTERNAL_ORDER'] ?>')"><?= $ar['NO_POLISI'] ?></a></u></td>
						<td align='center'><?= $ar['ID_INTERNAL_ORDER'] ?></td>
				</tr>
				<?php
			}
		}
		while ($arr=oci_fetch_array($sql)){
			?>
			<tr>
				<td align='center'><u><a href="javascript:pick_polisi('<?= $arr['NO_POLISI'] ?>');javascript:pick_iorder('<?= $arr['ID_INTERNAL_ORDER'] ?>')"><?= $arr['NO_POLISI'] ?></a></u></td>
				<td align='center'><?= $arr['ID_INTERNAL_ORDER'] ?></td>
			</tr>
			<?PHP
		}
	}
//}

/*ELSE{
	
	$buss_area = $_GET['BA'];
	$query = "SELECT COUNT(NIK) AS JUMLAH
				  FROM T_EMPLOYEE
				 WHERE JOB_CODE IN ('MANDOR PANEN','MANDOR 1')
				 AND (" . $id_ba . ")"; //added by NB 01.07.2014
	$sql1 = oci_parse($con,$query);
			oci_execute($sql1, OCI_DEFAULT);
	$numRow = oci_fetch_row($sql1);
	
	$query_emp = "SELECT NIK, EMP_NAME, JOB_CODE
				  FROM T_EMPLOYEE
				 WHERE JOB_CODE IN ('MANDOR PANEN','MANDOR 1')
				 AND (" . $id_ba . ")"; //added by NB 01.07.2014
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
		echo "<form name='search' method='post' action='".$_SERVER['PHP_SELF']."?afdeling=$afdeling&baris=$baris'>
			  <div style='text-align:left; padding:10px 5px; margin-bottom :5px; background :#CCC;'>
				<b>Pencarian :</b> <input name='txtSearch' id='txtSearch' type='text' size='25%' style='text-transform:uppercase'/>
			  </div>
			  </form>";
		?>

		<table width="100%" border="1" cellspacing="0" cellpadding="0">
		<tr>
			<th width='15%'>NIK</th>
			<th width='25%'>Nama Karyawan</th>
			<th width='10%'>Jabatan</th>
		<tr>
		<?PHP
		if($_POST) {
			$afdeling = $_GET['afdeling'];
			$search=$_POST['txtSearch'];
			$query_emp =   "SELECT NIK, EMP_NAME, JOB_CODE
						  FROM T_EMPLOYEE
						 WHERE JOB_CODE IN ('MANDOR PANEN','MANDOR 1')
						 AND (NIK LIKE '%$search%' OR lower(EMP_NAME) LIKE '%$search%')";
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
						<td align='center'><u><a href="javascript:pick('<?= $ar['NIK'] ?>');javascript:pickNAMA('<?= $ar['EMP_NAME'] ?>')"><?= $ar['NIK'] ?></a></u></td>
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
				<td align='center'><u><a href="javascript:pick('<?= $arr['NIK'] ?>');javascript:pickNAMA('<?= $arr['EMP_NAME'] ?>')"><?= $arr['NIK'] ?></a></u></td>
				<td align='center'><?= $arr['EMP_NAME'] ?></td>
				<td align='center'><?= $arr['JOB_CODE'] ?></td>
			</tr>
			<?PHP
		}
	}
}*/

?>		
</TABLE>
</BODY>
</HTML>