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
	if ($_GET['cmdx'] == "TMBH_PETUGAS_PENAGIHAN") {
		$NAMA_PETUGAS = $_GET['NAMA_PETUGAS'];
		$WILAYAH = $_GET['WILAYAH'];
		$JABATAN = $_GET['JABATAN'];
		$KD_PROPINSI = $_SESSION['kecamatanXweb']['KD_PROPINSI'];
		$KD_DATI2 = $_SESSION['kecamatanXweb']['KD_DATI2']; 
		$KD_KECAMATAN = $_SESSION['kecamatanXweb']['KD_KECAMATAN'];
		$KD_KELURAHAN = $_SESSION['kecamatanXweb']['KD_KELURAHAN'];
		$NM_KECAMATAN = $_SESSION['kecamatanXweb']['NM_KECAMATAN'];
		$NM_KELURAHAN = $_SESSION['kecamatanXweb']['NM_KELURAHAN'];
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
		$KD_PROPINSI = $_SESSION['kecamatanXweb']['KD_PROPINSI'];
		$KD_DATI2 = $_SESSION['kecamatanXweb']['KD_DATI2']; 
		$KD_KECAMATAN = $_SESSION['kecamatanXweb']['KD_KECAMATAN'];
		$KD_KELURAHAN = $_SESSION['kecamatanXweb']['KD_KELURAHAN'];
		$NM_KECAMATAN = $_SESSION['kecamatanXweb']['NM_KECAMATAN'];
		$NM_KELURAHAN = $_SESSION['kecamatanXweb']['NM_KELURAHAN'];
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
		$KD_PROPINSI = $_SESSION['kecamatanXweb']['KD_PROPINSI'];
		$KD_DATI2 = $_SESSION['kecamatanXweb']['KD_DATI2']; 
		$KD_KECAMATAN = $_SESSION['kecamatanXweb']['KD_KECAMATAN'];
		$KD_KELURAHAN = $_SESSION['kecamatanXweb']['KD_KELURAHAN'];
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
		$KD_PROPINSI = $_SESSION['kecamatanXweb']['KD_PROPINSI'];
		$KD_DATI2 = $_SESSION['kecamatanXweb']['KD_DATI2']; 
		$KD_KECAMATAN = $_SESSION['kecamatanXweb']['KD_KECAMATAN'];
		$KD_KELURAHAN = $_SESSION['kecamatanXweb']['KD_KELURAHAN'];
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
		$first_passwd = sha1($_GET['newpassword']); $conf_passwd = sha1($_GET['confirmpassword']);
		if ($first_passwd == $conf_passwd) {
			try {
				/* connection setting */
				$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $dbcon->prepare("UPDATE appx_desa_users SET WORDPASS = :new_password WHERE KD_PROPINSI = :KDPROP AND KD_DATI2 = :KDKAB AND KD_KECAMATAN = :KDKEC AND KD_KELURAHAN = :KDKEL");
				$stmt->bindValue(":new_password", $first_passwd, PDO::PARAM_STR);
				$stmt->bindValue(":KDPROP", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
				$stmt->bindValue(":KDKAB", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
				$stmt->bindValue(":KDKEC", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
				$stmt->bindValue(":KDKEL", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
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
		$kode_propinsi = $_SESSION['kecamatanXweb']['KD_PROPINSI'];
		$kode_dati2 = $_SESSION['kecamatanXweb']['KD_DATI2'];
		$kode_kecamatan = $_SESSION['kecamatanXweb']['KD_KECAMATAN'];
		$kode_kelurahan = $_SESSION['kecamatanXweb']['KD_KELURAHAN'];
		$namaKecamatan = $_SESSION['kecamatanXweb']['NM_KECAMATAN'];
		$namaKelurahan = $_SESSION['kecamatanXweb']['NM_KELURAHAN'];
		$thnsppt = $_SESSION['tahunPAJAK'];
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
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			/* counting data */
			$stmt_cnt = $dbcon->prepare("SELECT COUNT(*) as foundrows FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak");
			$stmt_cnt->bindValue(":kdprov", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kdkab", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kdkec", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kddesa", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":thnpajak", $_SESSION['tahunPAJAK'], PDO::PARAM_STR);
			if ($stmt_cnt->execute()) {
				while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
					$rowsfound = $rowset_cnt['foundrows'];
				}
				if (intval($rowsfound)>0) {
					$stmt = $dbcon->prepare("SELECT COUNT(*) AS totalcountsppt, COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprova AND KD_DATI2 = :kdkaba AND KD_KECAMATAN = :kdkeca AND KD_KELURAHAN = :kddesaa AND THN_PAJAK_SPPT = :thnpajaka) AS countallpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovb AND KD_DATI2 = :kdkabb AND KD_KECAMATAN = :kdkecb AND KD_KELURAHAN = :kddesab AND THN_PAJAK_SPPT = :thnpajakb AND TGL_TERBIT_SPPT = '2017-01-02') AS countdefaultpenetapan, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovc AND KD_DATI2 = :kdkabc AND KD_KECAMATAN = :kdkecc AND KD_KELURAHAN = :kddesac AND THN_PAJAK_SPPT = :thnpajakc AND TGL_TERBIT_SPPT = '2017-01-02') AS sumdefaultpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovd AND KD_DATI2 = :kdkabd AND KD_KECAMATAN = :kdkecd AND KD_KELURAHAN = :kddesad AND THN_PAJAK_SPPT = :thnpajakd AND TGL_TERBIT_SPPT != '2017-01-02') AS countlaterpenetapan, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprove AND KD_DATI2 = :kdkabe AND KD_KECAMATAN = :kdkece AND KD_KELURAHAN = :kddesae AND THN_PAJAK_SPPT = :thnpajake AND TGL_TERBIT_SPPT != '2017-01-02') AS sumlaterpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovf AND KD_DATI2 = :kdkabf AND KD_KECAMATAN = :kdkecf AND KD_KELURAHAN = :kddesaf AND THN_PAJAK_SPPT = :thnpajakf AND PETUGASIDX = 0) AS countunassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovg AND KD_DATI2 = :kdkabg AND KD_KECAMATAN = :kdkecg AND KD_KELURAHAN = :kddesag AND THN_PAJAK_SPPT = :thnpajakg AND PETUGASIDX = 0) AS sumunassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovh AND KD_DATI2 = :kdkabh AND KD_KECAMATAN = :kdkech AND KD_KELURAHAN = :kddesah AND THN_PAJAK_SPPT = :thnpajakh AND PETUGASIDX > 0) AS countassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovi AND KD_DATI2 = :kdkabi AND KD_KECAMATAN = :kdkeci AND KD_KELURAHAN = :kddesai AND THN_PAJAK_SPPT = :thnpajaki AND PETUGASIDX > 0) AS sumassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovj AND KD_DATI2 = :kdkabj AND KD_KECAMATAN = :kdkecj AND KD_KELURAHAN = :kddesaj AND THN_PAJAK_SPPT = :thnpajakj AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS countunpaiddesa, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovk AND KD_DATI2 = :kdkabk AND KD_KECAMATAN = :kdkeck AND KD_KELURAHAN = :kddesak AND THN_PAJAK_SPPT = :thnpajakk AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS sumunpaiddesa, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovl AND KD_DATI2 = :kdkabl AND KD_KECAMATAN = :kdkecl AND KD_KELURAHAN = :kddesal AND THN_PAJAK_SPPT = :thnpajakl AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS countpaiddesa, (SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovm AND KD_DATI2 = :kdkabm AND KD_KECAMATAN = :kdkecm AND KD_KELURAHAN = :kddesam AND THN_PAJAK_SPPT = :thnpajakm AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS sumpaiddesa, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovn AND KD_DATI2 = :kdkabn AND KD_KECAMATAN = :kdkecn AND KD_KELURAHAN = :kddesan AND THN_PAJAK_SPPT = :thnpajakn AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX > 0) AS countpaidassigned, (SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovo AND KD_DATI2 = :kdkabo AND KD_KECAMATAN = :kdkeco AND KD_KELURAHAN = :kddesao AND THN_PAJAK_SPPT = :thnpajako AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX > 0) AS sumpaidassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovp AND KD_DATI2 = :kdkabp AND KD_KECAMATAN = :kdkecp AND KD_KELURAHAN = :kddesap AND THN_PAJAK_SPPT = :thnpajakp AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX > 0) AS countunpaidassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovq AND KD_DATI2 = :kdkabq AND KD_KECAMATAN = :kdkecq AND KD_KELURAHAN = :kddesaq AND THN_PAJAK_SPPT = :thnpajakq AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX > 0) AS sumunpaidassigned FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak");
					$stmt->bindValue(":kdprova", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR); $stmt->bindValue(":kdkaba", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR); $stmt->bindValue(":kdkeca", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR); $stmt->bindValue(":kddesaa", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR); $stmt->bindValue(":thnpajaka", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); $stmt->bindValue(":kdprovb", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR); $stmt->bindValue(":kdkabb", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR); $stmt->bindValue(":kdkecb", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR); $stmt->bindValue(":kddesab", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR); $stmt->bindValue(":thnpajakb", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); $stmt->bindValue(":kdprovc", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR); $stmt->bindValue(":kdkabc", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR); $stmt->bindValue(":kdkecc", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR); $stmt->bindValue(":kddesac", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR); $stmt->bindValue(":thnpajakc", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); $stmt->bindValue(":kdprovd", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR); $stmt->bindValue(":kdkabd", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR); $stmt->bindValue(":kdkecd", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR); $stmt->bindValue(":kddesad", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR); $stmt->bindValue(":thnpajakd", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); $stmt->bindValue(":kdprove", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR); $stmt->bindValue(":kdkabe", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR); $stmt->bindValue(":kdkece", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR); $stmt->bindValue(":kddesae", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR); $stmt->bindValue(":thnpajake", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); $stmt->bindValue(":kdprovf", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR); $stmt->bindValue(":kdkabf", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR); $stmt->bindValue(":kdkecf", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR); $stmt->bindValue(":kddesaf", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR); $stmt->bindValue(":thnpajakf", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); $stmt->bindValue(":kdprovg", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR); $stmt->bindValue(":kdkabg", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR); $stmt->bindValue(":kdkecg", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR); $stmt->bindValue(":kddesag", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR); $stmt->bindValue(":thnpajakg", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); $stmt->bindValue(":kdprovh", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR); $stmt->bindValue(":kdkabh", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR); $stmt->bindValue(":kdkech", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR); $stmt->bindValue(":kddesah", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR); $stmt->bindValue(":thnpajakh", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); $stmt->bindValue(":kdprovi", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR); $stmt->bindValue(":kdkabi", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR); $stmt->bindValue(":kdkeci", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR); $stmt->bindValue(":kddesai", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR); $stmt->bindValue(":thnpajaki", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); $stmt->bindValue(":kdprovj", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR); $stmt->bindValue(":kdkabj", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR); $stmt->bindValue(":kdkecj", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR); $stmt->bindValue(":kddesaj", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR); $stmt->bindValue(":thnpajakj", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); $stmt->bindValue(":kdprovk", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR); $stmt->bindValue(":kdkabk", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR); $stmt->bindValue(":kdkeck", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR); $stmt->bindValue(":kddesak", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR); $stmt->bindValue(":thnpajakk", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); $stmt->bindValue(":kdprovl", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR); $stmt->bindValue(":kdkabl", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR); $stmt->bindValue(":kdkecl", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR); $stmt->bindValue(":kddesal", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR); $stmt->bindValue(":thnpajakl", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); $stmt->bindValue(":kdprovm", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR); $stmt->bindValue(":kdkabm", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR); $stmt->bindValue(":kdkecm", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR); $stmt->bindValue(":kddesam", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR); $stmt->bindValue(":thnpajakm", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); $stmt->bindValue(":kdprovn", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR); $stmt->bindValue(":kdkabn", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR); $stmt->bindValue(":kdkecn", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR); $stmt->bindValue(":kddesan", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR); $stmt->bindValue(":thnpajakn", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); $stmt->bindValue(":kdprovo", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR); $stmt->bindValue(":kdkabo", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR); $stmt->bindValue(":kdkeco", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR); $stmt->bindValue(":kddesao", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR); $stmt->bindValue(":thnpajako", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); $stmt->bindValue(":kdprovp", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR); $stmt->bindValue(":kdkabp", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR); $stmt->bindValue(":kdkecp", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR); $stmt->bindValue(":kddesap", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR); $stmt->bindValue(":thnpajakp", $_SESSION['tahunPAJAK'], PDO::PARAM_STR); $stmt->bindValue(":kdprovq", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR); $stmt->bindValue(":kdkabq", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR); $stmt->bindValue(":kdkecq", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR); $stmt->bindValue(":kddesaq", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR); $stmt->bindValue(":thnpajakq", $_SESSION['tahunPAJAK'], PDO::PARAM_STR);
					$stmt->bindValue(":kdprov", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
					$stmt->bindValue(":kdkab", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
					$stmt->bindValue(":kdkec", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
					$stmt->bindValue(":kddesa", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
					$stmt->bindValue(":thnpajak", $_SESSION['tahunPAJAK'], PDO::PARAM_STR);
					$stmt->execute();
					while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
						$vtotalcountsppt = $rowset['totalcountsppt']; $vtotalsumsppt = $rowset['totalsumsppt']; $vcountallpenetapan = $rowset['countallpenetapan'];
						$vcountdefaultpenetapan = $rowset['countdefaultpenetapan']; $vsumdefaultpenetapan = $rowset['sumdefaultpenetapan'];
						$vcountlaterpenetapan = $rowset['countlaterpenetapan']; $vsumlaterpenetapan = $rowset['sumlaterpenetapan'];
						$vcountunassigned = $rowset['countunassigned']; $vsumunassigned = $rowset['sumunassigned'];
						$vcountassigned = $rowset['countassigned']; $vsumassigned = $rowset['sumassigned'];
						$vcountunpaiddesa = $rowset['countunpaiddesa']; $vsumunpaiddesa = $rowset['sumunpaiddesa'];
						$vcountpaiddesa = $rowset['countpaiddesa']; $vsumpaiddesa = $rowset['sumpaiddesa'];
						$vcountunpaidassigned = $rowset['countunpaidassigned']; $vsumunpaidassigned = $rowset['sumunpaidassigned'];
						$vcountpaidassigned = $rowset['countpaidassigned']; $vsumpaidassigned = $rowset['sumpaidassigned'];
					}
					/* final response */
					$response = array("ajaxresult"=>"found","kodesppt"=>strval($_SESSION['kecamatanXweb']['KD_PROPINSI'].'.'.$_SESSION['kecamatanXweb']['KD_DATI2'].'.'.$_SESSION['kecamatanXweb']['KD_KECAMATAN'].'.'.$_SESSION['kecamatanXweb']['KD_KELURAHAN'].''),"namakecamatan"=>$_SESSION['kecamatanXweb']['NM_KECAMATAN'],"namadesa"=>$_SESSION['kecamatanXweb']['NM_KELURAHAN'],"tahunpajak"=>$_SESSION['tahunPAJAK'],"jumlahpersonil"=>$countpersonil,"totalcountsppt"=>$vtotalcountsppt,"totalsumsppt"=>$vtotalsumsppt,"countallpenetapan"=>$vcountallpenetapan,"countdefaultpenetapan"=>$vcountdefaultpenetapan,"sumdefaultpenetapan"=>$vsumdefaultpenetapan,"countlaterpenetapan"=>$vcountlaterpenetapan,"sumlaterpenetapan"=>$vsumlaterpenetapan,"countunassigned"=>$vcountunassigned,"sumunassigned"=>$vsumunassigned,"countassigned"=>$vcountassigned,"sumassigned"=>$vsumassigned,"countunpaiddesa"=>$vcountunpaiddesa,"sumunpaiddesa"=>$vsumunpaiddesa,"countpaiddesa"=>$vcountpaiddesa,"sumpaiddesa"=>$vsumpaiddesa,"countunpaidassigned"=>$vcountunpaidassigned,"sumunpaidassigned"=>$vsumunpaidassigned,"countpaidassigned"=>$vcountpaidassigned,"sumpaidassigned"=>$vsumpaidassigned);
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
			$stmt_cnt->bindValue(":kdprov", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kdkab", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kdkec", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kddesa", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			if ($stmt_cnt->execute()) {
				while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
					$rowsfound = $rowset_cnt['foundrows'];
				}
				if (intval($rowsfound)>0) {
					$stmt = $dbcon->prepare("SELECT * FROM appx_desa_petugas_pungut WHERE KD_PROPINSI = :kdprop AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa ORDER BY NAMA_PETUGAS");
					$stmt->bindValue(":kdprov", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
					$stmt->bindValue(":kdkab", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
					$stmt->bindValue(":kdkec", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
					$stmt->bindValue(":kddesa", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
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
			$stmt_cnt->bindValue(":kdprov", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kdkab", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kdkec", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kddesa", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			if ($stmt_cnt->execute()) {
				while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
					$rowsfound = $rowset_cnt['foundrows'];
				}
				if (intval($rowsfound)>0) {
					$stmt = $dbcon->prepare("SELECT * FROM appx_desa_petugas_pungut WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND STATUS = 1 ORDER BY NAMA_PETUGAS");
					$stmt->bindValue(":kdprov", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
					$stmt->bindValue(":kdkab", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
					$stmt->bindValue(":kdkec", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
					$stmt->bindValue(":kddesa", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
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
			$stmt_cnt->bindValue(":kdprov", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kdkab", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kdkec", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kddesa", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":thnpajak", $_SESSION['tahunPAJAK'], PDO::PARAM_STR);
			if ($stmt_cnt->execute()) {
				while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
					$rowsfound = $rowset_cnt['foundrows'];
				}
				if (intval($rowsfound)>0) {
					$stmt = $dbcon->prepare("SELECT DISTINCT(KD_BLOK) AS KD_BLOK, KD_PROPINSI, KD_DATI2, KD_KECAMATAN, KD_KELURAHAN FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak GROUP BY KD_BLOK");
					$stmt->bindValue(":kdprov", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
					$stmt->bindValue(":kdkab", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
					$stmt->bindValue(":kdkec", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
					$stmt->bindValue(":kddesa", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
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
	} elseif ($_GET['cmdx'] == "GET_LIST_SPPT_DESA_BY_BLOK") {
		$KD_BLOK = $_GET['kodexblok'];
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			/* counting data */
			$stmt_cnt = $dbcon->prepare("SELECT COUNT(*) as foundrows FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND KD_BLOK = :kdblok AND THN_PAJAK_SPPT = :thnpajak");
			$stmt_cnt->bindValue(":kdprov", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kdkab", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kdkec", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kddesa", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			$stmt_cnt->bindValue(":kdblok", $KD_BLOK, PDO::PARAM_STR);
			$stmt_cnt->bindValue(":thnpajak", $_SESSION['tahunPAJAK'], PDO::PARAM_STR);
			if ($stmt_cnt->execute()) {
				while($rowset_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC)){
					$rowsfound = $rowset_cnt['foundrows'];
				}
				if (intval($rowsfound)>0) {
					$stmt = $dbcon->prepare("SELECT * FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND KD_BLOK = :kdblok AND THN_PAJAK_SPPT = :thnpajak ORDER BY NO_URUT");
					$stmt->bindValue(":kdprov", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
					$stmt->bindValue(":kdkab", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
					$stmt->bindValue(":kdkec", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
					$stmt->bindValue(":kddesa", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
					$stmt->bindValue(":kdblok", $KD_BLOK, PDO::PARAM_STR);
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
			$stmt->bindValue(":kdprov", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt->bindValue(":kdkab", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt->bindValue(":kdkec", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt->bindValue(":kddesa", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
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
			$stmt->bindValue(":kdprov", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt->bindValue(":kdkab", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt->bindValue(":kdkec", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt->bindValue(":kddesa", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
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
	} elseif ($_GET['cmdx'] == "ASSIGN_BLOK_TO_PETUGAS") {
		$petugasidx = $_GET['idxpetugas'];
		$petugasnama = $_GET['namapetugas'];
		$KD_BLOK = $_GET['kodexblok'];
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			/* counting data */
			$stmt = $dbcon->prepare("UPDATE sppt_data SET PETUGASIDX = :idpetugas, NAMAPETUGAS = :nmpetugas, STATUS = 1 WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND KD_BLOK = :kdblok AND THN_PAJAK_SPPT = :thnpajak");
			$stmt->bindValue(":kdprov", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt->bindValue(":kdkab", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt->bindValue(":kdkec", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt->bindValue(":kddesa", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			$stmt->bindValue(":kdblok", $KD_BLOK, PDO::PARAM_STR);
			$stmt->bindValue(":thnpajak", $_SESSION['tahunPAJAK'], PDO::PARAM_STR);
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
			$stmt->bindValue(":kdprov", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt->bindValue(":kdkab", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt->bindValue(":kdkec", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt->bindValue(":kddesa", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
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
	} elseif ($_GET['cmdx'] == "PREVIEW_TEMPORARY_BANKENZAHLEN") {
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			/* counting data */
			$stmt = $dbcon->prepare("SELECT * FROM temp_sppt_data WHERE IDX LIKE CONCAT('%',:kdprov,'|',:kdkab,'|',:kdkec,'|',:kddesa,'|%')");
			$stmt->bindValue(":kdprov", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt->bindValue(":kdkab", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt->bindValue(":kdkec", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
			$stmt->bindValue(":kddesa", $_SESSION['kecamatanXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
			if ($stmt->execute()) {
				while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
					$items[] = $rowset;
				}
				$array_counting = array_filter($items);
				if (!empty($array_counting)) {
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
				$response = array("ajaxresult"=>"notremoved");
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
	} elseif ($_GET['cmdx'] == "GET_CURRENT_KECAMATAN_INFO") {
		if (isset($_SESSION['kecamatanXweb'])) {
			$response = array(
				"ajaxresult"=>"is_set",
				"kode_prop"=>$_SESSION['kecamatanXweb']['KD_PROPINSI'],
				"kode_kab"=>$_SESSION['kecamatanXweb']['KD_DATI2'],
				"kode_kec"=>$_SESSION['kecamatanXweb']['KD_KECAMATAN'],
				"nama_kec"=>$_SESSION['kecamatanXweb']['NM_KECAMATAN'],
				"user_password"=>$_SESSION['kecamatanXweb']['WORDPASS'],
				"user_status"=>$_SESSION['kecamatanXweb']['STATUS']);
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		} else {
			$response = array(
				"ajaxresult"=>"not_set",
				"kode_prop"=>"00",
				"kode_kab"=>"00",
				"kode_kec"=>"00",
				"nama_kec"=>"undefined",
				"user_password"=>"undefined",
				"user_status"=>"0");
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		}
	} elseif ($_GET['cmdx'] == "CHANGE_PASSWORD_KECAMATAN") {
		$first_passwd = sha1($_GET['newpassword']); $conf_passwd = sha1($_GET['confirmpassword']);
		if ($first_passwd == $conf_passwd) {
			try {
				/* connection setting */
				$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $dbcon->prepare("UPDATE appx_kecamatan_users SET WORDPASS = :new_password WHERE KD_PROPINSI = :KDPROP AND KD_DATI2 = :KDKAB AND KD_KECAMATAN = :KDKEC");
				$stmt->bindValue(":new_password", $first_passwd, PDO::PARAM_STR);
				$stmt->bindValue(":KDPROP", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
				$stmt->bindValue(":KDKAB", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
				$stmt->bindValue(":KDKEC", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
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
	} elseif ($_GET['cmdx'] == "GET_DESA") {
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("SELECT * FROM appx_desa_users WHERE KD_PROPINSI = :KDPROP AND KD_DATI2 = :KDKAB AND KD_KECAMATAN = :KDKEC ORDER BY NM_KELURAHAN");
			$stmt->bindValue(":KDPROP", $_SESSION['kecamatanXweb']['KD_PROPINSI'], PDO::PARAM_STR);
			$stmt->bindValue(":KDKAB", $_SESSION['kecamatanXweb']['KD_DATI2'], PDO::PARAM_STR);
			$stmt->bindValue(":KDKEC", $_SESSION['kecamatanXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
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
		
	}
} else {
	/* IF cmdx is not defined */
	$response = array("ajaxresult"=>"undefined");
	header('Content-Type: application/json');
	echo json_encode($response);
}
?>