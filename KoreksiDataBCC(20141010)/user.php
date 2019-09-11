<?

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

$bacode = $_GET['bacode'];
$search = $_GET['q'];
# Perform the query

$sql_t_Emp_All  = "
	SELECT TE.NIK||':'||TE.EMP_NAME as NAME,TE.NIK as ID
	FROM T_EMPLOYEE TE 
	INNER JOIN T_JOBAUTHORITY TJ 
		ON TE.ID_JOBAUTHORITY = TJ.ID_JOBAUTHORITY
    WHERE TE.NIK||':'||TE.EMP_NAME LIKE UPPER('%".$search."%')
		AND TJ.ID_BA LIKE UPPER('%".$bacode."%')
";
	 
$result_t_Emp = oci_parse($con, $sql_t_Emp_All);
oci_execute($result_t_Emp, OCI_DEFAULT);
		
while ($obj = oci_fetch_array($result_t_Emp)) {	
	//$arr[] = $obj;
	echo $obj['NAME']."\n";
}

# Collect the results


# JSON-encode the response
$json_response = json_encode($arr);

# Optionally: Wrap the response in a callback function for JSONP cross-domain support
if($_GET["callback"]) {
    $json_response = $_GET["callback"] . "(" . $json_response . ")";
}

# Return the response
echo $json_response;

?>
