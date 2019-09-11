<?php
session_start();

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
</style>
<table width="923" height="424" border="0" align="center">
  <tr style="background:url(image/logo.png) no-repeat; background-position:center">
    <th width="580" height="115" scope="row"><?php include("../include/Header.php") ?></th>
  </tr>
  <tr style="background-position:center; font-style: italic; color: #F00; font-size:18px ">
    <th height="372" scope="row" valign="top">You Don't Have Authority</th>
  </tr>
  <tr>
    <th align="center"><?php include("../include/Footer.php") ?></th>
  </tr>
</table>
<?php
}
else{
	$_SESSION[err] = "tolong login dulu!".$Job_Code."<br>".$username."<br>".$Emp_Name."<br>".$Jenis_Login."<br>".$subID_BA_Afd;
	header("location:../index.php");

}
?>