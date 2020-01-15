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
$subtotal2 = 0;
$totalpages = 0;
$pdf = new FPDF('P','cm',array(21.5,28));
//if (isset($_SESSION['desaXweb'])) {
	/*$maxonepagerows = 32;
	$maxfirstpagerows = 37;
	$kode_propinsi = $_SESSION['desaXweb']['KD_PROPINSI'];
	$kode_dati2 = $_SESSION['desaXweb']['KD_DATI2'];
	$kode_kecamatan = $_SESSION['desaXweb']['KD_KECAMATAN'];
	$kode_kelurahan = $_SESSION['desaXweb']['KD_KELURAHAN'];
	$namaKecamatan = $_SESSION['desaXweb']['NM_KECAMATAN'];
	$namaKelurahan = $_SESSION['desaXweb']['NM_KELURAHAN'];*/
	
	$kode_propinsi = '33';
	$kode_dati2 = '23';
	$kode_kecamatan = '130';
	$kode_kelurahan = '003';
	$namaKecamatan = 'KECAMATAN';
	$namaKelurahan = 'KELURAHAN';
	
	$thnsppt = $appxinfo['_tahun_pajak_'];
	$dbcon = oci_connect("".$appxinfo['_oracle_sismiop_user_']."", "".$appxinfo['_oracle_sismiop_pass_']."", "//".$appxinfo['_oracle_sismiop_host_'].":".$appxinfo['_oracle_sismiop_port_']."/".$appxinfo['_oracle_sismiop_service_']."");
	if (!$dbcon) {
		$errMsg = oci_error();
		trigger_error(htmlentities($errMsg['message']), E_USER_ERROR);
	} else {
		/* block count SPPT */
		$querycount = 'SELECT COUNT(*) "foundrows" FROM PEMBAYARAN_SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL';
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
		$querysum = 'SELECT SUM(PEMBAYARAN_SPPT.JML_SPPT_YG_DIBAYAR) "foundsum", SUM(SPPT.KETETAPAN) "sumketetapan" FROM PEMBAYARAN_SPPT, SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL';
		$stmtsum = oci_parse($dbcon, $querysum);
		/* binding statements */
		oci_bind_by_name($stmtsum, ":KODEPROP", $kode_propinsi);
		oci_bind_by_name($stmtsum, ":KODEDATI2", $kode_dati2);
		oci_bind_by_name($stmtsum, ":KODEKEC", $kode_kecamatan);
		oci_bind_by_name($stmtsum, ":KODEDESKEL", $kode_kelurahan);
		oci_bind_by_name($stmtsum, ":THNSPPT", $thnsppt);
		if (oci_execute($stmtsum)) {
			while ($rowsetsum = oci_fetch_array($stmtsum, OCI_RETURN_NULLS+OCI_ASSOC)) {
				$sumresult1 = $rowsetsum['foundsum'];
				$sumresult2 = $rowsetsum['sumketetapan'];
			}
			oci_free_statement($stmtsum);
		} else {
			$sumresult1 = 0;
			$sumresult2 = 0;
		}
		/* /block sum SPPT */
		/* block select all data SPPT */
		$query = "SELECT 
	a.KD_PROPINSI||'.'||a.KD_DATI2||'.'||a.KD_KECAMATAN||'.'||a.KD_KELURAHAN||'.'||a.KD_BLOK||'.'||a.NO_URUT||'-'||A.KD_JNS_OP AS NOP,
	a.THN_PAJAK_SPPT AS THN_PAJAK_SPPT,
	a.PEMBAYARAN_SPPT_KE AS PEMBAYARAN_SPPT_KE,
	a.KD_KANWIL_BANK AS KD_KANWIL_BANK,
	a.KD_KPPBB_BANK AS KD_KPPBB_BANK,
	a.KD_BANK_TUNGGAL AS KD_BANK_TUNGGAL,
	a.KD_BANK_PERSEPSI AS KD_BANK_PERSEPSI,
	a.KD_TP AS KD_TP,
	a.DENDA_SPPT AS DENDA_SPPT,
	a.JML_SPPT_YG_DIBAYAR AS JML_SPPT_YG_DIBAYAR,
	TO_CHAR(a.TGL_PEMBAYARAN_SPPT, 'yyyy-mm-dd') AS TGL_PEMBAYARAN_SPPT,
	TO_CHAR(a.TGL_REKAM_BYR_SPPT, 'yyyy-mm-dd') AS TGL_REKAM_BYR_SPPT,
	a.NIP_REKAM_BYR_SPPT AS NIP_REKAM_BYR_SPPT,
	b.NM_WP_SPPT AS NAMA,
	b.JLN_WP_SPPT||', '||b.BLOK_KAV_NO_WP_SPPT||' RT '||b.RT_WP_SPPT||'/ '||b.RW_WP_SPPT AS ALAMAT, 
	b.LUAS_BUMI_SPPT AS L_BUMI, 
	b.LUAS_BNG_SPPT as L_BGN, 
	b.PBB_YG_HARUS_DIBAYAR_SPPT AS KETETAPAN, 
	TO_CHAR(b.TGL_TERBIT_SPPT,'yyyy-mm-dd') AS TGL_TERBIT_SPPT, 
	b.TGL_CETAK_SPPT AS TGL_CETAK_SPPT, 
	b.STATUS_PEMBAYARAN_SPPT AS STATUS_PEMBAYARAN_SPPT 
FROM PEMBAYARAN_SPPT a 
INNER JOIN SPPT b 
ON 
	a.KD_PROPINSI = b.KD_PROPINSI 
	AND a.KD_DATI2 = b.KD_DATI2 
	AND a.KD_KECAMATAN = b.KD_KECAMATAN 
	AND a.KD_KELURAHAN = b.KD_KELURAHAN 
	AND a.KD_BLOK = b.KD_BLOK 
	AND a.NO_URUT = b.NO_URUT 
	AND a.KD_JNS_OP = b.KD_JNS_OP 
	AND a.THN_PAJAK_SPPT = b.THN_PAJAK_SPPT 
WHERE 
	a.THN_PAJAK_SPPT = :THNSPPT 
	AND a.KD_PROPINSI = :KODEPROP 
	AND a.KD_DATI2 = :KODEDATI2 
	AND a.KD_KECAMATAN = :KODEKEC 
	AND a.KD_KELURAHAN = :KODEDESKEL 
ORDER BY a.KD_PROPINSI||''||a.KD_DATI2||''||a.KD_KECAMATAN||''||a.KD_KELURAHAN||''||a.KD_BLOK||''||a.NO_URUT||''||a.KD_JNS_OP";
		$stmt = oci_parse($dbcon, $query);
		/* binding statements */
		oci_bind_by_name($stmt, ":KODEPROP", $kode_propinsi);
		oci_bind_by_name($stmt, ":KODEDATI2", $kode_dati2);
		oci_bind_by_name($stmt, ":KODEKEC", $kode_kecamatan);
		oci_bind_by_name($stmt, ":KODEDESKEL", $kode_kelurahan);
		oci_bind_by_name($stmt, ":THNSPPT", $thnsppt);
		if (oci_execute($stmt)) {
			/* /initial variables */
			$pdf->AddPage();
			$pagenum = 1;
			$pdf->SetFont('Arial','',8);
			//$pdf->Cell(19.5,0.5,'DAFTAR HIMPUNAN KETETAPAN PAJAK (DHKP)','',1,'C','');
			$pdf->Cell(19.5,0.5,'DAFTAR PEMBAYARAN PBB','',1,'C','');
			$pdf->Cell(19.5,0.5,'DESA : '.$namaKelurahan.'','',1,'C','');
			$pdf->Cell(19.5,0.5,'KECAMATAN : '.$namaKecamatan.'','',1,'C','');
			$pdf->Cell(19.5,0.5,'TAHUN PAJAK: '.$appxinfo['_tahun_pajak_'].'','',1,'C','');
			$ofxpages = 1;
			$rownum = 1;
			while ($rowset = oci_fetch_array($stmt, OCI_RETURN_NULLS+OCI_ASSOC)) {
/* ##################################################################################### */
				/* limiting string based on it's length */
				$arrTglTAP = explode("-", $rowset['TGL_PEMBAYARAN_SPPT']);
				$tgl_bayar = strval("".$arrTglTAP[2]."/".$arrTglTAP[1]."/".$arrTglTAP[0]."");
									
				$str_alamatwp = mb_strimwidth($rowset['ALAMAT'], 0, 26, "");
				$str_namawp = mb_strimwidth($rowset['NAMA'], 0, 21, "");
				if (intval($rows) <= $maxonepagerows) { /* rows <= 32 */
					$ofxpages = 1;
					if (intval($rownum) == 1) {
						/* pre-header: memuat nomor ayat dan indikator halaman */
						/* first header */
						$pdf->Cell(1,0.75,'No.','TLRB',0,'C','');
						$pdf->Cell(4,0.75,'NOP','TRB',0,'C','');
						$pdf->Cell(4,0.75,'NAMA WP','TRB',0,'C','');
						$pdf->Cell(5,0.75,'ALAMAT WP','TRB',0,'C','');
						$pdf->Cell(2,0.75,'PBB','TRB',0,'C','');
						$pdf->Cell(1.5,0.75,'Tgl.Bayar','TRB',0,'C','');
						$pdf->Cell(2,0.75,'Jml.Bayar','TRB',1,'C','');
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
						$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
						$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
						
						} elseif (intval($rownum) == intval($rows)) {
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
						$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
						$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
						
						/* total sum row - last row */
						$pdf->Cell(14,0.75,'Total   : PBB :','LRBT',0,'R','');
						$pdf->Cell(2,0.75,number_format($sumresult1,2,',','.'),'RBT',0,'R','');
						$pdf->Cell(1.5,0.75,'Bayar :','RBT',0,'R','');
						$pdf->Cell(2,0.75,number_format($sumresult2,2,',','.'),'RBT',1,'R','');
						
					} else {
				
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
						$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
						$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
						
					}
				} elseif (intval($rows) > $maxonepagerows && intval($rows) <= $maxfirstpagerows) { /* rows > 32 & <= 40 */
					$ofxpages = 2;
					if (intval($rownum) == 1) {
						/* pre-header: memuat nomor ayat dan indikator halaman */
						/* first header */
						$pdf->Cell(1,0.75,'No.','TLRB',0,'C','');
						$pdf->Cell(4,0.75,'NOP','TRB',0,'C','');
						$pdf->Cell(4,0.75,'NAMA WP','TRB',0,'C','');
						$pdf->Cell(5,0.75,'ALAMAT WP','TRB',0,'C','');
						$pdf->Cell(2,0.75,'PBB','TRB',0,'C','');
						$pdf->Cell(1.5,0.75,'Tgl.Bayar','TRB',0,'C','');
						$pdf->Cell(2,0.75,'Jml.Bayar','TRB',1,'C','');
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
						$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
						$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
						
						} elseif (intval($rownum) <= $maxonepagerows) { /* 1 - 32 : intval($rownum) <= $maxonepagerows */
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
						$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
						$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
						
						} elseif (intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) < intval($rows)) { /* 33 - 39 : intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) < intval($rows) */
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
						$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
						$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
						
						} elseif (intval($rownum) > $maxonepagerows && intval($rownum) == $maxfirstpagerows && intval($rownum) == intval($rows)) { /* 33 - 39 : intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) == intval($rows) */
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
						$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
						$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
						
						/* total sum row - last row */
						$pdf->Cell(14,0.75,'Total   : PBB :','LRBT',0,'R','');
						$pdf->Cell(2,0.75,number_format($sumresult1,2,',','.'),'RBT',0,'R','');
						$pdf->Cell(1.5,0.75,'Bayar :','RBT',0,'R','');
						$pdf->Cell(2,0.75,number_format($sumresult2,2,',','.'),'RBT',1,'R','');
						/* end document - tanda tangan */
						
						$pagenum++;
						
					} elseif (intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) == intval($rows)) { /* 33 - 39 : intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) == intval($rows) */
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
						$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
						$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
						
						/* total sum row - last row */
						$pdf->Cell(14,0.75,'Total   : PBB :','LRBT',0,'R','');
						$pdf->Cell(2,0.75,number_format($sumresult1,2,',','.'),'RBT',0,'R','');
						$pdf->Cell(1.5,0.75,'Bayar :','RBT',0,'R','');
						$pdf->Cell(2,0.75,number_format($sumresult2,2,',','.'),'RBT',1,'R','');
						/* end document - tanda tangan */
						
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
						/* first header */
						$pdf->Cell(1,0.75,'No.','TLRB',0,'C','');
						$pdf->Cell(4,0.75,'NOP','TRB',0,'C','');
						$pdf->Cell(4,0.75,'NAMA WP','TRB',0,'C','');
						$pdf->Cell(5,0.75,'ALAMAT WP','TRB',0,'C','');
						$pdf->Cell(2,0.75,'PBB','TRB',0,'C','');
						$pdf->Cell(1.5,0.75,'Tgl.Bayar','TRB',0,'C','');
						$pdf->Cell(2,0.75,'Jml.Bayar','TRB',1,'C','');
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
						$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
						$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
						
						$subtotal1 += intval($rowset['KETETAPAN']);
						$subtotal2 += intval($rowset['JML_SPPT_YG_DIBAYAR']);
					} elseif (intval($rownum)%37==0) {
						if (intval($rownum) == intval($rows)) {
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
						$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
						$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
						
						$subtotal1 += intval($rowset['KETETAPAN']);
						$subtotal2 += intval($rowset['JML_SPPT_YG_DIBAYAR']);
						/* total sum row - last row */
						
						$pdf->Cell(14,0.75,'Subtotal   : PBB :','LRBT',0,'R','');
						$pdf->Cell(2,0.75,number_format($subtotal1,2,',','.'),'RBT',0,'R','');
						$pdf->Cell(1.5,0.75,'Bayar :','RBT',0,'R','');
						$pdf->Cell(2,0.75,number_format($subtotal2,2,',','.'),'RBT',1,'R','');
							/* total sum row - last row */
						$pdf->Cell(14,0.75,'Total   : PBB :','LRBT',0,'R','');
						$pdf->Cell(2,0.75,number_format($sumresult1,2,',','.'),'RBT',0,'R','');
						$pdf->Cell(1.5,0.75,'Bayar :','RBT',0,'R','');
						$pdf->Cell(2,0.75,number_format($sumresult2,2,',','.'),'RBT',1,'R','');
						
						$pagenum++;
							/* next page indikator */
						
						} else {
						
						$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
						$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
						$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
						$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
						$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
						$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
						$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
						
						$subtotal1 += intval($rowset['KETETAPAN']);
						$subtotal2 += intval($rowset['JML_SPPT_YG_DIBAYAR']);
						/* total sum row - last row */
						
						$pdf->Cell(14,0.75,'Subtotal   : PBB :','LRBT',0,'R','');
						$pdf->Cell(2,0.75,number_format($subtotal1,2,',','.'),'RBT',0,'R','');
						$pdf->Cell(1.5,0.75,'Bayar :','RBT',0,'R','');
						$pdf->Cell(2,0.75,number_format($subtotal2,2,',','.'),'RBT',1,'R','');
							/* total sum row - last row */
						$pdf->Cell(14,0.75,'Total   : PBB :','LRBT',0,'R','');
						$pdf->Cell(2,0.75,number_format($sumresult1,2,',','.'),'RBT',0,'R','');
						$pdf->Cell(1.5,0.75,'Bayar :','RBT',0,'R','');
						$pdf->Cell(2,0.75,number_format($sumresult2,2,',','.'),'RBT',1,'R','');
						
						$pagenum++;
		
							/* reset subtotal */
						$subtotal1 = 0;
						$subtotal2 = 0;
							
						$pdf->AddPage();
						}
					} elseif (intval($rownum)%37==1) {
						if (intval($rownum) == intval($rows)) {
							if (intval($baselastpagerows) <= $lastpageminimumrows) {
								$subtotal1 = 0;
								$subtotal2 = 0;
								/* pre-header: memuat nomor ayat dan indikator halaman */
								/* first header */
								$pdf->Cell(1,0.75,'No.','TLRB',0,'C','');
								$pdf->Cell(4,0.75,'NOP','TRB',0,'C','');
								$pdf->Cell(4,0.75,'NAMA WP','TRB',0,'C','');
								$pdf->Cell(5,0.75,'ALAMAT WP','TRB',0,'C','');
								$pdf->Cell(2,0.75,'PBB','TRB',0,'C','');
								$pdf->Cell(1.5,0.75,'Tgl.Bayar','TRB',0,'C','');
								$pdf->Cell(2,0.75,'Jml.Bayar','TRB',1,'C','');
						
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
								$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
								$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
						
								$subtotal1 += intval($rowset['KETETAPAN']);
								$subtotal2 += intval($rowset['JML_SPPT_YG_DIBAYAR']);
								/* subtotal */
								
								$pdf->Cell(14,0.75,'Subtotal   : PBB :','LRBT',0,'R','');
								$pdf->Cell(2,0.75,number_format($subtotal1,2,',','.'),'RBT',0,'R','');
								$pdf->Cell(1.5,0.75,'Bayar :','RBT',0,'R','');
								$pdf->Cell(2,0.75,number_format($subtotal2,2,',','.'),'RBT',1,'R','');
									/* total sum row - last row */
								$pdf->Cell(14,0.75,'Total   : PBB :','LRBT',0,'R','');
								$pdf->Cell(2,0.75,number_format($sumresult1,2,',','.'),'RBT',0,'R','');
								$pdf->Cell(1.5,0.75,'Bayar :','RBT',0,'R','');
								$pdf->Cell(2,0.75,number_format($sumresult2,2,',','.'),'RBT',1,'R','');
						
							} else {
								$subtotal1 = 0;
								$subtotal2 = 0;
								/* pre-header: memuat nomor ayat dan indikator halaman */
								/* first header */
								$pdf->Cell(1,0.75,'No.','TLRB',0,'C','');
								$pdf->Cell(4,0.75,'NOP','TRB',0,'C','');
								$pdf->Cell(4,0.75,'NAMA WP','TRB',0,'C','');
								$pdf->Cell(5,0.75,'ALAMAT WP','TRB',0,'C','');
								$pdf->Cell(2,0.75,'PBB','TRB',0,'C','');
								$pdf->Cell(1.5,0.75,'Tgl.Bayar','TRB',0,'C','');
								$pdf->Cell(2,0.75,'Jml.Bayar','TRB',1,'C','');
						
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
								$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
								$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
						
								$subtotal1 += intval($rowset['KETETAPAN']);
								$subtotal2 += intval($rowset['JML_SPPT_YG_DIBAYAR']);
								/* subtotal */
								
								$pdf->Cell(14,0.75,'Subtotal   : PBB :','LRBT',0,'R','');
								$pdf->Cell(2,0.75,number_format($subtotal1,2,',','.'),'RBT',0,'R','');
								$pdf->Cell(1.5,0.75,'Bayar :','RBT',0,'R','');
								$pdf->Cell(2,0.75,number_format($subtotal2,2,',','.'),'RBT',1,'R','');
									/* total sum row - last row */
								$pdf->Cell(14,0.75,'Total   : PBB :','LRBT',0,'R','');
								$pdf->Cell(2,0.75,number_format($sumresult1,2,',','.'),'RBT',0,'R','');
								$pdf->Cell(1.5,0.75,'Bayar :','RBT',0,'R','');
								$pdf->Cell(2,0.75,number_format($sumresult2,2,',','.'),'RBT',1,'R','');
						
								$pagenum++;
								/* next page indikator */
								
							}
						} else {
							$subtotal1 = 0;
							$subtotal2 = 0;
						/* pre-header: memuat nomor ayat dan indikator halaman */
							/* first header */
							$pdf->Cell(1,0.75,'No.','TLRB',0,'C','');
							$pdf->Cell(4,0.75,'NOP','TRB',0,'C','');
							$pdf->Cell(4,0.75,'NAMA WP','TRB',0,'C','');
							$pdf->Cell(5,0.75,'ALAMAT WP','TRB',0,'C','');
							$pdf->Cell(2,0.75,'PBB','TRB',0,'C','');
							$pdf->Cell(1.5,0.75,'Tgl.Bayar','TRB',0,'C','');
							$pdf->Cell(2,0.75,'Jml.Bayar','TRB',1,'C','');
						
							$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
							$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
							$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
							$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
							$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
							$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
							$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
					
							$subtotal1 += intval($rowset['KETETAPAN']);
							$subtotal2 += intval($rowset['JML_SPPT_YG_DIBAYAR']);
							}
					} else {
						if (intval($pagenum) == intval($totalpages)) {
							if (intval($baselastpagerows) == 0) { /* add page + subtotal + total + ttd */
							
							$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
							$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
							$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
							$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
							$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
							$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
							$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
					
							$subtotal1 += intval($rowset['KETETAPAN']);
							$subtotal2 += intval($rowset['JML_SPPT_YG_DIBAYAR']);
							
							} elseif (intval($baselastpagerows) > 0 && intval($baselastpagerows) <= $lastpageminimumrows) { /* subtotal + total + ttd */
								if (intval($rownum) == intval($rows)) {	
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
								$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
								$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
					
								$subtotal1 += intval($rowset['KETETAPAN']);
								$subtotal2 += intval($rowset['JML_SPPT_YG_DIBAYAR']);
								/* subtotal */
								
								$pdf->Cell(14,0.75,'Subtotal   : PBB :','LRBT',0,'R','');
								$pdf->Cell(2,0.75,number_format($subtotal1,2,',','.'),'RBT',0,'R','');
								$pdf->Cell(1.5,0.75,'Bayar :','RBT',0,'R','');
								$pdf->Cell(2,0.75,number_format($subtotal2,2,',','.'),'RBT',1,'R','');
									/* total sum row - last row */
								$pdf->Cell(14,0.75,'Total   : PBB :','LRBT',0,'R','');
								$pdf->Cell(2,0.75,number_format($sumresult1,2,',','.'),'RBT',0,'R','');
								$pdf->Cell(1.5,0.75,'Bayar :','RBT',0,'R','');
								$pdf->Cell(2,0.75,number_format($sumresult2,2,',','.'),'RBT',1,'R','');
						
								} else {
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
								$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
								$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
					
								$subtotal1 += intval($rowset['KETETAPAN']);
								$subtotal2 += intval($rowset['JML_SPPT_YG_DIBAYAR']);
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
									$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
									$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
									$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
					
									$subtotal1 += intval($rowset['KETETAPAN']);
									$subtotal2 += intval($rowset['JML_SPPT_YG_DIBAYAR']);
									/* subtotal */
									
									$pdf->Cell(14,0.75,'Subtotal   : PBB :','LRBT',0,'R','');
									$pdf->Cell(2,0.75,number_format($subtotal1,2,',','.'),'RBT',0,'R','');
									$pdf->Cell(1.5,0.75,'Bayar :','RBT',0,'R','');
									$pdf->Cell(2,0.75,number_format($subtotal2,2,',','.'),'RBT',1,'R','');
										/* total sum row - last row */
									$pdf->Cell(14,0.75,'Total   : PBB :','LRBT',0,'R','');
									$pdf->Cell(2,0.75,number_format($sumresult1,2,',','.'),'RBT',0,'R','');
									$pdf->Cell(1.5,0.75,'Bayar :','RBT',0,'R','');
									$pdf->Cell(2,0.75,number_format($sumresult2,2,',','.'),'RBT',1,'R','');
						
									} else {
									
									$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
									$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
									$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
									$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
									$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
									$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
									$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
					
									$subtotal1 += intval($rowset['KETETAPAN']);
									$subtotal2 += intval($rowset['JML_SPPT_YG_DIBAYAR']);
									/* subtotal */
									
									$pdf->Cell(14,0.75,'Subtotal   : PBB :','LRBT',0,'R','');
									$pdf->Cell(2,0.75,number_format($subtotal1,2,',','.'),'RBT',0,'R','');
									$pdf->Cell(1.5,0.75,'Bayar :','RBT',0,'R','');
									$pdf->Cell(2,0.75,number_format($subtotal2,2,',','.'),'RBT',1,'R','');
										/* total sum row - last row */
									$pdf->Cell(14,0.75,'Total   : PBB :','LRBT',0,'R','');
									$pdf->Cell(2,0.75,number_format($sumresult1,2,',','.'),'RBT',0,'R','');
									$pdf->Cell(1.5,0.75,'Bayar :','RBT',0,'R','');
									$pdf->Cell(2,0.75,number_format($sumresult2,2,',','.'),'RBT',1,'R','');
						
									$pagenum++;
									/* next page indikator */
									
									}
								} else {
									
									$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
									$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
									$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
									$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
									$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
									$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
									$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
					
									$subtotal1 += intval($rowset['KETETAPAN']);
									$subtotal2 += intval($rowset['JML_SPPT_YG_DIBAYAR']);
									}
							} else {
									
									$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
									$pdf->Cell(4,0.5,$rowset['NOP'],'R',0,'C','');
									$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
									$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
									$pdf->Cell(2,0.5,number_format($rowset['KETETAPAN'],2,',','.'),'R',0,'R','');
									$pdf->Cell(1.5,0.5,$tgl_bayar,'R',0,'R','');
									$pdf->Cell(2,0.5,number_format($rowset['JML_SPPT_YG_DIBAYAR'],0,',','.'),'R',1,'R','');
					
									$subtotal += intval($rowset['KETETAPAN']);
									$subtotal2 += intval($rowset['JML_SPPT_YG_DIBAYAR']);
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
/*} else {
	$pdf->AddPage();
	$pdf->SetFont('Arial','',11);
	$pdf->Text(1,1,'Warning: Unauthorized access to PDF generator. Please provide an authorized access.');
}*/
$pdf->Output();
?>