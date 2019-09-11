<?php

#
# Example PHP server-side script for generating
# responses suitable for use with jquery-tokeninput
#

# Connect to the database
include("../config/SQL_function.php");
		//require_once __DIR__ . '/db_config.php'; 
		//$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
		include("../config/db_connect.php");
		$con = connect();
//$arr;
$bacode = $_GET['bacode'];
$search = $_GET['q'];
# Perform the query

$sql_t_Emp_All  = "
	SELECT TE.NIK||':'||TE.EMP_NAME||':'||TA.ID_AFD as NAME,TE.NIK as ID
	FROM T_EMPLOYEE TE 
	INNER JOIN T_AFDELING TA 
		ON TE.ID_BA_AFD = TA.ID_BA_AFD 
    WHERE UPPER(TE.EMP_NAME) LIKE UPPER('%".$search."%') AND TE.JOB_CODE IN ('TUKANG MUAT','MANDOR 1','MANDOR PANEN','MANDOR RAWAT','PEKERJA RAWAT','PEMANEN','SUPIR')
	AND TA.ID_BA LIKE UPPER('%".$bacode."%') order by te.emp_name
";
	 
	// echo $sql_t_Emp_All;
	// die();
	
$result_t_Emp = oci_parse($con, $sql_t_Emp_All);
oci_execute($result_t_Emp, OCI_DEFAULT);
//if(oci_fetch_array($result_t_Emp)){		
while ($obj = oci_fetch_array($result_t_Emp)) {	
	//$arr[] = $obj['NAME'];
	echo $obj['NAME']."\n";
}
/*}
else{
	$arr = "no Result";
}
*/
# Collect the results


# JSON-encode the response
//$json_response = json_encode($arr);

# Optionally: Wrap the response in a callback function for JSONP cross-domain support
if(isset($_GET["callback"])){
if($_GET["callback"] != NULL) {
    $json_response = $_GET["callback"] . "(" . $json_response . ")";
}
}

# Return the response
//echo $json_response."ABCDE";

?>
