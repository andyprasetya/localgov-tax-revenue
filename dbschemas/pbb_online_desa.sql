-- MySQL dump 10.13  Distrib 5.7.17, for Linux (x86_64)
--
-- Host: localhost    Database: pbb_online_desa
-- ------------------------------------------------------
-- Server version	5.7.17

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `appx_camat_users`
--

DROP TABLE IF EXISTS `appx_camat_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appx_camat_users` (
  `KD_PROPINSI` varchar(2) NOT NULL,
  `KD_DATI2` varchar(2) NOT NULL,
  `KD_KECAMATAN` varchar(3) NOT NULL,
  `NM_KECAMATAN` varchar(32) NOT NULL DEFAULT '',
  `WORDPASS` varchar(40) NOT NULL DEFAULT '',
  `STATUS` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`KD_PROPINSI`,`KD_DATI2`,`KD_KECAMATAN`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_desa_petugas_pungut`
--

DROP TABLE IF EXISTS `appx_desa_petugas_pungut`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=2491 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_desa_users`
--

DROP TABLE IF EXISTS `appx_desa_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appx_kecamatan_users`
--

DROP TABLE IF EXISTS `appx_kecamatan_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appx_kecamatan_users` (
  `KD_PROPINSI` varchar(2) NOT NULL,
  `KD_DATI2` varchar(2) NOT NULL,
  `KD_KECAMATAN` varchar(3) NOT NULL,
  `NM_KECAMATAN` varchar(32) NOT NULL DEFAULT '',
  `WORDPASS` varchar(40) NOT NULL DEFAULT '',
  `STATUS` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`KD_PROPINSI`,`KD_DATI2`,`KD_KECAMATAN`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_desa`
--

DROP TABLE IF EXISTS `ref_desa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=291 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ref_kecamatan`
--

DROP TABLE IF EXISTS `ref_kecamatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ref_kecamatan` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID DATA',
  `kode` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE DPPKAD',
  `kode_pbb` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE PBB/SISMIOP',
  `kode_capil` varchar(4) NOT NULL DEFAULT '' COMMENT 'KODE DISDUKCAPIL',
  `kecamatan` varchar(64) NOT NULL DEFAULT '' COMMENT 'NAMA KECAMATAN',
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sppt_data`
--

DROP TABLE IF EXISTS `sppt_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sppt_data` (
  `KD_PROPINSI` char(2) NOT NULL,
  `KD_DATI2` char(2) NOT NULL,
  `KD_KECAMATAN` char(3) NOT NULL,
  `KD_KELURAHAN` char(3) NOT NULL,
  `KD_BLOK` char(3) NOT NULL,
  `NO_URUT` char(4) NOT NULL,
  `KD_JNS_OP` char(1) NOT NULL,
  `THN_PAJAK_SPPT` char(4) NOT NULL,
  `SIKLUS_SPPT` int(10) NOT NULL DEFAULT '0',
  `KD_KANWIL_BANK` varchar(2) NOT NULL DEFAULT '',
  `KD_KPPBB_BANK` varchar(2) NOT NULL DEFAULT '',
  `KD_BANK_TUNGGAL` varchar(2) NOT NULL DEFAULT '',
  `KD_BANK_PERSEPSI` varchar(2) NOT NULL DEFAULT '',
  `KD_TP` varchar(2) NOT NULL DEFAULT '',
  `NM_WP_SPPT` varchar(255) NOT NULL DEFAULT '',
  `JLN_WP_SPPT` varchar(255) NOT NULL DEFAULT '',
  `BLOK_KAV_NO_WP_SPPT` varchar(64) NOT NULL DEFAULT '',
  `RW_WP_SPPT` varchar(3) NOT NULL DEFAULT '',
  `RT_WP_SPPT` varchar(3) NOT NULL DEFAULT '',
  `KELURAHAN_WP_SPPT` varchar(64) NOT NULL DEFAULT '',
  `KOTA_WP_SPPT` varchar(64) NOT NULL DEFAULT '',
  `KD_POS_WP_SPPT` varchar(5) NOT NULL DEFAULT '',
  `NPWP_SPPT` varchar(16) NOT NULL DEFAULT '',
  `NO_PERSIL_SPPT` varchar(32) NOT NULL DEFAULT '',
  `KD_KLS_TANAH` varchar(8) NOT NULL DEFAULT '',
  `THN_AWAL_KLS_TANAH` varchar(4) NOT NULL DEFAULT '',
  `KD_KLS_BNG` varchar(8) NOT NULL DEFAULT '',
  `THN_AWAL_KLS_BNG` varchar(4) NOT NULL DEFAULT '',
  `TGL_JATUH_TEMPO_SPPT` date NOT NULL DEFAULT '1900-01-01',
  `LUAS_BUMI_SPPT` decimal(10,2) NOT NULL DEFAULT '0.00',
  `LUAS_BNG_SPPT` decimal(10,2) NOT NULL DEFAULT '0.00',
  `NJOP_BUMI_SPPT` bigint(20) NOT NULL DEFAULT '0',
  `NJOP_BNG_SPPT` bigint(20) NOT NULL DEFAULT '0',
  `NJOP_SPPT` bigint(20) NOT NULL DEFAULT '0',
  `NJOPTKP_SPPT` bigint(20) NOT NULL DEFAULT '0',
  `NJKP_SPPT` bigint(20) NOT NULL DEFAULT '0',
  `PBB_TERHUTANG_SPPT` bigint(20) NOT NULL DEFAULT '0',
  `FAKTOR_PENGURANG_SPPT` int(10) NOT NULL DEFAULT '0',
  `PBB_YG_HARUS_DIBAYAR_SPPT` bigint(20) NOT NULL DEFAULT '0',
  `STATUS_PEMBAYARAN_SPPT` varchar(4) NOT NULL DEFAULT '',
  `STATUS_TAGIHAN_SPPT` varchar(4) NOT NULL DEFAULT '',
  `STATUS_CETAK_SPPT` varchar(4) NOT NULL DEFAULT '',
  `TGL_TERBIT_SPPT` date NOT NULL DEFAULT '1900-01-01',
  `TGL_CETAK_SPPT` date NOT NULL DEFAULT '1900-01-01',
  `NIP_PENCETAK_SPPT` varchar(16) NOT NULL DEFAULT '',
  `PEMBAYARAN_SPPT_KE` int(10) NOT NULL DEFAULT '0',
  `DENDA_SPPT` decimal(10,2) NOT NULL DEFAULT '0.00',
  `JML_SPPT_YG_DIBAYAR` bigint(20) NOT NULL DEFAULT '0',
  `TGL_PEMBAYARAN_SPPT` date NOT NULL DEFAULT '1900-01-01',
  `TGL_REKAM_BYR_SPPT` date NOT NULL DEFAULT '1900-01-01',
  `NIP_REKAM_BYR_SPPT` varchar(16) NOT NULL DEFAULT '',
  `PETUGASIDX` int(10) NOT NULL DEFAULT '0',
  `NAMAPETUGAS` varchar(64) NOT NULL DEFAULT '',
  `STATUS` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`KD_PROPINSI`,`KD_DATI2`,`KD_KECAMATAN`,`KD_KELURAHAN`,`KD_BLOK`,`NO_URUT`,`KD_JNS_OP`,`THN_PAJAK_SPPT`,`TGL_TERBIT_SPPT`),
  KEY `KD_PROPINSI` (`KD_PROPINSI`),
  KEY `KD_DATI2` (`KD_DATI2`),
  KEY `KD_KECAMATAN` (`KD_KECAMATAN`),
  KEY `KD_KELURAHAN` (`KD_KELURAHAN`),
  KEY `KD_BLOK` (`KD_BLOK`),
  KEY `NO_URUT` (`NO_URUT`),
  KEY `KD_JNS_OP` (`KD_JNS_OP`),
  KEY `THN_PAJAK_SPPT` (`THN_PAJAK_SPPT`),
  KEY `KELURAHAN_WP_SPPT` (`KELURAHAN_WP_SPPT`),
  KEY `TGL_TERBIT_SPPT` (`TGL_TERBIT_SPPT`),
  KEY `TGL_PEMBAYARAN_SPPT` (`TGL_PEMBAYARAN_SPPT`),
  KEY `PETUGASIDX` (`PETUGASIDX`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `summary_desa`
--

DROP TABLE IF EXISTS `summary_desa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `summary_desa` (
  `kd_propinsi` char(2) NOT NULL,
  `kd_dati2` char(2) NOT NULL,
  `kd_kecamatan` char(3) NOT NULL,
  `kd_kelurahan` char(3) NOT NULL,
  `nm_kecamatan` varchar(64) NOT NULL DEFAULT '',
  `nm_kelurahan` varchar(64) NOT NULL DEFAULT '',
  `thn_pajak_sppt` varchar(4) NOT NULL DEFAULT '',
  `jml_sppt` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_unpaid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_unpaid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_paid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_paid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_unassigned` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_unassigned` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_unassigned_unpaid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_unassigned_unpaid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_unassigned_paid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_unassigned_paid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_assigned` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_assigned` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_assigned_unpaid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_assigned_unpaid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_assigned_paid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_assigned_paid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `last_update` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
  PRIMARY KEY (`kd_propinsi`,`kd_dati2`,`kd_kecamatan`,`kd_kelurahan`,`thn_pajak_sppt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `summary_kabupaten`
--

DROP TABLE IF EXISTS `summary_kabupaten`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `summary_kabupaten` (
  `kd_propinsi` char(2) NOT NULL,
  `kd_dati2` char(2) NOT NULL,
  `nm_kabupaten` varchar(64) NOT NULL DEFAULT '',
  `thn_pajak_sppt` varchar(4) NOT NULL DEFAULT '',
  `jml_sppt` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_unpaid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_unpaid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_paid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_paid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_unassigned` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_unassigned` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_unassigned_unpaid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_unassigned_unpaid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_unassigned_paid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_unassigned_paid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_assigned` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_assigned` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_assigned_unpaid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_assigned_unpaid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_assigned_paid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_assigned_paid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `last_update` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
  PRIMARY KEY (`kd_propinsi`,`kd_dati2`,`thn_pajak_sppt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `summary_kecamatan`
--

DROP TABLE IF EXISTS `summary_kecamatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `summary_kecamatan` (
  `kd_propinsi` char(2) NOT NULL,
  `kd_dati2` char(2) NOT NULL,
  `kd_kecamatan` char(3) NOT NULL,
  `nm_kecamatan` varchar(64) NOT NULL DEFAULT '',
  `thn_pajak_sppt` varchar(4) NOT NULL DEFAULT '',
  `jml_sppt` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_unpaid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_unpaid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_paid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_paid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_unassigned` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_unassigned` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_unassigned_unpaid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_unassigned_unpaid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_unassigned_paid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_unassigned_paid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_assigned` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_assigned` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_assigned_unpaid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_assigned_unpaid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_assigned_paid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_assigned_paid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `last_update` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
  PRIMARY KEY (`kd_propinsi`,`kd_dati2`,`kd_kecamatan`,`thn_pajak_sppt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `summary_petugas_pungut`
--

DROP TABLE IF EXISTS `summary_petugas_pungut`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `summary_petugas_pungut` (
  `idx` int(10) NOT NULL,
  `KD_PROPINSI` char(2) NOT NULL,
  `KD_DATI2` char(2) NOT NULL,
  `KD_KECAMATAN` char(3) NOT NULL,
  `KD_KELURAHAN` char(3) NOT NULL,
  `NM_KECAMATAN` varchar(32) NOT NULL DEFAULT '',
  `NM_KELURAHAN` varchar(32) NOT NULL DEFAULT '',
  `NAMA_PETUGAS` varchar(64) NOT NULL DEFAULT '',
  `THN_PAJAK_SPPT` varchar(4) NOT NULL,
  `jml_sppt_assigned` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_assigned` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_assigned_unpaid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_assigned_unpaid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jml_sppt_assigned_paid` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sppt_assigned_paid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `STATUS` int(1) NOT NULL DEFAULT '0',
  `last_update` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
  PRIMARY KEY (`idx`,`THN_PAJAK_SPPT`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sync_distribusi_petugas`
--

DROP TABLE IF EXISTS `sync_distribusi_petugas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sync_distribusi_petugas` (
  `KD_PROPINSI` char(2) NOT NULL,
  `KD_DATI2` char(2) NOT NULL,
  `KD_KECAMATAN` char(3) NOT NULL,
  `KD_KELURAHAN` char(3) NOT NULL,
  `KD_BLOK` char(3) NOT NULL,
  `NO_URUT` char(4) NOT NULL,
  `KD_JNS_OP` char(1) NOT NULL,
  `THN_PAJAK_SPPT` char(4) NOT NULL,
  `TGL_TERBIT_SPPT` date NOT NULL DEFAULT '1900-01-01',
  `PETUGASIDX` int(10) NOT NULL DEFAULT '0',
  `NAMAPETUGAS` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`KD_PROPINSI`,`KD_DATI2`,`KD_KECAMATAN`,`KD_KELURAHAN`,`KD_BLOK`,`NO_URUT`,`KD_JNS_OP`,`THN_PAJAK_SPPT`,`TGL_TERBIT_SPPT`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `temp_assign_sppt`
--

DROP TABLE IF EXISTS `temp_assign_sppt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `temp_assign_sppt` (
  `KD_PROPINSI` char(2) NOT NULL,
  `KD_DATI2` char(2) NOT NULL,
  `KD_KECAMATAN` char(3) NOT NULL,
  `KD_KELURAHAN` char(3) NOT NULL,
  `KD_BLOK` char(3) NOT NULL,
  `NO_URUT` char(4) NOT NULL,
  `KD_JNS_OP` char(1) NOT NULL,
  `THN_PAJAK_SPPT` char(4) NOT NULL,
  `TGL_TERBIT_SPPT` date NOT NULL DEFAULT '1900-01-01',
  `PETUGASIDX` int(10) NOT NULL DEFAULT '0',
  `NAMAPETUGAS` varchar(64) NOT NULL DEFAULT '',
  `CODEX` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`KD_PROPINSI`,`KD_DATI2`,`KD_KECAMATAN`,`KD_KELURAHAN`,`KD_BLOK`,`NO_URUT`,`KD_JNS_OP`,`THN_PAJAK_SPPT`,`TGL_TERBIT_SPPT`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `temp_sppt_data`
--

DROP TABLE IF EXISTS `temp_sppt_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `temp_sppt_data` (
  `IDX` varchar(64) NOT NULL,
  `KD_PROPINSI` char(2) NOT NULL,
  `KD_DATI2` char(2) NOT NULL,
  `KD_KECAMATAN` char(3) NOT NULL,
  `KD_KELURAHAN` char(3) NOT NULL,
  `KD_BLOK` char(3) NOT NULL,
  `NO_URUT` char(4) NOT NULL,
  `KD_JNS_OP` char(1) NOT NULL,
  `THN_PAJAK_SPPT` char(4) NOT NULL,
  `SIKLUS_SPPT` int(10) NOT NULL DEFAULT '0',
  `KD_KANWIL_BANK` varchar(2) NOT NULL DEFAULT '',
  `KD_KPPBB_BANK` varchar(2) NOT NULL DEFAULT '',
  `KD_BANK_TUNGGAL` varchar(2) NOT NULL DEFAULT '',
  `KD_BANK_PERSEPSI` varchar(2) NOT NULL DEFAULT '',
  `KD_TP` varchar(2) NOT NULL DEFAULT '',
  `NM_WP_SPPT` varchar(255) NOT NULL DEFAULT '',
  `JLN_WP_SPPT` varchar(255) NOT NULL DEFAULT '',
  `BLOK_KAV_NO_WP_SPPT` varchar(64) NOT NULL DEFAULT '',
  `RW_WP_SPPT` varchar(3) NOT NULL DEFAULT '',
  `RT_WP_SPPT` varchar(3) NOT NULL DEFAULT '',
  `KELURAHAN_WP_SPPT` varchar(64) NOT NULL DEFAULT '',
  `KOTA_WP_SPPT` varchar(64) NOT NULL DEFAULT '',
  `KD_POS_WP_SPPT` varchar(5) NOT NULL DEFAULT '',
  `NPWP_SPPT` varchar(16) NOT NULL DEFAULT '',
  `NO_PERSIL_SPPT` varchar(32) NOT NULL DEFAULT '',
  `KD_KLS_TANAH` varchar(8) NOT NULL DEFAULT '',
  `THN_AWAL_KLS_TANAH` varchar(4) NOT NULL DEFAULT '',
  `KD_KLS_BNG` varchar(8) NOT NULL DEFAULT '',
  `THN_AWAL_KLS_BNG` varchar(4) NOT NULL DEFAULT '',
  `TGL_JATUH_TEMPO_SPPT` date NOT NULL DEFAULT '1900-01-01',
  `LUAS_BUMI_SPPT` decimal(10,2) NOT NULL DEFAULT '0.00',
  `LUAS_BNG_SPPT` decimal(10,2) NOT NULL DEFAULT '0.00',
  `NJOP_BUMI_SPPT` bigint(20) NOT NULL DEFAULT '0',
  `NJOP_BNG_SPPT` bigint(20) NOT NULL DEFAULT '0',
  `NJOP_SPPT` bigint(20) NOT NULL DEFAULT '0',
  `NJOPTKP_SPPT` bigint(20) NOT NULL DEFAULT '0',
  `NJKP_SPPT` bigint(20) NOT NULL DEFAULT '0',
  `PBB_TERHUTANG_SPPT` bigint(20) NOT NULL DEFAULT '0',
  `FAKTOR_PENGURANG_SPPT` int(10) NOT NULL DEFAULT '0',
  `PBB_YG_HARUS_DIBAYAR_SPPT` bigint(20) NOT NULL DEFAULT '0',
  `STATUS_PEMBAYARAN_SPPT` varchar(4) NOT NULL DEFAULT '',
  `STATUS_TAGIHAN_SPPT` varchar(4) NOT NULL DEFAULT '',
  `STATUS_CETAK_SPPT` varchar(4) NOT NULL DEFAULT '',
  `TGL_TERBIT_SPPT` date NOT NULL DEFAULT '1900-01-01',
  `TGL_CETAK_SPPT` date NOT NULL DEFAULT '1900-01-01',
  `NIP_PENCETAK_SPPT` varchar(16) NOT NULL DEFAULT '',
  `PEMBAYARAN_SPPT_KE` int(10) NOT NULL DEFAULT '0',
  `DENDA_SPPT` decimal(10,2) NOT NULL DEFAULT '0.00',
  `JML_SPPT_YG_DIBAYAR` bigint(20) NOT NULL DEFAULT '0',
  `TGL_PEMBAYARAN_SPPT` date NOT NULL DEFAULT '1900-01-01',
  `TGL_REKAM_BYR_SPPT` date NOT NULL DEFAULT '1900-01-01',
  `NIP_REKAM_BYR_SPPT` varchar(16) NOT NULL DEFAULT '',
  `PETUGASIDX` int(10) NOT NULL DEFAULT '0',
  `NAMAPETUGAS` varchar(64) NOT NULL DEFAULT '',
  `STATUS` int(10) NOT NULL DEFAULT '0',
  `CODEX` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`IDX`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-11-13 20:57:43
