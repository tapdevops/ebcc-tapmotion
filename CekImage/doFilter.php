<?php
session_start();

	
	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();
	

		//$sql_image = "
       // select picture_name from t_hasil_Panen where substr(id_nab_tgl,1,4)='4221'
		//";
	//	$_SESSION["sql_image"] 		= $sql_image;	
		//echo $sql_image; die();
		//header("Location:ListImage.php");
	
    
	$sql_ID_Group_BA  = "select picture_name from t_hasil_Panen where substr(id_nab_tgl,1,4)='4221''";
				$result_ID_Group_BA = oci_parse($con, $sql_ID_Group_BA);
				oci_execute($result_ID_Group_BA, OCI_DEFAULT);
				while(oci_fetch($result_ID_Group_BA)){
					$sel_ID_Group_BA[]		= oci_result($result_ID_Group_BA, "picture_name");
				}
				$roweffec_ID_Group_BA = oci_num_rows($result_ID_Group_BA);
				//echo $sql_ID_Group_BA.$roweffec_ID_Group_BA."<br>";	
		
				//untuk DIKIRIM 
				//JIKA ADA GROUP	
				if($roweffec_ID_Group_BA > 0){

	$filename = '/path/to/foo.txt';

	if (file_exists($filename)) {
		echo "The file $filename exists";
	} else {
		echo "The file $filename does not exist";
	}



?>