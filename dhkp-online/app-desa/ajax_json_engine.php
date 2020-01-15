<?php
/* ERROR DEBUG HANDLE */
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('max_execution_time', 300);
/* SESSION START */
session_start();
$txsessionid = session_id();
/* CONFIGURATION */
require_once dirname(__FILE__) . '/../variablen.php';
require_once dirname(__FILE__) . '/../einstellung.php';
require_once dirname(__FILE__) . '/../funktion.php';
header("Access-Control-Allow-Origin: *");
if (isset($_GET['cmdx'])){
	if ($_GET['cmdx'] == "TMBH_PETUGAS_PENAGIHAN") {
		$NAMA_PETUGAS = $_GET['NAMA_PETUGAS'];
		$WILAYAH = $_GET['WILAYAH'];
		$JABATAN = $_GET['JABATAN'];
		$KD_PROPINSI = $_SESSION['desaXweb']['KD_PROPINSI'];
		$KD_DATI2 = $_SESSION['desaXweb']['KD_DATI2']; 
		$KD_KECAMATAN = $_SESSION['desaXweb']['KD_KECAMATAN'];
		$KD_KELURAHAN = $_SESSION['desaXweb']['KD_KELURAHAN'];
		$NM_KECAMATAN = $_SESSION['desaXweb']['NM_KECAMATAN'];
		$NM_KELURAHAN = $_SESSION['desaXweb']['NM_KELURAHAN'];
		$STATUS = 1;
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("INSERT INTO appx_desa_petugas_pungut (KD_PROPINSI, KD_DATI2, KD_KECAMATAN, KD_KELURAHAN, NM_KECAMATAN, NM_KELURAHAN, NAMA_PETUGAS, WILAYAH, JABATAN, STATUS) VALUES (:KD_PROPINSI, :KD_DATI2, :KD_KECAMATAN, :KD_KELURAHAN, UPPER(:NM_KECAMATAN), UPPER(:NM_KELURAHAN), UPPER(:NAMA_PETUGAS), UPPER(:WILAYAH), UPPER(:JABATAN), :STATUS)");
				$stmt->bindValue(":STATUS", $STATUS, PDO::PARAM_STR);
				$stmt->bindValue(":NAMA_PETUGAS", $NAMA_PETUGAS, PDO::PARAM_STR);
				$stmt->bindValue(":WILAYAH", $WILAYAH, PDO::PARAM_STR);
				$stmt->bindValue(":JABATAN", $JABATAN, PDO::PARAM_STR);
				$stmt->bindValue(":KD_PROPINSI", $KD_PROPINSI, PDO::PARAM_STR);
				$stmt->bindValue(":KD_DATI2", $KD_DATI2, PDO::PARAM_STR);
				$stmt->bindValue(":KD_KECAMATAN", $KD_KECAMATAN, PDO::PARAM_STR);
				$stmt->bindValue(":KD_KELURAHAN", $KD_KELURAHAN, PDO::PARAM_STR);
				$stmt->bindValue(":NM_KECAMATAN", $NM_KECAMATAN, PDO::PARAM_STR);
				$stmt->bindValue(":NM_KELURAHAN", $NM_KELURAHAN, PDO::PARAM_STR);
				if($stmt->execute()){
					$response = array("ajaxresult"=>"success");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				} else {
					$response = array("ajaxresult"=>"failed");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"failed","ajaxmessage"=>"".$e->getMessage()."");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
	} elseif ($_GET['cmdx'] == "EDIT_PETUGAS_PENAGIHAN") {
		$ID_PETUGAS = $_GET['petugasid'];
		$NAMA_PETUGAS = $_GET['NAMA_PETUGAS'];
		$WILAYAH = $_GET['WILAYAH'];
		$JABATAN = $_GET['JABATAN'];
		$KD_PROPINSI = $_SESSION['desaXweb']['KD_PROPINSI'];
		$KD_DATI2 = $_SESSION['desaXweb']['KD_DATI2']; 
		$KD_KECAMATAN = $_SESSION['desaXweb']['KD_KECAMATAN'];
		$KD_KELURAHAN = $_SESSION['desaXweb']['KD_KELURAHAN'];
		$NM_KECAMATAN = $_SESSION['desaXweb']['NM_KECAMATAN'];
		$NM_KELURAHAN = $_SESSION['desaXweb']['NM_KELURAHAN'];
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("UPDATE appx_desa_petugas_pungut SET NAMA_PETUGAS = UCASE(:NAMA_PETUGAS), WILAYAH = UCASE(:WILAYAH), JABATAN = UCASE(:JABATAN) WHERE KD_PROPINSI = :KD_PROPINSI AND KD_DATI2 = :KD_DATI2 AND KD_KECAMATAN = :KD_KECAMATAN AND KD_KELURAHAN = :KD_KELURAHAN AND idx = :idxpetugas LIMIT 1");
				$stmt->bindValue(":idxpetugas", $ID_PETUGAS, PDO::PARAM_INT);
				$stmt->bindValue(":NAMA_PETUGAS", $NAMA_PETUGAS, PDO::PARAM_STR);
				$stmt->bindValue(":WILAYAH", $WILAYAH, PDO::PARAM_STR);
				$stmt->bindValue(":JABATAN", $JABATAN, PDO::PARAM_STR);
				$stmt->bindValue(":KD_PROPINSI", $KD_PROPINSI, PDO::PARAM_STR);
				$stmt->bindValue(":KD_DATI2", $KD_DATI2, PDO::PARAM_STR);
				$stmt->bindValue(":KD_KECAMATAN", $KD_KECAMATAN, PDO::PARAM_STR);
				$stmt->bindValue(":KD_KELURAHAN", $KD_KELURAHAN, PDO::PARAM_STR);
				if($stmt->execute()){
					$dbcondesa = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
					$dbcondesa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$stmt_update_assignment = $dbcondesa->prepare("UPDATE sppt_data SET NAMAPETUGAS = UCASE(:NAMA_PETUGAS) WHERE KD_PROPINSI = :KD_PROPINSI AND KD_DATI2 = :KD_DATI2 AND KD_KECAMATAN = :KD_KECAMATAN AND KD_KELURAHAN = :KD_KELURAHAN AND PETUGASIDX = :idxpetugas");
					$stmt_update_assignment->bindValue(":idxpetugas", $ID_PETUGAS, PDO::PARAM_INT);
					$stmt_update_assignment->bindValue(":NAMA_PETUGAS", $NAMA_PETUGAS, PDO::PARAM_STR);
					$stmt_update_assignment->bindValue(":KD_PROPINSI", $KD_PROPINSI, PDO::PARAM_STR);
					$stmt_update_assignment->bindValue(":KD_DATI2", $KD_DATI2, PDO::PARAM_STR);
					$stmt_update_assignment->bindValue(":KD_KECAMATAN", $KD_KECAMATAN, PDO::PARAM_STR);
					$stmt_update_assignment->bindValue(":KD_KELURAHAN", $KD_KELURAHAN, PDO::PARAM_STR);
					if ($stmt_update_assignment->execute()) {
						$response = array("ajaxresult"=>"updated");
						header('Content-Type: application/json');
						echo json_encode($response);
						$dbcondesa = null;
						$dbcon = null;
						exit;
					} else {
						$response = array("ajaxresult"=>"updated");
						header('Content-Type: application/json');
						echo json_encode($response);
						$dbcondesa = null;
						$dbcon = null;
						exit;
					}
				} else {
					$response = array("ajaxresult"=>"notupdated");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"notupdated","ajaxmessage"=>"".$e->getMessage()."");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
	} elseif ($_GET['cmdx'] == "DEAKTIVASI_PETUGAS_PENAGIHAN") {
		$ID_PETUGAS = $_GET['petugasid'];
		$KD_PROPINSI = $_SESSION['desaXweb']['KD_PROPINSI'];
		$KD_DATI2 = $_SESSION['desaXweb']['KD_DATI2']; 
		$KD_KECAMATAN = $_SESSION['desaXweb']['KD_KECAMATAN'];
		$KD_KELURAHAN = $_SESSION['desaXweb']['KD_KELURAHAN'];
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("UPDATE appx_desa_petugas_pungut SET STATUS = 0 WHERE KD_PROPINSI = :KD_PROPINSI AND KD_DATI2 = :KD_DATI2 AND KD_KECAMATAN = :KD_KECAMATAN AND KD_KELURAHAN = :KD_KELURAHAN AND idx = :idxpetugas LIMIT 1");
			$stmt->bindValue(":idxpetugas", $ID_PETUGAS, PDO::PARAM_INT);
			$stmt->bindValue(":KD_PROPINSI", $KD_PROPINSI, PDO::PARAM_STR);
			$stmt->bindValue(":KD_DATI2", $KD_DATI2, PDO::PARAM_STR);
			$stmt->bindValue(":KD_KECAMATAN", $KD_KECAMATAN, PDO::PARAM_STR);
			$stmt->bindValue(":KD_KELURAHAN", $KD_KELURAHAN, PDO::PARAM_STR);
			if($stmt->execute()){
				$response = array("ajaxresult"=>"deactivated");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			} else {
				$response = array("ajaxresult"=>"notdeactivated");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"notdeactivated","ajaxmessage"=>"".$e->getMessage()."");
			header('Content-Type: application/json');
			echo json_encode($response);
			$dbcon = null;
			exit;
		}
	} elseif ($_GET['cmdx'] == "REAKTIVASI_PETUGAS_PENAGIHAN") {
		$ID_PETUGAS = $_GET['petugasid'];
		$KD_PROPINSI = $_SESSION['desaXweb']['KD_PROPINSI'];
		$KD_DATI2 = $_SESSION['desaXweb']['KD_DATI2']; 
		$KD_KECAMATAN = $_SESSION['desaXweb']['KD_KECAMATAN'];
		$KD_KELURAHAN = $_SESSION['desaXweb']['KD_KELURAHAN'];
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("UPDATE appx_desa_petugas_pungut SET STATUS = 1 WHERE KD_PROPINSI = :KD_PROPINSI AND KD_DATI2 = :KD_DATI2 AND KD_KECAMATAN = :KD_KECAMATAN AND KD_KELURAHAN = :KD_KELURAHAN AND idx = :idxpetugas LIMIT 1");
				$stmt->bindValue(":idxpetugas", $ID_PETUGAS, PDO::PARAM_INT);
				$stmt->bindValue(":KD_PROPINSI", $KD_PROPINSI, PDO::PARAM_STR);
				$stmt->bindValue(":KD_DATI2", $KD_DATI2, PDO::PARAM_STR);
				$stmt->bindValue(":KD_KECAMATAN", $KD_KECAMATAN, PDO::PARAM_STR);
				$stmt->bindValue(":KD_KELURAHAN", $KD_KELURAHAN, PDO::PARAM_STR);
				if($stmt->execute()){
					$response = array("ajaxresult"=>"reactivated");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				} else {
					$response = array("ajaxresult"=>"notreactivated");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"notreactivated","ajaxmessage"=>"".$e->getMessage()."");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
	} elseif ($_GET['cmdx'] == "CHANGE_PASSWORD_DESA") {
    $first_passwd = hash_hmac('sha1', $_GET['newpassword'], '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
    $conf_passwd = hash_hmac('sha1', $_GET['confirmpassword'], '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
		if ($first_passwd == $conf_passwd) {
			try {
				/* connection setting */
				$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $dbcon->prepare("UPDATE appx_desa_users SET WORDPASS = :np WHERE KD_PROPINSI = :KDPROP AND KD_DATI2 = :KDKAB AND KD_KECAMATAN = :KDKEC AND KD_KELURAHAN = :KDKEL");
				$stmt->bindValue(":np", $first_passwd, PDO::PARAM_STR);
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
	} elseif ($_GET['cmdx'] == "GET_DESA_HOME_INFO") {
		$kode_propinsi = $_SESSION['desaXweb']['KD_PROPINSI'];
		$kode_dati2 = $_SESSION['desaXweb']['KD_DATI2'];
		$kode_kecamatan = $_SESSION['desaXweb']['KD_KECAMATAN'];
		$kode_kelurahan = $_SESSION['desaXweb']['KD_KELURAHAN'];
		$namaKecamatan = $_SESSION['desaXweb']['NM_KECAMATAN'];
		$namaKelurahan = $_SESSION['desaXweb']['NM_KELURAHAN'];
		$thnsppt = $_SESSION['tahunPAJAK'];
		$tglterbitdefault = strval("".$_SESSION['tahunPAJAK']."-01-02");
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
			/* counting data */
			$stmt_count_sppt_data = $dbcon->prepare("SELECT COUNT(*) as foundrows FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak");
			$stmt_count_sppt_data->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt_count_sppt_data->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt_count_sppt_data->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt_count_sppt_data->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			$stmt_count_sppt_data->bindValue(":thnpajak", $_SESSION['tahunPAJAK'], PDO::PARAM_STR);
			if ($stmt_count_sppt_data->execute()) {
				while($rowset_count_sppt_data = $stmt_count_sppt_data->fetch(PDO::FETCH_ASSOC)){
					$rowsfound = $rowset_count_sppt_data['foundrows'];
				}
				if (intval($rowsfound)>0) {
					$stmt_summary_sppt_data = $dbcon->prepare("SELECT COUNT(*) AS totalcountsppt, COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprova AND KD_DATI2 = :kdkaba AND KD_KECAMATAN = :kdkeca AND KD_KELURAHAN = :kddesaa AND THN_PAJAK_SPPT = :thnpajaka) AS countallpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovb AND KD_DATI2 = :kdkabb AND KD_KECAMATAN = :kdkecb AND KD_KELURAHAN = :kddesab AND THN_PAJAK_SPPT = :thnpajakb AND TGL_TERBIT_SPPT = '2017-01-02') AS countdefaultpenetapan, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovc AND KD_DATI2 = :kdkabc AND KD_KECAMATAN = :kdkecc AND KD_KELURAHAN = :kddesac AND THN_PAJAK_SPPT = :thnpajakc AND TGL_TERBIT_SPPT = '2017-01-02') AS sumdefaultpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovd AND KD_DATI2 = :kdkabd AND KD_KECAMATAN = :kdkecd AND KD_KELURAHAN = :kddesad AND THN_PAJAK_SPPT = :thnpajakd AND TGL_TERBIT_SPPT != '2017-01-02') AS countlaterpenetapan, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprove AND KD_DATI2 = :kdkabe AND KD_KECAMATAN = :kdkece AND KD_KELURAHAN = :kddesae AND THN_PAJAK_SPPT = :thnpajake AND TGL_TERBIT_SPPT != '2017-01-02') AS sumlaterpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovf AND KD_DATI2 = :kdkabf AND KD_KECAMATAN = :kdkecf AND KD_KELURAHAN = :kddesaf AND THN_PAJAK_SPPT = :thnpajakf AND PETUGASIDX = 0) AS countunassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovg AND KD_DATI2 = :kdkabg AND KD_KECAMATAN = :kdkecg AND KD_KELURAHAN = :kddesag AND THN_PAJAK_SPPT = :thnpajakg AND PETUGASIDX = 0) AS sumunassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovh AND KD_DATI2 = :kdkabh AND KD_KECAMATAN = :kdkech AND KD_KELURAHAN = :kddesah AND THN_PAJAK_SPPT = :thnpajakh AND PETUGASIDX > 0) AS countassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovi AND KD_DATI2 = :kdkabi AND KD_KECAMATAN = :kdkeci AND KD_KELURAHAN = :kddesai AND THN_PAJAK_SPPT = :thnpajaki AND PETUGASIDX > 0) AS sumassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovj AND KD_DATI2 = :kdkabj AND KD_KECAMATAN = :kdkecj AND KD_KELURAHAN = :kddesaj AND THN_PAJAK_SPPT = :thnpajakj AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS countunpaiddesa, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovk AND KD_DATI2 = :kdkabk AND KD_KECAMATAN = :kdkeck AND KD_KELURAHAN = :kddesak AND THN_PAJAK_SPPT = :thnpajakk AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS sumunpaiddesa, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovl AND KD_DATI2 = :kdkabl AND KD_KECAMATAN = :kdkecl AND KD_KELURAHAN = :kddesal AND THN_PAJAK_SPPT = :thnpajakl AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS countpaiddesa, (SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovm AND KD_DATI2 = :kdkabm AND KD_KECAMATAN = :kdkecm AND KD_KELURAHAN = :kddesam AND THN_PAJAK_SPPT = :thnpajakm AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS sumpaiddesa, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovn AND KD_DATI2 = :kdkabn AND KD_KECAMATAN = :kdkecn AND KD_KELURAHAN = :kddesan AND THN_PAJAK_SPPT = :thnpajakn AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX > 0) AS countpaidassigned, (SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovo AND KD_DATI2 = :kdkabo AND KD_KECAMATAN = :kdkeco AND KD_KELURAHAN = :kddesao AND THN_PAJAK_SPPT = :thnpajako AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX > 0) AS sumpaidassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovp AND KD_DATI2 = :kdkabp AND KD_KECAMATAN = :kdkecp AND KD_KELURAHAN = :kddesap AND THN_PAJAK_SPPT = :thnpajakp AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX > 0) AS countunpaidassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovq AND KD_DATI2 = :kdkabq AND KD_KECAMATAN = :kdkecq AND KD_KELURAHAN = :kddesaq AND THN_PAJAK_SPPT = :thnpajakq AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX > 0) AS sumunpaidassigned FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak");
					$stmt_summary_sppt_data->bindValue(":kdprova", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkaba", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkeca", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesaa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajaka", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovb", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabb", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecb", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesab", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakb", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovc", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabc", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecc", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesac", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakc", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovd", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabd", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecd", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesad", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakd", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprove", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabe", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkece", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesae", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajake", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovf", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabf", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecf", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesaf", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakf", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovg", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabg", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecg", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesag", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakg", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovh", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabh", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkech", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesah", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakh", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovi", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabi", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkeci", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesai", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajaki", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovj", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabj", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecj", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesaj", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakj", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovk", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabk", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkeck", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesak", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakk", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovl", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabl", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecl", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesal", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakl", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovm", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabm", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecm", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesam", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakm", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovn", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabn", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecn", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesan", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakn", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovo", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabo", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkeco", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesao", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajako", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovp", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabp", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecp", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesap", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakp", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovq", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabq", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecq", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesaq", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakq", $_SESSION['tahunPAJAK'], PDO::PARAM_STR);
					$stmt_summary_sppt_data->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
					$stmt_summary_sppt_data->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
					$stmt_summary_sppt_data->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
					$stmt_summary_sppt_data->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
					$stmt_summary_sppt_data->bindValue(":thnpajak", $_SESSION['tahunPAJAK'], PDO::PARAM_STR);
					$stmt_summary_sppt_data->execute();
					while($rowset_summary_sppt_data = $stmt_summary_sppt_data->fetch(PDO::FETCH_ASSOC)){
						$vtotalcountsppt = $rowset_summary_sppt_data['totalcountsppt']; $vtotalsumsppt = $rowset_summary_sppt_data['totalsumsppt']; $vcountallpenetapan = $rowset_summary_sppt_data['countallpenetapan'];
						$vcountdefaultpenetapan = $rowset_summary_sppt_data['countdefaultpenetapan']; $vsumdefaultpenetapan = $rowset_summary_sppt_data['sumdefaultpenetapan'];
						$vcountlaterpenetapan = $rowset_summary_sppt_data['countlaterpenetapan']; $vsumlaterpenetapan = $rowset_summary_sppt_data['sumlaterpenetapan'];
						$vcountunassigned = $rowset_summary_sppt_data['countunassigned']; $vsumunassigned = $rowset_summary_sppt_data['sumunassigned'];
						$vcountassigned = $rowset_summary_sppt_data['countassigned']; $vsumassigned = $rowset_summary_sppt_data['sumassigned'];
						$vcountunpaiddesa = $rowset_summary_sppt_data['countunpaiddesa']; $vsumunpaiddesa = $rowset_summary_sppt_data['sumunpaiddesa'];
						$vcountpaiddesa = $rowset_summary_sppt_data['countpaiddesa']; $vsumpaiddesa = $rowset_summary_sppt_data['sumpaiddesa'];
						$vcountunpaidassigned = $rowset_summary_sppt_data['countunpaidassigned']; $vsumunpaidassigned = $rowset_summary_sppt_data['sumunpaidassigned'];
						$vcountpaidassigned = $rowset_summary_sppt_data['countpaidassigned']; $vsumpaidassigned = $rowset_summary_sppt_data['sumpaidassigned'];
					}
					/* ========== TEMP =========== */
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
					/* ========== /TEMP ========== */
					/* final response */
					$response = array(
						"ajaxresult"=>"found",
						"kodesppt"=>strval($_SESSION['desaXweb']['KD_PROPINSI'].'.'.$_SESSION['desaXweb']['KD_DATI2'].'.'.$_SESSION['desaXweb']['KD_KECAMATAN'].'.'.$_SESSION['desaXweb']['KD_KELURAHAN'].''),
						"namakecamatan"=>$_SESSION['desaXweb']['NM_KECAMATAN'],
						"namadesa"=>$_SESSION['desaXweb']['NM_KELURAHAN'],
						"tahunpajak"=>$_SESSION['tahunPAJAK'],
						"jumlahpersonil"=>$countpersonil,
						"totalcountsppt"=>$vtotalcountsppt,
						"totalsumsppt"=>$vtotalsumsppt,
						"countallpenetapan"=>$vcountallpenetapan,
						"countdefaultpenetapan"=>$vcountdefaultpenetapan,
						"sumdefaultpenetapan"=>$vsumdefaultpenetapan,
						"countlaterpenetapan"=>$vcountlaterpenetapan,
						"sumlaterpenetapan"=>$vsumlaterpenetapan,
						"countunassigned"=>$vcountunassigned,
						"sumunassigned"=>$vsumunassigned,
						"countassigned"=>$vcountassigned,
						"sumassigned"=>$vsumassigned,
						"countunpaiddesa"=>$vcountunpaiddesa,
						"sumunpaiddesa"=>$vsumunpaiddesa,
						"countpaiddesa"=>$vcountpaiddesa,
						"sumpaiddesa"=>$vsumpaiddesa,
						"countunpaidassigned"=>$vcountunpaidassigned,
						"sumunpaidassigned"=>$vsumunpaidassigned,
						"countpaidassigned"=>$vcountpaidassigned,
						"sumpaidassigned"=>$vsumpaidassigned,
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
						"rttmpsumunpaiddefaultpenetapan"=>$RTTMPsumunpaiddefaultpenetapan);
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
					/* /final response */
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
	} elseif ($_GET['cmdx'] == "GET_DESA_HOME_INFO_BYTAXYEAR") {
		$kode_propinsi = $_SESSION['desaXweb']['KD_PROPINSI'];
		$kode_dati2 = $_SESSION['desaXweb']['KD_DATI2'];
		$kode_kecamatan = $_SESSION['desaXweb']['KD_KECAMATAN'];
		$kode_kelurahan = $_SESSION['desaXweb']['KD_KELURAHAN'];
		$namaKecamatan = $_SESSION['desaXweb']['NM_KECAMATAN'];
		$namaKelurahan = $_SESSION['desaXweb']['NM_KELURAHAN'];
		$thnsppt = $_GET['taxyear'];
		$tglterbitdefault = strval("".$thnsppt."-01-02");
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
			/* counting data */
			$stmt_count_sppt_data = $dbcon->prepare("SELECT COUNT(*) as foundrows FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak");
			$stmt_count_sppt_data->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt_count_sppt_data->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt_count_sppt_data->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt_count_sppt_data->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			$stmt_count_sppt_data->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
			if ($stmt_count_sppt_data->execute()) {
				while($rowset_count_sppt_data = $stmt_count_sppt_data->fetch(PDO::FETCH_ASSOC)){
					$rowsfound = $rowset_count_sppt_data['foundrows'];
				}
				if (intval($rowsfound)>0) {
					$stmt_summary_sppt_data = $dbcon->prepare("SELECT COUNT(*) AS totalcountsppt, COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprova AND KD_DATI2 = :kdkaba AND KD_KECAMATAN = :kdkeca AND KD_KELURAHAN = :kddesaa AND THN_PAJAK_SPPT = :thnpajaka) AS countallpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovb AND KD_DATI2 = :kdkabb AND KD_KECAMATAN = :kdkecb AND KD_KELURAHAN = :kddesab AND THN_PAJAK_SPPT = :thnpajakb AND TGL_TERBIT_SPPT = '2017-01-02') AS countdefaultpenetapan, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovc AND KD_DATI2 = :kdkabc AND KD_KECAMATAN = :kdkecc AND KD_KELURAHAN = :kddesac AND THN_PAJAK_SPPT = :thnpajakc AND TGL_TERBIT_SPPT = '2017-01-02') AS sumdefaultpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovd AND KD_DATI2 = :kdkabd AND KD_KECAMATAN = :kdkecd AND KD_KELURAHAN = :kddesad AND THN_PAJAK_SPPT = :thnpajakd AND TGL_TERBIT_SPPT != '2017-01-02') AS countlaterpenetapan, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprove AND KD_DATI2 = :kdkabe AND KD_KECAMATAN = :kdkece AND KD_KELURAHAN = :kddesae AND THN_PAJAK_SPPT = :thnpajake AND TGL_TERBIT_SPPT != '2017-01-02') AS sumlaterpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovf AND KD_DATI2 = :kdkabf AND KD_KECAMATAN = :kdkecf AND KD_KELURAHAN = :kddesaf AND THN_PAJAK_SPPT = :thnpajakf AND PETUGASIDX = 0) AS countunassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovg AND KD_DATI2 = :kdkabg AND KD_KECAMATAN = :kdkecg AND KD_KELURAHAN = :kddesag AND THN_PAJAK_SPPT = :thnpajakg AND PETUGASIDX = 0) AS sumunassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovh AND KD_DATI2 = :kdkabh AND KD_KECAMATAN = :kdkech AND KD_KELURAHAN = :kddesah AND THN_PAJAK_SPPT = :thnpajakh AND PETUGASIDX > 0) AS countassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovi AND KD_DATI2 = :kdkabi AND KD_KECAMATAN = :kdkeci AND KD_KELURAHAN = :kddesai AND THN_PAJAK_SPPT = :thnpajaki AND PETUGASIDX > 0) AS sumassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovj AND KD_DATI2 = :kdkabj AND KD_KECAMATAN = :kdkecj AND KD_KELURAHAN = :kddesaj AND THN_PAJAK_SPPT = :thnpajakj AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS countunpaiddesa, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovk AND KD_DATI2 = :kdkabk AND KD_KECAMATAN = :kdkeck AND KD_KELURAHAN = :kddesak AND THN_PAJAK_SPPT = :thnpajakk AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS sumunpaiddesa, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovl AND KD_DATI2 = :kdkabl AND KD_KECAMATAN = :kdkecl AND KD_KELURAHAN = :kddesal AND THN_PAJAK_SPPT = :thnpajakl AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS countpaiddesa, (SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovm AND KD_DATI2 = :kdkabm AND KD_KECAMATAN = :kdkecm AND KD_KELURAHAN = :kddesam AND THN_PAJAK_SPPT = :thnpajakm AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS sumpaiddesa, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovn AND KD_DATI2 = :kdkabn AND KD_KECAMATAN = :kdkecn AND KD_KELURAHAN = :kddesan AND THN_PAJAK_SPPT = :thnpajakn AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX > 0) AS countpaidassigned, (SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovo AND KD_DATI2 = :kdkabo AND KD_KECAMATAN = :kdkeco AND KD_KELURAHAN = :kddesao AND THN_PAJAK_SPPT = :thnpajako AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX > 0) AS sumpaidassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovp AND KD_DATI2 = :kdkabp AND KD_KECAMATAN = :kdkecp AND KD_KELURAHAN = :kddesap AND THN_PAJAK_SPPT = :thnpajakp AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX > 0) AS countunpaidassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovq AND KD_DATI2 = :kdkabq AND KD_KECAMATAN = :kdkecq AND KD_KELURAHAN = :kddesaq AND THN_PAJAK_SPPT = :thnpajakq AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX > 0) AS sumunpaidassigned FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak");
					$stmt_summary_sppt_data->bindValue(":kdprova", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkaba", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkeca", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesaa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajaka", $thnsppt, PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovb", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabb", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecb", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesab", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakb", $thnsppt, PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovc", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabc", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecc", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesac", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakc", $thnsppt, PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovd", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabd", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecd", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesad", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakd", $thnsppt, PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprove", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabe", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkece", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesae", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajake", $thnsppt, PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovf", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabf", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecf", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesaf", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakf", $thnsppt, PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovg", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabg", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecg", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesag", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakg", $thnsppt, PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovh", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabh", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkech", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesah", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakh", $thnsppt, PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovi", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabi", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkeci", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesai", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajaki", $thnsppt, PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovj", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabj", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecj", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesaj", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakj", $thnsppt, PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovk", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabk", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkeck", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesak", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakk", $thnsppt, PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovl", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabl", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecl", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesal", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakl", $thnsppt, PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovm", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabm", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecm", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesam", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakm", $thnsppt, PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovn", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabn", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecn", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesan", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakn", $thnsppt, PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovo", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabo", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkeco", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesao", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajako", $thnsppt, PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovp", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabp", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecp", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesap", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakp", $thnsppt, PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdprovq", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkabq", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kdkecq", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":kddesaq", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR); 
					$stmt_summary_sppt_data->bindValue(":thnpajakq", $thnsppt, PDO::PARAM_STR);
					$stmt_summary_sppt_data->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
					$stmt_summary_sppt_data->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
					$stmt_summary_sppt_data->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
					$stmt_summary_sppt_data->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
					$stmt_summary_sppt_data->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
					$stmt_summary_sppt_data->execute();
					while($rowset_summary_sppt_data = $stmt_summary_sppt_data->fetch(PDO::FETCH_ASSOC)){
						$vtotalcountsppt = $rowset_summary_sppt_data['totalcountsppt']; $vtotalsumsppt = $rowset_summary_sppt_data['totalsumsppt']; $vcountallpenetapan = $rowset_summary_sppt_data['countallpenetapan'];
						$vcountdefaultpenetapan = $rowset_summary_sppt_data['countdefaultpenetapan']; $vsumdefaultpenetapan = $rowset_summary_sppt_data['sumdefaultpenetapan'];
						$vcountlaterpenetapan = $rowset_summary_sppt_data['countlaterpenetapan']; $vsumlaterpenetapan = $rowset_summary_sppt_data['sumlaterpenetapan'];
						$vcountunassigned = $rowset_summary_sppt_data['countunassigned']; $vsumunassigned = $rowset_summary_sppt_data['sumunassigned'];
						$vcountassigned = $rowset_summary_sppt_data['countassigned']; $vsumassigned = $rowset_summary_sppt_data['sumassigned'];
						$vcountunpaiddesa = $rowset_summary_sppt_data['countunpaiddesa']; $vsumunpaiddesa = $rowset_summary_sppt_data['sumunpaiddesa'];
						$vcountpaiddesa = $rowset_summary_sppt_data['countpaiddesa']; $vsumpaiddesa = $rowset_summary_sppt_data['sumpaiddesa'];
						$vcountunpaidassigned = $rowset_summary_sppt_data['countunpaidassigned']; $vsumunpaidassigned = $rowset_summary_sppt_data['sumunpaidassigned'];
						$vcountpaidassigned = $rowset_summary_sppt_data['countpaidassigned']; $vsumpaidassigned = $rowset_summary_sppt_data['sumpaidassigned'];
					}
					/* ========== TEMP =========== */
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
					/* ========== /TEMP ========== */
					/* final response */
					$response = array(
						"ajaxresult"=>"found",
						"kodesppt"=>strval($_SESSION['desaXweb']['KD_PROPINSI'].'.'.$_SESSION['desaXweb']['KD_DATI2'].'.'.$_SESSION['desaXweb']['KD_KECAMATAN'].'.'.$_SESSION['desaXweb']['KD_KELURAHAN'].''),
						"namakecamatan"=>$_SESSION['desaXweb']['NM_KECAMATAN'],
						"namadesa"=>$_SESSION['desaXweb']['NM_KELURAHAN'],
						"tahunpajak"=>$thnsppt,
						"jumlahpersonil"=>$countpersonil,
						"totalcountsppt"=>$vtotalcountsppt,
						"totalsumsppt"=>$vtotalsumsppt,
						"countallpenetapan"=>$vcountallpenetapan,
						"countdefaultpenetapan"=>$vcountdefaultpenetapan,
						"sumdefaultpenetapan"=>$vsumdefaultpenetapan,
						"countlaterpenetapan"=>$vcountlaterpenetapan,
						"sumlaterpenetapan"=>$vsumlaterpenetapan,
						"countunassigned"=>$vcountunassigned,
						"sumunassigned"=>$vsumunassigned,
						"countassigned"=>$vcountassigned,
						"sumassigned"=>$vsumassigned,
						"countunpaiddesa"=>$vcountunpaiddesa,
						"sumunpaiddesa"=>$vsumunpaiddesa,
						"countpaiddesa"=>$vcountpaiddesa,
						"sumpaiddesa"=>$vsumpaiddesa,
						"countunpaidassigned"=>$vcountunpaidassigned,
						"sumunpaidassigned"=>$vsumunpaidassigned,
						"countpaidassigned"=>$vcountpaidassigned,
						"sumpaidassigned"=>$vsumpaidassigned,
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
						"rttmpsumunpaiddefaultpenetapan"=>$RTTMPsumunpaiddefaultpenetapan);
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
					/* /final response */
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
	} elseif ($_GET['cmdx'] == "LIST_DISTRIBUSI_PENAGIHAN") {
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			/* counting data */
			$stmt_cnt = $dbcon->prepare("SELECT COUNT(*) as foundrows FROM appx_desa_petugas_pungut WHERE KD_PROPINSI = :kdprop AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa ORDER BY NAMA_PETUGAS");
			$stmt_cnt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			if ($stmt_cnt->execute()) {
				while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
					$rowsfound = $rowset_cnt['foundrows'];
				}
				if (intval($rowsfound)>0) {
					$stmt = $dbcon->prepare("SELECT * FROM appx_desa_petugas_pungut WHERE KD_PROPINSI = :kdprop AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa ORDER BY NAMA_PETUGAS");
					$stmt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
					$stmt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
					$stmt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
					$stmt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
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
	} elseif ($_GET['cmdx'] == "GET_PETUGAS_PENAGIH") {
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			/* counting data */
			$stmt_cnt = $dbcon->prepare("SELECT COUNT(idx) as foundrows FROM appx_desa_petugas_pungut WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND STATUS = 1");
			$stmt_cnt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			if ($stmt_cnt->execute()) {
				while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
					$rowsfound = $rowset_cnt['foundrows'];
				}
				if (intval($rowsfound)>0) {
					$stmt = $dbcon->prepare("SELECT * FROM appx_desa_petugas_pungut WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND STATUS = 1 ORDER BY NAMA_PETUGAS");
					$stmt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
					$stmt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
					$stmt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
					$stmt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
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
	} elseif ($_GET['cmdx'] == "GET_BLOK_LIST_SPPT_DESA") {
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			/* counting data */
			$stmt_cnt = $dbcon->prepare("SELECT COUNT(*) as foundrows FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak");
			$stmt_cnt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":thnpajak", $_SESSION['tahunPAJAK'], PDO::PARAM_STR);
			if ($stmt_cnt->execute()) {
				while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
					$rowsfound = $rowset_cnt['foundrows'];
				}
				if (intval($rowsfound)>0) {
					$stmt = $dbcon->prepare("SELECT DISTINCT(KD_BLOK) AS KD_BLOK, KD_PROPINSI, KD_DATI2, KD_KECAMATAN, KD_KELURAHAN FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak GROUP BY KD_BLOK");
					$stmt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
					$stmt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
					$stmt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
					$stmt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
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
	} elseif ($_GET['cmdx'] == "ASSIGN_NOP_TO_PETUGAS_EDIT") {
		$petugasidx = $_GET['idxpetugas'];
		$petugasnama = $_GET['namapetugas'];
		$KD_PROPINSI = $_GET['kdprov'];
		$KD_DATI2 = $_GET['kdkab'];
		$KD_KECAMATAN = $_GET['kdkec'];
		$KD_KELURAHAN = $_GET['kdkel'];
		$KD_BLOK = $_GET['kdblok'];
		$NO_URUT = $_GET['nourut'];
		$KD_JNS_OP = $_GET['kdjop'];
		$THN_PAJAK_SPPT = $_GET['pajakth'];
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			/* counting data */
			$stmt = $dbcon->prepare("UPDATE sppt_data SET PETUGASIDX = :idpetugas, NAMAPETUGAS = :nmpetugas WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND KD_BLOK = :kdblok AND NO_URUT = :nourut AND KD_JNS_OP = :kdjnsop AND THN_PAJAK_SPPT = :thnpajak");
			$stmt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			$stmt->bindValue(":kdblok", $KD_BLOK, PDO::PARAM_STR);
			$stmt->bindValue(":nourut", $NO_URUT, PDO::PARAM_STR);
			$stmt->bindValue(":kdjnsop", $KD_JNS_OP, PDO::PARAM_STR);
			$stmt->bindValue(":thnpajak", $THN_PAJAK_SPPT, PDO::PARAM_STR);
			$stmt->bindValue(":idpetugas", $petugasidx, PDO::PARAM_INT);
			$stmt->bindValue(":nmpetugas", $petugasnama, PDO::PARAM_STR);
			if ($stmt->execute()) {
				$response = array("ajaxresult"=>"assigned");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			} else {
				$response = array("ajaxresult"=>"notassigned");
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
	} elseif ($_GET['cmdx'] == "ASSIGN_NOP_TO_PETUGAS") {
		$petugasidx = $_GET['idxpetugas'];
		$petugasnama = $_GET['namapetugas'];
		$KD_PROPINSI = $_GET['kdprov'];
		$KD_DATI2 = $_GET['kdkab'];
		$KD_KECAMATAN = $_GET['kdkec'];
		$KD_KELURAHAN = $_GET['kdkel'];
		$KD_BLOK = $_GET['kdblok'];
		$NO_URUT = $_GET['nourut'];
		$KD_JNS_OP = $_GET['kdjop'];
		$THN_PAJAK_SPPT = $_GET['pajakth'];
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			/* counting data */
			$stmt = $dbcon->prepare("UPDATE sppt_data SET PETUGASIDX = :idpetugas, NAMAPETUGAS = :nmpetugas, STATUS = 1 WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND KD_BLOK = :kdblok AND NO_URUT = :nourut AND KD_JNS_OP = :kdjnsop AND THN_PAJAK_SPPT = :thnpajak");
			$stmt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			$stmt->bindValue(":kdblok", $KD_BLOK, PDO::PARAM_STR);
			$stmt->bindValue(":nourut", $NO_URUT, PDO::PARAM_STR);
			$stmt->bindValue(":kdjnsop", $KD_JNS_OP, PDO::PARAM_STR);
			$stmt->bindValue(":thnpajak", $THN_PAJAK_SPPT, PDO::PARAM_STR);
			$stmt->bindValue(":idpetugas", $petugasidx, PDO::PARAM_INT);
			$stmt->bindValue(":nmpetugas", $petugasnama, PDO::PARAM_STR);
			if ($stmt->execute()) {
				$response = array("ajaxresult"=>"assigned");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			} else {
				$response = array("ajaxresult"=>"notassigned");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"notassigned","ajaxmessage"=>"".$e->getMessage()."");
			header('Content-Type: application/json');
			echo json_encode($response);
			$dbcon = null;
			exit;
		}
	} elseif ($_GET['cmdx'] == "DEASSIGN_NOP_TO_PETUGAS") {
		$petugasidx = $_GET['idxpetugas'];
		$petugasnama = $_GET['namapetugas'];
		$KD_PROPINSI = $_GET['kdprov'];
		$KD_DATI2 = $_GET['kdkab'];
		$KD_KECAMATAN = $_GET['kdkec'];
		$KD_KELURAHAN = $_GET['kdkel'];
		$KD_BLOK = $_GET['kdblok'];
		$NO_URUT = $_GET['nourut'];
		$KD_JNS_OP = $_GET['kdjop'];
		$THN_PAJAK_SPPT = $_GET['pajakth'];
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			/* counting data */
			$stmt = $dbcon->prepare("UPDATE sppt_data SET PETUGASIDX = 0, NAMAPETUGAS = '', STATUS = 0 WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND KD_BLOK = :kdblok AND NO_URUT = :nourut AND KD_JNS_OP = :kdjnsop AND THN_PAJAK_SPPT = :thnpajak AND PETUGASIDX = :idpetugas");
			$stmt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			$stmt->bindValue(":kdblok", $KD_BLOK, PDO::PARAM_STR);
			$stmt->bindValue(":nourut", $NO_URUT, PDO::PARAM_STR);
			$stmt->bindValue(":kdjnsop", $KD_JNS_OP, PDO::PARAM_STR);
			$stmt->bindValue(":thnpajak", $THN_PAJAK_SPPT, PDO::PARAM_STR);
			$stmt->bindValue(":idpetugas", $petugasidx, PDO::PARAM_INT);
			if ($stmt->execute()) {
				$response = array("ajaxresult"=>"deassigned");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			} else {
				$response = array("ajaxresult"=>"notdeassigned");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"notdeassigned","ajaxmessage"=>"".$e->getMessage()."");
			header('Content-Type: application/json');
			echo json_encode($response);
			$dbcon = null;
			exit;
		}
	} elseif ($_GET['cmdx'] == "ASSIGN_BLOK_TO_PETUGAS") {
		$kode_propinsi = $_SESSION['desaXweb']['KD_PROPINSI'];
		$kode_dati2 = $_SESSION['desaXweb']['KD_DATI2'];
		$kode_kecamatan = $_SESSION['desaXweb']['KD_KECAMATAN'];
		$kode_kelurahan = $_SESSION['desaXweb']['KD_KELURAHAN'];
		$namaKecamatan = $_SESSION['desaXweb']['NM_KECAMATAN'];
		$namaKelurahan = $_SESSION['desaXweb']['NM_KELURAHAN'];
		$thnsppt = $_SESSION['tahunPAJAK'];
  	$rawpetugasidx = $_GET['petugasidx'];
		$arrPetugasInfo = explode("|", $rawpetugasidx);
		$petugasidx = $arrPetugasInfo[0];
		$namaPetugas = $arrPetugasInfo[1];
		$KD_BLOK = $_GET['bloxidx'];
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			/* counting data */
			$stmt = $dbcon->prepare("UPDATE sppt_data SET PETUGASIDX = :idpetugas, NAMAPETUGAS = :nmpetugas, STATUS = 1 WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND KD_BLOK = :kdblok AND THN_PAJAK_SPPT = :thnpajak AND PETUGASIDX = 0");
			$stmt->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
			$stmt->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
			$stmt->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
			$stmt->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
			$stmt->bindValue(":kdblok", $KD_BLOK, PDO::PARAM_STR);
			$stmt->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
			$stmt->bindValue(":idpetugas", $petugasidx, PDO::PARAM_INT);
			$stmt->bindValue(":nmpetugas", $namaPetugas, PDO::PARAM_STR);
			if ($stmt->execute()) {
				$response = array("ajaxresult"=>"assigned");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			} else {
				$response = array("ajaxresult"=>"notassigned");
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
	} elseif ($_GET['cmdx'] == "ADD_TO_TEMPORARY_BANKENZAHLEN") {
		$strinfo = $_GET['idx'];
		$hashcodex = $_GET['codex'];
		$petugasidx = $_GET['idxpetugas'];
		$arrinfo = explode("|", $strinfo);
		$KD_PROPINSI = $arrinfo[0];
		$KD_DATI2 = $arrinfo[1];
		$KD_KECAMATAN = $arrinfo[2];
		$KD_KELURAHAN = $arrinfo[3];
		$KD_BLOK = $arrinfo[4];
		$NO_URUT = $arrinfo[5];
		$KD_JNS_OP = $arrinfo[6];
		$THN_PAJAK_SPPT = $arrinfo[7];
		$TGL_TERBIT_SPPT = $arrinfo[8];
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			/* counting data */
			$stmt = $dbcon->prepare("INSERT IGNORE INTO temp_sppt_data(IDX,KD_PROPINSI,KD_DATI2,KD_KECAMATAN,
						KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,SIKLUS_SPPT,KD_KANWIL_BANK,KD_KPPBB_BANK,KD_BANK_TUNGGAL,
						KD_BANK_PERSEPSI,KD_TP,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,KELURAHAN_WP_SPPT,
						KOTA_WP_SPPT,KD_POS_WP_SPPT,NPWP_SPPT,NO_PERSIL_SPPT,KD_KLS_TANAH,THN_AWAL_KLS_TANAH,KD_KLS_BNG,THN_AWAL_KLS_BNG,
						TGL_JATUH_TEMPO_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,NJOP_BUMI_SPPT,NJOP_BNG_SPPT,NJOP_SPPT,NJOPTKP_SPPT,NJKP_SPPT,
						PBB_TERHUTANG_SPPT,FAKTOR_PENGURANG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,STATUS_TAGIHAN_SPPT,
						STATUS_CETAK_SPPT,TGL_TERBIT_SPPT,TGL_CETAK_SPPT,NIP_PENCETAK_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,
						TGL_PEMBAYARAN_SPPT,TGL_REKAM_BYR_SPPT,NIP_REKAM_BYR_SPPT,PETUGASIDX,NAMAPETUGAS,STATUS,CODEX) 
					SELECT 
						:idxdata,KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,SIKLUS_SPPT,KD_KANWIL_BANK,
						KD_KPPBB_BANK,KD_BANK_TUNGGAL,KD_BANK_PERSEPSI,KD_TP,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,
						RT_WP_SPPT,KELURAHAN_WP_SPPT,KOTA_WP_SPPT,KD_POS_WP_SPPT,NPWP_SPPT,NO_PERSIL_SPPT,KD_KLS_TANAH,THN_AWAL_KLS_TANAH,
						KD_KLS_BNG,THN_AWAL_KLS_BNG,TGL_JATUH_TEMPO_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,NJOP_BUMI_SPPT,NJOP_BNG_SPPT,
						NJOP_SPPT,NJOPTKP_SPPT,NJKP_SPPT,PBB_TERHUTANG_SPPT,FAKTOR_PENGURANG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,
						STATUS_TAGIHAN_SPPT,STATUS_CETAK_SPPT,TGL_TERBIT_SPPT,TGL_CETAK_SPPT,NIP_PENCETAK_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,
						JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,TGL_REKAM_BYR_SPPT,NIP_REKAM_BYR_SPPT,PETUGASIDX,NAMAPETUGAS,STATUS,:hashxcode 
					FROM 
						sppt_data 
					WHERE 
						sppt_data.KD_PROPINSI = :kdprov 
						AND sppt_data.KD_DATI2 = :kdkab 
						AND sppt_data.KD_KECAMATAN = :kdkec 
						AND sppt_data.KD_KELURAHAN = :kddesa 
						AND sppt_data.KD_BLOK = :kdblok 
						AND sppt_data.NO_URUT = :nourut 
						AND sppt_data.KD_JNS_OP = :kdjnsop 
						AND sppt_data.THN_PAJAK_SPPT = :thnpajak 
						AND sppt_data.TGL_TERBIT_SPPT = :tglsppt 
						AND sppt_data.PETUGASIDX = :idpetugas");
			$stmt->bindValue(":idxdata", $strinfo, PDO::PARAM_STR);
			$stmt->bindValue(":kdprov", $KD_PROPINSI, PDO::PARAM_STR);
			$stmt->bindValue(":kdkab", $KD_DATI2, PDO::PARAM_STR);
			$stmt->bindValue(":kdkec", $KD_KECAMATAN, PDO::PARAM_STR);
			$stmt->bindValue(":kddesa", $KD_KELURAHAN, PDO::PARAM_STR);
			$stmt->bindValue(":kdblok", $KD_BLOK, PDO::PARAM_STR);
			$stmt->bindValue(":nourut", $NO_URUT, PDO::PARAM_STR);
			$stmt->bindValue(":kdjnsop", $KD_JNS_OP, PDO::PARAM_STR);
			$stmt->bindValue(":thnpajak", $THN_PAJAK_SPPT, PDO::PARAM_STR);
			$stmt->bindValue(":tglsppt", $TGL_TERBIT_SPPT, PDO::PARAM_STR);
			$stmt->bindValue(":idpetugas", $petugasidx, PDO::PARAM_INT);
			$stmt->bindValue(":hashxcode", $hashcodex, PDO::PARAM_STR);
			if ($stmt->execute()) {
				$response = array("ajaxresult"=>"added");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			} else {
				$response = array("ajaxresult"=>"notadded");
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
	} elseif ($_GET['cmdx'] == "REMOVE_FROM_TEMPORARY_BANKENZAHLEN") {
		$strinfo = $_GET['idx'];
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("DELETE FROM temp_sppt_data WHERE IDX = :idxdata");
			$stmt->bindValue(":idxdata", $strinfo, PDO::PARAM_STR);
			if ($stmt->execute()) {
				$stmt_optimise_table = $dbcon->query("OPTIMIZE TABLE temp_sppt_data");
				$response = array("ajaxresult"=>"removed");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			} else {
				$response = array("ajaxresult"=>"notremoved");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"notremoved","ajaxmessage"=>"".$e->getMessage()."");
			header('Content-Type: application/json');
			echo json_encode($response);
			$dbcon = null;
			exit;
		}
	} elseif ($_GET['cmdx'] == "CLEAR_TEMPORARY_BANKENZAHLEN") {
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			/* counting data */
			$stmt = $dbcon->prepare("DELETE FROM temp_sppt_data WHERE IDX LIKE CONCAT('%',:kdprov,'|',:kdkab,'|',:kdkec,'|',:kddesa,'|%')");
			$stmt->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			if ($stmt->execute()) {
				$stmt_optimise_table = $dbcon->query("OPTIMIZE TABLE temp_sppt_data");
				$response = array("ajaxresult"=>"cleared");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			} else {
				$response = array("ajaxresult"=>"notcleared");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"notcleared","ajaxmessage"=>"".$e->getMessage()."");
			header('Content-Type: application/json');
			echo json_encode($response);
			$dbcon = null;
			exit;
		}
	} elseif ($_GET['cmdx'] == "LOAD_STARTUP_DATA_SYNC_PETUGAS") {
		$currenttaxyear = $appxinfo['_tahun_pajak_'];
		$previoustaxyear = intval($currenttaxyear) - 1;
		$maxtaxyear = $appxinfo['_tahun_pajak_'];
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			/* ================ */
			$stmt_max_taxyear = $dbcon->prepare("SELECT MAX(THN_PAJAK_SPPT) AS maxtaxyear FROM sppt_data WHERE KD_PROPINSI = :KDPROPINSI AND KD_DATI2 = :KDDATI2 AND KD_KECAMATAN = :KDKECAMATAN AND KD_KELURAHAN = :KDKELURAHAN");
			$stmt_max_taxyear->bindValue(":KDPROPINSI", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt_max_taxyear->bindValue(":KDDATI2", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt_max_taxyear->bindValue(":KDKECAMATAN", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt_max_taxyear->bindValue(":KDKELURAHAN", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			if ($stmt_max_taxyear->execute()) {
				while($rowset_max_taxyear = $stmt_max_taxyear->fetch(PDO::FETCH_ASSOC)){
					$maxtaxyear = $rowset_max_taxyear['maxtaxyear'];
				}
				
				$ddl_summary_table = $dbcon->prepare("CREATE TEMPORARY TABLE sppt_data_status(THN_PAJAK_SPPT char(4) NOT NULL,JML_SPPT int(10) NOT NULL DEFAULT '0',JML_DISTRIBUTED int(10) NOT NULL DEFAULT '0',JML_UNDISTRIBUTED int(10) NOT NULL DEFAULT '0') ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
				$ddl_summary_table->execute();
				for ($i=2013;$i<=intval($maxtaxyear);$i++) {
					$stmt_insert_taxyear = $dbcon->prepare("INSERT INTO sppt_data_status(THN_PAJAK_SPPT) VALUES (:ITERATED)");
					$stmt_insert_taxyear->bindValue(":ITERATED", $i, PDO::PARAM_STR);
					$stmt_insert_taxyear->execute();
				}
				for ($i=2013;$i<=intval($maxtaxyear);$i++) {
					$stmt_update_count_sppt = $dbcon->prepare("
						UPDATE 
							sppt_data_status 
							SET JML_SPPT = (
														SELECT COUNT(*) AS JML_SPPT FROM sppt_data WHERE KD_PROPINSI = :KDPROPINSIA AND KD_DATI2 = :KDDATI2A AND KD_KECAMATAN = :KDKECAMATANA AND KD_KELURAHAN = :KDKELURAHANA AND THN_PAJAK_SPPT = :THNPAJAKSPPTA), 
								JML_DISTRIBUTED = (
														SELECT COUNT(*) AS JML_DISTRIBUTED FROM sppt_data WHERE KD_PROPINSI = :KDPROPINSIB AND KD_DATI2 = :KDDATI2B AND KD_KECAMATAN = :KDKECAMATANB AND KD_KELURAHAN = :KDKELURAHANB AND THN_PAJAK_SPPT = :THNPAJAKSPPTB AND PETUGASIDX != 0 AND NAMAPETUGAS != ''), 
								JML_UNDISTRIBUTED = (
														SELECT COUNT(*) AS JML_UNDISTRIBUTED FROM sppt_data WHERE KD_PROPINSI = :KDPROPINSIC AND KD_DATI2 = :KDDATI2C AND KD_KECAMATAN = :KDKECAMATANC AND KD_KELURAHAN = :KDKELURAHANC AND THN_PAJAK_SPPT = :THNPAJAKSPPTC AND PETUGASIDX = 0) 
						WHERE THN_PAJAK_SPPT = :ITERATED");
					$stmt_update_count_sppt->bindValue(":KDPROPINSIA", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
					$stmt_update_count_sppt->bindValue(":KDDATI2A", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
					$stmt_update_count_sppt->bindValue(":KDKECAMATANA", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
					$stmt_update_count_sppt->bindValue(":KDKELURAHANA", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
					$stmt_update_count_sppt->bindValue(":THNPAJAKSPPTA", $i, PDO::PARAM_STR);
					$stmt_update_count_sppt->bindValue(":KDPROPINSIB", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
					$stmt_update_count_sppt->bindValue(":KDDATI2B", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
					$stmt_update_count_sppt->bindValue(":KDKECAMATANB", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
					$stmt_update_count_sppt->bindValue(":KDKELURAHANB", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
					$stmt_update_count_sppt->bindValue(":THNPAJAKSPPTB", $i, PDO::PARAM_STR);
					$stmt_update_count_sppt->bindValue(":KDPROPINSIC", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
					$stmt_update_count_sppt->bindValue(":KDDATI2C", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
					$stmt_update_count_sppt->bindValue(":KDKECAMATANC", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
					$stmt_update_count_sppt->bindValue(":KDKELURAHANC", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
					$stmt_update_count_sppt->bindValue(":THNPAJAKSPPTC", $i, PDO::PARAM_STR);
					$stmt_update_count_sppt->bindValue(":ITERATED", $i, PDO::PARAM_STR);
					$stmt_update_count_sppt->execute();
				}
				/* select data */
				$stmt_select_data = $dbcon->prepare("SELECT * FROM sppt_data_status ORDER BY THN_PAJAK_SPPT");
				if ($stmt_select_data->execute()) {
					while($rowset_select_data = $stmt_select_data->fetch(PDO::FETCH_ASSOC)){
						$items[] = $rowset_select_data;
					}
					$response = array("ajaxresult"=>"found","dataarray"=>$items);
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				} else {
					$response = array("ajaxresult"=>"notfound","dataproblem"=>"nobuiltdata","ajaxmessage"=>"Data tidak ditemukan. Lakukan sinkronisasi data terlebih dahulu.");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
				
			} else {
				$response = array("ajaxresult"=>"notfound","dataproblem"=>"nobuiltdata","ajaxmessage"=>"Data tidak ditemukan. Lakukan sinkronisasi data terlebih dahulu.");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
			/* ================ */
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"notfound","dataproblem"=>"connectionerror","ajaxmessage"=>"".$e->getMessage()."");
			header('Content-Type: application/json');
			echo json_encode($response);
			$dbcon = null;
			exit;
		}
	} elseif ($_GET['cmdx'] == "CHECK_SYNC_PETUGAS_INDICATOR") {
		$currenttaxyear = $_GET['currenttaxyear'];
		$previoustaxyear = $_GET['previoustaxyear'];
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt_count_previoustaxyear_sppt = $dbcon->prepare("SELECT COUNT(*) AS foundrows FROM sppt_data WHERE KD_PROPINSI = :KDPROPINSI AND KD_DATI2 = :KDDATI2 AND KD_KECAMATAN = :KDKECAMATAN AND KD_KELURAHAN = :KDKELURAHAN AND THN_PAJAK_SPPT = :THNPAJAKSPPT");
			$stmt_count_previoustaxyear_sppt->bindValue(":KDPROPINSI", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt_count_previoustaxyear_sppt->bindValue(":KDDATI2", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt_count_previoustaxyear_sppt->bindValue(":KDKECAMATAN", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt_count_previoustaxyear_sppt->bindValue(":KDKELURAHAN", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			$stmt_count_previoustaxyear_sppt->bindValue(":THNPAJAKSPPT", $previoustaxyear, PDO::PARAM_STR);
			if ($stmt_count_previoustaxyear_sppt->execute()) {
				while($rowset_count_previoustaxyear_sppt = $stmt_count_previoustaxyear_sppt->fetch(PDO::FETCH_ASSOC)){
					$count_prev_sppt = $rowset_count_previoustaxyear_sppt['foundrows'];
				}
				if (intval($count_prev_sppt)==0) {
					$response = array("ajaxresult"=>"nodata","ajaxmessage"=>"Data SPPT acuan tidak ditemukan.");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				} else {
					$stmt_count_previoustaxyear_assigned = $dbcon->prepare("SELECT COUNT(*) AS foundrows FROM sppt_data WHERE KD_PROPINSI = :KDPROPINSI AND KD_DATI2 = :KDDATI2 AND KD_KECAMATAN = :KDKECAMATAN AND KD_KELURAHAN = :KDKELURAHAN AND THN_PAJAK_SPPT = :THNPAJAKSPPT AND PETUGASIDX != 0");
					$stmt_count_previoustaxyear_assigned->bindValue(":KDPROPINSI", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
					$stmt_count_previoustaxyear_assigned->bindValue(":KDDATI2", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
					$stmt_count_previoustaxyear_assigned->bindValue(":KDKECAMATAN", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
					$stmt_count_previoustaxyear_assigned->bindValue(":KDKELURAHAN", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
					$stmt_count_previoustaxyear_assigned->bindValue(":THNPAJAKSPPT", $previoustaxyear, PDO::PARAM_STR);
					if ($stmt_count_previoustaxyear_assigned->execute()) {
						while($rowset_count_previoustaxyear_assigned = $stmt_count_previoustaxyear_assigned->fetch(PDO::FETCH_ASSOC)){
							$count_prev_assigned_sppt = $rowset_count_previoustaxyear_assigned['foundrows'];
						}
						if (intval($count_prev_assigned_sppt)==0) {
							$response = array("ajaxresult"=>"nodata","ajaxmessage"=>"Data distribusi SPPT acuan tidak ditemukan.");
							header('Content-Type: application/json');
							echo json_encode($response);
							$dbcon = null;
							exit;
						} else {
							/* =================== */
							$stmt_count_currenttaxyear_sppt = $dbcon->prepare("SELECT COUNT(*) AS foundrows FROM sppt_data WHERE KD_PROPINSI = :KDPROPINSI AND KD_DATI2 = :KDDATI2 AND KD_KECAMATAN = :KDKECAMATAN AND KD_KELURAHAN = :KDKELURAHAN AND THN_PAJAK_SPPT = :THNPAJAKSPPT");
							$stmt_count_currenttaxyear_sppt->bindValue(":KDPROPINSI", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
							$stmt_count_currenttaxyear_sppt->bindValue(":KDDATI2", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
							$stmt_count_currenttaxyear_sppt->bindValue(":KDKECAMATAN", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
							$stmt_count_currenttaxyear_sppt->bindValue(":KDKELURAHAN", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
							$stmt_count_currenttaxyear_sppt->bindValue(":THNPAJAKSPPT", $currenttaxyear, PDO::PARAM_STR);
							if ($stmt_count_currenttaxyear_sppt->execute()) {
								while($rowset_count_currenttaxyear_sppt = $stmt_count_currenttaxyear_sppt->fetch(PDO::FETCH_ASSOC)){
									$count_current_sppt = $rowset_count_currenttaxyear_sppt['foundrows'];
								}
								if (intval($count_current_sppt)==0) {
									$response = array("ajaxresult"=>"nodata","ajaxmessage"=>"Data SPPT Tahun Pajak ".$currenttaxyear." tidak ditemukan.");
									header('Content-Type: application/json');
									echo json_encode($response);
									$dbcon = null;
									exit;
								} else {
									/* ########################################################### */
									/* STEP 1 : CLEAR ALL RELATED DATA */
									$stmt_clear_data = $dbcon->prepare("DELETE FROM sync_distribusi_petugas WHERE KD_PROPINSI = :KDPROPINSI AND KD_DATI2 = :KDDATI2 AND KD_KECAMATAN = :KDKECAMATAN AND KD_KELURAHAN = :KDKELURAHAN");
									$stmt_clear_data->bindValue(":KDPROPINSI", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
									$stmt_clear_data->bindValue(":KDDATI2", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
									$stmt_clear_data->bindValue(":KDKECAMATAN", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
									$stmt_clear_data->bindValue(":KDKELURAHAN", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
									$stmt_clear_data->execute();
									/* STEP 2 : OPTIMIZE TABLE */
									$stmt_optimise_table = $dbcon->query("OPTIMIZE TABLE sync_distribusi_petugas");
									/* STEP 3 : INSERT REFERENTIAL DATA */
									$stmt_insert_data = $dbcon->prepare("INSERT INTO sync_distribusi_petugas(KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,TGL_TERBIT_SPPT,PETUGASIDX,NAMAPETUGAS) SELECT KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,TGL_TERBIT_SPPT,PETUGASIDX,NAMAPETUGAS FROM sppt_data WHERE KD_PROPINSI = :KDPROPINSI AND KD_DATI2 = :KDDATI2 AND KD_KECAMATAN = :KDKECAMATAN AND KD_KELURAHAN = :KDKELURAHAN AND THN_PAJAK_SPPT = :THNPAJAKSPPT AND PETUGASIDX != 0 AND NAMAPETUGAS != '' ORDER BY KD_PROPINSI||KD_DATI2||KD_KECAMATAN||KD_KELURAHAN||KD_BLOK||NO_URUT||KD_JNS_OP");
									$stmt_insert_data->bindValue(":KDPROPINSI", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
									$stmt_insert_data->bindValue(":KDDATI2", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
									$stmt_insert_data->bindValue(":KDKECAMATAN", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
									$stmt_insert_data->bindValue(":KDKELURAHAN", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
									$stmt_insert_data->bindValue(":THNPAJAKSPPT", $previoustaxyear, PDO::PARAM_STR);
									$stmt_insert_data->execute();
									/* STEP 4 : SYNC DATA */
									$stmt_sync_data = $dbcon->prepare("
										UPDATE 
											sppt_data,
											sync_distribusi_petugas 
										SET 
											sppt_data.PETUGASIDX = sync_distribusi_petugas.PETUGASIDX, 
											sppt_data.NAMAPETUGAS = sync_distribusi_petugas.NAMAPETUGAS, 
											sppt_data.STATUS = 1 
										WHERE 
											sppt_data.KD_PROPINSI = sync_distribusi_petugas.KD_PROPINSI 
												AND sppt_data.KD_DATI2 = sync_distribusi_petugas.KD_DATI2 
												AND sppt_data.KD_KECAMATAN = sync_distribusi_petugas.KD_KECAMATAN 
												AND sppt_data.KD_KELURAHAN = sync_distribusi_petugas.KD_KELURAHAN 
												AND sppt_data.KD_BLOK = sync_distribusi_petugas.KD_BLOK 
												AND sppt_data.NO_URUT = sync_distribusi_petugas.NO_URUT 
												AND sppt_data.KD_JNS_OP = sync_distribusi_petugas.KD_JNS_OP 
												AND sync_distribusi_petugas.KD_PROPINSI = :KDPROPINSI 
												AND sync_distribusi_petugas.KD_DATI2 = :KDDATI2 
												AND sync_distribusi_petugas.KD_KECAMATAN = :KDKECAMATAN 
												AND sync_distribusi_petugas.KD_KELURAHAN = :KDKELURAHAN 
												AND sppt_data.THN_PAJAK_SPPT = :THNPAJAKSPPT");
									$stmt_sync_data->bindValue(":KDPROPINSI", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
									$stmt_sync_data->bindValue(":KDDATI2", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
									$stmt_sync_data->bindValue(":KDKECAMATAN", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
									$stmt_sync_data->bindValue(":KDKELURAHAN", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
									$stmt_sync_data->bindValue(":THNPAJAKSPPT", $currenttaxyear, PDO::PARAM_STR);
									if ($stmt_sync_data->execute()) {
										/* STEP 5 : CLEAR ALL RELATED DATA */
										$stmt_clear_data_final = $dbcon->prepare("DELETE FROM sync_distribusi_petugas WHERE KD_PROPINSI = :KDPROPINSI AND KD_DATI2 = :KDDATI2 AND KD_KECAMATAN = :KDKECAMATAN AND KD_KELURAHAN = :KDKELURAHAN");
										$stmt_clear_data_final->bindValue(":KDPROPINSI", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
										$stmt_clear_data_final->bindValue(":KDDATI2", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
										$stmt_clear_data_final->bindValue(":KDKECAMATAN", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
										$stmt_clear_data_final->bindValue(":KDKELURAHAN", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
										$stmt_clear_data_final->execute();
										/* STEP 6 : OPTIMIZE TABLE */
										$stmt_optimise_table_final = $dbcon->query("OPTIMIZE TABLE sync_distribusi_petugas");
										$response = array("ajaxresult"=>"synchronized","ajaxmessage"=>"Data distribusi SPPT ke petugas pemungut berhasil dilaksanakan.");
										header('Content-Type: application/json');
										echo json_encode($response);
										$dbcon = null;
										exit;
									} else {
										/* STEP 5_failsafe : CLEAR ALL RELATED DATA */
										$stmt_clear_data_final = $dbcon->prepare("DELETE FROM sync_distribusi_petugas WHERE KD_PROPINSI = :KDPROPINSI AND KD_DATI2 = :KDDATI2 AND KD_KECAMATAN = :KDKECAMATAN AND KD_KELURAHAN = :KDKELURAHAN");
										$stmt_clear_data_final->bindValue(":KDPROPINSI", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
										$stmt_clear_data_final->bindValue(":KDDATI2", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
										$stmt_clear_data_final->bindValue(":KDKECAMATAN", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
										$stmt_clear_data_final->bindValue(":KDKELURAHAN", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
										$stmt_clear_data_final->execute();
										/* STEP 6_failsafe : OPTIMIZE TABLE */
										$stmt_optimise_table_final = $dbcon->query("OPTIMIZE TABLE sync_distribusi_petugas");
										$response = array("ajaxresult"=>"failtosync","ajaxmessage"=>"Sinkronisasi data distribusi SPPT ke petugas pemungut gagal.");
										header('Content-Type: application/json');
										echo json_encode($response);
										$dbcon = null;
										exit;
									}
									/* ########################################################### */
								}
							} else {
								$response = array("ajaxresult"=>"queryerror","ajaxmessage"=>"Query error. Hubungi Administrator Sistem BPPKAD.");
								header('Content-Type: application/json');
								echo json_encode($response);
								$dbcon = null;
								exit;
							}
							/* =================== */
						}
					} else {
						$response = array("ajaxresult"=>"queryerror","ajaxmessage"=>"Query error. Hubungi Administrator Sistem BPPKAD.");
						header('Content-Type: application/json');
						echo json_encode($response);
						$dbcon = null;
						exit;
					}
				}
			} else {
				$response = array("ajaxresult"=>"queryerror","ajaxmessage"=>"Query error. Hubungi Administrator Sistem BPPKAD.");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"connectionerror","ajaxmessage"=>"".$e->getMessage()."");
			header('Content-Type: application/json');
			echo json_encode($response);
			$dbcon = null;
			exit;
		}
	} elseif ($_GET['cmdx'] == "GET_TAHUN_PAJAK_SPPT_LIMITED") {
		$mintaxyear = "2013";
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("SELECT thnpajak FROM tahun_pajak_sismiop WHERE status = 1 AND thnpajak >= :mintaxyear ORDER BY thnpajak");
			$stmt->bindValue(":mintaxyear", $mintaxyear, PDO::PARAM_STR);
			if($stmt->execute()){
				while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
					$items[] = $rowset;
				}
				$response = array("response"=>"200","dataarray"=>$items);
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			} else {
				$response = array("response"=>"500","message"=>"".$e->getMessage()."");
				header('Content-Type: application/json');
				echo json_encode($response);
				$dbcon = null;
				exit;
			}
		} catch (PDOException $e) {
			$response = array("response"=>"500","message"=>"".$e->getMessage()."");
			header('Content-Type: application/json');
			echo json_encode($response);
			$dbcon = null;
			exit;
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
	