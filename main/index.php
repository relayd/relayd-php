<?php

$debugging = true;
if ($debugging) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

define('ROOT_PATH', dirname(__DIR__));

ini_set('session.cookie_lifetime', 60 * 60 * 24 * 365);
ini_set('session.gc-maxlifetime', 60 * 60 * 24 * 365);
session_start();

$request_path = "/";
if (isset($_SERVER['SCRIPT_URL'])) {
    $request_path = $_SERVER['SCRIPT_URL'];
}
if (isset($_SERVER['PATH_INFO'])) {
    $request_path = $_SERVER['PATH_INFO'];
}

include ROOT_PATH . "/core/init.php";

// API only changes
header("Content-type: application/json");
// End API only changes

if (file_exists("./pages" . $request_path . ".php")) {
    include "./pages" . $request_path . ".php";
} else if (file_exists("./pages" . $request_path . "/index.php")) {
    include "./pages" . $request_path . "/index.php";
} else if (file_exists("./pages/404.php")) {
    include "./pages/404.php";
} else if (file_exists(ROOT_PATH . "/core/404.php")) {
    include ROOT_PATH . "/core/404.php";
}