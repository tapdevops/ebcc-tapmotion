<?php 
	session_start();
	error_reporting(0);

?>
<?php
	define('FPDF_FONTPATH','font/');
	include "fpdf.php";
	
	$i = 0;
	
	include("../config/SQL_function.php");
	include("../config/db_connect.php");
	$con = connect();

	$sql_value = $_SESSION["sql_cetak_LHM_panen"];
	$sql_value = str_replace("null jam_kerja,", "NULL jam_kerja, case when thp.status_tph = 'MANUAL' THEN 'Y' else '' end status_input_manual,
    case when thp.status_tph = 'WEBSITE' THEN 'Y' else '' end status_koreksi_lokasi,", $sql_value);
	$print_date = $_SESSION["printdate"];
	$tgl1 = $_SESSION["tgl1"];
	$tgl2 = $_SESSION["tgl2"];
	$id_ba = $_SESSION["ID_BA"];
	$id_cc = $_SESSION["ID_CC"];
	$valueAfdeling = $_SESSION["valueAfd"];
	$NIK_Mandor = $_SESSION["nikmandor"];
	
	//echo $sql_value; exit;
	$stid = oci_parse($con, $sql_value);
	oci_execute($stid);
	//echo date('m-d-Y H:i:s'); exit;
	
	while (($data = oci_fetch_array($stid, OCI_ASSOC))) 
	{
		$no = $no + 1;
		$cell[$i][0] = $no;
		$cell[$i][1] = $data["NIK_PEMANEN"];
		$cell[$i][2] = $data["NAMA_PEMANEN"];
		$cell[$i][3] = $data["NO_BCC"];
		$cell[$i][4] = $data["NO_TPH"];
		$cell[$i][5] = $data["ID_BLOK"];
		$cell[$i][6] = number_format((float)$data["LUASAN_PANEN"], 2, '.', '');
		$cell[$i][7] = $data["STATUS_INPUT_MANUAL"];
		$cell[$i][8] = $data["STATUS_KOREKSI_LOKASI"];
		$cell[$i][9] = $data["TBS"];
		$cell[$i][10] = $data["BRD"];
		$cell[$i][11] = $data["BM"];
		$cell[$i][12] = $data["BK"];
		$cell[$i][13] = $data["TP"];
		$cell[$i][14] = $data["BB"];
		$cell[$i][15] = $data["JK"];
		$cell[$i][16] = $data["BT"];
		$cell[$i][17] = $data["BL"];
		$cell[$i][18] = $data["PB"];
		$cell[$i][19] = $data["AB"];
		$cell[$i][20] = $data["SF"];
		$cell[$i][21] = $data["BS"];
		$cell[$i][22] = "";
		$cell[$i][23] = "";
		$cell[$i][24] = $data["BA"];
		$cell[$i][25] = $data["AFD_PEMANEN"];
		$cell[$i][26] = $data["CUSTOMER"];
		$cell[$i][27] = $data["MS"];
		$cell[$i][28] = $data["OR"];
		
		$blok_name[$i] = $data["BLOK_NAME"];
		$no_rekap_bcc[$i] = $data["NO_REKAP_BCC"];
		$id_rencana[$i] = $data["ID_RENCANA"];
		$tglPanen[$i] = $data["TGL_PANEN"];
		$NIKMandor[$i] = $data["NIK_MANDOR"];
		$NamaMandor[$i] = $data["NAMA_MANDOR"];
		$IDBa[$i] = $data["ID_BA"];
		$IDEstate = $data["NAMA_BA"];
		
		$IDAfd[$i] = $data["ID_AFD"];
		$compName = $data["COMP_NAME"];
		
		$KodeDeliveryTicket[$i] = $data["KODE_DELIVERY_TICKET"];
		$i++;
		
		//Set Flag timestamp cetak lhm
		$cek_timestamp_cetak = "SELECT * FROM T_HASIL_PANEN WHERE 
		ID_RENCANA = '".$data['ID_RENCANA']."' AND NO_REKAP_BCC = '".$data['NO_REKAP_BCC']."' AND NO_BCC = '".$data['NO_BCC']."'";
		$result_thp_cek = oci_parse($con, $cek_timestamp_cetak);
		oci_execute($result_thp_cek, OCI_DEFAULT);
		oci_fetch($result_thp_cek);
		$cek_id_rencana_THP = oci_result($result_thp_cek, "ID_RENCANA");
		if($cek_id_rencana_THP!=""){
			$val_cetak_bcc = oci_result($result_thp_cek, "CETAK_BCC");
			$val_cetak_date = oci_result($result_thp_cek, "CETAK_DATE");
			if($val_cetak_bcc=="" and $val_cetak_date==""){
				$query_ins_thp = "UPDATE T_HASIL_PANEN SET CETAK_BCC = 'X', CETAK_DATE = to_date('".date('m-d-Y H:i:s')."', 'MM-DD-YYYY HH24:MI:SS') WHERE ID_RENCANA = '".$data['ID_RENCANA']."' AND NO_REKAP_BCC = '".$data['NO_REKAP_BCC']."' AND NO_BCC = '".$data['NO_BCC']."'";
				
				$result_THP = num_rows($con, $query_ins_thp);
				
			}
		}
	}
	
	
	
	oci_free_statement($stid);
	oci_close($con);
	$pagebreak = 20;
	
	$pdf = new FPDF("L","cm","A4"); //membuat lembar PDF ukuran A4 Landscape, dan ukuran yang digunakan dalam cm
	$pdf->SetMargins(1,2,1); //membuat margin (kiri,atas,kanan)
	
	if($i==0){
		/*added by NB 20.08.2014*/
			$pdf->AddPage(); 
		
			$pdf->SetFont('Arial','B',8); // added by NB 20.08.2014
			$pdf->Cell(28.1,0.6,'Print Date: '.$print_date,0,0,'R');// added by NB 20.08.2014
			$pdf->Ln();// added by NB 20.08.2014
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(10.5,0.6,$compName.' (Palm Oil)',1,0,'L');
			$pdf->Cell(17.5,0.6,'LAPORAN HARIAN MANDOR PANEN',1,0,'C');
			$pdf->Ln();
			
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(4,0.4,'Tanggal Panen','L',0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(5,0.4,$tglPanen[$j],0,0,'L');
			$pdf->Cell(1,0.4,'','R',0,'L');
			$pdf->Cell(3.5,0.4,'','',0,'C');
			$pdf->Cell(3,0.4,'Estate',0,0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(10.5,0.4,$IDEstate,'R',0,'L');	
			$pdf->Ln();
			
			$pdf->Cell(4,0.4,'NIK Mandor Panen','L',0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(5,0.4,$NIKMandor[$j],0,0,'L');
			$pdf->Cell(1,0.4,'','R',0,'L');
			$pdf->Cell(3.5,0.4,'','',0,'C');
			$pdf->Cell(3,0.4,'Divisi/Afdeling',0,0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(10.5,0.4,$IDAfd[$j],'R',0,'L');
			$pdf->Ln();
			
			$pdf->Cell(4,0.4,'Nama Mandor Panen','L',0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(5,0.4,$NamaMandor[$j],0,0,'L');
			$pdf->Cell(1,0.4,'','R',0,'L');
			$pdf->Cell(3.5,0.4,'','',0,'C');
			$pdf->Cell(3,0.4,'Page',0,0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(10.5,0.4,$page_header.' dari '.$to_page ,'R',0,'L');
			$pdf->Ln();
			
			$pdf->Cell(9.5,0.4,'','L',0,'L');
			$pdf->Cell(1,0.4,'','R',0,'L');
			$pdf->Cell(3.5,0.4,'','',0,'C');
			$pdf->Cell(14,0.4,'','R',0,'L');
			
			$pdf->Ln();
			$pdf->SetFont('Arial','B',8);
			
			$pdf->Cell(3,0.5,'AFD KARY',1,0,'C');
			$pdf->Cell(3.1,0.5,'NIK',1,0,'C');
			$pdf->Cell(4.4,0.5,'Nama Karyawan',1,0,'C');
			$pdf->Cell(1,0.5,'',1,0,'C',true);//
			$pdf->Cell(0.5,0.5,'',1,0,'C',true);//
			$pdf->Cell(1.1,0.5,'HA',1,0,'C');
			$pdf->Cell(1,0.5,'Jam',1,0,'C');
			$pdf->Cell(2,0.5,'Hasil Panen',1,0,'C');
			$pdf->Cell(8.4,0.5,'Pinalty',1,0,'C');
			$pdf->Cell(2.2,0.5,'Telah Diperiksa',1,0,'C');
			$pdf->Cell(1.3,0.5,'Cust',1,0,'C');
			
			$pdf->Ln();
			$pdf->Cell(3,0.5,'',1,0,'C',true);//
			$pdf->Cell(3.1,0.5,'',1,0,'C',true);//
			$pdf->Cell(4.4,0.5,'',1,0,'C',true);//
			$pdf->Cell(1,0.5,'',1,0,'C',true);//
			$pdf->Cell(0.5,0.5,'',1,0,'C',true);//
			$pdf->Cell(1.1,0.5,'',1,0,'C',true);//
			$pdf->Cell(1,0.5,'Kerja',1,0,'C');
			$pdf->Cell(1,0.5,'TBS',1,0,'C');
			$pdf->Cell(1,0.5,'BRD',1,0,'C');
			$pdf->Cell(0.7,0.5,'BM',1,0,'C');
			$pdf->Cell(0.7,0.5,'BK',1,0,'C');
			$pdf->Cell(0.7,0.5,'TP',1,0,'C');
			$pdf->Cell(0.7,0.5,'BB',1,0,'C');
			$pdf->Cell(0.7,0.5,'JK',1,0,'C');
			$pdf->Cell(0.7,0.5,'BA',1,0,'C');
			$pdf->Cell(0.7,0.5,'BT',1,0,'C');
			$pdf->Cell(0.7,0.5,'BL',1,0,'C');
			$pdf->Cell(0.7,0.5,'PB',1,0,'C');
			$pdf->Cell(0.7,0.5,'AB',1,0,'C');
			$pdf->Cell(0.7,0.5,'SF',1,0,'C');
			$pdf->Cell(0.7,0.5,'BS',1,0,'C');
			$pdf->Cell(2.5,0.5,'',1,0,'C',true);
			$pdf->Cell(1,0.5,'',1,0,'C',true);
			$pdf->Ln();
			
			$pdf->AddPage(); 
		
			$pdf->SetFont('Arial','B',8);// added by NB 20.08.2014
			$pdf->Cell(28.1,0.6,'Print Date: '.$print_date,0,0,'R');// added by NB 20.08.2014
			$pdf->Ln();// added by NB 20.08.2014
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(11.6,0.6,$compName.' (Palm Oil)',1,0,'L');
			$pdf->Cell(16.4,0.6,'LAMPIRAN',1,0,'C');
			$pdf->Ln();
			
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(4,0.4,'Tanggal Panen','L',0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(7.1,0.4,$tglPanen[$j],0,0,'L');
			$pdf->Cell(1.9,0.4,'','L',0,'L');
			$pdf->Cell(3,0.4,'Estate',0,0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(11,0.4,$IDEstate,'R',0,'L');
			$pdf->Ln();
			$pdf->Cell(4,0.4,'NIK Mandor Panen','L',0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(7.1,0.4,$NIKMandor[$j],0,0,'L');
			$pdf->Cell(1.9,0.4,'','L',0,'L');
			$pdf->Cell(3,0.4,'Divisi/Afdeling',0,0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(11,0.4,$IDAfd[$j],'R',0,'L');
			$pdf->Ln();
			$pdf->Cell(4,0.4,'Nama Mandor Panen','L',0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(7.1,0.4,$NamaMandor[$j],0,0,'L');
			$pdf->Cell(1.9,0.4,'','L',0,'L');
			$pdf->Cell(3,0.4,'Page',0,0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(11,0.4,$page_lampiran.' dari '.ceil((($j+1)-$start_lampiran)/20),'R',0,'L');
			//$pdf->Cell(11,0.4,$page_lampiran.' dari '.($j+1)."-".$start_lampiran." ".(($j+1)-$start_lampiran)/20,'R',0,'L');
			$pdf->Ln();
			
			$pdf->Cell(10.6,0.4,'','L',0,'L');
			$pdf->Cell(1,0.4,'','R',0,'L');
			$pdf->Cell(3.5,0.4,'','',0,'C');
			$pdf->Cell(12.9,0.4,'','R',0,'L');
			$pdf->Ln();
			
			//bagian untuk memasukkan keterangan tabel
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(0.6,0.5,'No.',1,0,'C');
			$pdf->Cell(1,0.5,'AFD',1,0,'C');
			$pdf->Cell(2.5,0.5,'NIK',1,0,'C');
			$pdf->Cell(4.5,0.5,'Nama Karyawan',1,0,'C');
			$pdf->Cell(3,0.5,'OPH/BCC',1,0,'C');
			$pdf->Cell(0.8,0.5,'TPH',1,0,'C');
			$pdf->Cell(0.8,0.5,'Blok',1,0,'C');
			$pdf->Cell(0.8,0.5,'Desc',1,0,'C');
			$pdf->Cell(0.8,0.5,'HA',1,0,'C');
			$pdf->Cell(0.8,0.5,'Jam',1,0,'C');
			$pdf->Cell(2,0.5,'Hasil Panen',1,0,'C');
			$pdf->Cell(8.4,0.5,'Pinalty',1,0,'C');
			$pdf->Cell(1,0.5,'Kode',1,0,'C');
			$pdf->Cell(1,0.5,'Cust',1,0,'C');
			$pdf->Ln();
			$pdf->Cell(0.6,0.5,'',1,0,'C',true);
			$pdf->Cell(1,0.5,'KARY',1,0,'C');
			$pdf->Cell(2.5,0.5,'',1,0,'C',true);
			$pdf->Cell(4.5,0.5,'',1,0,'C',true);
			$pdf->Cell(3,0.5,'',1,0,'C',true);
			$pdf->Cell(0.8,0.5,'',1,0,'C',true);
			$pdf->Cell(0.8,0.5,'',1,0,'C',true);
			$pdf->Cell(0.8,0.5,'',1,0,'C',true);
			$pdf->Cell(0.8,0.5,'',1,0,'C',true);
			$pdf->Cell(0.8,0.5,'Kerja',1,0,'C');
			$pdf->Cell(1,0.5,'TBS',1,0,'C');
			$pdf->Cell(1,0.5,'BRD',1,0,'C');
			$pdf->Cell(0.7,0.5,'BM',1,0,'C');
			$pdf->Cell(0.7,0.5,'BK',1,0,'C');
			$pdf->Cell(0.7,0.5,'TP',1,0,'C');
			$pdf->Cell(0.7,0.5,'BB',1,0,'C');
			$pdf->Cell(0.7,0.5,'JK',1,0,'C');
			$pdf->Cell(0.7,0.5,'BA',1,0,'C');
			$pdf->Cell(0.7,0.5,'BT',1,0,'C');
			$pdf->Cell(0.7,0.5,'BL',1,0,'C');
			$pdf->Cell(0.7,0.5,'PB',1,0,'C');
			$pdf->Cell(0.7,0.5,'AB',1,0,'C');
			$pdf->Cell(0.7,0.5,'SF',1,0,'C');
			$pdf->Cell(0.7,0.5,'BS',1,0,'C');
			$pdf->Cell(1,0.5,'Absen',1,0,'C');
			$pdf->Cell(1,0.5,'',1,0,'C',true);
			$pdf->Ln();
			$pdf->Cell(27,0.1,'',1,0,'C');
			$pdf->Ln();
	}
	
	//bagian untuk memasukkan isi tabel
	$totalBlok=0;
	$nomor = 0;
	$start_lampiran = 0;
	for ($j=0;$j<$i;$j++)
	{
		
		if($j == 0)
		{
			$manual = (!empty($cell[$j][7])) ? 1 : 0;
			$koreksi = (!empty($cell[$j][8])) ? 1 : 0;
			$blok[$totalBlok] = $cell[$j][5];
			$blname[$totalBlok] = $blok_name[$j];
			$luasan[$totalBlok] = $cell[$j][6];
			$totalManual[$totalBlok] = $manual;
			$totalKoreksi[$totalBlok] = $koreksi;
			$totalTBS[$totalBlok] = $cell[$j][9];
			$totalBRD[$totalBlok] = $cell[$j][10];
			$totalBlok = $totalBlok +1;
		}
		else
		{
			//Edited by Ardo, 22-09-2016 : CR Synchronize EBCC perubahan perhitungan Luasan Panen
			if($tglPanen[$j]!=$tglPanen[$j-1] || 
					$IDAfd[$j]!=$IDAfd[$j-1] || 
					$cell[$j][5]!=$cell[$j-1][5] || 
					$cell[$j][1]!=$cell[$j-1][1] || $NIKMandor[$j]!=$NIKMandor[$j-1]
					)
			//if($no_rekap_bcc[$j] !== $no_rekap_bcc[$j-1])
			{
				$x_found = 'false';
				for($k=0;$k<$totalBlok;$k++)
				{
					if($cell[$j][5]==$blok[$k])
					{
						$luasan[$k] = $luasan[$k] + $cell[$j][6];
						$x_found = 'true';
					}
				}
				if($x_found == 'false')
				{
					$blok[$totalBlok] = $cell[$j][5];
					$blname[$totalBlok] = $blok_name[$j];
					$luasan[$totalBlok] = $cell[$j][6];
					$totalBlok = $totalBlok +1;
				}
			}
			/* else if($id_rencana[$j] !== $id_rencana[$j-1])
			{
				$x_found = 'false';
				for($k=0;$k<$totalBlok;$k++)
				{
					if($cell[$j][5]==$blok[$k])
					{
						$luasan[$k] = $luasan[$k] + $cell[$j][6];
						$x_found = 'true';
					}
				}
				if($x_found == 'false')
				{
					$blok[$totalBlok] = $cell[$j][5];
					$blname[$totalBlok] = $blok_name[$j];
					$luasan[$totalBlok] = $cell[$j][6];
					$totalBlok = $totalBlok +1;
				}
			} */
			
			$x_found = 'false';
			for($k=0;$k<$totalBlok;$k++)
			{
				if($cell[$j][5]==$blok[$k])
				{
					$tmanual = (!empty($cell[$j][7])) ? 1 : 0;
					$tkoreksi = (!empty($cell[$j][8])) ? 1 : 0;
					$totalManual[$k] = $totalManual[$k] + $tmanual;
					$totalKoreksi[$k] = $totalKoreksi[$k] + $tkoreksi;
					$totalTBS[$k] = $totalTBS[$k] + $cell[$j][9];
					$totalBRD[$k] = $totalBRD[$k] + $cell[$j][10];
					$x_found = 'true';
				}
			}
			if($x_found == 'false')
			{
				$tmanual = (!empty($cell[$j][7])) ? 1 : 0;
				$tkoreksi = (!empty($cell[$j][8])) ? 1 : 0;
				$totalManual[$totalBlok] = $tmanual;
				$totalKoreksi[$totalBlok] = $tkoreksi;
				$totalTBS[$totalBlok] = $cell[$j][9];
				$totalBRD[$totalBlok] = $cell[$j][10];
				$totalBlok = $totalBlok +1;
			}
		}
		
		
		$page_header = 1;
		if(($NIKMandor[$j]!==$NIKMandor[$j+1] || $tglPanen[$j]!==$tglPanen[$j+1] || $IDAfd[$j]!==$IDAfd[$j+1]) && $j+1<=$i)
		{
			$tgl = date("Y-m-d", strtotime($tglPanen[$j]));
			$sql_cetak_panen = "	
						  select afd_pemanen, nik_pemanen, nama_pemanen, customer, sum(luasan_panen) as luasan_panen, sum(TBS) as TBS, sum(BRD) as BRD, sum(BM) as BM, sum(BK) as BK, sum(TP) as TP, sum(BB) as BB, sum(JK) as JK, sum(BT) as BT, sum(BA) as BA, sum(BL) as BL, sum(PB) as PB, sum(AB) as AB, sum(SF) as SF, sum(BS) as BS, status_input_manual, status_koreksi_lokasi from (
						  select afd_pemanen, nik_pemanen, nama_pemanen, customer, max(luasan_panen) as luasan_panen, sum(TBS) as TBS, sum(BRD) as BRD, sum(BM) as BM, sum(BK) as BK, sum(TP) as TP, sum(BB) as BB, sum(JK) as JK, sum(BT) as BT, sum(BA) as BA, sum(BL) as BL, sum(PB) as PB, sum(AB) as AB, sum(SF) as SF, sum(BS) as BS, status_input_manual, status_koreksi_lokasi from (
						  select tgl_panen, afd_pemanen, nik_pemanen, nama_pemanen, customer,
						  id_blok,
						  luasan_panen,
						  sum(TBS) as TBS, 
						  sum(BRD) as BRD,
						  sum(BM) as BM, 
						  sum(BK) as BK,
						  sum(TP) as TP, 
						  sum(BB) as BB,
						  sum(JK) as JK, 
						  sum(BT) as BT,
						  sum(BA) as BA,
						  sum(BL) as BL, 
						  sum(PB) as PB,
						  sum(AB) as AB, 
						  sum(SF) as SF,
						  sum(BS) as BS,
						  status_input_manual,
						  status_koreksi_lokasi
						  from (
						  select tc.id_cc,
						 tc.comp_name comp_name,
						 tba.id_ba,
						 tba.nama_ba nama_ba,
						 ta.id_afd,
						 thrp.tanggal_rencana tgl_panen,
						 thrp.nik_mandor,
						 f_get_idafd_nik(thrp.nik_pemanen) afd_pemanen,
						 f_get_empname (thrp.nik_mandor) nama_mandor,
						 thrp.nik_pemanen NIK_PEMANEN,
						 f_get_empname (thrp.nik_pemanen) nama_pemanen,
						 thp.no_bcc,
						 thp.no_tph,
                        case when thp.status_tph = 'MANUAL' THEN 1 else 0 end status_input_manual,
                        case when thp.status_tph = 'WEBSITE' THEN 1 else 0 end status_koreksi_lokasi,
						 tb.id_blok id_blok,
						 tdrp.luasan_panen luasan_panen,
						 null jam_kerja,
							 NVL (F_GET_HASIL_PANEN_BUNCH (tba.id_ba, thp.no_rekap_bcc, thp.no_bcc, 'BUNCH_HARVEST'), 0) as TBS,
							 NVL( F_GET_HASIL_PANEN_BRDX  (thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc),0)  as BRD,
							 NVL( F_GET_HASIL_PANEN_NUMBERX ( thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 1),0)  as BM, 
							 NVL( F_GET_HASIL_PANEN_NUMBERX ( thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 2) ,0) as BK, 
							 NVL( F_GET_HASIL_PANEN_NUMBERX ( thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 7) ,0) as TP, 
							 NVL( F_GET_HASIL_PANEN_NUMBERX ( thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 6),0)  as BB, 
							 NVL (F_GET_HASIL_PANEN_NUMBERX ( thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 15), 0) JK, 
							 NVL( F_GET_HASIL_PANEN_NUMBERX ( thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 11),0)  as BT, 
							 NVL( F_GET_HASIL_PANEN_NUMBERX ( thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 16),0)  as BA, 
							 NVL( F_GET_HASIL_PANEN_NUMBERX ( thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 12),0)  as BL, 
							 NVL( F_GET_HASIL_PANEN_NUMBERX ( thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 13) ,0) as PB,
							 NVL( F_GET_HASIL_PANEN_NUMBERX ( thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 10) ,0) as AB, 
							 NVL( F_GET_HASIL_PANEN_NUMBERX ( thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 14) ,0) as SF,               
							 NVL( F_GET_HASIL_PANEN_NUMBERX ( thrp.id_rencana, thp.no_rekap_bcc, thp.no_bcc, 8) ,0) as BS, 
						 null kode_absen,
						 CASE
							WHEN ta.ID_BA <> ta2.ID_BA THEN 'CINT_' || ta.ID_BA
							ELSE NULL
						 END
							customer,
						 tb.blok_name,
						 tdrp.no_rekap_bcc,
						 thrp.id_rencana
					   FROM t_header_rencana_panen thrp
						 INNER JOIN T_EMPLOYEE te
							ON thrp.NIK_PEMANEN = te.NIK
						 INNER JOIN T_AFDELING ta2
							ON te.ID_BA_AFD = ta2.ID_BA_AFD
						 INNER JOIN t_detail_rencana_panen tdrp
							ON thrp.id_rencana = tdrp.id_rencana
						 INNER JOIN t_hasil_panen thp
							ON tdrp.id_rencana = thp.id_rencana
							AND tdrp.no_rekap_bcc = thp.no_rekap_bcc
						 INNER JOIN t_blok tb
							ON tdrp.id_ba_afd_blok = tb.id_ba_afd_blok
						 INNER JOIN t_afdeling ta
							ON tb.id_ba_afd = ta.id_ba_afd
						 INNER JOIN t_bussinessarea tba
							ON tba.id_ba = ta.id_ba
						 INNER JOIN t_companycode tc
							ON tba.id_cc = tc.id_cc
				   where     tc.id_cc = '$id_cc'
						 and tba.id_ba = '$id_ba'
						 and ta.id_afd = nvl(decode('$IDAfd[$j]', 'ALL', null, '$IDAfd[$j]'), ta.id_afd)
						 and thrp.nik_mandor = nvl(decode('$NIKMandor[$j]', 'ALL', null, '$NIKMandor[$j]'), thrp.nik_mandor)
						 and TO_CHAR (thrp.tanggal_rencana, 'YYYY-MM-DD') = '$tgl'
				order by   tgl_panen, thrp.nik_mandor,  nama_pemanen,  tdrp.no_rekap_bcc,  thrp.id_rencana, thp.no_bcc) header
							group by tgl_panen, afd_pemanen, nik_pemanen, nama_pemanen, id_blok, luasan_panen, customer, status_input_manual, status_koreksi_lokasi order by tgl_panen, nik_pemanen) detail
				GROUP BY afd_pemanen, tgl_panen, nik_pemanen, nama_pemanen, id_blok, customer, status_input_manual, status_koreksi_lokasi
                            ORDER BY tgl_panen, nik_pemanen)
				group by afd_pemanen, nik_pemanen, nama_pemanen, customer, status_input_manual, status_koreksi_lokasi order by afd_pemanen, nama_pemanen, nik_pemanen";
			//echo $sql_cetak_panen; exit;			
			$con1 = connect();
			$stid1 = oci_parse($con1, $sql_cetak_panen);
			oci_execute($stid1);
			$laporan_count = oci_fetch_all($stid1, $laporan_result, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
			$to_page = ceil($laporan_count/19);
			
			/*added by NB 20.08.2014*/
			$pdf->AddPage(); 
		
			$pdf->SetFont('Arial','B',8); // added by NB 20.08.2014
			$pdf->Cell(28.1,0.6,'Print Date: '.$print_date,0,0,'R');// added by NB 20.08.2014
			$pdf->Ln();// added by NB 20.08.2014
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(10.5,0.6,$compName.' (Palm Oil)',1,0,'L');
			$pdf->Cell(17.5,0.6,'LAPORAN HARIAN MANDOR PANEN',1,0,'C');
			$pdf->Ln();
			
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(4,0.4,'Tanggal Panen','L',0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(5,0.4,$tglPanen[$j],0,0,'L');
			$pdf->Cell(1,0.4,'','R',0,'L');
			$pdf->Cell(3.5,0.4,'','',0,'C');
			$pdf->Cell(3,0.4,'Estate',0,0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(10.5,0.4,$IDEstate,'R',0,'L');	
			$pdf->Ln();
			
			$pdf->Cell(4,0.4,'NIK Mandor Panen','L',0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(5,0.4,$NIKMandor[$j],0,0,'L');
			$pdf->Cell(1,0.4,'','R',0,'L');
			$pdf->Cell(3.5,0.4,'','',0,'C');
			$pdf->Cell(3,0.4,'Divisi/Afdeling',0,0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(10.5,0.4,$IDAfd[$j],'R',0,'L');
			$pdf->Ln();
			
			$pdf->Cell(4,0.4,'Nama Mandor Panen','L',0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(5,0.4,$NamaMandor[$j],0,0,'L');
			$pdf->Cell(1,0.4,'','R',0,'L');
			$pdf->Cell(3.5,0.4,'','',0,'C');
			$pdf->Cell(3,0.4,'Page',0,0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(10.5,0.4,$page_header.' dari '.$to_page ,'R',0,'L');
			$pdf->Ln();
			
			$pdf->Cell(9.5,0.4,'','L',0,'L');
			$pdf->Cell(1,0.4,'','R',0,'L');
			$pdf->Cell(3.5,0.4,'','',0,'C');
			$pdf->Cell(14,0.4,'','R',0,'L');
			
			$pdf->Ln();
			$pdf->SetFont('Arial','B',8);
			
			$pdf->Cell(3,0.5,'AFD KARY',1,0,'C');
			$pdf->Cell(3.1,0.5,'NIK',1,0,'C');
			$pdf->Cell(4.4,0.5,'Nama Karyawan',1,0,'C');
			$pdf->Cell(1,0.5,'',1,0,'C',true);//
			$pdf->Cell(0.5,0.5,'',1,0,'C',true);//
			$pdf->Cell(1.1,0.5,'HA',1,0,'C');
			$pdf->Cell(1,0.5,'Jam',1,0,'C');
			$pdf->Cell(2,0.5,'Hasil Panen',1,0,'C');
			$pdf->Cell(8.4,0.5,'Pinalty',1,0,'C');
			$pdf->Cell(2.2,0.5,'Telah Diperiksa',1,0,'C');
			$pdf->Cell(1.3,0.5,'Cust',1,0,'C');
			
			$pdf->Ln();
			$pdf->Cell(3,0.5,'',1,0,'C',true);//
			$pdf->Cell(3.1,0.5,'',1,0,'C',true);//
			$pdf->Cell(4.4,0.5,'',1,0,'C',true);//
			$pdf->Cell(1,0.5,'',1,0,'C',true);//
			$pdf->Cell(0.5,0.5,'',1,0,'C',true);//
			$pdf->Cell(1.1,0.5,'',1,0,'C',true);//
			$pdf->Cell(1,0.5,'Kerja',1,0,'C');
			$pdf->Cell(1,0.5,'TBS',1,0,'C');
			$pdf->Cell(1,0.5,'BRD',1,0,'C');
			$pdf->Cell(0.7,0.5,'BM',1,0,'C');
			$pdf->Cell(0.7,0.5,'BK',1,0,'C');
			$pdf->Cell(0.7,0.5,'TP',1,0,'C');
			$pdf->Cell(0.7,0.5,'BB',1,0,'C');
			$pdf->Cell(0.7,0.5,'JK',1,0,'C');
			$pdf->Cell(0.7,0.5,'BA',1,0,'C');
			$pdf->Cell(0.7,0.5,'BT',1,0,'C');
			$pdf->Cell(0.7,0.5,'BL',1,0,'C');
			$pdf->Cell(0.7,0.5,'PB',1,0,'C');
			$pdf->Cell(0.7,0.5,'AB',1,0,'C');
			$pdf->Cell(0.7,0.5,'SF',1,0,'C');
			$pdf->Cell(0.7,0.5,'BS',1,0,'C');
			$pdf->Cell(2.5,0.5,'',1,0,'C',true);
			$pdf->Cell(1,0.5,'',1,0,'C',true);
			$pdf->Ln();
			
			$nik = "";
			
			$row_array = 0;
			$pagebreak1 = 19;
			$jmlbaris = 0;
			$pdf->SetFont('Arial','',6.5);// added by NB 20.08.2014
			
			$total_HA = 0;
			$total_TBS = 0;
			$total_BRD = 0;
			$total_BM = 0;
			$total_BK = 0;
			$total_TP = 0;
			$total_BB = 0;
			$total_JK = 0;
			$total_BA = 0;
			$total_BT = 0;
			$total_BL = 0;
			$total_PB = 0;
			$total_AB = 0;
			$total_SF = 0;
			$total_BS = 0;
			$input_manual = 0;
			$koreksi_lokasi = 0;

			//while (($data1 = oci_fetch_array($stid1, OCI_ASSOC))) 
			for ($clap = 0; $clap < $laporan_count; $clap++)
			{
				$jmlbaris += 1;
				if($jmlbaris > $pagebreak1){
					$page_header++;
					
					$pdf->AddPage(); 
					$jmlbaris = 0;
					
					$pdf->SetFont('Arial','B',8); // added by NB 20.08.2014
					$pdf->Cell(28.1,0.6,'Print Date: '.$print_date,0,0,'R');// added by NB 20.08.2014
					$pdf->Ln();// added by NB 20.08.2014
					$pdf->SetFont('Arial','B',12);
					$pdf->Cell(10.5,0.6,$compName.' (Palm Oil)',1,0,'L');
					$pdf->Cell(17.5,0.6,'LAPORAN HARIAN MANDOR PANEN',1,0,'C');
					$pdf->Ln();
					
					$pdf->SetFont('Arial','',9);
					$pdf->Cell(4,0.4,'Tanggal Panen','L',0,'L');
					$pdf->Cell(0.5,0.4,':',0,0,'L');
					$pdf->Cell(5,0.4,$tglPanen[$j],0,0,'L');
					$pdf->Cell(1,0.4,'','R',0,'L');
					$pdf->Cell(3.5,0.4,'','',0,'C');
					$pdf->Cell(3,0.4,'Estate',0,0,'L');
					$pdf->Cell(0.5,0.4,':',0,0,'L');
					$pdf->Cell(10.5,0.4,$IDEstate,'R',0,'L');	
					$pdf->Ln();
					
					$pdf->Cell(4,0.4,'NIK Mandor Panen','L',0,'L');
					$pdf->Cell(0.5,0.4,':',0,0,'L');
					$pdf->Cell(5,0.4,$NIKMandor[$j],0,0,'L');
					$pdf->Cell(1,0.4,'','R',0,'L');
					$pdf->Cell(3.5,0.4,'','',0,'C');
					$pdf->Cell(3,0.4,'Divisi/Afdeling',0,0,'L');
					$pdf->Cell(0.5,0.4,':',0,0,'L');
					$pdf->Cell(10.5,0.4,$IDAfd[$j],'R',0,'L');
					$pdf->Ln();
					
					$pdf->Cell(4,0.4,'Nama Mandor Panen','L',0,'L');
					$pdf->Cell(0.5,0.4,':',0,0,'L');
					$pdf->Cell(5,0.4,$NamaMandor[$j],0,0,'L');
					$pdf->Cell(1,0.4,'','R',0,'L');
					$pdf->Cell(3.5,0.4,'','',0,'C');
					$pdf->Cell(3,0.4,'Page',0,0,'L');
					$pdf->Cell(0.5,0.4,':',0,0,'L');
					$pdf->Cell(10.5,0.4,$page_header.' dari '.$to_page ,'R',0,'L');
					$pdf->Ln();
					
					$pdf->Cell(9.5,0.4,'','L',0,'L');
					$pdf->Cell(1,0.4,'','R',0,'L');
					$pdf->Cell(3.5,0.4,'','',0,'C');
					$pdf->Cell(14,0.4,'','R',0,'L');
					
					$pdf->Ln();
					$pdf->SetFont('Arial','B',8);
					
					$pdf->Cell(3,0.5,'AFD KARY',1,0,'C');
					$pdf->Cell(3.1,0.5,'NIK',1,0,'C');
					$pdf->Cell(4.4,0.5,'Nama Karyawan',1,0,'C');
					$pdf->Cell(1,0.5,'',1,0,'C',true);//
					$pdf->Cell(0.5,0.5,'',1,0,'C',true);//
					$pdf->Cell(1.1,0.5,'HA',1,0,'C');
					$pdf->Cell(1,0.5,'Jam',1,0,'C');
					$pdf->Cell(2,0.5,'Hasil Panen',1,0,'C');
					$pdf->Cell(8.4,0.5,'Pinalty',1,0,'C');
					$pdf->Cell(2.2,0.5,'Telah Diperiksa',1,0,'C');
					$pdf->Cell(1.3,0.5,'Cust',1,0,'C');
					
					$pdf->Ln();
					$pdf->Cell(3,0.5,'',1,0,'C',true);//
					$pdf->Cell(3.1,0.5,'',1,0,'C',true);//
					$pdf->Cell(4.4,0.5,'',1,0,'C',true);//
					$pdf->Cell(1,0.5,'',1,0,'C',true);//
					$pdf->Cell(0.5,0.5,'',1,0,'C',true);//
					$pdf->Cell(1.1,0.5,'',1,0,'C',true);//
					$pdf->Cell(1,0.5,'Kerja',1,0,'C');
					$pdf->Cell(1,0.5,'TBS',1,0,'C');
					$pdf->Cell(1,0.5,'BRD',1,0,'C');
					$pdf->Cell(0.7,0.5,'BM',1,0,'C');
					$pdf->Cell(0.7,0.5,'BK',1,0,'C');
					$pdf->Cell(0.7,0.5,'TP',1,0,'C');
					$pdf->Cell(0.7,0.5,'BB',1,0,'C');
					$pdf->Cell(0.7,0.5,'JK',1,0,'C');
					$pdf->Cell(0.7,0.5,'BA',1,0,'C');
					$pdf->Cell(0.7,0.5,'BT',1,0,'C');
					$pdf->Cell(0.7,0.5,'BL',1,0,'C');
					$pdf->Cell(0.7,0.5,'PB',1,0,'C');
					$pdf->Cell(0.7,0.5,'AB',1,0,'C');
					$pdf->Cell(0.7,0.5,'SF',1,0,'C');
					$pdf->Cell(0.7,0.5,'BS',1,0,'C');
					$pdf->Cell(2.5,0.5,'',1,0,'C',true);
					$pdf->Cell(1,0.5,'',1,0,'C',true);
					$pdf->Ln();
					
				}
				$pdf->SetFont('Arial','',6.5);// added by NB 20.08.2014
				$pdf->Cell(3,0.5,$laporan_result[$clap]["AFD_PEMANEN"],1,0,'C');
				$pdf->Cell(3.1,0.5,$laporan_result[$clap]["NIK_PEMANEN"],1,0,'L');
				$pdf->Cell(4.4,0.5,$laporan_result[$clap]["NAMA_PEMANEN"],1,0,'C');
				$pdf->Cell(1,0.5,'',1,0,'C',true);//
				$pdf->Cell(0.5,0.5,'',1,0,'C',true);//
				$pdf->Cell(1.1,0.5,number_format($laporan_result[$clap]["LUASAN_PANEN"],2),1,0,'C');
				$pdf->Cell(1,0.5,'',1,0,'C');
				$pdf->Cell(1,0.5,$laporan_result[$clap]["TBS"],1,0,'C');
				$pdf->Cell(1,0.5,$laporan_result[$clap]["BRD"],1,0,'C');
				$pdf->Cell(0.7,0.5,$laporan_result[$clap]['BM'],1,0,'C');
				$pdf->Cell(0.7,0.5,$laporan_result[$clap]['BK'],1,0,'C');
				$pdf->Cell(0.7,0.5,$laporan_result[$clap]['TP'],1,0,'C');
				$pdf->Cell(0.7,0.5,$laporan_result[$clap]['BB'],1,0,'C');
				$pdf->Cell(0.7,0.5,$laporan_result[$clap]['JK'],1,0,'C');
				$pdf->Cell(0.7,0.5,$laporan_result[$clap]['BA'],1,0,'C');
				$pdf->Cell(0.7,0.5,$laporan_result[$clap]['BT'],1,0,'C');
				$pdf->Cell(0.7,0.5,$laporan_result[$clap]['BL'],1,0,'C');
				$pdf->Cell(0.7,0.5,$laporan_result[$clap]['PB'],1,0,'C');
				$pdf->Cell(0.7,0.5,$laporan_result[$clap]['AB'],1,0,'C');
				$pdf->Cell(0.7,0.5,$laporan_result[$clap]['SF'],1,0,'C');
				$pdf->Cell(0.7,0.5,$laporan_result[$clap]['BS'],1,0,'C');
				$pdf->Cell(1.1,0.5,'','BR',0,'L');
				$pdf->Cell(1.1,0.5,'','BR',0,'L');
				$pdf->Cell(1.3,0.5,$laporan_result[$clap]['CUSTOMER'],1,0,'C');
				$pdf->Ln();
				
				$total_HA += $laporan_result[$clap]["LUASAN_PANEN"];
				$total_TBS += $laporan_result[$clap]["TBS"];
				$total_BRD += $laporan_result[$clap]["BRD"];
				$total_BM += $laporan_result[$clap]["BM"];
				$total_BK += $laporan_result[$clap]["BK"];
				$total_TP += $laporan_result[$clap]["TP"];
				$total_BB += $laporan_result[$clap]["BB"];
				$total_JK += $laporan_result[$clap]["JK"];
				$total_BA += $laporan_result[$clap]["BA"];
				$total_BT += $laporan_result[$clap]["BT"];
				$total_BL += $laporan_result[$clap]["BL"];
				$total_PB += $laporan_result[$clap]["PB"];
				$total_AB += $laporan_result[$clap]["AB"];
				$total_SF += $laporan_result[$clap]["SF"];
				$total_BS += $laporan_result[$clap]["BS"];

				$input_manual = $input_manual + $laporan_result[$clap]["STATUS_INPUT_MANUAL"];
				$koreksi_lokasi = $koreksi_lokasi + $laporan_result[$clap]["STATUS_KOREKSI_LOKASI"];
			}
			/*end added by NB 20.08.2014*/
			
			//Total
			$pdf->Cell(12,0.5,'TOTAL',1,0,'C');
			$pdf->Cell(1.1,0.5,number_format($total_HA,2),1,0,'C');
			$pdf->Cell(1,0.5,'',1,0,'C');
			$pdf->Cell(1,0.5,$total_TBS,1,0,'C');
			$pdf->Cell(1,0.5,$total_BRD,1,0,'C');
			
			$pdf->Cell(0.7,0.5,$total_BM,1,0,'C');
			$pdf->Cell(0.7,0.5,$total_BK,1,0,'C');
			$pdf->Cell(0.7,0.5,$total_TP,1,0,'C');
			$pdf->Cell(0.7,0.5,$total_BB,1,0,'C');
			$pdf->Cell(0.7,0.5,$total_JK,1,0,'C');
			$pdf->Cell(0.7,0.5,$total_BA,1,0,'C');
			$pdf->Cell(0.7,0.5,$total_BT,1,0,'C');
			$pdf->Cell(0.7,0.5,$total_BL,1,0,'C');
			$pdf->Cell(0.7,0.5,$total_PB,1,0,'C');
			$pdf->Cell(0.7,0.5,$total_AB,1,0,'C');
			$pdf->Cell(0.7,0.5,$total_SF,1,0,'C');
			$pdf->Cell(0.7,0.5,$total_BS,1,0,'C');
			
			$pdf->Cell(1.1,0.5,'','BR',0,'L');
			$pdf->Cell(1.1,0.5,'','BR',0,'L');
			$pdf->Cell(1.3,0.5,'','BR',0,'L');
			$pdf->Ln();
			//End Total

			// Start - Total EBCC - Input Manual
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(10.5, 0.5,'TOTAL EBCC - INPUT MANUAL', 1, 0, 'C');
			$pdf->SetFont('Arial','', 6.5);
			$pdf->Cell(1.5, 0.5, $input_manual, 1, 0, 'C');
			$pdf->Cell(1.1, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(1, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(2, 0.5, '', 1, 0, 'C', true);

			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			
			$pdf->Cell(1.1,0.5,'','BR',0,'L');
			$pdf->Cell(1.1,0.5,'','BR',0,'L');
			$pdf->Cell(1.3,0.5,'','BR',0,'L', true);
			$pdf->Ln();
			// End - Total EBCC - Input Manual

			// Start - Total EBCC - Lokasi Salah
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(10.5, 0.5,'TOTAL EBCC - LOKASI SALAH', 1, 0, 'C');
			$pdf->SetFont('Arial','', 6.5);
			$pdf->Cell(1.5, 0.5, $koreksi_lokasi, 1, 0, 'C');
			$pdf->Cell(1.1, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(1, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(2, 0.5, '', 1, 0, 'C', true);

			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			$pdf->Cell(0.7, 0.5, '', 1, 0, 'C', true);
			
			$pdf->Cell(1.1,0.5,'','BR',0,'L');
			$pdf->Cell(1.1,0.5,'','BR',0,'L');
			$pdf->Cell(1.3,0.5,'','BR',0,'L', true);
			$pdf->Ln();
			// End - Total EBCC - Lokasi Salah

			$pdf->SetFont('Arial','B',6.5);
			$pdf->Cell(3,0.5,'Dibuat Oleh',1,0,'C');
			$pdf->Cell(7.5,0.5,'Diperiksa Oleh',1,0,'C');
			$pdf->Cell(3.6,0.5,'Disetujui Oleh',1,0,'C');
			$pdf->Cell(3,0.5,'Disetujui Oleh',1,0,'C');
			$pdf->Cell(10.9,0.5,'Keterangan',1,0,'C');
			
			$pdf->Ln();
			$pdf->Cell(3,0.5,'','LR',0,'L');
			$pdf->Cell(7.5,0.5,'','LR',0,'L');
			$pdf->Cell(3.6,0.5,'','LR',0,'L');
			$pdf->Cell(3,0.5,'','LR',0,'L');
			$pdf->Cell(0.8,0.5,'','L',0,'L');
			$pdf->Cell(4.5,0.3,'BM : Buah Mentah',0,0,'L');
			$pdf->Cell(5.6,0.3,'BL : Buah Tinggal (Piringan/Pasar Pikul)','R',0,'L');$pdf->Ln();
			$pdf->Cell(3,0.5,'','LR',0,'L');
			$pdf->Cell(7.5,0.5,'','LR',0,'L');
			$pdf->Cell(3.6,0.5,'','LR',0,'L');
			$pdf->Cell(3,0.5,'','LR',0,'L');
			$pdf->Cell(0.8,0.5,'','L',0,'L');
			$pdf->Cell(4.5,0.3,'BK : Buah Mengkal',0,0,'L');
			$pdf->Cell(5.6,0.3,'PB : Pinalti Brondolan (Piringan)','R',0,'L');$pdf->Ln();
			$pdf->Cell(3,0.5,'','LR',0,'L');
			$pdf->Cell(7.5,0.5,'','LR',0,'L');
			$pdf->Cell(3.6,0.5,'','LR',0,'L');
			$pdf->Cell(3,0.5,'','LR',0,'L');
			$pdf->Cell(0.8,0.5,'','L',0,'L');
			$pdf->Cell(4.5,0.3,'TP : Tangkai Panjang',0,0,'L');
			$pdf->Cell(5.6,0.3,'AB : Tidak ada Alas Brondolan (Per TPH)','R',0,'L');$pdf->Ln();
			$pdf->Cell(3,0.5,'','LR',0,'L');
			$pdf->Cell(7.5,0.5,'','LR',0,'L');
			$pdf->Cell(3.6,0.5,'','LR',0,'L');
			$pdf->Cell(3,0.5,'','LR',0,'L');
			$pdf->Cell(0.8,0.5,'','L',0,'L');
			$pdf->Cell(4.5,0.3,'BB : Buah Busuk',0,0,'L');
			$pdf->Cell(5.6,0.3,'SF : Buah Matahari','R',0,'L');$pdf->Ln();
			$pdf->Cell(3,0.5,'','LR',0,'L');
			$pdf->Cell(7.5,0.5,'','LR',0,'L');
			$pdf->Cell(3.6,0.5,'','LR',0,'L');
			$pdf->Cell(3,0.5,'','LR',0,'L');
			$pdf->Cell(0.8,0.5,'','L',0,'L');
			$pdf->Cell(4.5,0.3,'JK : Janjang Kosong',0,0,'L');
			$pdf->Cell(5.6,0.3,'BS : Buah Sakit','R',0,'L');$pdf->Ln();
			$pdf->Cell(3,0.5,'','LR',0,'L');
			$pdf->Cell(7.5,0.5,'','LR',0,'L');
			$pdf->Cell(3.6,0.5,'','LR',0,'L');
			$pdf->Cell(3,0.5,'','LR',0,'L');
			$pdf->Cell(0.8,0.5,'','L',0,'L');
			$pdf->Cell(4.5,0.3,'BT : Buah Tinggal di Pokok',0,0,'L');
			$pdf->Cell(5.6,0.3,'BA : Buah Aborsi','R',0,'L');
			
			$pdf->Ln();
			$pdf->Cell(3,0.5,'Mandor Panen',1,0,'C');
			$pdf->Cell(7.5,0.5,'Mandor 1 & Asisten Lapangan',1,0,'C');
			$pdf->Cell(3.6,0.5,'Kepala Kebun',1,0,'C');
			$pdf->Cell(3,0.5,'Estate Manager',1,0,'C');
			$pdf->Cell(10.9,0.5,'','BR',0,'L');
			
			
			//LAMPIRAN DISINI
			$page_lampiran = 1;
			
			$pdf->AddPage(); 
		
			$pdf->SetFont('Arial','B',8);// added by NB 20.08.2014
			$pdf->Cell(28.1,0.6,'Print Date: '.$print_date,0,0,'R');// added by NB 20.08.2014
			$pdf->Ln();// added by NB 20.08.2014
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(11.6,0.6,$compName.' (Palm Oil)',1,0,'L');
			$pdf->Cell(16.4,0.6,'LAMPIRAN',1,0,'C');
			$pdf->Ln();
			
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(4,0.4,'Tanggal Panen','L',0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(7.1,0.4,$tglPanen[$j],0,0,'L');
			$pdf->Cell(1.9,0.4,'','L',0,'L');
			$pdf->Cell(3,0.4,'Estate',0,0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(11,0.4,$IDEstate,'R',0,'L');
			$pdf->Ln();
			$pdf->Cell(4,0.4,'NIK Mandor Panen','L',0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(7.1,0.4,$NIKMandor[$j],0,0,'L');
			$pdf->Cell(1.9,0.4,'','L',0,'L');
			$pdf->Cell(3,0.4,'Divisi/Afdeling',0,0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(11,0.4,$IDAfd[$j],'R',0,'L');
			$pdf->Ln();
			$pdf->Cell(4,0.4,'Nama Mandor Panen','L',0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(7.1,0.4,$NamaMandor[$j],0,0,'L');
			$pdf->Cell(1.9,0.4,'','L',0,'L');
			$pdf->Cell(3,0.4,'Page',0,0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(11,0.4,$page_lampiran.' dari '.ceil((($j+1)-$start_lampiran+$totalBlok)/20),'R',0,'L');
			//$pdf->Cell(11,0.4,$page_lampiran.' dari '.($j+1)."-".$start_lampiran." ".(($j+1)-$start_lampiran)/20,'R',0,'L');
			$pdf->Ln();
			
			$pdf->Cell(10.6,0.4,'','L',0,'L');
			$pdf->Cell(1,0.4,'','R',0,'L');
			$pdf->Cell(3.5,0.4,'','',0,'C');
			$pdf->Cell(12.9,0.4,'','R',0,'L');
			$pdf->Ln();
			
			//bagian untuk memasukkan keterangan tabel
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(0.6,0.5,'No.',1,0,'C');
			$pdf->Cell(1,0.5,'AFD',1,0,'C');
			$pdf->Cell(2.2,0.5,'NIK',1,0,'C');
			$pdf->Cell(4.5,0.5,'Nama Karyawan',1,0,'C');
			$pdf->Cell(3,0.5,'OPH/BCC',1,0,'C');
			$pdf->Cell(0.8,0.5,'TPH',1,0,'C');
			$pdf->Cell(0.8,0.5,'Blok',1,0,'C');
			$pdf->Cell(0.8,0.5,'Desc',1,0,'C');
			$pdf->Cell(0.8,0.5,'HA',1,0,'C');
			//$pdf->Cell(0.8,0.5,'Jam',1,0,'C');
			$pdf->Cell(1,0.5,'Manual',1,0,'C');
			$pdf->Cell(1,0.5,'Lokasi',1,0,'C');
			$pdf->Cell(2,0.5,'Hasil Panen',1,0,'C');
			$pdf->Cell(8.4,0.5,'Pinalty',1,0,'C');
			//$pdf->Cell(1,0.5,'Kode',1,0,'C');
			$pdf->Cell(1.1,0.5,'Cust',1,0,'C');
			$pdf->Ln();
			$pdf->Cell(0.6,0.5,'',1,0,'C',true);
			$pdf->Cell(1,0.5,'KARY',1,0,'C');
			$pdf->Cell(2.2,0.5,'',1,0,'C',true);
			$pdf->Cell(4.5,0.5,'',1,0,'C',true);
			$pdf->Cell(3,0.5,'',1,0,'C',true);
			$pdf->Cell(0.8,0.5,'',1,0,'C',true);
			$pdf->Cell(0.8,0.5,'',1,0,'C',true);
			$pdf->Cell(0.8,0.5,'',1,0,'C',true);
			$pdf->Cell(0.8,0.5,'',1,0,'C',true);
			//$pdf->Cell(0.8,0.5,'Kerja',1,0,'C');
			$pdf->Cell(1,0.5,'',1,0,'C');
			$pdf->Cell(1,0.5,'Salah',1,0,'C');
			$pdf->Cell(1,0.5,'TBS',1,0,'C');
			$pdf->Cell(1,0.5,'BRD',1,0,'C');
			$pdf->Cell(0.7,0.5,'BM',1,0,'C');
			$pdf->Cell(0.7,0.5,'BK',1,0,'C');
			$pdf->Cell(0.7,0.5,'TP',1,0,'C');
			$pdf->Cell(0.7,0.5,'BB',1,0,'C');
			$pdf->Cell(0.7,0.5,'JK',1,0,'C');
			$pdf->Cell(0.7,0.5,'BA',1,0,'C');
			$pdf->Cell(0.7,0.5,'BT',1,0,'C');
			$pdf->Cell(0.7,0.5,'BL',1,0,'C');
			$pdf->Cell(0.7,0.5,'PB',1,0,'C');
			$pdf->Cell(0.7,0.5,'AB',1,0,'C');
			$pdf->Cell(0.7,0.5,'SF',1,0,'C');
			$pdf->Cell(0.7,0.5,'BS',1,0,'C');
			//$pdf->Cell(1,0.5,'Absen',1,0,'C');
			$pdf->Cell(1.1,0.5,'',1,0,'C',true);
			$pdf->Ln();
			$pdf->Cell(28,0.1,'',1,0,'C');
			$pdf->Ln();
			
			$nomor = 0;
			$ctr = 0;
			for($x=$start_lampiran;$x<=$j;$x++){
				$nomor++;
				$pdf->setFillColor(0,0,0);
				if($ctr==$pagebreak && $j+1<=$i){
					$page_lampiran++;
					
					$pdf->AddPage(); 
		
					$pdf->SetFont('Arial','B',8);// added by NB 20.08.2014
					$pdf->Cell(28.1,0.6,'Print Date: '.$print_date,0,0,'R');// added by NB 20.08.2014
					$pdf->Ln();// added by NB 20.08.2014
					$pdf->SetFont('Arial','B',12);
					$pdf->Cell(11.6,0.6,$compName.' (Palm Oil)',1,0,'L');
					$pdf->Cell(16.4,0.6,'LAMPIRAN',1,0,'C');
					$pdf->Ln();
					
					$pdf->SetFont('Arial','',9);
					$pdf->Cell(4,0.4,'Tanggal Panen','L',0,'L');
					$pdf->Cell(0.5,0.4,':',0,0,'L');
					$pdf->Cell(7.1,0.4,$tglPanen[$j],0,0,'L');
					$pdf->Cell(1.9,0.4,'','L',0,'L');
					$pdf->Cell(3,0.4,'Estate',0,0,'L');
					$pdf->Cell(0.5,0.4,':',0,0,'L');
					$pdf->Cell(11,0.4,$IDEstate,'R',0,'L');
					$pdf->Ln();
					$pdf->Cell(4,0.4,'NIK Mandor Panen','L',0,'L');
					$pdf->Cell(0.5,0.4,':',0,0,'L');
					$pdf->Cell(7.1,0.4,$NIKMandor[$j],0,0,'L');
					$pdf->Cell(1.9,0.4,'','L',0,'L');
					$pdf->Cell(3,0.4,'Divisi/Afdeling',0,0,'L');
					$pdf->Cell(0.5,0.4,':',0,0,'L');
					$pdf->Cell(11,0.4,$IDAfd[$j],'R',0,'L');
					$pdf->Ln();
					$pdf->Cell(4,0.4,'Nama Mandor Panen','L',0,'L');
					$pdf->Cell(0.5,0.4,':',0,0,'L');
					$pdf->Cell(7.1,0.4,$NamaMandor[$j],0,0,'L');
					$pdf->Cell(1.9,0.4,'','L',0,'L');
					$pdf->Cell(3,0.4,'Page',0,0,'L');
					$pdf->Cell(0.5,0.4,':',0,0,'L');
					$pdf->Cell(11,0.4,$page_lampiran.' dari '.ceil((($j+1)-$start_lampiran+$totalBlok)/20),'R',0,'L');
					$pdf->Ln();
					
					$pdf->Cell(10.6,0.4,'','L',0,'L');
					$pdf->Cell(1,0.4,'','R',0,'L');
					$pdf->Cell(3.5,0.4,'','',0,'C');
					$pdf->Cell(12.9,0.4,'','R',0,'L');
					$pdf->Ln();
					
					//bagian untuk memasukkan keterangan tabel
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(0.6,0.5,'No.',1,0,'C');
					$pdf->Cell(1,0.5,'AFD',1,0,'C');
					$pdf->Cell(2.2,0.5,'NIK',1,0,'C');
					$pdf->Cell(4.5,0.5,'Nama Karyawan',1,0,'C');
					$pdf->Cell(3,0.5,'OPH/BCC',1,0,'C');
					$pdf->Cell(0.8,0.5,'TPH',1,0,'C');
					$pdf->Cell(0.8,0.5,'Blok',1,0,'C');
					$pdf->Cell(0.8,0.5,'Desc',1,0,'C');
					$pdf->Cell(0.8,0.5,'HA',1,0,'C');
					$pdf->Cell(0.8,0.5,'Jam',1,0,'C');
					$pdf->Cell(2,0.5,'Hasil Panen',1,0,'C');
					$pdf->Cell(8.4,0.5,'Pinalty',1,0,'C');
					$pdf->Cell(1,0.5,'Kode',1,0,'C');
					$pdf->Cell(1.3,0.5,'Cust',1,0,'C');
					$pdf->Ln();
					$pdf->Cell(0.6,0.5,'',1,0,'C',true);
					$pdf->Cell(1,0.5,'KARY',1,0,'C');
					$pdf->Cell(2.2,0.5,'',1,0,'C',true);
					$pdf->Cell(4.5,0.5,'',1,0,'C',true);
					$pdf->Cell(3,0.5,'',1,0,'C',true);
					$pdf->Cell(0.8,0.5,'',1,0,'C',true);
					$pdf->Cell(0.8,0.5,'',1,0,'C',true);
					$pdf->Cell(0.8,0.5,'',1,0,'C',true);
					$pdf->Cell(0.8,0.5,'',1,0,'C',true);
					$pdf->Cell(0.8,0.5,'Kerja',1,0,'C');
					$pdf->Cell(1,0.5,'TBS',1,0,'C');
					$pdf->Cell(1,0.5,'BRD',1,0,'C');
					$pdf->Cell(0.7,0.5,'BM',1,0,'C');
					$pdf->Cell(0.7,0.5,'BK',1,0,'C');
					$pdf->Cell(0.7,0.5,'TP',1,0,'C');
					$pdf->Cell(0.7,0.5,'BB',1,0,'C');
					$pdf->Cell(0.7,0.5,'JK',1,0,'C');
					$pdf->Cell(0.7,0.5,'BA',1,0,'C');
					$pdf->Cell(0.7,0.5,'BT',1,0,'C');
					$pdf->Cell(0.7,0.5,'BL',1,0,'C');
					$pdf->Cell(0.7,0.5,'PB',1,0,'C');
					$pdf->Cell(0.7,0.5,'AB',1,0,'C');
					$pdf->Cell(0.7,0.5,'SF',1,0,'C');
					$pdf->Cell(0.7,0.5,'BS',1,0,'C');
					$pdf->Cell(1,0.5,'Absen',1,0,'C');
					$pdf->Cell(1.3,0.5,'',1,0,'C',true);
					$pdf->Ln();
					$pdf->Cell(28,0.1,'',1,0,'C');
					$pdf->Ln();
					
					$ctr=0;
				}
				$ctr++;
				$pdf->SetFont('Arial','',6.5);
				
				//If duplicate : BA + AFD + Block + NIK Pemanen + Tanggal Panen + Delivery Ticket + Jumlah Janjang
				if($x==0){
					if($IDBa[$x]==$IDBa[$x+1] && $IDAfd[$x]==$IDAfd[$x+1] && $cell[$x][5]==$cell[$x+1][5] 
					&& $tglPanen[$x]==$tglPanen[$x+1] && $KodeDeliveryTicket[$x]==$KodeDeliveryTicket[$x+1] 
					&& $cell[$x][11]==$cell[$x+1][11]
					&& $cell[$x][12]==$cell[$x+1][12]
					&& $cell[$x][14]==$cell[$x+1][14]
					&& $cell[$x][15]==$cell[$x+1][15]
					&& $cell[$x][24]==$cell[$x+1][24]
					&& $cell[$x][27]==$cell[$x+1][27]
					&& $cell[$x][28]==$cell[$x+1][28]
					){
						$pdf->setFillColor(204,204,204);
						$fill_set = 1;
					} else {
						$fill_set = 0;
					}
					
				} else {
					if(($IDBa[$x]==$IDBa[$x+1] && $IDAfd[$x]==$IDAfd[$x+1] && $cell[$x][5]==$cell[$x+1][5] 
					&& $tglPanen[$x]==$tglPanen[$x+1] && $KodeDeliveryTicket[$x]==$KodeDeliveryTicket[$x+1] 
					&& $cell[$x][11]==$cell[$x+1][11]
					&& $cell[$x][12]==$cell[$x+1][12]
					&& $cell[$x][14]==$cell[$x+1][14]
					&& $cell[$x][15]==$cell[$x+1][15]
					&& $cell[$x][24]==$cell[$x+1][24]
					&& $cell[$x][27]==$cell[$x+1][27]
					&& $cell[$x][28]==$cell[$x+1][28]
					) 
					or ($IDBa[$x]==$IDBa[$x-1] && $IDAfd[$x]==$IDAfd[$x-1] && $cell[$x][5]==$cell[$x-1][5] 
					&& $tglPanen[$x]==$tglPanen[$x-1] && $KodeDeliveryTicket[$x]==$KodeDeliveryTicket[$x-1] 
					&& $cell[$x][11]==$cell[$x-1][11]
					&& $cell[$x][12]==$cell[$x-1][12]
					&& $cell[$x][14]==$cell[$x-1][14]
					&& $cell[$x][15]==$cell[$x-1][15]
					&& $cell[$x][24]==$cell[$x-1][24]
					&& $cell[$x][27]==$cell[$x-1][27]
					&& $cell[$x][28]==$cell[$x-1][28]
					)){
						$pdf->setFillColor(204,204,204);
						$fill_set = 1;
					} else {
						$fill_set = 0;
					}
				}
				
				
				$pdf->Cell(0.6,0.5,$nomor,1,0,'C',$fill_set);
				$pdf->Cell(1,0.5,$cell[$x][25],1,0,'C',$fill_set);
				$pdf->Cell(2.2,0.5,$cell[$x][1],1,0,'L',$fill_set);
				$pdf->Cell(4.5,0.5,$cell[$x][2],1,0,'C',$fill_set);
				$pdf->Cell(3,0.5,separator($cell[$x][3]),1,0,'C',$fill_set);
				$pdf->Cell(0.8,0.5,$cell[$x][4],1,0,'C',$fill_set);
				$pdf->Cell(0.8,0.5,$cell[$x][5],1,0,'C',$fill_set);
				$pdf->Cell(0.8,0.5,$blok_name[$x],1,0,'C',$fill_set);
				
				if($x == 0)
				{
					$pdf->Cell(0.8,0.5,$cell[$x][6],1,0,'C',$fill_set);
				}
				else
				{
					//Edited by Ardo, 22-09-2016 : CR Synchronize EBCC perubahan perhitungan Luasan Panen
					if($tglPanen[$x]!=$tglPanen[$x-1] || 
					$IDAfd[$x]!=$IDAfd[$x-1] || 
					$cell[$x][5]!=$cell[$x-1][5] || 
					$cell[$x][1]!=$cell[$x-1][1] || $NIKMandor[$x]!=$NIKMandor[$x-1]
					)
					{
						$pdf->Cell(0.8,0.5,$cell[$x][6],1,0,'C',$fill_set);
					}
					/* if($no_rekap_bcc[$x] !== $no_rekap_bcc[$x-1])
					{
						$pdf->Cell(0.8,0.5,$cell[$x][6],1,0,'C',$fill_set);
					}
					else if($id_rencana[$x] !== $id_rencana[$x-1])
					{
						$pdf->Cell(0.8,0.5,$cell[$x][6],1,0,'C',$fill_set);
					} */
					else
					{
						$pdf->Cell(0.8,0.5,'-',1,0,'C',$fill_set);
					}
				}
				// untuk input manual
				$pdf->Cell(1,0.5,$cell[$x][7],1,0,'C',$fill_set);
				// untuk koreksi salah
				$pdf->Cell(1,0.5,$cell[$x][8],1,0,'C',$fill_set);
				$pdf->Cell(1,0.5,$cell[$x][9],1,0,'C',$fill_set);
				$pdf->Cell(1,0.5,$cell[$x][10],1,0,'C',$fill_set);
				$pdf->Cell(0.7,0.5,$cell[$x][11],1,0,'C',$fill_set);
				$pdf->Cell(0.7,0.5,$cell[$x][12],1,0,'C',$fill_set);
				$pdf->Cell(0.7,0.5,$cell[$x][13],1,0,'C',$fill_set);
				$pdf->Cell(0.7,0.5,$cell[$x][14],1,0,'C',$fill_set);
				$pdf->Cell(0.7,0.5,$cell[$x][15],1,0,'C',$fill_set);
				$pdf->Cell(0.7,0.5,$cell[$x][24],1,0,'C',$fill_set);
				$pdf->Cell(0.7,0.5,$cell[$x][16],1,0,'C',$fill_set);
				$pdf->Cell(0.7,0.5,$cell[$x][17],1,0,'C',$fill_set);
				$pdf->Cell(0.7,0.5,$cell[$x][18],1,0,'C',$fill_set);
				$pdf->Cell(0.7,0.5,$cell[$x][19],1,0,'C',$fill_set);
				$pdf->Cell(0.7,0.5,$cell[$x][20],1,0,'C',$fill_set);
				$pdf->Cell(0.7,0.5,$cell[$x][21],1,0,'C',$fill_set);
				//$pdf->Cell(1,0.5,$cell[$x][21],1,0,'C',$fill_set);
				$pdf->Cell(1.1,0.5,$cell[$x][26],1,0,'C',$fill_set);
				$pdf->Ln();
			}
			
			
			
			if(($ctr + $totalBlok) > $pagebreak)
			{
				$pdf->AddPage(); 
				$pdf->SetFont('Arial','B',8);// added by NB 20.08.2014
				$pdf->Cell(28.1,0.6,'Print Date: '.$print_date,0,0,'R');// added by NB 20.08.2014
				$pdf->Ln();// added by NB 20.08.2014
				$pdf->SetFont('Arial','B',12);
				$pdf->Cell(11.6,0.6,$compName.' (Palm Oil)',1,0,'L');
				$pdf->Cell(16.4,0.6,'LAMPIRAN',1,0,'C');
				$pdf->Ln();
				
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(4,0.4,'Tanggal Panen','L',0,'L');
				$pdf->Cell(0.5,0.4,':',0,0,'L');
				$pdf->Cell(7.1,0.4,$tglPanen[$j],0,0,'L');
				$pdf->Cell(1.9,0.4,'','L',0,'L');
				$pdf->Cell(3,0.4,'Estate',0,0,'L');
				$pdf->Cell(0.5,0.4,':',0,0,'L');
				$pdf->Cell(11,0.4,$IDEstate,'R',0,'L');
				$pdf->Ln();
				$pdf->Cell(4,0.4,'NIK Mandor Panen','L',0,'L');
				$pdf->Cell(0.5,0.4,':',0,0,'L');
				$pdf->Cell(7.1,0.4,$NIKMandor[$j],0,0,'L');
				$pdf->Cell(1.9,0.4,'','L',0,'L');
				$pdf->Cell(3,0.4,'Divisi/Afdeling',0,0,'L');
				$pdf->Cell(0.5,0.4,':',0,0,'L');
				$pdf->Cell(11,0.4,$IDAfd[$j],'R',0,'L');
				$pdf->Ln();
				$pdf->Cell(4,0.4,'Nama Mandor Panen','L',0,'L');
				$pdf->Cell(0.5,0.4,':',0,0,'L');
				$pdf->Cell(7.1,0.4,$NamaMandor[$j],0,0,'L');
				$pdf->Cell(1.9,0.4,'','L',0,'L');
				$pdf->Cell(3,0.4,'Page',0,0,'L');
				$pdf->Cell(0.5,0.4,':',0,0,'L');
				$pdf->Cell(11,0.4,($page_lampiran+1).' dari '.ceil((($j+1)-$start_lampiran+$totalBlok)/20),'R',0,'L');
				$pdf->Ln();
				
				$pdf->Cell(10.6,0.4,'','L',0,'L');
				$pdf->Cell(1,0.4,'','R',0,'L');
				$pdf->Cell(3.5,0.4,'','',0,'C');
				$pdf->Cell(12.9,0.4,'','R',0,'L');
				$pdf->Ln();
				
				//bagian untuk memasukkan keterangan tabel
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(0.6,0.5,'No.',1,0,'C');
				$pdf->Cell(1,0.5,'AFD',1,0,'C');
				$pdf->Cell(2.2,0.5,'NIK',1,0,'C');
				$pdf->Cell(4.5,0.5,'Nama Karyawan',1,0,'C');
				$pdf->Cell(3,0.5,'OPH/BCC',1,0,'C');
				$pdf->Cell(0.8,0.5,'TPH',1,0,'C');
				$pdf->Cell(0.8,0.5,'Blok',1,0,'C');
				$pdf->Cell(0.8,0.5,'Desc',1,0,'C');
				$pdf->Cell(0.8,0.5,'HA',1,0,'C');
				$pdf->Cell(0.8,0.5,'Jam',1,0,'C');
				$pdf->Cell(2,0.5,'Hasil Panen',1,0,'C');
				$pdf->Cell(8.4,0.5,'Pinalty',1,0,'C');
				$pdf->Cell(1,0.5,'Kode',1,0,'C');
				$pdf->Cell(1.3,0.5,'Cust',1,0,'C');
				$pdf->Ln();
				$pdf->Cell(0.6,0.5,'',1,0,'C',true);
				$pdf->Cell(1,0.5,'KARY',1,0,'C');
				$pdf->Cell(2.2,0.5,'',1,0,'C',true);
				$pdf->Cell(4.5,0.5,'',1,0,'C',true);
				$pdf->Cell(3,0.5,'',1,0,'C',true);
				$pdf->Cell(0.8,0.5,'',1,0,'C',true);
				$pdf->Cell(0.8,0.5,'',1,0,'C',true);
				$pdf->Cell(0.8,0.5,'',1,0,'C',true);
				$pdf->Cell(0.8,0.5,'',1,0,'C',true);
				$pdf->Cell(0.8,0.5,'Kerja',1,0,'C');
				$pdf->Cell(1,0.5,'TBS',1,0,'C');
				$pdf->Cell(1,0.5,'BRD',1,0,'C');
				$pdf->Cell(0.7,0.5,'BM',1,0,'C');
				$pdf->Cell(0.7,0.5,'BK',1,0,'C');
				$pdf->Cell(0.7,0.5,'TP',1,0,'C');
				$pdf->Cell(0.7,0.5,'BB',1,0,'C');
				$pdf->Cell(0.7,0.5,'JK',1,0,'C');
				$pdf->Cell(0.7,0.5,'BA',1,0,'C');
				$pdf->Cell(0.7,0.5,'BT',1,0,'C');
				$pdf->Cell(0.7,0.5,'BL',1,0,'C');
				$pdf->Cell(0.7,0.5,'PB',1,0,'C');
				$pdf->Cell(0.7,0.5,'AB',1,0,'C');
				$pdf->Cell(0.7,0.5,'SF',1,0,'C');
				$pdf->Cell(0.7,0.5,'BS',1,0,'C');
				$pdf->Cell(1,0.5,'Absen',1,0,'C');
				$pdf->Cell(1.3,0.5,'',1,0,'C',true);
				$pdf->Ln();
				$pdf->Cell(28,0.1,'',1,0,'C');
				$pdf->Ln();
			}
			
			$pdf->Cell(28,0.1,'',1,0,'C');
			$pdf->Ln();
			
			$lampiran_total_manual = 0;
			$lampiran_total_koreksi = 0;
			$lampiran_total_luasan = 0;
			$lampiran_total_TBS = 0;
			$lampiran_total_BRD = 0;
			for($l=0;$l<$totalBlok;$l++)
			{
				$pdf->Cell(12.1,0.5,'Blok ID :',1,0,'C');
				$pdf->Cell(0.8,0.5,$blok[$l],1,0,'C');
				$pdf->Cell(0.8,0.5,$blname[$l],1,0,'C');
				$pdf->Cell(0.8,0.5,number_format($luasan[$l],2),1,0,'C');
				$pdf->Cell(1,0.5,$totalManual[$l],1,0,'C');
				$pdf->Cell(1,0.5,$totalKoreksi[$l],1,0,'C');
				$pdf->Cell(1,0.5,$totalTBS[$l],1,0,'C');
				$pdf->Cell(1,0.5,$totalBRD[$l],1,0,'C');
				$pdf->Cell(9.5,0.5,'',1,0,'C');
				$pdf->Ln();
				
				$lampiran_total_luasan += $luasan[$l];
				$lampiran_total_TBS += $totalTBS[$l];
				$lampiran_total_BRD += $totalBRD[$l];
				$lampiran_total_manual += $totalManual[$l];
				$lampiran_total_koreksi += $totalKoreksi[$l];
			}
			$pdf->Cell(12.1,0.5,'TOTAL',1,0,'C');
			$pdf->Cell(0.8,0.5,'',1,0,'C',true);
			$pdf->Cell(0.8,0.5,'',1,0,'C',true);
			$pdf->Cell(0.8,0.5,number_format($lampiran_total_luasan,2),1,0,'C');
			$pdf->Cell(1,0.5,$lampiran_total_manual,1,0,'C');
			$pdf->Cell(1,0.5,$lampiran_total_koreksi,1,0,'C');
			$pdf->Cell(1,0.5,$lampiran_total_TBS,1,0,'C');
			$pdf->Cell(1,0.5,$lampiran_total_BRD,1,0,'C');
			$pdf->Cell(9.5,0.5,'',1,0,'C');
			$pdf->Ln();
			
			$start_lampiran = $j+1;
			$totalBlok = 0;
			$blok = "";
			$blname = "";
			unset($totalManual);
			unset($totalKoreksi);
			unset($totalBRD);
			unset($totalTBS);
		}
			
		
	}
	$pdf->Output();
?>
