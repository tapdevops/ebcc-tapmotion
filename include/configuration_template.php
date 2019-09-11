<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
if (!function_exists('json_decode')) {
    require_once 'json.php';
}

class Configuration_template extends CI_Controller {

    public $session_plantid;
    public $session_company;
    public $session_document;
	public $session_purchaseorg;
    public $access;
    public $navigation;
    public $pageNow;
    public $exception;
    public $totalrownumber = 3;

    public function __construct() {
        parent::__construct();
        //set global css
        $this->css = array('bootstrap.min', 'style');
        //set global js		
        $this->js = array('jquery', 'bootstrap.min', 'global_function', 'jquery-ui.min');
        $this->per_page = 10;
        $this->per_page_search = 10;
        $this->loadCss($this->css);
        $this->loadJs($this->js);
		//$this->load->library('encrypt');
        list($navigation, $accessMenu) = $this->user_app->get_navigation($this->session->userdata('USR_ORGSTRU'));		
        $this->navigation = $navigation;
        $this->access = $accessMenu;		
        $this->pageNow = $this->uri->segment(1);
        $this->exception = array('group_access_document' => 'group_access_doc', 'master_department' => 'department');
        if ($this->session->userdata('username')) {
            $this->session_plantid = $this->user_app->get_all_user_plant('*', " UPPER(AUA_IDADNAME) like '" . strtoupper($this->session->userdata('username')) . "'", 'idonly');
            $this->session_document = $this->user_app->get_all_document('*', 'ACD_GADID LIKE \'' . $this->session->userdata('USR_GRADOC') . '\'');
			$this->session_purchaseorg = $this->user_app->get_purchasing_org('*','UPG_USRID LIKE \''.$this->session->userdata('user_id').'\'');
		}
		
    }

    public function loadCss($css) {
        foreach ($css as $css_style) {
            $config_css = $css_style . '.css';
            $this->template->add_css($config_css);
        }
    }

    public function loadJs($js) {
        foreach ($js as $js_style) {
            $config_js = $js_style . '.js';
            $this->template->add_js($config_js);
        }
    }

    public function set_template($page, $attribute = NULL) {
        $attribute['status'] = $this->set_default_status();
		$attribute['status_gad'] = $this->set_default_status_gad();
        // list($navigation, $access) = $this->user_app->get_navigation($this->session->userdata('USR_ORGSTRU')); 
        $attribute['navi'] = $this->navigation;
        //$attribute['exception'] = array('group_access_document'=>'group_access_doc','master_department'=>'department');
        $attribute['exception'] = $this->exception;

        $condition = " UPPER(AUA_IDADNAME) like '" . strtoupper($this->session->userdata('username')) . "' AND USR_STATUS LIKE 'E'";
        $attribute['loginas_list'] = $this->user_app->login_as('*', $condition);
        $this->template->write_view('header', 'include/header', $attribute);
        $this->template->write_view('navigation', 'include/navigation', $attribute);
        $this->template->write('title', $attribute['title']);
        $this->template->write_view('content', $page, $attribute);
        $this->template->write_view('footer', 'include/footer', $attribute);
        $this->template->render();
    }

    public function set_template_login($page, $attribute = NULL) {
        $this->template->write_view('header', 'include/header', $attribute);
        $this->template->write('title', $attribute['title']);
        $this->template->write_view('content', $page, $attribute);
        $this->template->write_view('footer', 'include/footer', $attribute);
        $this->template->render();
    }

    public function set_content_only($page, $attribute = NULL) {
        $this->load->view($page, $attribute);
    }

    public function checkUserValidation() {
        try {
            $tampung = array();
            foreach ($this->access as $menu) {
                $name = str_replace(" ", "_", strtolower($menu));
                $tampung[] = $name;
                if (!empty($this->exception[$name])) {
                    $tampung[] = $this->exception[$name];
                }
            }
            if (!in_array($this->pageNow, $tampung)) {
                $valid = false;
               //$this->session->sess_destroy();
            } else {
                $valid = $this->session->userdata('is_login');
            }
            $valid = $this->session->userdata('is_login');
            if (!isset($valid) || $valid != true) {
            	if (strrpos($_SERVER['REQUEST_URI'], $this->config->item('index_page')) > 0) {
            	$url_request = substr($_SERVER['REQUEST_URI'],strrpos($_SERVER['REQUEST_URI'], $this->config->item('index_page')) + strlen($this->config->item('index_page')) +1,strlen($_SERVER['REQUEST_URI'])); 
            	$user_info = array('redirect_back' => $url_request);
                $this->session->set_userdata($user_info); 
            	}
            	redirect('', 'refresh');
                //show_error('<div align="center">You don\'t have permission to access this page without login.</div> <br>' . anchor('', '<div align="center">Back to home page</div>') . '', 403);
            }
        } catch (Exception $e) {
            show_error('Your error message', 500);
        }
    }

    public function MsgHTML($message, $basedir = '') {
        preg_match_all("/(src|background)=[\"'](.*)[\"']/Ui", $message, $images);
        if(isset($images[2])) {
            foreach($images[2] as $i => $url) {
            // do not change urls for absolute images (thanks to corvuscorax)
                if (!preg_match('#^[A-z]+://#', $url)) {
                    $filename = basename($url);
                    $directory = dirname($url);
                    ($directory == '.') ? $directory='': '';
                    //$cid = 'cid:' . md5($filename);
                    //$ext = pathinfo($filename, PATHINFO_EXTENSION);
                    //$mimeType  = self::_mime_types($ext);
                    if ( strlen($basedir) > 1 && substr($basedir, -1) != '/') { $basedir .= '/'; }
                    if ( strlen($directory) > 1 && substr($directory, -1) != '/') { $directory .= '/'; }
                    if ( $this->AddEmbeddedImage($basedir.$directory.$filename, md5($filename), $filename, 'base64', $mimeType) ) {
                        $message = preg_replace("/".$images[1][$i]."=[\"']".preg_quote($url, '/')."[\"']/Ui", $images[1][$i]."=\"".$cid."\"", $message);
                    }
                }
            }
        }
        $this->IsHTML(true);
        $this->Body = $message;
	if (empty($this->AltBody)) {
		$textMsg = trim(strip_tags(preg_replace('/<(head|title|style|script)[^>]*>.*?<\/\\1>/s', '', $message)));
		if (!empty($textMsg)) {
			$this->AltBody = html_entity_decode($textMsg, ENT_QUOTES, $this->CharSet);
		}
	}
        if (empty($this->AltBody)) {
            $this->AltBody = 'To view this email message, open it in a program that understands HTML!' . "\n\n";
        }
	return $message;
    }

    
    public function notification_email($username = '', $subject = '', $sendto = array(), $from = '', $details = array(),$sendtoUserID = array()) {
        $this->load->library('mail');        
        $message = array('PR Submission' => '1', 'PR Approval' => '2', 'PR Updated' => '3');		
		$scconfig["ipmailserver"] = 'smtp.tap-agri.com';
		$scconfig["portsmtp"] = '25';
		$toInfo = implode(",",$sendto);
		$content = "";
		$details['HEADER']['TO'] = array();
			for($a=0;$a < count($sendto);$a++) {
				if ($sendto[$a]!="") {
				$tmpname = $this->manage_user_app->get_fullname_by_email($sendto[$a]);	
				$details['HEADER']['TO'][] = $tmpname["AUA_ADFULLNAME"];
				}
			}
		//$sendto = array("agnies.bahrul@tap-agri.co.id");
		$sendto = array("zulmy.taufik@tap-agri.com","ridzky.putra@tap-agri.com","achdan.risnandar@tap-agri.com","adam.rachman@tap-agri.com","agnies.bahrul@tap-agri.co.id");			
		// echo '<pre>';
		// print_r($details);
		// echo '</pre>';
		// exit();
		$app_full_approve = $this->pr_approval_app->getSingleRowQuery("SELECT COUNT(*) AS CNT FROM APPROVAL_PR WHERE APP_PRNUMBER = '" . $details['HEADER']['PRH_PRNUMBER'] . "' AND UPPER(APP_STATUS) IN('REJECT','ASKMORE','REVISE','WAITING') ");
		$app_full_reject = $this->pr_approval_app->getSingleRowQuery("SELECT COUNT(*) AS CNT  FROM APPROVAL_PR WHERE APP_PRNUMBER = '" . $details['HEADER']['PRH_PRNUMBER'] . "' AND UPPER(APP_STATUS) IN('REJECT') ");
		
		if ($app_full_reject["CNT"] > 0) $full_status = "REJECT";
		else if ($app_full_approve["CNT"] == 0) $full_status = "FULL APPROVE";
		else $full_status = "APPROVAL";
		
        if ($subject == 'PR Submission') {		
        	if (($subject == 'PR Submission') && ((strtoupper($details['HEADER']['APP_STATUS_OLD'])=="REVISE") || (strtoupper($details['HEADER']['APP_STATUS_OLD'])=="ASKMORE"))) {
			$subject = 'PRC-OL [INFORMASI TELAH DIREVISI - PR/SPR '.$details['HEADER']['PRH_PRNUMBER'].']';
        	} else {
			$subject = 'PRC-OL [PERMOHONAN PERSETUJUAN - NO PR/SPR '.$details['HEADER']['PRH_PRNUMBER'].']';        		
        	}
            $content = $this->PR_request_notification($details, 'buat',$sendtoUserID,$username,$full_status);
        } else if ($subject == 'PR Approval') {
			$subject = 'PRC-OL [PERMOHONAN PERSETUJUAN - PR/SPR '.$details['HEADER']['PRH_PRNUMBER'].']';
            $content = $this->PR_request_notification($details,'approve',$sendtoUserID,$username,$full_status);
        } else if ($subject == 'PR Updated') {
        	if ((strtoupper($details['HEADER']['APP_STATUS_OLD'])=="REVISE") || (strtoupper($details['HEADER']['APP_STATUS_OLD'])=="ASKMORE")) {
				$subject = 'PRC-OL [INFORMASI TELAH DIREVISI - PR/SPR '.$details['HEADER']['PRH_PRNUMBER'].']';
        	} else if(strtoupper($details['HEADER']['STATUS'])=="REVISE"){
				$subject = 'PRC-OL [INFORMASI REVISI - PR/SPR '.$details['HEADER']['PRH_PRNUMBER'].']';
			} else if(strtoupper($details['HEADER']['STATUS'])=="REJECT"){
				$subject = 'PRC-OL [INFORMASI REJECT - PR/SPR '.$details['HEADER']['PRH_PRNUMBER'].']';
			} else if(strtoupper($details['HEADER']['STATUS'])=="ASKMORE"){
				$subject = 'PRC-OL [INFORMASI PENAMBAHAN DATA - PR/SPR '.$details['HEADER']['PRH_PRNUMBER'].']';
			} else if ($full_status=="FULL APPROVE") {
				$subject = 'PRC-OL [INFORMASI PERSETUJUAN LENGKAP - PR/SPR '.$details['HEADER']['PRH_PRNUMBER'].']';	
			}else{
				$subject = 'PRC-OL [INFORMASI PERSETUJUAN - NO PR/SPR '.$details['HEADER']['PRH_PRNUMBER'].']'; 
			}
            $content = $this->PR_request_notification($details, 'ubah',$sendtoUserID,$username,$full_status);
        }
		$content .= " To : " . $toInfo;
		//$subject .= " (" . $details['HEADER']['SUBJECT'] . ")"; 
		$this->mail->ClearAddresses();  // each AddAddress add to list
		$this->mail->ClearCCs();
		$this->mail->ClearBCCs();
		$this->mail->ClearReplyTos();
		$this->mail->ClearAllRecipients();	
		$this->mail->ClearAttachments();
		$this->mail->ClearCustomHeaders();	
		$this->mail->sendmail($from,implode(",",$sendto),$subject,$content,$scconfig);	
		//sleep(1);	
		//print(implode(",",$sendto) ."<br>");
		//print_r($this->mail);

		
    }


  

    public function PR_request_notification($details, $pesan,$sendtoUserID,$username = '',$full_status = "APPROVAL") {
	$notifStatus = array("A" => "Approve","R" => "Reject","W" => "Wait");
	
		$ContentHeader = $this->pr_approval_app->getSingleRowQuery("SELECT * FROM PR_HEADER WHERE PRH_PRNUMBER = '" . $details['HEADER']['PRH_PRNUMBER'] . "' ");

        //$condition_participant = " APP_PRNUMBER = '" . $details['HEADER']['PRH_PRNUMBER'] . "' ";
        //$this->pr_approval_app->order = 'APP_LEVEL,APP_ID';
        //$return = $this->pr_approval_app->get_all_user_participant2("*", $condition_participant);
        //$this->pr_approval_app->order = '1';		
        
		if (!empty($details['CONTENT'])) {
		$app_current = $this->pr_approval_app->get_approval('*', ' APP_ID = \'' . $details['HEADER']['APPROVAL_ID'] . '\' ', 'GetRow');
			
		$htmlAppPath = "";

        $fields = array('DISTINCT APP_USERAPPROVAL', 'APO_DESCRIPTION',
            'APP_STAGE AS WFD_STAGE', 'APP_LEVEL AS WFD_LEVEL', 'AUA_IDADNAME AS PAR_USER', 'APP_APPROVALMODEL AS PAR_APPROVALMODEL',
            'APP_ATPID AS WFD_ATPID', 'APP_AMOUNTTO AS WFD_AMOUNTTO', 'APP_AMOUNTFROM AS WFD_AMOUNTFROM','APP_ID','APP_APPROVEDATE','APP_USERUPDATED','APP_TYPE AS WFD_TYPE'
        );
        $condition_participant = " APP_PRNUMBER = '" . $details['HEADER']['PRH_PRNUMBER'] . "' ";
        $this->pr_approval_app->order = 'APP_LEVEL,APP_ID';
        $get_participants = $this->pr_approval_app->get_all_user_participant2(implode(", ", $fields), $condition_participant);
        $this->pr_approval_app->order = '1';

        $htmlAppPath = '<table border="0"  cellpadding="1" cellspacing="0" style="font-family:arial;border-collapse: collapse;padding: 0pt;border:0px solid #E5E6E7;font-size: 8px;" id="tbl_release_strategy">';
        $htmlAppPath.= '<tr><th><b>No</b></th><th><b>Full Name</b></th><th><b>Approval Status</b></th><th><b>Action By</b></th><th><b>Action Date</b></th></tr>';

		$addName = array();
		$no=0;
        if (!empty($get_participants)) {
            foreach ($get_participants as $key_part => $val_part) {
            $this->pr_approval_app->order = 'APP_LEVEL';
            $app_status = $this->pr_approval_app->get_approval('*', 'APP_STAGE = \'' . $val_part['WFD_STAGE'] . '\' AND APP_PRNUMBER = \'' . $details['HEADER']['PRH_PRNUMBER'] . '\' AND APP_USERAPPROVAL = \'' . $val_part['PAR_USER'] . '\'', 'GetRow');
            $status = (!empty($app_status['APP_STATUS'])) ? $app_status['APP_STATUS'] : 'WAITING';
            $val_part['PAR_BYSTRUCTURE'] = 0;

                if ($val_part['PAR_BYSTRUCTURE'] > 0) {
                    $name = $this->pr_approval_app->get_assigned_by_structure($val_part['PAR_BYSTRUCTURE'], $this->session->userdata('USR_ORGSTRU'));
                    $name['AUA_ADFULLNAME'] = (!empty($name['AUA_ADFULLNAME'])) ? $name['AUA_ADFULLNAME'] : "";
                } else {
                    $this->pr_approval_app->order = '1';
                    $name = $this->pr_approval_app->get_approval('AUA_ADFULLNAME', 'AUA_IDADNAME like \'' . $val_part['PAR_USER'] . '\'', 'GetRow', 'APP_USER_AD');
                }
                if (empty($val_part['APP_APPROVEDATE'])) $val_part['APP_APPROVEDATE'] = "";
                if (!empty($val_part['APP_USERUPDATED'])) {
                $this->pr_approval_app->order = '1';
                $tmpname = $this->pr_approval_app->get_approval('AUA_ADFULLNAME', 'AUA_IDADNAME like \'' . $val_part['APP_USERUPDATED'] . '\'', 'GetRow', 'APP_USER_AD');
				$val_part['APP_USERUPDATED'] = $tmpname['AUA_ADFULLNAME'];
                } else $val_part['APP_USERUPDATED'] = ''; 
                
            	if (!in_array ($name['AUA_ADFULLNAME'], $addName)) {  
            	$no++;   
                array_push ($addName,$name['AUA_ADFULLNAME']);           
                $htmlAppPath.='<tr><td nowrap>' . $no . '. </td><td nowrap>' . $name['AUA_ADFULLNAME'] . '</td><td nowrap>&nbsp;&nbsp;' . ucfirst(strtolower($status)) . '</td><td nowrap>&nbsp;&nbsp;' . $val_part['APP_USERUPDATED'] . '</td><td nowrap>&nbsp;&nbsp;' . $val_part['APP_APPROVEDATE'] . '</td></tr>';
	                if ($status=='WAITING') { 
	                $getDelegation = $this->manage_user_app->get_delegation(date('Y-m-d'), $val_part['PAR_USER']);
		                if (!empty($getDelegation)) {
		                    foreach ($getDelegation as $key => $val) {
		                    	$no++; 		                    	
		                        $htmlAppPath.='<tr><td nowrap >' . $no . '. </td><td nowrap>Delegate to : ' . $val['APD_DELEGATETO'] . '</td><td nowrap>&nbsp;&nbsp;' . ucfirst(strtolower($status)) . '</td><td nowrap>&nbsp;</td><td nowrap>&nbsp;</td></tr>';
		                    }
		                }
	                }
            	}
            }
        } else {
            $htmlAppPath.='<tr><td colspan="4" class="text-center">No user</td></tr>';
        }
        $htmlAppPath.='</table>';

    			
			$totalAmount = 0;
			$item = "";
            foreach ($details['CONTENT'] as $id) {
			$infoMaterial = $this->material_app->get_material_pr_item('*'," PRI_PRNUMBER='" . $details['HEADER']['PRH_PRNUMBER'] . "' AND PRI_ITEMNO = '" . $id['PRI_ITEMNO'] . "'  ",'GetRow');
			$infoItemText3Bln  = $this->pr_submission_app->get_list_pr_itemtext('*',"  PIT_PRNUMBER='" . $details['HEADER']['PRH_PRNUMBER'] . "' AND PIT_PRITEMNO = '" . $id['PRI_ITEMNO']  . "' AND PIT_IMTID = 1006 ", 'GetRow');
			$infoItemTextNote  = $this->pr_submission_app->get_list_pr_itemtext('*',"  PIT_PRNUMBER='" . $details['HEADER']['PRH_PRNUMBER'] . "' AND PIT_PRITEMNO = '" . $id['PRI_ITEMNO']  . "' AND PIT_IMTID = 1002 ", 'GetRow');
			
			//if (strtoupper($infoMaterial['PRI_APPROVESTATUS'])!="R") 
			$totalAmount += $infoMaterial['PRI_TOTALVALUE'];
			
				if ($infoMaterial['MAT_SHORTDESC']!="") {
				$item.= "<tr>";
				$item.= "<td>" . $id['PRI_ITEMNO'] . "</td>";
				$item.= "<td>" . ucfirst(strtolower($infoMaterial['MAT_SHORTDESC'])) . " " . ucfirst(strtolower($infoMaterial["MAT_LONGDESC"])) . " " . ucfirst(strtolower($infoMaterial["PIT_ITEMTEXT"])) . "</td>";
				$item.= "<td>" . $infoMaterial['MAT_UOM']. "</td>";
				$item.= "<td style='text-align:right;'>" . number_format($infoMaterial['PRI_QUANTITY'],2) . "</td>";
				$item.= "<td style='text-align:right;'>" . number_format($infoMaterial['PRI_TOTALVALUE']) . "</td>"; 
				$item.= "<td >." .  $infoItemTextNote["PIT_ITEMTEXT"] . "</td>"; 
				$item.= "<td >." .  $infoItemText3Bln["PIT_ITEMTEXT"] . " </td>"; 
				$item.= "<td style='text-align:right;'><strong>" .  $notifStatus[$infoMaterial["PRI_APPROVESTATUS"]] . "</strong></td>"; 
				$item.= "</tr>";
				} else {
				$this->pr_submission_app->order = 'PRS_PRITEMNO,PRS_ITEMNO';											
				$itemService = $this->pr_submission_app->get_list_pr_services('*', " PRS_PRNUMBER = '" . $details['HEADER']['PRH_PRNUMBER'] . "' AND PRS_PRITEMNO = '" . $id['PRI_ITEMNO']  . "' ",'GetAll');
				$this->pr_submission_app->order = '1';											
					foreach ($itemService as $key => $itemServiceValue) {					
					$item.= "<tr>";
					$item.= "<td>" . $id['PRI_ITEMNO'] . "." . $itemServiceValue["PRS_ITEMNO"] . "</td>";
					$item.= "<td>" . ucfirst(strtolower($itemServiceValue["PRS_SCVDESC"])) . " - " . ucfirst(strtolower($infoMaterial['PRI_ITEMDESC'])) . "</td>";
					$item.= "<td>" . $itemServiceValue['PRS_UNIT']. "</td>";					
					$item.= "<td style='text-align:right;'>" . number_format($itemServiceValue['PRS_QUANTITY'],2) . "</td>";
					$item.= "<td style='text-align:right;'>" . number_format(($itemServiceValue['PRS_QUANTITY']*$itemServiceValue['PRS_GROSSPRICE'])) . "</td>"; 
					$item.= "<td >&nbsp;" .  $infoItemTextNote["PIT_ITEMTEXT"] . "</td>"; 
					$item.= "<td >&nbsp;" .  $infoItemText3Bln["PIT_ITEMTEXT"] . " </td>"; 
					$item.= "<td style='text-align:right;'><strong>" .  $notifStatus[$infoMaterial["PRI_APPROVESTATUS"]] . "</strong></td>"; 
					$item.= "</tr>";					
					}
				}
            }
        } else {
            $item = "<tr><td colspan='4' style='text-align:center'>No Item</td></tr>";
        }
		
		$listAttach = "";
		$attachment = $this->pr_submission_app->get_list_attachment('*', 'PAT_PRNUMBER = \'' . $details['HEADER']['PRH_PRNUMBER'] . '\'');
			if(!empty($attachment)){
                foreach($attachment as $key=>$valAttach){
                $idAttachment = $valAttach['PAT_PRNUMBER'].'/'.$valAttach['PAT_SEQID'];
				$listAttach .= "<li><a href='" . BASEURL_S."pr_approval/load_file/".$valAttach['PAT_PRNUMBER']."/".$valAttach['PAT_SEQID']."' target='_blank' title='" . $valAttach['PAT_FILENAME'] . "' >" . $valAttach['PAT_FILENAME'] . "</a></li>";
				}
			}
		if ($listAttach!="") $listAttach = "<b>Lampiran : </b><ul>" . $listAttach . "</ul>";
        //$status = "APPROVE";
        //document Type itu ada D sama '' (kosong)
        //$documentType = "D";
        //tinggal di pake terus masuk"in data"nya sesuai namanya        
        //$url_approve = BASEURL_S."pr_approval/".$details['PRH_PRNUMBER']."/".$details['APPROVAL_ID']."/".$documentType."/".$status."";
		//print(phpinfo());
		//print('1312312' . $this->encode($details['HEADER']['PRH_PRNUMBER']) . ' '   );
		$PRH_PRNUMBER = $this->encode($details['HEADER']['PRH_PRNUMBER']);
        $APPROVAL_ID = $this->encode($details['HEADER']['APPROVAL_ID']);
        $PRH_DTEID = $this->encode($details['HEADER']['PRH_DTEID']);
        if (count($sendtoUserID) > 0) $APPUSERID = $this->encode($sendtoUserID[0]);


		
        $approve = "<a href='".BASEURL_S."pr_approval/direct_insert_email/".$PRH_PRNUMBER."/".$APPROVAL_ID."/".$PRH_DTEID."/APPROVE/" . $APPUSERID . "' target='_blank'>APPROVE</a>";
        $rejected = "<a href='".BASEURL_S."pr_approval/direct_insert_email/".$PRH_PRNUMBER."/".$APPROVAL_ID."/".$PRH_DTEID."/REJECT/" . $APPUSERID . "' target='_blank'>REJECT</a>";//(Uda gw bikinin templatenya tinggal masuk-masukin datanya)
        $askmore = "";//"<a href='".BASEURL_S."pr_approval/direct_insert_email/".$PRH_PRNUMBER."/".$APPROVAL_ID."/".$PRH_DTEID."/ASKMORE/" . $APPUSERID . "' target='_blank'>ASK MORE</a>";//(Uda gw bikinin templatenya tinggal masuk-masukin datanya)
		$view_revisi = "<a href='".BASEURL_S."pr_submission/edit/".$details['HEADER']['PRH_PRNUMBER']."/1' target='_blank' style='color:black'>Action</a>";
		$view_app = "<a href='".BASEURL_S."pr_approval/view/".$details['HEADER']['PRH_PRNUMBER']."/".$details['HEADER']['APPROVAL_ID']."/".$app_current["APP_ATPID"]."/1' target='_blank' style='color:black'>Detail</a>";
		
        $html = "<table style='font-family:arial;font-size: 10px;'>";
        //$html .= "<tr><td colspan='4'>PR/SPR Telah <b>" . $details['HEADER']['STATUS'] . "</b> </td></tr>";
        //$html .= "<tr><td colspan='4'>&nbsp;</td></tr>";
		$html .= "<tr><td colspan='4' style='font-family:arial;font-size: 12px;'><b>";
		
		if (($pesan == "ubah") && (strtoupper($details['HEADER']['STATUS'])=="REVISE")) $html .= "INFORMASI PERMOHONAN REVISI PENGADAAN BARANG ATAU JASA";
		else if (($pesan == "ubah") && (strtoupper($details['HEADER']['STATUS'])=="ASKMORE")) $html .= "INFORMASI PERMOHONAN PENAMBAHAN DATA PENGADAAN BARANG ATAU JASA";
		else if (($pesan == "ubah") && (strtoupper($full_status)=="REJECT")) $html .= "INFORMASI REJECT PENGADAAN BARANG ATAU JASA";
		else if (($pesan == "ubah") && (strtoupper($full_status)=="FULL APPROVE")) $html .= "INFORMASI PENGADAAN BARANG ATAU JASA TELAH LENGKAP";
		else if ((($pesan=="buat") || ($pesan=="ubah")) && (strtoupper($details['HEADER']['APP_STATUS_OLD'])=="REVISE")) $html .= "INFORMASI TELAH DI REVISI PENGADAAN BARANG ATAU JASA";
		else if ((($pesan=="buat") || ($pesan=="ubah")) &&  (strtoupper($details['HEADER']['APP_STATUS_OLD'])=="ASKMORE")) $html .= "INFORMASI DATA TELAH DI TAMBAH PENGADAAN BARANG ATAU JASA";
		else if ($pesan=="ubah")  $html .= "INFORMASI PERSETUJUAN PENGADAAN BARANG ATAU JASA";
		else $html .= "PERMOHONAN PERSETUJUAN PENGADAAN BARANG ATAU JASA";
		$html .= "</b><br>
				Procurement On-Line PT. Triputra Agro Persada<br><br>
				Kepada Yth., <Br>
				" . implode(",",$details['HEADER']['TO']) . " <br><br>";
        if (($pesan == "buat") || ($pesan == "approve")) {
        	
        	if (($pesan=="buat") && ((strtoupper($details['HEADER']['APP_STATUS_OLD'])=="REVISE") || (strtoupper($details['HEADER']['APP_STATUS_OLD'])=="ASKMORE"))) {
	        $html .= "Kami informasikan bahwa dokumen ini telah <b>direvisi</b> : </td></tr>";
        	} else {
    	    $html .= "Dibutuhkan <b>persetujuan</b> atas dokumen berikut: </td></tr>";		
        	}
				//140506 PT TRIPUTRA AGRO PERSADA <br><br>
				//140506 Bersama dengan E-mail ini, kami sampaikan informasi saat ini ada dokumen yang perlu di setujui dengan detail sebagai berikut :&nbsp;<br><br>
        }  else {

        	if (($pesan=="ubah") && ((strtoupper($details['HEADER']['APP_STATUS_OLD'])=="REVISE") || (strtoupper($details['HEADER']['APP_STATUS_OLD'])=="ASKMORE"))) {
	        $html .= "Kami informasikan bahwa dokumen ini telah <b>direvisi</b> : </td></tr>";
        	} else if (($pesan == "ubah") && (strtoupper($details['HEADER']['STATUS'])=="REVISE"))   {
			$html .= "Dibutuhkan <b>revisi</b> atas dokumen berikut :<br><br></td></tr>";	
			} else if (($pesan == "ubah") && (strtoupper($details['HEADER']['STATUS'])=="ASKMORE")) {
			$html .= "Dibutuhkan <b>lampiran data</b> atas dokumen – dokumen berikut : </td></tr>";        							
			} else if (($pesan == "ubah") && (strtoupper($full_status)=="REJECT"))   {
			$html .= "Kami informasikan bahwa dokumen ini telah <b>di Reject </b> oleh " . ucwords($username)  . " :<br><br></td></tr>";						
			} else if (($pesan == "ubah") && (strtoupper($full_status)=="FULL APPROVE"))   {
			$html .= "Kami informasikan bahwa dokumen berikut telah lengkap disetujui :<br><br></td></tr>";				
			} else {	
			$html .= "Kami informasikan status persetujuan dokumen dengan detail sebagai berikut :<br><br></td></tr>";						
			}
			
                	
        }      
        
        $LastPRLog = $this->pr_approval_app->getSingleRowQuery("SELECT APL_NOTES  FROM APPROVAL_PR_LOG WHERE APL_PRNUMBER='" . $details['HEADER']['PRH_PRNUMBER'] . "' AND ROWNUM = 1 ORDER BY APL_SEQNO DESC  ");        
        
        
        $html .= "<tr><td colspan='4'>&nbsp;</td></tr>" .
				"<tr><td><table>".
					"<tr style='font-family:arial;font-size: 12px;'><td>No PR.</td><td>:</td><td style='text-align:left;'>" . $details['HEADER']['PRH_PRNUMBER'] . "</td><td>&nbsp;</td></tr>" .
					"<tr style='font-family:arial;font-size: 12px;'><td>Doc. Type</td><td>:</td><td style='text-align:left;'>" . $details['HEADER']['PRH_DTESHORTDESC'] . "</td><td>&nbsp;</td></tr>" .
					"<tr style='font-family:arial;font-size: 12px;'><td>Company</td><td>:</td><td style='text-align:left;'>" . $details['HEADER']['COM_NAME'] . "</td><td>&nbsp;</td></tr>" .
					"<tr style='font-family:arial;font-size: 12px;'><td>Total Value</td><td>:</td><td style='text-align:left;'>Rp " . number_format($totalAmount) . "</td><td>&nbsp;</td></tr>" .
					"<tr style='font-family:arial;font-size: 12px;'><td>Created By</td><td>:</td><td style='text-align:left;'>" . ucwords($this->pr_approval_app->get_name_by_user_id($ContentHeader["PRH_USERCREATED"])) . "</td><td>&nbsp;</td></tr>";
		if (($pesan == "ubah") && (strtoupper($full_status)=="REJECT")) $html .= "<tr style='font-family:arial;font-size: 12px;'><td>Prev Approval</td><td>:</td><td style='text-align:left;'>" . ucwords($username) . "</td><td>&nbsp;</td></tr>";
		$html .= "</table></td></tr>".
                "<tr><td colspan='4'>&nbsp;</td></tr>" .
                "<tr><td colspan='4'>&nbsp;</td></tr>" .
                "<tr><td colspan='4' width='950'><Br>" .
                "<table border='1' width='100%' cellpadding='2' cellspacing='0' style='font-family:arial;border-collapse: collapse;padding: 2pt;border:1px solid #E5E6E7;font-size: 10px;'>" .
                "<tr style='text-align:center'>
                    <td><strong>No</strong></td>
                    <td><strong>PR Item</strong></td>
                    <td><strong>UOM</strong></td>
                    <td><strong>Qty</strong></td>
                    <td><strong>Value (Rp)</strong></td><td><strong>Item Note</strong></td><td><strong>Pemakaian 3 bulan terakhir</strong></td>
                    <td><strong>Status</strong></td>
				</tr>" .
                $item .
                "</table>" .
                "</td></tr>" .
                "<tr><td colspan='4'>&nbsp;</td></tr>";
	                if (($pesan == "ubah") && ((strtoupper($details['HEADER']['STATUS'])=="REVISE") || (strtoupper($details['HEADER']['STATUS'])=="ASKMORE")))   {
	                $html .= "<tr><td colspan='3'>Catatan : <strong style='font-size:12px'><i>". $LastPRLog["APL_NOTES"] . "</i></strong></td></tr>";	   
	                $html .= "<tr><td colspan='3'><strong style='font-size:12px'>".$view_revisi. "</strong></td></tr>";	                	
	                } else if ($pesan == "ubah") {
	                	if ($full_status=="REJECT") {
	                	$html .= "<tr><td colspan='3'><strong style='font-size:12px'>Catatan : <strong style='font-size:12px'><i>". $LastPRLog["APL_NOTES"] . "</i></strong></td></tr>";	   	                		
	                	}	                
	                } else {
	                //if (($pesan == "buat") && ($LastPRLog["APL_NOTES"]!="")) $html .= "<tr><td colspan='3'><strong style='font-size:12px'>Catatan : ". $LastPRLog["APL_NOTES"] . "</strong></td></tr>";	  	
	                if (($pesan == "buat") || ($pesan == "approve")) $html .= "<tr><td colspan='3'><strong style='font-size:12px'>".$approve." &nbsp;&nbsp;&nbsp; ".$rejected." &nbsp;&nbsp;&nbsp; " . $view_app . "</strong></td></tr>";
	                }
                $html .= "</table>";
				if (($pesan == "buat") || ($pesan == "approve")) $html .= "<br><Br><font style='font-family:arial;font-size: 10px;'>" . $listAttach . "</font>";
				
				$html .= "<font style='font-family:arial;font-size: 12px;'>";
				if (($pesan == "buat") || ($pesan == "approve")) {
				//$html .= "<br>Atas perhatiannya kami ucapkan terima kasih,<br><br>";
				} else {
				//$html .= "<br>Telah di" . strtolower($details['HEADER']['STATUS'])  . " oleh " . $details['HEADER']['AUA_ADFULLNAME'] . "<br><br>";				
    			}
				$html .= "<table>";
				 if (($pesan == "ubah") && (strtoupper($full_status)=="REJECT")) {
				$html .= "<tr><td colspan='7' style='font-family:arial;font-size: 10px;'>Email ini dihasilkan otomatis dari sistem Procurement On-Line PT. Triputra Agro Persada<br>
					Mohon tidak melakukan reply.</td></tr>
					<tr><td colspan='7' style='font-family:arial;font-size: 11px;'>";				 	
				 }  else { 
				 	if (($pesan == "ubah") && ((strtoupper($details['HEADER']['STATUS'])=="REVISE") || (strtoupper($details['HEADER']['STATUS'])=="ASKMORE"))) {
					$html .= "<tr><td colspan='7' style='font-family:arial;font-size: 10px;'>Email ini dihasilkan otomatis dari sistem Procurement On-Line PT. Triputra Agro Persada<br>
						Mohon tidak melakukan reply.</td></tr>
						<tr><td colspan='7' style='font-family:arial;font-size: 11px;'>";				 					 		
				 	}
				 	$html .= "<tr><td colspan='7' style='font-family:arial;font-size: 11px;'><hr><b>Daftar Penyetuju      	:</b><br>" . $htmlAppPath . "<hr>";
				 }
				$html .= "	</td></tr>";
				 if (($pesan == "buat") || ($pesan == "approve")) {					
					$html .= "<tr  style='font-family:arial;font-size: 11px;'><td><b>CATATAN PENTING</b></td></tr>
					<tr  style='font-family:arial;font-size: 11px;'><td>Untuk:</td></tr>";
				 }
					$html .= "</table>";
				 if (($pesan == "buat") || ($pesan == "approve")) {	
				$html .= "	<table>
						<tr  style='font-family:arial;font-size: 11px;'><td width='10' align='right'>1.</td><td>Melakukan Persetujuan secara Parsial</td></tr>
						<tr  style='font-family:arial;font-size: 11px;'><td width='10' align='right'>2.</td><td>Melihat sejarah dokumen</td></tr>
						<tr  style='font-family:arial;font-size: 11px;'><td width='10' align='right'>3.</td><td>Mengurangi jumlah barang/ jasa yang diajukan</td></tr>
						<tr  style='font-family:arial;font-size: 11px;'><td width='10' align='right'>4.</td><td>Pengajuan revisi ke pembuat dokumen</td></tr>
						<tr  style='font-family:arial;font-size: 11px;'><td width='10' align='right'>5.</td><td>Permohonan penambahan data pendukung ke pembuat dokumen</td></tr>
						<tr  style='font-family:arial;font-size: 11px;'><td colspan=2>dapat dilakukan dengan memilih action " . $view_app . "<br><br></td></tr>
					</table><hr>";
				 }
				$html .= "	<table>
						<tr  style='font-family:arial;font-size: 11px;'><td colspan=3>Apabila ada pertanyaan mengenai email ini dapat menghubungi :<br></td></tr>							
						<tr  style='font-family:arial;font-size: 11px;'><td>Email</td><td>:</td><td>TAP.callcenter.helpdesk@tap-agri.com</td></tr>
						<tr  style='font-family:arial;font-size: 11px;'><td>Ext</td><td>:</td><td>794/502</td></tr>
						<tr  style='font-family:arial;font-size: 11px;'><td>HP</td><td>:</td><td>0821 1401 3315</td></tr>
						<tr  style='font-family:arial;font-size: 11px;'><td>PIN</td><td>:</td><td>74A84D64</td></tr>
						<tr  style='font-family:arial;font-size: 11px;'><td>Website</td><td>:</td><td>http://helpdesk.tap-agri.com</td></tr>						
					</table>";
					/*Regards,<br><br>
					<strong><i><u>ADMIN PR ONLINE</u></i><br><br></font>
					<font style='font-family:arial;font-size: 10px;'>PT Triputra Agro Persada</strong><br>
					The East Suite 23<br>
					Jl. DR IDE ANAK AGUNG GEDE AGUNG Kav. E3.2 no. 1<br>
					Jakarta Selatan, 12950<br>
					T. 021 - 5794 4737<br>
					F. 021 - 5794 4745<br></font>";*/
				
        return $html;
    }    
    
    

    public function set_default_status($key = 'status') {
        $dropdown['status'] = array("E" => "Enable", "D" => "Disable");
        $dropdown['block_id'] = array("0" => "Not Blocked", "1" => "Blocked");
        return $dropdown[$key];
    }
	
	public function set_default_status_gad($key = 'status_gad') {
        $dropdown['status_gad'] = array("E" => "Enable", "D" => "Disable","%"=>"ALL");
        $dropdown['block_id'] = array("0" => "Not Blocked", "1" => "Blocked");
        return $dropdown[$key];
    }

    public function pagination($temp, $all_row, $path) {
        $this->void = 1;
        $this->setting_pagging['function_name'] = 'pagination';
        $this->setting_pagging['full_tag_open'] = '<ul class="pagination">';
        $this->setting_pagging['full_tag_close'] = '</ul><!--pagination-->';
        $this->setting_pagging['use_page_numbers'] = TRUE;
        $this->setting_pagging['first_link'] = '&laquo; First';
        $this->setting_pagging['first_tag_open'] = '<li class="prev page">';
        $this->setting_pagging['first_tag_close'] = '</li>';

        $this->setting_pagging['last_link'] = 'Last &raquo;';
        $this->setting_pagging['last_tag_open'] = '<li class="next page">';
        $this->setting_pagging['last_tag_close'] = '</li>';

        $this->setting_pagging['next_link'] = 'Next';
        $this->setting_pagging['next_tag_open'] = '<li class="next page">';
        $this->setting_pagging['next_tag_close'] = '</li>';

        $this->setting_pagging['prev_link'] = 'Previous';
        $this->setting_pagging['prev_tag_open'] = '<li class="prev page">';
        $this->setting_pagging['prev_tag_close'] = '</li>';

        $this->setting_pagging['cur_tag_open'] = '<li class="active"><a href="#">';
        $this->setting_pagging['cur_tag_close'] = '</a></li>';

        $this->setting_pagging['num_tag_open'] = '<li class="page">';
        $this->setting_pagging['num_tag_close'] = '</li>';
        $this->setting_pagging['base_url'] = BASEURL_S . $path;
        $this->setting_pagging['first_url'] = $this->setting_pagging['base_url'] . '/1';
        $this->setting_pagging['add_atribute'] = $path;
        $this->setting_pagging['total_rows'] = $all_row;
        $this->setting_pagging['per_page'] = $this->per_page;
        $this->setting_pagging['use_page_numbers'] = TRUE;
        $this->setting_row = $temp;

        $this->pagination->initialize($this->setting_pagging);
        if (!empty($this->void)) {
            $this->attribute['paging'] = $this->pagination->create_links_void();
        } else {
            $this->attribute['paging'] = $this->pagination->create_links();
        }
        return $this->attribute['paging'];
    }

    public function create_condition($search) {
        $unset_parameter = array('searchBy', 'check_all', 'check');
        $where = '';

        foreach ($unset_parameter as $variable) {
            unset($search[$variable]);
        }
        foreach ($search as $key => $val) {
            if ($val != '') {
                $where.=($where == "") ? " AND " . $key . " like '" . addslashes($val) . "'" : " AND " . $key . " like '" . addslashes($val) . "'";
                $this->attribute[$key . '_VAL'] = $val;
            } else {
                $this->attribute[$key . '_VAL'] = $val;
            }
        }
        return $where;
    }

    public function decode_parameter($array) {
        return json_decode(base64_decode($array), TRUE);
    }

	
	// Begin Function Encrypt URL 
    public  function safe_b64encode($string) {
	
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }

	public function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
	
    public  function encode($value){ 
		
	    if(!$value){return false;}
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->config->item('encryption_key'), $text, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext)); 
    }
    
    public function decode($value){
		
        if(!$value){return false;}
        $crypttext = $this->safe_b64decode($value); 
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->config->item('encryption_key'), $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }	
	
	// End Function Encrypt URL
}
