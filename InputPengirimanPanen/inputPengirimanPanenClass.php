<?php
// start of input Hasil Panen(BCP)
class input_Pengiriman_Panen 
{
	var $s_PlanID;
	var $s_no_bcc;
	var $s_idBA;
	
	var $s_tgl_kirim;
	var $s_id_nab_tgl;
	var $s_no_nab;
	var $i_tipe_order;
	var $s_tipe_order;
	var $s_id_int_order;
	var $i_no_polisi;
	var $i_nik_supir;
	var $nik_tkg_muat1;
	var $nik_tkg_muat2;
	var $nik_tkg_muat3;
	var $i_Row;
	var $c_box;
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
	
	
	
	function insert_T_NAB(){
		$con = connect();
		if($this->nik_tkg_muat1 == "") $this->nik_tkg_muat1 = "-";
		if($this->nik_tkg_muat2 == "") $this->nik_tkg_muat2 = "-";
		if($this->nik_tkg_muat3 == "") $this->nik_tkg_muat3 = "-";
		
		$query_insertemp = "INSERT INTO T_NAB (ID_NAB_TGL, NO_NAB, TGL_NAB, TIPE_ORDER, ID_INTERNAL_ORDER, NO_POLISI, NIK_SUPIR, NIK_TUKANG_MUAT1, NIK_TUKANG_MUAT2, NIK_TUKANG_MUAT3, STATUS_DOWNLOAD) VALUES ('$this->s_id_nab_tgl', '$this->s_no_nab', to_date('" . $this->s_tgl_kirim . "','mm-dd-yyyy hh24:mi:ss'), '$this->s_tipe_order', '$this->s_id_int_order', '$this->i_no_polisi', '$this->i_nik_supir', '$this->nik_tkg_muat1', '$this->nik_tkg_muat2', '$this->nik_tkg_muat3', 'N')";
		$result_insertemp = num_rows($con,$query_insertemp);
		commit($con);
		$query_insertlog = "INSERT INTO T_LOG_NAB (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_ID_NAB_TGL, CREEDIT_FROM, SYNC_SERVER, NEW_SUPIR,
								NEW_TUKANG_MUAT_1, NEW_TUKANG_MUAT_2, NEW_TUKANG_MUAT_3, NEW_STATUS_DOWNLOAD, NEW_TYPE_ORDER, NEW_ID_INTERNAL_ORDER, NEW_NO_POLISI) VALUES ('INSERT', sysdate, '$this->s_username', '$this->s_loginname',
								't_nab', '$this->s_id_nab_tgl', 'Website', sysdate, '$this->i_nik_supir', '$this->nik_tkg_muat1', '$this->nik_tkg_muat2', '$this->nik_tkg_muat3', 'N', 
								'$this->s_tipe_order', '$this->s_id_int_order', '$this->i_no_polisi')";
		$result_insertlog = num_rows($con, $query_insertlog);
		commit($con);
		$query_insertlog = "INSERT INTO T_TIMESTAMP (ID_TIMESTAMP, TYPE_TIMESTAMP, START_INS_TIME, END_INS_TIME)
							VALUES ('$this->s_id_nab_tgl', 'Input Pengiriman Hasil Panen', sysdate, sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		//if($result_insertemp == '1') $result_insertemp = 'true
		return $result_insertemp;
	}
	
	function Update_T_Hasil_Panen(){
		$con = connect();
		
		$query_updatetemp = "UPDATE T_HASIL_PANEN set STATUS_BCC = 'DELIVERED', ID_NAB_TGL = '$this->s_id_nab_tgl' WHERE 
							 ID_RENCANA = '$this->s_PlanID' and NO_BCC = '$this->s_no_bcc'";
		$result_updatetemp = num_rows($con,$query_updatetemp);
		commit($con);
		
		$query_d_ticket = "select KODE_DELIVERY_TICKET from T_HASIL_PANEN where ID_RENCANA = '$this->s_PlanID' and NO_BCC = '$this->s_no_bcc'";
		$result_d_ticket = oci_parse($con, $query_d_ticket);
		oci_execute($result_d_ticket, OCI_DEFAULT);
		oci_fetch($result_d_ticket);
		$deliv_tiket = oci_result($result_d_ticket, "KODE_DELIVERY_TICKET");
		
		$query_insertlog = "INSERT INTO T_LOG_HASIL_PANEN (INSERTUPDATE, TGL_CREEDIT, NIK_CREEDITOR, LOGIN_NAME_CREEDITOR, ON_TABLE, ON_NO_BCC, ON_KODE_DELIVERY_TICKET, 
							NEW_VALUE_STATUS_BCC, OLD_VALUE_STATUS_BCC, CREEDIT_FROM, SYNC_SERVER)
							VALUES ('UPDATE', sysdate, '$this->s_username', '$this->s_loginname',
								't_hasil_panen', '$this->s_no_bcc', '$deliv_tiket', 'DELIVERED', 'RESTAN', 'Website', sysdate)";
		$result_insertlog = num_rows($con,$query_insertlog);
		commit($con);
		return $result_updatetemp;
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