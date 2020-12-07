<?php
	session_start();
	include("../include/Header.php");

	function distance($lat1, $lon1, $lat2, $lon2, $unit) {
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);

		if ($unit == "K") {
			return ($miles * 1.609344);
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}

	if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name'])){	
		$Job_Code = $_SESSION['Job_Code'];
		$username = $_SESSION['NIK'];	
		$Emp_Name = $_SESSION['Name'];
		//$Jenis_Login = $_SESSION['Jenis_Login'];
		$Comp_Name = $_SESSION['Comp_Name'];
		$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
		$Date = $_SESSION['Date'];
		
		if($username == ""){
			$_SESSION[err] = "tolong login dulu!";
			header("location:../index.php");
		} else {
			include("../config/SQL_function.php");
			include("../config/db_connect.php");
			$con = connect();
			
			$ExportToSAPLHMPanen = "";
			if(isset($_POST["ExportToSAPLHMPanen"])){
				$ExportToSAPLHMPanen = $_POST["ExportToSAPLHMPanen"];
				$_SESSION["ExportToSAPLHMPanen"] = $ExportToSAPLHMPanen;
			}
			if(isset($_SESSION["ExportToSAPLHMPanen"])){
				$ExportToSAPLHMPanen = $_SESSION["ExportToSAPLHMPanen"];
			}
			
			if($ExportToSAPLHMPanen == TRUE){
				
				$sesAfdeling = "";
				if(isset($_POST["Afdeling"])){
					$_SESSION["Afdeling"] = $_POST["Afdeling"];
					
				}
				$sesAfdeling = $_SESSION["Afdeling"];
				
				$sql_afd = "select * from t_afdeling where ID_BA = '$subID_BA_Afd' ORDER BY ID_BA";
					
				
				
				//echo "here".$sql_afd;
				$result_afd = oci_parse($con, $sql_afd);
				oci_execute($result_afd, OCI_DEFAULT);
				while (oci_fetch($result_afd)) {	
					//$ID_BA_Afd[] 		= oci_result($result_afd, "ID_BA_AFD");
					$ID_Afd[] 		= oci_result($result_afd, "ID_AFD");
				}
				$jumlahAfd = oci_num_rows($result_afd);
				//echo "here".$sql_afd."jumlah".$jumlahAfd;
				
				
				$sdate1 = "";
				$sdate2 = "";
				if(isset($_POST["date1"])){
					$_SESSION['date1'] = date("Y-m-d", strtotime($_POST["date1"]));
					unset($_SESSION['date2']);
					
					$sdate2 = "";
				} else {
					unset($_SESSION['date1']);
				}
				
				if(isset($_POST["date2"])){
					$_SESSION['date2'] = date("Y-m-d", strtotime($_POST["date2"]));
					if($_SESSION['date2'] == "1970-01-01")
					{
						$_SESSION['date2'] = "";
					}
				} else {
					unset($_SESSION['date2']);
				}
				
				if(isset($_SESSION['date1']) and $_SESSION['date1']!="1970-01-01"){
					$sdate1 = $_SESSION['date1'];
				}
				
				if(isset($_SESSION['date2']) and $_SESSION['date2']!="1970-01-01"){
					$sdate2 = $_SESSION['date2'];
				} 
				if($_POST["NIKMandor"]==""){
					$nik_mandor = "ALL";
				} else {
					$nik_mandor = $_POST["NIKMandor"];
				}
				
				$date1 	= date("Y-m-d", strtotime($_POST["date1"]));
				if($_POST["date2"] == '1970-01-01' or $_POST["date2"] == ''){
					$date2 = null;
				} else {
					$date2 	= date("Y-m-d", strtotime($_POST["date2"]));
				}
				$ID_BA 		= $_SESSION['subID_BA_Afd'];
				$ID_CC 		= $_SESSION['subID_CC'];
				
			}
			else{
				header("location:../menu/authoritysecure.php");
			}
		}
		
	?>
	
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
	
	<script type="text/javascript">
		$(function() {
			$('#datepicker2').datepicker({
				changeMonth: true,
				changeYear: true
			});
		});
		function change(x)
		{
			if(x == 1){
				document.getElementById("Afdeling").style.visibility="visible";
				document.getElementById("NIKMandor").style.visibility="hidden";
				//document.getElementById("NIKPemanen").style.visibility="hidden";
				document.getElementById("NIKMandor").value="kosong";
				//document.getElementById("NIKPemanen").value="kosong";
				document.getElementById("button").style.visibility="visible";
				
			}
			if(x == 2){
				document.getElementById("Afdeling").style.visibility="hidden";
				document.getElementById("NIKMandor").style.visibility="visible";
				//document.getElementById("NIKPemanen").style.visibility="hidden";
				document.getElementById("Afdeling").value="kosong";
				//document.getElementById("NIKPemanen").value="kosong";
				document.getElementById("button").style.visibility="visible";
			}
			
		}
		
		function coba(x)
		{
			if(x == 1){
				document.getElementById("Tanggal").style.visibility="visible";
				document.getElementById("Periode").style.visibility="hidden";
				document.getElementById("Periode").value="kosong";
			}
			
			if(x == 2){
				document.getElementById("Tanggal").style.visibility="hidden";
				document.getElementById("Periode").style.visibility="visible";
				document.getElementById("Tanggal").value="kosong";
			}
		}
		
		function formSubmit(x)
		{
			if(x == 1){
				document.getElementById("doFilter").submit();
			}
		}
		
		function cek_filter(){
			if($('#datepicker').val()==''){
				alert('Pilih tanggal terlebih dahulu');
				return false;
			} 
			else if($('#Afdeling').val()=='-'){
				alert('Pilih Afdeling terlebih dahulu');
				return false;
			} 
			else if($('#NIKMandor').val()==''){
				alert('Pilih Mandor terlebih dahulu');
				return false;
			} 
		
		}
	</script>
	
	<?php
	}
	else{
		$_SESSION[err] = "tolong login dulu!".$Job_Code."<br>".$username."<br>".$Emp_Name."<br>".$subID_BA_Afd;
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
	body,td,th {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-weight:normal;
	}
	
	
</style>
<table width="978" height="300" border="0" align="center">
	<tr>
		<th height="100" scope="row" align="center">
			<form id="submittanggal1" name="submittanggal1" method="post" action="ExportToSAPLHMPanen.php" onsubmit="return cek_filter()">  
				<table border="0" id="setbody2">
					<tr>
						<td height="50" colspan="4" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>Export To SAP - LHM Panen</strong></span></td>
						
					</tr>
					<tr>
						<td colspan="4" valign="bottom" style=" border-bottom:solid #000">LOKASI</td>
						<td colspan="3" valign="bottom" style=" border-bottom:solid #000"></td>
					</tr>
					
					<tr>
						<td width="138">Company Name</td>
						<td width="8">:</td>
						<td width="385"><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$Comp_Name?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
						<td width="28">&nbsp;</td>
						<td width="96"><span >Tanggal Panen</span></td>
						<td width="10">:</td>
						<td width="277">
							<input type="text" name="date1" id="datepicker" class="box_field" onchange="this.form.submit();" value="<?= $sdate1 ?>">
						</td>
					</tr>
					<tr>
						<td>Business Area</td>
						<td>:</td>
						<td><input name="ID_BA2" type="text" id="ID_BA2" value="<?=$subID_BA_Afd?>" style="background-color:#CCC; width:70px; height:25px; font-size:15px" onmousedown="return false"/></td>
						<td>&nbsp;</td>
						
						<td width="96"><span >Mandor</span></td>
						<td width="10">:</td>
						<td width="277">
							<?php
							//Mandor
							if (!is_null($ID_BA) and !is_null($date1)){
								$sql_MD = "
										  SELECT thrp.nik_mandor, emp_name nama_mandor
											FROM t_header_rencana_panen thrp
												 INNER JOIN ebcc.t_employee te
													ON thrp.nik_mandor = te.nik
												 INNER JOIN ebcc.t_detail_rencana_panen tdrp
													ON thrp.id_rencana = tdrp.id_rencana
												 INNER JOIN ebcc.t_blok tb
													ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
												 INNER JOIN ebcc.t_afdeling ta
													ON tb.id_ba_afd = ta.id_ba_afd
										   WHERE ta.id_ba = '$ID_BA' AND ta.id_afd = NVL (DECODE ('$sesAfdeling', 'ALL', NULL, '$sesAfdeling'), ta.id_afd) AND TO_CHAR (thrp.tanggal_rencana, 'YYYY-MM-DD') = '$date1'
										GROUP BY thrp.nik_mandor, emp_name
										 ";
							}
							else {
								$sql_MD = "
										  SELECT ' ' nik_mandor, ' ' nama_mandor
											FROM dual
										 ";
							}
														/* $sql_MD = "select NIK_Mandor, f_get_empname(NIK_Mandor) Nama_Mandor from t_header_rencana_panen thrp 
														inner join t_detail_rencana_panen tdrp on thrp.id_rencana = tdrp.id_rencana 
														inner join t_blok tb on tdrp.id_ba_afd_blok = tb.id_ba_afd_blok 
														inner join t_afdeling ta on tb.id_ba_afd = ta.id_ba_afd 
														WHERE ta.ID_BA = '$subID_BA_Afd' 
														and ta.id_afd = nvl (decode ('$sesAfdeling', 'ALL', null, '$sesAfdeling'), ta.id_afd) 
														and to_char(thrp.tanggal_rencana,'YYYY-MM-DD') between '$sdate1' and nvl ('$sdate2', '$sdate1')
														group by NIK_Mandor 
														order by Nama_Mandor"; */
														
														//echo $sql_MD;
														$result_MD = oci_parse($con, $sql_MD);
														oci_execute($result_MD, OCI_DEFAULT);
														$mandor = array();
														while (oci_fetch($result_MD)) {	
															$nikMandor = oci_result($result_MD, "NIK_MANDOR");
															$mandor[$nikMandor] = oci_result($result_MD, "NAMA_MANDOR");
														}
														$jumlahMD = oci_num_rows($result_MD);
														
														//echo "mandor".$sql_MD. $jumlahMD;
														
														//echo "here".$sql_MD .$jumlahMD;
														if($jumlahMD >0 ){
															//$jumlahRecord = $_SESSION['jumlahMD'];
															$selectoMD = "<select name=\"NIKMandor\" id=\"NIKMandor\" style=\"visibility:visible; font-size: 15px;  height: 25px\">";
															$optiondefMD = "<option value=\"\">-</option>";
															echo $selectoMD.$optiondefMD;
															asort($mandor);
															foreach($mandor as $key => $key_value) {
															  if($key==$_POST['NIKMandor']){
																	$_SESSION['Emp_NameMandor'] = $key_value;
																	echo "<option selected value=\"$key\">$key - $key_value</option>";
																} else {
																	echo "<option value=\"$key\">$key - $key_value</option>"; 
																}
															}
															$selectcMD = "</select>";
															echo $selectcMD;
															if($_POST['NIKMandor']=="ALL"){ $_SESSION['Emp_NameMandor'] = "ALL"; } 
														}
													?>
						</td>
						
						<td width="96" style="display:none"><span >End Date</span></td>
						<td width="10" style="display:none">:</td>
						<td width="277" style="display:none">
							<input type="text" name="date2" id="datepicker2" class="box_field" onchange="this.form.submit();" value="<?= $sdate2 ?>">
						</td>
					
					</tr>
					<tr>
						<td> Afdeling</td>
						<td>:</td>
						<td style="font-size:16px"><?php
							//Afdeling
							
							
							if($jumlahAfd > 0 ){
								//$jumlahRecord = $_SESSION['jumlahAfd'];
								
								$selectoAfd = "<select name=\"Afdeling\" id=\"Afdeling\" onchange=\"this.form.submit();\" style=\"visibility:visible; font-size: 15px; height: 25px \">";
								$optiondefAfd = "";
								echo $selectoAfd.$optiondefAfd;
								for($xAfd = 0; $xAfd < $jumlahAfd; $xAfd++){
									if($ID_Afd[$xAfd]==$_SESSION["Afdeling"]){
										echo "<option selected value=\"$ID_Afd[$xAfd]\">$ID_Afd[$xAfd]</option>";
									} else {
										echo "<option value=\"$ID_Afd[$xAfd]\">$ID_Afd[$xAfd]</option>";
									}										
								}
								$selectcAfd = "</select>";
								echo $selectcAfd;
							}           
						?>
						</td>
						<td>&nbsp;</td>
						<td width="96">&nbsp;</td>
						<td width="10">&nbsp;</td>
						<td width="277">&nbsp;</td>
					</tr>
					
					<tr>
						<td height="50" colspan="9" align="right" valign="baseline"><input type="submit" name="button" id="button" value="TAMPILKAN" style="width:120px; height: 30px" /></td>
					</tr>
				</table>
			</form>	
		</th>
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
			} else if(isset($_SESSION['success'])){
				$success = $_SESSION['success'];
				if($success!=null)
				{
					echo $success;
					unset($_SESSION['success']);
				}
			}
		?></th>
	</tr>
	</table>
	<?php if(isset($_POST["Afdeling"]) && isset($_POST["date1"]) && isset($_POST["button"])){
		
		
		//include('../config/dw_tap_config.php');
		$sql_Download_Denda_Panen = "SELECT
		TC.ID_CC, TBA.PROFILE_NAME, HPK.ID_BCC AS NO_BCC,  KP.SHORT_NAME AS PENALTI, HPK.QTY AS NILAI 
 	 FROM T_HEADER_RENCANA_PANEN THRP
       INNER JOIN T_DETAIL_RENCANA_PANEN TDRP
          ON THRP.ID_RENCANA = TDRP.ID_RENCANA
       INNER JOIN T_HASIL_PANEN H
		     ON TDRP.ID_RENCANA = H.ID_RENCANA
               AND TDRP.NO_REKAP_BCC = H.NO_REKAP_BCC
       INNER JOIN  T_HASILPANEN_KUALTAS HPK
          ON  H.NO_BCC=HPK.ID_BCC
		  AND H.ID_RENCANA = HPK.ID_RENCANA
       INNER JOIN T_KUALITAS_PANEN KP
          ON KP.ID_KUALITAS = HPK.ID_KUALITAS
       INNER JOIN T_BLOK TB
          ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
       INNER JOIN T_AFDELING TA 
          ON TB.ID_BA_AFD = TA.ID_BA_AFD 
       INNER JOIN T_BUSSINESSAREA TBA
            ON TA.ID_BA = TBA.ID_BA
	   INNER JOIN t_companycode tc
            ON tba.id_cc = tc.id_cc
   WHERE     TBA.ID_CC = '$ID_CC'
         AND TA.ID_BA = '$ID_BA'
		 AND TA.ID_AFD = NVL (DECODE ('$sesAfdeling', 'ALL', NULL, '$sesAfdeling'), TA.ID_AFD)
         AND TO_CHAR (THRP.TANGGAL_RENCANA, 'yyyy-mm-dd') BETWEEN '$date1' AND  NVL ('','$date1')
		 AND KP.PENALTY_STATUS='Y'  AND  HPK.QTY<>'0'
		";
		//echo $sql_Download_Denda_Panen; //exit;
		$_SESSION['sql_Download_Denda_Panen'] = $sql_Download_Denda_Panen;
		
		//Edited by Ardo, 22-10-2016 : Agar data tidak dobel kalau NIK_Gandeng dobel tapi sama 
		$sql_Download_Crop_Harv = "
  SELECT DISTINCT(THP.NO_BCC),
		 TBA.ID_CC AS ID_CC,
         THRP.NIK_PEMANEN,
		 TBA.PROFILE_NAME,
         f_get_empname (thrp.nik_pemanen) NAMA_PEMANEN,
         TO_CHAR (THRP.TANGGAL_RENCANA, 'DD.MM.YYYY') TANGGAL,
         THP.NO_TPH,
         CASE
            WHEN TA.ID_BA <> TA2.ID_BA THEN 'CINT_' || TA2.ID_BA
            ELSE NULL
         END
            CUST,
         TBA.ID_BA BA_KERJA,   
         TA2.ID_AFD AFD_KERJA,
         TB.ID_BLOK,
         CASE
            WHEN TA.ID_BA <> TA2.ID_BA
            THEN
               NVL (F_GET_HASIL_PANEN_BUNCH (TA2.ID_BA,
                                             thp.no_rekap_bcc,
                                             thp.no_bcc,
                                             'BUNCH_HARVEST'), 0)
            ELSE
               NVL (F_GET_HASIL_PANEN_BUNCH (TBA.ID_BA,
                                             thp.no_rekap_bcc,
                                             thp.no_bcc,
                                             'BUNCH_HARVEST'), 0)
         END
            AS TBS,
         NVL (
            F_GET_HASIL_PANEN_BRDX (thrp.id_rencana,
                                    thp.no_rekap_bcc,
                                    thp.no_bcc),
            0)
            AS BRD,
             CASE
                WHEN TA.ID_BA <> TA2.ID_BA
                THEN
                   NVL (F_GET_HASIL_PANEN_BUNCH (TA2.ID_BA,
                                                 thp.no_rekap_bcc,
                                                 thp.no_bcc,
                                                 'BUNCH_SEND'), 0)
                ELSE
                   NVL (F_GET_HASIL_PANEN_BUNCH (TBA.ID_BA,
                                                 thp.no_rekap_bcc,
                                                 thp.no_bcc,
                                                 'BUNCH_SEND'), 0)
             END
                AS DIKIRIM,
				CASE
            WHEN TA.ID_BA <> TA2.ID_BA
            THEN
               NVL (F_GET_HASIL_PANEN_BUNCH (TA2.ID_BA,
                                             thp.no_rekap_bcc,
                                             thp.no_bcc,
                                             'BUNCH_PAID'), 0)
            ELSE
               NVL (F_GET_HASIL_PANEN_BUNCH (TBA.ID_BA,
                                             thp.no_rekap_bcc,
                                             thp.no_bcc,
                                             'BUNCH_PAID'), 0)
         END
            AS TBS_BAYAR,
         thrp.nik_mandor,
         thrp.nik_kerani_buah,
         CASE WHEN TDG.NIK_GANDENG != '-' THEN 'X' ELSE NULL END GANDENG,
         CASE WHEN TDG.NIK_GANDENG = '-' THEN NULL ELSE TDG.NIK_GANDENG END
            NIK_GANDENG,
         tdrp.luasan_panen,
         THRP.id_rencana,
         THP.NO_REKAP_BCC,
		 THP.KODE_DELIVERY_TICKET,
         THP.VALIDASI_BCC,
         THP.VALIDASI_DATE,
         THP.CETAK_BCC,
         THP.CETAK_DATE,
		 tc_kary.ID_CC COMP_CODE_KARY,
		 tba_kary.PROFILE_NAME PROFILE_NAME_KARY,
		 f_get_idafd_nik (thrp.nik_pemanen) AFD_PEMANEN,
         NVL (F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana,
                                         thp.no_rekap_bcc,
                                         thp.no_bcc,
                                         1), 0)
            AS BM,
         NVL (F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana,
                                         thp.no_rekap_bcc,
                                         thp.no_bcc,
                                         2), 0)
            AS BK,
         NVL (F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana,
                                         thp.no_rekap_bcc,
                                         thp.no_bcc,
                                         7), 0)
            AS TP,
         NVL (F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana,
                                         thp.no_rekap_bcc,
                                         thp.no_bcc,
                                         6), 0)
            AS BB,
         NVL (F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana,
                                         thp.no_rekap_bcc,
                                         thp.no_bcc,
                                         15), 0)
            JK,
         NVL (F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana,
                                         thp.no_rekap_bcc,
                                         thp.no_bcc,
                                         16), 0)
            AS BA,
         NVL (F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana,
                                         thp.no_rekap_bcc,
                                         thp.no_bcc,
                                         11), 0)
            AS BT,
         NVL (F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana,
                                         thp.no_rekap_bcc,
                                         thp.no_bcc,
                                         12), 0)
            AS BL,
         NVL (F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana,
                                         thp.no_rekap_bcc,
                                         thp.no_bcc,
                                         13), 0)
            AS PB,
         NVL (F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana,
                                         thp.no_rekap_bcc,
                                         thp.no_bcc,
                                         10), 0)
            AS AB,
         NVL (F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana,
                                         thp.no_rekap_bcc,
                                         thp.no_bcc,
                                         14), 0)
            AS SF,
         NVL (F_GET_HASIL_PANEN_NUMBERX (thrp.id_rencana,
                                         thp.no_rekap_bcc,
                                         thp.no_bcc,
                                         8), 0)
            AS BS,
         (SELECT NILAI FROM T_PARAMETER WHERE KETERANGAN = 'RANGE') AS TOLERANSI_JARAK,
         TMT.LATITUDE M_LATITUDE,
         TMT.LONGITUDE M_LONGITUDE,
         THP.LATITUDE,
         THP.LONGITUDE,
         THP.STATUS_LOKASI,
         THP.STATUS_TPH,
         THP.STATUS_DETIC
		,CASE
		WHEN NVL( tts.status, 1 )= 1 THEN 0
		ELSE 1
	END tph_status
    FROM T_HEADER_RENCANA_PANEN THRP
         INNER JOIN T_EMPLOYEE TE
            ON THRP.NIK_PEMANEN = TE.NIK
         INNER JOIN T_AFDELING TA
            ON TE.ID_BA_AFD = TA.ID_BA_AFD
		 INNER JOIN t_bussinessarea tba_kary
            ON tba_kary.id_ba = TA.id_ba
         INNER JOIN t_companycode tc_kary
            ON tba_kary.id_cc = tc_kary.id_cc
         INNER JOIN t_detail_rencana_panen tdrp
                    ON thrp.id_rencana = tdrp.id_rencana
         INNER JOIN t_hasil_panen thp
            ON tdrp.id_rencana = thp.id_rencana
            AND tdrp.no_rekap_bcc = thp.no_rekap_bcc
         INNER JOIN t_blok tb
            ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
         INNER JOIN t_afdeling ta2
            ON tb.id_ba_afd = ta2.id_ba_afd
         INNER JOIN t_bussinessarea tba
            ON tba.id_ba = ta2.id_ba
         INNER JOIN t_companycode tc
            ON tba.id_cc = tc.id_cc
         LEFT JOIN T_DETAIL_GANDENG TDG
            ON THRP.ID_RENCANA = TDG.ID_RENCANA
         LEFT JOIN TAP_DW.TM_TPH@PRODDW_LINK TMT ON TB.ID_BA_AFD_BLOK = TMT.WERKS || TMT.AFD_CODE || Trim(To_char(tmt.block_code, '099') ) AND THP.NO_TPH = TMT.NO_TPH 
		 LEFT JOIN T_TPH_STATUS tts ON tts.werks = tmt.werks AND tts.afd = tmt.afd_code AND tts.block_code = Trim(To_char(tmt.block_code, '099') ) AND tts.tph = tmt.no_tph
   WHERE     tc.id_cc = '$ID_CC'
         and tba.id_ba = '$ID_BA'
		 and ta2.id_afd = nvl(decode('$sesAfdeling', 'ALL', null, '$sesAfdeling'), ta2.id_afd)
		 and thrp.nik_mandor = nvl(decode('".$_POST["NIKMandor"]."', 'ALL', null, '".$_POST["NIKMandor"]."'), thrp.nik_mandor)
         and TO_CHAR (thrp.tanggal_rencana, 'YYYY-MM-DD') BETWEEN '$date1' and nvl ('', '$date1')
		 --and thp.cetak_bcc is not null and thp.cetak_date is not null
		 
		 -- AND THP.NO_TPH NOT IN (
         	-- SELECT tph FROM T_TPH_STATUS TTS WHERE TTS.WERKS = TMT.WERKS AND TTS.AFD = TMT.AFD_CODE AND TTS.BLOCK_CODE = TMT.BLOCK_CODE AND STATUS = 0
         -- )
		 
ORDER BY AFD_PEMANEN asc, nama_pemanen asc, nik_pemanen asc, id_blok asc, luasan_panen desc, nik_gandeng
		";
		// echo $sql_Download_Crop_Harv; die;
		$result = oci_parse($con, $sql_Download_Crop_Harv);
		oci_execute($result, OCI_DEFAULT);
		
		$count_first = 0;
		$temp_valid = 0;
		while (oci_fetch($result)){
			
			if($count_first==0){
				$temp_bcc = oci_result($result, "NO_BCC");
				$temp_valid = $count_first;
				//echo "if : " . $count_first . "<br>";
				$bcc_export[$count_first]['NO_BCC'] = oci_result($result, "NO_BCC");
				$bcc_export[$count_first]['ID_CC'] = oci_result($result, "ID_CC");
				$bcc_export[$count_first]['NIK_KARYAWAN'] = oci_result($result, "NIK_PEMANEN");
				$bcc_export[$count_first]['PROFILE_NAME'] = oci_result($result, "PROFILE_NAME");
				$bcc_export[$count_first]['NAMA_KARYAWAN'] = oci_result($result, "NAMA_PEMANEN");
				$bcc_export[$count_first]['TANGGAL'] = oci_result($result, "TANGGAL");
				$bcc_export[$count_first]['NO_TPH'] = oci_result($result, "NO_TPH");
				$bcc_export[$count_first]['CUST'] = oci_result($result, "CUST");
				$bcc_export[$count_first]['BA_KERJA'] = oci_result($result, "BA_KERJA");
				$bcc_export[$count_first]['AFD_KERJA'] = oci_result($result, "AFD_KERJA");
				$bcc_export[$count_first]['BLOK'] = oci_result($result, "ID_BLOK");
				$bcc_export[$count_first]['TBS'] = oci_result($result, "TBS");
				$bcc_export[$count_first]['BRD'] = oci_result($result, "BRD");
				$bcc_export[$count_first]['DIKIRIM'] = oci_result($result, "DIKIRIM");
				$bcc_export[$count_first]['TBS_BAYAR'] = oci_result($result, "TBS_BAYAR");
				$bcc_export[$count_first]['NIK_MANDOR'] = oci_result($result, "NIK_MANDOR");
				$bcc_export[$count_first]['NIK_KERANI_BUAH'] = oci_result($result, "NIK_KERANI_BUAH");
				$bcc_export[$count_first]['GANDENG'][] = oci_result($result, "GANDENG");
				$bcc_export[$count_first]['NIK_GANDENG'][] = oci_result($result, "NIK_GANDENG");
				$bcc_export[$count_first]['LUASAN_PANEN'] = oci_result($result, "LUASAN_PANEN");
				$bcc_export[$count_first]['ID_RENCANA'] = oci_result($result, "ID_RENCANA");
				$bcc_export[$count_first]['NO_REKAP_BCC'] = oci_result($result, "NO_REKAP_BCC");
				$bcc_export[$count_first]['KODE_DELIVERY_TICKET'] = oci_result($result, "KODE_DELIVERY_TICKET");
				$bcc_export[$count_first]['CETAK_BCC'] = oci_result($result, "CETAK_BCC");
				$bcc_export[$count_first]['CETAK_DATE'] = oci_result($result, "CETAK_DATE");
				$bcc_export[$count_first]['VALIDASI_BCC'] = oci_result($result, "VALIDASI_BCC");
				$bcc_export[$count_first]['VALIDASI_DATE'] = oci_result($result, "VALIDASI_DATE");
				$bcc_export[$count_first]['COMP_CODE_KARY'] = oci_result($result, "COMP_CODE_KARY");
				$bcc_export[$count_first]['PROFILE_NAME_KARY'] = oci_result($result, "PROFILE_NAME_KARY");
				$bcc_export[$count_first]['AFD_PEMANEN'] = oci_result($result, "AFD_PEMANEN");
				$bcc_export[$count_first]['BM'] = oci_result($result, "BM");
				$bcc_export[$count_first]['BK'] = oci_result($result, "BK");
				$bcc_export[$count_first]['TP'] = oci_result($result, "TP");
				$bcc_export[$count_first]['BB'] = oci_result($result, "BB");
				$bcc_export[$count_first]['JK'] = oci_result($result, "JK");
				$bcc_export[$count_first]['BA'] = oci_result($result, "BA");
				$bcc_export[$count_first]['BT'] = oci_result($result, "BT");
				$bcc_export[$count_first]['BL'] = oci_result($result, "BL");
				$bcc_export[$count_first]['PB'] = oci_result($result, "PB");
				$bcc_export[$count_first]['AB'] = oci_result($result, "AB");
				$bcc_export[$count_first]['SF'] = oci_result($result, "SF");
				$bcc_export[$count_first]['BS'] = oci_result($result, "BS");
				$bcc_export[$count_first]['JARAK'] = oci_result($result, "JARAK");
				$bcc_export[$count_first]['TOLERANSI_JARAK'] = oci_result($result, "TOLERANSI_JARAK");
				$bcc_export[$count_first]['STATUS_LOKASI'] = oci_result($result, "STATUS_LOKASI");
				$bcc_export[$count_first]['STATUS_TPH'] = oci_result($result, "STATUS_TPH");
				$bcc_export[$count_first]['STATUS_DETIC'] = oci_result($result, "STATUS_DETIC");
				$bcc_export[$count_first]['M_LATITUDE'] = oci_result($result, "M_LATITUDE");
				$bcc_export[$count_first]['M_LONGITUDE'] = oci_result($result, "M_LONGITUDE");
				$bcc_export[$count_first]['LATITUDE'] = oci_result($result, "LATITUDE");
				$bcc_export[$count_first]['LONGITUDE'] = oci_result($result, "LONGITUDE");
				$bcc_export[$count_first]['TPH_STATUS'] = oci_result($result, "TPH_STATUS");
				
				$count_first++;
			} else { 
			/*echo "BCC : " . $temp_bcc . " == " . oci_result($result, "NO_BCC") . "<br>";
				if($temp_bcc==oci_result($result, "NO_BCC")){
					$bcc_export[$temp_valid]['NIK_GANDENG'][] = oci_result($result, "NIK_GANDENG");
					$bcc_export[$temp_valid]['GANDENG'][] = oci_result($result, "GANDENG");
					echo "HITUNG1 : " . $count_first . "<br>";
					$bcc_export[$count_first]['NO_BCC'] = oci_result($result, "NO_BCC");
					$bcc_export[$count_first]['ID_CC'] = oci_result($result, "ID_CC");
					$bcc_export[$count_first]['NIK_KARYAWAN'] = oci_result($result, "NIK_PEMANEN");
					$bcc_export[$count_first]['PROFILE_NAME'] = oci_result($result, "PROFILE_NAME");
					$bcc_export[$count_first]['NAMA_KARYAWAN'] = oci_result($result, "NAMA_PEMANEN");
					$bcc_export[$count_first]['TANGGAL'] = oci_result($result, "TANGGAL");
					$bcc_export[$count_first]['NO_TPH'] = oci_result($result, "NO_TPH");
					$bcc_export[$count_first]['CUST'] = oci_result($result, "CUST");
					$bcc_export[$count_first]['BA_KERJA'] = oci_result($result, "BA_KERJA");
					$bcc_export[$count_first]['AFD_KERJA'] = oci_result($result, "AFD_KERJA");
					$bcc_export[$count_first]['BLOK'] = oci_result($result, "ID_BLOK");
					$bcc_export[$count_first]['TBS'] = oci_result($result, "TBS");
					$bcc_export[$count_first]['BRD'] = oci_result($result, "BRD");
					$bcc_export[$count_first]['DIKIRIM'] = oci_result($result, "DIKIRIM");
					$bcc_export[$count_first]['TBS_BAYAR'] = oci_result($result, "TBS_BAYAR");
					$bcc_export[$count_first]['NIK_MANDOR'] = oci_result($result, "NIK_MANDOR");
					$bcc_export[$count_first]['NIK_KERANI_BUAH'] = oci_result($result, "NIK_KERANI_BUAH");
					$bcc_export[$count_first]['GANDENG'][] = oci_result($result, "GANDENG");
					$bcc_export[$count_first]['NIK_GANDENG'][] = oci_result($result, "NIK_GANDENG");
					$bcc_export[$count_first]['LUASAN_PANEN'] = oci_result($result, "LUASAN_PANEN");
					$bcc_export[$count_first]['ID_RENCANA'] = oci_result($result, "ID_RENCANA");
					$bcc_export[$count_first]['NO_REKAP_BCC'] = oci_result($result, "NO_REKAP_BCC");
					$bcc_export[$count_first]['KODE_DELIVERY_TICKET'] = oci_result($result, "KODE_DELIVERY_TICKET");
					$bcc_export[$count_first]['CETAK_BCC'] = oci_result($result, "CETAK_BCC");
					$bcc_export[$count_first]['CETAK_DATE'] = oci_result($result, "CETAK_DATE");
					$bcc_export[$count_first]['VALIDASI_BCC'] = oci_result($result, "VALIDASI_BCC");
					$bcc_export[$count_first]['VALIDASI_DATE'] = oci_result($result, "VALIDASI_DATE");
					$bcc_export[$count_first]['COMP_CODE_KARY'] = oci_result($result, "COMP_CODE_KARY");
					$bcc_export[$count_first]['PROFILE_NAME_KARY'] = oci_result($result, "PROFILE_NAME_KARY");
					$bcc_export[$count_first]['AFD_PEMANEN'] = oci_result($result, "AFD_PEMANEN");
					$bcc_export[$count_first]['BM'] = oci_result($result, "BM");
					$bcc_export[$count_first]['BK'] = oci_result($result, "BK");
					$bcc_export[$count_first]['TP'] = oci_result($result, "TP");
					$bcc_export[$count_first]['BB'] = oci_result($result, "BB");
					$bcc_export[$count_first]['JK'] = oci_result($result, "JK");
					$bcc_export[$count_first]['BA'] = oci_result($result, "BA");
					$bcc_export[$count_first]['BT'] = oci_result($result, "BT");
					$bcc_export[$count_first]['BL'] = oci_result($result, "BL");
					$bcc_export[$count_first]['PB'] = oci_result($result, "PB");
					$bcc_export[$count_first]['AB'] = oci_result($result, "AB");
					$bcc_export[$count_first]['SF'] = oci_result($result, "SF");
					$bcc_export[$count_first]['BS'] = oci_result($result, "BS");
					$bcc_export[$count_first]['JARAK'] = oci_result($result, "JARAK");
					$bcc_export[$count_first]['TOLERANSI_JARAK'] = oci_result($result, "TOLERANSI_JARAK");
					$bcc_export[$count_first]['STATUS_LOKASI'] = oci_result($result, "STATUS_LOKASI");
					$bcc_export[$count_first]['STATUS_TPH'] = oci_result($result, "STATUS_TPH");
					$bcc_export[$count_first]['STATUS_DETIC'] = oci_result($result, "STATUS_DETIC");
					$bcc_export[$count_first]['M_LATITUDE'] = oci_result($result, "M_LATITUDE");
					$bcc_export[$count_first]['M_LONGITUDE'] = oci_result($result, "M_LONGITUDE");
					$bcc_export[$count_first]['LATITUDE'] = oci_result($result, "LATITUDE");
					$bcc_export[$count_first]['LONGITUDE'] = oci_result($result, "LONGITUDE");
					$count_first++;
					echo "HITUNG2 : " . $count_first . "<br>";
					$bcc_export[$count_first]['NO_BCC'] = oci_result($result, "NO_BCC");
					$bcc_export[$count_first]['ID_CC'] = oci_result($result, "ID_CC");
					$bcc_export[$count_first]['NIK_KARYAWAN'] = oci_result($result, "NIK_PEMANEN");
					$bcc_export[$count_first]['PROFILE_NAME'] = oci_result($result, "PROFILE_NAME");
					$bcc_export[$count_first]['NAMA_KARYAWAN'] = oci_result($result, "NAMA_PEMANEN");
					$bcc_export[$count_first]['TANGGAL'] = oci_result($result, "TANGGAL");
					$bcc_export[$count_first]['NO_TPH'] = oci_result($result, "NO_TPH");
					$bcc_export[$count_first]['CUST'] = oci_result($result, "CUST");
					$bcc_export[$count_first]['BA_KERJA'] = oci_result($result, "BA_KERJA");
					$bcc_export[$count_first]['AFD_KERJA'] = oci_result($result, "AFD_KERJA");
					$bcc_export[$count_first]['BLOK'] = oci_result($result, "ID_BLOK");
					$bcc_export[$count_first]['TBS'] = oci_result($result, "TBS");
					$bcc_export[$count_first]['BRD'] = oci_result($result, "BRD");
					$bcc_export[$count_first]['DIKIRIM'] = oci_result($result, "DIKIRIM");
					$bcc_export[$count_first]['TBS_BAYAR'] = oci_result($result, "TBS_BAYAR");
					$bcc_export[$count_first]['NIK_MANDOR'] = oci_result($result, "NIK_MANDOR");
					$bcc_export[$count_first]['NIK_KERANI_BUAH'] = oci_result($result, "NIK_KERANI_BUAH");
					$bcc_export[$count_first]['GANDENG'][] = oci_result($result, "GANDENG");
					$bcc_export[$count_first]['NIK_GANDENG'][] = oci_result($result, "NIK_GANDENG");
					$bcc_export[$count_first]['LUASAN_PANEN'] = oci_result($result, "LUASAN_PANEN");
					$bcc_export[$count_first]['ID_RENCANA'] = oci_result($result, "ID_RENCANA");
					$bcc_export[$count_first]['NO_REKAP_BCC'] = oci_result($result, "NO_REKAP_BCC");
					$bcc_export[$count_first]['KODE_DELIVERY_TICKET'] = oci_result($result, "KODE_DELIVERY_TICKET");
					$bcc_export[$count_first]['CETAK_BCC'] = oci_result($result, "CETAK_BCC");
					$bcc_export[$count_first]['CETAK_DATE'] = oci_result($result, "CETAK_DATE");
					$bcc_export[$count_first]['VALIDASI_BCC'] = oci_result($result, "VALIDASI_BCC");
					$bcc_export[$count_first]['VALIDASI_DATE'] = oci_result($result, "VALIDASI_DATE");
					$bcc_export[$count_first]['COMP_CODE_KARY'] = oci_result($result, "COMP_CODE_KARY");
					$bcc_export[$count_first]['PROFILE_NAME_KARY'] = oci_result($result, "PROFILE_NAME_KARY");
					$bcc_export[$count_first]['AFD_PEMANEN'] = oci_result($result, "AFD_PEMANEN");
					$bcc_export[$count_first]['BM'] = oci_result($result, "BM");
					$bcc_export[$count_first]['BK'] = oci_result($result, "BK");
					$bcc_export[$count_first]['TP'] = oci_result($result, "TP");
					$bcc_export[$count_first]['BB'] = oci_result($result, "BB");
					$bcc_export[$count_first]['JK'] = oci_result($result, "JK");
					$bcc_export[$count_first]['BA'] = oci_result($result, "BA");
					$bcc_export[$count_first]['BT'] = oci_result($result, "BT");
					$bcc_export[$count_first]['BL'] = oci_result($result, "BL");
					$bcc_export[$count_first]['PB'] = oci_result($result, "PB");
					$bcc_export[$count_first]['AB'] = oci_result($result, "AB");
					$bcc_export[$count_first]['SF'] = oci_result($result, "SF");
					$bcc_export[$count_first]['BS'] = oci_result($result, "BS");
					$bcc_export[$count_first]['JARAK'] = oci_result($result, "JARAK");
					$bcc_export[$count_first]['TOLERANSI_JARAK'] = oci_result($result, "TOLERANSI_JARAK");
					$bcc_export[$count_first]['STATUS_LOKASI'] = oci_result($result, "STATUS_LOKASI");
					$bcc_export[$count_first]['STATUS_TPH'] = oci_result($result, "STATUS_TPH");
					$bcc_export[$count_first]['STATUS_DETIC'] = oci_result($result, "STATUS_DETIC");
					$bcc_export[$count_first]['M_LATITUDE'] = oci_result($result, "M_LATITUDE");
					$bcc_export[$count_first]['M_LONGITUDE'] = oci_result($result, "M_LONGITUDE");
					$bcc_export[$count_first]['LATITUDE'] = oci_result($result, "LATITUDE");
					$bcc_export[$count_first]['LONGITUDE'] = oci_result($result, "LONGITUDE");
					
					//echo "else1 : " . $count_first . "<br>";
					
				} else {*/
					$temp_bcc = oci_result($result, "NO_BCC");
					$temp_valid = $count_first;
					//echo "else2 : " . $count_first . "<br>";
					//echo "HITUNG3 : " . $count_first . "<br>";
					$bcc_export[$count_first]['NO_BCC'] = oci_result($result, "NO_BCC");
					$bcc_export[$count_first]['ID_CC'] = oci_result($result, "ID_CC");
					$bcc_export[$count_first]['NIK_KARYAWAN'] = oci_result($result, "NIK_PEMANEN");
					$bcc_export[$count_first]['PROFILE_NAME'] = oci_result($result, "PROFILE_NAME");
					$bcc_export[$count_first]['NAMA_KARYAWAN'] = oci_result($result, "NAMA_PEMANEN");
					$bcc_export[$count_first]['TANGGAL'] = oci_result($result, "TANGGAL");
					$bcc_export[$count_first]['NO_TPH'] = oci_result($result, "NO_TPH");
					$bcc_export[$count_first]['CUST'] = oci_result($result, "CUST");
					$bcc_export[$count_first]['BA_KERJA'] = oci_result($result, "BA_KERJA");
					$bcc_export[$count_first]['AFD_KERJA'] = oci_result($result, "AFD_KERJA");
					$bcc_export[$count_first]['BLOK'] = oci_result($result, "ID_BLOK");
					$bcc_export[$count_first]['TBS'] = oci_result($result, "TBS");
					$bcc_export[$count_first]['BRD'] = oci_result($result, "BRD");
					$bcc_export[$count_first]['DIKIRIM'] = oci_result($result, "DIKIRIM");
					$bcc_export[$count_first]['TBS_BAYAR'] = oci_result($result, "TBS_BAYAR");
					$bcc_export[$count_first]['NIK_MANDOR'] = oci_result($result, "NIK_MANDOR");
					$bcc_export[$count_first]['NIK_KERANI_BUAH'] = oci_result($result, "NIK_KERANI_BUAH");
					$bcc_export[$count_first]['GANDENG'][] = oci_result($result, "GANDENG");
					$bcc_export[$count_first]['NIK_GANDENG'][] = oci_result($result, "NIK_GANDENG");
					$bcc_export[$count_first]['LUASAN_PANEN'] = oci_result($result, "LUASAN_PANEN");
					$bcc_export[$count_first]['ID_RENCANA'] = oci_result($result, "ID_RENCANA");
					$bcc_export[$count_first]['NO_REKAP_BCC'] = oci_result($result, "NO_REKAP_BCC");
					$bcc_export[$count_first]['KODE_DELIVERY_TICKET'] = oci_result($result, "KODE_DELIVERY_TICKET");
					$bcc_export[$count_first]['CETAK_BCC'] = oci_result($result, "CETAK_BCC");
					$bcc_export[$count_first]['CETAK_DATE'] = oci_result($result, "CETAK_DATE");
					$bcc_export[$count_first]['VALIDASI_BCC'] = oci_result($result, "VALIDASI_BCC");
					$bcc_export[$count_first]['VALIDASI_DATE'] = oci_result($result, "VALIDASI_DATE");
					$bcc_export[$count_first]['COMP_CODE_KARY'] = oci_result($result, "COMP_CODE_KARY");
					$bcc_export[$count_first]['PROFILE_NAME_KARY'] = oci_result($result, "PROFILE_NAME_KARY");
					$bcc_export[$count_first]['AFD_PEMANEN'] = oci_result($result, "AFD_PEMANEN");
					$bcc_export[$count_first]['BM'] = oci_result($result, "BM");
					$bcc_export[$count_first]['BK'] = oci_result($result, "BK");
					$bcc_export[$count_first]['TP'] = oci_result($result, "TP");
					$bcc_export[$count_first]['BB'] = oci_result($result, "BB");
					$bcc_export[$count_first]['JK'] = oci_result($result, "JK");
					$bcc_export[$count_first]['BA'] = oci_result($result, "BA");
					$bcc_export[$count_first]['BT'] = oci_result($result, "BT");
					$bcc_export[$count_first]['BL'] = oci_result($result, "BL");
					$bcc_export[$count_first]['PB'] = oci_result($result, "PB");
					$bcc_export[$count_first]['AB'] = oci_result($result, "AB");
					$bcc_export[$count_first]['SF'] = oci_result($result, "SF");
					$bcc_export[$count_first]['BS'] = oci_result($result, "BS");
					$bcc_export[$count_first]['JARAK'] = oci_result($result, "JARAK");
					$bcc_export[$count_first]['TOLERANSI_JARAK'] = oci_result($result, "TOLERANSI_JARAK");
					$bcc_export[$count_first]['STATUS_LOKASI'] = oci_result($result, "STATUS_LOKASI");
					$bcc_export[$count_first]['STATUS_TPH'] = oci_result($result, "STATUS_TPH");
					$bcc_export[$count_first]['STATUS_DETIC'] = oci_result($result, "STATUS_DETIC");
					$bcc_export[$count_first]['M_LATITUDE'] = oci_result($result, "M_LATITUDE");
					$bcc_export[$count_first]['M_LONGITUDE'] = oci_result($result, "M_LONGITUDE");
					$bcc_export[$count_first]['LATITUDE'] = oci_result($result, "LATITUDE");
					$bcc_export[$count_first]['LONGITUDE'] = oci_result($result, "LONGITUDE");
					$bcc_export[$count_first]['TPH_STATUS'] = oci_result($result, "TPH_STATUS");
					$count_first++;
					
					
				}
				
			//}
		}	
		/* "<pre>";
		print_r($bcc_export); //exit;
		echo"</pre>"; */
		$_SESSION['sql_export_crop_harv'] = $sql_Download_Crop_Harv;
		$roweffec = $count_first;
		//echo $roweffec; die();
	?>
	<style>
	table.scroll td {
      border-bottom: 3px ridge #9CC346;
      padding: 5px;
      
    }
    table.scroll td + td {
      border-left: 3px ridge #9CC346;
    }
    table.scroll th {
      padding: 0 5px;
	  
    }
	table.scroll tr:nth-child(even) {background: #F0F3EC}
	table.scroll tr:nth-child(odd) {background: #DEE7D2}
	
    .header-background {
      border-bottom: 3px ridge #9CC346;
    }
    
    /* above this is decorative, not part of the test */
    
    .fixed-table-container {
	
      width: 1100px;
      height: 200px;
      border-left: 3px ridge #9CC346;
      border-right: 3px ridge #9CC346;
      border-bottom: 3px ridge #9CC346;
      margin: 10px auto;
      /* above is decorative or flexible */
      position: relative; /* could be absolute or relative */
      padding-top: 30px; /* height of header */
    }

    .fixed-table-container-inner {
      overflow-x: hidden;
      overflow-y: auto;
      height: 100%;
    }
     
    .header-background {
      background-color: #9CC346;
      height: 30px; /* height of header */
      position: absolute;
      top: 0;
      right: 0;
      left: 0;
    }
    
    table.scroll {
      background-color: white;
      width: 1100px;
      overflow-x: hidden;
      overflow-y: auto;
	  
    }

    .th-inner {
      position: absolute;
      top: 0;
      line-height: 30px; /* height of header */
      text-align: left;
      border-left: 3px ridge #9CC346;
      border-top: 3px ridge #9CC346;
      padding-left: 5px;
      margin-left: -5px;
    }
    .first .th-inner {
        border-left: none;
        padding-left: 6px;
      }
		
		

  
    
    /* for complex headers */
    
    .complex.fixed-table-container {
      padding-top: 60px; /* height of header */
      overflow-x: hidden; /* for border */
    }
    
    .complex .header-background {
      height: 60px;
    }
    
    .complex-top .th-inner {
      border-bottom: 3px ridge #9CC346;
      width: 100%;
	  
    }
    
    .complex-bottom .th-inner {
      top: 30px;
      width: 100%
    }
    
    .complex-top .third .th-inner { /* double row cell */
      height: 60px;
      border-bottom: none;
	  
	  
    }
	</style>
	<div class="fixed-table-container complex">
      <div class="header-background"> </div>
      <div class="fixed-table-container-inner">
        <table cellspacing="0" class="scroll">
				<thead>
					<tr class="complex-top" bgcolor="#9CC346" >
					<th width="100px" class="third" rowspan="2" align="center" style="font-size:12px; " ><div class="th-inner">AFD <br>KARYAWAN</div></th>
					<th width="80px" class="third" rowspan="2" align="center" style="font-size:14px;" ><div class="th-inner"><div style="margin-left:50px">NIK</div></div></th>
					<th class="third" rowspan="2" align="center" style="font-size:14px; " ><div class="th-inner"><div style="margin-left:20px">Nama Karyawan</div></div></th>
					<th class="third" rowspan="2" align="center" style="font-size:14px;" ><div class="th-inner"><div style="margin-left:12px">HA</div></div></th>
					<th class="third" colspan="2" align="center" style="font-size:14px; " ><div class="th-inner"><div style="margin-left:10px">Hasil Panen</div></div></th>
					<th class="third" colspan="12" align="center" style="font-size:14px; " ><div class="th-inner"><div style="margin-left:220px">Pinalty</div></div></th>
					<th width="80px" class="third" rowspan="2" align="center" style="font-size:12px; " ><div class="th-inner">eBCC <br>Validation</div></th>
					<th width="80px" class="third" rowspan="2" align="center" style="font-size:12px; " ><div class="th-inner">Validasi <br>Lokasi</div></th>
				</tr>
				<tr class="complex-bottom" bgcolor="#9CC346">
					
					<th width="50px" class="first" align="center" style="font-size:14px; " ><div class="th-inner">TBS</div></th>
					<th width="50px" class="third" align="center" style="font-size:14px; " ><div class="th-inner">BRD</div></th>
					<th width="35px" class="third" align="center" style="font-size:14px; " ><div class="th-inner">BM</div></th>
					<th width="35px" class="third" align="center" style="font-size:14px; " ><div class="th-inner">BK</div></th>
					<th width="35px" class="third" align="center" style="font-size:14px; " ><div class="th-inner">TP</div></th>
					<th width="35px" class="third" align="center" style="font-size:14px; " ><div class="th-inner">BB</div></th>
					<th width="35px" class="third" align="center" style="font-size:14px; " ><div class="th-inner">JK</div></th>
					<th width="35px" class="third" align="center" style="font-size:14px; " ><div class="th-inner">BA</div></th>
					<th width="35px" class="third" align="center" style="font-size:14px; " ><div class="th-inner">BT</div></th>
					<th width="35px" class="third" align="center" style="font-size:14px; " ><div class="th-inner">BL</div></th>
					<th width="35px" class="third" align="center" style="font-size:14px; " ><div class="th-inner">PB</div></th>
					<th width="35px" class="third" align="center" style="font-size:14px; " ><div class="th-inner">AB</div></th>
					<th width="35px" class="third" align="center" style="font-size:14px; " ><div class="th-inner">SF</div></th>
					<th width="35px" class="third" align="center" style="font-size:14px; " ><div class="th-inner">BS</div></th>
				</tr>
				</thead>
			
					<tbody>
				
				<?php 
				$total_HA = 0;
				$total_TBS = 0;
				$total_BRD = 0;
				$total_BM = 0;
				$total_BK = 0;
				$total_TP = 0;
				$total_BB = 0;
				$total_JK = 0;
				$total_BA = 0;
				$total_BT = 0;
				$total_BL = 0;
				$total_PB = 0;
				$total_AB = 0;
				$total_SF = 0;
				$total_BS = 0;
				
				$sementara_HA = 0;
				$sementara_TBS = 0;
				$sementara_BRONDOLAN = 0;
				$sementara_BM = 0;
				$sementara_BK = 0;
				$sementara_TP = 0;
				$sementara_BB = 0;
				$sementara_JK = 0;
				$sementara_BA = 0;
				$sementara_BT = 0;
				$sementara_BL = 0;
				$sementara_PB = 0;
				$sementara_AB = 0;
				$sementara_SF = 0;
				$sementara_BS = 0;
				
				$lolos_validasi = 0;
				$valid_me = 0;
				$jml_valid = 0;
				$status_validasi_kabun_or_em = '';
				$jml_record = 0;
				$valid_cetak = 0;
				$invalid_latlong = 0;
				$invalid_lokasi = 0;
				$tmp = array();

				$total_invalid_lokasi = 0;
				if ($roweffec < 1) {
				?>
							<tr>
								<td width="71" align="center" >-</td>
								<td width="160" align="center"  >-</td>
								<td width="160" align="center"  >-</td>
								<td width="50" align="center"  >0.00</td>
								<td width="40" align="center"  >0</td>
								<td width="40" align="center"  ></td>
								<td width="35" align="center"  >0</td>
								<td width="35" align="center"  >0</td>
								<td width="35" align="center"  >0</td>
								<td width="35" align="center"  >0</td>
								<td width="35" align="center"  >0</td>
								<td width="35" align="center"  >0</td>
								<td width="35" align="center"  >0</td>
								<td width="35" align="center"  >0</td>
								<td width="35" align="center"  >0</td>
								<td width="35" align="center"  >0</td>
								<td width="35" align="center"  >0</td>
								<td width="35" align="center"  >0</td>
								<td width="70" align="center"  > </td>
								<td width="70" align="center"> </td>
							</tr>
				<?php }
				for ($z = 0; $z < $roweffec; $z++) {
					$inactive[$bcc_export[$z]['NIK_KARYAWAN']] = 0;
					/*(
                CASE
                    WHEN THP.STATUS_TPH = 'MANUAL' THEN (
                        CASE
                            WHEN TMT.LATITUDE IS NULL
                                 OR TMT.LONGITUDE IS NULL THEN 0.1
                            ELSE ROUND(SDO_GEOM.SDO_DISTANCE(SDO_GEOM.SDO_GEOMETRY(2001,8307,SDO_GEOM.SDO_POINT_TYPE(NVL(TMT.LATITUDE,0),NVL(TMT.LONGITUDE,0),NULL),NULL,NULL),SDO_GEOM.SDO_GEOMETRY(2001,8307,SDO_POINT_TYPE(NVL(THP.LATITUDE,0),NVL(THP.LONGITUDE,0),NULL),NULL,NULL),0.0001,'unit=M') )
                        END
                    )
                    ELSE 0.1
                END
            ) JARAK,*/






            		//echo $
            		//echo 'TPH Latitude : ' . $bcc_export[$z]['M_LATITUDE']; echo '<br />';
            		//echo 'TPH Longitude : ' . $bcc_export[$z]['M_LONGITUDE']; echo '<br />';
            		//echo 'M Latitude : ' . $bcc_export[$z]['LATITUDE']; echo '<br />';
            		//echo 'M Longitude : ' . $bcc_export[$z]['LONGITUDE']; echo '<br /><br />';
            		if ($bcc_export[$z]['STATUS_TPH'] == 'MANUAL') {
            			if (empty($bcc_export[$z]['M_LATITUDE']) && empty($bcc_export[$z]['M_LONGITUDE'])) {
            				$jarak = 0.1;
            			} else {
            				$jarak = round(distance($bcc_export[$z]['M_LATITUDE'], $bcc_export[$z]['M_LONGITUDE'], $bcc_export[$z]['LATITUDE'], $bcc_export[$z]['LONGITUDE'], "K") * 1000);
            			}
            		} else {
            			$jarak = 0;
            		}

					//echo 'Status TPH : ' . $bcc_export[$z]['STATUS_TPH']; echo '<br />';
					//echo 'Status Detic : ' . $bcc_export[$z]['STATUS_DETIC']; echo '<br />';
					//echo 'Toleransi Jarak : ' . $bcc_export[$z]['TOLERANSI_JARAK']; echo '<br />';
					//echo 'Jarak : ' . $jarak; echo '<br /><br />';

					if (!empty($bcc_export[$z]['STATUS_TPH']) && !empty($bcc_export[$z]['STATUS_DETIC']) && $bcc_export[$z]['STATUS_TPH'] == 'MANUAL') {
						if ($jarak == '0.1') {
							$jarak = $jarak + $bcc_export[$z]['TOLERANSI_JARAK'];
						} else {
							$jarak = $jarak;
						}

						if( $bcc_export[$z]['STATUS_TPH'] == 'MANUAL' ){
							if($bcc_export[$z]['STATUS_LOKASI'] != 1){
								$total_invalid_lokasi++;
								$tmp[] = array(
									'$STATUS_TPH'	=> $bcc_export[$z]['STATUS_TPH'],
									'$STATUS_DETIC'	=> $bcc_export[$z]['STATUS_DETIC'],
									'$M_LATITUDE'	=> $bcc_export[$z]['M_LATITUDE'],
									'$M_LONGITUDE'	=> $bcc_export[$z]['M_LONGITUDE'],
									'$LATITUDE'	=> $bcc_export[$z]['LATITUDE'],
									'$LONGITUDE'	=> $bcc_export[$z]['LONGITUDE'],
									'$jarak'	=> $jarak,
									'$TOLERANSI_JARAK'	=> $bcc_export[$z]['TOLERANSI_JARAK'],
									'$STATUS_LOKASI'	=> $bcc_export[$z]['STATUS_LOKASI'],
									'$TBS'	=> $bcc_export[$z]['TBS'],
									'$NIK_KARYAWAN'	=> $bcc_export[$z]['NIK_KARYAWAN'],
								);
							}
						}else{
							if (($jarak > $bcc_export[$z]['TOLERANSI_JARAK']) && $bcc_export[$z]['STATUS_LOKASI'] != '1') {
								$total_invalid_lokasi++;
								
								$tmp[] = array(
									'$STATUS_TPH'	=> $bcc_export[$z]['STATUS_TPH'],
									'$STATUS_DETIC'	=> $bcc_export[$z]['STATUS_DETIC'],
									'$M_LATITUDE'	=> $bcc_export[$z]['M_LATITUDE'],
									'$M_LONGITUDE'	=> $bcc_export[$z]['M_LONGITUDE'],
									'$LATITUDE'	=> $bcc_export[$z]['LATITUDE'],
									'$LONGITUDE'	=> $bcc_export[$z]['LONGITUDE'],
									'$jarak'	=> $jarak,
									'$TOLERANSI_JARAK'	=> $bcc_export[$z]['TOLERANSI_JARAK'],
									'$STATUS_LOKASI'	=> $bcc_export[$z]['STATUS_LOKASI'],
									'$TBS'	=> $bcc_export[$z]['TBS'],
									'$NIK_KARYAWAN'	=> $bcc_export[$z]['NIK_KARYAWAN'],
								);
							}
						}
					}
				}
				//echo $total_invalid_lokasi;
				
				//echo $roweffec;
				$inactive_tph = 0;
				$inactive_tph_list = array();
				for ($counter = 0; $counter < $roweffec; $counter++){
					if($bcc_export[$counter]['TPH_STATUS']==1){
						// echo 12345678;die;
						$inactive[$bcc_export[$counter]['NIK_KARYAWAN']] += 1;
						$inactive_tph_list[
							$bcc_export[$counter]['BLOK']
						][] = array( 
							$bcc_export[$counter]['BA_KERJA'],
							$bcc_export[$counter]['BA_KERJA'],
							$bcc_export[$counter]['BLOK'],
							$bcc_export[$counter]['NO_TPH'],
						);
					}
					//cek cetak bcc
					if($bcc_export[$counter]['CETAK_BCC']==''){
						$valid_cetak++;
					}

            		if ($bcc_export[$counter]['STATUS_TPH'] == 'MANUAL') {
            			if (empty($bcc_export[$counter]['M_LATITUDE']) && empty($bcc_export[$counter]['M_LONGITUDE'])) {
            				$jarak = 0.1;
            			} else {
            				$jarak = round(distance($bcc_export[$counter]['M_LATITUDE'], $bcc_export[$counter]['M_LONGITUDE'], $bcc_export[$counter]['LATITUDE'], $bcc_export[$counter]['LONGITUDE'], "K") * 1000);
            			}
            		} else {
            			$jarak = 0.1;
            		}
					//echo 'Status : ' . $bcc_export[$counter]['STATUS_TPH'] . '<br />';
					if (!empty($bcc_export[$counter]['STATUS_TPH']) && !empty($bcc_export[$counter]['STATUS_DETIC']) && $bcc_export[$counter]['STATUS_TPH'] == 'MANUAL') {
						if ($jarak == '0.1') {
							$jarak = $jarak + $bcc_export[$counter]['TOLERANSI_JARAK'];
						} else {
							$jarak = $jarak;
						}

						if (($jarak > $bcc_export[$counter]['TOLERANSI_JARAK']) || $bcc_export[$counter]['STATUS_LOKASI'] == '0' || empty($bcc_export[$counter]['STATUS_LOKASI']) || $bcc_export[$counter]['STATUS_LOKASI'] === NULL) {
							$invalid_latlong++;
						}
					}

					if($bcc_export[$counter]['TANGGAL']!=$bcc_export[$counter-1]['TANGGAL'] || 
					$bcc_export[$counter]['AFD_KERJA']!=$bcc_export[$counter-1]['AFD_KERJA'] || 
					$bcc_export[$counter]['BLOK']!=$bcc_export[$counter-1]['BLOK'] || 
					$bcc_export[$counter]['NIK_KARYAWAN']!=$bcc_export[$counter-1]['NIK_KARYAWAN'] || 
					$bcc_export[$counter]['NIK_MANDOR']!=$bcc_export[$counter-1]['NIK_MANDOR']
					){
					/* if($NO_REKAP_BCC[$counter] !== $NO_REKAP_BCC[$counter-1])
					{
						$total_HA += $LUASAN_PANEN[$counter];
					}
					else if($ID_RENCANA[$counter] !== $ID_RENCANA[$counter-1])
					{ */
						$total_HA += $bcc_export[$counter]['LUASAN_PANEN'];
					}
					else
					{
						$total_HA += 0;
					}
					
					
					$total_TBS += $bcc_export[$counter]['TBS'];
					//$total_BRONDOLAN += $bcc_export[$counter]['BRONDOLAN'];
					$total_BRONDOLAN += $bcc_export[$counter]['BRD'];
					$total_BM += $bcc_export[$counter]['BM'];
					$total_BK += $bcc_export[$counter]['BK'];
					$total_TP += $bcc_export[$counter]['TP'];
					$total_BB += $bcc_export[$counter]['BB'];
					$total_JK += $bcc_export[$counter]['JK'];
					$total_BA += $bcc_export[$counter]['BA'];
					$total_BT += $bcc_export[$counter]['BT'];
					$total_BL += $bcc_export[$counter]['BL'];
					$total_PB += $bcc_export[$counter]['PB'];
					$total_AB += $bcc_export[$counter]['AB'];
					$total_SF += $bcc_export[$counter]['SF'];
					$total_BS += $bcc_export[$counter]['BS'];
					
					$invalid_lokasi = 0;
					if($counter==0){

						$sementara_HA += $bcc_export[$counter]['LUASAN_PANEN'];
						$sementara_TBS += $bcc_export[$counter]['TBS'];
						//$sementara_BRONDOLAN += $bcc_export[$counter]['BRONDOLAN'];
						$sementara_BRONDOLAN += $bcc_export[$counter]['BRD'];
						$sementara_BM += $bcc_export[$counter]['BM'];
						$sementara_BK += $bcc_export[$counter]['BK'];
						$sementara_TP += $bcc_export[$counter]['TP'];
						$sementara_BB += $bcc_export[$counter]['BB'];
						$sementara_JK += $bcc_export[$counter]['JK'];
						$sementara_BA += $bcc_export[$counter]['BA'];
						$sementara_BT += $bcc_export[$counter]['BT'];
						$sementara_BL += $bcc_export[$counter]['BL'];
						$sementara_PB += $bcc_export[$counter]['PB'];
						$sementara_AB += $bcc_export[$counter]['AB'];
						$sementara_SF += $bcc_export[$counter]['SF'];
						$sementara_BS += $bcc_export[$counter]['BS'];	
						//echo "1. " . $bcc_export[$counter]['NAMA_KARYAWAN'] . ' - ' . $bcc_export[$counter]['NO_BCC'] . ' - ' . $bcc_export[$counter]['STATUS_TPH'] . ' - ' . $bcc_export[$counter]['STATUS_LOKASI']; echo '<br />';
						
						if($bcc_export[$counter]['VALIDASI_BCC']!="" and $bcc_export[$counter]['VALIDASI_DATE']!=""){
							$lolos_validasi++;
						}
						$query_cek_valid_me = "
							select * from 
							(select * from MOBILE_ESTATE.TR_EBCC 
							where  DATA_FROM = 'EBCC_VALIDATION'
							and DELIVERY_TICKET = '".$bcc_export[$counter]['KODE_DELIVERY_TICKET']."'
							UNION
							select * from MOBILE_INSPECTION.TR_EBCC
							)
							where 
							WERKS = '".$bcc_export[$counter]['BA_KERJA']."' and AFD_CODE = '".$bcc_export[$counter]['AFD_KERJA']."' 
							and BLOCK_CODE = '".$bcc_export[$counter]['BLOK']."' and TPH_CODE = '".$bcc_export[$counter]['NO_TPH']."' 
							and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$bcc_export[$counter]['TANGGAL']."','DD.MM.YYYY')
							";
						//echo $query_cek_valid_me;
						//Table sudah di union dengan mobile_inspection tidak perlu lagi kondisi 
						/* if (($bcc_export[$counter]['BA_KERJA'] == '4121')||($bcc_export[$counter]['BA_KERJA'] == '4122')||($bcc_export[$counter]['BA_KERJA'] == '4221')||($bcc_export[$counter]['BA_KERJA'] == '4123')){
						 //ini untuk pt yang sudah menggunakan mobile inspection
							$query_cek_valid_me = "
							select * from MOBILE_ESTATE.TR_EBCC where 
							WERKS = '".$bcc_export[$counter]['BA_KERJA']."' and AFD_CODE = '".$bcc_export[$counter]['AFD_KERJA']."' 
							and BLOCK_CODE = '".$bcc_export[$counter]['BLOK']."' and TPH_CODE = '".$bcc_export[$counter]['NO_TPH']."' 
							and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$bcc_export[$counter]['TANGGAL']."','DD.MM.YYYY')
							and DATA_FROM = 'MOBILE_INS'";	
						
						}else{
						//ini untuk pt yang belum menggunakan mobile inspection
							$query_cek_valid_me = "
							select * from 
							(select * from 
							MOBILE_ESTATE.TR_EBCC 
							where  DATA_FROM = 'EBCC_VALIDATION'
							UNION
							select * from mobile_inspection.tr_ebcc
							)
							where 
							WERKS = '".$bcc_export[$counter]['BA_KERJA']."' and AFD_CODE = '".$bcc_export[$counter]['AFD_KERJA']."' 
							and BLOCK_CODE = '".$bcc_export[$counter]['BLOK']."' and TPH_CODE = '".$bcc_export[$counter]['NO_TPH']."' 
							and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$bcc_export[$counter]['TANGGAL']."','DD.MM.YYYY')
							";
						} */
						
						//echo $query_cek_valid_me."<br>";
						$result_me_cek = oci_parse($con, $query_cek_valid_me);
						oci_execute($result_me_cek, OCI_DEFAULT);
						$result_me_count = oci_fetch_all($result_me_cek, $result_record, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
						if($result_me_count>0){
							$valid_me = 1;
						}

						$cek_status_lokasi = "
							SELECT thp.status_tph, thp.status_lokasi from t_header_rencana_panen thrp
							left join t_hasil_panen thp on thp.id_rencana = thrp.id_rencana
							where nik_pemanen = '{$bcc_export[$counter]['NIK_KARYAWAN']}' 
							and to_char(tanggal_rencana, 'YYYY-MM-DD') = '{$date1}'
						";

            			if ($bcc_export[$counter]['STATUS_TPH'] == 'MANUAL') {
            				if (empty($bcc_export[$counter]['M_LATITUDE']) && empty($bcc_export[$counter]['M_LONGITUDE'])) {
            					$jarak = 0.1;
            				} else {
            					$jarak = round(distance($bcc_export[$counter]['M_LATITUDE'], $bcc_export[$counter]['M_LONGITUDE'], $bcc_export[$counter]['LATITUDE'], $bcc_export[$counter]['LONGITUDE'], "K") * 1000);
            				}
            			} else {
            				$jarak = 0.1;
            			}

						if (!empty($bcc_export[$counter]['STATUS_TPH']) && !empty($bcc_export[$counter]['STATUS_DETIC']) && $bcc_export[$counter]['STATUS_TPH'] == 'MANUAL') {
							if ($jarak == '0.1') {
								$jarak = $jarak + $bcc_export[$counter]['TOLERANSI_JARAK'];
							} else {
								$jarak = $jarak;
							}

							if (($jarak > $bcc_export[$counter]['TOLERANSI_JARAK']) && $bcc_export[$counter]['STATUS_LOKASI'] != '1') {
								$invalid_lokasi++;
							}
						}

						if ($bcc_export[$counter]['STATUS_TPH'] == 'AUTOMATIC') {
							$invalid_lokasi = 0;
						}

					} else {
						//echo 'Valid Me : ' . $valid_me . '<br />';
						//echo 'NIK Karyawan Counter : ';
						//echo $bcc_export[$counter]['NIK_KARYAWAN']; echo '<br />';
						//echo 'NIK Karyawan Counter - 1 : ';
						//echo $bcc_export[$counter-1]['NIK_KARYAWAN']; echo '<br />';
						//echo 'Lolos Validasi : ' . $lolos_validasi . '<br />';

						//$invalid_lokasi = 0;
						if($bcc_export[$counter]['NIK_KARYAWAN']!=$bcc_export[$counter-1]['NIK_KARYAWAN']){
							$jml_record++;
							if($valid_me==1){
								$symbol = "&#x2713;";
								//$invalid_lokasi = 'atas-def';
								$jml_valid++;
							} else {
								if($lolos_validasi>0){
									$symbol = "&#x2713;";
									//$invalid_lokasi = 'atas-ghi';
									$jml_valid++;
								} else {
									$symbol = "";
									//$invalid_lokasi = '0';
								}
							}	
							
							//echo $bcc_export[$counter-1]['NIK_KARYAWAN'] . ' - ' . $bcc_export[$counter-1]['NO_BCC'] . ' - ' . $bcc_export[$counter-1]['STATUS_TPH'] . ' - ' . $bcc_export[$counter-1]['STATUS_LOKASI']; echo '<br />';
            				if ($bcc_export[$counter-1]['STATUS_TPH'] == 'MANUAL') {
            					if (empty($bcc_export[$counter-1]['M_LATITUDE']) && empty($bcc_export[$counter-1]['M_LONGITUDE'])) {
            						$jarak = 0.1;
            					} else {
            						$jarak = round(distance($bcc_export[$counter-1]['M_LATITUDE'], $bcc_export[$counter-1]['M_LONGITUDE'], $bcc_export[$counter-1]['LATITUDE'], $bcc_export[$counter-1]['LONGITUDE'], "K") * 1000);
            					}
            				} else {
            					$jarak = 0.1;
            				}

							if (!empty($bcc_export[$counter-1]['STATUS_TPH']) && !empty($bcc_export[$counter-1]['STATUS_DETIC']) && $bcc_export[$counter-1]['STATUS_TPH'] == 'MANUAL') {
								if ($jarak == '0.1') {
									$jarak = $jarak + $bcc_export[$counter-1]['TOLERANSI_JARAK'];
								} else {
									$jarak = $jarak;
								}

								if (($jarak > $bcc_export[$counter-1]['TOLERANSI_JARAK']) && $bcc_export[$counter-1]['STATUS_LOKASI'] != '1') {
									$invalid_lokasi++;
								}
							}

							if ($bcc_export[$counter-1]['STATUS_TPH'] == 'AUTOMATIC') {
								$invalid_lokasi = $invalid_lokasi + 0;
							}

							$status_invalid_lokasi = ($invalid_lokasi < 1) ? "&#x2713;" : "";						//echo $bcc_export[$counter-1]['NAMA_KARYAWAN'] . " -- " . $sementara_TBS . "<br>";//die();
							//$symbol = ($inactive[$bcc_export[$counter-1]['NIK_KARYAWAN']] < 1) ? "&#x2713;" : ""; //tidak perlu

							?>
							<tr>
								<td width="71" align="center" ><?= $bcc_export[$counter-1]['AFD_PEMANEN'] ?></td>
								<td width="160" align="center"  ><?= $bcc_export[$counter-1]['NIK_KARYAWAN'] ?></td>
								<td width="160" align="center"  ><?= $bcc_export[$counter-1]['NAMA_KARYAWAN'] ?></td>
								<td width="50" align="center"  ><?= number_format($sementara_HA,2) ?></td>
								<td width="40" align="center"  ><?= $sementara_TBS ?></td>
								<td width="40" align="center"  ><?= $sementara_BRONDOLAN ?></td>
								<td width="35" align="center"  ><?= $sementara_BM ?></td>
								<td width="35" align="center"  ><?= $sementara_BK ?></td>
								<td width="35" align="center"  ><?= $sementara_TP ?></td>
								<td width="35" align="center"  ><?= $sementara_BB ?></td>
								<td width="35" align="center"  ><?= $sementara_JK ?></td>
								<td width="35" align="center"  ><?= $sementara_BA ?></td>
								<td width="35" align="center"  ><?= $sementara_BT ?></td>
								<td width="35" align="center"  ><?= $sementara_BL ?></td>
								<td width="35" align="center"  ><?= $sementara_PB ?></td>
								<td width="35" align="center"  ><?= $sementara_AB ?></td>
								<td width="35" align="center"  ><?= $sementara_SF ?></td>
								<td width="35" align="center"  ><?= $sementara_BS ?></td>
								<td width="70" align="center"  ><?= $symbol ?></td>
								<!--<td width="70" align="center"><?= $status_invalid_lokasi ?></td>-->
								
								<td width="70" align="center">
									<?php 
										$cek_status_lokasi = "
											SELECT
												thp.latitude, thp.longitude, tmt.latitude as m_latitude, tmt.longitude as m_longitude, thp.status_lokasi,
												(SELECT NILAI FROM T_PARAMETER WHERE KETERANGAN = 'RANGE') AS TOLERANSI_JARAK
											FROM
												t_header_rencana_panen thrp
											INNER JOIN t_detail_rencana_panen tdrp ON thrp.id_rencana = tdrp.id_rencana
											INNER JOIN t_hasil_panen thp ON tdrp.id_rencana = thp.id_rencana AND tdrp.no_rekap_bcc = thp.no_rekap_bcc
											INNER JOIN t_blok tb ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
											LEFT JOIN TAP_DW.TM_TPH@PRODDW_LINK TMT ON TB.ID_BA_AFD_BLOK = TMT.WERKS || TMT.AFD_CODE || TMT.BLOCK_CODE AND THP.NO_TPH = TMT.NO_TPH 
											WHERE
												nik_pemanen = '{$bcc_export[$counter-1]['NIK_KARYAWAN']}'
											AND   TO_CHAR(tanggal_rencana,'YYYY-MM-DD') = '{$date1}'
											AND   thp.status_tph = 'MANUAL'
											AND   thp.status_lokasi IS NULL
										";

										$result_status_lokasi = oci_parse($con, $cek_status_lokasi);
										oci_execute($result_status_lokasi);
										$res_status_lokasi = oci_fetch_all($result_status_lokasi, $res_lokasi, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);

										$total_salah = 0;
										foreach ($res_lokasi as $k => $v) {
											if (empty($v['M_LATITUDE']) && empty($v['M_LONGITUDE'])) {
												$jarak = 0.1;
											} else {
												$jarak = round(distance($v['M_LATITUDE'], $v['M_LONGITUDE'], $v['LATITUDE'], $v['LONGITUDE'], "K") * 1000);
											}

											if ($jarak == '0.1' || $jarak > $v['TOLERANSI_JARAK']) {
												$total_salah++;
											}
										}

										echo ($total_salah > 0) ? "" : "&#x2713;";
									?>
								</td>
							</tr>
							<?php 
							$sementara_HA = 0;
							$sementara_TBS = 0;
							$sementara_BRONDOLAN = 0;
							$sementara_BM = 0;
							$sementara_BK = 0;
							$sementara_TP = 0;
							$sementara_BB = 0;
							$sementara_JK = 0;
							$sementara_BA = 0;
							$sementara_BT = 0;
							$sementara_BL = 0;
							$sementara_PB = 0;
							$sementara_AB = 0;
							$sementara_SF = 0;
							$sementara_BS = 0;
							
							$lolos_validasi = 0;
							$valid_me = 0;
							
							$sementara_HA += $bcc_export[$counter]['LUASAN_PANEN'];
							$sementara_TBS += $bcc_export[$counter]['TBS'];
							//$sementara_BRONDOLAN += $bcc_export[$counter]['BRONDOLAN'];
							$sementara_BRONDOLAN += $bcc_export[$counter]['BRD'];
							$sementara_BM += $bcc_export[$counter]['BM'];
							$sementara_BK += $bcc_export[$counter]['BK'];
							$sementara_TP += $bcc_export[$counter]['TP'];
							$sementara_BB += $bcc_export[$counter]['BB'];
							$sementara_JK += $bcc_export[$counter]['JK'];
							$sementara_BA += $bcc_export[$counter]['BA'];
							$sementara_BT += $bcc_export[$counter]['BT'];
							$sementara_BL += $bcc_export[$counter]['BL'];
							$sementara_PB += $bcc_export[$counter]['PB'];
							$sementara_AB += $bcc_export[$counter]['AB'];
							$sementara_SF += $bcc_export[$counter]['SF'];
							$sementara_BS += $bcc_export[$counter]['BS'];	
							//echo "2. " . $bcc_export[$counter]['NAMA_KARYAWAN'] . ' - ' . $bcc_export[$counter]['NO_BCC'] . ' - ' . $bcc_export[$counter]['STATUS_TPH'] . ' - ' . $bcc_export[$counter]['STATUS_LOKASI']; echo '<br />';

							//echo 'Validasi BCC : ' . $bcc_export[$counter]['VALIDASI_BCC'] . '<br />';
							//echo 'Validasi Date : ' . $bcc_export[$counter]['VALIDASI_DATE'] . '<br />';
							if($bcc_export[$counter]['VALIDASI_BCC']!="" and $bcc_export[$counter]['VALIDASI_DATE']!=""){
								$lolos_validasi++;
							}
							//echo 'Lolos Validasi : ' . $lolos_validasi . '<br />';

							if($valid_me==0){
								$query_cek_valid_me = "
									select * from 
									(select * from MOBILE_ESTATE.TR_EBCC 
									where  DATA_FROM = 'EBCC_VALIDATION'
									and DELIVERY_TICKET = '".$bcc_export[$counter]['KODE_DELIVERY_TICKET']."'
									UNION
									select * from MOBILE_INSPECTION.TR_EBCC
									)
									where 
									WERKS = '".$bcc_export[$counter]['BA_KERJA']."' and AFD_CODE = '".$bcc_export[$counter]['AFD_KERJA']."' 
									and BLOCK_CODE = '".$bcc_export[$counter]['BLOK']."' and TPH_CODE = '".$bcc_export[$counter]['NO_TPH']."' 
									and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$bcc_export[$counter]['TANGGAL']."','DD.MM.YYYY')
									";
								/* if (($bcc_export[$counter]['BA_KERJA'] == '4121')||($bcc_export[$counter]['BA_KERJA'] == '4122')||($bcc_export[$counter]['BA_KERJA'] == '4221')||($bcc_export[$counter]['BA_KERJA'] == '4123')){
									//ini buat mobile inspection
									$query_cek_valid_me = "
									select * from MOBILE_ESTATE.TR_EBCC where 
									WERKS = '".$bcc_export[$counter]['BA_KERJA']."' and AFD_CODE = '".$bcc_export[$counter]['AFD_KERJA']."' 
									and BLOCK_CODE = '".$bcc_export[$counter]['BLOK']."' and TPH_CODE = '".$bcc_export[$counter]['NO_TPH']."' 
									and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$bcc_export[$counter]['TANGGAL']."','DD.MM.YYYY')
									and DATA_FROM = 'MOBILE_INS'";
								}else{
									//ini buat mobile estate
									$query_cek_valid_me = "
									select * from MOBILE_ESTATE.TR_EBCC where 
									WERKS = '".$bcc_export[$counter]['BA_KERJA']."' and AFD_CODE = '".$bcc_export[$counter]['AFD_KERJA']."' 
									and BLOCK_CODE = '".$bcc_export[$counter]['BLOK']."' and TPH_CODE = '".$bcc_export[$counter]['NO_TPH']."' 
									and DELIVERY_TICKET = '".$bcc_export[$counter]['KODE_DELIVERY_TICKET']."' and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$bcc_export[$counter]['TANGGAL']."','DD.MM.YYYY')
									and DATA_FROM = 'EBCC_VALIDATION'";
								} */
								
								//echo $query_cek_valid_me."<br>";
								$result_me_cek = oci_parse($con, $query_cek_valid_me);
								oci_execute($result_me_cek, OCI_DEFAULT);
								$result_me_count = oci_fetch_all($result_me_cek, $result_record, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
								if($result_me_count>0){
									$valid_me = 1;
								}
							}
							//echo $valid_me;

						} else { 
							if($bcc_export[$counter]['TANGGAL']!=$bcc_export[$counter-1]['TANGGAL'] || 
							$bcc_export[$counter]['AFD_KERJA']!=$bcc_export[$counter-1]['AFD_KERJA'] || 
							$bcc_export[$counter]['BLOK']!=$bcc_export[$counter-1]['BLOK'] || 
							$bcc_export[$counter]['NIK_KARYAWAN']!=$bcc_export[$counter-1]['NIK_KARYAWAN'] || 
							$bcc_export[$counter]['NIK_MANDOR']!=$bcc_export[$counter-1]['NIK_MANDOR']
							){
							/* if($NO_REKAP_BCC'] !== $NO_REKAP_BCC[$counter-1])
							{
								$total_HA += $LUASAN_PANEN'];
							}
							else if($ID_RENCANA'] !== $ID_RENCANA[$counter-1])
							{ */
								$sementara_HA += $bcc_export[$counter]['LUASAN_PANEN'];
							}
							else
							{
								$sementara_HA += 0;
							}
							
							$sementara_TBS += $bcc_export[$counter]['TBS'];
							//$sementara_BRONDOLAN += $bcc_export[$counter]['BRONDOLAN'];
							$sementara_BRONDOLAN += $bcc_export[$counter]['BRD'];
							$sementara_BM += $bcc_export[$counter]['BM'];
							$sementara_BK += $bcc_export[$counter]['BK'];
							$sementara_TP += $bcc_export[$counter]['TP'];
							$sementara_BB += $bcc_export[$counter]['BB'];
							$sementara_JK += $bcc_export[$counter]['JK'];
							$sementara_BA += $bcc_export[$counter]['BA'];
							$sementara_BT += $bcc_export[$counter]['BT'];
							$sementara_BL += $bcc_export[$counter]['BL'];
							$sementara_PB += $bcc_export[$counter]['PB'];
							$sementara_AB += $bcc_export[$counter]['AB'];
							$sementara_SF += $bcc_export[$counter]['SF'];
							$sementara_BS += $bcc_export[$counter]['BS'];	
							//echo "3. " . $bcc_export[$counter]['NAMA_KARYAWAN'] . ' - ' . $bcc_export[$counter]['NO_BCC'] . ' - ' . $bcc_export[$counter]['STATUS_TPH'] . ' - ' . $bcc_export[$counter]['STATUS_LOKASI']; echo '<br />';
							
							if($bcc_export[$counter]['VALIDASI_BCC']!="" and $bcc_export[$counter]['VALIDASI_DATE']!=""){
								$lolos_validasi++;
							}
							
							if($valid_me==0){
								$query_cek_valid_me = "
									select * from 
									(select * from MOBILE_ESTATE.TR_EBCC 
									where  DATA_FROM = 'EBCC_VALIDATION'
									and DELIVERY_TICKET = '".$bcc_export[$counter]['KODE_DELIVERY_TICKET']."'
									UNION
									select * from MOBILE_INSPECTION.TR_EBCC
									)
									where 
									WERKS = '".$bcc_export[$counter]['BA_KERJA']."' and AFD_CODE = '".$bcc_export[$counter]['AFD_KERJA']."' 
									and BLOCK_CODE = '".$bcc_export[$counter]['BLOK']."' and TPH_CODE = '".$bcc_export[$counter]['NO_TPH']."' 
									and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$bcc_export[$counter]['TANGGAL']."','DD.MM.YYYY')
									";
								/* if (($bcc_export[$counter]['BA_KERJA'] == '4121')||($bcc_export[$counter]['BA_KERJA'] == '4122')||($bcc_export[$counter]['BA_KERJA'] == '4221')||($bcc_export[$counter]['BA_KERJA'] == '4123')){
									//dari Mobile Inspection
									$query_cek_valid_me = "
									select * from MOBILE_ESTATE.TR_EBCC where 
									WERKS = '".$bcc_export[$counter]['BA_KERJA']."' and AFD_CODE = '".$bcc_export[$counter]['AFD_KERJA']."' 
									and BLOCK_CODE = '".$bcc_export[$counter]['BLOK']."' and TPH_CODE = '".$bcc_export[$counter]['NO_TPH']."' 
									and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$bcc_export[$counter]['TANGGAL']."','DD.MM.YYYY')
									and DATA_FROM = 'MOBILE_INS'";
								}else{
									//dari Mobile Estate
									$query_cek_valid_me = "
									select * from MOBILE_ESTATE.TR_EBCC where 
									WERKS = '".$bcc_export[$counter]['BA_KERJA']."' and AFD_CODE = '".$bcc_export[$counter]['AFD_KERJA']."' 
									and BLOCK_CODE = '".$bcc_export[$counter]['BLOK']."' and TPH_CODE = '".$bcc_export[$counter]['NO_TPH']."' 
									and DELIVERY_TICKET = '".$bcc_export[$counter]['KODE_DELIVERY_TICKET']."' and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$bcc_export[$counter]['TANGGAL']."','DD.MM.YYYY')
									and DATA_FROM = 'EBCC_VALIDATION'";
								} */
								
								//echo $query_cek_valid_me."<br>";
								$result_me_cek = oci_parse($con, $query_cek_valid_me);
								oci_execute($result_me_cek, OCI_DEFAULT);
								$result_me_count = oci_fetch_all($result_me_cek, $result_record, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
								if($result_me_count>0){
									$valid_me = 1;
								}
								
								
							}
							//echo $valid_me;
						}	
					}
					
					if($counter==$roweffec-1){
						$jml_record++;
						if($bcc_export[$counter]['VALIDASI_BCC']!="" and $bcc_export[$counter]['VALIDASI_DATE']!=""){
							$lolos_validasi++;
						}
						
						if($valid_me==0){
							$query_cek_valid_me = "
								select * from 
								(select * from MOBILE_ESTATE.TR_EBCC 
								where  DATA_FROM = 'EBCC_VALIDATION'
								and DELIVERY_TICKET = '".$bcc_export[$counter]['KODE_DELIVERY_TICKET']."'
								UNION
								select * from MOBILE_INSPECTION.TR_EBCC
								)
								where 
								WERKS = '".$bcc_export[$counter]['BA_KERJA']."' and AFD_CODE = '".$bcc_export[$counter]['AFD_KERJA']."' 
								and BLOCK_CODE = '".$bcc_export[$counter]['BLOK']."' and TPH_CODE = '".$bcc_export[$counter]['NO_TPH']."' 
								and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$bcc_export[$counter]['TANGGAL']."','DD.MM.YYYY')
								";
							/* if (($bcc_export[$counter]['BA_KERJA'] == '4121')||($bcc_export[$counter]['BA_KERJA'] == '4122')||($bcc_export[$counter]['BA_KERJA'] == '4221')||($bcc_export[$counter]['BA_KERJA'] == '4123')){	
								//dari Mobile Inspection
								$query_cek_valid_me = "
								select * from MOBILE_ESTATE.TR_EBCC where 
								WERKS = '".$bcc_export[$counter]['BA_KERJA']."' and AFD_CODE = '".$bcc_export[$counter]['AFD_KERJA']."' 
								and BLOCK_CODE = '".$bcc_export[$counter]['BLOK']."' and TPH_CODE = '".$bcc_export[$counter]['NO_TPH']."' 
								and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$bcc_export[$counter]['TANGGAL']."','DD.MM.YYYY')
								and DATA_FROM = 'MOBILE_INS'";
							}else{
								//dari Mobile Estate
								$query_cek_valid_me = "
								select * from MOBILE_ESTATE.TR_EBCC where 
								WERKS = '".$bcc_export[$counter]['BA_KERJA']."' and AFD_CODE = '".$bcc_export[$counter]['AFD_KERJA']."' 
								and BLOCK_CODE = '".$bcc_export[$counter]['BLOK']."' and TPH_CODE = '".$bcc_export[$counter]['NO_TPH']."' 
								and DELIVERY_TICKET = '".$bcc_export[$counter]['KODE_DELIVERY_TICKET']."' and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$bcc_export[$counter]['TANGGAL']."','DD.MM.YYYY')
								and DATA_FROM = 'EBCC_VALIDATION'";
							} */
							
							//echo $query_cek_valid_me."<br>";
							$result_me_cek = oci_parse($con, $query_cek_valid_me);
							oci_execute($result_me_cek, OCI_DEFAULT);
							$result_me_count = oci_fetch_all($result_me_cek, $result_record, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
							if($result_me_count>0){
								$valid_me = 1;
							}
						}
						if($valid_me==1){
							$symbol = "&#x2713;";
							//$invalid_lokasi = '0';
							$jml_valid++;
						} else {
							//echo $lolos_validasi;
							if($lolos_validasi>0){
								$symbol = "&#x2713;";
								//$invalid_lokasi = '0';
								$jml_valid++;
							} else {
								$symbol = "";
								//$invalid_lokasi = '0';
							}
						}
						//echo 'jlh' . $jml_valid;
						//echo $bcc_export[$counter]['NIK_KARYAWAN'] . ' - ' . $bcc_export[$counter]['NO_BCC'] . ' - ' . $bcc_export[$counter]['STATUS_TPH'] . ' - ' . $bcc_export[$counter]['STATUS_LOKASI']; echo '<br />';
	            		if ($bcc_export[$counter]['STATUS_TPH'] == 'MANUAL') {
	            			if (empty($bcc_export[$counter]['M_LATITUDE']) && empty($bcc_export[$counter]['M_LONGITUDE'])) {
	            				$jarak = 0.1;
	            			} else {
	            				$jarak = round(distance($bcc_export[$counter]['M_LATITUDE'], $bcc_export[$counter]['M_LONGITUDE'], $bcc_export[$counter]['LATITUDE'], $bcc_export[$counter]['LONGITUDE'], "K") * 1000);
	            			}
	            		} else {
	            			$jarak = 0.1;
	            		}

						if (!empty($bcc_export[$counter]['STATUS_TPH']) && !empty($bcc_export[$counter]['STATUS_DETIC']) && $bcc_export[$counter]['STATUS_TPH'] == 'MANUAL') {
							if ($jarak == '0.1') {
								$jarak = $jarak + $bcc_export[$counter]['TOLERANSI_JARAK'];
							} else {
								$jarak = $jarak;
							}

							if (($jarak > $bcc_export[$counter]['TOLERANSI_JARAK']) && $bcc_export[$counter]['STATUS_LOKASI'] != '1') {
								$invalid_lokasi++;
							}
						}

						if ($bcc_export[$counter]['STATUS_TPH'] == 'AUTOMATIC') {
							$invalid_lokasi = $invalid_lokasi + 0;
						}
						$status_invalid_lokasi = ($invalid_lokasi < 1) ? "&#x2713;" : "";
						// $symbol = ($inactive[$bcc_export[$counter]['NIK_KARYAWAN']] < 1) ? "&#x2713;" : "";		



					
					// START UPDATE AFTER VALIDATION KABUN DEPLOYMENT DECEMBER 2020
						// CHECK VALID IF HAVE DATA APPROVAL EM 
						$query_cek_valid_me = "select * from T_APPROVAL_CETAK_LHM 
						 						where ba='".$bcc_export[$counter]['BA_KERJA']."' 
						 						and afdeling='".$bcc_export[$counter]['AFD_KERJA']."' 
						 						and trunc(tanggal_ebcc) = TO_DATE('".$bcc_export[$counter]['TANGGAL']."', 'DD/MM/YYYY')";
						$result_me_cek = oci_parse($con, $query_cek_valid_me);
						oci_execute($result_me_cek, OCI_DEFAULT);
						$result_me_count = oci_fetch_all($result_me_cek, $result_record, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
						if($result_me_count>0){
							$status_validasi_kabun_or_em = "&#x2713;";
						}
						// CHECK VALID IF HAVE DATA IN MOBILE_INSPECTION.TR_EBCC_COMPARE
						$query_cek_valid_me = "select * from MOBILE_INSPECTION.TR_EBCC_COMPARE
						 						where val_werks='".$bcc_export[$counter]['BA_KERJA']."' 
						 						and VAL_AFD_CODE='".$bcc_export[$counter]['AFD_KERJA']."' 
						 						and trunc(VAL_DATE_TIME) = trunc(TO_DATE('".$bcc_export[$counter]['TANGGAL']."', 'DD/MM/YYYY'))";
						$result_me_cek = oci_parse($con, $query_cek_valid_me);
						oci_execute($result_me_cek, OCI_DEFAULT);
						$result_me_count = oci_fetch_all($result_me_cek, $result_record, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
						if($result_me_count>0){
							$status_validasi_kabun_or_em = "&#x2713;";
						}
						if($jml_valid>0)
						{
							$status_validasi_kabun_or_em = "&#x2713;";
						}
					// END UPDATE AFTER VALIDATION KABUN DEPLOYMENT DECEMBER 2020

					?>
					<tr>
						<td width="71" align="center"  ><?= $bcc_export[$counter]['AFD_PEMANEN'] ?></td>
						<td width="160" align="center"  ><?= $bcc_export[$counter]['NIK_KARYAWAN'] ?></td>
						<td width="160" align="center"  ><?= $bcc_export[$counter]['NAMA_KARYAWAN'] ?></td>
						<td width="50" align="center"  ><?= number_format($sementara_HA,2) ?></td>
						<td width="40" align="center"  ><?= $sementara_TBS ?></td>
						<td width="40" align="center"  ><?= $sementara_BRONDOLAN ?></td>
						<td width="35" align="center"  ><?= $sementara_BM ?></td>
						<td width="35" align="center"  ><?= $sementara_BK ?></td>
						<td width="35" align="center"  ><?= $sementara_TP ?></td>
						<td width="35" align="center"  ><?= $sementara_BB ?></td>
						<td width="35" align="center"  ><?= $sementara_JK ?></td>
						<td width="35" align="center"  ><?= $sementara_BA ?></td>
						<td width="35" align="center"  ><?= $sementara_BT ?></td>
						<td width="35" align="center"  ><?= $sementara_BL ?></td>
						<td width="35" align="center"  ><?= $sementara_PB ?></td>
						<td width="35" align="center"  ><?= $sementara_AB ?></td>
						<td width="35" align="center"  ><?= $sementara_SF ?></td>
						<td width="35" align="center"  ><?= $sementara_BS ?></td>
						<td width="70" align="center"  ><?= $symbol ?></td>
						<!--<td width="70" align="center"><?= $status_invalid_lokasi ?></td>-->
						<td width="70" align="center">
									<?php 
										$cek_status_lokasi = "
											SELECT
												thp.latitude, thp.longitude, tmt.latitude as m_latitude, tmt.longitude as m_longitude, thp.status_lokasi,
												(SELECT NILAI FROM T_PARAMETER WHERE KETERANGAN = 'RANGE') AS TOLERANSI_JARAK
											FROM
												t_header_rencana_panen thrp
											INNER JOIN t_detail_rencana_panen tdrp ON thrp.id_rencana = tdrp.id_rencana
											INNER JOIN t_hasil_panen thp ON tdrp.id_rencana = thp.id_rencana AND tdrp.no_rekap_bcc = thp.no_rekap_bcc
											INNER JOIN t_blok tb ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
											LEFT JOIN TAP_DW.TM_TPH@PRODDW_LINK TMT ON TB.ID_BA_AFD_BLOK = TMT.WERKS || TMT.AFD_CODE || TMT.BLOCK_CODE AND THP.NO_TPH = TMT.NO_TPH 
											WHERE
												nik_pemanen = '{$bcc_export[$counter]['NIK_KARYAWAN']}'
											AND   TO_CHAR(tanggal_rencana,'YYYY-MM-DD') = '{$date1}'
											AND   thp.status_tph = 'MANUAL'
											AND   thp.status_lokasi IS NULL
										";

										$result_status_lokasi = oci_parse($con, $cek_status_lokasi);
										oci_execute($result_status_lokasi);
										$res_status_lokasi = oci_fetch_all($result_status_lokasi, $res_lokasi, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);

										$total_salah = 0;
										foreach ($res_lokasi as $k => $v) {
											if (empty($v['M_LATITUDE']) && empty($v['M_LONGITUDE'])) {
												$jarak = 0.1;
											} else {
												$jarak = round(distance($v['M_LATITUDE'], $v['M_LONGITUDE'], $v['LATITUDE'], $v['LONGITUDE'], "K") * 1000);
											}

											if ($jarak == '0.1' || $jarak > $v['TOLERANSI_JARAK']) {
												$total_salah++;
											}
										}

										echo ($total_salah > 0) ? "" : "&#x2713;";
									?>
						</td>
					</tr>
					<?php
					}
					?>
				
				
		<?php } ?>
		
				<tr>
					<td width="" align="center"  colspan="3" style="background-color:#CCC">TOTAL</td>
					<td align="center"  style="background-color:#CCC"><?= number_format($total_HA,2) ?></td>
					<td align="center"  style="background-color:#CCC"><?= $total_TBS ?></td>
					<td align="center"  style="background-color:#CCC"><?= $total_BRONDOLAN ?></td>
					<td align="center"  style="background-color:#CCC"><?= $total_BM ?></td>
					<td align="center"  style="background-color:#CCC"><?= $total_BK ?></td>
					<td align="center"  style="background-color:#CCC"><?= $total_TP ?></td>
					<td align="center"  style="background-color:#CCC"><?= $total_BB ?></td>
					<td align="center"  style="background-color:#CCC"><?= $total_JK ?></td>
					<td align="center"  style="background-color:#CCC"><?= $total_BA ?></td>
					<td align="center"  style="background-color:#CCC"><?= $total_BT ?></td>
					<td align="center"  style="background-color:#CCC"><?= $total_BL ?></td>
					<td align="center"  style="background-color:#CCC"><?= $total_PB ?></td>
					<td align="center"  style="background-color:#CCC"><?= $total_AB ?></td>
					<td align="center"  style="background-color:#CCC"><?= $total_SF ?></td>
					<td align="center"  style="background-color:#CCC"><?= $total_BS ?></td>
					<td align="center"  style="background-color:#CCC"><?= $status_validasi_kabun_or_em ?></td>
					<td align="center"  style="background-color:#CCC"></td>
			    </tr>
				</tbody>
				</table>
			</div>
			</div>
		<div style="width:1100px">	
			<form action="doExport.php" name="formExport" method="post" style="text-align:right">
			<td height="50" colspan="9" align="right" valign="baseline">
				<?php
					$disable = '';
					$disable_karna = '';
					if ((int) $total_invalid_lokasi > 0) {
						$disable = 'disabled="disabled"';
						$disable_karna .= '- Lokasi BCC tidak sesuai dengan lokasi TPH<br>';
					} else {
						if ($jml_valid < 1 && $status_validasi_kabun_or_em=='') {
							$disable = 'disabled="disabled"';
							$disable_karna .= $jml_valid < 1?'- Belum dilakukan BCC Validation<br>':'';
						}						
						if ($valid_cetak > 0) {
							$disable = 'disabled="disabled"';
							$disable_karna .= $valid_cetak > 0?'- Terdapat BCC yang belum dilakukan cetak LHM Panen<br>':'';
						}						
					}
					
					$cek_stopper = 0;
					foreach($inactive as $k => $inac){
						if($inac > 0 && $cek_stopper==0){
							$disable = 'disabled="disabled"';
							$disable_karna .= $inac > 0?'- Terdapat TPH Inactive<br>':'';
							foreach($inactive_tph_list as $mBlock=>$itl){
								$disable_karna .= 'Block '.$mBlock.': ';
								foreach($itl as $k=>$mitl){
									$disable_karna .= $k<1? $mitl[3] : ', '.$mitl[3];
								}
								$disable_karna .= '<br/>';
							}
							$cek_stopper++;
						}
					}
					/*if (($jml_valid < 1 || $valid_cetak > 0) && $invalid_lokasi > 0) {
						//if ($invalid_lokasi > 0) {
						$disable = 'disabled="disabled"';
						//} else {
						//	$disable = '';
						//}
					//} else if (($jml_valid < 1 || $valid_cetak > 0) || $invalid_lokasi < 1) {
					//	$disable = 'disabled="disabled"';
					//} else if (($jml_valid < 1 || $valid_cetak > 0) || $invalid_lokasi < 1) {
						//if ($invalid_lokasi > 0) {
						//$disable = 'disabled="disabled"';
						//} else {
						//	$disable = '';
						//}
					}*/
				?>
				<input type="submit" name="btn_export" id="btn_export" value="EXPORT" style="width:120px; height: 30px" <?php echo $disable; ?> />
				<div style="width:1100px; text-align:right; color:red; font-style: italic;">
					<?php echo $disable_karna ?>
				</div>
			</td>
			</form>
		</div>
		<!-- <div style="width:1100px; text-align:left; color:red; font-style: italic;">
			* Tombol export tidak dapat digunakan jika :<br>
			- Terdapat BCC yang belum dilakukan cetak LHM Panen<br>
			- Belum dilakukan BCC Validation<br />
			- Lokasi BCC tidak sesuai dengan lokasi TPH<br />
			- Terdapat TPH Inactive
		</div> -->
		<?php }  ?>
	<table>
	<tr>
		<th align="center"><?php include("../include/Footer.php") ?></th>
	</tr>
</table>
