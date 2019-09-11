<?php

        include("../config/SQL_function.php"); 
		include("../config/db_config.php");
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');

		$afdeling = $_GET['afdeling'];
		$tgl_panen = $_GET['var_tgl'];
		$buss_area = substr($afdeling, 0, -1);
		$nik = $_GET['nik'];
		$sql_new  = "SELECT *
  FROM (  SELECT NIK_PEMANEN,
                 EMP_NAME,
                 JOB_CODE,
                 blok,
                 ID_BA_AFD_BLOK,
                 MAX (luasan_panen) luasan_panen
            FROM (  SELECT NIK_PEMANEN,
                           EMP_NAME,
                           JOB_CODE,
                           SUBSTR (ID_BA_AFD_BLOK, 6, 3) AS blok,
                           ID_BA_AFD_BLOK,
                           TDRP.ID_RENCANA,
                           luasan_panen from T_HEADER_RENCANA_PANEN THRP 
                         LEFT JOIN T_DETAIL_RENCANA_PANEN TDRP ON (THRP.ID_RENCANA = TDRP.ID_RENCANA)
                         LEFT JOIN T_HASIL_PANEN THP ON (THRP.ID_RENCANA = THP.ID_RENCANA AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC)
                         LEFT JOIN T_EMPLOYEE TE ON (TE.NIK = THRP.NIK_PEMANEN)
                         WHERE TANGGAL_RENCANA = TO_DATE('$tgl_panen', 'MM/DD/YYYY HH24:MI:SS') and 
						 NIK_PEMANEN LIKE '$nik' and ID_BA_AFD_BLOK like '$afdeling%' 
						 GROUP BY NIK_PEMANEN,
                           EMP_NAME,
                           JOB_CODE,
                           SUBSTR (ID_BA_AFD_BLOK, 6, 3),
                           ID_BA_AFD_BLOK,
                           luasan_panen,
                           TDRP.ID_RENCANA
                  ORDER BY luasan_panen DESC)
        GROUP BY NIK_PEMANEN,
                 EMP_NAME,
                 JOB_CODE,
                 blok,
                 ID_BA_AFD_BLOK)
 WHERE luasan_panen = 0";
// echo $sql_new; exit;
		$rthp = oci_parse($con, $sql_new);
		oci_execute($rthp, OCI_DEFAULT);
		oci_fetch($rthp);
		//$jml = oci_result($rthp, "JML");
		$jml = oci_num_rows($rthp);
		//echo $jml; exit;
		if($jml != "0"){
			
			echo "<tr>
					<td rowspan='2' bgcolor='#75d88e' align='center'>No	</td>
					<td rowspan='2' bgcolor='#75d88e' style='width:70px; height:10px' align='center'>BLOK</td>
					<td rowspan='2' bgcolor='#75d88e' style='width:70px; height:10px' align='center'>LUASAN </br>PANEN</td>
					<td colspan='4' bgcolor='#75d88e' style='width:660px; height:10px' align='center'>PENALTI PANEN</td>
				  </tr>
				  <tr>
					<td bgcolor='#75d88e' style='width:165px; height:10px' align='center'>Buah Tinggal (Pokok)</td>
					<td bgcolor='#75d88e' style='width:165px; height:10px' align='center'>Buah Tinggal (Piringan)</td>
					<td bgcolor='#75d88e' style='width:165px; height:10px' align='center'>Penalti Brondolan (Piringan)</td>
					<td bgcolor='#75d88e' style='width:165px; height:10px' align='center'>Buah Matahari</td>
				  </tr>";
			
			$i = 1;
			$rthp2 = oci_parse($con, $sql_new);
			oci_execute($rthp2, OCI_DEFAULT);
		
			while (oci_fetch($rthp2)) {
				$sql_t_HP  = "Select NIK_PEMANEN, 
								EMP_NAME, 
								JOB_CODE, 
								substr(tdrp.ID_BA_AFD_BLOK,6,3) as BLOK,
								tdrp.ID_BA_AFD_BLOK,
								id_afd,
								THP.NO_REKAP_BCC as NO_REKAP_BCC,
								NO_BCC,
								TDRP.ID_RENCANA as ID_RENCANA,
								TC.ID_CC,
								TBA.PROFILE_NAME,
								to_char(tanggal_rencana, 'MM/DD/YYYY') as tanggal_rencana
							 from T_HEADER_RENCANA_PANEN THRP 
							 LEFT JOIN T_DETAIL_RENCANA_PANEN TDRP ON (THRP.ID_RENCANA = TDRP.ID_RENCANA)
							 LEFT JOIN T_HASIL_PANEN THP ON (THRP.ID_RENCANA = THP.ID_RENCANA AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC)
							 LEFT JOIN T_EMPLOYEE TE ON (TE.NIK = THRP.NIK_PEMANEN)
							 LEFT JOIN t_blok tb
								ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
							 LEFT JOIN t_afdeling ta
								ON tb.id_ba_afd = ta.id_ba_afd
							 LEFT JOIN t_bussinessarea tba
								ON tba.id_ba = ta.id_ba
							 LEFT JOIN t_companycode tc
								ON tba.id_cc = tc.id_cc
							 WHERE TANGGAL_RENCANA = TO_DATE('$tgl_panen', 'MM/DD/YYYY HH24:MI:SS') and 
							 NIK_PEMANEN LIKE '".oci_result($rthp2, "NIK_PEMANEN")."' and tdrp.ID_BA_AFD_BLOK like '$afdeling%' and
							 substr(tdrp.ID_BA_AFD_BLOK,6,3) = '".oci_result($rthp2, "BLOK")."'
							 order by BLOK";
				$result_t_HP = oci_parse($con, $sql_t_HP);
				//echo $sql_t_HP."<br>"; //exit;
				oci_execute($result_t_HP, OCI_DEFAULT);
				
				while ($p=oci_fetch($result_t_HP)) {
					//Edited by Ardo, 29-09-2016
					$query_cek_export = "
					SELECT * FROM T_STATUS_TO_SAP_EBCC WHERE 
					COMP_CODE = '".oci_result($result_t_HP, "ID_CC")."' AND 
					PROFILE_NAME = '".oci_result($result_t_HP, "PROFILE_NAME")."' AND 
					TANGGAL = '".date('d.m.Y',strtotime(oci_result($result_t_HP, "TANGGAL_RENCANA")))."' AND 
					NIK_PEMANEN = '".oci_result($result_t_HP, "NIK_PEMANEN")."' AND 
					BLOCK = '".oci_result($result_t_HP, "BLOK")."' AND
					((EXPORT_STATUS IS NOT NULL AND EXPORT_TIMESTAMP IS NOT NULL)
					   OR (POST_STATUS IS NOT NULL AND POST_TIMESTAMP IS NOT NULL))
					";
					//echo $query_cek_export."<br>";
					$result_export_cek = oci_parse($con, $query_cek_export);
					oci_execute($result_export_cek, OCI_DEFAULT);
					$result_export_count = oci_fetch_all($result_export_cek, $result_export_record, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
					//echo 'woy '.$result_export_count."<br>";
					if($result_export_count==0){
						if($i==1){
							$blok = oci_result($result_t_HP, "BLOK");
							$id_rencana = oci_result($result_t_HP, "ID_RENCANA");
							$no_rekap_bcc = oci_result($result_t_HP, "NO_REKAP_BCC");
							$id_ba_afd_blok = oci_result($result_t_HP, "ID_BA_AFD_BLOK");
							$no_bcc = oci_result($result_t_HP, "NO_BCC");
							
							$TGL_RENCANA = oci_result($result_t_HP, "TANGGAL_RENCANA");
							$ID_AFD = oci_result($result_t_HP, "ID_AFD");
							$NIK_PEMANEN = oci_result($result_t_HP, "NIK_PEMANEN");
							
							echo "<tr>";
							echo "<td align='center'>$i</td>";
							echo "<td align='center'><input name='t_blok$i' type='text' value='$blok' id='t_blok$i' style='width:70px; height:20px' readonly='readonly'/></td>";
							echo "<input name='id_rencana$i' type='hidden' id='id_rencana$i' value='$id_rencana' readonly='readonly'/>\n";
							echo "<input name='no_rekap_bcc$i' type='hidden' id='no_rekap_bcc$i' value='$no_rekap_bcc' readonly='readonly'/>>\n";
							echo "<input name='id_ba_afd_blok$i' type='hidden' id='id_ba_afd_blok$i' value='$id_ba_afd_blok' readonly='readonly'/>\n";
							echo "<input name='no_bcc$i' type='hidden' id='no_bcc$i' value='$no_bcc' readonly='readonly'/>\n";
							echo "<td align='center'><input name='t_luasan_panen$i' type='text' id='t_luasan_panen$i' onkeypress='return isNumber(event)' style='width:70px; height:20px' /></td>
								<td align='center'><input name='t_bt_pokok$i' type='text' id='t_bt_pokok$i' value='0' style='width:165px; height:20px' onkeypress='return isNumber(event)' onblur='changeformat(this)'/></td>
								<td align='center'><input name='t_bt_piringan$i' type='text' id='t_bt_piringan$i' value='0' style='width:165px; height:20px' onkeypress='return isNumber(event)' onblur='changeformat(this)'/></td>
								<td align='center'><input name='t_pb_piringan$i' type='text' id='t_pb_piringan$i' value='0' style='width:165px; height:20px' onkeypress='return isNumber(event)' onblur='changeformat(this)'/></td>
								<td align='center'><input name='t_buahmatahari$i' type='text' id='t_buahmatahari$i' value='0' style='width:165px; height:20px' onkeypress='return isNumber(event)' onblur='changeformat(this)'/></td>";
							echo "</tr>";
							$i++;
						} else {
							if($TGL_RENCANA!==oci_result($result_t_HP, "TANGGAL_RENCANA") || $ID_AFD!==oci_result($result_t_HP, "ID_AFD") || $blok!==oci_result($result_t_HP, "BLOK") || $NIK_PEMANEN!==oci_result($result_t_HP, "NIK_PEMANEN")){
								$blok = oci_result($result_t_HP, "BLOK");
								$id_rencana = oci_result($result_t_HP, "ID_RENCANA");
								$no_rekap_bcc = oci_result($result_t_HP, "NO_REKAP_BCC");
								$id_ba_afd_blok = oci_result($result_t_HP, "ID_BA_AFD_BLOK");
								$no_bcc = oci_result($result_t_HP, "NO_BCC");
								
								$TGL_RENCANA = oci_result($result_t_HP, "TANGGAL_RENCANA");
								$ID_AFD = oci_result($result_t_HP, "ID_AFD");
								$NIK_PEMANEN = oci_result($result_t_HP, "NIK_PEMANEN");
								
								echo "<tr>";
								echo "<td align='center'>$i</td>";
								echo "<td align='center'><input name='t_blok$i' type='text' value='$blok' id='t_blok$i' style='width:70px; height:20px' readonly='readonly'/></td>";
								echo "<input name='id_rencana$i' type='hidden' id='id_rencana$i' value='$id_rencana' readonly='readonly'/>\n";
								echo "<input name='no_rekap_bcc$i' type='hidden' id='no_rekap_bcc$i' value='$no_rekap_bcc' readonly='readonly'/>>\n";
								echo "<input name='id_ba_afd_blok$i' type='hidden' id='id_ba_afd_blok$i' value='$id_ba_afd_blok' readonly='readonly'/>\n";
								echo "<input name='no_bcc$i' type='hidden' id='no_bcc$i' value='$no_bcc' readonly='readonly'/>\n";
								echo "<td align='center'><input name='t_luasan_panen$i' type='text' id='t_luasan_panen$i' onkeypress='return isNumber(event)' style='width:70px; height:20px' /></td>
									<td align='center'><input name='t_bt_pokok$i' type='text' id='t_bt_pokok$i' value='0' style='width:165px; height:20px' onkeypress='return isNumber(event)' onblur='changeformat(this)'/></td>
									<td align='center'><input name='t_bt_piringan$i' type='text' id='t_bt_piringan$i' value='0' style='width:165px; height:20px' onkeypress='return isNumber(event)' onblur='changeformat(this)'/></td>
									<td align='center'><input name='t_pb_piringan$i' type='text' id='t_pb_piringan$i' value='0' style='width:165px; height:20px' onkeypress='return isNumber(event)' onblur='changeformat(this)'/></td>
									<td align='center'><input name='t_buahmatahari$i' type='text' id='t_buahmatahari$i' value='0' style='width:165px; height:20px' onkeypress='return isNumber(event)' onblur='changeformat(this)'/></td>";
								echo "</tr>";
								$i++;
							}
						}
					}
				}
				//added by Ardo, 12-11-2016 : bugs input luasan panen yg kesimpen cuman 1
				echo"<input name='countRow' type='hidden' id='countRow' colspan='7' value='".($i-1)."' style='width:70px; height:20px' readonly='readonly'/>";
			}
			echo "</table>";
		}else{
			echo "<b style='font-size:30px'>Tidak ada Aktivitas Panen untuk karyawan di Afdeling Tersebut</b>";
		}
?>
