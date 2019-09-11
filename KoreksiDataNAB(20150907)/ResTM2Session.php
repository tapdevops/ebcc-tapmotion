<?php
session_start();
unset($_SESSION['BATM2']);
unset($_SESSION['NIK_TM2']);
unset($_SESSION['Nama_TM2']);
unset($_SESSION['Afd_TM2']);
header("Location:KoreksiNABSelect.php");
?>