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
							 ->setTitle("DAFTAR PEMBAYARAN PBB")
							 ->setSubject("PAJAK BUMI DAN BANGUNAN PERDESAAN DAN PERKOTAAN ( PBB-P2 )")
							 ->setDescription("DHKP Online")
							 ->setKeywords("BKAD,DHKP,Online")
							 ->setCategory("Microsoft Office 2007 Excel Document");
/* set advanced value binder */
PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );

if (isset($_SESSION['desaXweb'])) {
	$rawpetugasidx = $_GET['idx'];
	$arrPetugasInfo = explode("|", $rawpetugasidx);
	$petugasidx = $arrPetugasInfo[0];
	$namaPetugas = $arrPetugasInfo[1];
	$namaKecamatan = $_SESSION['desaXweb']['NM_KECAMATAN'];
	$namaKelurahan = $_SESSION['desaXweb']['NM_KELURAHAN'];
	$thnsppt = $appxinfo['_tahun_pajak_'];
	try {
		$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
		$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		/* counting data */
		$stmt_cnt = $dbcon->prepare("SELECT COUNT(*) as foundrows FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND PETUGASIDX = :idxpetugas");
		$stmt_cnt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
		$stmt_cnt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
		$stmt_cnt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
		$stmt_cnt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
		$stmt_cnt->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
		$stmt_cnt->bindValue(":idxpetugas", $petugasidx, PDO::PARAM_INT);
		if ($stmt_cnt->execute()) {
			while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
				$rowsfound = $rowset_cnt['foundrows'];
			}
			if (intval($rowsfound)>0) {
				/* SUM total */
				$stmt_sum_total = $dbcon->prepare("SELECT SUM(PBB_YG_HARUS_DIBAYAR_SPPT) as totaltargetpetugas FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND PETUGASIDX = :idxpetugas");
				$stmt_sum_total->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
				$stmt_sum_total->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
				$stmt_sum_total->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
				$stmt_sum_total->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
				$stmt_sum_total->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
				$stmt_sum_total->bindValue(":idxpetugas", $petugasidx, PDO::PARAM_INT);
				$stmt_sum_total->execute();
				while($rowset_sum_total = $stmt_sum_total->fetch(PDO::FETCH_ASSOC)){
					$sum_total_desa = $rowset_sum_total['totaltargetpetugas'];
				}
				/* SUM assigned */
				$stmt_sum_assigned = $dbcon->prepare("SELECT SUM(JML_SPPT_YG_DIBAYAR) as totalassigneddibayar FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND PETUGASIDX = :idxpetugas");
				$stmt_sum_assigned->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
				$stmt_sum_assigned->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
				$stmt_sum_assigned->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
				$stmt_sum_assigned->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
				$stmt_sum_assigned->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
				$stmt_sum_assigned->bindValue(":idxpetugas", $petugasidx, PDO::PARAM_INT);
				$stmt_sum_assigned->execute();
				while($rowset_sum_assigned = $stmt_sum_assigned->fetch(PDO::FETCH_ASSOC)){
					$sum_assigned_desa = $rowset_sum_assigned['totalassigneddibayar'];
				}
				/* select all assigned */
				$stmt = $dbcon->prepare("SELECT * FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND PETUGASIDX = :idxpetugas");
				$stmt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
				$stmt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
				$stmt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
				$stmt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
				$stmt->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
				$stmt->bindValue(":idxpetugas", $petugasidx, PDO::PARAM_INT);
				if ($stmt->execute()) {
					$rowcount = 9;
					$rownum = 1;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('B1', 'REKAPITULASI PBB-P2 PER PETUGAS PBB-P2')
						->setCellValue('B2', 'SEMUA DATA')
						->setCellValue('B3', 'KECAMATAN : '.$namaKelurahan.'')
						->setCellValue('B4', 'DESA : '.$namaKecamatan.'')
						->setCellValue('B5', 'TAHUN PAJAK : '.$thnsppt.'')
						->setCellValue('B6', 'PETUGAS PUNGUT : '.$namaPetugas.'')
			
						->setCellValue('A8', 'No.')
						->setCellValue('B8', 'NOP')
						->setCellValue('C8', 'NAMA WP')
						->setCellValue('D8', 'ALAMAT WP')
						->setCellValue('E8', 'LUAS BUMI')
						->setCellValue('F8', 'LUAS BANGUNAN')
						->setCellValue('G8', 'JUMLAH KETETAPAN');
					while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
						/* data looping - lihat row counter nya.. */
						$objPHPExcel->setActiveSheetIndex(0)
									->setCellValue('A'.$rowcount.'', ''.$rownum.'')
									->setCellValue('B'.$rowcount.'', ''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'.'.$rowset['KD_JNS_OP'].'')
									->setCellValue('C'.$rowcount.'', ''.$rowset['NM_WP_SPPT'].'')
									->setCellValue('D'.$rowcount.'', ''.$rowset['JLN_WP_SPPT'].' '.$rowset['BLOK_KAV_NO_WP_SPPT'].' RT '.$rowset['RT_WP_SPPT'].'/RW '.$rowset['RW_WP_SPPT'].' '.$rowset['KELURAHAN_WP_SPPT'].'')
									->setCellValue('E'.$rowcount.'', ''.number_format($rowset['LUAS_BUMI_SPPT'],0,'','').'')
									->setCellValue('F'.$rowcount.'', ''.number_format($rowset['LUAS_BNG_SPPT'],0,'','').'')
									->setCellValue('G'.$rowcount.'', ''.number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],0,'','').'');
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
					/* total ketetapan */
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('G'.$rowcount.'', ''.number_format($sum_total_desa,0,'','').'');
					/* formatting */
					$objPHPExcel->getActiveSheet()
								->getStyle('G'.$rowcount.'')->getNumberFormat()
								->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				} else {
					$objPHPExcel->setActiveSheetIndex(0)
											->setCellValue('A1', 'Error: No data found.');
				}
			} else {
				$objPHPExcel->setActiveSheetIndex(0)
										->setCellValue('A1', 'Error: No data found.');
			}
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
$objPHPExcel->getActiveSheet()->setTitle('DHKP Desa');
$objPHPExcel->setActiveSheetIndex(0);
/* Redirect output ke web browser (Excel2007) */
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="REKAPITULASI_PER_PETUGAS_'.trim($namaPetugas).'_SEMUA_DATA_'.Date('dmY_His').'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>