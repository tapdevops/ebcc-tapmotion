<?php
session_start();
if(isset($_SESSION['NIK']) && isset($_SESSION['roweffec_t_nik_passwd'])){
	$NIK = $_SESSION['NIK'];
	$roweffec_jenis_login = $_SESSION['roweffec_t_nik_passwd'];
	
	//echo "sini".$NIK.$roweffec_jenis_login."<br>";;
	$initial = false;
	for($y =0; $y<$roweffec_jenis_login; $y++){
		if(isset($_SESSION["Jenis_LoginHead$y"])){
			$Jenis_Login[$y] = $_SESSION["Jenis_LoginHead$y"];
			//echo $Jenis_Login[$y];
			if($Jenis_Login[$y] == 4 || $Jenis_Login[$y] == 5 || $Jenis_Login[$y] == 6 || $Jenis_Login[$y] == 8 || $Jenis_Login[$y] == 9 || $Jenis_Login[$y] == 10|| $Jenis_Login[$y] == 0){
				$initial = true;
			}
			else{
				$_SESSION['err'] = "Not Allowed. Check Jenis Login";
				header("Location:../index.php");
				//echo $_SESSION[err];
				
			}
		}	
		else{
			$_SESSION['err'] = "jenis login not found";
			header("Location:../index.php");
			//echo $_SESSION['err'];
		}
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TAP - MOTION EBCC</title>
<link rel="stylesheet" href="../css/menu.css">
<script type="text/javascript" src="../js/menu.js"></script>
<link rel="stylesheet" href="../css/style.css">
<style type="text/css">
	table td { vertical-align: middle; }
</style>
</head>

<body>
<div align="center">
<div class="headerbg">
<div id="logoimage" align="left"><img src="../image/logo2.png"/>
<div id="logoname"><strong>TAP MOTION - eBCC</strong>

</div>

</div>


<div id="bgmenu" class="mlmenu bgimagehead">
    <div id="menu" class="mlmenu horizontal greenwhite">
    <ul>
        <lo><a href="../menu/home.php">Home</a></lo>
        <?php
		if($initial == true){
				$x_found = false;
				for($x =0; $x<$roweffec_jenis_login; $x++)
				{
					//if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 0)
					if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9 )
					{
						$x_found = true;
					}
				}
				if($x_found == true){
					echo "<form id=\"formCetakLHMPanen\" name=\"formCetakLHMPanen\" method=\"post\" action=\"../CetakLHMPanen/WelCetakLHMPanenFilter.php\">
						<input name=\"CetakLHMPanen\" type=\"text\" id=\"CetakLHMPanen\" value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
						<la><a href=\"../include/ResetSession.php?link=cetaklhm&CetakLHMPanen=TRUE\">Cetak LHM Panen</a></la>
						</form>";
					//echo "<la><a href=\"../CetakLHMPanen/WelCetakLHMPanenFilter.php\">Cetak LHM Panen</a></la>";
				}
				else{
					echo "<la><a href=\"../menu/authoritysecure.php\">Cetak LHM Panen</a></la>";
				}
		
				$x_found = false;
				$x_found_1 = false;
				$x_found_2 = false;
				$x_found_3 = false;
				for($x =0; $x<$roweffec_jenis_login; $x++)
				{
					//if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
					if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9)
					{
						$x_found = true;
						//if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 0)
						
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9)
						
						{
							$x_found_1 = true;
							
						}
						//if($Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
						if($Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9)
						{
							$x_found_2 = true;
						}
						//if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 0)
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9)
						{
							$x_found_3 = true;
							
						}
					}
				}
				
				if($x_found == true){
					echo "<form id=\"formSAPTemplate\" name=\"formSAPTemplate\" method=\"post\" action=\"#\">
						<input name=\"SAPTemplate\" type=\"text\" id=\"SAPTemplate\" value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
						<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formSAPTemplate') .submit()\">SAP Template</a><ul>
						</form>"; 
					//echo "<li><a href=\"#\"><span style=\"font-size:15px\">SAP Template</span></a><ul>";
						
					if($x_found_1 == true){
						echo "<form id=\"formSAPTemplateCH\" name=\"formSAPTemplateCH\" 
							method=\"post\" action=\"../DownloadSAPCropHarvest/DownloadSAPCH.php\">
							<input name=\"SAPTemplateCH\" type=\"text\" id=\"SAPTemplateCH\" value=\"TRUE\" 
							style=\"display:none\" onmousedown=\"return false\"/>
							<li>
							<a href=\"../include/ResetSession.php?link=saptemplatech&SAPTemplateCH=TRUE\">Crop Harvesting
							</a></li>
							</form>";
						//echo "<li><a href=\"../DownloadSAPCropHarvest/DownloadSAPCH.php\">Crop Harvesting</a></li>";
					} 
					/*else{
						echo "<li><a href=\"../menu/authoritysecure.php\">Crop Harvesting</a></li>";
					}*/
						
					if($x_found_3 == true){
						echo "<form id=\"formSAPTemplateP\" name=\"formSAPTemplateP\" 
							method=\"post\" action=\"../DownloadPenalty/DownloadPenalty.php\">
							<input name=\"SAPTemplateP\" type=\"text\" id=\"SAPTemplateP\" value=\"TRUE\" 
							style=\"display:none\" onmousedown=\"return false\"/>
							<li><a href=\"../include/ResetSession.php?link=downloadpenalty&SAPTemplateP=TRUE\">Denda Panen
							</a></li>
							</form>"; 
						//echo "<li><a href=\"../DownloadSAPNAB/TampilkanSAPNAB.php\">NAB</a></li>";
					}
					
					if($x_found_2 == true){
						echo "<form id=\"formSAPTemplateNAB\" name=\"formSAPTemplateNAB\" 
							method=\"post\" action=\"../DownloadSAPNAB/TampilkanSAPNAB.php\">
							<input name=\"SAPTemplateNAB\" type=\"text\" id=\"SAPTemplateNAB\" value=\"TRUE\" 
							style=\"display:none\" onmousedown=\"return false\"/>
							<li><a href=\"../include/ResetSession.php?link=downloadnab&SAPTemplateNAB=TRUE\">NAB
							</a></li>
							</form>"; 
						//echo "<li><a href=\"../DownloadSAPNAB/TampilkanSAPNAB.php\">NAB</a></li>";
					}
					
				   
					/*else{
						echo "<li><a href=\"../menu/authoritysecure.php\">NAB</a></li>";
					}*/
						
					echo  "</ul>
					</li>";
					
					//Added by Ardo, 07-08-2016
					
					echo "<form id=\"formExportToSAP\" name=\"formExportToSAP\" method=\"post\" action=\"#\">
						<input name=\"ExportToSAP\" type=\"text\" id=\"ExportToSAP\" value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
						<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formExportToSAP') .submit()\">Export To SAP</a><ul>
						</form>"; 
						
					if($x_found_1 == true){
						echo "<form id=\"formExportToSAPLHMPanen\" name=\"formExportToSAPLHMPanen\" 
							method=\"post\" action=\"../ExportToSAPLHMPanen/ExportToSAPLHMPanen.php\">
							<input name=\"ExportToSAPLHMPanen\" type=\"text\" id=\"ExportToSAPLHMPanen\" value=\"TRUE\" 
							style=\"display:none\" onmousedown=\"return false\"/>
							<li>
							<a href=\"../include/ResetSession.php?link=exporttosaplhmpanen&ExportToSAPLHMPanen=TRUE\">LHM Panen
							</a></li>
							</form>";
					} 
					
					
					if($x_found_2 == true){
						echo "<form id=\"formExportToSAPNAB\" name=\"formExportToSAPNAB\" 
							method=\"post\" action=\"../ExportToSAPNAB/ExportToSAPNAB.php\">
							<input name=\"ExportToSAPNAB\" type=\"text\" id=\"ExportToSAPNAB\" value=\"TRUE\" 
							style=\"display:none\" onmousedown=\"return false\"/>
							<li><a href=\"../include/ResetSession.php?link=exporttosapnab&ExportToSAPNAB=TRUE\">NAB
							</a></li>
							</form>"; 
					}
						
					echo  "</ul>
					</li>";
					
				}
				else{
					echo "<li><a href=\"../menu/authoritysecure.php\">SAP Template</a></li>";
					echo "<li><a href=\"../menu/authoritysecure.php\">Export To SAP</a></li>";
				}
				
				$x_found = false;
				$x_found_1 = false;
				$x_found_2 = false;
				$x_found_3 = false;
				for($x =0; $x<$roweffec_jenis_login; $x++)
				{
					//if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
					if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9)
					{
						$x_found = true;
						//if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 0)
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9)
						{
							$x_found_1 = true;
						}
						//if($Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
						if($Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9)
						{
							$x_found_2 = true;
						}
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9)
						{
							$x_found_3 = true;
						}
					}
				}
				if($x_found == true){
					echo "<form id=\"formKoreksiData\" name=\"formKoreksiData\" method=\"post\" action=\"#\">
						<input name=\"KoreksiData\" type=\"text\" id=\"KoreksiData\" value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
						<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formKoreksiData') .submit()\">Koreksi Data</a><ul>
						</form>";	
					//echo "<li><a href=\"#\">Koreksi Data</a><ul>";
						if($x_found_1 == true){
							echo "<form id=\"formKoreksiDataBCC\" name=\"formKoreksiDataBCC\" 
								method=\"post\" action=\"../include/ResetSession.php?link=KorBCC\">
								<input name=\"KoreksiDataBCC\" type=\"text\" id=\"KoreksiDataBCC\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../include/ResetSession.php?link=KorBCC&KoreksiDataBCC=TRUE\">BCC
								</a></li>
								</form>";	
							//echo "<li><a href=\"../include/ResetSession.php?link=KorBCC\">BCC</a></li>";
						}
						/*else{
							echo "<li><a href=\"../menu/authoritysecure.php\">BCC</a></li>";
						}*/
						
						if($x_found_2 == true){
							echo "<form id=\"formKoreksiDataNAB\" name=\"formKoreksiDataNAB\" 
								method=\"post\" action=\"../include/ResetSession.php?link=KorNAB\">
								<input name=\"KoreksiDataNAB\" type=\"text\" id=\"KoreksiDataNAB\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../include/ResetSession.php?link=KorNAB&KoreksiDataNAB=TRUE\">NAB
								</a></li>
								</form>";	
							//echo "<li><a href=\"../include/ResetSession.php?link=KorNAB\">NAB</a></li>";
						}
						/*else{
							echo "<li><a href=\"../menu/authoritysecure.php\">NAB</a></li>";
						}*/
						
						if($x_found_3 == true){
							echo "<form id=\"formKoreksiDataAAP\" name=\"formKoreksiDataAAP\" 
								method=\"post\" action=\"../include/ResetSession.php?link=KorAAP\">
								<input name=\"KoreksiDataAAP\" type=\"text\" id=\"KoreksiDataAAP\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../include/ResetSession.php?link=KorAAP&KoreksiDataAAP=TRUE\">AAP
								</a></li>
								</form>";	
							//echo "<li><a href=\"../include/ResetSession.php?link=KorNAB\">NAB</a></li>";
						}
						/*else{
							echo "<li><a href=\"../menu/authoritysecure.php\">NAB</a></li>";
						}*/
						
				echo "</ul>
				</li>";
				}
				else{
					echo "<li><a href=\"../menu/authoritysecure.php\">Koreksi Data</a></li>";
				}
				
				$x_found = false;
				$x_found_1 = false;
				$x_found_2 = false;
				$x_found_3 = false;
				$x_found_4 = false;
				$x_found_5 = false;
				$x_found_6 = false;
				$x_found_7 = false;
				$x_found_8 = false;
				for($x =0; $x<$roweffec_jenis_login; $x++)
				{
				//echo $Jenis_Login[$x];die();
					if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 10)
					{
						//Edited by Ardo, 21-09-2016 : .LAP lihat semua laporan
						
						$x_found = true;
						//if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 10 || $Jenis_Login[$x] == 8)
						{
							$x_found_1 = true;
						}
						//if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 10)
						{
							$x_found_2 = true;
						}
						//if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 10)
						{
							$x_found_3 = true;
							$x_found_8 = true;
						}
						//if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 10)
						{
							$x_found_4 = true;
						}
						//if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 10 || $Jenis_Login[$x] == 8)
						{
							$x_found_5 = true;
						}
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 10)
						{
							$x_found_6 = true;
						}
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 10 || $Jenis_Login[$x] == 8)
						{
							$x_found_7 = true;
						}
					}
				}
				if($x_found == true){
					echo "<form id=\"formLaporan\" name=\"formLaporan\" method=\"post\" action=\"#\">
						<input name=\"Laporan\" type=\"text\" id=\"Laporan\" value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
						<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formLaporan') .submit()\">Laporan</a><ul>
						</form>";
					//echo "<li><a href=\"#\">Laporan</a><ul>";
						/*<li><a href="../Report/LaporanLHMPanenFilter/Filter/AfdMNPMC.php?nilai=3">LHM</a></li>*/
						
						if($x_found_1 == true){
							echo "<form id=\"formLaporanLHM\" name=\"formLaporanLHM\">
								<input name=\"LaporanLHM\" type=\"text\" id=\"LaporanLHM\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../include/ResetSession.php?link=lhm&LaporanLHM=TRUE\">Laporan Hasil Panen</a></li>
								</form>";
							//echo "<li><a href=\"../include/ResetSession.php?link=lhm\">LHM</a></li>";
						}
						if($x_found_2 == true){
							echo "<form id=\"formLaporanNAB\" name=\"formLaporanNAB\" method=\"post\" action=\"../include/ResetSession.php?link=nab\">
								<input name=\"LaporanNAB\" type=\"text\" id=\"LaporanNAB\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../include/ResetSession.php?link=nab&LaporanNAB=TRUE\">NAB</a></li>
								</form>";
							//echo "<li><a href=\"../include/ResetSession.php?link=nab\">NAB</a></li>";
						}
						if($x_found_3 == true){
							echo "<form id=\"formLaporanBCCRestan\" name=\"formLaporanBCCRestan\" 
								method=\"post\" action=\"../include/ResetSession.php?link=bccrestan\">
								<input name=\"LaporanBCCRestan\" type=\"text\" id=\"LaporanBCCRestan\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../include/ResetSession.php?link=bccrestan&LaporanBCCRestan=TRUE\">BCC Restan
								</a></li>
								</form>";
							//echo "<li><a href=\"../include/ResetSession.php?link=bccrestan\">BCC Restan</a></li>";
						}
						if($x_found_4 == true){
							echo "<form id=\"formLaporanProduksi\" name=\"formLaporanProduksi\" 
								method=\"post\" action=\"../include/ResetSession.php?link=prod\">
								<input name=\"LaporanProduksi\" type=\"text\" id=\"LaporanProduksi\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../include/ResetSession.php?link=prod&LaporanProduksi=TRUE\">Taksasi Produksi
								</a></li>
								</form>";
							//echo "<li><a href=\"../include/ResetSession.php?link=prod\">Produksi</a></li>";
						}
						
						if($x_found_5 == true){
							echo "<form id=\"formLaporanLaporanBCC\" name=\"formLaporanLaporanBCC\" 
								method=\"post\" action=\"../include/ResetSession.php?link=lapbcc\">
								<input name=\"LaporanLaporanBCC\" type=\"text\" id=\"LaporanLaporanBCC\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../include/ResetSession.php?link=lapbcc&LaporanLaporanBCC=TRUE\">Laporan BCC
								</a></li>
								</form>";
								
							echo "<form id=\"formLaporanLaporanBCCCompare\" name=\"formLaporanLaporanBCCCompare\" 
								method=\"post\" action=\"../include/ResetSession.php?link=lapbcccompare\">
								<input name=\"LaporanLaporanBCCCompare\" type=\"text\" id=\"LaporanLaporanBCCCompare\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../include/ResetSession.php?link=lapbcccompare&LaporanLaporanBCCCompare=TRUE\">Laporan BCC Compare
								</a></li>
								</form>";
							//echo "<li><a href=\"../include/ResetSession.php?link=lapbcc\">Laporan BCC</a></li>";
						}//echo $x_found_6;die();
						
						if($x_found_6 == true){
							echo "<form id=\"formLaporanLaporanBCP\" name=\"formLaporanLaporanBCP\" 
								method=\"post\" action=\"../include/ResetSession.php?link=lapbcp\">
								<input name=\"LaporanLaporanBCP\" type=\"text\" id=\"LaporanLaporanBCP\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../include/ResetSession.php?link=lapbcp&LaporanLaporanBCP=TRUE\">Laporan BCP
								</a></li>
								</form>";
						}
						if($x_found_8 == true){
							//Edited by Ardo 19-08-2016 : Synchronize BCC - Laporan BCC Loss/WO/Delete
							echo "<form id=\"formLaporanLaporanBCCLoss\" name=\"formLaporanLaporanBCCLoss\" 
								method=\"post\" action=\"../include/ResetSession.php?link=lapbccloss\">
								<input name=\"LaporanLaporanBCCLoss\" type=\"text\" id=\"LaporanLaporanBCCLoss\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a style='height:50px' href=\"../include/ResetSession.php?link=lapbccloss&LaporanLaporanBCCLoss=TRUE\">Laporan BCC Loss/WO/Delete
								</a></li>
								</form>";
						}
						if($x_found_7 == true){
							echo "<form id=\"formLaporanDuplicateBCC\" name=\"formLaporanDuplicateBCC\" 
								method=\"post\" action=\"../LaporanDuplicate/daftarbccrestan.php\">
								<input name=\"LaporanDuplicateBCC\" type=\"text\" id=\"LaporanDuplicateBCC\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li>
								<a href=\"../include/ResetSession.php?link=lapduplicate&LaporanDuplicateBCC=TRUE\">Duplicate BCC
								</a></li>
								</form>";
							//echo "<li><a href=\"../LaporanDuplicate/daftarbccrestan.php\">Duplicate BCC</a></li>";
						}
						
						//Added by Ardo, 23 Nov 2016 : Change Request Laporan Aktivitas Akhir Panen
						if($x_found_1 == true){
							echo "<form id=\"formLaporanAAP\" name=\"formLaporanAAP\">
								<input name=\"LaporanAAP\" type=\"text\" id=\"LaporanAAP\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a style='height:50px' href=\"../include/ResetSession.php?link=lapaap&LaporanAAP=TRUE\">Laporan Aktivitas Akhir Panen</a></li>
								</form>";
						}

						echo '<li><a href="../LaporanKraniBuah/LaporanKraniBuah.php">Laporan Krani Buah</a></li>';
						
				echo "</ul>
				</li>";
				}
				else{
					echo "<li><a href=\"../menu/authoritysecure.php\">Laporan</a></li>";
				}
				
				$x_found = false;
				$x_found_1 = false;
				$x_found_2 = false;
				$x_found_3 = false;
				for($x =0; $x<$roweffec_jenis_login; $x++)
				{
					//if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
					if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9)
					{
						$x_found = true;
						//if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 0)
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9)
						{
							$x_found_1 = true;
						}
						//if($Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
						if($Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9)
						{
							$x_found_2 = true;
						}
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 9)
						{
							$x_found_3 = true;
						}
					}
				}
				if($x_found == true){
					 echo "<form id=\"formSAPTemplate\" name=\"formSAPTemplate\" method=\"post\" action=\"#\">
						<input name=\"SAPTemplate\" type=\"text\" id=\"BCP\" value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
						<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formSAPTemplate') .submit()\">BCP</a><ul>
						</form>"; 	
					//echo "<li><a href=\"#\">Koreksi Data</a><ul>";
						if($x_found_1 == true){
							echo "<form id=\"formInputHasilPanen\" name=\"formInputHasilPanen\" 
								method=\"post\" action=\"../InputHasilPanen/inputHasilPanen.php\">
								<input name=\"InputHasilPanen\" type=\"text\" id=\"InputHasilPanen\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../include/ResetSession.php?link=ihp&InputHasilPanen=TRUE\">Input Hasil Panen
								</a></li>
								</form>";	
							//echo "<li><a href=\"../include/ResetSession.php?link=KorBCC\">BCC</a></li>";
						}
						/*else{
							echo "<li><a href=\"../menu/authoritysecure.php\">BCC</a></li>";
						}*/
						
						if($x_found_2 == true){
							echo "<form id=\"formInputPengiriman\" name=\"formInputPengiriman\" 
								method=\"post\" action=\"../InputPengirimanPanen/inputPengirimanPanen.php\">
								<input name=\"InputPengirimanPanen\" type=\"text\" id=\"InputPengirimanPanen\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../include/ResetSession.php?link=ipp&InputPengirimanPanen=TRUE\">Input Pengiriman Panen
								</a></li>
								</form>";	
							//echo "<li><a href=\"../include/ResetSession.php?link=KorNAB\">NAB</a></li>";
						}
						/*else{
							echo "<li><a href=\"../menu/authoritysecure.php\">NAB</a></li>";
						}*/
						
						if($x_found_3 == true){
							echo "<form id=\"formInputAktivitas\" name=\"formInputAktivitas\" 
								method=\"post\" action=\"../InputAktivitasPanen/inputAktivitasPanen.php\">
								<input name=\"InputAktivitasPanen\" type=\"text\" id=\"InputAktivitasPanen\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../include/ResetSession.php?link=iap&InputAktivitasPanen=TRUE\">Input Aktivitas
								</a></li>
								</form>";	
							//echo "<li><a href=\"../include/ResetSession.php?link=KorNAB\">NAB</a></li>";
						}
						/*else{
							echo "<li><a href=\"../menu/authoritysecure.php\">NAB</a></li>";
						}*/
						
				echo "</ul>
				</li>";
				}
				else{
					echo "<li><a href=\"../menu/authoritysecure.php\">Koreksi Data</a></li>";
				}
				$x_found = false;
				$x_found_1 = false;
				for($x =0; $x<$roweffec_jenis_login; $x++)
				{
					if($Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 0 || $Jenis_Login[$x] == 10)
					{
						$x_found = true;
						if($Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 0)
						{
							$x_found_1 = true;
						}
					}
				}
				if($x_found == true){
					
					echo "<form id=\"formParameterSetting\" name=\"formParameterSetting\" method=\"post\" action=\"#\">
						<input name=\"ParameterSetting\" type=\"text\" id=\"ParameterSetting\" 
						value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
						<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formParameterSetting') .submit()\">Parameter Setting
						</a><ul>
						</form>";
					
					//echo "<li><a href=\"#\">Parameter Setting</a><ul>";
						if($x_found_1 == true){
							echo "<form id=\"formPanenGandeng\" name=\"formPanenGandeng\" 
								method=\"post\" action=\"../PanenGandeng/panengandeng.php\">
								<input name=\"PanenGandeng\" type=\"text\" id=\"PanenGandeng\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../include/ResetSession.php?link=pg&PanenGandeng=TRUE\">Panen Gandeng
								</a></li>
								</form>";
							//echo "<li><a href=\"../PanenGandeng/panengandeng.php\">Panen Gandeng</a></li>";
						}
				echo "</ul>
				</li>";
				}
				else{
					echo "<li><a href=\"../menu/authoritysecure.php\">Parameter Setting</a></li>";
				}
				
				$x_found = false;
				//jobauthority
				$x_found_1 = false;
				$x_found_1_1 = false;
				//idgroup
				$x_found_2 = false;
				$x_found_2_1 = false;
				$x_found_2_2 = false;
				$x_found_2_3 = false;
				$x_found_2_4 = false;
				//registerdevice
				$x_found_3 = false;
				//bcclost
				$x_found_4 = false;
				//changepassword
				$x_found_5 = false;
				$x_found_6 = false;
				$x_found_7 = false;
				for($x =0; $x<$roweffec_jenis_login; $x++){
					if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 10 || $Jenis_Login[$x] == 0){
						$x_found = true;
						if($Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 0){
							$x_found_1 = true;
							if($Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 0){
								$x_found_1_1 = true;
							}
						}
						if($Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 0){
							$x_found_2 = true;
							if($Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 0){
								$x_found_2_1 = true;
							}
							if($Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 0){
								$x_found_2_2 = true;
							}
							
						}
						if($Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 0){
							$x_found_3 = true;
						}
						if($Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 0){
							$x_found_4 = true;
						}
						if($Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 0){
							$x_found_6 = true;
						}
						if($Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 0){
							$x_found_7 = true;
						}
						if($Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 0){
							$x_found_9 = true;
						}
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 10 || $Jenis_Login[$x] == 0){
							$x_found_5 = true;
						}
					}
				}
				if($x_found == true){
					echo "<form id=\"formAdministration\" name=\"formAdministration\" method=\"post\" action=\"#\">
						<input name=\"Administration\" type=\"text\" id=\"Administration\" 
						value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
						<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formAdministration') .submit()\">Administration
						</a><ul>
						</form>";
					//echo "<li><a href=\"#\">Administration</a><ul>";
					
						if($x_found_1 == true){
							echo "<form id=\"formJobAuthority\" name=\"formJobAuthority\" method=\"post\" action=\"#\">
								<input name=\"JobAuthority\" type=\"text\" id=\"JobAuthority\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formJobAuthority') .submit()\">Job Authority
								</a><ul>
								</form>";
								
								if($x_found_1_1 == true){
									echo "<form id=\"formJobAuthorityView\" name=\"formJobAuthorityView\" 
										method=\"post\" action=\"../JobAuthoEmployee/viewjobauthority.php\">
										<input name=\"JobAuthorityView\" type=\"text\" id=\"JobAuthorityView\" 
										value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
										<li><a href=\"../include/ResetSession.php?link=vja&JobAuthorityView=TRUE\">View
										</a></li>
										</form>";
								}
							echo "</ul>
							</li>";
							
							/*echo 
							"<li><a href=\"#\">Job Authority</a>
								<ul>
									<li><a href=\"../JobAuthoEmployee/viewjobauthority.php\">View</a></li>
								</ul>
							</li>";*/
							
						}
						
						if($x_found_2 == true){
							echo "<form id=\"formIDGroup\" name=\"formIDGroup\" method=\"post\" action=\"#\">
								<input name=\"IDGroup\" type=\"text\" id=\"IDGroup\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formIDGroup') .submit()\">ID Group
								</a><ul>
								</form>";
						
								if($x_found_2_1 == true){
									echo "<form id=\"formEDITABLE\" name=\"formEDITABLE\" 
										method=\"post\" action=\"../GroupBA/createnewgroupba.php\">
										<input name=\"EDITABLE\" type=\"text\" id=\"EDITABLE\" 
										value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
										<input name=\"IDGroupCN\" type=\"text\" id=\"IDGroupCN\" 
										value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
										<li><a href=\"../GroupBA/createnewgroupba.php\">Create new
										</a></li>
										</form>";
								}
								if($x_found_2_2 == true){
									echo "<form id=\"formIDGroupView\" name=\"formIDGroupView\" 
										method=\"post\" action=\"../GroupBA/daftargroupba.php\">
										<input name=\"IDGroupView\" type=\"text\" id=\"IDGroupView\" 
										value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
										<li><a href=\"../GroupBA/daftargroupba.php\">View
										</a></li>
										</form>";
								}	
							echo "</ul>
							</li>";
							
							/*echo 
							"<li><a href=\"#\">ID Group</a>
								<ul>
									<form id=\"formEDITABLE\" name=\"formEDITABLE\" 
									method=\"post\" action=\"../GroupBA/createnewgroupba.php\">
									<input name=\"EDITABLE\" type=\"text\" id=\"EDITABLE\" 
									value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
									<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formEDITABLE') .submit()\">Create new
									</a></li>
									</form>
									<li><a href=\"../GroupBA/daftargroupba.php\">View</a></li>
								</ul>
							</li>"; */
						}
						
				
						if($x_found_4 == true){
							echo 
								"<form id=\"formBCCLost2\" name=\"formBCCLost2\" 
								method=\"post\" action=\"../BCCLost/createnewbcclost.php\">
								<input name=\"BCCLost\" type=\"text\" id=\"BCCLost\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../BCCLost/createnewbcclost.php\">BCC Loss
								</a></li>
								</form>"; 
							//echo "<li><a href=\"../BCCLost/createnewbcclost.php\">BCC Lost</a></li>";
						}
						if($x_found_3 == true){
							echo "<form id=\"formRegisterDevice\" name=\"formRegisterDevice\" 
								method=\"post\" action=\"../inputdevice/inputdevice.php\">
								<input name=\"RegisterDevice\" type=\"text\" id=\"RegisterDevice\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../inputdevice/inputdevice.php\">Register Device
								</a></li>
								</form>";
								
								
							//echo "<li><a href=\"../inputdevice/inputdevice.php\">Register Device</a></li>";
						}
						if($x_found_6 == true){
							echo "<form id=\"formSetIP\" name=\"formSetIP\" 
								method=\"post\" action=\"../setip/inputip.php\">
								<input name=\"SetIP\" type=\"text\" id=\"SetIP\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../setip/inputip.php\">Setting IP
								</a></li>
								</form>";
								
								
							//echo "<li><a href=\"../setip/inputip.php\">Setting IP</a></li>";
						}
						if($x_found_9 == true){
							echo "<form id=\"formInputParameterBunch\" name=\"formInputParameterBunch\" 
								method=\"post\" action=\"../inputParameterBunch/inputparameterbunch.php\">
								<input name=\"inputParameterBunch\" type=\"text\" id=\"inputParameterBunch\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../inputParameterBunch/inputparameterbunch.php\">Setting Bunch
								</a></li>
								</form>";
								
							//echo "<li><a href=\"../inputdevice/inputdevice.php\">Register Device</a></li>";
						}
						
						if($x_found_1 == true){
							echo "<li><a href=\"../qrcode/qrcode.php\">QR Code</a></li>";
							//echo "<li><a href=\"../reqrcode/reqrcode.php\">Re-Print QR Code</a></li>";
						}
						
						if($x_found_4 == true){
							echo "<li><a href=\"../reqrcode/reqrcode.php\">Re-Print QR Code</a></li>";
						}
						
						if($x_found_7 == true){
							echo "<form id=\"formInputUser\" name=\"formInputUser\" 
								method=\"post\" action=\"../inputuser/inputdevice.php\">
								<input name=\"InputUser\" type=\"text\" id=\"InputUser\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"../inputuser/inputdevice.php\">Input User
								</a></li>
								</form>";
								
							//echo "<li><a href=\"../inputdevice/inputdevice.php\">Register Device</a></li>";
						}
						if($x_found_5 == true){
							echo "<form id=\"formChangePassword\" name=\"formChangePassword\" 
								method=\"post\" action=\"../ChangePass/changepass.php\">
								<input name=\"ChangePassword\" type=\"text\" id=\"ChangePassword\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formChangePassword') .submit()\">Change Password
								</a></li>
								</form>";
							//echo "<li><a href=\"../ChangePass/changepass.php\">Change Password</a></li>";
							echo "<li><a href=\"../config/db_disconnect.php\">Logout</a></li>";
						}

				echo "</ul>
				</li>";
				}
				else{
					echo "<li><a href=\"../menu/authoritysecure.php\">Parameter Setting</a></li>";
				}
		}
		?>
	
    </ul>
</div>
</div>    
</div>
</body>
</html>

<?php

}
else{
	$_SESSION['err'] = "Not Allowed. Check NIK";
	header("Location:../index.php");
	//echo $_SESSION['err'];	
}
?>
