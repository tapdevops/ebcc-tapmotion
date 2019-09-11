<?php
session_start();

unset($_SESSION[No_Polisi_io]);
unset($_SESSION[No_Polisiint]);
unset($_SESSION[Supirint]);
unset($_SESSION[TM1SBCCNAB]);
unset($_SESSION[TM2SBCCNAB]);
unset($_SESSION[TM3SBCCNAB]);

header("Location:EditStatusBCC&NAB.php");

?>