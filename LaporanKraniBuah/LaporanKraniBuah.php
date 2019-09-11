<?php
session_start();
include("../include/Header.php");

if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['Jenis_Login']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name']) && isset($_SESSION['subID_CC'])) {
	$Job_Code 		= $_SESSION['Job_Code'];
	$username 		= $_SESSION['NIK'];
	$Emp_Name 		= $_SESSION['Name'];
	$Jenis_Login 	= $_SESSION['Jenis_Login'];
	$Comp_Name 		= $_SESSION['Comp_Name'];
	$subID_BA_Afd 	= $_SESSION['subID_BA_Afd'];
	$Date 			= $_SESSION['Date'];
	$ID_Group_BA 	= $_SESSION['ID_Group_BA'];
	$subID_CC 		= $_SESSION['subID_CC'];
	$sComp_Name 	= $_SESSION['Comp_Name'];

	if ($username == "") {
		$_SESSION[err] = "tolong login dulu!";
		header("location:../index.php");
	} else {
		header("location:../menu/authoritysecure.php");
	}
} else {
	$_SESSION[err] = "tolong login dulu!";
	header("location:../index.php");
}
?>

<script type="text/javascript" src="../datepicker/js/jquery.min.js"></script>
<script type="text/javascript" src="../datepicker/js/pa.js"></script>
<script type="text/javascript" src="../datepicker/datepicker/ui.core.js"></script>
<script type="text/javascript" src="../datepicker/datepicker/ui.datepicker.js"></script>
<script type="text/javascript" src="jquery.redirect.js"></script>

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
	body,td,th { font-family: "Trebuchet MS", Arial, Helvetica, sans-serif; font-size: 16px; font-weight:normal; }
	tbody#scrolling { width: 1100px; height: 300px; overflow: auto; display: block; }
</style>

<form method="post" action="CetakPDFLaporanKraniBuah.php" id="krani-buah-form" target="_blank">
	<table width="80%" border="0" align="center" style="margin-top:45px;">
		<tr>
			<td colspan="8" style="padding-bottom:10px;">
				<span style="font:bold; font-size:20px; font-weight: bold;"><strong>LAPORAN HARIAN KRANI BUAH</strong></span>
			</td>
		</tr>
		<tr>
			<td colspan="6" style="padding-top:10px;padding-bottom:10px;border-bottom:3px solid black;">LOKASI</td>
			<td colspan="2" style="border-bottom:3px solid black;">PERIODE</td>
		</tr>
		<tr>
			<td style="padding-top:5px;padding-bottom:5px;">Company Code</td>
			<td style="padding-top:5px;padding-bottom:5px;">
				: <input name="CClabel" type="text" id="CClabel" value="<?=$subID_CC?>" style="background-color:#CCC; width: 100px; height:25px; font-size:15px; display:inline" />
			</td>
			<td>Business Area</td>
			<td>
				: <input type="text" name="BA" id="BA" readonly="readonly" value="<?=$subID_BA_Afd?>" style="background-color:#CCC; width: 145px; visibility: visible; font-size: 15px; height: 25px;">
			</td>
			<td>Blok</td>
			<td>
				: <select name="BLOK" id="BLOK" style="visibility:visible; font-size: 15px; height: 25px; width:150px;"></select>
			</td>
			<td>Start Date</td>
			<td>
				: <input type="text" name="date1" id="datepicker" class="box_field" readonly="readonly" style="width:120px; height:25px; font-size:15px; display:inline;">
			</td>
		</tr>
		<tr>
			<td style="padding-top:5px;padding-bottom:5px;">Company Name</td>
			<td style="padding-top:5px;padding-bottom:5px;">
				: <input name="Comp_Name" type="text" id="Comp_Name" value="<?=$sComp_Name?>" style="background-color:#CCC; width: 250px; height:25px; font-size:15px" />
			</td>
			<td>Afdeling</td>
			<td>
				: <select name="AFD" id="AFD" style="visibility:visible; font-size: 15px; height: 25px; width:150px;"></select>
			</td>
			<td colspan="2"></td>
			<td>End Date</td>
			<td>
				: <input type="text" name="date2" id="datepicker2" class="box_field" readonly="readonly" style="width:120px; height:25px; font-size:15px; display:inline;">
			</td>
		</tr>
		<tr>
			<td colspan="8" style="padding-top:10px;padding-bottom:5px;border-top:3px solid black;">
				<input type="submit" value="TAMPILKAN" style="width:200px; font-size:16px;">
				<!--<input type="submit" value="CETAK" style="width:200px; height: 50px; font-size:16px;">-->
			</td>
		</tr>
	</table>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		$('#datepicker2').datepicker({
			changeMonth: true,
			changeYear: true
		});

		var comp = '<?php echo $_SESSION['subID_CC']; ?>';
		var ba = $('#BA').val();
		$.ajax({
			type: 'POST',
			url: 'getBaKraniBuah.php',
			dataType: 'json',
			data: { comp:comp, ba:ba },
			success: function(data) {
				$('#BA').html(data);

				var ba = $('#BA').val();
				var afd = $('#AFD').val();

				$.ajax({
					type: 'POST',
					url: 'getAfdKraniBuah.php',
					dataType: 'json',
					data: { ba:ba, afd:afd },
					success: function(data) {
						$('#AFD').html(data);

						var ba = $('#BA').val();
						var afd = $('#AFD').val();
						$.ajax({
							type: 'POST',
							url: 'getBlokKraniBuah.php',
							dataType: 'json',
							data: { ba:ba, afd:afd },
							success: function(data) {
								$('#BLOK').html(data);
							}
						});
					}
				});
			}
		});

		$('#BA').change(function() {
			var ba = $('#BA').val();
			var afd = $('#AFD').val();
			$.ajax({
				type: 'POST',
				url: 'getAfdKraniBuah.php',
				dataType: 'json',
				data: { ba:ba, afd:afd },
				success: function(data) {
					$('#AFD').html(data);

					var ba = $('#BA').val();
					var afd = $('#AFD').val();
					$.ajax({
						type: 'POST',
						url: 'getBlokKraniBuah.php',
						dataType: 'json',
						data: { ba:ba, afd:afd },
						success: function(data) {
							$('#BLOK').html(data);
						}
					})
				}
			});
		}).change();

		$('#AFD').change(function() {
			var ba = $('#BA').val();
			var afd = $('#AFD').val();
			$.ajax({
				type: 'POST',
				url: 'getBlokKraniBuah.php',
				dataType: 'json',
				data: { ba:ba, afd:afd },
				success: function(data) {
					$('#BLOK').html(data);
				}
			});
		}).change();

		$('#krani-buah-submit').click(function() {
			var CClabel = $('#CClabel').val();
			var BA = $('#BA').val();
			var BLOK = $('#BLOK').val();
			var date1 = $('#date1').val();
			var Comp_Name = $('#Comp_Name').val();
			var AFD = $('#AFD').val();
			var date2 = $('#date2').val();

			$.ajax({
				url: 'CetakLaporanKraniBuah.php',
				type: 'POST',
				dataType: 'json',
				data: {
					CClabel: CClabel,
					BA: BA,
					BLOK: BLOK,
					date1: date1,
					Comp_Name: Comp_Name,
					AFD: AFD,
					date2: date2
				},
				success: function(data) {
					if (data.length == '0') {
						alert(data.output);
						return false;
					} else {
						jQuery('#krani-buah-form').submit();
					}
				}
			})
		});
	});
</script>

<?php include("../include/Footer.php") ?>