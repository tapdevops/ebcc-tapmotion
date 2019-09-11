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
<table width="978" height="390" border="0" align="center">
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
							$sql_MD = "
							 SELECT thrp.nik_mandor, f_get_empname(thrp.nik_mandor) nama_mandor
								FROM T_HEADER_RENCANA_PANEN THRP
         INNER JOIN T_EMPLOYEE TE
            ON THRP.NIK_PEMANEN = TE.NIK
         INNER JOIN T_AFDELING TA
            ON TE.ID_BA_AFD = TA.ID_BA_AFD
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
   WHERE     tc.id_cc = '$ID_CC'
         and tba.id_ba = '$ID_BA'
		 and ta2.id_afd = nvl(decode('$sesAfdeling', 'ALL', null, '$sesAfdeling'), ta2.id_afd)
		 and thrp.nik_mandor = nvl(decode('".$_POST["NIKMandor"]."', 'ALL', null, '".$_POST["NIKMandor"]."'), thrp.nik_mandor)
         and TO_CHAR (thrp.tanggal_rencana, 'YYYY-MM-DD') BETWEEN '$date1' and nvl ('', '$date1')
		 and thp.cetak_bcc is not null and thp.cetak_date is not null
																								  group by thrp.nik_mandor
														";
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
														while (oci_fetch($result_MD)) {	
															$NIK_Mandor[] 		= oci_result($result_MD, "NIK_MANDOR");
															$Emp_NameMandor[] 	= oci_result($result_MD, "NAMA_MANDOR");
														}
														$jumlahMD = oci_num_rows($result_MD);
														
														//echo "mandor".$sql_MD. $jumlahMD;
														
														//echo "here".$sql_MD .$jumlahMD;
														if($jumlahMD >0 ){
															//$jumlahRecord = $_SESSION['jumlahMD'];
															$selectoMD = "<select name=\"NIKMandor\" id=\"NIKMandor\" style=\"visibility:visible; font-size: 15px;  height: 25px\">";
															$optiondefMD = "<option value=\"ALL\"> ALL </option>";
															echo $selectoMD.$optiondefMD;
															
															for($xMD = 0; $xMD < $jumlahMD; $xMD++){
																if($NIK_Mandor[$xMD]==$_POST['NIKMandor']){
																	$_SESSION['Emp_NameMandor'] = $Emp_NameMandor[$xMD];
																	echo "<option selected value=\"$NIK_Mandor[$xMD]\">$NIK_Mandor[$xMD] - $Emp_NameMandor[$xMD]</option>"; 
																} else {
																	echo "<option value=\"$NIK_Mandor[$xMD]\">$NIK_Mandor[$xMD] - $Emp_NameMandor[$xMD]</option>"; 
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
	<?php if(isset($_POST["Afdeling"]) && isset($_POST["date1"]) && isset($_POST["button"])){
		/* $sql_Download_Crop_Harv = "	
		     SELECT TBA.ID_CC as ID_CC,
			 THRP.id_rencana,
			 TBA.ID_ESTATE as ID_ESTATE,
			 f_get_idafd_nik(thrp.nik_pemanen) AFD_PEMANEN,
			 THRP.NIK_PEMANEN,
			 f_get_empname (thrp.nik_pemanen) NAMA_PEMANEN,
			 tdrp.luasan_panen,
         TO_CHAR (THRP.TANGGAL_RENCANA, 'DD.MM.YYYY') TANGGAL,
         THP.NO_BCC,
         THP.NO_TPH,
		 THP.NO_REKAP_BCC,
		 THP.VALIDASI_BCC,
		 THP.VALIDASI_DATE,
         CASE
            WHEN TA.ID_BA <> TA2.ID_BA THEN 'CINT_' || TA2.ID_BA
            ELSE NULL
         END
            CUST,
         CASE WHEN TA.ID_BA <> TA2.ID_BA THEN TA2.ID_BA ELSE NULL END PLANT,
         TB.ID_BLOK,
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
            AS TBS,
		 NVL( F_GET_HASIL_PANEN_BRDX  ( thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc),0)  as BRD,
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
            WHEN F_GET_IDBA_NIK (THRP.NIK_PEMANEN) != TA2.ID_BA THEN NULL
            ELSE THRP.NIK_MANDOR
         END
            NIK_MANDOR,
         '' as NIK_KERANI_BUAH,
         CASE WHEN TDG.NIK_GANDENG != '-' THEN 'X' ELSE NULL END GANDENG,
         CASE WHEN TDG.NIK_GANDENG = '-' THEN NULL ELSE TDG.NIK_GANDENG END
            NIK_GANDENG,
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
            AS BS
    FROM T_HEADER_RENCANA_PANEN THRP
         INNER JOIN T_EMPLOYEE TE
            ON THRP.NIK_PEMANEN = TE.NIK
         INNER JOIN T_AFDELING TA
            ON TE.ID_BA_AFD = TA.ID_BA_AFD
         INNER JOIN T_BUSSINESSAREA TBA
            ON TA.ID_BA = TBA.ID_BA
         INNER JOIN T_DETAIL_RENCANA_PANEN TDRP
            ON THRP.ID_RENCANA = TDRP.ID_RENCANA
         INNER JOIN T_HASIL_PANEN THP
            ON TDRP.ID_RENCANA = THP.ID_RENCANA
               AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC
         INNER JOIN T_BLOK TB
            ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
         INNER JOIN T_AFDELING TA2
            ON TB.ID_BA_AFD = TA2.ID_BA_AFD
         LEFT JOIN T_DETAIL_GANDENG TDG
            ON THRP.ID_RENCANA = TDG.ID_RENCANA
   WHERE     TBA.ID_CC = '$ID_CC' AND
         F_GET_IDBA_NIK (THRP.NIK_PEMANEN) = '$ID_BA'
         AND TA.ID_AFD = NVL (DECODE ('$sesAfdeling', 'ALL', NULL, '$sesAfdeling'), TA.ID_AFD)
		 AND thrp.nik_mandor =
               NVL (DECODE ('$nik_mandor', 'ALL', NULL, '$nik_mandor'), thrp.nik_mandor)
         AND TO_CHAR (THRP.TANGGAL_RENCANA, 'yyyy-mm-dd') BETWEEN '$date1'
                                                              AND  NVL (
                                                                      '$date2',
                                                                      '$date1')
ORDER BY 
		NAMA_PEMANEN,
		THRP.NIK_PEMANEN
		"; */
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
		//echo $sql_Download_Denda_Panen; exit;
		$_SESSION['sql_Download_Denda_Panen'] = $sql_Download_Denda_Panen;
		
		$sql_Download_Crop_Harv = "
  SELECT TBA.ID_CC AS ID_CC,
         THP.NO_BCC,
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
            AS BS
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
   WHERE     tc.id_cc = '$ID_CC'
         and tba.id_ba = '$ID_BA'
		 and ta2.id_afd = nvl(decode('$sesAfdeling', 'ALL', null, '$sesAfdeling'), ta2.id_afd)
		 and thrp.nik_mandor = nvl(decode('".$_POST["NIKMandor"]."', 'ALL', null, '".$_POST["NIKMandor"]."'), thrp.nik_mandor)
         and TO_CHAR (thrp.tanggal_rencana, 'YYYY-MM-DD') BETWEEN '$date1' and nvl ('', '$date1')
		 and thp.cetak_bcc is not null and thp.cetak_date is not null
order by TANGGAL,
         thrp.nik_mandor,
		 ta2.id_afd,
         nama_pemanen,
		 nik_pemanen,
		 TB.id_blok,
		 THP.NO_BCC,
         BM,
         BK,
         TP,
         BB,
         JK,
         BA,
         BT,
         BL,
         PB,
         AB,
         SF,
         BS
		";
		
		//echo $sql_Download_Crop_Harv; exit;
		$result = oci_parse($con, $sql_Download_Crop_Harv);
		oci_execute($result, OCI_DEFAULT);
		
		while (oci_fetch($result)) {	
			$AFD_PEMANEN[] 	= oci_result($result, "AFD_PEMANEN");
			$NIK_KARYAWAN[] 	= oci_result($result, "NIK_PEMANEN");
			$NAMA_KARYAWAN[] 	= oci_result($result, "NAMA_PEMANEN");
			$ID_RENCANA[] 		= oci_result($result, "ID_RENCANA");
			$LUASAN_PANEN[] 	= oci_result($result, "LUASAN_PANEN");
			$TANGGAL[] 			= oci_result($result, "TANGGAL");
			$NO_BCC[] 			= oci_result($result, "NO_BCC");
			$NO_TPH[] 			= oci_result($result, "NO_TPH");
			$NO_REKAP_BCC[] 	= oci_result($result, "NO_REKAP_BCC");
			$KODE_DELIVERY_TICKET[] 	= oci_result($result, "KODE_DELIVERY_TICKET");
			$CUST[] 			= oci_result($result, "CUST");
			$BA_KERJA[] 		= oci_result($result, "BA_KERJA");
			$AFD_KERJA[] 		= oci_result($result, "AFD_KERJA");
			$BLOK[] 			= oci_result($result, "ID_BLOK");
			$TBS[] 				= oci_result($result, "TBS");
			$BRONDOLAN[] 		= oci_result($result, "BRD");
			$DIKIRIM[] 			= oci_result($result, "DIKIRIM");
			$NIK_MANDOR[] 		= oci_result($result, "NIK_MANDOR");
			$NIK_KRANI_BUAH[] 	= oci_result($result, "NIK_KERANI_BUAH");
			$GANDENG[] 			= oci_result($result, "GANDENG");
			$NIK_GANDENG[] 		= oci_result($result, "NIK_GANDENG");
			$BM[] 				= oci_result($result, "BM");
			$BK[] 				= oci_result($result, "BK");
			$TP[] 				= oci_result($result, "TP");
			$BB[] 				= oci_result($result, "BB");
			$JK[] 				= oci_result($result, "JK");
			$BA[] 				= oci_result($result, "BA");
			$BT[] 				= oci_result($result, "BT");
			$BL[] 				= oci_result($result, "BL");
			$PB[] 				= oci_result($result, "PB");
			$AB[] 				= oci_result($result, "AB");
			$SF[] 				= oci_result($result, "SF");
			$BS[] 				= oci_result($result, "BS");
			$VALIDASI_BCC[] 	= oci_result($result, "VALIDASI_BCC");
			$VALIDASI_DATE[] 	= oci_result($result, "VALIDASI_DATE");
		}
		$_SESSION['sql_export_crop_harv'] = $sql_Download_Crop_Harv;
		$roweffec = oci_num_rows($result);
		//echo $roweffec; exit;
	?>
	<tr>
		<td colspan="4" valign="top">
			<table width="1134" border="0">
				<tbody id="scrolling2" style="width:1134">
				
				<tr bgcolor="#9CC346">
					<td width="" rowspan="2" align="center" style="font-size:14px" id="bordertable">AFD KARYAWAN</td>
					<td width="277" rowspan="2" align="center" style="font-size:14px" id="bordertable">NIK</td>
					<td width="277" rowspan="2" align="center" style="font-size:14px" id="bordertable">Nama Karyawan</td>
					<td width="60" rowspan="2" align="center" style="font-size:14px" id="bordertable">HA</td>
					<td width="120" colspan="2" align="center" style="font-size:14px" id="bordertable">Hasil Panen</td>
					<td width="600" colspan="12" align="center" style="font-size:14px" id="bordertable">Pinalty</td>
					<td width="77" rowspan="2" align="center" style="font-size:14px" id="bordertable">eBCC Validation</td>
				</tr>
		  
			    <tr bgcolor="#9CC346">
					<td width="60" align="center" style="font-size:14px" id="bordertable">TBS</td>
					<td width="60" align="center" style="font-size:14px" id="bordertable">BRD</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">BM</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">BK</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">TP</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">BB</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">JK</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">BA</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">BT</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">BL</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">PB</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">AB</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">SF</td>
					<td width="50" align="center" style="font-size:14px" id="bordertable">BS</td>
			    </tr>
				
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
				$jml_record = 0;
				
				for ($counter = 0; $counter < $roweffec; $counter++){
					
					if($NO_REKAP_BCC[$counter] !== $NO_REKAP_BCC[$counter-1])
					{
						$total_HA += $LUASAN_PANEN[$counter];
					}
					else if($ID_RENCANA[$counter] !== $ID_RENCANA[$counter-1])
					{
						$total_HA += $LUASAN_PANEN[$counter];
					}
					else
					{
						$total_HA += 0;
					}
					
					
					$total_TBS += $TBS[$counter];
					$total_BRONDOLAN += $BRONDOLAN[$counter];
					$total_BM += $BM[$counter];
					$total_BK += $BK[$counter];
					$total_TP += $TP[$counter];
					$total_BB += $BB[$counter];
					$total_JK += $JK[$counter];
					$total_BA += $BA[$counter];
					$total_BT += $BT[$counter];
					$total_BL += $BL[$counter];
					$total_PB += $PB[$counter];
					$total_AB += $AB[$counter];
					$total_SF += $SF[$counter];
					$total_BS += $BS[$counter];
					
					if($counter==0){
						$sementara_HA += $LUASAN_PANEN[$counter];
						$sementara_TBS += $TBS[$counter];
						$sementara_BRONDOLAN += $BRONDOLAN[$counter];
						$sementara_BM += $BM[$counter];
						$sementara_BK += $BK[$counter];
						$sementara_TP += $TP[$counter];
						$sementara_BB += $BB[$counter];
						$sementara_JK += $JK[$counter];
						$sementara_BA += $BA[$counter];
						$sementara_BT += $BT[$counter];
						$sementara_BL += $BL[$counter];
						$sementara_PB += $PB[$counter];
						$sementara_AB += $AB[$counter];
						$sementara_SF += $SF[$counter];
						$sementara_BS += $BS[$counter];	
						
						if($VALIDASI_BCC[$counter]!="" and $VALIDASI_DATE[$counter]!=""){
							$lolos_validasi++;
						}
						
						$query_cek_valid_me = "
						select * from MOBILE_ESTATE.TR_EBCC where 
						WERKS = '".$BA_KERJA[$counter]."' and AFD_CODE = '".$AFD_KERJA[$counter]."' 
						and BLOCK_CODE = '".$BLOK[$counter]."' and TPH_CODE = '".$NO_TPH[$counter]."' 
						and DELIVERY_TICKET = '".$KODE_DELIVERY_TICKET[$counter]."' and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$TANGGAL[$counter]."','DD.MM.YYYY')
						and DATA_FROM = 'EBCC_VALIDATION'";
						//echo $query_cek_valid_me."<br>";
						$result_me_cek = oci_parse($con, $query_cek_valid_me);
						oci_execute($result_me_cek, OCI_DEFAULT);
						$result_me_count = oci_fetch_all($result_me_cek, $result_record, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
						if($result_me_count>0){
							$valid_me = 1;
						}
						
					} else {
					
						if($NIK_KARYAWAN[$counter]!=$NIK_KARYAWAN[$counter-1]){
							$jml_record++;
							if($valid_me==1){
								$symbol = "&#x2713;";
								$jml_valid++;
							} else {
								if($lolos_validasi>0){
									$symbol = "&#x2713;";
									$jml_valid++;
								} else {
									$symbol = "";
								}
							}	
							
							?>
							<tr style="font-size:12px; height:2px; visibility:hidden">
								<td align="center"  style="visibility:hidden"><?= $AFD_PEMANEN[$counter-1] ?></td>
								<td align="center"  style="visibility:hidden"><?= $NIK_KARYAWAN[$counter-1] ?></td>
								<td align="center"  style="visibility:hidden"><?= $NAMA_KARYAWAN[$counter-1] ?></td>
								<td align="center"  style="visibility:hidden"><?= number_format($sementara_HA,2) ?></td>
								<td align="center"  style="visibility:hidden"><?= $sementara_TBS ?></td>
								<td align="center"  style="visibility:hidden"><?= $sementara_BRONDOLAN ?></td>
								<td align="center"  style="visibility:hidden"><?= $sementara_BM ?></td>
								<td align="center"  style="visibility:hidden"><?= $sementara_BK ?></td>
								<td align="center"  style="visibility:hidden"><?= $sementara_TP ?></td>
								<td align="center"  style="visibility:hidden"><?= $sementara_BB ?></td>
								<td align="center"  style="visibility:hidden"><?= $sementara_JK ?></td>
								<td align="center"  style="visibility:hidden"><?= $sementara_BA ?></td>
								<td align="center"  style="visibility:hidden"><?= $sementara_BT ?></td>
								<td align="center"  style="visibility:hidden"><?= $sementara_BL ?></td>
								<td align="center"  style="visibility:hidden"><?= $sementara_PB ?></td>
								<td align="center"  style="visibility:hidden"><?= $sementara_AB ?></td>
								<td align="center"  style="visibility:hidden"><?= $sementara_SF ?></td>
								<td align="center"  style="visibility:hidden"><?= $sementara_BS ?></td>
								<td align="center"  style="visibility:hidden"><?= $symbol ?></td>
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
							
							$sementara_HA += $LUASAN_PANEN[$counter];
							$sementara_TBS += $TBS[$counter];
							$sementara_BRONDOLAN += $BRONDOLAN[$counter];
							$sementara_BM += $BM[$counter];
							$sementara_BK += $BK[$counter];
							$sementara_TP += $TP[$counter];
							$sementara_BB += $BB[$counter];
							$sementara_JK += $JK[$counter];
							$sementara_BA += $BA[$counter];
							$sementara_BT += $BT[$counter];
							$sementara_BL += $BL[$counter];
							$sementara_PB += $PB[$counter];
							$sementara_AB += $AB[$counter];
							$sementara_SF += $SF[$counter];
							$sementara_BS += $BS[$counter];	
							
							if($VALIDASI_BCC[$counter]!="" and $VALIDASI_DATE[$counter]!=""){
								$lolos_validasi++;
							}
							
							if($valid_me==0){
								$query_cek_valid_me = "
								select * from MOBILE_ESTATE.TR_EBCC where 
								WERKS = '".$BA_KERJA[$counter]."' and AFD_CODE = '".$AFD_KERJA[$counter]."' 
								and BLOCK_CODE = '".$BLOK[$counter]."' and TPH_CODE = '".$NO_TPH[$counter]."' 
								and DELIVERY_TICKET = '".$KODE_DELIVERY_TICKET[$counter]."' and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$TANGGAL[$counter]."','DD.MM.YYYY')
								and DATA_FROM = 'EBCC_VALIDATION'";
								//echo $query_cek_valid_me."<br>";
								$result_me_cek = oci_parse($con, $query_cek_valid_me);
								oci_execute($result_me_cek, OCI_DEFAULT);
								$result_me_count = oci_fetch_all($result_me_cek, $result_record, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
								if($result_me_count>0){
									$valid_me = 1;
								}
							}
							
						} else { 
							if($NO_REKAP_BCC[$counter] !== $NO_REKAP_BCC[$counter-1])
							{
								$sementara_HA += $LUASAN_PANEN[$counter];
							}
							else if($ID_RENCANA[$counter] !== $ID_RENCANA[$counter-1])
							{
								$sementara_HA += $LUASAN_PANEN[$counter];
							}
							else
							{
								$sementara_HA += 0;
							}
							
							$sementara_TBS += $TBS[$counter];
							$sementara_BRONDOLAN += $BRONDOLAN[$counter];
							$sementara_BM += $BM[$counter];
							$sementara_BK += $BK[$counter];
							$sementara_TP += $TP[$counter];
							$sementara_BB += $BB[$counter];
							$sementara_JK += $JK[$counter];
							$sementara_BA += $BA[$counter];
							$sementara_BT += $BT[$counter];
							$sementara_BL += $BL[$counter];
							$sementara_PB += $PB[$counter];
							$sementara_AB += $AB[$counter];
							$sementara_SF += $SF[$counter];
							$sementara_BS += $BS[$counter];	
							
							if($VALIDASI_BCC[$counter]!="" and $VALIDASI_DATE[$counter]!=""){
								$lolos_validasi++;
							}
							
							if($valid_me==0){
								$query_cek_valid_me = "
								select * from MOBILE_ESTATE.TR_EBCC where 
								WERKS = '".$BA_KERJA[$counter]."' and AFD_CODE = '".$AFD_KERJA[$counter]."' 
								and BLOCK_CODE = '".$BLOK[$counter]."' and TPH_CODE = '".$NO_TPH[$counter]."' 
								and DELIVERY_TICKET = '".$KODE_DELIVERY_TICKET[$counter]."' and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$TANGGAL[$counter]."','DD.MM.YYYY')
								and DATA_FROM = 'EBCC_VALIDATION'";
								//echo $query_cek_valid_me."<br>";
								$result_me_cek = oci_parse($con, $query_cek_valid_me);
								oci_execute($result_me_cek, OCI_DEFAULT);
								$result_me_count = oci_fetch_all($result_me_cek, $result_record, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
								if($result_me_count>0){
									$valid_me = 1;
								}
								
							}
						}	
					}
					
					if($counter==$roweffec-1){
						$jml_record++;
						if($VALIDASI_BCC[$counter]!="" and $VALIDASI_DATE[$counter]!=""){
							$lolos_validasi++;
						}
						
						if($valid_me==0){
							$query_cek_valid_me = "
							select * from MOBILE_ESTATE.TR_EBCC where 
							WERKS = '".$BA_KERJA[$counter]."' and AFD_CODE = '".$AFD_KERJA[$counter]."' 
							and BLOCK_CODE = '".$BLOK[$counter]."' and TPH_CODE = '".$NO_TPH[$counter]."' 
							and DELIVERY_TICKET = '".$KODE_DELIVERY_TICKET[$counter]."' and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$TANGGAL[$counter]."','DD.MM.YYYY')
							and DATA_FROM = 'EBCC_VALIDATION'";
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
							$jml_valid++;
						} else {
							if($lolos_validasi>0){
								$symbol = "&#x2713;";
								$jml_valid++;
							} else {
								$symbol = "";
							}
						}
					?>
					<tr style="font-size:12px; height:2px; visibility:hidden">
						<td align="center"  style="visibility:hidden"><?= $AFD_PEMANEN[$counter] ?></td>
						<td align="center"  style="visibility:hidden"><?= $NIK_KARYAWAN[$counter] ?></td>
						<td align="center"  style="visibility:hidden"><?= $NAMA_KARYAWAN[$counter] ?></td>
						<td align="center"  style="visibility:hidden"><?= number_format($sementara_HA,2) ?></td>
						<td align="center"  style="visibility:hidden"><?= $sementara_TBS ?></td>
						<td align="center"  style="visibility:hidden"><?= $sementara_BRONDOLAN ?></td>
						<td align="center"  style="visibility:hidden"><?= $sementara_BM ?></td>
						<td align="center"  style="visibility:hidden"><?= $sementara_BK ?></td>
						<td align="center"  style="visibility:hidden"><?= $sementara_TP ?></td>
						<td align="center"  style="visibility:hidden"><?= $sementara_BB ?></td>
						<td align="center"  style="visibility:hidden"><?= $sementara_JK ?></td>
						<td align="center"  style="visibility:hidden"><?= $sementara_BA ?></td>
						<td align="center"  style="visibility:hidden"><?= $sementara_BT ?></td>
						<td align="center"  style="visibility:hidden"><?= $sementara_BL ?></td>
						<td align="center"  style="visibility:hidden"><?= $sementara_PB ?></td>
						<td align="center"  style="visibility:hidden"><?= $sementara_AB ?></td>
						<td align="center"  style="visibility:hidden"><?= $sementara_SF ?></td>
						<td align="center"  style="visibility:hidden"><?= $sementara_BS ?></td>
						<td align="center"  style="visibility:hidden"><?= $symbol ?></td>
					</tr>
					<?php
					}
					?>
					
				
		<?php } ?>
		</tbody>
	</table>
		<!-- begin -->
		<table width="1134" border="0">
			<tbody id="scrolling" style="width:1134">
				
				<!-- <tr style="display:none">
					<td width="44" rowspan="2" align="center" style="font-size:14px" >AFD KARYAWAN</td>
					<td width="250" rowspan="2" align="center" style="font-size:14px" >NIK</td>
					<td width="250" rowspan="2" align="center" style="font-size:14px" >Nama Karyawan</td>
					<td width="50" rowspan="2" align="center" style="font-size:14px" >HA</td>
					<td width="100" colspan="2" align="center" style="font-size:14px" >Hasil Panen</td>
					<td width="500" colspan="12" align="center" style="font-size:14px" >Pinalty</td>
					<td width="100" rowspan="2" align="center" style="font-size:14px" >eBCC Validation</td>
				</tr>
		  
			    <tr style="display:none">
					<td width="50" align="center" style="font-size:14px" >TBS</td>
					<td width="50" align="center" style="font-size:14px" >BRD</td>
					<td width="50" align="center" style="font-size:14px" >BM</td>
					<td width="50" align="center" style="font-size:14px" >BK</td>
					<td width="50" align="center" style="font-size:14px" >TP</td>
					<td width="50" align="center" style="font-size:14px" >BB</td>
					<td width="50" align="center" style="font-size:14px" >JK</td>
					<td width="50" align="center" style="font-size:14px" >BA</td>
					<td width="50" align="center" style="font-size:14px" >BT</td>
					<td width="50" align="center" style="font-size:14px" >BL</td>
					<td width="50" align="center" style="font-size:14px" >PB</td>
					<td width="50" align="center" style="font-size:14px" >AB</td>
					<td width="50" align="center" style="font-size:14px" >SF</td>
					<td width="50" align="center" style="font-size:14px" >BS</td>
			    </tr> -->
				
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
				$jml_record = 0;
				
				for ($counter = 0; $counter < $roweffec; $counter++){
					
					if($NO_REKAP_BCC[$counter] !== $NO_REKAP_BCC[$counter-1])
					{
						$total_HA += $LUASAN_PANEN[$counter];
					}
					else if($ID_RENCANA[$counter] !== $ID_RENCANA[$counter-1])
					{
						$total_HA += $LUASAN_PANEN[$counter];
					}
					else
					{
						$total_HA += 0;
					}
					
					
					$total_TBS += $TBS[$counter];
					$total_BRONDOLAN += $BRONDOLAN[$counter];
					$total_BM += $BM[$counter];
					$total_BK += $BK[$counter];
					$total_TP += $TP[$counter];
					$total_BB += $BB[$counter];
					$total_JK += $JK[$counter];
					$total_BA += $BA[$counter];
					$total_BT += $BT[$counter];
					$total_BL += $BL[$counter];
					$total_PB += $PB[$counter];
					$total_AB += $AB[$counter];
					$total_SF += $SF[$counter];
					$total_BS += $BS[$counter];
					
					if($counter==0){
						$sementara_HA += $LUASAN_PANEN[$counter];
						$sementara_TBS += $TBS[$counter];
						$sementara_BRONDOLAN += $BRONDOLAN[$counter];
						$sementara_BM += $BM[$counter];
						$sementara_BK += $BK[$counter];
						$sementara_TP += $TP[$counter];
						$sementara_BB += $BB[$counter];
						$sementara_JK += $JK[$counter];
						$sementara_BA += $BA[$counter];
						$sementara_BT += $BT[$counter];
						$sementara_BL += $BL[$counter];
						$sementara_PB += $PB[$counter];
						$sementara_AB += $AB[$counter];
						$sementara_SF += $SF[$counter];
						$sementara_BS += $BS[$counter];	
						
						if($VALIDASI_BCC[$counter]!="" and $VALIDASI_DATE[$counter]!=""){
							$lolos_validasi++;
						}
						
						$query_cek_valid_me = "
						select * from MOBILE_ESTATE.TR_EBCC where 
						WERKS = '".$BA_KERJA[$counter]."' and AFD_CODE = '".$AFD_KERJA[$counter]."' 
						and BLOCK_CODE = '".$BLOK[$counter]."' and TPH_CODE = '".$NO_TPH[$counter]."' 
						and DELIVERY_TICKET = '".$KODE_DELIVERY_TICKET[$counter]."' and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$TANGGAL[$counter]."','DD.MM.YYYY')
						and DATA_FROM = 'EBCC_VALIDATION'";
						//echo $query_cek_valid_me."<br>";
						$result_me_cek = oci_parse($con, $query_cek_valid_me);
						oci_execute($result_me_cek, OCI_DEFAULT);
						$result_me_count = oci_fetch_all($result_me_cek, $result_record, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
						if($result_me_count>0){
							$valid_me = 1;
						}
						
					} else {
					
						if($NIK_KARYAWAN[$counter]!=$NIK_KARYAWAN[$counter-1]){
							$jml_record++;
							if($valid_me==1){
								$symbol = "&#x2713;";
								$jml_valid++;
							} else {
								if($lolos_validasi>0){
									$symbol = "&#x2713;";
									$jml_valid++;
								} else {
									$symbol = "";
								}
							}	
							
							?>
							<tr>
								<td width="112" align="center" id="bordertable" ><?= $AFD_PEMANEN[$counter-1] ?></td>
								<td width="250" align="center" id="bordertable" ><?= $NIK_KARYAWAN[$counter-1] ?></td>
								<td width="250" align="center" id="bordertable" ><?= $NAMA_KARYAWAN[$counter-1] ?></td>
								<td width="" align="center" id="bordertable" ><?= number_format($sementara_HA,2) ?></td>
								<td width="50" align="center" id="bordertable" ><?= $sementara_TBS ?></td>
								<td width="50" align="center" id="bordertable" ><?= $sementara_BRONDOLAN ?></td>
								<td width="50" align="center" id="bordertable" ><?= $sementara_BM ?></td>
								<td width="50" align="center" id="bordertable" ><?= $sementara_BK ?></td>
								<td width="50" align="center" id="bordertable" ><?= $sementara_TP ?></td>
								<td width="50" align="center" id="bordertable" ><?= $sementara_BB ?></td>
								<td width="50" align="center" id="bordertable" ><?= $sementara_JK ?></td>
								<td width="50" align="center" id="bordertable" ><?= $sementara_BA ?></td>
								<td width="50" align="center" id="bordertable" ><?= $sementara_BT ?></td>
								<td width="50" align="center" id="bordertable" ><?= $sementara_BL ?></td>
								<td width="50" align="center" id="bordertable" ><?= $sementara_PB ?></td>
								<td width="50" align="center" id="bordertable" ><?= $sementara_AB ?></td>
								<td width="50" align="center" id="bordertable" ><?= $sementara_SF ?></td>
								<td width="50" align="center" id="bordertable" ><?= $sementara_BS ?></td>
								<td width="100" align="center" id="bordertable" ><?= $symbol ?></td>
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
							
							$sementara_HA += $LUASAN_PANEN[$counter];
							$sementara_TBS += $TBS[$counter];
							$sementara_BRONDOLAN += $BRONDOLAN[$counter];
							$sementara_BM += $BM[$counter];
							$sementara_BK += $BK[$counter];
							$sementara_TP += $TP[$counter];
							$sementara_BB += $BB[$counter];
							$sementara_JK += $JK[$counter];
							$sementara_BA += $BA[$counter];
							$sementara_BT += $BT[$counter];
							$sementara_BL += $BL[$counter];
							$sementara_PB += $PB[$counter];
							$sementara_AB += $AB[$counter];
							$sementara_SF += $SF[$counter];
							$sementara_BS += $BS[$counter];	
							
							if($VALIDASI_BCC[$counter]!="" and $VALIDASI_DATE[$counter]!=""){
								$lolos_validasi++;
							}
							
							if($valid_me==0){
								$query_cek_valid_me = "
								select * from MOBILE_ESTATE.TR_EBCC where 
								WERKS = '".$BA_KERJA[$counter]."' and AFD_CODE = '".$AFD_KERJA[$counter]."' 
								and BLOCK_CODE = '".$BLOK[$counter]."' and TPH_CODE = '".$NO_TPH[$counter]."' 
								and DELIVERY_TICKET = '".$KODE_DELIVERY_TICKET[$counter]."' and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$TANGGAL[$counter]."','DD.MM.YYYY')
								and DATA_FROM = 'EBCC_VALIDATION'";
								//echo $query_cek_valid_me."<br>";
								$result_me_cek = oci_parse($con, $query_cek_valid_me);
								oci_execute($result_me_cek, OCI_DEFAULT);
								$result_me_count = oci_fetch_all($result_me_cek, $result_record, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
								if($result_me_count>0){
									$valid_me = 1;
								}
							}
							
						} else { 
							if($NO_REKAP_BCC[$counter] !== $NO_REKAP_BCC[$counter-1])
							{
								$sementara_HA += $LUASAN_PANEN[$counter];
							}
							else if($ID_RENCANA[$counter] !== $ID_RENCANA[$counter-1])
							{
								$sementara_HA += $LUASAN_PANEN[$counter];
							}
							else
							{
								$sementara_HA += 0;
							}
							
							$sementara_TBS += $TBS[$counter];
							$sementara_BRONDOLAN += $BRONDOLAN[$counter];
							$sementara_BM += $BM[$counter];
							$sementara_BK += $BK[$counter];
							$sementara_TP += $TP[$counter];
							$sementara_BB += $BB[$counter];
							$sementara_JK += $JK[$counter];
							$sementara_BA += $BA[$counter];
							$sementara_BT += $BT[$counter];
							$sementara_BL += $BL[$counter];
							$sementara_PB += $PB[$counter];
							$sementara_AB += $AB[$counter];
							$sementara_SF += $SF[$counter];
							$sementara_BS += $BS[$counter];	
							
							if($VALIDASI_BCC[$counter]!="" and $VALIDASI_DATE[$counter]!=""){
								$lolos_validasi++;
							}
							
							if($valid_me==0){
								$query_cek_valid_me = "
								select * from MOBILE_ESTATE.TR_EBCC where 
								WERKS = '".$BA_KERJA[$counter]."' and AFD_CODE = '".$AFD_KERJA[$counter]."' 
								and BLOCK_CODE = '".$BLOK[$counter]."' and TPH_CODE = '".$NO_TPH[$counter]."' 
								and DELIVERY_TICKET = '".$KODE_DELIVERY_TICKET[$counter]."' and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$TANGGAL[$counter]."','DD.MM.YYYY')
								and DATA_FROM = 'EBCC_VALIDATION'";
								//echo $query_cek_valid_me."<br>";
								$result_me_cek = oci_parse($con, $query_cek_valid_me);
								oci_execute($result_me_cek, OCI_DEFAULT);
								$result_me_count = oci_fetch_all($result_me_cek, $result_record, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
								if($result_me_count>0){
									$valid_me = 1;
								}
								
							}
						}	
					}
					
					if($counter==$roweffec-1){
						$jml_record++;
						if($VALIDASI_BCC[$counter]!="" and $VALIDASI_DATE[$counter]!=""){
							$lolos_validasi++;
						}
						
						if($valid_me==0){
							$query_cek_valid_me = "
							select * from MOBILE_ESTATE.TR_EBCC where 
							WERKS = '".$BA_KERJA[$counter]."' and AFD_CODE = '".$AFD_KERJA[$counter]."' 
							and BLOCK_CODE = '".$BLOK[$counter]."' and TPH_CODE = '".$NO_TPH[$counter]."' 
							and DELIVERY_TICKET = '".$KODE_DELIVERY_TICKET[$counter]."' and TO_DATE(TO_CHAR(DATE_TIME, 'DD/MM/YYYY'), 'DD/MM/YYYY') = TO_DATE('".$TANGGAL[$counter]."','DD.MM.YYYY')
							and DATA_FROM = 'EBCC_VALIDATION'";
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
							$jml_valid++;
						} else {
							if($lolos_validasi>0){
								$symbol = "&#x2713;";
								$jml_valid++;
							} else {
								$symbol = "";
							}
						}
					?>
					<tr>
						<td width="44" align="center" id="bordertable" ><?= $AFD_PEMANEN[$counter] ?></td>
						<td width="250" align="center" id="bordertable" ><?= $NIK_KARYAWAN[$counter] ?></td>
						<td width="250" align="center" id="bordertable" ><?= $NAMA_KARYAWAN[$counter] ?></td>
						<td width="50" align="center" id="bordertable" ><?= number_format($sementara_HA,2) ?></td>
						<td width="50" align="center" id="bordertable" ><?= $sementara_TBS ?></td>
						<td width="50" align="center" id="bordertable" ><?= $sementara_BRONDOLAN ?></td>
						<td width="50" align="center" id="bordertable" ><?= $sementara_BM ?></td>
						<td width="50" align="center" id="bordertable" ><?= $sementara_BK ?></td>
						<td width="50" align="center" id="bordertable" ><?= $sementara_TP ?></td>
						<td width="50" align="center" id="bordertable" ><?= $sementara_BB ?></td>
						<td width="50" align="center" id="bordertable" ><?= $sementara_JK ?></td>
						<td width="50" align="center" id="bordertable" ><?= $sementara_BA ?></td>
						<td width="50" align="center" id="bordertable" ><?= $sementara_BT ?></td>
						<td width="50" align="center" id="bordertable" ><?= $sementara_BL ?></td>
						<td width="50" align="center" id="bordertable" ><?= $sementara_PB ?></td>
						<td width="50" align="center" id="bordertable" ><?= $sementara_AB ?></td>
						<td width="50" align="center" id="bordertable" ><?= $sementara_SF ?></td>
						<td width="50" align="center" id="bordertable" ><?= $sementara_BS ?></td>
						<td width="100" align="center" id="bordertable" ><?= $symbol ?></td>
					</tr>
					<?php
					}
					?>
					
				
		<?php } ?>
		
		
				<tr>
					<td width="450" align="center" id="bordertable" colspan="3" style="background-color:#CCC">TOTAL</td>
					<td align="center" id="bordertable" style="background-color:#CCC"><?= number_format($total_HA,2) ?></td>
					<td align="center" id="bordertable" style="background-color:#CCC"><?= $total_TBS ?></td>
					<td align="center" id="bordertable" style="background-color:#CCC"><?= $total_BRONDOLAN ?></td>
					<td align="center" id="bordertable" style="background-color:#CCC"><?= $total_BM ?></td>
					<td align="center" id="bordertable" style="background-color:#CCC"><?= $total_BK ?></td>
					<td align="center" id="bordertable" style="background-color:#CCC"><?= $total_TP ?></td>
					<td align="center" id="bordertable" style="background-color:#CCC"><?= $total_BB ?></td>
					<td align="center" id="bordertable" style="background-color:#CCC"><?= $total_JK ?></td>
					<td align="center" id="bordertable" style="background-color:#CCC"><?= $total_BA ?></td>
					<td align="center" id="bordertable" style="background-color:#CCC"><?= $total_BT ?></td>
					<td align="center" id="bordertable" style="background-color:#CCC"><?= $total_BL ?></td>
					<td align="center" id="bordertable" style="background-color:#CCC"><?= $total_PB ?></td>
					<td align="center" id="bordertable" style="background-color:#CCC"><?= $total_AB ?></td>
					<td align="center" id="bordertable" style="background-color:#CCC"><?= $total_SF ?></td>
					<td align="center" id="bordertable" style="background-color:#CCC"><?= $total_BS ?></td>
					<td align="center" id="bordertable" style="background-color:#CCC"></td>
			    </tr>
			</tbody>
		  </table>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<form action="doExport.php" name="formExport" method="post">
		<td height="50" colspan="9" align="right" valign="baseline"><input type="submit" name="btn_export" id="btn_export" value="EXPORT" style="width:120px; height: 30px" <?php if($jml_valid<1){ echo "disabled"; } ?> /></td>
		</form>
	</tr>
	<?php } ?>
	<tr>
		<th align="center"><?php include("../include/Footer.php") ?></th>
	</tr>
</table>
