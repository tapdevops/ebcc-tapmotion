<?php
session_start();

//if(isset($_SESSION[NIK]) && isset($_POST["date3"]) && isset($_POST["status"]) ){
if(isset($_SESSION[NIK]) && isset($_SESSION[LoginName]) && isset($_POST[status]) && isset($_POST[Kode_Delivery_TicketSBCC]) && isset($_POST[No_BCCSBCC]) ){
	$NIK_Login	= $_SESSION[NIK];
	$LoginName	= $_SESSION[LoginName];
	//$Deliv_Date = $_POST["date3"];
	$Status_BCC = $_POST["status"];
	$Ticket = $_POST["Kode_Delivery_TicketSBCC"];
	$No_BCC = $_POST["No_BCCSBCC"];
	$Status_BCCOld = $_POST["Status_BCCSBCCOld"];

	if($NIK_Login == "" || $LoginName == "")
	{
		$_SESSION[err] = "Please login";
		header("Location:../../login.php");
	}
	
	else{
		
		if($Status_BCC == ""){
			$_SESSION[err] = "Please choose new Status BCC";
			header("Location:EditStatusBCC.php");
		}
		
		//if($Status_BCC == "DELIVERED" && $Deliv_Date == "0000-00-00"){
				//$_SESSION[err] = "Please choose Delivered Date";
		else if($Status_BCC == "DELIVERED"){
			//header("Location:EditStatusBCC.php");
			header("Location:EditStatusBCC&NAB.php");
		}
		
		else{ // open else query
			include '../../db_connect.php';
			$db = new DB_CONNECT();
			mysql_query("BEGIN");	
			$sql_value = "UPDATE t_hasil_panen SET Status_BCC = '$Status_BCC' WHERE Kode_Delivery_Ticket = '$Ticket' AND No_BCC = '$No_BCC'" ;
			$result_value = mysql_query($sql_value);
			$roweffec_value = mysql_affected_rows();
			
			$sql_value_log = "INSERT INTO t_log_hasil_panen (InsertUpdate, Tgl_CreEdit, NIK_CreEditor, Login_Name_CreEditor, On_Table, On_No_BCC, On_Kode_Delivery_Ticket, New_Value_Status_BCC, Old_Value_Status_BCC, CreEdit_From, Sync_Server) 
			VALUES
			('UPDATE', DATE(now()), '$NIK_Login', '$LoginName', 't_hasil_panen', '$No_BCC', '$Ticket', '$Status_BCC', '$Status_BCCOld', 'Website', now())" ;
			$result_value_log = mysql_query($sql_value_log);
			$roweffec_value_log = mysql_affected_rows();
			
			
			
			if($result_value && $roweffec_value > 0 && $roweffec_value != 0 && $result_value_log && $roweffec_value_log > 0 && $roweffec_value_log != 0){ //see row effect
				//echo "sukses ".$roweffec_value."<=>".$sql_value."<br>";
				mysql_query("COMMIT");
				$_SESSION[err] = "Data has been updated";
				header("Location:AllStatusBCCPaging.php");	
			}
			
			else{
				mysql_query("ROLLBACK");
				//echo "gagal else ".$roweffec_value."<=>".$sql_value."<br>";		
				$_SESSION[err] = "Data has not been updated";
				header("Location:EditStatusBCC.php");
			}
		} // close else query
	} //close else Status_BCC
} // close if(isset($_SESSION[NIK])... ...isset($_POST[No_BCCSBCC]) )
else{
$_SESSION[err] = "Please login";
header("Location:../../login.php");
} 

//echo "TM1 ". $_SESSION[TM1];

//echo "tes1  ". $_POST[status];

?>