<?php
session_start();
include("../include/Header.php");
?>
<!-- LIBRARY JQUERY -->
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
var htmlobjek;
$(document).ready(function(){
  //apabila terjadi event onchange terhadap object <select id=propinsi>
  $("#propinsi").change(function(){
    var propinsi = $("#propinsi").val();
    $.ajax({
        url: "ambilkota.php",
        data: "propinsi="+propinsi,
        cache: false,
        success: function(msg){
            //jika data sukses diambil dari server kita tampilkan
            //di <select id=kota>
            $("#kota").html(msg);
        }
    });
  });
  $("#kota").change(function(){
    var kota = $("#kota").val();
    $.ajax({
        url: "ambilkecamatan.php",
        data: "kota="+kota,
        cache: false,
        success: function(msg){
            $("#id_kec").html(msg);
        }
    });
  });
});

</script>

<script type="text/javascript">

function send(x)
{
	if(x == "1"){
	var str2 = document.getElementById('Nama_TM1').value;
	var str3 = document.getElementById('NIK_TM1').value;
	var n=str2.split(":"); 
	//alert (str2);
	document.getElementById('NIK_TM1').value= n[0];
	document.getElementById('Nama_TM1').value= n[1];


	}	
	
	if(x == "2"){
	var str2 = document.getElementById('id_kec').value;
	var n=str2.split("-"); 
	//alert (str2);
	document.getElementById('yy').value= n[0];
	document.getElementById('nama').value= n[1];

	}	
	
}

</script>
<?php
include("../config/SQL_function.php");
		//require_once __DIR__ . '/db_connect.php'; 
		include("../config/db_config.php");
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['Jenis_Login']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name'])&& isset($_SESSION['subID_CC'])){	
$Job_Code = $_SESSION['Job_Code'];
$username = $_SESSION['NIK'];	
$Emp_Name = $_SESSION['Name'];
$Jenis_Login = $_SESSION['Jenis_Login']; //echo "Jenis_Login: $Jenis_Login";
$Comp_Name = $_SESSION['Comp_Name'];
$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
$subID_CC=$_SESSION['subID_CC'];


$Date = $_SESSION['Date'];
	
	if($username == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:login.php");
	}
	
		// close secure user matriks
		$RegisterDevice = "";
		if(isset($_POST["RegisterDevice"])){
			$RegisterDevice = $_POST["RegisterDevice"];
			$_SESSION["RegisterDevice"] = $RegisterDevice;
		}
		if(isset($_SESSION["RegisterDevice"])){
			$RegisterDevice = $_SESSION["RegisterDevice"];
		}
		
		if($RegisterDevice == TRUE){
	
		   $Nama_TM1	= "";
		   $Nama_TM2	= "";
		   $NIK_TM1	= "";
		   $NIK_TM2	= "";
		   $sql_bcc_restan  ="";
		   if($NIK_TM1 == ""){
		   $pagesize = 15;
            if($Jenis_Login==9)
							{ 		   
			$sql_bcc_restan  = "SELECT  ID_DEV, ID_BA, ID_CC, MERK, TIPE, ip, NIK1, F_GET_EMPNAME(NIK1) AS NAMA, NIK2  
			FROM T_DEVICE WHERE  STA_DEV='Y'";
			
			}			
							else
							{
							$sql_bcc_restan  = "SELECT  ID_DEV, ID_BA, ID_CC, MERK, TIPE, ip, NIK1, F_GET_EMPNAME(NIK1) AS NAMA, NIK2  
			FROM T_DEVICE WHERE ID_BA='$subID_BA_Afd' AND STA_DEV='Y'";
							
				}
						
					
							
				$result_t_bcc_restan = oci_parse($con, $sql_bcc_restan);
				oci_execute($result_t_bcc_restan, OCI_DEFAULT);
				while(oci_fetch($result_t_bcc_restan)){
				$ID_DEV[] 			= oci_result($result_t_bcc_restan, "ID_DEV");
				$ID_BA[] 			= oci_result($result_t_bcc_restan, "ID_BA");
				$ID_CC[] 			= oci_result($result_t_bcc_restan, "ID_CC");
				$MERK[] 		    = oci_result($result_t_bcc_restan, "MERK");
				$TIPE[] 		= oci_result($result_t_bcc_restan, "TIPE"); 
				$ip[]   = oci_result($result_t_bcc_restan, "ip"); 
				$NIK1[]   = oci_result($result_t_bcc_restan, "NIK1");
				$NIK2[]   = oci_result($result_t_bcc_restan, "NIK2");
				$NAMA[]   = oci_result($result_t_bcc_restan, "NAMA");
				
				}
				$rowBCCRestan = oci_num_rows($result_t_bcc_restan);
				
				$totalpage = ceil($rowBCCRestan/$pagesize);
				$setPage = $totalpage - 1;
				//echo "DALAM IF: ".$sql_bcc_restan;
				//echo "row: ".$rowBCCRestan." ".$totalpage." - ".$setPage;
			}
			else{
				$totalpage = 0;
				$rowBCCRestan  = "";
				//echo "ELSE: ".$sql_bcc_restan;
			}
			
			if(isset($_SESSION["Cpage"])){
				$sesPage = $_SESSION["Cpage"];
			}
			else{
				$sesPage = 0;
			}
			
			if(isset($_GET["page"])){
			$OnPage = $_GET["page"];
			$CPage = 1;
				if($OnPage == "next"){
					$sesPageres = $sesPage + $CPage;
					if($sesPageres >= $setPage){
						$sesPageres = $setPage;
					}
					$calPage = $sesPageres * $pagesize;
					$_SESSION["Cpage"]  = $sesPageres; 
				}
				
				else if($OnPage == "back"){
					$sesPageres = $sesPage - $CPage;
					if($sesPageres <= 0){
						$sesPageres = 0;
					}
					$calPage = $sesPageres * $pagesize;
					$_SESSION["Cpage"]  = $sesPageres; 
				}
				
				else{
					$calPage = 0; 
				}
			}
			else{
				$CPage = 0;
				$sesPageres = $sesPage + $CPage;
				$calPage = $sesPageres * $pagesize;
				$_SESSION["Cpage"]  = $sesPageres;  
			}

}		
}	
?>






<script type="text/javascript">
function validasi_input(form){
	  if (form.propinsi.value == "--select--"){
		alert("Data Company masih kosong!");
		form.propinsi.focus();
		return (false);
	  }	  
	  if (form.propinsi.value == ""){
		alert("Data Company masih kosong!");
		form.propinsi.focus();
		return (false);
	  }

	  if (form.kota.value == ""){
		alert("Data BA  masih kosong!");
		form.kota.focus();
		return (false);
	  }
	  
	  if (form.kota.value == "--select--"){
		alert("Data BA  masih kosong!");
		form.kota.focus();
		return (false);
	  }
	  
	  if (form.id_kec.value == ""){
		alert("Data User  masih kosong!");
		form.kota.focus();
		return (false);
	  }
	  
	  if (form.id_kec.value == "--select--"){
		alert("Data User  masih kosong!");
		form.kota.focus();
		return (false);
	  }
	  
	  if (form.ip.value == ""){
		alert("Data ip masih kosong!");
		form.ip.focus();
		return (false);
	  }


	  
	    
	   if (form.ip.value == ""){
		alert("Data ip masih kosong!");
		form.ip.focus();
		return (false);
	  }
	  return (true);
  
}
</script>
<script language="javascript" type="text/javascript">
<!--
function deletebarang(noCust) {
	if (confirm('Apakah Device  : '+noCust+' Ini Akan Di Hapus ?')) return true;
	else return false;
}
//-->
</script>
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

<table width="1140" height="390" border="0" align="center">
  <!--<tr bgcolor="#C4D59E">-->
  <tr>
    <th height="197" scope="row" align="center"> <table width="1118" border="0" id="setbody2">
        <tr>
    <th colspan="6" width="150" align="left"><span style="font-size:18px;">SETTING IP IMAGE</span></th>
  </tr>
        <tr valign="middle">
          <td width="921" align="center" valign="bottom" style="font:bold; font-size:18px"><table width="1111" border="0">
              <tr>
                <td align="center" valign="top">
				 
				<form id="form1" name="form1" method="post" action="doSubmit.php" onsubmit="return validasi_input(this)">
                    <table width="1104"  border="0" id="setbody2">  
                        <tr>
						<td height="2" colspan="3"  style="border-bottom:solid #000">&nbsp;</td>
					</tr> 
                     
                      <tr>
                        <td width="150" height="29" valign="top">Company Name</td>
						<td width="10" height="29" valign="top">:</td>
						<td><select name="propinsi" id="propinsi">
                            <option selected="selected">--select--</option>
                            <?php
									$sql_t_CC  = "SELECT ID_CC, COMP_NAME FROM t_companycode order by ID_CC";
									$result_t_CC = oci_parse($con, $sql_t_CC);
												   oci_execute($result_t_CC, OCI_DEFAULT);
							        while (oci_fetch($result_t_CC)) {	
												   $id_prov= oci_result($result_t_CC, "ID_CC");
												   $nama_prov= oci_result($result_t_CC, "COMP_NAME");
											       echo "<option value=\"$id_prov\">$id_prov : $nama_prov</option>\n";
								   }
							?>
                          </select>
                       </td>
                      </tr>
                      <tr>
                        <td width="150" height="29" valign="top">Business Area</td>
						<td width="10" height="29" valign="top">:</td>
						<td><select name="kota" id="kota" >
                            <option selected="selected">--select--</option>
                            <?php
									$sql_t_BA  = "SELECT ID_BA, NAMA_BA FROM T_BUSSINESSAREA ORDER BY ID_BA";
									$result_t_BA = oci_parse($con, $sql_t_BA);
												   oci_execute($result_t_BA, OCI_DEFAULT);
									while ($p=oci_fetch($result_t_BA)) {	
												  $id_kabkot = oci_result($result_t_BA, "ID_BA");
												  $nama_kabkot = oci_result($result_t_BA, "NAMA_BA");
									     		  echo "<option value=\"$id_kabkot\">$id_kabkot : $nama_kabkot</option>\n";
									}
									?>
                           </select>
                       </td>
                      </tr>
                      <tr>
                        <td width="150" height="29" valign="top">Afdeling</td>
						<td width="10" height="29" valign="top">:</td>
						<td><select name="id_kec" id="id_kec">
                            <option>-All-</option>
                          </select>
                        </td>
                      </tr>
					 
					  
                      <tr>
                        <td width="150" height="29" valign="top">IP Address</td>
						<td width="10" height="29" valign="top">:</td>
						<td><input name="ip" type="text" size="50" class="box" /></td>
                      </tr>
                        <tr>
						<td height="2" colspan="3"  style="border-bottom:solid #000">&nbsp;</td>
					</tr> 
                      <tr>
                        <td  align="right" colspan="7">
                          <input type="submit" name="button" id="button" value="SIMPAN" style="width:120px; height: 30px"/>
                        </td>
                      </tr>
                    </table>
                  </form>
				       
					</td>
              </tr>
            </table></td>
        </tr>
      </table></th>
  </tr>
  <tr>
    <td colspan="7" valign="top">
	<?php
	if($rowBCCRestan > 0){
	echo "
		<table width=\"1140\" border=\"1\" bordercolor=\"#9CC346\">
          <tr bgcolor=\"#9CC346\">
            <td width=\"10\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">Company Code</td>
			<td width=\"30\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">Business Area</td>
			<td width=\"100\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">MERK</td>
            <td width=\"40\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">TIPE</td>
            <td width=\"100\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">ip</td>
			<td width=\"100\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">NIK</td>
			<td width=\"100\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">NAMA USER</td>
			<td width=\"80\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">Action</td>
          </tr>
	";		  

	$endPage = $calPage + $pagesize;
	for($xJAN = $calPage; $xJAN <  $rowBCCRestan && $xJAN <$endPage; $xJAN++){
	$no = $xJAN +1;
	
	if(($xJAN % 2) == 0){
		$bg = "#F0F3EC";
	}
	else{
		$bg = "#DEE7D2";
	}

	echo "<tr style=\"font-size:12px\" bgcolor=$bg>";
	echo "<td align=\"center\" style=\"font-size:12px\" >&nbsp;$ID_CC[$xJAN]</td>
            <td align=\"center\" style=\"font-size:12px\" >&nbsp;$ID_BA[$xJAN]</td>
            <td align=\"center\" style=\"font-size:12px\" >&nbsp;$MERK[$xJAN]</td>
			<td align=\"center\" style=\"font-size:12px\" >&nbsp;$TIPE[$xJAN]</td>
			<td align=\"center\" style=\"font-size:12px\" >&nbsp;$ip[$xJAN]</td>
			<td align=\"center\" style=\"font-size:12px\" >&nbsp;$NIK1[$xJAN]</td>
			<td align=\"center\" style=\"font-size:12px\" >&nbsp;$NAMA[$xJAN]</td>
				<td align=\"center\" style=\"font-size:12px\" > 
				
				<a href=\"editdevice.php?&id=$ID_DEV[$xJAN]\" onClick=\"return editbarang('$ip[$xJAN]');\">
				<input type=\"button\" name=\"button\" id=\"button\" value=\"Edit\"/>
				</a>
				
				
				<a href=\"delSubmitya.php?&id=$ID_DEV[$xJAN]\" onClick=\"return deletebarang('$ip[$xJAN]');\">
				<input type=\"button\" name=\"button\" id=\"button\" value=\"Hapus\"/>
				</a>
				
				
			</td>
		    ";
	}
		echo "</tr></table>";
	}
	?>
    </td>
  </tr>

  <tr>
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
