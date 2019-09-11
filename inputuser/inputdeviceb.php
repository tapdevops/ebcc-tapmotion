<!-- LIBRARY JQUERY -->
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery.min.js"></script>
<!-- END LIBRARY JQUERY -->
<!-- TOKEN INPUT 
<script type="text/javascript" src="jquery.tokeninput.js"></script>
-->
<script type="text/javascript">
					$(document).ready(function() {
						//var bacode = $("#BATM2").val();
						var bacode =  $('#BATM2').find(":selected").text();
						var q =  $('#Nama_TM2').find(":selected").text();
						$("#Nama_TM2").autocomplete("userTM2.php?bacode="+bacode+"&q="+q, {
							selectFirst: true
						});
					});
				</script>
<script type="text/javascript">
					$(document).ready(function() {
						//var bacode = $("#BATM3").val();
						var bacode =  $('#BATM3').find(":selected").text();
						var q =  $('#Nama_TM3').find(":selected").text();						
						$("#Nama_TM3").autocomplete("userTM3.php?bacode="+bacode+"&q="+q, {
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
include("../config/SQL_function.php");
		//require_once __DIR__ . '/db_connect.php'; 
		include("../config/db_config.php");
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
session_start();
if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['Jenis_Login']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name'])&& isset($_SESSION['subID_CC'])){	
$Job_Code = $_SESSION['Job_Code'];
$username = $_SESSION['NIK'];	
$Emp_Name = $_SESSION['Name'];
$Jenis_Login = $_SESSION['Jenis_Login'];
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
			$sql_bcc_restan  = "SELECT  ID_DEV, ID_BA, ID_CC, MERK, TIPE, IMEI, NIK1, F_GET_EMPNAME(NIK1) AS NAMA, NIK2  
			FROM T_DEVICE WHERE ID_BA='$subID_BA_Afd' AND STA_DEV='Y'";
				$result_t_bcc_restan = oci_parse($con, $sql_bcc_restan);
				oci_execute($result_t_bcc_restan, OCI_DEFAULT);
				while(oci_fetch($result_t_bcc_restan)){
				$ID_DEV[] 			= oci_result($result_t_bcc_restan, "ID_DEV");
				$ID_BA[] 			= oci_result($result_t_bcc_restan, "ID_BA");
				$ID_CC[] 			= oci_result($result_t_bcc_restan, "ID_CC");
				$MERK[] 		    = oci_result($result_t_bcc_restan, "MERK");
				$TIPE[] 		= oci_result($result_t_bcc_restan, "TIPE"); 
				$IMEI[]   = oci_result($result_t_bcc_restan, "IMEI"); 
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
		else{
			header("location:../menu/authoritysecure.php");
		} // close secure user matriks

}		
	
?>
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
	var str2 = document.getElementById('Nama_TM2').value;
	var n=str2.split(":"); 
	//alert (str2);
	document.getElementById('NIK_TM2').value= n[0];
	document.getElementById('Nama_TM2').value= n[1];

	}	
	
}

</script>


<script type="text/javascript">
function validasi_input(form){
	  if (form.merk.value == ""){
		alert("Data merk masih kosong!");
		form.merk.focus();
		return (false);
	  }

	  if (form.tipe.value == ""){
		alert("Data tipe masih kosong!");
		form.tipe.focus();
		return (false);
	  }
	  
	  


	 if (form.imei.value != ""){
	  var x = (form.imei.value);
	  var status = true;
	  var list = new Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
	  for (i=0; i<=x.length-1; i++)
	  {
	  if (x[i] in list) cek = true;
	  else cek = false;
	 status = status && cek;
	  }
	  if (status == false)
	  {
	  alert("Imei harus angka!");
	  form.imei.focus();
	  return false;
	  }
	  }
	  
	  
	 if (form.NIK_TM1.value == ""){
		alert("Data User 1 masih kosong!");
		form.Nama_TM1.focus();
		return (false);
	  }
	  
	  if (form.NIK_TM2.value == ""){
		alert("Data User 2 masih kosong!");
		form.Nama_TM2.focus();
		return (false);
	  }
	  
	  
	   if (form.imei.value == ""){
		alert("Data imei masih kosong!");
		form.imei.focus();
		return (false);
	  }
	  return (true);
  
}
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
</style>
<table width="1140" height="390" border="0" align="center">
  <tr>
    <th width="972" height="108" scope="row"><?php include("../include/sHeader.php") ?></th>
  </tr>
  <tr>
    <th height="40" scope="row" align="center"><span style="font-size: 18px">Register Device</span></th>
  </tr>
  <tr bgcolor="#C4D59E">
    <th height="197" scope="row" align="center">
	
	<table width="1118" border="0">
        <tr valign="middle">
          <td width="921" align="center" valign="bottom" style="font:bold; font-size:18px">
          
              <table width="1111" border="0">
                <tr>
                  <td align="center" valign="top"> <form id="form1" name="form1" method="post" action="doSubmit.php" onsubmit="return validasi_input(this)">
                  <table width="1104"  border="0" style="border:#9CC346 ridge">
               
                      <tr>
                        <td>Merk</td>
                        <td>&nbsp;</td>
                        <td><input name="merk" type="text" size="50" class="box" /></td>
                      </tr>
                      <tr>
                        <td height="25">Type</td>
                        <td>&nbsp;</td>
                        <td><input name="tipe" type="text" size="50" class="box" /></td>
                      </tr>
                      <tr>
                        <td height="25">No Imei</td>
                        <td>&nbsp;</td>
                        <td><input name="imei" type="text" size="50" class="box" maxlength="15" /></td>
                      </tr>
                      <tr>
                        <td height="25">Nik User 1</td>
                        <td>&nbsp;</td>
                        <td><input name="Nama_TM1" type="text" id="Nama_TM1" value="<?=$Nama_TM1?>" size="50" class="box"  onchange="send(1)"/>
                          <script type="text/javascript">
					$(document).ready(function() {
						//var bacode = $("#BATM1").val();
						var bacode = "<?php echo $subID_BA_Afd; ?>";
						// $('#subID_BA_Afd').find(":selected").text();
						var q =  $('#Nama_TM1').find(":selected").text();
						$("#Nama_TM1").autocomplete("userTM1.php?bacode="+bacode+"&q="+q, {
							selectFirst: true
						});
					});
        </script>
                        <input name="NIK_TM1" type="text" id="NIK_TM1" value="<?=$NIK_TM1?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
                      </tr>
                      <tr>
                        <td height="25">Nik User 2</td>
                        <td>&nbsp;</td>
                        <td><input name="Nama_TM2" type="text" id="Nama_TM2" value="<?=$Nama_TM2?>" size="50" class="box"  onchange="send(2)"/>
                          <script type="text/javascript">
					$(document).ready(function() {
						//var bacode = $("#BATM1").val();
						var bacode = "<?php echo $subID_BA_Afd; ?>";
						// $('#subID_BA_Afd').find(":selected").text();
						var q =  $('#Nama_TM2').find(":selected").text();
						$("#Nama_TM2").autocomplete("userTM2.php?bacode="+bacode+"&q="+q, {
							selectFirst: true
						});
					});
        </script>
                        <input name="NIK_TM2" type="text" id="NIK_TM2" value="<?=$NIK_TM2?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
                      </tr>
                      <tr>
                         <td  align="center" colspan="7">  

            <input name="BA" type="text" id="BA" value="<?=$subID_BA_Afd?>" style="display:none" onmousedown="return false"/>
            <input name="CC" type="text" id="CC" value="<?= $subID_CC?>" style="display:none" onmousedown="return false"/>
            <input type="submit" name="button" id="button" value="SIMPAN" style="width:120px; height: 30px"/>            </td>
                      </tr>
                    </table>
              </form>
          </td>
                </tr>
              </table>
           </td>
        </tr>
      </table>
      </th>
      
  </tr>
   <tr>
          <td colspan="8" valign="top"><?php
if($rowBCCRestan > 0){
	
echo "
		<table width=\"1140\" border=\"1\" bordercolor=\"#9CC346\">
          <tr bgcolor=\"#9CC346\">
            <td width=\"100\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">Company Code</td>
			<td width=\"100\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">Business Area</td>
			<td width=\"100\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">MERK</td>
            <td width=\"100\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">TIPE</td>
            <td width=\"100\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">IMEI</td>
			<td width=\"100\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">NIK</td>
			<td width=\"100\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\">NAMA USER</td>
			<td width=\"40\" align=\"center\" style=\"font-size:14px; border-bottom:ridge\"></td>
		
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


	
echo "<tr style=\"font-size:14px\" bgcolor=$bg>";
echo "<td align=\"center\">&nbsp;$ID_CC[$xJAN]</td>
            <td align=\"center\">&nbsp;$ID_BA[$xJAN]</td>
            <td align=\"center\">&nbsp;$MERK[$xJAN]</td>
			<td align=\"center\">&nbsp;$TIPE[$xJAN]</td>
			<td align=\"center\">&nbsp;$IMEI[$xJAN]</td>
			<td align=\"center\">&nbsp;$NIK1[$xJAN]</td>
			<td align=\"center\">&nbsp;$NAMA[$xJAN]</td>
			<td>
				<form id=\"formNOBCC$xJAN\" name=\"formNOBCC$xJAN\" method=\"post\" action=\"doDelete.php\">
				<input name=\"editNO_BCC\" type=\"text\" id=\"editNO_BCC\" value=\"$ID_DEV[$xJAN]\" style=\"display:none\"/>
				
				<a href=\"javascript:;\" onclick=\"javascript: document.getElementById('formNOBCC$xJAN') .submit()\">
				<input type=\"button\" name=\"button\" id=\"button\" value=\"Hapus\"/>
				</a>
					
			</form>
			
			
			</td>
		            ";

}
echo "</tr></table>";
}
?>
          </td>
        </tr>
        <tr>
  


          <td align="right"><table  border="0">
              <tr>
                <td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="inputdevice.php?page=back">
                  <input type="button" name="button5" id="button5" value="&lt;&lt; Back" style="width:70px; background-color:#9CC346"/>
                  </a></td>
                <td width="65" align="center" valign="middle" style="background-color:#9CC346; font-size:12px"><span style="padding-top:0px">Page
                  <?=$sesPageres+1?>
                  of
                  <?=$totalpage?>
                  </span></td>
                <td width="74" align="left" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="inputdevice.php?page=next"></a><a href="inputdevice.php?page=next">
                  <input type="button" name="button4" id="button4" value="Next &gt;&gt;" style="width:70px; background-color:#9CC346"/>
                  </a></td>
              </tr>
            </table></td>
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
    <th align="center"><?php include("../include/sFooter.php") ?></th>
  </tr>
</table>
