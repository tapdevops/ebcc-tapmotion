<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
=========================================================================================================================
Project				: 	TAP PUSH REPORT
Versi				: 	1.0.0
Deskripsi			: 	Controller Class untuk execute fungsi mengirim email dan crontab
Function 			:	- getNameFromNumber	: SID 12/03/2015	: change number to excel alphabet column
						- file_xls			: ARS 10/03/2015	: Generate file to .xls 
						- email_sender		: ARS 10/03/2015	: send email
Disusun Oleh		: 	IT Enterprise Solution - PT Triputra Agro Persada
Developer			: 	Aries Sholehudin
Dibuat Tanggal		: 	01/03/2015
Update Terakhir		:	27/04/2015
Revisi				:	
	SID 27/04/2015	: number format
=========================================================================================================================
*/
class Push_report_exe extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('tbl_push_report');
	}
	
	//change number to excel alphabet column
	function getNameFromNumber($num) {
		$numeric = $num % 26;
		$letter = chr(65 + $numeric);
		$num2 = intval($num / 26);
		if ($num2 > 0) {
			return $this->getNameFromNumber($num2 - 1) . $letter;
		} else {
			return $letter;
		}
	}
	
	function file_xls($code){ //langsung jadi file	
		$user = $this->session->userdata('user');
		$setRep = $this->tbl_push_report->get_report($code)->row();
		
		//INSERT LOGS
		$id_log = $this->tbl_push_report->insert_log($setRep->REPORT_CODE, $user);
		
		//replace parameter tanggal di query
		$tglrpt =  str_replace("'",'"',$setRep->TGL_REPORT);
		
		//CEK PROCEDURE YANG AKAN DIGUNAKAN OLEH PUSHREPORT
		$cek = 0;
		$isProc = "";
		$list_proc = $this->tbl_push_report->get_prereqruisite_proc($setRep->REPORT_CODE);
		if ($list_proc->num_rows() > 0){
			foreach ($list_proc->result() as $lproc){
				$cekProc = $this->tbl_push_report->cek_prereqruisite_proc($lproc->PROC_NAME)->num_rows();	
				if ($cekProc == 0 ){
					$cek++;
					$isProc .= "<b>- ".$lproc->PROC_NAME."</b><br/>";
				}
			}
			
			if ($cek != 0){
				$isProc = rtrim($isProc, ',');
				$header = "Push Report Failed - ".$setRep->REPORT_CODE; 
				$body = "Push Report <b>(".$setRep->REPORT_CODE.") ".$setRep->REPORT_NAME."</b> Gagal Terkirim.<br/>Harus Jalankan Proc :<br/>".$isProc; 
				$to = "tap.it.solution.dept@tap-agri.com"; 
				$cc = ""; 
				$bcc = ""; 
				$file = $file_name = $new_dir = $attach = $tbl = $pmonth = $pday = "";
				
				$this->email_sender($header, $body, $to, $cc, $bcc, $file, $file_name, $new_dir, $attach, $tbl, $pmonth, $pday);
				echo "<script>window.close();</script>";
				die();
			}
		}	
		//end cek procedure 

		//ini kalo generate share folder nya di ceklist 
		if ($setRep->CREATE_SHARE == "Y"){ 
			$file_name = $setRep->REPORT_CODE.".sql";
			$file_dir = './TMP/';
			$file = $file_dir.$file_name;
			$query = file_get_contents($file);
			
			if ($setRep->TGL_REPORT != ""){
				$str_query = str_replace("[date]",$tglrpt,$query);
			}else{
				$str_query = str_replace("[date]",$setRep->DEF_TGL_REPORT,$query);
			}
			
			$data = $this->tbl_push_report->run_query($str_query, $start="", $end="");
			if (!$data){
				echo "<center>ERROR QUERY GENERATE XLS !!!</center><br/><br/>";
				echo $this->db->last_query();
				die();
			}
			
			//cek data hasil query kalo ga ada isisnya ga usah dilanjutin
			if ($data['query']->num_rows() == 0){
				$header = "Data Not Found"; 
				$body = "This report <b>(".$setRep->REPORT_CODE.") ".$setRep->REPORT_NAME."</b> return null data."; 
				$to = "tap.it.solution.dept@tap-agri.com"; 
				$cc = ""; 
				$bcc = ""; 
				$file = $file_name = $new_dir = $attach = $tbl = $pmonth = $pday = "";
				
				$this->email_sender($header, $body, $to, $cc, $bcc, $file, $file_name, $new_dir, $attach, $tbl, $pmonth, $pday);
				echo "<script>window.close();</script>";
				die();
			}
			
			//untuk Nama file sesuai tanggal
			$fn = explode("--",$setRep->FILE_NAME);
			$resfilename = "";

			for ($i=0; $i < count($fn); $i++){
				$get_name = $this->tbl_push_report->get_name($fn[$i], $tglrpt, $setRep->DEF_TGL_REPORT);
				if (!$get_name){
					$resfilename .= $fn[$i];
				}else{
					$resfilename .= $get_name->row()->FN;
				}
			}
			
			$file_name = $resfilename.".xls";
			$file_dir = './tmp_report/';
			
			if (!is_dir($file_dir) ) {
				$oldumask = umask(0);
				if (!mkdir($file_dir, 0777, true)) {
					die('Failed to create folders...');
				} // or even 01777 so you get the sticky bit set
				chmod($file_dir, 0777);
				umask($oldumask);
			}
			
			$new_dir = getcwd()."/win_report/".$setRep->SHARE_FOLDER;
			if (!is_dir($new_dir)) { 
				mkdir($new_dir, 0777, true);
				chmod($new_dir, 0777);
			}
			
			$file = $file_dir."/".$file_name;

			//==================================== php excel ========================================================
				$this->load->library("excel");
				
				//Create multiple sheet
				$s = 0;
				$start = 0;
				$end = 65535; // jumlah field yang akan ditampilkan dalam satu sheet (value harus sama dengan $rowxl) defaul row excel .xls 65535
				$rowxl = 65535; // jumlah field yang akan ditampilkan dalam satu sheet (value harus sama dengan $end) defaul row excel .xls 65535
				$maxsheet = ceil($data['query']->num_rows()/$rowxl);
				
				$this->excel->disconnectWorksheets(); //deleting default WorkSheet
				while ($s < $maxsheet) {
				
					$this->excel->createSheet($s);
					$this->excel->setActiveSheetIndex($s);
					
					// Field names in the first row
					$fields = $data['query']->field_data();
					
					//border
					$styleArray1 = array(
					'borders' => array(
						'allborders' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
							)
					)
					);
						
					//fill color first row
					$styleArray2 = array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('rgb' => '818181')
					),
					'font'  => array(
						'bold'  => true,
						'color' => array('rgb' => 'FFFFFF')
					),
					'alignment' => array(
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					)
					);
					
					//fill color line row
					$styleArrayline = array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('rgb' => '888888')
					),
					'font'  => array(
						'bold'  => true,
						'color' => array('rgb' => 'FFFFFF')
					)
					);
					
					
					$col = 0;
					$i = 1;
					foreach ($fields as $field)				
					{
						if ($field->name != 'MY_ROWNUM'){ //Filter untuk ROWNUM tidak dianggap Field
							if($i%2 == 0){
								//header table (first row)
								$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field->name);
								$this->excel->getActiveSheet()->getColumnDimension(chr(65+$col))->setAutoSize(true);
								$col++;
							}
							$i++;
						}
					}
					$max_col = $col-1;
					
					// Fetching the table data (detail row)
					$row = 2;
					$data = $this->tbl_push_report->run_query($str_query, $start, $end); // Selecting Query Perpage (detail row)
					foreach($data['query']->result() as $datafld)
					{
						$col = 0;
						$i = 1;
						
						foreach ($fields as $field)
						{
					
						//MEWARNAI TABEL SELANG SELING
						//if($row%2 != 0){
						//	$this->excel->getActiveSheet()->getStyle($this->getNameFromNumber($col).$row.':'.$this->getNameFromNumber($max_col).$row)->applyFromArray($styleArrayline);
						//}
					
						$fldname = $field->name;
						$fldtype = $field->type;
						
						if ($field->name != 'MY_ROWNUM'){ //Filter ROWNUM tidak dianggap Field
							if($i%2 != 0){ 										//ambil data attribute dari field ganjil
								$attr = explode('|||',$datafld->$fldname);
								$type = str_replace(' ', '', $attr[0]); 
								$bg = (str_replace(' ', '', $attr[1]) == "")?('#ffffff'):($attr[1]);
								$font = (str_replace(' ', '', $attr[2]) == "")?('#000000'):($attr[2]);
								$weight = str_replace(' ', '', $attr[3]);
								$style = str_replace(' ', '', $attr[4]);
							}else{												//apply attribute ke field setelah nya
								$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $datafld->$fldname);
								
								//ini setingan untuk background color
								if ($bg != '#ffffff'){
								$this->excel->getActiveSheet()->getStyle($this->getNameFromNumber($col).$row)
									->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()
									->setRGB(str_replace('#','',$bg));
								}
								
								//ini setingan untuk font color
								$this->excel->getActiveSheet()->getStyle($this->getNameFromNumber($col).$row)->getFont()->getColor()->setRGB(str_replace('#','',$font));
								
								//ini settingan untuk font bold
								if ($weight != ""){
									$this->excel->getActiveSheet()->getStyle($this->getNameFromNumber($col).$row)->getFont()->setBold(true);
								}else{
									$this->excel->getActiveSheet()->getStyle($this->getNameFromNumber($col).$row)->getFont()->setBold(false);
								}
								
								//ini settingan untuk font bold
								if ($style != ""){
									$this->excel->getActiveSheet()->getStyle($this->getNameFromNumber($col).$row)->getFont()->setItalic(true);
								}else{
									$this->excel->getActiveSheet()->getStyle($this->getNameFromNumber($col).$row)->getFont()->setItalic(false);
								}
								
								//ini untuk format data nya
								if ($type == "DATE" && $datafld->$fldname != ""){
									$this->excel->getActiveSheet()->getStyle($this->getNameFromNumber($col).$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
								}elseif ($type == "DATETIME" && $datafld->$fldname != ""){
									$this->excel->getActiveSheet()->getStyle($this->getNameFromNumber($col).$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME);
								}elseif ($type == "INTEGER" && $datafld->$fldname != ""){
									$this->excel->getActiveSheet()->getStyle($this->getNameFromNumber($col).$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
								}elseif ($type == "DECIMAL" && $datafld->$fldname != ""){
									$this->excel->getActiveSheet()->getStyle($this->getNameFromNumber($col).$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
								}else{
								
								}
								
								//kosongin variable untuk field berikut nya
								$type = ""; 
								$bg = "";
								$font = "";
								$weight = "";
								$style = "";
								$col++;
							}
							$i++;
						}
						
						}

						$row++;
					}
					$excel_col = $this->getNameFromNumber($max_col);
					
					//give style to first row
					$this->excel->getActiveSheet()->getStyle('A1:'.$excel_col.($row-1))->applyFromArray($styleArray1);
					$this->excel->getActiveSheet()->getStyle('A1:'.$excel_col."1")->applyFromArray($styleArray2);
					
					unset($styleArray);
					
					$this->excel->getActiveSheet()->setTitle('Sheet'.($s+1)); //untuk nama sheet dimulai dari 1
					
					$s++; //next sheet
					$start = $start + $rowxl; //start for query next sheet
					$end = $end + $rowxl; //end for query next sheet
				}
				
				$this->excel->save($file);
			//==================================================================================================
		}else{
			$file = "";
			$file_name = ""; 
			$setRep->SHARE_FOLDER = ""; 
			$setRep->SENT_ATTACHMENT = "";
		}
		
		//========= buat summary table body email ============
		for ($x = 1; $x <= 5; $x++) {
			
			$file_names = "summary_".$setRep->REPORT_CODE."_".$x.".sql";
			$file_dir = './TMP/';
			$filesum = $file_dir.$file_names;
			$sumquery = file_get_contents($filesum);

			//ini cek kalo query summary nya ga diisi
			if (strpos($sumquery,'SELECT') !== false){
				if ($tglrpt != ""){
					$str_query = str_replace("[date]",$tglrpt,$sumquery);
				}else{
					$str_query = str_replace("[date]",$setRep->DEF_TGL_REPORT,$sumquery);
				}
			
				$summ = $this->tbl_push_report->run_query_summary($str_query);
				
				if (!$summ){
					echo "ERROR QUERY SUMMARY ".$x."!!!";
					die();
				}else{
					
					if ($summ->num_rows() == 0){
						$header = "Data Not Found"; 
						$body = "This report <b>(".$setRep->REPORT_CODE.") ".$setRep->REPORT_NAME." - ".$file_names."</b> return null data."; 
						$to = "tap.it.solution.dept@tap-agri.com"; 
						$cc = ""; 
						$bcc = ""; 
						$file = $file_name = $new_dir = $attach = $tbl = $pmonth = $pday = "";
						
						$this->email_sender($header, $body, $to, $cc, $bcc, $file, $file_name, $new_dir, $attach, $tbl, $pmonth, $pday);
						echo "<script>window.close();</script>";
						die();
					}
					
					//setting color summary
					if (!$setRep->COLOR_SETTING){
						$headerBg = 'cccccc'; 
						$fontColor = '000000';
						$lineBg1 = 'ffffff'; 
						$lineColor1 = '000000';  
						$lineBg2 = 'ffffff'; 
						$lineColor2 = '000000'; 
					}else{
						$headerBg = $setRep->HEADER_BG; 
						$fontColor = $setRep->HEADER_COLOR;
						$lineBg1 = $setRep->LINE_1_BG; 
						$lineColor1 = $setRep->LINE_1_COLOR;  
						$lineBg2 = $setRep->LINE_2_BG; 
						$lineColor2 = $setRep->LINE_2_COLOR; 
					}
				
					// ini untuk header nya
					$flds = $summ->field_data();
					$tbl[$x] = "<table border='1' style='font-family:Verdana; font-size:10px; border: solid 1px #999;'>
							<tr style='background-color:#".$headerBg."; color:#".$fontColor."; border: solid 1px #999;'>";
					$i = 1;		
					foreach ($flds as $fld)
					{
						if($i%2 == 0){
							$tbl[$x] .= "<td style='border: solid 1px #999;' align='center'><b>".$fld->name."</b></td>";
						}						
						$i++;
					}
						$tbl[$x] .= "</tr>";
					
					// ini untuk detail nya
					$trow = 2;
					foreach($summ->result() as $sm)
					{
						$i = 1;
						
						//ini buat warnain table nya selang seling
						if($trow%2 != 0){
							$tbl[$x] .= "<tr style='background-color:#".$lineBg1."; color:#".$lineColor1.";'>";
						}else{
							$tbl[$x] .= "<tr style='background-color:#".$lineBg2."; color:#".$lineColor2.";'>";
						}
						
						foreach ($flds as $fld)
						{
								$fname = $fld->name;
								$ftype = $fld->type;

								if($i%2 != 0){ 								//ini ambil attribut field nya kolom ganjil
									$attr = explode('|||',$sm->$fname);
									$type = $attr[0]; 
									$bg = $attr[1];
									$font = $attr[2];
									$weight = $attr[3];
									$style = $attr[4];
									$parchart = $attr[5];
								}else{ 										//ini ambil menampilkan field nya kolom genap
									if ($type == "DATE" && $sm->$fname != ""){
										$tbl[$x] .= "<td style='background:".$bg."; color:".$font."; font-weight:".$weight."; font-style:".$style.";'>".date("d-m-Y", strtotime($sm->$fname))."</td>";
									}elseif ($type == "DATETIME" && $sm->$fname != ""){
										$tbl[$x] .= "<td style='background:".$bg."; color:".$font."; font-weight:".$weight."; font-style:".$style.";'>".date("d-m-Y H:i:s", strtotime($sm->$fname))."</td>"; 
									}elseif ($type == "INTEGER" && $sm->$fname != ""){
										$tbl[$x] .= "<td style='background:".$bg."; color:".$font."; font-weight:".$weight."; font-style:".$style.";' align='right'>".number_format($sm->$fname)."</td>";
									}elseif ($type == "DECIMAL" && $sm->$fname != ""){
										$tbl[$x] .= "<td style='background:".$bg."; color:".$font."; font-weight:".$weight."; font-style:".$style.";' align='right'>".number_format($sm->$fname,2, '.', ',')."</td>";
									}else{
										$tbl[$x] .= "<td style='background:".$bg."; color:".$font."; font-weight:".$weight."; font-style:".$style.";'>".$sm->$fname."</td>"; //default type = TEXT
									}
									
									//================================= setting variable for chart ==========================================
									if ($parchart == 'xdata'){
										$dataX[] = $sm->$fname;
									}elseif ($parchart == 'xlabel'){
										$axisX[] = $sm->$fname;
									}	
									
									
									$type = ""; 
									$bg = "";
									$font = "";
									$weight = "";
									$style = "";
									$width = "";	
								}
							$i++;
							
						}
						
						$tbl[$x] .= "</tr>";
						//variable for graphics
						//$dataX[] = $sm->JML_JJG;
						//$axisX[] = date('d', strtotime($sm->TGL_REPORT));
						//$legendX = date('Y', strtotime($sm->TGL_REPORT));
						$trow++;
					}
					$tbl[$x] .= "</table>";
					//call grafik
					//$this->tampilgrafik($dataX, $axisX, $legendX);
					//$this->tampilgrafik($setRep->EMAIL_HEADER, $dataX, $axisX);
				}	
			}else{
				$tbl[$x] = "";
				//$dataX[$x] = "";
				//$axisX[$x] = "";
			}
		}
		
		//============= SENT EMAIL ================
		if ($setRep->SENT_EMAIL == "Y"){
			if (file_exists('./TMP/'.$setRep->REPORT_CODE.'-sendto.txt')) {
				$mailto = file_get_contents('./TMP/'.$setRep->REPORT_CODE.'-sendto.txt');
			}else{
				$mailto = '';
			}
			if (file_exists('./TMP/'.$setRep->REPORT_CODE.'-sendcc.txt')) {
				$mailcc = file_get_contents('./TMP/'.$setRep->REPORT_CODE.'-sendcc.txt');
			}else{
				$mailcc = '';
			}
			if (file_exists('./TMP/'.$setRep->REPORT_CODE.'-sendbcc.txt')) {
				$mailbcc = file_get_contents('./TMP/'.$setRep->REPORT_CODE.'-sendbcc.txt');
			}else{
				$mailbcc = '';
			}
			
			//penambahan dynamic periode untuk di body email 
			$pmonth = $this->tbl_push_report->get_name('MonthRRRR', $tglrpt, $setRep->DEF_TGL_REPORT)->row()->FN;;
			$pday = $this->tbl_push_report->get_name('DD MonthRRRR', $tglrpt, $setRep->DEF_TGL_REPORT)->row()->FN;;
			
			if ($this->email_sender($setRep->EMAIL_HEADER, $setRep->EMAIL_BODY, $mailto, $mailcc, $mailbcc, $file, $file_name, $setRep->SHARE_FOLDER, $setRep->SENT_ATTACHMENT, $tbl, $pmonth, $pday) === TRUE){
				shell_exec ("mv ".getcwd()."/tmp_report/".$file_name." ".$new_dir."/".$file_name);
				$this->tbl_push_report->update_endTask($id_log);
				echo "<script>window.close();</script>";
			}
		}else{
			shell_exec ("mv ".getcwd()."/tmp_report/".$file_name." ".$new_dir."/".$file_name);
			$this->tbl_push_report->update_endTask($id_log);
			echo "<script>window.close();</script>";
		}
		$this->tbl_push_report->update_endTask($id_log);
		$this->tbl_push_report->del_tglReport($setRep->REPORT_CODE); //hapus tanggal report
	}
	
	function email_sender($header, $body, $to, $cc, $bcc, $file, $file_name, $new_dir, $attach, $tbl, $pmonth, $pday){
		$this->load->library('email');

		$config['protocol']    = 'smtp';
		$config['smtp_host']    = 'smtp.tap-agri.com';
		$config['smtp_port']    = '25';
		$config['smtp_timeout'] = '7';
		$config['smtp_user']    = '';
		$config['smtp_pass']    = '';
		$config['charset']    = 'iso-8859-1';
		$config['newline']    = "\r\n";
		$config['crlf']    = "\r\n";
		$config['mailtype'] = 'html'; // or text
		$config['validation'] = TRUE; // bool whether to validate email or not      

		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from('no-reply@tap-agri.com','Automatic Push Report');
		$this->email->to($to); 
		$this->email->cc($cc); 
		$this->email->bcc($bcc);
		$this->email->subject($header);
		
		//========= replace body text to retrive parameter 
		$search = array ("[file_name]", "[file_location]", "[summary1]", "[summary2]", 
						 "[summary3]", "[summary4]", "[summary5]", "[p_month]", "[p_day]");
		$replace = array ($file_name, str_replace("/", "\\", '\\'.'\fs.tap-agri.com\Automatic_Push_Report\\'.$new_dir), 
					      $tbl[1], $tbl[2], $tbl[3], $tbl[4], $tbl[5], $pmonth, $pday); 
		$body = str_replace($search, $replace, $body);
		
		//$this->email->message(htmlspecialchars_decode($body));  
		//jangan dibuka buat graphic ntar 
		//$this->email->attach('tmp_report/graphics/imagegraph1.jpg', 'inline');
		//$this->email->message($body."<img src='cid:imagegraph1.jpg' />");
		
		$this->email->message($body);
		
		//====== ini ngirim attachment 
		if ($attach === 'Y'){
			$this->email->attach($file);
		}

        if($this->email->send()){
			return TRUE;
			$this->email->print_debugger();
        }else{
			show_error($this->email->print_debugger());
        }
		
	}
	
	function call_graph(){
		return $this->load->view('graph_img');
	}
	
	function tampilgrafik($title, $dataX, $axisX){
        $this->load->library('jpgraph');
       
        $bar_graph = $this->jpgraph->barchart();
   
		$datay1 = array(20,15,23,15);
		$datay2 = array(12,9,42,8);
		$datay3 = array(5,17,32,24);
		$capM=array('01-02-015','02-02-015','03-02-015','04-02-015','05-02-015','06-02-015');
		$capY = array(10,20,30,40,50,60);
		
		// Setup the graph
		$graph = new Graph(600,500);
		$graph->SetScale("textlin");
		$graph->xaxis->SetTickLabels($axisX);
		//$graph->yaxis->SetTickLabels($capY);

		$theme_class=new UniversalTheme;

		$graph->SetTheme($theme_class);
		$graph->img->SetAntiAliasing(false);
		$graph->title->Set($title);
		$graph->SetBox(false);

		$graph->img->SetAntiAliasing();

		$graph->yaxis->HideZeroLabel();
		$graph->yaxis->HideLine(false);
		$graph->yaxis->HideTicks(false,false);

		$graph->xgrid->Show();
		$graph->xgrid->SetLineStyle("solid");
		$graph->xaxis->SetTickLabels($axisX);
		$graph->xgrid->SetColor('#E3E3E3');

		// Create the first line
		$p1 = new LinePlot($dataX);
		$graph->Add($p1);
		$p1->SetColor("#6495ED");
		//$p1->SetLegend("legend parameter");

		// Create the second line
		//$p2 = new LinePlot($datay2);
		//$graph->Add($p2);
		//$p2->SetColor("#B22222");
		//$p2->SetLegend('Line 2');

		// Create the third line
		//$p3 = new LinePlot($datay3);
		//$graph->Add($p3);
		//$p3->SetColor("#FF1493");
		//$p3->SetLegend('Line 3');

		$graph->legend->SetFrameWeight(1);

		// Output line
		$graph->Stroke('tmp_report/graphics/imagegraph1.jpg'); //save to file
		//$graph->Stroke();
    }
	
	public function test(){
		shell_exec('sh /home/oracle/AA.sh');
	}
}