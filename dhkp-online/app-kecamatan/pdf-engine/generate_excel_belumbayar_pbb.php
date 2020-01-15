<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Jakarta');
/* SESSION START */
session_start();
$txsessionid = session_id();
/* VARIABLES */
require_once dirname(__FILE__) . '/../../variablen.php';
require_once dirname(__FILE__) . '/../../einstellung.php';
require_once dirname(__FILE__) . '/../../funktion.php';
/* ==================================== */
if (PHP_SAPI == 'cli')
	die('Anda harus mengeksekusi perintah ini dengan menggunakan Web Browser');

/* Include PHPExcel */
require_once dirname(__FILE__) . '/../local-assets/PHPExcel/PHPExcel.php';
require_once dirname(__FILE__) . '/../local-assets/PHPExcel/PHPExcel/Cell/AdvancedValueBinder.php';

/* Create new PHPExcel object */
$objPHPExcel = new PHPExcel();

/* Set document properties */
$objPHPExcel->getProperties()->setCreator("MAPATDA BPPKAD")
							 ->setLastModifiedBy("BPPKAD Kabupaten Temanggung")
							 ->setTitle("DAFTAR TAGIHAN PBB-P2")
							 ->setSubject("PAJAK BUMI DAN BANGUNAN PERDESAAN DAN PERKOTAAN ( PBB-P2 )")
							 ->setDescription("DHKP Online")
							 ->setKeywords("BPPKAD,DHKP,Online")
							 ->setCategory("Microsoft Office 2007 Excel Document");
/* set advanced value binder */
PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );

if (isset($_SESSION['desaXweb'])) {
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
					$querycount = 'SELECT COUNT(*) "foundrows" FROM SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL AND STATUS_PEMBAYARAN_SPPT = 0';
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
					$querysum = 'SELECT SUM(PBB_YG_HARUS_DIBAYAR_SPPT) "foundsum" FROM SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL AND STATUS_PEMBAYARAN_SPPT = 0';
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
					$query = "SELECT a.KD_PROPINSI||'.'||a.KD_DATI2||'.'||a.KD_KECAMATAN||'.'||a.KD_KELURAHAN||'.'||a.KD_BLOK||'.'||a.NO_URUT||'-'||A.KD_JNS_OP  AS NOP, a.NM_WP_SPPT AS NAMA,a.JLN_WP_SPPT||', '||a.BLOK_KAV_NO_WP_SPPT||' RT '||a.RT_WP_SPPT||'/ '||a.RW_WP_SPPT AS ALAMAT,a.LUAS_BUMI_SPPT AS L_BUMI,a.LUAS_BNG_SPPT as L_BGN, a.PBB_YG_HARUS_DIBAYAR_SPPT AS KETETAPAN FROM SPPT a WHERE a.THN_PAJAK_SPPT = :THNSPPT AND a.KD_PROPINSI = :KODEPROP AND a.KD_DATI2 = :KODEDATI2 AND a.KD_KECAMATAN = :KODEKEC AND a.KD_KELURAHAN = :KODEDESKEL AND a.STATUS_PEMBAYARAN_SPPT = 0 ORDER BY a.KD_PROPINSI||''||a.KD_DATI2||''||a.KD_KECAMATAN||''||a.KD_KELURAHAN||''||a.KD_BLOK||''||a.NO_URUT||''||a.KD_JNS_OP";
					$stmt = oci_parse($dbcon, $query);
					/* binding statements */
					oci_bind_by_name($stmt, ":KODEPROP", $kode_propinsi);
					oci_bind_by_name($stmt, ":KODEDATI2", $kode_dati2);
					oci_bind_by_name($stmt, ":KODEKEC", $kode_kecamatan);
					oci_bind_by_name($stmt, ":KODEDESKEL", $kode_kelurahan);
					oci_bind_by_name($stmt, ":THNSPPT", $thnsppt);
					
					if (oci_execute($stmt)) {
	
						$rowcount = 8;
						$rownum = 1;
						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B1', 'DAFTAR TAGIHAN PBB-P2')
							->setCellValue('B3', 'DESA : '.$namaKelurahan.'')
							->setCellValue('B4', 'KECAMATAN : '.$namaKecamatan.'')
							->setCellValue('B5', 'TAHUN PAJAK: '.$appxinfo['_tahun_pajak_'].'')
				
							->setCellValue('A7', 'No.')
							->setCellValue('B7', 'NOP')
							->setCellValue('C7', 'NAMA WP')
							->setCellValue('D7', 'ALAMAT WP')
							->setCellValue('E7', 'L.Bumi')
							->setCellValue('F7', 'L.Bng')
							->setCellValue('G7', 'KETETAPAN');
	
						while ($rowset = oci_fetch_array($stmt, OCI_RETURN_NULLS+OCI_ASSOC)) {
							/* data looping - lihat row counter nya.. */
							$objPHPExcel->setActiveSheetIndex(0)
				
							->setCellValue('A'.$rowcount.'', ''.$rownum.'')
							->setCellValue('B'.$rowcount.'', ''.$rowset['NOP'].'')
							->setCellValue('C'.$rowcount.'', ''.$rowset['NAMA'].'')
							->setCellValue('D'.$rowcount.'', ''.$rowset['ALAMAT'].'')
							->setCellValue('E'.$rowcount.'', ''.$rowset['L_BUMI'].'')
							->setCellValue('F'.$rowcount.'', ''.$rowset['L_BGN'].'')
							->setCellValue('G'.$rowcount.'', ''.$rowset['KETETAPAN'].'');
							/* formatting */
							$objPHPExcel->getActiveSheet()
										->getStyle('E'.$rowcount.'')->getNumberFormat()
										->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
							$objPHPExcel->getActiveSheet()
										->getStyle('F'.$rowcount.'')->getNumberFormat()
										->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
							$objPHPExcel->getActiveSheet()
										->getStyle('G'.$rowcount.'')->getNumberFormat()
										->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
							$rowcount++;
							$rownum++;
						}
						$objPHPExcel->setActiveSheetIndex(0)
						
						->setCellValue('F'.$rowcount.'', 'Total')
						->setCellValue('G'.$rowcount.'', ''.$sumresult.'');
							/* formatting */
							$objPHPExcel->getActiveSheet()
										->getStyle('G'.$rowcount.'')->getNumberFormat()
										->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					
					} else {
							oci_close($dbcon);
						}
				}

} else {
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Error: No active session.');
}

/* Rename worksheet */
$objPHPExcel->getActiveSheet()->setTitle('DHKP Desa');
$objPHPExcel->setActiveSheetIndex(0);
/* Redirect output ke web browser (Excel2007) */
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="DAFTAR_TAGIHAN_'.$kode_propinsi.''.$kode_dati2.''.$kode_kecamatan.''.$kode_kelurahan.'_'.$namaKelurahan.'_PBB-P2_'.Date('dmY_His').'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>