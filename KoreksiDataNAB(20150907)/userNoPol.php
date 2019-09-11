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
	SELECT TIO.ID_INTERNAL_ORDER||':'||TIO.NO_POLISI as NAME
	FROM T_INTERNAL_ORDER TIO
    WHERE UPPER(TIO.NO_POLISI) LIKE UPPER('%".$search."%') AND
		 TIO.ID_BA LIKE UPPER('%".$bacode."%') order by TIO.NO_POLISI
";
	 
	 //echo $sql_t_Emp_All;
	 //die();
	
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
