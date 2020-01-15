<?php
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();
$txsessionid = session_id();
require("./einstellung.php");
require("./funktion.php");
header("Access-Control-Allow-Origin: *");
if (!isset($_SESSION['desaXweb']) && !isset($_SESSION['kecamatanXweb']) && !isset($_SESSION['camatXweb']) && !isset($_SESSION['dhkpXweb'])) { ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="./trulogix-cdn/favicons/kabupaten-kulonprogo/favicon.ico">
    <title><?php echo("".$appxinfo['_appx_short_name_'].""); ?></title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="./trulogix-cdn/custom-fonts/font-awesome.min.css">
    <link rel="stylesheet" href="./trulogix-cdn/custom-fonts/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="./trulogix-cdn/custom-fonts/roboto.css">
    <link rel="stylesheet" href="./trulogix-cdn/frontpage-theme/style.green.css" id="theme-stylesheet">
		<link rel="stylesheet" href="./trulogix-cdn/datatables/minimalist-alt/datatables.min.css">
    <link rel="stylesheet" href="./trulogix-cdn/custom-css/custom.css">
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
    <script src="./trulogix-cdn/js/pace.min.js"></script>
    <link href='./local-assets/custom-css/pace-loading-small.css' rel='stylesheet'>
  </head>
  <body>
    <!-- navbar-->
    <header class="header">
      <div role="navigation" class="navbar navbar-default">
        <div class="container">
          <div class="navbar-header"><a href="./" class="navbar-brand"><?php echo("".$appxinfo['_appx_short_name_'].""); ?></a>
            <div class="navbar-buttons">
              <button type="button" data-toggle="collapse" data-target=".navbar-collapse" class="navbar-toggle navbar-btn">Menu<i class="fa fa-align-justify"></i></button>
            </div>
          </div>
          <div id="navigation" class="collapse navbar-collapse navbar-right">
            <ul class="nav navbar-nav">
              <li class="front-navmenu active"><a id='home' href="#">Home</a></li>
              <!-- <li class="front-navmenu"><a id='perda' href="#">Perda</a></li> -->
            </ul>
            <a href="#" data-toggle="modal" data-target="#login-desa" class="btn navbar-btn btn-ghost"><i class="fa fa-sign-in"></i>Log in Desa/Kelurahan</a>
            <a href="#" data-toggle="modal" data-target="#login-modal" class="btn navbar-btn btn-ghost"><i class="fa fa-sign-in"></i>Log in Kecamatan</a>
						<a href="#" data-toggle="modal" data-target="#login-wp" class="btn navbar-btn btn-ghost"><i class="fa fa-sign-in"></i>Info SPPT</a>
          </div>
        </div>
      </div>
    </header>
    
    <div id="login-modal" tabindex="-1" role="dialog" aria-labelledby="LoginBPPKAD" aria-hidden="true" class="modal fade">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">×</button>
            <h4 id='LoginBPPKAD' class="modal-title">Login Kecamatan</h4>
          </div>
          <div class="modal-body">
            <form id="loginform" name="loginform" method="GET" action="./ajax_json_engine.php" enctype="application/x-www-form-urlencoded">
              <div class="form-group">
                <input id="username" name="username" type="text" placeholder="username" class="form-control">
              </div>
              <div class="form-group">
                <input id="password" name="password" type="password" placeholder="password" class="form-control">
              </div>
              <p class="text-center">
                <button type="button" id="submituserlogin" class="btn btn-primary"><i class="fa fa-sign-in"></i> Log in</button>
              </p>
            </form>
            <div id='notice'></div>
          </div>
        </div>
      </div>
    </div>
    
    <div id="login-desa" tabindex="-1" role="dialog" aria-labelledby="LoginDesa" aria-hidden="true" class="modal fade">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">×</button>
            <h4 id='LoginDesa' class="modal-title">Login Desa/Kelurahan</h4>
          </div>
          <div class="modal-body">
            <form id="loginDesaform" name="loginDesaform" method="GET" action="./ajax_json_engine.php" enctype="application/x-www-form-urlencoded">
              <div class="form-group">
                <input id="txdesausername" name="txdesausername" type="text" placeholder="username" class="form-control">
              </div>
              <div class="form-group">
                <input id="txdesapassword" name="txdesapassword" type="password" placeholder="password" class="form-control">
              </div>
              <p class="text-center">
                <button type="button" id="submitdesalogin" class="btn btn-primary"><i class="fa fa-sign-in"></i> Log in</button>
              </p>
            </form>
            <div id='desanotice'></div>
          </div>
        </div>
      </div>
    </div>
		
    <div id="login-wp" tabindex="-1" role="dialog" aria-labelledby="LoginWP" aria-hidden="true" class="modal fade">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">×</button>
            <h4 id='LoginWP' class="modal-title">Informasi SPPT</h4>
          </div>
          <div class="modal-body">
            <form id="loginWPform" name="loginWPform" method="GET" action="./ajax_json_engine.php" enctype="application/x-www-form-urlencoded">
              <div class="form-group">
                <input id="txstrnop" name="txstrnop" type="text" placeholder="NOP SPPT" class="form-control">
              </div>
              <p class="text-center">
                <button type="button" id="submitwplogin" class="btn btn-primary"><i class="fa fa-sign-in"></i> Tampilkan Data</button>
              </p>
            </form>
            <div id='wpnotice'></div>
          </div>
        </div>
      </div>
    </div>

    <div class="jumbotron main-jumbotron">
      <div class="container">
        <div class="content">
          <h1><?php echo("".$appxinfo['_appx_short_name_'].""); ?></h1>
          <p class="margin-bottom">Badan Keuangan dan Aset Daerah <a href="http://bkad.kulonprogokab.go.id/">Kabupaten Kulon Progo</a></p>
          <p><a data-toggle="modal" data-target="#login-wp" class="btn btn-white">Informasi Selengkapnya</a></p>
        </div>
      </div>
    </div>
    
    <div id='frontpage_content_container'>
	    <section id='startup_section' class='custom-frontpage-section'>
	      <div class="container clearfix">
	        <div class="row services">
	          <div class="col-md-12">
	            <h2 class="h1">Pelayanan Pajak Daerah Berbasis Kinerja Integrasi Data</h2>
	            <div class="row">
	              <div class="col-sm-4">
	                <div class="box box-services">
	                  <div class="icon"><i class="pe-7s-home"></i></div>
	                  <h4 class="heading">Self-Assessment</h4>
	                  <p>Pajak daerah <i>self-assessment</i> adalah pajak daerah yang dibayar sendiri oleh Wajib Pajak. Pajak daerah ini meliputi pajak rumah makan/restoran, hotel/tempat penginapan, lahan parkir dan tempat hiburan. Besarnya pajak ini telah ditentukan sebesar 10% dari omzet objek pajak.</p>
	                </div>
	              </div>
	              <div class="col-sm-4">
	                <div class="box box-services">
	                  <div class="icon"><i class="pe-7s-bookmarks"></i></div>
	                  <h4 class="heading">Official-Assessment</h4>
	                  <p>Pajak daerah <i>official-assessment</i> adalah pajak daerah yang dipungut berdasarkan Ketetapan Kepala Daerah. Pajak daerah ini meliputi pajak air tanah, reklame, dan PBB Pedesaan dan Perkotaan. Tarif yang diberlakukan untuk pajak ini telah sesuai dengan Perda/Perbup yang berlaku.</p>
	                </div>
	              </div>
	              <div class="col-sm-4">
	                <div class="box box-services">
	                  <div class="icon"><i class="pe-7s-paperclip"></i></div>
	                  <h4 class="heading">Penyelenggaraan BPHTB</h4>
	                  <p>Bea Perolehan Hak atas Tanah dan Bangunan (BPHTB) adalah pajak yang dikenakan atas perolehan hak atas tanah dan atau bangunan. Tarif yang dikenakan atas objek BPHTB adalah sebesar maksimal 5%, dan yang menjadi dasar pengenaan BPHTB adalah Nilai Perolehan Objek Pajak (NPOP).</p>
	                </div>
	              </div>
	            </div>
	            <h2 class="h1">Layanan Publik BKAD Kabupaten Kulon Progo</h2>
	            <div class="row">
	              <div class="col-sm-4">
	                <div class="box box-services">
	                  <div class="icon"><i class="pe-7s-monitor"></i></div>
	                  <h4 class="heading">Notaris/PPAT Online</h4>
	                  <p>Layanan Notaris/PPAT <i>Online</i> adalah layanan yang diperuntukkan bagi seluruh Notaris/PPAT di lingkup Kabupaten Kulon Progo dalam melaksanakan pendaftaran BPHTB secara <i>online</i>.</p>
	                </div>
	              </div>
	              <div class="col-sm-4">
	                <div class="box box-services">
	                  <div class="icon"><i class="pe-7s-signal"></i></div>
	                  <h4 class="heading">DHKP Online Desa / Kelurahan</h4>
	                  <p>Layanan Daftar Himpunan Ketetapan Pajak (DHKP) Online adalah layanan akses seluruh data PBB-P2 secara <i>real-time</i> yang diperuntukkan untuk seluruh wajib pajak, desa dan kelurahan di Kabupaten Temanggung.</p>
	                </div>
	              </div>
	              <div class="col-sm-4">
	                <div class="box box-services">
	                  <div class="icon"><i class="pe-7s-map-2"></i></div>
	                  <h4 class="heading">SIG Mapatda BKAD</h4>
	                  <p>Sistem Informasi Geografis (SIG) Mapatda BKAD adalah layanan informasi objek pajak berbasis peta dapat diakses secara publik.</p>
	                </div>
	              </div>
	            </div>
	          </div>
	        </div>
	      </div>
	    </section>
    </div>

    <footer class="footer">
      <div class="footer__copyright">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <p><?php echo(Date('Y'));?> Badan Keuangan dan Aset Daerah (BKAD) Kabupaten Kulon Progo</p>
            </div>
            <!-- div class="col-md-6">
              <p class="credit">Dibangun oleh <a href="http://bkad.kulonprogokab.go.id" class="external">BKAD Kabupaten Kulon Progo</a></p>
            </div -->
          </div>
        </div>
      </div>
    </footer>
    <!-- Javascript files-->
		<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
		<script>window.jQuery || document.write('<script src="./trulogix-cdn/js/jquery-1.12.4.min.js">\x3C/script>')</script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
		<script src="./trulogix-cdn/datatables/minimalist-alt/datatables.min.js"></script>
    <script src="./local-assets/js-obfuscated/frontpage.js"></script>
  </body>
</html>
<?php
} else {
	if (isset($_SESSION['desaXweb'])) {
		header("Location: ./app-desa/");
		exit;
	} elseif (isset($_SESSION['kecamatanXweb'])) {
		header("Location: ./app-kecamatan/");
		exit;
	} elseif (isset($_SESSION['camatXweb'])) {
		header("Location: ./app-camat/");
		exit;
	} elseif (isset($_SESSION['dhkpXweb'])) {
		header("Location: ./apps/");
		exit;
	} else {
		echo("Error is Session Management. Contact your System Administrator.");
		exit;
	}
}
?>
