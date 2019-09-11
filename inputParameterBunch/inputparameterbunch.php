<?php
session_start();
include("../include/Header.php");
?>
<!-- LIBRARY JQUERY -->
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
var htmlobjek;
$(document).ready(function(){
  $("#propinsi").change(function(){
    var propinsi = $("#propinsi").val();
    $.ajax({
        url: "ambilkota.php",
        data: "propinsi="+propinsi,
        cache: false,
        success: function(msg){
            $("#kota").html(msg);
			$("#res_par").html("");
        }
    });
  });
  
  $("#kota").change(function(){
		$("#bunch").show();
		$("#bunch").val("");
		$("#res_par").html("");
  });
  
	$("#bunch").change(function(){
		var comp = $("#propinsi").val();
		var ba = $("#kota").val();
		var bunch = $("#bunch").val();

		if (comp=='' || ba == ''){
			alert ("Silahkan isi Company Name dan Bisnis Area terlebihdahulu!");
			$("#bunch").val('');
		}else{
			$.ajax({
				url: "ambilparameter.php",
				data: { "ba": ba, "bunch" : bunch},
				cache: false,
				success: function(msg){
					$("#res_par").html(msg);
				}
			});
		}	
	}); 
  
});

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
$Jenis_Login = $_SESSION['Jenis_Login']; 
$Comp_Name = $_SESSION['Comp_Name'];
$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
$subID_CC=$_SESSION['subID_CC'];
$Date = $_SESSION['Date'];
	
	if($username == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:login.php");
	}
	

	
}	
?>


<script type="text/javascript">
function resFrm(){
	location.href = "inputparameterbunch.php";
}

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
	  
	  if (form.bunch.value == ""){
		alert("Data Bunch Type masih kosong!");
		form.kota.focus();
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
body,td,th {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-weight:normal;
}
</style>

<table width="1151" height="390" border="0" align="center">
  <!--<tr bgcolor="#C4D59E">-->
  <tr>
    <th height="197" scope="row" align="center"><table width="937" border="0" id="setbody2">
      <tr>
		<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>REGISTER PARAMETER BUNCH</strong></span></td>
      </tr>
        <tr valign="middle">
          <td width="921" align="center" valign="bottom" style="font:bold; font-size:18px"><table width="1111" border="0">
              <tr>
                <td align="center" valign="top">
				<form id="form1" name="form1" method="post" action="doSubmit.php" onsubmit="return validasi_input(this)">
                    <table width="1104"  border="0" style="border:#9CC346 ridge">
					     
                      <tr>
                        <td width="150" height="29" valign="top">Company Name</td>
						<td><select name="propinsi" id="propinsi">
                            <option value="" selected="selected">--select--</option>
                            <?php
								$sql_t_CC  = "SELECT ID_CC, COMP_NAME FROM t_companycode where id_cc <> 'ID01' order by ID_CC";
								$result_t_CC = oci_parse($con, $sql_t_CC);
											   oci_execute($result_t_CC, OCI_DEFAULT);
								while (oci_fetch($result_t_CC)) {	
											   $id_prov= oci_result($result_t_CC, "ID_CC");
											   $nama_prov= oci_result($result_t_CC, "COMP_NAME");
											   echo "<option value=\"$id_prov\">$id_prov - $nama_prov</option>\n";
						    }?>
                          </select>
                       </td>
                      </tr>
                      <tr>
                        <td width="150" height="29" valign="top">Business Area</td>
						<td><select name="kota" id="kota" >
                            <option value=""  selected="selected">--select--</option>
                            <?php
									$sql_t_BA  = "SELECT ID_BA, NAMA_BA FROM T_BUSSINESSAREA ORDER BY ID_BA";
									$result_t_BA = oci_parse($con, $sql_t_BA);
												   oci_execute($result_t_BA, OCI_DEFAULT);
									while ($p=oci_fetch($result_t_BA)) {	
												  $id_kabkot = oci_result($result_t_BA, "ID_BA");
												  $nama_kabkot = oci_result($result_t_BA, "NAMA_BA");
									     		  echo "<option value=\"$id_kabkot\">$id_kabkot - $nama_kabkot</option>\n";
									}
									?>
                           </select>
                       </td>
                      </tr>
					  <tr>
                        <td width="150" height="29" valign="top">Bunch Type</td>
						<td><select name="bunch" id="bunch">
                            <option value=""  selected="selected">--select--</option>
                            <?php
									$sql_t_bunch  = "SELECT DISTINCT KETERANGAN FROM T_PARAMETER_BUNCH";
									$resultBunch = oci_parse($con, $sql_t_bunch);
												   oci_execute($resultBunch, OCI_DEFAULT);
									while (oci_fetch($resultBunch)) {	
									     		  echo "<option value='".oci_result($resultBunch, "KETERANGAN")."'>".oci_result($resultBunch, "KETERANGAN")."</option>\n";
									}
									?>
                           </select>
                       </td>
                      </tr>
					  <tr>
						<td colspan="2"><div id="res_par"></div></td>
					  </tr>
                      <tr>
                        <td  align="left" colspan="2">
                          <input type="submit" name="button" id="button" value="SIMPAN" style="width:120px; height: 30px"/>
                          <input type="button" value="BATAL" onclick="resFrm()" style="width:120px; height: 30px"/>  
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
