<?php
include("fungsi.php");
$response = array();
   $row_t_hasil_panen = $_POST["row_t_hasil_panen"];
		   $nobcc = $_POST["row_t_hasil_panen"];
		
	
		   for($c = 0 ; $c < $row_t_hasil_panen ; $c++ )
		   {
		     $Picture_Name[$c] 	=  $_POST["Picture_Name$c"];
			 $nama_gambar = $_POST["Picture_Name$c"];
            
			 if (file_exists('$nama_gambar')) {
				$response["success"] = 0;
				$response["message"] = $nama_gambar. "sudah ada";
				echo json_encode($response);
				logToFile("my.log", $nama_gambar. "sudah ada");
			} 
			else 
			{
				header('Content-Type: bitmap; charset=utf-8');
				 
			    $base=$_REQUEST["Picture_Data$c"];
				$binary=base64_decode($base);
												
				$file = fopen('uploads/'.$Picture_Name[$c], 'wb');
				fwrite($file, $binary);
				fclose($file);
 				
	    		$response["success"] = 1;
	    		$response["message"] = '$nama_gambar';
	    		echo json_encode($response);	
        		logToFile("my.log", $nama_gambar. "  succes diuploads");
				
			}	
   
		   }
	   
				
?>