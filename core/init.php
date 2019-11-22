<?php

foreach (glob(ROOT_PATH . "/core/classes/*.php") as $filename) {
    include $filename;
}

$relaydConfig = array();

if (is_file(ROOT_PATH . "/core/custom/config.php")) {
    require_once ROOT_PATH . "/core/custom/config.php";
} else {
    die("The config was not found.");
}

// Fix IP of visitor behind Cloudflare
if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

$relayd = new Relayd($relaydConfig);