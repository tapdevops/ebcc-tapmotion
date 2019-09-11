<?php
session_start();
include("../include/Header.php");
if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['Jenis_Login']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name'])){	
	$Job_Code = $_SESSION['Job_Code'];
	$username = $_SESSION['NIK'];	
	$Emp_Name = $_SESSION['Name'];
	$Jenis_Login = $_SESSION['Jenis_Login'];
	$Comp_Name = $_SESSION['Comp_Name'];
	$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
	$Date = $_SESSION['Date'];
	$ID_Group_BA = $_SESSION['ID_Group_BA']; 

	if($username == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:../index.php");
	} else {

		include("../config/SQL_function.php");
		include("../config/db_connect.php");
		$con = connect();

		$sql_user_login  = 	"select c.id_cc as COMPANY_CODE, c.id_ba as BUSINESS_AREA, d.comp_name AS COMPANY_NAME
		from t_employee a, t_afdeling b, t_bussinessarea c, t_companycode d
		where a.id_ba_afd = b.id_ba_afd and b.id_ba = c.id_ba and c.id_cc = d.id_cc 
		and a.nik = '$username'";
		$result_user_login	= select_data($con,$sql_user_login);
		$company_code		= $result_user_login["COMPANY_CODE"];
		$business_area		= $result_user_login["BUSINESS_AREA"];
		$company_name		= $result_user_login["COMPANY_NAME"];


		if(isset($_SESSION["sql_t_AAP"])){
			$pagesize = 10;	
			
			$sql_t_AAP = $_SESSION["sql_t_AAP"];
			//echo $sql_t_AAP; exit;
			$result_t_AAP = oci_parse($con, $sql_t_AAP);
			oci_execute($result_t_AAP, OCI_DEFAULT);
			$nrows = oci_fetch_all($result_t_AAP, $res_AAP);
			/* echo"<pre>";
			print_r($res_AAP); exit;
			echo"</pre>"; */
			/* echo $res_AAP['ID_RENCANA'][0]." ".$res_AAP['ID_RENCANA'][1]; exit; */
			
			$cek_done = true;
			$jml_max_gandeng = 1;
			$cek_max_gandeng = 1;
			$roweffec_AAP = 0;
			//echo"<br><br><br>";
			foreach($res_AAP['ID_RENCANA'] as $key_AAP=>$item_AAP){
				//echo $res_AAP['ID_RENCANA'][$key_AAP]." ".$cek_max_gandeng."<br>";
				if($cek_done){
					if($res_AAP['ID_RENCANA'][$key_AAP]==$res_AAP['ID_RENCANA'][$key_AAP+1] and $res_AAP['ID_AFD'][$key_AAP]==$res_AAP['ID_AFD'][$key_AAP+1] and $res_AAP['ID_BLOK'][$key_AAP]==$res_AAP['ID_BLOK'][$key_AAP+1]){
						$cek_done = false;
						$no_gdg = $roweffec_AAP;
						$data_gdg[$roweffec_AAP]['NIK_GANDENG'][] = $res_AAP['NIK_GANDENG'][$key_AAP];
						$data_gdg[$roweffec_AAP]['NAMA_GANDENG'][] = $res_AAP['NAMA_GANDENG'][$key_AAP];
						$cek_max_gandeng++;
						
						if($jml_max_gandeng<$cek_max_gandeng){ $jml_max_gandeng=$cek_max_gandeng; }
					} else {
						$cek_done = true;
						$data_gdg[$roweffec_AAP]['NIK_GANDENG'][] = $res_AAP['NIK_GANDENG'][$key_AAP];
						$data_gdg[$roweffec_AAP]['NAMA_GANDENG'][] = $res_AAP['NAMA_GANDENG'][$key_AAP];
						
						
						$cek_max_gandeng = 1;
					}
					$data_gdg[$roweffec_AAP]['ID_RENCANA'] = $res_AAP['ID_RENCANA'][$key_AAP];
					$data_gdg[$roweffec_AAP]['TANGGAL_RENCANA'] = $res_AAP['TANGGAL_RENCANA'][$key_AAP];
					$data_gdg[$roweffec_AAP]['ID_AFD'] = $res_AAP['ID_AFD'][$key_AAP];
					$data_gdg[$roweffec_AAP]['ID_BLOK'] = $res_AAP['ID_BLOK'][$key_AAP];
					$data_gdg[$roweffec_AAP]['BLOK_NAME'] = $res_AAP['BLOK_NAME'][$key_AAP];
					$data_gdg[$roweffec_AAP]['NIK_KERANI_BUAH'] = $res_AAP['NIK_KERANI_BUAH'][$key_AAP];
					$data_gdg[$roweffec_AAP]['NAMA_KERANI_BUAH'] = $res_AAP['NAMA_KERANI_BUAH'][$key_AAP];
					$data_gdg[$roweffec_AAP]['NIK_MANDOR'] = $res_AAP['NIK_MANDOR'][$key_AAP];
					$data_gdg[$roweffec_AAP]['NAMA_MANDOR'] = $res_AAP['NAMA_MANDOR'][$key_AAP];
					$data_gdg[$roweffec_AAP]['NIK_PEMANEN'] = $res_AAP['NIK_PEMANEN'][$key_AAP];
					$data_gdg[$roweffec_AAP]['NAMA_PEMANEN'] = $res_AAP['NAMA_PEMANEN'][$key_AAP];
					$data_gdg[$roweffec_AAP]['LUASAN_PANEN'] = number_format((float)$res_AAP['LUASAN_PANEN'][$key_AAP], 2, '.', '');
					$roweffec_AAP++;
				} else {
					if($res_AAP['ID_RENCANA'][$key_AAP]==$res_AAP['ID_RENCANA'][$key_AAP+1] and $res_AAP['ID_AFD'][$key_AAP]==$res_AAP['ID_AFD'][$key_AAP+1] and $res_AAP['ID_BLOK'][$key_AAP]==$res_AAP['ID_BLOK'][$key_AAP+1]){
						$cek_done = false;
						$data_gdg[$no_gdg]['NIK_GANDENG'][] = $res_AAP['NIK_GANDENG'][$key_AAP];
						$data_gdg[$no_gdg]['NAMA_GANDENG'][] = $res_AAP['NAMA_GANDENG'][$key_AAP];
						$cek_max_gandeng++;
						if($jml_max_gandeng<$cek_max_gandeng){ $jml_max_gandeng=$cek_max_gandeng; }
					} else {
						
						$cek_done = true;
						$data_gdg[$no_gdg]['NIK_GANDENG'][] = $res_AAP['NIK_GANDENG'][$key_AAP];
						$data_gdg[$no_gdg]['NAMA_GANDENG'][] = $res_AAP['NAMA_GANDENG'][$key_AAP];
						
						if($jml_max_gandeng<$cek_max_gandeng){ $jml_max_gandeng=$cek_max_gandeng; }
						$cek_max_gandeng = 1;
					}
				}
				
			}
			//echo $jml_max_gandeng; exit;
			/* echo"<pre style='text-align:left'>";
			print_r($data_gdg); exit;
			echo"</pre>";
			exit; */
			
			if($roweffec_AAP>0)	
			{
				$totalpage = ceil($roweffec_AAP/$pagesize);
				$setPage = $totalpage - 1;
				//echo "totalpage".$totalpage;	
			}
			else{
				$totalpage = 0;
				$roweffec_AAP  = "";
			}

			if(isset($_SESSION["Cpage"])){
				$sesPage = $_SESSION["Cpage"];
			}
			else{
				$sesPage = 0;
			}

			if(isset($_GET["page"])){
				$OnPage = $_GET["page"];
				$CPage = 1;
				if($OnPage == "next"){
					$sesPageres = $sesPage + $CPage;
					if($sesPageres >= $setPage){
						$sesPageres = $setPage;
					}
					$calPage = $sesPageres * $pagesize;
					$_SESSION["Cpage"]  = $sesPageres; 
				}

				else if($OnPage == "back"){
					$sesPageres = $sesPage - $CPage;
					if($sesPageres <= 0){
						$sesPageres = 0;
					}
					$calPage = $sesPageres * $pagesize;
					$_SESSION["Cpage"]  = $sesPageres; 
				}

				else if($OnPage == "first"){
					$sesPageres = 0;
					if($sesPageres <= 0){
						$sesPageres = 0;
					}
					$calPage = $sesPageres * $pagesize;
					$_SESSION["Cpage"]  = $sesPageres; 
				}

				else if($OnPage == "last"){
					$sesPageres = $totalpage;
					if($sesPageres >= $setPage){
						$sesPageres = $setPage;
					}
					$calPage = $sesPageres * $pagesize;
					$_SESSION["Cpage"]  = $sesPageres; 
				}

				else{
					$calPage = 0; 
				}
			} else {
				$CPage = 0;
				$sesPageres = $sesPage + $CPage;
				$calPage = $sesPageres * $pagesize;
				$_SESSION["Cpage"]  = $sesPageres;  
			}
		}
		else{
			$_SESSION[err] = "Please check input value!";
			header("location:LaporanAAP.php");
		}
	}

	?>

	<?php
}
else{
	$_SESSION[err] = "Please Login!";
	header("location:../index.php");
}
?>

<style type="text/css">
	a:link {
		text-decoration: none;
	}
	a:visited {
		text-decoration: none;
	}
	a:hover {
		text-decoration: none;
	}
	a:active {
		text-decoration: none;
	}
	body,td,th {
		font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		font-size: 16px;
		font-weight:normal;
	}
</style>
<table width="1151" height="390" border="0" align="center">
	<!--<tr bgcolor="#C4D59E">-->
	<tr>
		<th height="197" scope="row" align="center"><table width="937" border="0" id="setbody2">
			<tr>
				<td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>LAPORAN AKTIVITAS AKHIR PANEN</strong></span></td>
				<td width="619" align="right">
					<a href="printXLS.php">
						<input type="submit" name="button" id="button" value="DOWNLOAD TO XLS" style="width:200px; height: 30px; font-size:16px; visibility:<?=$visisub?>" onclick="formSubmit(1)"/>
					</a>
				</td>
			</tr>
			<tr>
				<tr>
					<td width="130" height="29" valign="top">Company Code</td>
					<td width="7" height="29" valign="top">:</td>
					<td width="355" align="left" valign="top"><input name="company_code" type="text" id="company_code" value="<?=$company_code?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly="readonly" />
					</td>
				</tr>
				<tr>
					<td width="130" height="29" valign="top">Business Area</td>
					<td width="7" height="29" valign="top">:</td>
					<td width="355" align="left" valign="top"><input name="business_area" type="text" id="business_area" value="<?=$business_area?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly="readonly" />
					</td>
				</tr>
				<tr>
					<td width="130" height="29" valign="top">Company Name</td>
					<td width="7" height="29" valign="top">:</td>
					<td width="355" align="left" valign="top"><input name="company_name" type="text" id="company_name" value="<?=$company_name?>" style="background-color:#CCC; width: 350px; height:25px; font-size:15px" readonly="readonly" />
					</td>
				</tr>

				<tr>
					<td colspan="4" valign="top">
					<table width="1134" border="0">

						<tr bgcolor="#9CC346"  >
							<th width="50px" rowspan=2 align="center" style="font-size:14px" id="bordertable">Tanggal</th>
							<th width="25px" rowspan=2 align="center" style="font-size:14px" id="bordertable">Afd</th>
							<th width="30px" rowspan=2 align="center" style="font-size:14px" id="bordertable">Blok</th>
							<th width="30px" rowspan=2 align="center" style="font-size:14px" id="bordertable">Blok Desk</th>
							<th colspan=2 align="center" style="font-size:14px" id="bordertable">Krani Buah</th>
							<th colspan=2 align="center" style="font-size:14px" id="bordertable">Mandor</th>
							<th colspan=2 align="center" style="font-size:14px" id="bordertable">Karyawan</th>
							<th width="30px" rowspan=2 align="center" style="font-size:14px" id="bordertable">Ha Panen</th>
							
							<?php for($jj=1;$jj<=$jml_max_gandeng;$jj++){ ?>
							<th colspan=2 align="center" style="font-size:14px" id="bordertable">Pemanen Gandeng <?= $jj ?></th>
							<?php } ?>    
						</tr>
						<tr bgcolor="#9CC346">
							<th width="60px" align="center" style="font-size:14px" id="bordertable">NIK</th>
							<th width="60px" align="center" style="font-size:14px" id="bordertable">NAMA</th>
							<th width="60px" align="center" style="font-size:14px" id="bordertable">NIK</th>
							<th width="60px" align="center" style="font-size:14px" id="bordertable">NAMA</th>
							<th width="60px" align="center" style="font-size:14px" id="bordertable">NIK</th>
							<th width="60px" align="center" style="font-size:14px" id="bordertable">NAMA</th>
							
							<?php for($jj=1;$jj<=$jml_max_gandeng;$jj++){ ?>	
							<th width="60px" align="center" style="font-size:14px" id="bordertable">NIK</th>
							<th width="60px" align="center" style="font-size:14px" id="bordertable">NAMA</th>
							<?php } ?>
						
						</tr>
						
						<?php
						$endPage = $calPage + $pagesize;
						$tanda = 0;
						for($xJAN = $calPage; $xJAN <  $roweffec_AAP && $xJAN <$endPage; $xJAN++){
							
							if(($xJAN % 2) == 0){
								$bg = "#F0F3EC";
							}
							else{
								$bg = "#DEE7D2";
							}
							
							//Added by Ardo 29-09-2016 : CR Perubahan perhitungan Luasan Panen
							if($tanda==0){
								$val_luasan_panen = $data_gdg[$xJAN]['LUASAN_PANEN'];
							} else {
								if($data_gdg[$xJAN]['TANGGAL_RENCANA']!==$data_gdg[$xJAN-1]['TANGGAL_RENCANA'] || $data_gdg[$xJAN]['ID_AFD']!==$data_gdg[$xJAN-1]['ID_AFD'] || $data_gdg[$xJAN]['ID_BLOK']!==$data_gdg[$xJAN-1]['ID_BLOK'] || $data_gdg[$xJAN]['NIK_PEMANEN']!==$data_gdg[$xJAN-1]['NIK_PEMANEN']){
									$val_luasan_panen = $data_gdg[$xJAN]['LUASAN_PANEN'];
								} else {
									$val_luasan_panen = $data_gdg[$xJAN-1]['LUASAN_PANEN'];
								}
							}
							$tanda++;
							echo "<tr style=\"font-size:12px\" bgcolor=$bg >";
							echo "
							<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">".$data_gdg[$xJAN]['TANGGAL_RENCANA']."</td>
							<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">".$data_gdg[$xJAN]['ID_AFD']."</td>
							<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">".$data_gdg[$xJAN]['ID_BLOK']."</td>
							<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">".$data_gdg[$xJAN]['BLOK_NAME']."</td>            
							<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">".$data_gdg[$xJAN]['NIK_KERANI_BUAH']."</td>
							<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">".$data_gdg[$xJAN]['NAMA_KERANI_BUAH']."</td>
							<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">".$data_gdg[$xJAN]['NIK_MANDOR']."</td>
							<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">".$data_gdg[$xJAN]['NAMA_MANDOR']."</td>
							<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">".$data_gdg[$xJAN]['NIK_PEMANEN']."</td>
							<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">".$data_gdg[$xJAN]['NAMA_PEMANEN']."</td>";
							echo"<td style=\"font-size:12px\" align=\"center\" id=\"bordertable\">$val_luasan_panen</td>";
							for($xjml=0;$xjml<$jml_max_gandeng;$xjml++){
								if(isset($data_gdg[$xJAN]['NIK_GANDENG'][$xjml])){
									echo"<td width=\"\" style=\"font-size:12px\" align=\"center\" id=\"bordertable\">".$data_gdg[$xJAN]['NIK_GANDENG'][$xjml]."</td>
									<td width=\"\" style=\"font-size:12px\" align=\"center\" id=\"bordertable\">".$data_gdg[$xJAN]['NAMA_GANDENG'][$xjml]."</td>";
								
								} else {
									echo"<td width=\"\" style=\"font-size:12px\" align=\"center\" id=\"bordertable\">-</td>
									<td width=\"\" style=\"font-size:12px\" align=\"center\" id=\"bordertable\">-</td>";
								}
							}
							

							
							echo "</tr>";
							
						}
						
						?>

					</table></td>
				</tr>

				<tr>
					<td colspan="3" align="right">
						<table width="400" border="0">
							<tr>
								<td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="LaporanAAPList.php?page=first">
									<input type="button" name="button6" id="button6" value="&lt;&lt; First" style="width:70px; background-color:#9CC346"/>
								</a></td>
								<td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="LaporanAAPList.php?page=back">
									<input type="button" name="button5" id="button5" value="&lt; Back" style="width:70px; background-color:#9CC346"/>
								</a></td>
								<td width="100" align="center" valign="middle" style="background-color:#9CC346; font-size:12px"><span style="padding-top:0px">Page
									<?=$sesPageres+1?>
									of
									<?=$totalpage?>
								</span></td>
								<td width="70" align="left" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="LaporanAAPList.php?page=next"></a><a href="LaporanAAPList.php?page=next">
									<input type="button" name="button4" id="button4" value="Next &gt;" style="width:70px; background-color:#9CC346"/>
								</a></td>
								<td width="70" align="right" valign="middle" style="background-color:#9CC346; font-size:12px"><a href="LaporanAAPList.php?page=last">
									<input type="button" name="button7" id="button7" value="Last &gt;&gt;" style="width:70px; background-color:#9CC346"/>
								</a></td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td colspan="4"><?php
						if(isset($_SESSION['err'])){
							$err = $_SESSION['err'];
							if($err!=NULL)
							{
								echo $err;
								unset($_SESSION['err']);
							}
						}
						?>
					</td>
				</tr>
				</table>
				</th>
			</tr>
			<tr>
				<th align="center"><?php include("../include/Footer.php") ?></th>
			</tr>
		</table>

