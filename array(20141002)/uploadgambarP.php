
	<?php
	include("fungsi_log.php");
	$response = array();
    $row_t_hasil_panen = $_POST["row_t_hasil_panen"];
		   
	if ($row_t_hasil_panen > 0){
				
		   for($c = 0 ; $c < $row_t_hasil_panen ; $c++ )
		   {
				$Picture_Name[$c] 	=  $_POST["Picture_Name$c"];
				$nama_gambar = $_POST["Picture_Name$c"]; 
				$No_BCC[$c] 	=  $_POST["No_BCC$c"];
				$nomor_bcc = $_POST["No_BCC$c"];

			    header('Content-Type: bitmap; charset=utf-8');
			    $base=$_REQUEST["Picture_Data$c"];
				$binary=base64_decode($base);
												
				$file = fopen('uploads/'.$Picture_Name[$c], 'wb');
				fwrite($file, $binary);
				fclose($file);
				
				
				$response["success"] = 1;
				$response["message"] = "berhasil di upload ".$nama_gambar;
				echo json_encode($response);	
				logToFile($nomor_bcc.'-'.$nama_gambar);			
	     } 
		
    } 
	else{
		$response["success"] = 0;
		$response["message"] = "gagal Upload";
		echo json_encode($response);	
		logToFile($nomor_bcc.'-'.$nama_gambar);		
	}
				
?>