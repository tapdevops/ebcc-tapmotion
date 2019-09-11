<?php
	session_start();

if(isset($_SESSION[NIK])){

	$Job_Code = $_SESSION[Job_Code];
	$username = $_SESSION[NIK];	
	$Emp_Name = $_SESSION[Name];
	$Jenis_Login = $_SESSION[Jenis_Login];	
	$subID_BA_Afd = $_SESSION[subID_BA_Afd];		
		if($username == ""){
			$_SESSION[err] = "Please login";
			header("location:../../login.php");
		}
		else{		
		
			/*if($_GET[No_BCC] != "" && $_GET[No_TPH] != ""){
				$No_BCC = $_GET[No_BCC];
				$No_TPH = $_GET[No_TPH];
				$_SESSION[No_BCC] = $No_BCC ;
				$_SESSION[No_TPH] = $No_TPH ;			
			}
			
			else if($_SESSION[No_BCC] != "" && $_SESSION[No_TPH] != ""){
				$No_BCC = $_SESSION[No_BCC];
				$No_TPH = $_SESSION[No_TPH];
			}
			
			else{
				$_SESSION[err] = "Data for update not found";
				header("Location:../../login.php");
			}*/
			
			/*include '../../db_connect.php';
			$db = new DB_CONNECT(); 
			
			$sql_value_hasil_panen = "SELECT t1.*, t2.ID_BA_Afd_Blok, t2.ID_Rencana, t2.Luasan_Panen 
			FROM t_hasil_panen t1 
			INNER JOIN t_detail_rencana_panen t2 ON t1.No_Rekap_BCC = t2.No_Rekap_BCC 
			WHERE SUBSTRING(ID_BA_Afd_Blok,1,4) = '$subID_BA_Afd' AND No_BCC = '$No_BCC' AND No_TPH = '$No_TPH' ";	
			
			$result_value_hasil_panen = mysql_query($sql_value_hasil_panen);
			if($fetch_value_hasil_panen = mysql_fetch_array($result_value_hasil_panen)){ 
				$ID_RencanaSBCC		= $fetch_value_hasil_panen['ID_Rencana'];
				$ID_BA_Afd_BlokSBCC	= $fetch_value_hasil_panen['ID_BA_Afd_Blok'];
				$Luasan_PanenSBCC	= $fetch_value_hasil_panen['Luasan_Panen'];
				$No_BCCSBCC			= $fetch_value_hasil_panen['No_BCC'];
				$No_TPHSBCC			= $fetch_value_hasil_panen['No_TPH'];
				$Kode_Delivery_TicketSBCC	= $fetch_value_hasil_panen['Kode_Delivery_Ticket'];
				$LatitudeSBCC		= $fetch_value_hasil_panen['Latitude'];
				$LongitudeSBCC		= $fetch_value_hasil_panen['Longitude'];
				$Picture_NameSBCC	= $fetch_value_hasil_panen['Picture_Name'];
				$Status_BCCSBCC		= $fetch_value_hasil_panen['Status_BCC'];
				$ID_NAB_tglSBCC		= $fetch_value_hasil_panen['ID_NAB_Tgl'];
echo $ID_NAB_tglSBCC;
				$_SESSION["ID_RencanaSBCC"] 	= $ID_RencanaSBCC;
				$_SESSION["ID_BA_Afd_BlokSBCC"] = $ID_BA_Afd_BlokSBCC;
				$_SESSION["Luasan_PanenSBCC"] 	= $Luasan_PanenSBCC;
				$_SESSION["No_BCCSBCC"] 		= $No_BCCSBCC;
				$_SESSION["No_TPHSBCC"] 		= $No_TPHSBCC;
				$_SESSION["Kode_Delivery_TicketSBCC"] = $Kode_Delivery_TicketSBCC;
				$_SESSION["LatitudeSBCC"] 		= $LatitudeSBCC;
				$_SESSION["LongitudeSBCC"] 		= $LongitudeSBCC;
				$_SESSION["Picture_NameSBCC"] 	= $Picture_NameSBCC;
				$_SESSION["Status_BCCSBCC"] 	= $Status_BCCSBCC;
				$_SESSION["ID_NAB_tglSBCC"] 	= $ID_NAB_tglSBCC;
				$_SESSION[Status_BCCOld] = $Status_BCCSBCC;
			} //close if($result_value_hasil_panen ...
			
			else{
				$_SESSION[err] = "No data found";
				header("Location:../../Admin.php");	
			}	*/
			
		}

}
else{
	$_SESSION[err] = "Please login";
	header("Location:../../login.php");
}

		
?>

<script type="text/javascript">
function change(x)
{
	if(x == 1){
	document.getElementById("internal").style.display="inline";
	document.getElementById("eksternal").style.display="none";
	}
	if(x == 2){
	document.getElementById("internal").style.display="none";
	document.getElementById("eksternal").style.display="inline";
	}
}

function kirim(x)
{
	if(x == 1){
	document.getElementById("button").style.visibility="visible";
	}
	
	if(x == 2){
	document.getElementById("button").style.visibility="hidden";
	document.getElementById("form1").submit();
	}
	
	if(x == 3){
	document.getElementById("button").style.visibility="visible";
	}
}

function formSubmit(x)
{
	if(x == 1){
	document.getElementById("formTM1").submit();
	//alert ("tes1");
	}
	if(x == 2){
	document.getElementById("formTM2").submit();
	//alert ("tes2");
	}
	if(x == 3){
	document.getElementById("formTM3").submit();
	//alert ("tes3");
	}	
	
}

</script>


<table width="837" height="424" border="0" align="center">
  <tr>
    <th width="301" height="115" scope="row">&nbsp;</th>
  </tr>
  <tr>
    <th height="372" scope="row" valign="top"><table width="819" border="0" align="center">
      <tr style="color:#FFF">
        <th width="295" align="left" scope="row" valign="baseline"><span style="font:normal">Welcome,</span> <span style="font:bold"><?=$Emp_Name?> (<?=$username?>)</span></th>
      </tr>
      <tr style="font-style: italic; color:#FFF">
        <th align="left" scope="row">Bussiness Area :
          <?=$subID_BA_Afd?></th>
      </tr>
      <tr style="font-style: italic; color:#FFF">
        <th align="left" scope="row">Job Code/  Login Type:
          <?=$Job_Code?> / <?=$Jenis_Login?></th>
      </tr>
      <tr>
        <td height="201" scope="row" style="font:Georgia, 'Times New Roman', Times, serif; font-size:18px ; font-style: italic; color: #8A0000" align="center" valign="top">
        
        <table width="805" height="194" border="0"  align="center">
          <tr>
            <th height="46" style="font:Georgia, 'Times New Roman', Times, serif; font-size:24px ; font-style: italic; color: #666666; border-bottom:double #666666" scope="row">Edit Hasil Panen</th>
          </tr>
          <tr>
            <th height="32" scope="row"><table width="784" border="0">
              <tr>
                <th width="445" height="34" align="left" scope="row"><a href="../../welcome.php">
                  <input type="button" name="Report_U2" id="Report_U2" value="Home" style="width:140px; height:30px; color:#333"/>
                </a></th>
                <td width="447" align="right"><a href="../../db_disconnect.php">
                  <input type="button" name="Report_U2" id="Report_U3" value="Logout" style="width:140px; height:30px; color:#333"/>
                </a></td>
              </tr>
            </table></th>
          </tr>
          <tr>
            <th height="32" align="left" scope="row">
              
              <form id="form1" name="form1" method="post" action="RunEditStatusBCC.php">
                <table width="774" border="0">
                  <tr>
                    <td width="184" style="border-bottom:dotted #666666">ID_Rencana</td>
                    <td width="10">:</td>
                    <td width="270" style="border-bottom:dotted #666666"><?=$ID_RencanaSBCC?>
                      <input name="ID_RencanaSBCC" value="<?=$ID_RencanaSBCC?>" style="display:none" /></td>
                  </tr>
                  <tr>
                    <td style="border-bottom:dotted #666666">ID_BA_Afd_Blok</td>
                    <td>:</td>
                    <td style="border-bottom:dotted #666666"><?=$ID_BA_Afd_BlokSBCC?></td>
                  </tr>
                  <tr>
                    <td style="border-bottom:dotted #666666">Luasan_Panen</td>
                    <td>:</td>
                    <td style="border-bottom:dotted #666666"><?=$Luasan_PanenSBCC?></td>
                  </tr>
                  <tr>
                    <td style="border-bottom:dotted #666666">No_BCC</td>
                    <td>:</td>
                    <td style="border-bottom:dotted #666666"><?=$No_BCCSBCC?>
                      <input name="No_BCCSBCC" value="<?=$No_BCCSBCC?>" style="display:none" /></td>
                  </tr>
                  <tr>
                    <td style="border-bottom:dotted #666666">No_TPH</td>
                    <td>:</td>
                    <td style="border-bottom:dotted #666666"><?=$No_TPHSBCC?></td>
                  </tr>
                  <tr>
                    <td style="border-bottom:dotted #666666">Kode_Delivery_Ticket</td>
                    <td>:</td>
                    <td style="border-bottom:dotted #666666"><?=$Kode_Delivery_TicketSBCC?>
                      <input name="Kode_Delivery_TicketSBCC" value="<?=$Kode_Delivery_TicketSBCC?>" style="display:none" /></td>
                  </tr>
                  <tr>
                    <td style="border-bottom:dotted #666666">Latitude</td>
                    <td>:</td>
                    <td style="border-bottom:dotted #666666"><?=$LatitudeSBCC?></td>
                  </tr>
                  <tr>
                    <td style="border-bottom:dotted #666666">Longitude</td>
                    <td>:</td>
                    <td style="border-bottom:dotted #666666"><?=$LongitudeSBCC?></td>
                  </tr>
                  <tr>
                    <td style="border-bottom:dotted #666666">Picture_Name</td>
                    <td>:</td>
                    <td style="border-bottom:dotted #666666"><?=$Picture_NameSBCC?></td>
                  </tr>
                  <tr>
                    <td style="border-bottom:dotted #666666">Status_BCC</td>
                    <td>:</td>
                    <td style="border-bottom:dotted #666666"><?=$Status_BCCSBCC?>
                      <input name="Status_BCCSBCCOld" value="<?=$Status_BCCSBCC?>" style="display:none" /></td>
                  </tr>
                  <tr>
                    <td style="border-bottom:dotted #666666">New Status_BCC</td>
                    <td>:</td>
                    <td style="border-bottom:dotted #666666"><input type="radio" name="status" id="status" value="LOSS" onclick="kirim(3)"/>
LOSS</td>
                  </tr>
                  <tr>
                    <td style="border-bottom:dotted #666666">ID_NAB_tgl</td>
                    <td>:</td>
                    <td style="border-bottom:dotted #666666"><?=$ID_NAB_tglSBCC?></td>
                  </tr>
                  
                  <tr>
                    <td colspan="3" align="center"><input type="submit" name="button" id="button" value="Submit" style="visibility:hidden"/></td>
                  </tr>
                </table>
                </form>              
              
            </th>
            </tr>
          <tr>
            <th height="32" scope="row">&nbsp;</th>
          </tr>
          <tr>
            <th height="32" scope="row">&nbsp;</th>
          </tr>
        </table>
        <?php
			$err = $_SESSION[err];
			if($err!=null){
				echo $err;
				unset($_SESSION[err]);
			}
		?>
        
        
        </td>
      </tr>
      <tr>
        <th scope="row">&nbsp;</th>
      </tr>
      <tr>
        <th scope="row">&nbsp;</th>
      </tr>
    </table></th>
  </tr>
  <tr>
    <th height="30" scope="row" style="font-size:12px" align="center"> Copyright 2013 - Sola Interactive</th>
  </tr>
</table>



