<?php
		session_start();
        include("../config/SQL_function.php"); 
		include("../config/db_config.php");
		$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
		$countRow = $_GET['countRow'];
		if($countRow == "undefined" || $countRow == ""){
			$countRow = 0;
			$next_array = 1;
		}else{
			$next_array = 1;
		}
			
		$sql_t_NAB = $_SESSION["sql_t_NAB"];
		
		$result_t_NAB = oci_parse($con, $sql_t_NAB);
					oci_execute($result_t_NAB, OCI_DEFAULT);
					if($next_array == '1'){
						echo "<tr #04B431>
							<th bgcolor='#75d88e' style='width:75px; height:10px'>TANGGAL</br>PANEN</th>
							<th bgcolor='#75d88e' style='width:40px; height:10px'>BLOK</th>
							<th bgcolor='#75d88e' style='width:120px; height:10px'>NIK</th>
							<th bgcolor='#75d88e' style='width:250px; height:10px'>NAMA PEMANEN</th>
							<th bgcolor='#75d88e' style='width:40px; height:10px'>TPH</th>
							<th bgcolor='#75d88e' style='width:70px; height:10px'>DELIVERY</br>TICKET</th>
							<th bgcolor='#75d88e' style='width:150px; height:10px'>No BCC</th>
							<th bgcolor='#75d88e' style='width:50px; height:10px'>JANJANG KIRIM</th>
							<th bgcolor='#75d88e' style='width:70px; height:10px'>BRONDOLAN</th>
							<th bgcolor='#75d88e' style='width:50px; height:10px'>BJR</th>
							<th bgcolor='#75d88e' style='width:70px; height:10px'>ESTIMASI BERAT</th>
							<th bgcolor='#75d88e' style='width:70px; height:10px'>HAPUS</th>
						</tr>";
					}	
					while ($p=oci_fetch($result_t_NAB)) {
						$id_rencana = oci_result($result_t_NAB, "ID_RENCANA");
						$tgl_rencana = oci_result($result_t_NAB, "TANGGAL_RENCANA");
						$blok = oci_result($result_t_NAB, "ID_BLOK");
						$nik = oci_result($result_t_NAB, "NIK_PEMANEN");
						$nama = oci_result($result_t_NAB, "NAMA_PEMANEN");
						$no_tph = oci_result($result_t_NAB, "NO_TPH");
						$kd_d_ticket = oci_result($result_t_NAB, "KODE_DELIVERY_TICKET");
						$no_bcc = oci_result($result_t_NAB, "NO_BCC");
						$jjg = oci_result($result_t_NAB, "JJG");
						$brd = oci_result($result_t_NAB, "BRD");
						$bjr = oci_result($result_t_NAB, "BJR");
						$est_berat = oci_result($result_t_NAB, "ESTIMASI_BERAT");
						echo "<tr>";
						echo "<input name='id_rencana$next_array' type='hidden' id='id_rencana$next_array' value='$id_rencana' readonly='readonly'/>\n";
						echo "<td><input name='tgl_rencana$next_array' type='text' id='tgl_rencana$next_array' value='$tgl_rencana' style='width:75px; height:20px' readonly='readonly'/></td>\n";
						echo "<td><input name='t_blok$next_array' type='text' id='t_blok$next_array' value='$blok' style='width:40px; height:20px' readonly='readonly'/></td>\n";
						echo "<td><input name='t_nik$next_array' type='text' id='t_nik$next_array' value='$nik' style='width:120px; height:20px' readonly='readonly'/></td>\n";
						echo "<td><input name='t_nama_pemanen$next_array' type='text' id='t_nama_pemanen$next_array' value='$nama' style='width:250px; height:20px' readonly='readonly'/></td>\n";
						echo "<td><input name='t_no_tph$next_array' type='text' id='t_no_tph$next_array' value='$no_tph' style='width:40px; height:20px' readonly='readonly'/></td>\n";
						echo "<td><input name='t_kd_d_ticket$next_array' type='text' id='t_kd_d_ticket$next_array' value='$kd_d_ticket' style='width:70px; height:20px' readonly='readonly'/></td>\n";
						echo "<td><input name='t_no_bcc$next_array' type='text' id='t_no_bcc$next_array' value='$no_bcc' style='width:150px; height:20px' readonly='readonly'/></td>\n";
						echo "<td><input name='t_jjg$next_array' type='text' id='t_jjg$next_array' value='$jjg' style='width:50px; height:20px' readonly='readonly'/></td>\n";
						echo "<td><input name='t_brd$next_array' type='text' id='t_brd$next_array' value='$brd' style='width:70px; height:20px' readonly='readonly'/></td>\n";
						echo "<td><input name='t_bjr$next_array' type='text' id='t_bjr$next_array' value='$bjr' style='width:50px; height:20px' readonly='readonly'/></td>\n";
						echo "<td><input name='t_est_berat$next_array' type='text' id='t_est_berat$next_array' value='$est_berat' style='width:70px; height:20px' readonly='readonly'/></td>\n";
						echo "</tr>";
						$next_array++;
					}
					echo " . # . $next_array";
					
		
?>
