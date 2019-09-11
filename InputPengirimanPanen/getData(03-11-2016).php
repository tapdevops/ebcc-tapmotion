<?php

        include("../config/SQL_function.php"); 
		include("../config/db_config.php");
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
		$countRow = "";
		$afdeling = $_GET['afdeling'];
		$tgl_panen = $_GET['var_tgl'];
		$deliv_ticket = $_GET['deliv_ticket'];
		$countRow = $_GET['countRow'];
		$tmp_tgl = $_GET['tmp_tgl'];
		$tmp_blok = $_GET['tmp_blok'];
		$tmp_nik = $_GET['tmp_nik'];
		$tmp_tph = $_GET['tmp_tph'];
		$tmp_bcc = $_GET['tmp_bcc'];
		
		$tgl_bcc = date('m/d/Y', strtotime($tmp_tgl));
		
		if($countRow == "undefined" || $countRow == ""){
			$countRow = 0;
			$next_array = 1;
		}else{
			$countRow = $countRow + 1;
			$next_array = $countRow;
		}
		//$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
		
		//Edited by Ardo, 22-10-2016 : mengganti tanggalnya agar BCC yang identik (cth : 1610190010111111111B dan 1610210010111111111B) pada tgl berbeda tidak ikut masuk pada NAB tsb jika dipilih salah satu
		$sql_t_HP  = "select count(NIK_PEMANEN) as JML
					from T_HASIL_PANEN THP
					left join T_DETAIL_RENCANA_PANEN TDRP on (TDRP.ID_RENCANA = THP.ID_RENCANA and TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC)
					left join T_HEADER_RENCANA_PANEN THRP on (THRP.ID_RENCANA = THP.ID_RENCANA)
					/*left join T_HASILPANEN_KUALTAS THK1 on (THK1.ID_RENCANA = THP.ID_RENCANA and THK1.ID_BCC = THP.NO_BCC and THK1.ID_KUALITAS = '1')
					left join T_HASILPANEN_KUALTAS THK2 on (THK1.ID_RENCANA = THP.ID_RENCANA and THK2.ID_BCC = THP.NO_BCC and THK2.ID_KUALITAS = '2')
					left join T_HASILPANEN_KUALTAS THK3 on (THK1.ID_RENCANA = THP.ID_RENCANA and THK3.ID_BCC = THP.NO_BCC and THK3.ID_KUALITAS = '3')
					left join T_HASILPANEN_KUALTAS THK4 on (THK1.ID_RENCANA = THP.ID_RENCANA and THK4.ID_BCC = THP.NO_BCC and THK4.ID_KUALITAS = '4')
					left join T_HASILPANEN_KUALTAS THK5 on (THK1.ID_RENCANA = THP.ID_RENCANA and THK5.ID_BCC = THP.NO_BCC and THK5.ID_KUALITAS = '5')
					left join T_HASILPANEN_KUALTAS THK6 on (THK6.ID_RENCANA = THP.ID_RENCANA and THK6.ID_BCC = THP.NO_BCC and THK6.ID_KUALITAS = '6')
					left join T_HASILPANEN_KUALTAS THK15 on (THK15.ID_RENCANA = THP.ID_RENCANA and THK15.ID_BCC = THP.NO_BCC and THK15.ID_KUALITAS = '15')*/
					left join T_EMPLOYEE TE on (TE.NIK = THRP.NIK_PEMANEN)
					where STATUS_BCC = 'RESTAN' and SUBSTR(TDRP.ID_BA_AFD_BLOK,6,3) like '$tmp_blok' and NIK_PEMANEN like '$tmp_nik' and NO_TPH like '$tmp_tph' and KODE_DELIVERY_TICKET like '$deliv_ticket' and NO_BCC = '$tmp_bcc' and TDRP.ID_BA_AFD_BLOK like '$afdeling%' 
					and TANGGAL_RENCANA = TO_DATE('$tgl_bcc', 'MM/DD/YYYY HH24:MI:SS')";
		$result_t_HP = oci_parse($con, $sql_t_HP);
		oci_execute($result_t_HP, OCI_DEFAULT);
		$p=oci_fetch($result_t_HP);
		$jml = oci_result($result_t_HP, "JML");
		//$countRow += $jml;		
		if($jml != "0"){
			$sql_t_HP  = "select THP.ID_RENCANA as ID_RENCANA,
								 TANGGAL_RENCANA, 
								 SUBSTR(TDRP.ID_BA_AFD_BLOK,6,3) as BLOK, 
								 NIK_PEMANEN, 
								 REPLACE(EMP_NAME,'''','') as EMP_NAME,
								 NO_TPH, 
								 KODE_DELIVERY_TICKET, 
								 NO_BCC,
								 NVL (F_GET_HASIL_PANEN_BUNCH (SUBSTR(TDRP.ID_BA_AFD_BLOK,1,4), thp.no_rekap_bcc, thp.no_bcc, 'BUNCH_SEND'), 0) as JJG, 
								 --(NVL(THK1.QTY,0)+NVL(THK2.QTY,0)+NVL(THK3.QTY,0)+NVL(THK4.QTY,0)+NVL(THK6.QTY,0)+NVL(THK15.QTY,0)) as JJG, 
								NVL(f_get_hasil_panen (thp.no_rekap_bcc, thp.no_bcc, 'BRD'),0) BRD
								--THK5.QTY as BRD
						from T_HASIL_PANEN THP
						left join T_DETAIL_RENCANA_PANEN TDRP on (TDRP.ID_RENCANA = THP.ID_RENCANA and TDRP.NO_REKAP_BCC = THP.NO_REKAP_BCC)
						left join T_HEADER_RENCANA_PANEN THRP on (THRP.ID_RENCANA = THP.ID_RENCANA)
						/*left join T_HASILPANEN_KUALTAS THK1 on (THK1.ID_RENCANA = THP.ID_RENCANA and THK1.ID_BCC = THP.NO_BCC and THK1.ID_KUALITAS = '1')
						left join T_HASILPANEN_KUALTAS THK2 on (THK2.ID_RENCANA = THP.ID_RENCANA and THK2.ID_BCC = THP.NO_BCC and THK2.ID_KUALITAS = '2')
						left join T_HASILPANEN_KUALTAS THK3 on (THK3.ID_RENCANA = THP.ID_RENCANA and THK3.ID_BCC = THP.NO_BCC and THK3.ID_KUALITAS = '3')
						left join T_HASILPANEN_KUALTAS THK4 on (THK4.ID_RENCANA = THP.ID_RENCANA and THK4.ID_BCC = THP.NO_BCC and THK4.ID_KUALITAS = '4')
						left join T_HASILPANEN_KUALTAS THK5 on (THK5.ID_RENCANA = THP.ID_RENCANA and THK5.ID_BCC = THP.NO_BCC and THK5.ID_KUALITAS = '5')
						left join T_HASILPANEN_KUALTAS THK6 on (THK6.ID_RENCANA = THP.ID_RENCANA and THK6.ID_BCC = THP.NO_BCC and THK6.ID_KUALITAS = '6')
						left join T_HASILPANEN_KUALTAS THK15 on (THK15.ID_RENCANA = THP.ID_RENCANA and THK15.ID_BCC = THP.NO_BCC and THK15.ID_KUALITAS = '15')*/
						left join T_EMPLOYEE TE on (TE.NIK = THRP.NIK_PEMANEN)
						where STATUS_BCC = 'RESTAN' and SUBSTR(TDRP.ID_BA_AFD_BLOK,6,3) like '$tmp_blok' and NIK_PEMANEN like '$tmp_nik' and NO_TPH like '$tmp_tph' and
						KODE_DELIVERY_TICKET like '$deliv_ticket' and NO_TPH like '$tmp_tph' and TDRP.ID_BA_AFD_BLOK like '$afdeling%' 
						and TANGGAL_RENCANA = TO_DATE('$tgl_bcc', 'MM/DD/YYYY HH24:MI:SS')
						order by ID_RENCANA, TANGGAL_RENCANA, SUBSTR(TDRP.ID_BA_AFD_BLOK,6,3), EMP_NAME";
			$result_t_HP = oci_parse($con, $sql_t_HP);
						oci_execute($result_t_HP, OCI_DEFAULT);
						//echo $result_t_HP;die();
						if($next_array == '1'){
							echo "<tr #04B431>
								<th bgcolor='#75d88e' style='width:100px; height:10px'>TANGGAL</br>PANEN</th>
								<th bgcolor='#75d88e' style='width:50px; height:10px'>BLOK</th>
								<th bgcolor='#75d88e' style='width:120px; height:10px'>NIK</th>
								<th bgcolor='#75d88e' style='width:250px; height:10px'>NAMA PEMANEN</th>
								<th bgcolor='#75d88e' style='width:50px; height:10px'>TPH</th>
								<th bgcolor='#75d88e' style='width:100px; height:10px'>DELIVERY</br>TICKET</th>
								<th bgcolor='#75d88e' style='width:150px; height:10px'>No BCC</th>
								<th bgcolor='#75d88e' style='width:50px; height:10px'>JANJANG KIRIM</th>
								<th bgcolor='#75d88e' style='width:70px; height:10px'>BRONDOLAN</th>
								<th bgcolor='#75d88e' style='width:70px; height:10px'>HAPUS</th>
							</tr>";
						}	
						while ($p=oci_fetch($result_t_HP)) {
							$id_rencana = oci_result($result_t_HP, "ID_RENCANA");
							$tgl_rencana = oci_result($result_t_HP, "TANGGAL_RENCANA");
							$blok = oci_result($result_t_HP, "BLOK");
							$nik = oci_result($result_t_HP, "NIK_PEMANEN");
							$nama = oci_result($result_t_HP, "EMP_NAME");
							$no_tph = oci_result($result_t_HP, "NO_TPH");
							$kd_d_ticket = oci_result($result_t_HP, "KODE_DELIVERY_TICKET");
							$no_bcc = oci_result($result_t_HP, "NO_BCC");
							$jjg = oci_result($result_t_HP, "JJG");
							$brd = oci_result($result_t_HP, "BRD");
							echo "<tr>";
							echo "<input name='id_rencana$next_array' type='hidden' id='id_rencana$next_array' value='$id_rencana' readonly='readonly'/>\n";
							echo "<td><input name='tgl_rencana$next_array' type='text' id='tgl_rencana$next_array' value='$tgl_rencana' style='width:100px; height:20px' readonly='readonly'/></td>\n";
							echo "<td><input name='t_blok$next_array' type='text' id='t_blok$next_array' value='$blok' style='width:50px; height:20px' readonly='readonly'/></td>\n";
							echo "<td><input name='t_nik$next_array' type='text' id='t_nik$next_array' value='$nik' style='width:120px; height:20px' readonly='readonly'/></td>\n";
							echo "<td><input name='t_nama_pemanen$next_array' type='text' id='t_nama_pemanen$next_array' value='$nama' style='width:250px; height:20px' readonly='readonly'/></td>\n";
							echo "<td><input name='t_no_tph$next_array' type='text' id='t_no_tph$next_array' value='$no_tph' style='width:50px; height:20px' readonly='readonly'/></td>\n";
							echo "<td><input name='t_kd_d_ticket$next_array' type='text' id='t_kd_d_ticket$next_array' value='$kd_d_ticket' style='width:100px; height:20px' readonly='readonly'/></td>\n";
							echo "<td><input name='t_no_bcc$next_array' type='text' id='t_no_bcc$next_array' value='$no_bcc' style='width:150px; height:20px' readonly='readonly'/></td>\n";
							echo "<td><input name='t_jjg$next_array' type='text' id='t_jjg$next_array' value='$jjg' style='width:50px; height:20px' readonly='readonly'/></td>\n";
							echo "<td><input name='t_brd$next_array' type='text' id='t_brd$next_array' value='$brd' style='width:70px; height:20px' readonly='readonly'/></td>\n";
							echo "<td><input name='$next_array' type='button' id='$next_array' class='del' value='delete' onclick='deleteRow(this,$next_array)' style='width:70px; height:20px' readonly='readonly'/></td>\n";
							
							
							echo "</tr>";
							$next_array++;
						}
						echo " . # . $next_array";
						//echo "</table>";
		}else{
			echo "0";
		}
?>
