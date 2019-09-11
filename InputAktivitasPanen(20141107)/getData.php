<?php

        include("../config/SQL_function.php"); 
		include("../config/db_config.php");
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');

		$afdeling = $_GET['afdeling'];
		$tgl_panen = $_GET['var_tgl'];
		$buss_area = substr($afdeling, 0, -1);
		$nik = $_GET['nik'];
		$sql_t_HP  = "Select count(JML) as JML from (
						Select count(NIK) as JML from T_HEADER_RENCANA_PANEN THRP 
                         LEFT JOIN T_DETAIL_RENCANA_PANEN TDRP ON (THRP.ID_RENCANA = TDRP.ID_RENCANA)
                         LEFT JOIN T_HASIL_PANEN THP ON (THRP.ID_RENCANA = THP.ID_RENCANA AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC)
                         LEFT JOIN T_EMPLOYEE TE ON (TE.NIK = THRP.NIK_PEMANEN)
                         WHERE TANGGAL_RENCANA = TO_DATE('$tgl_panen', 'MM/DD/YYYY HH24:MI:SS') AND LUASAN_PANEN = '0' and 
						 NIK_PEMANEN LIKE '$nik' and ID_BA_AFD_BLOK like '$afdeling%' group by NIK_PEMANEN, EMP_NAME, JOB_CODE,substr(ID_BA_AFD_BLOK,6,3), ID_BA_AFD_BLOK, THP.NO_REKAP_BCC, TDRP.ID_RENCANA) a";
		$result_t_HP = oci_parse($con, $sql_t_HP);
		oci_execute($result_t_HP, OCI_DEFAULT);
		$p=oci_fetch($result_t_HP);
		$jml = oci_result($result_t_HP, "JML");
		//echo $sql_t_HP;
		if($jml != "0"){
			$i = 1;
			$sql_t_HP  = "Select distinct(NIK_PEMANEN) as NIK_PEMANEN, 
                            EMP_NAME, 
                            JOB_CODE, 
                            substr(ID_BA_AFD_BLOK,6,3) as BLOK,
                            ID_BA_AFD_BLOK,
                            THP.NO_REKAP_BCC as NO_REKAP_BCC,
                            min(THP.NO_BCC) as NO_BCC,
                            TDRP.ID_RENCANA as ID_RENCANA
                         from T_HEADER_RENCANA_PANEN THRP 
                         LEFT JOIN T_DETAIL_RENCANA_PANEN TDRP ON (THRP.ID_RENCANA = TDRP.ID_RENCANA)
                         LEFT JOIN T_HASIL_PANEN THP ON (THRP.ID_RENCANA = THP.ID_RENCANA AND TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC)
                         LEFT JOIN T_EMPLOYEE TE ON (TE.NIK = THRP.NIK_PEMANEN)
                         WHERE TANGGAL_RENCANA = TO_DATE('$tgl_panen', 'MM/DD/YYYY HH24:MI:SS') AND LUASAN_PANEN = '0' and 
                         NIK_PEMANEN LIKE '$nik' and ID_BA_AFD_BLOK like '$afdeling%' group by NIK_PEMANEN, EMP_NAME, JOB_CODE,substr(ID_BA_AFD_BLOK,6,3), ID_BA_AFD_BLOK, THP.NO_REKAP_BCC, TDRP.ID_RENCANA order by BLOK";
			$result_t_HP = oci_parse($con, $sql_t_HP);
			//echo $sql_t_HP;
						oci_execute($result_t_HP, OCI_DEFAULT);
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
						
						//echo "";
						while ($p=oci_fetch($result_t_HP)) {
							$blok = oci_result($result_t_HP, "BLOK");
							$id_rencana = oci_result($result_t_HP, "ID_RENCANA");
							$no_rekap_bcc = oci_result($result_t_HP, "NO_REKAP_BCC");
							$id_ba_afd_blok = oci_result($result_t_HP, "ID_BA_AFD_BLOK");
							$no_bcc = oci_result($result_t_HP, "NO_BCC");
							echo "<tr>";
							echo "<td align='center'>$i</td>";
							echo "<td align='center'><input name='t_blok$i' type='text' value='$blok' id='t_blok$i' style='width:70px; height:20px' readonly='readonly'/><input name='countRow' type='hidden' id='countRow' colspan='7' value='$jml' style='width:70px; height:20px' readonly='readonly'/></td>";
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
						echo "</table>";
		}else{
			echo "<b style='font-size:30px'>Tidak ada Aktivitas Panen untuk karyawan di Afdeling Tersebut</b>";
		}
?>
