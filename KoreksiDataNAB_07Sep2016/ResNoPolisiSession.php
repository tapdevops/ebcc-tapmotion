<?php
session_start();
unset($_SESSION['SessTIPE_ORDER']);
header("Location:KoreksiNABSelect.php");
?>