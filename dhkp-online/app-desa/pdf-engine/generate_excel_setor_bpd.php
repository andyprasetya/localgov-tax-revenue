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
$objPHPExcel->getProperties()->setCreator("MAPATDA BKAD")
							 ->setLastModifiedBy("BKAD Kabupaten Kulon Progo")
							 ->setTitle("SETORAN PEMBAYARAN BANK PER-PETUGAS")
							 ->setSubject("PAJAK BUMI DAN BANGUNAN PERDESAAN DAN PERKOTAAN ( PBB-P2 )")
							 ->setDescription("DHKP Online")
							 ->setKeywords("BKAD,DHKP,Online")
							 ->setCategory("Microsoft Office 2007 Excel Document");
/* set advanced value binder */
PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );

if (isset($_SESSION['desaXweb'])) {
	/* $hashcodex = $_GET['codex'];
	$petugasidx = $_GET['idxpetugas'];
	$namaPetugas = $_GET['namapetugas']; */
	try {
		$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
		$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		/* selecting data */
		$stmt = $dbcon->prepare("SELECT * FROM temp_sppt_data WHERE IDX LIKE CONCAT('%',:kdprov,'|',:kdkab,'|',:kdkec,'|',:kddesa,'|%')");
		$stmt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
		$stmt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
		$stmt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
		$stmt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
		if ($stmt->execute()) {
			$rowcount = 1;
			while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
				$arrinfosppt = strval(''.$rowset['KD_PROPINSI'].''.$rowset['KD_DATI2'].''.$rowset['KD_KECAMATAN'].''.$rowset['KD_KELURAHAN'].''.$rowset['KD_BLOK'].''.$rowset['NO_URUT'].''.$rowset['KD_JNS_OP'].'');
				/* data looping - lihat row counter nya.. */
				$objPHPExcel->setActiveSheetIndex(0)
				/* ->setCellValue('A'.$rowcount.'', ''.$arrinfosppt.'') */
				->setCellValueExplicit('A'.$rowcount.'', ''.$arrinfosppt.'', PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValue('B'.$rowcount.'', ''.$rowset['THN_PAJAK_SPPT'].'')
				->setCellValue('C'.$rowcount.'', ''.$rowset['NM_WP_SPPT'].'')
				->setCellValue('D'.$rowcount.'', ''.$rowset['PBB_YG_HARUS_DIBAYAR_SPPT'].'')
				->setCellValue('E'.$rowcount.'', ''.$rowset['DENDA_SPPT'].'');
				/* formatting */
				$objPHPExcel->getActiveSheet()
					    ->getStyle('B'.$rowcount.'')->getNumberFormat()
					    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
				$objPHPExcel->getActiveSheet()
							->getStyle('D'.$rowcount.'')->getNumberFormat()
							->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
				$objPHPExcel->getActiveSheet()
							->getStyle('E'.$rowcount.'')->getNumberFormat()
							->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
				$rowcount++;
			}
			/* cleaning temporary data */
			$stmt_clearing_data = $dbcon->prepare("DELETE FROM temp_sppt_data WHERE IDX LIKE CONCAT('%',:kdprov,'|',:kdkab,'|',:kdkec,'|',:kddesa,'|%')");
			$stmt_clearing_data->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt_clearing_data->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt_clearing_data->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt_clearing_data->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			$stmt_clearing_data->execute();
			$stmt_optimiser = $dbcon->query("OPTIMIZE TABLE temp_sppt_data");
		} else {
			$objPHPExcel->setActiveSheetIndex(0)
									->setCellValue('A1', 'Error: No data found.');
		}
	} catch (PDOException $e) {
		$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A1', 'Error: '.$e->getMessage().'');
	}
} else {
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Error: No active session.');
}

/* Rename worksheet */
$objPHPExcel->getActiveSheet()->setTitle('Daftar Pembayaran PBB');
$objPHPExcel->setActiveSheetIndex(0);
$namaPetugas = preg_replace('/\s+/', '', $namaPetugas);
/* Redirect output ke web browser (Excel2007) */
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="SETORAN_PEMBAYARAN_BPD_'.$_SESSION['desaXweb']['NM_KELURAHAN'].'_'.Date('dmY_His').'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
/* kosongkan data */

/* /kosongkan data */
exit;
?>