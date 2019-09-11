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
	$print_date = $_SESSION["printdate"];
	$tgl1 = $_SESSION["tgl1"];
	$tgl2 = $_SESSION["tgl2"];
	$id_ba = $_SESSION["ID_BA"];
	$id_cc = $_SESSION["ID_CC"];
	//echo $id_ba;die();
	$valueAfdeling = $_SESSION["valueAfd"];
	$NIK_Mandor = $_SESSION["nikmandor"];
	
	$stid = oci_parse($con, $sql_value);
	oci_execute($stid);
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
		$cell[$i][7] = '';
		$cell[$i][8] = $data["TBS"];
		$cell[$i][9] = $data["BRD"];
		$cell[$i][10] = $data["BM"];
		$cell[$i][11] = $data["BK"];
		$cell[$i][12] = $data["TP"];
		$cell[$i][13] = $data["BB"];
		$cell[$i][14] = $data["JK"];
		$cell[$i][15] = $data["BT"];
		$cell[$i][16] = $data["BL"];
		$cell[$i][17] = $data["PB"];
		$cell[$i][18] = $data["AB"];
		$cell[$i][19] = $data["SF"];
		$cell[$i][20] = $data["BS"];
		$cell[$i][21] = "";
		$cell[$i][22] = "";
		
		$blok_name[$i] = $data["BLOK_NAME"];
		$no_rekap_bcc[$i] = $data["NO_REKAP_BCC"];
		$id_rencana[$i] = $data["ID_RENCANA"];
		$tglPanen[$i] = $data["TGL_PANEN"];
		$NIKMandor[$i] = $data["NIK_MANDOR"];
		$NamaMandor[$i] = $data["NAMA_MANDOR"];
		$IDEstate = $data["NAMA_BA"];
		$IDAfd[$i] = $data["ID_AFD"];
		$compName = $data["COMP_NAME"];
		$i++;
	}
	oci_free_statement($stid);
	oci_close($con);
	$pagebreak = 20;
	
	$pdf = new FPDF("L","cm","A4"); //membuat lembar PDF ukuran A4 Landscape, dan ukuran yang digunakan dalam cm
	$pdf->SetMargins(1.3,2,1); //membuat margin (kiri,atas,kanan)
	$pdf->AddPage();
	
	$ctr=0;
	$page=1;
	$nomor=0;
	
	$pdf->SetFont('Arial','B',8); // added by NB 20.08.2014
	$pdf->Cell(27,0.6,'Print Date: '.$print_date,0,0,'R');// added by NB 20.08.2014
	$pdf->Ln();// added by NB 20.08.2014
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(10,0.6,$compName.' (Palm Oil)',1,0,'L');
	$pdf->Cell(17,0.6,'LAPORAN HARIAN MANDOR PANEN',1,0,'C');
	$pdf->Ln();
	
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(4,0.4,'Tanggal Panen','L',0,'L');
	$pdf->Cell(0.5,0.4,':',0,0,'L');
	$pdf->Cell(9,0.4,$tglPanen[0],0,0,'L');
	$pdf->Cell(3,0.4,'Estate',0,0,'L');
	$pdf->Cell(0.5,0.4,':',0,0,'L');
	$pdf->Cell(10,0.4,$IDEstate,'R',0,'L');
	$pdf->Ln();
	$pdf->Cell(4,0.4,'NIK Mandor Panen','L',0,'L');
	$pdf->Cell(0.5,0.4,':',0,0,'L');
	$pdf->Cell(9,0.4,$NIKMandor[0],0,0,'L');
	$pdf->Cell(3,0.4,'Divisi/Afdeling',0,0,'L');
	$pdf->Cell(0.5,0.4,':',0,0,'L');
	$pdf->Cell(10,0.4,$IDAfd[0],'R',0,'L');
	$pdf->Ln();
	$pdf->Cell(4,0.4,'Nama Mandor Panen','L',0,'L');
	$pdf->Cell(0.5,0.4,':',0,0,'L');
	$pdf->Cell(9,0.4,$NamaMandor[0],0,0,'L');
	$pdf->Cell(3,0.4,'Page',0,0,'L');
	$pdf->Cell(0.5,0.4,':',0,0,'L');
	$pdf->Cell(10,0.4,$page,'R',0,'L');
	$pdf->Ln();

	//bagian untuk memasukkan keterangan tabel
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(0.6,0.5,'No.',1,0,'C');
	$pdf->Cell(2.5,0.5,'NIK',1,0,'C');
	$pdf->Cell(4,0.5,'Nama Karyawan',1,0,'C');
	$pdf->Cell(3,0.5,'OPH/BCC',1,0,'C');
	$pdf->Cell(1,0.5,'TPH',1,0,'C');
	$pdf->Cell(1,0.5,'Blok',1,0,'C');
	$pdf->Cell(1,0.5,'Desc',1,0,'C');
	$pdf->Cell(1,0.5,'HA',1,0,'C');
	$pdf->Cell(1,0.5,'Jam',1,0,'C');
	$pdf->Cell(2,0.5,'Hasil Panen',1,0,'C');
	$pdf->Cell(7.7,0.5,'Pinalty',1,0,'C');
	$pdf->Cell(1,0.5,'Kode',1,0,'C');
	$pdf->Cell(1.2,0.5,'Cust',1,0,'C');
	$pdf->Ln();
	$pdf->Cell(0.6,0.5,'',1,0,'C',true);
	$pdf->Cell(2.5,0.5,'',1,0,'C',true);
	$pdf->Cell(4,0.5,'',1,0,'C',true);
	$pdf->Cell(3,0.5,'',1,0,'C',true);
	$pdf->Cell(1,0.5,'',1,0,'C',true);
	$pdf->Cell(1,0.5,'',1,0,'C',true);
	$pdf->Cell(1,0.5,'',1,0,'C',true);
	$pdf->Cell(1,0.5,'',1,0,'C',true);
	$pdf->Cell(1,0.5,'Kerja',1,0,'C');
	$pdf->Cell(1,0.5,'TBS',1,0,'C');
	$pdf->Cell(1,0.5,'BRD',1,0,'C');
	$pdf->Cell(0.7,0.5,'BM',1,0,'C');
	$pdf->Cell(0.7,0.5,'BK',1,0,'C');
	$pdf->Cell(0.7,0.5,'TP',1,0,'C');
	$pdf->Cell(0.7,0.5,'BB',1,0,'C');
	$pdf->Cell(0.7,0.5,'JK',1,0,'C');
	$pdf->Cell(0.7,0.5,'BT',1,0,'C');
	$pdf->Cell(0.7,0.5,'BL',1,0,'C');
	$pdf->Cell(0.7,0.5,'PB',1,0,'C');
	$pdf->Cell(0.7,0.5,'AB',1,0,'C');
	$pdf->Cell(0.7,0.5,'SF',1,0,'C');
	$pdf->Cell(0.7,0.5,'BS',1,0,'C');
	$pdf->Cell(1,0.5,'Absen',1,0,'C');
	$pdf->Cell(1.2,0.5,'',1,0,'C',true);
	$pdf->Ln();
	$pdf->Cell(27,0.1,'',1,0,'C');
	$pdf->Ln();
	
	/*
	$ctr=0;
	$page=1;
	*/
	//bagian untuk memasukkan isi tabel
	$totalBlok=0;
	$totalKaryawan=0;
	for ($j=0;$j<$i;$j++)
	{//$totalBlok=0;
		$ctr = $ctr+1;
		$nomor = $nomor+1;
		$pdf->SetFont('Arial','',6.5);
		
		if($j == 0)
		{
			$blok[$totalBlok] = $cell[$j][5];
			$luasan[$totalBlok] = $cell[$j][6];
			$totalTBS[$totalBlok] = $cell[$j][8];
			$totalBRD[$totalBlok] = $cell[$j][9];
			$totalBlok = $totalBlok +1;
		}
		else
		{
			if($no_rekap_bcc[$j] !== $no_rekap_bcc[$j-1])
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
					$luasan[$totalBlok] = $cell[$j][6];
					$totalBlok = $totalBlok +1;
				}
			}
			else if($id_rencana[$j] !== $id_rencana[$j-1])
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
					$luasan[$totalBlok] = $cell[$j][6];
					$totalBlok = $totalBlok +1;
				}
			}
			
			$x_found = 'false';
			for($k=0;$k<$totalBlok;$k++)
			{
				if($cell[$j][5]==$blok[$k])
				{
					$totalTBS[$k] = $totalTBS[$k] + $cell[$j][8];
					$totalBRD[$k] = $totalBRD[$k] + $cell[$j][9];
					$x_found = 'true';
				}
			}
			if($x_found == 'false')
			{
				$totalTBS[$totalBlok] = $cell[$j][8];
				$totalBRD[$totalBlok] = $cell[$j][9];
				$totalBlok = $totalBlok +1;
			}
		}
		$pdf->Cell(0.6,0.5,$nomor,1,0,'C');
		$pdf->Cell(2.5,0.5,$cell[$j][1],1,0,'L');
		$pdf->Cell(4,0.5,$cell[$j][2],1,0,'L');
		$pdf->Cell(3,0.5,separator($cell[$j][3]),1,0,'C');
		$pdf->Cell(1,0.5,$cell[$j][4],1,0,'C');
		$pdf->Cell(1,0.5,$cell[$j][5],1,0,'C');
		$pdf->Cell(1,0.5,$blok_name[$j],1,0,'C');
		
		if($j == 0)
		{
			$pdf->Cell(1,0.5,$cell[$j][6],1,0,'C');
		}
		else
		{
			if($no_rekap_bcc[$j] !== $no_rekap_bcc[$j-1])
			{
				$pdf->Cell(1,0.5,$cell[$j][6],1,0,'C');
			}
			else if($id_rencana[$j] !== $id_rencana[$j-1])
			{
				$pdf->Cell(1,0.5,$cell[$j][6],1,0,'C');
			}
			else
			{
				$pdf->Cell(1,0.5,'-',1,0,'C');
			}
		}
		$pdf->Cell(1,0.5,$cell[$j][7],1,0,'C');
		$pdf->Cell(1,0.5,$cell[$j][8],1,0,'C');
		$pdf->Cell(1,0.5,$cell[$j][9],1,0,'C');
		$pdf->Cell(0.7,0.5,$cell[$j][10],1,0,'C');
		$pdf->Cell(0.7,0.5,$cell[$j][11],1,0,'C');
		$pdf->Cell(0.7,0.5,$cell[$j][12],1,0,'C');
		$pdf->Cell(0.7,0.5,$cell[$j][13],1,0,'C');
		$pdf->Cell(0.7,0.5,$cell[$j][14],1,0,'C');
		$pdf->Cell(0.7,0.5,$cell[$j][15],1,0,'C');
		$pdf->Cell(0.7,0.5,$cell[$j][16],1,0,'C');
		$pdf->Cell(0.7,0.5,$cell[$j][17],1,0,'C');
		$pdf->Cell(0.7,0.5,$cell[$j][18],1,0,'C');
		$pdf->Cell(0.7,0.5,$cell[$j][19],1,0,'C');
		$pdf->Cell(0.7,0.5,$cell[$j][20],1,0,'C');
		$pdf->Cell(1,0.5,$cell[$j][21],1,0,'C');
		$pdf->Cell(1.2,0.5,$cell[$j][22],1,0,'C');
		$pdf->Ln();
		
		if(($ctr==$pagebreak || $NIKMandor[$j]!==$NIKMandor[$j+1] || $tglPanen[$j]!==$tglPanen[$j+1]) && $j+1<$i)
		{
			$ctr=0;
			$page++;
			if($NIKMandor[$j]!==$NIKMandor[$j+1] || $tglPanen[$j]!==$tglPanen[$j+1])
			{
				$page=1;
				$nomor=0;
				
				if(($ctr + $totalBlok) > $pagebreak)
				{
					$pdf->AddPage(); 
				}
				
				$pdf->Cell(27,0.1,'',1,0,'C');
				$pdf->Ln();
						
				for($l=0;$l<$totalBlok;$l++)
				{
					$pdf->Cell(11.1,0.5,'Blok ID :',1,0,'C');
					$pdf->Cell(1,0.5,$blok[$l],1,0,'C');
					$pdf->Cell(1,0.5,'',1,0,'C');
					$pdf->Cell(1,0.5,$luasan[$l],1,0,'C');
					$pdf->Cell(1,0.5,'',1,0,'C');
					$pdf->Cell(1,0.5,$totalTBS[$l],1,0,'C');
					$pdf->Cell(1,0.5,$totalBRD[$l],1,0,'C');
					$pdf->Cell(9.9,0.5,'',1,0,'C');
					$pdf->Ln();
				}
				$totalBlok = 0;
				
				/*added by NB 20.08.2014*/
				$pdf->Cell(27,0.5,'',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(27,0.5,'SUMMARY PEMANEN',1,0,'L');
				$pdf->Ln();
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(3.1,0.5,'NIK',1,0,'C');
				$pdf->Cell(4,0.5,'Nama Karyawan',1,0,'C');
				$pdf->Cell(4,0.5,'',1,0,'C',true);
				$pdf->Cell(1,0.5,'Blok',1,0,'C');
				$pdf->Cell(1,0.5,'Desc',1,0,'C');
				$pdf->Cell(1,0.5,'HA',1,0,'C');
				$pdf->Cell(1,0.5,'Jam',1,0,'C');
				$pdf->Cell(2,0.5,'Hasil Panen',1,0,'C');
				$pdf->Cell(7.7,0.5,'Pinalty',1,0,'C');
				$pdf->Cell(1,0.5,'Kode',1,0,'C');
				$pdf->Cell(1.2,0.5,'Cust',1,0,'C');
				$pdf->Ln();
				$pdf->Cell(3.1,0.5,'',1,0,'C');
				$pdf->Cell(4,0.5,'',1,0,'C');
				$pdf->Cell(4,0.5,'',1,0,'C');
				$pdf->Cell(1,0.5,'',1,0,'C');
				$pdf->Cell(1,0.5,'',1,0,'C');
				$pdf->Cell(1,0.5,'',1,0,'C');
				$pdf->Cell(1,0.5,'Kerja',1,0,'C');
				$pdf->Cell(1,0.5,'TBS',1,0,'C');
				$pdf->Cell(1,0.5,'BRD',1,0,'C');
				$pdf->Cell(0.7,0.5,'BM',1,0,'C');
				$pdf->Cell(0.7,0.5,'BK',1,0,'C');
				$pdf->Cell(0.7,0.5,'TP',1,0,'C');
				$pdf->Cell(0.7,0.5,'BB',1,0,'C');
				$pdf->Cell(0.7,0.5,'JK',1,0,'C');
				$pdf->Cell(0.7,0.5,'BT',1,0,'C');
				$pdf->Cell(0.7,0.5,'BL',1,0,'C');
				$pdf->Cell(0.7,0.5,'PB',1,0,'C');
				$pdf->Cell(0.7,0.5,'AB',1,0,'C');
				$pdf->Cell(0.7,0.5,'SF',1,0,'C');
				$pdf->Cell(0.7,0.5,'BS',1,0,'C');
				$pdf->Cell(1,0.5,'Absen',1,0,'C');
				$pdf->Cell(1.2,0.5,'',1,0,'C',true);
				$pdf->Ln();
				$tgl = date("Y-m-d", strtotime($tglPanen[$j]));
				$sql_cetak_panen = "	
							  select nik_pemanen, nama_pemanen, sum(luasan_panen) as luasan_panen, sum(TBS) as TBS, sum(BRD) as BRD, sum(BM) as BM, sum(BK) as BK, sum(TP) as TP, sum(BB) as BB, sum(JK) as JK, sum(BT) as BT, sum(BL) as BL, sum(PB) as PB, sum(AB) as AB, sum(SF) as SF, sum(BS) as BS from (
							  select tgl_panen, nik_pemanen, nama_pemanen, 
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
							  sum(BL) as BL, 
							  sum(PB) as PB,
							  sum(AB) as AB, 
							  sum(SF) as SF,
							  sum(BS) as BS
							  from (
							  select tc.id_cc,
							 tc.comp_name comp_name,
							 tba.id_ba,
							 tba.nama_ba nama_ba,
							 ta.id_afd,
							 thrp.tanggal_rencana tgl_panen,
							 thrp.nik_mandor,
							 f_get_empname (thrp.nik_mandor) nama_mandor,
							 thrp.nik_pemanen NIK_PEMANEN,
							 f_get_empname (thrp.nik_pemanen) nama_pemanen,
							 thp.no_bcc,
							 thp.no_tph,
							 tb.id_blok id_blok,
							 tdrp.luasan_panen luasan_panen,
							 null jam_kerja,
								 NVL( F_GET_HASIL_PANEN_TBS2 ( thp.no_rekap_bcc, thp.no_bcc),0)  as TBS, 
								 NVL( F_GET_HASIL_PANEN_BRD  ( thp.no_rekap_bcc, thp.no_bcc),0)  as BRD,
								 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 1),0)  as BM, 
								 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 2) ,0) as BK, 
								 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 7) ,0) as TP, 
								 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 6),0)  as BB, 
								 0 JK, 
								 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 11),0)  as BT, 
								 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 12),0)  as BL, 
								 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 13) ,0) as PB,
								 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 10) ,0) as AB, 
								 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 14) ,0) as SF,               
								 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 8) ,0) as BS, 
							 null kode_absen,
							 null customer,
							 tb.blok_name,
							 tdrp.no_rekap_bcc,
							 thrp.id_rencana
						   FROM t_header_rencana_panen thrp
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
							 and ta.id_afd = nvl(decode('$valueAfdeling', 'ALL', null, '$valueAfdeling'), ta.id_afd)
							 and thrp.nik_mandor = nvl(decode('$NIK_Mandor', 'ALL', null, '$NIK_Mandor'), thrp.nik_mandor)
							 and TO_CHAR (thrp.tanggal_rencana, 'YYYY-MM-DD') = '$tgl'
					order by   tgl_panen, thrp.nik_mandor,  nama_pemanen,  tdrp.no_rekap_bcc,  thrp.id_rencana, thp.no_bcc) header
								group by tgl_panen, nik_pemanen, nama_pemanen, id_blok, luasan_panen order by tgl_panen, nik_pemanen) detail
					group by nik_pemanen, nama_pemanen order by nama_pemanen";
								
				$con1 = connect();
				$stid1 = oci_parse($con1, $sql_cetak_panen);
				oci_execute($stid1);
				$nik = "";
				$blok = "";
				$row_array = 0;
				while (($data1 = oci_fetch_array($stid1, OCI_ASSOC))) 
				{
					$pdf->Cell(3.1,0.5,$data1["NIK_PEMANEN"],1,0,'C');
					$pdf->Cell(4,0.5,$data1["NAMA_PEMANEN"],1,0,'C');
					$pdf->Cell(4,0.5,'',1,0,'C');
					$pdf->Cell(1,0.5,'',1,0,'C');
					$pdf->Cell(1,0.5,'',1,0,'C');
					$pdf->Cell(1,0.5,number_format($data1["LUASAN_PANEN"],2),1,0,'C');
					$pdf->Cell(1,0.5,'',1,0,'C');
					$pdf->Cell(1,0.5,$data1["TBS"],1,0,'C');
					$pdf->Cell(1,0.5,$data1["BRD"],1,0,'C');
					$pdf->Cell(0.7,0.5,$data1['BM'],1,0,'C');
					$pdf->Cell(0.7,0.5,$data1['BK'],1,0,'C');
					$pdf->Cell(0.7,0.5,$data1['TP'],1,0,'C');
					$pdf->Cell(0.7,0.5,$data1['BB'],1,0,'C');
					$pdf->Cell(0.7,0.5,$data1['JK'],1,0,'C');
					$pdf->Cell(0.7,0.5,$data1['BT'],1,0,'C');
					$pdf->Cell(0.7,0.5,$data1['BL'],1,0,'C');
					$pdf->Cell(0.7,0.5,$data1['PB'],1,0,'C');
					$pdf->Cell(0.7,0.5,$data1['AB'],1,0,'C');
					$pdf->Cell(0.7,0.5,$data1['SF'],1,0,'C');
					$pdf->Cell(0.7,0.5,$data1['BS'],1,0,'C');
					$pdf->Cell(2.2,0.5,'',1,0,'C');
					$pdf->Ln();
				}
				$totalKaryawan = 0;
				/*end added by NB 20.08.2014*/
				
				$pdf->SetFont('Arial','B',7);
				$pdf->Cell(4,0.5,'Disetujui Oleh',1,0,'C');
				$pdf->Cell(9.5,0.5,'Diperiksa Oleh',1,0,'C');
				$pdf->Cell(4,0.5,'Dibuat Oleh',1,0,'C');
				$pdf->Cell(9.5,0.5,'Keterangan',1,0,'C');
				
				$pdf->Ln();
				$pdf->Cell(4,2,'',1,0,'L');
				$pdf->Cell(9.5,2,'',1,0,'C');
				$pdf->Cell(4,2,'',1,0,'C');
				$pdf->Cell(4.5,0.3,'BM : Buah Mentah',0,0,'L');
				$pdf->Cell(5,0.3,'BL : Buah Tinggal (Piringan/Pasar Pikul)','R',0,'L');$pdf->Ln();
				$pdf->Cell(4,2,'',0,0,'L');
				$pdf->Cell(9.5,2,'',0,0,'C');
				$pdf->Cell(4,2,'',0,0,'C');
				$pdf->Cell(4.5,0.3,'BK : Buah Mengkal',0,0,'L');
				$pdf->Cell(5,0.3,'PB : Pinalti Brondolan (Piringan)','R',0,'L');$pdf->Ln();
				$pdf->Cell(4,2,'',0,0,'L');
				$pdf->Cell(9.5,2,'',0,0,'C');
				$pdf->Cell(4,2,'',0,0,'C');
				$pdf->Cell(4.5,0.3,'TP : Tangkai Panjang',0,0,'L');
				$pdf->Cell(5,0.3,'AB : Tidak ada Alas Brondolan (Per TPH)','R',0,'L');$pdf->Ln();
				$pdf->Cell(4,2,'',0,0,'L');
				$pdf->Cell(9.5,2,'',0,0,'C');
				$pdf->Cell(4,2,'',0,0,'C');
				$pdf->Cell(4.5,0.3,'BB : Buah Busuk',0,0,'L');
				$pdf->Cell(5,0.3,'SF : Buah Matahari','R',0,'L');$pdf->Ln();
				$pdf->Cell(4,2,'',0,0,'L');
				$pdf->Cell(9.5,2,'',0,0,'C');
				$pdf->Cell(4,2,'',0,0,'C');
				$pdf->Cell(4.5,0.3,'JK : Janjang Kosong',0,0,'L');
				$pdf->Cell(5,0.3,'BS : Buah Sakit','R',0,'L');
				
				$pdf->Ln();
				$pdf->Cell(4,0.5,'Estate Manager/Kabun',1,0,'C');
				$pdf->Cell(9.5,0.5,'Asisten Afdeling & Mandor 1',1,0,'C');
				$pdf->Cell(4,0.5,'Mandor Panen',1,0,'C');
				$pdf->Cell(9.5,0.5,'BT : Buah Tinggal di Pokok','BR',0,'L');
				
				/*$pdf->Ln();
				$pdf->Cell(27,0.5,'Print Date: '.$print_date,0,0,'R');
				*/$totalBlok = 0;
				unset($totalBRD);
				unset($totalTBS);
			}
			
			$pdf->AddPage(); 
			
			$pdf->SetFont('Arial','B',8);// added by NB 20.08.2014
			$pdf->Cell(27,0.6,'Print Date: '.$print_date,0,0,'R');// added by NB 20.08.2014
			$pdf->Ln();// added by NB 20.08.2014
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(10,0.6,$compName.' (Palm Oil)',1,0,'L');
			$pdf->Cell(17,0.6,'LAPORAN HARIAN MANDOR PANEN',1,0,'C');
			$pdf->Ln();
			$next_tgl = $tglPanen[$j+1]; // added by NB 21.08.2014
			$next_NIK_Mandor = $NIKMandor[$j+1]; //added by NB 03.09.2014
			
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(4,0.4,'Tanggal Panen','L',0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(9,0.4,$tglPanen[$j+1],0,0,'L');
			$pdf->Cell(3,0.4,'Estate',0,0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(10,0.4,$IDEstate,'R',0,'L');
			$pdf->Ln();
			$pdf->Cell(4,0.4,'NIK Mandor Panen','L',0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(9,0.4,$NIKMandor[$j+1],0,0,'L');
			$pdf->Cell(3,0.4,'Divisi/Afdeling',0,0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(10,0.4,$IDAfd[$j+1],'R',0,'L');
			$pdf->Ln();
			$pdf->Cell(4,0.4,'Nama Mandor Panen','L',0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(9,0.4,$NamaMandor[$j+1],0,0,'L');
			$pdf->Cell(3,0.4,'Page',0,0,'L');
			$pdf->Cell(0.5,0.4,':',0,0,'L');
			$pdf->Cell(10,0.4,$page,'R',0,'L');
			$pdf->Ln();

			//bagian untuk memasukkan keterangan tabel
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(0.6,0.5,'No.',1,0,'C');
			$pdf->Cell(2.5,0.5,'NIK',1,0,'C');
			$pdf->Cell(4,0.5,'Nama Karyawan',1,0,'C');
			$pdf->Cell(3,0.5,'OPH/BCC',1,0,'C');
			$pdf->Cell(1,0.5,'TPH',1,0,'C');
			$pdf->Cell(1,0.5,'Blok',1,0,'C');
			$pdf->Cell(1,0.5,'Desc',1,0,'C');
			$pdf->Cell(1,0.5,'HA',1,0,'C');
			$pdf->Cell(1,0.5,'Jam',1,0,'C');
			$pdf->Cell(2,0.5,'Hasil Panen',1,0,'C');
			$pdf->Cell(7.7,0.5,'Pinalty',1,0,'C');
			$pdf->Cell(1,0.5,'Kode',1,0,'C');
			$pdf->Cell(1.2,0.5,'Cust',1,0,'C');
			$pdf->Ln();
			$pdf->Cell(3.1,0.5,'',1,0,'C');
			$pdf->Cell(4,0.5,'',1,0,'C',true);
			$pdf->Cell(3,0.5,'',1,0,'C',true);
			$pdf->Cell(1,0.5,'',1,0,'C',true);
			$pdf->Cell(1,0.5,'',1,0,'C',true);
			$pdf->Cell(1,0.5,'',1,0,'C',true);
			$pdf->Cell(1,0.5,'',1,0,'C',true);
			$pdf->Cell(1,0.5,'Kerja',1,0,'C');
			$pdf->Cell(1,0.5,'TBS',1,0,'C');
			$pdf->Cell(1,0.5,'BRD',1,0,'C');
			$pdf->Cell(0.7,0.5,'BM',1,0,'C');
			$pdf->Cell(0.7,0.5,'BK',1,0,'C');
			$pdf->Cell(0.7,0.5,'TP',1,0,'C');
			$pdf->Cell(0.7,0.5,'BB',1,0,'C');
			$pdf->Cell(0.7,0.5,'JK',1,0,'C');
			$pdf->Cell(0.7,0.5,'BT',1,0,'C');
			$pdf->Cell(0.7,0.5,'BL',1,0,'C');
			$pdf->Cell(0.7,0.5,'PB',1,0,'C');
			$pdf->Cell(0.7,0.5,'AB',1,0,'C');
			$pdf->Cell(0.7,0.5,'SF',1,0,'C');
			$pdf->Cell(0.7,0.5,'BS',1,0,'C');
			$pdf->Cell(1,0.5,'Absen',1,0,'C');
			$pdf->Cell(1.2,0.5,'',1,0,'C',true);
			$pdf->Ln();
			$pdf->Cell(27,0.1,'',1,0,'C');
			$pdf->Ln();
		}
	}
	
	if(($ctr + $totalBlok) > $pagebreak)
	{
		$pdf->AddPage(); 
	}
	
	$pdf->Cell(27,0.1,'',1,0,'C');
	$pdf->Ln();
	
	for($l=0;$l<$totalBlok;$l++)
	{
		$pdf->Cell(11.1,0.5,'Blok ID :',1,0,'C');
		$pdf->Cell(1,0.5,$blok[$l],1,0,'C');
		$pdf->Cell(1,0.5,'',1,0,'C');
		$pdf->Cell(1,0.5,$luasan[$l],1,0,'C');
		$pdf->Cell(1,0.5,'',1,0,'C');
		$pdf->Cell(1,0.5,$totalTBS[$l],1,0,'C');
		$pdf->Cell(1,0.5,$totalBRD[$l],1,0,'C');
		$pdf->Cell(9.9,0.5,'',1,0,'C');
		$pdf->Ln();
	}
	$totalBlok = 0;
	
	/*added by NB 20.08.2014*/
	
	$pdf->Cell(27,0.5,'',1,0,'C',true);
	$pdf->Ln();
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(27,0.5,'SUMMARY PEMANEN',1,0,'L');
	$pdf->Ln();
	
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(3.1,0.5,'NIK',1,0,'C');
	$pdf->Cell(4,0.5,'Nama Karyawan',1,0,'C');
	$pdf->Cell(4,0.5,'',1,0,'C',true);
	$pdf->Cell(1,0.5,'Blok',1,0,'C');
	$pdf->Cell(1,0.5,'Desc',1,0,'C');
	$pdf->Cell(1,0.5,'HA',1,0,'C');
	$pdf->Cell(1,0.5,'Jam',1,0,'C');
	$pdf->Cell(2,0.5,'Hasil Panen',1,0,'C');
	$pdf->Cell(7.7,0.5,'Pinalty',1,0,'C');
	$pdf->Cell(1,0.5,'Kode',1,0,'C');
	$pdf->Cell(1.2,0.5,'Cust',1,0,'C');
	$pdf->Ln();
	$pdf->Cell(0.6,0.5,'',1,0,'C',true);
	$pdf->Cell(2.5,0.5,'',1,0,'C',true);
	$pdf->Cell(4,0.5,'',1,0,'C',true);
	$pdf->Cell(3,0.5,'',1,0,'C',true);
	$pdf->Cell(1,0.5,'',1,0,'C',true);
	$pdf->Cell(1,0.5,'',1,0,'C',true);
	$pdf->Cell(1,0.5,'',1,0,'C',true);
	$pdf->Cell(1,0.5,'',1,0,'C',true);
	$pdf->Cell(1,0.5,'Kerja',1,0,'C');
	$pdf->Cell(1,0.5,'TBS',1,0,'C');
	$pdf->Cell(1,0.5,'BRD',1,0,'C');
	$pdf->Cell(0.7,0.5,'BM',1,0,'C');
	$pdf->Cell(0.7,0.5,'BK',1,0,'C');
	$pdf->Cell(0.7,0.5,'TP',1,0,'C');
	$pdf->Cell(0.7,0.5,'BB',1,0,'C');
	$pdf->Cell(0.7,0.5,'JK',1,0,'C');
	$pdf->Cell(0.7,0.5,'BT',1,0,'C');
	$pdf->Cell(0.7,0.5,'BL',1,0,'C');
	$pdf->Cell(0.7,0.5,'PB',1,0,'C');
	$pdf->Cell(0.7,0.5,'AB',1,0,'C');
	$pdf->Cell(0.7,0.5,'SF',1,0,'C');
	$pdf->Cell(0.7,0.5,'BS',1,0,'C');
	$pdf->Cell(1,0.5,'Absen',1,0,'C');
	$pdf->Cell(1.2,0.5,'',1,0,'C',true);
	$pdf->Ln();
	$tgl = date("Y-m-d", strtotime($next_tgl));
	if($tgl == "1970-01-01"){
		$tgl = date("Y-m-d", strtotime($tglPanen[0]));
	}
	$sql_cetak_panen1 = "	
				  select nik_pemanen, nama_pemanen, sum(luasan_panen) as luasan_panen, sum(TBS) as TBS, sum(BRD) as BRD, sum(BM) as BM, sum(BK) as BK, sum(TP) as TP, sum(BB) as BB, sum(JK) as JK, sum(BT) as BT, sum(BL) as BL, sum(PB) as PB, sum(AB) as AB, sum(SF) as SF, sum(BS) as BS from (
				  select tgl_panen, nik_pemanen, nama_pemanen, 
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
				  sum(BL) as BL, 
				  sum(PB) as PB,
				  sum(AB) as AB, 
				  sum(SF) as SF,
				  sum(BS) as BS
				  from (
				  select tc.id_cc,
				 tc.comp_name comp_name,
				 tba.id_ba,
				 tba.nama_ba nama_ba,
				 ta.id_afd,
				 thrp.tanggal_rencana tgl_panen,
				 thrp.nik_mandor,
				 f_get_empname (thrp.nik_mandor) nama_mandor,
				 thrp.nik_pemanen NIK_PEMANEN,
				 f_get_empname (thrp.nik_pemanen) nama_pemanen,
				 thp.no_bcc,
				 thp.no_tph,
				 tb.id_blok id_blok,
				 tdrp.luasan_panen luasan_panen,
				 null jam_kerja,
					 NVL( F_GET_HASIL_PANEN_TBS2 ( thp.no_rekap_bcc, thp.no_bcc),0)  as TBS, 
					 NVL( F_GET_HASIL_PANEN_BRD  ( thp.no_rekap_bcc, thp.no_bcc),0)  as BRD,
					 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 1),0)  as BM, 
					 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 2) ,0) as BK, 
					 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 7) ,0) as TP, 
					 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 6),0)  as BB, 
					 0 JK, 
					 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 11),0)  as BT, 
					 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 12),0)  as BL, 
					 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 13) ,0) as PB,
					 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 10) ,0) as AB, 
					 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 14) ,0) as SF,               
					 NVL( F_GET_HASIL_PANEN_NUMBER ( thp.no_rekap_bcc, thp.no_bcc, 8) ,0) as BS, 
				 null kode_absen,
				 null customer,
				 tb.blok_name,
				 tdrp.no_rekap_bcc,
				 thrp.id_rencana
			   FROM t_header_rencana_panen thrp
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
				 and ta.id_afd = nvl(decode('$valueAfdeling', 'ALL', null, '$valueAfdeling'), ta.id_afd)
				 and thrp.nik_mandor = nvl(decode('$next_NIK_Mandor', 'ALL', null, '$next_NIK_Mandor'), thrp.nik_mandor)
				 and TO_CHAR (thrp.tanggal_rencana, 'YYYY-MM-DD') = '$tgl'
		order by   tgl_panen, thrp.nik_mandor,  nama_pemanen,  tdrp.no_rekap_bcc,  thrp.id_rencana, thp.no_bcc) header
					group by tgl_panen, nik_pemanen, nama_pemanen, id_blok, luasan_panen order by tgl_panen, nik_pemanen) detail
				group by nik_pemanen, nama_pemanen order by nama_pemanen";
	$con2 = connect();
	//echo $sql_cetak_panen1;die();
	$stid2 = oci_parse($con2, $sql_cetak_panen1);
	oci_execute($stid2);
	$nik = "";
	$blok = "";
	$row_array = 0;
	while (($data2 = oci_fetch_array($stid2, OCI_ASSOC))) 
	{
		$pdf->Cell(3.1,0.5,$data2["NIK_PEMANEN"],1,0,'C');
		$pdf->Cell(4,0.5,$data2["NAMA_PEMANEN"],1,0,'C');
		$pdf->Cell(4,0.5,'',1,0,'C');
		$pdf->Cell(1,0.5,'',1,0,'C');
		$pdf->Cell(1,0.5,'',1,0,'C');
		$pdf->Cell(1,0.5,number_format($data2["LUASAN_PANEN"],2),1,0,'C');
		$pdf->Cell(1,0.5,'',1,0,'C');
		$pdf->Cell(1,0.5,$data2["TBS"],1,0,'C');
		$pdf->Cell(1,0.5,$data2["BRD"],1,0,'C');
		$pdf->Cell(0.7,0.5,$data2['BM'],1,0,'C');
		$pdf->Cell(0.7,0.5,$data2['BK'],1,0,'C');
		$pdf->Cell(0.7,0.5,$data2['TP'],1,0,'C');
		$pdf->Cell(0.7,0.5,$data2['BB'],1,0,'C');
		$pdf->Cell(0.7,0.5,$data2['JK'],1,0,'C');
		$pdf->Cell(0.7,0.5,$data2['BT'],1,0,'C');
		$pdf->Cell(0.7,0.5,$data2['BL'],1,0,'C');
		$pdf->Cell(0.7,0.5,$data2['PB'],1,0,'C');
		$pdf->Cell(0.7,0.5,$data2['AB'],1,0,'C');
		$pdf->Cell(0.7,0.5,$data2['SF'],1,0,'C');
		$pdf->Cell(0.7,0.5,$data2['BS'],1,0,'C');
		$pdf->Cell(2.2,0.5,'',1,0,'C');
		$pdf->Ln();
	}
	$totalKaryawan = 0;
	/*end added by NB 20.08.2014*/
	
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(4,0.5,'Disetujui Oleh',1,0,'C');
	$pdf->Cell(9.5,0.5,'Diperiksa Oleh',1,0,'C');
	$pdf->Cell(4,0.5,'Dibuat Oleh',1,0,'C');
	$pdf->Cell(9.5,0.5,'Keterangan',1,0,'C');
	
	$pdf->Ln();
	$pdf->Cell(4,2,'',1,0,'L');
	$pdf->Cell(9.5,2,'',1,0,'C');
	$pdf->Cell(4,2,'',1,0,'C');
	$pdf->Cell(4.5,0.3,'BM : Buah Mentah',0,0,'L');
	$pdf->Cell(5,0.3,'BL : Buah Tinggal (Piringan/Pasar Pikul)','R',0,'L');$pdf->Ln();
	$pdf->Cell(4,2,'',0,0,'L');
	$pdf->Cell(9.5,2,'',0,0,'C');
	$pdf->Cell(4,2,'',0,0,'C');
	$pdf->Cell(4.5,0.3,'BK : Buah Mengkal',0,0,'L');
	$pdf->Cell(5,0.3,'PB : Pinalti Brondolan (Piringan)','R',0,'L');$pdf->Ln();
	$pdf->Cell(4,2,'',0,0,'L');
	$pdf->Cell(9.5,2,'',0,0,'C');
	$pdf->Cell(4,2,'',0,0,'C');
	$pdf->Cell(4.5,0.3,'TP : Tangkai Panjang',0,0,'L');
	$pdf->Cell(5,0.3,'AB : Tidak ada Alas Brondolan (Per TPH)','R',0,'L');$pdf->Ln();
	$pdf->Cell(4,2,'',0,0,'L');
	$pdf->Cell(9.5,2,'',0,0,'C');
	$pdf->Cell(4,2,'',0,0,'C');
	$pdf->Cell(4.5,0.3,'BB : Buah Busuk',0,0,'L');
	$pdf->Cell(5,0.3,'SF : Buah Matahari','R',0,'L');$pdf->Ln();
	$pdf->Cell(4,2,'',0,0,'L');
	$pdf->Cell(9.5,2,'',0,0,'C');
	$pdf->Cell(4,2,'',0,0,'C');
	$pdf->Cell(4.5,0.3,'JK : Janjang Kosong',0,0,'L');
	$pdf->Cell(5,0.3,'BS : Buah Sakit','R',0,'L');
	
	$pdf->Ln();
	$pdf->Cell(4,0.5,'Estate Manager/Kabun',1,0,'C');
	$pdf->Cell(9.5,0.5,'Asisten Afdeling & Mandor 1',1,0,'C');
	$pdf->Cell(4,0.5,'Mandor Panen',1,0,'C');
	$pdf->Cell(9.5,0.5,'BT : Buah Tinggal di Pokok','BR',0,'L');
	
	/*$pdf->Ln();
	$pdf->Cell(1,0.5,'Print Date: '.$print_date,0,0,'L');
	(*/
	$pdf->Output();
?>
