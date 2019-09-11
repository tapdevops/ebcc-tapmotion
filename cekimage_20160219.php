<?php
	header("Refresh:3600");
	
	
	include("config/db_connect.php");
	$con = connect();
	
	$sql  = "select ID_BA, NAMA_BA from T_BUSSINESSAREA 
			--where ID_BA in ('5121','5132','3121','2121','4121','4122','4123','4221','4321','4421','5131') 
			group by ID_BA,NAMA_BA ";
	$resultPt = oci_parse($con, $sql);
	oci_execute($resultPt, OCI_DEFAULT);
	while(oci_fetch($resultPt)){
		$namaPt = oci_result($resultPt, "NAMA_BA");
		$kodePt = oci_result($resultPt, "ID_BA");
		$image =null;
		$font="";
		$jumahImageCocok=0;
		$selisih=0;
		
		$i=0;
		for ($i=0;$i<=30;$i++){
			$tgl  = date('ymd',mktime(0, 0, 0, date("m"), date("d")-$i, date("Y")));
			$tglTampil  = date('d-m-Y',mktime(0, 0, 0, date("m"), date("d")-$i, date("Y")));
			$jumahImageCocok=0;
			$image =null;
			$font="";
			$selisih=0;
			
			$sql  = "
				select 	substr(id_rencana,29,4) as BA_CODE, 
						picture_name,
						case
							when image_file is not null
							then '1'
							else '0'
						end as IMAGE_FILE
				from t_hasil_panen a 
				where substr(id_rencana,29,4)= '$kodePt'
				and substr(no_rekap_bcc,1,6) = '$tgl'
			";
			//echo $sql."<br>";
			//die();
			$result = oci_parse($con, $sql);
			oci_execute($result, OCI_DEFAULT); 
			while(oci_fetch($result)){
				$imageNow = oci_result($result, "PICTURE_NAME");
				$ba_code = oci_result($result, "BA_CODE");
				$status_img_file = oci_result($result, "IMAGE_FILE");
				$path = "/var/www/html/tap-motion/ebcc/array/uploads/".$imageNow; 
				$image[]= $imageNow;		
				if (file_exists($path) && (filesize($path) > 0) ) {
					$jumahImageCocok ++;
					
					if($status_img_file == 0) {
						//JIKA IMAGE ADA, UPDATE DATA KE CLOB - Tambahan : SID 15-06-2015
						/* UPLOAD IMAGE FILE USING BASE64ENCODE TO CLOB DATA TYPE */
						
						$img = file_get_contents($path);
						$image_file = base64_encode($img);	
						$arr_image_file = str_split($image_file, 32759); //split string to max char varchar
						
						try {
							$sql1 = "
								BEGIN
									PRC_INSERT_CLOB(:parBA_CODE, :parIMAGE_NAME, :parCLOB00, :parCLOB01, :parCLOB02, :parCLOB03, :parCLOB04,
																				 :parCLOB05, :parCLOB06, :parCLOB07, :parCLOB08, :parCLOB09,
																				 :parCLOB10, :parCLOB11, :parCLOB12, :parCLOB13, :parCLOB14, 
																				 :parCLOB15, :parCLOB16, :parCLOB17, :parCLOB18, :parCLOB19,
																				 :parCLOB20, :parCLOB21, :parCLOB22, :parCLOB23, :parCLOB24, 
																				 :parCLOB25, :parCLOB26, :parCLOB27, :parCLOB28, :parCLOB29, 
																				 :parCLOB30);
								END;
							";
							$stmt1 = oci_parse($con, $sql1);
							oci_bind_by_name($stmt1,":parBA_CODE",$ba_code,200);
							oci_bind_by_name($stmt1,":parIMAGE_NAME",$imageNow,200);
							oci_bind_by_name($stmt1,":parCLOB00",$arr_image_file[0],32759);
							oci_bind_by_name($stmt1,":parCLOB01",$arr_image_file[1],32759);
							oci_bind_by_name($stmt1,":parCLOB02",$arr_image_file[2],32759);
							oci_bind_by_name($stmt1,":parCLOB03",$arr_image_file[3],32759);
							oci_bind_by_name($stmt1,":parCLOB04",$arr_image_file[4],32759);
							oci_bind_by_name($stmt1,":parCLOB05",$arr_image_file[5],32759);
							oci_bind_by_name($stmt1,":parCLOB06",$arr_image_file[6],32759);
							oci_bind_by_name($stmt1,":parCLOB07",$arr_image_file[7],32759);
							oci_bind_by_name($stmt1,":parCLOB08",$arr_image_file[8],32759);
							oci_bind_by_name($stmt1,":parCLOB09",$arr_image_file[9],32759);
							oci_bind_by_name($stmt1,":parCLOB10",$arr_image_file[10],32759);
							oci_bind_by_name($stmt1,":parCLOB11",$arr_image_file[11],32759);
							oci_bind_by_name($stmt1,":parCLOB12",$arr_image_file[12],32759);
							oci_bind_by_name($stmt1,":parCLOB13",$arr_image_file[13],32759);
							oci_bind_by_name($stmt1,":parCLOB14",$arr_image_file[14],32759);
							oci_bind_by_name($stmt1,":parCLOB15",$arr_image_file[15],32759);
							oci_bind_by_name($stmt1,":parCLOB16",$arr_image_file[16],32759);
							oci_bind_by_name($stmt1,":parCLOB17",$arr_image_file[17],32759);
							oci_bind_by_name($stmt1,":parCLOB18",$arr_image_file[18],32759);
							oci_bind_by_name($stmt1,":parCLOB19",$arr_image_file[19],32759);
							oci_bind_by_name($stmt1,":parCLOB20",$arr_image_file[20],32759);
							oci_bind_by_name($stmt1,":parCLOB21",$arr_image_file[21],32759);
							oci_bind_by_name($stmt1,":parCLOB22",$arr_image_file[22],32759);
							oci_bind_by_name($stmt1,":parCLOB23",$arr_image_file[23],32759);
							oci_bind_by_name($stmt1,":parCLOB24",$arr_image_file[24],32759);
							oci_bind_by_name($stmt1,":parCLOB25",$arr_image_file[25],32759);
							oci_bind_by_name($stmt1,":parCLOB26",$arr_image_file[26],32759);
							oci_bind_by_name($stmt1,":parCLOB27",$arr_image_file[27],32759);
							oci_bind_by_name($stmt1,":parCLOB28",$arr_image_file[28],32759);
							oci_bind_by_name($stmt1,":parCLOB29",$arr_image_file[29],32759);
							oci_bind_by_name($stmt1,":parCLOB30",$arr_image_file[30],32759);
							$res = oci_execute($stmt1);
						} catch (Exception $e) {
							//error log file
							print_r($e);
						}
					}
				}
			}
			$totalImageDb = count($image);
			if ($totalImageDb <> $jumahImageCocok){
				$font = "<font color=red>";
				$selisih = $totalImageDb - $jumahImageCocok;
			}else $selisih=0;
			
			echo "<br>$font PT : $namaPt - Tanggal : $tglTampil Jumlah Image Database : ".count($image) ." - Jumlah Image File =" . $jumahImageCocok ." - Selisih = $selisih </font> ".$imgSite;
			
			$sqlDel  = "DELETE FROM TR_CEK_IMG WHERE WERKS = '".$kodePt."' AND DATE_IMG = TO_DATE('".$tglTampil."','DD-MM-RRRR')";
			$resl = oci_parse($con, $sqlDel);
			oci_execute($resl, OCI_DEFAULT); 
			oci_free_statement($resl);
			
			$sqlIns  = "INSERT INTO TR_CEK_IMG (WERKS, DATE_IMG, NUM_IMG, FILE_IMG) VALUES ('".$kodePt."',TO_DATE('".$tglTampil."','DD-MM-RRRR'),'".count($image)."','$jumahImageCocok')";
			$resl = oci_parse($con, $sqlIns);
			oci_execute($resl, OCI_DEFAULT); 
			oci_free_statement($resl);
		}
		
	echo "<br>";
	}
	
	oci_commit($con);
?>
