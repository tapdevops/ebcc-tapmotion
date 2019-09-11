<?php
session_start();
unset($_SESSION['BATM3']);
unset($_SESSION['NIK_TM3']);
unset($_SESSION['Nama_TM3']);
unset($_SESSION['Afd_TM3']);
header("Location:KoreksiNABSelect.php");
?>