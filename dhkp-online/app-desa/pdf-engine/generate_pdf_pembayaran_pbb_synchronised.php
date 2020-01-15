<?php
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
/* ERROR DEBUG HANDLE */
error_reporting(E_ALL);
ini_set("display_errors", 1);
/* SESSION START */
session_start();
$txsessionid = session_id();
/* VARIABLES */
require_once dirname(__FILE__) . '/../../variablen.php';
require_once dirname(__FILE__) . '/../../einstellung.php';
require_once dirname(__FILE__) . '/../../funktion.php';
/* ==================================== */
require_once dirname(__FILE__) . '/../local-assets/fpdf/fpdf.php';
$pageComponent = new dasFunktionz();
/* initial variables */
$dateFrom = null; $dateTtto = null;
$baselastpagerows = 0;
$lastpageminimumrows = 32;
$subtotal = 0;
$totalpages = 0;
if (isset($_SESSION['desaXweb'])) {
	$maxonepagerows = 32;
	$maxfirstpagerows = 37;
	$kode_propinsi = $_SESSION['desaXweb']['KD_PROPINSI'];
	$kode_dati2 = $_SESSION['desaXweb']['KD_DATI2'];
	$kode_kecamatan = $_SESSION['desaXweb']['KD_KECAMATAN'];
	$kode_kelurahan = $_SESSION['desaXweb']['KD_KELURAHAN'];
	$namaKecamatan = $_SESSION['desaXweb']['NM_KECAMATAN'];
	$namaKelurahan = $_SESSION['desaXweb']['NM_KELURAHAN'];
	$thnsppt = $_GET['taxyear'];
	try {
		$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
		$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		/* counting lembar sppt - desa */
		$stmt_cnt = $dbcon->prepare("SELECT COUNT(*) as foundrows FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND STATUS_PEMBAYARAN_SPPT != 0 AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01'");
		$stmt_cnt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
		$stmt_cnt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
		$stmt_cnt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
		$stmt_cnt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
		$stmt_cnt->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
		if ($stmt_cnt->execute()) {
			while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
				$rows = $rowset_cnt['foundrows'];
			}
			/* summing total ketetapan desa/kelurahan */
			$stmt_sum_paid_sppt = $dbcon->prepare("SELECT SUM(JML_SPPT_YG_DIBAYAR) as totalsumpaidspptdesa FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND STATUS_PEMBAYARAN_SPPT != 0 AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01'");
			$stmt_sum_paid_sppt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt_sum_paid_sppt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt_sum_paid_sppt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt_sum_paid_sppt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			$stmt_sum_paid_sppt->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
			if ($stmt_sum_paid_sppt->execute()) {
				while($rowset_sum_paid_sppt = $stmt_sum_paid_sppt->fetch(PDO::FETCH_ASSOC)){
					$sumresult = $rowset_sum_paid_sppt['totalsumpaidspptdesa'];
				}
				/* select all sppt */
				$stmt_select_data = $dbcon->prepare("SELECT KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,STATUS_TAGIHAN_SPPT,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND STATUS_PEMBAYARAN_SPPT != 0 AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' ORDER BY KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP");
				$stmt_select_data->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
				$stmt_select_data->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
				$stmt_select_data->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
				$stmt_select_data->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
				$stmt_select_data->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
				if ($stmt_select_data->execute()) {
					$pdf = new FPDF('P','cm',array(21.5,28));
					$pdf->AddPage();
					$pagenum = 1;
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(19.5,0.5,'DAFTAR PEMBAYARAN','',1,'C','');
					$pdf->Cell(19.5,0.5,'PAJAK BUMI DAN BANGUNAN PERDESAAN DAN PERKOTAAN (PBB-P2)','',1,'C','');
					$pdf->Cell(19.5,0.5,'DESA : '.$namaKelurahan.'','',1,'C','');
					$pdf->Cell(19.5,0.5,'KECAMATAN : '.$namaKecamatan.'','',1,'C','');
					$pdf->Cell(19.5,0.5,'TAHUN PAJAK: '.$thnsppt.'','',1,'C','');
					$ofxpages = 1;
					$rownum = 1;
					while($rowset = $stmt_select_data->fetch(PDO::FETCH_ASSOC)){
/* ##################################################################################### */
						/* formatting date */
						$arrTPAY = explode("-", $rowset['TGL_PEMBAYARAN_SPPT']);
						$TGL_PEMBAYARAN_SPPT = strval("".$arrTPAY[2]."/".$arrTPAY[1]."/".$arrTPAY[0]."");
						/* limiting string based on it's length */
						$str_alamatwp = mb_strimwidth(strval($rowset['JLN_WP_SPPT'].', '.$rowset['BLOK_KAV_NO_WP_SPPT'].', RT '.$rowset['RT_WP_SPPT'].'/ RW '.$rowset['RW_WP_SPPT'].''), 0, 26, "");
						$str_namawp = mb_strimwidth($rowset['NM_WP_SPPT'], 0, 21, "");
						if (intval($rows) <= $maxonepagerows) { /* rows <= 32 */
							$ofxpages = 1;
							if (intval($rownum) == 1) {
								/* pre-header: memuat nomor ayat dan indikator halaman */
								$pdf->Cell(15.5,1,'','',0,'L','');
								$pdf->Cell(4,1,'HALAMAN: '.$pagenum.'/'.$ofxpages.'','',1,'R','');
								/* first header */
								
								$pdf->Cell(1,0.75,'No.','TLRB',0,'C','');
								$pdf->Cell(4,0.75,'NOP','TRB',0,'C','');
								$pdf->Cell(4,0.75,'NAMA WP','TRB',0,'C','');
								$pdf->Cell(5,0.75,'ALAMAT WP','TRB',0,'C','');
								$pdf->Cell(2,0.75,'PBB','TRB',0,'C','');
								$pdf->Cell(1.5,0.75,'Tgl.Bayar','TRB',0,'C','');
								$pdf->Cell(2,0.75,'Jml.Bayar','TRB',1,'C','');
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
								$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
								
								} elseif (intval($rownum) == intval($rows)) {
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
								$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
								/* total sum row - last row */
								$pdf->Cell(17.5,0.75,'Total Pembayaran:','LRBT',0,'R','');
								$pdf->Cell(2,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
								/* end document - tanda tangan */
								/* $pdf->Cell(19.5,1.5,'','',1,'C','');
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_NM_DATI2_'].' , 2 Januari '.Date('Y').'','',1,'C','');

								$pdf->Cell(19.5,0.5,'','',1,'C','');
								
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,'KEPALA DINAS PENDAPATAN, PENGELOLAAN','',1,'C','');
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,'KEUANGAN, DAN ASET DAERAH,','',1,'C','');
								
								$pdf->Cell(19.5,1.25,'','',1,'C','');
								
								$pdf->SetFont('Arial','U',8);
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_nama_kadinas_'].'','',1,'C','');
								
								$pdf->SetFont('Arial','',8);
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_pangkat_kadinas_'].'','',1,'C','');
								
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_nip_kadinas_'].'','',1,'C',''); */
								/* /end document - tanda tangan */
							} else {
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
								$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
								
							}
						} elseif (intval($rows) > $maxonepagerows && intval($rows) <= $maxfirstpagerows) { /* rows > 32 & <= 40 */
							$ofxpages = 2;
							if (intval($rownum) == 1) {
								/* pre-header: memuat nomor ayat dan indikator halaman */
								$pdf->Cell(15.5,1,'','',0,'L','');
								$pdf->Cell(4,1,'HALAMAN: '.$pagenum.'/'.$ofxpages.'','',1,'R','');
								/* first header */
								$pdf->Cell(1,0.75,'No.','TLRB',0,'C','');
								$pdf->Cell(4,0.75,'NOP','TRB',0,'C','');
								$pdf->Cell(4,0.75,'NAMA WP','TRB',0,'C','');
								$pdf->Cell(5,0.75,'ALAMAT WP','TRB',0,'C','');
								$pdf->Cell(2,0.75,'PBB','TRB',0,'C','');
								$pdf->Cell(1.5,0.75,'Tgl.Bayar','TRB',0,'C','');
								$pdf->Cell(2,0.75,'Jml.Bayar','TRB',1,'C','');
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
								$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
								
								} elseif (intval($rownum) <= $maxonepagerows) { /* 1 - 32 : intval($rownum) <= $maxonepagerows */
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
								$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
								
								} elseif (intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) < intval($rows)) { /* 33 - 39 : intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) < intval($rows) */
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
								$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
								
								} elseif (intval($rownum) > $maxonepagerows && intval($rownum) == $maxfirstpagerows && intval($rownum) == intval($rows)) { /* 33 - 39 : intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) == intval($rows) */
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
								$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
								
								/* total sum row - last row */
								$pdf->Cell(17.5,0.75,'Total Pembayaran:','LRBT',0,'R','');
								$pdf->Cell(2,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
								/* end document - tanda tangan */
								/* next page indikator */
								/* $pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN PERSETUJUAN','',1,'R',''); */
								
								/* $pagenum++;
								$pdf->AddPage();
								
								$pdf->Cell(19.5,1,'HALAMAN: '.$pagenum.'/'.$ofxpages.'','',1,'R',''); */
								/* end document - tanda tangan */
								/* $pdf->Cell(19.5,1.5,'','',1,'C','');
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_NM_DATI2_'].' , 2 Januari '.Date('Y').'','',1,'C','');
								
								$pdf->Cell(19.5,0.5,'','',1,'C','');
								
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,'KEPALA DINAS PENDAPATAN, PENGELOLAAN','',1,'C','');
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,'KEUANGAN, DAN ASET DAERAH,','',1,'C','');
								
								$pdf->Cell(19.5,1.25,'','',1,'C','');
								
								$pdf->SetFont('Arial','U',8);
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_nama_kadinas_'].'','',1,'C','');
								
								$pdf->SetFont('Arial','',8);
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_pangkat_kadinas_'].'','',1,'C','');
								
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_nip_kadinas_'].'','',1,'C',''); */
								/* /end document - tanda tangan */
							} elseif (intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) == intval($rows)) { /* 33 - 39 : intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) == intval($rows) */
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
								$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
								
								/* total sum row - last row */
								$pdf->Cell(17.5,0.75,'Total Pembayaran:','LRBT',0,'R','');
								$pdf->Cell(2,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
								/* end document - tanda tangan */
								/* next page indikator */
								/* $pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN PERSETUJUAN','',1,'R','');
								
								$pagenum++;
								$pdf->AddPage();
								
								$pdf->Cell(19.5,1,'HALAMAN: '.$pagenum.'/'.$ofxpages.'','',1,'R',''); */
								/* end document - tanda tangan */
								/* $pdf->Cell(19.5,1.5,'','',1,'C','');
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_NM_DATI2_'].' , 2 Januari '.Date('Y').'','',1,'C','');
								
								$pdf->Cell(19.5,0.5,'','',1,'C','');
								
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,'KEPALA DINAS PENDAPATAN, PENGELOLAAN','',1,'C','');
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,'KEUANGAN, DAN ASET DAERAH,','',1,'C','');
								
								$pdf->Cell(19.5,1.25,'','',1,'C','');
								
								$pdf->SetFont('Arial','U',8);
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_nama_kadinas_'].'','',1,'C','');
								
								$pdf->SetFont('Arial','',8);
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_pangkat_kadinas_'].'','',1,'C','');
								
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_nip_kadinas_'].'','',1,'C',''); */
								/* /end document - tanda tangan */
							} else {
								/* do nothing - out of premises */
							}
						} elseif (intval($rows) > $maxonepagerows && intval($rows) > $maxfirstpagerows) { /* rows > 32 & > 40 */
							$baselastpagerows = intval(intval($rows)%37);
							if (intval($baselastpagerows) == 0) { /* base = 0 */
								$totalpages = round((intval($rows)/37),0,PHP_ROUND_HALF_UP) + 1;
							} elseif (intval($baselastpagerows) <= $lastpageminimumrows) { /* base <= 32 */
								$totalpages = round((intval($rows)/37),0,PHP_ROUND_HALF_UP) + 1;
							} elseif (intval($baselastpagerows) > $lastpageminimumrows) { /* base > 32 */
								$totalpages = round((intval($rows)/37),0,PHP_ROUND_HALF_UP) + 1;
							} else {
								/* do nothing - out of premises */
							}
							if (intval($rownum) == 1) {
								/* pre-header: memuat nomor ayat dan indikator halaman */
								$pdf->Cell(15.5,1,'','',0,'L','');
								$pdf->Cell(4,1,'HALAMAN: '.$pagenum.'/'.$totalpages.'','',1,'R','');
								/* first header */
								$pdf->Cell(1,0.75,'No.','TLRB',0,'C','');
								$pdf->Cell(4,0.75,'NOP','TRB',0,'C','');
								$pdf->Cell(4,0.75,'NAMA WP','TRB',0,'C','');
								$pdf->Cell(5,0.75,'ALAMAT WP','TRB',0,'C','');
								$pdf->Cell(2,0.75,'PBB','TRB',0,'C','');
								$pdf->Cell(1.5,0.75,'Tgl.Bayar','TRB',0,'C','');
								$pdf->Cell(2,0.75,'Jml.Bayar','TRB',1,'C','');
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
								$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
								
								$subtotal += intval($rowset['JML_SPPT_YG_DIBAYAR']);
							} elseif (intval($rownum)%37==0) {
								if (intval($rownum) == intval($rows)) {
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
								$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
								
								$subtotal += intval($rowset['PBB_YG_HARUS_DIBAYAR_SPPT']);
									/* total sum row - last row */
								$pdf->Cell(17.5,0.5,'Subtotal:','LRBT',0,'R','');
								$pdf->Cell(2,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
									/* total sum row - last row */
								$pdf->Cell(17.5,0.75,'Total Pembayaran:','LRBT',0,'R','');
								$pdf->Cell(2,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
								$pagenum++;
									/* next page indikator */
								/* $pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN PERSETUJUAN','',1,'R','');
									
								$pdf->AddPage();
									
								$pdf->Cell(19.5,1,'HALAMAN: '.$pagenum.'/'.$ofxpages.'','',1,'R',''); */
								/* end document - tanda tangan */
								/* $pdf->Cell(19.5,1.5,'','',1,'C','');
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_NM_DATI2_'].' , 2 Januari '.Date('Y').'','',1,'C','');
								
								$pdf->Cell(19.5,0.5,'','',1,'C','');
								
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,'KEPALA DINAS PENDAPATAN, PENGELOLAAN','',1,'C','');
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,'KEUANGAN, DAN ASET DAERAH,','',1,'C','');
								
								$pdf->Cell(19.5,1.25,'','',1,'C','');
								
								$pdf->SetFont('Arial','U',8);
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_nama_kadinas_'].'','',1,'C','');
								
								$pdf->SetFont('Arial','',8);
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_pangkat_kadinas_'].'','',1,'C','');
								
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_nip_kadinas_'].'','',1,'C',''); */
								/* /end document - tanda tangan */
								} else {
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
								$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
								
								$subtotal += intval($rowset['JML_SPPT_YG_DIBAYAR']);
								/* total sum row - last row */
								$pdf->Cell(17.5,0.5,'Subtotal:','LRBT',0,'R','');
								$pdf->Cell(2,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
									/* total sum row - last row */
								$pdf->Cell(17.5,0.75,'Total Pembayaran:','LRBT',0,'R','');
								$pdf->Cell(2,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
								$pagenum++;
								/* next page indikator */
								$pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN '.$pagenum.'','',1,'R','');
									/* reset subtotal */
								$subtotal = 0;
									
								$pdf->AddPage();
								}
							} elseif (intval($rownum)%37==1) {
								if (intval($rownum) == intval($rows)) {
									if (intval($baselastpagerows) <= $lastpageminimumrows) {
										$subtotal = 0;
										
										$pdf->Cell(15.5,1,'','',0,'L','');
										$pdf->Cell(4,1,'HALAMAN: '.$pagenum.'/'.$ofxpages.'','',1,'R','');
										/* first header */
										$pdf->Cell(1,0.75,'No.','TLRB',0,'C','');
										$pdf->Cell(4,0.75,'NOP','TRB',0,'C','');
										$pdf->Cell(4,0.75,'NAMA WP','TRB',0,'C','');
										$pdf->Cell(5,0.75,'ALAMAT WP','TRB',0,'C','');
										$pdf->Cell(2,0.75,'PBB','TRB',0,'C','');
										$pdf->Cell(1.5,0.75,'Tgl.Bayar','TRB',0,'C','');
										$pdf->Cell(2,0.75,'Jml.Bayar','TRB',1,'C','');
								
										$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
										$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
										$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
										$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
										$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
										$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
										$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
								
										$subtotal += intval($rowset['JML_SPPT_YG_DIBAYAR']);
										/* subtotal */
										$pdf->Cell(17.5,0.5,'Subtotal:','LRBT',0,'R','');
										$pdf->Cell(2,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
										/* total sum row - last row */
										$pdf->Cell(17.5,0.75,'Total Pembayaran:','LRBT',0,'R','');
										$pdf->Cell(2,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
										/* end document - tanda tangan */
										/* $pdf->Cell(19.5,1.5,'','',1,'C','');
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,''.$appxinfo['_NM_DATI2_'].' , 2 Januari '.Date('Y').'','',1,'C','');
										
										$pdf->Cell(19.5,0.5,'','',1,'C','');
										
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,'KEPALA DINAS PENDAPATAN, PENGELOLAAN','',1,'C','');
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,'KEUANGAN, DAN ASET DAERAH,','',1,'C','');
										
										$pdf->Cell(19.5,1.25,'','',1,'C','');
										
										$pdf->SetFont('Arial','U',8);
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,''.$appxinfo['_nama_kadinas_'].'','',1,'C','');
										
										$pdf->SetFont('Arial','',8);
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,''.$appxinfo['_pangkat_kadinas_'].'','',1,'C','');
										
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,''.$appxinfo['_nip_kadinas_'].'','',1,'C',''); */
										/* /end document - tanda tangan */
									} else {
										$subtotal = 0;
										
										$pdf->Cell(15.5,1,'','',0,'L','');
										$pdf->Cell(4,1,'HALAMAN: '.$pagenum.'/'.$ofxpages.'','',1,'R','');
										/* first header */
										$pdf->Cell(1,0.75,'No.','TLRB',0,'C','');
										$pdf->Cell(4,0.75,'NOP','TRB',0,'C','');
										$pdf->Cell(4,0.75,'NAMA WP','TRB',0,'C','');
										$pdf->Cell(5,0.75,'ALAMAT WP','TRB',0,'C','');
										$pdf->Cell(2,0.75,'PBB','TRB',0,'C','');
										$pdf->Cell(1.5,0.75,'Tgl.Bayar','TRB',0,'C','');
										$pdf->Cell(2,0.75,'Jml.Bayar','TRB',1,'C','');
								
										$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
										$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
										$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
										$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
										$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
										$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
										$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
								
										$subtotal += intval($rowset['JML_SPPT_YG_DIBAYAR']);
										/* subtotal */
										$pdf->Cell(17.5,0.5,'Subtotal:','LRBT',0,'R','');
										$pdf->Cell(2,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
										/* total sum row - last row */
										$pdf->Cell(17.5,0.75,'Total Pembayaran:','LRBT',0,'R','');
										$pdf->Cell(2,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
										$pagenum++;
										/* next page indikator */
										/* $pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN PERSETUJUAN','',1,'R','');
										
										$pdf->AddPage();
										$pdf->Cell(19.5,1,'HALAMAN: '.$pagenum.'/'.$totalpages.'','',1,'R',''); */
										/* end document - tanda tangan */
										/* $pdf->Cell(19.5,1.5,'','',1,'C','');
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,''.$appxinfo['_NM_DATI2_'].' , 2 Januari '.Date('Y').'','',1,'C','');
										
										$pdf->Cell(19.5,0.5,'','',1,'C','');
										
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,'KEPALA DINAS PENDAPATAN, PENGELOLAAN','',1,'C','');
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,'KEUANGAN, DAN ASET DAERAH,','',1,'C','');
										
										$pdf->Cell(19.5,1.25,'','',1,'C','');
										
										$pdf->SetFont('Arial','U',8);
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,''.$appxinfo['_nama_kadinas_'].'','',1,'C','');
										
										$pdf->SetFont('Arial','',8);
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,''.$appxinfo['_pangkat_kadinas_'].'','',1,'C','');
										
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,''.$appxinfo['_nip_kadinas_'].'','',1,'C',''); */
										/* /end document - tanda tangan */
									}
								} else {
									$subtotal = 0;
									
									$pdf->Cell(19.5,1,'HALAMAN: '.$pagenum.'/'.$totalpages.'','',1,'R','');
									/* page header */
									$pdf->Cell(1,0.75,'No.','TLRB',0,'C','');
									$pdf->Cell(4,0.75,'NOP','TRB',0,'C','');
									$pdf->Cell(4,0.75,'NAMA WP','TRB',0,'C','');
									$pdf->Cell(5,0.75,'ALAMAT WP','TRB',0,'C','');
									$pdf->Cell(2,0.75,'PBB','TRB',0,'C','');
									$pdf->Cell(1.5,0.75,'Tgl.Bayar','TRB',0,'C','');
									$pdf->Cell(2,0.75,'Jml.Bayar','TRB',1,'C','');
								
									$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
									$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
									$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
									$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
									$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
									$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
									$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
								
									$subtotal += intval($rowset['JML_SPPT_YG_DIBAYAR']);
									}
							} else {
								if (intval($pagenum) == intval($totalpages)) {
									if (intval($baselastpagerows) == 0) { /* add page + subtotal + total + ttd */
									
									$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
									$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
									$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
									$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
									$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
									$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
									$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
								
									$subtotal += intval($rowset['JML_SPPT_YG_DIBAYAR']);
									} elseif (intval($baselastpagerows) > 0 && intval($baselastpagerows) <= $lastpageminimumrows) { /* subtotal + total + ttd */
										if (intval($rownum) == intval($rows)) {	
											
										$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
										$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
										$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
										$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
										$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
										$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
										$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
									
										$subtotal += intval($rowset['JML_SPPT_YG_DIBAYAR']);
										/* subtotal */
										$pdf->Cell(17.5,0.5,'Subtotal:','LRBT',0,'R','');
										$pdf->Cell(2,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
										/* total sum row - last row */
										$pdf->Cell(17.5,0.75,'Total Pembayaran:','LRBT',0,'R','');
										$pdf->Cell(2,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
										/* end document - tanda tangan */
										/* $pdf->Cell(19.5,1.5,'','',1,'C','');
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,''.$appxinfo['_NM_DATI2_'].' , 2 Januari '.Date('Y').'','',1,'C','');
										
										$pdf->Cell(19.5,0.5,'','',1,'C','');
										
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,'KEPALA DINAS PENDAPATAN, PENGELOLAAN','',1,'C','');
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,'KEUANGAN, DAN ASET DAERAH,','',1,'C','');
										
										$pdf->Cell(19.5,1.25,'','',1,'C','');
										
										$pdf->SetFont('Arial','U',8);
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,''.$appxinfo['_nama_kadinas_'].'','',1,'C','');
										
										$pdf->SetFont('Arial','',8);
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,''.$appxinfo['_pangkat_kadinas_'].'','',1,'C','');
										
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,''.$appxinfo['_nip_kadinas_'].'','',1,'C',''); */
										/* /end document - tanda tangan */
										} else {
											
										$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
										$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
										$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
										$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
										$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
										$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
										$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
									
										$subtotal += intval($rowset['JML_SPPT_YG_DIBAYAR']);
										}
									} else {
										/* do nothing - out of premises */
									}
								} else {
									if (intval($pagenum)==(intval($totalpages)-1)) {
										if (intval($rownum) == intval($rows)) {
											if (intval($baselastpagerows) <= $lastpageminimumrows) {
													
											$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
											$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
											$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
											$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
											$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
											$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
											$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
										
											$subtotal += intval($rowset['JML_SPPT_YG_DIBAYAR']);
											/* subtotal */
											$pdf->Cell(17.5,0.5,'Subtotal:','LRBT',0,'R','');
											$pdf->Cell(2,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
											/* total sum row - last row */
											$pdf->Cell(17.5,0.75,'Total Pembayaran:','LRBT',0,'R','');
											$pdf->Cell(2,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
											/* end document - tanda tangan */
											/* $pdf->Cell(19.5,1.5,'','',1,'C','');
											$pdf->Cell(9.75,0.5,'','',0,'C','');
											$pdf->Cell(9.75,0.5,''.$appxinfo['_NM_DATI2_'].' , 2 Januari '.Date('Y').'','',1,'C','');
											
											$pdf->Cell(19.5,0.5,'','',1,'C','');
											
											$pdf->Cell(9.75,0.5,'','',0,'C','');
											$pdf->Cell(9.75,0.5,'KEPALA DINAS PENDAPATAN, PENGELOLAAN','',1,'C','');
											$pdf->Cell(9.75,0.5,'','',0,'C','');
											$pdf->Cell(9.75,0.5,'KEUANGAN, DAN ASET DAERAH,','',1,'C','');
											
											$pdf->Cell(19.5,1.25,'','',1,'C','');
											
											$pdf->SetFont('Arial','U',8);
											$pdf->Cell(9.75,0.5,'','',0,'C','');
											$pdf->Cell(9.75,0.5,''.$appxinfo['_nama_kadinas_'].'','',1,'C','');
											
											$pdf->SetFont('Arial','',8);
											$pdf->Cell(9.75,0.5,'','',0,'C','');
											$pdf->Cell(9.75,0.5,''.$appxinfo['_pangkat_kadinas_'].'','',1,'C','');
											
											$pdf->Cell(9.75,0.5,'','',0,'C','');
											$pdf->Cell(9.75,0.5,''.$appxinfo['_nip_kadinas_'].'','',1,'C',''); */
											/* /end document - tanda tangan */
											} else {
													
											$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
											$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
											$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
											$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
											$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
											$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
											$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
										
											$subtotal += intval($rowset['JML_SPPT_YG_DIBAYAR']);
											/* subtotal */
											$pdf->Cell(17.5,0.5,'Subtotal:','LRBT',0,'R','');
											$pdf->Cell(2,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
											/* total sum row - last row */
											$pdf->Cell(17.5,0.75,'Total Pembayaran:','LRBT',0,'R','');
											$pdf->Cell(2,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
											$pagenum++;
											/* next page indikator */
											/* $pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN PERSETUJUAN','',1,'R','');
												
											$pdf->AddPage();
											$pdf->Cell(19.5,1,'HALAMAN: '.$pagenum.'/'.$totalpages.'','',1,'R',''); */
											/* end document - tanda tangan */
											/* $pdf->Cell(19.5,1.5,'','',1,'C','');
											$pdf->Cell(9.75,0.5,'','',0,'C','');
											$pdf->Cell(9.75,0.5,''.$appxinfo['_NM_DATI2_'].' , 2 Januari '.Date('Y').'','',1,'C','');
											
											$pdf->Cell(19.5,0.5,'','',1,'C','');
											
											$pdf->Cell(9.75,0.5,'','',0,'C','');
											$pdf->Cell(9.75,0.5,'KEPALA DINAS PENDAPATAN, PENGELOLAAN','',1,'C','');
											$pdf->Cell(9.75,0.5,'','',0,'C','');
											$pdf->Cell(9.75,0.5,'KEUANGAN, DAN ASET DAERAH,','',1,'C','');
											
											$pdf->Cell(19.5,1.25,'','',1,'C','');
											
											$pdf->SetFont('Arial','U',8);
											$pdf->Cell(9.75,0.5,'','',0,'C','');
											$pdf->Cell(9.75,0.5,''.$appxinfo['_nama_kadinas_'].'','',1,'C','');
											
											$pdf->SetFont('Arial','',8);
											$pdf->Cell(9.75,0.5,'','',0,'C','');
											$pdf->Cell(9.75,0.5,''.$appxinfo['_pangkat_kadinas_'].'','',1,'C','');
											
											$pdf->Cell(9.75,0.5,'','',0,'C','');
											$pdf->Cell(9.75,0.5,''.$appxinfo['_nip_kadinas_'].'','',1,'C',''); */
											/* /end document - tanda tangan */
											}
										} else {
													
											$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
											$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
											$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
											$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
											$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
											$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
											$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
										
											$subtotal += intval($rowset['JML_SPPT_YG_DIBAYAR']);
											}
									} else {
										$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
										$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
										$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
										$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
										$pdf->Cell(2,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,',','.'),'R',0,'R','');
										$pdf->Cell(1.5,0.5,$TGL_PEMBAYARAN_SPPT,'R',0,'C','');
										$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
										
										$subtotal += intval($rowset['JML_SPPT_YG_DIBAYAR']);
									}
								}
							}
						} else {
							/* do nothing - out of premises */
						}
						$rownum++;
/* ##################################################################################### */
					}
				} else {
					$pdf->SetFont('Arial','',11);
					$pdf->Text(1,1,'Warning: SQL failure. Contact Administrator.');
				}
			} else {
				$pdf->SetFont('Arial','',11);
				$pdf->Text(1,1,'Warning: SQL failure. Contact Administrator.');
			}
		} else {
			$pdf->SetFont('Arial','',11);
			$pdf->Text(1,1,'Warning: SQL failure. Contact Administrator.');
		}
	} catch (PDOException $e) {
		$pdf->SetFont('Arial','',11);
		$pdf->Text(1,1,'Warning: Database connection failure: '.$e->getMessage().'');
	}
} else {
	$pdf->SetFont('Arial','',11);
	$pdf->Text(1,1,'Warning: Unauthorized access to PDF generator. Please provide an authorized access.');
}
$pdf->Output();
?>