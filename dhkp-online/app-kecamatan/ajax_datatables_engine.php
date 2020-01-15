<?php
/* ERROR DEBUG HANDLE */
error_reporting(E_ALL);
ini_set("display_errors", 1);
/* SESSION START */
session_start();
$txsessionid = session_id();
/* CONFIGURATION */
require_once("../einstellung.php");
require_once("../variablen.php");
require_once("../funktion.php");
header("Access-Control-Allow-Origin: *");
if (isset($_GET['cmdx'])){
	/*----------------------------------------------------------------
	 * RETRIEVE DATA PETUGAS PENAGIHAN BERDASARKAN KELURAHAN TERTENTU
	 * ---------------------------------------------------------------*/
	if ($_GET['cmdx'] == "GET_PETUGAS_PENAGIHAN") {
			/* --- $kode_kelurahan = $_SESSION['desaXweb']['KD_KELURAHAN']; */
			try {
				/* connection setting */
				$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$stmt = $dbcon->prepare("SELECT * FROM appx_desa_petugas_pungut WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND STATUS = 1 ORDER BY KD_KECAMATAN,KD_KELURAHAN");
				/* @Dion: Kode Desa/Kelurahan saja belum unik. Bisa saja ada kode desa yang sama.
				 * Biar sekalian unik, sertakan KODE PROVINSI, KABUPATEN, KECAMATAN dan DESA.
				 * Kemudian, untuk kode2 di atas, ambil saja dari session. */
				$stmt->bindValue(":kdprov", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
				$stmt->bindValue(":kdkab", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
				$stmt->bindValue(":kdkec", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
				if($stmt->execute()) {					
					$response = array("ajaxresult"=>"found","datarows"=>$stmt->fetchAll(PDO::FETCH_CLASS));
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				} else {
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
			
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"undefined","ajaxmessage"=>"".$e->getMessage()."");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
	/*----------------------------------------------------------------
	 * RETRIEVE DATA MATERI DISTRIBUSI SPPT/NOP KE PETUGAS PEMUNGUT
	 * ---------------------------------------------------------------*/
    } elseif ($_GET['cmdx'] == "GET_MATERI_DISTRIBUSI_SPPT") {
    	$searchscope = $_GET['desaidx'];
			$arrDesaIDX = explode("|", $searchscope);
			$KD_KELURAHAN = $arrDesaIDX[3];
			try {
				$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				/* counting data */
				$stmt_cnt = $dbcon->prepare("SELECT COUNT(*) as foundrows FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND PETUGASIDX = 0");
				$stmt_cnt->bindValue(":kdprov", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
				$stmt_cnt->bindValue(":kdkab", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
				$stmt_cnt->bindValue(":kdkec", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
				$stmt_cnt->bindValue(":kddesa", $KD_KELURAHAN, PDO::PARAM_STR);
				$stmt_cnt->bindValue(":thnpajak", $_SESSION['tahunPAJAK'], PDO::PARAM_STR);
				if ($stmt_cnt->execute()) {
					while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
						$rowsfound = $rowset_cnt['foundrows'];
					}
					if (intval($rowsfound)>0) {
						$stmt = $dbcon->prepare("SELECT * FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND PETUGASIDX = 0");
						$stmt->bindValue(":kdprov", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
						$stmt->bindValue(":kdkab", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
						$stmt->bindValue(":kdkec", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
						$stmt->bindValue(":kddesa", $KD_KELURAHAN, PDO::PARAM_STR);
						$stmt->bindValue(":thnpajak", $_SESSION['tahunPAJAK'], PDO::PARAM_STR);
						$stmt->execute();
						while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
							$items[] = $rowset;
						}
						$response = array("ajaxresult"=>"found","dataarray"=>$items);
						header('Content-Type: application/json');
						echo json_encode($response);
						$dbcon = null;
						exit;
					} else {
						$response = array("ajaxresult"=>"notfound");
						header('Content-Type: application/json');
						echo json_encode($response);
						$dbcon = null;
						exit;
					}
				} else {
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"".$e->getMessage()."");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
    } elseif ($_GET['cmdx'] == "GET_REKAPITULASI_DISTRIBUSI_SPPT_UNFILTERED") {
			$kode_propinsi = $_SESSION['desaXweb']['KD_PROPINSI'];
			$kode_dati2 = $_SESSION['desaXweb']['KD_DATI2'];
			$kode_kecamatan = $_SESSION['desaXweb']['KD_KECAMATAN'];
			$kode_kelurahan = $_SESSION['desaXweb']['KD_KELURAHAN'];
			$namaKecamatan = $_SESSION['desaXweb']['NM_KECAMATAN'];
			$namaKelurahan = $_SESSION['desaXweb']['NM_KELURAHAN'];
			$thnsppt = $_SESSION['tahunPAJAK'];
			try {
				$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				/* counting data */
				$stmt_cnt = $dbcon->prepare("SELECT COUNT(*) as foundrows FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND PETUGASIDX > 0");
				$stmt_cnt->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
				$stmt_cnt->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
				$stmt_cnt->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
				$stmt_cnt->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
				$stmt_cnt->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
				if ($stmt_cnt->execute()) {
					while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
						$rowsfound = $rowset_cnt['foundrows'];
					}
					if (intval($rowsfound)>0) {
						try {
							$dbconbppkad = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
							$dbconbppkad->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							/* counting data */
							$stmt_cnt_personil = $dbconbppkad->prepare("SELECT COUNT(*) as foundpetugas FROM appx_desa_petugas_pungut WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa");
							$stmt_cnt_personil->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
							$stmt_cnt_personil->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
							$stmt_cnt_personil->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
							$stmt_cnt_personil->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
							if ($stmt_cnt_personil->execute()) {
								while($rowset_cnt_personil = $stmt_cnt_personil->fetch(PDO::FETCH_ASSOC)){
									$countpersonil = $rowset_cnt_personil['foundpetugas'];
								}
								$dbconbppkad = null;
							} else {
								$countpersonil = 0;
								$dbconbppkad = null;
							}
						} catch (PDOException $ep) {
							$countpersonil = 0;
							$dbconbppkad = null;
						}
						/* ====================================== */
						$stmt_summary = $dbcon->prepare("SELECT 
	COUNT(*) AS totalcountsppt, 
	COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprova 
			AND KD_DATI2 = :kdkaba 
			AND KD_KECAMATAN = :kdkeca 
			AND KD_KELURAHAN = :kddesaa 
			AND THN_PAJAK_SPPT = :thnpajaka) AS countallpenetapan, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovb 
			AND KD_DATI2 = :kdkabb 
			AND KD_KECAMATAN = :kdkecb 
			AND KD_KELURAHAN = :kddesab 
			AND THN_PAJAK_SPPT = :thnpajakb 
			AND TGL_TERBIT_SPPT = CONCAT(:thnpajakbb,'-01-02')) AS countdefaultpenetapan, 
	(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovc 
			AND KD_DATI2 = :kdkabc 
			AND KD_KECAMATAN = :kdkecc 
			AND KD_KELURAHAN = :kddesac 
			AND THN_PAJAK_SPPT = :thnpajakc 
			AND TGL_TERBIT_SPPT = CONCAT(:thnpajakcc,'-01-02')) AS sumdefaultpenetapan, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovd 
			AND KD_DATI2 = :kdkabd 
			AND KD_KECAMATAN = :kdkecd 
			AND KD_KELURAHAN = :kddesad 
			AND THN_PAJAK_SPPT = :thnpajakd 
			AND TGL_TERBIT_SPPT != CONCAT(:thnpajakdd,'-01-02')) AS countlaterpenetapan, 
	(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprove 
			AND KD_DATI2 = :kdkabe 
			AND KD_KECAMATAN = :kdkece 
			AND KD_KELURAHAN = :kddesae 
			AND THN_PAJAK_SPPT = :thnpajake 
			AND TGL_TERBIT_SPPT != CONCAT(:thnpajakee,'-01-02')) AS sumlaterpenetapan, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovf 
			AND KD_DATI2 = :kdkabf 
			AND KD_KECAMATAN = :kdkecf 
			AND KD_KELURAHAN = :kddesaf 
			AND THN_PAJAK_SPPT = :thnpajakf 
			AND PETUGASIDX = 0) AS countunassigned, 
	(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovg 
			AND KD_DATI2 = :kdkabg 
			AND KD_KECAMATAN = :kdkecg 
			AND KD_KELURAHAN = :kddesag 
			AND THN_PAJAK_SPPT = :thnpajakg 
			AND PETUGASIDX = 0) AS sumunassigned, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovh 
			AND KD_DATI2 = :kdkabh 
			AND KD_KECAMATAN = :kdkech 
			AND KD_KELURAHAN = :kddesah 
			AND THN_PAJAK_SPPT = :thnpajakh 
			AND PETUGASIDX > 0) AS countassigned, 
	(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovi 
			AND KD_DATI2 = :kdkabi 
			AND KD_KECAMATAN = :kdkeci 
			AND KD_KELURAHAN = :kddesai 
			AND THN_PAJAK_SPPT = :thnpajaki 
			AND PETUGASIDX > 0) AS sumassigned, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovj 
			AND KD_DATI2 = :kdkabj 
			AND KD_KECAMATAN = :kdkecj 
			AND KD_KELURAHAN = :kddesaj 
			AND THN_PAJAK_SPPT = :thnpajakj 
			AND JML_SPPT_YG_DIBAYAR = 0 
			AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS countunpaiddesa, 
	(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovk 
			AND KD_DATI2 = :kdkabk 
			AND KD_KECAMATAN = :kdkeck 
			AND KD_KELURAHAN = :kddesak 
			AND THN_PAJAK_SPPT = :thnpajakk 
			AND JML_SPPT_YG_DIBAYAR = 0 
			AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS sumunpaiddesa, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovl 
			AND KD_DATI2 = :kdkabl 
			AND KD_KECAMATAN = :kdkecl 
			AND KD_KELURAHAN = :kddesal 
			AND THN_PAJAK_SPPT = :thnpajakl 
			AND JML_SPPT_YG_DIBAYAR > 0 
			AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS countpaiddesa, 
	(SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovm 
			AND KD_DATI2 = :kdkabm 
			AND KD_KECAMATAN = :kdkecm 
			AND KD_KELURAHAN = :kddesam 
			AND THN_PAJAK_SPPT = :thnpajakm 
			AND JML_SPPT_YG_DIBAYAR > 0 
			AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS sumpaiddesa, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovn 
			AND KD_DATI2 = :kdkabn 
			AND KD_KECAMATAN = :kdkecn 
			AND KD_KELURAHAN = :kddesan 
			AND THN_PAJAK_SPPT = :thnpajakn 
			AND JML_SPPT_YG_DIBAYAR = 0 
			AND TGL_PEMBAYARAN_SPPT = '1900-01-01' 
			AND PETUGASIDX = 0) AS countunpaidunassigned, 
	(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovo 
			AND KD_DATI2 = :kdkabo 
			AND KD_KECAMATAN = :kdkeco 
			AND KD_KELURAHAN = :kddesao 
			AND THN_PAJAK_SPPT = :thnpajako 
			AND JML_SPPT_YG_DIBAYAR = 0 
			AND TGL_PEMBAYARAN_SPPT = '1900-01-01' 
			AND PETUGASIDX = 0) AS sumunpaidunassigned, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovp 
			AND KD_DATI2 = :kdkabp 
			AND KD_KECAMATAN = :kdkecp 
			AND KD_KELURAHAN = :kddesap 
			AND THN_PAJAK_SPPT = :thnpajakp 
			AND JML_SPPT_YG_DIBAYAR > 0 
			AND TGL_PEMBAYARAN_SPPT != '1900-01-01' 
			AND PETUGASIDX = 0) AS countpaidunassigned, 
	(SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovq 
			AND KD_DATI2 = :kdkabq 
			AND KD_KECAMATAN = :kdkecq 
			AND KD_KELURAHAN = :kddesaq 
			AND THN_PAJAK_SPPT = :thnpajakq 
			AND JML_SPPT_YG_DIBAYAR > 0 
			AND TGL_PEMBAYARAN_SPPT != '1900-01-01' 
			AND PETUGASIDX = 0) AS sumpaidunassigned, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovr 
			AND KD_DATI2 = :kdkabr 
			AND KD_KECAMATAN = :kdkecr 
			AND KD_KELURAHAN = :kddesar 
			AND THN_PAJAK_SPPT = :thnpajakr 
			AND JML_SPPT_YG_DIBAYAR = 0 
			AND TGL_PEMBAYARAN_SPPT = '1900-01-01' 
			AND PETUGASIDX > 0) AS countunpaidassigned, 
	(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovs 
			AND KD_DATI2 = :kdkabs 
			AND KD_KECAMATAN = :kdkecs 
			AND KD_KELURAHAN = :kddesas 
			AND THN_PAJAK_SPPT = :thnpajaks 
			AND JML_SPPT_YG_DIBAYAR = 0 
			AND TGL_PEMBAYARAN_SPPT = '1900-01-01' 
			AND PETUGASIDX > 0) AS sumunpaidassigned, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovt 
			AND KD_DATI2 = :kdkabt 
			AND KD_KECAMATAN = :kdkect 
			AND KD_KELURAHAN = :kddesat 
			AND THN_PAJAK_SPPT = :thnpajakt 
			AND JML_SPPT_YG_DIBAYAR > 0 
			AND TGL_PEMBAYARAN_SPPT != '1900-01-01' 
			AND PETUGASIDX > 0) AS countpaidassigned, 
	(SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovu 
			AND KD_DATI2 = :kdkabu 
			AND KD_KECAMATAN = :kdkecu 
			AND KD_KELURAHAN = :kddesau 
			AND THN_PAJAK_SPPT = :thnpajaku 
			AND JML_SPPT_YG_DIBAYAR > 0 
			AND TGL_PEMBAYARAN_SPPT != '1900-01-01' 
			AND PETUGASIDX > 0) AS sumpaidassigned 
FROM 
	sppt_data 
WHERE 
	KD_PROPINSI = :kdprov 
	AND KD_DATI2 = :kdkab 
	AND KD_KECAMATAN = :kdkec 
	AND KD_KELURAHAN = :kddesa 
	AND THN_PAJAK_SPPT = :thnpajak");
						$stmt_summary->bindValue(":kdprova", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkaba", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkeca", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesaa", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajaka", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovb", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabb", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecb", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesab", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakb", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakbb", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovc", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabc", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecc", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesac", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakc", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakcc", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovd", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabd", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecd", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesad", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakd", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakdd", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprove", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabe", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkece", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesae", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajake", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakee", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovf", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabf", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecf", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesaf", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakf", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovg", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabg", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecg", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesag", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakg", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovh", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabh", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkech", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesah", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakh", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovi", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabi", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkeci", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesai", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajaki", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovj", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabj", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecj", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesaj", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakj", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovk", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabk", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkeck", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesak", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakk", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovl", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabl", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecl", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesal", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakl", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovm", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabm", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecm", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesam", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakm", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovn", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabn", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecn", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesan", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakn", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovo", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabo", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkeco", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesao", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajako", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovp", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabp", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecp", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesap", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakp", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovq", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabq", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecq", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesaq", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakq", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovr", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabr", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecr", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesar", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakr", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovs", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabs", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecs", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesas", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajaks", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovt", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabt", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkect", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesat", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakt", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovu", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabu", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecu", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesau", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajaku", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
						if ($stmt_summary->execute()) {
							while($rowset_summary = $stmt_summary->fetch(PDO::FETCH_ASSOC)){
								$totalcountsppt = $rowset_summary['totalcountsppt']; 
								$totalsumsppt = $rowset_summary['totalsumsppt']; 
								$countallpenetapan = $rowset_summary['countallpenetapan'];
								$countdefaultpenetapan = $rowset_summary['countdefaultpenetapan']; 
								$sumdefaultpenetapan = $rowset_summary['sumdefaultpenetapan'];
								$countlaterpenetapan = $rowset_summary['countlaterpenetapan']; 
								$sumlaterpenetapan = $rowset_summary['sumlaterpenetapan'];
								$countunassigned = $rowset_summary['countunassigned']; 
								$sumunassigned = $rowset_summary['sumunassigned'];
								$countassigned = $rowset_summary['countassigned']; 
								$sumassigned = $rowset_summary['sumassigned'];
								$countunpaiddesa = $rowset_summary['countunpaiddesa']; 
								$sumunpaiddesa = $rowset_summary['sumunpaiddesa'];
								$countpaiddesa = $rowset_summary['countpaiddesa']; 
								$sumpaiddesa = $rowset_summary['sumpaiddesa'];
								$countunpaidunassigned = $rowset_summary['countunpaidunassigned']; 
								$sumunpaidunassigned = $rowset_summary['sumunpaidunassigned'];
								$countpaidunassigned = $rowset_summary['countpaidunassigned']; 
								$sumpaidunassigned = $rowset_summary['sumpaidunassigned'];
								$countunpaidassigned = $rowset_summary['countunpaidassigned'];
								$sumunpaidassigned = $rowset_summary['sumunpaidassigned'];
								$countpaidassigned = $rowset_summary['countpaidassigned'];
								$sumpaidassigned = $rowset_summary['sumpaidassigned'];
							}
							/* select all assigned */
							$stmt = $dbcon->prepare("SELECT CONCAT(KD_PROPINSI,'.',KD_DATI2,'.',KD_KECAMATAN,'.',KD_KELURAHAN,'.',KD_BLOK,'-',NO_URUT,'-',KD_JNS_OP) AS NOP,THN_PAJAK_SPPT,NM_WP_SPPT,CONCAT(JLN_WP_SPPT,', ',BLOK_KAV_NO_WP_SPPT,', RT ',RT_WP_SPPT,'/ RW ',RW_WP_SPPT) AS ALAMAT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,TGL_TERBIT_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,PETUGASIDX,NAMAPETUGAS FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND PETUGASIDX > 0");
							$stmt->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
							$stmt->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
							$stmt->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
							$stmt->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
							$stmt->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
							$stmt->execute();
							while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
								$items[] = $rowset;
							}
							$response = array(
								"ajaxresult"=>"found",
								"countpersonil"=>$countpersonil,
								"namakecamatan"=>$namaKecamatan,
								"namakelurahan"=>$namaKelurahan,
								"totalcountsppt"=>$totalcountsppt,
								"totalsumsppt"=>$totalsumsppt,
								"countallpenetapan"=>$countallpenetapan,
								"countdefaultpenetapan"=>$countdefaultpenetapan,
								"sumdefaultpenetapan"=>$sumdefaultpenetapan,
								"countlaterpenetapan"=>$countlaterpenetapan,
								"sumlaterpenetapan"=>$sumlaterpenetapan,
								"countunassigned"=>$countunassigned,
								"sumunassigned"=>$sumunassigned,
								"countassigned"=>$countassigned,
								"sumassigned"=>$sumassigned,
								"countunpaiddesa"=>$countunpaiddesa,
								"sumunpaiddesa"=>$sumunpaiddesa,
								"countpaiddesa"=>$countpaiddesa,
								"sumpaiddesa"=>$sumpaiddesa,
								"countunpaidunassigned"=>$countunpaidunassigned,
								"sumunpaidunassigned"=>$sumunpaidunassigned,
								"countpaidunassigned"=>$countpaidunassigned,
								"sumpaidunassigned"=>$sumpaidunassigned,
								"countunpaidassigned"=>$countunpaidassigned,
								"sumunpaidassigned"=>$sumunpaidassigned,
								"countpaidassigned"=>$countpaidassigned,
								"sumpaidassigned"=>$sumpaidassigned,
								"dataarray"=>$items);
							header('Content-Type: application/json');
							echo json_encode($response);
							$dbcon = null;
							exit;
						} else {
							$response = array("ajaxresult"=>"notfound");
							header('Content-Type: application/json');
							echo json_encode($response);
							$dbcon = null;
							exit;
						}
					} else {
						$response = array("ajaxresult"=>"notfound");
						header('Content-Type: application/json');
						echo json_encode($response);
						$dbcon = null;
						exit;
					}
				} else {
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"".$e->getMessage()."");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
    } elseif ($_GET['cmdx'] == "GET_REKAPITULASI_DISTRIBUSI_SPPT_FILTERED") {
			$kode_propinsi = $_SESSION['desaXweb']['KD_PROPINSI'];
			$kode_dati2 = $_SESSION['desaXweb']['KD_DATI2'];
			$kode_kecamatan = $_SESSION['desaXweb']['KD_KECAMATAN'];
			$kode_kelurahan = $_SESSION['desaXweb']['KD_KELURAHAN'];
			$namaKecamatan = $_SESSION['desaXweb']['NM_KECAMATAN'];
			$namaKelurahan = $_SESSION['desaXweb']['NM_KELURAHAN'];
			$thnsppt = $_SESSION['tahunPAJAK'];
    	$petugasidx = $_GET['idpetugas'];
			$lingkupdata = $_GET['lingkup'];
			try {
				$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				/* counting data */
				$stmt_cnt = $dbcon->prepare("SELECT COUNT(*) as foundrows FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND PETUGASIDX = :idxpetugas");
				$stmt_cnt->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
				$stmt_cnt->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
				$stmt_cnt->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
				$stmt_cnt->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
				$stmt_cnt->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
				$stmt_cnt->bindValue(":idxpetugas", $petugasidx, PDO::PARAM_INT);
				if ($stmt_cnt->execute()) {
					while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
						$rowsfound = $rowset_cnt['foundrows'];
					}
					if (intval($rowsfound)>0) {
						try {
							$dbconbppkad = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
							$dbconbppkad->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							/* counting data */
							$stmt_cnt_personil = $dbconbppkad->prepare("SELECT COUNT(*) as foundpetugas FROM appx_desa_petugas_pungut WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa");
							$stmt_cnt_personil->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
							$stmt_cnt_personil->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
							$stmt_cnt_personil->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
							$stmt_cnt_personil->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
							if ($stmt_cnt_personil->execute()) {
								while($rowset_cnt_personil = $stmt_cnt_personil->fetch(PDO::FETCH_ASSOC)){
									$countpersonil = $rowset_cnt_personil['foundpetugas'];
								}
								$dbconbppkad = null;
							} else {
								$countpersonil = 0;
								$dbconbppkad = null;
							}
						} catch (PDOException $ep) {
							$countpersonil = 0;
							$dbconbppkad = null;
						}
						/* ====================================== */
						$stmt_summary = $dbcon->prepare("SELECT 
	COUNT(*) AS totalcountsppt, 
	COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprova 
			AND KD_DATI2 = :kdkaba 
			AND KD_KECAMATAN = :kdkeca 
			AND KD_KELURAHAN = :kddesaa 
			AND THN_PAJAK_SPPT = :thnpajaka) AS countallpenetapan, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovb 
			AND KD_DATI2 = :kdkabb 
			AND KD_KECAMATAN = :kdkecb 
			AND KD_KELURAHAN = :kddesab 
			AND THN_PAJAK_SPPT = :thnpajakb 
			AND TGL_TERBIT_SPPT = CONCAT(:thnpajakbb,'-01-02')) AS countdefaultpenetapan, 
	(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovc 
			AND KD_DATI2 = :kdkabc 
			AND KD_KECAMATAN = :kdkecc 
			AND KD_KELURAHAN = :kddesac 
			AND THN_PAJAK_SPPT = :thnpajakc 
			AND TGL_TERBIT_SPPT = CONCAT(:thnpajakcc,'-01-02')) AS sumdefaultpenetapan, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovd 
			AND KD_DATI2 = :kdkabd 
			AND KD_KECAMATAN = :kdkecd 
			AND KD_KELURAHAN = :kddesad 
			AND THN_PAJAK_SPPT = :thnpajakd 
			AND TGL_TERBIT_SPPT != CONCAT(:thnpajakdd,'-01-02')) AS countlaterpenetapan, 
	(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprove 
			AND KD_DATI2 = :kdkabe 
			AND KD_KECAMATAN = :kdkece 
			AND KD_KELURAHAN = :kddesae 
			AND THN_PAJAK_SPPT = :thnpajake 
			AND TGL_TERBIT_SPPT != CONCAT(:thnpajakee,'-01-02')) AS sumlaterpenetapan, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovf 
			AND KD_DATI2 = :kdkabf 
			AND KD_KECAMATAN = :kdkecf 
			AND KD_KELURAHAN = :kddesaf 
			AND THN_PAJAK_SPPT = :thnpajakf 
			AND PETUGASIDX = 0) AS countunassigned, 
	(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovg 
			AND KD_DATI2 = :kdkabg 
			AND KD_KECAMATAN = :kdkecg 
			AND KD_KELURAHAN = :kddesag 
			AND THN_PAJAK_SPPT = :thnpajakg 
			AND PETUGASIDX = 0) AS sumunassigned, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovh 
			AND KD_DATI2 = :kdkabh 
			AND KD_KECAMATAN = :kdkech 
			AND KD_KELURAHAN = :kddesah 
			AND THN_PAJAK_SPPT = :thnpajakh 
			AND PETUGASIDX > 0) AS countassigned, 
	(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovi 
			AND KD_DATI2 = :kdkabi 
			AND KD_KECAMATAN = :kdkeci 
			AND KD_KELURAHAN = :kddesai 
			AND THN_PAJAK_SPPT = :thnpajaki 
			AND PETUGASIDX > 0) AS sumassigned, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovj 
			AND KD_DATI2 = :kdkabj 
			AND KD_KECAMATAN = :kdkecj 
			AND KD_KELURAHAN = :kddesaj 
			AND THN_PAJAK_SPPT = :thnpajakj 
			AND JML_SPPT_YG_DIBAYAR = 0 
			AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS countunpaiddesa, 
	(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovk 
			AND KD_DATI2 = :kdkabk 
			AND KD_KECAMATAN = :kdkeck 
			AND KD_KELURAHAN = :kddesak 
			AND THN_PAJAK_SPPT = :thnpajakk 
			AND JML_SPPT_YG_DIBAYAR = 0 
			AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS sumunpaiddesa, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovl 
			AND KD_DATI2 = :kdkabl 
			AND KD_KECAMATAN = :kdkecl 
			AND KD_KELURAHAN = :kddesal 
			AND THN_PAJAK_SPPT = :thnpajakl 
			AND JML_SPPT_YG_DIBAYAR > 0 
			AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS countpaiddesa, 
	(SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovm 
			AND KD_DATI2 = :kdkabm 
			AND KD_KECAMATAN = :kdkecm 
			AND KD_KELURAHAN = :kddesam 
			AND THN_PAJAK_SPPT = :thnpajakm 
			AND JML_SPPT_YG_DIBAYAR > 0 
			AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS sumpaiddesa, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovn 
			AND KD_DATI2 = :kdkabn 
			AND KD_KECAMATAN = :kdkecn 
			AND KD_KELURAHAN = :kddesan 
			AND THN_PAJAK_SPPT = :thnpajakn 
			AND JML_SPPT_YG_DIBAYAR = 0 
			AND TGL_PEMBAYARAN_SPPT = '1900-01-01' 
			AND PETUGASIDX = 0) AS countunpaidunassigned, 
	(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovo 
			AND KD_DATI2 = :kdkabo 
			AND KD_KECAMATAN = :kdkeco 
			AND KD_KELURAHAN = :kddesao 
			AND THN_PAJAK_SPPT = :thnpajako 
			AND JML_SPPT_YG_DIBAYAR = 0 
			AND TGL_PEMBAYARAN_SPPT = '1900-01-01' 
			AND PETUGASIDX = 0) AS sumunpaidunassigned, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovp 
			AND KD_DATI2 = :kdkabp 
			AND KD_KECAMATAN = :kdkecp 
			AND KD_KELURAHAN = :kddesap 
			AND THN_PAJAK_SPPT = :thnpajakp 
			AND JML_SPPT_YG_DIBAYAR > 0 
			AND TGL_PEMBAYARAN_SPPT != '1900-01-01' 
			AND PETUGASIDX = 0) AS countpaidunassigned, 
	(SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovq 
			AND KD_DATI2 = :kdkabq 
			AND KD_KECAMATAN = :kdkecq 
			AND KD_KELURAHAN = :kddesaq 
			AND THN_PAJAK_SPPT = :thnpajakq 
			AND JML_SPPT_YG_DIBAYAR > 0 
			AND TGL_PEMBAYARAN_SPPT != '1900-01-01' 
			AND PETUGASIDX = 0) AS sumpaidunassigned, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovr 
			AND KD_DATI2 = :kdkabr 
			AND KD_KECAMATAN = :kdkecr 
			AND KD_KELURAHAN = :kddesar 
			AND THN_PAJAK_SPPT = :thnpajakr 
			AND JML_SPPT_YG_DIBAYAR = 0 
			AND TGL_PEMBAYARAN_SPPT = '1900-01-01' 
			AND PETUGASIDX > 0) AS countunpaidassigned, 
	(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovs 
			AND KD_DATI2 = :kdkabs 
			AND KD_KECAMATAN = :kdkecs 
			AND KD_KELURAHAN = :kddesas 
			AND THN_PAJAK_SPPT = :thnpajaks 
			AND JML_SPPT_YG_DIBAYAR = 0 
			AND TGL_PEMBAYARAN_SPPT = '1900-01-01' 
			AND PETUGASIDX > 0) AS sumunpaidassigned, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovt 
			AND KD_DATI2 = :kdkabt 
			AND KD_KECAMATAN = :kdkect 
			AND KD_KELURAHAN = :kddesat 
			AND THN_PAJAK_SPPT = :thnpajakt 
			AND JML_SPPT_YG_DIBAYAR > 0 
			AND TGL_PEMBAYARAN_SPPT != '1900-01-01' 
			AND PETUGASIDX > 0) AS countpaidassigned, 
	(SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovu 
			AND KD_DATI2 = :kdkabu 
			AND KD_KECAMATAN = :kdkecu 
			AND KD_KELURAHAN = :kddesau 
			AND THN_PAJAK_SPPT = :thnpajaku 
			AND JML_SPPT_YG_DIBAYAR > 0 
			AND TGL_PEMBAYARAN_SPPT != '1900-01-01' 
			AND PETUGASIDX > 0) AS sumpaidassigned, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovv 
			AND KD_DATI2 = :kdkabv 
			AND KD_KECAMATAN = :kdkecv 
			AND KD_KELURAHAN = :kddesav 
			AND THN_PAJAK_SPPT = :thnpajakv 
			AND JML_SPPT_YG_DIBAYAR = 0 
			AND TGL_PEMBAYARAN_SPPT = '1900-01-01' 
			AND PETUGASIDX = :idxpetugasv) AS countunpaidassignedpetugas, 
	(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovw 
			AND KD_DATI2 = :kdkabw 
			AND KD_KECAMATAN = :kdkecw 
			AND KD_KELURAHAN = :kddesaw 
			AND THN_PAJAK_SPPT = :thnpajakw 
			AND JML_SPPT_YG_DIBAYAR = 0 
			AND TGL_PEMBAYARAN_SPPT = '1900-01-01' 
			AND PETUGASIDX = :idxpetugasw) AS sumunpaidassignedpetugas, 
	(SELECT COUNT(*) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovx 
			AND KD_DATI2 = :kdkabx 
			AND KD_KECAMATAN = :kdkecx 
			AND KD_KELURAHAN = :kddesax 
			AND THN_PAJAK_SPPT = :thnpajakx 
			AND JML_SPPT_YG_DIBAYAR > 0 
			AND TGL_PEMBAYARAN_SPPT != '1900-01-01' 
			AND PETUGASIDX = :idxpetugasx) AS countpaidassignedpetugas, 
	(SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovy 
			AND KD_DATI2 = :kdkaby 
			AND KD_KECAMATAN = :kdkecy 
			AND KD_KELURAHAN = :kddesay 
			AND THN_PAJAK_SPPT = :thnpajaky 
			AND JML_SPPT_YG_DIBAYAR > 0 
			AND TGL_PEMBAYARAN_SPPT != '1900-01-01' 
			AND PETUGASIDX = :idxpetugasy) AS sumpaidassignedpetugas, 
	(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) 
		FROM 
			sppt_data 
		WHERE 
			KD_PROPINSI = :kdprovz 
			AND KD_DATI2 = :kdkabz 
			AND KD_KECAMATAN = :kdkecz 
			AND KD_KELURAHAN = :kddesaz 
			AND THN_PAJAK_SPPT = :thnpajakz 
			AND PETUGASIDX = :idxpetugasz) AS sumassignedpetugas 
FROM 
	sppt_data 
WHERE 
	KD_PROPINSI = :kdprov 
	AND KD_DATI2 = :kdkab 
	AND KD_KECAMATAN = :kdkec 
	AND KD_KELURAHAN = :kddesa 
	AND THN_PAJAK_SPPT = :thnpajak");
						$stmt_summary->bindValue(":kdprova", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkaba", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkeca", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesaa", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajaka", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovb", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabb", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecb", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesab", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakb", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakbb", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovc", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabc", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecc", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesac", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakc", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakcc", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovd", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabd", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecd", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesad", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakd", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakdd", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprove", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabe", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkece", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesae", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajake", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakee", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovf", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabf", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecf", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesaf", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakf", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovg", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabg", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecg", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesag", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakg", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovh", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabh", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkech", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesah", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakh", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovi", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabi", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkeci", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesai", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajaki", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovj", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabj", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecj", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesaj", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakj", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovk", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabk", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkeck", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesak", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakk", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovl", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabl", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecl", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesal", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakl", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovm", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabm", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecm", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesam", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakm", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovn", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabn", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecn", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesan", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakn", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovo", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabo", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkeco", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesao", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajako", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovp", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabp", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecp", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesap", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakp", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovq", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabq", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecq", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesaq", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakq", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovr", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabr", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecr", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesar", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakr", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovs", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabs", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecs", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesas", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajaks", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovt", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabt", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkect", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesat", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakt", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovu", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabu", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecu", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesau", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajaku", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":kdprovv", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabv", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecv", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesav", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakv", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":idxpetugasv", $petugasidx, PDO::PARAM_INT);$stmt_summary->bindValue(":kdprovw", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabw", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecw", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesaw", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakw", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":idxpetugasw", $petugasidx, PDO::PARAM_INT);$stmt_summary->bindValue(":kdprovx", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabx", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecx", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesax", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakx", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":idxpetugasx", $petugasidx, PDO::PARAM_INT);$stmt_summary->bindValue(":kdprovy", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkaby", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecy", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesay", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajaky", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":idxpetugasy", $petugasidx, PDO::PARAM_INT);$stmt_summary->bindValue(":kdprovz", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkabz", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkecz", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesaz", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajakz", $thnsppt, PDO::PARAM_STR);$stmt_summary->bindValue(":idxpetugasz", $petugasidx, PDO::PARAM_INT);$stmt_summary->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);$stmt_summary->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);$stmt_summary->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);$stmt_summary->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
						if ($stmt_summary->execute()) {
							while($rowset_summary = $stmt_summary->fetch(PDO::FETCH_ASSOC)){
								$totalcountsppt = $rowset_summary['totalcountsppt']; 
								$totalsumsppt = $rowset_summary['totalsumsppt']; 
								$countallpenetapan = $rowset_summary['countallpenetapan'];
								$countdefaultpenetapan = $rowset_summary['countdefaultpenetapan']; 
								$sumdefaultpenetapan = $rowset_summary['sumdefaultpenetapan'];
								$countlaterpenetapan = $rowset_summary['countlaterpenetapan']; 
								$sumlaterpenetapan = $rowset_summary['sumlaterpenetapan'];
								$countunassigned = $rowset_summary['countunassigned']; 
								$sumunassigned = $rowset_summary['sumunassigned'];
								$countassigned = $rowset_summary['countassigned']; 
								$sumassigned = $rowset_summary['sumassigned'];
								$countunpaiddesa = $rowset_summary['countunpaiddesa']; 
								$sumunpaiddesa = $rowset_summary['sumunpaiddesa'];
								$countpaiddesa = $rowset_summary['countpaiddesa']; 
								$sumpaiddesa = $rowset_summary['sumpaiddesa'];
								$countunpaidunassigned = $rowset_summary['countunpaidunassigned']; 
								$sumunpaidunassigned = $rowset_summary['sumunpaidunassigned'];
								$countpaidunassigned = $rowset_summary['countpaidunassigned']; 
								$sumpaidunassigned = $rowset_summary['sumpaidunassigned'];
								$countunpaidassigned = $rowset_summary['countunpaidassigned'];
								$sumunpaidassigned = $rowset_summary['sumunpaidassigned'];
								$countpaidassigned = $rowset_summary['countpaidassigned'];
								$sumpaidassigned = $rowset_summary['sumpaidassigned'];
								$countunpaidassignedpetugas = $rowset_summary['countunpaidassignedpetugas'];
								$sumunpaidassignedpetugas = $rowset_summary['sumunpaidassignedpetugas'];
								$countpaidassignedpetugas = $rowset_summary['countpaidassignedpetugas'];
								$sumpaidassignedpetugas = $rowset_summary['sumpaidassignedpetugas'];
								$sumspptpetugas = $rowset_summary['sumassignedpetugas'];
							}
							/* select all assigned to petugas - SUDAH BAYAR */
							if ($lingkupdata == "SB") {
								$stmt_select_sudah_bayar = $dbcon->prepare("SELECT CONCAT(KD_PROPINSI,'.',KD_DATI2,'.',KD_KECAMATAN,'.',KD_KELURAHAN,'.',KD_BLOK,'-',NO_URUT,'-',KD_JNS_OP) AS NOP,THN_PAJAK_SPPT,NM_WP_SPPT,CONCAT(JLN_WP_SPPT,', ',BLOK_KAV_NO_WP_SPPT,', RT ',RT_WP_SPPT,'/ RW ',RW_WP_SPPT) AS ALAMAT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,TGL_TERBIT_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,PETUGASIDX,NAMAPETUGAS FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND PETUGASIDX = :idxpetugas AND STATUS_PEMBAYARAN_SPPT != 0 AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01'");
								$stmt_select_sudah_bayar->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
								$stmt_select_sudah_bayar->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
								$stmt_select_sudah_bayar->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
								$stmt_select_sudah_bayar->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
								$stmt_select_sudah_bayar->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
								$stmt_select_sudah_bayar->bindValue(":idxpetugas", $petugasidx, PDO::PARAM_INT);
								$stmt_select_sudah_bayar->execute();
								while($rowset_select_sudah_bayar = $stmt_select_sudah_bayar->fetch(PDO::FETCH_ASSOC)){
									$items[] = $rowset_select_sudah_bayar;
								}
								$response = array("ajaxresult"=>"found","datascope"=>"SUDAH BAYAR","countpersonil"=>$countpersonil,"namakecamatan"=>$namaKecamatan,"namakelurahan"=>$namaKelurahan,"totalcountsppt"=>$totalcountsppt,"totalsumsppt"=>$totalsumsppt,"countallpenetapan"=>$countallpenetapan,"countdefaultpenetapan"=>$countdefaultpenetapan,"sumdefaultpenetapan"=>$sumdefaultpenetapan,"countlaterpenetapan"=>$countlaterpenetapan,"sumlaterpenetapan"=>$sumlaterpenetapan,"countunassigned"=>$countunassigned,"sumunassigned"=>$sumunassigned,"countassigned"=>$countassigned,"sumassigned"=>$sumassigned,"countunpaiddesa"=>$countunpaiddesa,"sumunpaiddesa"=>$sumunpaiddesa,"countpaiddesa"=>$countpaiddesa,"sumpaiddesa"=>$sumpaiddesa,"countunpaidunassigned"=>$countunpaidunassigned,"sumunpaidunassigned"=>$sumunpaidunassigned,"countpaidunassigned"=>$countpaidunassigned,"sumpaidunassigned"=>$sumpaidunassigned,"countunpaidassigned"=>$countunpaidassigned,"sumunpaidassigned"=>$sumunpaidassigned,"countpaidassigned"=>$countpaidassigned,"sumpaidassigned"=>$sumpaidassigned,"countspptpetugas"=>$rowsfound,"sumspptpetugas"=>$sumspptpetugas,"countunpaidassignedpetugas"=>$countunpaidassignedpetugas,"sumunpaidassignedpetugas"=>$sumunpaidassignedpetugas,"countpaidassignedpetugas"=>$countpaidassignedpetugas,"sumpaidassignedpetugas"=>$sumpaidassignedpetugas,"dataarray"=>$items);
								header('Content-Type: application/json');
								echo json_encode($response);
								$dbcon = null;
								exit;
							/* select all assigned to petugas - BELUM BAYAR */
							} elseif ($lingkupdata == "BB") {
								$stmt_select_belum_bayar = $dbcon->prepare("SELECT CONCAT(KD_PROPINSI,'.',KD_DATI2,'.',KD_KECAMATAN,'.',KD_KELURAHAN,'.',KD_BLOK,'-',NO_URUT,'-',KD_JNS_OP) AS NOP,THN_PAJAK_SPPT,NM_WP_SPPT,CONCAT(JLN_WP_SPPT,', ',BLOK_KAV_NO_WP_SPPT,', RT ',RT_WP_SPPT,'/ RW ',RW_WP_SPPT) AS ALAMAT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,TGL_TERBIT_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,PETUGASIDX,NAMAPETUGAS FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND PETUGASIDX = :idxpetugas AND STATUS_PEMBAYARAN_SPPT = 0 AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01'");
								$stmt_select_belum_bayar->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
								$stmt_select_belum_bayar->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
								$stmt_select_belum_bayar->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
								$stmt_select_belum_bayar->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
								$stmt_select_belum_bayar->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
								$stmt_select_belum_bayar->bindValue(":idxpetugas", $petugasidx, PDO::PARAM_INT);
								$stmt_select_belum_bayar->execute();
								while($rowset_select_belum_bayar = $stmt_select_belum_bayar->fetch(PDO::FETCH_ASSOC)){
									$items[] = $rowset_select_belum_bayar;
								}
								$response = array("ajaxresult"=>"found","datascope"=>"BELUM BAYAR","countpersonil"=>$countpersonil,"namakecamatan"=>$namaKecamatan,"namakelurahan"=>$namaKelurahan,"totalcountsppt"=>$totalcountsppt,"totalsumsppt"=>$totalsumsppt,"countallpenetapan"=>$countallpenetapan,"countdefaultpenetapan"=>$countdefaultpenetapan,"sumdefaultpenetapan"=>$sumdefaultpenetapan,"countlaterpenetapan"=>$countlaterpenetapan,"sumlaterpenetapan"=>$sumlaterpenetapan,"countunassigned"=>$countunassigned,"sumunassigned"=>$sumunassigned,"countassigned"=>$countassigned,"sumassigned"=>$sumassigned,"countunpaiddesa"=>$countunpaiddesa,"sumunpaiddesa"=>$sumunpaiddesa,"countpaiddesa"=>$countpaiddesa,"sumpaiddesa"=>$sumpaiddesa,"countunpaidunassigned"=>$countunpaidunassigned,"sumunpaidunassigned"=>$sumunpaidunassigned,"countpaidunassigned"=>$countpaidunassigned,"sumpaidunassigned"=>$sumpaidunassigned,"countunpaidassigned"=>$countunpaidassigned,"sumunpaidassigned"=>$sumunpaidassigned,"countpaidassigned"=>$countpaidassigned,"sumpaidassigned"=>$sumpaidassigned,"countspptpetugas"=>$rowsfound,"sumspptpetugas"=>$sumspptpetugas,"countunpaidassignedpetugas"=>$countunpaidassignedpetugas,"sumunpaidassignedpetugas"=>$sumunpaidassignedpetugas,"countpaidassignedpetugas"=>$countpaidassignedpetugas,"sumpaidassignedpetugas"=>$sumpaidassignedpetugas,"dataarray"=>$items);
								header('Content-Type: application/json');
								echo json_encode($response);
								$dbcon = null;
								exit;
							/* select all assigned to petugas - SEMUA DATA */
							} else {
								$stmt_select_all_data_petugas = $dbcon->prepare("SELECT CONCAT(KD_PROPINSI,'.',KD_DATI2,'.',KD_KECAMATAN,'.',KD_KELURAHAN,'.',KD_BLOK,'-',NO_URUT,'-',KD_JNS_OP) AS NOP,THN_PAJAK_SPPT,NM_WP_SPPT,CONCAT(JLN_WP_SPPT,', ',BLOK_KAV_NO_WP_SPPT,', RT ',RT_WP_SPPT,'/ RW ',RW_WP_SPPT) AS ALAMAT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,TGL_TERBIT_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,PETUGASIDX,NAMAPETUGAS FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND PETUGASIDX = :idxpetugas");
								$stmt_select_all_data_petugas->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
								$stmt_select_all_data_petugas->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
								$stmt_select_all_data_petugas->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
								$stmt_select_all_data_petugas->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
								$stmt_select_all_data_petugas->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
								$stmt_select_all_data_petugas->bindValue(":idxpetugas", $petugasidx, PDO::PARAM_INT);
								$stmt_select_all_data_petugas->execute();
								while($rowset_select_all_data_petugas = $stmt_select_all_data_petugas->fetch(PDO::FETCH_ASSOC)){
									$items[] = $rowset_select_all_data_petugas;
								}
								$response = array("ajaxresult"=>"found","datascope"=>"SEMUA DATA","countpersonil"=>$countpersonil,"namakecamatan"=>$namaKecamatan,"namakelurahan"=>$namaKelurahan,"totalcountsppt"=>$totalcountsppt,"totalsumsppt"=>$totalsumsppt,"countallpenetapan"=>$countallpenetapan,"countdefaultpenetapan"=>$countdefaultpenetapan,"sumdefaultpenetapan"=>$sumdefaultpenetapan,"countlaterpenetapan"=>$countlaterpenetapan,"sumlaterpenetapan"=>$sumlaterpenetapan,"countunassigned"=>$countunassigned,"sumunassigned"=>$sumunassigned,"countassigned"=>$countassigned,"sumassigned"=>$sumassigned,"countunpaiddesa"=>$countunpaiddesa,"sumunpaiddesa"=>$sumunpaiddesa,"countpaiddesa"=>$countpaiddesa,"sumpaiddesa"=>$sumpaiddesa,"countunpaidunassigned"=>$countunpaidunassigned,"sumunpaidunassigned"=>$sumunpaidunassigned,"countpaidunassigned"=>$countpaidunassigned,"sumpaidunassigned"=>$sumpaidunassigned,"countunpaidassigned"=>$countunpaidassigned,"sumunpaidassigned"=>$sumunpaidassigned,"countpaidassigned"=>$countpaidassigned,"sumpaidassigned"=>$sumpaidassigned,"countspptpetugas"=>$rowsfound,"sumspptpetugas"=>$sumspptpetugas,"countunpaidassignedpetugas"=>$countunpaidassignedpetugas,"sumunpaidassignedpetugas"=>$sumunpaidassignedpetugas,"countpaidassignedpetugas"=>$countpaidassignedpetugas,"sumpaidassignedpetugas"=>$sumpaidassignedpetugas,"dataarray"=>$items);
								header('Content-Type: application/json');
								echo json_encode($response);
								$dbcon = null;
								exit;
							}
						} else {
							$response = array("ajaxresult"=>"notfound");
							header('Content-Type: application/json');
							echo json_encode($response);
							$dbcon = null;
							exit;
						}
					} else {
						$response = array("ajaxresult"=>"notfound");
						header('Content-Type: application/json');
						echo json_encode($response);
						$dbcon = null;
						exit;
					}
				} else {
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"".$e->getMessage()."");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
	/*----------------------------------------------------------------
	 * RETRIEVE DATA DAFTAR HIMPUNAN KETETAPAN PAJAK (DHKP) DARI MYSQL
	 * ---------------------------------------------------------------*/
    } elseif ($_GET['cmdx'] == "GET_MYSQL_DHKP_LOCAL") {
    	$searchscope = $_GET['desaidx'];
			$arrDesaIDX = explode("|", $searchscope);
			$kode_propinsi = $_SESSION['kecamatanXweb']['KD_PROPINSI'];
			$kode_dati2 = $_SESSION['kecamatanXweb']['KD_DATI2'];
			$kode_kecamatan = $_SESSION['kecamatanXweb']['KD_KECAMATAN'];
			$kode_kelurahan = $arrDesaIDX[3];
			$namaKecamatan = $_SESSION['kecamatanXweb']['NM_KECAMATAN'];
			$namaKelurahan = $arrDesaIDX[5];
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
				$stmt_cnt = $dbcon->prepare("SELECT COUNT(*) as foundrows FROM dhkp_temporary_sppt_data");
				$stmt_cnt->execute();
				while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
					$rowsfound = $rowset_cnt['foundrows'];
				}
				if (intval($rowsfound)>0) {
					/* count data personnel pemungut desa */
					$stmt_cnt_personil = $dbcon->prepare("SELECT COUNT(*) as foundpetugas FROM appx_desa_petugas_pungut WHERE KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa");
					$stmt_cnt_personil->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
					$stmt_cnt_personil->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
					$stmt_cnt_personil->execute();
					while($rowset_cnt_personil = $stmt_cnt_personil->fetch(PDO::FETCH_ASSOC)){
						$countpersonil = $rowset_cnt_personil['foundpetugas'];
					}
					/* BUILD SUMMARY */
					/* ======= table: sppt_data ======== */
					$stmt_summary_sppt_data = $dbcon->prepare("SELECT COUNT(*) AS totalcountsppt, COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, (SELECT COUNT(*) FROM sppt_data WHERE KD_KECAMATAN = :kdkeca AND KD_KELURAHAN = :kddesaa AND THN_PAJAK_SPPT = :thnpajaka) AS countallpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_KECAMATAN = :kdkecb AND KD_KELURAHAN = :kddesab AND THN_PAJAK_SPPT = :thnpajakb AND TGL_TERBIT_SPPT = CONCAT(:thnpajakbb,'-01-02')) AS countdefaultpenetapan, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_KECAMATAN = :kdkecc AND KD_KELURAHAN = :kddesac AND THN_PAJAK_SPPT = :thnpajakc AND TGL_TERBIT_SPPT = CONCAT(:thnpajakcc,'-01-02')) AS sumdefaultpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_KECAMATAN = :kdkecd AND KD_KELURAHAN = :kddesad AND THN_PAJAK_SPPT = :thnpajakd AND TGL_TERBIT_SPPT != CONCAT(:thnpajakdd,'-01-02')) AS countlaterpenetapan, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_KECAMATAN = :kdkece AND KD_KELURAHAN = :kddesae AND THN_PAJAK_SPPT = :thnpajake AND TGL_TERBIT_SPPT != CONCAT(:thnpajakee,'-01-02')) AS sumlaterpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_KECAMATAN = :kdkecf AND KD_KELURAHAN = :kddesaf AND THN_PAJAK_SPPT = :thnpajakf AND PETUGASIDX = 0) AS countunassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_KECAMATAN = :kdkecg AND KD_KELURAHAN = :kddesag AND THN_PAJAK_SPPT = :thnpajakg AND PETUGASIDX = 0) AS sumunassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_KECAMATAN = :kdkech AND KD_KELURAHAN = :kddesah AND THN_PAJAK_SPPT = :thnpajakh AND PETUGASIDX > 0) AS countassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_KECAMATAN = :kdkeci AND KD_KELURAHAN = :kddesai AND THN_PAJAK_SPPT = :thnpajaki AND PETUGASIDX > 0) AS sumassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_KECAMATAN = :kdkecj AND KD_KELURAHAN = :kddesaj AND THN_PAJAK_SPPT = :thnpajakj AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS countunpaiddesa, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_KECAMATAN = :kdkeck AND KD_KELURAHAN = :kddesak AND THN_PAJAK_SPPT = :thnpajakk AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS sumunpaiddesa, (SELECT COUNT(*) FROM sppt_data WHERE KD_KECAMATAN = :kdkecl AND KD_KELURAHAN = :kddesal AND THN_PAJAK_SPPT = :thnpajakl AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS countpaiddesa, (SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_KECAMATAN = :kdkecm AND KD_KELURAHAN = :kddesam AND THN_PAJAK_SPPT = :thnpajakm AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS sumpaiddesa, (SELECT COUNT(*) FROM sppt_data WHERE KD_KECAMATAN = :kdkecn AND KD_KELURAHAN = :kddesan AND THN_PAJAK_SPPT = :thnpajakn AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX = 0) AS countunpaidunassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_KECAMATAN = :kdkeco AND KD_KELURAHAN = :kddesao AND THN_PAJAK_SPPT = :thnpajako AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX = 0) AS sumunpaidunassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_KECAMATAN = :kdkecp AND KD_KELURAHAN = :kddesap AND THN_PAJAK_SPPT = :thnpajakp AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX = 0) AS countpaidunassigned, (SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_KECAMATAN = :kdkecq AND KD_KELURAHAN = :kddesaq AND THN_PAJAK_SPPT = :thnpajakq AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX = 0) AS sumpaidunassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_KECAMATAN = :kdkecr AND KD_KELURAHAN = :kddesar AND THN_PAJAK_SPPT = :thnpajakr AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX > 0) AS countunpaidassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_KECAMATAN = :kdkecs AND KD_KELURAHAN = :kddesas AND THN_PAJAK_SPPT = :thnpajaks AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX > 0) AS sumunpaidassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_KECAMATAN = :kdkect AND KD_KELURAHAN = :kddesat AND THN_PAJAK_SPPT = :thnpajakt AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX > 0) AS countpaidassigned, (SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_KECAMATAN = :kdkecu AND KD_KELURAHAN = :kddesau AND THN_PAJAK_SPPT = :thnpajaku AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX > 0) AS sumpaidassigned FROM sppt_data WHERE KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak");
					$stmt_summary_sppt_data->bindValue(":kdkeca", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesaa", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajaka", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkecb", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesab", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakb", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakbb", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkecc", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesac", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakc", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakcc", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkecd", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesad", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakd", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakdd", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkece", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesae", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajake", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakee", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkecf", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesaf", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakf", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkecg", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesag", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakg", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkech", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesah", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakh", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkeci", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesai", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajaki", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkecj", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesaj", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakj", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkeck", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesak", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakk", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkecl", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesal", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakl", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkecm", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesam", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakm", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkecn", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesan", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakn", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkeco", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesao", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajako", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkecp", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesap", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakp", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkecq", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesaq", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakq", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkecr", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesar", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakr", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkecs", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesas", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajaks", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkect", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesat", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajakt", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkecu", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesau", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajaku", $thnsppt, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary_sppt_data->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
					$stmt_summary_sppt_data->execute();
					while($rowset_summary_sppt_data = $stmt_summary_sppt_data->fetch(PDO::FETCH_ASSOC)){
							$totalcountsppt = $rowset_summary_sppt_data['totalcountsppt'];
							$totalsumsppt = $rowset_summary_sppt_data['totalsumsppt'];
							$countallpenetapan = $rowset_summary_sppt_data['countallpenetapan'];
							$countdefaultpenetapan = $rowset_summary_sppt_data['countdefaultpenetapan'];
							$sumdefaultpenetapan = $rowset_summary_sppt_data['sumdefaultpenetapan'];
							$countlaterpenetapan = $rowset_summary_sppt_data['countlaterpenetapan'];
							$sumlaterpenetapan = $rowset_summary_sppt_data['sumlaterpenetapan'];
							$countunassigned = $rowset_summary_sppt_data['countunassigned'];
							$sumunassigned = $rowset_summary_sppt_data['sumunassigned'];
							$countassigned = $rowset_summary_sppt_data['countassigned'];
							$sumassigned = $rowset_summary_sppt_data['sumassigned'];
							$countunpaiddesa = $rowset_summary_sppt_data['countunpaiddesa'];
							$sumunpaiddesa = $rowset_summary_sppt_data['sumunpaiddesa'];
							$countpaiddesa = $rowset_summary_sppt_data['countpaiddesa'];
							$sumpaiddesa = $rowset_summary_sppt_data['sumpaiddesa'];
							$countunpaidunassigned = $rowset_summary_sppt_data['countunpaidunassigned'];
							$sumunpaidunassigned = $rowset_summary_sppt_data['sumunpaidunassigned'];
							$countpaidunassigned = $rowset_summary_sppt_data['countpaidunassigned'];
							$sumpaidunassigned = $rowset_summary_sppt_data['sumpaidunassigned'];
							$countunpaidassigned = $rowset_summary_sppt_data['countunpaidassigned'];
							$sumunpaidassigned = $rowset_summary_sppt_data['sumunpaidassigned'];
							$countpaidassigned = $rowset_summary_sppt_data['countpaidassigned'];
							$sumpaidassigned = $rowset_summary_sppt_data['sumpaidassigned'];
					}
					/* ======= /table: sppt_data ======== */
					/* ======= table: dhkp_temporary_sppt_data ======== */
					/* count total jumlah/lembar SPPT, total penetapan, dan total SPPT terbayar dari table dhkp_temporary_sppt_data */
					$stmt_summary_dhkp_local_a = $dbcon->prepare("
						SELECT 
							COUNT(*) AS totalcountsppt, 
							COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, 
							COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) AS sumpaiddesa 
						FROM dhkp_temporary_sppt_data");
					$stmt_summary_dhkp_local_a->execute();
					while($rowset_summary_dhkp_local_a = $stmt_summary_dhkp_local_a->fetch(PDO::FETCH_ASSOC)){
						$RTTMPtotalcountsppt = $rowset_summary_dhkp_local_a['totalcountsppt'];
						$RTTMPtotalsumsppt = $rowset_summary_dhkp_local_a['totalsumsppt'];
						$RTTMPsumpaiddesa = $rowset_summary_dhkp_local_a['sumpaiddesa'];
					}
					/* count total jumlah/lembar SPPT, total penetapan, dan total SPPT terbayar dari table dhkp_temporary_sppt_data, yang tgl penetapannya 02-01-[TAHUN_PAJAK] */
					$stmt_summary_dhkp_local_b = $dbcon->prepare("
						SELECT 
							COUNT(*) AS totalcountsppt, 
							COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, 
							COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) AS sumpaiddefault 
						FROM 
							dhkp_temporary_sppt_data 
						WHERE 
							TGL_TERBIT_SPPT = :defaulttglterbit");
					$stmt_summary_dhkp_local_b->bindValue(":defaulttglterbit", $tglterbitdefault, PDO::PARAM_STR);
					$stmt_summary_dhkp_local_b->execute();
					while($rowset_summary_dhkp_local_b = $stmt_summary_dhkp_local_b->fetch(PDO::FETCH_ASSOC)){
						$RTTMPcountdefaultpenetapan = $rowset_summary_dhkp_local_b['totalcountsppt'];
						$RTTMPsumdefaultpenetapan = $rowset_summary_dhkp_local_b['totalsumsppt'];
						$RTTMPsumpaiddefaultpenetapan = $rowset_summary_dhkp_local_b['sumpaiddefault'];
					}
					/* count total jumlah/lembar SPPT, total penetapan, dan total SPPT terbayar dari table dhkp_temporary_sppt_data, yang tgl penetapannya > 02-01-[TAHUN_PAJAK] */
					$stmt_summary_dhkp_local_c = $dbcon->prepare("
						SELECT 
							COUNT(*) AS totalcountsppt, 
							COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, 
							COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) AS sumpaidlater 
						FROM 
							dhkp_temporary_sppt_data 
						WHERE 
							TGL_TERBIT_SPPT > :defaulttglterbit");
					$stmt_summary_dhkp_local_c->bindValue(":defaulttglterbit", $tglterbitdefault, PDO::PARAM_STR);
					$stmt_summary_dhkp_local_c->execute();
					while($rowset_summary_dhkp_local_c = $stmt_summary_dhkp_local_c->fetch(PDO::FETCH_ASSOC)){
						$RTTMPcountlaterpenetapan = $rowset_summary_dhkp_local_c['totalcountsppt'];
						$RTTMPsumlaterpenetapan = $rowset_summary_dhkp_local_c['totalsumsppt'];
						$RTTMPsumpaidlaterpenetapan = $rowset_summary_dhkp_local_c['sumpaidlater'];
					}
					/* count total jumlah/lembar SPPT, total penetapan, dan total SPPT terbayar dari table dhkp_temporary_sppt_data, yang belum di-assign ke petugas pungut desa */
					$stmt_summary_dhkp_local_d = $dbcon->prepare("
						SELECT 
							COUNT(*) AS totalcountsppt, 
							COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, 
							COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) AS sumpaidunassigned 
						FROM 
							dhkp_temporary_sppt_data 
						WHERE 
							PETUGASIDX = 0");
					$stmt_summary_dhkp_local_d->execute();
					while($rowset_summary_dhkp_local_d = $stmt_summary_dhkp_local_d->fetch(PDO::FETCH_ASSOC)){
						$RTTMPcountunassigned = $rowset_summary_dhkp_local_d['totalcountsppt'];
						$RTTMPsumunassigned = $rowset_summary_dhkp_local_d['totalsumsppt'];
						$RTTMPsumpaidunassigned = $rowset_summary_dhkp_local_d['sumpaidunassigned'];
					}
					/* count total jumlah/lembar SPPT BELUM DIBAYAR dari table dhkp_temporary_sppt_data */
					$stmt_summary_dhkp_local_f = $dbcon->prepare("
						SELECT 
							COUNT(*) AS totalcountsppt 
						FROM 
							dhkp_temporary_sppt_data 
						WHERE 
							JML_SPPT_YG_DIBAYAR = 0");
					$stmt_summary_dhkp_local_f->execute();
					while($rowset_summary_dhkp_local_f = $stmt_summary_dhkp_local_f->fetch(PDO::FETCH_ASSOC)){
						$RTTMPcountunpaiddesa = $rowset_summary_dhkp_local_f['totalcountsppt'];
						
					}
					/* count total jumlah/lembar SPPT BELUM DIBAYAR + UNASSIGNED dari table dhkp_temporary_sppt_data */
					$stmt_summary_dhkp_local_g = $dbcon->prepare("
						SELECT 
							COUNT(*) AS totalcountsppt 
						FROM 
							dhkp_temporary_sppt_data 
						WHERE 
							JML_SPPT_YG_DIBAYAR = 0 
							AND PETUGASIDX = 0");
					$stmt_summary_dhkp_local_g->execute();
					while($rowset_summary_dhkp_local_g = $stmt_summary_dhkp_local_g->fetch(PDO::FETCH_ASSOC)){
						$RTTMPcountunpaidunassigned = $rowset_summary_dhkp_local_g['totalcountsppt'];
					}
					/* count total jumlah/lembar SPPT BELUM DIBAYAR + ASSIGNED dari table dhkp_temporary_sppt_data */
					$stmt_summary_dhkp_local_h = $dbcon->prepare("
						SELECT 
							COUNT(*) AS totalcountsppt 
						FROM 
							dhkp_temporary_sppt_data 
						WHERE 
							JML_SPPT_YG_DIBAYAR = 0 
							AND PETUGASIDX > 0");
					$stmt_summary_dhkp_local_h->execute();
					while($rowset_summary_dhkp_local_h = $stmt_summary_dhkp_local_h->fetch(PDO::FETCH_ASSOC)){
						$RTTMPcountunpaidassigned = $rowset_summary_dhkp_local_h['totalcountsppt'];
					}
					/* some small-but-important otf-calculation */
					$RTTMPsumunpaiddesa = intval($RTTMPtotalsumsppt) - intval($RTTMPsumpaiddesa);
					$RTTMPsumunpaiddefaultpenetapan = intval($RTTMPsumdefaultpenetapan) - intval($RTTMPsumpaiddefaultpenetapan);
					$RTTMPsumunpaidlaterpenetapan = intval($RTTMPsumlaterpenetapan) - intval($RTTMPsumpaidlaterpenetapan);
					$RTTMPcountassigned = intval($RTTMPtotalcountsppt) - intval($RTTMPcountunassigned);
					$RTTMPsumassigned = intval($RTTMPtotalsumsppt) - intval($RTTMPsumunassigned);
					$RTTMPsumpaidassigned = intval($RTTMPsumpaiddesa) - intval($RTTMPsumpaidunassigned);
					$RTTMPsumunpaidunassigned = intval($RTTMPsumunassigned) - intval($RTTMPsumpaidunassigned);
					$RTTMPsumunpaidassigned = intval($RTTMPsumassigned) - intval($RTTMPsumpaidassigned);
					$RTTMPcountpaiddesa = intval($RTTMPtotalcountsppt) - intval($RTTMPcountunpaiddesa);
					$RTTMPcountpaidunassigned = intval($RTTMPcountunassigned) - intval($RTTMPcountunpaidunassigned);
					$RTTMPcountpaidassigned = intval($RTTMPcountassigned) - intval($RTTMPcountunpaidassigned);
					/* select all sppt from dhkp_temporary_sppt_data */
					$stmt_select_data_dhkp = $dbcon->prepare("SELECT KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,TGL_TERBIT_SPPT FROM dhkp_temporary_sppt_data ORDER BY KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP");
					if ($stmt_select_data_dhkp->execute()) {
						while($rowset_select_data_dhkp = $stmt_select_data_dhkp->fetch(PDO::FETCH_ASSOC)){
							$items[] = $rowset_select_data_dhkp;
						}
						$response = array("ajaxresult"=>"found",
							"datacontext"=>"GET_MYSQL_DHKP_LOCAL",
							"kodeprov"=>$kode_propinsi,
							"kodekab"=>$kode_dati2,
							"kodekec"=>$kode_kecamatan,
							"kodedesa"=>$kode_kelurahan,
							"thnpajak"=>$thnsppt,
							"namakecamatan"=>$namaKecamatan,
							"namakelurahan"=>$namaKelurahan,
							"countpersonil"=>$countpersonil,
							"totalcountsppt"=>$totalcountsppt,
							"totalsumsppt"=>$totalsumsppt,
							"countallpenetapan"=>$countallpenetapan,
							"countdefaultpenetapan"=>$countdefaultpenetapan,
							"sumdefaultpenetapan"=>$sumdefaultpenetapan,
							"countlaterpenetapan"=>$countlaterpenetapan,
							"sumlaterpenetapan"=>$sumlaterpenetapan,
							"countunassigned"=>$countunassigned,
							"sumunassigned"=>$sumunassigned,
							"countassigned"=>$countassigned,
							"sumassigned"=>$sumassigned,
							"countunpaiddesa"=>$countunpaiddesa,
							"sumunpaiddesa"=>$sumunpaiddesa,
							"countpaiddesa"=>$countpaiddesa,
							"sumpaiddesa"=>$sumpaiddesa,
							"countunpaidunassigned"=>$countunpaidunassigned,
							"sumunpaidunassigned"=>$sumunpaidunassigned,
							"countpaidunassigned"=>$countpaidunassigned,
							"sumpaidunassigned"=>$sumpaidunassigned,
							"countunpaidassigned"=>$countunpaidassigned,
							"sumunpaidassigned"=>$sumunpaidassigned,
							"countpaidassigned"=>$countpaidassigned,
							"sumpaidassigned"=>$sumpaidassigned,
							"rttmptotalcountsppt"=>$RTTMPtotalcountsppt,
							"rttmptotalsumsppt"=>$RTTMPtotalsumsppt,
							"rttmpcountdefaultpenetapan"=>$RTTMPcountdefaultpenetapan,
							"rttmpsumdefaultpenetapan"=>$RTTMPsumdefaultpenetapan,
							"rttmpcountlaterpenetapan"=>$RTTMPcountlaterpenetapan,
							"rttmpsumlaterpenetapan"=>$RTTMPsumlaterpenetapan,
							"rttmpcountunassigned"=>$RTTMPcountunassigned,
							"rttmpsumunassigned"=>$RTTMPsumunassigned,
							"rttmpcountassigned"=>$RTTMPcountassigned,
							"rttmpsumassigned"=>$RTTMPsumassigned,
							"rttmpcountunpaid"=>$RTTMPcountunpaiddesa,
							"rttmpsumunpaid"=>$RTTMPsumunpaiddesa,
							"rttmpcountpaid"=>$RTTMPcountpaiddesa,
							"rttmpsumpaid"=>$RTTMPsumpaiddesa,
							"rttmpcountunpaidunassigned"=>$RTTMPcountunpaidunassigned,
							"rttmpsumunpaidunassigned"=>$RTTMPsumunpaidunassigned,
							"rttmpcountpaidunassigned"=>$RTTMPcountpaidunassigned,
							"rttmpsumpaidunassigned"=>$RTTMPsumpaidunassigned,
							"rttmpcountunpaidassigned"=>$RTTMPcountunpaidassigned,
							"rttmpsumunpaidassigned"=>$RTTMPsumunpaidassigned,
							"rttmpcountpaidassigned"=>$RTTMPcountpaidassigned,
							"rttmpsumpaidassigned"=>$RTTMPsumpaidassigned,
							"rttmpsumpaidlaterpenetapan"=>$RTTMPsumpaidlaterpenetapan,
							"rttmpsumunpaidlaterpenetapan"=>$RTTMPsumunpaidlaterpenetapan,
							"rttmpsumpaiddefaultpenetapan"=>$RTTMPsumpaiddefaultpenetapan,
							"rttmpsumunpaiddefaultpenetapan"=>$RTTMPsumunpaiddefaultpenetapan,
							"dataarray"=>$items);
						header('Content-Type: application/json');
						echo json_encode($response);
						$dbcon = null;
						exit;
					} else {
						$response = array("ajaxresult"=>"notfound");
						header('Content-Type: application/json');
						echo json_encode($response);
						$dbcon = null;
						exit;
					}
				} else {
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"".$e->getMessage()."");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
		} elseif ($_GET['cmdx'] == "GET_MYSQL_PEMBAYARAN_LOCAL") {
    	$searchscope = $_GET['desaidx'];
			$arrDesaIDX = explode("|", $searchscope);
			$kode_propinsi = $_SESSION['kecamatanXweb']['KD_PROPINSI'];
			$kode_dati2 = $_SESSION['kecamatanXweb']['KD_DATI2'];
			$kode_kecamatan = $_SESSION['kecamatanXweb']['KD_KECAMATAN'];
			$kode_kelurahan = $arrDesaIDX[3];
			$namaKecamatan = $_SESSION['kecamatanXweb']['NM_KECAMATAN'];
			$namaKelurahan = $arrDesaIDX[5];
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
				
				/* ======= table: dhkp_temporary_sppt_data ======== */
				/* count total jumlah/lembar SPPT, total penetapan, dan total SPPT terbayar dari table dhkp_temporary_sppt_data */
				$stmt_summary_dhkp_local_a = $dbcon->prepare("
					SELECT 
						COUNT(*) AS totalcountsppt, 
						COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, 
						COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) AS sumpaiddesa, 
						COALESCE(SUM(DENDA_SPPT), 0) AS sumpaiddendadesa 
					FROM dhkp_temporary_sppt_data");
				$stmt_summary_dhkp_local_a->execute();
				while($rowset_summary_dhkp_local_a = $stmt_summary_dhkp_local_a->fetch(PDO::FETCH_ASSOC)){
					$RTTMPtotalcountsppt = $rowset_summary_dhkp_local_a['totalcountsppt'];
					$RTTMPtotalsumsppt = $rowset_summary_dhkp_local_a['totalsumsppt'];
					$RTTMPsumpaiddesa = $rowset_summary_dhkp_local_a['sumpaiddesa'];
					$RTTMPsumpaiddendadesa = $rowset_summary_dhkp_local_a['sumpaiddendadesa'];
				}
				/* count total jumlah/lembar SPPT, total penetapan, dan total SPPT terbayar dari table dhkp_temporary_sppt_data, yang tgl penetapannya 02-01-[TAHUN_PAJAK] */
				$stmt_summary_dhkp_local_b = $dbcon->prepare("
					SELECT 
						COUNT(*) AS totalcountsppt, 
						COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, 
						COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) AS sumpaiddefault 
					FROM 
						dhkp_temporary_sppt_data 
					WHERE 
						TGL_TERBIT_SPPT = :defaulttglterbit");
				$stmt_summary_dhkp_local_b->bindValue(":defaulttglterbit", $tglterbitdefault, PDO::PARAM_STR);
				$stmt_summary_dhkp_local_b->execute();
				while($rowset_summary_dhkp_local_b = $stmt_summary_dhkp_local_b->fetch(PDO::FETCH_ASSOC)){
					$RTTMPcountdefaultpenetapan = $rowset_summary_dhkp_local_b['totalcountsppt'];
					$RTTMPsumdefaultpenetapan = $rowset_summary_dhkp_local_b['totalsumsppt'];
					$RTTMPsumpaiddefaultpenetapan = $rowset_summary_dhkp_local_b['sumpaiddefault'];
				}
				/* count total jumlah/lembar SPPT, total penetapan, dan total SPPT terbayar dari table dhkp_temporary_sppt_data, yang tgl penetapannya > 02-01-[TAHUN_PAJAK] */
				$stmt_summary_dhkp_local_c = $dbcon->prepare("
					SELECT 
						COUNT(*) AS totalcountsppt, 
						COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, 
						COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) AS sumpaidlater 
					FROM 
						dhkp_temporary_sppt_data 
					WHERE 
						TGL_TERBIT_SPPT > :defaulttglterbit");
				$stmt_summary_dhkp_local_c->bindValue(":defaulttglterbit", $tglterbitdefault, PDO::PARAM_STR);
				$stmt_summary_dhkp_local_c->execute();
				while($rowset_summary_dhkp_local_c = $stmt_summary_dhkp_local_c->fetch(PDO::FETCH_ASSOC)){
					$RTTMPcountlaterpenetapan = $rowset_summary_dhkp_local_c['totalcountsppt'];
					$RTTMPsumlaterpenetapan = $rowset_summary_dhkp_local_c['totalsumsppt'];
					$RTTMPsumpaidlaterpenetapan = $rowset_summary_dhkp_local_c['sumpaidlater'];
				}
				/* count total jumlah/lembar SPPT, total penetapan, dan total SPPT terbayar dari table dhkp_temporary_sppt_data, yang belum di-assign ke petugas pungut desa */
				$stmt_summary_dhkp_local_d = $dbcon->prepare("
					SELECT 
						COUNT(*) AS totalcountsppt, 
						COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, 
						COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) AS sumpaidunassigned 
					FROM 
						dhkp_temporary_sppt_data 
					WHERE 
						PETUGASIDX = 0");
				$stmt_summary_dhkp_local_d->execute();
				while($rowset_summary_dhkp_local_d = $stmt_summary_dhkp_local_d->fetch(PDO::FETCH_ASSOC)){
					$RTTMPcountunassigned = $rowset_summary_dhkp_local_d['totalcountsppt'];
					$RTTMPsumunassigned = $rowset_summary_dhkp_local_d['totalsumsppt'];
					$RTTMPsumpaidunassigned = $rowset_summary_dhkp_local_d['sumpaidunassigned'];
				}
				/* count total jumlah/lembar SPPT BELUM DIBAYAR dari table dhkp_temporary_sppt_data */
				$stmt_summary_dhkp_local_f = $dbcon->prepare("
					SELECT 
						COUNT(*) AS totalcountsppt 
					FROM 
						dhkp_temporary_sppt_data 
					WHERE 
						JML_SPPT_YG_DIBAYAR = 0");
				$stmt_summary_dhkp_local_f->execute();
				while($rowset_summary_dhkp_local_f = $stmt_summary_dhkp_local_f->fetch(PDO::FETCH_ASSOC)){
					$RTTMPcountunpaiddesa = $rowset_summary_dhkp_local_f['totalcountsppt'];
					
				}
				/* count total jumlah/lembar SPPT BELUM DIBAYAR + UNASSIGNED dari table dhkp_temporary_sppt_data */
				$stmt_summary_dhkp_local_g = $dbcon->prepare("
					SELECT 
						COUNT(*) AS totalcountsppt 
					FROM 
						dhkp_temporary_sppt_data 
					WHERE 
						JML_SPPT_YG_DIBAYAR = 0 
						AND PETUGASIDX = 0");
				$stmt_summary_dhkp_local_g->execute();
				while($rowset_summary_dhkp_local_g = $stmt_summary_dhkp_local_g->fetch(PDO::FETCH_ASSOC)){
					$RTTMPcountunpaidunassigned = $rowset_summary_dhkp_local_g['totalcountsppt'];
				}
				/* count total jumlah/lembar SPPT BELUM DIBAYAR + ASSIGNED dari table dhkp_temporary_sppt_data */
				$stmt_summary_dhkp_local_h = $dbcon->prepare("
					SELECT 
						COUNT(*) AS totalcountsppt 
					FROM 
						dhkp_temporary_sppt_data 
					WHERE 
						JML_SPPT_YG_DIBAYAR = 0 
						AND PETUGASIDX > 0");
				$stmt_summary_dhkp_local_h->execute();
				while($rowset_summary_dhkp_local_h = $stmt_summary_dhkp_local_h->fetch(PDO::FETCH_ASSOC)){
					$RTTMPcountunpaidassigned = $rowset_summary_dhkp_local_h['totalcountsppt'];
				}
				/* some small-but-important otf-calculation */
				$RTTMPsumunpaiddesa = intval($RTTMPtotalsumsppt) - intval($RTTMPsumpaiddesa);
				$RTTMPsumunpaiddefaultpenetapan = intval($RTTMPsumdefaultpenetapan) - intval($RTTMPsumpaiddefaultpenetapan);
				$RTTMPsumunpaidlaterpenetapan = intval($RTTMPsumlaterpenetapan) - intval($RTTMPsumpaidlaterpenetapan);
				$RTTMPcountassigned = intval($RTTMPtotalcountsppt) - intval($RTTMPcountunassigned);
				$RTTMPsumassigned = intval($RTTMPtotalsumsppt) - intval($RTTMPsumunassigned);
				$RTTMPsumpaidassigned = intval($RTTMPsumpaiddesa) - intval($RTTMPsumpaidunassigned);
				$RTTMPsumunpaidunassigned = intval($RTTMPsumunassigned) - intval($RTTMPsumpaidunassigned);
				$RTTMPsumunpaidassigned = intval($RTTMPsumassigned) - intval($RTTMPsumpaidassigned);
				$RTTMPcountpaiddesa = intval($RTTMPtotalcountsppt) - intval($RTTMPcountunpaiddesa);
				$RTTMPcountpaidunassigned = intval($RTTMPcountunassigned) - intval($RTTMPcountunpaidunassigned);
				$RTTMPcountpaidassigned = intval($RTTMPcountassigned) - intval($RTTMPcountunpaidassigned);
				/* ======= table: dhkp_temporary_sppt_data ======== */
					
				/* counting lembar sppt - desa */
				$stmt_cnt = $dbcon->prepare("SELECT COUNT(*) as foundrows FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak");
				$stmt_cnt->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
				$stmt_cnt->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
				$stmt_cnt->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
				$stmt_cnt->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
				$stmt_cnt->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
				if ($stmt_cnt->execute()) {
					while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
						$rowsfound = $rowset_cnt['foundrows'];
					}
					if (intval($rowsfound)>0) {
						/* counting data */
						$stmt_cnt_personil = $dbcon->prepare("SELECT COUNT(*) as foundpetugas FROM appx_desa_petugas_pungut WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa");
						$stmt_cnt_personil->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
						$stmt_cnt_personil->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
						$stmt_cnt_personil->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
						$stmt_cnt_personil->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
						if ($stmt_cnt_personil->execute()) {
							while($rowset_cnt_personil = $stmt_cnt_personil->fetch(PDO::FETCH_ASSOC)){
								$countpersonil = $rowset_cnt_personil['foundpetugas'];
							}
						} else {
							$countpersonil = 0;
						}
						/* ====================================== */
						$stmt_summary_all_data = $dbcon->prepare("SELECT COUNT(*) AS totalcountsppt, COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprova AND KD_DATI2 = :kdkaba AND KD_KECAMATAN = :kdkeca AND KD_KELURAHAN = :kddesaa AND THN_PAJAK_SPPT = :thnpajaka) AS countallpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovb AND KD_DATI2 = :kdkabb AND KD_KECAMATAN = :kdkecb AND KD_KELURAHAN = :kddesab AND THN_PAJAK_SPPT = :thnpajakb AND TGL_TERBIT_SPPT = CONCAT(:thnpajakbb,'-01-02')) AS countdefaultpenetapan, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovc AND KD_DATI2 = :kdkabc AND KD_KECAMATAN = :kdkecc AND KD_KELURAHAN = :kddesac AND THN_PAJAK_SPPT = :thnpajakc AND TGL_TERBIT_SPPT = CONCAT(:thnpajakcc,'-01-02')) AS sumdefaultpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovd AND KD_DATI2 = :kdkabd AND KD_KECAMATAN = :kdkecd AND KD_KELURAHAN = :kddesad AND THN_PAJAK_SPPT = :thnpajakd AND TGL_TERBIT_SPPT != CONCAT(:thnpajakdd,'-01-02')) AS countlaterpenetapan, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprove AND KD_DATI2 = :kdkabe AND KD_KECAMATAN = :kdkece AND KD_KELURAHAN = :kddesae AND THN_PAJAK_SPPT = :thnpajake AND TGL_TERBIT_SPPT != CONCAT(:thnpajakee,'-01-02')) AS sumlaterpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovf AND KD_DATI2 = :kdkabf AND KD_KECAMATAN = :kdkecf AND KD_KELURAHAN = :kddesaf AND THN_PAJAK_SPPT = :thnpajakf AND PETUGASIDX = 0) AS countunassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovg AND KD_DATI2 = :kdkabg AND KD_KECAMATAN = :kdkecg AND KD_KELURAHAN = :kddesag AND THN_PAJAK_SPPT = :thnpajakg AND PETUGASIDX = 0) AS sumunassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovh AND KD_DATI2 = :kdkabh AND KD_KECAMATAN = :kdkech AND KD_KELURAHAN = :kddesah AND THN_PAJAK_SPPT = :thnpajakh AND PETUGASIDX > 0) AS countassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovi AND KD_DATI2 = :kdkabi AND KD_KECAMATAN = :kdkeci AND KD_KELURAHAN = :kddesai AND THN_PAJAK_SPPT = :thnpajaki AND PETUGASIDX > 0) AS sumassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovj AND KD_DATI2 = :kdkabj AND KD_KECAMATAN = :kdkecj AND KD_KELURAHAN = :kddesaj AND THN_PAJAK_SPPT = :thnpajakj AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS countunpaiddesa, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovk AND KD_DATI2 = :kdkabk AND KD_KECAMATAN = :kdkeck AND KD_KELURAHAN = :kddesak AND THN_PAJAK_SPPT = :thnpajakk AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS sumunpaiddesa, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovl AND KD_DATI2 = :kdkabl AND KD_KECAMATAN = :kdkecl AND KD_KELURAHAN = :kddesal AND THN_PAJAK_SPPT = :thnpajakl AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS countpaiddesa, (SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovm AND KD_DATI2 = :kdkabm AND KD_KECAMATAN = :kdkecm AND KD_KELURAHAN = :kddesam AND THN_PAJAK_SPPT = :thnpajakm AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS sumpaiddesa, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovn AND KD_DATI2 = :kdkabn AND KD_KECAMATAN = :kdkecn AND KD_KELURAHAN = :kddesan AND THN_PAJAK_SPPT = :thnpajakn AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX = 0) AS countunpaidunassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovo AND KD_DATI2 = :kdkabo AND KD_KECAMATAN = :kdkeco AND KD_KELURAHAN = :kddesao AND THN_PAJAK_SPPT = :thnpajako AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX = 0) AS sumunpaidunassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovp AND KD_DATI2 = :kdkabp AND KD_KECAMATAN = :kdkecp AND KD_KELURAHAN = :kddesap AND THN_PAJAK_SPPT = :thnpajakp AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX = 0) AS countpaidunassigned, (SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovq AND KD_DATI2 = :kdkabq AND KD_KECAMATAN = :kdkecq AND KD_KELURAHAN = :kddesaq AND THN_PAJAK_SPPT = :thnpajakq AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX = 0) AS sumpaidunassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovr AND KD_DATI2 = :kdkabr AND KD_KECAMATAN = :kdkecr AND KD_KELURAHAN = :kddesar AND THN_PAJAK_SPPT = :thnpajakr AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX > 0) AS countunpaidassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovs AND KD_DATI2 = :kdkabs AND KD_KECAMATAN = :kdkecs AND KD_KELURAHAN = :kddesas AND THN_PAJAK_SPPT = :thnpajaks AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX > 0) AS sumunpaidassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovt AND KD_DATI2 = :kdkabt AND KD_KECAMATAN = :kdkect AND KD_KELURAHAN = :kddesat AND THN_PAJAK_SPPT = :thnpajakt AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX > 0) AS countpaidassigned, (SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovu AND KD_DATI2 = :kdkabu AND KD_KECAMATAN = :kdkecu AND KD_KELURAHAN = :kddesau AND THN_PAJAK_SPPT = :thnpajaku AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX > 0) AS sumpaidassigned FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak");
						$stmt_summary_all_data->bindValue(":kdprova", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkaba", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkeca", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesaa", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajaka", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovb", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabb", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkecb", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesab", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakb", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakbb", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovc", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabc", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkecc", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesac", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakc", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakcc", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovd", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabd", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkecd", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesad", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakd", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakdd", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprove", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabe", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkece", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesae", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajake", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakee", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovf", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabf", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkecf", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesaf", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakf", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovg", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabg", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkecg", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesag", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakg", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovh", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabh", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkech", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesah", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakh", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovi", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabi", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkeci", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesai", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajaki", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovj", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabj", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkecj", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesaj", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakj", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovk", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabk", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkeck", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesak", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakk", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovl", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabl", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkecl", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesal", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakl", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovm", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabm", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkecm", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesam", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakm", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovn", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabn", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkecn", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesan", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakn", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovo", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabo", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkeco", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesao", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajako", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovp", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabp", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkecp", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesap", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakp", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovq", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabq", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkecq", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesaq", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakq", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovr", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabr", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkecr", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesar", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakr", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovs", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabs", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkecs", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesas", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajaks", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovt", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabt", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkect", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesat", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajakt", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprovu", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkabu", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkecu", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesau", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajaku", $thnsppt, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR); 
						$stmt_summary_all_data->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
						if ($stmt_summary_all_data->execute()) {
							while($rowset_summary_all_data = $stmt_summary_all_data->fetch(PDO::FETCH_ASSOC)){
								$totalcountsppt = $rowset_summary_all_data['totalcountsppt']; 
								$totalsumsppt = $rowset_summary_all_data['totalsumsppt']; 
								$countallpenetapan = $rowset_summary_all_data['countallpenetapan']; 
								$countdefaultpenetapan = $rowset_summary_all_data['countdefaultpenetapan']; 
								$sumdefaultpenetapan = $rowset_summary_all_data['sumdefaultpenetapan']; 
								$countlaterpenetapan = $rowset_summary_all_data['countlaterpenetapan']; 
								$sumlaterpenetapan = $rowset_summary_all_data['sumlaterpenetapan']; 
								$countunassigned = $rowset_summary_all_data['countunassigned']; 
								$sumunassigned = $rowset_summary_all_data['sumunassigned']; 
								$countassigned = $rowset_summary_all_data['countassigned']; 
								$sumassigned = $rowset_summary_all_data['sumassigned']; 
								$countunpaiddesa = $rowset_summary_all_data['countunpaiddesa']; 
								$sumunpaiddesa = $rowset_summary_all_data['sumunpaiddesa']; 
								$countpaiddesa = $rowset_summary_all_data['countpaiddesa']; 
								$sumpaiddesa = $rowset_summary_all_data['sumpaiddesa']; 
								$countunpaidunassigned = $rowset_summary_all_data['countunpaidunassigned']; 
								$sumunpaidunassigned = $rowset_summary_all_data['sumunpaidunassigned']; 
								$countpaidunassigned = $rowset_summary_all_data['countpaidunassigned']; 
								$sumpaidunassigned = $rowset_summary_all_data['sumpaidunassigned']; 
								$countunpaidassigned = $rowset_summary_all_data['countunpaidassigned']; 
								$sumunpaidassigned = $rowset_summary_all_data['sumunpaidassigned']; 
								$countpaidassigned = $rowset_summary_all_data['countpaidassigned']; 
								$sumpaidassigned = $rowset_summary_all_data['sumpaidassigned'];
							}
							/* select all sppt */
							$stmt_select_data = $dbcon->prepare("SELECT KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,TGL_TERBIT_SPPT,STATUS_PEMBAYARAN_SPPT,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND STATUS_PEMBAYARAN_SPPT != 0 AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' ORDER BY TGL_PEMBAYARAN_SPPT,KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP");
							$stmt_select_data->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
							$stmt_select_data->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
							$stmt_select_data->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
							$stmt_select_data->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
							$stmt_select_data->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
							if ($stmt_select_data->execute()) {
								while($rowset_select_data = $stmt_select_data->fetch(PDO::FETCH_ASSOC)){
									$items[] = $rowset_select_data;
								}
								$response = array(
									"ajaxresult"=>"found",
									"datacontext"=>"GET_MYSQL_PEMBAYARAN_LOCAL",
									"kodeprov"=>$kode_propinsi,
									"kodekab"=>$kode_dati2,
									"kodekec"=>$kode_kecamatan,
									"kodedesa"=>$kode_kelurahan,
									"thnpajak"=>$thnsppt,
									"namakecamatan"=>$namaKecamatan,
									"namakelurahan"=>$namaKelurahan,
									"countpersonil"=>$countpersonil,
									
									"totalcountsppt"=>$totalcountsppt,
									"totalsumsppt"=>$totalsumsppt,
									"countallpenetapan"=>$countallpenetapan,
									"countdefaultpenetapan"=>$countdefaultpenetapan,
									"sumdefaultpenetapan"=>$sumdefaultpenetapan,
									"countlaterpenetapan"=>$countlaterpenetapan,
									"sumlaterpenetapan"=>$sumlaterpenetapan,
									"countunassigned"=>$countunassigned,
									"sumunassigned"=>$sumunassigned,
									"countassigned"=>$countassigned,
									"sumassigned"=>$sumassigned,
									"countunpaiddesa"=>$countunpaiddesa,
									"sumunpaiddesa"=>$sumunpaiddesa,
									"countpaiddesa"=>$countpaiddesa,
									"sumpaiddesa"=>$sumpaiddesa,
									"countunpaidunassigned"=>$countunpaidunassigned,
									"sumunpaidunassigned"=>$sumunpaidunassigned,
									"countpaidunassigned"=>$countpaidunassigned,
									"sumpaidunassigned"=>$sumpaidunassigned,
									"countunpaidassigned"=>$countunpaidassigned,
									"sumunpaidassigned"=>$sumunpaidassigned,
									"countpaidassigned"=>$countpaidassigned,
									"sumpaidassigned"=>$sumpaidassigned,
									
									"rttmptotalcountsppt"=>$RTTMPtotalcountsppt,
									"rttmptotalsumsppt"=>$RTTMPtotalsumsppt,
									"rttmpcountdefaultpenetapan"=>$RTTMPcountdefaultpenetapan,
									"rttmpsumdefaultpenetapan"=>$RTTMPsumdefaultpenetapan,
									"rttmpcountlaterpenetapan"=>$RTTMPcountlaterpenetapan,
									"rttmpsumlaterpenetapan"=>$RTTMPsumlaterpenetapan,
									"rttmpcountunassigned"=>$RTTMPcountunassigned,
									"rttmpsumunassigned"=>$RTTMPsumunassigned,
									"rttmpcountassigned"=>$RTTMPcountassigned,
									"rttmpsumassigned"=>$RTTMPsumassigned,
									"rttmpcountunpaid"=>$RTTMPcountunpaiddesa,
									"rttmpsumunpaid"=>$RTTMPsumunpaiddesa,
									"rttmpcountpaid"=>$RTTMPcountpaiddesa,
									"rttmpsumpaid"=>$RTTMPsumpaiddesa,
									"rttmpsumpaiddenda"=>$RTTMPsumpaiddendadesa,
									"rttmpcountunpaidunassigned"=>$RTTMPcountunpaidunassigned,
									"rttmpsumunpaidunassigned"=>$RTTMPsumunpaidunassigned,
									"rttmpcountpaidunassigned"=>$RTTMPcountpaidunassigned,
									"rttmpsumpaidunassigned"=>$RTTMPsumpaidunassigned,
									"rttmpcountunpaidassigned"=>$RTTMPcountunpaidassigned,
									"rttmpsumunpaidassigned"=>$RTTMPsumunpaidassigned,
									"rttmpcountpaidassigned"=>$RTTMPcountpaidassigned,
									"rttmpsumpaidassigned"=>$RTTMPsumpaidassigned,
									"rttmpsumpaidlaterpenetapan"=>$RTTMPsumpaidlaterpenetapan,
									"rttmpsumunpaidlaterpenetapan"=>$RTTMPsumunpaidlaterpenetapan,
									"rttmpsumpaiddefaultpenetapan"=>$RTTMPsumpaiddefaultpenetapan,
									"rttmpsumunpaiddefaultpenetapan"=>$RTTMPsumunpaiddefaultpenetapan,
									
									"dataarray"=>$items);
								header('Content-Type: application/json');
								echo json_encode($response);
								$dbcon = null;
								exit;
							} else {
								$response = array("ajaxresult"=>"notfound");
								header('Content-Type: application/json');
								echo json_encode($response);
								$dbcon = null;
								exit;
							}
						} else {
							$response = array("ajaxresult"=>"notfound");
							header('Content-Type: application/json');
							echo json_encode($response);
							$dbcon = null;
							exit;
						}
					} else {
						$response = array("ajaxresult"=>"notfound");
						header('Content-Type: application/json');
						echo json_encode($response);
						$dbcon = null;
						exit;
					}
				} else {
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"".$e->getMessage()."");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
		} elseif ($_GET['cmdx'] == "GET_MYSQL_PENAGIHAN_LOCAL") {
    	$searchscope = $_GET['desaidx'];
			$arrDesaIDX = explode("|", $searchscope);
			$kode_propinsi = $_SESSION['kecamatanXweb']['KD_PROPINSI'];
			$kode_dati2 = $_SESSION['kecamatanXweb']['KD_DATI2'];
			$kode_kecamatan = $_SESSION['kecamatanXweb']['KD_KECAMATAN'];
			$kode_kelurahan = $arrDesaIDX[3];
			$namaKecamatan = $_SESSION['kecamatanXweb']['NM_KECAMATAN'];
			$namaKelurahan = $arrDesaIDX[5];
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
				
				/* ======= table: dhkp_temporary_sppt_data ======== */
				/* count total jumlah/lembar SPPT, total penetapan, dan total SPPT terbayar dari table dhkp_temporary_sppt_data */
				$stmt_summary_dhkp_local_a = $dbcon->prepare("
					SELECT 
						COUNT(*) AS totalcountsppt, 
						COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, 
						COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) AS sumpaiddesa 
					FROM dhkp_temporary_sppt_data");
				$stmt_summary_dhkp_local_a->execute();
				while($rowset_summary_dhkp_local_a = $stmt_summary_dhkp_local_a->fetch(PDO::FETCH_ASSOC)){
					$RTTMPtotalcountsppt = $rowset_summary_dhkp_local_a['totalcountsppt'];
					$RTTMPtotalsumsppt = $rowset_summary_dhkp_local_a['totalsumsppt'];
					$RTTMPsumpaiddesa = $rowset_summary_dhkp_local_a['sumpaiddesa'];
				}
				/* count total jumlah/lembar SPPT, total penetapan, dan total SPPT terbayar dari table dhkp_temporary_sppt_data, yang tgl penetapannya 02-01-[TAHUN_PAJAK] */
				$stmt_summary_dhkp_local_b = $dbcon->prepare("
					SELECT 
						COUNT(*) AS totalcountsppt, 
						COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, 
						COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) AS sumpaiddefault 
					FROM 
						dhkp_temporary_sppt_data 
					WHERE 
						TGL_TERBIT_SPPT = :defaulttglterbit");
				$stmt_summary_dhkp_local_b->bindValue(":defaulttglterbit", $tglterbitdefault, PDO::PARAM_STR);
				$stmt_summary_dhkp_local_b->execute();
				while($rowset_summary_dhkp_local_b = $stmt_summary_dhkp_local_b->fetch(PDO::FETCH_ASSOC)){
					$RTTMPcountdefaultpenetapan = $rowset_summary_dhkp_local_b['totalcountsppt'];
					$RTTMPsumdefaultpenetapan = $rowset_summary_dhkp_local_b['totalsumsppt'];
					$RTTMPsumpaiddefaultpenetapan = $rowset_summary_dhkp_local_b['sumpaiddefault'];
				}
				/* count total jumlah/lembar SPPT, total penetapan, dan total SPPT terbayar dari table dhkp_temporary_sppt_data, yang tgl penetapannya > 02-01-[TAHUN_PAJAK] */
				$stmt_summary_dhkp_local_c = $dbcon->prepare("
					SELECT 
						COUNT(*) AS totalcountsppt, 
						COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, 
						COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) AS sumpaidlater 
					FROM 
						dhkp_temporary_sppt_data 
					WHERE 
						TGL_TERBIT_SPPT > :defaulttglterbit");
				$stmt_summary_dhkp_local_c->bindValue(":defaulttglterbit", $tglterbitdefault, PDO::PARAM_STR);
				$stmt_summary_dhkp_local_c->execute();
				while($rowset_summary_dhkp_local_c = $stmt_summary_dhkp_local_c->fetch(PDO::FETCH_ASSOC)){
					$RTTMPcountlaterpenetapan = $rowset_summary_dhkp_local_c['totalcountsppt'];
					$RTTMPsumlaterpenetapan = $rowset_summary_dhkp_local_c['totalsumsppt'];
					$RTTMPsumpaidlaterpenetapan = $rowset_summary_dhkp_local_c['sumpaidlater'];
				}
				/* count total jumlah/lembar SPPT, total penetapan, dan total SPPT terbayar dari table dhkp_temporary_sppt_data, yang belum di-assign ke petugas pungut desa */
				$stmt_summary_dhkp_local_d = $dbcon->prepare("
					SELECT 
						COUNT(*) AS totalcountsppt, 
						COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, 
						COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) AS sumpaidunassigned 
					FROM 
						dhkp_temporary_sppt_data 
					WHERE 
						PETUGASIDX = 0");
				$stmt_summary_dhkp_local_d->execute();
				while($rowset_summary_dhkp_local_d = $stmt_summary_dhkp_local_d->fetch(PDO::FETCH_ASSOC)){
					$RTTMPcountunassigned = $rowset_summary_dhkp_local_d['totalcountsppt'];
					$RTTMPsumunassigned = $rowset_summary_dhkp_local_d['totalsumsppt'];
					$RTTMPsumpaidunassigned = $rowset_summary_dhkp_local_d['sumpaidunassigned'];
				}
				/* count total jumlah/lembar SPPT BELUM DIBAYAR dari table dhkp_temporary_sppt_data */
				$stmt_summary_dhkp_local_f = $dbcon->prepare("
					SELECT 
						COUNT(*) AS totalcountsppt 
					FROM 
						dhkp_temporary_sppt_data 
					WHERE 
						JML_SPPT_YG_DIBAYAR = 0");
				$stmt_summary_dhkp_local_f->execute();
				while($rowset_summary_dhkp_local_f = $stmt_summary_dhkp_local_f->fetch(PDO::FETCH_ASSOC)){
					$RTTMPcountunpaiddesa = $rowset_summary_dhkp_local_f['totalcountsppt'];
					
				}
				/* count total jumlah/lembar SPPT BELUM DIBAYAR + UNASSIGNED dari table dhkp_temporary_sppt_data */
				$stmt_summary_dhkp_local_g = $dbcon->prepare("
					SELECT 
						COUNT(*) AS totalcountsppt 
					FROM 
						dhkp_temporary_sppt_data 
					WHERE 
						JML_SPPT_YG_DIBAYAR = 0 
						AND PETUGASIDX = 0");
				$stmt_summary_dhkp_local_g->execute();
				while($rowset_summary_dhkp_local_g = $stmt_summary_dhkp_local_g->fetch(PDO::FETCH_ASSOC)){
					$RTTMPcountunpaidunassigned = $rowset_summary_dhkp_local_g['totalcountsppt'];
				}
				/* count total jumlah/lembar SPPT BELUM DIBAYAR + ASSIGNED dari table dhkp_temporary_sppt_data */
				$stmt_summary_dhkp_local_h = $dbcon->prepare("
					SELECT 
						COUNT(*) AS totalcountsppt 
					FROM 
						dhkp_temporary_sppt_data 
					WHERE 
						JML_SPPT_YG_DIBAYAR = 0 
						AND PETUGASIDX > 0");
				$stmt_summary_dhkp_local_h->execute();
				while($rowset_summary_dhkp_local_h = $stmt_summary_dhkp_local_h->fetch(PDO::FETCH_ASSOC)){
					$RTTMPcountunpaidassigned = $rowset_summary_dhkp_local_h['totalcountsppt'];
				}
				/* some small-but-important otf-calculation */
				$RTTMPsumunpaiddesa = intval($RTTMPtotalsumsppt) - intval($RTTMPsumpaiddesa);
				$RTTMPsumunpaiddefaultpenetapan = intval($RTTMPsumdefaultpenetapan) - intval($RTTMPsumpaiddefaultpenetapan);
				$RTTMPsumunpaidlaterpenetapan = intval($RTTMPsumlaterpenetapan) - intval($RTTMPsumpaidlaterpenetapan);
				$RTTMPcountassigned = intval($RTTMPtotalcountsppt) - intval($RTTMPcountunassigned);
				$RTTMPsumassigned = intval($RTTMPtotalsumsppt) - intval($RTTMPsumunassigned);
				$RTTMPsumpaidassigned = intval($RTTMPsumpaiddesa) - intval($RTTMPsumpaidunassigned);
				$RTTMPsumunpaidunassigned = intval($RTTMPsumunassigned) - intval($RTTMPsumpaidunassigned);
				$RTTMPsumunpaidassigned = intval($RTTMPsumassigned) - intval($RTTMPsumpaidassigned);
				$RTTMPcountpaiddesa = intval($RTTMPtotalcountsppt) - intval($RTTMPcountunpaiddesa);
				$RTTMPcountpaidunassigned = intval($RTTMPcountunassigned) - intval($RTTMPcountunpaidunassigned);
				$RTTMPcountpaidassigned = intval($RTTMPcountassigned) - intval($RTTMPcountunpaidassigned);
				/* ======= table: dhkp_temporary_sppt_data ======== */
				/* counting lembar sppt - desa */
				$stmt_cnt = $dbcon->prepare("SELECT COUNT(*) as foundrows FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak");
				$stmt_cnt->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
				$stmt_cnt->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
				$stmt_cnt->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
				$stmt_cnt->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
				$stmt_cnt->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
				if ($stmt_cnt->execute()) {
					while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
						$rowsfound = $rowset_cnt['foundrows'];
					}
					if (intval($rowsfound)>0) {
						/* counting data */
						$stmt_cnt_personil = $dbcon->prepare("SELECT COUNT(*) as foundpetugas FROM appx_desa_petugas_pungut WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa");
						$stmt_cnt_personil->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
						$stmt_cnt_personil->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
						$stmt_cnt_personil->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
						$stmt_cnt_personil->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
						if ($stmt_cnt_personil->execute()) {
							while($rowset_cnt_personil = $stmt_cnt_personil->fetch(PDO::FETCH_ASSOC)){
								$countpersonil = $rowset_cnt_personil['foundpetugas'];
							}
						} else {
							$countpersonil = 0;
						}
						/* ====================================== */
						$stmt_summary = $dbcon->prepare("SELECT COUNT(*) AS totalcountsppt, COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprova AND KD_DATI2 = :kdkaba AND KD_KECAMATAN = :kdkeca AND KD_KELURAHAN = :kddesaa AND THN_PAJAK_SPPT = :thnpajaka) AS countallpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovb AND KD_DATI2 = :kdkabb AND KD_KECAMATAN = :kdkecb AND KD_KELURAHAN = :kddesab AND THN_PAJAK_SPPT = :thnpajakb AND TGL_TERBIT_SPPT = CONCAT(:thnpajakbb,'-01-02')) AS countdefaultpenetapan, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovc AND KD_DATI2 = :kdkabc AND KD_KECAMATAN = :kdkecc AND KD_KELURAHAN = :kddesac AND THN_PAJAK_SPPT = :thnpajakc AND TGL_TERBIT_SPPT = CONCAT(:thnpajakcc,'-01-02')) AS sumdefaultpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovd AND KD_DATI2 = :kdkabd AND KD_KECAMATAN = :kdkecd AND KD_KELURAHAN = :kddesad AND THN_PAJAK_SPPT = :thnpajakd AND TGL_TERBIT_SPPT != CONCAT(:thnpajakdd,'-01-02')) AS countlaterpenetapan, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprove AND KD_DATI2 = :kdkabe AND KD_KECAMATAN = :kdkece AND KD_KELURAHAN = :kddesae AND THN_PAJAK_SPPT = :thnpajake AND TGL_TERBIT_SPPT != CONCAT(:thnpajakee,'-01-02')) AS sumlaterpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovf AND KD_DATI2 = :kdkabf AND KD_KECAMATAN = :kdkecf AND KD_KELURAHAN = :kddesaf AND THN_PAJAK_SPPT = :thnpajakf AND PETUGASIDX = 0) AS countunassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovg AND KD_DATI2 = :kdkabg AND KD_KECAMATAN = :kdkecg AND KD_KELURAHAN = :kddesag AND THN_PAJAK_SPPT = :thnpajakg AND PETUGASIDX = 0) AS sumunassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovh AND KD_DATI2 = :kdkabh AND KD_KECAMATAN = :kdkech AND KD_KELURAHAN = :kddesah AND THN_PAJAK_SPPT = :thnpajakh AND PETUGASIDX > 0) AS countassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovi AND KD_DATI2 = :kdkabi AND KD_KECAMATAN = :kdkeci AND KD_KELURAHAN = :kddesai AND THN_PAJAK_SPPT = :thnpajaki AND PETUGASIDX > 0) AS sumassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovj AND KD_DATI2 = :kdkabj AND KD_KECAMATAN = :kdkecj AND KD_KELURAHAN = :kddesaj AND THN_PAJAK_SPPT = :thnpajakj AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS countunpaiddesa, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovk AND KD_DATI2 = :kdkabk AND KD_KECAMATAN = :kdkeck AND KD_KELURAHAN = :kddesak AND THN_PAJAK_SPPT = :thnpajakk AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS sumunpaiddesa, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovl AND KD_DATI2 = :kdkabl AND KD_KECAMATAN = :kdkecl AND KD_KELURAHAN = :kddesal AND THN_PAJAK_SPPT = :thnpajakl AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS countpaiddesa, (SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovm AND KD_DATI2 = :kdkabm AND KD_KECAMATAN = :kdkecm AND KD_KELURAHAN = :kddesam AND THN_PAJAK_SPPT = :thnpajakm AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS sumpaiddesa, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovn AND KD_DATI2 = :kdkabn AND KD_KECAMATAN = :kdkecn AND KD_KELURAHAN = :kddesan AND THN_PAJAK_SPPT = :thnpajakn AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX = 0) AS countunpaidunassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovo AND KD_DATI2 = :kdkabo AND KD_KECAMATAN = :kdkeco AND KD_KELURAHAN = :kddesao AND THN_PAJAK_SPPT = :thnpajako AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX = 0) AS sumunpaidunassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovp AND KD_DATI2 = :kdkabp AND KD_KECAMATAN = :kdkecp AND KD_KELURAHAN = :kddesap AND THN_PAJAK_SPPT = :thnpajakp AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX = 0) AS countpaidunassigned, (SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovq AND KD_DATI2 = :kdkabq AND KD_KECAMATAN = :kdkecq AND KD_KELURAHAN = :kddesaq AND THN_PAJAK_SPPT = :thnpajakq AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX = 0) AS sumpaidunassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovr AND KD_DATI2 = :kdkabr AND KD_KECAMATAN = :kdkecr AND KD_KELURAHAN = :kddesar AND THN_PAJAK_SPPT = :thnpajakr AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX > 0) AS countunpaidassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovs AND KD_DATI2 = :kdkabs AND KD_KECAMATAN = :kdkecs AND KD_KELURAHAN = :kddesas AND THN_PAJAK_SPPT = :thnpajaks AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX > 0) AS sumunpaidassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovt AND KD_DATI2 = :kdkabt AND KD_KECAMATAN = :kdkect AND KD_KELURAHAN = :kddesat AND THN_PAJAK_SPPT = :thnpajakt AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX > 0) AS countpaidassigned, (SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovu AND KD_DATI2 = :kdkabu AND KD_KECAMATAN = :kdkecu AND KD_KELURAHAN = :kddesau AND THN_PAJAK_SPPT = :thnpajaku AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX > 0) AS sumpaidassigned FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak");
						$stmt_summary->bindValue(":kdprova", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkaba", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkeca", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesaa", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajaka", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovb", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabb", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecb", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesab", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakb", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakbb", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovc", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabc", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecc", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesac", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakc", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakcc", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovd", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabd", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecd", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesad", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakd", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakdd", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprove", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabe", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkece", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesae", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajake", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakee", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovf", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabf", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecf", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesaf", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakf", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovg", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabg", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecg", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesag", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakg", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovh", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabh", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkech", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesah", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakh", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovi", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabi", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkeci", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesai", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajaki", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovj", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabj", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecj", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesaj", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakj", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovk", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabk", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkeck", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesak", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakk", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovl", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabl", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecl", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesal", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakl", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovm", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabm", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecm", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesam", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakm", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovn", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabn", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecn", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesan", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakn", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovo", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabo", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkeco", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesao", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajako", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovp", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabp", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecp", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesap", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakp", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovq", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabq", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecq", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesaq", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakq", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovr", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabr", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecr", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesar", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakr", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovs", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabs", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecs", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesas", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajaks", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovt", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabt", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkect", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesat", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajakt", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprovu", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkabu", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkecu", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesau", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajaku", $thnsppt, PDO::PARAM_STR); $stmt_summary->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR); $stmt_summary->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR); $stmt_summary->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR); $stmt_summary->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
						if ($stmt_summary->execute()) {
							while($rowset_summary = $stmt_summary->fetch(PDO::FETCH_ASSOC)){
								$totalcountsppt = $rowset_summary['totalcountsppt'];$totalsumsppt = $rowset_summary['totalsumsppt'];$countallpenetapan = $rowset_summary['countallpenetapan'];$countdefaultpenetapan = $rowset_summary['countdefaultpenetapan'];$sumdefaultpenetapan = $rowset_summary['sumdefaultpenetapan'];$countlaterpenetapan = $rowset_summary['countlaterpenetapan']; $sumlaterpenetapan = $rowset_summary['sumlaterpenetapan'];$countunassigned = $rowset_summary['countunassigned']; $sumunassigned = $rowset_summary['sumunassigned'];$countassigned = $rowset_summary['countassigned']; $sumassigned = $rowset_summary['sumassigned'];$countunpaiddesa = $rowset_summary['countunpaiddesa']; $sumunpaiddesa = $rowset_summary['sumunpaiddesa'];$countpaiddesa = $rowset_summary['countpaiddesa']; $sumpaiddesa = $rowset_summary['sumpaiddesa'];$countunpaidunassigned = $rowset_summary['countunpaidunassigned']; $sumunpaidunassigned = $rowset_summary['sumunpaidunassigned'];$countpaidunassigned = $rowset_summary['countpaidunassigned']; $sumpaidunassigned = $rowset_summary['sumpaidunassigned'];$countunpaidassigned = $rowset_summary['countunpaidassigned'];$sumunpaidassigned = $rowset_summary['sumunpaidassigned'];$countpaidassigned = $rowset_summary['countpaidassigned'];$sumpaidassigned = $rowset_summary['sumpaidassigned'];
							}
							/* select all sppt */
							/* select all unpaid sppt based on tahun pajak */
							/*  ---- disabled to get attention ---- $stmt_select_data = $dbcon->prepare("SELECT KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,TGL_TERBIT_SPPT,STATUS_PEMBAYARAN_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,PETUGASIDX,NAMAPETUGAS FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND STATUS_PEMBAYARAN_SPPT = 0 AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' ORDER BY KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT"); */
							/* select all unpaid sppt (all tahun pajak) - test case. If use this, disable bindValue too. */
							$stmt_select_data = $dbcon->prepare("SELECT KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,TGL_TERBIT_SPPT,STATUS_PEMBAYARAN_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,PETUGASIDX,NAMAPETUGAS FROM dhkp_temporary_sppt_data WHERE STATUS_PEMBAYARAN_SPPT = 0 AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' ORDER BY KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT");
							/* $stmt_select_data->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
							$stmt_select_data->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
							$stmt_select_data->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
							$stmt_select_data->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR); */
							/*  ---- disabled to get attention ---- $stmt_select_data->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR); */
							if ($stmt_select_data->execute()) {
								while($rowset_select_data = $stmt_select_data->fetch(PDO::FETCH_ASSOC)){
									$items[] = $rowset_select_data;
								}
								$response = array(
									"ajaxresult"=>"found",
									"datacontext"=>"GET_MYSQL_PENAGIHAN_LOCAL",
									
									"kodeprov"=>$kode_propinsi,
									"kodekab"=>$kode_dati2,
									"kodekec"=>$kode_kecamatan,
									"kodedesa"=>$kode_kelurahan,
									"thnpajak"=>$thnsppt,
									"namakecamatan"=>$namaKecamatan,
									"namakelurahan"=>$namaKelurahan,
									
									"countpersonil"=>$countpersonil,
									
									"totalcountsppt"=>$totalcountsppt,
									"totalsumsppt"=>$totalsumsppt,
									"countallpenetapan"=>$countallpenetapan,
									"countdefaultpenetapan"=>$countdefaultpenetapan,
									"sumdefaultpenetapan"=>$sumdefaultpenetapan,
									"countlaterpenetapan"=>$countlaterpenetapan,
									"sumlaterpenetapan"=>$sumlaterpenetapan,
									"countunassigned"=>$countunassigned,
									"sumunassigned"=>$sumunassigned,
									"countassigned"=>$countassigned,
									"sumassigned"=>$sumassigned,
									"countunpaiddesa"=>$countunpaiddesa,
									"sumunpaiddesa"=>$sumunpaiddesa,
									"countpaiddesa"=>$countpaiddesa,
									"sumpaiddesa"=>$sumpaiddesa,
									"countunpaidunassigned"=>$countunpaidunassigned,
									"sumunpaidunassigned"=>$sumunpaidunassigned,
									"countpaidunassigned"=>$countpaidunassigned,
									"sumpaidunassigned"=>$sumpaidunassigned,
									"countunpaidassigned"=>$countunpaidassigned,
									"sumunpaidassigned"=>$sumunpaidassigned,
									"countpaidassigned"=>$countpaidassigned,
									"sumpaidassigned"=>$sumpaidassigned,
									
									"rttmptotalcountsppt"=>$RTTMPtotalcountsppt,
									"rttmptotalsumsppt"=>$RTTMPtotalsumsppt,
									"rttmpcountdefaultpenetapan"=>$RTTMPcountdefaultpenetapan,
									"rttmpsumdefaultpenetapan"=>$RTTMPsumdefaultpenetapan,
									"rttmpcountlaterpenetapan"=>$RTTMPcountlaterpenetapan,
									"rttmpsumlaterpenetapan"=>$RTTMPsumlaterpenetapan,
									"rttmpcountunassigned"=>$RTTMPcountunassigned,
									"rttmpsumunassigned"=>$RTTMPsumunassigned,
									"rttmpcountassigned"=>$RTTMPcountassigned,
									"rttmpsumassigned"=>$RTTMPsumassigned,
									"rttmpcountunpaid"=>$RTTMPcountunpaiddesa,
									"rttmpsumunpaid"=>$RTTMPsumunpaiddesa,
									"rttmpcountpaid"=>$RTTMPcountpaiddesa,
									"rttmpsumpaid"=>$RTTMPsumpaiddesa,
									"rttmpcountunpaidunassigned"=>$RTTMPcountunpaidunassigned,
									"rttmpsumunpaidunassigned"=>$RTTMPsumunpaidunassigned,
									"rttmpcountpaidunassigned"=>$RTTMPcountpaidunassigned,
									"rttmpsumpaidunassigned"=>$RTTMPsumpaidunassigned,
									"rttmpcountunpaidassigned"=>$RTTMPcountunpaidassigned,
									"rttmpsumunpaidassigned"=>$RTTMPsumunpaidassigned,
									"rttmpcountpaidassigned"=>$RTTMPcountpaidassigned,
									"rttmpsumpaidassigned"=>$RTTMPsumpaidassigned,
									"rttmpsumpaidlaterpenetapan"=>$RTTMPsumpaidlaterpenetapan,
									"rttmpsumunpaidlaterpenetapan"=>$RTTMPsumunpaidlaterpenetapan,
									"rttmpsumpaiddefaultpenetapan"=>$RTTMPsumpaiddefaultpenetapan,
									"rttmpsumunpaiddefaultpenetapan"=>$RTTMPsumunpaiddefaultpenetapan,
									
									"dataarray"=>$items);
								header('Content-Type: application/json');
								echo json_encode($response);
								$dbcon = null;
								exit;
							} else {
								$response = array("ajaxresult"=>"notfound");
								header('Content-Type: application/json');
								echo json_encode($response);
								$dbcon = null;
								exit;
							}
						} else {
							$response = array("ajaxresult"=>"notfound");
							header('Content-Type: application/json');
							echo json_encode($response);
							$dbcon = null;
							exit;
						}
					} else {
						$response = array("ajaxresult"=>"notfound");
						header('Content-Type: application/json');
						echo json_encode($response);
						$dbcon = null;
						exit;
					}
				} else {
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"".$e->getMessage()."");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
    } else {
			$response = array("ajaxresult"=>"undefined");
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
    }
} else {
	/* IF cmdx is not defined */
	$response = array("ajaxresult"=>"undefined");
	header('Content-Type: application/json');
	echo json_encode($response);
	exit;
}
?>
	