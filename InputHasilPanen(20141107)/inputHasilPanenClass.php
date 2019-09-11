<?php
// start of input Hasil Panen(BCP)
class input_Hasil_Panen 
{
	var $s_PlanID;
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
	var $i_tangkaipanjang;
	var $i_abnormal;
	var $i_hama;
	var $i_alas;
	var $i_ttlbrondolan;
	var $s_username;
	var $s_loginname;

	//var $s_StatusGandeng;
	var $s_Return;
	
	function Show_AllMasterCity_Table()
	{
		$cryp = new tap_encryp;
		$s_header_bgColor = "#CCCCCC";
		$s_Return = "";
		$s_Return = "<table border=\"1px\" width=\"85%\" align=\"center\" cellpadding=\"1px\" cellspacing=\"1px\">";
        $s_Return = $s_Return . "<th bgcolor=\"" . $s_header_bgColor . "\">ID Kota</th>";
		$s_Return = $s_Return . "<th bgcolor=\"" . $s_header_bgColor . "\">Kode Kota</th>";
		$s_Return = $s_Return . "<th bgcolor=\"" . $s_header_bgColor . "\">Nama Kota</th>";
		$s_Return = $s_Return . "<th bgcolor=\"" . $s_header_bgColor . "\">Kabupaten</th>";
		$s_Return = $s_Return . "<th bgcolor=\"" . $s_header_bgColor . "\">Propinsi</th>";
		$s_Return = $s_Return . "<th bgcolor=\"" . $s_header_bgColor . "\">Kode Telepon</th>";
		$s_Return = $s_Return . "<th bgcolor=\"" . $s_header_bgColor . "\">Nama Area</th>";
		$s_Return = $s_Return . "<th bgcolor=\"" . $s_header_bgColor . "\">&nbsp;</th>";        
		
		$nomor = 0;
		$dataperpage = 10;

		if(isset($_GET['page'])){
    		$noPage = $_GET['page'];
		} 
		else $noPage = 1;

		$offset = ($noPage - 1) * $dataperpage;
		
		$db = new Database; 	
		$Query = "select a.City_ID as City_ID, a.City_Code as City_Code, a.City_Name as City_Name, b.Area_Name as Area_Name, a.City_Kab as City_Kab, a.City_Province as City_Province, a.City_CodePhone as City_CodePhone from Master.M_City a, Master.M_Area b where City_InactiveTime is null and b.Area_ID = a.City_AreaID LIMIT $offset, $dataperpage"; 
		$db->query($Query);
		while ($db->nextRecord()) 
			{ 
				$s_Return = $s_Return . "<tr>";
			  	$s_Return = $s_Return . "<td>" . $db->Record['City_ID'] . "</td>";
			  	$s_Return = $s_Return . "<td>" . $db->Record['City_Code'] . "</td>";
			  	$s_Return = $s_Return . "<td>" . $db->Record['City_Name'] . "</td>";
			  	$s_Return = $s_Return . "<td>" . $db->Record['City_Kab'] . "</td>";
			  	$s_Return = $s_Return . "<td>" . $db->Record['City_Province'] . "</td>";
			  	$s_Return = $s_Return . "<td>" . $db->Record['City_CodePhone'] . "</td>";
			  	$s_Return = $s_Return . "<td>" . $db->Record['Area_Name'] . "</td>";
			  	
				$s_Return = $s_Return . "<td align=center><a href=\"mst_city.php?s=" . $cryp->encrypt('edit') . "&cid=" . $cryp->encrypt($db->Record['City_ID']) . "\"><img src=\"Images/Icon-Edit-2.png\" border=0 alt=\"Edit\" title=\"Ubah\"/></a> ";
				$s_Return = $s_Return . "<a href=\"mst_city_prosess.php?s=" . $cryp->encrypt('delete') . "&cid=" . $cryp->encrypt($db->Record['City_ID']) . "\"><img src=\"Images/icon-delete.png\" border=0 alt=\"Delete\" title=\"Hapus\"/></a></td>";
			  	$s_Return = $s_Return . "</tr>";
			}
		$s_Return = $s_Return . "<tr>";
		$s_Return = $s_Return . "<td>&nbsp;</td>";
		$s_Return = $s_Return . "<td>&nbsp;</td>";
		$s_Return = $s_Return . "<td>&nbsp;</td>";
			
		$Query = "select count(*) as jumData from Master.M_City a, Master.M_Area b where City_InactiveTime is null and b.Area_ID = a.City_AreaID"; 
		$db->query($Query);
		$db->singleRecord();
			
		$jumData = $db->Record['jumData'];
		$jumPage = ceil($jumData/$dataperpage);
		
		$s_Return = $s_Return . "<td colspan=5 align=center>";
		if ($noPage > 1) $s_Return = $s_Return . "<a href='".$_SERVER['PHP_SELF']."?page=".($noPage-1)."'>&lt;&lt; Prev</a>";
			
		for($page = 1; $page <= $jumPage; $page++){
         	if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage)){   
            	if ($page == $noPage) $s_Return = $s_Return . " <b>".$page."</b> ";
            else $s_Return = $s_Return . " <a href='".$_SERVER['PHP_SELF']."?page=".$page."' style='text-align:center'>".$page."</a> ";
            $showPage = $page;          
         	}
		}
		if ($noPage < $jumPage) $s_Return = $s_Return . "<a href='".$_SERVER['PHP_SELF']."?page=".($noPage+1)."'>Next &gt;&gt;</a>";
		
		$s_Return = $s_Return . "</td>";
		$s_Return = $s_Return . "</tr>";
		$s_Return = $s_Return . "</table>";
		
		$this->s_Return = $s_Return;  
	    return $this->s_Return;  
	}
	
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
		$plan_ID = oci_result($result_id_rencana, "ID_RENCANA");
		if($plan_ID <> ""){
			$result_cekvalidasi = 1;
		}else $result_cekvalidasi = 0;
		
		return $result_cekvalidasi;
	}
	
	function insert_HeaderRencanaPanen(){
		$con = connect();
		$query_id_rencana = "select ID_RENCANA from T_HEADER_RENCANA_PANEN where ID_RENCANA = '$this->s_PlanID'";
							
		$result_id_rencana = oci_parse($con, $query_id_rencana);
		oci_execute($result_id_rencana, OCI_DEFAULT);
		oci_fetch($result_id_rencana);
		$plan_ID = oci_result($result_id_rencana, "ID_RENCANA");
		
		if($plan_ID == ""){
			$query_insertemp = "INSERT INTO T_HEADER_RENCANA_PANEN (ID_RENCANA, TANGGAL_RENCANA, NIK_MANDOR, NIK_KERANI_BUAH, NIK_PEMANEN, STATUS_GANDENG)
				VALUES ('$this->s_PlanID', to_date('" . $this->s_PlanDate . "','mm-dd-yyyy hh24:mi:ss'), '$this->s_MandorNIK', '$this->s_KraniNIK', '$this->s_PemanenNIK', 'NO')";
			$result_insertemp = num_rows($con,$query_insertemp);
			commit($con);		
		}else{
			$result_insertemp = 1;
		}
		return $result_insertemp;
	}
	
	function insert_DetailRencanaPanen(){
		$con = connect();
		
		$id_ba_afd_blok = $this->s_afd . $this->s_blok;
		
		$datePlan = date('ymd', strtotime($this->s_PlanDate));
		$datePlan1 = date('Ymd', strtotime($this->s_PlanDate));
		$no_rekap = $datePlan . $this->s_blok;
		$no_rekap_BCC = "";
		$query_no_rekap_bcc = "SELECT NO_REKAP_BCC FROM T_DETAIL_RENCANA_PANEN WHERE NO_REKAP_BCC LIKE '$no_rekap%' AND 
								ID_RENCANA LIKE '" . $datePlan1 . ".%." . $this->s_PemanenNIK . "'";
								
		$result_no_rekap_bcc = oci_parse($con, $query_no_rekap_bcc);
		oci_execute($result_no_rekap_bcc, OCI_DEFAULT);
		oci_fetch($result_no_rekap_bcc);
		$no_rekap_BCC = oci_result($result_no_rekap_bcc, "NO_REKAP_BCC");
		if($no_rekap_BCC == ""){
			$query_seq_numb = "SELECT max(SUBSTR(NO_REKAP_BCC, 10, 2)) as MAX_SEQUENCE FROM T_DETAIL_RENCANA_PANEN WHERE NO_REKAP_BCC LIKE '$no_rekap%'";
			$result_seq_numb = oci_parse($con, $query_seq_numb);
						oci_execute($result_seq_numb, OCI_DEFAULT);
						while ($p=oci_fetch($result_seq_numb)) {	
								$res_seq_numb = oci_result($result_seq_numb, "MAX_SEQUENCE");
						}
			if($res_seq_numb == ""){
				$seq_numb = "01";
			}else{
				$res_seq_numb += 1;
				$seq_numb = $res_seq_numb;
			}
			$no_rekap_BCC = $no_rekap . $seq_numb;
			$no_Rekap_BCC_temp = $datePlan . "." . $this->s_blok . "." . $seq_numb;
			$query_insertemp = "INSERT INTO T_DETAIL_RENCANA_PANEN (ID_BA_AFD_BLOK, NO_REKAP_BCC, ID_RENCANA, LUASAN_PANEN)
				VALUES ('$id_ba_afd_blok', '$no_rekap_BCC', '$this->s_PlanID', '0')";
			$result_insertemp = num_rows($con,$query_insertemp);
			commit($con);
			$query_insertlog = "INSERT INTO T_LOG_RENCANA_PANEN (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_RENCANA, ON_NO_REKAP_BCC,
								NEW_VALUE_ID_BA_AFD_BLOK, OLD_VALUE_ID_BA_AFD_BLOK, CREEDIT_FROM, SYNC_SERVER) VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_detail_rencana_panen', '$this->s_PlanID', '$no_rekap_BCC', '$id_ba_afd_blok', '', 'Website', sysdate)";
			$result_insertlog = num_rows($con, $query_insertlog);
			commit($con);
		}else{
			$result_insertemp = 1;
		}
		$returnHasilP = $this->insert_HasilPanen($no_rekap_BCC, $no_Rekap_BCC_temp);
		$split = explode(" . # . ", $returnHasilP);
		if($split[0] == '1'){
			return $result_insertemp . " . # . " . $split[1];
		}else{
			return $result_insertemp . " . # . Not Success";
		}
	}
	
	function insert_HasilPanen($no_rekap_BCC, $no_Rekap_BCC_temp){
		$con = connect();
		
		$no_BCC = $no_rekap_BCC . $this->s_TPH . $this->s_deliveryTicket . "B";
		
		$query_insertemp = "INSERT INTO T_HASIL_PANEN (ID_RENCANA, NO_REKAP_BCC, NO_TPH, NO_BCC, KODE_DELIVERY_TICKET, LATITUDE, LONGITUDE, PICTURE_NAME, ID_NAB_TGL)
			VALUES ('$this->s_PlanID', '$no_rekap_BCC', '$this->s_TPH', '$no_BCC', '$this->s_deliveryTicket', '0', '0', '0', '0')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);	
		
		$query_insertlog = "INSERT INTO T_LOG_HASIL_PANEN (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_NO_BCC, ON_KODE_DELIVERY_TICKET, 
							NEW_VALUE_STATUS_BCC, OLD_VALUE_STATUS_BCC, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasil_panen', '$no_BCC', '$this->s_deliveryTicket', 'RESTAN', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		
		$no_BCC1 = $no_Rekap_BCC_temp . "." . $this->s_TPH . "." . $this->s_deliveryTicket . "." . "B";
		$query_insertlog = "INSERT INTO T_TIMESTAMP (ID_TIMESTAMP, TYPE_TIMESTAMP, START_INS_TIME, END_INS_TIME)
							VALUES ('$no_BCC1', 'Input Hasil Panen', sysdate, sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		
		$id_ba_afd_blok = $this->s_afd . $this->s_blok;
		$datePlan = date('ymd', strtotime($this->s_PlanDate));
		$datePlan1 = date('Ymd', strtotime($this->s_PlanDate));
		$nik = $this->s_PemanenNIK;
		
		$query_rencana  = "select ID_RENCANA from T_HASILPANEN_KUALTAS where ID_BCC_KUALITAS like '$no_BCC%' and ID_RENCANA = '$datePlan1.MANUAL.$nik' 
							and ID_KUALITAS in ('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '15')";
		$result_rencana = oci_parse($con, $query_rencana);
					oci_execute($result_rencana, OCI_DEFAULT);
					while ($p=oci_fetch($result_rencana)) {	
							$res_id_rencana = oci_result($result_rencana, "ID_RENCANA");
					}
		if($res_id_rencana == ""){
			$returnHasilK = $this->insert_HasilPanen_Kualitas($no_BCC);
		}else{
			//$returnHasilK = $this->update_HasilPanen_Kualitas($no_BCC, $res_id_rencana);
		}
		return $returnHasilK . " . # . " . $no_BCC;
	}
	
	function insert_HasilPanen_Kualitas($no_BCC){
		$con = connect();
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "1', '$no_BCC', '1', '$this->i_mentah', '$this->s_PlanID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "1', '$this->i_mentah', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "2', '$no_BCC', '2', '$this->i_mengkal', '$this->s_PlanID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "2', '$this->i_mengkal', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		
			
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "3', '$no_BCC', '3', '$this->i_masak', '$this->s_PlanID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "3', '$this->i_masak', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "4', '$no_BCC', '4', '$this->i_toomasak', '$this->s_PlanID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "4', '$this->i_toomasak', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "5', '$no_BCC', '5', '$this->i_ttlbrondolan', '$this->s_PlanID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "5', '$this->i_ttlbrondolan', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "6', '$no_BCC', '6', '$this->i_busuk', '$this->s_PlanID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "6', '$this->i_busuk', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "7', '$no_BCC', '7', '$this->i_tangkaipanjang', '$this->s_PlanID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "7', '$this->i_tangkaipanjang', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "8', '$no_BCC', '8', '$this->i_abnormal', '$this->s_PlanID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "8', '$this->i_abnormal', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "9', '$no_BCC', '9', '$this->i_hama', '$this->s_PlanID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "9', '$this->i_hama', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "10', '$no_BCC', '10', '$this->i_alas', '$this->s_PlanID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "10', '$this->i_alas', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		
		$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA)
			VALUES ('" . $no_BCC . "15', '$no_BCC', '15', '$this->i_jangkos', '$this->s_PlanID')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $no_BCC . "15', '$this->i_jangkos', '', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
			
		return $result_insertemp;
	}
	
	function update_HasilPanen_Kualitas($no_BCC, $plan_ID){
		$con = connect();
		
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_mentah' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "1' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);
		
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_mengkal' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "2' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);
			
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_masak' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "3' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);
		
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_toomasak' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "4' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);
		
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_ttlbrondolan' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "5' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);
		
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_busuk' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "6' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);
		
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_tangkaipanjang' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "7' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);
		
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_abnormal' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "8' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);
		
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_hama' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "9' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);
		
		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_alas' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "10' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);	

		$query_updateemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$this->i_jangkos' WHERE 
							ID_BCC_KUALITAS = '" . $no_BCC . "15' AND ID_RENCANA = '$plan_ID'";
		$result_updateemp = num_rows($con,$query_updateemp);
		commit($con);			
			
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