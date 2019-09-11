<?php
include("fungsi.php");
$response = array();
if(isset($_POST["NIK"]) && isset($_POST["Login_Name"]) ){

    $NIK = $_POST["NIK"];
	$Login_Name = $_POST["Login_Name"];  

	if($NIK == "" && $Login_Name == ""){
	$response["success"] = 0;
	$response["message"] = "NIK dan Login Name tidak ada";
	echo json_encode($response);
	}
	
	else if($NIK == "" || $Login_Name == ""){
	$response["success"] = 0;
	$response["message"] = "NIK atau Login Name tidak ada";
	echo json_encode($response);
	}
	
	else{ 
		   $row_t_hasil_panen = $_POST["row_t_hasil_panen"];
		   $nobcc = $_POST["row_t_hasil_panen"];
				
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
			}
	    }
	    $response["success"] = 1;
	    $response["message"] = "berhasil";
	    echo json_encode($response);	
        logToFile("my.log", "Image transfer success");
		
	}
else{
	$NIK = $_POST["NIK"];
	$Login_Name = $_POST["Login_Name"];  
	$response["success"] = 0;
	$response["message"] = "User login kosong nih ".$NIK." - ".$Login_Name;
	echo json_encode($response);
	logToFile("my.log", "login error");
}	
				
?>