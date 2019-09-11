

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" href="../css/menu.css">
<script type="text/javascript" src="../js/menu.js"></script>
<link rel="stylesheet" href="../css/style.css">

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
       
        <form id="formCetakLHMPanen" name="formCetakLHMPanen" method="post" action="../CetakLHMPanen/WelCetakLHMPanenFilter.php">
        <input name="CetakLHMPanen" type="text" id="CetakLHMPanen" value="TRUE" style="display:none" onmousedown="return false"/>
        <la><a href="javascript:;" onclick="javascript: document.getElementById('formCetakLHMPanen') .submit()">Cetak LHM Panen</a></la>
        </form>
						
        <form id="formSAPTemplate" name="formSAPTemplate" method="post" action="#">
        <input name="SAPTemplate" type="text" id="SAPTemplate" value="TRUE" style="display:none" onmousedown="return false"/>
        <li><a href="javascript:;" onclick="javascript: document.getElementById('formSAPTemplate') .submit()">SAP Template</a>
        <ul>
        </form>
                        
            <form id="formSAPTemplateCH" name="formSAPTemplateCH" 
            method="post" action="../DownloadSAPCropHarvest/DownloadSAPCH.php">
            <input name="SAPTemplateCH" type="text" id="SAPTemplateCH" value="TRUE" 
            style="display:none" onmousedown="return false"/>
            <li>
            <a href="javascript:;" onclick="javascript: document.getElementById('formSAPTemplateCH') .submit()">Crop Harvesting
            </a></li>
            </form>
            
            <form id="formSAPTemplateP" name="formSAPTemplateP" 
            method="post" action="../DownloadPenalty/DownloadPenalty.php">
            <input name="SAPTemplateP" type="text" id="SAPTemplateP" value="TRUE" 
            style="display:none" onmousedown="return false"/>
            <li><a href="javascript:;" onclick="javascript: document.getElementById('formSAPTemplateP') .submit()">Denda Panen
            </a></li>
            </form>
            
            <form id="formSAPTemplateNAB" name="formSAPTemplateNAB" 
            method="post" action="../DownloadSAPNAB/TampilkanSAPNAB.php">
            <input name="SAPTemplateNAB" type="text" id="SAPTemplateNAB" value="TRUE" 
            style="display:none" onmousedown="return false"/>
            <li><a href="javascript:;" onclick="javascript: document.getElementById('formSAPTemplateNAB') .submit()">NAB
            </a></li>
            </form>
                
        </ul>
		</li>
				
        <form id="formKoreksiData" name="formKoreksiData" method="post" action="#">
        <input name="KoreksiData" type="text" id="KoreksiData" value="TRUE" style="display:none" onmousedown="return false"/>
        <li><a href="javascript:;" onclick="javascript: document.getElementById('formKoreksiData') .submit()">Koreksi Data</a>
        <ul>
        </form>
                        
            <form id="formKoreksiDataBCC" name="formKoreksiDataBCC" 
            method="post" action="../include/ResetSession.php?link=KorBCC">
            <input name="KoreksiDataBCC" type="text" id="KoreksiDataBCC" 
            value="TRUE" style="display:none" onmousedown="return false"/>
            <li><a href="javascript:;" onclick="javascript: document.getElementById('formKoreksiDataBCC') .submit()">BCC
            </a></li>
            </form>
            
            <form id="formKoreksiDataNAB" name="formKoreksiDataNAB" 
            method="post" action="../include/ResetSession.php?link=KorNAB">
            <input name="KoreksiDataNAB" type="text" id="KoreksiDataNAB" 
            value="TRUE" style="display:none" onmousedown="return false"/>
            <li><a href="javascript:;" onclick="javascript: document.getElementById('formKoreksiDataNAB') .submit()">NAB
            </a></li>
            </form>
			
            <form id="formKoreksiDataAAP" name="formKoreksiDataAAP" 
            method="post" action="../include/ResetSession.php?link=KorAAP">
            <input name="KoreksiDataAAP" type="text" id="KoreksiDataAAP" 
            value="TRUE" style="display:none" onmousedown="return false"/>
            <li><a href="javascript:;" onclick="javascript: document.getElementById('formKoreksiDataAAP') .submit()">AAP
            </a></li>
            </form>
                                
       		</ul>
        </li>
				
        <form id="formLaporan" name="formLaporan" method="post" action="#">
        <input name="Laporan" type="text" id="Laporan" value="TRUE" style="display:none" onmousedown="return false"/>
        <li><a href="javascript:;" onclick="javascript: document.getElementById('formLaporan') .submit()">Laporan</a>
        <ul>
        </form>
                        
            <form id="formLaporanLHM" name="formLaporanLHM" method="post" action="../include/ResetSession.php?link=lhm">
            <input name="LaporanLHM" type="text" id="LaporanLHM" 
            value="TRUE" style="display:none" onmousedown="return false"/>
            <li><a href="javascript:;" onclick="javascript: document.getElementById('formLaporanLHM') .submit()">LHM</a></li>
            </form>
            
            <form id="formLaporanNAB" name="formLaporanNAB" method="post" action="../include/ResetSession.php?link=nab">
            <input name="LaporanNAB" type="text" id="LaporanNAB" 
            value="TRUE" style="display:none" onmousedown="return false"/>
            <li><a href="javascript:;" onclick="javascript: document.getElementById('formLaporanNAB') .submit()">NAB</a></li>
            </form>
            
            <form id="formLaporanBCCRestan" name="formLaporanBCCRestan" 
            method="post" action="../include/ResetSession.php?link=bccrestan">
            <input name="LaporanBCCRestan" type="text" id="LaporanBCCRestan" 
            value="TRUE" style="display:none" onmousedown="return false"/>
            <li><a href="javascript:;" onclick="javascript: document.getElementById('formLaporanBCCRestan') .submit()">BCC Restan
            </a></li>
            </form>
                                
            <form id="formLaporanProduksi" name="formLaporanProduksi" 
            method="post" action="../include/ResetSession.php?link=prod">
            <input name="LaporanProduksi" type="text" id="LaporanProduksi" 
            value="TRUE" style="display:none" onmousedown="return false"/>
            <li><a href="javascript:;" onclick="javascript: document.getElementById('formLaporanProduksi') .submit()">Produksi
            </a></li>
            </form>
            
            <form id="formLaporanLaporanBCC" name="formLaporanLaporanBCC" 
            method="post" action="../include/ResetSession.php?link=lapbcc">
            <input name="LaporanLaporanBCC" type="text" id="LaporanLaporanBCC" 
            value="TRUE" style="display:none" onmousedown="return false"/>
            <li><a href="javascript:;" onclick="javascript: document.getElementById('formLaporanLaporanBCC') .submit()">Laporan BCC
            </a></li>
            </form>                              
            
            <form id="formLaporanDuplicateBCC" name="formLaporanDuplicateBCC" 
            method="post" action="../LaporanDuplicate/daftarbccrestan.php">
            <input name="LaporanDuplicateBCC" type="text" id="LaporanDuplicateBCC" 
            value="TRUE" style="display:none" onmousedown="return false"/>
            <li>
            <a href="javascript:;" onclick="javascript: document.getElementById('formLaporanDuplicateBCC') .submit()">Duplicate BCC
            </a></li>
            </form>
                                
        	</ul>
        </li>
                		
        <form id="formParameterSetting" name="formParameterSetting" method="post" action="#">
        <input name="ParameterSetting" type="text" id="ParameterSetting" 
        value="TRUE" style="display:none" onmousedown="return false"/>
        <li><a href="javascript:;" onclick="javascript: document.getElementById('formParameterSetting') .submit()">Parameter Setting</a>
        <ul>
        </form>
    
            <form id="formPanenGandeng" name="formPanenGandeng" 
            method="post" action="../PanenGandeng/panengandeng.php">
            <input name="PanenGandeng" type="text" id="PanenGandeng" 
            value="TRUE" style="display:none" onmousedown="return false"/>
            <li><a href="javascript:;" onclick="javascript: document.getElementById('formPanenGandeng') .submit()">Panen Gandeng
            </a></li>
            </form>
                                
        	</ul>
        </li>
				
        <form id="formAdministration" name="formAdministration" method="post" action="#">
        <input name="Administration" type="text" id="Administration" 
        value="TRUE" style="display:none" onmousedown="return false"/>
        <li><a href="javascript:;" onclick="javascript: document.getElementById('formAdministration') .submit()">Administration</a>
        <ul>
        </form>
                        
            <form id="formJobAuthority" name="formJobAuthority" method="post" action="#">
            <input name="JobAuthority" type="text" id="JobAuthority" 
            value="TRUE" style="display:none" onmousedown="return false"/>
            <li><a href="javascript:;" onclick="javascript: document.getElementById('formJobAuthority') .submit()">Job Authority</a>
            <ul>
            </form>
                                		
                <form id="formJobAuthorityView" name="formJobAuthorityView" 
                method="post" action="../JobAuthoEmployee/viewjobauthority.php">
                <input name="JobAuthorityView" type="text" id="JobAuthorityView" 
                value="TRUE" style="display:none" onmousedown="return false"/>
                <li><a href="javascript:;" onclick="javascript: document.getElementById('formJobAuthorityView') .submit()">View
                </a></li>
                </form>
                                        
            	</ul>
            </li>
                                  
            <form id="formIDGroup" name="formIDGroup" method="post" action="#">
            <input name="IDGroup" type="text" id="IDGroup" 
            value="TRUE" style="display:none" onmousedown="return false"/>
            <li><a href="javascript:;" onclick="javascript: document.getElementById('formIDGroup') .submit()">ID Group</a>
            <ul>
            </form>
                                
                <form id="formEDITABLE" name="formEDITABLE" 
                method="post" action="../GroupBA/createnewgroupba.php">
                <input name="EDITABLE" type="text" id="EDITABLE" 
                value="TRUE" style="display:none" onmousedown="return false"/>
                <input name="IDGroupCN" type="text" id="IDGroupCN" 
                value="TRUE" style="display:none" onmousedown="return false"/>
                <li><a href="javascript:;" onclick="javascript: document.getElementById('formEDITABLE') .submit()">Create new
                </a></li>
                </form>
                
                <form id="formIDGroupView" name="formIDGroupView" 
                method="post" action="../GroupBA/daftargroupba.php">
                <input name="IDGroupView" type="text" id="IDGroupView" 
                value="TRUE" style="display:none" onmousedown="return false"/>
                <li><a href="javascript:;" onclick="javascript: document.getElementById('formIDGroupView') .submit()">View
                </a></li>
                </form>
                                        
            	</ul>
            </li>
                   

			<form id="formBCCLost2" name="formBCCLost2" 
            method="post" action="../BCCLost/createnewbcclost.php">
            <input name="BCCLost" type="text" id="BCCLost" 
            value="TRUE" style="display:none" onmousedown="return false"/>
            <li><a href="javascript:;" onclick="javascript: document.getElementById('formBCCLost2') .submit()">BCC Lost
            </a></li>
            </form>	
			
            <form id="formRegisterDevice" name="formRegisterDevice" 

            method="post" action="../inputdevice/inputdevice.php">
            <input name="RegisterDevice" type="text" id="RegisterDevice" 
            value="TRUE" style="display:none" onmousedown="return false"/>
            <li><a href="javascript:;" onclick="javascript: document.getElementById('formRegisterDevice') .submit()">Register Device
            </a></li>
            </form>
			
			 <form id="formSetIp" name="formSetIp" 

            method="post" action="../setip/inputip.php">
            <input name="SettingIp" type="text" id="SettingIp" 
            value="TRUE" style="display:none" onmousedown="return false"/>
            <li><a href="javascript:;" onclick="javascript: document.getElementById('formSetIp') .submit()">Set IP
            </a></li>
            </form>
        
           
            
            <form id="formChangePassword" name="formChangePassword" 
            method="post" action="../ChangePass/changepass.php">
            <input name="ChangePassword" type="text" id="ChangePassword" 
            value="TRUE" style="display:none" onmousedown="return false"/>
            <li><a href="javascript:;" onclick="javascript: document.getElementById('formChangePassword') .submit()">Change Password
            </a></li>
            </form>

						
        	</ul>
        </li>
		
    </ul>
</div>
</div>   
</div>  
</div> 
</body>
</html>

