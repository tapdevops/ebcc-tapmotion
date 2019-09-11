<?php
session_start();
unset($_SESSION['NIK_Pemanen']);
unset($_SESSION['BA']);
header("Location:KoreksiBCCSelect.php");
?>