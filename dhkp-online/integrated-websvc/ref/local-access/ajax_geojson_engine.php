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
	if ($_GET['cmdx'] == "dummy") {
		/* Dummy/prototype AJAX JSON */
		$response = array("ajaxresult"=>"success");
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;
	} elseif ($_GET['cmdx'] == "REKLAME_ACTIVE") {
		$geojson = array(
		   'type'      => 'FeatureCollection',
		   'features'  => array()
		);
		try {
			$dbcon = new PDO("pgsql:host=".$appxinfo['_sig_db_host_'].";port=".$appxinfo['_sig_db_port_'].";dbname=".$appxinfo['_sig_db_name_'].";user=".$appxinfo['_sig_db_user_'].";password=".$appxinfo['_sig_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("SELECT *, public.ST_AsGeoJSON(public.ST_Transform((geom),4326),6) AS geojson FROM view_reklame WHERE CURRENT_DATE BETWEEN startdate AND enddate");
			if($stmt->execute()){
				$id_count = 0;
				while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
					$properties = $rowset;
					unset($properties['geojson']);
					unset($properties['geom']);
				    $feature = array(
				         'type' => 'Feature',
				         'id' => $id_count,
				         'properties' => $properties,
				         'geometry' => json_decode($rowset['geojson'], true)
				    );
					array_push($geojson['features'], $feature);
					$id_count++;
				}
				header('Content-Type: application/json');
				echo json_encode($geojson, JSON_NUMERIC_CHECK);
				$dbcon = null;
				exit;
			} else {
				header('Content-Type: application/json');
				echo json_encode($geojson, JSON_NUMERIC_CHECK);
				$dbcon = null;
				exit;
			}
		} catch (PDOException $e) {
			header('Content-Type: application/json');
			echo json_encode($geojson, JSON_NUMERIC_CHECK);
			$dbcon = null;
			exit;
		}
	} elseif ($_GET['cmdx'] == "AIRTANAH_ACTIVE") {
		$geojson = array(
		   'type'      => 'FeatureCollection',
		   'features'  => array()
		);
		try {
			$dbcon = new PDO("pgsql:host=".$appxinfo['_sig_db_host_'].";port=".$appxinfo['_sig_db_port_'].";dbname=".$appxinfo['_sig_db_name_'].";user=".$appxinfo['_sig_db_user_'].";password=".$appxinfo['_sig_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("SELECT *, public.ST_AsGeoJSON(public.ST_Transform((geom),4326),6) AS geojson FROM view_airtanah");
			if($stmt->execute()){
				$id_count = 0;
				while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
					$properties = $rowset;
					unset($properties['geojson']);
					unset($properties['geom']);
				    $feature = array(
				         'type' => 'Feature',
				         'id' => $id_count,
				         'properties' => $properties,
				         'geometry' => json_decode($rowset['geojson'], true)
				    );
					array_push($geojson['features'], $feature);
					$id_count++;
				}
				header('Content-Type: application/json');
				echo json_encode($geojson, JSON_NUMERIC_CHECK);
				$dbcon = null;
				exit;
			} else {
				header('Content-Type: application/json');
				echo json_encode($geojson, JSON_NUMERIC_CHECK);
				$dbcon = null;
				exit;
			}
		} catch (PDOException $e) {
			header('Content-Type: application/json');
			echo json_encode($geojson, JSON_NUMERIC_CHECK);
			$dbcon = null;
			exit;
		}
	} else {
		
	}
} else {
	
}
?>