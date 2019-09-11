<?php
session_start();
include("../include/Header.php");
?>
<!-- LIBRARY JQUERY -->
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery.min.js"></script>

<!-- END LIBRARY JQUERY -->
<!-- TOKEN INPUT 
<script type="text/javascript" src="jquery.tokeninput.js"></script>
-->

<script type="text/javascript">
$(document).ready(function() {
	
	var bacode = $("#ID_BAlabel").val();
	
	var src =  $('#NIK_Gandeng_auto').find(":selected").text();
	$("#NIK_Gandeng_auto").autocomplete("userPemanen.php?bacode="+bacode+"&q="+src, {
		selectFirst: true
    });
	
	var q =  $('#NIK_Gandeng_BARU').find(":selected").text();
	$("#NIK_Gandeng_BARU").autocomplete("userPemanen.php?bacode="+bacode+"&q="+q, {
		selectFirst: true
	});
});
</script>

<link rel="stylesheet" type="text/css" href="jquery.autocomplete.css" />
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery.autocomplete.js"></script>

<link rel="stylesheet" href="token-input.css" type="text/css" />
<!-- END TOKEN INPUT -->
<?php
if(isset($_SESSION['Job_Code']) && isset($_SESSION['NIK']) && isset($_SESSION['Name']) && isset($_SESSION['subID_BA_Afd']) && isset($_SESSION['Date']) && isset($_SESSION['Comp_Name'])){	
$Job_Code = $_SESSION['Job_Code'];
$username = $_SESSION['NIK'];	
$Emp_Name = $_SESSION['Name'];
//$Jenis_Login = $_SESSION['Jenis_Login'];
$Comp_Name = $_SESSION['Comp_Name'];
$subID_BA_Afd = $_SESSION['subID_BA_Afd'];
$Date = $_SESSION['Date'];
$ID_Group_BA = $_SESSION['ID_Group_BA'];
$subID_CC = $_SESSION['subID_CC'];

	
	if($username == ""){
		$_SESSION[err] = "tolong login dulu!";
		header("location:login.php");
	}
	else{
		include("../config/SQL_function.php");
		//require_once __DIR__ . '/db_config.php'; 
		//$con = oci_connect(DB_USER, DB_PASSWORD, DB_SERVER.'/'.DB_DATABASE) or die ('Connection Failed');
		include("../config/db_connect.php");
		$con = connect();
		if(isset($_POST['editTGL_RENCANA'])){
			$_SESSION['tgl'] = $_POST['editTGL_RENCANA'];
		}
		
		if(isset($_POST['editNIK_PEMANEN'])){
			$_SESSION['NikPemanen'] = $_POST['editNIK_PEMANEN'];
		}
		
		if(isset($_POST['editNAMA_PEMANEN'])){
			$_SESSION['NamaPemanen'] = $_POST['editNAMA_PEMANEN'];
		}
		
		if(isset($_POST['editID_RENCANA'])){
			$_SESSION['IdRencana'] = $_POST['editID_RENCANA'];
		}
		
		if(isset($_POST["editID_RENCANA"])){
		$_SESSION["editID_RENCANA"] = $_POST["editID_RENCANA"];
		}
		
		if(isset($_SESSION["editID_RENCANA"])){
		$ID_RENCANA = $_SESSION["editID_RENCANA"];
		}
		
		if(isset($_POST["editID_AFD"])){
		$_SESSION["editID_AFD"] = $_POST["editID_AFD"];
		}
		
		if(isset($_SESSION["editID_AFD"])){
		$ID_AFD = $_SESSION["editID_AFD"];
		}
		
		if(isset($_POST["editID_BLOK"])){
		$_SESSION["editID_BLOK"] = $_POST["editID_BLOK"];
		}
		
		if(isset($_SESSION["editID_BLOK"])){
		$ID_BLOK = $_SESSION["editID_BLOK"];
		}
		
		
		//Edited by Ardo, 29-09-2016 : CR perubahan proses koreksi AAP
		if(isset($_POST["editLUASAN_PANEN"])){
		$_SESSION["val_luasan_panen"] = number_format((float)$_POST["editLUASAN_PANEN"], 2, '.', '');
		}
		 
			$sql_user_login  = 	"select c.id_cc as COMPANY_CODE, c.id_ba as BUSINESS_AREA, d.comp_name AS COMPANY_NAME
								from t_employee a, t_afdeling b, t_bussinessarea c, t_companycode d
								where a.id_ba_afd = b.id_ba_afd and b.id_ba = c.id_ba and c.id_cc = d.id_cc 
								and a.nik = '$_SESSION[NIK]'";
			$result_user_login	= select_data($con,$sql_user_login);
			$val_ba		= $result_user_login["BUSINESS_AREA"];
		  
			$sql_user_login  	= 	"select maksimum_jumlah_gandeng, id_ba, to_char(start_date,'MM/DD/YYYY') start_date, to_char(end_date,'MM/DD/YYYY') end_date from t_max_gandeng where id_ba='$val_ba'";
			$result_user_login	= select_data($con,$sql_user_login);
			$max_jml_gandeng	= $result_user_login["MAKSIMUM_JUMLAH_GANDENG"];
			if($max_jml_gandeng==null)
			{
				$max_jml_gandeng =  0;
			}
			
		//GET ALL MANDOR DAN KERANI 
			$SQLGETMANDOR = "SELECT NIK_MANDOR, 
								f_get_empname (NIK_MANDOR) MANDOR,
								NIK_KERANI_BUAH,
								f_get_empname (NIK_KERANI_BUAH) KERANI 
								from t_header_rencana_panen WHERE NIK_PEMANEN = '".$_SESSION['NikPemanen']."' and TANGGAL_RENCANA = '".$_SESSION['tgl']."'";
			$RESSQLGET = oci_parse($con, $SQLGETMANDOR);
			oci_execute($RESSQLGET, OCI_DEFAULT);
			
			while (oci_fetch($RESSQLGET)) {
				$MANDOR[] 	= oci_result($RESSQLGET, "MANDOR");
				$KERANI[] 	= oci_result($RESSQLGET, "KERANI");
			}
		//==================================================================	
			
		$sql_t_hpk  = "
			select thrp.id_rencana, tb.id_blok, ta.id_afd, NIK_Pemanen, f_get_empname(NIK_Pemanen) Nama_Pemanen, 
			NIK_Mandor, f_get_empname(NIK_Mandor) Nama_Mandor, 
			NIK_Kerani_buah, f_get_empname(NIK_Kerani_buah) Nama_Kerani_buah, 
			luasan_panen, no_rekap_bcc, nik_gandeng 
			FROM t_header_rencana_panen thrp,
			 t_blok tb,
			 t_afdeling ta,
			 t_hasilpanen_kualtas thk,
			 t_bussinessarea tba,
			 t_detail_rencana_panen tdrp
			 left join t_detail_gandeng tdg on tdrp.id_rencana = tdg.id_rencana
			WHERE 
			thrp.id_rencana = tdrp.id_rencana and 
			tdrp.id_ba_afd_blok = tb.id_ba_afd_blok and 
			tb.id_ba_afd = ta.id_ba_afd and 
			tdrp.id_rencana = thk.id_rencana and 
			ta.id_ba = tba.id_ba and
			--thrp.status_gandeng = 'YES' and 
			tdrp.luasan_panen >= 0 and 
			tba.id_cc = '$subID_CC' and
			ta.ID_BA = '$subID_BA_Afd' and 
			thrp.id_rencana = '$ID_RENCANA' and
			tb.id_blok = '$ID_BLOK' and
			ta.id_afd = '$ID_AFD'
			group by thrp.id_rencana, tb.id_blok, ta.id_afd, NIK_PEMANEN, NIK_Mandor, NIK_Kerani_buah, NIK_Kerani_buah, luasan_panen, no_rekap_bcc, nik_gandeng 
			order by Nama_Pemanen 
							";
							
			$_SESSION["sql_koreksi_aap"] = $sql_t_hpk;
			$result_t_hpk = oci_parse($con, $sql_t_hpk);
			oci_execute($result_t_hpk, OCI_DEFAULT);
			
			//echo $sql_t_hpk; die();
			
			while (oci_fetch($result_t_hpk)) {	
				$viewID_Rencana = oci_result($result_t_hpk, "ID_RENCANA");
				$viewID_AFD = oci_result($result_t_hpk, "ID_AFD");
				$viewID_BLOK = oci_result($result_t_hpk, "ID_BLOK");
				$viewNIK_GANDENG = oci_result($result_t_hpk, "NIK_GANDENG");
				$viewLUASAN_PANEN = number_format((float)oci_result($result_t_hpk, "LUASAN_PANEN"), 2, '.', '');
				$viewNIK_PEMANEN = oci_result($result_t_hpk, "NIK_PEMANEN");
				$viewNAMA_PEMANEN = oci_result($result_t_hpk, "NAMA_PEMANEN");
				$viewNIK_KERANI_BUAH = oci_result($result_t_hpk, "NIK_KERANI_BUAH");
				$viewNAMA_KERANI_BUAH = oci_result($result_t_hpk, "NAMA_KERANI_BUAH");
				$viewNIK_MANDOR = oci_result($result_t_hpk, "NIK_MANDOR");
				$viewNAMA_MANDOR = oci_result($result_t_hpk, "NAMA_MANDOR");
				$viewNO_REKAP_BCC = oci_result($result_t_hpk, "NO_REKAP_BCC");
			}
		
		//$sql_t_BCC_table = "select ID_GANDENG, NIK_GANDENG, f_get_empname(NIK_GANDENG) NAMA_GANDENG from t_detail_gandeng where id_rencana = '$ID_RENCANA' order by ID_GANDENG";
		$sql_t_BCC_table = "SELECT NIK_GANDENG, f_get_empname (NIK_GANDENG) NAMA_GANDENG
							FROM t_detail_gandeng TDG LEFT JOIN T_HEADER_RENCANA_PANEN THRP
										ON THRP.ID_RENCANA = TDG.ID_RENCANA
							WHERE THRP.ID_RENCANA like '%".$_SESSION['NikPemanen']."'
                            AND THRP.TANGGAL_RENCANA  = TO_DATE( '".$_SESSION['tgl']."', 'DD-MON-RRRR')
							GROUP BY NIK_GANDENG,  f_get_empname (NIK_GANDENG) 
                            ORDER BY NIK_GANDENG";
		
		$rec_jml_gandeng=0;
		$result_t_BCC_table = oci_parse($con, $sql_t_BCC_table);
		oci_execute($result_t_BCC_table, OCI_DEFAULT);
		
		while(oci_fetch($result_t_BCC_table)){
			//$ID_RENCANAS[] 	= oci_result($result_t_BCC_table, "ID_RENCANA");
			//$ID_GANDENG[] 	= oci_result($result_t_BCC_table, "ID_GANDENG");
			$NIK_GANDENG[] 	= oci_result($result_t_BCC_table, "NIK_GANDENG");
			$NAMA_GANDENG[] 	= oci_result($result_t_BCC_table, "NAMA_GANDENG");
			if(oci_result($result_t_BCC_table, "NIK_GANDENG")!='-'){
				$rec_jml_gandeng++;
			}
		}
		$roweffec_DETAILGANDENG = oci_num_rows($result_t_BCC_table);
		
		//ADD
		$EditLuasan = "style='width: 50px; height:25px; font-size:15px' onmousedown= 'return true'";
		$displayAdd = "inline";		
		$displayDel = "inline";	
		$displayformNewAAP = "none";
		if(isset($_SESSION['AddAAP']) && isset($_SESSION['NewIDGandeng'])){
		$AddAAP 		= $_SESSION['AddAAP'];
		$NewIDGandeng 	= $_SESSION['NewIDGandeng'];
		//echo "AddAAP value ". $_SESSION['AddAAP'];
			if($AddAAP == TRUE)
			{
				$EditLuasan = "style='background-color:#CCC; width: 50px; height:25px; font-size:15px' onmousedown= 'return false'";
				$displayAdd = "none";
				$displayDel = "none";	
				$displayformNewAAP = "inline";	
				unset($_SESSION['AddAAP']);
			}
			else{
				$EditLuasan = "style='width: 50px; height:25px; font-size:15px' onmousedown= 'return true'";
				$displayAdd = "inline";
				$displayDel = "inline";
				$displayformNewAAP = "none";
			}
		}	
	}
	
?>

<script type="text/javascript">
$(document).ready(function() {
	
	$("#button").live('click',function() {
		var str = $('#NIK_Gandeng_auto').val();
		if (str == ''){
			alert ('Silahkan input pemanen terlebih dahulu!');
		}else{
			var n=str.split(":"); 
			var max = $('#max_gandeng').val();
			var jml = $('#jml_gandeng').val();
			var bgr = $('#bg').val();
			
			if (bgr == "#F0F3EC"){
				bgr = "#DEE7D2";
			}else{
				bgr = "#F0F3EC";
			}
			
			if (jml < max){
			var seqNum = Number(jml) + 1;	
			$('#tblPemanenGandeng').append('<tr style="font-size:14px" bgcolor="'+bgr+'" id="trPmn'+seqNum+'">'+
											'<td id="bordertable" class="nomor"></td>'+
											'<td id="bordertable" align="center"> '+n[0]+' <input type="hidden" class="GD_NIK_GANDENG_ID" name="GD_NIK_GANDENG[]" value="'+n[0]+' | '+n[1]+'"/></td>'+
											'<td id="bordertable" align="center"> '+n[1]+'</td>'+
											'<td id="bordertable" align="center"> <input type="button" value="Delete (-)" onclick="delPemanen('+seqNum+')"></td>'+
										'</tr>');
			$('#NIK_Gandeng_auto').val('');	
			$('#jml_gandeng').val(Number(jml)+1);
			
			$(".nomor").get().forEach(function(elem, index, array) {
					nom = Number(index) + 1;
					$(elem).html('<center>'+nom+'</center>');
			});
			}else{
				alert ("Jumlah Pemanen Gandeng maksimal "+max+" - "+jml +"orang");
				$('#NIK_Gandeng_auto').val('');
			}
		}
	});
	
	$("#buttonDel").live('click',function() {
		var delId = $(this).attr('class');
		var jml = $('#jml_gandeng').val();
		
		$("#trPmn"+delId).fadeOut(300, function() { 
			$(this).remove(); 
			$(".nomor").get().forEach(function(elem, index, array) {
				nom = Number(index) + 1;
				$(elem).html('<center>'+nom+'</center>');
			});
			$('#jml_gandeng').val(Number(jml)-1);
		});
	});
	
});

function clearVal(idx){
	$('#luasanPanenBaru'+idx).val('');
}

function formEditSubmit(){
	var numBlock = $('#afdblocksum').val();
	var VVblockha = '';
	var VVpemanen = '';
	for (i = 1; i <= numBlock; i++) { 
		var Vblock = $('#afdblock'+i).val(); 
		var ha = $('#luasanPanenBaru'+i).val(); 
		if (ha != ''){
			var Vblockha =  '\t- '+ Vblock +' | '+ ha +' ha\n';
			VVblockha =  VVblockha + Vblockha;
		}
	}
	
	$(".GD_NIK_GANDENG_ID").get().forEach(function(elem, index, array) {
		VVpemanen = VVpemanen + '\t- '+ $(elem).val() + '\n';
	});
		
	if (confirm('Apakah anda yakin melakukan koreksi atas pemanen <?=$_SESSION['NamaPemanen']?>  pada tanggal <?=$_SESSION['tgl']?> ?\n\n Perubahan hektaran : \n'+ VVblockha + '\n Pemanen Yang terdaftar : \n' + VVpemanen)) {
		// Save it!
		$.ajax({
			url: "doAdUpDels.php",
			data: $('#FormEditDetailGandeng').serialize() + $( "input[name=GD_NIK_GANDENG[]]" ).serialize(),
			type: "POST",
			success: function(data){
				//location.reload(); 
				window.location=window.location; // NBU 23032018
			}
		});
	} else {
    // Do nothing!
	}
	
}

</script>
<link href="../css/style.css" rel="stylesheet" type="text/css" media="all" />

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
.style1 {
	color: #FF0000;
 	font-size:16px;
	font-weight:normal;
}
body,td,th {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-weight:normal;
}
</style>
<table width="1079" height="390" border="0" align="center" id="setbody2">
  <tr>
    <th height="197" scope="row" align="center"><table width="937" border="0" id="setbody2">
    <!--<table width="1031" border="0" id="setbody2">-->
    <tr>
        <td colspan="3" align="left" valign="baseline"><span style="font:bold; font-size:20px; font-weight: bold;"><strong>KOREKSI DATA AKTIVITAS AKHIR PANEN</strong></span></td>
      </tr>
      <tr style="border-bottom:solid #000">
        <td colspan="2" align="center">
        <table width="995" border="0" id="setbody2">
		  <tr>
            <td width="125">Tanggal</td>
            <td width="462"><input name="tglRencana" type="text" id="tglRencana" value="<?=$_SESSION['tgl']?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
          </tr>
          <tr>
            <td width="125">Company Name</td>
            <td width="462"><input name="Comp_Name" type="text" id="Comp_Name" value="<?=$_SESSION['Comp_Name']?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
          </tr>
		  <tr>
            <td>Business Area</td>
            <td><input name="ID_BAlabel" type="text" id="ID_BAlabel" value="<?=$_SESSION['subID_BA_Afd']?>" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/></td>
          </tr>  
			<?php
				
				for($x = 0; $x < count(array_unique($MANDOR)); $x++) {
					if ($x == 0){
						$tLabel = "Nama Mandor";
					}else{
						$tLabel = ""; 
					}
					echo '<tr>
							<td>'.$tLabel.'</td>
							<td><input name="Nama_Mandorlabel" type="text" id="Nama_Mandorlabel" value="'.$MANDOR[$x].'" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
						  </tr>';
				}
				
				for($x = 0; $x < count(array_unique($KERANI)); $x++) {
					if ($x == 0){
						$tLabel = "Nama Kerani Buah";
					}else{
						$tLabel = ""; 
					}
					echo '<tr>
							<td width="152">'.$tLabel.'</td>
							<td width="343"><input name="Krani_Buahlabel" type="text" id="Krani_Buahlabel" value="'.$KERANI[$x].'" style="background-color:#CCC; width:300px; height:25px; font-size:15px" onmousedown="return false"/></td>
						  </tr>';
				}
			?> 
		  <tr>
            <td width="125">Nama Pemanen</td>
            <td width="462"><input name="Nama_Pemanen" type="text" id="Nama_Pemanen" value="<?=$_SESSION['NamaPemanen']?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
          </tr>
		  <tr>
            <td width="125">Nik Pemanen</td>
            <td width="462"><input name="Nik_Pemanen" type="text" id="Nik_Pemanen" value="<?=$_SESSION['NikPemanen']?>" style="background-color:#CCC; width: 300px; height:25px; font-size:15px" onmousedown="return false"/></td>
          </tr>
        </table>
        </td>
      </tr>
      
      <form id="FormEditDetailGandeng">
      
      <tr>
        <td colspan="2" align="center" style="border-bottom:solid #000">
			<center>Luasan Panen</center>
			<table width="992" border="0" id="setbody2">
				<tr bgcolor="#9CC346">
				  <td width="55" align="center" style="font-size:14px" id="bordertable">No.</td>
				  <td width="100" align="center" style="font-size:14px" id="bordertable">Afdeling</td>
				  <td width="100" align="center" style="font-size:14px" id="bordertable">Blok</td>
				  <td width="100" align="center" style="font-size:14px" id="bordertable">Blok Desk</td>
				  <td width="100" align="center" style="font-size:14px" id="bordertable">Luasan Panen</td>
				  <td width="100" align="center" style="font-size:14px" id="bordertable">Luasan Panen Baru</td>
				</tr>
				<?php
				//GET LUASAN PANEN
					$SQLLUASANS = "SELECT tdrp.ID_RENCANA, ta.id_afd,  tb.id_blok, tb.blok_name, tdrp.luasan_panen   FROM  t_detail_rencana_panen tdrp
									 LEFT JOIN t_blok tb
										ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
										 LEFT JOIN t_afdeling ta
										ON tb.id_ba_afd = ta.id_ba_afd
										 WHERE ID_RENCANA = '".$_SESSION['IdRencana']."'";	

					$SQLLUASAN = "SELECT THRP.ID_RENCANA, TDRP.NO_REKAP_BCC, THRP.NIK_PEMANEN, ta.id_afd,  tb.id_blok, tb.blok_name, MAX(tdrp.luasan_panen) luasan_panen  FROM  T_HEADER_RENCANA_PANEN THRP
                                    JOIN  t_detail_rencana_panen  TDRP
                                    ON THRP.ID_RENCANA = TDRP.ID_RENCANA
                                     LEFT JOIN t_blok tb
                                        ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
                                         LEFT JOIN t_afdeling ta
                                        ON tb.id_ba_afd = ta.id_ba_afd
                                         WHERE THRP.ID_RENCANA like '%".$_SESSION['NikPemanen']."'
                                         AND THRP.TANGGAL_RENCANA  = TO_DATE( '".$_SESSION['tgl']."', 'DD-MON-RRRR')
                                         GROUP BY THRP.ID_RENCANA, TDRP.NO_REKAP_BCC, THRP.NIK_PEMANEN, ta.id_afd,  tb.id_blok, tb.blok_name
                                         ORDER BY ta.id_afd,  tb.id_blok";

					$RESLUASAN = oci_parse($con, $SQLLUASAN);
					oci_execute($RESLUASAN, OCI_DEFAULT);
					
					$x=0;
					while (oci_fetch($RESLUASAN)) {
						$x++;
						echo '<tr>
							  <td align="center" style="font-size:14px" id="bordertable">'.$x.'</td>
							  <td align="center" style="font-size:14px" id="bordertable">'.oci_result($RESLUASAN, "ID_AFD").'<input type="hidden" name="luasanIdRencana[]" value="'.oci_result($RESLUASAN, "ID_RENCANA").'"/></td>
							  <td align="center" style="font-size:14px" id="bordertable">'.oci_result($RESLUASAN, "ID_BLOK").'<input type="hidden" name="luasanNoRekapBcc[]" value="'.oci_result($RESLUASAN, "NO_REKAP_BCC").'"/></td>
							  <td align="center" style="font-size:14px" id="bordertable" id="afdblock'.$x.'">'.oci_result($RESLUASAN, "BLOK_NAME").'<input type="hidden" id="afdblock'.$x.'" name="luasanafdblock[]" value="'.oci_result($RESLUASAN, "ID_AFD").' | '.oci_result($RESLUASAN, "ID_BLOK").' | '.oci_result($RESLUASAN, "BLOK_NAME").'"/></td>
							  <td align="right" style="font-size:14px" id="bordertable">'.oci_result($RESLUASAN, "LUASAN_PANEN").' ha </td>
							  <td align="center" style="font-size:14px" id="bordertable"><input type="text" id="luasanPanenBaru'.$x.'" name="luasanPanenBaru[]" onclick="clearVal('.$x.')"/> ha </td>
							</tr>';
					}
				?>
			</table>
			<input type="hidden" id="afdblocksum" value="<?php echo $x;?>" />
        </td>
      </tr>
      <tr style="border-bottom:solid #000">
        <td colspan="2" align="center">
			<br/>
			<table border="0" id="tblPemanenGandeng">
				<tr bgcolor="#9CC346">
					<td width="" align="center" style="font-size:14px" id="bordertable">No.</td>
					<td width="" align="center" style="font-size:14px" id="bordertable"><input type="hidden" name="GD_NIK_GANDENG[]" value=""/>NIK Pemanen Gandeng</td>
					<td width="" align="center" style="font-size:14px" id="bordertable">Nama Pemanen Gandeng</td>
					<td width="" align="center" style="font-size:14px" id="bordertable">Delete</td>
				</tr>
				<?php
					for($xJAN = 0; $xJAN <  $roweffec_DETAILGANDENG ; $xJAN++){
						$no = $xJAN +1;
						
						if(($xJAN % 2) == 0){
							$bg = "#F0F3EC";
						}
						else{
							$bg = "#DEE7D2";
						}
					echo "<tr style=\"font-size:14px\" bgcolor='$bg' id='trPmn$no'>";
					echo "<td align=\"center\" id=\"bordertable\" class='nomor'>$no</td>
						<td align=\"center\" id=\"bordertable\">$NIK_GANDENG[$xJAN]
							<input name=\"GD_NIK_GANDENG[]\" class=\"GD_NIK_GANDENG_ID\" type=\"hidden\" value=\"$NIK_GANDENG[$xJAN] | $NAMA_GANDENG[$xJAN]\" \>
						</td>	
						<td align=\"center\" id=\"bordertable\">$NAMA_GANDENG[$xJAN]</td>
						<td align=\"center\" id=\"bordertable\">
							<input type=\"button\" id=\"buttonDel\" value=\"Delete (-)\" class=\"$no\" />
						</td>
					</tr>";
					}
				?>
				<tfoot id="setAdd">
					<tr bgcolor="#9CC346">
						<td colspan="3" id="bordertable">
							<input name="NIK_Gandeng_auto" type="text" id="NIK_Gandeng_auto" value="" style="width:400px; height:25px; font-size:15px"/>
						</td>
						<td id="bordertable" align="center">
							<input type="button" id="button" value="Add (+)" style="width:70px; height:30px;"/>
							<input type="hidden" id="max_gandeng" value="<?=$max_jml_gandeng ?>">
							<input type="hidden" id="jml_gandeng" value="<?=$rec_jml_gandeng ?>">
							<input type="hidden" id="bg" value="<?=$bg?>">
						</td>
					</tr>
				</tfoot>
			</table>	
		<br/>
       <tr>
        <td colspan="2" align="center"><span class="style1">Pastikan koreksi data Anda telah mendapatkan persutujan dari EM atau KABUN !!</span></td>
      </tr>
    <!--</table>-->
	<tr>
        <td align="center" colspan="5">
        <input name="UpdateAAP" type="text" id="UpdateAAP" value="" style="display:none"/>
        <!--<a href="javascript:;" onclick="javascript: document.getElementById('FormEditDetailGandeng') .submit()">-->
        <input type="button" value="SIMPAN" style="width:120px; height: 30px;" onclick="formEditSubmit()"/>
        <!--</a>--></td>
    </tr>
    </form>
    </th>
  </tr>
  <tr>
    <th align="center"><?php
		if(isset($_SESSION['err'])){
			$err = $_SESSION['err'];
			if($err!=null)
			{
				echo $err;
				unset($_SESSION['err']);
			}
		}
		?></th>
  </tr>
  <tr>
    <th align="center"><?php include("../include/Footer.php") ?></th>
  </tr>
</table>

<?php
}
else{
	$_SESSION[err] = "tolong login dulu!".$Job_Code."<br>".$username."<br>".$Emp_Name."<br>".$subID_BA_Afd;
	header("location:../index.php");
}
?>