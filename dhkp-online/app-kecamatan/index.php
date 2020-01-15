<?php
/* ERROR DEBUG HANDLE */
error_reporting(E_ALL);
ini_set("display_errors", 1);
/* SESSION START */
session_start();
$txsessionid = session_id();
/* CONSTANTS & VARIABLES */
require("../variablen.php");
/* CONFIGURATION */
require("../einstellung.php");
/* LIBRARIES */
require("../funktion.php");
if (isset($_SESSION['kecamatanXweb'])) { ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../trulogix-cdn/favicons/kabupaten-kulonprogo/favicon.ico">
    <title><?php echo($appxinfo['_appx_short_name_']); ?> - <?php echo($appxinfo['_appx_company_']); ?></title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" rel="stylesheet" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link href='../trulogix-cdn/custom-fonts/font-awesome.min.css' rel='stylesheet'>
    <link href="../trulogix-cdn/bootstrap-plugins/datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="./local-assets/datatables/minimalist-alt/datatables.min.css" rel="stylesheet">
    <link href='./local-assets/custom-css/app.css' rel='stylesheet'>
    <link href='./local-assets/custom-css/custom.css' rel='stylesheet'>
    <link href="../trulogix-cdn/custom-css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="../local-assets/js/pace.min.js"></script>
    <link href='./local-assets/custom-css/pace-loading-small.css' rel='stylesheet'>
  </head>
  <body class='body-for-application'>
  	<nav class='navbar navbar-inverse navbar-fixed-top'>
  		<div class='container-fluid'>
  			<div class='navbar-header'>
  				<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>
  					<span class='sr-only'>Toggle navigation</span>
  					<span class='icon-bar'></span>
  					<span class='icon-bar'></span>
  					<span class='icon-bar'></span>
  				</button>
  				<a class='navbar-brand' href='./'><?php echo($appxinfo['_appx_short_name_']); ?></a>
  			</div>
  			<div id='navbar' class='navbar-collapse collapse'>
  				<ul class='nav navbar-nav navbar-left'>
  					<li class='top-menu active'><a id='home' href='./' class='ajax-menu'><i class='fa fa-home'></i> Home</a></li>
  					<li class='top-menu'><a id='left-sidebar-app|mapatda_kecamatan_data' href='#' class='ajax-menu'><i class='fa fa-file'></i> Data</a></li>
  				</ul>
  				<ul class='nav navbar-nav navbar-right'>
  					<!-- <li class='top-menu'><a id='left-sidebar-app|mapatda_kecamatan_syncdata' href='#' class='ajax-menu'><i class='fa fa-database'></i> Sinkronisasi Data</a></li> -->
  					<li class='top-menu'><a id='left-sidebar-app|mapatda_kecamatan_setting' href='#' class='ajax-menu'><i class='fa fa-cog'></i> Pengaturan</a></li>
  					<li class='top-menu'><a id='exit' href='../ajax_json_engine.php?cmdx=exit' class='ajax-menu'><i class='fa fa-power-off'></i> Keluar</a></li>
  				</ul>
  			</div>
  		</div>
  	</nav>
  	
		<div id='app_container' class='container-fluid'></div>
		
		<div id='loading_container'></div>
		
		<div class='modal fade' id='app_modal' tabindex='-1' role='dialog' aria-labelledby='app_modal_label'>
			<div id='app_modal_size' class='modal-dialog' role='document'>
				<div class='modal-content'>
					<div class='modal-header'>
						<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
						<h4 class='modal-title' id='app_modal_label'></h4>
					</div>
					<div id='app_modal_body' class='modal-body'></div>
					<div id='app_modal_footer' class='modal-footer'>
						<button type='button' class='btn btn-default' data-dismiss='modal'><i class='fa fa-power-off'></i>&nbsp;Tutup / Keluar</button>
					</div>
				</div>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
		<script>window.jQuery || document.write('<script src="./local-assets/js/jquery-1.12.4.min.js">\x3C/script>')</script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="../trulogix-cdn/js/ie10-viewport-bug-workaround.js"></script>
    <script src="../trulogix-cdn/js/numeral.js"></script>
    <script src="../trulogix-cdn/js/crypto_sha1.js"></script>
    <script src="../trulogix-cdn/bootstrap-plugins/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="../trulogix-cdn/bootstrap-plugins/datepicker/locales/bootstrap-datepicker.id.min.js"></script>
    <script src="../trulogix-cdn/datatables/minimalist-alt/datatables.min.js"></script>
    <script src="../trulogix-cdn/highcharts/js/highcharts.js"></script>
    <script src="./local-assets/dom-engine/html5engine.min.js"></script>
    <script>
    	$(document).ready(function(){
    		app_engine._generate_home();
    		app_engine._enable_topmenu();
    	});
    </script>
  </body>
</html>
<?php
} else {
	header('Location: ../');
	exit;
}
?>