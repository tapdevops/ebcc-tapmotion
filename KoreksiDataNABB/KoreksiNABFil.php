<?php
session_start();
if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['Jenis_Login']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name'])){	
$Job_Code = $_SESSION['Job_Code'];
$username = $_SESSION['NIK'];	
$Emp_Name = $_SESSION['Name'];
$Jenis_Login = $_SESSION['Jenis_Login'];
$Comp_Name = $_SESSION['Comp_Name'];
$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
$Date = $_SESSION['Date'];
	
	if($username == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:login.php");
	}
	
	$KoreksiDataNAB = "";
	if(isset($_POST["KoreksiDataNAB"])){
		$KoreksiDataNAB = $_POST["KoreksiDataNAB"];
		$_SESSION["KoreksiDataNAB"] = $KoreksiDataNAB;
	}
	if(isset($_SESSION["KoreksiDataNAB"])){
		$KoreksiDataNAB = $_SESSION["KoreksiDataNAB"];
	}
	
	if($KoreksiDataNAB == TRUE){
	
	
?>

<script type="text/javascript">
function change(x)
{
	if(x == 1){
	document.getElementById("Afdeling").style.visibility="visible";
	document.getElementById("NIKMandor").style.visibility="hidden";
	document.getElementById("NIKPemanen").style.visibility="hidden";
	document.getElementById("NIKMandor").value="kosong";
	document.getElementById("NIKPemanen").value="kosong";
	document.getElementById("button").style.visibility="visible";
	}
	if(x == 2){
	document.getElementById("Afdeling").style.visibility="hidden";
	document.getElementById("NIKMandor").style.visibility="visible";
	document.getElementById("NIKPemanen").style.visibility="hidden";
	document.getElementById("Afdeling").value="kosong";
	document.getElementById("NIKPemanen").value="kosong";
	document.getElementById("button").style.visibility="visible";
	}
	if(x == 3){
	document.getElementById("Afdeling").style.visibility="hidden";
	document.getElementById("NIKMandor").style.visibility="hidden";
	document.getElementById("NIKPemanen").style.visibility="visible";
	document.getElementById("Afdeling").value="kosong";
	document.getElementById("NIKMandor").value="kosong";
	document.getElementById("button").style.visibility="visible";
	}
}

function coba(x)
{
	
	if(x == 1){
	document.getElementById("Tanggal").style.visibility="visible";
	document.getElementById("Periode").style.visibility="hidden";
	document.getElementById("Periode").value="kosong";
	}
	
	if(x == 2){
	document.getElementById("Tanggal").style.visibility="hidden";
	document.getElementById("Periode").style.visibility="visible";
	document.getElementById("Tanggal").value="kosong";
	}
}
</script>

<link href="../calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../calendar/calendar.js"></script>

<?php
		require_once('../calendar/classes/tc_calendar.php');

        include("../config/SQL_function.php");
		//require_once __DIR__ . '/db_config.php'; 
		//$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
		include("../config/db_connect.php");
		$con = connect();
	}
	else{
		header("location:../menu/authoritysecure.php");
	}
}
else{
	$_SESSION[err] = "Please Login!".$Job_Code."<br>".$username."<br>".$Emp_Name."<br>".$Jenis_Login."<br>".$subID_BA_Afd;
	header("location:../index.php");

}
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
<table width="978" height="390" border="0" align="center">
  <tr>
    <th width="972" height="108" scope="row"><?php include("../include/Header.php") ?></th>
  </tr>
  <tr>
    <th height="40" scope="row" align="center"><span style="font-size: 18px">Koreksi Data NAB</span></th>
  </tr>
  <tr bgcolor="#C4D59E">
    <th height="197" scope="row" align="center">
    <form id="form3" name="form3" method="post" action="doFilter.php">
            <table width="1094" border="0" style="border:#9CC346 ridge">
              <tr>
                <td width="171">Company Name </td>
                <td width="6">&nbsp;</td>
                <td width="364"><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$Comp_Name?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
                <td width="15">&nbsp;</td>
                <td width="88">Periode</td>
                <td width="416">&nbsp;</td>
              </tr>
              <tr>
                <td>Business Area</td>
                <td>&nbsp;</td>
                <td><input name="ID_BA2" type="text" id="ID_BA2" value="<?=$subID_BA_Afd?>" style="background-color:#CCC; width:70px; height:25px; font-size:15px" onmousedown="return false"/></td>
                <td>&nbsp;</td>
                <td>Tanggal</td>
                <td id="Periode3" style="font-size:14px"><div style="float: left;">
                  <!--<div style="float: left; padding-right: 3px; line-height: 18px;">from:</div> -->
                  <div style="float: left;">
                    <?php
						$thisweek = date('W');
						$thisyear = date('Y');

						$dayTimes = getDaysInWeek($thisweek, $thisyear);
						//----------------------------------------

						$date1 = date('Y-m-d', $dayTimes[0]);
						$date2 = date('Y-m-d', $dayTimes[(sizeof($dayTimes)-1)]);

						function getDaysInWeek ($weekNumber, $year, $dayStart = 1) {
						  // Count from '0104' because January 4th is always in week 1
						  // (according to ISO 8601).
						  $time = strtotime($year . '0104 +' . ($weekNumber - 1).' weeks');
						  // Get the time of the first day of the week
						  $dayTime = strtotime('-' . (date('w', $time) - $dayStart) . ' days', $time);
						  // Get the times of days 0 -> 6
						  $dayTimes = array ();
						  for ($i = 0; $i < 7; ++$i) {
							$dayTimes[] = strtotime('+' . $i . ' days', $dayTime);
						  }
						  // Return timestamps for mon-sun.
						  return $dayTimes;
						}


					  $myCalendar = new tc_calendar("date1", true, false);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					  //$myCalendar->setDate(date('d', strtotime($date1)), date('m', strtotime($date1)), date('Y', strtotime($date1)));  //display date
					  $myCalendar->setPath("../calendar/");
					  $myCalendar->setYearInterval(2013, 2028);
					  //$myCalendar->dateAllow('2009-02-20', "", false);
					  $myCalendar->setAlignment('left', 'bottom');
					  $myCalendar->setDatePair('date1', 'date2', $date2);
					  //$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
					  $myCalendar->writeScript();
					  ?>
                  </div>
                </div>
                  <div style="float: left;">
                    <div style="float: left; padding-left: 3px; padding-right: 3px; line-height: 18px;"> s/d </div>
                    <div style="float: left;">
                      <?php
					  $myCalendar = new tc_calendar("date2", true, false);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					  //$myCalendar->setDate(date('d', strtotime($date2)), date('m', strtotime($date2)), date('Y', strtotime($date2)));  //display date
					  $myCalendar->setPath("../calendar/");
					  $myCalendar->setYearInterval(2013, 2028);
					  //$myCalendar->dateAllow("", '2009-11-03', false);
					  $myCalendar->setAlignment('left', 'bottom');
					  $myCalendar->setDatePair('date1', 'date2', $date1);
					  //$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
					  $myCalendar->writeScript();
					  ?>
                    </div>
                  </div></td>
              </tr>
              <tr>
                <td>Afdeling</td>
                <td>&nbsp;</td>
                <td><?php
				//Afdeling
				$sql_value_afd = "select *  from t_Afdeling tafd
				inner join t_BussinessArea tba
				on tafd.id_ba = tba.id_ba WHERE tba.id_ba = '$subID_BA_Afd'";
				
				$result_value_afd = oci_parse($con, $sql_value_afd);
				oci_execute($result_value_afd, OCI_DEFAULT);
				while(oci_fetch($result_value_afd)){
					$ID_AFD[]		= oci_result($result_value_afd, "ID_AFD");
					$ID_BA_Afd[]		= oci_result($result_value_afd, "ID_BA_AFD");
				}
				$roweffec_afd = oci_num_rows($result_value_afd);

				//$jumlahAfd = $_SESSION['jumlahAfd'];
				$selectoAfd = "<select name=\"Afdeling\" id=\"Afdeling\" style=\"visibility:visible; font-size: 15px\">";
				$optiondefAfd = "<option value=\"ALL\">-- ALL --</option>";
				echo $selectoAfd.$optiondefAfd;
				for($xAfd = 0; $xAfd < $roweffec_afd; $xAfd++){
					echo "<option value=\"$ID_AFD[$xAfd]\">$ID_AFD[$xAfd]</option>"; 
				}
				$selectcAfd = "</select>";
				echo $selectcAfd;
				          
				?></td>
                <td>&nbsp;</td>
                <td colspan="2">&nbsp;</td>
              </tr>
            </table>
            <div align="right" style="width:1094"><input type="submit" name="button" id="button" value="TAMPILKAN" style="width:120px; height: 30px"/></div>
          </form>
    
    </th>
  </tr>
  <tr>
    <th align="center"><?php
		if(isset($_SESSION['err'])){
			$err = $_SESSION['err'];
			if($err!=null)
			{
				echo $err;
				unset($_SESSION['err']);
			}
		}
		?></th>
  </tr>
  <tr>
    <th align="center"><?php include("../include/Footer.php") ?></th>
  </tr>
</table>
