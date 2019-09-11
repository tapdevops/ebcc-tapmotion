<?php
$page = $_SERVER['PHP_SELF'];
$sec = "10800";
?>
<html>
    <head>
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    </head>
    <body>

<?php

include("class/class.ftp_upload.php");
//error_reporting(E_ERROR | E_PARSE);

/* --------------------------------------------------------------------------
File Created By	: Nicholas B.
Created Date	: Agustus 2012
Version			: 3.0
Last Modify by	: Nicholas B.
Last Modify date: 18 feb 2014
	- 		perubahan query dan koneksi db dari access ke mysql
	-		perubahan coding dengan cara menaruh file ke server HO dan running secara job
-----------------------------------------------------------------------------*/
?>


<?php	
	$ftp = new class_ftp_upload;
	
	$company_code = '[kode_pt]';
	$oracle_username = 'MOBILE_ESTATE';
	$oracle_password = 'estate123#';
	$oracle_host	 = '10.20.1.109/tapapps';
	
	$conn = oci_connect($oracle_username, $oracle_password, $oracle_host);
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	$ftp_server ='10.20.1.153';	
	$ftp_username ='ftp-motion';
	$ftp_password ='tap123#';
	$ftp_serverdir='';
	$ftp_clientdir ='D:/xampp/htdocs/tap-motion/mobile_estate_'.$company_code.'/public/upload_image_tmp';
	
	$file = $company_code."_";
	$jumlahKirim=0;
	$jumlahFile=0;
	
	$login_result = $ftp->ftp_config($ftp_server, $ftp_username, $ftp_password, $ftp_serverdir, $ftp_clientdir);
	
	chdir($ftp_clientdir);
	
	//listing seluruh image yang ada
	foreach (glob("*.jpg") as $filename) {
			$return_file = $ftp->log_file($file, $filename.";\n", $ftp_clientdir);
			$jumlahFile++;
	}
	
	//kirim file yang berisi seluruh file name
	foreach (glob("*.log") as $filename) {
		$return = $ftp->upload_file($ftp_clientdir,$filename);
	}
	
	
	foreach (glob("*.jpg") as $filename) {
		$return = $ftp->upload_file($ftp_clientdir, $filename);
		if($return == "Failed"){
			$err_img_name .= $filename . ";\n";
			echo "$filename gagal";
		}else{
			$jumlahKirim++;
			
			//insert log untuk image file fisik yg berhasil diupload menggunakan FTP
			$query1 = "
				SELECT NVL(IMAGE_NAME, 0) as JML_DATA, NVL(SERVER_FTP, '') as SERVER_FTP
				FROM TR_IMAGE_STATUS
				WHERE IMAGE_NAME = SUBSTR('".$filename."',0, LENGTH('".$filename."')-4)
					AND COMP_CODE = '".$company_code."'
			";
			$stid1 = oci_parse($conn, $query1);
			oci_execute($stid1);
			
			$row = oci_fetch_array($stid1, OCI_ASSOC+OCI_RETURN_NULLS);
			
			//jika record di TR_IMAGE_STATUS sudah ada & belum pernah dikirim FTP, maka update
			if($row['JML_DATA'] && $row['SERVER_FTP'] == ''){
				try{
					$query2 = "
						UPDATE TR_IMAGE_STATUS
						SET SERVER_FTP = SYSDATE,
							UPDATE_USER = 'FTP_SERVER', 
							UPDATE_TIME = SYSDATE
						WHERE IMAGE_NAME = SUBSTR('".$filename."',0, LENGTH('".$filename."')-4)
							AND COMP_CODE = '".$company_code."'
					";
					$stid2 = oci_parse($conn, $query2);
					oci_execute($stid2, OCI_COMMIT_ON_SUCCESS);
				} catch (Exception $e) {
					$error_msg = $e->getCode(). " - " .$e->getMessage();
					echo $error_msg;
				}
			}
			//jika belum ada record, buat baru
			elseif(!$row['JML_DATA']){
				try{
					$query2 = "
						INSERT INTO TR_IMAGE_STATUS (
							COMP_CODE, 
							IMAGE_NAME, 
							TR_TYPE, 
							SERVER_FTP, 
							INSERT_USER, 
							INSERT_TIME
						) VALUES (
							'".$company_code."',
							SUBSTR('".$filename."',0, LENGTH('".$filename."')-4),
							'N/A',
							SYSDATE,
							'FTP_SERVER',
							SYSDATE
						)
					";
					$stid2 = oci_parse($conn, $query2);
					oci_execute($stid2, OCI_COMMIT_ON_SUCCESS);
				} catch (Exception $e) {
					$error_msg = $e->getCode(). " - " .$e->getMessage();
					echo $error_msg;
				}
			}
		}
	
	}

	echo "<br>Upload data $jumlahFile, berhasil $jumlahKirim;<br>";
	$jam1 = date('H:i:s');
	
	
?>
  </body>
</html>
    