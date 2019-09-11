<?php

if(strpos($m['message'],'unique constraint') == true)
{
	$x_continue = true;
	$x_stage = 0;
	logToFile("sql.log", 'nyampai sini');
	for($a = 0 ; $a < $row_t_detail_rencana_panen ; $a++ )
	{
		$ID_BA_Afd_Blok[$a] =  $_POST["ID_BA_Afd_Blok$a"];
		$No_Rekap_BCCdr[$a] 	=  replace_dot($_POST["No_Rekap_BCCdr$a"]);
		$Luasan_Panen[$a] 	=  $_POST["Luasan_Panen$a"]; 
		
		$sql_t_drp = "select count(*) JUMLAH_DATA from t_detail_rencana_panen where id_rencana = '$ID_Rencana' and no_rekap_bcc = '$No_Rekap_BCCdr[$a]'";
		$select_t_drp  = select_data($con,$sql_t_drp);
		$jml_drp = $select_t_drp["JUMLAH_DATA"];
		
		logToFile("sql.log", $sql_t_drp);
		
		if($jml_drp == 0)
		{
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
				$detail_message[$a] = "t_detail_rencana_panen, <br> No Rekap ".$No_Rekap_BCCdr[$a]." dan ID_BA_Afd_Blok ".$ID_BA_Afd_Blok[$a]." berhasil diinput";
				$detail_array[$a] = 1;										
			}
			else{
				$x_continue = false;
				$detail_message[$a] = "roweffec_t_detail_rencana_panen:".$roweffec_t_detail_rencana_panen." roweffec_value_log_rencana_panen:".$roweffec_value_log_rencana_panen." @@ ".$sql_t_header_rencana_panen." #t_detail_rencana_panen, <br> No Rekap ".$No_Rekap_BCCdr[$a]." dan ID_BA_Afd_Blok ".$ID_BA_Afd_Blok[$a]." gagal diinput, query ".$sql_t_detail_rencana_panen;
				$detail_array[$a] = 0;
			}
		}
		
		if($Luasan_Panen[$a]>0)
		{
			$sql_t_detail_rencana_panen = " UPDATE T_DETAIL_RENCANA_PANEN SET LUASAN_PANEN = '$Luasan_Panen[$a]' WHERE ID_RENCANA = '$ID_Rencana' AND NO_REKAP_BCC = '$No_Rekap_BCCdr[$a]'";
			$roweffec_t_detail_rencana_panen = num_rows($con,$sql_t_detail_rencana_panen);
            
			logToFile("sql.log", $sql_t_detail_rencana_panen);
			
			$sql_value_log_rencana_panen = "INSERT INTO t_log_rencana_panen 
			(InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_Rencana, On_No_Rekap_BCC, New_Value_ID_BA_Afd_Blok, CreEdit_From, Sync_Server)
			VALUES
			('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_detail_rencana_panen', '$ID_Rencana', '$No_Rekap_BCCdr[$a]', '$ID_BA_Afd_Blok[$a]', 'Device', SYSDATE)" ;
			$roweffec_value_log_rencana_panen = num_rows($con,$sql_value_log_rencana_panen);
             
			logToFile("sql.log", $sql_value_log_rencana_panen); 
			 
			if($roweffec_t_detail_rencana_panen > 0 && $roweffec_t_detail_rencana_panen != 0 
			   && $roweffec_value_log_rencana_panen > 0 && $roweffec_value_log_rencana_panen != 0){
				$sql_t_header_rencana_panen = " UPDATE T_HEADER_RENCANA_PANEN SET STATUS_GANDENG = '$Status_Gandeng' WHERE ID_RENCANA = '$ID_Rencana'";
				$update_t_header_rencana_panen = num_rows($con,$sql_t_header_rencana_panen);
				//commit($con);
				logToFile("sql.log", $sql_t_header_rencana_panen);
				
				$detail_message[$a] = "t_detail_rencana_panen, No Rekap ".$No_Rekap_BCCdr[$a]." dan ID_BA_Afd_Blok ".$ID_BA_Afd_Blok[$a]." berhasil DI UPDATE";
				$detail_array[$a] = 1;										
			}
			else{
				$x_stage = 1;
				$x_continue = false;
				//rollback($con);
				$detail_message[$a] = "roweffec_t_detail_rencana_panen:".$roweffec_t_detail_rencana_panen." roweffec_value_log_rencana_panen:".$roweffec_value_log_rencana_panen." @@ ".$sql_t_header_rencana_panen." #t_detail_rencana_panen, No Rekap ".$No_Rekap_BCCdr[$a]." dan ID_BA_Afd_Blok ".$ID_BA_Afd_Blok[$a]." gagal diinput, query ".$sql_t_detail_rencana_panen;
				$detail_array[$a] = 0;
			}
		}
	} 
	
	$lost_list="NO BCC: ";
	$x_count_lost=0;
			
	for($c = 0 ; $c < $row_t_hasil_panen ; $c++ )
	{
		$No_Rekap_BCChp[$c] 		= replace_dot($_POST["No_Rekap_BCChp$c"]);
		$No_TPH[$c]					= $_POST["No_TPH$c"];
		$No_BCC[$c] 				= replace_dot($_POST["No_BCC$c"]);
		$Kode_Delivery_Ticket[$c] 	= $_POST["Kode_Delivery_Ticket$c"];
		$Latitude[$c] 				= $_POST["Latitude$c"];
		$Longitude[$c] 				= $_POST["Longitude$c"];
		$Picture_Name[$c] 			= $_POST["Picture_Name$c"];
		$Status_BCC[$c] 			= $_POST["Status_BCC$c"];
		
		logToFile("sql.log", $No_BCC[$c]); 
		logToFile("sql.log", 'step 1');
		
		if(isset($_POST["ID_NAB_Tgl$c"]))
		{
			$ID_NAB_Tgl[$c] 	=  $_POST["ID_NAB_Tgl$c"];
			if($ID_NAB_Tgl[$c] !== '' && $ID_NAB_Tgl[$c] !== 0  && $ID_NAB_Tgl[$c] !== null)
			{
				$No_NAB[$c] 			= $_POST["No_NAB$c"];
				$Tgl_NAB[$c] 			= $_POST["Tgl_NAB$c"];
				$Tipe_Order[$c] 		= $_POST["Tipe_Order$c"];
				$ID_Internal_Order[$c] 	= $_POST["ID_Internal_Order$c"];
				$No_Polisi[$c] 			= $_POST["No_Polisi$c"];
				$NIK_Supir[$c] 			= $_POST["NIK_Supir$c"];
				$NIK_Tukang_Muat1[$c] 	= $_POST["NIK_Tukang_Muat1$c"];
				$NIK_Tukang_Muat2[$c] 	= $_POST["NIK_Tukang_Muat2$c"];
				$NIK_Tukang_Muat3[$c] 	= $_POST["NIK_Tukang_Muat3$c"];
			}
			else{
				$ID_NAB_Tgl[$c] 		= "";
				$No_NAB[$c] 			= "";
				$Tgl_NAB[$c] 			= "";
				$Tipe_Order[$c] 		= "";
				$ID_Internal_Order[$c] 	= "";
				$No_Polisi[$c] 			= "";
				$NIK_Supir[$c] 			= "";
				$NIK_Tukang_Muat1[$c] 	= "";
				$NIK_Tukang_Muat2[$c] 	= "";
				$NIK_Tukang_Muat3[$c] 	= "";
			}
		}
		else
		{
			$ID_NAB_Tgl[$c] 		= "";
			$No_NAB[$c] 			= "";
			$Tgl_NAB[$c] 			= "";
			$Tipe_Order[$c] 		= "";
			$ID_Internal_Order[$c] 	= "";
			$No_Polisi[$c] 			= "";
			$NIK_Supir[$c] 			= "";
			$NIK_Tukang_Muat1[$c] 	= "";
			$NIK_Tukang_Muat2[$c] 	= "";
			$NIK_Tukang_Muat3[$c] 	= "";
		}
		
		if($No_BCC[$c] == "" || $No_Rekap_BCChp[$c] == ""){
			$hp_array[$c] = 0;
			$hp_message[$c] = "nomor BCC atau No_Rekap tidak dikirim";
			$x_continue = false;
			$x_stage = 2;
		}
		else{
			$sql_t_hasil_panen_old = "select Status_BCC from t_hasil_panen 
			WHERE No_BCC  = '$No_BCC[$c]' AND ID_RENCANA = '$ID_Rencana'";
			$select_t_hasil_panen  = select_data($con,$sql_t_hasil_panen_old);
			$old_Status_BCC = $select_t_hasil_panen["STATUS_BCC"];
			
				logToFile("sql.log", 'step 2');
			
			logToFile("sql.log", $sql_t_hasil_panen_old);
			
			if($old_Status_BCC == "LOST")
			{
				$lost_list=$lost_list." ".$No_BCC[$c].", ";
				$x_count_lost= $x_count_lost+1;
			}
			else
			{
				$sql_t_hp = "select count(*) JUMLAH_DATA from t_hasil_panen where id_rencana = '$ID_Rencana' and No_BCC = '$No_BCC[$c]'";
				$select_t_hp  = select_data($con,$sql_t_hp);
				$jml_hp = $select_t_hp["JUMLAH_DATA"];
				
				logToFile("sql.log", $sql_t_hp);
				
				if($jml_hp == 0)
				{
					$sql_t_hasil_panen = "INSERT INTO t_hasil_panen 
					(ID_RENCANA, No_Rekap_BCC, No_TPH, No_BCC, Kode_Delivery_Ticket, Latitude, Longitude, Picture_Name, Status_BCC, ID_NAB_Tgl) 
					VALUES
					('$ID_Rencana','$No_Rekap_BCChp[$c]', '$No_TPH[$c]', '$No_BCC[$c]', '$Kode_Delivery_Ticket[$c]', 
					'$Latitude[$c]', '$Longitude[$c]', '$Picture_Name[$c]', '$Status_BCC[$c]', '$ID_NAB_Tgl[$c]')";
					$roweffec_t_hasil_panen = num_rows($con,$sql_t_hasil_panen);
					
					logToFile("sql.log", 'step 3');
					
					logToFile("sql.log", $sql_t_hasil_panen);
				}
				else
				{
					$sql_t_hasil_panen = "UPDATE t_hasil_panen 
					SET Status_BCC = '$Status_BCC[$c]', ID_NAB_TGL = '$ID_NAB_Tgl[$c]'
					WHERE No_BCC  = '$No_BCC[$c]' AND ID_RENCANA = '$ID_Rencana'";
					$roweffec_t_hasil_panen  = num_rows($con,$sql_t_hasil_panen);
					
					logToFile("sql.log", 'step 4');
					
					logToFile("sql.log", $sql_t_hasil_panen);
				}
				
				$sql_value_log_hasil_panen = "INSERT INTO t_log_hasil_panen 
				(InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_No_BCC, On_Kode_Delivery_Ticket, New_Value_Status_BCC, Old_Value_Status_BCC, CreEdit_From, Sync_Server) 
				VALUES
				('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_hasil_panen', '$No_BCC[$c]', '$Kode_Delivery_Ticket[$c]', '$Status_BCC[$c]', '$old_Status_BCC', 'Device', SYSDATE)" ;
				$roweffec_value_log_hasil_panen = num_rows($con,$sql_value_log_hasil_panen);
				
				logToFile("sql.log", $sql_value_log_hasil_panen);
				
				if($roweffec_t_hasil_panen > 0 && $roweffec_t_hasil_panen != 0
				&& $roweffec_value_log_hasil_panen > 0 && $roweffec_value_log_hasil_panen != 0){
					//commit($con);
					
					$hp_message[$c] = "input hasil panen success ".$No_BCC[$c];
					$hp_array[$c] = 1;
					
						if($ID_NAB_Tgl[$c] == "" || $No_NAB[$c] == "" || $Tgl_NAB[$c] == "" || $No_Polisi[$c] == "" || $NIK_Supir[$c] == ""){
							$nab_message[$c] = "ID_NAB_Tgl, No_NAB, Tgl_NAB, No_Polisi, atau NIK_Supir not send";
							$nab_array[$c] = 0;
							$x_continue = false;
							$x_stage = 30;
						}
						else if($ID_NAB_Tgl[$c] == "0" || $No_NAB[$c] == "" || $Tgl_NAB[$c] == "" || $No_Polisi[$c] == "" || $NIK_Supir[$c] == ""){
							$nab_message[$c] = "ID_NAB_Tgl, No_NAB, Tgl_NAB, No_Polisi, atau NIK_Supir not send";
							$nab_array[$c] = 0;
							$x_stage = 31;
						}
						else{
						$sql_check_nab = "SELECT * FROM t_nab where ID_NAB_Tgl = '$ID_NAB_Tgl[$c]'";	
							
							if ($fetch_check_nab = select_data($con,$sql_check_nab)){								
								$nab_message[$c] = "ID_NAB_Tgl ".$ID_NAB_Tgl[$c]." dan No_Polisi ".$No_Polisi[$c]." already send";
								$nab_array[$c] = 0;
								//$x_continue = false;
								
								logToFile("sql.log", $sql_check_nab);
								
								}
							else{
								$sql_t_nab = "INSERT INTO t_nab 
								(ID_NAB_Tgl, No_NAB, Tgl_NAB, Tipe_Order, ID_Internal_Order, No_Polisi, NIK_Supir, NIK_Tukang_Muat1, NIK_Tukang_Muat2, NIK_Tukang_Muat3, STATUS_DOWNLOAD)
								VALUES
								('$ID_NAB_Tgl[$c]', '$No_NAB[$c]', to_date('$Tgl_NAB[$c]', 'YYYY-MM-DD'),'$Tipe_Order[$c]', '$ID_Internal_Order[$c]', '$No_Polisi[$c]', '$NIK_Supir[$c]', '$NIK_Tukang_Muat1[$c]', '$NIK_Tukang_Muat2[$c]', '$NIK_Tukang_Muat3[$c]', 'N')";
								$roweffec_t_nab = num_rows($con,$sql_t_nab);
								
								logToFile("sql.log", $sql_t_nab);
								
								if($roweffec_t_nab > 0 && $roweffec_t_nab != 0){
									$sql_value_t_log_nab = "INSERT INTO t_log_nab 
									(InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_NAB_Tgl, CreEdit_From, Sync_Server) 
									VALUES
									('INSERT', SYSDATE, '$NIK', '$Login_Name', 't_nab', '$ID_NAB_Tgl[$c]', 'Device', SYSDATE)" ;
									$result_value_t_log_nab = insert_data($con,$sql_value_t_log_nab);
									
									logToFile("sql.log", $sql_value_t_log_nab);
									
									//commit($con);
									$nab_message[$c] = "Input ID_NAB_Tgl ".$ID_NAB_Tgl[$c]." dan No_Polisi ".$No_Polisi[$c]." sukses";
									$nab_array[$c] = 1;
								}
								else{
									//rollback($con);
									$nab_message[$c] = "Input ID_NAB_Tgl ".$ID_NAB_Tgl[$c]." dan No_Polisi ".$No_Polisi[$c]." gagal";
									$nab_array[$c] = 0;
									$x_continue = false;
									$x_stage = 4;
								}
					
							}
						}
				}
				else{
					//rollback($con);
					$hp_message[$c] = "t_hasil_panen, No_TPH = ".$No_TPH[$c]." No_BCC = ".$No_BCC[$c]." Kode_Delivery_Ticket = ".$Kode_Delivery_Ticket[$c]." # has not been submited".$sql_t_hasil_panen." # ".$sql_value_log_hasil_panen;
					$hp_array[$c] = 0;
					$x_continue = false;
					$x_stage = 5;
				}
			}
		} //close else
	} //close for t_hasil_panen
	/*
	for($d = 0 ; $d < $row_t_hasilpanen_kualitas ; $d++ )
	logToFile("sql.log", $d);
	logToFile("sql.log", $row_t_hasilpanen_kualitas);
	{
		$ID_BCC_Kualitas[$d] =  replace_dot($_POST["ID_BCC_Kualitas$d"]);
		$ID_BCC[$d]		=  replace_dot($_POST["ID_BCC$d"]);
		$ID_Kualitas[$d]=  $_POST["ID_Kualitas$d"];
		$Qty[$d] 		=  $_POST["Qty$d"]; 
	*/
	for($d = 0 ; $d < $row_t_hasilpanen_kualitas ; $d++ )
	
	{
	  logToFile("sql.log", $d);
	  logToFile("sql.log", $row_t_hasilpanen_kualitas);
	
		$ID_BCC_Kualitas[$d] =  replace_dot($_POST["ID_BCC_Kualitas$d"]);
		//$ID_BCC[$d]		=  replace_dot($_POST["ID_BCC$d"]);
		$ID_Kualitas[$d]=  $_POST["ID_Kualitas$d"];
		$Qty[$d] 		=  $_POST["Qty$d"]; 
		$ID_BCC[$d] 		= replace_dot($_POST["ID_BCC$d"]);	
		
		//$No_Rekap_BCChp[$c] 		= replace_dot($_POST["No_Rekap_BCChp$c"]);
		//for($c = 0 ; $c < $row_t_hasil_panen ; $c++ )
		//'$No_NAB[$c]'
		
		logToFile("sql.log", 'step 6');
	
		$sql_status_bcc = "select Status_BCC from t_hasil_panen 
		WHERE No_BCC  = '$ID_BCC[$d]' AND ID_RENCANA = '$ID_Rencana'";
		$select_status_bcc  = select_data($con,$sql_status_bcc);
		$result_Status_BCC = $select_status_bcc["STATUS_BCC"];
		logToFile("sql.log", 'step 7');
		
		logToFile("sql.log", $sql_status_bcc);
		
		if($result_Status_BCC !== "LOST")
		{
			$sql_t_hasilpanen_kualtas = "INSERT INTO t_hasilpanen_kualtas 
			(ID_BCC_Kualitas, ID_BCC, ID_Kualitas, Qty, ID_RENCANA) 
			VALUES
			('$ID_BCC_Kualitas[$d]', '$ID_BCC[$d]', '$ID_Kualitas[$d]', '$Qty[$d]', '$ID_Rencana')";
			
			logToFile("sql.log", 'step 8');
			
			logToFile("sql.log", $sql_t_hasilpanen_kualtas);
			
			$stmt = oci_parse($con,$sql_t_hasilpanen_kualtas);
			$x_exe = oci_execute($stmt, OCI_DEFAULT);
			if(!$x_exe)
			{
				$m = oci_error($stmt);
				$sql_t_hasilpanen_kualtas = "UPDATE t_hasilpanen_kualtas 
				SET QTY = '$Qty[$d]'
				WHERE ID_BCC_Kualitas  = '$ID_BCC_Kualitas[$d]' AND ID_RENCANA = '$ID_Rencana'";
				$roweffec_t_hasilpanen_kualtas  = num_rows($con,$sql_t_hasilpanen_kualtas);
				$logAction ="UPDATE";
				logToFile("sql.log", 'step 9');
				
				logToFile("sql.log", $sql_t_hasilpanen_kualtas);
			}
			else
			{
				$roweffec_t_hasilpanen_kualtas = oci_num_rows($stmt);
				oci_free_statement($stmt);
				$logAction ="INSERT";
			}
			
			$sql_value_log_hasilpanen_kualitas = "INSERT INTO t_log_hasilpanen_kualitas 
			(InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_BCC_Kualitas, New_Value_Qty, CreEdit_From, Sync_Server)
			VALUES
			('$logAction', SYSDATE, '$NIK', '$Login_Name', 't_hasilpanen_kualtas', '$ID_BCC_Kualitas[$d]', $Qty[$d], 'Device', SYSDATE)" ;
			$roweffec_value_log_hasilpanen_kualitas = num_rows($con,$sql_value_log_hasilpanen_kualitas);
			
			logToFile("sql.log", 'step 10');
			logToFile("sql.log", $sql_value_log_hasilpanen_kualitas);
			
			if($roweffec_t_hasilpanen_kualtas > 0 && $roweffec_t_hasilpanen_kualtas != 0 
			   && $roweffec_value_log_hasilpanen_kualitas > 0 && $roweffec_value_log_hasilpanen_kualitas != 0){
				//commit($con);
				$hpk_message[$d] = $logAction." kualitas hasil panen sukses".$ID_BCC_Kualitas[$d];
				$hpk_array[$d] = 1;
			}
			else{
				//rollback($con);
				$hpk_message[$d] = $logAction." kualitas hasil panen gagal".$ID_BCC_Kualitas[$d];
				$hpk_array[$d] = 0;
				$x_continue = false;
				$x_stage = 6;
			}
		}
	} // close for t_hasilpanen_kualtas
	
	$sql_t_dg = "select count(*) JUMLAH_DATA from t_detail_gandeng where id_rencana = '$ID_Rencana'";
	$select_t_dg  = select_data($con,$sql_t_dg);
	$jml_dg = $select_t_dg["JUMLAH_DATA"];
	
	if($jml_dg == 0)
	{
		for($b = 0 ; $b < $row_t_detail_gandeng ; $b++ )
		{	
			$NIK_Gandeng[$b] 	= $_POST["NIK_Gandeng$b"];	
			if($NIK_Gandeng[$b] == ""){
				$gandeng_message[$b] = "NIK_Gandeng not send";
				$gandeng_array[$b] = 0;
				$x_continue = false;
				$x_stage = 7;
				}
			else{
				$sql_dual = "select SEQ_DETAIL_GANDENG.nextval as ID_GANDENG from dual";
				$result_dual = oci_parse($con, $sql_dual);
				oci_execute($result_dual, OCI_DEFAULT);
				oci_fetch($result_dual);
				$ID_Gandeng = oci_result($result_dual, "ID_GANDENG");
				
				logToFile("sql.log", $sql_dual);
				
				$sql_t_detail_gandeng = "INSERT INTO t_detail_gandeng 
				(ID_Gandeng, ID_Rencana, NIK_Gandeng) 
				VALUES
				('$ID_Gandeng', '$ID_Rencana', '$NIK_Gandeng[$b]')";
				$roweffec_t_detail_gandeng = num_rows($con,$sql_t_detail_gandeng);
				
				logToFile("sql.log", $sql_t_detail_gandeng);
				
				if($roweffec_t_detail_gandeng > 0 && $roweffec_t_detail_gandeng != 0){
					$sql_value_t_log_detail_gandeng = "INSERT INTO t_log_detail_gandeng (
					InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_Gandeng, CreEdit_From, Sync_Server) 
					VALUES
					('INSERT', SYSDATE , '$NIK', '$Login_Name', 't_detail_gandeng', '$ID_Gandeng', 'Device', SYSDATE)" ;
					$result_value_t_log_detail_gandeng = insert_data($con,$sql_value_t_log_detail_gandeng);
					
					logToFile("sql.log", $sql_value_t_log_detail_gandeng);
					
					//commit($con);
					$gandeng_message[$b] = "NIK Gandeng ".$NIK_Gandeng[$b]." successfully submited";
					$gandeng_array[$b] = 1;
					
				}
				else{
					//rollback($con);
					$gandeng_message[$b] = "NIK Gandeng ".$NIK_Gandeng[$b]." has not been submited";
					$gandeng_array[$b] = 0;
					$x_continue = false;
					$x_stage = 8;
				}
			}
		} //close for t_detail_gandeng
	}
	
	if($x_continue == true)
	{
		commit($con);
		$lost_list=$lost_list." sudah diupdate menjadi LOSS";
			
		$response["success"] = 1;
		
		if($x_count_lost>0)
		{
			$response["message"] = "data hasil panen berhasil di update, data nab berhasil di input. ".$lost_list;	
		}
		else
		{
			$response["message"] = "data hasil panen berhasil di update, data nab berhasil di input.";
		}
	}
	else
	{
		rollback($con);
		$response["success"] = 0;
		for($c = 0 ; $c < $row_t_hasil_panen ; $c++ )
		{
			if ($hp_array[$c] == 0)
			{
				$response["hp_message$c"] = $hp_message[$c];
				$response["hp_array$c"] = $c;
			}
		}	

		for($c = 0 ; $c < $row_t_detail_rencana_panen ; $c++ )
		{
			if ($detail_array[$c] == 0)
			{
				$response["detail_message$c"] = $detail_message[$c];
				$response["detail_array$c"] = $c;
			}
		}
		
		for($c = 0 ; $c < $row_t_hasilpanen_kualitas ; $c++ )
		{
			if ($hpk_array[$c] == 0)
			{
				$response["hpk_message$c"] = $hpk_message[$c];
				$response["hpk_array$c"] = $c;
			}
		}
		
		for($c = 0 ; $c < $row_t_detail_gandeng ; $c++ )
		{
			if ($gandeng_array[$c] == 0)
			{
				$response["gandeng_message$c"] = $gandeng_message[$c];
				$response["gandeng_array$c"] = $c;
			}
		}
		$response["message"] = "X_STAGE: ".$x_stage. " roweffec_t_nab:".$roweffec_t_nab;
	}
	echo json_encode($response);
}
else
{
	$response["success"] = 0;
	$response["message"] = "oracle error: ".$m['message'];	
	echo json_encode($response);
}
?>