<?php
/* Todo: header/footer setiap halaman, ada x of xx, bersambung..., sambungan, subtotal + total, etc..
 */
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
$strinfo = $_GET['idx'];
$strONE = explode("|", $strinfo);
$useridx = $strONE[0];
$username = $strONE[1];
$baselastpagerows = 0;
$lastpageminimumrows = 32;
$subtotal = 0;
$subtotal2 = 0;
$totalpages = 0;
$pdf = new FPDF('P','cm',array(21.5,28));
if (isset($_SESSION['desaXweb'])) {
	$thnsppt = $appxinfo['_tahun_pajak_'];
	$maxonepagerows = 32;
	$maxfirstpagerows = 37;
	try {
		$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
		$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		/* count */
		$stmt_cnt = $dbcon->prepare("SELECT COUNT(*) AS foundrows FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND PETUGASIDX = :idxpetugas AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND JML_SPPT_YG_DIBAYAR = 0");
		$stmt_cnt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
		$stmt_cnt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
		$stmt_cnt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
		$stmt_cnt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
		$stmt_cnt->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
		$stmt_cnt->bindValue(":idxpetugas", $strONE[0], PDO::PARAM_INT);
		if ($stmt_cnt->execute()) {
			while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
				$rows = $rowset_cnt['foundrows'];
			}
			if (intval($rows)>0) {
				$stmt_sum = $dbcon->prepare("SELECT SUM(PBB_YG_HARUS_DIBAYAR_SPPT) AS sumtotal FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND PETUGASIDX = :idxpetugas AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND JML_SPPT_YG_DIBAYAR = 0");
				$stmt_sum->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
				$stmt_sum->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
				$stmt_sum->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
				$stmt_sum->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
				$stmt_sum->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
				$stmt_sum->bindValue(":idxpetugas", $useridx, PDO::PARAM_INT);
				$stmt_sum->execute();
				while($rowset_sum = $stmt_sum->fetch(PDO::FETCH_ASSOC)){
					$sumresult = $rowset_sum['sumtotal'];
				}
				/* start generate PDF */
				$pdf->AddPage();
				$pagenum = 1;
				$pdf->SetFont('Arial','',8);
				$pdf->Cell(9.75,0.5,'REKAPITULASI PBB-P2 PER PETUGAS','LTR',0,'L','');
				$pdf->Cell(9.75,0.5,'BELUM BAYAR','TR',1,'L','');
				$pdf->Cell(3,0.5,'KECAMATAN','LTR',0,'L','');
				$pdf->Cell(6.75,0.5,''.$_SESSION['desaXweb']['NM_KECAMATAN'].'','TR',0,'L','');
				$pdf->Cell(3,0.5,'TAHUN PAJAK','TR',0,'L','');
				$pdf->Cell(6.75,0.5,''.$thnsppt.'','TR',1,'L','');
				$pdf->Cell(3,0.5,'DESA','LTRB',0,'L','');
				$pdf->Cell(6.75,0.5,''.$_SESSION['desaXweb']['NM_KELURAHAN'].'','TRB',0,'L','');
				$pdf->Cell(3,0.5,'PETUGAS PUNGUT','TRB',0,'L','');
				$pdf->Cell(6.75,0.5,''.$username.'','TRB',1,'L','');
				$ofxpages = 1;
				$rownum = 1;
				/* ========================================================================== */
				$stmt = $dbcon->prepare("SELECT * FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND PETUGASIDX = :idxpetugas AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND JML_SPPT_YG_DIBAYAR = 0");
				$stmt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
				$stmt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
				$stmt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
				$stmt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
				$stmt->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
				$stmt->bindValue(":idxpetugas", $useridx, PDO::PARAM_INT);
				$stmt->execute();
				while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
					/* limiting string based on it's length */
					$str_alamatwp = mb_strimwidth(strval($rowset['JLN_WP_SPPT'].', '.$rowset['BLOK_KAV_NO_WP_SPPT'].', RW: '.$rowset['RW_WP_SPPT'].'/ RT :'.$rowset['RT_WP_SPPT']), 0, 26, "");
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
							$pdf->Cell(1.25,0.75,'L.Bumi','TRB',0,'C','');
							$pdf->Cell(1.25,0.75,'L.Bng','TRB',0,'C','');
							$pdf->Cell(3,0.75,'KETETAPAN','TRB',1,'C','');
							
							$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
							$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
							$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
							$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
							
							} elseif (intval($rownum) == intval($rows)) {
							
							$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
							$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
							$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
							$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
							/* total sum row - last row */
							$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
							$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
							
						} else {
							
							$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
							$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
							$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
							$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
							
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
							$pdf->Cell(1.25,0.75,'L.Bumi','TRB',0,'C','');
							$pdf->Cell(1.25,0.75,'L.Bng','TRB',0,'C','');
							$pdf->Cell(3,0.75,'KETETAPAN','TRB',1,'C','');
							
							$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
							$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
							$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
							$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
							
							} elseif (intval($rownum) <= $maxonepagerows) { /* 1 - 32 : intval($rownum) <= $maxonepagerows */
							
							$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
							$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
							$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
							$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
							
							} elseif (intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) < intval($rows)) { /* 33 - 39 : intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) < intval($rows) */
							
							$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
							$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
							$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
							$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
							
							} elseif (intval($rownum) > $maxonepagerows && intval($rownum) == $maxfirstpagerows && intval($rownum) == intval($rows)) { /* 33 - 39 : intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) == intval($rows) */
							
							$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
							$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
							$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
							$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
							
							/* total sum row - last row */
							$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
							$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
							/* end document - tanda tangan */
							/* next page indikator */
							//$pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN PERSETUJUAN','',1,'R','');
							
							$pagenum++;
							
						} elseif (intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) == intval($rows)) { /* 33 - 39 : intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) == intval($rows) */
							
							$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
							$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
							$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
							$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
							
							/* total sum row - last row */
							$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
							$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
							/* end document - tanda tangan */
							/* next page indikator */
							//$pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN PERSETUJUAN','',1,'R','');
							
							$pagenum++;
							
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
							$pdf->Cell(1.25,0.75,'L.Bumi','TRB',0,'C','');
							$pdf->Cell(1.25,0.75,'L.Bng','TRB',0,'C','');
							$pdf->Cell(3,0.75,'KETETAPAN','TRB',1,'C','');
							
							$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
							$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
							$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
							$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
							
							$subtotal += intval($rowset['PBB_YG_HARUS_DIBAYAR_SPPT']);
						} elseif (intval($rownum)%37==0) {
							if (intval($rownum) == intval($rows)) {
							
							$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
							$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
							$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
							$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
							
							$subtotal += intval($rowset['PBB_YG_HARUS_DIBAYAR_SPPT']);
								/* total sum row - last row */
							$pdf->Cell(16.5,0.5,'Subtotal:','LRBT',0,'R','');
							$pdf->Cell(3,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
								/* total sum row - last row */
							$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
							$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
							$pagenum++;
						
							} else {
							
							$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
							$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
							$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
							$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
							$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
							
							$subtotal += intval($rowset['PBB_YG_HARUS_DIBAYAR_SPPT']);
							/* total sum row - last row */
							$pdf->Cell(16.5,0.5,'Subtotal:','LRBT',0,'R','');
							$pdf->Cell(3,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
								/* total sum row - last row */
							$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
							$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
							$pagenum++;
							/* next page indikator */
							//$pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN '.$pagenum.'','',1,'R','');
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
									$pdf->Cell(1.25,0.75,'L.Bumi','TRB',0,'C','');
									$pdf->Cell(1.25,0.75,'L.Bng','TRB',0,'C','');
									$pdf->Cell(3,0.75,'KETETAPAN','TRB',1,'C','');
							
									$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
									$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
									$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
									$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
									$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
									$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
									$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
							
									$subtotal += intval($rowset['PBB_YG_HARUS_DIBAYAR_SPPT']);
									/* subtotal */
									$pdf->Cell(16.5,0.5,'Subtotal:','LRBT',0,'R','');
									$pdf->Cell(3,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
									/* total sum row - last row */
									$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
									$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
									
								} else {
									$subtotal = 0;
									
									$pdf->Cell(15.5,1,'','',0,'L','');
									$pdf->Cell(4,1,'HALAMAN: '.$pagenum.'/'.$ofxpages.'','',1,'R','');
									/* first header */
									$pdf->Cell(1,0.75,'No.','TLRB',0,'C','');
									$pdf->Cell(4,0.75,'NOP','TRB',0,'C','');
									$pdf->Cell(4,0.75,'NAMA WP','TRB',0,'C','');
									$pdf->Cell(5,0.75,'ALAMAT WP','TRB',0,'C','');
									$pdf->Cell(1.25,0.75,'L.Bumi','TRB',0,'C','');
									$pdf->Cell(1.25,0.75,'L.Bng','TRB',0,'C','');
									$pdf->Cell(3,0.75,'KETETAPAN','TRB',1,'C','');
							
									$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
									$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
									$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
									$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
									$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
									$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
									$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
							
									$subtotal += intval($rowset['PBB_YG_HARUS_DIBAYAR_SPPT']);
									/* subtotal */
									$pdf->Cell(16.5,0.5,'Subtotal:','LRBT',0,'R','');
									$pdf->Cell(3,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
									/* total sum row - last row */
									$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
									$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
									$pagenum++;
									/* next page indikator */
									
								}
							} else {
								$subtotal = 0;
								
								$pdf->Cell(19.5,1,'HALAMAN: '.$pagenum.'/'.$totalpages.'','',1,'R','');
								/* page header */
								$pdf->Cell(1,0.75,'No.','TLRB',0,'C','');
								$pdf->Cell(4,0.75,'NOP','TRB',0,'C','');
								$pdf->Cell(4,0.75,'NAMA WP','TRB',0,'C','');
								$pdf->Cell(5,0.75,'ALAMAT WP','TRB',0,'C','');
								$pdf->Cell(1.25,0.75,'L.Bumi','TRB',0,'C','');
								$pdf->Cell(1.25,0.75,'L.Bng','TRB',0,'C','');
								$pdf->Cell(3,0.75,'KETETAPAN','TRB',1,'C','');
							
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
							
								$subtotal += intval($rowset['PBB_YG_HARUS_DIBAYAR_SPPT']);
								}
						} else {
							if (intval($pagenum) == intval($totalpages)) {
								if (intval($baselastpagerows) == 0) { /* add page + subtotal + total + ttd */
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
							
								$subtotal += intval($rowset['KETETAPAN']);
								} elseif (intval($baselastpagerows) > 0 && intval($baselastpagerows) <= $lastpageminimumrows) { /* subtotal + total + ttd */
									if (intval($rownum) == intval($rows)) {	
										
									$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
									$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
									$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
									$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
									$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
									$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
									$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
								
									$subtotal += intval($rowset['PBB_YG_HARUS_DIBAYAR_SPPT']);
									/* subtotal */
									$pdf->Cell(16.5,0.5,'Subtotal:','LRBT',0,'R','');
									$pdf->Cell(3,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
									/* total sum row - last row */
									$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
									$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
									
									} else {
										
									$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
									$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
									$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
									$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
									$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
									$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
									$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
								
									$subtotal += intval($rowset['PBB_YG_HARUS_DIBAYAR_SPPT']);
									}
								} else {
									/* do nothing - out of premises */
								}
							} else {
								if (intval($pagenum)==(intval($totalpages)-1)) {
									if (intval($rownum) == intval($rows)) {
										if (intval($baselastpagerows) <= $lastpageminimumrows) {
												
										$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
										$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
										$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
										$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
										$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
										$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
										$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
									
										$subtotal += intval($rowset['PBB_YG_HARUS_DIBAYAR_SPPT']);
										/* subtotal */
										$pdf->Cell(16.5,0.5,'Subtotal:','LRBT',0,'R','');
										$pdf->Cell(3,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
										/* total sum row - last row */
										$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
										$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
										
										} else {
												
										$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
										$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
										$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
										$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
										$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
										$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
										$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
									
										$subtotal += intval($rowset['PBB_YG_HARUS_DIBAYAR_SPPT']);
										/* subtotal */
										$pdf->Cell(16.5,0.5,'Subtotal:','LRBT',0,'R','');
										$pdf->Cell(3,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
										/* total sum row - last row */
										$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
										$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
										$pagenum++;
										/* next page indikator */
										
										}
									} else {
												
										$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
										$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
										$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
										$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
										$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
										$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
										$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
									
										$subtotal += intval($rowset['PBB_YG_HARUS_DIBAYAR_SPPT']);
										}
								} else {
												
										$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
										$pdf->Cell(4,0.5,strval($rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''),'R',0,'C','');
										$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
										$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
										$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
										$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
										$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
									
										$subtotal += intval($rowset['PBB_YG_HARUS_DIBAYAR_SPPT']);
										}
							}
						}
					} else {
						/* do nothing - out of premises */
					}
					$rownum++;
				}
				/* ========================================================================== */
			} else {
				$pdf->AddPage();
				$pdf->SetFont('Arial','',11);
				$pdf->Text(1,1,'Warning: Data untuk petugas pungut: '.$strONE[1].' tidak ditemukan');
			}
		} else {
			$pdf->AddPage();
			$pdf->SetFont('Arial','',11);
			$pdf->Text(1,1,'Warning: Data untuk petugas pungut: '.$strONE[1].' tidak ditemukan');
		}
	} catch (PDOException $e) {
		$pdf->AddPage();
		$pdf->SetFont('Arial','',11);
		$pdf->Text(1,1,'Warning: '.$e->getMessage().'');
	}
} else {
	$pdf->AddPage();
	$pdf->SetFont('Arial','',11);
	$pdf->Text(1,1,'Warning: Unauthorized access to PDF generator. Please provide an authorized access.');
}
$pdf->Output();
?>