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
							 ->setTitle("DAFTAR TAGIHAN")
							 ->setSubject("PAJAK BUMI DAN BANGUNAN PERDESAAN DAN PERKOTAAN ( PBB-P2 )")
							 ->setDescription("Tagihan PBB-P2 Desa")
							 ->setKeywords("BPPKAD,DHKP,Desa")
							 ->setCategory("Microsoft Office 2007 Excel Document");
/* set advanced value binder */
PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );

if (isset($_SESSION['kecamatanXweb'])) {

	$kode_propinsi = $_SESSION['kecamatanXweb']['KD_PROPINSI'];
	$kode_dati2 = $_SESSION['kecamatanXweb']['KD_DATI2'];
	$kode_kecamatan = $_SESSION['kecamatanXweb']['KD_KECAMATAN'];
	$kode_kelurahan = $_GET['iddesa'];
	$namaKecamatan = $_SESSION['kecamatanXweb']['NM_KECAMATAN'];
	$namaKelurahan = $_GET['nmdesa'];
	$thnsppt = $_SESSION['tahunPAJAK'];
	$tglterbitdefault = strval("".$_SESSION['tahunPAJAK']."-01-02");
	try {
		$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
		$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt_create_dhkp_temporary_sppt_data = $dbcon->prepare("CREATE TEMPORARY TABLE dhkp_temporary_sppt_data(KD_PROPINSI char(2) NOT NULL,KD_DATI2 char(2) NOT NULL,KD_KECAMATAN char(3) NOT NULL,KD_KELURAHAN char(3) NOT NULL,KD_BLOK char(3) NOT NULL,NO_URUT char(4) NOT NULL,KD_JNS_OP char(1) NOT NULL,THN_PAJAK_SPPT char(4) NOT NULL,SIKLUS_SPPT int(10) NOT NULL DEFAULT '0',KD_KANWIL_BANK varchar(2) NOT NULL DEFAULT '',KD_KPPBB_BANK varchar(2) NOT NULL DEFAULT '',KD_BANK_TUNGGAL varchar(2) NOT NULL DEFAULT '',KD_BANK_PERSEPSI varchar(2) NOT NULL DEFAULT '',KD_TP varchar(2) NOT NULL DEFAULT '',NM_WP_SPPT varchar(255) NOT NULL DEFAULT '',JLN_WP_SPPT varchar(255) NOT NULL DEFAULT '',BLOK_KAV_NO_WP_SPPT varchar(64) NOT NULL DEFAULT '',RW_WP_SPPT varchar(3) NOT NULL DEFAULT '',RT_WP_SPPT varchar(3) NOT NULL DEFAULT '',KELURAHAN_WP_SPPT varchar(64) NOT NULL DEFAULT '',KOTA_WP_SPPT varchar(64) NOT NULL DEFAULT '',KD_POS_WP_SPPT varchar(5) NOT NULL DEFAULT '',NPWP_SPPT varchar(16) NOT NULL DEFAULT '',NO_PERSIL_SPPT varchar(32) NOT NULL DEFAULT '',KD_KLS_TANAH varchar(8) NOT NULL DEFAULT '',THN_AWAL_KLS_TANAH varchar(4) NOT NULL DEFAULT '',KD_KLS_BNG varchar(8) NOT NULL DEFAULT '',THN_AWAL_KLS_BNG varchar(4) NOT NULL DEFAULT '',TGL_JATUH_TEMPO_SPPT date NOT NULL DEFAULT '1900-01-01',LUAS_BUMI_SPPT decimal(10,2) NOT NULL DEFAULT '0.00',LUAS_BNG_SPPT decimal(10,2) NOT NULL DEFAULT '0.00',NJOP_BUMI_SPPT bigint(20) NOT NULL DEFAULT '0',NJOP_BNG_SPPT bigint(20) NOT NULL DEFAULT '0',NJOP_SPPT bigint(20) NOT NULL DEFAULT '0',NJOPTKP_SPPT bigint(20) NOT NULL DEFAULT '0',NJKP_SPPT bigint(20) NOT NULL DEFAULT '0',PBB_TERHUTANG_SPPT bigint(20) NOT NULL DEFAULT '0',FAKTOR_PENGURANG_SPPT int(10) NOT NULL DEFAULT '0',PBB_YG_HARUS_DIBAYAR_SPPT bigint(20) NOT NULL DEFAULT '0',STATUS_PEMBAYARAN_SPPT varchar(4) NOT NULL DEFAULT '',STATUS_TAGIHAN_SPPT varchar(4) NOT NULL DEFAULT '',STATUS_CETAK_SPPT varchar(4) NOT NULL DEFAULT '',TGL_TERBIT_SPPT date NOT NULL DEFAULT '1900-01-01',TGL_CETAK_SPPT date NOT NULL DEFAULT '1900-01-01',NIP_PENCETAK_SPPT varchar(16) NOT NULL DEFAULT '',PEMBAYARAN_SPPT_KE int(10) NOT NULL DEFAULT '0',DENDA_SPPT decimal(10,2) NOT NULL DEFAULT '0.00',JML_SPPT_YG_DIBAYAR bigint(20) NOT NULL DEFAULT '0',TGL_PEMBAYARAN_SPPT date NOT NULL DEFAULT '1900-01-01',TGL_REKAM_BYR_SPPT date NOT NULL DEFAULT '1900-01-01',NIP_REKAM_BYR_SPPT varchar(16) NOT NULL DEFAULT '',PETUGASIDX int(10) NOT NULL DEFAULT '0',NAMAPETUGAS varchar(64) NOT NULL DEFAULT '',STATUS int(10) NOT NULL DEFAULT '0',PRIMARY KEY (KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,TGL_TERBIT_SPPT),INDEX KD_PROPINSI (KD_PROPINSI),INDEX KD_DATI2 (KD_DATI2),INDEX KD_KECAMATAN (KD_KECAMATAN),INDEX KD_KELURAHAN (KD_KELURAHAN),INDEX KD_BLOK (KD_BLOK),INDEX NO_URUT (NO_URUT),INDEX KD_JNS_OP (KD_JNS_OP),INDEX THN_PAJAK_SPPT (THN_PAJAK_SPPT),INDEX KELURAHAN_WP_SPPT (KELURAHAN_WP_SPPT),INDEX TGL_TERBIT_SPPT (TGL_TERBIT_SPPT),INDEX TGL_PEMBAYARAN_SPPT (TGL_PEMBAYARAN_SPPT),INDEX PETUGASIDX (PETUGASIDX)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
		$stmt_create_dhkp_temporary_sppt_data->execute();
		$stmt_create_dhkp_temporary_neu_sppt_data = $dbcon->prepare("CREATE TEMPORARY TABLE dhkp_temporary_neu_sppt_data(KD_PROPINSI char(2) NOT NULL,KD_DATI2 char(2) NOT NULL,KD_KECAMATAN char(3) NOT NULL,KD_KELURAHAN char(3) NOT NULL,KD_BLOK char(3) NOT NULL,NO_URUT char(4) NOT NULL,KD_JNS_OP char(1) NOT NULL,THN_PAJAK_SPPT char(4) NOT NULL,SIKLUS_SPPT int(10) NOT NULL DEFAULT '0',KD_KANWIL_BANK varchar(2) NOT NULL DEFAULT '',KD_KPPBB_BANK varchar(2) NOT NULL DEFAULT '',KD_BANK_TUNGGAL varchar(2) NOT NULL DEFAULT '',KD_BANK_PERSEPSI varchar(2) NOT NULL DEFAULT '',KD_TP varchar(2) NOT NULL DEFAULT '',NM_WP_SPPT varchar(255) NOT NULL DEFAULT '',JLN_WP_SPPT varchar(255) NOT NULL DEFAULT '',BLOK_KAV_NO_WP_SPPT varchar(64) NOT NULL DEFAULT '',RW_WP_SPPT varchar(3) NOT NULL DEFAULT '',RT_WP_SPPT varchar(3) NOT NULL DEFAULT '',KELURAHAN_WP_SPPT varchar(64) NOT NULL DEFAULT '',KOTA_WP_SPPT varchar(64) NOT NULL DEFAULT '',KD_POS_WP_SPPT varchar(5) NOT NULL DEFAULT '',NPWP_SPPT varchar(16) NOT NULL DEFAULT '',NO_PERSIL_SPPT varchar(32) NOT NULL DEFAULT '',KD_KLS_TANAH varchar(8) NOT NULL DEFAULT '',THN_AWAL_KLS_TANAH varchar(4) NOT NULL DEFAULT '',KD_KLS_BNG varchar(8) NOT NULL DEFAULT '',THN_AWAL_KLS_BNG varchar(4) NOT NULL DEFAULT '',TGL_JATUH_TEMPO_SPPT date NOT NULL DEFAULT '1900-01-01',LUAS_BUMI_SPPT decimal(10,2) NOT NULL DEFAULT '0.00',LUAS_BNG_SPPT decimal(10,2) NOT NULL DEFAULT '0.00',NJOP_BUMI_SPPT bigint(20) NOT NULL DEFAULT '0',NJOP_BNG_SPPT bigint(20) NOT NULL DEFAULT '0',NJOP_SPPT bigint(20) NOT NULL DEFAULT '0',NJOPTKP_SPPT bigint(20) NOT NULL DEFAULT '0',NJKP_SPPT bigint(20) NOT NULL DEFAULT '0',PBB_TERHUTANG_SPPT bigint(20) NOT NULL DEFAULT '0',FAKTOR_PENGURANG_SPPT int(10) NOT NULL DEFAULT '0',PBB_YG_HARUS_DIBAYAR_SPPT bigint(20) NOT NULL DEFAULT '0',STATUS_PEMBAYARAN_SPPT varchar(4) NOT NULL DEFAULT '',STATUS_TAGIHAN_SPPT varchar(4) NOT NULL DEFAULT '',STATUS_CETAK_SPPT varchar(4) NOT NULL DEFAULT '',TGL_TERBIT_SPPT date NOT NULL DEFAULT '1900-01-01',TGL_CETAK_SPPT date NOT NULL DEFAULT '1900-01-01',NIP_PENCETAK_SPPT varchar(16) NOT NULL DEFAULT '',PEMBAYARAN_SPPT_KE int(10) NOT NULL DEFAULT '0',DENDA_SPPT decimal(10,2) NOT NULL DEFAULT '0.00',JML_SPPT_YG_DIBAYAR bigint(20) NOT NULL DEFAULT '0',TGL_PEMBAYARAN_SPPT date NOT NULL DEFAULT '1900-01-01',TGL_REKAM_BYR_SPPT date NOT NULL DEFAULT '1900-01-01',NIP_REKAM_BYR_SPPT varchar(16) NOT NULL DEFAULT '',PETUGASIDX int(10) NOT NULL DEFAULT '0',NAMAPETUGAS varchar(64) NOT NULL DEFAULT '',STATUS int(10) NOT NULL DEFAULT '0',PRIMARY KEY (KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,TGL_TERBIT_SPPT),INDEX KD_PROPINSI (KD_PROPINSI),INDEX KD_DATI2 (KD_DATI2),INDEX KD_KECAMATAN (KD_KECAMATAN),INDEX KD_KELURAHAN (KD_KELURAHAN),INDEX KD_BLOK (KD_BLOK),INDEX NO_URUT (NO_URUT),INDEX KD_JNS_OP (KD_JNS_OP),INDEX THN_PAJAK_SPPT (THN_PAJAK_SPPT),INDEX KELURAHAN_WP_SPPT (KELURAHAN_WP_SPPT),INDEX TGL_TERBIT_SPPT (TGL_TERBIT_SPPT),INDEX TGL_PEMBAYARAN_SPPT (TGL_PEMBAYARAN_SPPT),INDEX PETUGASIDX (PETUGASIDX)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
		$stmt_create_dhkp_temporary_neu_sppt_data->execute();
		/* Insert Data to temporary SPPT */
		$stmt_insert_sppt_data = $dbcon->prepare("INSERT INTO dhkp_temporary_sppt_data(KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,KD_KANWIL_BANK,KD_KPPBB_BANK,KD_BANK_TUNGGAL,KD_BANK_PERSEPSI,KD_TP,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,KELURAHAN_WP_SPPT,KOTA_WP_SPPT,KD_POS_WP_SPPT,NPWP_SPPT,NO_PERSIL_SPPT,KD_KLS_TANAH,THN_AWAL_KLS_TANAH,KD_KLS_BNG,THN_AWAL_KLS_BNG,TGL_JATUH_TEMPO_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,NJOP_BUMI_SPPT,NJOP_BNG_SPPT,NJOP_SPPT,NJOPTKP_SPPT,NJKP_SPPT,PBB_TERHUTANG_SPPT,FAKTOR_PENGURANG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,STATUS_TAGIHAN_SPPT,STATUS_CETAK_SPPT,TGL_TERBIT_SPPT,TGL_CETAK_SPPT,NIP_PENCETAK_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,TGL_REKAM_BYR_SPPT,NIP_REKAM_BYR_SPPT,PETUGASIDX,NAMAPETUGAS,STATUS) SELECT KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,KD_KANWIL_BANK,KD_KPPBB_BANK,KD_BANK_TUNGGAL,KD_BANK_PERSEPSI,KD_TP,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,KELURAHAN_WP_SPPT,KOTA_WP_SPPT,KD_POS_WP_SPPT,NPWP_SPPT,NO_PERSIL_SPPT,KD_KLS_TANAH,THN_AWAL_KLS_TANAH,KD_KLS_BNG,THN_AWAL_KLS_BNG,TGL_JATUH_TEMPO_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,NJOP_BUMI_SPPT,NJOP_BNG_SPPT,NJOP_SPPT,NJOPTKP_SPPT,NJKP_SPPT,PBB_TERHUTANG_SPPT,FAKTOR_PENGURANG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,STATUS_TAGIHAN_SPPT,STATUS_CETAK_SPPT,TGL_TERBIT_SPPT,TGL_CETAK_SPPT,NIP_PENCETAK_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,TGL_REKAM_BYR_SPPT,NIP_REKAM_BYR_SPPT,PETUGASIDX,NAMAPETUGAS,STATUS FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND TGL_TERBIT_SPPT = :defaulttglterbit ORDER BY NO_URUT, KD_JNS_OP");
		$stmt_insert_sppt_data->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
		$stmt_insert_sppt_data->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
		$stmt_insert_sppt_data->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
		$stmt_insert_sppt_data->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
		$stmt_insert_sppt_data->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
		$stmt_insert_sppt_data->bindValue(":defaulttglterbit", $tglterbitdefault, PDO::PARAM_STR);
		$stmt_insert_sppt_data->execute();
		/* Insert Data to temporary NEU SPPT */
		$stmt_insert_neu_sppt_data = $dbcon->prepare("INSERT INTO dhkp_temporary_neu_sppt_data(KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,KD_KANWIL_BANK,KD_KPPBB_BANK,KD_BANK_TUNGGAL,KD_BANK_PERSEPSI,KD_TP,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,KELURAHAN_WP_SPPT,KOTA_WP_SPPT,KD_POS_WP_SPPT,NPWP_SPPT,NO_PERSIL_SPPT,KD_KLS_TANAH,THN_AWAL_KLS_TANAH,KD_KLS_BNG,THN_AWAL_KLS_BNG,TGL_JATUH_TEMPO_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,NJOP_BUMI_SPPT,NJOP_BNG_SPPT,NJOP_SPPT,NJOPTKP_SPPT,NJKP_SPPT,PBB_TERHUTANG_SPPT,FAKTOR_PENGURANG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,STATUS_TAGIHAN_SPPT,STATUS_CETAK_SPPT,TGL_TERBIT_SPPT,TGL_CETAK_SPPT,NIP_PENCETAK_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,TGL_REKAM_BYR_SPPT,NIP_REKAM_BYR_SPPT,PETUGASIDX,NAMAPETUGAS,STATUS) SELECT KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,KD_KANWIL_BANK,KD_KPPBB_BANK,KD_BANK_TUNGGAL,KD_BANK_PERSEPSI,KD_TP,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,KELURAHAN_WP_SPPT,KOTA_WP_SPPT,KD_POS_WP_SPPT,NPWP_SPPT,NO_PERSIL_SPPT,KD_KLS_TANAH,THN_AWAL_KLS_TANAH,KD_KLS_BNG,THN_AWAL_KLS_BNG,TGL_JATUH_TEMPO_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,NJOP_BUMI_SPPT,NJOP_BNG_SPPT,NJOP_SPPT,NJOPTKP_SPPT,NJKP_SPPT,PBB_TERHUTANG_SPPT,FAKTOR_PENGURANG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,STATUS_TAGIHAN_SPPT,STATUS_CETAK_SPPT,TGL_TERBIT_SPPT,TGL_CETAK_SPPT,NIP_PENCETAK_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,TGL_REKAM_BYR_SPPT,NIP_REKAM_BYR_SPPT,PETUGASIDX,NAMAPETUGAS,STATUS FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND TGL_TERBIT_SPPT > :defaulttglterbit ORDER BY NO_URUT, KD_JNS_OP");
		$stmt_insert_neu_sppt_data->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
		$stmt_insert_neu_sppt_data->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
		$stmt_insert_neu_sppt_data->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
		$stmt_insert_neu_sppt_data->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
		$stmt_insert_neu_sppt_data->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
		$stmt_insert_neu_sppt_data->bindValue(":defaulttglterbit", $tglterbitdefault, PDO::PARAM_STR);
		$stmt_insert_neu_sppt_data->execute();
		/* remove duplicates */
		$stmt_remove_duplicates = $dbcon->prepare("DELETE default_sppt FROM dhkp_temporary_sppt_data default_sppt JOIN dhkp_temporary_neu_sppt_data neu_sppt ON default_sppt.KD_BLOK = neu_sppt.KD_BLOK AND default_sppt.NO_URUT = neu_sppt.NO_URUT AND default_sppt.KD_JNS_OP = neu_sppt.KD_JNS_OP");
		$stmt_remove_duplicates->execute();
		/* insert-combine neu SPPT to default SPPT */
		$stmt_insert_combine_default_neu = $dbcon->prepare("INSERT INTO dhkp_temporary_sppt_data(KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,KD_KANWIL_BANK,KD_KPPBB_BANK,KD_BANK_TUNGGAL,KD_BANK_PERSEPSI,KD_TP,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,KELURAHAN_WP_SPPT,KOTA_WP_SPPT,KD_POS_WP_SPPT,NPWP_SPPT,NO_PERSIL_SPPT,KD_KLS_TANAH,THN_AWAL_KLS_TANAH,KD_KLS_BNG,THN_AWAL_KLS_BNG,TGL_JATUH_TEMPO_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,NJOP_BUMI_SPPT,NJOP_BNG_SPPT,NJOP_SPPT,NJOPTKP_SPPT,NJKP_SPPT,PBB_TERHUTANG_SPPT,FAKTOR_PENGURANG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,STATUS_TAGIHAN_SPPT,STATUS_CETAK_SPPT,TGL_TERBIT_SPPT,TGL_CETAK_SPPT,NIP_PENCETAK_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,TGL_REKAM_BYR_SPPT,NIP_REKAM_BYR_SPPT,PETUGASIDX,NAMAPETUGAS,STATUS) SELECT KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,KD_KANWIL_BANK,KD_KPPBB_BANK,KD_BANK_TUNGGAL,KD_BANK_PERSEPSI,KD_TP,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,KELURAHAN_WP_SPPT,KOTA_WP_SPPT,KD_POS_WP_SPPT,NPWP_SPPT,NO_PERSIL_SPPT,KD_KLS_TANAH,THN_AWAL_KLS_TANAH,KD_KLS_BNG,THN_AWAL_KLS_BNG,TGL_JATUH_TEMPO_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,NJOP_BUMI_SPPT,NJOP_BNG_SPPT,NJOP_SPPT,NJOPTKP_SPPT,NJKP_SPPT,PBB_TERHUTANG_SPPT,FAKTOR_PENGURANG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,STATUS_TAGIHAN_SPPT,STATUS_CETAK_SPPT,TGL_TERBIT_SPPT,TGL_CETAK_SPPT,NIP_PENCETAK_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,TGL_REKAM_BYR_SPPT,NIP_REKAM_BYR_SPPT,PETUGASIDX,NAMAPETUGAS,STATUS FROM dhkp_temporary_neu_sppt_data ORDER BY NO_URUT, KD_JNS_OP");
		$stmt_insert_combine_default_neu->execute();
		/* counting lembar sppt - desa */
		$stmt_cnt = $dbcon->prepare("SELECT COUNT(*) as foundrows FROM dhkp_temporary_sppt_data WHERE STATUS_PEMBAYARAN_SPPT = 0 AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01'");
		/* $stmt_cnt->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
		$stmt_cnt->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
		$stmt_cnt->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
		$stmt_cnt->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
		$stmt_cnt->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR); */
		if ($stmt_cnt->execute()) {
			while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
				$rows = $rowset_cnt['foundrows'];
			}
			/* summing total ketetapan desa/kelurahan */
			$stmt_sum_lembar_sppt = $dbcon->prepare("SELECT SUM(PBB_YG_HARUS_DIBAYAR_SPPT) AS totalsumunpaidspptdesa FROM dhkp_temporary_sppt_data WHERE STATUS_PEMBAYARAN_SPPT = 0 AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01'");
			/* $stmt_sum_lembar_sppt->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
			$stmt_sum_lembar_sppt->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
			$stmt_sum_lembar_sppt->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
			$stmt_sum_lembar_sppt->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
			$stmt_sum_lembar_sppt->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR); */
			if ($stmt_sum_lembar_sppt->execute()) {
				while($rowset_sum_lembar_sppt = $stmt_sum_lembar_sppt->fetch(PDO::FETCH_ASSOC)){
					$sumresult = $rowset_sum_lembar_sppt['totalsumunpaidspptdesa'];
				}
				/* select all sppt */
				$stmt_select_data = $dbcon->prepare("SELECT KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,STATUS_TAGIHAN_SPPT,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT FROM dhkp_temporary_sppt_data WHERE STATUS_PEMBAYARAN_SPPT = 0 AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' ORDER BY KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP");
				/* $stmt_select_data->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
				$stmt_select_data->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
				$stmt_select_data->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
				$stmt_select_data->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
				$stmt_select_data->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR); */
				if ($stmt_select_data->execute()) {
					$rowcount = 9;
					$rownum = 1;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('B1', 'DAFTAR TAGIHAN')
						->setCellValue('B2', 'PAJAK BUMI DAN BANGUNAN PERDESAAN DAN PERKOTAAN (PBB-P2)')
						->setCellValue('B4', 'DESA : '.$namaKelurahan.'')
						->setCellValue('B5', 'KECAMATAN : '.$namaKecamatan.'')
						->setCellValue('B6', 'TAHUN PAJAK: '.$thnsppt.'')
						->setCellValue('A8', 'No.')
						->setCellValue('B8', 'NOP')
						->setCellValue('C8', 'NAMA WP')
						->setCellValue('D8', 'ALAMAT WP')
						->setCellValue('E8', 'L.Bumi')
						->setCellValue('F8', 'L.Bng')
						->setCellValue('G8', 'KETETAPAN');
					while($rowset = $stmt_select_data->fetch(PDO::FETCH_ASSOC)){
						/* data looping - lihat row counter nya.. */
						$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A'.$rowcount.'', ''.$rownum.'')
								->setCellValueExplicit('B'.$rowcount.'', strval(''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].''), PHPExcel_Cell_DataType::TYPE_STRING)
								->setCellValue('C'.$rowcount.'', ''.$rowset['NM_WP_SPPT'].'')
								->setCellValue('D'.$rowcount.'', strval($rowset['JLN_WP_SPPT'].', '.$rowset['BLOK_KAV_NO_WP_SPPT'].', RT '.$rowset['RT_WP_SPPT'].'/ RW '.$rowset['RW_WP_SPPT'].''))
								->setCellValue('E'.$rowcount.'', ''.$rowset['LUAS_BUMI_SPPT'].'')
								->setCellValue('F'.$rowcount.'', ''.$rowset['LUAS_BNG_SPPT'].'')
								->setCellValue('G'.$rowcount.'', ''.$rowset['PBB_YG_HARUS_DIBAYAR_SPPT'].'');
						/* formatting */
						$objPHPExcel->getActiveSheet()
									->getStyle('E'.$rowcount.'')->getNumberFormat()
									->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
						$objPHPExcel->getActiveSheet()
									->getStyle('F'.$rowcount.'')->getNumberFormat()
									->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
						$objPHPExcel->getActiveSheet()
									->getStyle('G'.$rowcount.'')->getNumberFormat()
									->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
						$rowcount++;
						$rownum++;
					}
					$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('F'.$rowcount.'', 'Total')
							->setCellValue('G'.$rowcount.'', ''.$sumresult.'');
					/* formatting */
					$objPHPExcel->getActiveSheet()
							->getStyle('G'.$rowcount.'')->getNumberFormat()
							->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
				} else {
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A1', 'Error: No row(s) found.');
				}
			} else {
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A1', 'Error: No row(s) found.');
			}
		} else {
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A1', 'Error: No row(s) found.');
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
header('Content-Disposition: attachment;filename="TAGIHAN_PBB_'.$kode_propinsi.''.$kode_dati2.''.$kode_kecamatan.''.$kode_kelurahan.'_'.$namaKelurahan.'_'.Date('dmY_His').'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>