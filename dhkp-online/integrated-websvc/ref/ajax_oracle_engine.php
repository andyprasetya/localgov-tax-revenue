<?php
/* ERROR DEBUG HANDLE */
error_reporting(E_ALL);
ini_set("display_errors", 1);
/* SESSION START */
session_start();
$txsessionid = session_id();
/* CONSTANTS & VARIABLES */
require("./variablen.php");
/* CONFIGURATION */
require("./einstellung.php");
/* LIBRARIES */
require("./funktion.php");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: token, kec, kel");
if (isset($_GET['cmdx'])) {
	$dbcon = oci_connect("".$appxinfo['_oracle_user_']."", "".$appxinfo['_oracle_pass_']."", "//".$appxinfo['_oracle_host_'].":".$appxinfo['_oracle_port_']."/".$appxinfo['_oracle_service_']."");
	if (!$dbcon) {
		$errMsg = oci_error();
		trigger_error(htmlentities($errMsg['message']), E_USER_ERROR);
	} else {
		if (isset($_SESSION['appsimkelXweb'])) {
			$kode_kecamatan = strtoupper($_SESSION['appsimkelXweb']['kode_kec']);
			$kode_kelurahan = strtoupper($_SESSION['appsimkelXweb']['kode_kel']);
			$nama_kelurahan = strtoupper($_SESSION['appsimkelXweb']['nama_kel']);
			$chPROP = "33";
			$chKABU = "71";
		} elseif (isset($_SESSION['appsiakXtools'])) {
			$kode_kecamatan = null;
			$kode_kelurahan = null;
			$nama_kelurahan = null;
			$chPROP = $_SESSION['appsiakXtools']['kode_prov'];
			$chKABU = $_SESSION['appsiakXtools']['kode_kab'];
		} else {
			// identify options vs post
			$reqMethod = $_SERVER['REQUEST_METHOD'];
			if ($reqMethod !== 'OPTIONS') {
				// other than request method = options
				// decoding token: it must be yyyymmdd|simkelweb-siaktools
				$token = base64_decode($_SERVER['HTTP_TOKEN']);
				$arrToken = explode('|', $token);
				if ($arrToken[0] == date('Ymd') && $arrToken[1] == 'simkelweb-siaktools') {
					$kode_kecamatan = base64_decode($_SERVER['HTTP_KEC']);
					$kode_kelurahan = base64_decode($_SERVER['HTTP_KEL']);
				}
			}
		}
		if ($_GET['cmdx'] == "INDIVIDUAL_SEARCH") {
			if (is_numeric($_GET['strquery'])) {
				$query = "SELECT TO_CHAR (a.nik, '0000000000000000') nik, UPPER (a.nama_lgkp) nama_lgkp, UPPER (a.TMPT_LHR) TMPT_LHR, TO_CHAR (a.TGL_LHR, 'dd-mm-yyyy') TGL_LHR, DECODE (a.JENIS_KLMIN,  1, 'LAKI-LAKI',  2, 'PEREMPUAN') JENIS_KLMIN, DECODE (a.GOL_DRH, 1, 'A', 2, 'B', 3, 'AB', 4, 'O', 5, 'A+', 6, 'A-', 7, 'B+', 8, 'B-', 9, 'AB+', 10, 'AB-', 11, 'O+', 12, 'O-', 13, '-') GOL_DRH, DECODE (a.AGAMA, 1, 'ISLAM', 2, 'KRISTEN', 3, 'KATHOLIK', 4, 'HINDHU', 5, 'BUDHA', 6, 'KONG HUCHU', 7, 'LAINNYA') AGAMA, DECODE (a.PDDK_AKH, 1, 'TIDAK/BELUM SEKOLAH', 2, 'BELUM TAMAT SD/SEDERAJAT', 3, 'TAMAT SD/SEDERAJAT', 4, 'SLTP/SEDERAJAT', 5, 'SLTA/SEDERAJAT', 6, 'DIPLOMA I/II', 7, 'AKADEMI/DIPLOMA III/S. MUDA', 8, 'DIPLOMA IV/STRATA I', 9, 'STRATA II', 10, 'STRATA III') PDDK_AKH, UPPER (getPkrjn (a.JENIS_PKRJN)) JENIS_PKRJN, DECODE (a.PNYDNG_CCT, 1, 'CACAT FISIK', 2, 'CACAT NETRA', 3, 'CACAT RUNGU/WICARA', 4, 'CACAT MENTAL/JIWA', 5, 'CACAT FISIK DAN MENTAL', 6, 'CACAT LAINNYA') PNYDNG_CCT, DECODE (a.STAT_KWN, 1, 'BELUM KAWIN', 2, 'KAWIN', 3, 'CERAI HIDUP', 4, 'CERAI MATI') STAT_KWN, DECODE (a.STAT_HBKEL, 1, 'KEPALA KELUARGA', 2, 'SUAMI', 3, 'ISTRI', 4, 'ANAK', 5, 'MENANTU', 6, 'CUCU', 7, 'ORANGTUA', 8, 'MERTUA', 9, 'FAMILI LAIN', 10, 'PEMBANTU', 11, 'LAINNYA') stat_hbkel, DECODE (a.nik_ibu, '0', '-', NULL, '-', TO_CHAR (a.nik_ibu, '0000000000000000')) nik_ibu, UPPER (a.NAMA_LGKP_IBU) NAMA_LGKP_IBU, DECODE (a.NIK_AYAH, '0', '-', NULL, '-', TO_CHAR (a.NIK_AYAH, '0000000000000000')) NIK_AYAH, UPPER (a.NAMA_LGKP_AYAH) NAMA_LGKP_AYAH, UPPER (NVL (a.TMPT_SBL, '-')) TMPT_SBL, UPPER (b.alamat) alamat, DECODE (b.no_rt, NULL, '-', 0, '-', TRIM (TO_CHAR (b.no_rt, '000'))) no_rt, DECODE (b.no_rw, NULL, '-', 0, '-', TRIM (TO_CHAR (b.no_rw, '000'))) no_rw, UPPER (NVL (b.dusun, '-')) dusun, DECODE (b.KODE_POS,  NULL, '-',  0, '-',  b.KODE_POS) KODE_POS, NVL (b.TELP, '-') TELP, UPPER (getnamaprop (a.no_prop)) nama_prop, UPPER (getnamakab (a.no_kab, a.no_prop)) nama_kab, UPPER (getnamakec (a.no_kec, a.no_kab, a.no_prop)) nama_kec, UPPER (getnamakel (a.no_kel, a.no_kec, a.no_kab, a.no_prop)) nama_kel, NVL (TO_CHAR (a.no_kk, '0000000000000000'), '-') no_kk, DECODE (a.NO_PASPOR,  NULL, '-',  0, '-',  a.NO_PASPOR) NO_PASPOR, DECODE (a.TGL_AKH_PASPOR, NULL, '-', TO_CHAR (a.TGL_AKH_PASPOR, 'dd-mm-yyyy')) TGL_AKH_PASPOR, DECODE (a.NO_AKTA_LHR,  NULL, '-',  0, '-',  a.NO_AKTA_LHR) NO_AKTA_LHR, DECODE (a.NO_AKTA_KWN,  NULL, '-',  0, '-',  a.NO_AKTA_KWN) NO_AKTA_KWN, DECODE (a.TGL_KWN, NULL, '-', TO_CHAR (a.TGL_KWN, 'dd-mm-yyyy')) TGL_KWN, DECODE (a.NO_AKTA_CRAI,  NULL, '-',  0, '-',  a.NO_AKTA_CRAI) NO_AKTA_CRAI, DECODE (a.TGL_CRAI, NULL, '-', TO_CHAR (a.TGL_CRAI, 'dd-mm-yyyy')) TGL_CRAI FROM biodata_wni a, data_keluarga b WHERE a.no_kk = b.no_kk AND a.flag_status = 0 and a.nik = :SEARCHEDSTR AND a.no_kec = :KODEKEC AND a.no_kel = :KODEKEL";
				$stmt = oci_parse($dbcon, $query);
				$strsearch = $_GET['strquery'];
				oci_bind_by_name($stmt, ":SEARCHEDSTR", $strsearch);
				/* oci_bind_by_name($stmt, ":KELURAHAN", $nama_kelurahan); */
				oci_bind_by_name($stmt, ":KODEKEC", $kode_kecamatan);
				oci_bind_by_name($stmt, ":KODEKEL", $kode_kelurahan);
				if (oci_execute($stmt)) {
					while ($rowset = oci_fetch_array($stmt, OCI_RETURN_NULLS+OCI_ASSOC)) {
						$NIK = $rowset['NIK'];
						$NAMA_LGKP = $rowset['NAMA_LGKP'];
						$TMPT_LHR = $rowset['TMPT_LHR'];
						$TGL_LHR = $rowset['TGL_LHR'];
						$JENIS_KLMIN = $rowset['JENIS_KLMIN'];
						$GOL_DRH = $rowset['GOL_DRH'];
						$AGAMA = $rowset['AGAMA'];
						$PDDK_AKH = $rowset['PDDK_AKH'];
						$JENIS_PKRJN = $rowset['JENIS_PKRJN'];
						$PNYDNG_CCT = $rowset['PNYDNG_CCT'];
						$STAT_KWN = $rowset['STAT_KWN'];
						$STAT_HBKEL = $rowset['STAT_HBKEL'];
						$NIK_IBU = $rowset['NIK_IBU'];
						$NAMA_LGKP_IBU = $rowset['NAMA_LGKP_IBU'];
						$NIK_AYAH = $rowset['NIK_AYAH'];
						$NAMA_LGKP_AYAH = $rowset['NAMA_LGKP_AYAH'];
						$TMPT_SBL = $rowset['TMPT_SBL'];
						$ALAMAT = $rowset['ALAMAT'];
						$NO_RT = $rowset['NO_RT'];
						$NO_RW = $rowset['NO_RW'];
						$DUSUN = $rowset['DUSUN'];
						$KODE_POS = $rowset['KODE_POS'];
						$TELP = $rowset['TELP'];
						$NAMA_PROP = $rowset['NAMA_PROP'];
						$NAMA_KAB = $rowset['NAMA_KAB'];
						$NAMA_KEC = $rowset['NAMA_KEC'];
						$NAMA_KEL = $rowset['NAMA_KEL'];
						$NO_KK = $rowset['NO_KK'];
						$NO_PASPOR = $rowset['NO_PASPOR'];
						$TGL_AKH_PASPOR = $rowset['TGL_AKH_PASPOR'];
						$NO_AKTA_LHR = $rowset['NO_AKTA_LHR'];
						$NO_AKTA_KWN = $rowset['NO_AKTA_KWN'];
						$TGL_KWN = $rowset['TGL_KWN'];
						$NO_AKTA_CRAI = $rowset['NO_AKTA_CRAI'];
						$TGL_CRAI = $rowset['TGL_CRAI'];
					}
					$response = array("ajaxresult"=>"found","modedata"=>"single","NIK"=>$NIK,"NAMA_LGKP"=>$NAMA_LGKP,"TMPT_LHR"=>$TMPT_LHR,"TGL_LHR"=>$TGL_LHR,"JENIS_KLMIN"=>$JENIS_KLMIN,"GOL_DRH"=>$GOL_DRH,"AGAMA"=>$AGAMA,"PDDK_AKH"=>$PDDK_AKH,"JENIS_PKRJN"=>$JENIS_PKRJN,"PNYDNG_CCT"=>$PNYDNG_CCT,"STAT_KWN"=>$STAT_KWN,"STAT_HBKEL"=>$STAT_HBKEL,"NIK_IBU"=>$NIK_IBU,"NAMA_LGKP_IBU"=>$NAMA_LGKP_IBU,"NIK_AYAH"=>$NIK_AYAH,"NAMA_LGKP_AYAH"=>$NAMA_LGKP_AYAH,"TMPT_SBL"=>$TMPT_SBL,"ALAMAT"=>$ALAMAT,"NO_RT"=>$NO_RT,"NO_RW"=>$NO_RW,"DUSUN"=>$DUSUN,"KODE_POS"=>$KODE_POS,"TELP"=>$TELP,"NAMA_PROP"=>$NAMA_PROP,"NAMA_KAB"=>$NAMA_KAB,"NAMA_KEC"=>$NAMA_KEC,"NAMA_KEL"=>$NAMA_KEL,"NO_KK"=>$NO_KK,"NO_PASPOR"=>$NO_PASPOR,"TGL_AKH_PASPOR"=>$TGL_AKH_PASPOR,"NO_AKTA_LHR"=>$NO_AKTA_LHR,"NO_AKTA_KWN"=>$NO_AKTA_KWN,"TGL_KWN"=>$TGL_KWN,"NO_AKTA_CRAI"=>$NO_AKTA_CRAI,"TGL_CRAI"=>$TGL_CRAI);
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				} else {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"Data tidak ditemukan.");
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				}
			} else {
				$query = "SELECT TO_CHAR (a.nik, '0000000000000000') nik, UPPER (a.nama_lgkp) nama_lgkp, UPPER (a.TMPT_LHR) TMPT_LHR, TO_CHAR (a.TGL_LHR, 'dd-mm-yyyy') TGL_LHR, DECODE (a.JENIS_KLMIN,  1, 'LAKI-LAKI',  2, 'PEREMPUAN') JENIS_KLMIN, DECODE (a.GOL_DRH, 1, 'A', 2, 'B', 3, 'AB', 4, 'O', 5, 'A+', 6, 'A-', 7, 'B+', 8, 'B-', 9, 'AB+', 10, 'AB-', 11, 'O+', 12, 'O-', 13, '-') GOL_DRH, DECODE (a.AGAMA, 1, 'ISLAM', 2, 'KRISTEN', 3, 'KATHOLIK', 4, 'HINDHU', 5, 'BUDHA', 6, 'KONG HUCHU', 7, 'LAINNYA') AGAMA, DECODE (a.PDDK_AKH, 1, 'TIDAK/BELUM SEKOLAH', 2, 'BELUM TAMAT SD/SEDERAJAT', 3, 'TAMAT SD/SEDERAJAT', 4, 'SLTP/SEDERAJAT', 5, 'SLTA/SEDERAJAT', 6, 'DIPLOMA I/II', 7, 'AKADEMI/DIPLOMA III/S. MUDA', 8, 'DIPLOMA IV/STRATA I', 9, 'STRATA II', 10, 'STRATA III') PDDK_AKH, UPPER (getPkrjn (a.JENIS_PKRJN)) JENIS_PKRJN, DECODE (a.PNYDNG_CCT, 1, 'CACAT FISIK', 2, 'CACAT NETRA', 3, 'CACAT RUNGU/WICARA', 4, 'CACAT MENTAL/JIWA', 5, 'CACAT FISIK DAN MENTAL', 6, 'CACAT LAINNYA') PNYDNG_CCT, DECODE (a.STAT_KWN, 1, 'BELUM KAWIN', 2, 'KAWIN', 3, 'CERAI HIDUP', 4, 'CERAI MATI') STAT_KWN, DECODE (a.STAT_HBKEL, 1, 'KEPALA KELUARGA', 2, 'SUAMI', 3, 'ISTRI', 4, 'ANAK', 5, 'MENANTU', 6, 'CUCU', 7, 'ORANGTUA', 8, 'MERTUA', 9, 'FAMILI LAIN', 10, 'PEMBANTU', 11, 'LAINNYA') stat_hbkel, DECODE (a.nik_ibu, '0', '-', NULL, '-', TO_CHAR (a.nik_ibu, '0000000000000000')) nik_ibu, UPPER (a.NAMA_LGKP_IBU) NAMA_LGKP_IBU, DECODE (a.NIK_AYAH, '0', '-', NULL, '-', TO_CHAR (a.NIK_AYAH, '0000000000000000')) NIK_AYAH, UPPER (a.NAMA_LGKP_AYAH) NAMA_LGKP_AYAH, UPPER (NVL (a.TMPT_SBL, '-')) TMPT_SBL, UPPER (b.alamat) alamat, DECODE (b.no_rt, NULL, '-', 0, '-', TRIM (TO_CHAR (b.no_rt, '000'))) no_rt, DECODE (b.no_rw, NULL, '-', 0, '-', TRIM (TO_CHAR (b.no_rw, '000'))) no_rw, UPPER (NVL (b.dusun, '-')) dusun, DECODE (b.KODE_POS,  NULL, '-',  0, '-',  b.KODE_POS) KODE_POS, NVL (b.TELP, '-') TELP, UPPER (getnamaprop (a.no_prop)) nama_prop, UPPER (getnamakab (a.no_kab, a.no_prop)) nama_kab, UPPER (getnamakec (a.no_kec, a.no_kab, a.no_prop)) nama_kec, UPPER (getnamakel (a.no_kel, a.no_kec, a.no_kab, a.no_prop)) nama_kel, NVL (TO_CHAR (a.no_kk, '0000000000000000'), '-') no_kk, DECODE (a.NO_PASPOR,  NULL, '-',  0, '-',  a.NO_PASPOR) NO_PASPOR, DECODE (a.TGL_AKH_PASPOR, NULL, '-', TO_CHAR (a.TGL_AKH_PASPOR, 'dd-mm-yyyy')) TGL_AKH_PASPOR, DECODE (a.NO_AKTA_LHR,  NULL, '-',  0, '-',  a.NO_AKTA_LHR) NO_AKTA_LHR, DECODE (a.NO_AKTA_KWN,  NULL, '-',  0, '-',  a.NO_AKTA_KWN) NO_AKTA_KWN, DECODE (a.TGL_KWN, NULL, '-', TO_CHAR (a.TGL_KWN, 'dd-mm-yyyy')) TGL_KWN, DECODE (a.NO_AKTA_CRAI,  NULL, '-',  0, '-',  a.NO_AKTA_CRAI) NO_AKTA_CRAI, DECODE (a.TGL_CRAI, NULL, '-', TO_CHAR (a.TGL_CRAI, 'dd-mm-yyyy')) TGL_CRAI FROM biodata_wni a, data_keluarga b WHERE a.no_kk = b.no_kk AND a.flag_status = 0 AND a.nama_lgkp LIKE :SEARCHEDSTR AND a.no_kec = :KODEKEC AND a.no_kel = :KODEKEL";
				$stmt = oci_parse($dbcon, $query);
				$strsearch = strval('%'.strtoupper($_GET['strquery']).'%');
				oci_bind_by_name($stmt, ":SEARCHEDSTR", $strsearch);
				oci_bind_by_name($stmt, ":KODEKEC", $kode_kecamatan);
				oci_bind_by_name($stmt, ":KODEKEL", $kode_kelurahan);
				if (oci_execute($stmt)) {
					while ($rowset = oci_fetch_array($stmt, OCI_RETURN_NULLS+OCI_ASSOC)) {
						$items[] = $rowset;
					}
					if (!empty($items)) {
						$response = array("ajaxresult"=>"found","kec"=>$kode_kecamatan,"kel"=>$kode_kelurahan,"str"=>$strsearch,"modedata"=>"multiple","dataarray"=>$items);
						header('Content-Type: application/json');
						echo json_encode($response);
						oci_free_statement($stmt);
						oci_close($dbcon);
						exit;
					} else {
						$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"Data tidak ditemukan.");
						header('Content-Type: application/json');
						echo json_encode($response);
						oci_free_statement($stmt);
						oci_close($dbcon);
						exit;
					}
				} else {
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				}
			}
		} elseif ($_GET['cmdx'] == "SEARCH_BY_NOKK") {
			$query = "SELECT * FROM SIAKOFF.SIMKEL_KK WHERE NO_KK = :SEARCHEDNOKK AND NO_KEC = :KODEKEC AND NO_KEL = :KODEKEL";
			/* $query = "
SELECT DISTINCT NVL(TO_CHAR(b.NO_KK, '0000000000000000'), '-') NO_KK, K.ALAMAT,K.NO_RT,K.NO_RW, K.NO_KEL,K.NO_KEC,K.NO_KAB,K.NO_PROP, K.KODE_POS,
siakoff.GETNAMAKEL(K.NO_KEL,K.NO_KEC,K.NO_KAB,K.NO_PROP) KEL, siakoff.GETNAMAKEC(K.NO_KEC,K.NO_KAB,K.NO_PROP) KEC
FROM siakoff.BIODATA_WNI b INNER JOIN siakoff.DATA_KELUARGA k on b.NO_KK = k.NO_KK 
WHERE b.NIK IN      
  (SELECT b.NIK FROM BIODATA_WNI b left join siakel.BIODATA_WNI_201701 s on b.nik=s.nik  WHERE s.nik is null and b.flag_status=0
   minus select NIK FROM BIODATA_WNI where nvl(TO_CHAR(TGL_ENTRI, 'yyyymmdd'),'20100101') > '20170530' or nvl(TO_CHAR(TGL_UBAH, 'yyyymmdd'),'20100101') > '20170530'  
   minus select d.nik from datang_header h inner join datang_detail d on h.no_datang=d.no_datang where h.KLASIFIKASI_PINDAH > 3 and to_char(h.tgl_datang,'yyyymmdd')>'20170530')
  And b.flag_status=0 AND k.NO_KK = :SEARCHEDNOKK AND k.NO_KEC = :KODEKEC AND k.NO_KEL = :KODEKEL"; */
			$stmt = oci_parse($dbcon, $query);
			$nokk = $_GET['nokk'];
			/* sample: 3371031407140003 */
			oci_bind_by_name($stmt, ":SEARCHEDNOKK", $nokk);
			oci_bind_by_name($stmt, ":KODEKEC", $kode_kecamatan);
			oci_bind_by_name($stmt, ":KODEKEL", $kode_kelurahan);
			if (oci_execute($stmt)) {
				while ($rowset = oci_fetch_array($stmt, OCI_RETURN_NULLS+OCI_ASSOC)) {
					$items[] = $rowset;
				}
				if (!empty($items)) {
					$querymember = "SELECT TO_CHAR (a.nik, '0000000000000000') nik, UPPER (a.nama_lgkp) nama_lgkp, UPPER (a.TMPT_LHR) TMPT_LHR, TO_CHAR (a.TGL_LHR, 'dd-mm-yyyy') TGL_LHR, DECODE (a.JENIS_KLMIN,  1, 'LAKI-LAKI',  2, 'PEREMPUAN') JENIS_KLMIN, DECODE (a.GOL_DRH, 1, 'A', 2, 'B', 3, 'AB', 4, 'O', 5, 'A+', 6, 'A-', 7, 'B+', 8, 'B-', 9, 'AB+', 10, 'AB-', 11, 'O+', 12, 'O-', 13, '-') GOL_DRH, DECODE (a.AGAMA, 1, 'ISLAM', 2, 'KRISTEN', 3, 'KATHOLIK', 4, 'HINDHU', 5, 'BUDHA', 6, 'KONG HUCHU', 7, 'LAINNYA') AGAMA, DECODE (a.PDDK_AKH, 1, 'TIDAK/BELUM SEKOLAH', 2, 'BELUM TAMAT SD/SEDERAJAT', 3, 'TAMAT SD/SEDERAJAT', 4, 'SLTP/SEDERAJAT', 5, 'SLTA/SEDERAJAT', 6, 'DIPLOMA I/II', 7, 'AKADEMI/DIPLOMA III/S. MUDA', 8, 'DIPLOMA IV/STRATA I', 9, 'STRATA II', 10, 'STRATA III') PDDK_AKH, UPPER (getPkrjn (a.JENIS_PKRJN)) JENIS_PKRJN, DECODE (a.PNYDNG_CCT, 1, 'CACAT FISIK', 2, 'CACAT NETRA', 3, 'CACAT RUNGU/WICARA', 4, 'CACAT MENTAL/JIWA', 5, 'CACAT FISIK DAN MENTAL', 6, 'CACAT LAINNYA') PNYDNG_CCT, DECODE (a.STAT_KWN, 1, 'BELUM KAWIN', 2, 'KAWIN', 3, 'CERAI HIDUP', 4, 'CERAI MATI') STAT_KWN, DECODE (a.STAT_HBKEL, 1, 'KEPALA KELUARGA', 2, 'SUAMI', 3, 'ISTRI', 4, 'ANAK', 5, 'MENANTU', 6, 'CUCU', 7, 'ORANGTUA', 8, 'MERTUA', 9, 'FAMILI LAIN', 10, 'PEMBANTU', 11, 'LAINNYA') stat_hbkel, DECODE (a.nik_ibu, '0', '-', NULL, '-', TO_CHAR (a.nik_ibu, '0000000000000000')) nik_ibu, UPPER (a.NAMA_LGKP_IBU) NAMA_LGKP_IBU, DECODE (a.NIK_AYAH, '0', '-', NULL, '-', TO_CHAR (a.NIK_AYAH, '0000000000000000')) NIK_AYAH, UPPER (a.NAMA_LGKP_AYAH) NAMA_LGKP_AYAH, UPPER (NVL (a.TMPT_SBL, '-')) TMPT_SBL, UPPER (b.alamat) alamat, DECODE (b.no_rt, NULL, '-', 0, '-', TRIM (TO_CHAR (b.no_rt, '000'))) no_rt, DECODE (b.no_rw, NULL, '-', 0, '-', TRIM (TO_CHAR (b.no_rw, '000'))) no_rw, UPPER (NVL (b.dusun, '-')) dusun, DECODE (b.KODE_POS,  NULL, '-',  0, '-',  b.KODE_POS) KODE_POS, NVL (b.TELP, '-') TELP, UPPER (getnamaprop (a.no_prop)) nama_prop, UPPER (getnamakab (a.no_kab, a.no_prop)) nama_kab, UPPER (getnamakec (a.no_kec, a.no_kab, a.no_prop)) nama_kec, UPPER (getnamakel (a.no_kel, a.no_kec, a.no_kab, a.no_prop)) nama_kel, NVL (TO_CHAR (a.no_kk, '0000000000000000'), '-') no_kk, DECODE (a.NO_PASPOR,  NULL, '-',  0, '-',  a.NO_PASPOR) NO_PASPOR, DECODE (a.TGL_AKH_PASPOR, NULL, '-', TO_CHAR (a.TGL_AKH_PASPOR, 'dd-mm-yyyy')) TGL_AKH_PASPOR, DECODE (a.NO_AKTA_LHR,  NULL, '-',  0, '-',  a.NO_AKTA_LHR) NO_AKTA_LHR, DECODE (a.NO_AKTA_KWN,  NULL, '-',  0, '-',  a.NO_AKTA_KWN) NO_AKTA_KWN, DECODE (a.TGL_KWN, NULL, '-', TO_CHAR (a.TGL_KWN, 'dd-mm-yyyy')) TGL_KWN, DECODE (a.NO_AKTA_CRAI,  NULL, '-',  0, '-',  a.NO_AKTA_CRAI) NO_AKTA_CRAI, DECODE (a.TGL_CRAI, NULL, '-', TO_CHAR (a.TGL_CRAI, 'dd-mm-yyyy')) TGL_CRAI FROM biodata_wni a, data_keluarga b WHERE a.no_kk = b.no_kk AND a.flag_status = 0 AND a.no_kk = :NOMORKK";
					$stmtmember = oci_parse($dbcon, $querymember);
					oci_bind_by_name($stmtmember, ":NOMORKK", $nokk);
					if (oci_execute($stmtmember)) {
						while ($rowsetmember = oci_fetch_array($stmtmember, OCI_RETURN_NULLS+OCI_ASSOC)) {
							$itemsmember[] = $rowsetmember;
						}
						if (!empty($itemsmember)) {
							$response = array("ajaxresult"=>"found","dataarray"=>$items,"memberarray"=>$itemsmember);
							header('Content-Type: application/json');
							echo json_encode($response);
							oci_free_statement($stmt);
							oci_close($dbcon);
							exit;
						} else {
							$response = array("ajaxresult"=>"found","dataarray"=>$items);
							header('Content-Type: application/json');
							echo json_encode($response);
							oci_free_statement($stmt);
							oci_close($dbcon);
							exit;
						}
					} else {
						$response = array("ajaxresult"=>"found","dataarray"=>$items);
						header('Content-Type: application/json');
						echo json_encode($response);
						oci_free_statement($stmt);
						oci_close($dbcon);
						exit;
					}
				} else {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"Data tidak ditemukan.");
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				}
			} else {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"Data tidak ditemukan.");
				header('Content-Type: application/json');
				echo json_encode($response);
				oci_free_statement($stmt);
				oci_close($dbcon);
				exit;
			}
		} elseif ($_GET['cmdx'] == "GET_ALL_PENDUDUK_KELURAHAN") {
			$query = "SELECT TO_CHAR (a.nik, '0000000000000000') nik, UPPER (a.nama_lgkp) nama_lgkp, UPPER (a.TMPT_LHR) TMPT_LHR, TO_CHAR (a.TGL_LHR, 'dd-mm-yyyy') TGL_LHR, DECODE (a.JENIS_KLMIN,  1, 'LAKI-LAKI',  2, 'PEREMPUAN') JENIS_KLMIN, DECODE (a.GOL_DRH, 1, 'A', 2, 'B', 3, 'AB', 4, 'O', 5, 'A+', 6, 'A-', 7, 'B+', 8, 'B-', 9, 'AB+', 10, 'AB-', 11, 'O+', 12, 'O-', 13, '-') GOL_DRH, DECODE (a.AGAMA, 1, 'ISLAM', 2, 'KRISTEN', 3, 'KATHOLIK', 4, 'HINDHU', 5, 'BUDHA', 6, 'KONG HUCHU', 7, 'LAINNYA') AGAMA, DECODE (a.PDDK_AKH, 1, 'TIDAK/BELUM SEKOLAH', 2, 'BELUM TAMAT SD/SEDERAJAT', 3, 'TAMAT SD/SEDERAJAT', 4, 'SLTP/SEDERAJAT', 5, 'SLTA/SEDERAJAT', 6, 'DIPLOMA I/II', 7, 'AKADEMI/DIPLOMA III/S. MUDA', 8, 'DIPLOMA IV/STRATA I', 9, 'STRATA II', 10, 'STRATA III') PDDK_AKH, UPPER (getPkrjn (a.JENIS_PKRJN)) JENIS_PKRJN, DECODE (a.PNYDNG_CCT, 1, 'CACAT FISIK', 2, 'CACAT NETRA', 3, 'CACAT RUNGU/WICARA', 4, 'CACAT MENTAL/JIWA', 5, 'CACAT FISIK DAN MENTAL', 6, 'CACAT LAINNYA') PNYDNG_CCT, DECODE (a.STAT_KWN, 1, 'BELUM KAWIN', 2, 'KAWIN', 3, 'CERAI HIDUP', 4, 'CERAI MATI') STAT_KWN, DECODE (a.STAT_HBKEL, 1, 'KEPALA KELUARGA', 2, 'SUAMI', 3, 'ISTRI', 4, 'ANAK', 5, 'MENANTU', 6, 'CUCU', 7, 'ORANGTUA', 8, 'MERTUA', 9, 'FAMILI LAIN', 10, 'PEMBANTU', 11, 'LAINNYA') stat_hbkel, DECODE (a.nik_ibu, '0', '-', NULL, '-', TO_CHAR (a.nik_ibu, '0000000000000000')) nik_ibu, UPPER (a.NAMA_LGKP_IBU) NAMA_LGKP_IBU, DECODE (a.NIK_AYAH, '0', '-', NULL, '-', TO_CHAR (a.NIK_AYAH, '0000000000000000')) NIK_AYAH, UPPER (a.NAMA_LGKP_AYAH) NAMA_LGKP_AYAH, UPPER (NVL (a.TMPT_SBL, '-')) TMPT_SBL, UPPER (b.alamat) alamat, DECODE (b.no_rt, NULL, '-', 0, '-', TRIM (TO_CHAR (b.no_rt, '000'))) no_rt, DECODE (b.no_rw, NULL, '-', 0, '-', TRIM (TO_CHAR (b.no_rw, '000'))) no_rw, UPPER (NVL (b.dusun, '-')) dusun, DECODE (b.KODE_POS,  NULL, '-',  0, '-',  b.KODE_POS) KODE_POS, NVL (b.TELP, '-') TELP, UPPER (getnamaprop (a.no_prop)) nama_prop, UPPER (getnamakab (a.no_kab, a.no_prop)) nama_kab, UPPER (getnamakec (a.no_kec, a.no_kab, a.no_prop)) nama_kec, UPPER (getnamakel (a.no_kel, a.no_kec, a.no_kab, a.no_prop)) nama_kel, NVL (TO_CHAR (a.no_kk, '0000000000000000'), '-') no_kk, DECODE (a.NO_PASPOR,  NULL, '-',  0, '-',  a.NO_PASPOR) NO_PASPOR, DECODE (a.TGL_AKH_PASPOR, NULL, '-', TO_CHAR (a.TGL_AKH_PASPOR, 'dd-mm-yyyy')) TGL_AKH_PASPOR, DECODE (a.NO_AKTA_LHR,  NULL, '-',  0, '-',  a.NO_AKTA_LHR) NO_AKTA_LHR, DECODE (a.NO_AKTA_KWN,  NULL, '-',  0, '-',  a.NO_AKTA_KWN) NO_AKTA_KWN, DECODE (a.TGL_KWN, NULL, '-', TO_CHAR (a.TGL_KWN, 'dd-mm-yyyy')) TGL_KWN, DECODE (a.NO_AKTA_CRAI,  NULL, '-',  0, '-',  a.NO_AKTA_CRAI) NO_AKTA_CRAI, DECODE (a.TGL_CRAI, NULL, '-', TO_CHAR (a.TGL_CRAI, 'dd-mm-yyyy')) TGL_CRAI FROM biodata_wni a, data_keluarga b WHERE a.no_kk = b.no_kk AND a.flag_status = 0 AND a.no_kec = :KODEKEC AND a.no_kel = :KODEKEL";
			$stmt = oci_parse($dbcon, $query);
			oci_bind_by_name($stmt, ":KODEKEC", $kode_kecamatan);
			oci_bind_by_name($stmt, ":KODEKEL", $kode_kelurahan);
			if (oci_execute($stmt)) {
				while ($rowset = oci_fetch_array($stmt, OCI_RETURN_NULLS+OCI_ASSOC)) {
					$items[] = $rowset;
				}
				if (!empty($items)) {
					$response = array("ajaxresult"=>"found","dataarray"=>$items);
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				} else {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"Data tidak ditemukan.");
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				}
			} else {
				$response = array("ajaxresult"=>"notfound");
				header('Content-Type: application/json');
				echo json_encode($response);
				oci_free_statement($stmt);
				oci_close($dbcon);
				exit;
			}
		} elseif ($_GET['cmdx'] == "GET_RW") {
			$arrKodex = $_GET['stridx'];
			$arrKODE = explode("|", $arrKodex);
			$chKECA = $arrKODE[0];
			$chKELU = $arrKODE[1];
			$query = "SELECT DISTINCT NO_RW FROM DATA_KELUARGA WHERE NO_PROP = :KODEPROV AND NO_KAB = :KODEKAB AND NO_KEC = :KODEKEC AND NO_KEL = :KODEKEL ORDER BY NO_RW";
			$stmt = oci_parse($dbcon, $query);
			oci_bind_by_name($stmt, ":KODEPROV", $chPROP);
			oci_bind_by_name($stmt, ":KODEKAB", $chKABU);
			oci_bind_by_name($stmt, ":KODEKEC", $chKECA);
			oci_bind_by_name($stmt, ":KODEKEL", $chKELU);
			if (oci_execute($stmt)) {
				while ($rowset = oci_fetch_array($stmt, OCI_RETURN_NULLS+OCI_ASSOC)) {
					$items[] = $rowset;
				}
				if (!empty($items)) {
					$response = array("ajaxresult"=>"found","dataarray"=>$items);
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				} else {
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				}
			} else {
				$response = array("ajaxresult"=>"notfound");
				header('Content-Type: application/json');
				echo json_encode($response);
				oci_free_statement($stmt);
				oci_close($dbcon);
				exit;
			}
		} elseif ($_GET['cmdx'] == "GET_DAFTAR_KELURAHAN") {
			$query = "SELECT C.NO_PROP,C.NO_KAB,C.NO_KEC,C.NAMA_KEC,L.NO_KEL,L.NAMA_KEL FROM SETUP_KEC C, SETUP_KEL L WHERE C.NO_PROP=L.NO_PROP AND C.NO_KAB=L.NO_KAB AND C.NO_KEC=L.NO_KEC AND C.NO_PROP = '33' AND C.NO_KAB = '71' ORDER BY NO_KEC,NO_KEL";
			$stmt = oci_parse($dbcon, $query);
			if (oci_execute($stmt)) {
				while ($rowset = oci_fetch_array($stmt, OCI_RETURN_NULLS+OCI_ASSOC)) {
					$items[] = $rowset;
				}
				if (!empty($items)) {
					$response = array("ajaxresult"=>"found","dataarray"=>$items);
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				} else {
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				}
			} else {
				$response = array("ajaxresult"=>"notfound");
				header('Content-Type: application/json');
				echo json_encode($response);
				oci_free_statement($stmt);
				oci_close($dbcon);
				exit;
			}
		} elseif ($_GET['cmdx'] == "BUILD_DATA_POTENSI_PEMUTAKHIRAN_AKTA_KELAHIRAN") {
			$NO_RW = $_GET['koderw'];
			$KODE_KEL = $_GET['kelurahan'];
			$arrKODE = explode("|", $KODE_KEL);
			$query = "SELECT B.NIK, B.NO_KK, B.NAMA_LGKP, B.TMPT_LHR, TO_CHAR(B.TGL_LHR,'yyyy-mm-dd') TGL_LHR, K.ALAMAT, K.NAMA_KEP, K.NO_RW, K.NO_RT FROM BIODATA_WNI B INNER JOIN DATA_KELUARGA K ON B.NO_KK = K.NO_KK WHERE B.AKTA_LHR = '1' AND B.NO_PROP = '33' AND B.NO_KAB = '71' AND B.NO_KEC = :KODEKEC AND B.NO_KEL = :KODEKEL AND K.NO_RW = :NORW ORDER BY NO_RW";
			$stmt = oci_parse($dbcon, $query);
			oci_bind_by_name($stmt, ":KODEKEC", $arrKODE[0]);
			oci_bind_by_name($stmt, ":KODEKEL", $arrKODE[1]);
			oci_bind_by_name($stmt, ":NORW", $NO_RW);
			if (oci_execute($stmt)) {
				while ($rowset = oci_fetch_array($stmt, OCI_RETURN_NULLS+OCI_ASSOC)) {
					$items[] = $rowset;
				}
				if (!empty($items)) {
					$response = array("ajaxresult"=>"found","dataarray"=>$items);
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				} else {
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				}
			} else {
				$response = array("ajaxresult"=>"notfound");
				header('Content-Type: application/json');
				echo json_encode($response);
				oci_free_statement($stmt);
				oci_close($dbcon);
				exit;
			}
		} elseif ($_GET['cmdx'] == "GET_DATA_DKBVSDP") {
			$tempARREXP = $_GET['areaidx'];
			$arrKODE = explode("|", $tempARREXP);
			$NO_RW = $_GET['arearw'];
			/* $query = "SELECT
	TO_CHAR (b.NIK, '0000000000000000') NIK,
	UPPER (b.NAMA_LGKP) NAMA_LGKP,
	UPPER (b.TMPT_LHR) TMPT_LHR,
	TO_CHAR (b.TGL_LHR, 'dd-mm-yyyy') TGL_LHR,
	DECODE (b.JENIS_KLMIN,  1, 'LAKI-LAKI',  2, 'PEREMPUAN') JENIS_KLMIN,
	DECODE (b.GOL_DRH,1, 'A',2, 'B',3, 'AB',4, 'O',5, 'A+',6, 'A-',7, 'B+',8, 'B-',9, 'AB+',10, 'AB-',11, 'O+',12, 'O-',13, '-') GOL_DRH,
	DECODE (b.AGAMA,1, 'ISLAM',2, 'KRISTEN',3, 'KATHOLIK',4, 'HINDHU',5, 'BUDHA',6, 'KONG HUCHU',7, 'LAINNYA') AGAMA,
	DECODE (b.PDDK_AKH,1, 'TIDAK/BELUM SEKOLAH',2, 'BELUM TAMAT SD/SEDERAJAT',3, 'TAMAT SD/SEDERAJAT',4, 'SLTP/SEDERAJAT',5, 'SLTA/SEDERAJAT',6, 'DIPLOMA I/II',7, 'AKADEMI/DIPLOMA III/S. MUDA',8, 'DIPLOMA IV/STRATA I',9, 'STRATA II',10, 'STRATA III') PDDK_AKH,
	UPPER (getPkrjn (b.JENIS_PKRJN)) JENIS_PKRJN,
	DECODE (b.PNYDNG_CCT,1, 'CACAT FISIK',2, 'CACAT NETRA',3, 'CACAT RUNGU/WICARA',4, 'CACAT MENTAL/JIWA',5, 'CACAT FISIK DAN MENTAL',6, 'CACAT LAINNYA') PNYDNG_CCT,
	DECODE (b.STAT_KWN,1, 'BELUM KAWIN',2, 'KAWIN',3, 'CERAI HIDUP',4, 'CERAI MATI') STAT_KWN,
	DECODE (b.STAT_HBKEL,1, 'KEPALA KELUARGA',2, 'SUAMI',3, 'ISTRI',4, 'ANAK',5, 'MENANTU',6, 'CUCU',7, 'ORANGTUA',8, 'MERTUA',9, 'FAMILI LAIN',10, 'PEMBANTU',11, 'LAINNYA') STAT_HBKEL,
	DECODE (b.NIK_IBU,'0', '-',NULL, '-',TO_CHAR (b.nik_ibu, '0000000000000000')) NIK_IBU,
	UPPER (b.NAMA_LGKP_IBU) NAMA_LGKP_IBU,
	DECODE (b.NIK_AYAH,'0', '-',NULL, '-',TO_CHAR (b.NIK_AYAH, '0000000000000000')) NIK_AYAH,
	UPPER (b.NAMA_LGKP_AYAH) NAMA_LGKP_AYAH,
	UPPER (NVL (b.TMPT_SBL, '-')) TMPT_SBL,
	UPPER (k.ALAMAT) ALAMAT,
	DECODE (k.NO_RT,NULL, '-',0, '-',TRIM (TO_CHAR (k.NO_RT, '000'))) NO_RT,
	DECODE (k.NO_RW,NULL, '-',0, '-',TRIM (TO_CHAR (k.NO_RW, '000'))) NO_RW,
	UPPER (NVL (k.DUSUN, '-')) DUSUN,
	DECODE (k.KODE_POS, NULL, '-', 0, '-',  k.KODE_POS) KODE_POS,
	NVL (k.TELP, '-') TELP,
	UPPER (getnamaprop (b.NO_PROP)) NAMA_PROP,
	UPPER (getnamakab (b.NO_KAB, b.NO_PROP)) NAMA_KAB,
	UPPER (getnamakec (b.NO_KEC, b.NO_KAB, b.NO_PROP)) NAMA_KEC,
	UPPER (getnamakel (b.NO_KEL, b.NO_KEC, b.NO_KAB, b.NO_PROP)) NAMA_KEL,
	NVL (TO_CHAR (b.NO_KK, '0000000000000000'), '-') NO_KK,
	DECODE (b.NO_PASPOR, NULL, '-', 0, '-', b.NO_PASPOR) NO_PASPOR,
	DECODE (b.TGL_AKH_PASPOR, NULL, '-', TO_CHAR(b.TGL_AKH_PASPOR, 'dd-mm-yyyy')) TGL_AKH_PASPOR,
	DECODE (b.NO_AKTA_LHR, NULL, '-', 0, '-', b.NO_AKTA_LHR) NO_AKTA_LHR,
	DECODE (b.NO_AKTA_KWN, NULL, '-', 0, '-', b.NO_AKTA_KWN) NO_AKTA_KWN,
	DECODE (b.TGL_KWN, NULL, '-', TO_CHAR(b.TGL_KWN, 'dd-mm-yyyy')) TGL_KWN,
	DECODE (b.NO_AKTA_CRAI, NULL, '-', 0, '-',  b.NO_AKTA_CRAI) NO_AKTA_CRAI,
	DECODE (b.TGL_CRAI, NULL, '-', TO_CHAR(b.TGL_CRAI, 'dd-mm-yyyy')) TGL_CRAI

FROM BIODATA_WNI b INNER JOIN DATA_KELUARGA k on b.NO_KK = k.NO_KK

WHERE b.NIK IN (
	SELECT
		NIK
	FROM BIODATA_WNI
	WHERE nvl(TO_CHAR(TGL_ENTRI, 'yyyymmdd'),'20100101') < '20161116' and nvl(TO_CHAR(TGL_UBAH, 'yyyymmdd'),'20100101') < '20161116'
		minus select d.nik from  datang_header h inner join  datang_detail d on h.no_datang=d.no_datang where h.KLASIFIKASI_PINDAH > 3 and to_char(h.tgl_datang,'yyyymmdd')>'20161116' MINUS
	SELECT NIK FROM siakel.BIODATA_WNI_201602) AND b.FLAG_STATUS = 0 AND b.NO_KEC = :KODEKEC AND b.NO_KEL = :KODEKEL AND k.NO_RW = :NORW ORDER BY NO_KK,NO_RW,NO_RT,NAMA_KEP"; */
			$query = "SELECT
	TO_CHAR (b.NIK, '0000000000000000') NIK,
	UPPER (b.NAMA_LGKP) NAMA_LGKP,
	UPPER (b.TMPT_LHR) TMPT_LHR,
	TO_CHAR (b.TGL_LHR, 'dd-mm-yyyy') TGL_LHR,
	DECODE (b.JENIS_KLMIN,  1, 'LAKI-LAKI',  2, 'PEREMPUAN') JENIS_KLMIN,
	DECODE (b.GOL_DRH,1, 'A',2, 'B',3, 'AB',4, 'O',5, 'A+',6, 'A-',7, 'B+',8, 'B-',9, 'AB+',10, 'AB-',11, 'O+',12, 'O-',13, '-') GOL_DRH,
	DECODE (b.AGAMA,1, 'ISLAM',2, 'KRISTEN',3, 'KATHOLIK',4, 'HINDHU',5, 'BUDHA',6, 'KONG HUCHU',7, 'LAINNYA') AGAMA,
	DECODE (b.PDDK_AKH,1, 'TIDAK/BELUM SEKOLAH',2, 'BELUM TAMAT SD/SEDERAJAT',3, 'TAMAT SD/SEDERAJAT',4, 'SLTP/SEDERAJAT',5, 'SLTA/SEDERAJAT',6, 'DIPLOMA I/II',7, 'AKADEMI/DIPLOMA III/S. MUDA',8, 'DIPLOMA IV/STRATA I',9, 'STRATA II',10, 'STRATA III') PDDK_AKH,
	UPPER (getPkrjn (b.JENIS_PKRJN)) JENIS_PKRJN,
	DECODE (b.PNYDNG_CCT,1, 'CACAT FISIK',2, 'CACAT NETRA',3, 'CACAT RUNGU/WICARA',4, 'CACAT MENTAL/JIWA',5, 'CACAT FISIK DAN MENTAL',6, 'CACAT LAINNYA') PNYDNG_CCT,
	DECODE (b.STAT_KWN,1, 'BELUM KAWIN',2, 'KAWIN',3, 'CERAI HIDUP',4, 'CERAI MATI') STAT_KWN,
	DECODE (b.STAT_HBKEL,1, 'KEPALA KELUARGA',2, 'SUAMI',3, 'ISTRI',4, 'ANAK',5, 'MENANTU',6, 'CUCU',7, 'ORANGTUA',8, 'MERTUA',9, 'FAMILI LAIN',10, 'PEMBANTU',11, 'LAINNYA') STAT_HBKEL,
	DECODE (b.NIK_IBU,'0', '-',NULL, '-',TO_CHAR (b.nik_ibu, '0000000000000000')) NIK_IBU,
	UPPER (b.NAMA_LGKP_IBU) NAMA_LGKP_IBU,
	DECODE (b.NIK_AYAH,'0', '-',NULL, '-',TO_CHAR (b.NIK_AYAH, '0000000000000000')) NIK_AYAH,
	UPPER (b.NAMA_LGKP_AYAH) NAMA_LGKP_AYAH,
	UPPER (NVL (b.TMPT_SBL, '-')) TMPT_SBL,
	UPPER (k.ALAMAT) ALAMAT,
	DECODE (k.NO_RT,NULL, '-',0, '-',TRIM (TO_CHAR (k.NO_RT, '000'))) NO_RT,
	DECODE (k.NO_RW,NULL, '-',0, '-',TRIM (TO_CHAR (k.NO_RW, '000'))) NO_RW,
	UPPER (NVL (k.DUSUN, '-')) DUSUN,
	DECODE (k.KODE_POS, NULL, '-', 0, '-',  k.KODE_POS) KODE_POS,
	NVL (k.TELP, '-') TELP,
	UPPER (getnamaprop (b.NO_PROP)) NAMA_PROP,
	UPPER (getnamakab (b.NO_KAB, b.NO_PROP)) NAMA_KAB,
	UPPER (getnamakec (b.NO_KEC, b.NO_KAB, b.NO_PROP)) NAMA_KEC,
	UPPER (getnamakel (b.NO_KEL, b.NO_KEC, b.NO_KAB, b.NO_PROP)) NAMA_KEL,
	NVL (TO_CHAR (b.NO_KK, '0000000000000000'), '-') NO_KK,
	DECODE (b.NO_PASPOR, NULL, '-', 0, '-', b.NO_PASPOR) NO_PASPOR,
	DECODE (b.TGL_AKH_PASPOR, NULL, '-', TO_CHAR(b.TGL_AKH_PASPOR, 'dd-mm-yyyy')) TGL_AKH_PASPOR,
	DECODE (b.NO_AKTA_LHR, NULL, '-', 0, '-', b.NO_AKTA_LHR) NO_AKTA_LHR,
	DECODE (b.NO_AKTA_KWN, NULL, '-', 0, '-', b.NO_AKTA_KWN) NO_AKTA_KWN,
	DECODE (b.TGL_KWN, NULL, '-', TO_CHAR(b.TGL_KWN, 'dd-mm-yyyy')) TGL_KWN,
	DECODE (b.NO_AKTA_CRAI, NULL, '-', 0, '-',  b.NO_AKTA_CRAI) NO_AKTA_CRAI,
	DECODE (b.TGL_CRAI, NULL, '-', TO_CHAR(b.TGL_CRAI, 'dd-mm-yyyy')) TGL_CRAI

FROM BIODATA_WNI b INNER JOIN DATA_KELUARGA k on b.NO_KK = k.NO_KK

WHERE b.NIK IN      
     (SELECT b.NIK FROM BIODATA_WNI b left join siakel.BIODATA_WNI_201701 s on b.nik=s.nik  WHERE s.nik is null and b.flag_status=0
      minus select  NIK FROM BIODATA_WNI where  nvl(TO_CHAR(TGL_ENTRI, 'yyyymmdd'),'20100101') > '20170530' or nvl(TO_CHAR(TGL_UBAH, 'yyyymmdd'),'20100101') > '20170530'  
      minus select d.nik from datang_header h inner join datang_detail d on h.no_datang=d.no_datang where h.KLASIFIKASI_PINDAH > 3 and to_char(h.tgl_datang,'yyyymmdd')>'20170530')
     And b.flag_status=0 AND b.NO_KEC = :KODEKEC AND b.NO_KEL = :KODEKEL AND k.NO_RW = :NORW ORDER BY NO_KK,NO_RW,NO_RT,NAMA_KEP";
			$stmt = oci_parse($dbcon, $query);
			oci_bind_by_name($stmt, ":KODEKEC", $arrKODE[0]);
			oci_bind_by_name($stmt, ":KODEKEL", $arrKODE[1]);
			oci_bind_by_name($stmt, ":NORW", $NO_RW);
			if (oci_execute($stmt)) {
				while ($rowset = oci_fetch_array($stmt, OCI_RETURN_NULLS+OCI_ASSOC)) {
					$items[] = $rowset;
				}
				if (!empty($items)) {
					$response = array("ajaxresult"=>"found","dataarray"=>$items);
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				} else {
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				}
			} else {
				$response = array("ajaxresult"=>"notfound");
				header('Content-Type: application/json');
				echo json_encode($response);
				oci_free_statement($stmt);
				oci_close($dbcon);
				exit;
			}
		} elseif ($_GET['cmdx'] == "INDIVIDUAL_SEARCH_AKTIVASI_DATA") {
			if (is_numeric($_GET['strquery'])) {
				$query = "SELECT TO_CHAR (NO_KK, '0000000000000000') NO_KK, TO_CHAR (NIK, '0000000000000000') NIK, NAMA_LGKP, TMPT_LHR, TGL_LHR, JENIS_KLMIN, AKTIVASI, KETERANGAN, TGL_AKTIVASI FROM siakel.ILLEGAL1703 WHERE CAST(NIK AS VARCHAR(16)) = :SEARCHEDSTR";
				$stmt = oci_parse($dbcon, $query);
				$strsearch = $_GET['strquery'];
				oci_bind_by_name($stmt, ":SEARCHEDSTR", $strsearch);
				if (oci_execute($stmt)) {
					while ($rowset = oci_fetch_array($stmt, OCI_RETURN_NULLS+OCI_ASSOC)) {
						$NO_KK = $rowset['NO_KK'];
						$NIK = $rowset['NIK'];
						$NAMA_LGKP = $rowset['NAMA_LGKP'];
						$TMPT_LHR = $rowset['TMPT_LHR'];
						$TGL_LHR = $rowset['TGL_LHR'];
						$JENIS_KLMIN = $rowset['JENIS_KLMIN'];
						$AKTIVASI = $rowset['AKTIVASI'];
						$KETERANGAN = $rowset['KETERANGAN'];
						$TGL_AKTIVASI = $rowset['TGL_AKTIVASI'];
					}
					$response = array("ajaxresult"=>"found","modedata"=>"single","NO_KK"=>$NO_KK,"NIK"=>$NIK,"NAMA_LGKP"=>$NAMA_LGKP,"TMPT_LHR"=>$TMPT_LHR,"TGL_LHR"=>$TGL_LHR,"JENIS_KLMIN"=>$JENIS_KLMIN,"AKTIVASI"=>$AKTIVASI,"KETERANGAN"=>$KETERANGAN,"TGL_AKTIVASI"=>$TGL_AKTIVASI);
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				} else {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"Data tidak ditemukan.");
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				}
			} else {
				$query = "SELECT TO_CHAR (a.NO_KK, '0000000000000000') NO_KK, TO_CHAR (a.NIK, '0000000000000000') NIK, a.NAMA_LGKP, a.TMPT_LHR, a.TGL_LHR, a.JENIS_KLMIN, NVL(a.AKTIVASI, '0') AKTIVASI, NVL(a.KETERANGAN, '-') KETERANGAN, NVL(a.TGL_AKTIVASI, SYSDATE) TGL_AKTIVASI FROM siakel.ILLEGAL1703 a WHERE a.NAMA_LGKP LIKE :SEARCHEDSTR";
				$stmt = oci_parse($dbcon, $query);
				$strsearch = strval('%'.strtoupper($_GET['strquery']).'%');
				oci_bind_by_name($stmt, ":SEARCHEDSTR", $strsearch);
				if (oci_execute($stmt)) {
					while ($rowset = oci_fetch_array($stmt, OCI_RETURN_NULLS+OCI_ASSOC)) {
						$items[] = $rowset;
					}
					if (!empty($items)) {
						$response = array("ajaxresult"=>"found","modedata"=>"multiple","dataarray"=>$items);
						header('Content-Type: application/json');
						echo json_encode($response);
						oci_free_statement($stmt);
						oci_close($dbcon);
						exit;
					} else {
						$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"Data tidak ditemukan.");
						header('Content-Type: application/json');
						echo json_encode($response);
						oci_free_statement($stmt);
						oci_close($dbcon);
						exit;
					}
				} else {
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				}
			}
		} elseif ($_GET['cmdx'] == "zz") {
			$query = "";
			$stmt = oci_parse($dbcon, $query);
			$nik = $_GET['nik'];
			oci_bind_by_name($stmt, ":SEARCHEDNIK", $nik);
			if (oci_execute($stmt)) {
				while ($rowset = oci_fetch_array($stmt, OCI_RETURN_NULLS+OCI_ASSOC)) {
					$items[] = $rowset;
				}
				$response = array("ajaxresult"=>"found","dataarray"=>$items);
				header('Content-Type: application/json');
				echo json_encode($response);
				oci_free_statement($stmt);
				oci_close($dbcon);
				exit;
			} else {
				$response = array("ajaxresult"=>"notfound");
				header('Content-Type: application/json');
				echo json_encode($response);
				oci_free_statement($stmt);
				oci_close($dbcon);
				exit;
			}
		} else {

		}
	}
} else {

}
?>
