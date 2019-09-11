<?php
	session_start();
	session_destroy();
	$_SESSION[err] = "You have been logout";
	header("location:../index.php");
?>