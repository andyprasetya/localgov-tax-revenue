<?php
class dasSpezifisch {
	/* vars declarations */
	public $dummy_string;
	public $stringToProcess;
	public $emptyArray;

	public $dev_db_host;
	public $dev_db_port;
	public $dev_db_user;
	public $dev_db_pass;
	public $dev_db_name;
	public $db_host;
	public $db_port;
	public $db_user;
	public $db_pass;
	public $db_name;
	public $desa_db_host;
	public $desa_db_port;
	public $desa_db_user;
	public $desa_db_pass;
	public $desa_db_name;
	public $sig_db_host;
	public $sig_db_port;
	public $sig_db_user;
	public $sig_db_pass;
	public $sig_db_name;
	public $oracle_access;
	public $oracle_host;
	public $oracle_port;
	public $oracle_user;
	public $oracle_pass;
	public $oracle_service;
	public $tahun_pajak;
	
	public $kode_propinsi;
	public $kode_dati2;
	public $kode_kecamatan;
	public $kode_kelurahan;
	public $namaKecamatan;
	public $namaKelurahan;
	public $password;
	public $status;
	public $thnsppt;
	public $tglterbitdefault;
	public $tgltutuptahundefault;
	/* class constructors */
	public function __construct(){
		$numArgs = func_num_args();
		if ($numArgs == 0 || $numArgs == null) {
			/* do nothing */
		} else {
			/* do nothing */
		}
		if (isset($_SESSION['desaXweb']) && isset($_SESSION['appxINFO'])) {
			/* ------------------------------------------ */
			$this->dev_db_host = $_SESSION['appxINFO']['_dev_db_host_'];
			$this->dev_db_port = $_SESSION['appxINFO']['_dev_db_port_'];
			$this->dev_db_user = $_SESSION['appxINFO']['_dev_db_user_'];
			$this->dev_db_pass = $_SESSION['appxINFO']['_dev_db_pass_'];
			$this->dev_db_name = $_SESSION['appxINFO']['_dev_db_name_'];
			$this->db_host = $_SESSION['appxINFO']['_db_host_'];
			$this->db_port = $_SESSION['appxINFO']['_db_port_'];
			$this->db_user = $_SESSION['appxINFO']['_db_user_'];
			$this->db_pass = $_SESSION['appxINFO']['_db_pass_'];
			$this->db_name = $_SESSION['appxINFO']['_db_name_'];
			$this->desa_db_host = $_SESSION['appxINFO']['_desa_db_host_'];
			$this->desa_db_port = $_SESSION['appxINFO']['_desa_db_port_'];
			$this->desa_db_user = $_SESSION['appxINFO']['_desa_db_user_'];
			$this->desa_db_pass = $_SESSION['appxINFO']['_desa_db_pass_'];
			$this->desa_db_name = $_SESSION['appxINFO']['_desa_db_name_'];
			$this->sig_db_host = $_SESSION['appxINFO']['_sig_db_host_'];
			$this->sig_db_port = $_SESSION['appxINFO']['_sig_db_port_'];
			$this->sig_db_user = $_SESSION['appxINFO']['_sig_db_user_'];
			$this->sig_db_pass = $_SESSION['appxINFO']['_sig_db_pass_'];
			$this->sig_db_name = $_SESSION['appxINFO']['_sig_db_name_'];
			$this->oracle_access = $_SESSION['appxINFO']['_oracle_sismiop_access_'];
			$this->oracle_host = $_SESSION['appxINFO']['_oracle_sismiop_host_'];
			$this->oracle_port = $_SESSION['appxINFO']['_oracle_sismiop_port_'];
			$this->oracle_user = $_SESSION['appxINFO']['_oracle_sismiop_user_'];
			$this->oracle_pass = $_SESSION['appxINFO']['_oracle_sismiop_pass_'];
			$this->oracle_service = $_SESSION['appxINFO']['_oracle_sismiop_service_'];
			$this->thnsppt = $_SESSION['appxINFO']['_tahun_pajak_'];
			$this->tglterbitdefault = strval("".$_SESSION['appxINFO']['_tahun_pajak_']."-01-02");
			$this->tgltutuptahundefault = strval("".$_SESSION['appxINFO']['_tahun_pajak_']."-12-31");
			/* ------------------------------------------ */
			$this->kode_propinsi = $_SESSION['desaXweb']['KD_PROPINSI'];
			$this->kode_dati2 = $_SESSION['desaXweb']['KD_DATI2'];
			$this->kode_kecamatan = $_SESSION['desaXweb']['KD_KECAMATAN'];
			$this->kode_kelurahan = $_SESSION['desaXweb']['KD_KELURAHAN'];
			$this->namaKecamatan = $_SESSION['desaXweb']['NM_KECAMATAN'];
			$this->namaKelurahan = $_SESSION['desaXweb']['NM_KELURAHAN'];
			$this->password = $_SESSION['desaXweb']['WORDPASS'];
			$this->status = $_SESSION['desaXweb']['STATUS'];
			/* ------------------------------------------ */
		} else {
			/* do nothing */
		}
		$this->dummy_string = "Hello, this is a dummy string!";
		$this->stringToProcess = "";
		$this->emptyArray = null;
	}
	/* ====================================================== */
	public function getSummaryInfoAllSPPT () {
		$dbcon = $response = null;
		try {
			$dbcon = new PDO("mysql:host=".$this->desa_db_host.";dbname=".$this->desa_db_name."","".$this->desa_db_user."","".$this->desa_db_pass."");
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
			
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
			return $response;
		}
	}
	/* ====================================================== */
	public function getSummaryInfoSPPTDHKP () {
		return false;
	}
	/* ====================================================== */
	public function getSummaryInfoSPPTDHKPWithDataSPPT () {
		return false;
	}
	/* ====================================================== */
	/* ====================================================== */
	public function getSummaryInfoSPPTDHKPWithDataDHKP () {
		return false;
	}
}
?>