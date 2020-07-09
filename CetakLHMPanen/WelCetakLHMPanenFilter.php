<?php
session_start();
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';
// exit;
include("../include/Header.php");
if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name'])){	
$Job_Code = $_SESSION['Job_Code'];
$username = $_SESSION['NIK'];	
$Emp_Name = $_SESSION['Name'];
//$Jenis_Login = $_SESSION['Jenis_Login'];
$Comp_Name = $_SESSION['Comp_Name'];
$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
$Date = $_SESSION['Date'];
	
	if($username == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:../index.php");
	}
	else{
		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		
		$CetakLHMPanen = "";
		if(isset($_POST["CetakLHMPanen"])){
			$CetakLHMPanen = $_POST["CetakLHMPanen"];
			$_SESSION["CetakLHMPanen"] = $CetakLHMPanen;
		}
		if(isset($_SESSION["CetakLHMPanen"])){
			$CetakLHMPanen = $_SESSION["CetakLHMPanen"];
		}	
			
		if($CetakLHMPanen == TRUE){

			$conditionAfd = "";
			$optionGetAfd = "";
			$sesAfdeling = "";
			if(isset($_POST["Afdeling"])){
			$_SESSION["Afdeling"] = $_POST["Afdeling"];
			}
			
			if(isset($_SESSION["Afdeling"])){
				$sesAfdeling = $_SESSION["Afdeling"];
				//echo $sesAfdeling;
				$Ses_sql_afd = "select * from t_afdeling where ID_BA = '$subID_BA_Afd' and ID_AFD = nvl(decode('$sesAfdeling', 'ALL', null, '$sesAfdeling'), ID_AFD) ORDER BY ID_BA";
				//echo "here".$sql_afd;
				$Ses_result_afd = oci_parse($con, $Ses_sql_afd);
				oci_execute($Ses_result_afd, OCI_DEFAULT);
				while (oci_fetch($Ses_result_afd)) {	
					$Ses_ID_BA_Afd[] 		= oci_result($Ses_result_afd, "ID_BA_AFD");
					$Ses_ID_Afd[] 		= oci_result($Ses_result_afd, "ID_AFD");
				}
				$optionGetAfd = "<option value=\"$Ses_ID_Afd[0]\" selected=\"selected\">$Ses_ID_Afd[0]</option>";
				$conditionAfd = "and ta.ID_AFD = '$Ses_ID_Afd[0]'";
				$sesSql_Afd = "select * from t_afdeling where ID_AFD != '$sesAfdeling'";
				
				if($sesAfdeling == "ALL"){
					$sql_afd = "select * from t_afdeling where ID_BA = '$subID_BA_Afd' ORDER BY ID_BA";
					$conditionAfd = "";
					$optionGetAfd = "";
				}
				else{
					$sql_afd = "select * from t_afdeling where ID_BA = '$subID_BA_Afd' and ID_AFD != '$sesAfdeling' ORDER BY ID_BA";
				}
			}
			else{
				$sql_afd = "select * from t_afdeling where ID_BA = '$subID_BA_Afd' ORDER BY ID_BA";
			}
			
			//echo "here".$sql_afd;
			$result_afd = oci_parse($con, $sql_afd);
			oci_execute($result_afd, OCI_DEFAULT);
			while (oci_fetch($result_afd)) {	
				$ID_BA_Afd[] 		= oci_result($result_afd, "ID_BA_AFD");
				$ID_Afd[] 		= oci_result($result_afd, "ID_AFD");
			}
			$jumlahAfd = oci_num_rows($result_afd);
			//echo "here".$sql_afd."jumlah".$jumlahAfd;
			
			
			$sdate1 = "";
			$sdate2 = "";
			if(isset($_POST["date1"])){
				$_SESSION['date1'] = date("Y-m-d", strtotime($_POST["date1"]));
				unset($_SESSION['date2']);
				$sdate2 = "";
			}
	
			if(isset($_POST["date1"])){
				$_SESSION['date2'] = date("Y-m-d", strtotime($_POST["date1"]));
				if($_SESSION['date2'] == "1970-01-01")
				{
					$_SESSION['date2'] = "";
				}
			}
	
			if(isset($_SESSION['date1'])){
				$sdate1 = $_SESSION['date1'];
			}
			
			if(isset($_SESSION['date1'])){
				$sdate2 = $_SESSION['date1'];
			}
		}
		else{
			header("location:../menu/authoritysecure.php");
		}
	}
	
?>

<script type="text/javascript" src="../datepicker/js/jquery.min.js"></script>
<script type="text/javascript" src="../datepicker/js/pa.js"></script>
<script type="text/javascript" src="../datepicker/datepicker/ui.core.js"></script>
<script type="text/javascript" src="../datepicker/datepicker/ui.datepicker.js"></script>
<link type="text/css" href="../datepicker/datepicker/ui.core.css" rel="stylesheet" />
<link type="text/css" href="../datepicker/datepicker/ui.resizable.css" rel="stylesheet" />
<link type="text/css" href="../datepicker/datepicker/ui.accordion.css" rel="stylesheet" />
<link type="text/css" href="../datepicker/datepicker/ui.dialog.css" rel="stylesheet" />
<link type="text/css" href="../datepicker/datepicker/ui.slider.css" rel="stylesheet" />
<link type="text/css" href="../datepicker/datepicker/ui.tabs.css" rel="stylesheet" />
<link type="text/css" href="../datepicker/datepicker/ui.datepicker.css" rel="stylesheet" />
<link type="text/css" href="../datepicker/datepicker/ui.progressbar.css" rel="stylesheet" />
<link type="text/css" href="../datepicker/datepicker/ui.theme.css" rel="stylesheet" />
<link type="text/css" href="../datepicker/datepicker/demos.css" rel="stylesheet" />

<script type="text/javascript">
$(function() {
	$('#datepicker1,#datepicker2').datepicker({
		  dateFormat: 'yy-mm-dd',
		  changeMonth: true,
		  changeYear: true
	});
});
function change(x)
{
	if(x == 1){
	document.getElementById("Afdeling").style.visibility="visible";
	document.getElementById("NIKMandor").style.visibility="hidden";
	//document.getElementById("NIKPemanen").style.visibility="hidden";
	document.getElementById("NIKMandor").value="kosong";
	//document.getElementById("NIKPemanen").value="kosong";
	document.getElementById("button").style.visibility="visible";
	
	}
	if(x == 2){
	document.getElementById("Afdeling").style.visibility="hidden";
	document.getElementById("NIKMandor").style.visibility="visible";
	//document.getElementById("NIKPemanen").style.visibility="hidden";
	document.getElementById("Afdeling").value="kosong";
	//document.getElementById("NIKPemanen").value="kosong";
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

function formSubmit(x,nik,afd)
{
	if(x == 1){
		document.getElementById("valueAfd_select").value = afd;
		document.getElementById("NIKMandor_select").value = nik;
		document.getElementById("submittanggal1").action = 'doFilter.php';
		document.getElementById("submittanggal1").submit();
		// console.log($('form').serializeArray(),nik,afd);
	}
	if(x ==0)
	{
		document.getElementById("submittanggal1").action = 'WelCetakLHMPanenFilter.php';
		document.getElementById("submittanggal1").submit();
	}
}
</script>

<?php
}
else{
	$_SESSION[err] = "tolong login dulu!".$Job_Code."<br>".$username."<br>".$Emp_Name."<br>".$subID_BA_Afd;
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
body,td,th {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-weight:normal;
}
</style>
<form id="submittanggal1" name="submittanggal1" method="post" action="WelCetakLHMPanenFilter.php">
<table width="978" height="390" border="0" align="center">
  <tr>
    <th height="197" scope="row" align="center">
      <table width="937" border="0" id="setbody2" style="margin-bottom: 0px;">
      <tr>
        <td height="50" colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>CETAK LHM PANEN</strong></span></td>
        <td height="50" colspan="3" align="right" valign="baseline"><input type="submit" name="button" id="button" value="TAMPILKAN" style="visibility:visible; width:120px; height: 30px" onclick="formSubmit(0)"/></td>
      </tr>
      <tr>
        <td height="9" colspan="3" valign="bottom" style="font-size:14px ; border-bottom:solid #000">LOKASI</td>
        <td height="9" colspan="3" valign="bottom" style="font-size:14px ; border-bottom:solid #000">PERIODE</td>
      </tr>
      <tr>
        <td width="169" style="padding-top: 10px;">Company Name</td>
        <td width="11" style="padding-top: 10px;">:</td>
        <td width="349" style="padding-top: 10px;"><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$Comp_Name?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
        <td width="75" style="white-space: nowrap;padding-top: 10px;"><p><span>Harvesting Date</span></p></td>
        <td width="6" style="padding-top: 10px;">:</td>
        <td width="301" style="padding-top: 10px;">
        <input type="text" name="date1" id="datepicker1" class="box_field" value="<?=$sdate1?>" <?php if(isset($_POST["date1"])){ echo "value='$_POST[date1]'"; }?>>
        </td>
      </tr>
      <tr>
        <td>Business Area</td>
        <td>:</td>
        <td><input name="ID_BA2" type="text" id="ID_BA2" value="<?=$subID_BA_Afd?>" style="background-color:#CCC; width:70px; height:25px; font-size:15px" onmousedown="return false"/></td>
        <td ></td>
        <td ></td>
        <td >
        
        </td>
      </tr>
      <tr>
        <td valign="top"> Afdeling</td>
        <td valign="top">:</td>
        <td valign="top" style="font-size:16px">
          <?php
				//Afdeling
				
				if($jumlahAfd > 0 ){
				//$jumlahRecord = $_SESSION['jumlahAfd'];
				$selectoAfd = "<select name=\"Afdeling\" id=\"Afdeling\" style=\"visibility:visible; font-size: 15px; height: 25px \">";
				$optiondefAfd = "<option ".($sesAfdeling=='ALL'?'selected':'')." value=\"ALL\"> ALL </option>";
				echo $selectoAfd.$optionGetAfd.$optiondefAfd;
				for($xAfd = 0; $xAfd < $jumlahAfd; $xAfd++){
					echo "<option ".($sesAfdeling==$ID_Afd[$xAfd]?'selected':'')." value=\"$ID_Afd[$xAfd]\">$ID_Afd[$xAfd]</option>"; 
				}
				$selectcAfd = "</select>";
				echo $selectcAfd;
				}           
				?>
        </td>
        <td colspan="3" >&nbsp;</td>
      </tr>
	  <!-- </form> -->
      <tr>
        <td height="28" colspan="8" valign="bottom" style="border-bottom:solid #000">&nbsp;</td>
      </tr>
      <!-- <form id="doFilter" name="doFilter" method="post" action="doFilter.php"> -->
        <tr>
          <td valign="top">Mandor</td>
          <td valign="top">:</td>
          <td  valign="top"><?php
        //Mandor
		$sql_MD = "SELECT  ID_AFD,
							data_list.NIK_MANDOR,
							data_list.Nama_Mandor, 
							data_list.NIK_KERANI_BUAH, 
							data_list.Nama_Krani,
							SUM(CASE WHEN val.roles<>'KEPALA_KEBUN' THEN 1 ELSE 0 end) Aslap, 
							SUM(CASE WHEN val.roles='KEPALA_KEBUN' THEN 1 ELSE 0 end) Kabun
					FROM 	( select 
								ta.ID_AFD, 
								thrp.NIK_Mandor, 
								f_get_empname(thrp.NIK_Mandor) Nama_Mandor,
								thrp.NIK_KERANI_BUAH, 
								f_get_empname(thrp.NIK_KERANI_BUAH) Nama_Krani,
								thrp.tanggal_rencana
							from 
								t_header_rencana_panen thrp inner join t_detail_rencana_panen tdrp on thrp.id_rencana = tdrp.id_rencana 
							inner join 
								t_blok tb on tdrp.id_ba_afd_blok = tb.id_ba_afd_blok 
							inner join 
								t_afdeling ta on tb.id_ba_afd = ta.id_ba_afd 	
							WHERE 
								ta.ID_BA = '$subID_BA_Afd' 
							and ta.id_afd = nvl (decode ('$sesAfdeling', 'ALL', null, '$sesAfdeling'), ta.id_afd) 
							and to_char(thrp.tanggal_rencana,'YYYY-MM-DD') between '$sdate1' and nvl ('$sdate2', '$sdate1')
							group by NIK_Mandor , ta.ID_AFD, NIK_KERANI_BUAH, thrp.tanggal_rencana ) data_list 
					LEFT JOIN
						t_validasi val ON val.tanggal_ebcc = data_list.tanggal_rencana AND val.nik_mandor = data_list.nik_mandor AND val.NIK_KRANI_BUAH = data_list.NIK_KERANI_BUAH 
					GROUP BY 
						ID_AFD,
						data_list.NIK_MANDOR,
						data_list.Nama_Mandor, 
						data_list.NIK_KERANI_BUAH, 
						data_list.Nama_Krani
					ORDER BY 
						ID_AFD,Nama_Mandor";
        // echo 'pre>'.$sql_MD;die;
        $data_select_mandor = array();
        $data_table_mandor = array();
        $result_MD = oci_parse($con, $sql_MD);
        oci_execute($result_MD, OCI_DEFAULT);
        while (oci_fetch($result_MD)) {	
            $data_select_mandor[oci_result($result_MD, "NAMA_MANDOR")] = oci_result($result_MD, "NIK_MANDOR");
            if(!ISSET($data_table_mandor[oci_result($result_MD, "ID_AFD").oci_result($result_MD, "NIK_MANDOR")]))
            {
	            $data_table_mandor[oci_result($result_MD, "ID_AFD").oci_result($result_MD, "NIK_MANDOR")] = array(
	            						'id'	=>	oci_result($result_MD, "ID_AFD"),
	            						'nik'	=>	oci_result($result_MD, "NIK_MANDOR"),
	            						'name'	=>	oci_result($result_MD, "NAMA_MANDOR"),
	            					   );
            }
           	if(!ISSET($data_table_mandor[oci_result($result_MD, "ID_AFD").oci_result($result_MD, "NIK_MANDOR")]['krani']))
           	{
           		$data_table_mandor[oci_result($result_MD, "ID_AFD").oci_result($result_MD, "NIK_MANDOR")]['krani'] = array();
           	}
            $data_table_mandor[oci_result($result_MD, "ID_AFD").oci_result($result_MD, "NIK_MANDOR")]['krani'][] = array('nik' 	=>	oci_result($result_MD, "NIK_KERANI_BUAH"),
																														 'name' =>	oci_result($result_MD, "NAMA_KRANI"),
																														 'aslap' =>	oci_result($result_MD, "ASLAP"),
																														 'kabun' =>	oci_result($result_MD, "KABUN"),
																														);
        }
        $jumlahMD = oci_num_rows($result_MD);
        
        //echo "mandor".$sql_MD. $jumlahMD;
        
        //echo "here".$sql_MD .$jumlahMD;
        if($jumlahMD >0 ){
        //$jumlahRecord = $_SESSION['jumlahMD'];
        $NIKMandor = $_POST['NIKMandor'];
        $selectoMD = "<select name=\"NIKMandor\" id=\"NIKMandor\" style=\"visibility:visible; font-size: 15px;  height: 25px\">";
        $optiondefMD = "<option value=\"ALL\"> ALL </option>";
        echo $selectoMD.$optiondefMD;
        ksort($data_select_mandor);
        foreach($data_select_mandor as $key => $val){
         echo "<option ".($NIKMandor==$val?'selected':'')." value=\"$val\">$val - $key</option>"; 
        }
        $selectcMD = "</select>";
        echo $selectcMD;
        }
        ?></td>
          <td colspan="3"  valign="top">&nbsp;</td>
          </tr>
        <input name="valueAfd" type="text" id="valueAfd" value="<?=$_SESSION["Afdeling"]?>" onmousedown="return false" style="display:none"/>
        <input name="valueAfd_select" type="text" id="valueAfd_select" value="" style="display:none"/>
        <input name="NIKMandor_select" type="text" id="NIKMandor_select" value="" style="display:none"/>
        <input name="sdate1" type="text" id="sdate1" value="<?=$sdate1?>" onmousedown="return false" style="display:none"/>
        <input name="sdate2" type="text" id="sdate2" value="<?=$sdate2?>" onmousedown="return false" style="display:none"/>
      </form>
      <tr>
        <td>&nbsp;
          <?php
                
				//echo  $sql_MD. $jumlahMD;
				?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="3">&nbsp;</td>
        </tr>
    </table>
    <?php 
    if($jumlahMD >0 ){ ?>
    <table border="1" width="100%">
      <tr>
      	<td style="background-color: #CCC;" rowspan="2" align="center"><b><small>AFDELING</small></b></td>
      	<td style="background-color: #CCC;" rowspan="2" align="center"><b><small>MANDOR</small></b></td>
      	<td style="background-color: #CCC;" rowspan="2" align="center"><b><small>KRANI BUAH</small></b></td>
      	<td style="background-color: #CCC;" colspan="2" align="center"><b><small>VALIDASI</small></b></td>
      	<td style="background-color: #CCC;" rowspan="2" align="center"><b><small>CETAK LHM</small></b></td>
  	  </tr>
      <tr>
      	<td style="background-color: #CCC;" align="center"><b><small>&nbsp;ASLAP&nbsp;</small></b></td>
      	<td style="background-color: #CCC;" align="center"><b><small>&nbsp;KABUN&nbsp;</small></b></td>
  	  </tr>
  	  <?php
        foreach($data_table_mandor as $key => $val){
         $id = $val['id'];
         $nik_mandor = $val['nik'];
         $name_mandor = $val['name'];
         $count = COUNT($val['krani']);
         if($NIKMandor=='ALL' || $NIKMandor==$nik_mandor)
         {
	         echo "<tr>"; 
	         echo "<td rowspan='$count' align='center'><small>$id</small></td>"; 
	         echo "<td rowspan='$count'><small>&nbsp;$name_mandor - $nik_mandor&nbsp;</small></td>"; 
	         $nik = $val['krani'][0]['nik'];
	         $name = $val['krani'][0]['name'];
	         $aslap = $val['krani'][0]['aslap'];
			 $kabun = $val['krani'][0]['kabun'];
			 $cetak_status = 0;
	         foreach ($val['krani'] as $key => $check) 
	         {
				 if($check['kabun']!=3)
				 {
					$cetak_status++;
				 }
			 }
	         echo "<td style='padding-top: 7px;padding-bottom: 7px;'><small>&nbsp;$name - $nik&nbsp;</small></td>"; 
	         echo "<td align='center'>$aslap</td>"; 
	         echo "<td align='center'>$kabun</td>"; 
	        //  echo "<td rowspan='$count' align='center'><i style='color:red'>&#10006;</i></td>"; 
	        //  echo "<td rowspan='$count' align='center'><i style='color:green'>&#10004;</i></td>"; 
			 if($cetak_status==0)
			 {
				echo "<td rowspan='$count' align='center'><small><input type='button' value='CETAK LHM' style='visibility:visible; width:100px; height: 20px;margin: 5px;' onclick='formSubmit(1,`$nik_mandor`,`$id`)'/></small></td>"; 
			 }
			 else 
			 {
				echo "<td rowspan='$count' align='center'><small style='color:red;'>Belum Bisa Dilakukan</small></td>"; 
			 }
	         echo "</tr>"; 
	         foreach ($val['krani'] as $key => $val) 
	         {
	         	if($key!=0)
	         	{
			         echo "<tr>"; 
			         $nik = $val['nik'];
			         $name = $val['name'];
					 $aslap = $val['aslap'];
					 $kabun = $val['kabun'];
	         		 echo "<td style='padding-top: 7px;padding-bottom: 7px;'><small>&nbsp;$name - $nik&nbsp;</small></td>"; 
					 echo "<td align='center'>$aslap</td>"; 
					 echo "<td align='center'>$kabun</td>"; 
			         echo "</tr>"; 
	         	}
	         }
         }
        } 
  	  ?>
    </table>
	<?php }?>
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
	</form>
