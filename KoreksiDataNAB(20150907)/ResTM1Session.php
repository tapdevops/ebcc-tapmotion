<?php
session_start();
unset($_SESSION['BATM1']);
unset($_SESSION['NIK_TM1']);
unset($_SESSION['Nama_TM1']);
unset($_SESSION['Afd_TM1']);
header("Location:KoreksiNABSelect.php");
?>