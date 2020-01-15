<?php
/* ERROR DEBUG HANDLE */
error_reporting(E_ALL);
ini_set("display_errors", 1);
if(!ini_get('date.timezone')) {
	date_default_timezone_set('GMT');
}
/* SESSION START */
session_start();
$txsessionid = session_id();
/* CONFIGURATION */
require_once ("../einstellung.php");
require_once ("../variablen.php");
require_once ("../funktion.php");
header("Access-Control-Allow-Origin: *");


if (isset($_GET['cmdx'])) {
	/*----------------------------------------------------------------
	 * RETRIEVE DATA
	 * ---------------------------------------------------------------*/

	if ($_GET['cmdx'] == "GET_CHART_PROGRESS_PBB_PAYMENT") {

		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$qry = "SELECT nm_kecamatan AS kecamatan,
					       CAST(total_sppt AS UNSIGNED) AS target,
					       CAST(total_sppt_paid AS UNSIGNED) AS realisasi,
					       CAST(ROUND(((total_sppt_paid/total_sppt)*100), 0) AS UNSIGNED) AS persentase
					FROM summary_kecamatan
					WHERE thn_pajak_sppt = (SELECT EXTRACT(YEAR FROM CURDATE()))
					ORDER BY realisasi DESC";
			$stmt = $dbcon->prepare($qry);

			if($stmt->execute()) {
				$cat = array();
				$arrTarget = array();
				$arrPencapaian = array();
				$result = $stmt->fetchAll(PDO::FETCH_CLASS);
				$colorArray= array('#136F63', '#FFCE54', '#D13A3A');
				for($i=0;$i < count($result); $i++) {
					$res = $result[$i]->kecamatan." "."[". $result[$i]->persentase."%]";
					array_push($cat, $res);

					array_push($arrTarget, (int)$result[$i]->target);

					$color = "";
					if($result[$i]->persentase < 25) {
						$color = $colorArray[2];
					} else if($result[$i]->persentase > 25 && $result[$i]->persentase <= 75) {
						$color = $colorArray[1];
					} else {
						$color = $colorArray[0];
					}

					$acc = array(
					 'y' => (int)$result[$i]->realisasi,
					 'color' => $color
					);

					array_push($arrPencapaian, $acc);
				}

				$seriesTarget = array(
					"name" => "Target",
					"data" => $arrTarget,
					"color" => "#032B43"
				);

				$seriesPencapaian = array(
					"name" => "Pencapaian",
					"data" => $arrPencapaian
				);

				$series = array($seriesTarget, $seriesPencapaian);
				$response = array(
					"ajaxresult" => "found",
					"namaKabupaten" => "Temanggung",
					"namaKecamatan" => "Kranggan",
					"tahunPajak" => date('Y'),
					"pbbKabupaten" => array(
						"categories" => $cat,
						"series" => $series
					),
					"pbbKecamatan" => array(
						"categories" => array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"),
						"series" => array(
							array(
								"name" => "Pencapaian",
								"data" => array(1000, 2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000, 11000, 12000)
							)
						)
					)
				);

				header('Content-Type: application/json');
				echo json_encode($response);
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

	} else {
		$response = array("ajaxresult" => "undefined");
		header('Content-Type: application/json');
		echo json_encode($response);
		exit ;
	}
} else {
	/* IF cmdx is not defined */
	$response = array("ajaxresult" => "undefined");
	header('Content-Type: application/json');
	echo json_encode($response);
	exit ;
}
