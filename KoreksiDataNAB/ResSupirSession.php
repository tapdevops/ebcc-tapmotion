<?php
session_start();
unset($_SESSION['BASupir']);
unset($_SESSION['NIK_Supir']);
unset($_SESSION['Nama_Supir']);
unset($_SESSION['Afd_Supir']);
header("Location:KoreksiNABSelect.php");
?>