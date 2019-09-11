<?php 
session_start();
include("../include/Header.php"); //TAMBAHKAN INI
if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name'])){	
$Job_Code = $_SESSION['Job_Code'];
$username = $_SESSION['NIK'];	
$Emp_Name = $_SESSION['Name'];
$Comp_Name = $_SESSION['Comp_Name'];
$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
$Date = $_SESSION['Date'];
	
	if($username == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:../index.php");
	}
	/*else{
	$_SESSION['Job_Code']	= $Job_Code;
	$_SESSION['NIK'] 		= $username;	
	$_SESSION['Name'] 	= $Emp_Name;
	$_SESSION['Jenis_Login'] 	= $Jenis_Login;	
	$_SESSION['subID_BA_Afd']	= $subID_BA_Afd;
	}*/
?>
<style type="text/css">
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
body,td,th {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-weight:normal;
}
</style>


<div align="center" style="width:1260px; min-width:1260px; max-width:1260px">
<div class="divbodytable" align="center">
<div id="welcome">Selamat datang, <?=$Emp_Name?> (<?=$username?>)</div>
<div id="date"><?=$Date?></div>
</div>
<div class="diverr">
<?php
		if(isset($_SESSION['err'])){
			$err = $_SESSION['err'];
			if($err!=null)
			{
				echo $err;
				unset($_SESSION['err']);
			}
		}
?>  
</div>
<div class="buttonlogout">
<a href="../config/db_disconnect.php">
<input type="button" name="Report_U" id="Report_U" value="Logout" style="width:140px; height:30px; color:#333"/>
</a>
</div> 
</div>

<?php
include("../include/footer.php");
}
else{
	$_SESSION[err] = "tolong login dulu!".$Job_Code."<br>".$username."<br>".$Emp_Name."<br>".$Jenis_Login."<br>".$subID_BA_Afd;
	header("location:../index.php");

}
?>