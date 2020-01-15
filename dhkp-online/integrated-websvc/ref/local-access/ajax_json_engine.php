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
	} elseif ($_GET['cmdx'] == "GET_DATA_REGISTERED_WAJIB_PAJAK") {
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("SELECT idx,npwpd,origin,nama,alamat,kategori,status FROM appx_wp_base WHERE status = 1 ORDER BY npwpd,nama");
			if($stmt->execute()){
				while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
					$items[] = $rowset;
				}
				if (empty($items)) {
					$dbcon = null;
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
					exit;
				} else {
					$response = array("ajaxresult"=>"found","dataarray"=>$items);
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
			} else {
				$dbcon = null;
				$response = array("ajaxresult"=>"notfound");
				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"notfound");
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		}
	} elseif ($_GET['cmdx'] == "SIG_MAPATDA_LOGIN_ATTEMPT") {
		$namexuser = $_GET['username']; $password = sha1($_GET['password']);
		if (is_numeric($namexuser)) {
			
		} else {
			try {
				$dbcon = new PDO("pgsql:host=".$appxinfo['_sig_db_host_'].";port=".$appxinfo['_sig_db_port_'].";dbname=".$appxinfo['_sig_db_name_'].";user=".$appxinfo['_sig_db_user_'].";password=".$appxinfo['_sig_db_pass_']."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt_count_existence = $dbcon->prepare("SELECT COUNT(idx) AS counted, idx FROM appx_sig_users WHERE username LIKE :username AND wordpass LIKE :password AND status = 1 GROUP BY idx");
				$stmt_count_existence->bindValue(":username", $namexuser, PDO::PARAM_STR);
				$stmt_count_existence->bindValue(":password", $password, PDO::PARAM_STR);
				if ($stmt_count_existence->execute()) {
					if ($stmt_count_existence->rowCount() > 0) {
						while($rowset_count_existence = $stmt_count_existence->fetch(PDO::FETCH_ASSOC)){
							if (intval($rowset_count_existence['counted']) == 0) {
								$dbcon = null;
								$response = array("ajaxresult"=>"failed","ajaxmessage"=>"Username/password tidak terdaftar.");
								header('Content-Type: application/json');
								echo json_encode($response);
							} elseif (intval($rowset_count_existence['counted']) == 1) {
								$user_idx = $rowset_count_existence['idx'];
								$stmt_select_user = $dbcon->prepare("SELECT * FROM appx_sig_users WHERE idx = :useridx");
								$stmt_select_user->bindValue(":useridx", $user_idx, PDO::PARAM_INT);
								if ($stmt_select_user->execute()) {
									while($rowset_select_user = $stmt_select_user->fetch(PDO::FETCH_ASSOC)){
										$items = $rowset_select_user;
										$usermodule = $rowset_select_user['module'];
									}
									$_SESSION['appxINFO'] = $appxinfo;
									$_SESSION['MapatdaWebSIGXin'] = $items;
									$dbcon = null;
									$response = array("ajaxresult"=>"found","module"=>$usermodule);
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
		}
	} elseif ($_GET['cmdx'] == "SAVE_NEW_PENDUDUK_PD") {
		/* var infobasic = "0"+nikcwp+"|1"+namapd+"|2"+tmplahirpd+"|3"+tgllahirpd+"|4"+genderpd+"|5"+goldarahpd+"|6"+alamatpd+"|7"+rwpd+"|8"+rtpd+"|9"+agamapd+"|10"+statuskwnpd+"|11"+pekerjaanpd+""; */
		$strinfo = $_GET['basicinfo']; $modenpwpd = $_GET['refnpwpd']; $strkecamatan = $_GET['pdkecamatan']; $strdesa = $_GET['pddesa'];
		$arrinfo = explode("|",$strinfo); $arrKecIDX = explode("|",$strkecamatan); $arrDesaIDX = explode("|",$strdesa);
		//$strurl = "cmdx=SAVE_NEW_PENDUDUK_PD&refnpwpd=".$modenpwpd."&basicinfo=".$strinfo."&pdkecamatan=".$strkecamatan."&pddesa=".$strdesa."";
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt_search_exist = $dbcon->prepare("SELECT COUNT(nik) AS foundrows FROM view_data_penduduk_pd WHERE nik = :cwpnik");
			$stmt_search_exist->bindValue(":cwpnik", $arrinfo[0], PDO::PARAM_STR);
			if($stmt_search_exist->execute()){
				while($rowset_search_exist = $stmt_search_exist->fetch(PDO::FETCH_ASSOC)){
					$rows = $rowset_search_exist['foundrows'];
				}
				if (intval($rows)==0) {
					$stmt_insert_A = $dbcon->prepare("INSERT INTO appx_master_data_penduduk(nik,kategori,npwp,npwpd,nama,jk,tempat,tgllahir,goldarah,agama,statuskawin,statushub,statushubrt,pendidikan,pekerjaan,namaibu,namaayah,nokk,namakk,alamat,kode_pos,kode_prop,nama_prop,kode_kab,nama_kab,kode_kec,nama_kec,kode_kel,nama_kel,rw,rt) VALUES (:cwpnik,'PD','','',UCASE(:cwpnama),UCASE(:cwpjk),UCASE(:cwptempat),:cwptgllahir,:cwpgoldarah,UCASE(:cwpagama),UCASE(:cwpstatuskawin),'','0','',UCASE(:cwppekerjaan),'','','','',UCASE(:cwpalamat),'','33','JAWA TENGAH','23','TEMANGGUNG',:cwpkodekec,UCASE(:cwpnamakec),:cwpkodekel,UCASE(:cwpnamakel),:cwprw,:cwprt)");
					$stmt_insert_A->bindValue(":cwpnik", $arrinfo[0], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpnama", $arrinfo[1], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpjk", $arrinfo[4], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwptempat", $arrinfo[2], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwptgllahir", $arrinfo[3], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpgoldarah", $arrinfo[5], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpagama", $arrinfo[9], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpstatuskawin", $arrinfo[10], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwppekerjaan", $arrinfo[11], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpalamat", $arrinfo[6], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpkodekec", $arrKecIDX[7], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpnamakec", $arrKecIDX[9], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpkodekel", $arrDesaIDX[10], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpnamakel", $arrDesaIDX[13], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwprw", $arrinfo[7], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwprt", $arrinfo[8], PDO::PARAM_STR);
					if ($stmt_insert_A->execute()) {
						if ($modenpwpd == "NIK") { /* NIK sebagai NPWPD */
							$stmt_wp_perorangan = $dbcon->prepare("INSERT INTO appx_wp_perorangan(nik,kategori,npwp,npwpd,nama,alamat,status) VALUES (:xwpnik,'PD','',:xwpnpwpd,UCASE(:xwpnama),UCASE(:xwpalamat),1)");
							$stmt_wp_perorangan->bindValue(":xwpnik", $arrinfo[0], PDO::PARAM_STR);
							$stmt_wp_perorangan->bindValue(":xwpnpwpd", $arrinfo[0], PDO::PARAM_STR);
							$stmt_wp_perorangan->bindValue(":xwpnama", $arrinfo[1], PDO::PARAM_STR);
							$stmt_wp_perorangan->bindValue(":xwpalamat", $arrinfo[6], PDO::PARAM_STR);
							if ($stmt_wp_perorangan->execute()) { /* insert ke appx_wp_base */
								$stmt_wp_base = $dbcon->prepare("INSERT INTO appx_wp_base(kategori,origin,npwpd,nama,alamat,status) VALUES ('PD','NIK',:zwpnpwpd,UCASE(:zwpnama),UCASE(:zwpalamat),1)");
								$stmt_wp_base->bindValue(":zwpnpwpd", $arrinfo[0], PDO::PARAM_STR);
								$stmt_wp_base->bindValue(":zwpnama", $arrinfo[1], PDO::PARAM_STR);
								$stmt_wp_base->bindValue(":zwpalamat", $arrinfo[6], PDO::PARAM_STR);
								if ($stmt_wp_base->execute()) {
									$stmt_update_penduduk = $dbcon->prepare("UPDATE appx_master_data_penduduk SET npwpd = nik WHERE nik = :ywpnik");
									$stmt_update_penduduk->bindValue(":ywpnik", $arrinfo[0], PDO::PARAM_STR);
									if ($stmt_update_penduduk->execute()) {
										$response = array("ajaxresult"=>"saved");
										header('Content-Type: application/json');
										echo json_encode($response);
										$dbcon = null;
										exit;
									} else {
										$response = array("ajaxresult"=>"uncomplete","ajaxmessage"=>"Update data NIK sebagai NPWPD gagal.");
										header('Content-Type: application/json');
										echo json_encode($response);
										$dbcon = null;
										exit;
									}
								} else {
									$response = array("ajaxresult"=>"uncomplete","ajaxmessage"=>"Insert data tidak sempurna.");
									header('Content-Type: application/json');
									echo json_encode($response);
									$dbcon = null;
									exit;
								}
							} else {
								$response = array("ajaxresult"=>"uncomplete","ajaxmessage"=>"Insert data tidak sempurna.");
								header('Content-Type: application/json');
								echo json_encode($response);
								$dbcon = null;
								exit;
							}
						} else { /* AutoIncrement di appx_wp_base sebagai NPWPD :: $npwpdBaseNumber = $dbcon->lastInsertId(); */
							$stmt_wp_perorangan = $dbcon->prepare("INSERT INTO appx_wp_perorangan(nik,kategori,npwp,npwpd,nama,alamat,status) VALUES (:xwpnik,'PD','','',UCASE(:xwpnama),UCASE(:xwpalamat),1)");
							$stmt_wp_perorangan->bindValue(":xwpnik", $arrinfo[0], PDO::PARAM_STR);
							$stmt_wp_perorangan->bindValue(":xwpnama", $arrinfo[1], PDO::PARAM_STR);
							$stmt_wp_perorangan->bindValue(":xwpalamat", $arrinfo[6], PDO::PARAM_STR);
							if ($stmt_wp_perorangan->execute()) { /* insert ke appx_wp_base */
								$stmt_wp_base = $dbcon->prepare("INSERT INTO appx_wp_base(kategori,origin,npwpd,nama,alamat,status) VALUES ('PD','IDX','',UCASE(:zwpnama),UCASE(:zwpalamat),1)");
								$stmt_wp_base->bindValue(":zwpnama", $arrinfo[1], PDO::PARAM_STR);
								$stmt_wp_base->bindValue(":zwpalamat", $arrinfo[6], PDO::PARAM_STR);
								if ($stmt_wp_base->execute()) {
									/* CREATE NPWPD berdasar idx!!! */
									$npwpdBaseNumber = $dbcon->lastInsertId();
									$npwpdFixdNumber = intval($npwpdBaseNumber) + 3323999990000000;
									/* /CREATE NPWPD berdasar idx!!! */
									$stmt_update_penduduk = $dbcon->prepare("UPDATE appx_master_data_penduduk SET npwpd = :idxnpwpd WHERE nik = :ywpnik");
									$stmt_update_penduduk->bindValue(":idxnpwpd", $npwpdFixdNumber, PDO::PARAM_STR);
									$stmt_update_penduduk->bindValue(":ywpnik", $arrinfo[0], PDO::PARAM_STR);
									if ($stmt_update_penduduk->execute()) {
										$stmt_update_perorangan = $dbcon->prepare("UPDATE appx_wp_perorangan SET npwpd = :idznpwpd WHERE nik = :xywpnik");
										$stmt_update_perorangan->bindValue(":idznpwpd", $npwpdFixdNumber, PDO::PARAM_STR);
										$stmt_update_perorangan->bindValue(":xywpnik", $arrinfo[0], PDO::PARAM_STR);
										if ($stmt_update_perorangan->execute()) {
											$stmt_update_wp_base = $dbcon->prepare("UPDATE appx_wp_base SET npwpd = :idynpwpd WHERE idx = :baseidx");
											$stmt_update_wp_base->bindValue(":idynpwpd", $npwpdFixdNumber, PDO::PARAM_STR);
											$stmt_update_wp_base->bindValue(":baseidx", $npwpdBaseNumber, PDO::PARAM_INT);
											if ($stmt_update_wp_base->execute()) {
												$response = array("ajaxresult"=>"saved");
												header('Content-Type: application/json');
												echo json_encode($response);
												$dbcon = null;
												exit;
											} else {
												$response = array("ajaxresult"=>"uncomplete","ajaxmessage"=>"Update data IDX sebagai NPWPD gagal.");
												header('Content-Type: application/json');
												echo json_encode($response);
												$dbcon = null;
												exit;
											}
										} else {
											$response = array("ajaxresult"=>"uncomplete","ajaxmessage"=>"Update data IDX sebagai NPWPD gagal.");
											header('Content-Type: application/json');
											echo json_encode($response);
											$dbcon = null;
											exit;
										}
									} else {
										$response = array("ajaxresult"=>"uncomplete","ajaxmessage"=>"Update data NIK sebagai NPWPD gagal.");
										header('Content-Type: application/json');
										echo json_encode($response);
										$dbcon = null;
										exit;
									}
								} else {
									$response = array("ajaxresult"=>"uncomplete","ajaxmessage"=>"Insert data tidak sempurna.");
									header('Content-Type: application/json');
									echo json_encode($response);
									$dbcon = null;
									exit;
								}
							} else {
								$response = array("ajaxresult"=>"uncomplete","ajaxmessage"=>"Insert data tidak sempurna.");
								header('Content-Type: application/json');
								echo json_encode($response);
								$dbcon = null;
								exit;
							}
						}
					} else {
						$response = array("ajaxresult"=>"notsaved","ajaxmessage"=>"Insert data ke basis data penduduk gagal.");
						header('Content-Type: application/json');
						echo json_encode($response);
						$dbcon = null;
						exit;
					}
				} else {
					$response = array("ajaxresult"=>"dataexists","ajaxmessage"=>"NIK sudah terdaftar dalam basis data penduduk.");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
			} else {
				$response = array("ajaxresult"=>"notexecuted","ajaxmessage"=>"Pencocokan data ke basis data penduduk gagal.");
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
	} elseif ($_GET['cmdx'] == "SAVE_NEW_PENDUDUK_PF") {
		/* var infobasic = "0"+nikcwp+"|1"+namapf+"|2"+tmplahirpf+"|3"+tgllahirpf+"|4"+genderpf+"|5"+goldarahpf+"|6"+alamatpf+"|7"+kecamatanpf+"|8"+desapf+"|9"+rwpf+"|10"+rtpf+"|11"+kabupatenpf+"|12"+provinsipf+"|13"+agamapf+"|14"+statuskwnpf+"|15"+pekerjaanpf+""; */
		$strinfo = $_GET['basicinfo']; $modenpwpd = $_GET['refnpwpd'];
		$arrinfo = explode("|",$strinfo);
		//$strurl = "cmdx=SAVE_NEW_PENDUDUK_PF&refnpwpd=".$modenpwpd."&basicinfo=".$strinfo."";
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt_search_exist = $dbcon->prepare("SELECT COUNT(nik) AS foundrows FROM view_data_penduduk_all WHERE nik = :cwpnik");
			$stmt_search_exist->bindValue(":cwpnik", $arrinfo[0], PDO::PARAM_STR);
			if($stmt_search_exist->execute()){
				while($rowset_search_exist = $stmt_search_exist->fetch(PDO::FETCH_ASSOC)){
					$rows = $rowset_search_exist['foundrows'];
				}
				if (intval($rows)==0) {
					$stmt_insert_A = $dbcon->prepare("INSERT INTO appx_master_data_penduduk(nik,kategori,npwp,npwpd,nama,jk,tempat,tgllahir,goldarah,agama,statuskawin,statushub,statushubrt,pendidikan,pekerjaan,namaibu,namaayah,nokk,namakk,alamat,kode_pos,kode_prop,nama_prop,kode_kab,nama_kab,kode_kec,nama_kec,kode_kel,nama_kel,rw,rt) VALUES (:cwpnik,'PF','','',UCASE(:cwpnama),UCASE(:cwpjk),UCASE(:cwptempat),:cwptgllahir,:cwpgoldarah,UCASE(:cwpagama),UCASE(:cwpstatuskawin),'','0','',UCASE(:cwppekerjaan),'','','','',UCASE(:cwpalamat),'','',UCASE(:cwpprovinsi),'',UCASE(:cwpkabupaten),'',UCASE(:cwpnamakec),'',UCASE(:cwpnamakel),:cwprw,:cwprt)");
					$stmt_insert_A->bindValue(":cwpnik", $arrinfo[0], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpnama", $arrinfo[1], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpjk", $arrinfo[4], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwptempat", $arrinfo[2], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwptgllahir", $arrinfo[3], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpgoldarah", $arrinfo[5], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpagama", $arrinfo[13], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpstatuskawin", $arrinfo[14], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwppekerjaan", $arrinfo[15], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpalamat", $arrinfo[6], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpprovinsi", $arrinfo[12], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpkabupaten", $arrinfo[11], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpnamakec", $arrinfo[7], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwpnamakel", $arrinfo[8], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwprw", $arrinfo[9], PDO::PARAM_STR);
					$stmt_insert_A->bindValue(":cwprt", $arrinfo[10], PDO::PARAM_STR);
					if ($stmt_insert_A->execute()) {
						if ($modenpwpd == "NIK") { /* NIK sebagai NPWPD */
							$stmt_wp_perorangan = $dbcon->prepare("INSERT INTO appx_wp_perorangan(nik,kategori,npwp,npwpd,nama,alamat,status) VALUES (:xwpnik,'PF','',:xwpnpwpd,UCASE(:xwpnama),UCASE(:xwpalamat),1)");
							$stmt_wp_perorangan->bindValue(":xwpnik", $arrinfo[0], PDO::PARAM_STR);
							$stmt_wp_perorangan->bindValue(":xwpnpwpd", $arrinfo[0], PDO::PARAM_STR);
							$stmt_wp_perorangan->bindValue(":xwpnama", $arrinfo[1], PDO::PARAM_STR);
							$stmt_wp_perorangan->bindValue(":xwpalamat", $arrinfo[6], PDO::PARAM_STR);
							if ($stmt_wp_perorangan->execute()) { /* insert ke appx_wp_base */
								$stmt_wp_base = $dbcon->prepare("INSERT INTO appx_wp_base(kategori,origin,npwpd,nama,alamat,status) VALUES ('PF','NIK',:zwpnpwpd,UCASE(:zwpnama),UCASE(:zwpalamat),1)");
								$stmt_wp_base->bindValue(":zwpnpwpd", $arrinfo[0], PDO::PARAM_STR);
								$stmt_wp_base->bindValue(":zwpnama", $arrinfo[1], PDO::PARAM_STR);
								$stmt_wp_base->bindValue(":zwpalamat", $arrinfo[6], PDO::PARAM_STR);
								if ($stmt_wp_base->execute()) {
									$stmt_update_penduduk = $dbcon->prepare("UPDATE appx_master_data_penduduk SET npwpd = nik WHERE nik = :ywpnik");
									$stmt_update_penduduk->bindValue(":ywpnik", $arrinfo[0], PDO::PARAM_STR);
									if ($stmt_update_penduduk->execute()) {
										$response = array("ajaxresult"=>"saved");
										header('Content-Type: application/json');
										echo json_encode($response);
										$dbcon = null;
										exit;
									} else {
										$response = array("ajaxresult"=>"uncomplete","ajaxmessage"=>"Update data NIK sebagai NPWPD gagal.");
										header('Content-Type: application/json');
										echo json_encode($response);
										$dbcon = null;
										exit;
									}
								} else {
									$response = array("ajaxresult"=>"uncomplete","ajaxmessage"=>"Insert data tidak sempurna.");
									header('Content-Type: application/json');
									echo json_encode($response);
									$dbcon = null;
									exit;
								}
							} else {
								$response = array("ajaxresult"=>"uncomplete","ajaxmessage"=>"Insert data tidak sempurna.");
								header('Content-Type: application/json');
								echo json_encode($response);
								$dbcon = null;
								exit;
							}
						} else { /* AutoIncrement di appx_wp_base sebagai NPWPD :: $npwpdBaseNumber = $dbcon->lastInsertId(); */
							$stmt_wp_perorangan = $dbcon->prepare("INSERT INTO appx_wp_perorangan(nik,kategori,npwp,npwpd,nama,alamat,status) VALUES (:xwpnik,'PF','','',UCASE(:xwpnama),UCASE(:xwpalamat),1)");
							$stmt_wp_perorangan->bindValue(":xwpnik", $arrinfo[0], PDO::PARAM_STR);
							$stmt_wp_perorangan->bindValue(":xwpnama", $arrinfo[1], PDO::PARAM_STR);
							$stmt_wp_perorangan->bindValue(":xwpalamat", $arrinfo[6], PDO::PARAM_STR);
							if ($stmt_wp_perorangan->execute()) { /* insert ke appx_wp_base */
								$stmt_wp_base = $dbcon->prepare("INSERT INTO appx_wp_base(kategori,origin,npwpd,nama,alamat,status) VALUES ('PF','IDX','',UCASE(:zwpnama),UCASE(:zwpalamat),1)");
								$stmt_wp_base->bindValue(":zwpnama", $arrinfo[1], PDO::PARAM_STR);
								$stmt_wp_base->bindValue(":zwpalamat", $arrinfo[6], PDO::PARAM_STR);
								if ($stmt_wp_base->execute()) {
									/* CREATE NPWPD berdasar idx!!! */
									$npwpdBaseNumber = $dbcon->lastInsertId();
									$npwpdFixdNumber = intval($npwpdBaseNumber) + 3323999990000000;
									/* /CREATE NPWPD berdasar idx!!! */
									$stmt_update_penduduk = $dbcon->prepare("UPDATE appx_master_data_penduduk SET npwpd = :idxnpwpd WHERE nik = :ywpnik");
									$stmt_update_penduduk->bindValue(":idxnpwpd", $npwpdFixdNumber, PDO::PARAM_STR);
									$stmt_update_penduduk->bindValue(":ywpnik", $arrinfo[0], PDO::PARAM_STR);
									if ($stmt_update_penduduk->execute()) {
										$stmt_update_perorangan = $dbcon->prepare("UPDATE appx_wp_perorangan SET npwpd = :idznpwpd WHERE nik = :xywpnik");
										$stmt_update_perorangan->bindValue(":idznpwpd", $npwpdFixdNumber, PDO::PARAM_STR);
										$stmt_update_perorangan->bindValue(":xywpnik", $arrinfo[0], PDO::PARAM_STR);
										if ($stmt_update_perorangan->execute()) {
											$stmt_update_wp_base = $dbcon->prepare("UPDATE appx_wp_base SET npwpd = :idynpwpd WHERE idx = :baseidx");
											$stmt_update_wp_base->bindValue(":idynpwpd", $npwpdFixdNumber, PDO::PARAM_STR);
											$stmt_update_wp_base->bindValue(":baseidx", $npwpdBaseNumber, PDO::PARAM_INT);
											if ($stmt_update_wp_base->execute()) {
												$response = array("ajaxresult"=>"saved");
												header('Content-Type: application/json');
												echo json_encode($response);
												$dbcon = null;
												exit;
											} else {
												$response = array("ajaxresult"=>"uncomplete","ajaxmessage"=>"Update data IDX sebagai NPWPD gagal.");
												header('Content-Type: application/json');
												echo json_encode($response);
												$dbcon = null;
												exit;
											}
										} else {
											$response = array("ajaxresult"=>"uncomplete","ajaxmessage"=>"Update data IDX sebagai NPWPD gagal.");
											header('Content-Type: application/json');
											echo json_encode($response);
											$dbcon = null;
											exit;
										}
									} else {
										$response = array("ajaxresult"=>"uncomplete","ajaxmessage"=>"Update data NIK sebagai NPWPD gagal.");
										header('Content-Type: application/json');
										echo json_encode($response);
										$dbcon = null;
										exit;
									}
								} else {
									$response = array("ajaxresult"=>"uncomplete","ajaxmessage"=>"Insert data tidak sempurna.");
									header('Content-Type: application/json');
									echo json_encode($response);
									$dbcon = null;
									exit;
								}
							} else {
								$response = array("ajaxresult"=>"uncomplete","ajaxmessage"=>"Insert data tidak sempurna.");
								header('Content-Type: application/json');
								echo json_encode($response);
								$dbcon = null;
								exit;
							}
						}
					} else {
						$response = array("ajaxresult"=>"notsaved","ajaxmessage"=>"Insert data ke basis data penduduk gagal.");
						header('Content-Type: application/json');
						echo json_encode($response);
						$dbcon = null;
						exit;
					}
				} else {
					$response = array("ajaxresult"=>"dataexists","ajaxmessage"=>"NIK sudah terdaftar dalam basis data penduduk.");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
			} else {
				$response = array("ajaxresult"=>"notexecuted","ajaxmessage"=>"Pencocokan data ke basis data penduduk gagal.");
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
	} elseif ($_GET['cmdx'] == "SAVE_NEW_WAJIB_PAJAK_BD") {
		$namabd = $_GET['bdnama']; $alamatbd = $_GET['bdalamat']; $nikpjbd = $_GET['bdnikpj']; $namapjbd = $_GET['bdnamapj']; $alamatpjbd = $_GET['bdalamatpj'];
		try {
			$dbcon = new PDO("mysql:host=".$appxinfo['_db_host_'].";dbname=".$appxinfo['_db_name_']."","".$appxinfo['_db_user_']."","".$appxinfo['_db_pass_']."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt_search_exist = $dbcon->prepare("SELECT COUNT(npwpd) AS foundrows FROM view_wp_badan WHERE nama LIKE :namabadan");
			$stmt_search_exist->bindValue(":namabadan", $namabd, PDO::PARAM_STR);
			if ($stmt_search_exist->execute()) {
				while($rowset_search_exist = $stmt_search_exist->fetch(PDO::FETCH_ASSOC)){
					$rows = $rowset_search_exist['foundrows'];
				}
				if (intval($rows)==0) {
					$stmt_wp_base = $dbcon->prepare("INSERT INTO appx_wp_base(kategori,origin,npwpd,nama,alamat,status) VALUES ('BD','IDX','',UCASE(:xbadannama),UCASE(:xbadanalamat),1)");
					$stmt_wp_base->bindValue(":xbadannama", $namabd, PDO::PARAM_STR);
					$stmt_wp_base->bindValue(":xbadanalamat", $alamatbd, PDO::PARAM_STR);
					if ($stmt_wp_base->execute()) {
						/* CREATE NPWPD berdasar idx!!! */
						$npwpdBaseNumber = $dbcon->lastInsertId();
						$npwpdFixdNumber = intval($npwpdBaseNumber) + 3323999990000000;
						/* /CREATE NPWPD berdasar idx!!! */
						$stmt_badan = $dbcon->prepare("INSERT INTO appx_wp_badan(npwpd,npwp,nama,alamat,nik_pj,kategori_pj,nama_pj,alamat_pj,status) VALUES (:znpwpd,'',UCASE(:zbadannama),UCASE(:zbadanalamat),:nik_pj,'',UCASE(:znama_pj),UCASE(:zalamat_pj),1)");
						$stmt_badan->bindValue(":znpwpd", $npwpdFixdNumber, PDO::PARAM_STR);
						$stmt_badan->bindValue(":zbadannama", $namabd, PDO::PARAM_STR);
						$stmt_badan->bindValue(":zbadanalamat", $alamatbd, PDO::PARAM_STR);
						$stmt_badan->bindValue(":nik_pj", $nikpjbd, PDO::PARAM_STR);
						$stmt_badan->bindValue(":znama_pj", $namapjbd, PDO::PARAM_STR);
						$stmt_badan->bindValue(":zalamat_pj", $alamatpjbd, PDO::PARAM_STR);
						if ($stmt_badan->execute()) {
							$stmt_update_wp_base = $dbcon->prepare("UPDATE appx_wp_base SET npwpd = :idynpwpd WHERE idx = :baseidx");
							$stmt_update_wp_base->bindValue(":idynpwpd", $npwpdFixdNumber, PDO::PARAM_STR);
							$stmt_update_wp_base->bindValue(":baseidx", $npwpdBaseNumber, PDO::PARAM_INT);
							if ($stmt_update_wp_base->execute()) {
								$response = array("ajaxresult"=>"saved");
								header('Content-Type: application/json');
								echo json_encode($response);
								$dbcon = null;
								exit;
							} else {
								$response = array("ajaxresult"=>"uncomplete","ajaxmessage"=>"Update data IDX sebagai NPWPD gagal.");
								header('Content-Type: application/json');
								echo json_encode($response);
								$dbcon = null;
								exit;
							}
						} else {
							$response = array("ajaxresult"=>"notsaved","ajaxmessage"=>"Insert data ke basis data gagal.");
							header('Content-Type: application/json');
							echo json_encode($response);
							$dbcon = null;
							exit;
						}
					} else {
						$response = array("ajaxresult"=>"notsaved","ajaxmessage"=>"Insert data ke basis data gagal.");
						header('Content-Type: application/json');
						echo json_encode($response);
						$dbcon = null;
						exit;
					}
				} else {
					$response = array("ajaxresult"=>"dataexists","ajaxmessage"=>"Nama Badan/Institusi sudah terdaftar dalam basis data.");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbcon = null;
					exit;
				}
			} else {
				$response = array("ajaxresult"=>"notexecuted","ajaxmessage"=>"Pencocokan data ke basis data penduduk gagal.");
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