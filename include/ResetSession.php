<?php
session_start();

unset($_SESSION["Cpage"]);

if($_GET["link"] == "bccrestan")
{
	$LaporanBCCRestan = "";
	if(isset($_POST["LaporanBCCRestan"])){
		$LaporanBCCRestan = $_POST["LaporanBCCRestan"];
		$_SESSION["LaporanBCCRestan"] = $LaporanBCCRestan;
	}else if(isset($_GET["LaporanBCCRestan"])){
		$LaporanBCCRestan = $_GET["LaporanBCCRestan"];
		$_SESSION["LaporanBCCRestan"] = $LaporanBCCRestan;
	}
	if(isset($_SESSION["LaporanBCCRestan"])){
		$LaporanBCCRestan = $_SESSION["LaporanBCCRestan"];
	}
	unset($_SESSION['sql_bcc_restan']);
	unset($_SESSION["date1"]);
	unset($_SESSION["date2"]);
	unset($_SESSION["Cpage"]);
	unset($_SESSION["ctr"]);
	unset($_SESSION["CC"]);
	unset($_SESSION["BA"]);
	unset($_SESSION["AFD"]);
	unset($_SESSION["JA_No"]);
	unset($_SESSION["roweffec_AC"]);
	
	unset($_SESSION["JA_No"]);
	unset($_SESSION["JA_No"]);
	
	header("Location:../BCCRestan/daftarbccrestan.php");
}
else if($_GET["link"] == "lhm")
{
	$LaporanLHM = "";
	if(isset($_POST["LaporanLHM"])){
		$LaporanLHM = $_POST["LaporanLHM"];
		$_SESSION["LaporanLHM"] = $LaporanLHM;
	}else if(isset($_GET["LaporanLHM"])){
		$LaporanLHM = $_GET["LaporanLHM"];
		$_SESSION["LaporanLHM"] = $LaporanLHM;
	}
	if(isset($_SESSION["LaporanLHM"])){
		$LaporanLHM = $_SESSION["LaporanLHM"];
	}
	header("Location:../LaporanLHM/laporanLHMFilter.php");
}
else if($_GET["link"] == "lapbcp")
{
	$LaporanLaporanBCP = "";
	if(isset($_POST["LaporanLaporanBCP"])){
		$LaporanLaporanBCP = $_POST["LaporanLaporanBCP"];
		$_SESSION["LaporanLaporanBCP"] = $LaporanLaporanBCP;
	}
	if(isset($_SESSION["LaporanLaporanBCP"])){
		$LaporanLaporanBCP = $_SESSION["LaporanLaporanBCP"];
	}
	header("Location:../LaporanBCP/laporanBCPFilter.php");
}
else if($_GET["link"] == "lapbccloss")
{
	
	$LaporanLaporanBCCLoss = "";
	if(isset($_POST["LaporanLaporanBCCLoss"])){
		$LaporanLaporanBCCLoss = $_POST["LaporanLaporanBCCLoss"];
		$_SESSION["LaporanLaporanBCCLoss"] = $LaporanLaporanBCCLoss;
	}else if(isset($_GET["LaporanLaporanBCCLoss"])){
		$LaporanLaporanBCCLoss = $_GET["LaporanLaporanBCCLoss"];
		$_SESSION["LaporanLaporanBCCLoss"] = $LaporanLaporanBCCLoss;
	}
	if(isset($_SESSION["LaporanLaporanBCCLoss"])){
		$LaporanLaporanBCCLoss = $_SESSION["LaporanLaporanBCCLoss"];
	}
	header("Location:../LaporanBCCLoss/LaporanBCCLoss.php");
}
else if($_GET["link"] == "nab")
{
	$LaporanNAB = "";
	if(isset($_POST["LaporanNAB"])){
		$LaporanNAB = $_POST["LaporanNAB"];
		$_SESSION["LaporanNAB"] = $LaporanNAB;
	}else if(isset($_GET["LaporanNAB"])){
		$LaporanNAB = $_GET["LaporanNAB"];
		$_SESSION["LaporanNAB"] = $LaporanNAB;
	}
	if(isset($_SESSION["LaporanNAB"])){
		$LaporanNAB = $_SESSION["LaporanNAB"];
	}
	header("Location:../LaporanNAB/laporanNABFilter.php");
}
else if($_GET["link"] == "prod")
{
	$LaporanProduksi = "";
	if(isset($_POST["LaporanProduksi"])){
		$LaporanProduksi = $_POST["LaporanProduksi"];
		$_SESSION["LaporanProduksi"] = $LaporanProduksi;
	}else if(isset($_GET["LaporanProduksi"])){
		$LaporanProduksi = $_GET["LaporanProduksi"];
		$_SESSION["LaporanProduksi"] = $LaporanProduksi;
	}
	if(isset($_SESSION["LaporanProduksi"])){
		$LaporanProduksi = $_SESSION["LaporanProduksi"];
	}
	//Reset session Laporan Produksi
	unset($_SESSION['sql_laporan_production']);
	unset($_SESSION["BA"]);
	unset($_SESSION["AFD"]);
	unset($_SESSION["BLOK"]);
	unset($_SESSION["NIK_Pemanen"]);
	unset($_SESSION["date1"]);
	unset($_SESSION["date2"]);
	unset($_SESSION["ctr"]);
	header("Location:../LaporanProduksi/LaporanProduksiView.php");
}
else if($_GET["link"] == "lapbcc")
{
	$LaporanLaporanBCC = "";
	if(isset($_POST["LaporanLaporanBCC"])){
		$LaporanLaporanBCC = $_POST["LaporanLaporanBCC"];
		$_SESSION["LaporanLaporanBCC"] = $LaporanLaporanBCC;
	}else if(isset($_GET["LaporanLaporanBCC"])){
		$LaporanLaporanBCC = $_GET["LaporanLaporanBCC"];
		$_SESSION["LaporanLaporanBCC"] = $LaporanLaporanBCC;
	}
	if(isset($_SESSION["LaporanLaporanBCC"])){
		$LaporanLaporanBCC = $_SESSION["LaporanLaporanBCC"];
	}
	header("Location:../LaporanBCC/LaporanBCC.php");
}
else if($_GET["link"] == "lapbcccompare")
{
	$LaporanLaporanBCCCompare = "";
	if(isset($_POST["LaporanLaporanBCCCompare"])){
		$LaporanLaporanBCC = $_POST["LaporanLaporanBCCCompare"];
		$_SESSION["LaporanLaporanBCCCompare"] = $LaporanLaporanBCCCompare;
	}else if(isset($_GET["LaporanLaporanBCCCompare"])){
		$LaporanLaporanBCC = $_GET["LaporanLaporanBCCCompare"];
		$_SESSION["LaporanLaporanBCCCompare"] = $LaporanLaporanBCCCompare;
	}
	if(isset($_SESSION["LaporanLaporanBCCCompare"])){
		$LaporanLaporanBCCCompare = $_SESSION["LaporanLaporanBCCCompare"];
	}
	header("Location:../LaporanBCCCompare/LaporanBCCCompare.php");
}
else if($_GET["link"] == "KorAAP")
{
	$KoreksiDataAAP = "";
	if(isset($_POST["KoreksiDataAAP"])){
		$KoreksiDataAAP = $_POST["KoreksiDataAAP"];
		$_SESSION["KoreksiDataAAP"] = $KoreksiDataAAP;
	}else if(isset($_GET["KoreksiDataAAP"])){
		$KoreksiDataAAP = $_GET["KoreksiDataAAP"];
		$_SESSION["KoreksiDataAAP"] = $KoreksiDataAAP;
	}
	if(isset($_SESSION["KoreksiDataAAP"])){
		$KoreksiDataAAP = $_SESSION["KoreksiDataAAP"];
	}
	//Reset session Laporan Produksi
	unset($_SESSION['sql_koreksi_aap']);
	unset($_SESSION["BA"]);
	unset($_SESSION["AFD"]);
	unset($_SESSION["BLOK"]);
	unset($_SESSION["NIK_Pemanen"]);
	unset($_SESSION["date1"]);
	unset($_SESSION["date2"]);
	unset($_SESSION["ctr"]);
	header("Location:../KoreksiDataAAP/KoreksiAAPView.php");
}
else if($_GET["link"] == "KorBCC")
{
	$KoreksiDataBCC = "";
	if(isset($_POST["KoreksiDataBCC"])){
		$KoreksiDataBCC = $_POST["KoreksiDataBCC"];
		$_SESSION["KoreksiDataBCC"] = $KoreksiDataBCC;
	}else if(isset($_GET["KoreksiDataBCC"])){
		$KoreksiDataBCC = $_GET["KoreksiDataBCC"];
		$_SESSION["KoreksiDataBCC"] = $KoreksiDataBCC;	
	}
	if(isset($_SESSION["KoreksiDataBCC"])){
		$KoreksiDataBCC = $_SESSION["KoreksiDataBCC"];
	}
	
	unset($_SESSION["editNO_BCC"]);
	unset($_SESSION["BA"]);
	unset($_SESSION["NIK_Pemanen"]);
	header("Location:../KoreksiDataBCC/KoreksiBCCFil.php");
}
else if($_GET["link"] == "KorNAB")
{
	$KoreksiDataNAB = "";
	if(isset($_POST["KoreksiDataNAB"])){
		$KoreksiDataNAB = $_POST["KoreksiDataNAB"];
		$_SESSION["KoreksiDataNAB"] = $KoreksiDataNAB;
	}else if(isset($_GET["KoreksiDataNAB"])){
		$KoreksiDataNAB = $_GET["KoreksiDataNAB"];
		$_SESSION["KoreksiDataNAB"] = $KoreksiDataNAB;	
	}
	if(isset($_SESSION["KoreksiDataNAB"])){
		$KoreksiDataNAB = $_SESSION["KoreksiDataNAB"];
	}
	
	unset($_SESSION["editNO_NAB"]);
	unset($_SESSION["sql_t_NAB"]);
	unset($_SESSION["BASupir"]);
	unset($_SESSION["BATM1"]);
	unset($_SESSION["BATM2"]);
	unset($_SESSION["BATM3"]);
	unset($_SESSION["NIK_Supir"]);
	unset($_SESSION["Afd_Supir"]);
	unset($_SESSION["Nama_Supir"]);
	unset($_SESSION["NIK_TM1"]);
	unset($_SESSION["Afd_TM1"]);
	unset($_SESSION["Nama_TM1"]);
	unset($_SESSION["NIK_TM2"]);
	unset($_SESSION["Afd_TM2"]);
	unset($_SESSION["Nama_TM2"]);
	unset($_SESSION["NIK_TM3"]);
	unset($_SESSION["Afd_TM3"]);
	unset($_SESSION["Nama_TM3"]);	
	unset($_SESSION['SessTIPE_ORDER']);
	unset($_SESSION['No_PolisiLabel']);
	unset($_SESSION['Id_Internal_OrderLabel']);
	
	header("Location:../KoreksiDataNAB/KoreksiNABFil.php");
}
else if($_GET["link"] == "cetaklhm")
{
	$CetakLHMPanen = "";
	if(isset($_POST["CetakLHMPanen"])){
		$CetakLHMPanen = $_POST["CetakLHMPanen"];
		$_SESSION["CetakLHMPanen"] = $CetakLHMPanen;
	}else if(isset($_GET["CetakLHMPanen"])){
		$CetakLHMPanen = $_GET["CetakLHMPanen"];
		$_SESSION["CetakLHMPanen"] = $CetakLHMPanen;
	}

	if(isset($_SESSION["CetakLHMPanen"])){
		$CetakLHMPanen = $_SESSION["CetakLHMPanen"];
	}
	
	header("Location:../CetakLHMPanen/WelCetakLHMPanenFilter.php");
}
else if($_GET["link"] == "saptemplatech")
{
	$SAPTemplateCH = "";
	if(isset($_POST["SAPTemplateCH"])){
		$SAPTemplateCH = $_POST["SAPTemplateCH"];
		$_SESSION["SAPTemplateCH"] = $SAPTemplateCH;
	}else if(isset($_GET["SAPTemplateCH"])){
		$SAPTemplateCH = $_GET["SAPTemplateCH"];
		$_SESSION["SAPTemplateCH"] = $SAPTemplateCH;
	}

	if(isset($_SESSION["SAPTemplateCH"])){
		$SAPTemplateCH = $_SESSION["SAPTemplateCH"];
	}
	
	header("Location:../DownloadSAPCropHarvest/DownloadSAPCH.php");
}
else if($_GET["link"] == "downloadpenalty")
{
	$SAPTemplateP = "";
	if(isset($_POST["SAPTemplateP"])){
		$SAPTemplateP = $_POST["SAPTemplateP"];
		$_SESSION["SAPTemplateP"] = $SAPTemplateP;
	}else if(isset($_GET["SAPTemplateP"])){
		$SAPTemplateP = $_GET["SAPTemplateP"];
		$_SESSION["SAPTemplateP"] = $SAPTemplateP;
	}

	if(isset($_SESSION["SAPTemplateP"])){
		$SAPTemplateP = $_SESSION["SAPTemplateP"];
	}
	
	header("Location:../DownloadPenalty/DownloadPenalty.php");
}
else if($_GET["link"] == "downloadnab")
{
	$SAPTemplateNAB = "";
	if(isset($_POST["SAPTemplateNAB"])){
		$SAPTemplateNAB = $_POST["SAPTemplateNAB"];
		$_SESSION["SAPTemplateNAB"] = $SAPTemplateNAB;
	}else if(isset($_GET["SAPTemplateNAB"])){
		$SAPTemplateNAB = $_GET["SAPTemplateNAB"];
		$_SESSION["SAPTemplateNAB"] = $SAPTemplateNAB;
	}

	if(isset($_SESSION["SAPTemplateNAB"])){
		$SAPTemplateNAB = $_SESSION["SAPTemplateNAB"];
	}
	
	header("Location:../DownloadSAPNAB/TampilkanSAPNAB.php");
}

//Added by Ardo, 07-08-2016
else if($_GET["link"] == "exporttosaplhmpanen")
{
	$ExportToSAPLHMPanen = "";
	if(isset($_POST["ExportToSAPLHMPanen"])){
		$ExportToSAPLHMPanen = $_POST["ExportToSAPLHMPanen"];
		$_SESSION["ExportToSAPLHMPanen"] = $ExportToSAPLHMPanen;
	}else if(isset($_GET["ExportToSAPLHMPanen"])){
		$ExportToSAPLHMPanen = $_GET["ExportToSAPLHMPanen"];
		$_SESSION["ExportToSAPLHMPanen"] = $ExportToSAPLHMPanen;
	}

	if(isset($_SESSION["ExportToSAPLHMPanen"])){
		$ExportToSAPLHMPanen = $_SESSION["ExportToSAPLHMPanen"];
		
	}
	
	header("Location:../ExportToSAPLHMPanen/ExportToSAPLHMPanen.php");
}
else if($_GET["link"] == "exporttosapnab")
{
	$ExportToSAPNAB = "";
	if(isset($_POST["ExportToSAPNAB"])){
		$ExportToSAPNAB = $_POST["ExportToSAPNAB"];
		$_SESSION["ExportToSAPNAB"] = $ExportToSAPNAB;
	}else if(isset($_GET["ExportToSAPNAB"])){
		$ExportToSAPNAB = $_GET["ExportToSAPNAB"];
		$_SESSION["ExportToSAPNAB"] = $ExportToSAPNAB;
		
	}

	if(isset($_SESSION["ExportToSAPNAB"])){
		$ExportToSAPNAB = $_SESSION["ExportToSAPNAB"];
		
		
	}
	
	header("Location:../ExportToSAPNAB/ExportToSAPNAB.php");
}
//end by ardo

else if($_GET["link"] == "lapduplicate")
{
	$LaporanDuplicateBCC = "";
	if(isset($_POST["LaporanDuplicateBCC"])){
		$LaporanDuplicateBCC = $_POST["LaporanDuplicateBCC"];
		$_SESSION["LaporanDuplicateBCC"] = $LaporanDuplicateBCC;
	}else if(isset($_GET["LaporanDuplicateBCC"])){
		$LaporanDuplicateBCC = $_GET["LaporanDuplicateBCC"];
		$_SESSION["LaporanDuplicateBCC"] = $LaporanDuplicateBCC;
	}

	if(isset($_SESSION["LaporanDuplicateBCC"])){
		$LaporanDuplicateBCC = $_SESSION["LaporanDuplicateBCC"];
	}
	
	header("Location:../LaporanDuplicate/daftarbccrestan.php");
}
//Added by Ardo, 23-11-2016 : Change Request Laporan Aktivitas Akhir Panen
else if($_GET["link"] == "lapaap")
{
	$LaporanAAP = "";
	if(isset($_POST["LaporanAAP"])){
		$LaporanAAP = $_POST["LaporanAAP"];
		$_SESSION["LaporanAAP"] = $LaporanAAP;
	}else if(isset($_GET["LaporanAAP"])){
		$LaporanAAP = $_GET["LaporanAAP"];
		$_SESSION["LaporanAAP"] = $LaporanAAP;
	}

	if(isset($_SESSION["LaporanAAP"])){
		$LaporanAAP = $_SESSION["LaporanAAP"];
		
	}
	
	header("Location:../LaporanAAP/LaporanAAP.php");
}
//end by Ardo
else if($_GET["link"] == "ihp")
{
	$InputHasilPanen = "";
	if(isset($_POST["InputHasilPanen"])){
		$InputHasilPanen = $_POST["InputHasilPanen"];
		$_SESSION["InputHasilPanen"] = $InputHasilPanen;
	}else if(isset($_GET["InputHasilPanen"])){
		$InputHasilPanen = $_GET["InputHasilPanen"];
		$_SESSION["InputHasilPanen"] = $InputHasilPanen;
	}

	if(isset($_SESSION["InputHasilPanen"])){
		$InputHasilPanen = $_SESSION["InputHasilPanen"];
	}
	
	header("Location:../InputHasilPanen/inputHasilPanen.php");
}
else if($_GET["link"] == "ipp")
{
	$InputPengirimanPanen = "";
	if(isset($_POST["InputPengirimanPanen"])){
		$InputPengirimanPanen = $_POST["InputPengirimanPanen"];
		$_SESSION["InputPengirimanPanen"] = $InputPengirimanPanen;
	}else if(isset($_GET["InputPengirimanPanen"])){
		$InputPengirimanPanen = $_GET["InputPengirimanPanen"];
		$_SESSION["InputPengirimanPanen"] = $InputPengirimanPanen;
	}

	if(isset($_SESSION["InputPengirimanPanen"])){
		$InputPengirimanPanen = $_SESSION["InputPengirimanPanen"];
	}
	
	header("Location:../InputPengirimanPanen/inputPengirimanPanen.php");
}
else if($_GET["link"] == "iap")
{
	$InputAktivitasPanen = "";
	if(isset($_POST["InputAktivitasPanen"])){
		$InputAktivitasPanen = $_POST["InputAktivitasPanen"];
		$_SESSION["InputAktivitasPanen"] = $InputAktivitasPanen;
	}else if(isset($_GET["InputAktivitasPanen"])){
		$InputAktivitasPanen = $_GET["InputAktivitasPanen"];
		$_SESSION["InputAktivitasPanen"] = $InputAktivitasPanen;
	}

	if(isset($_SESSION["InputAktivitasPanen"])){
		$InputAktivitasPanen = $_SESSION["InputAktivitasPanen"];
	}
	
	header("Location:../InputAktivitasPanen/inputAktivitasPanen.php");
}
else if($_GET["link"] == "vja")
{
	$JobAuthorityView = "";
	if(isset($_POST["JobAuthorityView"])){
		$JobAuthorityView = $_POST["JobAuthorityView"];
		$_SESSION["JobAuthorityView"] = $JobAuthorityView;
	}else if(isset($_GET["JobAuthorityView"])){
		$JobAuthorityView = $_GET["JobAuthorityView"];
		$_SESSION["JobAuthorityView"] = $JobAuthorityView;
	}

	if(isset($_SESSION["JobAuthorityView"])){
		$JobAuthorityView = $_SESSION["JobAuthorityView"];
	}
	
	header("Location:../JobAuthoEmployee/viewjobauthority.php");
}
else if($_GET["link"] == "pg")
{
	$PanenGandeng = "";
	if(isset($_POST["PanenGandeng"])){
		$PanenGandeng = $_POST["PanenGandeng"];
		$_SESSION["PanenGandeng"] = $PanenGandeng;
	}else if(isset($_GET["PanenGandeng"])){
		$PanenGandeng = $_GET["PanenGandeng"];
		$_SESSION["PanenGandeng"] = $PanenGandeng;
	}

	if(isset($_SESSION["PanenGandeng"])){
		$PanenGandeng = $_SESSION["PanenGandeng"];
	}
	
	header("Location:../PanenGandeng/panengandeng.php");
}


?>