<?php
class dasFunktionz {
	/* =============== */
	public $dummy_string;
	/* =============== */
	public $server_address;
	public $inbound_server_address;
	public $outbound_server_address;
	/* Application Name and Owner Info */
	public $appx_name;
	public $appx_short_name;
	public $appx_version;
	public $appx_company;
	public $jenis_daerah;
	public $nama_daerah;
	public $logo_daerah_big;
	public $logo_daerah_medium;
	public $logo_daerah_small;
	public $logo_skpd_big;
	public $logo_skpd_medium;
	public $logo_skpd_small;
	/* ---------------------- */
	public $dev_db_host;
	public $dev_db_port;
	public $dev_db_user;
	public $dev_db_pass;
	public $dev_db_name;
	public $db_host;
	public $db_port;
	public $db_user;
	public $db_pass;
	public $db_name;
	public $sig_db_host;
	public $sig_db_port;
	public $sig_db_user;
	public $sig_db_pass;
	public $sig_db_name;
	public $oracle_access;
	public $oracle_host;
	public $oracle_port;
	public $oracle_user;
	public $oracle_pass;
	public $oracle_service;
	public $tahun_pajak;
	/* =============== */
	public $userid;
	public $userspvid;
	public $usercontext;
  public $userrealname;
  public $userinitial;
  public $userusername;
  public $userwordpass;
  public $usermodule;
  public $useraddmodule;
  public $isverificator;
  public $iscollector;
  public $userstatus;
	/* =============== */
	public $arrAddMenu;
	public $stringToProcess;
	public $emptyArray;
	public $htmlstring;
	public $inArrayCount;
	/*
	 * Class (parameter) constructor.
	 */
	public function __construct(){
		$numArgs = func_num_args();
		if ($numArgs == 0 || $numArgs == null) {
			/* ------------------------------------------ */

			/* ------------------------------------------ */
		} else {
			/* ------------------------------------------ */

			/* ------------------------------------------ */
		}
		if (isset($_SESSION['mapatdaXin']) && isset($_SESSION['appxINFO'])) {
			/* ------------------------------------------ */
			$this->server_address = $_SESSION['appxINFO']['_server_inbound_ip_address_'];
			$this->inbound_server_address = $_SESSION['appxINFO']['_server_inbound_ip_address_'];
			$this->outbound_server_address = $_SESSION['appxINFO']['_server_outbound_ip_address_'];
			$this->appx_name = $_SESSION['appxINFO']['_appx_name_'];
			$this->appx_short_name = $_SESSION['appxINFO']['_appx_short_name_'];
			$this->appx_version = $_SESSION['appxINFO']['_appx_version_'];
			$this->appx_company = $_SESSION['appxINFO']['_appx_company_'];
			$this->jenis_daerah = $_SESSION['appxINFO']['_JN_DATI2_'];
			$this->nama_daerah = $_SESSION['appxINFO']['_NM_DATI2_'];
			$this->logo_daerah_big = $_SESSION['appxINFO']['_LOGO_DATI2_BIG_'];
			$this->logo_daerah_medium = $_SESSION['appxINFO']['_LOGO_DATI2_MEDIUM_'];
			$this->logo_daerah_small = $_SESSION['appxINFO']['_LOGO_DATI2_SMALL_'];
			$this->logo_skpd_big = $_SESSION['appxINFO']['_LOGO_DATI2_BIG_'];
			$this->logo_skpd_medium = $_SESSION['appxINFO']['_LOGO_DATI2_MEDIUM_'];
			$this->logo_skpd_small = $_SESSION['appxINFO']['_LOGO_DATI2_SMALL_'];
			$this->dev_db_host = $_SESSION['appxINFO']['_dev_db_host_'];
			$this->dev_db_port = $_SESSION['appxINFO']['_dev_db_port_'];
			$this->dev_db_user = $_SESSION['appxINFO']['_dev_db_user_'];
			$this->dev_db_pass = $_SESSION['appxINFO']['_dev_db_pass_'];
			$this->dev_db_name = $_SESSION['appxINFO']['_dev_db_name_'];
			$this->db_host = $_SESSION['appxINFO']['_db_host_'];
			$this->db_port = $_SESSION['appxINFO']['_db_port_'];
			$this->db_user = $_SESSION['appxINFO']['_db_user_'];
			$this->db_pass = $_SESSION['appxINFO']['_db_pass_'];
			$this->db_name = $_SESSION['appxINFO']['_db_name_'];
			$this->sig_db_host = $_SESSION['appxINFO']['_sig_db_host_'];
			$this->sig_db_port = $_SESSION['appxINFO']['_sig_db_port_'];
			$this->sig_db_user = $_SESSION['appxINFO']['_sig_db_user_'];
			$this->sig_db_pass = $_SESSION['appxINFO']['_sig_db_pass_'];
			$this->sig_db_name = $_SESSION['appxINFO']['_sig_db_name_'];
			$this->oracle_access = $_SESSION['appxINFO']['_oracle_sismiop_access_'];
			$this->oracle_host = $_SESSION['appxINFO']['_oracle_sismiop_host_'];
			$this->oracle_port = $_SESSION['appxINFO']['_oracle_sismiop_port_'];
			$this->oracle_user = $_SESSION['appxINFO']['_oracle_sismiop_user_'];
			$this->oracle_pass = $_SESSION['appxINFO']['_oracle_sismiop_pass_'];
			$this->oracle_service = $_SESSION['appxINFO']['_oracle_sismiop_service_'];
			$this->tahun_pajak = $_SESSION['appxINFO']['_tahun_pajak_'];
			/* ------------------------------------------ */
			$this->userid = $_SESSION['mapatdaXin']['idx'];
			$this->userspvid = $_SESSION['mapatdaXin']['supervisor_idx'];
			$this->usercontext = $_SESSION['mapatdaXin']['context'];
			$this->userrealname = $_SESSION['mapatdaXin']['realname'];
			$this->userinitial = $_SESSION['mapatdaXin']['initial'];
			$this->userusername = $_SESSION['mapatdaXin']['username'];
			$this->userwordpass = $_SESSION['mapatdaXin']['wordpass'];
			$this->usermodule = $_SESSION['mapatdaXin']['module'];
			$this->useraddmodule = $_SESSION['mapatdaXin']['add_module'];
			$this->isverificator = $_SESSION['mapatdaXin']['verificator'];
			$this->iscollector = $_SESSION['mapatdaXin']['collector'];
			$this->userstatus = $_SESSION['mapatdaXin']['status'];
			/* ------------------------------------------ */
		} else {
			/* ------------------------------------------ */
			
			/* ------------------------------------------ */
		}
		$this->dummy_string = "Hello, this is a dummy string!";
		$this->stringToProcess = "";
		$this->emptyArray = null;
	}
	public function dummy(){
		return $this->dummy_string;
	}
	/*
	 * TOP-NAVIGATION MENU
	 */
	public function generate_topnav_menu(){
		$iconHome = "fa fa-home";
		$iconPelayanan = "fa fa-shield";
		$iconPendataan = "fa fa-pencil";
		$iconBPHTB = "fa fa-sitemap";
		$iconPenetapan = "fa fa-gavel";
		$iconPenagihan = "fa fa-shopping-basket";
		$iconPengelolaan = "fa fa-cogs";
		$iconPengendalian = "fa fa-paper-plane";
		$iconPengaturan = "fa fa-cog";
		$iconKeluar = "fa fa-power-off";
		$iconLaporan = "fa fa-database";
		$iconDevelopment = "fa fa-github-alt";
		if ($this->usercontext == "BPPKAD") {
			if ($this->usermodule == "pendataanplanspv") {								/* KABID PENDATAAN */
				$html5_component = "" .
					"<nav class='navbar navbar-inverse navbar-fixed-top'>" .
						"<div class='container-fluid'>" .
							"<div class='navbar-header'>" .
								"<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>" .
									"<span class='sr-only'>Toggle navigation</span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
								"</button>" .
								"<a class='navbar-brand' href='./'>".$this->appx_short_name."</a>" .
							"</div>" .
							"<div id='navbar' class='navbar-collapse collapse'>" .
								"<!-- LEFT Menus -->" .
								"<ul class='nav navbar-nav navbar-left'>" .
									"<li class='top-menu active'><a id='home' href='#' class='ajax-menu'><i class='".$iconHome."'></i>&nbsp;Home</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|data-supervisor-pendataanplansvc' href='#' class='ajax-menu'><i class='".$iconPelayanan."'></i>&nbsp;Data Bidang I</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|report-supervisor-pendataanplansvc' href='#' class='ajax-menu'><i class='".$iconLaporan."'></i>&nbsp;Laporan</a></li>" .
									$this->generate_additional_menu() .
								"</ul>" .
								"<!-- /LEFT Menus -->" .
								"<!-- RIGHT Menus -->" .
								"<ul class='nav navbar-nav navbar-right'>" .
									"<li class='top-menu'><a id='left-sidebar-app|settings' href='#' class='ajax-menu'><i class='".$iconPengaturan."'></i>&nbsp;Pengaturan</a></li>" .
									"<li class='top-menu'><a id='exit' href='../ajax_json_engine.php?cmdx=exit'><i class='".$iconKeluar."'></i>&nbsp;Keluar</a></li>" .
								"</ul>" .
							"</div>" .
						"</div>" .
					"</nav>";
				echo("".$html5_component."");
			} elseif ($this->usermodule == "pendataanplansvccrd") {				/* KASI PELAYANAN */
				$html5_component = "" .
					"<nav class='navbar navbar-inverse navbar-fixed-top'>" .
						"<div class='container-fluid'>" .
							"<div class='navbar-header'>" .
								"<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>" .
									"<span class='sr-only'>Toggle navigation</span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
								"</button>" .
								"<a class='navbar-brand' href='./'>".$this->appx_short_name."</a>" .
							"</div>" .
							"<div id='navbar' class='navbar-collapse collapse'>" .
								"<!-- LEFT Menus -->" .
								"<ul class='nav navbar-nav navbar-left'>" .
									"<li class='top-menu active'><a id='home' href='#' class='ajax-menu'><i class='".$iconHome."'></i>&nbsp;Home</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|data-coordinator-pelayanan' href='#' class='ajax-menu'><i class='".$iconPelayanan."'></i>&nbsp;Data Pelayanan</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|report-coordinator-pelayanan' href='#' class='ajax-menu'><i class='".$iconLaporan."'></i>&nbsp;Laporan</a></li>" .
									$this->generate_additional_menu() .
								"</ul>" .
								"<!-- /LEFT Menus -->" .
								"<!-- RIGHT Menus -->" .
								"<ul class='nav navbar-nav navbar-right'>" .
									"<li class='top-menu'><a id='left-sidebar-app|settings' href='#' class='ajax-menu'><i class='".$iconPengaturan."'></i>&nbsp;Pengaturan</a></li>" .
									"<li class='top-menu'><a id='exit' href='../ajax_json_engine.php?cmdx=exit'><i class='".$iconKeluar."'></i>&nbsp;Keluar</a></li>" .
								"</ul>" .
							"</div>" .
						"</div>" .
					"</nav>";
				echo("".$html5_component."");
			} elseif ($this->usermodule == "pendataanplansvcstaff") {			/* STAFF PELAYANAN */
				$html5_component = "" .
					"<nav class='navbar navbar-inverse navbar-fixed-top'>" .
						"<div class='container-fluid'>" .
							"<div class='navbar-header'>" .
								"<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>" .
									"<span class='sr-only'>Toggle navigation</span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
								"</button>" .
								"<a class='navbar-brand' href='./'>".$this->appx_short_name."</a>" .
							"</div>" .
							"<div id='navbar' class='navbar-collapse collapse'>" .
								"<!-- LEFT Menus -->" .
								"<ul class='nav navbar-nav navbar-left'>" .
									"<li class='top-menu active'><a id='home' href='#' class='ajax-menu'><i class='".$iconHome."'></i>&nbsp;Home</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|data-staff-pelayanan' href='#' class='ajax-menu'><i class='".$iconPelayanan."'></i>&nbsp;Data</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|report-staff-pelayanan' href='#' class='ajax-menu'><i class='".$iconLaporan."'></i>&nbsp;Laporan</a></li>" .
									$this->generate_additional_menu() .
								"</ul>" .
								"<!-- /LEFT Menus -->" .
								"<!-- RIGHT Menus -->" .
								"<ul class='nav navbar-nav navbar-right'>" .
									"<li class='top-menu'><a id='left-sidebar-app|settings' href='#' class='ajax-menu'><i class='".$iconPengaturan."'></i>&nbsp;Pengaturan</a></li>" .
									"<li class='top-menu'><a id='exit' href='../ajax_json_engine.php?cmdx=exit'><i class='".$iconKeluar."'></i>&nbsp;Keluar</a></li>" .
								"</ul>" .
							"</div>" .
						"</div>" .
					"</nav>";
				echo("".$html5_component."");
			} elseif ($this->usermodule == "pendataanplanbcfcrd") {				/* KASI PERENCANAAN/PENDATAAN */
				$html5_component = "" .
					"<nav class='navbar navbar-inverse navbar-fixed-top'>" .
						"<div class='container-fluid'>" .
							"<div class='navbar-header'>" .
								"<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>" .
									"<span class='sr-only'>Toggle navigation</span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
								"</button>" .
								"<a class='navbar-brand' href='./'>".$this->appx_short_name."</a>" .
							"</div>" .
							"<div id='navbar' class='navbar-collapse collapse'>" .
								"<!-- LEFT Menus -->" .
								"<ul class='nav navbar-nav navbar-left'>" .
									"<li class='top-menu active'><a id='home' href='#' class='ajax-menu'><i class='".$iconHome."'></i>&nbsp;Home</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|data-coordinator-pendataan' href='#' class='ajax-menu'><i class='".$iconPelayanan."'></i>&nbsp;Data Perencanaan</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|report-coordinator-pendataan' href='#' class='ajax-menu'><i class='".$iconLaporan."'></i>&nbsp;Laporan</a></li>" .
									$this->generate_additional_menu() .
								"</ul>" .
								"<!-- /LEFT Menus -->" .
								"<!-- RIGHT Menus -->" .
								"<ul class='nav navbar-nav navbar-right'>" .
									"<li class='top-menu'><a id='left-sidebar-app|settings' href='#' class='ajax-menu'><i class='".$iconPengaturan."'></i>&nbsp;Pengaturan</a></li>" .
									"<li class='top-menu'><a id='exit' href='../ajax_json_engine.php?cmdx=exit'><i class='".$iconKeluar."'></i>&nbsp;Keluar</a></li>" .
								"</ul>" .
							"</div>" .
						"</div>" .
					"</nav>";
				echo("".$html5_component."");
			} elseif ($this->usermodule == "pendataanplanbcfstaff") {			/* STAFF PERENCANAAN/PENDATAAN */
				$html5_component = "" .
					"<nav class='navbar navbar-inverse navbar-fixed-top'>" .
						"<div class='container-fluid'>" .
							"<div class='navbar-header'>" .
								"<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>" .
									"<span class='sr-only'>Toggle navigation</span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
								"</button>" .
								"<a class='navbar-brand' href='./'>".$this->appx_short_name."</a>" .
							"</div>" .
							"<div id='navbar' class='navbar-collapse collapse'>" .
								"<!-- LEFT Menus -->" .
								"<ul class='nav navbar-nav navbar-left'>" .
									"<li class='top-menu active'><a id='home' href='#' class='ajax-menu'><i class='".$iconHome."'></i>&nbsp;Home</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|data-staff-pendataan' href='#' class='ajax-menu'><i class='".$iconPelayanan."'></i>&nbsp;Data Perencanaan</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|report-staff-pendataan' href='#' class='ajax-menu'><i class='".$iconLaporan."'></i>&nbsp;Laporan</a></li>" .
									$this->generate_additional_menu() .
								"</ul>" .
								"<!-- /LEFT Menus -->" .
								"<!-- RIGHT Menus -->" .
								"<ul class='nav navbar-nav navbar-right'>" .
									"<li class='top-menu'><a id='left-sidebar-app|settings' href='#' class='ajax-menu'><i class='".$iconPengaturan."'></i>&nbsp;Pengaturan</a></li>" .
									"<li class='top-menu'><a id='exit' href='../ajax_json_engine.php?cmdx=exit'><i class='".$iconKeluar."'></i>&nbsp;Keluar</a></li>" .
								"</ul>" .
							"</div>" .
						"</div>" .
					"</nav>";
				echo("".$html5_component."");
			} elseif ($this->usermodule == "pengelolaanspv") {						/* KABID PENGELOLAAN */
				$html5_component = "" .
					"<nav class='navbar navbar-inverse navbar-fixed-top'>" .
						"<div class='container-fluid'>" .
							"<div class='navbar-header'>" .
								"<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>" .
									"<span class='sr-only'>Toggle navigation</span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
								"</button>" .
								"<a class='navbar-brand' href='./'>".$this->appx_short_name."</a>" .
							"</div>" .
							"<div id='navbar' class='navbar-collapse collapse'>" .
								"<!-- LEFT Menus -->" .
								"<ul class='nav navbar-nav navbar-left'>" .
									"<li class='top-menu active'><a id='home' href='#' class='ajax-menu'><i class='".$iconHome."'></i>&nbsp;Home</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|data-supervisor-pengelolaan' href='#' class='ajax-menu'><i class='".$iconPelayanan."'></i>&nbsp;Data Bidang II</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|report-supervisor-pengelolaan' href='#' class='ajax-menu'><i class='".$iconLaporan."'></i>&nbsp;Laporan</a></li>" .
									$this->generate_additional_menu() .
								"</ul>" .
								"<!-- /LEFT Menus -->" .
								"<!-- RIGHT Menus -->" .
								"<ul class='nav navbar-nav navbar-right'>" .
									"<li class='top-menu'><a id='left-sidebar-app|settings' href='#' class='ajax-menu'><i class='".$iconPengaturan."'></i>&nbsp;Pengaturan</a></li>" .
									"<li class='top-menu'><a id='exit' href='../ajax_json_engine.php?cmdx=exit'><i class='".$iconKeluar."'></i>&nbsp;Keluar</a></li>" .
								"</ul>" .
							"</div>" .
						"</div>" .
					"</nav>";
				echo("".$html5_component."");
			} elseif ($this->usermodule == "pengelolaantapcrd") {					/* KASI PENETAPAN */
				$html5_component = "" .
					"<nav class='navbar navbar-inverse navbar-fixed-top'>" .
						"<div class='container-fluid'>" .
							"<div class='navbar-header'>" .
								"<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>" .
									"<span class='sr-only'>Toggle navigation</span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
								"</button>" .
								"<a class='navbar-brand' href='./'>".$this->appx_short_name."</a>" .
							"</div>" .
							"<div id='navbar' class='navbar-collapse collapse'>" .
								"<!-- LEFT Menus -->" .
								"<ul class='nav navbar-nav navbar-left'>" .
									"<li class='top-menu active'><a id='home' href='#' class='ajax-menu'><i class='".$iconHome."'></i>&nbsp;Home</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|data-coordinator-penetapan' href='#' class='ajax-menu'><i class='".$iconPelayanan."'></i>&nbsp;Data Penetapan</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|report-coordinator-penetapan' href='#' class='ajax-menu'><i class='".$iconLaporan."'></i>&nbsp;Laporan</a></li>" .
									$this->generate_additional_menu() .
								"</ul>" .
								"<!-- /LEFT Menus -->" .
								"<!-- RIGHT Menus -->" .
								"<ul class='nav navbar-nav navbar-right'>" .
									"<li class='top-menu'><a id='left-sidebar-app|settings' href='#' class='ajax-menu'><i class='".$iconPengaturan."'></i>&nbsp;Pengaturan</a></li>" .
									"<li class='top-menu'><a id='exit' href='../ajax_json_engine.php?cmdx=exit'><i class='".$iconKeluar."'></i>&nbsp;Keluar</a></li>" .
								"</ul>" .
							"</div>" .
						"</div>" .
					"</nav>";
				echo("".$html5_component."");
			} elseif ($this->usermodule == "pengelolaantapstaff") {				/* STAFF PENETAPAN */
				$html5_component = "" .
					"<nav class='navbar navbar-inverse navbar-fixed-top'>" .
						"<div class='container-fluid'>" .
							"<div class='navbar-header'>" .
								"<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>" .
									"<span class='sr-only'>Toggle navigation</span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
								"</button>" .
								"<a class='navbar-brand' href='./'>".$this->appx_short_name."</a>" .
							"</div>" .
							"<div id='navbar' class='navbar-collapse collapse'>" .
								"<!-- LEFT Menus -->" .
								"<ul class='nav navbar-nav navbar-left'>" .
									"<li class='top-menu active'><a id='home' href='#' class='ajax-menu'><i class='".$iconHome."'></i>&nbsp;Home</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|data-staff-penetapan' href='#' class='ajax-menu'><i class='".$iconPelayanan."'></i>&nbsp;Data</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|report-staff-penetapan' href='#' class='ajax-menu'><i class='".$iconLaporan."'></i>&nbsp;Laporan</a></li>" .
									$this->generate_additional_menu() .
								"</ul>" .
								"<!-- /LEFT Menus -->" .
								"<!-- RIGHT Menus -->" .
								"<ul class='nav navbar-nav navbar-right'>" .
									"<li class='top-menu'><a id='left-sidebar-app|settings' href='#' class='ajax-menu'><i class='".$iconPengaturan."'></i>&nbsp;Pengaturan</a></li>" .
									"<li class='top-menu'><a id='exit' href='../ajax_json_engine.php?cmdx=exit'><i class='".$iconKeluar."'></i>&nbsp;Keluar</a></li>" .
								"</ul>" .
							"</div>" .
						"</div>" .
					"</nav>";
				echo("".$html5_component."");
			} elseif ($this->usermodule == "pengelolaandatacrd") {				/* KASI PENGELOLAAN */
				$html5_component = "" .
					"<nav class='navbar navbar-inverse navbar-fixed-top'>" .
						"<div class='container-fluid'>" .
							"<div class='navbar-header'>" .
								"<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>" .
									"<span class='sr-only'>Toggle navigation</span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
								"</button>" .
								"<a class='navbar-brand' href='./'>".$this->appx_short_name."</a>" .
							"</div>" .
							"<div id='navbar' class='navbar-collapse collapse'>" .
								"<!-- LEFT Menus -->" .
								"<ul class='nav navbar-nav navbar-left'>" .
									"<li class='top-menu active'><a id='home' href='#' class='ajax-menu'><i class='".$iconHome."'></i>&nbsp;Home</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|data-coordinator-pengelolaan' href='#' class='ajax-menu'><i class='".$iconPelayanan."'></i>&nbsp;Data Pengelolaan</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|report-coordinator-pengelolaan' href='#' class='ajax-menu'><i class='".$iconLaporan."'></i>&nbsp;Laporan</a></li>" .
									$this->generate_additional_menu() .
								"</ul>" .
								"<!-- /LEFT Menus -->" .
								"<!-- RIGHT Menus -->" .
								"<ul class='nav navbar-nav navbar-right'>" .
									"<li class='top-menu'><a id='left-sidebar-app|settings' href='#' class='ajax-menu'><i class='".$iconPengaturan."'></i>&nbsp;Pengaturan</a></li>" .
									"<li class='top-menu'><a id='exit' href='../ajax_json_engine.php?cmdx=exit'><i class='".$iconKeluar."'></i>&nbsp;Keluar</a></li>" .
								"</ul>" .
							"</div>" .
						"</div>" .
					"</nav>";
				echo("".$html5_component."");
			} elseif ($this->usermodule == "pengelolaandatastaff") {			/* STAFF PENGELOLAAN */
				$html5_component = "" .
					"<nav class='navbar navbar-inverse navbar-fixed-top'>" .
						"<div class='container-fluid'>" .
							"<div class='navbar-header'>" .
								"<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>" .
									"<span class='sr-only'>Toggle navigation</span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
								"</button>" .
								"<a class='navbar-brand' href='./'>".$this->appx_short_name."</a>" .
							"</div>" .
							"<div id='navbar' class='navbar-collapse collapse'>" .
								"<!-- LEFT Menus -->" .
								"<ul class='nav navbar-nav navbar-left'>" .
									"<li class='top-menu active'><a id='home' href='#' class='ajax-menu'><i class='".$iconHome."'></i>&nbsp;Home</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|data-staff-pengelolaan' href='#' class='ajax-menu'><i class='".$iconPelayanan."'></i>&nbsp;Data</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|report-staff-pengelolaan' href='#' class='ajax-menu'><i class='".$iconLaporan."'></i>&nbsp;Laporan</a></li>" .
									$this->generate_additional_menu() .
								"</ul>" .
								"<!-- /LEFT Menus -->" .
								"<!-- RIGHT Menus -->" .
								"<ul class='nav navbar-nav navbar-right'>" .
									"<li class='top-menu'><a id='left-sidebar-app|settings' href='#' class='ajax-menu'><i class='".$iconPengaturan."'></i>&nbsp;Pengaturan</a></li>" .
									"<li class='top-menu'><a id='exit' href='../ajax_json_engine.php?cmdx=exit'><i class='".$iconKeluar."'></i>&nbsp;Keluar</a></li>" .
								"</ul>" .
							"</div>" .
						"</div>" .
					"</nav>";
				echo("".$html5_component."");
			} elseif ($this->usermodule == "pengendalianspv") {						/* KABID PENGENDALIAN */
				$html5_component = "" .
					"<nav class='navbar navbar-inverse navbar-fixed-top'>" .
						"<div class='container-fluid'>" .
							"<div class='navbar-header'>" .
								"<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>" .
									"<span class='sr-only'>Toggle navigation</span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
								"</button>" .
								"<a class='navbar-brand' href='./'>".$this->appx_short_name."</a>" .
							"</div>" .
							"<div id='navbar' class='navbar-collapse collapse'>" .
								"<!-- LEFT Menus -->" .
								"<ul class='nav navbar-nav navbar-left'>" .
									"<li class='top-menu active'><a id='home' href='#' class='ajax-menu'><i class='".$iconHome."'></i>&nbsp;Home</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|data-supervisor-pengendalian' href='#' class='ajax-menu'><i class='".$iconPelayanan."'></i>&nbsp;Data Bidang III</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|report-supervisor-pengendalian' href='#' class='ajax-menu'><i class='".$iconLaporan."'></i>&nbsp;Laporan</a></li>" .
									$this->generate_additional_menu() .
								"</ul>" .
								"<!-- /LEFT Menus -->" .
								"<!-- RIGHT Menus -->" .
								"<ul class='nav navbar-nav navbar-right'>" .
									"<li class='top-menu'><a id='left-sidebar-app|settings' href='#' class='ajax-menu'><i class='".$iconPengaturan."'></i>&nbsp;Pengaturan</a></li>" .
									"<li class='top-menu'><a id='exit' href='../ajax_json_engine.php?cmdx=exit'><i class='".$iconKeluar."'></i>&nbsp;Keluar</a></li>" .
								"</ul>" .
							"</div>" .
						"</div>" .
					"</nav>";
				echo("".$html5_component."");
			} elseif ($this->usermodule == "pengendalianctrlcrd") {				/* KASI PENGENDALIAN */
				$html5_component = "" .
					"<nav class='navbar navbar-inverse navbar-fixed-top'>" .
						"<div class='container-fluid'>" .
							"<div class='navbar-header'>" .
								"<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>" .
									"<span class='sr-only'>Toggle navigation</span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
								"</button>" .
								"<a class='navbar-brand' href='./'>".$this->appx_short_name."</a>" .
							"</div>" .
							"<div id='navbar' class='navbar-collapse collapse'>" .
								"<!-- LEFT Menus -->" .
								"<ul class='nav navbar-nav navbar-left'>" .
									"<li class='top-menu active'><a id='home' href='#' class='ajax-menu'><i class='".$iconHome."'></i>&nbsp;Home</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|data-coordinator-pengendalian' href='#' class='ajax-menu'><i class='".$iconPelayanan."'></i>&nbsp;Data Pengendalian</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|report-coordinator-pengendalian' href='#' class='ajax-menu'><i class='".$iconLaporan."'></i>&nbsp;Laporan</a></li>" .
									$this->generate_additional_menu() .
								"</ul>" .
								"<!-- /LEFT Menus -->" .
								"<!-- RIGHT Menus -->" .
								"<ul class='nav navbar-nav navbar-right'>" .
									"<li class='top-menu'><a id='left-sidebar-app|settings' href='#' class='ajax-menu'><i class='".$iconPengaturan."'></i>&nbsp;Pengaturan</a></li>" .
									"<li class='top-menu'><a id='exit' href='../ajax_json_engine.php?cmdx=exit'><i class='".$iconKeluar."'></i>&nbsp;Keluar</a></li>" .
								"</ul>" .
							"</div>" .
						"</div>" .
					"</nav>";
				echo("".$html5_component."");
			} elseif ($this->usermodule == "pengendalianctrlstaff") {			/* STAFF PENGENDALIAN */
				$html5_component = "" .
					"<nav class='navbar navbar-inverse navbar-fixed-top'>" .
						"<div class='container-fluid'>" .
							"<div class='navbar-header'>" .
								"<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>" .
									"<span class='sr-only'>Toggle navigation</span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
								"</button>" .
								"<a class='navbar-brand' href='./'>".$this->appx_short_name."</a>" .
							"</div>" .
							"<div id='navbar' class='navbar-collapse collapse'>" .
								"<!-- LEFT Menus -->" .
								"<ul class='nav navbar-nav navbar-left'>" .
									"<li class='top-menu active'><a id='home' href='#' class='ajax-menu'><i class='".$iconHome."'></i>&nbsp;Home</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|data-staff-pengendalian' href='#' class='ajax-menu'><i class='".$iconPelayanan."'></i>&nbsp;Data</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|report-staff-pengendalian' href='#' class='ajax-menu'><i class='".$iconLaporan."'></i>&nbsp;Laporan</a></li>" .
									$this->generate_additional_menu() .
								"</ul>" .
								"<!-- /LEFT Menus -->" .
								"<!-- RIGHT Menus -->" .
								"<ul class='nav navbar-nav navbar-right'>" .
									"<li class='top-menu'><a id='left-sidebar-app|settings' href='#' class='ajax-menu'><i class='".$iconPengaturan."'></i>&nbsp;Pengaturan</a></li>" .
									"<li class='top-menu'><a id='exit' href='../ajax_json_engine.php?cmdx=exit'><i class='".$iconKeluar."'></i>&nbsp;Keluar</a></li>" .
								"</ul>" .
							"</div>" .
						"</div>" .
					"</nav>";
				echo("".$html5_component."");
			} elseif ($this->usermodule == "pengendaliancollcrd") {				/* KASI PENAGIHAN */
				$html5_component = "" .
					"<nav class='navbar navbar-inverse navbar-fixed-top'>" .
						"<div class='container-fluid'>" .
							"<div class='navbar-header'>" .
								"<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>" .
									"<span class='sr-only'>Toggle navigation</span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
								"</button>" .
								"<a class='navbar-brand' href='./'>".$this->appx_short_name."</a>" .
							"</div>" .
							"<div id='navbar' class='navbar-collapse collapse'>" .
								"<!-- LEFT Menus -->" .
								"<ul class='nav navbar-nav navbar-left'>" .
									"<li class='top-menu active'><a id='home' href='#' class='ajax-menu'><i class='".$iconHome."'></i>&nbsp;Home</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|data-coordinator-penagihan' href='#' class='ajax-menu'><i class='".$iconPelayanan."'></i>&nbsp;Data Penagihan</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|report-coordinator-penagihan' href='#' class='ajax-menu'><i class='".$iconLaporan."'></i>&nbsp;Laporan</a></li>" .
									$this->generate_additional_menu() .
								"</ul>" .
								"<!-- /LEFT Menus -->" .
								"<!-- RIGHT Menus -->" .
								"<ul class='nav navbar-nav navbar-right'>" .
									"<li class='top-menu'><a id='left-sidebar-app|settings' href='#' class='ajax-menu'><i class='".$iconPengaturan."'></i>&nbsp;Pengaturan</a></li>" .
									"<li class='top-menu'><a id='exit' href='../ajax_json_engine.php?cmdx=exit'><i class='".$iconKeluar."'></i>&nbsp;Keluar</a></li>" .
								"</ul>" .
							"</div>" .
						"</div>" .
					"</nav>";
				echo("".$html5_component."");
			} elseif ($this->usermodule == "pengendaliancollstaff") {			/* STAFF PENAGIHAN */
				$html5_component = "" .
					"<nav class='navbar navbar-inverse navbar-fixed-top'>" .
						"<div class='container-fluid'>" .
							"<div class='navbar-header'>" .
								"<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>" .
									"<span class='sr-only'>Toggle navigation</span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
								"</button>" .
								"<a class='navbar-brand' href='./'>".$this->appx_short_name."</a>" .
							"</div>" .
							"<div id='navbar' class='navbar-collapse collapse'>" .
								"<!-- LEFT Menus -->" .
								"<ul class='nav navbar-nav navbar-left'>" .
									"<li class='top-menu active'><a id='home' href='#' class='ajax-menu'><i class='".$iconHome."'></i>&nbsp;Home</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|data-staff-penagihan' href='#' class='ajax-menu'><i class='".$iconPelayanan."'></i>&nbsp;Data</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|report-staff-penagihan' href='#' class='ajax-menu'><i class='".$iconLaporan."'></i>&nbsp;Laporan</a></li>" .
									$this->generate_additional_menu() .
								"</ul>" .
								"<!-- /LEFT Menus -->" .
								"<!-- RIGHT Menus -->" .
								"<ul class='nav navbar-nav navbar-right'>" .
									"<li class='top-menu'><a id='left-sidebar-app|settings' href='#' class='ajax-menu'><i class='".$iconPengaturan."'></i>&nbsp;Pengaturan</a></li>" .
									"<li class='top-menu'><a id='exit' href='../ajax_json_engine.php?cmdx=exit'><i class='".$iconKeluar."'></i>&nbsp;Keluar</a></li>" .
								"</ul>" .
							"</div>" .
						"</div>" .
					"</nav>";
				echo("".$html5_component."");
			} elseif ($this->usermodule == "development") {								/* DEVELOPMENT - SUPERUSER */
				$html5_component = "" .
					"<nav class='navbar navbar-inverse navbar-fixed-top'>" .
						"<div class='container-fluid'>" .
							"<div class='navbar-header'>" .
								"<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>" .
									"<span class='sr-only'>Toggle navigation</span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
									"<span class='icon-bar'></span>" .
								"</button>" .
								"<a class='navbar-brand' href='./'>".$this->appx_short_name."</a>" .
							"</div>" .
							"<div id='navbar' class='navbar-collapse collapse'>" .
								"<!-- LEFT Menus -->" .
								"<ul class='nav navbar-nav navbar-left'>" .
									"<li class='top-menu active'><a id='home' href='#' class='ajax-menu'><i class='".$iconHome."'></i>&nbsp;Home</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|data-supervisor-pendataanplansvc' href='#' class='ajax-menu'><i class='".$iconPelayanan."'></i>&nbsp;Bidang I</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|data-supervisor-pengelolaan' href='#' class='ajax-menu'><i class='".$iconPelayanan."'></i>&nbsp;Bidang II</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|data-supervisor-pengendalian' href='#' class='ajax-menu'><i class='".$iconPelayanan."'></i>&nbsp;Bidang III</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|report-development' href='#' class='ajax-menu'><i class='".$iconLaporan."'></i>&nbsp;Laporan</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|bendahara' href='#' class='ajax-menu'><i class='fa fa-money'></i>&nbsp;Bendahara</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|sigbppkad' href='#' class='ajax-menu'><i class='fa fa-map'></i>&nbsp;SIG BPPKAD</a></li><li class='top-menu'><a id='left-sidebar-app|sigmetadata' href='#' class='ajax-menu'><i class='fa fa-file'></i>&nbsp;Metadata Spasial</a></li>" .
									"<li class='top-menu'><a id='left-sidebar-app|sismioptoolsspv' href='#' class='ajax-menu'><i class='fa fa-github-alt'></i>&nbsp;SISMIOPTools</a></li>" .
								"</ul>" .
								"<!-- /LEFT Menus -->" .
								"<!-- RIGHT Menus -->" .
								"<ul class='nav navbar-nav navbar-right'>" .
									"<li class='top-menu'><a id='left-sidebar-app|settings' href='#' class='ajax-menu'><i class='".$iconPengaturan."'></i>&nbsp;Pengaturan</a></li>" .
									"<li class='top-menu'><a id='exit' href='../ajax_json_engine.php?cmdx=exit'><i class='".$iconKeluar."'></i>&nbsp;Keluar</a></li>" .
								"</ul>" .
							"</div>" .
						"</div>" .
					"</nav>";
				echo("".$html5_component."");
			} else {
				/* ------------------------------------------------------ */
				
				/* ------------------------------------------------------ */
			}
		} else {
			/* ------------------------------------------------------ */
			
			/* ------------------------------------------------------ */
		}
	}
	/*
	 *
	 */
	public function generate_additional_menu(){
		$arrAddMenu = null; $htmlstring = ""; $inArrayCount = null;
		if ($this->useraddmodule == "UNDEFINED") {
			return $htmlstring;
		} else {
			if (ctype_alnum($this->useraddmodule) == false) {
				$arrAddMenu = explode("|",$this->useraddmodule);
				$inArrayCount = count($arrAddMenu);
				for ($i=0;$i<$inArrayCount;$i++) {
					$stringToProcess = $arrAddMenu[$i];
					if ($stringToProcess == "addmodbendahara") {
						$htmlstring .= "<li class='top-menu'><a id='left-sidebar-app|bendahara' href='#' class='ajax-menu'><i class='fa fa-money'></i>&nbsp;Bendahara</a></li>";
					} elseif ($stringToProcess == "addmodpembayaran") {
						$htmlstring .= "<li class='top-menu'><a id='left-sidebar-app|pembayaran' href='#' class='ajax-menu'><i class='fa fa-money'></i>&nbsp;Pembayaran</a></li>";
					} elseif ($stringToProcess == "addmodsigbppkad") {
						$htmlstring .= "<li class='top-menu'><a id='left-sidebar-app|sigbppkad' href='#' class='ajax-menu'><i class='fa fa-map'></i>&nbsp;SIG BPPKAD</a></li><li class='top-menu'><a id='left-sidebar-app|sigmetadata' href='#' class='ajax-menu'><i class='fa fa-file'></i>&nbsp;Metadata Spasial</a></li>";
					} elseif ($stringToProcess == "addmodsismioptoolspv") {
						$htmlstring .= "<li class='top-menu'><a id='left-sidebar-app|sismioptoolsspv' href='#' class='ajax-menu'><i class='fa fa-github-alt'></i>&nbsp;SISMIOPTools</a></li>";
					} elseif ($stringToProcess == "addmodsismioptoolstaff") {
						$htmlstring .= "<li class='top-menu'><a id='left-sidebar-app|sismioptoolsstaff' href='#' class='ajax-menu'><i class='fa fa-github-alt'></i>&nbsp;SISMIOPTools</a></li>";
					} elseif ($stringToProcess == "addmodverifikasibphtb") {
						$htmlstring .= "<li class='top-menu'><a id='left-sidebar-app|verifikatorbphtb' href='#' class='ajax-menu'><i class='fa fa-sitemap'></i>&nbsp;Verifikasi BPHTB</a></li>";
					} elseif ($stringToProcess == "addmodcollector") {
						$htmlstring .= "<li class='top-menu'><a id='left-sidebar-app|kolektor' href='#' class='ajax-menu'><i class='fa fa-paper-plane'></i>&nbsp;Pemungut Lapangan</a></li>";
					} else {
						$htmlstring .= "";
					}
				}
			} else {
				if ($this->useraddmodule == "addmodbendahara") {
					$htmlstring = "<li class='top-menu'><a id='left-sidebar-app|bendahara' href='#' class='ajax-menu'><i class='fa fa-money'></i>&nbsp;Bendahara</a></li>";
				} elseif ($this->useraddmodule == "addmodpembayaran") {
					$htmlstring = "<li class='top-menu'><a id='left-sidebar-app|pembayaran' href='#' class='ajax-menu'><i class='fa fa-money'></i>&nbsp;Pembayaran</a></li>";
				} elseif ($this->useraddmodule == "addmodsigbppkad") {
					$htmlstring = "<li class='top-menu'><a id='left-sidebar-app|sigbppkad' href='#' class='ajax-menu'><i class='fa fa-map'></i>&nbsp;SIG BPPKAD</a></li><li class='top-menu'><a id='left-sidebar-app|sigmetadata' href='#' class='ajax-menu'><i class='fa fa-file'></i>&nbsp;Metadata Spasial</a></li>";
				} elseif ($this->useraddmodule == "addmodsismioptoolspv") {
					$htmlstring = "<li class='top-menu'><a id='left-sidebar-app|sismioptoolsspv' href='#' class='ajax-menu'><i class='fa fa-github-alt'></i>&nbsp;SISMIOPTools</a></li>";
				} elseif ($this->useraddmodule == "addmodsismioptoolstaff") {
					$htmlstring = "<li class='top-menu'><a id='left-sidebar-app|sismioptoolsstaff' href='#' class='ajax-menu'><i class='fa fa-github-alt'></i>&nbsp;SISMIOPTools</a></li>";
				} elseif ($this->useraddmodule == "addmodverifikasibphtb") {
					$htmlstring = "<li class='top-menu'><a id='left-sidebar-app|verifikatorbphtb' href='#' class='ajax-menu'><i class='fa fa-sitemap'></i>&nbsp;Verifikasi BPHTB</a></li>";
				} elseif ($this->useraddmodule == "addmodcollector") {
					$htmlstring = "<li class='top-menu'><a id='left-sidebar-app|kolektor' href='#' class='ajax-menu'><i class='fa fa-paper-plane'></i>&nbsp;Pemungut Lapangan</a></li>";
				} else {
					$htmlstring = "";
				}
			}
			return $htmlstring;
		}
	}
	/*
	 *
	 */
	public function generate_empty_topnav(){
		/* undefined module, generate only home menu and exit */
		$iconHome = "fa fa-home";
		$iconKeluar = "fa fa-power-off";
		$html5_component = "" .
			"<nav class='navbar navbar-inverse navbar-fixed-top'>" .
				"<div class='container-fluid'>" .
					"<div class='navbar-header'>" .
						"<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>" .
							"<span class='sr-only'>Toggle navigation</span>" .
							"<span class='icon-bar'></span>" .
							"<span class='icon-bar'></span>" .
							"<span class='icon-bar'></span>" .
						"</button>" .
						"<a class='navbar-brand' href='./'>MAPATDA DPPKAD</a>" .
					"</div>" .
					"<div id='navbar' class='navbar-collapse collapse'>" .
						"<!-- LEFT Menus -->" .
						"<ul class='nav navbar-nav navbar-left'>" .
							"<li class='top-menu active'><a id='home' href='#' class='ajax-menu'><i class='".$iconHome."'></i> Home</a></li>" .
						"</ul>" .
						"<!-- /LEFT Menus -->" .
						"<!-- RIGHT Menus -->" .
						"<ul class='nav navbar-nav navbar-right'>" .
							"<li class='top-menu'><a id='exit' href='./ajax_json_engine.php?cmdx=exit'><i class='".$iconKeluar."'></i> Keluar</a></li>" .
						"</ul>" .
					"</div>" .
				"</div>" .
			"</nav>";
		echo("".$html5_component."");
	}
	/*
	 * Function days_diff.
	 */
	public function days_diff($d1, $d2) {
	    $x1 = days($d1);
	    $x2 = days($d2);

	    if ($x1 && $x2) {
	        return abs($x1 - $x2);
	    }
	}
	/*
	 * Function support for days_diff().
	 */
	public function days($x) {
    if (get_class($x) != 'DateTime') {
        return false;
    }
    $y = $x->format('Y') - 1;
    $days = $y * 365;
    $z = (int)($y / 4);
    $days += $z;
    $z = (int)($y / 100);
    $days -= $z;
    $z = (int)($y / 400);
    $days += $z;
    $days += $x->format('z');
    return $days;
	}
	/*
	 *
	 */
	public function extract_date($mode = "to_html", $input){
		$this->stringToProcess = $input;
		if ($mode == "to_html") {
			$this->emptyArray = explode("-", $this->stringToProcess);
			return "".$this->emptyArray[2]."/".$this->emptyArray[1]."/".$this->emptyArray[0]."";
		} elseif ($mode == "to_print_id") {
			/* assumed input = db format (yyyy-mm-dd) */
			$this->emptyArray = explode("-", $this->stringToProcess);
			/* stupid - develop later!!! */
			switch ($this->emptyArray[1]){
				case "01":
					return "".$this->emptyArray[2]." Januari ".$this->emptyArray[0]."";
					break;
				case "02":
					return "".$this->emptyArray[2]." Pebruari ".$this->emptyArray[0]."";
					break;
				case "03":
					return "".$this->emptyArray[2]." Maret ".$this->emptyArray[0]."";
					break;
				case "04":
					return "".$this->emptyArray[2]." April ".$this->emptyArray[0]."";
					break;
				case "05":
					return "".$this->emptyArray[2]." Mei ".$this->emptyArray[0]."";
					break;
				case "06":
					return "".$this->emptyArray[2]." Juni ".$this->emptyArray[0]."";
					break;
				case "07":
					return "".$this->emptyArray[2]." Juli ".$this->emptyArray[0]."";
					break;
				case "08":
					return "".$this->emptyArray[2]." Agustus ".$this->emptyArray[0]."";
					break;
				case "09":
					return "".$this->emptyArray[2]." September ".$this->emptyArray[0]."";
					break;
				case "10":
					return "".$this->emptyArray[2]." Oktober ".$this->emptyArray[0]."";
					break;
				case "11":
					return "".$this->emptyArray[2]." Nopember ".$this->emptyArray[0]."";
					break;
				case "12":
					return "".$this->emptyArray[2]." Desember ".$this->emptyArray[0]."";
					break;
				default:
					return "".$this->emptyArray[2]."/".$this->emptyArray[1]."/".$this->emptyArray[0]."";
			}
		} elseif ($mode == "to_db") {
			$this->emptyArray = explode("/", $this->stringToProcess);
			return "".$this->emptyArray[2]."-".$this->emptyArray[1]."-".$this->emptyArray[0]."";
		} else {
			/* assumed input = db format (yyyy-mm-dd) */
			$this->emptyArray = explode("-", $this->stringToProcess);
			return "".$this->emptyArray[2]."/".$this->emptyArray[1]."/".$this->emptyArray[0]."";
		}
	}
	public function datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
    /*
			* $interval can be:
			* yyyy - Number of full years
			* q - Number of full quarters
			* m - Number of full months
			* y - Difference between day numbers
			*   (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
			* d - Number of full days
			* w - Number of full weekdays
			* ww - Number of full weeks
			* h - Number of full hours
			* n - Number of full minutes
			* s - Number of full seconds (default)
		 * EXAMPLE:
		 * Untuk mencari x minggu y hari...
		 * $days = datediff('d', $start_date, $end_date, false);
		 * $base_minggu = datediff('ww', $start_date, $end_date, false);
		 * $days_remain = $days - ($base_minggu * 7);
    */

    if (!$using_timestamps) {
        $datefrom = strtotime($datefrom, 0);
        $dateto = strtotime($dateto, 0);
    }
    $difference = $dateto - $datefrom; // Difference in seconds

    switch($interval) {

    case 'yyyy': // Number of full years
        $years_difference = floor($difference / 31536000);
        if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
            $years_difference--;
        }
        if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
            $years_difference++;
        }
        $datediff = $years_difference;
        break;
    case "q": // Number of full quarters
        $quarters_difference = floor($difference / 8035200);
        while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
            $months_difference++;
        }
        $quarters_difference--;
        $datediff = $quarters_difference;
        break;
    case "m": // Number of full months
        $months_difference = floor($difference / 2678400);
        while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
            $months_difference++;
        }
        $months_difference--;
        $datediff = $months_difference;
        break;
    case 'y': // Difference between day numbers
        $datediff = date("z", $dateto) - date("z", $datefrom);
        break;
    case "d": // Number of full days
        $datediff = floor($difference / 86400);
        break;
    case "w": // Number of full weekdays
        $days_difference = floor($difference / 86400);
        $weeks_difference = floor($days_difference / 7); // Complete weeks
        $first_day = date("w", $datefrom);
        $days_remainder = floor($days_difference % 7);
        $odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
        if ($odd_days > 7) { // Sunday
            $days_remainder--;
        }
        if ($odd_days > 6) { // Saturday
            $days_remainder--;
        }
        $datediff = ($weeks_difference * 5) + $days_remainder;
        break;
    case "ww": // Number of full weeks
        $datediff = floor($difference / 604800);
        break;
    case "h": // Number of full hours
        $datediff = floor($difference / 3600);
        break;
    case "n": // Number of full minutes
        $datediff = floor($difference / 60);
        break;
    default: // Number of full seconds (default)
        $datediff = $difference;
        break;
    }
    return $datediff;
	}
	/* DATE ADD +1 MONTH */
	/*
	 * Example:
	 * $startDate = '2014-06-03'; // select date in Y-m-d format
	 * $nMonths = 1; // choose how many months you want to move ahead
	 * $final = endCycle($startDate, $nMonths) // output: 2014-07-02
	 */
	public function endCycle($d1, $months){
	  $date = new DateTime($d1);
	  // call second function to add the months
	  $newDate = $date->add($this->add_months($months, $date));
	  // goes back 1 day from date, remove if you want same day of month
	  $newDate->sub(new DateInterval('P1D'));
	  //formats final date to Y-m-d form
	  $dateReturned = $newDate->format('Y-m-d');
	  return $dateReturned;
	}
	private function add_months($months, DateTime $dateObject){
	  $next = new DateTime($dateObject->format('Y-m-d'));
	  $next->modify('last day of +'.$months.' month');

	  if($dateObject->format('d') > $next->format('d')) {
	      return $dateObject->diff($next);
	  } else {
	      return new DateInterval('P'.$months.'M');
	  }
	}
	public function Terbilang($x)
	{
	  $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	  if ($x < 12)
	    return " " . $abil[$x];
	  elseif ($x < 20)
	    return Terbilang($x - 10) . " belas";
	  elseif ($x < 100)
	    return Terbilang($x / 10) . " puluh" . Terbilang($x % 10);
	  elseif ($x < 200)
	    return " seratus" . Terbilang($x - 100);
	  elseif ($x < 1000)
	    return Terbilang($x / 100) . " ratus" . Terbilang($x % 100);
	  elseif ($x < 2000)
	    return " seribu" . Terbilang($x - 1000);
	  elseif ($x < 1000000)
	    return Terbilang($x / 1000) . " ribu" . Terbilang($x % 1000);
	  elseif ($x < 1000000000)
	    return Terbilang($x / 1000000) . " juta" . Terbilang($x % 1000000);
	}
	public function bulan($x)
	{
	  if (intval($x) == 1 || $x == "01")
	    return "JANUARI";
	  elseif (intval($x) == 2 || $x == "02")
	    return "FEBRUARI";
	  elseif (intval($x) == 3 || $x == "03")
	    return "MARET";
	  elseif (intval($x) == 4 || $x == "04")
	    return "APRIL";
	  elseif (intval($x) == 5 || $x == "05")
	    return "MEI";
	  elseif (intval($x) == 6 || $x == "06")
	    return "JUNI";
	  elseif (intval($x) == 7 || $x == "07")
	    return "JULI";
	  elseif (intval($x) == 8 || $x == "08")
	    return "AGUSTUS";
	  elseif (intval($x) == 9 || $x == "09")
	    return "SEPTEMBER";
	  elseif (intval($x) == 10 || $x == "10")
	    return "OKTOBER";
		elseif (intval($x) == 11 || $x == "11")
	    return "NOVEMBER";
		elseif (intval($x) == 12 || $x == "12")
	    return "DESEMBER";
	}
}
?>