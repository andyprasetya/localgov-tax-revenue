-- MySQL dump 10.13  Distrib 8.0.16, for Linux (x86_64)
--
-- Host: localhost    Database: mapatda_bkad
-- ------------------------------------------------------
-- Server version	8.0.16

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8mb4 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `appx_backoffice_bphtb_core`
--

DROP TABLE IF EXISTS `appx_backoffice_bphtb_core`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_backoffice_bphtb_core` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID DATA',
  `bku_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID DATA - appx_buku_kas_umum',
  `no_bku` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NO. BKU',
  `no_pel` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NO. PELAYANAN',
  `no_kohir` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NO. KOHIR',
  `origin_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID DATA ORIGIN - appx_temporary_sspd_bphtb_backup.idx',
  `opidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'REF. OBJEK PAJAK ENTRY',
  `dt_entry` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT 'DATETIME INSERT DATA',
  `d_entry` date NOT NULL DEFAULT '1970-01-01' COMMENT 'DATE INSERT DATA',
  `context` varchar(64) NOT NULL DEFAULT '' COMMENT 'CONTEXT',
  `subcontext_a` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 1',
  `subcontext_b` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 2',
  `subcontext_c` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 3',
  `kode_rekening` varchar(64) NOT NULL DEFAULT '' COMMENT 'KODE REKENING - LEVEL 0',
  `subkoderekening_a` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 1',
  `subkoderekening_b` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 2',
  `subkoderekening_c` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 3',
  `dk` varchar(8) NOT NULL DEFAULT '' COMMENT 'DEBET/KREDIT',
  `debet` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'DEBET',
  `kredit` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'KREDIT',
  `cnc` varchar(8) NOT NULL DEFAULT '' COMMENT 'TUNAI/BANK',
  `cash` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'TUNAI',
  `bank` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'BANK',
  `nominal` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMINAL TRANSAKSI',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS DATA',
  `nik_wp` varchar(64) NOT NULL DEFAULT '' COMMENT 'NIK WAJIB PAJAK',
  `nama_wp` varchar(255) NOT NULL DEFAULT '' COMMENT 'DP - NAMA',
  `nama_notaris` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA NOTARIS',
  `jenis_trx` varchar(2) NOT NULL DEFAULT '' COMMENT 'JENIS PEROLEHAN / TRANSAKSI',
  `jenis_trx_string` varchar(255) NOT NULL DEFAULT '' COMMENT 'STRING JENIS PEROLEHAN / TRANSAKSI',
  `sppt_nop` varchar(32) NOT NULL DEFAULT '' COMMENT 'NOP PADA SPPT',
  `sppt_kecamatan_wp` varchar(64) NOT NULL DEFAULT '',
  `sppt_kelurahan_wp` varchar(64) NOT NULL DEFAULT '',
  `veri_lt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - LUAS TANAH-BUMI',
  `veri_lb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - LUAS BANGUNAN',
  `veri_njop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP TANAH-BUMI + BANGUNAN',
  `d_pengenaan` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TANGGAL DASAR PENGENAAN - TGL VERIFIKASI/TGL STP',
  `target` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD/SKPDKB',
  `selisih_l` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SELISIH LEBIH BAYAR',
  `selisih_k` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SELISIH KURANG BAYAR',
  `petugas` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID VERIFIKATOR',
  `nama_verifikator` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA VERIFIKATOR',
  `id_op` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID OPERATOR ENTRY DATA',
  `nama_operator` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA OPERATOR ENTRY DATA',
  `stat_dok` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS DOKUMEN',
  `notes` varchar(255) NOT NULL DEFAULT '' COMMENT 'KETERANGAN/URAIAN',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=155 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_base_realisasi`
--

DROP TABLE IF EXISTS `appx_base_realisasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_base_realisasi` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `trx_date` date NOT NULL,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ddla` tinyint(3) unsigned DEFAULT '0',
  `kode_a` varchar(32) NOT NULL,
  `kode_b` varchar(16) NOT NULL,
  `kode_c` varchar(16) NOT NULL,
  `jenis_penerimaan` varchar(128) NOT NULL,
  `rencana` bigint(20) unsigned NOT NULL,
  `sd_bulan_lalu` bigint(20) unsigned NOT NULL,
  `bulan_laporan` bigint(20) unsigned NOT NULL,
  `sd_bulan_laporan` bigint(20) unsigned NOT NULL,
  `persen` decimal(6,2) NOT NULL,
  `selisih` bigint(20) NOT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=164 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_bidang`
--

DROP TABLE IF EXISTS `appx_bidang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_bidang` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `bidang` varchar(64) DEFAULT NULL COMMENT 'NAMA BIDANG',
  `module` varchar(255) DEFAULT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_bphtb_history_verifikasi`
--

DROP TABLE IF EXISTS `appx_bphtb_history_verifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_bphtb_history_verifikasi` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PRIMARY KEY + ID',
  `origin_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ORIGIN ID REF appx_sspd_bphtb.idx',
  `codex` varchar(40) NOT NULL DEFAULT '' COMMENT 'CODEX',
  `opidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'OBJEK PAJAK ID REF appx_obyek_pajak.idx',
  `data_thn_pajak` varchar(4) NOT NULL DEFAULT '' COMMENT 'TAHUN PAJAK DATA',
  `dt_entry` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT 'DATETIME INSERT DATA',
  `d_entry` date NOT NULL DEFAULT '1970-01-01' COMMENT 'DATE INSERT DATA',
  `dt_verified` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT 'DATE TIME VERIFIKASI',
  `d_verified` date NOT NULL DEFAULT '1970-01-01' COMMENT 'DATE VERIFIKASI',
  `id_notaris` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOTARIS ID',
  `kode_notaris` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE NOTARIS',
  `nama_notaris` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA NOTARIS',
  `sppt_nop` varchar(32) NOT NULL DEFAULT '' COMMENT 'NOP PADA SPPT',
  `sppt_nama_wp` varchar(64) NOT NULL DEFAULT '',
  `sppt_alamat_wp` varchar(255) NOT NULL DEFAULT '',
  `sppt_kelurahan_wp` varchar(64) NOT NULL DEFAULT '',
  `sppt_kecamatan_wp` varchar(64) NOT NULL DEFAULT '',
  `sppt_kabupaten_wp` varchar(64) NOT NULL DEFAULT '',
  `sppt_thn_pajak` varchar(4) NOT NULL DEFAULT '' COMMENT 'SPPT - TAHUN PAJAK',
  `sppt_lt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - LUAS TANAH/BUMI',
  `sppt_ntm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP TANAH/BUMI M2',
  `sppt_nt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP TANAH/BUMI',
  `sppt_lb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - LUAS BANGUNAN',
  `sppt_nbm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP BANGUNAN M2',
  `sppt_nb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP BANGUNAN',
  `sppt_njop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP BUMI+BANGUNAN',
  `sspd_lt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - LUAS TANAH/BUMI',
  `sspd_ntm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NILAI TANAH/BUMI M2',
  `sspd_nt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NJOP TANAH/BUMI',
  `sspd_lb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - LUAS BANGUNAN',
  `sspd_nbm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NILAI BANGUNAN M2',
  `sspd_nb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NJOP BANGUNAN',
  `sspd_njop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NJOP TANAH/BUMI + BANGUNAN',
  `sspd_trx` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - TRANSAKSI',
  `sspd_npop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NPOP',
  `sspd_npoptkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NPOPTKP',
  `sspd_npopkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NPOPKP',
  `sspd_bphtb_ht` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - BPHTB TERHUTANG',
  `sspd_bphtb_hb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - BPHTB HARUS DIBAYAR',
  `veri_lt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - LUAS TANAH-BUMI',
  `veri_ntm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP TANAH-BUMI/M2',
  `veri_nt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP TANAH-BUMI',
  `veri_lb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - LUAS BANGUNAN',
  `veri_nbm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP BANGUNAN/M2',
  `veri_nb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP BANGUNAN',
  `veri_njop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP TANAH-BUMI + BANGUNAN',
  `veri_trx` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - HARGA TRANSAKSI',
  `veri_npop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NPOP',
  `veri_npoptkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NPOPTKP',
  `veri_npopkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NPOPKP',
  `veri_bphtb_ht` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - BPHTB TERHUTANG',
  `veri_bphtb_hb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - BPHTB YANG HARUS DIBAYAR',
  `veri_bphtb_kb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - BPHTB KURANG BAYAR',
  `petugas` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'PETUGAS VERIFIKASI ID',
  `id_op` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'OPERATOR PELAYANAN ID',
  `stat_dok` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS DOKUMEN',
  `stat_active` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS DATA AKTIF/NON-AKTIF',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=2473 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_bphtb_tariff`
--

DROP TABLE IF EXISTS `appx_bphtb_tariff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_bphtb_tariff` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `tariff` int(11) NOT NULL DEFAULT '0',
  `npoptkp_a` int(11) NOT NULL DEFAULT '0',
  `npoptkp_b` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_bppkad_users_maintenance`
--

DROP TABLE IF EXISTS `appx_bppkad_users_maintenance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_bppkad_users_maintenance` (
  `idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'USER ID',
  `opdcode` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'KODE OPD',
  `supervisor_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SUPERVISOR ID',
  `context` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'USER CONTEXT',
  `realname` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'REALNAME',
  `initial` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'INITIAL',
  `username` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'USERNAME',
  `wordpass` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'PASSWORD',
  `module` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'STANDARD MODULE',
  `add_module` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'MODULE TAMBAHAN',
  `verificator` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'REMARK BPHTB VERIFIKATOR',
  `collector` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'REMARK KOLEKTOR LAPANGAN',
  `status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS USER'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_bridge_pembayaran_sppt`
--

DROP TABLE IF EXISTS `appx_bridge_pembayaran_sppt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_bridge_pembayaran_sppt` (
  `KD_PROPINSI` char(2) NOT NULL,
  `KD_DATI2` char(2) NOT NULL,
  `KD_KECAMATAN` char(3) NOT NULL,
  `KD_KELURAHAN` char(3) NOT NULL,
  `KD_BLOK` char(3) NOT NULL,
  `NO_URUT` char(4) NOT NULL,
  `KD_JNS_OP` char(1) NOT NULL,
  `THN_PAJAK_SPPT_SPPT` char(4) NOT NULL,
  `THN_PAJAK_SPPT_PEMBAYARAN` char(4) NOT NULL,
  `TGL_TERBIT_SPPT` date NOT NULL DEFAULT '1900-01-01',
  `PBB_YG_HARUS_DIBAYAR_SPPT` decimal(17,3) NOT NULL DEFAULT '0.000',
  `TGL_PEMBAYARAN_SPPT` date NOT NULL DEFAULT '1900-01-01',
  `TGL_REKAM_BYR_SPPT` date NOT NULL DEFAULT '1900-01-01',
  `DENDA_SPPT` decimal(17,3) NOT NULL DEFAULT '0.000',
  `JML_SPPT_YG_DIBAYAR` decimal(17,3) NOT NULL DEFAULT '0.000',
  `NM_WP_SPPT` varchar(255) NOT NULL DEFAULT '',
  `CODEX` varchar(40) NOT NULL DEFAULT '' COMMENT 'CODEX',
  `STATUS` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`KD_PROPINSI`,`KD_DATI2`,`KD_KECAMATAN`,`KD_KELURAHAN`,`KD_BLOK`,`NO_URUT`,`KD_JNS_OP`,`THN_PAJAK_SPPT_SPPT`,`THN_PAJAK_SPPT_PEMBAYARAN`,`TGL_TERBIT_SPPT`,`TGL_PEMBAYARAN_SPPT`),
  KEY `TGL_PEMBAYARAN_SPPT` (`TGL_PEMBAYARAN_SPPT`),
  KEY `KD_PROPINSI` (`KD_PROPINSI`),
  KEY `KD_DATI2` (`KD_DATI2`),
  KEY `KD_KECAMATAN` (`KD_KECAMATAN`),
  KEY `KD_KELURAHAN` (`KD_KELURAHAN`),
  KEY `KD_BLOK` (`KD_BLOK`),
  KEY `NO_URUT` (`NO_URUT`),
  KEY `KD_JNS_OP` (`KD_JNS_OP`),
  KEY `THN_PAJAK_SPPT_SPPT` (`THN_PAJAK_SPPT_SPPT`),
  KEY `THN_PAJAK_SPPT_PEMBAYARAN` (`THN_PAJAK_SPPT_PEMBAYARAN`),
  KEY `TGL_TERBIT_SPPT` (`TGL_TERBIT_SPPT`),
  KEY `CODEX` (`CODEX`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_buku_kas_umum`
--

DROP TABLE IF EXISTS `appx_buku_kas_umum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_buku_kas_umum` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID DATA',
  `codex` varchar(40) NOT NULL DEFAULT '' COMMENT 'CODEX',
  `th_pajak` varchar(4) NOT NULL DEFAULT '' COMMENT 'TAHUN PAJAK',
  `no_bku` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NO. BKU',
  `subnobku_a` int(11) NOT NULL DEFAULT '0' COMMENT 'SPECIFIC BKU - LEVEL 1',
  `subnobku_b` int(11) NOT NULL DEFAULT '0' COMMENT 'SPECIFIC BKU - LEVEL 2',
  `origin_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID DATA ORIGIN',
  `opidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'REF. OBJEK PAJAK ENTRY',
  `dt_entry` datetime NOT NULL DEFAULT '1900-01-01 00:00:00' COMMENT 'DATETIME INSERT DATA',
  `d_entry` date NOT NULL DEFAULT '1900-01-01' COMMENT 'DATE INSERT DATA',
  `context` varchar(64) NOT NULL DEFAULT '' COMMENT 'CONTEXT',
  `subcontext_a` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 1',
  `subcontext_b` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 2',
  `subcontext_c` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 3',
  `kode_rekening` varchar(64) NOT NULL DEFAULT '' COMMENT 'KODE REKENING - LEVEL 0',
  `subkoderekening_a` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 1',
  `subkoderekening_b` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 2',
  `subkoderekening_c` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 3',
  `dk` varchar(8) NOT NULL DEFAULT '' COMMENT 'DEBET/KREDIT',
  `debet` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'DEBET',
  `kredit` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'KREDIT',
  `cnc` varchar(8) NOT NULL DEFAULT '' COMMENT 'TUNAI/BANK',
  `cash` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'TUNAI',
  `bank` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'BANK',
  `nominal` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMINAL TRANSAKSI',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS DATA',
  `notes` varchar(255) NOT NULL DEFAULT '' COMMENT 'KETERANGAN/URAIAN',
  PRIMARY KEY (`idx`),
  KEY `codex` (`codex`),
  KEY `no_bku` (`no_bku`),
  KEY `subnobku_a` (`subnobku_a`),
  KEY `subnobku_b` (`subnobku_b`),
  KEY `origin_idx` (`origin_idx`),
  KEY `opidx` (`opidx`),
  KEY `dt_entry` (`dt_entry`),
  KEY `d_entry` (`d_entry`),
  KEY `context` (`context`),
  KEY `subcontext_a` (`subcontext_a`),
  KEY `subcontext_b` (`subcontext_b`),
  KEY `subcontext_c` (`subcontext_c`),
  KEY `kode_rekening` (`kode_rekening`),
  KEY `subkoderekening_a` (`subkoderekening_a`),
  KEY `subkoderekening_b` (`subkoderekening_b`),
  KEY `subkoderekening_c` (`subkoderekening_c`)
) ENGINE=MyISAM AUTO_INCREMENT=1281777 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_buku_kas_umum_opd`
--

DROP TABLE IF EXISTS `appx_buku_kas_umum_opd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_buku_kas_umum_opd` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID DATA',
  `codex` varchar(40) NOT NULL DEFAULT '' COMMENT 'CODEX',
  `tahun` varchar(4) NOT NULL DEFAULT '' COMMENT 'TAHUN PAJAK',
  `no_bku` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NO. BKU',
  `subnobku_a` int(11) NOT NULL DEFAULT '0' COMMENT 'SPECIFIC BKU - LEVEL 1',
  `subnobku_b` int(11) NOT NULL DEFAULT '0' COMMENT 'SPECIFIC BKU - LEVEL 2',
  `origin_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID DATA ORIGIN',
  `opidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'REF. OBJEK PAJAK ENTRY',
  `dt_entry` datetime NOT NULL DEFAULT '1900-01-01 00:00:00' COMMENT 'DATETIME INSERT DATA',
  `dt_lastupdate` datetime NOT NULL DEFAULT '1900-01-01 00:00:00' COMMENT 'DATETIME LAST UPDATE',
  `d_entry` date NOT NULL DEFAULT '1900-01-01' COMMENT 'DATE INSERT DATA',
  `context` varchar(64) NOT NULL DEFAULT '' COMMENT 'CONTEXT',
  `subcontext_a` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 1',
  `subcontext_b` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 2',
  `subcontext_c` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 3',
  `opdidx` varchar(16) NOT NULL DEFAULT '' COMMENT 'KODE OPD',
  `kode_a` varchar(64) NOT NULL DEFAULT '' COMMENT 'KODE REKENING - LEVEL 0',
  `kode_b` varchar(64) NOT NULL DEFAULT '' COMMENT 'KODE REKENING - LEVEL 1',
  `kode_c` varchar(64) NOT NULL DEFAULT '' COMMENT 'KODE REKENING - LEVEL 2',
  `kode_d` varchar(64) NOT NULL DEFAULT '' COMMENT 'KODE REKENING - LEVEL 3',
  `kode_e` varchar(64) NOT NULL DEFAULT '' COMMENT 'KODE REKENING - LEVEL 4',
  `kode_f` varchar(64) NOT NULL DEFAULT '' COMMENT 'KODE REKENING - LEVEL 5',
  `kode_g` varchar(64) NOT NULL DEFAULT '' COMMENT 'KODE REKENING - LEVEL 6',
  `namarekening` text NOT NULL,
  `dk` varchar(8) NOT NULL DEFAULT '' COMMENT 'DEBET/KREDIT',
  `debet` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'DEBET',
  `kredit` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'KREDIT',
  `cnc` varchar(8) NOT NULL DEFAULT '' COMMENT 'TUNAI/BANK',
  `cash` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'TUNAI',
  `bank` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'BANK',
  `origin` varchar(255) NOT NULL DEFAULT '',
  `target` varchar(255) NOT NULL DEFAULT '',
  `nominal` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMINAL TRANSAKSI',
  `penyetoridx` int(11) NOT NULL DEFAULT '0',
  `penyetor` varchar(255) NOT NULL DEFAULT '',
  `review_bppkad` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS REVIEW BPPKAD',
  `d_review` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL REVIEW',
  `notes_review` text NOT NULL COMMENT 'TEXT REVIEW BPPKAD',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS DATA',
  `filests` varchar(255) NOT NULL DEFAULT '' COMMENT 'FILE STS',
  `notes` text NOT NULL COMMENT 'KETERANGAN/URAIAN',
  PRIMARY KEY (`idx`),
  KEY `codex` (`codex`),
  KEY `no_bku` (`no_bku`),
  KEY `subnobku_a` (`subnobku_a`),
  KEY `subnobku_b` (`subnobku_b`),
  KEY `dt_entry` (`dt_entry`),
  KEY `d_entry` (`d_entry`),
  KEY `context` (`context`),
  KEY `opdidx` (`opdidx`),
  KEY `kode_a` (`kode_a`),
  KEY `kode_b` (`kode_b`),
  KEY `kode_c` (`kode_c`),
  KEY `kode_d` (`kode_d`),
  KEY `kode_e` (`kode_e`),
  KEY `kode_f` (`kode_f`),
  KEY `kode_g` (`kode_g`)
) ENGINE=MyISAM AUTO_INCREMENT=1427 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_buku_kas_umum_pengurutan`
--

DROP TABLE IF EXISTS `appx_buku_kas_umum_pengurutan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_buku_kas_umum_pengurutan` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID DATA',
  `origin_bkuidx` int(11) NOT NULL DEFAULT '0',
  `codex` varchar(40) NOT NULL DEFAULT '' COMMENT 'CODEX',
  `th_pajak` varchar(4) NOT NULL DEFAULT '' COMMENT 'TAHUN PAJAK',
  `no_bku` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NO. BKU',
  `subnobku_a` int(11) NOT NULL DEFAULT '0' COMMENT 'SPECIFIC BKU - LEVEL 1',
  `subnobku_b` int(11) NOT NULL DEFAULT '0' COMMENT 'SPECIFIC BKU - LEVEL 2',
  `origin_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID DATA ORIGIN',
  `opidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'REF. OBJEK PAJAK ENTRY',
  `dt_entry` datetime NOT NULL DEFAULT '1900-01-01 00:00:00' COMMENT 'DATETIME INSERT DATA',
  `d_entry` date NOT NULL DEFAULT '1900-01-01' COMMENT 'DATE INSERT DATA',
  `context` varchar(64) NOT NULL DEFAULT '' COMMENT 'CONTEXT',
  `subcontext_a` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 1',
  `subcontext_b` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 2',
  `subcontext_c` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 3',
  `kode_rekening` varchar(64) NOT NULL DEFAULT '' COMMENT 'KODE REKENING - LEVEL 0',
  `subkoderekening_a` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 1',
  `subkoderekening_b` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 2',
  `subkoderekening_c` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 3',
  `dk` varchar(8) NOT NULL DEFAULT '' COMMENT 'DEBET/KREDIT',
  `debet` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'DEBET',
  `kredit` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'KREDIT',
  `cnc` varchar(8) NOT NULL DEFAULT '' COMMENT 'TUNAI/BANK',
  `cash` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'TUNAI',
  `bank` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'BANK',
  `nominal` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMINAL TRANSAKSI',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS DATA',
  `notes` varchar(255) NOT NULL DEFAULT '' COMMENT 'KETERANGAN/URAIAN',
  PRIMARY KEY (`idx`),
  KEY `codex` (`codex`),
  KEY `no_bku` (`no_bku`),
  KEY `subnobku_a` (`subnobku_a`),
  KEY `subnobku_b` (`subnobku_b`),
  KEY `origin_idx` (`origin_idx`),
  KEY `opidx` (`opidx`),
  KEY `dt_entry` (`dt_entry`),
  KEY `d_entry` (`d_entry`),
  KEY `context` (`context`),
  KEY `subcontext_a` (`subcontext_a`),
  KEY `subcontext_b` (`subcontext_b`),
  KEY `subcontext_c` (`subcontext_c`),
  KEY `kode_rekening` (`kode_rekening`),
  KEY `subkoderekening_a` (`subkoderekening_a`),
  KEY `subkoderekening_b` (`subkoderekening_b`),
  KEY `subkoderekening_c` (`subkoderekening_c`)
) ENGINE=MyISAM AUTO_INCREMENT=532765 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_buku_kas_umum_rebuild`
--

DROP TABLE IF EXISTS `appx_buku_kas_umum_rebuild`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_buku_kas_umum_rebuild` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID DATA',
  `codex` varchar(40) NOT NULL DEFAULT '' COMMENT 'CODEX',
  `th_pajak` varchar(4) NOT NULL DEFAULT '' COMMENT 'TAHUN PAJAK',
  `no_bku` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NO. BKU',
  `subnobku_a` int(11) NOT NULL DEFAULT '0' COMMENT 'SPECIFIC BKU - LEVEL 1',
  `subnobku_b` int(11) NOT NULL DEFAULT '0' COMMENT 'SPECIFIC BKU - LEVEL 2',
  `origin_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID DATA ORIGIN',
  `opidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'REF. OBJEK PAJAK ENTRY',
  `dt_entry` datetime NOT NULL DEFAULT '1900-01-01 00:00:00' COMMENT 'DATETIME INSERT DATA',
  `d_entry` date NOT NULL DEFAULT '1900-01-01' COMMENT 'DATE INSERT DATA',
  `context` varchar(64) NOT NULL DEFAULT '' COMMENT 'CONTEXT',
  `subcontext_a` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 1',
  `subcontext_b` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 2',
  `subcontext_c` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 3',
  `kode_rekening` varchar(64) NOT NULL DEFAULT '' COMMENT 'KODE REKENING - LEVEL 0',
  `subkoderekening_a` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 1',
  `subkoderekening_b` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 2',
  `subkoderekening_c` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 3',
  `dk` varchar(8) NOT NULL DEFAULT '' COMMENT 'DEBET/KREDIT',
  `debet` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'DEBET',
  `kredit` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'KREDIT',
  `cnc` varchar(8) NOT NULL DEFAULT '' COMMENT 'TUNAI/BANK',
  `cash` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'TUNAI',
  `bank` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'BANK',
  `nominal` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMINAL TRANSAKSI',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS DATA',
  `notes` varchar(255) NOT NULL DEFAULT '' COMMENT 'KETERANGAN/URAIAN',
  PRIMARY KEY (`idx`),
  KEY `codex` (`codex`),
  KEY `no_bku` (`no_bku`),
  KEY `subnobku_a` (`subnobku_a`),
  KEY `subnobku_b` (`subnobku_b`),
  KEY `origin_idx` (`origin_idx`),
  KEY `opidx` (`opidx`),
  KEY `dt_entry` (`dt_entry`),
  KEY `d_entry` (`d_entry`),
  KEY `context` (`context`),
  KEY `subcontext_a` (`subcontext_a`),
  KEY `subcontext_b` (`subcontext_b`),
  KEY `subcontext_c` (`subcontext_c`),
  KEY `kode_rekening` (`kode_rekening`),
  KEY `subkoderekening_a` (`subkoderekening_a`),
  KEY `subkoderekening_b` (`subkoderekening_b`),
  KEY `subkoderekening_c` (`subkoderekening_c`)
) ENGINE=MyISAM AUTO_INCREMENT=624948 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_buku_kas_umum_reklame`
--

DROP TABLE IF EXISTS `appx_buku_kas_umum_reklame`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_buku_kas_umum_reklame` (
  `idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID DATA',
  `codex` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'CODEX',
  `th_pajak` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'TAHUN PAJAK',
  `no_bku` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NO. BKU',
  `subnobku_a` int(11) NOT NULL DEFAULT '0' COMMENT 'SPECIFIC BKU - LEVEL 1',
  `subnobku_b` int(11) NOT NULL DEFAULT '0' COMMENT 'SPECIFIC BKU - LEVEL 2',
  `origin_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID DATA ORIGIN',
  `opidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'REF. OBJEK PAJAK ENTRY',
  `dt_entry` datetime NOT NULL DEFAULT '1900-01-01 00:00:00' COMMENT 'DATETIME INSERT DATA',
  `d_entry` date NOT NULL DEFAULT '1900-01-01' COMMENT 'DATE INSERT DATA',
  `context` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'CONTEXT',
  `subcontext_a` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 1',
  `subcontext_b` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 2',
  `subcontext_c` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 3',
  `kode_rekening` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'KODE REKENING - LEVEL 0',
  `subkoderekening_a` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 1',
  `subkoderekening_b` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 2',
  `subkoderekening_c` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 3',
  `dk` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'DEBET/KREDIT',
  `debet` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'DEBET',
  `kredit` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'KREDIT',
  `cnc` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'TUNAI/BANK',
  `cash` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'TUNAI',
  `bank` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'BANK',
  `nominal` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMINAL TRANSAKSI',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS DATA',
  `notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'KETERANGAN/URAIAN'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_camat_users`
--

DROP TABLE IF EXISTS `appx_camat_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_camat_users` (
  `KD_PROPINSI` varchar(2) NOT NULL,
  `KD_DATI2` varchar(2) NOT NULL,
  `KD_KECAMATAN` varchar(3) NOT NULL,
  `NM_KECAMATAN` varchar(32) NOT NULL DEFAULT '',
  `WORDPASS` varchar(40) NOT NULL DEFAULT '',
  `STATUS` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`KD_PROPINSI`,`KD_DATI2`,`KD_KECAMATAN`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_core_bphtb_analysis`
--

DROP TABLE IF EXISTS `appx_core_bphtb_analysis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_core_bphtb_analysis` (
  `idx` int(10) unsigned NOT NULL COMMENT 'PRIMARY KEY + ID',
  `codex` varchar(40) NOT NULL DEFAULT '' COMMENT 'CODEX',
  `opidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'OBJEK PAJAK ID REF appx_obyek_pajak.idx',
  `th_pajak` varchar(4) NOT NULL DEFAULT '' COMMENT 'TAHUN PAJAK',
  `dt_entry` datetime NOT NULL DEFAULT '1900-01-01 00:00:00' COMMENT 'DATETIME INSERT DATA',
  `d_entry` date NOT NULL DEFAULT '1900-01-01' COMMENT 'DATE INSERT DATA',
  `dt_verified` datetime NOT NULL DEFAULT '1900-01-01 00:00:00' COMMENT 'DATE TIME VERIFIKASI',
  `d_verified` date NOT NULL DEFAULT '1900-01-01' COMMENT 'DATE VERIFIKASI',
  `d_sspd_payment` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL BAYAR SSPD',
  `d_skpdkb_payment` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL BAYAR SKPDKB',
  `data_src` varchar(16) NOT NULL DEFAULT '' COMMENT 'DATA SOURCE',
  `no_pel` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR PELAYANAN',
  `nik_wp` varchar(64) NOT NULL DEFAULT '' COMMENT 'NIK WAJIB PAJAK',
  `nama_wp` varchar(255) NOT NULL DEFAULT '' COMMENT 'DP - NAMA',
  `alamat` varchar(255) NOT NULL DEFAULT '' COMMENT 'DP - ALAMAT',
  `kabupaten` varchar(64) NOT NULL DEFAULT '' COMMENT 'DP - KABUPATEN',
  `kecamatan` varchar(64) NOT NULL DEFAULT '' COMMENT 'DP - KECAMATAN',
  `kelurahan` varchar(64) NOT NULL DEFAULT '' COMMENT 'DP - KELURAHAN',
  `rw` varchar(16) NOT NULL DEFAULT '' COMMENT 'DP - RW',
  `rt` varchar(16) NOT NULL DEFAULT '' COMMENT 'DP - RT',
  `kode_pos` varchar(32) NOT NULL DEFAULT '',
  `id_notaris` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOTARIS ID',
  `kode_notaris` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE NOTARIS',
  `nama_notaris` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA NOTARIS',
  `jenis_trx` varchar(2) NOT NULL DEFAULT '' COMMENT 'JENIS PEROLEHAN / TRANSAKSI',
  `sppt_nop` varchar(32) NOT NULL DEFAULT '' COMMENT 'NOP PADA SPPT',
  `sppt_nama_wp` varchar(64) NOT NULL DEFAULT '',
  `sppt_alamat_wp` varchar(255) NOT NULL DEFAULT '',
  `sppt_kelurahan_wp` varchar(64) NOT NULL DEFAULT '',
  `sppt_kecamatan_wp` varchar(64) NOT NULL DEFAULT '',
  `sppt_kabupaten_wp` varchar(64) NOT NULL DEFAULT '',
  `sppt_thn_pajak` varchar(4) NOT NULL DEFAULT '' COMMENT 'SPPT - TAHUN PAJAK',
  `sppt_lt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - LUAS TANAH/BUMI',
  `sppt_ntm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP TANAH/BUMI M2',
  `sppt_nt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP TANAH/BUMI',
  `sppt_lb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - LUAS BANGUNAN',
  `sppt_nbm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP BANGUNAN M2',
  `sppt_nb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP BANGUNAN',
  `sppt_njop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP BUMI+BANGUNAN',
  `sspd_lt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - LUAS TANAH/BUMI',
  `sspd_ntm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NILAI TANAH/BUMI M2',
  `sspd_nt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NJOP TANAH/BUMI',
  `sspd_lb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - LUAS BANGUNAN',
  `sspd_nbm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NILAI BANGUNAN M2',
  `sspd_nb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NJOP BANGUNAN',
  `sspd_njop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NJOP TANAH/BUMI + BANGUNAN',
  `sspd_trx` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - TRANSAKSI',
  `sspd_npop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NPOP',
  `sspd_npoptkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NPOPTKP',
  `sspd_npopkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NPOPKP',
  `sspd_bphtb_ht` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - BPHTB TERHUTANG',
  `sspd_bphtb_hb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - BPHTB HARUS DIBAYAR',
  `veri_lt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - LUAS TANAH-BUMI',
  `veri_ntm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP TANAH-BUMI/M2',
  `veri_nt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP TANAH-BUMI',
  `veri_lb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - LUAS BANGUNAN',
  `veri_nbm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP BANGUNAN/M2',
  `veri_nb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP BANGUNAN',
  `veri_njop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP TANAH-BUMI + BANGUNAN',
  `veri_trx` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - HARGA TRANSAKSI',
  `veri_npop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NPOP',
  `veri_npoptkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NPOPTKP',
  `veri_npopkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NPOPKP',
  `veri_bphtb_ht` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - BPHTB TERHUTANG',
  `veri_bphtb_hb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - BPHTB YANG HARUS DIBAYAR',
  `veri_bphtb_kb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - BPHTB KURANG BAYAR',
  `veri_kurang_setor` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'VERIFIKATOR - KURANG SETOR',
  `veri_lebih_setor` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'VERIFIKATOR - LEBIH SETOR',
  `paid_sspd` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'JUMLAH PEMBAYARAN SSPD',
  `paid_skpdkb` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'JUMLAH PEMBAYARAN SKPDKB',
  `total_paid` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'TOTAL PEMBAYARAN SSPD + SKPDKB',
  `under_paid` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'SELISIH REALISASI THD TOTAL PEMBAYARAN - KEKURANGAN',
  `over_paid` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'SELISIH REALISASI THD TOTAL PEMBAYARAN - KELEBIHAN',
  `no_sertifikat` varchar(64) NOT NULL DEFAULT '' COMMENT 'NOMOR SERTIFIKAT',
  `petugas` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'PETUGAS VERIFIKASI ID',
  `nama_petugas` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA PETUGAS VERIFIKATOR',
  `id_op` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'OPERATOR PELAYANAN ID',
  `nama_op` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA OPERATOR PELAYANAN',
  `stat_dok` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS DOKUMEN',
  `stat_active` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS DATA AKTIF/NON-AKTIF',
  `note_deactive` varchar(64) NOT NULL DEFAULT '' COMMENT 'KETERANGAN DEAKTIVASI DATA',
  PRIMARY KEY (`idx`),
  KEY `nik_wp` (`nik_wp`),
  KEY `nama_wp` (`nama_wp`),
  KEY `d_entry` (`d_entry`),
  KEY `d_verified` (`d_verified`),
  KEY `d_sspd_payment` (`d_sspd_payment`),
  KEY `d_skpdkb_payment` (`d_skpdkb_payment`),
  KEY `kecamatan` (`kecamatan`),
  KEY `kelurahan` (`kelurahan`),
  KEY `sppt_nop` (`sppt_nop`),
  KEY `id_notaris` (`id_notaris`),
  KEY `kode_notaris` (`kode_notaris`),
  KEY `nama_notaris` (`nama_notaris`),
  KEY `petugas` (`petugas`),
  KEY `nama_petugas` (`nama_petugas`),
  KEY `id_op` (`id_op`),
  KEY `nama_op` (`nama_op`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_core_bphtb_bku`
--

DROP TABLE IF EXISTS `appx_core_bphtb_bku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_core_bphtb_bku` (
  `idx` int(10) unsigned NOT NULL COMMENT 'ID DATA',
  `no_bku` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NO. BKU',
  `origin_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID DATA ORIGIN',
  `opidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'REF. OBJEK PAJAK ENTRY',
  `dt_entry` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT 'DATETIME INSERT DATA',
  `d_entry` date NOT NULL DEFAULT '1970-01-01' COMMENT 'DATE INSERT DATA',
  `context` varchar(64) NOT NULL DEFAULT '' COMMENT 'CONTEXT',
  `subcontext_a` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 1',
  `subcontext_b` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 2',
  `subcontext_c` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 3',
  `kode_rekening` varchar(64) NOT NULL DEFAULT '' COMMENT 'KODE REKENING - LEVEL 0',
  `subkoderekening_a` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 1',
  `subkoderekening_b` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 2',
  `subkoderekening_c` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 3',
  `dk` varchar(8) NOT NULL DEFAULT '' COMMENT 'DEBET/KREDIT',
  `debet` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'DEBET',
  `kredit` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'KREDIT',
  `cnc` varchar(8) NOT NULL DEFAULT '' COMMENT 'TUNAI/BANK',
  `cash` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'TUNAI',
  `bank` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'BANK',
  `nominal` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMINAL TRANSAKSI',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS DATA',
  `notes` varchar(255) NOT NULL DEFAULT '' COMMENT 'KETERANGAN/URAIAN',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_core_bphtb_skpdkb`
--

DROP TABLE IF EXISTS `appx_core_bphtb_skpdkb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_core_bphtb_skpdkb` (
  `idx` int(10) unsigned NOT NULL COMMENT 'PRIMARY KEY + ID',
  `d_entry` date NOT NULL DEFAULT '1900-01-01' COMMENT 'DATE INSERT DATA',
  `paid_skpdkb` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'PEMBAYARAN SKPDKB',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_core_bphtb_sspd`
--

DROP TABLE IF EXISTS `appx_core_bphtb_sspd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_core_bphtb_sspd` (
  `idx` int(10) unsigned NOT NULL COMMENT 'PRIMARY KEY + ID',
  `d_entry` date NOT NULL DEFAULT '1900-01-01' COMMENT 'DATE INSERT DATA',
  `paid_sspd` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'PEMBAYARAN SSPD',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_core_data_penduduk`
--

DROP TABLE IF EXISTS `appx_core_data_penduduk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_core_data_penduduk` (
  `nik` varchar(16) NOT NULL COMMENT 'PD/PF - NIK',
  `nama` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - NAMA',
  `jk` varchar(16) NOT NULL DEFAULT '' COMMENT 'PD/PF - JENIS KELAMIN',
  `tempat` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - TEMPAT LAHIR',
  `tgllahir` date NOT NULL DEFAULT '1900-01-01' COMMENT 'PD/PF - TANGGAL LAHIR',
  `goldarah` varchar(8) NOT NULL DEFAULT '' COMMENT 'PD - GOLONGAN DARAH',
  `agama` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - AGAMA',
  `statuskawin` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - STATUS PERKAWINAN',
  `statushub` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD - STATUS HUBUNGAN DALAM KELUARGA',
  `statushubrt` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'PD - KODE STATUS HUBUNGAN DALAM KELUARGA',
  `pendidikan` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD - PENDIDIKAN TERAKHIR',
  `pekerjaan` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - PEKERJAAN',
  `namaibu` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD - NAMA IBU',
  `namaayah` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD - NAMA AYAH',
  `nokk` varchar(16) NOT NULL DEFAULT '' COMMENT 'PD - NOMOR KK',
  `namakk` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD - NAMA KEPALA KELUARGA',
  `alamat` varchar(255) NOT NULL DEFAULT '' COMMENT 'PD/PF - ALAMAT',
  `kode_pos` varchar(8) NOT NULL DEFAULT '' COMMENT 'PD/PF - KODE POS',
  `kode_prop` varchar(4) NOT NULL DEFAULT '' COMMENT 'PD/PF - KODE PROPINSI',
  `nama_prop` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - NAMA PROPINSI',
  `kode_kab` varchar(4) NOT NULL DEFAULT '' COMMENT 'PD/PF - KODE KABUPATEN',
  `nama_kab` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - NAMA KABUPATEN',
  `kode_kec` varchar(4) NOT NULL DEFAULT '' COMMENT 'PD/PF - KODE KECAMATAN',
  `nama_kec` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - NAMA KECAMATAN',
  `kode_kel` varchar(4) NOT NULL DEFAULT '' COMMENT 'PD/PF - KODE DESA / KELURAHAN',
  `nama_kel` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - NAMA DESA / KELURAHAN',
  `rw` varchar(3) NOT NULL DEFAULT '' COMMENT 'PD/PF - RW',
  `rt` varchar(3) NOT NULL DEFAULT '' COMMENT 'PD/PF - RT',
  PRIMARY KEY (`nik`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_core_organisation`
--

DROP TABLE IF EXISTS `appx_core_organisation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_core_organisation` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentidx` int(11) NOT NULL DEFAULT '0',
  `opdidx` varchar(16) NOT NULL DEFAULT 'UNDEFINED',
  `opdname` varchar(255) NOT NULL DEFAULT 'UNDEFINED',
  `opdheadtitle` varchar(255) NOT NULL DEFAULT 'UNDEFINED',
  `opdheadname` varchar(255) NOT NULL DEFAULT 'UNDEFINED',
  `opdheadnip` varchar(255) NOT NULL DEFAULT 'UNDEFINED',
  `opdbendaharatitle` varchar(255) NOT NULL DEFAULT 'UNDEFINED',
  `opdbendaharaname` varchar(255) NOT NULL DEFAULT 'UNDEFINED',
  `opdbendaharanip` varchar(255) NOT NULL DEFAULT 'UNDEFINED',
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_core_realisasi_pendapatan`
--

DROP TABLE IF EXISTS `appx_core_realisasi_pendapatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_core_realisasi_pendapatan` (
  `kode_a` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 1',
  `kode_b` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 2',
  `kode_c` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 3',
  `kode_d` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 4',
  `kode_e` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 5',
  `kode_f` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 6',
  `kode_g` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 7',
  `d_entry` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL PERHITUNGAN REALISASI',
  `tahun` char(4) NOT NULL COMMENT 'TAHUN ANGGARAN',
  `rekening` varchar(255) NOT NULL DEFAULT '' COMMENT 'REKENING/JENIS PENDAPATAN',
  `rencana_penetapan` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'RENCANA PENDAPATAN - PENETAPAN',
  `rencana_perubahan` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'RENCANA PENDAPATAN - PERUBAHAN',
  `rencana` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'RENCANA PENDAPATAN',
  `sd_bulan_lalu` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN BULAN LALU',
  `bulan_laporan` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI PADA BULAN LAPORAN',
  `sd_bulan_laporan` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN BULAN LAPORAN',
  `persen` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN',
  `selisih` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'SELISIH',
  PRIMARY KEY (`kode_a`,`kode_b`,`kode_c`,`kode_d`,`kode_e`,`kode_f`,`kode_g`,`tahun`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_core_realisasi_pendapatan_periodic`
--

DROP TABLE IF EXISTS `appx_core_realisasi_pendapatan_periodic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_core_realisasi_pendapatan_periodic` (
  `kode_a` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 1',
  `kode_b` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 2',
  `kode_c` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 3',
  `kode_d` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 4',
  `kode_e` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 5',
  `kode_f` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 6',
  `kode_g` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 7',
  `d_entry` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL PERHITUNGAN REALISASI',
  `tahun` char(4) NOT NULL COMMENT 'TAHUN ANGGARAN',
  `rekening` varchar(255) NOT NULL DEFAULT '' COMMENT 'REKENING/JENIS PENDAPATAN',
  `rencana_penetapan` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'RENCANA PENDAPATAN - PENETAPAN',
  `rencana_perubahan` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'RENCANA PENDAPATAN - PERUBAHAN',
  `rencana` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'RENCANA PENDAPATAN',
  `sd_bulan_lalu` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN BULAN LALU',
  `bulan_laporan` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI PADA BULAN LAPORAN',
  `sd_bulan_laporan` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN BULAN LAPORAN',
  `persen` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN',
  `selisih` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'SELISIH',
  PRIMARY KEY (`kode_a`,`kode_b`,`kode_c`,`kode_d`,`kode_e`,`kode_f`,`kode_g`,`tahun`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_core_realisasi_perbulan`
--

DROP TABLE IF EXISTS `appx_core_realisasi_perbulan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_core_realisasi_perbulan` (
  `kode_a` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 1',
  `kode_b` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 2',
  `kode_c` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 3',
  `kode_d` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 4',
  `kode_e` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 5',
  `kode_f` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 6',
  `kode_g` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 7',
  `tahun` char(4) NOT NULL COMMENT 'TAHUN ANGGARAN',
  `rekening` varchar(255) NOT NULL DEFAULT '' COMMENT 'REKENING/JENIS PENDAPATAN',
  `rencana_penetapan` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'RENCANA PENDAPATAN',
  `rencana_perubahan` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'RENCANA PENDAPATAN - PERUBAHAN',
  `real_01` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI BULAN 01',
  `accm_01` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN BULAN 01',
  `pctr_01` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA BULAN 01',
  `real_02` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI BULAN 02',
  `accm_02` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN BULAN 02',
  `pctr_02` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA BULAN 02',
  `real_03` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI BULAN 03',
  `accm_03` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN BULAN 03',
  `pctr_03` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA BULAN 03',
  `real_triwulan_01` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI TRIWULAN I',
  `accm_triwulan_01` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN TRIWULAN I',
  `pctr_triwulan_01` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA TRIWULAN I',
  `real_04` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI BULAN 04',
  `accm_04` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN BULAN 04',
  `pctr_04` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA BULAN 04',
  `real_kuartal_01` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI KUARTAL I',
  `accm_kuartal_01` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN KUARTAL I',
  `pctr_kuartal_01` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA KUARTAL I',
  `real_05` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI BULAN 05',
  `accm_05` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN BULAN 05',
  `pctr_05` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA BULAN 05',
  `real_06` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI BULAN 06',
  `accm_06` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN BULAN 06',
  `pctr_06` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA BULAN 06',
  `real_triwulan_02` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI TRIWULAN II',
  `accm_triwulan_02` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN TRIWULAN II',
  `pctr_triwulan_02` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA TRIWULAN II',
  `real_semester_01` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SEMESTER I',
  `accm_semester_01` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN SEMESTER I',
  `pctr_semester_01` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA SEMESTER I',
  `real_07` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI BULAN 07',
  `accm_07` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN BULAN 07',
  `pctr_07` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA BULAN 07',
  `real_08` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI BULAN 08',
  `accm_08` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN BULAN 08',
  `pctr_08` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA BULAN 08',
  `real_kuartal_02` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI KUARTAL II',
  `accm_kuartal_02` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN KUARTAL II',
  `pctr_kuartal_02` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA KUARTAL II',
  `real_09` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI BULAN 09',
  `accm_09` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN BULAN 09',
  `pctr_09` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA BULAN 09',
  `real_triwulan_03` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI TRIWULAN III',
  `accm_triwulan_03` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN TRIWULAN III',
  `pctr_triwulan_03` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA TRIWULAN III',
  `real_10` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI BULAN 10',
  `accm_10` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN BULAN 10',
  `pctr_10` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA BULAN 10',
  `real_11` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI BULAN 11',
  `accm_11` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN BULAN 11',
  `pctr_11` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA BULAN 11',
  `real_12` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI BULAN 12',
  `accm_12` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN BULAN 12',
  `pctr_12` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA BULAN 12',
  `real_triwulan_04` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI TRIWULAN IV',
  `accm_triwulan_04` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN TRIWULAN IV',
  `pctr_triwulan_04` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA TRIWULAN IV',
  `real_kuartal_03` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI KUARTAL III',
  `accm_kuartal_03` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN KUARTAL III',
  `pctr_kuartal_03` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA KUARTAL III',
  `real_semester_02` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SEMESTER II',
  `accm_semester_02` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'REALISASI SAMPAI DENGAN SEMESTER II',
  `pctr_semester_02` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN HINGGA SEMESTER II',
  `pct` decimal(7,3) NOT NULL DEFAULT '0.000' COMMENT 'PERSENTASE REALISASI TERHADAP RENCANA PENDAPATAN',
  PRIMARY KEY (`kode_a`,`kode_b`,`kode_c`,`kode_d`,`kode_e`,`kode_f`,`kode_g`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_core_rekening_koran_opd`
--

DROP TABLE IF EXISTS `appx_core_rekening_koran_opd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_core_rekening_koran_opd` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codex` varchar(40) NOT NULL DEFAULT '',
  `tahun` varchar(8) NOT NULL DEFAULT '',
  `opdidx` varchar(16) NOT NULL DEFAULT '',
  `opdname` varchar(255) NOT NULL DEFAULT '',
  `dt_entry` datetime NOT NULL DEFAULT '1900-01-01 00:00:01',
  `d_entry` date NOT NULL DEFAULT '1900-01-01',
  `filerekeningkoran` text NOT NULL,
  `notes` text NOT NULL,
  `review_bppkad` int(10) unsigned NOT NULL DEFAULT '0',
  `d_review` date NOT NULL DEFAULT '1900-01-01',
  `notes_review` text NOT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_core_rekening_opd`
--

DROP TABLE IF EXISTS `appx_core_rekening_opd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_core_rekening_opd` (
  `kodeopd` varchar(16) NOT NULL DEFAULT '' COMMENT 'KODE OPD',
  `kode_a` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 1',
  `kode_b` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 2',
  `kode_c` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 3',
  `kode_d` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 4',
  `kode_e` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 5',
  `kode_f` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 6',
  `kode_g` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 7',
  `rekening` text NOT NULL COMMENT 'REKENING',
  PRIMARY KEY (`kodeopd`,`kode_a`,`kode_b`,`kode_c`,`kode_d`,`kode_e`,`kode_f`,`kode_g`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_core_rekening_pendapatan`
--

DROP TABLE IF EXISTS `appx_core_rekening_pendapatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_core_rekening_pendapatan` (
  `idx` varchar(17) NOT NULL DEFAULT '' COMMENT 'COMPOSITE ID',
  `tahun` char(4) NOT NULL,
  `nourut` int(11) NOT NULL DEFAULT '0' COMMENT 'NOMOR URUT',
  `kode_a` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 1',
  `kode_b` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 2',
  `kode_c` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 3',
  `kode_d` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 4',
  `kode_e` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 5',
  `kode_f` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 6',
  `kode_g` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 7',
  `rekening` varchar(255) NOT NULL DEFAULT '' COMMENT 'REKENING',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT 'STATUS',
  PRIMARY KEY (`tahun`,`kode_a`,`kode_b`,`kode_c`,`kode_d`,`kode_e`,`kode_f`,`kode_g`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_core_rencana_pendapatan`
--

DROP TABLE IF EXISTS `appx_core_rencana_pendapatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_core_rencana_pendapatan` (
  `kode_a` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 1',
  `kode_b` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 2',
  `kode_c` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 3',
  `kode_d` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 4',
  `kode_e` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 5',
  `kode_f` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 6',
  `kode_g` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 7',
  `tahun` char(4) NOT NULL COMMENT 'TAHUN ANGGARAN',
  `rekening` varchar(255) NOT NULL DEFAULT '' COMMENT 'REKENING',
  `rencana_penetapan` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'RENCANA PENDAPATAN - PENETAPAN',
  `rencana_perubahan` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'RENCANA PENDAPATAN - PERUBAHAN',
  PRIMARY KEY (`kode_a`,`kode_b`,`kode_c`,`kode_d`,`kode_e`,`kode_f`,`kode_g`,`tahun`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_core_rencana_pendapatan_backup`
--

DROP TABLE IF EXISTS `appx_core_rencana_pendapatan_backup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_core_rencana_pendapatan_backup` (
  `kode_a` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 1',
  `kode_b` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 2',
  `kode_c` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 3',
  `kode_d` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 4',
  `kode_e` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 5',
  `kode_f` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 6',
  `kode_g` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 7',
  `tahun` char(4) NOT NULL COMMENT 'TAHUN ANGGARAN',
  `rekening` varchar(255) NOT NULL DEFAULT '' COMMENT 'REKENING',
  `rencana_penetapan` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'RENCANA PENDAPATAN - PENETAPAN',
  `rencana_perubahan` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'RENCANA PENDAPATAN - PERUBAHAN',
  PRIMARY KEY (`kode_a`,`kode_b`,`kode_c`,`kode_d`,`kode_e`,`kode_f`,`kode_g`,`tahun`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_core_sspd_bphtb`
--

DROP TABLE IF EXISTS `appx_core_sspd_bphtb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_core_sspd_bphtb` (
  `idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'PRIMARY KEY + ID',
  `codex` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'CODEX',
  `opidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'OBJEK PAJAK ID REF appx_obyek_pajak.idx',
  `th_pajak` varchar(4) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'TAHUN PAJAK',
  `dt_entry` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT 'DATETIME INSERT DATA',
  `d_entry` date NOT NULL DEFAULT '1970-01-01' COMMENT 'DATE INSERT DATA',
  `dt_verified` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT 'DATE TIME VERIFIKASI',
  `d_verified` date NOT NULL DEFAULT '1970-01-01' COMMENT 'DATE VERIFIKASI',
  `data_src` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'DATA SOURCE',
  `no_pel` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR PELAYANAN',
  `nik_wp` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'NIK WAJIB PAJAK',
  `nama_wp` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'DP - NAMA',
  `alamat` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'DP - ALAMAT',
  `kabupaten` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'DP - KABUPATEN',
  `kecamatan` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'DP - KECAMATAN',
  `kelurahan` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'DP - KELURAHAN',
  `rw` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'DP - RW',
  `rt` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'DP - RT',
  `kode_pos` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `id_notaris` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOTARIS ID',
  `kode_notaris` varchar(4) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'KODE NOTARIS',
  `nama_notaris` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'NAMA NOTARIS',
  `jenis_trx` varchar(2) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'JENIS PEROLEHAN / TRANSAKSI',
  `sppt_nop` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'NOP PADA SPPT',
  `sppt_nama_wp` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `sppt_alamat_wp` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `sppt_kelurahan_wp` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `sppt_kecamatan_wp` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `sppt_kabupaten_wp` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `sppt_thn_pajak` varchar(4) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'SPPT - TAHUN PAJAK',
  `sppt_lt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - LUAS TANAH/BUMI',
  `sppt_ntm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP TANAH/BUMI M2',
  `sppt_nt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP TANAH/BUMI',
  `sppt_lb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - LUAS BANGUNAN',
  `sppt_nbm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP BANGUNAN M2',
  `sppt_nb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP BANGUNAN',
  `sppt_njop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP BUMI+BANGUNAN',
  `sspd_lt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - LUAS TANAH/BUMI',
  `sspd_ntm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NILAI TANAH/BUMI M2',
  `sspd_nt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NJOP TANAH/BUMI',
  `sspd_lb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - LUAS BANGUNAN',
  `sspd_nbm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NILAI BANGUNAN M2',
  `sspd_nb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NJOP BANGUNAN',
  `sspd_njop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NJOP TANAH/BUMI + BANGUNAN',
  `sspd_trx` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - TRANSAKSI',
  `sspd_npop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NPOP',
  `sspd_npoptkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NPOPTKP',
  `sspd_npopkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NPOPKP',
  `sspd_bphtb_ht` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - BPHTB TERHUTANG',
  `sspd_bphtb_hb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - BPHTB HARUS DIBAYAR',
  `veri_lt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - LUAS TANAH-BUMI',
  `veri_ntm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP TANAH-BUMI/M2',
  `veri_nt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP TANAH-BUMI',
  `veri_lb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - LUAS BANGUNAN',
  `veri_nbm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP BANGUNAN/M2',
  `veri_nb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP BANGUNAN',
  `veri_njop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP TANAH-BUMI + BANGUNAN',
  `veri_trx` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - HARGA TRANSAKSI',
  `veri_npop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NPOP',
  `veri_npoptkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NPOPTKP',
  `veri_npopkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NPOPKP',
  `veri_bphtb_ht` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - BPHTB TERHUTANG',
  `veri_bphtb_hb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - BPHTB YANG HARUS DIBAYAR',
  `veri_bphtb_kb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - BPHTB KURANG BAYAR',
  `veri_kurang_setor` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'VERIFIKATOR - KURANG SETOR',
  `veri_lebih_setor` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'VERIFIKATOR - LEBIH SETOR',
  `no_sertifikat` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'NOMOR SERTIFIKAT',
  `petugas` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'PETUGAS VERIFIKASI ID',
  `id_op` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'OPERATOR PELAYANAN ID',
  `stat_dok` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS DOKUMEN',
  `stat_active` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS DATA AKTIF/NON-AKTIF',
  `note_deactive` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'KETERANGAN DEAKTIVASI DATA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_desa_petugas_pungut`
--

DROP TABLE IF EXISTS `appx_desa_petugas_pungut`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_desa_petugas_pungut` (
  `idx` int(10) NOT NULL AUTO_INCREMENT,
  `KD_PROPINSI` varchar(2) NOT NULL,
  `KD_DATI2` varchar(2) NOT NULL,
  `KD_KECAMATAN` varchar(3) NOT NULL,
  `KD_KELURAHAN` varchar(3) NOT NULL,
  `NM_KECAMATAN` varchar(32) NOT NULL DEFAULT '',
  `NM_KELURAHAN` varchar(32) NOT NULL DEFAULT '',
  `NAMA_PETUGAS` varchar(64) NOT NULL DEFAULT '',
  `WILAYAH` varchar(64) NOT NULL DEFAULT '',
  `JABATAN` varchar(255) NOT NULL,
  `STATUS` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=836 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_desa_users`
--

DROP TABLE IF EXISTS `appx_desa_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_desa_users` (
  `KD_PROPINSI` varchar(2) NOT NULL,
  `KD_DATI2` varchar(2) NOT NULL,
  `KD_KECAMATAN` varchar(3) NOT NULL,
  `KD_KELURAHAN` varchar(3) NOT NULL,
  `NM_KECAMATAN` varchar(32) NOT NULL DEFAULT '',
  `NM_KELURAHAN` varchar(32) NOT NULL DEFAULT '',
  `WORDPASS` varchar(40) NOT NULL DEFAULT '',
  `STATUS` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`KD_PROPINSI`,`KD_DATI2`,`KD_KECAMATAN`,`KD_KELURAHAN`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_kecamatan_users`
--

DROP TABLE IF EXISTS `appx_kecamatan_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_kecamatan_users` (
  `KD_PROPINSI` varchar(2) NOT NULL,
  `KD_DATI2` varchar(2) NOT NULL,
  `KD_KECAMATAN` varchar(3) NOT NULL,
  `NM_KECAMATAN` varchar(32) NOT NULL DEFAULT '',
  `WORDPASS` varchar(40) NOT NULL DEFAULT '',
  `STATUS` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`KD_PROPINSI`,`KD_DATI2`,`KD_KECAMATAN`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_master_data_penduduk`
--

DROP TABLE IF EXISTS `appx_master_data_penduduk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_master_data_penduduk` (
  `nik` varchar(16) NOT NULL COMMENT 'PD/PF - NIK',
  `kategori` varchar(4) NOT NULL DEFAULT '' COMMENT 'KATEGORI: PD / PF',
  `npwp` varchar(32) NOT NULL DEFAULT '' COMMENT 'PD/PF - NPWP NASIONAL',
  `npwpd` varchar(32) NOT NULL DEFAULT '' COMMENT 'PD/PF - NPWPD',
  `nama` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - NAMA',
  `jk` varchar(16) NOT NULL DEFAULT '' COMMENT 'PD/PF - JENIS KELAMIN',
  `tempat` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - TEMPAT LAHIR',
  `tgllahir` date NOT NULL DEFAULT '1900-01-01' COMMENT 'PD/PF - TANGGAL LAHIR',
  `goldarah` varchar(8) NOT NULL DEFAULT '' COMMENT 'PD - GOLONGAN DARAH',
  `agama` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - AGAMA',
  `statuskawin` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - STATUS PERKAWINAN',
  `statushub` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD - STATUS HUBUNGAN DALAM KELUARGA',
  `statushubrt` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'PD - KODE STATUS HUBUNGAN DALAM KELUARGA',
  `pendidikan` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD - PENDIDIKAN TERAKHIR',
  `pekerjaan` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - PEKERJAAN',
  `namaibu` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD - NAMA IBU',
  `namaayah` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD - NAMA AYAH',
  `nokk` varchar(16) NOT NULL DEFAULT '' COMMENT 'PD - NOMOR KK',
  `namakk` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD - NAMA KEPALA KELUARGA',
  `alamat` varchar(255) NOT NULL DEFAULT '' COMMENT 'PD/PF - ALAMAT',
  `kode_pos` varchar(8) NOT NULL DEFAULT '' COMMENT 'PD/PF - KODE POS',
  `kode_prop` varchar(4) NOT NULL DEFAULT '' COMMENT 'PD/PF - KODE PROPINSI',
  `nama_prop` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - NAMA PROPINSI',
  `kode_kab` varchar(4) NOT NULL DEFAULT '' COMMENT 'PD/PF - KODE KABUPATEN',
  `nama_kab` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - NAMA KABUPATEN',
  `kode_kec` varchar(4) NOT NULL DEFAULT '' COMMENT 'PD/PF - KODE KECAMATAN',
  `nama_kec` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - NAMA KECAMATAN',
  `kode_kel` varchar(4) NOT NULL DEFAULT '' COMMENT 'PD/PF - KODE DESA / KELURAHAN',
  `nama_kel` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - NAMA DESA / KELURAHAN',
  `rw` varchar(3) NOT NULL DEFAULT '' COMMENT 'PD/PF - RW',
  `rt` varchar(3) NOT NULL DEFAULT '' COMMENT 'PD/PF - RT',
  PRIMARY KEY (`nik`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_objek_self_assessment`
--

DROP TABLE IF EXISTS `appx_objek_self_assessment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_objek_self_assessment` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID DATA',
  `codex` varchar(40) NOT NULL DEFAULT '' COMMENT 'CODEX',
  `dt_entry` datetime NOT NULL DEFAULT '1900-01-01 00:00:01' COMMENT 'DATETIME DATA ENTRY',
  `d_entry` date NOT NULL DEFAULT '1900-01-01' COMMENT 'DATE DATA ENTRY',
  `datasrc` varchar(64) NOT NULL DEFAULT '' COMMENT 'DATA SOURCE',
  `nopel` int(11) NOT NULL DEFAULT '0' COMMENT 'NOMOR PELAYANAN',
  `context` varchar(255) NOT NULL DEFAULT '' COMMENT 'CONTEXT',
  `kode_a` varchar(16) NOT NULL DEFAULT '' COMMENT 'KODE A',
  `kode_b` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE B',
  `kode_c` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE C',
  `kode_d` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE D',
  `alias` varchar(255) NOT NULL DEFAULT '' COMMENT 'ALIAS KLASIFIKASI',
  `klasifikasi` varchar(255) NOT NULL DEFAULT '' COMMENT 'KLASIFIKASI',
  `npwpd` varchar(40) NOT NULL DEFAULT '' COMMENT 'NPWPD',
  `nama` varchar(40) NOT NULL DEFAULT '' COMMENT 'NAMA WAJIB PAJAK',
  `alamat` varchar(255) NOT NULL DEFAULT '' COMMENT 'ALAMAT WAJIB PAJAK',
  `objdescription` varchar(255) NOT NULL DEFAULT '' COMMENT 'DESKRIPSI OBJEK PAJAK',
  `objlocation` varchar(255) NOT NULL DEFAULT '' COMMENT 'LOKASI OBJEK PAJAK',
  `kecamatanidx` varchar(16) NOT NULL DEFAULT '' COMMENT 'ID KECAMATAN',
  `kecamatan` varchar(255) NOT NULL DEFAULT '' COMMENT 'NAMA KECAMATAN',
  `desaidx` varchar(16) NOT NULL DEFAULT '' COMMENT 'ID DESA/KELURAHAN',
  `desa` varchar(255) NOT NULL DEFAULT '' COMMENT 'NAMA DESA/KELURAHAN',
  `objomzet` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'OMZET/BULAN',
  `edopidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID OPERATOR DATA ENTRY',
  `nmedop` varchar(255) NOT NULL DEFAULT '' COMMENT 'NAMA OPERATOR DATA ENTRY',
  `txcidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID PENAGIH',
  `nmtxc` varchar(255) NOT NULL DEFAULT '' COMMENT 'NAMA PENAGIH',
  `status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS',
  `notes` text COMMENT 'KETERANGAN',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_obyek_retribusi_dpu`
--

DROP TABLE IF EXISTS `appx_obyek_retribusi_dpu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_obyek_retribusi_dpu` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dt_entry` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `d_entry` date NOT NULL DEFAULT '1970-01-01',
  `data_src` varchar(16) NOT NULL DEFAULT '',
  `no_pel` int(10) unsigned NOT NULL DEFAULT '0',
  `nik_wp` varchar(64) NOT NULL DEFAULT '',
  `ctx` varchar(32) NOT NULL DEFAULT '' COMMENT 'CONTEXT',
  `rekening` varchar(10) NOT NULL DEFAULT '',
  `jenis` varchar(32) NOT NULL DEFAULT '',
  `nama` varchar(255) NOT NULL DEFAULT '' COMMENT 'REKLAME: NAMA PEMASANG',
  `alamat` varchar(255) NOT NULL DEFAULT '',
  `klasifikasi` varchar(32) NOT NULL DEFAULT '',
  `lokasi` varchar(255) NOT NULL DEFAULT '',
  `rk_peruntukan` varchar(255) NOT NULL DEFAULT '' COMMENT 'NAMA OBYEK REKLAME',
  `kode_kec` int(10) unsigned NOT NULL DEFAULT '0',
  `id_kec` varchar(4) NOT NULL DEFAULT '',
  `id_kec_pbb` varchar(4) NOT NULL DEFAULT '',
  `nama_kec` varchar(64) NOT NULL DEFAULT '',
  `kode_kel` int(10) unsigned NOT NULL DEFAULT '0',
  `id_kel` varchar(4) NOT NULL DEFAULT '',
  `nama_kel` varchar(64) NOT NULL DEFAULT '',
  `rw` varchar(4) NOT NULL DEFAULT '',
  `rt` varchar(4) NOT NULL DEFAULT '',
  `rk_panjang` decimal(5,2) NOT NULL DEFAULT '0.00',
  `rk_lebar` decimal(5,2) NOT NULL DEFAULT '0.00',
  `rk_luas` decimal(5,2) NOT NULL DEFAULT '0.00',
  `rk_jumlah` int(10) unsigned NOT NULL DEFAULT '0',
  `rk_tarif_pct` decimal(4,2) NOT NULL DEFAULT '0.00',
  `rk_tarif` decimal(16,2) NOT NULL DEFAULT '0.00',
  `rk_estimasi` decimal(16,2) NOT NULL DEFAULT '0.00',
  `jml_penetapan` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'JUMLAH PENETAPAN - REKLAME/AIR TANAH',
  `tgl_penetapan` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL PENETAPAN',
  `rk_start_date` date NOT NULL DEFAULT '1970-01-01',
  `rk_end_date` date NOT NULL DEFAULT '1970-01-01',
  `rk_diff_thn` int(10) unsigned NOT NULL DEFAULT '0',
  `rk_diff_bln` int(10) unsigned NOT NULL DEFAULT '0',
  `rk_diff_mgg` int(10) unsigned NOT NULL DEFAULT '0',
  `rk_diff_hari` int(10) unsigned NOT NULL DEFAULT '0',
  `rk_sig` int(10) unsigned NOT NULL DEFAULT '0',
  `petugas` int(10) unsigned NOT NULL DEFAULT '0',
  `id_op` int(10) unsigned NOT NULL DEFAULT '0',
  `stat_dok` int(10) unsigned NOT NULL DEFAULT '1',
  `stat_active` int(10) unsigned NOT NULL DEFAULT '1',
  `note_deactive` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_pengendalian_bphtb_core`
--

DROP TABLE IF EXISTS `appx_pengendalian_bphtb_core`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_pengendalian_bphtb_core` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PRIMARY KEY + ID',
  `origin_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'DATA ID REF appx_temporary_sspd_bphtb_backup.idx',
  `opidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'OBJEK PAJAK ID REF appx_obyek_pajak.idx',
  `th_pajak` varchar(4) NOT NULL DEFAULT '' COMMENT 'TAHUN PAJAK - SUBSTRING TAHUN DARI TGL. VERIFIKASI',
  `d_pel` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TANGGAL PELAYANAN - appx_register_raw.d_reg',
  `no_pel` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR PELAYANAN',
  `d_verified` date NOT NULL DEFAULT '1970-01-01' COMMENT 'DATE VERIFIKASI - appx_temporary_sspd_bphtb_backup.d_entry',
  `nik_wp` varchar(64) NOT NULL DEFAULT '' COMMENT 'NIK WAJIB PAJAK',
  `nama_wp` varchar(255) NOT NULL DEFAULT '' COMMENT 'DP - NAMA',
  `alamat` varchar(255) NOT NULL DEFAULT '' COMMENT 'DP - ALAMAT',
  `nama_notaris` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA NOTARIS',
  `jenis_trx` varchar(2) NOT NULL DEFAULT '' COMMENT 'JENIS PEROLEHAN / TRANSAKSI',
  `jenis_trx_string` varchar(32) NOT NULL DEFAULT '' COMMENT 'JENIS PEROLEHAN / TRANSAKSI',
  `aphb` varchar(8) NOT NULL DEFAULT '' COMMENT 'REMARK APHB',
  `aphb_a` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'APHB A',
  `aphb_b` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'APHB B',
  `sppt_nop` varchar(32) NOT NULL DEFAULT '' COMMENT 'NOP PADA SPPT',
  `sppt_nama_wp` varchar(64) NOT NULL DEFAULT '',
  `sppt_alamat_wp` varchar(255) NOT NULL DEFAULT '',
  `sppt_kelurahan_wp` varchar(64) NOT NULL DEFAULT '',
  `sppt_kecamatan_wp` varchar(64) NOT NULL DEFAULT '',
  `sppt_thn_pajak` varchar(4) NOT NULL DEFAULT '' COMMENT 'SPPT - TAHUN PAJAK',
  `sppt_lt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - LUAS TANAH/BUMI',
  `sppt_ntm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP TANAH/BUMI M2',
  `sppt_nt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP TANAH/BUMI',
  `sppt_lb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - LUAS BANGUNAN',
  `sppt_nbm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP BANGUNAN M2',
  `sppt_nb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP BANGUNAN',
  `sppt_njop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP BUMI+BANGUNAN',
  `sspd_lt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - LUAS TANAH/BUMI',
  `sspd_ntm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NILAI TANAH/BUMI M2',
  `sspd_nt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NJOP TANAH/BUMI',
  `sspd_lb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - LUAS BANGUNAN',
  `sspd_nbm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NILAI BANGUNAN M2',
  `sspd_nb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NJOP BANGUNAN',
  `sspd_njop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NJOP TANAH/BUMI + BANGUNAN',
  `sspd_trx` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - TRANSAKSI',
  `sspd_npop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NPOP',
  `sspd_npoptkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NPOPTKP',
  `sspd_npopkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NPOPKP',
  `sspd_bphtb_ht` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - BPHTB TERHUTANG',
  `sspd_bphtb_hb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - BPHTB HARUS DIBAYAR',
  `veri_lt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - LUAS TANAH-BUMI',
  `veri_ntm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP TANAH-BUMI/M2',
  `veri_nt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP TANAH-BUMI',
  `veri_lb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - LUAS BANGUNAN',
  `veri_nbm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP BANGUNAN/M2',
  `veri_nb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP BANGUNAN',
  `veri_njop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP TANAH-BUMI + BANGUNAN',
  `veri_trx` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - HARGA TRANSAKSI',
  `veri_npop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NPOP',
  `veri_npoptkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NPOPTKP',
  `veri_npopkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NPOPKP',
  `veri_bphtb_ht` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - BPHTB TERHUTANG',
  `veri_bphtb_hb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - BPHTB YANG HARUS DIBAYAR',
  `veri_bphtb_kb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - BPHTB KURANG BAYAR',
  `veri_kurang_setor` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'VERIFIKATOR - KURANG SETOR',
  `veri_lebih_setor` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'VERIFIKATOR - LEBIH SETOR',
  `td_sspd` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TARGET DATE SSPD - appx_obyek_pajak.d_entry',
  `jt_sspd` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TGL. JATUH TEMPO PEMBAYARAN SSPD',
  `sd_sspd` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TGL. MULAI PENGHITUNGAN DENDA KETERLAMBATAN PEMBAYARAN SSPD',
  `tn_sspd` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'TARGET NOMINAL SSPD - appx_temporary_sspd_bphtb_backup.sspd_bphtb_hb',
  `rd_sspd` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TGL. REALISASI/BAYAR SSPD',
  `rn_sspd` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMINAL REALISASI/BAYAR SSPD',
  `bku_sspd` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR BKU PEMBAYARAN SSPD',
  `def_sspd` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'DEFISIT/KEKURANGAN REALISASI/BAYAR SSPD',
  `sur_sspd` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SURPLUS/KELEBIHAN REALISASI/BAYAR SSPD',
  `bln_denda_sspd` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'JUMLAH BULAN KETERLAMBATAN',
  `fb_sspd` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'FAKTOR BASIS PENGHITUNGAN DENDA KETERLAMBATAN',
  `fd_sspd` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT 'FAKTOR PENGALI PENGHITUNGAN DENDA KETERLAMBATAN - DEF. 0.02',
  `nd_sspd` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'JUMLAH DENDA HINGGA TGL. TERAKHIR PROSES DATA',
  `kh_skpdkb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR KOHIR SKPDKB',
  `td_skpdkb` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TARGET DATE SKPDKB - appx_temporary_sspd_bphtb_backup.d_entry',
  `jt_skpdkb` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TGL. JATUH TEMPO PEMBAYARAN SKPDKB',
  `sd_skpdkb` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TGL. MULAI PENGHITUNGAN DENDA KETERLAMBATAN PEMBAYARAN SKPDKB',
  `tn_skpdkb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'TARGET NOMINAL SKPDKB - appx_temporary_sspd_bphtb_backup.veri_bphtb_kb',
  `rd_skpdkb` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TGL. REALISASI/BAYAR SKPDKB',
  `rn_skpdkb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMINAL REALISASI/BAYAR SKPDKB',
  `bku_skpdkb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR BKU PEMBAYARAN SKPDKB',
  `def_skpdkb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'DEFISIT/KEKURANGAN REALISASI/BAYAR SKPDKB',
  `sur_skpdkb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SURPLUS/KELEBIHAN REALISASI/BAYAR SKPDKB',
  `bln_denda_skpdkb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'JUMLAH BULAN KETERLAMBATAN',
  `fb_skpdkb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'FAKTOR BASIS PENGHITUNGAN DENDA KETERLAMBATAN',
  `fd_skpdkb` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT 'FAKTOR PENGALI PENGHITUNGAN DENDA KETERLAMBATAN - DEF. 0.02',
  `nd_skpdkb` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'JUMLAH DENDA HINGGA TGL. TERAKHIR PROSES DATA',
  `kh_skpdkbt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR KOHIR SKPDKBT',
  `td_skpdkbt` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TARGET DATE SKPDKBT',
  `jt_skpdkbt` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TGL. JATUH TEMPO PEMBAYARAN SKPDKBT',
  `sd_skpdkbt` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TGL. MULAI PENGHITUNGAN DENDA KETERLAMBATAN PEMBAYARAN SKPDKBT',
  `tn_skpdkbt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'TARGET NOMINAL SKPDKBT - appx_temporary_sspd_bphtb_backup.veri_bphtb_kb',
  `rd_skpdkbt` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TGL. REALISASI/BAYAR SKPDKBT',
  `rn_skpdkbt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMINAL REALISASI/BAYAR SKPDKBT',
  `bku_skpdkbt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR BKU PEMBAYARAN SKPDKBT',
  `def_skpdkbt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'DEFISIT/KEKURANGAN REALISASI/BAYAR SKPDKBT',
  `sur_skpdkbt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SURPLUS/KELEBIHAN REALISASI/BAYAR SKPDKBT',
  `bln_denda_skpdkbt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'JUMLAH BULAN KETERLAMBATAN',
  `fb_skpdkbt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'FAKTOR BASIS PENGHITUNGAN DENDA KETERLAMBATAN',
  `fd_skpdkbt` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT 'FAKTOR PENGALI PENGHITUNGAN DENDA KETERLAMBATAN - DEF. 0.02',
  `nd_skpdkbt` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'JUMLAH DENDA HINGGA TGL. TERAKHIR PROSES DATA',
  `kh_stp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR KOHIR STP',
  `tt_stp` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TGL. DITERBITKANNYA STP',
  `jt_stp` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TGL. JATUH TEMPO PEMBAYARAN STP',
  `sd_stp` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TGL. MULAI PENGHITUNGAN DENDA KETERLAMBATAN PEMBAYARAN STP',
  `tn_stp` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'JUMLAH NOMINAL STP',
  `rd_stp` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TGL. REALISASI/BAYAR STP',
  `rn_stp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMINAL REALISASI/BAYAR STP',
  `bku_stp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR BKU PEMBAYARAN STP',
  `def_stp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'DEFISIT/KEKURANGAN REALISASI/BAYAR STP',
  `sur_stp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SURPLUS/KELEBIHAN REALISASI/BAYAR STP',
  `bln_denda_stp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'JUMLAH BULAN KETERLAMBATAN',
  `fb_stp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'FAKTOR BASIS PENGHITUNGAN DENDA KETERLAMBATAN',
  `fd_stp` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT 'FAKTOR PENGALI PENGHITUNGAN DENDA KETERLAMBATAN - DEF. 0.02',
  `nd_stp` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'JUMLAH DENDA HINGGA TGL. TERAKHIR PROSES DATA',
  `petugas` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'PETUGAS VERIFIKASI ID',
  `nama_verifikator` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA VERIFIKATOR',
  `id_op` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'OPERATOR PELAYANAN ID',
  `nama_operator` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA OPERATOR PELAYANAN',
  `no_sertifikat` varchar(64) NOT NULL DEFAULT '' COMMENT 'NOMOR SERTIFIKAT',
  `stat_dok` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS DOKUMEN',
  `stat_active` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS DATA AKTIF/NON-AKTIF',
  `note_deactive` varchar(64) NOT NULL DEFAULT '' COMMENT 'KETERANGAN DEAKTIVASI DATA',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=2844 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_register_pelayanan_staff`
--

DROP TABLE IF EXISTS `appx_register_pelayanan_staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_register_pelayanan_staff` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dt_entry` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  `d_entry` date NOT NULL DEFAULT '1970-01-01',
  `no_pel` int(11) NOT NULL DEFAULT '0',
  `ctx` varchar(255) NOT NULL DEFAULT '',
  `npwpd` varchar(32) NOT NULL DEFAULT '',
  `nama` varchar(255) NOT NULL DEFAULT '',
  `alamat` text NOT NULL,
  `objek_pajak` text NOT NULL,
  `lokasi` varchar(255) NOT NULL DEFAULT '',
  `notaris` varchar(255) NOT NULL DEFAULT '',
  `at_rk_estimasi_sspd` bigint(20) NOT NULL DEFAULT '0',
  `bphtb_verified` bigint(20) NOT NULL DEFAULT '0',
  `bphtb_skpdkb` bigint(20) NOT NULL DEFAULT '0',
  `notes` text NOT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_sptpd_self_assessment`
--

DROP TABLE IF EXISTS `appx_sptpd_self_assessment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_sptpd_self_assessment` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID DATA',
  `codex` varchar(40) NOT NULL DEFAULT '' COMMENT 'CODEX',
  `dt_entry` datetime NOT NULL DEFAULT '1900-01-01 00:00:01' COMMENT 'DATETIME DATA ENTRY',
  `d_entry` date NOT NULL DEFAULT '1900-01-01' COMMENT 'DATE DATA ENTRY',
  `d_sptpd` date NOT NULL DEFAULT '1900-01-01',
  `d_bayar` date NOT NULL DEFAULT '1900-01-01',
  `context` varchar(255) NOT NULL DEFAULT '' COMMENT 'CONTEXT',
  `kode_a` varchar(16) NOT NULL DEFAULT '' COMMENT 'KODE A',
  `kode_b` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE B',
  `kode_c` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE C',
  `kode_d` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE D',
  `alias` varchar(255) NOT NULL DEFAULT '' COMMENT 'ALIAS KLASIFIKASI',
  `klasifikasi` varchar(255) NOT NULL DEFAULT '' COMMENT 'KLASIFIKASI',
  `npwpd` varchar(40) NOT NULL DEFAULT '' COMMENT 'NPWPD',
  `nama` varchar(40) NOT NULL DEFAULT '' COMMENT 'NAMA WAJIB PAJAK',
  `alamat` varchar(255) NOT NULL DEFAULT '' COMMENT 'ALAMAT WAJIB PAJAK',
  `objdescription` varchar(255) NOT NULL DEFAULT '' COMMENT 'DESKRIPSI OBJEK PAJAK',
  `objlocation` varchar(255) NOT NULL DEFAULT '' COMMENT 'LOKASI OBJEK PAJAK',
  `kecamatanidx` varchar(16) NOT NULL DEFAULT '' COMMENT 'ID KECAMATAN',
  `kecamatan` varchar(255) NOT NULL DEFAULT '' COMMENT 'NAMA KECAMATAN',
  `desaidx` varchar(16) NOT NULL DEFAULT '' COMMENT 'ID DESA/KELURAHAN',
  `desa` varchar(255) NOT NULL DEFAULT '' COMMENT 'NAMA DESA/KELURAHAN',
  `objomzet` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'OMZET/BULAN',
  `pcttax` decimal(4,2) NOT NULL DEFAULT '0.00',
  `taxnominal` bigint(20) NOT NULL DEFAULT '0',
  `txcidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID PENAGIH',
  `nmtxc` varchar(255) NOT NULL DEFAULT '' COMMENT 'NAMA PENAGIH',
  `status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS',
  `notes` text COMMENT 'KETERANGAN',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_sspd_bphtb`
--

DROP TABLE IF EXISTS `appx_sspd_bphtb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_sspd_bphtb` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codex` varchar(40) NOT NULL DEFAULT '' COMMENT 'CODEX',
  `opidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'OBJEK PAJAK ID REF appx_obyek_pajak.idx',
  `th_pajak` varchar(4) NOT NULL DEFAULT '' COMMENT 'TAHUN PAJAK',
  `dt_entry` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT 'DATETIME INSERT DATA',
  `d_entry` date NOT NULL DEFAULT '1970-01-01' COMMENT 'DATE INSERT DATA',
  `dt_verified` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT 'DATE TIME VERIFIKASI',
  `d_verified` date NOT NULL DEFAULT '1970-01-01' COMMENT 'DATE VERIFIKASI',
  `data_src` varchar(16) NOT NULL DEFAULT '' COMMENT 'DATA SOURCE',
  `no_pel` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR PELAYANAN',
  `nik_wp` varchar(64) NOT NULL DEFAULT '' COMMENT 'NIK WAJIB PAJAK',
  `nama_wp` varchar(255) NOT NULL DEFAULT '' COMMENT 'DP - NAMA',
  `alamat` varchar(255) NOT NULL DEFAULT '' COMMENT 'DP - ALAMAT',
  `kabupaten` varchar(64) NOT NULL DEFAULT '' COMMENT 'DP - KABUPATEN',
  `kecamatan` varchar(64) NOT NULL DEFAULT '' COMMENT 'DP - KECAMATAN',
  `kelurahan` varchar(64) NOT NULL DEFAULT '' COMMENT 'DP - KELURAHAN',
  `rw` varchar(16) NOT NULL DEFAULT '' COMMENT 'DP - RW',
  `rt` varchar(16) NOT NULL DEFAULT '' COMMENT 'DP - RT',
  `kode_pos` varchar(32) NOT NULL DEFAULT '',
  `id_notaris` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOTARIS ID',
  `kode_notaris` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE NOTARIS',
  `nama_notaris` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA NOTARIS',
  `jenis_trx` varchar(2) NOT NULL DEFAULT '' COMMENT 'JENIS PEROLEHAN / TRANSAKSI',
  `sppt_nop` varchar(32) NOT NULL DEFAULT '' COMMENT 'NOP PADA SPPT',
  `sppt_nama_wp` varchar(64) NOT NULL DEFAULT '',
  `sppt_alamat_wp` varchar(255) NOT NULL DEFAULT '',
  `sppt_kelurahan_wp` varchar(64) NOT NULL DEFAULT '',
  `sppt_kecamatan_wp` varchar(64) NOT NULL DEFAULT '',
  `sppt_kabupaten_wp` varchar(64) NOT NULL DEFAULT '',
  `sppt_thn_pajak` varchar(4) NOT NULL DEFAULT '' COMMENT 'SPPT - TAHUN PAJAK',
  `sppt_lt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - LUAS TANAH/BUMI',
  `sppt_ntm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP TANAH/BUMI M2',
  `sppt_nt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP TANAH/BUMI',
  `sppt_lb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - LUAS BANGUNAN',
  `sppt_nbm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP BANGUNAN M2',
  `sppt_nb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP BANGUNAN',
  `sppt_njop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SPPT - NJOP BUMI+BANGUNAN',
  `sspd_lt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - LUAS TANAH/BUMI',
  `sspd_ntm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NILAI TANAH/BUMI M2',
  `sspd_nt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NJOP TANAH/BUMI',
  `sspd_lb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - LUAS BANGUNAN',
  `sspd_nbm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NILAI BANGUNAN M2',
  `sspd_nb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NJOP BANGUNAN',
  `sspd_njop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NJOP TANAH/BUMI + BANGUNAN',
  `sspd_trx` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - TRANSAKSI',
  `sspd_npop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NPOP',
  `sspd_npoptkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NPOPTKP',
  `sspd_npopkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - NPOPKP',
  `sspd_bphtb_ht` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - BPHTB TERHUTANG',
  `sspd_bphtb_hb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SSPD - BPHTB HARUS DIBAYAR',
  `veri_lt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - LUAS TANAH-BUMI',
  `veri_ntm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP TANAH-BUMI/M2',
  `veri_nt` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP TANAH-BUMI',
  `veri_lb` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - LUAS BANGUNAN',
  `veri_nbm` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP BANGUNAN/M2',
  `veri_nb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP BANGUNAN',
  `veri_njop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NJOP TANAH-BUMI + BANGUNAN',
  `veri_trx` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - HARGA TRANSAKSI',
  `veri_npop` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NPOP',
  `veri_npoptkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NPOPTKP',
  `veri_npopkp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - NPOPKP',
  `veri_bphtb_ht` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - BPHTB TERHUTANG',
  `veri_bphtb_hb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - BPHTB YANG HARUS DIBAYAR',
  `veri_bphtb_kb` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'VERIFIKATOR - BPHTB KURANG BAYAR',
  `veri_kurang_setor` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'VERIFIKATOR - KURANG SETOR',
  `veri_lebih_setor` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'VERIFIKATOR - LEBIH SETOR',
  `no_sertifikat` varchar(64) NOT NULL DEFAULT '' COMMENT 'NOMOR SERTIFIKAT',
  `petugas` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'PETUGAS VERIFIKASI ID',
  `id_op` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'OPERATOR PELAYANAN ID',
  `stat_dok` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS DOKUMEN',
  `stat_active` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS DATA AKTIF/NON-AKTIF',
  `note_deactive` varchar(64) NOT NULL DEFAULT '' COMMENT 'KETERANGAN DEAKTIVASI DATA',
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_subbidang`
--

DROP TABLE IF EXISTS `appx_subbidang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_subbidang` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `bidangid` int(10) unsigned DEFAULT NULL COMMENT 'ID BIDANG',
  `bidang` varchar(64) DEFAULT NULL COMMENT 'NAMA BIDANG',
  `subbidang` varchar(64) DEFAULT NULL COMMENT 'NAMA SUB-BIDANG',
  `module` varchar(255) DEFAULT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_sync_pembayaran_sppt`
--

DROP TABLE IF EXISTS `appx_sync_pembayaran_sppt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_sync_pembayaran_sppt` (
  `KD_PROPINSI` char(2) NOT NULL,
  `KD_DATI2` char(2) NOT NULL,
  `KD_KECAMATAN` char(3) NOT NULL,
  `KD_KELURAHAN` char(3) NOT NULL,
  `KD_BLOK` char(3) NOT NULL,
  `NO_URUT` char(4) NOT NULL,
  `KD_JNS_OP` char(1) NOT NULL,
  `THN_PAJAK_SPPT_SPPT` char(4) NOT NULL,
  `THN_PAJAK_SPPT_PEMBAYARAN` char(4) NOT NULL,
  `TGL_TERBIT_SPPT` date NOT NULL DEFAULT '1900-01-01',
  `PBB_YG_HARUS_DIBAYAR_SPPT` decimal(17,3) NOT NULL DEFAULT '0.000',
  `TGL_PEMBAYARAN_SPPT` date NOT NULL DEFAULT '1900-01-01',
  `TGL_REKAM_BYR_SPPT` date NOT NULL DEFAULT '1900-01-01',
  `DENDA_SPPT` decimal(17,3) NOT NULL DEFAULT '0.000',
  `JML_SPPT_YG_DIBAYAR` decimal(17,3) NOT NULL DEFAULT '0.000',
  `NM_WP_SPPT` varchar(255) NOT NULL DEFAULT '',
  `CODEX` varchar(40) NOT NULL DEFAULT '' COMMENT 'CODEX',
  `STATUS` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`KD_PROPINSI`,`KD_DATI2`,`KD_KECAMATAN`,`KD_KELURAHAN`,`KD_BLOK`,`NO_URUT`,`KD_JNS_OP`,`THN_PAJAK_SPPT_SPPT`,`THN_PAJAK_SPPT_PEMBAYARAN`,`TGL_TERBIT_SPPT`,`TGL_PEMBAYARAN_SPPT`),
  KEY `TGL_PEMBAYARAN_SPPT` (`TGL_PEMBAYARAN_SPPT`),
  KEY `KD_PROPINSI` (`KD_PROPINSI`),
  KEY `KD_DATI2` (`KD_DATI2`),
  KEY `KD_KECAMATAN` (`KD_KECAMATAN`),
  KEY `KD_KELURAHAN` (`KD_KELURAHAN`),
  KEY `KD_BLOK` (`KD_BLOK`),
  KEY `NO_URUT` (`NO_URUT`),
  KEY `KD_JNS_OP` (`KD_JNS_OP`),
  KEY `THN_PAJAK_SPPT_SPPT` (`THN_PAJAK_SPPT_SPPT`),
  KEY `THN_PAJAK_SPPT_PEMBAYARAN` (`THN_PAJAK_SPPT_PEMBAYARAN`),
  KEY `TGL_TERBIT_SPPT` (`TGL_TERBIT_SPPT`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_temp_rekening_pendapatan`
--

DROP TABLE IF EXISTS `appx_temp_rekening_pendapatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_temp_rekening_pendapatan` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `kode_a` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 1',
  `kode_b` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 2',
  `kode_c` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 3',
  `kode_d` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 4',
  `kode_e` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 5',
  `kode_f` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 6',
  `kode_g` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 7',
  `rekening` varchar(255) NOT NULL DEFAULT '' COMMENT 'REKENING',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=481 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_temp_rencana_pendapatan`
--

DROP TABLE IF EXISTS `appx_temp_rencana_pendapatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_temp_rencana_pendapatan` (
  `kode_a` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 1',
  `kode_b` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 2',
  `kode_c` char(1) NOT NULL COMMENT 'KODE REKENING SEGMENT 3',
  `kode_d` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 4',
  `kode_e` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 5',
  `kode_f` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 6',
  `kode_g` char(2) NOT NULL COMMENT 'KODE REKENING SEGMENT 7',
  `tahun` char(4) NOT NULL COMMENT 'TAHUN ANGGARAN',
  `rekening` varchar(255) NOT NULL DEFAULT '' COMMENT 'REKENING',
  `rencana_penetapan` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'RENCANA PENDAPATAN - PENETAPAN',
  `rencana_perubahan` decimal(17,3) NOT NULL DEFAULT '0.000' COMMENT 'RENCANA PENDAPATAN - PERUBAHAN',
  PRIMARY KEY (`kode_a`,`kode_b`,`kode_c`,`kode_d`,`kode_e`,`kode_f`,`kode_g`,`tahun`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_temporary_ibpbb`
--

DROP TABLE IF EXISTS `appx_temporary_ibpbb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_temporary_ibpbb` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `remark` varchar(8) NOT NULL DEFAULT '',
  `tahun` int(11) NOT NULL DEFAULT '0',
  `prev_sppt` int(11) NOT NULL DEFAULT '0',
  `prev_pokok` bigint(20) NOT NULL DEFAULT '0',
  `prev_denda` bigint(20) NOT NULL DEFAULT '0',
  `prev_jumlah` bigint(20) NOT NULL DEFAULT '0',
  `current_sppt` int(11) NOT NULL DEFAULT '0',
  `current_pokok` bigint(20) NOT NULL DEFAULT '0',
  `current_denda` bigint(20) NOT NULL DEFAULT '0',
  `current_jumlah` bigint(20) NOT NULL DEFAULT '0',
  `acc_sppt` int(11) NOT NULL DEFAULT '0',
  `acc_pokok` bigint(20) NOT NULL DEFAULT '0',
  `acc_denda` bigint(20) NOT NULL DEFAULT '0',
  `acc_jumlah` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_temporary_ipppbb`
--

DROP TABLE IF EXISTS `appx_temporary_ipppbb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_temporary_ipppbb` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `remark` varchar(8) NOT NULL DEFAULT '',
  `tahun` int(11) NOT NULL DEFAULT '0',
  `prev_sppt` int(11) NOT NULL DEFAULT '0',
  `prev_pokok` bigint(20) NOT NULL DEFAULT '0',
  `prev_denda` bigint(20) NOT NULL DEFAULT '0',
  `prev_jumlah` bigint(20) NOT NULL DEFAULT '0',
  `current_sppt` int(11) NOT NULL DEFAULT '0',
  `current_pokok` bigint(20) NOT NULL DEFAULT '0',
  `current_denda` bigint(20) NOT NULL DEFAULT '0',
  `current_jumlah` bigint(20) NOT NULL DEFAULT '0',
  `acc_sppt` int(11) NOT NULL DEFAULT '0',
  `acc_pokok` bigint(20) NOT NULL DEFAULT '0',
  `acc_denda` bigint(20) NOT NULL DEFAULT '0',
  `acc_jumlah` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_users`
--

DROP TABLE IF EXISTS `appx_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_users` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'USER ID',
  `origin` varchar(64) DEFAULT NULL COMMENT 'ORIGIN',
  `opdcode` varchar(64) DEFAULT NULL COMMENT 'KODE OPD',
  `bidangid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'BIDANG ID',
  `bidang` varchar(255) DEFAULT NULL COMMENT 'BIDANG',
  `subbidangid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SUB-BIDANG ID',
  `subbidang` varchar(255) DEFAULT NULL COMMENT 'SUPERVISOR NAME',
  `posisi` varchar(255) DEFAULT NULL COMMENT 'JABATAN/POSISI',
  `spvidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SUPERVISOR ID',
  `context` varchar(255) DEFAULT NULL COMMENT 'USER CONTEXT',
  `realname` varchar(255) DEFAULT NULL COMMENT 'REALNAME',
  `initial` varchar(8) DEFAULT NULL COMMENT 'INITIAL',
  `username` varchar(255) DEFAULT NULL COMMENT 'USERNAME',
  `wordpass` varchar(255) DEFAULT NULL COMMENT 'PASSWORD',
  `module` varchar(64) DEFAULT NULL COMMENT 'STANDARD MODULE',
  `add_module` varchar(255) DEFAULT NULL COMMENT 'MODULE TAMBAHAN',
  `verificator` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'REMARK BPHTB VERIFIKATOR',
  `collector` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'REMARK KOLEKTOR LAPANGAN',
  `spvstatus` int(10) NOT NULL DEFAULT '0',
  `status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS USER',
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_wp_badan`
--

DROP TABLE IF EXISTS `appx_wp_badan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_wp_badan` (
  `npwpd` varchar(32) NOT NULL DEFAULT '' COMMENT 'NPWPD',
  `npwp` varchar(32) NOT NULL DEFAULT '' COMMENT 'NPWP NASIONAL',
  `nama` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA BADAN USAHA / INSTITUSI',
  `alamat` varchar(255) NOT NULL DEFAULT '' COMMENT 'ALAMAT',
  `kabupaten` varchar(255) NOT NULL DEFAULT '',
  `provinsi` varchar(255) NOT NULL DEFAULT '',
  `nik_pj` varchar(16) NOT NULL DEFAULT '' COMMENT 'NIK PENANGGUNG JAWAB',
  `kategori_pj` varchar(4) NOT NULL DEFAULT '' COMMENT 'KATEGORI PENANGGUNGJAWAB BADAN - PD/PF',
  `nama_pj` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA PENANGGUNGJAWAB BADAN',
  `alamat_pj` varchar(255) NOT NULL DEFAULT '' COMMENT 'ALAMAT PENANGGUNGJAWAB BADAN',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS DATA',
  PRIMARY KEY (`npwpd`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_wp_base`
--

DROP TABLE IF EXISTS `appx_wp_base`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_wp_base` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID DATA',
  `kategori` varchar(4) NOT NULL DEFAULT '' COMMENT 'KATEGORI: PD / PF / BD',
  `origin` varchar(4) NOT NULL DEFAULT '' COMMENT 'NPWPD ORIGIN: NIK / IDX',
  `npwpd` varchar(32) NOT NULL DEFAULT '' COMMENT 'NPWPD',
  `nama` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA WAJIB PAJAK',
  `alamat` varchar(255) NOT NULL DEFAULT '' COMMENT 'ALAMAT WAJIB PAJAK',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS DATA',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=3844 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_wp_perorangan`
--

DROP TABLE IF EXISTS `appx_wp_perorangan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `appx_wp_perorangan` (
  `nik` varchar(16) NOT NULL COMMENT 'PD/PF - NIK',
  `kategori` varchar(4) NOT NULL DEFAULT '' COMMENT 'KATEGORI: PD / PF',
  `npwp` varchar(32) NOT NULL DEFAULT '' COMMENT 'PD/PF - NPWP NASIONAL',
  `npwpd` varchar(32) NOT NULL DEFAULT '' COMMENT 'PD/PF - NPWPD',
  `nama` varchar(64) NOT NULL DEFAULT '' COMMENT 'PD/PF - NAMA',
  `alamat` varchar(255) NOT NULL DEFAULT '' COMMENT 'PD/PF - ALAMAT',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS DATA',
  PRIMARY KEY (`nik`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dev_obyek_pajak_airtanah`
--

DROP TABLE IF EXISTS `dev_obyek_pajak_airtanah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `dev_obyek_pajak_airtanah` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'DATA ID',
  `dt_entry` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT 'DATETIME ENTRY DATA',
  `d_entry` date NOT NULL DEFAULT '1970-01-01' COMMENT 'DATE ENTRY DATA',
  `data_src` varchar(16) NOT NULL DEFAULT '' COMMENT 'SUMBER DATA ENTRY',
  `no_pel` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR PELAYANAN',
  `npwpd` varchar(64) NOT NULL DEFAULT '' COMMENT 'NPWPD',
  `ctx` varchar(32) NOT NULL DEFAULT '' COMMENT 'CONTEXT DATA',
  `rekening` varchar(10) NOT NULL DEFAULT '' COMMENT 'NOMOR REKENING PENDAPATAN',
  `subrekening` varchar(32) NOT NULL DEFAULT '' COMMENT 'NOMOR SUB-REKENING PENDAPATAN',
  `tenant` varchar(255) NOT NULL DEFAULT '' COMMENT 'NAMA WAJIB PAJAK',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT 'ALAMAT WAJIB PAJAK',
  `classification` varchar(32) NOT NULL DEFAULT '' COMMENT 'KLASIFIKASI OBYEK PAJAK',
  `location` varchar(255) NOT NULL DEFAULT '' COMMENT 'LOKASI OBYEK PAJAK',
  `obyek_pajak` varchar(255) NOT NULL DEFAULT '' COMMENT 'KONTEN REKLAME / PERUNTUKAN PEMAKAIAN',
  `kode_kec` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'KODE KECAMATAN - VERSI DPPKAD',
  `id_kec` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE KECAMATAN - VERSI CAPIL',
  `id_kec_pbb` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE KECAMATAN - VERSI SISMIOP',
  `nama_kec` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA KECAMATAN',
  `kode_kel` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'KODE DESA / KELURAHAN - VERSI DPPKAD',
  `id_kel` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE DESA / KELURAHAN - VERSI CAPIL',
  `nama_kel` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA DESA / KELURAHAN',
  `rw` varchar(4) NOT NULL DEFAULT '' COMMENT 'RW',
  `rt` varchar(4) NOT NULL DEFAULT '' COMMENT 'RT',
  `at_sig` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'REMARK SIG',
  `point_latlong` varchar(255) NOT NULL DEFAULT '' COMMENT 'POINT LATITUDE-LONGITUDE SIG',
  `photo` varchar(255) NOT NULL DEFAULT '' COMMENT 'PHOTO OBYEK PAJAK',
  `petugas` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID PETUGAS KOLEKTOR / PENAGIH',
  `id_op` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID OPERATOR ENTRY DATA',
  `stat_active` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS AKTIVASI DATA',
  `note_deactive` varchar(64) NOT NULL DEFAULT '' COMMENT 'CATATAN TERHADAP DEAKTIVASI OBYEK PAJAK',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=4451 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dev_skpd_official`
--

DROP TABLE IF EXISTS `dev_skpd_official`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `dev_skpd_official` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'DATA ID',
  `order_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR URUT / KOHIR',
  `order_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR URUT / SPESIFIK',
  `spopd_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR ID SPOPD',
  `meter_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR ID TEMP METERING - AT',
  `th_pajak` char(4) NOT NULL COMMENT 'TAHUN PAJAK',
  `dt_entry` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT 'DATETIME ENTRY DATA',
  `d_entry` date NOT NULL DEFAULT '1970-01-01' COMMENT 'DATE ENTRY DATA',
  `data_src` varchar(16) NOT NULL DEFAULT '' COMMENT 'SUMBER DATA ENTRY',
  `no_pel` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR PELAYANAN',
  `npwpd` varchar(64) NOT NULL DEFAULT '' COMMENT 'NPWPD',
  `ctx` varchar(32) NOT NULL DEFAULT '' COMMENT 'CONTEXT - REKLAME / AIR TANAH',
  `rekening` varchar(10) NOT NULL DEFAULT '' COMMENT 'NOMOR REKENING PENDAPATAN',
  `jenis` varchar(32) NOT NULL DEFAULT '' COMMENT 'NOMOR SUB-REKENING PENDAPATAN',
  `kode` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE INTERNAL TARIF REKLAME DPPKAD',
  `rk_a` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE SEGMEN A',
  `rk_b` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE SEGMEN B',
  `rk_c` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE SEGMEN C',
  `rk_d` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE SEGMEN D',
  `rk_e` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE SEGMEN E',
  `tenant` varchar(255) NOT NULL DEFAULT '' COMMENT 'NAMA PEMASANG / AGENSI / WP',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT 'ALAMAT PEMASANG / AGENSI / WP',
  `classification` varchar(32) NOT NULL DEFAULT '' COMMENT 'KLASIFIKASI OBYEK PAJAK',
  `location` varchar(255) NOT NULL DEFAULT '' COMMENT 'LOKASI OBYEK PAJAK',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT 'KONTEN REKLAME / PERUNTUKAN PEMAKAIAN',
  `kode_kec` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'KODE KECAMATAN - VERSI DPPKAD',
  `id_kec` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE KECAMATAN - VERSI CAPIL',
  `id_kec_pbb` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE KECAMATAN - VERSI SISMIOP',
  `nama_kec` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA KECAMATAN',
  `kode_kel` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'KODE DESA / KELURAHAN - VERSI DPPKAD',
  `id_kel` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE DESA / KELURAHAN - VERSI CAPIL',
  `nama_kel` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA DESA / KELURAHAN',
  `rw` varchar(4) NOT NULL DEFAULT '' COMMENT 'RW',
  `rt` varchar(4) NOT NULL DEFAULT '' COMMENT 'RT',
  `rk_panjang` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'PANJANG',
  `rk_lebar` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'LEBAR',
  `rk_luas` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'LUAS',
  `rk_jumlah` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'JUMLAH',
  `rk_tarif_pct` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT 'PERSENTASE TARIF',
  `rk_tarif` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'TARIF DASAR REKLAME',
  `meter_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'METER BULAN LALU',
  `meter_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'METER BULAN INI',
  `volume` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME PENGGUNAAN [B-A]',
  `tarif_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 1',
  `tarif_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 2',
  `tarif_c` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 3',
  `tarif_d` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 4',
  `tarif_e` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 5',
  `tarif_f` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 6',
  `volume_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 1',
  `volume_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 2',
  `volume_c` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 3',
  `volume_d` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 4',
  `volume_e` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 5',
  `volume_f` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 6',
  `subtotal_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 1',
  `subtotal_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 2',
  `subtotal_c` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 3',
  `subtotal_d` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 4',
  `subtotal_e` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 5',
  `subtotal_f` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 6',
  `estimasi` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'ESTIMASI PAJAK SPOPD',
  `penetapan` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'JUMLAH PENETAPAN - SKPD',
  `tgl_penetapan` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL PENETAPAN',
  `tgl_jatuhtempo` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL JATUH TEMPO',
  `tgl_penerimaan` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL PENERIMAAN',
  `penerimaan` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'JUMLAH PENERIMAAN',
  `no_bku` varchar(32) NOT NULL DEFAULT '' COMMENT 'NOMOR BKU PENERIMAAN',
  `start_date` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TANGGAL MULAI MASA PAJAK',
  `end_date` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TANGGAL BERAKHIR MASA PAJAK',
  `rk_diff_thn` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'DIFF TAHUN',
  `rk_diff_bln` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'DIFF BULAN',
  `rk_diff_mgg` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'DIFF MINGGU',
  `rk_diff_hari` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'DIFF HARI',
  `rk_sig` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'REMARK SIG',
  `point_latlong` varchar(255) NOT NULL DEFAULT '' COMMENT 'POINT LATITUDE-LONGITUDE SIG',
  `photo` varchar(255) NOT NULL DEFAULT '' COMMENT 'PHOTO OBYEK PAJAK',
  `petugas` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID PETUGAS KOLEKTOR / PENAGIH',
  `id_op` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID OPERATOR ENTRY DATA',
  `stat_dok` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS DOKUMEN',
  `stat_active` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS AKTIVASI DATA',
  `note_deactive` varchar(64) NOT NULL DEFAULT '' COMMENT 'CATATAN TERHADAP DEAKTIVASI OBYEK PAJAK',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=3316 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dev_skpd_official_pdt`
--

DROP TABLE IF EXISTS `dev_skpd_official_pdt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `dev_skpd_official_pdt` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'DATA ID',
  `order_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR URUT / KOHIR',
  `order_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR URUT / SPESIFIK',
  `spopd_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR ID SPOPD',
  `meter_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR ID TEMP METERING - AT',
  `th_pajak` char(4) NOT NULL COMMENT 'TAHUN PAJAK',
  `dt_entry` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT 'DATETIME ENTRY DATA',
  `d_entry` date NOT NULL DEFAULT '1970-01-01' COMMENT 'DATE ENTRY DATA',
  `data_src` varchar(16) NOT NULL DEFAULT '' COMMENT 'SUMBER DATA ENTRY',
  `no_pel` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR PELAYANAN',
  `npwpd` varchar(64) NOT NULL DEFAULT '' COMMENT 'NPWPD',
  `ctx` varchar(32) NOT NULL DEFAULT '' COMMENT 'CONTEXT - REKLAME / AIR TANAH',
  `rekening` varchar(10) NOT NULL DEFAULT '' COMMENT 'NOMOR REKENING PENDAPATAN',
  `jenis` varchar(32) NOT NULL DEFAULT '' COMMENT 'NOMOR SUB-REKENING PENDAPATAN',
  `kode` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE INTERNAL TARIF REKLAME DPPKAD',
  `rk_a` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE SEGMEN A',
  `rk_b` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE SEGMEN B',
  `rk_c` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE SEGMEN C',
  `rk_d` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE SEGMEN D',
  `rk_e` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE SEGMEN E',
  `tenant` varchar(255) NOT NULL DEFAULT '' COMMENT 'NAMA PEMASANG / AGENSI / WP',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT 'ALAMAT PEMASANG / AGENSI / WP',
  `classification` varchar(32) NOT NULL DEFAULT '' COMMENT 'KLASIFIKASI OBYEK PAJAK',
  `location` varchar(255) NOT NULL DEFAULT '' COMMENT 'LOKASI OBYEK PAJAK',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT 'KONTEN REKLAME / PERUNTUKAN PEMAKAIAN',
  `kode_kec` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'KODE KECAMATAN - VERSI DPPKAD',
  `id_kec` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE KECAMATAN - VERSI CAPIL',
  `id_kec_pbb` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE KECAMATAN - VERSI SISMIOP',
  `nama_kec` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA KECAMATAN',
  `kode_kel` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'KODE DESA / KELURAHAN - VERSI DPPKAD',
  `id_kel` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE DESA / KELURAHAN - VERSI CAPIL',
  `nama_kel` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA DESA / KELURAHAN',
  `rw` varchar(4) NOT NULL DEFAULT '' COMMENT 'RW',
  `rt` varchar(4) NOT NULL DEFAULT '' COMMENT 'RT',
  `rk_panjang` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'PANJANG',
  `rk_lebar` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'LEBAR',
  `rk_luas` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'LUAS',
  `rk_jumlah` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'JUMLAH',
  `rk_tarif_pct` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT 'PERSENTASE TARIF',
  `rk_tarif` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'TARIF DASAR REKLAME',
  `meter_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'METER BULAN LALU',
  `meter_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'METER BULAN INI',
  `volume` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME PENGGUNAAN [B-A]',
  `tarif_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 1',
  `tarif_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 2',
  `tarif_c` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 3',
  `tarif_d` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 4',
  `tarif_e` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 5',
  `tarif_f` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 6',
  `volume_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 1',
  `volume_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 2',
  `volume_c` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 3',
  `volume_d` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 4',
  `volume_e` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 5',
  `volume_f` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 6',
  `subtotal_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 1',
  `subtotal_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 2',
  `subtotal_c` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 3',
  `subtotal_d` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 4',
  `subtotal_e` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 5',
  `subtotal_f` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 6',
  `estimasi` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'ESTIMASI PAJAK SPOPD',
  `penetapan` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'JUMLAH PENETAPAN - SKPD',
  `tgl_penetapan` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL PENETAPAN',
  `tgl_jatuhtempo` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL JATUH TEMPO',
  `tgl_penerimaan` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL PENERIMAAN',
  `penerimaan` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'JUMLAH PENERIMAAN',
  `no_bku` varchar(32) NOT NULL DEFAULT '' COMMENT 'NOMOR BKU PENERIMAAN',
  `start_date` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TANGGAL MULAI MASA PAJAK',
  `end_date` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TANGGAL BERAKHIR MASA PAJAK',
  `tgl_cutoff` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL CUT-OFF',
  `jmlhari` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'JUMLAH HARI TOTAL',
  `jmlharisdcutoff` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'JUMLAH HARI S/D TANGGAL CUT-OFF',
  `pajakperhari` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'JUMLAH TOTAL PENERIMAAN / JUMLAH HARI',
  `pajaksdcutoff` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'PENDAPATAN S/D TANGGAL CUT-OFF',
  `pajakstcutoff` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'PENDAPATAN DITERIMA DIMUKA SETELAH TANGGAL CUT-OFF S/D TANGGAL END',
  `rk_diff_thn` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'DIFF TAHUN',
  `rk_diff_bln` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'DIFF BULAN',
  `rk_diff_mgg` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'DIFF MINGGU',
  `rk_diff_hari` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'DIFF HARI',
  `rk_sig` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'REMARK SIG',
  `point_latlong` varchar(255) NOT NULL DEFAULT '' COMMENT 'POINT LATITUDE-LONGITUDE SIG',
  `photo` varchar(255) NOT NULL DEFAULT '' COMMENT 'PHOTO OBYEK PAJAK',
  `petugas` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID PETUGAS KOLEKTOR / PENAGIH',
  `id_op` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID OPERATOR ENTRY DATA',
  `stat_dok` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS DOKUMEN',
  `stat_active` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS AKTIVASI DATA',
  `note_deactive` varchar(64) NOT NULL DEFAULT '' COMMENT 'CATATAN TERHADAP DEAKTIVASI OBYEK PAJAK',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=471 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dummy_appx_buku_kas_umum`
--

DROP TABLE IF EXISTS `dummy_appx_buku_kas_umum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `dummy_appx_buku_kas_umum` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID DATA',
  `codex` varchar(40) NOT NULL DEFAULT '' COMMENT 'CODEX',
  `th_pajak` varchar(4) NOT NULL DEFAULT '' COMMENT 'TAHUN PAJAK',
  `no_bku` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NO. BKU',
  `subnobku_a` int(11) NOT NULL DEFAULT '0' COMMENT 'SPECIFIC BKU - LEVEL 1',
  `subnobku_b` int(11) NOT NULL DEFAULT '0' COMMENT 'SPECIFIC BKU - LEVEL 2',
  `origin_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID DATA ORIGIN',
  `opidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'REF. OBJEK PAJAK ENTRY',
  `dt_entry` datetime NOT NULL DEFAULT '1900-01-01 00:00:00' COMMENT 'DATETIME INSERT DATA',
  `d_entry` date NOT NULL DEFAULT '1900-01-01' COMMENT 'DATE INSERT DATA',
  `context` varchar(64) NOT NULL DEFAULT '' COMMENT 'CONTEXT',
  `subcontext_a` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 1',
  `subcontext_b` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 2',
  `subcontext_c` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 3',
  `kode_rekening` varchar(64) NOT NULL DEFAULT '' COMMENT 'KODE REKENING - LEVEL 0',
  `subkoderekening_a` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 1',
  `subkoderekening_b` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 2',
  `subkoderekening_c` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 3',
  `dk` varchar(8) NOT NULL DEFAULT '' COMMENT 'DEBET/KREDIT',
  `debet` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'DEBET',
  `kredit` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'KREDIT',
  `cnc` varchar(8) NOT NULL DEFAULT '' COMMENT 'TUNAI/BANK',
  `cash` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'TUNAI',
  `bank` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'BANK',
  `nominal` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMINAL TRANSAKSI',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS DATA',
  `notes` varchar(255) NOT NULL DEFAULT '' COMMENT 'KETERANGAN/URAIAN',
  PRIMARY KEY (`idx`),
  KEY `codex` (`codex`),
  KEY `no_bku` (`no_bku`),
  KEY `subnobku_a` (`subnobku_a`),
  KEY `subnobku_b` (`subnobku_b`),
  KEY `origin_idx` (`origin_idx`),
  KEY `opidx` (`opidx`),
  KEY `dt_entry` (`dt_entry`),
  KEY `d_entry` (`d_entry`),
  KEY `context` (`context`),
  KEY `subcontext_a` (`subcontext_a`),
  KEY `subcontext_b` (`subcontext_b`),
  KEY `subcontext_c` (`subcontext_c`),
  KEY `kode_rekening` (`kode_rekening`),
  KEY `subkoderekening_a` (`subkoderekening_a`),
  KEY `subkoderekening_b` (`subkoderekening_b`),
  KEY `subkoderekening_c` (`subkoderekening_c`)
) ENGINE=MyISAM AUTO_INCREMENT=677723 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mapatda_users`
--

DROP TABLE IF EXISTS `mapatda_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `mapatda_users` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'USER ID',
  `skpd` varchar(64) NOT NULL DEFAULT '' COMMENT 'SKPD USER',
  `realname` varchar(64) NOT NULL DEFAULT '' COMMENT 'REALNAME',
  `initial` varchar(3) NOT NULL DEFAULT '' COMMENT 'INITIAL',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT 'USERNAME',
  `wordpass` varchar(40) NOT NULL DEFAULT '' COMMENT 'PASSWORD',
  `module` varchar(64) NOT NULL DEFAULT '' COMMENT 'STANDARD MODULE',
  `level` varchar(64) NOT NULL DEFAULT '' COMMENT 'LEVEL AKSES STANDARD MODULE',
  `add_module` varchar(64) NOT NULL DEFAULT '' COMMENT 'MODULE TAMBAHAN',
  `add_level` varchar(64) NOT NULL DEFAULT '' COMMENT 'LEVEL AKSES MODULE TAMBAHAN',
  `printer_ip` varchar(16) NOT NULL DEFAULT '' COMMENT 'PRINTER IP ADDRESS',
  `printer` varchar(32) NOT NULL DEFAULT '' COMMENT 'LPD PRINTER SHARE NAME',
  `uitheme` varchar(32) NOT NULL DEFAULT '' COMMENT 'UI/UX THEME',
  `status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS USER',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notaris_ppat_users`
--

DROP TABLE IF EXISTS `notaris_ppat_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `notaris_ppat_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'WEB SYSTEM ID',
  `docidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'CORE SYSTEM ID',
  `kode` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'CORE SYSTEM ID',
  `notaris_ppat` varchar(64) NOT NULL DEFAULT '' COMMENT 'NOTARIS/PPAT',
  `realname` varchar(255) NOT NULL DEFAULT '' COMMENT 'REALNAME',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT 'USERNAME',
  `wordpass` varchar(128) NOT NULL DEFAULT '' COMMENT 'PASSWORD',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT 'ALAMAT NOTARIS',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT 'PHONE NOTARIS',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT 'E-MAIL NOTARIS',
  `status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS USER',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_desa`
--

DROP TABLE IF EXISTS `ref_desa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `ref_desa` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID DATA',
  `kode` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE DPPKAD',
  `kode_pbb` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE PBB/SISMIOP',
  `kode_capil` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE DISDUKCAPIL',
  `idx_kec` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID KECAMATAN',
  `kode_kec` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE DPPKAD KECAMATAN',
  `kode_pbb_kec` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE PBB/SISMIOP KECAMATAN',
  `kode_capil_kec` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE DISDUKCAPIL KECAMATAN',
  `kecamatan` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA KECAMATAN',
  `desa` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA DESA/KELURAHAN',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=291 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_jenis_pekerjaan`
--

DROP TABLE IF EXISTS `ref_jenis_pekerjaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `ref_jenis_pekerjaan` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID DATA',
  `versi` varchar(16) NOT NULL DEFAULT '' COMMENT 'VERSI DATA: SIAK/SISMIOP/DPPKAD',
  `kode` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE',
  `pekerjaan` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA/JENIS PEKERJAAN',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='REFERENSI JENIS PEKERJAAN';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_jenis_selfassessment`
--

DROP TABLE IF EXISTS `ref_jenis_selfassessment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `ref_jenis_selfassessment` (
  `kode_a` varchar(16) NOT NULL DEFAULT '',
  `kode_b` varchar(4) NOT NULL DEFAULT '',
  `kode_c` varchar(4) NOT NULL DEFAULT '',
  `kode_d` varchar(4) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `klasifikasi` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`kode_a`,`kode_b`,`kode_c`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_kecamatan`
--

DROP TABLE IF EXISTS `ref_kecamatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `ref_kecamatan` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID DATA',
  `kode` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE DPPKAD',
  `kode_pbb` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE PBB/SISMIOP',
  `kode_capil` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE DISDUKCAPIL',
  `kecamatan` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA KECAMATAN',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_notaris`
--

DROP TABLE IF EXISTS `ref_notaris`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `ref_notaris` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID DATA',
  `kode` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'KODE DPPKAD NOTARIS/PPAT',
  `jenis` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'NOTARIS/PPAT ATAU PPAT',
  `nama` varchar(255) NOT NULL DEFAULT '' COMMENT 'NAMA NOTARIS/PPAT',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'STATUS DATA',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_penetapan_harga`
--

DROP TABLE IF EXISTS `ref_penetapan_harga`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `ref_penetapan_harga` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System ID',
  `origin_idx` int(10) unsigned NOT NULL DEFAULT '0',
  `d_verified` date NOT NULL DEFAULT '1900-01-01',
  `nop` varchar(32) NOT NULL DEFAULT '',
  `kodekec` varchar(8) NOT NULL DEFAULT '',
  `namakec` varchar(64) NOT NULL DEFAULT '',
  `kodekel` varchar(8) NOT NULL DEFAULT '',
  `namakel` varchar(64) NOT NULL DEFAULT '',
  `kodeblok` varchar(64) NOT NULL DEFAULT '',
  `harga` varchar(64) NOT NULL DEFAULT '',
  `notaris` varchar(64) NOT NULL DEFAULT '',
  `validator` varchar(64) NOT NULL DEFAULT '',
  `status` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=8019 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_penetapan_harga_history`
--

DROP TABLE IF EXISTS `ref_penetapan_harga_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `ref_penetapan_harga_history` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'System ID',
  `origin_idx` int(10) unsigned NOT NULL DEFAULT '0',
  `d_verified` date NOT NULL DEFAULT '1900-01-01',
  `nop` varchar(32) NOT NULL DEFAULT '',
  `kodekec` varchar(8) NOT NULL DEFAULT '',
  `namakec` varchar(64) NOT NULL DEFAULT '',
  `kodekel` varchar(8) NOT NULL DEFAULT '',
  `namakel` varchar(64) NOT NULL DEFAULT '',
  `kodeblok` varchar(64) NOT NULL DEFAULT '',
  `harga` varchar(64) NOT NULL DEFAULT '',
  `notaris` varchar(64) NOT NULL DEFAULT '',
  `validator` varchar(64) NOT NULL DEFAULT '',
  `status` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=2730 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_tarif_airtanah`
--

DROP TABLE IF EXISTS `ref_tarif_airtanah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `ref_tarif_airtanah` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID DATA',
  `rekening` varchar(16) NOT NULL DEFAULT '' COMMENT 'KODE REKENING PENDAPATAN',
  `peruntukan` varchar(64) NOT NULL DEFAULT '' COMMENT 'KATEGORI PERUNTUKAN/PENGGUNAAN',
  `vol_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0 - 100',
  `vol_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '101 - 500',
  `vol_c` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '501 - 1000',
  `vol_d` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '1001 - 2500',
  `vol_e` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '2501 - 5000',
  `vol_f` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '> 5000',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_tarif_reklame`
--

DROP TABLE IF EXISTS `ref_tarif_reklame`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `ref_tarif_reklame` (
  `idx` int(10) unsigned NOT NULL COMMENT 'IDX - PRIMARY KEY',
  `rek` varchar(32) NOT NULL DEFAULT '' COMMENT 'KODE REKENING PENDAPATAN',
  `rek_a` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE DPPKAD - SEGMEN 1',
  `rek_b` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE DPPKAD - SEGMEN 2',
  `kode` varchar(64) NOT NULL DEFAULT '' COMMENT 'KODE JENIS REKLAME',
  `note_kode` varchar(64) NOT NULL DEFAULT '' COMMENT 'KETERANGAN JENIS REKLAME',
  `kode_a` varchar(16) NOT NULL DEFAULT '' COMMENT 'KODE DURASI MASA PAJAK',
  `note_kode_a` varchar(16) NOT NULL DEFAULT '' COMMENT 'KETERANGAN DURASI MASA PAJAK',
  `kode_b` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE LOKASI PEMASANGAN REKLAME',
  `note_kode_b` varchar(32) NOT NULL DEFAULT '' COMMENT 'KETERANGAN LOKASI PEMASANGAN REKLAME',
  `kode_c` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT 'JUMLAH SISI OBYEK REKLAME',
  `kode_d` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE KATEGORI REKLAME - BIASA/KHUSUS',
  `note_kode_d` varchar(32) NOT NULL DEFAULT '' COMMENT 'KATEGORI REKLAME - BIASA/KHUSUS',
  `pct` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT 'PCT TARIF PAJAK',
  `tarif` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'TARIF PAJAK',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS DATA',
  PRIMARY KEY (`idx`),
  UNIQUE KEY `idx` (`idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_tarif_reklame_duplikat`
--

DROP TABLE IF EXISTS `ref_tarif_reklame_duplikat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `ref_tarif_reklame_duplikat` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `rek` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'KODE REKENING PENDAPATAN',
  `durasi` decimal(5,3) NOT NULL DEFAULT '0.000',
  `lokasi` decimal(5,3) NOT NULL DEFAULT '0.000',
  `kategori` decimal(5,3) NOT NULL DEFAULT '0.000',
  `faktor` decimal(5,3) NOT NULL DEFAULT '0.000',
  `perda` int(11) NOT NULL DEFAULT '0',
  `pctfaktor` decimal(5,3) NOT NULL DEFAULT '0.000',
  `rek_a` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'KODE DPPKAD - SEGMEN 1',
  `rek_b` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'KODE DPPKAD - SEGMEN 2',
  `kode` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'KODE JENIS REKLAME',
  `note_kode` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'KETERANGAN JENIS REKLAME',
  `kode_a` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'KODE DURASI MASA PAJAK',
  `note_kode_a` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'KETERANGAN DURASI MASA PAJAK',
  `kode_b` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'KODE LOKASI PEMASANGAN REKLAME',
  `note_kode_b` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'KETERANGAN LOKASI PEMASANGAN REKLAME',
  `kode_c` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT 'JUMLAH SISI OBYEK REKLAME',
  `kode_d` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'KODE KATEGORI REKLAME - BIASA/KHUSUS',
  `note_kode_d` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'KATEGORI REKLAME - BIASA/KHUSUS',
  `pct` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT 'PCT TARIF PAJAK',
  `tarif` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'TARIF PAJAK',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS DATA',
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=292 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_tarif_reklame_jenis`
--

DROP TABLE IF EXISTS `ref_tarif_reklame_jenis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `ref_tarif_reklame_jenis` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID DATA',
  `kode` varchar(16) NOT NULL DEFAULT '',
  `keterangan` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='REFERENSI TAMBAHAN / KODE TRANSLATOR';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_transaksi_bphtb`
--

DROP TABLE IF EXISTS `ref_transaksi_bphtb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `ref_transaksi_bphtb` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID DATA',
  `syscode` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'SYSTEM CODE',
  `kode` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE',
  `transaksi` varchar(255) NOT NULL DEFAULT '' COMMENT 'JENIS TRANSAKSI HTB',
  `aliastrx` varchar(64) NOT NULL DEFAULT '' COMMENT 'ALIAS TRANSAKSI',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS DATA',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `simulasi_appx_buku_kas_umum`
--

DROP TABLE IF EXISTS `simulasi_appx_buku_kas_umum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `simulasi_appx_buku_kas_umum` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID DATA',
  `no_bku` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NO. BKU',
  `origin_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID DATA ORIGIN',
  `opidx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'REF. OBJEK PAJAK ENTRY',
  `dt_entry` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT 'DATETIME INSERT DATA',
  `d_entry` date NOT NULL DEFAULT '1970-01-01' COMMENT 'DATE INSERT DATA',
  `context` varchar(64) NOT NULL DEFAULT '' COMMENT 'CONTEXT',
  `subcontext_a` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 1',
  `subcontext_b` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 2',
  `subcontext_c` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-CONTEXT LEVEL 3',
  `kode_rekening` varchar(64) NOT NULL DEFAULT '' COMMENT 'KODE REKENING - LEVEL 0',
  `subkoderekening_a` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 1',
  `subkoderekening_b` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 2',
  `subkoderekening_c` varchar(64) NOT NULL DEFAULT '' COMMENT 'SUB-KODE REKENING - LEVEL 3',
  `dk` varchar(8) NOT NULL DEFAULT '' COMMENT 'DEBET/KREDIT',
  `debet` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'DEBET',
  `kredit` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'KREDIT',
  `cnc` varchar(8) NOT NULL DEFAULT '' COMMENT 'TUNAI/BANK',
  `cash` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'TUNAI',
  `bank` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'BANK',
  `nominal` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMINAL TRANSAKSI',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'STATUS DATA',
  `notes` varchar(255) NOT NULL DEFAULT '' COMMENT 'KETERANGAN/URAIAN',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=1536 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `simulasi_dev_skpd_official`
--

DROP TABLE IF EXISTS `simulasi_dev_skpd_official`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `simulasi_dev_skpd_official` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'DATA ID',
  `order_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR URUT / KOHIR',
  `order_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR URUT / SPESIFIK',
  `spopd_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR ID SPOPD',
  `meter_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR ID TEMP METERING - AT',
  `th_pajak` char(4) NOT NULL COMMENT 'TAHUN PAJAK',
  `dt_entry` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT 'DATETIME ENTRY DATA',
  `d_entry` date NOT NULL DEFAULT '1970-01-01' COMMENT 'DATE ENTRY DATA',
  `data_src` varchar(16) NOT NULL DEFAULT '' COMMENT 'SUMBER DATA ENTRY',
  `no_pel` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR PELAYANAN',
  `npwpd` varchar(64) NOT NULL DEFAULT '' COMMENT 'NPWPD',
  `ctx` varchar(32) NOT NULL DEFAULT '' COMMENT 'CONTEXT - REKLAME / AIR TANAH',
  `rekening` varchar(10) NOT NULL DEFAULT '' COMMENT 'NOMOR REKENING PENDAPATAN',
  `jenis` varchar(32) NOT NULL DEFAULT '' COMMENT 'NOMOR SUB-REKENING PENDAPATAN',
  `kode` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE INTERNAL TARIF REKLAME DPPKAD',
  `rk_a` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE SEGMEN A',
  `rk_b` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE SEGMEN B',
  `rk_c` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE SEGMEN C',
  `rk_d` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE SEGMEN D',
  `rk_e` varchar(8) NOT NULL DEFAULT '' COMMENT 'KODE SEGMEN E',
  `tenant` varchar(255) NOT NULL DEFAULT '' COMMENT 'NAMA PEMASANG / AGENSI / WP',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT 'ALAMAT PEMASANG / AGENSI / WP',
  `classification` varchar(32) NOT NULL DEFAULT '' COMMENT 'KLASIFIKASI OBYEK PAJAK',
  `location` varchar(255) NOT NULL DEFAULT '' COMMENT 'LOKASI OBYEK PAJAK',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT 'KONTEN REKLAME / PERUNTUKAN PEMAKAIAN',
  `kode_kec` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'KODE KECAMATAN - VERSI DPPKAD',
  `id_kec` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE KECAMATAN - VERSI CAPIL',
  `id_kec_pbb` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE KECAMATAN - VERSI SISMIOP',
  `nama_kec` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA KECAMATAN',
  `kode_kel` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'KODE DESA / KELURAHAN - VERSI DPPKAD',
  `id_kel` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE DESA / KELURAHAN - VERSI CAPIL',
  `nama_kel` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA DESA / KELURAHAN',
  `rw` varchar(4) NOT NULL DEFAULT '' COMMENT 'RW',
  `rt` varchar(4) NOT NULL DEFAULT '' COMMENT 'RT',
  `rk_panjang` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'PANJANG',
  `rk_lebar` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'LEBAR',
  `rk_luas` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'LUAS',
  `rk_jumlah` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'JUMLAH',
  `rk_tarif_pct` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT 'PERSENTASE TARIF',
  `rk_tarif` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'TARIF DASAR REKLAME',
  `meter_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'METER BULAN LALU',
  `meter_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'METER BULAN INI',
  `volume` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME PENGGUNAAN [B-A]',
  `tarif_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 1',
  `tarif_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 2',
  `tarif_c` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 3',
  `tarif_d` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 4',
  `tarif_e` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 5',
  `tarif_f` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 6',
  `volume_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 1',
  `volume_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 2',
  `volume_c` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 3',
  `volume_d` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 4',
  `volume_e` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 5',
  `volume_f` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 6',
  `subtotal_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 1',
  `subtotal_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 2',
  `subtotal_c` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 3',
  `subtotal_d` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 4',
  `subtotal_e` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 5',
  `subtotal_f` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 6',
  `estimasi` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'ESTIMASI PAJAK SPOPD',
  `penetapan` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'JUMLAH PENETAPAN - SKPD',
  `tgl_penetapan` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL PENETAPAN',
  `tgl_jatuhtempo` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL JATUH TEMPO',
  `tgl_penerimaan` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL PENERIMAAN',
  `penerimaan` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'JUMLAH PENERIMAAN',
  `no_bku` varchar(32) NOT NULL DEFAULT '' COMMENT 'NOMOR BKU PENERIMAAN',
  `start_date` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TANGGAL MULAI MASA PAJAK',
  `end_date` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TANGGAL BERAKHIR MASA PAJAK',
  `rk_diff_thn` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'DIFF TAHUN',
  `rk_diff_bln` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'DIFF BULAN',
  `rk_diff_mgg` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'DIFF MINGGU',
  `rk_diff_hari` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'DIFF HARI',
  `rk_sig` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'REMARK SIG',
  `point_latlong` varchar(255) NOT NULL DEFAULT '' COMMENT 'POINT LATITUDE-LONGITUDE SIG',
  `photo` varchar(255) NOT NULL DEFAULT '' COMMENT 'PHOTO OBYEK PAJAK',
  `petugas` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID PETUGAS KOLEKTOR / PENAGIH',
  `id_op` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID OPERATOR ENTRY DATA',
  `stat_dok` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS DOKUMEN',
  `stat_active` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS AKTIVASI DATA',
  `note_deactive` varchar(64) NOT NULL DEFAULT '' COMMENT 'CATATAN TERHADAP DEAKTIVASI OBYEK PAJAK',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=1126 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `temp_meter_air_tanah`
--

DROP TABLE IF EXISTS `temp_meter_air_tanah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `temp_meter_air_tanah` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'DATA ID / NOMOR KOHIR',
  `spopd_idx` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR ID SPOPD',
  `th_pajak` varchar(4) NOT NULL DEFAULT '' COMMENT 'TAHUN PAJAK',
  `dt_entry` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT 'DATETIME ENTRY DATA',
  `d_entry` date NOT NULL DEFAULT '1970-01-01' COMMENT 'DATE ENTRY DATA',
  `data_src` varchar(16) NOT NULL DEFAULT '' COMMENT 'SUMBER DATA ENTRY',
  `no_pel` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'NOMOR PELAYANAN',
  `npwpd` varchar(64) NOT NULL DEFAULT '' COMMENT 'NPWPD',
  `ctx` varchar(32) NOT NULL DEFAULT '' COMMENT 'CONTEXT - REKLAME / AIR TANAH',
  `rekening` varchar(10) NOT NULL DEFAULT '' COMMENT 'NOMOR REKENING PENDAPATAN',
  `subrekening` varchar(32) NOT NULL DEFAULT '' COMMENT 'NOMOR SUB-REKENING PENDAPATAN',
  `tenant` varchar(255) NOT NULL DEFAULT '' COMMENT 'NAMA PEMASANG / AGENSI / WP',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT 'ALAMAT PEMASANG / AGENSI / WP',
  `classification` varchar(32) NOT NULL DEFAULT '' COMMENT 'KLASIFIKASI OBYEK PAJAK',
  `location` varchar(255) NOT NULL DEFAULT '' COMMENT 'LOKASI OBYEK PAJAK',
  `obyek_pajak` varchar(255) NOT NULL DEFAULT '' COMMENT 'KETERANGAN OBYEK PAJAK',
  `kode_kec` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'KODE KECAMATAN - VERSI DPPKAD',
  `id_kec` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE KECAMATAN - VERSI CAPIL',
  `id_kec_pbb` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE KECAMATAN - VERSI SISMIOP',
  `nama_kec` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA KECAMATAN',
  `kode_kel` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'KODE DESA / KELURAHAN - VERSI DPPKAD',
  `id_kel` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE DESA / KELURAHAN - VERSI CAPIL',
  `nama_kel` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA DESA / KELURAHAN',
  `rw` varchar(4) NOT NULL DEFAULT '' COMMENT 'RW',
  `rt` varchar(4) NOT NULL DEFAULT '' COMMENT 'RT',
  `meter_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'METER BULAN LALU',
  `meter_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'METER BULAN INI',
  `volume` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME PENGGUNAAN [B-A]',
  `tarif_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 1',
  `tarif_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 2',
  `tarif_c` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 3',
  `tarif_d` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 4',
  `tarif_e` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 5',
  `tarif_f` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF LEVEL 6',
  `volume_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 1',
  `volume_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 2',
  `volume_c` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 3',
  `volume_d` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 4',
  `volume_e` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 5',
  `volume_f` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'VOLUME LEVEL 6',
  `subtotal_a` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 1',
  `subtotal_b` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 2',
  `subtotal_c` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 3',
  `subtotal_d` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 4',
  `subtotal_e` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 5',
  `subtotal_f` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TARIF X VOLUME 6',
  `volumextariff` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'TOTAL PEROLEHAN - VOLUME X TARIF',
  `estimasi` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'ESTIMASI PAJAK SPOPD',
  `penetapan` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'JUMLAH PENETAPAN - SKPD',
  `tgl_spopd` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL SPOPD',
  `tgl_penetapan` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL PENETAPAN',
  `tgl_penerimaan` date NOT NULL DEFAULT '1900-01-01' COMMENT 'TANGGAL PENERIMAAN',
  `penerimaan` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'JUMLAH PENERIMAAN',
  `no_bku` varchar(32) NOT NULL DEFAULT '' COMMENT 'NOMOR BKU PENERIMAAN',
  `start_date` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TANGGAL MULAI MASA PAJAK',
  `end_date` date NOT NULL DEFAULT '1970-01-01' COMMENT 'TANGGAL BERAKHIR MASA PAJAK',
  `stat_dok` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS DOKUMEN',
  `stat_active` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS AKTIVASI DATA',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=1051 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `temp_tarif_reklame`
--

DROP TABLE IF EXISTS `temp_tarif_reklame`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `temp_tarif_reklame` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'IDX - PRIMARY KEY',
  `rek` varchar(32) NOT NULL DEFAULT '' COMMENT 'KODE REKENING PENDAPATAN',
  `rek_a` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE DPPKAD - SEGMEN 1',
  `rek_b` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE DPPKAD - SEGMEN 2',
  `kode` varchar(64) NOT NULL DEFAULT '' COMMENT 'KODE JENIS REKLAME',
  `note_kode` varchar(64) NOT NULL DEFAULT '' COMMENT 'KETERANGAN JENIS REKLAME',
  `kode_a` varchar(16) NOT NULL DEFAULT '' COMMENT 'KODE DURASI MASA PAJAK',
  `note_kode_a` varchar(16) NOT NULL DEFAULT '' COMMENT 'KETERANGAN DURASI MASA PAJAK',
  `kode_b` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE LOKASI PEMASANGAN REKLAME',
  `note_kode_b` varchar(32) NOT NULL DEFAULT '' COMMENT 'KETERANGAN LOKASI PEMASANGAN REKLAME',
  `kode_c` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT 'JUMLAH SISI OBYEK REKLAME',
  `kode_d` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE KATEGORI REKLAME - BIASA/KHUSUS',
  `note_kode_d` varchar(32) NOT NULL DEFAULT '' COMMENT 'KATEGORI REKLAME - BIASA/KHUSUS',
  `pct` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT 'PCT TARIF PAJAK',
  `tarif` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT 'TARIF PAJAK',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'STATUS DATA',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=253 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `verificator_performance`
--

DROP TABLE IF EXISTS `verificator_performance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `verificator_performance` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idxv` int(10) unsigned NOT NULL DEFAULT '0',
  `verificator` varchar(64) NOT NULL DEFAULT '',
  `docv` int(10) unsigned NOT NULL DEFAULT '0',
  `nihil` int(10) unsigned NOT NULL DEFAULT '0',
  `sspd` bigint(20) unsigned NOT NULL DEFAULT '0',
  `skpdkb` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bphtb` bigint(20) unsigned NOT NULL DEFAULT '0',
  `rsspd` bigint(20) NOT NULL DEFAULT '0',
  `rskpdkb` bigint(20) NOT NULL DEFAULT '0',
  `realisasi` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-01-16  1:57:12
