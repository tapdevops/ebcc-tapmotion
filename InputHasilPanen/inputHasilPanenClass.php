<?php
// start of input Hasil Panen(BCP)
class input_Hasil_Panen 
{
	var $s_PlanID;
	var $s_PlanID2;
	var $s_PlanDate;
	//var $s_LHMNumb;
	var $s_idBA;
	var $s_afd;
	var $s_MandorNIK;
	var $s_KraniNIK;
	var $s_PemanenNIK;
	var $i_Row;
	var $s_blok;
	var $s_TPH;
	var $s_deliveryTicket;
	var $i_mentah;
	var $i_mengkal;
	var $i_masak;
	var $i_toomasak;
	var $i_busuk;
	var $i_jangkos;
	var $i_buborsi;
	var $i_tangkaipanjang;
	var $i_abnormal;
	var $i_hama;
	var $i_alas;
	var $i_ttlbrondolan;
	var $s_username;
	var $s_loginname;

	//var $s_StatusGandeng;
	var $s_Return;
	
	function cek_ValidasiBCC(){
		$con = connect();
		$id_ba_afd_blok = $this->s_afd . $this->s_blok;
		$tph = $this->s_TPH;
		$ticket = $this->s_deliveryTicket;
		$datePlan = date('Y-m-d', strtotime($this->s_PlanDate));
		$sql_t_PID  = "select THP.ID_RENCANA as ID_RENCANA from T_HASIL_PANEN THP
							join T_DETAIL_RENCANA_PANEN TDRP on TDRP.ID_RENCANA = THP.ID_RENCANA
							join T_HEADER_RENCANA_PANEN THRP on THRP.ID_RENCANA = THP.ID_RENCANA
					   where 
							TDRP.ID_BA_AFD_BLOK = '$id_ba_afd_blok'
							and THP.NO_TPH = '$tph'
							and THP.KODE_DELIVERY_TICKET = '$ticket'
							and THRP.TANGGAL_RENCANA = TO_DATE('$datePlan','RRRR-mm-dd')";
		$result_id_rencana = oci_parse($con, $sql_t_PID);
		oci_execute($result_id_rencana, OCI_DEFAULT);
		oci_fetch($result_id_rencana);
		//echo '1 : ' . $sql_t_PID; echo '<br /><br />';
		$plan_ID = oci_result($result_id_rencana, "ID_RENCANA");
		if($plan_ID <> ""){
			$result_cekvalidasi = 1;
		}else $result_cekvalidasi = 0;
		
		return $result_cekvalidasi;
	}
	
	function insert_HeaderRencanaPanen(){
		$con = connect();
		$datePlan1 = date('Ymd', strtotime($this->s_PlanDate));
	
		$query_id_rencana = "select ID_RENCANA from T_HEADER_RENCANA_PANEN where ID_RENCANA like '" . $datePlan1 . ".%." . $this->s_PemanenNIK . "'
							and NIK_MANDOR = '" . $this->s_MandorNIK . "' and NIK_KERANI_BUAH = '" . $this->s_KraniNIK . "'";
		$result_id_rencana = oci_parse($con, $query_id_rencana);
		oci_execute($result_id_rencana, OCI_DEFAULT);
		oci_fetch($result_id_rencana);
		//echo '2 : ' . $query_id_rencana; echo '<br /><br />';
		$plan_ID = oci_result($result_id_rencana, "ID_RENCANA");
		if($plan_ID == ""){
			$query_id_rencana1 = "select ID_RENCANA from T_HEADER_RENCANA_PANEN where ID_RENCANA = '" . $this->s_PlanID . "'";
			$result_id_rencana1 = oci_parse($con, $query_id_rencana1);
			oci_execute($result_id_rencana1, OCI_DEFAULT);
			oci_fetch($result_id_rencana1);
			//echo '3 : ' . $query_id_rencana1; echo '<br /><br />';
			$plan_ID2 = oci_result($result_id_rencana1, "ID_RENCANA");
			
			if($plan_ID2 == ""){
				$query_insertemp = "INSERT INTO T_HEADER_RENCANA_PANEN (ID_RENCANA, TANGGAL_RENCANA, NIK_MANDOR, NIK_KERANI_BUAH, NIK_PEMANEN, STATUS_GANDENG)
					VALUES ('" . $this->s_PlanID . "', to_date('" . $this->s_PlanDate . "','mm-dd-yyyy hh24:mi:ss'), '" . $this->s_MandorNIK . "', 
					'" . $this->s_KraniNIK . "', '" . $this->s_PemanenNIK . "', 'NO')";
				$result_insertemp = num_rows($con,$query_insertemp);
				commit($con);		
				$plan_ID2 = $this->s_PlanID;
				//echo '4 : ' . $query_insertemp; echo '<br /><br />';
			}else{
				$seq_numb = 0;
				$query_seq_numb1 = "SELECT max(SUBSTR(ID_RENCANA, 10, 10)) as KD_RENCANA FROM 
									T_HEADER_RENCANA_PANEN WHERE ID_RENCANA LIKE '$datePlan1.MANUAL%.$this->s_PemanenNIK'";
				$result_seq_numb1 = oci_parse($con, $query_seq_numb1);
				oci_execute($result_seq_numb1, OCI_DEFAULT);
				//echo '5 : ' . $query_seq_numb1; echo '<br /><br />';
				while ($p=oci_fetch($result_seq_numb1)) {	
						$res_seq_numb1 = oci_result($result_seq_numb1, "KD_RENCANA");
				}
				$split1 = explode(".", $res_seq_numb1);
				$split2 = explode("L", $split1[0]);
				
				if($split2[1] == ""){
					$seq_numb = "01";
				}else{
					$seq_numb += 1;
					if($seq_numb >= 2 && $seq_numb <= 9){
						$seq_numb = "0".$seq_numb;
					}
				}
				$plan_ID2 = $datePlan1.'.'.'MANUAL'.$seq_numb.'.'.$this->s_PemanenNIK;
				$query_insertemp = "INSERT INTO T_HEADER_RENCANA_PANEN (ID_RENCANA, TANGGAL_RENCANA, NIK_MANDOR, NIK_KERANI_BUAH, NIK_PEMANEN, STATUS_GANDENG)
					VALUES ('" . $plan_ID2 . "', to_date('" . $this->s_PlanDate . "','mm-dd-yyyy hh24:mi:ss'), '" . $this->s_MandorNIK . "', 
					'" . $this->s_KraniNIK . "', '" . $this->s_PemanenNIK . "', 'NO')";
				$result_insertemp = num_rows($con,$query_insertemp);
				commit($con);		
				//echo '6 : ' . $query_insertemp; echo '<br /><br />';
			}
		}else{
			$result_insertemp = 1;
			if($plan_ID == ''){
				$plan_ID2 = $this->s_PlanID;
			}else{
				$plan_ID2 = $plan_ID;
			}
		}
		//echo $query_insertemp;die();
		return $result_insertemp . " . # . " . $plan_ID2;
	}
	
	function insert_DetailRencanaPanen($plan_ID_1){
		$con = connect();
		
		$id_ba_afd_blok = $this->s_afd . $this->s_blok;
		$plan_ID = "";
		$datePlan = date('ymd', strtotime($this->s_PlanDate));
		$datePlan1 = date('Ymd', strtotime($this->s_PlanDate));
		$no_rekap = $datePlan . $this->s_blok;
		$no_rekap_BCC = "";
		
		$query_id_rencana = "select ID_BA_AFD_BLOK, TDRP.ID_RENCANA as ID_RENCANA, NO_REKAP_BCC from T_DETAIL_RENCANA_PANEN TDRP
							join T_HEADER_RENCANA_PANEN THRP on TDRP.ID_RENCANA = THRP.ID_RENCANA
							 where ID_BA_AFD_BLOK = '" . $id_ba_afd_blok . "' and TDRP.ID_RENCANA like '" . $datePlan1 . ".%." . $this->s_PemanenNIK . "' 
							 and NIK_MANDOR = '" . $this->s_MandorNIK . "' and NIK_KERANI_BUAH = '" . $this->s_KraniNIK . "'";
		$result_id_rencana = oci_parse($con, $query_id_rencana);
		oci_execute($result_id_rencana, OCI_DEFAULT);
		oci_fetch($result_id_rencana);
		//echo '7 : ' . $query_id_rencana; echo '<br /><br />';
		$plan_ID = oci_result($result_id_rencana, "ID_RENCANA");
		
		if($plan_ID == ""){
			$plan_ID = $plan_ID_1;
			$query_no_rekap_bcc = "SELECT NO_REKAP_BCC FROM T_DETAIL_RENCANA_PANEN WHERE NO_REKAP_BCC LIKE '$no_rekap%' AND 
									ID_RENCANA LIKE '" . $datePlan1 . ".%." . $this->s_PemanenNIK . "'";
			$result_no_rekap_bcc = oci_parse($con, $query_no_rekap_bcc);
			oci_execute($result_no_rekap_bcc, OCI_DEFAULT);
			oci_fetch($result_no_rekap_bcc);
			//echo '8 : ' . $query_no_rekap_bcc; echo '<br /><br />';
			$no_rekap_BCC = oci_result($result_no_rekap_bcc, "NO_REKAP_BCC");
			//if($no_rekap_BCC == ""){
			$query_seq_numb = "SELECT max(SUBSTR(NO_REKAP_BCC, 10, 2)) as MAX_SEQUENCE FROM T_DETAIL_RENCANA_PANEN WHERE NO_REKAP_BCC LIKE '$no_rekap%'";
			$result_seq_numb = oci_parse($con, $query_seq_numb);
			//echo '9 : ' . $query_seq_numb; echo '<br /><br />';
			oci_execute($result_seq_numb, OCI_DEFAULT);
			while ($p=oci_fetch($result_seq_numb)) {	
					$res_seq_numb = oci_result($result_seq_numb, "MAX_SEQUENCE");
			}
						
			if($res_seq_numb == ""){
				$seq_numb = "01";
			}else{
				$res_seq_numb += 1;
				$seq_numb = $res_seq_numb;
				if($seq_numb >= 2 && $seq_numb <= 9){
					$seq_numb = "0".$seq_numb;
				}
			}
			$no_rekap_BCC = $no_rekap . $seq_numb;
			$no_Rekap_BCC_temp = $datePlan . "." . $this->s_blok . "." . $seq_numb;
			$query_insertemp = "INSERT INTO T_DETAIL_RENCANA_PANEN (ID_BA_AFD_BLOK, NO_REKAP_BCC, ID_RENCANA, LUASAN_PANEN)
				VALUES ('" . $id_ba_afd_blok . "', '" . $no_rekap_BCC . "', '" . $plan_ID_1 . "', '0')";
			$result_insertemp = num_rows($con,$query_insertemp);
			commit($con);
			//echo '10 : ' . $query_insertemp; echo '<br /><br />';
			$query_insertlog = "INSERT INTO T_LOG_RENCANA_PANEN (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_RENCANA, ON_NO_REKAP_BCC,
								NEW_VALUE_ID_BA_AFD_BLOK, OLD_VALUE_ID_BA_AFD_BLOK, CREEDIT_FROM, SYNC_SERVER) VALUES 
								('INSERT', sysdate, '" . $this->s_username . "', '" . $this->s_loginname . "',
								't_detail_rencana_panen', '" . $plan_ID_1 . "', '" . $no_rekap_BCC . "', '" . $id_ba_afd_blok . "', '', 'Website', sysdate)";
			$result_insertlog = num_rows($con, $query_insertlog);
			commit($con);
			//echo '11 : ' . $query_insertlog; echo '<br /><br />';
		}
		else{
			$query_seq_numb1 = "SELECT max(SUBSTR(NO_REKAP_BCC, 10, 2)) as MAX_SEQUENCE FROM T_DETAIL_RENCANA_PANEN WHERE NO_REKAP_BCC LIKE '$no_rekap%'";
			$result_seq_numb1 = oci_parse($con, $query_seq_numb1);
					oci_execute($result_seq_numb1, OCI_DEFAULT);
					while ($p=oci_fetch($result_seq_numb1)) {	
							$res_seq_numb1 = oci_result($result_seq_numb1, "MAX_SEQUENCE");
					}
			//echo '12 : ' . $query_seq_numb1; echo '<br /><br />';	
					
			if($res_seq_numb1 == ""){
				$seq_numb1 = "01";
			}else{
				//$res_seq_numb1 += 1;
				$seq_numb1 = $res_seq_numb1;
				//if($seq_numb1 >= 2 && $seq_numb1 <= 9){
				//$seq_numb1 = "0".$seq_numb1	;
				//}
			}
			$no_rekap_BCC = oci_result($result_id_rencana, "NO_REKAP_BCC");
			$no_Rekap_BCC_temp = $datePlan . "." . $this->s_blok . "." . $seq_numb1;
			$result_insertemp = 1;
		}
		
		
		$returnHasilP = $this->insert_HasilPanen($no_rekap_BCC, $no_Rekap_BCC_temp, $plan_ID);
		$split = explode(" . # . ", $returnHasilP);
		if($split[0] == '1'){
			return $result_insertemp . " . # . " . $split[1];
		}else{
			return $result_insertemp . " . # . Not Success";
		}
	}
	
	function insert_HasilPanen($no_rekap_BCC, $no_Rekap_BCC_temp, $plan_ID){
		$con = connect();
		
		$no_BCC = $no_rekap_BCC . $this->s_TPH . $this->s_deliveryTicket . "B";
		
		$query_insertemp = "INSERT INTO T_HASIL_PANEN (ID_RENCANA, NO_REKAP_BCC, NO_TPH, NO_BCC, KODE_DELIVERY_TICKET, LATITUDE, LONGITUDE, PICTURE_NAME, ID_NAB_TGL, STATUS_TPH)
			VALUES ('$plan_ID', '$no_rekap_BCC', '$this->s_TPH', '$no_BCC', '$this->s_deliveryTicket', '0', '0', '0', '0', 'BCP')";
		$result_insertemp = num_rows($con,$query_insertemp);
		
		commit($con);	
		//echo '13 : ' . $query_insertemp; echo '<br /><br />';
		
		$query_insertlog = "INSERT INTO T_LOG_HASIL_PANEN (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_NO_BCC, ON_KODE_DELIVERY_TICKET, 
							NEW_VALUE_STATUS_BCC, OLD_VALUE_STATUS_BCC, CREEDIT_FROM, SYNC_SERVER, NEW_VALUE_STATUS_TPH)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasil_panen', '$no_BCC', '$this->s_deliveryTicket', 'RESTAN', '', 'Website', sysdate, 'BCP')";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		//echo '14 : ' . $query_insertlog; echo '<br /><br />';
		
		$no_BCC1 = $no_Rekap_BCC_temp . "." . $this->s_TPH . "." . $this->s_deliveryTicket . "." . "B";
		$query_insertlog = "INSERT INTO T_TIMESTAMP (ID_TIMESTAMP, TYPE_TIMESTAMP, START_INS_TIME, END_INS_TIME, START_UPD_TIME, END_UPD_TIME)
							VALUES ('$no_BCC1', 'Input Hasil Panen', sysdate, sysdate, '', '')";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		//echo '15 : ' . $query_insertlog; echo '<br /><br />';
		
		
		$id_ba_afd_blok = $this->s_afd . $this->s_blok;
		$datePlan = date('ymd', strtotime($this->s_PlanDate));
		$datePlan1 = date('Ymd', strtotime($this->s_PlanDate));
		$nik = $this->s_PemanenNIK;
		
		$query_rencana  = "select ID_RENCANA from T_HASILPANEN_KUALTAS where ID_BCC_KUALITAS like '$no_BCC%' and ID_RENCANA = '$plan_ID' 
							and ID_KUALITAS in ('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '15')";
		$result_rencana = oci_parse($con, $query_rencana);
					oci_execute($result_rencana, OCI_DEFAULT);
					while ($p=oci_fetch($result_rencana)) {	
							$res_id_rencana = oci_result($result_rencana, "ID_RENCANA");
					}
		//echo '16 : ' . $query_rencana; echo '<br /><br />';
		//echo $query_rencana;die();
		if($res_id_rencana == ""){
			$returnHasilK = $this->insert_HasilPanen_Kualitas($no_BCC, $plan_ID);
		}else{
			//$returnHasilK = $this->update_HasilPanen_Kualitas($no_BCC, $res_id_rencana);
		}
		return $returnHasilK . " . # . " . $no_BCC;
	}
	
	function insert_HasilPanen_Kualitas($no_BCC, $plan_ID){
		$con = connect();
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "1', '$no_BCC', '1', '$this->i_mentah', '$plan_ID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		//echo '17 : ' . $query_insertemp; echo '<br /><br />';
		
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "1', '$this->i_mentah', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		//echo '18 : ' . $query_insertlog; echo '<br /><br />';
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "2', '$no_BCC', '2', '$this->i_mengkal', '$plan_ID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		//echo '19 : ' . $query_insertemp; echo '<br /><br />';
		
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "2', '$this->i_mengkal', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		//echo '20 : ' . $query_insertlog; echo '<br /><br />';
		
			
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "3', '$no_BCC', '3', '$this->i_masak', '$plan_ID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		//echo '21 : ' . $query_insertemp; echo '<br /><br />';
		
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "3', '$this->i_masak', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		//echo '22 : ' . $query_insertlog; echo '<br /><br />';
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "4', '$no_BCC', '4', '$this->i_toomasak', '$plan_ID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		//echo '23 : ' . $query_insertemp; echo '<br /><br />';
		
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "4', '$this->i_toomasak', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		//echo '24 : ' . $query_insertlog; echo '<br /><br />';
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "5', '$no_BCC', '5', '$this->i_ttlbrondolan', '$plan_ID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		//echo '25 : ' . $query_insertemp; echo '<br /><br />';
		
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "5', '$this->i_ttlbrondolan', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		//echo '26 : ' . $query_insertlog; echo '<br /><br />';
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "6', '$no_BCC', '6', '$this->i_busuk', '$plan_ID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		//echo '27 : ' . $query_insertemp; echo '<br /><br />';
		
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "6', '$this->i_busuk', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		//echo '28 : ' . $query_insertlog; echo '<br /><br />';
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "7', '$no_BCC', '7', '$this->i_tangkaipanjang', '$plan_ID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		//echo '29 : ' . $query_insertemp; echo '<br /><br />';
		
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "7', '$this->i_tangkaipanjang', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		//echo '30 : ' . $query_insertlog; echo '<br /><br />';
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "8', '$no_BCC', '8', '$this->i_abnormal', '$plan_ID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		//echo '31 : ' . $query_insertemp; echo '<br /><br />';
		
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "8', '$this->i_abnormal', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		//echo '32 : ' . $query_insertlog; echo '<br /><br />';
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "9', '$no_BCC', '9', '$this->i_hama', '$plan_ID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		//echo '33 : ' . $query_insertemp; echo '<br /><br />';
		
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "9', '$this->i_hama', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		//echo '34 : ' . $query_insertlog; echo '<br /><Br />';
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "10', '$no_BCC', '10', '$this->i_alas', '$plan_ID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		//echo '35 : ' . $query_insertemp; echo '<br /><br />';
		
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "10', '$this->i_alas', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		//echo '36 : ' . $query_insertlog; echo '<br /><br />';
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "15', '$no_BCC', '15', '$this->i_jangkos', '$plan_ID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		//echo '37 : ' . $query_insertemp; echo '<br /><br />';
		
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "15', '$this->i_jangkos', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		//echo '38 : ' . $query_insertlog; echo '<br /><br />';
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "16', '$no_BCC', '16', '$this->i_buborsi', '$plan_ID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		//echo '39 : ' . $query_insertemp; echo '<br /><br />';
		
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "16', '$this->i_buborsi', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		//echo '40 : ' . $query_insertlog; echo '<br /><br />';
		
		return $result_insertemp;
	}
	
	function update_HasilPanen_Kualitas($no_BCC, $plan_ID){
		$con = connect();
		
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_mentah' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "1' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);
		//echo '41 : ' . $query_updateemp; echo '<Br /><br />';
		
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_mengkal' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "2' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);
		//echo '42 : ' . $query_updateemp; echo '<br /><br />';
			
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_masak' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "3' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);
		//echo '43 : ' . $query_updateemp; echo '<br /><br />';
		
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_toomasak' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "4' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);
		//echo '44 : ' . $query_updateemp; echo '<br /><br />';
		
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_ttlbrondolan' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "5' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);
		//echo '45 : ' . $query_updateemp; echo '<br /><br />';
		
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_busuk' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "6' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);
		//echo '46 : ' . $query_updateemp; echo '<br /><br />';
		
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_tangkaipanjang' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "7' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);
		//echo '47 : ' . $query_updateemp; echo '<br /><br />';
		
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_abnormal' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "8' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);
		//echo '48 : ' . $query_updateemp; echo '<br /><br />';
		
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_hama' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "9' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);
		//echo '49 : ' . $query_updateemp; echo '<br /><br />';
		
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_alas' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "10' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);	
		//echo '50 : ' . $query_updateemp; echo '<br /><br />';

		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_jangkos' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "15' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);		
		//echo '51 : ' . $query_updateemp; echo '<br /><br />';

		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_buborsi' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "16' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);		
		//echo '52 : ' . $query_updateemp; echo '<br /><br />';
			
		return $result_updateemp;
	}
		
	function Delete_MasterCity($CityID=""){
		if ($CityID<>"") 
		{
			$db = new Database; 
			$sql_query = "update Master.M_City set City_InactiveTime = sysdate() where City_ID = '" . $CityID . "'";
			$db->query($sql_query);
			return true;
		}
		return false;
	}
	
} // end of Master City Class
?>