<?php
session_start();
include("../include/Header.php");
if (
	isset($_SESSION['Job_Code']) 	&& isset($_SESSION['NIK']) 			&& isset($_SESSION['Name'])
	&& isset($_SESSION['Jenis_Login'])  && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date'])
	&& isset($_SESSION['Comp_Name'])
) {

	$Job_Code 		= $_SESSION['Job_Code'];
	$username 		= $_SESSION['NIK'];
	$Emp_Name 		= $_SESSION['Name'];
	$Jenis_Login 	= $_SESSION['Jenis_Login'];
	$Comp_Name 		= $_SESSION['Comp_Name'];
	$subID_BA_Afd 	= $_SESSION['subID_BA_Afd'];
	$Date 			= $_SESSION['Date'];
	$ID_Group_BA 	= $_SESSION['ID_Group_BA'];

	if ($username == "") {
		$_SESSION[err] = "tolong login dulu!";
		header("location:login.php");
	} else {
		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();

		if (isset($_POST["editNO_BCC"])) {
			$_SESSION["editNO_BCC"] = $_POST["editNO_BCC"];
		}

		if (isset($_SESSION["editNO_BCC"])) {
			$NO_BCC = $_SESSION["editNO_BCC"];
		}

		$sql_t_BCC = "
		SELECT 
			THP.ID_RENCANA,
			THP.NO_BCC, 
			THRP.TANGGAL_RENCANA TANGGAL, 
			F_GET_IDBA_IDR (TDRP.ID_RENCANA,TDRP.ID_BA_AFD_BLOK, TDRP.NO_REKAP_BCC) AS BA, 
			F_GET_IDAFD_IDR (TDRP.ID_RENCANA,TDRP.ID_BA_AFD_BLOK) AS AFD, 
			F_GET_NAMABLOK_IDR(TDRP.ID_RENCANA,TDRP.ID_BA_AFD_BLOK) AS BLOK, 
			THP.NO_TPH, 
			THP.LONGITUDE, 
			THP.LATITUDE, 
			THP.PICTURE_NAME, 
			-- to_char(TTS.START_INS_TIME, 'dd-mm-yyyy HH24:MI:SS') as start_ins_time,
			-- to_char(TTS.END_INS_TIME, 'dd-mm-yyyy HH24:MI:SS') as end_ins_time, 
			-- to_char(TTS.START_UPD_TIME, 'dd-mm-yyyy HH24:MI:SS') as start_upd_time, 
			-- to_char(TTS.END_UPD_TIME, 'dd-mm-yyyy HH24:MI:SS') as end_upd_time,  
			F_GET_IDBA_NIK(THRP.NIK_MANDOR) IDBA_MANDOR, 
			F_GET_IDAFD_NIK(THRP.NIK_MANDOR) IDAFD_MANDOR, 
			THRP.NIK_MANDOR, 
			F_GET_EMPNAME (THRP.NIK_MANDOR) NAMA_MANDOR, 
			F_GET_IDBA_NIK(THRP.NIK_PEMANEN) IDBA_PEMANEN, 
			F_GET_IDAFD_NIK(THRP.NIK_PEMANEN) IDAFD_PEMANEN, 
			THRP.NIK_PEMANEN, F_GET_EMPNAME (THRP.NIK_PEMANEN) NAMA_PEMANEN 
		FROM T_HEADER_RENCANA_PANEN THRP 
			INNER JOIN T_DETAIL_RENCANA_PANEN TDRP ON THRP.ID_RENCANA = TDRP.ID_RENCANA 
			INNER JOIN T_HASIL_PANEN THP on tdrp.id_rencana = thp.id_rencana AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC 
			INNER JOIN T_BLOK TB ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK 
			INNER JOIN T_AFDELING TA ON TB.ID_BA_AFD = TA.ID_BA_AFD 
			INNER JOIN T_BUSSINESSAREA TBA ON TA.ID_BA = TBA.ID_BA
			--inner join T_TIMESTAMP TTS on THP.NO_BCC = REPLACE(ID_TIMESTAMP, '.')
		WHERE THP.NO_BCC = '$NO_BCC' and TA.ID_BA = '$subID_BA_Afd'";
		//echo $sql_t_BCC;die();
		$result_t_BCC = oci_parse($con, $sql_t_BCC);
		oci_execute($result_t_BCC, OCI_DEFAULT);
		while (oci_fetch($result_t_BCC)) {
			$ID_RENCANA				= oci_result($result_t_BCC, "ID_RENCANA");
			$NO_BCC 				= oci_result($result_t_BCC, "NO_BCC");
			$TANGGAL 				= oci_result($result_t_BCC, "TANGGAL");
			$BA 					= oci_result($result_t_BCC, "BA");
			$AFD					= oci_result($result_t_BCC, "AFD");
			$BLOK 					= oci_result($result_t_BCC, "BLOK");
			$NO_TPH 				= oci_result($result_t_BCC, "NO_TPH");
			$START_INS_TIME 		= oci_result($result_t_BCC, "START_INS_TIME");
			$END_INS_TIME 			= oci_result($result_t_BCC, "END_INS_TIME");
			$START_UPD_TIME 		= oci_result($result_t_BCC, "START_UPD_TIME");
			$END_UPD_TIME 			= oci_result($result_t_BCC, "END_UPD_TIME");
			$LONGITUDE 				= oci_result($result_t_BCC, "LONGITUDE");
			$LATITUDE 				= oci_result($result_t_BCC, "LATITUDE");
			$PICTURE_NAME 			= oci_result($result_t_BCC, "PICTURE_NAME");
			$IDBA_MANDOR 			= oci_result($result_t_BCC, "IDBA_MANDOR");
			$IDAFD_MANDOR 			= oci_result($result_t_BCC, "IDAFD_MANDOR");
			$NIK_MANDOR 			= oci_result($result_t_BCC, "NIK_MANDOR");
			$NAMA_MANDOR 			= oci_result($result_t_BCC, "NAMA_MANDOR");
			$IDBA_PEMANEN 			= oci_result($result_t_BCC, "IDBA_PEMANEN");
			$IDAFD_PEMANEN 			= oci_result($result_t_BCC, "IDAFD_PEMANEN");
			$NIK_PEMANEN 			= oci_result($result_t_BCC, "NIK_PEMANEN");
			$NAMA_PEMANEN 			= oci_result($result_t_BCC, "NAMA_PEMANEN");
		}
		$roweffec_BCC = oci_num_rows($result_t_BCC);

		$sql_t_BCC = "
		SELECT 
			to_char(TTS.START_INS_TIME, 'dd-mm-yyyy HH24:MI:SS') as start_ins_time,
			to_char(TTS.END_INS_TIME, 'dd-mm-yyyy HH24:MI:SS') as end_ins_time, 
			to_char(TTS.START_UPD_TIME, 'dd-mm-yyyy HH24:MI:SS') as start_upd_time, 
			to_char(TTS.END_UPD_TIME, 'dd-mm-yyyy HH24:MI:SS') as end_upd_time 
		FROM T_HEADER_RENCANA_PANEN THRP 
			INNER JOIN T_DETAIL_RENCANA_PANEN TDRP ON THRP.ID_RENCANA = TDRP.ID_RENCANA 
			INNER JOIN T_HASIL_PANEN THP on tdrp.id_rencana = thp.id_rencana AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC 
			INNER JOIN T_BLOK TB ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK 
			INNER JOIN T_AFDELING TA ON TB.ID_BA_AFD = TA.ID_BA_AFD 
			INNER JOIN T_BUSSINESSAREA TBA ON TA.ID_BA = TBA.ID_BA
			inner join T_TIMESTAMP TTS on THP.NO_BCC = REPLACE(ID_TIMESTAMP, '.')
		WHERE THP.NO_BCC = '$NO_BCC' and TA.ID_BA = '$subID_BA_Afd'";
		//echo $sql_t_BCC;die();
		$result_t_BCC = oci_parse($con, $sql_t_BCC);
		oci_execute($result_t_BCC, OCI_DEFAULT);
		while (oci_fetch($result_t_BCC)) {
			$START_INS_TIME 		= oci_result($result_t_BCC, "START_INS_TIME");
			$END_INS_TIME 			= oci_result($result_t_BCC, "END_INS_TIME");
			$START_UPD_TIME 		= oci_result($result_t_BCC, "START_UPD_TIME");
			$END_UPD_TIME 			= oci_result($result_t_BCC, "END_UPD_TIME");
		}
		$roweffec_BCC = oci_num_rows($result_t_BCC);



		$sql_t_BCC = "
		SELECT INSERTUPDATE,
				TO_CHAR (min(TLHPK.SYNC_SERVER), 'dd-mm-yyyy HH24:MI:SS')
				  AS START_TIME,
				TO_CHAR (max(TLHPK.SYNC_SERVER), 'dd-mm-yyyy HH24:MI:SS')
				  AS END_TIME
		  FROM T_HEADER_RENCANA_PANEN THRP
			   INNER JOIN T_DETAIL_RENCANA_PANEN TDRP
				  ON THRP.ID_RENCANA = TDRP.ID_RENCANA
			   INNER JOIN T_HASIL_PANEN THP
				  ON tdrp.id_rencana = thp.id_rencana
					 AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC
			   INNER JOIN T_BLOK TB
				  ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
			   INNER JOIN T_AFDELING TA
				  ON TB.ID_BA_AFD = TA.ID_BA_AFD
			   INNER JOIN T_BUSSINESSAREA TBA
				  ON TA.ID_BA = TBA.ID_BA
			   INNER JOIN T_LOG_HASILPANEN_KUALITAS TLHPK
				  ON TLHPK.ON_ID_BCC_KUALITAS LIKE  '$NO_BCC%'
		 WHERE THP.NO_BCC = '$NO_BCC'
		  and TA.ID_BA = '$subID_BA_Afd'
		 group by INSERTUPDATE order by INSERTUPDATE";
		$result_t_BCC = oci_parse($con, $sql_t_BCC);
		oci_execute($result_t_BCC, OCI_DEFAULT);
		while (oci_fetch($result_t_BCC)) {
			if (oci_result($result_t_BCC, "INSERTUPDATE") == 'INSERT') {
				if ($START_INS_TIME == '' && $END_INS_TIME == '') {
					$START_INS_TIME 		= oci_result($result_t_BCC, "START_TIME");
					$END_INS_TIME 			= oci_result($result_t_BCC, "END_TIME");
				}
			} else if (oci_result($result_t_BCC, "INSERTUPDATE") == 'UPDATE') {
				if ($START_UPD_TIME == '' && $END_UPD_TIME == '') {
					$START_UPD_TIME 		= oci_result($result_t_BCC, "START_TIME");
					$END_UPD_TIME 			= oci_result($result_t_BCC, "END_TIME");
				}
			}
		}
		$roweffec_BCC = oci_num_rows($result_t_BCC);
	}

	?>
	<link href="tabel.css" rel="stylesheet" type="text/css" />
	<script src="../js/jquery.js"></script>
	<script language="javascript" type="text/javascript">
		function tablePrint() {

			var display_setting = "toolbar=no,location=no,directories=no,menubar=no,";
			display_setting += "scrollbars=yes,width=750, height=600, left=100, top=25";

			var content_innerhtml = document.getElementById("tbl_display").innerHTML;
			var document_print = window.open("", "", display_setting);
			document_print.document.open();
			document_print.document.write('<html><head><title>DRadio </title></head>');
			//document_print.document.write('<body  onLoad="self.print();self.close();" >');
			document_print.document.write(content_innerhtml);
			document_print.document.write('</body></html>');
			document_print.print();
			//document_print.document.close();
			return false;
		}

		//Added by Ardo 16-08-2016 : Synchronize BCC to SAP - Laporan
		function print_save(id_rencana, no_bcc) {
			window.print();
			//alert(id_rencana+' '+no_bcc);
			$(document).ready(function() {
				$.ajax({
					type: "POST",
					url: "save.php",
					data: "id_rencana=" + id_rencana + "&no_bcc=" + no_bcc,
					success: function(data) {

					}
				});
			});
		}
	</script>

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

		body,
		td,
		th {
			font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			font-size: 16px;
			font-weight: normal;
		}
	</style>

	<table style="margin-to" style="margin-top:10px;">


		<table width=500 style="margin-top:40px;">
			<tr align="center">
				<td colspan="5" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>LAPORAN SAMPLING EBCC vs EBCC</strong></span><br></td>
			</tr>
			<tr align="center">
				<td>PT: ;</td>
				<td>Bisnis Area: ;</td>
				<td>AFD: ;</td>
				<td>BLOCK: ;</td>
				<td>TPH: </td>
			</tr>
		</table>
		<table style="margin-top:10px;">

			<tr height=" 50">
				<td colspan="8" bgcolor="orange" align="center"><b>SAMPLING EBCC</b></td>
				<td colspan="8" bgcolor="#9CC346" align="center"><b>EBCC</b></td>
			</tr>
			<tr class="">
				<td colspan="8" width="300" height="300"></td>
				<td colspan="8" width="300" height="300"></td>
			</tr>
			<tr>
				<td colspan="8">
					<table align="center" border="2">
						<tr align="center">
							<td width="50px"> <b>BM (jjg)</b> </td>
							<td width="50px"> <b>BK (jjg)</b> </td>
							<td width="50px"> <b>MS (jjg)</b> </td>
							<td width="50px"> <b>OR (jjg)</b> </td>
							<td width="50px"> <b>BB (jjg)</b> </td>
							<td width="50px"> <b>JK (jjg)</b> </td>
							<td width="50px"> <b>BA (jjg)</b> </td>
							<td width="50px"> <b>Total Jenjang Panen</b> </td>
						</tr>
						<tr align="center">
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</table>
				</td>
				<td colspan="8">
					<table align="center" border="2">
						<tr align="center">
							<td width="50px"> <b>BM (jjg)</b> </td>
							<td width="50px"> <b>BK (jjg)</b> </td>
							<td width="50px"> <b>MS (jjg)</b> </td>
							<td width="50px"> <b>OR (jjg)</b> </td>
							<td width="50px"> <b>BB (jjg)</b> </td>
							<td width="50px"> <b>JK (jjg)</b> </td>
							<td width="50px"> <b>BA (jjg)</b> </td>
							<td width="50px"> <b>Total Jenjang Panen</b> </td>
						</tr>
						<tr align="center">
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</table>
				</td>



			</tr>
			<tr>
				<td colspan="16">
					<table align="center" id="setbody2">

						<tr>
							<td width="117" valign="top">NIK</td>
							<td width="373" valign="top"></td>
							<td width="228" rowspan="6">Foto</td>
							<td width="128" valign="top">NIK</td>
							<td width="278" valign="top"></td>
							<td width="228" rowspan="6">Foto</td>
						</tr>
						<tr>
							<td width="228" valign="top">Nama</td>
							<td width="378" valign="top"></td>
							<td width="228" valign="top">Nama</td>
							<td width="378" valign="top"></td>
						</tr>
						<tr>
							<td width="228" valign="top">Jabatan</td>
							<td width="378" valign="top"></td>
							<td width="228" valign="top">Jabatan</td>
							<td width="378" valign="top"></td>
						</tr>
						<tr>
							<td width="228" valign="top">Waktu Pencatatan</td>
							<td width="378" valign="top"></td>
							<td width="228" valign="top">Waktu Pencatatan</td>
							<td width="378" valign="top"></td>
						</tr>
						<tr>
							<td width="228" valign="top">Kode EBCC</td>
							<td width="378" valign="top"></td>
							<td width="228" valign="top">Kode EBCC</td>
							<td width="378" valign="top"></td>
						</tr>
						<tr>
							<td width="228" valign="top">Status QR Code</td>
							<td width="378" valign="top"></td>
							<td width="228" valign="top">Status QR Code</td>
							<td width="378" valign="top"></td>
						</tr>
					</table>
			<tr>
				<td colspan="16">
					<table align="left">

						<tr>
							<td colspan="3"> <u><b>Keterangan:</b></u> </td>
						</tr>
						<tr>
							<td>BM</td>
							<td>:</td>
							<td>a.Mentah</td>
						</tr>
						<tr>
							<td>BK</td>
							<td>:</td>
							<td>b.Mangkal/Kurang Masak</td>
						</tr>
						<tr>
							<td>MS</td>
							<td>:</td>
							<td>c.Masak</td>
						</tr>
						<tr>
							<td>OR</td>
							<td>:</td>
							<td>d.Overripe</td>
						</tr>
						<tr>
							<td>BB</td>
							<td>:</td>
							<td>e.Busuk</td>
						</tr>
						<tr>
							<td>JK</td>
							<td>:</td>
							<td>j.Jenjang Kosong</td>
						</tr>
						<tr>
							<td>BA</td>
							<td>:</td>
							<td>g.Buah Aborsi</td>
						</tr>
					</table>
				</td>
			</tr>
			</td>
			</tr>
		</table>

		<table align="center" id="setbody2">

			<tr>
				<th align="center"><?php include("../include/Footer.php") ?></th>
			</tr>
		</table>
		</tr>
		</tr>

	</table>


<?php
} else {
	$_SESSION[err] = "tolong login dulu!" . $Job_Code . "<br>" . $username . "<br>" . $Emp_Name . "<br>" . $Jenis_Login . "<br>" . $subID_BA_Afd;
	header("location:../index.php");
}
?>