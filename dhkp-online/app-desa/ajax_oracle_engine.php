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
if (isset($_SESSION['desaXweb'])) {
	if (isset($_GET['cmdx'])) {
		if ($_GET['cmdx'] == "GET_SISMIOP_TAHUN_PAJAK_GLOBAL") {
			$connection = oci_connect("".$appxinfo['_oracle_sismiop_user_']."", "".$appxinfo['_oracle_sismiop_pass_']."", "//".$appxinfo['_oracle_sismiop_host_'].":".$appxinfo['_oracle_sismiop_port_']."/".$appxinfo['_oracle_sismiop_service_']."");
			if (!$connection) {
				$errorMsg = oci_error();
				$response = array("ajaxresult"=>"orcl_connect_failed","ajaxmessage"=>"Error: ".$errorMsg['message']."");
				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			} else {
				$query_get_tahun_pajak = "SELECT DISTINCT(THN_PAJAK_SPPT) \"thnpajak\" FROM SPPT ORDER BY THN_PAJAK_SPPT";
				$stmt_get_tahun_pajak = oci_parse($connection, $query_get_tahun_pajak);
				if (oci_execute($stmt_get_tahun_pajak)) {
					while ($rowset_tahun_pajak = oci_fetch_array($stmt_get_tahun_pajak, OCI_RETURN_NULLS+OCI_ASSOC)) {
						$items[] = $rowset_tahun_pajak;
					}
					$response = array("ajaxresult"=>"found","dataarray"=>$items);
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt_get_tahun_pajak);
					oci_close($connection);
					exit;
				} else {
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt_get_tahun_pajak);
					oci_close($connection);
					exit;
				}
			}
		} elseif ($_GET['cmdx'] == "GET_SISMIOP_TAHUN_PAJAK_DESA") {
			$connection = oci_connect("".$appxinfo['_oracle_sismiop_user_']."", "".$appxinfo['_oracle_sismiop_pass_']."", "//".$appxinfo['_oracle_sismiop_host_'].":".$appxinfo['_oracle_sismiop_port_']."/".$appxinfo['_oracle_sismiop_service_']."");
			if (!$connection) {
				$errorMsg = oci_error();
				$response = array("ajaxresult"=>"orcl_connect_failed","ajaxmessage"=>"Error: ".$errorMsg['message']."");
				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			} else {
				$kode_propinsi = $_SESSION['desaXweb']['KD_PROPINSI'];
				$kode_dati2 = $_SESSION['desaXweb']['KD_DATI2'];
				$kode_kecamatan = $_SESSION['desaXweb']['KD_KECAMATAN'];
				$kode_kelurahan = $_SESSION['desaXweb']['KD_KELURAHAN'];
				$query_get_tahun_pajak = "SELECT DISTINCT(THN_PAJAK_SPPT) \"thnpajak\" FROM SPPT WHERE KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL ORDER BY THN_PAJAK_SPPT";
				$stmt_get_tahun_pajak = oci_parse($connection, $query_get_tahun_pajak);
				oci_bind_by_name($stmt_get_tahun_pajak, ":KODEPROP", $kode_propinsi);
				oci_bind_by_name($stmt_get_tahun_pajak, ":KODEDATI2", $kode_dati2);
				oci_bind_by_name($stmt_get_tahun_pajak, ":KODEKEC", $kode_kecamatan);
				oci_bind_by_name($stmt_get_tahun_pajak, ":KODEDESKEL", $kode_kelurahan);
				if (oci_execute($stmt_get_tahun_pajak)) {
					while ($rowset_tahun_pajak = oci_fetch_array($stmt_get_tahun_pajak, OCI_RETURN_NULLS+OCI_ASSOC)) {
						$items[] = $rowset_tahun_pajak;
					}
					$response = array("ajaxresult"=>"found","dataarray"=>$items);
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt_get_tahun_pajak);
					oci_close($connection);
					exit;
				} else {
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_free_statement($stmt_get_tahun_pajak);
					oci_close($connection);
					exit;
				}
			}
		} elseif ($_GET['cmdx'] == "GET_SISMIOP_DHKP_STATUS" || $_GET['cmdx'] == "GET_SISMIOP_PEMBAYARAN_STATUS") {
			$connection = oci_connect("".$appxinfo['_oracle_sismiop_user_']."", "".$appxinfo['_oracle_sismiop_pass_']."", "//".$appxinfo['_oracle_sismiop_host_'].":".$appxinfo['_oracle_sismiop_port_']."/".$appxinfo['_oracle_sismiop_service_']."");
			if (!$connection) {
				$errorMsg = oci_error();
				echo $errorMsg['message'], "\n";
				exit;
			} else {
				$kode_propinsi = $_SESSION['desaXweb']['KD_PROPINSI'];
				$kode_dati2 = $_SESSION['desaXweb']['KD_DATI2'];
				$kode_kecamatan = $_SESSION['desaXweb']['KD_KECAMATAN'];
				$kode_kelurahan = $_SESSION['desaXweb']['KD_KELURAHAN'];
				$namaKecamatan = $_SESSION['desaXweb']['NM_KECAMATAN'];
				$namaKelurahan = $_SESSION['desaXweb']['NM_KELURAHAN'];
				$kode_sppt = strval($kode_propinsi.'.'.$kode_dati2.'.'.$kode_kecamatan.'.'.$kode_kelurahan.'');
				$thnsppt = $_SESSION['tahunPAJAK'];
				$tgldefaultterbitsppt = strval(''.$thnsppt.'-01-02');
				$tglterbitdefault = strval("".$_SESSION['tahunPAJAK']."-01-02");
				$query_count_all_penetapan = "SELECT COUNT(*) \"countallpenetapan\" FROM SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL";
				$stmt_count_all_penetapan = oci_parse($connection, $query_count_all_penetapan);
				oci_bind_by_name($stmt_count_all_penetapan, ":KODEPROP", $kode_propinsi);
				oci_bind_by_name($stmt_count_all_penetapan, ":KODEDATI2", $kode_dati2);
				oci_bind_by_name($stmt_count_all_penetapan, ":KODEKEC", $kode_kecamatan);
				oci_bind_by_name($stmt_count_all_penetapan, ":KODEDESKEL", $kode_kelurahan);
				oci_bind_by_name($stmt_count_all_penetapan, ":THNSPPT", $thnsppt);
				if (oci_execute($stmt_count_all_penetapan)) {
					while ($rowset_count_all_penetapan = oci_fetch_array($stmt_count_all_penetapan, OCI_RETURN_NULLS+OCI_ASSOC)) {
						$count_all_penetapan = $rowset_count_all_penetapan['countallpenetapan'];
					}
					oci_free_statement($stmt_count_all_penetapan);
					$query_count_default_penetapan = "SELECT COUNT(*) \"countdefaultpenetapan\" FROM SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL AND TGL_TERBIT_SPPT = TO_DATE(:TGLTERBITSPPDEFAULT,'YYYY-MM-DD')";
					$stmt_count_default_penetapan = oci_parse($connection, $query_count_default_penetapan);
					oci_bind_by_name($stmt_count_default_penetapan, ":KODEPROP", $kode_propinsi);
					oci_bind_by_name($stmt_count_default_penetapan, ":KODEDATI2", $kode_dati2);
					oci_bind_by_name($stmt_count_default_penetapan, ":KODEKEC", $kode_kecamatan);
					oci_bind_by_name($stmt_count_default_penetapan, ":KODEDESKEL", $kode_kelurahan);
					oci_bind_by_name($stmt_count_default_penetapan, ":THNSPPT", $thnsppt);
					oci_bind_by_name($stmt_count_default_penetapan, ":TGLTERBITSPPDEFAULT", $tgldefaultterbitsppt);
					if (oci_execute($stmt_count_default_penetapan)) {
						while ($rowset_count_default_penetapan = oci_fetch_array($stmt_count_default_penetapan, OCI_RETURN_NULLS+OCI_ASSOC)) {
							$count_default_penetapan = $rowset_count_default_penetapan['countdefaultpenetapan'];
						}
						oci_free_statement($stmt_count_default_penetapan);
						$query_count_later_penetapan = "SELECT COUNT(*) \"countlaterpenetapan\" FROM SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL AND TGL_TERBIT_SPPT != TO_DATE(:TGLTERBITSPPDEFAULT,'YYYY-MM-DD')";
						$stmt_count_later_penetapan = oci_parse($connection, $query_count_later_penetapan);
						oci_bind_by_name($stmt_count_later_penetapan, ":KODEPROP", $kode_propinsi);
						oci_bind_by_name($stmt_count_later_penetapan, ":KODEDATI2", $kode_dati2);
						oci_bind_by_name($stmt_count_later_penetapan, ":KODEKEC", $kode_kecamatan);
						oci_bind_by_name($stmt_count_later_penetapan, ":KODEDESKEL", $kode_kelurahan);
						oci_bind_by_name($stmt_count_later_penetapan, ":THNSPPT", $thnsppt);
						oci_bind_by_name($stmt_count_later_penetapan, ":TGLTERBITSPPDEFAULT", $tgldefaultterbitsppt);
						if (oci_execute($stmt_count_later_penetapan)) {
							while ($rowset_count_later_penetapan = oci_fetch_array($stmt_count_later_penetapan, OCI_RETURN_NULLS+OCI_ASSOC)) {
								$count_later_penetapan = $rowset_count_later_penetapan['countlaterpenetapan'];
							}
							oci_free_statement($stmt_count_later_penetapan);
							$query_count_unpaid_sppt = "SELECT COUNT(*) \"countunpaidsppt\" FROM SPPT A WHERE A.THN_PAJAK_SPPT = :THNSPPT AND A.KD_PROPINSI = :KODEPROP AND A.KD_DATI2 = :KODEDATI2 AND A.KD_KECAMATAN = :KODEKEC AND A.KD_KELURAHAN = :KODEDESKEL AND A.STATUS_PEMBAYARAN_SPPT = 0";
							$stmt_count_unpaid_sppt = oci_parse($connection, $query_count_unpaid_sppt);
							oci_bind_by_name($stmt_count_unpaid_sppt, ":KODEPROP", $kode_propinsi);
							oci_bind_by_name($stmt_count_unpaid_sppt, ":KODEDATI2", $kode_dati2);
							oci_bind_by_name($stmt_count_unpaid_sppt, ":KODEKEC", $kode_kecamatan);
							oci_bind_by_name($stmt_count_unpaid_sppt, ":KODEDESKEL", $kode_kelurahan);
							oci_bind_by_name($stmt_count_unpaid_sppt, ":THNSPPT", $thnsppt);
							if (oci_execute($stmt_count_unpaid_sppt)) {
								while ($rowset_count_unpaid_sppt = oci_fetch_array($stmt_count_unpaid_sppt, OCI_RETURN_NULLS+OCI_ASSOC)) {
									$count_unpaid_sppt = $rowset_count_unpaid_sppt['countunpaidsppt'];
								}
								oci_free_statement($stmt_count_unpaid_sppt);
								$query_count_paid_sppt = "SELECT COUNT(*) \"countpaidsppt\" FROM SPPT A WHERE A.THN_PAJAK_SPPT = :THNSPPT AND A.KD_PROPINSI = :KODEPROP AND A.KD_DATI2 = :KODEDATI2 AND A.KD_KECAMATAN = :KODEKEC AND A.KD_KELURAHAN = :KODEDESKEL AND A.STATUS_PEMBAYARAN_SPPT != 0";
								$stmt_count_paid_sppt = oci_parse($connection, $query_count_paid_sppt);
								oci_bind_by_name($stmt_count_paid_sppt, ":KODEPROP", $kode_propinsi);
								oci_bind_by_name($stmt_count_paid_sppt, ":KODEDATI2", $kode_dati2);
								oci_bind_by_name($stmt_count_paid_sppt, ":KODEKEC", $kode_kecamatan);
								oci_bind_by_name($stmt_count_paid_sppt, ":KODEDESKEL", $kode_kelurahan);
								oci_bind_by_name($stmt_count_paid_sppt, ":THNSPPT", $thnsppt);
								if (oci_execute($stmt_count_paid_sppt)) {
									while ($rowset_count_paid_sppt = oci_fetch_array($stmt_count_paid_sppt, OCI_RETURN_NULLS+OCI_ASSOC)) {
										$count_paid_sppt = $rowset_count_paid_sppt['countpaidsppt'];
									}
									oci_free_statement($stmt_count_paid_sppt);
									$query_sum_all_penetapan = "SELECT NVL(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) \"sumallpenetapan\" FROM SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL";
									$stmt_sum_all_penetapan = oci_parse($connection, $query_sum_all_penetapan);
									oci_bind_by_name($stmt_sum_all_penetapan, ":KODEPROP", $kode_propinsi);
									oci_bind_by_name($stmt_sum_all_penetapan, ":KODEDATI2", $kode_dati2);
									oci_bind_by_name($stmt_sum_all_penetapan, ":KODEKEC", $kode_kecamatan);
									oci_bind_by_name($stmt_sum_all_penetapan, ":KODEDESKEL", $kode_kelurahan);
									oci_bind_by_name($stmt_sum_all_penetapan, ":THNSPPT", $thnsppt);
									if (oci_execute($stmt_sum_all_penetapan)) {
										while ($rowset_sum_all_penetapan = oci_fetch_array($stmt_sum_all_penetapan, OCI_RETURN_NULLS+OCI_ASSOC)) {
											$sum_all_penetapan = $rowset_sum_all_penetapan['sumallpenetapan'];
										}
										oci_free_statement($stmt_sum_all_penetapan);
										$query_sum_default_penetapan = "SELECT NVL(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) \"sumdefaultpenetapan\" FROM SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL AND TGL_TERBIT_SPPT = TO_DATE(:TGLTERBITSPPDEFAULT,'YYYY-MM-DD')";
										$stmt_sum_default_penetapan = oci_parse($connection, $query_sum_default_penetapan);
										oci_bind_by_name($stmt_sum_default_penetapan, ":KODEPROP", $kode_propinsi);
										oci_bind_by_name($stmt_sum_default_penetapan, ":KODEDATI2", $kode_dati2);
										oci_bind_by_name($stmt_sum_default_penetapan, ":KODEKEC", $kode_kecamatan);
										oci_bind_by_name($stmt_sum_default_penetapan, ":KODEDESKEL", $kode_kelurahan);
										oci_bind_by_name($stmt_sum_default_penetapan, ":THNSPPT", $thnsppt);
										oci_bind_by_name($stmt_sum_default_penetapan, ":TGLTERBITSPPDEFAULT", $tgldefaultterbitsppt);
										if (oci_execute($stmt_sum_default_penetapan)) {
											while ($rowset_sum_default_penetapan = oci_fetch_array($stmt_sum_default_penetapan, OCI_RETURN_NULLS+OCI_ASSOC)) {
												$sum_default_penetapan = $rowset_sum_default_penetapan['sumdefaultpenetapan'];
											}
											oci_free_statement($stmt_sum_default_penetapan);
											$query_sum_later_penetapan = "SELECT NVL(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) \"sumlaterpenetapan\" FROM SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL AND TGL_TERBIT_SPPT != TO_DATE(:TGLTERBITSPPDEFAULT,'YYYY-MM-DD')";
											$stmt_sum_later_penetapan = oci_parse($connection, $query_sum_later_penetapan);
											oci_bind_by_name($stmt_sum_later_penetapan, ":KODEPROP", $kode_propinsi);
											oci_bind_by_name($stmt_sum_later_penetapan, ":KODEDATI2", $kode_dati2);
											oci_bind_by_name($stmt_sum_later_penetapan, ":KODEKEC", $kode_kecamatan);
											oci_bind_by_name($stmt_sum_later_penetapan, ":KODEDESKEL", $kode_kelurahan);
											oci_bind_by_name($stmt_sum_later_penetapan, ":THNSPPT", $thnsppt);
											oci_bind_by_name($stmt_sum_later_penetapan, ":TGLTERBITSPPDEFAULT", $tgldefaultterbitsppt);
											if (oci_execute($stmt_sum_later_penetapan)) {
												while ($rowset_sum_later_penetapan = oci_fetch_array($stmt_sum_later_penetapan, OCI_RETURN_NULLS+OCI_ASSOC)) {
													$sum_later_penetapan = $rowset_sum_later_penetapan['sumlaterpenetapan'];
												}
												oci_free_statement($stmt_sum_later_penetapan);
												$query_sum_unpaid_sppt = "SELECT NVL(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) \"sumunpaidsppt\" FROM SPPT A WHERE A.THN_PAJAK_SPPT = :THNSPPT AND A.KD_PROPINSI = :KODEPROP AND A.KD_DATI2 = :KODEDATI2 AND A.KD_KECAMATAN = :KODEKEC AND A.KD_KELURAHAN = :KODEDESKEL AND A.STATUS_PEMBAYARAN_SPPT = 0";
												$stmt_sum_unpaid_sppt = oci_parse($connection, $query_sum_unpaid_sppt);
												oci_bind_by_name($stmt_sum_unpaid_sppt, ":KODEPROP", $kode_propinsi);
												oci_bind_by_name($stmt_sum_unpaid_sppt, ":KODEDATI2", $kode_dati2);
												oci_bind_by_name($stmt_sum_unpaid_sppt, ":KODEKEC", $kode_kecamatan);
												oci_bind_by_name($stmt_sum_unpaid_sppt, ":KODEDESKEL", $kode_kelurahan);
												oci_bind_by_name($stmt_sum_unpaid_sppt, ":THNSPPT", $thnsppt);
												if (oci_execute($stmt_sum_unpaid_sppt)) {
													while ($rowset_sum_unpaid_sppt = oci_fetch_array($stmt_sum_unpaid_sppt, OCI_RETURN_NULLS+OCI_ASSOC)) {
														$sum_unpaid_sppt = $rowset_sum_unpaid_sppt['sumunpaidsppt'];
													}
													oci_free_statement($stmt_sum_unpaid_sppt);
													$query_sum_paid_sppt = "SELECT NVL(SUM(JML_SPPT_YG_DIBAYAR), 0) \"sumpaidsppt\" FROM PEMBAYARAN_SPPT A WHERE A.THN_PAJAK_SPPT = :THNSPPT AND A.KD_PROPINSI = :KODEPROP AND A.KD_DATI2 = :KODEDATI2 AND A.KD_KECAMATAN = :KODEKEC AND A.KD_KELURAHAN = :KODEDESKEL";
													$stmt_sum_paid_sppt = oci_parse($connection, $query_sum_paid_sppt);
													oci_bind_by_name($stmt_sum_paid_sppt, ":KODEPROP", $kode_propinsi);
													oci_bind_by_name($stmt_sum_paid_sppt, ":KODEDATI2", $kode_dati2);
													oci_bind_by_name($stmt_sum_paid_sppt, ":KODEKEC", $kode_kecamatan);
													oci_bind_by_name($stmt_sum_paid_sppt, ":KODEDESKEL", $kode_kelurahan);
													oci_bind_by_name($stmt_sum_paid_sppt, ":THNSPPT", $thnsppt);
													if (oci_execute($stmt_sum_paid_sppt)) {
														while ($rowset_sum_paid_sppt = oci_fetch_array($stmt_sum_paid_sppt, OCI_RETURN_NULLS+OCI_ASSOC)) {
															$sum_paid_sppt = $rowset_sum_paid_sppt['sumpaidsppt'];
														}
														oci_free_statement($stmt_sum_paid_sppt);
														oci_close($connection);
														/* ### CONNECTION TO LOCAL DHKP MYSQL #### */
														try {
															$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
															$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
															/* counting data */
															$stmt_cnt_personil = $dbcon->prepare("SELECT COUNT(*) as foundpetugas FROM appx_desa_petugas_pungut WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa");
															$stmt_cnt_personil->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
															$stmt_cnt_personil->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
															$stmt_cnt_personil->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
															$stmt_cnt_personil->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
															if ($stmt_cnt_personil->execute()) {
																while($rowset_cnt_personil = $stmt_cnt_personil->fetch(PDO::FETCH_ASSOC)){
																	$countpersonil = $rowset_cnt_personil['foundpetugas'];
																}
															} else {
																$countpersonil = 0;
															}
															/* BUILD DHKP - EXCLUDE SPPT YANG SUDAH MENGALAMI PEMUTAKHIRAN */
															/* ========== TEMP =========== */
															$stmt_create_dhkp_temporary_sppt_data = $dbcon->prepare("CREATE TEMPORARY TABLE dhkp_temporary_sppt_data(KD_PROPINSI char(2) NOT NULL,KD_DATI2 char(2) NOT NULL,KD_KECAMATAN char(3) NOT NULL,KD_KELURAHAN char(3) NOT NULL,KD_BLOK char(3) NOT NULL,NO_URUT char(4) NOT NULL,KD_JNS_OP char(1) NOT NULL,THN_PAJAK_SPPT char(4) NOT NULL,SIKLUS_SPPT int(10) NOT NULL DEFAULT '0',KD_KANWIL_BANK varchar(2) NOT NULL DEFAULT '',KD_KPPBB_BANK varchar(2) NOT NULL DEFAULT '',KD_BANK_TUNGGAL varchar(2) NOT NULL DEFAULT '',KD_BANK_PERSEPSI varchar(2) NOT NULL DEFAULT '',KD_TP varchar(2) NOT NULL DEFAULT '',NM_WP_SPPT varchar(255) NOT NULL DEFAULT '',JLN_WP_SPPT varchar(255) NOT NULL DEFAULT '',BLOK_KAV_NO_WP_SPPT varchar(64) NOT NULL DEFAULT '',RW_WP_SPPT varchar(3) NOT NULL DEFAULT '',RT_WP_SPPT varchar(3) NOT NULL DEFAULT '',KELURAHAN_WP_SPPT varchar(64) NOT NULL DEFAULT '',KOTA_WP_SPPT varchar(64) NOT NULL DEFAULT '',KD_POS_WP_SPPT varchar(5) NOT NULL DEFAULT '',NPWP_SPPT varchar(16) NOT NULL DEFAULT '',NO_PERSIL_SPPT varchar(32) NOT NULL DEFAULT '',KD_KLS_TANAH varchar(8) NOT NULL DEFAULT '',THN_AWAL_KLS_TANAH varchar(4) NOT NULL DEFAULT '',KD_KLS_BNG varchar(8) NOT NULL DEFAULT '',THN_AWAL_KLS_BNG varchar(4) NOT NULL DEFAULT '',TGL_JATUH_TEMPO_SPPT date NOT NULL DEFAULT '1900-01-01',LUAS_BUMI_SPPT decimal(10,2) NOT NULL DEFAULT '0.00',LUAS_BNG_SPPT decimal(10,2) NOT NULL DEFAULT '0.00',NJOP_BUMI_SPPT bigint(20) NOT NULL DEFAULT '0',NJOP_BNG_SPPT bigint(20) NOT NULL DEFAULT '0',NJOP_SPPT bigint(20) NOT NULL DEFAULT '0',NJOPTKP_SPPT bigint(20) NOT NULL DEFAULT '0',NJKP_SPPT bigint(20) NOT NULL DEFAULT '0',PBB_TERHUTANG_SPPT bigint(20) NOT NULL DEFAULT '0',FAKTOR_PENGURANG_SPPT int(10) NOT NULL DEFAULT '0',PBB_YG_HARUS_DIBAYAR_SPPT bigint(20) NOT NULL DEFAULT '0',STATUS_PEMBAYARAN_SPPT varchar(4) NOT NULL DEFAULT '',STATUS_TAGIHAN_SPPT varchar(4) NOT NULL DEFAULT '',STATUS_CETAK_SPPT varchar(4) NOT NULL DEFAULT '',TGL_TERBIT_SPPT date NOT NULL DEFAULT '1900-01-01',TGL_CETAK_SPPT date NOT NULL DEFAULT '1900-01-01',NIP_PENCETAK_SPPT varchar(16) NOT NULL DEFAULT '',PEMBAYARAN_SPPT_KE int(10) NOT NULL DEFAULT '0',DENDA_SPPT decimal(10,2) NOT NULL DEFAULT '0.00',JML_SPPT_YG_DIBAYAR bigint(20) NOT NULL DEFAULT '0',TGL_PEMBAYARAN_SPPT date NOT NULL DEFAULT '1900-01-01',TGL_REKAM_BYR_SPPT date NOT NULL DEFAULT '1900-01-01',NIP_REKAM_BYR_SPPT varchar(16) NOT NULL DEFAULT '',PETUGASIDX int(10) NOT NULL DEFAULT '0',NAMAPETUGAS varchar(64) NOT NULL DEFAULT '',STATUS int(10) NOT NULL DEFAULT '0',PRIMARY KEY (KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,TGL_TERBIT_SPPT),INDEX KD_PROPINSI (KD_PROPINSI),INDEX KD_DATI2 (KD_DATI2),INDEX KD_KECAMATAN (KD_KECAMATAN),INDEX KD_KELURAHAN (KD_KELURAHAN),INDEX KD_BLOK (KD_BLOK),INDEX NO_URUT (NO_URUT),INDEX KD_JNS_OP (KD_JNS_OP),INDEX THN_PAJAK_SPPT (THN_PAJAK_SPPT),INDEX KELURAHAN_WP_SPPT (KELURAHAN_WP_SPPT),INDEX TGL_TERBIT_SPPT (TGL_TERBIT_SPPT),INDEX TGL_PEMBAYARAN_SPPT (TGL_PEMBAYARAN_SPPT),INDEX PETUGASIDX (PETUGASIDX)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
															$stmt_create_dhkp_temporary_sppt_data->execute();
															$stmt_create_dhkp_temporary_neu_sppt_data = $dbcon->prepare("CREATE TEMPORARY TABLE dhkp_temporary_neu_sppt_data(KD_PROPINSI char(2) NOT NULL,KD_DATI2 char(2) NOT NULL,KD_KECAMATAN char(3) NOT NULL,KD_KELURAHAN char(3) NOT NULL,KD_BLOK char(3) NOT NULL,NO_URUT char(4) NOT NULL,KD_JNS_OP char(1) NOT NULL,THN_PAJAK_SPPT char(4) NOT NULL,SIKLUS_SPPT int(10) NOT NULL DEFAULT '0',KD_KANWIL_BANK varchar(2) NOT NULL DEFAULT '',KD_KPPBB_BANK varchar(2) NOT NULL DEFAULT '',KD_BANK_TUNGGAL varchar(2) NOT NULL DEFAULT '',KD_BANK_PERSEPSI varchar(2) NOT NULL DEFAULT '',KD_TP varchar(2) NOT NULL DEFAULT '',NM_WP_SPPT varchar(255) NOT NULL DEFAULT '',JLN_WP_SPPT varchar(255) NOT NULL DEFAULT '',BLOK_KAV_NO_WP_SPPT varchar(64) NOT NULL DEFAULT '',RW_WP_SPPT varchar(3) NOT NULL DEFAULT '',RT_WP_SPPT varchar(3) NOT NULL DEFAULT '',KELURAHAN_WP_SPPT varchar(64) NOT NULL DEFAULT '',KOTA_WP_SPPT varchar(64) NOT NULL DEFAULT '',KD_POS_WP_SPPT varchar(5) NOT NULL DEFAULT '',NPWP_SPPT varchar(16) NOT NULL DEFAULT '',NO_PERSIL_SPPT varchar(32) NOT NULL DEFAULT '',KD_KLS_TANAH varchar(8) NOT NULL DEFAULT '',THN_AWAL_KLS_TANAH varchar(4) NOT NULL DEFAULT '',KD_KLS_BNG varchar(8) NOT NULL DEFAULT '',THN_AWAL_KLS_BNG varchar(4) NOT NULL DEFAULT '',TGL_JATUH_TEMPO_SPPT date NOT NULL DEFAULT '1900-01-01',LUAS_BUMI_SPPT decimal(10,2) NOT NULL DEFAULT '0.00',LUAS_BNG_SPPT decimal(10,2) NOT NULL DEFAULT '0.00',NJOP_BUMI_SPPT bigint(20) NOT NULL DEFAULT '0',NJOP_BNG_SPPT bigint(20) NOT NULL DEFAULT '0',NJOP_SPPT bigint(20) NOT NULL DEFAULT '0',NJOPTKP_SPPT bigint(20) NOT NULL DEFAULT '0',NJKP_SPPT bigint(20) NOT NULL DEFAULT '0',PBB_TERHUTANG_SPPT bigint(20) NOT NULL DEFAULT '0',FAKTOR_PENGURANG_SPPT int(10) NOT NULL DEFAULT '0',PBB_YG_HARUS_DIBAYAR_SPPT bigint(20) NOT NULL DEFAULT '0',STATUS_PEMBAYARAN_SPPT varchar(4) NOT NULL DEFAULT '',STATUS_TAGIHAN_SPPT varchar(4) NOT NULL DEFAULT '',STATUS_CETAK_SPPT varchar(4) NOT NULL DEFAULT '',TGL_TERBIT_SPPT date NOT NULL DEFAULT '1900-01-01',TGL_CETAK_SPPT date NOT NULL DEFAULT '1900-01-01',NIP_PENCETAK_SPPT varchar(16) NOT NULL DEFAULT '',PEMBAYARAN_SPPT_KE int(10) NOT NULL DEFAULT '0',DENDA_SPPT decimal(10,2) NOT NULL DEFAULT '0.00',JML_SPPT_YG_DIBAYAR bigint(20) NOT NULL DEFAULT '0',TGL_PEMBAYARAN_SPPT date NOT NULL DEFAULT '1900-01-01',TGL_REKAM_BYR_SPPT date NOT NULL DEFAULT '1900-01-01',NIP_REKAM_BYR_SPPT varchar(16) NOT NULL DEFAULT '',PETUGASIDX int(10) NOT NULL DEFAULT '0',NAMAPETUGAS varchar(64) NOT NULL DEFAULT '',STATUS int(10) NOT NULL DEFAULT '0',PRIMARY KEY (KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,TGL_TERBIT_SPPT),INDEX KD_PROPINSI (KD_PROPINSI),INDEX KD_DATI2 (KD_DATI2),INDEX KD_KECAMATAN (KD_KECAMATAN),INDEX KD_KELURAHAN (KD_KELURAHAN),INDEX KD_BLOK (KD_BLOK),INDEX NO_URUT (NO_URUT),INDEX KD_JNS_OP (KD_JNS_OP),INDEX THN_PAJAK_SPPT (THN_PAJAK_SPPT),INDEX KELURAHAN_WP_SPPT (KELURAHAN_WP_SPPT),INDEX TGL_TERBIT_SPPT (TGL_TERBIT_SPPT),INDEX TGL_PEMBAYARAN_SPPT (TGL_PEMBAYARAN_SPPT),INDEX PETUGASIDX (PETUGASIDX)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
															$stmt_create_dhkp_temporary_neu_sppt_data->execute();
															/* Insert Data to temporary SPPT */
															$stmt_insert_sppt_data = $dbcon->prepare("INSERT INTO dhkp_temporary_sppt_data(KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,KD_KANWIL_BANK,KD_KPPBB_BANK,KD_BANK_TUNGGAL,KD_BANK_PERSEPSI,KD_TP,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,KELURAHAN_WP_SPPT,KOTA_WP_SPPT,KD_POS_WP_SPPT,NPWP_SPPT,NO_PERSIL_SPPT,KD_KLS_TANAH,THN_AWAL_KLS_TANAH,KD_KLS_BNG,THN_AWAL_KLS_BNG,TGL_JATUH_TEMPO_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,NJOP_BUMI_SPPT,NJOP_BNG_SPPT,NJOP_SPPT,NJOPTKP_SPPT,NJKP_SPPT,PBB_TERHUTANG_SPPT,FAKTOR_PENGURANG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,STATUS_TAGIHAN_SPPT,STATUS_CETAK_SPPT,TGL_TERBIT_SPPT,TGL_CETAK_SPPT,NIP_PENCETAK_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,TGL_REKAM_BYR_SPPT,NIP_REKAM_BYR_SPPT,PETUGASIDX,NAMAPETUGAS,STATUS) SELECT KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,KD_KANWIL_BANK,KD_KPPBB_BANK,KD_BANK_TUNGGAL,KD_BANK_PERSEPSI,KD_TP,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,KELURAHAN_WP_SPPT,KOTA_WP_SPPT,KD_POS_WP_SPPT,NPWP_SPPT,NO_PERSIL_SPPT,KD_KLS_TANAH,THN_AWAL_KLS_TANAH,KD_KLS_BNG,THN_AWAL_KLS_BNG,TGL_JATUH_TEMPO_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,NJOP_BUMI_SPPT,NJOP_BNG_SPPT,NJOP_SPPT,NJOPTKP_SPPT,NJKP_SPPT,PBB_TERHUTANG_SPPT,FAKTOR_PENGURANG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,STATUS_TAGIHAN_SPPT,STATUS_CETAK_SPPT,TGL_TERBIT_SPPT,TGL_CETAK_SPPT,NIP_PENCETAK_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,TGL_REKAM_BYR_SPPT,NIP_REKAM_BYR_SPPT,PETUGASIDX,NAMAPETUGAS,STATUS FROM sppt_data FORCE INDEX(KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,TGL_TERBIT_SPPT) WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND TGL_TERBIT_SPPT = :defaulttglterbit ORDER BY NO_URUT, KD_JNS_OP");
															$stmt_insert_sppt_data->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
															$stmt_insert_sppt_data->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
															$stmt_insert_sppt_data->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
															$stmt_insert_sppt_data->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
															$stmt_insert_sppt_data->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
															$stmt_insert_sppt_data->bindValue(":defaulttglterbit", $tglterbitdefault, PDO::PARAM_STR);
															$stmt_insert_sppt_data->execute();
															/* Insert Data to temporary NEU SPPT */
															$stmt_insert_neu_sppt_data = $dbcon->prepare("INSERT INTO dhkp_temporary_neu_sppt_data(KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,KD_KANWIL_BANK,KD_KPPBB_BANK,KD_BANK_TUNGGAL,KD_BANK_PERSEPSI,KD_TP,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,KELURAHAN_WP_SPPT,KOTA_WP_SPPT,KD_POS_WP_SPPT,NPWP_SPPT,NO_PERSIL_SPPT,KD_KLS_TANAH,THN_AWAL_KLS_TANAH,KD_KLS_BNG,THN_AWAL_KLS_BNG,TGL_JATUH_TEMPO_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,NJOP_BUMI_SPPT,NJOP_BNG_SPPT,NJOP_SPPT,NJOPTKP_SPPT,NJKP_SPPT,PBB_TERHUTANG_SPPT,FAKTOR_PENGURANG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,STATUS_TAGIHAN_SPPT,STATUS_CETAK_SPPT,TGL_TERBIT_SPPT,TGL_CETAK_SPPT,NIP_PENCETAK_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,TGL_REKAM_BYR_SPPT,NIP_REKAM_BYR_SPPT,PETUGASIDX,NAMAPETUGAS,STATUS) SELECT KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,KD_KANWIL_BANK,KD_KPPBB_BANK,KD_BANK_TUNGGAL,KD_BANK_PERSEPSI,KD_TP,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,KELURAHAN_WP_SPPT,KOTA_WP_SPPT,KD_POS_WP_SPPT,NPWP_SPPT,NO_PERSIL_SPPT,KD_KLS_TANAH,THN_AWAL_KLS_TANAH,KD_KLS_BNG,THN_AWAL_KLS_BNG,TGL_JATUH_TEMPO_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,NJOP_BUMI_SPPT,NJOP_BNG_SPPT,NJOP_SPPT,NJOPTKP_SPPT,NJKP_SPPT,PBB_TERHUTANG_SPPT,FAKTOR_PENGURANG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,STATUS_TAGIHAN_SPPT,STATUS_CETAK_SPPT,TGL_TERBIT_SPPT,TGL_CETAK_SPPT,NIP_PENCETAK_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,TGL_REKAM_BYR_SPPT,NIP_REKAM_BYR_SPPT,PETUGASIDX,NAMAPETUGAS,STATUS FROM sppt_data FORCE INDEX(KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,TGL_TERBIT_SPPT) WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND TGL_TERBIT_SPPT > :defaulttglterbit ORDER BY NO_URUT, KD_JNS_OP");
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
															/* BUILD DHKP - EXCLUDE SPPT YANG SUDAH MENGALAMI PEMUTAKHIRAN */
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
																	$stmt = $dbcon->prepare("SELECT COUNT(*) AS totalcountsppt, COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprova AND KD_DATI2 = :kdkaba AND KD_KECAMATAN = :kdkeca AND KD_KELURAHAN = :kddesaa AND THN_PAJAK_SPPT = :thnpajaka) AS countallpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovb AND KD_DATI2 = :kdkabb AND KD_KECAMATAN = :kdkecb AND KD_KELURAHAN = :kddesab AND THN_PAJAK_SPPT = :thnpajakb AND TGL_TERBIT_SPPT = '2017-01-02') AS countdefaultpenetapan, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovc AND KD_DATI2 = :kdkabc AND KD_KECAMATAN = :kdkecc AND KD_KELURAHAN = :kddesac AND THN_PAJAK_SPPT = :thnpajakc AND TGL_TERBIT_SPPT = '2017-01-02') AS sumdefaultpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovd AND KD_DATI2 = :kdkabd AND KD_KECAMATAN = :kdkecd AND KD_KELURAHAN = :kddesad AND THN_PAJAK_SPPT = :thnpajakd AND TGL_TERBIT_SPPT != '2017-01-02') AS countlaterpenetapan, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprove AND KD_DATI2 = :kdkabe AND KD_KECAMATAN = :kdkece AND KD_KELURAHAN = :kddesae AND THN_PAJAK_SPPT = :thnpajake AND TGL_TERBIT_SPPT != '2017-01-02') AS sumlaterpenetapan, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovf AND KD_DATI2 = :kdkabf AND KD_KECAMATAN = :kdkecf AND KD_KELURAHAN = :kddesaf AND THN_PAJAK_SPPT = :thnpajakf AND PETUGASIDX = 0) AS countunassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovg AND KD_DATI2 = :kdkabg AND KD_KECAMATAN = :kdkecg AND KD_KELURAHAN = :kddesag AND THN_PAJAK_SPPT = :thnpajakg AND PETUGASIDX = 0) AS sumunassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovh AND KD_DATI2 = :kdkabh AND KD_KECAMATAN = :kdkech AND KD_KELURAHAN = :kddesah AND THN_PAJAK_SPPT = :thnpajakh AND PETUGASIDX > 0) AS countassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovi AND KD_DATI2 = :kdkabi AND KD_KECAMATAN = :kdkeci AND KD_KELURAHAN = :kddesai AND THN_PAJAK_SPPT = :thnpajaki AND PETUGASIDX > 0) AS sumassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovj AND KD_DATI2 = :kdkabj AND KD_KECAMATAN = :kdkecj AND KD_KELURAHAN = :kddesaj AND THN_PAJAK_SPPT = :thnpajakj AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS countunpaiddesa, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovk AND KD_DATI2 = :kdkabk AND KD_KECAMATAN = :kdkeck AND KD_KELURAHAN = :kddesak AND THN_PAJAK_SPPT = :thnpajakk AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS sumunpaiddesa, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovl AND KD_DATI2 = :kdkabl AND KD_KECAMATAN = :kdkecl AND KD_KELURAHAN = :kddesal AND THN_PAJAK_SPPT = :thnpajakl AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS countpaiddesa, (SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovm AND KD_DATI2 = :kdkabm AND KD_KECAMATAN = :kdkecm AND KD_KELURAHAN = :kddesam AND THN_PAJAK_SPPT = :thnpajakm AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS sumpaiddesa, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovn AND KD_DATI2 = :kdkabn AND KD_KECAMATAN = :kdkecn AND KD_KELURAHAN = :kddesan AND THN_PAJAK_SPPT = :thnpajakn AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX > 0) AS countpaidassigned, (SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovo AND KD_DATI2 = :kdkabo AND KD_KECAMATAN = :kdkeco AND KD_KELURAHAN = :kddesao AND THN_PAJAK_SPPT = :thnpajako AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX > 0) AS sumpaidassigned, (SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovp AND KD_DATI2 = :kdkabp AND KD_KECAMATAN = :kdkecp AND KD_KELURAHAN = :kddesap AND THN_PAJAK_SPPT = :thnpajakp AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX > 0) AS countunpaidassigned, (SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovq AND KD_DATI2 = :kdkabq AND KD_KECAMATAN = :kdkecq AND KD_KELURAHAN = :kddesaq AND THN_PAJAK_SPPT = :thnpajakq AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX > 0) AS sumunpaidassigned FROM sppt_data WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak");
																	$stmt->bindValue(":kdprova", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkaba", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkeca", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesaa", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajaka", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovb", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabb", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecb", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesab", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakb", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovc", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabc", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecc", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesac", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakc", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovd", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabd", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecd", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesad", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakd", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprove", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabe", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkece", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesae", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajake", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovf", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabf", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecf", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesaf", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakf", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovg", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabg", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecg", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesag", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakg", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovh", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabh", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkech", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesah", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakh", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovi", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabi", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkeci", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesai", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajaki", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovj", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabj", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecj", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesaj", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakj", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovk", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabk", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkeck", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesak", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakk", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovl", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabl", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecl", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesal", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakl", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovm", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabm", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecm", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesam", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakm", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovn", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabn", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecn", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesan", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakn", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovo", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabo", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkeco", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesao", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajako", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovp", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabp", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecp", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesap", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakp", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovq", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabq", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecq", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesaq", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakq", $thnsppt, PDO::PARAM_STR);
																	$stmt->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
																	$stmt->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
																	$stmt->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
																	$stmt->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
																	$stmt->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
																	$stmt->execute();
																	while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
																		$vtotalcountsppt = $rowset['totalcountsppt']; 
																		$vtotalsumsppt = $rowset['totalsumsppt']; 
																		$vcountallpenetapan = $rowset['countallpenetapan']; 
																		$vcountdefaultpenetapan = $rowset['countdefaultpenetapan']; 
																		$vsumdefaultpenetapan = $rowset['sumdefaultpenetapan'];
																		$vcountlaterpenetapan = $rowset['countlaterpenetapan']; 
																		$vsumlaterpenetapan = $rowset['sumlaterpenetapan'];
																		$vcountunassigned = $rowset['countunassigned']; 
																		$vsumunassigned = $rowset['sumunassigned'];
																		$vcountassigned = $rowset['countassigned']; 
																		$vsumassigned = $rowset['sumassigned'];
																		$vcountunpaiddesa = $rowset['countunpaiddesa']; 
																		$vsumunpaiddesa = $rowset['sumunpaiddesa'];
																		$vcountpaiddesa = $rowset['countpaiddesa']; 
																		$vsumpaiddesa = $rowset['sumpaiddesa'];
																		$vcountunpaidassigned = $rowset['countunpaidassigned']; 
																		$vsumunpaidassigned = $rowset['sumunpaidassigned'];
																		$vcountpaidassigned = $rowset['countpaidassigned']; 
																		$vsumpaidassigned = $rowset['sumpaidassigned'];
																	}
																	/* === CLOSE ALL CONNECTION + AJAX JSON RESPONSE === */
																	$response = array(
																		"ajaxresult"=>"found",
																		"sismiopcountallpenetapan"=>$count_all_penetapan,
																		"sismiopcountdefaultpenetapan"=>$count_default_penetapan,
																		"sismiopcountlaterpenetapan"=>$count_later_penetapan,
																		"sismiopsumdefaultpenetapan"=>$sum_default_penetapan,
																		"sismiopsumlaterpenetapan"=>$sum_later_penetapan,
																		"sismiopcountunpaidsppt"=>$count_unpaid_sppt,
																		"sismiopcountpaidsppt"=>$count_paid_sppt,
																		"sismiopsumallpenetapan"=>$sum_all_penetapan,
																		"sismiopsumunpaidsppt"=>$sum_unpaid_sppt,
																		"sismiopsumpaidsppt"=>$sum_paid_sppt,
																		"kodesppt"=>$kode_sppt,
																		"namakecamatan"=>$namaKecamatan,
																		"namadesa"=>$namaKelurahan,
																		"tahunpajak"=>$thnsppt,
																		"jumlahpersonil"=>$countpersonil,
																		"dhkptotalcountsppt"=>$vtotalcountsppt,
																		"dhkptotalsumsppt"=>$vtotalsumsppt,
																		"dhkpcountallpenetapan"=>$vcountallpenetapan,
																		"dhkpcountdefaultpenetapan"=>$vcountdefaultpenetapan,
																		"dhkpcountlaterpenetapan"=>$vcountlaterpenetapan,
																		"dhkpsumdefaultpenetapan"=>$vsumdefaultpenetapan,
																		"dhkpsumlaterpenetapan"=>$vsumlaterpenetapan,
																		"dhkpcountunassigned"=>$vcountunassigned,
																		"dhkpsumunassigned"=>$vsumunassigned,
																		"dhkpcountassigned"=>$vcountassigned,
																		"dhkpsumassigned"=>$vsumassigned,
																		"dhkpcountunpaiddesa"=>$vcountunpaiddesa,
																		"dhkpsumunpaiddesa"=>$vsumunpaiddesa,
																		"dhkpcountpaiddesa"=>$vcountpaiddesa,
																		"dhkpsumpaiddesa"=>$vsumpaiddesa,
																		"dhkpcountunpaidassigned"=>$vcountunpaidassigned,
																		"dhkpsumunpaidassigned"=>$vsumunpaidassigned,
																		"dhkpcountpaidassigned"=>$vcountpaidassigned,
																		"dhkpsumpaidassigned"=>$vsumpaidassigned,
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
																	/* === /CLOSE ALL CONNECTION + AJAX JSON RESPONSE === */
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
														/* ### /CONNECTION TO MYSQL #### */
													} else {
														oci_free_statement($stmt_sum_paid_sppt);
														oci_close($connection);
														$response = array("ajaxresult"=>"notfound");
														header('Content-Type: application/json');
														echo json_encode($response);
													}
												} else {
													oci_free_statement($stmt_sum_unpaid_sppt);
													oci_close($connection);
													$response = array("ajaxresult"=>"notfound");
													header('Content-Type: application/json');
													echo json_encode($response);
												}
											} else {
												oci_free_statement($stmt_sum_later_penetapan);
												oci_close($connection);
												$response = array("ajaxresult"=>"notfound");
												header('Content-Type: application/json');
												echo json_encode($response);
											}
										} else {
											oci_free_statement($stmt_sum_default_penetapan);
											oci_close($connection);
											$response = array("ajaxresult"=>"notfound");
											header('Content-Type: application/json');
											echo json_encode($response);
										}
									} else {
										oci_free_statement($stmt_sum_all_penetapan);
										oci_close($connection);
										$response = array("ajaxresult"=>"notfound");
										header('Content-Type: application/json');
										echo json_encode($response);
									}
								} else {
									oci_free_statement($stmt_count_paid_sppt);
									oci_close($connection);
									$response = array("ajaxresult"=>"notfound");
									header('Content-Type: application/json');
									echo json_encode($response);
								}
							} else {
								oci_free_statement($stmt_count_unpaid_sppt);
								oci_close($connection);
								$response = array("ajaxresult"=>"notfound");
								header('Content-Type: application/json');
								echo json_encode($response);
							}
						} else {
							oci_free_statement($stmt_count_later_penetapan);
							oci_close($connection);
							$response = array("ajaxresult"=>"notfound");
							header('Content-Type: application/json');
							echo json_encode($response);
						}
					} else {
						oci_free_statement($stmt_count_default_penetapan);
						oci_close($connection);
						$response = array("ajaxresult"=>"notfound");
						header('Content-Type: application/json');
						echo json_encode($response);
					}
				} else {
					oci_free_statement($stmt_count_all_penetapan);
					oci_close($connection);
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
				}
			}
		} elseif ($_GET['cmdx'] == "GET_SISMIOP_DHKP_STATUS_BYTAXYEAR" || $_GET['cmdx'] == "GET_SISMIOP_PEMBAYARAN_STATUS_BYTAXYEAR") {
			$connection = oci_connect("".$appxinfo['_oracle_sismiop_user_']."", "".$appxinfo['_oracle_sismiop_pass_']."", "//".$appxinfo['_oracle_sismiop_host_'].":".$appxinfo['_oracle_sismiop_port_']."/".$appxinfo['_oracle_sismiop_service_']."");
			if (!$connection) {
				$errorMsg = oci_error();
				echo $errorMsg['message'], "\n";
				exit;
			} else {
				$kode_propinsi = $_SESSION['desaXweb']['KD_PROPINSI'];
				$kode_dati2 = $_SESSION['desaXweb']['KD_DATI2'];
				$kode_kecamatan = $_SESSION['desaXweb']['KD_KECAMATAN'];
				$kode_kelurahan = $_SESSION['desaXweb']['KD_KELURAHAN'];
				$namaKecamatan = $_SESSION['desaXweb']['NM_KECAMATAN'];
				$namaKelurahan = $_SESSION['desaXweb']['NM_KELURAHAN'];
				$kode_sppt = strval($kode_propinsi.'.'.$kode_dati2.'.'.$kode_kecamatan.'.'.$kode_kelurahan.'');
				$thnsppt = $_GET['taxyear'];
				$tgldefaultterbitsppt = strval(''.$thnsppt.'-01-02');
				$tglterbitdefault = strval("".$thnsppt."-01-02");
				$query_count_all_penetapan = "SELECT COUNT(*) \"countallpenetapan\" FROM SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL";
				$stmt_count_all_penetapan = oci_parse($connection, $query_count_all_penetapan);
				oci_bind_by_name($stmt_count_all_penetapan, ":KODEPROP", $kode_propinsi);
				oci_bind_by_name($stmt_count_all_penetapan, ":KODEDATI2", $kode_dati2);
				oci_bind_by_name($stmt_count_all_penetapan, ":KODEKEC", $kode_kecamatan);
				oci_bind_by_name($stmt_count_all_penetapan, ":KODEDESKEL", $kode_kelurahan);
				oci_bind_by_name($stmt_count_all_penetapan, ":THNSPPT", $thnsppt);
				if (oci_execute($stmt_count_all_penetapan)) {
					while ($rowset_count_all_penetapan = oci_fetch_array($stmt_count_all_penetapan, OCI_RETURN_NULLS+OCI_ASSOC)) {
						$count_all_penetapan = $rowset_count_all_penetapan['countallpenetapan'];
					}
					oci_free_statement($stmt_count_all_penetapan);
					$query_count_default_penetapan = "SELECT COUNT(*) \"countdefaultpenetapan\" FROM SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL AND TGL_TERBIT_SPPT = TO_DATE(:TGLTERBITSPPDEFAULT,'YYYY-MM-DD')";
					$stmt_count_default_penetapan = oci_parse($connection, $query_count_default_penetapan);
					oci_bind_by_name($stmt_count_default_penetapan, ":KODEPROP", $kode_propinsi);
					oci_bind_by_name($stmt_count_default_penetapan, ":KODEDATI2", $kode_dati2);
					oci_bind_by_name($stmt_count_default_penetapan, ":KODEKEC", $kode_kecamatan);
					oci_bind_by_name($stmt_count_default_penetapan, ":KODEDESKEL", $kode_kelurahan);
					oci_bind_by_name($stmt_count_default_penetapan, ":THNSPPT", $thnsppt);
					oci_bind_by_name($stmt_count_default_penetapan, ":TGLTERBITSPPDEFAULT", $tgldefaultterbitsppt);
					if (oci_execute($stmt_count_default_penetapan)) {
						while ($rowset_count_default_penetapan = oci_fetch_array($stmt_count_default_penetapan, OCI_RETURN_NULLS+OCI_ASSOC)) {
							$count_default_penetapan = $rowset_count_default_penetapan['countdefaultpenetapan'];
						}
						oci_free_statement($stmt_count_default_penetapan);
						$query_count_later_penetapan = "SELECT COUNT(*) \"countlaterpenetapan\" FROM SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL AND TGL_TERBIT_SPPT != TO_DATE(:TGLTERBITSPPDEFAULT,'YYYY-MM-DD')";
						$stmt_count_later_penetapan = oci_parse($connection, $query_count_later_penetapan);
						oci_bind_by_name($stmt_count_later_penetapan, ":KODEPROP", $kode_propinsi);
						oci_bind_by_name($stmt_count_later_penetapan, ":KODEDATI2", $kode_dati2);
						oci_bind_by_name($stmt_count_later_penetapan, ":KODEKEC", $kode_kecamatan);
						oci_bind_by_name($stmt_count_later_penetapan, ":KODEDESKEL", $kode_kelurahan);
						oci_bind_by_name($stmt_count_later_penetapan, ":THNSPPT", $thnsppt);
						oci_bind_by_name($stmt_count_later_penetapan, ":TGLTERBITSPPDEFAULT", $tgldefaultterbitsppt);
						if (oci_execute($stmt_count_later_penetapan)) {
							while ($rowset_count_later_penetapan = oci_fetch_array($stmt_count_later_penetapan, OCI_RETURN_NULLS+OCI_ASSOC)) {
								$count_later_penetapan = $rowset_count_later_penetapan['countlaterpenetapan'];
							}
							oci_free_statement($stmt_count_later_penetapan);
							$query_count_unpaid_sppt = "SELECT COUNT(*) \"countunpaidsppt\" FROM SPPT A WHERE A.THN_PAJAK_SPPT = :THNSPPT AND A.KD_PROPINSI = :KODEPROP AND A.KD_DATI2 = :KODEDATI2 AND A.KD_KECAMATAN = :KODEKEC AND A.KD_KELURAHAN = :KODEDESKEL AND A.STATUS_PEMBAYARAN_SPPT = 0";
							$stmt_count_unpaid_sppt = oci_parse($connection, $query_count_unpaid_sppt);
							oci_bind_by_name($stmt_count_unpaid_sppt, ":KODEPROP", $kode_propinsi);
							oci_bind_by_name($stmt_count_unpaid_sppt, ":KODEDATI2", $kode_dati2);
							oci_bind_by_name($stmt_count_unpaid_sppt, ":KODEKEC", $kode_kecamatan);
							oci_bind_by_name($stmt_count_unpaid_sppt, ":KODEDESKEL", $kode_kelurahan);
							oci_bind_by_name($stmt_count_unpaid_sppt, ":THNSPPT", $thnsppt);
							if (oci_execute($stmt_count_unpaid_sppt)) {
								while ($rowset_count_unpaid_sppt = oci_fetch_array($stmt_count_unpaid_sppt, OCI_RETURN_NULLS+OCI_ASSOC)) {
									$count_unpaid_sppt = $rowset_count_unpaid_sppt['countunpaidsppt'];
								}
								oci_free_statement($stmt_count_unpaid_sppt);
								$query_count_paid_sppt = "SELECT COUNT(*) \"countpaidsppt\" FROM SPPT A WHERE A.THN_PAJAK_SPPT = :THNSPPT AND A.KD_PROPINSI = :KODEPROP AND A.KD_DATI2 = :KODEDATI2 AND A.KD_KECAMATAN = :KODEKEC AND A.KD_KELURAHAN = :KODEDESKEL AND A.STATUS_PEMBAYARAN_SPPT != 0";
								$stmt_count_paid_sppt = oci_parse($connection, $query_count_paid_sppt);
								oci_bind_by_name($stmt_count_paid_sppt, ":KODEPROP", $kode_propinsi);
								oci_bind_by_name($stmt_count_paid_sppt, ":KODEDATI2", $kode_dati2);
								oci_bind_by_name($stmt_count_paid_sppt, ":KODEKEC", $kode_kecamatan);
								oci_bind_by_name($stmt_count_paid_sppt, ":KODEDESKEL", $kode_kelurahan);
								oci_bind_by_name($stmt_count_paid_sppt, ":THNSPPT", $thnsppt);
								if (oci_execute($stmt_count_paid_sppt)) {
									while ($rowset_count_paid_sppt = oci_fetch_array($stmt_count_paid_sppt, OCI_RETURN_NULLS+OCI_ASSOC)) {
										$count_paid_sppt = $rowset_count_paid_sppt['countpaidsppt'];
									}
									oci_free_statement($stmt_count_paid_sppt);
									$query_sum_all_penetapan = "SELECT NVL(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) \"sumallpenetapan\" FROM SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL";
									$stmt_sum_all_penetapan = oci_parse($connection, $query_sum_all_penetapan);
									oci_bind_by_name($stmt_sum_all_penetapan, ":KODEPROP", $kode_propinsi);
									oci_bind_by_name($stmt_sum_all_penetapan, ":KODEDATI2", $kode_dati2);
									oci_bind_by_name($stmt_sum_all_penetapan, ":KODEKEC", $kode_kecamatan);
									oci_bind_by_name($stmt_sum_all_penetapan, ":KODEDESKEL", $kode_kelurahan);
									oci_bind_by_name($stmt_sum_all_penetapan, ":THNSPPT", $thnsppt);
									if (oci_execute($stmt_sum_all_penetapan)) {
										while ($rowset_sum_all_penetapan = oci_fetch_array($stmt_sum_all_penetapan, OCI_RETURN_NULLS+OCI_ASSOC)) {
											$sum_all_penetapan = $rowset_sum_all_penetapan['sumallpenetapan'];
										}
										oci_free_statement($stmt_sum_all_penetapan);
										$query_sum_default_penetapan = "SELECT NVL(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) \"sumdefaultpenetapan\" FROM SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL AND TGL_TERBIT_SPPT = TO_DATE(:TGLTERBITSPPDEFAULT,'YYYY-MM-DD')";
										$stmt_sum_default_penetapan = oci_parse($connection, $query_sum_default_penetapan);
										oci_bind_by_name($stmt_sum_default_penetapan, ":KODEPROP", $kode_propinsi);
										oci_bind_by_name($stmt_sum_default_penetapan, ":KODEDATI2", $kode_dati2);
										oci_bind_by_name($stmt_sum_default_penetapan, ":KODEKEC", $kode_kecamatan);
										oci_bind_by_name($stmt_sum_default_penetapan, ":KODEDESKEL", $kode_kelurahan);
										oci_bind_by_name($stmt_sum_default_penetapan, ":THNSPPT", $thnsppt);
										oci_bind_by_name($stmt_sum_default_penetapan, ":TGLTERBITSPPDEFAULT", $tgldefaultterbitsppt);
										if (oci_execute($stmt_sum_default_penetapan)) {
											while ($rowset_sum_default_penetapan = oci_fetch_array($stmt_sum_default_penetapan, OCI_RETURN_NULLS+OCI_ASSOC)) {
												$sum_default_penetapan = $rowset_sum_default_penetapan['sumdefaultpenetapan'];
											}
											oci_free_statement($stmt_sum_default_penetapan);
											$query_sum_later_penetapan = "SELECT NVL(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) \"sumlaterpenetapan\" FROM SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL AND TGL_TERBIT_SPPT != TO_DATE(:TGLTERBITSPPDEFAULT,'YYYY-MM-DD')";
											$stmt_sum_later_penetapan = oci_parse($connection, $query_sum_later_penetapan);
											oci_bind_by_name($stmt_sum_later_penetapan, ":KODEPROP", $kode_propinsi);
											oci_bind_by_name($stmt_sum_later_penetapan, ":KODEDATI2", $kode_dati2);
											oci_bind_by_name($stmt_sum_later_penetapan, ":KODEKEC", $kode_kecamatan);
											oci_bind_by_name($stmt_sum_later_penetapan, ":KODEDESKEL", $kode_kelurahan);
											oci_bind_by_name($stmt_sum_later_penetapan, ":THNSPPT", $thnsppt);
											oci_bind_by_name($stmt_sum_later_penetapan, ":TGLTERBITSPPDEFAULT", $tgldefaultterbitsppt);
											if (oci_execute($stmt_sum_later_penetapan)) {
												while ($rowset_sum_later_penetapan = oci_fetch_array($stmt_sum_later_penetapan, OCI_RETURN_NULLS+OCI_ASSOC)) {
													$sum_later_penetapan = $rowset_sum_later_penetapan['sumlaterpenetapan'];
												}
												oci_free_statement($stmt_sum_later_penetapan);
												$query_sum_unpaid_sppt = "SELECT NVL(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) \"sumunpaidsppt\" FROM SPPT A WHERE A.THN_PAJAK_SPPT = :THNSPPT AND A.KD_PROPINSI = :KODEPROP AND A.KD_DATI2 = :KODEDATI2 AND A.KD_KECAMATAN = :KODEKEC AND A.KD_KELURAHAN = :KODEDESKEL AND A.STATUS_PEMBAYARAN_SPPT = 0";
												$stmt_sum_unpaid_sppt = oci_parse($connection, $query_sum_unpaid_sppt);
												oci_bind_by_name($stmt_sum_unpaid_sppt, ":KODEPROP", $kode_propinsi);
												oci_bind_by_name($stmt_sum_unpaid_sppt, ":KODEDATI2", $kode_dati2);
												oci_bind_by_name($stmt_sum_unpaid_sppt, ":KODEKEC", $kode_kecamatan);
												oci_bind_by_name($stmt_sum_unpaid_sppt, ":KODEDESKEL", $kode_kelurahan);
												oci_bind_by_name($stmt_sum_unpaid_sppt, ":THNSPPT", $thnsppt);
												if (oci_execute($stmt_sum_unpaid_sppt)) {
													while ($rowset_sum_unpaid_sppt = oci_fetch_array($stmt_sum_unpaid_sppt, OCI_RETURN_NULLS+OCI_ASSOC)) {
														$sum_unpaid_sppt = $rowset_sum_unpaid_sppt['sumunpaidsppt'];
													}
													oci_free_statement($stmt_sum_unpaid_sppt);
													$query_sum_paid_sppt = "SELECT NVL(SUM(JML_SPPT_YG_DIBAYAR), 0) \"sumpaidsppt\" FROM PEMBAYARAN_SPPT A WHERE A.THN_PAJAK_SPPT = :THNSPPT AND A.KD_PROPINSI = :KODEPROP AND A.KD_DATI2 = :KODEDATI2 AND A.KD_KECAMATAN = :KODEKEC AND A.KD_KELURAHAN = :KODEDESKEL";
													$stmt_sum_paid_sppt = oci_parse($connection, $query_sum_paid_sppt);
													oci_bind_by_name($stmt_sum_paid_sppt, ":KODEPROP", $kode_propinsi);
													oci_bind_by_name($stmt_sum_paid_sppt, ":KODEDATI2", $kode_dati2);
													oci_bind_by_name($stmt_sum_paid_sppt, ":KODEKEC", $kode_kecamatan);
													oci_bind_by_name($stmt_sum_paid_sppt, ":KODEDESKEL", $kode_kelurahan);
													oci_bind_by_name($stmt_sum_paid_sppt, ":THNSPPT", $thnsppt);
													if (oci_execute($stmt_sum_paid_sppt)) {
														while ($rowset_sum_paid_sppt = oci_fetch_array($stmt_sum_paid_sppt, OCI_RETURN_NULLS+OCI_ASSOC)) {
															$sum_paid_sppt = $rowset_sum_paid_sppt['sumpaidsppt'];
														}
														oci_free_statement($stmt_sum_paid_sppt);
														oci_close($connection);
														/* ### CONNECTION TO LOCAL DHKP MYSQL #### */
														try {
															$dbcon = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
															$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
															/* counting data */
															$stmt_cnt_personil = $dbcon->prepare("SELECT COUNT(*) as foundpetugas FROM appx_desa_petugas_pungut WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa");
															$stmt_cnt_personil->bindValue(":kdprov", $_SESSION['desaXweb']['KD_PROPINSI'], PDO::PARAM_STR);
															$stmt_cnt_personil->bindValue(":kdkab", $_SESSION['desaXweb']['KD_DATI2'], PDO::PARAM_STR);
															$stmt_cnt_personil->bindValue(":kdkec", $_SESSION['desaXweb']['KD_KECAMATAN'], PDO::PARAM_STR);
															$stmt_cnt_personil->bindValue(":kddesa", $_SESSION['desaXweb']['KD_KELURAHAN'], PDO::PARAM_STR);
															if ($stmt_cnt_personil->execute()) {
																while($rowset_cnt_personil = $stmt_cnt_personil->fetch(PDO::FETCH_ASSOC)){
																	$countpersonil = $rowset_cnt_personil['foundpetugas'];
																}
															} else {
																$countpersonil = 0;
															}
															/* BUILD DHKP - EXCLUDE SPPT YANG SUDAH MENGALAMI PEMUTAKHIRAN */
															/* ========== TEMP =========== */
															$stmt_create_dhkp_temporary_sppt_data = $dbcon->prepare("CREATE TEMPORARY TABLE dhkp_temporary_sppt_data(KD_PROPINSI char(2) NOT NULL,KD_DATI2 char(2) NOT NULL,KD_KECAMATAN char(3) NOT NULL,KD_KELURAHAN char(3) NOT NULL,KD_BLOK char(3) NOT NULL,NO_URUT char(4) NOT NULL,KD_JNS_OP char(1) NOT NULL,THN_PAJAK_SPPT char(4) NOT NULL,SIKLUS_SPPT int(10) NOT NULL DEFAULT '0',KD_KANWIL_BANK varchar(2) NOT NULL DEFAULT '',KD_KPPBB_BANK varchar(2) NOT NULL DEFAULT '',KD_BANK_TUNGGAL varchar(2) NOT NULL DEFAULT '',KD_BANK_PERSEPSI varchar(2) NOT NULL DEFAULT '',KD_TP varchar(2) NOT NULL DEFAULT '',NM_WP_SPPT varchar(255) NOT NULL DEFAULT '',JLN_WP_SPPT varchar(255) NOT NULL DEFAULT '',BLOK_KAV_NO_WP_SPPT varchar(64) NOT NULL DEFAULT '',RW_WP_SPPT varchar(3) NOT NULL DEFAULT '',RT_WP_SPPT varchar(3) NOT NULL DEFAULT '',KELURAHAN_WP_SPPT varchar(64) NOT NULL DEFAULT '',KOTA_WP_SPPT varchar(64) NOT NULL DEFAULT '',KD_POS_WP_SPPT varchar(5) NOT NULL DEFAULT '',NPWP_SPPT varchar(16) NOT NULL DEFAULT '',NO_PERSIL_SPPT varchar(32) NOT NULL DEFAULT '',KD_KLS_TANAH varchar(8) NOT NULL DEFAULT '',THN_AWAL_KLS_TANAH varchar(4) NOT NULL DEFAULT '',KD_KLS_BNG varchar(8) NOT NULL DEFAULT '',THN_AWAL_KLS_BNG varchar(4) NOT NULL DEFAULT '',TGL_JATUH_TEMPO_SPPT date NOT NULL DEFAULT '1900-01-01',LUAS_BUMI_SPPT decimal(10,2) NOT NULL DEFAULT '0.00',LUAS_BNG_SPPT decimal(10,2) NOT NULL DEFAULT '0.00',NJOP_BUMI_SPPT bigint(20) NOT NULL DEFAULT '0',NJOP_BNG_SPPT bigint(20) NOT NULL DEFAULT '0',NJOP_SPPT bigint(20) NOT NULL DEFAULT '0',NJOPTKP_SPPT bigint(20) NOT NULL DEFAULT '0',NJKP_SPPT bigint(20) NOT NULL DEFAULT '0',PBB_TERHUTANG_SPPT bigint(20) NOT NULL DEFAULT '0',FAKTOR_PENGURANG_SPPT int(10) NOT NULL DEFAULT '0',PBB_YG_HARUS_DIBAYAR_SPPT bigint(20) NOT NULL DEFAULT '0',STATUS_PEMBAYARAN_SPPT varchar(4) NOT NULL DEFAULT '',STATUS_TAGIHAN_SPPT varchar(4) NOT NULL DEFAULT '',STATUS_CETAK_SPPT varchar(4) NOT NULL DEFAULT '',TGL_TERBIT_SPPT date NOT NULL DEFAULT '1900-01-01',TGL_CETAK_SPPT date NOT NULL DEFAULT '1900-01-01',NIP_PENCETAK_SPPT varchar(16) NOT NULL DEFAULT '',PEMBAYARAN_SPPT_KE int(10) NOT NULL DEFAULT '0',DENDA_SPPT decimal(10,2) NOT NULL DEFAULT '0.00',JML_SPPT_YG_DIBAYAR bigint(20) NOT NULL DEFAULT '0',TGL_PEMBAYARAN_SPPT date NOT NULL DEFAULT '1900-01-01',TGL_REKAM_BYR_SPPT date NOT NULL DEFAULT '1900-01-01',NIP_REKAM_BYR_SPPT varchar(16) NOT NULL DEFAULT '',PETUGASIDX int(10) NOT NULL DEFAULT '0',NAMAPETUGAS varchar(64) NOT NULL DEFAULT '',STATUS int(10) NOT NULL DEFAULT '0',PRIMARY KEY (KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,TGL_TERBIT_SPPT),INDEX KD_PROPINSI (KD_PROPINSI),INDEX KD_DATI2 (KD_DATI2),INDEX KD_KECAMATAN (KD_KECAMATAN),INDEX KD_KELURAHAN (KD_KELURAHAN),INDEX KD_BLOK (KD_BLOK),INDEX NO_URUT (NO_URUT),INDEX KD_JNS_OP (KD_JNS_OP),INDEX THN_PAJAK_SPPT (THN_PAJAK_SPPT),INDEX KELURAHAN_WP_SPPT (KELURAHAN_WP_SPPT),INDEX TGL_TERBIT_SPPT (TGL_TERBIT_SPPT),INDEX TGL_PEMBAYARAN_SPPT (TGL_PEMBAYARAN_SPPT),INDEX PETUGASIDX (PETUGASIDX)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
															$stmt_create_dhkp_temporary_sppt_data->execute();
															$stmt_create_dhkp_temporary_neu_sppt_data = $dbcon->prepare("CREATE TEMPORARY TABLE dhkp_temporary_neu_sppt_data(KD_PROPINSI char(2) NOT NULL,KD_DATI2 char(2) NOT NULL,KD_KECAMATAN char(3) NOT NULL,KD_KELURAHAN char(3) NOT NULL,KD_BLOK char(3) NOT NULL,NO_URUT char(4) NOT NULL,KD_JNS_OP char(1) NOT NULL,THN_PAJAK_SPPT char(4) NOT NULL,SIKLUS_SPPT int(10) NOT NULL DEFAULT '0',KD_KANWIL_BANK varchar(2) NOT NULL DEFAULT '',KD_KPPBB_BANK varchar(2) NOT NULL DEFAULT '',KD_BANK_TUNGGAL varchar(2) NOT NULL DEFAULT '',KD_BANK_PERSEPSI varchar(2) NOT NULL DEFAULT '',KD_TP varchar(2) NOT NULL DEFAULT '',NM_WP_SPPT varchar(255) NOT NULL DEFAULT '',JLN_WP_SPPT varchar(255) NOT NULL DEFAULT '',BLOK_KAV_NO_WP_SPPT varchar(64) NOT NULL DEFAULT '',RW_WP_SPPT varchar(3) NOT NULL DEFAULT '',RT_WP_SPPT varchar(3) NOT NULL DEFAULT '',KELURAHAN_WP_SPPT varchar(64) NOT NULL DEFAULT '',KOTA_WP_SPPT varchar(64) NOT NULL DEFAULT '',KD_POS_WP_SPPT varchar(5) NOT NULL DEFAULT '',NPWP_SPPT varchar(16) NOT NULL DEFAULT '',NO_PERSIL_SPPT varchar(32) NOT NULL DEFAULT '',KD_KLS_TANAH varchar(8) NOT NULL DEFAULT '',THN_AWAL_KLS_TANAH varchar(4) NOT NULL DEFAULT '',KD_KLS_BNG varchar(8) NOT NULL DEFAULT '',THN_AWAL_KLS_BNG varchar(4) NOT NULL DEFAULT '',TGL_JATUH_TEMPO_SPPT date NOT NULL DEFAULT '1900-01-01',LUAS_BUMI_SPPT decimal(10,2) NOT NULL DEFAULT '0.00',LUAS_BNG_SPPT decimal(10,2) NOT NULL DEFAULT '0.00',NJOP_BUMI_SPPT bigint(20) NOT NULL DEFAULT '0',NJOP_BNG_SPPT bigint(20) NOT NULL DEFAULT '0',NJOP_SPPT bigint(20) NOT NULL DEFAULT '0',NJOPTKP_SPPT bigint(20) NOT NULL DEFAULT '0',NJKP_SPPT bigint(20) NOT NULL DEFAULT '0',PBB_TERHUTANG_SPPT bigint(20) NOT NULL DEFAULT '0',FAKTOR_PENGURANG_SPPT int(10) NOT NULL DEFAULT '0',PBB_YG_HARUS_DIBAYAR_SPPT bigint(20) NOT NULL DEFAULT '0',STATUS_PEMBAYARAN_SPPT varchar(4) NOT NULL DEFAULT '',STATUS_TAGIHAN_SPPT varchar(4) NOT NULL DEFAULT '',STATUS_CETAK_SPPT varchar(4) NOT NULL DEFAULT '',TGL_TERBIT_SPPT date NOT NULL DEFAULT '1900-01-01',TGL_CETAK_SPPT date NOT NULL DEFAULT '1900-01-01',NIP_PENCETAK_SPPT varchar(16) NOT NULL DEFAULT '',PEMBAYARAN_SPPT_KE int(10) NOT NULL DEFAULT '0',DENDA_SPPT decimal(10,2) NOT NULL DEFAULT '0.00',JML_SPPT_YG_DIBAYAR bigint(20) NOT NULL DEFAULT '0',TGL_PEMBAYARAN_SPPT date NOT NULL DEFAULT '1900-01-01',TGL_REKAM_BYR_SPPT date NOT NULL DEFAULT '1900-01-01',NIP_REKAM_BYR_SPPT varchar(16) NOT NULL DEFAULT '',PETUGASIDX int(10) NOT NULL DEFAULT '0',NAMAPETUGAS varchar(64) NOT NULL DEFAULT '',STATUS int(10) NOT NULL DEFAULT '0',PRIMARY KEY (KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,TGL_TERBIT_SPPT),INDEX KD_PROPINSI (KD_PROPINSI),INDEX KD_DATI2 (KD_DATI2),INDEX KD_KECAMATAN (KD_KECAMATAN),INDEX KD_KELURAHAN (KD_KELURAHAN),INDEX KD_BLOK (KD_BLOK),INDEX NO_URUT (NO_URUT),INDEX KD_JNS_OP (KD_JNS_OP),INDEX THN_PAJAK_SPPT (THN_PAJAK_SPPT),INDEX KELURAHAN_WP_SPPT (KELURAHAN_WP_SPPT),INDEX TGL_TERBIT_SPPT (TGL_TERBIT_SPPT),INDEX TGL_PEMBAYARAN_SPPT (TGL_PEMBAYARAN_SPPT),INDEX PETUGASIDX (PETUGASIDX)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
															$stmt_create_dhkp_temporary_neu_sppt_data->execute();
															/* Insert Data to temporary SPPT */
															$stmt_insert_sppt_data = $dbcon->prepare("INSERT INTO dhkp_temporary_sppt_data(KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,KD_KANWIL_BANK,KD_KPPBB_BANK,KD_BANK_TUNGGAL,KD_BANK_PERSEPSI,KD_TP,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,KELURAHAN_WP_SPPT,KOTA_WP_SPPT,KD_POS_WP_SPPT,NPWP_SPPT,NO_PERSIL_SPPT,KD_KLS_TANAH,THN_AWAL_KLS_TANAH,KD_KLS_BNG,THN_AWAL_KLS_BNG,TGL_JATUH_TEMPO_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,NJOP_BUMI_SPPT,NJOP_BNG_SPPT,NJOP_SPPT,NJOPTKP_SPPT,NJKP_SPPT,PBB_TERHUTANG_SPPT,FAKTOR_PENGURANG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,STATUS_TAGIHAN_SPPT,STATUS_CETAK_SPPT,TGL_TERBIT_SPPT,TGL_CETAK_SPPT,NIP_PENCETAK_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,TGL_REKAM_BYR_SPPT,NIP_REKAM_BYR_SPPT,PETUGASIDX,NAMAPETUGAS,STATUS) SELECT KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,KD_KANWIL_BANK,KD_KPPBB_BANK,KD_BANK_TUNGGAL,KD_BANK_PERSEPSI,KD_TP,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,KELURAHAN_WP_SPPT,KOTA_WP_SPPT,KD_POS_WP_SPPT,NPWP_SPPT,NO_PERSIL_SPPT,KD_KLS_TANAH,THN_AWAL_KLS_TANAH,KD_KLS_BNG,THN_AWAL_KLS_BNG,TGL_JATUH_TEMPO_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,NJOP_BUMI_SPPT,NJOP_BNG_SPPT,NJOP_SPPT,NJOPTKP_SPPT,NJKP_SPPT,PBB_TERHUTANG_SPPT,FAKTOR_PENGURANG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,STATUS_TAGIHAN_SPPT,STATUS_CETAK_SPPT,TGL_TERBIT_SPPT,TGL_CETAK_SPPT,NIP_PENCETAK_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,TGL_REKAM_BYR_SPPT,NIP_REKAM_BYR_SPPT,PETUGASIDX,NAMAPETUGAS,STATUS FROM sppt_data FORCE INDEX(KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,TGL_TERBIT_SPPT) WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND TGL_TERBIT_SPPT = :defaulttglterbit ORDER BY NO_URUT, KD_JNS_OP");
															$stmt_insert_sppt_data->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
															$stmt_insert_sppt_data->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
															$stmt_insert_sppt_data->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
															$stmt_insert_sppt_data->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
															$stmt_insert_sppt_data->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
															$stmt_insert_sppt_data->bindValue(":defaulttglterbit", $tglterbitdefault, PDO::PARAM_STR);
															$stmt_insert_sppt_data->execute();
															/* Insert Data to temporary NEU SPPT */
															$stmt_insert_neu_sppt_data = $dbcon->prepare("INSERT INTO dhkp_temporary_neu_sppt_data(KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,KD_KANWIL_BANK,KD_KPPBB_BANK,KD_BANK_TUNGGAL,KD_BANK_PERSEPSI,KD_TP,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,KELURAHAN_WP_SPPT,KOTA_WP_SPPT,KD_POS_WP_SPPT,NPWP_SPPT,NO_PERSIL_SPPT,KD_KLS_TANAH,THN_AWAL_KLS_TANAH,KD_KLS_BNG,THN_AWAL_KLS_BNG,TGL_JATUH_TEMPO_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,NJOP_BUMI_SPPT,NJOP_BNG_SPPT,NJOP_SPPT,NJOPTKP_SPPT,NJKP_SPPT,PBB_TERHUTANG_SPPT,FAKTOR_PENGURANG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,STATUS_TAGIHAN_SPPT,STATUS_CETAK_SPPT,TGL_TERBIT_SPPT,TGL_CETAK_SPPT,NIP_PENCETAK_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,TGL_REKAM_BYR_SPPT,NIP_REKAM_BYR_SPPT,PETUGASIDX,NAMAPETUGAS,STATUS) SELECT KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,KD_KANWIL_BANK,KD_KPPBB_BANK,KD_BANK_TUNGGAL,KD_BANK_PERSEPSI,KD_TP,NM_WP_SPPT,JLN_WP_SPPT,BLOK_KAV_NO_WP_SPPT,RW_WP_SPPT,RT_WP_SPPT,KELURAHAN_WP_SPPT,KOTA_WP_SPPT,KD_POS_WP_SPPT,NPWP_SPPT,NO_PERSIL_SPPT,KD_KLS_TANAH,THN_AWAL_KLS_TANAH,KD_KLS_BNG,THN_AWAL_KLS_BNG,TGL_JATUH_TEMPO_SPPT,LUAS_BUMI_SPPT,LUAS_BNG_SPPT,NJOP_BUMI_SPPT,NJOP_BNG_SPPT,NJOP_SPPT,NJOPTKP_SPPT,NJKP_SPPT,PBB_TERHUTANG_SPPT,FAKTOR_PENGURANG_SPPT,PBB_YG_HARUS_DIBAYAR_SPPT,STATUS_PEMBAYARAN_SPPT,STATUS_TAGIHAN_SPPT,STATUS_CETAK_SPPT,TGL_TERBIT_SPPT,TGL_CETAK_SPPT,NIP_PENCETAK_SPPT,PEMBAYARAN_SPPT_KE,DENDA_SPPT,JML_SPPT_YG_DIBAYAR,TGL_PEMBAYARAN_SPPT,TGL_REKAM_BYR_SPPT,NIP_REKAM_BYR_SPPT,PETUGASIDX,NAMAPETUGAS,STATUS FROM sppt_data FORCE INDEX(KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,KD_BLOK,NO_URUT,KD_JNS_OP,THN_PAJAK_SPPT,TGL_TERBIT_SPPT) WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak AND TGL_TERBIT_SPPT > :defaulttglterbit ORDER BY NO_URUT, KD_JNS_OP");
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
															/* BUILD DHKP - EXCLUDE SPPT YANG SUDAH MENGALAMI PEMUTAKHIRAN */
															$stmt_cnt = $dbcon->prepare("SELECT COUNT(*) as foundrows FROM sppt_data FORCE INDEX(KD_PROPINSI,KD_DATI2,KD_KECAMATAN,KD_KELURAHAN,THN_PAJAK_SPPT) WHERE KD_PROPINSI = :kdprov AND KD_DATI2 = :kdkab AND KD_KECAMATAN = :kdkec AND KD_KELURAHAN = :kddesa AND THN_PAJAK_SPPT = :thnpajak");
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
																	$stmt = $dbcon->prepare("
																		SELECT COUNT(*) AS totalcountsppt, 
																			COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) AS totalsumsppt, 
																			(SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprova AND KD_DATI2 = :kdkaba AND KD_KECAMATAN = :kdkeca AND KD_KELURAHAN = :kddesaa AND THN_PAJAK_SPPT = :thnpajaka) AS countallpenetapan, 
																			(SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovb AND KD_DATI2 = :kdkabb AND KD_KECAMATAN = :kdkecb AND KD_KELURAHAN = :kddesab AND THN_PAJAK_SPPT = :thnpajakb AND TGL_TERBIT_SPPT = '2017-01-02') AS countdefaultpenetapan, 
																			(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovc AND KD_DATI2 = :kdkabc AND KD_KECAMATAN = :kdkecc AND KD_KELURAHAN = :kddesac AND THN_PAJAK_SPPT = :thnpajakc AND TGL_TERBIT_SPPT = '2017-01-02') AS sumdefaultpenetapan, 
																			(SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovd AND KD_DATI2 = :kdkabd AND KD_KECAMATAN = :kdkecd AND KD_KELURAHAN = :kddesad AND THN_PAJAK_SPPT = :thnpajakd AND TGL_TERBIT_SPPT != '2017-01-02') AS countlaterpenetapan, 
																			(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprove AND KD_DATI2 = :kdkabe AND KD_KECAMATAN = :kdkece AND KD_KELURAHAN = :kddesae AND THN_PAJAK_SPPT = :thnpajake AND TGL_TERBIT_SPPT != '2017-01-02') AS sumlaterpenetapan, 
																			(SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovf AND KD_DATI2 = :kdkabf AND KD_KECAMATAN = :kdkecf AND KD_KELURAHAN = :kddesaf AND THN_PAJAK_SPPT = :thnpajakf AND PETUGASIDX = 0) AS countunassigned, 
																			(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovg AND KD_DATI2 = :kdkabg AND KD_KECAMATAN = :kdkecg AND KD_KELURAHAN = :kddesag AND THN_PAJAK_SPPT = :thnpajakg AND PETUGASIDX = 0) AS sumunassigned, 
																			(SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovh AND KD_DATI2 = :kdkabh AND KD_KECAMATAN = :kdkech AND KD_KELURAHAN = :kddesah AND THN_PAJAK_SPPT = :thnpajakh AND PETUGASIDX > 0) AS countassigned, 
																			(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovi AND KD_DATI2 = :kdkabi AND KD_KECAMATAN = :kdkeci AND KD_KELURAHAN = :kddesai AND THN_PAJAK_SPPT = :thnpajaki AND PETUGASIDX > 0) AS sumassigned, 
																			(SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovj AND KD_DATI2 = :kdkabj AND KD_KECAMATAN = :kdkecj AND KD_KELURAHAN = :kddesaj AND THN_PAJAK_SPPT = :thnpajakj AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS countunpaiddesa, 
																			(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovk AND KD_DATI2 = :kdkabk AND KD_KECAMATAN = :kdkeck AND KD_KELURAHAN = :kddesak AND THN_PAJAK_SPPT = :thnpajakk AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01') AS sumunpaiddesa, 
																			(SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovl AND KD_DATI2 = :kdkabl AND KD_KECAMATAN = :kdkecl AND KD_KELURAHAN = :kddesal AND THN_PAJAK_SPPT = :thnpajakl AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS countpaiddesa, 
																			(SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovm AND KD_DATI2 = :kdkabm AND KD_KECAMATAN = :kdkecm AND KD_KELURAHAN = :kddesam AND THN_PAJAK_SPPT = :thnpajakm AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01') AS sumpaiddesa, 
																			(SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovn AND KD_DATI2 = :kdkabn AND KD_KECAMATAN = :kdkecn AND KD_KELURAHAN = :kddesan AND THN_PAJAK_SPPT = :thnpajakn AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX > 0) AS countpaidassigned, 
																			(SELECT COALESCE(SUM(JML_SPPT_YG_DIBAYAR), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovo AND KD_DATI2 = :kdkabo AND KD_KECAMATAN = :kdkeco AND KD_KELURAHAN = :kddesao AND THN_PAJAK_SPPT = :thnpajako AND JML_SPPT_YG_DIBAYAR > 0 AND TGL_PEMBAYARAN_SPPT != '1900-01-01' AND PETUGASIDX > 0) AS sumpaidassigned, 
																			(SELECT COUNT(*) FROM sppt_data WHERE KD_PROPINSI = :kdprovp AND KD_DATI2 = :kdkabp AND KD_KECAMATAN = :kdkecp AND KD_KELURAHAN = :kddesap AND THN_PAJAK_SPPT = :thnpajakp AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX > 0) AS countunpaidassigned, 
																			(SELECT COALESCE(SUM(PBB_YG_HARUS_DIBAYAR_SPPT), 0) FROM sppt_data WHERE KD_PROPINSI = :kdprovq AND KD_DATI2 = :kdkabq AND KD_KECAMATAN = :kdkecq AND KD_KELURAHAN = :kddesaq AND THN_PAJAK_SPPT = :thnpajakq AND JML_SPPT_YG_DIBAYAR = 0 AND TGL_PEMBAYARAN_SPPT = '1900-01-01' AND PETUGASIDX > 0) AS sumunpaidassigned 
																		FROM sppt_data 
																		WHERE 
																			KD_PROPINSI = :kdprov 
																			AND KD_DATI2 = :kdkab 
																			AND KD_KECAMATAN = :kdkec 
																			AND KD_KELURAHAN = :kddesa 
																			AND THN_PAJAK_SPPT = :thnpajak");
																	$stmt->bindValue(":kdprova", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkaba", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkeca", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesaa", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajaka", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovb", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabb", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecb", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesab", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakb", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovc", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabc", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecc", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesac", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakc", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovd", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabd", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecd", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesad", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakd", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprove", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabe", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkece", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesae", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajake", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovf", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabf", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecf", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesaf", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakf", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovg", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabg", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecg", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesag", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakg", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovh", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabh", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkech", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesah", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakh", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovi", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabi", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkeci", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesai", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajaki", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovj", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabj", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecj", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesaj", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakj", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovk", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabk", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkeck", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesak", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakk", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovl", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabl", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecl", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesal", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakl", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovm", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabm", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecm", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesam", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakm", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovn", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabn", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecn", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesan", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakn", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovo", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabo", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkeco", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesao", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajako", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovp", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabp", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecp", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesap", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakp", $thnsppt, PDO::PARAM_STR); $stmt->bindValue(":kdprovq", $kode_propinsi, PDO::PARAM_STR); $stmt->bindValue(":kdkabq", $kode_dati2, PDO::PARAM_STR); $stmt->bindValue(":kdkecq", $kode_kecamatan, PDO::PARAM_STR); $stmt->bindValue(":kddesaq", $kode_kelurahan, PDO::PARAM_STR); $stmt->bindValue(":thnpajakq", $thnsppt, PDO::PARAM_STR);
																	$stmt->bindValue(":kdprov", $kode_propinsi, PDO::PARAM_STR);
																	$stmt->bindValue(":kdkab", $kode_dati2, PDO::PARAM_STR);
																	$stmt->bindValue(":kdkec", $kode_kecamatan, PDO::PARAM_STR);
																	$stmt->bindValue(":kddesa", $kode_kelurahan, PDO::PARAM_STR);
																	$stmt->bindValue(":thnpajak", $thnsppt, PDO::PARAM_STR);
																	$stmt->execute();
																	while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
																		$vtotalcountsppt = $rowset['totalcountsppt']; 
																		$vtotalsumsppt = $rowset['totalsumsppt']; 
																		$vcountallpenetapan = $rowset['countallpenetapan']; 
																		$vcountdefaultpenetapan = $rowset['countdefaultpenetapan']; 
																		$vsumdefaultpenetapan = $rowset['sumdefaultpenetapan'];
																		$vcountlaterpenetapan = $rowset['countlaterpenetapan']; 
																		$vsumlaterpenetapan = $rowset['sumlaterpenetapan'];
																		$vcountunassigned = $rowset['countunassigned']; 
																		$vsumunassigned = $rowset['sumunassigned'];
																		$vcountassigned = $rowset['countassigned']; 
																		$vsumassigned = $rowset['sumassigned'];
																		$vcountunpaiddesa = $rowset['countunpaiddesa']; 
																		$vsumunpaiddesa = $rowset['sumunpaiddesa'];
																		$vcountpaiddesa = $rowset['countpaiddesa']; 
																		$vsumpaiddesa = $rowset['sumpaiddesa'];
																		$vcountunpaidassigned = $rowset['countunpaidassigned']; 
																		$vsumunpaidassigned = $rowset['sumunpaidassigned'];
																		$vcountpaidassigned = $rowset['countpaidassigned']; 
																		$vsumpaidassigned = $rowset['sumpaidassigned'];
																	}
																	/* === CLOSE ALL CONNECTION + AJAX JSON RESPONSE === */
																	$response = array(
																		"ajaxresult"=>"found",
																		"sismiopcountallpenetapan"=>$count_all_penetapan,
																		"sismiopcountdefaultpenetapan"=>$count_default_penetapan,
																		"sismiopcountlaterpenetapan"=>$count_later_penetapan,
																		"sismiopsumdefaultpenetapan"=>$sum_default_penetapan,
																		"sismiopsumlaterpenetapan"=>$sum_later_penetapan,
																		"sismiopcountunpaidsppt"=>$count_unpaid_sppt,
																		"sismiopcountpaidsppt"=>$count_paid_sppt,
																		"sismiopsumallpenetapan"=>$sum_all_penetapan,
																		"sismiopsumunpaidsppt"=>$sum_unpaid_sppt,
																		"sismiopsumpaidsppt"=>$sum_paid_sppt,
																		"kodesppt"=>$kode_sppt,
																		"namakecamatan"=>$namaKecamatan,
																		"namadesa"=>$namaKelurahan,
																		"tahunpajak"=>$thnsppt,
																		"jumlahpersonil"=>$countpersonil,
																		"dhkptotalcountsppt"=>$vtotalcountsppt,
																		"dhkptotalsumsppt"=>$vtotalsumsppt,
																		"dhkpcountallpenetapan"=>$vcountallpenetapan,
																		"dhkpcountdefaultpenetapan"=>$vcountdefaultpenetapan,
																		"dhkpcountlaterpenetapan"=>$vcountlaterpenetapan,
																		"dhkpsumdefaultpenetapan"=>$vsumdefaultpenetapan,
																		"dhkpsumlaterpenetapan"=>$vsumlaterpenetapan,
																		"dhkpcountunassigned"=>$vcountunassigned,
																		"dhkpsumunassigned"=>$vsumunassigned,
																		"dhkpcountassigned"=>$vcountassigned,
																		"dhkpsumassigned"=>$vsumassigned,
																		"dhkpcountunpaiddesa"=>$vcountunpaiddesa,
																		"dhkpsumunpaiddesa"=>$vsumunpaiddesa,
																		"dhkpcountpaiddesa"=>$vcountpaiddesa,
																		"dhkpsumpaiddesa"=>$vsumpaiddesa,
																		"dhkpcountunpaidassigned"=>$vcountunpaidassigned,
																		"dhkpsumunpaidassigned"=>$vsumunpaidassigned,
																		"dhkpcountpaidassigned"=>$vcountpaidassigned,
																		"dhkpsumpaidassigned"=>$vsumpaidassigned,
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
																	/* === /CLOSE ALL CONNECTION + AJAX JSON RESPONSE === */
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
														/* ### /CONNECTION TO MYSQL #### */
													} else {
														oci_free_statement($stmt_sum_paid_sppt);
														oci_close($connection);
														$response = array("ajaxresult"=>"notfound");
														header('Content-Type: application/json');
														echo json_encode($response);
													}
												} else {
													oci_free_statement($stmt_sum_unpaid_sppt);
													oci_close($connection);
													$response = array("ajaxresult"=>"notfound");
													header('Content-Type: application/json');
													echo json_encode($response);
												}
											} else {
												oci_free_statement($stmt_sum_later_penetapan);
												oci_close($connection);
												$response = array("ajaxresult"=>"notfound");
												header('Content-Type: application/json');
												echo json_encode($response);
											}
										} else {
											oci_free_statement($stmt_sum_default_penetapan);
											oci_close($connection);
											$response = array("ajaxresult"=>"notfound");
											header('Content-Type: application/json');
											echo json_encode($response);
										}
									} else {
										oci_free_statement($stmt_sum_all_penetapan);
										oci_close($connection);
										$response = array("ajaxresult"=>"notfound");
										header('Content-Type: application/json');
										echo json_encode($response);
									}
								} else {
									oci_free_statement($stmt_count_paid_sppt);
									oci_close($connection);
									$response = array("ajaxresult"=>"notfound");
									header('Content-Type: application/json');
									echo json_encode($response);
								}
							} else {
								oci_free_statement($stmt_count_unpaid_sppt);
								oci_close($connection);
								$response = array("ajaxresult"=>"notfound");
								header('Content-Type: application/json');
								echo json_encode($response);
							}
						} else {
							oci_free_statement($stmt_count_later_penetapan);
							oci_close($connection);
							$response = array("ajaxresult"=>"notfound");
							header('Content-Type: application/json');
							echo json_encode($response);
						}
					} else {
						oci_free_statement($stmt_count_default_penetapan);
						oci_close($connection);
						$response = array("ajaxresult"=>"notfound");
						header('Content-Type: application/json');
						echo json_encode($response);
					}
				} else {
					oci_free_statement($stmt_count_all_penetapan);
					oci_close($connection);
					$response = array("ajaxresult"=>"notfound");
					header('Content-Type: application/json');
					echo json_encode($response);
				}
			}
		} elseif ($_GET['cmdx'] == "SYNCDATA_SISMIOP_DHKP_TO_DESA") {
			$kode_propinsi = $_SESSION['desaXweb']['KD_PROPINSI'];
			$kode_dati2 = $_SESSION['desaXweb']['KD_DATI2'];
			$kode_kecamatan = $_SESSION['desaXweb']['KD_KECAMATAN'];
			$kode_kelurahan = $_SESSION['desaXweb']['KD_KELURAHAN'];
			$namaKecamatan = $_SESSION['desaXweb']['NM_KECAMATAN'];
			$namaKelurahan = $_SESSION['desaXweb']['NM_KELURAHAN'];
			$thnsppt = $_SESSION['tahunPAJAK'];
			$dbcon = oci_connect("".$appxinfo['_oracle_sismiop_user_']."", "".$appxinfo['_oracle_sismiop_pass_']."", "//".$appxinfo['_oracle_sismiop_host_'].":".$appxinfo['_oracle_sismiop_port_']."/".$appxinfo['_oracle_sismiop_service_']."");
			if (!$dbcon) {
				$errMsg = oci_error();
				trigger_error(htmlentities($errMsg['message']), E_USER_ERROR);
			} else {
				/* block counting existing SISMIOP data */
				$querycount = 'SELECT COUNT(*) "foundrows" FROM SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL';
				$stmtcount = oci_parse($dbcon, $querycount);
				/* binding statements */
				oci_bind_by_name($stmtcount, ":KODEPROP", $kode_propinsi);
				oci_bind_by_name($stmtcount, ":KODEDATI2", $kode_dati2);
				oci_bind_by_name($stmtcount, ":KODEKEC", $kode_kecamatan);
				oci_bind_by_name($stmtcount, ":KODEDESKEL", $kode_kelurahan);
				oci_bind_by_name($stmtcount, ":THNSPPT", $thnsppt);
				if (oci_execute($stmtcount)) {
					while ($rowsetcount = oci_fetch_array($stmtcount, OCI_RETURN_NULLS+OCI_ASSOC)) {$jumlahsppt = $rowsetcount['foundrows'];}
					oci_free_statement($stmtcount);
				} else {$jumlahsppt = 0;}
				if (intval($jumlahsppt)>0) {
					/* =============================================================================== */
					/* block select all data SPPT */
					$query = "SELECT A.KD_PROPINSI AS KD_PROPINSI,A.KD_DATI2 AS KD_DATI2,A.KD_KECAMATAN AS KD_KECAMATAN,A.KD_KELURAHAN AS KD_KELURAHAN,A.KD_BLOK AS KD_BLOK,A.NO_URUT AS NO_URUT,A.KD_JNS_OP AS KD_JNS_OP,A.THN_PAJAK_SPPT AS THN_PAJAK_SPPT,A.SIKLUS_SPPT AS SIKLUS_SPPT,A.KD_KANWIL_BANK AS KD_KANWIL_BANK,A.KD_KPPBB_BANK AS KD_KPPBB_BANK,A.KD_BANK_TUNGGAL AS KD_BANK_TUNGGAL,A.KD_BANK_PERSEPSI AS KD_BANK_PERSEPSI,A.KD_TP AS KD_TP,A.NM_WP_SPPT AS NM_WP_SPPT,A.JLN_WP_SPPT AS JLN_WP_SPPT,A.BLOK_KAV_NO_WP_SPPT AS BLOK_KAV_NO_WP_SPPT,A.RW_WP_SPPT AS RW_WP_SPPT,A.RT_WP_SPPT AS RT_WP_SPPT,A.KELURAHAN_WP_SPPT AS KELURAHAN_WP_SPPT,A.KOTA_WP_SPPT AS KOTA_WP_SPPT,A.KD_POS_WP_SPPT AS KD_POS_WP_SPPT,A.NPWP_SPPT AS NPWP_SPPT,A.NO_PERSIL_SPPT AS NO_PERSIL_SPPT,A.KD_KLS_TANAH AS KD_KLS_TANAH,A.THN_AWAL_KLS_TANAH AS THN_AWAL_KLS_TANAH,A.KD_KLS_BNG AS KD_KLS_BNG,A.THN_AWAL_KLS_BNG AS THN_AWAL_KLS_BNG,TO_CHAR(A.TGL_JATUH_TEMPO_SPPT, 'yyyy-mm-dd') AS TGL_JATUH_TEMPO_SPPT,A.LUAS_BUMI_SPPT AS LUAS_BUMI_SPPT,A.LUAS_BNG_SPPT AS LUAS_BNG_SPPT,A.NJOP_BUMI_SPPT AS NJOP_BUMI_SPPT,A.NJOP_BNG_SPPT AS NJOP_BNG_SPPT,A.NJOP_SPPT AS NJOP_SPPT,A.NJOPTKP_SPPT AS NJOPTKP_SPPT,A.NJKP_SPPT AS NJKP_SPPT,A.PBB_TERHUTANG_SPPT AS PBB_TERHUTANG_SPPT,A.FAKTOR_PENGURANG_SPPT AS FAKTOR_PENGURANG_SPPT,A.PBB_YG_HARUS_DIBAYAR_SPPT AS PBB_YG_HARUS_DIBAYAR_SPPT,A.STATUS_PEMBAYARAN_SPPT AS STATUS_PEMBAYARAN_SPPT,A.STATUS_TAGIHAN_SPPT AS STATUS_TAGIHAN_SPPT,A.STATUS_CETAK_SPPT AS STATUS_CETAK_SPPT,TO_CHAR(A.TGL_TERBIT_SPPT, 'yyyy-mm-dd') AS TGL_TERBIT_SPPT,TO_CHAR(A.TGL_CETAK_SPPT, 'yyyy-mm-dd') AS TGL_CETAK_SPPT,A.NIP_PENCETAK_SPPT FROM SPPT A WHERE A.THN_PAJAK_SPPT = :THNSPPT AND A.KD_PROPINSI = :KODEPROP AND A.KD_DATI2 = :KODEDATI2 AND A.KD_KECAMATAN = :KODEKEC AND A.KD_KELURAHAN = :KODEDESKEL ORDER BY A.KD_PROPINSI||''||A.KD_DATI2||''||A.KD_KECAMATAN||''||A.KD_KELURAHAN||''||A.KD_BLOK||''||A.NO_URUT||''||A.KD_JNS_OP";
					$stmt = oci_parse($dbcon, $query);
					/* binding statements */
					oci_bind_by_name($stmt, ":KODEPROP", $kode_propinsi);
					oci_bind_by_name($stmt, ":KODEDATI2", $kode_dati2);
					oci_bind_by_name($stmt, ":KODEKEC", $kode_kecamatan);
					oci_bind_by_name($stmt, ":KODEDESKEL", $kode_kelurahan);
					oci_bind_by_name($stmt, ":THNSPPT", $thnsppt);
					if (oci_execute($stmt)) {
						/* create connection to MySQL */
						$dbconmysql = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
						$dbconmysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$array_counting = 1;
						while ($rowset = oci_fetch_array($stmt, OCI_RETURN_NULLS+OCI_ASSOC)) {
							if ($array_counting==1) {
								$array_chunks .= strval("(#".$rowset['KD_PROPINSI']."#,#".$rowset['KD_DATI2']."#,#".$rowset['KD_KECAMATAN']."#,#".$rowset['KD_KELURAHAN']."#,#".$rowset['KD_BLOK']."#,#".$rowset['NO_URUT']."#,#".$rowset['KD_JNS_OP']."#,#".$rowset['THN_PAJAK_SPPT']."#,#".$rowset['SIKLUS_SPPT']."#,#".$rowset['KD_KANWIL_BANK']."#,#".$rowset['KD_KPPBB_BANK']."#,#".$rowset['KD_BANK_TUNGGAL']."#,#".$rowset['KD_BANK_PERSEPSI']."#,#".$rowset['KD_TP']."#,#".$rowset['NM_WP_SPPT']."#,#".$rowset['JLN_WP_SPPT']."#,#".$rowset['BLOK_KAV_NO_WP_SPPT']."#,#".$rowset['RW_WP_SPPT']."#,#".$rowset['RT_WP_SPPT']."#,#".$rowset['KELURAHAN_WP_SPPT']."#,#".$rowset['KOTA_WP_SPPT']."#,#".$rowset['KD_POS_WP_SPPT']."#,#".$rowset['NPWP_SPPT']."#,#".$rowset['NO_PERSIL_SPPT']."#,#".$rowset['KD_KLS_TANAH']."#,#".$rowset['THN_AWAL_KLS_TANAH']."#,#".$rowset['KD_KLS_BNG']."#,#".$rowset['THN_AWAL_KLS_BNG']."#,#".$rowset['TGL_JATUH_TEMPO_SPPT']."#,#".$rowset['LUAS_BUMI_SPPT']."#,#".$rowset['LUAS_BNG_SPPT']."#,#".$rowset['NJOP_BUMI_SPPT']."#,#".$rowset['NJOP_BNG_SPPT']."#,#".$rowset['NJOP_SPPT']."#,#".$rowset['NJOPTKP_SPPT']."#,#".$rowset['NJKP_SPPT']."#,#".$rowset['PBB_TERHUTANG_SPPT']."#,#".$rowset['FAKTOR_PENGURANG_SPPT']."#,#".$rowset['PBB_YG_HARUS_DIBAYAR_SPPT']."#,#".$rowset['STATUS_PEMBAYARAN_SPPT']."#,#".$rowset['STATUS_TAGIHAN_SPPT']."#,#".$rowset['STATUS_CETAK_SPPT']."#,#".$rowset['TGL_TERBIT_SPPT']."#,#".$rowset['TGL_CETAK_SPPT']."#,#".$rowset['NIP_PENCETAK_SPPT']."#)");
							} else {
								$array_chunks .= strval(",(#".$rowset['KD_PROPINSI']."#,#".$rowset['KD_DATI2']."#,#".$rowset['KD_KECAMATAN']."#,#".$rowset['KD_KELURAHAN']."#,#".$rowset['KD_BLOK']."#,#".$rowset['NO_URUT']."#,#".$rowset['KD_JNS_OP']."#,#".$rowset['THN_PAJAK_SPPT']."#,#".$rowset['SIKLUS_SPPT']."#,#".$rowset['KD_KANWIL_BANK']."#,#".$rowset['KD_KPPBB_BANK']."#,#".$rowset['KD_BANK_TUNGGAL']."#,#".$rowset['KD_BANK_PERSEPSI']."#,#".$rowset['KD_TP']."#,#".$rowset['NM_WP_SPPT']."#,#".$rowset['JLN_WP_SPPT']."#,#".$rowset['BLOK_KAV_NO_WP_SPPT']."#,#".$rowset['RW_WP_SPPT']."#,#".$rowset['RT_WP_SPPT']."#,#".$rowset['KELURAHAN_WP_SPPT']."#,#".$rowset['KOTA_WP_SPPT']."#,#".$rowset['KD_POS_WP_SPPT']."#,#".$rowset['NPWP_SPPT']."#,#".$rowset['NO_PERSIL_SPPT']."#,#".$rowset['KD_KLS_TANAH']."#,#".$rowset['THN_AWAL_KLS_TANAH']."#,#".$rowset['KD_KLS_BNG']."#,#".$rowset['THN_AWAL_KLS_BNG']."#,#".$rowset['TGL_JATUH_TEMPO_SPPT']."#,#".$rowset['LUAS_BUMI_SPPT']."#,#".$rowset['LUAS_BNG_SPPT']."#,#".$rowset['NJOP_BUMI_SPPT']."#,#".$rowset['NJOP_BNG_SPPT']."#,#".$rowset['NJOP_SPPT']."#,#".$rowset['NJOPTKP_SPPT']."#,#".$rowset['NJKP_SPPT']."#,#".$rowset['PBB_TERHUTANG_SPPT']."#,#".$rowset['FAKTOR_PENGURANG_SPPT']."#,#".$rowset['PBB_YG_HARUS_DIBAYAR_SPPT']."#,#".$rowset['STATUS_PEMBAYARAN_SPPT']."#,#".$rowset['STATUS_TAGIHAN_SPPT']."#,#".$rowset['STATUS_CETAK_SPPT']."#,#".$rowset['TGL_TERBIT_SPPT']."#,#".$rowset['TGL_CETAK_SPPT']."#,#".$rowset['NIP_PENCETAK_SPPT']."#)");
							}
							$array_counting++;
						}
						/* WATCHOUT!!! */
						$array_chunks = str_replace("'", "\'", $array_chunks);
						$array_chunks = str_replace("#", "'", $array_chunks);
						$array_chunks = str_replace("\','", "','", $array_chunks);
						/* /WATCHOUT!!! */
						$bulkloadsqlmodel = strval("INSERT IGNORE INTO sppt_data( KD_PROPINSI, KD_DATI2, KD_KECAMATAN, KD_KELURAHAN, KD_BLOK, NO_URUT, KD_JNS_OP, THN_PAJAK_SPPT, SIKLUS_SPPT, KD_KANWIL_BANK, KD_KPPBB_BANK, KD_BANK_TUNGGAL, KD_BANK_PERSEPSI, KD_TP, NM_WP_SPPT, JLN_WP_SPPT, BLOK_KAV_NO_WP_SPPT, RW_WP_SPPT, RT_WP_SPPT, KELURAHAN_WP_SPPT, KOTA_WP_SPPT, KD_POS_WP_SPPT, NPWP_SPPT, NO_PERSIL_SPPT, KD_KLS_TANAH, THN_AWAL_KLS_TANAH, KD_KLS_BNG, THN_AWAL_KLS_BNG, TGL_JATUH_TEMPO_SPPT, LUAS_BUMI_SPPT, LUAS_BNG_SPPT, NJOP_BUMI_SPPT, NJOP_BNG_SPPT, NJOP_SPPT, NJOPTKP_SPPT, NJKP_SPPT, PBB_TERHUTANG_SPPT, FAKTOR_PENGURANG_SPPT, PBB_YG_HARUS_DIBAYAR_SPPT, STATUS_PEMBAYARAN_SPPT, STATUS_TAGIHAN_SPPT, STATUS_CETAK_SPPT, TGL_TERBIT_SPPT, TGL_CETAK_SPPT, NIP_PENCETAK_SPPT) VALUES ".$array_chunks."");
						$stmtmysql = $dbconmysql->prepare($bulkloadsqlmodel);
						if ($stmtmysql->execute()) {
							$response = array("ajaxresult"=>"dataissynchronised");
							header('Content-Type: application/json');
							echo json_encode($response);
							$dbconmysql = null;
							oci_free_statement($stmt);
							oci_close($dbcon);
							exit;
						} else {
							$response = array("ajaxresult"=>"syncdatafailed","ajaxmessage"=>"Error: No data found.");
							header('Content-Type: application/json');
							echo json_encode($response);
							oci_close($dbcon);
							exit;
						}
						// echo($bulkloadsqlmodel);
					} else {
						$response = array("ajaxresult"=>"syncdatafailed","ajaxmessage"=>"Error: SQL failed to be executed.");
						header('Content-Type: application/json');
						echo json_encode($response);
						oci_close($dbcon);
						exit;
					}
					/* =============================================================================== */
				} else {
					$response = array("ajaxresult"=>"syncdatafailed","ajaxmessage"=>"Error: No data found.");
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_close($dbcon);
					exit;
				}
				/* /block select all data SPPT */
			}
		} elseif ($_GET['cmdx'] == "SYNCDATA_SISMIOP_DHKP_TO_DESA_BYTAXYEAR") {
			$kode_propinsi = $_SESSION['desaXweb']['KD_PROPINSI'];
			$kode_dati2 = $_SESSION['desaXweb']['KD_DATI2'];
			$kode_kecamatan = $_SESSION['desaXweb']['KD_KECAMATAN'];
			$kode_kelurahan = $_SESSION['desaXweb']['KD_KELURAHAN'];
			$namaKecamatan = $_SESSION['desaXweb']['NM_KECAMATAN'];
			$namaKelurahan = $_SESSION['desaXweb']['NM_KELURAHAN'];
			$thnsppt = $_GET['taxyear'];
			$dbcon = oci_connect("".$appxinfo['_oracle_sismiop_user_']."", "".$appxinfo['_oracle_sismiop_pass_']."", "//".$appxinfo['_oracle_sismiop_host_'].":".$appxinfo['_oracle_sismiop_port_']."/".$appxinfo['_oracle_sismiop_service_']."");
			if (!$dbcon) {
				$errMsg = oci_error();
				trigger_error(htmlentities($errMsg['message']), E_USER_ERROR);
			} else {
				/* block counting existing SISMIOP data */
				$querycount = 'SELECT COUNT(*) "foundrows" FROM SPPT WHERE THN_PAJAK_SPPT = :THNSPPT AND KD_PROPINSI = :KODEPROP AND KD_DATI2 = :KODEDATI2 AND KD_KECAMATAN = :KODEKEC AND KD_KELURAHAN = :KODEDESKEL';
				$stmtcount = oci_parse($dbcon, $querycount);
				/* binding statements */
				oci_bind_by_name($stmtcount, ":KODEPROP", $kode_propinsi);
				oci_bind_by_name($stmtcount, ":KODEDATI2", $kode_dati2);
				oci_bind_by_name($stmtcount, ":KODEKEC", $kode_kecamatan);
				oci_bind_by_name($stmtcount, ":KODEDESKEL", $kode_kelurahan);
				oci_bind_by_name($stmtcount, ":THNSPPT", $thnsppt);
				if (oci_execute($stmtcount)) {
					while ($rowsetcount = oci_fetch_array($stmtcount, OCI_RETURN_NULLS+OCI_ASSOC)) {$jumlahsppt = $rowsetcount['foundrows'];}
					oci_free_statement($stmtcount);
				} else {$jumlahsppt = 0;}
				if (intval($jumlahsppt)>0) {
					/* =============================================================================== */
					/* block select all data SPPT */
					$query = "SELECT A.KD_PROPINSI AS KD_PROPINSI,A.KD_DATI2 AS KD_DATI2,A.KD_KECAMATAN AS KD_KECAMATAN,A.KD_KELURAHAN AS KD_KELURAHAN,A.KD_BLOK AS KD_BLOK,A.NO_URUT AS NO_URUT,A.KD_JNS_OP AS KD_JNS_OP,A.THN_PAJAK_SPPT AS THN_PAJAK_SPPT,A.SIKLUS_SPPT AS SIKLUS_SPPT,A.KD_KANWIL_BANK AS KD_KANWIL_BANK,A.KD_KPPBB_BANK AS KD_KPPBB_BANK,A.KD_BANK_TUNGGAL AS KD_BANK_TUNGGAL,A.KD_BANK_PERSEPSI AS KD_BANK_PERSEPSI,A.KD_TP AS KD_TP,A.NM_WP_SPPT AS NM_WP_SPPT,A.JLN_WP_SPPT AS JLN_WP_SPPT,A.BLOK_KAV_NO_WP_SPPT AS BLOK_KAV_NO_WP_SPPT,A.RW_WP_SPPT AS RW_WP_SPPT,A.RT_WP_SPPT AS RT_WP_SPPT,A.KELURAHAN_WP_SPPT AS KELURAHAN_WP_SPPT,A.KOTA_WP_SPPT AS KOTA_WP_SPPT,A.KD_POS_WP_SPPT AS KD_POS_WP_SPPT,A.NPWP_SPPT AS NPWP_SPPT,A.NO_PERSIL_SPPT AS NO_PERSIL_SPPT,A.KD_KLS_TANAH AS KD_KLS_TANAH,A.THN_AWAL_KLS_TANAH AS THN_AWAL_KLS_TANAH,A.KD_KLS_BNG AS KD_KLS_BNG,A.THN_AWAL_KLS_BNG AS THN_AWAL_KLS_BNG,TO_CHAR(A.TGL_JATUH_TEMPO_SPPT, 'yyyy-mm-dd') AS TGL_JATUH_TEMPO_SPPT,A.LUAS_BUMI_SPPT AS LUAS_BUMI_SPPT,A.LUAS_BNG_SPPT AS LUAS_BNG_SPPT,A.NJOP_BUMI_SPPT AS NJOP_BUMI_SPPT,A.NJOP_BNG_SPPT AS NJOP_BNG_SPPT,A.NJOP_SPPT AS NJOP_SPPT,A.NJOPTKP_SPPT AS NJOPTKP_SPPT,A.NJKP_SPPT AS NJKP_SPPT,A.PBB_TERHUTANG_SPPT AS PBB_TERHUTANG_SPPT,A.FAKTOR_PENGURANG_SPPT AS FAKTOR_PENGURANG_SPPT,A.PBB_YG_HARUS_DIBAYAR_SPPT AS PBB_YG_HARUS_DIBAYAR_SPPT,A.STATUS_PEMBAYARAN_SPPT AS STATUS_PEMBAYARAN_SPPT,A.STATUS_TAGIHAN_SPPT AS STATUS_TAGIHAN_SPPT,A.STATUS_CETAK_SPPT AS STATUS_CETAK_SPPT,TO_CHAR(A.TGL_TERBIT_SPPT, 'yyyy-mm-dd') AS TGL_TERBIT_SPPT,TO_CHAR(A.TGL_CETAK_SPPT, 'yyyy-mm-dd') AS TGL_CETAK_SPPT,A.NIP_PENCETAK_SPPT FROM SPPT A WHERE A.THN_PAJAK_SPPT = :THNSPPT AND A.KD_PROPINSI = :KODEPROP AND A.KD_DATI2 = :KODEDATI2 AND A.KD_KECAMATAN = :KODEKEC AND A.KD_KELURAHAN = :KODEDESKEL ORDER BY A.KD_PROPINSI||''||A.KD_DATI2||''||A.KD_KECAMATAN||''||A.KD_KELURAHAN||''||A.KD_BLOK||''||A.NO_URUT||''||A.KD_JNS_OP";
					$stmt = oci_parse($dbcon, $query);
					/* binding statements */
					oci_bind_by_name($stmt, ":KODEPROP", $kode_propinsi);
					oci_bind_by_name($stmt, ":KODEDATI2", $kode_dati2);
					oci_bind_by_name($stmt, ":KODEKEC", $kode_kecamatan);
					oci_bind_by_name($stmt, ":KODEDESKEL", $kode_kelurahan);
					oci_bind_by_name($stmt, ":THNSPPT", $thnsppt);
					if (oci_execute($stmt)) {
						/* create connection to MySQL */
						$dbconmysql = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
						$dbconmysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$array_counting = 1;
						while ($rowset = oci_fetch_array($stmt, OCI_RETURN_NULLS+OCI_ASSOC)) {
							if ($array_counting==1) {
								$array_chunks .= strval("(#".$rowset['KD_PROPINSI']."#,#".$rowset['KD_DATI2']."#,#".$rowset['KD_KECAMATAN']."#,#".$rowset['KD_KELURAHAN']."#,#".$rowset['KD_BLOK']."#,#".$rowset['NO_URUT']."#,#".$rowset['KD_JNS_OP']."#,#".$rowset['THN_PAJAK_SPPT']."#,#".$rowset['SIKLUS_SPPT']."#,#".$rowset['KD_KANWIL_BANK']."#,#".$rowset['KD_KPPBB_BANK']."#,#".$rowset['KD_BANK_TUNGGAL']."#,#".$rowset['KD_BANK_PERSEPSI']."#,#".$rowset['KD_TP']."#,#".$rowset['NM_WP_SPPT']."#,#".$rowset['JLN_WP_SPPT']."#,#".$rowset['BLOK_KAV_NO_WP_SPPT']."#,#".$rowset['RW_WP_SPPT']."#,#".$rowset['RT_WP_SPPT']."#,#".$rowset['KELURAHAN_WP_SPPT']."#,#".$rowset['KOTA_WP_SPPT']."#,#".$rowset['KD_POS_WP_SPPT']."#,#".$rowset['NPWP_SPPT']."#,#".$rowset['NO_PERSIL_SPPT']."#,#".$rowset['KD_KLS_TANAH']."#,#".$rowset['THN_AWAL_KLS_TANAH']."#,#".$rowset['KD_KLS_BNG']."#,#".$rowset['THN_AWAL_KLS_BNG']."#,#".$rowset['TGL_JATUH_TEMPO_SPPT']."#,#".$rowset['LUAS_BUMI_SPPT']."#,#".$rowset['LUAS_BNG_SPPT']."#,#".$rowset['NJOP_BUMI_SPPT']."#,#".$rowset['NJOP_BNG_SPPT']."#,#".$rowset['NJOP_SPPT']."#,#".$rowset['NJOPTKP_SPPT']."#,#".$rowset['NJKP_SPPT']."#,#".$rowset['PBB_TERHUTANG_SPPT']."#,#".$rowset['FAKTOR_PENGURANG_SPPT']."#,#".$rowset['PBB_YG_HARUS_DIBAYAR_SPPT']."#,#".$rowset['STATUS_PEMBAYARAN_SPPT']."#,#".$rowset['STATUS_TAGIHAN_SPPT']."#,#".$rowset['STATUS_CETAK_SPPT']."#,#".$rowset['TGL_TERBIT_SPPT']."#,#".$rowset['TGL_CETAK_SPPT']."#,#".$rowset['NIP_PENCETAK_SPPT']."#)");
							} else {
								$array_chunks .= strval(",(#".$rowset['KD_PROPINSI']."#,#".$rowset['KD_DATI2']."#,#".$rowset['KD_KECAMATAN']."#,#".$rowset['KD_KELURAHAN']."#,#".$rowset['KD_BLOK']."#,#".$rowset['NO_URUT']."#,#".$rowset['KD_JNS_OP']."#,#".$rowset['THN_PAJAK_SPPT']."#,#".$rowset['SIKLUS_SPPT']."#,#".$rowset['KD_KANWIL_BANK']."#,#".$rowset['KD_KPPBB_BANK']."#,#".$rowset['KD_BANK_TUNGGAL']."#,#".$rowset['KD_BANK_PERSEPSI']."#,#".$rowset['KD_TP']."#,#".$rowset['NM_WP_SPPT']."#,#".$rowset['JLN_WP_SPPT']."#,#".$rowset['BLOK_KAV_NO_WP_SPPT']."#,#".$rowset['RW_WP_SPPT']."#,#".$rowset['RT_WP_SPPT']."#,#".$rowset['KELURAHAN_WP_SPPT']."#,#".$rowset['KOTA_WP_SPPT']."#,#".$rowset['KD_POS_WP_SPPT']."#,#".$rowset['NPWP_SPPT']."#,#".$rowset['NO_PERSIL_SPPT']."#,#".$rowset['KD_KLS_TANAH']."#,#".$rowset['THN_AWAL_KLS_TANAH']."#,#".$rowset['KD_KLS_BNG']."#,#".$rowset['THN_AWAL_KLS_BNG']."#,#".$rowset['TGL_JATUH_TEMPO_SPPT']."#,#".$rowset['LUAS_BUMI_SPPT']."#,#".$rowset['LUAS_BNG_SPPT']."#,#".$rowset['NJOP_BUMI_SPPT']."#,#".$rowset['NJOP_BNG_SPPT']."#,#".$rowset['NJOP_SPPT']."#,#".$rowset['NJOPTKP_SPPT']."#,#".$rowset['NJKP_SPPT']."#,#".$rowset['PBB_TERHUTANG_SPPT']."#,#".$rowset['FAKTOR_PENGURANG_SPPT']."#,#".$rowset['PBB_YG_HARUS_DIBAYAR_SPPT']."#,#".$rowset['STATUS_PEMBAYARAN_SPPT']."#,#".$rowset['STATUS_TAGIHAN_SPPT']."#,#".$rowset['STATUS_CETAK_SPPT']."#,#".$rowset['TGL_TERBIT_SPPT']."#,#".$rowset['TGL_CETAK_SPPT']."#,#".$rowset['NIP_PENCETAK_SPPT']."#)");
							}
							$array_counting++;
						}
						/* WATCHOUT!!! */
						$array_chunks = str_replace("'", "\'", $array_chunks);
						$array_chunks = str_replace("#", "'", $array_chunks);
						$array_chunks = str_replace("\','", "','", $array_chunks);
						/* /WATCHOUT!!! */
						$bulkloadsqlmodel = strval("INSERT IGNORE INTO sppt_data( KD_PROPINSI, KD_DATI2, KD_KECAMATAN, KD_KELURAHAN, KD_BLOK, NO_URUT, KD_JNS_OP, THN_PAJAK_SPPT, SIKLUS_SPPT, KD_KANWIL_BANK, KD_KPPBB_BANK, KD_BANK_TUNGGAL, KD_BANK_PERSEPSI, KD_TP, NM_WP_SPPT, JLN_WP_SPPT, BLOK_KAV_NO_WP_SPPT, RW_WP_SPPT, RT_WP_SPPT, KELURAHAN_WP_SPPT, KOTA_WP_SPPT, KD_POS_WP_SPPT, NPWP_SPPT, NO_PERSIL_SPPT, KD_KLS_TANAH, THN_AWAL_KLS_TANAH, KD_KLS_BNG, THN_AWAL_KLS_BNG, TGL_JATUH_TEMPO_SPPT, LUAS_BUMI_SPPT, LUAS_BNG_SPPT, NJOP_BUMI_SPPT, NJOP_BNG_SPPT, NJOP_SPPT, NJOPTKP_SPPT, NJKP_SPPT, PBB_TERHUTANG_SPPT, FAKTOR_PENGURANG_SPPT, PBB_YG_HARUS_DIBAYAR_SPPT, STATUS_PEMBAYARAN_SPPT, STATUS_TAGIHAN_SPPT, STATUS_CETAK_SPPT, TGL_TERBIT_SPPT, TGL_CETAK_SPPT, NIP_PENCETAK_SPPT) VALUES ".$array_chunks."");
						$stmtmysql = $dbconmysql->prepare($bulkloadsqlmodel);
						if ($stmtmysql->execute()) {
							$response = array("ajaxresult"=>"dataissynchronised");
							header('Content-Type: application/json');
							echo json_encode($response);
							$dbconmysql = null;
							oci_free_statement($stmt);
							oci_close($dbcon);
							exit;
						} else {
							$response = array("ajaxresult"=>"syncdatafailed","ajaxmessage"=>"Error: No data found.");
							header('Content-Type: application/json');
							echo json_encode($response);
							oci_close($dbcon);
							exit;
						}
						// echo($bulkloadsqlmodel);
					} else {
						$response = array("ajaxresult"=>"syncdatafailed","ajaxmessage"=>"Error: SQL failed to be executed.");
						header('Content-Type: application/json');
						echo json_encode($response);
						oci_close($dbcon);
						exit;
					}
					/* =============================================================================== */
				} else {
					$response = array("ajaxresult"=>"syncdatafailed","ajaxmessage"=>"Error: No data found.");
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_close($dbcon);
					exit;
				}
				/* /block select all data SPPT */
			}
		} elseif ($_GET['cmdx'] == "SYNCDATA_SISMIOP_PEMBAYARAN_TO_DESA") {
			$kode_propinsi = $_SESSION['desaXweb']['KD_PROPINSI'];
			$kode_dati2 = $_SESSION['desaXweb']['KD_DATI2'];
			$kode_kecamatan = $_SESSION['desaXweb']['KD_KECAMATAN'];
			$kode_kelurahan = $_SESSION['desaXweb']['KD_KELURAHAN'];
			$namaKecamatan = $_SESSION['desaXweb']['NM_KECAMATAN'];
			$namaKelurahan = $_SESSION['desaXweb']['NM_KELURAHAN'];
			$thnsppt = $_SESSION['tahunPAJAK'];
			$dbcon = oci_connect("".$appxinfo['_oracle_sismiop_user_']."", "".$appxinfo['_oracle_sismiop_pass_']."", "//".$appxinfo['_oracle_sismiop_host_'].":".$appxinfo['_oracle_sismiop_port_']."/".$appxinfo['_oracle_sismiop_service_']."");
			if (!$dbcon) {
				$errMsg = oci_error();
				trigger_error(htmlentities($errMsg['message']), E_USER_ERROR);
			} else {
				$query = "SELECT A.KD_PROPINSI AS KD_PROPINSI, A.KD_DATI2 AS KD_DATI2, A.KD_KECAMATAN AS KD_KECAMATAN, A.KD_KELURAHAN AS KD_KELURAHAN, A.KD_BLOK AS KD_BLOK, A.NO_URUT AS NO_URUT, A.KD_JNS_OP AS KD_JNS_OP, A.THN_PAJAK_SPPT AS THN_PAJAK_SPPT, A.PEMBAYARAN_SPPT_KE AS PEMBAYARAN_SPPT_KE, A.KD_KANWIL_BANK AS KD_KANWIL_BANK, A.KD_KPPBB_BANK AS KD_KPPBB_BANK, A.KD_BANK_TUNGGAL AS KD_BANK_TUNGGAL, A.KD_BANK_PERSEPSI AS KD_BANK_PERSEPSI, A.KD_TP AS KD_TP, A.DENDA_SPPT AS DENDA_SPPT, A.JML_SPPT_YG_DIBAYAR AS JML_SPPT_YG_DIBAYAR, TO_CHAR(A.TGL_PEMBAYARAN_SPPT, 'yyyy-mm-dd') AS TGL_PEMBAYARAN_SPPT, TO_CHAR(A.TGL_REKAM_BYR_SPPT, 'yyyy-mm-dd') AS TGL_REKAM_BYR_SPPT, A.NIP_REKAM_BYR_SPPT AS NIP_REKAM_BYR_SPPT, B.STATUS_PEMBAYARAN_SPPT AS STATUS_PEMBAYARAN_SPPT, B.STATUS_TAGIHAN_SPPT AS STATUS_TAGIHAN_SPPT FROM PEMBAYARAN_SPPT A INNER JOIN SPPT B ON A.KD_PROPINSI = B.KD_PROPINSI AND A.KD_DATI2 = B.KD_DATI2 AND A.KD_KECAMATAN = B.KD_KECAMATAN AND A.KD_KELURAHAN = B.KD_KELURAHAN AND A.KD_BLOK = B.KD_BLOK AND A.NO_URUT = B.NO_URUT AND A.KD_JNS_OP = B.KD_JNS_OP AND A.THN_PAJAK_SPPT = B.THN_PAJAK_SPPT WHERE A.THN_PAJAK_SPPT = :THNSPPT AND A.KD_PROPINSI = :KODEPROP AND A.KD_DATI2 = :KODEDATI2 AND A.KD_KECAMATAN = :KODEKEC AND A.KD_KELURAHAN = :KODEDESKEL ORDER BY A.KD_PROPINSI||''||A.KD_DATI2||''||A.KD_KECAMATAN||''||A.KD_KELURAHAN||''||A.KD_BLOK||''||A.NO_URUT||''||A.KD_JNS_OP";
				$stmt = oci_parse($dbcon, $query);
				oci_bind_by_name($stmt, ":KODEPROP", $kode_propinsi);
				oci_bind_by_name($stmt, ":KODEDATI2", $kode_dati2);
				oci_bind_by_name($stmt, ":KODEKEC", $kode_kecamatan);
				oci_bind_by_name($stmt, ":KODEDESKEL", $kode_kelurahan);
				oci_bind_by_name($stmt, ":THNSPPT", $thnsppt);
				if (oci_execute($stmt)) {
					$dbconmysql = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
					$dbconmysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					while ($rowset = oci_fetch_array($stmt, OCI_RETURN_NULLS+OCI_ASSOC)) {
						$stmtmysql = $dbconmysql->prepare("UPDATE sppt_data SET sppt_data.STATUS_PEMBAYARAN_SPPT = :STATUSPEMBAYARANSPPT, sppt_data.STATUS_TAGIHAN_SPPT = :STATUSTAGIHANSPPT, sppt_data.PEMBAYARAN_SPPT_KE = :PEMBAYARANSPPTKE, sppt_data.KD_KANWIL_BANK = :KDKANWILBANK, sppt_data.KD_KPPBB_BANK = :KDKPPBBBANK, sppt_data.KD_BANK_TUNGGAL = :KDBANKTUNGGAL, sppt_data.KD_BANK_PERSEPSI = :KDBANKPERSEPSI, sppt_data.KD_TP = :KDTP, sppt_data.DENDA_SPPT = :DENDASPPT, sppt_data.JML_SPPT_YG_DIBAYAR = :JMLSPPTYGDIBAYAR, sppt_data.TGL_PEMBAYARAN_SPPT = :TGLPEMBAYARANSPPT, sppt_data.TGL_REKAM_BYR_SPPT = :TGLREKAMBYRSPPT, sppt_data.NIP_REKAM_BYR_SPPT = :NIPREKAMBYRSPPT, sppt_data.STATUS = 2 WHERE sppt_data.KD_PROPINSI = :KDPROPINSI AND sppt_data.KD_DATI2 = :KDDATI2 AND sppt_data.KD_KECAMATAN = :KDKECAMATAN AND sppt_data.KD_KELURAHAN = :KDKELURAHAN AND sppt_data.KD_BLOK = :KDBLOK AND sppt_data.NO_URUT = :NOURUT AND sppt_data.KD_JNS_OP = :KDJNSOP AND sppt_data.THN_PAJAK_SPPT = :THNPAJAKSPPT");
						$stmtmysql->bindValue(":KDPROPINSI", $rowset['KD_PROPINSI'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDDATI2", $rowset['KD_DATI2'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDKECAMATAN", $rowset['KD_KECAMATAN'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDKELURAHAN", $rowset['KD_KELURAHAN'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDBLOK", $rowset['KD_BLOK'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":NOURUT", $rowset['NO_URUT'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDJNSOP", $rowset['KD_JNS_OP'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":THNPAJAKSPPT", $rowset['THN_PAJAK_SPPT'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":PEMBAYARANSPPTKE", $rowset['PEMBAYARAN_SPPT_KE'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDKANWILBANK", $rowset['KD_KANWIL_BANK'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDKPPBBBANK", $rowset['KD_KPPBB_BANK'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDBANKTUNGGAL", $rowset['KD_BANK_TUNGGAL'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDBANKPERSEPSI", $rowset['KD_BANK_PERSEPSI'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDTP", $rowset['KD_TP'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":DENDASPPT", $rowset['DENDA_SPPT'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":JMLSPPTYGDIBAYAR", $rowset['JML_SPPT_YG_DIBAYAR'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":TGLPEMBAYARANSPPT", $rowset['TGL_PEMBAYARAN_SPPT'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":TGLREKAMBYRSPPT", $rowset['TGL_REKAM_BYR_SPPT'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":NIPREKAMBYRSPPT", $rowset['NIP_REKAM_BYR_SPPT'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":STATUSPEMBAYARANSPPT", $rowset['STATUS_PEMBAYARAN_SPPT'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":STATUSTAGIHANSPPT", $rowset['STATUS_TAGIHAN_SPPT'], PDO::PARAM_STR);
						$stmtmysql->execute();
					}
					$response = array("ajaxresult"=>"dataissynchronised");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbconmysql = null;
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				} else {
					$response = array("ajaxresult"=>"syncdatafailed","ajaxmessage"=>"Error: SQL failed to be executed.");
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_close($dbcon);
					exit;
				}
			}
		} elseif ($_GET['cmdx'] == "SYNCDATA_SISMIOP_PEMBAYARAN_TO_DESA_BYTAXYEAR") {
			$kode_propinsi = $_SESSION['desaXweb']['KD_PROPINSI'];
			$kode_dati2 = $_SESSION['desaXweb']['KD_DATI2'];
			$kode_kecamatan = $_SESSION['desaXweb']['KD_KECAMATAN'];
			$kode_kelurahan = $_SESSION['desaXweb']['KD_KELURAHAN'];
			$namaKecamatan = $_SESSION['desaXweb']['NM_KECAMATAN'];
			$namaKelurahan = $_SESSION['desaXweb']['NM_KELURAHAN'];
			$thnsppt = $_GET['taxyear'];
			$dbcon = oci_connect("".$appxinfo['_oracle_sismiop_user_']."", "".$appxinfo['_oracle_sismiop_pass_']."", "//".$appxinfo['_oracle_sismiop_host_'].":".$appxinfo['_oracle_sismiop_port_']."/".$appxinfo['_oracle_sismiop_service_']."");
			if (!$dbcon) {
				$errMsg = oci_error();
				trigger_error(htmlentities($errMsg['message']), E_USER_ERROR);
			} else {
				$query = "SELECT A.KD_PROPINSI AS KD_PROPINSI, A.KD_DATI2 AS KD_DATI2, A.KD_KECAMATAN AS KD_KECAMATAN, A.KD_KELURAHAN AS KD_KELURAHAN, A.KD_BLOK AS KD_BLOK, A.NO_URUT AS NO_URUT, A.KD_JNS_OP AS KD_JNS_OP, A.THN_PAJAK_SPPT AS THN_PAJAK_SPPT, A.PEMBAYARAN_SPPT_KE AS PEMBAYARAN_SPPT_KE, A.KD_KANWIL_BANK AS KD_KANWIL_BANK, A.KD_KPPBB_BANK AS KD_KPPBB_BANK, A.KD_BANK_TUNGGAL AS KD_BANK_TUNGGAL, A.KD_BANK_PERSEPSI AS KD_BANK_PERSEPSI, A.KD_TP AS KD_TP, A.DENDA_SPPT AS DENDA_SPPT, A.JML_SPPT_YG_DIBAYAR AS JML_SPPT_YG_DIBAYAR, TO_CHAR(A.TGL_PEMBAYARAN_SPPT, 'yyyy-mm-dd') AS TGL_PEMBAYARAN_SPPT, TO_CHAR(A.TGL_REKAM_BYR_SPPT, 'yyyy-mm-dd') AS TGL_REKAM_BYR_SPPT, A.NIP_REKAM_BYR_SPPT AS NIP_REKAM_BYR_SPPT, B.STATUS_PEMBAYARAN_SPPT AS STATUS_PEMBAYARAN_SPPT, B.STATUS_TAGIHAN_SPPT AS STATUS_TAGIHAN_SPPT FROM PEMBAYARAN_SPPT A INNER JOIN SPPT B ON A.KD_PROPINSI = B.KD_PROPINSI AND A.KD_DATI2 = B.KD_DATI2 AND A.KD_KECAMATAN = B.KD_KECAMATAN AND A.KD_KELURAHAN = B.KD_KELURAHAN AND A.KD_BLOK = B.KD_BLOK AND A.NO_URUT = B.NO_URUT AND A.KD_JNS_OP = B.KD_JNS_OP AND A.THN_PAJAK_SPPT = B.THN_PAJAK_SPPT WHERE A.THN_PAJAK_SPPT = :THNSPPT AND A.KD_PROPINSI = :KODEPROP AND A.KD_DATI2 = :KODEDATI2 AND A.KD_KECAMATAN = :KODEKEC AND A.KD_KELURAHAN = :KODEDESKEL ORDER BY A.KD_PROPINSI||''||A.KD_DATI2||''||A.KD_KECAMATAN||''||A.KD_KELURAHAN||''||A.KD_BLOK||''||A.NO_URUT||''||A.KD_JNS_OP";
				$stmt = oci_parse($dbcon, $query);
				oci_bind_by_name($stmt, ":KODEPROP", $kode_propinsi);
				oci_bind_by_name($stmt, ":KODEDATI2", $kode_dati2);
				oci_bind_by_name($stmt, ":KODEKEC", $kode_kecamatan);
				oci_bind_by_name($stmt, ":KODEDESKEL", $kode_kelurahan);
				oci_bind_by_name($stmt, ":THNSPPT", $thnsppt);
				if (oci_execute($stmt)) {
					$dbconmysql = new PDO("mysql:host=".$appxinfo['_desa_db_host_'].";dbname=".$appxinfo['_desa_db_name_']."","".$appxinfo['_desa_db_user_']."","".$appxinfo['_desa_db_pass_']."");
					$dbconmysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					while ($rowset = oci_fetch_array($stmt, OCI_RETURN_NULLS+OCI_ASSOC)) {
						$stmtmysql = $dbconmysql->prepare("UPDATE sppt_data SET sppt_data.STATUS_PEMBAYARAN_SPPT = :STATUSPEMBAYARANSPPT, sppt_data.STATUS_TAGIHAN_SPPT = :STATUSTAGIHANSPPT, sppt_data.PEMBAYARAN_SPPT_KE = :PEMBAYARANSPPTKE, sppt_data.KD_KANWIL_BANK = :KDKANWILBANK, sppt_data.KD_KPPBB_BANK = :KDKPPBBBANK, sppt_data.KD_BANK_TUNGGAL = :KDBANKTUNGGAL, sppt_data.KD_BANK_PERSEPSI = :KDBANKPERSEPSI, sppt_data.KD_TP = :KDTP, sppt_data.DENDA_SPPT = :DENDASPPT, sppt_data.JML_SPPT_YG_DIBAYAR = :JMLSPPTYGDIBAYAR, sppt_data.TGL_PEMBAYARAN_SPPT = :TGLPEMBAYARANSPPT, sppt_data.TGL_REKAM_BYR_SPPT = :TGLREKAMBYRSPPT, sppt_data.NIP_REKAM_BYR_SPPT = :NIPREKAMBYRSPPT, sppt_data.STATUS = 2 WHERE sppt_data.KD_PROPINSI = :KDPROPINSI AND sppt_data.KD_DATI2 = :KDDATI2 AND sppt_data.KD_KECAMATAN = :KDKECAMATAN AND sppt_data.KD_KELURAHAN = :KDKELURAHAN AND sppt_data.KD_BLOK = :KDBLOK AND sppt_data.NO_URUT = :NOURUT AND sppt_data.KD_JNS_OP = :KDJNSOP AND sppt_data.THN_PAJAK_SPPT = :THNPAJAKSPPT");
						$stmtmysql->bindValue(":KDPROPINSI", $rowset['KD_PROPINSI'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDDATI2", $rowset['KD_DATI2'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDKECAMATAN", $rowset['KD_KECAMATAN'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDKELURAHAN", $rowset['KD_KELURAHAN'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDBLOK", $rowset['KD_BLOK'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":NOURUT", $rowset['NO_URUT'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDJNSOP", $rowset['KD_JNS_OP'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":THNPAJAKSPPT", $rowset['THN_PAJAK_SPPT'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":PEMBAYARANSPPTKE", $rowset['PEMBAYARAN_SPPT_KE'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDKANWILBANK", $rowset['KD_KANWIL_BANK'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDKPPBBBANK", $rowset['KD_KPPBB_BANK'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDBANKTUNGGAL", $rowset['KD_BANK_TUNGGAL'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDBANKPERSEPSI", $rowset['KD_BANK_PERSEPSI'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":KDTP", $rowset['KD_TP'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":DENDASPPT", $rowset['DENDA_SPPT'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":JMLSPPTYGDIBAYAR", $rowset['JML_SPPT_YG_DIBAYAR'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":TGLPEMBAYARANSPPT", $rowset['TGL_PEMBAYARAN_SPPT'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":TGLREKAMBYRSPPT", $rowset['TGL_REKAM_BYR_SPPT'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":NIPREKAMBYRSPPT", $rowset['NIP_REKAM_BYR_SPPT'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":STATUSPEMBAYARANSPPT", $rowset['STATUS_PEMBAYARAN_SPPT'], PDO::PARAM_STR);
						$stmtmysql->bindValue(":STATUSTAGIHANSPPT", $rowset['STATUS_TAGIHAN_SPPT'], PDO::PARAM_STR);
						$stmtmysql->execute();
					}
					$response = array("ajaxresult"=>"dataissynchronised");
					header('Content-Type: application/json');
					echo json_encode($response);
					$dbconmysql = null;
					oci_free_statement($stmt);
					oci_close($dbcon);
					exit;
				} else {
					$response = array("ajaxresult"=>"syncdatafailed","ajaxmessage"=>"Error: SQL failed to be executed.");
					header('Content-Type: application/json');
					echo json_encode($response);
					oci_close($dbcon);
					exit;
				}
			}
		} else {
			$response = array("ajaxresult"=>"undefined","ajaxmessage"=>"Error: Your command is unrecognized by the system.");
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		}
	} else {
		$response = array("ajaxresult"=>"undefined","ajaxmessage"=>"Error: No valid command is set.");
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;
	}
} else {
	$response = array("ajaxresult"=>"undefined","ajaxmessage"=>"Error: No valid session is set.");
	header('Content-Type: application/json');
	echo json_encode($response);
	exit;
}
?>