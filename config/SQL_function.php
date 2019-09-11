<?php

	function insert_data($conn,$query)
	{
	  $stmt = oci_parse($conn,$query);
	  oci_execute($stmt, OCI_DEFAULT);
	  oci_free_statement($stmt);
	  //echo $conn . " inserted \n\n";
	}
	
	function update_data($conn,$query)
	{
	  $stmt = oci_parse($conn,$query);
	  oci_execute($stmt, OCI_DEFAULT);
	  oci_free_statement($stmt);
	  //echo $conn . " updated \n\n";
	}

	function delete_data($conn,$query)
	{
	  $stmt = oci_parse($conn,$query);
	  oci_execute($stmt, OCI_DEFAULT);
	  oci_free_statement($stmt);
	  //echo $conn . " deleted \n\n";
	}
	
	function num_rows($conn,$query)
	{
	  $stmt = oci_parse($conn,$query);
	  oci_execute($stmt, OCI_DEFAULT);
	  $row = oci_num_rows($stmt);
	  oci_free_statement($stmt);
	  //echo $conn . " deleted \n\n";
	  return $row;
	}

	function commit($conn)
	{
	  oci_commit($conn);
	  //echo $conn . " committed\n\n";
	}

	function rollback($conn)
	{
	  oci_rollback($conn);
	  //echo $conn . " rollback\n\n";
	}
/*
	function select_data($conn,$query)
	{
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt, OCI_DEFAULT);
		$ctr = 0;
		while ($line = oci_fetch_assoc($stmt)) 
		{
			foreach ($line as $col_value) 
			{
				$data[$ctr] = $col_value;
			}
			$ctr = $ctr+1;
		}
		oci_free_statement($stmt);
		return $data;
	}  */
	
	function select_data($conn,$query)
	{
		$stid = oci_parse($conn, $query);
		oci_execute($stid);
		$row = oci_fetch_array($stid, OCI_ASSOC);
		oci_free_statement($stid);
		return $row;
	}
	
	function oracle_query($conn,$query)
	{
		$stid = oci_parse($conn, $query);
		oci_execute($stid);
		return $stid;
	}
	
	function replace_dot($bcc)
	{
		$result = str_replace(".","",$bcc,$i);
		return $result;
	}
	
	function separator($number)
	{
		$result = substr($number, 0, 6) . '.' . substr($number, 6, 3) . '.'. substr($number, 9, 2) . '.' . substr($number, 11, 3) . '.' . substr($number, 14, 5) . '.' . substr($number, 19, 1);
		return $result;
	}
	

	function sepbcc($number)
	{
		$result = substr($number, 0, 6) . '.' . substr($number, 6,3) . '.'. substr($number, 9, 2);
		return $result;
	}
	
   function doLog($text)
    {
    // open log file
    $filename = "form_ipn.log";
    $fh = fopen($filename, "a") or die("Could not open log file.");
    fwrite($fh, date("d-m-Y, H:i")." - $text\n") or die("Could not write file!");
    fclose($fh);
    }

?>