<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();
require_once dirname(__FILE__) . '/./variablen.php';
require_once dirname(__FILE__) . '/./einstellung.php';
require_once dirname(__FILE__) . '/./funktion.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: xtoken, xorigin, xauthsupplement");
$reqMethod = $_SERVER['REQUEST_METHOD'];
if ($reqMethod !== 'OPTIONS') {
	if (isset($_SERVER['HTTP_XTOKEN'])) {
		$TruLogix = new dasFunktionz();
		$token = base64_decode($_SERVER['HTTP_XTOKEN']);
		$arrToken = explode('|', $token);
		$request_date = $arrToken[0]; $request_id = $arrToken[1];
		$client_dir = $arrToken[2]; $client_hash_codex = $arrToken[3];
		$public_key_file_handle = fopen("./clients/". $client_dir ."/public_key", "r");
		if ($public_key_file_handle) {
			while (($public_key_file_line = fgets($public_key_file_handle)) !== false) {
				$secret = $public_key_file_line;
			}
			fclose($public_key_file_handle);
			$authKey = hash_hmac_file('sha256', './clients/'. $client_dir .'/core_secret.txt', $secret);
			if ($request_date == date('Ymd') && $authKey == $client_hash_codex) {
/* ######################## ==== BLOCK AUTHORISED SECURE ACCESS ==== ###################################### */
				if (isset($_GET['cmdx'])) {
					if ($_GET['cmdx'] == "GET_DATA_REGISTERED_WAJIB_PAJAK_DAN_RETRIBUSI") {
						/*
						 * 
						 * 
						 */
						try {
							$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
							$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$stmt = $dbcon->prepare("SELECT idx,npwpd,origin,nama,alamat,kategori,status FROM appx_wp_base WHERE status = 1 ORDER BY npwpd,nama");
							if($stmt->execute()){
								while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
									$items[] = $rowset;
								}
								if (empty($items)) {
									$response = array("ajaxresult"=>"notfound");
									header('Content-Type: application/json');
									echo json_encode($response);
									$dbcon = null;
									exit;
								} else {
									$response = array("ajaxresult"=>"found","dataarray"=>$items);
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
							$response = array("ajaxresult"=>"notfound");
							header('Content-Type: application/json');
							echo json_encode($response);
							$dbcon = null;
							exit;
						}
					} elseif ($_GET['cmdx'] == "SEARCH_DATA_REGISTERED_WAJIB_PAJAK_DAN_RETRIBUSI") {
						
					} elseif ($_GET['cmdx'] == "GET_DATA_NON_SPATIAL_KECAMATAN") {
						$response = $TruLogix->getJSONKecamatan();
						header('Content-Type: application/json');
						echo json_encode($response);
						exit;
					} elseif ($_GET['cmdx'] == "GET_DATA_NON_SPATIAL_DESA_KELURAHAN_BY_KODE_DAGRI") {
						$kode_dagri_kecamatan = $_GET['kodedagrikecamatan'];
						$response = $TruLogix->getJSONDagriDesaKelurahan($kode_dagri_kecamatan);
						header('Content-Type: application/json');
						echo json_encode($response);
						exit;
					} else {
						$response = array("response"=>"unauthorised","ticketid"=>"1877");
						header('Content-Type: application/json');
						echo json_encode($response);
						exit;
					}
				} else {
					$response = array("response"=>"unauthorised","ticketid"=>"1876");
					header('Content-Type: application/json');
					echo json_encode($response);
					exit;
				}
/* ######################## ==== BLOCK AUTHORISED SECURE ACCESS ==== ###################################### */
			} else {
				$response = array("response"=>"unauthorised","ticketid"=>"1875");
				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array("response"=>"unauthorised","ticketid"=>"1874");
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		}
	} else {
		$response = array("response"=>"unauthorised","ticketid"=>"1873");
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;
	}
} else {
	$response = array("response"=>"unauthorised","ticketid"=>"1872");
	header('Content-Type: application/json');
	echo json_encode($response);
	exit;
}
?>