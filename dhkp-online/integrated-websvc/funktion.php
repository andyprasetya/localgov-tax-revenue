<?php
class dasFunktionz {
	/* =============== */
	public $dummy_string;
	/* =============== */
	public $pgsql_onemap_db_host;
	public $pgsql_onemap_db_port;
	public $pgsql_onemap_db_user;
	public $pgsql_onemap_db_pass;
	public $pgsql_onemap_db_name;
	/* =============== */
	public $pgsql_integrated_db_host;
	public $pgsql_integrated_db_port;
	public $pgsql_integrated_db_user;
	public $pgsql_integrated_db_pass;
	public $pgsql_integrated_db_name;
	/* =============== */
	public $webgisworx_db_host;
	public $webgisworx_db_port;
	public $webgisworx_db_user;
	public $webgisworx_db_pass;
	public $webgisworx_db_name;
	/* =============== */
	public $mysql_core_db_host;
	public $mysql_core_db_port;
	public $mysql_core_db_user;
	public $mysql_core_db_pass;
	public $mysql_core_db_name;
	/* =============== */
	public $mysql_integrated_db_host;
	public $mysql_integrated_db_port;
	public $mysql_integrated_db_user;
	public $mysql_integrated_db_pass;
	public $mysql_integrated_db_name;
	/* =============== */
	public $oracle_sismiop_host;
	public $oracle_sismiop_port;
	public $oracle_sismiop_user;
	public $oracle_sismiop_pass;
	public $oracle_sismiop_service;
	/* =============== */
	public $tax_year;
	public $response;
	/* todo next... */
	/*
	 * Class (parameter) constructor.
	 */
	public function __construct(){
		$numArgs = func_num_args();
		if ($numArgs == 0 || $numArgs == null) {
			/* todo next... */

		} else {
			/* todo next... */

		}
		$this->dummy_string = "Herzlich willkommen bei TruLogix!";
		$this->tax_year = Date('Y');
		/* =============== */
		$this->pgsql_onemap_db_host = "localhost";
		$this->pgsql_onemap_db_port = "5432";
		$this->pgsql_onemap_db_user = "geoserver";
		$this->pgsql_onemap_db_pass = "Sarajevo!99x";
		$this->pgsql_onemap_db_name = "one_map_kabupaten_temanggung";
		/* =============== */
		$this->pgsql_integrated_db_host = "localhost";
		$this->pgsql_integrated_db_port = "5432";
		$this->pgsql_integrated_db_user = "geoserver";
		$this->pgsql_integrated_db_pass = "Sarajevo!99x";
		$this->pgsql_integrated_db_name = "sig_dppkad_temanggung";
		/* =============== */
		$this->webgisworx_db_host = "localhost";
		$this->webgisworx_db_port = "5432";
		$this->webgisworx_db_user = "geoserver";
		$this->webgisworx_db_pass = "Sarajevo!99x";
		$this->webgisworx_db_name = "webgisworx";
		/* =============== */
		$this->mysql_core_db_host = "localhost";
		$this->mysql_core_db_port = "3306";
		$this->mysql_core_db_user = "appx";
		$this->mysql_core_db_pass = "Sarajevo!99x";
		$this->mysql_core_db_name = "mapatda_production";
		/* =============== */
		$this->mysql_integrated_db_host = "localhost";
		$this->mysql_integrated_db_port = "3306";
		$this->mysql_integrated_db_user = "appx";
		$this->mysql_integrated_db_pass = "Sarajevo!99x";
		$this->mysql_integrated_db_name = "mapatda_production";
		/* =============== */
		$this->oracle_sismiop_host		= "192.168.255.100";
		$this->oracle_sismiop_port		= "1521";
		$this->oracle_sismiop_user		= "PBB";
		$this->oracle_sismiop_pass		= "PBB";
		$this->oracle_sismiop_service	= "SISMIOP";
		/* todo next... */
	}
	public function dummy(){
		return $this->dummy_string;
	}
	public function dummyJSON(){
		$this->response = array("ajaxresult"=>"found","ajaxmessage"=>$this->dummy_string);
		return $this->response;
	}
	public function getGeoJSONAdminKabupatenKota () {
		$dbcon = $stmt = $rowset = $items = $response = $geojson = $id_count = $feature = $properties = null;
		$geojson = array('type'=>'FeatureCollection','features'=>array());
		try {
			$dbcon = new PDO("pgsql:host=".$this->pgsql_onemap_db_host.";port=".$this->pgsql_onemap_db_port.";dbname=".$this->pgsql_onemap_db_name.";user=".$this->pgsql_onemap_db_user.";password=".$this->pgsql_onemap_db_pass."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("SELECT gid, kode_provinsi, nama_provinsi, kode_kabupaten_kota, bentuk_kabupaten_kota, nama_kabupaten_kota, nama_bentuk_kabupaten_kota, minx, miny, maxx, maxy, centroid, initzoom, status, public.ST_AsGeoJSON(public.ST_Transform((geom),4326),6) AS geojson FROM appx_core_basemap_kabupaten_kota WHERE gid = 1");
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
				$response = $geojson;
				return $response;
				$dbcon = null;
				exit;
			} else {
				$response = $geojson;
				return $response;
				$dbcon = null;
				exit;
			}
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
			return $response;
			$dbcon = null;
			exit;
		}
	}
	public function getGeoJSONAdminKecamatan () {
		$dbcon = $stmt = $rowset = $items = $response = $geojson = $id_count = $feature = $properties = null;
		$geojson = array('type'=>'FeatureCollection','features'=>array());
		try {
			$dbcon = new PDO("pgsql:host=".$this->pgsql_onemap_db_host.";port=".$this->pgsql_onemap_db_port.";dbname=".$this->pgsql_onemap_db_name.";user=".$this->pgsql_onemap_db_user.";password=".$this->pgsql_onemap_db_pass."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("SELECT gid, kode_provinsi, nama_provinsi, kode_kabupaten_kota, bentuk_kabupaten_kota, nama_kabupaten_kota, nama_bentuk_kabupaten_kota, kode_dagri_kecamatan, kode_sismiop_kecamatan, nama_kecamatan, minx, miny, maxx, maxy, centroid, initzoom, status, public.ST_AsGeoJSON(public.ST_Transform((geom),4326),6) AS geojson FROM appx_core_basemap_kecamatan WHERE status = 1");
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
				$response = $geojson;
				return $response;
				$dbcon = null;
				exit;
			} else {
				$response = $geojson;
				return $response;
				$dbcon = null;
				exit;
			}
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
			return $response;
			$dbcon = null;
			exit;
		}
	}
	public function getJSONKabupatenKota () {
		try {
			$dbcon = new PDO("pgsql:host=".$this->pgsql_onemap_db_host.";port=".$this->pgsql_onemap_db_port.";dbname=".$this->pgsql_onemap_db_name.";user=".$this->pgsql_onemap_db_user.";password=".$this->pgsql_onemap_db_pass."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("SELECT gid, kode_provinsi, nama_provinsi, kode_kabupaten_kota, bentuk_kabupaten_kota, nama_kabupaten_kota, nama_bentuk_kabupaten_kota, minx, miny, maxx, maxy, centroid, initzoom, status FROM appx_core_basemap_kabupaten_kota WHERE status = 1");
			if($stmt->execute()){
				while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
					$items[] = $rowset;
				}
				if (empty($items)) {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
					return $response;
					$dbcon = null;
					exit;
				} else {
					$response = array("ajaxresult"=>"found","dataarray"=>$items);
					return $response;
					$dbcon = null;
					exit;
				}
			} else {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
				return $response;
				$dbcon = null;
				exit;
			}
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
			return $response;
			$dbcon = null;
			exit;
		}
	}
	public function getJSONKecamatan () {
		try {
			$dbcon = new PDO("pgsql:host=".$this->pgsql_onemap_db_host.";port=".$this->pgsql_onemap_db_port.";dbname=".$this->pgsql_onemap_db_name.";user=".$this->pgsql_onemap_db_user.";password=".$this->pgsql_onemap_db_pass."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("SELECT gid, kode_provinsi, nama_provinsi, kode_kabupaten_kota, bentuk_kabupaten_kota, nama_kabupaten_kota, nama_bentuk_kabupaten_kota, kode_dagri_kecamatan, kode_sismiop_kecamatan, nama_kecamatan, minx, miny, maxx, maxy, centroid, initzoom, status FROM appx_core_basemap_kecamatan WHERE status = 1 ORDER BY nama_kecamatan");
			if($stmt->execute()){
				while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
					$items[] = $rowset;
				}
				if (empty($items)) {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
					return $response;
					$dbcon = null;
					exit;
				} else {
					$response = array("ajaxresult"=>"found","dataarray"=>$items);
					return $response;
					$dbcon = null;
					exit;
				}
			} else {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
				return $response;
				$dbcon = null;
				exit;
			}
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
			return $response;
			$dbcon = null;
			exit;
		}
	}
	public function getJSONAllDesaKelurahan () {
		try {
			$dbcon = new PDO("pgsql:host=".$this->pgsql_onemap_db_host.";port=".$this->pgsql_onemap_db_port.";dbname=".$this->pgsql_onemap_db_name.";user=".$this->pgsql_onemap_db_user.";password=".$this->pgsql_onemap_db_pass."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("SELECT gid, kode_provinsi, nama_provinsi, kode_kabupaten_kota, bentuk_kabupaten_kota, nama_kabupaten_kota, nama_bentuk_kabupaten_kota, kode_dagri_kecamatan, kode_sismiop_kecamatan, nama_kecamatan, kode_dagri_desa_kelurahan, kode_sismiop_desa_kelurahan, bentuk_desa_kelurahan, nama_desa_kelurahan, nama_bentuk_desa_kelurahan, minx, miny, maxx, maxy, centroid, initzoom, status FROM appx_core_basemap_desa_kelurahan WHERE status = 1 ORDER BY nama_kecamatan, nama_desa_kelurahan");
			if($stmt->execute()){
				while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
					$items[] = $rowset;
				}
				if (empty($items)) {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
					return $response;
					$dbcon = null;
					exit;
				} else {
					$response = array("ajaxresult"=>"found","dataarray"=>$items);
					return $response;
					$dbcon = null;
					exit;
				}
			} else {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
				return $response;
				$dbcon = null;
				exit;
			}
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
			return $response;
			$dbcon = null;
			exit;
		}
	}
	public function getJSONDagriDesaKelurahan ($kodekecamatan = null) {
		if ($kodekecamatan === null) {
			try {
				$dbcon = new PDO("pgsql:host=".$this->pgsql_onemap_db_host.";port=".$this->pgsql_onemap_db_port.";dbname=".$this->pgsql_onemap_db_name.";user=".$this->pgsql_onemap_db_user.";password=".$this->pgsql_onemap_db_pass."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $dbcon->prepare("SELECT gid, kode_provinsi, nama_provinsi, kode_kabupaten_kota, bentuk_kabupaten_kota, nama_kabupaten_kota, nama_bentuk_kabupaten_kota, kode_dagri_kecamatan, kode_sismiop_kecamatan, nama_kecamatan, kode_dagri_desa_kelurahan, kode_sismiop_desa_kelurahan, bentuk_desa_kelurahan, nama_desa_kelurahan, nama_bentuk_desa_kelurahan, minx, miny, maxx, maxy, centroid, initzoom, status FROM appx_core_basemap_desa_kelurahan WHERE status = 1 ORDER BY nama_kecamatan, nama_desa_kelurahan");
				if($stmt->execute()){
					while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
						$items[] = $rowset;
					}
					if (empty($items)) {
						$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
						return $response;
						$dbcon = null;
						exit;
					} else {
						$response = array("ajaxresult"=>"found","dataarray"=>$items);
						return $response;
						$dbcon = null;
						exit;
					}
				} else {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
					return $response;
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
				return $response;
				$dbcon = null;
				exit;
			}
		} else {
			try {
				$dbcon = new PDO("pgsql:host=".$this->pgsql_onemap_db_host.";port=".$this->pgsql_onemap_db_port.";dbname=".$this->pgsql_onemap_db_name.";user=".$this->pgsql_onemap_db_user.";password=".$this->pgsql_onemap_db_pass."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $dbcon->prepare("SELECT gid, kode_provinsi, nama_provinsi, kode_kabupaten_kota, bentuk_kabupaten_kota, nama_kabupaten_kota, nama_bentuk_kabupaten_kota, kode_dagri_kecamatan, kode_sismiop_kecamatan, nama_kecamatan, kode_dagri_desa_kelurahan, kode_sismiop_desa_kelurahan, bentuk_desa_kelurahan, nama_desa_kelurahan, nama_bentuk_desa_kelurahan, minx, miny, maxx, maxy, centroid, initzoom, status FROM appx_core_basemap_desa_kelurahan WHERE kode_dagri_kecamatan = :kodedagrikecamatan AND status = 1 ORDER BY nama_desa_kelurahan");
				$stmt->bindValue(":kodedagrikecamatan", $kodekecamatan, PDO::PARAM_STR);
				if($stmt->execute()){
					while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
						$items[] = $rowset;
					}
					if (empty($items)) {
						$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
						return $response;
						$dbcon = null;
						exit;
					} else {
						$response = array("ajaxresult"=>"found","dataarray"=>$items);
						return $response;
						$dbcon = null;
						exit;
					}
				} else {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
					return $response;
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
				return $response;
				$dbcon = null;
				exit;
			}
		}
	}
	public function getJSONSISMIOPDesaKelurahan ($kodekecamatan = null) {
		if ($kodekecamatan === null) {
			try {
				$dbcon = new PDO("pgsql:host=".$this->pgsql_onemap_db_host.";port=".$this->pgsql_onemap_db_port.";dbname=".$this->pgsql_onemap_db_name.";user=".$this->pgsql_onemap_db_user.";password=".$this->pgsql_onemap_db_pass."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $dbcon->prepare("SELECT gid, kode_provinsi, nama_provinsi, kode_kabupaten_kota, bentuk_kabupaten_kota, nama_kabupaten_kota, nama_bentuk_kabupaten_kota, kode_dagri_kecamatan, kode_sismiop_kecamatan, nama_kecamatan, kode_dagri_desa_kelurahan, kode_sismiop_desa_kelurahan, bentuk_desa_kelurahan, nama_desa_kelurahan, nama_bentuk_desa_kelurahan, minx, miny, maxx, maxy, centroid, initzoom, status FROM appx_core_basemap_desa_kelurahan WHERE status = 1 ORDER BY nama_kecamatan, nama_desa_kelurahan");
				if($stmt->execute()){
					while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
						$items[] = $rowset;
					}
					if (empty($items)) {
						$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
						return $response;
						$dbcon = null;
						exit;
					} else {
						$response = array("ajaxresult"=>"found","dataarray"=>$items);
						return $response;
						$dbcon = null;
						exit;
					}
				} else {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
					return $response;
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
				return $response;
				$dbcon = null;
				exit;
			}
		} else {
			try {
				$dbcon = new PDO("pgsql:host=".$this->pgsql_onemap_db_host.";port=".$this->pgsql_onemap_db_port.";dbname=".$this->pgsql_onemap_db_name.";user=".$this->pgsql_onemap_db_user.";password=".$this->pgsql_onemap_db_pass."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $dbcon->prepare("SELECT gid, kode_provinsi, nama_provinsi, kode_kabupaten_kota, bentuk_kabupaten_kota, nama_kabupaten_kota, nama_bentuk_kabupaten_kota, kode_dagri_kecamatan, kode_sismiop_kecamatan, nama_kecamatan, kode_dagri_desa_kelurahan, kode_sismiop_desa_kelurahan, bentuk_desa_kelurahan, nama_desa_kelurahan, nama_bentuk_desa_kelurahan, minx, miny, maxx, maxy, centroid, initzoom, status FROM appx_core_basemap_desa_kelurahan WHERE kode_sismiop_kecamatan = :kodesismiopkecamatan AND status = 1 ORDER BY nama_desa_kelurahan");
				$stmt->bindValue(":kodesismiopkecamatan", $kodekecamatan, PDO::PARAM_STR);
				if($stmt->execute()){
					while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
						$items[] = $rowset;
					}
					if (empty($items)) {
						$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
						return $response;
						$dbcon = null;
						exit;
					} else {
						$response = array("ajaxresult"=>"found","dataarray"=>$items);
						return $response;
						$dbcon = null;
						exit;
					}
				} else {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
					return $response;
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
				return $response;
				$dbcon = null;
				exit;
			}
		}
	}
	public function getGeoJSONAdminDesaKelurahan () {
		$dbcon = $stmt = $rowset = $items = $response = $geojson = $id_count = $feature = $properties = null;
		$geojson = array('type'=>'FeatureCollection','features'=>array());
		try {
			$dbcon = new PDO("pgsql:host=".$this->pgsql_onemap_db_host.";port=".$this->pgsql_onemap_db_port.";dbname=".$this->pgsql_onemap_db_name.";user=".$this->pgsql_onemap_db_user.";password=".$this->pgsql_onemap_db_pass."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("SELECT gid, kode_provinsi, nama_provinsi, kode_kabupaten_kota, bentuk_kabupaten_kota, nama_kabupaten_kota, nama_bentuk_kabupaten_kota, kode_dagri_kecamatan, kode_sismiop_kecamatan, nama_kecamatan, kode_dagri_desa_kelurahan, kode_sismiop_desa_kelurahan, bentuk_desa_kelurahan, nama_desa_kelurahan, nama_bentuk_desa_kelurahan, minx, miny, maxx, maxy, centroid, initzoom, status, public.ST_AsGeoJSON(public.ST_Transform((geom),4326),6) AS geojson FROM appx_core_basemap_desa_kelurahan WHERE status = 1");
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
				$response = $geojson;
				return $response;
				$dbcon = null;
				exit;
			} else {
				$response = $geojson;
				return $response;
				$dbcon = null;
				exit;
			}
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
			return $response;
			$dbcon = null;
			exit;
		}
	}
	public function getDataListWPWR(){
		$dbcon = $stmt = $rowset = $items = $response = null;
		try {
			$dbcon = new PDO("mysql:host=".$this->mysql_core_db_host.";dbname=".$this->mysql_core_db_name."","".$this->mysql_core_db_user."","".$this->mysql_core_db_pass."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $dbcon->prepare("SELECT idx,npwpd,origin,nama,alamat,kategori,status FROM appx_wp_base WHERE npwpd != '' AND status = 1 ORDER BY npwpd, nama");
			if($stmt->execute()){
				while($rowset = $stmt->fetch(PDO::FETCH_ASSOC)){
					$items[] = $rowset;
				}
				if (empty($items)) {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
					return $response;
					$dbcon = null;
					exit;
				} else {
					$response = array("ajaxresult"=>"found","dataarray"=>$items);
					return $response;
					$dbcon = null;
					exit;
				}
			} else {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
				return $response;
				$dbcon = null;
				exit;
			}
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
			return $response;
			$dbcon = null;
			exit;
		}
	}
	public function getLocusInfoKabupatenKota ($kode_provinsi = null, $kode_kabupaten_kota = null, $coordinatex = null) {
		$dbcon = $stmt_count_check_kabupaten_kota = 
		$rowset_count_check_kabupaten_kota = $countinside = $stmt_select_area_info = 
		$rowset_select_area_info = $info_gid = $info_kode_provinsi = $info_nama_provinsi = 
		$info_kode_kabupaten_kota = $info_bentuk_kabupaten_kota = $info_nama_kabupaten_kota = 
		$info_nama_bentuk_kabupaten_kota = $info_kode_dagri_kecamatan = $info_kode_sismiop_kecamatan = 
		$info_nama_kecamatan = $info_kode_dagri_desa_kelurahan = $info_kode_sismiop_desa_kelurahan = 
		$info_bentuk_desa_kelurahan = $info_nama_desa_kelurahan = $info_nama_bentuk_desa_kelurahan = 
		$info_minx = $info_miny = $info_maxx = $info_maxy = $info_centroid = $info_initzoom = 
		$info_status = $response = null;
		try {
			$dbcon = new PDO("pgsql:host=".$this->pgsql_onemap_db_host.";port=".$this->pgsql_onemap_db_port.";dbname=".$this->pgsql_onemap_db_name.";user=".$this->pgsql_onemap_db_user.";password=".$this->pgsql_onemap_db_pass."");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			/* todos */
			$stmt_count_check_kabupaten_kota = $dbcon->prepare("SELECT COUNT(*) AS countinside FROM appx_core_basemap_kabupaten_kota WHERE kode_provinsi = :kodeprovinsi AND kode_kabupaten_kota = :kodekabupatenkota AND ST_Contains(geom, ST_GeomFromText(:coordinate, 4326))");
			$stmt_count_check_kabupaten_kota->bindValue(":kodeprovinsi", $kode_provinsi, PDO::PARAM_STR);
			$stmt_count_check_kabupaten_kota->bindValue(":kodekabupatenkota", $kode_kabupaten_kota, PDO::PARAM_STR);
			$stmt_count_check_kabupaten_kota->bindValue(":coordinate", $coordinatex, PDO::PARAM_STR);
			if ($stmt_count_check_kabupaten_kota->execute()) {
				while($rowset_count_check_kabupaten_kota = $stmt_count_check_kabupaten_kota->fetch(PDO::FETCH_ASSOC)) {
					$countinside = $rowset_count_check_kabupaten_kota['countinside'];
				}
				if (intval($countinside)==1) {
					$stmt_select_area_info = $dbcon->prepare("SELECT gid, kode_provinsi, nama_provinsi, kode_kabupaten_kota, bentuk_kabupaten_kota, nama_kabupaten_kota, nama_bentuk_kabupaten_kota, kode_dagri_kecamatan, kode_sismiop_kecamatan, nama_kecamatan, kode_dagri_desa_kelurahan, kode_sismiop_desa_kelurahan, bentuk_desa_kelurahan, nama_desa_kelurahan, nama_bentuk_desa_kelurahan, minx, miny, maxx, maxy, centroid, initzoom, status FROM appx_core_basemap_desa_kelurahan WHERE ST_Contains(geom, ST_GeomFromText(:coordinate, 4326))");
					$stmt_select_area_info->bindValue(":coordinate", $coordinatex, PDO::PARAM_STR);
					$stmt_select_area_info->execute();
					while($rowset_select_area_info = $stmt_select_area_info->fetch(PDO::FETCH_ASSOC)) {
						$info_gid = $rowset_select_area_info['gid'];
						$info_kode_provinsi = $rowset_select_area_info['kode_provinsi'];
						$info_nama_provinsi = $rowset_select_area_info['nama_provinsi'];
						$info_kode_kabupaten_kota = $rowset_select_area_info['kode_kabupaten_kota'];
						$info_bentuk_kabupaten_kota = $rowset_select_area_info['bentuk_kabupaten_kota'];
						$info_nama_kabupaten_kota = $rowset_select_area_info['nama_kabupaten_kota'];
						$info_nama_bentuk_kabupaten_kota = $rowset_select_area_info['nama_bentuk_kabupaten_kota'];
						$info_kode_dagri_kecamatan = $rowset_select_area_info['kode_dagri_kecamatan'];
						$info_kode_sismiop_kecamatan = $rowset_select_area_info['kode_sismiop_kecamatan'];
						$info_nama_kecamatan = $rowset_select_area_info['nama_kecamatan'];
						$info_kode_dagri_desa_kelurahan = $rowset_select_area_info['kode_dagri_desa_kelurahan'];
						$info_kode_sismiop_desa_kelurahan = $rowset_select_area_info['kode_sismiop_desa_kelurahan'];
						$info_bentuk_desa_kelurahan = $rowset_select_area_info['bentuk_desa_kelurahan'];
						$info_nama_desa_kelurahan = $rowset_select_area_info['nama_desa_kelurahan'];
						$info_nama_bentuk_desa_kelurahan = $rowset_select_area_info['nama_bentuk_desa_kelurahan'];
						$info_minx = $rowset_select_area_info['minx'];
						$info_miny = $rowset_select_area_info['miny'];
						$info_maxx = $rowset_select_area_info['maxx'];
						$info_maxy = $rowset_select_area_info['maxy'];
						$info_centroid = $rowset_select_area_info['centroid'];
						$info_initzoom = $rowset_select_area_info['initzoom'];
						$info_status = $rowset_select_area_info['status'];
					}
					$response = array("ajaxresult"=>"inside","gid"=>$info_gid,"kode_provinsi"=>$info_kode_provinsi,"nama_provinsi"=>$info_nama_provinsi,"kode_kabupaten_kota"=>$info_kode_kabupaten_kota,"bentuk_kabupaten_kota"=>$info_bentuk_kabupaten_kota,"nama_kabupaten_kota"=>$info_nama_kabupaten_kota,"nama_bentuk_kabupaten_kota"=>$info_nama_bentuk_kabupaten_kota,"kode_dagri_kecamatan"=>$info_kode_dagri_kecamatan,"kode_sismiop_kecamatan"=>$info_kode_sismiop_kecamatan,"nama_kecamatan"=>$info_nama_kecamatan,"kode_dagri_desa_kelurahan"=>$info_kode_dagri_desa_kelurahan,"kode_sismiop_desa_kelurahan"=>$info_kode_sismiop_desa_kelurahan,"bentuk_desa_kelurahan"=>$info_bentuk_desa_kelurahan,"nama_desa_kelurahan"=>$info_nama_desa_kelurahan,"nama_bentuk_desa_kelurahan"=>$info_nama_bentuk_desa_kelurahan,"minx"=>$info_minx,"miny"=>$info_miny,"maxx"=>$info_maxx,"maxy"=>$info_maxy,"centroid"=>$info_centroid,"initzoom"=>$info_initzoom,"status"=>$info_status);
					return $response;
				} else {
					$response = array("ajaxresult"=>"outside");
					$dbcon = null;
					return $response;
				}
			} else {
				$response = array("ajaxresult"=>"error","ajaxmessage"=>$e->getMessage());
				$dbcon = null;
				return $response;
			}
		} catch (PDOException $e) {
			$response = array("ajaxresult"=>"error","ajaxmessage"=>$e->getMessage());
			$dbcon = null;
			return $response;
		}
	}
	public function getLocusInfoKabupatenKotaKecamatan ($kode_kecamatan = null, $coordinatex = null) {
		
	}
	public function getLocusInfoKabupatenKotaKecamatanDesaKelurahan ($kode_kecamatan = null, $kode_desa_kelurahan = null, $coordinatex = null) {
		
	}
	/* #################### project "Zanzibar Leopard" #################### */
	public function webgisworxGetGeoJSONAdminKabupatenKota ($kabupaten = null) {
		if ($kabupaten == "TORAJA UTARA") {
			$dbcon = $stmt = $rowset = $items = $response = $geojson = $id_count = $feature = $properties = null;
			$geojson = array('type'=>'FeatureCollection','features'=>array());
			try {
				$dbcon = new PDO("pgsql:host=".$this->webgisworx_db_host.";port=".$this->webgisworx_db_port.";dbname=".$this->webgisworx_db_name.";user=".$this->webgisworx_db_user.";password=".$this->webgisworx_db_pass."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $dbcon->prepare("SELECT gid, kode_prov, nama_prov, kode_kab, tipe_kab, nama_kab, public.ST_AsGeoJSON(public.ST_Transform((geom),4326),6) AS geojson FROM raw_kabupaten WHERE gid = 1");
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
					$response = $geojson;
					return $response;
					$dbcon = null;
					exit;
				} else {
					$response = $geojson;
					return $response;
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
				return $response;
				$dbcon = null;
				exit;
			}
		} else {
			return "undefined";
		}
	}
	public function webgisworxGetGeoJSONAdminKecamatan ($kabupaten = null) {
		if ($kabupaten == "TORAJA UTARA") {
			$dbcon = $stmt = $rowset = $items = $response = $geojson = $id_count = $feature = $properties = null;
			$geojson = array('type'=>'FeatureCollection','features'=>array());
			try {
				$dbcon = new PDO("pgsql:host=".$this->webgisworx_db_host.";port=".$this->webgisworx_db_port.";dbname=".$this->webgisworx_db_name.";user=".$this->webgisworx_db_user.";password=".$this->webgisworx_db_pass."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $dbcon->prepare("SELECT gid, kode_prov, nama_prov, kode_kab, tipe_kab, nama_kab, kode_kec, nama_kec, public.ST_AsGeoJSON(public.ST_Transform((geom),4326),6) AS geojson FROM raw_kecamatan");
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
					$response = $geojson;
					return $response;
					$dbcon = null;
					exit;
				} else {
					$response = $geojson;
					return $response;
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
				return $response;
				$dbcon = null;
				exit;
			}
		} else {
			return "undefined";
		}
	}
	public function webgisworxGetGeoJSONAdminDesaKelurahan ($kabupaten = null) {
		if ($kabupaten == "TORAJA UTARA") {
			$dbcon = $stmt = $rowset = $items = $response = $geojson = $id_count = $feature = $properties = null;
			$geojson = array('type'=>'FeatureCollection','features'=>array());
			try {
				$dbcon = new PDO("pgsql:host=".$this->webgisworx_db_host.";port=".$this->webgisworx_db_port.";dbname=".$this->webgisworx_db_name.";user=".$this->webgisworx_db_user.";password=".$this->webgisworx_db_pass."");
				$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $dbcon->prepare("SELECT gid, kode_prov, nama_prov, kode_kab, tipe_kab, nama_kab, kode_kec, nama_kec, kode_desa, tipe_desa, nama_desa, public.ST_AsGeoJSON(public.ST_Transform((geom),4326),6) AS geojson FROM raw_desa");
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
					$response = $geojson;
					return $response;
					$dbcon = null;
					exit;
				} else {
					$response = $geojson;
					return $response;
					$dbcon = null;
					exit;
				}
			} catch (PDOException $e) {
				$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
				return $response;
				$dbcon = null;
				exit;
			}
		} else {
			return "undefined";
		}
	}
	public function webgisworxGetGeoJSONThematic ($context = null, $object = null) {
		if ($context == "TORAJA UTARA") {
			if ($object == "JALAN") {
				$dbcon = $stmt = $rowset = $items = $response = $geojson = $id_count = $feature = $properties = null;
				$geojson = array('type'=>'FeatureCollection','features'=>array());
				try {
					$dbcon = new PDO("pgsql:host=".$this->webgisworx_db_host.";port=".$this->webgisworx_db_port.";dbname=".$this->webgisworx_db_name.";user=".$this->webgisworx_db_user.";password=".$this->webgisworx_db_pass."");
					$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$stmt = $dbcon->prepare("SELECT gid,kl_dat_das, COALESCE(nm_ruas, '-') AS nm_ruas, thn_data, COALESCE(status,'undefined') AS status, fungsi, mendukung, ura_dukung, kd_bd_pu, kd_jns_inf, kd_inf, propinsi, kab_kot, kecamatan, desa_kel, tk_ruas_aw, tk_ruas_ak, kd_patok, km_awal, km_akhir, nm_lintas, kon_baik, kon_sdg, kon_rgn, kon_rusak, kon_mntp, kon_t_mntp, panjang, lbr_keras, lhrt, vcr, tipe_jln, mst, tipe_keras, tanah_kri, macadam, aspal, rigid, thn_pen_ak, jns_pen, koord_x_aw, koord_y_aw, koord_x_ak, koord_y_ak, shape_leng, nm_pangkal, nm_ujung, kb_dra_kir, kb_dra_kan, foto_rj, pan_kri, pan_macada, pan_aspal, pan_ringid, foto_pang, foto_ujung, no_ruas, public.ST_AsGeoJSON(public.ST_Transform((geom),4326),6) AS geojson FROM raw_jalan");
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
						$response = $geojson;
						return $response;
						$dbcon = null;
						exit;
					} else {
						$response = $geojson;
						return $response;
						$dbcon = null;
						exit;
					}
				} catch (PDOException $e) {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
					return $response;
					$dbcon = null;
					exit;
				}
			} elseif ($object == "JALAN_LINGKUNGAN") {
				$dbcon = $stmt = $rowset = $items = $response = $geojson = $id_count = $feature = $properties = null;
				$geojson = array('type'=>'FeatureCollection','features'=>array());
				try {
					$dbcon = new PDO("pgsql:host=".$this->webgisworx_db_host.";port=".$this->webgisworx_db_port.";dbname=".$this->webgisworx_db_name.";user=".$this->webgisworx_db_user.";password=".$this->webgisworx_db_pass."");
					$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$stmt = $dbcon->prepare("SELECT gid,kl_dat_das, COALESCE(nm_ruas, '-') AS nm_ruas, thn_data, COALESCE(status,'undefined') AS status, fungsi, mendukung, ura_dukung, kd_bd_pu, kd_jns_inf, kd_inf, propinsi, kab_kot, kecamatan, desa_kel, tk_ruas_aw, tk_ruas_ak, kd_patok, km_awal, km_akhir, nm_lintas, kon_baik, kon_sdg, kon_rgn, kon_rusak, kon_mntp, kon_t_mntp, panjang, lbr_keras, lhrt, vcr, tipe_jln, mst, tipe_keras, tanah_kri, macadam, aspal, rigid, thn_pen_ak, jns_pen, koord_x_aw, koord_y_aw, koord_x_ak, koord_y_ak, shape_leng, nm_pangkal, nm_ujung, kb_dra_kir, kb_dra_kan, foto_rj, pan_kri, pan_macada, pan_aspal, pan_ringid, foto_pang, foto_ujung, no_ruas, public.ST_AsGeoJSON(public.ST_Transform((geom),4326),6) AS geojson FROM raw_jalan WHERE status = 'Jalan Lingkungan'");
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
						$response = $geojson;
						return $response;
						$dbcon = null;
						exit;
					} else {
						$response = $geojson;
						return $response;
						$dbcon = null;
						exit;
					}
				} catch (PDOException $e) {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
					return $response;
					$dbcon = null;
					exit;
				}
			} elseif ($object == "JALAN_KABUPATEN") {
				$dbcon = $stmt = $rowset = $items = $response = $geojson = $id_count = $feature = $properties = null;
				$geojson = array('type'=>'FeatureCollection','features'=>array());
				try {
					$dbcon = new PDO("pgsql:host=".$this->webgisworx_db_host.";port=".$this->webgisworx_db_port.";dbname=".$this->webgisworx_db_name.";user=".$this->webgisworx_db_user.";password=".$this->webgisworx_db_pass."");
					$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$stmt = $dbcon->prepare("SELECT gid,kl_dat_das, COALESCE(nm_ruas, '-') AS nm_ruas, thn_data, COALESCE(status,'undefined') AS status, fungsi, mendukung, ura_dukung, kd_bd_pu, kd_jns_inf, kd_inf, propinsi, kab_kot, kecamatan, desa_kel, tk_ruas_aw, tk_ruas_ak, kd_patok, km_awal, km_akhir, nm_lintas, kon_baik, kon_sdg, kon_rgn, kon_rusak, kon_mntp, kon_t_mntp, panjang, lbr_keras, lhrt, vcr, tipe_jln, mst, tipe_keras, tanah_kri, macadam, aspal, rigid, thn_pen_ak, jns_pen, koord_x_aw, koord_y_aw, koord_x_ak, koord_y_ak, shape_leng, nm_pangkal, nm_ujung, kb_dra_kir, kb_dra_kan, foto_rj, pan_kri, pan_macada, pan_aspal, pan_ringid, foto_pang, foto_ujung, no_ruas, public.ST_AsGeoJSON(public.ST_Transform((geom),4326),6) AS geojson FROM raw_jalan WHERE status = 'Jalan Kabupaten'");
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
						$response = $geojson;
						return $response;
						$dbcon = null;
						exit;
					} else {
						$response = $geojson;
						return $response;
						$dbcon = null;
						exit;
					}
				} catch (PDOException $e) {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
					return $response;
					$dbcon = null;
					exit;
				}
			} elseif ($object == "JALAN_DALAM_KOTA") {
				$dbcon = $stmt = $rowset = $items = $response = $geojson = $id_count = $feature = $properties = null;
				$geojson = array('type'=>'FeatureCollection','features'=>array());
				try {
					$dbcon = new PDO("pgsql:host=".$this->webgisworx_db_host.";port=".$this->webgisworx_db_port.";dbname=".$this->webgisworx_db_name.";user=".$this->webgisworx_db_user.";password=".$this->webgisworx_db_pass."");
					$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$stmt = $dbcon->prepare("SELECT gid,kl_dat_das, COALESCE(nm_ruas, '-') AS nm_ruas, thn_data, COALESCE(status,'undefined') AS status, fungsi, mendukung, ura_dukung, kd_bd_pu, kd_jns_inf, kd_inf, propinsi, kab_kot, kecamatan, desa_kel, tk_ruas_aw, tk_ruas_ak, kd_patok, km_awal, km_akhir, nm_lintas, kon_baik, kon_sdg, kon_rgn, kon_rusak, kon_mntp, kon_t_mntp, panjang, lbr_keras, lhrt, vcr, tipe_jln, mst, tipe_keras, tanah_kri, macadam, aspal, rigid, thn_pen_ak, jns_pen, koord_x_aw, koord_y_aw, koord_x_ak, koord_y_ak, shape_leng, nm_pangkal, nm_ujung, kb_dra_kir, kb_dra_kan, foto_rj, pan_kri, pan_macada, pan_aspal, pan_ringid, foto_pang, foto_ujung, no_ruas, public.ST_AsGeoJSON(public.ST_Transform((geom),4326),6) AS geojson FROM raw_jalan WHERE status = 'Jalan Kabupaten/Jalan Dalam Kota'");
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
						$response = $geojson;
						return $response;
						$dbcon = null;
						exit;
					} else {
						$response = $geojson;
						return $response;
						$dbcon = null;
						exit;
					}
				} catch (PDOException $e) {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
					return $response;
					$dbcon = null;
					exit;
				}
			} elseif ($object == "JALAN_DESA") {
				$dbcon = $stmt = $rowset = $items = $response = $geojson = $id_count = $feature = $properties = null;
				$geojson = array('type'=>'FeatureCollection','features'=>array());
				try {
					$dbcon = new PDO("pgsql:host=".$this->webgisworx_db_host.";port=".$this->webgisworx_db_port.";dbname=".$this->webgisworx_db_name.";user=".$this->webgisworx_db_user.";password=".$this->webgisworx_db_pass."");
					$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$stmt = $dbcon->prepare("SELECT gid,kl_dat_das, COALESCE(nm_ruas, '-') AS nm_ruas, thn_data, COALESCE(status,'undefined') AS status, fungsi, mendukung, ura_dukung, kd_bd_pu, kd_jns_inf, kd_inf, propinsi, kab_kot, kecamatan, desa_kel, tk_ruas_aw, tk_ruas_ak, kd_patok, km_awal, km_akhir, nm_lintas, kon_baik, kon_sdg, kon_rgn, kon_rusak, kon_mntp, kon_t_mntp, panjang, lbr_keras, lhrt, vcr, tipe_jln, mst, tipe_keras, tanah_kri, macadam, aspal, rigid, thn_pen_ak, jns_pen, koord_x_aw, koord_y_aw, koord_x_ak, koord_y_ak, shape_leng, nm_pangkal, nm_ujung, kb_dra_kir, kb_dra_kan, foto_rj, pan_kri, pan_macada, pan_aspal, pan_ringid, foto_pang, foto_ujung, no_ruas, public.ST_AsGeoJSON(public.ST_Transform((geom),4326),6) AS geojson FROM raw_jalan WHERE status = 'Jalan Desa'");
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
						$response = $geojson;
						return $response;
						$dbcon = null;
						exit;
					} else {
						$response = $geojson;
						return $response;
						$dbcon = null;
						exit;
					}
				} catch (PDOException $e) {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
					return $response;
					$dbcon = null;
					exit;
				}
			} elseif ($object == "JALAN_NASIONAL") {
				$dbcon = $stmt = $rowset = $items = $response = $geojson = $id_count = $feature = $properties = null;
				$geojson = array('type'=>'FeatureCollection','features'=>array());
				try {
					$dbcon = new PDO("pgsql:host=".$this->webgisworx_db_host.";port=".$this->webgisworx_db_port.";dbname=".$this->webgisworx_db_name.";user=".$this->webgisworx_db_user.";password=".$this->webgisworx_db_pass."");
					$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$stmt = $dbcon->prepare("SELECT gid,kl_dat_das, COALESCE(nm_ruas, '-') AS nm_ruas, thn_data, COALESCE(status,'undefined') AS status, fungsi, mendukung, ura_dukung, kd_bd_pu, kd_jns_inf, kd_inf, propinsi, kab_kot, kecamatan, desa_kel, tk_ruas_aw, tk_ruas_ak, kd_patok, km_awal, km_akhir, nm_lintas, kon_baik, kon_sdg, kon_rgn, kon_rusak, kon_mntp, kon_t_mntp, panjang, lbr_keras, lhrt, vcr, tipe_jln, mst, tipe_keras, tanah_kri, macadam, aspal, rigid, thn_pen_ak, jns_pen, koord_x_aw, koord_y_aw, koord_x_ak, koord_y_ak, shape_leng, nm_pangkal, nm_ujung, kb_dra_kir, kb_dra_kan, foto_rj, pan_kri, pan_macada, pan_aspal, pan_ringid, foto_pang, foto_ujung, no_ruas, public.ST_AsGeoJSON(public.ST_Transform((geom),4326),6) AS geojson FROM raw_jalan WHERE status = 'Jalan Nasional'");
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
						$response = $geojson;
						return $response;
						$dbcon = null;
						exit;
					} else {
						$response = $geojson;
						return $response;
						$dbcon = null;
						exit;
					}
				} catch (PDOException $e) {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
					return $response;
					$dbcon = null;
					exit;
				}
			} elseif ($object == "JALAN_PROVINSI") {
				$dbcon = $stmt = $rowset = $items = $response = $geojson = $id_count = $feature = $properties = null;
				$geojson = array('type'=>'FeatureCollection','features'=>array());
				try {
					$dbcon = new PDO("pgsql:host=".$this->webgisworx_db_host.";port=".$this->webgisworx_db_port.";dbname=".$this->webgisworx_db_name.";user=".$this->webgisworx_db_user.";password=".$this->webgisworx_db_pass."");
					$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$stmt = $dbcon->prepare("SELECT gid,kl_dat_das, COALESCE(nm_ruas, '-') AS nm_ruas, thn_data, COALESCE(status,'undefined') AS status, fungsi, mendukung, ura_dukung, kd_bd_pu, kd_jns_inf, kd_inf, propinsi, kab_kot, kecamatan, desa_kel, tk_ruas_aw, tk_ruas_ak, kd_patok, km_awal, km_akhir, nm_lintas, kon_baik, kon_sdg, kon_rgn, kon_rusak, kon_mntp, kon_t_mntp, panjang, lbr_keras, lhrt, vcr, tipe_jln, mst, tipe_keras, tanah_kri, macadam, aspal, rigid, thn_pen_ak, jns_pen, koord_x_aw, koord_y_aw, koord_x_ak, koord_y_ak, shape_leng, nm_pangkal, nm_ujung, kb_dra_kir, kb_dra_kan, foto_rj, pan_kri, pan_macada, pan_aspal, pan_ringid, foto_pang, foto_ujung, no_ruas, public.ST_AsGeoJSON(public.ST_Transform((geom),4326),6) AS geojson FROM raw_jalan WHERE status = 'Jalan Propinsi'");
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
						$response = $geojson;
						return $response;
						$dbcon = null;
						exit;
					} else {
						$response = $geojson;
						return $response;
						$dbcon = null;
						exit;
					}
				} catch (PDOException $e) {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
					return $response;
					$dbcon = null;
					exit;
				}
			} elseif ($object == "JALAN_UNCLASSIFIED_UNKNOWN") {
				$dbcon = $stmt = $rowset = $items = $response = $geojson = $id_count = $feature = $properties = null;
				$geojson = array('type'=>'FeatureCollection','features'=>array());
				try {
					$dbcon = new PDO("pgsql:host=".$this->webgisworx_db_host.";port=".$this->webgisworx_db_port.";dbname=".$this->webgisworx_db_name.";user=".$this->webgisworx_db_user.";password=".$this->webgisworx_db_pass."");
					$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$stmt = $dbcon->prepare("SELECT gid,kl_dat_das, COALESCE(nm_ruas, '-') AS nm_ruas, thn_data, COALESCE(status,'undefined') AS status, fungsi, mendukung, ura_dukung, kd_bd_pu, kd_jns_inf, kd_inf, propinsi, kab_kot, kecamatan, desa_kel, tk_ruas_aw, tk_ruas_ak, kd_patok, km_awal, km_akhir, nm_lintas, kon_baik, kon_sdg, kon_rgn, kon_rusak, kon_mntp, kon_t_mntp, panjang, lbr_keras, lhrt, vcr, tipe_jln, mst, tipe_keras, tanah_kri, macadam, aspal, rigid, thn_pen_ak, jns_pen, koord_x_aw, koord_y_aw, koord_x_ak, koord_y_ak, shape_leng, nm_pangkal, nm_ujung, kb_dra_kir, kb_dra_kan, foto_rj, pan_kri, pan_macada, pan_aspal, pan_ringid, foto_pang, foto_ujung, no_ruas, public.ST_AsGeoJSON(public.ST_Transform((geom),4326),6) AS geojson FROM raw_jalan WHERE status IS NULL");
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
						$response = $geojson;
						return $response;
						$dbcon = null;
						exit;
					} else {
						$response = $geojson;
						return $response;
						$dbcon = null;
						exit;
					}
				} catch (PDOException $e) {
					$response = array("ajaxresult"=>"notfound","ajaxmessage"=>$e->getMessage());
					return $response;
					$dbcon = null;
					exit;
				}
			} else {
				
			}
		} else {
			return "undefined";
		}
	}
	/* #################### project torut #################### */
	/* todo next... */
}
?>