<?php
/* ERROR DEBUG HANDLE */
error_reporting(E_ALL);
ini_set("display_errors", 1);
/* SESSION START */
session_start();
$txsessionid = session_id();
/* CONFIGURATION */
require_once dirname(__FILE__) . '/../variablen.php';
require_once dirname(__FILE__) . '/../einstellung.php';
require_once dirname(__FILE__) . '/../funktion.php';
header("Access-Control-Allow-Origin: *");
if (isset($_GET['cmdx'])){
	$dasFunktion = new dasFunktionz();
	if ($_GET['cmdx'] == "dummy") {
		$response = $dasFunktion->dummy();
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;
	} elseif ($_GET['cmdx'] == "THOROUGH_SEARCH_CHECK") {
		$querystr = $_GET['strquery'];
		if (is_numeric($querystr)) {
			/*
			 * 192.168.1.200/mapatda-dppkad/ajax_datatables_engine.php?cmdx=THOROUGH_SEARCH_MAPATDA&strquery=3323999990000100
			 */
			if (strlen($querystr)==16) { /* NIK or NPWPD */
				try {
					$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
					$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$stmt_cnt = $dbcon->prepare("
						SELECT COUNT(*) AS foundrows FROM ((SELECT 
							nik AS base,
							kategori AS category,
							npwpd AS npwpd,
							nama AS nama,
							alamat AS alamat,
							'DATA PENDUDUK' AS context,
							'DP/F' AS remark 
							FROM appx_master_data_penduduk WHERE nik = :querystri) 
						UNION ALL 
						(SELECT 
							npwpd AS base,
							kategori AS category,
							npwpd AS npwpd,
							nama AS nama,
							alamat AS alamat,
							'DATA WAJIB PAJAK/RETRIBUSI' AS context,
							'DWP' AS remark 
							FROM appx_wp_base WHERE npwpd = :querystrii) 
						UNION ALL 
						(SELECT 
							nik_pj AS base,
							kategori_pj AS category,
							npwpd AS npwpd,
							nama_pj AS nama,
							alamat_pj AS alamat,
							'PENANGGUNGJAWAB BADAN' AS context,
							'PJB' AS remark 
							FROM appx_wp_badan WHERE nik_pj = :querystriii)) thorough_search");
					$stmt_cnt->bindValue(":querystri", $querystr, PDO::PARAM_STR);
					$stmt_cnt->bindValue(":querystrii", $querystr, PDO::PARAM_STR);
					$stmt_cnt->bindValue(":querystriii", $querystr, PDO::PARAM_STR);
					if($stmt_cnt->execute()){
						while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
							$rows = $rowset_cnt['foundrows'];
						}
						if (intval($rows)>0) {
							$stmt = $dbcon->prepare("
								SELECT * FROM ((SELECT 
									nik AS base,
									kategori AS category,
									npwpd AS npwpd,
									nama AS nama,
									alamat AS alamat,
									'DATA PENDUDUK' AS context,
									'DP/F' AS remark 
									FROM appx_master_data_penduduk WHERE nik = :querystri) 
								UNION ALL 
								(SELECT 
									npwpd AS base,
									kategori AS category,
									npwpd AS npwpd,
									nama AS nama,
									alamat AS alamat,
									'DATA WAJIB PAJAK/RETRIBUSI' AS context,
									'DWP' AS remark 
									FROM appx_wp_base WHERE npwpd = :querystrii) 
								UNION ALL 
								(SELECT 
									nik_pj AS base,
									kategori_pj AS category,
									npwpd AS npwpd,
									nama_pj AS nama,
									alamat_pj AS alamat,
									'PENANGGUNGJAWAB BADAN' AS context,
									'PJB' AS remark 
									FROM appx_wp_badan WHERE nik_pj = :querystriii)) thorough_search");
							$stmt->bindValue(":querystri", $querystr, PDO::PARAM_STR);
							$stmt->bindValue(":querystrii", $querystr, PDO::PARAM_STR);
							$stmt->bindValue(":querystriii", $querystr, PDO::PARAM_STR);
							if($stmt->execute()){
								while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
									$items[] = $rowset;
								}
								$response = array("ajaxresult"=>"found","dataarray"=>$items);
								header('Content-Type: application/json');
								echo json_encode($response);
								$dbcon = null;
								exit;
							} else {
								$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"Tidak ditemukan data yang mengandung [ ".$querystr." ].");
								header('Content-Type: application/json');
								echo json_encode($response);
								$dbcon = null;
								exit;
							}
						} else {
							$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"Tidak ditemukan data yang mengandung [ ".$querystr." ].");
							header('Content-Type: application/json');
							echo json_encode($response);
							$dbcon = null;
							exit;
						}
					} else {
						$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"Error: Penghitungan hasil pencarian gagal.");
						header('Content-Type: application/json');
						echo json_encode($response);
						$dbcon = null;
						exit;
					}
				} catch (PDOException $e) {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"Error: ".$e->getMessage()."");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
			} else { /* bad query */
				$response = array("ajaxresult"=>"badquery","ajaxmessage"=>"Pencarian NIK/NPWPD gagal. Periksa kembali NIK/NPWPD.");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
		} else {
			/*
			 * 192.168.1.200/mapatda-dppkad/ajax_datatables_engine.php?cmdx=THOROUGH_SEARCH_MAPATDA&strquery=budi%20su
			 */
			try {
				$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt_cnt = $dbcon->prepare("
					SELECT COUNT(*) AS foundrows FROM ((SELECT 
						nik AS base,
						kategori AS category,
						npwpd AS npwpd,
						nama AS nama,
						alamat AS alamat,
						'DATA PENDUDUK' AS context,
						'DP/F' AS remark 
						FROM appx_master_data_penduduk WHERE nama LIKE :querystri) 
					UNION ALL 
					(SELECT 
						npwpd AS base,
						kategori AS category,
						npwpd AS npwpd,
						nama AS nama,
						alamat AS alamat,
						'DATA WAJIB PAJAK/RETRIBUSI' AS context,
						'DWP' AS remark 
						FROM appx_wp_base WHERE nama LIKE :querystrii) 
					UNION ALL 
					(SELECT 
						nik_pj AS base,
						kategori_pj AS category,
						npwpd AS npwpd,
						nama_pj AS nama,
						alamat_pj AS alamat,
						'PENANGGUNGJAWAB BADAN' AS context,
						'PJB' AS remark 
						FROM appx_wp_badan WHERE nama_pj LIKE :querystriii)) thorough_search");
				$stmt_cnt->bindValue(":querystri", "%".$querystr."%", PDO::PARAM_STR);
				$stmt_cnt->bindValue(":querystrii", "%".$querystr."%", PDO::PARAM_STR);
				$stmt_cnt->bindValue(":querystriii", "%".$querystr."%", PDO::PARAM_STR);
				if($stmt_cnt->execute()){
					while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
						$rows = $rowset_cnt['foundrows'];
					}
					if (intval($rows)>0) {
						$stmt = $dbcon->prepare("
							SELECT * FROM ((SELECT 
								nik AS base,
								kategori AS category,
								npwpd AS npwpd,
								nama AS nama,
								alamat AS alamat,
								'DATA PENDUDUK' AS context,
								'DP/F' AS remark 
								FROM appx_master_data_penduduk WHERE nama LIKE :querystri) 
							UNION ALL 
							(SELECT 
								npwpd AS base,
								kategori AS category,
								npwpd AS npwpd,
								nama AS nama,
								alamat AS alamat,
								'DATA WAJIB PAJAK/RETRIBUSI' AS context,
								'DWP' AS remark 
								FROM appx_wp_base WHERE nama LIKE :querystrii) 
							UNION ALL 
							(SELECT 
								nik_pj AS base,
								kategori_pj AS category,
								npwpd AS npwpd,
								nama_pj AS nama,
								alamat_pj AS alamat,
								'PENANGGUNGJAWAB BADAN' AS context,
								'PJB' AS remark 
								FROM appx_wp_badan WHERE nama_pj LIKE :querystriii)) thorough_search");
						$stmt->bindValue(":querystri", "%".$querystr."%", PDO::PARAM_STR);
						$stmt->bindValue(":querystrii", "%".$querystr."%", PDO::PARAM_STR);
						$stmt->bindValue(":querystriii", "%".$querystr."%", PDO::PARAM_STR);
						if($stmt->execute()){
							while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
								$items[] = $rowset;
							}
							$response = array("ajaxresult"=>"found","dataarray"=>$items);
							header('Content-Type: application/json');
							echo json_encode($response);
							$dbcon = null;
							exit;
						} else {
							$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"Tidak ditemukan data yang mengandung [ ".$querystr." ].");
							header('Content-Type: application/json');
							echo json_encode($response);
							$dbcon = null;
							exit;
						}
					} else {
						$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"Tidak ditemukan data yang mengandung [ ".$querystr." ].");
						header('Content-Type: application/json');
						echo json_encode($response);
						$dbcon = null;
						exit;
					}
				} else {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"Error: Penghitungan hasil pencarian gagal.");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"Error: ".$e->getMessage()."");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
		}
	} else {
		/* IF cmdx is not defined */
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