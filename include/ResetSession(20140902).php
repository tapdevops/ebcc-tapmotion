<?php
session_start();

unset($_SESSION["Cpage"]);

if($_GET["link"] == "bccrestan")
{
	$LaporanBCCRestan = "";
	if(isset($_POST["LaporanBCCRestan"])){
		$LaporanBCCRestan = $_POST["LaporanBCCRestan"];
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
	}
	if(isset($_SESSION["LaporanLHM"])){
		$LaporanLHM = $_SESSION["LaporanLHM"];
	}
	header("Location:../LaporanLHM/laporanLHMFilter.php");
}
else if($_GET["link"] == "nab")
{
	$LaporanNAB = "";
	if(isset($_POST["LaporanNAB"])){
		$LaporanNAB = $_POST["LaporanNAB"];
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
	}
	if(isset($_SESSION["LaporanLaporanBCC"])){
		$LaporanLaporanBCC = $_SESSION["LaporanLaporanBCC"];
	}
	header("Location:../LaporanBCC/LaporanBCC.php");
}
else if($_GET["link"] == "KorAAP")
{
	$KoreksiDataAAP = "";
	if(isset($_POST["KoreksiDataAAP"])){
		$KoreksiDataAAP = $_POST["KoreksiDataAAP"];
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

?>