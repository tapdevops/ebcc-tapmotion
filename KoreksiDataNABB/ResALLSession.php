<?php
session_start();

unset($_SESSION['BASupir']);
unset($_SESSION['NIK_Supir']);
unset($_SESSION['Nama_Supir']);
unset($_SESSION['Afd_Supir']);

unset($_SESSION['BATM1']);
unset($_SESSION['NIK_TM1']);
unset($_SESSION['Nama_TM1']);
unset($_SESSION['Afd_TM1']);

unset($_SESSION['BATM2']);
unset($_SESSION['NIK_TM2']);
unset($_SESSION['Nama_TM2']);
unset($_SESSION['Afd_TM2']);

unset($_SESSION['BATM3']);
unset($_SESSION['NIK_TM3']);
unset($_SESSION['Nama_TM3']);
unset($_SESSION['Afd_TM3']);
header("Location:KoreksiNABSelect.php");
?>