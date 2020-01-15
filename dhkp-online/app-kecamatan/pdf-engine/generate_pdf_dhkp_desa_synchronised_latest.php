<?php
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
if (isset($_SESSION['kecamatanXweb'])) {
	$maxonepagerows = 32;
	$maxfirstpagerows = 37;
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
		/* $stmt_cnt = $dbcon->prepare("SELECT COUNT(*) as foundrows FROM dhkp_temporary_sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak"); */
		$stmt_cnt = $dbcon->prepare("SELECT COUNT(*) as foundrows FROM dhkp_temporary_sppt_data");
		/* $stmt_cnt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
		$stmt_cnt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
		$stmt_cnt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
		$stmt_cnt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
		$stmt_cnt->bindValue(":thnpajak", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); */
		if ($stmt_cnt->execute()) {
			while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
				$rows = $rowset_cnt['foundrows'];
			}
			/* summing total ketetapan desa/kelurahan */
			/* $stmt_sum_lembar_sppt = $dbcon->prepare("SELECT SUM(PBB_YG_HARUS_DIBAYAR_SPPT) as totalsumspptdesa FROM dhkp_temporary_sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak"); */
			$stmt_sum_lembar_sppt = $dbcon->prepare("SELECT SUM(PBB_YG_HARUS_DIBAYAR_SPPT) as totalsumspptdesa FROM dhkp_temporary_sppt_data");
			/* $stmt_sum_lembar_sppt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt_sum_lembar_sppt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt_sum_lembar_sppt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt_sum_lembar_sppt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			$stmt_sum_lembar_sppt->bindValue(":thnpajak", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); */
			if ($stmt_sum_lembar_sppt->execute()) {
				while($rowset_sum_lembar_sppt = $stmt_sum_lembar_sppt->fetch(PDO::FETCH_ASSOC)){
					$sumresult = $rowset_sum_lembar_sppt['totalsumspptdesa'];
				}
				/* select all sppt */
				/* $stmt_select_data = $dbcon->prepare("SELECT KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT FROM dhkp_temporary_sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak ORDER BY KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP"); */
				$stmt_select_data = $dbcon->prepare("SELECT KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT FROM dhkp_temporary_sppt_data ORDER BY KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP");
				/* $stmt_select_data->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
				$stmt_select_data->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
				$stmt_select_data->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
				$stmt_select_data->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
				$stmt_select_data->bindValue(":thnpajak", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); */
				if ($stmt_select_data->execute()) {
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
					$pdf->Cell(19.5,1,'TAHUN '.$thnsppt.'','',1,'C','');
					/* /cover */
					$pdf->AddPage();
					$pagenum = 1;
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(19.5,0.5,'DAFTAR HIMPUNAN KETETAPAN PAJAK (DHKP)','',1,'C','');
					$pdf->Cell(19.5,0.5,'PAJAK BUMI DAN BANGUNAN PERDESAAN DAN PERKOTAAN (PBB-P2)','',1,'C','');
					$pdf->Cell(19.5,0.5,'DESA : '.$namaKelurahan.'','',1,'C','');
					$pdf->Cell(19.5,0.5,'KECAMATAN : '.$namaKecamatan.'','',1,'C','');
					$pdf->Cell(19.5,0.5,'TAHUN PAJAK: '.$thnsppt.'','',1,'C','');
					$ofxpages = 1;
					$rownum = 1;
					while($rowset = $stmt_select_data->fetch(PDO::FETCH_ASSOC)){
/* ##################################################################################### */
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
								$pdf->Cell(1.25,0.75,'L.Bumi','TRB',0,'C','');
								$pdf->Cell(1.25,0.75,'L.Bng','TRB',0,'C','');
								$pdf->Cell(3,0.75,'KETETAPAN','TRB',1,'C','');
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
								
								} elseif (intval($rownum) == intval($rows)) {
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
								/* total sum row - last row */
								$pdf->Cell(16.5,0.75,'Total Penetapan:','LRBT',0,'R','');
								$pdf->Cell(3,0.75,number_format($sumresult,2,',','.'),'RBT',1,'R','');
								/* end document - tanda tangan */
								$pdf->Cell(19.5,1.5,'','',1,'C','');
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_NM_DATI2_'].' , _______________ '.Date('Y').'','',1,'C','');
								
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
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
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
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
								
								} elseif (intval($rownum) <= $maxonepagerows) { /* 1 - 32 : intval($rownum) <= $maxonepagerows */
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
								
								} elseif (intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) < intval($rows)) { /* 33 - 39 : intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) < intval($rows) */
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
								
								} elseif (intval($rownum) > $maxonepagerows && intval($rownum) == $maxfirstpagerows && intval($rownum) == intval($rows)) { /* 33 - 39 : intval($rownum) > $maxonepagerows && intval($rownum) < $maxfirstpagerows && intval($rownum) == intval($rows) */
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
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
								$pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN PERSETUJUAN','',1,'R','');
								
								$pagenum++;
								$pdf->AddPage();
								
								$pdf->Cell(19.5,1,'HALAMAN: '.$pagenum.'/'.$ofxpages.'','',1,'R','');
								/* end document - tanda tangan */
								$pdf->Cell(19.5,1.5,'','',1,'C','');
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_NM_DATI2_'].' , _______________ '.Date('Y').'','',1,'C','');
								
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
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
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
								$pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN PERSETUJUAN','',1,'R','');
								
								$pagenum++;
								$pdf->AddPage();
								
								$pdf->Cell(19.5,1,'HALAMAN: '.$pagenum.'/'.$ofxpages.'','',1,'R','');
								/* end document - tanda tangan */
								$pdf->Cell(19.5,1.5,'','',1,'C','');
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_NM_DATI2_'].' , _______________ '.Date('Y').'','',1,'C','');
								
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
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
								$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
								$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
								$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
								$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
								
								$subtotal += intval($rowset['PBB_YG_HARUS_DIBAYAR_SPPT']);
							} elseif (intval($rownum)%37==0) {
								if (intval($rownum) == intval($rows)) {
								
								$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
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
								$pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN PERSETUJUAN','',1,'R','');
									
								$pdf->AddPage();
									
								$pdf->Cell(19.5,1,'HALAMAN: '.$pagenum.'/'.$ofxpages.'','',1,'R','');
								/* end document - tanda tangan */
								$pdf->Cell(19.5,1.5,'','',1,'C','');
								$pdf->Cell(9.75,0.5,'','',0,'C','');
								$pdf->Cell(9.75,0.5,''.$appxinfo['_NM_DATI2_'].' , _______________ '.Date('Y').'','',1,'C','');
								
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
								$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
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
										$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
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
										/* end document - tanda tangan */
										$pdf->Cell(19.5,1.5,'','',1,'C','');
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,''.$appxinfo['_NM_DATI2_'].' , _______________ '.Date('Y').'','',1,'C','');
										
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
										$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
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
										$pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN PERSETUJUAN','',1,'R','');
										
										$pdf->AddPage();
										$pdf->Cell(19.5,1,'HALAMAN: '.$pagenum.'/'.$totalpages.'','',1,'R','');
										/* end document - tanda tangan */
										$pdf->Cell(19.5,1.5,'','',1,'C','');
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,''.$appxinfo['_NM_DATI2_'].' , _______________ '.Date('Y').'','',1,'C','');
										
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
									$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
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
									$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
									$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
									$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
									$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
									$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
									$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
								
									$subtotal += intval($rowset['PBB_YG_HARUS_DIBAYAR_SPPT']);
									} elseif (intval($baselastpagerows) > 0 && intval($baselastpagerows) <= $lastpageminimumrows) { /* subtotal + total + ttd */
										if (intval($rownum) == intval($rows)) {	
											
										$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
										$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
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
										/* end document - tanda tangan */
										$pdf->Cell(19.5,1.5,'','',1,'C','');
										$pdf->Cell(9.75,0.5,'','',0,'C','');
										$pdf->Cell(9.75,0.5,''.$appxinfo['_NM_DATI2_'].' , _______________ '.Date('Y').'','',1,'C','');
										
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
										$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
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
											$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
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
											/* end document - tanda tangan */
											$pdf->Cell(19.5,1.5,'','',1,'C','');
											$pdf->Cell(9.75,0.5,'','',0,'C','');
											$pdf->Cell(9.75,0.5,''.$appxinfo['_NM_DATI2_'].' , _______________ '.Date('Y').'','',1,'C','');
											
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
											$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
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
											$pdf->Cell(19.5,0.75,'BERSAMBUNG KE HALAMAN PERSETUJUAN','',1,'R','');
												
											$pdf->AddPage();
											$pdf->Cell(19.5,1,'HALAMAN: '.$pagenum.'/'.$totalpages.'','',1,'R','');
											/* end document - tanda tangan */
											$pdf->Cell(19.5,1.5,'','',1,'C','');
											$pdf->Cell(9.75,0.5,'','',0,'C','');
											$pdf->Cell(9.75,0.5,''.$appxinfo['_NM_DATI2_'].' , _______________ '.Date('Y').'','',1,'C','');
											
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
											$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
											$pdf->Cell(4,0.5,$str_namawp,'R',0,'L','');
											$pdf->Cell(5,0.5,$str_alamatwp,'R',0,'L','');
											$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BUMI_SPPT'],0,',','.'),'R',0,'R','');
											$pdf->Cell(1.25,0.5,number_format($rowset['LUAS_BNG_SPPT'],0,',','.'),'R',0,'R','');
											$pdf->Cell(3,0.5,number_format($rowset['PBB_YG_HARUS_DIBAYAR_SPPT'],2,',','.'),'R',1,'R','');
										
											$subtotal += intval($rowset['PBB_YG_HARUS_DIBAYAR_SPPT']);
											}
									} else {
										$pdf->Cell(1,0.5,$rownum.'.','LR',0,'R','');
										$pdf->Cell(4,0.5,''.$rowset['KD_PROPINSI'].'.'.$rowset['KD_DATI2'].'.'.$rowset['KD_KECAMATAN'].'.'.$rowset['KD_KELURAHAN'].'.'.$rowset['KD_BLOK'].'-'.$rowset['NO_URUT'].'-'.$rowset['KD_JNS_OP'].'','R',0,'C','');
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