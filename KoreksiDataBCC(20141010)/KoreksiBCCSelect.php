<?php
session_start();
include("../include/Header.php");
if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name'])){	
$Job_Code = $_SESSION['Job_Code'];
$username = $_SESSION['NIK'];	
$Emp_Name = $_SESSION['Name'];
//$Jenis_Login = $_SESSION['Jenis_Login'];
$Comp_Name = $_SESSION['Comp_Name'];
$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
$Date = $_SESSION['Date'];
$ID_Group_BA = $_SESSION['ID_Group_BA'];
	
	if($username == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:login.php");
	}
	else{
		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();

		if(isset($_POST["editNO_BCC"])){
		$_SESSION["editNO_BCC"] = $_POST["editNO_BCC"];
		}
		
		if(isset($_SESSION["editNO_BCC"])){
		$NO_BCC = $_SESSION["editNO_BCC"];
		}

		$sql_t_BCC = "
		select thrp.tanggal_rencana tanggal, thrp.id_rencana id_rencana,
		tba.id_cc AS CC,
        tba.id_ba AS BA,
       ta.id_afd AS AFD,
       tb.id_blok as ID_BLOK,
       tb.blok_name as BLOK_NAME,
	   thp.no_bcc,
       thp.no_rekap_bcc,
       thrp.nik_pemanen,
       f_get_empname (thrp.nik_pemanen) nama_pemanen,
       thrp.nik_mandor,
       f_get_empname (thrp.nik_mandor) nama_mandor,
      tkp.nama_kualitas, thpk.ID_BCC_KUALITAS as ID_BCC_KUALITAS, thpk.qty
  from t_header_rencana_panen thrp
       inner join t_detail_rencana_panen tdrp
          on thrp.id_rencana = tdrp.id_rencana
       inner join t_hasil_panen thp
		  on tdrp.id_rencana = thp.id_rencana
		  and tdrp.no_rekap_bcc = thp.no_rekap_bcc
       inner join t_blok tb
          on tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
       inner join t_afdeling ta
          on tb.id_ba_afd = ta.id_ba_afd
       inner join t_bussinessarea tba
          on ta.id_ba = tba.id_ba
     inner join t_hasilpanen_kualtas thpk
          on thp.no_bcc = thpk.id_bcc
      inner join  t_kualitas_panen tkp
        on thpk.id_kualitas=tkp.id_kualitas
where     thp.no_bcc = '$NO_BCC'
		";
		//echo $sql_t_BCC; die;
		$result_t_BCC = oci_parse($con, $sql_t_BCC);
		oci_execute($result_t_BCC, OCI_DEFAULT);
		while(oci_fetch($result_t_BCC)){
			$ID_RENCANA 		= oci_result($result_t_BCC, "ID_RENCANA");
			$TANGGAL_RENCANA 		= oci_result($result_t_BCC, "TANGGAL");
			$aNO_BCC 				= oci_result($result_t_BCC, "NO_BCC");
			$CC				= oci_result($result_t_BCC, "CC");
			$BA 				= oci_result($result_t_BCC, "BA");
			$AFD 				= oci_result($result_t_BCC, "AFD");
			$ID_BLOK 				= oci_result($result_t_BCC, "ID_BLOK");
			$BLOK_NAME 				= oci_result($result_t_BCC, "BLOK_NAME");
			$NAMA_PEMANEN 		= oci_result($result_t_BCC, "NAMA_PEMANEN");
			$NAMA_MANDOR 			= oci_result($result_t_BCC, "NAMA_MANDOR");
			$NIK_PEMANEN 		= oci_result($result_t_BCC, "NIK_PEMANEN");
			$NIK_MANDOR 			= oci_result($result_t_BCC, "NIK_MANDOR");
			$NO_REKAP 			= oci_result($result_t_BCC, "NO_REKAP_BCC");
		}
		
		$sql_t_BCC_table = "SELECT H.ID_BCC_KUALITAS ,K.ID_KUALITAS, K.NAMA_KUALITAS, H.QTY  FROM  t_kualitas_panen K,
        t_hasilpanen_kualtas H  
        WHERE  H.ID_BCC(+)='$NO_BCC'
            AND K.ID_KUALITAS = H.ID_KUALITAS(+)
            ORDER BY Group_kualitas,ID_KUALITAS ASC";
		$result_t_BCC_table = oci_parse($con, $sql_t_BCC_table);
		oci_execute($result_t_BCC_table, OCI_DEFAULT);
		while(oci_fetch($result_t_BCC_table)){
			$NAMA_KUALITAS[] 	= oci_result($result_t_BCC_table, "NAMA_KUALITAS");
			$QTY[] 				= oci_result($result_t_BCC_table, "QTY");
			$ID_BCC_KUALITAS[] 	= oci_result($result_t_BCC_table, "ID_BCC_KUALITAS");
			$ID_Kualitas[]		= oci_result($result_t_BCC_table, "ID_KUALITAS");
		}
		$roweffec_BCC = oci_num_rows($result_t_BCC_table);
		

		$sql_t_CC = "select COMP_NAME from t_companycode WHERE ID_CC = '$CC'";
		
		$result_t_CC = oci_parse($con, $sql_t_CC);
		oci_execute($result_t_CC, OCI_DEFAULT);
		while(oci_fetch($result_t_CC)){
			$COMP_NAME			= oci_result($result_t_CC, "COMP_NAME");
		}
		$roweffec_CC = oci_num_rows($result_t_CC);

		if(isset($_POST['BA'])){
			$_SESSION['BA'] = $_POST['BA'];
		}
		
		if(isset($_SESSION['BA'])){
			$ses_BA = $_SESSION['BA'];
			if($ses_BA  == ""){
				$sql_BA = "select * from t_BussinessArea tba
				inner join t_CompanyCode tcc on tba.id_cc = tcc.id_cc
				inner join t_alternate_ba_group tabg on tba.id_ba = tabg.id_ba
				where tabg.id_group_ba in (select id_group_ba from t_bussinessarea where id_ba = $subID_BA_Afd)
				 order by tba.id_ba";
				$sql_t_Emp_All  = "SELECT * from t_employee WHERE JOB_CODE = 'PEMANEN'";
				$optionBA = "";
			}
			else{
				$sql_BA = "select * from t_BussinessArea tba
				inner join t_CompanyCode tcc on tba.id_cc = tcc.id_cc 
				inner join t_alternate_ba_group tabg on tba.id_ba = tabg.id_ba
				where tabg.id_group_ba in (select id_group_ba from t_bussinessarea where id_ba = $subID_BA_Afd)
				AND tba.ID_BA != '$ses_BA' order by tba.id_ba";
				
				$optionBA = "<option value=\"$ses_BA\" selected=\"selected\">$ses_BA</option>";
				
				$sql_t_Emp_All  = "select * from t_employee te
				inner join t_afdeling ta on te.id_ba_afd = ta.id_ba_afd
				where ta.id_ba = '$ses_BA' and te.job_code = 'PEMANEN'";	
			}
		}
		else{
			$sql_BA = "select * from t_BussinessArea tba
				inner join t_CompanyCode tcc on tba.id_cc = tcc.id_cc 
				inner join t_alternate_ba_group tabg on tba.id_ba = tabg.id_ba
				where tabg.id_group_ba in (select id_group_ba from t_bussinessarea where id_ba = $subID_BA_Afd)
				order by tba.id_ba";
			$sql_t_Emp_All  = "SELECT * from t_employee WHERE JOB_CODE = 'PEMANEN'";
			$optionBA = "";
		}		
		
		//echo $sql_BA;
		$result_t_Emp_All = oci_parse($con, $sql_t_Emp_All);
		oci_execute($result_t_Emp_All, OCI_DEFAULT);
		
		while (oci_fetch($result_t_Emp_All)) {	
			$NIK_Pemanen_All[] = oci_result($result_t_Emp_All, "NIK");
			$Nama_Pemanen_All[] = oci_result($result_t_Emp_All, "EMP_NAME");
			$ID_BA_AFD_Pemanen_All[] = oci_result($result_t_Emp_All, "ID_BA_AFD");
		}
		$roweffec_Emp_All = oci_num_rows($result_t_Emp_All);

		$result_BA = oci_parse($con, $sql_BA);
		oci_execute($result_BA, OCI_DEFAULT);
		while(oci_fetch($result_BA)){
			$ID_BA[]		= oci_result($result_BA, "ID_BA");	
		}
		$roweffec_BA = oci_num_rows($result_BA);


		if(isset($_POST['NIK_Pemanen'])){
			$_SESSION['NIK_Pemanen'] = $_POST['NIK_Pemanen'];
			//echo "Pemanenpost";
		}
				//echo "here".$_POST['NIK_Pemanen'];
		if(isset($_SESSION['NIK_Pemanen'])){
			
			$ses_NIK_Pemanen = $_SESSION['NIK_Pemanen'];
			//echo "Pemanen". $_SESSION['NIK_Pemanen'];
			if($ses_NIK_Pemanen  == ""){
				$sql_t_Emp  = "select te.nik, te.emp_name, ta.id_ba, ta.id_afd
				from t_employee te inner join t_afdeling ta on te.id_ba_afd = ta.id_ba_afd WHERE NIK = '$NIK_PEMANEN'";	
				//echo "seskosong";
			}
			else{
				$sql_t_Emp  = "select te.nik, te.emp_name, ta.id_ba, ta.id_afd
				from t_employee te inner join t_afdeling ta on te.id_ba_afd = ta.id_ba_afd WHERE NIK = '$ses_NIK_Pemanen'";	
				//echo "sesada".$sql_t_Emp;
			}
		}
		else{
			$sql_t_Emp  = "select te.nik, te.emp_name, ta.id_ba, ta.id_afd
			from t_employee te inner join t_afdeling ta on te.id_ba_afd = ta.id_ba_afd WHERE NIK = '$NIK_PEMANEN'";	
			//echo "sesnotsend".$sql_t_Emp;
		}
		$result_t_Emp = oci_parse($con, $sql_t_Emp);
		oci_execute($result_t_Emp, OCI_DEFAULT);
		
		while (oci_fetch($result_t_Emp)) {	
			$NIK_Pemanen = oci_result($result_t_Emp, "NIK");
			$Nama_Pemanen = oci_result($result_t_Emp, "EMP_NAME");
			//$ID_BA_Pemanen = oci_result($result_t_Emp, "ID_BA");
			$Afd_Pemanen = oci_result($result_t_Emp, "ID_AFD");
		}
		$roweffec_Emp = oci_num_rows($result_t_Emp);
	}
	
?>

<script type="text/javascript">
function formSubmit(x)
{
	document.getElementById('NIK_Pemanen').value = x;
	document.getElementById("FormPemanen").submit();
}

function formSubmitvalue()
{
	//document.getElementById('NIK_Pemanen').value = x;
	//alert('Pastikan koreksi data Anda telah mendapatkan persutujan dari EM atau KABUN !!!');
	document.getElementById("FormEditBCC").submit();
}

function showListPemanen() {
	var afd = document.getElementById('AFDlabel').value;
	var ba = document.getElementById('ID_BAlabel').value;
	var tgl = document.getElementById('datepicker').value;
	
	var afdeling = ba+afd;
	//var baris = row;
	if (afdeling != "0" && tgl != "")
		sList = window.open("popupPemanen.php?afdeling="+afdeling+"&tgl_panen="+tgl+"", "Daftar_Dokumen", "width=800,height=500");
	else if (tgl == "")
		alert("Pilih tanggal panen terlebih dahulu");
	else
		sList = window.open("popupPemanen.php?afdeling="+afdeling+"&tgl_panen="+tgl+"", "Daftar_Dokumen", "width=800,height=500");
}

function showListMandor() {
	var afd = document.getElementById('AFDlabel').value;
	var ba = document.getElementById('ID_BAlabel').value;
	var tgl = document.getElementById('datepicker').value;
	
	var afdeling = ba+afd;
	if (afdeling != "0" && tgl != "")
		sList = window.open("popupMandor.php?afdeling="+afdeling+"&tgl_panen="+tgl+"", "Daftar_Dokumen", "width=800,height=500");
	else if (tgl == "")
		alert("Pilih tanggal panen terlebih dahulu");
	else
		sList = window.open("popupMandor.php?afdeling="+afdeling+"&tgl_panen="+tgl+"", "Daftar_Dokumen", "width=800,height=500");
}

</script>
<link href="../css/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"> </script>
<script type="text/javascript" src="../js/script.js"></script>

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
	font-weight: bold;
}
body,td,th {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-weight:normal;
}
</style>
<form id="FormEditBCC" name="FormEditBCC" method="post" action="doUpdateBCCPemanen.php">
<table width="1151" height="390" border="0" align="center">

  <!--<tr bgcolor="#C4D59E">-->
  <tr>
    <th height="197" scope="row" align="center"><table width="937" border="0" id="setbody2">
      <tr>
		<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>KOREKSI DATA BCC</strong></span></td>
      </tr>
  
  <tr>
    <th height="197" scope="row" align="center"><table border="0" style="border:solid #556A29">
      <tr>
        <td align="center"><table width="995" border="0">
          <tr>
            <td width="130" height="29" valign="top">Company Name</td>
            <td width="7" height="29" valign="top">:</td>
            <td width="355" align="left" valign="top"><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$COMP_NAME?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
            <td width="130" height="29" valign="top">Tanggal Panen</td>
            <td width="7" height="29" valign="top">:</td>
            <td width="355" align="left" valign="top"><input type="text" name="datepicker" id="datepicker" value="<?=$TANGGAL_RENCANA?>" style="width:300px; height:25px; font-size:15px" readOnly="readOnly" ></td>
            </tr>
          <tr>
            <td width="130" height="29" valign="top">Business Area</td>
            <td width="7" height="29" valign="top">:</td>
            <td width="355" align="left" valign="top" ><input name="ID_BAlabel" type="text" id="ID_BAlabel" value="<?=$BA?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/></td>
            <td width="130" height="29" valign="top" >Nama Mandor</td>
            <td width="7" height="29" valign="top" >:</td>
            <td width="355" align="left" valign="top" ><input name="Nama_Mandorlabel" type="text" id="Nama_Mandorlabel" value="<?=$NAMA_MANDOR?>" style="width: 300px; height:25px; font-size:15px" onClick='javascript:showListMandor();'  readOnly="readOnly"/><input name="NIK_Mandor" type="text" id="NIK_Mandor" value="<?=$NIK_MANDOR?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px; display:none"></td>
            </tr>
          <tr>
            <td width="130" height="29" valign="top" >Afdeling Panen</td>
            <td width="7" height="29" valign="top" >:</td>
            <td width="355" align="left" valign="top" ><input name="AFDlabel" type="text" id="AFDlabel" value="<?=$AFD?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/></td>
            <td width="130" height="29" valign="top" >No BCC</td>
            <td width="7" height="29" valign="top" >:</td>
            <td width="355" align="left" valign="top" ><input name="No_BCClabel" type="text" id="No_BCClabel" value="<?=separator($aNO_BCC)?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/><input name="NO_Rekap" type="text" id="NO_Rekap" value="<?=$NO_REKAP?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px; display:none"></td>
            </tr>
			<tr>
			<td width="130" height="29" valign="top" ></td>
            <td width="7" height="29" valign="top" ></td>
            <td width="355" align="left" valign="top" ></td>
            <td width="130" height="29" valign="top" >BLOK</td>
            <td width="7" height="29" valign="top" >:</td>
            <td width="355" align="left" valign="top" ><select name="selectblok" id="selectblok">
			<?php
					$query_blok  = "SELECT ID_BLOK, BLOK_NAME FROM T_BLOK WHERE ID_BA_AFD = '$BA$AFD' order by ID_BLOK";
					$result_blok = oci_parse($con, $query_blok);
								   oci_execute($result_blok, OCI_DEFAULT);
					while ($p=oci_fetch($result_blok)) {	
								  $id_blok = oci_result($result_blok, "ID_BLOK");
								  $blok_name = oci_result($result_blok, "BLOK_NAME");
								  if($id_blok == $ID_BLOK){
									echo "<option value=\"$id_blok\" selected='selected'>$id_blok - $blok_name</option>\n";
								  }else{
									echo "<option value=\"$id_blok\">$id_blok - $blok_name</option>\n";
								  }
					}
			?>
		   </select></td>
        </table></td>
        </tr>
      <tr>
        <td align="center">
          <table width="991" border="0" style="border:solid #556A29">
            <tr>
              <td width="112" align="center" colspan="6">Data Pemanen</td>
              </tr>
            <tr>
              <td width="130" height="29" valign="top" >Business Area</td>
              <td width="7" height="29" valign="top" >:</td>
              <td width="355" align="left" valign="top" ><input name="ID_BAlabel" type="text" id="ID_BAlabel" value="<?=$BA?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/></td>
              
              <td width="130" height="29" valign="top" >Nama Pemanen</td>
              <td width="7" height="29" valign="top" >:</td>
              <td width="355" align="left" valign="top" >
                <input name="Nama_Pemanen" type="text" id="Nama_Pemanen" value="<?=$Nama_Pemanen?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/>
                </td>
              </tr>
            <tr>
              <td width="130" height="29" valign="top" >Afdeling</td>
              <td width="7" height="29" valign="top" >:</td>
              <td width="355" align="left" valign="top">
                <input name="Afd_Pemanen" type="text" id="Afd_Pemanen" value="<?=$Afd_Pemanen?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/>
                </td>
              
              <td width="130" height="29" valign="top" >NIK Pemanen</td>
              <td width="7" height="29" valign="top" >:</td>
              <td width="355" align="left" valign="top" >
                <input name="NIK_Pemanen" type="text" id="NIK_Pemanen" value="<?=$NIK_Pemanen?>" style="width:300px; height:25px; font-size:15px" onClick='javascript:showListPemanen();' readOnly="readOnly"/>              </td>
              </tr>
          </table>        </td>
        </tr>
      <tr>
        <td align="center">
          <table width="992" border="0" style="border:solid #556A29">
            <tr bgcolor="#9CC346">
              <td width="55" align="center" style="font-size:14px; border-bottom:ridge">No.</td>
              <td width="466" align="center" style="font-size:14px; border-bottom:ridge">Kualitas Panen</td>
              <td width="216" align="center" style="font-size:14px; border-bottom:ridge">Jumlah Lama</td>
              <td width="229" align="center" style="font-size:14px; border-bottom:ridge">Jumlah Baru</td>
              </tr>
            <?php

for($xJAN = 0; $xJAN <  $roweffec_BCC ; $xJAN++){
	$no = $xJAN +1;
	
	if(($xJAN % 2) == 0){
		$bg = "#F0F3EC";
	}
	else{
		$bg = "#DEE7D2";
	}
	
echo "<tr style=\"font-size:14px\" bgcolor=$bg >";
echo "<td>$no</td>
            <td>$NAMA_KUALITAS[$xJAN]</td>
            <td align=\"center\">$QTY[$xJAN]<input name=\"OldQty$xJAN\" value=\"$QTY[$xJAN]\" style=\"display:none\">
			<input type=\"text\" name=\"ID_BCC_KUALITAS$xJAN\" value=\"$ID_BCC_KUALITAS[$xJAN]\" style=\"display:none\">
			<input type=\"text\" name=\"ID_Kualitas$xJAN\" value=\"$ID_Kualitas[$xJAN]\" style=\"display:none\">
			<input type=\"text\" name=\"NIK_Pemanen1\" value=\"$NIK_Pemanen\" style=\"display:none\">
			<input type=\"text\" name=\"ID_RENCANA\" value=\"$ID_RENCANA\" style=\"display:none\"></td>
            <td align=\"center\">
			<input name=\"NewQty$xJAN\" type=\"text\" id=\"NewQty$xJAN\" value=\"$QTY[$xJAN]\" style=\"width: 50px; height:25px; font-size:15px\"/></td>
			";

}
echo "</tr>";
?>
            </table>
          <input name="roweffec_BCC" type="text" id="roweffec_BCC" value="<?=$roweffec_BCC?>" style="display:none"/>
          <input name="No_BCC" type="text" id="No_BCC" value="<?=$aNO_BCC?>" style="display:none"/>
        </td>
        </tr>
      <tr>
        <td align="center" colspan="3"><input type="submit" name="button" id="button" value="SIMPAN" style="width:120px; height: 30px" onclick="formSubmitvalue()"/></td>
      </tr>
       <tr>
         <td align="center"><span class="style1">Pastikan koreksi data Anda telah mendapatkan persutujan dari EM atau KABUN !!</span></td>
        </tr>
    </table></th>
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
</th>
  </tr>
  </form>
<?php
}
else{
	$_SESSION[err] = "tolong login dulu!".$Job_Code."<br>".$username."<br>".$Emp_Name."<br>".$subID_BA_Afd;
	header("location:../index.php");
}
?>