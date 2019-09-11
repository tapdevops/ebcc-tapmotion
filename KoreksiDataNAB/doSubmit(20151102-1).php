<?php
session_start();
if(isset($_POST['s_Supir']) || isset($_POST['s_TM1']) || isset($_POST['s_TM2']) || isset($_POST['s_TM3']) || isset($_POST["editNO_NAB"]) || isset($_POST["sTIPE_ORDER"]) || isset($_POST["sNO_POLISI"]) || isset($_POST["sID_INTERNAL_ORDER"]) || isset($_POST["countRow"]) || isset($_POST["startInputBCC"]) ){


$TIPE_ORDER 	= $_POST['ssTIPE_ORDER'];
if($TIPE_ORDER == ''){
	$TIPE_ORDER = $_POST['s_old_TIPE_ORDER'];
}

$supir = $_POST['s_Supir'];
$id_BA = $_POST['s_BA'];

$TM1 = $_POST['s_TM1'];
$TM2 = $_POST['s_TM2'];
$TM3 = $_POST['s_TM3'];
$ID_NAB_TGL = $_POST["editNO_NAB"];
$NIK 			= $_SESSION['NIK'];
$Login_Name 	= $_SESSION['LoginName'];
$No_Polisi 		= $_POST['sNO_POLISI'];
$Id_Internal_Order 	= $_POST['sID_INTERNAL_ORDER1'];

//echo $Id_Internal_Order;die();
//echo "$ID_NAB_TGL";die();
$No_NAB 		= $_POST['tmpNo_NAB'];
$tglNAB 		= $_POST['tglNAB'];
$startInputBcc = $_POST['startInputBCC'];
$countRow = $_POST['countRow'];
//echo $supir . " - " . $TM1 . " - " . $TM2 . " - " . $TM3 . " - " . $ID_NAB_TGL . " - " . $NIK . " - " . $Login_Name . " - " . $No_Polisi . " - " . $Id_Internal_Order
 //. " - " . $No_NAB . " - " . $tglNAB . " - " . $startInputBcc;die();//0000259
$tmp_date = '01-01-70';
//echo $countRow;die();
//$tmp_date = date('Y-M-d', strtotime($tglNAB));
$tmp_dateNAB = date('Y-m-d', strtotime($tglNAB));
//echo $tmp_dateNAB . " - ";
for($d_array = 1; $d_array <= $countRow; $d_array++){
	$tmp_tgl_rncana = date('Y-M-d', strtotime($_POST['tgl_rencana'.$d_array]));
	if($tmp_date < $tmp_tgl_rncana){
		$tmp_date = date('Y-m-d', strtotime($tmp_tgl_rncana));
	}
	//echo $_POST['tgl_rencana'.$d_array] . "<br>";
}//echo $tmp_date; 

if($tglNAB <> '01-01-1970'){
	$tglNAB = date("m-d-Y", strtotime($tglNAB));
}
//echo "TMP_NAB = " . $tmp_dateNAB . "; TMP_DATE = " . $tmp_date;die();
if($tmp_dateNAB >= $tmp_date){

		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();
		
		//cek data yang diinput sama atau tidak
		$sql_select_old = "select * from t_nab where ID_NAB_TGL = '$ID_NAB_TGL'";
		$result_select_old = oci_parse($con, $sql_select_old);
		oci_execute($result_select_old, OCI_DEFAULT);
		while(oci_fetch($result_select_old)){
			$Old_Supir[] =  oci_result($result_select_old, "NIK_SUPIR");
			$Old_Tukang_Muat_1[] =  oci_result($result_select_old, "NIK_TUKANG_MUAT1");
			$Old_Tukang_Muat_2[] =  oci_result($result_select_old, "NIK_TUKANG_MUAT2");
			$Old_Tukang_Muat_3[] =  oci_result($result_select_old, "NIK_TUKANG_MUAT3");
			$Old_Id_Internal_Order[] =  oci_result($result_select_old, "ID_INTERNAL_ORDER");
			$Old_No_Polisi[] 	 =  oci_result($result_select_old, "NO_POLISI");
			$Old_Type_Order[] 	 =  oci_result($result_select_old, "TIPE_ORDER");
		}
		$roweffec_select_old = oci_num_rows($result_select_old);
		//echo $sql_select_old."<br>".$TM1."<br>".$Old_Tukang_Muat_1[0];
		
		//cek data yang diinput sama atau tidak
		$sql_select = "select * from t_nab where NO_NAB = '$No_NAB' and TGL_NAB = to_date('" . $tglNAB . "','mm-dd-yyyy') and NIK_SUPIR = '$supir' and NIK_TUKANG_MUAT1 = '$TM1' and NIK_TUKANG_MUAT2 = '$TM2' and NIK_TUKANG_MUAT3 = '$TM3' and ID_NAB_TGL = '$ID_NAB_TGL' and NO_POLISI = '$No_Polisi' ";
		$result_select = oci_parse($con, $sql_select);
		oci_execute($result_select, OCI_DEFAULT);
		while(oci_fetch($result_select)){
		}
		$roweffec_select = oci_num_rows($result_select);
		//echo $roweffec_select . " - " . $startInputBcc . " - " . $countRow;die();
		if($roweffec_select > 0 && $startInputBcc == $countRow){
			$_SESSION["err"] = "You input same data. No data updated";
			unset($_SESSION['editNO_NAB']);
			unset($_SESSION["sql_t_NAB"]);
			
			unset($_SESSION['BASupir']);
			unset($_SESSION['NIK_Supir']);
			unset($_SESSION['Nama_Supir']);
			unset($_SESSION['Afd_Supir']);
			
			unset($_SESSION['BATM1']);
			unset($_SESSION['NIK_TM1']);
			unset($_SESSION['Nama_TM1']);
			unset($_SESSION['Afd_TM1']);
			
			unset($_SESSION['BATM2']);
			unset($_SESSION['NIK_TM2']);
			unset($_SESSION['Nama_TM2']);
			unset($_SESSION['Afd_TM2']);
			
			unset($_SESSION['BATM3']);
			unset($_SESSION['NIK_TM3']);
			unset($_SESSION['Nama_TM3']);
			unset($_SESSION['Afd_TM3']);
			unset($_SESSION['SessTIPE_ORDER']);
			unset($_SESSION['No_PolisiLabel']);
			unset($_SESSION['Id_Internal_OrderLabel']);
			
			//header("Location:KoreksiNABFil.php");
			echo "2";
			
		}
		else{ 
			if($roweffec_select < 1){
			//jika type order internal
			$Flag = true; //untuk menentukan lempar ke halaman mana message errornya.
			if($Old_Type_Order[0] == "INTERNAL"){
			$a = 1;
				//type order internal tidak boleh kosong, maka jika kosong
				//echo "internal".$No_Polisi."<br>";die();
				if($Id_Internal_Order == "" || $Id_Internal_Order == NULL){
					$a = 2;
					$_SESSION["err_type_order"] = "No Polisi not valid for Type Order Internal";
					//header("Location:KoreksiNABSelect.php");
					$Flag = false;
				}
				else{
					$a = 3;
					$sql_cek_supir = "select count(*) jml from t_employee where nik = '$supir'";
					//echo $sql_cek_supir;
					$select_cek_supir = oci_parse($con,$sql_cek_supir);
					oci_execute($select_cek_supir, OCI_DEFAULT);
					oci_fetch($select_cek_supir);
					$roweffec_cek_supir = oci_result($select_cek_supir, "JML"); 
					if($roweffec_cek_supir == 0)
					{
						$a = 4;
						$_SESSION["err_cek_supir"] = "NIK Supir is not valid, please check again";
						//header("Location:KoreksiNABSelect.php");
						$Flag = false; //die();
					}
					else
					{
						$a = 5;
						
						$query_nab = "select NO_NAB, to_char(TGL_NAB, 'mm-dd-yyyy') as TGL_NAB from T_NAB where ID_NAB_TGL = '" . $ID_NAB_TGL . "'";
						$result_nab = oci_parse($con, $query_nab);
						oci_execute($result_nab, OCI_DEFAULT);
						oci_fetch($result_nab);
						$old_no_nab = oci_result($result_nab, "NO_NAB");
						$old_tgl_nab = oci_result($result_nab, "TGL_NAB");
						
						//added by NB 20.10.2015
						$query_IO = "select ID_INTERNAL_ORDER from T_INTERNAL_ORDER where NO_POLISI = '" . $No_Polisi . "' AND ID_BA = '" . $id_BA . "'";
						$result_IO = oci_parse($con, $query_IO);
						oci_execute($result_IO, OCI_DEFAULT);
						oci_fetch($result_IO);
						$id_IO = oci_result($result_IO, "ID_INTERNAL_ORDER");
						
						if($id_IO == ''){
							$id_IO = '-';
						}
						//end added by NB 20.10.2015
						
						$sql_value = "UPDATE t_nab SET NO_NAB = '$No_NAB', TGL_NAB = to_date('" . $tglNAB . "','mm-dd-yyyy hh24:mi:ss'),
						NIK_SUPIR = '$supir', NIK_TUKANG_MUAT1 = '$TM1', NIK_TUKANG_MUAT2 = '$TM2', NIK_TUKANG_MUAT3 = '$TM3', 
						NO_POLISI = '$No_Polisi',ID_INTERNAL_ORDER = '$Id_Internal_Order', TIPE_ORDER = '$TIPE_ORDER'
						WHERE ID_NAB_TGL = '$ID_NAB_TGL' ";
						$roweffec_value = num_rows($con,$sql_value);
						//echo $sql_value;
						$sql_value_t_log_nab = "INSERT INTO t_log_nab 
						(InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_NAB_Tgl, 
						CreEdit_From, Sync_Server, 
						New_Supir, Old_Supir, 
						New_Tukang_Muat_1, Old_Tukang_Muat_1, 
						New_Tukang_Muat_2, Old_Tukang_Muat_2, 
						New_Tukang_Muat_3, Old_Tukang_Muat_3,
						New_Type_Order, Old_Type_Order,
						New_Id_Internal_Order, Old_Id_Internal_Order,
						New_No_Polisi, Old_No_Polisi,
						NEW_NO_NAB, OLD_NO_NAB,
						NEW_TGL_NAB, OLD_TGL_NAB ) 
						VALUES
						('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_nab', '$ID_NAB_TGL', 'Website', SYSDATE, 
						'$supir', '$Old_Supir[0]', 
						'$TM1', '$Old_Tukang_Muat_1[0]', 
						'$TM2', '$Old_Tukang_Muat_2[0]', 
						'$TM3', '$Old_Tukang_Muat_3[0]',
						'$TIPE_ORDER', '$Old_Type_Order[0]',
						'$Id_Internal_Order', '$Old_Id_Internal_Order[0]',
						'$No_Polisi', '$Old_No_Polisi[0]',
						'$No_NAB', '$old_no_nab',
						to_date('" . $tglNAB . "','mm-dd-yyyy hh24:mi:ss'), to_date('" . $old_tgl_nab . "','mm-dd-yyyy hh24:mi:ss'))" ;
						$result_value_t_log_nab = num_rows($con,$sql_value_t_log_nab);
						
						$Flag = true;
					}
				}
			}
			//jika type order eksternal
			else{
				$a = 12;
				if($No_Polisi == "" || $No_Polisi == NULL || $No_Polisi == "undefined"){
					$_SESSION["err_type_order"] = "No Polisi can not be Null or undefined for INTERNAL Type Order";
					//header("Location:KoreksiNABSelect.php");
					$Flag = false;
				}
				else{
					$query_nab = "select NO_NAB, to_char(TGL_NAB, 'mm-dd-yyyy') as TGL_NAB from T_NAB where ID_NAB_TGL = '" . $ID_NAB_TGL . "'";
					$result_nab = oci_parse($con, $query_nab);
					oci_execute($result_nab, OCI_DEFAULT);
					oci_fetch($result_nab);
					$old_no_nab = oci_result($result_nab, "NO_NAB");
					$old_tgl_nab = oci_result($result_nab, "TGL_NAB");

					//added by NB 20.10.2015
					$query_IO = "select ID_INTERNAL_ORDER from T_INTERNAL_ORDER where NO_POLISI = '" . $No_Polisi . "' AND ID_BA = '" . $id_BA . "'";
					$result_IO = oci_parse($con, $query_IO);
					oci_execute($result_IO, OCI_DEFAULT);
					oci_fetch($result_IO);
					$id_IO = oci_result($result_IO, "ID_INTERNAL_ORDER");
					
					if($id_IO == ''){
						$id_IO = '-';
					}
					//end added by NB 20.10.2015
					
					$sql_value = "UPDATE t_nab SET 
					NO_NAB = '$No_NAB', TGL_NAB = to_date('" . $tglNAB . "','mm-dd-yyyy hh24:mi:ss'),
					NIK_SUPIR = '$supir', NIK_TUKANG_MUAT1 = '$TM1', NIK_TUKANG_MUAT2 = '$TM2', NIK_TUKANG_MUAT3 = '$TM3', 
					NO_POLISI = '$No_Polisi',ID_INTERNAL_ORDER = '$id_IO', TIPE_ORDER = '$TIPE_ORDER'
					WHERE ID_NAB_TGL = '$ID_NAB_TGL'";
					//echo $sql_value;
						
					$roweffec_value = num_rows($con,$sql_value);
					
					
					$sql_value_t_log_nab = "INSERT INTO t_log_nab 
					(InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_NAB_Tgl, 
					CreEdit_From, Sync_Server, 
					New_Supir, Old_Supir, 
					New_Tukang_Muat_1, Old_Tukang_Muat_1, 
					New_Tukang_Muat_2, Old_Tukang_Muat_2, 
					New_Tukang_Muat_3, Old_Tukang_Muat_3,
					New_Type_Order, Old_Type_Order,
					New_Id_Internal_Order, Old_Id_Internal_Order,
					New_No_Polisi, Old_No_Polisi,
					NEW_NO_NAB, OLD_NO_NAB,
					NEW_TGL_NAB, OLD_TGL_NAB) 
					VALUES
					('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_nab', '$ID_NAB_TGL', 'Website', SYSDATE, 
					'$supir', '$Old_Supir[0]', 
					'$TM1', '$Old_Tukang_Muat_1[0]', 
					'$TM2', '$Old_Tukang_Muat_2[0]', 
					'$TM3', '$Old_Tukang_Muat_3[0]',
					'$TIPE_ORDER', '$Old_Type_Order[0]',
					'$Id_Internal_Order', '$Old_Id_Internal_Order[0]',
					'$No_Polisi', '$Old_No_Polisi[0]',
					'$No_NAB', '$old_no_nab',
					to_date('" . $tglNAB . "','mm-dd-yyyy hh24:mi:ss'), to_date('" . $old_tgl_nab . "','mm-dd-yyyy hh24:mi:ss'))" ;
					$result_value_t_log_nab = num_rows($con,$sql_value_t_log_nab);
					
					$Flag = true;
				}
			}
		}		
		if ($startInputBcc <> $countRow){
			for($c_array = $startInputBcc + 1; $c_array <= $countRow; $c_array++){
				$id_rencana = $_POST['id_rencana'.$c_array];
				$no_bcc = $_POST['t_no_bcc'.$c_array];
				$kd_detik = $_POST['t_kd_d_ticket'.$c_array];
				if($id_rencana <> ''){
					$sql_value = "UPDATE t_hasil_panen SET 
					ID_NAB_TGL = '$ID_NAB_TGL', STATUS_BCC = 'DELIVERED'
					WHERE ID_RENCANA = '$id_rencana' AND NO_BCC = '$no_bcc' ";
					$roweffec_value = num_rows($con,$sql_value);
					
					$sql_value_t_log_hasilpanen = "INSERT INTO t_log_hasil_panen 
					(InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_NO_BCC, 
					ON_KODE_DELIVERY_TICKET, NEW_VALUE_STATUS_BCC, 
					OLD_VALUE_STATUS_BCC, CREEDIT_FROM, SYNC_SERVER) 
					VALUES
					('UPDATE', SYSDATE, '$NIK', '$Login_Name', 't_hasil_panen', '$no_bcc', '$kd_detik', 'DELIVERED', 'RESTAN', 
					'Website', sysdate)" ;
					$result_value_t_log_hasilpanen = num_rows($con,$sql_value_t_log_hasilpanen);
					$Flag = true;
				}
			}
		}
			//echo $roweffec_value . " - " . $result_value_t_log_nab . " - " . $result_value_t_log_hasilpanen;
			//echo $sql_value. $roweffec_value."<br>".$sql_value_t_log_nab. $result_value_t_log_nab. "<br>". $sql_select_old.$Old_Type_Order[0];die();
			if(($roweffec_value > 0 && $result_value_t_log_nab > 0) || ($roweffec_value > 0 && $result_value_t_log_hasilpanen > 0)){
				commit($con);
				$_SESSION["err"] = "Data updated";
				if($roweffec_value > 0 && $result_value_t_log_nab > 0){
					unset($_SESSION['editNO_NAB']);
					
					unset($_SESSION['BASupir']);
					unset($_SESSION['NIK_Supir']);
					unset($_SESSION['Nama_Supir']);
					unset($_SESSION['Afd_Supir']);
					
					unset($_SESSION['BATM1']);
					unset($_SESSION['NIK_TM1']);
					unset($_SESSION['Nama_TM1']);
					unset($_SESSION['Afd_TM1']);
					
					unset($_SESSION['BATM2']);
					unset($_SESSION['NIK_TM2']);
					unset($_SESSION['Nama_TM2']);
					unset($_SESSION['Afd_TM2']);
					
					unset($_SESSION['BATM3']);
					unset($_SESSION['NIK_TM3']);
					unset($_SESSION['Nama_TM3']);
					unset($_SESSION['Afd_TM3']);
					unset($_SESSION['SessTIPE_ORDER']);
					unset($_SESSION['No_PolisiLabel']);
					unset($_SESSION['Id_Internal_OrderLabel']);
				}
				echo "1";		
				//header("Location:KoreksiNABFil.php");
			}
			else{
				rollback($con);
				//$_SESSION["err"] = "Data not updated <br>".$sql_value."<br>".$roweffec_value."<br>".$sql_value_t_log_nab."<br>".$result_value_t_log_nab."<br>".$sql_select ;		
				$_SESSION["err"] = "Data not updated";
				if($Flag == true){
					//echo $_SESSION["err"];
					//header("Location:KoreksiNABFil.php");
					echo "0";
				}
				else{
					echo "0";
				}
			}	
		}
	}else{
		echo "3";
	}
}
else{
$_SESSION["err"] = "Please select";
header("Location:KoreksiNABFil.php");
}


?>