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

?>