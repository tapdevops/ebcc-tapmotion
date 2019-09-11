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
<?php
session_start();


if(isset($_GET['id'])){

	$id_dev = $_GET['id'];

	//print_r($_GET);
	//die ();
	                 


		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();


			$sql_bcc_restan  = "SELECT  ID_DEV, ID_BA, ID_CC, MERK, TIPE, IMEI, NIK1, F_GET_EMPNAME(NIK1) AS NAMA, NIK2 FROM T_DEVICE WHERE  ID_DEV='$id_dev' and STA_DEV='Y'";
				$result_t_bcc_restan = oci_parse($con, $sql_bcc_restan);
				oci_execute($result_t_bcc_restan, OCI_DEFAULT);
				while(oci_fetch($result_t_bcc_restan)){
				$ID_DEV 			= oci_result($result_t_bcc_restan, "ID_DEV");
				$ID_BA 			= oci_result($result_t_bcc_restan, "ID_BA");
				$ID_CC 			= oci_result($result_t_bcc_restan, "ID_CC");
				$MERK 		    = oci_result($result_t_bcc_restan, "MERK");
				$TIPE 		= oci_result($result_t_bcc_restan, "TIPE"); 
				$IMEI   = oci_result($result_t_bcc_restan, "IMEI"); 
				$NIK1   = oci_result($result_t_bcc_restan, "NIK1");
				$NIK2   = oci_result($result_t_bcc_restan, "NIK2");
				$NAMA   = oci_result($result_t_bcc_restan, "NAMA");
				
				}
				$rowBCCRestan = oci_num_rows($result_t_bcc_restan);
				//echo $sql_bcc_restan; die;
				
}		
	
?>

<script type="text/javascript">
function send(x)
{
	if(x == "2"){
	var str2 = document.getElementById('id_kec').value;
	var n=str2.split(":"); 
	//alert (str2);
	document.getElementById('yy').value= n[0];
	document.getElementById('nama').value= n[1];
	}		
}
</script>



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
    <th width="972" height="108" scope="row"><?php include("../include/Header.php") ?></th>
  </tr>
  <tr>
    <th height="40" scope="row" align="center"><span style="font-size: 18px">Edit Register Device</span></th>
  </tr>
  <tr bgcolor="#C4D59E">
    <th height="197" scope="row" align="center"> <table width="1118" border="0">
        <tr valign="middle">
          <td width="921" align="center" valign="bottom" style="font:bold; font-size:18px"><table width="1111" border="0">
              <tr>
                <td align="center" valign="top"><form id="form1" name="form1" method="post" action="upSubmit.php" onsubmit="return validasi_input(this)">
                    <table width="1104"  border="0" style="border:#9CC346 ridge">
        
                      <tr>
                        <td width="160">Company Name </td>
                        <td width="6">&nbsp;</td>
                        <td> <input name="propinsi" type="text" size="50" class="box" maxlength="15" value="<?=$ID_CC?>" readonly="readonly" />  </td>
                      </tr>
                      <tr>
                        <td>Business Area</td>
                        <td>&nbsp;</td>
                        <td> <input name="kota" type="text" size="50" class="box" maxlength="15" value="<?=$ID_BA?>" readonly="readonly" /> </td>
                      </tr>
                          <tr>
                        <td height="25">Nama User</td>
                        <td>&nbsp;</td>
                        <td><select name="id_kec" id="id_kec"  onchange="send(2)">
                            <option selected="selected"><?=$NAMA?> : <?=$NIK1?></option>
                            <?php
									$sql_t_BA  = "select te.nik, te.emp_name from t_employee te inner join 
               									  t_afdeling ta on te.id_ba_afd = ta.id_ba_afd where ID_BA='$ID_BA' and job_code='KRANI BUAH' order by te.emp_name ";
									$result_t_BA = oci_parse($con, $sql_t_BA);
												   oci_execute($result_t_BA, OCI_DEFAULT);
									while ($p=oci_fetch($result_t_BA)) {
										  $id_kec = oci_result($result_t_BA, "NIK");
										  $nama_kec = oci_result($result_t_BA, "EMP_NAME");
											   //  ECHO $nama_kec; DIE;
										  echo "<option value=\"$id_kec\">$nama_kec : $id_kec</option>\n";
									}
									?>
                           </select></td>
                        </tr>
                      <tr>
                        <td>Merk</td>
                        <td>&nbsp;</td>
                        <td><input name="merk" type="text" size="50" class="box" value="<?=$MERK?>" /></td>
                      </tr>
                      <tr>
                        <td height="25">Type</td>
                        <td>&nbsp;</td>
                        <td><input name="tipe" type="text" size="50" class="box" value="<?=$TIPE?>"/></td>
                      </tr>
                      <tr>
                        <td height="25">No Imei</td>
                        <td>&nbsp;</td>
                        <td><input name="imei" type="text" size="50" class="box" maxlength="15"  value="<?=$IMEI?>" /></td>
                      </tr>                                     
                      <tr>
                        <td  align="center" colspan="7">
                          <input name="yy"  id="yy" value="<?=$NIK1?>" type="hidden"/>
                          <input name="id_dev"  value="<?=$ID_DEV?>" type="hidden"/>
                          <input type="submit" name="button" id="button" value="UPDATE" style="width:120px; height: 30px"/>                       
                         </td>
                      </tr>
                    </table>
                  </form></td>
              </tr>
            </table></td>
        </tr>
      </table></th>
  </tr>
  <tr>
    
  </tr>
</table>
