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
	/*
	if($i>20)
	{
		$pagebreak = 20;
	}
	else
	{
		$pagebreak = 25;
	}
	*/
	$pdf = new FPDF("L","cm","A4"); //membuat lembar PDF ukuran A4 Landscape, dan ukuran yang digunakan dalam cm
	$pdf->SetMargins(1.3,2,1); //membuat margin (kiri,atas,kanan)
	$pdf->AddPage();
	
	$ctr=0;
	$page=1;
	$nomor=0;
	
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
	for ($j=0;$j<$i;$j++)
	{
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
				$totalTBS[$k] = $cell[$j][8];
				$totalBRD[$k] = $cell[$j][9];
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
		//$pdf->Cell(1,0.5,$cell[$j][6],1,0,'C');
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
				
				$pdf->Cell(27,0.1,'',1,0,'C');
				$pdf->Ln();
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
				
				$pdf->Ln();
				$pdf->Cell(27,0.5,'Print Date: '.$print_date,0,0,'R');
			}
			
			$pdf->AddPage(); 
			
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(10,0.6,$compName.' (Palm Oil)',1,0,'L');
			$pdf->Cell(17,0.6,'LAPORAN HARIAN MANDOR PANEN',1,0,'C');
			$pdf->Ln();
			
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
	
	$pdf->Cell(27,0.1,'',1,0,'C');
	$pdf->Ln();
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
	
	$pdf->Ln();
	$pdf->Cell(27,0.5,'Print Date: '.$print_date,0,0,'R');
	
	$pdf->Output();
?>
