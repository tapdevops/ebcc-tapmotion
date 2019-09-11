<?php
session_start();

if(isset($_SESSION[NIK]) && isset($_SESSION[LoginName]) && isset($_POST[Status_BCC]) && isset($_POST[No_NABin]) && isset($_POST[getdate3])   ){

	$NIK_Login	= $_SESSION[NIK];
	$LoginName	= $_SESSION[LoginName];
	$Status_BCC = $_POST[Status_BCC];
	$No_NAB = $_POST[No_NABin];
	$Deliv_Date = $_POST[getdate3];
	$ID_NAB_Tgl = $_POST[No_NABin].$_POST[getdate3];
	$No_Polisiext = $_POST[No_Polisiext];
	$Supirext = $_POST[Supirext];
	$ID_Internal_Order = $_POST[No_Polisiintin];
	$No_Polisi_io = $_SESSION[No_Polisi_io];
	$Supirintin = $_POST[Supirintin];
	$TM1in = $_POST[TM1in];
	$TM2in = $_POST[TM2in];
	$TM3in = $_POST[TM3in];
	$Status_BCCOld = $_SESSION[Status_BCCOld];
	$Ticket = $_SESSION["Kode_Delivery_TicketSBCC"];
	$No_BCC = $_SESSION["No_BCCSBCC"];

	if($NIK_Login == "" || $LoginName == "")
	{
		$_SESSION[err] = "Please login a";
		header("Location:../../login.php");
	}
	
	else{ //open STATUS_BCC
		if($Status_BCC == "")
		{
			$_SESSION[err] = "Please choose new Status_BCC";
			header("Location:EditStatusBCC.php");
		}
		else{ //open No_NAB & Deliv_Date
			if($No_NAB == "" || $Deliv_Date == "0000-00-00"){
				$_SESSION[err] = "Please choose No_NAB and Tgl_NAB";
				header("Location:EditStatusBCC&NAB.php");
			}
			
			else{ //open No_Polisiext & Supirext
				include '../../db_connect.php';
				$db = new DB_CONNECT();
				
				if($No_Polisiext == "" && $Supirext == "")
				{ //open if($No_Polisiext == "" && $Supirext == "")
					if($ID_Internal_Order == "" || $Supirintin == "")
					{
						$_SESSION[err] = "Please choose No_Polisi and Supir internal";
						header("Location:EditStatusBCC&NAB.php");
					}
					else{ // open input no polisi and supir internal
					
						mysql_query("BEGIN");
						$sql_value_status = "UPDATE t_hasil_panen SET Status_BCC = '$Status_BCC' WHERE Kode_Delivery_Ticket = '$Ticket' AND No_BCC = '$No_BCC'" ;
						$result_value_status = mysql_query($sql_value_status);
						$roweffec_value_status = mysql_affected_rows();
						
						$sql_value_status_log = "INSERT INTO t_log_hasil_panen (
						InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_No_BCC, On_Kode_Delivery_Ticket, New_Value_Status_BCC, Old_Value_Status_BCC, CreEdit_From, Sync_Server) 
						VALUES
						('UPDATE', DATE(now()), '$NIK_Login', '$LoginName', 't_hasil_panen', '$No_BCC', '$Ticket', '$Status_BCC', '$Status_BCCOld', 'Website', now())" ;
						$result_value_status_log = mysql_query($sql_value_status_log);
						$roweffec_value_status_log  = mysql_affected_rows();
						
						
						if($result_value_status && $roweffec_value_status > 0 && $roweffec_value_status != 0 && $result_value_status_log && $roweffec_value_status_log > 0 && $roweffec_value_status_log != 0){

							$sql_value_t_nab = "INSERT INTO t_nab (
							ID_NAB_Tgl, No_NAB, Tgl_NAB, Tipe_Order, ID_Internal_Order, No_Polisi, NIK_Supir, NIK_Tukang_Muat1, NIK_Tukang_Muat2, NIK_Tukang_Muat3) 
							VALUES
							('$ID_NAB_Tgl', '$No_NAB', '$Deliv_Date', 'INTERNAL', '$ID_Internal_Order', '$No_Polisi_io', '$Supirintin', '$TM1in', '$TM2in', '$TM3in')" ;
							$result_value_t_nab = mysql_query($sql_value_t_nab);
							$roweffec_value_t_nab  = mysql_affected_rows();
							
							$sql_value_t_log_nab = "INSERT INTO t_log_nab (
							InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_NAB_Tgl, CreEdit_From, Sync_Server) 
							VALUES
							('INSERT', DATE(now()), '$NIK_Login', '$LoginName', 't_nab', '$ID_NAB_Tgl', 'Website', now())" ;
							$result_value_t_log_nab = mysql_query($sql_value_t_log_nab);
							$roweffec_value_t_log_nab  = mysql_affected_rows();
							
							if($result_value_t_nab && $roweffec_value_t_nab > 0 && $roweffec_value_t_nab != 0 && $result_value_t_log_nab && $roweffec_value_t_log_nab > 0 && $roweffec_value_t_log_nab != 0){
								mysql_query("COMMIT");
								$_SESSION[err] = "Status_BCC and NAB successfully updated";
								header("Location:EditStatusBCC.php");
							}
							
							else{
								mysql_query("ROLLBACK");  
								//$_SESSION[err] = "Status_BCC and NAB has not been updated <br>".$sql_value_t_log_nab."<br>".$sql_value_t_nab;
								$_SESSION[err] = "Status_BCC and NAB has not been updated";
								header("Location:EditStatusBCC.php");
							}
						}
						else{
							mysql_query("ROLLBACK");
							$_SESSION[err] = "Status_BCC has not been updated";
							header("Location:EditStatusBCC.php");
						}
					} //close input no polisi and supir internal
				} //close if($No_Polisiext == "" && $Supirext == "")
				else if($No_Polisiext == "" || $Supirext == ""){
					$_SESSION[err] = "Please choose No_Polisi and Supir external";
					header("Location:EditStatusBCC&NAB.php");
				}
				
				
				else // input no polisi and supir eksternal
				{
						mysql_query("BEGIN");
						$sql_value_status = "UPDATE t_hasil_panen SET Status_BCC = '$Status_BCC' WHERE Kode_Delivery_Ticket = '$Ticket' AND No_BCC = '$No_BCC'" ;
						$result_value_status = mysql_query($sql_value_status);
						$roweffec_value_status = mysql_affected_rows();
						
						$sql_value_status_log = "INSERT INTO t_log_hasil_panen (
						InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_No_BCC, On_Kode_Delivery_Ticket, New_Value_Status_BCC, Old_Value_Status_BCC, CreEdit_From, Sync_Server) 
						VALUES
						('UPDATE', DATE(now()), '$NIK_Login', '$LoginName', 't_hasil_panen', '$No_BCC', '$Ticket', '$Status_BCC', '$Status_BCCOld', 'Website', now())" ;
						$result_value_status_log = mysql_query($sql_value_status_log);
						$roweffec_value_status_log  = mysql_affected_rows();
						
						
						if($result_value_status && $roweffec_value_status > 0 && $roweffec_value_status != 0 && $result_value_status_log && $roweffec_value_status_log > 0 && $roweffec_value_status_log != 0){
					
							$sql_value_t_nab = "INSERT INTO t_nab (
							ID_NAB_Tgl, No_NAB, Tgl_NAB, Type_Order, No_Polisi, NIK_Supir) 
							VALUES
							('$ID_NAB_Tgl', '$No_NAB', '$Deliv_Date', 'EKSTERNAL', '$No_Polisiext', '$Supirext')" ;
							$result_value_t_nab = mysql_query($sql_value_t_nab);
							$roweffec_value_t_nab  = mysql_affected_rows();
							
							$sql_value_t_log_nab = "INSERT INTO t_log_nab (
							InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_ID_NAB_Tgl, CreEdit_From, Sync_Server) 
							VALUES
							('INSERT', DATE(now()), '$NIK_Login', '$LoginName', 't_nab', '$ID_NAB_Tgl', 'Website', now())" ;
							$result_value_t_log_nab = mysql_query($sql_value_t_log_nab);
							$roweffec_value_t_log_nab  = mysql_affected_rows();
							
							if($result_value_t_nab && $roweffec_value_t_nab > 0 && $roweffec_value_t_nab != 0 && $result_value_t_log_nab && $roweffec_value_t_log_nab > 0 && $roweffec_value_t_log_nab != 0){
								mysql_query("COMMIT");
								$_SESSION[err] = "Status_BCC and NAB successfully updated";
								header("Location:EditStatusBCC.php");
							}
							
							else{
								mysql_query("ROLLBACK");  
								$_SESSION[err] = "Status_BCC and NAB has not been updated";
								header("Location:EditStatusBCC.php");
							}
						}
						else{
							mysql_query("ROLLBACK");
							$_SESSION[err] = "Status_BCC has not been updated";
							header("Location:EditStatusBCC.php");
						}
				} //tutup input no polisi and supir eksternal
			
			} //close else No_Polisiext & Supirext

		} //close else No_NAB & Deliv_Date
	
	}  //close else STATUS_BCC
	
} // close if(isset($_SESSION[NIK]) ... ... isset($_POST[getdate3]))
else{
$_SESSION[err] = "Please login b";
header("Location:../../login.php");
}  


/*
//echo "TM1 ". $_SESSION[TM1];
$NIK_Login	= $_SESSION[NIK];
$LoginName	= $_SESSION[LoginName];
echo "NIK" .$NIK_Login;
echo "LoginName" .$LoginName;
echo "Status_BCC = ". $_POST[Status_BCC]. "<br>";
echo "No_NAB = " . $_POST[No_NABin]. "<br>";
echo "date = " . $_POST[getdate3]. "<br>";
echo "No_Polisiext = ". $_POST[No_Polisiext]. "<br>";
echo "Supirext = ". $_POST[Supirext]. "<br>";
echo "No_Polisiint = ". $_POST[No_Polisiintin]. "<br>";
echo "Supirint = ". $_POST[Supirintin]. "<br>";
echo "TM1 = ". $_POST[TM1in]. "<br>";
echo "TM2 = ". $_POST[TM2in]. "<br>";
echo "TM3 = ". $_POST[TM3in]. "<br>";

$ID_NAB_Tgl = $_POST[No_NABin].$_POST[getdate3]; 


echo "ID_NAB_Tgl = ". $ID_NAB_Tgl ;*/


//echo "tes1  ". $_POST[status];

?>