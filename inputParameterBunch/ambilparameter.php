<?php
    include("../config/SQL_function.php"); 
	include("../config/db_config.php");
	$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');

	$ba = $_GET['ba'];
	$bunch = $_GET['bunch'];
	
	$queryMaster = "SELECT ID_KUALITAS, NAMA_KUALITAS, SHORT_NAME FROM T_KUALITAS_PANEN ORDER BY GROUP_KUALITAS, NAMA_KUALITAS";
	$rMaster = oci_parse($con, $queryMaster);
	oci_execute($rMaster, OCI_DEFAULT);
	
	echo "<br/><table width='50%' style='border:solid #556A29'>";
	echo "<tr bgcolor='#9CC346'>
			<td align='center' style='font-size:14px; border-bottom:ridge'>".$bunch."</td>
		  </tr>";
	$i = 0;	  
	while (oci_fetch($rMaster)) {
		$i++;
		
		if(($i % 2) == 0){
			$bg = "#F0F3EC";
		}else{
			$bg = "#DEE7D2";
		}
		
		$queryTrans1 = "SELECT BA_CODE, ID_KUALITAS, KETERANGAN, INSERT_USER, INSERT_TIME, UPDATE_USER, UPDATE_TIME, DELETE_USER, DELETE_TIME
					FROM T_PARAMETER_BUNCH WHERE BA_CODE = '$ba' 
					AND ID_KUALITAS = '".oci_result($rMaster, "ID_KUALITAS")."' 
					AND KETERANGAN = '".$bunch."'";

		$rTran1 = oci_parse($con, $queryTrans1);
		oci_execute($rTran1, OCI_DEFAULT);
		oci_fetch($rTran1);
			
		echo "<tr bgcolor='".$bg."'>
				<td style='font-size:14px;'>
				<input type='checkbox' 
					class='check'
					name='t_harvest[]' 
					value='".oci_result($rMaster, "ID_KUALITAS")."'
					".((oci_result($rTran1, "BA_CODE") != "")?('checked'):('')).">"
					.oci_result($rMaster, "NAMA_KUALITAS").
					"<input type='hidden' name='t_iuser[]' value='".oci_result($rTran1, "INSERT_USER")."'>
					<input type='hidden' name='t_itime[]' value='".oci_result($rTran1, "INSERT_TIME")."'>
					<input type='hidden' name='t_duser[]' value='".oci_result($rTran1, "DELETE_USER")."'>
					<input type='hidden' name='t_dtime[]' value='".oci_result($rTran1, "DELETE_TIME")."'>
				</td>	
			</tr>";
	}
	echo "<tr bgcolor='#9CC346'><td align='center' style='border:solid #556A29'>
				<a href='#' onclick='checkall()'>Select All</a> / 
				<a href='#' onclick='uncheckall()'>Diselect All</a>
		  </td></tr>";
	echo "</table><br/>";
?>

<script type="text/javascript">
	function checkall(){
		$('.check').attr('checked', true);
	}
	
	function uncheckall(){
		$('.check').attr('checked', false);
	}
</script>