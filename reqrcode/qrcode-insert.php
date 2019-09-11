<?php
session_start();
//echo $_POST["werks"]." - ".$_POST["afd"];
//die();

require '../qrcode/vendor/autoload.php';
use GDText\Box;
use GDText\Color;

include("../config/SQL_function.php");
include("../config/db_config.php");
$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');

$pdfroot  = dirname(dirname(__FILE__));
$pdfroot .= '/qrcode/print_pdf_result/';
//echo $pdfroot;

/* Start = Generate QR Code */
//if (isset($_POST) && !empty($_POST) && $_POST['submit'] == 'GENERATE') {
//	$pdfname = date('Y-m-d') . '-' . $_POST['werks'] . '-' . $_POST['afd'] . '.pdf';

//	$total = 0;
//	foreach ($_POST['tph'] as $row) {
//		$total = $total + $row;
//	}

	include('../qrcode/qr_lib/qrlib.php');
	$cons = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');

	$sql = "INSERT INTO EBCC.T_REPRINT_TPH (WERKS, AFD, BLOK, TPH, DATE_CREATED, USER_CREATED) VALUES ('{$_POST['werks']}', '{$_POST['afd']}', '{$_POST['block']}', '{$_POST['tph']}', sysdate, '{$_SESSION['NIK']}')";
			$st = oci_parse($cons, $sql);
			$r = oci_execute($st, OCI_NO_AUTO_COMMIT);
			oci_commit($cons);

//	$count = 0;
//	$namefiles = array();
//	foreach ($_POST['blok_code'] as $k => $v) {
//		if (!empty($_POST['tph'][$k]) && $_POST['tph'][$k] != '0' && $_POST['tph'][$k] != null) {
//			$sql = "INSERT INTO EBCC.T_BLOK_TPH (WERKS, AFD, BLOCK_CODE, TPH, CREATED_AT, CREATED_BY) VALUES ('{$_POST['werks']}', '{$_POST['afd']}', '{$v}', '{$_POST['tph'][$k]}', sysdate, 'system')";
//			$st = oci_parse($cons, $sql);
//			$r = oci_execute($st, OCI_NO_AUTO_COMMIT);

//			if (!$r) {
//				$e = oci_error($stid);
//				oci_rollback($cons);
//				trigger_error(htmlentities($e['message']), E_USER_ERROR);
//			}

//			$r = oci_commit($cons);
//			if (!$r) {
//				$e = oci_error($cons);
//				trigger_error(htmlentities($e['message']), E_USER_ERROR);
//			} else {
//				if (!empty($_POST['tph'][$k]) && $_POST['tph'][$k] != '0' && $_POST['tph'][$k] != null) {
//					for ($i = 1; $i <= $_POST['tph'][$k]; $i++) {
						$v = $_POST["block"];
						$z = sprintf("%03s", $_POST['tph']);
						$filename = 'TAPQRCODE' . $_POST['werks'] . '-' . $_POST['afd'] . '-' . $v . '-' . $z . '.png';
						//$namefiles[] = $_POST['blok_name']. '|' . $filename;

						$qr = $z . '-' . $_POST['afd'] . '-' . $_POST['werks'] . '-' . $v;
						$data = base64_encode($qr);

						QRcode::png($data, 'print/' . $filename, 'H', 44, 1);

						$frameloc = '../qrcode/border.jpg';
						$imageloc = __DIR__ . '/print/' . $filename;
						$frame = imagecreatefromjpeg($frameloc);
						$image = imagecreatefrompng($imageloc);

						$text = $_POST['werks'] . ' Afd. ' . $_POST['afd'];
						$text1 = 'Block ' . $_POST['block'] . ' / ' . $_POST['blok_name'];
						$text2 = 'TPH ' . $z;

						$box = new Box($frame);
						$box->setFontFace('../qrcode/arial-bold.TTF');
						$box->setFontSize(150);
						$box->setFontColor(new Color(0, 0, 0));
						$box->setBox(-20, -700, 1193, 1783);
						$box->setTextAlign('center', 'center');
						$box->draw($text);

						$box = new Box($frame);
						$box->setFontFace('../qrcode/arial-bold.TTF');
						$box->setFontSize(120);
						$box->setFontColor(new Color(0, 0, 0));
						$box->setBox(-20, -525, 1193, 1783);
						$box->setTextAlign('center', 'center');
						$box->draw($text1);

						$box = new Box($frame);
						$box->setFontFace('../qrcode/arial-bold.TTF');
						$box->setFontSize(200);
						$box->setFontColor(new Color(0, 0, 0));
						$box->setBox(-10, -325, 1193, 1783);
						$box->setTextAlign('center', 'center');
						$box->draw($text2);

						imagecopymerge($frame, $image, 80, 690, 0, 0, 1012, 1012, 100);

						imagepng($frame, __DIR__ . '/print_result/' . $filename);
						imagedestroy($image);
						imagedestroy($frame);

						/*$im = new imagick(__DIR__ . '/print_result/' . $filename);
						//$im->setImageUnits(imagick::RESOLUTION_PIXELSPERCENTIMETER);
						$im->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);
						$im->setImageResolution(300,300);
						$im->setImageFormat("jpg");
						$im->writeImage(__DIR__ . '/print_result/' . str_replace('.png', '.jpg', $filename));
						$im->clear();
						$im->destroy();
						unlink(__DIR__ . '/print_result/' . $filename);*/
						
						$filePath = __DIR__ . '/print_result/' . $filename;
						$image = imagecreatefrompng($filePath);
						$bg = imagecreatetruecolor(imagesx($image), imagesy($image));
						imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
						imagealphablending($bg, TRUE);
						imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
						imagedestroy($image);
						$quality = 100; // 0 = worst / smaller file, 100 = better / bigger file 
						imagejpeg($bg, str_replace('.png', '', $filePath) . ".jpg", $quality);
						imagedestroy($bg);

						$fileFinalPath = str_replace('.png', '.jpg', __DIR__ . '/print_result/' . $filename);
						$newImagePath = str_replace(array('TAPQRCODE', '.png'), array('', '.jpg'), __DIR__ . '/print_result/' . $filename);
						header('Content-Type: image/jpeg');
						$imageGet = file_get_contents($fileFinalPath);

						if($imageGet){
							$imageConverted = substr_replace($imageGet, pack("cnn", 1, 300, 300), 13, 5);
							$savefile = file_put_contents($newImagePath, $imageConverted);
							unlink(__DIR__ . '/print_result/' . $filename);
							unlink(__DIR__ . '/print_result/' . str_replace('.png', '.jpg', $filename));
							unlink(__DIR__ . '/print/' . $filename);

							$filename = str_replace('.png', '.jpg', $filename);
							echo 'http://'.$_SERVER['HTTP_HOST'] .'/ebcc/reqrcode/print_result/' . str_replace('TAPQRCODE', '', $filename);
						} else {
							echo 'Failed';
						}
						//$filename = str_replace('.png', '.jpg', $filename);
						//echo 'http://'.$_SERVER['HTTP_HOST'] .'/ebcc/reqrcode/print_result/' . str_replace('TAPQRCODE', '', $filename);
						//print "<img src=\"$image\" width=\"100px\" height=\"100px\"\/>";

/*						require_once ('smbclient.php');
						$smbc = new smbclient ('//10.20.1.7/QRcode', 'tap\eko.lesmana', 'tap12345');
						//echo '<pre>'; print_r ($smbc); echo '</pre>';
						if (!$smbc->put (__DIR__ . '/print_result/' . str_replace(array('TAPQRCODE', '.png'), array('', '.jpg'), $filename), str_replace(array('TAPQRCODE', '.png'), array('', '.jpg'), $filename))) {
							//print "Failed to retrieve file:\n";
							//print join ("\n", $smbc->get_last_stdout());
						} else {
							//print "Transferred file successfully.";
						}
*/
//					}
//				}
//			}
//		}
//		$count++;
//	}

	//session_start();
	//$_SESSION['print_pdf'] = 'Success';

	//$url = 'http://'.$_SERVER['HTTP_HOST'] . '/ebcc/reqrcode/reqrcode.php';
	//header('Location:' . $url);
//}
/* End = Generate QR Code */
