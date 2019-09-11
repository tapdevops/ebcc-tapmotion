<?php
session_start();
include("../include/Header.php");
if(isset($_SESSION['Job_Code']) 	&& isset($_SESSION['NIK']) 			&& isset($_SESSION['Name']) 
&& isset($_SESSION['Jenis_Login'])  && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) 
&& isset($_SESSION['Comp_Name'])){	

$Job_Code 		= $_SESSION['Job_Code'];
$username 		= $_SESSION['NIK'];	
$Emp_Name 		= $_SESSION['Name'];
$Jenis_Login 	= $_SESSION['Jenis_Login'];
$Comp_Name 		= $_SESSION['Comp_Name'];
$subID_BA_Afd 	= $_SESSION['subID_BA_Afd'];
$Date 			= $_SESSION['Date'];
$ID_Group_BA 	= $_SESSION['ID_Group_BA'];

	
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
		SELECT 
			THP.NO_BCC, 
			THRP.TANGGAL_RENCANA TANGGAL, 
			F_GET_IDBA_IDR (TDRP.ID_RENCANA,TDRP.ID_BA_AFD_BLOK, TDRP.NO_REKAP_BCC) AS BA, 
			F_GET_IDAFD_IDR (TDRP.ID_RENCANA,TDRP.ID_BA_AFD_BLOK) AS AFD, 
			F_GET_NAMABLOK_IDR(TDRP.ID_RENCANA,TDRP.ID_BA_AFD_BLOK) AS BLOK, 
			THP.NO_TPH, 
			THP.LONGITUDE, 
			THP.LATITUDE, 
			THP.PICTURE_NAME, 
			-- to_char(TTS.START_INS_TIME, 'dd-mm-yyyy HH24:MI:SS') as start_ins_time,
			-- to_char(TTS.END_INS_TIME, 'dd-mm-yyyy HH24:MI:SS') as end_ins_time, 
			-- to_char(TTS.START_UPD_TIME, 'dd-mm-yyyy HH24:MI:SS') as start_upd_time, 
			-- to_char(TTS.END_UPD_TIME, 'dd-mm-yyyy HH24:MI:SS') as end_upd_time,  
			F_GET_IDBA_NIK(THRP.NIK_MANDOR) IDBA_MANDOR, 
			F_GET_IDAFD_NIK(THRP.NIK_MANDOR) IDAFD_MANDOR, 
			THRP.NIK_MANDOR, 
			F_GET_EMPNAME (THRP.NIK_MANDOR) NAMA_MANDOR, 
			F_GET_IDBA_NIK(THRP.NIK_PEMANEN) IDBA_PEMANEN, 
			F_GET_IDAFD_NIK(THRP.NIK_PEMANEN) IDAFD_PEMANEN, 
			THRP.NIK_PEMANEN, F_GET_EMPNAME (THRP.NIK_PEMANEN) NAMA_PEMANEN 
		FROM T_HEADER_RENCANA_PANEN THRP 
			INNER JOIN T_DETAIL_RENCANA_PANEN TDRP ON THRP.ID_RENCANA = TDRP.ID_RENCANA 
			INNER JOIN T_HASIL_PANEN THP on tdrp.id_rencana = thp.id_rencana AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC 
			INNER JOIN T_BLOK TB ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK 
			INNER JOIN T_AFDELING TA ON TB.ID_BA_AFD = TA.ID_BA_AFD 
			INNER JOIN T_BUSSINESSAREA TBA ON TA.ID_BA = TBA.ID_BA
			--inner join T_TIMESTAMP TTS on THP.NO_BCC = REPLACE(ID_TIMESTAMP, '.')
		WHERE THP.NO_BCC = '$NO_BCC'";
		//echo $sql_t_BCC;die();
		$result_t_BCC = oci_parse($con, $sql_t_BCC);
		oci_execute($result_t_BCC, OCI_DEFAULT);
		while(oci_fetch($result_t_BCC)){
			$NO_BCC 				= oci_result($result_t_BCC, "NO_BCC");
			$TANGGAL 				= oci_result($result_t_BCC, "TANGGAL");
			$BA 					= oci_result($result_t_BCC, "BA");
			$AFD					= oci_result($result_t_BCC, "AFD");
			$BLOK 					= oci_result($result_t_BCC, "BLOK");
			$NO_TPH 				= oci_result($result_t_BCC, "NO_TPH");
			$START_INS_TIME 		= oci_result($result_t_BCC, "START_INS_TIME");
			$END_INS_TIME 			= oci_result($result_t_BCC, "END_INS_TIME");
			$START_UPD_TIME 		= oci_result($result_t_BCC, "START_UPD_TIME");
			$END_UPD_TIME 			= oci_result($result_t_BCC, "END_UPD_TIME");
			$LONGITUDE 				= oci_result($result_t_BCC, "LONGITUDE");
			$LATITUDE 				= oci_result($result_t_BCC, "LATITUDE");
			$PICTURE_NAME 			= oci_result($result_t_BCC, "PICTURE_NAME");
			$IDBA_MANDOR 			= oci_result($result_t_BCC, "IDBA_MANDOR");
			$IDAFD_MANDOR 			= oci_result($result_t_BCC, "IDAFD_MANDOR");
			$NIK_MANDOR 			= oci_result($result_t_BCC, "NIK_MANDOR");
			$NAMA_MANDOR 			= oci_result($result_t_BCC, "NAMA_MANDOR");	
			$IDBA_PEMANEN 			= oci_result($result_t_BCC, "IDBA_PEMANEN");
			$IDAFD_PEMANEN 			= oci_result($result_t_BCC, "IDAFD_PEMANEN");
			$NIK_PEMANEN 			= oci_result($result_t_BCC, "NIK_PEMANEN");
			$NAMA_PEMANEN 			= oci_result($result_t_BCC, "NAMA_PEMANEN");		
		}
		$roweffec_BCC = oci_num_rows($result_t_BCC);
		
		$sql_t_BCC = "
		SELECT 
			to_char(TTS.START_INS_TIME, 'dd-mm-yyyy HH24:MI:SS') as start_ins_time,
			to_char(TTS.END_INS_TIME, 'dd-mm-yyyy HH24:MI:SS') as end_ins_time, 
			to_char(TTS.START_UPD_TIME, 'dd-mm-yyyy HH24:MI:SS') as start_upd_time, 
			to_char(TTS.END_UPD_TIME, 'dd-mm-yyyy HH24:MI:SS') as end_upd_time 
		FROM T_HEADER_RENCANA_PANEN THRP 
			INNER JOIN T_DETAIL_RENCANA_PANEN TDRP ON THRP.ID_RENCANA = TDRP.ID_RENCANA 
			INNER JOIN T_HASIL_PANEN THP on tdrp.id_rencana = thp.id_rencana AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC 
			INNER JOIN T_BLOK TB ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK 
			INNER JOIN T_AFDELING TA ON TB.ID_BA_AFD = TA.ID_BA_AFD 
			INNER JOIN T_BUSSINESSAREA TBA ON TA.ID_BA = TBA.ID_BA
			inner join T_TIMESTAMP TTS on THP.NO_BCC = REPLACE(ID_TIMESTAMP, '.')
		WHERE THP.NO_BCC = '$NO_BCC'";
		$result_t_BCC = oci_parse($con, $sql_t_BCC);
		oci_execute($result_t_BCC, OCI_DEFAULT);
		while(oci_fetch($result_t_BCC)){
			$START_INS_TIME 		= oci_result($result_t_BCC, "START_INS_TIME");
			$END_INS_TIME 			= oci_result($result_t_BCC, "END_INS_TIME");
			$START_UPD_TIME 		= oci_result($result_t_BCC, "START_UPD_TIME");
			$END_UPD_TIME 			= oci_result($result_t_BCC, "END_UPD_TIME");		
		}
		$roweffec_BCC = oci_num_rows($result_t_BCC);
	
	
	
		$sql_t_BCC = "
		SELECT INSERTUPDATE,
				TO_CHAR (min(TLHPK.SYNC_SERVER), 'dd-mm-yyyy HH24:MI:SS')
				  AS START_TIME,
				TO_CHAR (max(TLHPK.SYNC_SERVER), 'dd-mm-yyyy HH24:MI:SS')
				  AS END_TIME
		  FROM T_HEADER_RENCANA_PANEN THRP
			   INNER JOIN T_DETAIL_RENCANA_PANEN TDRP
				  ON THRP.ID_RENCANA = TDRP.ID_RENCANA
			   INNER JOIN T_HASIL_PANEN THP
				  ON tdrp.id_rencana = thp.id_rencana
					 AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC
			   INNER JOIN T_BLOK TB
				  ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
			   INNER JOIN T_AFDELING TA
				  ON TB.ID_BA_AFD = TA.ID_BA_AFD
			   INNER JOIN T_BUSSINESSAREA TBA
				  ON TA.ID_BA = TBA.ID_BA
			   INNER JOIN T_LOG_HASILPANEN_KUALITAS TLHPK
				  ON TLHPK.ON_ID_BCC_KUALITAS LIKE  '$NO_BCC%'
		 WHERE THP.NO_BCC = '$NO_BCC'
		 group by INSERTUPDATE order by INSERTUPDATE";
		$result_t_BCC = oci_parse($con, $sql_t_BCC);
		oci_execute($result_t_BCC, OCI_DEFAULT);
		while(oci_fetch($result_t_BCC)){
			if(oci_result($result_t_BCC, "INSERTUPDATE") == 'INSERT'){
				if($START_INS_TIME == '' && $END_INS_TIME == ''){
					$START_INS_TIME 		= oci_result($result_t_BCC, "START_TIME");
					$END_INS_TIME 			= oci_result($result_t_BCC, "END_TIME");
				}
			}else if(oci_result($result_t_BCC, "INSERTUPDATE") == 'UPDATE'){
				if($START_UPD_TIME == '' && $END_UPD_TIME == ''){
					$START_UPD_TIME 		= oci_result($result_t_BCC, "START_TIME");
					$END_UPD_TIME 			= oci_result($result_t_BCC, "END_TIME");
				}
			}					
		}
		$roweffec_BCC = oci_num_rows($result_t_BCC);
	}
	
?>
<link href="tabel.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
function tablePrint()
{

var display_setting="toolbar=no,location=no,directories=no,menubar=no,";
display_setting+="scrollbars=yes,width=750, height=600, left=100, top=25";

var content_innerhtml = document.getElementById("tbl_display").innerHTML;
var document_print=window.open("","",display_setting);
document_print.document.open();
document_print.document.write('<html><head><title>DRadio </title></head>');
//document_print.document.write('<body  onLoad="self.print();self.close();" >');
document_print.document.write(content_innerhtml);
document_print.document.write('</body></html>');
document_print.print();
//document_print.document.close();
return false;
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

<table width="1079" height="390" border="0" align="center" id="setbody2">
   <tr>
		<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>LAPORAN DATA BCC</strong></span></td>
    </tr>
  <tr>
    <th height="197" scope="row" align="LEFT"> <table width="1031" class="bordered" id="tbl_display">
        <tr>
          <td align="center">
            <table width=100% >  
           
        
               <tr>
                <td width="117" valign="top">No BCC</td>
                <td width="373" valign="top"><?=separator($NO_BCC);?></td>
                <td width="128" valign="top">Tgl Panen</td>
                <td width="278" valign="top"><?=$TANGGAL?></td>
                
              </tr>
			  <tr>
			  <td width="228" valign="top">Mulai Input</td>
                <td width="378" valign="top"><?=$START_INS_TIME?></td>
				<td width="228" valign="top">Selesai Input</td>
                <td width="378" valign="top"><?=$END_INS_TIME?></td>
			  </tr>
			  <tr>
			  <td width="228" valign="top">Mulai Ubah</td>
                <td width="378" valign="top"><?=$START_UPD_TIME?></td>
			  <td width="228" valign="top">Selesai Ubah</td>
                <td width="378" valign="top"><?=$END_UPD_TIME?></td>
			  </tr>
              <tr>
                <td colspan="6" bgcolor="#9CC346">Lokasi Panen</td>
              </tr>
              <tr>
                <td width="217" valign="top">Business Area</td>
                <td width="273" valign="top"><?=$BA?></td>
                <td colspan="2" rowspan="6" valign="top"><img src="../array/uploads/<?=$PICTURE_NAME?>" width="275" height="200" alt="<?=$PICTURE_NAME?>" align="absmiddle" ></td>
              </tr>
              <tr>
                <td>Afdeling</td>
                <td><?=$AFD?></td>
              </tr>
              <tr>
                <td width="117" valign="top">Blok</td>
                <td width="373" valign="top"><?=$BLOK?></td>
              </tr>
              <tr>
                <td>TPH</td>
                <td><?=$NO_TPH?></td>
              </tr>
              <tr>
                <td width="117" valign="top">Longitude</td>
                <td width="373" valign="top"><?=$LONGITUDE?></td>
              </tr>
              <tr>
                <td>Latitude</td>
                <td><?=$LATITUDE?></td>
              </tr>
              <tr>
                <td colspan="6" bgcolor="#9CC346">Mandor</td>
              </tr>
              <tr>
                <td width="117" valign="top">Business Area</td>
                <td width="373" valign="top"><?=$IDBA_MANDOR?></td>
                <td width="228" valign="top">Nama Mandor</td>
                <td width="278" valign="top"><?=$NAMA_MANDOR?></td>
              </tr>
              <tr>
                <td>Afdeling</td>
                <td><?=$IDAFD_MANDOR?></td>
                <td>NIK Mandor</td>
                <td colspan="3"><?=$NIK_MANDOR?>
                </td>
              </tr>
              <tr>
                <td colspan="6" bgcolor="#9CC346">Pemanen</td>
              </tr>
              <tr>
                <td width="117" valign="top">Business Area</td>
                <td width="373" valign="top"><?=$IDBA_PEMANEN?></td>
                <td width="228" valign="top">Nama Pemanen</td>
                <td width="278" colspan='3' valign="top"><?=$NAMA_PEMANEN?></td>
              </tr>
              <tr>
                <td>Afdeling</td>
                <td><?=$IDAFD_PEMANEN?></td>
                <td>NIK Pemanen</td>
                <td colspan="3"><?=$NIK_PEMANEN?>
                </td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td>
            <TABLE width="450" class="bordered" bgcolor="#FFFFFF" align="center">
      
              <?
			  echo "<TR><TD colspan='4' align='center' >Total Hasil Panen</TD></TR>";
			  $sql_t_hasil = "SELECT K.ID_KUALITAS, K.NAMA_KUALITAS, K.UOM, H.QTY  FROM  t_kualitas_panen K,
			  t_hasilpanen_kualtas H  WHERE  H.ID_BCC(+)='$NO_BCC' AND K.ID_KUALITAS = H.ID_KUALITAS(+) AND GROUP_KUALITAS='HASIL PANEN' AND UOM<>'KG'
			  ORDER BY ID_KUALITAS ASC";
			  //echo $sql_t_BCC;
		//die ();
			  $result_t_hasil = oci_parse($con, $sql_t_hasil);
			  oci_execute($result_t_hasil, OCI_DEFAULT);
			  $TOTAL=0;
			  while(oci_fetch($result_t_hasil)){
							$UOM 				        = oci_result($result_t_hasil, "UOM");
							$NAMA_KUALITAS 				= oci_result($result_t_hasil, "NAMA_KUALITAS");
							$QTY 					    = oci_result($result_t_hasil, "QTY");		
							$TOTAL=$TOTAL+$QTY;  
			  echo "<TR><TD>$NAMA_KUALITAS</TD><TD align='right'>$QTY&nbsp;</TD><TD>$UOM </TD</TR>";
			 }
			  
              $sql_t_hasil0 = "SELECT K.ID_KUALITAS, K.NAMA_KUALITAS, K.UOM, H.QTY  FROM  t_kualitas_panen K,
			  t_hasilpanen_kualtas H  WHERE  H.ID_BCC(+)='$NO_BCC' AND K.ID_KUALITAS = H.ID_KUALITAS(+) AND GROUP_KUALITAS='HASIL PANEN' AND UOM='KG'
			  ORDER BY ID_KUALITAS ASC";
			  $result_t_hasil0 = oci_parse($con, $sql_t_hasil0);
			  oci_execute($result_t_hasil0, OCI_DEFAULT);
			  while(oci_fetch($result_t_hasil0)){
							$UOM0 				        = oci_result($result_t_hasil0, "UOM");
							$NAMA_KUALITAS0 			= oci_result($result_t_hasil0, "NAMA_KUALITAS");
							$QTY0 					    = oci_result($result_t_hasil0, "QTY");		 
			  echo "<TR><TD>$NAMA_KUALITAS0</TD><TD align='right'>$QTY0&nbsp;</TD><TD>$UOM0 </TD</TR>";
			 }
			  echo "<TR bgcolor='#9CC346'><TD>Total Janjang Panen</TD><TD align='right'>$TOTAL&nbsp;</TD><TD>JJG</TD></TR>";
			  echo "<TR><TD colspan='4' align='center' >Informasi Tambahan Kondisi Buah</TD></TR>";
			 
			 $sql_t_hasil1 = "SELECT K.ID_KUALITAS, K.NAMA_KUALITAS, K.UOM, H.QTY  FROM  t_kualitas_panen K,
			 t_hasilpanen_kualtas H  
        	 WHERE  H.ID_BCC(+)='$NO_BCC' AND K.ID_KUALITAS = H.ID_KUALITAS(+) AND GROUP_KUALITAS='KONDISI BUAH'
             ORDER BY ID_KUALITAS ASC";
	
			 $result_t_hasil1 = oci_parse($con, $sql_t_hasil1);
			 oci_execute($result_t_hasil1, OCI_DEFAULT);
			 $TOTAL1=0;
			 while(oci_fetch($result_t_hasil1)){
							$UOM1 				= oci_result($result_t_hasil1, "UOM");
							$NAMA_KUALITAS1				= oci_result($result_t_hasil1, "NAMA_KUALITAS");
							$QTY1					    = oci_result($result_t_hasil1, "QTY");		
							$TOTAL1=$TOTAL1+$QTY1;  
			echo "<TR><TD>$NAMA_KUALITAS1</TD><TD align='right'>$QTY1&nbsp;</TD><TD>$UOM1</TD></TR>";
			}
		    echo "<TR><TD colspan='4' align='center' >Informasi Penalti Tambahan</TD></TR>"; 
			$sql_t_hasil3 = "SELECT K.ID_KUALITAS, K.NAMA_KUALITAS, K.UOM, H.QTY  FROM  t_kualitas_panen K,
			t_hasilpanen_kualtas H  
        	WHERE  H.ID_BCC(+)='$NO_BCC' AND K.ID_KUALITAS = H.ID_KUALITAS(+) AND GROUP_KUALITAS='PENALTY DI TPH'
            ORDER BY ID_KUALITAS ASC";
	
			$result_t_hasil3 = oci_parse($con, $sql_t_hasil3);
			oci_execute($result_t_hasil3, OCI_DEFAULT);
			$TOTAL3=0;
			while(oci_fetch($result_t_hasil3)){
							$UOM3 				        = oci_result($result_t_hasil3, "UOM");
							$NAMA_KUALITAS3 			= oci_result($result_t_hasil3, "NAMA_KUALITAS");
							$QTY3 					    = oci_result($result_t_hasil3, "QTY");		
							$TOTAL3=$TOTAL3+$QTY3;  
			echo "<TR><TD>$NAMA_KUALITAS3</TD><TD align='right'>$QTY3&nbsp;</TD><TD>$UOM3</TD></TR>";
			}
       
			$sql_t_hasil2 = "SELECT K.ID_KUALITAS, K.NAMA_KUALITAS, K.UOM, H.QTY  FROM  t_kualitas_panen K,
			t_hasilpanen_kualtas H   WHERE  H.ID_BCC(+)='$NO_BCC' AND K.ID_KUALITAS = H.ID_KUALITAS(+) AND GROUP_KUALITAS='PENALTY MANDOR'
            ORDER BY ID_KUALITAS ASC
			";
		//echo "$sql_t_hasil2";
			$result_t_hasil2 = oci_parse($con, $sql_t_hasil2);
			oci_execute($result_t_hasil2, OCI_DEFAULT);
			$TOTAL2=0;
			while(oci_fetch($result_t_hasil2)){
				$UOM2 				= oci_result($result_t_hasil2, "UOM");
				$NAMA_KUALITAS2 				= oci_result($result_t_hasil2, "NAMA_KUALITAS");
				$QTY2 					    = oci_result($result_t_hasil2, "QTY");		
				$TOTAL2=$TOTAL2+$QTY2;  
			echo "<TR><TD>$NAMA_KUALITAS2</TD><TD align='right'>$QTY2&nbsp;</TD><TD>$UOM2</TD></TR>";

			}
//$TOTP=0;
//$TOTP=$TOTP+$TOTAL2+$TOTAL3;
//echo "<TR bgcolor='#9CC346'><TD>&nbsp;</TD><TD align='right'>$TOTP&nbsp;</TD></TR>";
//$JUMLAH=0;
//$JUMLAH=$JUMLAH+$TOTAL+$TOTAL1+$TOTAL2+$TOTAL3;
//echo "<TR ><TD>Total</TD><TD align='right'>$JUMLAH&nbsp;</TD></TR>";
?>
            </TABLE>
            <br> 
            </td>
        </tr>
      </table></th>
  </tr>
  <tr>
  <td align="center"><input type="button" value="Print" onClick="window.print()">&nbsp;<input type="button" class="box2" value="Back" onClick="javascript: history.go(-1);"></td>
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
	$_SESSION[err] = "tolong login dulu!".$Job_Code."<br>".$username."<br>".$Emp_Name."<br>".$Jenis_Login."<br>".$subID_BA_Afd;
	header("location:../index.php");
}
?>
