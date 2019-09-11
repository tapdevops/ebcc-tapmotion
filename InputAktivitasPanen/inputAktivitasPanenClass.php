<?php
// start of input Aktivitas Panen(BCP)
class input_Aktivitas_Panen 
{
	var $s_id_ba_afd_blok;
	var $s_no_rekap_bcc;
	var $s_idRencana;
	var $i_rowCount;
	var $i_countRow;
	var $f_luasan_panen;
	var $s_status_gandeng;
	var $s_nik_gandeng;
	var $s_id_kualitas;
	var $i_qty;
	var $s_no_bcc;
	var $s_id_bcc_kualitas;
	var $s_cmb_compcode;
	var $s_cmb_BA;
	var $s_cmb_Afd;
	var $s_nikGandeng;
	var $s_id_BCC;
	var $i_BT_Pokok;
	var $i_BT_Piringan;
	var $i_PB_Piringan;
	var $i_Buah_Matahari;
	var $s_username;
	var $s_loginname;
	
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
		
		//$db->disconnect();
		$this->s_Return = $s_Return;  
	    return $this->s_Return;  
	}
	
	function Update_T_H_RencanaPanen(){
		$con = connect();
		
		$query_updatetemp = "UPDATE T_HEADER_RENCANA_PANEN set STATUS_GANDENG = 'YES' WHERE 
							 ID_RENCANA = '$this->s_idRencana'";
		$result_updatetemp = num_rows($con,$query_updatetemp);
		commit($con);
		return $result_updatetemp;
	}
	
	function Insert_T_Detail_Gandeng(){
		$con = connect();
		
		if($this->s_nikGandeng == ""){
			$query_insertemp = "INSERT INTO T_DETAIL_GANDENG (ID_GANDENG, ID_RENCANA, NIK_GANDENG) VALUES (SEQ_DETAIL_GANDENG.nextval, '$this->s_idRencana', '-')";
		}
		else{
			$query_insertemp = "INSERT INTO T_DETAIL_GANDENG (ID_GANDENG, ID_RENCANA, NIK_GANDENG) VALUES (SEQ_DETAIL_GANDENG.nextval, '$this->s_idRencana', '$this->s_nikGandeng')";
		}
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		//echo $query_insertemp;die();
		return $result_insertemp;
	}
	
	function Update_T_D_Rencana_Panen(){
		$con = connect();
		$this->f_luasan_panen = str_replace(",", ".", $this->f_luasan_panen);
		$query_updatetemp = "UPDATE T_DETAIL_RENCANA_PANEN set LUASAN_PANEN = '$this->f_luasan_panen' WHERE 
							 ID_BA_AFD_BLOK = '$this->s_id_ba_afd_blok' and NO_REKAP_BCC = '$this->s_no_rekap_bcc' and ID_RENCANA = '$this->s_idRencana'";
		$result_updatetemp = num_rows($con,$query_updatetemp);
		commit($con);
		return $result_updatetemp;
	}
	
	function Insert_T_Hasil_PK($id_kualitas){
		$con = connect();
		
		$query_select  = "select * from T_HASILPANEN_KUALTAS where ID_KUALITAS = '$id_kualitas' and ID_RENCANA = '$this->s_idRencana' and ID_BCC = '$this->s_id_BCC'";
		$result_t_HPK = oci_parse($con, $query_select);
		oci_execute($result_t_HPK, OCI_DEFAULT);
		$p = oci_fetch($result_t_HPK);
		if($p != ""){
			while ($p=oci_fetch($result_t_HPK)) {	
				$qty = oci_result($result_t_HPK, "QTY");
				$id_bcc_kualitas = oci_result($result_t_HPK, "ID_BCC_KUALITAS");
				$no_bcc = oci_result($result_t_HPK, "ID_BCC");
				if($qty == ""){
					if($id_kualitas == '11') $qty_1 = $this->i_BT_Pokok;
					else if($id_kualitas == '12') $qty_1 = $this->i_BT_Piringan;
					else if($id_kualitas == '13') $qty_1 = $this->i_PB_Piringan;
					else if($id_kualitas == '14') $qty_1 = $this->i_Buah_Matahari;
					$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA) VALUES ('" . $no_bcc.$id_kualitas . "', '$no_bcc', '$id_kualitas', '$qty_1', '$this->s_idRencana')";
					$result_insertemp = num_rows($con,$query_insertemp);
					commit($con);
				}else if($qty <> ""){
					if($id_kualitas == '11') $qty += $this->i_BT_Pokok;
					else if($id_kualitas == '12') $qty += $this->i_BT_Piringan;
					else if($id_kualitas == '13') $qty += $this->i_PB_Piringan;
					else if($id_kualitas == '14') $qty += $this->i_Buah_Matahari;
					
					$query_updatetemp = "UPDATE T_HASILPANEN_KUALTAS set QTY = '$qty' WHERE 
								 ID_RENCANA = '$this->s_idRencana' and ID_BCC_KUALITAS = '$id_bcc_kualitas' and ID_KUALITAS = '$id_kualitas'";
					$result_insertemp = num_rows($con,$query_updatetemp);
					commit($con);
					//$result_insertemp .= $query_updatetemp;
					//echo $query_updatetemp;die();
				}
			}
		}else{
			if($id_kualitas == '11') $qty_1 = $this->i_BT_Pokok;
			else if($id_kualitas == '12') $qty_1 = $this->i_BT_Piringan;
			else if($id_kualitas == '13') $qty_1 = $this->i_PB_Piringan;
			else if($id_kualitas == '14') $qty_1 = $this->i_Buah_Matahari;
			$query_insertemp = "INSERT INTO T_HASILPANEN_KUALTAS (ID_BCC_KUALITAS, ID_BCC, ID_KUALITAS, QTY, ID_RENCANA) VALUES ('" . $this->s_id_BCC.$id_kualitas . "', '$this->s_id_BCC', '$id_kualitas', '$qty_1', '$this->s_idRencana')";
			$result_insertemp = num_rows($con,$query_insertemp);
			commit($con);
			$query_insertlog = "INSERT INTO T_LOG_HASILPANEN_KUALITAS (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_BCC_KUALITAS, NEW_VALUE_QTY, 
							OLD_VALUE_QTY, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasilpanen_kualtas', '" . $this->s_id_BCC.$id_kualitas . "', '$qty_1', '', 'Website', sysdate)";
			$result_insertlog = num_rows($con,$query_insertlog);
			commit($con);
		}
		return $result_insertemp;
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