<?php
/* ERROR DEBUG HANDLE */
error_reporting(E_ALL);
ini_set("display_errors", 1);
/* SESSION START */
session_start();
$txsessionid = session_id();
/* CONFIGURATION */
require_once("./einstellung.php");
require_once("./variablen.php");
require_once("./funktion.php");
header("Access-Control-Allow-Origin: *");
if (isset($_GET['cmdx'])){
	if ($_GET['cmdx'] == "dummy") {
		$protoFunktion = new dasFunktionz();
		/* Dummy/prototype AJAX JSON */
		// $response = array("ajaxresult"=>"success");
		$response = $protoFunktion->dummy();
		header('Content-Type: application/json');
		echo json_encode($response);
	} elseif ($_GET['cmdx'] == "exit") {
	    session_unset();
	    session_destroy();
	    header("Location: ./");
	    exit;
	} elseif ($_GET['cmdx'] == "GET_CURRENT_DATE") {
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("SELECT CURDATE() AS currensysdbtdate");
			if($stmt->execute()){
				while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
					$currentsysdate = $rowset['currensysdbtdate'];
				}
				$response = array("ajaxresult"=>"found","currentdbsysdate"=>$currentsysdate);
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
			$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"".$e->getMessage()."");
			header('Content-Type: application/json');
			echo json_encode($response);
			$dbcon = null;
			exit;
		}
	} elseif ($_GET['cmdx'] == "LOGIN_DESA_ATTEMPT") {
		$namexuser = $_GET['txusername'];
    $password = hash_hmac('sha1', $_GET['wordxpass'], '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
		$kode_propinsi = substr($namexuser,0,2);
		$kode_dati2 = substr($namexuser,2,2);
		$kode_kecamatan = substr($namexuser,4,3);
		$kode_kelurahan = substr($namexuser,7,3);
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt_A = $dbcon->prepare("SELECT COUNT(*) AS counted, KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN FROM appx_desa_users WHERE KD_PROPINSI = :KDPROP AND KD_DATI2 = :KDKAB AND KD_KECAMATAN = :KDKEC AND KD_KELURAHAN = :KDKEL AND WORDPASS LIKE :PASSXWORDX AND status = 1");
			$stmt_A->bindValue(":KDPROP", $kode_propinsi, PDO::PARAM_STR);
			$stmt_A->bindValue(":KDKAB", $kode_dati2, PDO::PARAM_STR);
			$stmt_A->bindValue(":KDKEC", $kode_kecamatan, PDO::PARAM_STR);
			$stmt_A->bindValue(":KDKEL", $kode_kelurahan, PDO::PARAM_STR);
			$stmt_A->bindValue(":PASSXWORDX", $password, PDO::PARAM_STR);
			if ($stmt_A->execute()) {
				if ($stmt_A->rowCount() > 0) {
					while($rowset_A = $stmt_A->fetch(PDO::FETCH_ASSOC)){
						if (intval($rowset_A['counted']) == 0) {
							$dbcon = null;
							$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
							header('Content-Type: application/json');
							echo json_encode($response);
						} elseif (intval($rowset_A['counted']) == 1) {
							$stmt_B = $dbcon->prepare("SELECT * FROM appx_desa_users WHERE KD_PROPINSI = :KDPROP AND KD_DATI2 = :KDKAB AND KD_KECAMATAN = :KDKEC AND KD_KELURAHAN = :KDKEL AND WORDPASS LIKE :PASSXWORDX AND status = 1");
							$stmt_B->bindValue(":KDPROP", $kode_propinsi, PDO::PARAM_STR);
							$stmt_B->bindValue(":KDKAB", $kode_dati2, PDO::PARAM_STR);
							$stmt_B->bindValue(":KDKEC", $kode_kecamatan, PDO::PARAM_STR);
							$stmt_B->bindValue(":KDKEL", $kode_kelurahan, PDO::PARAM_STR);
							$stmt_B->bindValue(":PASSXWORDX", $password, PDO::PARAM_STR);
							if ($stmt_B->execute()) {
								while($rowset_B = $stmt_B->fetch(PDO::FETCH_ASSOC)){
									$items = $rowset_B;
									$NM_KECAMATAN = $rowset_B['NM_KECAMATAN'];
									$NM_KELURAHAN = $rowset_B['NM_KELURAHAN'];
									$password = $rowset_B['WORDPASS'];
								}
								$_SESSION['sessionID'] = $txsessionid;
								$_SESSION['appxINFO'] = $appxinfo;
								$_SESSION['tahunPAJAK'] = $appxinfo['_tahun_pajak_'];
								$_SESSION['desaXweb'] = $items;
								$dbcon = null;
								$response = array("ajaxresult"=>"found","taxyear"=>Date('Y'),"kdprv"=>$kode_propinsi,"nmprv"=>$appxinfo['_NM_PROV_'],"kdkab"=>$kode_dati2,"nmkab"=>$appxinfo['_NM_DATI2_'],"kdkec"=>$kode_kecamatan,"kdkel"=>$kode_kelurahan,"nmkec"=>$NM_KECAMATAN,"nmkel"=>$NM_KELURAHAN,"kdcmpkab"=>strval("".$kode_propinsi."".$kode_dati2.""),"kdcmpkec"=>strval("".$kode_propinsi."".$kode_dati2."".$kode_kecamatan.""),"kdcmpkel"=>strval("".$kode_propinsi."".$kode_dati2."".$kode_kecamatan."".$kode_kelurahan.""),"dt"=>Date('Y-m-d'),"module"=>"desa","userpassword"=>$password);
								header('Content-Type: application/json');
								echo json_encode($response);
								exit;
							} else {
								$dbcon = null;
								$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Error: SQL Level 2 Error.");
								header('Content-Type: application/json');
								echo json_encode($response);
								exit;
							}
						} else {
							$dbcon = null;
							$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
							header('Content-Type: application/json');
							echo json_encode($response);
							exit;
						}
					}
				} else {
					$dbcon = null;
					$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
					header('Content-Type: application/json');
					echo json_encode($response);
					exit;
				}
			} else {
				$dbcon = null;
				$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Error: SQL Level 1 Error.");
				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Error: ".$e->getMessage()."");
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		}
	} elseif ($_GET['cmdx'] == "LOGIN_KECAMATAN_ATTEMPT") {
		$namexuser = $_GET['txusername'];
    $password = hash_hmac('sha1', $_GET['wordxpass'], '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
		if (strlen($namexuser)==7) {
			$kode_propinsi = substr($namexuser,0,2);
			$kode_dati2 = substr($namexuser,2,2);
			$kode_kecamatan = substr($namexuser,4,3);
			try {
				$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt_count = $dbcon->prepare("SELECT COUNT(*) AS counted, KD_PROPINSI,KD_DATI2,KD_KECAMATAN FROM appx_kecamatan_users WHERE KD_PROPINSI = :KDPROP AND KD_DATI2 = :KDKAB AND KD_KECAMATAN = :KDKEC AND WORDPASS LIKE :PASSXWORDX AND status = 1");
				$stmt_count->bindValue(":KDPROP", $kode_propinsi, PDO::PARAM_STR);
				$stmt_count->bindValue(":KDKAB", $kode_dati2, PDO::PARAM_STR);
				$stmt_count->bindValue(":KDKEC", $kode_kecamatan, PDO::PARAM_STR);
				$stmt_count->bindValue(":PASSXWORDX", $password, PDO::PARAM_STR);
				if ($stmt_count->execute()) {
					if ($stmt_count->rowCount() > 0) {
						while($rowset_count = $stmt_count->fetch(PDO::FETCH_ASSOC)){
							if (intval($rowset_count['counted']) == 0) {
								$dbcon = null;
								$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
								header('Content-Type: application/json');
								echo json_encode($response);
								exit;
							} elseif (intval($rowset_count['counted']) == 1) {
								$stmt_select_data = $dbcon->prepare("SELECT * FROM appx_kecamatan_users WHERE KD_PROPINSI = :KDPROP AND KD_DATI2 = :KDKAB AND KD_KECAMATAN = :KDKEC AND WORDPASS LIKE :PASSXWORDX AND status = 1");
								$stmt_select_data->bindValue(":KDPROP", $kode_propinsi, PDO::PARAM_STR);
								$stmt_select_data->bindValue(":KDKAB", $kode_dati2, PDO::PARAM_STR);
								$stmt_select_data->bindValue(":KDKEC", $kode_kecamatan, PDO::PARAM_STR);
								$stmt_select_data->bindValue(":PASSXWORDX", $password, PDO::PARAM_STR);
								if ($stmt_select_data->execute()) {
									while($rowset_select_data = $stmt_select_data->fetch(PDO::FETCH_ASSOC)){
										$items = $rowset_select_data;
										$NM_KECAMATAN = $rowset_B['NM_KECAMATAN'];
										$password = $rowset_B['WORDPASS'];
									}
									if (!empty($items)) {
										$_SESSION['sessionID'] = $txsessionid;
										$_SESSION['appxINFO'] = $appxinfo;
										$_SESSION['tahunPAJAK'] = $appxinfo['_tahun_pajak_'];
										$_SESSION['kecamatanXweb'] = $items;
										$dbcon = null;
										$response = array("ajaxresult"=>"found","module"=>"kecamatan","taxyear"=>Date('Y'),"kdprv"=>$kode_propinsi,"nmprv"=>$appxinfo['_NM_PROV_'],"kdkab"=>$kode_dati2,"nmkab"=>$appxinfo['_NM_DATI2_'],"kdkec"=>$kode_kecamatan,"nmkec"=>$NM_KECAMATAN,"kdcmpkab"=>strval("".$kode_propinsi."".$kode_dati2.""),"kdcmpkec"=>strval("".$kode_propinsi."".$kode_dati2."".$kode_kecamatan.""),"dt"=>Date('Y-m-d'),"userpassword"=>$password);
										header('Content-Type: application/json');
										echo json_encode($response);
										exit;
									} else {
										$dbcon = null;
										$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
										header('Content-Type: application/json');
										echo json_encode($response);
										exit;
									}
								} else {
									$dbcon = null;
									$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
									header('Content-Type: application/json');
									echo json_encode($response);
									exit;
								}
							} else {
								$dbcon = null;
								$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
								header('Content-Type: application/json');
								echo json_encode($response);
								exit;
							}
						}
					} else {
						$dbcon = null;
						$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
						header('Content-Type: application/json');
						echo json_encode($response);
						exit;
					}
				} else {
					$dbcon = null;
					$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
					header('Content-Type: application/json');
					echo json_encode($response);
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Error: ".$e->getMessage()."");
				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		} elseif (strlen($namexuser)==13) {
			$kode_propinsi = substr($namexuser,6,2);
			$kode_dati2 = substr($namexuser,8,2);
			$kode_kecamatan = substr($namexuser,10,3);
			try {
				$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt_count = $dbcon->prepare("SELECT COUNT(*) AS counted, KD_PROPINSI,KD_DATI2,KD_KECAMATAN FROM appx_camat_users WHERE KD_PROPINSI = :KDPROP AND KD_DATI2 = :KDKAB AND KD_KECAMATAN = :KDKEC AND WORDPASS LIKE :PASSXWORDX AND status = 1");
				$stmt_count->bindValue(":KDPROP", $kode_propinsi, PDO::PARAM_STR);
				$stmt_count->bindValue(":KDKAB", $kode_dati2, PDO::PARAM_STR);
				$stmt_count->bindValue(":KDKEC", $kode_kecamatan, PDO::PARAM_STR);
				$stmt_count->bindValue(":PASSXWORDX", $password, PDO::PARAM_STR);
				if ($stmt_count->execute()) {
					if ($stmt_count->rowCount() > 0) {
						while($rowset_count = $stmt_count->fetch(PDO::FETCH_ASSOC)){
							if (intval($rowset_count['counted']) == 0) {
								$dbcon = null;
								$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
								header('Content-Type: application/json');
								echo json_encode($response);
								exit;
							} elseif (intval($rowset_count['counted']) == 1) {
								$stmt_select_data = $dbcon->prepare("SELECT * FROM appx_camat_users WHERE KD_PROPINSI = :KDPROP AND KD_DATI2 = :KDKAB AND KD_KECAMATAN = :KDKEC AND WORDPASS LIKE :PASSXWORDX AND status = 1");
								$stmt_select_data->bindValue(":KDPROP", $kode_propinsi, PDO::PARAM_STR);
								$stmt_select_data->bindValue(":KDKAB", $kode_dati2, PDO::PARAM_STR);
								$stmt_select_data->bindValue(":KDKEC", $kode_kecamatan, PDO::PARAM_STR);
								$stmt_select_data->bindValue(":PASSXWORDX", $password, PDO::PARAM_STR);
								if ($stmt_select_data->execute()) {
									while($rowset_select_data = $stmt_select_data->fetch(PDO::FETCH_ASSOC)){
										$items = $rowset_select_data;
										$NM_KECAMATAN = $rowset_B['NM_KECAMATAN'];
										$password = $rowset_B['WORDPASS'];
									}
									if (!empty($items)) {
										$_SESSION['sessionID'] = $txsessionid;
										$_SESSION['appxINFO'] = $appxinfo;
										$_SESSION['tahunPAJAK'] = $appxinfo['_tahun_pajak_'];
										$_SESSION['camatXweb'] = $items;
										$dbcon = null;
										$response = array("ajaxresult"=>"found","module"=>"camat","taxyear"=>Date('Y'),"kdprv"=>$kode_propinsi,"nmprv"=>$appxinfo['_NM_PROV_'],"kdkab"=>$kode_dati2,"nmkab"=>$appxinfo['_NM_DATI2_'],"kdkec"=>$kode_kecamatan,"nmkec"=>$NM_KECAMATAN,"kdcmpkab"=>strval("".$kode_propinsi."".$kode_dati2.""),"kdcmpkec"=>strval("".$kode_propinsi."".$kode_dati2."".$kode_kecamatan.""),"dt"=>Date('Y-m-d'),"userpassword"=>$password);
										header('Content-Type: application/json');
										echo json_encode($response);
										exit;
									} else {
										$dbcon = null;
										$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
										header('Content-Type: application/json');
										echo json_encode($response);
										exit;
									}
								} else {
									$dbcon = null;
									$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
									header('Content-Type: application/json');
									echo json_encode($response);
									exit;
								}
							} else {
								$dbcon = null;
								$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
								header('Content-Type: application/json');
								echo json_encode($response);
								exit;
							}
						}
					} else {
						$dbcon = null;
						$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
						header('Content-Type: application/json');
						echo json_encode($response);
						exit;
					}
				} else {
					$dbcon = null;
					$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
					header('Content-Type: application/json');
					echo json_encode($response);
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Error: ".$e->getMessage()."");
				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		} else {
			$dbcon = null;
			$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		}
	} elseif ($_GET['cmdx'] == "LOGIN_CAMAT_ATTEMPT") {
		$namexuser = $_GET['txusername'];
		$kode_propinsi = substr($namexuser,0,2);
		$kode_dati2 = substr($namexuser,2,2);
		$kode_kecamatan = substr($namexuser,4,3);
    $password = hash_hmac('sha1', $_GET['wordxpass'], '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt_count = $dbcon->prepare("SELECT COUNT(*) AS counted, KD_PROPINSI,KD_DATI2,KD_KECAMATAN FROM appx_camat_users WHERE KD_PROPINSI = :KDPROP AND KD_DATI2 = :KDKAB AND KD_KECAMATAN = :KDKEC AND WORDPASS LIKE :PASSXWORDX AND status = 1");
			$stmt_count->bindValue(":KDPROP", $kode_propinsi, PDO::PARAM_STR);
			$stmt_count->bindValue(":KDKAB", $kode_dati2, PDO::PARAM_STR);
			$stmt_count->bindValue(":KDKEC", $kode_kecamatan, PDO::PARAM_STR);
			$stmt_count->bindValue(":PASSXWORDX", $password, PDO::PARAM_STR);
			if ($stmt_count->execute()) {
				if ($stmt_count->rowCount() > 0) {
					while($rowset_count = $stmt_count->fetch(PDO::FETCH_ASSOC)){
						if (intval($rowset_count['counted']) == 0) {
							$dbcon = null;
							$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
							header('Content-Type: application/json');
							echo json_encode($response);
							exit;
						} elseif (intval($rowset_count['counted']) == 1) {
							$stmt_select_data = $dbcon->prepare("SELECT * FROM appx_camat_users WHERE KD_PROPINSI = :KDPROP AND KD_DATI2 = :KDKAB AND KD_KECAMATAN = :KDKEC AND WORDPASS LIKE :PASSXWORDX AND status = 1");
							$stmt_select_data->bindValue(":KDPROP", $kode_propinsi, PDO::PARAM_STR);
							$stmt_select_data->bindValue(":KDKAB", $kode_dati2, PDO::PARAM_STR);
							$stmt_select_data->bindValue(":KDKEC", $kode_kecamatan, PDO::PARAM_STR);
							$stmt_select_data->bindValue(":PASSXWORDX", $password, PDO::PARAM_STR);
							if ($stmt_select_data->execute()) {
								while($rowset_select_data = $stmt_select_data->fetch(PDO::FETCH_ASSOC)){
									$items = $rowset_select_data;
								}
								if (!empty($items)) {
									$_SESSION['sessionID'] = $txsessionid;
									$_SESSION['appxINFO'] = $appxinfo;
									$_SESSION['tahunPAJAK'] = $appxinfo['_tahun_pajak_'];
									$_SESSION['camatXweb'] = $items;
									$dbcon = null;
									$response = array("ajaxresult"=>"found","module"=>"camat");
									header('Content-Type: application/json');
									echo json_encode($response);
									exit;
								} else {
									$dbcon = null;
									$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
									header('Content-Type: application/json');
									echo json_encode($response);
									exit;
								}
							} else {
								$dbcon = null;
								$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
								header('Content-Type: application/json');
								echo json_encode($response);
								exit;
							}
						} else {
							$dbcon = null;
							$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
							header('Content-Type: application/json');
							echo json_encode($response);
							exit;
						}
					}
				} else {
					$dbcon = null;
					$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
					header('Content-Type: application/json');
					echo json_encode($response);
					exit;
				}
			} else {
				$dbcon = null;
				$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Error: ".$e->getMessage()."");
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		}
	} elseif ($_GET['cmdx'] == "GET_CURRENT_USER_INFO") {
		if (isset($_SESSION['mapatdaXin'])) {
			$response = array("ajaxresult"=>"is_set","user_id"=>$_SESSION['mapatdaXin']['idx'],"user_supervisorid"=>$_SESSION['mapatdaXin']['supervisor_idx'],"user_context"=>$_SESSION['mapatdaXin']['context'],"user_realname"=>$_SESSION['mapatdaXin']['realname'],"user_initial"=>$_SESSION['mapatdaXin']['initial'],"user_username"=>$_SESSION['mapatdaXin']['username'],"user_password"=>$_SESSION['mapatdaXin']['wordpass'],"user_module"=>$_SESSION['mapatdaXin']['module'],"user_add_module"=>$_SESSION['mapatdaXin']['add_module'],"user_verificator"=>$_SESSION['mapatdaXin']['verificator'],"user_collector"=>$_SESSION['mapatdaXin']['collector'],"user_status"=>$_SESSION['mapatdaXin']['status']);
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		} else {
			$response = array("ajaxresult"=>"not_set","user_id"=>"0","user_skpd"=>"undefined","user_realname"=>"undefined","user_username"=>"undefined","user_password"=>"undefined","user_initial"=>"undefined","user_module"=>"undefined","user_level"=>"undefined","user_add_module"=>"undefined","user_add_level"=>"undefined","user_ip"=>"undefined","user_printer"=>"undefined","user_status"=>"0");
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		}
	} elseif ($_GET['cmdx'] == "GET_CURRENT_NOTARIS_INFO") {
		if (isset($_SESSION['notarisXweb'])) {
			$response = array("ajaxresult"=>"is_set","user_id"=>$_SESSION['notarisXweb']['idx'],"user_docidx"=>$_SESSION['notarisXweb']['docidx'],"user_kode_dppkad"=>$_SESSION['notarisXweb']['kode_dppkad'],"user_notaris_ppat"=>$_SESSION['notarisXweb']['notaris_ppat'],"user_realname"=>$_SESSION['notarisXweb']['realname'],"user_username"=>$_SESSION['notarisXweb']['username'],"user_password"=>$_SESSION['notarisXweb']['wordpass'],"user_status"=>$_SESSION['notarisXweb']['status']);
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		} else {
			$response = array("ajaxresult"=>"not_set","user_id"=>"0","user_docidx"=>"0","user_kode_dppkad"=>"0","user_notaris_ppat"=>"undefined","user_realname"=>"undefined","user_username"=>"undefined","user_password"=>"undefined","user_status"=>"0");
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		}
	} elseif ($_GET['cmdx'] == "GET_CURRENT_DESA_INFO") {
		if (isset($_SESSION['desaXweb'])) {
			$response = array(
				"ajaxresult"=>"is_set",
				"kode_prop"=>$_SESSION['desaXweb']['KD_PROPINSI'],
				"kode_kab"=>$_SESSION['desaXweb']['KD_DATI2'],
				"kode_kec"=>$_SESSION['desaXweb']['KD_KECAMATAN'],
				"kode_kel"=>$_SESSION['desaXweb']['KD_KELURAHAN'],
				"nama_kec"=>$_SESSION['desaXweb']['NM_KECAMATAN'],
				"nama_kel"=>$_SESSION['desaXweb']['NM_KELURAHAN'],
				"user_password"=>$_SESSION['desaXweb']['WORDPASS'],
				"user_status"=>$_SESSION['desaXweb']['STATUS']);
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		} else {
			$response = array(
				"ajaxresult"=>"not_set",
				"kode_prop"=>"00",
				"kode_kab"=>"00",
				"kode_kec"=>"00",
				"kode_kel"=>"00",
				"nama_kec"=>"undefined",
				"nama_kel"=>"undefined",
				"user_password"=>"undefined",
				"user_status"=>"0");
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		}
	} elseif ($_GET['cmdx'] == "CHANGE_PASSWORD") {
		$first_passwd = sha1($_GET['newpassword']); $conf_passwd = sha1($_GET['confirmpassword']);
		if ($first_passwd == $conf_passwd) {
			try {
				/* connection setting */
				$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $dbcon->prepare("UPDATE appx_bppkad_users SET wordpass = :new_password WHERE idx = :iduser LIMIT 1");
				$stmt->bindValue(":new_password", $first_passwd, PDO::PARAM_STR);
				$stmt->bindValue(":iduser", $_SESSION['mapatdaXin']['idx'], PDO::PARAM_INT);
				if($stmt->execute()){
					$response = array("ajaxresult"=>"changed");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				} else {
					$response = array("ajaxresult"=>"unchanged");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"unchanged","ajaxmessage"=>"".$e->getMessage()."");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
		} else {
			$response = array("ajaxresult"=>"unchanged");
			header('Content-Type: application/json');
			echo json_encode($response);
			$dbcon = null;
			exit;
		}
	} elseif ($_GET['cmdx'] == "CHANGE_PASSWORD_DESA") { /* copied to /app-desa/ajax_json_engine.php */
		$first_passwd = sha1($_GET['newpassword']);
    $conf_passwd = sha1($_GET['confirmpassword']);
		if ($first_passwd == $conf_passwd) {
			try {
				/* connection setting */
				$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $dbcon->prepare("UPDATE appx_desa_users SET WORDPASS = :new_password WHERE KD_PROPINSI = :KDPROP AND KD_DATI2 = :KDKAB AND KD_KECAMATAN = :KDKEC AND KD_KELURAHAN = :KDKEL");
				$stmt->bindValue(":new_password", $first_passwd, PDO::PARAM_STR);
				$stmt->bindValue(":KDPROP", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
				$stmt->bindValue(":KDKAB", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
				$stmt->bindValue(":KDKEC", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
				$stmt->bindValue(":KDKEL", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
				if($stmt->execute()){
					$response = array("ajaxresult"=>"changed");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				} else {
					$response = array("ajaxresult"=>"unchanged");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"unchanged","ajaxmessage"=>"".$e->getMessage()."");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
		} else {
			$response = array("ajaxresult"=>"unchanged");
			header('Content-Type: application/json');
			echo json_encode($response);
			$dbcon = null;
			exit;
		}
	} elseif ($_GET['cmdx'] == "GET_KECAMATAN") {
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("SELECT * FROM view_ref_kecamatan");
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
			$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"".$e->getMessage()."");
			header('Content-Type: application/json');
			echo json_encode($response);
			$dbcon = null;
			exit;
		}
	} elseif ($_GET['cmdx'] == "GET_DESA_BY_KECAMATAN") {
		$namakecamatanx = $_GET['kecamatan'];
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("SELECT * FROM view_ref_desa WHERE kecamatan = :nmkecamatan ORDER BY desa");
			$stmt->bindValue(":nmkecamatan", $namakecamatanx, PDO::PARAM_STR);
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
			$response = array("ajaxresult"=>"notfound","ajaxmessage"=>"".$e->getMessage()."");
			header('Content-Type: application/json');
			echo json_encode($response);
			$dbcon = null;
			exit;
		}
	} elseif ($_GET['cmdx'] == "INFO_SPPT_ATTEMPT") {
		$JNS_BUMI = $KD_ZNT = $NO_BNG = $KD_JPB = $NO_FORMULIR_LSPOP = $THN_DIBANGUN_BNG = $THN_RENOVASI_BNG = $LUAS_BNG = $JML_LANTAI_BNG = $KONDISI_BNG = $JNS_KONSTRUKSI_BNG = $JNS_ATAP_BNG = $KD_DINDING = $KD_LANTAI = $KD_LANGIT_LANGIT = $NILAI_SISTEM_BNG = $JNS_TRANSAKSI_BNG = $TGL_PENDATAAN_BNG = $TGL_PEMERIKSAAN_BNG = $TGL_PEREKAMAN_BNG  = $DAYA_LISTRIK = null;
		$RAWNOP = $_GET['spptnop'];
		$NOP_SEGMENT_1 = substr($RAWNOP,0,2); 
		$NOP_SEGMENT_2 = substr($RAWNOP,2,2); 
		$NOP_SEGMENT_3 = substr($RAWNOP,4,3); 
		$NOP_SEGMENT_4 = substr($RAWNOP,7,3); 
		$NOP_SEGMENT_5 = substr($RAWNOP,10,3); 
		$NOP_SEGMENT_6 = substr($RAWNOP,13,4); 
		$NOP_SEGMENT_7 = substr($RAWNOP,17,1);
		$historyitems = null; $thnsppt = Date('Y');
		$oracledbconnection = oci_connect("".$appxinfo['_oracle_sismiop_user_']."", "".$appxinfo['_oracle_sismiop_pass_']."", "//".$appxinfo['_oracle_sismiop_host_'].":".$appxinfo['_oracle_sismiop_port_']."/".$appxinfo['_oracle_sismiop_service_']."");
		if (!$oracledbconnection) {
			$errMessage = oci_error();
			trigger_error(htmlentities($errMessage['message']), E_USER_ERROR);
		} else {
			$query_select_current_sppt = 'SELECT 
						SPPT.KD_PROPINSI,
						SPPT.KD_DATI2,
						SPPT.KD_KECAMATAN,
						SPPT.KD_KELURAHAN,
						SPPT.KD_BLOK,
						SPPT.NO_URUT,
						SPPT.KD_JNS_OP,
						SPPT.THN_PAJAK_SPPT,
						SPPT.SIKLUS_SPPT,
						SPPT.KD_KANWIL_BANK,
						SPPT.KD_KPPBB_BANK,
						SPPT.KD_BANK_TUNGGAL,
						SPPT.KD_BANK_PERSEPSI,
						SPPT.KD_TP,
						SPPT.NM_WP_SPPT,
						SPPT.JLN_WP_SPPT,
						SPPT.BLOK_KAV_NO_WP_SPPT,
						SPPT.RW_WP_SPPT,
						SPPT.RT_WP_SPPT,
						SPPT.KELURAHAN_WP_SPPT,
						SPPT.KOTA_WP_SPPT,
						SPPT.KD_POS_WP_SPPT,
						SPPT.NPWP_SPPT,
						SPPT.NO_PERSIL_SPPT,
						SPPT.KD_KLS_TANAH,
						SPPT.THN_AWAL_KLS_TANAH,
						SPPT.KD_KLS_BNG,
						SPPT.THN_AWAL_KLS_BNG,
						TO_CHAR(SPPT.TGL_JATUH_TEMPO_SPPT,\'yyyy-mm-dd\') TGL_JATUH_TEMPO_SPPT,
						SPPT.LUAS_BUMI_SPPT,
						SPPT.LUAS_BNG_SPPT,
						SPPT.NJOP_BUMI_SPPT,
						SPPT.NJOP_BNG_SPPT,
						SPPT.NJOP_SPPT,
						SPPT.NJOPTKP_SPPT,
						SPPT.NJKP_SPPT,
						SPPT.PBB_TERHUTANG_SPPT,
						SPPT.FAKTOR_PENGURANG_SPPT,
						SPPT.PBB_YG_HARUS_DIBAYAR_SPPT,
						SPPT.STATUS_PEMBAYARAN_SPPT,
						SPPT.STATUS_TAGIHAN_SPPT,
						SPPT.STATUS_CETAK_SPPT,
						TO_CHAR(SPPT.TGL_TERBIT_SPPT,\'yyyy-mm-dd\') TGL_TERBIT_SPPT,
						TO_CHAR(SPPT.TGL_CETAK_SPPT,\'yyyy-mm-dd\') TGL_CETAK_SPPT,
						SPPT.NIP_PENCETAK_SPPT,
						DAT_OP_BUMI.NO_BUMI,
						DAT_OP_BUMI.KD_ZNT,
						DAT_OP_BUMI.LUAS_BUMI,
						DECODE(DAT_OP_BUMI.JNS_BUMI, 1, \'TANAH DAN BANGUNAN\', 2, \'KAVLING SIAP BANGUN\', 3, \'TANAH KOSONG\', 4, \'FASILITAS UMUM\') JNS_BUMI,
						DAT_OP_BUMI.NILAI_SISTEM_BUMI,
						REF_KECAMATAN.NM_KECAMATAN NM_KECAMATAN,
						REF_KELURAHAN.NM_KELURAHAN NM_KELURAHAN 
				FROM 
					SPPT, 
					REF_KECAMATAN, 
					REF_KELURAHAN, 
					DAT_OP_BUMI 
				WHERE 
					SPPT.KD_PROPINSI = :KODEPROP 
					AND SPPT.KD_DATI2 = :KODEDATI2 
					AND SPPT.KD_KECAMATAN = :KODEKEC 
					AND SPPT.KD_KELURAHAN = :KODEDESKEL 
					AND SPPT.KD_BLOK = :KODEBLOK 
					AND SPPT.NO_URUT = :NOURUT 
					AND SPPT.KD_JNS_OP = :KODEJENISOP 
					AND SPPT.THN_PAJAK_SPPT = :THNSPPT 
					AND REF_KECAMATAN.KD_PROPINSI = SPPT.KD_PROPINSI 
					AND REF_KECAMATAN.KD_DATI2 = SPPT.KD_DATI2 
					AND REF_KECAMATAN.KD_KECAMATAN = SPPT.KD_KECAMATAN 
					AND REF_KELURAHAN.KD_PROPINSI = SPPT.KD_PROPINSI 
					AND REF_KELURAHAN.KD_DATI2 = SPPT.KD_DATI2 
					AND REF_KELURAHAN.KD_KECAMATAN = SPPT.KD_KECAMATAN 
					AND REF_KELURAHAN.KD_KELURAHAN = SPPT.KD_KELURAHAN 
					AND DAT_OP_BUMI.KD_PROPINSI = SPPT.KD_PROPINSI 
					AND DAT_OP_BUMI.KD_DATI2 = SPPT.KD_DATI2 
					AND DAT_OP_BUMI.KD_KECAMATAN = SPPT.KD_KECAMATAN 
					AND DAT_OP_BUMI.KD_KELURAHAN = SPPT.KD_KELURAHAN 
					AND DAT_OP_BUMI.KD_BLOK = SPPT.KD_BLOK 
					AND DAT_OP_BUMI.NO_URUT = SPPT.NO_URUT 
					AND DAT_OP_BUMI.KD_JNS_OP = SPPT.KD_JNS_OP';
			$stmt_select_current_sppt = oci_parse($oracledbconnection, $query_select_current_sppt);
			oci_bind_by_name($stmt_select_current_sppt, ":KODEPROP", $NOP_SEGMENT_1);
			oci_bind_by_name($stmt_select_current_sppt, ":KODEDATI2", $NOP_SEGMENT_2);
			oci_bind_by_name($stmt_select_current_sppt, ":KODEKEC", $NOP_SEGMENT_3);
			oci_bind_by_name($stmt_select_current_sppt, ":KODEDESKEL", $NOP_SEGMENT_4);
			oci_bind_by_name($stmt_select_current_sppt, ":KODEBLOK", $NOP_SEGMENT_5);
			oci_bind_by_name($stmt_select_current_sppt, ":NOURUT", $NOP_SEGMENT_6);
			oci_bind_by_name($stmt_select_current_sppt, ":KODEJENISOP", $NOP_SEGMENT_7);
			oci_bind_by_name($stmt_select_current_sppt, ":THNSPPT", $thnsppt);
			if (oci_execute($stmt_select_current_sppt)) {
				while ($rowset_select_current_sppt = oci_fetch_array($stmt_select_current_sppt, OCI_RETURN_NULLS+OCI_ASSOC)) {
					$KD_PROPINSI = $rowset_select_current_sppt['KD_PROPINSI'];
					$KD_DATI2 = $rowset_select_current_sppt['KD_DATI2'];
					$KD_KECAMATAN = $rowset_select_current_sppt['KD_KECAMATAN'];
					$KD_KELURAHAN = $rowset_select_current_sppt['KD_KELURAHAN'];
					$KD_BLOK = $rowset_select_current_sppt['KD_BLOK'];
					$NO_URUT = $rowset_select_current_sppt['NO_URUT'];
					$KD_JNS_OP = $rowset_select_current_sppt['KD_JNS_OP'];
					$THN_PAJAK_SPPT = $rowset_select_current_sppt['THN_PAJAK_SPPT'];
					$SIKLUS_SPPT = $rowset_select_current_sppt['SIKLUS_SPPT'];
					$KD_KANWIL_BANK = $rowset_select_current_sppt['KD_KANWIL_BANK'];
					$KD_KPPBB_BANK = $rowset_select_current_sppt['KD_KPPBB_BANK'];
					$KD_BANK_TUNGGAL = $rowset_select_current_sppt['KD_BANK_TUNGGAL'];
					$KD_BANK_PERSEPSI = $rowset_select_current_sppt['KD_BANK_PERSEPSI'];
					$KD_TP = $rowset_select_current_sppt['KD_TP'];
					$NM_WP_SPPT = $rowset_select_current_sppt['NM_WP_SPPT'];
					$JLN_WP_SPPT = $rowset_select_current_sppt['JLN_WP_SPPT'];
					$BLOK_KAV_NO_WP_SPPT = $rowset_select_current_sppt['BLOK_KAV_NO_WP_SPPT'];
					$RW_WP_SPPT = $rowset_select_current_sppt['RW_WP_SPPT'];
					$RT_WP_SPPT = $rowset_select_current_sppt['RT_WP_SPPT'];
					$KELURAHAN_WP_SPPT = $rowset_select_current_sppt['KELURAHAN_WP_SPPT'];
					$KOTA_WP_SPPT = $rowset_select_current_sppt['KOTA_WP_SPPT'];
					$KD_POS_WP_SPPT = $rowset_select_current_sppt['KD_POS_WP_SPPT'];
					$NPWP_SPPT = $rowset_select_current_sppt['NPWP_SPPT'];
					$NO_PERSIL_SPPT = $rowset_select_current_sppt['NO_PERSIL_SPPT'];
					$KD_KLS_TANAH = $rowset_select_current_sppt['KD_KLS_TANAH'];
					$THN_AWAL_KLS_TANAH = $rowset_select_current_sppt['THN_AWAL_KLS_TANAH'];
					$KD_KLS_BNG = $rowset_select_current_sppt['KD_KLS_BNG'];
					$THN_AWAL_KLS_BNG = $rowset_select_current_sppt['THN_AWAL_KLS_BNG'];
					$TGL_JATUH_TEMPO_SPPT = $rowset_select_current_sppt['TGL_JATUH_TEMPO_SPPT'];
					$LUAS_BUMI_SPPT = $rowset_select_current_sppt['LUAS_BUMI_SPPT'];
					$LUAS_BNG_SPPT = $rowset_select_current_sppt['LUAS_BNG_SPPT'];
					$NJOP_BUMI_SPPT = $rowset_select_current_sppt['NJOP_BUMI_SPPT'];
					$NJOP_BNG_SPPT = $rowset_select_current_sppt['NJOP_BNG_SPPT'];
					$NJOP_SPPT = $rowset_select_current_sppt['NJOP_SPPT'];
					$NJOPTKP_SPPT = $rowset_select_current_sppt['NJOPTKP_SPPT'];
					$NJKP_SPPT = $rowset_select_current_sppt['NJKP_SPPT'];
					$PBB_TERHUTANG_SPPT = $rowset_select_current_sppt['PBB_TERHUTANG_SPPT'];
					$FAKTOR_PENGURANG_SPPT = $rowset_select_current_sppt['FAKTOR_PENGURANG_SPPT'];
					$PBB_YG_HARUS_DIBAYAR_SPPT = $rowset_select_current_sppt['PBB_YG_HARUS_DIBAYAR_SPPT'];
					$STATUS_PEMBAYARAN_SPPT = $rowset_select_current_sppt['STATUS_PEMBAYARAN_SPPT'];
					$STATUS_TAGIHAN_SPPT = $rowset_select_current_sppt['STATUS_TAGIHAN_SPPT'];
					$STATUS_CETAK_SPPT = $rowset_select_current_sppt['STATUS_CETAK_SPPT'];
					$TGL_TERBIT_SPPT = $rowset_select_current_sppt['TGL_TERBIT_SPPT'];
					$TGL_CETAK_SPPT = $rowset_select_current_sppt['TGL_CETAK_SPPT'];
					$NIP_PENCETAK_SPPT = $rowset_select_current_sppt['NIP_PENCETAK_SPPT'];
					$NM_KECAMATAN = $rowset_select_current_sppt['NM_KECAMATAN'];
					$NM_KELURAHAN = $rowset_select_current_sppt['NM_KELURAHAN'];
					$JNS_BUMI = $rowset_select_current_sppt['JNS_BUMI'];
					$KD_ZNT = $rowset_select_current_sppt['KD_ZNT'];
				}
				oci_free_statement($stmt_select_current_sppt);
				if (strlen(strval("".$KD_PROPINSI."".$KD_DATI2."".$KD_KECAMATAN."".$KD_KELURAHAN."".$KD_BLOK."".$NO_URUT."".$KD_JNS_OP.""))==18) {
					
					$query_history_sppt = 'SELECT KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,SIKLUS_SPPT,KD_KANWIL_BANK,KD_KPPBB_BANK,KD_BANK_TUNGGAL,KD_BANK_PERSEPSI,KD_TP,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,KELURAHAN_WP_SPPT,KOTA_WP_SPPT,KD_POS_WP_SPPT,NPWP_SPPT,NO_PERSIL_SPPT,KD_KLS_TANAH,THN_AWAL_KLS_TANAH,KD_KLS_BNG,THN_AWAL_KLS_BNG,TO_CHAR(TGL_JATUH_TEMPO_SPPT,\'yyyy-mm-dd\') TGL_JATUH_TEMPO_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,NJOP_BUMI_SPPT,NJOP_BNG_SPPT,NJOP_SPPT,NJOPTKP_SPPT,NJKP_SPPT,PBB_TERHUTANG_SPPT,FAKTOR_PENGURANG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,STATUS_TAGIHAN_SPPT,STATUS_CETAK_SPPT,TO_CHAR(TGL_TERBIT_SPPT,\'yyyy-mm-dd\') TGL_TERBIT_SPPT,TO_CHAR(TGL_CETAK_SPPT,\'yyyy-mm-dd\') TGL_CETAK_SPPT,NIP_PENCETAK_SPPT FROM SPPT WHERE KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL AND KD_BLOK = :KODEBLOK AND NO_URUT = :NOURUT AND KD_JNS_OP = :KODEJENISOP AND THN_PAJAK_SPPT BETWEEN \'2005\' AND :MAXTHNSPPT ORDER BY THN_PAJAK_SPPT';
					$stmt_history_sppt = oci_parse($oracledbconnection, $query_history_sppt);
					oci_bind_by_name($stmt_history_sppt, ":KODEPROP", $NOP_SEGMENT_1);
					oci_bind_by_name($stmt_history_sppt, ":KODEDATI2", $NOP_SEGMENT_2);
					oci_bind_by_name($stmt_history_sppt, ":KODEKEC", $NOP_SEGMENT_3);
					oci_bind_by_name($stmt_history_sppt, ":KODEDESKEL", $NOP_SEGMENT_4);
					oci_bind_by_name($stmt_history_sppt, ":KODEBLOK", $NOP_SEGMENT_5);
					oci_bind_by_name($stmt_history_sppt, ":NOURUT", $NOP_SEGMENT_6);
					oci_bind_by_name($stmt_history_sppt, ":KODEJENISOP", $NOP_SEGMENT_7);
					oci_bind_by_name($stmt_history_sppt, ":MAXTHNSPPT", $thnsppt);
					if (oci_execute($stmt_history_sppt)) {
						while ($rowset_history_sppt = oci_fetch_array($stmt_history_sppt, OCI_RETURN_NULLS+OCI_ASSOC)) {
							$historyitems[] = $rowset_history_sppt;
						}
						oci_free_statement($stmt_history_sppt);
						/* ========== BANGUNAN ========= */
						if ($LUAS_BNG_SPPT!="0") {
							$query_bangunan_sppt = 'SELECT 
								NO_BNG,
								DECODE(KD_JPB, 01, \'PERUMAHAN\', 02, \'PERKANTORAN SWASTA\', 03, \'PABRIK\', 04, \'TOKO/APOTIK/PASAR/RUKO\', 05, \'RUMAH SAKIT/KLINIK\', 06, \'OLAH RAGA/REKREASI\', 07, \'HOTEL/WISMA\', 08, \'BENGKEL/GUDANG/PERTANIAN\', 09, \'GEDUNG PEMERINTAH\', 10, \'LAIN-LAIN\', 11, \'BANGUNAN TIDAK KENA PAJAK\', 12, \'BANGUNAN PARKIR\', 13, \'APARTEMEN\', 14, \'POMPA BENSIN\', 15, \'TANGKI MINYAK\', 16, \'GEDUNG SEKOLAH\') KD_JPB,
								NO_FORMULIR_LSPOP,
								THN_DIBANGUN_BNG,
								THN_RENOVASI_BNG,
								LUAS_BNG,
								JML_LANTAI_BNG,
								KONDISI_BNG,
								DECODE(JNS_KONSTRUKSI_BNG, 1, \'BAJA\', 2, \'BETON\', 3, \'BATU BATA\', 4, \'KAYU\') JNS_KONSTRUKSI_BNG,
								DECODE(JNS_ATAP_BNG, 1, \'GENTING BETON\', 2, \'GENTING KARBON\', 3, \'GENTING BIASA\', 4, \'SENG/ASBES\') JNS_ATAP_BNG,
								DECODE(KD_DINDING, 1, \'KACA\', 2, \'BETON\', 3, \'BATU BATA/CONBLOCK\', 4, \'SENG/ASBES\', 5, \'TIDAK ADA\') KD_DINDING,
								DECODE(KD_LANTAI, 1, \'MARMER\', 2, \'KERAMIK\', 3, \'TERASO\', 4, \'UBIN PC/PAPAN\', 5, \'SEMEN\') KD_LANTAI,
								DECODE(KD_LANGIT_LANGIT, 1, \'AKUSTIK/JATI\', 2, \'TRIPLEK/ASBES/BAMBU\', 3, \'TIDAK ADA\') KD_LANGIT_LANGIT,
								NILAI_SISTEM_BNG,
								JNS_TRANSAKSI_BNG,
								TO_CHAR(TGL_PENDATAAN_BNG,\'dd/mm/yyyy\') TGL_PENDATAAN_BNG,
								TGL_PEMERIKSAAN_BNG,
								TO_CHAR(TGL_PEREKAMAN_BNG,\'dd/mm/yyyy\') TGL_PEREKAMAN_BNG 
							FROM 
								DAT_OP_BANGUNAN 
							WHERE 
								KD_PROPINSI = :KODEPROP 
								AND KD_DATI2 = :KODEDATI2 
								AND KD_KECAMATAN = :KODEKEC 
								AND KD_KELURAHAN = :KODEDESKEL 
								AND KD_BLOK = :KODEBLOK 
								AND NO_URUT = :NOURUT 
								AND KD_JNS_OP = :KODEJENISOP';
							$stmt_bangunan_sppt = oci_parse($oracledbconnection, $query_bangunan_sppt);
							oci_bind_by_name($stmt_bangunan_sppt, ":KODEPROP", $NOP_SEGMENT_1);
							oci_bind_by_name($stmt_bangunan_sppt, ":KODEDATI2", $NOP_SEGMENT_2);
							oci_bind_by_name($stmt_bangunan_sppt, ":KODEKEC", $NOP_SEGMENT_3);
							oci_bind_by_name($stmt_bangunan_sppt, ":KODEDESKEL", $NOP_SEGMENT_4);
							oci_bind_by_name($stmt_bangunan_sppt, ":KODEBLOK", $NOP_SEGMENT_5);
							oci_bind_by_name($stmt_bangunan_sppt, ":NOURUT", $NOP_SEGMENT_6);
							oci_bind_by_name($stmt_bangunan_sppt, ":KODEJENISOP", $NOP_SEGMENT_7);
							if (oci_execute($stmt_bangunan_sppt)) {
								while ($rowset_bangunan_sppt = oci_fetch_array($stmt_bangunan_sppt, OCI_RETURN_NULLS+OCI_ASSOC)) {
									$NO_BNG = $rowset_bangunan_sppt['NO_BNG'];
									$KD_JPB = $rowset_bangunan_sppt['KD_JPB'];
									$NO_FORMULIR_LSPOP = $rowset_bangunan_sppt['NO_FORMULIR_LSPOP'];
									$THN_DIBANGUN_BNG = $rowset_bangunan_sppt['THN_DIBANGUN_BNG'];
									$THN_RENOVASI_BNG = $rowset_bangunan_sppt['THN_RENOVASI_BNG'];
									$LUAS_BNG = $rowset_bangunan_sppt['LUAS_BNG'];
									$JML_LANTAI_BNG = $rowset_bangunan_sppt['JML_LANTAI_BNG'];
									$KONDISI_BNG = $rowset_bangunan_sppt['KONDISI_BNG'];
									$JNS_KONSTRUKSI_BNG = $rowset_bangunan_sppt['JNS_KONSTRUKSI_BNG'];
									$JNS_ATAP_BNG = $rowset_bangunan_sppt['JNS_ATAP_BNG'];
									$KD_DINDING = $rowset_bangunan_sppt['KD_DINDING'];
									$KD_LANTAI = $rowset_bangunan_sppt['KD_LANTAI'];
									$KD_LANGIT_LANGIT = $rowset_bangunan_sppt['KD_LANGIT_LANGIT'];
									$NILAI_SISTEM_BNG = $rowset_bangunan_sppt['NILAI_SISTEM_BNG'];
									$JNS_TRANSAKSI_BNG = $rowset_bangunan_sppt['JNS_TRANSAKSI_BNG'];
									$TGL_PENDATAAN_BNG = $rowset_bangunan_sppt['TGL_PENDATAAN_BNG'];
									$TGL_PEMERIKSAAN_BNG = $rowset_bangunan_sppt['TGL_PEMERIKSAAN_BNG'];
									$TGL_PEREKAMAN_BNG = $rowset_bangunan_sppt['TGL_PEREKAMAN_BNG'];
								}
								oci_free_statement($stmt_bangunan_sppt);
							/* ========= LISTRIK ========== */
							$query_listrik_sppt = 'SELECT 
								JML_SATUAN 
							FROM 
								DAT_FASILITAS_BANGUNAN 
							WHERE 
								KD_PROPINSI = :KODEPROP 
								AND KD_DATI2 = :KODEDATI2 
								AND KD_KECAMATAN = :KODEKEC 
								AND KD_KELURAHAN = :KODEDESKEL 
								AND KD_BLOK = :KODEBLOK 
								AND NO_URUT = :NOURUT 
								AND KD_JNS_OP = :KODEJENISOP';
							$stmt_listrik_sppt = oci_parse($oracledbconnection, $query_listrik_sppt);
							oci_bind_by_name($stmt_listrik_sppt, ":KODEPROP", $NOP_SEGMENT_1);
							oci_bind_by_name($stmt_listrik_sppt, ":KODEDATI2", $NOP_SEGMENT_2);
							oci_bind_by_name($stmt_listrik_sppt, ":KODEKEC", $NOP_SEGMENT_3);
							oci_bind_by_name($stmt_listrik_sppt, ":KODEDESKEL", $NOP_SEGMENT_4);
							oci_bind_by_name($stmt_listrik_sppt, ":KODEBLOK", $NOP_SEGMENT_5);
							oci_bind_by_name($stmt_listrik_sppt, ":NOURUT", $NOP_SEGMENT_6);
							oci_bind_by_name($stmt_listrik_sppt, ":KODEJENISOP", $NOP_SEGMENT_7);
							if (oci_execute($stmt_listrik_sppt)) {
								while ($rowset_listrik_sppt = oci_fetch_array($stmt_listrik_sppt, OCI_RETURN_NULLS+OCI_ASSOC)) {
									$DAYA_LISTRIK = $rowset_listrik_sppt['JML_SATUAN'];
								}
								oci_free_statement($stmt_listrik_sppt);
							} else {
								oci_free_statement($stmt_listrik_sppt);
								$DAYA_LISTRIK = "-";
							}
							/* ========= /LISTRIK ========= */
							} else {
								oci_free_statement($stmt_bangunan_sppt);
								$NO_BNG = $KD_JPB = $NO_FORMULIR_LSPOP = $THN_DIBANGUN_BNG = $THN_RENOVASI_BNG = $LUAS_BNG = $JML_LANTAI_BNG = $KONDISI_BNG = $JNS_KONSTRUKSI_BNG = $JNS_ATAP_BNG = $KD_DINDING = $KD_LANTAI = $KD_LANGIT_LANGIT = $NILAI_SISTEM_BNG = $JNS_TRANSAKSI_BNG = $TGL_PENDATAAN_BNG = $TGL_PEMERIKSAAN_BNG = $TGL_PEREKAMAN_BNG = "-";
							}
						} else {
							$NO_BNG = $KD_JPB = $NO_FORMULIR_LSPOP = $THN_DIBANGUN_BNG = $THN_RENOVASI_BNG = $LUAS_BNG = $JML_LANTAI_BNG = $KONDISI_BNG = $JNS_KONSTRUKSI_BNG = $JNS_ATAP_BNG = $KD_DINDING = $KD_LANTAI = $KD_LANGIT_LANGIT = $NILAI_SISTEM_BNG = $JNS_TRANSAKSI_BNG = $TGL_PENDATAAN_BNG = $TGL_PEMERIKSAAN_BNG = $TGL_PEREKAMAN_BNG = "-";
						}
						/* ========== PAYMENT ========== */
						$query_payment_sppt = 'SELECT S.KD_PROPINSI,S.KD_DATI2,S.KD_KECAMATAN,S.KD_KELURAHAN,S.KD_BLOK,S.NO_URUT,S.KD_JNS_OP,S.THN_PAJAK_SPPT,S.NM_WP_SPPT,S.PBB_YG_HARUS_DIBAYAR_SPPT,P.PEMBAYARAN_SPPT_KE,P.KD_KANWIL_BANK,P.KD_KPPBB_BANK,P.KD_BANK_TUNGGAL,P.KD_BANK_PERSEPSI,P.KD_TP,P.DENDA_SPPT,NVL(P.JML_SPPT_YG_DIBAYAR,\'0\') JML_SPPT_YG_DIBAYAR,NVL(TO_CHAR(P.TGL_PEMBAYARAN_SPPT,\'dd/mm/yyyy\'),\'-\') TGL_PEMBAYARAN_SPPT,TO_CHAR(P.TGL_REKAM_BYR_SPPT,\'dd/mm/yyyy\') TGL_REKAM_BYR_SPPT,P.NIP_REKAM_BYR_SPPT FROM SPPT S LEFT JOIN PEMBAYARAN_SPPT P ON P.KD_PROPINSI = S.KD_PROPINSI AND P.KD_DATI2 = S.KD_DATI2 AND P.KD_KECAMATAN = S.KD_KECAMATAN AND P.KD_KELURAHAN = S.KD_KELURAHAN AND P.KD_BLOK = S.KD_BLOK AND P.NO_URUT = S.NO_URUT AND P.KD_JNS_OP = S.KD_JNS_OP AND P.THN_PAJAK_SPPT = S.THN_PAJAK_SPPT WHERE S.KD_PROPINSI = :KODEPROP AND S.KD_DATI2 = :KODEDATI2 AND S.KD_KECAMATAN = :KODEKEC AND S.KD_KELURAHAN = :KODEDESKEL AND S.KD_BLOK = :KODEBLOK AND S.NO_URUT = :NOURUT AND S.KD_JNS_OP = :KODEJENISOP AND S.THN_PAJAK_SPPT BETWEEN \'2005\' AND :MAXTHNSPPT ORDER BY S.THN_PAJAK_SPPT';
						$stmt_payment_sppt = oci_parse($oracledbconnection, $query_payment_sppt);
						oci_bind_by_name($stmt_payment_sppt, ":KODEPROP", $NOP_SEGMENT_1);
						oci_bind_by_name($stmt_payment_sppt, ":KODEDATI2", $NOP_SEGMENT_2);
						oci_bind_by_name($stmt_payment_sppt, ":KODEKEC", $NOP_SEGMENT_3);
						oci_bind_by_name($stmt_payment_sppt, ":KODEDESKEL", $NOP_SEGMENT_4);
						oci_bind_by_name($stmt_payment_sppt, ":KODEBLOK", $NOP_SEGMENT_5);
						oci_bind_by_name($stmt_payment_sppt, ":NOURUT", $NOP_SEGMENT_6);
						oci_bind_by_name($stmt_payment_sppt, ":KODEJENISOP", $NOP_SEGMENT_7);
						oci_bind_by_name($stmt_payment_sppt, ":MAXTHNSPPT", $thnsppt);
						if (oci_execute($stmt_payment_sppt)) {
							while ($rowset_payment_sppt = oci_fetch_array($stmt_payment_sppt, OCI_RETURN_NULLS+OCI_ASSOC)) {
								$items[] = $rowset_payment_sppt;
							}
							oci_free_statement($stmt_payment_sppt);
							$response = array("ajaxresult"=>"found","KD_PROPINSI"=>$KD_PROPINSI,"KD_DATI2"=>$KD_DATI2,"KD_KECAMATAN"=>$KD_KECAMATAN,"KD_KELURAHAN"=>$KD_KELURAHAN,"KD_BLOK"=>$KD_BLOK,"NO_URUT"=>$NO_URUT,"KD_JNS_OP"=>$KD_JNS_OP,"THN_PAJAK_SPPT"=>$THN_PAJAK_SPPT,"SIKLUS_SPPT"=>$SIKLUS_SPPT,"KD_KANWIL_BANK"=>$KD_KANWIL_BANK,"KD_KPPBB_BANK"=>$KD_KPPBB_BANK,"KD_BANK_TUNGGAL"=>$KD_BANK_TUNGGAL,"KD_BANK_PERSEPSI"=>$KD_BANK_PERSEPSI,"KD_TP"=>$KD_TP,"NM_WP_SPPT"=>$NM_WP_SPPT,"JLN_WP_SPPT"=>$JLN_WP_SPPT,"BLOK_KAV_NO_WP_SPPT"=>$BLOK_KAV_NO_WP_SPPT,"RW_WP_SPPT"=>$RW_WP_SPPT,"RT_WP_SPPT"=>$RT_WP_SPPT,"KELURAHAN_WP_SPPT"=>$KELURAHAN_WP_SPPT,"KOTA_WP_SPPT"=>$KOTA_WP_SPPT,"KD_POS_WP_SPPT"=>$KD_POS_WP_SPPT,"NPWP_SPPT"=>$NPWP_SPPT,"NO_PERSIL_SPPT"=>$NO_PERSIL_SPPT,"KD_KLS_TANAH"=>$KD_KLS_TANAH,"THN_AWAL_KLS_TANAH"=>$THN_AWAL_KLS_TANAH,"KD_KLS_BNG"=>$KD_KLS_BNG,"THN_AWAL_KLS_BNG"=>$THN_AWAL_KLS_BNG,"TGL_JATUH_TEMPO_SPPT"=>$TGL_JATUH_TEMPO_SPPT,"LUAS_BUMI_SPPT"=>$LUAS_BUMI_SPPT,"LUAS_BNG_SPPT"=>$LUAS_BNG_SPPT,"NJOP_BUMI_SPPT"=>$NJOP_BUMI_SPPT,"NJOP_BNG_SPPT"=>$NJOP_BNG_SPPT,"NJOP_SPPT"=>$NJOP_SPPT,"NJOPTKP_SPPT"=>$NJOPTKP_SPPT,"NJKP_SPPT"=>$NJKP_SPPT,"PBB_TERHUTANG_SPPT"=>$PBB_TERHUTANG_SPPT,"FAKTOR_PENGURANG_SPPT"=>$FAKTOR_PENGURANG_SPPT,"PBB_YG_HARUS_DIBAYAR_SPPT"=>$PBB_YG_HARUS_DIBAYAR_SPPT,"STATUS_PEMBAYARAN_SPPT"=>$STATUS_PEMBAYARAN_SPPT,"STATUS_TAGIHAN_SPPT"=>$STATUS_TAGIHAN_SPPT,"STATUS_CETAK_SPPT"=>$STATUS_CETAK_SPPT,"TGL_TERBIT_SPPT"=>$TGL_TERBIT_SPPT,"TGL_CETAK_SPPT"=>$TGL_CETAK_SPPT,"NIP_PENCETAK_SPPT"=>$NIP_PENCETAK_SPPT,"NM_KECAMATAN"=>$NM_KECAMATAN,"NM_KELURAHAN"=>$NM_KELURAHAN,"JNS_BUMI"=>$JNS_BUMI,"KD_ZNT"=>$KD_ZNT,"NO_BNG"=>$NO_BNG,"KD_JPB"=>$KD_JPB,"NO_FORMULIR_LSPOP"=>$NO_FORMULIR_LSPOP,"THN_DIBANGUN_BNG"=>$THN_DIBANGUN_BNG,"THN_RENOVASI_BNG"=>$THN_RENOVASI_BNG,"LUAS_BNG"=>$LUAS_BNG,"JML_LANTAI_BNG"=>$JML_LANTAI_BNG,"KONDISI_BNG"=>$KONDISI_BNG,"JNS_KONSTRUKSI_BNG"=>$JNS_KONSTRUKSI_BNG,"JNS_ATAP_BNG"=>$JNS_ATAP_BNG,"KD_DINDING"=>$KD_DINDING,"KD_LANTAI"=>$KD_LANTAI,"KD_LANGIT_LANGIT"=>$KD_LANGIT_LANGIT,"NILAI_SISTEM_BNG"=>$NILAI_SISTEM_BNG,"JNS_TRANSAKSI_BNG"=>$JNS_TRANSAKSI_BNG,"TGL_PENDATAAN_BNG"=>$TGL_PENDATAAN_BNG,"TGL_PEMERIKSAAN_BNG"=>$TGL_PEMERIKSAAN_BNG,"TGL_PEREKAMAN_BNG"=>$TGL_PEREKAMAN_BNG,"DAYA_LISTRIK"=>$DAYA_LISTRIK,"historyarray"=>$historyitems,"dataarray"=>$items);
								header('Content-Type: application/json');
								echo json_encode($response);
								oci_close($oracledbconnection);
								exit;
						} else {
							oci_free_statement($stmt_payment_sppt);
							$response = array("ajaxresult"=>"notfound");
							header('Content-Type: application/json');
							echo json_encode($response);
							oci_close($oracledbconnection);
							exit;
						}
						/* ========== PAYMENT ========== */
					} else {
						oci_free_statement($stmt_payment_sppt);
						$response = array("ajaxresult"=>"notfound");
						header('Content-Type: application/json');
						echo json_encode($response);
						oci_close($oracledbconnection);
						exit;
					}
					
				} else {
					//oci_free_statement($stmt_select_current_sppt);
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_close($oracledbconnection);
					exit;
				}
			} else {
				oci_free_statement($stmt_select_current_sppt);
				$response = array("ajaxresult"=>"notfound");
				header('Content-Type: application/json');
				echo json_encode($response);
				oci_close($oracledbconnection);
				exit;
			}
		}
	} else {
		/* IF cmdx is not defined */
		$response = array("ajaxresult"=>"undefined");
		header('Content-Type: application/json');
		echo json_encode($response);
	}
} else {
	/* IF cmdx is not defined */
	$response = array("ajaxresult"=>"undefined");
	header('Content-Type: application/json');
	echo json_encode($response);
}
?>