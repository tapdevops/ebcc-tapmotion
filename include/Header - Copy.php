<?php
//session_start();
if(isset($_SESSION['NIK']) && isset($_SESSION['roweffec_t_nik_passwd'])){
	$NIK = $_SESSION['NIK'];
	$roweffec_jenis_login = $_SESSION['roweffec_t_nik_passwd'];
	
	//echo "sini".$NIK.$roweffec_jenis_login."<br>";;
	$initial = false;
	for($y =0; $y<$roweffec_jenis_login; $y++){
		if(isset($_SESSION["Jenis_LoginHead$y"])){
			$Jenis_Login[$y] = $_SESSION["Jenis_LoginHead$y"];
			//echo $Jenis_Login[$y];
			if($Jenis_Login[$y] == 4 || $Jenis_Login[$y] == 5 || $Jenis_Login[$y] == 6 || $Jenis_Login[$y] == 8 || $Jenis_Login[$y] == 9 || $Jenis_Login[$y] == 0){
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
<title>Untitled Document</title>
<link rel="stylesheet" href="../css/menu.css">
<link rel="stylesheet" href="../css/style.css">
<script type="text/javascript" src="../js/menu.js"></script>
<style type="text/css"> body{ padding-bottom: 200px; }</style>
</head>

<body>
<table border="0" align="center" id="sethead">
  <tr>
    <td width="304" height="97" style="background:url(../image/logo2.png) no-repeat; background-position:center">&nbsp;</td>
    <td width="821" align="center" style="font-size:24px">TAP MOBILE APPLICATION - EBCC</td>
  </tr>
  <tr style="color:#FFF" align="center">
    <td height="40" colspan="2" align="left" valign="middle">   
    <div id="bgmenu" class="mlmenu bgimagehead">
    <div id="menu" class="mlmenu horizontal greenwhite">
    <ul>
        <lo style="width: 100px"><a href="../menu/home.php">Home</a></lo>
        <?php
		if($initial == true){
				$x_found = false;
				for($x =0; $x<$roweffec_jenis_login; $x++)
				{
					if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 0)
					{
						$x_found = true;
					}
				}
				if($x_found == true){
					echo "<form id=\"formCetakLHMPanen\" name=\"formCetakLHMPanen\" method=\"post\" action=\"../CetakLHMPanen/WelCetakLHMPanenFilter.php\">
						<input name=\"CetakLHMPanen\" type=\"text\" id=\"CetakLHMPanen\" value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
						<la><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formCetakLHMPanen') .submit()\">Cetak LHM Panen</a></la>
						</form>";
					//echo "<la><a href=\"../CetakLHMPanen/WelCetakLHMPanenFilter.php\">Cetak LHM Panen</a></la>";
				}
				else{
					echo "<la><a href=\"../menu/authoritysecure.php\">Cetak LHM Panen</a></la>";
				}
		
				$x_found = false;
				$x_found_1 = false;
				$x_found_2 = false;
				for($x =0; $x<$roweffec_jenis_login; $x++)
				{
					if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
					{
						$x_found = true;
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 0)
						{
							$x_found_1 = true;
						}
						if($Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
						{
							$x_found_2 = true;
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
							<a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formSAPTemplateCH') .submit()\">Crop Harvesting
							</a></li>
							</form>"; 
						//echo "<li><a href=\"../DownloadSAPCropHarvest/DownloadSAPCH.php\">Crop Harvesting</a></li>";
					} 
					/*else{
						echo "<li><a href=\"../menu/authoritysecure.php\">Crop Harvesting</a></li>";
					}*/
						
					if($x_found_2 == true){
						echo "<form id=\"formSAPTemplateNAB\" name=\"formSAPTemplateNAB\" 
							method=\"post\" action=\"../DownloadSAPNAB/TampilkanSAPNAB.php\">
							<input name=\"SAPTemplateNAB\" type=\"text\" id=\"SAPTemplateNAB\" value=\"TRUE\" 
							style=\"display:none\" onmousedown=\"return false\"/>
							<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formSAPTemplateNAB') .submit()\">NAB
							</a></li>
							</form>"; 
						//echo "<li><a href=\"../DownloadSAPNAB/TampilkanSAPNAB.php\">NAB</a></li>";
					}
					/*else{
						echo "<li><a href=\"../menu/authoritysecure.php\">NAB</a></li>";
					}*/
						
				echo  "</ul>
				</li>";
				}
				else{
					echo "<li><a href=\"../menu/authoritysecure.php\">SAP Template</a></li>";
				}
				
				$x_found = false;
				$x_found_1 = false;
				$x_found_2 = false;
				for($x =0; $x<$roweffec_jenis_login; $x++)
				{
					if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
					{
						$x_found = true;
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 0)
						{
							$x_found_1 = true;
						}
						if($Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
						{
							$x_found_2 = true;
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
								<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formKoreksiDataBCC') .submit()\">BCC
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
								<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formKoreksiDataNAB') .submit()\">NAB
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
				for($x =0; $x<$roweffec_jenis_login; $x++)
				{
					if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
					{
						$x_found = true;
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
						{
							$x_found_1 = true;
						}
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
						{
							$x_found_2 = true;
						}
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
						{
							$x_found_3 = true;
						}
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
						{
							$x_found_4 = true;
						}
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
						{
							$x_found_5 = true;
						}
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 0)
						{
							$x_found_6 = true;
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
							echo "<form id=\"formLaporanLHM\" name=\"formLaporanLHM\" method=\"post\" action=\"../include/ResetSession.php?link=lhm\">
								<input name=\"LaporanLHM\" type=\"text\" id=\"LaporanLHM\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formLaporanLHM') .submit()\">LHM</a></li>
								</form>";
							//echo "<li><a href=\"../include/ResetSession.php?link=lhm\">LHM</a></li>";
						}
						if($x_found_2 == true){
							echo "<form id=\"formLaporanNAB\" name=\"formLaporanNAB\" method=\"post\" action=\"../include/ResetSession.php?link=nab\">
								<input name=\"LaporanNAB\" type=\"text\" id=\"LaporanNAB\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formLaporanNAB') .submit()\">NAB</a></li>
								</form>";
							//echo "<li><a href=\"../include/ResetSession.php?link=nab\">NAB</a></li>";
						}
						if($x_found_3 == true){
							echo "<form id=\"formLaporanBCCRestan\" name=\"formLaporanBCCRestan\" 
								method=\"post\" action=\"../include/ResetSession.php?link=bccrestan\">
								<input name=\"LaporanBCCRestan\" type=\"text\" id=\"LaporanBCCRestan\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formLaporanBCCRestan') .submit()\">BCC Restan
								</a></li>
								</form>";
							//echo "<li><a href=\"../include/ResetSession.php?link=bccrestan\">BCC Restan</a></li>";
						}
						if($x_found_4 == true){
							echo "<form id=\"formLaporanProduksi\" name=\"formLaporanProduksi\" 
								method=\"post\" action=\"../include/ResetSession.php?link=prod\">
								<input name=\"LaporanProduksi\" type=\"text\" id=\"LaporanProduksi\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formLaporanProduksi') .submit()\">Produksi
								</a></li>
								</form>";
							//echo "<li><a href=\"../include/ResetSession.php?link=prod\">Produksi</a></li>";
						}
						
						if($x_found_5 == true){
							echo "<form id=\"formLaporanLaporanBCC\" name=\"formLaporanLaporanBCC\" 
								method=\"post\" action=\"../include/ResetSession.php?link=lapbcc\">
								<input name=\"LaporanLaporanBCC\" type=\"text\" id=\"LaporanLaporanBCC\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formLaporanLaporanBCC') .submit()\">Laporan BCC
								</a></li>
								</form>";
							//echo "<li><a href=\"../include/ResetSession.php?link=lapbcc\">Laporan BCC</a></li>";
						}
						if($x_found_6 == true){
							echo "<form id=\"formLaporanDuplicateBCC\" name=\"formLaporanDuplicateBCC\" 
								method=\"post\" action=\"../LaporanDuplicate/daftarbccrestan.php\">
								<input name=\"LaporanDuplicateBCC\" type=\"text\" id=\"LaporanDuplicateBCC\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li>
								<a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formLaporanDuplicateBCC') .submit()\">Duplicate BCC
								</a></li>
								</form>";
							//echo "<li><a href=\"../LaporanDuplicate/daftarbccrestan.php\">Duplicate BCC</a></li>";
						}

				echo "</ul>
				</li>";
				}
				else{
					echo "<li><a href=\"../menu/authoritysecure.php\">Laporan</a></li>";
				}
				
				
				$x_found = false;
				$x_found_1 = false;
				for($x =0; $x<$roweffec_jenis_login; $x++)
				{
					if($Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 0)
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
								<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formPanenGandeng') .submit()\">Panen Gandeng
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
				//registerdevice
				$x_found_3 = false;
				//bcclost
				$x_found_4 = false;
				//changepassword
				$x_found_5 = false;
				for($x =0; $x<$roweffec_jenis_login; $x++){
					if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 0){
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
						if($Jenis_Login[$x] == 4 || $Jenis_Login[$x] == 5 || $Jenis_Login[$x] == 6 || $Jenis_Login[$x] == 8 || $Jenis_Login[$x] == 9 || $Jenis_Login[$x] == 0){
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
										<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formJobAuthorityView') .submit()\">View
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
										<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formEDITABLE') .submit()\">Create new
										</a></li>
										</form>";
								}
								if($x_found_2_2 == true){
									echo "<form id=\"formIDGroupView\" name=\"formIDGroupView\" 
										method=\"post\" action=\"../GroupBA/daftargroupba.php\">
										<input name=\"IDGroupView\" type=\"text\" id=\"IDGroupView\" 
										value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
										<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formIDGroupView') .submit()\">View
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
						
						if($x_found_3 == true){
							echo "<form id=\"formRegisterDevice\" name=\"formRegisterDevice\" 
								method=\"post\" action=\"../inputdevice/inputdevice.php\">
								<input name=\"RegisterDevice\" type=\"text\" id=\"RegisterDevice\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formRegisterDevice') .submit()\">Register Device
								</a></li>
								</form>";
							//echo "<li><a href=\"../inputdevice/inputdevice.php\">Register Device</a></li>";
						}
						if($x_found_4 == true){
							echo 
								"<form id=\"formBCCLost2\" name=\"formBCCLost2\" 
								method=\"post\" action=\"../BCCLost/createnewbcclost.php\">
								<input name=\"BCCLost\" type=\"text\" id=\"BCCLost\" 
								value=\"TRUE\" style=\"display:none\" onmousedown=\"return false\"/>
								<li><a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formBCCLost2') .submit()\">BCC Lost
								</a></li>
								</form>"; 
							//echo "<li><a href=\"../BCCLost/createnewbcclost.php\">BCC Lost</a></li>";
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
    </td>
  </tr>
</table>
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