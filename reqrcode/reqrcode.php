<?php
session_start();
include("../include/Header.php");

?>

<!-- LIBRARY JQUERY -->
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#werks').on('change', function() {
			var werks = $('#werks').val();
			if (werks == '') {
				alert('Pilih Company Name');
			} else {
				$.ajax({
					method: 'POST',
					url: 'qrcode-afd.php',
					data: {
						werks: werks
					},
					cache: false,
					success: function(data) {
						$('#afd').html(data);
					}
				});
			}
		});

		$('#afd').on('change', function() {
			var werks = $('#werks').val();
			var afd = $('#afd').val();
			if (werks == '') {
				alert('Pilih Company Name');
			} else {
				$.ajax({
					method: 'POST',
					url: 'qrcode-blok.php',
					data: {
						werks: werks,
						afd: afd
					},
					cache: false,
					success: function(data) {
						console.log(data);
						$('#blok').html(data);
					}
				})
			}
		});
		
		$('#blok').on('change', function() {
			var werks = $('#werks').val();
			var afd = $('#afd').val();
			var blok = $('#blok').val();
			if (werks == '') {
				alert('Pilih Company Name');
			} else {
				$.ajax({
					method: 'POST',
					url: 'list-data.php',
					data: {
						werks: werks,
						afd: afd,
						blok: blok
					},
					cache: false,
					success: function(data) {
						console.log(data);
						$('#tbl_list').html(data);
					}
				})
			}
		});
	});
	
	function generate_code(block, blockname, tph){
		var werks = $('#werks').val();
		var afd = $('#afd').val();
			$.ajax({
				method: 'POST',
				url: 'qrcode-insert.php',
				data: {
					werks : werks,
					block : block,
					blok_name : blockname,
					tph : tph,
					afd : afd
				},
				cache: false,
				success: function(data) {
					alert ('QRCODE berhasil digenerate.');	
					$('#tr'+blockname+tph).remove();
					window.open(data);
					//PrintImage(data)
				}
			})
	};
	
	/*function ImagetoPrint(source) {
		return "<html><head><script>function step1(){\n" +
				"setTimeout('step2()', 10);}\n" +
				"function step2(){window.print();window.close()}\n" +
				"</scri" + "pt></head><body onload='step1()'>\n" +
				"<img src='" + source + "' width='15cm' height='10cm' height/></body></html>";
	}
	
	function PrintImage(source) {
		Pagelink = "about:blank";
		var pwa = window.open(Pagelink, "_new");
		pwa.document.open();
		pwa.document.write(ImagetoPrint(source));
		pwa.document.close();
	}*/
</script>

<?php
include("../config/SQL_function.php");
include("../config/db_config.php");
$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');

include('../config/dw_tap_config.php');
if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['Jenis_Login']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name'])&& isset($_SESSION['subID_CC'])) {
	$Job_Code = $_SESSION['Job_Code'];
	$username = $_SESSION['NIK'];
	$Emp_Name = $_SESSION['Name'];
	$Jenis_Login = $_SESSION['Jenis_Login']; //echo "Jenis_Login: $Jenis_Login";
	$Comp_Name = $_SESSION['Comp_Name'];
	$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
	$subID_CC=$_SESSION['subID_CC'];
	$Date = $_SESSION['Date'];

	if($username == "") {
		$_SESSION[err] = "tolong login dulu!";
		header("location:login.php");
	}

	// close secure user matriks
	$RegisterDevice = "";
	if(isset($_POST["RegisterDevice"])) {
		$RegisterDevice = $_POST["RegisterDevice"];
		$_SESSION["RegisterDevice"] = $RegisterDevice;
	}
	if(isset($_SESSION["RegisterDevice"])) {
		$RegisterDevice = $_SESSION["RegisterDevice"];
	}

	if($RegisterDevice == TRUE) {
		$Nama_TM1	= "";
		$Nama_TM2	= "";
		$NIK_TM1	= "";
		$NIK_TM2	= "";
		$sql_bcc_restan  ="";
		if($NIK_TM1 == "") {
			$pagesize = 15;
			if($Jenis_Login==9) {
				$sql_bcc_restan  = "SELECT  ID_DEV, ID_BA, ID_CC, MERK, TIPE, IMEI, NIK1, F_GET_EMPNAME(NIK1) AS NAMA, NIK2 FROM T_DEVICE WHERE  STA_DEV='Y'";
			} else {
				$sql_bcc_restan  = "SELECT  ID_DEV, ID_BA, ID_CC, MERK, TIPE, IMEI, NIK1, F_GET_EMPNAME(NIK1) AS NAMA, NIK2 FROM T_DEVICE WHERE ID_BA='$subID_BA_Afd' AND STA_DEV='Y'";
			}

			$result_t_bcc_restan = oci_parse($con, $sql_bcc_restan);
			oci_execute($result_t_bcc_restan, OCI_DEFAULT);
			while(oci_fetch($result_t_bcc_restan)) {
				$ID_DEV[] 			= oci_result($result_t_bcc_restan, "ID_DEV");
				$ID_BA[] 			= oci_result($result_t_bcc_restan, "ID_BA");
				$ID_CC[] 			= oci_result($result_t_bcc_restan, "ID_CC");
				$MERK[] 		    = oci_result($result_t_bcc_restan, "MERK");
				$TIPE[] 			= oci_result($result_t_bcc_restan, "TIPE"); 
				$IMEI[]   			= oci_result($result_t_bcc_restan, "IMEI"); 
				$NIK1[]   			= oci_result($result_t_bcc_restan, "NIK1");
				$NIK2[]   			= oci_result($result_t_bcc_restan, "NIK2");
				$NAMA[]   			= oci_result($result_t_bcc_restan, "NAMA");
			}
			$rowBCCRestan = oci_num_rows($result_t_bcc_restan);
			$totalpage = ceil($rowBCCRestan/$pagesize);
			$setPage = $totalpage - 1;
			//echo "DALAM IF: ".$sql_bcc_restan;
			//echo "row: ".$rowBCCRestan." ".$totalpage." - ".$setPage;
		} else {
			$totalpage = 0;
			$rowBCCRestan  = "";
			//echo "ELSE: ".$sql_bcc_restan;
		}

		if(isset($_SESSION["Cpage"])) {
			$sesPage = $_SESSION["Cpage"];
		} else {
			$sesPage = 0;
		}

		if(isset($_GET["page"])) {
			$OnPage = $_GET["page"];
			$CPage = 1;
			if($OnPage == "next") {
				$sesPageres = $sesPage + $CPage;
				if($sesPageres >= $setPage) {
					$sesPageres = $setPage;
				}
				$calPage = $sesPageres * $pagesize;
				$_SESSION["Cpage"]  = $sesPageres; 
			} else if($OnPage == "back") {
				$sesPageres = $sesPage - $CPage;
				if($sesPageres <= 0) {
					$sesPageres = 0;
				}
				$calPage = $sesPageres * $pagesize;
				$_SESSION["Cpage"]  = $sesPageres;
			} else {
				$calPage = 0;
			}
		} else {
			$CPage = 0;
			$sesPageres = $sesPage + $CPage;
			$calPage = $sesPageres * $pagesize;
			$_SESSION["Cpage"]  = $sesPageres;
		}
	}
}
?>


<style type="text/css">
a:link { text-decoration: none; }
a:visited { text-decoration: none; }
a:hover { text-decoration: none; }
a:active { text-decoration: none; }
body,td,th { font-family: "Trebuchet MS", Arial, Helvetica, sans-serif; font-size: 16px; font-weight:normal; }
</style>

<form method="POST" action="qrcode-insert.php">
<table width="85%" style="margin-top:30px;">
	<tr>
		<td><strong style="font-size:20px;">GENERATE QR CODE</strong></td>
	</tr>

	<?php if (isset($_SESSION['print_pdf']) && !empty($_SESSION['print_pdf']) && $_SESSION['print_pdf'] == 'Success') : ?>
	<tr>
		<td><strong style="color:green;">Generate QR Code Success <br />Lokasi QR Code : <a href="file:/\/\/fs.tap-agri.com\QRcode">\\fs.tap-agri.com\QRcode</a></strong></td>
	</tr>
	<?php unset($_SESSION['print_pdf']); ?>
	<?php endif; ?>
	<tr>
		<td>
			<table style="margin-top:20px;">
				<tr>
					<td>Estate</td>
					<td>: </td>
					<td>
						<select name="werks" id="werks">
							<option value="">--- Pilih ---</option>
							<?php
								$stid = oci_parse($cons, "SELECT WERKS, EST_NAME FROM TAP_DW.TM_EST GROUP BY WERKS, EST_NAME ORDER BY WERKS");
								oci_execute($stid);

								while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
									echo '<option value="'.$row['WERKS'].'">'.$row['WERKS'].' - ' .$row['EST_NAME']. '</option>';
								}
								oci_free_statement($stid);
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td style="padding-top:10px;">Afdeling</td>
					<td style="padding-top:10px;">: </td>
					<td style="padding-top:10px;">
						<select name="afd" id="afd">
							<option value="">--- Pilih ---</option>
						</select>
					</td>
				</tr>
				<tr>
					<td style="padding-top:10px;">Blok</td>
					<td style="padding-top:10px;">: </td>
					<td style="padding-top:10px;">
						<select name="blok" id="blok">
							<option value="">--- Pilih ---</option>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php if (isset($count) && $count > 0) : ?>
	<tr>
		<td style="padding-top:20px;">
			<font color="green"><i>QR Code TPH Berhasil Digenerate.</i></font>
		</td>
	</tr>
	<?php endif; ?>
	<tr>
		<td>
			<table style="margin-top:20px; width:45%; border:1px solid black;">
				<thead>
					<tr style="text-align:left;">
						<th style="width:30%;margin: 10px;padding: 10px;border-bottom: 1px solid black;">Tanggal</th>
						<th style="width:30%;margin: 10px;padding: 10px;border-bottom: 1px solid black;">Kode Blok</th>
						<th style="width:30%;margin:10px; padding:10px; border-bottom:1px solid black;">Nama Blok</th>
						<th style="width:30%;margin:10px; padding:10px; border-bottom:1px solid black;">TPH</th>
						<th style="border-bottom:1px solid black;">Action</th>
					</tr>
				</thead>
				<tbody id="tbl_list">
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<th align="center"><?php include("../include/Footer.php") ?></th>
	</tr>
</table>
</form>
