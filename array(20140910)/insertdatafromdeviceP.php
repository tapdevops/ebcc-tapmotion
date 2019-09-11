<?php
include("fungsi.php");
$response = array();



if(isset($_POST["NIK"]) && isset($_POST["Login_Name"]) ){



$x_stage = 0;
$x_stage2 = 0;
	
	$NIK = $_POST["NIK"];
	$Login_Name = $_POST["Login_Name"];  

	
	if($NIK == "" && $Login_Name == ""){
	$response["success"] = 0;
	$response["message"] = "NIK dan Login Name tidak ada";
	echo json_encode($response);
	}
	
	else if($NIK == "" || $Login_Name == ""){
	$response["success"] = 0;
	$response["message"] = "NIK atau Login Name tidak ada";
	echo json_encode($response);
	}
	
	else{ 
	
			
		if(
			isset($_POST["Tanggal_Rencana"]) && 
			isset($_POST["NIK_Mandor"]) && 
			isset($_POST["NIK_Pemanen"]) && 
			isset($_POST["Status_Gandeng"]) && 
			isset($_POST["row_t_detail_rencana_panen"]) && 
			isset($_POST["row_t_hasil_panen"]) && 
			isset($_POST["row_t_hasilpanen_kualitas"]) &&
			isset($_POST["row_t_detail_gandeng"]) &&
			isset($_POST["ID_Rencana"])
		)
		
		
		{	
			

			$Tanggal_Rencana 	= $_POST["Tanggal_Rencana"]; 
			$NIK_Mandor 		= $_POST["NIK_Mandor"];
			$NIK_Pemanen 		= $_POST["NIK_Pemanen"];
			$Status_Gandeng 	= $_POST["Status_Gandeng"]; 
			$row_t_detail_rencana_panen	= $_POST["row_t_detail_rencana_panen"];
			$row_t_hasil_panen			= $_POST["row_t_hasil_panen"];
			//$row_t_hasilpanen_kualitas	= $_POST["row_t_hasilpanen_kualitas"]; 
			$row_t_hasilpanen_kualitas=$_POST["row_t_hasilpanen_kualitas"];
			$row_t_detail_gandeng 		= $_POST["row_t_detail_gandeng"]; 
			$ID_Rencana 		= $_POST["ID_Rencana"]; 
			
			logToFile("sql.log", $row_t_detail_rencana_panen);
			logToFile("sql.log", $row_t_hasil_panen);
			logToFile("sql.log", $row_t_hasilpanen_kualitas);
			logToFile("sql.log", $row_t_detail_gandeng);
		
			if(	$Tanggal_Rencana == ""
						&& $NIK_Mandor 		== ""
						&& $NIK_Pemanen 	== ""
						&& $Status_Gandeng 	== ""
						&& $row_t_detail_rencana_panen	== ""
						&& $row_t_hasil_panen			== ""
						&& $row_t_hasilpanen_kualitas 	== ""
						&& $row_t_detail_gandeng 		== ""
						&& $ID_Rencana 		== ""
						){
						$response["success"] = 0;
						$response["message"] = "data yang dikirim kosong";
						echo json_encode($response);
					}
					else if($Tanggal_Rencana == ""
						|| $NIK_Mandor 		== ""
						|| $NIK_Pemanen 	== ""
						|| $Status_Gandeng 	== ""
						|| $row_t_detail_rencana_panen	== ""
						|| $row_t_hasil_panen			== ""
						|| $row_t_hasilpanen_kualitas 	== ""
						|| $row_t_detail_gandeng 		== ""
						|| $ID_Rencana 		== ""
						){
						$response["success"] = 0;
						$response["message"] = "data yang dikirim tidak lengkap <br> Tanggal_Rencana = ".$Tanggal_Rencana. "<br> NIK_Mandor = ". $NIK_Mandor . "<br> NIK_Pemanen = ".$NIK_Pemanen."<br> Status_Gandeng = ".$Status_Gandeng."<br> row_t_detail_rencana_panen = ".$row_t_detail_rencana_panen."<br> row_t_hasil_panen = ".$row_t_hasil_panen."<br> row_t_hasilpanen_kualitas = ".$row_t_hasilpanen_kualitas;
						echo json_encode($response);
					}
					else{
						include("../config/SQL_function.php");
						include("../config/db_connect.php");
						$con = connect();
$x_stage = 1;						
$x_stage = 2;						
						$roweffec_t_nab="";
						$sql_value_t_log_nab="";
						$sql_t_nab="";
															
						//t_header_rencana_panen
						$roweffec_t_header_rencana_panen = 0;
						$sql_t_header_rencana_panen = "INSERT INTO t_header_rencana_panen
						(ID_Rencana, Tanggal_Rencana, NIK_Mandor, NIK_KERANI_BUAH, NIK_Pemanen, Status_Gandeng) 
						VALUES
						('$ID_Rencana', to_date('$Tanggal_Rencana','YYYY-MM-DD'), '$NIK_Mandor', '$NIK', '$NIK_Pemanen', '$Status_Gandeng')";
						
						logToFile("sql.log", $sql_t_header_rencana_panen);
//for update t_hasil_panen
						$stmt = oci_parse($con,$sql_t_header_rencana_panen);
						$x_exe = oci_execute($stmt, OCI_DEFAULT);
						if(!$x_exe)
						{
						    logToFile("sql.log", $x_exe);
							logToFile("sql.log", 'ERROR');
							$m = oci_error($stmt);
							include("updatehasilpanen.php");
						}
						else
						{
							$roweffec_t_header_rencana_panen = oci_num_rows($stmt);
							oci_free_statement($stmt);
							logToFile("sql.log", 'TIDAK ERROR');
							/*$response["success"] = 0;
							$response["message"] = "else when not update: ".$roweffec_t_header_rencana_panen;	
							echo json_encode($response);*/
						}
//end of update t_hasil_panen
$x_stage = $Tanggal_Rencana;
						if ($roweffec_t_header_rencana_panen > 0 && $roweffec_t_header_rencana_panen != 0){
						
$x_stage = 4;							
							$affected_t_detail_gandeng = 0;
							$sql_t_dg = "select count(*) JUMLAH_DATA from t_detail_gandeng where id_rencana = '$ID_Rencana'";
							$select_t_dg  = select_data($con,$sql_t_dg);
							$jml_dg = $select_t_dg["JUMLAH_DATA"];
							
							logToFile("sql.log", $sql_t_dg);
							
							if($jml_dg == 0)
							{
								for($b = 0 ; $b < $row_t_detail_gandeng ; $b++ )
								{	
									$NIK_Gandeng[$b] 	= $_POST["NIK_Gandeng$b"];	
									if($NIK_Gandeng[$b] == ""){
										$gandeng_message[$b] = "NIK_Gandeng not send";
										$gandeng_array[$b] = 0;
$x_stage = 5;
										}
									else{
$x_stage = 6;
									//mysql_query("BEGIN");
										$sql_dual = "select SEQ_DETAIL_GANDENG.nextval as ID_GANDENG from dual";
										$result_dual = oci_parse($con, $sql_dual);
										
										logToFile("sql.log", $sql_dual);
										
										oci_execute($result_dual, OCI_DEFAULT);
										oci_fetch($result_dual);
										$ID_Gandeng = oci_result($result_dual, "ID_GANDENG");
										
										$sql_t_detail_gandeng = "INSERT INTO t_detail_gandeng 
										(ID_Gandeng, ID_Rencana, NIK_Gandeng) 
										VALUES
										('$ID_Gandeng', '$ID_Rencana', '$NIK_Gandeng[$b]')";
										$roweffec_t_detail_gandeng = num_rows($con,$sql_t_detail_gandeng);
										
										logToFile("sql.log", $sql_t_detail_gandeng);
										
										if($roweffec_t_detail_gandeng > 0 && $roweffec_t_detail_gandeng != 0){
$x_stage = 7;										
											$sql_value_t_log_detail_gandeng = "INSERT INTO t_log_detail_gandeng (
											InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_Gandeng, CreEdit_From, Sync_Server) 
											VALUES
											('INSERT', SYSDATE , '$NIK', '$Login_Name', 't_detail_gandeng', '$ID_Gandeng', 'Device', SYSDATE)" ;
											$result_value_t_log_detail_gandeng = insert_data($con,$sql_value_t_log_detail_gandeng);
											
											logToFile("sql.log", $sql_value_t_log_detail_gandeng);
											
											commit($con);
											$affected_t_detail_gandeng++;
											$gandeng_message[$b] = "NIK Gandeng ".$NIK_Gandeng[$b]." successfully submited";
											$gandeng_array[$b] = 1;
											
										}
										else{
$x_stage = 8;
											rollback($con);
											$gandeng_message[$b] = "NIK Gandeng ".$NIK_Gandeng[$b]." has not been submited";
											$gandeng_array[$b] = 0;
										}
									} //close else
								} //close for t_detail_gandeng
							}
$x_stage = 9;
							//t_detail_rencana_panen  HERE
							//include("include/array_detail_rencana_panen.php"); 
							$affected_t_detail_rencana_panen = 0;
							for($a = 0 ; $a < $row_t_detail_rencana_panen ; $a++ )
							{
$x_stage = 10;
								$ID_BA_Afd_Blok[$a] =  $_POST["ID_BA_Afd_Blok$a"];
								$No_Rekap_BCCdr[$a] 	=  replace_dot($_POST["No_Rekap_BCCdr$a"]);
								$Luasan_Panen[$a] 	=  $_POST["Luasan_Panen$a"]; 
								
								//mysql_query("BEGIN");
								$sql_t_detail_rencana_panen = "INSERT INTO t_detail_rencana_panen 
								(ID_BA_Afd_Blok, No_Rekap_BCC, ID_Rencana, Luasan_Panen)
								VALUES
								('$ID_BA_Afd_Blok[$a]', '$No_Rekap_BCCdr[$a]', '$ID_Rencana', '$Luasan_Panen[$a]' )";
								$roweffec_t_detail_rencana_panen = num_rows($con,$sql_t_detail_rencana_panen);
								
								logToFile("sql.log", $sql_t_detail_rencana_panen);
	
								$sql_value_log_rencana_panen = "INSERT INTO t_log_rencana_panen 
								(InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_Rencana, On_No_Rekap_BCC, New_Value_ID_BA_Afd_Blok, CreEdit_From, Sync_Server)
								VALUES
								('INSERT', SYSDATE, '$NIK', '$Login_Name', 't_detail_rencana_panen', '$ID_Rencana', '$No_Rekap_BCCdr[$a]', '$ID_BA_Afd_Blok[$a]', 'Device', SYSDATE)" ;
								$roweffec_value_log_rencana_panen = num_rows($con,$sql_value_log_rencana_panen);
								
								logToFile("sql.log", $sql_value_log_rencana_panen);
	
								if($roweffec_t_detail_rencana_panen > 0 && $roweffec_t_detail_rencana_panen != 0 
								   && $roweffec_value_log_rencana_panen > 0 && $roweffec_value_log_rencana_panen != 0){
$x_stage = 11;
									commit($con);
									$affected_t_detail_rencana_panen++;
									$detail_message[$a] = "t_detail_rencana_panen, <br> No Rekap ".$No_Rekap_BCCdr[$a]." dan ID_BA_Afd_Blok ".$ID_BA_Afd_Blok[$a]." berhasil diinput";
									$detail_array[$a] = 1;										
								}
								else{
$x_stage = 12;
								rollback($con);
									$detail_message[$a] = "roweffec_t_detail_rencana_panen:".$roweffec_t_detail_rencana_panen." roweffec_value_log_rencana_panen:".$roweffec_value_log_rencana_panen." @@ ".$sql_t_header_rencana_panen." #t_detail_rencana_panen, <br> No Rekap ".$No_Rekap_BCCdr[$a]." dan ID_BA_Afd_Blok ".$ID_BA_Afd_Blok[$a]." gagal diinput, query ".$sql_t_detail_rencana_panen;
									$detail_array[$a] = 0;
								}
							} // close for t_detail_rencana_panen
							
							if($row_t_detail_rencana_panen == $affected_t_detail_rencana_panen){
$x_stage = 13;								
								//t_hasil_panen  HERE
								//include("include/array_hasil_panen.php"); 
								//include("include/array_nab.php"); 
								$affected_t_hasil_panen = 0;
								$affected_t_nab = 0;
								for($c = 0 ; $c < $row_t_hasil_panen ; $c++ )
								{
$x_stage = 14;
									$No_Rekap_BCChp[$c] 	=  replace_dot($_POST["No_Rekap_BCChp$c"]);
									$No_TPH[$c]			=  $_POST["No_TPH$c"];
									$No_BCC[$c] 		=  replace_dot($_POST["No_BCC$c"]);
									$Kode_Delivery_Ticket[$c] =  $_POST["Kode_Delivery_Ticket$c"];
									$Latitude[$c] 		=  $_POST["Latitude$c"];
									$Longitude[$c] 		=  $_POST["Longitude$c"];
									$Picture_Name[$c] 	=  $_POST["Picture_Name$c"];
									$Status_BCC[$c] 	=  $_POST["Status_BCC$c"];
									
									if(isset($_POST["ID_NAB_Tgl$c"]))
									{
										$ID_NAB_Tgl[$c] 	=  $_POST["ID_NAB_Tgl$c"];

										if($ID_NAB_Tgl[$c] !== '' && $ID_NAB_Tgl[$c] !== 0  && $ID_NAB_Tgl[$c] !== null)
										{
											$No_NAB[$c] 		= $_POST["No_NAB$c"];
											$Tgl_NAB[$c] 		= $_POST["Tgl_NAB$c"];
											$Tipe_Order[$c] 	= $_POST["Tipe_Order$c"];
											$ID_Internal_Order[$c] = $_POST["ID_Internal_Order$c"];
											$No_Polisi[$c] 		= $_POST["No_Polisi$c"];
											$NIK_Supir[$c] 		= $_POST["NIK_Supir$c"];
											$NIK_Tukang_Muat1[$c] = $_POST["NIK_Tukang_Muat1$c"];
											$NIK_Tukang_Muat2[$c] = $_POST["NIK_Tukang_Muat2$c"];
											$NIK_Tukang_Muat3[$c] = $_POST["NIK_Tukang_Muat3$c"];
										}
										else{
											$ID_NAB_Tgl[$c] 	= "";
											
											$No_NAB[$c] 		= "";
											$Tgl_NAB[$c] 		= "";
											$Tipe_Order[$c] 	= "";
											$ID_Internal_Order[$c] = "";
											$No_Polisi[$c] 		= "";
											$NIK_Supir[$c] 		= "";
											$NIK_Tukang_Muat1[$c] = "";
											$NIK_Tukang_Muat2[$c] = "";
											$NIK_Tukang_Muat3[$c] = "";
										}
									}
									else
									{
										$ID_NAB_Tgl[$c] 	= "";
										
										$No_NAB[$c] 		= "";
										$Tgl_NAB[$c] 		= "";
										$Tipe_Order[$c] 	= "";
										$ID_Internal_Order[$c] = "";
										$No_Polisi[$c] 		= "";
										$NIK_Supir[$c] 		= "";
										$NIK_Tukang_Muat1[$c] = "";
										$NIK_Tukang_Muat2[$c] = "";
										$NIK_Tukang_Muat3[$c] = "";
									}
									
									
									
									if($No_BCC[$c] == "" || $No_Rekap_BCChp[$c] == ""){
$x_stage = 15;
										$hp_array[$c] = 0;
										$hp_message[$c] = "nomor BCC atau No_Rekap tidak dikirim";
									}
									else{
$x_stage = 16;
										/*$sql_check_BCC = "select No_BCC from t_hasil_panen where No_BCC = '$No_BCC[$c]' AND ID_RENCANA = '$ID_Rencana'";		
										//$fetch_check_BCC = select_data($con,$sql_check_BCC);
										//echo $sql_check_BCC;
										
										if ($fetch_check_BCC = select_data($con,$sql_check_BCC)){								
											$hp_message[$c] = "nomor BCC ".$No_BCC[$c]." dan nomor rekap ".$No_Rekap_BCChp[$c]." sudah pernah dikirim";
											$hp_array[$c] = 0;
$x_stage = 17;	
										}
										else{*/
											
											//mysql_query("BEGIN");
											$sql_t_hasil_panen = "INSERT INTO t_hasil_panen 
											(ID_RENCANA, No_Rekap_BCC, No_TPH, No_BCC, Kode_Delivery_Ticket, Latitude, Longitude, Picture_Name, Status_BCC, ID_NAB_Tgl) 
											VALUES
											('$ID_Rencana','$No_Rekap_BCChp[$c]', '$No_TPH[$c]', '$No_BCC[$c]', '$Kode_Delivery_Ticket[$c]', 
											'$Latitude[$c]', '$Longitude[$c]', '$Picture_Name[$c]', '$Status_BCC[$c]', '$ID_NAB_Tgl[$c]')";
											$roweffec_t_hasil_panen = num_rows($con,$sql_t_hasil_panen);
											
											logToFile("sql.log", $sql_t_hasil_panen);
											
											$sql_value_log_hasil_panen = "INSERT INTO t_log_hasil_panen 
											(InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_No_BCC, On_Kode_Delivery_Ticket, New_Value_Status_BCC, CreEdit_From, Sync_Server) 
											VALUES
											('INSERT', SYSDATE, '$NIK', '$Login_Name', 't_hasil_panen', '$No_BCC[$c]', '$Kode_Delivery_Ticket[$c]', '$Status_BCC[$c]', 'Device', SYSDATE)" ;
											$roweffec_value_log_hasil_panen = num_rows($con,$sql_value_log_hasil_panen);
											
											logToFile("sql.log", $sql_value_log_hasil_panen);
											
											if($roweffec_t_hasil_panen > 0 && $roweffec_t_hasil_panen != 0
											&& $roweffec_value_log_hasil_panen > 0 && $roweffec_value_log_hasil_panen != 0){
												commit($con);
												
												/* DI DISABLE DULU, TAP'S REQUEST
												
												//set nama gambar
												header('Content-Type: bitmap; charset=utf-8');
												
												//$Picture_Name = $_POST["Picture_Name$c"]; 
												$base=$_REQUEST["Picture_Data$c"];
												$binary=base64_decode($base);
												
												$file = fopen('uploads/'.$Picture_Name[$c], 'wb');
												//$file = fopen('uploads/$c.jpg', 'wb');
												fwrite($file, $binary);
												fclose($file);
												//set nama gambar 
												
												*/
												
												$affected_t_hasil_panen++;
												$hp_message[$c] = "input hasil panen success ".$No_BCC[$c];
												$hp_array[$c] = 1;
												
													//t_nab
													if($ID_NAB_Tgl[$c] == "" || $No_NAB[$c] == "" || $Tgl_NAB[$c] == "" || $No_Polisi[$c] == "" || $NIK_Supir[$c] == ""){
$x_stage2 = 20;
														$nab_message[$c] = "ID_NAB_Tgl, No_NAB, Tgl_NAB, No_Polisi, atau NIK_Supir not send";
														$nab_array[$c] = 0;
													}
													else{
$x_stage = 21;
													$sql_check_nab = "SELECT * FROM t_nab 
														where ID_NAB_Tgl = '$ID_NAB_Tgl[$c]' AND No_NAB  = '$No_NAB[$c]' AND Tgl_NAB  = to_date('$Tgl_NAB[$c]', 'YYYY-MM-DD') AND No_Polisi  = '$No_Polisi[$c]' AND NIK_Supir  = '$NIK_Supir[$c]' ";	
														
														logToFile("sql.log", $sql_check_nab);
														
														if ($fetch_check_nab = select_data($con,$sql_check_nab)){								
															$nab_message[$c] = "ID_NAB_Tgl ".$ID_NAB_Tgl[$c]." dan No_Polisi ".$No_Polisi[$c]." already send";
															$nab_array[$c] = 0;
$x_stage = 22;
															}
														else{
$x_stage = 23;
															//mysql_query("BEGIN");
															$sql_t_nab = "INSERT INTO t_nab 
															(ID_NAB_Tgl, No_NAB, Tgl_NAB, Tipe_Order, ID_Internal_Order, No_Polisi, NIK_Supir, NIK_Tukang_Muat1, NIK_Tukang_Muat2, NIK_Tukang_Muat3, STATUS_DOWNLOAD)
															VALUES
															('$ID_NAB_Tgl[$c]', '$No_NAB[$c]', to_date('$Tgl_NAB[$c]', 'YYYY-MM-DD'),'$Tipe_Order[$c]', '$ID_Internal_Order[$c]', '$No_Polisi[$c]', '$NIK_Supir[$c]', '$NIK_Tukang_Muat1[$c]', '$NIK_Tukang_Muat2[$c]', '$NIK_Tukang_Muat3[$c]', 'N')";
															$roweffec_t_nab = num_rows($con,$sql_t_nab);
															
															logToFile("sql.log", $sql_t_nab);
															
															$sql_value_t_log_nab = "INSERT INTO t_log_nab 
															(InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_NAB_Tgl, 
															CreEdit_From, Sync_Server, 
															New_Supir,  
															New_Tukang_Muat_1, 
															New_Tukang_Muat_2,  
															New_Tukang_Muat_3,  
															New_Status_Download) 
															VALUES
															('INSERT', SYSDATE, '$NIK', '$Login_Name', 't_nab', '$ID_NAB_Tgl[$c]', 'Device', SYSDATE, 
															'$NIK_Supir[$c]', 
															'$NIK_Tukang_Muat1[$c]', 
															'$NIK_Tukang_Muat2[$c]', 
															'$NIK_Tukang_Muat3[$c]',
															'N')" ;
															
															logToFile("sql.log", $sql_value_t_log_nab);
															
															$result_value_t_log_nab = num_rows($con,$sql_value_t_log_nab);
															
															if($roweffec_t_nab > 0 && $result_value_t_log_nab > 0){
$x_stage = 24;
																commit($con);
																$affected_t_nab++;
																$nab_message[$c] = "Input ID_NAB_Tgl ".$ID_NAB_Tgl[$c]." dan No_Polisi ".$No_Polisi[$c]." sukses";
																$nab_array[$c] = 1;
															}
															else{
$x_stage = 25;
																rollback($con);
																$nab_message[$c] = "Input ID_NAB_Tgl ".$ID_NAB_Tgl[$c]." dan No_Polisi ".$No_Polisi[$c]." gagal";
																$nab_array[$c] = 0;
															}
												
														}
													}
											}
											else{
												rollback($con);
												$hp_message[$c] = "t_hasil_panen, <br> No_TPH = ".$No_TPH[$c]."<br> No_BCC = ".$No_BCC[$c]."<br> Kode_Delivery_Ticket = ".$Kode_Delivery_Ticket[$c]."\n has not been submited".$sql_t_hasil_panen."\n".$sql_value_log_hasil_panen;
												$hp_array[$c] = 0;
											}
											
										/*} //close else*/
									} //close else
								} //close for t_hasil_panen
								
								if($row_t_hasil_panen == $affected_t_hasil_panen){
									
									//t_hasilpanen_kualtas
									//include("include/array_hasilpanen_kualtas.php"); 
									$affected_t_hasilpanen_kualtas = 0;
									for($d = 0 ; $d < $row_t_hasilpanen_kualitas ; $d++ )
									{
										$ID_BCC_Kualitas[$d] =  replace_dot($_POST["ID_BCC_Kualitas$d"]);
										$ID_BCC[$d]		=  replace_dot($_POST["ID_BCC$d"]);
										$ID_Kualitas[$d]=  $_POST["ID_Kualitas$d"];
										$Qty[$d] 		=  $_POST["Qty$d"]; 
									
										//mysql_query("BEGIN");
										$sql_t_hasilpanen_kualtas = "INSERT INTO t_hasilpanen_kualtas 
										(ID_BCC_Kualitas, ID_BCC, ID_Kualitas, Qty, ID_RENCANA) 
										VALUES
										('$ID_BCC_Kualitas[$d]', '$ID_BCC[$d]', '$ID_Kualitas[$d]', '$Qty[$d]', '$ID_Rencana')";
										$roweffec_t_hasilpanen_kualtas = num_rows($con,$sql_t_hasilpanen_kualtas);
										
										logToFile("sql.log", $sql_t_hasilpanen_kualtas);
										
										$sql_value_log_hasilpanen_kualitas = "INSERT INTO t_log_hasilpanen_kualitas 
										(InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_BCC_Kualitas, New_Value_Qty, CreEdit_From, Sync_Server)
										VALUES
										('INSERT', SYSDATE, '$NIK', '$Login_Name', 't_hasilpanen_kualtas', '$ID_BCC_Kualitas[$d]', $Qty[$d], 'Device', SYSDATE)" ;
										$roweffec_value_log_hasilpanen_kualitas = num_rows($con,$sql_value_log_hasilpanen_kualitas);
										
										logToFile("sql.log", $sql_value_log_hasilpanen_kualitas);
										
											if($roweffec_t_hasilpanen_kualtas > 0 && $roweffec_t_hasilpanen_kualtas != 0 
											   && $roweffec_value_log_hasilpanen_kualitas > 0 && $roweffec_value_log_hasilpanen_kualitas != 0){
												commit($con);
												$affected_t_hasilpanen_kualtas++;
												$hpk_message[$d] = "input kualitas hasil panen sukses".$ID_BCC_Kualitas[$d];
												$hpk_array[$d] = 1;
											}
											else{
												rollback($con);
												$failed_t_hasilpanen_kualtas++;
												$hpk_message[$d] = "input kualitas hasil panen gagal".$ID_BCC_Kualitas[$d];
												$hpk_array[$d] = 0;
											}
									} // close for t_hasilpanen_kualtas
	
									// open check row_t_hasilpanen_kualitas vs affected_t_hasilpanen_kualtas
									
									
									if($row_t_hasilpanen_kualitas == $affected_t_hasilpanen_kualtas){
										$response["success"] = 1;
										$response["message"] = "seluruh data berhasil diinput ".$sql_t_nab. " roweffect : ".$roweffec_t_nab. " lognya : ". $sql_value_t_log_nab;
										$response["row_t_detail_rencana_panen"] = $row_t_detail_rencana_panen;
										$response["row_t_hasil_panen"] = $row_t_hasil_panen;
										$response["row_t_hasilpanen_kualitas"] = $row_t_hasilpanen_kualitas;
										
										echo json_encode($response);
									}
									else{
										
											$response["success"] = 0;
											$response["message"] = "t_hasilpanen_kualtas";
											$response["total_array"] = $row_t_hasilpanen_kualitas - $affected_t_hasilpanen_kualtas;
											$response["row_t_hasilpanen_kualitas"] = "del".$row_t_hasilpanen_kualitas. $affected_t_hasilpanen_kualtas;
											$response["query"] = $sql_t_hasilpanen_kualtas."\n".$sql_value_log_hasilpanen_kualitas;
											for($d = 0 ; $d < $row_t_hasilpanen_kualitas ; $d++ )
											{
												if ($hpk_array[$d] == 0){
												$response["hpk_message$d"] = $hpk_message[$d];
												$response["hpk_array$d"] = $d;
												}
											}
											echo json_encode($response);
											
									} // close check row_t_hasilpanen_kualitas vs affected_t_hasilpanen_kualtas
									
									//NAB?? di dalam hasil_panen
									
								}
								else{
									
									if($affected_t_hasil_panen == 0){
									
										$sql_del_t_header_rencana_panen = "DELETE FROM t_header_rencana_panen
										WHERE ID_Rencana = '$ID_Rencana'";
										$result_del_t_header_rencana_panen = delete_data($con,$sql_del_t_header_rencana_panen);	
										
										logToFile("sql.log", $sql_del_t_header_rencana_panen);
										
										for($b = 0 ; $b < $row_t_detail_gandeng ; $b++ ){
											$sql_del_t_detail_gandeng = "DELETE FROM t_detail_gandeng 
											WHERE  ID_Gandeng = '$ID_Gandeng' ";
											$result_del_t_detail_gandeng = delete_data($con,$sql_del_t_detail_gandeng);
											
											logToFile("sql.log", $sql_del_t_detail_gandeng);
											
											$sql_del_value_t_log_detail_gandeng = "DELETE FROM t_log_detail_gandeng
											WHERE NIK_CreEditor = '$NIK' 
											AND Login_Name_CreEditor = '$Login_Name'
											AND On_ID_Gandeng = '$ID_Gandeng' ";
											$result_del_value_t_log_detail_gandeng = delete_data($con,$sql_del_value_t_log_detail_gandeng);
											
											logToFile("sql.log", $sql_del_value_t_log_detail_gandeng);
										}
										
										
										for($a = 0 ; $a < $row_t_detail_rencana_panen ; $a++ ){
											$sql_del_t_detail_rencana_panen = "DELETE FROM t_detail_rencana_panen 
											WHERE ID_BA_Afd_Blok = '$ID_BA_Afd_Blok[$a]' AND No_Rekap_BCC = '$No_Rekap_BCCdr[$a]' AND ID_Rencana = '$ID_Rencana' AND Luasan_Panen = '$Luasan_Panen[$a]' " ;
											$result_del_t_detail_rencana_panen = delete_data($con,$sql_del_t_detail_rencana_panen);
											
											logToFile("sql.log", $sql_del_t_detail_rencana_panen);
				
											$sql_del_value_log_rencana_panen = "DELETE FROM t_log_rencana_panen 
											WHERE NIK_CreEditor = '$NIK' 
											AND Login_Name_CreEditor = '$Login_Name' 
											AND On_ID_Rencana = '$ID_Rencana' 
											AND On_No_Rekap_BCC = '$No_Rekap_BCCdr[$a]' 
											AND New_Value_ID_BA_Afd_Blok = '$ID_BA_Afd_Blok[$a]' " ;
											$result_del_value_log_rencana_panen = delete_data($con,$sql_del_value_log_rencana_panen);

											logToFile("sql.log", $sql_del_value_log_rencana_panen);
										}
										commit($con);
										$response["success"] = 0;
										$response["message"] = "t_hasil_panen tes".$sql_t_hasil_panen.$roweffec_t_hasil_panen.$sql_t_nab.$roweffec_value_log_hasil_panen;
										$response["total_array"] = $row_t_hasil_panen - $affected_t_hasil_panen;
										$response["row_t_hasil_panen"] = $row_t_hasil_panen;
										
										for($c = 0 ; $c < $row_t_hasil_panen ; $c++ )
										{
											if ($hp_array[$c] == 0){
											$response["hp_message$c"] = $hp_message[$c];
											$response["hp_array$c"] = $c;
											}
										}
										echo json_encode($response);
									}
									
									else{
										$response["success"] = 0;
										$response["message"] = "t_hasil_panen tes2";
										$response["total_array"] = $row_t_hasil_panen - $affected_t_hasil_panen;
										$response["row_t_hasil_panen"] = $row_t_hasil_panen;
										for($c = 0 ; $c < $row_t_hasil_panen ; $c++ )
										{
											if ($hp_array[$c] == 0){
											$response["hp_message$c"] = $hp_message[$c];
											$response["hp_array$c"] = $c;
											}
											// else{$response["nab$e"] = $nab_message[$e]; }
										}
										echo json_encode($response);
									}
								}
							} //close if($row_t_detail_rencana_panen == $affected_t_detail_rencana_panen)
							else{
								$response["success"] = 0;
								$response["message"] = "t_detail_rencana_panen";
								$response["total_array"] = $row_t_detail_rencana_panen - $affected_t_detail_rencana_panen;
								$response["row_t_detail_rencana_panen"] = $row_t_detail_rencana_panen;
								for($a = 0 ; $a < $row_t_detail_rencana_panen ; $a++ )
								{
									if ($detail_array[$a] == 0){
									$response["detail_message$a"] = $detail_message[$a];
									$response["detail_array$a"] = $a;
									}
									/* else{
									$response["nab$e"] = $nab_message[$e];
									} */
								}
								echo json_encode($response);
							}
						}
					} //close else		
		} //close if(isset($_POST["ID_Rencana"]) && ...... && isset($_POST["row_t_detail_gandeng"])
				
		else{
			$response["success"] = 0;
			$response["message"] = "No data to input, DETAIL: ".$_POST["Tanggal_Rencana"]." # ".$_POST["NIK_Mandor"]." # ".$_POST["NIK_Pemanen"]." # ".$_POST["Status_Gandeng"]." # ".$_POST["row_t_detail_rencana_panen"]." # ".$_POST["row_t_hasil_panen"]." # ".$_POST["row_t_hasilpanen_kualitas"]." # ".$_POST["row_t_detail_gandeng"]." # ".$_POST["ID_Rencana"];
			echo json_encode($response);
		}
	}// close else	
}	//close if(isset($_POST["NIK"]) && isset($_POST["Login_Name"]) )		
else{
	$NIK = $_POST["NIK"];
	$Login_Name = $_POST["Login_Name"];  
	$response["success"] = 0;
	$response["message"] = "User login kosong nih ".$NIK." - ".$Login_Name;
	echo json_encode($response);
}	
			
?>