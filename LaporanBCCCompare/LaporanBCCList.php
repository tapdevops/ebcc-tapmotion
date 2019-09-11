<?php
session_start();
include("../include/Header.php");
if (isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['Jenis_Login']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name'])) {
	$Job_Code = $_SESSION['Job_Code'];
	$username = $_SESSION['NIK'];
	$Emp_Name = $_SESSION['Name'];
	$Jenis_Login = $_SESSION['Jenis_Login'];
	$Comp_Name = $_SESSION['Comp_Name'];
	$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
	$Date = $_SESSION['Date'];
	$ID_Group_BA = $_SESSION['ID_Group_BA'];

	if ($username == "") {
		$_SESSION[err] = "tolong login dulu!";
		header("location:../index.php");
	} else {

		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();

		$sql_user_login  = 	"select c.id_cc as COMPANY_CODE, c.id_ba as BUSINESS_AREA, d.comp_name AS COMPANY_NAME
							from t_employee a, t_afdeling b, t_bussinessarea c, t_companycode d
							where a.id_ba_afd = b.id_ba_afd and b.id_ba = c.id_ba and c.id_cc = d.id_cc 
							and a.nik = '$username'";
		$result_user_login	= select_data($con, $sql_user_login);
		$company_code		= $result_user_login["COMPANY_CODE"];
		$business_area		= $result_user_login["BUSINESS_AREA"];
		$company_name		= $result_user_login["COMPANY_NAME"];


		if (isset($_SESSION["sql_t_BCC"])) {
			$pagesize = 10;

			$sql_t_BCC = $_SESSION["sql_t_BCC"];
			$result_t_BCC = oci_parse($con, $sql_t_BCC);
			oci_execute($result_t_BCC, OCI_DEFAULT);
			while (oci_fetch($result_t_BCC)) {
				$VAL_DATE_TIME[] 					= oci_result($result_t_BCC, "VAL_DATE_TIME");
				$VAL_NIK_VALIDATOR[] 				= oci_result($result_t_BCC, "VAL_NIK_VALIDATOR");
				$VAL_NAMA_VALIDATOR[] 				= oci_result($result_t_BCC, "VAL_NAMA_VALIDATOR");
				$VAL_JABATAN_VALIDATOR[] 			= oci_result($result_t_BCC, "VAL_JABATAN_VALIDATOR");
				$VAL_WERKS[] 						= oci_result($result_t_BCC, "VAL_WERKS");
				$VAL_AFD_CODE[] 					= oci_result($result_t_BCC, "VAL_AFD_CODE");
				$VAL_BLOCK_CODE[] 					= oci_result($result_t_BCC, "VAL_BLOCK_CODE");
				$VAL_BLOCK_NAME[] 					= oci_result($result_t_BCC, "VAL_BLOCK_NAME");
				$VAL_EBCC_CODE[] 					= oci_result($result_t_BCC, "VAL_EBCC_CODE");
				$VAL_TPH_CODE[] 					= oci_result($result_t_BCC, "VAL_TPH_CODE");
				$VAL_DELIVERY_TICKET[] 				= oci_result($result_t_BCC, "VAL_DELIVERY_TICKET");
				$VAL_JML_BM[] 						= oci_result($result_t_BCC, "VAL_JML_BM");
				$VAL_JML_BK[] 						= oci_result($result_t_BCC, "VAL_JML_BK");
				$VAL_JML_MS[] 						= oci_result($result_t_BCC, "VAL_JML_MS");
				$VAL_JML_OR[] 						= oci_result($result_t_BCC, "VAL_JML_OR");
				$VAL_JML_BB[] 						= oci_result($result_t_BCC, "VAL_JML_BB");
				$VAL_JML_JK[] 						= oci_result($result_t_BCC, "VAL_JML_JK");
				$VAL_JML_BA[] 						= oci_result($result_t_BCC, "VAL_JML_BA");
				$VAL_JML_BRD[] 						= oci_result($result_t_BCC, "VAL_JML_BRD");
				$VAL_JJG_PANEN[] 					= oci_result($result_t_BCC, "VAL_JJG_PANEN");
				$EBCC_ID_RENCANA[] 					= oci_result($result_t_BCC, "EBCC_ID_RENCANA");
				$EBCC_NO_BCC[] 						= oci_result($result_t_BCC, "EBCC_NO_BCC");
				$EBCC_WERKS[] 						= oci_result($result_t_BCC, "EBCC_WERKS");
				$EBCC_NIK_KERANI_BUAH[] 			= oci_result($result_t_BCC, "EBCC_NIK_KERANI_BUAH");
				$EBCC_NAMA_KERANI_BUAH[] 			= oci_result($result_t_BCC, "EBCC_NAMA_KERANI_BUAH");
				$EBCC_JABATAN_KERANI_BUAH[] 		= oci_result($result_t_BCC, "EBCC_JABATAN_KERANI_BUAH");
				$EBCC_DATE_TIME[] 					= oci_result($result_t_BCC, "EBCC_DATE_TIME");
				$EBCC_AFD_CODE[] 					= oci_result($result_t_BCC, "EBCC_AFD_CODE");
				$EBCC_BLOCK_CODE[] 					= oci_result($result_t_BCC, "EBCC_BLOCK_CODE");
				$EBCC_TPH_CODE[] 					= oci_result($result_t_BCC, "EBCC_TPH_CODE");
				$EBCC_JML_BM[] 						= oci_result($result_t_BCC, "EBCC_JML_BM");
				$EBCC_JML_BK[] 						= oci_result($result_t_BCC, "EBCC_JML_BK");
				$EBCC_JML_MS[] 						= oci_result($result_t_BCC, "EBCC_JML_MS");
				$EBCC_JML_OR[] 						= oci_result($result_t_BCC, "EBCC_JML_OR");
				$EBCC_JML_BB[] 						= oci_result($result_t_BCC, "EBCC_JML_BB");
				$EBCC_JML_JK[] 						= oci_result($result_t_BCC, "EBCC_JML_JK");
				$EBCCJML_BA[] 						= oci_result($result_t_BCC, "EBCCJML_BA");
				$EBCC_JML_BRD[] 					= oci_result($result_t_BCC, "EBCC_JML_BRD");
				$EBCC_JJG_PANEN[] 					= oci_result($result_t_BCC, "EBCC_JJG_PANEN");

				//Added by Ardo 16-08-2016 : Synchronize BCC - Laporan
				$STATUS_EXPORT[]			= oci_result($result_t_BCC, "STATUS_EXPORT");
			}
			$roweffec_BCC = oci_num_rows($result_t_BCC);

			if ($roweffec_BCC > 0) {
				$totalpage = ceil($roweffec_BCC / $pagesize);
				$setPage = $totalpage - 1;
				//echo "totalpage".$totalpage;	
			} else {
				$totalpage = 0;
				$roweffec_BCC  = "";
				//echo "roweffec_BCC".$roweffec_BCC;
			}

			if (isset($_SESSION["Cpage"])) {
				$sesPage = $_SESSION["Cpage"];
			} else {
				$sesPage = 0;
			}

			if (isset($_GET["page"])) {
				$OnPage = $_GET["page"];
				$CPage = 1;
				if ($OnPage == "next") {
					$sesPageres = $sesPage + $CPage;
					if ($sesPageres >= $setPage) {
						$sesPageres = $setPage;
					}
					$calPage = $sesPageres * $pagesize;
					$_SESSION["Cpage"]  = $sesPageres;
				} else if ($OnPage == "back") {
					$sesPageres = $sesPage - $CPage;
					if ($sesPageres <= 0) {
						$sesPageres = 0;
					}
					$calPage = $sesPageres * $pagesize;
					$_SESSION["Cpage"]  = $sesPageres;
				} else if ($OnPage == "first") {
					$sesPageres = 0;
					if ($sesPageres <= 0) {
						$sesPageres = 0;
					}
					$calPage = $sesPageres * $pagesize;
					$_SESSION["Cpage"]  = $sesPageres;
				} else if ($OnPage == "last") {
					$sesPageres = $totalpage;
					if ($sesPageres >= $setPage) {
						$sesPageres = $setPage;
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
		} else {
			$_SESSION[err] = "Please check input value!";
			header("location:KoreksiBCCFil.php");
		}
	}

	?>

<?php
} else {
	$_SESSION[err] = "Please Login!";
	header("location:../index.php");
}
?>

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
<table width="2000" height="390" border="0" align="center">
	<!--<tr bgcolor="#C4D59E">-->
	<tr>
		<th height="197" scope="row" align="center">
			<table width="937" border="0" id="setbody2">
				<tr>
					<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>LAPORAN BCC COMPARE</strong></span></td>
					<td width="619" align="right">
						<a href="printXLS.php">
							<input type="submit" name="button" id="button" value="DOWNLOAD TO XLS" style="width:200px; height: 30px; font-size:16px; visibility:<?= $visisub ?>" onclick="formSubmit(1)" />
						</a>
					</td>
				</tr>
				<tr>
				<tr>
					<td width="130" height="29" valign="top">Company Code</td>
					<td width="7" height="29" valign="top">:</td>
					<td width="355" align="left" valign="top"><input name="company_code" type="text" id="company_code" value="<?= $company_code ?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly="readonly" />
					</td>
				</tr>
				<tr>
					<td width="130" height="29" valign="top">Business Area</td>
					<td width="7" height="29" valign="top">:</td>
					<td width="355" align="left" valign="top"><input name="business_area" type="text" id="business_area" value="<?= $business_area ?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly="readonly" />
					</td>
				</tr>
				<tr>
					<td width="130" height="29" valign="top">Company Name</td>
					<td width="7" height="29" valign="top">:</td>
					<td width="355" align="left" valign="top"><input name="company_name" type="text" id="company_name" value="<?= $company_name ?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly="readonly" />
					</td>
				</tr>

				<tr>
					<td colspan="4" valign="top">
						<table width="3000" border="0">

							<tr bgcolor="#9CC346">
								<td width="44" align="center" style="font-size:14px" id="bordertable">No.</td>
								<td width="250" align="center" style="font-size:14px" id="bordertable">Tanggal </td>
								<td width="350" align="center" style="font-size:14px" id="bordertable">NIK Pembuat</td>
								<td width="350" align="center" style="font-size:14px" id="bordertable">Nama Pembuat</td>
								<td width="350" align="center" style="font-size:14px" id="bordertable">Jabatan Pembuat</td>
								<td width="250" align="center" style="font-size:14px" id="bordertable">Kode BA</td>
								<td width="250" align="center" style="font-size:14px" id="bordertable">Business Area</td>
								<td width="250" align="center" style="font-size:14px" id="bordertable">Kode AFD</td>
								<td width="250" align="center" style="font-size:14px" id="bordertable">Kode Block</td>
								<td width="250" align="center" style="font-size:14px" id="bordertable">Block Desrikpsi</td>
								<td width="250" align="center" style="font-size:14px" id="bordertable">TPH</td>
								<td width="350" align="center" style="font-size:14px" id="bordertable">Kode Sampling EBCC</td>
								<td width="250" align="center" style="font-size:14px" id="bordertable">Status QR Code TPH</td>
								<td width="100" align="center" style="font-size:14px" id="bordertable">BM (jjg)</td>
								<td width="100" align="center" style="font-size:14px" id="bordertable">Bk (jjg)</td>
								<td width="100" align="center" style="font-size:14px" id="bordertable">MS (jjg)</td>
								<td width="100" align="center" style="font-size:14px" id="bordertable">OR (jjg)</td>
								<td width="100" align="center" style="font-size:14px" id="bordertable">BB (jjg)</td>
								<td width="100" align="center" style="font-size:14px" id="bordertable">JK (jjg)</td>
								<td width="100" align="center" style="font-size:14px" id="bordertable">BA (jjg)</td>
								<td width="100" align="center" style="font-size:14px" id="bordertable">Total Jengjang Panen</td>
								<td width="350" align="center" style="font-size:14px" id="bordertable">NIK Krani Buah</td>
								<td width="350" align="center" style="font-size:14px" id="bordertable">Nama Krani Buah</td>
								<td width="350" align="center" style="font-size:14px" id="bordertable">No BCC</td>
								<td width="250" align="center" style="font-size:14px" id="bordertable">Status QR Code TPH</td>
								<td width="100" align="center" style="font-size:14px" id="bordertable">BM (jjg)</td>
								<td width="100" align="center" style="font-size:14px" id="bordertable">Bk (jjg)</td>
								<td width="100" align="center" style="font-size:14px" id="bordertable">MS (jjg)</td>
								<td width="100" align="center" style="font-size:14px" id="bordertable">OR (jjg)</td>
								<td width="100" align="center" style="font-size:14px" id="bordertable">BB (jjg)</td>
								<td width="100" align="center" style="font-size:14px" id="bordertable">JK (jjg)</td>
								<td width="100" align="center" style="font-size:14px" id="bordertable">BA (jjg)</td>
								<td width="100" align="center" style="font-size:14px" id="bordertable">Total Jengjang Panen</td>
								<td width="250" align="center" style="font-size:14px" id="bordertable">Lihat Foto</td>
								<td width="250" align="center" style="font-size:14px" id="bordertable">Akurasi Sampling EBCC</td>
								<td width="250" align="center" style="font-size:14px" id="bordertable">Akurasi Kualitas MS</td>
								<td width="99" id="bordertable">&nbsp;</td>
							</tr>
							<?php
							$endPage = $calPage + $pagesize;
							for ($xJAN = $calPage; $xJAN <  $roweffec_BCC && $xJAN < $endPage; $xJAN++) {
								$no = $xJAN + 1;

								if (($xJAN % 2) == 0) {
									$bg = "#F0F3EC";
								} else {
									$bg = "#DEE7D2";
								}

								$fixedBCC = separator($NO_BCC[$xJAN]);

								echo "<tr style=\"font-size:12px\" bgcolor=$bg >";
								echo "<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$no</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$VAL_DATE_TIME[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$VAL_NIK_VALIDATOR[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$VAL_NAMA_VALIDATOR[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$VAL_JABATAN_VALIDATOR[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$VAL_WERKS[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">Belum Ada</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$VAL_AFD_CODE[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$VAL_BLOCK_CODE[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$VAL_BLOCK_NAME[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$VAL_TPH_CODE[$xJAN]</td>						
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$VAL_EBCC_CODE[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">Belum Ada</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$VAL_JML_BM[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$VAL_JML_BK[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$VAL_JML_MS[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$VAL_JML_OR[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$VAL_JML_BB[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$VAL_JML_JK[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$VAL_JML_BA[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$VAL_JJG_PANEN[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$EBCC_NIK_KERANI_BUAH[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$EBCC_NAMA_KERANI_BUAH[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$EBCC_NO_BCC[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">Belum Ada</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$EBCC_JML_BM[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$EBCC_JML_BK[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$EBCC_JML_MS[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$EBCC_JML_OR[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$EBCC_JML_BB[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$EBCC_JML_JK[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$EBCCJML_BA[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$EBCC_JJG_PANEN[$xJAN]</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">
									<form target='_blank' id=\"formNOBCC$xJAN\" name=\"formNOBCC$xJAN\" method=\"post\" action=\"LaporanBCCSelectImage.php\">
										<input name=\"editNO_BCC\" type=\"text\" id=\"editNO_BCC\" value=\"$NO_BCC[$xJAN]\" style=\"display:none\"/>
										<td id=\"bordertable\">
											<a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formNOBCC$xJAN') .submit()\">
												<input type=\"button\" name=\"button\" id=\"button\" value=\"Lihat Foto\" style=\"width:90px; height:25px; font-size:12px\"/>
											</a>
										</td>
									</form>
								</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">Belum Ada</td>
								<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">Belum Ada</td>
								
								<form target='_blank' id=\"formNOBCC$xJAN\" name=\"formNOBCC$xJAN\" method=\"post\" action=\"LaporanBCCSelect.php\">
									<input name=\"editNO_BCC\" type=\"text\" id=\"editNO_BCC\" value=\"$NO_BCC[$xJAN]\" style=\"display:none\"/>
									<td id=\"bordertable\">
									<a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formNOBCC$xJAN') .submit()\">
									<input type=\"button\" name=\"button\" id=\"button\" value=\"View\" style=\"width:90px; height:25px; font-size:12px\"/>
									</a>
									</td>
								</form>
								";
							}
							echo "</tr>";
							?>

						</table>
					</td>
				</tr>

				<tr>
					<td colspan="3" align="right">
						<table width="400" border="0">
							<tr>
								<td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="LaporanBCCList.php?page=first">
										<input type="button" name="button6" id="button6" value="&lt;&lt; First" style="width:70px; background-color:#9CC346" />
									</a></td>
								<td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="LaporanBCCList.php?page=back">
										<input type="button" name="button5" id="button5" value="&lt; Back" style="width:70px; background-color:#9CC346" />
									</a></td>
								<td width="100" align="center" valign="middle" style="background-color:#9CC346; font-size:12px"><span style="padding-top:0px">Page
										<?= $sesPageres + 1 ?>
										of
										<?= $totalpage ?>
									</span></td>
								<td width="70" align="left" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="LaporanBCCList.php?page=next"></a><a href="LaporanBCCList.php?page=next">
										<input type="button" name="button4" id="button4" value="Next &gt;" style="width:70px; background-color:#9CC346" />
									</a></td>
								<td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="LaporanBCCList.php?page=last">
										<input type="button" name="button7" id="button7" value="Last &gt;&gt;" style="width:70px; background-color:#9CC346" />
									</a></td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td colspan="4"><?php
													if (isset($_SESSION['err'])) {
														$err = $_SESSION['err'];
														if ($err != NULL) {
															echo $err;
															unset($_SESSION['err']);
														}
													}
													?></td>
				</tr>
			</table>
		</th>
	</tr>
	<tr>
		<th align="center"><?php include("../include/Footer.php") ?></th>
	</tr>
</table>