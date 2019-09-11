<?php
session_start();
include("../include/Header.php");
?>
<!-- LIBRARY JQUERY -->
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery.min.js"></script>
<!-- END LIBRARY JQUERY -->
<!-- TOKEN INPUT 
<script type="text/javascript" src="jquery.tokeninput.js"></script>
-->

<script type="text/javascript">
$(document).ready(function() {
	var bacode = $("#ID_BAlabel").val();
	var q =  $('#NIK_Gandeng_BARU').find(":selected").text();
	$("#NIK_Gandeng_BARU").autocomplete("userPemanen.php?bacode="+bacode+"&q="+q, {
		selectFirst: true
	});
});
</script>

<link rel="stylesheet" type="text/css" href="jquery.autocomplete.css" />
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery.autocomplete.js"></script>

<link rel="stylesheet" href="token-input.css" type="text/css" />
<!-- END TOKEN INPUT -->
<?php
if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name'])){	
$Job_Code = $_SESSION['Job_Code'];
$username = $_SESSION['NIK'];	
$Emp_Name = $_SESSION['Name'];
//$Jenis_Login = $_SESSION['Jenis_Login'];
$Comp_Name = $_SESSION['Comp_Name'];
$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
$Date = $_SESSION['Date'];
$ID_Group_BA = $_SESSION['ID_Group_BA'];
$subID_CC = $_SESSION['subID_CC'];

	
	if($username == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:login.php");
	}
	else{
		include("../config/SQL_function.php");
		//require_once __DIR__ . '/db_config.php'; 
		//$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
		include("../config/db_connect.php");
		$con = connect();

		if(isset($_POST["editID_RENCANA"])){
		$_SESSION["editID_RENCANA"] = $_POST["editID_RENCANA"];
		}
		
		if(isset($_SESSION["editID_RENCANA"])){
		$ID_RENCANA = $_SESSION["editID_RENCANA"];
		}
		
		if(isset($_POST["editID_AFD"])){
		$_SESSION["editID_AFD"] = $_POST["editID_AFD"];
		}
		
		if(isset($_SESSION["editID_AFD"])){
		$ID_AFD = $_SESSION["editID_AFD"];
		}
		
		if(isset($_POST["editID_BLOK"])){
		$_SESSION["editID_BLOK"] = $_POST["editID_BLOK"];
		}
		
		if(isset($_SESSION["editID_BLOK"])){
		$ID_BLOK = $_SESSION["editID_BLOK"];
		}
		
		$sql_t_hpk  = "
			select thrp.id_rencana, tb.id_blok, ta.id_afd, NIK_Pemanen, f_get_empname(NIK_Pemanen) Nama_Pemanen, 
			NIK_Mandor, f_get_empname(NIK_Mandor) Nama_Mandor, 
			NIK_Kerani_buah, f_get_empname(NIK_Kerani_buah) Nama_Kerani_buah, 
			luasan_panen, no_rekap_bcc, nik_gandeng 
			from 
			t_header_rencana_panen thrp, 
			t_detail_rencana_panen tdrp, 
			t_blok tb, t_afdeling ta, 
			t_detail_gandeng tdg, 
			t_hasilpanen_kualtas thk,
			t_bussinessarea tba
			WHERE 
			thrp.id_rencana = tdrp.id_rencana and 
			tdrp.id_ba_afd_blok = tb.id_ba_afd_blok and 
			tb.id_ba_afd = ta.id_ba_afd and 
			tdrp.id_rencana = tdg.id_rencana and 
			tdrp.id_rencana = thk.id_rencana and 
			ta.id_ba = tba.id_ba and
			thrp.status_gandeng = 'YES' and 
			tdrp.luasan_panen > 0 and 
			tba.id_cc = '$subID_CC' and
			ta.ID_BA = '$subID_BA_Afd' and 
			thrp.id_rencana = '$ID_RENCANA' and
			tb.id_blok = '$ID_BLOK' and
			ta.id_afd = '$ID_AFD'
			group by thrp.id_rencana, tb.id_blok, ta.id_afd, NIK_PEMANEN, NIK_Mandor, NIK_Kerani_buah, NIK_Kerani_buah, luasan_panen, no_rekap_bcc, nik_gandeng 
			order by Nama_Pemanen 
							";
							
			$_SESSION["sql_koreksi_aap"] = $sql_t_hpk;
			$result_t_hpk = oci_parse($con, $sql_t_hpk);
			oci_execute($result_t_hpk, OCI_DEFAULT);
			
			//echo $sql_t_hpk;
			
			while (oci_fetch($result_t_hpk)) {	
				$viewID_Rencana = oci_result($result_t_hpk, "ID_RENCANA");
				$viewID_AFD = oci_result($result_t_hpk, "ID_AFD");
				$viewID_BLOK = oci_result($result_t_hpk, "ID_BLOK");
				$viewNIK_GANDENG = oci_result($result_t_hpk, "NIK_GANDENG");
				$viewLUASAN_PANEN = number_format((float)oci_result($result_t_hpk, "LUASAN_PANEN"), 2, '.', '');
				$viewNIK_PEMANEN = oci_result($result_t_hpk, "NIK_PEMANEN");
				$viewNAMA_PEMANEN = oci_result($result_t_hpk, "NAMA_PEMANEN");
				$viewNIK_KERANI_BUAH = oci_result($result_t_hpk, "NIK_KERANI_BUAH");
				$viewNAMA_KERANI_BUAH = oci_result($result_t_hpk, "NAMA_KERANI_BUAH");
				$viewNIK_MANDOR = oci_result($result_t_hpk, "NIK_MANDOR");
				$viewNAMA_MANDOR = oci_result($result_t_hpk, "NAMA_MANDOR");
				$viewNO_REKAP_BCC = oci_result($result_t_hpk, "NO_REKAP_BCC");
			}
		
		$sql_t_BCC_table = "select * from t_detail_gandeng where id_rencana = '$ID_RENCANA' order by ID_GANDENG";
		$result_t_BCC_table = oci_parse($con, $sql_t_BCC_table);
		oci_execute($result_t_BCC_table, OCI_DEFAULT);
		while(oci_fetch($result_t_BCC_table)){
			$ID_GANDENG[] 	= oci_result($result_t_BCC_table, "ID_GANDENG");
			$NIK_GANDENG[] 	= oci_result($result_t_BCC_table, "NIK_GANDENG");
		}
		$roweffec_DETAILGANDENG = oci_num_rows($result_t_BCC_table);
		
		//ADD
		$EditLuasan = "style='width: 50px; height:25px; font-size:15px' onmousedown= 'return true'";
		$displayAdd = "inline";		
		$displayDel = "inline";	
		$displayformNewAAP = "none";
		if(isset($_SESSION['AddAAP']) && isset($_SESSION['NewIDGandeng'])){
		$AddAAP 		= $_SESSION['AddAAP'];
		$NewIDGandeng 	= $_SESSION['NewIDGandeng'];
		//echo "AddAAP value ". $_SESSION['AddAAP'];
			if($AddAAP == TRUE)
			{
				$EditLuasan = "style='background-color:#CCC; width: 50px; height:25px; font-size:15px' onmousedown= 'return false'";
				$displayAdd = "none";
				$displayDel = "none";	
				$displayformNewAAP = "inline";	
				unset($_SESSION['AddAAP']);
			}
			else{
				$EditLuasan = "style='width: 50px; height:25px; font-size:15px' onmousedown= 'return true'";
				$displayAdd = "inline";
				$displayDel = "inline";
				$displayformNewAAP = "none";
			}
		}	
	}
	
?>

<script type="text/javascript">
function formSubmit(x)
{
	document.getElementById('NIK_Pemanen').value = x;
	document.getElementById("FormPemanen").submit();
}


function formSubmitvalue123(x,y)
{
	document.getElementById('UpdateAAP').value = "";
	document.getElementById('AddAAP').value = "";
	document.getElementById('DelStat').value = "";
	if(x == 1){
		document.getElementById('UpdateAAP').value = "TRUE";
	}
	if(x == 2){
		document.getElementById('AddAAP').value = "TRUE";
	}
	if(x == 3){
		document.getElementById('DelStat').value = "TRUE";
		document.getElementById('DelLine').value = y;
	}
	
	document.getElementById("FormEditDetailGandeng").submit();
}



function formSubmitvalue4(x)
{
	document.getElementById('NewAAPSubmit').value = "";
	if(x == 4){
		//alert (x);
		document.getElementById('NewAAPSubmit').value = "TRUE";
	}
	document.getElementById("FormEditDetailGandeng").submit();
}

function formAAPSubmit()
{
	document.getElementById("formNewAAPSubmit").submit();
}

function send(x,y)
{
	if(x == 0){
		$(document).ready(function() {
		var bacode = $("#ID_BAlabel").val();
		var q =  $('#NewNIK_GANDENG'+y).find(":selected").text();
		$("#NewNIK_GANDENG"+y).autocomplete("userPemanen.php?bacode="+bacode+"&q="+q, {
			selectFirst: true
		});
		});
	}

	if(x == 1){	
	var str2 = document.getElementById('NewNIK_GANDENG'+y).value;
	var n=str2.split(":"); 
	//alert (str1);
	document.getElementById('NewNIK_GANDENG'+y).value= n[0];
	}
	
	if(x == 2){
	var str1 = document.getElementById('NIK_Gandeng_BARU').value;
	var n=str1.split(":"); 
	//alert (str1);
	document.getElementById('NIK_Gandeng_BARU').value= n[0];
	}
	
	
}

</script>
<link href="../css/style.css" rel="stylesheet" type="text/css" media="all" />

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
.style1 {
	color: #FF0000;
 	font-size:16px;
	font-weight:normal;
}
body,td,th {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-weight:normal;
}
</style>
<table width="1079" height="390" border="0" align="center" id="setbody2">
  <tr>
    <th height="197" scope="row" align="center"><table width="937" border="0" id="setbody2">
    <!--<table width="1031" border="0" id="setbody2">-->
    <tr>
        <td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>KOREKSI DATA AKTIVITAS AKHIR PANEN</strong></span></td>
      </tr>
      <tr style="border-bottom:solid #000">
        <td colspan="2" align="center">
        <table width="995" border="0" id="setbody2">
        
          <tr>
            <td width="125">Company Name</td>
            <td width="462"><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$Comp_Name?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
            <td>Nama Mandor</td>
            <td><input name="Nama_Mandorlabel" type="text" id="Nama_Mandorlabel" value="<?=$viewNAMA_MANDOR?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
          </tr>
          <tr>
            <td>Business Area</td>
            <td><input name="ID_BAlabel" type="text" id="ID_BAlabel" value="<?=$subID_BA_Afd?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/></td>
            <td width="152">Nama Kerani Buah</td>
            <td width="343"><input name="Krani_Buahlabel" type="text" id="Krani_Buahlabel" value="<?=$viewNAMA_KERANI_BUAH?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/>
            </td>
		  </tr>
          <tr>
            <td>Afdeling Panen</td>
            <td style="font-size:16px"><input name="AFDlabel" type="text" id="AFDlabel" value="<?=$ID_AFD?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/></td>
            <td>Blok Panen</td>
            <td><input name="ID_BLOKlabel" type="text" id="ID_BLOKlabel" value="<?=$ID_BLOK?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
          </tr>
        </table>
        </td>
      </tr>
      
      <form id="FormEditDetailGandeng" name="FormEditDetailGandeng" method="post" action="doAdUpDel.php">
      
      <tr>
        
        <td colspan="2" align="center" style="border-bottom:solid #000">
          <table width="991" border="0" id="setbody2">
            <tr>
              <td width="128">&nbsp;</td>
              <td width="340">&nbsp;</td>
              <td width="117">Data Pemanen</td>
              <td width="152">&nbsp;</td>
              <td width="341">&nbsp;</td>
            </tr>
            <tr>
              <td valign="top">Business Area</td>
              <td valign="top"><input name="ID_BAlabel" type="text" id="ID_BAlabel" value="<?=$subID_BA_Afd?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/></td>
              <td>&nbsp;</td>
              <td valign="top">Nama Pemanen</td>
              <td valign="top">
                <input name="Nama_Pemanen" type="text" id="Nama_Pemanen" value="<?=$viewNAMA_PEMANEN?>" style="background-color:#CCC;  width:300px; height:25px; font-size:15px" onmousedown="return false"/>
              </td>
            </tr>
            <tr>
              <td>Afdeling</td>
              <td><span style="font-size:16px">
                <input name="Afd_Pemanen" type="text" id="Afd_Pemanen" value="<?=$ID_AFD?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/>
              </span></td>
              <td>&nbsp;</td>
              <td>NIK Pemanen</td>
              <td>
                <input name="NIK_Pemanen" type="text" id="NIK_Pemanen" value="<?=$viewNIK_PEMANEN?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/>              </td>
            </tr>
			
			<tr>
              <td>Luasan Panen</td>
              <td>	<span style="font-size:16px">
					<input name="Luasan_Panen" type="text" id="Luasan_Panen" value="<?=$viewLUASAN_PANEN?>" <?=$EditLuasan?>/>
              		</span>
              Ha</td>
            </tr>
            <tr>
              <td colspan="5" align="right"></td>
            </tr>
          </table>        </td>
      </tr>
      <tr style="border-bottom:solid #000">
        
        <td colspan="2" align="center">
          <table width="992" border="0" id="setbody2">
            <tr bgcolor="#9CC346">
              <td width="55" align="center" style="font-size:14px" id="bordertable">No.</td>
              <!--<td width="100" align="center" style="font-size:14px; border-bottom:ridge">ID Detail Gandeng</td>
              <td width="300" align="center" style="font-size:14px; border-bottom:ridge">ID Rencana</td>-->
              <td width="300" align="center" style="font-size:14px" id="bordertable">NIK Gandeng</td>
			  
              <?php 
			  if($displayformNewAAP == "none")
              echo "
			  <td width=\"300\" align=\"center\" style=\"font-size:14px\" id=\"bordertable\">NIK Gandeng Baru</td>
			  <td width=\"300\" align=\"center\" valign=\"middle\" style=\"font-size:14px\" id=\"bordertable\">Delete</td>"
			  ?>
            </tr>
            <?php

for($xJAN = 0; $xJAN <  $roweffec_DETAILGANDENG ; $xJAN++){
	$no = $xJAN +1;
	
	if(($xJAN % 2) == 0){
		$bg = "#F0F3EC";
	}
	else{
		$bg = "#DEE7D2";
	}
	
echo "<tr style=\"font-size:14px\" bgcolor=$bg >";
/*echo "<td>$no</td>
            <td>$ID_GANDENG[$xJAN]</td>
			<td>$ID_RENCANA</td>
            <td align=\"center\">$NIK_GANDENG[$xJAN]<input name=\"NIK_GANDENG$xJAN\" value=\"$NIK_GANDENG[$xJAN]\" style=\"display:none\">
			<input name=\"ID_GANDENG$xJAN\" type=\"text\" id=\"ID_GANDENG$xJAN\" value=\"$ID_GANDENG[$xJAN]\" style=\"display:none\"/>
			";*/
echo "<td align=\"center\" id=\"bordertable\">$no</td>
            <td align=\"center\" id=\"bordertable\">$NIK_GANDENG[$xJAN]<input name=\"NIK_GANDENG$xJAN\" value=\"$NIK_GANDENG[$xJAN]\" style=\"display:none\">
			<input name=\"ID_GANDENG$xJAN\" type=\"text\" id=\"ID_GANDENG$xJAN\" value=\"$ID_GANDENG[$xJAN]\" style=\"display:none\"/>
            
			";
if($displayDel== "inline"){
echo "		
			<td align=\"center\" id=\"bordertable\">
			<input name=\"NewNIK_GANDENG$xJAN\" type=\"text\" id=\"NewNIK_GANDENG$xJAN\" value=\"$NIK_GANDENG[$xJAN]\" style=\"width: 290px; height:25px; font-size:15px\" onchange=\"send(1,$xJAN)\" onclick=\"send(0,$xJAN)\"/>
			</td>
			
			<input name=\"DelAAP_ID$xJAN\" type=\"text\" id=\"DelAAP_ID$xJAN\" value=\"$ID_GANDENG[$xJAN]\" style=\"display:none\"/>
			<td align=\"center\" id=\"bordertable\">
					<input type=\"button\" name=\"buttonDel$xJAN\" id=\"buttonDel$xJAN\" value=\"Delete (-)\" onclick=\"formSubmitvalue123(3,$xJAN)\"/>
			</td>
			";
}

}
echo "
	</tr>
	<input name=\"Del_IDRencana\" type=\"text\" id=\"Del_IDRencana\" value=\"$ID_RENCANA\" style=\"display:none\"/>
	<input name=\"DelStat\" type=\"text\" id=\"DelStat\" value=\"\" style=\"display:none\"/>
	<input name=\"DelLine\" type=\"text\" id=\"DelLine\" value=\"\" style=\"display:none\"/>
	
	<input name=\"AddAAP\" type=\"text\" id=\"AddAAP\" value=\"\" style=\"display:none\"/>
	<tr>
	  <td colspan=\"6\" align=\"left\" style=\"font-size:14px\">	
		<input type=\"button\" name=\"buttonAdd\" id=\"buttonAdd\" value=\"Add (+)\" style=\"width:70px; height:30px; display:$displayAdd\" onclick=\"formSubmitvalue123(2,0)\"/>
	  </td>
	</tr>
";

?>
          </table>
          <input name="roweffec_DETAILGANDENG" type="text" id="roweffec_DETAILGANDENG" value="<?=$roweffec_DETAILGANDENG?>" style="display:none"/>
          <input name="ID_RENCANA" type="text" id="ID_RENCANA" value="<?=$ID_RENCANA?>" style="display:none"/>
		  <input name="NO_REKAP_BCC" type="text" id="NO_REKAP_BCC" value="<?=$viewNO_REKAP_BCC?>" style="display:none"/>
        </td>
      </tr>
      
      <?php
	  if($displayformNewAAP == "inline"){
	  ?>
      
      <tr>
      <td colspan="2">

      <input name="NewAAPSubmit" type="text" id="NewAAPSubmit" value="" style="display:none"/>
      <table width="992" align="center" border="0" id="setbody2"> 
        <tr style="display:none"> 
          <td>ID Detail Gandeng</td>
          <td>:</td>
          <td>
            <input name="ID_Detail_Gandeng_BARU" type="text" id="ID_Detail_Gandeng_BARU" value="<?=$NewIDGandeng?>" style="background-color:#CCC;  width:100px; height:25px; font-size:15px" onmousedown="return false"/>
          </td>
        </tr>
        <tr style="display:none">
          <td>ID Rencana</td>
          <td>:</td>
          <td>
            <input name="ID_Rencana_IGBARU" type="text" id="ID_Rencana_IGBARU" value="<?=$ID_RENCANA?>" style="background-color:#CCC;  width:400px; height:25px; font-size:15px" onmousedown="return false"/>
          </td>
        </tr>
        <tr>
          <td>NIK Gandeng Baru</td>
          <td>:</td>
          <td>
          <input name="ID_BA_Gandeng" type="text" id="ID_BA_Gandeng" value="<?=$subID_BA_Afd?>" style="display:none"/>
          
          <input name="NIK_Gandeng_BARU" type="text" id="NIK_Gandeng_BARU" value="" onchange="send(2,'X')" style="width:400px; height:25px; font-size:15px"/>

          <input type="submit" name="saveNewAAP" id="saveNewAAP" value="SIMPAN" style="width:140px; height: 30px" onclick="formSubmitvalue4(4)"/>
          <a href="KoreksiAAPSelect.php">
          <input type="button" name="cancelNewAAP" id="cancelNewAAP" value="BATAL" style="width:100px; height: 30px"/>
          </a>
          </td>
        </tr>

      </table> 
      
      </td>
      </tr>
      <?php
	  }
	  ?>
    
	<!--CLOSE Form ADD-->
   

      
      <tr>
        <td align="center" colspan="5">
        <input name="UpdateAAP" type="text" id="UpdateAAP" value="" style="display:none"/>
        <!--<a href="javascript:;" onclick="javascript: document.getElementById('FormEditDetailGandeng') .submit()">-->
        <input type="submit" name="button" id="button" value="SIMPAN" style="width:120px; height: 30px; display:<?=$displayAdd?>" onclick="formSubmitvalue123(1,0)"/>
        <!--</a>--></td>
      </tr>
      </form>
       <tr>
        <td colspan="2" align="center"><span class="style1">Pastikan koreksi data Anda telah mendapatkan persutujan dari EM atau KABUN !!</span></td>
      </tr>
    <!--</table>-->

    
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

<?php
}
else{
	$_SESSION[err] = "tolong login dulu!".$Job_Code."<br>".$username."<br>".$Emp_Name."<br>".$subID_BA_Afd;
	header("location:../index.php");
}
?>