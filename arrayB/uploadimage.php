<?php
include("fungsi.php");
$response = array();

		   $row_t_hasil_panen = $_POST["row_t_hasil_panen"];
		  
				
		   for($c = 0 ; $c < $row_t_hasil_panen ; $c++ )
		   {
				$Picture_Name[$c] 	=  $_POST["Picture_Name$c"];
				$x_stage = 1;
			    header('Content-Type: bitmap; charset=utf-8');
				$nama_gambar = $_POST["Picture_Name$c"]; 
			    $base=$_REQUEST["Picture_Data$c"];
				$binary=base64_decode($base);
												
				$file = fopen('uploads/'.$Picture_Name[$c], 'wb');
					   //$file = fopen('uploads/$c.jpg', 'wb');
				fwrite($file, $binary);
				fclose($file);
					   //set nama gambar 
                       //*/ 
				//logToFile("my.log", $nama_gambar);
				logToFile("my.log",$nama_gambar 'Image transfer success');				
			}
	 
	    $response["success"] = 1;
	    $response["message"] = "berhasil";
	    echo json_encode($response);	
       
				
?>