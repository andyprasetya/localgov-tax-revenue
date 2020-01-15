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
		exit;
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
		