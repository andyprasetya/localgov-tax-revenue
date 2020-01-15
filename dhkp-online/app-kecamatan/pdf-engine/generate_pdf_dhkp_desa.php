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
	$thnsppt = $appxinfo['_tahun_pajak_'];
	$dbcon = oci_connect("".$appxinfo['_oracle_sismiop_user_']."", "".$appxinfo['_oracle_sismiop_pass_']."", "//".$appxinfo['_oracle_sismiop_host_'].":".$appxinfo['_oracle_sismiop_port_']."/".$appxinfo['_oracle_sismiop_service_']."");
	if (!$dbcon) {
		$errMsg = oci_error();
		trigger_error(htmlentities($errMsg['message']), E_USER_ERROR);
	} else {
		/* block count SPPT */
		$querycount = 'SELECT COUNT(*) "foundrows" FROM SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL';
		$stmtcount = oci_parse($dbcon, $querycount);
		/* binding statements */
		oci_bind_by_name($stmtcount, ":KODEPROP", $kode_propinsi);
		oci_bind_by_name($stmtcount, ":KODEDATI2", $kode_dati2);
		oci_bind_by_name($stmtcount, ":KODEKEC", $kode_kecamatan);
		oci_bind_by_name($stmtcount, ":KODEDESKEL", $kode_kelurahan);
		oci_bind_by_name($stmtcount, ":THNSPPT", $thnsppt);
		if (oci_execute($stmtcount)) {
			while ($rowsetcount = oci_fetch_array($stmtcount, OCI_RETURN_NULLS+OCI_ASSOC)) {
				$rows = $rowsetcount['foundrows'];
			}
			oci_free_statement($stmtcount);
		} else {
			$rows = 0;
		}
		/* /block count SPPT */
		/* block sum SPPT */
		$querysum = 'SELECT SUM(PBB_YG_HARUS_DIBAYAR_SPPT) "foundsum" FROM SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL';
		$stmtsum = oci_parse($dbcon, $querysum);
		/* binding statements */
		oci_bind_by_name($stmtsum, ":KODEPROP", $kode_propinsi);
		oci_bind_by_name($stmtsum, ":KODEDATI2", $kode_dati2);
		oci_bind_by_name($stmtsum, ":KODEKEC", $kode_kecamatan);
		oci_bind_by_name($stmtsum, ":KODEDESKEL", $kode_kelurahan);
		oci_bind_by_name($stmtsum, ":THNSPPT", $thnsppt);
		if (oci_execute($stmtsum)) {
			while ($rowsetsum = oci_fetch_array($stmtsum, OCI_RETURN_NULLS+OCI_ASSOC)) {
				$sumresult = $rowsetsum['foundsum'];
			}
			oci_free_statement($stmtsum);
		} else {
			$sumresult = 0;
		}
		/* /block sum SPPT */
		/* block select all data SPPT */
		$query = "SELECT a.KD_PROPINSI||'.'||a.KD_DATI2||'.'||a.KD_KECAMATAN||'.'||a.KD_KELURAHAN||'.'||a.KD_BLOK||'.'||a.NO_URUT||'-'||A.KD_JNS_OP  AS NOP, a.NM_WP_SPPT AS NAMA,a.JLN_WP_SPPT||', '||a.BLOK_KAV_NO_WP_SPPT||' RT '||a.RT_WP_SPPT||'/ '||a.RW_WP_SPPT AS ALAMAT,a.LUAS_BUMI_SPPT AS L_BUMI,a.LUAS_BNG_SPPT as L_BGN, a.PBB_YG_HARUS_DIBAYAR_SPPT AS KETETAPAN FROM SPPT a WHERE a.THN_PAJAK_SPPT = :THNSPPT AND a.KD_PROPINSI = :KODEPROP AND a.KD_DATI2 = :KODEDATI2 AND a.KD_KECAMATAN = :KODEKEC AND a.KD_KELURAHAN = :KODEDESKEL ORDER BY a.KD_PROPINSI||''||a.KD_DATI2||''||a.KD_KECAMATAN||''||a.KD_KELURAHAN||''||a.KD_BLOK||''||a.NO_URUT||''||a.KD_JNS_OP";
		$stmt = oci_parse($dbcon, $query);
		/* binding statements */
		oci_bind_by_name($stmt, ":KODEPROP", $kode_propinsi);
		oci_bind_by_name($stmt, ":KODEDATI2", $kode_dati2);
		oci_bind_by_name($stmt, ":KODEKEC", $kode_kecamatan);
		oci_bind_by_name($stmt, ":KODEDESKEL", $kode_kelurahan);
		oci_bind_by_name($stmt, ":THNSPPT", $thnsppt);
		if (oci_execute($stmt)) {
			/* /initial variables */
			$pdf = new FPDF('P','cm',array(21.5,28));
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',17);
			$pdf->Cell(19.5,4,'BPPKAD  KABUPATEN  TEMANGGUNG','',1,'C','');
			
			$pdf->Cell(19.5,9,'','',1,'C','');
			$pdf->Image('../local-assets/img/logo-pemda-jpg.jpg',8.5,5.5,4.5,6.5);
			
			$pdf->SetFont('Arial','B',40);
			$pdf->Cell(19.5,3,'D H K P','',1,'C','');
			
			$pdf->SetFont('Arial','B',17);
			$pdf->Cell(19.5,1,'( DAFTAR HIMPUNAN KETETAPAN PAJAK )','',1,'C','');
			$pdf->Cell(19.5,0.75,'','',1,'C','');
			$pdf->Cell(19.5,1,'PAJAK BUMI DAN BANGUNAN PERDESAAN DAN','',1,'C','');
			$pdf->Cell(19.5,1,'PERKOTAAN ( PBB-P2 )','',1,'C','');
			$pdf->Cell(19.5,1,'','',1,'C','');
			$pdf->Cell(19.5,1,'KELURAHAN '.$namaKelurahan.'','',1,'C','');
			$pdf->Cell(19.5,1,'KECAMATAN '.$namaKecamatan.'','',1,'C','');
			$pdf->Cell(19.5,1,'','',1,'C','');
			$pdf->Cell(19.5,1,'TAHUN 2017','',1,'C','');
			/* /cover */
			$pdf->AddPage();
			$pagenum = 1;
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(19.5,0.5,'DAFTAR HIMPUNAN KETETAPAN PAJAK (DHKP)','',1,'C','');
			$pdf->Cell(19.5,0.5,'PAJAK BUMI DAN BANGUNAN PERDESAAN DAN PERKOTAAN (PBB-P2)','',1,'C','');
			$pdf->Cell(19.5,0.5,'DESA : '.$namaKelurahan.'','',1,'C','');
			$pdf->Cell(19.5,0.5,'KECAMATAN : '.$namaKecamatan.'','',1,'C','');
			$pdf->Cell(19.5,0.5,'TAHUN PAJAK: '.$appxinfo['_tahun_pajak_'].'','',1,'C','');
			$ofxpages = 1;
			$rownum = 1;
			while ($rowset = oci_fetch_array($stmt, OCI_RETURN_NULLS+OCI_ASSOC)) {
/* ##################################################################################### */
				/* limiting string based on it's length */
				$str_alamatwp = mb_strimwidth($rowset['ALAMAT'], 0, 26, "");
				$str_namawp = mb_strimwidth($rowset['NAMA'], 0, 21, "");
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
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
						$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
						
						} elseif (intval($rownum) == intval($rows)) {
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
						$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
						/* total sum row - last row */
						$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
						$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
						/* end document - tanda tangan */
						$pdf->Cell(19.5,1.5,'','',1,'C','');
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
						$pdf->Cell(9.75,0.5,''.$appxinfo['_nip_kadinas_'].'','',1,'C','');
						/* /end document - tanda tangan */
					} else {
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
						$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
						
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
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
						$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
						
						} elseif (intval($rownum) <= $maxonepagerows) { /* 1 - 32 : intval($rownum) <= $maxonepagerows */
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
						$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
						
						} elseif (intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) < intval($rows)) { /* 33 - 39 : intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) < intval($rows) */
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
						$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
						
						} elseif (intval($rownum) > $maxonepagerows && intval($rownum) == $maxfirstpagerows && intval($rownum) == intval($rows)) { /* 33 - 39 : intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) == intval($rows) */
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
						$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
						
						/* total sum row - last row */
						$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
						$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
						/* end document - tanda tangan */
						/* next page indikator */
						$pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN PERSETUJUAN','',1,'R','');
						
						$pagenum++;
						$pdf->AddPage();
						
						$pdf->Cell(19.5,1,'HALAMAN: '.$pagenum.'/'.$ofxpages.'','',1,'R','');
						/* end document - tanda tangan */
						$pdf->Cell(19.5,1.5,'','',1,'C','');
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
						$pdf->Cell(9.75,0.5,''.$appxinfo['_nip_kadinas_'].'','',1,'C','');
						/* /end document - tanda tangan */
					} elseif (intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) == intval($rows)) { /* 33 - 39 : intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) == intval($rows) */
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
						$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
						
						/* total sum row - last row */
						$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
						$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
						/* end document - tanda tangan */
						/* next page indikator */
						$pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN PERSETUJUAN','',1,'R','');
						
						$pagenum++;
						$pdf->AddPage();
						
						$pdf->Cell(19.5,1,'HALAMAN: '.$pagenum.'/'.$ofxpages.'','',1,'R','');
						/* end document - tanda tangan */
						$pdf->Cell(19.5,1.5,'','',1,'C','');
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
						$pdf->Cell(9.75,0.5,''.$appxinfo['_nip_kadinas_'].'','',1,'C','');
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
						$pdf->Cell(1.25,0.75,'L.Bumi','TRB',0,'C','');
						$pdf->Cell(1.25,0.75,'L.Bng','TRB',0,'C','');
						$pdf->Cell(3,0.75,'KETETAPAN','TRB',1,'C','');
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
						$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
						
						$subtotal += intval($rowset['KETETAPAN']);
					} elseif (intval($rownum)%37==0) {
						if (intval($rownum) == intval($rows)) {
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
						$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
						
						$subtotal += intval($rowset['KETETAPAN']);
							/* total sum row - last row */
						$pdf->Cell(16.5,0.5,'Subtotal:','LRBT',0,'R','');
						$pdf->Cell(3,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
							/* total sum row - last row */
						$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
						$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
						$pagenum++;
							/* next page indikator */
						$pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN PERSETUJUAN','',1,'R','');
							
						$pdf->AddPage();
							
						$pdf->Cell(19.5,1,'HALAMAN: '.$pagenum.'/'.$ofxpages.'','',1,'R','');
						/* end document - tanda tangan */
						$pdf->Cell(19.5,1.5,'','',1,'C','');
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
						$pdf->Cell(9.75,0.5,''.$appxinfo['_nip_kadinas_'].'','',1,'C','');
						/* /end document - tanda tangan */
						} else {
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
						$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
						$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
						
						$subtotal += intval($rowset['KETETAPAN']);
						/* total sum row - last row */
						$pdf->Cell(16.5,0.5,'Subtotal:','LRBT',0,'R','');
						$pdf->Cell(3,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
							/* total sum row - last row */
						$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
						$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
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
								$pdf->Cell(1.25,0.75,'L.Bumi','TRB',0,'C','');
								$pdf->Cell(1.25,0.75,'L.Bng','TRB',0,'C','');
								$pdf->Cell(3,0.75,'KETETAPAN','TRB',1,'C','');
						
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
								$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
						
								$subtotal += intval($rowset['KETETAPAN']);
								/* subtotal */
								$pdf->Cell(16.5,0.5,'Subtotal:','LRBT',0,'R','');
								$pdf->Cell(3,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
								/* total sum row - last row */
								$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
								$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
								/* end document - tanda tangan */
								$pdf->Cell(19.5,1.5,'','',1,'C','');
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
								$pdf->Cell(9.75,0.5,''.$appxinfo['_nip_kadinas_'].'','',1,'C','');
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
								$pdf->Cell(1.25,0.75,'L.Bumi','TRB',0,'C','');
								$pdf->Cell(1.25,0.75,'L.Bng','TRB',0,'C','');
								$pdf->Cell(3,0.75,'KETETAPAN','TRB',1,'C','');
						
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
								$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
						
								$subtotal += intval($rowset['KETETAPAN']);
								/* subtotal */
								$pdf->Cell(16.5,0.5,'Subtotal:','LRBT',0,'R','');
								$pdf->Cell(3,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
								/* total sum row - last row */
								$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
								$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
								$pagenum++;
								/* next page indikator */
								$pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN PERSETUJUAN','',1,'R','');
								
								$pdf->AddPage();
								$pdf->Cell(19.5,1,'HALAMAN: '.$pagenum.'/'.$totalpages.'','',1,'R','');
								/* end document - tanda tangan */
								$pdf->Cell(19.5,1.5,'','',1,'C','');
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
								$pdf->Cell(9.75,0.5,''.$appxinfo['_nip_kadinas_'].'','',1,'C','');
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
							$pdf->Cell(1.25,0.75,'L.Bumi','TRB',0,'C','');
							$pdf->Cell(1.25,0.75,'L.Bng','TRB',0,'C','');
							$pdf->Cell(3,0.75,'KETETAPAN','TRB',1,'C','');
						
							$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
							$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
							$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
							$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
							$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
							$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
							$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
						
							$subtotal += intval($rowset['KETETAPAN']);
							}
					} else {
						if (intval($pagenum) == intval($totalpages)) {
							if (intval($baselastpagerows) == 0) { /* add page + subtotal + total + ttd */
							
							$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
							$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
							$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
							$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
							$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
							$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
							$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
						
							$subtotal += intval($rowset['KETETAPAN']);
							} elseif (intval($baselastpagerows) > 0 && intval($baselastpagerows) <= $lastpageminimumrows) { /* subtotal + total + ttd */
								if (intval($rownum) == intval($rows)) {	
									
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
								$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
							
								$subtotal += intval($rowset['KETETAPAN']);
								/* subtotal */
								$pdf->Cell(16.5,0.5,'Subtotal:','LRBT',0,'R','');
								$pdf->Cell(3,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
								/* total sum row - last row */
								$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
								$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
								/* end document - tanda tangan */
								$pdf->Cell(19.5,1.5,'','',1,'C','');
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
								$pdf->Cell(9.75,0.5,''.$appxinfo['_nip_kadinas_'].'','',1,'C','');
								/* /end document - tanda tangan */
								} else {
									
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
								$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
							
								$subtotal += intval($rowset['KETETAPAN']);
								}
							} else {
								/* do nothing - out of premises */
							}
						} else {
							if (intval($pagenum)==(intval($totalpages)-1)) {
								if (intval($rownum) == intval($rows)) {
									if (intval($baselastpagerows) <= $lastpageminimumrows) {
											
									$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
									$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
									$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
									$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
									$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
									$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
									$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
								
									$subtotal += intval($rowset['KETETAPAN']);
									/* subtotal */
									$pdf->Cell(16.5,0.5,'Subtotal:','LRBT',0,'R','');
									$pdf->Cell(3,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
									/* total sum row - last row */
									$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
									$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
									/* end document - tanda tangan */
									$pdf->Cell(19.5,1.5,'','',1,'C','');
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
									$pdf->Cell(9.75,0.5,''.$appxinfo['_nip_kadinas_'].'','',1,'C','');
									/* /end document - tanda tangan */
									} else {
											
									$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
									$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
									$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
									$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
									$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
									$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
									$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
								
									$subtotal += intval($rowset['KETETAPAN']);
									/* subtotal */
									$pdf->Cell(16.5,0.5,'Subtotal:','LRBT',0,'R','');
									$pdf->Cell(3,0.5,number_format($subtotal,2,',','.'),'RBT',1,'R','');
									/* total sum row - last row */
									$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
									$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
									$pagenum++;
									/* next page indikator */
									$pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN PERSETUJUAN','',1,'R','');
										
									$pdf->AddPage();
									$pdf->Cell(19.5,1,'HALAMAN: '.$pagenum.'/'.$totalpages.'','',1,'R','');
									/* end document - tanda tangan */
									$pdf->Cell(19.5,1.5,'','',1,'C','');
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
									$pdf->Cell(9.75,0.5,''.$appxinfo['_nip_kadinas_'].'','',1,'C','');
									/* /end document - tanda tangan */
									}
								} else {
											
									$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
									$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
									$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
									$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
									$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
									$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
									$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
								
									$subtotal += intval($rowset['KETETAPAN']);
									}
							} else {
											
									$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
									$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
									$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
									$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
									$pdf->Cell(1.25,0.5,number_format($rowset['L_BUMI'],0,',','.'),'R',0,'R','');
									$pdf->Cell(1.25,0.5,number_format($rowset['L_BGN'],0,',','.'),'R',0,'R','');
									$pdf->Cell(3,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',1,'R','');
								
									$subtotal += intval($rowset['KETETAPAN']);
									}
						}
					}
				} else {
					/* do nothing - out of premises */
				}
				$rownum++;
/* ##################################################################################### */
			}
			oci_free_statement($stmt);
			oci_close($dbcon);
		} else {
			oci_close($dbcon);
		}
		/* /block select all data SPPT */
	}
} else {
	$pdf->SetFont('Arial','',11);
	$pdf->Text(1,1,'Warning: Unauthorized access to PDF generator. Please provide an authorized access.');
}
$pdf->Output();
?>