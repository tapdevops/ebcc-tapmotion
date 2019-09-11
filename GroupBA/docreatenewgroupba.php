<?php
session_start();
//echo $_POST["id_group_ba"]."=".$_POST["id_group_name"]."=".$_POST["start"]."=".$_POST["end"]."=".$_SESSION["NIK"];
$_SESSION["EDITABLE2"] = "TRUE";
if(isset($_POST["id_group_ba"]) && isset($_POST["id_group_name"]) && isset($_POST["start"]) && isset($_POST["end"]) && isset($_SESSION["NIK"]) && isset($_SESSION["rowBA"])){
	
include("../config/SQL_function.php");
include("../config/db_connect.php");
$con = connect();

$id_group_ba = $_POST["id_group_ba"];
$id_group_name = $_POST["id_group_name"];
$start = date("Y-m-d", strtotime($_POST["start"]));
$end = date("Y-m-d", strtotime($_POST["end"]));
$NIK = $_SESSION["NIK"];
$rowBA = $_SESSION["rowBA"];
$action = true;
	
	$sql_group_ba_1  = "select to_char(SYSDATE+1,'MM/DD/YYYY') TGL from dual";
	$result_group_ba_1  = select_data($con,$sql_group_ba_1);
	$start_date_1 = $result_group_ba_1["TGL"];
	$start_date_2 = date("Y-m-d", strtotime($start_date_1));

		if($end=="1970-01-01")
		{
			$end="2099-01-01";
		}
		
		if($start=="1970-01-01")
		{
			$start=$start_date_2;
		}
		
			if($id_group_ba == "" || $id_group_name == "" || $start == "" || $end == ""){
				$_SESSION[err] = "ID Group BA, ID Group Name, Start Date, atau End Date tidak boleh kosong";
				$_SESSION["IDGroupCN"] = TRUE;
				header("Location:createnewgroupba.php");
			}
			else{
			
				$sql_select_t_group_ba = "select count(*) as HITUNG from t_group_ba where ID_GROUP_BA='$id_group_ba'";
				$roweffec_select_t_group_ba = select_data($con,$sql_select_t_group_ba);
				
				if($roweffec_select_t_group_ba["HITUNG"] > 0){
					$insert[$ctr] = $ctr."ID_GROUP_BA: ".$id_group_ba." already on table t_group_ba and updated";
					$sql_t_JA = "UPDATE t_group_ba
					SET GROUP_NAME='$id_group_name', START_DATE=to_date('$start','YYYY-MM-DD'), END_DATE=to_date('$end','YYYY-MM-DD')
					WHERE ID_GROUP_BA='$id_group_ba'";
					$roweffec_t_JA = update_data($con,$sql_t_JA);
				}
				else{
			
					$sql_t_JA = "INSERT INTO t_group_ba
					(ID_GROUP_BA, GROUP_NAME, Created_Date, Created_By, START_DATE, END_DATE) 
					VALUES
					('$id_group_ba', '$id_group_name', sysdate, '$NIK', to_date('$start','YYYY-MM-DD'), to_date('$end','YYYY-MM-DD'))";
					$roweffec_t_JA = num_rows($con,$sql_t_JA);
					//echo $sql_t_JA;
				}
				
				//CLEAR DATA FOR SELECTED ID_GROUP_BA
				$sql_del_t_header_rencana_panen = "DELETE FROM t_alternate_ba_group WHERE ID_GROUP_BA='$id_group_ba'";
				$result_del_t_header_rencana_panen = delete_data($con,$sql_del_t_header_rencana_panen);	
				
				$ctr = 0;
				$untickRow = 0;
				for($x = 0 ; $x < $rowBA ; $x++) 
				{
					if(isset($_POST["chk$x"]) )
					{
						if($_POST["chk$x"] !== NULL)
						{
							$chk[$ctr] = $_POST["chk$x"];
							$sql_select_t_JA = "select count(*) as HITUNG from t_alternate_ba_group where ID_GROUP_BA='$id_group_ba' and ID_BA=$chk[$ctr]";
							$roweffec_select_t_JA = select_data($con,$sql_select_t_JA);
							
							if($roweffec_select_t_JA["HITUNG"] > 0)
							{
								$insert[$ctr] = $ctr."ID_GROUP_BA: ".$id_group_ba." with ID_BA: ".$chk[$ctr]." already on table t_alternate_ba_group";
							}
							else
							{
								$ID_ALT_BA_GROUP = $chk[$ctr].$id_group_ba;
								$sql_t_JA = "INSERT INTO t_alternate_ba_group
								(ID_ALT_BA_GROUP, ID_BA, ID_GROUP_BA) 
								VALUES
								('$ID_ALT_BA_GROUP', '$chk[$ctr]', '$id_group_ba')";
								$roweffec_t_JA = num_rows($con,$sql_t_JA);
								//echo $sql_t_JA;
								if($roweffec_t_JA > 0)
								{
									//commit($con);
									$insert[$ctr] = "Job Authorization Initial for Business Area : ".$id_group_name." with No Authority : ".$start." has been created";
								}
								else{
									$action = false;
									//rollback($con);
									$insert[$ctr] =  $ctr."ID_GROUP_BA: ".$id_group_ba." with ID_BA: ".$chk[$ctr]." has not been created. Failed when insert t_alternate_ba_group";
								}
							}
							$ctr++;
						}
					}
					else
					{
						$untickRow++;
					}
				}
				
				if($untickRow == $rowBA)
				{
					$action = false;
					$ctr = 1;
					$insert[0] = "No BA has been selected";
				}
				
				if($action == true)
				{
					commit($con);
				}
				else
				{
					rollback($con);
				}
				
				$_SESSION["ctr"] = $ctr;
				for($y = 0 ; $y < $ctr ; $y++){
					$_SESSION["insert$y"] = $insert[$y];
				}
				$_SESSION["IDGroupCN"] = TRUE;
				header("Location:createnewgroupba.php");
			}
}
else{
	$_SESSION[err] = "Pilih Company Code, Business Area, atau Job Authorization Code";
	$_SESSION["IDGroupCN"] = TRUE;
	header("Location:createnewgroupba.php");
}

?>